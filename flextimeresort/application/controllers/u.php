<?
class u extends Controller  {
	private $user = false;
	function __construct()
  {
		parent::__construct();
    $this->out('hide_home_top', true);

		// SEO Információk
		$SEO = null;
		// Site info
		$SEO .= $this->addMeta('description', '');
		$SEO .= $this->addMeta('keywords', '');
		$SEO .= $this->addMeta('revisit-after','3 days');

		// FB info
		$SEO .= $this->addOG('type','website');
		$SEO .= $this->addOG('url', $this->settings['page_url']);
		$SEO .= $this->addOG('image','');
		$SEO .= $this->addOG('site_name',$this->title);

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
