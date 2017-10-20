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


class Plumrocket_Search_Block_System_Config_Attributes extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    
    public function _construct() {
        parent::_construct();
        $this->setTemplate('psearch/system/config/attributes.phtml');
        return $this;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->element = $element;
        return $this->toHtml();
    }

    public function getAttributes()
    {
        return $this->_getCollection()
            ->addFieldToFilter('additional_table.is_searchable', 0)
            ->setOrder('main_table.frontend_label', 'asc')
            ->setOrder('main_table.attribute_code', 'asc');
    }

    public function getAttributesSearchable()
    {
        $default = array(
            'name'                  => 1,
            'short_description'     => 2,
            'description'           => 3,
            'computer_manufacturers'=> 4,
            'sku'                   => 5,
        );

        $collection = $this->_getCollection()
            ->addIsSearchableFilter()
            ->setOrder('IF(psearch_priority, psearch_priority, 1000)', 'asc');
        
        $items = array();
        $i = 0;
        foreach ($collection as $item) {
            if(!$item->getPsearchPriority()) {
                $priority = isset($default[ $item->getAttributeCode() ])? $default[ $item->getAttributeCode() ] : (1000 + $i);
                $item->setPsearchPriority($priority);
            }
            $items[ $item->getPsearchPriority() ] = $item;
            $i++;
        }

        ksort($items);

        return $items;
    }

    public function prepareLabel($attribute)
    {
        if(!$label = $attribute->getFrontendLabel()) {
            $label = ucwords(str_replace('_', ' ', $attribute->getName()));
        }

        return $label;
    }

    protected function _getCollection()
    {
        $collection = Mage::getResourceModel('catalog/product_attribute_collection')
            ->addVisibleFilter();

        return $collection;
    }

}