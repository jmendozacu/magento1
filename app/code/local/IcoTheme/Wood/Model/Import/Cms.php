<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_Wood_Model_Import_Cms extends Mage_Core_Model_Abstract
{
    private $_importPath;

    public function __construct()
    {
        parent::__construct();
        $this->_importPath = Mage::getBaseDir() . '/app/code/local/IcoTheme/Wood/etc/import/';
    }

    /**
     * Import CMS items
     * @param string model string
     * @param string name of the main XML node (and name of the XML file)
     * @param bool overwrite existing items
     */

    public function importCmsItems($modelString, $itemContainerNodeString, $layout)
    {
        try {
            $xmlPath = $this->_importPath . $layout .'/'.$itemContainerNodeString . '.xml';
            if (!is_readable($xmlPath)) {
                throw new Exception(
                    Mage::helper('adminhtml')->__("Can't read data file: %s", $xmlPath)
                );
            }
            $xmlObj = new Varien_Simplexml_Config($xmlPath);

            $conflictingOldItems = array();
            $i = 0;
            foreach ($xmlObj->getNode($itemContainerNodeString)->children() as $item) {

                //Check if block already exists
                $oldBlocks = Mage::getModel($modelString)->getCollection()
                    ->addFieldToFilter('identifier', $item->identifier)
                    ->load();

                if (count($oldBlocks) > 0) {
                    $conflictingOldItems[] = $item->identifier;
                    foreach ($oldBlocks as $old)
                        $old->delete();
                }

                $model = Mage::getModel($modelString)
                    ->setInstanceId($item->instance_id)
                    ->setTitle($item->title)
                    ->setContent($item->content)
                    ->setIdentifier($item->identifier)
                    ->setIsActive($item->is_active)
                    ->setStores(array(0));
                if($itemContainerNodeString == 'pages'){
                    $model->setRootTemplate($item->root_template);
                    $model->setIcoPageHeadingTextalign($item->heading_text_align);
                    $model->setIcoPageHeadingStyle($item->heading_style);
                    $model->setIcoPageHeadingImage($item->heading_image);
                    $model->setIcoPageHeadingTextStyle($item->heading_text_style);
                    $model->setIcoPageHeadingOverlayColor($item->heading_overlay_color);
                    $model->setIcoPageHeadingOverlayOpacity($item->heading_overlay_opacity);
                    $model->setIcoPageHeadingOverlayEffect($item->heading_overlay_effect);
                    $model->setIcoPageHeadingHeroHeight($item->heading_height);
                    $model->setIcoPageHeadingShowTitle($item->heading_show_title);
                    $model->setIcoPageHeadingPageTitle($item->heading_page_title);
                    $model->setIcoPageHeadingSubpageTitle($item->heading_sub_title);
                    $model->setIcoPageHeadingRemoveBcrumbs($item->heading_rm_bcrumbs);
                }
                $model->save();
                $i++;
            }

            if ($i) {
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Number of imported items: %s', $i)
                );
            } else {
                Mage::getSingleton('adminhtml/session')->addNotice(
                    Mage::helper('adminhtml')->__('No items were imported')
                );
            }

            if ($conflictingOldItems)
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Items (%s) with the following identifiers were overwritten:<br />%s', count($conflictingOldItems), implode(', ', $conflictingOldItems))
                );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::logException($e);
        }
    }

    /**
     * Import Widget items
     * @param string model string
     * @param string name of the main XML node (and name of the XML file)
     * @param bool overwrite existing items
     */

    public function importWidgetItems($modelString, $itemContainerNodeString, $overwrite = false, $layout)
    {
        try {
            $xmlPath = $this->_importPath . $layout .'/'. $itemContainerNodeString . '.xml';
            if (!is_readable($xmlPath)) {
                throw new Exception(
                    Mage::helper('adminhtml')->__("Can't read data file: %s", $xmlPath)
                );
            }
            $xmlObj = new Varien_Simplexml_Config($xmlPath);

            $i = 0;
            foreach ($xmlObj->getNode($itemContainerNodeString)->children() as $item) {
                $model = Mage::getModel($modelString)
                    ->setTitle($item->title)
                    ->setInstanceType($item->instance_type)
                    ->setPackageTheme($item->package_theme)
                    ->setWidgetParameters($item->widget_parameters)
                    ->setSortOrder($item->sort_order)
                    ->save();
                foreach ($item->page as $object) {
                    Mage::getSingleton('wood/resource_widget')->importInstancePage($model->getInstanceId(), $object, $item->sort_order, $item->xml);
                }
                $i++;
            }
            //Final info
            if ($i) {
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Number of imported items: %s', $i)
                );
            } else {
                Mage::getSingleton('adminhtml/session')->addNotice(
                    Mage::helper('adminhtml')->__('No items were imported')
                );
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::logException($e);
        }
    }

    /**
     * Restore Widget items
     * @param string model string
     * @param string name of the main XML node (and name of the XML file)
     * @param bool overwrite existing items
     */

    public function restoreWidgetItems($modelString, $itemContainerNodeString, $overwrite = false, $layout)
    {
        try {
            $collections = Mage::getModel($modelString)->getCollection()->addFieldToFilter('package_theme', array('wood/'.$layout));
            foreach($collections as $widget){
                $widget->delete();
            }
            Mage::getSingleton('wood/import_cms')->importWidgetItems($modelString, $itemContainerNodeString, $overwrite, $layout);
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::logException($e);
        }
    }
}
