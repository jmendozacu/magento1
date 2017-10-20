<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php
class IcoTheme_Wood_Block_Adminhtml_Product_Layout_Layout extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element){
        $html = parent::_getElementHtml($element);
        $imgParth = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/wysiwyg/icotheme/wood/product/layout/';
        $html .= '<br/><div id="layout_'.$element->getHtmlId().'" class="layout_preview" style="min-height: 210px;"></div>';
        $html .= '
            <script type="text/javascript">
                jQuery(window).load(function(){
                    var layout = jQuery("#'.$element->getHtmlId().' option:selected").val();
                    jQuery("#'.$element->getHtmlId().'")
                        .change(function() {
                            var imageLayout = "";
                            jQuery( "#'.$element->getHtmlId().' option:selected" ).each(function() {
                            imageLayout += "'.$imgParth.'"+jQuery( this ).val()+".png";
                        });
                        jQuery("#layout_'.$element->getHtmlId().'").html("<img src="+imageLayout+" />");
                    }).trigger("change");
                });
            </script>';
        return $html;
    }
}
?>