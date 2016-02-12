<div class="list-block">
  <ul>
    <!-- Text inputs -->
 <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Gestion automatisée chauffage</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="chauffage" class="lampes" type="checkbox" <?= $chaufauto;?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Gestion automatisée réveil</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="reveil" class="lampes" type="checkbox" <?= $reveilauto;?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
	<li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">État vérouillage</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="verouillage" class="lampes" type="checkbox" <?= $verrouillage;?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>

  </ul>
</div>
<div class="list-block">
  <ul>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-light"></i></div>
      <div class="item-inner">
        <div class="item-title">Lampe principale</div>
        <div class="item-after"><?= $nb['lampe1']?></div>
      </div>
    </li>
     <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-light"></i></div>
      <div class="item-inner">
        <div class="item-title">Lampe secondaire</div>
        <div class="item-after"><?php echo $nb['lampe2']?></div>
      </div>
    </li>
     <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-light"></i></div>
      <div class="item-inner">
        <div class="item-title">LED TV</div>
        <div class="item-after"><?php echo $nb['lampe3']?></div>
      </div>
    </li>
     <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-light"></i></div>
      <div class="item-inner">
        <div class="item-title">Chauffage</div>
        <div class="item-after"><?php echo $nb['lampe4']?></div>
      </div>
    </li>
     <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-network"></i></div>
      <div class="item-inner">
        <div class="item-title">PC</div>
        <div class="item-after"><?php echo $nb['pc']?></div>
      </div>
    </li>
     <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-server"></i></div>
      <div class="item-inner">
        <div class="item-title">Reboot serveur</div>
        <div class="item-after"><?php echo $nb['serveur']?></div>
      </div>
    </li>
   </ul>
  <div class="list-block-label">Statistiques débutées le 9 Février 2015.</div>
	<ul>
	 <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-stopwatch"></i></div>
      <div class="item-inner">
        <div class="item-title">Dernier Reboot
        </div>
            <div class="item-after">Le <?php echo $nb['datereboot'];?></span></div>
      </div>
    </li>
   </ul>
</div>
<script type="text/javascript">

                            function post(id){
                            	var idpdf = id;
                            	if ($('#'+id).is(':checked')) {
                            	var val = "1";
                            	}
                            	else {
	                            	var val = "0";
                            	}
	                          $.post( "index.php?q=ajax&action="+id+"", { val: val } );
                            }
$("#chauffage").change(function() {
	post('chauffage');
});
$("#verouillage").change(function() {
	post('deverrouiller');
});
$("#reveil").change(function() {
	post('reveil');
});
</script>