<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_Wood_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Get theme's main settings (single option)
     *
     * @return string
     */
    public function getCfg($optionString)
    {
        return Mage::getStoreConfig('wood/' . $optionString);
    }

    /**
     * Get theme's main settings design (single option)
     *
     * @return array
     */
    public function getCfgSectionDesign($storeId)
    {
        if ($storeId)
            return Mage::getStoreConfig('wood_design', $storeId);
        else
            return Mage::getStoreConfig('wood_design');
    }

    /**
     * Get theme's design settings (single option)
     *
     * @return string
     */
    public function getThemeDesignCfg($optionString, $storeCode = NULL)
    {
        return Mage::getStoreConfig('wood_design/' . $optionString, $storeCode);
    }

    /**
     * Get view product show label
     *
     * @return product detail
     */
    protected function _loadProduct(Mage_Catalog_Model_Product $product)
    {
        $product->load($product->getId());
    }

    /**
     * Get config show label for product
     *
     * @return html label
     */
    public function getLabel(Mage_Catalog_Model_Product $product)
    {
        $html = '';
        if (!$this->getCfg("product_labels/new") &&
            !$this->getCfg("product_labels/sale")
        ) {
            return $html;
        }
        if ($this->getCfg("product_labels/new") && $this->_checkNew($product)) {
            $html .= '<div class="product-new-label">' . $this->__('New') . '</div>';
        }
        if ($this->getCfg("product_labels/sale") && $this->_checkSale($product)) {
            $html .= '<div class="product-sale-label">' . $this->__('Sale') . '</div>';
        }

        return $html;
    }

    public function getSidebarPos()
    {
        return $this->getCfg("category/slidebar_position");
    }

    /**
     * Check date time locale
     *
     * @return true or false
     */
    protected function _checkDate($from, $to)
    {
        $today = strtotime(
            Mage::app()->getLocale()->date()
                ->setTime('00:00:00')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT)
        );
        if ($from && $today < $from) {
            return false;
        }
        if ($to && $today > $to) {
            return false;
        }
        if (!$to && !$from) {
            return false;
        }
        return true;
    }

    /**
     * Get date from new product
     *
     * @return from date and to date
     */
    protected function _checkNew($product)
    {
        $from = strtotime($product->getData('news_from_date'));
        $to = strtotime($product->getData('news_to_date'));

        return $this->_checkDate($from, $to);
    }

    /**
     * Get date from sale product
     *
     * @return from date and to date
     */
    protected function _checkSale($product)
    {
        $from = strtotime($product->getData('special_from_date'));
        $to = strtotime($product->getData('special_to_date'));

        return $this->_checkDate($from, $to);
    }

    /**
     * Get alternative image HTML of the given product
     *
     * @param Mage_Catalog_Model_Product $product Product
     * @param int $w Image width
     * @param int $h Image height
     * @param string $imgVersion Image version: image, small_image, thumbnail
     * @return string
     */
    public function getAltImgHtml($product, $w, $h, $imgType = 'small_image')
    {
        $column = $this->getCfg('category/alt_image_column');
        $value = $this->getCfg('category/alt_image_column_value');
        $product->load('media_gallery');
        if ($gal = $product->getMediaGalleryImages()) {
            if ($altImg = $gal->getItemByColumnValue($column, $value)) {
                return
                    '<img class="img-responsive alt-img lazy" ' . Mage::helper('wood/image')->getImgSources($product, $imgType, $w, $h, $altImg->getFile(), null) . ' src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'frontend/wood/default/images/AjaxLoader.gif" alt="' . $product->getName() . '" />';
            }
        }

        return '';
    }

    public function prevnext($product)
    {
        $prev_url = $next_url = $url = $product->getProductUrl();
        if (Mage::helper('catalog/data')->getCategory()) {
            $category = Mage::helper('catalog/data')->getCategory();
        } else {
            $_ccats = Mage::helper('catalog/data')->getProduct()->getCategoryIds();
            if (isset($_ccats[0])) {
                $category = Mage::getModel('catalog/category')->load($_ccats[0]);
            } else {
                return false;
            }
        }
        $children = $category->getProductCollection();
        $_count = is_array($children) ? count($children) : $children->count();
        if ($_count) {
            foreach ($children as $product) {
                $plist[] = $product->getId();
            }
            $current_pid = Mage::helper('catalog/data')->getProduct()->getId();
            $curpos = array_search($current_pid, $plist);
            // get link for prev product
            $previd = isset($plist[$curpos + 1]) ? $plist[$curpos + 1] : $current_pid;
            $product = Mage::getModel('catalog/product')->load($previd);
            $prevpos = $curpos;
            while (!$product->isVisibleInCatalog()) {
                $prevpos += 1;
                $nextid = isset($plist[$prevpos]) ? $plist[$prevpos] : $current_pid;
                $product = Mage::getModel('catalog/product')->load($nextid);
            }
            $prev_url = $product->getProductUrl();
            // get link for next product
            $nextid = isset($plist[$curpos - 1]) ? $plist[$curpos - 1] : $current_pid;
            $product = Mage::getModel('catalog/product')->load($nextid);
            $nextpos = $curpos;
            while (!$product->isVisibleInCatalog()) {
                $nextpos -= 1;
                $nextid = isset($plist[$nextpos]) ? $plist[$nextpos] : $current_pid;
                $product = Mage::getModel('catalog/product')->load($nextid);
            }
            $next_url = $product->getProductUrl();
        }
        $html = '';
        if ($url <> $prev_url):
            $html = '<a class="product-next" title="' . $this->__('Next Product') . '" href="' . $prev_url . '"><i class="fa fa-angle-right"></i></a>';
        endif;
        if ($url <> $next_url):
            $html .= '<a class="product-prev" title="' . $this->__('Previous Product') . '" href="' . $next_url . '"><i class="fa fa-angle-left"></i></a>';
        endif;
        return $html;
    }

    public function getCloudZoomConfig($rel = false)
    {
        $config['zoomWidth'] = is_numeric(Mage::getStoreConfig('wood/product_page/zoom_width')) ? Mage::getStoreConfig('wood/product_page/zoom_width') : 'auto';
        $config['zoomHeight'] = is_numeric(Mage::getStoreConfig('wood/product_page/zoom_height')) ? Mage::getStoreConfig('wood/product_page/zoom_height') : 'auto';
        $config['position'] = Mage::getStoreConfig('wood/product_page/zoom_position') ? Mage::getStoreConfig('wood/product_page/zoom_position') : 'right';
        $config['adjustX'] = is_numeric(Mage::getStoreConfig('wood/product_page/zoom_adjustX')) ? Mage::getStoreConfig('wood/product_page/zoom_adjustX') : 0;
        $config['adjustY'] = is_numeric(Mage::getStoreConfig('wood/product_page/zoom_adjustY')) ? Mage::getStoreConfig('wood/product_page/zoom_adjustY') : 0;
        if ($rel) {
            $out = array();
            foreach ($config as $k => $v) {
                if (is_numeric($v)) $out[] = sprintf("%s:%d", $k, $v);
                else $out[] = sprintf("%s:'%s'", $k, $v);
            }
            return implode(',', $out);
        } else return $config;
    }

}