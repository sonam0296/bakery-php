var FormValidation = function () {
  
  // advance validation
  var topos = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_topos');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				nome: {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_topos.submit(); // submit the form
			}
		});
  }

  // advance validation
  var listas = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_listas');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				nome: {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_listas.submit(); // submit the form
			}
		});
  }

  // advance validation
  var tipos = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_tipos');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				nome: {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_tipos.submit(); // submit the form
			}
		});
  }

   // advance validation
  var grupos = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_grupos');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				nome: {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_grupos.submit(); // submit the form
			}
		});
  }
	
	// advance validation
  var emails = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_emails');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				email: {
					required: true
				},
				"listas[]": {
					required: true
				},
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_emails.submit(); // submit the form
			}
		});
  }
	
	// advance validation
  var emails_exporta = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_exporta');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				"estado[]": {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_exporta.submit(); // submit the form
			}
		});
  }

  // advance validation
  var conteudos = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_conteudo');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				nome: {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_conteudo.submit(); // submit the form
			}
		});
  }

  // advance validation
  var blocos = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_conteudo_blocos');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				nome: {
					required: true
				},
				tipo: {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_conteudo_blocos.submit(); // submit the form
			}
		});
  }

  // advance validation
  var produtos = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_conteudo_produtos');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);
		
		var tipo_tema = 0;

		if(document.getElementById('tipo_tema')) {
			tipo_tema = document.getElementById('tipo_tema').value;

			if(tipo_tema == 1) { // produtos
				
				form2.validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block help-block-error', // default input error message class
					focusInvalid: false, // do not focus the last invalid input
					ignore: "",  // validate all fields including form hidden input
					rules: {
						nome_produto: {
							required: true
						},
						categoria: {
							required: true
						},
						produto: {
							required: true
						}
					},
		
					invalidHandler: function (event, validator) { //display error alert on form submit              
						success2.hide();
						error2.show();
						Metronic.scrollTo(error2, -200);
					},
		
					errorPlacement: function (error, element) { // render error placement for each input type
						var icon = $(element).parent('.input-icon').children('i');
						icon.removeClass('fa-check').addClass("fa-warning");  
						icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
					},
		
					highlight: function (element) { // hightlight error inputs
						$(element)
							.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
					},
		
					unhighlight: function (element) { // revert the change done by hightlight
						
					},
		
					success: function (label, element) {
						var icon = $(element).parent('.input-icon').children('i');
						$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
						icon.removeClass("fa-warning").addClass("fa-check");
					},
		
					submitHandler: function (form) {
						success2.show();
						error2.hide();
						document.frm_conteudo_produtos.submit(); // submit the form
					}
				});
				
			} else { // texto e/ou imagem
				
				form2.validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block help-block-error', // default input error message class
					focusInvalid: false, // do not focus the last invalid input
					ignore: "",  // validate all fields including form hidden input
					rules: {
						nome: {
							required: true
						}
					},
		
					invalidHandler: function (event, validator) { //display error alert on form submit              
						success2.hide();
						error2.show();
						Metronic.scrollTo(error2, -200);
					},
		
					errorPlacement: function (error, element) { // render error placement for each input type
						var icon = $(element).parent('.input-icon').children('i');
						icon.removeClass('fa-check').addClass("fa-warning");  
						icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
					},
		
					highlight: function (element) { // hightlight error inputs
						$(element)
							.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
					},
		
					unhighlight: function (element) { // revert the change done by hightlight
						
					},
		
					success: function (label, element) {
						var icon = $(element).parent('.input-icon').children('i');
						$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
						icon.removeClass("fa-warning").addClass("fa-check");
					},
		
					submitHandler: function (form) {
						success2.show();
						error2.hide();
						document.frm_conteudo_produtos.submit(); // submit the form
					}
				});
			}
		}
  }

  // advance validation
  var newsletters = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_newsletter');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				nome: {
					required: true
				},
				conteudo: {
					required: true
				},
				tipo: {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_newsletter.submit(); // submit the form
			}
		});
  }

  // advance validation
  var envio_newsletters = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_newsletter_envia');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				// nome: {
				// 	required: true
				// },
				// "listas[]": {
				// 	required: true,
				// 	minlength: 1
				// },
				data: {
					required: true
				},
				grupo: {
					required: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				document.frm_newsletter_envia.submit(); // submit the form
			}
		});
  }

  // advance validation
  var newsletters_config = function() {
    // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#frm_newsletter_config');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				max_emails: {
					required: true
				},
				email_from: {
					required: true,
					email: true
				},
				email_reply: {
					required: true,
					email: true
				},
				email_bounce: {
					required: true,
					email: true
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit              
				success2.hide();
				error2.show();
				Metronic.scrollTo(error2, -200);
			},

			errorPlacement: function (error, element) { // render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");  
				icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function (element) { // revert the change done by hightlight
				
			},

			success: function (label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function (form) {
				success2.show();
				error2.hide();
				form[0].submit(); // submit the form
			}
		});
  }

  var initComponents = function () {
    //init datepickers
    $('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true
    });
  }

  return {
      //main function to initiate the module
      init: function () {
				initComponents();
				tipos();
				grupos();
				listas();
				emails();
				emails_exporta();
				conteudos();
				blocos();
				produtos();
				newsletters();
				newsletters_config();
				envio_newsletters();
				topos();
      }
  };
}();