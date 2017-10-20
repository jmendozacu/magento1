<?php

$installer = $this;
$installer->startSetup();


$installer->addAttribute("customer", "quick_screen2", array(
    "type" => "datetime",
    "backend" => "eav/entity_attribute_backend_datetime",
    "label" => "Quick Screen",
    "input" => "date",
    "source" => "",
    "visible" => true,
    "required" => false,
    "default" => "",
    "frontend" => "",
    "unique" => false,
    "note" => ""
));

$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", "quick_screen2");


$used_in_forms = array();

/*
 * Cac truong nay se duoc thuoc hien tren cac form nao
 * neu muon no duoc thuoc hien tren form customer trong admin thi chung ta thuc 
 * hien them mang gia tri "adminhtml_customer" vao mang $user_in_form
 */
$used_in_forms[] = "adminhtml_customer";
//$used_in_forms[]="checkout_register";
//$used_in_forms[]="customer_account_create";
//$used_in_forms[]="customer_account_edit";
//$used_in_forms[]="adminhtml_checkout";
$attribute->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 100)
;
$attribute->save();




$installer->addAttribute("customer", "full_investigation2", array(
    "type" => "datetime",
    "backend" => "eav/entity_attribute_backend_datetime",
    "label" => "Full Investigation",
    "input" => "date",
    "source" => "",
    "visible" => true,
    "required" => false,
    "default" => "",
    "frontend" => "",
    "unique" => false,
    "note" => ""
));

$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", "full_investigation2");


$used_in_forms = array();

$used_in_forms[] = "adminhtml_customer";
//$used_in_forms[]="checkout_register";
//$used_in_forms[]="customer_account_create";
//$used_in_forms[]="customer_account_edit";
//$used_in_forms[]="adminhtml_checkout";
$attribute->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", false)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 100)
;
$attribute->save();



$installer->endSetup();
