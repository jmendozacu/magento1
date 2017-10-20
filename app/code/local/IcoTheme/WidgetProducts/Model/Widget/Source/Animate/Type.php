<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Model_Widget_Source_Animate_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'effect-zoomOut', 'label' => Mage::helper('widgetproducts')->__('Zoom Out')),
            array('value' => 'effect-zoomIn', 'label' => Mage::helper('widgetproducts')->__('Zoom In')),
            array('value' => 'effect-bounceIn', 'label' => Mage::helper('widgetproducts')->__('Bounce In')),
            array('value' => 'effect-bounceInRight', 'label' => Mage::helper('widgetproducts')->__('Bounce In Right')),
            array('value' => 'effect-pageTop', 'label' => Mage::helper('widgetproducts')->__('Page Top')),
            array('value' => 'effect-pageBottom', 'label' => Mage::helper('widgetproducts')->__('Page Bottom')),
            array('value' => 'effect-starwars', 'label' => Mage::helper('widgetproducts')->__('Star Wars')),
            array('value' => 'effect-pageLeft', 'label' => Mage::helper('widgetproducts')->__('Page Left')),
            array('value' => 'effect-pageRight', 'label' => Mage::helper('widgetproducts')->__('Page Right'))
        );
    }
}
