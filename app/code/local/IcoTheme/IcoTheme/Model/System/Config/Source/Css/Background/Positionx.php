<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_IcoTheme_Model_System_Config_Source_Css_Background_Positionx
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'left', 'label' => Mage::helper('adminhtml')->__('left')),
            array('value' => 'center', 'label' => Mage::helper('adminhtml')->__('center')),
            array('value' => 'right', 'label' => Mage::helper('adminhtml')->__('right'))
        );
    }
}