<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_Wood_Block_Adminhtml_Activate_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $isElementDisabled = false;
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('adminhtml')->__('Theme Activate')));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('cms')->__('Store View'),
                'title' => Mage::helper('cms')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled' => $isElementDisabled
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => 0
            ));
        }

        $fieldset->addField('demo_layout', 'select', array(
            'name'      => 'demo_layout',
            'label'     => Mage::helper('cms')->__('Layout active'),
            'title'     => Mage::helper('cms')->__('Layout active'),
            'disabled'  => false,
            'values'             => array(
                array(
                    'value' => 'default',
                    'label' => Mage::helper('cms')->__('Default'),
                ),
            ),
        ));

        $fieldset->addField('cms_block', 'checkbox', array(
            'name' => 'cms_block',
            'label' => Mage::helper('cms')->__('Create Cms Blocks'),
            'title' => Mage::helper('cms')->__('Create Cms Blocks'),
            'required' => false,
            'value' => '1',
            'disabled' => $isElementDisabled
        ))->setIsChecked(1);

        $fieldset->addField('cms_page', 'checkbox', array(
            'name' => 'cms_page',
            'label' => Mage::helper('cms')->__('Create Cms Pages'),
            'title' => Mage::helper('cms')->__('Create Cms Pages'),
            'required' => false,
            'value' => '1',
            'disabled' => $isElementDisabled
        ))->setIsChecked(1);

        $fieldset->addField('widget', 'checkbox', array(
            'name' => 'widget',
            'label' => Mage::helper('cms')->__('Create Widgets'),
            'title' => Mage::helper('cms')->__('Create Widgets'),
            'required' => false,
            'value' => '1',
            'disabled' => $isElementDisabled
        ))->setIsChecked(1);

        $fieldset->addField('config', 'checkbox', array(
            'name'      => 'config',
            'label'     => Mage::helper('cms')->__('Active Configuration'),
            'title'     => Mage::helper('cms')->__('Active Configuration'),
            'required'  => false,
            'value'    => '1',
            'after_element_html' => 'Tick the checkbox to active the default theme settings and colors.',
            'disabled'  => $isElementDisabled
        ))->setIsChecked(1);


        $form->setAction($this->getUrl('*/*/activate'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
