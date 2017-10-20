<?php
/**
 * Nublue_NewsletterRecaptcha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * @category    Nublue
 * @package     Nublue_NewsletterRecaptcha
 * @copyright   Copyright (c) 2017 Nublue Ltd (http://www.nublue.co.uk)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Nublue_NewsletterRecaptcha_Helper_Data extends Mage_Core_Helper_Abstract
{
    private $path = "nublue_newsletter_recaptcha/settings/";
    
    public function getConfig($key,$type="exact") {
        $val = Mage::getStoreConfig($this->path.$key);
        if($type == 'bool'){
            $val = filter_var($val, FILTER_VALIDATE_BOOLEAN);
        }
        return $val;
    }
}