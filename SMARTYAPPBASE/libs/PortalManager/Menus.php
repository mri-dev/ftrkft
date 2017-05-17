<?
namespace PortalManager;

use ExceptionManager\RedirectException;

/**
* class Menus
* @package PortalManager
* @version v1.0
*/
class Menus extends \Controller
{
	private $selected_menu_id = false;
	// Engedélyezett menü pozíciók
	private $allowed_positions = array( 'header', 'footer' );
	// Elérhető menü típusok
	private $allowed_menu_type = array(
		'url' => 'URL'
	);
	public $tree = false;
	private $current_item = false;
	private $current_get_item = false;
	private $tree_steped_item = false;
	private $tree_items = 0;
	private $walk_step = 0;
	private $final = false;

	public $filters = array();

	function __construct( $menu_id = false, $arg = array() )
	{
		parent::__construct();

		$this->selected_menu_id = $menu_id;

		return $this;
	}

	public function isFinal( $flag )
	{
		$this->final = $flag;
	}

	public function reset()
	{
		$this->filters = array();
		$this->walk_step = 0;
		$this->current_item = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->tree = false;
	}

	public function addFilter( $key, $value )
	{
		$this->filters[$key] = $value;
		return $this;
	}

	public function add( $data )
	{
		$deep 		= 0;
		$elem_id 	= NULL;

		$pos 	= ($data['menu_pos']) ?: false;
		$parent = ($data['parent']) ?: false;
		$type 	= ($data['menu_type']) ?: false;
		$sort 	= ($data['sorrend']) ?: 0;
		$css_class = ($data['css_class']) ?: NULL;
		$css_style = ($data['css_styles']) ?: NULL;
		$url 	= ($data['url']) ?: NULL;
		$felirat= ($data['nev']) ?: NULL;
		$lathato= ($data['lathato'] == 'on') ? 1 : 0;
		$kep 	= ($data['url_img']) ?: NULL;
		$datavalue = NULL;

		if ($parent) {
			$xparent = explode('_',$parent);
			$deep = $xparent[1]+1;
			$parent = $xparent[0];
		} else {
			$parent = NULL;
		}

		if (!$pos) { $this->error("Kérjük, hogy válassza ki a Menü pozícióját a listából!"); }
		if (!$type) { $this->error("Kérjük, hogy válassza ki a Menü típusát a listából!"); }

		$this->db->insert(
			"menu",
			array(
				'tipus' => $type,
				'elem_id' => $elem_id,
				'gyujto' => $pos,
				'szulo_id' => $parent,
				'nev' => $felirat,
				'url' => $url,
				'sorrend' => $sort,
				'deep' => $deep,
				'css_class' => $css_class,
				'css_styles' => $css_style,
				'kep' => $kep,
				'data_value' => $datavalue,
				'lathato' => $lathato
			)
		);
	}

	public function save( $data )
	{
		$deep 		= 0;
		$elem_id 	= NULL;

		$pos 	= ($data['menu_pos']) ?: false;
		$parent = ($data['parent']) ?: false;
		$type 	= ($data['menu_type']) ?: false;
		$css_class = ($data['css_class']) ?: NULL;
		$css_style = ($data['css_styles']) ?: NULL;
		$sort 	= ($data['sorrend']) ?: 0;
		$lathato= ($data['lathato'] == 'on') ? 1 : 0;
		$url 	= ($data['url']) ?: NULL;
		$felirat= ($data['nev']) ?: NULL;
		$kep 	= ($data['url_img']) ?: NULL;
		$datavalue = NULL;

		if ($parent) {
			$xparent = explode('_',$parent);
			$deep = $xparent[1]+1;
			$parent = $xparent[0];
		} else {
			$parent = NULL;
		}

		if (!$pos) { $this->error("Kérjük, hogy válassza ki a Menü pozícióját a listából!"); }
		if (!$type) { $this->error("Kérjük, hogy válassza ki a Menü típusát a listából!"); }

		$this->db->update(
			"menu",
			array(
				'tipus' => $type,
				'elem_id' => $elem_id,
				'gyujto' => $pos,
				'szulo_id' => $parent,
				'nev' => addslashes($felirat),
				'url' => $url,
				'sorrend' => $sort,
				'deep' => $deep,
				'css_class' => $css_class,
				'css_styles' => $css_style,
				'kep' => $kep,
				'data_value' => $datavalue,
				'lathato' => $lathato
			),
			sprintf("ID = %d", $this->selected_menu_id)
		);
	}

	public function delete( $id = false )
	{
		$del_id = ($id) ?: $this->selected_menu_id;

		if ( !$del_id ) return false;

		$del_qry = sprintf("DELETE FROM menu WHERE ID = %d", $del_id);

		//echo $del_qry ."<br>";
		$this->db->query( $del_qry );
	}


	public function get( $menu_id )
	{
		$data = array();
		$qry = $this->db->query(sprintf("
			SELECT 				*
			FROM 				menu
			WHERE 			ID = %d",$menu_id));

		$this->current_get_item = $qry->fetch(\PDO::FETCH_ASSOC);

		return $this;
	}


	/**
	 * Menü fa kilistázása
	 * @param int $top_menu_id Felső menü ID meghatározása, nem kötelező. Ha nincs megadva, akkor
	 * a teljes menü fa listázódik.
	 * @return array Menü fa
	 */
	public function getTree( $top_menu_id = false, $arg = array() )
	{
		$tree 		= array();

		// Legfelső színtű menü
		$qry = "
			SELECT 			*
			FROM 			menu
			WHERE 		ID IS NOT NULL ";

		if ( !$top_menu_id ) {
			$qry .= " and szulo_id IS NULL ";
		} else {
			$qry .= " and szulo_id = ".$top_menu_id;
		}

		if ( !$arg['admin'] ) {
			$qry .= " and lathato = 1 ";
		}

		// Filters
		if( $this->filters['menu_type'] ){
			$qry .= sprintf(" and gyujto = '%s'", $this->filters['menu_type']);
		}

		$qry .= "
			ORDER BY 		gyujto DESC, sorrend ASC, ID ASC;";

		$top_menu_qry 	= $this->db->query($qry);
		$top_menu_data 	= $top_menu_qry->fetchAll(\PDO::FETCH_ASSOC);

		if( $top_menu_qry->rowCount() == 0 ) return $this;

		foreach ( $top_menu_data as $top_menu ) {
			$this->tree_items++;

			$top_menu = $this->itemTypeAction( $top_menu['tipus'], $top_menu );

			$this->tree_steped_item[] = $top_menu;

			// Alkategóriák betöltése
			$top_menu['child'] = $this->getChildItems($top_menu['ID']);

			$tree[] = $top_menu;
		}

		$this->tree = $tree;

		return $this;
	}

	public function has_menu()
	{
		return ($this->tree_items === 0) ? false : true;
	}

	/**
	 * Végigjárja az összes menüt, amit betöltöttünk a getFree() függvény segítségével. while php függvénnyel
	 * járjuk végig. A while függvényen belül használjuk a the_menu() objektum függvényt, ami az aktuális menü
	 * adataiat tartalmazza tömbbe sorolva.
	 * @return boolean
	 */
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

	public function getWalkInfo()
	{
		return array(
			'walk_step' => $this->walk_step,
			'tree_steped_item' => $this->tree_steped_item,
			'tree_items' => $this->tree_items,
			'current_item' => $this->current_item,
		);
	}

	/**
	 * A walk() fgv-en belül visszakaphatjuk az aktuális menü elem adatait tömbbe tárolva.
	 * @return array
	 */
	public function the_menu()
	{
		return $this->current_item;
	}

	public function the_menu_type()
	{
		$item = $this->current_item;

		return array(
			'type' => $item['tipus'],
			'text' => $this->allowed_menu_type[$item['tipus']],
		);
	}

	private function itemTypeAction( $type, $item )
	{
		switch ( $type ) {
			case 'oldal_link':
				$oldal = $this->db->query(sprintf("
					SELECT 				o.cim, o.eleres
					FROM 				oldalak as o
					WHERE 				o.ID = %d",$item['elem_id']))->fetch(\PDO::FETCH_ASSOC);

				if( $this->final ) {
					$item['nev'] 	= ($item['nev'] ?: $oldal['cim']);
					$item['link'] 	= DOMAIN.'p/'.$oldal['eleres'];
				} else {
					$item['nev'] = ($item['nev'] ?: $oldal['cim']).' <span class="menu-type-prefix">(Kiválasztott oldal: <a title="oldal szerkesztése" href=\'/oldalak/szerkesz/'.$item['elem_id'].'\'>'.$oldal['cim'].'</a>)</span>';
				}

				break;
			default:
				$item['link'] 	= $item['url'];
				break;
		}

		return $item;
	}

	/**
	 * Menü al-elemeinek listázása
	 * @param  int $parent_id 	Szülő menü ID
	 * @return array 			Szülő menü al-elemei
	 */
	private function getChildItems( $parent_id, $deep = true )
	{
		$tree = array();

		// Gyerek menük
		$child_menu_qry 	= $this->db->query( sprintf("
			SELECT 			*
			FROM 			menu
			WHERE 			szulo_id = %d
			ORDER BY 		gyujto DESC, sorrend ASC, ID ASC;", $parent_id));
		$child_menu_data	= $child_menu_qry->fetchAll(\PDO::FETCH_ASSOC);

		if( $child_menu_qry->rowCount() == 0 ) return false;
		foreach ( $child_menu_data as $child_menu ) {
			$this->tree_items++;

			$child_menu = $this->itemTypeAction( $child_menu['tipus'], $child_menu );

			$this->tree_steped_item[] = $child_menu;

			if( $deep ) {
				$child_menu['child'] = $this->getChildItems($child_menu['ID']);
			}

			$tree[] = $child_menu;
		}

		return $tree;
	}

	/*===============================
	=            GETTERS            =
	===============================*/
	public function getPositionList()
	{
		return $this->allowed_positions;
	}
	public function getTypes()
	{
		return $this->allowed_menu_type;
	}
	public function getPosition()
	{
		return $this->current_get_item['gyujto'];
	}
	public function isVisible()
	{
		return ($this->current_get_item['lathato'] == '1') ? true : false;
	}
	public function getType()
	{
		return $this->current_get_item['tipus'];
	}
	public function getParentId()
	{
		return $this->current_get_item['szulo_id'];
	}
	public function getParentKey()
	{
		return $this->current_get_item['szulo_id'].'_'.($this->current_get_item['deep']-1);
	}
	public function getDeepIndex()
	{
		return $this->current_get_item['deep'];
	}
	public function getId()
	{
		return $this->current_get_item['ID'];
	}
	public function getElemId()
	{
		return $this->current_get_item['elem_id'];
	}
	public function getTitle()
	{
		return $this->current_get_item['nev'];
	}
	public function getUrl()
	{
		return $this->current_get_item['url'];
	}
	public function getValue()
	{
		return $this->current_get_item['data_value'];
	}
	public function getSortNumber()
	{
		return $this->current_get_item['sorrend'];
	}
	public function getCssClass()
	{
		return $this->current_get_item['css_class'];
	}
	public function getCssStyle()
	{
		return $this->current_get_item['css_styles'];
	}
	public function getImage()
	{
		return $this->current_get_item['kep'];
	}
	/*-----  End of GETTERS  ------*/

	private function error( $msg )
	{
		throw new RedirectException( $msg, $_POST['form'], $_POST['return'], $_POST['session_path'] );
	}

	public function __destruct()
	{
		$this->tree = false;
		$this->current_item = false;
		$this->current_get_item = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->walk_step = 0;
		$this->final = false;
	}

}
?>
