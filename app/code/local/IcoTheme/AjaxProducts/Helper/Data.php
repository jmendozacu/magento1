<?php
/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_AjaxProducts_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getAddToCartLayout($product){
        return
            $this->getLayout()->createBlock('ajaxproducts/cartlayout')
                ->setProductId($product->getId())
                ->toHtml();
    }
}