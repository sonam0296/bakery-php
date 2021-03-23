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

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_convidar")) {
  if($_POST['form_hidden'] == "") {
    if($csrf->check_valid('post')) {
      if($_POST['emails'] != "") {  
        $query_rsTexto = "SELECT * FROM textos".$extensao." WHERE id = 1";
        $rsTexto = DB::getInstance()->prepare($query_rsTexto);
        $rsTexto->execute();
        $row_rsTexto = $rsTexto->fetch(PDO::FETCH_ASSOC);

        $formcontent = getHTMLTemplate("contacto.htm");  
            
        $rodape = email_social();
                            
        $nome_cliente = $row_rsCliente['nome'];
        $email_cliente = $row_rsCliente['email'];
        $cod_cliente = $row_rsCliente['cod_bonus'];
        
        $emails = $_POST['emails'];
        $emails_parts = explode(',', $emails);
        
        $titulo = $Recursos->Resources['conv_email_tit'];
        $subject = $row_rsTexto['assunto_email'];
        $texto = str_replace("#nome_cliente#", $row_rsCliente['nome'], $row_rsTexto['texto_email']);
        
        $link = "<a href=\"".ROOTPATH_HTTP."login.php?code=".$cod_cliente."\" style=\"background-color: #ffffff; font-family: arial; font-weight: 400; font-size: 13px; color: #000000; line-height: 40px; text-align: center; text-decoration:uppercase;\">".$Recursos->Resources["conv_email_msg_3"]."</a>";

        $mensagem_final = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
            <tr>
              <td align="left" width="390" valign="top" height="25" style="font-family:arial; font-size:12px; line-height:18px; color:#3e3d42;">'.$texto.'</td>
            </tr>
            <tr>
              <td height="15">&nbsp;</td>
            </tr>
          </table>
          <table width="200" height="40" style="border: 2px solid #000000;" cellpadding="0" cellspacing="0">
            <tr>
              <td width="200" height="40" align="center">'.$link.'</td>
            </tr>
          </table>';
  
        $pagina_form = $_POST['titulo_pag']."<br><a style='font-family:arial; font-size: 11px; color: #444444; line-height:13px;' href='".ROOTPATH_HTTP."'>".ROOTPATH_HTTP."</a>";    
        
        $formcontent = str_replace ("#cpagina#", $pagina_form, $formcontent);
        $formcontent = str_replace ("#crodape#", $rodape, $formcontent);
        $formcontent = str_replace ("#ctitulo#", $titulo, $formcontent);
        $formcontent = str_replace ("#cmensagem#", $mensagem_final, $formcontent);
        $formcontent = str_replace ("#tit_mail_compr#", $Recursos->Resources["car_mail_7"], $formcontent);

        $data_convite = date('Y-m-d H:i:s');

        foreach($emails_parts as $email) {
          $mail = trim($email);

          if($mail != '') {
            $query_rsExiste = "SELECT id FROM convidar_amigos WHERE id_cliente = :id_cliente AND email_convidado = :email_convidado";
            $rsExiste = DB::getInstance()->prepare($query_rsExiste);
            $rsExiste->bindParam(':id_cliente', $row_rsCliente['id'], PDO::PARAM_INT);
            $rsExiste->bindParam(':email_convidado', $mail, PDO::PARAM_STR, 5);
            $rsExiste->execute();
            $totalRows_rsExiste = $rsExiste->rowCount();
            $rows_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);

            if($totalRows_rsExiste > 0) {
              $query_rsUpdate = "UPDATE convidar_amigos SET data_convite = :data_convite WHERE id = :id";
              $rsUpdate = DB::getInstance()->prepare($query_rsUpdate);
              $rsUpdate->bindParam(':id', $rows_rsExiste['id'], PDO::PARAM_INT);
              $rsUpdate->bindParam(':data_convite', $data_convite, PDO::PARAM_STR, 5);
              $rsUpdate->execute();
            }
            else {
              $query_rsInsert = "INSERT INTO convidar_amigos (id, id_cliente, email_convidado, data_convite) VALUES ('', :id_cliente, :email_convidado, :data_convite)";
              $rsInsert = DB::getInstance()->prepare($query_rsInsert);
              $rsInsert->bindParam(':id_cliente', $row_rsCliente['id'], PDO::PARAM_INT);
              $rsInsert->bindParam(':email_convidado', $mail, PDO::PARAM_STR, 5);
              $rsInsert->bindParam(':data_convite', $data_convite, PDO::PARAM_STR, 5);
              $rsInsert->execute();
            }

            sendMail($mail, '', $formcontent, $formcontent, $subject);
          }
        }

        header("Location: area-reservada-convidar.php?inserido=1");
      }
    }
  }
}

$query_rsTexto = "SELECT * FROM textos".$extensao." WHERE id = 1";
$rsTexto = DB::getInstance()->prepare($query_rsTexto);
$rsTexto->execute();
$row_rsTexto = $rsTexto->fetch(PDO::FETCH_ASSOC);

$query_rsAmigos = "SELECT * FROM convidar_amigos WHERE id_cliente = '".$row_rsCliente['id']."'";
$rsAmigos = DB::getInstance()->prepare($query_rsAmigos);
$rsAmigos->execute();
$totalRows_rsAmigos = $rsAmigos->rowCount();

$query_rsConfig = "SELECT saldo_por_compra_amigo, valor_por_compra_amigo FROM config_ecommerce WHERE id = 1";
$rsConfig = DB::getInstance()->prepare($query_rsConfig);
$rsConfig->execute();
$row_rsConfig = $rsConfig->fetch(PDO::FETCH_ASSOC);
$totalRows_rsConfig = $rsConfig->rowCount();

$menu_sel = "area_reservada";
$menu_sel_area = "convidar";

DB::close();

?>
<!DOCTYPE html>
<html lang="<?php echo $lang;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Recursos->Resources["charset"];?>" />
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame - Remove this if you use the .htaccess -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>
<?php if($title){ echo addslashes(htmlspecialchars($title, ENT_COMPAT, 'UTF-8')); }else{ echo $Recursos->Resources["pag_title"];}?>
</title>
<?php if($description){?>
<META NAME="description" CONTENT="<?php echo addslashes(htmlspecialchars($description, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php if($keywords!=""){?>
<META NAME="keywords" CONTENT="<?php echo addslashes(htmlspecialchars($keywords, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php include_once('codigo_antes_head.php'); ?>
</head>
<body>
<!--Preloader-->
<div class="mask">
  <div id="loader">
  </div>
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
                <span><?php echo $Recursos->Resources["meus_amigos"]; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="div_100 area_reservada tickets">
        <div class="row">
          <div class="column small-12 medium-3">
            <?php include_once('area-reservada-menu.php'); ?>
          </div>
          <div class="column small-12 medium-1 show-for-medium"></div>
          <div class="column small-12 medium-8">
            <div class="div_100 text-center">
              <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources['meus_amigos']; ?></h3></div>
              <?php if($row_rsTexto['texto_site'] != '') { 
                $texto = $row_rsTexto['texto_site'];
                $texto = str_replace('#perc#', $row_rsConfig['saldo_por_compra_amigo'], $texto);
                $texto = str_replace('#minimo#', $row_rsConfig['valor_por_compra_amigo'], $texto);
                ?>
                <div class="area_reservada_convidar textos text-left"><?php echo $texto; ?></div>
              <?php } ?>
              <div class="elements_animated right text-left"> 
                <form action="" novalidate autocomplete="off" onSubmit="return validaForm('form_convidar')" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>" method="POST" name="form_convidar" id="form_convidar" enctype="multipart/form-data">
                  <div class="inpt_holder">
                    <label class="inpt_label" for="cod_bonus"><?php echo $Recursos->Resources["conv_cod_bonus"]; ?></label><!--
                    --><input type="text" class="inpt" readonly name="cod_bonus" id="cod_bonus" value="<?php echo $row_rsCliente['cod_bonus']; ?>" />
                  </div>
                  <div class="inpt_holder textarea">
                    <label class="inpt_label" for="emails"><?php echo $Recursos->Resources["conv_emails"]; ?> *</label><!--
                    --><textarea class="inpt" required name="emails" id="emails"></textarea><!-- 
                  --></div>
                  <div class="inpt_holder simple">
                    <label class="textos" for="emails"><?php echo $Recursos->Resources["conv_emails_txt"]; ?></label>
                  </div>

                  <button type="submit" class="button invert" style="float: right;"><?php echo $Recursos->Resources["enviar"]; ?></button>

                  <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                  <input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
                  <input type="hidden" name="MM_insert" value="form_convidar" />
                  <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                </form>
              </div>
            </div>
            <div class="div_100 tickets_list area_margin">
              <div class="div_100 text-center">
                <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources['conv_amigos_tit']; ?></h3></div>
                <?php if($totalRows_rsAmigos > 0) { ?>
                  <div class="custom_table text-left padded" id="favoritos-list">
                    <div class="thead">
                      <div class="table-tr">
                        <div class="table-td mail"><?php echo $Recursos->Resources["mail"]; ?></div>
                        <div class="table-td data text-center"><?php echo $Recursos->Resources["conv_data"]; ?></div>
                        <div class="table-td estado text-center"><?php echo $Recursos->Resources["conv_estado"]; ?></div>
                      </div>
                    </div>
                    <div class="tbody list">
                      <?php while($row_rsAmigos = $rsAmigos->fetch()) { ?><!-- 
                        --><div class="table-tr list_div" data-amigo="<?php echo $row_rsAmigos['id']; ?>">
                          <div class="table-td mail" data-tit="<?php echo $Recursos->Resources["mail"]; ?>:"><?php echo $row_rsAmigos['email_convidado']; ?></div>
                          <div class="table-td data text-center" data-tit="<?php echo $Recursos->Resources["conv_data"]; ?>:"><?php echo $row_rsAmigos['data_convite']; ?></div>
                          <div class="table-td estado text-center" data-tit="<?php echo $Recursos->Resources["conv_estado"]; ?>:">
                            <?php if($row_rsAmigos['aceite'] == 1) echo $Recursos->Resources["conv_estado_1"]; else echo $Recursos->Resources["conv_estado_2"]; ?>
                          </div>
                        </div><!-- 
                      --><?php } ?>
                    </div>
                  </div>
                <?php } else { ?>
                  <div class="area_reservada_resultados textos"><?php echo $Recursos->Resources["sem_amigos"]; ?></div>
                <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>      
  </div>
  <?php include_once('footer.php'); ?>
</div>
<?php if(isset($_GET['inserido']) && $_GET['inserido'] == 1) { ?>
  <script type="text/javascript">     
    $(window).on('load', function() {
      ntg_success("<?php echo $Recursos->Resources["conv_sucesso"]; ?>");
    });
  </script>
<?php } ?>
<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>