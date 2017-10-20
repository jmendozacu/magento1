<?php
class IWD_StoreLocatori_JsonController extends Mage_Core_Controller_Front_Action{
	
	
	public function regionAction(){
		
		$region = Mage::helper('storelocatori')->prepareRegion($this);
		
	}
	
	
	public function searchAction(){
		Mage::helper('storelocatori')->prepareResultSearch($this);
	}
	
	public function geolocationAction(){
		Mage::helper('storelocatori/geolocation')->getDataJson($this);
	}
}