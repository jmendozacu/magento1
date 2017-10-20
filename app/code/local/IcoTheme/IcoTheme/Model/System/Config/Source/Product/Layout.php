<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_IcoTheme_Model_System_Config_Source_Product_Layout
{

    public function toOptionArray()
    {
        return array(
            array('value' => 'default', 'label' => Mage::helper('adminhtml')->__('Default')),
            array('value' => 'horizontal', 'label' => Mage::helper('adminhtml')->__('Horizontal')),
            array('value' => 'vertical', 'label' => Mage::helper('adminhtml')->__('Vertical')),
            array('value' => 'custom1', 'label' => Mage::helper('adminhtml')->__('Custom 1')),
            array('value' => 'custom2', 'label' => Mage::helper('adminhtml')->__('Custom 2'))
        );
    }

}