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
    var ntgSteps = window.ntgSteps || {};

    ntgSteps = (function() {

        function ntgSteps(element, settings) {

            var _ = this, dataSettings;

            _.defaults = {
                container: element,
                elements: '[steps-field]',
                prevButton: '[steps-prevBtn]',
                nextButton: '[steps-nextBtn]',
                images: '[steps-image]',
                progressBar: '[steps-progressbar]',
                duration: 1,
            };

            _.initials = {
                current: '',
                next: '',
                previous: '',
                left: '',
                opacity: '',
                scale: '',
                currentImg: '',
                nextImg: '',
                previousImg: '',
                animating: '',
                gotImages: 0,
                targetHeight: 0, 
            };
			
            if(!$(element).data('ntgsteps')){
                dataSettings = $(element).data('ntgsteps', _) || {};
                $.extend(_, _.initials);
                _.options = $.extend({}, _.defaults, dataSettings, settings);
    			    			
                _.init();
            } 

            _.init = $.proxy(_.init, _);
            _.handlePrev = $.proxy(_.handlePrev, _);
            _.handleNext = $.proxy(_.handleNext, _);

        }
        return ntgSteps;

    }());
   	   
    ntgSteps.prototype.init = function() {
        var _ = this; 

        if($(_.options.container).length>0 && $(_.options.container).find(_.options.elements).length>0){
            $(_.options.container).attr('formStep-current', 1);
            $(_.options.container).find(_.options.elements+':eq(0)').attr('step-active', '');

            if($(_.options.container).find(_.options.prevButton).length>0){
                $(_.options.container).find(_.options.prevButton).on('click', function(){
                    _.handlePrev();
                });
            }

            if($(_.options.nextButton).length>0){
                $(_.options.container).find(_.options.nextButton).on('click', function(){
                    _.handleNext();
                });
            }

            if($(_.options.container).find(_.options.images).length>0){
                $(_.options.container).find(_.options.images+':eq(0)').addClass('fadeIn');
            }

            _.setFormHeight(0);

            $(window).resized(function(){
                _.setFormHeight(0);
            });
        }
    };

    ntgSteps.prototype.handlePrev = function() {
        var _ = this; 

        var $btn = $(_.options.container).find('[step-active] [steps-prevBtn]');
        if(typeof $btn.attr('steps-callback')!="undefined"){
            var response = window[$btn.attr('steps-callback')]();
            if(response==false) return false;
        }

        if(_.initials.animating) return false;
        _.initials.animating = true;
     
        _.initials.current = $(_.options.container).find('[step-active]');
        _.initials.previous = _.initials.current.prev();

        if($(_.options.container).find(_.options.images).length>0){
            _.initials.gotImages = 1;

            _.initials.currentImg =  $(_.options.container).find(_.options.images).eq($(_.options.container).find('[step-active]').index());
            _.initials.previousImg = $(_.initials.currentImg).prev();
        }
     
        //de-activate current step on progressbar
        if($(_.options.container).find(_.options.progressBar).length>0){
            $(_.options.container).find(_.options.progressBar+' li').eq($(_.options.container).find('[step-active]').index()).removeClass("active");
        }
     
        //show the previous fieldset
        _.initials.previous.show(); 
        //show the previous image
        if(_.initials.gotImages==1){
            _.initials.previousImg.addClass('fadeIn');
            _.initials.currentImg.removeClass('fadeIn');
        }
        //hide the current fieldset with style
        TweenLite.to(_.initials.current, _.options.duration, { 
            autoAlpha: 0,
            left: '-50%',
            ease:Back.easeInOut, 
        });
        TweenLite.to(_.initials.previous, (_.options.duration - 0.3), { 
            autoAlpha: 1,
            transform: 'scale(1)',
            ease:Back.easeInOut, 
            onComplete: function(){
                $(_.options.container).attr('formStep-current', (_.initials.previous.index()+1));
                _.initials.current.removeAttr('step-active').hide();
                _.initials.previous.attr('step-active', '');

                _.setFormHeight(1);

                _.initials.animating = false;
            }
        });
    };

    ntgSteps.prototype.handleNext = function() {
        var _ = this; 

        var $btn = $(_.options.container).find('[step-active] [steps-nextBtn]');

        if(typeof $btn.attr('steps-callback')!="undefined"){
            var response = window[$btn.attr('steps-callback')]();
            if(response!=true) return false;
        }

        if(_.initials.animating) return false;
        _.initials.animating = true;
     
        _.initials.current = $(_.options.container).find('[step-active]');
        _.initials.next = _.initials.current.next();
     
        if($(_.options.container).find(_.options.images).length>0){
            _.initials.gotImages = 1;

            _.initials.currentImg =  $(_.options.container).find(_.options.images).eq($(_.options.container).find('[step-active]').index());
            _.initials.nextImg = $(_.initials.currentImg).next();
        }

        //activate next step on progressbar using the index of next
        if($(_.options.progressBar).length>0){
            $(_.options.progressBar+' li').eq($(_.options.container).find(_.options.elements).index(_.initials.next)).addClass("active");
        }
        
        //show the next fieldset
        _.initials.next.show(); 
        //show the previous image
        if(_.initials.gotImages==1){
            _.initials.nextImg.addClass('fadeIn');
            _.initials.currentImg.removeClass('fadeIn');
        }
        //hide the current fieldset with style
        TweenLite.to(_.initials.current, _.options.duration, { 
            autoAlpha: 0,
            transform: 'scale(0.8)',
            ease:Back.easeInOut, 
        });

        TweenLite.to(_.initials.next, (_.options.duration - 0.3), { 
            autoAlpha: 1,
            left: 0,
            ease:Back.easeInOut, 
            onComplete: function(){
                $(_.options.container).attr('formStep-current', (_.initials.next.index()+1));
                _.initials.current.removeAttr('step-active').hide();
                _.initials.next.attr('step-active', '');

                _.setFormHeight(1);

                _.initials.animating = false;
            }
        });
    }

    ntgSteps.prototype.setFormHeight = function(toTop) {
        var _ = this; 

        if($(_.options.container).attr('formStep-watch')=="true" || typeof $(_.options.container).attr('formStep-watch')=="undefined"){
            var newTargetHeight = 0;
            $(_.options.container).find(_.options.elements).each(function() {
                newTargetHeight = Math.max($(this).height(), newTargetHeight);
            });

            if(_.initials.targetHeight!=newTargetHeight){
                _.initials.targetHeight = newTargetHeight;
                $(_.options.container).height(_.initials.targetHeight);
            }
        }else{
            var newTargetHeight = $(_.options.container).find('[step-active]').height();
            TweenMax.fromTo($(_.options.container), (_.options.duration - 0.3), 
                {css: {height: _.initials.targetHeight}}, 
                {css:{height: newTargetHeight}} 
            );

            _.initials.targetHeight = newTargetHeight;

            if(toTop==1 && $(_.options.container).parents('[ntgmodal-content]').length>0){
                TweenLite.to($(_.options.container).parents('[ntgmodal-content]'), (_.options.duration - 0.3), { 
                    scrollTo:0
                });
            }
        }
    }

    $.fn.ntgsteps = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].ntgsteps = new ntgSteps(_[i], opt);
            else
                ret = _[i].ntgsteps[opt].apply(_[i].ntgsteps, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

}));


function initMultiSteps(element){
    if($('[formStep]').length>0){
        $('[formStep]').ntgsteps();
    }
}


