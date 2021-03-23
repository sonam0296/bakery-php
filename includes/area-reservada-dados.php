<?php require_once('Connections/connADMIN.php'); ?>
<?php

$query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$title = $row_rsMeta["title"];
$description = $row_rsMeta["description"];
$keywords = $row_rsMeta["keywords"];

if($row_rsCliente == 0) {
  header("Location: login.php");  
  exit;
} 

$id_cliente = $row_rsCliente['id'];

$existe_user1 = 0;
$existe_user2 = 0;

$form_dados = $csrf->form_names(array('nome', 'email', 'telefone', 'telemovel', 'nif', 'act_outro', 'atividade', 'pessoa_contacto', 'data_nasc'), false);
if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_dados")) {
  if($_POST['form_hidden'] == "") {
    if($csrf->check_valid('post')) { 
      if($_POST[$form_dados['nome']] != "" && $_POST[$form_dados['email']] != "") {
        $email = $_POST[$form_dados['email']];
        
        $query_rsEmail = "SELECT id FROM clientes WHERE email=:email AND id!='$id_cliente'";
        $rsEmail = DB::getInstance()->prepare($query_rsEmail);
        $rsEmail->bindParam(':email', $email, PDO::PARAM_STR, 5); 
        $rsEmail->execute();
        $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsEmail = $rsEmail->rowCount();
        
        // $query_rsNif = "SELECT id FROM clientes WHERE nif=:nif AND nif!='' AND id!='$id_cliente'";
        // $rsNif = DB::getInstance()->prepare($query_rsNif);
        // $rsNif->bindParam(':nif', $_POST[$form_dados['nif']], PDO::PARAM_INT);  
        // $rsNif->execute();
        // $row_rsNif = $rsNif->fetch(PDO::FETCH_ASSOC);
        // $totalRows_rsNif = $rsNif->rowCount();
        
        if($totalRows_rsEmail > 0) {
          $existe_user1 = 1;
        }
        else if($totalRows_rsNif > 0) {
          $existe_user2 = 1;
        }
        else{
          $nome = $_POST[$form_dados['nome']];
          $empresa = $_POST[$form_dados['empresa']];

          $telefone = $_POST[$form_dados['telefone']];
          $telemovel = $_POST[$form_dados['telemovel']];
          $nif = $_POST[$form_dados['nif']];
          $atividade = $_POST[$form_dados['atividade']];
          $atividade2 = $_POST[$form_dados['act_outro']];
          $pessoa = $_POST[$form_dados['pessoa_contacto']];
          $data_nasc = $_POST[$form_dados['data_nasc']];

          $insertSQL = "UPDATE clientes SET nome=:nome, empresa=:empresa, pessoa=:pessoa, nif=:nif, telefone=:telefone, telemovel=:telemovel, email=:email, atividade=:atividade, atividade2=:atividade2, data_nasc=:data_nasc WHERE id='$id_cliente'";
          $rsInsert = DB::getInstance()->prepare($insertSQL);
          $rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);  
          $rsInsert->bindParam(':email', $email, PDO::PARAM_STR, 5);    
          $rsInsert->bindParam(':empresa', $empresa, PDO::PARAM_STR, 5);
          $rsInsert->bindParam(':pessoa', $pessoa, PDO::PARAM_STR, 5);
          $rsInsert->bindParam(':nif', $nif, PDO::PARAM_INT);
          $rsInsert->bindParam(':telefone', $telefone, PDO::PARAM_STR, 5);
          $rsInsert->bindParam(':telemovel', $telemovel, PDO::PARAM_STR, 5);     
          $rsInsert->bindParam(':atividade', $atividade, PDO::PARAM_STR, 5);      
          $rsInsert->bindParam(':atividade2', $atividade2, PDO::PARAM_STR, 5);
          $rsInsert->bindParam(':data_nasc', $data_nasc, PDO::PARAM_STR, 5);   
          $rsInsert->execute();

          header("Location: area-reservada-dados.php?alterado=1");
          exit();
        }
      }
    }
  }
}

$form_pass = $csrf->form_names(array('password', 'password2'), false);
if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_pass")) {
  if($_POST['form_hidden'] == "") {
    if($csrf->check_valid('post')) {      
      if($_POST[$form_pass['password']] != "" && $_POST[$form_pass['password2']] != "" && ($_POST[$form_pass['password']] == $_POST[$form_pass['password2']])) {
        $password = $_POST[$form_pass['password']];

        $salt = $class_user->createSalt();        
        $hash = hash('sha256', $password);

        $password_final = hash('sha256', $salt . $hash);

        $insertSQL = "UPDATE clientes SET password=:password, password_salt=:salt WHERE id='$id_cliente'";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':password', $password_final, PDO::PARAM_STR, 5);  
        $rsInsert->bindParam(':salt', $salt, PDO::PARAM_STR, 5);  
        $rsInsert->execute();

        setcookie("SITE_pass", $password_final, 0, '/', '', $cookie_secure, true); 

        header("Location: area-reservada-dados.php?alterado=1");
        exit();
      }
    }
  }  
}

$form_elimina = $csrf->form_names(array('descricao'), false);
if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_elimina")) {
  if($_POST['form_hidden'] == "") {
    if($csrf->check_valid('post')) {      
      if($_POST[$form_elimina['descricao']] != "") {
        $formcontent = getHTMLTemplate("contacto.htm");
        $rodape = email_social();

        $descricao = $_POST[$form_elimina['descricao']];
        $data = date("Y-m-d H:i:s");

        $query_rsMaxId = "SELECT MAX(id) FROM clientes_remocao";
        $rsMaxId = DB::getInstance()->prepare($query_rsMaxId);
        $rsMaxId->execute();
        $row_rsMaxId = $rsMaxId->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsMaxId = $rsMaxId->rowCount();

        $id_max = $row_rsMaxId["MAX(id)"] + 1;
        
        $insertSQL = "INSERT INTO clientes_remocao (id, id_cliente, email, descricao, data_pedido) VALUES (:id, '$id_cliente', '".$row_rsCliente['email']."', :descricao, '$data')";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':id', $id_max, PDO::PARAM_INT);
        $rsInsert->bindParam(':descricao', $descricao, PDO::PARAM_STR, 5);
        $rsInsert->execute();
        
        $mensagem_final = '
        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
          <tr>
            <td style="font-family:arial; font-size:16px; line-height:22px; color:#575756; font-weight:bold"><strong>'.$Recursos->Resources["dados_contacto"].'</strong></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="1" cellspacing="0">
          <tr>
            <td height="20">&nbsp;</td>
            <td align="left" valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["mail"].':</strong></td>
            <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$row_rsCliente['email'].'</td>
          </tr>
          <tr>
            <td align="left" valign="top" width="130" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;"><strong>'.$Recursos->Resources["eliminar_conta_desc"].':</strong></td>
            <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$descricao.'</td>
          </tr>
        </table>';
        
        $posicao = strpos($mensagem, "<a");
        $posicao2 = strpos($mensagem, "#");
        
        if($posicao === false && $posicao2 === false) {    
          $query_rsNotificacoes = "SELECT * FROM notificacoes".$extensao." WHERE id = 9";
          $rsNotificacoes = DB::getInstance()->prepare($query_rsNotificacoes);
          $rsNotificacoes->execute();
          $row_rsNotificacoes = $rsNotificacoes->fetch(PDO::FETCH_ASSOC);
          $totalRows_rsNotificacoes = $rsNotificacoes->rowCount();
          
          $titulo = $row_rsNotificacoes['assunto'];
          $subject = $row_rsNotificacoes['assunto'];
          
          $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);  
          $pagina_form = $_POST['titulo_pag']."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."'>http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."</a>";  
          
          $formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);
          $formcontent = str_replace ("#crodape#", $rodape, $formcontent);
          $formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);
          $formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);
          $formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);

          if($row_rsNotificacoes['email']) {       
            if($row_rsNotificacoes['resposta']) {
              envia_resposta($row_rsNotificacoes['id'], $nome, $email, $mensagem_final, $pagina_form);
            }

            sendMail($row_rsNotificacoes['email'], '', $formcontent, $formcontent, $subject, $row_rsNotificacoes['email2'], $row_rsNotificacoes['email3'], $email);

            header("location: ".$url_form."env=9");
            exit();
          }
        }
      }
    }
  }
  else {
    header("location: ".$url_form."env=9");
    exit();
  }
}

if(CARRINHO_SALDO == 1) {
  $query_rsSaldo = "SELECT * FROM clientes_saldo WHERE cliente_id = '$id_cliente' ORDER BY id DESC";
  $rsSaldo = DB::getInstance()->prepare($query_rsSaldo);
  $rsSaldo->execute();
  $row_rsSaldo = $rsSaldo->fetchAll();
  $totalRows_rsSaldo = $rsSaldo->rowCount();
}

if($row_rsCliente['referer'] > 0) {
  $query_rsClienteReferer = "SELECT * FROM clientes WHERE id = '".$row_rsCliente['referer']."'";
  $rsClienteReferer = DB::getInstance()->prepare($query_rsClienteReferer);
  $rsClienteReferer->execute();
  $row_rsClienteReferer = $rsClienteReferer->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsClienteReferer = $rsClienteReferer->rowCount();
}

if(substr_count($row_rsCliente['nome'], ' ') >= 1) {
  $parts = explode(' ', $row_rsCliente['nome']);
  $firstname = array_shift($parts);
  $lastname = array_pop($parts);  
}

$query_rsAtividades = "SELECT * FROM clientes_atividades".$extensao." ORDER BY id ASC";
$rsAtividades = DB::getInstance()->prepare($query_rsAtividades);
$rsAtividades->execute();
$totalRows_rsAtividades = $rsAtividades->rowCount();

$data_nasc = date("d-m-Y", strtotime($row_rsCliente['data_nasc']));

$menu_sel = "area_reservada";
$menu_sel_area = "dados";

DB::close();

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Recursos->Resources["charset"];?>" />
  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame - Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>
    <?php if($title) { echo addslashes(htmlspecialchars($title, ENT_COMPAT, 'UTF-8')); } else { echo $Recursos->Resources["pag_title"]; } ?>
  </title>
  <?php if($description) { ?>
    <META NAME="description" CONTENT="<?php echo addslashes(htmlspecialchars($description, ENT_COMPAT, 'UTF-8')); ?>" />
  <?php } ?>
  <?php if($keywords != "") { ?>
    <META NAME="keywords" CONTENT="<?php echo addslashes(htmlspecialchars($keywords, ENT_COMPAT, 'UTF-8')); ?>" />
  <?php } ?>
  <?php include_once('codigo_antes_head.php'); ?>
</head>
<body>
<!--Preloader-->
<div class="mask">
  <div id="loader"></div>
</div>
<!--Preloader-->

<div class="mainDiv">
  <div class="row1">
    <div class="div_table_cell">
      <?php include_once('header.php'); ?>
      <nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
        <div class="row">
          <div class="column">
            <ul class="breadcrumbs">
              <li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
              <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
              <li>
                <span><?php echo $Recursos->Resources["dados_pessoais"]; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="div_100 area_reservada">
        <div class="row">
          <div class="column small-12 medium-3">
            <?php include_once('area-reservada-menu.php'); ?>               
          </div>
          
          <div class="column small-12 medium-9">
            <div class="listagem">
            <div class="div_100">
              <div class="elements_animated right text-center"> 
                <form method="POST" name="form_dados" id="form_dados" onSubmit="return validaForm('form_dados')" autocomplete="off" novalidate>
                  <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["dados_pessoais"]; ?></h3></div>
                  <div class="div_100 text-left">
                    <?php if($totalRows_rsClienteReferer > 0) { ?>
                      <div class="div_100" style="margin-bottom: 2rem;">  
                        <label class="inpt_label" for="referer" style="width: 100%;"><?php echo $Recursos->Resources["ar_referer"];?>: <span style="color: #a8a8a8"><?php echo $row_rsClienteReferer['nome']." - ".$row_rsClienteReferer['email'];?></span></label>
                      </div>
                    <?php } ?>
                    <div class="div_100">
                      <div class="inpt_cells xsmall">       
                        <div class="inpt_holder">
                          <label class="inpt_label" for="<?php echo $form_dados['nome']; ?>"><?php if($row_rsCliente['tipo'] == 1) echo $Recursos->Resources["nome"]; else echo $Recursos->Resources["ar_empresa"]; ?> *</label><!--
                          --><input class="inpt" required type="text" name="<?php echo $form_dados['nome']; ?>" id="<?php echo $form_dados['nome']; ?>" value="<?php echo $row_rsCliente['nome'];?>"/>
                        </div>   
                      </div><!--
                      --><?php if($row_rsCliente['tipo'] == 2) { ?>
                        <div class="inpt_cells xsmall">    
                          <div class="inpt_holder">
                            <label class="inpt_label" for="<?php echo $form_dados['pessoa_contacto']; ?>"><?php echo $Recursos->Resources["ar_pessoa_contacto"]; ?> *</label><!--
                            --><input class="inpt" required type="pessoa_contacto" name="<?php echo $form_dados['pessoa_contacto']; ?>" id="<?php echo $form_dados['pessoa_contacto']; ?>" value="<?php echo $row_rsCliente['pessoa'];?>"/>
                          </div>
                        </div>
                      <?php } ?><!--
                      --><div class="inpt_cells xsmall">    
                        <div class="inpt_holder">
                          <label class="inpt_label" for="<?php echo $form_dados['email']; ?>"><?php echo $Recursos->Resources["ar_email"]; ?> *</label><!--
                          --><input class="inpt" required type="email" name="<?php echo $form_dados['email']; ?>" id="<?php echo $form_dados['email']; ?>" value="<?php echo $row_rsCliente['email'];?>"/>
                        </div>
                      </div><!--
                      --><div class="inpt_cells xsmall">    
<!--                         <div class="inpt_holder">
                          <label class="inpt_label" for="<?php echo $form_dados['nif']; ?>"><?php echo $Recursos->Resources["ar_contribuinte"]; ?><?php if($row_rsCliente['tipo'] == 2) { ?> * <?php } ?></label><input <?php if($row_rsCliente['tipo'] == 2) { ?> required <?php } ?> class="inpt mask-nif"<?php if($row_rsCliente['tipo'] > 1) echo " required"; ?> type="text" name="<?php echo $form_dados['nif']; ?>" id="<?php echo $form_dados['nif']; ?>" value="<?php echo $row_rsCliente['nif'];?>"/>
                        </div>
 -->
                         <div class="inpt_holder">
                          <label class="inpt_label" for="<?php echo $form_dados['nif']; ?>"><?php echo $Recursos->Resources["ar_contribuinte"]; ?><?php if($row_rsCliente['tipo'] == 2) { ?> * <?php } ?></label><input class="inpt mask-nif" type="text" name="<?php echo $form_dados['nif']; ?>" id="<?php echo $form_dados['nif']; ?>" value="<?php echo $row_rsCliente['nif'];?>"/>
                        </div>
                      </div><!--
                      --><div class="inpt_cells xsmall">    
                        <div class="inpt_holder">
                          <label class="inpt_label" for="<?php echo $form_dados['telefone']; ?>"><?php echo $Recursos->Resources["ar_telefone"]; ?></label><!--
                          --><input class="inpt half mask-telphone" type="tel" name="<?php echo $form_dados['telefone']; ?>" id="<?php echo $form_dados['telefone']; ?>" value="<?php echo $row_rsCliente['telefone'];?>"/>
                        </div>
                      </div><!--
                      --><div class="inpt_cells xsmall">    
                        <div class="inpt_holder">
                          <label class="inpt_label" for="<?php echo $form_dados['telemovel']; ?>"><?php echo $Recursos->Resources["ar_telemovel"]; ?> *</label><!--
                          --><input class="inpt half mask-phone" required type="tel" name="<?php echo $form_dados['telemovel']; ?>" id="<?php echo $form_dados['telemovel']; ?>" value="<?php echo $row_rsCliente['telemovel'];?>"/>
                        </div>
                      </div><!--
                      --><div class="inpt_cells xsmall">    
                        <div class="inpt_holder">
                          <label class="inpt_label" for="<?php echo $form_dados['data_nasc']; ?>"><?php echo $Recursos->Resources["ar_data_nasc"]; ?> </label><!--
                          --><input class="inpt half " type="text" name="<?php echo $form_dados['data_nasc']; ?>" id="<?php echo $form_dados['data_nasc']; ?>" value="<?php echo $row_rsCliente['data_nasc'];?>" placeholder="aaaa-mm-dd"/>
                        </div>
                      </div><!--
                      --><?php if($row_rsCliente['tipo'] == 2) { ?>
                        <div class="inpt_cells xsmall">    
                          <div class="inpt_holder select" style="margin-bottom: 1rem;">
                            <label class="inpt_label" for="<?php echo $form_dados['atividade']; ?>"><?php echo $Recursos->Resources["ar_actividade"]; ?> *</label><!--
                            --><select class="inpt" name="<?php echo $form_dados['atividade']; ?>" id="<?php echo $form_dados['atividade']; ?>" required onchange="changeActividade(this.value);">
                              <option value=""><?php echo $Recursos->Resources["selecione"]; ?></option> 
                              <?php if($totalRows_rsAtividades > 0) {
                                while($row_rsAtividades = $rsAtividades->fetch()) { ?>
                                  <option value="<?php echo $row_rsAtividades["nome"]; ?>" <?php if($row_rsCliente["atividade"] == $row_rsAtividades["nome"]) echo "selected"; ?>><?php echo $row_rsAtividades["nome"]; ?></option>        
                                <?php }
                              } ?>
                              <option value="<?php echo $Recursos->Resources["outro"];?>" <?php if($row_rsCliente["atividade"] == $Recursos->Resources["outro"]) echo "selected"; ?>><?php echo $Recursos->Resources["outro"];?></option>
                            </select>
                          </div>
                          <div class="inpt_holder act_outro">
                            <input class="inpt half" type="text" name="<?php echo $form_dados['act_outro']; ?>" id="<?php echo $form_dados['act_outro']; ?>" value="<?php echo $row_rsCliente['atividade2'];?>"/>
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                    <button role="button" type="submit" class="button invert"><?php echo $Recursos->Resources["comprar_guardar"]; ?></button>  
                    <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                    <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                    <input type="hidden" name="MM_insert" value="form_dados" />
                    <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                  </div>
                </form>
                <form action="" class="area_margin" method="POST" name="form_pass" id="form_pass" onSubmit="return validaForm('form_pass')" autocomplete="off" novalidate>
                  <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["alterar_password"]; ?></h3></div>
                  <div class="div_100 text-left">
                    <div class="div_100">
                      <div class="inpt_cells xsmall">    
                        <div class="inpt_holder">
                          <label class="inpt_label" for="<?php echo $form_pass['password']; ?>"><?php echo $Recursos->Resources["ar_password"];?></label><!--
                          --><input class="inpt confirm" required type="password" name="<?php echo $form_pass['password']; ?>" id="<?php echo $form_pass['password']; ?>" value=""/>
                          <div class="passwordToggler" onClick="changePassType(this)"></div>
                        </div>
                      </div><!-- 
                      --><div class="inpt_cells xsmall">    
                        <div class="inpt_holder">
                            <label class="inpt_label" for="<?php echo $form_pass['password2']; ?>"><?php echo $Recursos->Resources["ar_password_conf"];?></label><!--
                          --><input class="inpt cod_confirm" required type="password" name="<?php echo $form_pass['password2']; ?>" id="<?php echo $form_pass['password2']; ?>" value=""/>
                          <div class="passwordToggler" onClick="changePassType(this)"></div>
                        </div>
                      </div>                                  
                    </div>
                    <button type="submit" class="button invert"><?php echo $Recursos->Resources["comprar_guardar"]; ?></button> 

                    <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                    <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                    <input type="hidden" name="MM_insert" value="form_pass" />
                    <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                  </div>
                </form>
                <form action="" class="area_margin" method="POST" name="form_elimina" id="form_elimina" onSubmit="return validaForm('form_elimina')" autocomplete="off" novalidate>
                  <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["eliminar_conta"]; ?></h3></div>
                  <div class="textos text-left" style="margin-bottom: 2rem;"><p><?php echo $Recursos->Resources["eliminar_conta_msg"]; ?></p></div>
                  <div class="div_100 text-left">
                    <div class="div_100">
                      <div class="inpt_holder textarea">
                        <label class="inpt_label" for="<?php echo $form_elimina['descricao']; ?>"><?php echo $Recursos->Resources["eliminar_conta_desc"];?> *</label><!--
                        --><textarea class="inpt" name="<?php echo $form_elimina['descricao']; ?>" id="<?php echo $form_elimina['descricao']; ?>" required></textarea>
                      </div>                             
                    </div>

                    <button type="submit" class="button invert"><?php echo $Recursos->Resources["enviar"]; ?></button> 

                    <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                    <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                    <input type="hidden" name="MM_insert" value="form_elimina" />
                    <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                  </div>
                </form>
              </div>    
            </div>
            <?php if(CARRINHO_SALDO == 1) { ?>
              <div class="div_100 area_margin text-center">
                <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["saldo_disponivel"]; ?>: <?php echo number_format($row_rsCliente['saldo'], 2, ',', '.');?> &pound;</h3></div>
                <?php if($totalRows_rsSaldo > 0) { ?>
                  <div class="custom_table text-left padded" id="favoritos-list">
                    <div class="thead">
                      <div class="table-tr">
                        <div class="table-td data text-center"><?php echo $Recursos->Resources["tck_data"]; ?></div>
                        <div class="table-td valor text-center"><?php echo $Recursos->Resources["valor"]; ?></div>
                        <div class="table-td validado text-center"><?php echo $Recursos->Resources["tck_estado"]; ?></div>
                        <div class="table-td text-center">&nbsp;</div>
                      </div>
                    </div>
                    <div class="tbody list">
                      <?php foreach($row_rsSaldo as $saldo) {
                        $data = explode(" ", $saldo['data']);
                        ?><!-- 
                        --><div class="table-tr list_div" data-amigo="<?php echo $saldo['id']; ?>">
                          <div class="table-td data text-center" data-tit="<?php echo $Recursos->Resources["tck_data"]; ?>:"><?php echo $data[0]; ?></div>
                          <div class="table-td valor text-center" data-tit="<?php echo $Recursos->Resources["valor"]; ?>:"><?php if($saldo['operacao'] == 2) echo "- "; ?><?php echo number_format($saldo['valor'], 2, ',', '.'); ?> &pound;</div>
                          <div class="table-td validado text-center" data-tit="<?php echo $Recursos->Resources["tck_estado"]; ?>:">
                            <?php if($saldo['validado'] == '1') echo $Recursos->Resources["concluido"]; else echo $Recursos->Resources["por_validar"]; ?>
                          </div>
                          <div class="table-td validado" data-tit="<?php echo $Recursos->Resources["comprar_observacoes"]; ?>:">
                            <?php echo $saldo['detalhe']; ?>
                          </div>
                        </div><!-- 
                      --><?php } ?>
                    </div>
                  </div>
                <?php } else { ?>
                  <div class="area_reservada_resultados textos"><?php echo $Recursos->Resources["sem_registos"]; ?></div>
                <?php } ?>
              </div>
            <?php } ?>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php include_once('footer.php'); ?>
</div>

<?php include_once('codigo_antes_body.php'); ?>

<script type="text/javascript">
$(window).on('load', function() {
  changeActividade('<?php echo $row_rsCliente['atividade']; ?>');
}); 

function changeActividade(act) {
  if(act == "<?php echo $Recursos->Resources["outro"]; ?>") {
    $('.act_outro').fadeIn('slow');
    $('.act_7').attr('required','required');
  }
  else {
    $('.act_outro').fadeOut('slow');
    $('.act_7').removeAttr('required').removeClass('has-error');
  }
}
</script>

<?php if($existe_user1 == 1) { ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      ntg_error("<?php echo $Recursos->Resources["area_mail_registado"]; ?>");
    });
  </script>
<?php } ?>
<?php if($existe_user2 == 1) { ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      ntg_error("<?php echo $Recursos->Resources["area_nif_registado"]; ?>");
    });
  </script>
<?php } ?>

<?php if(isset($_GET['alterado']) && $_GET['alterado']=='1') { ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      ntg_success("<?php echo $Recursos->Resources["ar_alt_dados"]; ?>");
    });
  </script>
<?php }?>

<?php if(isset($_GET['erro']) && $_GET['erro'] == 1) { ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      ntg_alert("<?php echo $Recursos->Resources["preencha_dados"]; ?>");
    });
  </script>
<?php } ?>
</body>
</html>