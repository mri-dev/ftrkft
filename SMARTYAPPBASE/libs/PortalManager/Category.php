<?
namespace PortalManager;

use ExceptionManager\RedirectException;

class Category extends \Controller
{
	public $db = null;
	private $id = false;
	private $cat_data = false;
	private $groupkey = false;
	public $settings = null;

	function __construct( $groupkey = false, $category_id = false, $arg = array() )
	{
		parent::__construct();
		$this->id = $category_id;
		$this->groupkey = $groupkey;

		$this->get();

		return $this;
	}

	/**
	 * Kategória adatainak lekérése
	 * @return void
	 */
	private function get()
	{
		if(!$this->id) return false;

		$q = sprintf("
			SELECT 			*
			FROM 			".\PortalManager\Categories::DBTERMS."
			WHERE 			1=1 and groupkey = '%s' and id = %d;", $this->groupkey, $this->id);

		$cat_qry 	= $this->db->query( $q );
		$cat_data = $cat_qry->fetch(\PDO::FETCH_ASSOC);
		$this->cat_data = $cat_data;
	}

	/**
	 * Kategória létrehzás
	 * @param array $data új kategória létrehozásához szükséges adatok
	 * @return int inserted ID
	 */
	public function add( $data = array() )
	{
		$deep = 0;
		$relations 	= 0;

		$name 		= ($data['nev']) 		?: false;
		$sort 		= ($data['sorrend']) 	?: 100;
		$parent 	= ($data['parent']) 	?: NULL;
		$slug 		= ($data['slug']) 	?: NULL;
		$langkey 	= ($data['langkey']) 	?: false;
		$groupkey = $data['groupkey'];

		if (!$langkey) {
			$langkey = $data['langkeyprefix'].str_replace('-','_', \Helper::makeSafeUrl( $name, '', false));
		} else {
			$langkey = $data['langkeyprefix'].$langkey;
		}

		$langkey = strtoupper($langkey);

		if ($parent) {
			$xparent 	= explode('_',$parent);
			$parent 	= (int)$xparent[0];
			$deep 		= (int)$xparent[1] + 1;
		}

		if (!$slug) {
			$slug = \Helper::makeSafeUrl($data['nev'],'',false);
		}

		if ( !$name ) {
			$this->error( "Kérjük, hogy adja meg az elem értékét!" );
		}

		if ( !$groupkey || empty($groupkey) ) {
			$this->error( "Az elem nem létrehozható. Hiányzik a tematikus lista azonosítója. Pótolja, vagy ha ismeretlen hiba, akkor jelezze a fejlesztő felé." );
		}

		$this->db->insert(
			\PortalManager\Categories::DBTERMS,
			array(
				'neve' 		=> $name,
				'szulo_id' 	=> $parent,
				'sorrend' 	=> $sort,
				'deep' 		=> $deep,
				'langkey' => $langkey,
				'groupkey' => $groupkey,
				'slug' 		=> $slug
			)
		);


		$id = $this->db->lastInsertId();

		return $id;
		/*
		$relations = '';

		if( $this->groupkey == \PortalManager\Categories::TYPE_TERULETEK ) {
			$relations .= $this->settings['country_id'];
		}

		$relations .= '_'.$parent;
		$relations .= '_'.$id;

		$relations = trim($relations, '_');

		$this->db->update(
			$this->groupkey,
			array(
				'relations' => $relations
			),
			"ID = ".$id
		);*/
	}

	/**
	 * Beszúrandó kategória ellenőrzése, hogy létezik-e már!
	 * @param  array $data( neve, szulo_id) kategória adatok
	 * @return boolean | int - elem id
	 */
	public function checkExists( $data  )
	{
		$deep 		= 0;
		$name 		= ( $data['neve'] ) 		? $data['neve'] 	: false;
		$parent 	= ( $data['szulo_id'] ) 	? $data['szulo_id'] : NULL;

		if ( $parent ) {
			$xparent 	= explode('_',$parent);
			$parent 	= (int)$xparent[0];
			$deep 		= (int)$xparent[1] + 1;
		} else {
			$parent = 'NULL';
		}

		$q = "SELECT id FROM ".$this->groupkey." WHERE szulo_id = $parent and deep = $deep and neve = '$name';";

		$check = $this->db->query($q);


		if( $check->rowCount() != 0 ){
			$id = $check->fetchColumn();
			return $id;
		}

		return false;
	}

	/**
	 * Kategória keresés adatok alapján
	 * @param  array $data
	 * @return array|boolean
	 */
	public function checkData( $data )
	{
		$details = array();

		if( empty( $data ) ) return false;

		$q = "SELECT * FROM ".$this->groupkey." WHERE 1 = 1 ";

		foreach ($data as $key => $value) {
			$q .= " and ".$key. " = '".addslashes($value)."' ";
		}

		$qry = $this->db->query( $q );

		if( $qry->rowCount() == 0) return false;

		$details = $qry->fetch(\PDO::FETCH_ASSOC);

		return $details;
	}

	/**
	 * Aktuális kategória adatainak szerkesztése / mentése
	 * @param  array $db_fields új kategória adatok
	 * @return void
	 */
	public function edit( $db_fields )
	{
		$deep = 0;
		$relations 	= '';
		$update = array();
		$id = $db_fields['id'];

		$parent = ($db_fields['szulo_id']) ?: NULL;
		$langkey = ($db_fields['langkey']) 	?: false;
		$slug = ($db_fields['slug']) 	?: NULL;

		if (!$langkey) {
			$langkey = $db_fields['langkeyprefix'].str_replace('-','_', \Helper::makeSafeUrl( $db_fields['nev'], '', false));
		} else {
			$langkey = $db_fields['langkeyprefix'].$langkey;
		}

		if (!$slug) {
			$slug = \Helper::makeSafeUrl($db_fields['nev'],'',false);
		}

		$langkey = strtoupper($langkey);

		if ($parent) {
			$xparent 	= explode('_',$parent);
			$parent 	= (int)$xparent[0];
			$deep 		= (int)$xparent[1] + 1;
		}

		if( empty( $db_fields['nev'] ) )  {
			$this->error( "Elem nevének megadása kötelező!" );
		}

		$update['neve'] 	= $db_fields['nev'];
		$update['sorrend'] 	= ( !empty($db_fields['sorrend']) ) ? (int)$db_fields['sorrend'] : 100;
		$update['szulo_id'] 	= $parent;
		$update['langkey'] 	= $langkey;
		$update['deep'] 		= $deep;
		$update['slug'] 		= $slug;
		//$db_fields['relations']	= $relations;



		$this->db->update(
			\PortalManager\Categories::DBTERMS,
			$update,
			"id = ".$id
		);
	}

	/**
	 * Aktuális kategória törlése
	 * @return void
	 */
	public function delete($id)
	{
		$this->db->query(sprintf("DELETE FROM ".\PortalManager\Categories::DBTERMS." WHERE ID = %d", $id));
	}

	private function error( $msg )
	{
		throw new RedirectException( $msg, $_POST['form'], $_POST['return'], $_POST['session_path'] );
	}

	private function kill( $msg = '' )
	{
		throw new \Exception( $msg );
	}

	/*===============================
	=            GETTERS            =
	===============================*/
	public function getName()
	{
		return $this->cat_data['neve'];
	}

	public function getLangKey()
	{
		return $this->cat_data['langkey'];
	}

	public function getSortNumber()
	{
		return $this->cat_data['sorrend'];
	}
	public function getParentKey()
	{
		return $this->cat_data['szulo_id'].'_'.($this->cat_data['deep']-1);
	}
	public function getParentId()
	{
		return $this->cat_data['szulo_id'];
	}
	public function getSlug()
	{
		return $this->cat_data['slug'];
	}
	public function getDeep()
	{
		return $this->cat_data['deep'];
	}
	public function getId()
	{
		return $this->cat_data['id'];
	}
	/*-----  End of GETTERS  ------*/

	public function __destruct()
	{
		$this->db = null;
		$this->cat_data = false;
		$this->settings = null;
	}

}
?>
