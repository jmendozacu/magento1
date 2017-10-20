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

 
class Plumrocket_Search_Helper_Config extends Mage_Core_Helper_Abstract
{

	/* Search */

	public function getProductAttributes($store = null)
	{
		return explode(',', Mage::getStoreConfig('psearch/search/product_attributes', $store));
	}

	public function searchByTags($store = null)
	{
		return Mage::getStoreConfig('psearch/search/search_by_tags', $store);
	}

	public function getTagsPriority($store = null)
	{
		return 50;
	}

	public function enabledFilterCategories($store = null)
	{
		return Mage::getStoreConfig('psearch/search/filter_categories_enable', $store);
	}

	public function getFilterCategoriesExclude($store = null)
	{
		return explode(',', Mage::getStoreConfig('psearch/search/filter_categories_exclude', $store));
	}

	public function getFilterCategoriesDepth($store = null)
	{
		return 3;
	}

	public function getSearchMinLenght($store = null)
	{
		return Mage::helper('catalogsearch')->getMinQueryLength($store);
	}

	public function getQueryDelay($store = null)
	{
		return (int)Mage::getStoreConfig('psearch/search/query_delay', $store);
	}

	public function getLikeSeparator($store = null)
	{
		return strtoupper(Mage::getStoreConfig('psearch/search/like_separator', $store));
	}

	/* Product Suggestion Settings */

	public function enabledProductSuggestion($store = null)
	{
		return Mage::getStoreConfig('psearch/product_suggestion/enable', $store);
	}

	public function getPSCount($store = null)
	{
		return (int)Mage::getStoreConfig('psearch/product_suggestion/count', $store);
	}

	public function showPSThumbs($store = null)
	{
		return Mage::getStoreConfig('psearch/product_suggestion/thumbs_show', $store);
	}

	public function showPSPrice($store = null)
	{
		return Mage::getStoreConfig('psearch/product_suggestion/price_show', $store);
	}

	public function showPSRating($store = null)
	{
		return Mage::getStoreConfig('psearch/product_suggestion/rating_show', $store);
	}

	public function showPSShortDescription($store = null)
	{
		return Mage::getStoreConfig('psearch/product_suggestion/short_description_show', $store);
	}

	public function getPSShortDescriptionLenght($store = null)
	{
		return (int)Mage::getStoreConfig('psearch/product_suggestion/short_description_lenght', $store);
	}

	/* Keyword Suggestion Settings */

	public function enabledCategorySuggestion($store = null)
	{
		return Mage::getStoreConfig('psearch/keyword_suggestion/category_enable', $store);
	}

	public function getCategorySuggestionCount($store = null)
	{
		return (int)Mage::getStoreConfig('psearch/keyword_suggestion/category_count', $store);
	}

	public function enabledTermsSuggestion($store = null)
	{
		return Mage::getStoreConfig('psearch/keyword_suggestion/terms_enable', $store);
	}

	public function getTermsSuggestionCount($store = null)
	{
		return (int)Mage::getStoreConfig('psearch/keyword_suggestion/terms_count', $store);
	}

}
	 