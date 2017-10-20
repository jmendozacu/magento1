<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please 
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_One_Step_Checkout
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


class Plumrocket_OneStepCheckout_Block_System_Config_FormFields extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		return $this->getLayout()->createBlock('onestepcheckout/system_config_formFields_inputTable')
			->setContainerFieldId($element->getName())
			->setRowKey('name')
			->addColumn('orig_label', array(
				'header'    => Mage::helper('onestepcheckout')->__('Field'),
				'index'     => 'orig_label',
				'type'      => 'label',
				'width'     => '80%',
			))
			->addColumn('enable', array(
				'header'    => Mage::helper('onestepcheckout')->__('Enable'),
				'index'     => 'enable',
				'type'      => 'checkbox',
				'value'     => 1,
				'width'     => '10%',
			))
			->addColumn('required', array(
				'header'    => Mage::helper('onestepcheckout')->__('Required'),
				'index'     => 'required',
				'type'      => 'checkbox',
				'value'     => 1,
				'width'     => '10%',
			))
			->setArray($element->getValue())
			->toHtml();
	}

	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$html = parent::render($element);
		$html = str_replace(
			'<td class="value">', 
			'<td class="value"><input type="hidden" name="groups[address_form_settings][fields][address_fields][value][fake][enable]" value="1">', 
			$html
		);
		return $html;
	}

}