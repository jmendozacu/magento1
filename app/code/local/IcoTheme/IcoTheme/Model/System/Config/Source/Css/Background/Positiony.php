<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_IcoTheme_Model_System_Config_Source_Css_Background_Positiony
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'top', 'label' => Mage::helper('adminhtml')->__('top')),
            array('value' => 'center', 'label' => Mage::helper('adminhtml')->__('center')),
            array('value' => 'bottom', 'label' => Mage::helper('adminhtml')->__('bottom'))
        );
    }
}