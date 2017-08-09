<?
namespace PortalManager;

class Lang {

	const DB_TABLE_TRANSLATES = 'language_texts';
	const DB_TABLE_LANGUAGES = 'languages';
	private $settings = array();
	private $language = 'hu';
	public $smarty = null;
	public $texts = array();

	public function __construct( $smarty, $arg = array() )
	{
		$this->db = $arg['db'];
		$this->smarty = $smarty;

		$this->install_db();

		$smarty_config = $smarty->getConfigVars();
		$this->settings = $smarty_config;
		$this->language = $smarty_config['default_language'];

    if( isset( $_COOKIE['language'] ) ) {
        $this->language = $_COOKIE['language'];
    }
	}

	function switchTo($code = 'hu')
	{
		$avaiable = $this->avaiableLanguages();

		if (in_array($code, $avaiable['avaiable_codes'])) {
			setcookie('language', $code, time()+3600*24*90, '/');
		}
	}

  public function getCurrentLang()
  {
      $lang = 'hu';

      if( isset( $_COOKIE['language'] ) ) {
				$avaiable = $this->avaiableLanguages();
				$clang = $_COOKIE['language'];

				if (in_array($clang, $avaiable['avaiable_codes'])) {
					$lang =  $_COOKIE['language'];
				}
      }

      return $lang;
  }

	public function avaiableLanguages()
	{
		$re = array();
		$langs = $this->db->query("SELECT * FROM ".self::DB_TABLE_LANGUAGES)->fetchAll(\PDO::FETCH_ASSOC);

		foreach ((array)$langs as $l) {
			$re['all'][] = $l;
			if ($l['active'] == 1) {
				$re['avaiable'][$l['code']] = $l;
				$re['avaiable_codes'][] = $l['code'];
			}
		}

		return $re;
	}

	public function isDefaultLangNow()
	{
		$code = $this->getCurrentLang();

		if($this->settings['default_language'] == $code ){
			return true;
		}

		return false;
	}

	public function switchLangActivity($code, $to)
	{
		$this->db->update(
			self::DB_TABLE_LANGUAGES,
			array(
				'active' => (int)$to
			),
			sprintf("code = '%s'", $code)
		);
	}

	public function addLang($code = false, $name = false)
	{
		if (empty($code)) {
			throw new \Exception("Nyelvi azonosító* megadása kötelező!");
		}

		if (empty($name)) {
			throw new \Exception("Nyelv elnevezés megadása kötelező!");
		}

		$check = $this->db->query("SELECT ID FROM ".self::DB_TABLE_LANGUAGES." WHERE code = '{$code}'");

		if ($check->rowCount() != 0) {
			throw new \Exception($code . " azonosítójú nyelv már létezik.");
		}

		$this->db->insert(
			self::DB_TABLE_LANGUAGES,
			array(
				'code' => $code,
				'nametext' => trim($name),
			)
		);
	}

	public function addText($srcstr = '', $textvalue = '')
	{
		if (empty($srcstr)) {
			throw new \Exception("Nyelvi szöveg azonosító kulcs megadása kötelező!");
		}

		if (empty($textvalue)) {
			throw new \Exception("Megjelenő MAGYAR szöveg megadása kötelező!");
		}

		$check = $this->db->query("SELECT ID FROM ".self::DB_TABLE_TRANSLATES." WHERE lang = '{$this->settings['default_language']}' and srcstr = '{$srcstr}'");

		if ($check->rowCount() != 0) {
			throw new \Exception($srcstr . " azonosítójú kulccsal már van egy nyelvi rekord regisztrálva.");
		}

		$this->db->insert(
			self::DB_TABLE_TRANSLATES,
			array(
				'lang' => $this->settings['default_language'],
				'srcstr' => trim($srcstr),
				'textvalue' => trim($textvalue)
			)
		);
	}

	public function saveText($lang = 'hu', $id = 0, $value, $parent = 0)
	{
		if (empty($value)) {
			return false;
		}

		if ($id != 0) {
			$this->db->update(
				self::DB_TABLE_TRANSLATES,
				array(
					'textvalue' => $value
				),
				sprintf("lang = '%s' and ID = %d", $lang, $id)
			);
		} else {
			$this->db->insert(
				self::DB_TABLE_TRANSLATES,
				array(
					'textvalue' => $value,
					'origin_id' => $parent,
					'lang' => $lang
				)
			);
		}

		return true;
	}

	public function prepareForTranslator( $langkey = 'hu' )
	{
		$avaiable = $this->avaiableLanguages();

		if (!in_array($langkey, $avaiable['avaiable_codes'])) {
			$langkey = $this->language;
		}

		$texts = array();

		if($this->settings['default_language'] == $langkey ){
			$q = "SELECT ID, srcstr,textvalue FROM ".self::DB_TABLE_TRANSLATES." WHERE 1=1 ";
			$q .= " and lang = '".$langkey."';";
			$data = $this->db->query($q)->fetchAll(\PDO::FETCH_ASSOC);


			foreach ((array)$data as $d) {
				$texts[$d['ID']] = $d;
			}
		} else {
			$q = "SELECT
				t.ID as parentID,
				t.srcstr,
				t.textvalue as origin_textvalue,
				t2.textvalue,
				t2.ID
			FROM ".self::DB_TABLE_TRANSLATES." as t
			LEFT OUTER JOIN ".self::DB_TABLE_TRANSLATES." as t2 ON t2.origin_id = t.ID and t2.lang = '{$langkey}'
			WHERE 1=1 and t.lang = '{$this->settings['default_language']}'";
			//echo $q.'<br>';
			$data = $this->db->query($q)->fetchAll(\PDO::FETCH_ASSOC);

			foreach ((array)$data as $d) {
				$d['parentID'] = (int)$d['parentID'];
				$texts[] = $d;
			}
		}


		return $texts;
	}

	public function loadTexts() {
		$code = $this->getCurrentLang();
		$texts = array();

		if($this->settings['default_language'] == $code ){
			$q = "SELECT srcstr,textvalue FROM ".self::DB_TABLE_TRANSLATES." WHERE 1=1 ";
			$q .= " and lang = '".$code."';";
			$txts = $this->db->query($q)->fetchAll(\PDO::FETCH_ASSOC);

			foreach($txts as $txt){
				$texts[$txt['srcstr']] = $txt['textvalue'];
			}
		} else {

			$q = "SELECT t.srcstr, IF(t2.ID IS NOT NULL, t2.textvalue, t.textvalue) as textvalue FROM ".self::DB_TABLE_TRANSLATES." as t LEFT OUTER JOIN ".self::DB_TABLE_TRANSLATES." as t2 ON t2.lang = '".$code."' and t2.origin_id = t.ID WHERE 1=1 and t.origin_id IS NULL";
			//echo $q.'<br>';
			$txts = $this->db->query($q)->fetchAll(\PDO::FETCH_ASSOC);

			foreach($txts as $txt){
				$texts[$txt['srcstr']] = $txt['textvalue'];
			}
		}

		$this->texts = $texts;
	}

	private function install_db()
	{
		if($_GET['appinstaller'] != '1') return false;

		//$created = (int)$this->db->query("SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' and TABLE_NAME = '".self::DB_TABLE_TRANSLATES."'")->fetchColumn();
	}

  public function __destruct()
  {

  }
}
?>
