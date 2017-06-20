<?php
namespace FlexTimeResort;

use ExceptionManager\RedirectException;

class Allasok
{
  const DBTABLE = 'allasok';
  const DB_META = 'allasok_meta';
  const DB_TERM_RELATIONS = 'allasok_x_terms';
  const DB_TERM_RELATIONS_ITEM = 'allasok_x_terms_item';

  public $db = null;
  private $controller = null;
  private $smarty = null;
  private $settings = array();
  private $admin = false;

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

    } else {
      // Update
      $updates = array();
      $metas = array();

      $updates['keywords'] = $data['keywords'];
      $updates['megye_id'] = (int)$data['megye_id'];
      $updates['city'] = $data['city'];
      $updates['pre_content'] = $data['pre_content'];
      $updates['content'] = $data['content'];
      $updates['author_name'] = (isset($data['author_name']) && !empty($data['author_name'])) ? $data['author_name']:null;
      $updates['author_phone'] = (isset($data['author_phone']) && !empty($data['author_phone'])) ? $data['author_phone']:null;
      $updates['author_email'] = (isset($data['author_email']) && !empty($data['author_email'])) ? $data['author_email']:null;
      $updates['city_slug'] = \Helper::makeSafeURL($data['city']);

      // Metas
      $metas['hirdetes_kategoria'] = array(
        'value' => (int)$data['hirdetes_kategoria'],
        'is_term_list' => 0
      );
      $metas['hirdetes_tipus'] = array(
        'value' => (int)$data['hirdetes_tipus'],
        'is_term_list' => 0
      );

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
      $insert[] = array($kulcs, $meta['value'], $meta['is_term_list'], $id);
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

		$qry = "
			SELECT
        a.*,
        u.name as oauthor_name,
        u.email as oauthor_email,
        (SELECT ertek FROM accounts_details WHERE fiok_id = a.author_id and nev = 'telefon') as oauthor_phone
			FROM ".self::DBTABLE." as a
      LEFT OUTER JOIN accounts as u ON u.ID = a.author_id
			WHERE 1=1";

    if ( $this->edit_id !== false ) {
        $qry .= " and a.ID = ".$this->edit_id;
    }

    if (isset($arg['author_id'])) {
      $qry .= " and a.author_id = ".(int)$arg['author_id'];
    }

    if (isset($arg['active_in']) && is_array($arg['active_in'])) {
      $qry .= " and a.active IN(".implode(",", $arg['active_in']).")";
    }

		if( !$this->o['orderby'] ) {
			$qry .= "
				ORDER BY 		a.publish_after DESC;";
		} else {
			$qry .= "
				ORDER BY 		a.".$this->o['orderby']." ".$this->o['order'].";";
		}

		$top_cat_qry 	= $this->db->query($qry);
		$top_cat_data 	= $top_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

		if( $top_cat_qry->rowCount() == 0 ) return $this;

		foreach ( $top_cat_data as $top_cat ) {
			$this->tree_items++;
      $top_cat['metas'] = $this->loadMetas($top_cat['ID']);
      $top_cat['term_list'] = $this->loadTermList($top_cat['ID']);

      if($top_cat['metas'])
      foreach ($top_cat['metas'] as $meta) {
        $top_cat[$meta['kulcs']] = $meta['value'];
      }

			$this->tree_steped_item[] = $top_cat;
			$tree[] = $top_cat;
		}

		$this->tree = $tree;

		return $this;
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
      term.groupkey as slug
    FROM ".self::DB_TERM_RELATIONS_ITEM." as t
    LEFT OUTER JOIN ".self::DB_TERM_RELATIONS." as tr ON tr.ID = t.allas_x_term_id
    LEFT OUTER JOIN ".\PortalManager\Categories::DBTERMS." as term ON term.id = t.term_id
    WHERE 1=1 and
    t.allas_id = {$adid}
    ORDER BY tr.sortindex ASC, t.sortindex ASC
    ")->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($terms as $term) {
      $tid = (int)$term['term_id'];

      $data[$tid]['value_texts'][] = $term['value_name'];
      $data[$tid]['term_ids'][] = (int)$term['value_id'];
      $data[$tid]['ID'] = $tid;
      $data[$tid]['title'] = $term['title'];
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

    $datas = $this->db->query("SELECT ID, kulcs, value, is_term_list FROM allasok_meta WHERE allas_id = '{$adid}'")->fetchAll(\PDO::FETCH_ASSOC);

    foreach ((array)$datas as $d) {
      $metas[$d['ID']] = $d;
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

  private function prepareOutput()
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

  public function getPublishDate()
  {
    return date('Y. m. d.', strtotime($this->current_category['publish_after']));
  }

  public function getCity()
  {
    return $this->current_category['city'];
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
