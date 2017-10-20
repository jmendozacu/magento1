<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_IcoTheme_Model_System_Layout_Compiler
{
    /**
     * Changes condition in XML file to string or
     * to boolean if needed
     *
     * @param $condition
     * @return mixed
     */
    public function getXmlCondition($condition)
    {
        $condition = (string)$condition;
        switch ($condition) {
            case '0':
            case 'false':
            case 'FALSE':
                $condition = false;
                break;

            case '':
            case '1':
            case 'true':
            case 'TRUE':
                $condition = true;
                break;
        }
        return $condition;
    }

    /**
     * Changes condition in XML file to string or
     * to boolean if needed
     *
     * @param $condition
     * @return mixed
     */
    public function getAdminConfig($configPath)
    {
        $config = (string)Mage::getStoreConfig($configPath);
        switch ($config) {
            case '':
            case '0':
            case 'false':
            case 'FALSE':
                $config = false;
                break;

            case '1':
            case 'true':
            case 'TRUE':
                $config = true;
                break;
        }
        return $config;
    }

    /**
     * Removes all spaces from a string
     *
     * @param    string $string
     * @return    string
     */
    public function spaceRemover($string)
    {
        $string = preg_replace('/ +/', ' ', (string)$string);
        return trim($string);
    }

}
