<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Model_Widget_Source_Responsive
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'width', 'label' => Mage::helper('widgetproducts')->__('By Width')),
            array('value' => 'breakpoint', 'label' => Mage::helper('widgetproducts')->__('By Breakpoints'))
        );
    }
}