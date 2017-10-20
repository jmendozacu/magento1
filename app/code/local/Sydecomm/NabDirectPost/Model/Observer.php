<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Sydecomm
 * @package    Sydecomm_NabDirectPost
 * @copyright  Copyright (c) 2015 Vnphpexpert.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Sydecomm_NabDirectPost_Model_Observer
{

    public function errorAction(Varien_Event_Observer $observer)
    {
        if(Mage::getSingleton('core/session')->getNabError() == 1){
			Mage::getSingleton('core/session')->setNabError('');
    		Mage::getSingleton('core/session')->addError('Payment was unsuccessful, please try again');
		}
    }
}