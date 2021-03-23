<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

$id = $_GET['id'];
$fixo = $_GET['fixo'];
$pagina = $_GET['pagina'];

$menu_sel='paginas';
$menu_sub_sel='paginas_fixas';
$nome_sel='Paginas Fixas';

if($fixo == 0) {
	$menu_sub_sel='paginas_outras';
	$nome_sel='Outras Páginas';
}

$tab_sel=2;
$inserido=0;

$tamanho_imagens1 = getFillSize('Paginas', 'imagem1');
$tamanho_imagens2 = getFillSize('Paginas', 'imagem2');
$tamanho_imagens3 = getFillSize('Paginas', 'imagem3');

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if($id>0 && isset($_GET['id_img']) && $_GET['id_img'] != "" && $_GET['id_img'] != 0) {
		$id_img = $_GET['id_img'];	
		
		$query_rsProc = "SELECT imagem1 FROM paginas_blocos_imgs WHERE id=:id_img AND bloco = :id";
		$rsProc = DB::getInstance()->prepare($query_rsProc);
		$rsProc->bindParam(':id', $id, PDO::PARAM_INT, 5);
    $rsProc->bindParam(':id_img', $id_img, PDO::PARAM_INT, 5);  
		$rsProc->execute();
    $row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsProc = $rsProc->rowCount();
		
		if($totalRows_rsProc>0) {
			if ($row_rsProc['imagem1']!='') {
				@unlink('../../../imgs/paginas/'.$row_rsProc['imagem1']);
			}
		
			$query_rsP = "DELETE FROM paginas_blocos_imgs WHERE id=:id_img AND bloco = :id";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id', $id, PDO::PARAM_INT, 5);
      $rsP->bindParam(':id_img', $id_img, PDO::PARAM_INT, 5);
			$rsP->execute();		
		}

    DB::close();	

    alteraSessions('paginas');
    alteraSessions('paginas_menu');
    alteraSessions('paginas_fixas');
		
		header("Location: paginas-blocos-imagens.php?id=".$id."&pagina=".$pagina."&fixo=".$fixo."&r=1");
	}
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_link_video")) {
  $insertSQL = "SELECT MAX(id) FROM paginas_blocos_imgs";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->execute();
  $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
  
  $max_id_2 = $row_rsInsert["MAX(id)"]+1;

  $tipo = 2;   
  
  $insertSQL = "INSERT INTO paginas_blocos_imgs (id, bloco, imagem1, tipo, proporcao_video) VALUES (:max_id_2, :id, :imagem1, :tipo, :proporcao_video)";
  $rsInsert = DB::getInstance()->prepare($insertSQL);
  $rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);
  $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
  $rsInsert->bindParam(':imagem1', $_POST['video'], PDO::PARAM_STR, 5);
  $rsInsert->bindParam(':proporcao_video', $_POST['proporcao_video'], PDO::PARAM_INT);
  $rsInsert->bindParam(':tipo', $tipo, PDO::PARAM_INT);
  $rsInsert->execute();
  DB::close();

  $inserido=1;

  alteraSessions('paginas');
  alteraSessions('paginas_menu');
  alteraSessions('paginas_fixas');
    
  if(!$manter) header("Location: paginas-blocos-imagens.php?alt=1&id=".$id."&pagina=".$pagina."&fixo=".$fixo);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_largura_imgs")) {

  $query_rsLinguas = "SELECT * FROM linguas WHERE visivel = '1'";
  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
  $rsLinguas->execute();
  $row_rsLinguas = $rsLinguas->fetchAll();
  
  foreach ($row_rsLinguas as $lingua) {
    $insertSQL = "UPDATE paginas_blocos_".$lingua['sufixo']." SET largura_imgs=:largura_imgs, valor_largura_imgs=:valor_largura_imgs WHERE id=:id";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':largura_imgs', $_POST['largura_imgs'], PDO::PARAM_INT);
    $rsInsert->bindParam(':valor_largura_imgs', $_POST['valor_largura_imgs'], PDO::PARAM_INT);
    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);  
    $rsInsert->execute();
  }
  DB::close();

  alteraSessions('paginas');
  alteraSessions('paginas_menu');
  alteraSessions('paginas_fixas');
    
  if(!$manter) header("Location: paginas-blocos-imagens.php?def=1&id=".$id."&pagina=".$pagina."&fixo=".$fixo);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_form")) {
	$manter = $_POST['manter'];
	
	$query_rsImg = "SELECT * FROM paginas_blocos_imgs WHERE bloco=:id ORDER BY ordem ASC, id DESC";
	$rsImg = DB::getInstance()->prepare($query_rsImg);
	$rsImg->bindParam(':id', $id, PDO::PARAM_INT, 5);
	$rsImg->execute();
	$totalRows_rsImg = $rsImg->rowCount();
	
	if($totalRows_rsImg) {
		while($row_rsImg = $rsImg->fetch()) {
			$id_img=$row_rsImg['id'];	
      $coluna=$_POST['coluna_'.$id_img];
			$link=$_POST['link_'.$id_img];		
			$ordem=$_POST['ordem_'.$id_img];

      $proporcao_video = 1;
      if($row_rsImg['tipo'] != 0){ // Se for diferente de imagem, vai guadar a proporção.
        $proporcao_video=$_POST['proporcao_video_'.$id_img];  
      }
			
			$insertSQL = "UPDATE paginas_blocos_imgs SET ordem=:ordem, link=:link, coluna=:coluna, proporcao_video=:proporcao_video WHERE id=:id_img AND bloco = :id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT);
      $rsInsert->bindParam(':coluna', $coluna, PDO::PARAM_INT);
			$rsInsert->bindParam(':link', $link, PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
      $rsInsert->bindParam(':id_img', $id_img, PDO::PARAM_INT);
      $rsInsert->bindParam(':proporcao_video', $proporcao_video, PDO::PARAM_INT);
			$rsInsert->execute();			
		}	
	}

  DB::close();
		
  alteraSessions('paginas');
  alteraSessions('paginas_menu');
  alteraSessions('paginas_fixas');
  
	$inserido=1;
		
	if(!$manter) header("Location: paginas-blocos-imagens.php?alt=1&id=".$id."&pagina=".$pagina."&fixo=".$fixo);
}


$query_rsP = "SELECT * FROM paginas".$extensao." WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $pagina, PDO::PARAM_INT, 5);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();

$query_rsP2 = "SELECT * FROM paginas_blocos".$extensao." WHERE id=:id";
$rsP2 = DB::getInstance()->prepare($query_rsP2);
$rsP2->bindParam(':id', $id, PDO::PARAM_INT, 5);	
$rsP2->execute();
$row_rsP2 = $rsP2->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP2 = $rsP2->rowCount();

$query_rsImg = "SELECT * FROM paginas_blocos_imgs WHERE bloco=:id ORDER BY ordem ASC, id DESC";
$rsImg = DB::getInstance()->prepare($query_rsImg);
$rsImg->bindParam(':id', $id, PDO::PARAM_INT, 5);
$rsImg->execute();
$totalRows_rsImg = $rsImg->rowCount();

DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
  #alert_div {
    display: none;
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
      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['paginas']; ?> <small><?php echo $nome_sel; ?> - <?php echo $RecursosCons->RecursosCons['blocos']; ?> </small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
            <li>
                <a href="paginas.php?fixo=<?php echo $fixo; ?>"> <?php echo $RecursosCons->RecursosCons['paginas']; ?></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="paginas-edit.php?fixo=<?php echo $fixo; ?>&id=<?php echo $pagina; ?>"> <?php echo $RecursosCons->RecursosCons['blocos']; ?> </a>
            </li>
        </ul>
      </div>
      <!-- END PAGE HEADER-->
      <div class="modal fade bs-modal-lg" id="large" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="margin-top: 10%;">
          <div class="modal-content">
            <form id="form_link_video" name="form_link_video" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['adicionar_link_video']; ?></h4>
            </div>
            <div class="modal-body">
              <div class="portlet-body">
                <div class="form-group">
                  <label class="col-md-2 control-label" for="video"><?php echo $RecursosCons->RecursosCons['video_label']; ?>: </label>
                  <div class="col-md-8">
                    <textarea class="form-control" name="video" id="video"><?php echo $_POST['video']; ?></textarea>
                    <p class="help-block"><?php echo $RecursosCons->RecursosCons['help_block_video']; ?></strong></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label" for="proporcao_video"><?php echo $RecursosCons->RecursosCons['proporcao_video']; ?>: </label>
                  <div class="col-md-8 md-radio-inline">
                    <div class="md-radio">
                      <input id="proporcao1" name="proporcao_video" value="1" checked class="md-radiobtn radio_proporcao" type="radio">
                      <label for="proporcao1">
                      <span></span>
                      <span class="check"></span>
                      <span class="box"></span>
                      16:9 </label>
                    </div>
                    <div class="md-radio">
                      <input id="proporcao2" name="proporcao_video" value="2" class="md-radiobtn radio_proporcao" type="radio">
                      <label for="proporcao2">
                      <span></span>
                      <span class="check"></span>
                      <span class="box"></span>
                      4:3 </label>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" name="MM_insert" value="form_link_video"/>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['btn_fechar']; ?></button>
              <button type="submit" class="btn green"><i class="fa fa-save"></i> <?php echo $RecursosCons->RecursosCons['inserir']; ?></button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <div class="modal fade bs-modal-lg" id="largura" tabindex="-1" role="dialog" aria-hidden="true" ">
        <div class="modal-dialog modal-md" style="margin-top: 10%;">
          <div class="modal-content">
            <form id="form_largura_imgs" name="form_largura_imgs" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['definicoes_largura']; ?></h4>
            </div>
            <div class="modal-body">
              <div class="portlet-body">
                <div class="form-group">
                  <label class="col-md-4 control-label" for="largura_imgs"><?php echo $RecursosCons->RecursosCons['largura_texto_label']; ?>: </label>
                  <div class="col-md-8 md-radio-inline">
                    <div class="md-radio">
                      <input id="largura_imgs1" name="largura_imgs" value="1" <?php if($row_rsP2['largura_imgs']==1) echo "checked"; ?> class="md-radiobtn radio_largura_imgs" type="radio">
                      <label for="largura_imgs1">
                      <span></span>
                      <span class="check"></span>
                      <span class="box"></span>
                      <?php echo $RecursosCons->RecursosCons['largura_max']; ?> </label>
                    </div>
                    <div class="md-radio">
                      <input id="largura_imgs2" name="largura_imgs" value="2" <?php if($row_rsP2['largura_imgs']==2) echo "checked"; ?>  class="md-radiobtn radio_largura_imgs" type="radio">
                      <label for="largura_imgs2">
                      <span></span>
                      <span class="check"></span>
                      <span class="box"></span>
                      <?php echo $RecursosCons->RecursosCons['largura_especifica']; ?>  </label>
                    </div>
                  </div>
                </div>
                <div class="form-group valor-largura-texto" <?php if($row_rsP2['largura_imgs']==1){ ?> style="display: none; <?php } ?>">
                  <label class="col-md-4 control-label " for="valor_largura_imgs"><?php echo $RecursosCons->RecursosCons['valor_largura_label']; ?>: </label>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" name="valor_largura_imgs" id="valor_largura_imgs" value="<?php echo $row_rsP2['valor_largura_imgs']; ?>">
                      <span id="span_desconto" class="input-group-addon">px</span>
                    </div> 
                  </div>
                </div>
              </div>
              <input type="hidden" name="MM_insert" value="form_largura_imgs"/>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['btn_fechar']; ?></button>
              <button type="submit" class="btn green"><i class="fa fa-save"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php //include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="produtos_form" name="produtos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <div class="portlet">
              <div class="portlet-title">
               <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $nome_sel; ?> - <?php echo $row_rsP['nome']; ?> - Bloco - <?php echo $row_rsP2['nome']; ?></div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='paginas-blocos.php?fixo=<?php echo $fixo; ?>&pagina=<?php echo $pagina; ?>'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li onclick="window.location='paginas-blocos-edit.php?id=<?php echo $id; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>&tab_sel=1'"><a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>
                	   <li class="active"><a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['tab_imagens_videos']; ?> </a> </li>
                     <?php /* <li onclick="window.location='paginas-blocos-edit.php?id=<?php echo $id; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>&tab_sel=3'"><a href="javascript:void(null)"> <?php echo $RecursosCons->RecursosCons['tab_fundo']; ?> </a> </li> */ ?>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_imagens">
                      <?php if($inserido==1) {?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['info_suc']; ?> </span> </div>
                      <?php } else if($_GET['suc']==1) { ?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['img_insert_suc']; ?> </span> </div>
                      <?php } else if($_GET['r']==1) { ?>
                      <div class="alert alert-danger display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['img_r']; ?>  </span> </div>
                      <?php } else if($_GET['def']==1) { ?>
                      <div class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['def']; ?>  </span> </div>
                      <?php } ?>
                      <div id="alert_div" class="alert alert-success display-show">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo $RecursosCons->RecursosCons['info_suc']; ?> </span> 
                      </div>
                      <div>
                        <div id="tab_images_uploader_container" class="form-group text-align-reverse margin-bottom-10">
                          <div class="col-md-4" align="left" style="margin-top: 5px;">
                            <strong>
                              <?php
                                if($row_rsP2['tipo'] == 1 && ($row_rsP2['orientacao'] == 0 || $row_rsP2['orientacao'] == 1)){
                                  echo $RecursosCons->RecursosCons['largura_imagens'].($tamanho_imagens2['0']/2)."px<br>".$RecursosCons->RecursosCons['altura_opcional'];
                                }
                                else if($row_rsP2['tipo'] == 1 && ($row_rsP2['orientacao'] == 2 || $row_rsP2['orientacao'] == 3)){
                                  echo $RecursosCons->RecursosCons['largura_imagens'].$tamanho_imagens2['0']."px<br>".$RecursosCons->RecursosCons['altura_opcional'];
                                }

                                if($row_rsP2['tipo'] == 3 && $row_rsP2['colunas'] == 2){
                                  echo $RecursosCons->RecursosCons['largura_imagens'].($tamanho_imagens2['0']/2)."px<br>".$RecursosCons->RecursosCons['altura_opcional'];
                                }
                                else if($row_rsP2['tipo'] == 3 && $row_rsP2['colunas'] == 3){
                                  echo $RecursosCons->RecursosCons['largura_imagens'].($tamanho_imagens2['0']/3)."px<br>".$RecursosCons->RecursosCons['altura_opcional'];
                                } ?>
                            </strong><br>
                            <div style="margin-top: 30px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt3']; ?></span></div>
                          </div>
                          <div class="col-md-8">
                            <a id="definicoes_largura" href="#largura" data-toggle="modal" class="btn blue"> <i class="icon-settings"></i> <?php echo $RecursosCons->RecursosCons['definicoes_largura']; ?> </a>
                            <a id="add_videos" href="#large" data-toggle="modal" class="btn blue"> <i class="fa fa-plus"></i> <?php echo $RecursosCons->RecursosCons['add_videos_link']; ?> </a> 
                            <a id="tab_images_uploader_pickfiles" href="javascript:;" class="btn yellow"> <i class="fa fa-plus"></i> <?php echo $RecursosCons->RecursosCons['selec_imagens_videos']; ?> </a> <a id="tab_images_uploader_uploadfiles" href="javascript:;" class="btn green"> <i class="fa fa-share"></i> <?php echo $RecursosCons->RecursosCons['upload_imagens_videos']; ?> </a></div> 
                        </div>
                        <div class="row">
                          <div id="tab_images_uploader_filelist" class="col-md-6 col-sm-12"> </div>
                        </div>
                        <table class="table table-bordered table-hover margin-top-10">
                          <thead>
                            <tr role="row" class="heading">
                              <th width="25%"><?php echo $RecursosConss->RecursosCons['imagem']; ?></th>
                              <?php if($row_rsP2["tipo"] == 3 || $row_rsP2["tipo"] == 6){ ?>
                                <th width="20%"><?php echo $RecursosCons->RecursosCons['coluna_label']; ?></th>
                              <?php } ?>
                              <th width="25%"><?php echo $RecursosCons->RecursosCons['link_label']; ?></th>
                              <th width="10%"><?php echo $RecursosCons->RecursosCons['ordem']; ?></th>
                              <th width="10%"><?php echo $RecursosCons->RecursosCons['visivel_tab']; ?></th>
                              <th width="10%"></th>
                            </tr>
                          </thead>
                          <?php if($totalRows_rsImg>0){ ?>
                          <tbody>
                            <?php $cont=0; while($row_rsImg = $rsImg->fetch()) { $cont++; ?>
                            <?php if($row_rsImg['imagem1']!="") { 
                              $temp_var = explode('.', $row_rsImg['imagem1']); //Para fazer verificação se é imagem ou vídeo
                            ?>
                            <tr>
                              <td>
                                <?php if($temp_var[1] != "mp4" && $temp_var[1] != "wmv" && (filter_var($row_rsImg['imagem1'], FILTER_VALIDATE_URL) === FALSE)){ ?>
                                  <a href="../../../imgs/paginas/<?php echo $row_rsImg['imagem1']; ?>" data-fancybox="gallery" > <img class="img-responsive" style="max-width: 150px" src="../../../imgs/paginas/<?php echo $row_rsImg['imagem1']; ?>"></a>
                                <?php } else { ?>
                                  <a data-fancybox="gallery" <?php if(file_exists("../../../imgs/paginas/".$row_rsImg['imagem1'])){ ?>href="../../../imgs/paginas/<?php echo $row_rsImg['imagem1']; ?>" <?php } else { ?> href="<?php echo $row_rsImg['imagem1']; ?>" <?php } ?> class="btn btn-primary"> <i class="fa fa-play"></i> <span class="hidden-480"><?php echo $RecursosCons->RecursosCons['ver_video']; ?></a>
                                  <div class="md-radio-inline" style="display: inline-block; margin-left: 10px;">
                                    <div class="md-radio">
                                      <input id="proporcao_<?php echo $row_rsImg['id']; ?>_1" name="proporcao_video_<?php echo $row_rsImg['id']; ?>" value="1" <?php if($row_rsImg['proporcao_video']==1) echo "checked"; ?> class="md-radiobtn radio_proporcao" type="radio">
                                      <label for="proporcao_<?php echo $row_rsImg['id']; ?>_1">
                                      <span></span>
                                      <span class="check"></span>
                                      <span class="box"></span>
                                      16:9 </label>
                                    </div>
                                    <div class="md-radio">
                                      <input id="proporcao_<?php echo $row_rsImg['id']; ?>_2" name="proporcao_video_<?php echo $row_rsImg['id']; ?>" value="2" <?php if($row_rsImg['proporcao_video']==2) echo "checked"; ?> class="md-radiobtn radio_proporcao" type="radio">
                                      <label for="proporcao_<?php echo $row_rsImg['id']; ?>_2">
                                      <span></span>
                                      <span class="check"></span>
                                      <span class="box"></span>
                                      4:3 </label>
                                    </div>
                                  </div>
                                <?php } ?>
                              </td>
                              <?php if($row_rsP2["tipo"] == 3){ ?>
                                <td>
                                  <div class="col-md-10">
                                    <select class="form-control" id="coluna_<?php echo $row_rsImg['id']; ?>" name="coluna_<?php echo $row_rsImg['id']; ?>">
                                      <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>
                                      <option value="1" <?php if($row_rsImg['coluna'] == 1) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['coluna1_label']; ?></option>
                                      <option value="2" <?php if($row_rsImg['coluna'] == 2) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['coluna2_label']; ?></option>
                                      <?php if($row_rsP2['colunas']==3) { ?> 
                                        <option value="3" <?php if($row_rsImg['coluna'] == 3) echo "selected"; ?>><?php echo $RecursosCons->RecursosCons['coluna3_label']; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div> 
                                </td>
                              <?php } ?>
                              <td><input type="text" class="form-control" name="link_<?php echo $row_rsImg['id']; ?>" value="<?php echo $row_rsImg['link']; ?>"
                                <?php if($temp_var[1] == "mp4" || $temp_var[1] == "wmv" || filter_var($row_rsImg['imagem1'], FILTER_VALIDATE_URL) != '') echo "disabled"; ?>></td>
                              <td><input type="text" class="form-control" name="ordem_<?php echo $row_rsImg['id']; ?>" value="<?php echo $row_rsImg['ordem']; ?>"></td>
                              <td><input type="checkbox" <?php if($row_rsImg['visivel'] == 1) { echo "checked"; } ?> name="switch" id="switch_<?php echo $row_rsImg['id']; ?>" class="make-switch" data-on-color="success" data-on-text="Sim" data-off-color="danger" data-off-text="Não" data-off-value="0" data-on-value="1"></td>
                              <td><a href="#modal_delete_img_<?php echo $row_rsImg['id']; ?>" data-toggle="modal" class="btn default btn-sm"><i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a>
                                <div class="modal fade" id="modal_delete_img_<?php echo $row_rsImg['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>
                                      </div>
                                      <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['rem_imagem_msg']; ?> </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn blue" onClick="document.location='paginas-blocos-imagens.php?id=<?php echo $id; ?>&pagina=<?php echo $pagina; ?>&fixo=<?php echo $fixo; ?>&rem=1&id_img=<?php echo $row_rsImg['id']; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>
                                        <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>
                                      </div>
                                    </div>
                                    <!-- /.modal-content --> 
                                  </div>
                                  <!-- /.modal-dialog --> 
                                </div>
                              </td>
                            </tr>
                            <?php }else{ ?>
                            <?php	
                							$query_rsDelete = "DELETE FROM paginas_blocos_imgs WHERE id='".$row_rsImg['id']."'";
                							$rsDelete = DB::getInstance()->query($query_rsDelete);
                							$rsDelete->execute();
                							DB::close();
                						} ?>
                            <?php } ?>
                          </tbody>
                          <?php } ?>
                        </table>
                        <?php if($totalRows_rsImg == 0) { ?>
                          <?php echo $RecursosCons->RecursosCons['sem_imagens_inseridas']; ?>
                        <?php } ?>
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<!--     --> 
<script src="paginas-blocos-imagens.js" data-tipo="<?php echo $row_rsP2['tipo']; ?>" data-id="<?php echo $id; ?>" data-ori="<?php echo $row_rsP2['orientacao']; ?>" data-pag="<?php echo $pagina; ?>" data-fixo="<?php echo $fixo; ?>" data-colunas="<?php echo $row_rsP2['colunas']; ?>" data-fullscreen="<?php echo $row_rsP2['fullscreen']; ?>" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  EcommerceProductsEdit.init();

  $('.radio_largura_imgs').on('change', function() {
    var id2 = $(this).attr('id');

    if(id2 == 'largura_imgs1'){
      $('.valor-largura-texto').css('display', 'none');
    }
    else if(id2 == 'largura_imgs2'){
      $('.valor-largura-texto').css('display', 'block');
    }
  });
   
  $('.bootstrap-switch').click(function() {
    var valor = 0;
    if($(this).hasClass('bootstrap-switch-on')) valor = 1;
    var parts = $(this).find('input').attr('id').split('_');
    var id = parts['1'];
    $.ajax({
      url: 'paginas-rpc.php',
      type: 'POST',
      data: {op: 'galeria_visivel', valor: valor, id: id},
    })
    .done(function(data) {
      $('#alert_div').css('display', 'block');

      setTimeout(function() { $('#alert_div').css('display', 'none'); }, 10000);
    });
  });
});
</script>
</body>
<!-- END BODY -->
</html>