<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php

class IcoTheme_IcoTheme_Block_Adminhtml_System_Config_Form_Field_Color extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = parent::_getElementHtml($element);
        $html .= '
       		<script type="text/javascript">
				jQuery(function($){
					$("#' . $element->getHtmlId() . '").attr("data-hex", true).width("250px").mColorPicker();
				});
			</script>
       	';
        return $html;
    }
}

?>