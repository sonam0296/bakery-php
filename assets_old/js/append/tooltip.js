(function(factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports !== 'undefined') {
        module.exports = factory(require('jquery'));
    } else {
        factory(jQuery);
    }

}(function($) {
    'use strict';
    var ntgTooltip = window.ntgTooltip || {};
    var TooltipEls = [];
    var TooltipItemsEls = [];

    ntgTooltip = (function() {

        function ntgTooltip(element, settings) {

            var _ = this, dataSettings;

            _.defaults = {
                elements : {
                    appender: 'self', // parent || self || body || elemento js // Elemento onde a tooltip será inserida
                    trigger : element, // trigger object
                    container : "", // tooltip object
                },
                position: 'top', // top || left || right || bottom
                triggerOpen: 'mouseenter touchstart', // trigger para abrir a tooltip. mouseenter touchstart || click
                triggerClose: 'mouseleave touchleave', // trigger para abrir a fechar. mouseleave touchleave || click
                animation: 'fade', // 'fade' || 'grow' || 'fall'
                animationSpeed: 350, // velocidade da animacao;
                openDelay: 150,  // delay para abrir
                closeDelay: 80,  // delay para fechar
                styles: {
                    maxWidth:"400px", //Width minimo
                    minWidth:"150px", //Width minimo
                    marginWindow: 20, //margem da extremidade da janela;
                },
            };

            _.initials = {
                appender: '',
                position: '',
                animation: '',
                previousPosition: '',
                timer: '',
                shouldUpdate: 1,
            };
            
            if(!$(element).data('tooltip')){
                dataSettings = $(element).data('tooltip', _) || {};
                $.extend(_, _.initials);
                _.options = $.extend({}, _.defaults, dataSettings, settings);
                            
                _.init();
            }
            _.init = $.proxy(_.init, _);
            _.openTooltip = $.proxy(_.openTooltip, _);
            _.closeTooltip = $.proxy(_.closeTooltip, _);

        }
        return ntgTooltip;

    }());
    
    ntgTooltip.prototype.makeId = function(limit, sufix, parent) {
        var _ = this;

        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < limit; i++){
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        var final_str = text+"-"+sufix;

        var arrayCompare;

        if(parent==1){
            arrayCompare = TooltipEls;
        }else{
            arrayCompare = TooltipItemsEls;
        }


        if(typeof arrayCompare == "undefined" || arrayCompare.indexOf(final_str)<0){
            return final_str;
        }else{
            return _.makeId(limit, sufix, parent);
        }
    }
   
    ntgTooltip.prototype.init = function() {
        var _ = this;

        if($(_.options.elements.trigger).length>0 && $(_.options.elements.trigger).attr('tooltip-content')){
            _.options.elements.container = $( "<div tooltip></div>");
            $(_.options.elements.container).html("<p>"+$(_.options.elements.trigger).attr('tooltip-content')+"</p>");
            $(_.options.elements.trigger).html('<a href="javascript:;">'+$(_.options.elements.trigger).html()+"</a>");

            var id_trigger = $(_.options.elements.trigger)[0].id || _.makeId(6, "trigger-tooltip", 1);
            var id_tooltip = $(_.options.elements.container)[0].id || _.makeId(6, "trigger-tooltip", 1);
            TooltipEls.push(id_tooltip);

            $(_.options.elements.trigger).attr({
                'aria-controls': $(_.options.elements.container).attr('id'),
                'id': id_trigger,
                'tabindex': 0
            });
            $(_.options.elements.container).attr({
                'role': 'tooltip',
                'id': id_tooltip,
                'aria-hidden': true,
            });

            
            //adiciona a tooltip ao body
            _.initials.appender = _.options.elements.appender;
            if(typeof $(_.options.elements.trigger).attr('tooltip-parent') != "undefined"){
                _.initials.appender = $(_.options.elements.trigger).attr('tooltip-parent');
            }

            if(_.initials.appender=="parent"){
                $(_.options.elements.container).attr('tooltip-absolute', '');
                $(_.options.elements.trigger).parent().css('position', 'relative');
                _.initials.appender = $(_.options.elements.trigger).parent();
            }
            if(_.initials.appender=="self"){
                $(_.options.elements.container).attr('tooltip-absolute-self', '');
                _.initials.appender = $(_.options.elements.trigger);
            }

            _.initials.position = _.options.elements.position;
            if(typeof $(_.options.elements.trigger).attr('tooltip-position') != "undefined"){
                _.initials.position = $(_.options.elements.trigger).attr('tooltip-position');
            }

            _.initials.animation = _.options.animation;
            if(typeof $(_.options.elements.trigger).attr('tooltip-animation') != "undefined"){
                _.initials.animation = $(_.options.elements.trigger).attr('tooltip-animation');
            }

            $(_.options.elements.container).attr('tooltip-position', _.initials.position);
            $(_.options.elements.container).attr('tooltip-animation', _.initials.animation);
            
            if(_.options.animationSpeed!=350){
                $(_.options.elements.container).css({
                    '-moz-animation-duration': self.__options.animationDuration[0] + 'ms',
                    '-ms-animation-duration': self.__options.animationDuration[0] + 'ms',
                    '-o-animation-duration': self.__options.animationDuration[0] + 'ms',
                    '-webkit-animation-duration': self.__options.animationDuration[0] + 'ms',
                    'animation-duration': self.__options.animationDuration[0] + 'ms',
                    'transition-duration': self.__options.animationDuration[0] + 'ms'
                });
            }

            $(_.options.elements.container).appendTo($(_.initials.appender));

            $(_.options.elements.trigger).find('a').on(_.options.triggerOpen, function(e) {
                if(typeof $(_.options.elements.container).attr('tooltip-absolute') != "undefined"){
                    _.openTooltip();
                }else{
                    _.checkPosition(1, 0);
                }
            });
            $(_.options.elements.trigger).find('a').on(_.options.triggerClose, function(e) {
                _.closeTooltip();
            });

            $('body').on('click',function(e) {
                if($(e.target).closest(_.options.elements.container).length == 0 && $(e.target).closest(_.options.elements.trigger).length == 0){
                    if($(_.options.elements.container).attr('tooltip-visible')) _.closeTooltip();
                }
            });

            if(typeof $(_.options.elements.container).attr('tooltip-absolute') == "undefined"){
                $(window).resized(function(){
                    _.checkPosition(0, 0);
                });
            }
        }
    };


    ntgTooltip.prototype.openTooltip = function() {
        var _ = this; 

        $.doTimeout(_.options.openDelay, function() {
            $(_.options.elements.container).attr({
                'tooltip-visible': '',
                'aria-hidden': false,
                'tabindex': -1
            });
        });
    }

    ntgTooltip.prototype.closeTooltip = function() {
        var _ = this; 

        if(_.initials.timer) clearTimeout(_.initials.timer);
        $.doTimeout(_.options.closeDelay, function() {
            $(_.options.elements.container).removeAttr('tooltip-visible');
            $(_.options.elements.container).attr({
                'aria-hidden': true,
                'tabindex': 0
            });
        });
    }

    ntgTooltip.prototype.handlePositionX = function(width, position) {
        var _ = this; 

        if(position!=0){
            _.initials.previousPosition = _.initials.position;
            $(_.options.elements.container).attr('tooltip-position', position);
        }
        
        width = width - _.options.styles.marginWindow - 20; //20 da margin da tooltip
        $(_.options.elements.container).css({
            'width': width+'px',
            'margin': (width/2)+'px',
        });

        _.initials.shouldUpdate = 0;
    }
    ntgTooltip.prototype.handlePositionY = function(shouldReplace) {
        var _ = this; 

        var windowW = $(window).innerWidth();   
        var width = windowW - $(_.options.elements.trigger).offset().left - _.options.styles.marginWindow;

        if(shouldReplace==1){
            _.initials.previousPosition = _.initials.position;
            _.initials.position = "top";
            $(_.options.elements.container).attr('tooltip-position', 'top');
        }

        $(_.options.elements.container).css({
            'width': width+'px',
            'left':  '0px',
            'margin-left': _.options.styles.marginWindow+'px',
        });

        _.initials.shouldUpdate = 0;
    }
    ntgTooltip.prototype.checkPosition = function(willOpen, reInit) {
        var _ = this; 

        if(reInit==0){
            if(_.initials.previousPosition!=""){
                _.initials.position = _.initials.previousPosition;
                $(_.options.elements.container).attr('tooltip-position', _.initials.position);
                _.initials.previousPosition = '';
            }

            $(_.options.elements.container).removeAttr('style');
            
            if($(_.options.elements.container).find('p').width() < $(_.options.elements.container).width()){
                var elWidth = $(_.options.elements.container).find('p').width() + (parseInt($(_.options.elements.container).css('padding-left'))*2) + 5;

                $(_.options.elements.container).css({
                    'max-width': _.options.styles.maxWidth,
                    'min-width': elWidth+'px',
                });
            }else{
                var elWidth = parseInt(_.options.styles.maxWidth)+100;

                $(_.options.elements.container).css({
                    'max-width': _.options.styles.maxWidth,
                    'width': elWidth+'px',
                });
            }            
        }

        var windowW = $(window).innerWidth();   
        var isOffscreen = _.checkOffscreen();      

        _.initials.timer = setTimeout(function(){
            if(isOffscreen){
                if(_.initials.position=="top" || _.initials.position=="bottom"){
                    // _.handlePositionY(0);

                    var overSide = "left";
                    if($(_.options.elements.container).offset().left + $(_.options.elements.container).width() > $(window).width()){
                        overSide = "right";
                    }

                    if(overSide == "left"){
                        _.initials.previousPosition = _.initials.position;
                        _.initials.position = "right";
                        $(_.options.elements.container).attr('tooltip-position', 'right');
                    }else{
                        _.initials.previousPosition = _.initials.position;
                        _.initials.position = "left";
                        $(_.options.elements.container).attr('tooltip-position', 'left');
                    }

                }else if(_.initials.position=="left" || _.initials.position=="right"){
                    var widthLeft = $(_.options.elements.trigger).offset().left;
                    var widthRight = windowW - ($(_.options.elements.trigger).offset().left + $(_.options.elements.trigger).width());

                    var width1 = widthLeft;
                    var width2 = widthRight;
                    var pos = "right";

                    if(_.initials.position=="right"){
                        width1 = widthRight;
                        width2 = widthLeft;
                        pos = "left";
                    }
                    
                    var width = width1;
                    if(width<parseInt(_.options.styles.minWidth) && width2>width1){
                        width = width2;
                        _.handlePositionX(width, pos);
                    }else if(width<parseInt(_.options.styles.minWidth) && width2<width1){
                        _.handlePositionY(1);
                    }else{
                        _.handlePositionX(width, 0);
                    }
                }                            
            }else{                
                _.initials.shouldUpdate = 1;
            }
            

            //update margin
            if(_.initials.position=="top" || _.initials.position=="bottom"){
                var marg = "-"+ (($(_.options.elements.container).innerWidth() / 2) - ($(_.options.elements.trigger).innerWidth() / 2));
                $(_.options.elements.container).css('margin-left', marg+'px');
            }
            if(_.initials.position=="left" || _.initials.position=="right"){
                var marg = "-"+ ($(_.options.elements.container).innerHeight() / 2);
                $(_.options.elements.container).css('margin-top', marg+'px');
            }

            var isOffscreen2 = _.checkOffscreen();
            if(isOffscreen2){
                _.checkPosition(1, 1);
                return;
            }

            if(willOpen==1) _.openTooltip();  
        }, 80);        
    }

    ntgTooltip.prototype.checkOffscreen = function() {
        var _ = this; 
        
        if($(_.options.elements.container).offset().left < 0 || $(_.options.elements.container).offset().left + $(_.options.elements.container).width() > $(window).width()){
            return true;
        }else{
            return false;
        }
    }

    $.fn.ntgtooltip = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].ntgtooltip = new ntgTooltip(_[i], opt);
            else
                ret = _[i].ntgtooltip[opt].apply(_[i].ntgtooltip, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

}));

function initTooltips(element){
    if($('[tooltipster]').length>0){
        $('[tooltipster]').ntgtooltip();
    }
}

/*
<div class="textos"><p>Lorem ipsum dolor sit amet, <span tooltipster tooltip-position="top" tooltip-content="adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam">tooltip top</span> adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis <span tooltipster tooltip-position="left" tooltip-content="adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam">tooltip left</span> leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam. Sed ac nulla ut diam interdum varius in et est. Vestibulum a dui at massa pharetra ultricies in at enim. Vestibulum consectetur efficitur iaculis. Quisque nec <span tooltipster tooltip-position="right" tooltip-content="adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam">tooltip right</span> lorem. Cras elementum magna non dui ornare, sit amet ullamcorper orci rutrum. Donec congue velit ut diam vestibulum porttitor. Pellentesque imperdiet mauris ut ligula lobortis fermentum. Suspendisse at ante sit <span tooltipster tooltip-position="bottom" tooltip-content="adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam">tooltip bottom</span> amet lorem aliquam porta. Donec bibendum purus a <span tooltipster tooltip-position="top" tooltip-animation="grow" tooltip-content="Quisque sodales sodales quam" >tooltip grow</span> suscipit venenatis. Quisque et sapien laoreet, convallis erat et, consectetur nisl. Nullam tristique interdum nisl <span tooltipster tooltip-position="top" tooltip-animation="fall" tooltip-content="Quisque sodales sodales quam" >tooltip fall</span> nec hendrerit.</p></div>
*/