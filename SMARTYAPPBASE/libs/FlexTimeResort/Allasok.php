<?php
namespace FlexTimeResort;

use ExceptionManager\RedirectException;

class Allasok
{
  const DBTABLE = 'allasok';
  const DB_META = 'allasok_meta';

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
			$this->tree_steped_item[] = $top_cat;
			$tree[] = $top_cat;
		}

		$this->tree = $tree;

		return $this;
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

  /*===============================
	=            GETTERS            =
	===============================*/

	public function getID()
	{
		return $this->current_category['ID'];
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
