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

    static public function getCurrentLang()
    {
        $lang = 'hu';

        if( isset( $_COOKIE['language'] ) ) {
            $lang = $_COOKIE['language'];
        }

        return $lang;
    }

	public function loadTexts() {
		$code = $this->getCurrentLang();
		//$code = 'en';
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
		
		$created = (int)$this->db->query("SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' and TABLE_NAME = '".self::DB_TABLE_TRANSLATES."'")->fetchColumn();
		
		if( $created === 0 ) 
		{
			$qry = array();			
			$qry[] = "CREATE TABLE IF NOT EXISTS `".self::DB_TABLE_TRANSLATES."` (
			  `ID` int(11) NOT NULL,
			  `lang` varchar(5) DEFAULT NULL,
			  `groupkey` varchar(50) NOT NULL DEFAULT 'global',
			  `srcstr` text,
			  `origin_id` int(11) DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			
			$qry[] = "ALTER TABLE `".self::DB_TABLE_TRANSLATES."`
			  ADD PRIMARY KEY (`ID`),
			  ADD KEY `lang` (`lang`),
			  ADD KEY `origin_id` (`origin_id`),
			  ADD KEY `groupkey` (`groupkey`);
			ALTER TABLE `language_texts` ADD FULLTEXT KEY `srcstr` (`srcstr`);";
			  
			$qry[] = "ALTER TABLE `".self::DB_TABLE_TRANSLATES."`
				MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";
				
			
				
			foreach($qry as $q) {
				$this->db->query($q);
			}
		}
		$created = false;
		
		$created = (int)$this->db->query("SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' and TABLE_NAME = '".self::DB_TABLE_LANGUAGES."'")->fetchColumn();
		
		if( $created === 0 ) 
		{
			$qry = array();			
			$qry[] = "CREATE TABLE IF NOT EXISTS  `".self::DB_TABLE_LANGUAGES."` (
				`ID` smallint(6) NOT NULL,
				`code` varchar(3) NOT NULL DEFAULT 'hu',
				`default_lang` tinyint(1) NOT NULL DEFAULT '0',
				`active` tinyint(1) NOT NULL DEFAULT '1'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			
			$qry[] = "ALTER TABLE `".self::DB_TABLE_LANGUAGES."`
			    ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `code` (`code`)";
			  
			$qry[] = "ALTER TABLE `".self::DB_TABLE_LANGUAGES."`
			  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			  
			$qry[] = "INSERT INTO `".self::DB_TABLE_LANGUAGES."` 
				(`ID`, `code`, `default_lang`, `active`) VALUES
				(null, 'hu', 1, 1);";
				
			foreach($qry as $q) {
				$this->db->query($q);
			}
		}
		
	}

    public function __destruct()
    {
		
    }
}
?>
