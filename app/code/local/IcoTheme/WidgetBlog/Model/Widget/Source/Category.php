<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetBlog_Model_Widget_Source_Category
{
    public function toOptionArray()
    {
        if (Mage::helper('core')->isModuleEnabled('AW_Blog')) {
            $collection = Mage::getModel('blog/cat')->getCollection();
            $cats = array();
            foreach ($collection as $item) {
                $cats[] = array(
                    'value' => $item->getCatId(),
                    'label' => $item->getTitle()
                );
            }
        }
        return $cats;
    }
}