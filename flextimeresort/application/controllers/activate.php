<?
class activate extends Controller {
	private $temp = '';
	function __construct(){
		parent::__construct();

		$this->setTitle($this->lang('ACTIVATE_SIKERES_AKTIVALAS'));

		// SEO Információk
		$SEO = null;
		// Site info
		$SEO .= $this->addMeta('description',$this->lang('ACTIVATE_SIKERES_AKTIVALAS'));
		$SEO .= $this->addMeta('keywords','');
		$SEO .= $this->addMeta('revisit-after','3 days');

		// FB info
		$SEO .= $this->addOG('type','website');
		$SEO .= $this->addOG('url','');
		$SEO .= $this->addOG('image','');
		$SEO .= $this->addOG('site_name', $this->title );

		$this->out( 'SEOSERVICE', $SEO );
	}

	// Regisztráció aktiválása
	function reg() {
		$this->temp = '/'.__FUNCTION__;

		$this->out('hide_home_top', true);
		$this->out('bodyclass', 'activationpage');

		$key = base64_decode($_GET['key']);
		$key = explode('=',$key);

		try{
			$this->USERS->activate( $key );
		}catch(\Exception $e){
			$this->out( 'msg', $e->getMessage() );
			$this->out( 'err', true );
		}
	}

	function __destruct(){
		// RENDER OUTPUT
		parent::bodyHead();					# HEADER
		$this->displayView( __CLASS__.$this->temp.'/index', true );		# CONTENT
		parent::__destruct();				# FOOTER
	}
}
?>
