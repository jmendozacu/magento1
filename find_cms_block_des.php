<?php

ini_set('memory_limit', '3072M');
set_time_limit(0);
require 'app/Mage.php';
$app = Mage::app();
umask(0);

$helper = Mage::helper('cms');
$block = Mage::getModel('cms/block')->getCollection()->toOptionArray();
foreach ($block as $value) {
    $block = Mage::getModel('cms/block')->setStoreId(Mage::app()->getStore()->getId())->load($value['value']);
    $content = $block->getContent();
    if (!empty($content)) {
        $pattern = 'dev.cafeideas';
        if (strpos($content, $pattern)) {
            echo 'See: CMS = ' . $value['value'] . ' dev.cafeideas' . strpos($content, $pattern) . " \n";
        }
        $pattern = 'http://dev.cafeideas';
        if (strpos($content, $pattern)) {
            echo 'See: CMS = ' . $value['value'] . ' http://dev.cafe' . strpos($content, $pattern) . " \n";
        }
        $pattern = 'https://dev.cafeideas.com.au/';
        if (strpos($content, $pattern)) {
            echo 'See: CMS = ' . $value['value'] . ' http://dev.cafe' . strpos($content, $pattern) . " \n";
        }
        $pattern = 'https://dev.cafeideas.com.au/';
        if (strpos($content, $pattern)) {
            echo 'See: CMS = ' . $value['value'] . ' https://www.cafeideas' . strpos($content, $pattern) . " \n";
        }
        $pattern = 'http://dev.cafeideas.com.au/';
        if (strpos($content, $pattern)) {
            echo 'See: CMS = ' . $value['value'] . ' http://www.cafeideas' . strpos($content, $pattern) . " \n";
        } else {
            echo "Not see CMS = " . $value['value'] . " \n";
        }
    } else {
        echo "========ProductID = " . $content . " No data\n";
    }
}
exit();
?>