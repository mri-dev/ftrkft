<?
namespace PortalManager;

use PortalManager\Ad;
use PortalManager\Users;

class Applicants
{
	public $total_items 	= 0;
	public $filters 		= array();
	public $unwatched 		= 0; 

	private $ad_id = false;

	private $db = null;
	public $lang = array();
	public $settings = null;
	public $arg = null;
	private $items = false;
	private $current_item = false;
	private $item_steped_item = false;	
	private $walk_step = 0;

	function __construct( $ad_id, $arg = array() )
	{
		$this->ad_id 		= $ad_id;

		$this->db 			= $arg[db];
		$this->arg 			= $arg;
		$this->settings 	= $arg[settings];
		$this->lang 		= $arg[lang];
		$this->filters 		= $arg[filters];

		return $this;
	}

	public function getList( $arg = array() )
	{
		$this->items = false;
		$items = array();

		$q = "
		SELECT 				a.id, a.felh_id, a.megtekintve
		FROM 				".\PortalManager\Users::TABLE_APPLICANT." as a
		WHERE 				1 = 1 and a.hird_id = ".$this->ad_id;

		// Order
		$q .= " ORDER BY a.megtekintve ASC, a.jelentkezes DESC ";

		
		//echo $q;

		$qry 		= $this->db->query($q);
		$qry_data 	= $qry->fetchAll(\PDO::FETCH_ASSOC); 

		if( $qry->rowCount() == 0 ) return $this; 
		
		foreach ( $qry_data as $d ) {
			$this->total_items++;

			if( $d['megtekintve'] == '0' ) {
				$this->unwatched++;
			}

			$d['user'] = new User( $d['felh_id'], $this->arg );
			$item = $d;

			$items[] = $item;
		}

		$this->items = $items;

		return $this;
	}

	public function walk()
	{			
		$this->current_item = $this->items[$this->walk_step];		

		$this->walk_step++;

		if ( $this->walk_step > $this->total_items ) {
			// Reset Walk
			$this->walk_step = 0;
			$this->current_item = false;

			return false;
		}

		return true;	
	}

	public function get()
	{
		return $this->current_item;
	}

	public function __destruct()
	{
		$this->db = null;
		$this->arg = null;
		$this->items = false;
		$this->current_item = false;
		$this->item_steped_item = false;
		$this->total_items = 0;
		$this->walk_step = 0;
	}
}
?>