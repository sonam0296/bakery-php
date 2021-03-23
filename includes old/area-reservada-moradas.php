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

if($row_rsCliente == 0) {
	header("Location: login.php");	
	exit;
}	

$id_cliente = $row_rsCliente['id'];

$form_morada_principal = $csrf->form_names(array('morada', 'localidade', 'codpostal', 'pais'), false);
if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_morada_principal")) {

  if($_POST['form_hidden'] == "") {
    if($csrf->check_valid('post')) { 
      $morada = $_POST[$form_morada_principal['morada']];
      $localidade = $_POST[$form_morada_principal['localidade']];
      $codpostal = $_POST[$form_morada_principal['codpostal']];
      $pais = $_POST[$form_morada_principal['pais']];

      if($morada != "" && $localidade != "" && $codpostal != "" && $pais != "") {	
       
        $insertSQL = "UPDATE clientes SET morada=:morada, cod_postal=:cod_postal, localidade=:localidade, pais=:pais WHERE id='$id_cliente'";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':morada', $morada, PDO::PARAM_STR, 5);	
        $rsInsert->bindParam(':cod_postal', $codpostal, PDO::PARAM_STR, 5);	
        $rsInsert->bindParam(':localidade', $localidade, PDO::PARAM_STR, 5);        
        $rsInsert->bindParam(':pais', $pais, PDO::PARAM_INT);      
        $rsInsert->execute();

        header("Location: area-reservada-moradas.php?alterado=1");
        exit();
      }
    }
  }
}

$form_outras_moradas = $csrf->form_names(array('descricao_nova', 'morada_nova', 'localidade_nova', 'codpostal_nova', 'pais_nova', 'distrito_nova'), false);
if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_outras_moradas")) {

  if($_POST['form_hidden'] == "") {
    if($csrf->check_valid('post')) { 
      $descricao = $_POST[$form_outras_moradas['descricao_nova']];
      $morada = $_POST[$form_outras_moradas['morada_nova']];
      $localidade = $_POST[$form_outras_moradas['localidade_nova']];
      $codpostal = $_POST[$form_outras_moradas['codpostal_nova']];
      $pais = $_POST[$form_outras_moradas['pais_nova']];
      $distrito = $_POST[$form_outras_moradas['distrito_nova']];

      if($descricao != "" && $morada != "" && $localidade != "" && $codpostal != "" && $pais != "" && $distrito != "") {    
        $insertSQL = "SELECT MAX(id) FROM clientes_moradas";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->execute();
        $row_rsInsert = $rsInsert->fetch(PDO::FETCH_ASSOC);
        
        $max_id = $row_rsInsert["MAX(id)"] + 1;

        $insertSQL = "INSERT INTO clientes_moradas (id, id_cliente, nome, morada, localidade, distrito, cod_postal, pais) VALUES (:id, :id_cliente, :nome, :morada, :localidade, :distrito, :cod_postal, :pais)";
        $rsInsert = DB::getInstance()->prepare($insertSQL);
        $rsInsert->bindParam(':id', $max_id, PDO::PARAM_INT);
        $rsInsert->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $rsInsert->bindParam(':nome', $descricao, PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':morada', $morada, PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':cod_postal', $codpostal, PDO::PARAM_STR, 5); 
        $rsInsert->bindParam(':localidade', $localidade, PDO::PARAM_STR, 5);
        $rsInsert->bindParam(':distrito', $distrito, PDO::PARAM_STR, 5);         
        $rsInsert->bindParam(':pais', $pais, PDO::PARAM_INT);      
        $rsInsert->execute();

        header("Location: area-reservada-moradas.php?inserido=1");
        exit();
      }
    }
  }
}

$query_rsOutrasMoradas = "SELECT * FROM clientes_moradas WHERE id_cliente = '$id_cliente'";
$rsOutrasMoradas = DB::getInstance()->prepare($query_rsOutrasMoradas);
$rsOutrasMoradas->execute();
$totalRows_rsOutrasMoradas = $rsOutrasMoradas->rowCount();

$menu_sel = "area_reservada";
$menu_sel_area = "moradas";

DB::close();

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Recursos->Resources["charset"];?>" />
  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame - Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>
    <?php if($title) { echo addslashes(htmlspecialchars($title, ENT_COMPAT, 'UTF-8')); } else { echo $Recursos->Resources["pag_title"]; } ?>
  </title>
  <?php if($description) { ?>
    <META NAME="description" CONTENT="<?php echo addslashes(htmlspecialchars($description, ENT_COMPAT, 'UTF-8')); ?>" />
  <?php } ?>
  <?php if($keywords != "") { ?>
    <META NAME="keywords" CONTENT="<?php echo addslashes(htmlspecialchars($keywords, ENT_COMPAT, 'UTF-8')); ?>" />
  <?php } ?>
  <?php include_once('codigo_antes_head.php'); ?>
</head>
<body>
  <!--Preloader-->
<div class="mask">
  <div id="loader"></div>
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
              <li><a href="<?php echo get_meta_link(1); ?>" data-ajaxurl="<?php echo ROOTPATH_HTTP; ?>includes/pages/index.php" data-remote="false"><?php echo $Recursos->Resources["home"]; ?></a></li>
              <li>
                <span><?php echo $Recursos->Resources["minhas_moradas"]; ?></span>
              </li>
            </ul>
           </div>
        </div>
      </nav>
      <div class="div_100 area_reservada">
        <div class="row">
          <div class="column small-12 medium-3">
            <?php include_once('area-reservada-menu.php'); ?>               
          </div>
          <div class="column small-12 medium-9">
            <div  class="listagem">
              <div class="div_100">
                <div class="elements_animated right text-center"> 
                  <form method="POST" name="form_morada_principal" id="form_morada_principal" onSubmit="return validaForm('form_morada_principal')" autocomplete="off" novalidate>
                    <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["ar_morada_principal"]; ?></h3></div>
                    <div class="div_100 text-left">
                      <div class="div_100">
                        <div class="inpt_cells xsmall">    
                          <div class="inpt_holder textarea">
                            <label class="inpt_label" for="<?php echo $form_morada_principal['morada']; ?>"><?php echo $Recursos->Resources["ar_morada"];?> *</label><!--
                            --><textarea class="inpt<?php if($row_rsCliente['morada']) echo " has_text"; ?>" required name="<?php echo $form_morada_principal['morada']; ?>" id="<?php echo $form_morada_principal['morada']; ?>"><?php echo $row_rsCliente['morada'];?></textarea>
                          </div>
                        </div><!--
                        --><div class="inpt_cells xsmall">    
                          <div class="inpt_holder">
                            <label class="inpt_label" for="<?php echo $form_morada_principal['cod_postal']; ?>"><?php echo $Recursos->Resources["ar_cpostal"];?> *</label><!--
                            --><input class="inpt half mask-postal" placeholder="<?php echo $Recursos->Resources["ar_cpostal2"];?>" required type="text" name="<?php echo $form_morada_principal['codpostal']; ?>" id="<?php echo $form_morada_principal['codpostal']; ?>" value="<?php echo $row_rsCliente['cod_postal'];?>"/>
                          </div>
                        </div><!--
                        --><div class="inpt_cells xsmall">    
                          <div class="inpt_holder">
                            <label class="inpt_label" for="<?php echo $form_morada_principal['localidade']; ?>"><?php echo $Recursos->Resources["localidade"];?> *</label><!--
                            --><input class="inpt half" required type="text" name="<?php echo $form_morada_principal['localidade']; ?>" id="<?php echo $form_morada_principal['localidade']; ?>" value="<?php echo $row_rsCliente['localidade'];?>"/>
                          </div>
                        </div><!--
                        --><div class="inpt_cells xsmall">    
                          <?php if(!empty($GLOBALS['divs_paises'])) { ?>
                            <div class="inpt_holder select">
                              <label class="inpt_label" for="<?php echo $form_morada_principal['pais']; ?>"><?php echo $Recursos->Resources["ar_selecione_pais"];?> *</label><!--
                              --><select class="inpt" name="<?php echo $form_morada_principal['pais']; ?>" id="<?php echo $form_morada_principal['pais']; ?>" required>
                                <option value="0"></option>  
                                <?php foreach($GLOBALS['divs_paises'] as $pais) { ?>
                                  <option value="<?php echo $pais['id']?>" <?php if($row_rsCliente["pais"] == $pais['id']) { ?> selected <?php } ?>><?php echo $pais['nome']?></option>
                                <?php } ?>
                              </select>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                      <button role="button" type="submit" class="button invert"><?php echo $Recursos->Resources["comprar_guardar"]; ?></button>
                    </div>
                    <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                    <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                    <input type="hidden" name="MM_insert" value="form_morada_principal" />
                    <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                  </form>

                  <form class="area_margin" method="POST" name="form_outras_moradas" id="form_outras_moradas" onSubmit="return validaForm('form_outras_moradas')" autocomplete="off" novalidate>
                    <div class="titles"><h3 class="titulos"><?php echo $Recursos->Resources["ar_outras_moradas"]; ?></h3></div>
                    <div class="div_100 text-left">
                      <div class="div_100">
                        <div class="inpt_cells xsmall">    
                          <div class="inpt_holder">
                            <label class="inpt_label" for="<?php echo $form_outras_moradas['descricao']; ?>"><?php echo $Recursos->Resources["ar_descricao"]; ?> *</label><!--
                            --><input class="inpt" required type="text" name="<?php echo $form_outras_moradas['descricao_nova']; ?>" id="<?php echo $form_outras_moradas['descricao_nova']; ?>"/>
                          </div>
                        </div><!--
                        --><div class="inpt_cells xsmall">    
                          <div class="inpt_holder textarea">
                            <label class="inpt_label" for="<?php echo $form_outras_moradas['morada']; ?>"><?php echo $Recursos->Resources["ar_morada"]; ?> *</label><!--
                            --><textarea class="inpt<?php if($row_rsCliente['morada']) echo " has_text"; ?>" required name="<?php echo $form_outras_moradas['morada_nova']; ?>" id="<?php echo $form_outras_moradas['morada_nova']; ?>"></textarea>
                          </div>
                        </div><!--
                        --><div class="inpt_cells xsmall">    
                          <div class="inpt_holder">
                            <label class="inpt_label" for="<?php echo $form_outras_moradas['cod_postal']; ?>"><?php echo $Recursos->Resources["ar_cpostal"]; ?> *</label><!--
                            --><input class="inpt half mask-postal" placeholder="<?php echo $Recursos->Resources["ar_cpostal2"];?>" required type="text" name="<?php echo $form_outras_moradas['codpostal_nova']; ?>" id="<?php echo $form_outras_moradas['codpostal_nova']; ?>"/>
                          </div>
                        </div><!--
                        --><div class="inpt_cells xsmall">    
                          <div class="inpt_holder">
                            <label class="inpt_label" for="<?php echo $form_outras_moradas['localidade']; ?>"><?php echo $Recursos->Resources["localidade"]; ?> *</label><!--
                            --><input class="inpt half" required type="text" name="<?php echo $form_outras_moradas['localidade_nova']; ?>" id="<?php echo $form_outras_moradas['localidade_nova']; ?>" />
                          </div>
                        </div><!--
                        --><div class="inpt_cells xsmall">    
                          <div class="inpt_holder textarea">
                            <label class="inpt_label" for="<?php echo $form_outras_moradas['distrito']; ?>"><?php echo $Recursos->Resources["distrito"]; ?> *</label><!--
                            --><input class="inpt half" required type="text" name="<?php echo $form_outras_moradas['distrito_nova']; ?>" id="<?php echo $form_outras_moradas['distrito_nova']; ?>" />
                          </div>
                        </div><!--
                        --><div class="inpt_cells xsmall">    
                          <?php if(!empty($GLOBALS['divs_paises'])) { ?>
                            <div class="inpt_holder select">
                              <label class="inpt_label" for="<?php echo $form_outras_moradas['pais']; ?>"><?php echo $Recursos->Resources["ar_selecione_pais"]; ?> *</label><!--
                              --><select class="inpt" name="<?php echo $form_outras_moradas['pais_nova']; ?>" id="<?php echo $form_outras_moradas['pais_nova']; ?>" required>
                                <option value="0"></option>  
                                <?php foreach($GLOBALS['divs_paises'] as $pais) { ?>
                                  <option value="<?php echo $pais['id']?>"><?php echo $pais['nome']?></option>
                                <?php } ?>
                              </select>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                      <button role="button" type="submit" class="button invert inserir"><?php echo $Recursos->Resources["ar_inserir"]; ?></button>
                      <button type="button" class="button invert guardar" onClick="guardaMorada();"><?php echo $Recursos->Resources["comprar_guardar"]; ?></button> 
                      <button type="button" class="button invert cancel" onClick="limpaForm();"><?php echo $Recursos->Resources["cancelar"]; ?></button>                
                    </div>
                    <input type="hidden" name="editar_morada" id="editar_morada" value="0" />
                    <input type="hidden" name="titulo_pag" id="titulo_pag" value="<?php echo $title; ?>" />
                    <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
                    <input type="hidden" name="MM_insert" value="form_outras_moradas" />
                    <input type="text" name="form_hidden" id="form_hidden" class="inpt hidden" value="" />
                  </form>    

                  <?php if($totalRows_rsOutrasMoradas > 0) { ?>
                    <div class="custom_table text-left padded area_margin">
                      <div class="thead">
                        <div class="table-tr">
                          <div class="table-td text-center" style="width: 20%;"><?php echo $Recursos->Resources["ar_descricao"]; ?> <i></i></div>
                          <div class="table-td text-center"><?php echo $Recursos->Resources["ar_morada"]; ?> <i></i></div>
                          <div class="table-td text-center" style="width: 20%;">&nbsp;</div>
                        </div>
                      </div>
                      <div class="tbody list">
                        <?php while($row_rsOutrasMoradas = $rsOutrasMoradas->fetch()) { 
                          $query_rsOutroPais = "SELECT nome FROM paises WHERE id = '".$row_rsOutrasMoradas['pais']."'";
                          $rsOutroPais = DB::getInstance()->prepare($query_rsOutroPais);
                          $rsOutroPais->execute();
                          $row_rsOutroPais = $rsOutroPais->fetch(PDO::FETCH_ASSOC);
                          DB::close();
                          ?><!-- 
                          --><div class="table-tr list_div">
                            <div class="table-td text-center" data-tit="<?php echo $Recursos->Resources["ar_descricao"]; ?>:" style="width: 20%;"><?php echo $row_rsOutrasMoradas['nome']; ?></div>
                            <div class="table-td text-center" data-tit="<?php echo $Recursos->Resources["ar_morada"]; ?>:">
                              <?php echo $row_rsOutrasMoradas['morada']."<br>".$row_rsOutrasMoradas['cod_postal']." ".$row_rsOutrasMoradas['localidade']."<br>".$row_rsOutrasMoradas['distrito'].", ".$row_rsOutroPais['nome']; ?>
                            </div>
                            <div class="table-td text-center" data-tit="<?php echo $Recursos->Resources["accoes"]; ?>:" style="width: 20%;">
                              <a href="javascript:" onClick="editaMorada('<?php echo $row_rsOutrasMoradas["id"]; ?>');" class="editar_morada"><?php echo $Recursos->Resources['ar_editar']; ?></a><br>
                              <a href="javascript:" onClick="removeMorada('<?php echo $row_rsOutrasMoradas["id"]; ?>');"><?php echo $Recursos->Resources['ar_remover']; ?></a>
                            </div>
                          </div><!-- 
                        --><?php } ?>
                      </div>
                    </div>
                  <?php } ?>
                </div>    
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('footer.php'); ?>
</div>

<?php include_once('codigo_antes_body.php'); ?>

<script type="text/javascript">
function removeMorada(id) {
  ntg_confirm(
    {
      type: 'info',
      title: "<?php echo $Recursos->Resources["ar_remover_morada_tit"]; ?>",
      html: "<?php echo $Recursos->Resources["ar_remover_morada"]; ?>",
      showCloseButton: true,
      showCancelButton: true,
      cancelButtonText: "<?php echo $Recursos->Resources["cancelar"]; ?>",
      showConfirmButton: true,
      confirmButtonText: "<?php echo $Recursos->Resources["ok"]; ?>",
    },
    function() { 
      $.post(_includes_path+"rpc.php", {op:"remover_morada", id:id}, function(data) {
        document.location = "area-reservada-moradas.php?removido=1";
      });
    },
    function() {
      return false
    },
    "",
    ""
  );
}

function editaMorada(id) {
  $.post(_includes_path+"rpc.php", {op:"carrega_morada", id:id}, function(data) {
    var parts = data.split("##");

    $('#<?php echo $form_outras_moradas['descricao_nova']; ?>').val(parts['0']);
    $('#<?php echo $form_outras_moradas['morada_nova']; ?>').val(parts['1']);
    $('#<?php echo $form_outras_moradas['localidade_nova']; ?>').val(parts['2']);
    $('#<?php echo $form_outras_moradas['distrito_nova']; ?>').val(parts['3']);
    $('#<?php echo $form_outras_moradas['codpostal_nova']; ?>').val(parts['4']);
    $('#<?php echo $form_outras_moradas['pais_nova']; ?>').val(parts['5']).trigger('change');

    $('#editar_morada').val(id);

    $('button.inserir').css('display', 'none');
    $('button.cancel').css('display', 'inline-block');
    $('button.guardar').css('display', 'inline-block');
  });
}

function guardaMorada() {
  var id = $('#editar_morada').val();
  var nome = $('#<?php echo $form_outras_moradas['descricao_nova']; ?>').val();
  var morada = $('#<?php echo $form_outras_moradas['morada_nova']; ?>').val();
  var cod_postal = $('#<?php echo $form_outras_moradas['codpostal_nova']; ?>').val();
  var localidade = $('#<?php echo $form_outras_moradas['localidade_nova']; ?>').val();
  var distrito = $('#<?php echo $form_outras_moradas['distrito_nova']; ?>').val();
  var pais = $('#<?php echo $form_outras_moradas['pais_nova']; ?>').val();

  $.post(_includes_path+"rpc.php", {op:"guarda_morada", id:id, nome:nome, morada:morada, cod_postal:cod_postal, localidade:localidade, distrito:distrito, pais:pais}, function(data) {
    document.location = "area-reservada-moradas.php?alterado=1";
  });
}

function limpaForm() {
  $('#<?php echo $form_outras_moradas['descricao_nova']; ?>').val('');
  $('#<?php echo $form_outras_moradas['morada_nova']; ?>').val('');
  $('#<?php echo $form_outras_moradas['codpostal_nova']; ?>').val('');
  $('#<?php echo $form_outras_moradas['localidade_nova']; ?>').val('');
  $('#<?php echo $form_outras_moradas['distrito_nova']; ?>').val('');
  $('#<?php echo $form_outras_moradas['pais_nova']; ?>').val('0').trigger('change');

  $('#editar_morada').val(0);

  $('button.cancel').css('display', 'none');
  $('button.guardar').css('display', 'none');
  $('button.inserir').css('display', 'table');
}
</script>
<?php if(isset($_GET['alterado']) && $_GET['alterado'] == '1') { ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      ntg_success("<?php echo $Recursos->Resources["ar_alt_dados"]; ?>");
    });
  </script>
<?php } ?>

<?php if(isset($_GET['inserido']) && $_GET['inserido'] == '1') { ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      ntg_success("<?php echo $Recursos->Resources["ar_ins_morada"]; ?>");
    });
  </script>
<?php } ?>

<?php if(isset($_GET['removido']) && $_GET['removido'] == '1') { ?>
  <script type="text/javascript">
    $(window).on('load', function() {
      ntg_error("<?php echo $Recursos->Resources["ar_rem_morada"]; ?>");
    });
  </script>
<?php } ?>

</body>
</html>