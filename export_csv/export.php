<?php
/**
 * @author: Ho Ngoc Hang<kemly.vn@gmail.com>
 * export CSV file (list products) from site customgear
 *  
 */
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=products_import.csv");
header("Pragma: no-cache");
header("Expires: 0");

$hostname = 'localhost';
$user = 'dev';
$pass = 'dev123456';
$db = 'dev_proj02';

$conn = mysql_connect($hostname, $user, $pass) or die('cannot connect database');
mysql_select_db($db, $conn) or die('cannot select database');
include_once 'HelperGetNewAttribute.php';

$fp = fopen('php://output', 'w');

$delimiter = ',';
$headers = array('pid','store','websites','attribute_set','type','sku','has_options','manufacturer','status','visibility','tax_class_id','name','image','small_image','thumbnail','gallery','weight','description','short_description','qty','is_in_stock','store_id','product_type_id','categories','price','features','branding_options','colour','dimensions','print_area');
fputcsv($fp, $headers, $delimiter);


$strSQL = "SELECT * FROM tmpproducts ORDER BY ProductID ASC";
$objQuery = mysql_query($strSQL) or die();
$Num_Rows = mysql_num_rows($objQuery);
$arr_data = array();

while ($objResult = mysql_fetch_array($objQuery)) {
    $ProductCode = $objResult["ProductCode"];
    $ProductName = $objResult["ProductName"];
    $Weight = $objResult["Weight"];
    $ProductImage = 'https://customgear.com.au/Admin/ProductImages/'.$objResult["ProductImage"];
    $ProductMainCategoryName = $objResult["ProductMainCategoryName"];
    $MutipleCategory = $objResult["MutipleCategory"];
    $ShortDescription = $objResult["ShortDescription"];
    $sub_cates = str_replace(' | ', '/', $MutipleCategory);
    $full_path_cate = $ProductMainCategoryName;
    if($sub_cates != ''){
        if(substr($sub_cates, 0, 3) == ' | '){
            $full_path_cate .= $sub_cates;
        }else{
            $full_path_cate .= '/'.$sub_cates;
        }        
    }
    $FullDescription = $objResult["Description"];
    $attributes = GetNewAttribute::renderAttribute($FullDescription);
    $Features = @$attributes['Features'];
    $Dimensions = @$attributes['Dimensions'];
    $Print_Area = @$attributes['Print Area'];
    $Branding_Options = @$attributes['Branding Options'];
    $Available_Colors = @$attributes['Available Colors'];
    $LongDescription = @$attributes['Description'];
 
    $arr_data = array($objResult["ProductID"],'english','base','Default','simple',$ProductCode,'1','','Enabled','Catalog, Search','Taxable Goods',$ProductName,$ProductImage,$ProductImage,$ProductImage,$ProductImage,$Weight,$LongDescription,$ShortDescription,'1','1','1','simple',$full_path_cate,'0',$Features,$Branding_Options,$Available_Colors,$Dimensions,$Print_Area);
    
    fputcsv($fp, $arr_data, $delimiter);
}
fclose($fp);

?>
