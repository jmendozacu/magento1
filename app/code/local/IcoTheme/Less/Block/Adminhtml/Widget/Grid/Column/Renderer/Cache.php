<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   IcoTheme
 * @package    IcoTheme_Less
 * @copyright  Copyright (c) 2012 IcoTheme <magento@icotheme.com> (Benoît Leulliette <benoit@icotheme.com>)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class IcoTheme_Less_Block_Adminhtml_Widget_Grid_Column_Renderer_Cache
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected function _getValue(Varien_Object $row)
    {
        if (is_array($value = parent::_getValue($row))) {
            return $this->__('Existing');
        } else {
            return $this->__('None');
        }
    }
}