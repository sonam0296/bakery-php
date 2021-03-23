<?php include_once('../inc_pages.php'); ?>
<?  
header("Cache-Control: no-store, no-cache, must-revalidate");header("Cache-Control: post-check=0, pre-check=0", false);header("Pragma: no-cache");header("Content-type: text/html; charset=UTF-8");
?>
<?

if ($_POST['op'] == 'altera_qntd' ) {	
	
	$prod=$_POST['prod'];
	$enc=$_POST['encomenda'];
	$qntd=$_POST['quantidade'];
	
	$insertSQL = "UPDATE encomendas_produtos SET qtd=:qntd WHERE id_encomenda=:id_encomenda AND produto_id =:produto_id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':qntd', $qntd, PDO::PARAM_INT);
	$rsInsert->bindParam(':id_encomenda', $enc, PDO::PARAM_INT);
	$rsInsert->bindParam(':produto_id', $prod, PDO::PARAM_INT);
	$rsInsert->execute();
	
	$query_rsEncomenda = "SELECT estado FROM encomendas WHERE id=:id_encomenda";
	$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);
	$rsEncomenda->bindParam(':id_encomenda', $enc, PDO::PARAM_INT);	
	$rsEncomenda->execute();
	$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsEncomenda = $rsEncomenda->rowCount();
	
	$query_rsCarrinho = "SELECT * FROM encomendas_produtos WHERE encomendas_produtos.id_encomenda=:id_encomenda";
	$rsCarrinho = DB::getInstance()->prepare($query_rsCarrinho);
	$rsCarrinho->bindParam(':id_encomenda', $enc, PDO::PARAM_INT);	
	$rsCarrinho->execute();
	$totalRows_rsCarrinho = $rsCarrinho->rowCount();
		
	while($row_rsCarrinho = $rsCarrinho->fetch()) { ?>
    <tr>
    <td>
        <?php echo $row_rsCarrinho['produto']; ?>
    </td>
    <td>
        <?php echo  number_format($row_rsCarrinho['preco'],2,',',' '); ?> &pound;
    </td>
    <td>
        <div id="qtd_prod_<?php echo $row_rsCarrinho['produto_id']; ?>">
            <?php echo $row_rsCarrinho['qtd']; ?> 
            <a href="javascript:" class="edit-prod-qtd" id="edit_product_qtd_<?php echo $row_rsCarrinho['produto_id']; ?>"><i style="margin-left: 10px" class="fa fa-pencil fa-lg"></i></a>
        </div>
        <div id="editar_prod_<?php echo $row_rsCarrinho['produto_id']; ?>" class="editar-qtd-prod">
            <form id="edit_qtd_prod" name="edit_qtd_prod" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
    <div class="col-md-8">
    <input class="form-control" id="prod_qtd_<?php echo $row_rsCarrinho['produto_id']; ?>" name="prod_qtd_<?php echo $row_rsCarrinho['produto_id']; ?>" value="<?php echo $row_rsCarrinho['qtd']; ?>">
    </div>
    <a href="javascript:" class="cancel_prod_qtd" id="cancel_prod_qtd_<?php echo $row_rsCarrinho['id']; ?>"><i class="fa fa-times"></i></a>
    <a onClick="altera_qntd('<?php echo $row_rsCarrinho['produto_id']; ?>')" href="javascript:" class="cancel_prod_qtd" id="save_prod_qtd_<?php echo $row_rsCarrinho['produto_id']; ?>"><i class="fa fa-save"></i></a>
    
    <input type="hidden" name="prod" value="<?php echo $row_rsCarrinho['produto_id']; ?>">
            <input type="hidden" name="MM_edit" value="edit_qtd_prod" />
            </form>
        </div>
    </td>
    <td>
        <?php echo round($row_rsCarrinho['preco'] * $row_rsCarrinho['qtd'], 2); ?> &pound;
    </td>
    <td>
        <?php if($row_rsCarrinho['iva']>0){ echo $row_rsCarrinho['iva']; }?> %
    </td>
    <td>
        <?php echo round(($row_rsCarrinho['preco'] * $row_rsCarrinho['qtd']) * ($row_rsCarrinho['iva'] / 100), 2); ?> &pound;
    </td>
    <?php if($row_rsEncomenda['estado'] == 1 || $row_rsEncomenda['estado'] == 2) { ?>
    <td>
        <a onClick="remove_prod('<?php echo $row_rsCarrinho['produto_id']; ?>')" href="javascript:"><i style="color: red" class="fa fa-times fa-lg"></i></a>
    </td>
    <?php } ?>
    </tr>
    <?php } 
}
if ($_POST['op'] == 'remove_prod' ) {	
	
	$prod=$_POST['prod'];
	$enc=$_POST['encomenda'];
		
	$insertSQL = "DELETE FROM encomendas_produtos WHERE id_encomenda=:id_encomenda AND produto_id=:produto_id";
	$rsInsert = DB::getInstance()->prepare($insertSQL);
	$rsInsert->bindParam(':id_encomenda', $enc, PDO::PARAM_INT);
	$rsInsert->bindParam(':produto_id', $prod, PDO::PARAM_INT);
	$rsInsert->execute();
	
	$query_rsEncomenda = "SELECT estado FROM encomendas WHERE id=:id_encomenda";
	$rsEncomenda = DB::getInstance()->prepare($query_rsEncomenda);
	$rsEncomenda->bindParam(':id_encomenda', $enc, PDO::PARAM_INT);	
	$rsEncomenda->execute();
	$row_rsEncomenda = $rsEncomenda->fetch(PDO::FETCH_ASSOC);
	$totalRows_rsEncomenda = $rsEncomenda->rowCount();
	
	$query_rsCarrinho = "SELECT * FROM encomendas_produtos WHERE encomendas_produtos.id_encomenda=:id_encomenda";
	$rsCarrinho = DB::getInstance()->prepare($query_rsCarrinho);
	$rsCarrinho->bindParam(':id_encomenda', $enc, PDO::PARAM_INT);	
	$rsCarrinho->execute();
	$totalRows_rsCarrinho = $rsCarrinho->rowCount();
	
	while($row_rsCarrinho = $rsCarrinho->fetch()) { ?>
    <tr>
    <td>
        <?php echo $row_rsCarrinho['produto']; ?>
    </td>
    <td>
        <?php echo  number_format($row_rsCarrinho['preco'],2,',',' '); ?> &pound;
    </td>
    <td>
        <div id="qtd_prod_<?php echo $row_rsCarrinho['produto_id']; ?>">
            <?php echo $row_rsCarrinho['qtd']; ?> 
            <a href="javascript:" class="edit-prod-qtd" id="edit_product_qtd_<?php echo $row_rsCarrinho['produto_id']; ?>"><i style="margin-left: 10px" class="fa fa-pencil fa-lg"></i></a>
        </div>
        <div id="editar_prod_<?php echo $row_rsCarrinho['produto_id']; ?>" class="editar-qtd-prod">
            <form id="edit_qtd_prod" name="edit_qtd_prod" class="form-horizontal form-row-seperated" method="post" role="form" enctype="multipart/form-data">
    <div class="col-md-8">
    <input class="form-control" id="prod_qtd_<?php echo $row_rsCarrinho['produto_id']; ?>" name="prod_qtd_<?php echo $row_rsCarrinho['produto_id']; ?>" value="<?php echo $row_rsCarrinho['qtd']; ?>">
    </div>
    <a href="javascript:" class="cancel_prod_qtd" id="cancel_prod_qtd_<?php echo $row_rsCarrinho['id']; ?>"><i class="fa fa-times"></i></a>
    <a onClick="altera_qntd('<?php echo $row_rsCarrinho['produto_id']; ?>')" href="javascript:" class="cancel_prod_qtd" id="save_prod_qtd_<?php echo $row_rsCarrinho['produto_id']; ?>"><i class="fa fa-save"></i></a>
    
    <input type="hidden" name="prod" value="<?php echo $row_rsCarrinho['produto_id']; ?>">
            <input type="hidden" name="MM_edit" value="edit_qtd_prod" />
            </form>
        </div>
    </td>
    <td>
        <?php echo round($row_rsCarrinho['preco'] * $row_rsCarrinho['qtd'], 2); ?> &pound;
    </td>
    <td>
        <?php if($row_rsCarrinho['iva']>0){ echo $row_rsCarrinho['iva']; }?> %
    </td>
    <td>
        <?php echo round(($row_rsCarrinho['preco'] * $row_rsCarrinho['qtd']) * ($row_rsCarrinho['iva'] / 100), 2); ?> &pound;
    </td>
    <?php if($row_rsEncomenda['estado'] == 1 || $row_rsEncomenda['estado'] == 2) { ?>
    <td>
        <a href="javascript:;" onClick="abre_modal('<?php echo $row_rsCarrinho['produto_id']; ?>')"><i style="color: red" class="fa fa-times fa-lg"></i></a>
        <a href="#modal_delete_prod" data-toggle="modal" id="abre_modal"></a>
    </td>
    <?php } ?>
    </tr>
    <?php } 
}

DB::close();

?>