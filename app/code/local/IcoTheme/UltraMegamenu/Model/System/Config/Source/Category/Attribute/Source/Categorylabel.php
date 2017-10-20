<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_UltraMegamenu_Model_System_Config_Source_Category_Attribute_Source_CategoryLabel
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_options;

    /**
     * Get list of existing category labels
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options[] =
                array('value' => '', 'label' => " ");

            if ($tmp = trim(Mage::getStoreConfig('ultramegamenu/display_options/label1'))) {
                $this->_options[] =
                    array('value' => 'label1', 'label' => $tmp);
            }
            if ($tmp = trim(Mage::getStoreConfig('ultramegamenu/display_options/label2'))) {
                $this->_options[] =
                    array('value' => 'label2', 'label' => $tmp);
            }
        }
        return $this->_options;
    }
}
