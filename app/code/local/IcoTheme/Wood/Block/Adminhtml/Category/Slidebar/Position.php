<?php
/**
 * @copyright    Copyright (C) 2015 IcoTheme.com. All Rights Reserved.
 */
?>
<?php
class IcoTheme_Wood_Block_Adminhtml_Category_Slidebar_Position extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element){ 
       	$html = parent::_getElementHtml($element);
        $urlparth = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $js = $this->getJsUrl('icotheme/jquery/jquery-1.8.2.min.js');
        $html .= '
                <script type="text/javascript" src="'. $js .'"></script>
                <script type="text/javascript">jQuery.noConflict();</script>
                ';
        $html .='<link href="'.$urlparth.'skin/adminhtml/wood/css/style.css'.'" type="text/css" rel="stylesheet">';
       	$html .= '<div class="slidebar_wrapper">';
        $html .='<span class="item">
						<span class="img-left"><img src="'.$urlparth.'skin/adminhtml/wood/images/sidebar_left.png"/></span>
						<input type="radio" name="'.$element->getHtmlId().'_img" value="slidebar_left" style="margin: 8px; 0 4px 0" class="input"/>
						Left
					 </span>';
        $html .='<span class="item">
						<span class="img-right"><img src="'.$urlparth.'skin/adminhtml/wood/images/sidebar_right.png"/></span>
						<input type="radio" name="'.$element->getHtmlId().'_img" value="slidebar_right" style="margin: 8px; 0 4px 0" class="input"/>
						Right
					 </span>';
        $html .= '</div>';
        $html .= '<script type="text/javascript">
                jQuery(window).load(function(){
                    var position = jQuery("#'.$element->getHtmlId().'").val();
                    jQuery(".slidebar_wrapper .item input:radio").each(function(n,e){
                        if(jQuery(e).val() == position) {
                            jQuery(e).attr("checked",true);
                        }
                    });
                    jQuery(".slidebar_wrapper .item").click(function(){
                        jQuery(this).find("input").attr("checked",true);
                        jQuery("#'.$element->getHtmlId().'").val(jQuery(this).find("input").val())
                    });
                });
                  </script>';
        return $html;
    }
}
?>