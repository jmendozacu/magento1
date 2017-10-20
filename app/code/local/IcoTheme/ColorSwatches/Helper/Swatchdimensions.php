<?php
/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */

/**
 * Class IcoTheme_ColorSwatches_Helper_Swatchdimensions
 */
class IcoTheme_ColorSwatches_Helper_Swatchdimensions extends Mage_Core_Helper_Abstract
{
    const AREA_DETAIL = 'product_detail_dimensions';
    const AREA_LISTING = 'product_listing_dimensions';
    const AREA_LAYER = 'layered_nav_dimensions';

    const DIM_WIDTH = 'width';
    const DIM_HEIGHT = 'height';

    /**
     * The buffer between the "inner" and "outer" dimensions of a swatch
     *
     * @var int
     */
    protected $_dimensionBuffer = 2;

    /**
     * Get any dimension
     *
     * @param string $area
     * @param string $dimension
     * @param bool $outer
     * @return int
     */
    public function getDimension($area, $dimension, $outer = false)
    {
        $dimension = (int)Mage::getStoreConfig(
            IcoTheme_ColorSwatches_Helper_Data::CONFIG_PATH_BASE . '/' . $area . '/' . $dimension);
        if ($outer) {
            $dimension += $this->_dimensionBuffer;
        }
        return $dimension;
    }

    /**
     * Get inner width for any area
     *
     * @param string $area
     * @return int
     */
    public function getInnerWidth($area)
    {
        return $this->getDimension($area, self::DIM_WIDTH);
    }

    /**
     * Get inner height for any area
     *
     * @param string $area
     * @return int
     */
    public function getInnerHeight($area)
    {
        return $this->getDimension($area, self::DIM_HEIGHT);
    }

    /**
     * Get outer width for any area
     *
     * @param string $area
     * @return int
     */
    public function getOuterWidth($area)
    {
        return $this->getDimension($area, self::DIM_WIDTH, true);
    }

    /**
     * Get outer height for any area
     *
     * @param string $area
     * @return int
     */
    public function getOuterHeight($area)
    {
        return $this->getDimension($area, self::DIM_HEIGHT, true);
    }
}
