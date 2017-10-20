<?php

ini_set('memory_limit', '8192M');
set_time_limit(0);
require '../app/Mage.php';
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
            $pattern = 'https://admin.royyoungchemist.com.au';
            if (strpos($old_des, $pattern)) {
                echo 'See: ProductID = ' . $value_p . ' https://admin' . strpos($old_des, $pattern) . " \n";
                $pattern = 'http://admin.royyoungchemist.com.au';
            } else if (strpos($old_des, $pattern)) {
                echo 'See: ProductID = ' . $value_p . ' http://admin' . strpos($old_des, $pattern) . " \n";
                $pattern = 'https://staging.royyoungchemist.com.au';
            } else if (strpos($old_des, $pattern)) {
                echo 'See: ProductID = ' . $value_p . ' https://staging' . strpos($old_des, $pattern) . " \n";
                $pattern = 'http://staging.royyoungchemist.com.au';
            } else if (strpos($old_des, $pattern)) {
                echo 'See: ProductID = ' . $value_p . ' http://staging' . strpos($old_des, $pattern) . " \n";
            } else {
                //echo "Not see:========ProductID = " . $value_p . 'Product name ' . $my_product->getName() . " \n";
            }
        } else {
            echo "========ProductID = " . $my_product->getName() . " No data\n";
        }
    }
    echo 'END';
} else {
    echo 'No data';
}
exit();
?>