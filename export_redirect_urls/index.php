<?php
/**
 * create file include redirect urls product
 * @author Ho Ngoc Hang<kemly.vn@gmail.com>
 */
require_once '../app/Mage.php';
Mage::app();
$num = $_GET['n'];
$fileName = 'OLD_SITE_SKU_URLS_20150529_'.$num.'.csv';
$array = csv_to_array($fileName);
$content = '';
for($i=0;$i<count($array);$i++){
    $row = $array[$i];
    $sku = $row['SKU'];
    $old_url = $row['URL'];   
    $product_id = Mage::getModel("catalog/product")->getIdBySku($sku);
    if ($product_id) {
        $product = Mage::getModel('catalog/product')->load($product_id);
        $new_url = $product->getUrlPath();
        $content .= 'RewriteRule ^'.$old_url.' /'.$new_url.' [R=301,L]'. "\n";        
    }else{
        //echo $sku.'<br/>';
    }
}
//echo Mage::getBaseDir().'/export_redirect_urls/redirect_urls.txt';exit();
file_put_contents(Mage::getBaseDir().'/export_redirect_urls/redirect_urls.txt', $content, FILE_APPEND);
exit();

function csv_to_array($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 12000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = @array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}
?>