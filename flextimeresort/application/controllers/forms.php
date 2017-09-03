<?php 
use PortalManager\User;
use PortalManager\Users;
use PortalManager\Admins;
use PortalManager\Form;
use PortalManager\Menus;
use PortalManager\Pages;
use PortalManager\Articles;
use PortalManager\Categories;
use PortalManager\Category;
use ExceptionManager\RedirectException;

class forms extends Controller {
	public $ctrl;
	function __construct(){
		$this->ctrl = parent::__construct();

		if( empty($_POST) ) {
			Helper::reload('/');
		}

		$form = new Form( $_GET['response'] );
		$this->out( 'form', $form );

		// SEO Információk
		$SEO = null;
	}

	function terms()
	{
		$this->hidePatern = true;
		$return_url = $_POST['return'];

		$id = (isset($_POST['id'])) ? $_POST['id'] : false;

		$terms = new Categories(false, array('controller' => $this));

		switch( $_POST['for'] )
		{
			case 'addList':
				try {
					$terms->addList($_POST);
					\PortalManager\Form::formDone( 'Sikeresen létrehozta a tematikus lista elemet.', false, $return_url);
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'editList':
				try {
					$terms->editList($_POST);
					\PortalManager\Form::formDone( 'Sikeresen módosította a tematikus lista adatait.', false, $return_url);
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'delList':
				try {
					$terms->deleteList($_POST['id']);
					\PortalManager\Form::formDone( 'Sikeresen eltávolította a tematikus lista elemet.', false, $return_url);
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'add':
				$cat = new Category();
				try {
					$cat->add($_POST);
					\PortalManager\Form::formDone( 'Sikeresen létrehozta a tematikus lista elemet.', false, $return_url);
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'edit':
				$cat = new Category();
				try {
					$cat->edit($_POST);
					\PortalManager\Form::formDone( 'Sikeresen módosította a tematikus lista adatait.', false, $return_url);
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'del':
				$cat = new Category();
				try {
					$cat->delete($_POST['id']);
					\PortalManager\Form::formDone( 'Sikeresen eltávolította a tematikus lista elemet.', false, $return_url);
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
		}
	}

	function menu()
	{
		$this->hidePatern = true;
		$return_url = $_POST['return'];

		$menu_id = (isset($_POST['id'])) ? $_POST['id'] : false;

		$menus = new Menus($menu_id);

		switch( $_POST['for'] )
		{
			case 'add':
				try {
					$menus->add($_POST);
					\PortalManager\Form::formDone( 'Sikeresen létrehozta a menü elemet.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'edit':
				try {
					$menus->save($_POST);
					\PortalManager\Form::formDone( 'Sikeresen módosította a menü adatait.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'del':
				try {
					$menus->delete($_POST['id']);
					\PortalManager\Form::formDone( 'Sikeresen eltávolította a menü elemet.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
		}
	}

	public function pages()
	{
		$this->hidePatern = true;
		$return_url = $_POST['return'];

		$id = (isset($_POST['id'])) ? $_POST['id'] : false;

		$ctrl = new Pages($id);

		switch( $_POST['for'] )
		{
			case 'add':
				try {
					$ctrl->add($_POST);
					\PortalManager\Form::formDone( 'Sikeresen létrehozta az oldalt.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'edit':
				try {
					$ctrl->save($_POST);
					\PortalManager\Form::formDone( 'Sikeresen módosította az oldal adatait.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'del':
				try {
					$ctrl->delete($_POST['id']);
					\PortalManager\Form::formDone( 'Sikeresen eltávolította az oldalt.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
		}
	}

	public function articles()
	{
		$this->hidePatern = true;
		$return_url = $_POST['return'];

		$id = (isset($_POST['id'])) ? $_POST['id'] : false;

		$ctrl = new Articles($id);

		switch( $_POST['for'] )
		{
			case 'add':
				try {
					$ctrl->add($_POST);
					\PortalManager\Form::formDone( 'Sikeresen létrehozta a cikket.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'edit':
				try {
					$ctrl->edit($_POST);
					\PortalManager\Form::formDone( 'Sikeresen módosította a cikk adatait.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			case 'del':
				try {
					$ctrl->delete();
					\PortalManager\Form::formDone( 'Sikeresen eltávolította a cikket.', false, $return_url );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
		}
	}

	/**
	 * Regisztrációk
	 * */
	function register() {
		$this->hidePatern = true;

		// Users class
		$users = $this->USERS;

		$return_url = $_POST['return'];

		try {
			$users->add( $_POST[data] );
			\PortalManager\Form::formDone( $this->lang('REGISTER_SIKERES_MSG'), false, $return_url );
		} catch (RedirectException $e) {
			$e->redirect();
		}
	}

	/**
	 * Azonosítás / Belépés
	 * */
	function auth() {
		$this->hidePatern = true;

		// Users class
		$users = $this->USERS;

		$return_url = (isset($_GET['re'])) ? $_GET['re'] : $_POST['return'];

		try {
			$users->login( $_POST );
			\PortalManager\Form::formDone( $this->lang('SIKERESEN_BEJELENTKEZETT'), false, $return_url );
		} catch (RedirectException $e) {
			$e->redirect();
		}
	}

	/**
	 * Felhasználók
	 * **/
	public function user()
	{
		$this->hidePatern = true;

		$return_url = $_POST['return'];

		switch( $_POST['for'] ) {
			// Munkavállaló alapadatok módosítása
			case 'settings_basic':
				// Objects
		        $lang = array_merge (
		            $this->lang->loadLangText( 'class/users', true ),
		            $this->lang->loadLangText( 'mails', true )
		        );

		        $user = $this->getVar('user');

				try {
					$msg = $this->User->change( $user['data']['ID'], $user['data']['user_group'], $_POST['data'], $_POST['details'] );
					\PortalManager\Form::formDone( $msg, false, '/user/settings/', 'basic' );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;
			// Munkavállaló jelszó csere
			case 'settings_password':
		 		// Users class
				$users 	= new Users(array('controller' => $this->ctrl));
				$user 	= $this->getVar('user');

		    /* */
				try {
					$users->changePassword( $this->ME->getId(), $_POST['data'] );
					\PortalManager\Form::formDone($this->lang('PASSWORD_SUCCESS_CHANGE'), false, $return_url, 'jelszo' );
				} catch (RedirectException $e) {
					$e->redirect( 'jelszo' );
				}
				/* */
			break;
		}
	}

	public function admins()
	{
		$this->hidePatern = true;
		$return_url = $_POST['return'];

		$admins = new Admins( array(
			'db' => $this->db,
			'smarty' => $this->smarty,
			'view' => $this->getAllVars()
		));
		$admin = $admins->get();

		if (!$admin->logged) {
			\Helper::reload($return_url);
			 exit;
		}

		switch( $_POST['for'] )
		{
			/**
			* Felhasználók
			**/
			// Létrehozás
			case 'user_create':
			break;

			// Szerkesztés
			case 'user_edit':
				try {
					$msg = $this->USERS->change( $_POST['id'], $_POST['data'], $_POST['details'] );
					\PortalManager\Form::formDone( $msg, false, $return_url, 'settings' );
				} catch (RedirectException $e) {
					$e->redirect( 'settings' );
				}
			break;

			// Törlés
			case 'user_del':
				try {
					$msg = $this->USERS->delete( $_POST['id'] );
					\PortalManager\Form::formDone( 'Sikeresen törölte a felhasználót.', false, '/cp/users' );
				} catch (RedirectException $e) {
					$e->redirect();
				}
			break;

			// Jelszó csere
			case 'user_changepassword':
				try {
					$msg = $this->USERS->changePasswordByAdmin( $_POST['id'], $_POST['pw'] );
					\PortalManager\Form::formDone( $msg, false, $return_url, 'password' );
				} catch (RedirectException $e) {
					$e->redirect( 'password' );
				}
			break;
		}
	}

	/**
	 * Jelszó reszetelés
	 * **/
	public function resetpassword()
	{
		/* */
		try {
			$this->USERS->resetPassword( $_POST['data'] );
			\PortalManager\Form::formDone( $this->lang('RESETPASS_JELSZOGENERALAS_SIKERULT', array('email', $_POST['data']['email'])), false, $_POST['return'] );
		} catch (RedirectException $e) {
			$e->redirect();
		}
		/* */
	}

	function __destruct(){
		// RENDER OUTPUT
		parent::bodyHead();					# HEADER
		$this->displayView( __CLASS__.'/index', true );		# CONTENT
		parent::__destruct();				# FOOTER
	}
}
?>
