<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Mor Mboup
Date de modification:04/10/2023
*/

session_start();
// session_destroy();

date_default_timezone_set('Africa/Dakar');


// var_dump($_SESSION['panier']);

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');
require('connectionPDO.php');

require('declarationVariables.php');


$beforeTime = '00:00:00';
$afterTime = '06:00:00';

    // var_dump(date('d-m-Y',strtotime("-1 days")));

if($_SESSION['Pays']=='Canada'){  
	$date = new DateTime();
	$timezone = new DateTimeZone('Canada/Eastern');
}
else{
	$date = new DateTime();
	$timezone = new DateTimeZone('Africa/Dakar');
}
$date->setTimezone($timezone);
$heureString=$date->format('H:i:s');

// if ($heureString >= $beforeTime && $heureString < $afterTime) {
//    	// var_dump ('is between');
// 	$date = new DateTime (date('d-m-Y',strtotime("-1 days")));
// }

// $date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateHeures=$dateString.' '.$heureString;

$uploaded=0;


function find_p_with_position($pns,$des) {
  foreach($pns as $index => $p) {
      if(($p['idDesignation'] == $des)){
          return $index;
      }
  } 
  return FALSE;
}

$sqlU = "SELECT * FROM `aaa-utilisateur` where idutilisateur=".$_SESSION['iduser'];
$resU = mysql_query($sqlU) or die ("persoonel requête 2".mysql_error());
$user = mysql_fetch_array($resU);

$iduser = $user['idutilisateur'];

// $finDefault = $annee.'-'.$mois.'-'.$jour;
// $dateFin = new DateTime (date('d-m-Y',strtotime("-30 days")));
// $dateFin->setTimezone($timezone);
// $anneeF =$dateFin->format('Y');
// $moisF =$dateFin->format('m');
// $jourF =$dateFin->format('d');
// $debutDefault=$anneeF.'-'.$moisF.'-'.$jourF;

if (isset($_POST['btnannulerPanier'])) {
  // var_dump(15440);
  $_SESSION['panier'] = array();
}

?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()" style="background-color:#eee;">
<?php
/**************** MENU HORIZONTAL *************/

  // require('header.php');

  /************************************* */
 
  $sqlC="SELECT * FROM `".$nomtableCategorie."`";
    
  $statementC = $bdd->prepare($sqlC);
  $statementC->execute();
  $categories = $statementC->fetchAll(PDO::FETCH_ASSOC); 
  // var_dump($_SESSION['panier']);
  $nbProduits = 0;
  if (@$_SESSION['panier']) {
    foreach ($_SESSION['panier'] as $ligne) {
      $nbProduits += $ligne['quantite'];
    }
  }

?>
<style>
  .cycle-tab-container {
  margin: 30px auto;
  /* width: 800px; */
  padding: 20px;
  box-shadow: 0 0 10px 2px #ddd;
  }

  .cycle-tab-container a {
    color: #173649;
    font-size: 16px;
    font-family: roboto;
    /* text-align: center; */
  }

  .tab-pane {
      /* text-align: center; */
      height: auto !important;
      margin: 30px auto;
      width: 100%;
      max-width: 100%;
  }

  .fade {
    opacity: 0;
    transition: opacity 1s ease-in-out;
  }

  .fade.active {
    opacity: 1;
  }

  .cycle-tab-item {
    width: auto;
  }

  .cycle-tab-item:after {
    display:block;
    content: '';
    border-bottom: solid 3px orange;  
    transform: scaleX(0);  
    transition: transform 0ms ease-out;
  }
  .cycle-tab-item.active:after { 
    transform: scaleX(1);
    transform-origin:  0% 50%; 
    transition: transform 5000ms ease-in;
  }


  .nav-link:focus,
  .nav-link:hover,
  .cycle-tab-item.active a {
    border-color: transparent !important;
    color: orange;
  }

  .item {
    position:relative;
    padding-top:20px;
    display:inline-block;
  }
  .notify-badge{
      position: absolute;
      right:-20px;
      top:10px;
      background:green;
      text-align: center;
      border-radius: 30px 30px 30px 30px;
      color:white;
      padding:5px 10px;
      font-size:20px;
  }

  /*****************************************/
  
/* .card {
    background-color: #fff;
    text-align:center;
    margin: 5px;
}

.card:hover {
  box-shadow: 0 5px 5px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
} */

.cardProd {
  
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  transition: all 0.2s ease-in-out;
  box-sizing: border-box;
  margin:10px;
  background-color:#FFF;
  border: none;
  border-radius: 10px;
  text-align:center;
  height: 200px;
}
.card {
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  transition: all 0.2s ease-in-out;
  box-sizing: border-box;
  margin:10px;
  background-color:#FFF;
  border: none;
  border-radius: 10px;
  text-align:center;
  height: 160px;
}

.card:hover {
  box-shadow: 0 2px 2px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
}
.card > .card-inner {
  padding:10px;
}
.card .header h2, h3 {
  margin-bottom: 0px;
  margin-top:0px;
}
.card .header {
  margin-bottom:5px;
}
.card img{
  width:100%;
}
.card-img-prod{
  border-radius: 0px;
  margin-top:3px;
}
.card-img-top{
  margin-top:3px;
}
.card-inner{
  height: 50px;
  overflow:hidden;
}
.nbByArticle{
  background-color:gold;
  color:darkBlue;
  right:10px
}
.btnCart{
  background-color:whitesmoke;
  border-color:gold;
  color:#000;
  float:right;
  font-size:20px;
  border-radius:30px 30px;

}
#nbProduits{
  font-size:24px;
}
</style>  
<br>
  <div class="container">
    <div class="col-lg-12" style="background-color:gold;">
        <div class="row">
          <h4 class="white col-lg-11">Nos Catégories </h4>
          <button class="btn btn-light col-lg-1 btnCart" type="button" onClick="afficherPanier()"><i class="fa fa-shopping-cart" aria-hidden="true"> </i>
            &nbsp;<span class="badge badge-primary" id="nbProduits"> <?= $nbProduits ?></span>
            <span class="sr-only">nombre d'articles</span>
          </button>
        </div>
        <div class="container-fluid">
        <?php
          foreach ($categories as $categorie) {
        ?>
          <!-- <div class=""> -->
            
            <div class="card col-lg-2" onClick="choixCategorie(<?= $categorie['idcategorie'] ?>,'<?= $categorie['nomcategorie'] ?>' )">
              <div class="image">

                <?php  if (file_exists("./imagesCategories/".$categorie['image'])) { ?> 
                    <img class="card-img-top" src="./imagesCategories/<?= ($categorie['image']) ? $categorie['image'] : '1703462078.png' ; ?>" height="100" alt="<?= $categorie['nomcategorie'] ?>"/>

                 <?php } else { ?>
                    <img src="./imagesCategories/1703462078.png" height="100"/>
                <?php } ?>
              </div>
              <div class="card-inner">
                <div class="header">
                  <h5 style="margin-bottom:5px;" title="<?= strtoupper($categorie['nomcategorie']) ?>"><?= strtoupper($categorie['nomcategorie']) ?></h5>
                </div>
              </div>
            </div>
          <!-- </div> -->
          <!-- <li class="list-group-item">
            <label class="radio-inline" for="categorie<?= $categorie['idcategorie'] ?>">
              <input type="radio" onChange="choixCategorie(this.value,'<?= $categorie['nomcategorie'] ?>' )" name="ctyradio" id="categorie<?= $categorie['idcategorie'] ?>" value="<?= $categorie['idcategorie'] ?>"><?= $categorie['nomcategorie'] ?>
            </label><br>
          </li> -->
        <?php
          }
        ?>
      </div>
    </div>
    <!-- <a href="insertionLigneLight.php" class="btn btn-info" style="float:left">Quitter</a> -->
    
    
    <!-- Modal -->
    <div class="modal fade" id="panierContenuModal" tabindex="-1" role="dialog" aria-labelledby="panierContenuModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
            <div align="center">


              <form id="ticket" action="barcodeFacture.php" method="post">
                <input type="hidden" id="idPanierInput" name="idPagnet">
                <button type="button" class="btn btn-success btn_disabled_after_click terminerPanier" onClick="terminerPanier()"><i class="fa fa-check" aria-hidden="true"></i> Terminer</button><!-- // paramettre 1 == avec ticket -->
              </form>
              <!-- <button type="button" class="btn btn-warning btn-xs" style="float:left" onClick="terminerPanier(2)"><i class="fa fa-check" aria-hidden="true"></i> Terminer sans ticket</button>// paramettre 1 == sans ticket -->
            </div>
            <button type="button" style="color:#961f1f" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="panier_container">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#annulerPanierModal" style="float:left">Annuler la vente en cours</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
    
  <div class="container-fluid" style="backgroun-color:white">
    <div id="venteParImage_container">
    </div>
  </div>
  
    <!-- Modal -->
    <div class="modal fade" id="annulerPanierModal" tabindex="-1" role="dialog" aria-labelledby="annulerPanierModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
            <button type="button" style="color:#961f1f" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post">
            <div class="modal-body" id="">
              <h3>
                <code>
                  Êtes-vous sûre de vouloir annuler la vente en cours ? 
                </code>
              </h3>
            </div>
            <div class="modal-footer">
              <button type="submit" name="btnannulerPanier" class="btn btn-success">Oui</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
  
  </div>
<script>
  $(document).ready( function () {
      $('#containerList').DataTable();
  } );
  // alert(265)
  // Tab-Pane change function
  function tabChange() {
      var tabs = $('.nav-tabs > li');
      var active = tabs.filter('.active');
      var next = active.next('li').length? active.next('li').find('a') : tabs.filter(':first-child').find('a');
      next.tab('show');
  }

  $('.tab-pane').hover(function() {
      // clearInterval(tabCycle);
  }, function() {
      // tabCycle = setInterval(tabChange, 5000);
  });

  // Tab Cycle function
  // var tabCycle = setInterval(tabChange, 5000)
      
  // Tab click event handler
  $(function(){
      $('.nav-tabs a').click(function(e) {
          e.preventDefault();
          clearInterval(tabCycle);
          $(this).tab('show')
          // tabCycle = setInterval(tabChange, 5000);
      });
  });

  

  function detailContainer(idContainer,container) {

    numContainer=idContainer;

    $('#numContainerDetail').text(container);
    /*************** Début lister prix boutique ***************/

    $(document).ready(function() {
      

      nbEntreeContainer = $('#nbEntreeContainer').val()



      $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"operation":1,"nbEntreeContainer":nbEntreeContainer,"query":"","cb":""}); //load initial records

      

      //executes code below when user click on pagination links

      $("#resultsDetailsContainer").on( "click", ".pagination a", function (e){

      // $("#resultsDetailsContainer").on( "click", function (e){

          e.preventDefault();

          $(".loading-div").show(); //show loading element

          // page = page+1; //get page number from link

          page = $(this).attr("data-page"); //get page number from link

          //  alert(page)



          nbEntreeContainer = $('#nbEntreeContainer').val()

          query = $('#searchInputContainer').val();



          if (query.length == 0) {

              $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"page":page,"operation":1,"nbEntreeContainer":nbEntreeContainer,"query":"","cb":""}, function(){ //get content from PHP page

                  $(".loading-div").hide(); //once done, hide loading element

              });

                  

          }else{

              $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"page":page,"operation":1,"nbEntreeContainer":nbEntreeContainer,"query":query,"cb":""}, function(){ //get content from PHP page

                  $(".loading-div").hide(); //once done, hide loading element

              });

          }

      });

    });



    /********   RECHERCHE et NOMBRE D'ENTREES   ******/

    $(document).ready(function() {



      $('#searchInputContainer').on("keyup", function(e) {

          e.preventDefault();

          

          query = $('#searchInputContainer').val()

          nbEntreeContainer = $('#nbEntreeContainer').val()



          var keycode = (e.keyCode ? e.keyCode : e.which);

          if (keycode == '13') {

              // alert(1111)

              t = 1; // code barre

              

              if (query.length > 0) {

                  

                  $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"operation":3,"nbEntreeContainer":nbEntreeContainer,"query":query,"cb":t}); //load initial records

              }else{

                  $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"operation":3,"nbEntreeContainer":nbEntreeContainer,"query":"","cb":t}); //load initial records

              }

          }else{

              // alert(2222)

              t = 0; // no code barre

              setTimeout(() => {

                  if (query.length > 0) {

                      

                      $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"operation":3,"nbEntreeContainer":nbEntreeContainer,"query":query,"cb":t}); //load initial records

                  }else{

                      $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"operation":3,"nbEntreeContainer":nbEntreeContainer,"query":"","cb":t}); //load initial records

                  }

              }, 100);

          }

      });

      

      $('#nbEntreeContainer').on("change", function(e) {

          e.preventDefault();



          nbEntreeContainer = $('#nbEntreeContainer').val()

          query = $('#searchInputContainer').val();



          if (query.length == 0) {

              $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"operation":4,"nbEntreeContainer":nbEntreeContainer,"query":"","cb":""}); //load initial records

                  

          }else{

              $("#resultsDetailsContainer" ).load( "ajax/detailContainerAjax.php",{"numContainer":numContainer,"operation":4,"nbEntreeContainer":nbEntreeContainer,"query":query,"cb":""}); //load initial records

          }

      });

    });

    $('#detailContainerModal').modal('show');

  }

</script>