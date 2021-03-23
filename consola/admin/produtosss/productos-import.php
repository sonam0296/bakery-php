<?php include_once('../inc_pages.php'); ?>

<?php 


// $query_rsProc = "SELECT id, preco, preco_ant FROM l_pecas_en";
// $rsProc = DB::getInstance()->prepare($query_rsProc);
// $rsProc->execute();
// $all_products = $rsProc->fetchAll();
// $totalRows_rsProc = $rsProc->rowCount();
// $i = 1;
// foreach ($all_products as $value) {
//     // echo "<pre>";
//     // print_r ($value);
//     // echo "</pre>";

//     // if ($i > 3) {
//     //     break;
//     //     exit();
//     // }

//     $preco_new = $value['preco'];
//     $preco_ant_new = $value['preco_ant'];

//     if (empty($value['preco']) || $value['preco'] == 0.00) {
//         // echo "<pre>";
//         // print_r ($i.'--');
//         // echo "</pre>";
//         if (!empty($value['preco_ant']) && $value['preco_ant'] != 0.00) {
//             //print_r ($i.'---');
//             $preco_new = $value['preco_ant'];
//         }
//     }
    
//     if (empty($value['preco_ant']) || $value['preco_ant'] == 0.00) {
//         if (!empty($value['preco']) && $value['preco'] != 0.00) {
//             //print_r ($i.'----');
//             $preco_ant_new = $value['preco'];
//         }
//     }

//     $insertSQL = "UPDATE l_pecas_en SET preco=:preco, preco_ant=:preco_ant WHERE id=:id";
//     $rsInsert = DB::getInstance()->prepare($insertSQL);
//     $rsInsert->bindParam(':preco', $preco_new, PDO::PARAM_STR, 5);    
//     $rsInsert->bindParam(':preco_ant', $preco_ant_new, PDO::PARAM_STR, 5);
//     $rsInsert->bindParam(':id', $value['id'], PDO::PARAM_INT);   
//     $rsInsert->execute();

//     $i++; 
// }
// echo "<pre>";
// print_r ($i);
// echo "</pre>";
// exit();

?>



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

  function categoria_parent_get($categoria_name)
  {
    $query_rsCat = "SELECT id, nome, cat_mae FROM l_categorias_en WHERE nome = '$categoria_name' AND cat_mae=0";
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

  function categoria_get_by_cate_name_and_parent($cate_name,$parent_id)
  {
    $query_rsCat = "SELECT id, nome, cat_mae FROM l_categorias_en WHERE nome = '$cate_name' AND cat_mae= '$parent_id'";
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
    $query_rsBrand = "SELECT id, nome FROM l_marcas_en WHERE nome = '$brand_name'";
    $rsBrand = DB::getInstance()->prepare($query_rsBrand);
    $rsBrand->execute();
    $row_rsBrand = $rsBrand->fetch();
    DB::close();  
    return $row_rsBrand;

  }


 

function import_brand($brand_name)
{

    $insertSQL = "SELECT MAX(id) FROM l_marcas_en";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
    DB::close();
    
    $max_id = $row_rsInsert["MAX(id)"] + 1;
    
    $query_rsLinguas = "SELECT * FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
    DB::close();

    while($row_rsLinguas = $rsLinguas->fetch()) {
      $nome_url = strtolower(verifica_nome($brand_name));
      
      $query_rsProc = "SELECT id FROM l_marcas_".$row_rsLinguas['sufixo']." WHERE url LIKE :url AND id!=:id";
      $rsProc = DB::getInstance()->prepare($query_rsProc);
      $rsProc->bindParam(':url', $nome_url, PDO::PARAM_STR, 5);
      $rsProc->bindParam(':id', $max_id, PDO::PARAM_INT);
      $rsProc->execute();
      $totalRows_rsProc = $rsProc->rowCount();
      
      if($totalRows_rsProc > 0) {
        $nome_url = $nome_url."-".$max_id;
      }

      $insertSQL = "INSERT INTO l_marcas_".$row_rsLinguas["sufixo"]." (id, nome, url, title) VALUES (:id, :nome, :url, :title)";
      $rsInsert = DB::getInstance()->prepare($insertSQL);
      $rsInsert->bindParam(':nome', $brand_name, PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5); 
      $rsInsert->bindParam(':title', $brand_name, PDO::PARAM_STR, 5);
      $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);  
      $rsInsert->execute();
    }

}

function get_filter($filter_name,$categoria)
  {
    $query_rOpcoes = "SELECT * FROM l_filt_opcoes_en WHERE nome = '$filter_name' AND categoria='$categoria'";
    $rsOpcoes = DB::getInstance()->prepare($query_rOpcoes);
    $rsOpcoes->execute();
    $row_rsOpcoes = $rsOpcoes->fetch();
    DB::close();  
    return $row_rsOpcoes;
  }

function import_filter($filter_name,$categoria)
{

    $insertSQL = "SELECT MAX(id) FROM l_filt_opcoes_en";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->execute();
    $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
    
    $max_id = $row_rsInsert["MAX(id)"]+1;
    
    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
    $rsLinguas->execute();
    $totalRows_rsLinguas = $rsLinguas->rowCount();
        
    while($row_rsLinguas = $rsLinguas->fetch()) {   
        $insertSQL = "INSERT INTO l_filt_opcoes_".$row_rsLinguas["sufixo"]." (id, nome, categoria) VALUES (:max_id, :nome, :categoria)";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':nome', $filter_name, PDO::PARAM_STR, 5);    
        $rsInsert->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $rsInsert->bindParam(':max_id', $max_id, PDO::PARAM_INT);
        $rsInsert->execute();
    }

}

function produtos_filter_import($pro_id,$filtro)
{
    $insertSQL2 = "SELECT MAX(id) FROM l_pecas_filtros";
    $rsInsert1 = DB::getInstance()->prepare($insertSQL2);
    $rsInsert1->execute();
    $row_rsInsert1 = $rsInsert1->fetch(PDO::FETCH_ASSOC);

    $max_idfilter = $row_rsInsert1["MAX(id)"]+1;     

    $insertSQL = "INSERT INTO l_pecas_filtros (id, id_peca, id_filtro) VALUES (:max_id_2, :id, :id_filtro)";
    $rsInsert = DB::getInstance()->prepare($insertSQL);
    $rsInsert->bindParam(':max_id_2', $max_idfilter, PDO::PARAM_INT);
    $rsInsert->bindParam(':id', $pro_id, PDO::PARAM_INT);
    $rsInsert->bindParam(':id_filtro', $filtro, PDO::PARAM_INT);  
    $rsInsert->execute();
}


function import_cate($main_cate_name,$parent_cat=0,$main_parent_cate=0)
{
    
    $main_parent_cate = categoria_get($main_parent_cate);
    $get_cate_name = categoria_get($parent_cat);
    
    if (!empty($get_cate_name)) {
        if (!empty($get_cate_name['id'])) {
            $get_cate_id = $get_cate_name['id'];

            if (!empty($main_parent_cate)) {
                $get_main_cate_detail = categoria_get_by_cate_name_and_parent($parent_cat,$main_parent_cate['id']);
                $get_cate_id = $get_main_cate_detail['id'];
            }

        }
        else{
            $get_cate_id = 0;
        }
    }else{
        $get_cate_id = 0;
    }

      $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";
      $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);
      $rsLinguas->execute();
      $row_rsLinguas = $rsLinguas->fetchAll();
      $totalRows_rsLinguas = $rsLinguas->rowCount();

                        
    //if (!empty($sub_cate_name) && !empty($get_cate_name)) {
        $sub_sub_exist = categoria_get($main_cate_name);
        // echo "<pre>";
        // print_r ($sub_sub_exist);
        // echo "</pre>";
        $continue_insert = 1;

        if (!empty($sub_sub_exist)) {
            $continue_insert = 0;
        }

        if (!empty($main_parent_cate)) {
           
            $parent_cat_id = $get_main_cate_detail['id'];
        }

        if (!empty($sub_sub_exist) && !empty($get_cate_id)) {
            $get_all_cate = categoria_get_all($main_cate_name); 
            
            if (!empty($get_all_cate)) {
                
                foreach ($get_all_cate as $key => $value) {
                    $cate_iddd = categoria_get_by_id($value['cat_mae']);
                    
                    if (!empty($main_parent_cate)) {
                        if ($parent_cat_id == $cate_iddd['id']) {
                            $continue_insert = 0;
                            break;
                        }else{
                            $continue_insert = 1;
                        } 
                    }else{
                        if ($cate_iddd['nome'] == $parent_cat) {
                        //echo $parent_cat;
                        $continue_insert = 0;
                            break;
                        }else{
                            $continue_insert = 1;
                        } 
                    }

                    
                }
            }else{

                $continue_insert = 0;
            }
        }


        if ($continue_insert == true) {

            $insertSQL = "SELECT MAX(id) FROM l_categorias_en";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->execute();
            $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
            
            $max_id = $row_rsInsert["MAX(id)"]+1;
           
            foreach ($row_rsLinguas as $linguas) {

                $query_rsCatMae = "SELECT url FROM l_categorias_".$linguas['sufixo']." WHERE id=:categoria";
                $rsCatMae = DB::getInstance()->prepare($query_rsCatMae);
                $rsCatMae->bindParam(':categoria', $get_cate_id, PDO::PARAM_INT);
                $rsCatMae->execute();
                $row_rsCatMae = $rsCatMae->fetch(PDO::FETCH_ASSOC);
                $totalRows_rsCatMae = $rsCatMae->rowCount();
                
                if($totalRows_rsCatMae > 0) {
                    $nome_url=$row_rsCatMae['url']."-";
                } else $nome_url="";
                            
                $nome_url.=strtolower(verifica_nome($main_cate_name));
                
                $query_rsProc = "SELECT * FROM l_categorias_".$linguas['sufixo']." WHERE url like :nome_url AND id!=:max_id";
                $rsProc = DB::getInstance()->prepare($query_rsProc);
                $rsProc->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);
                $rsProc->bindParam(':max_id', $max_id, PDO::PARAM_INT);
                $rsProc->execute();
                $totalRows_rsProc = $rsProc->rowCount();
                
                if($totalRows_rsProc>0) {
                    $nome_url=$nome_url."-".$max_id;
                }

                
                $cat_array = [
                    'max_id' => $max_id,
                    'nome' => $main_cate_name,
                    'categoria' => $get_cate_id,
                    'url' => $nome_url,
                    'title' => $main_cate_name,
                ];

                if (!empty($main_cate_name)) {
                    $sufixo = $linguas['sufixo'];
                    $insertSQL = "INSERT INTO l_categorias_".$sufixo." (id, nome, cat_mae, url, title) VALUES (:max_id, :nome, :categoria, :url, :title)";
                    $rsInsert = DB::getInstance()->prepare($insertSQL);
                    $rsInsert->bindParam(':max_id', $max_id, PDO::PARAM_INT);       
                    $rsInsert->bindParam(':nome', $main_cate_name, PDO::PARAM_STR, 5);
                    $rsInsert->bindParam(':categoria', $get_cate_id, PDO::PARAM_INT, 5);
                    $rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5); 
                    $rsInsert->bindParam(':title', $main_cate_name, PDO::PARAM_STR, 5);
                    $rsInsert->execute(); 
                }
                
                
            }

        }

    //}

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
            
            // Skip the first line
            $csvFile1 = fopen($_FILES['csvfile']['tmp_name'], 'r');
            fgetcsv($csvFile1);

            $i=1;
            while(($line1 = fgetcsv($csvFile1)) !== FALSE){

                break;

                $all_cate = $line1[21];
                $all_cate = explode("|",$all_cate);

                // echo "<pre>";
                // print_r ($all_cate);
                // echo "</pre>";

                $main_cate_name = '';
                $cate_name = '';
                $sub_cate_name = ''; 
                foreach ($all_cate as $first_lavel_cate) {
                    $all_cate_arr = explode(">",$first_lavel_cate);
                    if (count($all_cate_arr) == 1) {
                        $main_cate_name .= trim(str_replace("###",",",$all_cate_arr[0])).';';
                    }else{
                        $main_cate_name .= $all_cate_arr[0].';';
                    }
  
                }

                $main_cate_name = rtrim($main_cate_name,';');
                $cate_name = rtrim($cate_name,';');
                $sub_cate_name = rtrim($sub_cate_name,';');
                
                if($main_cate_name == '0' || empty($main_cate_name)){
                    $main_cate_name = '';
                }

                $main_cate_name = explode(";",$main_cate_name);
                

                foreach ($main_cate_name as $value) {
                    $value = trim($value);
                    if (!empty($value)) {
                        import_cate($value,0);
                    }
                } 

                $i++;
            }
            fclose($csvFile1);

            
            
            $csvFile2 = fopen($_FILES['csvfile']['tmp_name'], 'r');
            fgetcsv($csvFile2);

            $i=1;
            while(($line2 = fgetcsv($csvFile2)) !== FALSE){
                
                break;

                // $main_cate_name = $line2[4];
                // $cate_name = $line2[5];

                $all_cate = $line2[21];
                $all_cate = explode("|",$all_cate);

                $main_cate_name = '';
                $cate_name = '';
                $sub_cate_name = ''; 

                foreach ($all_cate as $first_lavel_cate) {
                    $all_cate_arr = explode(">",$first_lavel_cate);
                    if (count($all_cate_arr) == 1) {
                        $main_cate_name .= trim(str_replace("###",",",$all_cate_arr[0])).';';
                    }else{
                        $main_cate_name .= $all_cate_arr[0].';';
                    }

                    if (count($all_cate_arr) == 2) {
                        //unset($all_cate_arr[0]);
                        //$cate_name .= trim(str_replace("###",",",$all_cate_arr[0])).';';
                        $cate_name .= trim(str_replace("###",",",$all_cate_arr[1])).';';
                    }else{
                        $cate_name .= $all_cate_arr[1].';';
                    }

                    // if (count($all_cate_arr) > 2) {
                    //    unset($all_cate_arr[0]);
                    //    unset($all_cate_arr[1]); 
                    // }
                    
                    // if (count($all_cate_arr) > 2) {
                    //     foreach ($all_cate_arr as $value) {
                    //         $sub_cate_name .= trim(str_replace("###",",",$value)).';';   
                    //     }
                    // }
                }

                $main_cate_name = rtrim($main_cate_name,';');
                $cate_name = rtrim($cate_name,';');
                $sub_cate_name = rtrim($sub_cate_name,';');

                if($main_cate_name == '0' || empty($main_cate_name)){
                    $main_cate_name = '';
                }

                if($cate_name == '0' || empty($cate_name) ){
                    $cate_name = '';
                }

                $main_cate_name = explode(";",$main_cate_name);
                $cate_name = explode(";",$cate_name);
                
                

                foreach ($main_cate_name as $value) {
                    $value = trim($value);
                    if (!empty($value)) {
                        foreach ($cate_name as $value2) {
                            $value2 = trim($value2);
                            import_cate($value2,$value);
                        }
                        
                    }
                }
                    
                         
                $i++;
            }
            fclose($csvFile2);

            

            $csvFile3 = fopen($_FILES['csvfile']['tmp_name'], 'r');
            fgetcsv($csvFile3);

            $i=1;
            while(($line3 = fgetcsv($csvFile3)) !== FALSE){
                
                break;

                $all_cate = $line3[21];
                $all_cate = explode("|",$all_cate);

                $main_cate_name = '';
                $cate_name = '';
                $sub_cate_name = ''; 
                foreach ($all_cate as $first_lavel_cate) {
                    $all_cate_arr = explode(">",$first_lavel_cate);
                    if (count($all_cate_arr) == 1) {
                        $main_cate_name .= trim(str_replace("###",",",$all_cate_arr[0])).';';
                    }else{
                        $cate_name .= $all_cate_arr[0].';';
                    }

                    if (count($all_cate_arr) == 2) {
                        //$cate_name .= trim(str_replace("###",",",$all_cate_arr[0])).';';
                        $cate_name .= trim(str_replace("###",",",$all_cate_arr[1])).';';
                    }else{
                        $cate_name .= $all_cate_arr[1].';';
                    }

                    if (count($all_cate_arr) > 2) {
                       unset($all_cate_arr[0]);
                       unset($all_cate_arr[1]); 
                    }
                    
                    if (count($all_cate_arr) > 2) {
                        foreach ($all_cate_arr as $value) {
                            $sub_cate_name .= trim(str_replace("###",",",$value)).';';   
                        }
                    }
                }

                $main_cate_name = rtrim($main_cate_name,';');
                $cate_name = rtrim($cate_name,';');
                $sub_cate_name = rtrim($sub_cate_name,';');

                    if($main_cate_name == '0' || empty($main_cate_name)){
                        $main_cate_name = '';
                    }

                    if($cate_name == '0' || empty($cate_name) ){
                        $cate_name = '';
                    }

                    if($sub_cate_name == '0' || empty($sub_cate_name)){
                        $sub_cate_name = '';
                    }
                    

                    $main_cate_name = explode(";",$main_cate_name);

                    $cate_name = explode(";",$cate_name);

                    $sub_cate_name = explode(";",$sub_cate_name);

                    foreach ($main_cate_name as $mainvalue) {
                        $mainvalue = trim($mainvalue);
                        foreach ($cate_name as $value) {
                            $value = trim($value);
                            if (!empty($value)) {
                                foreach ($sub_cate_name as $value2) {
                                    $value2 = trim($value2);
                                    import_cate($value2,$value,$mainvalue);
                                }
                                
                            }
                        }  
                    }
                                       

                $i++;
            }
            fclose($csvFile3);

            //exit();

            // Parse data from CSV file line by line

            $csvFile4 = fopen($_FILES['csvfile']['tmp_name'], 'r');
            fgetcsv($csvFile4);

            $i=1;
            while(($line = fgetcsv($csvFile4)) !== FALSE){
                //break;

                    // if ($i >10) {
                    //     exit;
                    // }

                    $ref = $line[2];
                    $imagem = $line[23];
                    $imagem = str_replace("#N/A", " ", $imagem);

                    $temp_image = str_replace("#N/A", " ", $imagem);

                    // $imagem_array = array();
                    // if (!empty($imagem) && $imagem != "#N/A") {
                    //     $imagem_array = explode(";",$imagem);
                    // }

                    //$video_link = $line[2];
                    $nome = str_replace('"', "", $line[3]);
                    $nome = str_replace("#N/A", " ", $nome);

                    // $main_cate_name = str_replace("#N/A", " ", $line[4]);
                    // $cate_name = str_replace("#N/A", " ", $line[5]);
                    // $sub_cate_name = str_replace("#N/A", " ",$line[6]);

                    $all_cate = $line[21];
                    $all_cate = explode("|",$all_cate);

                    // echo "<pre>";
                    // print_r ($all_cate);
                    // echo "</pre>";
                    //exit;

                    $main_cate_name = '';
                    $cate_name = '';
                    $sub_cate_name = ''; 

                    if ($line[21]) {
                     
                        foreach ($all_cate as $first_lavel_cate) {
                            $all_cate_arr = explode(">",$first_lavel_cate);
                            if (count($all_cate_arr) == 1) {
                                $main_cate_name .= trim(str_replace("###",",",$all_cate_arr[0])).';';
                            }else {
                                $main_cate_name .= $all_cate_arr[0].';';
                            }
                            if (count($all_cate_arr) == 2) {
                                //$cate_name .= trim(str_replace("###",",",$all_cate_arr[0])).';';
                                $cate_name .= trim(str_replace("###",",",$all_cate_arr[1])).';';
                            }else{
                                $cate_name .= $all_cate_arr[1];
                            }

                            if (count($all_cate_arr) > 2) {
                               unset($all_cate_arr[0]);
                               unset($all_cate_arr[1]); 
                            }
                            
                            if (count($all_cate_arr) > 2) {
                                foreach ($all_cate_arr as $value) {
                                    $sub_cate_name .= trim(str_replace("###",",",$value)).';';   
                                }
                            }
                        }

                        $main_cate_name = rtrim($main_cate_name,';');
                        $cate_name = rtrim($cate_name,';');
                        $sub_cate_name = rtrim($sub_cate_name,';');


                        if($main_cate_name == '0' || empty($main_cate_name)){
                            $main_cate_name = '';
                        }

                        if($cate_name == '0' || empty($cate_name) ){
                            $cate_name = '';
                        }

                        if($sub_cate_name == '0' || empty($sub_cate_name)){
                            $sub_cate_name = '';
                        }

                    }

                    //$type = str_replace('"', "", $line[5]);
                    // $brand_name = str_replace("'", " ", $line[9]);
                    // $brand_name = str_replace('#N/A', " ",$brand_name);
                    // $brand_name = trim(preg_replace( "/\r|\n/", " ", $brand_name));

                    

                    // $iva = $line[13];
                    // $waight = $line[14];

                    // $waight = $waight/1000;

                    $min_discription = str_replace('"', "", $line[7]);
                    $discription = str_replace('"', "", $line[8]);
                   

                    $cate_check = true;

                    //=====================

                    $cate_array = array();

                    $final_array = array();

                    $main_cate_name_check = $main_cate_name;
                    $cate_name_check = $cate_name;
                    $sub_cate_name_check = $sub_cate_name;

                    $main_cate_name = explode(";",$main_cate_name);
                    $cate_name = explode(";",$cate_name);
                    $sub_cate_name = explode(";",$sub_cate_name);

                    // echo "<pre>";
                    // print_r ($main_cate_name);
                    // echo "</pre>";

                    // echo "<pre>";
                    // print_r ($cate_name);
                    // echo "</pre>";

                    // echo "<pre>";
                    // print_r ($sub_cate_name);
                    // echo "</pre>";

                    if (!empty($main_cate_name_check)) {
                        // LEVEL 1 CATE
                        foreach ($main_cate_name as $value) {
                            $value = trim($value);
                            $cate_array = categoria_parent_get($value);
                            if ($cate_array) { 
                                $final_array[] = $cate_array['id'];   
                            } 
                        }
                    }

                    if (!empty($cate_name_check)) {
                        // LEVEL 2 CATE
                        foreach ($main_cate_name as $value) {
                            $value = trim($value);
                            $cate_array = categoria_parent_get($value);
                            foreach ($cate_name as $value2) {
                                $value2 = trim($value2);
                                $get_main_cate_detail = categoria_get_by_cate_name_and_parent($value2,$cate_array['id']);
                                if (!empty($get_main_cate_detail)) {
                                    $final_array[] = $get_main_cate_detail['id'];
                                }
                            }
                        }
                    }

                    if (!empty($sub_cate_name)) {
                        // LEVEL 3 CATE
                        foreach ($main_cate_name as $value) {
                            $value = trim($value);
                            $cate_array = categoria_parent_get($value);
                            foreach ($cate_name as $value2) {
                                $value2 = trim($value2);
                                $get_main_cate_detail = categoria_get_by_cate_name_and_parent($value2,$cate_array['id']);
                                foreach ($sub_cate_name as $value3) {
                                    $value3 = trim($value3);
                                    $get_main_cate_detail_bottom = categoria_get_by_cate_name_and_parent($value3,$get_main_cate_detail['id']);
                                    if (!empty($get_main_cate_detail_bottom)) {
                                        $final_array[] = $get_main_cate_detail_bottom['id'];
                                    }
                                }
                            }
                        }
                    }

                    //====================

                    // $brand_array = brand_get($brand_name);
                    // if (!empty($brand_array)) {
                    //     $brand_id = $brand_array['id'];
                    //     $brand_id = $brand_id['id'];
                    // }else{
                    //     if (!empty($brand_name)) {
                    //         import_brand($brand_name);
                    //         $brand_array = brand_get($brand_name); 
                    //         $brand_id = $brand_array['id'];
                    //         $brand_id = $brand_id['id'];
                    //     } else {
                    //         $brand_id = 0;  
                    //     } 
                    // }

                    

                    $table = 0;
                    $all = false;

                    if ($_POST['linguas'] == 'all') {
                         $all = true; 
                    }else{
                       $table = 'l_pecas_'.$_POST['linguas'];
                    }

                    $all = true;
                    if ($all == true) {

                        // echo "<pre>";
                        // print_r ($row_rsLinguas);
                        // echo "</pre>";
                        // exit();
                        foreach ($row_rsLinguas as $value) {
 

                            $query_rsProcs = "SELECT id, nome FROM l_categorias_".$value['sufixo']." WHERE nome=:nome AND cate_mae_name=:cate_mae_name";
                            $rsProcs = DB::getInstance()->prepare($query_rsProcs);
                            $rsProcs->bindParam(':nome', $sub_cate_name, PDO::PARAM_STR, 5);
                            $rsProcs->bindParam(':cate_mae_name', $cate_name, PDO::PARAM_STR, 5);
                            $rsProcs->execute();
                            $row_rsProcs = $rsProcs->fetch(PDO::FETCH_ASSOC);
                            //$totalRows_rsProcs = $rsProcs->rowCount();
                   
                            $cate_id = $row_rsProcs['id'];

                            $table = 'l_pecas_'.$value['sufixo'];

                            $insertSQL = "SELECT MAX(id) FROM l_pecas_en";
                            $rsInsert = DB::getInstance()->prepare($insertSQL);
                            $rsInsert->execute();
                            $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
                          
                            $max_id = $row_rsInsert["MAX(id)"]+1;

                            $nome_url = "";

                            if(CATEGORIAS == 1) {
                              $query_rsCatMae = "SELECT url FROM l_categorias_".$value['sufixo']." WHERE id=:categoria";
                              $rsCatMae = DB::getInstance()->prepare($query_rsCatMae);
                              $rsCatMae->bindParam(':categoria', $cate_id, PDO::PARAM_INT);
                              $rsCatMae->execute();
                              $row_rsCatMae = $rsCatMae->fetch(PDO::FETCH_ASSOC);
                              $totalRows_rsCatMae = $rsCatMae->rowCount();
                              
                              if($totalRows_rsCatMae > 0) {
                                $nome_url .= $row_rsCatMae['url']."-";
                              }
                            }


                            $get_url_nome = preg_replace( "/\r|\n/", "-", $nome );
                            $get_url_nome = str_replace('', '-', $get_url_nome);
                            $nome_url .= strtolower(verifica_nome($get_url_nome));

                            $nome = preg_replace( "/\r|\n/", "", $nome );

                            $query_rsProc = "SELECT id FROM l_pecas_".$value['sufixo']." WHERE url LIKE :nome_url AND id!=:max_id";
                            $rsProc = DB::getInstance()->prepare($query_rsProc);
                            $rsProc->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);
                            $rsProc->bindParam(':max_id', $max_id, PDO::PARAM_INT);
                            $rsProc->execute();
                            $totalRows_rsProc = $rsProc->rowCount();
                            
                            if($totalRows_rsProc > 0) {
                              $nome_url = $nome_url."-".$max_id;
                            }

                            $imagem_array_new = array();
                            foreach ($imagem_array as $value) {
                                $value = trim($value," ");
                                if (!empty($value)) {
                                    $imagem_array_new[] = $value;
                                }
                            }

                            if (!empty($imagem_array_new)) {
                                $imagem = $imagem_array_new[0];
                            }else {
                                $imagem = "";
                            }

                            $preco = $line[20];
                            $preco_ant = $line[19];

                            $stock = $line[13];

                            $pro_id = $line[0];

                            $prepare =  $line[24];
                            $product_enquiry =  $line[40];

                           // echo "<pre>";
                           // print_r ($final_array);
                           // echo "</pre>";

                           $insertSQL = '';
                           $insertSQL = 'INSERT INTO '.$table.' (id, ref, nome, short_descricao, descricao, stock, preco, preco_ant, temp_image, visivel, url,pro_id,prepare,enquiry_type) VALUES ("'.$max_id.'", "'.$ref.'", "'.mb_convert_encoding($nome,"HTML-ENTITIES","ISO-8859-1").'", "'.mb_convert_encoding($min_discription,"HTML-ENTITIES","ISO-8859-1").'", "'.mb_convert_encoding($discription,"HTML-ENTITIES","ISO-8859-1").'", "'.$stock.'", "'.$preco.'", "'.$preco_ant.'", "'.$temp_image.'",1,"'.$nome_url.'","'.$pro_id.'","'.$prepare.'","'.$product_enquiry.'")';
                           // echo "<pre>";
                           // print_r ($insertSQL);
                           // echo "</pre>";

                            //exit();
                           $rsInsert = DB::getInstance()->prepare($insertSQL);
                           $rsInsert->execute();
                           $insert_id = DB::getInstance()->lastInsertId();
                           DB::close();

                           $insert_id = $max_id;
                           //  echo "<pre>";
                           // print_r ($insert_id);
                           // echo "</pre>";

                           //exit();
                            
                        }
                        
                        // Filter
                        // $color = str_replace('"', "", $line[10]);
                        // $theme = str_replace('"', "", $line[11]);

                        // if (!empty($color)) {
                        //     $filter_array_color = get_filter($color,1);
                        //     if (!empty($filter_array_color)) {
                        //         $color_id = $filter_array_color['id'];
                        //     }else{
                        //         if (!empty($color)) {
                        //             import_filter($color,1);
                        //             $filter_array_color = get_filter($brand_name,1);
                        //             $color_id = $filter_array_color['id'];
                        //         } else {
                        //             $color_id = 0;  
                        //         } 
                        //     }

                            
                        //     produtos_filter_import($insert_id,$color_id);
                        // }

                        // if (!empty($theme)) {
                        //     $filter_array_theme = get_filter($theme,2);
                        //     if (!empty($filter_array_theme)) {
                        //         $theme_id = $filter_array_theme['id'];
                        //     }else{
                        //         if (!empty($theme)) {
                        //             import_filter($theme,2);
                        //             $filter_array_theme = get_filter($theme,2); 
                        //             $theme_id = $filter_array_theme['id'];
                        //         } else {
                        //             $theme_id = 0;  
                        //         } 
                        //     }
                            
                        //     produtos_filter_import($insert_id,$theme_id);
                        // }


                    }else{


                    }
                    // echo "<pre>";
                    // print_r ($final_array);
                    // echo "</pre>";

                    foreach ($final_array as $value) {

                        $insertSQL = "SELECT MAX(id) FROM l_pecas_categorias";
                        $rsInsert = DB::getInstance()->prepare($insertSQL);
                        $rsInsert->execute();
                        $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);                        
                        $max_id_2 = $row_rsInsert["MAX(id)"] + 1;     
                        
                        $insertSQL = "INSERT INTO l_pecas_categorias (id, id_peca, id_categoria) VALUES (:max_id_2, :id_peca, :categoria)";
                        $rsInsert = DB::getInstance()->prepare($insertSQL);
                        $rsInsert->bindParam(':id_peca', $insert_id, PDO::PARAM_INT);
                        $rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);  
                        $rsInsert->bindParam(':categoria', $value, PDO::PARAM_INT);  
                        $rsInsert->execute();
                    }

                    //$video_link = $line[2];

                    // $visivel = 1;
                    // $type = 1;

                    // $imagem_array_new = array();
                    // foreach ($imagem_array as $value) {
                    //     $value = trim($value," ");
                    //     if (!empty($value)) {
                    //         $imagem_array_new[] = $value;
                    //     }
                    // }

                    // if (!empty($imagem_array_new)) {
                    //    $imagem = ""; 
                    //    foreach ($imagem_array_new as $imagem) {
                    //         if (!empty($imagem)) {
                    //             $insertImageSQL = "INSERT INTO l_pecas_imagens (id_peca, imagem1, imagem2 , imagem3, imagem4, tipo, visivel) VALUES ('".$insert_id."','".$imagem."', '".$imagem."', '".$imagem."', '".$imagem."','".$type."','".$visivel."')"; 
                    //             $rsInsertImg = DB::getInstance()->prepare($insertImageSQL);
                    //             $rsInsertImg->execute();
                    //         }  
                    //     }
                    // }

                    // $type = 2;
                    // if (!empty($video_link)) {
                    //     $video_link = explode("/", $video_link);
                       
                    //     $video_code = end($video_link);
                        
                    //     $video_link = "https://www.youtube.com/embed/".$video_code;

                    //     $insertVideoSQL = "INSERT INTO l_pecas_imagens (id_peca, tipo, video , visivel) VALUES ('".$insert_id."','".$type."', '".$video_link."', '".$visivel."')"; 
                    //     $rsInsertVideo = DB::getInstance()->prepare($insertVideoSQL);
                    //     $rsInsertVideo->execute();
                    // }


                $i++;

            }
           
            // Close opened CSV file
            fclose($csvFile4);

            echo "<pre>";
            print_r ('product-import');
            echo "</pre>";
            exit();
            
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