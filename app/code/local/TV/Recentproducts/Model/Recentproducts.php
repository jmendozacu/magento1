<?php

/*
  Created on : Jul 28, 2015, 3:38:10 PM
  Author     : Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
 */

class TV_Recentproducts_Model_Recentproducts extends Mage_Core_Model_Abstract {

    public function getRecentProducts() {
        $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->setOrder('entity_id', 'DESC')
                ->setPageSize($this->get_cur_page());
        return $products;
    }

}
