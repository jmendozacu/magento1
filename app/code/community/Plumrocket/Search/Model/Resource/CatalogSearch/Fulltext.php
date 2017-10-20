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


class Plumrocket_Search_Model_Resource_CatalogSearch_Fulltext extends Mage_CatalogSearch_Model_Resource_Fulltext
{

    protected $_firstProcess = true;
    protected $_skuAttributeId;

    /**
     * Prepare results for query
     *
     * @param Mage_CatalogSearch_Model_Fulltext $object
     * @param string $queryText
     * @param Mage_CatalogSearch_Model_Query $query
     * @return Mage_CatalogSearch_Model_Resource_Fulltext
     */
    public function prepareResult($object, $queryText, $query)
    {
        if(!Mage::helper('psearch')->moduleEnabled()) {
            return parent::prepareResult($object, $queryText, $query);
        }

        if (Mage::getSingleton('plumbase/observer')->customer() != Mage::getSingleton('plumbase/product')->currentCustomer()) {
            return parent::prepareResult($object, $queryText, $query);
        }

        $adapter = $this->_getWriteAdapter();
        if (!$query->getIsProcessed()) {
            $searchType = $object->getSearchType($query->getStoreId());

            $preparedTerms = Mage::getResourceHelper('catalogsearch')
                ->prepareTerms($queryText, $query->getMaxQueryWords());

            $bind = array();
            $like = array();
            $likeCond  = '';
            if ($searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_LIKE
                || $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE
            ) {
                $helper = Mage::getResourceHelper('core');
                $words = Mage::helper('psearch')->splitWords($queryText);
                foreach ($words as $word) {
                    $like[] = $helper->getCILike('s.data_index', $word, array('position' => 'any'));
                }
                if ($like) {
                    if(!$likeSep = Mage::helper('psearch/config')->getLikeSeparator()) {
                        $likeSep = 'OR';
                    }
                    $likeCond = '(' . join(" {$likeSep} ", $like) . ')';
                }
            }
            $mainTableAlias = 's';
            $fields = array(
                'query_id' => new Zend_Db_Expr($query->getId()),
                'product_id',
            );
            $select = $adapter->select()
                // ->from(array($mainTableAlias => new Zend_Db_Expr('(SELECT * FROM '. $this->getExtMainTable() .' ORDER BY `priority` ASC)')), $fields)
                ->from(array($mainTableAlias => $this->getExtMainTable()), $fields)
                ->joinInner(array('e' => $this->getTable('catalog/product')),
                    'e.entity_id = s.product_id',
                    array())
                ->where($mainTableAlias.'.store_id = ?', (int)$query->getStoreId());

            if ($searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_FULLTEXT
                || $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE
            ) {
                $bind[':query'] = implode(' ', $preparedTerms[0]);
                $where = new Zend_Db_Expr('MATCH ('.$mainTableAlias.'.data_index) AGAINST (:query)');
                $relevance = new Zend_Db_Expr("(1000 / MIN(`{$mainTableAlias}`.`priority`)) + MATCH (`{$mainTableAlias}`.`data_index`) AGAINST (:query)");
                $select->columns(array('relevance' => $relevance));
            }

            if ($likeCond != '' && $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE) {
                    $where .= ($where ? ' OR ' : '') . $likeCond;
            } elseif ($likeCond != '' && $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_LIKE) {
                $select->columns(array('relevance' => new Zend_Db_Expr("1000 / MIN(`{$mainTableAlias}`.`priority`)")));
                $where = $likeCond;
            }

            if ($where != '') {
                $select->where($where);
            }

            // Check priority.
            $select->group("{$mainTableAlias}.product_id");
            // $select->order("{$mainTableAlias}.priority ASC");

            $sql = $adapter->insertFromSelect($select,
                $this->getTable('catalogsearch/result'),
                array(),
                Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE);
            try{
                $adapter->query($sql, $bind);
            }catch(Exception $e) {}

            $query->setIsProcessed(1);
        }

        return $this;
    }

    public function getExtMainTable()
    {
        return Mage::getSingleton('core/resource')->getTableName('psearch/fulltext');
    }

    public function updatePriority($attributeId, $priority)
    {
        if(is_numeric($attributeId) && $attributeId > 0 && is_numeric($priority)) {
            $this->_getWriteAdapter()->query('UPDATE `'. $this->getExtMainTable() .'` SET `priority` = '. $this->_preparePriority($priority) .' WHERE `attribute_id` = '. $attributeId);
            return true;
        }
    }

    protected function _prepareProductIndex($indexData, $productData, $storeId)
    {
        $priority = Mage::helper('psearch')->getAttributesPriority();
        
        if($this->_firstProcess) {
            $this->_getWriteAdapter()->query('DELETE FROM `'. $this->getExtMainTable() .'` WHERE `attribute_id` <= 0 OR `attribute_id` NOT IN('. implode(',', array_keys($priority)) .')');

            $this->_skuAttributeId = Mage::getResourceModel('eav/entity_attribute')
                    ->getIdByCode(Mage_Catalog_Model_Product::ENTITY, 'sku');

            $config = Mage::helper('psearch/config');

            if($config->searchByTags()) {
                $tagCollection = Mage::getSingleton('tag/tag')->getCollection()
                    ->joinRel();

                $select = $tagCollection->getSelect()
                        ->reset(Zend_Db_Select::COLUMNS)
                        ->columns(array(
                            'relation.product_id',
                            'relation.store_id',
                            'attribute_id' => new Zend_Db_Expr('(-`relation`.`tag_relation_id`)'),
                            'priority' => new Zend_Db_Expr($config->getTagsPriority()),
                            'data_index' => 'main_table.name',
                            'fulltext_id' => new Zend_Db_Expr('NULL'),
                        ))
                        ->where('`relation`.`product_id` != "NULL" AND `relation`.`store_id` != "NULL"')
                        ->reset(Zend_Db_Select::GROUP)
                        ->group(array('relation.tag_id', 'relation.product_id'));

                $adapter = $this->_getWriteAdapter();
                $sql = $adapter->insertFromSelect($tagCollection->getSelect(),
                        $this->getExtMainTable()/*,
                        array(),
                        Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE*/);

                try{
                    $adapter->query($sql);
                }catch(Exception $e) {}
            }

            $this->_firstProcess = false;
        }

        if($productData['type_id'] == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE
            || $productData['type_id'] == Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
            $_indexData = array();
            foreach ($indexData as $entityId => $attributeData) {
                foreach ($attributeData as $attributeId => $attributeValue) {
                    $_attr = &$_indexData[ $productData['entity_id'] ][$attributeId];
                    $_attr .= (!empty($_attr)? '|': '') . $attributeValue;
                }
            }
        }else{
            $_indexData = $indexData;
        }

        $productIndexes = array();
        foreach ($_indexData as $entityId => $attributeData) {

            // Add sku to data for index.
            if ($entityId == $productData['entity_id'] && $this->_skuAttributeId > 0 && empty($attributeData[$this->_skuAttributeId])) {
                $attributeData[$this->_skuAttributeId] = $productData['sku'];
            }

            foreach ($attributeData as $attributeId => $attributeValue) {
                if(isset($priority[$attributeId])) {
                    if($value = $this->_getAttributeValue($attributeId, $attributeValue, $storeId)) {
                        $productIndexes[] = array(
                            'product_id'    => $entityId,
                            'store_id'      => $storeId,
                            'attribute_id'  => $attributeId,
                            'priority'      => $this->_preparePriority($priority[$attributeId]),
                            'data_index'    => $value,
                        );
                    }
                }
            }
        }
        
        if($productIndexes) {
            $this->_getWriteAdapter()->insertOnDuplicate($this->getExtMainTable(), $productIndexes, array('priority', 'data_index'));
        }
        
        $result = parent::_prepareProductIndex($indexData, $productData, $storeId);
        return $result;
    }

    protected function _preparePriority($priority)
    {
        return $priority > 0? $priority : 1000;
    }

}