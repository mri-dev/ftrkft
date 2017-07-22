<?
use PortalManager\Admins;
use PortalManager\Form;
use PortalManager\Categories;
use PortalManager\Category;
use PortalManager\UserList;
use PortalManager\User;
use PortalManager\Menus;
use PortalManager\Articles;
use PortalManager\Pagination;
use PortalManager\Messanger;
use ExceptionManager\RedirectException;
use PortalManager\Pages;
use MailManager\Mailer;
use DesignCreator\FormDesigns;
use FlexTimeResort\Allasok;
use FlexTimeResort\Requests;

class cp extends Controller {
	private $admin;
	private $temp = '';
	public $ctrl = false;
	function __construct(){
		$this->ctrl = parent::__construct( array(
			'root' => 'cp'
		) );
		parent::$pageTitle = 'ADMIN';

		$form = new Form( $_GET['response'] );
		$this->out( 'form', $form );

		$this->admins = new Admins( array(
			'db' => $this->db,
			'smarty' => $this->smarty,
			'view' => $this->getAllVars()
		));
		$this->admin = $this->admins->get();
		$this->root = '/'.__CLASS__.'/';

		$this->out( 'admin', $this->admin );
		$this->out( 'root', $this->root );
		$this->out( 'admin_css', '/'.str_replace('templates/','',$this->smarty->getTemplateDir(0)).'assets/css/media.css');

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

	function test()
	{
		// Aktiváló e-mail kiküldése
		/*$mail = new Mailer( $this->settings['page_title'], $this->settings['email_noreply_address'], $this->settings['mail_sender_mode'] );

		$mail->add( 'molnar.istvan@web-pro.hu' );
		$mail->setSubject( 'Teszt' );
		$mail->setMsg( $this->smarty->fetch( 'mails/hu/test.tpl' ) );

		$re = $mail->sendMail();
*/
		print_r($re);
	}

	function forms() {
		$this->hidePatern = true;

		$return_url = $_POST['return'];

		switch( $_POST['for'] ) {
			case 'login_admin':
				try {
					$this->admins->login( $_POST );
					\PortalManager\Form::formDone( 'Sikeresen bejelentkezett!', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'settings_basic':
				try {
					$this->admins->saveSettings( $_POST['data'] );
					\PortalManager\Form::formDone( 'Változások mentésre kerültek!', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
		}
	}

	public function messanger()
	{
		$filter_arr = $_GET;
		unset($filter_arr['tag']);

		if (isset($_GET['msgid']) && !empty($_GET['msgid'])) {
			$msgsession = rtrim($_GET['msgid'], '/');
		}

		$this->out('msgsession', $msgsession);
		$this->out( 'filter_arr', $filter_arr);
	}

	public function ads()
	{
		switch ( $this->gets[2] ) {
			case 'editor':
				$this->temp = '/'.$this->gets[2];

				$this->out( 'modid', (int)$this->gets[3]);
				$formdesign = new FormDesigns();
				$this->out('formdesigns', $formdesign);
			break;
			case 'requests':
				$this->temp = '/'.$this->gets[2];
				$filter_arr = $_GET;
				unset($filter_arr['tag']);

				$requests = new Requests(array(
					'controller' => $this->ctrl
				));

				if (isset($_GET['pickrequest'])) {
					$requestHash = $_GET['pickrequest'];

					try {
						$requests->pick($this->admin->getID(), $requestHash);
						\Helper::reload($this->getVar('root') . 'ads/requests/?opened='.$requestHash.'&hlad='.$_GET['hlad']);
					} catch (Exception $e) {
						$this->out("requestError", $e->getMessage());
						$this->out("link_back_list", true);
					}
				}

				if (isset($_POST['requestAction'])) {
					switch ($_POST['requestAction']) {
						case 'decline':
							$requestHash = $_GET['setdecline'];
							$status = $requests->setDecline($this->admin->getID(), $requestHash);
							if ($status) {
								\Helper::reload($this->root.'ads/requests/?ownpicked=1');
							}
						break;
						case 'setallow':
							$requestHash = $_GET['setallow'];
							$show_author_info = (isset($_POST['show_author_info'])) ? true : false;
							$status = $requests->setAllow($this->admin->getID(), $requestHash, $show_author_info);
							if ($status) {
								\Helper::reload($this->root.'ads/requests/?opened='.$requestHash.'&hlad='.$_GET['hlad']);
							}
						break;
						case 'createMessanger':
							$messanger = new Messanger(array('controller' => $this->ctrl));
							try {
								$session = $messanger->createSession( $_POST );

								if ($session) {
									\Helper::reload($this->root.'messanger/session/'.$session.'/?justcreated=1'); exit;
								}
							} catch (\Exception $e) {
								$this->out("requestError", $e->getMessage());
							}
						break;
					}
				}

				$filters = array();
				if (isset($_GET['accepts'])) {
					$filters['accepted'] = (int)$_GET['accepts'];
				}

				if (isset($_GET['onlyunpicked'])) {
					$filters['onlyunpicked'] = true;
				}

				if (isset($_GET['undown'])) {
					$filters['undown'] = true;
				}

				if (isset($_GET['onlyaccepted'])) {
					$filters['onlyaccepted'] = true;
				}

				if (isset($_GET['onlydeclined'])) {
					$filters['onlydeclined'] = true;
				}

				if (isset($_GET['ownpicked'])) {
					$filters['onlypickedby'] = (int)$this->admin->getID();
				}

				if (isset($_GET['hlad'])) {
					$filters['ad_ids'] = (array)$_GET['hlad'];
				}

				$arg = array();
				$arg['filters'] = $filters;

				$requests->getTree($arg);
				$this->out( 'requests', $requests);
				$this->out( 'filter_arr', $filter_arr);
			break;
			default:
				$allasok = new Allasok(array(
					'controller' => $this->ctrl,
					'admin' => true
				));
				$filtered = false;
				$arg = array();
				$filters = array();

				if(!empty($_GET['ID'])) {
					$filters['ID'] = $_GET['ID'];
				}
				if(!empty($_GET['s'])) {
					$filters['search'] = $_GET['s'];
				}

				if(!empty($_GET['meta']['cat_type'])) {
					$filters['meta']['hirdetes_tipus'] = (int)$_GET['meta']['cat_type'];
				}

				if(!empty($_GET['meta']['cat_kategoria'])) {
					$filters['meta']['hirdetes_kategoria'] = (int)$_GET['meta']['cat_kategoria'];
				}

				$filtered = (count($filters) > 0) ? true : false;

 				$arg['limit'] = 20;
				$arg['filters'] = $filters;
				$arg['page'] = ($this->gets[2] != '') ? (int)$this->gets[2] : 1;

				$allasok->getTree($arg);

				$this->out( 'pagination', (new Pagination(array(
					'max' => $allasok->total_pages,
					'current' => $allasok->current_page,
					'root' => '/'.$this->subfolder . 'ads',
					'after' => '?'.http_build_query($filters),
					'lang' => $this->LANGUAGES->texts
				)))->render());

				$this->out( 'lista', $allasok);
				$this->out( 'filtered', $filtered);
			break;
		}
	}

	public function requests()
	{
		# code...
	}

	public function terms()
	{
		$categories = new Categories(false, array('controller' => $this ));
		$this->out('term_list', $categories->getTermList());

		if (empty($_GET['groupkey'])) {
			switch ($_GET['mod']) {
				case 'edit': case 'del':
					$this->out('check', $categories->getList($_GET['id']));
				break;
			}
		} else {
			$this->out('list', $categories->getList($_GET['groupkey']));

			$categories->getTree($_GET['groupkey']);
			$this->out('terms', $categories);
			switch ($_GET['mod']) {
				case 'edit': case 'del':
				 	$cat = new Category($_GET['groupkey'], $_GET['id']);
					$this->out('check', $cat);
				break;
			}
		}
	}

	public function cikkek()
	{
		$id = false;

		$ctrl = new Articles(false, array(
			'admin' => true,
			''
		));
		$ctrl->getTree(array(
			'orderby' => 'create_at',
			'order' => 'DESC'
		));

		$this->out("ctrl", $ctrl);

		if ($this->gets[2] == 'edit' || $this->gets[2] == 'del') {
			$id = (int)$this->gets[3];
			$this->out("check", $ctrl->get($id));
		}
	}

	public function menu()
	{
		$menuid = false;
		if ($this->gets[2] == 'edit' || $this->gets[2] == 'del') {
			$menuid = (int)$this->gets[3];
		}

		$menus = new Menus();
		$menus->getTree(false, array('admin' => true));

		$this->out("menus", $menus);
		$this->out("check", $menus->get($menuid));
	}

	public function oldalak()
	{
		$id = false;
		if ($this->gets[2] == 'edit' || $this->gets[2] == 'del') {
			$id = (int)$this->gets[3];
		}

		$ctrl = new Pages();
		$ctrl->setAdmin(true);
		$ctrl->getTree();

		$this->out("ctrl", $ctrl);
		$this->out("check", $ctrl->get($id));
	}

	public function employers()
	{
		$filters = array();
		$filters['user_group'] 	= $this->settings['USERS_GROUP_EMPLOYER'];
		$filters['orderby'] 	= 'u.nev ASC';

		$users = new UserList( array(
			'db' => $this->db,
			'settings' => $this->settings,
			'filters' => $filters
		) );

		$users->getList();
		$this->out( 'lista', $users );
	}

	public function users()
	{
		$filters = array();
		$arg = array();
		$users = $this->USERS;

		$sub = $_GET['sub'];

		switch ($sub)
		{
			// Szerkesztés
			case 'edit':
				$this->temp = '/'.$sub;
				$user = new User($_GET[id], array('controller' => $this));

				$this->out('usergroups', $this->USERS->user_groups);
				$this->out('userdetails', $this->USERS->getUserDetails($user->getUserGroup()));
				$this->out('user', $user);
			break;

			// Létrehozás
			case 'create':
				$this->temp = '/'.$sub;

				$this->out('usergroups', $this->USERS->user_groups);
				$this->out('userdetails', $this->USERS->getUserDetails(-1));
			break;

			// Törlés
			case 'del':
				$this->temp = '/'.$sub;
				$user = new User($_GET[id], array('controller' => $this));

				$this->out('user', $user);
			break;

			// Lista
			default:
				$usergroup = ($_GET['user_group'] != '') ? (int)$_GET['user_group'] : -1;
				$filters['id'] = $_GET['id'];
				$filters['emailname'] = $_GET['emailname'];
				$filters['engedelyezve'] = $_GET['engedelyezve'];
				$arg['filters'] = $filters;
				$arg['limit'] = 50;
				$arg['page'] = ($_GET[page] != '') ? (int)$_GET['page'] : 1;

				$list = $users->getUserList( $arg, $usergroup );

				$this->out( 'pagination', (new Pagination(array(
					'max' => $list[info][pages][max],
					'current' => $list[info][pages][current],
					'root' => '/'.$this->subfolder . 'users',
					'after' => '?'.http_build_query($filters),
					'lang' => $this->LANGUAGES->texts
				)))->render());

				$this->out( 'lista', $list );
				$this->out( 'usergroups', $this->USERS->user_groups);
			break;
		}
	}

	public function logout()
	{
		$this->hidePatern = true;
		$this->admins->logout();
		Helper::reload( $this->settings['admin_root'] );
	}

	function __destruct(){
		// RENDER OUTPUT
		parent::bodyHead();					# HEADER
		if ($this->admin->logged) {
			$this->displayView( __CLASS__.$this->temp.'/index', true );		# CONTENT
		}
		parent::__destruct();				# FOOTER
	}
}
?>
