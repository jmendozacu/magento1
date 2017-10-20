<?php
/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_AjaxProducts_Block_Cartlayout extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('icotheme/ajaxproducts/cartlayout.phtml');
    }
    public function getProductQuotes()
    {
        return $this->getQuote()->getAllItems();
    }
    public function getAddToCartUrl($product, $additional = array())
    {
        return $this->helper('checkout/cart')->getAddUrl($product, $additional);
    }
    public function getProduct()
    {
        return Mage::getModel('catalog/product')->load($this->getProductId());
    }
    public function getQuote()
    {
        return Mage::getSingleton('checkout/session')->getQuote();
    }
}