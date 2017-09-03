<? 
use PortalManager\Ad;

class action extends Controller {
	function __construct(){	
		parent::__construct();
		// SEO Információk
		$SEO = null;
	}

	public function ad()
	{
		$this->hidePatern = true;

		$user 			= $this->getVar('user');
		$employer_group = $this->settings['USERS_GROUP_EMPLOYER'];

		if( !isset($user['data']['ID']) ) {
			return false;	
		}


		// Objects
        $lang = array_merge (
            $this->lang->loadLangText( 'employer/jobs/new', true ),
            $this->lang->loadLangText( 'mails', true )
        );

		$actionkeys = json_decode( base64_decode($this->gets[3]), true );

		switch( $this->gets[2] ) {
			case 'turnoff':
				if( $user['data']['user_group'] != $employer_group ) {
					\Helper::reload( $_SERVER['HTTP_REFERER'] );
				} else {
					if( $user['data']['ID'] != $actionkeys['employer'] ) {
						\Helper::reload( $_SERVER['HTTP_REFERER'] );
					} else {
						// Rendben van, mehet a módosítás
						$ad = new Ad( $actionkeys['id'], array( 'db' => $this->db, 'settings' => $this->settings ) );
						$ad->edit( array( 'active' => 0 ) );
						\Helper::reload( $_SERVER['HTTP_REFERER'] );
					}					
				}

			break;
			case 'turnon':
				if( $user['data']['user_group'] != $employer_group ) {
					\Helper::reload( $_SERVER['HTTP_REFERER'] );
				} else {
					if( $user['data']['ID'] != $actionkeys['employer'] ) {
						\Helper::reload( $_SERVER['HTTP_REFERER'] );
					} else {
						// Rendben van, mehet a módosítás
						$ad = new Ad( $actionkeys['id'], array( 'db' => $this->db, 'settings' => $this->settings ) );
						$ad->edit( array( 'active' => 1 ) );
						\Helper::reload( $_SERVER['HTTP_REFERER'] );
					}					
				}

			break;
		}
	}

	function __destruct(){
		// RENDER OUTPUT
		parent::bodyHead();					# HEADER
		$this->displayView( __CLASS__.'/index', true );		# CONTENT
		parent::__destruct();				# FOOTER
	}
}
?>