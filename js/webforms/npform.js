/* jQuery Mask Plugin v1.11.4*/
/* github.com/igorescobar/jQuery-Mask-Plugin*/
(function (b) {
    "function" === typeof define && define.amd ? define(["jquery"], b) : "object" === typeof exports ? module.exports = b(require("jquery")) : b(jQuery || Zepto)
})(function (b) {
    var y = function (a, d, e) {
        a = b(a);
        var g = this, k = a.val(), l;
        d = "function" === typeof d ? d(a.val(), void 0, a, e) : d;
        var c = {invalid: [], getCaret: function () {
                try {
                    var q, v = 0, b = a.get(0), f = document.selection, c = b.selectionStart;
                    if (f && -1 === navigator.appVersion.indexOf("MSIE 10"))
                        q = f.createRange(), q.moveStart("character", a.is("input") ? -a.val().length : -a.text().length),
                                v = q.text.length;
                    else if (c || "0" === c)
                        v = c;
                    return v
                } catch (d) {
                }
            }, setCaret: function (q) {
                try {
                    if (a.is(":focus")) {
                        var b, c = a.get(0);
                        c.setSelectionRange ? c.setSelectionRange(q, q) : c.createTextRange && (b = c.createTextRange(), b.collapse(!0), b.moveEnd("character", q), b.moveStart("character", q), b.select())
                    }
                } catch (f) {
                }
            }, events: function () {
                a.on("keyup.mask", c.behaviour).on("paste.mask drop.mask", function () {
                    setTimeout(function () {
                        a.keydown().keyup()
                    }, 100)
                }).on("change.mask", function () {
                    a.data("changed", !0)
                }).on("blur.mask",
                        function () {
                            k === a.val() || a.data("changed") || a.triggerHandler("change");
                            a.data("changed", !1)
                        }).on("keydown.mask, blur.mask", function () {
                    k = a.val()
                }).on("focus.mask", function (a) {
                    !0 === e.selectOnFocus && b(a.target).select()
                }).on("focusout.mask", function () {
                    e.clearIfNotMatch && !l.test(c.val()) && c.val("")
                })
            }, getRegexMask: function () {
                for (var a = [], b, c, f, e, h = 0; h < d.length; h++)
                    (b = g.translation[d.charAt(h)]) ? (c = b.pattern.toString().replace(/.{1}$|^.{1}/g, ""), f = b.optional, (b = b.recursive) ? (a.push(d.charAt(h)), e = {digit: d.charAt(h),
                        pattern: c}) : a.push(f || b ? c + "?" : c)) : a.push(d.charAt(h).replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&"));
                a = a.join("");
                e && (a = a.replace(RegExp("(" + e.digit + "(.*" + e.digit + ")?)"), "($1)?").replace(RegExp(e.digit, "g"), e.pattern));
                return RegExp(a)
            }, destroyEvents: function () {
                a.off("keydown keyup paste drop blur focusout ".split(" ").join(".mask "))
            }, val: function (b) {
                var c = a.is("input") ? "val" : "text";
                if (0 < arguments.length) {
                    if (a[c]() !== b)
                        a[c](b);
                    c = a
                } else
                    c = a[c]();
                return c
            }, getMCharsBeforeCount: function (a, b) {
                for (var c = 0,
                        f = 0, e = d.length; f < e && f < a; f++)
                    g.translation[d.charAt(f)] || (a = b ? a + 1 : a, c++);
                return c
            }, caretPos: function (a, b, e, f) {
                return g.translation[d.charAt(Math.min(a - 1, d.length - 1))] ? Math.min(a + e - b - f, e) : c.caretPos(a + 1, b, e, f)
            }, behaviour: function (a) {
                a = a || window.event;
                c.invalid = [];
                var e = a.keyCode || a.which;
                if (-1 === b.inArray(e, g.byPassKeys)) {
                    var d = c.getCaret(), f = c.val().length, n = d < f, h = c.getMasked(), k = h.length, m = c.getMCharsBeforeCount(k - 1) - c.getMCharsBeforeCount(f - 1);
                    c.val(h);
                    !n || 65 === e && a.ctrlKey || (8 !== e && 46 !== e && (d = c.caretPos(d,
                            f, k, m)), c.setCaret(d));
                    return c.callbacks(a)
                }
            }, getMasked: function (a) {
                var b = [], k = c.val(), f = 0, n = d.length, h = 0, l = k.length, m = 1, p = "push", t = -1, s, w;
                e.reverse ? (p = "unshift", m = -1, s = 0, f = n - 1, h = l - 1, w = function () {
                    return-1 < f && -1 < h
                }) : (s = n - 1, w = function () {
                    return f < n && h < l
                });
                for (; w(); ) {
                    var x = d.charAt(f), u = k.charAt(h), r = g.translation[x];
                    if (r)
                        u.match(r.pattern) ? (b[p](u), r.recursive && (-1 === t ? t = f : f === s && (f = t - m), s === t && (f -= m)), f += m) : r.optional ? (f += m, h -= m) : r.fallback ? (b[p](r.fallback), f += m, h -= m) : c.invalid.push({p: h, v: u, e: r.pattern}),
                                h += m;
                    else {
                        if (!a)
                            b[p](x);
                        u === x && (h += m);
                        f += m
                    }
                }
                a = d.charAt(s);
                n !== l + 1 || g.translation[a] || b.push(a);
                return b.join("")
            }, callbacks: function (b) {
                var g = c.val(), l = g !== k, f = [g, b, a, e], n = function (a, b, c) {
                    "function" === typeof e[a] && b && e[a].apply(this, c)
                };
                n("onChange", !0 === l, f);
                n("onKeyPress", !0 === l, f);
                n("onComplete", g.length === d.length, f);
                n("onInvalid", 0 < c.invalid.length, [g, b, a, c.invalid, e])
            }};
        g.mask = d;
        g.options = e;
        g.remove = function () {
            var b = c.getCaret();
            c.destroyEvents();
            c.val(g.getCleanVal());
            c.setCaret(b - c.getMCharsBeforeCount(b));
            return a
        };
        g.getCleanVal = function () {
            return c.getMasked(!0)
        };
        g.init = function (d) {
            d = d || !1;
            e = e || {};
            g.byPassKeys = b.jMaskGlobals.byPassKeys;
            g.translation = b.jMaskGlobals.translation;
            g.translation = b.extend({}, g.translation, e.translation);
            g = b.extend(!0, {}, g, e);
            l = c.getRegexMask();
            !1 === d ? (e.placeholder && a.attr("placeholder", e.placeholder), a.attr("autocomplete", "off"), c.destroyEvents(), c.events(), d = c.getCaret(), c.val(c.getMasked()), c.setCaret(d + c.getMCharsBeforeCount(d, !0))) : (c.events(), c.val(c.getMasked()))
        };
        g.init(!a.is("input"))
    };
    b.maskWatchers = {};
    var A = function () {
        var a = b(this), d = {}, e = a.attr("data-mask");
        a.attr("data-mask-reverse") && (d.reverse = !0);
        a.attr("data-mask-clearifnotmatch") && (d.clearIfNotMatch = !0);
        "true" === a.attr("data-mask-selectonfocus") && (d.selectOnFocus = !0);
        if (z(a, e, d))
            return a.data("mask", new y(this, e, d))
    }, z = function (a, d, e) {
        e = e || {};
        var g = b(a).data("mask"), k = JSON.stringify;
        a = b(a).val() || b(a).text();
        try {
            return"function" === typeof d && (d = d(a)), "object" !== typeof g || k(g.options) !== k(e) || g.mask !==
                    d
        } catch (l) {
        }
    };
    b.fn.mask = function (a, d) {
        d = d || {};
        var e = this.selector, g = b.jMaskGlobals, k = b.jMaskGlobals.watchInterval, l = function () {
            if (z(this, a, d))
                return b(this).data("mask", new y(this, a, d))
        };
        b(this).each(l);
        e && ("" !== e && g.watchInputs) && (clearInterval(b.maskWatchers[e]), b.maskWatchers[e] = setInterval(function () {
            b(document).find(e).each(l)
        }, k));
        return this
    };
    b.fn.unmask = function () {
        clearInterval(b.maskWatchers[this.selector]);
        delete b.maskWatchers[this.selector];
        return this.each(function () {
            var a = b(this).data("mask");
            a && a.remove().removeData("mask")
        })
    };
    b.fn.cleanVal = function () {
        return this.data("mask").getCleanVal()
    };
    b.applyDataMask = function (a) {
        a = a || b.jMaskGlobals.maskElements;
        (a instanceof b ? a : b(a)).filter(b.jMaskGlobals.dataMaskAttr).each(A)
    };
    var p = {maskElements: "input,td,span,div", dataMaskAttr: "*[data-mask]", dataMask: !0, watchInterval: 300, watchInputs: !0, watchDataMask: !1, byPassKeys: [9, 16, 17, 18, 36, 37, 38, 39, 40, 91], translation: {0: {pattern: /\d/}, 9: {pattern: /\d/, optional: !0}, "#": {pattern: /\d/, recursive: !0}, A: {pattern: /[a-zA-Z0-9]/},
            S: {pattern: /[a-zA-Z]/}}};
    b.jMaskGlobals = b.jMaskGlobals || {};
    p = b.jMaskGlobals = b.extend(!0, {}, p, b.jMaskGlobals);
    p.dataMask && b.applyDataMask();
    setInterval(function () {
        b.jMaskGlobals.watchDataMask && b.applyDataMask()
    }, p.watchInterval)
});
(function (a) {
    (jQuery.browser = jQuery.browser || {}).mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))
})(navigator.userAgent || navigator.vendor || window.opera);
/*
 Created on : Jan 7, 2015, 9:24:34 AM
 Author: Tran Trong Thang
 Email: trantrongthang1207@gmai.com
 */
 
jQuery(document).ready(function ($) {


    jQuery('.fgcfreequote .fgcfreeimg img').click(function () {

    });

    jQuery('.fgcfreesalesforce .fgctitleform span').click(function () {
        jQuery('.overlay_fgc').hide();
    });

    jQuery('.fgcfreevisualform .fgctitleform span').click(function () {
        jQuery('.overlay_fgc').hide();
    });
    jQuery('.fgcfreesalesforce #webform_5 #field_54').parent().addClass('parent-item-fgc');

    /*submit enquiry form*/
    jQuery('#webform_9_form .actions .button,#webform_5_form .buttons-set .button,#webform_3_form .buttons-set .button ,#webform_6_form .buttons-set .button,#webform_7_form .buttons-set .button,#webform_8_form .buttons-set .button,#webform_12_form .buttons-set .button,#webform_13_form .buttons-set .button,#webform_14_form .buttons-set .button').click(function () {

        var txt = jQuery(this).parent().parent().find('textarea').val();
        var txt1 = jQuery(this).parent().parent().find('textarea.webforms-fields-hint').length;
        if ((txt != "") && (txt1)) {
            txt = ""; 
        }
    });

    var $widthjs = parseInt(window.innerWidth);
    var $heightjs = parseInt(window.innerHeight);
    $(".fgcfreevisual img").click(function () {
        var CampaignStatus = $('#CampaignStatus').val();
        if (jQuery.browser.mobile || CampaignStatus!='') {
            var fgctopMeasure = $('.fgcfreevisualform').outerHeight(), /*height content popup*/
                    fgcleftMeasure = $('.fgcfreevisualform').outerWidth(), /*height content popup*/
                    fgcwindow = $(window).height(), /*height of viewport*/
                    fgcwindow_w = $(window).width(), /*height of viewport*/
                    fgcpositionpopup = (fgcwindow - fgctopMeasure) / 2,
                    fgcpositionpopup_left = (fgcwindow_w - fgcleftMeasure) / 2,
                    fgcviewport = $('.product-view').width(),
                    fgcview_lef_mobile = (fgcviewport - fgcleftMeasure) / 2;
            if (fgcpositionpopup < 0) {
                $('.fgcfreevisualform').css('top', 0);
            } else {
                $('.fgcfreevisualform').css('top', fgcpositionpopup + 'px');
            }
            if ($widthjs >= 768) {
                $('.fgcfreevisualform').css('left', fgcpositionpopup_left + 'px');
            } else {
                //for mobile
                //  $('.fgcfreevisualform').css('left', 0);
                $('.fgcfreevisualform').css('left', fgcview_lef_mobile + 'px');

            }
            jQuery('.overlay_fgc').show();
        } else {
            if (jQuery('body').find('button').is('.create-sample-btn.design-product')) {
                $('button.create-sample-btn.design-product').click();
            } else {
                var fgctopMeasure = $('.fgcfreevisualform').outerHeight(), /*height content popup*/
                        fgcleftMeasure = $('.fgcfreevisualform').outerWidth(), /*height content popup*/
                        fgcwindow = $(window).height(), /*height of viewport*/
                        fgcwindow_w = $(window).width(), /*height of viewport*/
                        fgcpositionpopup = (fgcwindow - fgctopMeasure) / 2,
                        fgcpositionpopup_left = (fgcwindow_w - fgcleftMeasure) / 2,
                        fgcviewport = $('.product-view').width(),
                        fgcview_lef_mobile = (fgcviewport - fgcleftMeasure) / 2;
                if (fgcpositionpopup < 0) {
                    $('.fgcfreevisualform').css('top', 0);
                } else {
                    $('.fgcfreevisualform').css('top', fgcpositionpopup + 'px');
                }
                if ($widthjs >= 768) {
                    $('.fgcfreevisualform').css('left', fgcpositionpopup_left + 'px');
                } else {
                    //for mobile
                    //  $('.fgcfreevisualform').css('left', 0);
                    $('.fgcfreevisualform').css('left', fgcview_lef_mobile + 'px');

                }
                jQuery('.overlay_fgc').show();
            }
        }
    });

    jQuery('.fgcfreevisual .fgctitleform span').click(function () {
        jQuery('.overlay_fgc').hide();
    })
    $(".fgctitleform span").click(function () {
        $(".fgcfreevisualform").css({
            top: -10000 + "px",
            left: -10000 + "px"
        });
    });
    $(".fgcfreevisualform h2.legend").text("Help us to stop spammers:");
    $(".tvcontactus h2.legend").text("Help us to stop spammers:");
    $("#webform_8 h2.legend").text("Help us to stop spammers:");



    if ($widthjs >= 768) {
        $(".fgcfreequote img").click(function () {
            $('.overlay_fgc').show();

            var fgctopMeasure = $('.fgcfreesalesforce').outerHeight(), /*height content popup*/
                    fgcleftMeasure = $('.fgcfreesalesforce').outerWidth(), /*height content popup*/
                    fgcwindow = $(window).height(), /*height of viewport*/
                    fgcwindow_w = $(window).width(), /*height of viewport*/
                    fgcpositionpopup = (fgcwindow - fgctopMeasure) / 2,
                    fgcpositionpopup_left = (fgcwindow_w - fgcleftMeasure) / 2;
            if (fgcpositionpopup < 0) {
                $('.fgcfreesalesforce').css('top', 0);
            } else {
                $('.fgcfreesalesforce').css('top', fgcpositionpopup + 'px');
            }

            $('.fgcfreesalesforce').css('left', fgcpositionpopup_left + 'px');

        });

    } else {
        $(".fgcfreequote img").click(function () {

            var scrollToElement = $("#sidebar-mobile");
            $('.overlay_fgc').addClass('overlay-visible');
            $(window).scrollTop(scrollToElement.offset().top);
        });
    }

    $(".fgcfreesalesforce .fgctitleform span").click(function () {
        $(".fgcfreesalesforce").css({
            top: -10000 + "px",
            left: -10000 + "px"
        });
    });
    $(".fgcfreesalesforce h2.legend").text("Help us to stop spammers:");
    $(".tvinternal_phone").mask('000000000000', {
        translation: {
            '0': {
                pattern: /[\ \-\0-9]/, optional: true
            }
        }
    });

})