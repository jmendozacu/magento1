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


class Plumrocket_OneStepCheckout_Model_Values_TermsConditionsPage
{
	public function toOptionArray()
	{
		$pages = Mage::getModel('cms/page')->getCollection()->toOptionArray();

		return $pages;
	}
}