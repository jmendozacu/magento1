<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Model_Widget_Source_Tab_Mode
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'latest', 'label' => Mage::helper('widgetproducts')->__('Latest Products')),
            array('value' => 'new', 'label' => Mage::helper('widgetproducts')->__('New Products')),
            array('value' => 'bestsell', 'label' => Mage::helper('widgetproducts')->__('Best Sell Products')),
            array('value' => 'mostviewed', 'label' => Mage::helper('widgetproducts')->__('Most Viewed Products')),
            array('value' => 'random', 'label' => Mage::helper('widgetproducts')->__('Random Products')),
            array('value' => 'discount', 'label' => Mage::helper('widgetproducts')->__('Discount Products')),
            array('value' => 'rating', 'label' => Mage::helper('widgetproducts')->__('Top Rated Products'))
        );
    }
}
