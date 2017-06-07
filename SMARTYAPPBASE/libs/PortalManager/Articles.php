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

  public function __construct( $arg = array() )
  {
    parent::__construct();

    $this->admin = (isset($arg['admin']) && $arg['admin'] === true) ? true : false;
  }

	/**
	 * Kategória létrehzás
	 * @param array $data új kategória létrehozásához szükséges adatok
	 * @return void
	 */
	public function add( $data = array() )
	{
		$deep = 0;
		$name 		= ($data['neve']) 		?: false;
		$sort 		= ($data['sorrend']) 	?: 100;
		$parent 	= ($data['szulo_id']) 	?: NULL;

		if ($parent) {
			$xparent 	= explode('_',$parent);
			$parent 	= (int)$xparent[0];
			$deep 		= (int)$xparent[1] + 1;
		}

		if ( !$name ) {
			$this->error( "Kérjük, hogy adja meg a kategória elnevezését!" );
		}

		$this->db->insert(
			$this->category_table,
			array(
				'neve' 		=> $name,
				'szulo_id' 	=> $parent,
				'sorrend' 	=> $sort,
				'deep' 		=> $deep
			)
		);
	}

	/**
	 * Kategória szerkesztése
	 * @param  Category $category PortalManager\Category class
	 * @param  array    $new_data
	 * @return void
	 */
	public function edit( Category $category, $new_data = array() )
	{
		$deep 	= 0;
		$name 	= ($new_data['nev']) ?: false;
		$sort 	= ($new_data['sorrend']) ?: 0;
		$parent = ($new_data['szulo_id']) ?: NULL;

		if ($parent) {
			$xparent 	= explode('_',$parent);
			$parent 	= (int)$xparent[0];
			$deep 		= (int)$xparent[1] + 1;
		}

		if ( !$name ) {
			$this->error( "Kérjük, hogy adja meg a kategória elnevezését!" );
		}

		$category->edit(array(
			'neve' 		=> $name,
			'szulo_id' 	=> $parent,
			'sorrend' 	=> $sort,
			'deep' 		=> $deep
		));
	}

	public function delete( Category $category )
	{
		$category->delete();
	}

	public function getTree( $arg = array() )
	{
		$tree = array();

		// Legfelső színtű kategóriák
		$qry = "
			SELECT 			*
			FROM 			".self::DBTABLE."
			WHERE 		1=1 ";


    if ( !$this->admin ) {
      $qry .= " and active = 1 ";
      $qry .= " and now() >= publish_after ";
    } else {

    }

		if( !$this->o['orderby'] ) {
			$qry .= "
				ORDER BY publish_after ASC;";
		} else {
			$qry .= "
				ORDER BY 		".$this->o['orderby']." ".$this->o['order'].";";
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


	/*===============================
	=            GETTERS            =
	===============================*/

	public function getID()
	{
		return $this->current_item['id'];
	}

	public function getTitle()
	{
		return $this->current_item['title'];
	}

  public function getSEODesc()
	{
		return $this->current_item['seo_desc'];
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
