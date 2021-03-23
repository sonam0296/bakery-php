$(document).ready(function() {
  var $scrollbar = $("#scrollbar1");
  $scrollbar.tinyscrollbar();

  $(".client-details").click(function() {
    $('#nome-cliente').text($(this).attr('data-nome'));
    $('#data-registo-cliente').text($(this).attr('data-dt-registo'));
    
    var wish = $(this).attr('data-wish');
    var follow = $(this).attr('data-follow');
    var wish_span ='';
    var follow_span = '';

    if(wish > 0)
      wish_span = "<i style='color:green' class='fa fa-check fa-lg'></i>";
    else
      wish_span = "<i style='color:red' class='fa fa-times fa-lg'></i>";

    if(follow > 0)
      follow_span = "<i style='color:green' class='fa fa-check fa-lg'></i>";
    else
      follow_span = "<i style='color:red' class='fa fa-times fa-lg'></i>";
    
    $('#wish-prod').html(wish_span);
    $('#follow-prod').html(follow_span);

    $('#edit-cliente').prop("href", "../outros/clientes-edit.php?id="+$(this).attr('data-id'));

    $.ajax({
      url: './produtos-carrega-encomendas.php',
      type: 'POST',
      data: {id_client: $(this).attr('data-id'), dir: "none", product_id: $('#product-id').val()}
    })
    .done(function(result) {
      if(result) {
        var res = $.parseJSON(result);
        $('#estado-enc').html(res.estado);
        $('#data-encomenda').text(res.data);
        $('#qtd-prod').text(res.qtd_prod);
        $('#total-prod').text(res.total_prod+" £");
        $('#total-enc').text(res.total_enc+" £");
        $('#encomenda_id').text(res.id);
        $('#last-order-id').val(res.id);

        $('#enc-id').prop('href', '../encomendas/encomendas-edit.php?id='+res.id);
      }
    });

    $.ajax({
      url: './produtos-carrega-encomendas.php',
      type: 'POST',
      data: {id_client: $(this).attr('data-id'), flag: "geral"}
    })
    .done(function(result) {
      if(result) {
        var res = $.parseJSON(result);
        $('#total-encomendas').text(res.total_enc);
        $('#total-gasto').text(res.valor_enc+" £");
      }
    });

    $('#client-id').val($(this).attr('data-id'));

    $('.stats-details').css('display', 'block');
  });

  $("#enc-ant").click(function() {
    $.ajax({
      url: './produtos-carrega-encomendas.php',
      type: 'POST',
      data: {id_client: $('#client-id').val(), last_order: $('#last-order-id').val(), dir: "ant", product_id: $('#product-id').val()}
    })
    .done(function(result) {
      if(result) {
        var res = $.parseJSON(result);
        $('#estado-enc').html(res.estado);
        $('#data-encomenda').text(res.data);
        $('#qtd-prod').text(res.qtd_prod);
        $('#total-prod').text(res.total_prod+" £");
        $('#total-enc').text(res.total_enc+" £");
        $('#encomenda_id').text(res.id);
        $('#last-order-id').val(res.id);

        $('#enc-id').prop('href', '../encomendas/encomendas-edit.php?id='+res.id);
      }
    });
  });

  $("#enc-prox").click(function() {
    $.ajax({
      url: './produtos-carrega-encomendas.php',
      type: 'POST',
      data: {id_client: $('#client-id').val(), last_order: $('#last-order-id').val(), dir: "prox", product_id: $('#product-id').val()}
    })
    .done(function(result) {
      if(result) {
        var res = $.parseJSON(result);
        $('#estado-enc').html(res.estado);
        $('#data-encomenda').text(res.data);
        $('#qtd-prod').text(res.qtd_prod);
        $('#total-prod').text(res.total_prod+" £");
        $('#total-enc').text(res.total_enc+" £");
        $('#encomenda_id').text(res.id);
        $('#last-order-id').val(res.id);

        $('#enc-id').prop('href', '../encomendas/encomendas-edit.php?id='+res.id);
      }
    });
  });
});
