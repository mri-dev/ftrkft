<?
use PortalManager\Form;
use PortalManager\Categories;
use PortalManager\Ad;

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
			$this->out('as', $as);
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
		}

		function applicant_for_job() {
			$user = $this->getVar('user');

			$this->out( 'applicants', $this->User->getApplicants( $user['data']['ID'] ) );

			// Jelentkezéshez a hirdetmény
			if( $_GET['a'] == 'applicant' && isset($_GET['job']) ) {
				$this->out( 'app', new Ad( $_GET['job'], array( 'admin' => true, 'db' => $this->db, 'settings' => $this->settings ) ) );
			}
		}

		function settings() {
			//$this->temp = '/'.__FUNCTION__;
		}

		function logout()
		{
			$this->User->logout();
            \Helper::reload( '/'.__CLASS__ );
		}

		function __destruct(){
			// RENDER OUTPUT
			parent::bodyHead();					# HEADER
			$this->displayView( __CLASS__.$this->temp.'/index', true );		# CONTENT
			parent::__destruct();				# FOOTER
		}
	}

?>
