<?php

/*
  Created on : Sep 28, 2015, 9:45:55 AM
  Author     : @Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
  Skype      : trantrongthang1207
 */

echo "here 1";

ini_set('max_execution_time', 600);
ini_set('memory_limit', '1024M');

echo "here 2";

require 'app/Mage.php';

$app = Mage::app('');
$coreResource = Mage::getSingleton('core/resource');
$write = $coreResource->getConnection('core_write');
$dbname = (string) Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');

echo "here 3";

$query = "CREATE TABLE IF NOT EXISTS osconnectkeys (
  `key_id` int(10) unsigned NOT NULL auto_increment,
  `key` varchar(250) NOT NULL default '',
  `creation_date` datetime NOT NULL default '0000-00-00',
  PRIMARY KEY  (`key_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$write->query($query);

echo "here 4";

$query2 = "INSERT INTO osconnectkeys VALUES (null,CONCAT(MD5(NOW()), MD5(CURTIME())), NOW());";
$write->query($query2);

echo "here 5";

$query3 = "INSERT INTO osconnectkeys VALUES (null,'https://secure.onesaas.com/signin/start', NOW());";
$write->query($query3);
echo "here 6";
?>