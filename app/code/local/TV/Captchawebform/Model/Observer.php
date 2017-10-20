<?php

class TV_Captchawebform_Model_Observer {

    public function webformsFieldsTypes(Varien_Object $observer) {
	$types = $observer->getTypes();

	// add new custom field Box
	$types->setData('custom/captchawebform', Mage::helper('captchawebform')->__('Custom / Captcha WebFrom'));
    }

    public function webformsFieldsTohtmlHtml(Varien_Object $observer) {
	$field = $observer->getField();
	//print_r($field);

	$form_id = $field->getWebformId();

	$validate_message = $field->getValidateMessage();

	// create frontend html code for the Box
	if ($field->getType() == 'custom/captchawebform') {
	    $field_name = "field[{$field->getId()}]";
	    $config = array(
		'field_name' => $field_name,
		'field' => $field,
		'webform_id' => $form_id,
		'validate_message' => $validate_message,
		'template' => 'captchawebform/box.phtml'
	    );
	    $html = Mage::app()->getLayout()->createBlock('core/template', $field_name, $config)->toHtml();
	    $observer->getHtmlObject()->setHtml($html);
	}
    }

    public function postWebForm($observer) {
	file_put_contents('test.txt', 'chao ban han hanh duoc don tiep ban');
    }

//    public function webformsBlockAdminhtmlResultsGridPrepareColumnsConfig(Varien_Object $observer) {
//	$field = $observer->getField();
//	$config = $observer->getConfig();
//
//	// add grid column renderer for our custom field
//	switch ($field->getType()) {
//	    case 'custom/captchawebform':
//		$config->setRenderer('TV_Captchawebform_Block_Adminhtml_Renderer_BoxColumn');
//		break;
//	}
//    }
//
//    public function webformsBlockAdminhtmlResultsEditFormPrepareLayoutField(Varien_Object $observer) {
//	/** @var Varien_Data_Form $form */
//	$fieldset = $observer->getFieldset();
//	$field = $observer->getField();
//	$config = $observer->getConfig();
//
//	// add new field type to admin form
//	$fieldset->addType('box', Mage::getConfig()->getBlockClassName('captchawebform/adminhtml_element_box'));
//
//	switch ($field->getType()) {
//	    case 'custom/captchawebform':
//		$config->setType('box');
//		break;
//	}
//    }
//
//    public function webformsResultsTohtmlValue(Varien_Object $observer) {
//	/** @var Varien_Object $value */
//	$value = $observer->getValue();
//
//	/** @var VladimirPopov_WebForms_Model_Fields $field */
//	$field = $observer->getField();
//
//	// prepare custom field for result visualisation
//	switch ($field->getType()) {
//	    case 'custom/captchawebform':
//		$json_data = $value->getValue();
//		$box_array = json_decode($json_data, true);
//
//		$str = array();
//		for ($i = 0; $i < count($box_array); $i++) {
//		    $str[] = $box_array[$i]["width"] . "cm x " . $box_array[$i]["height"] . "cm x " . $box_array[$i]["depth"] . "cm";
//		}
//		$value->setHtml(implode('<br>', $str));
//		break;
//	}
//    }
}
