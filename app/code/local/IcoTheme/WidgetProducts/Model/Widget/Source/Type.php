<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Model_Widget_Source_Type
{
    public function toOptionArray()
    {
        $types = array(
            array('value' => 'product', 'label' => Mage::helper('widgetproducts')->__('Product')),
            /*array('value' => 'attribute', 'label' => Mage::helper('widgetproducts')->__('Attribute')),*/
            array('value' => 'category', 'label' => Mage::helper('widgetproducts')->__('Category'))
        );

        return $types;
    }
}
