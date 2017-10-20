<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php
class IcoTheme_Wood_Block_Adminhtml_Button_Restore_Restore extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $elementOriginalData = $element->getOriginalData();
        $buttonSuffix = '';
        if (isset($elementOriginalData['label']))
            $buttonSuffix = ' ' . $elementOriginalData['label'];
        $url = $this->getUrl('wood/adminhtml_restore/restore');
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('scalable restore')
            ->setLabel($buttonSuffix)
            ->setOnClick("setLocation('$url')")
            ->toHtml();
        return $html;
    }
}