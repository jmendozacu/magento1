<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_Brands_Block_Adminhtml_Widget_Renderer_Attribute_Option extends Mage_Adminhtml_Block_Template
{
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $targetId = $this->getFieldsetId() . '_' . $this->getConfig('target');
        $block = $this->getLayout()->createBlock('brands/adminhtml_widget_renderer_depend', '', array(
            'target' => $targetId,
            'url' => $this->getUrl('brands/adminhtml_widget_attribute/option'),
            'me' => $element->getHtmlId(),
            'value' => implode(',', (array)$element->getValue())
        ));
        $element->setData('after_element_html', $block->toHtml());
        return $element;
    }
}