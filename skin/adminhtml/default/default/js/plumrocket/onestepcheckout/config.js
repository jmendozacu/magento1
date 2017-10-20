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


var catalogWysiwygEditor = {
    overlayShowEffectOptions : null,
    overlayHideEffectOptions : null,
    open : function(editorUrl, elementId) {
        if (editorUrl && elementId) {
            new Ajax.Request(editorUrl, {
                parameters: {
                    element_id: elementId+'_editor',
                    store_id: '0'
                },
                onSuccess: function(transport) {
                    try {
                        this.openDialogWindow(transport.responseText, elementId);
                    } catch(e) {
                        alert(e.message);
                    }
                }.bind(this)
            });
        }
    },
    openDialogWindow : function(content, elementId) {
        this.overlayShowEffectOptions = Windows.overlayShowEffectOptions;
        this.overlayHideEffectOptions = Windows.overlayHideEffectOptions;
        Windows.overlayShowEffectOptions = {duration:0};
        Windows.overlayHideEffectOptions = {duration:0};

        Dialog.confirm(content, {
            draggable:true,
            resizable:true,
            closable:true,
            className:"magento",
            windowClassName:"popup-window",
            title:'WYSIWYG Editor',
            width:950,
            height:555,
            zIndex:1000,
            recenterAuto:false,
            hideEffect:Element.hide,
            showEffect:Element.show,
            id:"catalog-wysiwyg-editor",
            buttonClass:"form-button",
            okLabel:"Submit",
            ok: this.okDialogWindow.bind(this),
            cancel: this.closeDialogWindow.bind(this),
            onClose: this.closeDialogWindow.bind(this),
            firedElementId: elementId
        });

        content.evalScripts.bind(content).defer();

        $(elementId+'_editor').value = $(elementId).value;
    },
    okDialogWindow : function(dialogWindow) {
        if (dialogWindow.options.firedElementId) {
            wysiwygObj = eval('wysiwyg'+dialogWindow.options.firedElementId+'_editor');
            wysiwygObj.turnOff();
            if (tinyMCE.get(wysiwygObj.id)) {
                $(dialogWindow.options.firedElementId).value = tinyMCE.get(wysiwygObj.id).getContent();
            } else {
                if ($(dialogWindow.options.firedElementId+'_editor')) {
                    $(dialogWindow.options.firedElementId).value = $(dialogWindow.options.firedElementId+'_editor').value;
                }
            }
        }
        this.closeDialogWindow(dialogWindow);
    },
    closeDialogWindow : function(dialogWindow) {
        // remove form validation event after closing editor to prevent errors during save main form
        if (typeof varienGlobalEvents != undefined && editorFormValidationHandler) {
            varienGlobalEvents.removeEventHandler('formSubmit', editorFormValidationHandler);
        }

        //IE fix - blocked form fields after closing
        $(dialogWindow.options.firedElementId).focus();

        //destroy the instance of editor
        wysiwygObj = eval('wysiwyg'+dialogWindow.options.firedElementId+'_editor');
        if (tinyMCE.get(wysiwygObj.id)) {
           tinyMCE.execCommand('mceRemoveControl', true, wysiwygObj.id);
        }

        dialogWindow.close();
        Windows.overlayShowEffectOptions = this.overlayShowEffectOptions;
        Windows.overlayHideEffectOptions = this.overlayHideEffectOptions;
    }
};

var _checkEnable = function(ev) {
    var chk = ev.target;

    if ($('onestepcheckout_address_form_settings_address_fields_inherit') && $('onestepcheckout_address_form_settings_address_fields_inherit').checked){
        chk.up('tr').addClassName('not-active');
        chk.up('tr').select('input.checkbox[name$="[required]"]')[0].disable();
        chk.up('tr').select('input.checkbox[name$="[enable]"]')[0].disable();
    } else {
        if (!chk.checked) {
            chk.up('tr').addClassName('not-active');
            chk.up('tr').select('input.checkbox[name$="[required]"]')[0].checked = false;
            chk.up('tr').select('input.checkbox[name$="[required]"]')[0].disable();
            if ( chk.readAttribute('name').search(/\[password\]/i) >= 0 ) {
                $next_tr = chk.up('tr').next('tr');
                if (!$next_tr.hasClassName('not-active')) {
                    $next_tr.addClassName('not-active');
                }
                $next_tr.select('input.checkbox[name$="[enable]"]')[0].checked = false;
                $next_tr.select('input.checkbox[name$="[enable]"]')[0].disable();
                $next_tr.select('input.checkbox[name$="[required]"]')[0].checked = false;
                $next_tr.select('input.checkbox[name$="[required]"]')[0].disable();
            }
        } else {
            chk.up('tr').removeClassName('not-active');
            if ( chk.readAttribute('name').search(/\[password\]/i) >= 0 || chk.readAttribute('name').search(/\[confirm_password\]/i) >= 0 ) {
                chk.up('tr').select('input.checkbox[name$="[required]"]')[0].checked = true;
                chk.up('tr').select('input.checkbox[name$="[required]"]')[0].disable();
            } else {
                chk.up('tr').select('input.checkbox[name$="[required]"]')[0].enable();
            }
            if ( chk.readAttribute('name').search(/\[password\]/i) >= 0 ) {
                $next_tr = chk.up('tr').next('tr');
                if ( $next_tr.select('input.checkbox[name$="[enable]"]')[0].checked ){
                    $next_tr.removeClassName('not-active');
                }
                $next_tr.select('input.checkbox[name$="[enable]"]')[0].enable();
            }
        }
    }
}

function setChecks(table_ch_items){
    for (var i = 0, _len = table_ch_items.length; i < _len; i++) {
        table_ch_items[i].observe("click", _checkEnable);
        _checkEnable({target: table_ch_items[i]});
    }
}
var _table_ch_items = $$('.form-list .grid table.data tbody input.checkbox[name$="[enable]"]');
setChecks(_table_ch_items);

if ($('onestepcheckout_address_form_settings_address_fields_inherit')){
    $('onestepcheckout_address_form_settings_address_fields_inherit').observe("click", function(){
        setChecks(_table_ch_items);
    });
}

if ($('onestepcheckout_additional_display_settings_footer_content_inherit') && $('onestepcheckout_additional_display_settings_footer_content_inherit').checked) {
    $('row_onestepcheckout_additional_display_settings_footer_content').select('button')[0].addClassName('disabled').disable();
}

var _onestepcheckout_adminhtml = {
    closeMsg : function(block) {
        var date = new Date;
        date.setDate(date.getDate() + 7);
        var message = document.getElementById(block);
        document.cookie = block + "=closed; expires=" + date.toUTCString() + ';path=/';
        message.remove();
    }
}