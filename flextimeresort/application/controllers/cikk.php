<?
use PortalManager\Articles;

class cikk extends Controller  {
	private $user = false;
	function __construct()
  {
		parent::__construct();
    $this->out('hide_home_instruction', true);
    $this->out('hide_login_instruction', true);

    $articles = (new Articles())->get($this->gets[1]);
    $this->out('articles', $articles);

    if (!$articles->getID()) {
      header("HTTP/1.0 404 Not Found");
      parent::$pageTitle = $this->lang('A cikk nem létezik.');
      $this->setTitle($this->lang('A cikk nem létezik.'));
    } else {
      parent::$pageTitle = $articles->getTitle();
      $this->setTitle($articles->getTitle());
    }

    $more_articles = new Articles();
		$arg = array();
		$arg['limit'] = 4;
    $arg['orderby'] = 'rand()';
    $arg['exc_ids'] = array($articles->getID());
		$more_articles->getTree( $arg );
		$this->out('more_articles', $more_articles);

		// SEO Információk
		$SEO = null;
		// Site info
		$SEO .= $this->addMeta('description', $articles->getSEODesc());
		$SEO .= $this->addMeta('keywords', $articles->getKeywords());
		$SEO .= $this->addMeta('revisit-after','3 days');

		// FB info
		$SEO .= $this->addOG('type','article');
		$SEO .= $this->addOG('url', $this->settings['page_url'].'/cikk/'.$articles->getSlug());
		$SEO .= $this->addOG('image',$this->settings['page_url'].$articles->Image());
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
