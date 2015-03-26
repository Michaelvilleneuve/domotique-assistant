
<div class="list-block">
  <ul>
    <li>
      <a href="#" onclick="envoi('nova');" id="nova" class="item-link list-button">Nova</a>
    </li>
    <li>
      <a href="#" onclick="envoi('fip');" id="fip" class="item-link list-button">Fip</a>
    </li>
    <li>
      <a href="#" onclick="envoi('classique');" id="classique" class="item-link list-button">Classique</a>
    </li>
     <li>
      <a href="#" onclick="envoi('virgin');" id="virgin" class="item-link list-button">Virgin</a>
    </li>
     <li>
      <a href="#" onclick="envoi('Frmusique');" id="frm" class="item-link list-button">France Musique</a>
    </li>
     <li>
      <a href="#" onclick="envoi('fun');" id="fun" class="item-link list-button">Fun Radio</a>
    </li>
     <li>
      <a href="#" onclick="envoi('nrj');" id="nrj" class="item-link list-button">NRJ</a>
    </li>
  </ul>
</div>
<div class="content-block" style="margin-bottom: 21px;">   
<div class="row">
  <div class="col-50">
    <a href="#" onclick="envoi('stop');" id="pause" class="button button-big button-red">Pause</a>
  </div>
  <div class="col-50">
    <a href="#" onclick="envoi('play');" id="play" class="button button-big button-green">Play</a>
  </div>
</div>         
</div>
<form id="my-form" style="margin: 15px 0;" class="list-block">
<ul><li>
<div class="item-content">
<div class="item-inner">
<div class="item-input">
<div class="range-slider" >
<input type="range"  min="-5000" max="5000" value="-1000" step="1000" id="volume" name="slider">
</div>
</div>
</div>
</div>
</li>
</ul>
</form>
<div style="margin: 15px 0;" class="content-block">
<p><a href="#" id="serveur"  onClick="var val = $('#volume').val();envoi('vol '+val);" class="button save-storage-data">Changer volume</a></p>
</div>

<div class="content-block">
        <p id="reponse"></p>
    </div>
<script type="text/javascript">
            function envoi(id) {
		                        		$("#reponse").load( "index.php?q=ajax&action=glad", { text : id } );
	                       
                        }  
						
							                            </script>