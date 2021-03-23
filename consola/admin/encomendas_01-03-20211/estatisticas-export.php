<?php header("Content-type: text/html; charset=UTF-8"); ?>
<?php include_once('../inc_pages.php'); ?>
<?php //error_reporting(E_ALL); ini_set("display_errors", "1");

set_time_limit(0);

if($_POST['op'] == 'exportarGeral') {
  $tipo = $_POST['tipo'];
  $cliente = $_POST['cliente'];
  $pagamento = $_POST['pagamento'];
  $cod_prom = $_POST['cod_prom'];
  $estado = $_POST['estado'];
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  
  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  $left_join = '';
  $where = '';
  if($cliente != '') {
    $where .= ' AND e.id_cliente =:id_cliente';
  }

  if($pagamento != '') {
    $where .= ' AND e.met_pagamt_id =:met_pagamt_id';
  }

  if($tipo != '') {
    $left_join .= " LEFT JOIN clientes c ON c.id = e.id_cliente";
    $where .= " AND c.tipo =:tipo";
  }

  if($estado != '') {
    $where .= " AND e.estado =:estado";
  }

  if($cod_prom != '') {
    $where .= " AND e.codigo_promocional = :cod_prom";
  }

  if($datai != '' && $dataf == '') {
    $where .= " AND DATE(e.data) >= :datai";
  }

  if($dataf != ''  && $datai == '') {
    $where .= " AND DATE(e.data) <= :dataf";
  }

  if($dataf != ''  && $datai != '') {
    $where .= " AND e.data BETWEEN :datai AND :dataf";
  }

  $query_rsEncomendas = "SELECT e.* FROM encomendas e".$left_join." WHERE e.id > 0".$where." ORDER BY e.data DESC";
  $rsEncomendas = DB::getInstance()->prepare($query_rsEncomendas);
  if($cliente != '') {
    $rsEncomendas->bindParam(':id_cliente', $cliente, PDO::PARAM_INT);
  }
  if($pagamento != '') {
    $rsEncomendas->bindParam(':met_pagamt_id', $pagamento, PDO::PARAM_INT);
  }
  if($tipo != '') {
    $rsEncomendas->bindParam(':tipo', $tipo, PDO::PARAM_INT);
  }
  if($estado != '') {
    $rsEncomendas->bindParam(':estado', $estado, PDO::PARAM_INT);
  }
  if($cod_prom != '') {
    $rsEncomendas->bindParam(':cod_prom', $cod_prom, PDO::PARAM_STR, 5);
  }
  if($datai != '') {
    $rsEncomendas->bindParam(':datai', $datai, PDO::PARAM_STR, 5);
  }
  if($dataf != '') {
    $rsEncomendas->bindParam(':dataf', $dataf, PDO::PARAM_STR, 5);
  }
  $rsEncomendas->execute();
  $totalRows_rsEncomendas = $rsEncomendas->rowCount();

  $estado_txt = '';

  if($estado == 1) {
    $estado_txt = "A aguardar pagamento";
  }
  else if($estado == 2) {
    $estado_txt = "Em processamento";
  }
  else if($estado == 3) {
    $estado_txt = "Enviada";
  }
  else if($estado == 4) {
    $estado_txt = "Concluída";
  }
  else if($estado == 5) {
    $estado_txt = "Anulada";
  }

  $tipo_cli = '';

  if($tipo == 1) {
    $tipo_cli = "Consumidor Final";
  }
  else if($tipo == 2) {
    $tipo_cli = "Empresas";
  }

  $query_rsMetPagamento = "SELECT nome_interno FROM met_pagamento_pt WHERE id=:id";
  $rsMetPagamento = DB::getInstance()->prepare($query_rsMetPagamento);
  $rsMetPagamento->bindParam(':id', $pagamento, PDO::PARAM_INT);
  $rsMetPagamento->execute();
  $row_rsMetPagamento = $rsMetPagamento->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsMetPagamento = $rsMetPagamento->rowCount();

  $query_rsCliente = "SELECT nome FROM clientes WHERE id=:id";
  $rsCliente = DB::getInstance()->prepare($query_rsCliente);
  $rsCliente->bindParam(':id', $cliente, PDO::PARAM_INT);
  $rsCliente->execute();
  $row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsCliente = $rsCliente->rowCount();

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A1', utf8_encode('Estatísticas - Filtros'));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A3', utf8_encode('Data Início'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B3', $datai);

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A4', utf8_encode('Data Fim'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B4', $dataf);

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A5', utf8_encode('Estado da encomenda'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($estado_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Cód. Promocianal'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($cod_prom));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Mét. Pagamento'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($row_rsMetPagamento['nome_interno']));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($tipo_cli));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A9', utf8_encode('Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B9', utf8_encode($row_rsCliente['nome']));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A9')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Número'))
              ->setCellValue('B'.$i, utf8_encode('Data'))
              ->setCellValue('C'.$i, utf8_encode('Nome'))
              ->setCellValue('D'.$i, "Valor (\xE2\x82\xAc)")
              ->setCellValue('E'.$i, utf8_encode('Mét. Pagamento'))
              ->setCellValue('F'.$i, utf8_encode('Estado'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'G'.$i)->getFont()->setBold(true);

  $i++;

  if($totalRows_rsEncomendas > 0) {
    while($row_rsEncomendas = $rsEncomendas->fetch()) {
      $query_rsMetPagamento = "SELECT nome_interno FROM met_pagamento_pt WHERE id=:id";
      $rsMetPagamento = DB::getInstance()->prepare($query_rsMetPagamento);
      $rsMetPagamento->bindParam(':id', $row_rsEncomendas['met_pagamt_id'], PDO::PARAM_INT);
      $rsMetPagamento->execute();
      $row_rsMetPagamento = $rsMetPagamento->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsMetPagamento = $rsMetPagamento->rowCount();

      $estado = '';

      if($row_rsEncomendas['estado'] == 1) {
        $estado = "A aguardar pagamento";
      }
      else if($row_rsEncomendas['estado'] == 2) {
        $estado = "Em processamento";
      }
      else if($row_rsEncomendas['estado'] == 3) {
        $estado = "Enviada";
      }
      else if($row_rsEncomendas['estado'] == 4) {
        $estado = "Concluída";
      }
      else if($row_rsEncomendas['estado'] == 5) {
        $estado = "Anulada";
      } 

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($row_rsEncomendas['numero']))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEncomendas['data']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEncomendas['nome']))
                  ->setCellValue('D'.$i, utf8_encode(number_format($row_rsEncomendas['valor_c_iva'], 2, ',', '.'))." \xE2\x82\xAc")
                  ->setCellValue('E'.$i, utf8_encode($row_rsMetPagamento['nome_interno']))
                  ->setCellValue('F'.$i, utf8_encode($estado));

      $i++;
    }
  }

  // Set active sheet index to the first sheet, so Excel opens this as the first sheet
  $objPHPExcel->setActiveSheetIndex(0);

  foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  DB::close();

  // Save Excel 2007 file
  $callStartTime = microtime(true);

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('report.xlsx');
  $callEndTime = microtime(true);
  $callTime = $callEndTime - $callStartTime;
}

if($_GET['op'] == 'exporta_resultados') {
  $file = "report.xlsx";
    
  header("Location: ".$file);
}
 
?>