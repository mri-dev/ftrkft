<?
namespace PortalManager;

use PortalManager\Category;
use ExceptionManager\RedirectException;

class Categories extends \Controller
{
	const DB_LIST = 'term_list';
	const DBTERMS = 'terms';

	const TYPE_MUNKATIPUS 	= 'munkatipusok';
	const TYPE_STUDIES 		= 'oktatas_kategoriak';
	const TYPE_MUNKAKOROK 	= 'munkakorok';
	const TYPE_TERULETEK 	= 'teruletek';
	const TYPE_KOMPETENCIAK = 'munkavallaloi_kompetenciak';

	private $category_table = false;
	public $db = null;
	public $tree = false;
	private $current_category = false;
	private $tree_steped_item = false;
	private $tree_items = 0;
	private $walk_step = 0;
	private $parent_data = false;
	private $o = array();

	function __construct( $category_table = false, $arg = array() )
	{
		parent::__construct();
		$this->category_table = $category_table;
		$this->o = $arg;
  }

	public function getTermList()
	{
		$q = "SELECT
		cl.*
		FROM ".self::DB_LIST." as cl
		ORDER BY cl.neve ASC
		";

		$data = $this->db->query($q)->fetchAll(\PDO::FETCH_ASSOC);

		return $data;
	}

	public function getList($key)
	{
		$q = "SELECT
		cl.*
		FROM ".self::DB_LIST." as cl
		WHERE 1=1
		";

		if (is_numeric($key)) {
			$q .= " and cl.ID = {$key}";
		} else {
			$q .= " and cl.termkey = '{$key}'";
		}

		$data = $this->db->query($q)->fetch(\PDO::FETCH_ASSOC);
		return $data;
	}

	public function addList( $data = array() )
	{
		$name = ($data['neve']) ?: false;
		$description = ($data['description']) ?: NULL;
		$termkey = ($data['termkey']) ?: \Helper::makeSafeUrl($name);

		$termkey = str_replace(array('-',' '), array('_', '_'), $termkey);

		if ( !$name ) {
			$this->error( "Kérjük, hogy adja meg a tematikus lista elnevezését!" );
		}

		if ( !$termkey ) {
			$this->error( "Kérjük, hogy adja meg a tematikus lista egyedi azonosító kulcsát!" );
		}
		// Termkey check
		$hasterm = (int)$this->db->query("SELECT 1 FROM ".self::DB_LIST." WHERE termkey = '{$termkey}'")->fetchColumn();

		if ( $hasterm == 1 ) {
			$this->error( "Ilyen egyedi azonosító kulccsal már létrehoztak egy tematikus listát." );
		}

		$this->db->insert(
			self::DB_LIST,
			array(
				'neve' 		=> $name,
				'description' 	=> $description,
				'termkey' 	=> $termkey
			)
		);
	}

	public function editList( $data = array() )
	{
		$id = $data['id'];
		$name = ($data['neve']) ?: false;
		$description = ($data['description']) ?: NULL;
		$termkey = ($data['termkey']) ?: \Helper::makeSafeUrl($name);

		if ( !$name ) {
			$this->error( "Kérjük, hogy adja meg a tematikus lista elnevezését!" );
		}

		if ( !$termkey ) {
			$this->error( "Kérjük, hogy adja meg a tematikus lista egyedi azonosító kulcsát!" );
		}
		// Termkey check
		$hasterm = (int)$this->db->query("SELECT 1 FROM ".self::DB_LIST." WHERE termkey = '{$termkey}' and ID != {$id}")->fetchColumn();

		if ( $hasterm == 1 ) {
			$this->error( "Ilyen egyedi azonosító kulccsal már létrehoztak egy tematikus listát." );
		}

		$this->db->update(
			self::DB_LIST,
			array(
				'neve' 		=> $name,
				'description' 	=> $description,
				'termkey' 	=> $termkey
			),
			sprintf("ID = %d", $id)
		);
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

	/**
	 * Kategória fa kilistázása
	 * @param int $top_category_id Felső kategória ID meghatározása, nem kötelező. Ha nincs megadva, akkor
	 * a teljes kategória fa listázódik.
	 * @return array Kategóriák
	 */
	public function getTree( $groupkey, $top_category_id = false )
	{
		$tree = array();

		if ( $top_category_id ) {
			$this->parent_data = $this->db->query( sprintf("SELECT * FROM ".self::DBTERMS." WHERE groupkey = %s and id = %d", $groupkey, $top_category_id) )->fetch(\PDO::FETCH_ASSOC);
		}

		// Legfelső színtű kategóriák
		$qry = "
			SELECT 			*
			FROM 			".self::DBTERMS."
			WHERE 		1=1 and groupkey = '{$groupkey}' ";

		if ( !$top_category_id ) {
			$qry .= " and szulo_id IS NULL ";
		} else {
			$qry .= " and szulo_id = ".$top_category_id;
		}

		if( !$this->o['orderby'] ) {
			$qry .= "
				ORDER BY 		sorrend ASC, neve ASC, id ASC;";
		} else {
			$qry .= "
				ORDER BY 		".$this->o['orderby']." ".$this->o['order'].";";
		}

		$top_cat_qry 	= $this->db->query($qry);
		$top_cat_data 	= $top_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

		if( $top_cat_qry->rowCount() == 0 ) return $this;

		foreach ( $top_cat_data as $top_cat ) {
			$this->tree_items++;

			// Kapcsolódó elemek száma
			//$top_cat['items'] = $this->calcItemNumbers( $top_cat );

			$this->tree_steped_item[] = $top_cat;

			// Alkategóriák betöltése
			$top_cat['child'] = $this->getChildCategories($groupkey, $top_cat['id']);
			$tree[] = $top_cat;
		}

		$this->tree = $tree;

		return $this;
	}

	/**
	 * Végigjárja az összes kategóriát, amit betöltöttünk a getFree() függvény segítségével. while php függvénnyel
	 * járjuk végig. A while függvényen belül használjuk a the_cat() objektum függvényt, ami az aktuális kategória
	 * adataiat tartalmazza tömbbe sorolva.
	 * @return boolean
	 */
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

	public function getParentData( $field = false )
	{
		if ( $field ) {
			return $this->parent_data[$field];
		} else
		return $this->parent_data;
	}

	/**
	 * Kategória alkategóriáinak listázása
	 * @param  int $parent_id 	Szülő kategória ID
	 * @return array 			Szülő kategória alkategóriái
	 */
	public function getChildCategories( $groupkey, $parent_id )
	{
		$tree = array();

		// Gyerek kategóriák
		$q = "
		SELECT 			*
		FROM 			".self::DBTERMS."
		WHERE 		groupkey = '%s' and szulo_id = %d";

		if( !$this->o['orderby'] ) {
			$q .= "
				ORDER BY 		sorrend ASC, id ASC;";
		} else {
			$q .= "
				ORDER BY 		".$this->o['orderby']." ".$this->o['order'].";";
		}

		$child_cat_qry 	= $this->db->query( sprintf( $q, $groupkey, $parent_id ) );
		$child_cat_data	= $child_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

		if( $child_cat_qry->rowCount() == 0 ) return false;
		foreach ( $child_cat_data as $child_cat ) {
			$this->tree_items++;

			$child_cat['link'] = DOMAIN.'termekek/'.\PortalManager\Formater::makeSafeUrl($child_cat['neve'],'_-'.$child_cat['id']);
			// Kapcsolódó elemek száma
			//$child_cat['items'] = $this->calcItemNumbers($child_cat);

			$this->tree_steped_item[] = $child_cat;

			$child_cat['child'] = $this->getChildCategories($groupkey, $child_cat['id']);
			$tree[] = $child_cat;
		}

		return $tree;

	}

	public function getChildIDS($groupkey, $parent_id)
	{
		$ids = array();

		// Gyerek kategóriák
		$q = "
		SELECT 			id
		FROM 			".self::DBTERMS."
		WHERE 		groupkey = %s and szulo_id = %d ";

		$q .= " ORDER BY sorrend ASC, id ASC;";

		$child_cat_qry 	= $this->db->query( sprintf( $q, $groupkey, $parent_id ) );
		$child_cat_data	= $child_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

		if( $child_cat_qry->rowCount() == 0 ) return $ids;

		foreach ( $child_cat_data as $child_cat ) {
			$ids[] = $child_cat['id'];
		}

		return $ids;
	}

	public function getCategoryParentRow( $id, $groupkey, $return_row = 'id', $deep_allow_under = 0 )
	{
		$row = array();

		$has_parent = true;

		$limit = 10;

		$sid = $id;

		while( $has_parent && $limit > 0 ) {
			$q 		= "SELECT ".$return_row.", szulo_id, deep FROM ".self::DBTERMS." WHERE groupkey = '{$groupkey}' and id = ".$sid.";";
			$qry 	= $this->db->query($q);
			$data 	= $qry->fetch(\PDO::FETCH_ASSOC);

			$sid = $data['szulo_id'];

			if( is_null( $data['szulo_id'] ) ) {
				$has_parent = false;
			}

			if( (int)$data['deep'] >= $deep_allow_under ) {
				$row[] = $data[$return_row];
			}

			$limit--;
		}

		return $row;
	}

	/*===============================
	=            GETTERS            =
	===============================*/

	public function getID()
	{
		return $this->current_category['id'];
	}

	public function getName()
	{
		return $this->current_category['neve'];
	}

	public function getDeep()
	{
		return $this->current_category['deep'];
	}
	public function getLangKey()
	{
		return $this->current_category['langkey'];
	}

	public function getParentID()
	{
		return  $this->current_category['szulo_id'];
	}

	public function getParentKey()
	{
		return $this->current_category['id'].'_'.$this->current_category['deep'];
	}

	public function getSortIndex()
	{
		return $this->current_category['sorrend'];
	}

	public function getSlug()
	{
		return $this->current_category['slug'];
	}

	public function isTop()
	{
		return ( (int) $this->current_category['deep'] === 0 ? true : false );
	}

	public function getItemNumbers()
	{
		return $this->current_category['items'];
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

	private function calcItemNumbers( $item_object )
	{
		$num = 0;

		switch ( $this->category_table ) {
			case self::TYPE_TERULETEK:
				if( true ) {

					$set = $this->getChildIDS( $item_object['id'] );
					$set[] = $item_object['id'];

					if( $set ) {

						$set = implode(",", $set);

						switch ( $this->o['nums_for']) {
							case 'users':
								$q = "SELECT count(u.ID) FROM ".\PortalManager\Users::TABLE_NAME." as u WHERE 1=1 ";

								if( isset($this->o['user_group']) ) {
									$q .= sprintf( " and u.user_group = '%s' ", $this->o['user_group'] );
								}

								$q .= " and u.engedelyezve = 1 and u.aktivalva IS NOT NULL ";

								$q .= " and (SELECT ertek FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE fiok_id = u.ID and nev = 'city') IN(".$set.") ";


								$qry = $this->db->query( $q );

								$num = $qry->fetchColumn();
							break;
							case 'ad':default:

								$q = "SELECT count(id) FROM hirdetmenyek WHERE  1=1 ";

								if( isset($this->o['calc_item_type']) ) {
									$q .= sprintf( " and tipus = '%s' ", $this->o['calc_item_type'] );
								}

								$q .= " and active = 1 and now() > feladas_ido and now() < lejarat_ido  and terulet_id IN (".$set.");";

								$qry = $this->db->query( $q );

								$num = $qry->fetchColumn();

							break;
						}

					}

				}
			break;
			case self::TYPE_MUNKAKOROK:
				if( true ) {

					$set = $this->getChildIDS( $item_object['id'] );
					$set[] = $item_object['id'];

					if( $set ) {
						$set = implode(",", $set);

						$q = "SELECT count(id) FROM hirdetmenyek WHERE 1=1 ";

						if( isset($this->o['calc_item_type']) ) {
							$q .= sprintf( " and tipus = '%s' ", $this->o['calc_item_type'] );
						}

						$q .= " and active = 1 and now() > feladas_ido and now() < lejarat_ido  and jobmode_id IN (".$set.");";

						$qry = $this->db->query( $q );

						$num = $qry->fetchColumn();
					}

				}
			break;
			case self::TYPE_MUNKATIPUS:
				if( true ) {

					$set = $this->getChildIDS( $item_object['id'] );
					$set[] = $item_object['id'];

					if( $set ) {
						$set = implode(",", $set);

						$q = "SELECT count(id) FROM hirdetmenyek WHERE  1=1 ";

						if( isset($this->o['calc_item_type']) ) {
							$q .= sprintf( " and tipus = '%s' ", $this->o['calc_item_type'] );
						}

						$q .= " and active = 1 and now() > feladas_ido and now() < lejarat_ido  and jobtype_id IN (".$set.");";

						$qry = $this->db->query( $q );

						$num = $qry->fetchColumn();
					}

				}
			break;
			case self::TYPE_STUDIES:
				if( true ) {

					$set = $this->getChildIDS( $item_object['id'] );
					$set[] = $item_object['id'];

					if( $set ) {
						$set = implode(",", $set);

						$q = "SELECT count(id) FROM hirdetmenyek WHERE  1=1 ";

						if( isset($this->o['calc_item_type']) ) {
							$q .= sprintf( " and tipus = '%s' ", $this->o['calc_item_type'] );
						}

						$q .= " and active = 1 and now() > feladas_ido and now() < lejarat_ido  and jobmode_id IN (".$set.");";

						$qry = $this->db->query( $q );

						$num = $qry->fetchColumn();
					}

				}
			break;
		}


		return $num;
	}

	public function __destruct()
	{
		//echo ' -DEST- ';
		$this->db = null;
		$this->tree = false;
		$this->current_category = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->walk_step = 0;
		$this->parent_data = false;
		$this->o = null;
	}
}
?>
