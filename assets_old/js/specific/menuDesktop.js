/*
 Version: 1
 Author: Netgócio
 Website: http://netgocio.pt
*/

var element_class = '.header_menu_drop';
var element = $(element_class);
var links_class = '.header_drop';
var links = $(links_class);
var wind_width = $(window).width();
var isToShow=false;
var menuSel;
var timeout;
var $headerEl = $('#header');
var action="hover";
var action_target="mouseover touchstart";
/*var action="click";
var action_target="click";*/

var max_height = 480;

if(element.length>0){
	if(links.length>0){
		links.on(action_target,function(e) {
			'use strict';

			e.preventDefault();
			
			isToShow=true;
			
			var tempo2=0; //para colocar lento colocar tempo2=400
			if(element.css('display') != "none"){
				tempo2=0;
			}
			
			menuSel=$(this);

			window.setTimeout(function(){
				if(isToShow==true){
					isToShow=false;
					if($(menuSel).attr('data-id')){
						var id = $(menuSel).attr('data-id');
						var el = $(menuSel);

						if(id != element.attr('data-open')){
							$.post(_includes_path+"rpc.php", {op:"carrega_menu", id:id}, function(data){
								if(data){
									if(element.css('display') != "none"){
										element.removeClass('opened');
									}

									$('body').addClass('overHidden');
									$('.mainDiv').addClass('has-overlay');
									links.removeClass('active');
									element.attr('data-open', id);
									el.addClass('active');

									if(element.css('display')=="none"){
										element.html(data);
										initLazyLoad(element_class);

										element.addClass('opened');										
										element.css('height', "auto");

										element.slideDown('fast', function(){
											setMenuHeight(element, max_height, 0);
										});
									}else{
										//setTimeout(function(){ para colocar lento tirar isto
											element.html(data);
											initLazyLoad(element_class);

											element.css('height', "auto");
											setMenuHeight(element, max_height, 0);
											element.addClass('opened');
										//}, 500);
									}

									initMenuLinks(element);
									$('a.active', element).trigger('mouseover');
								}else if(action == "click"){//se menu vazio, faz redirect apenas se action é click
									window.location = menuSel.attr('href');
								}else{ //fecha o menu
									escondeMenu();
								}
							});
							/*$.post(_includes_path+"rpc.php", {op:"carrega_menu_dests", id:id}, function(data){ //specific
								$('#menu_rpc_3').html(data);
								initLazyLoad('#menu_rpc_3');
							});*/
						}
					}
				}
			}, tempo2);
			
			clearTimeout(timeout);
		});
	}
	function initMenuLinks(parent){
		$('a', parent).on('mouseover touchstart', function(){
			var $this = $(this);
			var id = $this.attr('data-id');
			var subid = $this.attr('data-subid');

			$('a.active', parent).removeClass('active');
			$(this).addClass('active');

			initLazyLoad(parent);

			// $.post(_includes_path+"rpc.php", {op:"carrega_menu_cats", id:id, subid:subid}, function(data){
			// 	if(data){
			// 		$('#menu_rpc_2').html(data);
			// 	}
			// });
			// $.post(_includes_path+"rpc.php", {op:"carrega_menu_dests", id:id,}, function(data){ //specific
			// 	$('#menu_rpc_3').html(data);
			// 	initLazyLoad('#menu_rpc_3');
			// });
		});
		$('a:eq(0)', parent).trigger('mouseover');
	}

	function escondeMenu(){
		'use strict';
		clearTimeout(timeout);

		if(element.css('display') != "none"){
			links.removeClass('active');
			$('body').removeClass('overHidden');
			$('.mainDiv').removeClass('has-overlay');

			element.slideUp('fast').removeAttr('data-open');
			//adicionado Davide
			element.removeClass('opened');
		}
	}

	$(links_class+', '+element_class).on('mouseout',function() {
		'use strict';
		isToShow=false;
		timeout=setTimeout(function(){ escondeMenu(); },2000);
	});

	$(links_class+', '+element_class).on('mouseover touchstart',function() {
		'use strict';
		clearTimeout(timeout);
	});

	$('body').on('click',function(e) {
		'use strict';
		if($(e.target).closest(element_class).length == 0 && $(e.target).closest(links_class).length == 0){
			escondeMenu();
		}
	});	

	$(document).keyup(function(e) {
		if (e.keyCode == 27) { // escape key maps to keycode `27`
			if(element.attr('data-open')){
				escondeMenu();
			}
		}
	});


	/*RESIZE LOADS*/
	$(window).on('load', function(){
		'use strict';
		setMenuHeight(element, max_height, 1);
	});
}
