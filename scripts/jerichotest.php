<?php
require_once('/var/www/html/royyoungchemist.com.au/application/site/app/Mage.php');
Mage::app();
$store_id = 3;
$jericho_magento = Mage::getModel('jericho_magento/SMPro', $store_id);


print_r($jericho_magento->transactionalListId);
