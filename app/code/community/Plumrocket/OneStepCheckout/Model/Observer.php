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


class Plumrocket_OneStepCheckout_Model_Observer extends Mage_Core_Model_Abstract
{

	public function checkoutSubscribeEventOnepageSaveOrderAfter(Varien_Event_Observer $observer)
	{
		if (!Mage::helper('onestepcheckout')->moduleEnabled()) {
			return $this;
		}

		$_session = Mage::getSingleton('checkout/session');

		if (!$_session->getWillSubscribe()) {
			$params = Mage::app()->getRequest()->getParams();
			if (isset($params['billing']['subscribe'])) {
				$_session->setWillSubscribe(true);
			}
		}

		$willSubscribe = $_session->getWillSubscribe();

		if ((bool)$willSubscribe) {
			$email = $observer->getEvent()->getOrder()->getData('customer_email');
			Mage::getModel('newsletter/subscriber')->subscribe($email, true);
			$_session->setWillSubscribe(false);
		}
		return $this;
	}


	public function chechkRequestPathParams(Varien_Event_Observer $observer)
	{
		$request = $observer->getRequest();
		$event = $observer->getObject();

		$onCheckout = $event->getOnCheckout();
		if (!$onCheckout) {
			$onCheckout = ($request->getModuleName() == 'onestepcheckout');
			$event->setOnCheckout($onCheckout);
		}
	}

}