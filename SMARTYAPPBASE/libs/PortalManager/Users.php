<?
namespace PortalManager;

use MailManager\Mailer;
use MailManager\Mails;
use PortalManager\Template;
use PortalManager\Category;
use PortalManager\Portal;
use PortalManager\User;
use PortalManager\Ad;
use PortalManager\AdServices;
use PortalManager\Services;
use ExceptionManager\RedirectException;

/**
 * class Users
 *
 */
class Users
{
	const TABLE_NAME 			= 'accounts';
	const TABLE_DETAILS_NAME 	= 'accounts_details';
	const TABLE_EUROPASS_XML 	= 'europass_xml';
	const TABLE_COMPETENCE_XREF	= 'felhasznalo_kompetencia_xref';
	const TABLE_APPLICANT 		= 'jelentkezesek';
	const TABLE_PREMIUM 		= 'premium_fiok';
	const TABLE_SERVICES_ORDED	= 'szolgaltatasok';

	private $db = null;
	private $is_cp = false;
	public $lang = array();
	public $smarty = null;
	public $controller = null;
	public $arg = null;
	public $dateformat = 'Y. F d.';
	public $europass_dateformat = 'Y. F d. H:i';

	public $user 		= false;
	public $user_data 	= false;
	private $alerts 	= array(
		'has_alert' 	=> false,
		'alerts' 		=> array()
	);

	public $user_groups = array(
		0 => 'MUNKAVALLALO',
		10 => 'MUNKALTATO'
	);

	public $user_datails = array(
		'default' => array(
			'telefon' => array(
				'lang' => 'TELEFON',
				'required' => true
			)
		),
		0 => array(
			'nick' => array(
				'lang' => 'TELEFON',
				'required' => false
			)
		),
		10 => array(
			'ceg' => array(
				'lang' => 'TELEFON',
				'required' => false
			)
		)
	);

	function __construct( $arg = array() )
	{
		if (isset($arg['controller'])) {
			$this->controller = $arg['controller'];
			$this->db = $arg['controller']->db;
			$this->settings = $arg['controller']->settings;
			$this->smarty = $arg['controller']->smarty;
		}

		$this->is_cp 		= $arg['admin'];
		$this->arg 			= $arg;

		$this->install_db();

		//if( empty($this->lang) ) die(__CLASS__.' @ '.__FUNCTION__.': Hiányzó nyelvi fájlok. ');

		$this->Portal 		= new Portal( $arg );
		$this->getUser();
	}

	public function getUserDetails( $user_group = -1 )
	{
		$list = array();

		if($user_group != -1) {
			// Default
			foreach ($this->user_datails['default'] as $key => $value) {
				$list[$key] = array(
					'text' => $this->controller->lang($value['lang']),
					'required' => (int)$value[required],
					'by' => 'default',
					'group' => false,
				);
			}

			// By Group
			foreach ((array)$this->user_datails[$user_group] as $key => $value) {
				$list[$key] = array(
					'text' => $this->controller->lang($value['lang']),
					'required' => (int)$value[required],
					'by' => 'group',
					'group' => array(
						'id' => $user_group,
						'text' => $this->controller->lang($this->user_groups[$user_group]),
					)
				);
			}
		} else {
			foreach ((array)$this->user_datails as $group => $items) {
				$by = ($group === 'default') ? 'default' : 'group';
				foreach ((array)$items as $key => $value) {
					$groups = array(
						'id' => $group,
						'text' => $this->controller->lang($this->user_groups[$group]),
					);
					$list[$key] = array(
						'text' => $this->controller->lang($value['lang']),
						'required' => (int)$value[required],
						'by' => $by,
						'group' => $groups
					);
				}
			}
		}


		return $list;
	}

	function get( $arg = array() )
	{
		$ret 			= array();
		$ret[options] 	= $arg;

		$user_id = ( !$arg['user'] ) ? $this->user : $arg['user'];

		if(!$user_id) return false;

		$ret[data] 				= ($user_id) ? $this->getData( $user_id, 'ID' ) : false;
		$ret[email] 			= $ret[data][email];
		$ret[user_group] 		= $ret[data][user_group];

		switch ( $ret[user_group] ) {
			case $this->settings['USERS_GROUP_MUNKAADO']:
			break;
			case $this->settings['USERS_GROUP_USER']:
			break;
		}

		$this->user_data 	= $ret;
		$ret[alerts]		= $this->getAlerts( false, $ret['data']['user_group'] );

		if( !$ret[data] ) {
			unset($_SESSION['user_id']);
			return false;
		}

		return $ret;
	}

	public function getAlerts( $acc_id = false, $user_group = false )
	{
		$has_alerts 	= 0;
		$alerts 		= array();
		$is_admin 		= ( $user_group == $this->settings['USERS_GROUP_ADMIN'] ) ? true : false;

		/*
			$alerts[] = array(
				'priority' 	=> 10,
				'type' 		=> 'info',
				'mode' 		=> 'static',
				'text' 		=> 'miss_user_kompetenciak'
			);
		*/

		$this->alerts['alerts'] 	= $alerts;
		$this->alerts['has_alert'] 	= ( $has_alerts === 0 ) ? false : $has_alerts;

		return $this->alerts;
	}

	public function resetPassword( $data ){
		$password =  rand(1111111,9999999);

		if(!$this->userExists( 'email', $data['email'])){
			$this->error( $this->controller->lang('NINCS_ILYEN_EMAIL_CIM') );
		}

		$user = $this->getData( $data['email'], 'email');

		$this->db->update(self::TABLE_NAME,
			array(
				'password' => \Hash::jelszo($password)
			),
			sprintf("email = '%s'", $data['email'] )
		);


		$arg = array();
		$arg['password'] 	= $password;
		$arg['user'] 		= $user;

		(new Mails( $this, 'password_reset', $data['email'], $arg ))->send();
	}

	private function getUser(){
		if( $_SESSION['loginsession_id'] ){
			$this->user = $_SESSION['loginsession_id']	;
		}
	}

	public function change( $userID, $post, $details = array() )
	{
		return $this->changeUserAdat( $userID, $post, $details );
	}

	private function changeUserAdat( $userID, $post, $details = array() ){
		extract((array)$post);
		extract((array)$details);

		$err_code = array();
		$err = '';
		$error = false;

		/**
		* VALIDATE
		**/

		if($name == ''){
			$error = true;
			$err_code[] = 'name';
			$err .= ' - '.$this->controller->lang('FORM_USER_REG_NEV_HIANYZIK') . "\r\n";
		}

		if($email == ''){
			$error = true;
			$err_code[] = 'email';
			$err .= ' - '.$this->controller->lang('FORM_USER_REG_EMAIL_HIANYZIK') . "\r\n";
		}

		$checkemail = $this->db->query("SELECT 1 FROM ".self::TABLE_NAME." WHERE email = '{$email}' and ID != {$userID}")->fetchColumn();
		if($checkemail == 1){
			$error = true;
			$err_code[] = 'email';
			$err .= ' - '.$this->controller->lang('Ezt az e-mail címet már használja egy fiók.') . "\r\n";
		}

		$post['name'] = strip_tags($post['name']);

		// Details check
		$possdetails = $this->getUserDetails( $post['user_group'] );
		foreach ((array)$details as $key => $value) {
			$ddata = $possdetails[$key];

			if($ddata['required'] == 1) {
				if($value == '') {
					$error = true;
					$err_code[] = $key;
					$err .= ' - '.$this->controller->lang('KOTELEZO_MEZO_HIANYZIK_XY', array('key' => $ddata['text'])) . "\r\n";
				}
			}
		}


		if ($error) {
			$this->error($err, $err_code);
		}

		$this->db->update(
			self::TABLE_NAME,
			$post,
			"ID = $userID"
		);

		// Details save
		foreach ((array)$details as $key => $value) {
			$this->editAccountDetail($userID, $key, $value);
		}

		return $this->controller->lang('SIKERESEN_MODOSITOTVA_A_FIOK_ADATOK');
	}

	function changePasswordByAdmin($userID, $pw = false) {
		$err_code = array();
		$err = '';
		$error = false;

		if( !$pw && $pw == '' ) {
			$error = true;
			$err_code[] = 'pw';
			$err .= '- '.$this->controller->lang('Adja meg az új jelszót a cseréhez.') . "\r\n";
		}

		$password = \Hash::jelszo($pw);

		$user = $this->db->query("SELECT id, email, name FROM ".self::TABLE_NAME." WHERE ID = {$userID}");

		if ($error) {
			$this->error($err, $err_code);
		}

		$userdata = $user->fetch(\PDO::FETCH_ASSOC);

		$this->db->update(
			self::TABLE_NAME,
			array(
				'password' => \Hash::jelszo($pw)
			),
			"ID = $userID"
		);

		$marg = array();
		$marg[password] = $pw;
		$marg[email] = $userdata['email'];
		$marg[name] = $userdata['name'];

		(new Mails( $this, 'password_changed_by_admin', $userdata['email'], $marg ))->send();

		return $this->controller->lang('FROM_ADMIN_CHANGEDPASSWORD');
	}

	function changePassword($userID, $post) {
		extract($post);
		$err_code = array();
		$err = '';
		$error = false;

		if($userID == '') {
			$error = true;
			$err .= '- '.$this->controller->lang('PASSWORD_MISS_USER') . "\r\n";
		}

		if($old == '') {
			$error = true;
			$err_code[] = 'old';
			$err .= '- '.$this->controller->lang('PASSWORD_MISS_OLD') . "\r\n";
		}

		if($new == '' || $new2 == '') {
			$error = true;
			$err_code[] = 'new';
			$err_code[] = 'new2';
			$err .= '- '.$this->controller->lang('PASSWORD_MISS_NEW') . "\r\n";
		}

		if($new !== $new2) {
			$error = true;
			$err_code[] = 'new';
			$err_code[] = 'new2';
			$err .= '- '.$this->controller->lang('PASSWORD_MISS_DIFFERENT');
		}

		$password = \Hash::jelszo($old);

		$checkOld = $this->db->query("SELECT id, email FROM ".self::TABLE_NAME." WHERE id = {$userID} and password = '$password'");

		if($checkOld->rowCount() == 0){
			$error = true;
			$err_code[] = 'old';
			$err .= '- '.$this->controller->lang('PASSWORD_MISS_OLDNOTGOOD');
		}

		if ($error) {
			$this->error($err, $err_code);
		}

		$checkdata = $checkOld->fetch(\PDO::FETCH_ASSOC);

		$this->db->update(self::TABLE_NAME,
			array(
				'password' => \Hash::jelszo($new2)
			),
			"ID = $userID"
		);

		$marg = array();
		$marg[password] = $new2;

		(new Mails( $this, 'password_changed', $checkdata['email'], $marg ))->send();
	}

	function getData( $account_id, $db_by = 'email', $user_group = false ){
		if($account_id == '') return false;

		$q = "SELECT u.* FROM ".self::TABLE_NAME." as u WHERE 1 = 1 ";

		if( $user_group ) {
			$q .= " and u.user_group = $user_group ";
		}

		$q .= " and u.".$db_by." = '$account_id';";

		extract($this->db->q($q));

		if(empty($data)) return false;

		$account_id = $data['ID'];

		// Details
		$det = $this->db->query("SELECT nev, ertek FROM ".self::TABLE_DETAILS_NAME." WHERE fiok_id = $account_id;")->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($det as $d ) {
			$data[$d['nev']] = $d['ertek'];
		}


		return $data;
	}

	function login($data){
		$re 	= array();

		if( empty($data['email']) || empty($data['password'])) {
			$this->error( $this->controller->lang('HIANYZO_ADATOK') );
		}

		if(!$this->userExists('email',$data['email'])){
			$this->error( $this->controller->lang('EZ_EZ_EMAIL_NINCS_REGISZTRALVA') );
		}

		try {
			if(!$this->validUser($data['email'],$data['password'])){
				$this->error( $this->controller->lang('HIBAS_ADATOKAT_ADOTT_MEG') );
			}
		} catch (Exception $e) {
			$this->error( $e->getMessage() );
		}



		if(!$this->isActivated($data[email])){
			$this->error( $this->controller->lang('AZ_ON_FIOKJA_MEG_NINCS_AKTIVALVA') );
		}

		if(!$this->isEnabled($data[email])){
			$this->error( $this->controller->lang('BLOKKOLT_FIOK') );
		}

		// Refresh
		$this->db->update(
			self::TABLE_NAME,
			array(
				'last_login_date' => NOW
			),
			"email = '".$data[email]."'"
		);

		$data 	= $this->getData( $data[email], 'email' );

		\Session::set( 'loginsession_id', $data[ID] );
		\Session::set( 'loginsession_ug', $user_group );
	}

	public function logout()
	{
		\Session::kill( 'loginsession_id' );
		\Session::kill( 'loginsession_ug' );
	}

	public function getUserGroup( $account_id )
	{
		return $this->db->query("SELECT user_group FROM ".self::TABLE_NAME." WHERE ID = $account_id;")->fetchColumn();
	}

	function activate( $activate_arr ){

		$email 	= $activate_arr[0];
		$userID = $activate_arr[1];
		$pwHash = $activate_arr[2];

		if($email == '' || $userID == '' || $pwHash == '') throw new \Exception($this->controller->lang('HIBAS_AZONOSITO'));

		$q = $this->db->query("SELECT * FROM ".self::TABLE_NAME." WHERE ID = $userID and email = '$email' and password = '$pwHash'");

		if($q->rowCount() == 0) throw new \Exception($this->controller->lang('HIBAS_AZONOSITO'));

		$d = $q->fetch(\PDO::FETCH_ASSOC);

		if(!is_null($d["aktivalva"]))  throw new \Exception('<i class="fa fa-check-circle-o"></i><br>'.$this->controller->lang('A_FIOK_MAR_AKTIVALVA'));

		$this->db->update(self::TABLE_NAME,
			array(
				'aktivalva' => NOW
			),
			"ID = $userID"
		);
	}

	private function register_user( $data, $user_group  )
	{
		$error = false;
		$err = '';
		$err_code = array();

		if( empty($data['name']) ){
			$error = true;
			$err .= '- ' . $this->controller->lang('FORM_USER_REG_NEV_HIANYZIK') . '<br/>';
			$err_code[] = 'name';
		}

		if( empty($data['email']) ){
			$error = true;
			$err .= '- ' . $this->controller->lang('FORM_USER_REG_EMAIL_HIANYZIK') . '<br/>';
			$err_code[] = 'email';
		}

		if( empty($data['telefon']) ){
			$error = true;
			$err .= '- ' . $this->controller->lang('FORM_USER_REG_TELEFON_HIANYZIK') . '<br/>';
			$err_code[] = 'telefon';
		}

		if( empty($data['password']) ){
			$error = true;
			$err .= '- ' . $this->controller->lang('FORM_USER_REG_JELSZO_HIANYZIK') . '<br/>';
			$err_code[] = 'password';
		}

		if( empty($data['password2']) ){
			$error = true;
			$err .= '- ' . $this->controller->lang('FORM_USER_REG_JELSZO_UJRA_HIANYZIK') . '<br/>';
			$err_code[] = 'password2';
		}

		if( $data['password'] != $data['password2'] ){
			$error = true;
			$err .= '- ' . $this->controller->lang('FORM_USER_REG_JELSZO_NEMEGYEZIK') . '<br/>';
			$err_code[] = 'password';
			$err_code[] = 'password2';
		}

		if( !isset($data['aszf']) ){
			$error = true;
			$err .= '- ' . $this->controller->lang('FORM_USER_REG_ASZF_CHECK_HIANYZIK') . '<br/>';
			$err_code[] = 'aszf';
		}

		if ($error) {
			$this->error( $err, $err_code );
		}


		// Felhasználó használtság ellenőrzése
		if($this->userExists('email',$data['email'], $user_group )){
			$this->error( $this->lang['lng_form_login_alredyusedemail']);
		}

		$data['nev'] 	= strip_tags($data['nev']);
		$data['email'] 	= strip_tags($data['email']);

		// Felhasználó regisztrálása
		$this->db->insert(
			self::TABLE_NAME,
			array(
				'email' 		=> trim($data[email]),
				'name' 			=> trim($data[name]),
				'password' 		=> \Hash::jelszo($data[password2]),
				'user_group' 	=> $user_group
			)
		);

		// Új regisztrált felhasználó ID-ka
		$uid = $this->db->lastInsertId();
		$this->addAccountDetail( $uid, 'telefon', $data['telefon']);

		// Aktiváló e-mail kiküldése
		$this->sendActivationEmail( $data['email'], $user_group );
	}

	private function register_munkaado( $data, $user_group )
	{

		if( empty($data['nev']) ){
			$this->error( $this->lang['lng_form_reg_missingcompany']);
		}
		if( empty($data['email']) ){
			$this->error( $this->lang['lng_form_login_missingemail']);
		}
		if( empty($data['contact_name']) ){
			$this->error( $this->lang['lng_form_reg_missingcontactname']);
		}
		if( empty($data['contact_phone']) ){
			$this->error( $this->lang['lng_form_reg_missingcontactphone']);
		}
		if( empty($data['company_adoszam']) ){
			$this->error( $this->lang['lng_form_reg_company_adoszam']);
		}
		if( empty($data['megye']) ){
			$this->error( $this->lang['lng_form_reg_megye']);
		}
		if( empty($data['city']) ){
			$this->error( $this->lang['lng_form_reg_city']);
		}
		if( empty($data['company_hq']) ){
			$this->error( $this->lang['lng_form_reg_company_hq']);
		}
		if( empty($data['employer_aszf']) ){
			$this->error( $this->lang['lng_form_reg_wantacceptaszf']);
		}
		if( empty($data['pw']) || empty($data['pw2'])   ){
			$this->error( $this->lang['lng_form_reg_missingpasswords']);
		}

		if( $data['pw'] !== $data['pw2'] ){
			$this->error( $this->lang['lng_form_login_alredyusedemail']);
		}

		if( $data['megye'] == \PortalManager\Categories::TYPE_TERULETEK_BUDAPEST_ID ) {
			if( !isset($data['kerulet']) || empty($data['kerulet']) ) { $this->error( $this->lang['lng_form_missed_kerulet'] ); }
		}

		// Felhasználó használtság ellenőrzése
		if($this->userExists('email',$data['email'], $user_group )){
			$this->error( $this->lang['lng_form_login_alredyusedemail']);
		}

		$data['nev'] 	= strip_tags($data['nev']);
		$data['email'] 	= strip_tags($data['email']);

		// Felhasználó regisztrálása
		$this->db->insert(
			self::TABLE_NAME,
			array(
				'email' 		=> trim($data[email]),
				'nev' 			=> trim($data[nev]),
				'jelszo' 		=> \Hash::jelszo($data[pw2]),
				'user_group' 	=> $user_group
			)
		);

		// Új regisztrált felhasználó ID-ka
		$uid = $this->db->lastInsertId();

		$this->addAccountDetail( $uid, 'contact_name', 		strip_tags($data['contact_name']) );
		$this->addAccountDetail( $uid, 'contact_phone', 	strip_tags($data['contact_phone']) );
		$this->addAccountDetail( $uid, 'company_adoszam', 	strip_tags($data['company_adoszam']) );
		$this->addAccountDetail( $uid, 'company_hq', 		strip_tags($data['company_hq']) );


		$city_id = \PortalManager\Categories::TYPE_TERULETEK_BUDAPEST_ID;

		/**
		 * VÁROS MENTÉSE TERÜLETEK KÖZÉ
		 * deep = 2
		 * kivéve, ha Budapest
		 * */
		if( $data['megye'] != \PortalManager\Categories::TYPE_TERULETEK_BUDAPEST_ID ) {

			$cat = new Category( \PortalManager\Categories::TYPE_TERULETEK, false, array('db' => $this->db) );

			$cat_neve 	= strip_tags(trim($data['city']));
			$cat_szulo 	= $data['megye']."_1";

			$check_city = $cat->checkExists( array( 'neve' => $cat_neve, 'szulo_id' => $cat_szulo ) );
			$city_id 	= $check_city;

			// Város terület beszúrása, ha nem található az adatbázisban
			if( !$check_city ) {
				$city_id = $cat->add( array(
					'neve' 		=> $cat_neve,
					'szulo_id' 	=> $cat_szulo
				) );
			}

		} else {
			$data['megye'] 	= $city_id;
			$city_id 		= $data['kerulet'];
		}

		$this->addAccountDetail( $uid, 'city', 	$city_id );
		$this->addAccountDetail( $uid, 'megye', $data['megye'] );


		// Aktiváló e-mail kiküldése
		$this->sendActivationEmail( $data['email'], $user_group );

		// Ingyenesen STARTER PACK
		if( isset($this->settings['ALLOW_STARTER_ADS']) && $this->settings['ALLOW_STARTER_ADS'] == '1' ) {
			$this->addStarterPackToEmployer( $uid );
		}
	}

	private function addAccountDetail( $accountID, $key, $value )
	{
		$this->db->insert(
			self::TABLE_DETAILS_NAME,
			array(
				'fiok_id' 	=> $accountID,
				'nev' 		=> $key,
				'ertek' 	=> $value
			)
		);
	}

	private function addStarterPackToEmployer( $user_id )
	{
		if( !$user_id ) return false;

		$check = $this->db->query("SELECT 1 FROM ".\PortalManager\Ad::TABLE_PACKAGES_BUYED." WHERE fiok_id = $user_id and csomag_azonosito = 'JOBADSTARTER';");

		if( $check->rowCount() != 0 ) {
			return false;
		}

		// Szolgáltatás adatok
		$service = new AdServices( $this->arg );
		$service->getAd( 'JOBADSTARTER' );

		$this->db->insert(
			\PortalManager\Ad::TABLE_PACKAGES_BUYED,
			array(
				'fiok_id' 			=> $user_id,
				'csomag_azonosito' 	=> 'JOBADSTARTER',
				'elerheto_napok' 	=> (int)$service->getAllowedAdDays(),
				'kiadott_hirdetes' 	=> (int)$service->getHirdetes(),
				'hirdetes_maradt' 	=> (int)$service->getHirdetes(),
				'vasarolva' 		=> NOW
			)
		);
	}

	public function getAccountDetail( $id, $key )
	{
		$details = $this->getAccountDetails( $id );

		return $details[ $key ];
	}

	public function editAccountDetail( $account_id, $key, $value )
	{
		if( !$account_id ) return false;

		$check = $this->db->query("SELECT id FROM ".self::TABLE_DETAILS_NAME." WHERE fiok_id = ".$account_id." and nev = '".$key."';");

		if( $check->rowCount() !== 0 ) {
			$this->db->update(
				self::TABLE_DETAILS_NAME,
				array(
					'ertek' 			=> $value
				),
				sprintf( "fiok_id = %d and nev = '%s'", $account_id, $key)
			);
		} else {

			$this->db->insert(
				self::TABLE_DETAILS_NAME,
				array(
					'fiok_id' 	=> $account_id,
					'nev' 				=> $key,
					'ertek' 			=> $value
				)
			);
		}
	}

	/**
	 * Munkáltató állásajánlatára jelentkezőinek száma, amelyiket még nem nyitotta meg
	 * értesítés buborékhoz kell
	 **/
	public function getUnwatchedApplicants( $acc_id )
	{
		if( !$acc_id ) return 0;

		$ad_set = $this->db->query("SELECT GROUP_CONCAT(ID) FROM ".\PortalManager\Ad::TABLE." WHERE fiok_id = ".$acc_id)->fetchColumn();

		if( empty( $ad_set ) ) {
			return 0;
		}

		$q = "SELECT count(id) FROM ".self::TABLE_APPLICANT." WHERE hird_id IN (".$ad_set.") and megtekintve = 0;";

		return $this->db->query( $q )->fetchColumn();
	}



	public function orderService( $acc_id, $package_id )
	{
		if( !$acc_id ) return false;

		$this->arg['settings'] = $this->arg['view']['settings'];

		// Szolgáltatás adatok
		$arg = $this->arg;
		$arg['filters'] = array(
			'in_ids' => array( $package_id )
		);

		$service = new Services( $arg );
		$service->getList();

		$service_price 	= 0;
		$service_title 	= false;
		$service_desc 	= false;
		$add_day 		= 0;

		while( $service->walk() ) {
			$service_price 		= $service->getPrice( true );
			$service_id 		= $service->getID();
			$service_title 		= $service->getTitle();
			$service_desc 		= $service->getDescription();
			$add_day 			= $service->getAllowedDay();
		}

		// Felhasználó adatok
		$user = new User( $acc_id, $this->arg );

		// Egyenleg ellenőrzés
		$egyenleg = (float) $user->getEgyenleg();
		$price_diff = $egyenleg - $service_price;

		// Nincs elegendő egyenleg
		if( $price_diff < 0 ) {
			$this->error( $this->lang['lng_services_ad_form_not_enought_balance'] );

		}

		// Szolgáltatás árának levonása
		// Kiértesítés
		// Logolás
		$arg = array();
		$arg['service_title'] 		= $service_title;
		$arg['service_desc'] 		= $service_desc;
		$arg['elem'] 				= $package_id;
		$arg['price'] 				= $service_price;
		$arg['balance_after_buy'] 	= $price_diff;

		$trans_hash = $user->balance( ($service_price * -1), $package_id, true, $arg );

		// Szolgáltatás regisztrálása
		$this->db->insert(
			self::TABLE_SERVICES_ORDED,
			array(
				'fiok_id' 			=> $acc_id,
				'tipus' 			=> 'contact_watcher',
				'mikortol' 			=> NOW,
				'meddig' 			=> date( 'Y-m-d H:i:s', strtotime( NOW . ' +'.$add_day.' day') ),
				'tranzakcio_hash'	=> $trans_hash
			)
		);

	}


	public function orderAdService( $acc_id, $package_id, $selected_day )
	{
		if( !$acc_id ) return false;

		$this->arg['settings'] = $this->arg['view']['settings'];

		// Szolgáltatás adatok
		$service = new AdServices( $this->arg );
		$service->getAd( $package_id );
		$service_price = $service->getPrice( $selected_day, true );

		// Felhasználó adatok
		$user = new User( $acc_id, $this->arg );

		// Egyenleg ellenőrzés
		$egyenleg = (float) $user->getEgyenleg();
		$price_diff = $egyenleg - $service_price;

			// Nincs elegendő egyenleg
			if( $price_diff < 0 ) {
				$this->error( $this->lang['lng_services_ad_form_not_enought_balance'] );
			}


		// Szolgáltatás árának levonása
		// Kiértesítés
		// Logolás
		$arg = array();
		$arg['service'] = $service;
		$arg['elem'] 	= $service->getID();
		$arg['price'] 	= $service_price;
		$arg['balance_after_buy'] = $price_diff;

		$user->balance( ($service_price * -1), \PortalManager\User::BALANCE_SERVICE_ORDER_AD, true, $arg );

		// Szolgáltatás regisztrálása
		$this->db->insert(
			\PortalManager\Ad::TABLE_PACKAGES_BUYED,
			array(
				'fiok_id' 			=> $user->getID(),
				'csomag_azonosito' 	=> $service->getID(),
				'elerheto_napok' 	=> $selected_day,
				'kiadott_hirdetes' 	=> $service->getHirdetes(),
				'hirdetes_maradt' 	=> $service->getHirdetes(),
				'vasarolva' 		=> NOW
			)
		);
	}

	public function getAccountDetails( $id )
	{
		$list = array();

		$q = $this->db->query( "SELECT nev,ertek FROM ".self::TABLE_DETAILS_NAME." WHERE fiok_id = $id;" )->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($q as $d) {
			$list[$d['nev']] = $d['ertek'];
		}

		return $list;
	}

	function add( $data ){

		$user_group = $data['user_group'];

		switch( $user_group ) {
			// Munkavállaló
			case 0:
				$this->register_user( $data, $user_group );
			break;
			// Munkaadó
			case 10:
				$this->register_munkaado( $data, $user_group );
			break;
		}
	}

	public function delete( $id )
	{
		$data = $this->getData( $id, 'ID');

		// Kiegészítő adatok törlése
		if( $data ){
			$this->db->query("DELETE FROM ".self::TABLE_DETAILS_NAME." WHERE fiok_id = ".$id);
		}

		// Fiók törlése
		$this->db->query("DELETE FROM ".self::TABLE_NAME." WHERE ID = ".$id);
	}

	public function getUserNum($user_group = false, $arg = array() )
	{
		$num = 0;
		$emp_ids = array();

		// Terület ID-k, szülőkkel együtt
		$terulets 	= $arg['ad_in_terulet'];

		if( !$terulets ) {
			$terulets = array();
		}

		$q = "
		SELECT 			u.ID, GROUP_CONCAT(h.terulet_id) as terulet_id_set
		FROM 			".self::TABLE_NAME." as u
		LEFT OUTER JOIN ".\PortalManager\Ad::TABLE." as h ON h.fiok_id = u.ID
		WHERE 1=1 ";

		if( $user_group ) {
			$q .= " and u.user_group = " . $user_group;
		}

		$q .= " and h.active = 1 and now() > h.feladas_ido and now() < h.lejarat_ido ";

		$q .= " GROUP BY u.ID ";

		$qry = $this->db->query( $q )->fetchAll(\PDO::FETCH_ASSOC);

		foreach ( $qry as $d ) {
			$uset = explode(",",$d['terulet_id_set']);

			if( count($terulets) > 0) {
				foreach ( $terulets as $t ) {
					if( in_array( $t, $uset ) ) {
						if( !in_array( $d['ID'], $emp_ids ) ) {
							$emp_ids[] = $d['ID'];
							continue;
						}
					}
				}
			} else {
				if( !in_array( $d['ID'], $emp_ids ) ) {
					$emp_ids[] = $d['ID'];
				}
			}
		}

		$num = count($emp_ids);

		return $num;
	}

	public function sendActivationEmail( $email, $user_group )
	{
		$data = $this->db->query( sprintf(" SELECT * FROM ".self::TABLE_NAME." WHERE user_group = $user_group and email = '%s';", $email) )->fetch(\PDO::FETCH_ASSOC);

		$lang = $this->settings['language'];

		if( !$data ) return false;

		$account_details = $this->getData( $data['ID'], 'ID' );

		$activateKey = $this->settings['page_url'].'/activate/'.base64_encode(trim($email).'='.$data['ID'].'='.$data['password']);

		// Aktiváló e-mail kiküldése
		$mail = new Mailer( $this->settings['page_title'], $this->settings['email_noreply_address'], $this->settings['mail_sender_mode'] );
		$mail->add( $email );

		$this->smarty->assign( 'activateURL', $activateKey );
		$this->smarty->assign( 'account', $account_details );

		switch( $user_group ) {
			// Felh.
			case $this->settings['USERS_GROUP_USER']:
				$mail->setSubject( $this->controller->lang('MAIL_REGISZTRACIO_ACTIVATION_TITLE') );
			break;
			// Munkáltató
			case $this->settings['USERS_GROUP_MUNKAADO']:
				$mail->setSubject( $this->controller->lang('MAIL_REGISZTRACIO_ACTIVATION_TITLE') );
			break;
		}

		$mail->setMsg( $this->smarty->fetch( 'mails/'.$lang.'/register_activation.tpl' ) );
		$re = $mail->sendMail();
	}

	public function testMail( $email )
	{

		// Aktiváló e-mail kiküldése
		$mail = new Mailer( $this->settings['page_title'], $this->settings['email_noreply_address'], $this->settings['mail_sender_mode'] );
		$mail->add( $email );

		$mail->setMsg( $this->smarty->fetch( 'mails/teszt.tpl' ) );
		$re = $mail->sendMail();
	}

	function userExists( $by = 'email', $val, $group = false ){
		$q = "SELECT ID FROM ".self::TABLE_NAME." WHERE ".$by." = '".$val."' ";

		if( $group !== false ){
			$q .= " and user_group = $group ";
		}
		$c = $this->db->query($q);

		if($c->rowCount() == 0){
			return false;
		}else{
			return true;
		}
	}

	function oldUser($email)
	{
		$q = "SELECT ID FROM ".self::TABLE_NAME." WHERE email = '".$email."' and old_user = 1 and password = 'xxxx';";

		$c = $this->db->query($q);

		if($c->rowCount() == 0){
			return false;
		}else{
			return true;
		}
	}

	function isActivated($email, $group = false ){
		$q = "SELECT ID FROM ".self::TABLE_NAME." WHERE email = '".$email."' and aktivalva IS NOT NULL";

		if($group !== false){
			$q .= " and user_group = $group ";
		}

		$c = $this->db->query($q);

		if($c->rowCount() == 0){
			return false;
		}else{
			return true;
		}
	}

	function isEnabled($email, $group = false){
		$q = "SELECT ID FROM ".self::TABLE_NAME." WHERE email = '".$email."' and engedelyezve = 1";

		if($group !== false){
			$q .= " and user_group = $group ";
		}

		$c = $this->db->query($q);

		if($c->rowCount() == 0){
			return false;
		}else{
			return true;
		}
	}

	function validUser($email, $password, $group = false ){
		if($email == '' || $password == '') throw new \Exception('Hiányzó adatok. Nem lehet azonosítani a felhasználót!');

		$q = "SELECT ID FROM ".self::TABLE_NAME." WHERE email = '$email' and password = '".\Hash::jelszo($password)."'";

		if($group !== false){
			$q .= " and user_group = $group ";
		}

		$c = $this->db->query($q);

		if($c->rowCount() == 0){
			return false;
		}else{
			return true;
		}
	}

	public function getJobApplicantNum($acc_id, $user_group = false)
	{
		$num = 0;

		switch ( $user_group ) {
			default: case $this->settings['USERS_GROUP_USER']:
				$q = "SELECT count(id) FROM ".self::TABLE_APPLICANT." WHERE felh_id = ".$acc_id.";";
			break;
			case $this->settings['USERS_GROUP_EMPLOYER']:
				$q = "SELECT count(a.id) FROM ".self::TABLE_APPLICANT." as a WHERE (SELECT fiok_id FROM ".\PortalManager\Ad::TABLE." WHERE id = a.hird_id) = ".$acc_id.";";
			break;
		}

		$num = $this->db->query($q)->fetchColumn();

		return $num;
	}

	public function getSubscribeNum($acc_id)
	{
		$num = 0;

		return $num;
	}

	public function getUserList( $arg = array(), $user_group = -1 )
	{
		$q = "
		SELECT 			f.*
		FROM 			".self::TABLE_NAME." as f";
		// WHERE
		$q .= " WHERE 1=1 ";


		if( $user_group != -1 ) {
			$q .= " and f.user_group = ".$user_group;
		}

		// Csak bizonyos ID-jú elemek listázása
		if( isset($arg['in_ids']) && !empty($arg['in_ids']) ) {
			if( is_array($arg['in_ids']) && count($arg['in_ids']) > 0 ) {
				$q .= " and f.ID IN (".implode(",",$arg['in_ids']).") ";
			}
		}

		if(count($arg[filters]) > 0){
			foreach($arg[filters] as $key => $v){
				if($v != '') {
					switch($key)
					{
						case 'name':
							$q .= " and ".$key." LIKE '".$v."%' ";
						break;
						case 'emailname':
							$q .= " and (f.name LIKE '%".$v."%' or f.email LIKE '%".$v."%') ";
						break;
						default:
							$q .= " and ".$key." = '".$v."' ";
						break;
					}
				}
			}
		}
		$q .= "
		ORDER BY f.register_date DESC
		";

		$arg[multi] = "1";
		extract($this->db->q($q, $arg));

		$B = array();
		foreach($data as $d){
			$d[details] 		= $this->getAccountDetails( $d['ID'] );

			$B[] = $d;
		}

		$ret[data] = $B;

		return $ret;
	}

	public function getEmployerServices( $account )
	{
		// Defaults
		$ret = array(
			'ads' => array(
				'slot_left' => 0,
				'free' => array(
					// Elérhető létrehozható hirdetések száma - ingyenesen
					'avaiable' 		=> $this->settings['ads_free_creation'],
					// Engedélyezett futamidők - ingyenesen
					'allowed_days' 	=> array( 21 )
				),
				'paid' => array(
					// Elérhető létrehozható hirdetések száma - fizetett
					'avaiable' 		=> 0,
					// Engedélyezett futamidők - fizetett
					'allowed_days' 	=> array( ),
					'package' 		=> false
				)
			),
			'contact_watcher' 	=> array(
				'avaiable' 		=> 0,
				'acces_time' 	=> array( false, false )
			)
		);

		// Fizetett slotok
		$services = $this->db->query("SELECT id, csomag_azonosito, elerheto_napok, hirdetes_maradt, kiadott_hirdetes FROM ".\PortalManager\Ad::TABLE_PACKAGES_BUYED." WHERE fiok_id = $account and elhasznalva = 0;")->fetchAll(\PDO::FETCH_ASSOC);

		foreach( $services as $service ) {
			$ret['ads']['paid']['avaiable'] = (int)$ret['ads']['paid']['avaiable'] + $service['hirdetes_maradt'];

			if( !in_array( $service['elerheto_napok'], $ret['ads']['paid']['allowed_days'] )) {
				$ret['ads']['paid']['allowed_days'][] 	= $service['elerheto_napok'];
			}

			$ret['ads']['paid']['package'][] 		= array(
				'id' 			=> $service['csomag_azonosito'],
				'eid' 			=> $service['id'],
				'total_slot' 	=> $service['kiadott_hirdetes'],
				'left_slot' 	=> $service['hirdetes_maradt'],
				'used_slot' 	=> $service['kiadott_hirdetes'] - $service['hirdetes_maradt'],
				'usage_percent'	=> 100 - \Helper::getPercent( $service['kiadott_hirdetes'], $service['hirdetes_maradt'] ),
				'day'			=> $service['elerheto_napok']
			);
			$ret['ads']['slot_left'] 				= (int)$ret['ads']['slot_left'] + (int)$service['hirdetes_maradt'];
		}

		// Ingyenes hirdetés számolás
		$date_edge = date( 'Y-m-d H:i:s', strtotime( NOW . ' -30 day' ) );
		$honap_hirdetesek = $this->db->query( $cq = "SELECT count(c.id) FROM ".\PortalManager\Ad::TABLE." as c WHERE c.fiok_id = $account and c.feladas_ido > '$date_edge' and (SELECT 1 FROM ".\PortalManager\Ad::TABLE_PACKAGES_USED_LOG." WHERE hird_id = c.id) IS NULL;")->fetchColumn();
		$ret['ads']['free']['avaiable'] = ( (int) $ret['ads']['free']['avaiable'] ) - $honap_hirdetesek;
		$ret['ads']['free']['avaiable'] = ( $ret['ads']['free']['avaiable'] < 0 ) ? 0 : $ret['ads']['free']['avaiable'];
		$ret['ads']['slot_left'] 		= (int)$ret['ads']['slot_left']  +  (int) $ret['ads']['free']['avaiable'];

		// Kapcsolat felvétel felvétel
		$check = $this->db->query($q = "SELECT mikortol, meddig FROM ".self::TABLE_SERVICES_ORDED." WHERE fiok_id = ".$account." and tipus = 'contact_watcher' and now() >= mikortol and now() <= meddig;");
		if( $check->rowCount() != 0 ) {
			$cd = $check->fetch(\PDO::FETCH_ASSOC);

			$ret['contact_watcher']['avaiable'] 	= 1;
			$ret['contact_watcher']['acces_time'] 	= array(
				$cd['mikortol'],
				$cd['meddig']
			);
		}

		return $ret;
	}

	private function activeAds( $account = false )
	{
		$num = 0;

		if( !$account ) return $num;

		$num = $this->db->query("SELECT count(id) FROM ".\PortalManager\Ad::TABLE." WHERE fiok_id = '$account' and active = '1' and now() > feladas_ido and now() < lejarat_ido;")->fetchColumn();

		return $num;
	}

	private function install_db()
	{
		if($_GET['appinstaller'] != '1') return false;

		$created = (int)$this->db->query("SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' and TABLE_NAME = '".self::TABLE_NAME."'")->fetchColumn();

		if( $created === 0 )
		{
			$qry = array();
			$qry[] = "CREATE TABLE IF NOT EXISTS `".self::TABLE_NAME."` (
			  `ID` int(11) NOT NULL,
			  `name` varchar(80) NOT NULL,
			  `email` varchar(50) NOT NULL,
			  `password` varchar(50) NOT NULL,
			  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `last_login_date` datetime DEFAULT NULL,
			  `user_group` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0: user, 10: admin',
			  `engedelyezve` tinyint(1) DEFAULT '1',
			  `aktivalva` datetime DEFAULT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

			$qry[] = "ALTER TABLE `".self::TABLE_NAME."`
			  ADD PRIMARY KEY (`ID`),
			  ADD KEY `engedelyezve` (`engedelyezve`,`aktivalva`);";

			$qry[] = "ALTER TABLE `".self::TABLE_NAME."`
				MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";

			foreach($qry as $q) {
				$this->db->query($q);
			}
		}
		$created = false;

		$created = (int)$this->db->query("SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' and TABLE_NAME = '".self::TABLE_DETAILS_NAME."'")->fetchColumn();

		if( $created === 0 )
		{
			$qry = array();
			$qry[] = "CREATE TABLE IF NOT EXISTS  `".self::TABLE_DETAILS_NAME."` (
			  `id` int(11) NOT NULL,
			  `nev` varchar(250) NOT NULL,
			  `ertek` text,
			  `fiok_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

			$qry[] = "ALTER TABLE `".self::TABLE_DETAILS_NAME."`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `nev` (`nev`,`fiok_id`);";

			$qry[] = "ALTER TABLE `".self::TABLE_DETAILS_NAME."`
			  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

			foreach($qry as $q) {
				$this->db->query($q);
			}
		}
	}

	private function error( $msg, $error_codes = false )
	{
		throw new RedirectException( $msg, $_POST['form'], $_POST['return'], $_POST['session_path'], $error_codes);
	}

	public function __destruct()
	{
		$this->controller = null;
		$this->db = null;
		$this->arg = null;
		$this->user = false;
		$this->smarty = null;
		$this->user_data = false;
	}
}

?>
