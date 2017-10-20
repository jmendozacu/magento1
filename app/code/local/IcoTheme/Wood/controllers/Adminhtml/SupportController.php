<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_Wood_Adminhtml_SupportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('icotheme/wood')
            ->_title(Mage::helper('adminhtml')->__('Help & Support'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Help & Support'), Mage::helper('adminhtml')->__('Help & Support'));
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }
}

?>
