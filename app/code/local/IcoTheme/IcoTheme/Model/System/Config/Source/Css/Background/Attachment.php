<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_IcoTheme_Model_System_Config_Source_Css_Background_Attachment
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'fixed', 'label' => Mage::helper('adminhtml')->__('fixed')),
            array('value' => 'scroll', 'label' => Mage::helper('adminhtml')->__('scroll'))
        );
    }
}