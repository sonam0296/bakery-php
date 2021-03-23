<?php include_once('../inc_pages.php'); ?>

<?php 



$menu_sel='ec_produtos_produtos';

$menu_sub_sel='';



$tab_sel=1;

if($_GET['tab_sel'] > 0) $tab_sel=$_GET['tab_sel'];



$id=$_GET['id'];





if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "produtos_form")) {

	$manter = $_POST['manter'];

	$tab_sel = $_REQUEST['tab_sel'];	



	$query_rsP = "SELECT * FROM l_pecas_en WHERE id=:id";

	$rsP = DB::getInstance()->prepare($query_rsP);

	$rsP->bindParam(':id', $id, PDO::PARAM_INT);

	$rsP->execute();

	$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

	$totalRows_rsP = $rsP->rowCount();



  $query_rsRole ="SELECT * FROM roll WHERE visivel = 1";

  $rsRole = DB::getInstance()->prepare($query_rsRole);

  $rsRole->execute();

  $totalRows_rsRoll = $rsRole->fetchAll();







  if($_POST['nome'] != '' && ($_POST['categoria'] != '' || CATEGORIAS == 2) && $tab_sel == 1) {

		//Só atualiza o URL se a checkbox for selecionada (para não perder os URL's personalizados)

    if(isset($_POST['atualizar_url'])) {

      $nome_url = "";



    	if(CATEGORIAS == 1) {

    		$query_rsCatMae = "SELECT url FROM l_categorias".$extensao." WHERE id=:categoria";

        $rsCatMae = DB::getInstance()->prepare($query_rsCatMae);

        $rsCatMae->bindParam(':categoria', $_POST['categoria'], PDO::PARAM_INT);

        $rsCatMae->execute();

        $row_rsCatMae = $rsCatMae->fetch(PDO::FETCH_ASSOC);

        $totalRows_rsCatMae = $rsCatMae->rowCount();

        

        if($totalRows_rsCatMae > 0) {

          $nome_url .= $row_rsCatMae['url']."-";

        }

    	}

      

      $nome_url .= strtolower(verifica_nome($_POST['nome']));   		

			

			$query_rsProc = "SELECT id FROM l_pecas".$extensao." WHERE url like :nome_url AND id!=:id";

			$rsProc = DB::getInstance()->prepare($query_rsProc);

			$rsProc->bindParam(':id', $id, PDO::PARAM_INT);

      $rsProc->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);

			$rsProc->execute();

			$totalRows_rsProc = $rsProc->rowCount();

			

			if($totalRows_rsProc > 0) {

				$nome_url = $nome_url."-".$id;

			}	

			

			//REDIRECT 301

			if($row_rsP['url'] != $nome_url) redirectURL($row_rsP['url'], $nome_url, substr($extensao,1));

    }

    else {

      $nome_url = $row_rsP['url'];

    }

		

		//VERIFICA SE O TITULO JÁ ESTÁ PERSONALIZADO

		$title=$_POST['nome'];

		if($row_rsP['title'] != $row_rsP['nome']) {

			$title=$row_rsP['title'];

		}

		

		$insertSQL = "UPDATE l_pecas".$extensao." SET nome=:nome, descricao=:descricao, short_descricao=:short_descricao, descricao_stock=:descricao_stock, caracteristicas=:caracteristicas, url=:url, title=:title WHERE id=:id";

		$rsInsert = DB::getInstance()->prepare($insertSQL);

		$rsInsert->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR, 5);

		$rsInsert->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR, 5);	

    $rsInsert->bindParam(':short_descricao', $_POST['short_descricao'], PDO::PARAM_STR, 5);

		$rsInsert->bindParam(':descricao_stock', $_POST['descricao_stock'], PDO::PARAM_STR, 5);

    $rsInsert->bindParam(':caracteristicas', $_POST['caracteristicas'], PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':title', $title, PDO::PARAM_STR, 5);	

		$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

		$rsInsert->execute();



		$nao_limitar_stock = 0; 

    if($_POST['nao_limitar_stock'] == 1) $nao_limitar_stock = 1;

		

		$query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

		$rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

		$rsLinguas->execute();

		$totalRows_rsLinguas = $rsLinguas->rowCount();



		while($row_rsLinguas = $rsLinguas->fetch()) {

			$categoria = 0;

      if(CATEGORIAS == 1) {

        $categoria = $_POST['categoria'];

      }





      /* Start || Update Roll Value */



        $roleid       = json_encode($_POST['roleid']);

        $regularprice = json_encode($_POST['regularprice']);

        $sellingprice = json_encode($_POST['sellingprice']);

        $productqtn   = json_encode($_POST['productqtn']);



        

        $roleidd        = json_decode($roleid);

        $regularpricee  = json_decode($regularprice);

        $sellingpricee  = json_decode($sellingprice);

        $productqtnn    = json_decode($productqtn);





          $insertSQL = "UPDATE l_pecas".$extensao." SET 



          role_".$totalRows_rsRoll[0]["roll_name"]."= ".$roleidd[0].", 

          reguler_price_".$totalRows_rsRoll[0]["roll_name"]."= ".$regularpricee[0].", 

          selling_price_".$totalRows_rsRoll[0]["roll_name"]."= ".$sellingpricee[0].", 

          product_qulity_".$totalRows_rsRoll[0]["roll_name"]."= ".$productqtnn[0].",



          role_".$totalRows_rsRoll[1]["roll_name"]."= ".$roleidd[1].",

          reguler_price_".$totalRows_rsRoll[1]["roll_name"]." =  ".$regularpricee[1].", 

          selling_price_".$totalRows_rsRoll[1]["roll_name"]."= ".$sellingpricee[1].", 

          product_qulity_".$totalRows_rsRoll[1]["roll_name"]."= ".$productqtnn[1]."



          WHERE 



          id=:id";



          $rsInsert = DB::getInstance()->prepare($insertSQL);  

          $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 

          $rsInsert->execute();

        



      /* Start || Update Roll Value */





			$insertSQL = "UPDATE l_pecas".$extensao." SET ref=:ref, categoria=:categoria, preco=:preco, preco_forn=:preco_forn, preco_ant=:preco_ant,markup=:markup, iva=:iva, peso=:peso, stock=:stock, prepare= :prepare, enquiry_type= :enquiry_type, maxstock=:maxstock, nao_limitar_stock=:nao_limitar_stock WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':ref', $_POST['ref'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':categoria', $categoria, PDO::PARAM_INT);

			$rsInsert->bindParam(':preco', $_POST['preco'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':preco_forn', $_POST['preco_forn'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':preco_ant', $_POST['preco_ant'], PDO::PARAM_STR, 5);	

      $rsInsert->bindParam(':markup', $_POST['markup'], PDO::PARAM_STR, 5); 

			$rsInsert->bindParam(':iva', $_POST['iva'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':peso', $_POST['peso'], PDO::PARAM_STR, 5);		

			$rsInsert->bindParam(':stock', $_POST['stock'], PDO::PARAM_STR, 5);	

      $rsInsert->bindParam(':prepare', $_POST['prepare'], PDO::PARAM_STR, 5); 

      $rsInsert->bindParam(':enquiry_type', $_POST['enquiry_type'], PDO::PARAM_INT); 

      $rsInsert->bindParam(':maxstock', $_POST['maxstock'], PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':maxstock', $_POST['maxstock'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':nao_limitar_stock', $nao_limitar_stock, PDO::PARAM_INT);	

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

      $rsInsert->execute();



      if($_POST['cost']){

        $insertSQL = "UPDATE l_pecas_supplier SET  price=".$_POST['cost']." WHERE product_id=".$id." AND primery = 1";

        $rsInsert = DB::getInstance()->prepare($insertSQL);

        $rsInsert->execute();

			  

      }



  	}



  	if(CATEGORIAS == 2) {

      $query_rsDelete = "DELETE FROM l_pecas_categorias WHERE id_peca = :id";

      $rsDelete = DB::getInstance()->prepare($query_rsDelete);

      $rsDelete->bindParam(':id', $id, PDO::PARAM_INT);  

      $rsDelete->execute();

      

      for($i = 0; $i < sizeof($_POST['categorias']); $i++) {    

        $categoria = $_POST['categorias'][$i];    

        

        $insertSQL = "SELECT MAX(id) FROM l_pecas_categorias";

        $rsInsert = DB::getInstance()->prepare($insertSQL);

        $rsInsert->execute();

        $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);

        

        $max_id_2 = $row_rsInsert["MAX(id)"] + 1;     

        

        $insertSQL = "INSERT INTO l_pecas_categorias (id, id_peca, id_categoria) VALUES (:max_id_2, :id, :categoria)";

        $rsInsert = DB::getInstance()->prepare($insertSQL);

        $rsInsert->bindParam(':id', $id, PDO::PARAM_INT);

        $rsInsert->bindParam(':max_id_2', $max_id_2, PDO::PARAM_INT);  

        $rsInsert->bindParam(':categoria', $categoria, PDO::PARAM_INT);  

        $rsInsert->execute();   

      }

    } 

  	

    DB::close();

		

		if(!$manter) 

      header("Location: produtos.php?alt=1");

    else

      header("Location: produtos-edit.php?id=".$id."&alt=1&tab_sel=1");

	}



  if($tab_sel==5) {

    $insertSQL = "UPDATE l_pecas".$extensao." SET promocao_titulo=:promocao_titulo, promocao_texto=:promocao_texto WHERE id=:id";

    $rsInsert = DB::getInstance()->prepare($insertSQL);

    $rsInsert->bindParam(':promocao_titulo', $_POST['promocao_titulo'], PDO::PARAM_STR, 5);

    $rsInsert->bindParam(':promocao_texto', $_POST['promocao_texto'], PDO::PARAM_STR, 5);

    $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 

    $rsInsert->execute();



    $datai = NULL;

    if(isset($_POST['promocao_datai']) && $_POST['promocao_datai'] != "0000-00-00" && $_POST['promocao_datai'] != "") $datai = $_POST['promocao_datai'];

    $dataf = NULL;

    if(isset($_POST['promocao_dataf']) && $_POST['promocao_dataf'] != "0000-00-00" && $_POST['promocao_dataf'] != "") $dataf = $_POST['promocao_dataf'];



    $promocao = 0;

    if($_POST['promocao_desconto'] > 0) {

      $promocao = 1;

    }

    

    $query_rsLinguas = "SELECT sufixo FROM linguas WHERE visivel = '1'";

    $rsLinguas = DB::getInstance()->prepare($query_rsLinguas);

    $rsLinguas->execute();

    $totalRows_rsLinguas = $rsLinguas->rowCount();



    while($row_rsLinguas = $rsLinguas->fetch()) {

      $insertSQL = "UPDATE l_pecas_".$row_rsLinguas["sufixo"]." SET promocao_pagina=:promocao_pagina, promocao=:promocao, promocao_desconto=:promocao_desconto, promocao_datai=:promocao_datai, promocao_dataf=:promocao_dataf WHERE id=:id";

      $rsInsert = DB::getInstance()->prepare($insertSQL);

      $rsInsert->bindParam(':promocao_pagina', $_POST['promocao_pagina'], PDO::PARAM_INT);

      $rsInsert->bindParam(':promocao', $promocao, PDO::PARAM_INT);

      $rsInsert->bindParam(':promocao_desconto', $_POST['promocao_desconto'], PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':promocao_datai', $datai, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':promocao_dataf', $dataf, PDO::PARAM_STR, 5);

      $rsInsert->bindParam(':id', $id, PDO::PARAM_INT); 

      $rsInsert->execute();

    }



    DB::close();

      

    if(!$manter) 

      header("Location: produtos.php?alt=1");

    else

      header("Location: produtos-edit.php?id=".$id."&alt=1&tab_sel=5");

  }



  if($tab_sel==3) {

		if($_POST['url']!='') {	 

			$query_rsP = "SELECT url FROM l_pecas".$extensao." WHERE id=:id";

			$rsP = DB::getInstance()->prepare($query_rsP);

			$rsP->bindParam(':id', $id, PDO::PARAM_INT);	

			$rsP->execute();

			$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

			$totalRows_rsP = $rsP->rowCount();

			

			$nome_url=strtolower(verifica_nome($_POST['url']));



      $query_rsProc = "SELECT id FROM l_pecas".$extensao." WHERE url like :nome_url AND id!=:id";

      $rsProc = DB::getInstance()->prepare($query_rsProc);

      $rsProc->bindParam(':id', $id, PDO::PARAM_INT);

      $rsProc->bindParam(':nome_url', $nome_url, PDO::PARAM_STR, 5);

      $rsProc->execute();

      $totalRows_rsProc = $rsProc->rowCount();

      

      if($totalRows_rsProc > 0) {

        $nome_url = $nome_url."-".$id;

      }

			

			//REDIRECT 301

			if($row_rsP['url']!=$nome_url) redirectURL($row_rsP['url'], $nome_url, substr($extensao,1));

		

			$insertSQL = "UPDATE l_pecas".$extensao." SET url=:url, title=:title, description=:description, keywords=:keywords WHERE id=:id";

			$rsInsert = DB::getInstance()->prepare($insertSQL);

			$rsInsert->bindParam(':url', $nome_url, PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':title', $_POST['title'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':description', $_POST['description'], PDO::PARAM_STR, 5);	

			$rsInsert->bindParam(':keywords', $_POST['keywords'], PDO::PARAM_STR, 5);

			$rsInsert->bindParam(':id', $id, PDO::PARAM_INT);	

			$rsInsert->execute();

		}



    DB::close();



		if(!$manter) 

      header("Location: produtos.php?alt=1");

    else

      header("Location: produtos-edit.php?id=".$id."&alt=1&tab_sel=3");

	}

}



$query_rsP = "SELECT * FROM l_pecas".$extensao." WHERE id=:id";

$rsP = DB::getInstance()->prepare($query_rsP);

$rsP->bindParam(':id', $id, PDO::PARAM_INT);

$rsP->execute();

$row_rsP = $rsP->fetch(PDO::FETCH_ASSOC);

$totalRows_rsP = $rsP->rowCount();



$query_rsCat = "SELECT * FROM l_categorias_en WHERE cat_mae='0' ORDER BY nome ASC";

$rsCat = DB::getInstance()->prepare($query_rsCat);

$rsCat->execute();

$totalRows_rsCat = $rsCat->rowCount();



$query_rsMarcas = "SELECT * FROM l_marcas_pt ORDER BY nome ASC";

$rsMarcas = DB::getInstance()->prepare($query_rsMarcas);

$rsMarcas->execute();

$totalRows_rsMarcas = $rsMarcas->rowCount();



//Total de Encomendas

$query_rsTotalEnc = "SELECT COUNT(ep.id) AS total FROM encomendas_produtos ep, encomendas e WHERE e.id = ep.id_encomenda AND (e.estado=2 OR e.estado=3 OR e.estado=4 OR e.estado=6) AND ep.produto_id=:id";

$rsTotalEnc = DB::getInstance()->prepare($query_rsTotalEnc);

$rsTotalEnc->bindParam(':id', $id, PDO::PARAM_INT);

$rsTotalEnc->execute();

$row_rsTotalEnc = $rsTotalEnc->fetch(PDO::FETCH_ASSOC);

$totalRows_rsTotalEnc = $rsTotalEnc->rowCount();



//Quantidade Total

$query_rsQTot = "SELECT SUM(ep.qtd) AS total FROM encomendas_produtos ep, encomendas e WHERE e.id = ep.id_encomenda AND (e.estado=2 OR e.estado=3 OR e.estado=4 OR e.estado=6) AND ep.produto_id=:id";

$rsQTot = DB::getInstance()->prepare($query_rsQTot);

$rsQTot->bindParam(':id', $id, PDO::PARAM_INT);

$rsQTot->execute();

$row_rsQTot = $rsQTot->fetch(PDO::FETCH_ASSOC);

$totalRows_rsQTot = $rsQTot->rowCount();



//Quantidade Média

$q_med = round($row_rsQTot['total'] / $row_rsTotalEnc['total'], 2);



//Receita Total

$query_rsTotalRec = "SELECT ep.* FROM encomendas_produtos ep, encomendas e WHERE e.id = ep.id_encomenda AND (e.estado=2 OR e.estado=3 OR e.estado=4 OR e.estado=6) AND ep.produto_id=:id";

$rsTotalRec = DB::getInstance()->prepare($query_rsTotalRec);

$rsTotalRec->bindParam(':id', $id, PDO::PARAM_INT);

$rsTotalRec->execute();

$totalRows_rsTotalRec = $rsTotalRec->rowCount();



$total = 0;



if($totalRows_rsTotalEnc > 0) {

  while($row_rsTotalRec = $rsTotalRec->fetch()) {

    $total += ($row_rsTotalRec['qtd'] * $row_rsTotalRec['preco']);

  }

}



//Nº de Clientes diferentes que compraram o produto

$query_rsTotalCli = "SELECT COUNT(DISTINCT(c.id)) AS total FROM clientes c, encomendas e, encomendas_produtos ep WHERE ep.produto_id=:id AND ep.id_encomenda = e.id AND e.id_cliente = c.id AND (e.estado=2 OR e.estado=3 OR e.estado=4 OR e.estado=6)";

$rsTotalCli = DB::getInstance()->prepare($query_rsTotalCli);

$rsTotalCli->bindParam(':id', $id, PDO::PARAM_INT);

$rsTotalCli->execute();

$row_rsTotalCli = $rsTotalCli->fetch(PDO::FETCH_ASSOC);

$totalRows_rsTotalCli = $rsTotalCli->rowCount();



$query_rsClientes = "SELECT DISTINCT(c.id), c.* FROM clientes c, encomendas e, encomendas_produtos ep WHERE ep.produto_id=:id AND ep.id_encomenda = e.id AND e.id_cliente = c.id AND (e.estado=2 OR e.estado=3 OR e.estado=4 OR e.estado=6)";

$rsClientes = DB::getInstance()->prepare($query_rsClientes);

$rsClientes->bindParam(':id', $id, PDO::PARAM_INT);

$rsClientes->execute();

// $row_rsClientes = $rsClientes->fetch(PDO::FETCH_ASSOC);

$totalRows_rsClientes = $rsClientes->rowCount();



$nomes = '';



if($totalRows_rsClientes > 0) {

  while($row_rsClientes = $rsClientes->fetch()) {

    $query_rsClienteWish = "SELECT w.cliente FROM lista_desejo w WHERE w.produto = :id AND w.cliente = :id_cliente";

    $rsClienteWish = DB::getInstance()->prepare($query_rsClienteWish);

    $rsClienteWish->bindParam(':id', $id, PDO::PARAM_INT);

    $rsClienteWish->bindParam(':id_cliente', $row_rsClientes['id'], PDO::PARAM_INT);

    $rsClienteWish->execute();

    $row_rsClienteWish = $rsClienteWish->fetch(PDO::FETCH_ASSOC);

    $totalRows_rsClienteWish = $rsClienteWish->rowCount();



    $query_rsClienteFollow = "SELECT f.id_cliente FROM l_pecas_seguir f WHERE f.id_produto = :id AND f.id_cliente = :id_cliente";

    $rsClienteFollow = DB::getInstance()->prepare($query_rsClienteFollow);

    $rsClienteFollow->bindParam(':id', $id, PDO::PARAM_INT);

    $rsClienteFollow->bindParam(':id_cliente', $row_rsClientes['id'], PDO::PARAM_INT);

    $rsClienteFollow->execute();

    $row_rsClienteFollow = $rsClienteFollow->fetch(PDO::FETCH_ASSOC);

    $totalRows_rsClienteFollow = $rsClienteFollow->rowCount();



    $nomes.="<a href='javascript:' data-id='".$row_rsClientes['id']."' data-wish='".$row_rsClienteWish['cliente']."' data-follow='".$row_rsClienteFollow['id_cliente']."' data-nome='".$row_rsClientes['nome']."' data-dt-registo='".$row_rsClientes['data_registo']."' class='client-details'>".$row_rsClientes['nome']."</a>".", ";}

}



$nomes = substr($nomes, 0, -2);



//Obter a opção mais comum nas encomendas para este produto

$query_rsFavOpt = "SELECT ep.opcoes FROM encomendas_produtos ep, encomendas e WHERE e.id = ep.id_encomenda AND (e.estado=2 OR e.estado=3 OR e.estado=4 OR e.estado=6) AND ep.produto_id=:id";

$rsFavOpt = DB::getInstance()->prepare($query_rsFavOpt);

$rsFavOpt->bindParam(':id', $id, PDO::PARAM_INT);

$rsFavOpt->execute();

$totalRows_rsFavOpt = $rsFavOpt->rowCount();



$i=0;



if($totalRows_rsFavOpt > 0) {

  while($row_rsFavOpt = $rsFavOpt->fetch()) {

    $aux = strtok($row_rsFavOpt['opcoes'], ';');



    while($aux !== false) {

      $res[$i] = $aux;

      $aux = strtok(';');

      $i++;

    }

  }

}



$result = array_count_values($res);

asort($result);

end($result);

$fav_opt = key($result);



$query_rsPaginas = "SELECT id, nome FROM paginas_pt ORDER BY nome ASC";

$rsPaginas = DB::getInstance()->prepare($query_rsPaginas);

$rsPaginas->execute();

$totalRows_rsPaginas = $rsPaginas->rowCount();

$row_rsPaginas = $rsPaginas->fetch(PDO::FETCH_ASSOC);



//Nº de Clientes diferentes que estão a seguir o produto

$query_rsTotalFollow = "SELECT COUNT(id_cliente) AS total FROM l_pecas_seguir WHERE id_produto =:id GROUP BY id_produto";

$rsTotalFollow = DB::getInstance()->prepare($query_rsTotalFollow);

$rsTotalFollow->bindParam(':id', $id, PDO::PARAM_INT);

$rsTotalFollow->execute();

$row_rsTotalFollow = $rsTotalFollow->fetch(PDO::FETCH_ASSOC);

$totalRows_rsTotalFollow = $rsTotalFollow->rowCount();





$query_rsRole = "SELECT * FROM l_pecas".$extensao." where id =".$id;

$rsRole = DB::getInstance()->prepare($query_rsRole);

$rsRole->bindParam(':id', $id, PDO::PARAM_INT);

$rsRole->execute();

$totalRows_rsRoll = $rsRole->fetchAll();





$query_rsRole = "SELECT * FROM roll";

$rsRole = DB::getInstance()->prepare($query_rsRole);

$rsRole->execute();

$totalRows_rsRoll = $rsRole->fetchAll();

DB::close();





$query_rs_supp = "SELECT * FROM l_pecas_supplier where product_id=".$id;

$rsP_supp = DB::getInstance()->prepare($query_rs_supp);

$rsP_supp->execute();

$totalRows_rs_supp = $rsP_supp->fetchAll();



$query_rs_supp = "SELECT * FROM l_pecas".$extensao." where id =".$id;

$rsP_supp = DB::getInstance()->prepare($query_rs_supp);

$rsP_supp->bindParam(':id', $id, PDO::PARAM_INT);

$rsP_supp->execute();

$totalRows_rsP_supp = $rsP_supp->rowCount();

$row_rsP_supp = $rsP_supp->fetch(PDO::FETCH_ASSOC);







?>

<?php include_once(ROOTPATH_ADMIN.'inc_head_1.php'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->

<?php include_once(ROOTPATH_ADMIN.'inc_head_2.php'); ?>

<!--COR-->

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>js/jscolor/jscolor.js"></script>

<body class="<?php echo $body_info; ?>">

<?php include_once(ROOTPATH_ADMIN.'inc_topo.php'); ?>

<div class="clearfix"> </div>

<style type="text/css">

  .client-details {

    color: #0254EB

  }

  .client-details:visited {

    color: #0254EB

  }

  .stats-details {

    /*border-top: 1px solid #eee;*/

    margin-top: 60px;

    display: none;

  }

</style>

<!-- BEGIN CONTAINER -->

<div class="page-container">

  <?php include_once(ROOTPATH_ADMIN.'inc_menu.php'); ?>

  <!-- BEGIN CONTENT -->

  <div class="page-content-wrapper">

    <div class="page-content"> 

      <!-- BEGIN PAGE HEADER-->

      <h3 class="page-title"> <?php echo $RecursosCons->RecursosCons['produtos']; ?> <small><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></small> </h3>

      <div class="page-bar">

        <ul class="page-breadcrumb">

          <li> <i class="fa fa-home"></i> <a href="../index.php"><?php echo $RecursosCons->RecursosCons['home']; ?></a> <i class="fa fa-angle-right"></i> </li>           

          <li>

            <a href="produtos.php"><?php echo $RecursosCons->RecursosCons['produtos']; ?> <i class="fa fa-angle-right"></i></a>

          </li>

          <li>

            <a href="javascript:"><?php echo $RecursosCons->RecursosCons['editar_registo']; ?></a>

          </li>

        </ul>

      </div>

      <!-- END PAGE HEADER-->      

      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

      <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

          <div class="modal-content">

            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

              <h4 class="modal-title"><?php echo $RecursosCons->RecursosCons['eliminar_registo']; ?></h4>

            </div>

            <div class="modal-body"> <?php echo $RecursosCons->RecursosCons['msg_elimina_registo']; ?>  </div>

            <div class="modal-footer">

              <button type="button" class="btn blue" onClick="document.location='produtos.php?rem=1&id=<?php echo $row_rsP["id"]; ?>'"><?php echo $RecursosCons->RecursosCons['txt_ok']; ?></button>

              <button type="button" class="btn default" data-dismiss="modal"><?php echo $RecursosCons->RecursosCons['txt_cancelar']; ?></button>

            </div>

          </div>

        </div>

      </div>

      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

      <!-- BEGIN PAGE CONTENT-->

      <div class="row">

        <div class="col-md-12">     

          <?php include_once(ROOTPATH_ADMIN.'inc_linguas.php'); ?>    

          <form id="produtos_form" name="produtos_form" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">

            <input type="hidden" name="manter" id="manter" value="0">

            <input type="hidden" name="tab_sel" id="tab_sel" value="<?php echo $tab_sel; ?>">

            <input type="hidden" name="last-order-id" id="last-order-id" value="">

            <input type="hidden" name="client-id" id="client-id" value="">

            <input type="hidden" name="product-id" id="product-id" value="<?php echo $id; ?>">

            <div class="portlet">

              <div class="portlet-title">

                <div class="caption"> <i class="fa fa-pencil-square"></i><?php echo $RecursosCons->RecursosCons['produtos']; ?> - <?php echo $row_rsP['nome']; ?></div>

                <div class="form-actions actions btn-set">

                  <button type="button" name="back" class="btn default" onClick="document.location='produtos.php'"><i class="fa fa-angle-left"></i> <?php echo $RecursosCons->RecursosCons['voltar']; ?></button>

                  <button type="reset" class="btn default"><i class="fa fa-eraser"></i> <?php echo $RecursosCons->RecursosCons['limpar']; ?></button>

                  <button type="submit" class="btn green to-hide"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar']; ?></button>

                  <button type="submit" class="btn green to-hide" onClick="document.getElementById('manter').value='1';"><i class="fa fa-check"></i> <?php echo $RecursosCons->RecursosCons['guardar_manter']; ?></button>

                  <a href="#modal_delete" data-toggle="modal" class="btn red"><i class="fa fa-remove"></i> <?php echo $RecursosCons->RecursosCons['']; ?></a>

                </div>

              </div>

              <div class="portlet-body">

                <div class="tabbable">

                  <ul class="nav nav-tabs">

                    <li class="nav-tab <?php if($tab_sel==1) echo "active"; ?>"> <a href="#tab_general" data-toggle="tab" onClick="document.getElementById('tab_sel').value='1'"> <?php echo $RecursosCons->RecursosCons['tab_detalhes']; ?> </a> </li>

                    <li class="nav-tab <?php if($tab_sel==5) echo "active"; ?>"> <a href="#tab_promocao" data-toggle="tab" onClick="document.getElementById('tab_sel').value='5'"> <?php echo $RecursosCons->RecursosCons['tab_promocao']; ?> </a> </li>

                    <li class="nav-tab" onClick="window.location='produtos-edit-imagens.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_imagens']; ?> </a> </li>

                    <li class="nav-tab" onClick="window.location='produtos-edit-stocks.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_stocks']; ?> </a> </li>



                    <li class="nav-tab" onClick="window.location='store-edit.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> Store Locater </a> </li>



                    <li onClick="window.location='produtos-edit-filtros.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_filtros']; ?> </a> </li>

                    <!-- <li onClick="window.location='supply-edit.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> Supplier </li> -->

                   

                    <!--  <li class="nav-tab <?php if($tab_sel==7) echo "active"; ?>"> <a href="#tab_supplier" data-toggle="tab" onClick="document.getElementById('tab_sel').value='7'"> Supplier </a> </li> -->



                    <li class="nav-tab" onClick="window.location='supplier-edit.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> Supplier </a> </li>

                    <li class="nav-tab" onClick="window.location='QR.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> QR Code </a> </li>



                    <li class="nav-tab" onClick="window.location='produtos-edit-relacionados.php?id=<?php echo $id; ?>'"> <a href="javascript:void(null)" data-toggle="tab"> <?php echo $RecursosCons->RecursosCons['tab_relacionados']; ?> </a> </li>

                    <li id="nav-tab-stats" class="nav-tab <?php if($tab_sel==2) echo "active"; ?>"> <a id="tab_2" href="#tab_estatisticas" data-toggle="tab" onClick="document.getElementById('tab_sel').value='2'"> <?php echo $RecursosCons->RecursosCons['tab_estatisticas']; ?> </a> </li>

                    <li class="nav-tab <?php if($tab_sel==3) echo "active"; ?>"> <a id="tab_3" href="#tab_dados" data-toggle="tab" onClick="document.getElementById('tab_sel').value='3'"> <?php echo $RecursosCons->RecursosCons['tab_metatags']; ?> </a> </li>

                  </ul>

                  <div class="tab-content no-space">

                    <div class="tab-pane <?php if($tab_sel==1) echo "active"; ?>" id="tab_general">

                      <div class="form-body">

                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 1) { ?>

                          <div class="alert alert-success display-show">

                            <button class="close" data-close="alert"></button>

                            <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>

                          </div>

                        <?php } ?>

                        <?php if($_GET['env'] == 1) { ?>  

                          <div class="alert alert-success display-show">

                            <button class="close" data-close="alert"></button>

                            <?php echo $RecursosCons->RecursosCons['env_config']; ?> 

                          </div>

                        <?php } ?>

                        <div class="alert alert-danger display-hide">

                          <button class="close" data-close="alert"></button>

                          <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 

                        </div>                  

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="ref"><?php echo $RecursosCons->RecursosCons['ref_label']; ?>:</label>

                          <div class="col-md-3">

                            <input type="text" class="form-control" name="ref" id="ref" value="<?php echo $row_rsP['ref']; ?>">

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="nome"><?php echo $RecursosCons->RecursosCons['nome_label']; ?>: <span class="required"> * </span></label>

                          <div class="col-md-5">

                            <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $row_rsP['nome']; ?>">

                          </div>

                          <label class="col-md-1 control-label" for="atualizar_url"><?php echo $RecursosCons->RecursosCons['atualizar_url']; ?>? </label>

                          <div class="col-md-2" style="padding-top: 7px;">

                            <input type="checkbox" class="form-control" name="atualizar_url" id="atualizar_url" value="1">

                            <p class="help-block"><?php echo $RecursosCons->RecursosCons['atualizar_url_txt']; ?></p>

                          </div>

                        </div> 

                        <?php if(CATEGORIAS == 1) { ?>

      	                  <div class="form-group">

      	                    <label class="col-md-2 control-label" for="categoria"> <?php echo $RecursosCons->RecursosCons['categoria_label']; ?>: <span class="required"> * </span></label>

      	                    <div class="col-md-8">

      	                      <select class="form-control select2me" id="categoria" name="categoria">

      	                        <option value=""><?php echo $RecursosCons->RecursosCons['opt_selecionar']; ?></option>

      	                        <?php umaCategoriaPorProd(0, "", $row_rsP['categoria']); ?>

      	                      </select>

      	                    </div>

      	                  </div>

      	                <?php } else if(CATEGORIAS == 2) { ?>

      	                  <div class="form-group">

      	                    <label class="col-md-2 control-label" for="categoria"> <?php echo $RecursosCons->RecursosCons['categoria_label']; ?>: </label>

      	                    <div class="col-md-8">

      	                      <div class="form-control height-auto">

      	                        <div class="scroller" style="height: 300px; overflow-y: auto !important;" data-always-visible="1">

      	                          <ul class="list-unstyled">

                                    <?php variasCategoriasPorProd(0, $row_rsP['id'], 1); ?>

                                  </ul>

      	                        </div>

      	                      </div>

      	                    </div>

      	                  </div>

      	                <?php } ?>

                        <?php 

                        $cost = $row_rsP["cost"];

                        $markup = $row_rsP["markup"];



                        if($cost != "" && $markup != "")

                        {

                          $totalprice2 = $cost +  ( ($cost * $markup) / 100);

                        }

                        if($row_rsP['preco'] == 0 || $row_rsP['preco'] == "")

                        {

                          $totalprice2 = "";

                        }

                        if( ($cost == "" ||  $cost == 0 || $cost != 0) && ($markup == "" || $markup == 0 ) )

                        {

                          $totalprice2 = $row_rsP['preco'];

                        }

                        ?>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="preco"><?php echo $RecursosCons->RecursosCons['preco_label']; ?>:</label>

                          <div class="col-md-2">

                            <div class="input-group">

                              <input type="text" class="form-control" name="preco" id="preco" value="<?php echo $totalprice2; ?>" maxlength="8">

                              <span class="input-group-addon">&pound;</span>

                            </div>

                          </div>

                          <label class="col-md-1 control-label" for="preco_ant"><?php echo $RecursosCons->RecursosCons['preco_ant_label']; ?>:</label>

                          <div class="col-md-2">

                            <div class="input-group">

                              <input type="text" class="form-control" name="preco_ant" id="preco_ant" value="<?php echo $row_rsP['preco_ant']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">

                              <span class="input-group-addon">&pound;</span>

                            </div>                      

                          </div>

                        </div>



                        <!-- Start || Add Code By Vishal Prajapati 21-oct-2020-->

                          <div class="form-group">

                          <label class="col-md-2 control-label" for="markup">Markup:</label>

                          <div class="col-md-2">

                            <div class="input-group">

                              <input type="text" class="form-control" name="markup" id="markup" value="<?php echo $row_rsP['markup']; ?>" maxlength="8">

                              <span class="input-group-addon">%</span>

                            </div>

                          </div>

                          <?php

                            foreach($totalRows_rs_supp as $row) {

                              $primery =  $row["primery"];

                              $supp_price = $row["price"];

                            }

                           ?>

                          <label class="col-md-1 control-label" for="preco_ant">Cost:</label>

                          <div class="col-md-2">

                            <div class="input-group">

                              <input type="text" class="form-control" name="cost" id="cost" value="<?php if($primery == 1) { echo $supp_price; } else { echo $row_rsP['cost']; }  ?>" maxlength="8">

                              <span class="input-group-addon">&pound;</span>



                            </div>  

                                <label class="col-md-1 control-label" for="preco_ant" style="color: red;"><?php foreach($totalRows_rs_supp as $row) { if($row["primery"] == 1 && $row_rsP['cost'] == "") { echo $row["name"]; } else { echo ""; } } ?> </label>                   

                          </div>

                        </div>

                        <!--  -->



                        <div class="form-group">

                          <label class="col-md-2 control-label" for="peso"><?php echo $RecursosCons->RecursosCons['peso_label']; ?>:</label>

                          <div class="col-md-2">

                            <div class="input-group">

                              <input type="text" class="form-control" name="peso" id="peso" value="<?php echo $row_rsP['peso']; ?>" maxlength="8" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">

                              <span class="input-group-addon">kg</span>

                            </div>                       

                          </div>

                         <label class="col-md-1 control-label"> VAT: </label>

                            <div class="col-md-2">

                              <div class="input-group"> 

                                <input type="radio" name="iva" id="iva" value="20" <?php if($row_rsP['iva'] != "") { echo "checked=\"checked\""; }?>>Yes

                                <input type="radio" name="iva" id="iva" value="0" <?php if($row_rsP['iva'] == "" || $row_rsP['iva'] == 0) { echo "checked=\"checked\""; }?>>No

                              </div>

                            </div>



                           <label class="col-md-1 control-label" for="mStock"> Max Stock:</label>

                            <div class="col-md-2">

                              <div class="input-group">

                                <input type="text" class="form-control" name="maxstock" id="maxstock" value="<?php if($row_rsP['maxstock']>0) echo $row_rsP['maxstock']; else echo ""; ?>" maxlength="2" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">

                               

                              </div>                        

                            </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="stock"><?php echo $RecursosCons->RecursosCons['stock_label']; ?>:</label>

                          <div class="col-md-2">

                            <div class="input-group">

                              <input type="text" class="form-control" name="stock" id="stock" value="<?php echo $row_rsP['stock']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">

                              <span class="input-group-addon">uni</span>

                            </div>                    

                          </div>

                          <div class="col-md-2" style="padding-top:7px;text-align:center">

                            <label><input type="checkbox" class="form-control" name="nao_limitar_stock" id="nao_limitar_stock" value="1" <?php if($row_rsP['nao_limitar_stock']==1) echo "checked"; ?> />&nbsp;<?php echo $RecursosCons->RecursosCons['info_limite_stock']; ?></label>

                          </div>

                         <label class="col-md-2 control-label" for="descricao_stock"><?php echo $RecursosCons->RecursosCons['desc_stock']; ?>:</label>

                          <div class="col-md-2">

                            <input type="text" class="form-control" name="descricao_stock" id="descricao_stock" value="<?php echo $row_rsP['descricao_stock']; ?>">

                          </div>

                        </div>



                        <div class="form-group">

                          <label class="col-md-2 control-label" for="stock">Prepration Time:</label>

                          <div class="col-md-3">

                            <div class="input-group">

                              <input type="text" class="form-control" name="prepare" id="prepare" value="<?php echo $row_rsP['prepare']; ?>" onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">

                            </div>                    

                          </div>

                          <label class="col-md-2 control-label" for="enquiry_type">Make Enquiry:</label>

                          <div class="col-md-3">

                            <div class="input-group">

                              <input type="checkbox" id="enquiry_type" name="enquiry_type" value="1" <?php if($row_rsP["enquiry_type"] == 1) { echo  "checked"; } ?>>

                            </div>                    

                          </div>

                        </div>

                         <!-- Add Role || Code Vishal Prajapti -->

                    <div id="tabs" style="width: 1000px; margin-left: 100px;">

                       

                      <ul>

                        <?php  foreach ($totalRows_rsRoll as $role) {



                         ?>

                        <li>

                          <a href="#tabs-<?php echo $role['roll_name']; ?>" value="<?php echo $role['id']; ?>"><?php echo $role['roll_name']; ?>

                            <input type="hidden" class="form-control" name="roleid[]" id="roleid" value="<?php echo $role['id']; ?>">

                          </a>

                        </li>

                          <?php  } ?>

                      </ul>

                  

                  <?php foreach ($totalRows_rsRoll as $role) { ?>

                    <div id="tabs-<?php echo $role['roll_name']; ?>">      

                      <div class="form-group new_prod">



                        <?php if ($role["roll_name"] ){

                          $reguler_Price = 'reguler_price_'.$role['roll_name'];

                          $get_reguler_price = $row_rsP_supp[$reguler_Price];

                        ?>

                          <label class="col-md-3 control-label" for="regularprice">Regular Price (optional) :</label>

                            <div class="col-md-2">

                               <input type="text" class="form-control" name="regularprice[]" id="regularprice" value="<?php if($get_reguler_price != "") { echo  $get_reguler_price; } else { echo "0"; } ?>">

                            </div>

                        <?php } ?>



                        <?php if ($role["roll_name"] ){

                          $selling_price = 'selling_price_'.$role['roll_name'];

                          $get_selling_price = $row_rsP_supp[$selling_price];

                        ?>

                          <label class="col-md-3 control-label" for="sellingprice">Selling Price (optional):</label>

                          <div class="col-md-2">

                             <input type="text" class="form-control" name="sellingprice[]" id="sellingprice" value="<?php if($get_selling_price != "") { echo  $get_reguler_price; } else { echo "0"; } ?>">

                          </div>

                        <?php } ?>

                        

                      </div>



                      <div class="form-group new_prod">

                        <?php if ($role["roll_name"] ){

                            $product_qulity = 'product_qulity_'.$role['roll_name'];

                            $get_product_qulity = $row_rsP_supp[$product_qulity];

                          ?>

                          <label class="col-md-3 control-label" for="productqtn">Product Quantity (optional) :</label>

                            <div class="col-md-2">

                               <input type="text" class="form-control" name="productqtn[]" id="productqtn" value="<?php if($get_product_qulity != "") { echo  $get_reguler_price; } else { echo "0"; } ?>">

                            </div>

                        <?php } ?>

                        </div>

                    </div>

                  <?php  } ?> 

                </div>

                  <!-- End Role -->

                  <br>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="descricao"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>: </label>

                          <div class="col-md-8">

                            <textarea class="form-control caixa_options" id="descricao" name="descricao"><?php echo $row_rsP['descricao']; ?></textarea>

                          </div>

                        </div>



                        <div class="form-group">

                          <label class="col-md-2 control-label" for="caracteristicas"><?php echo $RecursosCons->RecursosCons['specifications_label']; ?>: </label>

                          <div class="col-md-8">

                            <textarea class="form-control caixa_options" id="caracteristicas" name="caracteristicas"><?php echo $row_rsP['caracteristicas']; ?></textarea>

                          </div>

                        </div>

                            

                      </div>

                    </div>

                    <div class="tab-pane <?php if($tab_sel==5) echo "active"; ?>" id="tab_promocao">

                      <div class="form-body">

                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 5) { ?>

                          <div class="alert alert-success display-show">

                            <button class="close" data-close="alert"></button>

                            <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>

                          </div>

                        <?php } ?>

                        <div class="alert alert-danger display-hide">

                          <button class="close" data-close="alert"></button>

                          <?php echo $RecursosCons->RecursosCons['msg_required']; ?> 

                        </div>                  

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="promocao_datai"><?php echo $RecursosCons->RecursosCons['data_inicio_label']; ?>: </label>

                          <div class="col-md-3">

                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">

                              <input type="text" class="form-control form-filter input-sm" name="promocao_datai" placeholder="Data" id="promocao_datai" value="<?php echo $row_rsP['promocao_datai']; ?>">

                              <span class="input-group-btn">

                              <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                              </span> 

                            </div>

                          </div>

                          <label class="col-md-2 control-label" for="promocao_dataf"><?php echo $RecursosCons->RecursosCons['data_fim_label']; ?>: </label>

                          <div class="col-md-3">

                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">

                              <input type="text" class="form-control form-filter input-sm" name="promocao_dataf" placeholder="Data" id="promocao_dataf" value="<?php echo $row_rsP['promocao_dataf']; ?>">

                              <span class="input-group-btn">

                              <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>

                              </span> 

                            </div>

                          </div>

                        </div> 

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="promocao_desconto"><?php echo $RecursosCons->RecursosCons['cli_desconto']; ?>: </label>

                          <div class="col-md-3">

                            <div class="input-group">

                              <input type="text" class="form-control" name="promocao_desconto" id="promocao_desconto" value="<?php echo $row_rsP['promocao_desconto']; ?>" onkeyup="onlyDecimal(this)" onblur="onlyDecimal(this)">

                              <span id="span_desconto" class="input-group-addon">%</span>

                            </div> 

                          </div>

                        </div>

                        

                        <hr>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="promocao_pagina"><?php echo $RecursosCons->RecursosCons['pagina']; ?>: </label>

                          <div class="col-md-8">

                            <select class="form-control select2me" name="promocao_pagina" id="promocao_pagina">

                              <option value="">Selecionar...</option>

                              <?php if($totalRows_rsPaginas > 0) {

                                while($row_rsPaginas = $rsPaginas->fetch()) { ?>

                                  <option value="<?php echo $row_rsPaginas['id']; ?>" <?php if($row_rsP['promocao_pagina'] == $row_rsPaginas['id']) echo "selected"; ?>><?php echo $row_rsPaginas['nome']; ?></option>

                                <?php }

                              } ?>

                            </select>

                            <p class="help-block"><?php echo $RecursosCons->RecursosCons['promocoes_aviso2']; ?></p>

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="promocao_titulo"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>: </label>

                          <div class="col-md-8">

                            <input type="text" class="form-control" name="promocao_titulo" id="promocao_titulo" value="<?php echo $row_rsP['promocao_titulo']; ?>" maxlength="25">

                            <p class="help-block"><?php echo $RecursosCons->RecursosCons['promocoes_aviso3']; ?></p>

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="promocao_texto"><?php echo $RecursosCons->RecursosCons['texto_label']; ?>: </label>

                          <div class="col-md-8">

                            <textarea class="form-control" name="promocao_texto" id="promocao_texto"><?php echo $row_rsP['promocao_texto']; ?></textarea>

                          </div>

                        </div>        

                      </div>

                    </div>



                    

                    <div class="tab-pane <?php if($tab_sel==2) echo "active"; ?>" id="tab_estatisticas">

                      <div class="form-body">

                        <div class="clearfix"></div>

                        <div class="clearfix margin-top-20 margin-bottom-20"> <span class="label label-danger"><?php echo $RecursosCons->RecursosCons['nota_txt']; ?>!</span> <span><?php echo $RecursosCons->RecursosCons['nota_estatisticas']; ?></span> </div>

                        <div class="clearfix"></div>

                        <div class="row">

                          <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['cli_total_encomendas']; ?>:</strong></label>

                          <div class="col-md-5">

                            <div class="form-control" style="border:0;"><?php if($row_rsTotalEnc['total'] != null && $row_rsTotalEnc['total'] != '') { echo $row_rsTotalEnc['total']; } else { echo "0"; } ?></div>

                          </div>

                        </div>

                        <div class="clearfix"></div>

                        <div class="row">

                          <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['quantidade_total_label']; ?>:</strong></label>

                          <div class="col-md-5">

                            <div class="form-control" style="border:0;"><?php if($row_rsQTot['total'] != null && $row_rsQTot['total'] != '') { echo $row_rsQTot['total']; } else { echo "0"; } ?></div>

                          </div>

                        </div>

                        <div class="clearfix"></div>

                        <div class="row">

                          <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['quantidade_media_enc_label']; ?>:</strong></label>

                          <div class="col-md-5">

                            <div class="form-control" style="border:0;"><?php if($q_med != null) { echo $q_med; } else { echo "0"; } ?></div>

                          </div>

                        </div>

                        <div class="clearfix"></div>

                        <div class="row">

                          <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['receita_total_label']; ?>:</strong></label>

                          <div class="col-md-5">

                            <div class="form-control" style="border:0;"><?php if($total != null) { echo round($total, 2)." €"; } else { echo "0€"; } ?></div>

                          </div>

                        </div>

                        <div class="clearfix"></div>

                        <div class="row">

                          <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['n_clientes_compraram_label']; ?>:</strong></label>

                          <div class="col-md-5">

                            <div class="form-control" style="border:0;"><?php if($row_rsTotalCli['total'] != null && $row_rsTotalCli['total'] != '') { echo $row_rsTotalCli['total']; } else { echo "0"; } ?></div>

                          </div>

                        </div>

                        <div class="clearfix"></div>

                          <div class="row">

                            <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['n_clientes_desejam_label']; ?>:</strong></label>

                            <div class="col-md-5">

                              <div class="form-control" style="border:0;"><?php if($row_rsP['favoritos'] != null && $row_rsP['favoritos'] != '') { echo $row_rsP['favoritos']; } else { echo "0"; } ?></div>

                            </div>

                          </div>

                          <div class="clearfix"></div>

                          <div class="row">

                            <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['n_clientes_seguem_label']; ?>:</strong></label>

                            <div class="col-md-5">

                              <div class="form-control" style="border:0;"><?php if($row_rsTotalFollow['total'] != null && $row_rsTotalFollow['total'] != '') { echo $row_rsTotalFollow['total']; } else { echo "0"; } ?></div>

                            </div>

                          </div>

                          <div class="clearfix"></div>

                          <div class="row">

                            <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['opcao_preferida_label']; ?>:</strong></label>

                            <div class="col-md-5">

                              <div class="form-control" style="border:0;"><?php if($fav_opt != null) { echo $fav_opt; } else { echo "-"; } ?></div>

                            </div>

                          </div>

                          <div class="clearfix"></div>

                          <div class="row">

                            <label class="col-md-3 control-label" style="text-align:right; margin-top:0px;"><strong><?php echo $RecursosCons->RecursosCons['clientes']; ?>:</strong></label>

                            <div class="col-md-5">

                              <div class="form-control" style="border:0;">

                                <div id="scrollbar1">

                                  <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>

                                  <div class="viewport">

                                    <div class="overview">

                                      <?php if($nomes != null && $nomes != '') { echo $nomes; } else { echo "-"; } ?>

                                    </div>

                                  </div>

                                </div>

                              </div>

                            </div>

                          </div>

                          <div class="clearfix margin-bottom-20"></div>

                          <div class="stats-details">

                            <div class="row">

                              <div class="col-md-6 col-sm-12">

                                <div class="portlet blue-hoki box">

                                  <div class="portlet-title">

                                    <div class="caption">

                                      <i class="fa fa-cogs"></i>Informação Cliente

                                    </div>

                                    <div class="actions">

                                      <a id="edit-cliente" href="" class="btn btn-default btn-sm">

                                      <i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?> </a>

                                    </div>

                                  </div>

                                  <div class="portlet-body">

                                    <div class="row static-info">

                                      <div class="col-md-4 name">

                                         <?php echo $RecursosCons->RecursosCons['nome_label']; ?>:

                                      </div>

                                      <div class="col-md-8 value">

                                         <span id="nome-cliente"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-4 name">

                                         <?php echo $RecursosCons->RecursosCons['data_registo']; ?>:

                                      </div>

                                      <div class="col-md-8 value">

                                         <span id="data-registo-cliente"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-4 name">

                                         <?php echo $RecursosCons->RecursosCons['cli_total_encomendas']; ?>:

                                      </div>

                                      <div class="col-md-8 value">

                                         <span id="total-encomendas"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-4 name">

                                         <?php echo $RecursosCons->RecursosCons['total_gasto_enc']; ?>:

                                      </div>

                                      <div class="col-md-8 value">

                                         <span id="total-gasto"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-4 name">

                                         <?php echo $RecursosCons->RecursosCons['deseja_produto_label']; ?>:

                                      </div>

                                      <div class="col-md-8 value">

                                         <span id="wish-prod"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-4 name">

                                         <?php echo $RecursosCons->RecursosCons['segue_produto_label']; ?>:

                                      </div>

                                      <div class="col-md-8 value">

                                         <span id="follow-prod"></span>

                                      </div>

                                    </div>

                                  </div>

                                </div>

                              </div>

                              <div class="col-md-6 col-sm-12">

                                <div class="portlet yellow-crusta box">

                                  <div class="portlet-title">

                                    <div class="caption">

                                      <i class="fa fa-cogs"></i><?php echo $RecursosCons->RecursosCons['detalhes_enc_label']; ?>

                                    </div>

                                    <div class="actions">

                                      <a id="enc-id" href="" class="btn btn-default btn-sm">

                                      <i class="fa fa-pencil"></i> <?php echo $RecursosCons->RecursosCons['btn_editar']; ?> </a>

                                    </div>

                                  </div>

                                  <div class="portlet-body">

                                    <div class="row static-info">

                                      <div class="col-md-5 name">

                                         <?php echo $RecursosCons->RecursosCons['encomenda_num']; ?>:

                                      </div>

                                      <div class="col-md-7 value">

                                        <a href="javascript:" id="enc-ant" data-id="<?php echo $id_cliente; ?>" style="margin-right: 10px"><i class="fa fa-angle-double-left"></i></a>

                                        <span id="encomenda_id"></span>

                                        <a href="javascript:" id="enc-prox" data-id="<?php echo $id_cliente; ?>" style="margin-left: 10px"><i class="fa fa-angle-double-right"></i></a>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-5 name">

                                         <?php echo $RecursosCons->RecursosCons['data_enc_label']; ?>:

                                      </div>

                                      <div class="col-md-7 value">

                                         <span id="data-encomenda"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-5 name">

                                         <?php echo $RecursosCons->RecursosCons['estado_enc_label']; ?>:

                                      </div>

                                      <div class="col-md-7 value">

                                        <span id="estado-enc"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-5 name">

                                        <?php echo $RecursosCons->RecursosCons['quant_do_prod_label']; ?>:

                                      </div>

                                      <div class="col-md-7 value">

                                        <span id="qtd-prod"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-5 name">

                                         <?php echo $RecursosCons->RecursosCons['total_do_prod_label']; ?>

                                      </div>

                                      <div class="col-md-7 value">

                                        <span id="total-prod"></span>

                                      </div>

                                    </div>

                                    <div class="row static-info">

                                      <div class="col-md-5 name">

                                         <?php echo $RecursosCons->RecursosCons['total_da_enc_label']; ?>

                                      </div>

                                      <div class="col-md-7 value">

                                        <span id="total-enc"></span>

                                      </div>

                                    </div>

                                  </div>

                                </div>

                              </div>

                            </div>

                          </div>

                        </div>

                      </div>

                      <div class="tab-pane <?php if($tab_sel==3) echo "active"; ?>" id="tab_dados">

                      <div class="form-body">

                        <?php if($_GET['alt'] == 1 && $_GET['tab_sel'] == 3) { ?>

                          <div class="alert alert-success display-show">

                            <button class="close" data-close="alert"></button>

                            <span> <?php echo $RecursosCons->RecursosCons['alt']; ?> </span>

                          </div>

                        <?php } ?>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="url"><?php echo $RecursosCons->RecursosCons['url_label']; ?>:</label>

                          <div class="col-md-10">

                            <input type="text" class="form-control" name="url" id="url" value="<?php echo $row_rsP['url']; ?>" onkeyup="carregaPreview()" onblur="carregaPreview()">

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="title"><?php echo $RecursosCons->RecursosCons['titulo_label']; ?>:</label>

                          <div class="col-md-10">

                            <input type="text" class="form-control" name="title" id="title" value="<?php echo $row_rsP['title']; ?>" onkeyup="carregaPreview()" onblur="carregaPreview()">

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="description"><?php echo $RecursosCons->RecursosCons['descricao_label']; ?>:</label>

                          <div class="col-md-10">

                            <textarea class="form-control" rows="5" id="description" name="description" style="resize:none" onkeyup="carregaPreview()" onblur="carregaPreview()"><?php echo $row_rsP['description']; ?></textarea>

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label" for="keywords"><?php echo $RecursosCons->RecursosCons['palavras-chave_label']; ?>:</label>

                          <div class="col-md-10">

                            <textarea class="form-control" rows="5" id="keywords" name="keywords" style="resize:none" onkeyup="carregaPreview()" onblur="carregaPreview()"><?php echo $row_rsP['keywords']; ?></textarea>

                            <span class="help-block"><strong>Note:</strong> separate words with a comma ','</span>

                          </div>

                        </div>

                        <div class="form-group">

                          <label class="col-md-2 control-label"><?php echo $RecursosCons->RecursosCons['pre-view_google_label']; ?>:</label>

                          <div class="col-md-10" style="padding:0 15px">

                            <div style="border:1px solid #e5e5e5;min-height:50px;padding:10px" id="googlePreview"></div>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

              </div>

            </div>

            <input type="hidden" name="MM_insert" value="produtos_form" />

          </form>

        </div>

      </div>

      <!-- END PAGE CONTENT--> 

    </div>

  </div>

  <!-- END CONTENT -->

  <?php include_once(ROOTPATH_ADMIN.'inc_quick_sidebar.php'); ?>

</div>

<!-- END CONTAINER -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_1.php'); ?>

<!-- BEGIN PAGE LEVEL PLUGINS --> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/select2/select2.min.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 

<!-- LINGUA PORTUGUESA -->

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt.js"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script> 

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/plupload/js/plupload.full.min.js" type="text/javascript"></script> 

<!-- END PAGE LEVEL PLUGINS -->

<?php include_once(ROOTPATH_ADMIN.'inc_footer_2.php'); ?>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckeditor/ckeditor.js"></script> 

<script type="text/javascript" src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.js"></script> 

<!-- BEGIN PAGE LEVEL SCRIPTS --> 

<script src="form-validation.js"></script> 

<link rel="stylesheet" href="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/css/tinyscrollbar.css" type="text/css"/>

<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/admin/pages/scripts/jquery.tinyscrollbar.min.js"></script>

<script src="produtos-estatisticas.js"></script>



<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

 <script type="text/javascript">

    $( function() {

    $( "#tabs" ).tabs();

  } );

 </script>



  <script>



     $("#markup").change(function(){



        var cost   = $('#cost').val();

        var markup = $('#markup').val();

        var totalprice =  parseInt(cost) +  ( (cost * markup) / 100);

        $('#preco').val(totalprice);

      });



       $("#preco").change(function(){



        var cost   = $('#cost').val();

        var preco = $('#preco').val();

        var totalprice2 =  100 * (preco - cost) / cost;

        $('#markup').val(totalprice2);

      });



       $("#cost").change(function(){



        var cost   = $('#cost').val();

        var markup = $('#markup').val();



        totalprice2 = parseInt(cost) +  ( (cost * markup) / 100);



        $('#preco').val(totalprice2);

      });

      



        /* $(document).on("change keyup blur", "#preco", function() {

            var total   = $('#tprice').val();

            var qtn    = $('#qtn').val();

            var total = (total * qtn);

            $('#total').val(total);

           // var mult = main * dec; // gives the value for subtract from main value

            //var discont = main - mult;

        });*/

          /*  $(document).ready(function() {

            $('#gst').keyup(function(ev) {

              var gst = $("#gst").val();

              var price = $("#tprice").val();

              var qty = $("#qtn").val();

              var qty_total = price * qty;

              var tot_price = (qty_total * gst / 100) + qty_total;

              var gst_total = document.getElementById('total');

              gst_total.value = tot_price;

            });

          });  */



    </script>





 <script>

  // Material Select Initialization

  $(document).ready(function() {

  $('.mdb-select').materialSelect();

  });

  </script>

  <script>

$(document).ready(function(){

 $('#framework').multiselect({

  nonSelectedText: 'Select Framework',

  enableFiltering: true,

  enableCaseInsensitiveFiltering: true,

  buttonWidth:'400px'

 });

 

 $('#framework_form').on('submit', function(event){

  event.preventDefault();

  var form_data = $(this).serialize();

  $.ajax({

   url:"insert.php",

   method:"POST",

   data:form_data,

   success:function(data)

   {

    $('#framework option:selected').each(function(){

     $(this).prop('selected', false);

    });

    $('#framework').multiselect('refresh');

    alert(data);

   }

  });

 });

 

 

});

</script>



 

<!-- END PAGE LEVEL SCRIPTS --> 

<script>

jQuery(document).ready(function() {    

  Metronic.init(); // init metronic core components

  Layout.init(); // init current layout

  QuickSidebar.init(); // init quick sidebar

  Demo.init(); // init demo features

  FormValidation.init();

});

</script> 

<script type="text/javascript">

var areas = Array('descricao', 'promocao_texto', 'caracteristicas', 'informacoes');

$.each(areas, function (i, area) {

 CKEDITOR.replace(area, {

  filebrowserBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html',

  filebrowserImageBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Images',

  filebrowserFlashBrowseUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/ckfinder.html?Type=Flash',

  filebrowserUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',

  filebrowserImageUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

  filebrowserFlashUploadUrl : '<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',

  toolbar : "Basic2",

  height: "250px"

 });

});

</script>

<script type="text/javascript">

document.ready=carregaPreview();

</script>

<script type="text/javascript">

  var parts = window.location.search.substr(1).split("&");

  var $_GET = {};

  

  for (var i = 0; i < parts.length; i++) {

      var temp = parts[i].split("=");

      $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);

  }



  if($_GET['tab_sel'] != null && $_GET['tab_sel'] == 2) {

    $('.to-hide').hide();

  }

  else {

    $('.to-hide').show();

  }

  

  $('.nav-tab').click(function() {

    if(this.id == 'nav-tab-stats') {

      $('.to-hide').hide();

    }

    else {

      $('.to-hide').show();

    }

  });

</script>

</body>

<!-- END BODY -->

</html>

