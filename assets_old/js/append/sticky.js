/*
$(document).ready(function() {
    if($('.header').length>0){
		$('.header').sticky({
			container : element, // elemento
			parent: '',
			parentWidth: false, // fica com o width do parent
			fixed: 'all', // resolução em que fica sticky, all para todas
			fixedAt: 'all', // resolução até onde fica sticky, all para todas
			fixedTil: '', // se pretenderem que fique fixo ate um elemento especifico
			position:'top', // posição, top ou bottom
			hideScroll: { //esconder no scroll bottom e mostrar on scroll top
				desktop: false,
				mobile: false,
			}, 
			appearAt: 300, // se quiserem um delay para aparecer coloquem o appearAt
			shrinkAt: 300, // para adicionar a class "shrinked" num certo offset
			shrinkTo: 0, // 0 = shrinked para todas as resolucoes || 950 = 950px up

			metaBase:'',  // Metatag base, para quando nao encontra nenhuma.
			metatags:true,  // se pretenderem alterar as metas on scroll
			anchors: '.header_links', // se pretenderem alterar as metas on scroll
			anchorsAll : false, //Se queremos ficar com a class "sel" antes e depois do "scroll" ter passado pela seccção, basta nao chamar isto
			activeClass: 'sel', // class para elemento/anchor activ
			line:'.sticky_line', // class da linha que acompanha o menu selecionado
		});
	}
});
*/

;(function ( $, window, document, undefined ) {
    'use strict';
    var Sticky = window.Sticky || {};
    var StickyEls = [];

    Sticky = (function() {

        function Sticky(element, settings) {

            var _ = this, dataSettings;

            _.defaults = {
            	container : element,
				parent: '',
				parentWidth: false,
            	fixed : 'all',
            	fixedAt : 'all',
				fixedTil: '', // Tiago -> if .all_stick = colado ao topo, e dar zero, senao o js passa-se todo. //adicionar a class!
				position:'top',
				hideScroll: {
					desktop: false,
					mobile: false,
				},
				appearAt: 0,
				shrinkAt: 0,
				shrinkTo: 950, 
            	metaBase:'',
            	metatags:false,
				anchors : '',
				anchorsAll : false, //Tiago -> Se queremos ficar com a class "sel" antes e depois do "scroll" ter passado pela seccção, basta nao chamar isto
				activeClass: 'sel',
				line:false,
				watchHeight: 1,
            };

            _.initials = {
            	stickyCont : '',
            	elActive : '',
            	elId : '',
            	newTop : 0,
            	currentTop : 0,
            	previousTop : 0,
            };
			
            dataSettings = $(element).data('sticky', _) || {};

			$.extend(_, _.initials);

            _.options = $.extend({}, _.defaults, dataSettings, settings);
			
			_.init = $.proxy(_.init, _);
			_.destroy = $.proxy(_.destroy, _);
			_.stickyContainer = $.proxy(_.stickyContainer, _);
			_.stick = $.proxy(_.stick, _);
			_.unstick = $.proxy(_.unstick, _);
			
            _.init();

        }
        return Sticky;

    }());
   	
   
    Sticky.prototype.init = function() {
        var _ = this;
        
        var id_str = $(_.options.container).attr('id');
		if(StickyEls.indexOf(id_str)<0) StickyEls.push(id_str);


        if($(_.options.container).length>0){
        	if($(_.options.container).hasClass('destroyed')){	
        		$(_.options.container).removeClass('destroyed');
        	}

			_.initials.elId = $(_.options.container).attr('id');

			var elHeight = $(_.options.container).outerHeight(true),
				elIndex = $(_.options.container).index('.to_sticky'),
				stickyAnchors = $(_.options.anchors);

			if(_.initials.elId){
				_.stickyContainer();

				if(_.options.metatags){
					_.createMetas();
				}

				//CREATE AND HANDLE CLICKS
				if(stickyAnchors.length>0){

					stickyAnchors.on( "click.sticky", function(e) {
						e.preventDefault();
						
						if($(this).attr('data-anchor')){
							var data_anchor = $(this).attr('data-anchor');
							_.anchorHandler('#'+data_anchor);
						}
					});
				}

				var $window = $(window);
				if($(_.options.container).parents('#details-modal').length>0){
					$window = $(_.options.container).parents('.detail-ajax');
				}

				var scrollTimer = 0;
				var scrollTimer2 = 0;

				_.checkParent();

				if(_.options.watchHeight==1) $(_.options.container).addClass('stickyHeight');
				
				//HANDLE SCROLL
				$window.on('scroll.stickyScroll', function() {
					//HANDLE SCROLL POSITION	
					if(!$(_.options.container).hasClass('destroyed') && $(_.initials.stickyCont).length>0){	
						if(_.options.position=="top"){
							_.initials.newTop = 0;
							_.initials.currentTop = $(this).scrollTop();
						
							var contOffset = $(_.initials.stickyCont).offset().top;
							var contOffset2 = $(_.initials.stickyCont).offset().top + $(_.initials.stickyCont).height();

							if(StickyEls.length>0){	
								_.getTopOffset();
							}
							
								
							var isSticky = _.isSticky(_.options.fixed);
							var fixedTil = _.options.fixedTil;		

							var hideScrollActive = 0;
							if(_.options.hideScroll.desktop && $('body').innerWidth()>950){
								hideScrollActive = 1;
							}
							if(_.options.hideScroll.mobile && $('body').innerWidth()<=950){
								hideScrollActive = 1;
							}

							if(hideScrollActive==1){
								$(_.options.container).addClass('hideScroll');
								if (_.initials.currentTop < _.initials.previousTop && (isSticky)) {
									if (_.initials.currentTop > contOffset2 && $(_.options.container).hasClass('is-fixed')) {
										scrollTimer2 = setTimeout(function(){
											$(_.options.container).addClass('is-visible');
										}, 500);
									}else{
										$(_.options.container).removeClass('is-visible is-fixed');
										clearTimeout(scrollTimer2);
									}
								}else{
									clearTimeout(scrollTimer2);
									$(_.options.container).removeClass('is-visible');
									if( _.initials.currentTop > contOffset2 && !$(_.options.container).hasClass('is-fixed') && (isSticky)) $(_.options.container).addClass('is-fixed');
								}
							}else{
								$(_.options.container).removeClass('is-visible is-fixed');
							}

							_.checkParent();
																									
							if ((_.initials.currentTop >= contOffset) && (isSticky) ) {
								$(_.options.container).addClass('is-sticky').css('top', _.initials.newTop);
								_.stick();
								
								if (_.options.shrinkTo < $('body').innerWidth()){

									if(_.options.shrinkAt > 0 && _.initials.currentTop > _.options.shrinkAt){
										$(_.options.container).addClass('shrinked');
									}else{
										$(_.options.container).removeClass('shrinked');
									}
								}

								if(fixedTil && $(fixedTil).length>0){
									var maxWidth = _.options.fixedAt;
									if(_.options.fixedAt == "all") maxWidth = 0;
									if($('body').innerWidth()>maxWidth){		
										var elementHeight = $(_.options.container).outerHeight(true),
										parentHeight = $(_.options.fixedTil).outerHeight(true),
										top = $(_.options.fixedTil).offset().top,
										height = parentHeight - elementHeight;
																								
										if (_.options.fixedTil == '.all_stick'){ //verificar comportamento no noticias_detalhe
											top = 0;
										}
					
										if(parentHeight > elementHeight){
											if( top <= _.initials.currentTop && top + height > _.initials.currentTop ) {
												$(_.options.container).removeClass('is-absolute').addClass('is-sticky');
											} else if( top + height <= _.initials.currentTop) {
												var delta = top + height - _.initials.currentTop;
												$(_.options.container).removeClass('is-sticky').addClass('is-absolute');
											} else { 
												_.unstick();
											}
										}else{
											_.unstick();
										}
									}else{
										_.unstick();
									}						
								}
							} else {
								_.unstick();
							}

							_.initials.previousTop = $(this).scrollTop();
						}else{			
							$(_.options.container).css('top', 'auto');
						
							_.initials.newTop = 0;
							_.initials.currentTop = $(this).scrollTop();

							if(StickyEls.length>0){	
								_.getTopOffset();
							}

							var contOffset = $(_.initials.stickyCont).offset().top;
							var isSticky = _.isSticky(_.options.fixed);

							if($(_.options.container).parents('#details-modal').length>0){
								contOffset = $(_.initials.stickyCont).position().top - $(_.initials.stickyCont).height();
							}						
		
							if (($(this).scrollTop() >= _.options.appearAt) && (isSticky) && ((_.initials.currentTop + $window.height() - $(_.initials.stickyCont).height()) < contOffset)) {
								$(_.options.container).addClass('is-sticky').css('bottom', _.initials.newTop);
								$(_.options.container).css('margin-bottom', "0px");
								_.stick();
							} else {
								if ((_.initials.currentTop + $window.height() - $(_.initials.stickyCont).outerHeight(true)) < contOffset) {
									//$(_.options.container).css('margin-bottom', "-"+$(_.initials.stickyCont).height()+"px");
								}
								
								_.unstick();	
							}
						}
					}
				});
				
				$window.on('scroll.stickyScroll2', function() {
					//HANDLE SCROLL FOR ANCHORS POSITION
					if(!$(_.options.container).hasClass('destroyed')){	
						if(stickyAnchors.length>0){
							var findSection = 0;
							stickyAnchors.each(function(a_index, a_el) {
								var data_anchor = $(a_el).attr('data-anchor'),
									anchor_el = $('#'+data_anchor);
								
								if(anchor_el.length>0){
									//var el_top = anchor_el.offset().top - _.getAltStickies() - 50;
									//especifico tiago -> troquei aquele valor ao fim para ser mais "lento" a adicionar a class "sel"
									var el_top = anchor_el.offset().top - _.getAltStickies() + 150;
									var el_bottom = el_top + anchor_el.outerHeight(true);
											
									if((_.initials.currentTop>=el_top) && (_.initials.currentTop<el_bottom) && $(a_el)!=_.initials.elActive){
										console.log(el_top);
										$(_.options.container).find('.'+_.options.activeClass).removeClass(_.options.activeClass);		

										_.initials.elActive = $(a_el);
										$(a_el).addClass(_.options.activeClass);
										
										if(_.options.metatags){
											_.updateMetas($(a_el).attr('data-meta'));
										}	

										if(_.options.line && $(_.options.line).length>0){
											_.updateLine();
										}	

										findSection = 1;
										return;
									}
								}
							});

							//especifico tiago -> Se queremos ficar com a class "sel" antes e depois do "scroll" ter passado pela seccção, basta nao chamar isto
							if(findSection==0  && _.initials.anchorsAll){
								$(_.options.container).find('.'+_.options.activeClass).removeClass(_.options.activeClass);		
								_.initials.elActive = '';

								if(_.options.metatags){
									_.updateMetas(0);
								}	

								if(_.options.line && $(_.options.line).length>0){
									_.updateLine();
								}	
							}

						}
					}
				});
				
				
				
				//HANDLE RESIZE
				//$window.on('resize', function () {		
				$(window).resized(function() {		
					if(!$(_.options.container).hasClass('destroyed')){	
						if(!$(_.options.container).hasClass('vertical')){
							_.stickyContainer();
						}
						if(_.options.line && $(_.options.line).length>0){
							_.updateLine();
						}	
					}
				});


				if(!$(_.options.container).hasClass('destroyed') && _.options.metatags){
					_.findAtive();
				}
			}
		}
    };

	Sticky.prototype.stick = function() {
		var _ = this;
		
		_.stickyContainer();
	};
	Sticky.prototype.unstick = function() {
		var _ = this;
		
		$(_.options.container).removeClass('is-sticky is-absolute is-visible is-fixed hideScroll').css({
			'bottom': 'auto',
			'top': 'auto'
		});
		_.stickyContainer();
	};

	

    Sticky.prototype.stickyContainer = function() {
    	var _ = this;

    	if(!$(_.options.container).hasClass('destroyed')){	
			if(_.options.parent=="" || $(_.options.parent).length==0){
				if(!$(_.options.container).hasClass('shrinked')){
					var newHeight = $(_.options.container).outerHeight(true);
					_.initials.stickyCont = $('#sticky_cont_'+_.initials.elId);
					
					if(_.initials.stickyCont.length>0){
						// NTG: PROBLEMA DOS BREADS ESCONDIDOS v.2
						if (_.initials.stickyCont.height() < newHeight ){
							_.initials.stickyCont.css('height', newHeight+"px");
						}
					}else{
						$(_.options.container).wrap( "<div class='div_100 sticky_cont' id='sticky_cont_"+_.initials.elId+"'></div>" );
						_.initials.stickyCont = $('#sticky_cont_'+_.initials.elId);
						_.initials.stickyCont.css('height', (newHeight+"px"));
					}
					// NTG: PROBLEMA DOS BREADS ESCONDIDOS v.1
					//$(window).trigger('resize'); window.dispatchEvent(new Event('resize'));
				}
			}else{
				_.initials.stickyCont = $(_.options.parent);
			}
			
			
			if(_.options.parentWidth==true){
				$(_.options.container).width($(_.options.container).parent().width());
			}
		}
	};

	  Sticky.prototype.checkParent = function() {
    	var _ = this;

		var contOffset = ($(_.initials.stickyCont).offset().top + $(_.initials.stickyCont).height()) - $(_.options.container).outerHeight();
		if(_.options.parent!="" && $(_.options.parent).length>0){
			if (_.initials.currentTop > contOffset && $(_.options.container).hasClass('is-sticky')) {
				$(_.options.container).addClass('afterParent');
			}else{
				if($(_.options.container).hasClass('afterParent')) $(_.options.container).removeClass('afterParent');
			}
		}else{
			if(!$(_.options.container).hasClass('afterParent')) $(_.options.container).addClass('afterParent');
		}
	}
	
	Sticky.prototype.updateLine = function() {
		var _ = this;
		var $line = $(_.options.line);

		if($(_.options.line, _.options.container).length>0){
			$(_.options.line).addClass('sticky-line');
			if($(_.options.container).find('.'+_.options.activeClass).length>0){
				var offset_left = parseInt($(_.options.container).find('.'+_.options.activeClass).position().left) + parseInt($(_.options.container).find('.'+_.options.activeClass).css('margin-left'));
				var width = $(_.options.container).find('.'+_.options.activeClass).width();
				
				$(_.options.line).addClass('active');
				$(_.options.line).css({
					'left': offset_left+"px",
					'width': width+"px"
				});
			}else{
				$(_.options.line).removeClass('active');
			}
		}
	};

	Sticky.prototype.anchorHandler = function(obj) {
		var _ = this;
			
		var stickys = 0;
		if(StickyEls.length>0){
			stickys = 1;
		}
	
		if(stickys==1){
			var offset = $(obj).offset().top - _.getAltStickies();
			$('html, body').stop(true, true).animate({
				scrollTop: offset
			}, 1500);
		}else{
			$('html, body').stop(true, true).animate({
				scrollTop: $(obj).offset().top
			}, 1500);
		}
	};


	Sticky.prototype.findAtive = function() {
		var _ = this;

		var url = location.href;
		url = url.split("/");

		var a_el = $(_.options.anchors+'[data-anchor="'+url[url.length-1]+'"]');		
		if(a_el.length>0){
			a_el.click();
		}
	};

	Sticky.prototype.createMetas = function() {
		var _ = this;

		if($('#meta_tit').length<=0){
			var url = $('<input type="hidden" name="meta_url" id="meta_url" value="" />').val(_.options.metaBase).appendTo('body');
            var title = $('<input type="hidden" name="meta_tit" id="meta_tit" value="" />').val(document.title).appendTo('body');
            var desc = $('<input type="hidden" name="meta_desc" id="meta_desc" value="" />').val($('meta[name=description]').attr('content')).appendTo('body');
            var key = $('<input type="hidden" name="meta_key" id="meta_key" value="" />').val($('meta[name=keywords]').attr('content')).appendTo('body');
		}
	};

	Sticky.prototype.updateMetas = function(meta) {
		var _ = this;
		

		if(meta>0){
			$.post(_includes_path+"rpc.php", {op:"carrega_meta", id:meta}, function(data2){		
				var meta = data2.split("__")
				var title=meta[0];
				var description=meta[1];
				var keywords=meta[2];
				var url=meta[3];
				
				document.title = title;
				$('meta[name=description]').attr('content', description);
				$('meta[name=keywords]').attr('content', keywords);
				window.history.replaceState('Object', document.title, url);
			});
		}else{
			if($('#meta_tit').length>0){
				document.title = $('#meta_tit').val();
				$('meta[name=description]').attr('content', $('#meta_desc').val());
				$('meta[name=keywords]').attr('content', $('#meta_key').val());
				window.history.replaceState('Object', document.title, $('#meta_url').val());
			}
		}

	};	

	Sticky.prototype.getTopOffset = function() {
		var _ = this;
		
		var elIndex = $(_.options.container).index('.to_sticky');

		$.each(StickyEls, function(index, stickEl) {
			var $element = $('#'+stickEl);

			var opts = $element.data('sticky');
			var position = "top";

			if(typeof opts != "undefined"){
				position = opts.options.position;
			}

			if($element.get(0) != $(_.options.container).get(0)){
				if(_.options.position=="top"){
					if($element.hasClass('stickyHeight') && (typeof position == "undefined" || position == "top") && (($element.css('position')==="fixed" && $element.hasClass('is-visible')) || ($element.css('position')==="fixed" && !$element.hasClass('vertical') && !$element.hasClass('hideScroll')))){
						if($element.index('.to_sticky') < elIndex){
							_.initials.currentTop += $element.outerHeight(true);
							_.initials.newTop += $element.outerHeight(true);
						}
					}
				}else{
					var $if = position == "bottom" && ($element.index('.to_sticky') > elIndex);
					if($(_.options.container).parents('.detail-ajax').length>0){
						$if = position == "bottom" && (($element.index('.to_sticky') > elIndex) || $element.parents('.page-main').length==0);
					}
					if($if){
						_.initials.currentTop += $element.outerHeight(true);
						_.initials.newTop += $element.outerHeight(true);
					}
				}
			}
		});
	};

	Sticky.prototype.getAltStickies = function() {
		var _ = this;
		var alt_menu = 0;

		$(StickyEls).each(function( index, stickEl ){
			stickEl = "#"+stickEl;
			
			if(!$(stickEl).hasClass('vertical')){
				var fixedIndex = $(stickEl).data('sticky').options.fixed;
				var isSticky = _.isSticky(fixedIndex);
				
				if(isSticky){
					alt_menu += $(stickEl).outerHeight(true);
				}
			}
		});

		return alt_menu;
	};


	Sticky.prototype.isSticky = function(offsetTop) {
		var _ = this;

		var atualIndex = $('body').innerWidth();

		if(offsetTop >= atualIndex || offsetTop === "all"){
			return true;
		}else{
			return false;
		}
	};

	Sticky.prototype.destroy = function() {
		var _ = this;
			
		_.unstick();

		$(_.options.container).addClass('destroyed');

		var id_str = $(_.options.container).attr('id');
		if(StickyEls.indexOf(id_str)>=0) StickyEls.splice(StickyEls.indexOf(id_str), 1);

		if($('#sticky_cont_'+_.initials.elId).length>0){ 
			$('#sticky_cont_'+_.initials.elId).replaceWith($('#sticky_cont_'+_.initials.elId).contents());
		}

		$(_.options.container).removeClass('is-sticky afterParent shrinked');

		//CREATE AND HANDLE CLICKS
		if($(_.options.anchors).length>0){
			$(_.options.anchors).off( "click.sticky");
		}

		$(window).off('scroll.stickyScroll');
		$(window).off('scroll.stickyScroll2');

        $(_.options.container).off("Sticky");
        $(_.options.container).unbind();
        $(_.options.container).removeData();
	    
	};

    $.fn.sticky = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].sticky = new Sticky(_[i], opt);
            else
                ret = _[i].sticky[opt].apply(_[i].sticky, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

})( jQuery, window, document );