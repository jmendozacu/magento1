<?php

/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
class IcoTheme_UltraMegamenu_Model_Resource_Eav_Mysql4_Setup extends Mage_Eav_Model_Entity_Setup
{

    /**
     * Prepare catalog attribute values to save
     * From: Mage_Catalog_Model_Resource_Setup
     *
     * @param array $attr
     * @return array
     */
    protected function _prepareValues($attr)
    {
        $data = parent::_prepareValues($attr);

        return $data;
    }
}
