<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please 
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_Search
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


class Plumrocket_Search_Model_System_Config_Source_Categories
{

    protected $_options = null;
    protected $_skip = false;
    protected $_depthStr = '';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_getOptions();
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $options = array();
        foreach ($this->_getOptions() as $option) {
            $options[ $option['value'] ] = $option['label'];
        }

        return $options;
    }

    protected function _getOptions()
    {
        if(is_null($this->_options)) {
            $options = array();
            $this->_getCategories(null, $options);
            if(!$this->getSkip()) {
                $options = array_merge(array(array('style' => '',
                                            'value' => 0,
                                            'label' => ' ')),
                                        $options);
            }
            $this->_options = $options;
        }

        return $this->_options;
    }

    protected function _getCategories($categories = null, &$options = array(), $level = 0)
    {
        $config = Mage::helper('psearch/config');
        $exclude = $config->getFilterCategoriesExclude();
        $depth = $config->getFilterCategoriesDepth();
        if(!$depth || $level >= $depth) {
            return;
        }

        if(is_null($categories)) {

            $byRequest = !$this->getSkip();
            if($byRequest) {
                $request = Mage::app()->getRequest();

                if($storeCode = $request->getParam('store')) {
                    $defaultStoreId = Mage::getModel('core/store')->load($storeCode, 'code')->getId();
                }elseif($websiteCode = $request->getParam('website')) {
                    if($website = Mage::app()->getWebsite($websiteCode)) {
                        $rootcatId = array();
                        foreach ($website->getStores() as $_store) {
                            $rootcatId[] = $_store->getRootCategoryId();
                        }
                        $rootcatId = array_unique($rootcatId);
                    }
                }else{
                    $rootcatId = 1;
                }

            }else{

                $defaultStoreId = Mage::app()
                    ->getWebsite( $byRequest? $websiteCode : null )
                    ->getDefaultGroup()
                    ->getDefaultStoreId();

                if(!$defaultStoreId) {
                    $websites = Mage::app()->getWebsites(true);
                    if(!empty($websites[1])) {
                        $defaultStoreId = $websites[1]
                            ->getDefaultGroup()
                            ->getDefaultStoreId();
                    }
                }

                if(!$defaultStoreId) {
                    $defaultStoreId = 1;
                }
            }

            if(!empty($rootcatId) && is_array($rootcatId)) {
                $categories = Mage::getModel('catalog/category')->getCategories(1);
                foreach ($categories as $key => $category) {
                    if(!in_array($category->getId(), $rootcatId)) {
                        unset($categories[$key]);
                    }
                }
            }elseif(!empty($rootcatId)) {
                $categories = Mage::getModel('catalog/category')->getCategories($rootcatId);
            }elseif(!empty($defaultStoreId)) {
                $rootcatId = Mage::app()->getStore($defaultStoreId)->getRootCategoryId();
                $categories = Mage::getModel('catalog/category')->getCategories($rootcatId);
            }

        }
        
        foreach($categories as $category) {
            if($this->getSkip() && in_array($category->getId(), $exclude)) {
                continue;
            }
            if($level >= 0) {
                $options[] = array('style' => 'padding-left: '. (3+20*$level) .'px;', 'value' => $category->getId(), 'label' => str_repeat($this->_depthStr, $level) . $category->getName());
            }
            if($category->hasChildren()) {
                $this->_getCategories($category->getChildren(), $options, $level+1);
            }
        }
        
    }

    public function setSkip($flag = true)
    {
        $this->_skip = (bool)$flag;
        return $this;
    }

    public function getSkip()
    {
        return $this->_skip;
    }

    public function setDepthStr($str)
    {
        $this->_depthStr = (string)$str;
        return $this;
    }

}