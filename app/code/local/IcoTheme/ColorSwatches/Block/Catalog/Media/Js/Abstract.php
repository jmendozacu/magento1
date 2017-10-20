<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
abstract class IcoTheme_ColorSwatches_Block_Catalog_Media_Js_Abstract extends Mage_Core_Block_Template
{
    protected $_template = 'icotheme/colorswatches/catalog/media/js.phtml';

    /**
     * Get target product IDs
     *
     * @return array
     */
    abstract public function getProducts();

    /**
     * json encode image fallback array
     *
     * @param array $imageFallback
     * @return string
     */
    protected function _getJsImageFallbackString(array $imageFallback)
    {
        /* @var $coreHelper Mage_Core_Helper_Data */
        $coreHelper = Mage::helper('core');

        return $coreHelper->jsonEncode($imageFallback);
    }

    /**
     * Image size(s) to attach to children products as array
     *
     * @return array
     */
    abstract protected function _getImageSizes();

    /**
     * Get image fallbacks by product as
     * array(product ID => array( product => product, image_fallback => image fallback ) )
     *
     * @return array
     */
    public function getProductImageFallbacks($keepFrame = null)
    {
        /* @var $helper IcoTheme_ColorSwatches_Helper_Mediafallback */
        $helper = Mage::helper('colorswatches/mediafallback');

        $fallbacks = array();

        $products = $this->getProducts();

        if ($keepFrame === null) {
            $listBlock = $this->getLayout()->getBlock('product_list');
            if ($listBlock && $listBlock->getMode() == 'grid') {
                $keepFrame = true;
            } else {
                $keepFrame = false;
            }
        }

        /* @var $product Mage_Catalog_Model_Product */
        foreach ($products as $product) {
            $imageFallback = $helper->getConfigurableImagesFallbackArray($product, $this->_getImageSizes(), $keepFrame);

            $fallbacks[$product->getId()] = array(
                'product' => $product,
                'image_fallback' => $this->_getJsImageFallbackString($imageFallback)
            );
        }

        return $fallbacks;
    }

    /**
     * Get image type to pass to configurable media image JS
     *
     * @return string
     */
    public function getImageType()
    {
        return parent::getImageType();
    }

    /**
     * Prevent actual block render if we are disabled, and i.e. via the module
     * config as opposed to the advanced module settings page
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!Mage::helper('colorswatches')->isEnabled()) { // functionality disabled
            return ''; // do not render block
        }
        return parent::_toHtml();
    }
}
