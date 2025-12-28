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

  
  $nomtableNature = $_SESSION['nomB'].'-nature';

  if (isset($_POST['btnEnregistrerNature'])) {
    
    $libelle=htmlspecialchars(trim($_POST['libelle']));
    $prix_cbm=htmlspecialchars(trim($_POST['prix_cbm']));
    $prix_fret=htmlspecialchars(trim($_POST['prix_fret']));
    $idNature=@htmlspecialchars(trim($_POST['idNature']));
    // var_dump($avoir);


    if ($idNature!="" && $idNature!=null) {
      
      $sql="UPDATE `".$nomtableNature."` SET libelle='".$libelle."', prix_cbm='".$prix_cbm."', prix_fret='".$prix_fret."' where idNature='".$idNature."'";
        
      $statement = $bdd->prepare($sql);
      $statement->execute();

    } else {
          
      $sql6="insert into `".$nomtableNature."` (libelle,prix_cbm,prix_fret) values('".$libelle."','".$prix_cbm."','".$prix_fret."')";

      $res6=mysql_query($sql6) or die ("insertion nature impossible =>".mysql_error());
    }
}

  // $sql="SELECT * FROM `".$nomtableContainer."`";
    
  // $statement = $bdd->prepare($sql);
  // $statement->execute();
  // $containers = $statement->fetchAll(PDO::FETCH_ASSOC); 

  // foreach ($containers as $key) {
      
  //   $sql="UPDATE `".$nomtablePagnet."` SET numContainer='".$key['idEntrepot']."' where numContainer='".$key['numContainer']."'";
      
  //   $statement = $bdd->prepare($sql);
  //   $statement->execute();
  // }

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
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNatureModal"> <span class="glyphicon glyphicon-plus"> Ajouter une nature</span></button>
    </div>

    <div class="cycle-tab-container container">
      <ul class="nav nav-tabs"> 
        <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#entrepot">Liste des natures de bagages</a>
        </li>
       <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivage">Enregistrer un arrivage</a>
        </li> -->
         <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#settings">settings</a>
        </li> -->
      </ul>
        <div class="tab-content">
        <div class="tab-pane fade active in" id="catalogue" role="tabpanel" aria-labelledby="catalogue-tab">
          <table id="catalogueList" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Nature bagage</th>
                    <th>Prix CBM (FCFA)</th>
                    <th>Prix FRET (FCFA)</th>
                    <!-- <th>Etat</th> -->
                    <!-- <th>Récupération bagages</th> -->
                    <th>Opération</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $sql="SELECT * FROM `".$nomtableNature."` where retirer=0 ORDER BY idNature DESC";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $natures = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $i=1;
                foreach ($natures as $key) {
                  
                // $sql="SELECT * FROM `".$nomtablePagnet."` where type<>2 and numContainer='".$key['idEntrepot']."'";
                
                // $statement = $bdd->prepare($sql);
                // $statement->execute();
                
                // $containersPagnetexist = $statement->fetchAll(PDO::FETCH_ASSOC);
                $containersPagnetexist = 1;

              ?>
                <tr id="tr<?= $key['idNature']?>">
                    <td><?= $i; ?></td>
                    <td><?= $key['libelle']?></td>
                    <td><?= number_format($key['prix_cbm'], 2, ',', ' ')?></td>
                    <td><?= number_format($key['prix_fret'], 2, ',', ' ')?></td>
                    
                    <td>
                      <a class="btn btn-xs btn-info" onClick="editNature('<?= $key['idNature']?>')"><span class="glyphicon glyphicon-edit"></span> </a>
                      <a class="btn btn-xs btn-danger" onClick="deleteNature('<?= $key['idNature']?>','<?= $key['libelle']?>')"><span class="glyphicon glyphicon-remove"></span> </a>
                    </td>
                </tr>
              <?php 
                  $i++;
                }
              ?>
            </tbody>
          </table>          
        </div>
        <!-- <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab"> Literally wolf flexitarian snackwave raw denim bitters ut synth kombucha consequat twee polaroid.</div> -->
      </div>
    </div>   
      
    <div class="modal fade" id="addNatureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Ajout de nature de bagage</h4>

          </div>

          <div class="modal-body">
            <form name="formulaireAjoutNature" method="post">

              <div class="form-group">

                  <label for="libelle" class="control-label">Libellé</label>

                  <input type="text" maxLength="100" class="form-control" id="libelle" name="libelle" placeholder="Libellé nature" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="prix_cbm" class="control-label">Prix CBM</label>

                  <input type="number" class="form-control" id="prix_cbm" name="prix_cbm" placeholder="Prix CBM">

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="prix_fret" class="control-label">Prix FRET</label>

                  <input type="number" class="form-control" id="prix_fret" name="prix_fret" placeholder="Prix FRET">

                  <span class="text-danger" ></span>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="submit" name="btnEnregistrerNature" class="btn btn-primary">Enregistrer</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="editNatureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Modification de container</h4>

          </div>

          <div class="modal-body">
            <form name="formulaireEditNature" method="post">
                
              <input type="hidden" id="idNature_edit" name="idNature">
              <div class="form-group">

                  <label for="libelle_edit" class="control-label">Libellé</label>

                  <input type="text" maxLength="100" class="form-control" id="libelle_edit" name="libelle" placeholder="Libellé nature" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="prix_cbm_edit" class="control-label">Prix CBM</label>

                  <input type="number" class="form-control" id="prix_cbm_edit" name="prix_cbm" placeholder="Prix CBM">

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="prix_fret_edit" class="control-label">Prix FRET</label>

                  <input type="number" class="form-control" id="prix_fret_edit" name="prix_fret" placeholder="Prix FRET">

                  <span class="text-danger" ></span>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="submit" name="btnEnregistrerNature" class="btn btn-primary">Enregistrer</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="deleteNatureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Suppresion de nature</h4>

          </div>

          <div class="modal-body">
            <div name="deleteNature">
              <input type="hidden" id="idNature_delete">
                <p><code>Êtes-vous sûr de vouloir supprimer l'entrepot <b><span id="libelleNatureToDelete"></span></b> ?</code></p>
              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="button" name="btnDeleteNature" onClick="confirmDeleteNature()" class="btn btn-danger">Supprimer</button>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready( function () {
          $('#catalogueList').DataTable();
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