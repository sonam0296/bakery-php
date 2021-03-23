<?php require_once('../../../Connections/connADMIN.php'); ?>
<?php include_once('../../sendMail/send_mail.php'); ?>
<?php include_once('news-funcoes-logs.php'); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<?php //ini_set('display_errors', 1);

$id_link=$_GET['id'];

$query_rsLink = "SELECT * FROM news_links WHERE id=:id";
$rsLink = DB::getInstance()->prepare($query_rsLink);
$rsLink->bindParam(':id', $id_link, PDO::PARAM_INT);
$rsLink->execute();
$row_rsLink = $rsLink->fetch(PDO::FETCH_ASSOC);
$totalRows_rsLink = $rsLink->rowCount();
DB::close();

if($totalRows_rsLink > 0) {
  //Através do código obtido vamos obter o id do email correspondente
  $query_rs = "SELECT id FROM news_emails WHERE codigo=:code";
  $rs = DB::getInstance()->prepare($query_rs);
  $rs->bindParam(':code', $row_rsLink['codigo'], PDO::PARAM_STR, 5);
  $rs->execute();
  $row_rs = $rs->fetch(PDO::FETCH_ASSOC);
  $totalRows_rs = $rs->rowCount();
  DB::close();

  if($totalRows_rs > 0) {
    $email_id = $row_rs['id'];
    
    $ip=$_SERVER['REMOTE_ADDR'];

    if($ip=="") {
      $ip=$HTTP_SERVER_VARS['REMOTE_ADDR'];
    }
    
    //94.126.169.55 - FILTRO SPAM 
    //Verifica se passou 1 min depois que o email foi enviado.. por causa do filtro de Spam!!
    if($ip!='94.126.169.55') {
      $data = date('Y-m-d H:i:s');
      
      //Registar mais uma visualização para o link
      $insertSQL = "UPDATE news_links SET n_clicks=n_clicks+1, data_ultimo_click='$data' WHERE id='$id_link'";
      $rsInsertSQL = DB::getInstance()->prepare($insertSQL);
      $rsInsertSQL->execute();
      DB::close();

      if($row_rsLink['tipo_link'] == 2) {
        $link = $row_rsLink['url'];
        if(strpos($link, "mailto:") !== false){
          $link = str_replace("mailto:", '', $link);
          $link = trim($link);
        }
        ?>
        <a id="link_mail" href="mailto:<?php echo $link; ?>" onClick="fecha_janela();"></a>

        <script type="text/javascript">
          $(window).load(function() {
            setTimeout(function() {
              document.getElementById('link_mail').click();
            }, 500);
          });

          function fecha_janela() {
            setTimeout(function() {
              window.close();
            }, 500);
          }
        </script>
        <?php
      }
      else
        header("Location: ".$row_rsLink['url']);
    }
  }
}

// header("Content-type: img/gif");

// $im=imagecreatefromgif('../imgs/fill.gif');

// imagegif($im);
// imagedestroy($im);

?>