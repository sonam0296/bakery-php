/*
 Version: 1.0.2
 Author: Netgócio
 Website: http://netgocio.pt
*/

/**
 * --------------------------------------------------------------------------------------------------------------------------------
 * Exemplo de aplicação do component
 * @param elemento local onde deve ser inicializado
 * --------------------------------------------------------------------------------------------------------------------------------

if($(elemento).length>0){
    $(elemento).productsList({
        divs: '.listagem_divs', // class de cada elemento, vai ser utilizado para contar o resultado de rpc
        toggles : { // class selectoras dos filtros
            openBtn: '.filtersToggle',
            closeBtn: '.close-button',
            filterBtn: '.btnFilters',
            element: '.listings_filters',
        },
        toFixed : {  // class para tornar os filtros fixos em certa resolução ou local
            parent: '.listings_filters_bg',
            element: '.listings_filters_content',
            breakpoint: 1150,       
        },
        filters : { // todas as configurações utlizada no filtros
            parent : '#filtros_rpc',
            groups : 'filters_divs',
            elements : 'loja_inpt',
            has_url : 'has-url',
            accordions: {
                elements: 'hidden_filters',
                buttons: 'hidden_filters_btn',
                openTxt: $recursos['ver_todos'],
                closeTxt: $recursos['ver_menos'],
                limit: 10,
            },
        },
        urlBase: 'produtos.php', // url base da listagem
        fileRpc: _includes_path+'produtos-list.php', // ficheiro onde são feitos o pedidos de dados
        breadcrumbs: '', // class selector das breadcrumbs
        banners: '',// class selector das banners
        navigation: false, 
        threshold : { //configurações gerais do botão carregar mais produtos
            class:'button-big invert3', //class de estilo para o btn
            btn : 'ias_trigger',
            text : $recursos['carregar_mais'],
            limit : 2,
        },
        createUrl: true, 
        loader: $('.listing_mask'),
        limit: 12, //limit de produtos apresentar
        extraFields: [
            {data: $('#data').val()},
            {concelho: $('#concelho').val()}
            {freguesia: $('#freguesia').val()}
        ],
        callbacks: function(){ //função chamada no final da execução de um pedido
            init_fades();
            initLazyLoad('.listings_divs');
            $( ".mainDiv" ).cart('initBtnClick');

            //$('#meta_url').val(document.location.href);
            if($initAtive==1){
                $initAtive=0;

                if($($prod_rpc).attr('data-willopen')){
                    var $elementOpen = $('a[href*="'+$($prod_rpc).attr('data-willopen')+'"]');

                    if($elementOpen.length>0 && $elementOpen.attr('data-detail')==1){
                        $elementOpen.click();
                    }  
                }
            }              
        },
        callbacksFiltros: function(){
            initCalendar();
        },
    });
 */

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
    var productsList = window.productsList || {};

    productsList = (function() {

        function productsList(element, settings) {

            var _ = this;

            _.defaults = {
				container : element,
				divs : '.listagem_divs',
				toggles : {
					openBtn: '.filtersToggle',
					closeBtn: '.filtersHead',
					filterBtn: '.btnFilters',
					element: '.listings_filters',
				},
				filters : {
					parent : '#filtros_rpc',
					groups : 'loja_filt_divs',
	                elements : 'loja_inpt',
	                goTo:'.mainDiv',
	                has_url : 'has-url',
					accordions: {
						elements: 'hidden_filters',
						buttons: 'hidden_filters_btn',
						openTxt: "Ver todos",
						closeTxt: "Ver menos",
						limit: 10,
					},
				},
                urlBase: 'loja',
                fileRpc: 'produtos-list.php',
                breadcrumbs: '.breadcrumbs',
                banners: '#banners',
                navigation: false,
				threshold : {
					class: 'button-big invert',
					btn : 'ias_trigger',
                	text : $recursos['carregar_mais'],
					limit : 3,
					appendTo: '',
				},
				thresholdCounter : 0,
				createUrl: true,
				loader: $('.listing_mask'),
				limit: 12,
				compare: null,
				callbacks: function() {},
				callbacksFiltros: function() {},
				extraFields: new Array(),
            };

            _.initials = {
            	start : 1,
				no_data : true,
				flag : true,
				reached_end : 0,
				total_prods : 0,
				flagCat : 0,
				flagFiltros : 1,
				alterei_url : 0,
				isInit: 0,
            };
			
			$.extend(_, _.initials);
            _.options = $.extend({}, _.defaults, settings);
			_.originalSettings = _.options;
			
			_.first = 1;
			_.limitValue = _.first * _.options.limit;
			
			var param = _.getParam('p');
			if(param>0){
				_.limitValue = param * _.options.limit;
			}

			if(!$(element).hasClass('onlyFilters')){
            	_.init();
            }else{
	            _.initFilters();
	        }

        }
        return productsList;

    }());
   	
   	productsList.prototype.init = function() {
        var _ = this;
		
        if(_.options.filters){
        	if(_.options.filters.has_url){
				_.inputUrlHandle();			
	    	}
			
			if(_.options.toggles.filterBtn){		
				$(_.options.toggles.filterBtn).on('click', function(){
					_.buttonHandle();
				});
			}	
			
			_.openFilters();
			
			_.handles(0, 0);
        	_.loadFilters();
        	_.breadHandle();
        	_.loadBanners();	
        	_.loadNavigation();	
    	}else{
    		_.handles(0, 0);
    	}    	
		
		window.dispatchEvent(new Event('resize'));

		document.addEventListener('scroll', function(){
			_.scrollHandle();
		});
    };

    productsList.prototype.initFilters = function() {
        var _ = this;
		
        if(_.options.filters){
        	if(_.options.filters.has_url){
				_.inputUrlHandle();			
	    	}
			
			if(_.options.toggles.filterBtn){		
				$(_.options.toggles.filterBtn).on('click', function(){
					_.buttonHandle();
				});
			}
			
			_.openFilters();
        	_.loadFilters();
    	}   	

		window.dispatchEvent(new Event('resize'));
    };

	productsList.prototype.openFilters = function() {
		var _ = this;

		if($(_.options.toggles.element).length>0){
			$(_.options.toggles.openBtn).on('click', function(){
				$('body').addClass('overHidden-scrollLoad');
				
				if($('body').innerWidth()<=950){
					$('.page-main').addClass('aboveAll-filter');
				}

				$(_.options.toggles.element).addClass('active');
			});
			
			$(_.options.toggles.closeBtn).on('click',function() {
				if($(_.options.toggles.element).hasClass('active')){
					$('body').removeClass('overHidden-scrollLoad');
					
					if($('body').innerWidth()<=950 && $('.page-main').hasClass('aboveAll-filter')){
						$('.page-main').removeClass('aboveAll-filter');
					}

					$(_.options.toggles.element).removeClass('active');
				}
			});
			
			$(document).keyup(function(e) {
				if (e.keyCode === 27) { // escape key maps to keycode `27`
					if($("[native-window-container][native-window-opened]").length>0){
						$("[native-window-container][native-window-opened]").find('[native-window-cancel]').trigger('click');
					}

					if($(_.options.toggles.element).hasClass('active')){
						$('body').removeClass('overHidden-scrollLoad');
						
						if($('body').innerWidth()<=950 && $('.page-main').hasClass('aboveAll-filter')){
							$('.page-main').removeClass('aboveAll-filter');
						}

						$(_.options.toggles.element).removeClass('active');
					}
				}
			});
		}
	};

    productsList.prototype.inputUrlHandle = function() {
        var _ = this;

		$('a.'+_.options.filters.has_url).on('click', function(e) {	
			if(!$(this).hasClass('refresh')){ 
				e.preventDefault();
				_.inputUrlFunction($(this));
			}
		});
		$('input.'+_.options.filters.has_url).on('change', function(e) {	
			if($(this).is(':checked')){
				_.inputUrlFunction($(this));
			}else{
				if($(this).parents('ul').prev('a.type2').length>0) $(this).parents('ul').prev('a.type2').click();
			}
		});
    };
    
    productsList.prototype.inputUrlFunction = function($element) {
        var _ = this;

        _.initials.flagCat=1;
       	
   		if($element.hasClass('type1')){
			$('.type1.active').not($element).removeClass('active');
		}
		if($element.hasClass('type1') || $element.hasClass('type2')){
			$('.type2.active').not($element).removeClass('active');
		}
		if($element.hasClass('type1') || $element.hasClass('type2') || $element.hasClass('type3')){
			$('.type3:checked').not($element).prop('checked', false);
		}

		if(!$element.hasClass('type3')){
			$element.addClass('active');	
		}	
			
		$('#filtros_rpc input:checked').prop('checked', false);

		//DAVIDE
		$('#categoria').val($element.attr('data-id'));
		$('#catmae').val($element.attr('data-catmae'));
		$('#submae').val($element.attr('data-submae'));

		if($('[data-sel="'+$element.attr('href')+'"]').length>0){
			$('#headerLinks a').removeClass('active current');
			$('a[data-sel="'+$element.attr('href')+'"]').addClass('active');
		}
		
		if(($('body').innerWidth()>950 && _.options.toggles.filterBtn && $(_.options.toggles.filterBtn).length>0) || !_.options.toggles.filterBtn){
			_.initials.start = 1;
			_.initials.no_data = true;
			_.first = 1;
			_.initials.reached_end = 0;
			_.initials.total_prods = 0;
			_.options.thresholdCounter = 0;
			
			_.initials.flagFiltros=0;

			_.handles(1, 0);
			_.loadFilters();
			_.loadBanners();
			_.loadNavigation();	
			_.breadHandle();
			_.metatagsHandle();
		}
    };

    productsList.prototype.inputHandle = function() {
        var _ = this;
        
        var $filter = "";
				
		if(_.options.filters.has_url){
			$filter = $('.'+_.options.filters.elements).not('.'+_.options.filters.has_url);
		}else{
			$filter = $('.'+_.options.filters.elements);
		}
		
		$filter.on('change', function() {			
			_.initials.flagCat=0;
			
			if($(this).attr('data-replace') == 1){
				_.clearFilters($(this), $(this).attr('name'));	
			}
			
			if(($('body').innerWidth()>950 && _.options.toggles.filterBtn && $(_.options.toggles.filterBtn).length>0) || !_.options.toggles.filterBtn){
				_.initials.start = 1;
				_.initials.no_data = true;
				_.first = 1;
				_.initials.reached_end = 0;
				_.initials.total_prods = 0;
				_.options.thresholdCounter = 0;
				
				_.initials.flagFiltros=0;

				_.handles(1, 0);
			}
		});
    };
	productsList.prototype.buttonHandle = function() {
        var _ = this;

        _.initials.start = 1;
		_.initials.no_data = true;
		_.first = 1;
		_.initials.reached_end = 0;
		_.initials.total_prods = 0;
		_.options.thresholdCounter = 0;
		
		_.initials.flagFiltros=0;


		if($(_.options.toggles.filterBtn).length>0 && $(_.options.toggles.filterBtn).hasClass('returnBase')){
			_.initials.isInit=2;
			_.verificaFilters(1);
		}else{
			_.handles(1, 0);
			
			_.loadFilters();
			_.loadBanners();
			_.loadNavigation();	
			_.breadHandle();
			_.metatagsHandle();
		}

		$(_.options.toggles.closeBtn).click();
    };

    productsList.prototype.metatagsHandle = function() {
    	var _ = this;
    	
    	var id = $('#categoria').val();
    	var meta_url = _.initials.alterei_url;

    	if(id>0 && _.initials.flagCat==1){
			var metaData = new FormData();
			metaData.append("op", "carrega_meta");
			metaData.append("id", id);
					
			if(_.options.extraFields.length>0){
				$.each( _.options.extraFields, function( i, field ) {
					Object.keys(field).map(function (key) { 
						metaData.append(key, field[key]);
					});
				});
			}			
			
			$.ajax({
				url: _.options.fileRpc,
				data: metaData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function (data2) {
					var meta = data2.split("__");
					var title=meta[0];
					var description=meta[1];
					var keywords=meta[2];
					var url_meta=meta[3];				
						
					document.title = title;
					$('meta[name=description]').attr('content', description);
					$('meta[name=keywords]').attr('content', keywords);
					window.history.replaceState('Object', document.title, url_meta);

					if(typeof ga != 'undefined'){
						window.setTimeout(function(){
				        var d = location.pathname;
				        ga('set', { page: d, title: document.title });
				        ga('send', 'pageview');
				    }, 200);
					}
				}
			});
		}else if(meta_url){
			window.history.replaceState('Object', document.title, meta_url);

			if(typeof ga != 'undefined'){
				window.setTimeout(function(){
		        var d = location.pathname;
		        ga('set', { page: d, title: document.title });
		        ga('send', 'pageview');
		    }, 200);
			}
		}		
    }; 

    productsList.prototype.breadHandle = function() {
    	var _ = this;
    	
    	if(_.options.breadcrumbs && $(_.options.breadcrumbs).length>0){
			var id = $('#categoria').val();
    		var catmae = $('#catmae').val();
			var submae = $('#submae').val();

	    	var breadData = new FormData();
			breadData.append("op", "carrega_bread");
			breadData.append("id", id);
			breadData.append("catmae", catmae);
			breadData.append("submae", submae);
					
			if(_.options.extraFields.length>0){
				$.each( _.options.extraFields, function( i, field ) {
					Object.keys(field).map(function (key) { 
						breadData.append(key, field[key]);
					});
				});
			}			
			
			$.ajax({
				url: _.options.fileRpc,
				data: breadData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function (data) {
					$(_.options.breadcrumbs).html(data);
				}
			});
		}
    };    

    productsList.prototype.scrollHandle = function() {
        var _ = this;
        if(_.initials.reached_end===0){
			if($(_.options.divs).length>0){
				var el = $(_.options.container).find(_.options.divs).last();				
				var treshold = el.offset().top + el.height();	
				var curScrOffset = $(window).scrollTop() + $(window).height();

				if(el.length>0 && curScrOffset >= treshold){
					if(_.options.threshold){
						if(_.options.thresholdCounter<_.options.threshold.limit){
							_.handles(0, 1);     
						}else{
							if($('.'+_.options.threshold.btn).length===0){
								var btn = document.createElement("a");

								$(btn)
								.addClass(_.options.threshold.btn)
								.addClass(_.options.threshold.class)
								.html(_.options.threshold.text);

								if(_.options.threshold.appendTo && $(_.options.threshold.appendTo).length>0){
									$(btn).appendTo(_.options.threshold.appendTo);
								}else{
									$(btn).insertAfter(_.options.container);
								}
								
								 
								$(btn).click(function(){
									_.handles(0, 1);
									$(this).remove();
								});
							}
						}
					}else{
						_.handles(0, 1);
					}
				}
			}else{
				if(_.initials.flagCat===0){
	        		_.handles(0, 1);
	        	}
			}
		}
    };


	productsList.prototype.handles = function(filtro, cameScroll) {
        var _ = this;
		_.initials.no_data = true;
		if(_.initials.flag && _.initials.no_data){
			if(cameScroll==0){
				_.initials.alterei_url = _.verificaFilters(1);
			}

			_.filtra(filtro);
		}
    };

    
    productsList.prototype.clearFilters = function(input, name) {
    	var _ = this;

    	if(input) $('[name="'+name+'"]').not(input).prop('checked', false);
    	else $('[name="'+name+'"]').prop('checked', false);
    };

    productsList.prototype.loadFilters = function() {
    	var _ = this;
		var url_final = _.initials.alterei_url;

        if(_.options.filters.parent && $(_.options.filters.parent).length>0){

			var id = $('#categoria').val();
			var catmae = $('#catmae').val();
			var submae = $('#submae').val();
			
			var filtrosData = new FormData();
			filtrosData.append("op", "filtros");
			filtrosData.append("id", id);
			filtrosData.append("catmae", catmae);
			filtrosData.append("submae", submae);
			filtrosData.append("url", url_final);

			if(typeof _.options.filters.accordions.limit != "undefined" && _.options.filters.accordions.limit>0){
				filtrosData.append("accordions", _.options.filters.accordions.limit);
			}
					
			if(_.options.extraFields.length>0){
				$.each( _.options.extraFields, function( i, field ) {
					Object.keys(field).map(function (key) { 
						filtrosData.append(key, field[key]);
					});
				});
			}			
			
			$.ajax({
				url: _.options.fileRpc,
				data: filtrosData,
				processData: false,
				contentType: false,
		        type: 'POST',
				success: function (data) {
					$(_.options.filters.parent).html(data);	
					_.options.callbacksFiltros();
					_.inputHandle();	
					_.accordions();
				}
			});			
			
		}else{
			_.inputHandle();
			_.handles(0);
		}
    };
    productsList.prototype.loadBanners = function() {
    	var _ = this;

    	if($(_.options.banners).length>0){      
            var id = $('#categoria').val();

            $.post(_includes_path+"produtos-list.php", {op:"carregaBanners", id:id}, function(data){
                $(_.options.banners).html(data);

                if($(_.options.banners).find('.slick-banners').length>0){                                                            
                    $(_.options.banners).find('.slick-banners').on('init', function(event, slick, currentSlide, nextSlide){       
                        var bgss = new bgsrcset();
                        bgss.callonce = false;
                        bgss.init('.banners_slide');
                    });
                    $(_.options.banners).find('.slick-banners').slick({
                        dots:true,
                        slidesToShow:1,
                        slidesToScroll:1,
                        arrows:true,
                        prevArrow: $('.banner_arrows.prev'),
                        nextArrow: $('.banner_arrows.next'),
                        infinite: false,
                        adaptiveHeight: false,
                        fade: true,
                        cssEase: 'linear',   
                    });
                }else if($(_.options.banners).find('.banners_slide').length>0){       
                    var bgss = new bgsrcset();
                    bgss.callonce = false;
                    bgss.init('.banners_slide');
                }
            });
        } 
    };

    productsList.prototype.loadNavigation = function() {
    	var _ = this;
    	var id = $('#categoria').val();

    	if(_.options.navigation){      
    		if($('.listagem_nav').length>0){
    			$('.listagem_nav').fadeOut('slow', function(){
    				$('.listagem_nav').remove();

		            $.post(_includes_path+"produtos-list.php", {op:"carregaNavigation", id:id}, function(data){
		                $('.listings_divs').prepend($(data));                
		            });
    			});
    		}else{
    			$.post(_includes_path+"produtos-list.php", {op:"carregaNavigation", id:id}, function(data){
	                $('.listings_divs').prepend($(data));                
	            });
    		}
        } 
    };

    productsList.prototype.verificaFilters = function(atualiza) {
    	var _ = this;

		var url_final="";

		if(_.initials.isInit>1){
  			if(!_.options.filters.has_url || ($('.'+_.options.filters.has_url).length===0 && $('.'+_.options.filters.has_url+'.active').length===0 && $('.'+_.options.filters.has_url+':checked').length===0)){
	  			if($('#categoria').length>0){
		  			var id = $('#categoria').val();
		  			if(id == "-1") url_final = "novidades?";
		  			else if(id == "-2") url_final = "promocoes?";
		  			else url_final = _.options.urlBase+"?";	
		  		}else url_final = _.options.urlBase+"?";			
			}else{
				if($('.'+_.options.filters.has_url+':checked').length>0){
					url_final = $('.'+_.options.filters.has_url+':checked').attr('data-url')+"?";
				}else if($('.'+_.options.filters.has_url+'.active').length>0){
					url_final = $('.'+_.options.filters.has_url+'.active').last().attr('data-url')+"?";
				}else{
					url_final = _.options.urlBase+"?";
				}
			}

			    	
			if(_.options.filters.groups && _.options.filters.elements){				
				var $groups = $('.'+_.options.filters.groups);

				var groups_array = [];
				$groups.each(function(index, element){
					var $inputs = "";
					
					if(_.options.filters.has_url){
						$inputs = $(element).find('.'+_.options.filters.elements).not('.'+_.options.filters.has_url);
					}else{
						$inputs = $(element).find('.'+_.options.filters.elements);
					}	
					
					$inputs.each(function(){
						var $this = $(this);
						var $type = $(this).attr('type');
						var $name = $(this).attr('data-name');

						if($type == "radio") {
							if($this.is(':checked') ) {
								if($this.hasClass(_.options.filters.has_url)){
									url_final += $this.attr('href')+"?";
								}else{
									url_final += $name+"="+$this.val()+"&";
								}
							}
						}else if($type == "checkbox") {
							if(groups_array.indexOf($name)==-1){
								groups_array.push($name);
								var pags = "";
								$('input[data-name="'+$name+'"]').each(function() {
									if($(this).is(':checked')==true){
										if(pags=="") pags=$(this).val();
										else pags=pags+","+$(this).val();
									}
								});
								if(pags){
									url_final += $name+"="+pags+"&";
								}
							}
						} else if(($type == "number") && $this.val()>0){
							url_final += $name+"="+$this.val()+"&";
						} else if(($type == "text" || $type == "search") && $this.val()){
							if($name=="data" && $this.hasClass('datepicker')){
								url_final += $name+"="+$this.next('input').val()+"&";
							}else{
								url_final += $name+"="+$this.val()+"&";
							}
						} else if(typeof $type == "undefined" && $this.find(':selected').length>0){
							if($this.val()!="0" && $this.val()!="") url_final += $name+"="+$this.val()+"&";
						} else if(typeof $type == "undefined" && $this.find('.selected').length>0) {
							if($this.val()!="0" && $this.val()!="") url_final += $name+"="+$this.val()+"&";
						}else if($this.val()){
							url_final += $name+"="+$this.val()+"&";
						}
					});
				});						
			}

			if(_.first>1){
				url_final += "p="+_.first;
			}	

		}else{
			_.initials.isInit++;
			url_final = document.location.href.substr(document.location.href.lastIndexOf('/') + 1);
		}

		if(_.initials.isInit>1){
			if(_.options.createUrl && atualiza==1){
				if($('#menu_sel').length>0) $('#menu_sel').val(url_final);
				window.history.replaceState('Object', document.title, url_final);
				
				if($(_.options.toggles.filterBtn).length>0 && $(_.options.toggles.filterBtn).hasClass('returnBase')){
					window.location = url_final;
				}
			}
		}
		
		return url_final;
    };

    productsList.prototype.filtra = function(replaced) {
        var _ = this;

		var url_final = _.initials.alterei_url;
		
		if(_.options.loader.length>0){
			_.options.loader.fadeIn('slow');
			$(_.options.divs).addClass('opac');
		}
		
		_.initials.flag = false;

		var elementsData = new FormData();
		elementsData.append("op", "elementos");

		if($('#categoria').length>0){
			var id = $('#categoria').val();
			var catmae = $('#catmae').val();
			var submae = $('#submae').val();

			elementsData.append("id", id);
			elementsData.append("catmae", catmae);
			elementsData.append("submae", submae);
		}
		elementsData.append("url", url_final);
		elementsData.append("first", _.first);
		elementsData.append("limit", _.limitValue);
		elementsData.append("start", _.initials.start);

		if(_.options.extraFields.length>0){
			$.each( _.options.extraFields, function( i, field ) {
				Object.keys(field).map(function (key) { 
					elementsData.append(key, field[key]);
				});
			});
		}

		$.ajax({
			url : _.options.fileRpc,
			dataType: "html",
			method: 'POST',
			data: elementsData,
			processData: false,
			contentType: false,
			type: 'POST',
			success: function( data ) {
				_.initials.flag = true;

				if(_.options.loader.length>0){
					_.options.loader.fadeOut('slow');
					$(_.options.divs).removeClass('opac');
				}         
		
				var num_devolvidos = $(_.options.divs, $("<div/>").html(data)).length;
				if(num_devolvidos>0){
					
					if(replaced===1){    
						$(_.options.container).html(data);
						if($('.'+_.options.threshold.btn).length>0){
							$('.'+_.options.threshold.btn).remove();
						}

						goTo(_.options.filters.goTo);
					}else{
						$(data).appendTo(_.options.container);
					}
	
					_.initials.total_prods += parseInt(num_devolvidos);
	
					if((num_devolvidos<_.limitValue) || (_.initials.total_prods==$('#total_prods').val())){
						 _.initials.reached_end = 1;
					}
					
					if(_.limitValue != _.options.limit){
						_.first = parseInt(_.limitValue / _.options.limit);
						_.limitValue = _.options.limit;
						_.options.thresholdCounter=_.first;

						var el = $(_.options.container).find(_.options.divs).last();
						$('html, body').stop(true, true).animate({
							scrollTop: $(el).offset().top
						}, 1500,'easeInOutExpo', function() {});
					}else{
						_.first++;
						_.options.thresholdCounter++;
					}
													
					_.initials.start = 0;			

					/* PRODUTOS: para os produtos que tenham um tamanho por defeito, simular o click para mostrar as outras opções */
					if($('[data-attr="carrega_atributos"]').length > 0) {
		        $('select[data-attr="carrega_atributos"]').trigger('change');
		        $('input[data-attr="carrega_atributos"]').click();
			    }  			
				}else{
					if(_.initials.start===1 || replaced===1){
						$(_.options.container).html(data);
					}
					
					//console.log('No more data to show');
					_.initials.start = 0;
					_.initials.no_data = false;
					_.initials.reached_end = 1;
				}
				
				//_.initials.alterei_url=0;
				_.initials.alterei_url = _.verificaFilters(0);
								
				_.options.callbacks();
			},
			error: function(){
				_.initials.flag = true;
				_.initials.no_data = false;
				
				if(_.options.loader.length>0){
					_.options.loader.fadeOut('slow');
					$(_.options.divs).removeClass('opac');
				} 

				_.options.callbacks();
				//console.log('Something went wrong, Please contact admin');
			}
		});
    };

	productsList.prototype.accordions = function() {
		var _ = this;
		
		if($('.'+_.options.filters.groups).length>0){
			$('.'+_.options.filters.groups).each(function() {
				if($(this).find('.'+_.options.filters.accordions.elements).length>0){
					$(this).find('.'+_.options.filters.accordions.buttons).addClass('active');
				}
			});
			
			$('.'+_.options.filters.accordions.buttons).each(function() {
				var $el = $(this);
				var events = $._data($(this)[0], "events");
				var hasEvents = (events != null);
				
				if(!hasEvents){
					$($el).on('click', function(){
						
						if($($el).hasClass('opened')){
							$($el).prev('.'+_.options.filters.accordions.elements).slideUp('slow');
							$($el).text(_.options.filters.accordions.openTxt).removeClass('opened');
						}else{
							$($el).prev('.'+_.options.filters.accordions.elements).slideDown('slow');
							$($el).text(_.options.filters.accordions.closeTxt).addClass('opened');
						}
					});
				}
			});
		}
	};

    productsList.prototype.hasParam = function() {
    	var _ = this;

    	var url = window.location.href;
    	if(url.indexOf('?')>0){
    		var param = url.split('?');
	    	var iscat = param[0].indexOf(_.options.urlBase);
	    	var hasparam = param[1].indexOf('=');
	    	var is_p = _.getParam('p');


	    	if(iscat>0 && (!hasparam || is_p)){
	    		return "";
	    	}else{
	    		return "cenas";
	    	}	
    	}else{
    		return "";
    	}    	
    };
	
	
    productsList.prototype.getParam = function(name) {
		var url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
			
		if (!results){
			return null;
		}
		if (!results[2]){
			return '';
		}
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	};

    $.fn.productsList = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].productsList = new productsList(_[i], opt);
            else
                ret = _[i].productsList[opt].apply(_[i].productsList, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

}));