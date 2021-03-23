var FormValidation = function () {

    // advance validation
    var Redirecionamentos = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation

            var form2 = $('#redirecionamentos');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    url_old: {
                        minlength: 2,
                        required: true
                    },
                    url_new: {
                        minlength: 2,
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
                    document.redirecionamentos.submit(); // submit the form
                }
            });
    }

    // advance validation
    var Blocos = function() {
        // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#blocos');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);
		
		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input
			rules: {
				titulo: {
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
				document.blocos.submit(); // submit the form
			}
		});
    }

    // advance validation
    var Galerias = function() {
        // for more info visit the official plugin documentation: 
		// http://docs.jquery.com/Plugins/Validation

		var form2 = $('#galerias');
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
				document.galerias.submit(); // submit the form
			}
		});
    }

    // advance validation
    var Contactos = function() {
      // for more info visit the official plugin documentation: 
      // http://docs.jquery.com/Plugins/Validation

      var form2 = $('#frm_contactos');
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
              document.frm_contactos.submit(); // submit the form
          }
      });
    }

    return {
        //main function to initiate the module
        init: function () {
        	Contactos();
          Redirecionamentos();
					Blocos();
					Galerias();

        }

    };

}();