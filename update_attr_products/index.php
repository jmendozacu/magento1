<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set("auto_detect_line_endings", true);

/**
 * import products from CSV
 * author: @Ho Ngoc Hang<kemly.vn@gmail.com>
 * */
//exit();
$valueRequest = !isset($_REQUEST['index']) ? 0 : $_REQUEST['index'];
if ($valueRequest == 0) {
    require_once 'import_product.html.php';
}
define('MAGENTO', dirname(dirname(__FILE__)));
require_once MAGENTO . '/app/Mage.php';
require_once 'request.class.php';
require_once 'config.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$filename = FILE_IMPORT_PRODUCTS;
if (!file_exists($filename) || !is_readable($filename)) {
    file_put_contents('log_product.txt', "File import is not exist or can not be readed.");
}

$attrSetName = 'upss';
$attributeSetId = Mage::getModel('eav/entity_attribute_set')
        ->load($attrSetName, 'attribute_set_name')
        ->getAttributeSetId();
if ($attributeSetId != '' && $attributeSetId > 0) {
    $att_set_id = $attributeSetId;
} else {
    $att_set_id = 9;
}

$request = new FgcRequest();

if ($valueRequest == 0) {
    if (file_exists('log_product.txt')) {
        unlink('log_product.txt');
    }
}

$arrRecod = CsvToArray($filename, '', $valueRequest, NUMBER_RECORD_POR_REQUEST);
//file_put_contents('log_product.txt', "ns:" . $valueRequest . "\n", FILE_APPEND);
$nextIndex = $valueRequest + 1;
if (count($arrRecod) > 0) {//&& $arr[0] != "No Product"
    foreach ($arrRecod as $arr) {
        $cate_name = getCateName($arr['Category']);
        if ($cate_name) {
            $_category = Mage::getModel('catalog/category')->loadByAttribute('name', $cate_name);
            if (is_object($_category)) {
                $arr['cate_id'] = $_category->getId();
                $msg = importProducts($arr, $att_set_id);
            } else {
                $msg = "Product SKU: {$arr['IM SKU']}: $cate_name not found category.";
            }
        } else {
            $msg = "Product SKU: {$arr['IM SKU']}: category name is null.";
        }

        if ($msg) {
            file_put_contents('log_product.txt', $msg . "\n", FILE_APPEND);
        }
    }
/////////////Use request browser
    $link = BASE_URL_SITE . '/fgc_import/import_products.php?index=' . $nextIndex . "&timeRequest=" . time();
    $request->request($link);
////////////Use command line
//    $out = '';
//    $return_val = '';
//    //$command = "php-cgi -f test1_1.php action=yes";
//    $command = "php-cgi -f import_products.php index=" . $nextIndex . " timeRequest=" . time();
//    @exec($command, $out, $return_val);
} elseif (empty($arrRecod)) {
    file_put_contents('log_product.txt', 'no product', FILE_APPEND);
}
//elseif ($arr[0] == 'No Product') {
//   
//}
//exit();

/**
 * convert CSV to array
 * @param type $filename
 * @param type $delimiter
 * @return boolean
 */
function CsvToArray($filename = '', $delimiter = ',', &$index, $number_record = NUMBER_RECORD_POR_REQUEST) {

    if (!file_exists($filename) || !is_readable($filename))
        return array();

    $header = NULL;
    $dataRecod = array();
    $index_file = 0;
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle)) !== FALSE) {
            $data = array();
            if ($index_file == 0) {
                $header = $row;
                $index_file++;
            } else if ($index_file > 0) {
                if ($index_file >= $index) {

                    if ($number_record > 0) {
                        $index = $index_file;
                        $data = array_combine($header, $row);
                        $product = Mage::getModel('catalog/product');
                        $productId = $product->getIdBySku($data['IM SKU']);

                        if (($productId && $data['Stock Available'] == 'N') || $data['Stock Available'] == 'Y') {
                            //file_put_contents('log1.txt', "n1:" . $productId . "\n", FILE_APPEND);
                            $dataRecod[] = $data;
                            $number_record = $number_record - 1;
                        }
                        $index_file++;
                    } else {
                        break;
                    }
                } else {
                    $index_file++;
                }
            }
        }
        fclose($handle);
    }
    // file_put_contents('log_product.txt', json_encode($dataRecod) . '|' . $index . "|$index_file" . "\n", FILE_APPEND);
    return $dataRecod;
}

/**
 * import products
 * @param type $importData
 * @param type $att_set_id
 */
function importProducts($importData, $att_set_id) {
    if (isset($importData['Manufacturer Name']) && $importData['Manufacturer Name'] != "") {
        $manufacturer_id = addAttributeValue('manufacturer', $importData['Manufacturer Name']);
    }

    $product = Mage::getModel('catalog/product');

    $productId = $product->getIdBySku($importData['IM SKU']);
    if ($productId) {
        $product = $product->reset();
        if ($importData['Stock Available'] == 'N') {
            $product->load($productId);
            $product->setStockData(array(
                'use_config_manage_stock' => 1, //'Use config settings' checkbox
                'manage_stock' => 1, //manage stock
                'is_in_stock' => 0, //Stock Availability
                'qty' => 0 //qty
                    )
            );
            $product->setStatus(2);
        } elseif ($importData['Stock Available'] == 'Y') {
            $product->load($productId);
            $product->setStockData(array(
                'use_config_manage_stock' => 1, //'Use config settings' checkbox
                'manage_stock' => 1, //manage stock
                'is_in_stock' => 1, //Stock Availability
                'qty' => QUANTY_IN_STOCK //qty
                    )
            );
            $product->setStatus(1);
        }
        $msg = "Updated SKU " . $importData['IM SKU'];
    } else {
        //$product = Mage::getModel('catalog/product');
        $product->setStatus(1);
        $msg = "Added SKU " . $importData['IM SKU'];
    }

    $product->setSku($importData['IM SKU']); // set SKU
    $product->setName($importData['Short Description']); // name product
    $product->setDescription(""); // description
    $product->setShortDescription($importData['Long Description']); // short desc
    $product->setPrice($importData['Price']); // price
    $product->setTypeId('simple');
    $product->setAttributeSetId($att_set_id); // need to look this up
    $product->setCategoryIds($importData['cate_id']); // assign to category
    $product->setTaxClassId(2); // taxable goods
    $product->setVisibility(4); // catalog, search
    //$product->setStatus(1); // enabled

    $product->setMsrp($importData['Retail Price']); // set Manufacturer's Suggested Retail Price
    $product->setHeight($importData['Height']);
    $product->setWidth($importData['Width']);
    $product->setWeight($importData['Weight']);
    $product->setLength($importData['Length']);
    $product->setManufacturer($manufacturer_id);
    $product->setImCode($importData['Manufacturer IM code']);
    $product->setPartNumber($importData['Manufacturer Part Number']);

// assign product to the default website
    $product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));

    try {
        //import internal and external image product
        $ext = pathinfo($importData['Image Path'], PATHINFO_EXTENSION);
        if (isset($importData['Image Path']) && $importData['Image Path'] != "" && ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png')) {
            
        } elseif ($importData['Image Path'] != "") {
            $content = @file_get_contents($importData['Image Path']);
            if ($content) {
                $pathTempImage = dirname(__FILE__) . '/tmp';
                if (!file_exists($pathTempImage)) {
                    mkdir($pathTempImage, '0777');
                }
                $name = time() . '.jpg';
                $file = $pathTempImage . '/' . $name;
                file_put_contents($file, $content);
                if (file_exists($file)) {
                    $importData['Image Path'] = BASE_URL_SITE . '/fgc_import/tmp/' . $name;
                } else {
                    $importData['Image Path'] = '';
                }
            } else {
                $importData['Image Path'] = '';
            }
        } else {
            $importData['Image Path'] = '';
        }
        
        if ($importData['Image Path'] != '') {
            //file_put_contents('log1.txt', "n1:" . $file . "\n", FILE_APPEND);
            importImages($importData, $product);
            if (file_exists($file)) {
                @unlink($file);
            }
        }
        //END import images
        $product->save();
        $msg = "I|" . $msg . " Successful ";

        return $msg;
    } catch (Exception $e) {
        $msg .= " Fail ";
        echo $e->getMessage();
        return $msg;
    }
}

?>