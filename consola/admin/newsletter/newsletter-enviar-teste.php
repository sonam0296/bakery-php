<?php include_once('../inc_pages.php'); ?>
<?php include_once(ROOTPATH.'sendMail/sendMailNews.php');
include_once('newsletter-funcoes-logs.php');
//ini_set("display_errors", 1);

$menu_sel='newsletter_newsletters';
$menu_sub_sel='';

$id=$_GET['id'];
$inserido=0;
$lista_erro=0;
$envio_teste=0;
$id_lista=0;
$id_historico=0;

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_newsletter_envia_teste")) {
  $str = $_POST['emails'];

  $emails_list=explode(',',$str);
  for($i=0; $i<count($emails_list); $i++){
    $list[$i] = trim($emails_list[$i]);
  }

  $query_rsP = "SELECT titulo, nome_from, email_from, email_reply, tipo_envio FROM newsletters WHERE id='$_GET[id]'";
  $rsP = DB::getInstance()->prepare($query_rsP);
  $rsP->execute();
  $row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsP = $rsP->rowCount();
  DB::close();

  $nome_from = $row_rsP['nome_from'];
  $email_from = $row_rsP['email_from'];
  $email_reply = $row_rsP['email_reply'];
  $email_bounce = $row_rsP['email_bounce'];
  $titulo = $row_rsP['titulo'].' - Teste';

  $query_rsEmails = "SELECT nome_from, email_from, email_reply, email_bounce FROM newsletters_config";
  $rsEmails = DB::getInstance()->prepare($query_rsEmails);
  $rsEmails->execute();
  $row_rsEmails = $rsEmails->fetch(PDO::FETCH_ASSOC);
  DB::close();

  if(!$nome_from) $nome_from = $row_rsEmails['nome_from'];
  if(!$email_from) $email_from = $row_rsEmails['email_from'];
  if(!$email_reply) $email_reply = $row_rsEmails['email_reply'];
  if(!$email_bounce) $email_bounce = $row_rsEmails['email_bounce'];
  
  $news_content_txt = $row_rsP['titulo'];
  $news_content=file_get_contents(HTTP_DIR.'/consola/admin/newsletter/newsletter-edit.php?id='.$id);
  $tipo_envio = $row_rsP['tipo_envio'];

  // verificar qual o tipo de envio: 1- sendMail; 2- Mailgun
  // se for do tipo mailgun faz include do script
  if($tipo_envio == 2) { //
    include_once(ROOTPATH."mailgun/mailgun.php");
  }

  $envio_teste=-1;

  $data_to = array();

  foreach($list as $email) {
    $email = trim($email);
    //corrige erros
    $email = str_replace(array(" ", "\r\n", "\r", "\n"), "", $email);

    if($email){
      //sendmail
      if($tipo_envio == 1) {
        $enviado = sendMailNews($email, $email_from, $news_content, $news_content_txt, $titulo, $email_reply, $email_bounce, 0, 0, '', $nome_from);
      }else{
        //mailgun
        //verifica se o email é válido para acrescentar ao array do mailgun
        $email_is_valid = filter_var($email, FILTER_VALIDATE_EMAIL);

        if($email_is_valid){
          $data_to[$email] = array("email"=>$email, "codigo"=>"", "name"=>"");
        }
      }
    }
  }

  //mailgun
  if($tipo_envio == 2){
    if($nome_from) $email_from = utf8_encode($nome_from).' <'.$email_from.'>';

    //coloca os emails todos juntos
    $email = implode(",", $list);
    
    $enviado = mg_send($id, $email, $email_from, $news_content, $news_content_txt, $titulo, $email_reply, $email_bounce, 0, $data_to);
  }

  if($enviado == 1) $envio_teste = 1;
}

$query_rsP = "SELECT * FROM newsletters WHERE id='$_GET[id]'";
$rsP = DB::getInstance()->prepare($query_rsP);
$rsP->execute();
$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);
$totalRows_rsP = $rsP->rowCount();
DB::close();

$query_rsContNews = "SELECT * FROM news_conteudo WHERE id=:id";
$rsContNews = DB::getInstance()->prepare($query_rsContNews);
$rsContNews->bindParam(':id', $row_rsP['conteudo'], PDO::PARAM_INT);
$rsContNews->execute();
$totalRows_rsContNews = $rsContNews->rowCount();
DB::close();

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
            <form id="frm_newsletter_envia_teste" name="frm_newsletter_envia_teste" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
              <input type="hidden" name="manter" id="manter" value="0">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"> <i class="fa fa-pencil-square"></i>Newsletters - Agendar</div>
                  <div class="form-actions actions btn-set">
                    <button type="button" name="back" class="btn default" onClick="document.location='newsletter.php'"><i class="fa fa-angle-left"></i> Voltar</button>
                    <button type="reset" class="btn default"><i class="fa fa-eraser"></i> Limpar</button>
                    <button id="processa" type="submit" class="btn blue"><i class="fa fa-history"></i> Processar</button> 
                  </div>
                </div>
                <div class="portlet-body">
                  <div class="tabbable">
                    <ul class="nav nav-tabs">
                      <li> <a href="#tab_general" data-toggle="tab" onClick="document.location='newsletter-detalhe.php?id=<?php echo $_GET['id']; ?>'"> Detalhe </a> </li>
                      <li> <a href="#tab_agendar" data-toggle="tab" onClick="document.location='newsletter-enviar.php?id=<?php echo $_GET['id']; ?>'"> Agendar </a> </li>
                      <li> <a href="#tab_general" data-toggle="tab" onClick="document.location='newsletter-historico.php?id=<?php echo $_GET['id']; ?>'"> Agendamentos </a> </li>
                      <li class="active"> <a href="#tab_envio_teste" data-toggle="tab"> Envio teste </a> </li>
                    </ul>
                    <div class="tab-content no-space">
                      <div class="tab-pane active" id="tab_envio_teste">
                        <div class="form-body">
                          <?php if($totalRows_rsContNews == 0) { ?>
                            <div class="alert alert-danger display-show">
                              <button class="close" data-close="alert"></button>
                              Esta newsletter não tem conteúdo associado. 
                            </div>
                          <?php } ?>
                          <?php if($inserido == 1) { ?>
                            <div class="alert alert-success display-show">
                              <button class="close" data-close="alert"></button>
                              <span> Registo efectuado com sucesso. </span> 
                            </div>
                          <?php } ?>
                          <?php if($envio_teste == 1) { ?>
                            <div class="alert alert-success display-show">
                              <button class="close" data-close="alert"></button>
                              <span> Teste de envio de emails efectuado com sucesso. </span> 
                            </div>
                          <?php }elseif($envio_teste == -1) { ?>
                            <div class="alert alert-danger display-show">
                              <button class="close" data-close="alert"></button>
                              <span> Ocorreu um erro ao tentar enviar o teste de newsletter. </span> 
                            </div>
                          <?php } ?>
                          <div class="alert alert-danger display-hide">
                            <button class="close" data-close="alert"></button>
                            Preencha todos os campos obrigatórios. 
                          </div>
                          <div class="col-md-6">
                            <div class="row">
                              <div class="form-group">
                               <label class="col-md-4 control-label" for="emails">Emails: <span class="required"> * </span></label>
                               <div class="col-md-8">
                                 <input type="text" class="form-control" name="emails" id="emails">
                                 <p class="help-block">Introduza os emails separados por vírgulas</p>
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
             <input type="hidden" name="MM_insert" value="frm_newsletter_envia_teste" />
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
</script>
<script type="text/javascript">
  function submete(e) {
    if (e.keyCode == 13) {
      document.getElementById('processa').click();
    }
  }
</script>
</body>
<!-- END BODY -->
</html>