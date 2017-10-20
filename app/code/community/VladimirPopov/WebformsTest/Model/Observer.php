<?php

/**
 * author: Ho Ngoc Hang<kemly.vn@gmail.com>
 * post data to Salesforce (run api with CURL)
 */
class VladimirPopov_WebformsTest_Model_Observer {

    public static function pushToSalesforce($arr_data, $oid) {

        //set POST variables
        $url = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
        $fields = array(
            'first_name' => urlencode($arr_data['first_name']),
            'email' => urlencode($arr_data['email']),
            'company' => urlencode($arr_data['company']),
            'phone' => urlencode($arr_data['phone']),
            '00N90000005wzos' => urlencode($arr_data['00N90000005wzos']),
            '00N90000005x01j' => urlencode($arr_data['00N90000005x01j']),
            '00N90000005wzNA' => urlencode($arr_data['00N90000005wzNA']),
            '00N90000005wg92' => urlencode($arr_data['00N90000005wg92']),
            'oid' => $oid,
            'retURL' => urlencode('http://test.com'),
            'debug' => '1',
            'debugEmail' => urlencode("youremail@emailaddress.com.au"),
        );
        //url-ify the data for the POST
        $fields_string = '';
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        //open connection

        $ch = curl_init();
        //set the url, number of POST vars, POST data

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
    }

    public function postWebForm($observer) {
        file_put_contents('test_test.txt', 'chao ban');
        if (!Mage::getStoreConfig('webforms/proccessresult/enable'))
            return;
        $webform = $observer->getWebform();
        if ($webform->getId() != 5 && $webform->getId() != 3 && $webform->getId() != 6 && $webform->getId() != 8)
            return;
        $result = Mage::getModel('webforms/results')->load($observer->getResult()->getId());
        $vPath = str_replace(array($_SERVER['HTTP_HOST'], 'http:///'), array('', ''), $_SERVER['HTTP_REFERER']);
        $oRewrite = Mage::getModel('core/url_rewrite')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->loadByRequestPath($vPath);
        $product_id = $oRewrite->getProductId();
        $currentProduct = Mage::getModel('catalog/product')->load($product_id);
        $product_name = $currentProduct->getName();
        $product_url = $currentProduct->getProductUrl();
        if ($webform->getId() == 5) { // product enquiry
            $arr_data = array(
                'first_name' => $_POST['field']['20'],
                'email' => $_POST['field']['21'],
                'company' => $_POST['field']['22'],
                'phone' => $_POST['field']['23'],
                '00N90000005wzos' => $_POST['field']['24'],
                '00N90000005x01j' => $_POST['field']['25'],
                '00N90000005wzNA' => $product_name . ' ' . $product_url,
                '00N90000005wg92' => date('d/m/Y', strtotime($_POST['field']['26'])),
            );
//            session_start();
//            if (isset($_POST['captcha_fgc_5'])) {
//                if ($_SESSION['captcha_fgc_5'] != $_POST['captcha_fgc_5']) {
//                   exit();
//                }
//            }
            $recepientEmail = $_POST['field']['21'];
            $recepientName =  $_POST['field']['20'];       
        } elseif ($webform->getId() == 3) { //customgear
            $arr_data = array(
                'first_name' => $_POST['field']['12'],
                'email' => $_POST['field']['14'],
                'company' => '',
                'phone' => $_POST['field']['13'],
                '00N90000005wzos' => '',
                '00N90000005x01j' => $_POST['field']['19'],
                '00N90000005wzNA' => $product_name . ' ' . $product_url,
                '00N90000005wg92' => date('d/m/Y'),
            );
            $recepientEmail = $_POST['field']['14'];
            $recepientName =  $_POST['field']['12']; 
        } elseif ($webform->getId() == 6) {
            $arr_data = array(
                'first_name' => $_POST['field']['29'],
                'email' => $_POST['field']['30'],
                'company' => '',
                'phone' => $_POST['field']['32'],
                '00N90000005wzos' => $_POST['field']['33'],
                '00N90000005x01j' => '',
                '00N90000005wzNA' => $product_name . ' ' . $product_url,
                '00N90000005wg92' => date('d/m/Y', strtotime($_POST['field']['42'])),
            );
//            session_start();
//            if (isset($_POST['captcha_fgc_6'])) {
//                if ($_SESSION['captcha_fgc_6'] != $_POST['captcha_fgc_6']) {
//                   exit();
//                }
//            }
            $recepientEmail = $_POST['field']['30'];
            $recepientName =  $_POST['field']['29']; 
        } elseif ($webform->getId() == 8) {
            $arr_data = array(
                'first_name' => $_POST['field']['43'],
                'email' => $_POST['field']['44'],
                'company' => '',
                'phone' => $_POST['field']['46'],
                '00N90000005wzos' => $_POST['field']['49'],
                '00N90000005x01j' => '',
                '00N90000005wzNA' => $product_name . ' ' . $product_url,
                '00N90000005wg92' => date('d/m/Y'),
            );
//            session_start();
//            if (isset($_POST['captcha_fgc_8'])) {
//                if ($_SESSION['captcha_fgc_8'] != $_POST['captcha_fgc_8']) {
//                   exit();
//                }
//            }
            $recepientEmail = $_POST['field']['44'];
            $recepientName =  $_POST['field']['43']; 
        }
        $oid = Mage::getStoreConfig('webforms/proccessresult/oid');
        self::pushToSalesforce($arr_data, $oid);
        self::sendTransactionalEmail($recepientEmail, $recepientName);
    }

    public function sendTransactionalEmail($recepientEmail, $recepientName) {
        if($recepientEmail == ''){
            return;
        }
        // Transactional Email Template's ID
        $templateId = 1;

        // Set sender information			
        $senderName = 'Custom Gear';
        $senderEmail = 'enquiries@customgear.com.au';
        $sender = array('name' => $senderName,
            'email' => $senderEmail);
        // Get Store ID		
        $store = Mage::app()->getStore()->getId();

        // Set variables that can be used in email template
        $vars = array('customerName' => $recepientName);

        $translate = Mage::getSingleton('core/translate');

        // Send Transactional Email
        Mage::getModel('core/email_template')
                ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);

        $translate->setTranslateInline(true);
    }

}

?>
