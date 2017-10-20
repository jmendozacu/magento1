<?php
class TV_Articles_Model_Mysql4_Articles_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
     public function _construct()
     {
         parent::_construct();
         $this->_init('articles/articles');
     }
}