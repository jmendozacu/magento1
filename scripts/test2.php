<?php

//ini_set('display_errors', '1');
//ini_set('memory_limit', '8192M');
//set_time_limit(0);
require '../app/Mage.php';
Mage::app();
$iDefaultStoreId = Mage::app()
    ->getWebsite(true)
    ->getDefaultGroup()
    ->getDefaultStoreId();
$product = Mage::getModel('catalog/product')->load(286);
echo $inStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getIsInStock();
//echo $product->getStoreId();
$_store = $product->getStore();
//print_r($product->getWebsiteIds());
if ($product->getTypeId() == 'configurable') {
    $childProducts = Mage::getModel('catalog/product_type_configurable')
            ->getUsedProducts(null, $product);
} elseif ($product->getTypeId() == 'grouped') {
    $childProducts = Mage::getModel('catalog/product_type_grouped')
            ->getAssociatedProducts($product);
}
$childProducts = Mage::getModel('catalog/product_type_configurable')
            ->getParentIdsByChild(402);
    print_r(count($childProducts));
//echo count($childProducts);
foreach ($childProducts as $child) {
    $pChildren = Mage::getModel('catalog/product')->load($child->getId());
    // echo $child->getId();
    //echo $pChildren->getName() . '<br>';
    //echo $pChildren->getSku() . '<br>';
}
exit();
//D:\www\2017\jetpilot.com.au\shop\app\design\frontend\rwd\_jp\template\catalog\product\view\media.phtml
//D:\www\2017\jetpilot.com.au\shop\app\design\frontend\rwd\_jp\template\catalog\product\view.phtml