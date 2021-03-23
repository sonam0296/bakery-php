<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='clientes';
$menu_sub_sel='listagem';

$id = $_GET['id'];

$tab_sel = 1;
if($_GET['tab_sel'] > 0) $tab_sel = $_GET['tab_sel'];

$erro_password = 0;
$erro_email = 0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_cliente_ed")) {
	if($_POST['nome']!='' && $_POST['email']!='' && $_POST['tipo']!='') {
		$manter = $_POST['manter'];
		
		$query_rsExiste = "SELECT id FROM clientes WHERE email=:email AND id!=:id";
		$rsExiste = DB::getInstance()->prepare($query_rsExiste);
		$rsExiste->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);	
		$rsExiste->bindParam(':id', $id, PDO::PARAM_STR, 5);	
		$rsExiste->execute();
		$totalRows_rsExiste = $rsExiste->rowCount();
		
		$query_rsP = "SELECT password, password_salt FROM clientes WHERE id = :id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsP->execute();
		$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsP = $rsP->rowCount();
		
		if($_POST['pass']!='' && $_POST['pass'] != $_POST['pass2']) {
			$erro_password = 1;
		} 
    else if($totalRows_rsExiste > 0) {
			$erro_email = 1;
		} 
    else {		
			if($_POST['pass']!='') {		
				$salt = createSalt();
        $hash = hash('sha256', $_POST['pass']);

        $password_final = hash('sha256', $salt . $hash);
			}
      else {
				$salt = $row_rsP['password_salt'];
        $password_final = $row_rsP['password'];
			}

      $data_nasc=$_POST['data_nasc'];
      if(!$data_nasc) $data_nasc=NULL;

			$insertSQL = "UPDATE clientes SET tipo=:tipo, email=:email, password=:password, password_salt=:password_salt, data_nasc=:data_nasc, nome=:nome, morada=:morada, cod_postal=:cod_postal, localidade=:localidade, pais=:pais, telefone=:telefone, telemovel=:telemovel, nif=:nif, desconto=:desconto, pessoa=:pessoa, atividade=:atividade, atividade2=:atividade2 WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_INT);
			$rsInsert->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':password', $password_final, PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':password_salt', $salt, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':data_nasc', $data_nasc, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':morada', $_POST['morada'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':cod_postal', $_POST['cpostal'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':localidade', $_POST['localidade'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':pais', $_POST['pais'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':telefone', $_POST['telefone'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':telemovel', $_POST['telemovel'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':nif', $_POST['nif'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':pessoa', $_POST['pessoa'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':atividade', $_POST['atividade'], PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':atividade2', $_POST['atividade2'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':desconto', $_POST['desconto'], PDO::PARAM_INT);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
			$rsInsert->execute();
		}
    
		DB::close();
	}

  if($erro_password == 0 && $erro_email == 0) {
    if($manter == 1) {
      header("Location: clientes-edit.php?id=".$id."&alt=1");
    }
    else if(!$manter) {
      header("Location: clientes.php?alt=1");
    }
  }
}

$query_rsP = "SELECT * FROM clientes WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

if($row_rsP['novo'] == 1) {
  $query_rsVisto = "UPDATE clientes SET novo=0 WHERE id = :id";
  $rsVisto = DB::getInstance()->prepare($query_rsVisto);
  $rsVisto->bindParam(':id', $id, PDO::PARAM_INT);  
  $rsVisto->execute();
  $totalRows_rsVisto = $rsVisto->rowCount();
}

$query_rsEnc = "SELECT * FROM encomendas WHERE id_cliente=:id AND (estado = 2 OR estado = 3 OR estado = 4)";
$rsEnc = DB::getInstance()->prepare($query_rsEnc);
$rsEnc->bindParam(':id', $id, PDO::PARAM_INT);
$rsEnc->execute();
$totalRows_rsEnc = $rsEnc->rowCount();

$query_rsEncProd = "SELECT ep.* FROM encomendas_produtos ep, encomendas e WHERE e.id_cliente=:id AND e.id=ep.id_encomenda AND (e.estado=2 OR e.estado=3 OR e.estado=4)";
$rsEncProd = DB::getInstance()->prepare($query_rsEncProd);
$rsEncProd->bindParam(':id', $id, PDO::PARAM_INT);
$rsEncProd->execute();
$row_rsEncProd = $rsEncProd->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEncProd = $rsEncProd->rowCount();

//Nº de encomendas a aguardar pagamento
$query_rsEstado1 = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente=:id AND estado=1";
$rsEstado1 = DB::getInstance()->prepare($query_rsEstado1);
$rsEstado1->bindParam(':id', $id, PDO::PARAM_INT);
$rsEstado1->execute();
$row_rsEstado1 = $rsEstado1->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEstado1 = $rsEstado1->rowCount();

//Nº de encomendas em processamento
$query_rsEstado2 = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente=:id AND estado=2";
$rsEstado2 = DB::getInstance()->prepare($query_rsEstado2);
$rsEstado2->bindParam(':id', $id, PDO::PARAM_INT);
$rsEstado2->execute();
$row_rsEstado2 = $rsEstado2->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEstado2 = $rsEstado2->rowCount();

//Nº de encomendas enviadas
$query_rsEstado3 = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente=:id AND estado=3";
$rsEstado3 = DB::getInstance()->prepare($query_rsEstado3);
$rsEstado3->bindParam(':id', $id, PDO::PARAM_INT);
$rsEstado3->execute();
$row_rsEstado3 = $rsEstado3->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEstado3 = $rsEstado3->rowCount();

//Nº de encomendas concluídas
$query_rsEstado4 = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente=:id AND estado=4";
$rsEstado4 = DB::getInstance()->prepare($query_rsEstado4);
$rsEstado4->bindParam(':id', $id, PDO::PARAM_INT);
$rsEstado4->execute();
$row_rsEstado4 = $rsEstado4->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEstado4 = $rsEstado4->rowCount();

//Nº de encomendas anuladas
$query_rsEstado5 = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente=:id AND estado=5";
$rsEstado5 = DB::getInstance()->prepare($query_rsEstado5);
$rsEstado5->bindParam(':id', $id, PDO::PARAM_INT);
$rsEstado5->execute();
$row_rsEstado5 = $rsEstado5->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEstado5 = $rsEstado5->rowCount();

//Nº de encomendas prontas para levantamento
$query_rsEstado6 = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente=:id AND estado=6";
$rsEstado6 = DB::getInstance()->prepare($query_rsEstado6);
$rsEstado6->bindParam(':id', $id, PDO::PARAM_INT);
$rsEstado6->execute();
$row_rsEstado6 = $rsEstado6->fetch(PDO::FETCH_ASSOC);
$totalRows_rsEstado6 = $rsEstado6->rowCount();

//Calcular o total de encomendas
$query_rsTotalEnc = "SELECT COUNT(id) AS total FROM encomendas WHERE id_cliente=:id AND (estado=2 OR estado=3 OR estado=4 OR estado=6)";
$rsTotalEnc = DB::getInstance()->prepare($query_rsTotalEnc);
$rsTotalEnc->bindParam(':id', $id, PDO::PARAM_INT);
$rsTotalEnc->execute();
$row_rsTotalEnc = $rsTotalEnc->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTotalEnc = $rsTotalEnc->rowCount();

//Calcular o total de productos
$query_rsTotalProd = "SELECT COUNT(e.id) AS total FROM encomendas e, encomendas_produtos ep WHERE e.id_cliente=:id AND e.id=ep.id_encomenda AND (e.estado=2 OR e.estado=3 OR e.estado=4 OR e.estado=6)";
$rsTotalProd = DB::getInstance()->prepare($query_rsTotalProd);
$rsTotalProd->bindParam(':id', $id, PDO::PARAM_INT);
$rsTotalProd->execute();
$row_rsTotalProd = $rsTotalProd->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTotalProd = $rsTotalProd->rowCount();

//Calcular a média de productos por encomenda
$total_encomendas = $row_rsTotalEnc['total'];
$total_productos = $row_rsTotalProd['total'];

$media1 = round($total_productos / $total_encomendas, 2);

//Calcular qual o producto preferido
$query_rsProdFav = "SELECT ep.produto, COUNT(ep.produto) AS total FROM encomendas e, encomendas_produtos ep WHERE e.id_cliente=:id AND e.id=ep.id_encomenda AND (e.estado=2 OR e.estado=3 OR e.estado=4 OR e.estado=6)";
$rsProdFav = DB::getInstance()->prepare($query_rsProdFav);
$rsProdFav->bindParam(':id', $id, PDO::PARAM_INT);
$rsProdFav->execute();
$row_rsProdFav = $rsProdFav->fetch(PDO::FETCH_ASSOC);
$totalRows_rsProdFav = $rsProdFav->rowCount();

//Calcular o total gasto sem iva e com iva
$total_gasto_sem_iva = 0;
$total_gasto = 0;

if($totalRows_rsEnc > 0) {
  while($row = $rsEnc->fetch()) {
    if($row['moeda'] == '$') {
      $convert = $row['valor_total'] * $row['valor_conversao'];
      $total_gasto_sem_iva+=$convert;

      $convert = $row['valor_c_iva'] * $row['valor_conversao'];
      $total_gasto+=$convert;
    }
    else if($row['moeda'] == '&pound;') {
      $convert = $row['valor_total'] * $row['valor_conversao'];
      $total_gasto_sem_iva+=$convert;

      $convert = $row['valor_c_iva'] * $row['valor_conversao'];
      $total_gasto+=$convert;
    }
    else {
      $total_gasto_sem_iva+=$row['valor_total'];

      $total_gasto+=$row['valor_c_iva'];
    }
  }
}

//Calcular a média de gasto por encomenda
$query_rsMedia = "SELECT * FROM encomendas WHERE id_cliente=:id_cliente AND (estado=2 OR estado=3 OR estado=4 OR estado=6)";
$rsMedia = DB::getInstance()->prepare($query_rsMedia);
$rsMedia->bindParam(':id_cliente', $id, PDO::PARAM_INT);
$rsMedia->execute();
$totalRows_rsMedia = $rsMedia->rowCount();

$media_encomenda = 0;

if($totalRows_rsMedia > 0) {
  while($row = $rsMedia->fetch()) {
    if($row['moeda'] == '$') {
      $convert = $row['valor_c_iva'] * $row['valor_conversao'];
      $media_encomenda += $convert;
    }
    else if($row['moeda'] == '&pound;') {
      $convert = $row['valor_c_iva'] * $row['valor_conversao'];
      $media_encomenda += $convert;
    }
    else {
      $media_encomenda+=$row['valor_c_iva'];
    }
  }
}

$media_encomenda = $media_encomenda / $total_encomendas;

//Retorna uma lista com a lista de desejos deste cliente
$query_rsListaDesejos = "SELECT p.*, l.cliente, l.produto FROM lista_desejo l, l_pecas_en p WHERE l.cliente=:id AND l.produto=p.id";
$rsListaDesejos = DB::getInstance()->prepare($query_rsListaDesejos);
$rsListaDesejos->bindParam(':id', $id, PDO::PARAM_INT);
$rsListaDesejos->execute();
$totalRows_rsListaDesejos = $rsListaDesejos->rowCount();

//Retorna uma lista com a com os produtos que o cliente está a seguir
$query_rsListaSeguir = "SELECT p.*, l.id_cliente, l.id_produto, l.preco, l.id_opcao FROM l_pecas_seguir l, l_pecas_en p WHERE l.id_cliente=:id AND l.id_produto=p.id";
$rsListaSeguir = DB::getInstance()->prepare($query_rsListaSeguir);
$rsListaSeguir->bindParam(':id', $id, PDO::PARAM_INT);
$rsListaSeguir->execute();
$totalRows_rsListaSeguir = $rsListaSeguir->rowCount();


$query_rsPais = "SELECT id, nome FROM paises ORDER BY nome ASC";
$rsPais = DB::getInstance()->prepare($query_rsPais);
$rsPais->execute();
$totalRows_rsPais = $rsPais->rowCount();

$query_rsAtividades = "SELECT * FROM clientes_atividades".$extensao." ORDER BY id ASC";
$rsAtividades = DB::getInstance()->prepare($query_rsAtividades);
$rsAtividades->execute();
$totalRows_rsAtividades = $rsAtividades->rowCount();

DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
  #div_atividade {
    display: none;
  }
  #div_atividade2 {
    display: none;
  }
</style>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<script type="text/javascript">
function enviaMail() {
  $.post("clientes-rpc.php", {op:"enviaMail", id:'<?php echo $id; ?>'}, function(data){
    window.location = 'clientes-edit.php?id=<?php echo $id; ?>&env=1';                                      
  });
}

function validaRegisto() {
  $.post("clientes-rpc.php", {op:"validaRegisto", id:'<?php echo $id; ?>'}, function(data) {
    window.location = 'clientes-edit.php?id=<?php echo $id; ?>&env=2';                                      
  });
}
</script>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<div class="clearfix"> </div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content"> 
      <!-- BEGIN PAGE HEADER-->
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['clientes']; ?> <small><?php echo $RecursosCons->RecursosCons['alterar_utilizador']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="clientes.php"> <?php echo $RecursosCons->RecursosCons['clientes']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"> <?php echo $RecursosCons->RecursosCons['alterar_utilizador']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
            </div>
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='clientes.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modal_enviar_mail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['registo_email']; ?></h4>
            </div>
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['env_email_cliente_msg']; ?> </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="enviaMail();"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modal_validar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['validar_registo']; ?></h4>
            </div>
            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['validar_txt']; ?> </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="validaRegisto();"><?php echo $RecursosCons->RecursosCons['text_visivel_sim']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
        </div>
      </div>
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="row">
        <div class="col-md-12">
          <form id="form_cliente_ed" name="form_cliente_ed" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="1">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-user"></i><?php echo $RecursosCons->RecursosCons['clientes']; ?> - <?php echo $row_rsP["nome"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='clientes.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="button" class="btn green to-hide" onClick="document.form_cliente_ed.submit();"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="submit" class="btn green to-hide" onClick="document.getElementById('manter').value='1'"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a> 
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_1" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1';"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='observacoes.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_observacoes']; ?> </a> </li>
										<li class="nav-tab-stats <?php if($tab_sel==3) echo "active"; ?>"> <a href="#tab_3" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3';"> <?php echo $RecursosCons->RecursosCons['tab_estatisticas']; ?> </a> </li>
                    <li class="nav-tab-stats <?php if($tab_sel==4) echo "active"; ?>"> <a href="#tab_4" data-toggle="tab" onClick="document.getElementById('tab_sel').value='4';"> <?php echo $RecursosCons->RecursosCons['tab_lista_desejos']; ?> </a> </li>
                    <li class="nav-tab-stats <?php if($tab_sel==5) echo "active"; ?>"> <a href="#tab_5" data-toggle="tab" onClick="document.getElementById('tab_sel').value='5';"> <?php echo $RecursosCons->RecursosCons['tab_seguir_produto']; ?> </a> </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane <?php if($tab_sel == 1) echo "active"; ?>" id="tab_1">
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          <span> <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </span>
                        </div>  
                        <?php if($_GET['env'] == 1) { ?>                    
                          <div class="alert alert-info">
                            <button class="close" data-close="alert"></button>
                            <?php echo $RecursosCons->RecursosCons['email_enviado']; ?> 
                          </div>
                        <?php } ?>
                        <?php if($_GET['env'] == 2) { ?>                    
                          <div class="alert alert-info">
                            <button class="close" data-close="alert"></button>
                            <?php echo $RecursosCons->RecursosCons['cliente_validado_suc']; ?>
                          </div>
                        <?php } ?>
                        <?php if($_GET['suc'] == 1) { ?>                    
                          <div class="alert alert-info">
                            <button class="close" data-close="alert"></button>
                            <?php echo $RecursosCons->RecursosCons['cliente_inserido']; ?>
                          </div>
                        <?php } ?>
                        <?php if($_GET['alt'] == 1) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span> <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> </span>
                          </div>
                        <?php } ?>
                        <?php if($erro_password == 1) { ?>
                          <div class="alert alert-danger display-show">
                            <button class="close" data-close="alert"></button>
                            <span> <?php echo $RecursosCons->RecursosCons['pass_error']; ?> </span>
                          </div>  
                        <?php } ?>
                        <?php if($erro_email == 1) { ?>
                          <div class="alert alert-danger display-show">
                            <button class="close" data-close="alert"></button>
                            <span> <?php echo $RecursosCons->RecursosCons['email_existe']; ?> </span>
                          </div>  
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label"><strong><?php echo $RecursosCons->RecursosCons['validar_tit']; ?> </strong></label>
                          <div class="col-md-3" style="padding-top: 7px;">
                            <?php if($row_rsP['validado'] == 0) { ?>
                              <a href="#modal_validar" data-toggle="modal" class="btn btn-sm green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['validar_cliente']; ?></a>
                            <?php } else { ?>
                              <i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['cliente_validado']; ?>
                            <?php } ?>
                          </div>
                          <div class="col-md-1"></div>
                          <div class="col-md-3" style="padding-top: 7px;">
                            <a href="#modal_enviar_mail" data-toggle="modal" class="btn btn-sm blue"><i class="fa fa-mail-forward"></i> <?php echo $RecursosCons->RecursosCons['enviar_email_dados_registo']; ?></a>
                          </div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="tipo"><?php echo $RecursosCons->RecursosCons['tipo_label']; ?>: <span class="required"> * </span> </label>
                          <div class="col-md-3">
                            <select class="form-control" name="tipo" id="tipo">
                              <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                              <option value="1" <?php if($row_rsP['tipo'] == 1) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['tipo1_label']; ?></option>
                              
                            </select>
                          </div>
                          <label class="col-md-2 control-label" for="ultima_entrada"><?php echo $RecursosCons->RecursosCons['data_ultimo_login']; ?>: </label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" name="ultima_entrada" id="ultima_entrada" value="<?php echo $row_rsP['ultima_entrada']; ?>" disabled/>
                          </div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="nif"><?php echo $RecursosCons->RecursosCons['cli_contribuinte']; ?>: </label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" name="nif" id="nif" value="<?php echo $row_rsP['nif']; ?>">
                          </div>
                          <label class="col-md-2 control-label" for="data_nasc"><?php echo $RecursosCons->RecursosCons['cli_data_nasc']; ?>: </label>
                          <div class="col-md-3">
                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                              <input type="text" class="form-control form-filter input-sm" name="data_nasc" placeholder="<?php echo $RecursosCons->RecursosCons['data_label']; ?>" id="data_nasc" value="<?php echo $row_rsP['data_nasc']; ?>">
                              <span class="input-group-btn">
                              <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                              </span> 
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['cli_nome_empresa']; ?>: <span class="required"> * </span> </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="pessoa"><?php echo $RecursosCons->RecursosCons['ar_pessoa_contacto']; ?>: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="pessoa" id="pessoa" value="<?php echo $row_rsP['pessoa']; ?>">
                          </div>
                        </div>
                        <div class="form-group" id="div_atividade">
                          <label class="col-md-2 control-label" for="atividade"><?php echo $RecursosCons->RecursosCons['atividade_label']; ?>: </label>
                          <div class="col-md-8">
                            <select class="form-control" name="atividade" id="atividade" onChange="verificaAtividade(this.value);">
                              <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                              <?php if($totalRows_rsAtividades > 0) {
                                while($row_rsAtividades = $rsAtividades->fetch()) { ?>
                                  <option value="<?php echo $row_rsAtividades['nome']; ?>" <?php if($row_rsP['atividade'] == $row_rsAtividades['nome']) echo "selected"; ?>><?php echo $row_rsAtividades['nome']; ?></option>
                                <?php }
                              } ?>
                              <option value="<?php echo $RecursosCons->RecursosCons['atividade_outro']; ?>" <?php if($row_rsP['atividade'] == $RecursosCons->RecursosCons['atividade_outro']) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['atividade_outro']; ?></option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group" id="div_atividade2">
                          <div class="col-md-2"></div>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="atividade2" id="atividade2" value="<?php echo $row_rsP['atividade2']; ?>" placeholder="<?php echo $RecursosCons->RecursosCons['atividade_qual']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="email"><?php echo $RecursosCons->RecursosCons['cli_email']; ?>: <span class="required"> * </span> </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $row_rsP['email']; ?>" data-required="1"  autocomplete="new-email">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="pass"><?php echo $RecursosCons->RecursosCons['cli_password']; ?>: </label>
                          <div class="col-md-3">
                            <input type="password" class="form-control" name="pass" id="pass" value="" data-required="1" autocomplete="new-password">
                          </div>
                          <label class="col-md-2 control-label" for="pass2"><?php echo $RecursosCons->RecursosCons['cli_rep_password']; ?>: </label>
                          <div class="col-md-3">
                            <input type="password" class="form-control" name="pass2" id="pass2" value="" data-required="1" autocomplete="new-password2">
                          </div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="morada"><?php echo $RecursosCons->RecursosCons['cli_morada']; ?>: </label>
                          <div class="col-md-3">
                            <textarea class="form-control" rows="2" id="morada" name="morada"><?php echo $row_rsP['morada']; ?></textarea>
                          </div>
                          <label class="col-md-2 control-label" for="cpostal"><?php echo $RecursosCons->RecursosCons['cli_cod_postal']; ?>: </label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" name="cpostal" id="cpostal" value="<?php echo $row_rsP['cod_postal']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="localidade"><?php echo $RecursosCons->RecursosCons['cli_localidade']; ?>: </label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" name="localidade" id="localidade" value="<?php echo $row_rsP['localidade']; ?>">
                          </div>
                          <label class="col-md-2 control-label" for="pais"><?php echo $RecursosCons->RecursosCons['cli_pais']; ?>:</label>
                          <div class="col-md-3">
                            <select class="form-control select2me" id="pais" name="pais" >
                              <option value=""><option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option></option>
                              <?php if($totalRows_rsPais > 0) { ?>
                                <?php while($row_rsPais = $rsPais->fetch()) { ?>
                                  <option value="<?php echo $row_rsPais['id']; ?>" <?php if($row_rsPais['id']==$row_rsP['pais']) echo "selected"; ?>><?php echo $row_rsPais['nome']; ?></option>
                                <?php } ?>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="telefone"><?php echo $RecursosCons->RecursosCons['cli_telefone']; ?>: </label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" name="telefone" id="telefone" value="<?php echo $row_rsP['telefone']; ?>">
                          </div>
                          <label class="col-md-2 control-label" for="telemovel"><?php echo $RecursosCons->RecursosCons['cli_telemovel']; ?>: </label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" name="telemovel" id="telemovel" value="<?php echo $row_rsP['telemovel']; ?>">
                          </div>
                        </div>  
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="desconto"><?php echo $RecursosCons->RecursosCons['cli_desconto']; ?>:</label>
                          <div class="col-md-3">
                            <div class="input-group">
                              <input type="text" class="form-control" name="desconto" id="desconto" value="<?php echo $row_rsP['desconto']; ?>" maxlength="2" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                              <span class="input-group-addon">%</span>
                            </div>                        
                          </div>
                        </div>  
                      </div>
                    </div>
                    <div class="tab-pane <?php if($tab_sel == 3) echo "active"; ?>" id="tab_3">
                      <div class="form-body">
                        <div class="clearfix margin-top-20 margin-bottom-20"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['nota_txt']; ?>!</span> <span><?php echo $RecursosCons->RecursosCons['nota_estatisticas']; ?></span> </div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_total_encomendas']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $row_rsTotalEnc['total']; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_total_produtos']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $row_rsTotalProd['total']; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_media_produtos']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $media1; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_prod_preferido']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php if($rows_rsProdFav['produto'] != null) { echo $row_rsProdFav['produto']; } else { echo "-"; } ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_total_gasto_s/iva']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo round($total_gasto_sem_iva, 2)." £"; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_total_gasto_c/iva']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo round($total_gasto, 2)." €"; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_gasto_medio']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo round($media_encomenda, 2)." €"; ?></div>
                          </div>
                        </div>
                        <hr>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_enc_aguarda_pag']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $row_rsEstado1['total']; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_enc_procesamento']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $row_rsEstado2['total']; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_enc_prontas']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $row_rsEstado6['total']; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_enc_enviadas']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $row_rsEstado3['total']; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_enc_concluidas']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $row_rsEstado4['total']; ?></div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_enc_anuladas']; ?>:</strong></label>
                          <div class="col-md-5">
                            <div class="form-control" style="border:0;"><?php echo $row_rsEstado5['total']; ?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <style type="text/css">
                      table tr:nth-child(even) {
                        background-color: #eee;
                      }

                      @media (max-width: 1100px) {
                        table {
                          width: 100% !important;
                        }
                      }
                    </style>
                    <div class="tab-pane <?php if($tab_sel == 4) echo "active"; ?>" id="tab_4">
                      <div class="form-body">
                        <?php if($totalRows_rsListaDesejos > 0) { ?>
                          <table style="width: 100%">
                            <tr>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['ref']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['nome']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['preco']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['disponibilidade']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['acoes']; ?></strong></td>
                            </tr>
                            <?php while($row_rsListaDesejos = $rsListaDesejos->fetch()) { ?>
                              <tr>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px"><?php echo $row_rsListaDesejos['ref']; ?></td>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px"><?php echo $row_rsListaDesejos['nome']; ?></td>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px"><?php echo $row_rsListaDesejos['preco']." €"; ?></td>
                                <td style="text-align: center; width: 20%; padding-bottom: 5px; padding-top: 5px"><?php if($row_rsListaDesejos['stock'] > 0) { echo "<span class='label label-success'><?php echo $RecursosCons->RecursosCons['disponivel']; ?></span>"; } else { echo "<span class='label label-danger'><?php echo $RecursosCons->RecursosCons['esgotado']; ?></span>"; } ?></td>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px"><a style="margin-top:3px;" href="../produtos/produtos-edit.php?id=<?php echo $row_rsListaDesejos['id']; ?>" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?></a></td>
                              </tr>
                            <?php } ?>
                          </table>
                        <?php }
                        else { ?>
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['sem_info']; ?></strong></label>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="tab-pane <?php if($tab_sel == 5) echo "active"; ?>" id="tab_5">
                      <div class="form-body">
                        <?php if($totalRows_rsListaSeguir > 0) { ?>
                          <table style="width: 100%">
                            <tr>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['ref']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['nome']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['opcoes']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['preco']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['acoes']; ?></strong></td>
                              <!-- <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['notif_baixa_preco']; ?></strong></td>
                              <td style="text-align: center; padding-bottom: 10px; font-size: 14px"><strong><?php echo $RecursosCons->RecursosCons['notif_sobre_stock']; ?></strong></td> -->
                            </tr>
                            <?php while($row_rsListaSeguir = $rsListaSeguir->fetch()) { ?>
                              <tr>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px"><?php echo $row_rsListaSeguir['ref']; ?></td>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px"><?php echo $row_rsListaSeguir['nome']; ?></td>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px">
                                  <?php if($row_rsListaSeguir['id_opcao'] > 0) {
                                    $query_rsTamanho = "SELECT car1, op1, car2, op2, car3, op3, car4, op4, car5, op5 FROM l_pecas_tamanhos WHERE peca =:id_peca AND id=:id";
                                    $rsTamanho = DB::getInstance()->prepare($query_rsTamanho);
                                    $rsTamanho->bindParam(':id_peca', $row_rsListaSeguir['id_produto'], PDO::PARAM_INT);
                                    $rsTamanho->bindParam(':id', $row_rsListaSeguir['id_opcao'], PDO::PARAM_INT);
                                    $rsTamanho->execute();
                                    $totalRows_rsTamanho = $rsTamanho->rowCount();
                                    $row_rsTamanho = $rsTamanho->fetch(PDO::FETCH_ASSOC);
                                    DB::close();

                                    if($totalRows_rsTamanho > 0) {
                                      $opcoes = '';

                                      $nome_opcao1 = NULL;
                                      $nome_opcao2 = NULL;
                                      $nome_opcao3 = NULL;
                                      $nome_opcao4 = NULL;
                                      $nome_opcao5 = NULL;

                                      $car1 = $row_rsTamanho['car1'];
                                      $op1 = $row_rsTamanho['op1'];
                                      $car2 = $row_rsTamanho['car2'];
                                      $op2 = $row_rsTamanho['op2'];
                                      $car3 = $row_rsTamanho['car3'];
                                      $op3 = $row_rsTamanho['op3'];
                                      $car4 = $row_rsTamanho['car4'];
                                      $op4 = $row_rsTamanho['op4'];
                                      $car5 = $row_rsTamanho['car5'];
                                      $op5 = $row_rsTamanho['op5'];

                                      $query_rsOpcao = "SELECT nome FROM l_caract_opcoes_pt WHERE id =:op1";
                                      $rsOpcao = DB::getInstance()->prepare($query_rsOpcao);
                                      $rsOpcao->bindParam(':op1', $op1, PDO::PARAM_INT);
                                      $rsOpcao->execute();
                                      $row_rsOpcao = $rsOpcao->fetch(PDO::FETCH_ASSOC);
                                      DB::close();

                                      if($car1 == 1) {
                                        $nome_opcao1 = $RecursosCons->RecursosCons['cor_label'].": ".$row_rsOpcao['nome'];
                                      }
                                      else if($car1 == 2) {
                                        $nome_opcao1 = $RecursosCons->RecursosCons['tamanho_label'].": ".$row_rsOpcao['nome'];
                                      }
                                      else {
                                        $query_rsCatOpcao = "SELECT nome FROM l_caract_categorias_pt WHERE id =:car1";
                                        $rsCatOpcao = DB::getInstance()->prepare($query_rsCatOpcao);
                                        $rsCatOpcao->bindParam(':car1', $car1, PDO::PARAM_INT);
                                        $rsCatOpcao->execute();
                                        $row_rsCatOpcao = $rsCatOpcao->fetch(PDO::FETCH_ASSOC);
                                        DB::close();

                                        $nome_opcao1 = $row_rsCatOpcao['nome'].": ".$row_rsOpcao['nome'];
                                      }

                                      $opcoes .= $nome_opcao1."<br>";

                                      if($car2 > 0 && $op2 > 0) {
                                        $query_rsOpcao = "SELECT nome FROM l_caract_opcoes_pt WHERE id=:op2";
                                        $rsOpcao = DB::getInstance()->prepare($query_rsOpcao);
                                        $rsOpcao->bindParam(':op2', $op2, PDO::PARAM_INT);
                                        $rsOpcao->execute();
                                        $row_rsOpcao = $rsOpcao->fetch(PDO::FETCH_ASSOC);
                                        DB::close();

                                        if($car2 == 1) {
                                          $nome_opcao2 = $RecursosCons->RecursosCons['cor_label'].": ".$row_rsOpcao['nome'];
                                        }
                                        else if($car2 == 2) {
                                          $nome_opcao2 = $RecursosCons->RecursosCons['tamanho_label'].": ".$row_rsOpcao['nome'];
                                        }
                                        else {
                                          $query_rsCatOpcao = "SELECT nome FROM l_caract_categorias_pt WHERE id =:car2";
                                          $rsCatOpcao = DB::getInstance()->prepare($query_rsCatOpcao);
                                          $rsCatOpcao->bindParam(':car2', $car2, PDO::PARAM_INT);
                                          $rsCatOpcao->execute();
                                          $row_rsCatOpcao = $rsCatOpcao->fetch(PDO::FETCH_ASSOC);
                                          DB::close();

                                          $nome_opcao2 = $row_rsCatOpcao['nome'].": ".$row_rsOpcao['nome'];
                                        }

                                        $opcoes .= $nome_opcao2."<br>";
                                      }

                                      if($car3 > 0 && $op3 > 0) {
                                        $query_rsOpcao = "SELECT nome FROM l_caract_opcoes_pt WHERE id=:op3";
                                        $rsOpcao = DB::getInstance()->prepare($query_rsOpcao);
                                        $rsOpcao->bindParam(':op3', $op3, PDO::PARAM_INT);
                                        $rsOpcao->execute();
                                        $row_rsOpcao = $rsOpcao->fetch(PDO::FETCH_ASSOC);
                                        DB::close();

                                        if($car3 == 1) {
                                          $nome_opcao3 = $RecursosCons->RecursosCons['cor_label'].": ".$row_rsOpcao['nome'];
                                        }
                                        else if($car3 == 2) {
                                          $nome_opcao3 = $RecursosCons->RecursosCons['tamanho_label'].": ".$row_rsOpcao['nome'];
                                        }
                                        else {
                                          $query_rsCatOpcao = "SELECT nome FROM l_caract_categorias_pt WHERE id =:car3";
                                          $rsCatOpcao = DB::getInstance()->prepare($query_rsCatOpcao);
                                          $rsCatOpcao->bindParam(':car3', $car3, PDO::PARAM_INT);
                                          $rsCatOpcao->execute();
                                          $row_rsCatOpcao = $rsCatOpcao->fetch(PDO::FETCH_ASSOC);
                                          DB::close();

                                          $nome_opcao3 = $row_rsCatOpcao['nome'].": ".$row_rsOpcao['nome'];
                                        }

                                        $opcoes .= $nome_opcao3."<br>";
                                      }

                                      if($car4 > 0 && $op4 > 0) {
                                        $query_rsOpcao = "SELECT nome FROM l_caract_opcoes_pt WHERE id =:op4";
                                        $rsOpcao = DB::getInstance()->prepare($query_rsOpcao);
                                        $rsOpcao->bindParam(':op4', $op4, PDO::PARAM_INT);
                                        $rsOpcao->execute();
                                        $row_rsOpcao = $rsOpcao->fetch(PDO::FETCH_ASSOC);
                                        DB::close();

                                        if($car4 == 1) {
                                          $nome_opcao4 = $RecursosCons->RecursosCons['cor_label'].": ".$row_rsOpcao['nome'];
                                        }
                                        else if($car4 == 2) {
                                          $nome_opcao4 = $RecursosCons->RecursosCons['tamanho_label'].": ".$row_rsOpcao['nome'];
                                        }
                                        else {
                                          $query_rsCatOpcao = "SELECT nome FROM l_caract_categorias_pt WHERE id =:car4";
                                          $rsCatOpcao = DB::getInstance()->prepare($query_rsCatOpcao);
                                          $rsCatOpcao->bindParam(':car4', $car4, PDO::PARAM_INT);
                                          $rsCatOpcao->execute();
                                          $row_rsCatOpcao = $rsCatOpcao->fetch(PDO::FETCH_ASSOC);
                                          DB::close();

                                          $nome_opcao4 = $row_rsCatOpcao['nome'].": ".$row_rsOpcao['nome'];
                                        }

                                        $opcoes .= $nome_opcao4."<br>";
                                      }

                                      if($car5 > 0 && $op5 > 0) {
                                        $query_rsOpcao = "SELECT nome FROM l_caract_opcoes_pt WHERE id =:op5";
                                        $rsOpcao = DB::getInstance()->prepare($query_rsOpcao);
                                        $rsOpcao->bindParam(':op5', $op5, PDO::PARAM_INT);
                                        $rsOpcao->execute();
                                        $row_rsOpcao = $rsOpcao->fetch(PDO::FETCH_ASSOC);
                                        DB::close();

                                        if($car5 == 1) {
                                          $nome_opcao5 =  $RecursosCons->RecursosCons['cor_label'].": ".$row_rsOpcao['nome'];
                                        }
                                        else if($car5 == 2) {
                                          $nome_opcao5 = $RecursosCons->RecursosCons['tamanho_label'].": ".$row_rsOpcao['nome'];
                                        }
                                        else {
                                          $query_rsCatOpcao = "SELECT nome FROM l_caract_categorias_pt WHERE id =:car5";
                                          $rsCatOpcao = DB::getInstance()->prepare($query_rsCatOpcao);
                                          $rsCatOpcao->bindParam(':car5', $car5, PDO::PARAM_INT);
                                          $rsCatOpcao->execute();
                                          $row_rsCatOpcao = $rsCatOpcao->fetch(PDO::FETCH_ASSOC);
                                          DB::close();

                                          $nome_opcao5 = $row_rsCatOpcao['nome'].": ".$row_rsOpcao['nome'];
                                        }

                                        $opcoes .= $nome_opcao5."<br>";
                                      }
                                    }
                                    else {
                                      $opcoes = '---';
                                    }
                                  }
                                  else {
                                    $opcoes = '---';
                                  }
                                  
                                  echo $opcoes; ?>
                                </td>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px"><?php echo $row_rsListaSeguir['preco']." €"; ?></td>
                                <td style="text-align: center; padding-bottom: 5px; padding-top: 5px"><a style="margin-top:3px;" href="../produtos/produtos-edit.php?id=<?php echo $row_rsListaSeguir['id']; ?>" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?></a></td>
                                <!-- <td style="text-align: center; width: 20%; padding-bottom: 5px; padding-top: 5px"><?php if($row_rsListaSeguir['preco_geral']==1) { echo "<i data-toggle='tooltip' data-placement='bottom' title='<?php echo $RecursosCons->RecursosCons['notif_cli_descida_preco']; ?>' style='color:green' class='fa fa-check fa-lg'></i>"; } else { echo "<i data-toggle='tooltip' data-placement='bottom' title='<?php echo $RecursosCons->RecursosCons['nao_recebe_nofic']; ?>' style='color:red' class='fa fa-times fa-lg'></i>"; } ?></td>
                                <td style="text-align: center; width: 15%; padding-bottom: 5px; padding-top: 5px"><?php if($row_rsListaSeguir['stock_geral']==1) { echo "<i data-toggle='tooltip' data-placement='bottom' title='<?php echo $RecursosCons->RecursosCons['notif_prod_esgotado']; ?>' style='color:green' class='fa fa-check fa-lg'></i>"; } else { echo "<i data-toggle='tooltip' data-placement='bottom' title='<?php echo $RecursosCons->RecursosCons['nao_recebe_nofic_2']; ?>' style='color:red' class='fa fa-times fa-lg'></i>"; } ?></td> -->
                              </tr>
                            <?php } ?>
                          </table>
                        <?php }
                        else { ?>
                          <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['sem_info']; ?></strong></label>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>   
            </div>
            <input type="hidden" name="MM_insert" value="form_cliente_ed" />
          </form>
        </div>
      </div>
      <!-- END PAGE CONTENT--> 
    </div>
  </div>
  <!-- END CONTENT -->
  <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<!-- BEGIN PAGE LEVEL PLUGINS --> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<!-- LINGUA PORTUGUESA -->
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  User.init();

  if(<?php echo $row_rsP['tipo']; ?> == 2){
    $('#div_atividade').css('display', 'block');
    verificaAtividade('<?php echo $row_rsP["atividade"]; ?>');
  }

  $("#tipo").change(function(){
    if($(this).val() == 2){
      $('#div_atividade').css('display', 'block');
      verificaAtividade('<?php echo $row_rsP["atividade"]; ?>');
    }
    else{
      $('#div_atividade2').css('display', 'none');
      $('#div_atividade').css('display', 'none');
    }
  });
});

function verificaAtividade(val) {
  if(val == 'Outro') {
    $('#div_atividade2').css('display', 'block');
  }
  else {
    $('#div_atividade2').css('display', 'none');
  }
}
</script>
<script type="text/javascript">
var parts = window.location.search.substr(1).split("&");
var $_GET = {};

for(var i = 0; i < parts.length; i++) {
  var temp = parts[i].split("=");
  $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

if($_GET['tab_sel'] == 3 || $_GET['tab_sel'] == 4 || $_GET['tab_sel'] == 5) {
  $('.to-hide').hide();
}
else {
  $('.to-hide').show();
}

$('.nav-tab').click(function() {
  if($(this).hasClass('nav-tab-stats')) {
    $('.to-hide').hide();
  }
  else {
    $('.to-hide').show();
  }
});
</script>
</body>
<!-- END BODY -->
</html>