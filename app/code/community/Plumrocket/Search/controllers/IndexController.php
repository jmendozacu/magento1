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

 
class Plumrocket_Search_IndexController extends Mage_Core_Controller_Front_Action
{

	public function indexAction()
	{
		$helper = $this->_getHelper();
		$config = $this->_getHelper('psearch/config');
		
		if(!$helper->moduleEnabled()) {
			return;
		}

		$queryText = $helper->getQueryText();
		
		$data = array(
			'success'	=> false,
			'content'	=> null,
		);

		try{
			$query = Mage::helper('catalogsearch')->getQuery();

			if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            }
            else {
                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity()+1);
                }
                else {
                    $query->setPopularity(1);
                }

                if ($query->getRedirect()){
                    $query->save();
                }else {
                    $query->prepare();
                }
            }

			if(Mage::helper('core/string')->strlen($queryText) >= $config->getSearchMinLenght()) {
				$this->loadLayout();
				$data['content'] = $this->getLayout()
					->getBlock('psearch.tooltip')
					->toHtml();

				$data['success'] = true;
			}
		} catch(Exception $e) {}

		$this->getResponse()->setBody(json_encode($data));
	}

	protected function _getHelper($path = 'psearch')
    {
        return Mage::helper($path);
    }

}