<?php

class TV_Recentproducts_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
	$this->loadLayout();
	$this->renderLayout();
    }

    public function ajaxAction() {
	$this->loadLayout();

	$block = $this->getLayout()->createBlock(
		'Mage_Core_Block_Template', 'my_block_name_here', array('template' => 'recentproducts/recentproducts.phtml')
	);

	echo $this->getLayout()->getBlock('content')->append($block)->toHtml();
	echo 'Hi, Thang';
	exit();
    }

}
