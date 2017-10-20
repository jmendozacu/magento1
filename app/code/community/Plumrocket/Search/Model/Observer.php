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


class Plumrocket_Search_Model_Observer
{

    public function adminSystemConfigSectionSaveAfter()
    {
        $helper = Mage::helper('psearch');
        if(!$helper->moduleEnabled()) {
            return;
        }

        $request = Mage::app()->getRequest();

        if($change = $request->getParam('psearch-attributes-change')) {
        	parse_str($change, $change);
        	if(!empty($change['on']) && is_array($change['on'])) {
                foreach (array_values($change['on']) as $n => $id) {
        			$helper->setAttributeSearchable($id, $n + 1);
        		}
        	}
        	if(!empty($change['off']) && is_array($change['off'])) {
                $helper->setAttributeSearchable($change['off'], 0);
        	}
        }
    }

}