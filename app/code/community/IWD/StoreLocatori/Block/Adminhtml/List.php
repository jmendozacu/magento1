<?php
class IWD_Storelocatori_Block_Adminhtml_List extends Mage_Adminhtml_Block_Widget_Grid_Container {
	
	public function __construct() {
		$this->_controller = 'adminhtml_list';
		$this->_blockGroup = 'storelocatori';
		$this->_headerText = Mage::helper ( 'storelocatori' )->__ ( 'Stores Locations' );
		$this->_addButtonLabel = Mage::helper ( 'storelocatori' )->__ ( 'Add New Store' );
		
		$click = "setLocation('" . $this->getUrl('slocator/adminhtml_import/removeall',array('_secure'=>true)) ."');";
		$message = "if (confirm('Are you sure you want remove All stores?')){".$click."}";
		$this->addButton('removeall', $data=array('label'=>'Remove All Stores','onclick'=>$message, 'class'=>'back'));
		
		$click = "setLocation('" . $this->getUrl('slocator/adminhtml_import/fill',array('_secure'=>true)) ."');";
		$this->addButton('fill', $data=array('label'=>'Fill Stores Geo Data','onclick'=>$click,'class'=>'back'));
		
		
		
		$click = "setLocation('" . $this->getUrl('slocator/adminhtml_import/index',array('_secure'=>true)) ."');";
		$this->addButton('import', $data=array('label'=>'Import Stores','onclick'=>$click));
		
		
		
		parent::__construct ();
	}
	
}