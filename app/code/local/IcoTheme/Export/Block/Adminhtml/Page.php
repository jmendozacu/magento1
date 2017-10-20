<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 *
 */
?>
<?php

class IcoTheme_Export_Block_Adminhtml_Page extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_page';
        $this->_blockGroup = 'export';
        $this->_headerText = Mage::helper('export')->__('Manage Export Page');
        parent::__construct();
        $this->_removeButton('add');
    }
}