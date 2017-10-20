/*
 Created on : Jan 7, 2015, 9:24:34 AM
 Author: Tran Trong Thang
 Email: trantrongthang1207@gmai.com
 */

jQuery(document).ready(function($) {
    $(".fgcfreevisual img").click(function() {
        $(".fgcfreevisualform").css({
            top: 0,
            left: "40%"
        });
        /*
        Recaptcha.create("6LcK7AITAAAAAII4YmuO8mmwocxYHmY60u3-uvfp",
                "webform_3_recaptcha",
                {
                    "theme": "red",
                    "lang": "en",
                    "custom_translations": [],
                    callback: captchaLoaded
                }
        );*/
    });
    function captchaLoaded() {
        console.log('here')
        $('#webform_3_recaptcha')
                .on('added.field.fv', function(e, data) {
            // The field "recaptcha_response_field" has just been added
            if (data.field === 'recaptcha_response_field') {
                // Find the icon
                var $icon = data.element.data('fv.icon');

                // Move icon to other position
                $icon.insertAfter('#recaptcha');
            }
        })
    }
    $(".fgctitleform > span").click(function() {
        $(".fgcfreevisualform").css({
            top: -10000 + "px",
            left: -10000 + "px"
        });
    });
    $(".fgcfreevisualform h2.legend").text("Help us to stop spammers:");
    $(".tvcontactus h2.legend").text("Help us to stop spammers:");
    $("#webform_8 h2.legend").text("Help us to stop spammers:");
    $(".fgcfreequote img").click(function() {
        $(".fgcfreesalesforce").css({
            top: 0,
            left: "40%"
        });
    });
    $(".fgcfreesalesforce .fgctitleform > span").click(function() {
        $(".fgcfreesalesforce").css({
            top: -10000 + "px",
            left: -10000 + "px"
        });
    });
    $(".fgcfreesalesforce h2.legend").text("Help us to stop spammers:");

})