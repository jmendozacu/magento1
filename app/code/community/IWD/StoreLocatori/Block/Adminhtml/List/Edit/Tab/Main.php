<?php
class IWD_StoreLocatori_Block_Adminhtml_List_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface{
	
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('storelocatori/container.phtml');
	}
	
	public function getRegionsUrl()
	{
		return $this->getUrl('adminhtml/json/countryRegion');
	}
	
    protected function _prepareForm(){
    	
    	Mage::getModel('storelocatori/image')->clearCache();
    	
        /* @var $model IWD_StoreLocatori_Model_Stores */
        $model = Mage::registry('storelocatori_store');

        
        $isElementDisabled = false;

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('storelocatori')->__('Store Information')));

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', array(
                'name' => 'entity_id',
            ));
        }
        

       
        if (!Mage::app()->isSingleStoreMode()) {
        	$fieldset->addField('store_id', 'multiselect', array(
        			'name'      => 'stores[]',
        			'label'     => Mage::helper('cms')->__('Store View'),
        			'title'     => Mage::helper('cms')->__('Store View'),
        			'required'  => true,
        			'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
        	));
        }
        else {
        	$fieldset->addField('store_id', 'hidden', array(
        			'name'      => 'stores[]',
        			'value'     => Mage::app()->getStore(true)->getId()
        	));
        	$model->setStoreId(Mage::app()->getStore(true)->getId());
        }
        

        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('storelocatori')->__('Title'),
            'title'     => Mage::helper('storelocatori')->__('Title'),
            'required'  => true,
            'disabled'  => $isElementDisabled
        ));
        
        
        $fieldset->addField('is_active', 'select', array(
        		'label'     => Mage::helper('storelocatori')->__('Status'),
        		'title'     => Mage::helper('storelocatori')->__('Status'),
        		'name'      => 'is_active',
        		'required'  => true,
        		'options'   => $model->getAvailableStatuses(),
        		'disabled'  => $isElementDisabled,
        ));
        
        $fieldset->addField('position', 'text', array(
        		'name'      => 'position',
        		'label'     => Mage::helper('storelocatori')->__('Position'),
        		'title'     => Mage::helper('storelocatori')->__('Position'),
        		'required'  => false,
        		'disabled'  => $isElementDisabled
        ));
        
        
        if (!$model->getId()) {
        	$model->setData('is_active', $isElementDisabled ? '0' : '1');
        }
        
        $fieldset = $form->addFieldset('address_fieldset', array('legend'=>Mage::helper('storelocatori')->__('Address')));
        
        
        $fieldset->addField('country_id', 'select', array(
        		'name'      => 'country_id',
        		'label'     => Mage::helper('storelocatori')->__('Country'),
        		'title'     => Mage::helper('storelocatori')->__('Country'),
        		'required'  => true,
        		'values'    => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),
        		'disabled'  => $isElementDisabled
        ));
        if (!$model->getId()) {
        	$model->setCountryId('US');
        }
        $fieldset->addField('region', 'select', array(
        		'name'      => 'region',
        		'label'     => Mage::helper('storelocatori')->__('State'),
        		'title'     => Mage::helper('storelocatori')->__('State'),
        		'required'  => true,
        		'disabled'  => $isElementDisabled,
        ));
        
        
        $fieldset->addField('region_id', 'hidden', array(
        		'name'      => 'region_id',
        		'label'     => Mage::helper('storelocatori')->__('State ID'),
        		'title'     => Mage::helper('storelocatori')->__('State ID '),
        		'required'  => true,
        		'disabled'  => $isElementDisabled,
        ));
        
        
        $regionElement = $form->getElement('region');
		$regionElement->setRequired(true);
        if ($regionElement) {
        	$regionElement->setRenderer(Mage::getModel('adminhtml/customer_renderer_region'));
        }
        
        $regionElement = $form->getElement('region_id');
        if ($regionElement) {
        	$regionElement->setNoDisplay(true);
        }
        
        $country = $form->getElement('country_id');
        if ($country) {
        	$country->addClass('countries');
        }
        
        $fieldset->addField('city', 'text', array(
        		'name'      => 'city',
        		'label'     => Mage::helper('storelocatori')->__('City'),
        		'title'     => Mage::helper('storelocatori')->__('City'),
        		'required'  => true,
        		'disabled'  => $isElementDisabled
        ));
        
        
        $fieldset->addField('postal_code', 'text', array(
        		'name'      => 'postal_code',
        		'label'     => Mage::helper('storelocatori')->__('Zip/Postal Code'),
        		'title'     => Mage::helper('storelocatori')->__('Zip/Postal Code'),
        		'required'  => true,
        		'disabled'  => $isElementDisabled
        ));
        
        
        $fieldset->addField('street', 'text', array(
        		'name'      => 'street',
        		'label'     => Mage::helper('storelocatori')->__('Street'),
        		'title'     => Mage::helper('storelocatori')->__('Street'),
        		'required'  => true,
        		'disabled'  => $isElementDisabled
        ));
        
        $fieldset->addField('website', 'text', array(
        		'name'      => 'website',
        		'label'     => Mage::helper('storelocatori')->__('Website'),
        		'title'     => Mage::helper('storelocatori')->__('Website'),        		
        		'disabled'  => $isElementDisabled,
        ));
        
        
        $fieldset->addField('desc', 'editor', array(
        		'name'      => 'desc',
        		'label'     => Mage::helper('storelocatori')->__('Description'),
        		'title'     => Mage::helper('storelocatori')->__('Description'),
        		'disabled'  => $isElementDisabled,
        ));
        
        $fieldset->addField('phone', 'text', array(
        		'name'      => 'phone',
        		'label'     => Mage::helper('storelocatori')->__('Phone Number'),
        		'title'     => Mage::helper('storelocatori')->__('Phone Number'),
        		'required'  => true,
        		'disabled'  => $isElementDisabled
        ));
      
        
        $data = $model->getData();
        
       
        
        
        $fieldset = $form->addFieldset('image_fieldset', array('legend'=>Mage::helper('storelocatori')->__('Image & Icon')));
        
        if(isset($data['icon']) && $data['icon'] != '' && !is_array($data['icon'])){
        	$finderLink = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) .'iwd/storelocatori/' . $data['icon'];
        	$finderName = $data['icon'];
        	 
        
        	$fieldset->addField('icon', 'image', array(
        			'label' => Mage::helper('storelocatori')->__('Image'),
        			'required' => false,
        			'value'     => $finderLink,
        			'name' => 'icon',
        			'after_element_html' => '<p class="nm"><small>' . $this->__('Dimension should be 32px * 32px') . '</small></p>',
        	));
        
        }else{
        	$fieldset->addField('icon', 'image', array(
        			'label' => Mage::helper('storelocatori')->__('Icon'),
        			'required' => false,
        			'name' => 'icon',
        			'after_element_html' => '<p class="nm"><small>' . $this->__('Dimension should be 32px * 32px') . '</small></p>',
        	));
        }
        
        
        
        if(isset($data['image']) && $data['image'] != '' && !is_array($data['image'])){
        	$finderLink = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) .'iwd/storelocatori/' . $data['image'];
        	$finderName = $data['image'];
        
        
        	$fieldset->addField('image', 'image', array(
        			'label' => Mage::helper('storelocatori')->__('Image'),
        			'required' => false,
        			'value'     => $finderLink,
        			'name' => 'image',        			
        	));
        
        }else{
        	$fieldset->addField('image', 'image', array(
        			'label' => Mage::helper('storelocatori')->__('Image'),
        			'required' => false,
        			'name' => 'image'        			
        	));
        }
        
        
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
        return Mage::helper('storelocatori')->__('Store Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('storelocatori')->__('Store Information');
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

    /**
     * Return JSON object with countries associated to possible websites
     *
     * @return string
     */
    public function getDefaultCountriesJson() {
    	$websites = Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(false, true);
    	$result = array();
    	foreach ($websites as $website) {
    		$result[$website['value']] = Mage::app()->getWebsite($website['value'])->getConfig(
    				Mage_Core_Helper_Data::XML_PATH_DEFAULT_COUNTRY
    		);
    	}
    
    	return Mage::helper('core')->jsonEncode($result);
    }
   
}
