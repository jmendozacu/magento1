<?php

/*
  Created on : Jul 28, 2015, 3:38:10 PM
  Author     : Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
 */

class TV_Recentproducts_Block_Recentproducts extends Mage_Core_Block_Template {

    public function getRecentProducts() {
        // call model to fetch data
        $arr_products = array();
        $products = Mage::getModel("recentproducts/recentproducts")->getRecentProducts();

        foreach ($products as $product) {
            $arr_products[] = array(
                'id' => $product -> getId(),
                'name' => $product->getName(),
                'url' => $product->getProductUrl(),
            );
        }

        return $arr_products;
    }

}
