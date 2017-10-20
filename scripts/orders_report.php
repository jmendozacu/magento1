<?php

error_reporting(0); ini_set('display_errors', 1);

// define('MAGENTO_ROOT', getcwd());
define('MAGENTO_ROOT', dirname(dirname(__FILE__)));

$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require_once $mageFilename;
umask(0);

ini_set('display_errors', 1);

/* Store or website code */
$mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';

/* Run store or run website */
$mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';

Mage::app($mageRunCode,$mageRunType);

Mage::setIsDeveloperMode(true);

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$table = $resource->getTableName('sales_flat_order_item');
//$myDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 month" ) );
$first_day = date('Y-m-d 00:00:00', strtotime("-7 days"));
echo $first_day;

$query =
	'SELECT store_id, product_type, name, sku, sum(qty_invoiced), sum(row_total) FROM sales_flat_order_item
	WHERE sku = "443749" and price > 0 AND created_at > "' . $first_day . '" 
	GROUP BY sku ORDER BY sku LIMIT 10000';
		

// $result = $readConnection->fetchOne($query);
$results = $readConnection->fetchAll($query);

var_dump($results);

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=last90days.xls");
$numsep = ","; $ndl='.';
echo "store\ttype\tname\tsku\tmain sku\tmain sku2\tsize\tqty\trevenue". "\n";
foreach($results as $row) {

	$store = $row[0];
	$type = $row[1];
	$name = $row[2];
	$sku = $row[3];
	$tmp_sku = preg_split('/[-_.]/',$sku);
	$mainsku = (isset($tmp_sku[1]))? $tmp_sku[0] . "." . $tmp_sku[1] : $tmp_sku;
	$mainsku2 = (isset($tmp_sku[2]))? $mainsku . "." . $tmp_sku[2] : $mainsku;
	$size = end($tmp_sku);
	$qty = $row[4];
	$rev = $row[5];
	echo $store . "\t" .
	$type . "\t" .
	$name . "\t" .
	$sku . "\t" .
	$mainsku . "\t" .
	$mainsku2 . "\t" .
	$size . "\t" .
	(int)$qty . "\t" .
	number_format ($rev, 2, $numsep, ' ') . "\t" . "\n";

}


// strrpos($sku, ".")-strlen($sku)+1

exit;

