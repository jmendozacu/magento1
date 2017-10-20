<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please 
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_Search
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


class Plumrocket_Search_Block_System_Config_GeneralAdditional extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    
    public function _construct() {
        parent::_construct();
        $this->setTemplate('psearch/system/config/general_additional.phtml');
        return $this;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->element = $element;
        return $this->toHtml();
    }

    public function getOptions()
    {
        $options = array();
        $catalog = Mage::getSingleton('adminhtml/config')->getSection('catalog');
        $catalogInventory = Mage::getSingleton('adminhtml/config')->getSection('cataloginventory');

        $options['Configuration -> Catalog -> Catalog Search'] = array(
            $this->_getOption('catalog/search/min_query_length'),
            $this->_getOption('catalog/search/max_query_length'),
            $this->_getOption('catalog/search/max_query_words'),
            $this->_getOption('catalog/search/search_type'),
        );

        $options['Configuration -> Inventory -> Stock Options'] = array(
            $this->_getOption('cataloginventory/options/show_out_of_stock'),
        );

        return $options;
    }

    protected function _getOption($path)
    {
        list($sectionName, $groupName, $fieldName) = explode('/', $path, 3);

        if(!$section = Mage::getSingleton('adminhtml/config')->getSection($sectionName)) {
            return false;
        }

        if(!$group = $section->groups->$groupName) {
            return false;
        }

        if(!$field = $group->fields->$fieldName) {
            return false;
        }

        $field->name    = $fieldName;
        $field->path    = $path;
        $field->value   = Mage::getStoreConfig($path);
        $field->url     = Mage::helper('adminhtml')->getUrl("adminhtml/system_config/edit/section/{$sectionName}");

        switch($field->frontend_type) {
            case 'select':
                $field->valueText = '';
                if($options = Mage::getSingleton("$field->source_model")->toOptionArray()) {
                    foreach ($options as $option) {
                        if($option['value'] == $field->value) {
                            $field->valueText = $option['label'];
                            break;
                        }
                    }
                }
                
                break;

            default:
                $field->valueText = (string)$field->value;
        }

        return $field;
    }

}