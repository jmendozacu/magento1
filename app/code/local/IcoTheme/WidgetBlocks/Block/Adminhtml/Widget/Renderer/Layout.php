<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetBlocks_Block_Adminhtml_Widget_Renderer_Layout extends Mage_Adminhtml_Block_Template
{
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = "
<div id='layout_{$this->getData("target")}' class='layout-preview'></div>
<script type='text/javascript'>
    document.observe('dom:loaded', function(){
        window.layout_{$this->getData("target")}_object = new LayoutPreview('layout_{$this->getData("target")}', '{$this->getFieldsetId()}', '{$this->getData("target")}');
    });
    setTimeout(function(){
        window.layout_{$this->getData("target")}_object = new LayoutPreview('layout_{$this->getData("target")}', '{$this->getFieldsetId()}', '{$this->getData("target")}');
    }, 100);
</script>
";
        $element->setData('after_element_html', $html);
        return $element;
    }
}