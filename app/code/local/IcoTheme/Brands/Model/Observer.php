<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_Brands_Model_Observer
{
    public function updateLayout()
    {
        Mage::helper('brands')->loadJsLibs('browser');
    }
}