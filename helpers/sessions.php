<?php
if(!isset($_SESSION)) {
  session_start();
}

function encrypt_decrypt($action, $string, $password=NOME_SITE, $salt='!kQm*fF3pXe1Kbm%9') {
  $output = false;
  $encrypt_method = "AES-256-CBC";
  //$secret_key = 'This is my secret key';
  $secret_iv = $salt;
  // hash
  $key = hash('sha256', $salt.$password, true);
  
  // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
  $iv = substr(hash('sha256', $secret_iv), 0, 16);
  if ($action == 'encrypt' ) {
    $output = base64_encode(openssl_encrypt(serialize($string), $encrypt_method, $key, 1, $iv));
  } else if($action == 'decrypt' ) {
    $output = unserialize(openssl_decrypt(base64_decode($string), $encrypt_method, $key, 1, $iv));
  }
  return $output;
}

function concatenateQuery($query, $id=0){
	$data=date("Y-m-d");
	global $extensao, $row_rsCliente;
	
	$pos_ext = strpos($query, "#extensao#");
	$pos_data = strpos($query, "#data#");
	$pos_id = strpos($query, "#id#");

	if ($pos_ext !== false) $query = str_replace("#extensao#",$extensao,$query);
	if ($pos_data !== false) $query = str_replace("#data#",$data,$query);
	if ($pos_id !== false && $id>0) $query = str_replace("#id#",$id,$query);
	
	return $query;
}

function Search_Array($array, $field, $value){	
	foreach($array as $key => $product){
		if ( $product['info'][$field] === $value ) return $key;
	}
	return false;
}

function geraSessions($id="geral"){
	global $extensao, $Recursos;

	if (!isset($_SESSION)) {
	  session_start();
	}

	$tabelas = "";
	$data=date("Y-m-d");
	$dataHora=date("Y-m-d H:i:s");
	$where = " WHERE geral = 1";

	if($id!="geral"){
		$where = " WHERE id = '$id' OR nome_session LIKE '%$id%'";
	}

	$query_rsSessions = "SELECT * FROM config_sessions".$where;
	$rsSessions = DB::getInstance()->prepare($query_rsSessions);
	$rsSessions->execute();
	$row_rsSessions = $rsSessions->fetchAll(PDO::FETCH_ASSOC);
	$totalRows_rsSessions = $rsSessions->rowCount();
	//DB::close();

	foreach($row_rsSessions as $sessions){
		$tabelas .= $sessions['nome_session']."###".$sessions['query']."###".$sessions['query2']."###".$sessions['query3']."###".$sessions['onlyRow']."###".$sessions['refresh'].";";
	}

	if($tabelas){
		$tabelas = substr($tabelas, 0, -1);
		$tabelas = explode(";", $tabelas);

		foreach($tabelas as $tabela){
			$tabela = explode("###", $tabela);
			
			$nome_tbl = $tabela[0];
			$query_tbl = $tabela[1];
			$query2_tbl = $tabela[2];
			$query3_tbl = $tabela[3];
			$onlyRow = $tabela[4];
			$data_tbl = $tabela[5];
			
			if($query_tbl){			
				$nome_session = strtolower(NOME_SITE."_".$nome_tbl.$extensao);
				$data_session = strtolower(NOME_SITE."_".$nome_tbl.$extensao."_data");
				
				if(!isset($_SESSION[$nome_session]) || (!isset($_SESSION[$data_session]) || $data_tbl!=$_SESSION[$data_session])){					
					$query_tbl = concatenateQuery($query_tbl);

					$query_rsP = $query_tbl;
					$rsP = DB::getInstance()->prepare($query_rsP);
					$rsP->execute();
					$row_rsP = $rsP->fetchAll(PDO::FETCH_ASSOC);
					$totalRows_rsP = $rsP->rowCount();
					//DB::close();
					
					$_SESSION[$nome_session] = array();
					
					if(($totalRows_rsP>1 || ($totalRows_rsP==1 && !$onlyRow) || ($totalRows_rsP==1 && $query2_tbl))){
						if($query2_tbl){
							$categorias_array = array();
							$subcategorias_array = array();

							foreach($row_rsP as $row) {
								$query2_tbl = concatenateQuery($query2_tbl,$row['id']);
								$query_rsP2 = $query2_tbl;
								$rsP2 = DB::getInstance()->prepare($query_rsP2);
								$rsP2->execute();
								$row_rsP2 = $rsP2->fetchAll(PDO::FETCH_ASSOC);
								$totalRows_rsP2 = $rsP2->rowCount();

								//DB::close();
								
								if($totalRows_rsP2>0){
									$subs_array = array();
								
									foreach($row_rsP2 as $row2) {								
										if($query3_tbl){
											$query3_tbl = concatenateQuery($query3_tbl,$row2['id']);
																	
											$query_rsP3 = $query3_tbl;
											$rsP3 = DB::getInstance()->prepare($query_rsP3);
											$rsP3->execute();
											$row_rsP3 = $rsP3->fetchAll(PDO::FETCH_ASSOC);
											$totalRows_rsP3 = $rsP3->rowCount();
											//DB::close();
											
											if($totalRows_rsP3>0){
												$subssubs_array = array();
												foreach($row_rsP3 as $row3) {
													$subssubs_array[$row3["id"]] = $row3;
													$subs_array[$row2["id"]] = array("info"=>$row2,"subs"=>$subssubs_array);
												}									
											}elseif($totalRows_rsP3==1){
												$subs_array[$row2["id"]] = array("info"=>$row2,"subs"=>$row_rsP3);
											}else{
												$subs_array[$row2["id"]] = array("info"=>$row2,"subs"=>'');
											}
											
										}else{
											$subs_array[$row2["id"]] = $row2;
										}
										
										$query3_tbl = $tabela[3];
									}
									
									if($onlyRow){
										$categorias_array = array("info"=>$row,"subs"=>$subs_array);
									}else{
										$categorias_array[$row["id"]] = array("info"=>$row,"subs"=>$subs_array);
									}
									
								}elseif($totalRows_rsP2==1){
									if($onlyRow){
										$categorias_array = array("info"=>$row,"subs"=>$subs_array);
									}else{
										$categorias_array[$row["id"]] = array("info"=>$row,"subs"=>$subs_array);
									}
								}else{
									$categorias_array[$row["id"]] = array("info"=>$row,"subs"=>'');
								}

								$query2_tbl = $tabela[2];
							}
							
							$_SESSION[$nome_session] = encrypt_decrypt('encrypt', $categorias_array);
							$GLOBALS['divs_'.$nome_tbl] = encrypt_decrypt('decrypt', $_SESSION[$nome_session]);						
						}else{
							$conteudo_array = array();
							if($totalRows_rsP>1){
								foreach($row_rsP as $row) {
									$conteudo_array[$row["id"]] = $row;
								}
							}elseif($totalRows_rsP==1){
								foreach($row_rsP as $row) {
									if($onlyRow){
										$conteudo_array = $row;
									}else{
										$conteudo_array[$row["id"]] = $row;
									}
								}
							}
							
							$_SESSION[$nome_session] = encrypt_decrypt('encrypt', $conteudo_array);
							$GLOBALS['divs_'.$nome_tbl] = encrypt_decrypt('decrypt', $_SESSION[$nome_session]);	
						}

					}elseif($totalRows_rsP==1){
						$conteudo_array = array();
						foreach($row_rsP as $row) {
							$conteudo_array = $row;
						}
						
						$_SESSION[$nome_session] = encrypt_decrypt('encrypt', $conteudo_array);
						$GLOBALS['divs_'.$nome_tbl] = encrypt_decrypt('decrypt', $_SESSION[$nome_session]);	
					}
								
					$_SESSION[$data_session] = $data_tbl;
				}else{
					$GLOBALS['divs_'.$nome_tbl] = encrypt_decrypt('decrypt', $_SESSION[$nome_session]);
				}
			}
		}
	}
}

geraSessions();

DB::close();
?>
