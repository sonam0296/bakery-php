<?php require_once('Connections/connADMIN.php'); ?>
<?php

$op = $_GET['op'];
$code = $_GET['code'];

if(isset($code) && isset($op) && $code != '' && $op == 'sdaioh2380usdo8f7804hf08y3hfiyf8923yh9') {
  //Se o cliente já tiver aberto o email, não vamos contabilizar a nova visualização
  $query_rsCarrinho = "SELECT id FROM carrinho_cliente_hist WHERE codigo=:code AND aberto=0";
  $rsCarrinho = DB::getInstance()->prepare($query_rsCarrinho);
  $rsCarrinho->bindParam(':code', $code, PDO::PARAM_STR, 5);
  $rsCarrinho->execute();
  $row_rsCarrinho = $rsCarrinho->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsCarrinho = $rsCarrinho->rowCount();

  if($totalRows_rsCarrinho > 0) {
    $id_linha = $row_rsCarrinho['id'];
    $data = date('Y-m-d H:i:s');

    $ip = $_SERVER['REMOTE_ADDR'];
    if($ip == "") {
      $ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
    }
    
    //94.126.169.55 - FILTRO SPAM 
    if($ip != '94.126.169.55') {
      $insertSQL = "UPDATE carrinho_cliente_hist SET aberto=1, data_aberto=:data WHERE id=:id";
      $rsInsertSQL = DB::getInstance()->prepare($insertSQL);
      $rsInsertSQL->bindParam(':data', $data, PDO::PARAM_STR, 5);
      $rsInsertSQL->bindParam(':id', $id_linha, PDO::PARAM_INT);
      $rsInsertSQL->execute();
    }
  }

  DB::close();
}

header("Content-type: img/gif");

$im=imagecreatefromgif('../imgs/elem/fill.gif');

imagegif($im);
imagedestroy($im);
?>