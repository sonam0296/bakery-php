<?php include_once('../inc_pages.php');
include('library/php_qr_code/qrlib.php');
 ?>
<?php 
$menu_sel='ec_produtos_produtos';
$menu_sub_sel='';

$id_delete = $_GET["id_delete"];

if(!empty($id_delete)){

   	  $query_rsP = "DELETE FROM l_pecas_imagens WHERE id_peca = ".$id_delete."";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
      
      $query_rsP = "DELETE FROM l_pecas_categorias WHERE id_peca = ".$id_delete."";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
    
      $query_rsP = "DELETE FROM l_pecas_caract WHERE id_peca = ".$id_delete."";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
      
      $query_rsP = "DELETE FROM l_pecas_filtros WHERE id_peca = ".$id_delete."";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
    
      $query_rsP = "DELETE FROM l_pecas_desconto WHERE id_peca = ".$id_delete."";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();

      $query_rsP = "DELETE FROM l_pecas_tamanhos WHERE peca = ".$id_delete."";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();
    
      $query_rsP = "DELETE FROM l_pecas_relacao WHERE id_peca = ".$id_delete."";
      $rsP = DB::getInstance()->prepare($query_rsP);
      $rsP->execute();

      $query_rsP = "DELETE FROM l_pecas_en WHERE id = ".$id_delete."";
	  $rsP = DB::getInstance()->prepare($query_rsP);
	  $rsP->execute();

	  header("Location: produtos.php?r=1");
 	}

if(isset($_GET['rem']) && $_GET['rem'] == 1) {
	if(isset($_GET['id']) && $_GET['id'] != "" && $_GET['id'] != 0) {
		$id = $_GET['id'];	
			
		$query_rsProc = "SELECT imagem1, imagem2, imagem3, imagem4 FROM l_pecas_imagens WHERE id_peca = :id";
		$rsProc = DB::getInstance()->prepare($query_rsProc);
		$rsProc->bindParam(':id', $id, PDO::PARAM_INT);
		$rsProc->execute();
		$totalRows_rsProc = $rsProc->rowCount();	
		
		if($totalRows_rsProc>0) {
			while($row_rsProc = $rsProc->fetch()) {
				@unlink('../../../imgs/produtos/'.$row_rsProc['imagem1']);
				@unlink('../../../imgs/produtos/'.$row_rsProc['imagem2']);
				@unlink('../../../imgs/produtos/'.$row_rsProc['imagem3']);
				@unlink('../../../imgs/produtos/'.$row_rsProc['imagem4']);
			}
		}
		
		$query_rsP = "DELETE FROM l_pecas_imagens WHERE id_peca = :id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();

		$query_rsP = "DELETE FROM l_pecas_categorias WHERE id_peca = :id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();

		$query_rsP = "DELETE FROM l_pecas_caract WHERE id_peca = :id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();
		
		$query_rsP = "DELETE FROM l_pecas_filtros WHERE id_peca = :id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();

		$query_rsP = "DELETE FROM l_pecas_desconto WHERE id_peca = :id";
    $rsP = DB::getInstance()->prepare($query_rsP);
    $rsP->bindParam(':id', $id, PDO::PARAM_INT);
    $rsP->execute();
    
    $query_rsP = "DELETE FROM l_pecas_tamanhos WHERE peca = :id";
    $rsP = DB::getInstance()->prepare($query_rsP);
    $rsP->bindParam(':id', $id, PDO::PARAM_INT);
    $rsP->execute();

    $query_rsP = "DELETE FROM l_pecas_relacao WHERE id_peca = :id";
		$rsP = DB::getInstance()->prepare($query_rsP);
		$rsP->bindParam(':id', $id, PDO::PARAM_INT);
		$rsP->execute();
		
		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
		$rsLinguas->execute();
		$totalRows_rsLinguas = $rsLinguas->rowCount();
		
		while($row_rsLinguas = $rsLinguas->fetch()) {
			$query_rsP = "DELETE FROM l_pecas_".$row_rsLinguas["sufixo"]." WHERE id = :id";
			$rsP = DB::getInstance()->prepare($query_rsP);
			$rsP->bindParam(':id', $id, PDO::PARAM_INT);
			$rsP->execute();
		}

    DB::close();
			
		header("Location: produtos.php?r=1");
	}
}

$query_rsCat = "SELECT * FROM l_categorias_en WHERE cat_mae=0 ORDER BY nome ASC";
$rsCat = DB::getInstance()->query($query_rsCat);
$rsCat->execute();
$totalRows_rsCat = $rsCat->rowCount();

$query_rsMarcas = "SELECT id, nome FROM l_marcas_pt ORDER BY nome ASC";
$rsMarcas = DB::getInstance()->query($query_rsMarcas);
$rsMarcas->execute();
$totalRows_rsMarcas = $rsMarcas->rowCount();


DB::close();

$query_rsInferior = "SELECT * FROM l_pecas_supplier WHERE product_id=:id";
$rsInferior = DB::getInstance()->prepare($query_rsInferior);
$rsInferior->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
$rsInferior->execute();
$row_rsInferior = $rsInferior->fetch(PDO::FETCH_ASSOC);
$totalRows_rsInferior = $rsInferior->rowCount();

$query_rsSuperior = "SELECT MAX(l_pecas_supplier.id) FROM l_pecas_supplier WHERE product_id=:id";
  $rsSuperior = DB::getInstance()->prepare($query_rsSuperior);
  $rsSuperior->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
  $rsSuperior->execute();
  $row_rsSuperior = $rsSuperior->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsSuperior = $rsSuperior->rowCount();

	
	if(isset($_POST["MM_update"])) {


		/*Update code Popup*/
		$insertSQL = "UPDATE l_pecas_en SET on_order=:order,iva=:vat, preco=:preco,cost=:cost, markup=:markup,stock=:stock, descricao_stock=:descricao_stock, maxstock=:maxstock WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':order', $_POST['order'], PDO::PARAM_INT);
		$rsInsert->bindParam(':vat', $_POST['vat'], PDO::PARAM_INT);
		$rsInsert->bindParam(':cost', $_POST['cost'], PDO::PARAM_INT, 5);
		$rsInsert->bindParam(':preco', $_POST['preco'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':markup', $_POST['markup'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':stock', $_POST['stock'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':descricao_stock', $_POST['descricao_stock'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':maxstock', $_POST['maxstock'], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id',  $_POST['id'], PDO::PARAM_INT); 
		$rsInsert->execute();

		if($_POST['primery'])
		{
		

			$minimo=$row_rsInferior['MIN(l_pecas_supplier.id)'];
			$maximo=$row_rsSuperior['MAX(l_pecas_supplier.id)'];

			for($i=$minimo; $i<=$maximo; $i++) 
			{
				$price=$_POST['sell_preco'.$i];
				$primery=0;
				if($_POST['primery']==$i) $primery=1;

				$insertSQL = "UPDATE l_pecas_supplier SET  price=:price, primery=:primery WHERE id=:id";
				$rsInsert = DB::getInstance()->prepare($insertSQL);
				$rsInsert->bindParam(':price', $price, PDO::PARAM_STR, 5);
				$rsInsert->bindParam(':primery', $primery, PDO::PARAM_INT);
				$rsInsert->bindParam(':id',  $i, PDO::PARAM_INT); 
				$rsInsert->execute();
			}			
		}
	}

$query_rs_pro = "SELECT * FROM l_pecas_en";
$rsP_pro = DB::getInstance()->prepare($query_rs_pro);
$rsP_pro->execute();
$totalRows_pro = $rsP_pro->rowCount();
$row_rsP_pro = $rsP_pro->fetch(PDO::FETCH_ASSOC);


$query_rs_supp = "SELECT * FROM l_pecas".$extensao."";
$rsP_supp = DB::getInstance()->prepare($query_rs_supp);
$rsP_supp->execute();
$totalRows_QR = $rsP_supp->rowCount();
$row_rsP_QR = $rsP_supp->fetchAll();

$query_rsP = "SELECT * FROM  l_pecas".$extensao." WHERE id= '".$_POST['id']."'";
$rsP = DB::getInstance()->prepare($query_rsP);	
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount(); 

if(isset($_REQUEST['qr_submit']) and $_REQUEST['qr_submit']!=""){

  $image = $row_rsP['qr_code'];

  $file= '../../../imgs/QR/'.$image;

  unlink($file);

	//its a location where generated QR code can be stored.
	$qr_code_file_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../imgs/QR'.DIRECTORY_SEPARATOR;
	$set_qr_code_path = '../../../imgs/QR/';

	// If directory is not created, the create a new directory 
	if(!file_exists($qr_code_file_path)){
  	mkdir($qr_code_file_path);
	}
	
	//Set a file name of each generated QR code
	$filename	=	$qr_code_file_path.time().'.png';
	
/* All the user generated data must be sanitize before the processing */
if (isset($_REQUEST['level']) && $_REQUEST['level']!='')
  $errorCorrectionLevel = $_REQUEST['level'];

if (isset($_REQUEST['size']) && $_REQUEST['size']!='')
  $matrixPointSize = $_REQUEST['size'];
	

	$frm_link	=	"Product Name : ".$row_rsP['nome'];
	$frm_link	.=	"Stock : ".$row_rsP['stock'];
	$frm_link	.=	"Price : ".$row_rsP['preco'];
	
	
	// After getting all the data, now pass all the value to generate QR code.
	QRcode::png($frm_link, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

	$store_qr = basename($filename);


	$insertSQL = "UPDATE l_pecas".$extensao." SET qr_code=:qr WHERE id=:id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
	$rsInsert->bindParam(':qr', $store_qr, PDO::PARAM_STR, 5);
	
	$rsInsert->execute();
}


?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<!-- END PAGE LEVEL STYLES -->

<style type="text/css">
	
	/****** CODE ******/
.qrbutton.red.btn
{
	margin-right: 5px;
    height: 28px;
    -webkit-user-modify: read-write-plaintext-only;
}	

.file-upload{display:block;text-align:center;font-family: Helvetica, Arial, sans-serif;font-size: 12px;}
.file-upload .file-select{display:block;border: 2px solid #dce4ec;color: #34495e;cursor:pointer;height:45px;line-height:40px;text-align:left;background:#FFFFFF;overflow:hidden;position:relative;}
.file-upload .file-select .file-select-button{background:#dce4ec;padding:0 10px;display:inline-block;height:40px;line-height:40px;}
.file-upload .file-select .file-select-name{line-height:40px;display:inline-block;padding:0 10px;}
.file-upload .file-select:hover{border-color:#34495e;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
.file-upload .file-select:hover .file-select-button{background:#34495e;color:#FFFFFF;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
.file-upload.active .file-select{border-color:#3fa46a;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
.file-upload.active .file-select .file-select-button{background:#3fa46a;color:#FFFFFF;transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;}
.file-upload .file-select input[type=file]{z-index:100;cursor:pointer;position:absolute;height:100%;width:100%;top:0;left:0;opacity:0;filter:alpha(opacity=0);}
.file-upload .file-select.file-select-disabled{opacity:0.65;}
.file-upload .file-select.file-select-disabled:hover{cursor:default;display:block;border: 2px solid #dce4ec;color: #34495e;cursor:pointer;height:40px;line-height:40px;margin-top:5px;text-align:left;background:#FFFFFF;overflow:hidden;position:relative;}
.file-upload .file-select.file-select-disabled:hover .file-select-button{background:#dce4ec;color:#666666;padding:0 10px;display:inline-block;height:40px;line-height:40px;}
.file-upload .file-select.file-select-disabled:hover .file-select-name{line-height:40px;display:inline-block;padding:0 10px;}
.modal .modal-header {
    border-bottom: 1px solid #EFEFEF;
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: space-between;


    html{
  font-family: "Helvetica Neue", sans-serif;
  width:100%;
  color:#666666;
  text-align:center;
}

.popup-overlay{
  /*Hides pop-up when there is no "active" class*/
  visibility:hidden;
  position:absolute;
  background:#ffffff;
  border:3px solid #666666;
  width:50%;
  height:50%;
  left:25%; 
}
.popup-overlay.active{
  /*displays pop-up when "active" class is present*/
  visibility:visible;
  text-align:center;
}

.popup-content {
  /*Hides pop-up content when there is no "active" class */
 visibility:hidden;
}

.popup-content.active {
  /*Shows pop-up content when "active" class is present */
  visibility:visible;
}

button{
  display:inline-block;
  vertical-align:middle;
  border-radius:30px;
  margin:.20rem;
  font-size: 1rem;
  color:#666666;
  background:	#ffffff;
  border:1px solid #666666;  
}

button:hover{
  border:1px solid #666666;
  background:#666666;
  color:#ffffff;
}
}

</style>
<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>
<body class="<?php echo $body_info; ?>">
<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->      
      		<h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['produtos']; ?> <small><?php echo $RecursosCons->RecursosCons['listagem']; ?> </small> </h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?> </a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?> </a>
					</li>
				</ul>
			</div>
			<!-- END PAGE HEADER-->            
			<!-- BEGIN PAGE CONTENT-->
      <?php if(isset($_GET['r']) && $_GET['r']==1){ ?>
      <div class="alert alert-danger display-show">
        <button class="close" data-close="alert"></button>
        <span> <?php echo $RecursosCons->RecursosCons['r']; ?>  </span> </div>  
      <?php } ?>    
      <?php if(isset($_GET['alt']) && $_GET['alt']==1){ ?>      
      <div class="alert alert-success display-show">
        <button class="close" data-close="alert"></button>
        <span> <?php echo $RecursosCons->RecursosCons['alt']; ?>  </span> </div>
      <?php } ?>
       <!-- START QR CODE || CODE BY VISHAL PRAJAPATI || 17-12-20-->
		<?php 

			if(isset($_REQUEST['submit']) and $_REQUEST['submit']!="")
			{
				$query_rs_supp = "SELECT qr_code FROM l_pecas_en";
				$rsP_supp = DB::getInstance()->prepare($query_rs_supp);
				$rsP_supp->execute();

				while($delete = $rsP_supp->fetch()) 
				{
	                $image = $delete['qr_code'];

	                $file= '../../../imgs/QR/'.$image;

	                unlink($file);
			    }

			    foreach ($row_rsP_QR as $value) 
			    {
					$image = $delete['qr_code'];

					//its a location where generated QR code can be stored.
					$qr_code_file_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'../../../imgs/QR'.DIRECTORY_SEPARATOR;

					$set_qr_code_path = '../../../imgs/QR/';

					// If directory is not created, the create a new directory 
					if(!file_exists($qr_code_file_path))
					{
						mkdir($qr_code_file_path);
					}
					//Set a file name of each generated QR code
					$filename	=	$qr_code_file_path.rand().'.png';

					/* All the user generated data must be sanitize before the processing */
					if (isset($_REQUEST['level']) && $_REQUEST['level']!='')
						$errorCorrectionLevel = $_REQUEST['level'];

					if (isset($_REQUEST['size']) && $_REQUEST['size']!='')
						$matrixPointSize = $_REQUEST['size'];
					
					
					$frm_link	=	  "Product Name : ".$value['nome'];
					$frm_link	.=	"Stock : ".$value['stock'];
					$frm_link	.=	"Price : ".$value['preco'];

					$store_qr = basename($filename);

					$insertSQL = "UPDATE l_pecas".$extensao." SET qr_code=:qr WHERE id=:id";
					$rsInsert = DB::getInstance()->prepare($insertSQL);
					$rsInsert->bindParam(':id', $value['id'], PDO::PARAM_INT);
					$rsInsert->bindParam(':qr', $store_qr, PDO::PARAM_STR, 5);
					
					$rsInsert->execute();
			
					// After getting all the data, now pass all the value to generate QR code.
					QRcode::png($frm_link, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
				}
			}
		?>
			<div class="row">
				<div class="col-md-12">
					<!-- Begin: life time stats -->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-folder"></i><?php echo $RecursosCons->RecursosCons['produtos']; ?> 
							</div>

              <div class="actions" style="margin-left: 10px;"> <?php /*<a href="produtos-atualiza-precos.php" class="btn blue"> <i class="fa fa-upload"></i> <span class="hidden-480"> <?php echo $RecursosCons->RecursosCons['tab_precos_produtos']; ?> </span> </a><?php }  */ ?> <a href="produtos-insert.php" class="btn default yellow-stripe"> <i class="fa fa-plus"></i> <span class="hidden-480"> <?php echo $RecursosCons->RecursosCons['novo_produto']; ?> </span> </a> </div>

              <div class="actions"><a href="javascript:;" class="btn blue" data-toggle="modal" data-target="#exampleModal"> <i class="fa fa-download"></i><span class="hidden-480"> Import CSV</span> </a> </div>

              <div class="actions">
	               	<form method="POST">
		               	<input type="hidden" name="level" value="M" >
					 	<input type="hidden" name="size" value="4">
					   	<input type="submit" name="submit" value="QR UPGRADE" class="btn red qrbutton">
					</form>
			  </div>

            </div>
            	

						<div class="portlet-body">
							<div class="table-container">	
              					<div class="table-actions-wrapper">
									<span>
									</span>
									<select class="table-group-action-input form-control input-inline input-small input-sm">
										  <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?> </option>

										  <option value="3"><?php echo $RecursosCons->RecursosCons['visivel']; ?> </option>
										  <option value="4"><?php echo $RecursosCons->RecursosCons['nao_visivel']; ?> </option>
										  <option value="5"><?php echo $RecursosCons->RecursosCons['opt_destacar']; ?> </option>
										  <option value="6"><?php echo $RecursosCons->RecursosCons['opt_nao_destacar']; ?> </option>
										  <option value="7"><?php echo $RecursosCons->RecursosCons['opt_novidade']; ?> </option>
										  <option value="8"><?php echo $RecursosCons->RecursosCons['opt_nao_novidade']; ?> </option>
										  <option value="-1"><?php echo $RecursosCons->RecursosCons['eliminar']; ?> </option>
									</select>
									<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['submeter']; ?> </button>
                  					<button class="btn btn-sm green" id="bt_submete" type="button"><i class="fa fa-refresh"></i> <?php echo $RecursosCons->RecursosCons['guarda_alt']; ?> </button>
								</div>				
								<table class="table table-striped table-bordered table-hover" id="datatable_products">
								<thead>
								<tr role="row" class="heading">
									<th width="1%"><input type="checkbox" class="group-checkable"></th>
									<th width="10%">Image.</th>
									<th width="10%">Ref.</th>
									<th><?php echo $RecursosCons->RecursosCons['nome']; ?> </th>
									<th>Price</th>
									<th>Stock </th>
									<th>Min Stock </th>
                                   <!--  <th width="8%"><?php echo $RecursosCons->RecursosCons['promocao']; ?> </th> -->
                                    <th width="8%">Markup - Cost </th>
								<!-- 	<th width="8%"><?php echo $RecursosCons->RecursosCons['destaque']; ?> </th>
									<th width="8%"><?php echo $RecursosCons->RecursosCons['novidade']; ?> </th> -->
          				            <th width="8%"><?php echo $RecursosCons->RecursosCons['visivel_tab']; ?> </th>
									<th width="8%"><?php echo $RecursosCons->RecursosCons['acoes']; ?> </th>
								</tr>
								<tr role="row" class="filter">
									<td></td>
									<td></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_ref" onKeyPress="submete(event)"></td>
									<td><input type="text" class="form-control form-filter input-sm" name="form_nome" onKeyPress="submete(event)" style="width:100%; min-width:170px;display:block">
										<select name="form_categoria" class="form-control form-filter input-sm select2me" onchange="document.getElementById('pesquisa').click();" style="margin-top: 5px; width:50%; min-width:170px; display:inline-block">
	                    <option value=""><?php echo $RecursosCons->RecursosCons['opt_todas_cat_label']; ?> </option>
                      <option value="0"><?php echo $RecursosCons->RecursosCons['opt_sem_categorias']; ?></option>
	                    <?php umaCategoriaPorProd(0,"",-1); ?>
		                </select><!-- 
		                 --><select name="form_marca" class="form-control form-filter input-sm select2me" onchange="document.getElementById('pesquisa').click();" style="margin-top: 5px; width:50%; min-width:170px; display:inline-block">
	                    <option value=""><?php echo $RecursosCons->RecursosCons['opt_todas_marcas']; ?></option>
                      <option value="0"><?php echo $RecursosCons->RecursosCons['opt_sem_marcas']; ?></option>
	                    <?php if($totalRows_rsMarcas > 0) { ?>
		                    <?php while($row_rsMarcas = $rsMarcas->fetch()) { ?>
		                      <option value="<?php echo $row_rsMarcas['id']; ?>"><?php echo $row_rsMarcas['nome']; ?></option>
		                    <?php }
		                  } ?>
		                </select>
		                </td>
		                <td></td>
		                <td>
		                	<?php
		                		$stock = $row_rsP_pro['stock'];
		                		$descricao_stock = $row_rsP_pro['descricao_stock'];
		                		if($stock <= $descricao_stock)
		                		{
		                			$minstock = $stock;
		                		}
		                		else
		                		{
		                			$maxstock = $stock;
		                		}
		                	 ?>
							<select name="stock" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
								<option value=""><?php echo $RecursosCons->RecursosCons['pesq_todas']; ?></option>
							
								<option value="<?php echo $maxstock; ?>">Max Stock</option>
								<option value="<?php echo $minstock; ?>">Low Stock</option>
							</select>
              			</td>
		                <td>
							<select name="form_stock" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
								<option value=""><?php echo $RecursosCons->RecursosCons['pesq_todas']; ?></option>
							
								<option value="10">Max</option>
								<option value="9">Min</option>
							</select>
              			</td>
                  <td></td>
               
                  <td><select name="form_visivel" class="form-control form-filter input-sm" onchange="document.getElementById('pesquisa').click();">
                        <option value=""><?php echo $RecursosCons->RecursosCons['pesq_todas']; ?></option>
                        <option value="1"><?php echo $RecursosCons->RecursosCons['visiveis']; ?></option>
                        <option value="0"><?php echo $RecursosCons->RecursosCons['nao_visiveis']; ?></option>
                  </select></td>
									<td><div class="margin-bottom-5">
											<button id="pesquisa" class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> <?php echo $RecursosCons->RecursosCons['pesquisar']; ?></button>
										</div>
										<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
									</td>
								</tr>
								</thead>
								<tbody>
								</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- End: life time stats -->
				</div>
			</div>
			</div>
			<!-- END PAGE CONTENT-->			
		</div>
	</div>
	<!-- END CONTENT -->
    <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>
</div>

<iv class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><b>Import Products</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     	<form action="productos-import.php" method="POST" id="import_products" role="form" enctype="multipart/form-data" class="form-horizontal">
		 	<div class="modal-body">
	 		    <div class="form-group">
	 		    	<?php 	
	 		    		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
					    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
					    $rsLinguas->execute();
					    $row_rsLinguas = $rsLinguas->fetchAll();
					    $totalRows_rsLinguas = $rsLinguas->rowCount();
	 		    	 ?>
  	                <label class="col-md-12 control-label" style="text-align:left; margin-bottom:10px;" for="categoria"> language: <span class="required"> * </span></label>
  	                <div class="col-md-12">
  	                    <select class="form-control select2me" id="categoria" name="linguas">
                    		<option value="all">All</option>
                    		<?php foreach ($row_rsLinguas as $value): ?>
                    			<option value="<?php echo $value['sufixo']; ?>"  ><?php echo strtoupper($value['sufixo']); ?></option>
                    		<?php endforeach ?>
					    </select>
  	                </div>
  	            </div>
			    <div class="actions">
					<div class="file-upload">
						<div class="file-select">
							<div class="file-select-button" id="fileName">Choose File</div>
							<div class="file-select-name" id="noFile">No file chosen...</div> 
							<input type="file" name="csvfile" accept=".csv" id="csvfile">
						</div>
					</div>
				</div>
		  	</div>
		    <div class="modal-footer">
				<button type="submit" name="importSubmit" class="btn btn-primary">IMPORT</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
      	</form>
    </div>
  </div>
</div>


 <div style="display: none;" id="animatedModal" class="animated-modal text-center p-5">

 </div>



<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>
<!-- BEGIN PAGE LEVEL PLUGINS -->

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>


<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
  
<script src="produtos-rpc.js" data-texto1="<?php echo $RecursosCons->RecursosCons['a_carregar']; ?>" data-texto2="<?php echo $RecursosCons->RecursosCons['selec_opcao']; ?>" data-texto3="<?php echo $RecursosCons->RecursosCons['selec_registos']; ?>"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {  
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
  ConteudoDados.init();
});
</script>
<script type="text/javascript">
function submeteDados() {
	var data = table.$('input, select').serialize();
}
function submete(e) {
  if(e.keyCode == 13) {
    document.getElementById('pesquisa').click();
  }
}
function alteraOrdem(e) {
  if(e.keyCode == 13) {
    document.getElementById('bt_submete').click();
  }
}
function alteraCost(e) {
  if(e.keyCode == 13) {
    document.getElementById('bt_submete').click();
  }
}
function alteraStock(e) {
  if(e.keyCode == 13) {
    document.getElementById('bt_submete').click();
  }
}
</script>

<script type="text/javascript">
	
	jQuery(document).ready(function($) {
		$('#csvfile').bind('change', function () {
			  var filename = $("#csvfile").val();
			  if (/^\s*$/.test(filename)) {
			    $(".file-upload").removeClass('active');
			    $("#noFile").text("No file chosen..."); 
			  }
			  else {
			    $(".file-upload").addClass('active');
			    $("#noFile").text(filename.replace("C:\\fakepath\\", "")); 
			  }
			});

	});	

</script>

<script type="text/javascript">
	$(document).on('click', '.popup', function(event) {
	event.preventDefault();

	var data_id = $(this).attr('id');
	$.ajax({
		url: 'product_popup.php',
		type: 'get',
		dataType: 'html',
		data: {data_id: data_id},
	})
	.done(function(html) {
		console.log("success");
		$("#animatedModal").html(html);
		$.fancybox.open($('#animatedModal'));
		console.log(data_id);

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
});
</script>

<script language="JavaScript" type="text/javascript">
function checkDelete(){
    return confirm('Are you sure?');
}
</script>





</body>
<!-- END BODY -->
</html>