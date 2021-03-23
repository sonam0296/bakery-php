<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='carrinho';
$menu_sub_sel='listagem';

$id=$_GET['id'];

$query_rsCat = "SELECT * FROM l_categorias_en WHERE cat_mae='0' ORDER BY nome ASC";
$rsCat = DB::getInstance()->query($query_rsCat);
$rsCat->execute();
$totalRows_rsCat = $rsCat->rowCount();
DB::close();

$query_rsCarrinho = "SELECT cart.session, cli.nome as nome_cliente, cli.email as email_cliente FROM carrinho cart LEFT JOIN clientes cli ON cart.id_cliente = cli.id  WHERE cart.id=:id";
$rsCarrinho = DB::getInstance()->prepare($query_rsCarrinho);
$rsCarrinho->bindParam(':id', $id, PDO::PARAM_INT, 5);
$rsCarrinho->execute();
$row_rsCarrinho = $rsCarrinho->fetch(PDO::FETCH_ASSOC);
$totalRows_rsCarrinho = $rsCarrinho->rowCount();
DB::close();

$session = $row_rsCarrinho['session'];

$query_rsList = "SELECT cart.*, prod.nome as nome_produto, prod.ref as referencia, prod.categoria as categoria FROM carrinho cart LEFT JOIN l_pecas_en prod ON cart.produto = prod.id WHERE cart.session =:session";
$rsList = DB::getInstance()->prepare($query_rsList);
$rsList->bindParam(':session', $session, PDO::PARAM_INT, 5);
$rsList->execute();
$totalRows_rsList = $rsList->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['carrinho']; ?> <small><?php echo $RecursosCons->RecursosCons['detalhes_carrinho']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>           
          <li>
            <a href="carrinho.php"><?php echo $RecursosCons->RecursosCons['carrinho']; ?> <i class="fa fa-angle-right"></i></a>
          </li>
          <li>
            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['detalhes_carrinho']; ?></a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER-->      
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">   
          <form id="produtos_form" name="produtos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="last-order-id" id="last-order-id" value="">
            <input type="hidden" name="client-id" id="client-id" value="">
            <input type="hidden" name="product-id" id="product-id" value="<?php echo $id; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['carrinho']; ?> - <?php if($row_rsCarrinho['nome_cliente'] != NULL){ echo $row_rsCarrinho['nome_cliente']; } else { echo $RecursosCons->RecursosCons['sem_cliente_assoc']; } ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='carrinho.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <div class="form-body">
                    <?php if($row_rsCarrinho['nome_cliente'] != '') { ?>
                      <div class="form-group">
                        <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['tab_cliente']; ?>: </label>
                        <div class="col-md-8">
                          <input type="text" class="form-control" value="<?php echo $row_rsCarrinho['nome_cliente'].' - '.$row_rsCarrinho['email_cliente']; ?>" disabled>
                        </div>
                      </div> 
                      <hr>
                    <?php } ?>    
                    <?php if($totalRows_rsList > 0) { ?>      
                      <div class="form-group">
                        <div class="col-md-12">
                          <div class="portlet blue-madison box">
                            <div class="portlet-title">
                              <div class="caption">
                                <i class="fa fa-shopping-cart"></i><?php echo $RecursosCons->RecursosCons['listagem_produtos_carrinho']; ?>
                              </div>
                            </div>
                            <div class="portlet-body">
                              <div class="table-responsive">
                                <table class="table table-hover table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th width="10%">
                                         <?php echo $RecursosCons->RecursosCons['data_label']; ?>
                                      </th>
                                      <th width="15%">
                                         <?php echo $RecursosCons->RecursosCons['produto']; ?>
                                      </th>
                                      <th width="20%">
                                         <?php echo $RecursosCons->RecursosCons['opcoes_produto']; ?>
                                      </th>
                                      <th width="10%">
                                         <?php echo $RecursosCons->RecursosCons['quantidade_total_label']; ?>
                                      </th>
                                      <th width="10%">
                                         <?php echo $RecursosCons->RecursosCons['preco_uni_prod_label']; ?>
                                      </th>
                                      <th width="10%">
                                         <?php echo $RecursosCons->RecursosCons['preco_total_label']; ?>
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $total_qtd = 0;
                                    $total_preco = 0; 
                                    while($row_rsList = $rsList->fetch()) { ?>
                                      <tr>
                                        <td>
                                          <?php echo $row_rsList['data']; ?>
                                        </td>
                                        <td>
                                          <div style="font-size:12px;">
                                            <?php if($row_rsList['referencia'] != NULL && $row_rsList['referencia'] != '') echo $RecursosCons->RecursosCons['referencia']."<strong>".$row_rsList['referencia']; ?></strong>
                                          </div>
                                          <strong><?php echo $row_rsList['nome_produto']; ?></strong>
                                        </td>
                                        <td>
                                          <div style="font-size:12px;">
                                          <?php
                                            $opcoes_txt = '';
                                            if($row_rsList['opcoes']) {

                                              $opcoes = explode("<br>", str_replace(";", "", $row_rsList['opcoes']));
                                              foreach($opcoes as $opcao){
                                                $opcao_ex = explode(":", $opcao);
                                                $opcoes_txt .= "<span><strong>".$opcao_ex[0].":</strong> ".$opcao_ex[1]."</span><br>";
                                              }

                                            }else {
                                              $opcoes_txt = "---";
                                            }

                                            echo $opcoes_txt;
                                          ?>
                                          </div> 
                                        </td>
                                        <td>
                                          <?php echo $row_rsList["quantidade"]; ?> 
                                        </td>
                                        <td>
                                          <?php echo  number_format($row_rsList["preco"], 2, ',', '.'); ?> &pound; 
                                        </td>
                                        <td>
                                          <?php echo number_format(($row_rsList["preco"] * $row_rsList["quantidade"]), 2, ',', '.');?> &pound;
                                        </td>
                                      </tr>
                                      <?php 
                                      $total_qtd += $row_rsList["quantidade"];
                                      $total_preco += ($row_rsList["preco"] * $row_rsList["quantidade"]); 
                                    } ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                          <div class="well">
                            <div class="row static-info align-reverse">
                              <div class="col-md-8 name">
                                <?php echo $RecursosCons->RecursosCons['cli_total_produtos']; ?>:
                              </div>
                              <div class="col-md-4 value" style="text-align: left;">
                                <?php echo $total_qtd ;?>
                              </div>
                            </div>
                            <div class="row static-info align-reverse">
                              <div class="col-md-8 name">
                                <?php echo $RecursosCons->RecursosCons['preco_total_label']; ?>:
                              </div>
                              <div class="col-md-4 value" style="text-align: left;">
                                <?php echo number_format($total_preco,2,',',' ');?>&pound;
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<link rel="stylesheet" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/css/tinyscrollbar.css" type="text/css"/>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/jquery.tinyscrollbar.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
});
</script>
</body>
<!-- END BODY -->
</html>
