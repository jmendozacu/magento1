<?php

/**
 * author: Ho Ngoc Hang<kemly.vn@gmail.com>
 * post data to Salesforce (run api with CURL)
 */
class VladimirPopov_WebformsPostToSalesforce_Model_Observer {

    public static function pushToSalesforce($arr_data, $oid) {
//file_put_contents(Mage::getBaseDir().'/webform.txt', print_r($customer_name, true));
        //set POST variables
        $url = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
        $fields = array(
            'first_name' => urlencode($arr_data['first_name']),
            'last_name' => urlencode($arr_data['last_name']),
            'email' => urlencode($arr_data['email']),
            'company' => urlencode($arr_data['company']),
            'phone' => urlencode($arr_data['phone']),
            '00N90000005wzos' => urlencode($arr_data['00N90000005wzos']),//comments
            '00N90000005x01j' => urlencode($arr_data['00N90000005x01j']),//newsletter
            '00N90000005wzNA' => urlencode($arr_data['00N90000005wzNA']),//product
            '00N90000005wg92' => urlencode($arr_data['00N90000005wg92']),//date require
            '00N90000005wzwJ' => urlencode($arr_data['file_url']),
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
        if (!Mage::getStoreConfig('webforms/proccessresult/enable'))
            return;
        $webform = $observer->getWebform();
        $arrayFormid = array(3, 5, 6, 8, 9, 10, 11, 12, 13, 14);
        if (!in_array($webform->getId(), $arrayFormid))
        //if ($webform->getId() != 5 && $webform->getId() != 3 && $webform->getId() != 6 && $webform->getId() != 8 && $webform->getId() != 9)
            return;
        $result = Mage::getModel('webforms/results')->load($observer->getResult()->getId());
        $result_data = $result->getData();

        if (isset($_POST['product_name']) && isset($_POST['product_link'])) {
            $product_name = $_POST['product_name'];
            $product_url = $_POST['product_link'];
        }
        if ($webform->getId() == 5) { // product enquiry
            if (isset($_POST['field']['26']) && $_POST['field']['26'] != '') {
                $date_require = date('d/m/Y', strtotime($_POST['field']['26']));
            } else {
                $date_require = '';
            }
            if($_POST['field']['54'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $customer_name = self::splitFullname($_POST['field']['20']);
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['21'],
                'company' => $_POST['field']['22'],
                'phone' => $_POST['field']['23'],
                '00N90000005wzos' => $result_data['field_24'],
                '00N90000005x01j' => $newsletter,
                '00N90000005wzNA' => $product_name . ' ' . $product_url,
                '00N90000005wg92' => $date_require,
            );
            
//            session_start();
//            if (isset($_POST['captcha_fgc_5'])) {
//                if ($_SESSION['captcha_fgc_5'] != $_POST['captcha_fgc_5']) {
//                   exit();
//                }
//            }
            $recepientEmail = $_POST['field']['21'];
            $recepientName = $_POST['field']['20'];
        } elseif ($webform->getId() == 3) { //customgear
            $customer_name = self::splitFullname($_POST['field']['12']);
            $field_file_id = 16;
            $file_name = $result_data['field_16'];
            if($file_name != ''){
                $file_link_path = $result->getDownloadLink($field_file_id,$file_name);
                $file_link = $file_link_path;
            }else{
                $file_link = '';
            }
            //$file_uploaded = "<a href='".$file_link."'>".$file_name."</a>";
            if($_POST['field']['19'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['14'],
                'phone' => $_POST['field']['13'],
                '00N90000005wzos' => $result_data['field_18'],
                '00N90000005x01j' => $newsletter,
                '00N90000005wzNA' => $product_name . ' ' . $product_url,
                'file_url' => $file_link,
            );
            $recepientEmail = $_POST['field']['14'];
            $recepientName = $_POST['field']['12'];
        } elseif ($webform->getId() == 6) {
            if($_POST['field']['53'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $customer_name = self::splitFullname($_POST['field']['29']);
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['30'],
                'company' => '',
                'phone' => $_POST['field']['32'],
                '00N90000005wzos' => $result_data['field_33'],
                '00N90000005x01j' => $newsletter,
            );
            
            $recepientEmail = $_POST['field']['30'];
            $recepientName = $_POST['field']['29'];
        } elseif ($webform->getId() == 8) {
            if($_POST['field']['55'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $customer_name = self::splitFullname($_POST['field']['43']);
            $service_require = "Service Required: " . $_POST['field']['45'];
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['44'],
                'company' => $service_require,
                'phone' => $_POST['field']['46'],
                '00N90000005wzos' => $result_data['field_49'],
                '00N90000005x01j' => $newsletter,
                '00N90000005wg92' => date('d/m/Y', strtotime($_POST['field']['48'])),
            );
            
            $recepientEmail = $_POST['field']['44'];
            $recepientName = $_POST['field']['43'];
        } elseif ($webform->getId() == 9) {
            if($_POST['field']['64'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $customer_name = self::splitFullname($_POST['field']['56']);
            $quantity = $_POST['field']['60'];
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['57'],
                'company' => '',
                'phone' => $_POST['field']['59'],
                '00N90000005wzos' => $result_data['field_62'], //message
                '00N90000005x01j' => $newsletter,
                '00N90000005wzNA' => $quantity . ', ' . $product_name . ' ' . $product_url,
            );
            
            $recepientEmail = $_POST['field']['57'];
            $recepientName = $_POST['field']['56'];
        } elseif ($webform->getId() == 10) {
            $customer_name = self::splitFullname($_POST['field']['66']);
            $field_file_id = 70;
            $file_name = $result_data['field_70'];
            $file_link_path = $result->getDownloadLink($field_file_id,$file_name);
            if($file_name != ''){
                $file_link = $file_link_path;
            }else{
                $file_link = '';
            }
            //$file_uploaded = "<a href='".$file_link."'>".$file_name."</a>";
            if($_POST['field']['73'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['68'],
                'company' => '',
                'phone' => $_POST['field']['67'],
                '00N90000005wzos' => $result_data['field_72'], //message
                '00N90000005x01j' => $newsletter,
                'file_url' => $file_link,
            );
            $recepientEmail = $_POST['field']['68'];
            $recepientName = $_POST['field']['66'];
        } elseif ($webform->getId() == 11) {
            $customer_name = self::splitFullname($_POST['field']['75']);
            $field_file_id = 79;
            $file_name = $result_data['field_79'];
            $file_link_path = $result->getDownloadLink($field_file_id,$file_name);
            if($file_name != ''){
                $file_link = $file_link_path;
            }else{
                $file_link = '';
            }
            //$file_uploaded = "<a href='".$file_link."'>".$file_name."</a>";
            if($_POST['field']['82'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['77'],
                'company' => '',
                'phone' => $_POST['field']['76'],
                '00N90000005wzos' => $result_data['field_81'], //message
                '00N90000005x01j' => $newsletter,
                'file_url' => $file_link,
            );
            $recepientEmail = $_POST['field']['77'];
            $recepientName = $_POST['field']['75'];
        } elseif ($webform->getId() == 12) {
            $customer_name = self::splitFullname($_POST['field']['84']);
            $field_file_id = 88;
            $file_name = $result_data['field_88'];
            $file_link_path = $result->getDownloadLink($field_file_id,$file_name);
            if($file_name != ''){
                $file_link = $file_link_path;
            }else{
                $file_link = '';
            }
            //$file_uploaded = "<a href='".$file_link."'>".$file_name."</a>";
            if($_POST['field']['91'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['86'],
                'company' => '',
                'phone' => $_POST['field']['85'],
                '00N90000005wzos' => $result_data['field_90'], //message
                '00N90000005x01j' => $newsletter,
                'file_url' => $file_link,
            );
            $recepientEmail = $_POST['field']['86'];
            $recepientName = $_POST['field']['84'];
        } elseif ($webform->getId() == 13) {
            $customer_name = self::splitFullname($_POST['field']['93']);
            $field_file_id = 97;
            $file_name = $result_data['field_97'];
            $file_link_path = $result->getDownloadLink($field_file_id,$file_name);
            if($file_name != ''){
                $file_link = $file_link_path;
            }else{
                $file_link = '';
            }
            //$file_uploaded = "<a href='".$file_link."'>".$file_name."</a>";
            if($_POST['field']['100'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['95'],
                'company' => '',
                'phone' => $_POST['field']['94'],
                '00N90000005wzos' => $result_data['field_99'], //message
                '00N90000005x01j' => $newsletter,
                'file_url' => $file_link,
            );
            $recepientEmail = $_POST['field']['95'];
            $recepientName = $_POST['field']['93'];
        } elseif ($webform->getId() == 14) {
            $customer_name = self::splitFullname($_POST['field']['102']);
            if($_POST['field']['109'] == '1'){
                $newsletter = 1;
            }  else {
                $newsletter = 0;
            }
            $arr_data = array(
                'first_name' => $customer_name[0],
                'last_name' => $customer_name[1],
                'email' => $_POST['field']['104'],
                'company' => '',
                'phone' => $_POST['field']['103'],
                '00N90000005wzos' => $result_data['field_108'], //message
                '00N90000005x01j' => $newsletter,
            );
            $recepientEmail = $_POST['field']['104'];
            $recepientName = $_POST['field']['102'];
        }
        $oid = Mage::getStoreConfig('webforms/proccessresult/oid');
        self::pushToSalesforce($arr_data, $oid);
        //post data to mailchimp
        if($newsletter == 1){
            $data = array(
                    "FNAME" => $customer_name[0],
                    "LNAME" => $customer_name[1],
                    "MMERGE3" => $customer_name[0].' '.$customer_name[1]
                );
            self::postToMailchimp($recepientEmail, $data);            
        }
        self::sendTransactionalEmail($recepientEmail, $recepientName, $webform->getEmailCustomerTemplateId());
    }

    public function sendTransactionalEmail($recepientEmail, $recepientName, $templateId) {
        if ($recepientEmail == '') {
            return;
        }
        // Set sender information			
        $senderName = 'Custom Gear';
        $senderEmail = 'enquiries@customgear.com.au';
        $sender = array('name' => $senderName,
            'email' => $senderEmail);
        // Get Store ID		
        $storeId = Mage::app()->getStore()->getId();

        // Set variables that can be used in email template
        $vars = array('customerName' => $recepientName);

        $translate = Mage::getSingleton('core/translate');

        // Send Transactional Email
        Mage::getModel('core/email_template')
                ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);

        $translate->setTranslateInline(true);
    }

    public static function splitFullname($fullname) {
        if($fullname == '')
            return array();
        $parts = explode(" ", $fullname);
        $lastname = array_pop($parts);
        $firstname = implode(" ", $parts);
        return array($firstname, $lastname);
    }
    
    public static function postToMailchimp($email, $data = array()) {
        if ($email == '')
            return;
        include_once('class.rest_api.php');

        $api_key = Mage::getStoreConfig('monkey/general/apikey');
        $list_id = Mage::getStoreConfig('monkey/general/list');
        $api = explode('-', $api_key);
        $config = array('dcAPI' => $api[1], 'keyAPI' => $api[0]);

        $restApi = new RestApi($config);
        $email_struct = new StdClass();
        $email_struct->email = $email;

        $args = array(
            'apikey' => $api_key,
            'id' => $list_id,
            'email' => $email_struct,
            'merge_vars' => $data,
            'double_optin' => false,
            'send_welcome' => true
        );
        $result = $restApi->runMethod($args, 'lists/subscribe');
        
    }
    
    public static function updateSubscriber($email, $first_name, $last_name){
        if(!$email)
            return;
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        $subscriber->setData('s_firstname',$first_name)->setData('s_lastname',$last_name);
        $subscriber->save();
    }

}

?>
