<div class="area_reservada_menu">
  <div class="div_100 show-for-medium">
    <a class="links list_subtit icon-right<?php if($menu_sel_area == "entrada") echo ' sel'; ?>" href="area-reservada.php"><span><?php echo $Recursos->Resources["area_reservada"]; ?></span></a>
    <a class="links list_subtit icon-right<?php if($menu_sel_area == "dados") echo ' sel'; ?>" href="area-reservada-dados.php"><span><?php echo $Recursos->Resources["meus_dados"]; ?></span></a>
    <a class="links list_subtit icon-right<?php if($menu_sel_area == "moradas") echo ' sel'; ?>" href="area-reservada-moradas.php"><span><?php echo $Recursos->Resources["minhas_moradas"]; ?></span></a>
    <a class="links list_subtit icon-right<?php if($menu_sel_area == "encomendas") echo ' sel'; ?>" href="area-reservada-encomendas.php"><span><?php echo $Recursos->Resources["minhas_encomendas"]; ?></span></a>
    <?php if(tableExists(DB::getInstance(), 'lista_desejo')){ ?><a class="links list_subtit icon-right<?php if($menu_sel_area == "favoritos") echo ' sel'; ?>" href="area-reservada-favoritos.php"><span><?php echo $Recursos->Resources["meus_favoritos"]; ?></span></a><?php } ?>
    <a class="links list_subtit icon-right<?php if($menu_sel_area == "tickets") echo ' sel'; ?>" href="area-reservada-tickets.php"><span><?php echo $Recursos->Resources["ar_meus_tickets"]; ?></span></a>
    <!-- <a class="links list_subtit icon-right<?php if($menu_sel_area == "notificacoes") echo ' sel'; ?>" href="area-reservada-notificacoes.php"><span><?php echo $Recursos->Resources["minhas_notificacoes"]; ?></span></a> -->
    <?php if(CARRINHO_CONVIDAR == 1) { ?><a class="links list_subtit icon-right<?php if($menu_sel_area == "convidar") echo ' sel'; ?>" href="area-reservada-convidar.php"><span><?php echo $Recursos->Resources["meus_amigos"]; ?></span></a><?php } ?>
    <a class="links list_subtit icon-right" href="logout.php"><span><?php echo $Recursos->Resources["logout"]; ?></span></a>
  </div>  
  <div class="div_100 hide-for-medium">
    <div class="row collapse text-center align-middle">
      <div class="column">
        <div class="inpt_holder select full" style="margin: 0">
          <select class="inpt" name="menu_mobile" id="menu_mobile" onchange="document.location = this.value;">
            <option value="area-reservada.php"<?php if($menu_sel_area == "entrada") echo ' selected'; ?>><?php echo $Recursos->Resources["area_reservada"]; ?></option>
            <option value="area-reservada-dados.php"<?php if($menu_sel_area == "dados") echo ' selected'; ?>><?php echo $Recursos->Resources["meus_dados"]; ?></option>
            <option value="area-reservada-moradas.php"<?php if($menu_sel_area == "moradas") echo ' selected'; ?>><?php echo $Recursos->Resources["minhas_moradas"]; ?></option>
            <option value="area-reservada-encomendas.php"<?php if($menu_sel_area == "encomendas") echo ' selected'; ?>><?php echo $Recursos->Resources["minhas_encomendas"]; ?></option>
            <?php if(tableExists(DB::getInstance(), 'lista_desejo')){ ?><option value="area-reservada-favoritos.php"<?php if($menu_sel_area == "favoritos") echo ' selected'; ?>><?php echo $Recursos->Resources["meus_favoritos"]; ?></option><?php } ?>
            <option value="area-reservada-tickets.php"<?php if($menu_sel_area == "tickets") echo ' selected'; ?>><?php echo $Recursos->Resources["ar_meus_tickets"]; ?></option>
            <!-- <option value="area-reservada-notificacoes.php"<?php if($menu_sel_area == "notificacoes") echo ' selected'; ?>><?php echo $Recursos->Resources["minhas_notificacoes"]; ?></option> -->
            <?php if(CARRINHO_CONVIDAR == 1) { ?><option value="area-reservada-convidar.php"<?php if($menu_sel_area == "convidar") echo ' selected'; ?>><?php echo $Recursos->Resources["meus_amigos"]; ?></option><?php } ?>
          </select>
        </div>
      </div>
      <div class="column shrink">
        <a class="button invert" href="logout.php"><?php echo $Recursos->Resources["logout"]; ?></a>
      </div>
    </div> 
  </div>
</div>