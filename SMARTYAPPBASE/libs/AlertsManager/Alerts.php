<?
namespace AlertsManager;

use FlexTimeResort\Allasok;

class Alerts
{
	const DB_TABLE = 'alerts';
	const DB_GROUP = 'alerts_groups';
	const DAYS_BEFORE_ARCHIVE = 30;

	public $db = null;
	public $controller = null;
	public $smarty = null;
	private $admin = false;
	public $settings = array();

	public $current_page = 1;
  public $total_items = 0;
  public $total_pages = 1;
  public $tree = false;
	private $current_category = false;
	private $tree_steped_item = false;
	private $tree_items = 0;
	private $walk_step = 0;

	public function __construct( $arg = array() )
	{
		if ( isset($arg['controller']) ) {
			$this->controller = $arg['controller'];
			$this->db = $arg['controller']->db;
			$this->settings = $arg['controller']->settings;
			$this->smarty = $arg['controller']->smarty;
		}
	}

	public function add( $uid = false, $groupkey, $itemid = false, $vars = array() )
	{
		if (!$uid) {
			return false;
		}

		$groups = $this->getGroups();

		if ( array_key_exists($groupkey, $groups) ) {
			$this->db->insert(
				self::DB_TABLE,
				array(
					'groupkey' => $groupkey,
					'user_id' => $uid,
					'itemid' => (int)$itemid,
					'vars' => (empty($vars)) ? NULL : json_encode($vars)
				)
			);

			return (int)$this->db->lastInsertId();
		} else return false;
	}

	public function Count()
	{
		return $this->tree_items;
	}

  public function walk()
	{
		if( !$this->tree_steped_item ) return false;

		$this->current_category = $this->tree_steped_item[$this->walk_step];

		$this->walk_step++;

		if ( $this->walk_step > $this->tree_items ) {
			// Reset Walk
			$this->walk_step = 0;
			$this->current_category = false;

			return false;
		}

		return true;
	}

	public function setWatchedAllUnwatched( $uid )
	{
		if (!$uid || $uid == 0) {
			return false;
		}

		$this->db->update(
			self::DB_TABLE,
			array(
				'watched' => 1,
				'watched_at' => NOW,
			),
			sprintf("user_id = %d and watched = 0", $uid)
		);
	}

	protected function getGroups()
	{
		$groups = array();

		$data = $this->db->query("SELECT * FROM ".self::DB_GROUP)->fetchAll(\PDO::FETCH_ASSOC);

		foreach ((array)$data as $d) {
			$groups[$d['groupkey']] = $d;
		}

		return $groups;
	}

	public function getTree( $arg = array() )
	{
		$tree = array();
    $filters = $arg['filters'];

		$qry = "
			SELECT SQL_CALC_FOUND_ROWS
        a.*,
				g.faico as fa_ico
			FROM ".self::DB_TABLE." as a
			LEFT OUTER JOIN ".self::DB_GROUP." as g ON g.groupkey = a.groupkey
			WHERE 1=1";

		$qry .= " and (a.watched = 0 || (a.watched = 1 && DATEDIFF(now(), a.alertdate) < ".self::DAYS_BEFORE_ARCHIVE.")) ";

		if (isset($arg['watched'])) {
      $qry .= " and a.watched = {$arg['watched']}";
    }

		if (isset($arg['userid'])) {
      $qry .= " and a.user_id = {$arg['userid']}";
    }

    // Filterek
    if (isset($filters) && !empty($filters)) {

    }

		if( !$this->o['orderby'] ) {
      if ($idset_orderby) {
        $qry .= " ORDER BY FIELD(a.ID, ".implode(",", (array)$idset_orderby).")";
      } else {
        $qry .= " ORDER BY a.watched ASC, a.alertdate DESC";
      }

		} else {
			$qry .= " ORDER BY a.".$this->o['orderby']." ".$this->o['order'];
		}

    // Limit
		$limit = $this->getLimit($arg);
		$qry .= " LIMIT ".$limit[0].", ".$limit[1];

		$top_cat_qry 	= $this->db->query($qry);
		$top_cat_data 	= $top_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

    $this->total_items 	= $this->db->query("SELECT FOUND_ROWS();")->fetchColumn();
		$this->total_pages 	= ceil( $this->total_items / $limit[1] );

		if( $top_cat_qry->rowCount() == 0 ) return $this;

		foreach ( $top_cat_data as $top_cat ) {
			$this->tree_items++;
			$this->tree_steped_item[] = $top_cat;
			$tree[] = $top_cat;
		}

		$this->tree = $tree;

		return $this;
	}

	private function getLimit( $arg = array() )
	{
		$limit = array( 0, 100 );

		if( isset($arg['limit']) ) {
			$limit[1] = $arg['limit'];
		}

		$page = $arg['page'];

		if( isset($page) && $page > 0 ) {

		} else {
			$page = 1;
		}

		$limit[0] = $limit[1] * $page - $limit[1];

		$this->limit[0] = $limit[0] + 1;
		$this->limit[1] = $limit[0] + $limit[1];
		$this->current_page = $page;

		return $limit;
	}

	public function getUnwatchedNum( $uid )
	{
		if (!$uid || $uid == 0) {
			return false;
		}
		return $this->db->query("SELECT count(ID) FROM ".self::DB_TABLE." WHERE user_id = {$uid} and watched = 0")->fetchColumn();
	}

	public function get( $key = false )
	{
    if ($key) {
      return $this->current_category[$key];
    } else {
      $this->prepareOutput();
      return $this->current_category;
    }
	}

	public function getIcon()
	{
		return $this->current_category['fa_ico'];
	}

	public function getMessage()
	{
		$msg = $this->controller->lang('ALERTMANAGER_MSG_'.$this->current_category['groupkey']);

		return $msg;
	}

	public function getAlertDate()
	{
		return $this->current_category['alertdate'];
	}

	public function isWatched()
	{
		return ($this->current_category['watched'] == '1') ? true :false;
	}

	public function getNavButton()
	{
		$button = false;
		$group = $this->current_category['groupkey'];
		$itemid = (int)$this->current_category['itemid'];

		switch ($group) {
			// Hirdetés gomb
			case 'allas_jelentkezes_sikeres':
			case 'allas_jelentkezes_hozzaferes_engedelyezes':
				if($itemid != 0){
					$allas = (new Allasok(array('controller' => $this->controller)))->load($itemid);
					$button = array(
						'text' => $this->controller->lang('Hirdetés adatlap'),
						'url' => $allas->getURL(),
						'msg' => $allas->shortDesc()
					);
				}
			break;
		}

		return $button;
	}

	public function __destruct()
	{
		$this->db 		= null;
		$this->arg 		= null;
		$this->smarty 	= null;
		$this->settings = null;

		$this->tree = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->walk_step = 0;
	}
}

?>
