<?
use PortalManager\Users;
use PortalManager\Pagination;
use DesignCreator\FormDesigns;
use FlexTimeResort\Allasok;

class munkavallalok extends Controller  {
	private $user = false;
  private $ctrl = null;
	function __construct(){
		$this->ctrl = parent::__construct();

    $this->out('hide_login_instruction', true);
		$this->out('hide_home_instruction', true);

		if (isset($_POST['requestUserforAd'])) {
			if(isset($_POST['ad']) && !empty($_POST['ad'])){
				$adForRequest = (new Allasok(array(
					'controller' => $this->ctrl
				)))->load($_POST['ad']);
				$ad_author = $adForRequest->getAuthorData('ID');

				if ($ad_author == $this->ME->getID()) {
					try {
						$adForRequest->registerUserRequestToAd($this->ME->getID(), $_POST['ad'], $_POST);
						\Helper::reload('/ugyfelkapu/hirdetesek/?hlad='.$_POST['ad'].'&showUserRequests=1&successUserRequest=1');
					} catch (\Exception $e) {
						$this->out('requestuserad_err', $e->getMessage());
					}
				}
			} else{
				$this->out('requestuserad_err', $this->ctrl->lang('MUNKAVALLALOI_ADAT_LEKERES_FORM_UNSELECTED_AD'));
			}
		}

		$formdesign = new FormDesigns();
    $users = new Users(array(
        "controller" => $this->ctrl,
        'returnType' => 'object',
        'includeCVHandler' => true
    ));
    $usergroup = $this->settings['USERS_GROUP_USER'];
    $filters = array();

		if (isset($_GET['nem']) && !empty($_GET['nem'])) {
			$filters['details']['nem'] = explode(",",$_GET['nem']);
		}

		if (isset($_GET['mt']) && !empty($_GET['mt'])) {
			$filters['details']['munkatapasztalat'] = explode(",",$_GET['mt']);
		}

		if (isset($_GET['lv']) && !empty($_GET['lv'])) {
			$filters['details']['iskolai_vegzettsegi_szintek'] = explode(",",$_GET['lv']);
		}

		if (isset($_GET['mk']) && !empty($_GET['mk'])) {
			$filters['details']['elvaras_munkateruletek'] = explode(",",$_GET['mk']);
		}

		if (isset($_GET['megye']) && !empty($_GET['megye'])) {
			$filters['details']['megyeaholdolgozok'] = explode(",",$_GET['megye']);
		}

    $arg['filters'] = $filters;
    $arg['limit'] = 30;
    $arg['page'] = ($_GET[page] != '') ? (int)$_GET['page'] : 1;

    $list = $users->getUserList( $arg, $usergroup );
		$useridssession = false;

		if (!empty($list['info']['query']['result_ids'])) {
			$usession_arr = array();
			$usession_arr['target_users'] = (array)$list['info']['query']['result_ids'];
			$usession_arr['requester_id'] = $this->ME->getID();
			$usession_arr['timestemp'] = microtime();

			$useridssession = base64_encode(json_encode($usession_arr, JSON_UNESCAPED_UNICODE));
			$this->out('useridssession', $useridssession);
		}

		$sget = $_GET;
		unset($sget['tag']);
		unset($sget['page']);

    $this->out( 'pagination', (new Pagination(array(
      'max' => $list[info][pages][max],
      'current' => $list[info][pages][current],
      'root' => substr($this->settings['munkavallalo_search_slug'], 0, -1),
      'after' => '?'.http_build_query($sget),
      'lang' => $this->LANGUAGES->texts
    )))->render());
    $this->out( 'lista', $list );
		$this->out('formdesigns', $formdesign);

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
