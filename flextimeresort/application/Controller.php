<?
use DatabaseManager\Database;
use PortalManager\Template;
use PortalManager\Users;
use PortalManager\User;
use PortalManager\Portal;
use Applications\Captcha;
use PortalManager\Lang;
use PortalManager\Menus;

class Controller
{
	public $title = '';
	public $smarty = null;
	public $hidePatern = true;
	public $subfolder = 'site/';
	public static $pageTitle;
	public static $user_opt = array();
	public $LANGUAGES;
	public $vars;
	public $db;

    function __construct($arg = array()){
        Session::init();
        Helper::setMashineID();
        $this->gets = Helper::GET();

		if ( $arg['root'] ) { $this->subfolder = $arg['root'].'/'; }

    /**
		* CORE
		**/
		// SMARTY
    $this->db = new Database();
    $template_root = VIEW . $this->subfolder . 'templates/';
    $this->smarty = new Smarty();
		$this->smarty->caching = false;
		$this->smarty->cache_lifetime = 0;
		$this->smarty->setTemplateDir( $template_root );
		$this->smarty->setCompileDir( VIEW . $this->subfolder . 'templates_c/' );
		$this->smarty->setConfigDir( './settings' );
		$this->smarty->setCacheDir( VIEW . $this->subfolder . 'cache/' );
		$this->smarty->configLoad( 'vars.conf' );

		define( 'IMG', '/'.VIEW . $this->subfolder . 'assets/images/' );
		define( 'STYLE','/'.VIEW . $this->subfolder . 'assets/css/' );
		define( 'JS', '/'.VIEW . $this->subfolder . 'assets/js/' );

    //////////////////////////////////////////////////////
    /**
    * LANGUAGES
    **/
    $this->LANGUAGES = new Lang( $this->smarty, array('db' => $this->db) );
		$this->LANGUAGES->loadTexts();

		// SETTINGS
		$this->settings = $this->getAllValtozo();

		$this->USERS = new Users( array(
			'controller' => $this
		) );

		$this->out('menu_header', $this->menu_load('header'));
		$this->out('menu_footer_left', $this->menu_load('footer_left'));
		$this->out('menu_footer_center', $this->menu_load('footer_center'));
		$this->out('menu_footer_right', $this->menu_load('footer_right'));
		$this->out('tematic_list', $this->tematikus_lista());

    $user = $this->USERS->get( self::$user_opt );

		$me = new User(
			$user['data']['ID'],
			array(
				'db' => $this->db,
				'lang' => $lang_users,
				'smarty' => $this->smarty,
				'settings' => $this->settings
			)
		);

		/**
		* VARS
		**/
		$this->out( 'GETS', $this->gets );
		$this->out( 'settings', $this->settings );
		$this->out( 'template_root', $template_root );
		$this->out( 'defaultlang', $this->LANGUAGES->isDefaultLangNow());

		if( $_GET['logout'] == '1' ) {
        $this->USERS->logout();
        header('Location: /');
    }

    $this->loadAllVars();

		$this->smarty->registerPlugin('function', 'lang', array($this, 'language_translator'));

    if( $_GET['start'] == 'off' ) {
    	setcookie( 'stredir', '1', time() + 3600*24*365, '/' );
    }

  	if(!$arg[hidePatern]){ $this->hidePatern = false; }
  }

	function language_translator($params, &$smarty){
		$text = $this->LANGUAGES->texts;

		if(!isset($text[$params['text']])) {
			return $params['text'];
		}

		$text = $text[$params['text']];

		preg_match_all('/%(.*?)%/', $text, $match);

		if(!empty($match[0])) {
			foreach((array)$match[1] as $m) {
				if(isset($params[$m]) && !empty($params[$m])) {
					$text = str_replace('%'.$m.'%', $params[$m], $text);
				}
			}
		}

		return $text;
	}

    function out( $viewKey, $output ){
        $this->smarty->assign( $viewKey, $output );
    }

    function outSet( $set_array = array() ){
        foreach ($set_array as $key => $value) {
          $this->smarty->assign( $key, $value );
        }
    }

    public function getAllVars()
    {
       $vars = array();

       if( !$this->smarty ) return false;

       $list = $this->smarty->tpl_vars;

       foreach ( $list as $key => $value ) {
          $vars[$key] = $value->value;
       }

       return $vars;
    }

     public function loadAllVars()
    {
       $vars = array();

       if( !$this->smarty ) return false;

       $list = $this->smarty->tpl_vars;

       foreach ( $list as $key => $value ) {
          $this->vars[$key] = $value->value;
       }
    }

     public function getVar( $key )
    {
       $vars = $this->smarty->tpl_vars[$key]->value;

       return $vars;
    }

	function lang( $key, $sprinf_params = array() ){
		$text = $this->LANGUAGES->texts[$key];
		$params = $sprinf_params;

		preg_match_all('/%(.*?)%/', $text, $match);

		if(!empty($match[0])) {
			foreach((array)$match[1] as $m) {
				if(isset($params[$m]) && !empty($params[$m])) {
					$text = str_replace('%'.$m.'%', $params[$m], $text);
				}
			}

		}

		return $text;
	}

    function bodyHead($key = ''){
        $subfolder  = '';

        $this->theme_wire   = ($key != '') ? $key : '';

        if($this->getThemeFolder() != ''){
            $subfolder  = $this->getThemeFolder().'/';
        }

        # Oldal címe
        if(self::$pageTitle != null){
            $this->title = self::$pageTitle . ' | ' . $this->settings['page_title'];
        } else {
            $this->title = $this->settings['page_title'] .$this->settings['page_description'];
        }

        # Render HEADER
        if(!$this->hidePatern){
					$this->out( 'title', $this->title );
					$this->displayView( $subfolder.$this->theme_wire.'head' );
        }

        # Aloldal átadása a VIEW-nek
        $this->called = $this->fnTemp;
    }

	// Facebook content
	function addOG($type, $content){
		return '<meta property="og:'.$type.'" content="'.$content.'" />'."\n\r";
	}

	// Meta content
	function addMeta($name, $content){
		return '<meta name="'.$name.'" content="'.$content.'" />'."\n\r";
	}


  function setTitle($title){
      $this->title = $title;
  }

  function valtozok($key){
      $d = $this->db->query("SELECT bErtek FROM settings WHERE bKulcs = '$key'");
      $dt = $d->fetch(PDO::FETCH_ASSOC);

      return $dt[bErtek];
  }

  function getAllValtozo(){
      $v = array( );
      $d = $this->db->query("SELECT bErtek, bKulcs FROM settings");
      $dt = $d->fetchAll(PDO::FETCH_ASSOC);

      foreach($dt as $d){
          $v[$d[bKulcs]] = $d[bErtek];
      }

      $v['title'] = ' &mdash; ' . $v['page_slogan'];

      // Országkód: 1 = HU
      $v['country_id'] = 1;
       // Valuta
      $v['valuta'] = 'HUF';
       // Nyelv
      $v['language'] = 'hu';

      return $v;
  }

  function setValtozok($key,$val){
      $iq = "UPDATE settings SET bErtek = '$val' WHERE bKulcs = '$key'";
      $this->model->db->query($iq);
  }

  function setThemeFolder($folder = ''){
      $this->theme_folder = $folder;
  }

  protected function getThemeFolder(){
      return $this->theme_folder;
  }

	private function menu_load( $key = 'header', $szulo_id = false )
	{
		$menu = array();
		$where = '';

		if($szulo_id) {
			$where .= ' and szulo_id = ' . $szulo_id;
		} else {
			$where .= ' and szulo_id IS NULL ';
		}

		$iq = "SELECT ID, nev, url, szulo_id, langkey FROM menu WHERE lathato = 1 and gyujto = '{$key}' {$where} ORDER BY sorrend ASC;";

		$q = $this->db->query($iq)->fetchAll(\PDO::FETCH_ASSOC);

		if(count($q) == 0) return array();

		foreach ((array)$q as $d) {
			$d['child'] = $this->menu_load($key, $d['ID']);
			$menu[] = $d;
		}

		return $menu;
	}

	public function tematikus_lista()
	{
		$q = "SELECT
		cl.*
		FROM ".\PortalManager\Categories::DB_LIST." as cl
		ORDER BY cl.neve ASC
		";

		$data = $this->db->query($q)->fetchAll(\PDO::FETCH_ASSOC);
		return $data;
	}

	public function displayView( $tpl, $has_folder = false){
		$folder = '';

		if ( $has_folder ) {
			if( $this->subfolder != 'site/' ) {
				$tpl = str_replace( $this->subfolder, '', $tpl);
			}
			$folder = ($this->gets[1] ?: 'home') . '/';
		}

    $templateDir = $this->smarty->getTemplateDir();

		if( !file_exists( $templateDir[0] . $folder . $tpl.'.tpl') ) {
			if( $this->subfolder == 'site/' ) {
				$folder = '';
			} else {
				$folder = 'PageNotFound/';
			}
		}
		$this->smarty->display( $folder . $tpl.'.tpl' );
	}

  function __destruct(){
      $mode       = false;
      $subfolder  = '';

      if($this->getThemeFolder() != ''){
          $mode       = true;
          $subfolder  = $this->getThemeFolder().'/';
      }

      if(!$this->hidePatern){
          # Render FOOTER
					$this->displayView( $subfolder.$this->theme_wire.'footer' );
      }

      $this->db = null;
      $this->smarty = null;
  }
}

?>
