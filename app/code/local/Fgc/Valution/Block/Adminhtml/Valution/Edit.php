<?php
	
class Fgc_Valution_Block_Adminhtml_Valution_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "valution";
				$this->_controller = "adminhtml_valution";
				$this->_updateButton("save", "label", Mage::helper("valution")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("valution")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("valution")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("valution_data") && Mage::registry("valution_data")->getId() ){

				    return Mage::helper("valution")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("valution_data")->getId()));

				} 
				else{

				     return Mage::helper("valution")->__("Add Item");

				}
		}
}