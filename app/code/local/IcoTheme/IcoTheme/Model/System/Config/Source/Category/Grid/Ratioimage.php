<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_IcoTheme_Model_System_Config_Source_Category_Grid_Ratioimage
{

    public function toOptionArray()
    {
        return array(
            array('value' => '1.11', 'label' => Mage::helper('adminhtml')->__('1 X 1.11 (Demo Value)')),
            array('value' => '1', 'label' => Mage::helper('adminhtml')->__('1 X 1')),
            array('value' => '1.25', 'label' => Mage::helper('adminhtml')->__('1 X 1.25')),
            array('value' => '1.5', 'label' => Mage::helper('adminhtml')->__('1 X 1.5')),
            array('value' => '2', 'label' => Mage::helper('adminhtml')->__('1 X 2')),
        );
    }

}