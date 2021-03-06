<?
namespace PortalManager;

use ExceptionManager\RedirectException;
use PortalManager\Admin;
/**
* class Admins
* @package PortalManager
* @version v1.0
*/
class Admins
{
	const ORDER_STATUS_KEY_DONE 		= 4;
	const ORDER_STATUS_KEY_DEFAULT 		= 1;
	const ORDER_STATUS_KEY_DELETED 		= 13;
	const SUPER_ADMIN_PRIV_INDEX		= \PortalManager\Admin::SUPER_ADMIN_PRIV_INDEX;

	public $admin 						= null;
	public $admin_id 					= false;
	public $admin_jog 				= 100; 	// Szuper admin = 0, alapé.: 100

	private $db;
	private $settings;
	private $arg;


	public function __construct( $arg = array() ){
		$this->db = $arg[db];
		$this->settings = $arg[view][settings];
		$this->arg = $arg;

		switch( $this->settings['admin_login_mode'] ){
			case 'session':
				$this->admin = $_SESSION[adm];
			break;
			default: case 'cookie':
				$this->admin = $this->getAdminByCookieToken($_COOKIE[__admin]);
			break;
		}

		$this->getAdminStatus();
	}

	public function get()
	{
		if( !$this->admin_id ) return false;

		return new Admin( $this->admin_id, $this->arg );
	}

	private function setCookieToken($admin){
		$token = md5(time());
		$this->db->update("admin",
		array(
			"valid_cookie_token" => $token
		),
		"user = '$admin'");

		return $token;
	}

	public function getAdminStatus(){
		if( !$this->admin ) return true;

		$data = $this->db->query("SELECT ID, engedelyezve, jog FROM admin WHERE user = '$this->admin'")->fetch(\PDO::FETCH_ASSOC);

		$this->admin_jog 	= (int)$data['jog'];
		$this->admin_id 	= (int)$data['ID'];

		return ($data['engedelyezve'] == 1) ? true : false;
	}

	public function getAdminByCookieToken($token){
		$admin = $this->db->query("SELECT user FROM admin WHERE valid_cookie_token = '$token'")->fetch(\PDO::FETCH_COLUMN);

		return $admin;
	}

	private function getCookieToken($token){
		$admin = $this->db->query("SELECT user FROM admin WHERE valid_cookie_token = '$token'")->fetch(\PDO::FETCH_COLUMN);

		return $admin;
	}

	public function lista( $arg = array() )
	{
		$q = "SELECT
			*
		FROM admin
		ORDER BY utoljara_belepett DESC
		";

		$arg['multi'] = 1;
		extract($this->db->q($q, $arg));

		return $data;
	}

	function isLogged(){
		if(isset($this->admin) && $this->admin != ''){
			if( $this->settings['admin_login_mode'] == 'cookie'){
				setcookie("__admin",$_COOKIE[__admin],time()+60*60*24,"/");
			}

			$this->db->query("UPDATE admin SET utolso_aktivitas = now() WHERE user = '$this->admin'");

			return true;
		}else{
			return false;
		}
	}

	public function saveSettings( $post )
	{
		foreach ( $post as $key => $value ) {
			$this->db->query("UPDATE settings SET bErtek = '$value' WHERE bKulcs = '$key';");
		}
	}

	public function saveServiceBasic( $post )
	{
		if( count($post['settings']) > 0 ) {
			foreach ($post['settings'] as $key => $value) {
				$q = "UPDATE settings SET bErtek = '".$value."' WHERE bKulcs = '$key';";
				$this->db->query( $q );
			}
		}
	}

	function logout(){
		unset($_SESSION[adm]);
		setcookie("__admin","",time()-3600,"/");
	}

	function login($post){
		extract($post);

		if($user == '') $this->error('Bejelentkezési azonosító megadása kötelező!');
		if($pw == '') 	$this->error('Bejelentkezési jelszó megadása kötelező!');

		$pw = \Hash::jelszo($pw);

		$iq = "SELECT engedelyezve FROM admin WHERE user = '$user' and pw = '$pw'";

		$q = $this->db->query($iq);

		if($q->rowCount() > 0){
			if($q->fetch(\PDO::FETCH_COLUMN) == 0) {
				$this->error('A fiók korlátozásra került.');
			}
			switch($this->settings['admin_login_mode']){
				case 'session':
					\Session::set('adm',$user);
				break;
				default: case 'cookie':
					setcookie("__admin",$this->setCookieToken($user),time()+60*60*24,"/");
				break;
			}

			$this->db->query(sprintf("UPDATE admin SET utoljara_belepett = now() WHERE user = '%s'", $user));


		}else{
			$this->error('Nincs ilyen adminisztrátor. Próbáld újra!');
		}
	}

	private function error( $msg )
	{
		throw new RedirectException( $msg, $_POST['form'], $_POST['return'], $_POST['session_path'] );
	}


	public function __destruct()
	{
	 	$this->db = null;
	}
}
?>
