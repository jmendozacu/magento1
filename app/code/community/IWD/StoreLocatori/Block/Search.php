<?php
class IWD_StoreLocatori_Block_Search extends Mage_Core_Block_Template{
	
	const XMLAPIKEY = 'storelocatori/gmaps/apikey';
	
	/**
	 * GET ALL AVAILABLE COUNTRY 
	 */
	public function getCountries(){
		$result = array();
		$collection = Mage::getModel('storelocatori/stores')->getCollection()->addFieldToFilter('is_active', array('eq'=>'1'));
		
		$storeCodes = array();
		foreach($collection as $item){
			$storeCodes[] = $item->getCountryId();
		}
		
		$storeCodes = array_unique($storeCodes);
		
		
		$options  = Mage::getModel('adminhtml/system_config_source_country')->toOptionArray();
		
		foreach($options as $option){
			if (in_array($option['value'], $storeCodes)){
				$result[] = $option;
			}
		}
		
		return $result;
	}
	
	public function getApiKey(){
		return Mage::getStoreConfig(self::XMLAPIKEY);
	}
	
	
	
	
	
	protected function _getMarkerFile(){
		$folderName = IWD_StoreLocatori_Model_System_Marker::UPLOAD_DIR;
		$storeConfig = Mage::getStoreConfig('storelocatori/gmaps/marker');
		$faviconFile = Mage::getBaseUrl('media') . $folderName . '/' . $storeConfig;
		$absolutePath = Mage::getBaseDir('media') . '/' . $folderName . '/' . $storeConfig;
	
		if(!is_null($storeConfig) && $this->_isFile($absolutePath)) {
			$url = $faviconFile;
		} else {
			//$url = $this->getSkinUrl('css/iwd/storelocatori/images/marker.png', array('_secure'=>true));
			//$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN, true) . 'frontend/base/default/css/iwd/storelocatori/images/marker.png';
			$url = Mage::getStoreConfig('web/unsecure/base_skin_url') . 'frontend/base/default/css/iwd/storelocatori/images/marker.png';
			
		}
		return $url;
	}
	
	
	protected function _isFile($filename) {
		if (Mage::helper('core/file_storage_database')->checkDbUsage() && !is_file($filename)) {
			Mage::helper('core/file_storage_database')->saveFileToFilesystem($filename);
		}
		return is_file($filename);
	}
	
	protected  function getMetric(){
		$list = array(
				1 => Mage::helper('storelocatori')->__('Km'),
				2 => Mage::helper('storelocatori')->__('Miles'),
		);
		
		$option = Mage::getStoreConfig('storelocatori/gmaps/metric');
		return $list[$option];
	}
	
	public  function getZoom(){
		return Mage::getStoreConfig('storelocatori/gmaps/zoom');		
	}
	
}