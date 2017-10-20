var Ico = Ico || {};
Ico.widgetConfig = Class.create();
Ico.widgetConfig.prototype = {
    initialize: function (id, config) {
        var wid = jQuery('#'+id);
        this.config = config || {};
        document.observe('dom:loaded', function () {
            this.initTab(wid);
            this.initCountdown(wid);
            if (this.config.carousel && this.config.carousel.enable) {
                this.initCarousel(wid);
            }
        }.bind(this));
    },
    initCountdown: function(wid){
        if (!this.config.countdown) return;
        if (!this.config.countdown.enable) return;
        wid.find('.product-date').each(function(i,item){
            var date = jQuery(item).attr('data-date');
            if(date){
                var config = {date: date};
                Object.extend(config, this.config.countdown);
                Object.extend(config, this.config.countdownConfig);
                if(this.config.countdownTemplate){
                    config.template = this.config.countdownTemplate;
                }
                jQuery(item).countdown(config);
            }
        }.bind(this));
    },
    initCarousel: function(wid){
        if (this.config.carousel && this.config.carousel.enable) {
            wid.find('.owl-carousel').each(function (i,div) {
                jQuery(div).owlCarousel(this.config.carousel);
            }.bind(this));
        }
    },
    initTab: function(wid){
        if (!this.config.requestUrl) return;
        wid.find('.widget-tabs a').each(function(i,tab){
            var tab_content = wid.find(jQuery(tab).attr('href'));
            if (!tab_content) return;
            if (tab_content.find('ul:first').length > 0) {
                tab_content.has_content = true;
            }
            jQuery(tab).on('click', function(e){
                e.preventDefault();
                this.hasTab(wid, tab, tab_content);
                if (tab_content.has_content) return;
                var data = Event.findElement(e, 'a'),
                    type = data.readAttribute('data-type'),
                    value = data.readAttribute('data-value'),
                    limit = data.readAttribute('data-limit'),
                    column = data.readAttribute('data-column'),
                    row = data.readAttribute('data-row'),
                    cid = data.readAttribute('data-cid'),
                    template = data.readAttribute('data-template'),
                    carousel = data.readAttribute('data-carousel');

                new Ajax.Request(this.config.requestUrl, {
                    method: 'post',
                    parameters: {
                        type: type,
                        value: value,
                        limit: limit,
                        carousel: carousel,
                        column: column,
                        cid: cid,
                        row: row,
                        template: template
                    },
                    onSuccess: function (transport) {
                        tab_content.has_content = true;
                        tab_content.append(transport.responseText);
                        this.initCarousel(wid);
                        this.initCountdown(wid);
                        this.initLazyLoad();
                        jQuery('.widget-spinner').hide();
                        tab_content.css({
                            height: 'auto'
                        });
                    }.bind(this)
                });


            }.bind(this));
        }.bind(this));
    },
    hasTab: function(wid, tab, content){
        if (!tab || !content) return;
        wid.find('.widget-tabs .active').removeClass('active');
        jQuery(tab).parent().addClass('active');
        if (!content.has_content) {
            var prev = wid.find('.tab-pane.active');
            if (prev) {
                content.css('height', prev.height());
                prev.removeClass('active');
                var spinner = jQuery('<div/>').addClass('widget-spinner');
                spinner.css({width: '100%', height: '100%'});
                var spinnerin = jQuery('<div/>').addClass('spinner');
                for (i = 1; i <= 3; i++) {
                    spinnerin.append(jQuery('<span/>').attr('id','bounce'+i));
                }
                spinnerin.css({
                    position: 'absolute',
                    top: '50%',
                    left: '50%'
                });
                spinner.append(spinnerin);
                spinner.css({position: 'relative'});
                content.append(spinner);
            }
        }else {
            wid.find('.tab-pane.active').removeClass('active');
        }
        content.addClass('active');
    },
    initLazyLoad: function(){
        var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
        if (pixelRatio > 1) {
            jQuery('img[data-srcX2]').each(function () {
                jQuery(this).attr('src', jQuery(this).attr('data-srcX2'));
            });
        }else{
            jQuery('img[data-src]').each(function () {
                jQuery(this).attr('src', jQuery(this).attr('data-src'));
            });
        }
    }
};