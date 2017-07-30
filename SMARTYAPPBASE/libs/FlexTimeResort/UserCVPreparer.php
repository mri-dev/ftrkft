<?php
namespace FlexTimeResort;

use PortalManager\Categories;

class UserCVPreparer
{
  private $db = null;
	public $controller = null;
	public $smarty = null;
  public $user = null;

  public function __construct( \PortalManager\User $user = null, $arg = array() )
  {
    if ($user) {
      $this->user = $user;
    }

    if(isset($arg['controller'])) {
			$this->controller = $arg['controller'];
			$this->db = $this->controller->db;
			$this->settings = $this->controller->settings;
			$this->smarty = $this->controller->smarty;
		}

    $this->loadModulDatas();

		return $this;
  }

  public function ProfilImg()
  {
    return $this->user->getProfilImg();
  }

  public function Name()
  {
    return $this->user->getValue('name');
  }

  public function Email()
  {
    return $this->user->getValue('email');
  }

  public function Phone()
  {
    $v = $this->user->getValue('telefon');

    if (empty($v)) {
      return false;
    }

    return $v;
  }

  public function BirthDate()
  {
    $v = $this->user->getValue('szuletesi_datum');

    if (empty($v)) {
      return false;
    }

    return $v;
  }

  public function Address()
  {
    $irsz = $this->user->getValue('lakcim_irsz');
    $city = $this->user->getValue('lakcim_city');
    $uhsz = $this->user->getValue('lakcim_uhsz');

    if (empty($irsz) && empty($city) && empty($uhsz)) {
      return false;
    }

    $addr = '';

		if (!empty($irsz)) {
			$addr .= $irsz." ";
		}

		if (!empty($city)) {
			$addr .= $city;
		}

		if (!empty($uhsz)) {
			$addr .= ", ".$uhsz;
		}

    return $addr;
  }

  public function Social($network)
  {
    $v = $this->user->getValue('social_url_'.$network);

    if (empty($v)) {
      return false;
    }

    return $v;
  }

  public function getTermValues($term, $values)
  {
    if (is_array($values)) {
      $where = " and ID IN(".implode($values,',').")";
    } else {
      $where = " and ID = '".$values."'";
    }
    $data = $this->db->query($iq = "SELECT ID, neve, langkey FROM terms WHERE groupkey = '".$term."'".$where)->fetchAll(\PDO::FETCH_ASSOC);

    if (count($data) == 1) {
      $text = $data[0];
    } elseif(count($data) > 1) {
      $text = array();
      foreach ((array)$data as $d) {
        $text[$d['ID']] = $d;
      }
    }

    return $text;
  }

  public function loadModulDatas()
  {
    $this->moduldatas = $this->user->getAccountModulData();

    return $this;
  }

  public function getModul($page, $group)
  {
    return $this->prepareModulForOutput($group, $this->moduldatas[$page][$group]);
  }

  private function prepareModulForOutput($group, $list = array())
  {
    $output = array();
    $term_groups = array(
      'vegzettseg_szint' => 'iskolai_vegzettsegi_szintek',
      'szakirany' => 'tanulmany_szakirany'
    );

    switch ($group) {
      case 'vegzettseg':
        foreach ((array)$list as $index => $l) {
          foreach ($l as $key => $value) {
            switch ($key) {
              case 'vegzettseg_szint':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
              break;
              case 'szakirany':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
              break;

            }

            $output[$index][$key] = $value;
          }

        }
      break;
    }

    return $output;
  }

  public function __destruct()
	{
		$this->db = null;
		$this->smarty = null;
		$this->user = null;
	}
}
?>
