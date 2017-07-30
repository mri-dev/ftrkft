<?
use PortalManager\User;
use FlexTimeResort\UserCVPreparer;

class u extends Controller
{
	private $user = false;
	private $temp = '';
  public $ctrl = false;

	function __construct()
  {
    $this->ctrl = parent::__construct( array(
			'root' => 'onlinecv'
		) );

  	$this->root = '/'.__CLASS__.'/';
		$this->out( 'root', $this->root );
		$this->out( 'admin_css', '/'.str_replace('templates/','',$this->smarty->getTemplateDir(0)).'assets/css/media.css');

    ///////////////////

    $uid = (int)$_GET['uid'];
    $this->user = new User($uid, array(
      'controller' => $this->ctrl
    ));
    $this->out( 'u', $this->user );

    // CV
    $output_vars = array();
    $cv = new UserCVPreparer($this->user, array(
      'controller' => $this->ctrl
    ));

    // Személyes
    $output_vars['nev'] = $cv->Name();
    $output_vars['szuletett'] = $cv->BirthDate();
    $output_vars['cim'] = $cv->Address();

    // Kapcsolat
    $output_vars['email'] = $cv->Email();
    $output_vars['telefon'] = $cv->Phone();

    // Social
    $output_vars['social_facebook'] = $cv->Social('facebook');
    $output_vars['social_twitter'] = $cv->Social('twitter');
    $output_vars['social_linkedin'] = $cv->Social('linkedin');

		// Ismeretek
		$output_vars['ismeretek_egyeb'] = $cv->IsmeretekEgyeb();

    //////////////
    // Output vars
    $this->out('cv', $cv);
    foreach ((array)$output_vars as $key => $value) {
      if(!$value || empty($value)) continue;
      $this->out('cv_'.$key, $value);
    }

		// SEO Információk
		$SEO = null;
		// Site info
		$SEO .= $this->addMeta('description', '');
		$SEO .= $this->addMeta('keywords', '');
		$SEO .= $this->addMeta('revisit-after','3 days');

		// FB info
		$SEO .= $this->addOG('type','website');
		$SEO .= $this->addOG('url', $this->settings['page_url']);
		$SEO .= $this->addOG('image','');
		$SEO .= $this->addOG('site_name',$this->title);

		$this->out( 'SEOSERVICE', $SEO );
	}

	function __destruct(){
    // RENDER OUTPUT
    parent::bodyHead();					# HEADER
    $this->displayView('index', true );		# CONTENT
    parent::__destruct();				# FOOTER
	}
}

?>
