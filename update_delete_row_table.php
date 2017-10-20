<?php

ini_set('max_execution_time', 600);
ini_set('memory_limit', '1024M');

require 'app/Mage.php';
$app = Mage::app('');
$coreResource = Mage::getSingleton('core/resource');
$write = $coreResource->getConnection('core_write');
$dbname = (string) Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');
$query = "DELETE FROM `shipping_matrixrate`";
$write->query($query);

$query = "ALTER TABLE `shipping_matrixrate` CHANGE `condition_from_value` `condition_from_value` DECIMAL(12,5) NOT NULL DEFAULT '0.0000'";
$write->query($query);

$query = "ALTER TABLE `shipping_matrixrate` CHANGE `condition_to_value` `condition_to_value` DECIMAL(12,5) NOT NULL DEFAULT '0.0000'";
$write->query($query);

$query = "ALTER TABLE `shipping_matrixrate` CHANGE `price` `price` DECIMAL(12,5) NOT NULL DEFAULT '0.0000'";
$write->query($query);

$query = "ALTER TABLE `shipping_matrixrate` CHANGE `cost` `cost` DECIMAL(12,5) NOT NULL DEFAULT '0.0000'";
$write->query($query);
?>
