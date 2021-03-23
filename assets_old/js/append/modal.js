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
    var ntgModal = window.ntgModal || {};

    ntgModal = (function() {

        function ntgModal(element, settings) {

            var _ = this, dataSettings, willInit;

            _.defaults = {
            	elements : {
					container : element,
					trigger : '[ntgmodal-open="'+$(element).attr('id')+'"]',
					close : '[ntgmodal-close]',
				},
                transitions : {
                    in: 'fade', //fade, slideDown; slideUp; slideLeft, slideRight, scale
                    out: 'fade', //fade, slideDown; slideUp; slideLeft, slideRight, scale
                    speed: 0.5,
                    easingIn: 'Power1.easeOut',
                    easingOut: 'Power1.easeOut',
                },
                appendElement: '.mainDiv',
				size: 'medium', //small:30%; medium:50%; large:75%; full: 100%
				overlay: true,
				delay: 0, //delay de open e close 
                onOpen: function() { return false; }, //Callback for when the modal is opened.
                onClose: function() { return false; },// Callback for when the modal closes.
            };

            _.initials = {
                size : '',
                transitions : {
                    in:'',
                    out:''
                }
            };
			
            dataSettings = $(element).data('ntgmodal', _) || {};
            $.extend(_, _.initials);
            _.options = $.extend({}, _.defaults, dataSettings, settings);   

            _.init();

            _.init = $.proxy(_.init, _);
            _.openModal = $.proxy(_.openModal, _);
            _.closeModal = $.proxy(_.closeModal, _);

        }
        return ntgModal;

    }());
   	   
    ntgModal.prototype.init = function() {
        var _ = this; 

        
        if($(_.options.elements.container).length>0 && $(_.options.elements.trigger).length>0){
            _.initials = {
                size : $(_.options.elements.container).attr('ntgmodal-size') || _.options.size,
                transitions : {
                    in: $(_.options.elements.container).attr('ntgmodal-animation-in') || _.options.transitions.in,
                    out: $(_.options.elements.container).attr('ntgmodal-animation-out') || _.options.transitions.out,
                }
            };
            
            if(_.options.appendElement && $(_.options.appendElement).length>0){
                $(_.options.appendElement).append(_.options.elements.container);
            }

            if(typeof $(_.options.elements.container).attr('ntgmodal') == "undefined"){
                $(_.options.elements.container).attr('ntgmodal', '');
            }

            if(typeof $(_.options.elements.container).attr('ntgmodal-size') == "undefined"){
                $(_.options.elements.container).attr('ntgmodal-size', _.initials.size);
            }

            if(typeof $(_.options.elements.container).attr('ntgmodal-animation-in') == "undefined"){
                $(_.options.elements.container).attr('ntgmodal-animation-in', _.initials.transitions.in);
            }

            if(typeof $(_.options.elements.container).attr('ntgmodal-animation-out') == "undefined"){
                $(_.options.elements.container).attr('ntgmodal-animation-out', _.initials.transitions.out);
            }

            if($('[ntgmodal-close="'+$(_.options.elements.container).attr('id')+'"]').length>0){
                _.options.elements.close = '[ntgmodal-close="'+$(_.options.elements.container).attr('id')+'"]';
            }else{
                _.options.elements.close = $(_.options.elements.container).find('[ntgmodal-close]');
            }

            $(_.options.elements.trigger).attr({
                'aria-controls': $(_.options.elements.container).attr('id'),
                'aria-haspopup': true,
                'tabindex': 0
            });

            $(_.options.elements.container).attr({
                'role': 'dialog',
                'aria-hidden': true,
            });

            $(_.options.elements.trigger).off("click.openModal");
            $(_.options.elements.trigger).on("click.openModal", function(event) {
                _.openModal();
                $(this).attr('ntgmodal-opened', 'true');
            });

            $(_.options.elements.close).off("click.closeModal");
            $(_.options.elements.close).on("click.closeModal", function(event) {
                _.closeModal();
                $('[ntgmodal-opened]').removeAttr('ntgmodal-opened');
            });

            $('body').on('click',function(e) {
                if($(e.target).closest(_.options.elements.container).length == 0 && $(e.target).closest(_.options.elements.trigger).length == 0){
                    if($(_.options.elements.container).attr('ntgmodal-opened')) _.closeModal();
                }
            });

            _.setInitialStyle();
        }
    };

    ntgModal.prototype.openModal = function() {
        var _ = this; 

        _.closeAllModals();
        setTimeout(function(){
            $('body').addClass('overHidden-modals');
            if(_.options.overlay) $('.mainDiv').addClass('has-overlay header-hidden');

            _.animationOpen();
        }, 600);
    }

    ntgModal.prototype.closeModal = function() {
        var _ = this; 

        _.animationClose();
    }
    ntgModal.prototype.closeAllModals = function() {
        var _ = this; 

        if($('[ntgmodal][ntgmodal-opened]').length>0){
            $('[ntgmodal][ntgmodal-opened]').each(function(){
                $(this).find('[ntgmodal-close]').trigger('click');
            });
        }
    }


    ntgModal.prototype.animationOpen = function() {
        var _ = this; 
        //var target = $('#'+$(_.options.elements.container).attr('id') + ' [ntgmodal-content]');
        var target = $(_.options.elements.container);

        if(_.initials.transitions.in == "fade"){
            TweenMax.to(target, _.options.transitions.speed, {
                autoAlpha: 1,
                ease: _.options.transitions.easingIn,
                onComplete: function(){
                    _.options.onOpen();
                }
            });
        }
        if(_.initials.transitions.in == "slideDown" || _.initials.transitions.in == "slideUp"){
            TweenMax.to(target, _.options.transitions.speed, {
                transform: 'translateY(0)',
                ease: _.options.transitions.easingIn,
                onComplete: function(){
                    _.options.onOpen();
                }
            });
        }
        if(_.initials.transitions.in == "slideLeft" || _.initials.transitions.in == "slideRight"){
            TweenMax.to(target, _.options.transitions.speed, {
                transform: 'translateX(0)',
                ease: _.options.transitions.easingIn,
                onComplete: function(){
                    _.options.onOpen();
                }
            });
        }
        if(_.initials.transitions.in == "scale"){
            TweenMax.to(target, _.options.transitions.speed, {
                scale: 'scale(1)',
                ease: _.options.transitions.easingIn,
                onComplete: function(){
                    _.options.onOpen();
                }
            });
        }

        var delay = parseInt((_.options.transitions.speed - 100) + _.options.delay);

        setTimeout(function(){
            _.afterOpen();
        }, delay);
    }

    ntgModal.prototype.animationClose = function() {
        var _ = this; 
        var target = $(_.options.elements.container);

        if(_.initials.transitions.out == "fade"){
            TweenMax.to(target, _.options.transitions.speed, {
                autoAlpha: 0,
                ease: _.options.transitions.easingOut,
                onComplete: function(){
                    _.options.onClose();
                }
            });
        }
        if(_.initials.transitions.out == "slideDown"){
            TweenMax.to(target, _.options.transitions.speed, {
                transform: 'translateY(-100vh)',
                ease: _.options.transitions.easingOut,
                onComplete: function(){
                    _.options.onClose();
                }
            });
        }
        if(_.initials.transitions.out == "slideUp"){
            TweenMax.to(target, _.options.transitions.speed, {
                transform: 'translateY(100vh)',
                ease: _.options.transitions.easingOut,
                onComplete: function(){
                    _.options.onClose();
                }
            });
        }
        if(_.initials.transitions.out == "slideLeft"){
            TweenMax.to(target, _.options.transitions.speed, {
                transform: 'translateX(-100vw)',
                ease: _.options.transitions.easingOut,
                onComplete: function(){
                    _.options.onClose();
                }
            });
        }
        if(_.initials.transitions.out == "slideRight"){
            TweenMax.to(target, _.options.transitions.speed, {
                transform: 'translateX(100vw)',
                ease: _.options.transitions.easingOut,
                onComplete: function(){
                    _.options.onClose();
                }
            });
        }
        if(_.initials.transitions.out == "scale"){
            TweenMax.to(target, _.options.transitions.speed, {
                scale: 'scale(0.4)',
                ease: _.options.transitions.easingOut,
                onComplete: function(){
                    _.options.onClose();
                }
            });
        }

        var delay = parseInt(_.options.transitions.speed + 100 + _.options.delay);

        setTimeout(function(){
            _.afterClose();
        }, delay);
    }

    ntgModal.prototype.setInitialStyle = function() {
        var _ = this; 
        var target = $(_.options.elements.container);
        $(target).removeAttr('style');

        if(_.initials.transitions.in == "fade"){
            $(target).css('opacity', 0);
            $(target).css('visibility', 'hidden');
        }
        if(_.initials.transitions.in == "slideDown"){
            $(target).css({
                '-webkit-transform': 'translateY(-100vh)',
                'transform': 'translateY(-100vh)',
            });
        }
        if(_.initials.transitions.in == "slideUp"){
            $(target).css({
                '-webkit-transform': 'translateY(100vh)',
                'transform': 'translateY(100vh)',
            });
        }
        if(_.initials.transitions.in == "slideLeft"){
            $(target).css({
                '-webkit-transform': 'translateX(-100vw)',
                'transform': 'translateX(-100vw)',
            });
        }
        if(_.initials.transitions.in == "slideRight"){
            $(target).css({
                '-webkit-transform': 'translateX(100vw)',
                'transform': 'translateX(100vw)',
            });
        }
        if(_.initials.transitions.in == "scale"){
            $(target).css({
                '-webkit-transform': 'scale(0.4)',
                'transform': 'scale(0.4)',
            });
        }
    }

    ntgModal.prototype.afterOpen = function() {
        var _ = this; 
        if($('nav#menu').length>0 && $('nav#menu').hasClass('menu-opened')){
            triggerMobileMenu();
        }

        $(_.options.elements.container).attr({
            'ntgmodal-opened': '',
            'aria-hidden': false,
            'tabindex': -1
        });
    }

    ntgModal.prototype.afterClose = function() {
        var _ = this; 

        $(_.options.elements.container).removeAttr('style');
        $(_.options.elements.container).removeAttr('ntgmodal-opened');
        $(_.options.elements.container).attr({
            'aria-hidden': true,
            'tabindex': 0
        });

        $('body').removeClass('overHidden-modals');
        if(_.options.overlay) $('.mainDiv').removeClass('has-overlay header-hidden');        

        setTimeout(function(){
            _.setInitialStyle();
        }, 300);
    }

    $.fn.ntgmodal = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].ntgmodal = new ntgModal(_[i], opt);
            else
                ret = _[i].ntgmodal[opt].apply(_[i].ntgmodal, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

}));


function initModals(element){
    if($('[ntgmodal]').length>0){
        $('[ntgmodal]').ntgmodal();
    }
}


/*
TRIGGER
<a class="button border" ntgmodal-open="modalMedium" href="javascript:;">Modal Medium</a>

EXEMPLO 1: com divisões
<div id="modalMedium" ntgmodal> 
    <div ntgmodal-content> 
        <div class="div_100" ntgmodalHeader>
            <h4 class="list_tit">Titulo Modal</h4><!-- 
            --><div>
                <button class="close-button small" ntgmodal-close aria-label="Close reveal" role="button" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <div class="div_100 textos" ntgmodalBody>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam. Sed ac nulla ut diam interdum varius in et est. Vestibulum a dui at massa pharetra ultricies in at enim. Vestibulum consectetur efficitur iaculis. Quisque nec mollis lorem. Cras elementum magna non dui ornare, sit amet ullamcorper orci rutrum. Donec congue velit ut diam vestibulum porttitor. Pellentesque imperdiet mauris ut ligula lobortis fermentum. Suspendisse at ante sit amet lorem aliquam porta. Donec bibendum purus a dolor suscipit venenatis. Quisque et sapien laoreet, convallis erat et, consectetur nisl. Nullam tristique interdum nisl nec hendrerit.
        </div>
        <div class="div_100" ntgmodalFooter>
            <a class="button invert" ntgmodal-close href="javascript:;">Action A</a><!-- 
            --><a class="button invert" ntgmodal-close href="javascript:;">Action B</a>
        </div>
    </div>
</div>

EXEMPLO 2: sem divisões
<div id="modalFull" ntgmodal ntgmodal-size="full">
    <div ntgmodal-content>
        <button class="close-button" ntgmodal-close aria-label="Close reveal" role="button" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="div_100" ntgmodalBody>
            <a href="javascript:;" class="list_tit">Modal Full</a>
            <div class="textos">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam. Sed ac nulla ut diam interdum varius in et est. Vestibulum a dui at massa pharetra ultricies in at enim. Vestibulum consectetur efficitur iaculis. Quisque nec mollis lorem. Cras elementum magna non dui ornare, sit amet ullamcorper orci rutrum. Donec congue velit ut diam vestibulum porttitor. Pellentesque imperdiet mauris ut ligula lobortis fermentum. Suspendisse at ante sit amet lorem aliquam porta. Donec bibendum purus a dolor suscipit venenatis. Quisque et sapien laoreet, convallis erat et, consectetur nisl. Nullam tristique interdum nisl nec hendrerit.
            </div>
        </div>
    </div>
</div>
*/
