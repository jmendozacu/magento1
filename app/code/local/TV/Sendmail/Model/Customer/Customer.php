<?php

require_once("Mage/Customer/Model/Customer.php");

class Tv_Sendmail_Model_Customer_Customer extends Mage_Customer_Model_Customer {

    /**
     * Send email with new account related information
     *
     * @param string $type
     * @param string $backUrl
     * @param string $storeId
     * @throws Mage_Core_Exception
     * @return Mage_Customer_Model_Customer
     */
    public function sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0') {
        $types = array(
            'registered' => self::XML_PATH_REGISTER_EMAIL_TEMPLATE, // welcome email, when confirmation is disabled
            'confirmed' => self::XML_PATH_CONFIRMED_EMAIL_TEMPLATE, // welcome email, when confirmation is enabled
            'confirmation' => self::XML_PATH_CONFIRM_EMAIL_TEMPLATE, // email with confirmation link
        );
        if (!isset($types[$type])) {
            Mage::throwException(Mage::helper('customer')->__('Wrong transactional account email type'));
        }

        if (!$storeId) {
            $storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
        }
        $this->_sendEmailTemplate($types[$type], self::XML_PATH_REGISTER_EMAIL_IDENTITY, array('customer' => $this, 'back_url' => $backUrl), $storeId);
        $this->_sendEmailTemplate2('tv_sendmail_create_account_wellcome_email_template', self::XML_PATH_REGISTER_EMAIL_IDENTITY, array('customer' => $this, 'back_url' => $backUrl), $storeId);

        /*
         * Added by: Tran Trong Thang
         * Email: trantrongthang1207@gmail.com
         */

        $addresses = $this->getAddresses();
        $address = $addresses[0];

        $email = $this->getEmail();
        $firstname = $this->getFirstname();
        $fullname = $this->getName();
        $phone = $address->getData('telephone');

        $adminEmail = Mage::getStoreConfig('trans_email/ident_general/email');

        $this->sendTransactionalEmail2($adminEmail, $fullname, 37, $fullname, $email, $phone);

        /*
         * End
         */

        return $this;
    }

    /*
     * Added by: Tran Trong Thang
     * Email: trantrongthang1207@gmail.com
     */

    public function sendTransactionalEmail2($recepientEmail, $recepientName, $templateId, $name, $email, $phone) {
        if ($recepientEmail == '') {
            return;
        }
        /*
         *  Set sender information			
         */
        $senderName = 'Magento';
        $senderEmail = $recepientEmail;
        $sender = array('name' => $senderName,
            'email' => $recepientEmail);
        /*
         *  Get Store ID		
         */
        $storeId = Mage::app()->getStore()->getId();

        /*
         * Set variables that can be used in email template
         * Khoi tao cac bien de su hien thi trong transactional mail
         * De su dung ta chi can goi {{username}}
         */
        $vars = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        );

        $translate = Mage::getSingleton('core/translate');

        /*
         *  Send Transactional Email
         */
        Mage::getModel('core/email_template')
                ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);

        $translate->setTranslateInline(true);
    }

    /*
     * End
     */

    /**
     * Send corresponding email template
     *
     * @param string $emailTemplate configuration path of email template
     * @param string $emailSender configuration path of email identity
     * @param array $templateParams
     * @param int|null $storeId
     * @return Mage_Customer_Model_Customer
     */
    protected function _sendEmailTemplate($template, $sender, $templateParams = array(), $storeId = null) {
        /** @var $mailer Mage_Core_Model_Email_Template_Mailer */
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($this->getEmail(), $this->getName());
        $mailer->addEmailInfo($emailInfo);

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig($sender, $storeId));
        $mailer->setStoreId($storeId);

        $mailer->setTemplateId(Mage::getStoreConfig($template, $storeId));
        $mailer->setTemplateParams($templateParams);
        $mailer->send();
        return $this;
    }

    /**
     * Send corresponding email template
     *
     * @param string $emailTemplate configuration path of email template
     * @param string $emailSender configuration path of email identity
     * @param array $templateParams
     * @param int|null $storeId
     * @return Mage_Customer_Model_Customer
     */
    protected function _sendEmailTemplate2($template, $sender, $templateParams = array(), $storeId = null) {
        /** @var $mailer Mage_Core_Model_Email_Template_Mailer */
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($this->getEmail(), $this->getName());
        $mailer->addEmailInfo($emailInfo);

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig($sender, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId(Mage::getStoreConfig($template, $storeId));
        $mailer->setTemplateParams($templateParams);
        $mailer->send();
        return $this;
    }

}
