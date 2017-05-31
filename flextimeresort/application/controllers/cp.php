<?
use PortalManager\Admins;
use PortalManager\Form;
use PortalManager\Categories;
use PortalManager\Category;
use PortalManager\UserList;
use PortalManager\User;
use PortalManager\Menus;
use PortalManager\Pagination;
use ExceptionManager\RedirectException;
use PortalManager\Pages;
use MailManager\Mailer;

class cp extends Controller {
	private $admin;
	private $temp = '';
	function __construct(){
		parent::__construct( array(
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

		$this->out( 'admin', 	$this->admins->get() );
		$this->out( 'root', 	'/'.__CLASS__.'/' );
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

	public function terms()
	{
		$categories = new Categories();
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
		$this->displayView( __CLASS__.$this->temp.'/index', true );		# CONTENT
		parent::__destruct();				# FOOTER
	}
}
?>
