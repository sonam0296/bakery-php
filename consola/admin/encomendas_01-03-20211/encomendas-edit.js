$(document).ready(function() {
	$('#save-morada-fatura').css('display', 'none');
	$('#morada-fatura-edit').css('display', 'none');
	$('#cancelar-morada-fatura').css('display', 'none');

	$('#edit-morada-fatura').click(function(){
		$('#morada-fatura').css('display', 'none');
		$('#morada-fatura-edit').css('display', 'block');
		
		$('#edit-morada-fatura').css('display', 'none');
		$('#save-morada-fatura').css('display', 'inline');
		$('#cancelar-morada-fatura').css('display', 'inline');
	});

	$('#cancelar-morada-fatura').click(function() {
		$('#morada-fatura-edit').css('display', 'none');
		$('#morada-fatura').css('display', 'block');
		
		$('#save-morada-fatura').css('display', 'none');
		$('#cancelar-morada-fatura').css('display', 'none');
		$('#edit-morada-fatura').css('display', 'block');
	});

	$('#save-morada-envio').css('display', 'none');
	$('#morada-envio-edit').css('display', 'none');
	$('#cancelar-morada-envio').css('display', 'none');

	$('#edit-morada-envio').click(function(){
		$('#morada-envio').css('display', 'none');
		$('#morada-envio-edit').css('display', 'block');
		
		$('#edit-morada-envio').css('display', 'none');
		$('#save-morada-envio').css('display', 'inline');
		$('#cancelar-morada-envio').css('display', 'inline');
	});

	$('#cancelar-morada-envio').click(function() {
		$('#morada-envio-edit').css('display', 'none');
		$('#morada-envio').css('display', 'block');
		
		$('#save-morada-envio').css('display', 'none');
		$('#cancelar-morada-envio').css('display', 'none');
		$('#edit-morada-envio').css('display', 'block');
	});

	$('#encomenda-edit').css('display', 'none');
	$('#cancelar-edit-encomenda').css('display', 'none');
	$('#save-encomenda').css('display', 'none');

	$('#edit-encomenda-bt').click(function() {
		$('#encomenda').css('display', 'none');
		$('#encomenda-edit').css('display', 'block');

		$('#edit-encomenda-bt').css('display', 'none');
		$('#cancelar-edit-encomenda').css('display', 'inline');
		$('#save-encomenda').css('display', 'inline');
	});

	$('#cancelar-edit-encomenda').click(function() {
		$('#encomenda-edit').css('display', 'none');
		$('#encomenda').css('display', 'block');
		
		$('#cancelar-edit-encomenda').css('display', 'none');
		$('#save-encomenda').css('display', 'none');
		$('#edit-encomenda-bt').css('display', 'block');
	});

	$('.editar-qtd-prod').css('display', 'none');

	$('.edit-prod-qtd').click(function() {
		var parts = this.id.split("_");

		$('#qtd_prod_'+parts[3]).css('display', 'none');
		$('#editar_prod_'+parts[3]).css('display', 'block');
	});

	$('.cancel_prod_qtd').click(function() {
		var parts = this.id.split("_");

		$('#editar_prod_'+parts[3]).css('display', 'none');
		$('#qtd_prod_'+parts[3]).css('display', 'block');
	});
});