<?
use PortalManager\User;
use PortalManager\Categories;
use PortalManager\Ad;
use PortalManager\AdServices;
use PortalManager\Services;
use PortalManager\Admins;
use FlexTimeResort\Allasok;

class ajax extends Controller  {
		private $root = 'index';
		private $path = '';

		function __construct(){
			parent::__construct();
			parent::$pageTitle = '';

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->addMeta('description','');
			$SEO .= $this->addMeta('keywords','');
			$SEO .= $this->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->addOG('type','website');
			$SEO .= $this->addOG('url','');
			$SEO .= $this->addOG('image','');
			$SEO .= $this->addOG('site_name',parent::$pageTitle);

			$this->out( 'SEOSERVICE', $SEO );
		}

		public function data()
		{
			$this->hidePatern = true;
			$params = $_REQUEST;
			$data = array();

			switch ( $params['type'] ) {
				case 'me':
					// Terms
					$data['terms']['anyanyelv'] = (int)$this->ME->getAccountData('anyanyelv');
					$data['terms']['nem'] = (int)$this->ME->getAccountData('nem');
					$data['terms']['allampolgarsag'] = (int)$this->ME->getAccountData('allampolgarsag');
					$data['terms']['csaladi_allapot'] = (int)$this->ME->getAccountData('csaladi_allapot');
					$data['terms']['iskolai_vegzettsegi_szintek'] = (int)$this->ME->getAccountData('iskolai_vegzettsegi_szintek');
					$data['terms']['munkatapasztalat'] = (int)$this->ME->getAccountData('munkatapasztalat');

					// Alapadatok
					$data['alap']['name'] = $this->ME->getName();
					$data['alap']['szakma_text'] = $this->ME->getAccountData('szakma_text');
					$data['alap']['email'] = $this->ME->getEmail();
					$data['alap']['profil_kep'] = $this->ME->getProfilImg();
					$data['alap']['szuletesi_datum'] = $this->ME->getAccountData('szuletesi_datum');

					// Elérhetőségek
					$data['elerhetoseg']['telefon'] = $this->ME->getAccountData('telefon');
					$data['elerhetoseg']['lakcim_irsz'] = (int)$this->ME->getAccountData('lakcim_irsz');
					$data['elerhetoseg']['lakcim_city'] = $this->ME->getAccountData('lakcim_city');
					$data['elerhetoseg']['lakcim_uhsz'] = $this->ME->getAccountData('lakcim_uhsz');
					$data['elerhetoseg']['social_url_facebook'] = $this->ME->getAccountData('social_url_facebook');
					$data['elerhetoseg']['social_url_twitter'] = $this->ME->getAccountData('social_url_twitter');
					$data['elerhetoseg']['social_url_linkedin'] = $this->ME->getAccountData('social_url_linkedin');

					// Ismeretek
					$data['ismeretek']['jogositvanyok'] = (array)$this->ME->getAccountData('jogositvanyok');
					$data['ismeretek']['ismeretek_egyeb'] = $this->ME->getAccountData('ismeretek_egyeb');

					// Elvárások
					$data['elvarasok']['fizetesi_igeny'] = (int)$this->ME->getAccountData('fizetesi_igeny');
					$data['elvarasok']['megyeaholdolgozok'] = (array)$this->ME->getAccountData('megyeaholdolgozok');
					$data['elvarasok']['elvaras_munkateruletek'] = (array)$this->ME->getAccountData('elvaras_munkateruletek');
					$data['elvarasok']['igenyek_egyeb_munkakorok'] = $this->ME->getAccountData('igenyek_egyeb_munkakorok');
					$data['elvarasok']['munkaba_allas_ideje'] = $this->ME->getAccountData('munkaba_allas_ideje');
					$data['elvarasok']['igenyek_egyeb'] = $this->ME->getAccountData('igenyek_egyeb');

					$data['elvarasok']['elvaras_munkakorok'] = (array)$this->ME->getAccountData('elvaras_munkakorok');

					$data['dokumentumok']['kulso_oneletrajz_url'] = $this->ME->getAccountData('kulso_oneletrajz_url');



					$data['oneletrajz'] = $this->ME->getOneletrajz();
					$data['documents'] = $this->ME->getDocuments();

					// Modul paraméterek
					$data['moduls'] = $this->ME->getAccountModulData();
				break;
				case 'user':
					$user = new User($params['id'], array(
						'controller' => $this
					));
					$data = $user->user;
				break;
				case 'uploadProfilImg':
					if (isset($_FILES['file']) && $_FILES['file']['error'] == 0)
					{
						// uploads image in the folder images
				    $temp = explode(".", $_FILES["file"]["name"]);
				    $newfilename = substr(md5(time()), 0, 10) . '.' . end($temp);
				    move_uploaded_file($_FILES['file']['tmp_name'], 'store/images/profils/' . $newfilename);

						// give callback to your angular code with the image src name
						$data['filename'] = $newfilename;
						$data['uploaded_path'] = '/store/images/profils/' . $newfilename;
						$data['error'] = false;
					} else {
						$data['error'] = false;
					}

					$data['FILE'] = $_FILES['file'];
				break;
				case 'uploadDocuments':
					if (isset($_FILES['file'])) {
						if (is_array($_FILES['file']['error'])) {
							// Multi upload
							foreach ((array)$_FILES['file']['tmp_name'] as $i => $tmp) {
								// uploads image in the folder images
						    $temp = explode(".", $_FILES["file"]["name"][$i]);
						    $newfilename = substr(md5(time()), 0, 10) . '.' . end($temp);
						    move_uploaded_file($_FILES['file']['tmp_name'][$i], 'store/docs/users/' . $newfilename);

								// give callback to your angular code with the image src name
								$data['filename'][$i] = $newfilename;
								$data['uploaded_path'][$i] = '/store/docs/users/' . $newfilename;
								$data['error'][$i] = false;
								$data['multiupload'] = true;
							}
						} else {
							// Simple upload
							// uploads image in the folder images
					    $temp = explode(".", $_FILES["file"]["name"]);
					    $newfilename = substr(md5(time()), 0, 10) . '.' . end($temp);
					    move_uploaded_file($_FILES['file']['tmp_name'], 'store/docs/users/' . $newfilename);

							// give callback to your angular code with the image src name
							$data['filename'] = $newfilename;
							$data['uploaded_path'] = '/store/docs/users/' . $newfilename;
							$data['error'] = false;
						}
					}

					$data['FILE'] = $_FILES['file'];
				break;
				case 'documentsRemover':
					$err = false;
					$success = true;

					if (!$err && empty($params['hashkey'])) {
						$msg = 'Hiányzik a törlendő dokumentum egyedi azonosítója.';
						$err = true;
					}

					if ($err) {
						$success = false;
					} else {
						$prev_file = $this->db->query("SELECT filepath FROM documents WHERE fiok_id = ".$this->ME->getID()." and hashkey = '".$params['hashkey']."'")->fetchColumn();
						if (!empty($prev_file)) {
							if (file_exists(REALPATH_APP.substr($prev_file, 1))) {
								$deleted = unlink(REALPATH_APP.substr($prev_file, 1));
								if ($deleted) {
									$this->db->query("DELETE FROM documents WHERE fiok_id = ".$this->ME->getID()." and hashkey = '".$params['hashkey']."'");
								}
							}
						}
					}

					$data['hashkey'] = $params['hashkey'];
					$data['success'] = $success;
				break;
				case 'profilsave':
					$nextpages = array(
						'alap' => 'elerhetoseg'
					);
					$form = json_decode($params['form'], true);
					$moduldatas = json_decode($params['moduldatas'], true);
					$moduldelete = json_decode($params['moduldelete'], true);
					$page = $params['page'];

					$profildata = array();
					$profildetails = array();

					$profildata['name'] = $form['name'];
					$profildetails['szuletesi_datum'] = $form['szuletesi_datum'];
					$profildetails['anyanyelv'] = (int)$form['anyanyelv'];
					$profildetails['szakma_text'] = $form['szakma_text'];
					$profildetails['nem'] = (int)$form['nem'];
					$profildetails['allampolgarsag'] = (int)$form['allampolgarsag'];
					$profildetails['csaladi_allapot'] = (int)$form['csaladi_allapot'];

					$profildetails['telefon'] = $form['telefon'];
					$profildetails['lakcim_irsz'] = $form['lakcim_irsz'];
					$profildetails['lakcim_uhsz'] = $form['lakcim_uhsz'];
					$profildetails['lakcim_city'] = $form['lakcim_city'];
					$profildetails['social_url_facebook'] = $form['social_url_facebook'];
					$profildetails['social_url_twitter'] = $form['social_url_twitter'];
					$profildetails['social_url_linkedin'] = $form['social_url_linkedin'];

					$profildetails['iskolai_vegzettsegi_szintek'] = (int)$form['iskolai_vegzettsegi_szintek'];
					$profildetails['jogositvanyok'] = (array)$form['jogositvanyok'];
					$profildetails['ismeretek_egyeb'] = $form['ismeretek_egyeb'];
					$profildetails['munkatapasztalat'] = (int)$form['munkatapasztalat'];

					$profildetails['fizetesi_igeny'] = (int)$form['fizetesi_igeny'];
					$profildetails['megyeaholdolgozok'] = (array)$form['megyeaholdolgozok'];
					$profildetails['elvaras_munkateruletek'] = (array)$form['elvaras_munkateruletek'];
					$profildetails['igenyek_egyeb_munkakorok'] = $form['igenyek_egyeb_munkakorok'];
					$profildetails['munkaba_allas_ideje'] = $form['munkaba_allas_ideje'];
					$profildetails['igenyek_egyeb'] = $form['igenyek_egyeb'];

					$profildetails['elvaras_munkakorok'] = (array)$form['elvaras_munkakor'];

					$profildetails['kulso_oneletrajz_url'] = $form['kulso_oneletrajz_url'];


					if (isset($form['newprofilimg'])) {
						$this->ME->changeProfilImg($form['newprofilimg']);
					}

					$re = $this->ME->saveProfil($profildata, $profildetails);

					if (!empty($moduldatas)) {
						$this->ME->saveProfilModulDatas($page, $moduldatas);
					}

					if (!empty($moduldelete)) {
						$this->ME->removeModulDatas($moduldelete);
					}

					if (isset($form['uploaded_oneletrajz'])) {
						$uploaded_oneletrajz = $form['uploaded_oneletrajz'];
						$this->ME->updateOneletrajz($uploaded_oneletrajz['uploaded_path'], $uploaded_oneletrajz['FILE']);
					}

					if (isset($form['uploaded_docs'])) {
						$uploaded_docs = $form['uploaded_docs'];
						$this->ME->multipleDocsUploadRegister($uploaded_docs['uploaded_path'], $uploaded_docs['FILE'], $form['uploaded_docs_info']);
					}

					$data['form'] = $form;
					$data['moduldatas'] = $moduldatas;
					$data['moduldelete'] = $moduldelete;
					$data['page'] = $page;
					$data['nextpage'] = $nextpages[$page];
				break;
				case 'lists':
					// Listák
					if(isset($params['lists'])){
						$lists = explode(",", $params['lists']);
						foreach ((array)$lists as $list) {
							$cat = new Categories(false, array('controller' => $this));
							$ld = $cat->getList($list);
							$terms = $cat->getTree($list);
							$data[lists][$list] = $ld;
							while ( $terms->walk() ) {
								$value_prefix = '';

								if($terms->getDeep() != 0) {
									$value_prefix = str_repeat('–', $terms->getDeep()).' ';
								}
								$tid = (int)$terms->getID();
								$data[terms][$list][':'.$tid] = array(
									'id' => $tid,
									'value' => $value_prefix.$terms->getName(),
									'slug' => $terms->getSlug(),
									'deep' => (int)$terms->getDeep(),
									'sort' => (int)$terms->getSortIndex(),
									'parent' => (int)$terms->getParentID()
								);
							}
						}
					} else {
						$filters = (array)json_decode($params['filters'], true);
						$cat = new Categories(false, array('controller' => $this));
						$termlist = $cat->getTermList($filters);
						$terms = array();
						foreach ((array)$termlist as $t) {
							$list = $t[termkey];
							$cat = new Categories(false, array('controller' => $this));
							$ld = $cat->getList($list);
							$terms = $cat->getTree($list);
							$data[lists][$list] = $ld;
							while ( $terms->walk() ) {
								$data[terms][$list][':'.(int)$cat->getID()] = array(
									'id' => (int)$cat->getID(),
									'value' => $cat->getName(),
									'slug' => $cat->getSlug(),
									'deep' => (int)$cat->getDeep(),
									'sort' => (int)$terms->getSortIndex(),
									'parent' => (int)$terms->getParentID()
								);
							}
						}
					}

				break;
				case 'getad':
					$user = $params['userid'];
					$success = true;
					$errmsg = false;
					$datas = array();
					$arg = array();

					$allasok = new Allasok(array(
						'controller' => $this,
						'admin' => true
					));

					$datas = $allasok->load((int)$params['adid'])->get();

					$data['params'] = $params;
					$data['data'] = $datas;
					$data['success'] = $success;
					$data['msg'] = $errmsg;
				break;
				case 'adsrequest':
					$id = ((int)$params['id'] == 0) ? false : (int)$params['id'];
					$success = true;
					$errmsg = false;
					$datas = json_decode($params['data'], true);
					$arg = array();

					$userid = $this->ME->getID();

					$allasok = new Allasok(array(
						'controller' => $this,
						'admin' => true
					));

					$request = $allasok->requestAd($userid, $id);

					if ($request === false) {
						$success = false;
						$errmsg = $this->lang('Hiányzó felhasználó ID, vagy ajánlat ID. Jelentkezzen be újra.');
					} else if($request === true) {
						$success = false;
						$errmsg = $this->lang('Ön már korábban leadta jelentkezését.');
					} else {
						$success = true;
						$data['hashkey'] = $request;
					}

					$data['params'] = $params;
					$data['data'] = $datas;
					$data['success'] = $success;
					$data['msg'] = $errmsg;
				break;
				case 'adscreator':
					$admin = ($params['admin'] == 1) ? true : false;
					$id = ((int)$params['id'] == 0) ? false : (int)$params['id'];
					$user = $params['userid'];
					$by = $params['by'];
					$success = true;
					$errmsg = false;
					$datas = json_decode($params['data'], true);
					$arg = array();

					if ($by == 'me') {
						$userid = $this->ME->getID();
					} else if(!empty($by)) {
						$userid = (int)$by;
					}

					$allasok = new Allasok(array(
						'controller' => $this,
						'admin' => true
					));

					// Adatok ellenőrzése mentés esetén
					if ($id) {
						$allas_adat = $allasok->load($id)->get();
						$allas_author_id = (int)$allas_adat['author_id'];
						if($by == 'me' && $allas_author_id != $userid){
							$success = false;
							$errmsg = $this->lang('Ön nem jogosult a hirdetés módosítására.');
						}
					}

					if ($success) {
						$datas['author_id'] = $userid;
						$creator_item_id = $allasok->creator($id, $datas);
					}
					$data['creating'] = ($id) ? false : true;
					if (!$id) {
						$data['created_item'] = $creator_item_id;
					}
					$data['params'] = $params;
					$data['data'] = $datas;
					$data['success'] = $success;
					$data['msg'] = $errmsg;
				break;
				case 'adslist':
					$author = $params['author'];
					$success = true;
					$errmsg = false;
					$datas = array();
					$arg = array();

					if ($author == 'me') {
						$arg['author_id'] = $this->ME->getID();
					} else if(!empty($author)) {
						$arg['author_id'] = (int)$author;
					}

					$allasok = new Allasok(array(
						'controller' => $this,
						'admin' => true
					));
					$allasok->getTree($arg);

					if ($allasok->Count() != 0) {
						while ($allasok->walk()) {
							$datas[] = $allasok->get();
						}
					}
					$data['qry_arg'] = $arg;
					$data['params'] = $params;
					$data['data'] = $datas;
					$data['success'] = $success;
					$data['msg'] = $errmsg;
				break;
				case 'messanger_messages':
					$arg = array();
					$group = $params['by'];
					$uid = (int)$this->ME->getID();
					parse_str($params[getstr], $getstr);

					if (isset($getstr['ad']) && !empty($getstr['ad'])) {
						$arg['onlybyad'] = (int)$getstr['ad'];
					}

					if (isset($getstr['byadmin']) && !empty($getstr['byadmin'])) {
						$arg['onlybyadmin'] = (int)$getstr['byadmin'];
					}

					if (isset($getstr['touser']) && !empty($getstr['touser'])) {
						$arg['touser'] = (int)$getstr['touser'];
					}

					if (isset($getstr['toemail']) && !empty($getstr['toemail'])) {
						$arg['useremail'] = $getstr['toemail'];
					}

					if (isset($params['for']) && $params['for'] == 'admin') {
						$arg['admin'] = true;
					}

					switch ($group) {
						case 'msg':
							$arg['controll_by'] = 'msg';
						break;
						case 'inbox':
							$arg['controll_by'] = $group;
						break;
						case 'outbox':
							$arg['controll_by'] = $group;
						break;
						case 'archiv':
							$arg['show_archiv'] = true;
						break;
					}

					$messages = $this->MESSANGER->loadMessages($uid, $arg);
					$unreaded = $messages['unreaded_group'];

					$data['uid'] = $uid;
					$data['str'] = $getstr;
					$data['unreaded'] = $unreaded;
					$data['messages'] = $messages;
				break;
				case 'messanger_messagesession_edit':
					$err = false;

					if (!$err && empty($params['value'])) {
						$msg = 'Kérjük, hogy adja meg a megjegyzését.';
						$err = true;
					}

					if( !$err ) {
						$messages = $this->MESSANGER->editMessageData($params['session'], $params['what'], $params['value']);
						$data['success'] = true;
					} else {
						$data['success'] = false;
						$data['msg'] = $msg;
					}

					$data['session'] = $params['session'];
					$data['value'] = $params['value'];
					$data['key'] = $params['what'];

				break;
				case 'messanger_message_send':
					$err = false;

					if (!$err && empty($params['msg'])) {
						$msg = $this->lang('Kérjük, hogy írja be üzenetének tartalmát.');
						$err = true;
					}

					if( !$err ) {
						$isadmin = ((int)$params['admin'] == 0) ? false : true;
						try {
							$message_id = $this->MESSANGER->addMessage($params['session'], (int)$params['from'], (int)$params['to'], $params['msg'], $isadmin);
							$data['success'] = true;
							$data['created_msg_id'] = $message_id;
						} catch (Exception $e) {
							$data['success'] = false;
							$data['msg'] = $e->getMessage();
						}
					} else {
						$data['success'] = false;
						$data['msg'] = $msg;
					}

					$data['session'] = $params['session'];
					$data['from_id'] = (int)$params['from'];
					$data['to_id'] = (int)$params['to'];
					$data['admin'] = (int)$params['admin'];
					$data['message'] = $params['msg'];
				break;
				case 'messanger_message_viewed':
					$session = $params['session'];
					$this->MESSANGER->setReadedMessage($params['by'], $session);
				break;
			}
			echo json_encode( $data );
		}

		public function box()
		{
			$this->hidePatern = true;

			extract($_POST);

			$this->root = $type;
			$this->path = __FUNCTION__;

			foreach ( $_POST as $key => $value ) {
				$this->out( $key, $value );
			}

			// Admin
			$lang_admin = array_merge (
	            $this->lang->loadLangText( 'adminobject', true )
	        );
			$this->admins = new Admins( array(
				'db' 		=> $this->db,
				'smarty' 	=> $this->smarty,
				'lang' 		=> $lang_admin,
				'view' 		=> $this->getAllVars()
			));
			$this->admin = $this->admins->get();
			$this->out( 'admin', $this->admin );


			switch ( $type ) {
				// Szolgáltatás megrendelése
				case 'service_order_ad':

					$user = $this->getVar('user');

					if( $servicetype == 'ad') {

						$lang = $this->lang->loadLangText( 'services', true );

						$service = new AdServices( array(
							'db' => $this->db,
							'settings' => $this->settings,
							'lang' => $lang
						) );

						$service->getAd($adid);

						// Csomag adatok
						$this->out( 'ad', $service );
					}



					$this->out( 'ad_nap', $serviveadday );
					$this->out( 'title_sub', $service->getTitle());
				break;

				// Szolgáltatás megrendelése
				case 'service_order':

					$user = $this->getVar('user');

					if( $servicetype == 'extra') {

						$lang = $this->lang->loadLangText( 'services', true );

						$service = new Services( array(
							'db' 		=> $this->db,
							'settings' 	=> $this->settings,
							'lang' 		=> $lang
						) );

						$service->getAd($adid);

						// Csomag adatok
						$this->out( 'ad', $service );
					}

					$this->out( 'ad_nap', $serviveadday );
					$this->out( 'title_sub', $service->getTitle());
				break;

				// ADMIN - Egyenleg jóváírás
				case 'employer_balance':
					$allowed = true;

					// Felh. adatai
					$felh = new User(
						$uid,
						array(
							'db' => $this->db,
							'settings' => $this->settings
						)
					);


					if ( !$this->admin->logged ) {
						$allowed = false;
					}

					$this->out( 'type_transfer_topup', 		\PortalManager\User::BALANCE_TRANSACTION_TRANSFER );
					$this->out( 'type_transfer_addition', 	\PortalManager\User::BALANCE_TRANSACTION_ADDITION );
					$this->out( 'felh', $felh );
					$this->out( 'allowed', $allowed );

				break;

				// ADMIN - Egyenleg jóváírás
				case 'employer_add_package':
					// Felh. adatai
					$felh = new User(
						$uid,
						array(
							'db' => $this->db,
							'settings' => $this->settings
						)
					);

					// Hirdetés szolgáltatások
					$lang = $this->lang->loadLangText( 'services', true );
					$services = new AdServices( array(
						'db' 		=> $this->db,
						'settings' 	=> $this->settings,
						'lang' 		=> $lang,
						'filters'   => array(
							'admin' => 1,
							'hide_offline' => 1
						)
					) );
					$services->getList();

					$this->out( 'ad_services', 	$services );
					$this->out( 'felh', $felh );

				break;

				case 'user_profil':
					// $uid - felh. ID

					// Felh. adatai
					$felh = new User(
						$uid,
						array(
							'db' => $this->db,
							'settings' => $this->settings
						)
					);
					$this->out('felh', $felh);

					// Jelentkezés megnézésének logolása
					if( $isjobapplicants ) {
						if( !empty($hid) && !empty($uid) ) {
							$this->db->query("UPDATE ".\PortalManager\Users::TABLE_APPLICANT." SET megtekintve = 1 WHERE megtekintve = 0 and hird_id = $hid and felh_id = $uid;");
						}
					}

					// Kompetencia adatok
					$c = (new Categories( \PortalManager\Categories::TYPE_KOMPETENCIAK, array( 'db' => $this->db ) ))->getTree();
					$this->out( 'kompetenciak', $c );

					// Kompetencia ID-k
					$this->out( 'kompetencia_id', explode(",",$felh->getKompetenciak()) );

					// Felhasználó formázott születési ideje
					$birth_date = false;

					if( $felh->getBithDate() ) {
						$birth_date = \Helper::replaceMonths( date( $this->User->dateformat, strtotime($felh->getBithDate()) ), strtolower($this->settings['language']) );
					}

					$this->out( 'birth_date', $birth_date );

				break;

				case 'app_for_job':

					// Hirdetés adatai
					$this->out( 'app', new Ad( $job, array( 'admin' => true, 'db' => $this->db, 'settings' => $this->settings ) ) );
				break;

				case 'send_message_to_user': case 'send_message':
					// Felh. adatai
					$felh = new User(
						$uid,
						array(
							'db' => $this->db,
							'settings' => $this->settings
						)
					);

					// Emp adatai
					$emp 	= $this->getVar('user');
					$empid 	= $emp['data']['ID'];

					$employer = new User(
						$empid,
						array(
							'db' => $this->db,
							'settings' => $this->settings
						)
					);

					$message = 'Tisztelt '.$felh->getName().'!';
					$message .= "\r\n\r\n";
					$message .= "Üzenet...";

					$message .= "\r\n\r\n";
					$message .= "--";
					$message .= "\r\n";
					$message .= "Üdvözlettel,";
					$message .= "\r\n";
					$message .= $employer->getValue('contact_name') . " &mdash; " . $employer->getName();

					$this->out('felh', $felh);
					$this->out('message', $message);

				break;
			}
		}

		public function get()
		{
			extract($_POST);

			$this->hidePatern 	= true;
			$this->path 		= __FUNCTION__;
			$this->root 		= $type;

			foreach ( $_POST as $key => $value ) {
				$this->out( $key, $value );
			}

			switch ( $type ) {
				case 'adminotify':
					$params = array();
					$output = array(
						'waiting_ad_applicant' => 0,
						'unwatched_messages' => 0
					);

					$msg = $this->db->query("SELECT
						count(m.sessionid)
					FROM ".\PortalManager\Messanger::DBTABLE_MESSAGES." as m
					LEFT OUTER JOIN ".\PortalManager\Messanger::DBTABLE." as ms ON ms.sessionid = m.sessionid
					WHERE m.admin_readed_at IS NULL")->fetchColumn();
					$output['unwatched_messages'] = (int)$msg;

					$app = $this->db->query("SELECT count(r.ID) FROM ".\FlexTimeResort\Allasok::DB_REQUEST_X." as r WHERE r.finished = 0 and r.admin_pick IS NULL")->fetchColumn();
					$output['waiting_ad_applicant'] = (int)$app;

					echo json_encode( $output );
				break;
			}
		}

		function __destruct(){
			// RENDER OUTPUT
			parent::bodyHead();															# HEADER
			if(!$this->hidePatern) {
				$this->displayView( __CLASS__.'/'.$this->path.'/'.$this->root, true );		# CONTENT
			}
			parent::__destruct();														# FOOTER
		}
	}

?>
