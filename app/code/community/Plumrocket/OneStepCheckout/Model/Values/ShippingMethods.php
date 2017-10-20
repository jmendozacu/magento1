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


class Plumrocket_OneStepCheckout_Model_Values_ShippingMethods
{
	public function toOptionArray()
	{
		$methods = Mage::getSingleton('shipping/config')->getActiveCarriers();

		$options = array(array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('--Please Select--')));
		
		foreach ($methods as $_code => $_method) {
			if(!$_title = Mage::getStoreConfig("carriers/$_code/title"))
			$_title = $_code;

			$options[] = array('value' => $_code, 'label' => $_title);
		}

		return $options;
	}
}