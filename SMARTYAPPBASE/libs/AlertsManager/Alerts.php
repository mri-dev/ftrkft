<?
namespace AlertsManager;

use MailManager\Mails;

class Alerts
{
	const DB_TABLE		= 'alerts';

	public $db = null;
	public $controller = null;
	public $smarty = null;
	private $admin = false;
	public $settings = array();

	private $offline = true;

	public function __construct( $arg = array() )
	{
		if ( isset($arg['controller']) ) {
			$this->controller = $arg['controller'];
			$this->db = $arg['controller']->db;
			$this->settings = $arg['controller']->settings;
			$this->smarty = $arg['controller']->smarty;
		}
	}

	public function __destruct()
	{
		$this->db 		= null;
		$this->arg 		= null;
		$this->smarty 	= null;
		$this->settings = null;
	}
}

?>
