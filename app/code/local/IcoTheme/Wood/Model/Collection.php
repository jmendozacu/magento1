<?php

/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
class IcoTheme_Wood_Model_Collection extends Mage_Core_Model_Abstract
{
    public function appendType($collection)
    {
        $entityIds = array();
        foreach ($collection->getItems() as $_itemId => $_item) {
            $entityIds[] = $_item->getEntityId();
        }

        if (sizeof($entityIds) == 0) {
            return $this;
        }
        $readonce = Mage::getSingleton('core/resource')->getConnection('core_read');
        $optionTable = $this->getTableName('catalog/product_option');
        foreach ($collection->getItems() as $_item) {
            $select = $readonce->fetchAll('SELECT type FROM ' . $optionTable . ' WHERE product_id=' . $_item->getEntityId());
            $_item->setOptionsType($select);
        }

        return $this;
    }

    public function getTableName($modelEntity)
    {
        try {
            $table = Mage::getSingleton('core/resource')->getTableName($modelEntity);
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }
        return $table;
    }
}
