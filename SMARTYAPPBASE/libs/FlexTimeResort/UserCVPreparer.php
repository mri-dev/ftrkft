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

  public function SzakmaText()
  {
    return $this->user->getValue('szakma_text');
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

  public function City()
  {
    $v = $this->user->getValue('lakcim_city');

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

  public function IsmeretekEgyeb()
  {
    $v = $this->user->getValue('ismeretek_egyeb');

    if (empty($v)) {
      return false;
    }

    return $v;
  }

  public function IgenyekEgyeb()
  {
    $v = $this->user->getValue('igenyek_egyeb');

    if (empty($v)) {
      return false;
    }

    return $v;
  }

  public function IgenyekEgyebMunkakorok()
  {
    $v = $this->user->getValue('igenyek_egyeb_munkakorok');

    if (empty($v)) {
      return false;
    }

    return $v;
  }

  public function KulsoOneletrajzUrl()
  {
    $v = $this->user->getValue('kulso_oneletrajz_url');

    if (empty($v)) {
      return false;
    }

    return $v;
  }

  public function Documents()
  {
    return $this->user->getDocuments();
  }

  public function UploadedCV()
  {
    return $this->user->getOneletrajz();
  }

  public function accessGrantedCheck($uid)
  {
    $granted = false;

    return $granted;
  }

  public function getTermValues($term, $values, $cast_multi_arr = false)
  {
    if (is_array($values)) {
      $where = " and ID IN(".implode($values,',').")";
    } else {
      $where = " and ID = '".$values."'";
    }
    $data = $this->db->query($iq = "SELECT ID, neve, langkey, szulo_id FROM terms WHERE groupkey = '".$term."'".$where)->fetchAll(\PDO::FETCH_ASSOC);

    if (count($data) == 1 && !$cast_multi_arr) {
      if(!is_null($data[0]['szulo_id'])) {
        $parent = $this->getTermValues($term, (int)$data[0]['szulo_id']);
      }
      if($parent){
         $data[0]['parent'] = $parent;
      }
      $text = $data[0];
    } elseif(count($data) > 1 || $cast_multi_arr) {
      $text = array();
      foreach ((array)$data as $d) {
        if(!is_null($d['szulo_id'])) {
          $parent = $this->getTermValues($term, (int)$d['szulo_id']);
        }
        if($parent){
           $d['parent'] = $parent;
        }

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
      'szakirany' => 'tanulmany_szakirany',
      'nyelv' => 'nyelvek',
      'szobeli_szint' => 'nyelvismeret',
      'irasbeli_szint' => 'nyelvismeret',
      'tudasszint' => 'tudasszintek',
      'szamitastechnikai_ismeret' => 'szamitastechnikai_ismeretek',
      'munkakor' => 'munkakorok',
      'beosztasi_szint' => 'beosztasi_szint'
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
                if ($term['parent']) {
                  $value['value'] = $term['parent']['neve'] .' / ' . $term['neve'];
                }
              break;

            }

            $output[$index][$key] = $value;
          }

        }
      break;
      case 'kepesitesek':
        foreach ((array)$list as $index => $l) {
          foreach ($l as $key => $value) {
            switch ($key) {
            }

            $output[$index][$key] = $value;
          }

        }
      break;
      case 'nyelvismeret':
        foreach ((array)$list as $index => $l) {
          foreach ($l as $key => $value) {
            switch ($key) {
              case 'nyelv':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
              break;
              case 'szobeli_szint':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
              break;
              case 'irasbeli_szint':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
              break;
            }

            $output[$index][$key] = $value;
          }

        }
      break;
      case 'szamitogepes':
        foreach ((array)$list as $index => $l) {
          foreach ($l as $key => $value) {
            switch ($key) {
              case 'tudasszint':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
              break;
              case 'szamitastechnikai_ismeret':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
                if ($term['parent']) {
                  $value['value'] = $term['parent']['neve'] .' / ' . $term['neve'];
                }
              break;
            }

            $output[$index][$key] = $value;
          }
        }
      break;

      case 'munkatapasztalat':
        foreach ((array)$list as $index => $l) {
          foreach ($l as $key => $value) {
            switch ($key) {
              case 'munkakor':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
                if ($term['parent']) {
                  $value['value'] = $term['parent']['neve'] .' / ' . $term['neve'];
                }
              break;
              case 'beosztasi_szint':
                $term = $this->getTermValues($term_groups[$key], (int)$value['value']);
                $value['termid'] = (int)$value['value'];
                $value['value'] = $term['neve'];
                if ($term['parent']) {
                  $value['value'] = $term['parent']['neve'] .' / ' . $term['neve'];
                }
              break;

            }

            $output[$index][$key] = $value;
          }
        }
      break;

      default:
        foreach ((array)$list as $index => $l) {
          foreach ($l as $key => $value) {
            switch ($key) {
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
