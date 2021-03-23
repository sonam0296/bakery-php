/*
 Version: 1
 Author: Netgócio
 Website: http://netgocio.pt
*/

function adiciona_favoritos(id, elemento, event){
	event.preventDefault();
	var elem = $(elemento);
	
	var title = $recursos["adicionar_favoritos"];
	var msg = $recursos["favorito_anuncio_txt"];
	var suc = $recursos["favorito_anuncio_suc"];
	var tipo = 1;
	
	if(elem.hasClass('active')){
		title= $recursos["adicionar_favoritos_2"];
		msg = $recursos["favorito_anuncio_txt2"];
		suc = $recursos["favorito_anuncio_suc2"];
		tipo = 2;
	}

	ntg_confirm(
        {
            type: 'info',
            title: title,
            html: msg,
            showCloseButton: true,
            showCancelButton: true,
            cancelButtonText: $recursos["cancelar"],
            showConfirmButton: true,
            confirmButtonText: $recursos["confirmar"],
        },
        function(){
            var opcoes;

			// if(!elem.hasClass('active')){
	  		//       	if($('.service_div').length>0){
			//         $('.service_div').each(function(){
			//             if($(this).find('input:checked').length>0){
			//                 var opcoes = $(this).attr('data-service'),
			//                     opcoeVal = $(this).find('input:checked').val(),
			//                     opcoePrice = $(this).find('input:checked').attr('data-preco');
			        
			//                 var opc={
			//                     "opcoe": opcoe,
			//                     "opcoeVal": opcoeVal,
			//                     "opcoePrice": opcoePrice,
			//                 };

			//                 opcoes.push(opc); 
			//             }
			//         });
			//     }
			// }
		    var form_data={
		        produto: id,
		        tipo: tipo,
		        opcoes: opcoes,
		    };
		   
		    var favoritoData = new FormData();
		    favoritoData.append('op',"favorito_produto");
		    favoritoData.append('form_data',JSON.stringify(form_data));

		    $.ajax({
		        url: _includes_path+"carrinho-rpc.php",
		        type : "POST",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data : favoritoData,
		        success : function(response) {
	            //retirado porque nao ha necessidade de ir la duas vezes
	            //ntg_success(suc);

							//Especifico favoritos: "skinelement, edghestore"
							// $.post(_includes_path+"carrinho-rpc.php", {op:"update_favs"}, function(data){
							// 	$('.fav_btn').find('.count').html(data);			
							// });

	            if(!elem.hasClass('reload')){
						if(tipo == 1){
							elem.addClass('active');
							if(!$('#wish-trigger').hasClass('visible')) $('#wish-trigger').addClass('visible');
						}else{
							elem.removeClass('active');
						}
					}

					if(elem.hasClass('reload')){
						setTimeout(function(){
							location.reload();
						}, 800);
					}
		        }, error: function(xhr, resp, text){
		            // show error to console
		            console.log(xhr, resp, text);
		        }
		    });		
        },
        function(){
            return false
        },
        suc,
        ''
    );
}
/*function seguir_preco(id, elemento){
	var id_cliente = '<?php echo $id_cliente; ?>';
	var elem = $(elemento);

	var opcao_1=0, opcao_2=0;
	
	if($('[name=caract_1_<?php echo $produto; ?>]').length>0) {	
		if($('[name=caract_1_<?php echo $produto; ?>]').is(':radio')){
			opcao_1=$('[name=caract_1_<?php echo $produto; ?>]:checked').val();
		}else{
			opcao_1=$('[name=caract_1_<?php echo $produto; ?>]').val();
		}
	}

	if($('[name=caract_2_<?php echo $produto; ?>]').length>0) {	
		if($('[name=caract_2_<?php echo $produto; ?>]').is(':radio')){
			opcao_2=$('[name=caract_2_<?php echo $produto; ?>]:checked').val();
		}else{
			opcao_2=$('[name=caract_2_<?php echo $produto; ?>]').val();
		}
	}
	
	if(id_cliente){
		var msg = $recursos["adicionar_seguir_txt"];
		var suc = $recursos["adicionar_seguir_suc"];
		var tipo = 1;
		
		if(elem.hasClass('active')){
			msg = $recursos["adicionar_seguir_txt2"];
			suc = $recursos["adicionar_seguir_suc2"];
			tipo = 2;
		}

		ntg_confirm(
	        $recursos["adicionar_seguir"],
	        msg,
	        "warning",
	        {
	            cancel: {
	                text: $recursos["cancelar"],
	                value: null,
	                visible: true,
	                className: "",
	                closeModal: true,
	            },
	            confirm: {
	                text: $recursos["confirmar"],
	                value: true,
	                visible: true,
	                className: "",
	                closeModal: false
	            }
	        },
	        true,
	        {
	            function: function(){
					ntg_success(suc);
					if(tipo == 1) elem.addClass('active');
					else elem.removeClass('active');
				},
	            file:"carrinho-rpc.php",
	            data: {op:"seguir_preco", id:id, cliente:id_cliente, tipo:tipo, opcao_1:opcao_1, opcao_2: opcao_2},
	        },
	        suc,
	        $recursos["tem_login_erro"]
	    );  
	}else{
		ntg_error($recursos["tem_login_erro"]);	
	}
}*/

	
function altera_caract(prod, whom){
	var op1="",
		value1 = 0,
		op2="",
		value2 = 0,
		op3="",
		value3 = 0,
		op4="",
		value4 = 0,
		op5="",
		value5 = 0,
		element="",
		change = 1;
			
	if($('[name=caract_1_'+prod+']').length>0) {	
		if($('[name=caract_1_'+prod+']').is(':radio')){
			value1 = $('[name=caract_1_'+prod+']:checked').val();
		}else{
			value1 = $('[name=caract_1_'+prod+']').val();
		}
			
		if(value1>0){
			op1 = value1;
			element=$('#prod_opc_2_'+prod);
		}
		
		if(whom == 'caract_1_'+prod){
			if(element.length>0) element.height(element.outerHeight(true));
			$('#prod_opc_2_'+prod).html('');
			$('#prod_opc_3_'+prod).html('');
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}
	}

	if($('[name=caract_2_'+prod+']').length>0) {	
		if($('[name=caract_2_'+prod+']').is(':radio')){
			value2=$('[name=caract_2_'+prod+']:checked').val();
		}else{
			value2=$('[name=caract_2_'+prod+']').val();
		}
		
	
		if(value2>0 || whom != 'caract_2_'+prod){
			op2 = value2;
			element=$('#prod_opc_3_'+prod);
		}else{
			change = 0;
			$('#prod_opc_3_'+prod).html('');
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}
		
		if(whom == 'caract_2_'+prod){
			if(element.length>0) element.height(element.outerHeight(true));
			$('#prod_opc_3_'+prod).html('');
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}
	}
	
	if($('[name=caract_3_'+prod+']').length>0){
		if($('[name=caract_3_'+prod+']').is(':radio')){
			value3=$('[name=caract_3_'+prod+']:checked').val();
		}else{
			value3=$('[name=caract_3_'+prod+']').val();
		}
		
		if(value3>0){
			op3 = value3;
			element=$('#prod_opc_4_'+prod);
		}else{
			change = 0;
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}

		if(whom == 'caract_3_'+prod){
			if(element.length>0) element.height(element.outerHeight(true));
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}		
	}
	
	if($('[name=caract_4_'+prod+']').length>0){
		if($('[name=caract_4_'+prod+']').is(':radio')){
			value4=$('[name=caract_4_'+prod+']:checked').val();
		}else{
			value4=$('[name=caract_4_'+prod+']').val();
		}
		
		if(value4>0){
			op4 = value4;
			element=$('#prod_opc_5_'+prod);
		}else{
			change = 0;
			$('#prod_opc_5_'+prod).html('');
		}

		if(whom == 'caract_4_'+prod){
			if(element.length>0) element.height(element.outerHeight(true));
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}		
	}
	
	if($('[name=caract_5_'+prod+']').length>0){
		if($('[name=caract_5_'+prod+']').is(':radio')){
			value5=$('[name=caract_5_'+prod+']:checked').val();
		}else{
			change = 0;
			value5=$('[name=caract_5_'+prod+']').val();
		}
		
		if(value5>0){
			op5 = value5;
			element = "";
		}
	}
	
	
	if(change == 1){
		$.post(_includes_path+"carrinho-rpc.php", {op:"alteraTamanho", produto:prod, tamanho_1:op1, tamanho_2:op2, tamanho_3:op3, tamanho_4:op4, tamanho_5:op5}, function(data){
			if(element && data){
				element.html(data).fadeIn('slow');
				element.css('height', 'auto');

				//VERIFICA SE SO EXISTE UMA OPÇÂO E SELECIONA
				if($(element).find('input[type="radio"]').length==1 && $(element).find('input[type="radio"]:checked').length==0){
					$(element).find('input[type="radio"]:eq(0)').prop('checked', true);
				}else if($(element).find('select option').length==1 && $(element).find('select').find(':selected').length==0){
					$(element).find('select option:eq(0)').prop('selected', true);
				}
				
				//ARRANJAR FORMA DE FAZER TRIGGER. VERIFICAR SE O VAL É MAIOR DO QUE 0 E SO AI FAZER TRIGGER
				if($('input:checked', element).length>0){
					$('input:checked', element).trigger('click');
				}
				if($('select', element).length>0 && $('select', element).val()>0){
					$('select', element).trigger('change');
				}

				if(!$(element).parents('.detalhe_divs').hasClass('active')){
					$(element).parents('.detalhe_divs').addClass('active');
					$(element).parents('.column').addClass('active');
				}else{
					$(element).parents('.column').addClass('active');
				}
			}	

			altera_preco(prod);				
		});	
	}
	
	altera_preco(prod);
}

$('.quantidade_sel').change(function(e) {
	var prod = $(this).attr('data-produto');
    altera_preco(prod);
});

function altera_preco(prod){
	
	var op1=0,
		op2=0,
		op3=0,
		op4=0,
		op5=0,
		qtd=$('#qtd_'+prod).val();
	
	if($('[name=caract_1_'+prod+']').length>0) {	
		if($('[name=caract_1_'+prod+']').is(':radio')){
			op1=$('[name=caract_1_'+prod+']:checked').val();
		}else{
			op1=$('[name=caract_1_'+prod+']').val();
		}
	}

	if($('[name=caract_2_'+prod+']').length>0) {	
		if($('[name=caract_2_'+prod+']').is(':radio')){
			op2=$('[name=caract_2_'+prod+']:checked').val();
		}else{
			op2=$('[name=caract_2_'+prod+']').val();
		}
	}
	
	if($('[name=caract_3_'+prod+']').length>0){
		if($('[name=caract_3_'+prod+']').is(':radio')){
			op3=$('[name=caract_3_'+prod+']:checked').val();
		}else{
			op3=$('[name=caract_3_'+prod+']').val();
		}
	}
	
	if($('[name=caract_4_'+prod+']').length>0){
		if($('[name=caract_4_'+prod+']').is(':radio')){
			op4=$('[name=caract_4_'+prod+']:checked').val();
		}else{
			op4=$('[name=caract_4_'+prod+']').val();
		}
	}
	
	if($('[name=caract_5_'+prod+']').length>0){
		if($('[name=caract_5_'+prod+']').is(':radio')){
			op5=$('[name=caract_5_'+prod+']:checked').val();
		}else{
			op5=$('[name=caract_5_'+prod+']').val();
		}
	}

	
	$.post(_includes_path+"carrinho-rpc.php", {op:"alteraPreco", produto:prod, qtd:qtd, op1:op1, op2:op2, op3:op3, op4:op4, op5:op5}, function(data){
		$('#conteudo_preco_'+prod).html(data);
	});
	

	$.post(_includes_path+"carrinho-rpc.php", {op:"alteraImgProd", produto:prod, op1:op1, op2:op2, op3:op3, op4:op4, op5:op5}, function(data){
		var tamanho = data;
		if(tamanho>0 && $('.item[data-tamanho="'+tamanho+'"]').length>0){
	        var index = $('.item[data-tamanho="'+tamanho+'"]').index();
	        $( '.slick-imgs' ).slick('slickGoTo',index);
	    }
	});

	// if(op1 > 0 && (document.getElementById('caract_2_'+prod+'') && op2!=0) || op1 > 0 && (document.getElementById('caract_2_'+prod+''))) {
		$.post(_includes_path+"carrinho-rpc.php", {op:"alteraMsgStock", produto:prod, qtd:qtd, op1:op1, op2:op2, op3:op3, op4:op4, op5:op5}, function(data) {
			document.getElementById('conteudo_stock').innerHTML=data;
			if($('#conteudo_stock').css("display") == "none") $('#conteudo_stock').fadeIn();
		});
	// }
}


function alteraCart(input){
	var id = input.attr('data-id');
	var menu_sel = $('#menu_sel').val();
	var is_detalhe = 0;

	if(menu_sel=="carrinho"){
		is_detalhe = 1;
	}
	
	$( ".mainDiv" ).cart('alteraProducts', id, input.val(), is_detalhe);
}
function removeCart(id){
	var menu_sel = $('#menu_sel').val();
	var is_detalhe = 0;
	if(menu_sel=="carrinho"){
		is_detalhe = 1;
	}
	
	$( ".mainDiv" ).cart('removeProducts', id, is_detalhe);
}

//Recarrega os descontos por quantidade de um produto ao mudar a opção escolhida
function carregaQuantidades(prod, whom) {
	var op1="",
	value1 = 0,
	op2="",
	value2 = 0,
	op3="",
	value3 = 0,
	op4="",
	value4 = 0,
	op5="",
	value5 = 0,
	element="",
	change = 1;
			
	if($('[name=caract_1_'+prod+']').length>0) {	
		if($('[name=caract_1_'+prod+']').is(':radio')){
			value1 = $('[name=caract_1_'+prod+']:checked').val();
		}else{
			value1 = $('[name=caract_1_'+prod+']').val();
		}
			
		if(value1>0){
			op1 = value1;
			element=$('#prod_opc_2_'+prod);
		}
		
		if(whom == 'caract_1_'+prod){
			$('#prod_opc_2_'+prod).html('');
			$('#prod_opc_3_'+prod).html('');
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}
	}

	if($('[name=caract_2_'+prod+']').length>0) {	
		if($('[name=caract_2_'+prod+']').is(':radio')){
			value2=$('[name=caract_2_'+prod+']:checked').val();
		}else{
			value2=$('[name=caract_2_'+prod+']').val();
		}
		
	
		if(value2>0 || whom != 'caract_2_'+prod){
			op2 = value2;
			element=$('#prod_opc_3_'+prod);
		}else{
			change = 0;
			$('#prod_opc_3_'+prod).html('');
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}
		
	}
	
	if($('[name=caract_3_'+prod+']').length>0){
		if($('[name=caract_3_'+prod+']').is(':radio')){
			value3=$('[name=caract_3_'+prod+']:checked').val();
		}else{
			value3=$('[name=caract_3_'+prod+']').val();
		}
		
		if(value3>0){
			op3 = value3;
			element=$('#prod_opc_4_'+prod);
		}else{
			change = 0;
			$('#prod_opc_4_'+prod).html('');
			$('#prod_opc_5_'+prod).html('');
		}
		
	}
	
	if($('[name=caract_4_'+prod+']').length>0){
		if($('[name=caract_4_'+prod+']').is(':radio')){
			value4=$('[name=caract_4_'+prod+']:checked').val();
		}else{
			value4=$('[name=caract_4_'+prod+']').val();
		}
		
		if(value4>0){
			op4 = value4;
			element=$('#prod_opc_5_'+prod);
		}else{
			change = 0;
			$('#prod_opc_5_'+prod).html('');
		}
		
	}
	
	if($('[name=caract_5_'+prod+']').length>0){
		if($('[name=caract_5_'+prod+']').is(':radio')){
			value5=$('[name=caract_5_'+prod+']:checked').val();
		}else{
			change = 0;
			value5=$('[name=caract_5_'+prod+']').val();
		}
		
		if(value5>0){
			op5 = value5;
			element = "";
		}
	}

	$.post(_includes_path+"carrinho-rpc.php", {op:"carregaQuantidades", prod:prod, tamanho_1:op1, tamanho_2:op2, tamanho_3:op3, tamanho_4:op4, tamanho_5:op5}, function(data) {
		if(data != "") {
			$('#div_qtd_prod').html(data);

			if($(".detalhe_sels.quantidade_sel").length > 0) {				
				altera_preco(prod);
				
				$('.quantidade_sel').change(function(e) {
					var prod = $(this).attr('data-produto');
			   	 	altera_preco(prod);
				});
			}
		}
	});
}
function hasSelectedOption(objName){
	obj=document.getElementsByName(objName);
	if(obj)
	{
		hasSelected=false;
		for(i=0;i<obj.length;i++)
		{
			if(obj[i].checked)
			{
				hasSelected=obj[i].value;
			}
		}
		return hasSelected;
	}
}