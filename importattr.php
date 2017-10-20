<?php 
//process import value option
ini_set('max_execution_time', 600);
ini_set('memory_limit', '1024M');

require 'app/Mage.php';
$app = Mage::app('');
$attr_add_value = $_REQUEST['attr'];
Mage::app();
$arg_attribute = $attr_add_value;

$attr_model = Mage::getModel('catalog/resource_eav_attribute');
$attr = $attr_model->loadByCode('catalog_product', $arg_attribute);
$attr_id = $attr->getAttributeId();
//get data from CSV
$row = 1;
$colors = array();
$sizes = array();
if (($handle = fopen("source.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($row > 1) {
            //get color
            $temp = explode("|", $data[17]);
            $colors = array_merge($colors, $temp);
            //get size
            $temp_size = explode("|", $data[count($data) - 1]);
            $sizes = array_merge($sizes, $temp_size);
        }

        $row++;
    }
    fclose($handle);
}
//process colors
$res_color = array();
foreach ($colors as $value) {
    if ($value != '')
        $res_color[] = strtolower(trim($value));
}
$res_color = array_unique($res_color);
//process size
$res_sizes = array();
foreach ($sizes as $value) {
    if ($value != '')
        $res_sizes[] = trim($value);
}
$res_sizes = array_unique($res_sizes);

if ($attr_add_value == 'size') {
    $data = $res_sizes;
} elseif ($attr_add_value == 'colour') {
    $data = $res_color;
}

foreach ($data as $value) {
    $option['attribute_id'] = $attr_id;
    $option['value']['any_option_name'][0] = $value;

    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
    $setup->addAttributeOption($option);
}

echo 'ok';
exit();
//end