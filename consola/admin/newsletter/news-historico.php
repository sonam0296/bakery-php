<?php require_once('../../Connections/connSITE.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

session_start();

$id=$_GET['id'];

mysql_select_db($database_connSITE, $connSITE);
$query_rsP = "SELECT * FROM newsletters_logs ORDER BY data DESC";
$rsP = mysql_query($query_rsP, $connSITE) or die(mysql_error());
$row_rsP = mysql_fetch_assoc($rsP);
$totalRows_rsP = mysql_num_rows($rsP);


$user=$_SESSION['CONS_User'];

mysql_select_db($database_connSITE, $connSITE);
$query_rsUser = "SELECT * FROM acesso WHERE acesso.username='$user'";
$rsUser = mysql_query($query_rsUser, $connSITE) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);



if($totalRows_rsUser==0){
	header("Location: ../index.php");	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/modelo.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="js/niceforms.js"></script>
<script language="javascript" type="text/javascript" src="js/validationFunctions.js"></script>
<script language="javascript" type="text/javascript" src="js/validationForms.js"></script>
<script type="text/javascript" src="../js/botaorato.js"></script>
<script src="js/jquery-1.2.6.pack.js" type="text/javascript"></script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="padding:15px 15px 15px 15px">
    <?php if($totalRows_rsP>0){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<?php $cor_cor="#EDEDED"; do{ if($cor_cor=="#EDEDED") $cor_cor="#CCCCCC"; else $cor_cor="#EDEDED"; ?>
      <tr>
        <td align="left" valign="middle" bgcolor="<?php echo $cor_cor; ?>" style="padding-left:15px;padding-right:15px;padding-top:5px;padding-bottom:5px"><?php
        $mensagem=$row_rsP['data']." » ";
  			if($row_rsP['utilizador']) $mensagem.="<strong>".$row_rsP['utilizador']."</strong> ";
  			$mensagem.=$row_rsP['que_fez']." » newsletter <strong>".$row_rsP['newsletter']."</strong> ";
  			if($row_rsP['listas']) $mensagem.=" para a(s) lista(s) ".$row_rsP['listas'].".";
  			
  			echo $mensagem; ?></td>
      </tr>
    <?php }while($row_rsP = mysql_fetch_assoc($rsP));?>
    </table>
    <?php }else{?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="100" align="center" valign="middle"><strong>Sem hist&oacute;rico gerado.</strong></td>
      </tr>
    </table>
    <?php } ?></td>
  </tr>
</table>
</body>
</html>
