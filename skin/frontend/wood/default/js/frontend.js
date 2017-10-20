var ICO = ICO || {};
(function ($) {
    var w = $(window).width();
    var h = $(window).height();
    var windowResize_t;
    var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
    ICO.header = {
        init: function(){
            ICO.header.logoRetinaInit();
            if ($.fn.mCustomScrollbar) {
                ICO.header.scrollBarInit();
            }
            if (frontendData.enableSticky && $.fn.sticky) {
                ICO.header.stickyInit();
            }
            ICO.header.mobileMenuInit();
        },
        logoRetinaInit: function() {
            var $logoImg = $('.header-wrapper .header-container .logo img.x1');
            if (pixelRatio > 1) {
                if($('.cms-index-index').length>0){
                    $logoImg.attr('src', $logoImg.attr('src').replace(frontendData.logoHome, frontendData.logoHomeRetina));
                }else{
                    $logoImg.attr('src', $logoImg.attr('src').replace(frontendData.logo, frontendData.logoRetina));
                }
            }
        },
        scrollBarInit: function(){
            $(".header-maincart .cart-content").mCustomScrollbar({
                scrollInertia:150,
                scrollButtons:{
                    enable:true
                }
            });
        },
        stickyInit: function(){
            $("#main-header").sticky({ topSpacing: 0 });
            $("#mobile-sticky").sticky({ topSpacing: 0 });
        },
        mobileMenuInit: function() {
            $(".nav-mobile-accordion, #categories_nav").mobileMenu({
                accordion: true,
                speed: 400,
                closedSign: 'collapse',
                openedSign: 'expand',
                mouseType: 0,
                easing: 'easeInOutQuad'
            });
        }
    };
    ICO.page = {
        init: function() {
            $.browserSelector();
            if ($("html").hasClass("chrome")) {
                $.smoothScroll();
            }
            if ($("html").hasClass("safari")) {
                $.smoothScroll();
            }
            if (pixelRatio > 1) {
                ICO.page.imageRetinaInit();
                ICO.page.disableLinkMobile();
            }
            if (frontendData.confGridEqualHeight) {
                ICO.page.setGridEqualHeight();
            }
            ICO.page.fullWidthSlideInit(h);
            ICO.page.setGridProductItem();
            ICO.page.lazyLoadInit();
            ICO.page.tabActionInit();
            ICO.page.inputBoxInit();
            ICO.page.fixLabelInit();
            ICO.page.formSelectInit();
            ICO.page.videoInit();
        },
        fullWidthSlideInit: function(h){
            $('#block-slide-home').find('.slide-inner-content').css('height', h);
        },
        imageRetinaInit: function(){
            $('img[data-srcX2]').each(function () {
                if ($(this).hasClass('lazy') || $(this).hasClass('lazyOwl')) {
                    return;
                } else {
                    $(this).attr('src', $(this).attr('data-srcX2'));
                }
            });
        },
        disableLinkMobile: function () {
            $('.products-grid').find('a.product-image').click(function(e){
                e.preventDefault();
                return false;
            });
        },
        setGridEqualHeight: function(){
            var nw = $(window).width();
            var SPACING = 20;
            if (nw >= 480) {
                $('.show-grid').removeClass("auto-height");
                var gridItemMaxHeight = 0;
                $('.show-grid > .item').each(function () {
                    $(this).css("height", "auto");
                    if (frontendData.displayAddtocart == 2 || frontendData.displayAddtolink == 2) {
                        var actionsHeight = $(this).find('.actions').height();
                        $(this).css("padding-bottom", (actionsHeight + SPACING) + "px");
                    }
                    gridItemMaxHeight = Math.max(gridItemMaxHeight, $(this).height());
                });
                $('.show-grid > .item').css("height", gridItemMaxHeight + "px");
            } else {
                $('.show-grid').addClass("auto-height");
                $('.show-grid > .item').css("height", "auto");
                $('.show-grid > .item').css("padding-bottom", "20px");
            }
        },
        setGridProductItem: function(){
            var ww = $(window).width();
            var col = frontendData.colFull;
            if (ww > 768) {
                newcol = col;
            }
            if (ww <= 768 && ww > 640) {
                newcol = frontendData.col768_640;
            }
            if (ww > 480 && ww <= 640) {
                newcol = frontendData.col480_640;
            }
            if (ww <= 480) {
                newcol = frontendData.col480;
            }
            for (var i = 1; i < 8; i++) {
                $('.catalog-category-view .category-products .itemgrid-adaptive').removeClass('products-itemgrid-'+i+'col');
            }
            $('.catalog-category-view .category-products .itemgrid-adaptive').addClass('products-itemgrid-'+newcol+'col');
            $i = 0;
            $('.show-grid > .item').each(function () {
                $i++;
                $(this).removeClass('first');
                $(this).removeClass('last');
                if (($i - 1) % newcol == 0) {
                    $(this).addClass('first');
                } else if ($i % newcol == 0) {
                    $(this).addClass('last');
                }
            });
        },
        lazyLoadInit: function() {
            $("img.lazy").lazy({
                effect: "fadeIn",
                effectTime: 800,
                threshold: 50,
                afterLoad: function (element) {
                    if (frontendData.confGridEqualHeight) {
                        ICO.page.setGridEqualHeight();
                    }
                }
            });
        },
        tabActionInit:function() {
            $('#product-tab a[href="#product_tabs_tabreviews"]').click(function (e) {
                $(this).tab('show');
            })
            $('div.product-view p.no-rating a, div.product-view .rating-links a').click(function () {
                $('#product-tab a[href="#product_tabs_tabreviews"]').trigger('click');
                $('#product-tab').scrollToMe();
                return false;
            });
        },
        videoInit:function(){
            $(".container-main-video .upb_video-wrapper .upb_video-bg em").click(function () {
                if($(this).hasClass("fa-play")){
                    $(".container-main-video .upb_video-wrapper .upb_video-bg video").get(0).play();
                }
                else
                    $(".container-main-video .upb_video-wrapper .upb_video-bg video").get(0).pause();
                $(this).toggleClass("fa-pause").toggleClass("fa-play");
                return false;
            });
        },
        formSelectInit:function(){
            var selectEl, selectVal;
            if ($.fn.chosen) {
                $("select").each(function() {
                    if ($(this).hasClass('no-display')) return;
                    $(this).chosen({
                        disable_search_threshold: 10,
                        width: '100%'
                    });
                    $(this).on('change', function() {
                        $(this).siblings('.validation-advice').hide(300);
                    });
                    if ($(this).hasClass('super-attribute-select') || this.id == 'limits' || this.id == 'orders' || $(this).hasClass('simulate-change')) {
                        $(this).on('change keyup', function(event) {
                            if (selectEl == $(this)[0] && selectVal == $(this).val()) return;
                            selectEl = $(this)[0];
                            selectVal = $(this).val();
                            setTimeout(function() {
                                if (selectEl === event.target) {
                                    selectEl.simulate('change');
                                }
                                $("select").each(function() {
                                    $(this).trigger("chosen:updated");
                                });
                            }, 0);
                        });
                    }
                });
            };
            $('.input-box').has('select').addClass('input-box-select');
            $('.input-box').has('select').parent().addClass('select-list');
            $('.chosen-container .chosen-results').on('touchend', function(event) {
                event.stopPropagation();
                event.preventDefault();
                return;
            });
        },
        fixLabelInit:function(){
            ICO.page.labelTextare();
            ICO.page.labelCheckbox();
            $(document).on('new:ajaxform', function() {
                ICO.page.labelTextare();
                ICO.page.labelCheckbox();
                ICO.page.formSelectInit();
            });
        },
        labelTextare:function(){
            $('.input-box').each(function() {
                $(this).has('textarea').addClass('textarea');
                $(this).has('textarea').siblings('label').addClass('textarea');
            });
        },
        labelCheckbox:function(){
            $('.input-box').each(function() {
                if ($(this).children("input[type='checkbox']").length > 0) {
                    $(this).addClass('checkbox');
                    $(this).siblings('label').addClass('checkbox');
                }
            });
        },
        inputFocus: function(el) {
            if ($(el).is(":focus")) $(el).parents('.input-box').addClass('focus');
            if ($(el).val().length > 0) {
                $(el).parents('.input-box').siblings('label').hide();
            }
            $(el).on('change keyup', function() {
                $(this).siblings('.validation-advice').hide(300);
            });
        },
        inputBlur: function(el) {
            $(el).parents('.input-box').removeClass('focus');
            if ($(el).val().length == 0) {
                $(el).removeClass('label-animated');
                $(el).parents('.input-box').siblings('label').show();
            }
        },
        bindInputboxes: function() {
            ICO.page.inputBoxInit();
        },
        inputBoxInit: function() {
            $('.input-text').each(function() {
                ICO.page.inputFocus(this);
            });
            $('body').on('focus keyup change input', '.input-text', function() {
                ICO.page.inputFocus(this);
            });
            $(document).on('new:ajaxform', function() {
                $('.input-text').each(function() {
                    ICO.page.inputFocus(this);
                });
                $("select").each(function() {
                    $(this).trigger("chosen:updated");
                });
            });
            $('body').on('focusout', '.input-text', function() {
                ICO.page.inputBlur(this);
            });
            $('body').on('focus keyup change input', 'textarea', function() {
                ICO.page.inputFocus(this);
            });
            $('body').on('focusout', 'textarea', function() {
                ICO.page.inputBlur(this);
            });
        }
    };

    ICO.footer = {
        init: function() {
            ICO.footer.backToTopInit();
            ICO.footer.mobileAccordionInit();
        },
        backToTopInit: function() {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('#back-top').fadeIn();
                } else {
                    $('#back-top').fadeOut();
                }
            });
            $('#back-top a').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });
        },
        mobileAccordionInit: function() {
            $('.mobile-button').addClass("active");
            $('.mobile-button').click(function(){
                if($(this).parents('.footer-block-title, .block-title').next().is(":visible")){
                    $(this).addClass("active");
                }else{
                    $(this).removeClass("active");
                }
                $(this).parents('.footer-block-title, .block-title').next().toggle(400);
            });
        }
    };
    ICO.onReady = {
        init: function() {
            ICO.header.init();
            ICO.page.init();
            ICO.footer.init();
        }
    };
    ICO.onLoad = {
        init: function() {}
    };
    $(window).resize(function () {
        var nw = $(window).width();
        var nh = $(window).height();
        if (w != nw || h != nh) {
            clearTimeout(windowResize_t);
            windowResize_t = setTimeout(function () {
                if (frontendData.confGridEqualHeight) {
                    ICO.page.setGridEqualHeight();
                }
                ICO.page.setGridProductItem();
                ICO.page.fullWidthSlideInit(nh);
            }, 200);
        }
        w = nw;
        h = nh;
    });
    $(document).ready(function(){
        ICO.onReady.init();
    });
    $(window).load(function(){
        ICO.onLoad.init();
    });
})(jQuery);
(function() {
    var eventMatchers = {
        'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
        'MouseEvents': /^(?:click|mouse(?:down|up|over|move|out))$/
    };
    var defaultOptions = {
        pointerX: 0,
        pointerY: 0,
        button: 0,
        ctrlKey: false,
        altKey: false,
        shiftKey: false,
        metaKey: false,
        bubbles: true,
        cancelable: true
    };
    Event.simulate = function(element, eventName) {
        var options = Object.extend(defaultOptions, arguments[2] || {});
        var oEvent, eventType = null;
        element = $(element);
        for (var name in eventMatchers) {
            if (eventMatchers[name].test(eventName)) {
                eventType = name;
                break;
            }
        }
        if (!eventType)
            throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');
        if (document.createEvent) {
            oEvent = document.createEvent(eventType);
            if (eventType == 'HTMLEvents') {
                oEvent.initEvent(eventName, options.bubbles, options.cancelable);
            } else {
                oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView, options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY, options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
            }
            element.dispatchEvent(oEvent);
        } else {
            options.clientX = options.pointerX;
            options.clientY = options.pointerY;
            oEvent = Object.extend(document.createEventObject(), options);
            element.fireEvent('on' + eventName, oEvent);
        }
        return element;
    };
    Element.addMethods({
        simulate: Event.simulate
    });
})(jQuery);
function mobileSkipLink(e){
    var skipContents = jQuery('.skip-content');
    var skipLinks = jQuery('.skip-link');
    var self = jQuery(e);
    var target = self.attr('data-target-element');
    // Get target element
    var elem = jQuery(target);
    // Check if stub is open
    var isSkipContentOpen = elem.hasClass('skip-active') ? 1 : 0;
    // Hide all stubs
    skipLinks.removeClass('skip-active');
    skipContents.removeClass('skip-active');
    self.removeClass('skip-active');
    // Toggle stubs
    if (isSkipContentOpen) {
        self.removeClass('skip-active');
    } else {
        self.addClass('skip-active');
        elem.addClass('skip-active');
    }
}
! function(t, e) {
    "function" == typeof define && define.amd ? define(function() {
        return e(t)
    }) : "object" == typeof exports ? module.exports = e : t.echo = e(t)
}(this, function(t) {
    "use strict";
    var e, n, o, r, c, i = {},
        l = function() {},
        a = function(t, e) {
            var n = t.getBoundingClientRect();
            return n.right >= e.l && n.bottom >= e.t && n.left <= e.r && n.top <= e.b
        },
        d = function() {
            (r || !n) && (clearTimeout(n), n = setTimeout(function() {
                i.render(), n = null
            }, o))
        };
    return i.init = function(n) {
        n = n || {};
        var a = n.offset || 0,
            u = n.offsetVertical || a,
            f = n.offsetHorizontal || a,
            s = function(t, e) {
                return parseInt(t || e, 10)
            };
        e = {
            t: s(n.offsetTop, u),
            b: s(n.offsetBottom, u),
            l: s(n.offsetLeft, f),
            r: s(n.offsetRight, f)
        }, o = s(n.throttle, 250), r = n.debounce !== !1, c = !!n.unload, l = n.callback || l, i.render(), document.addEventListener ? (t.addEventListener("scroll", d, !1), t.addEventListener("load", d, !1)) : (t.attachEvent("onscroll", d), t.attachEvent("onload", d))
    }, i.render = function() {
        for (var n, o, r = document.querySelectorAll("img[data-echo]"), d = r.length, u = {
            l: 0 - e.l,
            t: 0 - e.t,
            b: (t.innerHeight || document.documentElement.clientHeight) + e.b,
            r: (t.innerWidth || document.documentElement.clientWidth) + e.r
        }, f = 0; d > f; f++) o = r[f], a(o, u) ? (c && o.setAttribute("data-echo-placeholder", o.src), o.src = o.getAttribute("data-echo"), c || o.removeAttribute("data-echo"), l(o, "load")) : c && (n = o.getAttribute("data-echo-placeholder")) && (o.src = n, o.removeAttribute("data-echo-placeholder"), l(o, "unload"));
        d || i.detach()
    }, i.detach = function() {
        document.removeEventListener ? t.removeEventListener("scroll", d) : t.detachEvent("onscroll", d), clearTimeout(n)
    }, i
});