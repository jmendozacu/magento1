<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetBlocks_Model_Widget_Source_Block
{
    public function toOptionArray()
    {
        $collection = Mage::getResourceModel('cms/block_collection')->load();
        $blocks = array();
        foreach ($collection as $item) {
            $blocks[] = array(
                'value' => $item->getIdentifier(),
                'label' => $item->getTitle()
            );
        }
        return $blocks;
    }
}