<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_InstaFeed_Block_Widget extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
    const CACHE_GROUP = 'ico_instafeed';

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
        );
    }

    protected function _beforeToHtml()
    {
        if ($this->getTemplate() == 'icotheme/instafeed/default.phtml') {
            $this->setTemplate('icotheme/instafeed/feed.phtml');
        }
        return parent::_beforeToHtml();
    }

    public function getConfig($name, $param = null)
    {
        switch ($name) {
            case 'widget_title':
                return $this->escapeHtml($this->getData('widget_title'));
                break;
            case 'column':
                return is_numeric($this->getData('column')) ? (int)$this->getData('column') : 4;
                break;
            case 'classes':
                return $this->getData('classes') . ($this->getData('animate') ? ' ' . $this->getData('animation') : '');
                break;
            case 'show_by':
                return $this->getData('show_by');
                break;
            case 'clientid':
                return $this->getData('clientid');
                break;
            case 'accesstoken':
                return $this->getData('accesstoken');
                break;
            case 'userid':
                return $this->getData('userid');
                break;
            case 'tag_name':
                return $this->getData('tag_name');
                break;
            case 'location_id':
                return $this->getData('location_id');
                break;
            case 'sortby':
                return $this->getData('sortby');
                break;
            case 'links':
                return (bool)$this->getData('links');
                break;
            case 'limit':
                return $this->getData('limit');
                break;
            case 'resolution':
                return $this->getData('resolution');
                break;
            case 'custom_template':
                return $this->getData('custom_template');
                break;
            default:
                return $this->getData($name);
        }
    }
}
