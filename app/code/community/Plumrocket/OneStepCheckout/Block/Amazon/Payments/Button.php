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


if (class_exists('Amazon_Payments_Block_Button')){
	class Plumrocket_OneStepCheckout_Block_Amazon_Payments_Button extends Amazon_Payments_Block_Button
	{

	}
} else {
	class Plumrocket_OneStepCheckout_Block_Amazon_Payments_Button extends Mage_Core_Block_Template
	{

	}
}