<?php

/**
 * update feature attribute of products (NOTE: attribute code is same as column name)
 * @Ho Ngoc Hang<kemly.vn@gmail.com>
 */
require_once '../app/Mage.php';
Mage::app();

$number_each_row = 20;

$total_rows = 120;
global $attr_code;
$attr_code = 'size';
if (!isset($_GET['p'])) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
            <script type="text/javascript">
                var urlajax = 'index.php?p=0';
                // var countrecord = [0, 0];
                var numajax = Number(<?php echo $total_rows; ?>);
                var start = 0;
                var ajaxalway = {
                    goes: function (urlajax) {
                        if (window.timeout)
                            clearTimeout(window.timeout);
                        (function ($) {
                            $.ajax({
                                url: urlajax,
                                type: "POST",
                                dataType: 'html',
                                success: function (response) {
                                    //  if (response.text != 'undefined') {
                                    $("#responseajax").append("<div>" + response + "</div>");
                                    // }
                                    if (parseInt(start) < parseInt(numajax)) {
                                        window.timeout = window.setTimeout(function () {
                                            urlajax = 'index.php?p=' + start;
                                            ajaxalway.goes(urlajax);
                                        }, 2000);

                                        start = parseInt(start) + parseInt(<?php echo $number_each_row ?>);

                                    } else {
                                        alert('Import success!')
                                        $("#fgcloading").hide();
                                    }
                                }
                            });
                        })(jQuery);
                    }
                }
                ajaxalway.goes(urlajax);
            </script>
            <style type="text/css">
                #fgcloading{
                    display: block;
                    position: fixed;
                    width: 100%;
                    top: 100px;
                    left: 45%;
                }

                .productid {
                    font-weight: bold;
                }
                .fgcchild {
                    margin-left: 40px;
                }
                .imageerror{
                    color: red;
                }
                .noimage{
                    color: #FEA110;
                }
                .existsimage{
                    color: #0B4CB1;
                }
                .imagename{
                    color: #4CB10B;
                }
            </style>
        </head>
        <body>
            <div id="fgcloading">
                <img src="js/spinner.gif"/>
            </div>
            <div id="responseajax"></div>
        </body>
    </html>
    <?php
} else {
    if (!isset($_SESSION)) {
        session_start();
    }
    echo assignProduct($number_each_row);
    exit();
}

function assignProduct($recordsPerPage) {
    global $attr_code;
    $page = $_GET['p'];
    $fileName = 'Elevate_Product_Data_03Aug2016-options.csv';
    $array = csv_to_array($fileName);
    $index = ($page * $recordsPerPage) - 1;
    $recordsToBeDisplayed = array_slice($array, $page, $recordsPerPage);
    $messageResult = '';
    for ($j = 0; $j <= count($recordsToBeDisplayed); $j++) {
        $options = explode(' , ', $recordsToBeDisplayed[$j][$attr_code]);
        foreach($options as $attributeValue){
            if($attributeValue != ''){
                $attributeValue = trim($attributeValue);
                $attributeValue = str_replace(array('ñ', '.'), array('-', ''), $attributeValue);
                $attributeValue = preg_replace('/' . preg_quote('(') . '.*?' .preg_quote(')') . '/', '', $attributeValue);
                $attributeValue = trim($attributeValue);
                if(addAttributeValue($attr_code, $attributeValue) == true){
                    $messageResult .= '<p style="color:green">'.$attributeValue . ' ***** Success </p>';
                }else{
                    $messageResult .= '<p style="color:red">'.$attributeValue . ' ***** Exists </p>';
                }
            }
        }
        
    }
    $messageResult = "--------Page number: $page--------------<br/>" . $messageResult;
    return $messageResult;
}

//$attr_model = Mage::getModel('catalog/resource_eav_attribute');
//$attr = $attr_model->loadByCode('catalog_product', $arg_attribute);
//$attr_id = $attr->getAttributeId();
//
//$option['attribute_id'] = $attr_id;
//$option['value']['colorfgc'][0] = $arg_value;
//
//$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
//$setup->addAttributeOption($option);

function attributeValueExists($arg_attribute, $arg_value) {
    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');
    $attribute = Mage::getModel('catalog/resource_eav_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $arg_attribute);
    $attribute_table = $attribute_options_model->setAttribute($attribute);
    $options = $attribute_options_model->getAllOptions(false);
    foreach ($options as $option) {
        if ($option['label'] == $arg_value) {
            return $option['value'];
        }
    }
    return false;
}

function addAttributeValue($attributeCode, $attributeValue, $i = 0) {
    // Get Attribute by attribute code
    $attribute = Mage::getModel('catalog/resource_eav_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
    // Check if a value exists in Attribute
    if (!attributeValueExists($attributeCode, $attributeValue)) {
        // Add Valut to option array
        $value['option'] = array(
            $attributeValue
        );
        // Set order for the option
        $order['option'] = $i;
        // Assign values to result array
        $result = array(
            'value' => $value,
            'order' => $order
        );
        // Set attribute data
        $attribute->setData('option', $result);
        // Save attribute
        if($attribute->save()){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function csv_to_array($filename = '', $delimiter = ',') {
    if (!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 700, $delimiter)) !== FALSE) {
            if (!$header)
                $header = $row;
            else
                $data[] = @array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

?>
