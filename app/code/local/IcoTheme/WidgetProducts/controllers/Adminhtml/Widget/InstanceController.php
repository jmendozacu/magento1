<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Adminhtml_Widget_InstanceController extends Mage_Adminhtml_Controller_Action
{
    public function categoriesAction()
    {
        $selected = $this->getRequest()->getParam('selected', '');
        $chooser = $this->getLayout()
            ->createBlock('widgetproducts/adminhtml_catalog_category_widget_chooser')
            ->setUseMassaction(true)
            ->setId($this->getRequest()->getParam('id'))
            ->setSelectedCategories(explode(',', $selected));
        $this->setBody($chooser->toHtml());
    }

    public function productsAction()
    {
        $selected = $this->getRequest()->getParam('selected', '');
        $chooser = $this->getLayout()
            ->createBlock('widgetproducts/adminhtml_catalog_product_widget_chooser')
            ->setUseMassaction(true)
            ->setSelectedProducts(explode(',', $selected));
        /* @var $serializer Mage_Adminhtml_Block_Widget_Grid_Serializer */
        $serializer = $this->getLayout()->createBlock('adminhtml/widget_grid_serializer');
        $serializer->initSerializerBlock($chooser, 'getSelectedProducts', 'selected_products', 'selected_products');
        $this->setBody($chooser->toHtml() . $serializer->toHtml());
    }

    private function setBody($body)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($body);
        $this->getResponse()->setBody($body);
    }
}