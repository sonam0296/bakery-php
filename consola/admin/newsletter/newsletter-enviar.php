<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);

include_once('newsletter-funcoes-logs.php');

$menu_sel='newsletter_newsletters';
$menu_sub_sel='';

$id=$_GET['id'];
$inserido=0;
$lista_erro=0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_newsletter_envia")) {
  $query_rsConfig = "SELECT * FROM newsletters_config WHERE id=1";
  $rsConfig = DB::getInstance()->prepare($query_rsConfig);
  $rsConfig->execute();
  $row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsConfig = $rsConfig->rowCount();
  DB::close();

  $query_rsP = "SELECT * FROM newsletters WHERE id=:id";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->bindParam(':id', $id, PDO::PARAM_INT);
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();
  DB::close();

  $nova_lista = 0;

  $tipo_envio = $_POST['tipo_envio'];

	if($_FILES['file_emails']['name'] != '') {
    $imgs_dir = "news";
    $contaimg = 1; 
    
    $ficheiro = "";
    
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
          
      if(is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$file_dir/$nome_file") or die ("Couldn't copy"); }
  
      //store the name plus index as a string 
      $variableName = 'nome_file' . $contaimg; 
      //the double dollar sign is saying assign $imageName 
      // to the variable that has the name that is in $variableName
      $$variableName = $nome_file;  
      $contaimg++;
                          
    } // fim foreach
    
    $ficheiro = $nome_file1;

    if($ficheiro != '') {
      //Criar nova lista (apenas visível nesta newsletter) 
      $query_rsMaxID = "SELECT MAX(id) FROM news_listas";
      $rsMaxID = DB::getInstance()->query($query_rsMaxID);
      $rsMaxID->execute();
      $row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsMaxID = $rsMaxID->rowCount();
      DB::close();

      $nova_lista=$row_rsMaxID['MAX(id)'] + 1;
      $nome_lista = $row_rsP['titulo']." - Lista ".date('Y-m-d H:i:s');

      $query_rsInsert = "INSERT INTO news_listas (id, nome, visivel, newsletter) VALUES ('$nova_lista', '".$nome_lista."', 0, '".$id."')";
      $rsInsert = DB::getInstance()->prepare($query_rsInsert);
      $rsInsert->execute();
      DB::close();


      include '../Classes/PHPExcel/IOFactory.php';

      try {
        $inputFileType = PHPExcel_IOFactory::identify("news/".$ficheiro);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load("news/".$ficheiro);
      } 
      catch(Exception $e) {
        die('Error loading file "'.pathinfo("news/".$ficheiro,PATHINFO_BASENAME).'": '.$e->getMessage());
      }

      $sheet = $objPHPExcel->getSheet(0); 
      $highestRow = $sheet->getHighestRow(); 
      $highestColumn = $sheet->getHighestColumn();

      $rowData = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);
      
      for($row = 2; $row <= $highestRow; $row++) { 
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

        $email = trim(utf8_decode($rowData['0']['0']));
        $nome = trim(utf8_decode($rowData['0']['1']));
        $empresa = trim(utf8_decode($rowData['0']['2']));
        $cargo = trim(utf8_decode($rowData['0']['3']));
        $telefone = trim(utf8_decode($rowData['0']['4']));

        if($email != NULL && $email != '') {
          $query_rsMailer = "SELECT * FROM news_emails WHERE email=:email";
          $rsMailer = DB::getInstance()->prepare($query_rsMailer);
          $rsMailer->bindParam(':email', $email, PDO::PARAM_STR, 5);
          $rsMailer->execute();
          $row_rsMailer = $rsMailer->fetch(PDO::FETCH_ASSOC);
          $totalRows_rsMailer = $rsMailer->rowCount();
          DB::close();
           
          if($totalRows_rsMailer==0) {
            $query_rsMaxID = "SELECT MAX(id) FROM news_emails";
            $rsMaxID = DB::getInstance()->query($query_rsMaxID);
            $rsMaxID->execute();
            $row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);
            $totalRows_rsMaxID = $rsMaxID->rowCount();
            DB::close();

            $max_id = $row_rsMaxID['MAX(id)'] + 1;
            $data = date('Y-m-d');
            $codigo = randomCodeNews();

            $query_rsInsert = "INSERT INTO news_emails (id, empresa, nome, cargo, email, telefone, data, visivel, codigo, aceita, origem) VALUES ('$max_id', :empresa, :nome, :cargo, :email, :telefone, :data, '1', :codigo, '1', 'Importação emails')";
            $rsInsert = DB::getInstance()->prepare($query_rsInsert);
            $rsInsert->bindParam(':empresa', $empresa, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':cargo', $cargo, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':email', $email, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':telefone', $telefone, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':codigo', $codigo, PDO::PARAM_STR, 5);
            $rsInsert->execute();  

            $query_rsInsert = "INSERT INTO news_emails_listas (email, lista) VALUES ('".$max_id."', '".$nova_lista."')";
            $rsInsert = DB::getInstance()->prepare($query_rsInsert);
            $rsInsert->execute();
            DB::close();    
          }
          else {
            $email_id = $row_rsMailer['id'];
 
            $query_rsInsert = "UPDATE news_emails SET empresa=:empresa, nome=:nome, cargo=:cargo, telefone=:telefone, aceita='1', origem = 'Importação emails' WHERE id='$email_id'";
            $rsInsert = DB::getInstance()->prepare($query_rsInsert);
            $rsInsert->bindParam(':empresa', $empresa, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':cargo', $cargo, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':telefone', $telefone, PDO::PARAM_STR, 5);
            $rsInsert->execute();
            DB::close();
            
            $query_rsInsert = "INSERT INTO news_emails_listas (email, lista) VALUES ('".$email_id."', '".$nova_lista."')";
            $rsInsert = DB::getInstance()->prepare($query_rsInsert);
            $rsInsert->execute();
            DB::close();  
          }
        }
      }

      @unlink("news/".$ficheiro);
    }
  }

  if((sizeof($_POST['listas']) > 0 || $nova_lista > 0) && $_POST['grupo'] != '' && ($tipo_envio == 1 || ($tipo_envio == 2 && $_POST['mailgun_key'] != "" && $_POST['mailgun_dominio'] != ""))) {	
		$nome_from=$_POST['nome_from'];
    $email_from=$_POST['email_from'];
		$email_reply=$_POST['email_reply'];
    $email_bounce=$_POST['email_bounce'];
		
		if(!$nome_from) $nome_from=$row_rsConfig['nome_from'];
    if(!$email_from) $email_from=$row_rsConfig['email_from'];
		if(!$email_reply) $email_reply=$row_rsConfig['email_reply'];
    if(!$email_bounce) $email_bounce=$row_rsConfig['email_bounce'];
	
		$insertSQL = "UPDATE newsletters SET nome_from=:nome_from, email_from=:email_from, email_reply=:email_reply, email_bounce=:email_bounce, tipo_envio=:tipo_envio, mailgun_key=:mailgun_key, mailgun_dominio=:mailgun_dominio WHERE id=:id";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':nome_from', $nome_from, PDO::PARAM_STR, 5); 
		$rsInsert->bindParam(':email_from', $email_from, PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':email_reply', $email_reply, PDO::PARAM_STR, 5);	
    $rsInsert->bindParam(':email_bounce', $email_bounce, PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':tipo_envio', $tipo_envio, PDO::PARAM_INT);  
    $rsInsert->bindParam(':mailgun_key', $_POST['mailgun_key'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':mailgun_dominio', $_POST['mailgun_dominio'], PDO::PARAM_STR, 5);    
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
		$rsInsert->execute();
		DB::close();
			
		//busca o id maximo para saber qual a newsletter e insere o registo			
		$query_rsMaxID = "SELECT MAX(id) FROM newsletters_historico";
		$rsMaxID = DB::getInstance()->prepare($query_rsMaxID);
		$rsMaxID->execute();
		$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);
		$totalRows_rsMaxID = $rsMaxID->rowCount();
		DB::close();
		
		$id_max=$row_rsMaxID['MAX(id)']+1;
		
		$nome_utilizador=$row_rsUser["username"];
		
    $limite = $_POST['limite'];

    if($limite < 0) {
      $limite = 0;
    }elseif($tipo_envio == 2 && $limite > 500){ //limita a 500 para envio através do Mailgun
      $limite = 500;
    }elseif($limite > $row_rsConfig['max_emails']){
      $limite = $row_rsConfig['max_emails'];
    }

		$insertSQL = "INSERT INTO newsletters_historico (id, newsletter_id, grupo, utilizador, limite, data, hora, estado, nome_from, email_from, email_reply, email_bounce, tipo_envio, mailgun_key, mailgun_dominio) VALUES ('$id_max', :id, :grupo, :utilizador, :limite, :data, :hora, '1', :nome_from, :email_from, :email_reply, :email_bounce, :tipo_envio, :mailgun_key, :mailgun_dominio)";
		$rsInsert = DB::getInstance()->prepare($insertSQL);
		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	
    $rsInsert->bindParam(':grupo', $_POST['grupo'], PDO::PARAM_INT); 
		$rsInsert->bindParam(':utilizador', $nome_utilizador, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':limite', $limite, PDO::PARAM_INT); 
		$rsInsert->bindParam(':data', $_POST["data"], PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':hora', $_POST["hora"], PDO::PARAM_STR, 5);	
    $rsInsert->bindParam(':nome_from', $nome_from, PDO::PARAM_STR, 5); 
		$rsInsert->bindParam(':email_from', $email_from, PDO::PARAM_STR, 5);	
		$rsInsert->bindParam(':email_reply', $email_reply, PDO::PARAM_STR, 5);	
    $rsInsert->bindParam(':email_bounce', $email_bounce, PDO::PARAM_STR, 5);
    $rsInsert->bindParam(':tipo_envio', $tipo_envio, PDO::PARAM_INT);  
    $rsInsert->bindParam(':mailgun_key', $_POST['mailgun_key'], PDO::PARAM_STR, 5); 
    $rsInsert->bindParam(':mailgun_dominio', $_POST['mailgun_dominio'], PDO::PARAM_STR, 5);
		$rsInsert->execute();
		DB::close();
		
		$lista_nomes="";
		$array_lista="(";

    if(sizeof($_POST['listas']) > 0) {
			foreach($_POST['listas'] as $lista) {
				
				if($array_lista=="("){
					$array_lista.=$lista;
				}else{
					$array_lista.=", ".$lista;
				}
				
				$insertSQL = "INSERT INTO newsletters_historico_listas (newsletter_historico, lista, newsletter_id) VALUES ('$id_max', :lista, :id)";
				$rsInsert = DB::getInstance()->prepare($insertSQL);	
				$rsInsert->bindParam(':lista', $lista, PDO::PARAM_INT);
				$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
				$rsInsert->execute();
				DB::close();
				
				//passa os nomes todos das listas para inserir nos logs
				$query_rsEmaLista = "SELECT * FROM news_listas WHERE id=:lista";
				$rsEmaLista = DB::getInstance()->prepare($query_rsEmaLista);
				$rsEmaLista->bindParam(':lista', $lista, PDO::PARAM_INT);
				$rsEmaLista->execute();
				$row_rsEmaLista = $rsEmaLista->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsEmaLista = $rsEmaLista->rowCount();
				DB::close();		
				
				$lista_nomes.=$row_rsEmaLista['nome'].", ";
			}
    }

    if($nova_lista > 0) {
      if($array_lista=="("){
        $array_lista.=$nova_lista;
      }else{
        $array_lista.=", ".$nova_lista;
      }

      $insertSQL = "INSERT INTO newsletters_historico_listas (newsletter_historico, lista, newsletter_id) VALUES ('$id_max', :lista, :id)";
      $rsInsert = DB::getInstance()->prepare($insertSQL); 
      $rsInsert->bindParam(':lista', $nova_lista, PDO::PARAM_INT);
      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);
      $rsInsert->execute();
      DB::close();
      
      //passa os nomes todos das listas para inserir nos logs
      $query_rsEmaLista = "SELECT * FROM news_listas WHERE id=:lista";
      $rsEmaLista = DB::getInstance()->prepare($query_rsEmaLista);
      $rsEmaLista->bindParam(':lista', $nova_lista, PDO::PARAM_INT);
      $rsEmaLista->execute();
      $row_rsEmaLista = $rsEmaLista->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsEmaLista = $rsEmaLista->rowCount();
      DB::close();    
      
      $lista_nomes.=$row_rsEmaLista['nome'].", ";
    }

		$array_lista.=")";
	
		if($array_lista!="()") {
			//actualiza a newsletter com o total de emails enviados
			
			$query_rsTotEmails = "SELECT news_emails.email FROM news_emails, news_emails_listas WHERE news_emails.visivel='1' AND news_emails.aceita='1' AND news_emails_listas.email=news_emails.id AND news_emails_listas.lista IN $array_lista GROUP BY news_emails.email";
			$rsTotEmails = DB::getInstance()->prepare($query_rsTotEmails);
			$rsTotEmails->execute();
			$row_rsTotEmails = $rsTotEmails->fetch(PDO::FETCH_ASSOC);
			$totalRows_rsTotEmails = $rsTotEmails->rowCount();
			DB::close();	
			
			$emails_total=$totalRows_rsTotEmails;
			
			$query_rsActualiza = "UPDATE newsletters_historico SET emails_total = '$emails_total' WHERE id = '$id_max'";
			$rsActualiza = DB::getInstance()->prepare($query_rsActualiza);
			$rsActualiza->execute();
			DB::close();
			
			//REGISTA O HISTÓRICO
			if($lista_nomes!=''){
				$lista_nomes=substr($lista_nomes,0,count($lista_nomes)-3);
			}
			
			$que_fez="criou agendamento para ".$_POST['data']." // ".$_POST['hora'];
			$class_news_logs->logs_agendamentos($nome_utilizador, $id, $que_fez, $row_rsP['titulo'], $lista_nomes);
		}
		
		$inserido = 1;
	} 
  else 
    $lista_erro = 1;
}

$query_rsP = "SELECT * FROM newsletters WHERE id=:id";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->bindParam(':id', $id, PDO::PARAM_INT);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$nome_from = $row_rsP['nome_from'];
$email_from = $row_rsP['email_from'];
$email_reply = $row_rsP['email_reply'];
$email_bounce = $row_rsP['email_bounce'];

$query_rsConfig = "SELECT * FROM newsletters_config WHERE id=1";
$rsConfig = DB::getInstance()->prepare($query_rsConfig);
$rsConfig->execute();
$row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
$totalRows_rsConfig = $rsConfig->rowCount();
DB::close();

if(!$nome_from) $nome_from = $row_rsConfig['nome_from'];
if(!$email_from) $email_from = $row_rsConfig['email_from'];
if(!$email_reply) $email_reply = $row_rsConfig['email_reply'];
if(!$email_bounce) $email_bounce = $row_rsConfig['email_bounce'];

// conteúdos
$query_rsContNews = "SELECT * FROM news_conteudo WHERE id=:id";
$rsContNews = DB::getInstance()->prepare($query_rsContNews);
$rsContNews->bindParam(':id', $row_rsP['conteudo'], PDO::PARAM_INT);
$rsContNews->execute();
$totalRows_rsContNews = $rsContNews->rowCount();
DB::close();

// listas de correio enviadas
// $query_rsListas = "SELECT * FROM news_listas WHERE id IN (SELECT lista FROM newsletters_historico_listas WHERE newsletter_id=:id) ORDER BY news_listas.ordem ASC";
// $rsListas = DB::getInstance()->prepare($query_rsListas);
// $rsListas->bindParam(':id', $id, PDO::PARAM_INT);
// $rsListas->execute();
// $totalRows_rsListas = $rsListas->rowCount();
// DB::close();

// listas de correio por enviar
$query_rsListas2 = "SELECT * FROM news_listas WHERE id NOT IN (SELECT lista FROM newsletters_historico_listas WHERE newsletter_id=:id) ORDER BY news_listas.ordem ASC";
$rsListas2 = DB::getInstance()->prepare($query_rsListas2);
$rsListas2->bindParam(':id', $id, PDO::PARAM_INT);
$rsListas2->execute();
$totalRows_rsListas2 = $rsListas2->rowCount();
DB::close();

$query_rsTipos = "SELECT * FROM news_tipos_pt ORDER BY nome ASC";
$rsTipos = DB::getInstance()->prepare($query_rsTipos);
$rsTipos->execute();
$totalRows_rsTipos = $rsTipos->rowCount();
DB::close();

$query_rsGrupos = "SELECT * FROM news_grupos ORDER BY nome ASC";
$rsGrupos = DB::getInstance()->prepare($query_rsGrupos);
$rsGrupos->execute();
$totalRows_rsGrupos = $rsGrupos->rowCount();
DB::close();

?>
<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
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
      <h3 class="page-title"> <?php echo $row_rsP["titulo"]; ?> <small>agendar</small> </h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li> <i class="fa fa-home"></i> <a href="../index.php">Home</a> <i class="fa fa-angle-right"></i> </li>
          <li> <a href="newsletter.php">Newsletters</a> </li>
        </ul>
      </div>
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          <form id="frm_newsletter_envia" name="frm_newsletter_envia" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="manter" id="manter" value="0">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletters - Agendar</div>
                <div class="form-actions actions btn-set">
                  <button type="button" name="back" class="btn default" onClick="document.location='newsletter.php'"><i class="fa fa-angle-left"></i> Voltar</button>
                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                  <button type="submit" class="btn green"><i class="fa fa-check"></i> Guardar</button> 
                </div>
              </div>
              <div class="portlet-body">
                <div class="tabbable">
                  <ul class="nav nav-tabs">
                    <li> <a href="#tab_general" data-toggle="tab" onClick="document.location='newsletter-detalhe.php?id=<?php echo $_GET['id']; ?>'"> Detalhe </a> </li>
                    <li class="active"> <a href="#tab_agendar" data-toggle="tab"> Agendar </a> </li>
                    <li> <a href="#tab_general" data-toggle="tab" onClick="document.location='newsletter-historico.php?id=<?php echo $_GET['id']; ?>'"> Agendamentos </a> </li>
                    <li> <a href="#tab_envio_teste" data-toggle="tab" onClick="document.location='newsletter-enviar-teste.php?id=<?php echo $_GET['id']; ?>'"> Envio teste </a> </li>
                  </ul>
                  <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_agendar">
                      <div class="form-body">
                        <?php if($inserido == 1) { ?>
                          <div class="alert alert-success display-show">
                            <button class="close" data-close="alert"></button>
                            <span> Agendamento criado com sucesso. </span> 
                          </div>
                        <?php } ?>
                        <?php if($totalRows_rsContNews == 0) { ?>
                          <div class="alert alert-danger display-show">
                            <button class="close" data-close="alert"></button>
                            Esta newsletter não tem conteúdo associado. 
                          </div>
                        <?php }?>
                        <div class="alert alert-danger display-hide">
                          <button class="close" data-close="alert"></button>
                          Preencha todos os campos obrigatórios. 
                        </div>
                        <?php if($row_rsConfig["mostra_tipo"] == 1) { ?>
                          <div class="form-group">
                            <label class="col-md-2 control-label">Plataforma de envio: </label>
                            <div class="col-md-8">
                              <div class="md-radio-inline">
                                <div class="md-radio">
                                  <input type="radio" id="tipo_envio_1" name="tipo_envio" class="md-radiobtn" value="1" <?php if($row_rsP["tipo_envio"] == 1) echo "checked"; ?> onclick="verificaTipo(this.value);">
                                  <label for="tipo_envio_1"> <span></span> <span class="check"></span> <span class="box"></span> Interno </label>
                                </div>
                                <div class="md-radio">
                                  <input type="radio" id="tipo_envio_2" name="tipo_envio" class="md-radiobtn" value="2" <?php if($row_rsP["tipo_envio"] == 2) echo "checked"; ?> onclick="verificaTipo(this.value);">
                                  <label for="tipo_envio_2"> <span></span> <span class="check"></span> <span class="box"></span> Mailgun </label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div id="div_mailgun" style="<?php if($row_rsP["tipo_envio"] == 1) { ?> display: none; <?php } ?>">
                            <div class="form-group">
                              <label class="col-md-2 control-label" for="mailgun_key">Chave API: <span class="required"> * </span></label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="mailgun_key" id="mailgun_key" value="<?php echo $row_rsP['mailgun_key']; ?>" data-required="1">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-md-2 control-label" for="mailgun_dominio">Domínio: <span class="required"> * </span></label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="mailgun_dominio" id="mailgun_dominio" value="<?php echo $row_rsP['mailgun_dominio']; ?>" data-required="1">
                              </div>
                            </div>
                          </div>
                          <?php } 
                          else { ?>
                            <input type="hidden" id="tipo_envio" name="tipo_envio" value="1">
                          <?php } ?>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="nome_from">Nome <em>De</em> : </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="nome_from" id="nome_from" value="<?php echo $nome_from; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="email_from">Email <em>De</em> : </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="email_from" id="email_from" value="<?php echo $email_from; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="email_reply">Email <em>Responder</em> : </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="email_reply" id="email_reply" value="<?php echo $email_reply; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="email_bounce">Email <em>Bounce To</em> : </label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="email_bounce" id="email_bounce" value="<?php echo $email_bounce; ?>">
                            <p class="help-block">Se houver falhas no envio das newsletters, as notificações são enviadas para este email.</p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="limite">Limite por hora: </label>
                          <div class="col-md-3">
                            <input type="number" class="form-control" name="limite" id="limite" value="0" min="0">
                            <p class="help-block">Deixe o valor a 0 se não quiser impor um limite</p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="data">Agendar envio: <span class="required"> * </span> </label>
                          <div class="col-md-3">
                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                              <input type="text" class="form-control form-filter input-sm" name="data" placeholder="Data" id="data" value="<?php echo $_POST['data']; ?>" data-required="1" style="height:34px;">
                              <span class="input-group-btn">
                              <button class="btn btn-sm default" type="button" style="height:34px;"><i class="fa fa-calendar"></i></button>
                              </span> 
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="input-group">
                              <select class="form-control select2me" name="hora" id="hora">
                                <option value="00:00:00">0</option>
                                <option value="01:00:00">1</option>
                                <option value="02:00:00">2</option>
                                <option value="03:00:00">3</option>
                                <option value="04:00:00">4</option>
                                <option value="05:00:00">5</option>
                                <option value="06:00:00">6</option>
                                <option value="07:00:00">7</option>
                                <option value="08:00:00">8</option>
                                <option value="09:00:00">9</option>
                                <option value="10:00:00">10</option>
                                <option value="11:00:00">11</option>
                                <option value="12:00:00">12</option>
                                <option value="13:00:00">13</option>
                                <option value="14:00:00">14</option>
                                <option value="15:00:00">15</option>
                                <option value="16:00:00">16</option>
                                <option value="17:00:00">17</option>
                                <option value="18:00:00">18</option>
                                <option value="19:00:00">19</option>
                                <option value="20:00:00">20</option>
                                <option value="21:00:00">21</option>
                                <option value="22:00:00">22</option>
                                <option value="23:00:00">23</option>
                              </select>
                              <span class="input-group-addon" style="min-width:0 !important"><i class="fa fa-clock-o"></i></span> </div>
                          </div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="grupo">Tipo de Cliente: <span class="required">*</span></label>
                          <div class="col-md-6">
                            <select class="form-control select2me" name="grupo" id="grupo">
                              <option value="">Selecionar...</option>
                              <option value="0">Todos</option>
                              <?php if($totalRows_rsGrupos > 0) {
                                while($row_rsGrupos = $rsGrupos->fetch()) { ?>
                                  <option value="<?php echo $row_rsGrupos['id']; ?>"><?php echo $row_rsGrupos['nome']; ?></option>
                                <?php }
                              } ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="file_emails" style="text-align:right">Ficheiro com Emails (.xlsx): </label>
                          <div class="col-md-8">
                            <div class="fileinput fileinput-new" data-provides="fileinput"> <span class="btn default btn-file btn-sm"> <span class="fileinput-new"> Selecione ficheiro </span> <span class="fileinput-exists"> Alterar </span>
                              <input type="file" name="file_emails" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                              </span> <span class="fileinput-filename"> </span> &nbsp; <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a> </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="email_reply">Listas de correio por enviar:</label>
                          <div class="col-md-6">
                            <div class="form-control height-auto">
                              <div class="scroller" style="height: 300px;" data-always-visible="1">
                                <ul class="list-unstyled">
                                  <?php if($totalRows_rsListas2 > 0) { ?>
                                    <?php while($row_rsListas2 = $rsListas2->fetch()) { ?>
                                      <li>
                                        <label>
                                          <input type="checkbox" name="listas[]" value="<?php echo $row_rsListas2['id']; ?>">
                                          <?php echo $row_rsListas2['nome']; ?></label>
                                      </li>
                                    <?php } ?>
                                  <?php } ?>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php /*<div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-3 control-label" for="email_reply">Listas de correio já enviadas:</label>
                            <div class="col-md-9">
                              <div class="form-control height-auto">
                                <div class="scroller" style="height:150px;" data-always-visible="1">
                                  <ul class="list-unstyled">
                                    <?php if($totalRows_rsListas >0){ ?>
                                    <?php while($row_rsListas = $rsListas->fetch()) { ?>
                                    <li>
                                      <label>
                                        <input type="checkbox" name="listas[]" value="<?php echo $row_rsListas['id']; ?>">
                                        <?php echo $row_rsListas['nome']; ?></label>
                                    </li>
                                    <?php } ?>
                                    <?php } ?>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-3 control-label" for="email_reply">Listas de correio por enviar:</label>
                            <div class="col-md-9">
                              <div class="form-control height-auto">
                                <div class="scroller" style="height:150px;" data-always-visible="1">
                                  <ul class="list-unstyled">
                                    <?php if($totalRows_rsListas2 >0){ ?>
                                    <?php while($row_rsListas2 = $rsListas2->fetch()) { ?>
                                    <li>
                                      <label>
                                        <input type="checkbox" name="listas[]" value="<?php echo $row_rsListas2['id']; ?>">
                                        <?php echo $row_rsListas2['nome']; ?></label>
                                    </li>
                                    <?php } ?>
                                    <?php } ?>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-3 control-label" for="file_emails" style="text-align:right">Ficheiro com Emails (.xlsx): </label>
                            <div class="col-md-8">
                              <div class="fileinput fileinput-new" data-provides="fileinput"> <span class="btn default btn-file btn-sm"> <span class="fileinput-new"> Selecione ficheiro </span> <span class="fileinput-exists"> Alterar </span>
                                <input type="file" name="file_emails" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                </span> <span class="fileinput-filename"> </span> &nbsp; <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a> </div>
                            </div>
                          </div>
                        </div> */ ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="MM_insert" value="frm_newsletter_envia" />
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
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
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
  FormValidation.init();
});

function verificaTipo(id) {
  if(id == 2) $("#div_mailgun").fadeIn();
  else $("#div_mailgun").fadeOut();
}
</script>
</body>
<!-- END BODY -->
</html>