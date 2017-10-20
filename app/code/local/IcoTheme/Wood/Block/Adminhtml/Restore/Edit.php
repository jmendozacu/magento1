<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php
class IcoTheme_Wood_Block_Adminhtml_Restore_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'wood';
        $this->_controller = 'adminhtml_restore';
        $this->_updateButton('save', 'label', Mage::helper('adminhtml')->__('Restore Defaults'));
        $this->_removeButton('delete');
        $this->_removeButton('back');
    }

    public function getHeaderText()
    {
        return Mage::helper('adminhtml')->__('Theme Restore Defaults');
    }
}
