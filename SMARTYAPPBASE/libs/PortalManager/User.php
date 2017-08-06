<?
namespace PortalManager;

use TransactionManager\Transaction;
use MailManager\Mails;
use ExceptionManager\RedirectException;
use FlexTimeResort\UserCVPreparer;
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
	public $cvHandler = false;

	function __construct( $user_id, $arg = array() ){
		$this->id = $user_id;

		if(isset($arg['controller'])) {
			$this->controller = $arg['controller'];
			$this->db = $this->controller->db;
			$this->settings = $this->controller->settings;
			$this->smarty = $this->controller->smarty;
		}

		$this->user = $this->get();

		if (isset($arg['includeCVHandler']) && $arg['includeCVHandler'] === true) {
			$this->cvHandler = new UserCVPreparer($this, array('controller' => $this->controller));
		}

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

	public function cv()
	{
		if ($this->cvHandler) {
			return $this->cvHandler;
		}

		return false;
	}

	public function convertProfilRAWData($key, $data = false)
	{
		//echo $key;
		switch ($key) {
			case 'user_group':
				$data = (int)$data;
				switch ($data) {
					case $this->settings['USERS_GROUP_USER']:
						$data = $this->controller->lang('Munkavállaló');
					break;
					case $this->settings['USERS_GROUP_MUNKAADO']:
						$data = $this->controller->lang('Munkaadó');
					break;
				}
			break;
			case 'engedelyezve':
				$data = ((int)$data === 1) ? $this->controller->lang('Igen') : $this->controller->lang('Nem');
			break;
			case 'profil_img':
				$data = "<a href='".$data."'><img style='max-height: 50px;' src='".$data."' /></a>";
			break;
			case 'fizetesi_igeny':
				if (!empty($data) && $data != 0) {
					$data = number_format((int)$data, 0, ""," "). ' HUF ('.$this->controller->lang('bruttó').')';
				}
			break;
			case 'social_url_twitter':
			case 'social_url_linkedin':
			case 'social_url_facebook':
				$data = '<a target="_blank" href="'.$data.'">'.$data.'</a>';
			break;
			case 'jogositvanyok':
			case 'elvaras_munkateruletek':
			case 'elvaras_munkakorok':
			case 'megyeaholdolgozok':
			case 'munkatapasztalat':
			case 'anyanyelv':
			case 'nem':
			case 'allampolgarsag':
			case 'iskolai_vegzettsegi_szintek':

				switch ($key) {
					case 'elvaras_munkateruletek':
					case 'elvaras_munkakorok':
						$key = 'munkakorok';
					break;
					case 'megyeaholdolgozok':
						$key = 'megyek';
					break;
				}

				if (is_array($data)) {
					$termv = $this->getTermValues($key, $data, true);

					if ($termv) {
						$data = '';
						foreach ((array)$termv as $t) {
							$data .= $t['neve'].', ';
						}
						$data = rtrim($data,', ');
					}
				} else if(is_numeric($data)) {
					$termv = $this->getTermValues($key, (int)$data);

					if ($termv) {
						$data = $termv['neve'];
					}
				}
			break;
		}


		if ($data !== 0 && ($data == '' || empty($data))) {
			$data = '--';
		}

		return $data;
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
		$det = $this->db->query("SELECT nev, ertek, datatype FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE fiok_id = $account_id;")->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($det as $d ) {
			if($d['datatype'] == 'single') {
				$data[$d['nev']] = $d['ertek'];
			} else{
				$data[$d['nev']][] = (is_numeric($d['ertek'])) ? (int)$d['ertek']: $d['ertek'];
			}
		}

		return $data;
	}

	public function getBirthDate()
	{
		return $this->user['data']['szuletesi_datum'];
	}

	public function getUserGroup()
	{
		return (int)$this->user['data']['user_group'];
	}

	public function getUserGroupName()
	{
		$ug = (int)$this->user['data']['user_group'];
	}

	public function getCVUrl()
	{
		return '/u/'.$this->getID().'/'.\Helper::makeSafeUrl($this->getName());
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

	public function getSzakmaText()
	{
		return $this->user['data']['szakma_text'];
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

	public function getAddress()
	{
		$addr = '';

		if (!empty($this->user['data']['lakcim_irsz'])) {
			$addr .= $this->user['data']['lakcim_irsz']." ";
		}

		if (!empty($this->user['data']['lakcim_city'])) {
			$addr .= $this->user['data']['lakcim_city'];
		}

		if (!empty($this->user['data']['lakcim_uhsz'])) {
			$addr .= ", ".$this->user['data']['lakcim_uhsz'];
		}

		return $addr;
	}

	public function getNeme( $re = 'neve' )
	{
			$term = $this->getTermValues('nem', (int)$this->getValue('nem'));

			return $term[$re];
	}

	public function getProfilImg()
	{
		$profil = $this->getAccountData('profil_img');

		$neme = (int)$this->getValue('nem');

		switch ($neme) {
			default: case 16:
				$gender = 'male';
			break;
			case 17:
				$gender = 'female';
			break;
		}

		if ($profil) {
			return $this->getAccountData('profil_img');
		} else {
			return IMG.'no-profil-'.$gender.'.svg';
		}
	}

	public function changeProfilImg($img)
	{
		$previous = ltrim($this->getProfilImg(), '/');

		if (!empty($previous) && file_exists($previous)) {
			unlink($previous);
		}

		$this->editAccountDetail('profil_img', $img);
	}

	public function getTermValues($term, $values, $must_multiple = false)
  {
    if (is_array($values)) {
      $where = " and ID IN(".implode($values,',').")";
    } else {
      $where = " and ID = '".$values."'";
    }
    $data = $this->db->query($iq = "SELECT ID, neve, langkey, szulo_id FROM terms WHERE groupkey = '".$term."'".$where)->fetchAll(\PDO::FETCH_ASSOC);

    if (count($data) == 1 && !$must_multiple) {
      if(!is_null($data[0]['szulo_id'])) {
        $parent = $this->getTermValues($term, (int)$data[0]['szulo_id']);
      }
      if($parent){
         $data[0]['parent'] = $parent;
      }
      $text = $data[0];
    } elseif(count($data) > 1 || $must_multiple) {
      $text = array();
      foreach ((array)$data as $d) {
        if(!is_null($d['szulo_id'])) {
          $parent = $this->getTermValues($term, (int)$d['szulo_id']);
        }
        if($parent){
           $d['parent'] = $parent;
        }

        $text[$d['ID']] = $d;
      }
    }

    return $text;
  }

	public function getOneletrajz()
	{
		$data = $this->db->query("SELECT * FROM documents WHERE fiok_id = ".$this->getID()." and groupkey = 'oneletrajz'")->fetch(\PDO::FETCH_ASSOC);
		if (empty($data)) {
			return false;
		}
		$data['file_size'] = number_format((float)$data['file_size'] / 1024, 2, ".", " ");
		$data['file_type'] = str_replace(array('application/'), '', $data['file_type']);
		return $data;
	}

	public function getDocuments()
	{
		$data = $this->db->query("SELECT * FROM documents WHERE fiok_id = ".$this->getID()." and groupkey = 'dokumentum' ORDER BY upload_at DESC")->fetchAll(\PDO::FETCH_ASSOC);
		if (empty($data)) {
			return false;
		}

		$b = array();
		foreach ((array)$data as $d) {
			$d['file_type'] = \PortalManager\Formater::extensionFormater($d['file_type']);
			$d['upload_at'] = \PortalManager\Formater::dateFormat($d['upload_at'], 'Y. m. d. H:i');
			$d['file_size'] = \PortalManager\Formater::filesizeFormater((float)$d['file_size']);

			$b[] = $d;
		}
		unset($data);

		return $b;
	}

	public function multipleDocsUploadRegister($paths, $filedatas = array(), $infos = array() )
	{
		$docs = array();
		foreach ((array)$paths as $i => $path) {
			$hash = md5('docs_'.$filedatas['tmp_name'][$i].'_'.$this->getID());
			$type = ($filedatas['type'][$i]) ? $filedatas['type'][$i] : NULL;
			$docs[] = array($hash, $this->getID(), $infos[$i]['name'], $path, 'dokumentum', (float)$filedatas['size'][$i], $type);
		}

		if (!empty($docs)) {
			$this->db->multi_insert(
				'documents',
				array('hashkey', 'fiok_id', 'name', 'filepath', 'groupkey', 'file_size', 'file_type'),
				$docs,
				array(
					'duplicate_keys' => array('hashkey', 'filepath', 'file_size', 'file_type')
				)
			);
		}
	}

	public function updateOneletrajz($path, $filedata = array())
	{
		$docs = array();

		// previous delete
		$prev_file = $this->db->query("SELECT filepath FROM documents WHERE fiok_id = ".$this->getID()." and groupkey = 'oneletrajz'")->fetchColumn();
		if (!empty($prev_file)) {
			if (file_exists(REALPATH_APP.substr($prev_file, 1))) {
				unlink(REALPATH_APP.substr($prev_file, 1));
			}
		}

		$hash = md5('oneletrajz_'.$this->getID());
		$type = ($filedata['type']) ? $filedata['type'] : NULL;
		$docs[] = array($hash, $this->getID(), 'Önéletrajz', $path, 'oneletrajz', (float)$filedata['size'], $type);

		$this->db->multi_insert(
			'documents',
			array('hashkey', 'fiok_id', 'name', 'filepath', 'groupkey', 'file_size', 'file_type'),
			$docs,
			array(
				'duplicate_keys' => array('hashkey', 'filepath', 'file_size', 'file_type')
			)
		);
	}

	public function profilPercent()
	{
		$dataset = $this->user['data'];

		if ($this->isUser()) {
			$req = array('email', 'name', 'szakma_text', 'szuletesi_datum', 'allampolgarsag', 'anyanyelv', 'nem', 'profil_img', 'telefon', 'lakcim_irsz', 'lakcim_city',	'social', 'iskolai_vegzettsegi_szintek','munkatapasztalat','fizetesi_igeny','megyeaholdolgozok','elvaras_munkateruletek','elvaras_munkakorok');
		}

		if ($this->isMunkaado()) {
			$req = array('email', 'name', 'szakma_text', 'profil_img', 'telefon', 'szekhely_irsz', 'szekhely_city', 'szekhely_uhsz',	'social','ceges_kapcsolat_nev', 'ceges_kapcsolat_email', 'ceges_kapcsolat_telefon','ceges_foglalkoztatottak_szama','ceges_alapitas_ev','elvaras_munkakorok','ceges_megyek','ceges_munkateruletek');
		}

		$has = array();
		$current = 0;

		// User details
		foreach ($req as $k ) {
			$d = $dataset[$k];

			if($k == 'social'){
				if(
					!empty($dataset['social_url_facebook']) ||
					!empty($dataset['social_url_twitter']) ||
					!empty($dataset['social_url_linkedin'])
				){
					$has[] = $k;
					continue;
				}
			}

			if (isset($d) && !empty($d)) {
				$has[] = $k;
			}
		}

		// Moduls
		$moduls = $this->getAccountModulData();

		if ($this->isUser()) {
			$modul_req = 2;
			$modul_has = 0;
			// Végzettség
			$mdi = $moduls['vegzettseg']['vegzettseg'];

			if(count($mdi) != 0) {
				$modul_has++;
			}

			$mdi = $moduls['munkatapasztalat']['munkatapasztalat'];

			if(count($mdi) != 0) {
				$modul_has++;
			}
		}

		$total = count($req)+$modul_req;
		$current = count($has)+$modul_has;

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

	public function saveProfilModulDatas( $page, $data )
	{
		$module_access = array(
			'vegzettseg' => array('vegzettseg', 'kepesitesek'),
			'ismeretek' => array('nyelvismeret', 'szamitogepes'),
			'munkatapasztalat' => array('munkatapasztalat'),
		);

		if (!is_array($data) || empty($data)) {
			return false;
		}

		$modulinsert = array();
		foreach ((array)$data as $modulkey => $set) {
			if(!in_array($modulkey, (array)$module_access[$page])) continue;
			foreach ((array)$set as $i => $row) {
				$grouphash = ($row['grouphash']) ? $row['grouphash'] : uniqid();
				foreach ($row as $key => $value) {
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

		if (is_array($value)) {
			$check = $this->db->query("SELECT id FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE datatype = 'multi' and fiok_id = ".$account_id." and nev = '".$key."';");

			if( $check->rowCount() !== 0 ) {
				$this->db->query("DELETE FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE datatype = 'multi' and fiok_id = ".$account_id." and nev = '".$key."'");
			}

			foreach ((array)$value as $v) {
				$this->db->insert(
					\PortalManager\Users::TABLE_DETAILS_NAME,
					array(
						'datatype' => 'multi',
						'fiok_id' 	=> $account_id,
						'nev' 			=> $key,
						'ertek' 		=> $v
					)
				);
			}

		} else {
			$check = $this->db->query("SELECT id FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE datatype = 'single' and fiok_id = ".$account_id." and nev = '".$key."';");

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
						'datatype' => 'single',
						'fiok_id' 	=> $account_id,
						'nev' 			=> $key,
						'ertek' 		=> $value
					)
				);
			}
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
