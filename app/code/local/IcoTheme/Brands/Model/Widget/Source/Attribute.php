<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_Brands_Model_Widget_Source_Attribute
{
    public function toOptionArray()
    {
        $attributes = array();
        $collection = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->addFieldToFilter('is_filterable', array('eq' => '1'))
            ->addFieldToFilter('frontend_input', array('eq' => 'select'));
        foreach ($collection as $attribute) {
            $attributes[] = array(
                'value' => $attribute->getAttributeId() . ',' . $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel());
        }
        return $attributes;
    }
}