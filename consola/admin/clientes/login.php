<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='outros_clientes_login';
$menu_sub_sel='';

$tab_sel = 1;
if(isset($_GET['tab_sel']) && $_GET['tab_sel'] != "" && $_GET['tab_sel'] != 0) $tab_sel=$_GET['tab_sel'];

$id = 1;
$erro = 0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_texto")) {
  $tab_sel = $_REQUEST['tab_sel'];

  $query_rsP = "SELECT * FROM clientes_login".$extensao." WHERE id = :id";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->bindParam(':id', $id, PDO::PARAM_INT);  
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();

  $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	$rsLinguas->execute();
	$row_rsLinguas = $rsLinguas->fetchAll();
	$totalRows_rsLinguas = $rsLinguas->rowCount();

  if($tab_sel == 1) {
  	$insertSQL = "UPDATE clientes_login".$extensao." SET texto_password=:texto_password, titulo=:titulo, texto=:texto, titulo2=:titulo2, texto2=:texto2 WHERE id=:id";
  	$rsInsert = DB::getInstance()->prepare($insertSQL);
  	$rsInsert->bindParam(':texto_password', $_POST['texto_password'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR, 5);	
  	$rsInsert->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':titulo2', $_POST['titulo2'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':texto2', $_POST['texto2'], PDO::PARAM_STR, 5);
  	$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
  	$rsInsert->execute();

  	DB::close();

    header("Location: login.php?alt=1&tab_sel=1");
  }

  if($tab_sel == 2) {
    $opcao = $_POST['opcao'];
    $imagem = $row_rsP['imagem1'];

    if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
      if($opcao == 1) {
        $insertSQL = "UPDATE clientes_login".$extensao." SET imagem1=NULL WHERE id=:id";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);  
        $rsInsert->execute();

        $r = 0;

        //Para todas as línguas e enquanto não encontrar-mos outra categoria com a imagem a ser removida...
        foreach ($row_rsLinguas as $linguas) {
          $query_rsImagem = "SELECT id FROM clientes_login_".$linguas["sufixo"]." WHERE imagem1=:imagem AND id=:id";
          $rsImagem = DB::getInstance()->prepare($query_rsImagem);
          $rsImagem->bindParam(':imagem', $imagem, PDO::PARAM_STR, 5);
          $rsImagem->bindParam(':id', $id, PDO::PARAM_INT);
          $rsImagem->execute();
          $totalRows_rsImagem = $rsImagem->rowCount();

          if($totalRows_rsImagem > 0)
            $r = 1;
        }

        //Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la
        if($r == 0) {
          @unlink('../../../imgs/clientes/'.$imagem);
        }
      }
      else if($opcao == 2) {
        foreach ($row_rsLinguas as $linguas) { 
          $query_rsSelect = "SELECT imagem1 FROM clientes_login_".$linguas['sufixo']." WHERE id=:id";
          $rsSelect = DB::getInstance()->prepare($query_rsSelect);
          $rsSelect->bindParam(':id', $id, PDO::PARAM_INT);
          $rsSelect->execute();
          $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

          @unlink('../../../imgs/clientes/'.$row_rsSelect['imagem1']);

          $insertSQL = "UPDATE clientes_login_".$linguas["sufixo"]." SET imagem1=NULL WHERE id=:id";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 
          $rsInsert->execute();
        }
      }
    }

    if($_FILES['img']['name']!='') { // actualiza imagem
      //Verificar o formato do ficheiro
      $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

      if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png") {
        $erro = 1;
      }
      else {
        $ins = 1; 
        require("../resize_image.php");
        
        $imagem="";   
        
        $imgs_dir = "../../../imgs/clientes";
        $contaimg = 1; 
    
        foreach($_FILES as $file_name => $file_array) {
      
          $id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);
          
          switch ($contaimg) {
            case '1': case '2': case '3':    
              $file_dir =  $imgs_dir;
            break;
          }
          
          if($file_array['size'] > 0) {
            $nome_img=verifica_nome($file_array['name']);
            $nome_file = $id_file."_".$nome_img;
            @unlink($file_dir.'/'.$_POST['file_db_'.$contaimg]);
          }
          else {
            if($_POST['file_db_'.$contaimg]) {
              $nome_file = $_POST['file_db_'.$contaimg];
            }
            else {
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
          $contaimg++;
                              
        } // fim foreach
        //Fim do Trat. Imagens
          
        //RESIZE DAS IMAGENS
        $imagem = $nome_file1;

        //IMAGEM 1
        if($_FILES['img']['name']!='') {
          if($imagem!="" && file_exists("../../../imgs/clientes/".$imagem)) {
            $maxW=1000;
            $maxH=1000;
            
            $sizes=getimagesize("../../../imgs/clientes/".$imagem);
            
            $imageW=$sizes[0];
            $imageH=$sizes[1];
            
            if($imageW>$maxW || $imageH>$maxH) {                   
              $img1=new Resize("../../../imgs/clientes/", $imagem, $imagem, $maxW, $maxH);
              $img1->resize_image();          
            }
          }   
          
          if($row_rsP['imagem1']) {
            @unlink('../../../imgs/clientes/'.$row_rsP['imagem1']);
          }

          compressImage('../../../imgs/clientes/'.$imagem, '../../../imgs/clientes/'.$imagem);

          //Inserir apenas na língua atual
          if($opcao == 1) {
            $insertSQL = "UPDATE clientes_login".$extensao." SET imagem1=:imagem1 WHERE id=:id";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);    
            $rsInsert->execute();
          }
          //Inserir para todas as línguas
          else if($opcao == 2) {            
            foreach ($row_rsLinguas as $linguas) {  
              $insertSQL = "UPDATE clientes_login_".$linguas["sufixo"]." SET imagem1=:imagem1 WHERE id=:id";
              $rsInsert = DB::getInstance()->prepare($insertSQL);
              $rsInsert->bindParam(':imagem1', $imagem, PDO::PARAM_STR, 5);
              $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);    
              $rsInsert->execute();
            }
          }
        }
      }
    }

    DB::close();
    
    if($erro == 1)
      header("Location: login.php?erro=1&tab_sel=2");
    else 
      header("Location: login.php?alt=1&tab_sel=2");
  }
}

$query_rsP = "SELECT * FROM clientes_login".$extensao." WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['menu_clientes_login']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="login.php"><?php echo $RecursosCons->RecursosCons['menu_clientes_login']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT--> 
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_texto" name="frm_texto" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['menu_clientes_login']; ?> </div>
	              <div class="form-actions actions btn-set">
	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?> </button>
	                <button type="submit" class="btn green"><i class="fa fa-check-circle"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?> </button>
	              </div>
	            </div>
	            <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                    <?php if(LOGIN == 'TYPE3' || LOGIN == 'ALL') { ?><li <?php if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_images" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_imagem']; ?> </a> </li><?php } ?>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane <?php if($tab_sel == 1) echo "active"; ?>" id="tab_general">
                      <div class="form-body">
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 
                        </div>
                        <?php if($_GET['alt'] == 1 && $tab_sel == 1) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> 
                          </div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="texto_password"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                          <div class="col-md-8">
                            <p class="help-block"><?php echo $RecursosCons->RecursosCons['texto_password_aviso']; ?></p>
                            <textarea class="form-control" id="texto_password" name="texto_password"><?php echo $row_rsP['texto_password']; ?></textarea>
                          </div>
                        </div>
                        <?php if(LOGIN == 'TYPE1' || LOGIN == 'TYPE2' || LOGIN == 'ALL') { ?>
                          <hr>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>
                            <div class="col-md-8">
                              <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row_rsP['titulo']; ?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>
                            <div class="col-md-8">
                              <textarea class="form-control" id="texto" name="texto"><?php echo $row_rsP['texto']; ?></textarea>
                            </div>
                          </div>
                        <?php } ?>
                        <?php if(LOGIN == 'TYPE2' || LOGIN == 'ALL') { ?>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="titulo2"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?> 2: </label>
                            <div class="col-md-8">
                              <input type="text" class="form-control" name="titulo2" id="titulo2" value="<?php echo $row_rsP['titulo2']; ?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="texto2"><?php echo $RecursosCons->RecursosCons['texto_label']; ?> 2: </label>
                            <div class="col-md-8">
                              <textarea class="form-control" id="texto2" name="texto2"><?php echo $row_rsP['texto2']; ?></textarea>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_images">
                      <div class="form-body">
                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <?php echo $RecursosCons->RecursosCons['alt_dados']; ?> 
                          </div>
                        <?php } ?>
                        <?php if($_GET['erro'] == 1 && $_GET['tab_sel'] == 2) { ?>
                          <div class="alert alert-danger display-show">
                            <button class="close" data-close="alert"></button>
                            <?php echo $RecursosCons->RecursosCons['erro_ficheiro']; ?> 
                          </div>   
                        <?php } ?> 
                        <div class="form-group">
                          <label class="col-md-2 control-label" style="text-align:right"><?php echo $RecursosCons->RecursosCons['imagem']; ?><br>
                            <strong>1000 * 1000 px:</strong> </label>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-<?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/clientes/".$row_rsP['imagem1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                <?php if($row_rsP['imagem1']!="" && file_exists("../../../imgs/clientes/".$row_rsP['imagem1'])) { ?>
                                <a href="../../../imgs/clientes/<?php echo $row_rsP['imagem1']; ?>" class="fancybox-button" data-rel="fancybox-button"><img src="../../../imgs/clientes/<?php echo $row_rsP['imagem1']; ?>"></a>
                                <?php } ?>
                              </div>
                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                                <input id="upload_campo" type="file" name="img">
                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
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
                            </script><br><br>
                          </div>
                          <label class="col-md-2 control-label" for="opcao"><?php echo $RecursosCons->RecursosCons['guardar_para']; ?>: </label>
                          <div class="col-md-4">
                            <div style="margin-top: 8px" class="md-radio-list">
                              <div class="md-radio">
                                <input type="radio" id="opcao1" name="opcao" value="1" class="md-radiobtn" checked>
                                <label for="opcao1">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span>
                                <?php echo $RecursosCons->RecursosCons['lingua_atual']; ?> </label>
                              </div>
                              <div class="md-radio">
                                <input type="radio" id="opcao2" name="opcao" value="2" class="md-radiobtn">
                                <label for="opcao2">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span>
                                <?php echo $RecursosCons->RecursosCons['todas_linguas']; ?> </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_texto" />
          </form>
        </div>
      </div>
      <!-- END PAGE CONTENT--> 
    </div>
  </div>
  <!-- END CONTENT -->
  <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
});
</script> 
<script type="text/javascript">
CKEDITOR.replace('texto_password',
{
	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	toolbar : "Basic2",
	height: "200px"
});

<?php if(LOGIN == 'TYPE1' || LOGIN == 'TYPE2' || LOGIN == 'ALL') { ?>
  CKEDITOR.replace('texto',
  {
    filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
    filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
    filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
    toolbar : "Basic2",
    height: "200px"
  });
<?php } ?>

<?php if(LOGIN == 'TYPE2' || LOGIN == 'ALL') { ?>
  CKEDITOR.replace('texto2',
  {
    filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
    filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
    filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
    toolbar : "Basic2",
    height: "200px"
  });
<?php } ?>
</script> 
</body>
<!-- END BODY -->
</html>