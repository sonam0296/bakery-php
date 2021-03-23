<?php require_once('../Connections/connMicas.php'); ?>
<? include ("header.php"); ?>
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


function verifica_nome($nome)
{
	$strlogin = $nome;
	$caracteres = array('º','ª','"',',',';','/','<','>',':','?','~','^',']','}','´','`','[','{','=','+','-',')','\\','(','*','&','¨','%','$','#','@','!','|','à','è','ì','ò','ù','â','ê','î','ô','û','ä','ë','ï','ö','ü','á','é','í','ó','ú','ã','õ','À','È','Ì','Ò','Ù','Â','Ê','Î','Ô','Û','Ä','Ë','Ï','Ö','Ü','Á','É','Í','Ó','Ú','Ã','Õ','ç','Ç',' ', '\'');
	
	for ($i = 0;$i<count($caracteres);$i++){
		
		if($caracteres[$i]=="á" || $caracteres[$i]=="à" || $caracteres[$i]=="Á" || $caracteres[$i]=="À" || $caracteres[$i]=="ã" || $caracteres[$i]=="Ã" || $caracteres[$i]=="â" || $caracteres[$i]=="Â" ){
			$strlogin=str_replace($caracteres[$i], "a", $strlogin);
		}elseif($caracteres[$i]=="ó" || $caracteres[$i]=="ò" || $caracteres[$i]=="Ó" || $caracteres[$i]=="Ò" || $caracteres[$i]=="õ" || $caracteres[$i]=="Õ" || $caracteres[$i]=="ô" || $caracteres[$i]=="Ô"){
			$strlogin=str_replace($caracteres[$i], "o", $strlogin);
		}elseif($caracteres[$i]=="é" || $caracteres[$i]=="É" || $caracteres[$i]=="ê" || $caracteres[$i]=="Ê"){
			$strlogin=str_replace($caracteres[$i], "e", $strlogin);
		}elseif($caracteres[$i]=="ç" || $caracteres[$i]=="Ç"){
			$strlogin=str_replace($caracteres[$i], "c", $strlogin);
		}elseif($caracteres[$i]=="í" || $caracteres[$i]=="Í"){
			$strlogin=str_replace($caracteres[$i], "i", $strlogin);
		}elseif($caracteres[$i]=="ú" || $caracteres[$i]=="Ú"){
			$strlogin=str_replace($caracteres[$i], "u", $strlogin);
		}else{
			$strlogin=str_replace($caracteres[$i], "", $strlogin);
		}

	}
	
	return $strlogin;

}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );



	$imgs_dir = "data";
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





	
	/*mysql_select_db($database_connMicas, $connMicas);
	$query_rsFTP = "SELECT * FROM micas_ftp";
	$rsFTP = mysql_query($query_rsFTP, $connMicas) or die(mysql_error());
	$row_rsFTP = mysql_fetch_assoc($rsFTP);
	$totalRows_rsFTP = mysql_num_rows($rsFTP);


	$date = date ("YmdHis");
	$temp_file =  $date . "-" . $file_name;
	
	$ftp_server = $row_rsFTP['server'];
	$ftp_user_name = $row_rsFTP['user'];
	$ftp_user_pass = $row_rsFTP['pass'];
	
	$dest_file = "/public_html/consola/data/" . $temp_file;
	$location_file= "/public_html/consola/data/" . $temp_file;
	
	$conn_id = ftp_connect($ftp_server);
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	$upload = ftp_put($conn_id, $dest_file, $file, FTP_BINARY);
	$filename = $location_file;*/



//**APAGA PRODUTOS?
//mysql_select_db($database_connMicas, $connMicas); 
//	$sql_del = "delete from micas_clientes";
//mysql_query($sql_del, $connMicas) or die(mysql_error());

chmod("data/".$nome_file1, 0777);


require_once 'Excel/reader.php';

$reader = new Spreadsheet_Excel_Reader();
$reader->setOutputEncoding("UTF-8");
$reader->read("data/".$nome_file1);
$tudoo="";
for ($i = 1; $i <= $reader->sheets[0]["numRows"]; $i++){	
$tudoo="";

	for ($j = 1; $j <= $reader->sheets[0]["numCols"]; $j++){
		//$tudoo=$tudoo.$reader->sheets[0]["cells"][$i][$j]."\";";
		$tudoo.=$reader->sheets[0]["cells"][$i][$j].";;";
	}
	list($ref,$produto,$pvp1,$descricao,$peso, $imposto, $imagem, $imagem2, $imagem3, $imagem4, $ficheiro, $categoria, $stock, $marca) = explode(";;", $tudoo);
	
	if(($ref!="INT CODE") and ($ref!="")){
	
				
				
				echo $ref;
				echo"<br>";
				echo $produto;
				echo"<br>";




	
	
	/*	if($ordem=="")
				$ordem=99;
				
	
	mysql_select_db($database_connMicas, $connMicas);
	$query_rsP = "SELECT * FROM micas_produtos WHERE ref='$ref'";
	$rsP = mysql_query($query_rsP, $connMicas) or die(mysql_error());
	$row_rsP = mysql_fetch_assoc($rsP);
	$totalRows_rsP = mysql_num_rows($rsP);
	
	if($totalRows_rsP == 0) {	 // SE NAO EXISTIR 	UM PRODUTO COM ESTA REFERENCIA INSERE
				
		mysql_select_db($database_connMicas, $connMicas);
		$insert_data1 = "insert into micas_produtos values('','$produto','$ref','$descricao','','$pvp1','','','','','$data','$imagem','$imagem2','$imagem3','$imagem4','$imagem5','$categoria','$marca','$ordem','1','0','0','0','0','0','$stock','0','')";
		mysql_query($insert_data1, $connMicas) or die(mysql_error());
			
			
			
			
			
			
	}else{ // SE EXISTIR FAZ A ACTUALIZAÇÃO DO NOME E DA CATEGORIA
			$stock1=$stock+$row_rsP['stock'];
	
			mysql_select_db($database_connMicas, $connMicas);
		 	$update_data = "UPDATE micas_produtos SET  nome='$produto', preco='$pvp1', categoria='$categoria', imagem='$imagem', stock='$stock', marca='$marca', descricao='$descricao' WHERE ref='$ref'";
			mysql_query($update_data , $connMicas) or die(mysql_error());
		}*/
	}
}
	ftp_close($conn_id);

 ?>
<!--<META HTTP-EQUIV="refresh" CONTENT="0; URL=produtos.php">	
--><? } ?>
<table width="920" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="876"><table width="876" height="30" border="0" cellpadding="0" cellspacing="0" background="imgs/bgs/bg-menu.gif">
      <tr>
        <td width="20">&nbsp;</td>
        <td><table border="0" cellpadding="6" cellspacing="0" class="menu">
          <tr>
            <td><a href="banners.php">BANNER</a></td>
            <td><a href="categorias.php">CATEGORIAS</a></td>
                          <td><a href="marcas.php">MARCAS</a></td>
            <td><a href="descontos.php">DESCONTOS</a></td>
            <td class="menu-marcado">PRODUTOS</td>
            <td><a href="cheques.php">CHEQUES PRENDA</a></td>
            <td ><a href="clientes.php">CLIENTES</a></td>
            <td><a href="encomendas.php">ENCOMENDAS</a></td>
            <td><a href="portes.php">PORTES</a></td>
            <td><a href="ftp.php">FTP</a></td>
            <td><a href="dicas.php">DICAS</a></td>
            <td><a href="quem_somos.php">OUTROS</a></td>
            <td><a href="noticias.php">NOTÍCIAS</a></td>
          </tr>
        </table></td>
      </tr>
    </table>
      <table width="876" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="imgs/boxing/10x10t.gif" width="876" height="1" /></td>
        </tr>
      </table></td>
    <td width="1"><img src="imgs/boxing/10x10t.gif" width="1" height="30" /></td>
    <td width="43" valign="top"><a href="logout.php"><img src="imgs/bts/sair.gif" width="43" height="30" border="0" /></a></td>
  </tr>
</table>
<table width="920" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="imgs/boxing/10x10t.gif" width="920" height="1" /></td>
              </tr>
            </table>
            <table width="920" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="imgs/boxing/10x10t.gif" width="920" height="1" /></td>
              </tr>
            </table>
            <table width="920" height="30" border="0" cellpadding="0" cellspacing="0" background="imgs/bgs/bg-titulos.gif">
            <tr>
              <td width="20">&nbsp;</td>
              <td width="630" class="titulos-gr">UPLOAD DE PRODUTOS</td>
              <td width="250" align="right" class="titulos-gr">&nbsp;</td>
              <td width="20">&nbsp;</td>
            </tr>
          </table>
            <table width="920" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="imgs/boxing/10x10t.gif" width="920" height="1" /></td>
              </tr>
            </table>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>"  enctype="multipart/form-data">
            <table width="920" border="0" cellspacing="1" cellpadding="5">
                     <tr>
                        <td width="30" height="30" align="center" valign="middle" bgcolor="#CCCCCC" class="titulos-gr">&nbsp;</td>
                        <td width="220" height="30" bgcolor="#CCCCCC" class="titulostxt-gerais">FICHEIRO  (.xls)</td>
                       <td width="656" bgcolor="#EDEDED"><input name="file" type="file" class="cx-insert" /></td>
              </tr>
                     </table>
            <table width="920" border="0" cellspacing="1" cellpadding="5">
              <tr>
                <td width="257" height="30" align="right" valign="middle" bgcolor="#CCCCCC"><a href="produtos.php" ><img src="imgs/bts/voltar.gif" width="75" height="21" border="0" /></a></td>
                <td width="657" valign="middle" bgcolor="#CCCCCC"><input type="image" name="imageField2" src="imgs/bts/inserir.gif" /></td>
              </tr>
            </table>
        <input type="hidden" name="MM_update" value="form1">
        </form>
          <table width="920" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="imgs/boxing/10x10t.gif" width="920" height="1" /></td>
            </tr>
          </table>
		
          </td>
          <td width="20" background="imgs/boxing/right.gif">&nbsp;</td>
        </tr>
      </table>
      <table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><img src="imgs/boxing/end.gif" width="960" height="20" /></td>
        </tr>
      </table>
      <table width="960" height="20" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
