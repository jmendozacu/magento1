<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php
class IcoTheme_Wood_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction() {
        $this->getResponse()->setRedirect($this->getUrl("adminhtml/system_config/edit/section/wood/"));
    }
    public function blocksAction() {
        $config = Mage::helper('wood')->getCfg('wood_install/install/overwrite_blocks');
        Mage::getSingleton('wood/import_cms')->importCmsItems('cms/block', 'blocks');
        $this->getResponse()->setRedirect($this->getUrl("adminhtml/system_config/edit/section/wood_install/"));
    }
    public function pagesAction() {
        $config = Mage::helper('wood')->getCfg('install/overwrite_pages');
        Mage::getSingleton('wood/import_cms')->importCmsItems('cms/page', 'pages');
        $this->getResponse()->setRedirect($this->getUrl("adminhtml/system_config/edit/section/wood_install/"));
    }
    public function widgetsAction() {
        Mage::getSingleton('wood/import_cms')->importWidgetItems('widget/widget_instance', 'widgets', false);
        $this->getResponse()->setRedirect($this->getUrl("adminhtml/system_config/edit/section/wood_install/"));
    }
}
