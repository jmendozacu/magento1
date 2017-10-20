<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please 
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_Search
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


class Plumrocket_Search_Block_System_Config_Reindex extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $url = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/process/list');

		return '<div style="padding:10px;background-color:#fff;border:1px solid #ddd;margin-bottom:7px;">'.
			$this->__('Please note: to apply changes immediately after you saved configuration, make sure to <a href="%s" target="_blank">reindex</a> Catalog Search Index.', $url)
        .'</div>';
    }		            
}