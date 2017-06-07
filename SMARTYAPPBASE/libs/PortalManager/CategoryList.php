<?php
namespace PortalManager;

use PortalManager\Categories;

class CategoryList
{
  private $class = null;
  public function __construct()
  {
    if (class_exists('Controller')) {
      # code...
    }
    
    //$this->class = new Categories();
  }

  public function load( $group= false )
  {

  }

  public function __destruct()
  {
    $this->class = null;
  }
}
?>
