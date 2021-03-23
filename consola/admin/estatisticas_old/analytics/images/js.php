<? header("Content-type: text/javascript; charset: UTF-8");
$siteRoot = $_GET['sr']; 
if (!$_GET['sr']) { die(""); }
?>

$(document).ready(function(){
	// Fancybox
	$(".pme-cell-0-cssimg a").fancybox({
				'hideOnContentClick': false,
				'overlayShow':	true
	});
	$(".pme-cell-1-cssimg a").fancybox({
				'hideOnContentClick': false,
				'overlayShow':	true
	});
	
	
	jQuery.fn.shake = function(intShakes /*Amount of shakes*/, intDistance /*Shake distance*/, intDuration /*Time duration*/) {
		this.each(function() {
			$(this).css({position:'relative'});
			for (var x=1; x<=intShakes; x++) {
				$(this).animate({left:(intDistance*-1)}, (((intDuration/intShakes)/4)))
				.animate({left:intDistance}, ((intDuration/intShakes)/2))
				.animate({left:0}, (((intDuration/intShakes)/4)));
    		}
		});
  		return this;
	};
	
	jQuery.fn.delay = function(time,func){
    return this.each(function(){
        setTimeout(func,time);
    });
	};
	
	$sr = "<?php echo $siteRoot; ?>";
	
	$("#frmLogin").validate({
		debug: false,
		rules: {
			email: 	{ required: true, email: true},
			senha: 	{ required: true }
		},
		messages: {
			email: 	"Insira o e-mail",
			senha: 	"Insira a senha"
		},
		highlight: function(input, errorClass) {
			$(input).css("border", "1px solid #F85E42");
		},
		unhighlight: function(input, errorClass) {
			$(input).css("border", "1px solid #666");
		},
		submitHandler: function(form) {
			$("#alerta").fadeOut(0); 
			var frmLogin = $("#frmLogin").serialize();
			if(!window['MODO_RECUPERAR']){
				$.ajax({ 
					type: "GET",
					url: $sr+"login.php",
					data: "op=doLogin&"+frmLogin, 
					success: function(msg){ 
						if (msg=="OK"){
							top.location.href=$sr;
						}else if (msg=="ERRO"){
							$('#caixaLogin').shake(3, 6, 180);
							$("#alerta").fadeIn(500); 
							$('#alerta').html('<div class="msgbox-error">Dados Inválidos.</div>');
							$('#alerta').delay(3000, function(){ $('#alerta').fadeOut() });
						}
					}
				});
			}else{
				$.ajax({ 
					type: "GET",
					url: $sr+"login.php",
					data: "op=doRecover&"+frmLogin, 
					success: function(msg){ 
						if (msg=="OK"){
							$("#alerta").fadeIn(500); 
							$('#alerta').html('<div class="msgbox-info">E-mail enviado</div>');
							$('#alerta').delay(3000, function(){ $('#alerta').fadeOut() });
						}else if (msg=="ERRO"){
							$('#caixaLogin').shake(3, 6, 180);
							$("#alerta").fadeIn(500); 
							$('#alerta').html('<div class="msgbox-error">E-mail inexistente</div>');
							$('#alerta').delay(3000, function(){ $('#alerta').fadeOut() });
						}
					}
				});
			}
		}
	});
	window['MODO_RECUPERAR']=false;

	$('#clickPChave').click(function(){
		if($('#clickPChave').html()!="Voltar ao login"){
			$('#divInterface').fadeOut(250,function(){
				$('#divPChave').slideUp(500,function(){
					$('#clickPChave').html("Voltar ao login");
					$('#divInterface').fadeIn(250);
				});
			});			
		
		//	$('#imgTitle').attr("src","images/layout/login_recuperar.jpg");
			$("#senha").rules("remove");
			window['MODO_RECUPERAR']=true;
		}else{
			$('#divInterface').fadeOut(250,function(){
				$('#divPChave').slideDown(500,function(){
					$('#clickPChave').html("Perdeu o acesso?");
					$('#divInterface').fadeIn(250);
				});
			});			
	
			$('#imgTitle').attr("src","images/layout/login_arearestricta.jpg");
			$("#senha").rules("add",{ required: true });
			window['MODO_RECUPERAR']=false;
		}
	});
});