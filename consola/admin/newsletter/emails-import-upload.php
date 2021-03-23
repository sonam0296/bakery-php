<?php include_once('../inc_pages.php'); ?>
<?php 

$lista = $_REQUEST['lista'];

$contaimg = 1; 
$inserido = 0;

function randomCode($size = '24') {
  $string = '';
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  for ($i = 0; $i < $size; $i++)
    $string .= $characters[mt_rand(0, (strlen($characters) - 1))];  

  $query_rsExists = "SELECT * FROM news_emails WHERE codigo = '$string'";
  $rsExists = DB::getInstance()->prepare($query_rsExists);
  $rsExists->execute();
  $totalRows_rsExists = $rsExists->rowCount();
  DB::close();

  if($totalRows_rsExists == 0)
    return $string;
  else
    return randomCode();
}

if($lista != "" && !empty($_FILES)) {
	$array = explode(",",substr($lista, 0, -1));
	
	foreach($_FILES as $file_name => $file_array) {
		$id_file=date("his").'_'.$contaimg.'_'.rand(0,9999);
		
		switch ($contaimg) {
			case '1': case '2': case '3':    
				$file_dir =  "data";
			break;
		}
		
		if($file_array['size'] > 0) {
			$nome_img = verifica_nome(utf8_decode($file_array['name']));
			$nome_file = $id_file."_".$nome_img;
			@unlink($file_dir.'/'.$_POST['file_db_'.$contaimg]);
		}
		else {
			if($_POST['file_db']) {
				$nome_file = $_POST['file_db'];
			}
			else {
				$nome_file ='';
				@unlink($file_dir.'/'.$_POST['file_db_del']);
			}
		}
				
		if (is_uploaded_file($file_array['tmp_name'])) { move_uploaded_file($file_array['tmp_name'],"$file_dir/$nome_file") or die ("Couldn't copy"); }
		
		//store the name plus index as a string 
		$variableName = 'nome_file' . $contaimg; 
		//the double dollar sign is saying assign $imageName 
		// to the variable that has the name that is in $variableName
		$$variableName = $nome_file; 	
		$contaimg++;							
	} 
		
	if($nome_file1 != "") {
		include '../Classes/PHPExcel/IOFactory.php';
		try {
	    $inputFileType = PHPExcel_IOFactory::identify("data/".$nome_file1);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load("data/".$nome_file1);
		} 
		catch(Exception $e) {
		  die('Error loading file "'.pathinfo("data/".$nome_file1,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$rowData = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);

		$inserido = 1;

		for($row = 2; $row <= $highestRow; $row++) { 
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

	    $email = trim(utf8_decode($rowData['0']['0']));
	    $nome = trim(utf8_decode($rowData['0']['1']));
	    $empresa = trim(utf8_decode($rowData['0']['2']));
	    $cargo = trim(utf8_decode($rowData['0']['3']));
	    $telefone = trim(utf8_decode($rowData['0']['4']));

	    if(($email != "") && strpos($email," ") === false) {
				$query_rsMailer = "SELECT id FROM news_emails WHERE email='".$email."'";
				$rsMailer = DB::getInstance()->query($query_rsMailer);
				$rsMailer->execute();
				$row_rsMailer = $rsMailer->fetch(PDO::FETCH_ASSOC);
				$totalRows_rsMailer = $rsMailer->rowCount();
				
				if($totalRows_rsMailer == 0) {
					$query_rsMaxID = "SELECT MAX(id) FROM news_emails";
					$rsMaxID = DB::getInstance()->query($query_rsMaxID);
					$rsMaxID->execute();
					$row_rsMaxID = $rsMaxID->fetch(PDO::FETCH_ASSOC);
					$totalRows_rsMaxID = $rsMaxID->rowCount();
					
					$id = $row_rsMaxID['MAX(id)'] + 1;
					$codigo = randomCode();
					$data = date('Y-m-d H:i:s');
					
					$query_rsInsert = "INSERT INTO news_emails (id, nome, email, empresa, cargo, telefone, data, visivel, codigo) VALUES ('$id', :nome, :email, :empresa, :cargo, :telefone, :data, '1', :codigo)";
					$rsInsert = DB::getInstance()->prepare($query_rsInsert);
					$rsInsert->bindParam(':nome', $nome, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':email', $email, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':empresa', $empresa, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':cargo', $cargo, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':telefone', $telefone, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':data', $data, PDO::PARAM_STR, 5);
					$rsInsert->bindParam(':codigo', $codigo, PDO::PARAM_STR, 5);
					$rsInsert->execute();
					
					foreach($array as $lista) {
						$query_rsInsert = "INSERT INTO news_emails_listas (email, lista) VALUES ('".$id."', '".$lista."')";
						$rsInsert = DB::getInstance()->prepare($query_rsInsert);
						$rsInsert->execute();
					}		
				}
				else {
					$id = $row_rsMailer['id'];
					 
					foreach($array as $lista) {
						$query_rsEM = "SELECT * FROM news_emails_listas WHERE email='$id' AND lista='$lista'";
						$rsEM = DB::getInstance()->query($query_rsEM);
						$rsEM->execute();
						$row_rsEM = $rsEM->fetch(PDO::FETCH_ASSOC);
						$totalRows_rsEM = $rsEM->rowCount();;	
						
						if($totalRows_rsEM == 0) {
							$query_rsInsert = "INSERT INTO news_emails_listas (email, lista) VALUES ('".$id."','".$lista."')";
							$rsInsert = DB::getInstance()->prepare($query_rsInsert);
							$rsInsert->execute();
						}
					}	
				}

				DB::close();
			}
		}

		@unlink("data/".$nome_file1);
	}
}

echo $inserido;
?>