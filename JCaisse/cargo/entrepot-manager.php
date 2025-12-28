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

  if (isset($_POST['btnEnregistrerEntrepot'])) {
    
    $nomEntrepot=htmlspecialchars(trim($_POST['nomEntrepot']));
    $adresse=htmlspecialchars(trim($_POST['adresse']));
    $typeEntrepot=htmlspecialchars(trim($_POST['typeEntrepot']));
    $idEntrepot=@htmlspecialchars(trim($_POST['idEntrepot']));
    // var_dump($idEntrepot);

    if ($idEntrepot!="" && $idEntrepot!=null) {
      
      $sql="UPDATE `".$nomtableEntrepot."` SET nomEntrepot='".$nomEntrepot."', adresseEntrepot='".$adresse."', type='".$typeEntrepot."' where idEntrepot='".$idEntrepot."'";
        
      $statement = $bdd->prepare($sql);
      $statement->execute();

    } else {

      $sql6="insert into `".$nomtableEntrepot."` (nomEntrepot,adresseEntrepot,type) values ('".$nomEntrepot."','".$adresse."','".$typeEntrepot."')";

      $res6=mysql_query($sql6) or die ("insertion entrepot impossible =>".mysql_error());

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
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEntrepotModal"> <span class="glyphicon glyphicon-plus"> Ajouter un entrepot</span></button>
    </div>

    <div class="cycle-tab-container container">
      <ul class="nav nav-tabs"> 
        <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#entrepot">Liste des entrepots</a>
        </li>
       <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivage">Enregistrer un arrivage</a>
        </li> -->
         <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#settings">settings</a>
        </li> -->
      </ul>
        <div class="tab-content">
        <div class="tab-pane fade active in" id="entrepot" role="tabpanel" aria-labelledby="entrepot-tab">
          <table id="entrepotList" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Nom entrepot</th>
                    <th>Adresse</th>
                    <th>Type</th>
                    <!-- <th>Etat</th> -->
                    <!-- <th>Récupération bagages</th> -->
                    <th>Opération</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $sql="SELECT * FROM `".$nomtableEntrepot."` where retirer=0 ORDER BY idEntrepot DESC";
                
                $statement = $bdd->prepare($sql);
                $statement->execute();
                
                $entrepots = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $i=1;
                foreach ($entrepots as $key) {
                  
                // $sql="SELECT * FROM `".$nomtablePagnet."` where type<>2 and numContainer='".$key['idEntrepot']."'";
                
                // $statement = $bdd->prepare($sql);
                // $statement->execute();
                
                // $containersPagnetexist = $statement->fetchAll(PDO::FETCH_ASSOC);
                // $containersPagnetexist = 1;

              ?>
                <tr id="tr<?= $key['idEntrepot']?>">
                    <td><?= $i; ?></td>
                    <td><?= $key['nomEntrepot']?></td>
                    <td><?= $key['adresseEntrepot']?></td>
                    
                      <?php 
                          if ($key['type']==1) {
                            
                      ?>
                      <td> Départ</td>
                    <?php 
                      } else if ($key['type']==2) {
                        ?>
                        <td>Arrivée</td>
                        <?php 
                      } 
                      else {
                        ?>
                      <td>-------</td>
                      <?php 
                        }
                            
                      ?>
                    <td>
                      <a class="btn btn-xs btn-default" onClick="detailGlobalEntrepot('<?= $key['idEntrepot']?>','<?= $key['nomEntrepot'].' - '.$key['adresseEntrepot']?>')"><span class="glyphicon glyphicon-plus"></span> </a>
                      <a class="btn btn-xs btn-info" onClick="editEntrepot('<?= $key['idEntrepot']?>')"><span class="glyphicon glyphicon-edit"></span> </a>
                      <a class="btn btn-xs btn-danger" onClick="deleteEntrepot('<?= $key['idEntrepot']?>','<?= $key['nomEntrepot']?>')" ><span class="glyphicon glyphicon-remove"></span> </a>
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
      
    
    <div class="modal fade" id="detailEntrepotModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Détails des enregistrements de l'entrepot : <span id="nomEntrepotDetail"></span></h4>
                <!-- <h4 class="modal-title" align="center"> Etat : <span id="etatContainerDetail"> </span></h4> -->

            </div>

            <div class="modal-body">
              <div class="table-responsive">

                <div id="resultsDetailsEntrepot"><!-- content will be loaded here --></div>

              </div>
            </div>
          </div>
        </div>
    </div>
    
      
    <div class="modal fade" id="addEntrepotModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Ajout d'entrepot</h4>

          </div>

          <div class="modal-body">
            <form name="formulaireAjoutEntrepot" method="post">

              <div class="form-group">

                  <label for="nomEntrepot" class="control-label">Nom entrepot</label>

                  <input type="text" maxLength="100" class="form-control" id="nomEntrepot" name="nomEntrepot" placeholder="Nom entrepot" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="adresse" class="control-label">Adresse</label>

                  <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse" required>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="typeEntrepot" class="control-label">Type (départ/arrivée)</label>

                  <select type="text" class="form-control" id="typeEntrepot" name="typeEntrepot">
                    <!-- <option value="0">----- choisir -----</option> -->
                    <option value="1">Départ</option>
                    <option value="2">Arrivée</option>
                  </select>

                  <span class="text-danger" ></span>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="submit" name="btnEnregistrerEntrepot" class="btn btn-primary">Enregistrer</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="editEntrepotModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Modification d'entrepot</h4>

          </div>

          <div class="modal-body">
            <form name="formulaireEditEntrepot" method="post">

              <input type="hidden" name="idEntrepot" id="idEntrepot_edit">

              <div class="form-group">

                  <label for="nomEntrepot_edit" class="control-label">Nom entrepot</label>

                  <input type="text" maxLength="100" class="form-control" id="nomEntrepot_edit" name="nomEntrepot" placeholder="Nom entrepot" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="adresse_edit" class="control-label">Adresse</label>

                  <input type="text" class="form-control" id="adresse_edit" name="adresse" placeholder="Adresse" required>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="typeEntrepot_edit" class="control-label">Type (départ/arrivée)</label>

                  <select type="text" class="form-control" id="typeEntrepot_edit" name="typeEntrepot">
                    <!-- <option value="0">----- choisir -----</option> -->
                    <option value="1">Départ</option>
                    <option value="2">Arrivée</option>
                  </select>

                  <span class="text-danger" ></span>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="submit" name="btnEnregistrerEntrepot" class="btn btn-primary">Enregistrer</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="deleteEntrepotModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Suppresion de container</h4>

          </div>

          <div class="modal-body">
            <div name="deleteEntrepot">
              <input type="hidden" id="idEntrepot_delete">
                <p><code>Êtes-vous sûr de vouloir supprimer l'entrepot <b><span id="nomEntrepotToDelete"></span></b> ?</code></p>
              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="button" name="btnDeleteEntrepot" onClick="confirmDeleteEntrepot()" class="btn btn-danger">Supprimer</button>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready( function () {
          $('#entrepotList').DataTable();
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