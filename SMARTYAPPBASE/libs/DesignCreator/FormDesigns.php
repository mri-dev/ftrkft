<?php
namespace DesignCreator;

class FormDesigns extends \Controller
{
  public function __construct(){
    parent::__construct();
  }

  public function singleSelector( $key, $list = '' )
  {
    $this->smarty->assign('key', $key);
    $this->smarty->assign('list', $list);

    $root = $this->smarty->getTemplateDir();
    return $this->smarty->display($root[0].$this->template_root . 'FormDesigns/'.__FUNCTION__. '.tpl');
  }

  public function multiSelector( $key, $list = '' )
  {
    $this->smarty->assign('key', $key);
    $this->smarty->assign('list', $list);

    $root = $this->smarty->getTemplateDir();
    return $this->smarty->display($root[0].$this->template_root . 'FormDesigns/'.__FUNCTION__. '.tpl');
  }

  public function __destruct(){
  }
}
?>
