<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_Brands_Block_Widget extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
    const CACHE_GROUP = 'ico_brands';

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
            $this->getData('attribute_options'),
            $this->getData('attribute'),
            $this->getData('scroll'),
            $this->getData('column'),
        );
    }

    protected function _beforeToHtml()
    {
        if ($this->getTemplate() == 'icotheme/brands/default.phtml') {
            $this->setTemplate('icotheme/brands/brands.phtml');
        }
        return parent::_beforeToHtml();
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
        $helper = Mage::helper('core');
        switch ($name) {
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
                    'navigationText' => array($this->getData('navigation_prev'), $this->getData('navigation_next')),
                    'engineSrc' => Mage::getBaseUrl('js') . 'icotheme/jquery/plugins/owl-carousel/owl.carousel.js'
                ));
                break;
            case 'widget_title':
                return $this->escapeHtml($this->getData('widget_title'));
                break;
            case 'id':
                return $helper->uniqHash(is_string($param) ? $param : 'brands-');
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
