<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);
  /* 
   * Paging
   */
  
  // ordenação
  $sOrder = " ORDER BY id ASC";
  $colunas = array('nome', 'email', 'nome_codigo', 'data', '');
  if(isset($_REQUEST['order'])) {
	  $sOrder = " ORDER BY ";
	  if(sizeof($_REQUEST['order']) > 1) {
		  for($i=0; $i<sizeof($_REQUEST['order']); $i++) {
			 if($i>0) $sOrder .= ", ";
			 $sOrder .= $colunas[$_REQUEST['order'][$i]["column"]]." ".$_REQUEST['order'][$i]["dir"];
		  }
	  } elseif($colunas[$_REQUEST['order'][0]["column"]]) $sOrder .= $colunas[$_REQUEST['order'][0]["column"]]." ".$_REQUEST['order'][0]["dir"];
  }
  
  // pesquisa
  $where_pesq = "";
  if(isset($_REQUEST['action']) && $_REQUEST['action']=="filter") {
  	$pesq_nome = utf8_decode($_REQUEST['form_nome']);
    $pesq_email = utf8_decode($_REQUEST['form_email']);
    $pesq_codigo = utf8_decode($_REQUEST['form_codigo']);
    $pesq_data = $_REQUEST['form_data'];
  	
  	if($pesq_nome != "") $where_pesq .= " AND cli.nome LIKE '%$pesq_nome%'";
    if($pesq_email != "") $where_pesq .= " AND c.email LIKE '%$pesq_email%'";
    if($pesq_codigo != "") $where_pesq .= " AND (cp.nome LIKE '%$pesq_codigo%' OR cp.codigo LIKE '%$pesq_codigo%')";
    if($pesq_data != "") $where_pesq .= " AND c.data LIKE '$pesq_data'";
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT c.data as data, c.email as email, cli.nome as nome, cp.nome as nome_codigo, cp.codigo as codigo FROM codigos_promocionais_emails c, clientes cli, codigos_promocionais cp WHERE c.id_cliente = cli.id AND c.id_codigo = cp.id".$where_pesq.$sOrder;
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT c.data as data, c.email as email, cli.nome as nome, cp.nome as nome_codigo, cp.codigo as codigo, cp.id as id FROM codigos_promocionais_emails c, clientes cli, codigos_promocionais cp WHERE c.id_cliente = cli.id AND c.id_codigo = cp.id".$where_pesq.$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->query($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();
  
  $records = array();
  $records["data"] = array(); 

  $end = $iDisplayStart + $iDisplayLength;
  $end = $end > $iTotalRecords ? $iTotalRecords : $end;
  
  $i = $iDisplayStart;
  while($i < $end && $row_rsTotal = $rsTotal->fetch()) {
    $id = $row_rsTotal['id'];
    $nome = utf8_encode($row_rsTotal['nome']);
    $email = utf8_encode($row_rsTotal['email']);
    $nome_codigo = utf8_encode($row_rsTotal['nome_codigo']);
    $codigo = utf8_encode($row_rsTotal['codigo']);
    $data = $row_rsTotal['data'];
	  
    $records["data"][] = array(
	  $nome,
    $email,
	  $nome_codigo." - ".$codigo,
    $data,
	  '<a href="cod-promo-edit.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> Editar</a>',
    );
	  
	  $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>