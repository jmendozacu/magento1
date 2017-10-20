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


class Plumrocket_OneStepCheckout_Helper_DefaultFields extends Mage_Core_Helper_Abstract
{
	private $_data = null;

	public function getData()
	{
		if (is_null($this->_data)) {
			$fields = array(
				'telephone'			=> 'Telephone',
				'fax'				=> 'Fax',
				'company'			=> 'Company',

				'password' 			=> 'Password',
				'confirm_password' 	=> 'Confirm Password',
			);

			$result = array();
			foreach ($fields as $key => $label) {
				$result[$key] = array(
					'name'			=> $key,
					'orig_label' 	=> $label,
					'enable'		=> 0,
					'required'		=> 0,
				);
			}
			$this->_data = $result;
		}
		return $this->_data;
	}

}
	 
