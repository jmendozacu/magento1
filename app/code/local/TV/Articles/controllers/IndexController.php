<?php

class TV_Articles_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function listAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function NewAction() {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()):
            //Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
            $session = Mage::getSingleton('customer/session');
            $session->setBeforeAuthUrl(Mage::getUrl('articles/index/new'));
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
        else:
            $this->loadLayout();
            $this->renderLayout();
        endif;
    }

    public function ViewAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function EditAction() {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()):
            //Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
            $session = Mage::getSingleton('customer/session');
            $session->setBeforeAuthUrl(Mage::getUrl('articles/index/new'));
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
        else:
            $this->loadLayout();
            $this->renderLayout();
        endif;
    }

    public function UpdateAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('articles/articles')->load($data['id']);
            $model->setTitle($data['title']);
            $model->setShortDesc($data['short_desc']);
            $model->setLongDesc($data['long_desc']);
            $model->setStatus($data['status']);
            $model->setCreatedTime($data['created_time']);
            $model->setUpdateTime($data['update_time']);
            $model->save();
        }
        $this->_redirect('articles/index/list');
        $this->loadLayout();
        $this->renderLayout();
    }

    public function SaveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('articles/articles');
            $model->setTitle($data['title']);
            $model->setShortDesc($data['short_desc']);
            $model->setLongDesc($data['long_desc']);
            $model->setStatus($data['status']);
            $model->setCreatedTime($data['created_time']);
            $model->setUpdateTime($data['update_time']);
            $model->save();
        }
        $this->_redirect('articles/index/list');
        $this->loadLayout();
        $this->renderLayout();
    }

    public function DeleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $modle = Mage::getModel('articles/articles');
                $modle->load($id);
                $modle->delete();
                $this->_redirect('articles/index/list');
                $this->loadLayout();
                $this->renderLayout();
            } catch (Exception $ex) {
                $this->_redirect('articles/index/list');
                $this->loadLayout();
                $this->renderLayout();
            }
        }
    }

    public function SearchAction() {
        /*
         * check login
         *
          if (!Mage::getSingleton('customer/session')->isLoggedIn()):
          //Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
          $session = Mage::getSingleton('customer/session');
          $session->setBeforeAuthUrl(Mage::getUrl('articles/index/new'));
          Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
          endif;
         */
        $keysearch = $this->getRequest()->getParam('keysearch');

        /**
         * Get the resource model
         */
        $resource = Mage::getSingleton('core/resource');

        /**
         * Retrieve the read connection
         */
        $readConnection = $resource->getConnection('core_read');
        /*
         * Thuc hien mysql query o day
         */
        $query = 'SELECT * FROM ' . $resource->getTableName('articles') . ' WHERE title LIKE "%' . $keysearch . '%" OR short_desc LIKE "%' . $keysearch . '%" OR long_desc LIKE "%' . $keysearch . '%"';

        /**
         * Execute the query and store the results in $results
         */
        $results = $readConnection->fetchAll($query);

        /**
         * Print out the results
         */
        $arr = array(
            'base_url' => Mage::getBaseUrl(),
            'total' => count($results),
            'lists' => $results
        );
        /*
         * Ket qua tra ve la json
         */
        $this->getResponse()->setBody(json_encode($arr));
        $this->sendTemplateEmail('thang.testdev@gmail.com', 'Nham Phap', "Thu go, duoc gui toi tu site 'Mua Ban Tai Nang'");
        $this->sendTransactionalEmail('thang.testdev@gmail.com', 'Nham Phap', 1);
    }

    /*
     * Ham gui mail noi dung mail duoc chen vao templatemail
     */

    public function sendTemplateEmail($recepientEmail, $recepientName, $subject, $showmsg = false) {

        if ($recepientEmail == '') {
            return;
        }

        /*
         *  Set sender information			
         */
        $senderName = 'Nham Phap';
        $senderEmail = 'thang.testdev@gmail.com';
        $sender = array('name' => $senderName,
            'email' => $senderEmail);
        /*
         * get Store Id
         */
        $storeId = Mage::app()->getStore()->getId();
        /*
         * get inro customer
         */
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customer_id = $customer->getId();
        } else {
            $customer_id = 0;
        }

        $customerData = Mage::getModel('customer/customer')->load($customer_id);


        /*
         * Set variables that can be used in email template
         * Khoi tao cac bien de su hien thi trong file html template mail
         * De su dung ta chi can goi {{username}}
         */
        $emailTemplateVariables = array();
        $emailTemplateVariables['username'] = $customerData->getName();
        $emailTemplateVariables['email'] = $customerData->getEmail();
        $emailTemplateVariables['password'] = $customerData->getPasswordHash();
        $emailTemplateVariables['store_email'] = Mage::getStoreConfig('trans_email/ident_general/email');
        $emailTemplateVariables['store_phone'] = Mage::getStoreConfig('general/store_information/phone');
        $emailTemplateVariables['phone'] = '123456789';


        /*
         * Loads the html file named 'contact_form.html' from
         * app/locale/en_US/template/email/contact_form.html
         */
        $email = Mage::getModel('core/email_template')
                ->loadDefault('tv_articles_email_template');

        $processedTemplate = $email->getProcessedTemplate($emailTemplateVariables);

        $email->setReplyTo($senderEmail);
        $email->setSenderName($senderName);
        $email->setSenderEmail($senderEmail);
        $email->setTemplateSubject($subject);

        try {
            $result = $email->send(
                    $recepientEmail, $recepientName, array(
                'message' => $processedTemplate
                    )
            );
            if ($result) {
                $msg = "Email has sent to $receiver_email<br/>";
            } else {
                $msg = "Email hasn't sent to $receiver_email<br/>";
            }
            /*
             * echo $msg;
             * return $msg;
             * file_put_contents(Mage::getBaseDir('media').'/test.txt', $msg);
             */
        } catch (Exception $error) {
            $msg = "<b>" . $error->getMessage() . "<b><br/>";
            /* /
             * echo $msg;
             * file_put_contents(Mage::getBaseDir('media').'/test.txt', $msg);
             * return $msg;
             * 
             */
        }
        if ($showmsg) {
            echo $msg;
        }
    }

    /*
     * Ham gui email noi dung mail duoc chen vao transactional tu admin
     * $templateId la id cua transaction tu admin
     */

    public function sendTransactionalEmail($recepientEmail, $recepientName, $templateId) {
        if ($recepientEmail == '') {
            return;
        }
        /*
         *  Set sender information			
         */
        $senderName = 'Nham Phap';
        $senderEmail = 'thang.testdev@gmail.com';
        $sender = array('name' => $senderName,
            'email' => $senderEmail);
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
            'username' => $senderName,
            'email' => $recepientName,
            'password' => 'fgc123456',
            'store_email' => 'trantrongthang1207@gmail.com',
            'store_phone' => '123456789',
            'phone' => '123456789',
        );

        $translate = Mage::getSingleton('core/translate');

        /*
         *  Send Transactional Email
         */
        Mage::getModel('core/email_template')
                ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);

        $translate->setTranslateInline(true);
    }

    public function SendmailAction() {
        //$this->sendMail();
        $this->sendTemplateEmail('thang.testdev@gmail.com', 'Nham Phap', 'thang.testdev@gmail.com', true);
        //$this->sendTransactionalEmail('thang.testdev@gmail.com', 'Nham Phap', 2);
        echo "success";
        exit();
    }

    public function sendMail2Action() {
        $html = "Test send mail";
        $mail = Mage::getModel('core/email');
        $mail->setToName('Magenot');
        $mail->setToEmail('thang.fgc1207@gmail.com');
        $mail->setBody($html);
        $mail->setSubject('Test sen mail');
        $mail->setType('html'); // YOu can use Html or text as Mail format

        try {
            $mail->send();
            Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send.');
        }
    }

}
