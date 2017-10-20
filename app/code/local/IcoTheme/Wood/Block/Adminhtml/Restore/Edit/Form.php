<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php
class IcoTheme_Wood_Block_Adminhtml_Restore_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $isElementDisabled = false;
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('adminhtml')->__('Restore Parameters')));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => $isElementDisabled
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => 0
            ));
        }

        $fieldset->addField('deactivate', 'checkbox', array(
            'name'      => 'deactivate',
            'label'     => Mage::helper('cms')->__('Deactivate Theme'),
            'title'     => Mage::helper('cms')->__('Deactivate Theme'),
            'required'  => false,
            'value'    => '1',
            'after_element_html' => 'Check it if you want to deactivate the theme',
            'disabled'  => $isElementDisabled
        ));

        $fieldset->addField('demo_layout', 'select', array(
            'name'      => 'demo_layout',
            'label'     => Mage::helper('cms')->__('Layout restore'),
            'title'     => Mage::helper('cms')->__('Layout restore'),
            'disabled'  => false,
            'values'             => array(
                array(
                    'value' => 'default',
                    'label' => Mage::helper('cms')->__('Default'),
                ),
            ),
        ));

        $fieldset->addField('cms_block', 'checkbox', array(
            'name'      => 'cms_block',
            'label'     => Mage::helper('cms')->__('Restore Cms Blocks'),
            'title'     => Mage::helper('cms')->__('Restore Cms Blocks'),
            'required'  => false,
            'value'    => '1',
            'after_element_html' => 'Check to restore all demo blocks',
            'note' => Mage::helper('cms')->__('All changes you made in these blocks will be lost'),
            'disabled'  => $isElementDisabled
        ));

        $fieldset->addField('cms_page', 'checkbox', array(
            'name'      => 'cms_page',
            'label'     => Mage::helper('cms')->__('Restore Cms Pages'),
            'title'     => Mage::helper('cms')->__('Restore Cms Pages'),
            'required'  => false,
            'value'    => '1',
            'after_element_html' => 'Check to restore all demo pages',
            'note' => Mage::helper('cms')->__('All changes you made in these pages will be lost'),
            'disabled'  => $isElementDisabled
        ));

        $fieldset->addField('widget', 'checkbox', array(
            'name'      => 'widget',
            'label'     => Mage::helper('cms')->__('Restore Widget'),
            'title'     => Mage::helper('cms')->__('Restore Widget'),
            'required'  => false,
            'value'    => '1',
            'after_element_html' => 'Check to restore all demo pages',
            'note' => Mage::helper('cms')->__('All changes you made in these pages will be lost'),
            'disabled'  => $isElementDisabled
        ));

        $fieldset->addField('config', 'checkbox', array(
            'name'      => 'config',
            'label'     => Mage::helper('cms')->__('Clear Configuration'),
            'title'     => Mage::helper('cms')->__('Clear Configuration'),
            'required'  => false,
            'value'    => '1',
            'after_element_html' => 'Check to restore the theme default settings (File with custom colors won\'t be restored. You have to generate it again by yourself.)',
            'disabled'  => $isElementDisabled
        ));


        $form->setAction($this->getUrl('*/*/restore'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
