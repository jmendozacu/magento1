<?php
class IWD_StoreLocatori_Model_Resource_Store extends Mage_Core_Model_Resource_Db_Abstract {
	
	protected function _construct() {
		$this->_init ( 'storelocatori/store', 'entity_id' );
	}
}