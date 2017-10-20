<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please 
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_One_Step_Checkout
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


class Plumrocket_OneStepCheckout_Block_System_Config_Rewards extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	const REWARD_POINT_ROW_ID = "row_onestepcheckout_additional_display_settings_rewards_enable";

	public function render(Varien_Data_Form_Element_Abstract $element)
	{

		if (!Mage::getConfig()->getModuleConfig('Plumrocket_Rewards') && !Mage::getSingleton('core/cookie')->get(self::REWARD_POINT_ROW_ID) ) {
			$message = Mage::helper('onestepcheckout')->__('Hint: Your customers can earn reward points for purchases. Get 10% discount on <a href="https://store.plumrocket.com/magento-extensions/reward-points-magento-extension.html" target="_blank">Reward Points Entension</a> with promo code: <strong>CSPSAVE10</strong>');

			$html = $this->getCloseJs();
			$html .= '<tr id="' . self::REWARD_POINT_ROW_ID . '">';
			$html .= '<td colspan="4"><div style="' . $this->_getStyle() . '">'. $this->getCloseBtn(self::REWARD_POINT_ROW_ID) . $message.'</div></td><tr>';

			return $html;
		} elseif (Mage::getConfig()->getModuleConfig('Plumrocket_Rewards')) {
	        return '<input type="hidden" name="groups[additional_display_settings][fields][rewards_enable][value]" id="onestepcheckout_additional_display_settings_rewards_enable" value="1" />';
		}
	}


	public function getCloseBtn($block)
	{
		return '<div onclick="_onestepcheckout_adminhtml.closeMsg(`'.$block. '`)" style="position: absolute; right: 6px; color: #626262; cursor: pointer; top: 0px;">&#10005;</div>';
	}


	private function _getStyle()
	{
		return "padding: 10px; padding-right: 15px; background-color: #E4F2FF; border: 1px solid #A9C9E7; margin-bottom: 7px;  position: relative;";
	}
}