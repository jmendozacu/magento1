<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_IndexController extends Mage_Core_Controller_Front_Action
{
    public function productsAction()
    {
        //if (!$this->_validateFormKey()) return null;
        if (!$this->getRequest()->isPost()) return null;

        $type = $this->getRequest()->getPost('type');
        $value = $this->getRequest()->getPost('value');

        if (!$type && !$value) return null;

        $limit = $this->getRequest()->getPost('limit', 10);
        $carousel = $this->getRequest()->getPost('carousel', 0);
        $column = $this->getRequest()->getPost('column', 4);
        $row = $this->getRequest()->getPost('row', 1);
        $cid = $this->getRequest()->getPost('cid');
        $template = $this->getRequest()->getPost('template', 'icotheme/widgetproducts/collection.phtml');

        /* @var $helper IcoTheme_WidgetProducts_Helper_Data */
        $helper = Mage::helper('widgetproducts');
        /* @var $block IcoTheme_WidgetProducts_Block_Widget_Collection */
        $block = $this->getLayout()->createBlock('widgetproducts/widget_collection');
        $params = array();

        if ($cid) {
            $params['category_ids'] = explode(',', $cid);
        }

        $block->setTemplate($template);
        $block->setData(array(
            'row' => $row,
            'column' => $column,
            'carousel' => $carousel,
            'collection' => $helper->getProductCollection($type, $value, $params, $limit)
        ));

        return $this->getResponse()->setBody($block->toHtml());
    }
}
