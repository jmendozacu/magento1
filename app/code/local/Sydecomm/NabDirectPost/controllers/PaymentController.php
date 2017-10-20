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
 
class Sydecomm_NabDirectPost_PaymentController extends Mage_Core_Controller_Front_Action {

	public function creditcardAction()
	{
        $nabdirectpost = Mage::getModel('nabdirectpost/NabDirectPost');
        $forms = $nabdirectpost->getFormFields();

			$html = '<html><head><title>Complete Payment</title></head><body><div style="margin: auto; text-align: center;">Payment is being processed. Please do not refresh page till payment confirmation page appears.';
			$html .= '<form action="' . $forms['FormActionURL'] . '" method="post" name="nabdirectpost_checkout" id="nabdirectpost_checkout" style="display:none">';
			foreach($forms['request_data'] as $key=>$value){
				$html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
			}
			$html .= '</form></div>';
			$html .= ' <script type="text/javascript">';
			$html .= ' document.nabdirectpost_checkout.submit();';
			$html .= ' </script></body></html>';
			echo $html;
	}
	
	public function successAction()
	{
		$merchant = Mage::getStoreConfig( 'payment/nabdirectpost/merchant_code' );
		$password = Mage::getStoreConfig( 'payment/nabdirectpost/secure_key' );
		$fingerprint = $_REQUEST['fingerprint'];
		$timestamp = $_REQUEST['timestamp'];
		$summarycode = $_REQUEST['summarycode'];
        $restext = $_REQUEST['restext'];
		$txnid = $_REQUEST['txnid'];
		$refid = $_REQUEST['refid'];
		
		$order = Mage::getModel('sales/order')->loadByIncrementId($refid);
		$amount = $order->getGrandTotal();
		$amount = number_format($amount, 2, '.', '');
		
		$check_fingerprint = sha1($merchant . '|' . $password . '|' . $refid . '|' . $amount . '|' . $timestamp . '|' . $summarycode);
		if ($check_fingerprint == $fingerprint && $summarycode == 1) {
            $order->sendNewOrderEmail();

            if ($order->canInvoice())
            {
                $invoice = $order->prepareInvoice();
                $invoice->register()->capture();
                Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder())
                    ->save();
            }

            $transaction = Mage::getModel('sales/order_payment_transaction');
            $transaction->setTxnId($txnid);
            $transaction->setOrderPaymentObject($order->getPayment())->setTxnType(Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE);
            $transaction->save();

            $order_status = Mage::helper('core')->__('Payment is successful.');
            $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_PROCESSING, $order_status);
            $order->save();

			$redirect_url = 'checkout/onepage/success';
		} else {

            // NAB Error msg:
            // Mage::getSingleton('core/session')->addError(Mage::helper('core')->__($restext));

            $order->registerCancellation( 'Payment failed: ' . $restext, true )->save();

			$redirect_url = 'nabdirectpost/payment/failure';

		}
		
		$this->_redirect($redirect_url);
	}
	

	
	public function failureAction()
	{
		$checkout = Mage::getSingleton('checkout/session');
       	$lastOrderId = $checkout->getLastRealOrderId();
		$lastQuoteId = $checkout->getLastQuoteId();

        Mage::getSingleton('core/session')->setNabError('1');
        Mage::getSingleton('checkout/session')->setFirstTimeChk('0');

        $quote = Mage::getModel('sales/quote')->load($lastQuoteId);
        $quote->setIsActive(true)->save();
		
		$this->_redirect('checkout/');
	}
}