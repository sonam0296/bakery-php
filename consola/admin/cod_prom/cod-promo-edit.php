<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='outros_promo';
$menu_sub_sel='listagem';

$id = $_GET['id'];

$tab_sel = 1;
if(isset($_GET['tab_sel'])) {
  $tab_sel = $_GET['tab_sel'];
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_codigo")) {
	if($_POST['nome']!="" && $_POST['codigo'] != '' && $_POST['desconto'] != ''){
		$manter = $_POST['manter'];

    $datai = NULL;
    if(isset($_POST['datai']) && $_POST['datai'] != "0000-00-00" && $_POST['datai'] != "") $datai = $_POST['datai'];
    $dataf = NULL;
    if(isset($_POST['dataf']) && $_POST['dataf'] != "0000-00-00" && $_POST['dataf'] != "") $dataf = $_POST['dataf'];

		$insertSQL = "UPDATE codigos_promocionais SET nome=:nome, codigo=:codigo, desconto=:desconto, tipo_desconto=:tipo_desconto, tipo=:tipo, grupo=:grupo, id_cliente=:id_cliente, limite_total=:limite_total, id_categoria=:id_categoria, id_peca=:id_peca, id_marca=:id_marca, id_country=:id_country, valor_minimo=:valor_minimo, limite_cliente=:limite_cliente, datai=:datai, dataf=:dataf, pagina=:pagina WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);	
		$rsInsert->bindParam(':codigo', $_POST['codigo'], PDO::PARAM_STR);
		$rsInsert->bindParam(':desconto', $_POST['desconto'], PDO::PARAM_STR);
		$rsInsert->bindParam(':valor_minimo', $_POST['valor_minimo'], PDO::PARAM_STR);
    $rsInsert->bindParam(':grupo', $_POST['grupo'], PDO::PARAM_INT);
    $rsInsert->bindParam(':id_cliente', $_POST['cliente'], PDO::PARAM_INT);	
		$rsInsert->bindParam(':limite_cliente', $_POST['limite_cliente'], PDO::PARAM_INT);
    $rsInsert->bindParam(':limite_total', $_POST['limite_de_clientes'], PDO::PARAM_INT);
    $rsInsert->bindParam(':tipo_desconto', $_POST['radio_desconto'], PDO::PARAM_INT);
    $rsInsert->bindParam(':tipo', $_POST['tipo_desconto'], PDO::PARAM_INT);
    $rsInsert->bindParam(':id_marca', $_POST['marca'], PDO::PARAM_INT);
    $rsInsert->bindParam(':id_categoria', $_POST['categoria'], PDO::PARAM_INT);
    $rsInsert->bindParam(':id_peca', $_POST['produto'], PDO::PARAM_INT);
    $rsInsert->bindParam(':id_country', $_POST['paises'], PDO::PARAM_INT);
		$rsInsert->bindParam(':datai', $datai, PDO::PARAM_STR);
		$rsInsert->bindParam(':dataf', $dataf, PDO::PARAM_STR);
    $rsInsert->bindParam(':pagina', $_POST['pagina'], PDO::PARAM_INT);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
		$rsInsert->execute();

    //Atualiza o texto do código na tabela codigos_promocionais_texto{$extensao}
    $insertSQL = "UPDATE codigos_promocionais_texto".$extensao." SET texto=:texto WHERE cod_prom=:cod_prom";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':cod_prom', $id, PDO::PARAM_INT); //id do código promocional
    $rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR);
    $rsInsert->execute();
    
    DB::close();

		if(!$manter) {
      header("Location: cod-promo.php?alt=1");
    }
    else {
      header("Location: cod-promo-edit.php?id=".$id."&alt=1"); 
    }
	}
}

$query_rsP = "SELECT * FROM codigos_promocionais WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsCodPromTexto = "SELECT texto FROM codigos_promocionais_texto".$extensao." WHERE cod_prom = :cod_prom";
$rsCodPromTexto = DB::getInstance()->prepare($query_rsCodPromTexto);
$rsCodPromTexto->bindParam(':cod_prom', $id, PDO::PARAM_INT);  
$rsCodPromTexto->execute();
$row_rsCodPromTexto = $rsCodPromTexto->fetch(PDO::FETCH_ASSOC);
$totalRows_rsCodPromTexto = $rsCodPromTexto->rowCount();

$query_rsPaginas = "SELECT id, nome FROM paginas_pt ORDER BY nome ASC";
$rsPaginas = DB::getInstance()->prepare($query_rsPaginas);
$rsPaginas->execute();
$totalRows_rsPaginas = $rsPaginas->rowCount();
$row_rsPaginas = $rsPaginas->fetch(PDO::FETCH_ASSOC);

$query_rsGrupos = "SELECT id, nome FROM clientes_grupos ORDER BY nome ASC";
$rsGrupos = DB::getInstance()->prepare($query_rsGrupos);
$rsGrupos->execute();
$totalRows_rsGrupos = $rsGrupos->rowCount();

if($row_rsP['grupo'] != NULL && $row_rsP['grupo'] != '' && $row_rsP['grupo'] > 0) {
  $query_rsClientes = "SELECT id, nome, email FROM clientes WHERE grupo = '".$row_rsP['grupo']."' ORDER BY nome ASC";
  $rsClientes = DB::getInstance()->prepare($query_rsClientes);
  $rsClientes->execute();
  $totalRows_rsClientes = $rsClientes->rowCount();
}
else {
  $query_rsClientes = "SELECT id, nome, email FROM clientes ORDER BY nome ASC";
  $rsClientes = DB::getInstance()->prepare($query_rsClientes);
  $rsClientes->execute();
  $totalRows_rsClientes = $rsClientes->rowCount();
}

$query_rsCategorias = "SELECT id, nome FROM l_categorias_en WHERE cat_mae = 0 ORDER BY nome ASC";
$rsCategorias = DB::getInstance()->prepare($query_rsCategorias);
$rsCategorias->execute();
$totalRows_rsCategorias = $rsCategorias->rowCount();

$query_rsMarcas = "SELECT id, nome FROM l_marcas_pt ORDER BY nome ASC";
$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);
$rsMarcas->execute();
$totalRows_rsMarcas = $rsMarcas->rowCount();

$query_rsEncomendas = "SELECT * FROM encomendas WHERE codigo_promocional = '".$row_rsP['codigo']."'";
$rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);
$rsEncomendas->execute();
$totalRows_rsEncomendas = $rsEncomendas->rowCount();

$query_rsTotalEncomendas = "SELECT SUM(IF(valor_conversao > 0, (valor_total + codigo_promocional_valor) / valor_conversao, valor_total + codigo_promocional_valor)) as total FROM encomendas WHERE codigo_promocional = '".$row_rsP['codigo']."'";
$rsTotalEncomendas = DB::getInstance()->prepare($query_rsTotalEncomendas);
$rsTotalEncomendas->execute();
$row_rsTotalEncomendas = $rsTotalEncomendas->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTotalEncomendas = $rsTotalEncomendas->rowCount();

$query_rsTotalDesconto = "SELECT SUM(IF(valor_conversao > 0, codigo_promocional_valor / valor_conversao, codigo_promocional_valor)) as total FROM encomendas WHERE codigo_promocional = '".$row_rsP['codigo']."'";
$rsTotalDesconto = DB::getInstance()->prepare($query_rsTotalDesconto);
$rsTotalDesconto->execute();
$row_rsTotalDesconto = $rsTotalDesconto->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTotalDesconto = $rsTotalDesconto->rowCount();
DB::close();

$perc_total = 0;
if($row_rsTotalEncomendas['total'] > 0) {
  $perc_total =  round(($row_rsTotalDesconto['total'] * 100) / $row_rsTotalEncomendas['total'], 2);
} 

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
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
      <h3 class="page-title"> Vales de Desconto <small>editar registo</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="cod-promo.php">Vales de Desconto</a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:;">Editar registo</a> </li>
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
              <h4 class="modal-title">Eliminar registo</h4>
            </div>
            <div class="modal-body"> Deseja eliminar o registo <?php echo $row_rsP["nome"]; ?>? </div>
            <div class="modal-footer">
              <button type="button" class="btn blue" onClick="document.location='cod-promo.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'">Ok</button>
              <button type="button" class="btn default" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="form_codigo" name="form_codigo" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="1">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Vales de Desconto - <?php echo $row_rsP["nome"]; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='cod-promo.php'"><i class="fa fa-angle-left"></i> Voltar</button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> Guardar</button>
                  <button type="submit" class="btn green to-hide" onClick="document.getElementById('manter').value='1'"><i class="fa fa-check-circle"></i> Guardar e manter na página</button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> Eliminar</a> 
                </div>
              </div>
              <div class="tabbable">
                <ul class="nav nav-tabs nav-tabs-lg">
                  <li class="active">
                    <a class="nav_tab" id="nav-tab1" href="#tab_1" data-toggle="tab">
                    Detalhes </a>
                  </li>
                  <li>
                    <a class="nav_tab" id="nav-tab2" href="#tab_2" data-toggle="tab">
                    Encomendas </a>
                  </li>
                  <li>
                    <a class="nav_tab" id="nav-tab3" href="#tab_3" data-toggle="tab">
                    Estatísticas </a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane <?php if($tab_sel == 1) { echo "active"; } ?>" id="tab_1">
                    <div class="form-body">
                      <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        Preencha todos os campos obrigatórios. 
                      </div>  
                      <?php if($_GET['alt'] == 1) { ?>                    
                        <div class="alert alert-success">
                          <button class="close" data-close="alert"></button>
                          Dados alterados com sucesso. 
                        </div>
                      <?php } ?>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="datai">Início: </label>
                        <div class="col-md-3">
                          <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control form-filter input-sm" name="datai" placeholder="Data" id="datai" value="<?php echo $row_rsP['datai']; ?>">
                            <span class="input-group-btn">
                            <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                            </span> 
                          </div>
                        </div>
                        <label class="col-md-2 control-label" for="dataf">Fim: </label>
                        <div class="col-md-3">
                          <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control form-filter input-sm" name="dataf" placeholder="Data" id="dataf" value="<?php echo $row_rsP['dataf']; ?>">
                            <span class="input-group-btn">
                            <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                            </span> 
                          </div>
                        </div>
                      </div> 
                      <hr>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="nome">Nome: <span class="required"> * </span> </label>
                        <div class="col-md-8">
                          <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>" data-required="1">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="codigo">Código: <span class="required"> * </span> </label>
                        <div class="col-md-3">
                          <input type="text" class="form-control" name="codigo" id="codigo" value="<?php echo $row_rsP['codigo']; ?>" data-required="1">
                        </div>
                        <div class="col-md-2">
                          <a class="btn btn-primary" href="javascript:" onClick="geraCodigo()"><i class="fa fa-refresh"> Gerar Novo</i></a>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="desconto">Desconto: <span class="required"> * </span> </label>
                        <div class="col-md-3">
                          <div class="input-group">
                            <input type="text" class="form-control" name="desconto" id="desconto" value="<?php echo $row_rsP['desconto']; ?>" data-required="1" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                            <span id="span_desconto" class="input-group-addon"><?php if($row_rsP['tipo_desconto'] == 1) { echo "%"; } else if($row_rsP['tipo_desconto']) { echo "&pound;"; } ?></span>
                          </div> 
                        </div>
                        <div class="col-md-2 md-radio-inline">
                          <div class="md-radio">
                            <input type="radio" id="desc_perc" name="radio_desconto" class="md-radiobtn" value="1" <?php if($row_rsP['tipo_desconto'] == 1) { echo "checked"; } ?>>
                            <label for="desc_perc">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                            % </label>
                          </div>
                          <div class="md-radio">
                            <input type="radio" id="desc_preco" name="radio_desconto" class="md-radiobtn" value="2" <?php if($row_rsP['tipo_desconto'] == 2) { echo "checked"; } ?>>
                            <label for="desc_preco">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                            £ </label>
                          </div>
                        </div>
                      </div> 
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="tipo_desconto">Desconto sobre: </label>
                        <div class="col-md-8 md-radio-inline">
                          <div class="md-radio">
                            <input type="radio" id="desc_total" name="tipo_desconto" class="md-radiobtn" value="1" <?php if($row_rsP['tipo'] == 1) { echo "checked"; } ?>>
                            <label for="desc_total">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                            Total da Encomenda </label>
                          </div>
                          <div class="md-radio">
                            <input type="radio" id="desc_prod" name="tipo_desconto" class="md-radiobtn" value="2" <?php if($row_rsP['tipo'] == 2) { echo "checked"; } ?>>
                            <label for="desc_prod">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                            Produtos sem Desconto </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="valor_minimo">Compra mínima: </label>
                        <div class="col-md-3">
                          <div class="input-group">
                            <input type="text" class="form-control" name="valor_minimo" id="valor_minimo" value="<?php echo $row_rsP['valor_minimo']; ?>" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                            <span class="input-group-addon">£</span>
                          </div> 
                        </div>
                      </div>
                      <hr>
                    <?php 
                      $query_rsTotal = "SELECT * FROM paises WHERE visivel=1";
                      $rsTotal = DB::getInstance()->query($query_rsTotal);
                      $rsTotal->execute();
                      $totalRows_rsTotal = $rsTotal->rowCount();
                    ?>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="paises">Paíse: </label>
                      <div class="col-md-8">
                        <select class="form-control select2me" name="paises" id="paises">
                          <option value="0"><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                            <?php if($totalRows_rsTotal > 0) {
                              while($row_rsPaises = $rsTotal->fetch()) { ?>
                                <option value="<?php echo $row_rsPaises['id']; ?>" <?php if($row_rsPaises['id'] == $row_rsP['id_country']) echo "selected"; ?>><?php echo $row_rsPaises['nome']; ?></option>
                              <?php }
                            } ?>
                        </select>
                      </div>
                    </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="grupo">Grupos de Clientes: </label>
                        <div class="col-md-8">
                          <select class="form-control select2me" name="grupo" id="grupo" onChange="carregaClientes(this.value, 0);">
                            <option value="0">Selecionar...</option>
                            <?php if($totalRows_rsGrupos > 0) {
                              while($row_rsGrupos = $rsGrupos->fetch()) { ?>
                                <option value="<?php echo $row_rsGrupos['id']; ?>" <?php if($row_rsGrupos['id'] == $row_rsP['grupo']) echo "selected"; ?>><?php echo $row_rsGrupos['nome']; ?></option>
                              <?php }
                            } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="cliente">Cliente: </label>
                        <div class="col-md-8">
                          <div id="div_clientes">
                            <select class="form-control select2me" name="cliente" id="cliente">
                              <option value="">Selecionar...</option>
                              <?php if($totalRows_rsClientes > 0) {
                                while($row_rsClientes = $rsClientes->fetch()) { ?>
                                  <option value="<?php echo $row_rsClientes['id']; ?>" <?php if($row_rsClientes['id'] == $row_rsP['id_cliente']) { echo "selected"; } ?>><?php echo $row_rsClientes['nome']." - ".$row_rsClientes['email']; if($row_rsClientes['numero']) echo " - ".$row_rsClientes['numero'] ?></option>
                                <?php }
                              } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="limite_cliente">Limite por cliente: </label>
                        <div class="col-md-2">
                          <input type="text" class="form-control" name="limite_cliente" id="limite_cliente" value="<?php echo $row_rsP['limite_cliente']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                        </div>
                        <label class="col-md-2 control-label" for="limite_cliente">Limite de clientes: </label>
                        <div class="col-md-2">
                          <input type="text" class="form-control" name="limite_de_clientes" id="limite_de_clientes" value="<?php echo $row_rsP['limite_total']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                        </div>
                        <div class="col-md-2">
                          <p class="help-block">(0 = sem limite)</p>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="categoria">Categoria: </label>
                        <div class="col-md-8">
                          <select class="form-control select2me" name="categoria" id="categoria" onChange="carregaProdutos($('#marca').val(), this.value, 0);">
                            <option value="">Selecionar...</option>
                            <?php if($totalRows_rsCategorias > 0) {
                              while($row_rsCategorias = $rsCategorias->fetch()) { ?>
                                <option value="<?php echo $row_rsCategorias['id']; ?>" <?php if($row_rsCategorias['id'] == $row_rsP['id_categoria']) echo "selected"; ?>><?php echo $row_rsCategorias['nome']; ?></option>
                                <?php
                                $query_rsCategorias2 = "SELECT id, nome FROM l_categorias_pt WHERE cat_mae = '".$row_rsCategorias['id']."' ORDER BY nome ASC";
                                $rsCategorias2 = DB::getInstance()->prepare($query_rsCategorias2);
                                $rsCategorias2->execute();
                                $totalRows_rsCategorias2 = $rsCategorias2->rowCount();
                                DB::close();

                                if($totalRows_rsCategorias2 > 0) {
                                  while($row_rsCategorias2 = $rsCategorias2->fetch()) { ?>
                                    <option value="<?php echo $row_rsCategorias2['id']; ?>" <?php if($row_rsCategorias2['id'] == $row_rsP['id_categoria']) echo "selected"; ?>><?php echo $row_rsCategorias['nome']." » ".$row_rsCategorias2['nome']; ?></option>
                                    <?php
                                    $query_rsCategorias3 = "SELECT id, nome FROM l_categorias_pt WHERE cat_mae = '".$row_rsCategorias2['id']."' ORDER BY nome ASC";
                                    $rsCategorias3 = DB::getInstance()->prepare($query_rsCategorias3);
                                    $rsCategorias3->execute();
                                    $totalRows_rsCategorias3 = $rsCategorias3->rowCount();
                                    DB::close();

                                    if($totalRows_rsCategorias3 > 0) {
                                      while($row_rsCategorias3 = $rsCategorias3->fetch()) { ?>
                                        <option value="<?php echo $row_rsCategorias3['id']; ?>" <?php if($row_rsCategorias3['id'] == $row_rsP['id_categoria']) echo "selected"; ?>><?php echo $row_rsCategorias['nome']." » ".$row_rsCategorias2['nome']." » ".$row_rsCategorias3['nome']; ?></option>
                                      <?php } 
                                    }
                                  }
                                }
                              }
                            } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="marca">Marca: </label>
                        <div class="col-md-8">
                          <select class="form-control select2me" name="marca" id="marca" onChange="carregaProdutos(this.value, $('#categoria').val(), 0);">
                            <option value="">Selecionar...</option>
                            <?php if($totalRows_rsMarcas > 0) {
                              while($row_rsMarcas = $rsMarcas->fetch()) { ?>
                                <option value="<?php echo $row_rsMarcas['id']; ?>" <?php if($row_rsMarcas['id'] == $row_rsP['id_marca']) echo "selected"; ?>><?php echo $row_rsMarcas['nome']; ?></option>
                              <?php }
                            } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="produto">Produto: </label>
                        <div class="col-md-8">
                          <div id="div_produtos">
                            <select class="form-control select2me" name="produto" id="produto">
                              <option value="">Selecionar...</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="pagina"><?php echo $RecursosCons->RecursosCons['pagina']; ?>: </label>
                        <div class="col-md-8">
                          <select class="form-control select2me" name="pagina" id="pagina">
                            <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                            <?php if($totalRows_rsPaginas > 0) {
                              while($row_rsPaginas = $rsPaginas->fetch()) { ?>
                                <option value="<?php echo $row_rsPaginas['id']; ?>" <?php if($row_rsP['pagina'] == $row_rsPaginas['id']) echo "selected"; ?> ><?php echo $row_rsPaginas['nome']; ?></option>
                              <?php }
                            } ?>
                          </select>
                          <p class="help-block"><?php echo $RecursosCons->RecursosCons['promocoes_aviso4']; ?></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                        <div class="col-md-8">
                          <textarea class="form-control" name="texto" id="texto"><?php echo $row_rsCodPromTexto['texto']; ?></textarea>
                        </div>
                      </div>                                
                    </div>
                  </div>
                  <div class="tab-pane <?php if($tab_sel == 2) { echo "active"; } ?>" id="tab_2">
                    <div class="form-body">
                      <?php if($totalRows_rsEncomendas > 0) { ?>
                        <div style="width: 100%" class="table-container">
                          <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr role="row" class="heading">
                              <th width="25%">
                                 Email
                              </th>
                              <th width="20%">
                                 Data
                              </th>
                              <th width="15%">
                                 Nº Encomenda
                              </th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php while($row_rsEncomendas = $rsEncomendas->fetch()) { ?>
                                <tr>
                                  <td>
                                    <?php echo $row_rsEncomendas['email']; ?>
                                  </td>
                                  <td>
                                    <?php echo $row_rsEncomendas['data']; ?>
                                  </td>
                                  <td>
                                    <a href="../encomendas/encomendas-edit.php?id=<?php echo $row_rsEncomendas['id']; ?>"><?php echo $row_rsEncomendas['numero']; ?></a>
                                  </td>
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      <?php } 
                      else { ?>
                        <div class="form-group">
                          <div class="col-md-2"></div>
                          <div class="col-md-8"><strong>Este código promocional não foi usado em nenhuma encomenda.</strong></div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="tab-pane <?php if($tab_sel == 3) { echo "active"; } ?>" id="tab_3"> 
                    <div class="form-body">
                      <div class="clearfix margin-top-20 margin-bottom-20"></div>
                      <div class="row">
                        <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong>Total de Encomendas:</strong></label>
                        <div class="col-md-5">
                          <div class="form-control" style="border:0;"><?php echo $totalRows_rsEncomendas; ?></div>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="row">
                        <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong>Valor Total das Encomendas:</strong></label>
                        <div class="col-md-5">
                          <div class="form-control" style="border:0;"><?php if($row_rsTotalEncomendas['total'] == null) { echo "0 &pound;"; } else { echo round($row_rsTotalEncomendas['total'], 2)." &pound;"; } ?></div>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="row">
                        <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong>Valor Total de Desconto:</strong></label>
                        <div class="col-md-5">
                          <div class="form-control" style="border:0;"><?php if($row_rsTotalDesconto['total'] == null) { echo "0 &pound;"; } else { echo round($row_rsTotalDesconto['total'], 2)." &pound;"; } ?></div>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="row">
                        <label class="col-md-4 control-label" style="text-align:right; margin-top:0px;"><strong>Percentagem Total de Desconto:</strong></label>
                        <div class="col-md-5">
                          <div class="form-control" style="border:0;"><?php echo round($perc_total, 2)." %"; ?></div>
                        </div>
                      </div>
                    </div>
                  </div> 
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="form_codigo" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  User.init();

  if('<?php echo $row_rsP['id_marca']; ?>' != '0' || '<?php echo $row_rsP['id_categoria']; ?>' != '0') {
    carregaProdutos('<?php echo $row_rsP['id_marca']; ?>', '<?php echo $row_rsP['id_categoria']; ?>', '<?php echo $row_rsP['id_peca']; ?>');
  }

  if('<?php echo $row_rsP['grupo']; ?>' != '0') {
    carregaClientes('<?php echo $row_rsP['grupo']; ?>', '<?php echo $row_rsP['id_cliente']; ?>');
  }

  $('#desc_perc').on('change', function() {
    if($('#desc_perc').is(':checked')) 
      $('#span_desconto').text('%');
    else
      $('#span_desconto').text('£');
  });

  $('#desc_preco').on('change', function() {
    if($('#desc_preco').is(':checked')) 
      $('#span_desconto').text('£');
    else
      $('#span_desconto').text('%');
  });
});

function carregaProdutos(marca, categoria, sel) {
  $.post("cod-promo-rpc.php", {op:"carregaProdutos", marca:marca, categoria:categoria, sel:sel}, function(data) {
    document.getElementById('div_produtos').innerHTML=data;  
    $('#produto').select2();                
  });
}

function carregaClientes(grupo, sel) {
  $.ajax({
    url: 'cod-promo-rpc.php',
    type: 'POST',
    data: {op: 'carregaClientes', grupo: grupo, sel:sel},
  })
  .done(function(data) {
    $('#div_clientes').html(data);
    $('#cliente').select2();
  });
}

function geraCodigo() {
  $.ajax({
    url: 'cod-promo-rpc.php',
    type: 'POST',
    data: {op: 'geraCodigo'},
  })
  .done(function(data) {
    $('#codigo').val(data.trim());
  });
}
</script>
<script type="text/javascript">
var wordCountConf1 = {
  showParagraphs: false,
  showWordCount: false,
  showCharCount: true,
  countSpacesAsChars: true,
  countHTML: false,
  maxWordCount: -1,
  maxCharCount: 150
}

CKEDITOR.replace('texto',
{
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "150px",
  extraPlugins: 'wordcount,notification',
  wordcount: wordCountConf1
});
</script>
</body>
<!-- END BODY -->
</html>