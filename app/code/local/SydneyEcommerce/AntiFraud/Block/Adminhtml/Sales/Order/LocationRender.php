<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocationRender
 *
 * @author Hoang Bien <hoangbien264@gmail.com>
 */
class SydneyEcommerce_AntiFraud_Block_Adminhtml_Sales_Order_LocationRender extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $order = Mage::getModel('sales/order')->load($row->getId());
        if (is_object($order)) {
            $ip = $order->getRemoteIp();
            $helper = Mage::helper('antifraud');
            return $helper->getAddressCustomerByIP($ip);
        } else {
            return '';
        }
    }

}
