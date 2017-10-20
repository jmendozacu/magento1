<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Block_Adminhtml_Catalog_Category_Widget_Chooser extends Mage_Adminhtml_Block_Catalog_Category_Widget_Chooser
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('icotheme/widgetproducts/catalog/category/widget/tree.phtml');
        $this->_withProductCount = false;
    }

    public function getLoadTreeUrl($expanded = null)
    {
        return $this->getUrl('adminhtml/catalog_category_widget/categoriesJson', array(
            '_current' => true,
            'uniq_id' => $this->getId(),
            'use_massaction' => $this->getUseMassaction()
        ));
    }
}