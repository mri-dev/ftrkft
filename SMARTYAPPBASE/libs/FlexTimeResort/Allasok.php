<?php
namespace FlexTimeResort;

use ExceptionManager\RedirectException;
use PortalManager\User;
use AlertsManager\Alerts;

class Allasok
{
  const DBTABLE = 'allasok';
  const DB_META = 'allasok_meta';
  const DB_TERM_RELATIONS = 'allasok_x_terms';
  const DB_TERM_RELATIONS_ITEM = 'allasok_x_terms_item';
  const DB_REQUEST_X = 'user_accept_x_allasok';
  const DB_LOG_VIEW = 'user_allasok_view';

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
  private $edit_id = false;

  public function __construct($arg = array())
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
  public function load( $id, $arg = array() )
  {
    $this->edit_id = $id;
    $this->getTree( $arg )->walk();
    return $this;
  }
  public function creator( $id = false, $data = array() )
  {
    if ( !$id ) {
      // Létrehozás
      $updates = array();
      $metas = array();

      $updates['created_by_admin'] = (int)$data['created_by_admin'];
      $updates['author_id'] = $data['author_id'];
      $updates['publish_after'] = (!isset($data['publish_now']) || $data['publish_now']) ? NOW : $data['publish_after'];
      $updates['keywords'] = $data['keywords'];
      $updates['megye_id'] = (int)$data['megye_id'];
      $updates['city'] = $data['city'];
      $updates['short_desc'] = strip_tags($data['short_desc']);
      $updates['pre_content'] = $data['pre_content'];
      $updates['content'] = $data['content'];

      $updates['author_name'] = (isset($data['author_name']) && !empty($data['author_name'])) ? $data['author_name']:null;
      $updates['author_phone'] = (isset($data['author_phone']) && !empty($data['author_phone'])) ? $data['author_phone']:null;
      $updates['author_email'] = (isset($data['author_email']) && !empty($data['author_email'])) ? $data['author_email']:null;
      $updates['city_slug'] = \Helper::makeSafeURL($data['city']);
      $updates['active'] = ($data['active']) ? 1 : 0;

      // Metas
      $metas['hirdetes_kategoria'] = array(
        'value' => (int)$data['hirdetes_kategoria'],
        'is_term_list' => 0
      );
      $metas['hirdetes_tipus'] = array(
        'value' => (int)$data['hirdetes_tipus'],
        'is_term_list' => 0
      );
      if (count($data['munkakorok']['ids']) != 0) {
        foreach ((array)$data['munkakorok']['ids'] as $mid) {
          $metas['munkakorok'][] = array(
            'value' => (int)$mid,
            'is_term_list' => 0
          );
        }
      }
      if(!empty($updates)){
        $id = $this->db->insert(
          self::DBTABLE,
          $updates
        );

        if ($updates['created_by_admin'] == 0) {
          (new Alerts(array('controller' => $this->controller)))->add(
            $updates['author_id'],
            'allas_letrehozas_sikeres',
            $id
          );
        }
      }
      $this->rebuildMetas($id, $metas);
      $this->rebuildTermRelations($id, $data['tematic_list']);
    } else {
      // Update
      $updates = array();
      $metas = array();
      $updates['keywords'] = $data['keywords'];
      $updates['megye_id'] = (int)$data['megye_id'];
      $updates['city'] = $data['city'];
      $updates['short_desc'] = strip_tags($data['short_desc']);
      $updates['pre_content'] = $data['pre_content'];
      $updates['content'] = $data['content'];
      $updates['author_name'] = (isset($data['author_name']) && !empty($data['author_name'])) ? $data['author_name']:null;
      $updates['author_phone'] = (isset($data['author_phone']) && !empty($data['author_phone'])) ? $data['author_phone']:null;
      $updates['author_email'] = (isset($data['author_email']) && !empty($data['author_email'])) ? $data['author_email']:null;
      $updates['city_slug'] = \Helper::makeSafeURL($data['city']);
      $updates['active'] = ($data['active']) ? 1 : 0;
      // Metas
      $metas['hirdetes_kategoria'] = array(
        'value' => (int)$data['hirdetes_kategoria'],
        'is_term_list' => 0
      );
      $metas['hirdetes_tipus'] = array(
        'value' => (int)$data['hirdetes_tipus'],
        'is_term_list' => 0
      );
      if (count($data['munkakorok']['ids']) != 0) {
        foreach ((array)$data['munkakorok']['ids'] as $mid) {
          $metas['munkakorok'][] = array(
            'value' => (int)$mid,
            'is_term_list' => 0
          );
        }
      }
      if(!empty($updates)){
        $this->db->update(
          self::DBTABLE,
          $updates,
          sprintf("ID = %d", $id)
        );
      }
      $this->rebuildMetas($id, $metas);
      $this->rebuildTermRelations($id, $data['tematic_list']);
    }
    return $id;
  }
  public function checkRequestAd($uid, $adid)
  {
    $adid = (int)$adid;
    $uid = (int)$uid;
    if ($uid == 0 || $adid == 0) {
      return false;
    }
    $check = $this->db->query("SELECT hashkey FROM ".self::DB_REQUEST_X." WHERE allas_id = {$adid} and user_id = {$uid}")->fetchColumn();
    return (!empty($check)) ? $check : false;
  }
  public function getRequestHashkey($uid, $adid)
  {
    $adid = (int)$adid;
    $uid = (int)$uid;
    $data = $this->db->query("SELECT
      r.hashkey
    FROM ".self::DB_REQUEST_X." as r
    WHERE r.user_id = '{$uid}' and r.allas_id = {$adid}
    ")->fetchColumn();
    return $data;
  }
  public function getRequest($hashkey)
  {
    $request = $this->db->query("SELECT
      r.*,
      a.name as admin_name
    FROM ".self::DB_REQUEST_X." as r
    LEFT OUTER JOIN admin as a ON a.ID = r.admin_accept_id
    WHERE r.hashkey = '{$hashkey}'
    ")->fetch(\PDO::FETCH_ASSOC);
    return $request;
  }
  public function requestAd($uid, $adid)
  {
    $adid = (int)$adid;
    $uid = (int)$uid;
    if ($uid == 0 || $adid == 0) {
      return false;
    }
    $check = $this->checkRequestAd( $uid, $adid );
    if( !$check ){
      $hashkey = uniqid();
      $this->db->insert(
          self::DB_REQUEST_X,
          array(
            'hashkey' => $hashkey,
            'user_id' => $uid,
            'allas_id' => $adid,
          )
      );
      return $hashkey;
    } else {
      return true;
    }
  }
  public function logVisit($uid = false)
  {
    $this->db->insert(
        self::DB_LOG_VIEW,
        array(
          'ip' => $_SERVER['REMOTE_ADDR'],
          'user_id' => ($uid) ? $uid : NULL,
          'allas_id' => $this->getID()
        )
    );
  }
  public function rebuildTermRelations( $id, $terms = array() )
  {
    $insert = array();
    if (!empty($terms)) {
      $this->db->query("DELETE FROM ".self::DB_TERM_RELATIONS." WHERE allas_id = {$id};");
      $this->db->query("DELETE FROM ".self::DB_TERM_RELATIONS_ITEM." WHERE allas_id = {$id};");
    }
    $gindex = 0;
    foreach ((array)$terms as $term) {
      $term_id = $this->db->query("SELECT ID FROM ".\PortalManager\Categories::DB_LIST." WHERE termkey = '{$term[value]}'")->fetchColumn();
      $last_insert_xtermid = $this->db->insert(
        self::DB_TERM_RELATIONS,
        array(
          'allas_id' => $id,
          'term_id' => $term_id,
          'title' => ($term['title']) ? $term['title'] : null,
          'sortindex' => $gindex
        )
      );
      $title = ($term['title']) ? $term['title'] : null;
      $index = 0;
      foreach ((array)$term['selectedValues'] as $seli) {
        $insert[] = array($last_insert_xtermid, (int)$seli, $id, $index);
        $index++;
      }
      $gindex++;
    }
    if(!empty($insert)) {
      $this->db->multi_insert(
        self::DB_TERM_RELATIONS_ITEM,
        array('allas_x_term_id', 'term_id', 'allas_id', 'sortindex'),
        $insert,
        array('debug' => false)
      );
    }
  }
  private function rebuildMetas($id, $metas = array())
  {
    $insert = array();
    if (!empty($metas)) {
      $this->db->query("DELETE FROM ".self::DB_META." WHERE allas_id = {$id};");
    }
    foreach ((array)$metas as $kulcs => $meta) {
      if (is_array($meta[0])) {
        foreach ((array)$meta as $m) {
          $insert[] = array($kulcs, $m['value'], $m['is_term_list'], $id);
        }
      } else {
        $insert[] = array($kulcs, $meta['value'], $meta['is_term_list'], $id);
      }
    }
    if(!empty($insert)) {
      $this->db->multi_insert(
        self::DB_META,
        array('kulcs', 'value', 'is_term_list', 'allas_id'),
        $insert,
        array('debug' => false)
      );
    }
  }
  public function getTree( $arg = array() )
	{
		$tree = array();
    $filters = $arg['filters'];
		$qry = "
			SELECT SQL_CALC_FOUND_ROWS
        a.*,
        u.name as oauthor_name,
        u.email as oauthor_email,
        (SELECT ertek FROM accounts_details WHERE fiok_id = a.author_id and nev = 'telefon') as oauthor_phone
			FROM ".self::DBTABLE." as a
      LEFT OUTER JOIN accounts as u ON u.ID = a.author_id
			WHERE 1=1";
    if ( !$this->admin ) {
        $qry .= " and a.active = 1";
    }
    if ( $this->edit_id !== false ) {
        $qry .= " and a.ID = ".$this->edit_id;
    }
    if (isset($arg['author_id'])) {
      $qry .= " and a.author_id = ".(int)$arg['author_id'];
    }
    if (isset($arg['active_in']) && is_array($arg['active_in'])) {
      $qry .= " and a.active IN(".implode(",", $arg['active_in']).")";
    }
    if(isset($arg['show_history'])){
      $idset_orderby = array();
      if($arg['show_history'] === true) {
        $history_row = 'ip';
        $hval = $_SERVER['REMOTE_ADDR'];
      } else if(is_numeric($arg['show_history'])) {
        $history_row = 'user_id';
        $hval = (int)$arg['show_history'];
      }
      $idset_orderby = $this->getVisitedIDS($history_row, $hval);
      $qry .= " and a.ID IN (".implode(",",$idset_orderby).")";
    }
    if(isset($arg['show_requests'])){
      $idset_orderby = array();
      $history_row = 'user_id';
      $hval = (int)$arg['show_requests'];
      $idset_orderby = $this->getRequestsIDS($history_row, $hval);
      $qry .= " and a.ID IN (".implode(",",$idset_orderby).")";
    }
    // Filterek
    if (isset($filters) && !empty($filters)) {
      // ID
      if ($filters['ID']) {
        $qry .= " and a.ID = '{$filters[ID]}'";
      }
      // ID
      if ($filters['search']) {
        $qry .= " and (a.short_desc LIKE '%{$filters['search']}%' || a.keywords LIKE '%{$filters['search']}%')";
      }

      // Város
      if ($filters['city']) {
        $city = \Helper::makeSafeURL(trim($filters['city']));
        $qry .= " and a.city_slug = '$city'";
      }
      // Megye
      if ($filters['megye']) {
        if (is_array($filters['megye'])) {
          $megye = $filters['megye'];
          $qry .= " and a.megye_id IN (".implode(",", $megye).")";
        } else {
          $megye = $filters['megye'];
          $qry .= " and a.megye_id = $megye";
        }
      }
      // Keresés
      if ($filters['search']) {
        $keywords = explode(" ", trim($filters['search']));
        $search_qry = '';
        foreach ((array)$keywords as $key) {
          $key = trim($key);
          $search_qry .= "(a.keywords LIKE '%".$key."%' or a.short_desc LIKE '%".$key."%') or ";
        }
        $search_qry = rtrim($search_qry, ' or ');
        $qry .= ' and ('.$search_qry.')';
      }
      // Meták
      if (isset($filters['meta'])) {
        foreach ((array)$filters['meta'] as $key => $value) {
          if(!$value) continue;
          if (is_array($value)) {
            $qry .= " and (SELECT value FROM ".self::DB_META." WHERE allas_id = a.ID and kulcs = '{$key}') IN(".implode(",", $value).")";
          } else {
            $qry .= " and (SELECT value FROM ".self::DB_META." WHERE allas_id = a.ID and kulcs = '{$key}') IN(".(int)$value.")";
          }
        }
      }
    }

    if (isset($arg['active_in']) && is_array($arg['active_in'])) {
      $qry .= " and a.active IN(".implode(",", $arg['active_in']).")";
    }
		if( !$this->o['orderby'] ) {
      if ($idset_orderby) {
        $qry .= " ORDER BY FIELD(a.ID, ".implode(",", (array)$idset_orderby).")";
      } else {
        $qry .= " ORDER BY a.publish_after DESC";
      }
		} else {
			$qry .= " ORDER BY a.".$this->o['orderby']." ".$this->o['order'];
		}
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
      $top_cat['metas'] = $this->loadMetas($top_cat['ID']);
      $top_cat['term_list'] = $this->loadTermList($top_cat['ID']);
      if($top_cat['metas'])
      foreach ($top_cat['metas'] as $meta) {
        if($meta['kulcs'] == 'munkakorok') {
          $top_cat['munkakorok'][(int)$meta['ID']] = array(
            'ID' => (int)$meta['ID'],
            'is_term_list' => (int)$meta['is_term_list'],
            'value' => (int)$meta['value'],
            'value_text' => $meta['value_text'],
          );
        }else{
          $top_cat[$meta['kulcs']] = $meta['value'];
        }
      }
      $top_cat['tipus_name'] = $this->getTermName($top_cat['hirdetes_tipus']);
      $top_cat['cat_name'] = $this->getTermName($top_cat['hirdetes_kategoria']);
      $top_cat['megye_name'] = $this->getTermName($top_cat['megye_id']);
			$this->tree_steped_item[] = $top_cat;
			$tree[] = $top_cat;
		}
		$this->tree = $tree;
		return $this;
	}
  public function getVisitedIDS($by = 'ID', $val = 0, $limit = 10)
  {
    $ids = array();
    $set = $this->db->query("
    SELECT
      v.allas_id
    FROM ".self::DB_LOG_VIEW." as v
    WHERE v.{$by} = '{$val}'
    ORDER BY v.visit_at DESC")->fetchAll(\PDO::FETCH_ASSOC);
    foreach ((array)$set as $s) {
      if(count($ids) >= $limit) continue;
      if(!in_array((int)$s['allas_id'], $ids)){
        $ids[] = (int)$s['allas_id'];
      }
    }
    return $ids;
  }
  public function getRequestsIDS($by = 'user_id', $val = 0)
  {
    $ids = array();
    $set = $this->db->query("
    SELECT
      v.allas_id
    FROM ".self::DB_REQUEST_X." as v
    WHERE v.{$by} = '{$val}'
    ORDER BY v.request_at DESC")->fetchAll(\PDO::FETCH_ASSOC);
    foreach ((array)$set as $s) {
      if(!in_array((int)$s['allas_id'], $ids)){
        $ids[] = (int)$s['allas_id'];
      }
    }
    return $ids;
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

  private function loadTermList( $adid )
  {
    $data = array();
    $terms = $this->db->query("
    SELECT
      tr.title,
      tr.term_id,
      t.term_id as value_id,
      term.neve as value_name,
      term.langkey as value_langkey_name,
      term.groupkey as slug,
      tl.neve as termlist_name,
      tl.langkey as termlist_langkey_name
    FROM ".self::DB_TERM_RELATIONS_ITEM." as t
    LEFT OUTER JOIN ".self::DB_TERM_RELATIONS." as tr ON tr.ID = t.allas_x_term_id
    LEFT OUTER JOIN ".\PortalManager\Categories::DBTERMS." as term ON term.id = t.term_id
    LEFT OUTER JOIN ".\PortalManager\Categories::DB_LIST." as tl ON tl.termkey = term.groupkey
    WHERE 1=1 and
    t.allas_id = {$adid}
    ORDER BY tr.sortindex ASC, t.sortindex ASC
    ")->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($terms as $term) {
      $tid = (int)$term['term_id'];
      $data[$tid]['value_texts'][] = $term['value_name'];
      $data[$tid]['term_ids'][] = (int)$term['value_id'];
      $data[$tid]['ID'] = $tid;
      $data[$tid]['title'] = (!empty($term['title'])) ? $term['title'] : $term['termlist_name'];
      $data[$tid]['slug'] = $term['slug'];
      $data[$tid]['data'][] = array(
        'ID' => (int)$term['value_id'],
        'name' => $term['value_name']
      );
    }
    return $data;
  }
  private function loadMetas( $adid )
  {
    $metas = array();
    $datas = $this->db->query("SELECT
      m.ID, m.kulcs, m.value, m.is_term_list, t.neve as value_text
    FROM ".self::DB_META." as m
    LEFT OUTER JOIN ".\PortalManager\Categories::DBTERMS." as t ON t.ID = m.value
    WHERE m.allas_id = '{$adid}'")->fetchAll(\PDO::FETCH_ASSOC);
    foreach ((array)$datas as $d) {
      $metas[$d['ID']] = array_map(function($d){
        $d = trim($d);
        if(is_numeric($d)) {
          $d = (int)$d;
        }
        return $d;
      },$d);
    }
    return $metas;
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
  private function getTermName($id = false)
  {
    if (!$id) {
      return null;
    }
    $value = $this->db->query("SELECT neve FROM terms WHERE id = {$id}")->fetchColumn();
    return $value;
  }
  public function prepareOutput()
  {
    $this->current_category['tipus_name'] = $this->getTermName($this->getTypeID());
    $this->current_category['cat_name'] = $this->getTermName($this->getCatID());
    $this->current_category['megye_name'] = $this->getTermName($this->getMegyeID());
  }
  /*===============================
	=            GETTERS            =
	===============================*/
  public function get( $key = false )
	{
    if ($key) {
      return $this->current_category[$key];
    } else {
      $this->prepareOutput();
      return $this->current_category;
    }
	}
  public function getMetas($by = false, $byval = false)
  {
    $metas = $this->current_category[metas];
    $re = array();
    if( $by === false ) return $metas;
    foreach ((array)$metas as $key => $value) {
      if($value[$by] != $byval ) continue;
      $re[$key] = $value;
    }
    return $re;
  }
  public function getTerms()
  {
    return $this->current_category['term_list'];
  }
  public function getKeywords( $arrayed = true )
  {
    if ($arrayed) {
      $arr = array();
      $keys = explode(",", $this->get('keywords'));
      foreach ((array)$keys as $key) {
        $arr[] = trim($key);
      }
      return $arr;
    } else {
      return $this->get('keywords');
    }
  }
	public function getID()
	{
		return $this->current_category['ID'];
	}
  public function getTypeID()
  {
    return $this->current_category['hirdetes_tipus'];
  }
  public function getMegyeID()
  {
    return $this->current_category['megye_id'];
  }
  public function getCatID()
  {
    return $this->current_category['hirdetes_kategoria'];
  }
  public function shortDesc()
  {
    return $this->current_category['short_desc'];
  }
  public function getPreContent()
  {
    return $this->current_category['pre_content'];
  }
  public function getContent()
  {
    return $this->current_category['content'];
  }
  public function getPublishDate( $format = 'Y. m. d.')
  {
    return date($format, strtotime($this->current_category['publish_after']));
  }
  public function createDate( $format = 'Y. m. d.')
  {
    return date($format, strtotime($this->current_category['upload_at']));
  }
  public function getMegye()
  {
    return $this->current_category['megye_name'];
  }
  public function getCity()
  {
    return $this->current_category['city'];
  }
  public function createdBy()
  {
    $data = array();

    $data[by] = ($this->get('created_by_admin') != 0) ? 'admin' : 'user';
    $data[ID] = ($data[by] == 'admin') ? $this->get('created_by_admin') : $this->get('author_id');

    switch ($data[by]) {
      case 'user':
        $data['name'] = $this->db->query("SELECT name FROM ".\PortalManager\Users::TABLE_NAME." WHERE ID = {$data[ID]};")->fetchColumn();
      break;
      default:
        $data['name'] = $this->db->query("SELECT name FROM admin WHERE ID = {$data[ID]};")->fetchColumn();
      break;
    }

    return $data;
  }
  public function getAuthorData( $key = 'name')
  {
    switch ($key) {
      case 'name':
        $alt = $this->current_category['author_name'];
        if (is_null($alt)) {
          return $this->current_category['oauthor_name'];
        } else return $alt;
      break;
      case 'email':
        $alt = $this->current_category['author_email'];
        if (is_null($alt)) {
          return $this->current_category['oauthor_email'];
        } else return $alt;
      break;
      case 'phone':
        $alt = $this->current_category['author_phone'];
        if (is_null($alt)) {
          return $this->current_category['oauthor_phone'];
        } else return $alt;
      break;
      case 'ID':
        $alt = $this->current_category['author_id'];
        return $alt;
      break;
      case 'author':
        $obj = new User($this->get('author_id'), array('controller' => $this->controller));
        return $obj;
      break;
    }
  }
  public function getURL()
  {
    $SEO = '';
    $SEO .= \Helper::makeSafeURL($this->getCity(),'');
    $SEO .= '/'.\Helper::makeSafeURL($this->getAuthorData('name'),'');
    return $this->settings['allas_page_slug'] . $SEO . '_'.$this->getID();
  }
  private function kill( $msg = '' )
	{
		throw new \Exception( $msg . ' ('.__FILE__.')' );
	}
	private function error( $msg )
	{
		throw new RedirectException( $msg, $_POST['form'], $_POST['return'], $_POST['session_path'] );
	}
  public function __destruct()
	{
		$this->db = null;
		$this->smarty = false;
		$this->settings = null;
		$this->controller = null;
    $this->tree = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->walk_step = 0;
	}
}
?>
