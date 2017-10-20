<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Model_Widget_Source_Tab
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'none', 'label' => Mage::helper('widgetproducts')->__('None')),
            array('value' => 'categories', 'label' => Mage::helper('widgetproducts')->__('Categories')),
            array('value' => 'collections', 'label' => Mage::helper('widgetproducts')->__('Collections'))
        );
    }
}
