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


class Plumrocket_Search_Block_Tooltip extends Mage_Catalog_Block_Product_Abstract
{
	protected $_products = null;
	protected $_categories = null;
	protected $_terms = null;
	protected $_queryText = null;

	public function _construct()
	{
		// Set query.
		$this->_queryText = $this->helper('psearch')->getQueryText();
		Mage::app()->getRequest()->setParam($this->helper('catalogsearch')->getQueryParamName(), $this->_queryText);
	}

	public function getProducts()
	{
		if(is_null($this->_products)) {
			$config = $this->helper('psearch/config');
			$categoryId = $this->helper('psearch')->getQueryCategory();

			$productCollection = Mage::getSingleton('catalogsearch/layer')
				->getProductCollection()
				->addAttributeToSelect(array('name', 'short_description', 'price', 'special_price', 'bundle_price', 'image'))
				->setOrder('relevance', 'desc')
				->setPageSize($config->getPSCount());

			// Filter by categories.
			/*if(!is_numeric($categoryId) || $categoryId <= 0) {
				$categoryId = Mage::app()->getStore()->getRootCategoryId();
			}*/
			if(is_numeric($categoryId) && $categoryId > 0) {
				if($cat = Mage::getSingleton('catalog/category')->load($categoryId)) {
					$productCollection->addCategoryFilter($cat);
				}
			}

			if($config->showPSRating()) {
				Mage::getModel('review/review')->appendSummary($productCollection);
			}

			$this->_products = $productCollection;
		}

		return $this->_products;
	}

	public function getCategories()
	{
		if(is_null($this->_categories)) {
			$config = $this->helper('psearch/config');

			if($productCollection = $this->getProducts()) {
				$categoryCollection = Mage::getSingleton('catalog/category')->getCollection()
					->addAttributeToSelect('name');

				$productCollection->addCountToCategories($categoryCollection);
				
				foreach ($categoryCollection as $cat) {
					if($cat->getProductCount() < 1) {
						$categoryCollection->removeItemByKey($cat->getId());
					}
				}

				$categories = $categoryCollection->getItems();
				usort($categories, create_function('$a, $b', 'return ($a->getProductCount() > $b->getProductCount())? -1 : 1;'));

				if($limit = $config->getCategorySuggestionCount()) {
					$categories = array_slice($categories, 0, $limit);
				}

			}else{
				$categories = false;
			}

			$this->_categories = $categories;
		}

		return $this->_categories;
	}

	public function getParentCategory($category)
	{
		$parent = $category->getParentCategory();
		if($parent && $parent->getLevel() > 1) {               
			return $parent;
		}
	}

	public function getTerms()
	{
		if(is_null($this->_terms)) {
			$config = $this->helper('psearch/config');
			$helperCore = Mage::getResourceHelper('core');
			$websiteId = Mage::app()->getWebsite()->getId();
			$storeId = Mage::app()->getStore()->getStoreId();

			$termsCollection = Mage::getResourceModel('catalogsearch/query_collection')
                ->setPopularQueryFilter(Mage::app()->getStore()->getId())
                ->setPageSize($config->getTermsSuggestionCount());

            $filters = array();
            foreach ($this->helper('psearch')->getQueryWords($this->_queryText) as $word) {
	            $filters[] = array('like' => $helperCore->addLikeEscape($word, array('position' => 'any')));
	        }
	        if($filters) {
	        	if($config->getLikeSeparator() === 'AND') {
	        		foreach ($filters as $filter) {
	        			$termsCollection->addFieldToFilter('query_text', $filter);
	        		}
	        	}else{
	        		$termsCollection->addFieldToFilter('query_text', $filters);
	        	}
	        }

            $this->_terms = $termsCollection;
		}

		return $this->_terms;
	}

	public function getProductCssClass()
	{
		$config = $this->helper('psearch/config');
		
		$class = '';
		$class .= $config->showPSThumbs() ? '' : ' no-photo';
		$class .= $config->showPSPrice() ? '' : ' no-price';
		$class .= $config->showPSRating() ? '' : ' no-rating';
		$class .= $config->showPSShortDescription() ? '' : ' no-description';

		return $class;
	}

	public function tipsWords($text)
	{
		$text = $this->escapeHtml($text);
		$helper = Mage::helper('psearch');
		if($words = $helper->splitWords()) {
			foreach ($words as &$word) {
				$word = preg_quote($this->escapeHtml($word));
			}
			$text = preg_replace('/('. implode('|', $words) .')/iu', '<span class="psearch-tips">\0</span>', $text);
		}
		
		return $text;
	}

}