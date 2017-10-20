<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_WidgetBlocks_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getDate($product)
    {
        $date = $product->getData('special_to_date');
        if ($date) {
            $model = Mage::getSingleton('core/date');
            /* @var $model Mage_Core_Model_Date */
            $today = $model->date('Y-m-d H:i:s');
            if ($date > $today) {
                return date('F j, Y', strtotime($date));
            }
        }
    }

}
