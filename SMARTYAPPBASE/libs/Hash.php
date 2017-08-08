<?
class Hash{
	/**
	 *
	 * Bizonságos jelszó generálás
	 * @param string $data // Kódolandó anyag
	 */
	public static function create($data){
		if($data != ""){
			$key = hash_init('md5',HASH_HMAC,SKEY);
			hash_update($key, $data);

			return hash_final($key);
		}else{
			return false;
		}
	}

	public static function loadRememberMeHash( $cookie_key = '__arem' )
	{
		if (isset($_COOKIE[$cookie_key])) {
			$cookie = $_COOKIE[$cookie_key];
		} else return false;
		$cookie = json_decode(base64_decode($cookie, true), true);

		return $cookie;
	}

	public static function jelszo($str){
		$kstr = self::create($str);
		return $kstr;
	}
}
?>
