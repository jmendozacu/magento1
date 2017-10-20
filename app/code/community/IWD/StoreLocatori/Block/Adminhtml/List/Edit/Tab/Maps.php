<?php
class IWD_StoreLocatori_Block_Adminhtml_List_Edit_Tab_Maps extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface{
	
		
	
    protected function _prepareForm(){
    	
        /* @var $model IWD_StoreLocatori_Model_Stores */
        $model = Mage::registry('storelocatori_store');

        
        $isElementDisabled = false;

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('storelocatori')->__('Google Maps')));

        
        
        $fieldset->addField('latitude', 'text', array(
        		'name'      => 'latitude',
        		'label'     => Mage::helper('storelocatori')->__('Latitude'),
        		'title'     => Mage::helper('storelocatori')->__('Latitude'),
        		'required'  => true,
        		'disabled'  => $isElementDisabled
        ));
        
        
        $fieldset->addField('longitude', 'text', array(
        		'name'      => 'longitude',
        		'label'     => Mage::helper('storelocatori')->__('Longitude'),
        		'title'     => Mage::helper('storelocatori')->__('Longitude'),
        		'required'  => true,
        		'disabled'  => $isElementDisabled
        ));
        
        $field = $fieldset->addField('auto', 'button', array(
        		'name'      => 'auto',
        ));
        
        $field->setRenderer($this->getLayout()->createBlock('storelocatori/adminhtml_list_render_button'));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('storelocatori')->__('Google Maps Data');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('storelocatori')->__('Google Maps Data');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

}
