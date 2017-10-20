<?php
class TV_Articles_Model_Mysql4_Articles extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('articles/articles', 'articles_id');  // articles_id is the primary key of table
    }
}