<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Mor Mboup
Date de modification:04/10/2023
*/

session_start();

date_default_timezone_set('Africa/Dakar');


// var_dump($_SESSION['idBoutique']);

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

?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

  
  $nomtableContainer = $_SESSION['nomB'].'-container';

  if (isset($_POST['btnEnregistrerContainer'])) {
    
    $numContainer=htmlspecialchars(trim($_POST['numContainer']));
    $dateDepart=htmlspecialchars(trim($_POST['dateDepart']));
    $dateArrivee=htmlspecialchars(trim($_POST['dateArrivee']));
    // var_dump($avoir);

        
    $sql6="insert into `".$nomtableContainer."` (numContainer,dateDepart,dateArrivee,idUser) values('".$numContainer."','".$dateDepart."','".$dateArrivee."',".$_SESSION['iduser'].")";

    $res6=mysql_query($sql6) or die ("insertion container impossible =>".mysql_error());

}

  $sql="SELECT * FROM `".$nomtableContainer."`";
    
  $statement = $bdd->prepare($sql);
  $statement->execute();
  $containers = $statement->fetchAll(PDO::FETCH_ASSOC); 

  foreach ($containers as $key) {
      
    $sql="UPDATE `".$nomtablePagnet."` SET numContainer='".$key['idContainer']."' where numContainer='".$key['numContainer']."'";
      
    $statement = $bdd->prepare($sql);
    $statement->execute();
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
</style>  

    <div class="row" align="center">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addContainerModal"> <span class="glyphicon glyphicon-plus"> Ajouter un container</span></button>
    </div>

    <div class="cycle-tab-container container">
      <ul class="nav nav-tabs">
        <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#containerCbm">Détails des arrivages par container</a>
        </li>
       <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivage">Enregistrer un arrivage</a>
        </li>
         <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#settings">settings</a>
        </li> -->
      </ul>
        <div class="tab-content">
        <div class="tab-pane fade active in" id="containerCbm" role="tabpanel" aria-labelledby="containerCbm-tab">
          <table id="containerList" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Numéro container</th>
                    <th>Date départ</th>
                    <th>Date arrivée</th>
                    <th>Opération</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $sql="SELECT * FROM `".$nomtableContainer."` where retirer=0 ORDER BY idContainer DESC";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $containers = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $i=1;
                foreach ($containers as $key) {
                  
                $sql="SELECT * FROM `".$nomtablePagnet."` where type<>2 and numContainer='".$key['idContainer']."'";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $containersPagnetexist = $statement->fetchAll(PDO::FETCH_ASSOC);

              ?>
                <tr id="tr<?= $key['idContainer']?>">
                    <td><?= $i; ?></td>
                    <td><?= $key['numContainer']?></td>
                    <td><?= $key['dateDepart']?></td>
                    <td><?= $key['dateArrivee']?></td>
                    <td><a class="btn btn-xs btn-default" onClick="detailContainer('<?= $key['idContainer']?>','<?= $key['numContainer']?>')"><span class="glyphicon glyphicon-plus"></span> Détails</a>
                    <a class="btn btn-xs btn-info" onClick="editContainer('<?= $key['idContainer']?>')"><span class="glyphicon glyphicon-edit"></span> Modifier</a>
                    <a class="btn btn-xs btn-danger" <?= (sizeof($containersPagnetexist)>0) ? 'disabled' : '' ; ?> onClick="deleteContainer('<?= $key['idContainer']?>')"><span class="glyphicon glyphicon-remove"></span> Supprimer</a></td>
                </tr>
              <?php 
                  $i++;
                }
              ?>
            </tbody>
          </table>          
        </div>
        <div class="tab-pane fade" id="arrivage" role="tabpanel" aria-labelledby="arrivage-tab">
          <!-- <div class="container"> -->
            <div class="col-lg-2" id="divAddArrivageForm" style="width:250px">
              <label for=""><b>Numéro container</b> </label>
              <input type="text" name="numContainer" id="numContainer" class="form-control numContainerChanged" autocomplete="off">
              <br><br>
            </div>
            <table class="table table-bordered table-responsive" style="width:100%" id="tabCbm">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>CBM</th>
                        <th>BAL</th>
                        <th>Nombre de pièces</th>
                        <th>Prix CBM</th>
                        <th>Opération</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <input type="hidden" id="inputCBMHidden" class="form-control">
                        <td><input type="text" id="client_cbm" class="form-control" autocomplete="off"></td>
                        <td><input type="number" step="0.1" id="qty_cbm" class="form-control" value="0"></td>
                        <td><input type="number" step="0.1" id="qty_bal" class="form-control" value="0"></td>
                        <td><input type="number" id="nbPcsInContainer" class="form-control" value="1"></td>
                        <td align="center">127.000</td> 
                        <td align="center"><button type="button" class="btn btn-primary" id="btnValiderCbm" onClick="validerCbm()"><span class="glyphicon glyphicon-ok"></span></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab"> Literally wolf flexitarian snackwave raw denim bitters ut synth kombucha consequat twee polaroid.</div> -->
      </div>
    </div>
      
    
    <div class="modal fade" id="detailContainerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Détails container : <span id="numContainerDetail"></span></h4>

            </div>

            <div class="modal-body">
              <div class="table-responsive">

                <label class="pull-left" for="nbEntreeContainer">Nombre entrées </label>

                <select class="pull-left" id="nbEntreeContainer">

                <optgroup>

                  <option value="10">10</option>

                  <option value="20">20</option>

                  <option value="50">50</option> 

                </optgroup>       

                </select>

                <input class="pull-right" type="text" name="" id="searchInputContainer" placeholder="Rechercher...">

                <div id="resultsDetailsContainer"><!-- content will be loaded here --></div>

              </div>
            </div>
          </div>
        </div>
      </div>
      
    <div class="modal fade" id="addContainerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Ajout de container</h4>

          </div>

          <div class="modal-body">
            <form name="formulaireAjoutContainer" method="post">

              <div class="form-group">

                  <label for="numContainer" class="control-label">Numéro container</label>

                  <input type="text" maxLength="100" class="form-control" id="numContainer" name="numContainer" placeholder="Numéro container" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="dateDepart" class="control-label">Date départ</label>

                  <input type="date" class="form-control" id="dateDepart" name="dateDepart" placeholder="Date départ">

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="dateArrivee" class="control-label">Date arrivée</label>

                  <input type="date" class="form-control" id="dateArrivee" name="dateArrivee" placeholder="Date arrivé">

                  <span class="text-danger" ></span>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="submit" name="btnEnregistrerContainer" class="btn btn-primary">Enregistrer</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="editContainerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Modification de container</h4>

          </div>

          <div class="modal-body">
            <div name="formulaireEditContainer">
              <input type="hidden" id="idContainer_edit">

              <div class="form-group">

                  <label for="numContainer_edit" class="control-label">Motif</label>

                  <input type="text" maxLength="100" class="form-control" id="numContainer_edit" name="numContainer_edit" placeholder="Numéro container" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="dateDepart_edit" class="control-label">Date départ</label>

                  <input type="date" class="form-control" id="dateDepart_edit" name="dateDepart_edit" placeholder="Date départ">

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="dateArrivee_edit" class="control-label">Date arrivée</label>

                  <input type="date" class="form-control" id="dateArrivee_edit" name="dateArrivee_edit" placeholder="Date arrivé">

                  <span class="text-danger" ></span>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="button" name="btnEditerContainer" onClick="confimrEditContainer()" class="btn btn-primary">Enregistrer</button>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <div class="modal fade" id="deleteContainerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Suppresion de container</h4>

          </div>

          <div class="modal-body">
            <div name="deleteContainer">
              <input type="hidden" id="idContainer_delete">
                <p><code>Êtes-vous sûr de vouloir supprimer ce container ?</code></p>
              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="button" name="btnDeleteContainer" onClick="confimrDeleteContainer()" class="btn btn-danger">Supprimer</button>

              </div>
            </div>
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