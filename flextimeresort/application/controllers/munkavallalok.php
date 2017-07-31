<?
use PortalManager\Users;
use PortalManager\Pagination;

class munkavallalok extends Controller  {
	private $user = false;
  private $ctrl = null;
	function __construct(){
		$this->ctrl = parent::__construct();

    $this->out('hidehometop', true);

    $users = new Users(array(
        "controller" => $this->ctrl,
        'returnType' => 'object',
        'includeCVHandler' => true
    ));

    $usergroup = $this->settings['USERS_GROUP_USER'];
    $filters = array();

    $arg['filters'] = $filters;
    $arg['limit'] = 20;
    $arg['page'] = ($_GET[page] != '') ? (int)$_GET['page'] : 1;

    $list = $users->getUserList( $arg, $usergroup );
    $this->out( 'pagination', (new Pagination(array(
      'max' => $list[info][pages][max],
      'current' => $list[info][pages][current],
      'root' => substr($this->settings['munkavallalo_search_slug'], 0, -1),
      'after' => '?'.http_build_query($filters),
      'lang' => $this->LANGUAGES->texts
    )))->render());
    $this->out( 'lista', $list );

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
