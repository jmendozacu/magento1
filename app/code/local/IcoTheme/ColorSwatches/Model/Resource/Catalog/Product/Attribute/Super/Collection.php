<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_ColorSwatches_Model_Resource_Catalog_Product_Attribute_Super_Collection
    extends Mage_Catalog_Model_Resource_Product_Type_Configurable_Attribute_Collection
{
    private $_eavAttributesJoined = false;
    private $_storeId = null;

    /**
     * Filter parent products to these IDs
     *
     * @param array $parentProductIds
     * @return $this
     */
    public function addParentProductsFilter(array $parentProductIds)
    {
        $this->addFieldToFilter('product_id', array('in' => $parentProductIds));
        return $this;
    }

    /**
     * Attach (join) info from eav_attribute table
     *
     * @return $this
     */
    public function attachEavAttributes()
    {
        if ($this->_eavAttributesJoined) {
            return;
        }

        $this->join(
            array('eav_attributes' => 'eav/attribute'),
            '`eav_attributes`.`attribute_id` = `main_table`.`attribute_id`'
        );

        $this->_eavAttributesJoined = true;
        return $this;
    }

    /**
     * Set store ID
     *
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * Retrieve Store Id
     *
     * @return int
     */
    public function getStoreId()
    {
        return (int)$this->_storeId;
    }

    /**
     * Bypass parent _afterLoad() -- parent depends on single product context
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        Mage_Core_Model_Resource_Db_Collection_Abstract::_afterLoad();
        $this->_loadOptionLabels();
        return $this;
    }

    /**
     * Load attribute option labels for current store and default (fallback)
     *
     * @return $this
     */
    protected function _loadOptionLabels()
    {
        if ($this->count()) {
            $select = $this->getConnection()->select()
                ->from(
                    array('attr' => $this->getTable('catalog/product_super_attribute')),
                    array(
                        'product_super_attribute_id' => 'attr.product_super_attribute_id',
                    ))
                ->join(
                    array('opt' => $this->getTable('eav/attribute_option')),
                    'opt.attribute_id = attr.attribute_id',
                    array(
                        'attribute_id' => 'opt.attribute_id',
                        'option_id' => 'opt.option_id',
                    ))
                ->join(
                    array('lab' => $this->getTable('eav/attribute_option_value')),
                    'lab.option_id = opt.option_id',
                    array(
                        'label' => 'lab.value',
                        'store_id' => 'lab.store_id',
                    ))
                ->where('attr.product_super_attribute_id IN (?)', array_keys($this->_items));

            $result = $this->getConnection()->fetchAll($select);
            foreach ($result as $data) {
                $item = $this->getItemById($data['product_super_attribute_id']);
                if (!is_array($labels = $item->getOptionLabels())) {
                    $labels = array();
                }
                $labels[$data['option_id']][$data['store_id']] = $data['label'];
                $item->setOptionLabels($labels);
            }
        }
        return $this;
    }
}
