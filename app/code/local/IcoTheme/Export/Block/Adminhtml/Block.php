<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_Export_Block_Adminhtml_Block extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_block';
        $this->_blockGroup = 'export';
        $this->_headerText = $this->__('Manage Export Static Block');
        parent::__construct();
        $this->_removeButton('add');
    }
}