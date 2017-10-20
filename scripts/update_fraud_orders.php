<?php
require_once('/var/www/html/royyoungchemist.com.au/application/site/app/Mage.php');

Mage::app();

//$orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('status', 'fraud');

$orders = array(11023068,11022868,11022775,11022029);

foreach ($orders as $orderId) {

	$order = Mage::getModel('sales/order')->load($orderId);
	$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();


}

?>

