<?php require_once('Connections/connADMIN.php'); ?>
<?php

$query_rsMeta = "SELECT * FROM metatags".$extensao." WHERE id = '1'";
$rsMeta = DB::getInstance()->prepare($query_rsMeta);
$rsMeta->execute();
$row_rsMeta = $rsMeta->fetch(PDO::FETCH_ASSOC);
$totalRows_rsMeta = $rsMeta->rowCount();

$title = $row_rsMeta["title"];
$description = $row_rsMeta["description"];
$keywords = $row_rsMeta["keywords"];

if($row_rsCliente == 0){
	header("Location: login.php");	
	exit;
}	

$id_cliente = $row_rsCliente['id'];

$query_rsTickets = "SELECT * FROM tickets WHERE id_cliente = '$id_cliente' AND id_pai = '0' GROUP BY id ORDER BY id DESC";
$rsTickets = DB::getInstance()->prepare($query_rsTickets);
$rsTickets->execute();
$row_rsTickets = $rsTickets->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsTickets = $rsTickets->rowCount();

$query_rsTipos = "SELECT * FROM tickets_tipos".$extensao." WHERE visivel = 1 ORDER BY ordem ASC";
$rsTipos = DB::getInstance()->prepare($query_rsTipos);
$rsTipos->execute();
$row_rsTipos = $rsTipos->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsTipos = $rsTipos->rowCount();

$menu_sel = "area_reservada";
$menu_sel_area = "tickets";

DB::close();

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Recursos->Resources["charset"];?>" />
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame - Remove this if you use the .htaccess -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>
<?php if($title){ echo addslashes(htmlspecialchars($title, ENT_COMPAT, 'UTF-8')); }else{ echo $Recursos->Resources["pag_title"];}?>
</title>
<?php if($description){?>
<META NAME="description" CONTENT="<?php echo addslashes(htmlspecialchars($description, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php if($keywords!=""){?>
<META NAME="keywords" CONTENT="<?php echo addslashes(htmlspecialchars($keywords, ENT_COMPAT, 'UTF-8')); ?>" />
<?php }?>
<?php include_once('codigo_antes_head.php'); ?>
</head>
<body>
<!--Preloader-->
<div class="mask">
    <div id="loader">
    </div>
</div>
<!--Preloader-->
<div class="mainDiv">
  <div class="row1">
  	<div class="div_table_cell">
    	<?php include_once('header.php'); ?>
      <nav class="breadcrumbs_cont" aria-label="You are here:" role="navigation">
        <div class="row">
          <div class="column">
          	<ul class="breadcrumbs">
              <li class="disabled"><span><?php echo $Recursos->Resources["bread_tit"]; ?></span></li>
              <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
              <li>
                <span><?php echo $Recursos->Resources["ar_meus_tickets"]; ?></span>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="div_100 area_reservada tickets">
        <div class="row">
          <div class="column small-12 medium-3">
            <?php include_once('area-reservada-menu.php'); ?>               
          </div>
         	
          <div class="column small-12 medium-9">
          	<div class="listagem">
          	<div class="div_100 text-center">
          		<div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources['novo_ticket']; ?></h3></div>
							<div class="elements_animated right text-left"> 
								<form action="" novalidate autocomplete="off" onSubmit="return validaForm('form_tickets')" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>"  method="POST" name="form_tickets" id="form_tickets" enctype="multipart/form-data">
									<?php if($totalRows_rsTipos > 0) { ?>
										<div class="inpt_holder select">
										  <label class="inpt_label" for="<?php echo $form_tickets['tipo']; ?>"><?php echo $Recursos->Resources["tck_tipo"]; ?> *</label><!--
										  --><select class="inpt" name="<?php echo $form_tickets['tipo']; ?>" id="<?php echo $form_tickets['tipo']; ?>" required>
										    <?php foreach($row_rsTipos as $tipo) { ?>
										    	<option value="<?php echo $tipo['id']?>"><?php echo $tipo['nome']?></option>
										    <?php } ?>
										  </select>
										</div>
									<?php } ?>
									<div class="inpt_holder">
										<label class="inpt_label" for="<?php echo $form_tickets['assunto']; ?>"><?php echo $Recursos->Resources["tck_assunto"]; ?> *</label><!--
										--><input type="text" class="inpt" required name="<?php echo $form_tickets['assunto']; ?>" id="<?php echo $form_tickets['assunto']; ?>" />
									</div>
									<div class="inpt_holder textarea">
										<label class="inpt_label" for="<?php echo $form_tickets['mensagem']; ?>"><?php echo $Recursos->Resources["mensagem"]; ?> *</label><!--
										--><textarea class="inpt" required name="<?php echo $form_tickets['mensagem']; ?>" id="<?php echo $form_tickets['mensagem']; ?>"></textarea>
									</div>
	                <div class="inpt_holder simple icon-fileupl">
	                	<input type="file" name="<?php echo $form_tickets['upload']; ?>[]" multiple id="<?php echo $form_tickets['upload']; ?>" class="inputfile" accept="application/pdf,application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, image/*" />
										<label class="inpt_label" for="<?php echo $form_tickets['upload']; ?>"><?php echo $Recursos->Resources["upload"];?></label><!--
										--><input class="inpt text-ellipsis" placeholder="<?php echo $Recursos->Resources["selecione"];?>" />
	                  <svg class="upl" xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>
	                  <svg class="check" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 96 96" enable-background="new 0 0 96 96" xml:space="preserve"><g><path d="M49.844,68.325c-1.416,0-2.748-0.554-3.75-1.557L27.523,48.191c-1.003-1.002-1.555-2.334-1.555-3.75   s0.552-2.749,1.555-3.75c1.001-1.001,2.333-1.552,3.75-1.552s2.75,0.551,3.753,1.553l14.019,14.017L82.14,5.504   c0.989-1.468,2.639-2.345,4.412-2.345c1.054,0,2.075,0.312,2.956,0.902c2.424,1.631,3.07,4.934,1.439,7.361L54.25,65.98   c-0.892,1.316-2.312,2.162-3.895,2.314C50.17,68.315,50.01,68.325,49.844,68.325z"/></g>></svg>
	                </div>

									<button type="submit" class="button invert" style="float: right;"><?php echo $Recursos->Resources["enviar"]; ?></button>

									<input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
									<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
									<input type="hidden" name="MM_insert" value="form_tickets" />
									<input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
						    </form>
							</div>
            </div>
          	<div class="div_100 tickets_list area_margin">
              <div class="div_100 text-center">
              	<div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources['ar_meus_tickets']; ?></h3></div>
	            	<?php if($totalRows_rsTickets > 0) { ?>
		            	<?php foreach($row_rsTickets as $ticket) { 
                    $data_mostra = explode(' ',$ticket['data']);
                    $hora_mostra = substr($data_mostra['1'], 0, -3);
                    $data_mostra = data_hora($data_mostra[0]);

                  	$estado = $Recursos->Resources["aberto"];
										$estado_cor = "#0d6ec8";
										if($ticket['estado'] == 0) {
											$estado = $Recursos->Resources["concluido"];
											$estado_cor = "#0a833a";
										}

										if($ticket['anexos']) {
                    	$anexos = count(explode(',',$ticket['anexos']));
                    }
                    else {
                    	$anexos = 0;
                    }
                    ?>
		            		<div class="div_100 tickets_divs text-left">
											<div class="head">
												<div class="row collapse align-middle">
													<div class="column">
														<h3 class="list_subtit"><?php echo $ticket['remetente']; ?></h3><!-- 
														--><h5 class="list_txt"><?php echo $data_mostra.", ".$hora_mostra; ?></h5>
													</div>
													<div class="column shrink">
														<h3 class="list_subtit" style="color: <?php echo $estado_cor; ?>"><?php echo $estado; ?></h3>&nbsp;&nbsp;&nbsp;
														<h3 class="list_subtit">#<?php echo str_pad($ticket['id'], 3, '0', STR_PAD_LEFT); ?></h3>
													</div>
												</div>
											</div>
											<div class="body">
												<h4 class="list_subtit"><?php echo $ticket['assunto']; ?></h4>
												<div class="list_txt"><p><?php echo $ticket['descricao']; ?></p></div>
											</div>
											<div class="foot">
												<div class="row collapse align-middle">
													<div class="column">
														<span class="list_txt"><?php echo "<strong>".$Recursos->Resources["anexos"].":</strong> ".$anexos; ?></span>
													</div>
													<div class="column shrink">
														<a class="button invert" href="javascript:;" onClick="abreTicket('<?php echo $ticket['id']; ?>')"><?php echo $Recursos->Resources["mais_info"]; ?></a>
													</div>
												</div>
											</div>
		            		</div>
		            	<?php } ?>
	            	<?php } else { ?>
                  <div class="area_reservada_resultados textos" style="text-align: left; padding: 0;"><?php echo $Recursos->Resources["sem_tickets"]; ?></div>
                <?php } ?>
              </div>
            </div>
        	</div>
          </div>
      	</div>
      </div>
      <a href="javascript:;" class="hidden" ntgmodal-open="modalTickets"></a>
			<div id="modalTickets" ntgmodal ntgmodal-size="full">
	    	<div ntgmodal-content>
	        <button class="close-button" ntgmodal-close aria-label="Close reveal" role="button" type="button">
            <span aria-hidden="true">&times;</span>
	        </button>
	        <div class="div_100" ntgmodalBody id="rpc"></div>
	    	</div>
			</div>
    </div>
  </div>
  <?php include_once('footer.php'); ?>
</div>
<script type="text/javascript">
	function abreTicket(id) {
		$.post("area-reservada-tickets-det.php", {id:id, title:'<?php echo $title; ?>'}, function(data) {
			$('#modalTickets #rpc').html(data);		
			$('[ntgmodal-open="modalTickets"]').trigger('click');
			init_inputs();
		});
	}
</script>

<?php if(isset($_GET['inserido']) && $_GET['inserido'] == 1) { ?>
	<script type="text/javascript">		
		$(window).on('load', function(){
			ntg_alert("<?php echo $Recursos->Resources["formulario_tickets_msg"]; ?>");
		});
	</script>
<?php } ?>

<?php if(isset($_GET['alt']) && $_GET['alt'] == 1) { ?>
	<script type="text/javascript">	
		$(window).on('load', function(){
			ntg_alert("<?php echo $Recursos->Resources["resposta_sucesso"]; ?>");
		});	
	</script>
<?php } ?>
<?php include_once('codigo_antes_body.php'); ?>
</body>
</html>