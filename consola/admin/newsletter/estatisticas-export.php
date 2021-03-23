<?php header("Content-type: text/html; charset=UTF-8"); ?>
<?php include_once('../inc_pages.php'); ?>
<?php include_once('estatisticas-funcoes.php'); ?>
<?php //error_reporting(E_ALL); ini_set("display_errors", "1");

set_time_limit(0);

if($_POST['op'] == 'exportarResultadosGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  if($newsletter > 0) {
    if($grupo > 0) {
      $query_rsNews = "SELECT n.titulo FROM newsletters n, newsletters_historico h WHERE n.id='$newsletter' AND n.id = h.newsletter_id AND h.grupo > 0 AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
      $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

      $newsletter_txt = $row_rsNews['titulo'];

      $query_rsNewsletters = "SELECT id, newsletter_id, emails_total, emails_enviados, emails_vistos, emails_vistos_unicos FROM newsletters_historico WHERE newsletter_id='$newsletter' AND grupo = '$grupo' AND data >= '$datai' AND data <= '$dataf'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
    else {
      $query_rsNews = "SELECT n.titulo FROM newsletters n, newsletters_historico h WHERE n.id='$newsletter' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
      $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

      $newsletter_txt = $row_rsNews['titulo'];

      $query_rsNewsletters = "SELECT id, newsletter_id, emails_total, emails_enviados, emails_vistos, emails_vistos_unicos FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
  }
  else if($tipo > 0 && $grupo > 0) {
    $query_rsNews = "SELECT n.titulo FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();

    $newsletter_txt = 'Todas';

    $query_rsNewsletters = "SELECT hist.id, hist.newsletter_id, hist.emails_total, hist.emails_enviados, hist.emails_vistos, hist.emails_vistos_unicos FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($tipo > 0) {
    $query_rsNews = "SELECT n.titulo FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();

    $newsletter_txt = 'Todas';

    $query_rsNewsletters = "SELECT hist.id, hist.newsletter_id, hist.emails_total, hist.emails_enviados, hist.emails_vistos, hist.emails_vistos_unicos FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($grupo > 0) {
    $query_rsNews = "SELECT n.titulo FROM newsletters n, newsletters_historico h WHERE h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();

    $newsletter_txt = 'Todas';

    $query_rsNewsletters = "SELECT hist.id, hist.newsletter_id, hist.emails_total, hist.emails_enviados, hist.emails_vistos, hist.emails_vistos_unicos FROM newsletters_historico hist, newsletters n WHERE hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else {
    $query_rsNews = "SELECT n.titulo FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();

    $newsletter_txt = 'Todas';

    $query_rsNewsletters = "SELECT id, newsletter_id, emails_total, emails_enviados, emails_vistos, emails_vistos_unicos FROM newsletters_historico WHERE data >= '$datai' AND data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }

  if($totalRows_rsNewsletters > 0) {
    $total_emails = 0;
    $total_enviados = 0;
    $total_vistos = 0;
    $total_vistos_unicos = 0;
    $i = 0;
    $subtotal = 0;
    $vistos = 0;
    $vistos_unicos = 0;
    $n_links = 0;
    $n_cliques = 0;
    $n_pedidos_rem = 0;
    $array_news = array();
    $perc1 = 0;
    $perc2 = 0;
    $perc3 = 0;
    $media1 = 0;
    $n_devolvidos = 0;
    $n_recebidos = 0;
    $n_cancelamentos = 0;
    $array_pessoas = array();

    $newsletter_lista = '';

    while($row_rsNewsletters = $rsNewsletters->fetch()) {
      //Emais recebidos/devolvidos
      $query_rsSelect = "SELECT COUNT(id) as total FROM news_emails_devolvidos WHERE newsletter_id = :newsletter_id AND newsletter_id_historico = :newsletter_id_historico";
      $rsSelect = DB::getInstance()->prepare($query_rsSelect);
      $rsSelect->bindParam(':newsletter_id', $row_rsNewsletters['newsletter_id'], PDO::PARAM_INT);
      $rsSelect->bindParam(':newsletter_id_historico', $row_rsNewsletters['id'], PDO::PARAM_INT);
      $rsSelect->execute();
      $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

      $res2 = $row_rsNewsletters['emails_enviados'] - $row_rsSelect['total'];

      $n_devolvidos += $row_rsSelect['total'];
      $n_recebidos += $res2;

      //Adicionar o ID ao array para saber quem clicou nos links e quais
      $newsletter_lista .= $row_rsNewsletters['id'].",";

      /********************************************************************/
      $total_emails += $row_rsNewsletters['emails_total'];
      $total_enviados += $row_rsNewsletters['emails_enviados'];
      $total_vistos += $row_rsNewsletters['emails_vistos'];
      $total_vistos_unicos += $row_rsNewsletters['emails_vistos_unicos'];

      //Calcular total de links na newsletter
      if(!in_array($row_rsNewsletters['newsletter_id'], $array_news)) {
        $query_rsLinks = "SELECT COUNT(DISTINCT(url)) as total FROM news_links WHERE newsletter_id_historico='".$row_rsNewsletters['id']."'";
        $rsLinks = DB::getInstance()->prepare($query_rsLinks);
        $rsLinks->execute();
        $totalRows_rsLinks = $rsLinks->rowCount();
        $row_rsLinks = $rsLinks->fetch(PDO::FETCH_ASSOC);

        $n_links += $row_rsLinks['total'];
        array_push($array_news, $row_rsNewsletters['newsletter_id']);
      }

      //Calcular total de cliques na newsletter
      $query_rsCliques = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico='".$row_rsNewsletters['id']."'";
      $rsCliques = DB::getInstance()->prepare($query_rsCliques);
      $rsCliques->execute();
      $totalRows_rsCliques = $rsCliques->rowCount();
      $row_rsCliques = $rsCliques->fetch(PDO::FETCH_ASSOC);

      $n_cliques += $row_rsCliques['total'];

      //Calcular total de cliques para remover subscrição nas newsletters
      // $query_rsCliquesRem = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico='".$row_rsNewsletters['id']."' AND url LIKE '%news_remover.php%'";
      // $rsCliquesRem = DB::getInstance()->prepare($query_rsCliquesRem);
      // $rsCliquesRem->execute();
      // $totalRows_rsCliquesRem = $rsCliquesRem->rowCount();
      // $row_rsCliquesRem = $rsCliquesRem->fetch(PDO::FETCH_ASSOC);
      // DB::close();

      // $n_pedidos_rem += $row_rsCliquesRem['total'];

      $query_rsPedidosRem = "SELECT COUNT(id) as total FROM news_remover WHERE newsletter_id_historico='".$row_rsNewsletters['id']."'";
      $rsPedidosRem = DB::getInstance()->prepare($query_rsPedidosRem);
      $rsPedidosRem->execute();
      $totalRows_rsPedidosRem = $rsPedidosRem->rowCount();
      $row_rsPedidosRem = $rsPedidosRem->fetch(PDO::FETCH_ASSOC);

      $n_cancelamentos += $row_rsPedidosRem['total'];

      //Calcular média entre a data de envio e a data de abertura
      $query_rsMedia = "SELECT data_visto, data_envio FROM newsletters_vistos WHERE newsletter_id_historico='".$row_rsNewsletters['id']."'";
      $rsMedia = DB::getInstance()->prepare($query_rsMedia);
      $rsMedia->execute();
      $totalRows_rsMedia = $rsMedia->rowCount();

      while($row = $rsMedia->fetch()) {
        if($row['data_visto'] != NULL) {
          $min = round((strtotime($row['data_visto']) - strtotime($row['data_envio']))/(60));
          $subtotal+=$min;
          $i++;
        }
      }

      //Calcular a percentagem de visualizações únicas
      $query_rsPerc = "SELECT emails_vistos, emails_vistos_unicos FROM newsletters_historico WHERE id='".$row_rsNewsletters['id']."'";
      $rsPerc = DB::getInstance()->prepare($query_rsPerc);
      $rsPerc->execute();
      $row_rsPerc = $rsPerc->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsPerc = $rsPerc->rowCount();

      $vistos += $row_rsPerc['emails_vistos'];
      $vistos_unicos += $row_rsPerc['emails_vistos_unicos'];

      //Calcular percentagem de pessoas que clicaram em algum link
      $query_rsPessoas = "SELECT codigo FROM news_links WHERE newsletter_id_historico='".$row_rsNewsletters['id']."' AND n_clicks > 0 GROUP BY codigo";
      $rsPessoas = DB::getInstance()->prepare($query_rsPessoas);
      $rsPessoas->execute();
      $totalRows_rsPessoas = $rsPessoas->rowCount();

      if($totalRows_rsPessoas > 0) {
        while($row_rsPessoas = $rsPessoas->fetch()) {
          // if(!in_array($row_rsPessoas['codigo'], $array_pessoas)) {
            array_push($array_pessoas, $row_rsPessoas['codigo']);
          // }
        }
      }
    }

    //Calcular percentagem de emails enviados
    $perc1 = round($total_enviados / $total_emails, 2) * 100;

    //Calcular percentagem de emails abertos
    $perc2 = round($total_vistos_unicos / $total_enviados, 2) * 100;

    //Calcular média entre a data de envio e a data de abertura
    $media1 = round($subtotal / $i, 2);

    //Calcular a percentagem de visualizações únicas
    $perc3 = round($vistos_unicos / $vistos, 3) * 100;

    //Calcular a percentagem de emails recebidos
    $perc4 = round($n_recebidos / $total_enviados, 2) * 100;

    //Calcular a percentagem de emails devolvidos
    $perc5 = round($n_devolvidos / $total_enviados, 2) * 100;

    //Calcular a percentagem de visualizações
    $perc6 = round($vistos / $total_enviados, 2) * 100;

    //Calcular a percentagem de cliques para remover subscrição em newsletters
    $perc7 = round($n_pedidos_rem / $n_cliques, 2) * 100;

    //Calcular a percentagem de pessoas que clicaram em algum link
    $perc8 = round(sizeof($array_pessoas) / $total_vistos_unicos, 2) * 100;

    //Calcular a percentagem cancelamentos de cancelamentos de subscrição de newsletters face às visualizações únicas
    $perc9 = round($n_cancelamentos / $total_vistos_unicos, 4) * 100;


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
                ->setCellValue('A5', utf8_encode('Resultados'));
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B5', utf8_encode($resultados_txt));

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B6', utf8_encode($tipo_txt));

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B7', utf8_encode($grupo_txt));

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A8', utf8_encode('Newsletter'));
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B8', utf8_encode($newsletter_txt));

    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
    $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A11', utf8_encode('Resultados'));

    $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

    $i=13;

    if($totalRows_rsNews > 1) {
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A1', utf8_encode('Total de newsletters:'))
                  ->setCellValue('B1', $totalRows_rsNews)
                  ->setCellValue('C1', utf8_encode('nº de newsletters com agendamentos no período de tempo escolhido'));

      $i++;
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Total de agendamentos:'))
                ->setCellValue('B'.$i, $totalRows_rsNewsletters)
                ->setCellValue('C'.$i, utf8_encode('nº de agendamentos para esta(s) newsletter(s) no período de tempo escolhido'));  

    $i++;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Total de emails:'))
                ->setCellValue('B'.$i, $total_emails)
                ->setCellValue('C'.$i, utf8_encode('nº de emails escolhidos para receber a(s) newsletter(s)'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Enviados:'))
                ->setCellValue('B'.$i, $total_enviados)
                ->setCellValue('C'.$i, utf8_encode('nº de emails que foram enviados'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Percentagem de enviados:'))
                ->setCellValue('B'.$i, $perc1."%")
                ->setCellValue('C'.$i, utf8_encode('percentagem de emails enviados'));

    $i++;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Recebidos:'))
                ->setCellValue('B'.$i, $n_recebidos)
                ->setCellValue('C'.$i, utf8_encode('nº de emails que receberam a(s) newsletter(s)'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Percentagem de recebidos:'))
                ->setCellValue('B'.$i, $perc4."%")
                ->setCellValue('C'.$i, utf8_encode('percentagem de emails recebidos'));

    $i++;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Devolvidos:'))
                ->setCellValue('B'.$i, $n_devolvidos)
                ->setCellValue('C'.$i, utf8_encode('nº de emails que não receberam a(s) newsletter(s)'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Percentagem de devolvidos:'))
                ->setCellValue('B'.$i, $perc5."%")
                ->setCellValue('C'.$i, utf8_encode('percentagem de emails devolvidos'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Visualizações:'))
                ->setCellValue('B'.$i, $total_vistos)
                ->setCellValue('C'.$i, utf8_encode('nº de visualizações'));

    $i++;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Percentagem de visualizações:'))
                ->setCellValue('B'.$i, $perc6."%")
                ->setCellValue('C'.$i, utf8_encode('percentagem de visualizações'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Visualizações únicas:'))
                ->setCellValue('B'.$i, $total_vistos_unicos)
                ->setCellValue('C'.$i, utf8_encode('nº de visualizações únicas (uma por cada email)'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Percentagem de visualizações únicas:'))
                ->setCellValue('B'.$i, $perc2."%")
                ->setCellValue('C'.$i, utf8_encode('percentagem de visualizações únicas'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Rácio entre visualizações:'))
                ->setCellValue('B'.$i, $perc3."%")
                ->setCellValue('C'.$i, utf8_encode('do nº total de visualizações, a percentagem correspondente às visualizações únicas'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Média de tempo:'))
                ->setCellValue('B'.$i, $media1." minuto(s)")
                ->setCellValue('C'.$i, utf8_encode('média de tempo entre receber a newsletter e abrir o email'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Total de links:'))
                ->setCellValue('B'.$i, $n_links)
                ->setCellValue('C'.$i, utf8_encode('nº de links únicos presentes na(s) newsletter(s)'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Total de cliques:'))
                ->setCellValue('B'.$i, $n_cliques)
                ->setCellValue('C'.$i, utf8_encode('nº de cliques em todos os links da(s) newsletter(s)'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Percentagem de cliques:'))
                ->setCellValue('B'.$i, $perc8."%")
                ->setCellValue('C'.$i, utf8_encode('percentagem de clientes que clicaram em algum link'));

    // $i++;
    
    // $objPHPExcel->setActiveSheetIndex(0)
    //             ->setCellValue('A'.$i, utf8_encode('Total de pedidos de remoção:'))
    //             ->setCellValue('B'.$i, $n_pedidos_rem)
    //             ->setCellValue('C'.$i, utf8_encode('nº de cliques no link para anular a subscrição de newsletters'));

    // $i++;
    
    // $objPHPExcel->setActiveSheetIndex(0)
    //             ->setCellValue('A'.$i, utf8_encode('Percentagem de pedidos de remoção:'))
    //             ->setCellValue('B'.$i, $perc7."%")
    //             ->setCellValue('C'.$i, utf8_encode('percentagem de cliques no link para anular a subscrição de newsletters em relação ao nº de cliques total'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Total de cancelamentos:'))
                ->setCellValue('B'.$i, $n_cancelamentos)
                ->setCellValue('C'.$i, utf8_encode('nº de cancelamentos efetivos de subscrição de newsletter'));

    $i++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Percentagem de cancelamentos:'))
                ->setCellValue('B'.$i, $perc9."%")
                ->setCellValue('C'.$i, utf8_encode('percentagem de clientes que anularam a subscrição de newsletters em relação ao nº de visualizações únicas'));

    $objPHPExcel->getActiveSheet()->getStyle('A13:A32')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B13:B32')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('C13:C32')->getFont()->getColor()->setRGB('80898E');
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

if($_POST['op'] == 'exportarResultadosNews') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  
  $resultados_txt = 'Detalhado';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  if($newsletter > 0) {
    if($grupo > 0) {
      $query_rsNews = "SELECT n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.grupo = '$grupo' AND h.data >= '$datai' AND h.data <= '$dataf' AND n.id='$newsletter' GROUP BY n.id";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
    else {
      $query_rsNews = "SELECT n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' AND n.id='$newsletter' GROUP BY n.id";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
  }
  else if($tipo > 0 && $grupo > 0) {
    $query_rsNews = "SELECT n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
  }
  else if($tipo > 0) {
    $query_rsNews = "SELECT n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
  }
  else if($grupo > 0) {
    $query_rsNews = "SELECT n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
  }
  else {
    $query_rsNews = "SELECT n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
  }

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A13', utf8_encode('Título da Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B13', utf8_encode('Data de Envio'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('C13', utf8_encode('Nº de Agendamentos'));

  $objPHPExcel->getActiveSheet()->getStyle('A13:C13')->getFont()->setBold(true);

  $i=14;

  if($totalRows_rsNews > 0) {
    while($row_rsNews = $rsNews->fetch()) {
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($row_rsNews['titulo']))
                  ->setCellValue('B'.$i, utf8_encode($row_rsNews['data_envio']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsNews['total']));

      $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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

if($_POST['op'] == 'exportarResultadosDetalhes') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $id_news = $_POST['newsletter'];
  
  $resultados_txt = 'Detalhado';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  date_default_timezone_set('Europe/London');
  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  require_once '../Classes/PHPExcel.php';

  $objPHPExcel = new PHPExcel();

  
  if($id_news > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$id_news'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo'];

    if($grupo > 0) {
      $query_rsNewsletters = "SELECT id, newsletter_id, emails_enviados, emails_total, emails_vistos, emails_vistos_unicos, estado FROM newsletters_historico WHERE newsletter_id='$id_news' AND grupo = '$grupo' AND data >= '$datai' AND data <= '$dataf' ORDER BY CONCAT(data, ' ', hora) DESC";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $row_rsNewsletters = $rsNewsletters->fetchAll();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
    else {
      $query_rsNewsletters = "SELECT id, newsletter_id, emails_enviados, emails_total, emails_vistos, emails_vistos_unicos, estado FROM newsletters_historico WHERE newsletter_id='$id_news' AND data >= '$datai' AND data <= '$dataf' ORDER BY CONCAT(data, ' ', hora) DESC";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $row_rsNewsletters = $rsNewsletters->fetchAll();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }    

    if($totalRows_rsNewsletters > 0) {
      $total_emails = 0;
      $total_enviados = 0;
      $total_vistos = 0;
      $total_vistos_unicos = 0;
      $i = 0;
      $subtotal = 0;
      $vistos = 0;
      $vistos_unicos = 0;
      $n_links = 0;
      $n_cliques = 0;
      $n_pedidos_rem = 0;
      $array_news = array();
      $perc1 = 0;
      $perc2 = 0;
      $perc3 = 0;
      $media1 = 0;
      $n_devolvidos = 0;
      $n_recebidos = 0;
      $n_cancelamentos = 0;
      $array_pessoas = array();

      $newsletter_lista = '';

      foreach($row_rsNewsletters as $newsletter) {
        //Emais recebidos/devolvidos
        $query_rsSelect = "SELECT COUNT(id) as total FROM news_emails_devolvidos WHERE newsletter_id = :newsletter_id AND newsletter_id_historico = :newsletter_id_historico";
        $rsSelect = DB::getInstance()->prepare($query_rsSelect);
        $rsSelect->bindParam(':newsletter_id', $newsletter['newsletter_id'], PDO::PARAM_INT);
        $rsSelect->bindParam(':newsletter_id_historico', $newsletter['id'], PDO::PARAM_INT);
        $rsSelect->execute();
        $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

        $res2 = $newsletter['emails_enviados'] - $row_rsSelect['total'];

        $n_devolvidos += $row_rsSelect['total'];
        $n_recebidos += $res2;

        //Adicionar o ID ao array para saber quem clicou nos links e quais
        $newsletter_lista .= $newsletter['id'].",";

        /********************************************************************/
        $total_emails += $newsletter['emails_total'];
        $total_enviados += $newsletter['emails_enviados'];
        $total_vistos += $newsletter['emails_vistos'];
        $total_vistos_unicos += $newsletter['emails_vistos_unicos'];

        //Calcular total de links na newsletter
        if(!in_array($newsletter['newsletter_id'], $array_news)) {
          $query_rsLinks = "SELECT COUNT(DISTINCT(url)) as total FROM news_links WHERE newsletter_id_historico='".$newsletter['id']."'";
          $rsLinks = DB::getInstance()->prepare($query_rsLinks);
          $rsLinks->execute();
          $totalRows_rsLinks = $rsLinks->rowCount();
          $row_rsLinks = $rsLinks->fetch(PDO::FETCH_ASSOC);

          $n_links += $row_rsLinks['total'];
          array_push($array_news, $newsletter['newsletter_id']);
        }

        //Calcular total de cliques na newsletter
        $query_rsCliques = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico='".$newsletter['id']."'";
        $rsCliques = DB::getInstance()->prepare($query_rsCliques);
        $rsCliques->execute();
        $totalRows_rsCliques = $rsCliques->rowCount();
        $row_rsCliques = $rsCliques->fetch(PDO::FETCH_ASSOC);

        $n_cliques += $row_rsCliques['total'];

        //Calcular total de cliques para remover subscrição nas newsletters
        // $query_rsCliquesRem = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico='".$newsletter['id']."' AND url LIKE '%news_remover.php%'";
        // $rsCliquesRem = DB::getInstance()->prepare($query_rsCliquesRem);
        // $rsCliquesRem->execute();
        // $totalRows_rsCliquesRem = $rsCliquesRem->rowCount();
        // $row_rsCliquesRem = $rsCliquesRem->fetch(PDO::FETCH_ASSOC);
      

        // $n_pedidos_rem += $row_rsCliquesRem['total'];

        $query_rsPedidosRem = "SELECT COUNT(id) as total FROM news_remover WHERE newsletter_id_historico='".$newsletter['id']."'";
        $rsPedidosRem = DB::getInstance()->prepare($query_rsPedidosRem);
        $rsPedidosRem->execute();
        $totalRows_rsPedidosRem = $rsPedidosRem->rowCount();
        $row_rsPedidosRem = $rsPedidosRem->fetch(PDO::FETCH_ASSOC);

        $n_cancelamentos += $row_rsPedidosRem['total'];

        //Calcular média entre a data de envio e a data de abertura
        $query_rsMedia = "SELECT data_visto, data_envio FROM newsletters_vistos WHERE newsletter_id_historico='".$newsletter['id']."'";
        $rsMedia = DB::getInstance()->prepare($query_rsMedia);
        $rsMedia->execute();
        $totalRows_rsMedia = $rsMedia->rowCount();

        while($row = $rsMedia->fetch()) {
          if($row['data_visto'] != NULL) {
            $min = round((strtotime($row['data_visto']) - strtotime($row['data_envio']))/(60));
            $subtotal+=$min;
            $i++;
          }
        }

        //Calcular a percentagem de visualizações únicas
        $query_rsPerc = "SELECT emails_vistos, emails_vistos_unicos FROM newsletters_historico WHERE id='".$newsletter['id']."'";
        $rsPerc = DB::getInstance()->prepare($query_rsPerc);
        $rsPerc->execute();
        $row_rsPerc = $rsPerc->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsPerc = $rsPerc->rowCount();

        $vistos += $row_rsPerc['emails_vistos'];
        $vistos_unicos += $row_rsPerc['emails_vistos_unicos'];

        //Calcular percentagem de pessoas que clicaram em algum link
        $query_rsPessoas = "SELECT codigo FROM news_links WHERE newsletter_id_historico='".$newsletter['id']."' AND n_clicks > 0 GROUP BY codigo";
        $rsPessoas = DB::getInstance()->prepare($query_rsPessoas);
        $rsPessoas->execute();
        $totalRows_rsPessoas = $rsPessoas->rowCount();

        if($totalRows_rsPessoas > 0) {
          while($row_rsPessoas = $rsPessoas->fetch()) {
            // if(!in_array($row_rsPessoas['codigo'], $array_pessoas)) {
              array_push($array_pessoas, $row_rsPessoas['codigo']);
            // }
          }
        }
      }

      //Calcular percentagem de emails enviados
      $perc1 = round($total_enviados / $total_emails, 2) * 100;

      //Calcular percentagem de emails abertos
      $perc2 = round($total_vistos_unicos / $total_enviados, 2) * 100;

      //Calcular média entre a data de envio e a data de abertura
      $media1 = round($subtotal / $i, 2);

      //Calcular a percentagem de visualizações únicas
      $perc3 = round($vistos_unicos / $vistos, 3) * 100;

      //Calcular a percentagem de emails recebidos
      $perc4 = round($n_recebidos / $total_enviados, 2) * 100;

      //Calcular a percentagem de emails devolvidos
      $perc5 = round($n_devolvidos / $total_enviados, 2) * 100;

      //Calcular a percentagem de visualizações
      $perc6 = round($vistos / $total_enviados, 2) * 100;

      // //Calcular a percentagem de cliques para remover subscrição em newsletters
      // $perc7 = round($n_pedidos_rem / $n_cliques, 2) * 100;

      //Calcular a percentagem de pessoas que clicaram em algum link
      $perc8 = round(sizeof($array_pessoas) / $total_vistos_unicos, 2) * 100;

      //Calcular a percentagem cancelamentos de cancelamentos de subscrição de newsletters face às visualizações únicas
      $perc9 = round($n_cancelamentos / $total_vistos_unicos, 4) * 100;

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
                  ->setCellValue('A5', utf8_encode('Resultados'));
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B5', utf8_encode($resultados_txt));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B6', utf8_encode($tipo_txt));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B7', utf8_encode($grupo_txt));

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A8', utf8_encode('Newsletter'));
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B8', utf8_encode($newsletter_txt));

      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
      $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A11', utf8_encode('Resultados'));

      $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

      $i=13;

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Total de agendamentos:'))
                  ->setCellValue('B'.$i, $totalRows_rsNewsletters)
                  ->setCellValue('C'.$i, utf8_encode('nº de agendamentos para esta(s) newsletter(s) no período de tempo escolhido'));  

      $i++;

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Total de emails:'))
                  ->setCellValue('B'.$i, $total_emails)
                  ->setCellValue('C'.$i, utf8_encode('nº de emails escolhidos para receber a(s) newsletter(s)'));

      $i++;
  
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Enviados:'))
                  ->setCellValue('B'.$i, $total_enviados)
                  ->setCellValue('C'.$i, utf8_encode('nº de emails que foram enviados'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Percentagem de enviados:'))
                  ->setCellValue('B'.$i, $perc1."%")
                  ->setCellValue('C'.$i, utf8_encode('percentagem de emails enviados'));

      $i++;

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Recebidos:'))
                  ->setCellValue('B'.$i, $n_recebidos)
                  ->setCellValue('C'.$i, utf8_encode('nº de emails que receberam a(s) newsletter(s)'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Percentagem de recebidos:'))
                  ->setCellValue('B'.$i, $perc4."%")
                  ->setCellValue('C'.$i, utf8_encode('percentagem de emails recebidos'));

      $i++;

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Devolvidos:'))
                  ->setCellValue('B'.$i, $n_devolvidos)
                  ->setCellValue('C'.$i, utf8_encode('nº de emails que não receberam a(s) newsletter(s)'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Percentagem de devolvidos:'))
                  ->setCellValue('B'.$i, $perc5."%")
                  ->setCellValue('C'.$i, utf8_encode('percentagem de emails devolvidos'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Visualizações:'))
                  ->setCellValue('B'.$i, $total_vistos)
                  ->setCellValue('C'.$i, utf8_encode('nº de visualizações'));

      $i++;

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Percentagem de visualizações:'))
                  ->setCellValue('B'.$i, $perc6."%")
                  ->setCellValue('C'.$i, utf8_encode('percentagem de visualizações'));

      $i++;
  
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Visualizações únicas:'))
                  ->setCellValue('B'.$i, $total_vistos_unicos)
                  ->setCellValue('C'.$i, utf8_encode('nº de visualizações únicas (uma por cada email)'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Percentagem de visualizações únicas:'))
                  ->setCellValue('B'.$i, $perc2."%")
                  ->setCellValue('C'.$i, utf8_encode('percentagem de visualizações únicas'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Rácio entre visualizações:'))
                  ->setCellValue('B'.$i, $perc3."%")
                  ->setCellValue('C'.$i, utf8_encode('do nº total de visualizações, a percentagem correspondente às visualizações únicas'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Média de tempo:'))
                  ->setCellValue('B'.$i, $media1." minuto(s)")
                  ->setCellValue('C'.$i, utf8_encode('média de tempo entre receber a newsletter e abrir o email'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Total de links:'))
                  ->setCellValue('B'.$i, $n_links)
                  ->setCellValue('C'.$i, utf8_encode('nº de links únicos presentes na(s) newsletter(s)'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Total de cliques:'))
                  ->setCellValue('B'.$i, $n_cliques)
                  ->setCellValue('C'.$i, utf8_encode('nº de cliques em todos os links da(s) newsletter(s)'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Percentagem de cliques:'))
                  ->setCellValue('B'.$i, $perc8."%")
                  ->setCellValue('C'.$i, utf8_encode('percentagem de clientes que clicaram em algum link'));

      // $i++;
      
      // $objPHPExcel->setActiveSheetIndex(0)
      //             ->setCellValue('A'.$i, utf8_encode('Total de pedidos de remoção:'))
      //             ->setCellValue('B'.$i, $n_pedidos_rem)
      //             ->setCellValue('C'.$i, utf8_encode('nº de cliques no link para anular a subscrição de newsletters'));

      // $i++;
  
      // $objPHPExcel->setActiveSheetIndex(0)
      //             ->setCellValue('A'.$i, utf8_encode('Percentagem de pedidos de remoção:'))
      //             ->setCellValue('B'.$i, $perc7."%")
      //             ->setCellValue('C'.$i, utf8_encode('percentagem de cliques no link para anular a subscrição de newsletters em relação ao nº de cliques total'));

      $i++;
      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Total de cancelamentos:'))
                  ->setCellValue('B'.$i, $n_cancelamentos)
                  ->setCellValue('C'.$i, utf8_encode('nº de cancelamentos efetivos de subscrição de newsletter'));

      $i++;
  
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Percentagem de cancelamentos:'))
                  ->setCellValue('B'.$i, $perc9."%")
                  ->setCellValue('C'.$i, utf8_encode('percentagem de clientes que anularam a subscrição de newsletters em relação ao nº de visualizações únicas'));

      $objPHPExcel->getActiveSheet()->getStyle('A13:A32')->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('B13:B32')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('C13:C32')->getFont()->getColor()->setRGB('80898E');

      $i+=3;

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A35', utf8_encode('Quem Clicou'));

      $objPHPExcel->getActiveSheet()->getStyle('A35')->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A35')->getFont()->getColor()->setRGB('80898E');

      $i+=2;

      //Varivável para guardar o valor atual da linha para se construir depois a tabela dos links das newsletters ao mesmo nível que esta tabela abaixo
      $j = $i;

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode('Email'))
                  ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
                  ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
                  ->setCellValue('D'.$i, utf8_encode('Cargo'))
                  ->setCellValue('E'.$i, utf8_encode('Telefone'))
                  ->setCellValue('F'.$i, utf8_encode('Tipo de Cliente'))
                  ->setCellValue('G'.$i, utf8_encode('Tipo de Newsletter'))
                  ->setCellValue('H'.$i, utf8_encode('Título da página clicada'))
                  ->setCellValue('I'.$i, utf8_encode('Data do último clique'))
                  ->setCellValue('J'.$i, utf8_encode('Nº de Cliques'))
                  ->setCellValue('K'.$i, utf8_encode('% Nº de Cliques'));

      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'K'.$i)->getFont()->setBold(true);

      $i++;

      $perc_n_clicks_total = 0;
      $total_n_clicks = 0;

      foreach($row_rsNewsletters as $newsletters) { 
        $query_rsQuemClicou = "SELECT l.*, h.grupo, n.tipo, e.nome FROM news_links l, newsletters_historico h, newsletters n, news_emails e WHERE l.newsletter_id_historico = h.id AND h.newsletter_id = n.id AND e.codigo = l.codigo AND l.n_clicks > 0 AND l.newsletter_id_historico = '".$newsletters['id']."' GROUP BY l.url, l.codigo, l.newsletter_id_historico ORDER BY e.nome ASC, l.url ASC";
        $rsQuemClicou = DB::getInstance()->prepare($query_rsQuemClicou);
        $rsQuemClicou->execute();
        $totalRows_rsQuemClicou = $rsQuemClicou->rowCount();
        
        if($totalRows_rsQuemClicou > 0) {
          while($row_rsQuemClicou = $rsQuemClicou->fetch()) { 
            $query_rsEmail = "SELECT id, empresa, nome, email, cargo, telefone FROM news_emails WHERE codigo = '".$row_rsQuemClicou['codigo']."'";
            $rsEmail = DB::getInstance()->prepare($query_rsEmail);
            $rsEmail->execute();
            $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

            $query_rsGrupo = "SELECT nome FROM news_grupos WHERE id = '".$row_rsQuemClicou['grupo']."'";
            $rsGrupo = DB::getInstance()->prepare($query_rsGrupo);
            $rsGrupo->execute();
            $row_rsGrupo = $rsGrupo->fetch(PDO::FETCH_ASSOC);
            $totalRows_rsGrupo = $rsGrupo->rowCount();

            if($totalRows_rsGrupo > 0) {
              $grupo = $row_rsGrupo['nome'];
            }
            else {
              $grupo = '';
            }

            $query_rsTipo = "SELECT nome FROM news_tipos_pt WHERE id = '".$row_rsQuemClicou['tipo']."'";
            $rsTipo = DB::getInstance()->prepare($query_rsTipo);
            $rsTipo->execute();
            $row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
            $totalRows_rsTipo = $rsTipo->rowCount();

            if($totalRows_rsTipo > 0) {
              $tipo = $row_rsTipo['nome'];
            }
            else {
              $tipo = '';
            }

            if($row_rsQuemClicou['data_ultimo_click']) {
              $data_ultimo_click = $row_rsQuemClicou['data_ultimo_click'];
            }
            else {
              $data_ultimo_click = '-';
            }

            $perc_n_clicks = number_format(round(($row_rsQuemClicou['n_clicks'] / $n_cliques) * 100, 1), 1, ',', '');
            $perc_n_clicks_total += ($row_rsQuemClicou['n_clicks'] / $n_cliques) * 100;

            $total_n_clicks += $row_rsQuemClicou['n_clicks'];

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, utf8_encode($row_rsEmail['email']))
                        ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                        ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                        ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                        ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                        ->setCellValue('F'.$i, utf8_encode($grupo))
                        ->setCellValue('G'.$i, utf8_encode($tipo))
                        ->setCellValue('H'.$i, utf8_encode($row_rsQuemClicou['url']))
                        ->setCellValue('I'.$i, utf8_encode($data_ultimo_click))
                        ->setCellValue('J'.$i, utf8_encode($row_rsQuemClicou['n_clicks']))
                        ->setCellValue('K'.$i, utf8_encode($perc_n_clicks."%"));

            $i++;
          }
        }
      }

      if($perc_n_clicks_total > 0 && $i > 38) {
        // $objPHPExcel->getActiveSheet()
        //             ->setCellValue('J'.$i, '=SUM(J38:J'.($i-1).')');

         $objPHPExcel->getActiveSheet()
                    ->setCellValue('J'.$i, utf8_encode($total_n_clicks));

        $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()
                    ->setCellValue('K'.$i, utf8_encode($perc_n_clicks_total."%"));

        $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getFont()->setBold(true);
      }


      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('N'.$j, utf8_encode('Título da página clicada'))
                  ->setCellValue('O'.$j, utf8_encode('Nº de Cliques'))
                  ->setCellValue('P'.$j, utf8_encode('% Nº de Cliques'));

      $objPHPExcel->getActiveSheet()->getStyle('N'.$j.':'.'P'.$j)->getFont()->setBold(true);

      $j++;


      $lista_agendamentos = '(';
      $perc_n_clicks_total = 0;
      $total_n_clicks = 0;

      foreach($row_rsNewsletters as $newsletters) { 
        $lista_agendamentos .= $newsletters['id'].",";
      }

      $lista_agendamentos = substr($lista_agendamentos, 0, -1);
      $lista_agendamentos .= ')';

      $query_rsQuemClicou = "SELECT l.* FROM news_links l, newsletters_historico h WHERE l.newsletter_id_historico = h.id AND l.n_clicks > 0 AND l.newsletter_id_historico IN $lista_agendamentos GROUP BY l.url ORDER BY l.url ASC";
      $rsQuemClicou = DB::getInstance()->prepare($query_rsQuemClicou);
      $rsQuemClicou->execute();
      $totalRows_rsQuemClicou = $rsQuemClicou->rowCount();
      
      if($totalRows_rsQuemClicou > 0) {
        while($row_rsQuemClicou = $rsQuemClicou->fetch()) { 
          $query_rsTotClicks = "SELECT SUM(n_clicks) as total FROM news_links WHERE url = '".$row_rsQuemClicou['url']."' AND newsletter_id_historico IN $lista_agendamentos";
          $rsTotClicks = DB::getInstance()->prepare($query_rsTotClicks);
          $rsTotClicks->execute();
          $row_rsTotClicks = $rsTotClicks->fetch(PDO::FETCH_ASSOC);
          $totalRows_rsTotClicks = $rsTotClicks->rowCount();

          $perc_n_clicks = 0;
          if($row_rsTotClicks['total'] > 0) {
            $perc_n_clicks = number_format(round(($row_rsTotClicks['total'] / $n_cliques) * 100, 1), 1, ',', '');

            $perc_n_clicks_total += ($row_rsTotClicks['total'] / $n_cliques) * 100;
          }

          $total_n_clicks += $row_rsTotClicks['total'];

          $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('N'.$j, utf8_encode($row_rsQuemClicou['url']))
                      ->setCellValue('O'.$j, utf8_encode($row_rsTotClicks['total']))
                      ->setCellValue('P'.$j, utf8_encode($perc_n_clicks."%"));

          $j++;
        }
      }

      if($perc_n_clicks_total > 0 && $j > 38) {
        // $objPHPExcel->getActiveSheet()
        //             ->setCellValue('O'.$j, '=SUM(O38:O'.($j-1).')');
        $objPHPExcel->getActiveSheet()
                    ->setCellValue('O'.$j, utf8_encode($total_n_clicks));

        $objPHPExcel->getActiveSheet()->getStyle('O'.$j)->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()
                    ->setCellValue('P'.$j, utf8_encode($perc_n_clicks_total."%"));

        $objPHPExcel->getActiveSheet()->getStyle('P'.$j)->getFont()->setBold(true);
      }
    }
  }

  $objPHPExcel->setActiveSheetIndex(0);

  foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  DB::close();

  $callStartTime = microtime(true);
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('report.xlsx');
  $callEndTime = microtime(true);
  $callTime = $callEndTime - $callStartTime;
}

if($_POST['op'] == 'exportarResultadosClientes') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  
  $resultados_txt = 'Por Cliente';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  if($newsletter > 0) {
    if($grupo > 0) {
      $query_rsNews = "SELECT h.id, n.titulo FROM newsletters_historico h, newsletters n WHERE h.newsletter_id = '$newsletter' AND h.newsletter_id = n.id AND h.grupo = '$grupo' AND h.data >= '$datai' AND h.data <= '$dataf'";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
    else {
      $query_rsNews = "SELECT h.id, n.titulo FROM newsletters_historico h, newsletters n WHERE h.newsletter_id = '$newsletter' AND h.newsletter_id = n.id AND h.data >= '$datai' AND h.data <= '$dataf'";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
  }
  else if($tipo > 0 && $grupo > 0) {
    $query_rsNews = "SELECT h.id FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' ORDER BY n.titulo ASC";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();

    $newsletter_txt = "Todas";
  }
  else if($tipo > 0) {
    $query_rsNews = "SELECT h.id FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' ORDER BY n.titulo ASC";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();

    $newsletter_txt = "Todas";
  }
  else if($grupo > 0) {
    $query_rsNews = "SELECT h.id FROM newsletters n, newsletters_historico h WHERE h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' ORDER BY n.titulo ASC";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();

    $newsletter_txt = "Todas";
  }
  else {
    $query_rsNews = "SELECT h.id FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' ORDER BY n.titulo ASC";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();

    $newsletter_txt = "Todas";
  }

  if($totalRows_rsNews > 0) {
    $lista_agendamentos = '(';

    while($row_rsNews = $rsNews->fetch()) {
      $lista_agendamentos .= $row_rsNews['id'].",";

      if($newsletter > 0) {
        $newsletter_txt = $row_rsNews['titulo'];
      }
    }

    $lista_agendamentos = substr($lista_agendamentos, 0, -1);
    $lista_agendamentos .= ')';

    $query_rsQuemClicou = "SELECT l.*, h.grupo, n.tipo, n.titulo, e.nome FROM news_links l, newsletters_historico h, newsletters n, news_emails e WHERE l.newsletter_id_historico = h.id AND h.newsletter_id = n.id AND l.codigo = e.codigo AND l.n_clicks > 0 AND l.newsletter_id_historico IN $lista_agendamentos GROUP BY l.url, l.codigo, l.newsletter_id_historico ORDER BY n.titulo ASC, e.nome ASC, l.url ASC";
    $rsQuemClicou = DB::getInstance()->prepare($query_rsQuemClicou);
    $rsQuemClicou->execute();
    $totalRows_rsQuemClicou = $rsQuemClicou->rowCount();


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
                ->setCellValue('A5', utf8_encode('Resultados'));
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B5', utf8_encode($resultados_txt));

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B6', utf8_encode($tipo_txt));

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B7', utf8_encode($grupo_txt));

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A8', utf8_encode('Newsletter'));
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B8', utf8_encode($newsletter_txt));

    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
    $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A11', utf8_encode('Resultados'));

    $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

    $i=13;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, utf8_encode('Email'))
                ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
                ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
                ->setCellValue('D'.$i, utf8_encode('Cargo'))
                ->setCellValue('E'.$i, utf8_encode('Telefone'))
                ->setCellValue('F'.$i, utf8_encode('Tipo de Cliente'))
                ->setCellValue('G'.$i, utf8_encode('Tipo de Newsletter'))
                ->setCellValue('H'.$i, utf8_encode('Título da página clicada'))
                ->setCellValue('I'.$i, utf8_encode('Data do último clique'))
                ->setCellValue('J'.$i, utf8_encode('Nº de Cliques'));

    $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'J'.$i)->getFont()->setBold(true);

    $i++;
    $total_n_clicks = 0;

    if($totalRows_rsQuemClicou > 0) {
      while($row_rsQuemClicou = $rsQuemClicou->fetch()) {
        $query_rsEmail = "SELECT id, empresa, nome, email, telefone, cargo FROM news_emails WHERE codigo = '".$row_rsQuemClicou['codigo']."'";
        $rsEmail = DB::getInstance()->prepare($query_rsEmail);
        $rsEmail->execute();
        $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

        $query_rsGrupo = "SELECT nome FROM news_grupos WHERE id = '".$row_rsQuemClicou['grupo']."'";
        $rsGrupo = DB::getInstance()->prepare($query_rsGrupo);
        $rsGrupo->execute();
        $row_rsGrupo = $rsGrupo->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsGrupo = $rsGrupo->rowCount();

        if($totalRows_rsGrupo > 0) {
          $grupo = $row_rsGrupo['nome'];
        }
        else {
          $grupo = '';
        }

        $query_rsTipo = "SELECT nome FROM news_tipos_pt WHERE id = '".$row_rsQuemClicou['tipo']."'";
        $rsTipo = DB::getInstance()->prepare($query_rsTipo);
        $rsTipo->execute();
        $row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
        $totalRows_rsTipo = $rsTipo->rowCount();

        if($totalRows_rsTipo > 0) {
          $tipo = $row_rsTipo['nome'];
        }
        else {
          $tipo = '';
        }

        if($row_rsQuemClicou['data_ultimo_click']) {
          $data_ultimo_click = $row_rsQuemClicou['data_ultimo_click'];
        }
        else {
          $data_ultimo_click = '-';
        }

        $total_n_clicks += $row_rsQuemClicou['n_clicks'];

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, utf8_encode($row_rsEmail['email']))
                    ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                    ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                    ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                    ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                    ->setCellValue('F'.$i, utf8_encode($grupo))
                    ->setCellValue('G'.$i, utf8_encode($tipo))
                    ->setCellValue('H'.$i, utf8_encode($row_rsQuemClicou['url']))
                    ->setCellValue('I'.$i, utf8_encode($data_ultimo_click))
                    ->setCellValue('J'.$i, utf8_encode($row_rsQuemClicou['n_clicks']));

        $i++;
      }
    }

    if($i > 14) {
      // $objPHPExcel->getActiveSheet()
      //             ->setCellValue('J'.$i, '=SUM(J14:J'.($i-1).')');
      $objPHPExcel->getActiveSheet()
                  ->setCellValue('J'.$i, utf8_encode($total_n_clicks));

      $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getFont()->setBold(true);
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

if($_POST['op'] == 'exportarDevolvidosGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  if($newsletter > 0) {
    if($grupo > 0) {
      $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf' AND grupo = '$grupo'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
    else {
      $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
  }
  else if($tipo > 0 && $grupo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($tipo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($grupo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else {
    $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE data >= '$datai' AND data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }

  if($totalRows_rsNewsletters > 0) {
    $lista_ids = '';

    while($row_rsNewsletters = $rsNewsletters->fetch()) {
      $lista_ids .= $row_rsNewsletters['id'].",";
    }

    $lista_ids = substr($lista_ids, 0, -1);

    $lista = lista_emails_devolvidos_geral($lista_ids);
  }

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('G'.$i, utf8_encode('Agendamento'))
              ->setCellValue('H'.$i, utf8_encode('Motivo'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'H'.$i)->getFont()->setBold(true);

  $i++;

  if(!$empty) {
    foreach($lista as $key => $value) { 
      $parts = explode(';', $value);

      $query_rsEmail = "SELECT empresa, nome, cargo, telefone FROM news_emails WHERE email = '".$key."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($key))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                  ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                  ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                  ->setCellValue('F'.$i, utf8_encode($parts['1']))
                  ->setCellValue('G'.$i, utf8_encode($parts['2']))
                  ->setCellValue('H'.$i, utf8_encode($parts['0']));

      $i++;
    }
  }

  // Set active sheet index to the first sheet, so Excel opens this as the first sheet
  $objPHPExcel->setActiveSheetIndex(0);

  foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  // Save Excel 2007 file
  $callStartTime = microtime(true);

  DB::close();

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('report.xlsx');
  $callEndTime = microtime(true);
  $callTime = $callEndTime - $callStartTime;
}

if($_POST['op'] == 'exportarDevolvidos') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  $id = $_POST['historico'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  $query_rsNewsHist = "SELECT newsletter_id, data, hora FROM newsletters_historico WHERE id = '$id'";
  $rsNewsHist = DB::getInstance()->prepare($query_rsNewsHist);
  $rsNewsHist->execute();
  $row_rsNewsHist = $rsNewsHist->fetch(PDO::FETCH_ASSOC);

  $newsletter_id = $row_rsNewsHist['newsletter_id'];

  $query_rsNews = "SELECT titulo FROM newsletters WHERE id = '$newsletter_id'";
  $rsNews = DB::getInstance()->prepare($query_rsNews);
  $rsNews->execute();
  $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

  $query_rsListas = "SELECT lista FROM newsletters_historico_listas WHERE newsletter_historico = '$id'";
  $rsListas = DB::getInstance()->prepare($query_rsListas);
  $rsListas->execute();
  $totalRows_rsListas = $rsListas->rowCount();

  $listas_news = '---';
  if($totalRows_rsListas > 0) {
    $listas_news = '';
    
    while($row_rsListas = $rsListas->fetch()) {
      $query_rsLista = "SELECT nome FROM news_listas WHERE id = '".$row_rsListas['lista']."'";
      $rsLista = DB::getInstance()->prepare($query_rsLista);
      $rsLista->execute();
      $totalRows_rsLista = $rsLista->rowCount();
      $row_rsLista = $rsLista->fetch(PDO::FETCH_ASSOC);

      if($totalRows_rsLista > 0)
        $listas_news .= $row_rsLista['nome'].', ';
    }

    $listas_news = substr($listas_news, 0, -2);
  }

  $lista = lista_emails_devolvidos($newsletter_id, $id);

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('G'.$i, utf8_encode('Agendamento'))
              ->setCellValue('H'.$i, utf8_encode('Motivo'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'H'.$i)->getFont()->setBold(true);

  $i++;

  if(!$empty) {
    foreach($lista as $key => $value) { 
      $query_rsEmail = "SELECT empresa, nome, cargo, telefone FROM news_emails WHERE email = '".$key."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($key))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                  ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                  ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                  ->setCellValue('F'.$i, utf8_encode($row_rsNews['titulo']))
                  ->setCellValue('G'.$i, utf8_encode($row_rsNewsHist['data']." ".$row_rsNewsHist['hora']))
                  ->setCellValue('H'.$i, utf8_encode($value));

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

if($_POST['op'] == 'exportarRecebidosGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  if($newsletter > 0) {
    if($grupo > 0) {
      $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf' AND grupo = '$grupo'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
    else {
      $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
  }
  else if($tipo > 0 && $grupo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($tipo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($grupo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else {
    $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE data >= '$datai' AND data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }

  if($totalRows_rsNewsletters > 0) {
    $lista_ids = '';

    while($row_rsNewsletters = $rsNewsletters->fetch()) {
      $lista_ids .= $row_rsNewsletters['id'].",";
    }

    $lista_ids = substr($lista_ids, 0, -1);

    $lista = lista_emails_recebidos_geral($lista_ids);
  }

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('G'.$i, utf8_encode('Agendamento'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'G'.$i)->getFont()->setBold(true);

  $i++;

  if(!$empty) {
    foreach($lista as $key => $value) { 
      $parts = explode(';', $value);

      $query_rsEmail = "SELECT empresa, nome, cargo, telefone FROM news_emails WHERE email = '".$parts['1']."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($parts['1']))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                  ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                  ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                  ->setCellValue('F'.$i, utf8_encode($parts['2']))
                  ->setCellValue('G'.$i, utf8_encode($parts['3']));

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

if($_POST['op'] == 'exportarRecebidos') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  $id = $_POST['historico'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  $query_rsNewsHist = "SELECT newsletter_id, data, hora FROM newsletters_historico WHERE id = '$id'";
  $rsNewsHist = DB::getInstance()->prepare($query_rsNewsHist);
  $rsNewsHist->execute();
  $row_rsNewsHist = $rsNewsHist->fetch(PDO::FETCH_ASSOC);

  $newsletter_id = $row_rsNewsHist['newsletter_id'];

  $query_rsNews = "SELECT titulo FROM newsletters WHERE id = '$newsletter_id'";
  $rsNews = DB::getInstance()->prepare($query_rsNews);
  $rsNews->execute();
  $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

  $query_rsListas = "SELECT lista FROM newsletters_historico_listas WHERE newsletter_historico = '$id'";
  $rsListas = DB::getInstance()->prepare($query_rsListas);
  $rsListas->execute();
  $totalRows_rsListas = $rsListas->rowCount();

  $listas_news = '---';
  if($totalRows_rsListas > 0) {
    $listas_news = '';
    
    while($row_rsListas = $rsListas->fetch()) {
      $query_rsLista = "SELECT nome FROM news_listas WHERE id = '".$row_rsListas['lista']."'";
      $rsLista = DB::getInstance()->prepare($query_rsLista);
      $rsLista->execute();
      $totalRows_rsLista = $rsLista->rowCount();
      $row_rsLista = $rsLista->fetch(PDO::FETCH_ASSOC);

      if($totalRows_rsLista > 0)
        $listas_news .= $row_rsLista['nome'].', ';
    }

    $listas_news = substr($listas_news, 0, -2);
  }

  $lista = lista_emails_recebidos($newsletter_id, $id);

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('G'.$i, utf8_encode('Agendamento'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'G'.$i)->getFont()->setBold(true);

  $i++;

  if(!$empty) {
    foreach($lista as $key => $value) { 
      $query_rsEmail = "SELECT empresa, nome, cargo, telefone FROM news_emails WHERE email = '".$key."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($key))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                  ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                  ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                  ->setCellValue('F'.$i, utf8_encode($row_rsNews['titulo']))
                  ->setCellValue('G'.$i, utf8_encode($row_rsNewsHist['data']." ".$row_rsNewsHist['hora']));

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

if($_POST['op'] == 'exportarVisualizadosGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  if($newsletter > 0) {
    if($grupo > 0) {
      $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf' AND grupo = '$grupo'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
    else {
      $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
  }
  else if($tipo > 0 && $grupo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($tipo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($grupo > 0) {
    $query_rsNewsletters = "SELECT hist.id FROM newsletters_historico hist, newsletters n WHERE hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else {
    $query_rsNewsletters = "SELECT id FROM newsletters_historico WHERE data >= '$datai' AND data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }

  if($totalRows_rsNewsletters > 0) {
    $lista_ids = '';

    while($row_rsNewsletters = $rsNewsletters->fetch()) {
      $lista_ids .= $row_rsNewsletters['id'].",";
    }

    $lista_ids = substr($lista_ids, 0, -1);

    $lista = lista_emails_vistos_geral($lista_ids);
  }

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('G'.$i, utf8_encode('Agendamento'))
              ->setCellValue('H'.$i, utf8_encode('Total de Visualizações'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'H'.$i)->getFont()->setBold(true);

  $i++;

  if(!$empty) {
    foreach($lista as $key => $value) { 
      $parts = explode(';', $value);

      $query_rsEmail = "SELECT empresa, nome, cargo, telefone FROM news_emails WHERE email = '".$parts['1']."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($parts['1']))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                  ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                  ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                  ->setCellValue('F'.$i, utf8_encode($parts['3']))
                  ->setCellValue('G'.$i, utf8_encode($parts['4']))
                  ->setCellValue('H'.$i, utf8_encode($parts['2']));

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

if($_POST['op'] == 'exportarVisualizados') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  $id = $_POST['historico'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  $query_rsNewsHist = "SELECT newsletter_id, data, hora FROM newsletters_historico WHERE id = '$id'";
  $rsNewsHist = DB::getInstance()->prepare($query_rsNewsHist);
  $rsNewsHist->execute();
  $row_rsNewsHist = $rsNewsHist->fetch(PDO::FETCH_ASSOC);

  $newsletter_id = $row_rsNewsHist['newsletter_id'];

  $query_rsNews = "SELECT titulo FROM newsletters WHERE id = '$newsletter_id'";
  $rsNews = DB::getInstance()->prepare($query_rsNews);
  $rsNews->execute();
  $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

  $query_rsListas = "SELECT lista FROM newsletters_historico_listas WHERE newsletter_historico = '$id'";
  $rsListas = DB::getInstance()->prepare($query_rsListas);
  $rsListas->execute();
  $totalRows_rsListas = $rsListas->rowCount();

  $listas_news = '---';
  if($totalRows_rsListas > 0) {
    $listas_news = '';
    
    while($row_rsListas = $rsListas->fetch()) {
      $query_rsLista = "SELECT nome FROM news_listas WHERE id = '".$row_rsListas['lista']."'";
      $rsLista = DB::getInstance()->prepare($query_rsLista);
      $rsLista->execute();
      $totalRows_rsLista = $rsLista->rowCount();
      $row_rsLista = $rsLista->fetch(PDO::FETCH_ASSOC);

      if($totalRows_rsLista > 0)
        $listas_news .= $row_rsLista['nome'].', ';
    }

    $listas_news = substr($listas_news, 0, -2);
  }

  $lista = lista_emails_vistos($newsletter_id, $id);

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('G'.$i, utf8_encode('Agendamento'))
              ->setCellValue('H'.$i, utf8_encode('Total de Visualizações'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'H'.$i)->getFont()->setBold(true);

  $i++;

  if(!$empty) {
    foreach($lista as $key => $value) { 
      $parts = explode(';', $value);

      $query_rsEmail = "SELECT empresa, nome, cargo, telefone FROM news_emails WHERE email = '".$key."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($key))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                  ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                  ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                  ->setCellValue('F'.$i, utf8_encode($row_rsNews['titulo']))
                  ->setCellValue('G'.$i, utf8_encode($row_rsNewsHist['data']." ".$row_rsNewsHist['hora']))
                  ->setCellValue('H'.$i, utf8_encode($parts['1']));

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

if($_POST['op'] == 'exportarCancelamentosGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  if($newsletter > 0) {
    if($grupo > 0) {
      $query_rsNewsletters = "SELECT id, newsletter_id, data, hora FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf' AND grupo = '$grupo'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
    else {
      $query_rsNewsletters = "SELECT id, newsletter_id, data, hora FROM newsletters_historico WHERE newsletter_id='$newsletter' AND data >= '$datai' AND data <= '$dataf'";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
  }
  else if($tipo > 0 && $grupo > 0) {
    $query_rsNewsletters = "SELECT hist.id, hist.newsletter_id, hist.data, hist.hora FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($tipo > 0) {
    $query_rsNewsletters = "SELECT hist.id, hist.newsletter_id, hist.data, hist.hora FROM newsletters_historico hist, newsletters n WHERE n.tipo='$tipo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($grupo > 0) {
    $query_rsNewsletters = "SELECT hist.id, hist.newsletter_id, hist.data, hist.hora FROM newsletters_historico hist, newsletters n WHERE hist.grupo='$grupo' AND n.id = hist.newsletter_id AND hist.data >= '$datai' AND hist.data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else {
    $query_rsNewsletters = "SELECT id, newsletter_id, data, hora FROM newsletters_historico WHERE data >= '$datai' AND data <= '$dataf'";
    $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('G'.$i, utf8_encode('Agendamento'))
              ->setCellValue('H'.$i, utf8_encode('Data de Cancelamento'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'H'.$i)->getFont()->setBold(true);

  $i++;

  if($totalRows_rsNewsletters > 0) {
    while($row_rsNewsletters = $rsNewsletters->fetch()) {
      $query_rsNews = "SELECT titulo FROM newsletters WHERE id='".$row_rsNewsletters['newsletter_id']."'";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
      $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

      $query_rsEmails = "SELECT codigo, data_pedido FROM news_remover WHERE newsletter_id_historico = '".$row_rsNewsletters['id']."' GROUP BY codigo ORDER BY data_pedido DESC";
      $rsEmails = DB::getInstance()->prepare($query_rsEmails);
      $rsEmails->execute();
      $totalRows_rsEmails = $rsEmails->rowCount();

      if($totalRows_rsEmails > 0) {
        while($row_rsEmails = $rsEmails->fetch()) {
          $query_rsEmail = "SELECT nome, email, telefone, cargo, empresa FROM news_emails WHERE codigo = '".$row_rsEmails['codigo']."'";
          $rsEmail = DB::getInstance()->prepare($query_rsEmail);
          $rsEmail->execute();
          $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
          $totalRows_rsEmail = $rsEmail->rowCount();

          $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('A'.$i, utf8_encode($row_rsEmail['email']))
                      ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                      ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                      ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                      ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                      ->setCellValue('F'.$i, utf8_encode($row_rsNews['titulo']))
                      ->setCellValue('G'.$i, utf8_encode($row_rsNewsletters['data']." ".$row_rsNewsletters['hora']))
                      ->setCellValue('H'.$i, utf8_encode($row_rsEmails['data_pedido']));

          $i++;
        }
      }
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

if($_POST['op'] == 'exportarCancelamentos') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  $id = $_POST['historico'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($newsletter > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$newsletter'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  
  $query_rsNewsHist = "SELECT newsletter_id, data, hora FROM newsletters_historico WHERE id = '$id'";
  $rsNewsHist = DB::getInstance()->prepare($query_rsNewsHist);
  $rsNewsHist->execute();
  $row_rsNewsHist = $rsNewsHist->fetch(PDO::FETCH_ASSOC);

  $newsletter_id = $row_rsNewsHist['newsletter_id'];

  $query_rsNews = "SELECT titulo FROM newsletters WHERE id = '$newsletter_id'";
  $rsNews = DB::getInstance()->prepare($query_rsNews);
  $rsNews->execute();
  $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

  $query_rsEmails = "SELECT codigo, data_pedido FROM news_remover WHERE newsletter_id_historico = '$id' GROUP BY codigo ORDER BY data_pedido DESC";
  $rsEmails = DB::getInstance()->prepare($query_rsEmails);
  $rsEmails->execute();
  $totalRows_rsEmails = $rsEmails->rowCount();

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('G'.$i, utf8_encode('Agendamento'))
              ->setCellValue('H'.$i, utf8_encode('Data de Cancelamento'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'H'.$i)->getFont()->setBold(true);

  $i++;

  if($totalRows_rsEmails > 0) {
    while($row_rsEmails = $rsEmails->fetch()) { 
      $parts = explode(';', $value);

      $query_rsEmail = "SELECT empresa, nome, email, cargo, telefone FROM news_emails WHERE codigo = '".$row_rsEmails['codigo']."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsEmail = $rsEmail->rowCount();

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($row_rsEmail['email']))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                  ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                  ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                  ->setCellValue('F'.$i, utf8_encode($row_rsNews['titulo']))
                  ->setCellValue('G'.$i, utf8_encode($row_rsNewsHist['data']." ".$row_rsNewsHist['hora']))
                  ->setCellValue('H'.$i, utf8_encode($row_rsEmails['data_pedido']));

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

if($_POST['op'] == 'exportarQuemClicou') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $id_news = $_POST['newsletter'];
  $id = $_POST['quem_clicou'];
  
  $resultados_txt = 'Geral';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  if($id_news > 0) {
    $query_rsNews = "SELECT titulo FROM newsletters WHERE id='$id_news'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $newsletter_txt = $row_rsNews['titulo']; 
  }
  else {
    $newsletter_txt = 'Todas';
  }

  date_default_timezone_set('Europe/London');
  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  require_once '../Classes/PHPExcel.php';

  $objPHPExcel = new PHPExcel();

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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A8')->getFont()->setBold(true);
  

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A11', utf8_encode('Quem Clicou'));

  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A11')->getFont()->getColor()->setRGB('80898E');

  $i=13;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Tipo de Cliente'))
              ->setCellValue('G'.$i, utf8_encode('Tipo de Newsletter'))
              ->setCellValue('H'.$i, utf8_encode('Nome Newsletter'))
              ->setCellValue('I'.$i, utf8_encode('Título da página clicada'))
              ->setCellValue('J'.$i, utf8_encode('Data do último clique'))
              ->setCellValue('K'.$i, utf8_encode('Nº de Cliques'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'K'.$i)->getFont()->setBold(true);

  $i++;

  $query_rsLinks = "SELECT codigo, newsletter_id_historico FROM news_links WHERE id = '$id'";
  $rsLinks = DB::getInstance()->prepare($query_rsLinks);
  $rsLinks->execute();
  $row_rsLinks = $rsLinks->fetch(PDO::FETCH_ASSOC);

  $codigo = $row_rsLinks['codigo'];
  $newsletter_id_historico = $row_rsLinks['newsletter_id_historico'];

  $query_rsCliente = "SELECT empresa, nome, cargo, email, telefone FROM news_emails WHERE codigo = '$codigo'";
  $rsCliente = DB::getInstance()->prepare($query_rsCliente);
  $rsCliente->execute();
  $totalRows_rsCliente = $rsCliente->rowCount();
  $row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

  $query_rsClicks = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico = '$newsletter_id_historico' AND codigo = '$codigo'";
  $rsClicks = DB::getInstance()->prepare($query_rsClicks);
  $rsClicks->execute();
  $row_rsClicks = $rsClicks->fetch(PDO::FETCH_ASSOC);

  $query_rsOndeClicou = "SELECT DISTINCT(url), data_ultimo_click FROM news_links WHERE newsletter_id_historico = '$newsletter_id_historico' AND codigo = '$codigo' ORDER BY url ASC";
  $rsOndeClicou = DB::getInstance()->prepare($query_rsOndeClicou);
  $rsOndeClicou->execute();
  $totalRows_rsOndeClicou = $rsOndeClicou->rowCount();

  $query_rsNewsHist = "SELECT newsletter_id, grupo FROM newsletters_historico WHERE id = '$newsletter_id_historico'";
  $rsNewsHist = DB::getInstance()->prepare($query_rsNewsHist);
  $rsNewsHist->execute();
  $row_rsNewsHist = $rsNewsHist->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsNewsHist = $rsNewsHist->rowCount(); 

  if($row_rsNewsHist['grupo'] > 0) {
    $query_rsGrupo = "SELECT nome FROM news_grupos WHERE id = '".$row_rsNewsHist['grupo']."'";
    $rsGrupo = DB::getInstance()->prepare($query_rsGrupo);
    $rsGrupo->execute();
    $row_rsGrupo = $rsGrupo->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsGrupo = $rsGrupo->rowCount();

    $grupo = $row_rsGrupo['nome'];
  }
  else {
    $grupo = 'Todos';
  }

  $query_rsNews = "SELECT titulo, tipo FROM newsletters WHERE id = '".$row_rsNewsHist['newsletter_id']."'";
  $rsNews = DB::getInstance()->prepare($query_rsNews);
  $rsNews->execute();
  $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);
  $totalRows_rsNews = $rsNews->rowCount();

  if($row_rsNews['tipo'] > 0) {
    $query_rsTipo = "SELECT nome FROM news_tipos_pt WHERE id = '".$row_rsNews['tipo']."'";
    $rsTipo = DB::getInstance()->prepare($query_rsTipo);
    $rsTipo->execute();
    $row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsTipo = $rsTipo->rowCount();

    $tipo = $row_rsTipo['nome'];
  }
  else {
    $tipo = 'Todos';
  }

  if($row_rsClicks['total'] > 0) {
    while($row_rsOndeClicou = $rsOndeClicou->fetch()) {
      $query_rsTotalClicks = "SELECT SUM(n_clicks) as total FROM news_links WHERE url = '".$row_rsOndeClicou['url']."' AND newsletter_id_historico = '$newsletter_id_historico' AND codigo = '$codigo'";
      $rsTotalClicks = DB::getInstance()->prepare($query_rsTotalClicks);
      $rsTotalClicks->execute();
      $row_rsTotalClicks = $rsTotalClicks->fetch(PDO::FETCH_ASSOC);

      if($row_rsTotalClicks['total'] > 0) {
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, utf8_encode($row_rsCliente['email']))
                    ->setCellValue('B'.$i, utf8_encode($row_rsCliente['nome']))
                    ->setCellValue('C'.$i, utf8_encode($row_rsCliente['empresa']))
                    ->setCellValue('D'.$i, utf8_encode($row_rsCliente['cargo']))
                    ->setCellValue('E'.$i, utf8_encode($row_rsCliente['telefone']))
                    ->setCellValue('F'.$i, utf8_encode($grupo))
                    ->setCellValue('G'.$i, utf8_encode($tipo))
                    ->setCellValue('H'.$i, utf8_encode($row_rsNews['titulo']))
                    ->setCellValue('I'.$i, utf8_encode($row_rsOndeClicou['url']))
                    ->setCellValue('J'.$i, utf8_encode($row_rsOndeClicou['data_ultimo_click']))
                    ->setCellValue('K'.$i, utf8_encode($row_rsTotalClicks['total']));

        $i++;
      }
    }
  }

  $objPHPExcel->setActiveSheetIndex(0);

  foreach(range('A','Z') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  DB::close();

  $callStartTime = microtime(true);
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('report.xlsx');
  $callEndTime = microtime(true);
  $callTime = $callEndTime - $callStartTime;
}

if($_POST['op'] == 'exportarQuemClicouAgendamentos') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];
  $id_hist = $_POST['historico'];
  
  $resultados_txt = 'Detalhado';

  if($tipo > 0) {
    $query_rsTipoTXT = "SELECT nome FROM news_tipos_pt WHERE id = '$tipo'";
    $rsTipoTXT = DB::getInstance()->prepare($query_rsTipoTXT);
    $rsTipoTXT->execute();
    $row_rsTipoTXT = $rsTipoTXT->fetch(PDO::FETCH_ASSOC);

    $tipo_txt = $row_rsTipoTXT['nome'];
  }
  else {
    $tipo_txt = 'Todos';
  }

  if($grupo > 0) {
    $query_rsGrupoTXT = "SELECT nome FROM news_grupos WHERE id = '$grupo'";
    $rsGrupoTXT = DB::getInstance()->prepare($query_rsGrupoTXT);
    $rsGrupoTXT->execute();
    $row_rsGrupoTXT = $rsGrupoTXT->fetch(PDO::FETCH_ASSOC);

    $grupo_txt = $row_rsGrupoTXT['nome'];
  }
  else {
    $grupo_txt = 'Todos';
  }

  date_default_timezone_set('Europe/London');

  define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

  /** Include PHPExcel */
  require_once '../Classes/PHPExcel.php';

  // Create new PHPExcel object
  $objPHPExcel = new PHPExcel();


  $query_rsNews = "SELECT titulo FROM newsletters WHERE id = '$newsletter'";
  $rsNews = DB::getInstance()->prepare($query_rsNews);
  $rsNews->execute();
  $totalRows_rsNews = $rsNews->rowCount();
  $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);
   
  $newsletter_txt = $row_rsNews['titulo'];

  $query_rsNewsHist = "SELECT data, hora FROM newsletters_historico WHERE id = '$id_hist'";
  $rsNewsHist = DB::getInstance()->prepare($query_rsNewsHist);
  $rsNewsHist->execute();
  $totalRows_rsNewsHist = $rsNewsHist->rowCount();
  $row_rsNewsHist = $rsNewsHist->fetch(PDO::FETCH_ASSOC);
   
  $agendamento = $row_rsNewsHist['data']." ".$row_rsNewsHist['hora'];

  $query_rsCliques = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico = '$id_hist'";
  $rsCliques = DB::getInstance()->prepare($query_rsCliques);
  $rsCliques->execute();
  $totalRows_rsCliques = $rsCliques->rowCount();
  $row_rsCliques = $rsCliques->fetch(PDO::FETCH_ASSOC);

  $n_cliques = $row_rsCliques['total'];


  $query_rsQuemClicou = "SELECT l.*, h.grupo, n.tipo, n.titulo, e.nome FROM news_links l, newsletters_historico h, newsletters n, news_emails e WHERE l.newsletter_id_historico = h.id AND h.newsletter_id = n.id AND l.codigo = e.codigo AND l.n_clicks > 0 AND l.newsletter_id_historico = '$id_hist' GROUP BY l.url, l.codigo, l.newsletter_id_historico ORDER BY n.titulo ASC, e.nome ASC, l.url ASC";
  $rsQuemClicou = DB::getInstance()->prepare($query_rsQuemClicou);
  $rsQuemClicou->execute();
  $totalRows_rsQuemClicou = $rsQuemClicou->rowCount();


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
              ->setCellValue('A5', utf8_encode('Resultados'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B5', utf8_encode($resultados_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A6', utf8_encode('Tipo de Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B6', utf8_encode($tipo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A7', utf8_encode('Tipo de Cliente'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B7', utf8_encode($grupo_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A8', utf8_encode('Newsletter'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B8', utf8_encode($newsletter_txt));

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A9', utf8_encode('Agendamento'));
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B9', utf8_encode($agendamento));

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setRGB('80898E');
  $objPHPExcel->getActiveSheet()->getStyle('A3:A9')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A12', utf8_encode('Resultados'));

  $objPHPExcel->getActiveSheet()->getStyle('A12')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A12')->getFont()->getColor()->setRGB('80898E');

  $i=14;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$i, utf8_encode('Email'))
              ->setCellValue('B'.$i, utf8_encode('Nome Contacto'))
              ->setCellValue('C'.$i, utf8_encode('Nome Empresa'))
              ->setCellValue('D'.$i, utf8_encode('Cargo'))
              ->setCellValue('E'.$i, utf8_encode('Telefone'))
              ->setCellValue('F'.$i, utf8_encode('Tipo de Cliente'))
              ->setCellValue('G'.$i, utf8_encode('Tipo de Newsletter'))
              ->setCellValue('H'.$i, utf8_encode('Título da página clicada'))
              ->setCellValue('I'.$i, utf8_encode('Data do último clique'))
              ->setCellValue('J'.$i, utf8_encode('Nº de Cliques'))
              ->setCellValue('K'.$i, utf8_encode('% Nº de Cliques'));

  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':'.'K'.$i)->getFont()->setBold(true);

  $i++;
  $total_n_clicks = 0;
  $perc_n_clicks_total = 0;

  if($totalRows_rsQuemClicou > 0) {
    while($row_rsQuemClicou = $rsQuemClicou->fetch()) {
      $query_rsEmail = "SELECT id, empresa, nome, email, cargo, telefone FROM news_emails WHERE codigo = '".$row_rsQuemClicou['codigo']."'";
      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
      $rsEmail->execute();
      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

      $query_rsGrupo = "SELECT nome FROM news_grupos WHERE id = '".$row_rsQuemClicou['grupo']."'";
      $rsGrupo = DB::getInstance()->prepare($query_rsGrupo);
      $rsGrupo->execute();
      $row_rsGrupo = $rsGrupo->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsGrupo = $rsGrupo->rowCount();

      if($totalRows_rsGrupo > 0) {
        $grupo = $row_rsGrupo['nome'];
      }
      else {
        $grupo = '';
      }

      $query_rsTipo = "SELECT nome FROM news_tipos_pt WHERE id = '".$row_rsQuemClicou['tipo']."'";
      $rsTipo = DB::getInstance()->prepare($query_rsTipo);
      $rsTipo->execute();
      $row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsTipo = $rsTipo->rowCount();

      if($totalRows_rsTipo > 0) {
        $tipo = $row_rsTipo['nome'];
      }
      else {
        $tipo = '';
      }

      if($row_rsQuemClicou['data_ultimo_click']) {
        $data_ultimo_click = $row_rsQuemClicou['data_ultimo_click'];
      }
      else {
        $data_ultimo_click = '-';
      }

      $total_n_clicks += $row_rsQuemClicou['n_clicks'];

      $query_rsTotClicks = "SELECT SUM(n_clicks) as total FROM news_links WHERE codigo = '".$row_rsQuemClicou['codigo']."' AND newsletter_id_historico = '".$row_rsQuemClicou['newsletter_id_historico']."'";
      $rsTotClicks = DB::getInstance()->prepare($query_rsTotClicks);
      $rsTotClicks->execute();
      $row_rsTotClicks = $rsTotClicks->fetch(PDO::FETCH_ASSOC);
      $totalRows_rsTotClicks = $rsTotClicks->rowCount();

      $perc_n_clicks = 0;
      if($row_rsTotClicks['total'] > 0) {
        $perc_n_clicks = number_format(round(($row_rsQuemClicou['n_clicks'] / $n_cliques) * 100, 1), 1, ',', '');

        $perc_n_clicks_total += ($row_rsQuemClicou['n_clicks'] / $n_cliques) * 100;
      }

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, utf8_encode($row_rsEmail['email']))
                  ->setCellValue('B'.$i, utf8_encode($row_rsEmail['nome']))
                  ->setCellValue('C'.$i, utf8_encode($row_rsEmail['empresa']))
                  ->setCellValue('D'.$i, utf8_encode($row_rsEmail['cargo']))
                  ->setCellValue('E'.$i, utf8_encode($row_rsEmail['telefone']))
                  ->setCellValue('F'.$i, utf8_encode($grupo))
                  ->setCellValue('G'.$i, utf8_encode($tipo))
                  ->setCellValue('H'.$i, utf8_encode($row_rsQuemClicou['url']))
                  ->setCellValue('I'.$i, utf8_encode($data_ultimo_click))
                  ->setCellValue('J'.$i, utf8_encode($row_rsQuemClicou['n_clicks']))
                  ->setCellValue('K'.$i, utf8_encode($perc_n_clicks."%"));

      $i++;
    }
  }

  if($i > 15) {
    // $objPHPExcel->getActiveSheet()
    //             ->setCellValue('J'.$i, '=SUM(J14:J'.($i-1).')');
    $objPHPExcel->getActiveSheet()
                ->setCellValue('J'.$i, utf8_encode($total_n_clicks));

    $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
                ->setCellValue('K'.$i, utf8_encode($perc_n_clicks_total."%"));

    $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getFont()->setBold(true);
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
  $file="report.xlsx";
    
  header("Location: ".$file);
}
 
?>