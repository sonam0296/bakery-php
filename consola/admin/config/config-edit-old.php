<?php include_once('../inc_pages.php'); ?>
<?php 

$menu_sel='configuracao';
$menu_sub_sel='links';

$inserido=0;
$erro_password=0;
$tab_sel=0;
$erro = 0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "alterar")) {
	$query_rsP = "SELECT * FROM config WHERE id = 1";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();
  DB::close();

	$manutencao=0;
	if(isset($_POST['manutencao'])) $manutencao=1;

	$ips=$_POST['ips'];	

  $ativo=0;
  if(isset($_POST['popup_ativo'])) $ativo=1;
		
	$insertSQL = "UPDATE config SET manutencao=:manutencao, ips=:ips, link_popup=:link_popup, ativo=:ativo, tipo_popup=:tipo_popup  WHERE id='1'";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':manutencao', $manutencao, PDO::PARAM_INT);	
	$rsInsert->bindParam(':ips', $ips, PDO::PARAM_STR, 5);
  $rsInsert->bindParam(':link_popup', $_POST['link_popup'], PDO::PARAM_STR, 5); 
  $rsInsert->bindParam(':tipo_popup', $_POST['tipo_popup'], PDO::PARAM_INT);
  $rsInsert->bindParam(':ativo', $ativo, PDO::PARAM_INT);
	$rsInsert->execute();
	DB::close();

  if(ECOMMERCE == 1) {
    $ecommerce=0;
    if(isset($_POST['ecommerce'])) $ecommerce=1;

    $insertSQL = "UPDATE config_ecommerce SET ecommerce=:ecommerce WHERE id='1'";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':ecommerce', $ecommerce, PDO::PARAM_INT); 
    $rsInsert->execute();
    DB::close();
  }

  if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
    @unlink('../../../imgs/popup/'.$row_rsP['imagem_popup']);
    
    $insertSQL = "UPDATE config SET imagem_popup=NULL WHERE id=1";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    DB::close();
  }

  if($_FILES['img1']['name']!='') { // actualiza imagem
    $ext = strtolower(pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION));

    if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png") {
      $erro = 1;
    }
    else {  
      require("../resize_image.php");
      
      $imagem1=$row_rsP['imagem_popup'];
      
      $imgs_dir = "../../../imgs/popup";
      //$contaimg = 1;

    
      foreach($_FILES as $file_name => $file_array) {
        $sp = explode("img", $file_name);
        $contaimg = $sp[1];
    
        $id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);
        
        switch ($contaimg) {
          case '1': case '2': case '3':    
            $file_dir =  $imgs_dir;
          break;
        }
        

        if($file_array['size'] > 0){
            $nome_img=verifica_nome($file_array['name']);
            $nome_file = $id_file."_".$nome_img;
            @unlink($file_dir.'/'.$_POST['file_db_'.$contaimg]);
        }else {
            //$nome_file = $_POST['file_db_'.$contaimg];

          if($_POST['file_db_'.$contaimg])
            $nome_file = $_POST['file_db_'.$contaimg];
          else{
            $nome_file ='';
            @unlink($file_dir.'/'.$_POST['file_db_del_'.$contaimg]);
            }

        }
            
        if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$file_dir/$nome_file") or die ("Couldn't copy"); }

        //store the name plus index as a string 
        $variableName = 'nome_file' . $contaimg; 
        //the double dollar sign is saying assign $imageName 
        // to the variable that has the name that is in $variableName
        $$variableName = $nome_file;  
        //$contaimg++;
                            
      } // fim foreach
      //Fim do Trat. Imagens

      if($nome_file1)
        $imagem1=$nome_file1;
      
      //IMAGEM 1
      if($_FILES['img1']['name']!='') {
        if($imagem1!="" && file_exists("../../../imgs/popup/".$imagem1)){
                  
          $maxW=1000;
          $maxH=700;
          
          $sizes=getimagesize("../../../imgs/popup/".$imagem1);
          
          $imageW=$sizes[0];
          $imageH=$sizes[1];
          
          if($imageW>$maxW || $imageH>$maxH){
                    
            $img1=new Resize("../../../imgs/popup/", $imagem1, $imagem1, $maxW, $maxH);
            $img1->resize_image();
            
          }
        
        }   
        
        if($row_rsP['imagem_popup']){
          @unlink('../../../imgs/popup/'.$row_rsP['imagem_popup']);
        }
        
        $insertSQL = "UPDATE config SET imagem_popup=:imagem1 WHERE id=1";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':imagem1', $imagem1, PDO::PARAM_STR, 5); 
        $rsInsert->execute();
        DB::close();
      }
    }
  }
	
	if($erro == 1) {
    header("Location: config-edit.php?erro=1");
  }
  else {
    header("Location: config-edit.php?alt=1");
  }
}


$query_rsP = "SELECT * FROM config WHERE id='1'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

if(ECOMMERCE == 1) {
  $query_rsEcommerce = "SELECT * FROM config_ecommerce WHERE id='1'";
  $rsEcommerce = DB::getInstance()->prepare($query_rsEcommerce);
  $rsEcommerce->execute();
  $row_rsEcommerce = $rsEcommerce->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsEcommerce = $rsEcommerce->rowCount();
  DB::close();
}

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
  .form-horizontal .radio {
    padding-top: 2px;
  }
</style>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['config_site']; ?> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php if($totalRows_rsP>0){ ?>
          <form action="<?php echo $editFormAction; ?>" method="POST" id="dados_pessoais" role="form" enctype="multipart/form-data" class="form-horizontal">
          <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-desktop"></i><?php echo $RecursosCons->RecursosCons['site']; ?> </div>
                <div class="actions btn-set">
                  <button class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <?php if($_GET['alt']==1) { ?>
                    <div class="alert alert-success display-show">
                      <button class="close" data-close="alert"></button>
                      <?php echo $RecursosCons->RecursosCons['config_alt']; ?> </div>
                  <?php } ?>
                  <?php if($_GET['erro']==1) { ?>
                    <div class="alert alert-danger display-show">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> </div>   
                  <?php } ?> 
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="manutencao" style="padding-top:0;"><?php echo $RecursosCons->RecursosCons['manutencao']; ?>:</label>
                    <div class="col-md-10">
                      <input type="checkbox" class="form-control" name="manutencao" id="manutencao" value="1" <?php if($row_rsP['manutencao'] == 1) echo "checked";?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="ips"><?php echo $RecursosCons->RecursosCons['ips_label']; ?>:</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="ips" id="ips" value="<?php echo $row_rsP['ips']; ?>">
                    </div>
                    <div class="col-md-2">
                      <button type="button" onClick="addRemoteAddr()" class="btn default"><i class="fa fa-plus"></i> <?php echo $RecursosCons->RecursosCons['btn_add_ip_text']; ?></button>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <div class="col-md-6">
                      <label class="col-md-4 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['img_popup_label']; ?></label>
                      <div class="col-md-8">
                        <div class="fileinput fileinput-<?php if($row_rsP['imagem_popup']!="" && file_exists("../../../imgs/popup/".$row_rsP['imagem_popup'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                          <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                          <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                            <?php if($row_rsP['imagem_popup']!="" && file_exists("../../../imgs/popup/".$row_rsP['imagem_popup'])) { ?>
                              <a href="../../../imgs/popup/<?php echo $row_rsP['imagem_popup']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/popup/<?php echo $row_rsP['imagem_popup']; ?>"></a>
                            <?php } ?>
                          </div>
                          <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                            <input id="upload_campo" type="file" name="img1">
                            </span> <a href="javascript:;" class="btn default fileinput-exists" id="rem1" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                        </div>
                        <div class="clearfix margin-top-10"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span> </div>
                        <script type="text/javascript">
                        function alterar_imagem(){
                            document.getElementById('file_delete_1').value='';
                        }
                        function remover_imagem(){
                            document.getElementById('file_delete_1').value='';
                            document.getElementById('img_cont_1_vazia').style.display='block';                  
                            document.getElementById('img_cont_1').style.display='none';
                        }
                        </script> 
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="row">
                        <label class="col-md-2 control-label" for="popup_ativo" style="padding-top:0;"><?php echo $RecursosCons->RecursosCons['popup_ativo_label']; ?>: </label>
                        <div class="col-md-8">
                          <input type="checkbox" class="form-control" name="popup_ativo" id="popup_ativo" value="1" <?php if($row_rsP['ativo'] == 1) echo "checked";?>>
                        </div>
                      </div>
                      <div class="row" style="padding-top: 20px">
                        <label class="col-md-2 control-label" for="tipo_popup"><?php echo $RecursosCons->RecursosCons['tipo_popup_label']; ?>: </label>
                        <div class="col-md-8" style="padding-top: 4px">
                          <label>
                          <input type="radio" name="tipo_popup" id="tipo_popup1" value="1" <?php if($row_rsP['tipo_popup'] == 1) echo "checked"; ?>> <?php echo $RecursosCons->RecursosCons['tipo_popup_1_label']; ?> </label>
                          <label>
                          <input type="radio" name="tipo_popup" id="tipo_popup2" value="2" <?php if($row_rsP['tipo_popup'] == 2) echo "checked"; ?>> <?php echo $RecursosCons->RecursosCons['tipo_popup_2_label']; ?> </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="link_popup"><?php echo $RecursosCons->RecursosCons['link_popup_label']; ?>: </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="link_popup" id="link_popup" value="<?php echo $row_rsP['link_popup']; ?>"/>
                    </div>
                  </div>
                  <?php if(ECOMMERCE == 1) { ?>
                    <hr>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="ecommerce" style="padding-top:0;"><?php echo $RecursosCons->RecursosCons['ecommerce_ativo']; ?>: </label>
                      <div class="col-md-10">
                        <input type="checkbox" class="form-control" name="ecommerce" id="ecommerce" value="1" <?php if($row_rsEcommerce['ecommerce'] == 1) echo "checked";?>>
                        <p class="help-block"><strong><?php echo $RecursosCons->RecursosCons['help_block_ecommerce']; ?></strong></p>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="alterar" />
          </form>
          <?php } ?>
        </div>
      </div>
      <!-- END PAGE CONTENT--> 
    </div>
  </div>
</div>
<!-- END CONTENT -->
<?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/components-pickers.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- END PAGE LEVEL SCRIPTS --> 
<?php
$ip=$_SERVER['REMOTE_ADDR'];

if($ip==""){
	$ip=$HTTP_SERVER_VARS['REMOTE_ADDR'];
}
?>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  ComponentsPickers.init();
});
function addRemoteAddr(){
var length = $('input[name=ips]').attr('value').length;
if (length > 0)
$('input[name=ips]').attr('value',$('input[name=ips]').attr('value') +',<?php echo $ip; ?>');
else
$('input[name=ips]').attr('value','<?php echo $ip; ?>');
}
</script>
</body>
<!-- END BODY -->
</html>