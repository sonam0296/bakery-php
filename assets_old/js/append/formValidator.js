/**
* Distance between two points P1 (x1,y1) and P2 (x2,y2).
*/
var distancePoints = function distancePoints(x1, y1, x2, y2) {
    return Math.sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2));
};

// from http://www.quirksmode.org/js/events_properties.html#position
var getMousePos = function getMousePos(e) {
    var posx = 0,
        posy = 0;
    if (!e) var e = window.event;
    if (e.pageX || e.pageY) {
        posx = e.pageX;
        posy = e.pageY;
    } else if (e.clientX || e.clientY) {
        posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
        posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
    }
    return { x: posx, y: posy };
};
function Nearby(el, options) {
    var mousemoveFn = function (ev) {
        return requestAnimationFrame(function () {
            var mousepos = getMousePos(ev);
            var docScrolls = { left: document.body.scrollLeft + document.documentElement.scrollLeft, top: document.body.scrollTop + document.documentElement.scrollTop };
            var elRect = el.getBoundingClientRect();
            var elCoords = {
                x1: elRect.left + docScrolls.left, x2: elRect.width + elRect.left + docScrolls.left,
                y1: elRect.top + docScrolls.top, y2: elRect.height + elRect.top + docScrolls.top
            };
            var closestPoint = { x: mousepos.x, y: mousepos.y };

            if (mousepos.x < elCoords.x1) {
                closestPoint.x = elCoords.x1;
            } else if (mousepos.x > elCoords.x2) {
                closestPoint.x = elCoords.x2;
            }
            if (mousepos.y < elCoords.y1) {
                closestPoint.y = elCoords.y1;
            } else if (mousepos.y > elCoords.y2) {
                closestPoint.y = elCoords.y2;
            }
            if (options.onProgress) {
                options.onProgress(distancePoints(mousepos.x, mousepos.y, closestPoint.x, closestPoint.y));
            }
        });
    };

    window.addEventListener('mousemove', mousemoveFn);
}


/**
 * formValidator.js
 * Rui Correia
 */

var $elements = 'input[required], textarea[required], select[required]';
var $errorInpt = '.inpt_error';
var $error_el = 'has-error';
var $nerby_el = 'has-errorNearby';
var $anime_from = 0;

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
    var formValidator = window.formValidator || {};

    formValidator = (function() {

        function formValidator(element, settings) {

            var _ = this, dataSettings;

            _.defaults = {
                container: element,
                elements: $elements,
                errorEl: $errorInpt,
                btnSubmit: 'button[type="submit"]',
                color: '221,14,14',
                classes: {
                    nearbyError: $nerby_el,
                    error: $error_el,
                },
                distance: { // whatever we do, start at [distanceThreshold.max]px from the element and end at [distanceThreshold.min]px from the element.
                    min: 0, 
                    max: 75
                },
                animationInterval: { 
                    from:$anime_from, 
                    to: 1
                }
            };

            _.initials = {
                id: '',
                classValidate:'', 
                state: 1,
            };
			
            if(!$(element).data('formvalidator')){
                dataSettings = $(element).data('formvalidator', _) || {};
                $.extend(_, _.initials);
                _.options = $.extend({}, _.defaults, dataSettings, settings);
    			    			
                _.init();
            } 

            _.init = $.proxy(_.init, _);
            _.validateForm = $.proxy(_.validateForm, _);

        }
        return formValidator;

    }());
   	   
    formValidator.prototype.init = function() {
        var _ = this; 

        if($(_.options.container).length>0 && $(_.options.container).find(_.options.elements).length>0){
            _.initials.id = $(_.options.container).attr('id');

            $(_.options.container).on('submit', function(){
                _.initials.classValidate = _.options.classes.error;
                _.initials.state = 1;
                return _.validateForm(_.initials.id, 1, _.options.animationInterval.to);
            });



            if(!checkMobile()){
                $(_.options.container).find('.inpt_holder').removeClass(_.options.classes.error);

                /* // Analisar
                $(_.options.container).find(_.options.elements).on('change', function(){
                    _.initials.state = 1;

                    $(this).parent().find('.inpt_label_error').css('opacity', 0);
                    $(this).parent().find(_.options.errorEl).css('opacity', 0);
                });
                

                $(_.options.container).find(_.options.elements).each(function() {
                    var $label = $(this).parent().find('.inpt_label');
                    if($label.length>0){
                        var $clone = $label.clone( true );
                        $clone.addClass('inpt_label_error').insertAfter($label);
                    }
                }); 
                */
               
                Nearby($(_.options.container).find(_.options.btnSubmit)[0], {
                    onProgress: function(distance){
                        if((distance - 50) <= _.options.distance.max){
                            var o = _.lineEq(_.options.animationInterval.from, _.options.animationInterval.to, _.options.distance.max, _.options.distance.min, distance);
                            
                            if(o == 1) _.initials.state = 0;

                            _.initials.classValidate = _.options.classes.nearbyError;

                            return _.validateForm(_.initials.id, 0, o);
                        }
                    }
                });
            }            
        }
    };

    formValidator.prototype.validateForm = function(form, clicked, animeVal) {
        var _ = this; 

        return validaForm(form, clicked, animeVal, _.initials.classValidate);
    };


    formValidator.prototype.lineEq = function(y2, y1, x2, x1, currentVal){
        // y = mx + b 
        var m = (y2 - y1) / (x2 - x1), b = y1 - m * x1;
        return m * currentVal + b;
    }

    $.fn.formvalidator = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].formvalidator = new formValidator(_[i], opt);
            else
                ret = _[i].formvalidator[opt].apply(_[i].formvalidator, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

}));


function initFormValidator(){
    if($('form[nearby-validator]').length>0){
        $('form[nearby-validator]').formvalidator();
    }
}


function validaForm(form, clicked, animeVal, invalid) {
    var valid = 1; 
    var invalidClasses = $error_el;
    if(invalid) invalidClasses = invalid;

/*    var patternValidators = {
        'text_2_20': ['^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$', $recursos['reg_text_2_20']],
        'password': ['^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$', $recursos['reg_password']],
        'password_8': ['(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$', $recursos['reg_password_8']],
        'cartao_credito': ['[0-9]{13,16}', $recursos['reg_cartao_credito']],
        'visa': ['^4[0-9]{12}(?:[0-9]{3})?$', $recursos['reg_visa']],
        'mastercard': ['^5[1-5][0-9]{14}$', $recursos['reg_mastercard']],
        'american_express': ['^3[47][0-9]{13}$', $recursos['reg_american_express']],
        'cod_postal_pt': ['[0-9]{4}\-[0-9]{3}', $recursos['reg_cod_postal_pt']],
        'data': ['(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', $recursos['reg_data']], 
        'phones_pt': ['(\(?\+33\)?\s?|0033\s?)[1-9](?:[\s]?\d\d\d){3}$', $recursos['reg_phones_pt']],
        'preco_virgula': ['\d+(,\d{2})?', $recursos['reg_preco_virgula']],
        'preco_ponto': ['\d+(.\d{2})?', $recursos['reg_preco_ponto']],


    };*/
    //Data:
    //1) the year is numeric and starts with 19 or 20,
    //2) the month is numeric and between 01-12, and
    //3) the day is numeric between 01-29, or
    //b) 30 if the month value is anything other than 02, or
    //c) 31 if the month value is one of 01,03,05,07,08,10, or 12.
    
    //bloqueia o botão
    if(clicked==1){
        if($('#'+form).find('button').length>0){
            $('#'+form).find('button').attr('disabled', true);
            $('#'+form).find('button').addClass('disabled');
        }
    }
    
    $('#'+form).find($elements).each(function() {
        var $this = $(this);
        if($this.attr('type')==="radio" || $this.attr('type')==="checkbox"){            
            if($this.attr('type')==="radio"){
                var checked = $('input[name='+$this.attr('name')+']:checked').val();
                if(!checked){
                    $('input[name='+$this.attr('name')+']').addClass(invalidClasses);
                    valid = 0;
                }else{
                    $('input[name='+$this.attr('name')+']').removeClass(invalidClasses);
                }
            }
            if($this.attr('type')==="checkbox"){
                var checked = $('input[name="'+$this.attr('name')+'"]:checked').val();
                if(!checked){
                    $('input[name="'+$this.attr('name')+'"]').addClass(invalidClasses);
                    valid = 0;
                }else{
                    $('input[name="'+$this.attr('name')+'"]').removeClass(invalidClasses);
                }
            }
        }else{
        	 if($this.attr('id')=='localidade_pm'){
                if($('#pickme').css('display') != 'none' && $('#localidade_pm').val() == '0'){
                    if($this.parents('.inpt_holder').length>0) $this.parents('.inpt_holder').addClass(invalidClasses);
                    else $this.addClass(invalidClasses);
                    valid = 0;
                }
                else {
                    if($this.parents('.inpt_holder').length>0) $this.parents('.inpt_holder').removeClass(invalidClasses);
                    else $this.removeClass(invalidClasses);  
                }
	            }else{
	            if(!$this.val() || $this.val()==0 || $this.val().trim().length===0){
	                if($this.parents('.inpt_holder').length>0) $this.parents('.inpt_holder').addClass(invalidClasses);
	                else $this.addClass(invalidClasses);
	                
	                valid = 0;
	            }else{
	                if($this.parents('.inpt_holder').length>0) $this.parents('.inpt_holder').removeClass(invalidClasses);
	                else $this.removeClass(invalidClasses);
	            }
	            
	            if($this.attr('type') == "email"){
	                if(!isValidEmailAddress($this.val())){
	                    if($this.parents('.inpt_holder').length>0) $this.parents('.inpt_holder').addClass(invalidClasses);
	                    else $this.addClass(invalidClasses);
	                    
	                    valid = 0;
	                }else{
	                    if($this.parents('.inpt_holder').length>0) $this.parents('.inpt_holder').removeClass(invalidClasses);
	                    else $this.removeClass(invalidClasses);
	                }
	            }
        
                    
            }
              

	/*            if(typeof patternValidators[$this.attr('data-pattern')] != "undefined"){
	                var regExp = new RegExp(patternValidators[$this.attr('data-pattern')][0]);
	                var match = regExp.test($this.val());
	                
	                if (match){
	                    if($this.parents('.inpt_holder').length>0) $this.parents('.inpt_holder').removeClass(invalidClasses);
	                    else $this.removeClass(invalidClasses);

	                    $this.next('label').fadeOut('slow').remove();
	                }else{
	                    if($this.parents('.inpt_holder').length>0) $this.parents('.inpt_holder').addClass(invalidClasses);
	                    else $this.addClass(invalidClasses);
	                    if($this.next('.label-error').length===0){
	                        var $label = $( "<label class='label-error list_txt'>"+patternValidators[$this.attr('data-pattern')][1]+"</label>");
	                        $label.insertAfter($this);
	                    }   

	                    valid = 0;
	                }
	            }*/

	            if($this.hasClass('confirm')){
	                var index = $this.index('.confirm');
	                var confirm_inpt = $this;

	                var cod_confirm = $('.cod_confirm:eq('+index+')', '#'+form);

	                if((confirm_inpt.val() !== cod_confirm.val()) || confirm_inpt.val().trim().length===0 || cod_confirm.val().trim().length===0){
	                    if(confirm_inpt.parents('.inpt_holder').length>0) confirm_inpt.parents('.inpt_holder').addClass(invalidClasses);
	                    else confirm_inpt.addClass(invalidClasses);

	                    if(cod_confirm.parents('.inpt_holder').length>0) cod_confirm.parents('.inpt_holder').addClass(invalidClasses);
	                    else cod_confirm.addClass(invalidClasses);
	                    
	                    valid = 0;
	                }else{
	                    if(confirm_inpt.parents('.inpt_holder').length>0) confirm_inpt.parents('.inpt_holder').removeClass(invalidClasses);
	                    else confirm_inpt.removeClass(invalidClasses);

	                    if(cod_confirm.parents('.inpt_holder').length>0) cod_confirm.parents('.inpt_holder').removeClass(invalidClasses);
	                    else cod_confirm.removeClass(invalidClasses);
	                }
	            }
	        }
        

    if($('.captcha', '#'+form).length>0){
        var captcha = array_captcha[$('.captcha', '#'+form).attr('id')];
        var isCaptcha = grecaptcha.getResponse(captcha);
        
        if(isCaptcha.length === 0){
            if(valid == 1 && clicked==1) ntg_error($('.captcha', '#'+form).attr('data-error'));
            valid = 0;
        }
    }

    if(clicked==1){
        if($('#'+form).find('button').length>0){
            $('#'+form).find('button').attr('disabled', false);
            $('#'+form).find('button').removeClass('disabled');
        }
    }
            
    if(valid == 0){

        if($('#'+form).find('.'+invalidClasses).length>0){
            if(typeof $('#'+form).attr('nearby-validator') == "undefined" || checkMobile() || clicked == 1){
                $('html, body').stop(true, true).animate({
                    scrollTop: $('#'+form).find('.'+invalidClasses).offset().top - getAltMenu()
                }, 1500,'easeInOutExpo', function() {});
                if(form != 'comprar' && form != 'form_facturacao' && form != 'form_envio') {
                    ntg_error($recursos['form_erro']);
                }
            }else{
                $('#'+form).find('.'+invalidClasses).each(function(){
                    var $label = $(this).find('.inpt_label_error');
                    var $error = $(this).find($errorInpt);

                    TweenLite.to($label, .3, {
                        opacity: Math.max(animeVal, $anime_from),
                    });
                    TweenLite.to($error, .3, {
                        opacity: Math.max(animeVal, $anime_from),
                    });
                });

            }
        }

        return false;
    }else{      
        return true;
    }
}







