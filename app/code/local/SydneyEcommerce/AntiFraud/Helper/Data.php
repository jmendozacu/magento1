<?php

class SydneyEcommerce_AntiFraud_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Get City, Country by IP
     * @param type $ip
     * @return string city and country
     */
    public function getAddressCustomerByIP($ip) {
        if (!$ip)
            return '';
        /* Get user ip address details with geoplugin.net */
        $geopluginURL = 'http://www.geoplugin.net/php.gp?ip=' . $ip;
        $content = file_get_contents($geopluginURL);
        if ($content != '') {
            $addrDetailsArr = unserialize($content);
        }
        /* Get City name by return array */
        $city = isset($addrDetailsArr['geoplugin_city']) ? $addrDetailsArr['geoplugin_city'] : '';

        /* Get Country name by return array */
        $country = isset($addrDetailsArr['geoplugin_countryName']) ? $addrDetailsArr['geoplugin_countryName'] : '';

        if ($city && $country) {
            return $city . ", " . $country;
        } elseif ($city) {
            return $city;
        } elseif ($country) {
            return $country;
        } else {
            return '';
        }
    }

    public function getShippingAddressByOrder($order) {
        if (!$order)
            return '';
        $shippingAddress = $order->getShippingAddress();
        if (!$shippingAddress)
            return '';
        $city = $shippingAddress->getCity();
        $country = $shippingAddress->getCountry();
        if ($country) {
            $country = Mage::app()->getLocale()->getCountryTranslation($country);
        }
        if ($city && $country) {
            return $city . ", " . $country;
        } elseif ($city) {
            return $city;
        } elseif ($country) {
            return $country;
        } else {
            return '';
        }
    }

}
