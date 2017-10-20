<?php
/**
 *
 * ------------------------------------------------------------------------------
 * @category     IcoTheme
 * @package      IcoTheme_Themes
 * ------------------------------------------------------------------------------
 * @copyright    Copyright (C) 2014 IcoTheme.com. All Rights Reserved.
 * @license      GNU General Public License version 2 or later;
 * @author       IcoTheme.com
 * @email        support@icotheme.com
 * ------------------------------------------------------------------------------
 *
 */
?>
<?php

class IcoTheme_UltraMegamenu_Model_System_Config_Source_Category_Attribute_Source_Block_Display
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = array(
                array('value' => '1', 'label' => 'Yes'),
                array('value' => '0', 'label' => 'No')
            );
        }
        return $this->_options;
    }
}
