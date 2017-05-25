<?
use PortalManager\Form;

class resetpassword extends Controller  {
		function __construct(){
			parent::__construct();
			parent::$pageTitle = $this->lang('UJ_JELSZO_GENERALAS');

			$form = new Form( $_GET['response'] );
			$this->out( 'form', $form );
			$this->out('hide_home_top', true);
			$this->out('bodyclass', 'resetpassword');

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->addMeta('description',$this->lang('UJ_JELSZO_GENERALAS_SEO_DESC'));
			$SEO .= $this->addMeta('keywords',$this->lang('UJ_JELSZO_GENERALAS_SEO_KEYWORDS'));
			$SEO .= $this->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->addOG('type','website');
			$SEO .= $this->addOG('url',$this->settings['page_url'].$_SERVER['REQUEST_URI']);
			$SEO .= $this->addOG('image','');
			$SEO .= $this->addOG('site_name',parent::$pageTitle);

			$this->out( 'SEOSERVICE', $SEO );
		}

		function __destruct(){
			// RENDER OUTPUT
			parent::bodyHead();					# HEADER
			$this->displayView( __CLASS__.'/index', true );		# CONTENT
			parent::__destruct();				# FOOTER
		}
	}

?>
