<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_Wood_Helper_Image extends Mage_Core_Helper_Abstract
{
    public function getImg($_product, $imgType = "image", $w, $h, $file = NULL)
    {
        $config = Mage::getStoreConfig('wood/category');
        $keepAspectRatio = $config['aspect_ratio'];
        if ($config['custom_aspect_ratio_size']) {
            $customAspectRatio = $config['custom_aspect_ratio_size'];
        } else {
            $customAspectRatio = $config['aspect_ratio_size'];
        }

        $url = Mage::helper('catalog/image')->init($_product, $imgType, $file);

        if ($keepAspectRatio) {
            $url->keepAspectRatio(TRUE);
            $h = NULL;
        } else {
            $url->keepAspectRatio(FALSE);
            $h = $w*$customAspectRatio;
        }

        return $url->constrainOnly(TRUE)
            ->keepFrame(FALSE)
            ->resize($w, $h);
    }

    public function getImgSources($_product, $imgType = "image", $w, $h, $file = NULL)
    {
        $html = "data-src=\"" . $this->getImg($_product, $imgType, $w, $h, $file);
        $html .= "\" data-srcX2=\"";
        $html .= $this->getImg($_product, $imgType, $w * 2, $h * 2, $file);
        $html .= "\"";
        return $html;
    }
}
