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
require_once('Mage/Checkout/controllers/OnepageController.php');

class Plumrocket_Onestepcheckout_CheckoutController extends Mage_Checkout_OnepageController {

    /**
     * Override index action function to set new session
     */
//    public function indexAction(){
//        Mage::getSingleton('checkout/session')->setFirstCheckout('yes');
//        parent::indexAction();
//        Mage::getSingleton('checkout/session')->setFirstCheckout('no');
//    }
    /**
     * Get shipping method step html
     *
     * @return string
     */
    protected function _getShippingMethodsHtml() {
        $this->loadLayout('onestepcheckout_onepage_updateblocks');
        return $this->getLayout()->getBlock('shipping.root')->toHtml();
    }

    /**
     * Get payment method step html
     *
     * @return string
     */
    protected function _getPaymentMethodsHtml() {
        $this->loadLayout('onestepcheckout_onepage_updateblocks');
        return $this->getLayout()->getBlock('payment.root')->toHtml();
    }

    /**
     * Get review html
     *
     * @return string
     */
    protected function _getReviewHtml() {
        $this->loadLayout('onestepcheckout_onepage_updateblocks');
        return $this->getLayout()->getBlock('review.root')->toHtml();
    }

    /**
     * Save checkout method
     */
    public function saveMethodAction($sendResponse = true) {

        if ($this->_expireAjax() || !Mage::helper('onestepcheckout')->moduleEnabled()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $method = $this->getRequest()->getPost('method');
            $result = $this->getOnepage()->saveCheckoutMethod($method);

            if ($sendResponse) {
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            } else {
                return $result;
            }
        }
    }

    /**
     * Save checkout billing address
     */
    public function saveBillingAction($sendResponse = true) {
        if ($this->_expireAjax() || !Mage::helper('onestepcheckout')->moduleEnabled()) {
            return;
        }

        if (Mage::getSingleton('plumbase/observer')->customer() != Mage::getSingleton('plumbase/product')->currentCustomer()) {
            return;
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }

            if (($this->getOnepage()->getQuote()->getCheckoutMethod() == 'register') && !isset($data['customer_password'])) {
                $data['customer_password'] = $data['confirm_password'] = Mage::helper('core')->getRandomString($length = 7);
            }

            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }

            if ($sendResponse) {
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            } else {
                return $result;
            }
        }
    }

    /**
     * Shipping address save action
     */
    public function saveShippingAction($sendResponse = true) {
        if ($this->_expireAjax() || !Mage::helper('onestepcheckout')->moduleEnabled()) {
            return;
        }

        if (Mage::getSingleton('plumbase/observer')->customer() != Mage::getSingleton('plumbase/product')->currentCustomer()) {
            return;
        }

        if ($this->getRequest()->isPost()) {
            if (Mage::helper('onestepcheckout')->getConfigDisplayAddresses() == 'one' && !$this->getOnepage()->getQuote()->isVirtual()) {
                $data = $this->getRequest()->getPost('billing', array());
                $data['address_id'] = $this->getLayout()->createBlock('checkout/onepage_shipping')->getAddress()->getId();
                $data['same_as_billing'] = 1;
                $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
            } else {
                $data = $this->getRequest()->getPost('shipping', array());
                $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
            }

            $result = $this->getOnepage()->saveShipping($data, $customerAddressId);
            if (!isset($result['error'])) {
                $result['update_section'] = array(
                    'name' => 'shipping-method',
                    'html' => $this->_getShippingMethodsHtml()
                );
            }

            if ($sendResponse) {
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            } else {
                return $result;
            }
        }
    }

    /**
     * Shipping method save action
     */
    public function saveShippingMethodAction($sendResponse = true) {
        if ($this->_expireAjax() || !Mage::helper('onestepcheckout')->moduleEnabled()) {
            return;
        }

        if (Mage::getSingleton('plumbase/observer')->customer() != Mage::getSingleton('plumbase/product')->currentCustomer()) {
            return;
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');

            $result = $this->getOnepage()->saveShippingMethod($data);
            // $result will contain error data if shipping method is empty
            if (!$result) {
                Mage::dispatchEvent(
                        'checkout_controller_onepage_save_shipping_method', array(
                    'request' => $this->getRequest(),
                    'quote' => $this->getOnepage()->getQuote()));
                $this->getOnepage()->getQuote()->collectTotals();

                if ($sendResponse) {
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                }

                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();

            if (empty($result['error'])) {
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );
            }

            if ($sendResponse) {
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            } else {
                return $result;
            }
        }
    }

    /**
     * Save payment ajax action
     *
     * Sets either redirect or a JSON response
     */
    public function savePaymentAction($sendResponse = true) {
        if ($this->_expireAjax() || !Mage::helper('onestepcheckout')->moduleEnabled()) {
            return;
        }

        if (Mage::getSingleton('plumbase/observer')->customer() != Mage::getSingleton('plumbase/product')->currentCustomer()) {
            return;
        }

        try {
            if (!$this->getRequest()->isPost()) {
                $this->_ajaxRedirectResponse();
                return;
            }

            $data = $this->getRequest()->getPost('payment', array());

            $result = $this->getOnepage()->savePayment($data);

            // get section and redirect data
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            //if ( empty($result['error']) && !$redirectUrl ){
            if ((empty($result['error']) && !$redirectUrl) || !$sendResponse) {
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );
            }
            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }
        } catch (Mage_Payment_Exception $e) {
            if ($e->getFields()) {
                $result['fields'] = $e->getFields();
            }
            $result['error'] = $e->getMessage();
        } catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $result['error'] = $this->__('Unable to set Payment Method.');
        }

        if ($sendResponse) {
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } else {
            return $result;
        }
    }

    /**
     * Create order action
     */
    public function saveOrderAction() {
        if (!$this->_validateFormKey() || !Mage::helper('onestepcheckout')->moduleEnabled()) {
            $this->_redirect('*/*');
            return;
        }

        if ($this->_expireAjax()) {
            return;
        }

        $isPrescription = false;
        $isMedicine = false;
        $isPharmacistOnlyS3 = false;
        $medicalFormRedirect = false;
        $cartItems = $this->getOnepage()->getQuote()->getAllVisibleItems();
        foreach ($cartItems as $item) {
            $_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
            $prescription = $_product->getPrescription();
            $medicine = $_product->getMedicine();
            $pharmacistOnlyS3 = $_product->getPharmacistOnlyS3();

            //$prescription = 1;
            //$medicine = 1;
            if ($prescription == 1) {
                $isPrescription = true;
                break;
            }
            if ($medicine == 1) {
                $isMedicine = true;
                break;
            }
            if ($pharmacistOnlyS3 == 1) {
                $isPharmacistOnlyS3 = true;
                break;
            }
        }

        $result = array();
        try {
            $requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds();
            if ($requiredAgreements) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                $diff = array_diff($requiredAgreements, $postedAgreements);
                if ($diff) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }

            $data = $this->getRequest()->getPost('payment', array());
            if ($data) {
                $data['checks'] = Mage_Payment_Model_Method_Abstract::CHECK_USE_CHECKOUT | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_COUNTRY | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_CURRENCY | Mage_Payment_Model_Method_Abstract::CHECK_ORDER_TOTAL_MIN_MAX | Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL;
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }

            /**
             * auto create account and login
             */
            if ($isPrescription || $isMedicine || $isPharmacistOnlyS3) {
                $customer_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
                $customerconditions = Mage::getModel('prescription/customerconditions')->getCollection()->addFieldToFilter('customer_id', $customer_id);
                $customerallergies = Mage::getModel('prescription/customerallergies')->getCollection()->addFieldToFilter('customer_id', $customer_id);

                if ($customerconditions->count() == 0 || $customerallergies->count() == 0) {
                    $medicalFormRedirect = true;
                }
            }

            if (!Mage::getSingleton('customer/session')->isLoggedIn() && ($isMedicine || $isPrescription || $isPharmacistOnlyS3)) {
                $this->getOnepage()->autoRegister();
//                $result['redirect'] = Mage::getUrl('customer/account/medical', array('_secure'=>true)) ;
//                $this->getResponse()->setHeader('Content-type','application/json', true);
//                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));            
                return;
            } elseif ($medicalFormRedirect) {
                return;
            } else {
                $this->getOnepage()->saveOrder();
                $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            }

//            $this->getOnepage()->saveOrder();
//
//            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error'] = false;
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if (!empty($message)) {
                $result['error_messages'] = $message;
            }
            $result['update_section'] = array(
                'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

            $gotoSection = $this->getOnepage()->getCheckout()->getGotoSection();
            if ($gotoSection) {
                $this->getOnepage()->getCheckout()->setGotoSection(null);
            }
            $updateSection = $this->getOnepage()->getCheckout()->getUpdateSection();
            if ($updateSection) {
                if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                    $updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
                    $result['update_section'] = array(
                        'name' => $updateSection,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->getOnepage()->getCheckout()->setUpdateSection(null);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
        }
        $this->getOnepage()->getQuote()->save();
        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function preSaveAction() {
        $result = array();
        if ($this->getRequest()->getParam('steps')) {
            $stringStepAction = $this->getRequest()->getParam('steps');
            $steps = explode(",", $stringStepAction);
        }

        $actions = array('saveBillingAction', 'saveShippingAction', 'saveShippingMethodAction', 'savePaymentAction');
        $params = array('update_section');
        if (is_array($steps)) {

            foreach ($steps as $step) {
                $actionResult = array();
                if ($step == 10) {
                    $step = 0;
                }
                $action = $actions[$step];
                if ($action == 'saveBillingAction') {
                    array_push($actionResult, $this->saveMethodAction(false));
                    array_push($actionResult, $this->$action(false));
                    if (Mage::helper('onestepcheckout')->getConfigDisplayAddresses() == 'one' && !$this->getOnepage()->getQuote()->isVirtual()) {
                        array_push($actionResult, $this->saveShippingAction(false));
                    }
                } else {
                    try {
                        $valueResultAction = $this->$action(false);
                        array_push($actionResult, $valueResultAction);
                    } catch (Exception $e) {
                        Mage::throwException($e->getMessage());
                    }
                }
                foreach ($params as $param) {
                    foreach ($actionResult as $key => $values) {
                        if (isset($values[$param])) {
                            $result[$param][$values[$param]['name']] = $values[$param];
                        }
                    }
                }
            }
            $result['nextstep'] = $this->getRequest()->getParam('nextstep');
        }

        $this->getResponse()->setHeader('Content-type', 'application/json')->clearHeaders();
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Su dung cho Site RYC
     */
    public function saveAllRYCAction() {
        $result = array();
        if ($this->getRequest()->getParam('steps')) {
            $stringStepAction = $this->getRequest()->getParam('steps');
            $steps = explode(",", $stringStepAction);
        }
        if (is_array($steps)) {
            $actions = array('saveBillingAction', 'saveShippingAction', 'saveShippingMethodAction', 'savePaymentAction');
            foreach ($steps as $step) {
                $actionResult = array();
                if ($step == 10) {
                    $step = 0;
                }
                $action = isset($actions[$step]) ? $actions[$step] : '';
                if ($action != '') {
                    if ($action == 'saveBillingAction') {
                        array_push($actionResult, $this->saveMethodAction(false));
                        array_push($actionResult, $this->$action(false));
                        if (Mage::helper('onestepcheckout')->getConfigDisplayAddresses() == 'one' && !$this->getOnepage()->getQuote()->isVirtual()) {
                            array_push($actionResult, $this->saveShippingAction(false));
                        }
                    } else {
                        $valueResultAction = $this->$action(false);
                        array_push($actionResult, $valueResultAction);
                    }
                }
                $param = 'update_section';
                foreach ($actionResult as $key => $values) {
                    if (isset($values[$param])) {
                        $result[$param][$values[$param]['name']] = $values[$param];
                    }
                }
            }
            $result['nextstepall'] = $this->getRequest()->getParam('nextstepall');
        }
        $this->getResponse()->setHeader('Content-type', 'application/json')->clearHeaders();
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Create order action
     */
    public function saveAllAction() {
        if (!Mage::helper('onestepcheckout')->moduleEnabled()) {
            return;
        }

        Mage::getSingleton('checkout/session')->getMessages(true);
        $isRYCSite = $this->getRequest()->getParam('rycsite');
        if ($isRYCSite == 'ryc') {
            
        } else {

            $this->saveMethodAction();
            $this->saveBillingAction();
            $this->saveShippingAction();
            $this->saveShippingMethodAction();
            $this->savePaymentAction();
        }

        if (Mage::getSingleton('plumbase/observer')->customer() == Mage::getSingleton('plumbase/product')->currentCustomer()) {
            $payment = $this->getRequest()->getParam('payment');
            if (isset($payment['method'])) {
                switch ($payment['method']) {
                    case 'paypal_express' :
                        $this->getResponse()->setBody(json_encode(array('redirect' => Mage::getUrl('paypal/express/start'))));
                        break;
                    case 'paypal_express_bml' :
                        $this->getResponse()->setBody(json_encode(array('redirect' => Mage::getUrl('paypal/bml/start'))));
                        break;
                    case 'sagepayserver' :
                    case 'sagepaydirectpro' :
                    case 'sagepayform' :
                        $this->_forward('onepageSaveOrder', 'payment', 'sgps', $this->getRequest()->getParams());
                        break;
                    default:
                        $this->saveOrderAction();
                }
            }
        }
    }

    /**
     * Initialize coupon
     */
    public function couponPostAction() {
        if ($this->_expireAjax() || !Mage::helper('onestepcheckout')->moduleEnabled()) {
            return;
        }

        $couponCode = (string) $this->getRequest()->getParam('coupon_code');
        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $quote = $this->getOnepage()->getQuote();
        $oldCouponCode = $quote->getCouponCode();
        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            return;
        }

        try {
            $codeLength = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->setCouponCode($isCodeLengthValid ? $couponCode : '')
                    ->collectTotals()
                    ->save();

            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == $quote->getCouponCode()) {
                    //$this->_getSession()->addSuccess($this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode)));
                } else {
                    $result = array(
                        'error' => 1,
                        'message' => $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                }
            } else {
                //$this->_getSession()->addSuccess($this->__('Coupon code was canceled.'));
            }
        } catch (Mage_Core_Exception $e) {
            $result = array(
                'error' => 1,
                'message' => $e->getMessage()
            );
        } catch (Exception $e) {
            $result = array(
                'error' => 1,
                'message' => $this->__('Cannot apply the coupon code.')
            );
        }

        $result['update_section']['review'] = array(
            'name' => 'review',
            'html' => $this->_getReviewHtml()
        );
        $result['update_section']['shipping-method'] = array(
            'name' => 'shipping-method',
            'html' => $this->_getShippingMethodsHtml()
        );

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function customerExistAction() {
        $customerCollection = Mage::getModel('customer/customer')->getCollection();

        if (Mage::getStoreConfig('customer/account_share/scope') == "1") {
            $customerCollection->addFieldToFilter('website_id', Mage::app()->getStore()->getWebsiteId());
        }
        $customer = $customerCollection->addFieldToFilter('email', $this->getRequest()->getParam('email'))
                ->setPageSize(1)
                ->getFirstItem();

        $this->getResponse()->setBody(json_encode(array(
            'result' => ( $customer->getId() ) ? true : false
        )));
    }

    /**
     * Check can page show for unregistered users
     *
     * @return boolean
     */
    protected function _canShowForUnregisteredUsers() {
        //return Mage::getSingleton('customer/session')->isLoggedIn()

        return Mage::getSingleton('customer/session')->isLoggedIn() || $this->getRequest()->getActionName() == 'index' || true //Mage::helper('checkout')->isAllowedGuestCheckout($this->getOnepage()->getQuote())
                || !Mage::helper('checkout')->isCustomerMustBeLogged();
    }

}
