<?
	class Helper {

		static function GET(){
			$b = explode("/",rtrim($_GET[tag],"/"));
			if($b[0] == null){ $b[0] = 'home'; }
			return $b;
		}
		static function getArrayValueByMatch($data, $prefix){

			$return = array();
			foreach($data as $dk => $dv){
				if(strpos($dk,$prefix) === 0){
					$return[str_replace($prefix,'',$dk)] = $dv;
				}
			}

			return $return;
		}

		static function replaceGetValue( $replaces = array() )
		{
			$newget= '';
			unset($_GET['tag']);

			$urlset = $_SERVER['REQUEST_URI'];

			$xexp = explode( "?", $urlset );

			$newget = $xexp[0].'?';

			foreach ( $_GET as $key => $value ) {
				if( array_key_exists($key,$replaces) ) {
					$value = $replaces[$key];
				}
				$newget .= $key."=".$value."&";
			}

			$newget = rtrim($newget,"&");

			return $newget;

		}

		static function cookieValueSet( $key, $sep = ',' )
		{
			$set = $_COOKIE[$key];
			$set = rtrim($set, $sep);
			$set = ltrim($set, $sep);

			if( empty($set[0]) ) return array();

			return explode( $sep, $set );
		}

		static function getIDfromHashstring( $hash ){
			$xhash = explode("_-",$hash);
			$id = (int)end($xhash);

			if( !is_numeric($id) || $id === 0 ) return false;

			return $id;
		}

		static function currentPageNum(){
		  $num 	= 0;
		  $last = self::getLastParam();

		  $num 	= (is_numeric($last)) ? $last : 1;

		  return $num;
		}

		static function getParam($arg = array()){
			$get = self::GET();

			if(!empty($arg)){
				$pos = 2;
				foreach($arg as $ar){
					if($get[$pos] != null){
						$param[$ar] = $get[$pos];
						$pos++;
					}else{ break; }
				}
			}else{
				$pos = 0;
				foreach($get as $g){
					if($pos > 1){
						$param[] = $g;
					}
					$pos++;
				}
			}

			return $param;
		}

		static function replaceMonths( $str, $lang = 'hu') {
			if( $lang != 'hu' ) {
				return $str;
			}

			if( $lang == 'hu' ) {
				$str = str_replace(
					array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ),
					array( 'január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december' ) ,
					$str
				);
				return $str;
			}
		}

		static function getLastParam(){
			$p = self::GET();
			$p = array_reverse($p);
			return $p[0];
		}

		static function cashFormat($cash){
			$cash = number_format($cash,0,""," ");
			return $cash;
		}

		static function makeSafeUrl($str,$after = '', $keep_space = true ){
			$f 		= array(' ',',','á','Á','é','É','í','Í','ú','Ú','ü','Ü','ű','Ű','ö','Ö','ő','Ő','ó','Ó','(',')','\'','"',"=","/","\\","?","&","!",".",":",'%');
			$t 		= array('-','','a','a','e','e','i','i','u','u','u','u','u','u','o','o','o','o','o','o','','','','','','','','','','','','','');
			$str 	= str_replace($f,$t,$str);
			$str 	= strtolower($str);

			if( !$keep_space ) {
				$str = str_replace(array('-'),'', $str);
			}

			$ret = $str . $after;
			return $ret;
		}

		static function arry(&$array, $key, $type = 'ASC') {
		    $sorter=array();
			$ret=array();
				reset($array);
			foreach ($array as $ii => $va) {
				$sorter[$ii]=$va[$key];
			}
			asort($sorter);
			foreach ($sorter as $ii => $va) {
				$ret[$ii]=$array[$ii];
			}
			$array=$ret;
			if($type == "DESC"){
				$array = array_reverse($array);
			}
		}

		static function dellFromArrByVal($arr,$dell){
			if(($key = array_search($dell, $arr)) !== false) {
			    unset($arr[$key]);
			}

			return $arr;
		}

		static function dellFromArrByAssocVal($arr,$by,$v){
			foreach($arr as $key => $ar){
				if($ar[$by] == $v){
					$rmkey = $key;
				}
			}

			unset($arr[$rmkey]);

			return $arr;
		}

		static function getFromArrByAssocVal($arr,$by,$val){
			foreach($arr as $key => $ar){
				if($ar[$by] == $val){
					$rmkey = $key;
				}
			}

			return $rmkey;
		}

		public static function setMashineID(){
			if(self::getMachineID() == ""){
				setcookie('__mid',mt_rand(),time() + 60*60*24*365*2,"/");

				if($_COOKIE['__mid'] != ""){
					header('Location: ');
				}
			}
		}

		public static function getMachineID(){
			return $_COOKIE['__mid'];
		}

		static function softDate($d){
			if($d == '0000-00-00 00:00:00' || is_null($d)){ return 'n.a.'; }
			return str_replace("-","/",substr($d,0,-3));
		}

		static function getSecureUrlKey($anc = ''){
			$anc = ($anc != "") ? '#'.$anc : '';
			$s = base64_encode(substr(DOMAIN,0,-1).$_SERVER['REQUEST_URI'].$anc);
			return $s;
		}

		static function getPrevPage(){
			$url = $_SERVER['REQUEST_URI'];
			$xurl = explode("/",trim($url,"/"));
			$xurl = array_reverse($xurl);
			$url = str_replace($xurl[0].'/',"",$url);
			return $url;
		}

		static function safeEmail($email){
			$email = str_replace(array('@','.'),array(' <em>(kukac)</em> ',' <em>(pont)</em> '),$email);
			return $email;
		}

		static function distanceDate($date = NOW, $from = false){
			if($date == '0000-00-00 00:00:00'){ return 'sose'; }
			$now 		= ( $from ) ? strtotime( $from ) : strtotime(NOW);
			$date 		= strtotime($date);
			$mode 		= 'past';
			if($date < $now){
				$dif_sec =  $now - $date ;
			}else{
				$mode = 'future';
				$dif_sec =  $date - $now ;
			}

			$ret 		= array( 'num' => 0, 'type' => 'másodperc' );
			///////////////////////////////
			$perc 	= 60;
			$ora 	= $perc * 60;
			$nap 	= $ora * 24;
			$honap 	= $nap * 30;
			$ev 	= $honap * 12;
			///////////////////////////////
				switch($mode){
					case 'past':
						if($dif_sec <= $perc){ // Másodperc
							$ret[num] 	= (int) $dif_sec;
							$ret[type] 	= 'másodperc';
						}else if($dif_sec > $perc && $dif_sec <= $ora){ // Perc
							$ret[num] 	= (int) floor($dif_sec / $perc);
							$ret[type] 	= 'perc';
						}else if($dif_sec > $ora && $dif_sec <= $nap){ // Óra
							$ret[num] 	= (int) floor($dif_sec / $ora);
							$ret[type] 	= 'óra';
						}else if($dif_sec > $nap && $dif_sec <= $honap){ // Nap
							$np = floor($dif_sec / $nap);
							$ret[num] 	= (int) $np;
							$ret[type] 	= 'nap';
						}
					break;
					case 'future':
						if($dif_sec <= $perc){ // Másodperc
							$ret[num] 	= (int) $dif_sec;
							$ret[type] 	= 'másodperc';
						}else if($dif_sec > $perc && $dif_sec <= $ora){ // Perc
							$ret[num] 	= (int) floor($dif_sec / $perc);
							$ret[type] 	= 'perc';
						}else if($dif_sec > $ora && $dif_sec <= $nap){ // Óra
							$ret[num] 	= (int) floor($dif_sec / $ora);
							$ret[type] 	= 'óra';
						}else if($dif_sec > $nap && $dif_sec <= $honap){ // Nap
							$ret[num] 	= (int) floor($dif_sec / $nap);
							$ret[type] 	= 'nap';
						}
					break;
				}


			return $ret;
		}

		static function getMonthByNum($mnum){
			$re = $mnum;
				switch($mnum){
					case 1:
						$re = __('január');
					break;
					case 2:
						$re = __('február');
					break;
					case 3:
						$re = __('március');
					break;
					case 4:
						$re = __('április');
					break;
					case 5: case 'May':
						$re = __('május');
					break;
					case 6:
						$re = __('junius');
					break;
					case 7:
						$re = __('július');
					break;
					case 8:
						$re = __('augusztus');
					break;
					case 9:
						$re = __('szeptember');
					break;
					case 10:
						$re = __('október');
					break;
					case 11:
						$re = __('november');
					break;
					case 12:
						$re = __('december');
					break;

				}
			return $re;
		}

		static function get_extension($file_name){
			$ext = explode('.', $file_name);
			$ext = array_pop($ext);
			return strtolower($ext);
		}

		static function getPercent($max, $stat){
			$perc = 0;
			$perc = $stat / ($max / 100);

			if(!filter_var($perc, FILTER_VALIDATE_INT)){
				$perc = number_format($perc,1,'.','');
			}

			return $perc;
		}

		static function reload($to = ''){
			$to = ($to == '') ? $_SERVER['HTTP_REFERER'] : $to;
			header('Location: '.$to); exit;
		}


        static function shuffleAssocArr($list, $arg = array()){
            if (!is_array($list)) return $list;

            $keys = array_keys($list);
            $random = array();
            if(is_array($arg[step]) && count($arg[step]) > 0){
                foreach($arg[step] as $stp){
                    if($stp != '' && array_key_exists($stp,$list)){
                        $random[$stp] = $list[$stp];
                        unset($keys[$stp]);
                    }
                }
            }

            shuffle($keys);

            foreach ($keys as $key) {
                $random[$key] = $list[$key];
            }
            return $random;
        }
	}
?>
