<?php
class IWD_StoreLocatori_Helper_Data extends Mage_Core_Helper_Abstract{

	const XML_PATH_HIDE_RADIUS = 'storelocatori/global/hide_radius';
	
	const XML_PATH_METRIC = 'storelocatori/gmaps/metric';
	
	const XML_PATH_RADIUS = 'storelocatori/gmaps/radius';
	
	const XML_PATH_LIMIT = 'storelocatori/global/limit_result';
	
	const XML_PATH_SEARCH_LOAD ='storelocatori/global/search_load';
	
	const XML_PATH_FULL_DISABLED ='storelocatori/global/full';
	
	const XML_PATH_PAGINATION ='storelocatori/global/pagination';
	
	
	
	public function prepareRegion($controller){
		$countryCode = $controller->getRequest()->getParam('country_id', false);
		
		if (!$countryCode){
			$response = array('error'=>true, 'action'=>'clear');
			$controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			return;			
		}else{
			
			
			$states = Mage::getModel('directory/region_api')->items($countryCode);
			
			if (count($states)==0 || !$states){				
				$response = array('error'=>true, 'action'=>'showRegion');
				$controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
				return;				
			}
			
			//get all states
			$collection = Mage::getModel('storelocatori/stores')->getCollection()->addFieldToFilter('country_id', $countryCode)->addFieldToFilter('is_active', array('eq'=>'1'));
			
			foreach($collection as $item){
				$stateIds[] =  $item->getRegionId();
			}
			
			$result = array();
			foreach($states as $state){
				if (in_array($state['region_id'], $stateIds)){
					$result[] = $state;
				}
			}
			
			$response = array('error'=>false, 'action'=>'updateStates','result'=>$result);
			$controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			return;				
			
		}
		
	}
	
	public function convertCountry($code){
	    $countryList = Mage::getResourceModel('directory/country_collection')
	    ->loadData()
	    ->toOptionArray(false);
	    foreach ($countryList as $item){
	        if ($item['value']==$code){
	            return $item['label'];
	        }
	    }
	    return $code;
	}
	
	public function prepareResultSearch($controller){
		
		$option = Mage::getStoreConfig(self::XML_PATH_METRIC);
		if ($option == 1){
			$raduisEarth  = 6371;
		}
		
		if ($option == 2){
			$raduisEarth  = 3959;
		}
		
		$defaultRadius = Mage::getStoreConfig(self::XML_PATH_RADIUS);
	
		$data = $controller->getRequest()->getParams();
		
		$page = $controller->getRequest()->getParam('page', 1);

		$store = Mage::app()->getStore()->getId();

		
		
		$collection = Mage::getModel('storelocatori/stores')->getCollection()
			->addFieldToFilter('is_active', array('eq'=>'1'))
			->addFieldToFilter('latitude', array('neq'=>'0'))
			->addFieldToFilter('longitude', array('neq'=>'0'));
		
		
		
		//check current
		if ($data['latitude']=='null' ){
			$data['latitude'] =1;
		}
		
		if ($data['latitude']=='null' ){
			$data['longitude'] =1;
		}
		 
		
	
		if (isset($data['country']) && !empty($data['country']) && empty($data['address'])){
		    $country = $this->convertCountry($data['country']);
			$collection->addFieldToFilter('country_id', array('eq'=>$data['country']));
		}
		
		

		if (!empty($data['address']) || !empty($data['country'])){
			if (isset($data['country']) && !empty($data['country'])){
			    $country = $this->convertCountry($data['country']);
				$response = $this->getCoordinate($data['address'] . ', ' . $country);
			
			}else{
				$response = $this->getCoordinate($data['address']);
			}
			
			if ($response && $response['status']=='OK'){
				$geometry = $response['results'][0]['geometry']['location'];
				
				$latitude = $geometry['lat'];
				$longitude = $geometry['lng'];
			}
			
			if (!isset($data['radius']) || empty($data['radius'])){
				$data['radius'] = $defaultRadius;
			}
		}
		
	
		if (empty($data['address']) &&  empty($data['country'])){
			if (isset($data['latitude']) && !empty($data['latitude']) && $data['latitude']!='null'){
				$latitude = $data['latitude'];
				
			}
			
			if (isset($data['longitude']) && !empty($data['longitude'])  && $data['longitude']!='null'){
				$longitude = $data['longitude'];
				Mage::register('storelocatori_address', $this->getAddressByCoordinate($latitude, $longitude));
			}
			
			
			if (!isset($data['radius']) || empty($data['radius'])){
				$data['radius'] = $defaultRadius;
			}
			
		}
		
		

		if (isset($data['current'])){
			$latitude = $data['latitude'];
			$longitude = $data['longitude'];
				
			//if empty radius set default to 25
			if (!isset($data['radius']) || empty($data['radius'])){
				$data['radius'] = $defaultRadius;
			}
			Mage::unregister('storelocatori_address');
			Mage::register('storelocatori_address', $this->getAddressByCoordinate($latitude, $longitude));
		}
		
		if (isset($latitude) && isset($longitude)){
			if (isset($data['radius']) && !empty($data['radius'])){
				$radius = (int) $data['radius'];
			}else{
				$radius = $defaultRadius;
			}
			
			
			
			$collection->getSelect()->columns('ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( ' . $latitude . ' ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( ' . $latitude . ' )) * COS( RADIANS( `longitude` ) - RADIANS( ' . $longitude . ' )) ) * ' . $raduisEarth . ' AS distance');
			$collection->getSelect()->where('ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( ' . $latitude . ' ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( ' . $latitude . ' )) * COS( RADIANS( `longitude` ) - RADIANS( ' . $longitude . ' )) ) * ' . $raduisEarth . ' < ' . $radius);
			
		}
		
		
		$collection->getSelect()->order('position ASC');
		$collection->addStoreFilter(Mage::app()->getStore());

		$limit  = Mage::getStoreConfig(self::XML_PATH_LIMIT);
		
		if ($limit!=0){
			//$collection->getSelect()->limit($limit);
			$collection->setPageSize($limit)
                    ->setCurPage($page);
		}
		
		$lastPage = $collection->getLastPageNumber();
		
		
		if ($page >= $lastPage){
		    $stopLoad = true;
		}else{
		    $stopLoad = false;
		}
		
		$collection->printLogQuery(true);
                /*
                  SELECT `main_table`.*, ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( -33.8708464 ) ) + COS( RADIANS
( `latitude` ) ) * COS( RADIANS( -33.8708464 )) * COS( RADIANS( `longitude` ) - RADIANS( 151.20733 )
) ) * 6371 AS `distance` FROM `iwd_storelocator` AS `main_table`
 INNER JOIN `iwd_storelocator_store` AS `store_table` ON main_table.entity_id = store_table.locatorstore
 WHERE (`is_active` = '1') AND (`latitude` != '0') AND (`longitude` != '0') AND (ACOS( SIN( RADIANS(
 `latitude` ) ) * SIN( RADIANS( -33.8708464 ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( -33.8708464
 )) * COS( RADIANS( `longitude` ) - RADIANS( 151.20733 )) ) * 6371 < 25) AND (store_table.store_id IN
('1', 0)) GROUP BY `main_table`.`entity_id` ORDER BY `position` ASC
                 */
                
		$result = $collection->toArray();
	
		if ($page>1){
		    $pagination = true;
		}else{
		    $pagination = false;
		}
		
		
		//CHECK STOP LOAD IF SETTINGS ENABLED
		$usePagination = Mage::getStoreConfig(self::XML_PATH_PAGINATION);
		
		if (!$usePagination){
		    $stopLoad = true;
		    $pagination = false;
		}
		
		
		    
		$blockResultBar = Mage::app()->getLayout()->createBlock('storelocatori/search_result')
    		->setData('page', $page+1)
    		->setData('load', $pagination)    		
    		->setTemplate('storelocatori/result.phtml')->setCollection($collection);
		$blockResultBar = $blockResultBar->toHtml();
		
		
		foreach($result['items'] as &$item){
			$block = Mage::app()->getLayout()->createBlock('storelocatori/search_result')->setTemplate('storelocatori/item.phtml')->setItem($item);
			$item['content'] = $block->toHtml();
		}
		unset($item);
		
		
		
		$response = array('error'=>false, 'action'=>'viewresult','result'=>$blockResultBar,'maps'=>$result, 'pagination'=>$pagination,'stopLoad'=>$stopLoad);
		$controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
		return;
	}
	
	
	private function getCoordinate($address){
		$prepareAddress = trim($address);
		$prepareAddress = str_replace(' ', '+', $address);
		
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $prepareAddress . '&sensor=false';
		try{
			$http = new Varien_Http_Adapter_Curl ();
			$config = array (
					'timeout' =>5
			);
				
			
			$config ['header'] = false;
				
			$http->setConfig ( $config );
			$http->write ( Zend_Http_Client::POST, $url , '1.1' );
				
			$response = $http->read ();
			
			if ($response === false) {
				return false;
			}

			$json = $this->parseResponse ( $response );
		}catch(Exception $e){
			return false;
		}
		
		
		return $json;
	}
	
	
	public function getAddressByCoordinate($latitude, $longitude){
	   $key = Mage::getStoreConfig('storelocatori/gmaps/serverkey');
	    
	    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$key}";
	    
	    try{
	        $http = new Varien_Http_Adapter_Curl ();
	        $config = array (
	            'timeout' =>5
	        );
	    
	        	
	        $config ['header'] = false;
	    
	        $http->setConfig ( $config );
	        $http->write ( Zend_Http_Client::POST, $url , '1.1' );
	    
	        $response = $http->read ();
	        	
	        if ($response === false) {
	            return false;
	        }
	    
	        $json = $this->parseResponse ( $response );
	    }catch(Exception $e){
	        return false;
	    }
	    
	    
	    if ($json && $json['status']=='OK'){
	        $address = $json['results'][0]['formatted_address'];
	    
	       return $address;
	    }
	    return $json;
	}
	
	private function parseResponse($response) {
		
		$p = strpos($response, "\r\n\r\n");
		if( $p !== false ) {
			$rawHeades = substr($response, 0, $p);
			$response = substr($response, $p + 4);
		}
		
		$json = json_decode ( $response, true );
	
		if (isset ( $json->error )) {
			throw new Exception ( $json->message );
		}

		return $json;
	}
	
	public function getLocatorUrl(){
		return $this->_getUrl($this->getRoute());		
		
	}
	
	public function getRoute(){
		return Mage::getStoreConfig('storelocatori/global/route');
	}
	
	
	
	public function isHideRadius(){
		return Mage::getStoreConfig(self::XML_PATH_HIDE_RADIUS);
	}
	
	public function isLoadInitialDisabled(){
	    return Mage::getStoreConfig(self::XML_PATH_SEARCH_LOAD);
	}
	
	public function isDisabledFullWidth(){
	    return Mage::getStoreConfig(self::XML_PATH_FULL_DISABLED);
	}
	
	
}