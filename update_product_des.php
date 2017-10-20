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
    //$prodIds = array(16045, 16046, 16047, 16048, 16049);
    return $prodIds;
}

function replaceSrcImg($s) {
    if (empty($s))
        return "";
    $pattern = array('/https:\/\/admin.royyoungchemist.com.au/', '/http:\/\/staging.royyoungchemist.com.au/', '/https:\/\/staging.royyoungchemist.com.au/', '/http:\/\/admin.royyoungchemist.com.au/');
    $replacement = 'http://www.royyoungchemist.com.au';
    $s = preg_replace($pattern, $replacement, $s);
    return $s;
}

$produts = getAllProductIds();
if (count($produts) > 0) {
    foreach ($produts as $value_p) {
        $my_product = Mage::getModel('catalog/product')->load($value_p);
        $old_des = $my_product->getDescription();
        if (!empty($old_des)) {
            $new_des = replaceSrcImg(trim($old_des));
            echo "ProductID = " . $value_p . '; product name = ' . $my_product->getName() . " successfull\n ";
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