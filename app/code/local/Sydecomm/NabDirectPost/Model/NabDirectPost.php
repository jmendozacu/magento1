<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Sydecomm
 * @package    Sydecomm_NabDirectPost
 * @copyright  Copyright (c) 2015 Vnphpexpert.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Sydecomm_NabDirectPost_Model_NabDirectPost extends Mage_Payment_Model_Method_Cc
{
    /**
    * unique internal payment method identifier
    *
    * @var string [a-z0-9_]
    */
    protected $_code = 'nabdirectpost';
    
	protected $_test_url	= "https://transact.nab.com.au/test/directpostv2/authorise";
    protected $_live_url	= "https://transact.nab.com.au/live/directpostv2/authorise";
	
	protected $_isGateway               = true;
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = true;
    protected $_canSaveCc = false;
    protected $_canFetchTransactionInfo = true;

    public function authorize(Varien_Object $payment, $amount)
    {
        if ($amount <= 0) {
            Mage::throwException(Mage::helper('paygate')->__('Invalid amount for authorization.'));
        }
		
		Mage::getSingleton('core/session')->setCcNumber($payment->getCcNumber());
		Mage::getSingleton('core/session')->setCcCid($payment->getCcCid());
        return $this;
    }
	
	/**
     * Return Order place direct url
     *
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('nabdirectpost/payment/creditcard', array('_secure' => true));
    }
	
	public function getGatewayUrl()
    {
		if ($this->getConfigData('testmode') == 1)
			return $this->_test_url;
		return $this->_live_url;
    }
    
    public function getFormFields()
    {
        $checkout = Mage::getSingleton('checkout/session');
        $orderIncrementId = $checkout->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
		$payment = $order->getPayment();
        
        $currency   = $order->getOrderCurrencyCode();
		$BAddress = $order->getBillingAddress();

		$notify_url = Mage::getUrl('nabdirectpost/payment/notify');
		$return_url = Mage::getUrl('nabdirectpost/payment/success');
		
		$EPS_MERCHANT = Mage::getStoreConfig( 'payment/nabdirectpost/merchant_code' );
		$password = Mage::getStoreConfig( 'payment/nabdirectpost/secure_key' );
		$EPS_MERCHANTNUM = Mage::getStoreConfig( 'payment/nabdirectpost/merchant_number_3d' );
		
		if ($this->getConfigData('secure_3d') == 1)
			$EPS_TXNTYPE = 4;
		else
			$EPS_TXNTYPE = 0;
			
		$EPS_REFERENCEID = $orderIncrementId;
		$EPS_AMOUNT = number_format($order->getGrandTotal(), 2, '.', '');
		$EPS_TIMESTAMP = gmdate('YmdHis');
		
		$EPS_FINGERPRINT = sha1($EPS_MERCHANT . '|' . $password . '|' . $EPS_TXNTYPE . '|' . $EPS_REFERENCEID . '|' . $EPS_AMOUNT . '|' . $EPS_TIMESTAMP);
		
		$request_data = array('EPS_MERCHANT' => $EPS_MERCHANT,
						'EPS_TXNTYPE' => $EPS_TXNTYPE,
						'EPS_REFERENCEID' => $EPS_REFERENCEID,
						'EPS_AMOUNT' => $EPS_AMOUNT,
						'EPS_TIMESTAMP' => $EPS_TIMESTAMP,
						'EPS_FINGERPRINT' => $EPS_FINGERPRINT,
						'EPS_RESULTURL' => $return_url,
						//'EPS_CALLBACKURL' => $notify_url,

						'EPS_CARDNUMBER' => Mage::getSingleton('core/session')->getCcNumber(),
						'EPS_EXPIRYMONTH' => sprintf('%02d', $payment->getCcExpMonth()),
						'EPS_EXPIRYYEAR' => $payment->getCcExpYear(),
						'EPS_CCV' => Mage::getSingleton('core/session')->getCcCid(),
						
						'EPS_CURRENCY' => $currency,
						'EPS_REDIRECT' => 'TRUE',


				);

		if ($this->getConfigData('secure_3d') == 1) {
			$request_data['3D_XID'] = $EPS_TIMESTAMP . substr($EPS_REFERENCEID, 0, 6);
			$request_data['EPS_MERCHANTNUM'] = $EPS_MERCHANTNUM;
			$request_data['EPS_IP'] = Mage::helper('core/http')->getRemoteAddr(true);
			$request_data['EPS_FIRSTNAME'] = $BAddress->getFirstname();
			$request_data['EPS_LASTNAME'] = $BAddress->getLastname();
			$request_data['EPS_ZIPCODE'] = $BAddress->getPostcode();
			$request_data['EPS_TOWN'] = $BAddress->getCity();
			$request_data['EPS_BILLINGCOUNTRY'] = $BAddress->getCountry();
			$request_data['EPS_DELIVERYCOUNTRY'] = $BAddress->getCountry();
			$request_data['EPS_EMAILADDRESS'] = $order->getCustomerEmail();
		}

		return array(
			'FormActionURL' => $this->getGatewayUrl(),
        	'request_data' => $request_data,
		);
        
    }

    public function cancel(Varien_Object $payment)
    {
        $payment->setStatus(self::STATUS_DECLINED)
            ->setLastTransId($this->getTransactionId());

        return $this;
    }

    /**
     * Return true if the method can be used at this time
     *
     * @return bool
     */
    public function isAvailable($quote=null)
    {
        if (!parent::isAvailable($quote)) {
            return false;
        }
		return true;
    }
}