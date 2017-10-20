<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_Brands_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function loadJsLibs($libs = array())
    {
        if (is_string($libs)) $libs = array($libs);
        $head = Mage::getSingleton('core/layout')->getBlock('head')->setCanLoadExtJs(true);
        foreach ($libs as $lib) {
            switch ($lib) {
                case 'jscolor':
                    $head->addJs('jscolor/jscolor.js');
                    break;
                case 'browser':
                    $head->addJs('lib/flex.js')
                        ->addJs('lib/FABridge.js')
                        ->addJs('mage/adminhtml/flexuploader.js')
                        ->addJs('icotheme/jquery/plugins/browser.js')
                        ->addJs('prototype/window.js')
                        ->addItem('js_css', 'prototype/windows/themes/default.css');
                    if (version_compare(Mage::getVersion(), '1.7.0.0') < 0) {
                        $head->addItem('js_css', 'prototype/windows/themes/magento.css');
                    } else {
                        $head->addItem('skin_css', 'lib/prototype/windows/themes/magento.css');
                    }
                    break;
            }
        }
    }
}