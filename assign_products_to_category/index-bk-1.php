<?php 
require_once 'app/Mage.php';
Mage::app();
$fileName = '24_RUSH_SKUS.csv';
$array = csv_to_array($fileName);

$productsIds = array();
for($i=0;$i<count($array);$i++){
    $product_sku = $array[$i]['sku'];
    $product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );
    $productsIds[] = $product_id;
}
//print_r($productsIds);exit();

// Array of category_ids to add.
$newCategories = array(990);
for($i=0;$i<count($productsIds);$i++){
    $id = $productsIds[$i];
    if($id > 0){
        $product = Mage::getModel('catalog/product')->load($id);
        $product->setCategoryIds(
            array_merge($product->getCategoryIds(), $newCategories)
        );
        $product->save();
        echo $id.' - success <br/>';
    }
}

function csv_to_array($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}
?>
