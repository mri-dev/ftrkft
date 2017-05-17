<?
namespace PortalManager;

use MailManager\Mailer;
use FileManager\FileLister;

/**
* class Portal
*/
class Portal
{
	private $db = null;
	
	function __construct( $arg = array() )
	{
		$this->db = $arg['db'];
		$this->settings = $arg[view]->settings;
	}

	public function __destruct()
	{
		$this->db = null;
	}
}
?>