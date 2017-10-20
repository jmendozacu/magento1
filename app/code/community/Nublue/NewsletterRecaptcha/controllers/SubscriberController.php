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
include Mage::getModuleDir('controllers', 'Mage_Newsletter') . DS . 'SubscriberController.php';

class Nublue_NewsletterRecaptcha_SubscriberController extends Mage_Newsletter_SubscriberController {

    /**
     * New subscription action
     */
    public function newAction() {
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session = Mage::getSingleton('core/session');
            $customerSession = Mage::getSingleton('customer/session');
            $recaptcha = (string) $this->getRequest()->getPost('g-recaptcha-response');
            if ($recaptcha == '')
                $recaptcha = $_REQUEST['g-recaptcha-response'];
            $helper = Mage::helper('nublue_newsletter_recaptcha');

            try {

                $recaptchaEnabled = $helper->getConfig('enabled', 'bool');
                if ($recaptchaEnabled) {
                    $sitekey = $helper->getConfig('sitekey');
                    $secretkey = $helper->getConfig('secretkey');

                    // Double check that both config fields are populated before continuing
                    if (!empty($sitekey) && !empty($secretkey)) {

                        if (empty($recaptcha)) {
                            Mage::throwException($this->__('We did not receive your reCAPTCHA token. Please re-submit the form.'));
                        }
                        $recaptchaURL = "https://www.google.com/recaptcha/api/siteverify";

                        $fields = array(
                            'secret' => $secretkey,
                            'response' => $recaptcha,
                        );
                        $fieldsStr = "";
                        foreach ($fields as $key => $value)
                            $fieldsStr .= $key . '=' . $value . '&';
                        rtrim($fieldsStr, '&');

                        $ch = curl_init(); //open connection
                        curl_setopt($ch, CURLOPT_URL, $recaptchaURL);
                        curl_setopt($ch, CURLOPT_POST, count($fields));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsStr);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curlResult = curl_exec($ch); //execute post
                        curl_close($ch); //close connection

                        if ($curlResult && !empty($curlResult)) {
                            $result = json_decode($curlResult, true);
                            if ($result['success'] !== true) {
                                Mage::throwException($this->__('Your reCAPTCHA token was not valid. Please re-submit the form.'));
                            }
                            if (!isset($result['hostname']) OR ( isset($result['hostname']) AND $result['hostname'] != $_SERVER['HTTP_HOST'] )) {
                                Mage::throwException($this->__('Your reCAPTCHA token was not valid. Please re-submit the form.'));
                            }
                        } else {
                            Mage::throwException($this->__('reCAPTCHA verification failed. Please contact the site administrator.'));
                        }
                    }
                }
                // If no errors then call the original function from parent (Mage_Newsletter_SubscriberController)
                parent::newAction();
            } catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with newsletter sign-up: %s', $e->getMessage()));
                $this->_redirectReferer();
            } catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with your newsletter sign-up.'));
                $this->_redirectReferer();
            }
        }
    }

}
