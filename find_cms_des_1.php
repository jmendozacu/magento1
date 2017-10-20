<?php

ini_set('memory_limit', '3072M');
set_time_limit(0);
require 'app/Mage.php';
$app = Mage::app();
umask(0);

$helper = Mage::helper('cms');
$cms = Mage::getModel('cms/page')->getCollection()->toOptionArray();
$page = Mage::getModel('cms/page');
foreach ($cms as $value) {
    //print_r($value['value']);
    $model = Mage::getModel('cms/page')->load($value['value'], 'identifier');
    echo '<h2>' . $model->getContentHeading() . '<h2>';
    echo $model->getContent();
}
exit();
?>