<?php require_once('Connections/connADMIN.php'); ?>
<?php

if($row_rsCliente == 0) {
	header("Location: login.php");	
	exit;
}	

$id_cliente = $row_rsCliente['id'];
$id_ticket = $_POST['id'];
$title  = $_POST['title'];

$query_rsPai = "SELECT * FROM tickets WHERE id = '$id_ticket' AND id_cliente='$id_cliente'";
$rsPai = DB::getInstance()->prepare($query_rsPai);
$rsPai->execute();
$row_rsPai = $rsPai->fetch(PDO::FETCH_ASSOC);
$totalRows_rsPai = $rsPai->rowCount();

$query_rsTipo = "SELECT * FROM tickets_tipos".$extensao." WHERE visivel = 1 AND id = '$row_rsPai[tipo]'";
$rsTipo = DB::getInstance()->prepare($query_rsTipo);
$rsTipo->execute();
$row_rsTipo = $rsTipo->fetch(PDO::FETCH_ASSOC);
$totalRows_rsTipo = $rsTipo->rowCount();

$data_mostra = explode(' ',$row_rsPai['data']);
$hora_mostra = substr($data_mostra['1'], 0, -3);
$data_mostra = data_hora($data_mostra[0]);

$estado = $Recursos->Resources["aberto"];
$estado_cor = "#0d6ec8";
if($row_rsPai['estado'] == 0) {
	$estado = $Recursos->Resources["concluido"];
	$estado_cor = "#0a833a";
}

$nome_cliente = $row_rsPai['remetente'];
if(substr_count($row_rsPai['remetente'], ' ') > 2) {
  $parts = explode(' ', $row_rsPai['remetente']);
  $firstname = array_shift($parts);
  $lastname = array_pop($parts);  
  $nome_cliente = $firstname." ".$lastname;
}

$anexos = array();
if($row_rsPai['anexos']) $anexos = explode(',', $row_rsPai['anexos']);

$query_rsTickets = "SELECT * FROM tickets WHERE id_pai = '$id_ticket' GROUP BY id ORDER BY id ASC";
$rsTickets = DB::getInstance()->prepare($query_rsTickets);
$rsTickets->execute();
$row_rsTickets = $rsTickets->fetchAll(PDO::FETCH_ASSOC);
$totalRows_rsTickets = $rsTickets->rowCount();

DB::close();

?>
<div class="div_100 area_reservada tickets">
  <div class="row align-top align-center">
    <div class="column small-12 text-center">
    	<div class="titles"><h3 class="titulos"><?php echo $row_rsPai["assunto"]; ?>: <?php echo $row_rsTipo["nome"]; ?></h3></div>
			<div class="div_100 tickets_divs det text-left">
				<div class="head">
					<div class="row collapse align-middle">
						<div class="column">
							<h3 class="list_subtit" style="color: #FFFFFF"><?php echo $nome_cliente; ?></h3><!-- 
							--><h5 class="list_txt" style="color: #FFFFFF"><?php echo $data_mostra.", ".$hora_mostra; ?></h5>
						</div>
						<div class="column shrink">
							<h3 class="list_subtit" style="color: <?php echo $estado_cor; ?>"><?php echo $estado; ?></h3>&nbsp;&nbsp;&nbsp;
							<h3 class="list_subtit" style="color: #FFFFFF;">#<?php echo str_pad($id_ticket, 3, '0', STR_PAD_LEFT); ?></h3>
						</div>
					</div>
				</div>
				<div class="body full">
					<div class="textos"><p><?php echo nl2br($row_rsPai['descricao']); ?></p></div>
				</div>
				<?php if(!empty($anexos)) { ?>
					<div class="foot">
						<div class="row collapse align-middle">
							<div class="column">
                <div class="ficheiros">
                  <h4 class="list_subtit"><?php echo $Recursos->Resources['tickets_ficheiros']; ?></h4>
                  <div class="row collapse">
                    <?php foreach($anexos as $upload) {
                    	$upload = trim($upload);

                    	if($upload && file_exists(ROOTPATH.'imgs/tickets/'.$upload)) {
                    		$preview = "";
                    		$style = "";

												if(@is_array(getimagesize(ROOTPATH."imgs/tickets/".$upload))) {
												  $style = "background-image:url('".ROOTPATH_HTTP."imgs/tickets/".$upload."')";
												}
												else {
													$preview = file_get_contents(ROOTPATH.'imgs/tickets/file.svg');
												}
		                    ?>
                        <div class="small-6 column divs">
                          <a class="list_subtit" href="<?php echo ROOTPATH_HTTP;?>/imgs/tickets/<?php echo $upload; ?>" target="_blank">
                          	<div class="row collapse align-middle">
															<div class="column shrink">
																<i class="icon has_bg" style="<?php echo $style; ?>"><?php echo $preview; ?></i>
															</div>
															<div class="column">
																<span class="list_txt"><?php echo $upload; ?></span>
															</div>
                          	</div>
                          </a>
                        </div>
                      <?php } 
                   	} ?>
                  </div>
              	</div>
							</div>
						</div>
					</div>
				<?php } ?>
    	</div>
    	<?php if($totalRows_rsTickets > 0) { ?>
	    	<?php foreach($row_rsTickets as $ticket) { 
          $data_mostra = explode(' ',$ticket['data']);
          $hora_mostra = substr($data_mostra['1'], 0, -3);
          $data_mostra = data_hora($data_mostra[0]);

          $estado = $Recursos->Resources["aberto"];
          if($ticket['estado'] == 0) {
          	$estado = $Recursos->Resources["concluido"];
          }

          $nome_cliente = $ticket['remetente'];
          if(substr_count($ticket['remetente'], ' ') > 2) {
            $parts = explode(' ', $ticket['remetente']);
            $firstname = array_shift($parts);
            $lastname = array_pop($parts);  
            $nome_cliente = $firstname." ".$lastname;
          }

          $anexos = array();
					if($ticket['anexos']) { 
						$anexos = explode(',',$ticket['anexos']);
					}
        	?>
        	<div class="div_100 tickets_divs det text-left">
						<div class="head">
							<div class="row collapse align-middle">
								<div class="column">
									<h3 class="list_subtit" style="color: #FFFFFF;"><?php echo $nome_cliente; ?></h3><!-- 
									--><h5 class="list_txt" style="color: #FFFFFF;"><?php echo $data_mostra.", ".$hora_mostra; ?></h5>
								</div>
							</div>
						</div>
						<div class="body full">
							<div class="list_txt"><p><?php echo nl2br($ticket['descricao']); ?></p></div>
						</div>
						<?php if(!empty($anexos)) { ?>
							<div class="foot">
								<div class="row collapse align-middle">
									<div class="column">
                    <div class="ficheiros">
                      <h4 class="list_subtit"><?php echo $Recursos->Resources['tickets_ficheiros']; ?></h4>
                      <div class="row collapse">
	                      <?php foreach($anexos as $key => $upload) {
	                      	$upload = trim($upload);

	                      	if($upload && file_exists(ROOTPATH.'imgs/tickets/'.$upload)) {
	                      		$preview = "";
	                      		$style = "";

														if(@is_array(getimagesize(ROOTPATH."imgs/tickets/".$upload))) {
														  $style = "background-image:url('".ROOTPATH_HTTP."imgs/tickets/".$upload."')";
														}
														else {
															$preview = file_get_contents(ROOTPATH.'imgs/tickets/file.svg');
														}
                            ?>
	                          <div class="small-12 xxsmall-6 column divs">
	                          	<a class="list_subtit" href="<?php echo ROOTPATH_HTTP;?>/imgs/tickets/<?php echo $upload; ?>" target="_blank">
	                            	<div class="row collapse align-middle">
																	<div class="column shrink">
																		<i class="icon has_bg" style="<?php echo $style; ?>"><?php echo $preview; ?></i>
																	</div>
																	<div class="column">
																		<span class="list_txt"><?php echo $upload; ?></span>
																	</div>
                              	</div>
                              </a>
                            </div>
                          <?php } 
                       	} ?>
                    	</div>
                    </div>
									</div>
								</div>
							</div>
						<?php } ?>
        	</div>
      	<?php } ?>
  		<?php } ?>
    </div>
    <div class="column small-6 text-center">
    	<div class="titles area_margin"><h3 class="titulos"><?php echo $Recursos->Resources["nova_resposta"]; ?></h3></div>
			<div class="div_100 text-left"> 
				<form action="" novalidate autocomplete="off" onSubmit="return validaForm('form_tickets2')" data-error="<?php echo $Recursos->Resources["comprar_preencher"]; ?>"  method="POST" name="form_tickets2" id="form_tickets2" enctype="multipart/form-data">
					<div class="inpt_holder textarea">
						<label class="inpt_label" for="<?php echo $form_tickets['mensagem2']; ?>"><?php echo $Recursos->Resources["mensagem"];?> *</label><!--
						--><textarea class="inpt" required name="<?php echo $form_tickets['mensagem2']; ?>" id="<?php echo $form_tickets['mensagem2']; ?>"></textarea>
					</div>

          <div class="inpt_holder simple icon-fileupl">
          	<input type="file" name="<?php echo $form_tickets['upload2']; ?>[]" multiple id="<?php echo $form_tickets['upload2']; ?>" class="inputfile" accept="application/pdf,application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, image/*" />
						<label class="inpt_label" for="<?php echo $form_tickets['upload2']; ?>"><?php echo $Recursos->Resources["upload"];?></label><!--
						--><input class="inpt text-ellipsis" placeholder="<?php echo $Recursos->Resources["selecione"];?>" />
            <svg class="upl" xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>
            <svg class="check" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 96 96" enable-background="new 0 0 96 96" xml:space="preserve"><g><path d="M49.844,68.325c-1.416,0-2.748-0.554-3.75-1.557L27.523,48.191c-1.003-1.002-1.555-2.334-1.555-3.75   s0.552-2.749,1.555-3.75c1.001-1.001,2.333-1.552,3.75-1.552s2.75,0.551,3.753,1.553l14.019,14.017L82.14,5.504   c0.989-1.468,2.639-2.345,4.412-2.345c1.054,0,2.075,0.312,2.956,0.902c2.424,1.631,3.07,4.934,1.439,7.361L54.25,65.98   c-0.892,1.316-2.312,2.162-3.895,2.314C50.17,68.315,50.01,68.325,49.844,68.325z"/></g>></svg>
        	</div>

					<button type="submit" class="button invert" style="float: right;"><?php echo $Recursos->Resources["enviar"]; ?></button>

					<input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
					<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
					<input type="hidden" name="MM_insert" value="form_tickets" />
					<input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
					<input type="hidden" name="id_ticket" id="id_ticket" value="<?php echo $id_ticket; ?>" />
		    </form>
			</div>
    </div>
  </div>
</div>