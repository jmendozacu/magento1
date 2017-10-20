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


require_once('Mage/Customer/controllers/AccountController.php');
class Plumrocket_Onestepcheckout_AccountController extends Mage_Customer_AccountController
{

	/**
	* Login post action
	*/
	public function loginPostAction()
	{
		if (!$this->_validateFormKey()) {
			//$this->_redirect('*/*/');
			//return;
			return $this->sendResponse($this->__('Invalid Form Key!'));
		}

		if ($this->_getSession()->isLoggedIn()) {
			//$this->_redirect('*/*/');
			//return;
			return $this->sendResponse($this->__('is Logged In'));
		}
		$session = $this->_getSession();

		if ($this->getRequest()->isPost()) {
			$login = $this->getRequest()->getPost('login');
			if (!empty($login['username']) && !empty($login['password'])) {
				try {
					$session->login($login['username'], $login['password']);
					if ($session->getCustomer()->getIsJustConfirmed()) {
						$this->_welcomeCustomer($session->getCustomer(), true);
					}
				} catch (Mage_Core_Exception $e) {
					switch ($e->getCode()) {
						case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
							$value = $this->_getHelper('customer')->getEmailConfirmationUrl($login['username']);
							$message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
						break;
						case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
							$message = $e->getMessage();
						break;
					default:
						$message = $e->getMessage();
					}
					//$session->addError($message);
					$session->setUsername($login['username']);

					return $this->sendResponse($message);
					
				} catch (Exception $e) {
					// Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
				}
			} else {
				//$session->addError($this->__('Login and password are required.'));
				return $this->sendResponse('Login and password are required.');
			}
		}
		//$this->_loginPostRedirect();
		$this->sendResponse();
		
	}


	private function sendResponse($message = '')
	{
		$result = null;
		if ( $message ) {
			$result = array(
				'error' => 1,
				'message' => $this->__($message)
			);
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}

}