<?php

class TM_ProLabels_Block_Label extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('tm/prolabels/product/view/labels.phtml');
    }

    public function getContentLabels()
    {
        $result = array();
        if (Mage::helper('prolabels')->isMobileMode() && Mage::getStoreConfig("prolabels/general/mobile")) {
            return array();
        }
        $mode = 'product';
        $contentLabels = Mage::helper('prolabels')->getRegistryContentLabels($this->getProduct()->getId(), $mode);

        foreach ($contentLabels as $label) {
            if (Mage::getStoreConfig("prolabels/general/customer_group")) {
                $labelCustomerGroups = unserialize($label['customer_group']);
                $roleId = Mage::getSingleton('customer/session')->getCustomerGroupId();
                if ($labelCustomerGroups) {
                    if (!in_array($roleId, $labelCustomerGroups)) {
                        continue;
                    }
                }
            }
            if (array_key_exists('system_id', $label)) {
                if ($this->validateContentLabel($label)) {
                    if (Mage::helper('prolabels')->checkSystemLabelStore($label['system_id'], 'product')) {
                        $result[] = $label;
                    }
                }
            } else {
                if (Mage::helper('prolabels')->checkLabelStore($label['rules_id'], 'product')) {
                    $result[] = $label;
                }
            }
        }

        return $result;
    }

    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    public function getLabelText($label, $mode)
    {
        $product = $this->getProduct();
        $helper = Mage::helper('prolabels');

        return $helper->_getText($product, $mode, $label);
    }

    public function validateContentLabel($labelData)
    {
        $helper = Mage::helper('prolabels');
        if ('1' == $labelData['rules_id']) {
            return $helper->_isOnSale($this->getProduct(), 'product', $labelData);
        } else if ('2' == $labelData['rules_id']) {
            return $helper->_canShowQuantity($this->getProduct(), 'product', $labelData);
        } else if ('3' == $labelData['rules_id']) {
            return $helper->checkNewDate($this->getProduct());
        }
    }
}
