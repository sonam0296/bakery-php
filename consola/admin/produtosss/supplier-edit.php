<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$menu_sel='ec_produtos_produtos';
$menu_sub_sel='';
$tab_sel=1;

if($_GET['env']==1) $tab_sel=1;
if($_GET['tab_sel'] > 0) $tab_sel=$_GET['tab_sel'];

$id=$_GET['id'];

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_stock_form")) {
  $manter = $_POST['manter'];
  $name = $_POST['name'];
  $supplier_id = $_POST['supplier_id'];
  
  //verifica se pode inserir
  $erro_validar_insercao=0; 
  $erro_validar_duplicado=0;
  
  $query_rsCat = "SELECT * FROM l_caract_categorias_pt ORDER BY ordem ASC, nome ASC";
  $rsCat = DB::getInstance()->prepare($query_rsCat);
  $rsCat->execute();
  $totalRows_rsCat = $rsCat->rowCount();
  
  //quais foram inseridos antes
  $query_rsTamanhos = "SELECT * FROM l_pecas_tamanhos WHERE l_pecas_tamanhos.peca=:id ORDER BY l_pecas_tamanhos.id DESC";
  $rsTamanhos = DB::getInstance()->prepare($query_rsTamanhos);
  $rsTamanhos->bindParam(':id', $id, PDO::PARAM_INT);
  $rsTamanhos->execute();
  $row_rsTamanhos = $rsTamanhos->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsTamanhos = $rsTamanhos->rowCount();
    
  //se ja inseriu registos    
  // if($totalRows_rsTamanhos>0) {
  //   $car1=$row_rsTamanhos['car1'];
  //   $car2=$row_rsTamanhos['car2'];
  //   $car3=$row_rsTamanhos['car3'];
  //   $car4=$row_rsTamanhos['car4'];
  //   $car5=$row_rsTamanhos['car5'];  
    
  //   $op1=0;
  //   $op2=0;
  //   $op3=0;
  //   $op4=0;
  //   $op5=0;
    
  
  //   $num_caract_validar=1;
  
  //   if($totalRows_rsCat>0){
      
  //     while($row_rsCat = $rsCat->fetch()) {
        
  //       $variableCaractEx = $row_rsTamanhos['car'.$num_caract_validar];       
                
  //       $id_caract=$row_rsCat['id'];
  //       $opcao=$_POST['opcao_'.$id_caract];
        
  //       if(!$opcao) $opcao=0; // se estiver disabled
        
        
  //       $variableName = 'op' . $num_caract_validar; 
  //       //the double dollar sign is saying assign $imageName 
  //       // to the variable that has the name that is in $variableName
  //       $$variableName = $opcao; 
        
  //       //echo $opcao." ".$variableCaractEx;
        
  //       if($opcao==0 && $variableCaractEx>0){         
  //         $erro_validar_insercao=1; //nao existe            
  //       }
        
  //       $num_caract_validar++;
  //     }
  //   }
  // }

  $car1=$row_rsTamanhos['car1'];
  $car2=$row_rsTamanhos['car2'];
  $car3=$row_rsTamanhos['car3'];
  $car4=$row_rsTamanhos['car4'];
  $car5=$row_rsTamanhos['car5'];  
  
  $op1=0;
  $op2=0;
  $op3=0;
  $op4=0;
  $op5=0;

  if(isset($_POST['opcao_'.$car1]))
    $op1 = $_POST['opcao_'.$car1];

  if(isset($_POST['opcao_'.$car2])) {
    if($op1 == 0)
      $op1 = $_POST['opcao_'.$car2];
    else
      $op2 = $_POST['opcao_'.$car2];
  }

  if(isset($_POST['opcao_'.$car3])) {
    if($op1 == 0)
      $op1 = $_POST['opcao_'.$car3];
    else if($op2 == 0)
      $op2 = $_POST['opcao_'.$car3];
    else
      $op3 = $_POST['opcao_'.$car3];
  }

  if(isset($_POST['opcao_'.$car4])) {
    if($op1 == 0)
      $op1 = $_POST['opcao_'.$car4];
    else if($op2 == 0)
      $op2 = $_POST['opcao_'.$car4];
    else if($op3 == 0)
      $op3 = $_POST['opcao_'.$car4];
    else
      $op4 = $_POST['opcao_'.$car4];
  }

  if(isset($_POST['opcao_'.$car5])) {
    if($op1 == 0)
      $op1 = $_POST['opcao_'.$car5];
    else if($op2 == 0)
      $op2 = $_POST['opcao_'.$car5];
    else if($op3 == 0)
      $op3 = $_POST['opcao_'.$car5];
    else if($op4 == 0)
      $op4 = $_POST['opcao_'.$car5];
    else
      $op5 = $_POST['opcao_'.$car5];
  }

  if($totalRows_rsTamanhos>0) {
    if(($car1 > 0 && $op1 == 0) || ($car2 > 0 && $op2 == 0) || ($car3 > 0 && $op3 == 0) || ($car4 > 0 && $op4 == 0) || ($car5 > 0 && $op5 == 0))
      $erro_validar_insercao=1;
  }

      /*Start || Code By Vishal Prajapti 22-oct-2020*/

      for($i = 0; $i < sizeof($name); $i++) 
      {    
        $namemulti = $name[$i]; 
        $supplier_idm = $supplier_id[$i];   
        $insertSQL = "INSERT INTO l_pecas_supplier (product_id, supplier_id, name) VALUES (:max_id, :supplier_id, :name)";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':max_id', $id, PDO::PARAM_INT);
        $rsInsert->bindParam(':supplier_id', $supplier_idm, PDO::PARAM_INT);  
        $rsInsert->bindParam(':name', $namemulti, PDO::PARAM_STR, 5);   
        $rsInsert->execute(); 
    
         DB::close();

        if(!$manter) 
          header("Location: supplier.php?alt=1");
        else
          header("Location: supplier-edit.php?alt=1&id=".$id);
      }

      /*Start || Code By Vishal Prajapti 22-oct-2020*/

  if($erro_validar_insercao==0) {
    //verifica se já existe registo
    $query_rsProcReg = "SELECT * FROM l_pecas_tamanhos WHERE peca=:id AND car1=:car1 AND op1=:op1 AND car2=:car2 AND op2=:op2 AND car3=:car3 AND op3=:op3 AND car4=:car4 AND op4=:op4 AND car5=:car5 AND op5=:op5";
    $rsProcReg = DB::getInstance()->prepare($query_rsProcReg);
    $rsProcReg->bindParam(':id', $id, PDO::PARAM_INT);
    $rsProcReg->bindParam(':car1', $car1, PDO::PARAM_INT);
    $rsProcReg->bindParam(':op1', $op1, PDO::PARAM_INT);
    $rsProcReg->bindParam(':car2', $car2, PDO::PARAM_INT);
    $rsProcReg->bindParam(':op2', $op2, PDO::PARAM_INT);
    $rsProcReg->bindParam(':car3', $car3, PDO::PARAM_INT);
    $rsProcReg->bindParam(':op3', $op3, PDO::PARAM_INT);
    $rsProcReg->bindParam(':car4', $car4, PDO::PARAM_INT);
    $rsProcReg->bindParam(':op4', $op4, PDO::PARAM_INT);
    $rsProcReg->bindParam(':car5', $car5, PDO::PARAM_INT);
    $rsProcReg->bindParam(':op5', $op5, PDO::PARAM_INT);
    $rsProcReg->execute();
    $totalRows_rsProcReg = $rsProcReg->rowCount();
    
    if($totalRows_rsProcReg>0) {
      $erro_validar_duplicado=1;
    } 
    else {
      $rsInsert = "SELECT MAX(id) FROM l_pecas_tamanhos";
      $rsInsert = DB::getInstance()->prepare($rsInsert);
      $rsInsert->execute();
      $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
      
      $max_id = $row_rsInsert["MAX(id)"]+1; 
      
      $query_rsCat = "SELECT * FROM l_caract_categorias_pt ORDER BY ordem ASC, nome ASC";
      $rsCat = DB::getInstance()->prepare($query_rsCat);
      $rsCat->execute();
      $totalRows_rsCat = $rsCat->rowCount();

        }
      }
    }
  //}
//}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_stock_atualiza")) {
  $manter_act = $_POST['manter_act'];

  $query_rsInferior = "SELECT * FROM l_pecas_supplier WHERE product_id=:id";
  $rsInferior = DB::getInstance()->prepare($query_rsInferior);
  $rsInferior->bindParam(':id', $id, PDO::PARAM_INT);
  $rsInferior->execute();
  $row_rsInferior = $rsInferior->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsInferior = $rsInferior->rowCount();      

  $query_rsSuperior = "SELECT * FROM l_pecas_supplier WHERE product_id=:id";
  $rsSuperior = DB::getInstance()->prepare($query_rsSuperior);
  $rsSuperior->bindParam(':id', $id, PDO::PARAM_INT);
  $rsSuperior->execute();
  $row_rsSuperior = $rsSuperior->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsSuperior = $rsSuperior->rowCount();
  
 // $minimo=$row_rsInferior['MIN(l_pecas_supplier.id)'];
  //$maximo=$row_rsSuperior['MAX(l_pecas_supplier.id)'];
  
 // for($i=$minimo; $i<=$maximo; $i++) {name


  $query_rsSuperior = "SELECT MAX(l_pecas_supplier.id) FROM l_pecas_supplier WHERE product_id=:id";
  $rsSuperior = DB::getInstance()->prepare($query_rsSuperior);
  $rsSuperior->bindParam(':id', $id, PDO::PARAM_INT);
  $rsSuperior->execute();
  $row_rsSuperior = $rsSuperior->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsSuperior = $rsSuperior->rowCount();
  
  $minimo=$row_rsInferior['MIN(l_pecas_supplier.id)'];
  $maximo=$row_rsSuperior['MAX(l_pecas_supplier.id)'];

  for($i=$minimo; $i<=$maximo; $i++) {
    $price=$_POST['price'.$i];
    
    $primery=0;
    if($_POST['primery']==$i) $primery=1;
   

    if(isset($price)) {  
          //$supplier_idm = $supplier_id[$i];

          $insertSQL = "UPDATE l_pecas_supplier SET price=:price, primery=:primery WHERE id=:i";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->bindParam(':price', $price, PDO::PARAM_STR, 5);  
          //$rsInsert->bindParam(':supplier_id', $supplier_idm, PDO::PARAM_INT);  
          $rsInsert->bindParam(':primery', $primery, PDO::PARAM_INT);
          $rsInsert->bindParam(':i', $i, PDO::PARAM_INT); 
          $rsInsert->execute();

          DB::close();

          if(!$manter_act) 
            header("Location: supplier.php?alt=1");
          else
            header("Location: supplier-edit.php?alt=2&id=".$id);
        
    }
  }
}
if(isset($_GET['idd'])) {

  echo $_GET['idd'];
  die();

  if(isset($_GET['rem']) && $_GET['rem']==1) {
    $projecto=$_GET['reg'];
    $idd=$_GET['idd'];
    
    $insertSQL = "DELETE FROM l_pecas_supplier WHERE id=:id";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':id', $idd, PDO::PARAM_INT);  
    $rsInsert->execute();
    DB::close();  
    
    header("Location: supplier-edit.php?id=".$idd."&r=1");
  }
}

$query_rsP = "SELECT * FROM l_pecas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsTamanhos = "SELECT * FROM l_pecas_supplier WHERE l_pecas_supplier.product_id=:id ORDER BY l_pecas_supplier.product_id DESC";
$rsTamanhos = DB::getInstance()->prepare($query_rsTamanhos);
$rsTamanhos->bindParam(':id', $id, PDO::PARAM_INT);
$rsTamanhos->execute();
$row_rsTamanhos = $rsTamanhos->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTamanhos = $rsTamanhos->rowCount();

if($totalRows_rsTamanhos>0) {
  $car1=$row_rsTamanhos['car1'];
  $car2=$row_rsTamanhos['car2'];
  $car3=$row_rsTamanhos['car3'];
  $car4=$row_rsTamanhos['car4'];
  $car5=$row_rsTamanhos['car5'];
}

$query_rsList = "SELECT * FROM l_pecas_supplier";
$rsList = DB::getInstance()->prepare($query_rsList);
$rsList->bindParam(':id', $id, PDO::PARAM_INT);
$rsList->execute();
$totalRows_rsList = $rsList->rowCount();

/*if($totalRows_rsTamanhos > 0) {
  $query_rsCat = "SELECT * FROM l_caract_categorias_pt WHERE id IN ($car1, $car2, $car3, $car4, $car5) ORDER BY ordem ASC, nome ASC";
  $rsCat = DB::getInstance()->prepare($query_rsCat);
  $rsCat->bindParam(':id', $id, PDO::PARAM_INT);
  $rsCat->execute();
  $totalRows_rsCat = $rsCat->rowCount();
}
else {
  $query_rsCat = "SELECT * FROM l_caract_categorias_pt ORDER BY ordem ASC, nome ASC";
  $rsCat = DB::getInstance()->prepare($query_rsCat);
  $rsCat->bindParam(':id', $id, PDO::PARAM_INT);
  $rsCat->execute();
  $totalRows_rsCat = $rsCat->rowCount();
}*/

$query_rsList = "SELECT * FROM l_pecas_supplier WHERE l_pecas_supplier.product_id=:id ORDER BY l_pecas_supplier.id DESC";
$rsList = DB::getInstance()->prepare($query_rsList);
$rsList->bindParam(':id', $id, PDO::PARAM_INT);
$rsList->execute();
$totalRows_rsList = $rsList->rowCount();

function hasOptions() {
  $res = 0;

  $query_rsCategorias = "SELECT * FROM l_caract_categorias_pt ORDER BY ordem ASC, nome ASC";
  $rsCategorias = DB::getInstance()->prepare($query_rsCategorias);
  $rsCategorias->bindParam(':id', $id, PDO::PARAM_INT);
  $rsCategorias->execute();
  $totalRows_rsCategorias = $rsCategorias->rowCount();
  
  if($totalRows_rsCategorias > 0) {
    while($row_rsCategorias = $rsCategorias->fetch()) {
      $query_rsOpt = "SELECT * FROM l_caract_opcoes_pt WHERE categoria=:categoria ORDER BY ordem ASC, nome ASC";
      $rsOpt = DB::getInstance()->prepare($query_rsOpt);
      $rsOpt->bindParam(':categoria', $row_rsCategorias['id'], PDO::PARAM_INT);
      $rsOpt->execute();
      $totalRows_rsOpt = $rsOpt->rowCount();

      if($totalRows_rsOpt > 0)
        $res = 1;
    }
  }

  return $res;
}

DB::close();

$query_rssupplier = "SELECT * FROM supplier";
$rssupplier = DB::getInstance()->prepare($query_rssupplier);
$rssupplier->execute();
$row_rssupplier = $rssupplier->fetchAll();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<script type="text/javascript">
function carregaCaract(cat){
  $.post("produtos-rpc.php", {op:"carregaCaract", cat:cat, id:'<?php echo $id; ?>'}, function(data){
    document.getElementById('div_filtros').innerHTML=data;
    $('#filtro').select2();                                       
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['produtos']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>           
          <li>
            <a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?> <i class="fa fa-angle-right"></i></a>
          </li>
          <li>
            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?> </a>
          </li>
        </ul>
      </div>
      <!-- END PAGE HEADER-->      
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
              <button type="button" class="btn blue" onClick="document.location='produtos.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">  
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>   
          <form id="produtos_stock_form" name="produtos_stock_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['produtos']; ?> - <?php echo $row_rsP['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='produtos.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a>
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=1'"> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=5'"> <a href="#tab_promocao" data-toggle="tab" onClick="document.getElementById('tab_sel').value='5'"> <?php echo $RecursosCons->RecursosCons['tab_promocao']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit-imagens.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_imagens']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit-stocks.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_stocks']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit-filtros.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_filtros']; ?> </a> </li>
                   <!--  <li class="nav-tab" onClick="window.location='produtos-edit-quantidades.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_quantidades']; ?> </a> </li> -->

                    <li class="nav-tab active" onClick="window.location='supplier-edit.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> Supplier </a> </li>

                    <li class="nav-tab" onClick="window.location='produtos-edit-relacionados.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_relacionados']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=2'"> <a id="tab_2" href="#tab_estatisticas" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_estatisticas']; ?> </a> </li>
                    <li class="nav-tab" onClick="window.location='produtos-edit.php?id=<?php echo $id; ?>&tab_sel=3'"> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_general">
                      <div class="form-body">
                        <?php if($_GET['alt'] == 1) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span> <?php echo $RecursosCons->RecursosCons['env']; ?> </span>
                          </div>
                        <?php } ?>
                        <?php if($erro_validar_insercao==1) { ?>
                          <div class="alert alert-danger display-show">
                          <button class="close" data-close="alert"></button>
                          <span> <?php echo $RecursosCons->RecursosCons['erro_valida_insercao']; ?> </span> </div>   
                        <?php } ?>
                        <?php if($erro_validar_duplicado==1) { ?>
                          <div class="alert alert-danger display-show">
                          <button class="close" data-close="alert"></button>
                          <span><?php echo $RecursosCons->RecursosCons['erro_valida_duplicacao']; ?>  </span> </div>   
                        <?php } ?>
                        <?php if(isset($_GET['r']) && $_GET['r']==1) { ?>
                          <div class="alert alert-danger display-show">
                          <button class="close" data-close="alert"></button>
                          <span> <?php echo $RecursosCons->RecursosCons['r']; ?> </span> </div>   
                        <?php } ?>
                        <?php if($_GET['alt'] == 2) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>
                          </div>
                        <?php } ?> 
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button><?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>  

                        <?php $opts = hasOptions(); if($opts == 1) {
                          //if($totalRows_rsCat>0) { ?>
                            <div class="form-group">
                              <?php /*$count=0; while($row_rsCat = $rsCat->fetch()) { 
                                $count++; 
                                $id_caract=$row_rsCat['id'];*/
                                    
                                $query_rsCat2 = "SELECT * FROM supplier";
                                $rsCat2 = DB::getInstance()->prepare($query_rsCat2);
                                $rsCat2->execute();
                                $totalRows_rsCat2 = $rsCat2->rowCount();
                                DB::close();

                                $query_rsCat = "SELECT * FROM supplier";
                                $rsCat = DB::getInstance()->prepare($query_rsCat);
                                $rsCat->execute();
                                $totalRows_rsCat = $rsCat->rowCount();
                                $row_rsCat = $rsCat->fetchAll();
                                    
                                if($totalRows_rsCat>0) { ?> 

                                  <?php 
                                    foreach ($row_rsCat as $row_rsCat2) { ?>
                                  <input type="hidden" name="supplier_id[]" id="supplier_id" value="<?php echo $row_rsCat2['id']; ?>">
                                  <?php } ?>

                                  <div style="padding-bottom: 10px" class="col-md-3">
                                    <select multiple class="form-control select2me" id="name" name="name[]" <?php //if($row_rsCat['id']!=$car1 && $row_rsCat['id']!=$car2 && $row_rsCat['id']!=$car3 && $row_rsCat['id']!=$car4 && $row_rsCat['id']!=$car5 && $totalRows_rsTamanhos>0) echo "disabled";?>>
                                      <option value="0">Selecionar <?php echo $row_rsCat2['title']; ?></option>                 
                                      <?php foreach ($row_rsCat as $row_rsCat2) { ?>
                                        <option value="<?php echo $row_rsCat2['title']; ?>"><?php echo $row_rsCat2['title']; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                <?php } ?>
                              <?php //} ?>
                              <div class="col-md-1">
                                <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-upload"></i> <?php echo $RecursosCons->RecursosCons['inserir']; ?></button>
                              </div>
                            </div>
                          <?php //}
                        } else { ?>
                          <label class="col-md-4 control-label"><?php echo $RecursosCons->RecursosCons['sem_caracteristicas_registadas_msg']; ?></label>
                        <?php } ?>              
                      </div>
                    </div>                   
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="produtos_stock_form" />
          </form>
        </div>
      </div>
      <?php if($totalRows_rsList>0) { ?>
      <div class="row">
        <div class="col-md-12" style="padding-top:30px">
          <form id="produtos_stock_atualiza" name="produtos_stock_atualiza" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter_act" id="manter_act" value="0">
            <div style="width:100%;text-align:right;padding-bottom:10px"><button class="btn btn-sm green" id="bt_submete" type="submit" onClick="document.getElementById('manter_act').value='1';"><i class="fa fa-refresh"></i> <?php echo $RecursosCons->RecursosCons['guarda_alt']; ?> </button></div>
              <div class="portlet box green">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-list"></i><?php echo $RecursosCons->RecursosCons['stocks_inseridos']; ?> 
                  </div>
                  <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                    <a href="javascript:;" class="reload">
                    </a>
                  </div>
                </div>
                <div class="portlet-body">
                  <div class="table-scrollable">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>&nbsp;</th>
                          <th>Name </th>
                          <th>Price </th>
                          <th>Primary</th>
                          <th>&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $cont=0; while($row_rsList = $rsList->fetch()) { $cont++; ?>
                        <tr>
                          <td><?php echo $cont; ?></td>
                          <td><?php echo $row_rsList["name"] ?>
                          </td>

                          <td><input type="text" class="form-control col-md-1" name="price<?php echo $row_rsList['id']; ?>" id="price<?php echo $row_rsList['id']; ?>" value="<?php echo $row_rsList['price']; ?>"></td>  
                     
                          <td><div class="radio-list">
                            <label class="radio-inline">
                            <input type="radio" name="primery" id="primery<?php echo $row_rsList['id']; ?>" value="<?php echo $row_rsList['id']; ?>" <?php if($row_rsList['primery']==1) echo "checked=\"checked\""; ?>></label>
                          </div></td>
                          <td>
                            <?php 
                            $query_rsCat2 = "SELECT * FROM l_pecas_supplier";
                                $rsCat2 = DB::getInstance()->prepare($query_rsCat2);
                                $rsCat2->execute();
                                $totalRows_rsCat2 = $rsCat2->rowCount();
                                DB::close();
                             ?>
                              
                            <a <?php while($row_rsCat2 = $rsCat2->fetch()) { ?> href="supplier-edit.php?idd=<?php echo $row_rsCat2['id']; ?>&rem=1&id=<?php echo $row_rsCat2['id'];?>"  <?php } ?> data-toggle="modal" class="btn btn-sm red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['eliminar']; ?></a>
                         
                            <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                            <div class="modal fade" id="modal_delete_<?php echo $row_rsList['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
                                  </div>
                                  <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?> </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn blue" onClick="document.location='supplier-edit.php?id=<?php echo $id; ?>&rem=1&reg=<?php echo $row_rsList['id'];?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
                                    <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
                                  </div>
                                </div>
                                <!-- /.modal-content --> 
                              </div>
                              <!-- /.modal-dialog --> 
                            </div>
                            <!-- /.modal -->
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <input type="hidden" name="MM_insert" value="produtos_stock_atualiza" />
            </form>
          </div>
        </div>
      <?php } ?>
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
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="form-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   FormValidation.init();
});
</script> 
</body>
<!-- END BODY -->
</html>
