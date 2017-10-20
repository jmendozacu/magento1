<?php

ini_set('memory_limit', '3072M');
set_time_limit(0);
require 'app/Mage.php';
$app = Mage::app();
umask(0);

function getAllProductIds() {
    $prs = Mage::getModel('catalog/product')
	    ->getCollection()
	    ->addAttributeToFilter('status', array('eq' => 1));

    $prodIds = $prs->getAllIds();
    return $prodIds;
}

function removeMultipleLineBreak($s) {
    if (empty($s))
	return "";
    $s = @preg_replace("/\<br\/\>/", "", $s);
    $s = @preg_replace("/(\n){2,}/", "\n", trim($s));
    $your_array = @explode("\n", $s);
    $res = array();
    foreach ($your_array as $value) {
	$value = trim($value);
	if (!empty($value))
	    $res[] = $value;
    }
    $s = implode("\n", $res);
    return $s;
}

$produts = getAllProductIds();
if (count($produts) > 0) {
    foreach ($produts as $value_p) {
	$my_product = Mage::getModel('catalog/product')->load($value_p);
	$old_des = $my_product->getDescription();
	if (!empty($old_des)) {
	    $new_des = removeMultipleLineBreak(trim($old_des));
	    echo "ProductID = " . $value_p . " successfull\n";
	    Mage::getSingleton('catalog/product_action')->updateAttributes(array($value_p), array('description' => $new_des));
	} else {
	    echo "========ProductID = " . $value_p . " No data\n";
	}
    }
    echo 'END';
} else {
    echo 'No data';
}
exit();
?>