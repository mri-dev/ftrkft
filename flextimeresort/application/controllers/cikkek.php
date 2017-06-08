<?
use PortalManager\Articles;
use PortalManager\Pagination;

class cikkek extends Controller {
	private $user = false;
	function __construct()
  {
		parent::__construct();
    parent::$pageTitle = $this->lang('Cikkek, hírek');
    $this->out('hide_home_instruction', true);
    $this->out('hide_login_instruction', true);
    $this->title = 'SD';

    $articles = new Articles();
    $articles->getTree(array(
      'search' => explode(" ", $_GET['search']),
      'limit' => 20,
      'page' => $this->gets[1]
    ));

    $this->out('articles', $articles);
    $get = $_GET;
    unset($get['tag']);
    $this->out('pagination', (new Pagination(array(
      'max' => $articles->raw['info']['pages']['max'],
      'current' => $articles->raw['info']['pages']['current'],
      'root' => rtrim($this->settings['articles_list'],"/"),
      'after' => '?'.http_build_query($get),
      'lang' => $this->LANGUAGES->texts
    )))->render());

		// SEO Információk
		$SEO = null;
		// Site info
		$SEO .= $this->addMeta('description', $this->lang('ARTICLES_SEO_DESC'));
		$SEO .= $this->addMeta('keywords', $this->lang('cikkek, hírek, tanácsok, információk, bejegyzések'));
		$SEO .= $this->addMeta('revisit-after','3 days');

		// FB info
		$SEO .= $this->addOG('type','articles');
		$SEO .= $this->addOG('url', $this->settings['page_url'].$this->settings['articles_list']);
		$SEO .= $this->addOG('image',$this->settings['page_url'].$this->settings['page_logo']);
		$SEO .= $this->addOG('site_name', parent::$pageTitle);

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
