<?php
class Fgc_Valution_Block_Adminhtml_Valution_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("valution_form", array("legend"=>Mage::helper("valution")->__("Item information")));

				
						$fieldset->addField("your_name", "text", array(
						"label" => Mage::helper("valution")->__("Your name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "your_name",
						));
					
						$fieldset->addField("your_email", "text", array(
						"label" => Mage::helper("valution")->__("Your email"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "your_email",
						));
					
						$fieldset->addField("phone_number", "text", array(
						"label" => Mage::helper("valution")->__("Phone number"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "phone_number",
						));
									
						 $fieldset->addField('country', 'select', array(
						'label'     => Mage::helper('valution')->__('Country'),
						'values'   => Fgc_Valution_Block_Adminhtml_Valution_Grid::getValueArray3(),
						'name' => 'country',					
						"class" => "required-entry",
						"required" => true,
						));
						$fieldset->addField("message_titile", "text", array(
						"label" => Mage::helper("valution")->__("Message titile"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "message_titile",
						));
					
						$fieldset->addField("your_message", "textarea", array(
						"label" => Mage::helper("valution")->__("Your message"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "your_message",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getValutionData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getValutionData());
					Mage::getSingleton("adminhtml/session")->setValutionData(null);
				} 
				elseif(Mage::registry("valution_data")) {
				    $form->setValues(Mage::registry("valution_data")->getData());
				}
				return parent::_prepareForm();
		}
}
