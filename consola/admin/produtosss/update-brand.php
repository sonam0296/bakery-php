<?php include_once('../inc_pages.php'); ?>

<?php   
  $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
  $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
  $rsLinguas->execute();
  $row_rsLinguas = $rsLinguas->fetchAll();
  $totalRows_rsLinguas = $rsLinguas->rowCount();


  function categoria_get($categoria_name)
  {
    $query_rsCat = "SELECT id, nome, cat_mae FROM l_categorias_en WHERE nome = '$categoria_name'";
    $rsCat = DB::getInstance()->prepare($query_rsCat);
    $rsCat->execute();
    $row_rsCat = $rsCat->fetch();
    DB::close();   
    return $row_rsCat;
  }

  function categoria_get_by_id($id)
  {
    $query_rsCat = "SELECT id, nome, cat_mae FROM l_categorias_en WHERE id = '$id'";
    $rsCat = DB::getInstance()->prepare($query_rsCat);
    $rsCat->execute();
    $row_rsCat = $rsCat->fetch();
    DB::close();   
    return $row_rsCat;
  }

  function categoria_get_all($categoria_name)
  {
    $query_rsCat = "SELECT id, nome, cat_mae FROM l_categorias_en WHERE nome = '$categoria_name'";
    $rsCat = DB::getInstance()->prepare($query_rsCat);
    $rsCat->execute();
    $row_rsCat = $rsCat->fetchAll();
    DB::close();   
    return $row_rsCat;
  }

  function brand_get($brand_name)
  {
    $query_rsBrand = "SELECT id, nome FROM l_marcas_pt WHERE nome = '$brand_name'";
    $rsBrand = DB::getInstance()->prepare($query_rsBrand);
    $rsBrand->execute();
    $row_rsBrand = $rsBrand->fetch();
    DB::close();  
    return $row_rsBrand;
  }


    

?>

<?php 
    
    if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['csvfile']['name']) && in_array($_FILES['csvfile']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['csvfile']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode

            // Parse data from CSV file line by line

            $csvFile4 = fopen($_FILES['csvfile']['tmp_name'], 'r');
            fgetcsv($csvFile4);

            $brand_name_arr = array();
            $i=1;
            while(($line = fgetcsv($csvFile4)) !== FALSE){
                //break;
                if ($i < 1) {
                   $i++; 
                   continue;  
                }   


                    $ref = $line[0];
                    $imagem = $line[1];

                    $main_cate_name = $line[2];
                    $cate_name = $line[3];
                    $sub_cate_name = $line[4];

                    //$type = str_replace('"', "", $line[5]);
                    $nome = str_replace('"', "", $line[5]);
                    $brand_name = str_replace("'", " ", $line[6]);
                    $brand_name = trim(preg_replace( "/\r|\n/", " ", $brand_name));

                    $descricao = str_replace('"', "", $line[7]);

                    $composition_per_capsule = str_replace('"', "", $line[8]);

                    $modo_de_tomar = str_replace('"', "", $line[9]);

                    $detalhes_advertencias = str_replace('"', "", $line[10]);

                    $stock = $line[14];
                    $waight = $line[15];
                    $preco = $line[16];
                    $iva = $line[17];

                    $promocao = $line[18];

                    $cate_check = true;
                    

                    $brand_array = brand_get($brand_name);

                    if (empty($brand_array)) {
                      continue;
                    }

                    // if (empty($brand_array)) {
                    //     echo "<pre>";
                    //     print_r ($brand_name);
                    //     echo $i;
                    //     echo "</pre>";
                    // }else {
                    //   echo "<pre>";
                    //   //print_r ($brand_array);
                    //   echo "</pre>";
                    // }
                    

                    if (!empty($brand_array)) {
                        $brand_id = $brand_array['id'];
                        // echo "<pre>";
                        // print_r ($brand_array);
                        // echo "</pre>";
                      $brand_id = $brand_array['id'];
                    }else{
                        $brand_id = 0;
                    }


                    $table = 0;
                    $all = false;

                    if ($_POST['linguas'] == 'all') {
                         $all = true; 
                    }else{
                       $table = 'l_pecas_'.$_POST['linguas'];
                    }

                    $all = true;
                    if ($all == true) {

                        foreach ($row_rsLinguas as $value) {
                          if ($value['sufixo'] == 'en') {
                              //continue;
                          }
                            $nome = preg_replace( "/\r|\n/", "", $nome );
                            $table = 'l_pecas_'.$value['sufixo'];
                            $nome = mb_convert_encoding($nome,"HTML-ENTITIES","UTF-8");
                            $insertSQL = "SELECT id FROM l_pecas_en WHERE nome='$nome'";
                            $rsInsert = DB::getInstance()->prepare($insertSQL);
                            $rsInsert->execute();
                            //$row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
                            $row_rsInsert = $rsInsert->fetchAll();
                            if (empty($row_rsInsert)) {
                               // echo "<pre>";
                               // print_r ($nome);
                               // echo "</pre>";
                               //$i++;
                            }else{
                              if (count($row_rsInsert) == 1) {
                                // echo "<pre>";
                                // print_r ($row_rsInsert);
                                // echo "</pre>";
                                
                                $max_id = $row_rsInsert[0]['id'];

                                if (empty($brand_id)) {
                                  //$i++;
                                }
                                echo "<pre>";
                                if ($brand_id == 6) {
                                  //$i++;
                                }
                                echo "</pre>";
                                $query_rsUpdate = "UPDATE ".$table." SET marca='$brand_id' WHERE id='$max_id'";
                                $rsUpdate = DB::getInstance()->prepare($query_rsUpdate); 

                                // echo "<pre>";
                                // print_r ($rsUpdate);
                                // echo "</pre>";
                                //$rsUpdate->execute();
                                $i++;
                                
                              }else {
                               /* echo "<pre>";
                                print_r ($row_rsInsert);
                                echo "</pre>";*/
                                //$i++;
                              }
                            } 
                            $max_id = $row_rsInsert["id"];

                             
                            // $rsUpdate->execute();

                           // $insertSQL = '';
                           // $insertSQL = 'INSERT INTO '.$table.' (id,ref, nome, categoria, marca, descricao, composition_per_capsule, modo_de_tomar, detalhes_advertencias, preco, iva, imagem1, imagem2, imagem3, imagem4, visivel, url) VALUES ("'.$i.'", "'.$ref.'", "'.mb_convert_encoding($nome,"HTML-ENTITIES","UTF-8").'", "'.$cate_id.'", "'.$brand_id.'", "'.mb_convert_encoding($descricao,"HTML-ENTITIES","UTF-8").'", "'.mb_convert_encoding($composition_per_capsule,"HTML-ENTITIES","UTF-8").'", "'.mb_convert_encoding($modo_de_tomar,"HTML-ENTITIES","UTF-8").'", "'.mb_convert_encoding($detalhes_advertencias,"HTML-ENTITIES","UTF-8").'", "'.$preco.'",  "'.$iva.'", "'.$imagem.'" , "'.$imagem.'" , "'.$imagem.'", "'.$imagem.'",1,"'.$nome_url.'")';
                           // // echo "<pre>";
                           // // print_r ($insertSQL);
                           // // echo "</pre>";
                           // //  exit();
                           // $rsInsert = DB::getInstance()->prepare($insertSQL);
                           // $rsInsert->execute();
                           // $insert_id = DB::getInstance()->lastInsertId();
                           // DB::close();

                        }
                        

                    }else{


                    }

                    $visivel = 1;
                    // $insertImageSQL = "INSERT INTO l_pecas_imagens (id_peca, imagem1, imagem2 , imagem3, imagem4, visivel) VALUES ('".$insert_id."','".$imagem."', '".$imagem."', '".$imagem."', '".$imagem."','".$visivel."')"; 
                    //   $rsInsertImg = DB::getInstance()->prepare($insertImageSQL);
                    //   $rsInsertImg->execute();

                

            }
            echo "<pre>";
            print_r ($i);
            echo "</pre>";
            exit();
            // Close opened CSV file
            fclose($csvFile4);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: produtos.php".$qstring);


 ?>