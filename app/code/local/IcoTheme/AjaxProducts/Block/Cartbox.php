<?php
/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_AjaxProducts_Block_Cartbox extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('icotheme/ajaxproducts/cartbox.phtml');
    }
    public function getProduct()
    {
        return Mage::getModel('catalog/product')->load($this->getProductId());
    }
}