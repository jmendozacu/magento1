<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please 
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_One_Step_Checkout
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


class Plumrocket_OneStepCheckout_Model_Backend_FormFields extends Mage_Core_Model_Config_Data
{
	public function parseValue($value)
	{
		$result = Mage::helper('onestepcheckout/defaultFields')->getData();
		$values = json_decode($value);
		if ($values) {
			foreach ($values as $name => $value) {
				if (is_array($value) && array_key_exists($name, $result)) {
					$result[$name]['enable'] = (isset($value[0]))? (int)$value[0]: $result[$name]['enable'];
					$result[$name]['required'] = (isset($value[1]))? (int)$value[1]: $result[$name]['required'];
				}
			}
		}
		return $result;
	}


    protected function _afterLoad()
    {
        $value = $this->parseValue($this->getValue());
		$this->setValue($value);
		parent::_afterLoad();
    }
 
    protected function _beforeSave()
    {
    	$toSave = array();
    	$values = $this->getValue();
    	$result = Mage::helper('onestepcheckout/defaultFields')->getData();

    	foreach ($values as $name => $value) {
    		if (array_key_exists($name, $result) && (int)isset($value['enable'])) {
    			$toSave[$name] = array(
    				(int)isset($value['enable']),
    				(int)isset($value['required'])
    			);
    		}
    	}
        if ( array_key_exists('password', $toSave) && $toSave['password'][0] ) {
            $toSave['password'][1] = 1;
        } elseif (array_key_exists('confirm_password', $toSave)) {
            unset($toSave['confirm_password']);
        }
        if ( array_key_exists('confirm_password', $toSave) && $toSave['confirm_password'][0] ) {
            $toSave['confirm_password'][1] = 1;
        }

        //$attribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer_address', 'telephone');
        $requiredPhone      = (array_key_exists('telephone', $toSave) && $toSave['telephone'][1]) ? 1 : 0;
        $requiredFax        = (array_key_exists('fax', $toSave) && $toSave['fax'][1]) ? 1 : 0;
        $requiredCompany    = (array_key_exists('company', $toSave) && $toSave['company'][1]) ? 1 : 0;

        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
        $setup->startSetup();
        $setup->updateAttribute('customer_address', 'telephone', 'is_required', $requiredPhone);
        $setup->updateAttribute('customer_address', 'fax', 'is_required', $requiredFax);
        $setup->updateAttribute('customer_address', 'company', 'is_required', $requiredFax);
        $setup->endSetup();

    	$this->setValue(json_encode($toSave));
        parent::_beforeSave();
    }
}