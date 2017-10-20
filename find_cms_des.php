<?php

ini_set('memory_limit', '3072M');
set_time_limit(0);
require '../app/Mage.php';
$app = Mage::app();
umask(0);

$helper = Mage::helper('cms');
$cms = Mage::getModel('cms/page')->getCollection()->toOptionArray();
//$page = Mage::getModel('cms/page');
foreach ($cms as $value) {
    //print_r($value['value']);
    $model = Mage::getModel('cms/page')->load($value['value'], 'identifier');
    $content = $model->getContent();
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
        echo "========ProductID = " . $my_product->getName() . " No data\n";
    }
}
exit();
?>