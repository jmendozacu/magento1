<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Model_Widget_Source_Direction
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'horizontal', 'label' => Mage::helper('widgetproducts')->__('Horizontal')),
            array('value' => 'vertical', 'label' => Mage::helper('widgetproducts')->__('Vertical'))
        );
    }
}