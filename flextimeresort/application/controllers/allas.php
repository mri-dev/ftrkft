<?
use FlexTimeResort\Allasok;
use PortalManager\Admins;

class allas extends Controller{
	function __construct(){
		parent::__construct();

		$id = (int)end(explode("_", end($this->gets)));
    $this->out('hide_home_instruction', true);
    $this->out('hide_login_instruction', true);

		$allasok = new Allasok(array(
			'controller' => $this
		));
    $this->out( 'allas', $allasok->load($id));

		parent::$pageTitle = $allasok->get('tipus_name').', '.$allasok->get('cat_name').' '.$allasok->getCity().' '.$this->lang('területén');

		if ( $this->ME->logged() && $this->ME->isMunkaado() && $this->ME->getID() != $allasok->getAuthorData('ID') ) {
			Helper::reload($this->settings['page_url'].$this->settings['munkavallalo_search_slug']);
		}

		$allasok->logVisit($this->ME->getID());
		$request = $allasok->checkRequestAd($this->ME->getID(), $id);
		$request_data = $allasok->getRequest($request);
		$access_granted = ($request_data['accepted'] == 1) ? true : false;

		// Admin view
		if (isset($_GET['showfull']))
		{
			$adminToken = $_GET['atoken'];

			$admins = new Admins(array(
				'db' => $this->db,
				'view' => array(
					'settings' => $this->settings
				)
			));

			$adminTokenUser = $admins->getAdminByCookieToken($adminToken);

			if ($adminTokenUser) {
				$access_granted = true;
				$adminAccess = true;
			}
		}

		$this->out( 'requested_ad', $request );
		$this->out( 'requested_data', $request_data );
		$this->out( 'access_granted', $access_granted );
		$this->out( 'admin_access', $adminAccess );

		$author_obj = $allasok->getAuthorData('author');

		$this->out('bodyclass', 'allas-page-view');
		// SEO Információk
		$SEO = null;
		// Site info
		$SEO .= $this->addMeta('description', $this->lang('Állásajánlat').': '.$allasok->ShortDesc());
		$SEO .= $this->addMeta('keywords', $allasok->getKeywords(false));
		$SEO .= $this->addMeta('revisit-after','3 days');

		// FB info
		$SEO .= $this->addOG('title',parent::$pageTitle);
		$SEO .= $this->addOG('description',$this->lang('Állásajánlat').': '.$allasok->ShortDesc());
		$SEO .= $this->addOG('type','article');
		$SEO .= $this->addOG('url', $this->settings['page_url'].$allasok->getUrl());
		$SEO .= $this->addOG('image', $this->settings['page_url'].$author_obj->getProfilImg());
		$SEO .= $this->addOG('site_name', $this->settings['page_title']);

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
