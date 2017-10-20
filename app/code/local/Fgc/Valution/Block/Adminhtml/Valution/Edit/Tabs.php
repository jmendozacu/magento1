<?php
class Fgc_Valution_Block_Adminhtml_Valution_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("valution_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("valution")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("valution")->__("Item Information"),
				"title" => Mage::helper("valution")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("valution/adminhtml_valution_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
