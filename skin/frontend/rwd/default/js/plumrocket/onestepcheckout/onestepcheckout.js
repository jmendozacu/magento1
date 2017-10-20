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

var filedForms = {};

var Checkout = Class.create();
Checkout.prototype = {
    initialize: function (urls) {
        this.preSaveUrl = urls.preSave;
        this.failureUrl = urls.failure;
        this.billingForm = false;
        this.shippingForm = false;
        this.syncBillingShipping = false;
        this.method = '';
        this.payment = '';
        this.loadWaiting = false;
    },
    ajaxFailure: function () {
        location.href = this.failureUrl;
    },
    setLoadWaiting: function (step) {
        if (step) {
            pjQuery_1_10_2('button.btn-checkout').prop('disabled', true);
            pjQuery_1_10_2('#' + step + '-ajax-loader').show();
            this.loadWaiting = true;
        } else {
            pjQuery_1_10_2('button.btn-checkout').prop('disabled', false);
            pjQuery_1_10_2('.ajax-loader').hide();
            this.loadWaiting = false;
        }
    },
    setStepResponse: function (response) {
        if (response.update_section) {

            var sectionForms = {
                'payment-method': payment.form,
                'review': review.agreementsForm
            }

            if (response.update_section.name) {
                if ($('checkout-' + response.update_section.name + '-load')) {
                    $('checkout-' + response.update_section.name + '-load').update(response.update_section.html);
                }
            } else {
                for (var section in response.update_section) {
                    if ($('checkout-' + response.update_section[section].name + '-load')) {

                        if (sectionForms[section] && $(sectionForms[section])) {
                            /*
                             * Changed by: Tran Trong Thang
                             * Email: trantrongthang1207@gmail.com
                             var arrValues = {};
                             */
                            var arrValues = [];
                            var arrElements = Form.getElements(sectionForms[section]);
                            for (var elemIndex in arrElements) {
                                if (arrElements[elemIndex].type != undefined) {
                                    /*
                                     if (arrElements[elemIndex].type == 'radio' || arrElements[elemIndex].type == 'checkbox') {
                                     arrValues[elemIndex] = arrElements[elemIndex].checked;
                                     } else {
                                     arrValues[elemIndex] = arrElements[elemIndex].value;
                                     }*/
                                    if (arrElements[elemIndex].type != undefined) {
                                        if (arrElements[elemIndex].type == 'radio' || arrElements[elemIndex].type == 'checkbox') {
                                            var arrItem = {
                                                'id': arrElements[elemIndex].getAttribute('id'),
                                                'value': arrElements[elemIndex].checked
                                            }
                                        } else {
                                            var arrItem = {
                                                'id': arrElements[elemIndex].getAttribute('id'),
                                                'value': arrElements[elemIndex].value
                                            }
                                        }
                                    }
                                    arrValues.push(arrItem);
                                }
                            }
                        }

                        $('checkout-' + response.update_section[section].name + '-load').update(response.update_section[section].html);

                        if (sectionForms[section] && $(sectionForms[section])) {
                            var arrElements = Form.getElements(sectionForms[section]);
                            if (typeof arrValues != 'undefined') {
                                for (var elemIndex in arrValues) {
                                    if (typeof arrElements[elemIndex] != 'undefined') {
                                        /*
                                         if (arrElements[elemIndex].type == 'radio' || arrElements[elemIndex].type == 'checkbox') {
                                         arrElements[elemIndex].checked = arrValues[elemIndex];
                                         } else {
                                         arrElements[elemIndex].value = arrValues[elemIndex];
                                         }*/
                                        if (typeof arrValues[elemIndex] != 'object')
                                            continue;
                                        if (typeof jQuery('#' + arrValues[elemIndex]['id'])[0] != 'undefined') {
                                            if (jQuery('#' + arrValues[elemIndex]['id']).attr('type') == 'radio' || jQuery('#' + arrValues[elemIndex]['id']).attr('type') == 'checkbox') {
                                                jQuery('#' + arrValues[elemIndex]['id']).prop('checked', arrValues[elemIndex]['value']);
                                            } else {
                                                jQuery('#' + arrValues[elemIndex]['id']).val(arrValues[elemIndex]['value']);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
            }
        }
        if (response.redirect) {
            location.href = response.redirect;
            return true;
        }
        checkout.setLoadWaiting(false);
        return false;
    },
    getSteps: function () {
        var items = [];
        if (window.billing) {
            items.push(billing);
        } else {
            items.push(null);
        }
        if (window.shipping) {
            items.push(shipping);
        } else {
            items.push(null);
        }
        if (window.shippingMethod) {
            items.push(shippingMethod);
        } else {
            items.push(null);
        }
        if (window.payment) {
            items.push(payment);
        } else {
            items.push(null);
        }
        return items;
    },
    preSave: function (stepCode) {
        if (checkout.loadWaiting != false)
            return;

        var items = checkout.getSteps();

        if (items[stepCode]) {
            var validator = new Validation(items[stepCode].form);
            if (!validator.validate()) {
                return;
            }
        }

        checkout.setLoadWaiting('review');
        var params = '';
        var steps = '';
        for (var i = stepCode; i < items.length; i++) {
            if (items[i]) {
                checkout.setLoadWaiting(items[i].stepName);
                params += '&' + Form.serialize(items[i].form);
                steps += (!steps) ? i : ',' + i;
            }
            if (items[i].form == 'co-payment-form') {
                if (jQuery("#p_method_ewayrapid_ewayone").is(':checked')) {
                    params += '&payment[cc_number]=' + jQuery("#ewayrapid_ewayone_cc_number").val() + '&payment[cc_cid]=' + jQuery("#ewayrapid_ewayone_cc_cid").val();
                }
            }
        }
        params += '&method=' + pjQuery_1_10_2('#checkout_method').val();
        params += '&steps=' + steps;

        var request = new Ajax.Request(
                this.preSaveUrl,
                {
                    method: 'post',
                    parameters: params,
                    onSuccess: this.nextStep.bindAsEventListener(this),
                    onFailure: checkout.ajaxFailure.bind(checkout)
                }
        );
    },
    preLoadShipping: function (stepCode) {
        if (checkout.loadWaiting != false)
            return;

        var items = checkout.getSteps();

        if (stepCode == 0) {
            var $formbilling = jQuery('#co-billing-form');
            var error = false;
            $formbilling.find('.customer-name .field input').each(function () {
                var e = jQuery(this);
                if (e.val() == '') {
                    e.addClass('validation-failed');
                    error = true;
                } else {
                    e.removeClass('validation-failed');
                }
            });
            $formbilling.find('input.validate-email').each(function () {
                var e = jQuery(this);
                if (e.val() == '') {
                    e.addClass('validation-failed');
                    error = true;
                } else {
                    e.removeClass('validation-failed');
                }
            });
            if (error == true) {
                return;
            }
        }

        checkout.setLoadWaiting('review');
        var params = '';
        var steps = '';
        for (var i = stepCode; i < items.length; i++) {
            if (items[i]) {
                checkout.setLoadWaiting(items[i].stepName);
                params += '&' + Form.serialize(items[i].form);
                steps += (!steps) ? i : ',' + i;
            }
            if (items[i].form == 'co-payment-form') {
                if (jQuery("#p_method_ewayrapid_ewayone").is(':checked')) {
                    params += '&payment[cc_number]=' + jQuery("#ewayrapid_ewayone_cc_number").val() + '&payment[cc_cid]=' + jQuery("#ewayrapid_ewayone_cc_cid").val();
                }
            }
        }
        params += '&method=' + pjQuery_1_10_2('#checkout_method').val();
        params += '&steps=' + steps;

        var request = new Ajax.Request(
                this.preSaveUrl,
                {
                    method: 'post',
                    parameters: params,
                    onSuccess: this.nextStep.bindAsEventListener(this),
                    onFailure: checkout.ajaxFailure.bind(checkout)
                }
        );
    },
    validate: function () {
        var bs = true;
        if (window.billing) {
            var validator = new Validation(billing.form);
            bs = validator.validate();
        }

        var shs = true;
        if (window.shipping) {
            var validator = new Validation(shipping.form);
            shs = validator.validate();
        }

        var ps = true;
        if (window.payment) {
            var validator = new Validation(payment.form);
            ps = validator.validate() && payment.validate();
        }

        var shsm = true;
        if (window.shippingMethod) {
            shsm = shippingMethod.validate();
        }

        var agreementHolder = pjQuery_1_10_2('#checkout-agreements');
        var error = false;
        if (agreementHolder.html()) {
            agreementHolder.find('input.required-entry').each(function () {
                var e = pjQuery_1_10_2(this);
                e.parent().next().hide();
                if (!e.is(':checked')) {
                    e.parent().find('.validation-advice').show();
                    error = true;
                }
            });
        }

        return !error && bs && ps && shs && shsm;
    },
    nextStep: function (transport) {
        if (transport && transport.responseText) {
            try {
                response = eval('(' + transport.responseText + ')');
            } catch (e) {
                response = {};
            }
        }
        checkout.setStepResponse(response);
        if (window.payment) {
            payment.initWhatIsCvvListeners();
        }
    },
    isFilledForm: function (formId) {
        var result = true;
        pjQuery_1_10_2(formId + " .required-entry:visible").each(function () {
            if (!pjQuery_1_10_2.trim(pjQuery_1_10_2(this).val())) {
                result = false;
            }
        });
        return result;
    }
}

// billing
var Billing = Class.create();
Billing.prototype = {
    stepName: 'billing',
    initialize: function (form, addressUrl) {
        this.form = form;
        this.addressUrl = addressUrl;
        this.onAddressLoad = this.fillForm.bindAsEventListener(this);
    },
    setAddress: function (addressId) {
        if (addressId) {
            request = new Ajax.Request(
                    this.addressUrl + addressId,
                    {method: 'get', onSuccess: this.onAddressLoad, onFailure: checkout.ajaxFailure.bind(checkout)}
            );
        } else {
            this.fillForm(false);
        }
    },
    newAddress: function (isNew) {
        if (isNew) {
            this.resetSelectedAddress();
            Element.show('billing-new-address-form');
        } else {
            Element.hide('billing-new-address-form');
        }
    },
    resetSelectedAddress: function () {
        var selectElement = $('billing-address-select')
        if (selectElement) {
            selectElement.value = '';
        }
    },
    fillForm: function (transport) {
        var elementValues = {};
        if (transport && transport.responseText) {
            try {
                elementValues = eval('(' + transport.responseText + ')');
            } catch (e) {
                elementValues = {};
            }
        } else {
            this.resetSelectedAddress();
        }
        arrElements = Form.getElements(this.form);
        for (var elemIndex in arrElements) {
            if (arrElements[elemIndex].id) {
                var fieldName = arrElements[elemIndex].id.replace(/^billing:/, '');
                arrElements[elemIndex].value = elementValues[fieldName] ? elementValues[fieldName] : '';
                if (fieldName == 'country_id' && billingForm) {
                    billingForm.elementChildLoad(arrElements[elemIndex]);
                }
            }
        }
    },
    setUseForShipping: function (flag) {
        $('shipping:same_as_billing').checked = flag;
    }
}

// shipping
var Shipping = Class.create();
Shipping.prototype = {
    stepName: 'shipping',
    initialize: function (form, addressUrl) {
        this.form = form;
        this.addressUrl = addressUrl;
        this.onAddressLoad = this.fillForm.bindAsEventListener(this);
    },
    setAddress: function (addressId) {
        if (addressId) {
            request = new Ajax.Request(
                    this.addressUrl + addressId,
                    {method: 'get', onSuccess: this.onAddressLoad, onFailure: checkout.ajaxFailure.bind(checkout)}
            );
        } else {
            this.fillForm(false);
        }
    },
    newAddress: function (isNew) {
        if (isNew) {
            this.resetSelectedAddress();
            Element.show('shipping-new-address-form');
        } else {
            Element.hide('shipping-new-address-form');
        }
        shipping.setSameAsBilling(false);
    },
    resetSelectedAddress: function () {
        var selectElement = $('shipping-address-select')
        if (selectElement) {
            selectElement.value = '';
        }
    },
    fillForm: function (transport) {
        var elementValues = {};
        if (transport && transport.responseText) {
            try {
                elementValues = eval('(' + transport.responseText + ')');
            } catch (e) {
                elementValues = {};
            }
        } else {
            this.resetSelectedAddress();
        }
        arrElements = Form.getElements(this.form);
        for (var elemIndex in arrElements) {
            if (arrElements[elemIndex].id) {
                var fieldName = arrElements[elemIndex].id.replace(/^shipping:/, '');
                arrElements[elemIndex].value = elementValues[fieldName] ? elementValues[fieldName] : '';
                if (fieldName == 'country_id' && shippingForm) {
                    shippingForm.elementChildLoad(arrElements[elemIndex]);
                }
            }
        }
    },
    setSameAsBilling: function (flag) {
        $('shipping:same_as_billing').checked = flag;
// #5599. Also it hangs up, if the flag is not false
//        $('billing:use_for_shipping_yes').checked = flag;
        if (flag) {
            this.syncWithBilling();
            Element.hide('shipping-new-address-form');
            if ($('shipping-address-select')) {
                Element.hide('shipping-address-select-block');
            }
            var shoppingTextName = $('shipping:firstname').value + " " + $('shipping:lastname').value;
            var shoppingTextCompany = ($('shipping:company')) ? $('shipping:company').value : '';
            var shoppingTextStreet = (($('shipping:street1') && $('shipping:street1').value) ? $('shipping:street1').value + " " : "") + (($('shipping:street2') && $('shipping:street2').value) ? $('shipping:street2').value : "");
            var shoppingTextCity = (($('shipping:city').value) ? $('shipping:city').value + ", " : "") + (($('shipping:region').value) ? $('shipping:region').value : (($('shipping:region_id').value) ? pjQuery_1_10_2($('shipping:region_id')).find('option[value="' + $('shipping:region_id').value + '"]').html() : ""));
            var shoppingTextCountry = pjQuery_1_10_2($('shipping:country_id')).find('option[value="' + $('shipping:country_id').value + '"]').html();
            shoppingTextCountry += (pjQuery_1_10_2.trim(shoppingTextCountry) && pjQuery_1_10_2.trim($('shipping:postcode').value)) ? ', ' + $('shipping:postcode').value : $('shipping:postcode').value;
            var shoppingTextTel = (($('shipping:telephone') && $('shipping:telephone').value) ? $('shipping:telephone').value : "") + (($('shipping:telephone') && $('shipping:telephone').value && $('shipping:fax') && $('shipping:fax').value) ? ", " : "") + (($('shipping:fax') && $('shipping:fax').value) ? $('shipping:fax').value : "");

            if (pjQuery_1_10_2.trim(shoppingTextName)) {
                pjQuery_1_10_2('#shipping-text .shipping-text-name').text(pjQuery_1_10_2.trim(shoppingTextName));
            }
            if (pjQuery_1_10_2.trim(shoppingTextCompany)) {
                pjQuery_1_10_2('#shipping-text .shipping-text-company').text(pjQuery_1_10_2.trim(shoppingTextCompany));
            }
            if (pjQuery_1_10_2.trim(shoppingTextStreet)) {
                pjQuery_1_10_2('#shipping-text .shipping-text-street').text(pjQuery_1_10_2.trim(shoppingTextStreet));
            }
            if (pjQuery_1_10_2.trim(shoppingTextCity)) {
                pjQuery_1_10_2('#shipping-text .shipping-text-city').text(pjQuery_1_10_2.trim(shoppingTextCity));
            }
            if (pjQuery_1_10_2.trim(shoppingTextCountry)) {
                pjQuery_1_10_2('#shipping-text .shipping-text-country').text(pjQuery_1_10_2.trim(shoppingTextCountry));
            }
            if (pjQuery_1_10_2.trim(shoppingTextTel)) {
                pjQuery_1_10_2('#shipping-text .shipping-text-telephone').text(pjQuery_1_10_2.trim(shoppingTextTel));
            }

            pjQuery_1_10_2('#shipping-text').show();
        } else {
            pjQuery_1_10_2('#shipping-text').hide();
            if ($('shipping-address-select')) {
                Element.show('shipping-address-select-block');
                if (!$('shipping-address-select').value) {
                    Element.show('shipping-new-address-form');
                }
            } else {
                Element.show('shipping-new-address-form');
            }
        }
    },
    syncWithBilling: function () {
        $('billing-address-select') && this.newAddress(!$('billing-address-select').value);
        $('shipping:same_as_billing').checked = true;
        if (!$('billing-address-select') || !$('billing-address-select').value) {
            arrElements = Form.getElements(this.form);
            for (var elemIndex in arrElements) {
                if (arrElements[elemIndex].id) {
                    var sourceField = $(arrElements[elemIndex].id.replace(/^shipping:/, 'billing:'));
                    if (sourceField) {
                        arrElements[elemIndex].value = sourceField.value;
                    }
                }
            }
            //$('shipping:country_id').value = $('billing:country_id').value;
            shippingRegionUpdater.update();
            $('shipping:region_id').value = $('billing:region_id').value;
            $('shipping:region').value = $('billing:region').value;
            //shippingForm.elementChildLoad($('shipping:country_id'), this.setRegionValue.bind(this));
        } else {
            $('shipping-address-select').value = $('billing-address-select').value;
        }
    },
    setRegionValue: function () {
        $('shipping:region').value = $('billing:region').value;
    }
}

// shipping method
var ShippingMethod = Class.create();
ShippingMethod.prototype = {
    stepName: 'shipping',
    initialize: function (form) {
        this.form = form;
        this.validator = new Validation(this.form);
    },
    validate: function () {
        var methods = document.getElementsByName('shipping_method');
        if (methods.length == 0) {
            alert(Translator.translate('Your order cannot be completed at this time as there is no shipping methods available for it. Please make necessary changes in your shipping address.').stripTags());
            return false;
        }

        if (!this.validator.validate()) {
            return false;
        }

        for (var i = 0; i < methods.length; i++) {
            if (methods[i].checked) {
                return true;
            }
        }
        alert(Translator.translate('Please specify shipping method.').stripTags());
        return false;
    }
}


// payment
var Payment = Class.create();
Payment.prototype = {
    stepName: 'payment',
    beforeInitFunc: $H({}),
    afterInitFunc: $H({}),
    beforeValidateFunc: $H({}),
    afterValidateFunc: $H({}),
    initialize: function (form) {
        this.form = form;
    },
    addBeforeInitFunction: function (code, func) {
        this.beforeInitFunc.set(code, func);
    },
    beforeInit: function () {
        (this.beforeInitFunc).each(function (init) {
            (init.value)();
            ;
        });
    },
    init: function () {
        this.beforeInit();
        var elements = Form.getElements(this.form);
        var method = null;
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].name == 'payment[method]') {
                if (elements[i].checked) {
                    method = elements[i].value;
                }
            } else {
                elements[i].disabled = true;
            }
            elements[i].setAttribute('autocomplete', 'off');
        }
        if (method)
            this.switchMethod(method);
        this.afterInit();
    },
    addAfterInitFunction: function (code, func) {
        this.afterInitFunc.set(code, func);
    },
    afterInit: function () {
        (this.afterInitFunc).each(function (init) {
            (init.value)();
        });
    },
    switchMethod: function (method) {
        if (this.currentMethod && $('payment_form_' + this.currentMethod)) {
            this.changeVisible(this.currentMethod, true);
            $('payment_form_' + this.currentMethod).fire('payment-method:switched-off', {method_code: this.currentMethod});
        }
        if ($('payment_form_' + method)) {
            this.changeVisible(method, false);
            $('payment_form_' + method).fire('payment-method:switched', {method_code: method});
        } else {
            //Event fix for payment methods without form like "Check / Money order"
            document.body.fire('payment-method:switched', {method_code: method});
        }
        if (method) {
            this.lastUsedMethod = method;
        }
        this.currentMethod = method;
        pjQuery_1_10_2('.payment-button').removeClass('active');
        pjQuery_1_10_2('.button-' + method).addClass('active');
    },
    changeVisible: function (method, mode) {
        var block = 'payment_form_' + method;
        [block + '_before', block, block + '_after'].each(function (el) {
            element = $(el);
            if (element) {
                element.style.display = (mode) ? 'none' : '';
                element.select('input', 'select', 'textarea', 'button').each(function (field) {
                    field.disabled = mode;
                });
            }
        });
    },
    addBeforeValidateFunction: function (code, func) {
        this.beforeValidateFunc.set(code, func);
    },
    beforeValidate: function () {
        var validateResult = true;
        var hasValidation = false;
        (this.beforeValidateFunc).each(function (validate) {
            hasValidation = true;
            if ((validate.value)() == false) {
                validateResult = false;
            }
        }.bind(this));
        if (!hasValidation) {
            validateResult = false;
        }
        return validateResult;
    },
    validate: function () {
        var result = this.beforeValidate();
        if (result) {
            return true;
        }
        var methods = document.getElementsByName('payment[method]');
        if (methods.length == 0) {
            alert(Translator.translate('Your order cannot be completed at this time as there is no payment methods available for it.').stripTags());
            return false;
        }
        for (var i = 0; i < methods.length; i++) {
            if (methods[i].checked) {
                return true;
            }
        }
        result = this.afterValidate();
        if (result) {
            return true;
        }
        alert(Translator.translate('Please specify payment method.').stripTags());
        return false;
    },
    addAfterValidateFunction: function (code, func) {
        this.afterValidateFunc.set(code, func);
    },
    afterValidate: function () {
        var validateResult = true;
        var hasValidation = false;
        (this.afterValidateFunc).each(function (validate) {
            hasValidation = true;
            if ((validate.value)() == false) {
                validateResult = false;
            }
        }.bind(this));
        if (!hasValidation) {
            validateResult = false;
        }
        return validateResult;
    },
    initWhatIsCvvListeners: function () {
        $$('.cvv-what-is-this').each(function (element) {
            Event.observe(element, 'click', toggleToolTip);
        });
    }
}

var Review = Class.create();
Review.prototype = {
    stepName: 'review',
    initialize: function (saveUrl, successUrl, agreementsForm) {
        this.mySaveUrl = saveUrl;
        this.saveUrl = saveUrl;
        this.successUrl = successUrl;
        this.agreementsForm = agreementsForm;
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },
    save: function () {
        if (checkout.validate()) {
            if (checkout.loadWaiting != false)
                return;

            checkout.setLoadWaiting('review');

            var item_special_ = pjQuery_1_10_2('input[name="is-category-special"]').length;//special product
            if (item_special_) {
                var tvpass = pjQuery_1_10_2('#register-customer-password input[name="billing[customer_password]"]').val();
                var tvpasscon = pjQuery_1_10_2('#register-customer-password input[name="billing[confirm_password]"]').val();
                var is_login = pjQuery_1_10_2('input[name="is_login"]').val();

                if (is_login != 'true' && !pjQuery_1_10_2('#checkout_method').is(':checked') || (tvpass == '' && tvpasscon == '')) {
                    showMessage("The password cannot be empty!!");
                    checkout.setLoadWaiting(false);
                    return;
                }
                pjQuery_1_10_2('#review-buttons-container button.opc-btn-checkout').attr('onclick', checkMedical());
            }
            var params = '';
            var items = checkout.getSteps();
            for (var i = 0; i < items.length; i++) {
                if (items[i]) {
                    params += '&' + Form.serialize(items[i].form);
                }
            }
            if ($(this.agreementsForm)) {
                params += '&' + Form.serialize(this.agreementsForm);
            }
            params += '&method=' + pjQuery_1_10_2('#checkout_method').val();
            params.save = true;

            var request = new Ajax.Request(
                    this.mySaveUrl,
                    {
                        method: 'post',
                        parameters: params,
                        onComplete: this.onComplete,
                        onSuccess: this.onSave,
                        onFailure: checkout.ajaxFailure.bind(checkout)
                    }
            );
        }
    },
    resetLoadWaiting: function (transport) {
        //checkout.setLoadWaiting(false);
        jQuery('div.alert button.close').prop('disabled', false).removeClass('disabled');
    },
    nextStep: function (transport) {
        if (transport && transport.responseText) {
            try {
                response = eval('(' + transport.responseText + ')');
            } catch (e) {
                response = {};
            }

            if (response.success) {
                this.isSuccess = true;
                window.location = this.successUrl;
            } else {
                var msg = response.error_messages;
                if (typeof (msg) == 'object') {
                    msg = msg.join("\n");
                }
                if (msg) {
                    alert(msg);
                }
                if (typeof response.error_messages == 'undefined') {
                    if (typeof response.error != 'undefined') {
                        jQuery('.alert').remove();
                        jQuery('.fgc-overlay1').remove();
                        alert(response.error);
                    }
                }
                checkout.setStepResponse(response);
            }
        }
    },
    isSuccess: false
}


// CouponCode
var CouponCode = Class.create();
CouponCode.prototype = {
    initialize: function (form, saveUrl) {
        this.form = form;
        if ($(this.form)) {
            $(this.form).observe('submit', function (event) {
                this.save();
                Event.stop(event);
            }.bind(this));
        }
        this.saveUrl = saveUrl;
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },
    save: function () {

        if (checkout.loadWaiting != false)
            return;
        checkout.setLoadWaiting('review');

        var request = new Ajax.Request(
                this.saveUrl,
                {
                    method: 'post',
                    onComplete: this.onComplete,
                    onSuccess: this.onSave,
                    onFailure: checkout.ajaxFailure.bind(checkout),
                    parameters: Form.serialize(this.form)
                }
        );
    },
    resetLoadWaiting: function (transport) {
        checkout.setLoadWaiting(false);
    },
    nextStep: function (transport) {
        var response = {};
        if (transport && transport.responseText) {
            try {
                response = eval('(' + transport.responseText + ')');
            } catch (e) {
                response = {};
            }
        }

        if (response.error) {
            Validation.add(
                    'validate-coupon',
                    response.message,
                    function () {
                        return false;
                    });
            Validation.validate(document.getElementById('coupon_code'));
        }

        checkout.setStepResponse(response);
    }
}


// Reward Points
var RewardPoints = Class.create();
RewardPoints.prototype = {
    initialize: function (form, saveUrl, cancelUrl) {
        this.form = form;
        if ($(this.form)) {
            $(this.form).observe('submit', function (event) {
                this.save();
                Event.stop(event);
            }.bind(this));
        }
        this.saveUrl = saveUrl;
        this.cancelUrl = cancelUrl;
        this.onSave = this.nextStep.bindAsEventListener(this);
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },
    save: function () {

        if (checkout.loadWaiting != false)
            return;
        checkout.setLoadWaiting('review');

        var url = (parseInt($('remove_rewards').value) == 0) ? this.saveUrl : this.cancelUrl;
        var request = new Ajax.Request(
                url,
                {
                    method: 'post',
                    onComplete: this.onComplete,
                    onSuccess: this.onSave,
                    onFailure: checkout.ajaxFailure.bind(checkout),
                    parameters: Form.serialize(this.form)
                }
        );
    },
    resetLoadWaiting: function (transport) {
        checkout.setLoadWaiting(false);
    },
    nextStep: function (transport) {
        var response = {};
        if (transport && transport.responseText) {
            try {
                response = eval('(' + transport.responseText + ')');
            } catch (e) {
                response = {};
            }
        }

        if (response.error) {
            Validation.add(
                    'validate-rewards',
                    response.message,
                    function () {
                        return false;
                    });
            Validation.validate(document.getElementById('rewards_point_count'));
        }

        checkout.setStepResponse(response);
    }
}

// Ajax Login
var AjaxLogin = Class.create();
AjaxLogin.prototype = {
    initialize: function (form) {
        this.form = form;
        if ($(this.form)) {
            $(this.form).observe('submit', function (event) {
                Event.stop(event);
            });
        }
    },
    save: function () {
        var request = new Ajax.Request(
                $(this.form).readAttribute('action'),
                {
                    method: $(this.form).readAttribute('method'),
                    onSuccess: function (transport) {
                        var response = {};
                        if (transport && transport.responseText) {
                            try {
                                response = eval('(' + transport.responseText + ')');
                            } catch (e) {
                                response = {};
                            }
                        }

                        if (!response) {
                            location.reload();
                        } else if (response.error) {
                            Validation.add(
                                    'validate-login',
                                    response.message,
                                    function () {
                                        return false;
                                    });
                            Validation.validate(document.getElementById('login-email'));
                            $('btnLogin').removeClassName('disabled');
                            $('btnLogin').enable();
                        } else {
                            $('btnLogin').removeClassName('disabled');
                            $('btnLogin').enable();
                        }
                    },
                    parameters: Form.serialize(this.form)
                }
        );
    }
}