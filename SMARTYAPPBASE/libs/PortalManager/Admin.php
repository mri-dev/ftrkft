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
		$name 	= ($data['admin_name']) ?: false;
    $user 	= ($data['admin_user']) ?: false;
		$pw1 	= ($data['admin_pw']) ?: false;
		$status = (isset($data['admin_status'])) ? 1 : 0;
		$jog 	= $data['admin_jog'];

    if (!$name) {
			throw new \Exception("Kérjük, hogy adja meg az adminisztrátor <strong>Nevét</strong>!");
		}

		if (!$user) {
			throw new \Exception("Kérjük, hogy adja meg az adminisztrátor <strong>E-mail / Login</strong> azonosítóját!");
		}

		if ( !$pw1) {
			throw new \Exception("Kérjük, hogy adja meg az adminisztrátor <strong>jelszavát</strong>!");
		}

		$this->db->insert(
			"admin",
			array(
        'name' => trim($name),
				'user' => trim($user),
				'pw' => \Hash::jelszo($pw1),
				'engedelyezve' => $status,
				'jog' => $jog,
			)
		);

    return (int)$this->db->lastInsertId();
	}

	public function save( $id, $new_data )
	{
		$name 	= ($new_data['admin_name']) ?: false;
    $user 	= ($new_data['admin_user']) ?: false;
		$status = (isset($new_data['admin_status'])) ? 1 : 0;
		$jog 	= $new_data['admin_jog'];
		$password = false;

    if (!$name) {
			throw new \Exception("Kérjük, hogy adja meg az adminisztrátor <strong>Nevét</strong>!");
		}

		if (!$user) {
			throw new \Exception("Kérjük, hogy adja meg az adminisztrátor <strong>E-mail / Login</strong> azonosítóját!");
		}

		if ($new_data['admin_pw'] != '') {
			$password = ", pw = '".\Hash::jelszo($new_data['admin_pw'])."'";
		}

		$this->db->query(sprintf("UPDATE admin SET name = '%s', user = '%s', jog = %d, engedelyezve = %d $password WHERE ID = %d", $name, $user, $jog, $status, $id ));
	}

	public function delete($id)
	{
		$this->db->query(sprintf("DELETE FROM admin WHERE ID = %d",$id));
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

  public function getName()
	{
		return $this->admin['name'];
	}

  public function getEmail()
	{
		return $this->admin['user'];
	}

	public function getId()
	{
		return $this->admin['ID'];
	}

	public function getToken()
	{
		return $this->admin['valid_cookie_token'];
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
