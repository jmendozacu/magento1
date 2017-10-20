<?php
class IWD_StoreLocatori_Block_Adminhtml_List_Render_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	protected static $_statuses;
	
	public function __construct() {
		self::$_statuses = array (0 => Mage::helper ( 'storelocatori' )->__ ( 'Disabled' ), 1 => Mage::helper ( 'storelocatori' )->__ ( 'Enabled' ) );
		parent::__construct ();
	}
	
	public function render(Varien_Object $row) {
		return Mage::helper ( 'storelocatori' )->__ ( $this->getStatus ( $row->getIsActive () ) );
	}
	
	public static function getStatus($status) {
	
		if (isset ( self::$_statuses [$status] )) {
			return self::$_statuses [$status];
		}
	
		return Mage::helper ( 'storelocatori' )->__ ( 'Unknown' );
	}
}