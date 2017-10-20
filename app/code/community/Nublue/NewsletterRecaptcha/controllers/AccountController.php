<?php

require_once "Mage/Customer/controllers/AccountController.php";

class Nublue_NewsletterRecaptcha_AccountController extends Mage_Customer_AccountController {

    /**
     * Create customer account action
     */
    public function createPostAction() {
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
            parent::createPostAction();
        } catch (Mage_Core_Exception $e) {
            $session->addException($e, $this->__('There was a problem with Create an Account sign-up: %s', $e->getMessage()));
            $this->_redirectReferer();
        } catch (Exception $e) {
            $session->addException($e, $this->__('There was a problem with your Create an Account sign-up.'));
            $this->_redirectReferer();
        }
    }

}
