/*
 Version: 1
 Author: Netgócio
 Website: http://netgocio.pt
*/

(function($) {
    'use strict';
    var Cart = window.Cart || {};

    Cart = (function() {

        function Cart(element, settings) {

            var _ = this, dataSettings;
			
			_.defaults = {
				parent: 'body',
				class: 'cart',
				linkCart: 'carrinho.php',
				fileRpc: 'carrinho-rpc.php',
				position : 'cart-bottom', // TOP || BOTTOM
				trigger: '.cart-btn',
				menu_sel: '',
				modal : {
					openModal: 1, // ABRE MODAL OU ADICIONA DIRETO - '' || 1
					action: '.modal-action',
				},
				animation: 2, //animation 1 = com scroll || 2 = sem scroll
				breakpoint: 750,
				showPrices: false,
				elements : {
					divs: '.produtos_divs',
					image: '.produtos_divs_img',
					action: '.action',
					detalheBtn: '.detalhe_adiciona',
					detalheImg: '#div_imagem .slick-current',
				},
				appendTo : '', //No caso do TOP appendTo .header por exemplo
				animated:1, // '' || 1 - handles product animation to chart
				hasOverflow:'', // '' || 1 - remove scroll on open
				extraFields: new Array(),
				texts : {
					header: '<?php echo $Recursos->Resources["carrinho"]; ?>',
					footer: '<?php echo $Recursos->Resources["ir_carrinho"];?>',
					onAdd: '<?php echo $Recursos->Resources["stock_zero"]; ?>',
					delTitle: '<?php echo $Recursos->Resources["car_remover_artigo"];?>',
					delMsg: '<?php echo $Recursos->Resources["car_remover_artigo_txt"];?>',
					selecione: '<?php echo $Recursos->Resources["selecione"]; ?>',
					sucesso: '<?php echo $Recursos->Resources["selecione"]; ?>',
					ok: '<?php echo $Recursos->Resources["car_confimar"];?>',
					cancel: '<?php echo $Recursos->Resources["car_cancelar"];?>',
				},
				modalCallbacks: function() {},
			};
				
			_.initials = {
            	cartIsOpen : false,
				cardElement: '',
				cardHeader: '',
				cardBody: '',
				cardFooter: '',
				cardModal: '',
				prodsRpc: '',
				prodsAction: '',
				cartTimeout: '',
				cartTrigger: '.'+_.defaults.class+'-trigger',
            };
			
			$.extend(_, _.initials);
			
            dataSettings = $(element).data('cart', _) || {};
            
			_.options = $.extend({}, _.defaults, dataSettings, settings);
			
			_.initBtnClick = $.proxy(_.initBtnClick, _);
			_.alteraProducts = $.proxy(_.alteraProducts, _);
			_.removeProducts = $.proxy(_.removeProducts, _);
			
			
            _.init();

        }
        return Cart;
    }());
   	
   
    Cart.prototype.init = function() {
        var _ = this;

        _.initials.cartTrigger = '.'+_.options.class+'-trigger';

        _.initials.prodsAction = _.options.elements.divs+' '+_.options.elements.action;
        if(_.options.elements.divs == _.options.elements.action){
        	_.initials.prodsAction = _.options.elements.divs;
        }
		
		if(_.initials.cartTrigger && $(_.initials.cartTrigger).length>0){
			$(_.options.parent).addClass(_.options.position);
	
			$(_.initials.cartTrigger).click(function(e) {
				if( $('body').innerWidth() > _.options.breakpoint){
					e.preventDefault();
					_.handleTrigger();
				}
			});
						
			_.initBtnClick();
			
			if($(_.options.elements.detalheBtn).length>0){
				//add product to cart
				$(_.options.elements.detalheBtn).click(function(e) {
					e.preventDefault();
					_.handleAddButton($(this).attr('data-product'), $(this).attr('data-label'));
				});
			}
			
			/*CLOSE HELPERS*/
			$(document).keyup(function(e) {
				if (e.keyCode == 27) { // escape key maps to keycode `27`
					if(_.initials.cartIsOpen) {	
						_.handleTrigger();
						clearTimeout(_.initials.cartTimeout);
					}
				}
			});
			
			$('body').on('click touchstart',function(e) {
				if($(e.target).closest('.'+_.options.class).length == 0 && $(e.target).closest(_.initials.cartTrigger).length == 0 && $(e.target).closest(_.options.trigger).length == 0){
					if(_.initials.cartIsOpen) {	
						_.handleTrigger();
					}
				}
			});	
			
			
			/*CREATE CART*/
			_.createCart();
		}
    };
	
	Cart.prototype.initBtnClick = function() {
        var _ = this;

        $(_.options.trigger).off('click.openCart');
		$(_.options.trigger).on('click.openCart', function(e) {
			if( $('body').innerWidth() > _.options.breakpoint){
				e.preventDefault();
				$(_.initials.cartTrigger).click();
			}
		});
	
		if($(_.initials.prodsAction).length>0){
			$(_.initials.prodsAction).off('click.adicionaCarrinho');
			$(_.initials.prodsAction).on('click.adicionaCarrinho', function(e) {
				e.preventDefault();

				if(e.target === $('.pointerNull')[0] || $(e.target).parents('.pointerNull').length>0){
		            return;
		        }

				_.handleAddButton($(this).attr('data-product'), $(this).attr('data-label'));
			});
		}

		if(_.options.modal.openModal==1 && $(_.options.modal.action).length>0){
			$(_.options.modal.action).off('click.modalCarrinho');
			$(_.options.modal.action).on('click.modalCarrinho', function(e) {
				e.preventDefault();

				if($('body').innerWidth()> _.options.breakpoint){ //open modal for cart
					_.handleModalBtn($(this).attr('data-product'));
				}else{ //go to detail
					var path = $(e.target).parents(_.options.elements.divs).find('a').attr('href');
					var path = "";
					if(typeof $(e.target).parents(_.options.elements.divs).attr('href')!="undefined"){
						path = $(e.target).parents(_.options.elements.divs).attr('href');
					}else if(typeof $(e.target).parents(_.options.elements.divs).find('a').attr('href')!="undefined"){
						path = $(e.target).parents(_.options.elements.divs).find('a').attr('href');
					}else if($(e.target).attr('href')!="undefined"){
						path = $(e.target).attr('href');
					}

					window.location = path;
				}
			});
		}
    };
		
	/*FUNCTIONS*/
	Cart.prototype.createCart = function() {
		var _ = this;
			
		if( $('body').innerWidth() > _.options.breakpoint){	
			var cart_el = $( "<div class='"+_.options.class+"'></div>");
			var cart_mask = $( "<div class='cart-mask'></div>");
			
			var wrapper = $('<div class="wrapper"></div>');
			
			/* Link and total value of cart */
			var header = $('<header><h2>'+_.options.texts.header+'</h2></header>');
			header.appendTo(wrapper);
			_.initials.cardHeader = header;
			
			/* products added to the cart will be inserted here using JavaScript */
			var body = $('<div class="body"></div>');
			body.appendTo(wrapper);
			_.initials.cardBody = body;
			_.initials.prodsRpc = body;
			
			/* Link and total value of cart */
			var footer = $('<footer><a href="'+_.options.linkCart+'" class="checkout"><em>'+_.options.texts.footer+'<span></span></em></a></footer>');
			footer.appendTo(wrapper);
			_.initials.cardFooter = footer;
			
			wrapper.appendTo(cart_el);
			
			var modal = "";
			if(_.options.modal.openModal==1 && $('body').innerWidth()>_.options.breakpoint){
				modal = $('<div class="cart-modal"></div>');
				_.initials.cardModal = modal;
			}
			
					
			if(_.options.appendTo && $(_.options.appendTo).length>0){
				cart_el.appendTo(_.options.appendTo);
				cart_mask.insertAfter(_.options.appendTo);
				if(modal) modal.insertAfter(_.options.appendTo);
			}else{
				cart_el.insertAfter(_.initials.cartTrigger);
				cart_mask.insertAfter(cart_el);
				if(modal) modal.insertAfter(cart_el);
			}
			
			$('.'+_.options.class).on('click touchstart',function(e) {
				if($(e.target).closest('.'+_.options.class).length > 0){
					if(_.initials.cartIsOpen) {
						clearTimeout(_.initials.cartTimeout);
					}
				}
			});		
					
			_.initials.cardElement = cart_el;
			
			_.loadProducts();
		}else{
			_.updateCartValues();	
		}
	};
	Cart.prototype.loadProducts = function() {
		var _ = this;
				
		var prodsData = new FormData();
		prodsData.append("op", "carrega_produtos");
				
		if(_.options.extraFields.length>0){
			$.each( _.options.extraFields, function( i, field ) {
				Object.keys(field).map(function (key) { 
					prodsData.append(key, field[key]);
				});
			});
		}			
		
		$.ajax({
			url: _.options.fileRpc,
			data: prodsData,
			processData: false,
			contentType: false,
			type: 'POST',
			success: function (data) {
				$(_.initials.prodsRpc).html(data);	
				_.handleProdBtn();
				_.checkEmpty();		
				_.updateCartValues();	
			}
		});
	};
	Cart.prototype.alteraProducts = function(id, qtd, reinit){
		var _ = this;
		$.post(_.options.fileRpc, {op:"altera_carrinho", id:id, qtd:qtd}, function(data){
			if(reinit==0){
				_.loadProducts();
			}else{
				carregaElementos();
			}
		});
	};
	Cart.prototype.removeProducts = function(id, reinit){
		var _ = this;
		
		ntg_confirm(
	        {
	            type: 'info',
	            title: _.options.texts.delTitle,
	            html: _.options.texts.delMsg,
	            showCloseButton: true,
	            showCancelButton: true,
	            cancelButtonText: _.options.texts.cancel,
	            showConfirmButton: true,
	            confirmButtonText: _.options.texts.ok,
	        },
	        function(){	
	        	$.post(_.options.fileRpc, {op:"remove_carrinho", id:id}, function(data){
	        		if(reinit==0){
						_.loadProducts();
					}else{
						carregaElementos();
					}
	        	});
	        },
	        function(){
	            return false
	        },
	        "",
	        ""
	    );
	};
	Cart.prototype.updateCartValues = function() {
		var _ = this;
				
		$.post(_.options.fileRpc, {op:"update_values"}, function(data){
			data = data.split("###");
				
			if(_.options.showPrices){
				$('.'+_.options.class+' footer').find('span').html(data[0]);	
				$(_.options.trigger).find('.value').html(data[0]);
			}	
			
			$(_.options.trigger).find('.count').html(data[1]);
		});
	};
	
		
	
	/*CLICKS HANDLE*/
	Cart.prototype.handleTrigger = function(timeout = 0) { // OPENS CART
		var _ = this;
		var menu_sel = _.options.menu_sel;

		clearTimeout(_.initials.cartTimeout);
		
		if(menu_sel.indexOf('carrinho')<=0){
			if( $('body').innerWidth() > _.options.breakpoint){
				//Se for um timeout a chamar esta função, só verificámos se está aberto o modal e fechamos.
				if(timeout == 1) {
					if($(_.options.parent).hasClass("cart-open")) {
						_.initials.cartIsOpen = false;
						$(_.options.parent).removeClass('cart-open');
					}
				}
				else {
					if(!_.initials.cartIsOpen) {	
						_.initials.cartIsOpen = true;
						$(_.options.parent).addClass('cart-open');
						
						setTimeout(function(){
							_.initials.cardBody.scrollTop(0);
						}, 500);
					} 
					else {
						_.initials.cartIsOpen = false;
						$(_.options.parent).removeClass('cart-open');
					}
				}
				
				//check body overflow
				_.bodyOverflow();
			}
			else {
				_.updateCartValues();	
				ntg_success(_.options.texts.sucesso);
			}
		}
		else {
			window.open(_.options.linkCart, '_parent');
		}
	};

	Cart.prototype.handleModalBtn = function(product) {
		var _ = this;
			
		$.post(_.options.fileRpc, {op:"carregaModal", id:product}, function(data){				
			$(_.initials.cardModal).html(data);

			_.options.modalCallbacks();
			
			$('body').addClass('overHidden');
			$('.cart-modal').addClass('active');
			
			$('.close-button', '.cart-modal').on('click touchstart', function(){
				$('body').removeClass('overHidden');
				$('.cart-modal').removeClass('active');
			});
			
			if($(_.options.elements.detalheBtn).length>0){
				//add product to cart
				$(_.options.elements.detalheBtn).click(function(e) {
					e.preventDefault();					
					_.handleAddButton($(this).attr('data-product'), $(this).attr('data-label'));					
				});
			}
			
			if($(".quantidade_sel", '.cart-modal').length>0){
				$('.quantidade_sel', '.cart-modal').change(function(e) {
					var prod = $(this).attr('data-produto');
					altera_preco(prod);
				});
			}
			
			if($('input:checked', '.cart-modal').length>0){
				$('input:checked', '.cart-modal').trigger('click');
			}
			if($('select', '.cart-modal').length>0){
				$('select', '.cart-modal').trigger('change');
			}

			initLazyLoad('.cart-modal');
		});
	};
	Cart.prototype.handleAddButton = function(product, label) { // ADD TO CART BUTTON
		var _ = this;

		var prodsData = new FormData();
		prodsData.append("op", "adicionaCarrinho");
		prodsData.append("produto", product);
		prodsData.append("preco", $('#preco_final_'+product).val());
		prodsData.append("message", $('#message').val());

		var qtd_label = product;
		if(label){
			qtd_label = label+product;
		}

		if($('.cart-modal #qtd_'+qtd_label).length>0){
			prodsData.append("qtd", $('.cart-modal #qtd_'+qtd_label).val());
		}else if($('#qtd_'+qtd_label).length>0){
			prodsData.append("qtd", $('#qtd_'+qtd_label).val());
		}else{
			prodsData.append("qtd", 1);
		}
				
		for(var i=1; i<=5; i++){
			var element = $('[name=caract_'+i+'_'+product+']');
			var element_val = "";
			var atributo = $('#atributo'+i+'_'+product);
			var tamanho = "tam"+i;

			if(element.length>0){
				if(element.is(':radio')){
					element_val = $('[name=caract_'+i+'_'+product+']:checked').val();
				}else{
					element_val = $('[name=caract_'+i+'_'+product+']').val();
				}
			
			}
				
			if(element.length>0 && element_val>0) {		
				prodsData.append(tamanho, element_val);
			}else{
				if(atributo.length>0){
					ntg_error(_.options.texts.selecione+' "'+atributo.val()+'"');
					return;
				}
			}
		}
		
		if(_.options.extraFields.length>0){
			$.each( _.options.extraFields, function( i, field ) {
				Object.keys(field).map(function (key) { 
					prodsData.append(key, field[key]);
				});
			});
		}	
				
		$.ajax({
			url: _.options.fileRpc,
			data: prodsData,
			processData: false,
			contentType: false,
			type: 'POST',
			success: function (data) {
				var spl = data.split("-");
				if(spl[1]=='1'){
					/*COMEÇA MOVIMENTO IMAGEM PARA O CESTO */
					
					//var image_element = $('.produtos_divs[data-id='+product+'] .produtos_divs_img');
					var image_element="";

					if(_.options.modal.openModal==1 && $('.cart-modal').hasClass('active') && $('body').innerWidth()>_.options.breakpoint){
						image_element = $('.modal_img');	
					}else if($(_.options.detalheImg).length>0){
						image_element = $(_.options.detalheImg);	
					}else if($('#div_imagem').length>0){
						image_element = $('#div_imagem .slick-current');	
					}else{
						image_element = $(_.options.elements.divs+'[data-id='+product+'] '+_.options.elements.image);		
					}
					
					if(image_element.length>0 && _.options.animation==1){
						image_element.attr('id', 'to_clone');	
						
						var basket;;
						if($(_.initials.cartTrigger+':visible').length>0){
							basket = $(_.initials.cartTrigger+':visible');
						}else{
							basket = $(_.options.trigger+':visible');
						}
						
						
						_.moveProduct($('#to_clone'), basket);
					}else{
						_.moveProduct();
					}
					/* TERMINA MOVIMENTO PARA O CESTO */
					goTo(".header");	

				}else{
					ntg_error(_.options.texts.onAdd);
				}				
			}
		});
	};	
	
	Cart.prototype.handleProdBtn = function() { // OPENS CART
		var _ = this;
		
		/*if(!$(_.options.parent).hasClass('empty')){
			$('.delete-item', '.'+_.options.class).click(function(e) {
                var prod = $(this).parents('li').attr('data-linha');
				_.removeProducts(prod, 0);
            });
			$('.quantity-item select', '.'+_.options.class).change(function(e) {
                var prod = $(this).parents('li').attr('data-linha');
				var qtd = $(this).val();
				_.alteraProducts(prod, qtd, 0);
            });
		}*/
	};

	
	/*HELPERS*/
	Cart.prototype.checkEmpty = function() {
		var _ = this;
				
		if( Number(_.initials.cardBody.find('li').eq(0).text()) == 0) $(_.options.parent).addClass('cart-empty');
		else $(_.options.parent).removeClass('cart-empty');
	};
	Cart.prototype.bodyOverflow = function() {
		var _ = this;
		
		if(_.options.hasOverflow==1){
			$('body').toggleClass('overHidden',  _.initials.cartIsOpen);
		}
	};
	Cart.prototype.moveProduct = function(element, newParent){
		var _ = this;
				
		if(_.options.animation==1 && $(element).length>0 && $(newParent).length>0){
			var oldOffset = element.offset();
			var newOffset = newParent.offset();
		
			element.clone().appendTo('body').attr('id', 'clone');
			var temp = $('#clone');
			temp.removeAttr('class').addClass('has_bg contain');
			
			var newImageWidth 	= element.width() / 4;
			var newImageHeight	= element.height() / 4;
			
			temp.css({
				'position': 'absolute',
				'left': oldOffset.left,
				'top': oldOffset.top,
				'width': element.width(),
				'height': element.height(),
				'z-index': 1000,
			});
			
			temp.animate({'top': newOffset.top, 'left': newOffset.left, 'width': newImageWidth, 'height': newImageHeight}, 'slow', function(){			
				//remove cloned img
				temp.remove();	
				//Check if modal is open
				if(_.options.modal.openModal==1){
					if($('.cart-modal').hasClass('active')){
						$('body').removeClass('overHidden');
						$('.cart-modal').removeClass('active');
					}
				}
				//remove to_clone class
				$('#to_clone').removeAttr('id');
				//update items
				_.loadProducts();
				//show cart
				_.handleTrigger();
				//hide cart after 5 secs
				if( $('body').innerWidth() > _.options.breakpoint){
					_.initials.cartTimeout = setTimeout(function(){
						_.handleTrigger(1);
					}, 5000);
				}else{
					clearTimeout(_.initials.cartTimeout);
				}
			});
		}else{
			//Check if modal is open
			if(_.options.modal.openModal==1){
				if($('.cart-modal').hasClass('active')){
					$('body').removeClass('overHidden');
					$('.cart-modal').removeClass('active');
				}
			}
			//update items
			_.loadProducts();
			//show cart
			_.handleTrigger();
			//hide cart after 5 secs
			if( $('body').innerWidth() > _.options.breakpoint){
				_.initials.cartTimeout = setTimeout(function(){
					_.handleTrigger(1);
				}, 5000);
			}else{
				clearTimeout(_.initials.cartTimeout);
			}
		}
	};
	
	Cart.prototype.setOption = Cart.prototype.cartSetOption = function(option, value) {

        var _ = this, l, item;

        if( option === "extraFields" && $.type(value) === "array" ) {
            for ( item in value ) {
                 l = _.options.extraFields.length-1;
				// loop through the extraFields object and splice out duplicates.
				while( l >= 0 ) {
					if( _.options.extraFields[l].breakpoint === value[item].breakpoint ) {
						_.options.extraFields.splice(l,1);
					}
					l--;
				}
				_.options.extraFields.push( value[item] );
            }
        } else {
            _.options[option] = value;
        }

    };

    $.fn.cart = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].cart = new Cart(_[i], opt);
            else
                ret = _[i].cart[opt].apply(_[i].cart, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

})( jQuery );


