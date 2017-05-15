<?
namespace PortalManager;

/**
* class Admin
* @package PortalManager
* @version v1.0
*/
class Admin
{
	const SUPER_ADMIN_PRIV_INDEX = 0;

	public $logged = false;

	private $db = null;
	private $admin_id = 0;
	private $admin = false;

	function __construct( $admin_id = false, $arg = array() )
	{
		$this->db = $arg[db];
		$this->settings = $arg[view][settings];

		if ($admin_id) {
			$this->admin_id = $admin_id;
			$this->getAdmin();
		}

		return $this;
	}

	/**
	 * Adminisztrátor létregozása
	 * @param array $data POST, admin adatokkal
	 * @return  void
	 */
	public function add( $data )
	{
		$name 	= ($data['admin_user']) ?: false;
		$pw1 	= ($data['admin_pw1']) ?: false;
		$pw2 	= ($data['admin_pw2']) ?: false;
		$status = $data['admin_status'];
		$jog 	= $data['admin_jog'];

		if (!$name) {
			throw new \Exception("Kérjük, hogy adja meg az adminisztrátor <strong>belépési azonosítóját</strong>!");
		}

		if ( !$pw1 || !$pw2 ) {
			throw new \Exception("Kérjük, hogy adja meg az adminisztrátor <strong>jelszavát</strong>!");
		}

		if ( $pw1 != $pw2 ) {
			throw new \Exception("A megadott jelszó nem egyezik, kérjük, hogy írja be újra!");
		}

		$this->db->insert(
			"admin",
			array(
				'user' => trim($name),
				'pw' => \Hash::jelszo($pw2),
				'engedelyezve' => $status,
				'jog' => $jog,
			)
		);
	}
	
	public function save( $new_data )
	{
		$name 	= ($new_data['admin_user']) ?: false;
		$status = $new_data['admin_status'];
		$jog 	= $new_data['admin_jog'];
		$password = false;

		if (!$name) {
			throw new \Exception("Kérjük, hogy adja meg az adminisztrátor <strong>belépési azonosítóját</strong>!");
		}

		if ($new_data['admin_pw1'] != '' && $new_data['admin_pw2'] != '') {
			if ( $new_data['admin_pw1'] != $new_data['admin_pw2'] ) {
				throw new \Exception("A megadott jelszó nem egyezik, kérjük, hogy írja be újra!");
			}
			$password = ", pw = '".\Hash::jelszo($new_data['admin_pw2'])."'";
		}

		$this->db->query(sprintf("UPDATE admin SET user = '%s', jog = %d, engedelyezve = %d $password WHERE ID = %d", $name, $jog, $status, $this->admin_id ));
	}
	
	public function delete()
	{
		$this->db->query(sprintf("DELETE FROM admin WHERE ID = %d",$this->admin_id));
	}

	private function getAdmin()
	{
		$this->admin = $this->db->query(sprintf("SELECT * FROM admin WHERE ID = %d", $this->admin_id))->fetch(\PDO::FETCH_ASSOC);
		$this->logged = true;
	}

	/*===============================
	=            GETTERS            =
	===============================*/
	
	public function getUsername()
	{
		return $this->admin['user'];
	}

	public function getId()
	{
		return $this->admin['ID'];
	}

	public function getLastLogindate()
	{
		return $this->admin['utoljara_belepett'];
	}

	public function getStatus()
	{
		return ($this->admin['engedelyezve'] == 1 ? true : false);
	}

	public function getPrivIndex()
	{
		return (int)$this->admin['jog'];
	}
	
	/*-----  End of GETTERS  ------*/

	public function __destruct()
	{
		$this->db = null;
	}
		
}
?>