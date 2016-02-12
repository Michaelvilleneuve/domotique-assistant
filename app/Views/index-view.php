<div class="views tabs toolbar-fixed">
  <!-- Tab 1 - View 1, active by default -->
  <div id="tab1" class="view tab active">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="center"><?= $states['temperature']; ?> <?php echo  $states['humidite']; ?></div>
      </div>
    </div>
    <div class="pages navbar-fixed"> 
      <div data-page="home-1" class="page">
          <!-- /End of Top Navbar-->
 
           <!-- Page content should have additional "pull-to-refresh-content" class -->
  <div class="page-content pull-to-refresh-content" data-ptr-distance="55">
    <!-- Default pull to refresh layer-->
    <div class="pull-to-refresh-layer">
      <div class="preloader"></div>
      <div class="pull-to-refresh-arrow"></div>
    </div>
           
<div class="list-block" style="margin-top:22px">
  <ul>
    <!-- lampe1 -->
 <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Lampe principale</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="lampe1" class="lampes" type="checkbox" <?= $states['lampe1'];?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
 <!-- lampe2 -->
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Lampe secondaire</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="lampe2" class="lampes" type="checkbox" <?= $states['lampe2'];?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>

 
    <!-- led -->
   <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">LED</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="lampe3" class="lampes" type="checkbox" <?= $states['lampe3'];?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>

 
    <!-- pc -->
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">PC</div>
          <div class="item-input">
            <label class="label-switch" id="pclabel">
              <input id="pc" type="checkbox" >
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
   <!-- decodeur -->
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Décodeur TV</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="decodeur" class="lampes" type="checkbox" <?= $states['decodeur'];?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
    <!-- chauffage -->
   <li>
        <div class="item-content">
          <div class="item-inner">
            <div class="item-title label">Chauffage</div>
            <div class="item-input">
              <label class="label-switch">
                <input id="lampe4" class="lampes" type="checkbox" <?= $states['lampe4'];?>>
                <div class="checkbox"></div>
              </label>
            </div>
          </div>
        </div>
      </li>
  </ul>
</div>
<div class="content-block">
<p><a href="#" onclick="post('eteindretout');" id="eteindretout" class="button save-storage-data">Éteindre tout</a></p>
<p><a href="#" onclick="post('allumertout');" id="allumertout" class="button save-storage-data">Allumer tout</a></p>
<p><a href="#" onclick="post('verouiller');" id="verouiller" class="button save-storage-data">Verrouiller</a></p>
</div>

<div class="content-block">   
<div class="row">
  <div class="col-50">
    <a href="#" onclick="post('ouvrir');" id="ouvrir" class="button button-big button-red">Ouvrir</a>
  </div>
  <div class="col-50">
    <a href="#" onclick="post('fermer');" id="fermer" class="button button-big button-green">Fermer</a>
  </div>
</div>         
</div>

          </div>
      </div>
    </div>
  </div>
 <div id="tab2" class="view tab">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="center">Musique</div>
      </div>
    </div>
    <div class="pages navbar-fixed">
      <div data-page="home-2" class="page">
        <div class="page-content">
          <div class="content-block" id="camera">
            <p>Chargement</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="tab4" class="view tab">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="center">Gladys</div>
      </div>
    </div>
    <div class="pages navbar-fixed">
      <div data-page="home-2" class="page">
        <div class="page-content">
          <div class="content-block" id="routeur">
            <p>Chargement</p>
          </div>
        </div>
      </div>
    </div>
  </div>

 <div id="tab3" class="view tab">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="center">Paramètres / Stats</div>
      </div>
    </div>
    <div class="pages navbar-fixed">
      <div data-page="home-2" class="page">
        <div class="page-content">
          <div class="content-block" id="stats">
            <p>Chargement</p>
          </div>
        </div>
      </div>
    </div>
  </div>
            
          <!-- Bottom Toolbar-->
           <div class="toolbar tabbar">
    <div class="toolbar-inner">
      <a href="#tab1" class="tab-link active"><i class="icon pe-7s-home"></i></a>
      <a href="#tab2" id="tabcam" class="tab-link"><i class="icon pe-7s-musiclist"></i></a>
      <a href="#tab4" id="tabroot" class="tab-link"><i class="icon pe-7s-user-female"></i></a>
      <a href="#tab3" id="tabstats" class="tab-link"><i class="icon pe-7s-server"></i></a>
    </div>
  </div>
</div>  

<script type="text/javascript">
var myApp = new Framework7();
 
var $$ = Dom7;
var ptrContent = $$('.pull-to-refresh-content');
ptrContent.on('refresh', function (e) {
  top.frames.location.reload();
});
// Requête ping PC affichage pastille
$('#pclabel').load('index.php?q=ajax&action=ping');
// Requete pour tab caméra
$("#tabcam").click(function(){
  $('#camera').load('index.php?q=ajax&action=camera');
});
// Requête pour tab stats
$("#tabstats").click(function(){
  $('#stats').load('index.php?q=ajax&action=stats');
});
// Requête pour tab routeur
$("#tabroot").click(function(){
  $('#routeur').load('index.php?q=ajax&action=routeur');
});
// Fonction 
$("#eteindretout").click(function(){
  $('.lampes').attr('checked', false);
});
$("#allumertout").click(function(){
  $('.lampes').attr('checked', true);
});
$("#ouvrir").on('click',function() {
  $(this).text('Ouverture...');
  setTimeout(function(){
  $("#ouvrir").text('Ouvrir');
  },2000);
});
$("#verouiller").on('click',function() {
  $(this).text('Verouillage...');
  setTimeout(function(){
  $("#verouiller").text('Verouiller');
  },2000);
});
$("#lampe1").change(function() {
  post('lampe1');
});
$("#decodeur").change(function() {
  post('decodeur');
});
$("#lampe2").change(function() {
  post('lampe2');
});
$("#lampe3").change(function() {
  post('lampe3');
});
$("#lampe4").change(function() {
  post('lampe4');
});    
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
$("#pc").change(function() {
  post('pc');
  setTimeout(function(){
    var iframe = document.createElement("IFRAME");
  iframe.setAttribute("src", 'data:text/plain,');
  document.documentElement.appendChild(iframe);
  window.frames[0].window.alert('Allumage en cours, rafraichissement dans 30 secondes.');
  iframe.parentNode.removeChild(iframe);
  },100);
    setTimeout(function(){
    location.reload() ;
    }, 40000);
});                      
</script>