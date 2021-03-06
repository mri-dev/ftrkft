<?
use PortalManager\Form;
use PortalManager\Categories;
use DesignCreator\FormDesigns;
use FlexTimeResort\Allasok;

class user extends Controller{
		private $temp = '';
		function __construct(){
			parent::__construct();
			parent::$pageTitle = $this->settings['page_slogan'];

			$form = new Form( $_GET['response'] );
			$this->out( 'form', $form );

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

		function regisztracio() {
			$this->temp = '/'.__FUNCTION__;

			if ($this->ME->logged()) {
				\Helper::reload('/ugyfelkapu'); exit;
			}

			$as = (isset($_GET['as']) && !empty($_GET['as'])) ? $_GET['as'] : 'munkavallalo';
			$usergroup = -1;
			switch ($as) {
				default: case 'munkaado':
					$usergroup = 10;
				break;
				case 'munkavallalo':
					$usergroup = 0;
				break;
			}

			$this->out('hide_home_top', true);
			$this->out('as', rtrim($as,"/"));
			$this->out('usergroup', $usergroup);
		}

		function belepes()
		{
			$this->temp = '/'.__FUNCTION__;

			if ($this->ME->logged()) {
				\Helper::reload('/ugyfelkapu'); exit;
			}

			$this->out('hide_home_top', true);
		}

		function ugyfelkapu() {
			$this->temp = '/'.__FUNCTION__;

			if (!$this->ME->logged()) {
				\Helper::reload('/belepes?re='.$_SERVER['REQUEST_URI']); exit;
			}

			$subtitle = '';
			$p = rtrim($_GET['p'], "/");


			switch ($p) {
				default:
					$subtitle = $this->lang('Értesítő központ');

					$arg = array();
					$arg['userid'] = $this->ME->getID();

					$alerts = $this->ALERTS->getTree($arg);
					$this->out('alerts', $alerts);

					$this->ALERTS->setWatchedAllUnwatched((int)$this->ME->getID());
				break;
				case 'apps':
					$subtitle = $this->lang('Jelentkezéseim');

					if ($this->ME->isMunkaado()) {
						\Helper::reload('/ugyfelkapu');
					}

					// Jelentkezett hirdetések
					$alalsok = new Allasok(array(
						'controller' => $this
					));
					$alalsok->getTree(array(
						'show_requests' => (int)$this->ME->getID()
					));
					$this->out( 'allasok', $alalsok );

				break;
				case 'beallitasok':
					$subtitle = $this->lang('BEALLITASOK');
				break;
				case 'profil':
					$subtitle = $this->lang('PROFIL_SZERKESZTES');
					$formdesign = new FormDesigns();

					$subprofil = 'alap';

					if($_GET['sub'] != '') {
						$subprofil = $_GET['sub'];
					}

					$this->out('formdesigns', $formdesign);
					$this->out('show_profil_flow', true);
					$this->out('subprofil', $subprofil);
				break;
				case 'uzenetek':
					$subtitle = $this->lang('UZENETEK');

					switch ($_GET['sub']) {
						case 'inbox':
							$subtitle .= ' / '.$this->lang('BEJOVO');
						break;
						case 'outbox':
							$subtitle .= ' / '.$this->lang('Elküldött');
						break;
						case 'archiv':
							$subtitle .= ' / '.$this->lang('Archivált');
						break;
						case 'msg':
							$subtitle .= ' / '.$this->lang('Üzenet olvasása');
							$this->out('msgsession', rtrim($_GET['msgid'], '/'));
						break;
					}
				break;
				case 'hirdetesek':
					$subtitle = $this->lang('Hirdetések');
					if (!$this->ME->isMunkaado()) {
						\Helper::reload('/ugyfelkapu');
					}
				break;
				case 'uj-hirdetesek':
					if (!empty($_GET['modid'])) {
						$subtitle = $this->lang('Hirdetés szerkesztése');
					} else {
						$subtitle = $this->lang('Új hirdetés létrehozása');
					}

					$formdesign = new FormDesigns();
					$this->out('formdesigns', $formdesign);

					if (!$this->ME->isMunkaado()) {
						\Helper::reload('/ugyfelkapu');
					}
				break;
			}
			$this->out('subpage', $p);
			$this->out('hide_home_top', true);
			$this->out('hide_searcher', true);
			$this->out('show_ugyfelkapu_top', true);
			$this->out('subtitle', ($subtitle != '') ? ' / <strong>'.$subtitle.'</strong>' : '');
			$this->out('bodyclass', 'ugyfelkapu-view');
		}

		function settings() {
			//$this->temp = '/'.__FUNCTION__;
		}

		function modulview()
		{
			$this->defined_stl_temp = 'user/ugyfelkapu/profil/modulview/'.$_GET['group'].'/'.$_GET['item'];

			if ( !$this->smarty->templateExists($this->defined_stl_temp.'.tpl') ) {
				$this->defined_stl_temp = 'user/ugyfelkapu/profil/modulview/notfound';
			}
		}

		function logout()
		{
			$this->User->logout();
      \Helper::reload( '/'.__CLASS__ );
		}

		function __destruct(){
			// RENDER OUTPUT
			if (!isset($this->defined_stl_temp)) {
				parent::bodyHead();					# HEADER
				$this->displayView( __CLASS__.$this->temp.'/index', true );		# CONTENT
				parent::__destruct();				# FOOTER
			} else{
				$this->displayView( $this->defined_stl_temp, true );		# CONTENT
			}

		}
	}

?>
