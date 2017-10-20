<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php
class IcoTheme_Wood_Adminhtml_ActivateController extends Mage_Adminhtml_Controller_Action
{

    protected $_stores;
    protected $_clear;

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
            ->isAllowed('icotheme/wood/activate');
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('icotheme/wood/activate')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Theme Activate'), Mage::helper('adminhtml')->__('Theme Activate'));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->_title($this->__('IcoTheme'))
            ->_title($this->__('Wood'))
            ->_title($this->__('Theme Activate'));
        $this->_addContent($this->getLayout()->createBlock('wood/adminhtml_activate_edit'));
        $this->renderLayout();
    }

    public function activateAction()
    {

        $this->_stores = $this->getRequest()->getParam('stores', array(0));
        $this->_clear = $this->getRequest()->getParam('clear_scope', true);
        $cms_block = $this->getRequest()->getParam('cms_block', 0);
        $cms_page = $this->getRequest()->getParam('cms_page', 0);
        $widget = $this->getRequest()->getParam('widget', 0);
        $layout = $this->getRequest()->getParam('demo_layout', 'default');
        $config = $this->getRequest()->getParam('config', 0);
        if ($this->_clear) {
            if ( !in_array(0, $this->_stores) )
                $stores[] = 0;
        }
        try {
            if($cms_block):
                Mage::getSingleton('wood/import_cms')->importCmsItems('cms/block', 'blocks', $layout);
            endif;
            if($cms_page):
                Mage::getSingleton('wood/import_cms')->importCmsItems('cms/page', 'pages', $layout);
            endif;
            if($widget):
                Mage::getSingleton('wood/import_cms')->importWidgetItems('widget/widget_instance', 'widgets', false, $layout);
            endif;
            foreach ($this->_stores as $store) {
                $scope = ($store ? 'stores' : 'default');
                Mage::getConfig()->saveConfig('design/package/name', 'wood', $scope, $store);
                Mage::getConfig()->saveConfig('design/theme/default', $layout, $scope, $store);
                Mage::getConfig()->saveConfig('design/footer/copyright', 'Copyright &copy; 2015 <a href="http://icotheme.com" >Magento Themes</a> by IcoTheme. All rights reserved.', $scope, $store);
            }

            if ($config):
                $defaults = new Varien_Simplexml_Config();
                $defaults->loadFile(Mage::getBaseDir() . '/app/code/local/IcoTheme/Wood/etc/import/'.$layout.'/config.xml');
                $this->_activeSettings($defaults->getNode('default/wood_design')->children(), 'wood_design');
                $this->_activeSettings($defaults->getNode('default/wood')->children(), 'wood');
            endif;

            Mage::app()->cleanCache();
            $model = Mage::getModel('core/cache');
            $options = $model->canUse();
            foreach ($options as $option => $value) {
                $options[$option] = 0;
            }
            $model->saveOptions($options);

            $adminSession = Mage::getSingleton('admin/session');
            $adminSession->unsetAll();
            $adminSession->getCookie()->delete($adminSession->getSessionName());

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Theme activate.'));
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('An error occurred while activate theme.'));
        }
        $this->getResponse()->setRedirect($this->getUrl("*/*/"));
    }

    private function _activeSettings($items, $path)
    {
        $websites = Mage::app()->getWebsites();
        $stores = Mage::app()->getStores();
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->_activeSettings($item->children(), $path . '/' . $item->getName());
            } else {
                if ($this->_clear) {
                    Mage::getConfig()->deleteConfig($path . '/' . $item->getName());
                    foreach ($websites as $website) {
                        Mage::getConfig()->deleteConfig($path . '/' . $item->getName(), 'websites', $website->getId());
                    }
                    foreach ($stores as $store) {
                        Mage::getConfig()->deleteConfig($path . '/' . $item->getName(), 'stores', $store->getId());
                    }
                }
                foreach ($this->_stores as $store) {
                    $scope = ($store ? 'stores' : 'default');
                    Mage::getConfig()->saveConfig($path . '/' . $item->getName(), (string)$item, $scope, $store);
                }
            }
        }
    }

}