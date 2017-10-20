<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_ColorSwatches_Block_Catalog_Media_Js_List
    extends IcoTheme_ColorSwatches_Block_Catalog_Media_Js_Abstract
{
    /**
     * Get target product IDs from product collection
     * which was set on block
     *
     * @return array
     */
    public function getProducts()
    {
        return $this->getProductCollection();
    }

    /**
     * Default to small image type
     *
     * @return string
     */
    public function getImageType()
    {
        $type = parent::getImageType();

        if (empty($type)) {
            $type = IcoTheme_ColorSwatches_Helper_Productimg::MEDIA_IMAGE_TYPE_SMALL;
        }

        return $type;
    }

    /**
     * instruct small_image image type to be loaded
     *
     * @return array
     */
    protected function _getImageSizes()
    {
        return array('small_image');
    }
}
