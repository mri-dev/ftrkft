<?
use PortalManager\Admins;
use PortalManager\Form;
use PortalManager\Categories;
use PortalManager\Category;
use PortalManager\UserList;
use PortalManager\User;
use PortalManager\Menus;
use ExceptionManager\RedirectException;
use PortalManager\Pages;
use MailManager\Mailer;

class cp extends Controller {
	private $admin;
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
			case 'pages_save':
				$pages = new Pages( $_POST['page_id'], array( 'db' => $this->db )  );
				try{
					$pages->save( $_POST['data'] );
					\PortalManager\Form::formDone( 'Változások mentésre kerültek!', false, $return_url );
				}catch(RedirectException $e){
					$e->redirect();
				}
			break;
			case 'pages_create':
				$pages = new Pages( $_POST['page_id'], array( 'db' => $this->db )  );
				try{
					$pages->add( $_POST['data'] );
					\PortalManager\Form::formDone( 'Oldal sikeresen létrehozva!', false, $return_url );
				}catch(RedirectException $e){
					$e->redirect();
				}
			break;
		}
	}

	public function menu()
	{
		$menus = new Menus();
		$menus->getTree();
		$this->out("menus", $menus);
	}

	public function settings()
	{
		# code...
	}

	public function pages()
	{
		$pages = new Pages( $this->gets[2], array(
			'db' => $this->db )
		);
		$pages->setAdmin( true );

		switch( $this->gets[1] ){
			case 'szerkeszt': case 'torles':
				$this->out( 'page', $pages->get( $this->gets[2]) );
			break;
		}

		if( $this->gets[2] == 'szerkeszt' || $this->gets[2] == 'torol' ) {
			$this->out( 'page', $pages->get( $this->gets[3] ) );
		}

		// Oldal fa betöltés
		$page_tree 	= $pages->getTree();
		$this->out( 'pages', $page_tree );

		$this->out('tooltip_gyujto', \PortalManager\Formater::tooltip('A gyűjtő oldalnak jelölt oldalaknál nincs tartalom megjelenítés. Csak arra szolgál, hogy fa szerkezetbe rendezzük és összefogjunk egy adott témakörrel foglalkozó oldalakat.') );
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
		$filters['user_group'] 	= $this->settings['USERS_GROUP_USER'];
		$filters['orderby'] 	= 'u.nev ASC';

		$users = new UserList( array(
			'db' => $this->db,
			'settings' => $this->settings,
			'filters' => $filters
		) );

		$users->getList();
		$this->out( 'lista', $users );
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
		$this->displayView( __CLASS__.'/index', true );		# CONTENT
		parent::__destruct();				# FOOTER
	}
}
?>
