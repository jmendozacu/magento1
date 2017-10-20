<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_ColorSwatches_Block_Catalog_Product_View_Type_Configurable_Swatches extends Mage_Core_Block_Template
{
    protected $_initDone = false;

    /**
     * Determine if the renderer should be used
     *
     * @param Mage_Catalog_Model_Product_Type_Configurable_Attribute $attribute
     * @param string $jsonConfig
     * @return bool
     */
    public function shouldRender($attribute, $jsonConfig)
    {
        if (Mage::helper('colorswatches')->isEnabled()) {
            if (Mage::helper('colorswatches')->attrIsSwatchType($attribute->getProductAttribute())) {
                $this->_init($jsonConfig);
                return true;
            }
        }
        return false;
    }

    /**
     * Set one-time data on the renderer
     *
     * @param string $jsonConfig
     */
    protected function _init($jsonConfig)
    {
        if (!$this->_initDone) {
            $this->setJsonConfig($jsonConfig);

            $dimHelper = Mage::helper('colorswatches/swatchdimensions');
            $this->setSwatchInnerWidth(
                $dimHelper->getInnerWidth(IcoTheme_ColorSwatches_Helper_Swatchdimensions::AREA_DETAIL));
            $this->setSwatchInnerHeight(
                $dimHelper->getInnerHeight(IcoTheme_ColorSwatches_Helper_Swatchdimensions::AREA_DETAIL));
            $this->setSwatchOuterWidth(
                $dimHelper->getOuterWidth(IcoTheme_ColorSwatches_Helper_Swatchdimensions::AREA_DETAIL));
            $this->setSwatchOuterHeight(
                $dimHelper->getOuterHeight(IcoTheme_ColorSwatches_Helper_Swatchdimensions::AREA_DETAIL));

            $this->_initDone = true;
        }
    }
}
