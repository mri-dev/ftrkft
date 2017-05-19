<?
use PortalManager\Pages;

class p extends Controller  {
	private $user = false;
	function __construct()
  {
		parent::__construct();
    $this->out('hide_home_instruction', true);
    $this->out('hide_login_instruction', true);

    $pages = (new Pages())->get($this->gets[1]);
    $this->out('page', $pages);

    if (!$pages->getId()) {
      header("HTTP/1.0 404 Not Found");
      parent::$pageTitle = $this->lang('AZ_OLDAL_NEM_LETEZIK');
      $this->setTitle($this->lang('AZ_OLDAL_NEM_LETEZIK'));
    } else {
      parent::$pageTitle = $pages->getTitle();
      $this->setTitle($pages->getTitle());
    }


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
