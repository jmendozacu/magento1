<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetBlog_Block_Widget extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
    const CACHE_GROUP = 'ico_widgetblog';
    protected static $_helper;
    protected $_productCollection;

    protected function _construct()
    {
        parent::_construct();
        if (!self::$_helper && Mage::helper('core')->isModuleEnabled('AW_Blog')) {
            self::$_helper = Mage::helper('blog');
        }
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
            $this->getData('cat_ids')
        );
    }

    protected function _beforeToHtml()
    {
        if ($this->getTemplate() == 'icotheme/widgetblog/default.phtml') {
            $this->setTemplate('icotheme/widgetblog/blog.phtml');
        }
        return parent::_beforeToHtml();
    }

    public function getBlog()
    {
        if (Mage::helper('core')->isModuleEnabled('AW_Blog')) {
            $catids = (array)$this->getData('cat_ids');
            $collection = Mage::getModel('blog/blog')->getCollection()
                ->addPresentFilter()
                ->addEnableFilter(AW_Blog_Model_Status::STATUS_ENABLED)
                ->addStoreFilter()
                ->joinComments()
                ->addCatsFilter(implode(',', $catids))
                ->setOrder('update_time', 'desc');
            $collection->setPageSize((int)$this->getData('limit'));
            foreach ($collection as $item) {
                $item->setPostContent($this->truncate(strip_tags($item->getShortContent()), $length = 100, $etc = ' ...'));
                $item->setAddress($this->getBlogUrl($item->getIdentifier()));
            }
            return $collection;
        }
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
                return $helper->uniqHash(is_string($param) ? $param : 'widget-');
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

    public function truncate($string, $chars = 50, $terminator = ' ...')
    {
        $cutPos = $chars - mb_strlen($terminator);
        $boundaryPos = mb_strrpos(mb_substr($string, 0, mb_strpos($string, ' ', $cutPos)), ' ');
        return mb_substr($string, 0, $boundaryPos === false ? $cutPos : $boundaryPos) . $terminator;
    }

    public function getBlogUrl($route = null, $params = array())
    {
        $blogRoute = self::$_helper->getRoute();
        if (is_array($route)) {
            foreach ($route as $item) {
                $item = urlencode($item);
                $blogRoute .= "/{$item}";
            }
        } else {
            $blogRoute .= "/{$route}";
        }

        foreach ($params as $key => $value) {
            $value = urlencode($value);
            $blogRoute .= "{$key}/{$value}/";
        }

        return $this->getUrl($blogRoute);
    }

}
