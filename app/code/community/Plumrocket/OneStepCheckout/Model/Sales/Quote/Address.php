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


class Plumrocket_OneStepCheckout_Model_Sales_Quote_Address extends Mage_Sales_Model_Quote_Address
{

	/**
	* Perform basic validation
	*
	* @return void
	*/
	protected function _basicCheck()
	{
		if (!Zend_Validate::is($this->getFirstname(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the first name.'));
		}

		if (!Zend_Validate::is($this->getLastname(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the last name.'));
		}

		if (!Zend_Validate::is($this->getStreet(1), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the street.'));
		}

		if (!Zend_Validate::is($this->getCity(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the city.'));
		}

		if (Mage::helper('onestepcheckout')->getConfigAddressFieldRequired('telephone') && !Zend_Validate::is($this->getTelephone(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the telephone number.'));
		}

		if (Mage::helper('onestepcheckout')->getConfigAddressFieldRequired('fax') && !Zend_Validate::is($this->getFax(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the fax number.'));
		}

		if (Mage::helper('onestepcheckout')->getConfigAddressFieldRequired('company') && !Zend_Validate::is($this->getCompany(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the company name.'));
		}

		$_havingOptionalZip = Mage::helper('directory')->getCountriesWithOptionalZip();
		if (!in_array($this->getCountryId(), $_havingOptionalZip)
			&& !Zend_Validate::is($this->getPostcode(), 'NotEmpty')
		) {
			$this->addError(Mage::helper('customer')->__('Please enter the zip/postal code.'));
		}

		if (!Zend_Validate::is($this->getCountryId(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the country.'));
		}

		if ($this->getCountryModel()->getRegionCollection()->getSize()
			&& !Zend_Validate::is($this->getRegionId(), 'NotEmpty')
			&& Mage::helper('directory')->isRegionRequired($this->getCountryId())
		) {
			$this->addError(Mage::helper('customer')->__('Please enter the state/province.'));
		}
    }


    /**
     * Validate address attribute values
     *
     * @return array | bool
     */
    public function validate()
    {
        $this->_resetErrors();

        $this->implodeStreetAddress();

        if (!Zend_Validate::is($this->getFirstname(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the first name.'));
        }

        if (!Zend_Validate::is($this->getLastname(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the last name.'));
        }

        if (!Zend_Validate::is($this->getStreet(1), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the street.'));
        }

        if (!Zend_Validate::is($this->getCity(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the city.'));
        }

		if (Mage::helper('onestepcheckout')->getConfigAddressFieldRequired('telephone') && !Zend_Validate::is($this->getTelephone(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the telephone number.'));
		}

		if (Mage::helper('onestepcheckout')->getConfigAddressFieldRequired('fax') && !Zend_Validate::is($this->getFax(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the fax number.'));
		}

		if (Mage::helper('onestepcheckout')->getConfigAddressFieldRequired('company') && !Zend_Validate::is($this->getCompany(), 'NotEmpty')) {
			$this->addError(Mage::helper('customer')->__('Please enter the company name.'));
		}

        $_havingOptionalZip = Mage::helper('directory')->getCountriesWithOptionalZip();
        if (!in_array($this->getCountryId(), $_havingOptionalZip)
            && !Zend_Validate::is($this->getPostcode(), 'NotEmpty')
        ) {
            $this->addError(Mage::helper('customer')->__('Please enter the zip/postal code.'));
        }

        if (!Zend_Validate::is($this->getCountryId(), 'NotEmpty')) {
            $this->addError(Mage::helper('customer')->__('Please enter the country.'));
        }

        if ($this->getCountryModel()->getRegionCollection()->getSize()
               && !Zend_Validate::is($this->getRegionId(), 'NotEmpty')
               && Mage::helper('directory')->isRegionRequired($this->getCountryId())
        ) {
            $this->addError(Mage::helper('customer')->__('Please enter the state/province.'));
        }

        Mage::dispatchEvent('customer_address_validation_after', array('address' => $this));

        $errors = $this->_getErrors();

        $this->_resetErrors();

        if (empty($errors) || $this->getShouldIgnoreValidation()) {
            return true;
        }
        return $errors;
    }

}