<?php
class Fgc_Valution_Model_Mysql4_Valution extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("valution/valution", "id");
    }
}