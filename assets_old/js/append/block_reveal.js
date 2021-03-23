/**
 * main.js
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2016, Codrops
 * http://www.codrops.com
 */
;(function(window) {

	'use strict';

	// Helper vars and functions.
	function extend(a, b) {
		for(var key in b) { 
			if( b.hasOwnProperty( key ) ) {
				a[key] = b[key];
			}
		}
		return a;
	}

	function createDOMEl(type, className, content) {
		var el = document.createElement(type);
		el.className = className || '';
		el.innerHTML = content || '';
		return el;
	}

	/**
	 * RevealFx obj.
	 */
	function RevealFx(el, options) {
		this.el = el;
		this.options = extend({}, this.options);
		extend(this.options, options);
		this._init();
	}
	
	

	/**
	 * RevealFx options.
	 */
	RevealFx.prototype.options = {
		// If true, then the content will be hidden until it´s "revealed".
		isContentHidden: true,
		// The animation/reveal settings. This can be set initially or passed when calling the reveal method.
		revealSettings: {
			// Animation direction: left right (lr) || right left (rl) || top bottom (tb) || bottom top (bt).
			direction: 'lr',
			// Revealer´s background color.
			bgcolor: '#f0f0f0',
			// Animation speed. This is the speed to "cover" and also "uncover" the element (seperately, not the total time).
			duration: 800,
			// Animation easing. This is the easing to "cover" and also "uncover" the element.
			easing: 'easeInOutQuint',
			// percentage-based value representing how much of the area should be left covered.
			coverArea: 0,
			// Callback for when the revealer is covering the element (halfway through of the whole animation).
			onCover: function(contentEl, revealerEl) { return false; },
			// Callback for when the animation starts (animation start).
			onStart: function(contentEl, revealerEl) { return false; },
			// Callback for when the revealer has completed uncovering (animation end).
			onComplete: function(contentEl, revealerEl) { return false; }
		}
	};

	/**
	 * Init.
	 */
	RevealFx.prototype._init = function() {
		this._layout();
		var rev = this;
		
		$(window).resized(function() {
			rev._arrowSize();
		});
	};

	/**
	 * Build the necessary structure.
	 */
	RevealFx.prototype._layout = function() {
		if($(this.el).find('.block-revealer__content').length==0 && $(this.el).find('.block-revealer__element').length==0){
			var position = getComputedStyle(this.el).position;
			if( position !== 'fixed' && position !== 'absolute' && position !== 'relative' ) {
				this.el.style.position = 'relative';
			}
			// Content element.
			//this.content = createDOMEl('div', 'block-revealer__content', this.el.innerHTML);
			this.content = createDOMEl('div', 'block-revealer__content');
			if( this.options.isContentHidden) {
				this.content.style.opacity = 0;
			}
			// Revealer element (the one that animates)
			this.revealer = createDOMEl('div', 'block-revealer__element');
			this.el.classList.add('block-revealer');
			
			if($(this.el).hasClass('reveal_arrow')){
				this._arrow();
			}
			
			//this.el.appendChild(this.content);
			$(this.el).children().wrapAll(this.content);
			this.content = $(this.el).children().first()[0];
			this.el.appendChild(this.revealer);
		}else{
			this.content = $(this.el).children().first()[0];
			this.revealer = $(this.el).find('.block-revealer__element')[0];
		}
	};
	
	
	/**
	 * Gets the revealer arrow.
	 */
	RevealFx.prototype._arrow = function() {
		if($(this.el).hasClass('reveal_arrow') && !$(this.el).hasClass('revealed')){
			if($(this.revealer).find('.block-revealer__arrow').length==0){
				this.triangle = createDOMEl('div', 'block-revealer__arrow show-for-medium');
			}else{
				this.triangle = $(this.revealer).find('.block-revealer__arrow');
			}
		
			this._arrowSize();
				
			if($(this.revealer).find('.block-revealer__arrow').length==0){
				this.revealer.appendChild(this.triangle);
			}
		}else{
			this.triangle = $(this.el).find('.block-revealer__arrow')[0];
		}
	};

	/**
	 * Gets the revealer arrow.
	 */
	RevealFx.prototype._arrowSize = function() {
		if($(this.el).hasClass('reveal_arrow') && $(this.revealer).find('.block-revealer__arrow').length>0){
			var racio = $(this.el).attr('data-racio').split('x');
			var racioW = parseInt(racio[0]);
			var racioH = parseInt(racio[1]);
						
			var borderH = parseFloat(getComputedStyle(this.el).height);
			var borderW = (borderH*racioW) / racioH;
			borderH = borderH / 2;
			
			var border = "";	
						
			if($(this.el).hasClass('arrow_left')){
				border = borderH+'px '+borderW+'px '+borderH+'px 0';
				$(this.triangle).css('left', '-'+parseInt(borderW)+'px');
			}
			if($(this.el).hasClass('arrow_right')){
				border = borderH+'px 0 '+borderH+'px '+borderW+'px';
				$(this.triangle).css('right', '-'+parseInt(borderW)+'px');
			}


			$(this.triangle).css('border-width', border);
		}
	};

	/**
	 * Gets the revealer element´s transform and transform origin.
	 */
	RevealFx.prototype._getTransformSettings = function(direction) {
		var val, origin, origin_2;

		switch (direction) {
			case 'lr' : 
				val = 'scale3d(0,1,1)';
				origin = '0 50%';
				origin_2 = '100% 50%';
				break;
			case 'rl' : 
				val = 'scale3d(0,1,1)';
				origin = '100% 50%';
				origin_2 = '0 50%';
				break;
			case 'tb' : 
				val = 'scale3d(1,0,1)';
				origin = '50% 0';
				origin_2 = '50% 100%';
				break;
			case 'bt' : 
				val = 'scale3d(1,0,1)';
				origin = '50% 100%';
				origin_2 = '50% 0';
				break;
			default : 
				val = 'scale3d(0,1,1)';
				origin = '0 50%';
				origin_2 = '100% 50%';
				break;
		};

		return {
			// transform value.
			val: val,
			// initial and halfway/final transform origin.
			origin: {initial: origin, halfway: origin_2},
		};
	};

	/**
	 * Reveal animation. If revealSettings is passed, then it will overwrite the options.revealSettings.
	 */
	RevealFx.prototype.reveal = function(revealSettings) {
		// Do nothing if currently animating.
		if( this.isAnimating ) {
			return false;
		}
		this.isAnimating = true;
		
		// Set the revealer element´s transform and transform origin.
		var defaults = { // In case revealSettings is incomplete, its properties deafault to:
				duration: 500,
				easing: 'easeInOutQuint',
				delay: 0,
				bgcolor: '#f0f0f0',
				direction: 'lr',
				coverArea: 0
			},
			revealSettings = revealSettings || this.options.revealSettings,
			direction = revealSettings.direction || defaults.direction,
			transformSettings = this._getTransformSettings(direction);

		this.revealer.style.WebkitTransform = this.revealer.style.transform =  transformSettings.val;
		this.revealer.style.WebkitTransformOrigin = this.revealer.style.transformOrigin =  transformSettings.origin.initial;
		
		// Set the Revealer´s background color.
		this.revealer.style.backgroundColor = revealSettings.bgcolor || defaults.bgcolor;
		this.revealer.style.color = revealSettings.bgcolor || defaults.bgcolor;
		
		// Show it. By default the revealer element has opacity = 0 (CSS).
		this.revealer.style.opacity = 1;

		// Animate it.
		var self = this,
			delay = revealSettings.delay || defaults.delay,
			// Second animation step.
			animationSettings_2 = {
				onComplete: function() {
					self.isAnimating = false;
					if( typeof revealSettings.onComplete === 'function' ) {
						revealSettings.onComplete(self.content, self.revealer);
					}
				}
			},
			// First animation step.
			animationSettings = {
				onComplete: function() {
					self.revealer.style.WebkitTransformOrigin = self.revealer.style.transformOrigin = transformSettings.origin.halfway;		
					if( typeof revealSettings.onCover === 'function' ) {
						revealSettings.onCover(self.content, self.revealer);
					}

					setTimeout(function(){
						TweenMax.to(animationSettings_2.targets, animationSettings_2.duration, animationSettings_2);  
                    }, delay);
				}
			};

		animationSettings.targets = animationSettings_2.targets = this.revealer;
		animationSettings.duration = animationSettings_2.duration = revealSettings.duration || defaults.duration;
		animationSettings.easing = animationSettings_2.easing = revealSettings.easing || defaults.easing;

		var coverArea = revealSettings.coverArea || defaults.coverArea;
		if( direction === 'lr' || direction === 'rl' ) {
			animationSettings.scale = "scaleX: (0,1)";
			animationSettings_2.scale = "scaleX: [1,"+coverArea/100+"]";
		}else {
			animationSettings.scale = "scaleY: (0,1)";
			animationSettings_2.scale = "scaleY: [1,"+coverArea/100+"]";
		}

		if( typeof revealSettings.onStart === 'function' ) {
			revealSettings.onStart(self.content, self.revealer);
		}

		TweenMax.to(animationSettings.targets, animationSettings.duration, animationSettings); 
	};
	
	window.RevealFx = RevealFx;

})(window);