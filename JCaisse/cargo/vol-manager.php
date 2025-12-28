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

  
  $nomtableAvion = $_SESSION['nomB'].'-avion';

  if (isset($_POST['btnEnregistrerVol'])) {
    
    $numVol=htmlspecialchars(trim($_POST['numVol']));
    $numBooking=htmlspecialchars(trim($_POST['numBooking']));
    $nombrePieces=htmlspecialchars(trim($_POST['nombrePieces']));
    $dateDepart=htmlspecialchars(trim($_POST['dateDepart']));
    $dateArrivee=htmlspecialchars(trim($_POST['dateArrivee']));
    $localPath = "./imagesCargo/";
    
    $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction(); 

        $req4 = $bdd->prepare("INSERT INTO `".$nomtableAvion."` (numVol,numBooking,nombrePieces,dateDepart,dateArrivee,image,idUser)
            values (:numV,:numB,:nbP,:dD,:dA,:pj,:idU)");
        $req4->execute(array(
                'numV' => $numVol,
                'numB' => $numBooking,
                'nbP' => $nombrePieces,
                'dD' => $dateDepart,
                'dA' => $dateArrivee,
                'pj' => "",
                'idU' => $_SESSION['iduser']
            ))  or die(print_r("Insert avion 1 ".$req4->errorInfo()));
            $req4->closeCursor();
          $idAvion= $bdd->lastInsertId();

        if(isset($_FILES['file'])){
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
        $maxSize = 400000;

        if (in_array($extension, $extensions) && $error == 0) {

            $uniqueName = time().''.$idAvion;
            // $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './imagesCargo/'.$file);
            
            $sqlU="UPDATE `".$nomtableAvion."` set image='".$file."' where idAvion='".$idAvion."'";
            
            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(print_r($statementU->errorInfo(),true));
            
        }
        // else {
        //     echo "Une erreur est survenue";
        // }
    } 
    
    // Make the changes to the database permanent
        $bdd->commit();
    }
    catch ( PDOException $e ) { 
        // Failed to insert the order into the database so we rollback any changes
        $bdd->rollback();
        throw $e;

        // echo '0';
    }

}

if (isset($_POST['btnUploadImg'])) {
  $idAvion=htmlspecialchars(trim($_POST['idAvion']));
  $localPath = "./imagesCargo/";

  if(@$_POST['image'] != '') {

      if (unlink($localPath.$_POST['image'])) {

      $fileNameNew='';

      $sql5="UPDATE `".$nomtableAvion."` set image='".$fileNameNew."' where idAvion=".$idAvion;		
      $res5=@mysql_query($sql5)or die ("modification impossible   ".mysql_error());

      }

  }
  if(isset($_FILES['file'])){
      $tmpName = $_FILES['file']['tmp_name'];
      $name = $_FILES['file']['name'];
      $size = $_FILES['file']['size'];
      $error = $_FILES['file']['error'];

      $tabExtension = explode('.', $name);
      $extension = strtolower(end($tabExtension));

      $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
      $maxSize = 400000;

      if (in_array($extension, $extensions) && $error == 0) {

          $uniqueName = time().''.$idAvion;
          // $uniqueName = uniqid('', true);
          //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
          $file = $uniqueName.".".$extension;
          //$file = 5f586bf96dcd38.73540086.jpg

          move_uploaded_file($tmpName, './imagesCargo/'.$file);
            
          $sql2="UPDATE `".$nomtableAvion."` set image='".$file."' where idAvion='".$idAvion."' ";
          $res2=mysql_query($sql2) or die ("modification image avion impossible =>".mysql_error());
          
      }
      // else {
      //     echo "Une erreur est survenue";
      // }
  }
}

if (isset($_POST['btnSupImg'])) {
  $idAvion=htmlspecialchars(trim($_POST['idAvion']));
  $localPath = "./imagesCargo/";

  if($_POST['image'] != '') {

       if (unlink($localPath.$_POST['image'])) {

        $fileNameNew='';

        $sql5="UPDATE `".$nomtableAvion."` set image='".$fileNameNew."' where idAvion=".$idAvion;		
        $res5=@mysql_query($sql5)or die ("modification impossible   ".mysql_error());

       }

   } else {

         echo " ";

   }
}

  // $sql="SELECT * FROM `".$nomtableContainer."`";
    
  // $statement = $bdd->prepare($sql);
  // $statement->execute();
  // $containers = $statement->fetchAll(PDO::FETCH_ASSOC); 

  // foreach ($containers as $key) {
      
  //   $sql="UPDATE `".$nomtablePagnet."` SET numVol='".$key['idAvion']."' where numVol='".$key['numVol']."'";
      
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
    <?php 
      if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
    ?>
      <div class="row" align="center">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addVolModal"> <span class="glyphicon glyphicon-plus"> Ajouter un vol</span></button>
      </div>
    <?php 
     }
    ?>

    <div class="cycle-tab-container container-fluid">
      <ul class="nav nav-tabs"> 
        <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#containerCbm">Liste des vols</a>
        </li>
       <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivage">Enregistrer un arrivage</a>
        </li> -->
         <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#settings">settings</a>
        </li> -->
      </ul>
        <div class="tab-content">
        <div class="tab-pane fade active in" id="vol" role="tabpanel" aria-labelledby="vol-tab">
          <table id="volList" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Numéro vol</th>
                    <th>Numéro suivi</th>
                    <th>Nombre de pièces</th>
                    <th>Date départ</th>
                    <th>Date arrivée</th>
                    <th>Etat</th>
                    <th>Suivi</th>
                    <th>Pièce jointe</th>
                    <th>Opération</th>
                </tr>
            </thead>
            <tbody>
              <?php 
                $sql="SELECT * FROM `".$nomtableAvion."` where retirer=0 ORDER BY idAvion DESC";
                
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
                    <td><?php
                      if ($key['etat']==1) {
                    ?>
                      <b>Fermé</b>
                    <?php
                      } else if ($key['etat']==2) {
                    ?>
                      <b>Arrivé</b>
                    <?php
                      } else {
                    
                    ?>
                      <b>Ouvert</b>
                    <?php
                      } 
                    
                    ?>
                    </td>
                    <td id="tdBtn<?= $key['idAvion']?>">
                      <?php 
                          if ($key['etat']==2) {
                            
                      ?>
                      <?php 
                        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['caissier']==1) {
                            
                      ?>
                      <a class="btn btn-xs btn-default" id="paiementBtn1<?= $key['idAvion']?>" onClick="detailVol('<?= $key['idAvion']?>','<?= $key['numVol']?>','1')"><span class="glyphicon glyphicon-remove"> Non Payer</span> </a> <!-- 1 : pour ce qui n'ont pas retirés leurs bagages -->
                      <a class="btn btn-xs btn-info" id="paiementBtn2<?= $key['idAvion']?>" onClick="detailVol('<?= $key['idAvion']?>','<?= $key['numVol']?>','2')"><span class="glyphicon glyphicon-plus"> Avance</span> </a> <!-- 2 : pour ce qui ont retirés leurs bagages en donnant une avance -->
                      <a class="btn btn-xs btn-success" id="paiementBtn3<?= $key['idAvion']?>" onClick="detailVol('<?= $key['idAvion']?>','<?= $key['numVol']?>','3')"><span class="glyphicon glyphicon-ok"> Payer</span> </a> <!-- 3 : pour ce qui ont retirés leurs bagages en payant le tout -->
                    <?php }
                        
                      } else {
                    ?>
                    <?php 
                        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['gestionnaire']==1) {
                            
                      ?>
                      <a class="btn btn-xs btn-warning" id="fermerPorteur<?= $key['idAvion']?>" <?= (sizeof($enregexist)>0) ? 'onClick="fermerPorteur('.$key['idAvion'].',2)"' : 'disabled' ; ?> <?= ($key['etat']==1 || $key['etat']==2) ? 'style=display:none' : '' ;?>><span class="glyphicon glyphicon-close"> Fermer</span> </a> <!-- 1 : pour fermer le container après le chargement des bagages -->
                      <a class="btn btn-xs btn-warning" id="ouvrirPorteur<?= $key['idAvion']?>" onClick="ouvrirPorteur('<?= $key['idAvion']?>','2')" <?= ($key['etat']==0 || $key['etat']==2) ? 'style=display:none' : '' ;?>><span class="glyphicon glyphicon-close"> Ouvrir</span> </a> <!-- 1 : pour fermer le container après le chargement des bagages -->
                      <?php 
                        }
                        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['caissier']==1) {
                            
                      ?>
                      <a class="btn btn-xs btn-primary" id="arriveePorteur<?= $key['idAvion']?>" onClick="arriveePorteur('<?= $key['idAvion']?>','<?= $key['numVol']?>','2')" <?= ($key['etat']==2) ? 'disabled' : '' ;?> <?= ($key['etat']==0) ? 'style=display:none' : '' ;?>><span class="glyphicon glyphicon-plus"> Arrivée</span> </a> <!-- 2 : pour mettre à jour le trajet du container -->
                    <?php 
                        }
                      }
                    ?>
                    </td>
                    <td>                    
                      <a <?= ($key['image']=='' || $key['image']==null) ? "hidden='true'" : "" ; ?>><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_Avion(<?= $key['idAvion'];?>)"/></a>
                      <a <?= ($key['image']!=='' && $key['image']!=null) ? "hidden='true'" : "" ; ?>><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNew_Avion(<?= $key['idAvion'];?>)"/></a>
                    </td>
                    <td>
                      <a class="btn btn-xs btn-default" onClick="detailGlobalVol('<?= $key['idAvion']?>','<?= $key['numVol']?>')"><span class="glyphicon glyphicon-plus"></span> </a>
                      <?php 
                        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
                            
                      ?>
                      <a class="btn btn-xs btn-info" onClick="editVol('<?= $key['idAvion']?>')"><span class="glyphicon glyphicon-edit"></span> </a>
                      <a class="btn btn-xs btn-danger" <?= (sizeof($enregexist)>0) ? 'disabled' : 'onClick="deleteVol('.$key['idAvion'].')"' ; ?> ><span class="glyphicon glyphicon-remove"></span> </a>
                      <?php 
                        }
                      ?>
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
      
    
    <div class="modal fade" id="detailVolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Détails des enregistrements du vol : <span id="numVolDetail"></span></h4>
                <!-- <h4 class="modal-title" align="center"> Etat : <span id="etatContainerDetail"> </span></h4> -->

            </div>

            <div class="modal-body">
              <div class="table-responsive">

              <div class="">
                <ul class="nav nav-tabs">
                  <input type="hidden" id="idAvionHidden">
                  <!-- <li class="cycle-tab-item active">
                    <a class="nav-link" role="tab" data-toggle="tab" href="#containerCbm">Détails des arrivages par container</a>
                  </li> -->
                  <li class="cycle-tab-item active">
                    <a class="nav-link" role="tab" data-toggle="tab" href="#record">Bagages</a>
                  </li>
                  <li class="cycle-tab-item">
                    <a class="nav-link" role="tab" data-toggle="tab" href="#depenses">Dépenses</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane fade active in" id="record" role="tabpanel" aria-labelledby="record-tab">
                    <div class="table-responsive">

                      <div id="resultsDetailsVol"><!-- content will be loaded here --></div>

                    </div>
                  </div>
                  <div class="tab-pane fade" id="depenses" role="tabpanel" aria-labelledby="depenses-tab">
                    
                    <table class="table table-bordered table-responsive" id="tabDepense">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Description</th> 
                                <th>Montant</th>
                                <?php 
                                    if ($_SESSION['compte']==1) {
                                    ?>
                                <th>Compte</th>
                                <?php 
                                      }
                                    ?>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="depense">
                                <td>  
                                  <input type="text" id="libelle_depense" class="form-control" autocomplete="off">
                                  <input type="hidden" id="idDepense">
                                </td>
                                <td><input type="text" id="description" class="form-control" maxlength="50"></td>
                                <td><input type="number" id="montant_depense" min="0" class="form-control" placeholder="Montant"></td>
                                 <?php 
                                    if ($_SESSION['compte']==1) {
                                    ?>
                                <td>
                                  <select name="" id="compte_depense" class="form-control">
                                    <?php 
                                                    
                                      $sql="SELECT * FROM `".$nomtableCompte."` where idCompte<>2 and idCompte<>3 ORDER BY idCompte";
                                      
                                      $statement = $bdd->prepare($sql);
                                      $statement->execute();
                                      
                                      $comptes = $statement->fetchAll(PDO::FETCH_ASSOC); 

                                      foreach ($comptes as $key) {
                                        
                                    ?>
                                      <option value="<?= $key['idCompte']?>"><?= $key['nomCompte']?></option>                            
                                    <?php 
                                      }
                                    ?>
                                  </select>
                                </td>
                                <?php 
                                }
                                    ?>
                                <td align="center"><button type="button" class="btn btn-primary" id="btnValiderDepense" onClick="validerDepense(1)"><span class="glyphicon glyphicon-ok"></span></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="table-responsive">

                      <div id="resultsDepensesPorteur"><!-- content will be loaded here --></div>

                    </div>
                  </div>
                </div>
              </div>

              </div>
            </div>
          </div>
        </div>
    </div>
    
      
    <div class="modal fade" id="addVolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Ajout de vol</h4>

          </div>

          <div class="modal-body">
            <form name="formulaireAjoutContainer" method="post" enctype="multipart/form-data">

              <div class="form-group">

                  <label for="numVol" class="control-label">Numéro de vol</label>

                  <input type="text" maxLength="100" class="form-control" id="numVol" name="numVol" placeholder="Numéro de vol" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>
              
              <div class="form-group">

                <label for="numBooking" class="control-label">Numéro suivi</label>

                <input type="text" maxLength="100" class="form-control" id="numBooking" name="numBooking" placeholder="Numéro suivi" required="" autofocus>

                <span class="text-danger" ></span>

              </div>
              
              <div class="form-group">

                <label for="nombrePieces" class="control-label">Nombre de pièces</label>

                <input type="number" maxLength="100" class="form-control" id="nombrePieces" name="nombrePieces" placeholder="Nombre de pièces" required="" autofocus>

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
              
              <div class="form-group">

                <label for="pieceJointe" class="control-label">Pièce jointe</label>

                <input type="file" name="file" class="form-control"/>
                <span class="text-danger" ></span>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="submit" name="btnEnregistrerVol" class="btn btn-primary">Enregistrer</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="editVolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Modification de vol</h4>

          </div>

          <div class="modal-body">
            <div name="formulaireEditContainer">
              <input type="hidden" id="idAvion_edit">

              <div class="form-group">

                  <label for="numVol_edit" class="control-label">Numéro vol</label>

                  <input type="text" maxLength="100" class="form-control" id="numVol_edit" name="numVol_edit" placeholder="Numéro vol" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="numBooking_edit" class="control-label">Numéro Booking</label>

                  <input type="text" maxLength="100" class="form-control" id="numBooking_edit" name="numBooking_edit" placeholder="Numéro Booking" required="">

                  <span class="text-danger" ></span>

              </div>
              
              <div class="form-group">

                <label for="nombrePieces_edit" class="control-label">Nombre de pièces</label>

                <input type="number" maxLength="100" class="form-control" id="nombrePieces_edit" name="nombrePieces_edit" placeholder="Nombre de pièces" required="" autofocus>

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

                <button type="button" name="btnEditerVol" onClick="confirmEditVol()" class="btn btn-primary">Enregistrer</button>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <div class="modal fade" id="deleteVolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Suppresion de vol</h4>

          </div>

          <div class="modal-body">
            <div name="deleteVol">
              <input type="hidden" id="idAvion_delete">
                <p><code>Êtes-vous sûr de vouloir supprimer ce vol ?</code></p>
              <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                <button type="button" name="btnDeleteVol" onClick="confirmDeleteVol()" class="btn btn-danger">Supprimer</button>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- <div id="getImage"  class="modal fade " role="dialog">
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
    </div> -->
    
    <div id="imageNvAvion"  class="modal fade " role="dialog">
      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header" style="padding:35px 50px;">

            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>

          </div>

          <div class="modal-body" style="padding:40px 50px;">

              <form   method="post" enctype="multipart/form-data">

                  <input type="hidden" name="idAvion" id="id_Upd_ND" />

                  <div class="form-group" >

                  <br />

                    <input type="file" name="file" required/>

                  </div>

                  <div class="form-group" align="right">

                      <input type="submit" class="boutonbasic"  name="btnUploadImg" value="Enregister >>"/>

                  </div>

              </form>

          </div>

        </div>

      </div>
    </div>
    <div id="imageExAvion"  class="modal fade " role="dialog">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header" style="">

            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>aperçu/Modification </b></h4>

          </div>

          <div class="modal-body" style="">

            <img  width="50%" height="30%" alt="" src="" id="imgsrc_Upd" />

            <form   method="post" enctype="multipart/form-data">

                <input  type="hidden" name="idAvion" id="id_Upd_Ex" />

                <input  type="hidden" name="image" id="img_Upd_Ex" />

                <div class="form-group" >

                  <br />

                  <input type="file" name="file" />

                </div>

                <div class="form-group" align="right">

                    <input type="submit" class="boutonbasic"  name="btnSupImg" value="Suprimer >>"/>

                    <input type="submit" class="boutonbasic"  name="btnUploadImg" value="Modifier >>"/>

                </div>

            </form>

          </div>

        </div>

      </div>

    </div>

    <script>
      $(document).ready( function () {
          $('#volList').DataTable();
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
              // clearInterval(tabCycle);
              $(this).tab('show')
              // tabCycle = setInterval(tabChange, 5000);
          });
      });


    </script>