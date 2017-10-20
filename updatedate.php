<?php

/* 
    Created on : Aug 18, 2015, 4:55:57 PM
    Author     : @Tran Trong Thang
    Email      : trantrongthang1207@gmail.com
    Skype      : trantrongthang1207
*/
require 'app/Mage.php';

$app = Mage::app('');
$coreResource = Mage::getSingleton('core/resource');
$write = $coreResource->getConnection('core_write');
$dbname = (string) Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');

$installer = $this;
$installer->startSetup();


$installer->addAttribute("customer", "doctor_email", array(
    "type" => "varchar",
    "backend" => "",
    "label" => "Doctor Email",
    "input" => "text",
    "source" => "",
    "visible" => true,
    "required" => false,
    "default" => "",
    "frontend" => "",
    "unique" => false,
    "note" => "this is email of doctor"
));

$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", "doctor_email");


$used_in_forms = array();

$used_in_forms[] = "adminhtml_customer";
$attribute->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", true)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 0)
        ->setData("sort_order", 100)
;
$attribute->save();



$installer->endSetup();
