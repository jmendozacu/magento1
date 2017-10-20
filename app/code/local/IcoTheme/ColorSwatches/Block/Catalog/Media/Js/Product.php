<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_ColorSwatches_Block_Catalog_Media_Js_Product
    extends IcoTheme_ColorSwatches_Block_Catalog_Media_Js_Abstract
{
    /**
     * Return array of single product -- current product
     *
     * @return array
     */
    public function getProducts()
    {
        $product = Mage::registry('product');

        if (!$product) {
            return array();
        }

        return array($product);
    }

    /**
     * Default to base image type
     *
     * @return string
     */
    public function getImageType()
    {
        $type = parent::getImageType();

        if (empty($type)) {
            $type = IcoTheme_ColorSwatches_Helper_Productimg::MEDIA_IMAGE_TYPE_BASE;
        }

        return $type;
    }

    /**
     * instruct image image type to be loaded
     *
     * @return array
     */
    protected function _getImageSizes()
    {
        return array('image');
    }
}
