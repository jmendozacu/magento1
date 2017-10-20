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


require_once('Plumrocket/OneStepCheckout/controllers/CheckoutController.php');
class Plumrocket_Onestepcheckout_RewardsController extends Plumrocket_Onestepcheckout_CheckoutController
{

	public function activatePointsAction()
	{
		$message = null;
		$helper = Mage::helper('rewards');
		if (!$this->_haveAccess()) {
			//Mage::getSingleton('checkout/session')->addNotice($helper->__('You cannot use points.'));
			$message = $helper->__('You cannot use points.');
		} else {
			$modelPoints = Mage::getModel('rewards/points')->getByCustomer();
			$pointsCount = (int)($this->getRequest()->getParam('rewards_point_count'));


			//$quote = Mage::getSingleton('checkout/cart')->getQuote();
			$quote = $this->getOnepage()->getQuote();
			if ($quote && $quote->getCouponCode() && !Mage::getModel('rewards/config')->getRedeemPointsWithCoupon()) {
				//Mage::getSingleton('checkout/session')->addNotice($helper->__('You cannot use  Reward Points and Coupon Codes at the same time.'));
				$message = $helper->__('You cannot use  Reward Points and Coupon Codes at the same time.');
			} else {
				if ($modelPoints->canActivate($pointsCount)) {
					$modelPoints->activate($pointsCount);
					$quote->collectTotals()->save();
					$message = array(
						'update_section' => array(
							'name' => 'review', 
							'html' => $this->_getReviewHtml()
						)
					);
					//Mage::getSingleton('checkout/session')->addSuccess($helper->__('You have successfully activated %s Point', $pointsCount));
				} else {
					//Mage::getSingleton('checkout/session')->addNotice($helper->__('You cannot use this amount of points.'));
					$message = $helper->__('You cannot use this amount of points.');
				}
			}
		}

		//$this->_redirectReferer();
		return $this->sendResponse($message);
	}


	public function deactivatePointsAction()
	{
		$message = null;
		$helper = Mage::helper('rewards');
		if (!$this->_haveAccess()) {
			//Mage::getSingleton('checkout/session')->addNotice($helper->__('You cannot do this.'));
			$message = $helper->__('You cannot do this.');
		} else {
			$modelPoints = Mage::getModel('rewards/points')->getByCustomer()->deactivate();
			//Mage::getSingleton('checkout/session')->addSuccess($helper->__('You have successfully canceled points.'));
			$quote = $this->getOnepage()->getQuote();
			$quote->collectTotals()->save();
			$message = array(
				'update_section' => array(
					'name' => 'review', 
					'html' => $this->_getReviewHtml()
				)
			);
		}

		//$this->_redirectReferer();
		return $this->sendResponse($message);
	}


    protected function _haveAccess()
    {
    	return Mage::getModel('rewards/config')->modulEnabled() && Mage::helper('rewards')->getCurrentCustomerId();
    }


	private function sendResponse($message = '')
	{
		$result = null;
		if ( $message && is_array($message) ) {
			$result = $message;
		} else if($message){
			$result = array(
				'error' => 1,
				'message' => $this->__($message)
			);
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}

}