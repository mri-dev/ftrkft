<?
use PortalManager\Articles;
use FlexTimeResort\Allasok;

class home extends Controller  {
	private $user = false;
	function __construct(){
		parent::__construct();

		$this->out('homepage', true);

		$articles = new Articles();
		$arg = array();
		$arg['limit'] = 3;
		$articles->getTree( $arg );
		$this->out('articles_top', $articles);

		$articles = new Articles();
		$arg = array();
		$arg['limit'] = 4;
		$arg['offset'] = 3;
		$articles->getTree( $arg );
		$this->out('articles_more', $articles);

		$allasok = new Allasok(array(
			'controller' => $this
		));

		$arg = array();
		$arg['limit'] = 10;
		$arg['hide_inaktiv'] = true;
		$allasok->getTree($arg);
		$this->out('allasok', $allasok);

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
