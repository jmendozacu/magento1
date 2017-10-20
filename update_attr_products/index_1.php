<?php
/**
 * update feature attribute of products
 * @Ho Ngoc Hang<kemly.vn@gmail.com>
 */
//exit();

require_once '../app/Mage.php';
Mage::app();

$number_each_row = 1;

$total_rows = 1;

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
                                        alert('Updated success!')
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
    echo updateFeature($number_each_row);
    ////////////Use command line
//    $out = '';
//    $return_val = '';
//    $command = "php-cgi -f import_products.php index=" . $nextIndex . " timeRequest=" . time();
//    @exec($command, $out, $return_val);
    exit();
}

function updateFeature($number_each_row) {
    $page = $_GET['p'];
    $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect(array('entity_id', 'description'));
            //->addFieldToFilter('description', array('like' => '%\n\n%'));
            //->addFieldToFilter('entity_id', 7948);
    $collection->getSelect()->limit($number_each_row, $page);

//    echo $collection->getSelect() . '<br/>';
//    exit();

    $i = 0;
    $messageResult = '';
    if ($collection->getSize() > 0) {
        foreach ($collection as $product) {
            $i++;
            $pid = $product->getEntityId();
            $features = $product->getDescription();
            $replaced_feature = replaceLineBreakString($features);
            echo $features;
            exit();
            if($replaced_feature != ''){
                try {
                    $product->setDescription($replaced_feature);
                    $product->save();
                    $messageResult .= $i . '*** Updated product with ID - ' . $pid . ' -- Successful<br/>';
                } catch (Exception $exc) {
                    //echo $exc->getTraceAsString(). '<br/>';
                }
            }
        }
    } else {
        $messageResult = 'No any record to process';
    }
    $messageResult = "--------Page number: $page--------------<br/>" . $messageResult;
    return $messageResult;
}

?>
