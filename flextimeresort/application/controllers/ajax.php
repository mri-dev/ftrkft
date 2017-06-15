<?
use PortalManager\User;
use PortalManager\Categories;
use PortalManager\Ad;
use PortalManager\AdServices;
use PortalManager\Services;
use PortalManager\Admins;

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
					// Alapadatok
					$data['alap']['name'] = $this->ME->getName();
					$data['alap']['email'] = $this->ME->getEmail();
					$data['alap']['profil_kep'] = $this->ME->getProfilImg();
					$data['alap']['szuletesi_datum'] = $this->ME->getAccountData('szuletesi_datum');
					$data['alap']['anyanyelv'] = (int)$this->ME->getAccountData('anyanyelv');
					$data['alap']['nem'] = (int)$this->ME->getAccountData('nem');
					$data['alap']['allampolgarsag'] = (int)$this->ME->getAccountData('allampolgarsag');
					$data['alap']['csaladi_allapot'] = (int)$this->ME->getAccountData('csaladi_allapot');
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
				case 'profilsave':
					$nextpages = array(
						'alap' => 'elerhetoseg'
					);
					$form = json_decode($params['form'], true);
					$page = $params['page'];

					$profildata = array();
					$profildetails = array();

					$profildata['name'] = $form['name'];
					$profildetails['szuletesi_datum'] = $form['szuletesi_datum'];
					$profildetails['anyanyelv'] = (int)$form['anyanyelv'];
					$profildetails['nem'] = (int)$form['nem'];
					$profildetails['allampolgarsag'] = (int)$form['allampolgarsag'];
					$profildetails['csaladi_allapot'] = (int)$form['csaladi_allapot'];

					if (isset($form['newprofilimg'])) {
						$this->ME->changeProfilImg($form['newprofilimg']);
					}

					$re = $this->ME->saveProfil($profildata, $profildetails);

					$data['form'] = $form;
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
								$data[terms][$list][(int)$cat->getID()] = array(
									'id' => (int)$cat->getID(),
									'value' => $cat->getName(),
									'slug' => $cat->getSlug(),
								);
							}
						}
					} else {
						$cat = new Categories(false, array('controller' => $this));
						$termlist = $cat->getTermList();
						$terms = array();
						foreach ((array)$termlist as $t) {
							$list = $t[termkey];

							$cat = new Categories(false, array('controller' => $this));
							$ld = $cat->getList($list);
							$terms = $cat->getTree($list);

							$data[lists][$list] = $ld;

							while ( $terms->walk() ) {
								$data[terms][$list][(int)$cat->getID()] = array(
									'id' => (int)$cat->getID(),
									'value' => $cat->getName(),
									'slug' => $cat->getSlug(),
								);
							}
						}
					}

				break;
				case 'messanger_messages':
					$arg = array();
					$group = $params['by'];
					$uid = (int)$this->ME->getID();

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
				case 'me':

				break;
				case 'job_mode_studies_hint':
					$params = array();

					foreach ( $_POST as $key => $value ) {
						$params[$key] = $value;
					}

					$output = array(
						'error' 	=> 0,
						'msg' 		=> null,
						'params' 	=> $params
					);

					$src = $this->db->query("SELECT id, neve as value FROM ".\PortalManager\Categories::TYPE_STUDIES." WHERE neve LIKE '%$search%';");

					$result = $src->fetchAll( \PDO::FETCH_ASSOC );

					$list = array();

					if ( $src->rowCount() != 0 && !empty( $search ) )  {
						foreach ( $result as $res ) {
							// Egyezés százalék
							similar_text( $res['value'], $search, $res['similar_percent'] );
							$res['order'] = round($res['similar_percent'] * 100);

							$res['value'] = preg_replace('/'.$search.'/i', '<strong>'.$search.'</strong>', $res['value']);

	 						$list[] = $res;
						}

						// Egyezés alapján sorba rendezés
						// előre kerülnek azok az elemek, ahol nagyobb az egyezési
						// arány
						usort( $list, function($a, $b) {
						    return $b['order'] - $a['order'];
						});
					} else {
						$list = false;
					}

					$output['search'] = $list;

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
