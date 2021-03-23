<?php header("Content-type: text/html; charset=UTF-8"); ?>
<?php include_once('../inc_pages.php'); ?>
<?php include_once('estatisticas-funcoes.php'); ?>
<?php //error_reporting(E_ALL); ini_set("display_errors", "1");

set_time_limit(0);

if($_POST['op']=="processarEmailsDevolvidos") {
  // $mail_box = '{cpanel.netgocioserver4.com:995/novalidate-cert/pop3/ssl}'; //imap example
  // $mail_user = 'mithilchauhan@gmail.com'; //mail username
  // $mail_pass = 'ruintg#sa'; //mail password

 // $mail_box = '{cpanel.netgocioserver4.com:995/novalidate-cert/pop3/ssl}'; //imap example
  $mail_user = 'webtech.dev@gmail.com'; //mail username
  $mail_pass = 'mit99049'; //mail password

  $conn = imap_open ($mail_box, $mail_user, $mail_pass) or die(imap_last_error());
  $num_msgs = imap_num_msg($conn);
  $total = 0;

  for($n=1; $n<=$num_msgs; $n++) {
    $bounce = imap_fetchheader($conn, $n).imap_body($conn, $n);

    if(strpos($bounce, 'Action: failed') !== false) {
      if(strpos($bounce, '550 No Such User Here') !== false) {
        $erro = 1;

        $news = trim(getBetween('NewsCod:', '#', $bounce));
        $agendamento = trim(getBetween('NewsAgenCod:', '#', $bounce));
        $email = trim(getBetween('NewsEmail:', '#', $bounce));
      }
      else if(strpos($bounce, 'Mailbox is full') !== false) {
        $erro = 2;

        $news = trim(getBetween('NewsCod:', '#', $bounce));
        $agendamento = trim(getBetween('NewsAgenCod:', '#', $bounce));
        $email = trim(getBetween('NewsEmail:', '#', $bounce));
      }
      else {
        $erro = 3;

        $news = trim(getBetween('NewsCod:', '#', $bounce));
        $agendamento = trim(getBetween('NewsAgenCod:', '#', $bounce));
        $email = trim(getBetween('NewsEmail:', '#', $bounce));
      }

      if($news != 0 && $news != '' && $news != NULL && $agendamento != 0 && $agendamento != '' && $agendamento != NULL && $email != '' && $email != NULL) {
        $data = date('Y-m-d H:i:s');

        $query_rsExiste = "SELECT id FROM news_emails_devolvidos WHERE email=:email AND newsletter_id=:newsletter_id AND newsletter_id_historico=:newsletter_id_historico";
        $rsExiste = DB::getInstance()->prepare($query_rsExiste);
        $rsExiste->bindParam(':email', $email, PDO::PARAM_STR, 5);
        $rsExiste->bindParam(':newsletter_id', $news, PDO::PARAM_INT);
        $rsExiste->bindParam(':newsletter_id_historico', $agendamento, PDO::PARAM_INT);
        $rsExiste->execute();
        $totalRows_rsExiste = $rsExiste->rowCount();

        if($totalRows_rsExiste == 0) {
          $total++;

          $query_rsInsert = "INSERT INTO news_emails_devolvidos (id, email, newsletter_id, newsletter_id_historico, data_processamento, erro) VALUES ('', :email, :newsletter_id, :newsletter_id_historico, :data_processamento, :erro)";
          $rsInsert = DB::getInstance()->prepare($query_rsInsert);
          $rsInsert->bindParam(':email', $email, PDO::PARAM_STR, 5);
          $rsInsert->bindParam(':newsletter_id', $news, PDO::PARAM_INT);
          $rsInsert->bindParam(':newsletter_id_historico', $agendamento, PDO::PARAM_INT);
          $rsInsert->bindParam(':data_processamento', $data, PDO::PARAM_STR, 5);
          $rsInsert->bindParam(':erro', $erro, PDO::PARAM_INT);
          $rsInsert->execute();
        }

        //Marcar email para ser apagado
        imap_delete($conn, $n);
      }
    }
  }

  //Apagar todos os emails marcados
  imap_expunge($conn);

  imap_close($conn);

  echo $total;
}

if($_POST['op']=="carregaNewsletters") {
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];

  if(!$datai)
    $datai = date('Y-m-d', strtotime("-15 days"));

  if(!$dataf)
    $dataf = date('Y-m-d');

  if($tipo > 0 && $grupo > 0) { 
    $query_rsNewsletters = "SELECT n.id, n.titulo FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND n.tipo = '$tipo' AND h.grupo = '$grupo' AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
    $rsNewsletters = DB::getInstance()->query($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($tipo > 0) { 
    $query_rsNewsletters = "SELECT n.id, n.titulo FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND n.tipo = '$tipo' AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
    $rsNewsletters = DB::getInstance()->query($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else if($grupo > 0) { 
    $query_rsNewsletters = "SELECT n.id, n.titulo FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.grupo = '$grupo' AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
    $rsNewsletters = DB::getInstance()->query($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }
  else {
    $query_rsNewsletters = "SELECT n.id, n.titulo FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
    $rsNewsletters = DB::getInstance()->query($query_rsNewsletters);
    $rsNewsletters->execute();
    $totalRows_rsNewsletters = $rsNewsletters->rowCount();
  }

  DB::close();
  
  ?>
  <select class="form-control select2me" id="newsletter" name="newsletter">
    <option value="0">Todas</option>
    <?php if($totalRows_rsNewsletters > 0) { ?>
      <?php while($row_rsNewsletters = $rsNewsletters->fetch()) { ?>
        <option value="<?php echo $row_rsNewsletters['id']; ?>"><?php echo $row_rsNewsletters['titulo']; ?></option>
      <?php } ?>
    <?php } ?>
  </select>
  <?php 
}

if($_POST['op'] == 'carregaResultados') {
	$datai = $_POST['datai'];
	$dataf = $_POST['dataf'];
	$resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
	$newsletter = $_POST['newsletter'];

  if($resultados == 1) {
    if($newsletter > 0) {
      if($grupo > 0) {
        $query_rsNews = "SELECT n.titulo FROM newsletters n, newsletters_historico h WHERE n.id='$newsletter' AND n.id = h.newsletter_id AND h.grupo = '$grupo' AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id";
        $rsNews = DB::getInstance()->prepare($query_rsNews);
        $rsNews->execute();
        $totalRows_rsNews = $rsNews->rowCount();
        $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

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
      // $perc7 = round($n_pedidos_rem / $n_cliques, 2) * 100;

      //Calcular a percentagem de pessoas que clicaram em algum link
      $perc8 = round(sizeof($array_pessoas) / $total_vistos_unicos, 2) * 100;

      //Calcular a percentagem cancelamentos de cancelamentos de subscrição de newsletters face às visualizações únicas
      $perc9 = round($n_cancelamentos / $total_vistos_unicos, 4) * 100;

      $newsletter_lista = substr($newsletter_lista, 0, -1);

      $query_rsQuemClicou = "SELECT id, codigo, SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico IN (".$newsletter_lista.") GROUP BY codigo";
      $rsQuemClicou = DB::getInstance()->prepare($query_rsQuemClicou);
      $rsQuemClicou->execute();
      $totalRows_rsQuemClicou = $rsQuemClicou->rowCount();

      DB::close();
      ?>
      <div class="row">
        <div class="col-md-12">
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-bars"></i>Resultados <?php if($newsletter > 0) echo "- <strong>".$row_rsNews['titulo']."</strong>"; ?>
              </div>
              <div class="actions">
                <a href="#exportModal" data-toggle="modal" class="btn btn-default btn-sm" style="font-size: 16px" onClick="$('#export_type').val(1);">
                <i class="fa fa-file-excel-o"></i> Exportar </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php if($totalRows_rsNews > 1) { ?>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de newsletters:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $totalRows_rsNews; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de newsletters com agendamentos no período de tempo escolhido</p>
      </div>
      <?php } ?>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de agendamentos:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $totalRows_rsNewsletters; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de agendamentos para <?php if($totalRows_rsNews > 1) { ?>estas newsletters<?php } else { ?>esta newsletter<?php } ?> no período de tempo escolhido</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de emails:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $total_emails; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails escolhidos para receber <?php if($totalRows_rsNews > 1) { ?>as newsletters<?php } else { ?>a newsletter<?php } ?></p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Enviados:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $total_enviados; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails enviados</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de enviados:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc1."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails enviados</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Recebidos:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $n_recebidos; ?></div>
          <?php if($n_recebidos > 0) { ?>
            <a href="javascript:" id="emails_recebidos_geral" class="btn btn-xs default btn-editable" style="display: inline-block;" onClick="emailsRecebidosGeral();"><i class="fa fa-check"></i> Ver Emails</a>
          <?php } ?>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails que receberam <?php if($totalRows_rsNews > 1) { ?>as newsletters<?php } else { ?>a newsletter<?php } ?></p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de recebidos:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc4."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails recebidos</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Devolvidos:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $n_devolvidos; ?></div>
          <?php if($n_devolvidos > 0) { ?>
            <a href="javascript:" id="emails_devolvidos_geral" class="btn btn-xs default btn-editable" style="display: inline-block;" onClick="emailsDevolvidosGeral();"><i class="fa fa-check"></i> Ver Emails</a>
          <?php } ?>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails que não receberam <?php if($totalRows_rsNews > 1) { ?>as newsletters<?php } else { ?>a newsletter<?php } ?></p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de devolvidos:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc5."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails devolvidos</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Visualizações:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $total_vistos; ?></div>
          <?php if($total_vistos > 0) { ?>
            <a href="javascript:" id="emails_vistos_geral" class="btn btn-xs default btn-editable" style="display: inline-block;" onClick="emailsVistosGeral();"><i class="fa fa-check"></i> Ver Emails</a>
          <?php } ?>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de visualizações</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de visualizações:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc6."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de visualizações</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Visualizações únicas:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $total_vistos_unicos; ?></div>
          <?php if($total_vistos_unicos > 0) { ?>
            <a href="javascript:" id="emails_vistos_unicos_geral" class="btn btn-xs default btn-editable" style="display: inline-block;" onClick="emailsVistosGeral();"><i class="fa fa-check"></i> Ver Emails</a>
          <?php } ?>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de visualizações únicas (uma por cada email)</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de visualizações únicas:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc2."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de visualizações únicas</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Rácio entre visualizações:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc3."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">do nº total de visualizações, a percentagem correspondente às visualizações únicas</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Média de tempo:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $media1." minuto(s)"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">média de tempo entre receber a newsletter e abrir o email</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de links:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $n_links; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de links únicos presentes <?php if($totalRows_rsNews > 1) { ?>nas newsletters<?php } else { ?>na newsletter<?php } ?></p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de cliques:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $n_cliques; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de cliques em todos os links <?php if($totalRows_rsNews > 1) { ?>das newsletters<?php } else { ?>da newsletter<?php } ?></p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de cliques:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc8."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de clientes que clicaram em algum link (em relação aos que visualizaram as newsletters)</p>
      </div>
      <!-- <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de pedidos de remoção:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $n_pedidos_rem; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de cliques no link para anular a subscrição de newsletters</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de pedidos de remoção:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc7."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de cliques no link para anular a subscrição de newsletters em relação ao nº de cliques total</p>
      </div> -->
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de cancelamentos:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $n_cancelamentos; ?></div>
          <?php if($n_cancelamentos > 0) { ?>
            <a href="javascript:" id="pedidos_cancelamento_geral" class="btn btn-xs default btn-editable" style="display: inline-block;" onClick="pedidosCancelamentoGeral();"><i class="fa fa-check"></i> Ver Cancelamentos</a>
          <?php } ?>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de cancelamentos efetivos de subscrição de newsletter</p>
      </div>
      <div class="form-group" style="margin-bottom: 0">
        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de cancelamentos:</strong></label>
        <div class="col-md-3" style="padding-top: 1px;">
          <div class="form-control" style="border:0;"><?php echo $perc9."%"; ?></div>
        </div>
        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de clientes que anularam a subscrição de newsletters em relação ao nº de visualizações únicas</p>
      </div>
      <?php /*if($n_links > 0) { ?>
      <hr>
      <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
          <div class="portlet box grey-steel" style="border: 1px solid #e9edef; border-top: 0">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-bars"></i>Quem Clicou
              </div>
              <div class="tools">
                <a href="javascript:;" class="collapse"></a>
              </div>
            </div>
            <div class="portlet-body">
              <table class="table table-striped table-bordered table-hover sample_2">
                <thead>
                  <tr>
                    <th width="35%">
                       Nome
                    </th>
                    <th width="35%">
                       Email
                    </th>
                    <th width="15%">
                       Total de Cliques
                    </th>
                    <th width="15%">
                       Ação
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row_rsQuemClicou = $rsQuemClicou->fetch()) { 
                    $query_rsEmails = "SELECT * FROM news_emails WHERE codigo = '".$row_rsQuemClicou['codigo']."'";
                    $rsEmails = DB::getInstance()->prepare($query_rsEmails);
                    $rsEmails->execute();
                    $row_rsEmails = $rsEmails->fetch(PDO::FETCH_ASSOC);
                    DB::close();
                    ?>
                    <tr>
                      <td><?php echo $row_rsEmails['empresa']." - ".$row_rsEmails['nome']; ?></td>
                      <td><?php echo $row_rsEmails['email']; ?></td>
                      <td><?php echo $row_rsQuemClicou['total']; ?></td>
                      <td><a href="javascript:" id="detalhes_clicks_<?php echo $row_rsQuemClicou['id']; ?>" class="detalhes_clicks btn btn-xs default btn-editable" onClick="datelhesClicks(<?php echo $row_rsQuemClicou['id']; ?>);"><i class="fa fa-plus"></i> Ver onde clicou</a></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td>&nbsp; </td>
                    <td><strong>Total de Emails:</strong> <?php echo $totalRows_rsQuemClicou; ?></td>
                    <td><?php echo $n_cliques; ?></td>
                    <td>&nbsp;</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <?php } */?>
    <?php }
    else { ?>
      <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-6">
          <strong>Sem resultados disponíveis</strong>
        </div>
      </div>
    <?php }
  }
  else if($resultados == 2) {
    if($newsletter > 0) {
      if($grupo > 0) {
        $query_rsNews = "SELECT n.id, n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.grupo = '$grupo' AND h.data >= '$datai' AND h.data <= '$dataf' AND n.id='$newsletter' GROUP BY n.id";
        $rsNews = DB::getInstance()->prepare($query_rsNews);
        $rsNews->execute();
        $totalRows_rsNews = $rsNews->rowCount();
      }
      else {
        $query_rsNews = "SELECT n.id, n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' AND n.id='$newsletter' GROUP BY n.id";
        $rsNews = DB::getInstance()->prepare($query_rsNews);
        $rsNews->execute();
        $totalRows_rsNews = $rsNews->rowCount();
      }
    }
    else if($tipo > 0 && $grupo > 0) {
      $query_rsNews = "SELECT n.id, n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
    else if($tipo > 0) {
      $query_rsNews = "SELECT n.id, n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
    else if($grupo > 0) {
      $query_rsNews = "SELECT n.id, n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
    else {
      $query_rsNews = "SELECT n.id, n.titulo, n.data_envio, COUNT(h.id) as total FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' GROUP BY n.id ORDER BY n.titulo ASC";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }

    DB::close();

    if($totalRows_rsNews > 0) { ?>
      <div class="row">
        <div class="col-md-12">
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-bars"></i>Resultados
              </div>
              <div class="actions">
                <a href="#exportModal" data-toggle="modal" class="btn btn-default btn-sm" style="font-size: 16px" onClick="$('#export_type').val(2);">
                <i class="fa fa-file-excel-o"></i> Exportar </a>
              </div>
            </div>
            <div class="portlet-body">
              <div id="conteudo_rpc">
                <table class="table table-striped table-bordered table-hover" id="sample_1">
                  <thead>
                    <tr>
                      <th width="50%">
                         Newsletter
                      </th>
                      <th width="20%">
                         Data Envio
                      </th>
                      <th width="18%">
                         Total de Agendamentos
                      </th>
                      <th width="12%">
                         Ação
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while($row_rsNews = $rsNews->fetch()) { 
                      // $query_rsNewsletters = "SELECT COUNT(id) as total FROM newsletters_historico WHERE newsletter_id='".$row_rsNews['id']."' AND data >= '$datai' AND data <= '$dataf'";
                      // $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
                      // $rsNewsletters->execute();
                      // $row_rsNewsletters = $rsNewsletters->fetch(PDO::FETCH_ASSOC);
                      // DB::close();
                      ?>
                      <tr>
                        <td><?php echo $row_rsNews['titulo']; ?></td>
                        <td><?php echo $row_rsNews['data_envio']; ?></td>
                        <td><?php echo $row_rsNews['total']; ?></td>
                        <td><a href="javascript:" id="detalhes_<?php echo $row_rsNews['id']; ?>" class="detalhes_news btn btn-xs default btn-editable" onClick="clicksNews(<?php echo $row_rsNews['id']; ?>);"><i class="fa fa-plus"></i> Ver detalhes</a></td>
                      </tr>
                    <?php } ?> 
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } 
    else { ?>
      <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-6">
          <strong>Sem resultados disponíveis</strong>
        </div>
      </div>
    <?php } ?>
  <?php }
  else if($resultados == 3) {
    if($newsletter > 0) {
      if($grupo > 0) {
        $query_rsNews = "SELECT id FROM newsletters_historico WHERE newsletter_id = '$newsletter' AND grupo = '$grupo' AND data >= '$datai' AND data <= '$dataf'";
        $rsNews = DB::getInstance()->prepare($query_rsNews);
        $rsNews->execute();
        $totalRows_rsNews = $rsNews->rowCount();
      }
      else {
        $query_rsNews = "SELECT id FROM newsletters_historico WHERE newsletter_id = '$newsletter' AND data >= '$datai' AND data <= '$dataf'";
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
    }
    else if($tipo > 0) {
      $query_rsNews = "SELECT h.id FROM newsletters n, newsletters_historico h WHERE n.tipo='$tipo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' ORDER BY n.titulo ASC";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
    else if($grupo > 0) {
      $query_rsNews = "SELECT h.id FROM newsletters n, newsletters_historico h WHERE h.grupo='$grupo' AND n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' ORDER BY n.titulo ASC";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }
    else {
      $query_rsNews = "SELECT h.id FROM newsletters n, newsletters_historico h WHERE n.id = h.newsletter_id AND h.data >= '$datai' AND h.data <= '$dataf' ORDER BY n.titulo ASC";
      $rsNews = DB::getInstance()->prepare($query_rsNews);
      $rsNews->execute();
      $totalRows_rsNews = $rsNews->rowCount();
    }

    if($totalRows_rsNews > 0) {
      $lista_agendamentos = '(';

      while($row_rsNews = $rsNews->fetch()) {
        $lista_agendamentos .= $row_rsNews['id'].",";
      }

      $lista_agendamentos = substr($lista_agendamentos, 0, -1);
      $lista_agendamentos .= ')';

      $query_rsQuemClicou = "SELECT id, codigo, SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico IN $lista_agendamentos GROUP BY codigo HAVING total > 0";
      $rsQuemClicou = DB::getInstance()->prepare($query_rsQuemClicou);
      $rsQuemClicou->execute();
      $totalRows_rsQuemClicou = $rsQuemClicou->rowCount();
      
      ?>
      <input type="hidden" id="lista_agendamentos" value="<?php echo $lista_agendamentos; ?>"/>
      <div class="row">
        <div class="col-md-12">
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-bars"></i>Resultados
              </div>
              <div class="actions">
                <a href="#exportModal" data-toggle="modal" class="btn btn-default btn-sm" style="font-size: 16px" onClick="$('#export_type').val(4);">
                <i class="fa fa-file-excel-o"></i> Exportar </a>
              </div>
            </div>
            <div class="portlet-body">
              <table class="table table-striped table-bordered table-hover" id="sample_7">
                <thead>
                  <tr>
                    <th width="25%">
                       Nome
                    </th>
                    <th width="25%">
                       Email
                    </th>
                    <th width="15%">
                       Total de Cliques
                    </th>
                    <th width="17%">
                       Newsletters Enviadas
                    </th>
                    <th width="17%">
                       Newsletters Recebidas
                    </th>
                    <!-- <th width="15%">
                       Ação
                    </th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php $n_emails = 0; $n_cliques = 0; $news_total_env = 0; $news_total_rec = 0;
                  while($row_rsQuemClicou = $rsQuemClicou->fetch()) { 
                    $n_emails++; 
                    $n_cliques += $row_rsQuemClicou['total']; 

                    $query_rsEmail = "SELECT id, empresa, nome, email FROM news_emails WHERE codigo = '".$row_rsQuemClicou['codigo']."'";
                    $rsEmail = DB::getInstance()->prepare($query_rsEmail);
                    $rsEmail->execute();
                    $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

                    $query_rsTotNewsEnv = "SELECT COUNT(id) as total FROM newsletters_vistos WHERE email_id = '".$row_rsEmail['id']."' AND newsletter_id_historico IN $lista_agendamentos";
                    $rsTotNewsEnv = DB::getInstance()->prepare($query_rsTotNewsEnv);
                    $rsTotNewsEnv->execute();
                    $row_rsTotNewsEnv = $rsTotNewsEnv->fetch(PDO::FETCH_ASSOC);

                    $news_total_env += $row_rsTotNewsEnv['total'];

                    $query_rsTotNewsRec = "SELECT COUNT(v.id) as total FROM newsletters_vistos v WHERE v.email_id = '".$row_rsEmail['id']."' AND v.newsletter_id_historico IN $lista_agendamentos AND '".$row_rsEmail['email']."' NOT IN (SELECT d.email FROM news_emails_devolvidos d WHERE d.newsletter_id = v.newsletter_id AND d.newsletter_id_historico = v.newsletter_id_historico)";
                    $rsTotNewsRec = DB::getInstance()->prepare($query_rsTotNewsRec);
                    $rsTotNewsRec->execute();
                    $row_rsTotNewsRec = $rsTotNewsRec->fetch(PDO::FETCH_ASSOC);

                    $news_total_rec += $row_rsTotNewsRec['total'];
                    ?>
                    <tr>
                      <td><?php echo $row_rsEmail['empresa']." - ".$row_rsEmail['nome']; ?></td>
                      <td><?php echo $row_rsEmail['email']; ?></td>
                      <td><?php echo $row_rsQuemClicou['total']; ?></td>
                      <td><?php echo $row_rsTotNewsEnv['total']; ?></td>
                      <td><?php echo $row_rsTotNewsRec['total']; ?></td>
                      <!-- <td><a href="javascript:" id="detalhes_links_<?php echo $row_rsQuemClicou['id']; ?>" class="detalhes_links btn btn-xs default btn-editable" onClick="detalhesLinks(<?php echo $row_rsQuemClicou['id']; ?>);"><i class="fa fa-plus"></i> Ver onde clicou</a></td> -->
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td><strong style="display: block; text-align: right">Totais:</strong></td>
                    <td><?php echo $n_emails; ?></td>
                    <td><?php echo $n_cliques; ?></td>
                    <td><?php echo $news_total_env; ?></td>
                    <td><?php echo $news_total_rec; ?></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    <?php } 
    else { ?>
      <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-6">
          <strong>Sem resultados disponíveis</strong>
        </div>
      </div>
    <?php }

    DB::close();
  }
}

if($_POST['op'] == 'carregaResNews') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $id_news = $_POST['id'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];

  if($id_news > 0) {
    $query_rsNews = "SELECT tipo, titulo FROM newsletters WHERE id='$id_news'";
    $rsNews = DB::getInstance()->prepare($query_rsNews);
    $rsNews->execute();
    $totalRows_rsNews = $rsNews->rowCount();
    $row_rsNews = $rsNews->fetch(PDO::FETCH_ASSOC);

    $query_rsTipoNews = "SELECT nome FROM news_tipos_pt WHERE id = '".$row_rsNews['tipo']."'";
    $rsTiposNews = DB::getInstance()->prepare($query_rsTipoNews);
    $rsTiposNews->execute();
    $totalRows_rsTipoNews = $rsTiposNews->rowCount();
    $row_rsTipoNews = $rsTiposNews->fetch(PDO::FETCH_ASSOC);

    $tipo_news = '---';
    if($totalRows_rsTipoNews > 0)
      $tipo_news = $row_rsTipoNews['nome'];

    if($grupo > 0) {
      $query_rsNewsletters = "SELECT * FROM newsletters_historico WHERE newsletter_id='$id_news' AND grupo = '$grupo' AND data >= '$datai' AND data <= '$dataf' ORDER BY CONCAT(data, ' ', hora) DESC";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }
    else {
      $query_rsNewsletters = "SELECT * FROM newsletters_historico WHERE newsletter_id='$id_news' AND data >= '$datai' AND data <= '$dataf' ORDER BY CONCAT(data, ' ', hora) DESC";
      $rsNewsletters = DB::getInstance()->prepare($query_rsNewsletters);
      $rsNewsletters->execute();
      $totalRows_rsNewsletters = $rsNewsletters->rowCount();
    }    

    if($totalRows_rsNewsletters > 0) { ?>
      <div class="row">
        <div class="col-md-12">
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-bars"></i>Resultados - <strong><?php echo $row_rsNews['titulo']; ?></strong>
              </div>
              <div class="actions">
                <a href="#exportModal" data-toggle="modal" class="btn btn-default btn-sm" style="font-size: 16px" onClick="$('#export_type').val(3); $('#id_news').val(<?php echo $id_news; ?>);">
                <i class="fa fa-file-excel-o"></i> Exportar </a>
                <a href="javascript:" class="btn btn-default btn-sm return_list" style="font-size: 16px">
                <i class="fa fa-history"></i> Voltar à listagem </a>
              </div>
            </div>
          </div>
          <div class="portlet-body">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
              <?php $n_news = 0; 
              while($row_rsNewsletters = $rsNewsletters->fetch()) { 
                //Emais recebidos/devolvidos
                $n_devolvidos = 0; 
                $n_recebidos = 0;

                $query_rsSelect = "SELECT COUNT(id) as total FROM news_emails_devolvidos WHERE newsletter_id = :newsletter_id AND newsletter_id_historico = :newsletter_id_historico";
                $rsSelect = DB::getInstance()->prepare($query_rsSelect);
                $rsSelect->bindParam(':newsletter_id', $row_rsNewsletters['newsletter_id'], PDO::PARAM_INT);
                $rsSelect->bindParam(':newsletter_id_historico', $row_rsNewsletters['id'], PDO::PARAM_INT);
                $rsSelect->execute();
                $row_rsSelect = $rsSelect->fetch(PDO::FETCH_ASSOC);

                $res2 = $row_rsNewsletters['emails_enviados'] - $row_rsSelect['total'];

                $n_devolvidos += $row_rsSelect['total'];
                $n_recebidos += $res2;


                /********************************************************************/
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
                $n_cancelamentos = 0;
                $perc1 = 0;
                $perc2 = 0;
                $perc3 = 0;
                $media1 = 0;
                $array_pessoas = array();

                $n_news++;

                $query_rsListas = "SELECT lista FROM newsletters_historico_listas WHERE newsletter_historico = '".$row_rsNewsletters['id']."'";
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

                $total_emails += $row_rsNewsletters['emails_total'];
                $total_enviados += $row_rsNewsletters['emails_enviados'];
                $total_vistos += $row_rsNewsletters['emails_vistos'];
                $total_vistos_unicos += $row_rsNewsletters['emails_vistos_unicos'];

                //Estado do Agendamento
                $query_rsEstado = "SELECT nome FROM newsletters_historico_estados WHERE id = '".$row_rsNewsletters['estado']."'";
                $rsEstado = DB::getInstance()->prepare($query_rsEstado);
                $rsEstado->execute();
                $totalRows_rsEstado = $rsEstado->rowCount();
                $row_rsEstado = $rsEstado->fetch(PDO::FETCH_ASSOC);

                $estado = $row_rsEstado['nome'];

                //Calcular total de links na newsletter
                $query_rsLinks = "SELECT COUNT(DISTINCT(url)) as total FROM news_links WHERE newsletter_id_historico='".$row_rsNewsletters['id']."'";
                $rsLinks = DB::getInstance()->prepare($query_rsLinks);
                $rsLinks->execute();
                $totalRows_rsLinks = $rsLinks->rowCount();
                $row_rsLinks = $rsLinks->fetch(PDO::FETCH_ASSOC);

                $n_links += $row_rsLinks['total'];

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
                // $perc7 = round($n_pedidos_rem / $n_cliques, 2) * 100;

                //Calcular a percentagem de pessoas que clicaram em algum link
                $perc8 = round(sizeof($array_pessoas) / $total_vistos_unicos, 2) * 100;

                //Calcular a percentagem cancelamentos de cancelamentos de subscrição de newsletters face às visualizações únicas
                $perc9 = round($n_cancelamentos / $total_vistos_unicos, 4) * 100;

                $query_rsQuemClicou = "SELECT id, codigo, SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico = '".$row_rsNewsletters['id']."' GROUP BY codigo HAVING total > 0";
                $rsQuemClicou = DB::getInstance()->prepare($query_rsQuemClicou);
                $rsQuemClicou->execute();
                $totalRows_rsQuemClicou = $rsQuemClicou->rowCount();
                ?>
                <div class="panel panel-default">
                  <div role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $row_rsNewsletters['id']; ?>" aria-expanded="false" aria-controls="collapse_<?php echo $row_rsNewsletters['id']; ?>" class="panel-heading collapsed" id="heading_<?php echo $row_rsNewsletters['id']; ?>">
                    <h5 class="panel-title" style="font-size: 13px; cursor: pointer">
                      Agendamento - <strong><?php echo $row_rsNewsletters['data']." ".$row_rsNewsletters['hora']; ?></strong> - <?php echo $estado; ?> - <strong>Lista(s):</strong> <?php echo $listas_news; ?> 
                      <i style="float: right" class="indicator <?php if($n_news == 1) echo "fa fa-chevron-up"; else echo "fa fa-chevron-down"; ?>"></i>
                    </h5>
                  </div>
                  <div id="collapse_<?php echo $row_rsNewsletters['id']; ?>" class="panel-collapse collapse <?php if($n_news == 1) echo "in"; ?>" role="tabpanel" aria-labelledby="heading_<?php echo $row_rsNewsletters['id']; ?>">
                    <div class="panel-body">
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Tipo de Newsletter:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $tipo_news; ?></div>
                        </div>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Data de início:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $row_rsNewsletters['data_inicio']." ".$row_rsNewsletters['hora_inicio']."h"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">data de início do envio da newsletter</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Data de fim:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $row_rsNewsletters['data_fim']." ".$row_rsNewsletters['hora_fim']."h"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">data de fim do envio da newsletter</p>
                      </div>
                      <hr>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de emails:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $total_emails; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails escolhidos para receber a newsletter</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Enviados:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $total_enviados; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails que receberam a newsletter</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de enviados:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc1."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails enviados</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Recebidos:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $n_recebidos; ?></div>
                          <?php if($n_recebidos > 0) { ?>
                            <a href="javascript:" id="emails_recebidos_<?php echo $row_rsNewsletters['id']; ?>" class="emails_recebidos btn btn-xs default btn-editable" style="display: inline-block;" onClick="emailsRecebidosNews(<?php echo $row_rsNewsletters['id']; ?>);"><i class="fa fa-plus"></i> Ver Emails</a>
                          <?php } ?>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails que receberam <?php if($totalRows_rsNews > 1) { ?>as newsletters<?php } else { ?>a newsletter<?php } ?></p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de recebidos:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc4."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails recebidos</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Devolvidos:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $n_devolvidos; ?></div>
                          <?php if($n_devolvidos > 0) { ?>
                            <a href="javascript:" id="emails_devolvidos_<?php echo $row_rsNewsletters['id']; ?>" class="emails_devolvidos btn btn-xs default btn-editable" style="display: inline-block;" onClick="emailsDevolvidosNews(<?php echo $row_rsNewsletters['id']; ?>);"><i class="fa fa-plus"></i> Ver Emails</a>
                          <?php } ?>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de emails que não receberam <?php if($totalRows_rsNews > 1) { ?>as newsletters<?php } else { ?>a newsletter<?php } ?></p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de devolvidos:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc5."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de emails devolvidos</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Visualizações:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $total_vistos; ?></div>
                          <?php if($total_vistos > 0) { ?>
                            <a href="javascript:" id="emails_visualizacoes_<?php echo $row_rsNewsletters['id']; ?>" class="emails_visualizacoes btn btn-xs default btn-editable" style="display: inline-block;" onClick="emailsVistosNews(<?php echo $row_rsNewsletters['id']; ?>);"><i class="fa fa-plus"></i> Ver Emails</a>
                          <?php } ?>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de visualizações</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de visualizações:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc6."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de visualizações</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Visualizações únicas:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $total_vistos_unicos; ?></div>
                          <?php if($total_vistos_unicos > 0) { ?>
                            <a href="javascript:" id="emails_visualizacoes_unicas_<?php echo $row_rsNewsletters['id']; ?>" class="emails_visualizacoes btn btn-xs default btn-editable" style="display: inline-block;" onClick="emailsVistosNews(<?php echo $row_rsNewsletters['id']; ?>);"><i class="fa fa-plus"></i> Ver Emails</a>
                          <?php } ?>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de visualizações únicas (uma por cada email)</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de visualizações únicas:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc2."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de visualizações únicas</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Rácio entre visualizações:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc3."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">do nº total de visualizações, a percentagem correspondente às visualizações únicas</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Média de tempo:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $media1." minuto(s)"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">média de tempo entre receber a newsletter e abrir o email</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de links:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $n_links; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de links únicos presentes na newsletter</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de cliques:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $n_cliques; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de cliques em todos os links da newsletter</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de cliques:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc8."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de clientes que clicaram em algum link (em relação aos que visualizaram as newsletters)</p>
                      </div>
                      <!-- <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de pedidos de remoção:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $n_pedidos_rem; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de cliques no link para anular a subscrição de newsletters</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de pedidos de remoção:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc7."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de cliques no link para anular a subscrição de newsletters em relação ao nº de cliques total</p>
                      </div> -->
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Total de cancelamentos:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border: 0; display: inline-block; width: auto;"><?php echo $n_cancelamentos; ?></div>
                          <?php if($n_cancelamentos > 0) { ?>
                            <a href="javascript:" id="pedidos_cancelamento_<?php echo $row_rsNewsletters['id']; ?>" class="pedidos_cancelamento btn btn-xs default btn-editable" style="display: inline-block;" onClick="pedidosCancelamentoNews(<?php echo $row_rsNewsletters['id']; ?>);"><i class="fa fa-plus"></i> Ver Emails</a>
                          <?php } ?>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">nº de cancelamentos efetivos de subscrição de newsletter</p>
                      </div>
                      <div class="form-group" style="margin-bottom: 0">
                        <label class="col-md-3 control-label" style="font-size: 13px"><strong>Percentagem de cancelamentos:</strong></label>
                        <div class="col-md-3" style="padding-top: 1px;">
                          <div class="form-control" style="border:0;"><?php echo $perc9."%"; ?></div>
                        </div>
                        <p class="help-block" style="margin-left: 10px; padding-top: 3px; font-size: 12px">percentagem de clientes que anularam a subscrição de newsletters em relação ao nº de visualizações únicas</p>
                      </div>
                      <?php if($n_links > 0) { ?>
                      <hr>
                      <div class="row" style="margin-top: 20px">
                        <div class="col-md-12">
                          <div class="portlet box grey-steel" style="border: 1px solid #e9edef; border-top: 0">
                            <div class="portlet-title">
                              <div class="caption">
                                <i class="fa fa-bars"></i>Quem Clicou
                              </div>
                              <div class="tools">
                                <a href="javascript:;" class="collapse" style="background-image: url(../../assets/global/img/portlet-collapse-icon.png);"></a>
                              </div>
                              <div class="actions" style="padding-right: 15px">
                                <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(14); $('#id_news').val(<?php echo $row_rsNewsletters['newsletter_id']; ?>); $('#id_hist').val(<?php echo $row_rsNewsletters['id']; ?>); $('#confirm_export').click();">
                                <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
                              </div>
                            </div>
                            <div class="portlet-body">
                              <table class="table table-striped table-bordered table-hover sample_2">
                                <thead>
                                  <tr>
                                    <th width="35%">
                                       Nome
                                    </th>
                                    <th width="35%">
                                       Email
                                    </th>
                                    <th width="15%">
                                       Total de Cliques
                                    </th>
                                    <th width="15%">
                                       Ação
                                    </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php if($totalRows_rsQuemClicou > 0) {
                                    while($row_rsQuemClicou = $rsQuemClicou->fetch()) { 
                                      $query_rsEmails = "SELECT email, empresa, nome FROM news_emails WHERE codigo = '".$row_rsQuemClicou['codigo']."'";
                                      $rsEmails = DB::getInstance()->prepare($query_rsEmails);
                                      $rsEmails->execute();
                                      $row_rsEmails = $rsEmails->fetch(PDO::FETCH_ASSOC);
                                      ?>
                                      <tr>
                                        <td><?php echo $row_rsEmails['empresa']." - ".$row_rsEmails['nome']; ?></td>
                                        <td><?php echo $row_rsEmails['email']; ?></td>
                                        <td><?php echo $row_rsQuemClicou['total']; ?></td>
                                        <td><a href="javascript:" id="detalhes_clicks_<?php echo $row_rsQuemClicou['id']; ?>" class="detalhes_clicks btn btn-xs default btn-editable" onClick="detalhesClicksNews(<?php echo $row_rsQuemClicou['id']; ?>);"><i class="fa fa-plus"></i> Ver onde clicou</a></td>
                                      </tr>
                                    <?php } ?>
                                  <?php } ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td>&nbsp; </td>
                                    <td><strong>Total de Emails:</strong> <?php echo $totalRows_rsQuemClicou; ?></td>
                                    <td><?php echo $n_cliques; ?></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    <?php }

    DB::close();
  }
}

if($_POST['op'] == 'carregaQuemClicou') {
  $id = $_POST['id'];

  $query_rsLinks = "SELECT codigo, newsletter_id_historico FROM news_links WHERE id = '$id'";
  $rsLinks = DB::getInstance()->prepare($query_rsLinks);
  $rsLinks->execute();
  $row_rsLinks = $rsLinks->fetch(PDO::FETCH_ASSOC);

  $codigo = $row_rsLinks['codigo'];
  $newsletter_id_historico = $row_rsLinks['newsletter_id_historico'];

  $query_rsCliente = "SELECT empresa, nome FROM news_emails WHERE codigo = '$codigo'";
  $rsCliente = DB::getInstance()->prepare($query_rsCliente);
  $rsCliente->execute();
  $totalRows_rsCliente = $rsCliente->rowCount();
  $row_rsCliente = $rsCliente->fetch(PDO::FETCH_ASSOC);

  $query_rsClicks = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico = '$newsletter_id_historico' AND codigo = '$codigo'";
  $rsClicks = DB::getInstance()->prepare($query_rsClicks);
  $rsClicks->execute();
  $row_rsClicks = $rsClicks->fetch(PDO::FETCH_ASSOC);

  $query_rsOndeClicou = "SELECT DISTINCT(url) FROM news_links WHERE newsletter_id_historico = '$newsletter_id_historico' AND codigo = '$codigo'";
  $rsOndeClicou = DB::getInstance()->prepare($query_rsOndeClicou);
  $rsOndeClicou->execute();
  $totalRows_rsOndeClicou = $rsOndeClicou->rowCount();
  ?>

  <div class="row">
    <div class="form-group">
      <label class="col-md-1 control-label"><strong>Cliente:</strong> </label>
      <div class="col-md-11" style="padding-top: 2px">
        <?php echo $row_rsCliente['empresa']." - ".$row_rsCliente['nome']; ?>
      </div>
    </div>
  </div>
  <?php if($row_rsClicks['total'] > 0) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(13); $('#quem_clicou').val(<?php echo $id; ?>);$('#quem_clicou').val(<?php echo $id; ?>); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_3">
              <thead>
                <tr>
                  <th width="80%">
                     Link
                  </th>
                  <th width="20%">
                     Total de Cliques
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php while($row_rsOndeClicou = $rsOndeClicou->fetch()) {
                  $query_rsTotalClicks = "SELECT SUM(n_clicks) as total FROM news_links WHERE url = '".$row_rsOndeClicou['url']."' AND newsletter_id_historico = '$newsletter_id_historico' AND codigo = '$codigo'";
                  $rsTotalClicks = DB::getInstance()->prepare($query_rsTotalClicks);
                  $rsTotalClicks->execute();
                  $row_rsTotalClicks = $rsTotalClicks->fetch(PDO::FETCH_ASSOC);

                  if($row_rsTotalClicks['total'] > 0) { ?>
                    <tr>
                      <td width="80%">
                        <a href="<?php echo $row_rsOndeClicou['url']; ?>" target="_blank"><?php echo $row_rsOndeClicou['url']; ?></a>
                      </td>
                      <td width="20%">
                        <?php echo $row_rsTotalClicks['total']; ?>
                      </td>
                    </tr>
                  <?php } 
                } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <hr>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}

if($_POST['op'] == 'carregaQuemClicouURL') {
  $id = $_POST['id'];
  $lista_agendamentos = $_POST['lista_agendamentos'];

  $query_rsLinks = "SELECT codigo FROM news_links WHERE id = '$id'";
  $rsLinks = DB::getInstance()->prepare($query_rsLinks);
  $rsLinks->execute();
  $row_rsLinks = $rsLinks->fetch(PDO::FETCH_ASSOC);

  $codigo = $row_rsLinks['codigo'];

  $query_rsEmail = "SELECT empresa, nome FROM news_emails WHERE codigo = '$codigo'";
  $rsEmail = DB::getInstance()->prepare($query_rsEmail);
  $rsEmail->execute();
  $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);

  $query_rsClicks = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico IN $lista_agendamentos AND codigo = '$codigo'";
  $rsClicks = DB::getInstance()->prepare($query_rsClicks);
  $rsClicks->execute();
  $row_rsClicks = $rsClicks->fetch(PDO::FETCH_ASSOC);

  $query_rsQuemClicou = "SELECT DISTINCT(url) FROM news_links WHERE newsletter_id_historico IN $lista_agendamentos AND codigo = '$codigo'";
  $rsQuemClicou = DB::getInstance()->prepare($query_rsQuemClicou);
  $rsQuemClicou->execute();
  $totalRows_rsQuemClicou = $rsQuemClicou->rowCount();
  ?>

  <div class="row">
    <div class="form-group">
      <label class="col-md-1 control-label"><strong>Cliente:</strong> </label>
      <div class="col-md-11" style="padding-top: 2px">
        <?php echo $row_rsEmail['empresa']." - ".$row_rsEmail['nome']; ?>
      </div>
    </div>
  </div>
  <?php if($row_rsClicks['total'] > 0) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_3">
              <thead>
                <tr>
                  <th width="80%">
                     Link
                  </th>
                  <th width="20%">
                     Total de Cliques
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php while($row_rsQuemClicou = $rsQuemClicou->fetch()) {
                  $query_rsTotalClicks = "SELECT SUM(n_clicks) as total FROM news_links WHERE newsletter_id_historico IN $lista_agendamentos AND url = '".$row_rsQuemClicou['url']."' AND codigo = '$codigo'";
                  $rsTotalClicks = DB::getInstance()->prepare($query_rsTotalClicks);
                  $rsTotalClicks->execute();
                  $row_rsTotalClicks = $rsTotalClicks->fetch(PDO::FETCH_ASSOC);

                  if($row_rsTotalClicks['total'] > 0) { ?>
                    <tr>
                      <td width="80%">
                        <a href="<?php echo $row_rsQuemClicou['url']; ?>" target="_blank"><?php echo $row_rsQuemClicou['url']; ?></a>
                      </td>
                      <td width="20%">
                        <?php echo $row_rsTotalClicks['total']; ?>
                      </td>
                    </tr>
                  <?php } 
                } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <hr>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}


/* EMAILS DEVOLVIDOS (GERAL e DETALHADO) */

if($_POST['op'] == 'carregaEmailsDevolvidosGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];

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

  if(!empty($lista)) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(5); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_6">
              <thead>
                <tr>
                  <th width="25%">
                     Email
                  </th>
                  <th width="25%">
                     Motivo de Devolução
                  </th>
                  <th width="25%">
                     Newsletter
                  </th>
                  <th width="25%">
                     Agendamento
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $key => $value) { 
                  $parts = explode(';', $value); ?>
                  <tr>
                    <td width="25%">
                      <?php echo $key; ?>
                    </td>
                    <td width="25%">
                      <?php echo $parts['0']; ?>
                    </td>
                    <td width="25%">
                      <?php echo $parts['1']; ?>
                    </td>
                    <td width="25%">
                      <?php echo $parts['2']; ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}

if($_POST['op'] == 'carregaEmailsDevolvidos') {
  $id = $_POST['id'];

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
  ?>

  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Newsletter:</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $row_rsNews['titulo']; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Agendamento:</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $row_rsNewsHist['data']." ".$row_rsNewsHist['hora']; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Lista(s):</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $listas_news; ?>
      </div>
    </div>
  </div>
  <?php if(!empty($lista)) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(6); $('#id_news').val(<?php echo $newsletter_id; ?>); $('#id_hist').val(<?php echo $id; ?>); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_5">
              <thead>
                <tr>
                  <th width="50%">
                     Email
                  </th>
                  <th width="50%">
                     Motivo de Devolução
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $key => $value) { ?>
                  <tr>
                    <td width="50%">
                      <?php echo $key; ?>
                    </td>
                    <td width="50%">
                      <?php echo $value; ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <hr>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}


/* EMAILS RECEBIDOS (GERAL e DETALHADO) */

if($_POST['op'] == 'carregaEmailsRecebidosGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];

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

  if(!empty($lista)) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(7); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_6">
              <thead>
                <tr>
                  <th width="25%">
                     Nome
                  </th>
                  <th width="25%">
                     Email
                  </th>
                  <th width="25%">
                     Newsletter
                  </th>
                  <th width="25%">
                     Agendamento
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $key => $value) { 
                  $parts = explode(';', $value); ?>
                  <tr>
                    <td width="25%">
                      <?php echo $parts['0']; ?>
                    </td>
                    <td width="25%">
                      <?php echo $parts['1']; ?>
                    </td>
                    <td width="25%">
                      <?php echo $parts['2']; ?>
                    </td>
                    <td width="25%">
                      <?php echo $parts['3']; ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}

if($_POST['op'] == 'carregaEmailsRecebidos') {
  $id = $_POST['id'];

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
  ?>

  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Newsletter:</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $row_rsNews['titulo']; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Agendamento:</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $row_rsNewsHist['data']." ".$row_rsNewsHist['hora']; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Lista(s):</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $listas_news; ?>
      </div>
    </div>
  </div>
  <?php if(!empty($lista)) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(8); $('#id_news').val(<?php echo $newsletter_id; ?>); $('#id_hist').val(<?php echo $id; ?>); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_5">
              <thead>
                <tr>
                  <th width="50%">
                     Nome
                  </th>
                  <th width="50%">
                     Email
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $key => $value) { ?>
                  <tr>
                    <td width="50%">
                      <?php echo $value; ?>
                    </td>
                    <td width="50%">
                      <?php echo $key; ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <hr>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}


/* VISUALIZAÇÕES (GERAL e DETALHADO) */

if($_POST['op'] == 'carregaEmailsVistosGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];

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

  if(!empty($lista)) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(9); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_7">
              <thead>
                <tr>
                  <th width="20%">
                     Nome
                  </th>
                  <th width="20%">
                     Email
                  </th>
                  <th width="20%">
                     Total de Visualizações
                  </th>
                  <th width="20%">
                     Newsletter
                  </th>
                  <th width="20%">
                     Agendamento
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $key => $value) { 
                  $parts = explode(';', $value); ?>
                  <tr>
                    <td width="20%">
                      <?php echo $parts['0']; ?>
                    </td>
                    <td width="20%">
                      <?php echo $parts['1']; ?>
                    </td>
                    <td width="20%">
                      <?php echo $parts['2']; ?>
                    </td>
                    <td width="20%">
                      <?php echo $parts['3']; ?>
                    </td>
                    <td width="20%">
                      <?php echo $parts['4']; ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}

if($_POST['op'] == 'carregaEmailsVistos') {
  $id = $_POST['id'];

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
  ?>

  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Newsletter:</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $row_rsNews['titulo']; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Agendamento:</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $row_rsNewsHist['data']." ".$row_rsNewsHist['hora']; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Lista(s):</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $listas_news; ?>
      </div>
    </div>
  </div>
  <?php if(!empty($lista)) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(10); $('#id_news').val(<?php echo $newsletter_id; ?>); $('#id_hist').val(<?php echo $id; ?>); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_8">
              <thead>
                <tr>
                  <th width="35%">
                     Nome
                  </th>
                  <th width="35%">
                     Email
                  </th>
                  <th width="20%">
                     Total de Visualizações
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $key => $value) {
                  $parts = explode(';', $value); ?>
                  <tr>
                    <td width="35%">
                      <?php echo $parts['0']; ?>
                    </td>
                    <td width="35%">
                      <?php echo $key; ?>
                    </td>
                    <td width="20%">
                      <?php echo $parts['1']; ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <hr>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}


/* PEDIDOS DE CANCELAMENTO (GERAL e DETALHADO) */

if($_POST['op'] == 'carregaPedidosCancelamentoGeral') {
  $datai = $_POST['datai'];
  $dataf = $_POST['dataf'];
  $resultados = $_POST['resultados'];
  $tipo = $_POST['tipo'];
  $grupo = $_POST['grupo'];
  $newsletter = $_POST['newsletter'];

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

  if($totalRows_rsNewsletters > 0) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(11); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_6">
              <thead>
                <tr>
                  <th width="30%">
                     Nome & Email
                  </th>
                  <th width="30%">
                     Newsletter
                  </th>
                  <th width="20%">
                     Agendamento
                  </th>
                  <th width="20%">
                     Data de Cancelamento
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php while($row_rsNewsletters = $rsNewsletters->fetch()) { 
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
                      $query_rsEmail = "SELECT nome, email FROM news_emails WHERE codigo = '".$row_rsEmails['codigo']."'";
                      $rsEmail = DB::getInstance()->prepare($query_rsEmail);
                      $rsEmail->execute();
                      $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
                      $totalRows_rsEmail = $rsEmail->rowCount();

                      if($totalRows_rsEmail > 0) { ?>
                        <tr>
                          <td width="30%">
                            <?php echo $row_rsEmail['nome']." - ".$row_rsEmail['email']; ?>
                          </td>
                          <td width="30%">
                            <?php echo $row_rsNews['titulo']; ?>
                          </td>
                          <td width="20%">
                            <?php echo $row_rsNewsletters['data']." ".$row_rsNewsletters['hora']; ?>
                          </td>
                          <td width="20%">
                            <?php echo $row_rsEmails['data_pedido']; ?>
                          </td>
                        </tr>
                      <?php }
                    }
                  }
                } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}

if($_POST['op'] == 'carregaPedidosCancelamento') {
  $id = $_POST['id'];

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

  $query_rsEmails = "SELECT codigo, data_pedido FROM news_remover WHERE newsletter_id_historico = '$id' GROUP BY codigo ORDER BY data_pedido DESC";
  $rsEmails = DB::getInstance()->prepare($query_rsEmails);
  $rsEmails->execute();
  $totalRows_rsEmails = $rsEmails->rowCount();
  ?>

  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Newsletter:</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $row_rsNews['titulo']; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Agendamento:</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $row_rsNewsHist['data']." ".$row_rsNewsHist['hora']; ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group">
      <label class="col-md-2 control-label"><strong>Lista(s):</strong> </label>
      <div class="col-md-10" style="padding-top: 2px">
        <?php echo $listas_news; ?>
      </div>
    </div>
  </div>
  <?php if($totalRows_rsEmails > 0) { ?>
    <div class="row" style="margin-top: 20px">
      <div class="col-md-12">
        <div class="portlet box grey-steel" style="border: 1px solid #e9edef;">
          <div class="portlet-title">
            <div class="actions">
              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick="$('#export_type').val(12); $('#id_news').val(<?php echo $newsletter_id; ?>); $('#id_hist').val(<?php echo $id; ?>); $('#confirm_export').click();">
              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>
            </div>
          </div>
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_8">
              <thead>
                <tr>
                  <th width="35%">
                     Nome
                  </th>
                  <th width="35%">
                     Email
                  </th>
                  <th width="20%">
                     Data de Cancelamento
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php 
                while($row_rsEmails = $rsEmails->fetch()) {
                  $query_rsEmail = "SELECT empresa, nome, email FROM news_emails WHERE codigo = '".$row_rsEmails['codigo']."'";
                  $rsEmail = DB::getInstance()->prepare($query_rsEmail);
                  $rsEmail->execute();
                  $row_rsEmail = $rsEmail->fetch(PDO::FETCH_ASSOC);
                  $totalRows_rsEmail = $rsEmail->rowCount();

                  if($totalRows_rsEmail > 0) { ?>
                    <tr>
                      <td width="35%">
                        <?php echo $row_rsEmail['empresa']." - ".$row_rsEmail['nome']; ?>
                      </td>
                      <td width="35%">
                        <?php echo $row_rsEmail['email']; ?>
                      </td>
                      <td width="20%">
                        <?php echo $row_rsEmails['data_pedido']; ?>
                      </td>
                    </tr>
                  <?php }
                } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php }
  else { ?>
    <hr>
    <div class="row" style="margin-top: 20px">
      <div class="form-group">
        <label class="col-md-4"><strong>Sem resultados disponíveis</strong></label>
      </div>
    <div class="row" style="margin-top: 20px">
  <?php }

  DB::close();
}

?>