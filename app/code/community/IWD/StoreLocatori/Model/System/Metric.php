<?php

class IWD_StoreLocatori_Model_System_Metric
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('storelocatori')->__('Km')),
            array('value' => 2, 'label'=>Mage::helper('storelocatori')->__('Miles')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            1 => Mage::helper('storelocatori')->__('Km'),
            2 => Mage::helper('storelocatori')->__('Miles'),
        );
    }

}
