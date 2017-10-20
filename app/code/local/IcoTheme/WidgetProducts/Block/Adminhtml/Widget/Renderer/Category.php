<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetProducts_Block_Adminhtml_Widget_Renderer_Category
    extends Mage_Adminhtml_Block_Template
    implements Varien_Data_Form_Element_Renderer_Interface
{

    protected $_element;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('icotheme/widgetproducts/category.phtml');
        $this->id = Mage::helper('core')->uniqHash('tree');
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    public function setElement($element)
    {
        $this->_element = $element;
    }

    public function getElement()
    {
        return $this->_element;
    }

    public function getCategoriesChooserUrl()
    {
        return $this->getUrl('widgetproducts/adminhtml_widget_instance/categories', array('_current' => true));
    }

    public function getSelectedCategories()
    {
        return (array)$this->_selectedCategories;
    }
}