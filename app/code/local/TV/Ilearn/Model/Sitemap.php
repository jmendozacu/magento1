<?php

class TV_Ilearn_Model_Sitemap extends Mage_Sitemap_Model_Sitemap {

    protected $_io;

    public function generateXml() {
        //echo 'here';exit();
        //if (Mage::helper('blog')->extensionEnabled('Smartwave_Ascurl')) {
        //return Mage::getModel('ascurl/sitemap')->setData($this->getData())->generateXml();
        //}

        $this->fileCreate();

        $storeId = $this->getStoreId();
        $date = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        /**
         * Generate categories sitemap
         */
        $changefreq = (string) Mage::getStoreConfig('sitemap/category/changefreq');
        $priority = (string) Mage::getStoreConfig('sitemap/category/priority');
        $collection = Mage::getResourceModel('sitemap/catalog_category')->getCollection($storeId);
        foreach ($collection as $item) {
            $CatID = $item->getId();
            $cateparentid = $this->getCateParentId($CatID);
            $category = Mage::getModel('catalog/category')->load($cateparentid);
            //file_put_contents('sitemap.txt', 'getIsActive=' . $category->getIsActive() . ' id=' . $cateparentid . ' Cate Parent Name=' . $category->getName() . ' iditem=' . $CatID . ' Cate Name=' . Mage::getModel('catalog/category')->load($CatID)->getName() . " \n", FILE_APPEND);
            if ($category->getIsActive()) {
                $xml = sprintf(
                        '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>', htmlspecialchars($baseUrl . $item->getUrl()), $date, $changefreq, $priority
                );
                $this->sitemapFileAddLine($xml);
            }
        }
        unset($collection);

        /**
         * Generate products sitemap
         */
        $changefreq = (string) Mage::getStoreConfig('sitemap/product/changefreq');
        $priority = (string) Mage::getStoreConfig('sitemap/product/priority');
        $collection = Mage::getResourceModel('sitemap/catalog_product')->getCollection($storeId);
        foreach ($collection as $item) {
            $xml = sprintf(
                    '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>', htmlspecialchars($baseUrl . $item->getUrl()), $date, $changefreq, $priority
            );
            $this->sitemapFileAddLine($xml);
        }
        unset($collection);

        /**
         * Generate cms pages sitemap
         */
        $changefreq = (string) Mage::getStoreConfig('sitemap/page/changefreq');
        $priority = (string) Mage::getStoreConfig('sitemap/page/priority');
        $collection = Mage::getResourceModel('sitemap/cms_page')->getCollection($storeId);
        foreach ($collection as $item) {
            /*
             * Added by: Tran Trong Thang(trantrongthang1207@gmail.com)
             * added date: 1-Jul-2015
             */
            //file_put_contents('test.txt', $item->getUrl() . in_array($item->getUrl(), $arrItem) . " \n", FILE_APPEND);
            $arrItem = array('privacy-policy-cookie-restriction-mode', 'home-1', 'allium_no_route', 'termsoftrade');
            if (!in_array($item->getUrl(), $arrItem)) {
                $xml = sprintf(
                        '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>', htmlspecialchars($baseUrl . $item->getUrl()), $date, $changefreq, $priority
                );
                $this->sitemapFileAddLine($xml);
            }
        }
        unset($collection);

        Mage::dispatchEvent('sitemap_add_xml_block_to_the_end', array('sitemap_object' => $this));

        $this->fileClose();

        $this->setSitemapTime(Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }

    protected function fileCreate() {
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));
        $io->streamOpen($this->getSitemapFilename());

        $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        $io->streamWrite('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        $this->_io = $io;
    }

    protected function fileClose() {
        $this->_io->streamWrite('</urlset>');
        $this->_io->streamClose();
    }

    public function sitemapFileAddLine($xml) {
        $this->_io->streamWrite($xml);
    }

    public function getCateParentId($catID) {
        //Lay cate parent id cha cua catid
        $parentId = Mage::getModel('catalog/category')->load($catID)->getParentId();
        $category = Mage::getModel('catalog/category')->load($parentId);
        /*
         * Kiem tra xem cate co active ko
         */

        if (!$category->getIsActive()) {
            return $parentId;
        }
        /*
         * kiem tra xem no co phai la cate 'Default Category' ko
         * neu ko phai chay lai(de quy)
         * nguoc lai tra lai id
         */
        if ($category->getName() != 'Default Category') {
            if ($parentId > 0) {
                return $this->getCateParentId($parentId);
            } else {
                $Cate = Mage::getModel('catalog/category')->load($catID);
                $ID = $Cate->getId();
                return $ID;
            }
        } else {
            $Cate = Mage::getModel('catalog/category')->load($catID);
            $ID = $Cate->getId();
            return $ID;
        }
    }

}
