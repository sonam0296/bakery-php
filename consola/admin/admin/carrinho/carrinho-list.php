<?php include_once('../inc_pages.php'); ?>
<?php //ini_set('display_errors', 1);
  /* 
   * Paging
   */
  
  // ordenação
  $sOrder = " ORDER BY id ASC";
  $colunas = array('nome_cliente', 'ip', 'data', 'quantidade', 'preco', '');
  if(isset($_REQUEST['order'])) {
    $sOrder = " ORDER BY ";
    $i=0;
    
    for($i=0; $i<sizeof($_REQUEST['order']); $i++) {
     if($i>0) $sOrder .= ", ";
     $sOrder .= $colunas[$_REQUEST['order'][$i]["column"]]." ".$_REQUEST['order'][$i]["dir"];
    }
  }
  
  // pesquisa
  $where_pesq = "";
  if(isset($_REQUEST['action']) && $_REQUEST['action']=="filter") {
    $pesq_cliente = $_REQUEST['form_cliente'];
    $pesq_ip= utf8_decode($_REQUEST['form_ip']);
    $pesq_data = $_REQUEST['form_data'];
    $pesq_itens = $_REQUEST['form_itens'];

    if($pesq_ip != "") $where_pesq .= " AND cart.ip LIKE '%$pesq_ip%'";
    if($pesq_data != "") $where_pesq .= " AND cart.data LIKE '$pesq_data%'";
    if($pesq_itens != "") $where_pesq .= " AND cart.quantidade = '$pesq_itens'";
    if($pesq_cliente != "") {
      if($pesq_cliente == 0) {
        $where_pesq .= " AND (cart.id_cliente IS NULL OR cart.id_cliente = '' OR cart.id_cliente = 0)";
      }
      else {
        $where_pesq .= " AND (cart.id_cliente = '$pesq_cliente')"; 
      }
    }
  }
  
  $iDisplayLength = intval($_REQUEST['length']);
  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
  $iDisplayStart = intval($_REQUEST['start']);
  $sEcho = intval($_REQUEST['draw']);
  
  $query_rsTotal = "SELECT cart.id, cart.data, cart.ip, SUM(cart.quantidade) as qtd_total, SUM(cart.quantidade * cart.preco) as preco_total, cli.nome as nome_cliente, cli.email as email_cliente FROM carrinho cart LEFT JOIN clientes cli ON cart.id_cliente = cli.id WHERE cart.id > '0'".$where_pesq." GROUP BY cart.session ".$sOrder;
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
  $rsTotal->execute();
  $totalRows_rsTotal = $rsTotal->rowCount();
  DB::close();

  $iTotalRecords = $totalRows_rsTotal;
  
  $query_rsTotal = "SELECT cart.id, cart.data, cart.ip, SUM(cart.quantidade) as qtd_total, SUM(cart.quantidade * cart.preco) as preco_total, cli.nome as nome_cliente, cli.email as email_cliente FROM carrinho cart LEFT JOIN clientes cli ON cart.id_cliente = cli.id WHERE cart.id > '0'".$where_pesq." GROUP BY cart.session ".$sOrder." LIMIT $iDisplayStart, $iDisplayLength";
  $rsTotal = DB::getInstance()->prepare($query_rsTotal);
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

    $nome_cliente = "---";
    if($row_rsTotal['nome_cliente'] != NULL) {
      $nome_cliente = utf8_encode($row_rsTotal['nome_cliente']." - ".$row_rsTotal['email_cliente']);
    }

    $ip = $row_rsTotal['ip'];
    $data = $row_rsTotal['data'];
    $itens = $row_rsTotal['qtd_total'];
    $total = number_format($row_rsTotal['preco_total'], 2, ',', '.')."&pound;";
    
    $records["data"][] = array(
    $nome_cliente,
    $ip,
    $data,
    $itens,
    $total,
    '<a href="carrinho-detalhe.php?id='.$id.'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> '.$RecursosCons->RecursosCons['ver_detalhe'].'</a>',
    );
    
    $i++;
  }

  $records["draw"] = $sEcho;
  $records["recordsTotal"] = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;
  
  echo json_encode($records);
?>
