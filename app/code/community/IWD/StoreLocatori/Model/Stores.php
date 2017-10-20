<?php
class IWD_StoreLocatori_Model_Stores extends Mage_Core_Model_Abstract {
	
	/**
	 * Store's Statuses
	 */
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	
	/**
	 * Initialize resource model
	 */
	public function __construct() {
		$this->_init ( 'storelocatori/stores' );
	}
	
	/**
	 * Prepare page's statuses.
	 *
	 *
	 * @return array
	 */
	public function getAvailableStatuses() {
		$statuses = new Varien_Object ( array (
				self::STATUS_ENABLED => Mage::helper ( 'storelocatori' )->__ ( 'Enabled' ),
				self::STATUS_DISABLED => Mage::helper ( 'storelocatori' )->__ ( 'Disabled' ) 
		) );
		
		return $statuses->getData ();
	}
}