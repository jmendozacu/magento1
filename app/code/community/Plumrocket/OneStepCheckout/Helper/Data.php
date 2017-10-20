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


class Plumrocket_OneStepCheckout_Helper_Data extends Plumrocket_OneStepCheckout_Helper_Main
{

	protected $_fieldsConfig = array();


	public function getConfigShowSubscribe()
	{
		return (bool)Mage::getStoreConfig('onestepcheckout/address_form_settings/newsletter_subscription');
	}


	public function getConfigShowAgreements()
	{
		return (bool)Mage::getStoreConfig('onestepcheckout/additional_display_settings/terms_conditions');
	}


	public function getConfigGetAgreementsPage()
	{
		return Mage::getStoreConfig('onestepcheckout/additional_display_settings/terms_conditions_content');
	}


	public function getConfigGetDefaultCountry()
	{
		return Mage::getStoreConfig('onestepcheckout/address_form_settings/default_country');
	}


	public function getConfigShowDiscountForm()
	{
		return (bool)Mage::getStoreConfig('onestepcheckout/additional_display_settings/discount_form');
	}


	public function getConfigGetDefaultPayment()
	{
		return Mage::getStoreConfig('onestepcheckout/additional_display_settings/payment_method');
	}


	public function getConfigGetDefaultShippingMethod()
	{
		return Mage::getStoreConfig('onestepcheckout/additional_display_settings/shipping_method');
	}


	public function getConfigFooterContent()
	{
		return Mage::helper('cms')->getPageTemplateProcessor()->filter( Mage::getStoreConfig('onestepcheckout/additional_display_settings/footer_content') );
	}


	public function moduleEnabled($store = null)
	{
		return Mage::getStoreConfig('onestepcheckout/general/enabled', $store);
	}


	public function getConfigBlockBackgroundColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/block_background_color');
	}


	public function getConfigBlockHeadBackgroundColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/block_head_background_color');
	}


	public function getConfigBlockHeadTextColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/block_head_text_color');
	}


	public function getConfigLinkColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/link_color');
	}


	public function getConfigLinkHoverColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/link_hover_color');
	}


	public function getConfigButtonBackgroundColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/button_background_color');
	}


	public function getConfigButtonBorderColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/button_border_color');
	}


	public function getConfigButtonTextColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/button_text_color');
	}


	public function getConfigButtonHoverBackgroundColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/button_hover_background_color');
	}


	public function getConfigButtonHoverTextColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/button_hover_text_color');
	}


	public function getConfigNumbersBackgroundColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/numbers_background_color');
	}


	public function getConfigNumbersBorderColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/numbers_border_color');
	}


	public function getConfigNumbersTextColor()
	{
		return Mage::getStoreConfig('onestepcheckout/design_settings/numbers_text_color');
	}


	public function disableExtension()
	{
		$resource = Mage::getSingleton('core/resource');
		$connection = $resource->getConnection('core_write');
		$connection->delete($resource->getTableName('core/config_data'), array($connection->quoteInto('path IN (?)', array(
			'onestepcheckout/general/enabled', 
			'onestepcheckout/additional_display_settings/footer_content', 
			'onestepcheckout/additional_display_settings/terms_conditions_content', 
			'onestepcheckout/design_settings/block_background_color', 
			'onestepcheckout/design_settings/block_head_background_color', 
			'onestepcheckout/design_settings/link_color', 
			'onestepcheckout/design_settings/link_hover_color', 
			'onestepcheckout/design_settings/button_background_color', 
			'onestepcheckout/design_settings/button_text_color', 
			'onestepcheckout/design_settings/button_hover_background_color', 
			'onestepcheckout/design_settings/button_hover_text_color', 
			'onestepcheckout/design_settings/block_background_color', 
			'onestepcheckout/design_settings/button_border_color', 
			'onestepcheckout/design_settings/numbers_background_color', 
			'onestepcheckout/design_settings/numbers_border_color', 
			'onestepcheckout/design_settings/numbers_text_color'
			)))
		);
		$config = Mage::getConfig();
		$config->reinit();
		Mage::app()->reinitStores();
	}

	protected function _getConfigAddressFields($field)
	{
		$result = array('enable'=>false, 'required'=>false);
		if (isset($this->_fieldsConfig[$field])){
			$result = $this->_fieldsConfig[$field];
		} else {
			$values = json_decode(Mage::getStoreConfig('onestepcheckout/address_form_settings/address_fields'));
			if ($values) {
				foreach ($values as $name => $value) {
					if (is_array($value)) {
						$this->_fieldsConfig[$name]['enable'] = (isset($value[0]))? (int)$value[0]: false;
						$this->_fieldsConfig[$name]['required'] = (isset($value[1]))? (int)$value[1]: false;
					}
				}
				if (isset($this->_fieldsConfig[$field])){
					$result = $this->_fieldsConfig[$field];
				}
			}
		}
		return $result;
	}


	public function getConfigAddressFieldEnable($field)
	{
		$fieldConfig = $this->_getConfigAddressFields($field);
		return (bool)$fieldConfig['enable'];
	}


	public function getConfigAddressFieldRequired($field)
	{
		$fieldConfig = $this->_getConfigAddressFields($field);
		return (bool)$fieldConfig['required'];
	}


	public function getConfigDisplayAddresses()
	{
		return Mage::getStoreConfig('onestepcheckout/address_form_settings/display_addresses');
	}


	public function getConfigIsEnabledAutocomplete()
	{
		return (bool)Mage::getStoreConfig('onestepcheckout/address_form_settings/enable_autocomplete');
	}


	public function getConfigGoogleKey()
	{
		return trim(Mage::getStoreConfig('onestepcheckout/address_form_settings/google_api_key'));
	}


	public function customerSubscribed()
	{
		$customerEmail = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
		if ( $customerEmail ) {

			$emailExist = Mage::getModel('newsletter/subscriber')->getCollection()
				->addFieldToFilter('subscriber_email', $customerEmail)
				->addFieldToFilter('subscriber_status', '1')
				->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
				->setPageSize(1)
				->getFirstItem();

			return ( $emailExist->getId() ) ? true : false;
		}
		return false;
	}

	public function getConfigEnableRewards()
	{
		return (bool)(($module = Mage::getConfig()->getModuleConfig('Plumrocket_Rewards')) 
			&& ($module->is('active', 'true')) 
			&& Mage::getStoreConfig('onestepcheckout/additional_display_settings/rewards_form'));
	}

}