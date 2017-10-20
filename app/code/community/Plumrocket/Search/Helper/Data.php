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

 
class Plumrocket_Search_Helper_Data extends Plumrocket_Search_Helper_Main
{
	protected $_words;
	protected $_indexStatusChanged = false;

	public function getQueryText()
	{
		return Mage::helper('catalogsearch')->getQueryText();
	}

	public function getQueryCategory($default = null)
	{
		$categoryId = (int)Mage::app()->getRequest()->getParam('cat');
		if($categoryId <= 0) {
			$categoryId = $default;
		}
		return $categoryId;
	}

	public function getQueryWords($queryText = null)
	{
		$query = Mage::helper('catalogsearch')->getQuery();
		if(is_null($queryText)) {
			$queryText = $this->getQueryText();
		}
		return Mage::helper('core/string')->splitWords($queryText, true, $query->getMaxQueryWords());
	}

	public function getResultUrl($queryText = null, $categoryId = null)
    {
    	if(is_null($queryText)) {
			$queryText = $this->getQueryText();
		}
		if(is_null($categoryId)) {
			$categoryId = $this->getQueryCategory();
		}
        return $this->_getUrl('catalogsearch/result', array(
            '_query' => array(
            	Mage::helper('catalogsearch')->getQueryParamName() => $queryText,
            	'cat' => $categoryId
            ),
            '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
    }

    public function moduleEnabled($store = null)
	{
		return (bool)Mage::getStoreConfig('psearch/general/enable', $store);
	}

    public function getCategoryTree()
    {
    	$categories = Mage::getModel('psearch/system_config_source_categories')
    		->setSkip()
    		->setDepthStr('&nbsp;&nbsp;&nbsp;')
			->toArray();

		if($categories) {
			$categories = array(0 => $this->__('All')) + $categories;
		}

	    return $categories;
    }

    public function disableExtension()
	{
		$resource = Mage::getSingleton('core/resource');
	    $connection = $resource->getConnection('core_write');
		$connection->delete($resource->getTableName('core/config_data'), array($connection->quoteInto('path IN (?)', array('psearch/general/enable', 'psearch/general/filter_categories_exclude'))));
	    $config = Mage::getConfig();
	    $config->reinit();
	    Mage::app()->reinitStores();
	}

	public function splitWords($str = null)
	{
		if(is_null($this->_words)) {
			if(is_null($str)) {
				$str = $this->getQueryText();
			}
			$maxQueryWords = Mage::getStoreConfig(Mage_CatalogSearch_Model_Query::XML_PATH_MAX_QUERY_WORDS);
			$this->_words = Mage::helper('core/string')->splitWords($str, true, $maxQueryWords);
		}
		
		return $this->_words;
	}

    public function setAttributeSearchable($attributeId, $priority)
    {
    	$resource = Mage::getSingleton('core/resource');
	    $connection = $resource->getConnection('core_write');

	    $where = is_array($attributeId)? $connection->quoteInto('attribute_id IN (?)', $attributeId) : $connection->quoteInto('attribute_id = ?', $attributeId);
		$connection->update($resource->getTableName('catalog/eav_attribute'), array('is_searchable' => ($priority > 0? 1 : 0), 'psearch_priority' => $priority), array($where));
		
		// Mage::getSingleton('psearch/resource_catalogSearch_fulltext')->updatePriority($attributeId, $priority);
		if(!$this->_indexStatusChanged) {
			Mage::getSingleton('index/process')->load('catalogsearch_fulltext', 'indexer_code')->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
			$this->_indexStatusChanged = true;
		}
		return true;
    }

    public function getAttributesPriority()
    {
    	$priority = array();

        $collection = Mage::getResourceModel('catalog/product_attribute_collection')
            ->addVisibleFilter()
            ->addIsSearchableFilter();

        foreach ($collection as $item) {
        	$priority[ $item->getId() ] = $item->getPsearchPriority();
        }

    	return $priority;
    }

}	 