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
	header('Location:../../index.php');
}
require('../connection.php');
require('../connectionPDO.php');

require('../declarationVariables.php');


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

<?php require('entetehtmlCargo.php'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header-cargo.php');

  
  $nomtableContainer = $_SESSION['nomB'].'-container';

  if (isset($_POST['btnEnregistrerContainer'])) {
    
    $numContainer=htmlspecialchars(trim($_POST['numContainer']));
    $dateDepart=htmlspecialchars(trim($_POST['dateDepart']));
    $dateArrivee=htmlspecialchars(trim($_POST['dateArrivee']));
    // var_dump($avoir);

        
    $sql6="insert into `".$nomtableContainer."` (numContainer,dateDepart,dateArrivee,idUser) values('".$numContainer."','".$dateDepart."','".$dateArrivee."',".$_SESSION['iduser'].")";

    $res6=mysql_query($sql6) or die ("insertion container impossible =>".mysql_error());

}

  // $sql="SELECT * FROM `".$nomtableContainer."`";
    
  // $statement = $bdd->prepare($sql);
  // $statement->execute();
  // $containers = $statement->fetchAll(PDO::FETCH_ASSOC); 

  // foreach ($containers as $key) {
      
  //   $sql="UPDATE `".$nomtablePagnet."` SET numContainer='".$key['idContainer']."' where numContainer='".$key['numContainer']."'";
      
  //   $statement = $bdd->prepare($sql);
  //   $statement->execute();
  // }

?>
<style>
  #divAddArrivageEmplacement .typeahead {

    width: 20%;

    /* background: #fff none repeat scroll 0 0; */

    border: medium none;

    color: #333;

    font-size: 15px;

    /* font-weight: 300; */

    /* text-align: left; #c43b68*/

    height: auto;

    max-height : 250px;

    overflow-y: scroll;

  }
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

    <!-- <div class="row" align="center">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addContainerModal"> <span class="glyphicon glyphicon-plus"> Ajouter un container</span></button>
    </div> --> 
    <div class="cycle-tab-container container-fluid">
      <ul class="nav nav-tabs"> 
        <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivageContainer">Liste des arrivages par container</a>
        </li>
        <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivageAvion">Liste des arrivages par avion</a>
        </li>
        <!-- <li class="cycle-tab-item pull-right">
          <input type="text" id="searchByBarcode" class="form-control" autocomplete="off" placeholder="Rechercher par code-barre...">
        </li> -->
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade active in" id="arrivageContainer" role="tabpanel" aria-labelledby="arrivageContainer-tab">
          <table id="arrivageContainerList" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Numéro container</th>
                    <th>Numéro BL</th><!-- Numéro booking -->
                    <th>Date départ</th>
                    <th>Date arrivée</th>
                    <th>Opération</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $sql="SELECT * FROM `".$nomtableContainer."` where retirer=0 and etat=2 ORDER BY idContainer DESC";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $containers = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $i=1;
                foreach ($containers as $key) {
                  
                $sql="SELECT * FROM `".$nomtablePagnet."` where type<>2 and numContainer='".$key['idContainer']."'";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $containersPagnetexist = $statement->fetchAll(PDO::FETCH_ASSOC);
                
                  
                $sql="SELECT * FROM `".$nomtableEnregistrement."` where idContainer='".$key['idContainer']."'";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $enregexist = $statement->fetchAll(PDO::FETCH_ASSOC);

              ?>
                <tr id="tr<?= $key['idContainer']?>">
                    <td><?= $i; ?></td>
                    <td><?= $key['numContainer']?></td>
                    <td><?= $key['numBooking']?></td>
                    <td><?= $key['dateDepart']?></td>
                    <td><?= $key['dateArrivee']?></td>
                    <td>
                      <a href="cargo/arrivageDetails-manager.php?c=<?= $key['idContainer']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus"></span> </a>
                    </td>
                </tr>
              <?php 
                  $i++;
                }
              ?>
            </tbody>
          </table>          
        </div>
        <div class="tab-pane fade" id="arrivageAvion" role="tabpanel" aria-labelledby="arrivageAvion-tab">
          <table id="arrivageAvionList" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Numéro vol</th>
                    <th>Numéro suivi</th>
                    <th>Nombre de pièces</th>
                    <th>Date départ</th>
                    <th>Date arrivée</th>
                    <th>Opération</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $sql="SELECT * FROM `".$nomtableAvion."` where retirer=0 and etat=2 ORDER BY idAvion DESC";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $avions = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $i=1;
                foreach ($avions as $key) {
                  
                $sql="SELECT * FROM `".$nomtablePagnet."` where type<>2 and idAvion='".$key['idAvion']."'";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $volPagnetexist = $statement->fetchAll(PDO::FETCH_ASSOC);

                $sql="SELECT * FROM `".$nomtableEnregistrement."` where idAvion='".$key['idAvion']."'";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $enregexist = $statement->fetchAll(PDO::FETCH_ASSOC);

              ?>
                <tr id="tr<?= $key['idAvion']?>">
                    <td><?= $i; ?></td>
                    <td><?= $key['numVol']?></td>
                    <td><?= $key['numBooking']?></td>
                    <td><?= $key['nombrePieces']?></td>
                    <td><?= $key['dateDepart']?></td>
                    <td><?= $key['dateArrivee']?></td>
                    <td>
                      <a href="cargo/arrivageDetails-manager.php?a=<?= $key['idAvion']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus"></span> </a>
                    </td>
                </tr>
              <?php 
                  $i++;
                }
              ?>
            </tbody>
          </table>          
        </div>
      </div>
    </div>
      
        
    <div class="modal fade" id="serachByBarcodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Résultat de ma recherche : Enregistrement N° : <span id="numEnreg"></span></h4>

            </div> 

            <div class="modal-body row" style="font-size:16px;font-style:arial;">  
            <table class="table table-striped">
              <tr>
                <td>
                  <div class="col-sm-12 col-lg-6 col-md-6">Nom du client</div>
                </td>
                <td>
                  <div class="col-sm-12 col-lg-6 col-md-6" id="nomClientTxt"></div>

                </td>
              </tr>
              <tr>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6">Téléphone</div>

                </td>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="telClientTxt"></div>

                </td>
              </tr>
              <tr>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6">Date enregistrement</div>

                </td>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="dateEnregTxt"></div>

                </td>
              </tr>
              <tr>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6">Date chargement</div>

                </td>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="dateChargTxt"></div>

                </td>
              </tr>
              <tr>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6">Porteur</div>

                </td>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="porteurTxt"></div>

                </td>
              </tr>
              <tr>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="qtyByPorteurTxt"></div>

                </td>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="qtyTxt"></div>

                </td>
              </tr>
              <tr>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="prixByPorteurTxt"></div>

                </td>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="prixTxt"></div>

                </td>
              </tr>
              <tr>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6">Nombre de pièces</div>

                </td>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="nbPcsTxt"></div>

                </td>
              </tr>
              <tr>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6">Nature des bagages</div>

                </td>
                <td>
                                  <div class="col-sm-12 col-lg-6 col-md-6" id="NatBTxt"></div>

                </td>
              </tr>
            </table>   
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
              <!-- <button type="button" id="btnEditEnreg" onClick="deleteEnregistrementValidataion()" class="btn btn-danger">Supprimer</button> -->
            </div>
          </div>
        </div>
    </div>

    <div id="getImage"  class="modal fade " role="dialog">
      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header" style="padding:35px 50px;">

            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>

          </div>
          <div class="modal-body" style="padding:40px 50px;" align="center">
            <img src="" alt="" id="getImg">
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready( function () {
          $('#arrivageContainerList').DataTable();
          $('#arrivageAvionList').DataTable();
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

    </script>