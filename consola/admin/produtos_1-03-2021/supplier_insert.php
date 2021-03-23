<?php include_once('../inc_pages.php'); ?>
<?php

   $name = json_decode($_POST["name"]);
//insert.php
 echo $_POST["data-name"];
 echo $max_id_2;
 die();

$data = array(
 ':name'  => $_POST["name"],
 ':last_name'  => $_POST["last_name"]
); 

$query = "INSERT INTO l_pecas_supplier (product_id, supplier_id, name, price, primery,status) VALUES (:name, :last_name)";

$statement = DB::getInstance()->prepare($query);

if($statement->execute($data))
{
 $output = array(
  'name' => $_POST['name'],
  'last_name'  => $_POST['last_name']
 );

 echo json_encode($output);
}

?>