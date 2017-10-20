<?php
class VladimirPopov_WebFormsCRF_AccountController
    extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        if(!Mage::getSingleton('customer/session')->getCustomerId()){
            Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::helper('core/url')->getCurrentUrl());
            $login_url = Mage::helper('customer')->getLoginUrl();
            $status = 301;

            if (Mage::getStoreConfig('webforms/general/login_redirect')) {
                $login_url = $this->getUrl(Mage::getStoreConfig('webforms/general/login_redirect'));

                if (strstr(Mage::getStoreConfig('webforms/general/login_redirect'), '://'))
                    $login_url = Mage::getStoreConfig('webforms/general/login_redirect');
            }
            Mage::app()->getFrontController()->getResponse()->setRedirect($login_url, $status);
            return;
        }

        $this->loadLayout();

        $webform = Mage::getModel('webforms/webforms')->setStoreId(Mage::app()->getStore()->getId())->load($this->getRequest()->getParam('id'));

        $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();

        if ($webform->getData('crf_account') && $webform->getData('crf_account_frontend') && in_array($groupId, $webform->getData('crf_account_group'))) {
            $this->getLayout()->getBlock('customer_account_navigation')->setActive('webformscrf/account/index/id/' . $webform->getId());
            $this->getLayout()->getBlock('webformscrf.account')->setData('webform_id', $webform->getId());
        }

        $this->renderLayout();
    }
}