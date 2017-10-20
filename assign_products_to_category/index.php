<?php
/**
 * download image from CSV file
 * @Ho Ngoc Hang<kemly.vn@gmail.com>
 */
//exit();
require_once '../app/Mage.php';
Mage::app();

$number_each_row = 50;

$total_rows = 669;

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
    $page = $_GET['p'];
    $fileName = '24_RUSH_SKUS.csv';
    $array = csv_to_array($fileName);
    $index = ($page * $recordsPerPage) - 1;
    $recordsToBeDisplayed = array_slice($array, $page, $recordsPerPage);
    $newCategories = array(1053);
    //$total_pages = ceil(700 / $recordsPerPage);
    $messageResult = '';
    //print_r($recordsToBeDisplayed);exit();
    for ($j = 0; $j <= count($recordsToBeDisplayed); $j++) {
        $product_sku = $recordsToBeDisplayed[$j]['sku'];
        if ($product_sku != '') {
            $product_id = Mage::getModel("catalog/product")->getIdBySku($product_sku);
            if ($product_id) {
                $product = Mage::getModel('catalog/product')->load($product_id);
                $product->setCategoryIds(
                        array_merge($product->getCategoryIds(), $newCategories)
                );
                $product->save();
                $messageResult .= $product_sku . ' - success <br/>';
            } else {
                $messageResult .= $product_sku . ' - fail <br/>';
            }
        }
    }
    $messageResult = "--------Page number: $page--------------<br/>" . $messageResult;
    return $messageResult;
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
