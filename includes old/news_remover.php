<?php require_once('Connections/connADMIN.php'); 
if(!isset($_SESSION)) {
  session_start();
}

try {
  $query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
  $rsMeta = DB::getInstance()->prepare($query_rsMeta);
  $rsMeta->execute();
  $row_rsMeta = $rsMeta->fetchAll();
  $totalRows_rsMeta = $rsMeta->rowCount();
  DB::close();

  foreach($row_rsMeta as $row) {
    $title = $row["title"];
    $description = $row["description"];
    $keywords = $row["keywords"];
  }

} catch(PDOException $e){
  echo $e->getMessage();
}

$form_seguranca = $csrf->form_names(array('cod_res', 'cod_seg'), false);

$form_news_remove = $csrf->form_names(array('email'), false);
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_news_remove")) {
  if($_POST['form_hidden']==""){
    if($csrf->check_valid('post')) {
      if( (isset($_POST[$form_seguranca['cod_res']]) && $_POST[$form_seguranca['cod_res']]==$_POST[$form_seguranca['cod_seg']]) && !isset($_POST['g-recaptcha-response']) && CAPTCHA_KEY==NULL){
        $response=1;
      }else{
        $response = isValidCaptcha($_POST['g-recaptcha-response']);
      }
      
      if($response==1) {
        if($_POST[$form_news_remove['email']]!="") {
          $email= $_POST[$form_news_remove['email']];

          $query_rsExiste = "SELECT * FROM news_emails WHERE email = :email AND visivel='1'";
          $rsExiste = DB::getInstance()->prepare($query_rsExiste);
          $rsExiste->bindParam(':email', $email, PDO::PARAM_STR, 5); 
          $rsExiste->execute();
          $row_rsExiste = $rsExiste->fetch(PDO::FETCH_ASSOC);
          $totalRows_rsExiste = $rsExiste->rowCount();
          DB::close();

          $data = date("Y-m-d H:i:s");
        
          if($totalRows_rsExiste > 0) {
            $id=$row_rsExiste['id'];
        
            $insertSQL = "UPDATE news_emails SET visivel='0', data_remocao=:data, aceita='0', origem_remocao='Anular subscrição' WHERE id='$id'";
            $Result1 = DB::getInstance()->prepare($insertSQL);
            $Result1->execute(array(':data'=>$data));
            DB::close();
        
            header("Location: news_remover.php?rem=1");
          }
          else{
            header("Location: news_remover.php?err=1");
          }
        }
      }
    }
  }
}

$menu_sel="anular";

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
<?php include_once('funcoes.php'); ?>
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
    <div class="div_table_cell news_remover">
      <?php include_once('header.php'); ?>

      <div class="div_100 news_cont" >
        <div class="row content align-center" style="position: static;">
          <div class="small-12 medium-6 column">
            <h1 class="subtitulos"><?php echo $Recursos->Resources["anular_subscricao"];?></h1>
            <div class="textos"><?php echo $Recursos->Resources["anular_msg"];?></div>
              <form action="" onSubmit="return validaForm('form_news_remove')" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>"  method="post" name="form_news_remove" id="form_news_remove" novalidate autocomplete="off">
                <div class="animated_elements right"> 
                  <div class="inpt_holder">
                    <input required autocomplete="off" class="inpt" type="email" id="<?php echo $form_news_remove['email']; ?>" name="<?php echo $form_news_remove['email']; ?>" placeholder="<?php echo $Recursos->Resources["mail"]; ?> *"/>
                  </div>
                  
                  <div class="captcha" id="contactos_captcha" data-sitekey="<?php echo CAPTCHA_KEY; ?>" data-error="<?php echo $Recursos->Resources['preencha_captcha']; ?>"></div>
          
                  <button type="submit" class="button invert1">
                    <?php echo $Recursos->Resources["anular"];?>
                  </button>
                  
                  <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                  <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                  <input type="hidden" name="MM_insert" value="form_news_remove" />
                  <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                </div>                
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('footer.php'); ?>
</div>
<?php include_once('codigo_antes_body.php'); ?>
<?php include_once('footer_scripts.php'); ?>
<script type="text/javascript">
<?php if(isset($_GET['rem']) && $_GET['rem'] == 1) { ?>
  $(document).ready(function(){
    ntg_success('<?php echo $Recursos->Resources["news_remove_sucesso"]; ?>');
  });
<?php } ?>
<?php if(isset($_GET['err']) && $_GET['err'] == 1) { ?>
  $(document).ready(function(){
    ntg_error('<?php echo $Recursos->Resources["news_remove_erro"]; ?>');
  });
<?php } ?>
</script>
</body>
</html>