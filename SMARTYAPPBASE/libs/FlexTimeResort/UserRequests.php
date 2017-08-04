<?php
namespace FlexTimeResort;

use PortalManager\User;
use FlexTimeResort\Allasok;

class UserRequests
{
  public $db = null;
  public $controller = null;
  public $smarty = null;
  private $admin = false;
  public $settings = array();
	public $current_page = 1;
  public $total_items = 0;
  public $total_pages = 1;
  public $tree = false;
  public $request_info = array();
  public $infos = array();

  private $current_category = false;
  public $tree_ids = array();
  private $last_tree_id = false;
	private $tree_steped_item = false;
	private $tree_items = 0;
	private $walk_step = 0;
  private $close_loop = false;

  public function __construct( $arg = array() )
  {
    if ( isset($arg['controller']) ) {
      $this->controller = $arg['controller'];
      $this->db = $arg['controller']->db;
      $this->settings = $arg['controller']->settings;
      $this->smarty = $arg['controller']->smarty;
    }
    if(isset($arg['admin']) && $arg['admin'] === true ){
      $this->admin = true;
    }
    return $this;
  }
  public function getTree( $arg = array() )
  {
    $tree = array();
    $filters = $arg['filters'];
    $qry = "
      SELECT SQL_CALC_FOUND_ROWS
        ur.ID,
        ur.ad_id as allas_id,
        ur.access_granted,
        ur.feedback,
        ur.user_id,
        ur.admin_id,
        r.requested_at,
        ur.granted_date_at,
        ap.name as pick_admin_name
      FROM ".\FlexTimeResort\Allasok::DB_USERREQUEST_USERS." as ur
      LEFT OUTER JOIN ".\FlexTimeResort\Allasok::DB_USERREQUEST." as r ON r.ID = ur.request_id
      LEFT OUTER JOIN admin as ap ON ap.ID = ur.admin_id
      LEFT OUTER JOIN ".\PortalManager\Users::TABLE_NAME." as ma ON ma.ID = r.user_id
      LEFT OUTER JOIN ".\PortalManager\Users::TABLE_NAME." as mv ON mv.ID = ur.user_id
      WHERE 1=1 ";

    // Filterek
    if (isset($filters) && !empty($filters)) {
      if (isset($filters['ad_ids'])) {
        $qry .= " and ur.ad_id IN(".implode(",",$filters['ad_ids']).")";
      }
      if (isset($filters['requester_id'])) {
        $qry .= " and r.user_id IN(".implode(",",$filters['requester_id']).")";
      }
      if (isset($filters['onlypickedby'])) {
        $qry .= " and ur.admin_id = ".$filters['onlypickedby'];
      }
      if (isset($filters['undown']) && $filters['undown'] === true) {
        $qry .= " and (ur.admin_id IS NOT NULL && ur.feedback = -1)";
      }
      if (isset($filters['onlyunpicked']) && $filters['onlyunpicked'] === true) {
        $qry .= " and (ur.admin_id IS NULL && ur.feedback = -1)";
      }
      if (isset($filters['onlyaccepted']) && $filters['onlyaccepted'] === true) {
        $qry .= " and (ur.feedback = 1)";
      }
      if (isset($filters['onlydeclined']) && $filters['onlydeclined'] === true) {
        $qry .= " and (ur.feedback = 0)";
      }
      if (isset($filters['target_user'])) {
        $qry .= " and ur.user_id IN(".implode(",",$filters['target_user']).")";
      }
      if (isset($filters['search'])) {
        $filters['search'] = trim($filters['search']);
        $qry .= " and (ma.name LIKE '%".$filters['search']."%' || ma.email LIKE '%".$filters['search']."%' || mv.name LIKE '%".$filters['search']."%' || mv.email LIKE '%".$filters['search']."%')";
      }

    }

    // Order
    $qry .= " ORDER BY ur.admin_id ASC, ur.feedback DESC, r.requested_at DESC ";

    // Limit
    $limit = $this->getLimit($arg);
    $qry .= " LIMIT ".$limit[0].", ".$limit[1];

    //echo $qry;
    $top_cat_qry 	= $this->db->query($qry);
    $top_cat_data 	= $top_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

    $this->total_items 	= $this->db->query("SELECT FOUND_ROWS();")->fetchColumn();
    $this->total_pages 	= ceil( $this->total_items / $limit[1] );

    if( $top_cat_qry->rowCount() == 0 ) return $this;

    foreach ( $top_cat_data as $top_cat ) {
      $this->tree_items++;

      //$top_cat['messanger'] = $this->getMessangerSession($top_cat['allas_id'], $top_cat['user_id']);

      if (!in_array($top_cat['allas_id'], $this->tree_ids)) {
        $this->tree_ids[] = $top_cat[allas_id];
      }

      if ($top_cat['access_granted'] == '1') {
        $this->infos['requests']['access_granted']++;
        $this->request_info[$top_cat['allas_id']]['requests']['access_granted']++;
      } else if($top_cat['access_granted'] == '0' && $top_cat['feedback'] == '0') {
        $this->infos['requests']['declined']++;
        $this->request_info[$top_cat['allas_id']]['requests']['declined']++;
      }

      if($top_cat['feedback'] == '1'){
        $this->request_info[$top_cat['allas_id']]['requests']['accepted']++;
        $this->infos['requests']['accepted']++;
      }

      if($top_cat['access_granted'] == '1' || $top_cat['feedback'] != '-1'){
        $this->request_info[$top_cat['allas_id']]['requests']['finished']++;
        $this->infos['requests']['finished']++;
      }

      if($top_cat['access_granted'] == '0' && $top_cat['feedback'] == '-1'){
        $this->request_info[$top_cat['allas_id']]['requests']['untouched']++;
        $this->infos['requests']['untouched']++;
      }

      if(!isset($this->tree_steped_item[$top_cat[allas_id]]['ID'])){
        $this->tree_steped_item[$top_cat[allas_id]]['ID'] = $top_cat['allas_id'];
        $this->tree_steped_item[$top_cat[allas_id]]['data'] = (new Allasok(array('controller' => $this->controller)))->load($top_cat['allas_id']);
        $tree[$top_cat[allas_id]]['ID'] = $top_cat['allas_id'];
      }

      $top_cat['user'] = new User($top_cat['user_id'], array('controller' => $this->controller));

      $this->tree_steped_item[$top_cat[allas_id]][items][] = $top_cat;
      $tree[$top_cat[allas_id]][items][] = $top_cat;
    }

    $this->tree = $tree;
    return $this;
  }

  public function get()
  {
    return $this->current_category;
  }

  public function walk()
  {
    if( !$this->tree_steped_item ) return false;

    if ($this->walk_step == $this->last_tree_id) {
      if($this->close_loop){
        $this->walk_step = 0;
        $this->current_category = false;
        return false;
      }
      $this->close_loop = true;
    }

    if( !$this->last_tree_id ){
      $this->last_tree_id = end($this->tree_ids);
      reset($this->tree_ids);
    }

    if ( !$this->current_category ) {
      reset($this->tree_ids);
      $this->walk_step = current($this->tree_ids);
    } else {
      $this->walk_step = next($this->tree_ids);
    }

    //$this->current_category = $this->walk_step;
    $this->current_category = $this->tree_steped_item[$this->walk_step];

    return true;
  }

  public function pick($admin = false, $id = null)
  {
    if (!$admin || is_null($id)) {
      return false;
    }

    $cs = (int)$this->db->query("SELECT admin_id FROM ".\FlexTimeResort\Allasok::DB_USERREQUEST_USERS." WHERE ID = '{$id}'")->fetchColumn();

    if ( $cs !== 0) {
      throw new \Exception("Időközben már felvette egy adminisztrátor a kérelem kezelését.");
    }

    $this->db->update(
      \FlexTimeResort\Allasok::DB_USERREQUEST_USERS,
      array(
        'admin_id' => $admin
      ),
      sprintf("ID = '%s'", $id)
    );

    return true;
  }

  public function setGranted($admin = false, $id = null)
  {
    if (!$admin || is_null($id)) {
      return false;
    }

    $this->db->update(
      \FlexTimeResort\Allasok::DB_USERREQUEST_USERS,
      array(
        'access_granted' => 1,
        'granted_date_at' => NOW
      ),
      sprintf("ID = '%s'", $id)
    );

    return true;
  }

  public function setDecline($admin = false, $id = null)
  {
    if (!$admin || is_null($id)) {
      return false;
    }

    $this->db->update(
      \FlexTimeResort\Allasok::DB_USERREQUEST_USERS,
      array(
        'feedback' => 0
      ),
      sprintf("ID = '%s'", $id)
    );

    return true;
  }

  public function setAllow($admin = false, $id = null, $access_granted = false)
  {
    if (!$admin || is_null($id)) {
      return false;
    }

    $this->db->update(
      \FlexTimeResort\Allasok::DB_USERREQUEST_USERS,
      array(
        'feedback' => 1,
        'access_granted' => ($access_granted) ? 1 : 0,
        'granted_date_at' => ($access_granted) ? NOW : NULL
      ),
      sprintf("ID = '%s'", $id)
    );

    return true;
  }

  private function getLimit( $arg = array() )
	{
		$limit = array( 0, 25 );
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
}

?>
