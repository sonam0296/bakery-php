<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);
$menu_sel='homepage';
$menu_sub_sel='';


$id = 1;
$query_rsP = "SELECT * FROM homepage".$extensao." WHERE id = :id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);  
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$sid = 2;
$query_rsPs = "SELECT * FROM homepage".$extensao." WHERE id = :sid";
$rsPs = DB::getInstance()->prepare($query_rsPs);
$rsPs->bindParam(':sid', $sid, PDO::PARAM_INT); 
$rsPs->execute();
$row_rsPs = $rsPs->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPs = $rsPs->rowCount();
DB::close();

$tab_sel=1;
 if(isset($_REQUEST['tab_sel']) && $_REQUEST['tab_sel'] != "" && $_REQUEST['tab_sel'] != 0) $tab_sel=$_REQUEST['tab_sel'];

$erro = 0;



if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_homepage")) {
  
  $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
  $rsLinguas->execute();
  $row_rsLinguas = $rsLinguas->fetchAll();

  if($_POST['texto_header'] != '' && $tab_sel == 1){
	 $textid = 1;
    $insertSQL = "UPDATE homepage".$extensao." SET texto_header=:texto_header WHERE id=:textid";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':texto_header', $_POST['texto_header'], PDO::PARAM_STR);  
    $rsInsert->bindParam(':textid', $textid, PDO::PARAM_INT);
    $rsInsert->execute();
  }
  
  if($_POST['texto_form'] != '' && $tab_sel == 1){
	  $textid = 1;
    $insertSQL = "UPDATE homepage".$extensao." SET texto_form=:texto_form WHERE id=:textid";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':texto_form', $_POST['texto_form'], PDO::PARAM_STR);  
    $rsInsert->bindParam(':textid', $textid, PDO::PARAM_INT);
    $rsInsert->execute();
  }
    

  //   if($_POST['home_pft'] != '' || $_POST['home_pfc'] != '' && $tab_sel == 1){
  // $mid = 1;


  //   $insertSQL = "UPDATE homepage".$extensao." SET home_pft=:home_pft, home_pfc=:home_pfc id=:mid";
  //   $rsInsert = DB::getInstance()->prepare($insertSQL);
  //   $rsInsert->bindParam(':home_pft', $_POST['home_pft'], PDO::PARAM_STR, 5); 
  //   $rsInsert->bindParam(':home_pfc', $_POST['home_pfc'], PDO::PARAM_STR, 5); 
  //   $rsInsert->bindParam(':mid', $mid, PDO::PARAM_INT);
  //   $rsInsert->execute();
  // }



  if($_POST['home_pft'] != '' || $_POST['home_pfc'] != '' || $_POST['text_m_1'] != '' || $_POST['text_m_2'] != '' && $tab_sel == 1){
	$mid = 1;


    $insertSQL = "UPDATE homepage".$extensao." SET titulo1=:text_m_1, titulo_link1=:text_m_link_1, titulo2=:text_m_2, titulo_link2=:text_m_link_2, home_pft=:home_pft, home_pfc=:home_pfc, home_cft=:home_cft, home_cfc=:home_cfc WHERE id=:mid";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':text_m_1', $_POST['text_m_1'], PDO::PARAM_STR, 5);	
	  $rsInsert->bindParam(':text_m_2', $_POST['text_m_2'], PDO::PARAM_STR, 5); 
     $rsInsert->bindParam(':text_m_link_1', $_POST['text_m_link_1'], PDO::PARAM_STR, 5);  
    $rsInsert->bindParam(':text_m_link_2', $_POST['text_m_link_2'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':home_pft', $_POST['home_pft'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':home_pfc', $_POST['home_pfc'], PDO::PARAM_STR, 5);

     $rsInsert->bindParam(':home_cft', $_POST['home_cft'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':home_cfc', $_POST['home_cfc'], PDO::PARAM_STR, 5);

	  //$rsInsert->bindParam(':text_m_3', $_POST['text_m_3'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':mid', $mid, PDO::PARAM_INT);
    $rsInsert->execute();
  }
  
  if($tab_sel == 2){

    $opcao = 1;
    $imagem_icon = $row_rsP['icone1'];
    $imagem_icon2 = $row_rsP['icone2'];
    $imagem_icon3 = $row_rsP['icone3'];

	  $tid = 2;
    $insertSQL = "UPDATE homepage".$extensao." SET titulo1=:titulo1, titulo2=:titulo2, titulo3=:titulo3, link1=:link1, link2=:link2, link3=:link3, subtitulo1=:subtitulo1, subtitulo2=:subtitulo2, subtitulo3=:subtitulo3 WHERE id=:tid";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':titulo1', $_POST['titulo1'], PDO::PARAM_STR, 5);	
	  $rsInsert->bindParam(':titulo2', $_POST['titulo2'], PDO::PARAM_STR, 5); 
	  $rsInsert->bindParam(':titulo3', $_POST['titulo3'], PDO::PARAM_STR, 5);

    $rsInsert->bindParam(':link1', $_POST['link1'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':link2', $_POST['link2'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':link3', $_POST['link3'], PDO::PARAM_STR, 5);


	  $rsInsert->bindParam(':subtitulo1', $_POST['subtitulo1'], PDO::PARAM_STR, 5);
	  $rsInsert->bindParam(':subtitulo2', $_POST['subtitulo2'], PDO::PARAM_STR, 5);
	  $rsInsert->bindParam(':subtitulo3', $_POST['subtitulo3'], PDO::PARAM_STR, 5);	
    $rsInsert->bindParam(':tid', $tid, PDO::PARAM_INT);
    $rsInsert->execute();
    

    if(isset($_POST['img_remover1']) && $_POST['img_remover1']==1) {
      if($opcao == 1) {
        $insertSQL = "UPDATE homepage".$extensao." SET icone1=NULL WHERE id=:id";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT); 
        $rsInsert->execute();

        $r = 0;

        //Para todas as línguas e enquanto não encontrar-mos outra categoria com a imagem a ser removida...
        foreach ($row_rsLinguas as $linguas) {
          $query_rsImagem = "SELECT id FROM homepage_".$linguas["sufixo"]." WHERE icone1=:icone1 AND id=:id";
          $rsImagem = DB::getInstance()->prepare($query_rsImagem);
          $rsImagem->bindParam(':icone1', $imagem_icon, PDO::PARAM_STR, 5);
          $rsImagem->bindParam(':id', $tid, PDO::PARAM_INT);
          $rsImagem->execute();
          $totalRows_rsImagem = $rsImagem->rowCount();

          if($totalRows_rsImagem > 0)
            $r = 1;
        }

        //Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la
        if($r == 0)
          @unlink('../../../imgs/homepage/'.$imagem);
      }
      else if($opcao == 2) {
        foreach ($row_rsLinguas as $linguas) {
          $query_rsSelect = "SELECT icone1 FROM homepage_".$linguas['sufixo']." WHERE id=:id";
          $rsSelect = DB::getInstance()->prepare($query_rsSelect);
          $rsSelect->bindParam(':id', $tid, PDO::PARAM_INT);
          $rsSelect->execute();
          $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

          @unlink('../../../imgs/homepage/'.$row_rsSelect['icone1']);

          $insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone1=NULL WHERE id=:id";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT); 
          $rsInsert->execute();
        }
      }

      $rem = 1;
    }

     if(isset($_POST['img_remover2']) && $_POST['img_remover2']==1) {
      if($opcao == 1) {
        $insertSQL = "UPDATE homepage".$extensao." SET icone2=NULL WHERE id=:id";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT); 
        $rsInsert->execute();

        $r = 0;

        //Para todas as línguas e enquanto não encontrar-mos outra categoria com a imagem a ser removida...
        foreach ($row_rsLinguas as $linguas) {
          $query_rsImagem = "SELECT id FROM homepage_".$linguas["sufixo"]." WHERE icone2=:icone2 AND id=:id";
          $rsImagem = DB::getInstance()->prepare($query_rsImagem);
          $rsImagem->bindParam(':icone2', $imagem_icon2, PDO::PARAM_STR, 5);
          $rsImagem->bindParam(':id', $tid, PDO::PARAM_INT);
          $rsImagem->execute();
          $totalRows_rsImagem = $rsImagem->rowCount();

          if($totalRows_rsImagem > 0)
            $r = 1;
        }

        //Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la
        if($r == 0)
          @unlink('../../../imgs/homepage/'.$imagem);
      }
      else if($opcao == 2) {
        foreach ($row_rsLinguas as $linguas) {
          $query_rsSelect = "SELECT icone2 FROM homepage_".$linguas['sufixo']." WHERE id=:id";
          $rsSelect = DB::getInstance()->prepare($query_rsSelect);
          $rsSelect->bindParam(':id', $tid, PDO::PARAM_INT);
          $rsSelect->execute();
          $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

          @unlink('../../../imgs/homepage/'.$row_rsSelect['icone2']);

          $insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone2=NULL WHERE id=:id";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT); 
          $rsInsert->execute();
        }
      }

      $rem = 1;
    }

    if(isset($_POST['img_remover3']) && $_POST['img_remover3']==1) {
      if($opcao == 1) {
        $insertSQL = "UPDATE homepage".$extensao." SET icone3=NULL WHERE id=:id";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT); 
        $rsInsert->execute();

        $r = 0;

        //Para todas as línguas e enquanto não encontrar-mos outra categoria com a imagem a ser removida...
        foreach ($row_rsLinguas as $linguas) {
          $query_rsImagem = "SELECT id FROM homepage_".$linguas["sufixo"]." WHERE icone3=:icone3 AND id=:id";
          $rsImagem = DB::getInstance()->prepare($query_rsImagem);
          $rsImagem->bindParam(':icone3', $imagem2, PDO::PARAM_STR, 5);
          $rsImagem->bindParam(':id', $tid, PDO::PARAM_INT);
          $rsImagem->execute();
          $totalRows_rsImagem = $rsImagem->rowCount();

          if($totalRows_rsImagem > 0)
            $r = 1;
        }

        //Se a variável for igual a 0, significa que a imagem não é usada em mais nenhum registo e podemos removê-la
        if($r == 0)
          @unlink('../../../imgs/homepage/'.$imagem2);
      }
      else if($opcao == 2) {
        foreach ($row_rsLinguas as $linguas) {
          $query_rsSelect = "SELECT icone3 FROM homepage_".$linguas['sufixo']." WHERE id=:id";
          $rsSelect = DB::getInstance()->prepare($query_rsSelect);
          $rsSelect->bindParam(':id', $tid, PDO::PARAM_INT);
          $rsSelect->execute();
          $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

          @unlink('../../../imgs/homepage/'.$row_rsSelect['icone3']);

          $insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone3=NULL WHERE id=:id";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT); 
          $rsInsert->execute();
        }
      }

      $rem = 1;
    }

    if($_FILES['img']['name']!='' || $_FILES['img2']['name']!='' || $_FILES['img3']['name']!='') { // actualiza imagem
      //Verificar o formato do ficheiro
      $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
      $ext2 = strtolower(pathinfo($_FILES['img2']['name'], PATHINFO_EXTENSION));
      $ext3 = strtolower(pathinfo($_FILES['img3']['name'], PATHINFO_EXTENSION));

      if($ext != "jpg" && $ext != "jpeg" && $ext != "gif" && $ext != "png" && $ext != "svg" && $ext2 != "jpg" && $ext2 != "jpeg" && $ext2 != "gif" && $ext2 != "png" && $ext2 != "svg"  && $ext3 != "jpg" && $ext3 != "jpeg" && $ext3 != "gif" && $ext3 != "png" && $ext3 != "svg") {
        $erro = 1;
      }
      else {
        $ins = 1; 
        //require("../resize_image.php");
        
        $imagem=""; 
        
        $imgs_dir = "../../../imgs/homepage";
        $contaimg = 1; 
    
        foreach($_FILES as $file_name => $file_array) {
      
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
          $contaimg++;
                              
        } // fim foreach
        //Fim do Trat. Imagens
          
        //RESIZE DAS IMAGENS
        $imagem = $nome_file1;
        $imagem2 = $nome_file2;
        $imagem3 = $nome_file3;
      
        //IMAGEM 1
        if($_FILES['img']['name']!='') {  
          
          if($row_rsP['icone1']) {
            @unlink('../../../imgs/homepage/'.$row_rsP['icone1']);
          }
          
          //Inserir apenas na língua atual
          if($opcao == 1) {
            $insertSQL = "UPDATE homepage".$extensao." SET icone1=:icone1 WHERE id=:id";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->bindParam(':icone1', $imagem, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':id', $sid, PDO::PARAM_INT);   
            $rsInsert->execute();
          }
          //Inserir para todas as línguas
          else if($opcao == 2) {
            foreach ($row_rsLinguas as $linguas) {    
              $insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone1=:icone1 WHERE id=:id";
              $rsInsert = DB::getInstance()->prepare($insertSQL);
              $rsInsert->bindParam(':icone1', $imagem, PDO::PARAM_STR, 5);
              $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT);   
              $rsInsert->execute();
              
            }
          }
        }
 
        //IMAGEM 2
        if($_FILES['img2']['name']!='') {

          if($row_rsP['icone2']) {
            @unlink('../../../imgs/homepage/'.$row_rsP['icone2']);
          }
          
          //Inserir apenas na língua atual
          if($opcao == 1) {
            $insertSQL = "UPDATE homepage".$extensao." SET icone2=:icone2 WHERE id=:id";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->bindParam(':icone2', $imagem2, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT);   
            $rsInsert->execute();
          }
          //Inserir para todas as línguas
          else if($opcao == 2) {
            foreach ($row_rsLinguas as $linguas) {    
              $insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone2=:icone2 WHERE id=:id";
              $rsInsert = DB::getInstance()->prepare($insertSQL);
              $rsInsert->bindParam(':icone2', $imagem2, PDO::PARAM_STR, 5);
              $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT);   
              $rsInsert->execute();
              
            }
          }
        }

        //IMAGEM 3
        if($_FILES['img3']['name']!='') {
          
          if($row_rsP['icone3']) {
            @unlink('../../../imgs/homepage/'.$row_rsP['icone3']);
          }
          
          //Inserir apenas na língua atual
          if($opcao == 1) {
            $insertSQL = "UPDATE homepage".$extensao." SET icone3=:icone3 WHERE id=:id";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->bindParam(':icone3', $imagem3, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT);   
            $rsInsert->execute();
          }
          //Inserir para todas as línguas
          else if($opcao == 2) {
            foreach ($row_rsLinguas as $linguas) {    
              $insertSQL = "UPDATE homepage_".$linguas["sufixo"]." SET icone3=:icone3 WHERE id=:id";
              $rsInsert = DB::getInstance()->prepare($insertSQL);
              $rsInsert->bindParam(':icone3', $imagem3, PDO::PARAM_STR, 5);
              $rsInsert->bindParam(':id', $tid, PDO::PARAM_INT);   
              $rsInsert->execute();
              
            }
          }
        }

      }
    }

   

  }
 
  DB::close();
      
  header("Location: homepage.php?alt=1&tab_sel=".$tab_sel);
}

/* if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_homepage")) {
	
	$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
	$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
	$rsLinguas->execute();
	$row_rsLinguas = $rsLinguas->fetchAll();
	$totalRows_rsLinguas = $rsLinguas->rowCount();

	if($tab_sel == 1 || $tab_sel == 2) {
		$insertSQL = "UPDATE homepage".$extensao." SET titulo".$tab_sel."=:titulo".$tab_sel.", texto".$tab_sel."=:texto".$tab_sel.", link".$tab_sel."=:link".$tab_sel.", texto_botao".$tab_sel."=:texto_botao".$tab_sel." WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':titulo'.$tab_sel, $_POST['titulo'.$tab_sel], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':texto'.$tab_sel, $_POST['texto'.$tab_sel], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':link'.$tab_sel, $_POST['link'.$tab_sel], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':texto_botao'.$tab_sel, $_POST['texto_botao'.$tab_sel], PDO::PARAM_STR, 5);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
		$rsInsert->execute();

		foreach ($row_rsLinguas as $linguas) {
			$insertSQL = "UPDATE homepage_".$linguas['sufixo']." SET target".$tab_sel."=:target".$tab_sel." WHERE id=:id";
			$rsInsert = DB::getInstance()->prepare($insertSQL);
			$rsInsert->bindParam(':target'.$tab_sel, $_POST['target'.$tab_sel], PDO::PARAM_STR, 5);	
			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
			$rsInsert->execute();
		}

    alteraSessions('homepage');

		DB::close();

		header("Location: homepage.php?alt=1&tab_sel=".$_POST['tab_sel']);			
	}
} */


?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/css/dropzone.css" rel="stylesheet"/>
<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jcrop/css/jquery.Jcrop.css" rel="stylesheet"/>
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
      <h3 class="page-title"> <?php echo "Home Page"; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="homepage.php"><?php echo "Home Page"; ?> </a> <i class="fa fa-angle-right"></i></li>
          <li> <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?> </a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>
          <form id="frm_homepage" name="frm_homepage" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">
            <input type="hidden" name="img_remover1" id="img_remover1" value="0">
            <input type="hidden" name="img_remover2" id="img_remover2" value="0">
	          <div class="portlet">
	            <div class="portlet-title">
	              <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo "Home page"; ?> </div>
	              <div class="form-actions actions btn-set">
	                <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>
	                <button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>
	              </div>
	            </div>
	            <div class="portlet-body">
	              <div class="tabbable">
				  
	                <ul class="nav nav-tabs">
	                  <li <?php if($tab_sel==1) echo "class=\"active\""; ?>> <a href="#text_to_header" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_bloco1']; ?> </a> </li>
	                  <!-- <li <?php // if($tab_sel==2) echo "class=\"active\""; ?>> <a href="#tab_bloco2" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php // echo $RecursosCons->RecursosCons['tab_bloco2']; ?> </a> </li> -->
	                </ul>
					
	                <div class="tab-content no-space">
	                  <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="text_to_header">
                      <div class="form-body">

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="home_pft"><?php echo "Product Feature Title"; ?></label>
                          <div class="col-md-8">
                           <!--  <input type="text" class="form-control" name="text_m_1" id="text_m_1" value="<?php echo $row_rsP['titulo1']; ?>" data-required="1"> -->
                             <input class="form-control" name="home_pft" id="home_pft" value="<?php echo $row_rsP['home_pft']; ?>">
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="home_pfc"><?php echo "Products Feature Content"; ?></label>
                          <div class="col-md-8">
                           <!--  <input type="text" class="form-control" name="text_m_1" id="text_m_1" value="<?php echo $row_rsP['titulo1']; ?>" data-required="1"> -->
                             <textarea class="form-control" name="home_pfc" id="home_pfc" style="resize:none;height:250px" data-required="1"><?php echo $row_rsP['home_pfc']; ?></textarea>
                          </div>
                        </div>




                        <div class="form-group">
                          <label class="col-md-2 control-label" for="home_cft"><?php echo "Category Feature Title"; ?></label>
                          <div class="col-md-8">
                           <!--  <input type="text" class="form-control" name="text_m_1" id="text_m_1" value="<?php echo $row_rsP['titulo1']; ?>" data-required="1"> -->
                             <input class="form-control" name="home_cft" id="home_cft" value="<?php echo $row_rsP['home_cft']; ?>">
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="home_cfc"><?php echo "Category Feature Content"; ?></label>
                          <div class="col-md-8">
                           <!--  <input type="text" class="form-control" name="text_m_1" id="text_m_1" value="<?php echo $row_rsP['titulo1']; ?>" data-required="1"> -->
                             <textarea class="form-control" name="home_cfc" id="home_cfc" style="resize:none;height:250px" data-required="1"><?php echo $row_rsP['home_cfc']; ?></textarea>
                          </div>
                        </div>



                       
                       <!-- <div class="form-group">
                          <label class="col-md-2 control-label" for="texto_header"><?php echo "APOIO AO CLIENTE"; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="texto_header" id="texto_header" value="<?php echo $row_rsP['texto_header']; ?>" data-required="1">
                          </div>
                        </div> -->
						
						<!-- <div class="form-group">
                          <label class="col-md-2 control-label" for="texto_form"><?php echo $RecursosCons->RecursosCons['menu_email']; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="texto_form" id="texto_form" value="<?php echo $row_rsP['texto_form']; ?>" data-required="1">
                          </div>
                        </div> -->
						
						            <div class="form-group">
                          <label class="col-md-2 control-label" for="text_m_1"><?php echo "Text Header Middle 1"; ?></label>
                          <div class="col-md-8">
                           <!--  <input type="text" class="form-control" name="text_m_1" id="text_m_1" value="<?php echo $row_rsP['titulo1']; ?>" data-required="1"> -->
                             <textarea class="form-control" name="text_m_1" id="text_m_1" style="resize:none;height:250px" data-required="1"><?php echo $row_rsP['titulo1']; ?></textarea>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo1">Text Header link 1: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="text_m_link_1" id="text_m_link_1" value="<?php echo $row_rsP['titulo_link1']; ?>">
                          </div>
                        </div>
						
						            <div class="form-group">
                          <label class="col-md-2 control-label" for="text_m_2"><?php echo "Text Header Middle 2"; ?></label>
                          <div class="col-md-8">
                           <!--  <input type="text" class="form-control" name="text_m_2" id="text_m_2" value="<?php echo $row_rsP['titulo2']; ?>" data-required="1"> -->
                             <textarea class="form-control" name="text_m_2" id="text_m_2" style="resize:none;height:250px" data-required="1"><?php echo $row_rsP['titulo2']; ?></textarea>
                          </div>
                        </div>

						             <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo1"><?php echo $RecursosCons->RecursosCons['titulo1']; ?>Text Header link 2: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="text_m_link_2" id="text_m_link_2" value="<?php echo $row_rsP['titulo_link2']; ?>">
                          </div>
                        </div>

						<!-- <div class="form-group">
                          <label class="col-md-2 control-label" for="text_m_3"><?php //echo "Text Header Middle 3"; ?></label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="text_m_3" id="text_m_3" value="<?php echo $row_rsP['titulo3']; ?>" data-required="1">
                          </div>
                        </div> -->
						
						
                      </div>
                  	</div>
					
	                  <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_bloco2">
	                    <div class="form-body">
	                    	<?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 2) { ?>
                        	<div class="alert alert-success display-show">
	                          <button class="close" data-close="alert"></button>
	                        	<?php echo $RecursosCons->RecursosCons['alt_dados']; ?> 
	                       	</div>
                        <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo1"><?php echo $RecursosCons->RecursosCons['titulo_label_icon']; ?> 1: </label>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-<?php if($row_rsPs['icone1']!="" && file_exists("../../../imgs/homepage/".$row_rsPs['icone1'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px; height: 150px;">
                                <?php if($row_rsPs['icone1']!="" && file_exists("../../../imgs/homepage/".$row_rsPs['icone1'])) { ?>
                                <a href="../../../imgs/homepage/<?php echo $row_rsPs['icone1']; ?>" data-fancybox="gallery" ><img src="../../../imgs/homepage/<?php echo $row_rsPs['icone1']; ?>"></a>
                                <?php } ?>
                              </div>
                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                                <input id="upload_campo1" type="file" name="img">
                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover1').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                            </div>
                            <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>
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
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo1"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?> 1: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="titulo1" id="titulo1" value="<?php echo $row_rsPs['titulo1']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="subtitulo1"><?php echo $RecursosCons->RecursosCons['texto_label']; ?> 1: </label>
                          <div class="col-md-8">
                            <textarea class="form-control" name="subtitulo1" id="subtitulo1" ><?php echo $row_rsPs['subtitulo1']; ?></textarea>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="link1">link 1: </label>
                          <div class="col-md-8">
                            <textarea class="form-control" name="link1" id="link1" ><?php echo $row_rsPs['link1']; ?></textarea>
                          </div>
                        </div>

                        <hr>
						  
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo1"><?php echo $RecursosCons->RecursosCons['titulo_label_icon']; ?> 2: </label>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-<?php if($row_rsPs['icone2']!="" && file_exists("../../../imgs/homepage/".$row_rsPs['icone2'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px; height: 150px;">
                                <?php if($row_rsPs['icone2']!="" && file_exists("../../../imgs/homepage/".$row_rsPs['icone2'])) { ?>
                                <a href="../../../imgs/homepage/<?php echo $row_rsPs['icone2']; ?>" data-fancybox="gallery" ><img src="../../../imgs/homepage/<?php echo $row_rsPs['icone2']; ?>"></a>
                                <?php } ?>
                              </div>
                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                                <input id="upload_campo2" type="file" name="img2">
                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover2').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                            </div>
                            <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>
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
                        </div>

						            <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo2"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?> 2: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="titulo2" id="titulo2" value="<?php echo $row_rsPs['titulo2']; ?>">
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="subtitulo2"><?php echo $RecursosCons->RecursosCons['texto_label']; ?> 2: </label>
                          <div class="col-md-8">
                            <textarea class="form-control" name="subtitulo2" id="subtitulo2" ><?php echo $row_rsPs['subtitulo2']; ?></textarea>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="link2">link 2: </label>
                          <div class="col-md-8">
                            <textarea class="form-control" name="link2" id="link2" ><?php echo $row_rsPs['link2']; ?></textarea>
                          </div>
                        </div>

                        <hr>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo1"><?php echo $RecursosCons->RecursosCons['titulo_label_icon']; ?> 3: </label>
                          <div class="col-md-4">
                            <div class="fileinput fileinput-<?php if($row_rsPs['icone3']!="" && file_exists("../../../imgs/homepage/".$row_rsPs['icone3'])) { ?>exists<?php } else { ?>new<?php } ?>" data-provides="fileinput">
                              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> <img src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>imgs/sem_imagem.png" alt=""/> </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px; height: 150px;">
                                <?php if($row_rsPs['icone3']!="" && file_exists("../../../imgs/homepage/".$row_rsPs['icone3'])) { ?>
                                <a href="../../../imgs/homepage/<?php echo $row_rsPs['icone3']; ?>" data-fancybox="gallery" ><img src="../../../imgs/homepage/<?php echo $row_rsPs['icone3']; ?>"></a>
                                <?php } ?>
                              </div>
                              <div> <span class="btn default btn-file"> <span class="fileinput-new"> <?php echo $RecursosCons->RecursosCons['selec_imagem']; ?></span> <span class="fileinput-exists"> <?php echo $RecursosCons->RecursosCons['btn_altera_img']; ?> </span>
                                <input id="upload_campo3" type="file" name="img3">
                                </span> <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput" onClick="document.getElementById('img_remover3').value='1'"> <?php echo $RecursosCons->RecursosCons['btn_remove_img']; ?> </a> </div>
                            </div>
                            <div style="margin-top: 10px;"><span class="label label-danger"><?php echo $RecursosCons->RecursosCons['formatos_sup_txt']; ?></span></div>
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
                        </div>
						
						            <div class="form-group">
                          <label class="col-md-2 control-label" for="titulo3"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?> 3: </label>
                          <div class="col-md-8">
                            <input type="text" class="form-control" name="titulo3" id="titulo1" value="<?php echo $row_rsPs['titulo3']; ?>">
                          </div>
                        </div>


                        <div class="form-group">
                          <label class="col-md-2 control-label" for="subtitulo3"><?php echo $RecursosCons->RecursosCons['texto_label']; ?> 3: </label>
                          <div class="col-md-8">
                            <textarea class="form-control" name="subtitulo3" id="subtitulo3" ><?php echo $row_rsPs['subtitulo3']; ?></textarea>
                          </div>
                        </div>
                     
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="link3">link 3: </label>
                          <div class="col-md-8">
                            <textarea class="form-control" name="link3" id="link3" ><?php echo $row_rsPs['link3']; ?></textarea>
                          </div>
                        </div>
                       
	                    </div>
	                  </div>
	                </div>
	              </div>
	            </div>
	          </div>
	          <input type="hidden" name="MM_insert" value="frm_homepage" />
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
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/fancybox/jquery.fancybox.min.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 
<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>
<!-- BEGIN PAGE LEVEL SCRIPTS --> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/dropzone/dropzone.js"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jcrop/js/jquery.color.js"></script>
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	Form.init();
});
</script> 
<script type="text/javascript">
  // var areas = Array('texto1', 'texto2','text_m_1','text_m_2');
var areas = Array('text_m_1','text_m_2');
$.each(areas, function (i, area) {
 CKEDITOR.replace(area, {
  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',
  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',
  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbar : "Basic2",
  height: "250px"
 });
});
</script> 
</body>
<!-- END BODY -->
</html>
