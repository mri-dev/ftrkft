<?
use FlexTimeResort\Allasok;

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

		$this->out( 'requested_ad', $request );
		$this->out( 'requested_data', $request_data );
		$this->out( 'access_granted', $access_granted );

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
