<?php header("Content-type: text/html; charset=UTF-8"); ?>

<?php include_once('../inc_pages.php'); ?>

<?php //error_reporting(E_ALL); ini_set("display_errors", "1");



set_time_limit(0);



if($_POST['op'] == 'carregaClientes') {

  $tipo = $_POST['tipo'];



  $where = '';

  if($tipo > 0) {

    $where = 'WHERE tipo=:tipo';

  }



  $query_rsClientes = "SELECT id, nome, email FROM clientes ".$where." ORDER BY nome ASC";

  $rsClientes = DB::getInstance()->prepare($query_rsClientes);

  if($tipo > 0) {

    $rsClientes->bindParam(':tipo', $tipo, PDO::PARAM_INT);

  }

  $rsClientes->execute();

  $totalRows_rsClientes = $rsClientes->rowCount();

  ?>

  <select class="form-control select2me" name="cliente" id="cliente">

    <option value="">Selecionar...</option>

    <?php while($row_rsClientes = $rsClientes->fetch()) { ?>

      <option value="<?php echo $row_rsClientes['id']; ?>"><?php echo $row_rsClientes['nome']." - ".$row_rsClientes['email']; ?></option>

    <?php } ?>

  </select>

  <?php

}





if($_POST['op'] == 'carregaResultados') {

  $tipo = $_POST['tipo'];

  $cliente = $_POST['cliente'];

  $pagamento = $_POST['pagamento'];

  $cod_prom = $_POST['cod_prom'];

  $estado = $_POST['estado'];

  $datai = $_POST['datai'];

  $dataf = $_POST['dataf'];



  $left_join='';

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



  if($totalRows_rsEncomendas > 0) { ?>

    <div class="row" style="margin-top: 20px">

      <div class="col-md-12">

        <div class="portlet box grey-steel" style="border: 1px solid #e9edef; border-top: 0">

          <div class="portlet-title">

            <div class="caption">

              <i class="fa fa-bars"></i>Resultados

            </div>

            <div class="tools">

              <a href="javascript:;" class="collapse" style="background-image: url(../../assets/global/img/portlet-collapse-icon.png);"></a>

            </div>

            <div class="actions" style="padding-right: 15px">

              <a href="javascript:" class="btn btn-default btn-sm" style="font-size: 16px; color: #80898e; border: 1px solid #80898e" onClick=" $('#confirm_export').click();">

              <i class="fa fa-file-excel-o" style="color: #80898e"></i> Exportar </a>

            </div>

          </div>

          <div class="portlet-body">

            <table class="table table-striped table-bordered table-hover sample_1" id="sample_1">

              <thead>

                <tr>

                  <th width="10%">

                    Número

                  </th>

                  <th width="20%">

                    Data

                  </th>

                  <th width="20%">

                    Nome

                  </th>

                  <th width="15%">

                    Valor (€)

                  </th>

                  <th width="20%">

                    Mét. Pagamento

                  </th>

                  <th width="25%">

                    Estado

                  </th>

                </tr>

              </thead>

              <tbody>

              <?php while($row_rsEncomendas = $rsEncomendas->fetch()) { ?>

                <tr>

                  <td><?php echo $row_rsEncomendas['numero']?></td>

                  <td><?php echo $row_rsEncomendas['data']?></td>

                  <td><?php echo $row_rsEncomendas['nome']?></td>

                  <td><?php echo number_format($row_rsEncomendas['valor_c_iva'], 2, ',', '.')." &pound;"; ?></td>

                  <td>

                    <?php 

                    $query_rsMetPagamento = "SELECT nome_interno FROM met_pagamento_en WHERE id=:id";

                    $rsMetPagamento = DB::getInstance()->prepare($query_rsMetPagamento);

                    $rsMetPagamento->bindParam(':id', $row_rsEncomendas['met_pagamt_id'], PDO::PARAM_INT);

                    $rsMetPagamento->execute();

                    $row_rsMetPagamento = $rsMetPagamento->fetch(PDO::FETCH_ASSOC);

                    $totalRows_rsMetPagamento = $rsMetPagamento->rowCount();



                    echo $row_rsMetPagamento['nome_interno']; 

                    ?>

                  </td>

                  <td>

                    <?php

                    if($row_rsEncomendas['estado'] == 1) {

                      echo "A aguardar pagamento";

                    }

                    else if($row_rsEncomendas['estado'] == 2) {

                      echo "Em processamento";

                    }

                    else if($row_rsEncomendas['estado'] == 3) {

                      echo "Enviada";

                    }

                    else if($row_rsEncomendas['estado'] == 4) {

                      echo "Concluída";

                    }

                    else if($row_rsEncomendas['estado'] == 5) {

                      echo "Anulada";

                    } ?>

                  </td>

                </tr>

              <?php } ?>

              </tbody>

              <tfoot>

                <tr>

                  <td>&nbsp; </td>

                  <td><strong>Total de Encomendas:</strong> <?php echo $totalRows_rsEncomendas; ?></td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

              </tfoot>

            </table>

          </div>

        </div>

      </div>

    </div>

  <?php }

}

?>