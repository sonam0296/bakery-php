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
                          <label class="col-md-2 control-label" for="nome">User Name: <span class="required"> * </span> </label>
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
                        