<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '1024M'); 
require_once('/var/www/html/royyoungchemist.com.au/application/site/app/Mage.php');
umask(0);
Mage::app();

createAttribute("pharmacist_only_s3", "Pharmacist only-S3", "boolean", "simple");


function createAttribute($code, $label, $attribute_type, $product_type)
{		
	$_attribute_data = array(
		'attribute_code' => $code,
		'is_global' => '1',
		'frontend_input' => $attribute_type, //'boolean',
		'default_value_text' => '',
		'default_value_yesno' => '0',
		'default_value_date' => '',
		'default_value_textarea' => '',
		'is_unique' => '0',
		'is_required' => '0',
		'apply_to' => '0',
		'is_configurable' => '0',
		'is_searchable' => '0',
		'is_visible_in_advanced_search' => '0',
		'is_comparable' => '0',
		'is_used_for_price_rules' => '0',
		'is_wysiwyg_enabled' => '0',
		'is_html_allowed_on_front' => '0',
		'is_visible_on_front' => '0',
		'used_in_product_listing' => '0',
		'used_for_sort_by' => '0',
		'frontend_label' => $label
	);
 
 
	$model = Mage::getModel('catalog/resource_eav_attribute');
 
	if (!isset($_attribute_data['is_configurable'])) {
		$_attribute_data['is_configurable'] = 0;
	}
	if (!isset($_attribute_data['is_filterable'])) {
		$_attribute_data['is_filterable'] = 0;
	}
	if (!isset($_attribute_data['is_filterable_in_search'])) {
		$_attribute_data['is_filterable_in_search'] = 0;
	}
 
	if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
		$_attribute_data['backend_type'] = $model->getBackendTypeByInput($_attribute_data['frontend_input']);
	}
 
 
 
	$model->addData($_attribute_data);
 
	$model->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
	$model->setIsUserDefined(1);
 
 
	try {
		$model->save();
	} catch (Exception $e) { echo '<p>Sorry, error occured while trying to save the attribute. Error: '.$e->getMessage().'</p>'; }
}





?>
