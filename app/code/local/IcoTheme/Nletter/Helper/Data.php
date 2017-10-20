<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_Nletter_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getCfgEnable()
    {
        return Mage::getStoreConfig('nletter/display_options/enable');
    }

    public function getCfgWidth()
    {
        return Mage::getStoreConfig('nletter/display_options/width');
    }

    public function getCfgHeight()
    {
        return Mage::getStoreConfig('nletter/display_options/height');
    }

    public function getCfgBackgroundColor()
    {
        return Mage::getStoreConfig('nletter/display_options/background_color');
    }

    public function getCfgBackgroundImage()
    {
        return Mage::getStoreConfig('nletter/display_options/background_image');
    }

    public function getCfgIntro()
    {
        return Mage::getStoreConfig('nletter/display_options/intro');
    }
}