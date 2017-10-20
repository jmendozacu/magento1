<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetBlocks_Block_Widget extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
    const CACHE_GROUP = 'ico_widgetblocks';

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
            $this->getData('block_ids'),
            $this->getData('scroll'),
            $this->getData('column'),
            $this->getData('animate'),
        );
    }

    protected function _beforeToHtml()
    {
        if ($this->getTemplate() == 'icotheme/widgetblocks/default.phtml') {
            $this->setTemplate('icotheme/widgetblocks/block.phtml');
        }
        return parent::_beforeToHtml();
    }

    public function getBlocks()
    {
        $blocks = array();
        $blockIds = explode(',',$this->getData('block_ids'));
        $layout = $this->getLayout();
        $storeId = Mage::app()->getStore()->getId();
        foreach ($blockIds as $blockId) {
            $collection = Mage::getResourceModel('cms/block_collection')
                ->addFieldToFilter('identifier', array('eq' => $blockId));
            if ($collection->count()) {
                $block = $collection->getFirstItem();
                $block->load($block->getId());
                $storeIds = $block->getStoreId();
                if ($block->getIsActive() && (in_array($storeId, $storeIds) || in_array(0, $storeIds))) {
                    $blocks[] = array(
                        'class' => isset($classes[$blockId]) ? $classes[$blockId] : '',
                        'content' => $layout->createBlock('cms/block')->setStoreId()->setBlockId($blockId)->toHtml()
                    );
                }
            }
        }
        return $blocks;
    }

    public function getConfig($name, $param = null)
    {
        /* @var $helper Mage_Core_Helper_Data */
        $helper = Mage::helper('core');
        switch ($name) {
            case 'carousel':
                return $helper->jsonEncode(array(
                    'enable' => (bool)$this->getData('scroll'),
                    'pagination' => (bool)$this->getData('paging'),
                    'transitionStyle' => $this->getData('animation') ? 'fade' : false,
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
            case 'column':
                return is_numeric($this->getData('column')) ? (int)$this->getData('column') : 4;
                break;
            case 'classes':
                return $this->getData('classes') . ($this->getData('animate') ? ' ' . $this->getData('animation') : '');
                break;
            default:
                return $this->getData($name);
        }
    }
}
