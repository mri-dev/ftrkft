<?php
namespace PortalManager;

use ExceptionManager\RedirectException;

class Articles extends \Controller
{
  const DBTABLE = 'articles';

  private $category_table = false;
	public $db = null;
	public $tree = false;
  public $raw = false;
	private $current_category = false;
	private $tree_steped_item = false;
	private $tree_items = 0;
	private $walk_step = 0;
	private $parent_data = false;
	private $o = array();
  private $admin = false;
  private $selected_id = false;

  public function __construct( $itemid = false, $arg = array() )
  {
    parent::__construct();

    $this->admin = (isset($arg['admin']) && $arg['admin'] === true) ? true : false;

    if ( $itemid ) {
			$this->selected_id = $itemid;
		}

    return $this;
  }

  public function get( $page_id_or_slug )
	{
		$data = array();
		$qry = "
			SELECT 				*
			FROM 				".self::DBTABLE."
		";

		if (is_numeric($page_id_or_slug)) {
			$qry .= " WHERE ID = ".$page_id_or_slug;
		}else {
			$qry .= " WHERE slug = '".$page_id_or_slug."'";
		}


		if ( !$this->admin ) {
			$qry .= " and active = 1 ";
		}

		$qry = $this->db->query($qry);

		$this->current_item = $qry->fetch(\PDO::FETCH_ASSOC);

		return $this;
	}

	public function add( $data = array() )
	{
		$title = ($data['title']) ?: false;
    $seo_keywords = ($data['seo_keywords']) ?: NULL;
    $seo_desc = ($data['seo_desc']) ?: NULL;
    $content = ($data['content']) ?: false;
    $image = ($data['image']) ?: NULL;
    $publish_after = ($data['publish_after']) ?: date('Y-m-d');
    $eleres = ($data['slug']) ?: false;

		if ( !$title ) {
			$this->error( "Kérjük, hogy adja meg a cikk címét!" );
		}

    if (!$eleres) {
			$eleres = $this->checkEleres( $title );
		} else {
			$eleres = \PortalManager\Formater::makeSafeUrl($eleres,'');
		}

		$this->db->insert(
			self::DBTABLE,
			array(
				'title' => $title,
        'slug' => $slug,
        'seo_keywords' => $seo_keywords,
        'seo_desc' => $seo_desc,
        'content' => $content,
        'image' => $image,
        'publish_after' => $publish_after,
        'slug' => $eleres
			)
		);
	}

	public function edit( $data = array() )
	{
    $id = $this->selected_id;

    if(!$id) $this->error( "Hiányzik a cikk ID-ja! Nem végezhető el a módosítás." );

    $title = ($data['title']) ?: false;
    $seo_keywords = ($data['seo_keywords']) ?: NULL;
    $seo_desc = ($data['seo_desc']) ?: NULL;
    $content = ($data['content']) ?: false;
    $image = ($data['image']) ?: NULL;
    $publish_after = ($data['publish_after']) ?: date('Y-m-d');
    $eleres = ($data['slug']) ?: false;
    $lathato = (isset($data['lathato'])) ? 1 : 0;

		if ( !$title ) {
			$this->error( "Kérjük, hogy adja meg a cikk címét!" );
		}

    if (!$eleres) {
			$eleres = $this->checkEleres( $title );
		} else {
			$eleres = \PortalManager\Formater::makeSafeUrl($eleres,'');
		}

    $this->db->update(
      self::DBTABLE,
      array(
				'title' => $title,
        'slug' => $slug,
        'seo_keywords' => $seo_keywords,
        'seo_desc' => $seo_desc,
        'content' => $content,
        'image' => $image,
        'publish_after' => $publish_after,
        'slug' => $eleres,
        'active' => $lathato,
			),
      sprintf("ID = %d", $id)
    );

	}

	public function delete()
	{
		//$category->delete();
	}

	public function getTree( $arg = array() )
	{
		$tree = array();

		// Legfelső színtű kategóriák
		$qry = "
			SELECT 			*
			FROM 			".self::DBTABLE."
			WHERE 		1=1 ";

    if (isset($arg['exc_ids']) && is_array($arg['exc_ids']) && !empty($arg['exc_ids'])) {
      $qry .= " and ID NOT IN (".implode(",", $arg['exc_ids']).") ";
    }

    if ( !$this->admin ) {
      $qry .= " and active = 1 ";
      $qry .= " and now() >= publish_after ";
    } else {

    }

		if( !$arg['orderby'] ) {
			$qry .= "
				ORDER BY publish_after DESC;";
		} else {
			$qry .= "
				ORDER BY 		".$arg['orderby']." ".$arg['order'].";";
		}

    $qarg = array();
    $qarg['multi'] = true;
    $qarg['limit'] = (isset($arg['limit'])) ? $arg['limit'] : 20;
    $qarg['offset'] = (isset($arg['offset'])) ? $arg['offset'] : false;
    $qarg['page'] = (isset($arg['page'])) ? $arg['page'] : 1;
    extract($this->db->q($qry, $qarg));

    $this->raw = $ret;
    //print_r($ret[info]);
		//if( $top_cat_qry->rowCount() == 0 ) return $this;

		foreach ( (array)$data as $top_cat ) {
			$this->tree_items++;
			$this->tree_steped_item[] = $top_cat;
			$tree[] = $top_cat;
		}

		$this->tree = $tree;

		return $this;
	}

	public function walk()
	{
		if( !$this->tree_steped_item ) return false;

		$this->current_item = $this->tree_steped_item[$this->walk_step];

		$this->walk_step++;

		if ( $this->walk_step > $this->tree_items ) {
			// Reset Walk
			$this->walk_step = 0;
			$this->current_item = false;

			return false;
		}

		return true;
	}

  private function checkEleres( $text )
	{
		$text = Formater::makeSafeUrl($text,'');

		$qry = $this->db->query(sprintf("
			SELECT 		slug
			FROM 		".self::DBTABLE."
			WHERE 		slug = '%s' or
						slug like '%s-_' or
						slug like '%s-__'
			ORDER BY 	slug DESC
			LIMIT 0,1", trim($text), trim($text), trim($text) ));
		$last_text = $qry->fetch(\PDO::FETCH_COLUMN);

		if( $qry->rowCount() > 0 ) {

			$last_int = (int)end(explode("-",$last_text));

			if( $last_int != 0 ){
				$last_text = str_replace('-'.$last_int, '-'.($last_int+1) , $last_text);
			} else {
				$last_text .= '-1';
			}
		} else {
			$last_text = $text;
		}

		return $last_text;
	}


	/*===============================
	=            GETTERS            =
	===============================*/

	public function getID()
	{
		return $this->current_item['ID'];
	}

	public function getTitle()
	{
		return $this->current_item['title'];
	}

  public function getSEODesc()
	{
		return $this->current_item['seo_desc'];
	}

  public function getHtmlContent()
  {
    return $this->current_item['content'];
  }

  public function getSlug()
	{
		return $this->current_item['slug'];
	}

  public function getPublishAfter()
	{
		return $this->current_item['publish_after'];
	}

  public function getCreateDate()
  {
    return $this->current_item['create_at'];
  }

  public function getKeywords()
	{
		return $this->current_item['seo_keywords'];
	}

  public function Keywords()
  {
    $arr = array();

    $keys = explode(",", $this->getKeywords());

    $arr = $keys;

    return $arr;
  }

  public function getImage()
  {
    return $this->current_item['image'];
  }

  public function isActive()
  {
    return ($this->current_item['active'] == 1) ? true : false;
  }

  public function URL()
  {
    return '/cikk/'.$this->current_item['slug'];
  }

  public function Image()
  {
    $img = $this->current_item['image'];

    if( empty($img) ) {
      return IMG . 'logo-siluette-noimg.svg';
    }

    return $img;
  }

	/*=====  End of GETTERS  ======*/

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
		$this->tree = false;
		$this->current_item = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->walk_step = 0;
		$this->parent_data = false;
		$this->o = null;
	}
}
?>
