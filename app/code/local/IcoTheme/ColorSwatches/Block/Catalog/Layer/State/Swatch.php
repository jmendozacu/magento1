<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_ColorSwatches_Block_Catalog_Layer_State_Swatch extends Mage_Core_Block_Template
{
    protected $_initDone = false;

    /**
     * Determine if we should use this block to render a state filter
     *
     * @param Mage_Catalog_Model_Layer_Filter_Item $filter
     * @return bool
     */
    public function shouldRender($filter)
    {
        $helper = Mage::helper('colorswatches');
        if ($helper->isEnabled() && $filter->getFilter()->hasAttributeModel()) {
            if ($helper->attrIsSwatchType($filter->getFilter()->getAttributeModel())) {
                $this->_init($filter);
                if ($this->getSwatchUrl()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Set one-time data on the renderer
     *
     * @param Mage_Catalog_Model_Layer_Filter_Item $filter
     */
    protected function _init($filter)
    {
        if (!$this->_initDone) {
            $dimHelper = Mage::helper('colorswatches/swatchdimensions');
            $this->setSwatchInnerWidth(
                $dimHelper->getInnerWidth(IcoTheme_ColorSwatches_Helper_Swatchdimensions::AREA_LAYER));
            $this->setSwatchInnerHeight(
                $dimHelper->getInnerHeight(IcoTheme_ColorSwatches_Helper_Swatchdimensions::AREA_LAYER));
            $this->setSwatchOuterWidth(
                $dimHelper->getOuterWidth(IcoTheme_ColorSwatches_Helper_Swatchdimensions::AREA_LAYER));
            $this->setSwatchOuterHeight(
                $dimHelper->getOuterHeight(IcoTheme_ColorSwatches_Helper_Swatchdimensions::AREA_LAYER));

            $swatchUrl = Mage::helper('colorswatches/productimg')
                ->getGlobalSwatchUrl(
                    $filter,
                    $this->stripTags($filter->getLabel()),
                    $this->getSwatchInnerWidth(),
                    $this->getSwatchInnerHeight()
                );
            $this->setSwatchUrl($swatchUrl);

            $this->_initDone = true;
        }
    }
}
