<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_ColorSwatches_Model_System_Config_Source_Catalog_Product_Configattribute_Select
    extends IcoTheme_ColorSwatches_Model_System_Config_Source_Catalog_Product_Configattribute
{
    /**
     * Retrieve attributes as array
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (is_null($this->_attributes)) {
            parent::toOptionArray();
            array_unshift(
                $this->_attributes,
                array(
                    'value' => '',
                    'label' => Mage::helper('colorswatches')->__('-- Please Select --'),
                )
            );
        }
        return $this->_attributes;
    }
}
