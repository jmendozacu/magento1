<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetBlog_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getImgBlog($content)
    {
        preg_match_all('/(?<!_)url=([\'"])?(.*?)\\1/', $content, $matches);
        if (isset($matches[2][0])) {
            return Mage::getBaseUrl('media') . $matches[2][0];
        } else {
            preg_match_all('/(?<!_)src=([\'"])?(.*?)\\1/', $content, $option);
            return $option[2][0];
        }
    }
}
