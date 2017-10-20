<?php


class Fgc_Valution_Block_Adminhtml_Valution extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_valution";
	$this->_blockGroup = "valution";
	$this->_headerText = Mage::helper("valution")->__("Valution Manager");
	$this->_addButtonLabel = Mage::helper("valution")->__("Add New Item");
	parent::__construct();
	
	}

}