<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Block_Widget extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
    const CACHE_GROUP = 'widgetproduct';

    protected $_productCollection;

    protected function _construct()
    {
        parent::_construct();
        $this->setData('cache_tags', array(self::CACHE_GROUP, Mage_Core_Block_Template::CACHE_GROUP));
    }

    public function getCacheLifetime()
    {
        return $this->getData('cache') ? (int)$this->getData('cache') : null;
    }

    public function getCacheKeyInfo()
    {
        return array(
            self::CACHE_GROUP,
            Mage::app()->getStore()->getId(),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
            $this->getData('widget_type'),
            $this->getData('widget_tab'),
            $this->getData('collections'),
            $this->getData('category_ids'),
            $this->getData('product_ids'),
            $this->getData('attribute'),
            $this->getData('attribute_options'),
            $this->getData('attribute_link'),
            $this->getData('mode'),
            $this->getData('limit'),
            $this->getData('scroll'),
            $this->getData('row'),
            $this->getData('column'),
            $this->getData('animate'),
        );
    }

    protected function _beforeToHtml()
    {
        if ($this->getTemplate() == 'icotheme/widgetproducts/default.phtml') {
            switch ($this->getData('widget_type')) {
                case 'product':
                    switch ($this->getData('mode')) {
                        case 'related':
                            $this->setTemplate('icotheme/widgetproducts/related.phtml');
                            break;
                        default:
                            $this->setTemplate('icotheme/widgetproducts/product.phtml');
                            break;
                    }
                    switch ($this->getData('widget_tab')) {
                        case 'categories':
                        case 'collections':
                            $this->setTemplate('icotheme/widgetproducts/tab.phtml');
                            break;
                    }
                    break;
                case 'attribute':
                    $this->setTemplate('icotheme/widgetproducts/attribute.phtml');
                    break;
                case 'category':
                    $this->setTemplate('icotheme/widgetproducts/category.phtml');
                    break;
            }
        }
        return parent::_beforeToHtml();
    }

    public function getCategories()
    {
        if (!$this->getData('category_ids')) return array();

        $categories = array();
        foreach (explode(',', $this->getData('category_ids')) as $categoryId) {
            /* @var $category Mage_Catalog_Model_Category */
            $category = Mage::getModel('catalog/category')
                ->setStore(Mage::app()->getStore())
                ->load($categoryId, array('name', 'image', 'thumbnail'));
            if ($category->getId()) {
                $categories[] = array(
                    'url' => $category->getUrl(),
                    'label' => $category->getName(),
                    'image' => $category->getThumbnail() ? Mage::getBaseUrl('media') . 'catalog/category/' . $category->getThumbnail() : '',
                    'count' => $category->getProductCount()
                );
            }
        }

        return $categories;
    }

    public function getTabs()
    {
        $tabs = array();
        $type = $this->getData('widget_tab');
        $labels = explode('||', $this->getData('labels'));

        switch ($type) {
            case 'categories':
                $categoryIds = explode(',', $this->getData('category_ids'));
                foreach ($categoryIds as $index => $categoryId) {
                    /* @var $categoryModel Mage_Catalog_Model_Category */
                    $categoryModel = Mage::getModel('catalog/category')->load($categoryId, array('name'));
                    if ($categoryModel->getId()) {
                        $tabs[] = array(
                            'type' => 'category',
                            'id' => 'category-' . $categoryModel->getId(),
                            'label' => isset($labels[$index]) && $labels[$index] ? $labels[$index] : $categoryModel->getName(),
                            'value' => $categoryModel->getId()
                        );
                    }
                }
                break;
            case 'collections':
                $collectionNames = $this->getData('collections');
                if (!is_array($collectionNames)) $collectionNames = explode(',', $this->getData('collections'));
                foreach ($collectionNames as $index => $collectionName) {
                    $tabs[] = array(
                        'type' => 'collection',
                        'id' => 'collection-' . $collectionName,
                        'label' => isset($labels[$index]) && $labels[$index] ? $labels[$index] : $collectionName,
                        'value' => $collectionName
                    );
                }
                break;
        }

        return $tabs;
    }

    public function checkCollectionSize($type, $value)
    {
        $collection = $this->_getProductCollection($type, $value);
        return $collection->count();
    }

    public function renderCollection($type, $value, $template = 'icotheme/widgetproducts/collection.phtml')
    {
        /* @var $block IcoTheme_WidgetProducts_Block_Widget_Collection */
        $block = $this->getLayout()->createBlock('widgetproducts/widget_collection');

        $block->setData(array(
            'row' => $this->getConfig('row'),
            'column' => $this->getConfig('column'),
            'carousel' => $this->getConfig('scroll'),
            'collection' => $this->_getProductCollection($type, $value)
        ));
        $block->setTemplate($template);

        return $block->toHtml();
    }

    protected function _getProductCollection($type, $value)
    {
        if (!$this->_productCollection) {
            /* @var $helper IcoTheme_WidgetProducts_Helper_Data */
            $helper = Mage::helper('widgetproducts');
            $params = array();

            if ($this->getData('category_ids')) {
                $params['category_ids'] = explode(',', $this->getData('category_ids'));
            }
            if ($this->getData('product_ids')) {
                $params['product_ids'] = explode(',', $this->getData('product_ids'));
            }

            $this->_productCollection = $helper->getProductCollection($type, $value, $params, $this->getLimit());
        }

        return $this->_productCollection;
    }

    public function getLimit()
    {
        return is_numeric($this->getData('limit')) ? $this->getData('limit') : 10;
    }

    public function getAttibuteOptions()
    {
        $showOptions = explode(',', $this->getData('attribute_options'));
        list($attributeId, $attributeCode) = explode(',', $this->getData('attribute'));

        $optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
            ->setAttributeFilter($attributeId)
            ->setStoreFilter()
            ->load();

        $options = array();
        foreach ($optionCollection as $option) {
            if (in_array($option->getId(), $showOptions)) {
                if ($this->getData('attribute_link')) {
                    $options[] = array(
                        'id' => $option->getId(),
                        'label' => $option->getValue(),
                        'image' => $option->getImage() ?
                                (
                                strpos($option->getImage(), 'http') === 0 ?
                                    $option->getImage() :
                                    Mage::getBaseUrl('media') . $option->getImage()
                                ) :
                                null,
                        'link' => $this->getUrl('catalogsearch/result/index', array('q' => $option->getValue()))
                    );
                } else {
                    $options[] = array(
                        'id' => $option->getId(),
                        'label' => $option->getValue(),
                        'image' => $option->getImage() ?
                                (
                                strpos($option->getImage(), 'http') === 0 ?
                                    $option->getImage() :
                                    Mage::getBaseUrl('media') . $option->getImage()
                                ) :
                                null
                    );
                }
            }
        }

        return $options;
    }

    public function getConfig($name, $param = null)
    {
        /* @var $helper Mage_Core_Helper_Data */
        $helper = Mage::helper('core');

        switch ($name) {
            case 'enableCoundown':
                return (bool)$this->getData('countdown');
                break;
            case 'countdown':
                return $helper->jsonEncode(array(
                    'enable' => (bool)$this->getData('countdown'),
                    'yearText' => Mage::helper('widgetproducts')->__('years'),
                    'monthText' => Mage::helper('widgetproducts')->__('months'),
                    'weekText' => Mage::helper('widgetproducts')->__('weeks'),
                    'dayText' => Mage::helper('widgetproducts')->__('days'),
                    'hourText' => Mage::helper('widgetproducts')->__('hours'),
                    'minText' => Mage::helper('widgetproducts')->__('mins'),
                    'secText' => Mage::helper('widgetproducts')->__('secs'),
                    'yearSingularText' => Mage::helper('widgetproducts')->__('year'),
                    'monthSingularText' => Mage::helper('widgetproducts')->__('month'),
                    'weekSingularText' => Mage::helper('widgetproducts')->__('week'),
                    'daySingularText' => Mage::helper('widgetproducts')->__('day'),
                    'hourSingularText' => Mage::helper('widgetproducts')->__('hour'),
                    'minSingularText' => Mage::helper('widgetproducts')->__('min'),
                    'secSingularText' => Mage::helper('widgetproducts')->__('sec')
                ));
                break;
            case 'carousel':
                return $helper->jsonEncode(array(
                    'enable' => (bool)$this->getData('scroll'),
                    'pagination' => (bool)$this->getData('paging'),
                    'autoPlay' => is_numeric($this->getData('autoplay')) ? (int)$this->getData('autoplay') : false,
                    'items' => is_numeric($this->getData('column')) ? (int)$this->getData('column') : 4,
                    'singleItem' => $this->getData('column') == 1,
                    'lazyLoad' => true,
                    'lazyEffect' => false,
                    'addClassActive' => true,
                    'navigation' => (bool)$this->getData('navigation'),
                    'navigationText' => array($this->getData('navigation_prev'), $this->getData('navigation_next'))
                ));
                break;
            case 'widget_title':
                return $this->escapeHtml($this->getData('widget_title'));
                break;
            case 'id':
                return $helper->uniqHash(is_string($param) ? $param : 'block-');
                break;
            case 'row':
                return is_numeric($this->getData('row')) ? (int)$this->getData('row') : 1;
                break;
            case 'column':
                return is_numeric($this->getData('column')) ? (int)$this->getData('column') : 4;
                break;
            case 'limit':
                return is_numeric($this->getData('limit')) ? (int)$this->getData('limit') : 1;
                break;
            case 'classes':
                return $this->getData('classes') . ($this->getData('animate') ? ' ' . $this->getData('animation') : '');
                break;
            default:
                return $this->getData($name);
        }
    }
}
