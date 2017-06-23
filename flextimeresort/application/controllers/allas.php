<?
use FlexTimeResort\Allasok;

class allas extends Controller{
	function __construct(){
		parent::__construct();

    $this->out('hide_home_instruction', true);
    $this->out('hide_login_instruction', true);

		$allasok = new Allasok(array(
			'controller' => $this
		));

    $this->out( 'allas', $allasok->load(4) );

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

	function __destruct(){
		// RENDER OUTPUT
		parent::bodyHead();	#HEADER
		$this->displayView( __CLASS__.'/index', true ); #CONTENT
		parent::__destruct(); #FOOTER
	}
}

?>
