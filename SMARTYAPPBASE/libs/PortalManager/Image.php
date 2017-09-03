<?php
namespace PortalManager;

/**
* class Image
* @package PortalManager
* @version v1.0
*/
class Image
{
	private $image = null;
	public static $lang = null;

	const ORIENTATION_LANDSCAPE = 1;
	const ORIENTATION_PORTRAIT = 2;
	const ORIENTATION_CUBE = -1;

	function __construct( $image, $arg = array() ) {
		if ( !file_exists( $image ) ) {
			throw new \Exception( __CLASS__."(): A megadott képfájl nem létezik vagy nem olvasható: ".$image );
		}

		$this->image = $image;

		return $this;
	}

	/*
		$arg
			+ src[String]				: A forrása a feltöltött fájloknak ($_FILE[?])
			+ upDir[String]				: A képek mentésének helye.
			- required[0|1] 			: Kötelező a kép feltöltése, vagy sem. (1 = kötelező)
			- text[String]				: A feltöltött mező referencia neve hibaüzenetben.
			- noRoot[true|false-] 		: true -> nem sorolja be almappákba
			- makeThumbImg[true|false-] : true -> készítsen bélyegképeket
			- makeWaterMark[true|false-]: true -> készítsen vízjelet a képre
			- fileName[String]  		: true Str -> A kép neve ez lesz.
			- maxFileSize[Int] 			: Maximálisan engedélyezett fájlméret képenként.
	        - imgLimit[Int]             : Max. ennyi fájlt tölt fel.

	*/
	public static function upload($arg = array()){
		$si 	= $arg[src];
		$file 	= true;
		$dir 	= $arg[upDir];
		$src 	= $_FILES[$si];
		$noDir  = false;

		self::$lang = $arg['lang'];

		if($arg[mod] == "add"){ // Kiegészítő
			$dir = $arg[tDir];
		}


		if($dir == null || $dir == ""){
			$dir = substr(IMG,1)."uploads/";
			$noDir = true;
		}

		if(!file_exists($dir)){
			if($arg[mod] != "add"){
				throw new \Exception(sprintf(self::$lang['lng_image_root_not_valid'], $dir));
			}else if($arg[mod] == "add"){
				$dir = substr(IMG,1)."uploads/";
				$noDir = true;
			}
		}

		if($src[size][0] == 0){
			if($arg[mod] != "add"){
				if($arg[required] == 1){
					throw new \Exception(sprintf(self::$lang['lng_image_notfile_uploaded'], $arg[text]));
				}else{
					$file = false;
				}
			}
		}else{
		// Ellenőrzése
			$te         = -1;
            $errOfType  = '';
            $fn         = 0;
			foreach($src[type] as $ftt){ $te++; $fn++;
				// Fájlformátum ellenőrzés
				if( !in_array($ftt, array( 'image/jpg', 'image/png', 'image/jpeg' )) ){
					$errOfType  .= 	sprintf(self::$lang['lng_image_not_valid_fileformat'], 'jpg, png', $src[name][$te]);
				}

				// Fájlméret ellenőrzés - ADD: 2013/07
				if($arg[maxFileSize]){
					$fileKb = ceil($src[size][$te] / 1024);
					if($fileKb > $arg[maxFileSize]){
						$errOfType  .= 	sprintf(self::$lang['lng_image_not_allowed_filesize'], $fileKb, $arg[maxFileSize], $src[name][$te]);
					}
				}
			}

            // Darabszám
            if($arg[imgLimit] > 0){
                if($fn > $arg[imgLimit]){
                    $errOfType  .= 	sprintf(self::$lang['lng_image_not_allowed_filenumber'], $arg[imgLimit]);
                }
            }

			if($errOfType != ''){ throw new \Exception($errOfType); }

		// Minden szükséges adat megvan
			if($arg[mod] != "add" || $noDir){
				#Random mappa név
					if(!$arg[noRoot]){ $dh 	= self::sHash(7); }
				# Feltötendő képek mappája
					$updir 	= $dir.$dh.'/';

				if(!file_exists($updir)){
					mkdir($updir,0777);
					chmod($updir,0777);
				}
			}else{
				$updir = $dir;
			}

			$p          = 0;
            $allFiles   = array();

			foreach($src[tmp_name] as $tmp){
				usleep(020000); // 0.2 mp várakozás
				$mt = explode(" ",str_replace(".","",microtime()));
				$fln = (!$arg[fileName]) ? self::sHash(7).$mt[0].'.jpg' : $arg[fileName];

				if($p == 0){$ffile = $fln;}

				move_uploaded_file($tmp,$updir.$fln);

				// Bélyegképek
				if($arg[makeThumbImg]){
					self::makeThumbnail($updir.$fln,$updir, $fls, 'thb150_', 150);
					self::makeThumbnail($updir.$fln,$updir, $fls, 'thb75_', 75);
				}

				// Vízjelezés
				if($arg[makeWaterMark]){
					$kep = $updir.$fln;
					self::makeWatermarkedImage(WATERMARK_IMG,$kep,'középen');
				}

				$p++;
                $allFiles[] = $updir.$fln;
			}
			$file = true;
		}


		if($file){
			$back = array(
				"dir" 	=> $updir,
				"file" 	=> $updir.$ffile,
                "allUploadedFiles" => $allFiles
			);
			return $back;
		}else{ return false; }
	}

	private static function sHash($n = 7){
		return substr(md5(microtime()),0,$n);
	}

	private static function makeThumbnail($src, $dir, $name, $pref, $maxWidth){
		// Alap műveletek
			# Forrás fájl másolása
			copy($src,$dir.$pref.$name.'.jpg');
			# Forrás kép elérése
			$src 			= $dir.$pref.$name.'.jpg';
			# Virtuálos kép létrehozás
			$wi 			= imagecreatefromjpeg($src);
			# Kép méreteinek beolvasása
			list($iw,$ih) 	= getimagesize($src);

		// Méretarányos méretcsökkentés
		$dHeight = floor($ih * ($maxWidth / $iw));

		// Kép módosító
  		$vi = imagecreatetruecolor($maxWidth, $dHeight);
  		imagecopyresampled($vi, $wi, 0, 0, 0, 0, $maxWidth, $dHeight, $iw, $ih);

		// Módosítások érvényesítése / Output
		imagejpeg($vi,$dir.$pref.$name.'.jpg',85);

		// Temponális változók eltávolítása
		imagedestroy($vi);
	}

	static function makeWatermarkedImage($wmk, $file, $pos){
		if($wmk != ""){
			$fln = basename($file);
			$ext = explode(".",$fln);
			if($ext[1] == "jpg"){
				// Eredeti kép
				$kep 			= imagecreatefromjpeg($file);
				list($kx,$ky) 	= getimagesize($file);

				// Vízjel
				$wm 			= imagecreatefrompng($wmk);
				list($wmw,$wmh) = getimagesize($wmk);
				$wmpos 			= $pos;

				switch($wmpos){
					case 'bal-fent';
						$x = 5;
						$y = 5;
					break;
					case 'bal-lent';
						$x = 5;
						$y = $ky - $wmh -5;
					break;
					case 'jobb-fent';
						$x = $kx - $wmw -5;
						$y = 5;
					break;
					case 'jobb-lent';
						$x = $kx - $wmw -5;
						$y = $ky - $wmh -5;
					break;
					case 'középen';
						$x = ($kx / 2) - ($wmw / 2);
						$y = ($ky / 2) - ($wmh / 2);
					break;
				}

				imagecopy($kep,$wm,$x,$y,0,0,$wmw,$wmh);
				imagejpeg($kep,$file,100);
				imagedestroy($kep);
			}
		}
	}

	public function orientation()
	{
		list( $w, $h ) = getimagesize( $this->image );

		if ( $w > $h ) {
			return self::ORIENTATION_LANDSCAPE;
		} else if( $w < $h ) {
			return self::ORIENTATION_PORTRAIT;
		} else if( $w == $h ) {
			return self::ORIENTATION_CUBE;
		}
	}
}
?>
