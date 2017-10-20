<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('memory_limit', '1024M'); 

set_time_limit(0);

$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

require_once('/var/www/html/royyoungchemist.com.au/application/site/app/Mage.php');

umask(0);

foreach (Mage::app()->getWebsites() as $website) {
    foreach ($website->getGroups() as $group) {
        $stores = $group->getStores();
        foreach ($stores as $store) {
            echo $store->getId() ." ".$store->getName()."\n";
        }
    }
}

?> 

