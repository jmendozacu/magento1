<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '1024M');
require_once('/var/www/html/royyoungchemist.com.au/application/site/app/Mage.php');
umask(0);
Mage::app();

$product_id = 9651;
$product = Mage::getModel("catalog/product")->load($product_id);


 if ($product->getData('_edit_mode') == true) {

            $options = $product->getOptionInstance()->getOptions();
            if (count($options) > 0) {
echo "1:";
                foreach ($options as $key => $option) {
                    $options[$key]['is_delete'] = 1;
                }
                $product->getOptionInstance()->setOptions($options);
            }
        } else {
            $product->getOptionInstance()->unsetOptions();
            $options = $product->getOptions();
            if (count($options) > 0) {
echo "2:";
                foreach ($options as $option) {
                    $option->delete();
                }
            }
        }
