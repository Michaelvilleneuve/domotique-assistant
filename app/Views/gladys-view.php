<div class="list-block">
  <ul>
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-input">
        <textarea id="text" placeholder="Bonjour Michaël, que puis-je faire pour vous ?" autofocus></textarea>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>
<div class="content-block">
        <p id="reponse"></p>
        <p id="reponse2"></p>
    </div>
<script type="text/javascript">
function envoi() {
    var val = $("#text").val();
    var resultat = val.indexOf('glad'); 
    var resultat2 = val.indexOf('Glad'); 
    if (resultat > 0){
		$("#reponse").load( "index.php?q=ajax&action=glad", { text : val } );
		$("#text").val('');
    }
    else if (resultat2 > 0){
		$("#reponse").load( "index.php?q=ajax&action=glad", { text : val } );
		$("#text").val('');
    }
    else {
	    setTimeout(function(){
	    	var val2 = $("#text").val();
	    	if(val == val2){
	    		$("#reponse2").load( "index.php?q=ajax&action=glad", { text : val } );
	    		$("#text").val('');
	    		}
	    }, 2000);
    }
}
$('#text').keyup(function(){
	envoi();
});  
$('#text').change(function(){
	envoi();
});                            	
</script>