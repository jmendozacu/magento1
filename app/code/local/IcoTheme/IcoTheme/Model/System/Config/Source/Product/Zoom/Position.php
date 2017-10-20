<?php

/**
 * @copyright   Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
class IcoTheme_IcoTheme_Model_System_Config_Source_Product_Zoom_Position
{
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label' => Mage::helper('adminhtml')->__('Right')),
            array('value' => '11', 'label' => Mage::helper('adminhtml')->__('Left')),
            array('value' => '14', 'label' => Mage::helper('adminhtml')->__('Top')),
            array('value' => '6', 'label' => Mage::helper('adminhtml')->__('Bottom')),
            array('value' => 'inside', 'label' => Mage::helper('adminhtml')->__('Inside'))
        );
    }
}