<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
  Created on : Jul 4, 2015, 9:13:57 AM
  Author     : Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
 */

class TV_Ilearn_ProductController extends Mage_Core_Controller_Front_Action {

    function IndexAction() {
        $this->loadLayout(array('default'));
        $this->renderLayout();
    }

}
