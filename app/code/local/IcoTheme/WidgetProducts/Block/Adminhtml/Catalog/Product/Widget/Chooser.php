<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Block_Adminhtml_Catalog_Product_Widget_Chooser extends Mage_Adminhtml_Block_Catalog_Product_Widget_Chooser
{
    public function getGridUrl()
    {
        return $this->getUrl('adminhtml/catalog_product_widget/chooser', array(
            'products_grid' => true,
            '_current' => true,
            'uniq_id' => $this->getId(),
            'use_massaction' => $this->getUseMassaction()
        ));
    }

    public function getCheckboxCheckCallback()
    {
        if ($this->getUseMassaction()) {
            return "function (grid, element) {
                $(grid.containerId).fire('productNode:changed', {element: element});
            }";
        }
    }
}