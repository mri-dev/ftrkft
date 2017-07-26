<?
namespace PortalManager;

use TransactionManager\Transaction;
use MailManager\Mails;
use ExceptionManager\RedirectException;
/**
 * class Users
 *
 */
class User
{
	private $db = null;
	public $controller = null;
	public $smarty = null;

	public $id 	= false;
	public $user = false;

	function __construct( $user_id, $arg = array() ){
		$this->id = $user_id;

		if(isset($arg['controller'])) {
			$this->controller = $arg['controller'];
			$this->db = $this->controller->db;
			$this->settings = $this->controller->settings;
			$this->smarty = $this->controller->smarty;
		}

		$this->user = $this->get();

		return $this;
	}

	private function get( $arg = array() )
	{
		$ret 			= array();

		if(!$this->id) return false;

		$ret[data] 			= $this->getData( $this->id, 'ID' );
		$ret[email] 		= $ret[data][email];

		return $ret;
	}

	public function logged()
	{
		return ($this->user) ? true : false;
	}

	private function getData( $account_id, $db_by = 'email' ){
		if($account_id == '') return false;

		$q = "
		SELECT 			u.*
		FROM 			".\PortalManager\Users::TABLE_NAME." as u
		WHERE 			1 = 1 ";

		$q .= " and u.".$db_by." = '$account_id';";

		extract($this->db->q($q));

		// Details
		$det = $this->db->query("SELECT nev, ertek FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE fiok_id = $account_id;")->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($det as $d ) {
			$data[$d['nev']] = $d['ertek'];
		}

		return $data;
	}

	public function getUserGroup()
	{
		return (int)$this->user['data']['user_group'];
	}

	public function getUserGroupName()
	{
		$ug = (int)$this->user['data']['user_group'];
	}

	public function getValue( $key )
	{
		$v = $this->user['data'][$key];

		if( empty($v) && !$v ) return false;

		return $v;
	}

	public function isMunkaado()
	{
		$group = (int)$this->getUserGroup();

		if($group === (int)$this->settings['USERS_GROUP_MUNKAADO']) {
			return true;
		}
		return false;
	}

	public function isUser()
	{
		$group = (int)$this->getUserGroup();

		if($group === (int)$this->settings['USERS_GROUP_USER']) {
			return true;
		}
		return false;
	}

	public function getAccountData( $key )
	{
		return $this->user['data'][$key];
	}

	public function getName()
	{
		return $this->user['data']['name'];
	}

	public function getID()
	{
		return $this->user['data']['ID'];
	}

	public function getZipCode()
	{
		return $this->user['data']['zip_code'];
	}

	public function getEmail()
	{
		return $this->user['email'];
	}

	public function isAllowed()
	{
		return ($this->user['data']['engedelyezve'] == '1') ? true : false;
	}

	public function getPhone()
	{
		return $this->user['data']['telefon'];
	}

	public function getProfilImg()
	{
		return $this->getAccountData('profil_img');
	}

	public function changeProfilImg($img)
	{
		$previous = ltrim($this->getProfilImg(), '/');

		if (!empty($previous) && file_exists($previous)) {
			unlink($previous);
		}

		$this->editAccountDetail('profil_img', $img);
	}

	public function profilPercent()
	{
		$dataset = $this->user['data'];
		$req = array('email', 'name', 'szuletesi_datum', 'allampolgarsag', 'anyanyelv', 'csaladi_allapot', 'nem', 'profil_img', 'telefon', 'lakcim_irsz', 'lakcim_city', 'lakcim_uhsz',	'social_url_facebook', 'social_url_twitter', 'social_url_linkedin');
		$has = array();
		$current = 0;

		//
		foreach ($req as $k ) {
			$d = $dataset[$k];
			if (isset($d) && !empty($d)) {
				$has[] = $k;
			}
		}

		$total = count($req);
		$current = count($has);

		if($current == 0) return 0;

		return round(($current / $total) * 100);
	}

	public function getLastloginTime( $formated = false )
	{
		if( $formated ) {
			return \PortalManager\Formater::distanceDate($this->user['data']['utoljara_belepett']);
		} else {
			return $this->user['data']['utoljara_belepett'];
		}

	}

	public function getAccountModulData()
	{
		$moduls = array();

		if (!$this->getID() || $this->getID() == '') {
			return $moduls;
		}

		$data = $this->db->query("
		SELECT
			m.*
		FROM ".\PortalManager\Users::TABLE_MODULDATAS." as m
		WHERE 1=1 and
		m.fiok_id = ".$this->getID()."
		")->fetchAll(\PDO::FETCH_ASSOC);

		foreach ((array)$data as $d) {
			$keyname = $d['keyname'];
			$is_grouped = (is_null($d['groupedfor'])) ? false : true;

			if (!isset($moduls[$d['pagename']][$d['modulkey']][$d['sortindex']]['grouphash'])) {
				$moduls[$d['pagename']][$d['modulkey']][$d['sortindex']]['grouphash'] = $d['grouphash'];
			}

			if ($is_grouped) {
				$keyname = $d['groupedfor'];
				$subkey = str_replace($d['groupedfor'].'_','', $d['keyname']);
				$moduls[$d['pagename']][$d['modulkey']][$d['sortindex']][$keyname][$subkey] = array(
					'ID' => (int)$d['ID'],
					'hashkey' => $d['storehash'],
					'value' => (is_numeric($d['datavalue'])) ? (int)$d['datavalue'] : $d['datavalue'],
				);
			}else{
				$moduls[$d['pagename']][$d['modulkey']][$d['sortindex']][$keyname] = array(
					'ID' => (int)$d['ID'],
					'hashkey' => $d['storehash'],
					'value' => (is_numeric($d['datavalue'])) ? (int)$d['datavalue'] : $d['datavalue'],
				);
			}
		}

		return $moduls;
	}

	public function removeModulDatas($group_hashkeys = array())
	{
		if (!is_array($group_hashkeys) || empty($group_hashkeys)) {
			return false;
		}

		foreach ((array)$group_hashkeys as $grouphash) {
			$this->db->query("DELETE FROM ".\PortalManager\Users::TABLE_MODULDATAS." WHERE fiok_id = ".$this->getID()." and grouphash = '".$grouphash."'");
		}
	}

	public function saveProfilModulDatas( $data )
	{
		if (!is_array($data) || empty($data)) {
			return false;
		}

		$modulinsert = array();
		foreach ((array)$data as $modulkey => $set) {
			foreach ((array)$set as $i => $row) {
				$grouphash = uniqid();
				foreach ($row as $key => $value) {
					$page = 'vegzettseg';

					if (is_array($value)) {
						foreach ($value as $vkey => $vvalue) {
							if($key == 'grouphash') continue;
							$hash = md5($this->getID().'_'.$page.'_'.$modulkey.'_'.$key.'_'.$vkey.'_'.$i);
							$modulinsert[] = array(
								$this->getID(),
								$grouphash,
								$hash,
								$page,
								$modulkey,
								$key.'_'.$vkey,
								$vvalue,
								$key,
								$i
							);
						}
					} else {
						$hash = md5($this->getID().'_'.$page.'_'.$modulkey.'_'.$key.'_'.$i);
						if($key == 'grouphash') continue;
						$modulinsert[] = array(
							$this->getID(),
							$grouphash,
							$hash,
							$page,
							$modulkey,
							$key,
							$value,
							NULL,
							$i
						);
					}
				}
			}
		}

		if (!empty($modulinsert)) {
			$this->db->multi_insert(
				\PortalManager\Users::TABLE_MODULDATAS,
				array('fiok_id', 'grouphash', 'storehash', 'pagename', 'modulkey', 'keyname', 'datavalue', 'groupedfor', 'sortindex'),
				$modulinsert,
				array(
					'duplicate_keys' => array('storehash', 'datavalue', 'sortindex')
				)
			);
		}
	}

	public function saveProfil( $profils = array(), $details = array() )
	{
		if(empty($profils)) return false;

		// profil
		$this->db->update(
			\PortalManager\Users::TABLE_NAME,
			$profils,
			sprintf("ID = %d", $this->getID())
		);

		// details
		foreach ((array)$details as $key => $value) {
			$this->editAccountDetail($key, $value);
		}

		return $saved;
	}

	public function getRegisterTime( $formated = false )
	{
		if( $formated ) {
			return \PortalManager\Formater::distanceDate($this->user['data']['regisztralt']);
		} else {
			return $this->user['data']['regisztralt'];
		}
	}

	public function sendEmail( $message, $email_template, $arg = array(), $from = false )
	{
		$this->checkLanguageFiles();
		$this->checkSmarty();

		if( empty($message) ) {
			$this->error( $this->lang['lng_users_form_sendmessage_miss_message'] );
		}

		$arg['message'] 	= $message;
		$arg['from_name'] 	= $from['name'];
		$arg['from_email']	= $from['email'];
		$arg['infoMsg'] 	= $this->lang['lng_mail_sendth_jobabc'];

		$mail = new Mails( $this, $email_template, $this->getEmail(), $arg );

		$mail->send();
	}

	private function editAccountDetail( $key, $value )
	{
		$account_id = $this->getID();

		if( !$account_id ) return false;

		$check = $this->db->query("SELECT id FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE fiok_id = ".$account_id." and nev = '".$key."';");

		if( $check->rowCount() !== 0 ) {
			$this->db->update(
				\PortalManager\Users::TABLE_DETAILS_NAME,
				array(
					'ertek' 			=> $value
				),
				sprintf( "fiok_id = %d and nev = '%s'", $account_id, $key)
			);
		} else {
			$this->db->insert(
				\PortalManager\Users::TABLE_DETAILS_NAME,
				array(
					'fiok_id' 	=> $account_id,
					'nev' 			=> $key,
					'ertek' 		=> $value
				)
			);
		}
	}

	private function error( $msg )
	{
		throw new RedirectException( $msg, $_POST['form'], $_POST['return'], $_POST['session_path'] );
	}

	public function checkLanguageFiles()
	{
		if( empty($this->lang) ) die(__CLASS__.': '.'Hiányoznak a nyelvi fájlok.');
	}
	public function checkSmarty()
	{
		if( empty($this->smarty) ) die(__CLASS__.': '.'Hiányzik a Smarty controller');
	}

	public function __destruct()
	{
		$this->db = null;
		$this->smarty = null;
		$this->user = false;
	}
}

?>
