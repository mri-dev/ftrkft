<?
use FlexTimeResort\Allasok;
use PortalManager\Pagination;

class allasok_lista extends Controller  {
	private $user = false;
	function __construct(){
		parent::__construct();

    $this->out('hide_home_instruction', true);

		if ( $this->ME->logged() && $this->ME->isMunkaado() ) {
			Helper::reload($this->settings['page_url'].$this->settings['munkavallalo_search_slug']);
		}

		$allasok = new Allasok(array(
			'controller' => $this
		));
		$arg = array();
		$arg['limit'] = 20;		
		$arg['lang'] = $this->LANGUAGES->getCurrentLang();
		$arg['hide_inaktiv'] = true;
		$arg['page'] = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
		$arg['filters']['search'] = (!empty($_GET['s'])) ? $_GET['s'] : false;
		$arg['filters']['city'] = (!empty($_GET['c'])) ? $_GET['c'] : false;
		$megye = $_GET['megye'];
		if (strpos($megye, ",") !== false) {
			$megye = explode(",", $megye);
		} else {
			$megye = (int)$megye;
		}
		$arg['filters']['megye'] = (!empty($_GET['megye'])) ? $megye : false;
		$kat = $_GET['k'];
		if (strpos($kat, ",") !== false) {
			$kat = explode(",", $kat);
		} else {
			$kat = (int)$kat;
		}
		$arg['filters']['meta']['hirdetes_kategoria'] = (!empty($_GET['k'])) ? $kat : false;

		$type = $_GET['t'];
		if (strpos($type, ",") !== false) {
			$type = explode(",", $type);
		} else {
			$type = (int)$type;
		}
		$arg['filters']['meta']['hirdetes_tipus'] = (!empty($_GET['t'])) ? $type : false;

		$allasok->getTree($arg);
		$this->out('allasok', $allasok);

		$get = $_GET;
		unset($get['tag']);
		unset($get['page']);
		$this->out('pagination', (new Pagination(array(
			'current' =>$allasok->current_page,
			'max' => $allasok->total_pages,
			'item_limit' => 5,
			'class' => 'pagination circle_rounded',
			'root' => '/allasok',
			'after' => '?'.http_build_query($get)
		)))->render());

		// Látogatott hirdetések
		$latogatottak = new Allasok(array(
			'controller' => $this
		));
		$latogatottak->getTree(array(
			'show_history' => ($this->ME->getID()) ? $this->ME->getID() : true,
			'limit' => 10,
			'hide_inaktiv' => true
		));
		$this->out( 'history', $latogatottak );

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
