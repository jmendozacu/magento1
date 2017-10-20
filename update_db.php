<?php
 
ini_set('max_execution_time', 600);
ini_set('memory_limit', '1024M');

require 'app/Mage.php';
$app = Mage::app('');
$coreResource = Mage::getSingleton('core/resource');
$write = $coreResource->getConnection('core_write');
$dbname = (string) Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');
$query = "ALTER TABLE tv_iwd_storelocator ADD COLUMN `image` VARCHAR(255) NULL AFTER `icon`;	";
$write->query($query);
?>