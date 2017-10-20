<?php
/**
 * update feature attribute of products
 * @Ho Ngoc Hang<kemly.vn@gmail.com>
 */
//exit();
    $re = "/\\B(\\n){1,}\\B|\\b(\\n){1,}\\b/m"; 
    $string = "dong 1!\r\n\r\n\r\ndong 2\r\n\r\ndong 3?\r\n\r\n\r\n\r\n\r\n\r\ndong 4\r\ndong 5"; 
     
   $patterns = array();
    $patterns[0] = "/\B(\r?\n){3,}\B|\b(\r?\n){3,}\b/m";
    $patterns[1] = "/\B(\r?\n){2}\B|\b(\r?\n){2}\b/m";

    $replacements = array();
    $replacements[0] = "+";//\r\n\r\n
    $replacements[1] = "-";

    $string = preg_replace_array($patterns, $replacements, $string);
    echo nl2br($string);
    exit();

require_once '../app/Mage.php';
Mage::app();

$number_each_row = 50;

$total_rows = 50;

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
    exit();
}

function updateFeature($number_each_row) {
    $page = $_GET['p'];
    $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect(array('entity_id', 'features'))
            ->addFieldToFilter('features', array('like' => '%\n%'))
            ->addFieldToFilter('entity_id', 7948);
    $collection->getSelect()->limit($number_each_row, $page);
//    echo $collection->getSelect() . '<br/>';
//    exit();

    $i = 0;
    $messageResult = '';
    if ($collection->getSize() > 0) {
        foreach ($collection as $product) {
            $i++;
            $pid = $product->getEntityId();
            $features = $product->getFeatures();
            //echo json_encode($features);exit();
            $replaced_feature = replaceLineBreakString($features);
//            echo nl2br($replaced_feature);
//            exit();
            if($replaced_feature != ''){
                try {
                    $product->setFeatures($replaced_feature);
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

function getTotalOrders() {
    $collection = Mage::getModel('sales/order')->getCollection();
    return $collection->getSize();
}

function replaceLineBreakString($string) {
    //$string ="Dong 1\r\n\r\n\r\n\r\ndong 2\r\n\r\ndong 3\r\n\r\n\r\ndong 5\r\ndong 5";
    $patterns = array();
    $patterns[0] = "/\B(\r?\n){3,}\B|\b(\r?\n){3,}\b/m";
    $patterns[1] = "/\B(\r?\n){2}\B|\b(\r?\n){2}\b/m";
//    $patterns[2] = "/\b(.\r?\n){3,}\b/m";
//    $patterns[3] = "/\b(.\r?\n){2}\b/m";
//    $patterns[4] = "/\b(\?\r?\n){3,}\b/m";
//    $patterns[5] = "/\b(\?\r?\n){2}\b/m";
//    $patterns[6] = "/\b(\!\r?\n){3,}\b/m";
//    $patterns[7] = "/\b(\!\r?\n){2}\b/m";

    $replacements = array();
    $replacements[0] = "<br/><br/>";//\r\n\r\n
    $replacements[1] = " ";
//    $replacements[2] = ".<br/><br/>";//\r\n\r\n
//    $replacements[3] = ". ";
//    $replacements[4] = "?<br/><br/>";//\r\n\r\n
//    $replacements[5] = "? ";
//    $replacements[6] = "!<br/><br/>";//\r\n\r\n
//    $replacements[7] = "! ";
    

    $string = preg_replace_array($patterns, $replacements, trim($string));
    return $string;
}

function preg_replace_array($pattern, $replacement, $subject, $limit=-1) {
    if (is_array($subject)) {
        foreach ($subject as &$value) $value=preg_replace_array($pattern, $replacement, $value, $limit);
        return $subject;
    } else {
        return preg_replace($pattern, $replacement, $subject, $limit);
    }
}  
?>
