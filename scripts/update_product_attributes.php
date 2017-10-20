<?php
/**
 * update attribute product by category
 * author: kemly<kemly.vn@gmail.com>
 */
require_once('/var/www/html/royyoungchemist.com.au/application/site/app/Mage.php');
umask(0);
Mage::app();

$category_ids_medicine     = array(380, 431, 2396, 2421, 2411, 2422, 35, 48, 49, 2423, 2424, 2425, 2426, 2427, 2429, 2430, 2431, 2434, 2435, 2436, 2437, 2439, 59, 60, 61, 62, 63, 65, 66, 67, 70, 71, 72, 73, 75, 432, 433, 434, 435, 436, 438, 439, 440, 443, 444, 445, 447, 449);
$category_ids_prescription = array(430, 2448, 46);
//$category_ids_medicine = array(2436, 2439);
//store ids: YCS(3), RYC(2), P4L(4)

if (isset($_GET['type']))
{
    $type = $_GET['type'];
    if ($type == 'med')
    {
        $category_ids = $category_ids_medicine;
        $attribute    = 'medicine';
    }
    elseif ($type == 'pre')
    {
        $category_ids = $category_ids_prescription;
        $attribute    = 'prescription';
    }

    $collection = Mage::getModel('catalog/product')
        ->getCollection()
        ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
        ->addAttributeToSelect('*')
        ->addAttributeToFilter('category_id', array('in' => $category_ids));

    echo '<h1>' . $collection->count() . '</h1>';
    echo '# :: SKU<br/>';
    echo '----------------------------------------------------------------------<br/>';
    //exit();
    $i = 0;
    foreach ($collection as $_product)
    {
        $i++;
        //$_product->setData('barcode',$attribute);
        //if($i > 1284){
        $_product->setData($attribute, '1');
        if ($_product->save())
        {
            echo $i . ' :: ' . $_product->getSku() . ' :: updated <br/>';
        }
        else
        {
            echo $i . ' :: ' . $_product->getSku() . ' :: fail <br/>';
        }
        //exit();
        //}
    }
}
else
{
    echo 'Need specific type prescription or medicine!';
}

exit();
?>
