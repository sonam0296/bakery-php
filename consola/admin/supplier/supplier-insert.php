<?php include_once('../inc_pages.php'); ?>
<?php

$menu_sel='supplier';

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_noticias")) {
	if($_POST['nome']!='') {
		$insertSQL = "SELECT MAX(id) FROM supplier";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->execute();
		$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
		
		$id = $row_rsInsert["MAX(id)"];
		
		$query_rsLinguas = "SELECT sufixo, nome_para_noticias FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
		
		while($row_rsLinguas = $rsLinguas->fetch()) {
			$prefixo = $row_rsLinguas['nome_para_noticias']."-";
			
			$nome=strtolower(verifica_nome($_POST['nome']));
			
			$query_rsProc = "SELECT id FROM supplier WHERE title like :nome";
			$rsProc = DB::getInstance()->prepare($query_rsProc);
      $rsProc->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
			$rsProc->execute();
			$row_rsProc = $rsProc->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsProc = $rsProc->rowCount();
			
			if($totalRows_rsProc>0){
				$nome=$nome;
			}
			
			$descricao=str_replace("</p><p>", "<br />", $_POST['resumo']);
			$descricao=str_replace("</p>", "", $descricao);
			$descricao=str_replace("<p>", "", $descricao);
			$descricao=str_replace("<br>", "<br />", $descricao);
			
			$descricao=trim($descricao);	
		
			$data = NULL;
			if(isset($_POST['data']) && $_POST['data'] != "0000-00-00" && $_POST['data'] != "") $data = $_POST['data'];
			
			$insertSQL = "INSERT INTO supplier ( title, data , product_order_number, product_account_number, s_email_id, s_address) VALUES (:nome, :data, :product_order_number, :product_account_number, :s_email_id, :supplier_address)";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
      //$rsInsert->bindParam(':id', $id, PDO::PARAM_INT, 5);
			$rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':data', $data , PDO::PARAM_STR, 5);	
      $rsInsert->bindParam(':product_order_number', $_POST['product_order_number'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':product_account_number', $_POST['product_account_number'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':s_email_id', $_POST['s_email_id'], PDO::PARAM_STR, 5);
			$rsInsert->bindParam(':supplier_address', $_POST['supplier_address'], PDO::PARAM_STR, 5);
			$rsInsert->execute();
		}

    DB::close();

    alteraSessions('supplier');
		
		header("Location: supplier-edit.php?id=".$id."&env=1");
	}
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
      <h3 class="page-title"> Supplier <small><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="supplier.php">Supplier</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['inserir_registo']; ?></a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_noticias" name="frm_noticias" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['noticias_novo_registo']; ?> </div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='supplier.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_cont']; ?></button>
                </div>
              </div>
              <div class="portlet-body">
                <div class="form-body">
                  <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?php echo $RecursosCons->RecursosCons['msg_required']; ?> </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="data"><?php echo $RecursosCons->RecursosCons['data_label']; ?>: <span class="required"> * </span></label>
                    <div class="col-md-3">
                      <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-filter input-sm" name="data" placeholder="Data" id="data" value="<?php echo $_POST['data']; ?>" data-required="1">
                        <span class="input-group-btn">
                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                        </span> 
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $_POST['nome']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="product_order_number">Product Order Number: <span class="required"> * </span></label>
                    <div class="col-md-8">
                     <input type="text" class="form-control" name="product_order_number" id="product_order_number" value="<?php echo $_POST['product_order_number']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="product_account_number">Product Account Number: <span class="required"> * </span></label>
                    <div class="col-md-8">
                     <input type="text" class="form-control" name="product_account_number" id="product_account_number" value="<?php echo $_POST['product_account_number']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="s_email_id">Supplier Email Id: <span class="required"> * </span></label>
                    <div class="col-md-8">
                     <input type="text" class="form-control" name="s_email_id" id="s_email_id" value="<?php echo $_POST['s_email_id']; ?>" data-required="1">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-2 control-label" for="supplier_address">Supplier Address: <span class="required"> * </span> </label>
                    <div class="col-md-8">
                      <textarea class="form-control" id="supplier_address" name="supplier_address"><?php echo $_POST['supplier_address']; ?></textarea>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_noticias" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="noticias-validation.js"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   Noticias.init();
});
</script> 
<script type="text/javascript">
CKEDITOR.replace('supplier_address',
{
	filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
	filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
	filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	toolbar : "Basic",
	height: "250px"
});

var wordCountConf1 = {
  showParagraphs: false,
  showWordCount: false,
  showCharCount: true,
  countSpacesAsChars: true,
  countHTML: false,
  maxWordCount: -1,
  maxCharCount: 150
}

CKEDITOR.replace('resumo',
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