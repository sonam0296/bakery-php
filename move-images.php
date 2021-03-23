<?php require_once('Connections/connADMIN.php'); ?>
<?php 
    
    $query_rsProc = "SELECT id, temp_image FROM l_pecas_en";
    $rsProc = DB::getInstance()->prepare($query_rsProc);
    $rsProc->execute();
    $all_products = $rsProc->fetchAll();
    $totalRows_rsProc = $rsProc->rowCount();
    // echo "<pre>";
    // print_r ($all_products);
    // echo "</pre>";
    //exit();

    exit;
   
    $uploadsImage = 0;
    $uploadsImage_error = 0;
    foreach ($all_products as $key => $images) {
        
    $image = $images['temp_image'];
    $product_id = $images['id'];
    // echo "<pre>";
    // print_r ($product_id);
    // echo "</pre>";
    //$image = 'imgs/move-images/t_kab130-12.jpg, imgs/move-images/t_kab130-13.jpg';

    $images_arr = array_map('trim', explode('|', $image));

    $new_images_arr = array();

    // echo "<pre>";
    // print_r ($images_arr);
    // echo "</pre>";


    if (!empty($image) && !empty($images_arr)) {

        $img_c = 1;
        foreach ($images_arr as $value) {
            $get_image_name = explode('/', $value);
            $get_image_name = end($get_image_name);
            // echo "<pre>";
            // print_r ($get_image_name);
            // echo "</pre>";

            if ($img_c == 1) {
                $insertSQL = "UPDATE l_pecas_en SET imagem1=:imagem1, imagem2=:imagem2, imagem3=:imagem3, imagem4=:imagem4 WHERE id=:id";
                $rsInsert = DB::getInstance()->prepare($insertSQL);
                
                $rsInsert->bindParam(':imagem1', $get_image_name, PDO::PARAM_STR, 5);  
                $rsInsert->bindParam(':imagem2', $get_image_name, PDO::PARAM_STR, 5);
                $rsInsert->bindParam(':imagem3', $get_image_name, PDO::PARAM_STR, 5);
                $rsInsert->bindParam(':imagem4', $get_image_name, PDO::PARAM_STR, 5);    

                $rsInsert->bindParam(':id', $product_id, PDO::PARAM_INT);   
                $rsInsert->execute();
            }

            $insertSQL = "SELECT MAX(id) FROM l_pecas_imagens";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->execute();
            $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
            
            $max_id_2 = $row_rsInsert["MAX(id)"]+1;         
            
            $insertSQL = "INSERT INTO l_pecas_imagens (id, id_peca, imagem1, imagem2, imagem3, imagem4) VALUES (:max_id_2, :id_prod, :imagem1, :imagem2, :imagem3, :imagem4)";
            $rsInsert = DB::getInstance()->prepare($insertSQL);
            $rsInsert->bindParam(':imagem1', $get_image_name, PDO::PARAM_STR, 5);  
            $rsInsert->bindParam(':imagem2', $get_image_name, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':imagem3', $get_image_name, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':imagem4', $get_image_name, PDO::PARAM_STR, 5);
            $rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);
            $rsInsert->bindParam(':id_prod', $product_id, PDO::PARAM_INT);
            $rsInsert->execute();

            $img_c++;
        }

        //exit;

        $uploadsImage++;
    }else {
       $uploadsImage_error++; 
    }


    //exit();
        // foreach ($images_arr as $value) {
        //     // echo "<pre>";
        //     // print_r ($value);
        //     // echo "</pre>";
        //     $text = strstr($value, 'uploads');
        //     $text = str_replace("/", '\\', $text);
        //     $text = 'images_bkp\\'.$text;
        //     // echo "<pre>";
        //     // print_r ($text);
        //     // echo "</pre>";
            
        //     $new_images_arr[] = str_replace("/", '\\', $text);
        //     // echo "<pre>";
        //     // print_r ($new_images_arr);
        //     // echo "</pre>";
            

        //     foreach ($new_images_arr as $valuenew) {
        //         //$get_image_name[] = explode('/', $valuenew);
        //         //$image_name = end($get_image_name);
        //         // echo "<pre>";
        //         // print_r ($valuenew);
        //         // echo "</pre>";
        //         if (file_exists($valuenew)) {
        //            $pathinfo = pathinfo($valuenew);
        //             $dest_path = str_replace($pathinfo['dirname'], 'imgs/move-images/products', $valuenew);
        //             $dest_path = str_replace("/", '\\', $dest_path);
        //             copy($valuenew, $dest_path); 

        //             $uploadsImage++;
        //         }else{
        //             // echo "<pre>";
        //             // print_r ('$variable');
        //             // echo "</pre>";
        //             $uploadsImage_error++;
        //         }
                
        //     }
        //     //exit;
        // }

    }

    echo "<pre>";
    print_r ('uploadsImage_error-'.$uploadsImage);
    echo "</pre>";
    echo "<pre>";
    print_r ('uploadsImage_error-'.$uploadsImage_error);
    echo "</pre>";
    // $pic="somepic.jpg";
    // copy_files($pic,'products');

    // echo "<pre>";
    // print_r ($new_images_arr);
    // echo "</pre>";

exit();

?>