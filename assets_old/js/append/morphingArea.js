/*NOTA: 
The issue here lies with your .content-container wrapper class, which has a CSS declaration of webkit-transform: translate3d(0,0,0). The transform declaration, as this answer illustrates, changes the positioning context from the viewport to the rotated element, which essentially means that your fixed element behaves as if it were absolutely positioned. Here's an example showing the difference between a fixed element inside a transformed div and a fixed element outside of that div.

É a razão pela qual se utiliza um clone em vez do proprio elemento*/

;( function( window ) {
	
	'use strict';

	function extend( a, b ) {
		for( var key in b ) { 
			if( b.hasOwnProperty( key ) ) {
				a[key] = b[key];
			}
		}
		return a;
	}

	function UIMorphingArea( el, options ) {
		this.el = el;
		this.options = extend( {}, this.options );
		extend( this.options, options );
		this._init();
	}

	UIMorphingArea.prototype.options = {
		closeEl : '',
		onBeforeOpen : function() { return false; },
		onAfterOpen : function() { return false; },
		onBeforeClose : function() { return false; },
		onAfterClose : function() { return false; }
	}

	UIMorphingArea.prototype._init = function() {
		// the opener
		//this.opener = $(this.el).next( '.morphArea_opener' )[0];
		this.watcher = this.el.querySelector( '.morphArea_watcher' );
		this.opener = "";
		this.clone = "";
		this.offsets = [];
		this.positions = [];
		this.visibleHeader = 0;

		// overflow
		this._checkOverflow();

		// state
		this.expanded = false;
		// content el
		//this.contentEl = this.el.querySelector( '.morph-content' );
		this.contentEl = this.el.cloneNode(true);
		// init events
		this._initEvents();
	}

	UIMorphingArea.prototype._initEvents = function() {
		var self = this;

		// open
		if( self.opener !== '' ) {
			self.opener.addEventListener( 'click', function() { self.toggle(); } );
		}
	
		$(document).on("keydown", function(event) {
			if(event.which == 27 && self.expanded){
				self.toggle();
			}
	    }); //end keypress

		$(window).resized(function(){
			self._checkOverflow();
		});

		self._getOffsets();
	}

	UIMorphingArea.prototype._checkOverflow = function() {
		var self = this;

		if (this.watcher.offsetHeight < this.watcher.scrollHeight || this.watcher.offsetWidth < this.watcher.scrollWidth) {
		    // your element have overflow
		     if( this.opener === '' ) {
		     	var btnClass = "button";
		     	var btnTxt = $recursos['saiba_mais'];

		     	if($(this.watcher).attr('data-btnTxt')){
		     		btnTxt = $(this.watcher).attr('data-btnTxt');
		     	}
		     	if($(this.watcher).attr('data-btnClass')){
		     		btnClass = $(this.watcher).attr('data-btnClass');
		     	}

			    this.opener = document.createElement('button');
				this.opener.className = btnClass+' morphArea_opener';
				this.opener.innerHTML = btnTxt;

				if($(this.watcher).attr('data-btnAppender')){
		     		$(this.el).find($(this.watcher).attr('data-btnAppender'))[0].appendChild(this.opener);
		     	}else{
		     		this.el.appendChild(this.opener);
		     	}
			}
		} else {
		    if( this.opener !== '' ) {
				this.opener.removeEventListener( 'click', function() { self.toggle(); } );
				$(this.opener).remove();
			}
		}
	}

	UIMorphingArea.prototype._getOffsets = function() {
		var self = this;

		this.positions = [];
		this.offsets = [];
		
		this.offsets['top'] = ($(this.el).offset().top - $(window).scrollTop())+"px";
		this.offsets['left'] = $(this.el).offset().left+"px";
		this.offsets['width'] = $(this.el).outerWidth()+"px";
		this.offsets['height'] = $(this.el).outerHeight()+"px";
	    
	    if($('body').innerWidth()>950){
	    	var centerX = ($('body').innerWidth() - ($('body').innerWidth() / 2)) / 2;
	    	
	    	this.positions['top'] = "15%";
			//this.positions['left'] = "50%";
			this.positions['left'] = centerX+"px";
			this.positions['width'] = "50%";
			this.positions['height'] = "70%";
		}else{
			this.positions['top'] = "0px";
			this.positions['left'] = "0px";
			this.positions['width'] = "100%";
			this.positions['height'] = "100%";
		}
	}

	UIMorphingArea.prototype.toggle = function() {
		if( this.isAnimating ) return false;

		var self = this;
		this.isAnimating = true;

		this._getOffsets();

		// callback
		if( this.expanded ){
			this.options.onBeforeClose();

			TweenMax.to($(this.clone), 1, {
		        width: self.offsets['width'],
		        height: self.offsets['height'],
		        top: self.offsets['top'],
		        left: self.offsets['left'],
		        ease: Expo.easeInOut,
		        onComplete: function() {
					$(self.clone).removeClass( 'open' ); 

					$(self.clone).remove();
					self.clone = "";
					
					self.isAnimating = false;
					self.expanded = false;

					self.options.onAfterClose();
					$('body').removeClass('overHidden');
					$('.mainDiv').removeClass('has-overlay header-hidden');
		     	}
		    });
		    TweenMax.to($(this.el), 0.4, {
		    	opacity: 1
		    });
		}else {
			$('body').addClass('overHidden');
			this.options.onBeforeOpen();

			if( this.clone === '' ) {
				this.clone = document.createElement('div');

				$(this.clone).css({
                    position: "fixed",
                    display: "block",
                    top: self.offsets['top'],
                    left: self.offsets['left'],
                    width: self.offsets['width'],
                    height: self.offsets['height'],
                    overflow: 'hidden',
                    opacity: "0",
                    "z-index": "900",
                });


				if($(this.el).parents('.morphArea_appender').length>0){
					$(this.el).parents('.morphArea_appender')[0].appendChild(this.clone);
				}else if($(this.watcher).attr('data-appender') && $('#'+$(this.watcher).attr('data-appender')).length>0){
					$('#'+$(this.watcher).attr('data-appender'))[0].appendChild(this.clone);
				}else{
					//$(this.clone).insertAfter($(this.el));
					document.querySelector('.mainDiv').appendChild(this.clone);
				}

				var thisClass = "";
				if($(this.watcher).attr('data-class')){
		     		thisClass = $(this.watcher).attr('data-class');
		     	}

				$(this.clone).html(this.contentEl);
				$(this.clone).children().wrapInner("<div class='morphArea_scroller' />");
				$(this.clone).addClass('morphArea_clone '+thisClass);

				// close
				if( $(this.clone).find('.morphArea_opener').length>0 ) {
					$(this.clone).find('.morphArea_opener').text($recursos['fechar']);
					$(this.clone).find('.morphArea_opener')[0].addEventListener( 'click', function() { self.toggle(); } );
				}	

				var closeBtn = $('<a class="close-button morphArea_closer" data-close aria-label="Close modal">&times;</a>');
				closeBtn.appendTo($(this.clone).children());	
				closeBtn.on('click', function(){ 
					self.toggle(); 
				});
			}

			setTimeout(function(){
				$(self.clone).addClass( 'opening' );
				$('.mainDiv').addClass('has-overlay header-hidden');
			}, 500);

            TweenMax.to($(this.clone), 1, {
            	top: self.positions['top'],
                left: self.positions['left'],
                width: self.positions['width'],
                height: self.positions['height'],
				opacity: 1,
		        ease: Expo.easeInOut,
		        onComplete: function() {
					$(self.clone).addClass( 'open' );
					$(self.clone).removeClass( 'opening' );

					self.isAnimating = false;
					self.expanded = true;					

					self.options.onAfterOpen();
		     	}
		    });

		    TweenMax.to($(this.el), 0.8, {
		    	opacity: 0
		    });
		}
	}

	// add to global namespace
	window.UIMorphingArea = UIMorphingArea;

})( window );