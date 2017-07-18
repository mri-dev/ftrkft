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

    $this->out( 'allas', $allasok->load($id) );

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
