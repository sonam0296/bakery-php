<?php include_once('../inc_pages.php'); ?>

<?php 

$menu_sel='ec_produtos_produtos';
$menu_sub_sel='';


$query_rsRole ="SELECT * FROM roll WHERE visivel = 1";
$rsRole = DB::getInstance()->prepare($query_rsRole);
$rsRole->execute();
$totalRows_rsRoll = $rsRole->fetchAll();


if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_form")) {
  $copiar = 0;
  if(isset($_POST['copiar_prod'])) {
    $copiar = 1;
  }

  if($copiar == 1) {
    if($_POST['produto_old'] != '') {
      $imagens = 0;
      if(isset($_POST['dados_imagens'])) {
        $imagens = 1;
      }
      $relacionados = 0;
      if(isset($_POST['dados_relacionados'])) {
        $relacionados = 1;
      }
      $quantidades = 0;
      if(isset($_POST['dados_quantidades'])) {
        $quantidades = 1;
      }
      $promocao = 0;
      if(isset($_POST['dados_promocao'])) {
        $promocao = 1;
      }
      $filtros = 0;
      if(isset($_POST['dados_filtros'])) {
        $filtros = 1;
      }
      $stocks = 0;
      if(isset($_POST['dados_stocks'])) {
        $stocks = 1;
      }
      
      $id_prod = $_POST['produto_old'];
      $nome_prod = $_POST['nome_old'];

      $new_prod = copiarProduto($id_prod, $nome_prod, $imagens, $relacionados, $quantidades, $promocao, $filtros, $stocks);

      header("Location: produtos-edit.php?env=1&id=".$new_prod);
    }
    else {
      header("Location: produtos-insert.php?error=3&c=1");
    }
  }
  else {
    if($_POST['nome'] != '' &&($_POST['categoria'] != '' || CATEGORIAS == 2)) {
      $insertSQL = "SELECT MAX(id) FROM l_pecas_en";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->execute();
      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
      
      $max_id = $row_rsInsert["MAX(id)"]+1;
      
      $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
      $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
      $rsLinguas->execute();
      $totalRows_rsLinguas = $rsLinguas->rowCount();

      $nao_limitar_stock = 0; 
      if($_POST['nao_limitar_stock'] == 1) $nao_limitar_stock = 1;
      
      while($row_rsLinguas = $rsLinguas->fetch()) {
        $nome_url = "";

        if(CATEGORIAS == 1) {
          $query_rsCatMae = "SELECT url FROM l_categorias_".$row_rsLinguas['sufixo']." WHERE id=:categoria";
          $rsCatMae = DB::getInstance()->prepare($query_rsCatMae);
          $rsCatMae->bindParam(':categoria', $_POST['categoria'], PDO::PARAM_INT);
          $rsCatMae->execute();
          $row_rsCatMae = $rsCatMae->fetch(PDO::FETCH_ASSOC);
          $totalRows_rsCatMae = $rsCatMae->rowCount();
          
          if($totalRows_rsCatMae > 0) {
            $nome_url .= $row_rsCatMae['url']."-";
          }
        }

        $nome_url .= strtolower(verifica_nome($_POST['nome']));
        
        $query_rsProc = "SELECT id FROM l_pecas_".$row_rsLinguas['sufixo']." WHERE url LIKE :nome_url AND id!=:max_id";
        $rsProc = DB::getInstance()->prepare($query_rsProc);
        $rsProc->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);
        $rsProc->bindParam(':max_id', $max_id, PDO::PARAM_INT);
        $rsProc->execute();
        $totalRows_rsProc = $rsProc->rowCount();
        
        if($totalRows_rsProc > 0) {
          $nome_url = $nome_url."-".$max_id;
        }
    
        $categoria = 0;
        if(CATEGORIAS == 1) {
          $categoria = $_POST['categoria'];
        }

        /*Start Start || Add By Vishal Prajapati 21-oct-2020*/

        $roleid       = json_encode($_POST['roleid']);
        $regularprice = json_encode($_POST['regularprice']);
        $sellingprice = json_encode($_POST['sellingprice']);
        $productqtn   = json_encode($_POST['productqtn']);

        
        $roleidd        = json_decode($roleid);
        $regularpricee  = json_decode($regularprice);
        $sellingpricee  = json_decode($sellingprice);
        $productqtnn    = json_decode($productqtn);

  
         /* End Code */


        $insertSQL = "INSERT INTO l_pecas_en (

                      id, 
                      ref, 
                      nome, 
                      categoria, 
                      on_order, 
                      descricao, 
                      preco, 
                      preco_forn, 
                      preco_ant, 
                      iva, 
                      peso, 
                      stock, 
                      nao_limitar_stock, 
                      descricao_stock,
                      maxstock, 
                      role_".$totalRows_rsRoll[0]["roll_name"].",
                      reguler_price_".$totalRows_rsRoll[0]["roll_name"].", 
                      selling_price_".$totalRows_rsRoll[0]["roll_name"].",
                      product_qulity_".$totalRows_rsRoll[0]["roll_name"].", 

                      role_".$totalRows_rsRoll[1]["roll_name"].", 
                      reguler_price_".$totalRows_rsRoll[1]["roll_name"].",
                      selling_price_".$totalRows_rsRoll[1]["roll_name"].", 
                      product_qulity_".$totalRows_rsRoll[1]["roll_name"].", 
                      
                      url, 
                      title
                    ) 

                    VALUES (

                    :max_id, 
                    :ref, :nome, 
                    :categoria, 
                    :order, 
                    :descricao, 
                    :preco, 
                    :preco_forn, 
                    :preco_ant, 
                    :iva, 
                    :peso, 
                    :stock, 
                    :nao_limitar_stock, 
                    :descricao_stock,
                    :maxstock, 

                    ".$roleidd[0].",
                    ".$regularpricee[0].",
                    ".$sellingpricee[0].",
                    ".$productqtnn[0].",

                    ".$roleidd[1].",
                    ".$regularpricee[1].",
                    ".$sellingpricee[1].",
                    ".$productqtnn[1].",

                    :url, 
                    :title
                  )";

        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':ref', $_POST['ref'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        //$rsInsert->bindParam(':marca', $_POST['marca'], PDO::PARAM_INT);
        $rsInsert->bindParam(':order', $_POST['order'], PDO::PARAM_INT);
        $rsInsert->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':preco', $_POST['preco'], PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':preco_forn', $_POST['preco_forn'], PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':preco_ant', $_POST['preco_ant'], PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':iva', $_POST['iva'], PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':peso', $_POST['peso'], PDO::PARAM_STR, 5);   
        $rsInsert->bindParam(':stock', $_POST['stock'], PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':nao_limitar_stock', $nao_limitar_stock, PDO::PARAM_INT);  
        $rsInsert->bindParam(':descricao_stock', $_POST['descricao_stock'], PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':maxstock', $_POST['maxstock'], PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':title', $_POST['nome'], PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':max_id', $max_id, PDO::PARAM_INT);   
        $rsInsert->execute();

        

      if(CATEGORIAS == 2) {
        $query_rsDelete = "DELETE FROM l_pecas_categorias WHERE id_peca = :id";
        $rsDelete = DB::getInstance()->prepare($query_rsDelete);
        $rsDelete->bindParam(':id', $max_id, PDO::PARAM_INT);  
        $rsDelete->execute();
        
        for($i = 0; $i < sizeof($_POST['categorias']); $i++) {    
          $categoria = $_POST['categorias'][$i];    
          
          $insertSQL = "SELECT MAX(id) FROM l_pecas_categorias";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->execute();
          $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
          
          $max_id_2 = $row_rsInsert["MAX(id)"] + 1;     
          
          $insertSQL = "INSERT INTO l_pecas_categorias (id, id_peca, id_categoria) VALUES (:max_id_2, :id, :categoria)";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);  
          $rsInsert->bindParam(':categoria', $categoria, PDO::PARAM_INT);
          $rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);  
          $rsInsert->execute();
        }
      }
      
      DB::close();
      header("Location: produtos-edit.php?env=1&id=".$max_id);
    }}  
    else if($_POST['nome'] == '' || ($_POST['categoria'] == "" && CATEGORIAS == 1)) {
      header("Location: produtos-insert.php?error=1");
    }
  }
}


                             
$query_rsCat = "SELECT * FROM l_categorias_en WHERE cat_mae='0' ORDER BY nome ASC";
$rsCat = DB::getInstance()->prepare($query_rsCat);
$rsCat->execute();
$totalRows_rsCat = $rsCat->rowCount();
$row_rsCat = $rsCat->fetchAll();

$query_rsMarcas = "SELECT * FROM l_marcas_en ORDER BY nome ASC";
$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);
$rsMarcas->execute();
$totalRows_rsMarcas = $rsMarcas->rowCount();

DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<style type="text/css">
  div#uniform-iva span input {
    margin-left: -9px;
}
</style>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['produtos']; ?> <small> <?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>           
          <li>
            <a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?> <i class="fa fa-angle-right"></i></a>
          </li>
          <li>
            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="produtos_form" name="produtos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['prod_novos_registos']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='produtos.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">             
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 
                  </div>
                  <?php if(isset($_GET['error']) && $_GET['error'] == 1) { ?>
                    <div class="alert alert-danger display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['copiar_prod_erro1']; ?>
                    </div>
                  <?php } ?>
                  <?php if(isset($_GET['error']) && $_GET['error'] == 3) { ?>
                    <div class="alert alert-danger display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['copiar_prod_erro3']; ?>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="copiar_prod"> <?php echo $RecursosCons->RecursosCons['copiar_prod']; ?>? </label>
                    <div class="col-md-10" style="padding-top: 7px;">
                      <input type="checkbox" class="form-control" name="copiar_prod" id="copiar_prod" value="1" <?php if(isset($_GET['c']) && $_GET['c'] == 1) echo "checked"; ?>/>
                      <p class="help-block"><?php echo $RecursosCons->RecursosCons['copiar_prod_txt']; ?></p>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group new_prod">
                    <label class="col-md-2 control-label" for="ref"> <?php echo $RecursosCons->RecursosCons['ref_label']; ?>:</label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" name="ref" id="ref" value="<?php echo $_POST['ref']; ?>">
                    </div>
                  </div>
                  <div class="form-group new_prod">
                    <label class="col-md-2 control-label" for="nome"> <?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>">
                    </div>
                  </div>
                   <?php if(CATEGORIAS == 1) { ?>
                  <div class="form-group new_prod">
                    <label class="col-md-2 control-label" for="categoria"> <?php echo $RecursosCons->RecursosCons['categoria_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <select class="form-control select2me" id="categoria" name="categoria">
                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                        <?php umaCategoriaPorProd(0, "", -1); ?>
                      </select>
                    </div>
                  </div>
                  <?php } 
                  else if(CATEGORIAS == 2) { ?>
                    <div class="form-group new_prod">
                      <label class="col-md-2 control-label" for="categoria"> <?php echo $RecursosCons->RecursosCons['categoria_label']; ?>: </label>
                      <div class="col-md-8">
                        <div class="form-control height-auto">
                          <div class="scroller" style="height: 300px;" data-always-visible="1">
                            <ul class="list-unstyled">
                              <?php variasCategoriasPorProd(0, -1, 0); ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                   <label class="col-md-2 control-label"> On Order: </label>
                   <input type="radio" name="order" id="order" value="1" checked>Yes
                     <input type="radio" name="order" id="order" value="0" >No
                  </div>
                 <!--  <div class="form-group new_prod">
                    <label class="col-md-2 control-label" for="marca"> <?php echo $RecursosCons->RecursosCons['marca_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <select class="form-control select2me" id="marca" name="marca" >
                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                        <?php if($totalRows_rsMarcas > 0) { ?>
                          <?php while($row_rsMarcas = $rsMarcas->fetch()) { ?>
                            <option value="<?php echo $row_rsMarcas['id']; ?>"><?php echo $row_rsMarcas['nome']; ?></option>        
                          <?php } 
                        } ?>
                      </select>
                    </div>
                  </div> -->
                  <div class="form-group new_prod">
                    <label class="col-md-2 control-label" for="preco"> <?php echo $RecursosCons->RecursosCons['preco_label']; ?>:</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input type="text" class="form-control" name="preco" id="preco" value="<?php echo $_POST['preco']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                        <span class="input-group-addon">&pound;</span>
                      </div>
                    </div>
                    <label class="col-md-1 control-label" for="preco_ant"> <?php echo $RecursosCons->RecursosCons['preco_ant_label']; ?>:</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input type="text" class="form-control" name="preco_ant" id="preco_ant" value="<?php echo $_POST['preco_ant']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                        <span class="input-group-addon">&pound;</span>
                      </div>                      
                    </div>
                  </div>
                  <div class="form-group new_prod">
                    <label class="col-md-2 control-label" for="peso"> <?php echo $RecursosCons->RecursosCons['peso_label']; ?>:</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input type="text" class="form-control" name="peso" id="peso" value="<?php echo $_POST['peso']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">
                        <span class="input-group-addon">kg</span>
                      </div>                       
                    </div>
                   <label class="col-md-1 control-label"> VAT: </label>
                    <div class="col-md-2">
                      <div class="input-group"> 
                        <input type="radio" name="iva" id="iva" value="20" checked>Yes
                        <input type="radio" name="iva" id="iva" value="0">No
                      </div>
                    </div> 

                    <label class="col-md-1 control-label" for="mStock"> Max. Stock:</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input type="text" class="form-control" name="maxstock" id="maxstock" value="<?php if($_POST['maxstock']>0) echo $_POST['maxstock']; else echo ""; ?>" maxlength="2" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                       
                      </div>                        
                    </div>

                  </div>
                  <div class="form-group new_prod">
                    <label class="col-md-2 control-label" for="stock"> <?php echo $RecursosCons->RecursosCons['stock_label']; ?>:</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input type="text" class="form-control" name="stock" id="stock" value="<?php echo $_POST['stock']; ?>" maxlength="8" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                        <span class="input-group-addon">uni</span>
                      </div>                      
                    </div>
                    <div class="col-md-2" style="padding-top:7px;text-align:center">
                      <label><input type="checkbox" class="form-control" name="nao_limitar_stock" id="nao_limitar_stock" value="1" />&nbsp; <?php echo $RecursosCons->RecursosCons['info_limite_stock']; ?></label>
                    </div>
                    <label class="col-md-2 control-label" for="descricao_stock">Min. Stock:</label>
                    <div class="col-md-2">
                      <input type="text" class="form-control" name="descricao_stock" id="descricao_stock" value="<?php echo $_POST['descricao_stock']; ?>">
                    </div>
                  </div>
                  <!-- Add Role || Code Vishal Prajapti -->
                    <div id="tabs" style="width: 1000px; margin-left: 100px;">
                       
                      <ul>
                        <?php  foreach ($totalRows_rsRoll as $role) { ?>
                        <li>
                          <a href="#tabs-<?php echo $role['roll_name']; ?>" value="<?php echo $role['id']; ?>"><?php echo $role['roll_name']; ?>
                            <input type="hidden" class="form-control" name="roleid[]" id="roleid" value="<?php echo $role['id']; ?>">
                          </a>
                        </li>
                          <?php  } ?>
                      </ul>
                       <?php 
                       foreach ($totalRows_rsRoll as $role) { ?>
                      <div id="tabs-<?php echo $role['roll_name']; ?>">
                       
                        <div class="form-group new_prod">
                          <label class="col-md-3 control-label" for="regularprice">Regular Price (optional) :</label>
                            <div class="col-md-2">
                               <input type="text" class="form-control" name="regularprice[]" id="regularprice" value="0">
                            </div>

                           <label class="col-md-3 control-label" for="sellingprice">Selling Price (optional):</label>
                          <div class="col-md-2">
                             <input type="text" class="form-control" name="sellingprice[]" id="sellingprice" value="0">
                          </div>
                        </div>

                        <div class="form-group new_prod">
                          <label class="col-md-3 control-label" for="productqtn">Product Quantity (optional) :</label>
                            <div class="col-md-2">
                               <input type="text" class="form-control" name="productqtn[]" id="productqtn" value="0">
                            </div>
                        </div>

                      </div>
                    <?php  } ?> 
                    </div>
                

                  <!-- End Role -->
                  <br>
                  <div class="form-group new_prod">
                    <label class="col-md-2 control-label" for="descricao"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>: </label>
                    <div class="col-md-8">
                      <textarea class="form-control" id="descricao" name="descricao"><?php echo $_POST['descricao']; ?></textarea>
                    </div>
                  </div>

                  

                  <!-- Copiar dados de outro produto -->
                  <div class="form-group old_prod">
                    <label class="col-md-2 control-label" for="categoria_old"><?php echo $RecursosCons->RecursosCons['categoria_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <select class="form-control select2me" id="categoria_old" name="categoria_old" >
                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                        <?php umaCategoriaPorProd(0, "", -1); ?>
                      </select>
                    </div>
                  </div> 
                  <div class="form-group old_prod">
                    <label class="col-md-2 control-label" for="produto_old"><?php echo $RecursosCons->RecursosCons['produto']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <div id="div_produtos">
                        <select class="form-control select2me" name="produto_old" id="produto_old">
                          <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group old_prod">
                    <label class="col-md-2 control-label" for="nome_old"> <?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome_old" id="nome_old" value="<?php echo $_POST['nome_old']; ?>">
                    </div>
                  </div>
                  <div class="form-group old_prod">
                    <label label class="col-md-2 control-label" for="escolher_dados"><?php echo $RecursosCons->RecursosCons['escolher_copiar']; ?>: </label>
                    <div class="col-md-4">
                      <div class="form-control height-auto">
                        <div class="scroller" style="height: 240px; overflow: auto !important;" data-always-visible="1">
                          <ul class="list-unstyled">
                            <li>                                    
                              <label><input type="checkbox" name="dados_detalhes" id="dados_detalhes" value="1" checked disabled><?php echo $RecursosCons->RecursosCons['copiar_detalhes']; ?></label>
                            </li>
                            <li>                                    
                              <label><input type="checkbox" name="dados_imagens" id="dados_imagens" value="1" checked><?php echo $RecursosCons->RecursosCons['copiar_galeria']; ?></label>
                            </li>
                            <li>                                    
                              <label><input type="checkbox" name="dados_relacionados" id="dados_relacionados" value="1" checked><?php echo $RecursosCons->RecursosCons['tab_relacionados']; ?></label>
                            </li>
                            <li>                                    
                              <label><input type="checkbox" name="dados_promocao" id="dados_promocao" value="1" checked><?php echo $RecursosCons->RecursosCons['copiar_promocao']; ?></label>
                            </li>
                            <li>                                    
                              <label><input type="checkbox" name="dados_quantidades" id="dados_quantidades" value="1" checked><?php echo $RecursosCons->RecursosCons['copiar_quantidades']; ?></label>
                            </li>
                            <li>                                    
                              <label><input type="checkbox" name="dados_filtros" id="dados_filtros" value="1" checked><?php echo $RecursosCons->RecursosCons['tab_filtros']; ?></label>
                            </li>
                            <li>                                    
                              <label><input type="checkbox" name="dados_stocks" id="dados_stocks" value="1" checked><?php echo $RecursosCons->RecursosCons['tab_stocks']; ?></label>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>                 
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="produtos_form" />
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
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<!-- <script src="form-validation.js"></script> -->
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  //FormValidation.init();

  var parts = window.location.search.substr(1).split("&");
  var $_GET = {};
    
  for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
  }

  if($_GET['c'] == 1) {
    $('.new_prod').css('display', 'none');
    $('.old_prod').css('display', 'block');
  }
  else {
    $('.old_prod').css('display', 'none');
    $('.new_prod').css('display', 'block');
  }

  $('#copiar_prod').on('change', function() {
    if($('#copiar_prod').is(':checked')) {
      $('.new_prod').css('display', 'none');
      $('.old_prod').css('display', 'block');
    }
    else {
      $('.old_prod').css('display', 'none');
      $('.new_prod').css('display', 'block');
    }
  });

  $('#categoria_old').on('change', function() {
    $.post("produtos-rpc.php", {op:"carregaProdutosOld", cat: $('#categoria_old').val()}, function(data) {
      document.getElementById('div_produtos').innerHTML=data;  
      $('#produto_old').select2();                
    });
  });
});
</script> 
<script type="text/javascript">
CKEDITOR.replace('descricao',
{
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "250px"
});
</script>

</body>
<!-- END BODY -->
</html>
