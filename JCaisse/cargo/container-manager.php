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
    $numBooking=htmlspecialchars(trim($_POST['numBooking']));
    $dateDepart=htmlspecialchars(trim($_POST['dateDepart']));
    $dateArrivee=htmlspecialchars(trim($_POST['dateArrivee']));
    $localPath = "./imagesCargo/";
    
    $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    try {
        // From this point and until the transaction is being committed every change to the database can be reverted
        $bdd->beginTransaction(); 

        $req4 = $bdd->prepare("INSERT INTO `".$nomtableContainer."` (numContainer,numBooking,dateDepart,dateArrivee,image,idUser)
            values (:numC,:numB,:dD,:dA,:pj,:idU)");
        $req4->execute(array(
                'numC' => $numContainer,
                'numB' => $numBooking,
                'dD' => $dateDepart,
                'dA' => $dateArrivee,
                'pj' => "",
                'idU' => $_SESSION['iduser']
            ))  or die(print_r($req4->errorInfo()));
          $req4->closeCursor();
          $idContainer= $bdd->lastInsertId();
          
        
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

                $uniqueName = time().''.$idContainer;
                // $uniqueName = uniqid('', true);
                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                $file = $uniqueName.".".$extension;
                //$file = 5f586bf96dcd38.73540086.jpg

                move_uploaded_file($tmpName, './imagesCargo/'.$file);
                
                $sqlU="UPDATE `".$nomtableContainer."` set image='".$file."' where idContainer='".$idContainer."'";
                
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

        echo $e;
    }

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
  

if (isset($_POST['btnUploadImg'])) {
  $idContainer=htmlspecialchars(trim($_POST['idContainer']));
  $localPath = "./imagesCargo/";

  if(@$_POST['image'] != '') {

      if (unlink($localPath.$_POST['image'])) {

      $fileNameNew='';

      $sql5="UPDATE `".$nomtableContainer."` set image='".$fileNameNew."' where idContainer=".$idContainer;		
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

          $uniqueName = time().''.$idContainer;
          // $uniqueName = uniqid('', true);
          //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
          $file = $uniqueName.".".$extension;
          //$file = 5f586bf96dcd38.73540086.jpg

          move_uploaded_file($tmpName, './imagesCargo/'.$file);
            
          $sql2="UPDATE `".$nomtableContainer."` set image='".$file."' where idContainer='".$idContainer."' ";
          $res2=mysql_query($sql2) or die ("modification image avion impossible =>".mysql_error());
          
      }
      // else {
      //     echo "Une erreur est survenue";
      // }
  }
}

if (isset($_POST['btnSupImg'])) {
  $idContainer=htmlspecialchars(trim($_POST['idContainer']));
  $localPath = "./imagesCargo/";

  if($_POST['image'] != '') {

       if (unlink($localPath.$_POST['image'])) {

        $fileNameNew='';

        $sql5="UPDATE `".$nomtableContainer."` set image='".$fileNameNew."' where idContainer=".$idContainer;		
        $res5=@mysql_query($sql5)or die ("modification impossible   ".mysql_error());

       }

   } else {

         echo " ";

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
</style>  
 
  <?php if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) { ?> 
    <div class="row" align="center">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addContainerModal"> <span class="glyphicon glyphicon-plus"> Ajouter un container</span></button>
    </div> 
  <?php
    }
  ?> 

    <div class="cycle-tab-container container-fluid">
      <ul class="nav nav-tabs"> 
        <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#containerCbm">Liste des containers</a>
        </li>
       <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivage">Enregistrer un arrivage</a>
        </li> -->
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
                    <th>Numéro BL</th><!-- Numéro booking -->
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
                    <td id="tdBtn<?= $key['idContainer']?>">
                      <?php 
                        if ($key['etat']==2) {
                            
                      ?>
                      <?php 
                        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['caissier']==1) {
                            
                      ?>
                      <a class="btn btn-xs btn-default" id="paiementBtn1<?= $key['idContainer']?>" onClick="detailContainer('<?= $key['idContainer']?>','<?= $key['numContainer']?>','1')"><span class="glyphicon glyphicon-remove"> Non Payer</span> </a> <!-- 1 : pour ce qui n'ont pas retirés leurs bagages -->
                      <a class="btn btn-xs btn-info" id="paiementBtn2<?= $key['idContainer']?>" onClick="detailContainer('<?= $key['idContainer']?>','<?= $key['numContainer']?>','2')"><span class="glyphicon glyphicon-plus"> Avance</span> </a> <!-- 2 : pour ce qui ont retirés leurs bagages en donnant une avance -->
                      <a class="btn btn-xs btn-success" id="paiementBtn3<?= $key['idContainer']?>" onClick="detailContainer('<?= $key['idContainer']?>','<?= $key['numContainer']?>','3')"><span class="glyphicon glyphicon-ok"> Payer</span> </a> <!-- 3 : pour ce qui ont retirés leurs bagages en payant le tout -->
                    <?php 
                      }                        
                      } else {
                    ?>
                    <?php 
                        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['gestionnaire']==1) {
                            
                      ?>
                      <a class="btn btn-xs btn-warning" <?= (sizeof($enregexist)>0) ? 'onClick="fermerPorteur('.$key['idContainer'].',1)"' : 'disabled' ; ?> id="fermerPorteur<?= $key['idContainer']?>" <?= ($key['etat']==1 || $key['etat']==2) ? 'style=display:none' : '' ;?>><span class="glyphicon glyphicon-close"> Fermer</span> </a> <!-- 1 : pour fermer le container après le chargement des bagages -->
                      <a class="btn btn-xs btn-warning" id="ouvrirPorteur<?= $key['idContainer']?>" onClick="ouvrirPorteur('<?= $key['idContainer']?>','1')" <?= ($key['etat']==0 || $key['etat']==2) ? 'style=display:none' : '' ;?>><span class="glyphicon glyphicon-close"> Ouvrir</span> </a> <!-- 1 : pour fermer le container après le chargement des bagages -->
                      <?php 
                        }
                        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['caissier']==1) {
                            
                      ?>
                        <a class="btn btn-xs btn-primary" id="arriveePorteur<?= $key['idContainer']?>" onClick="arriveePorteur('<?= $key['idContainer']?>','<?= $key['numContainer']?>','1')" <?= ($key['etat']==2) ? 'disabled' : '' ;?> <?= ($key['etat']==0) ? 'style=display:none' : '' ;?>><span class="glyphicon glyphicon-plus"> Arrivée</span> </a> <!-- 2 : pour mettre à jour le trajet du container -->
                     <?php 

                      } 
                    ?>
                    <?php 
                      }
                    ?>
                    <td>
                      <a <?= ($key['image']=='' || $key['image']==null) ? "hidden='true'" : "" ; ?>><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_Container(<?= $key['idContainer'];?>)"/></a>
                      <a <?= ($key['image']!=='' && $key['image']!=null) ? "hidden='true'" : "" ; ?>><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNew_Container(<?= $key['idContainer'];?>)"/></a>                    </td>
                    </td>
                    <!-- <td><a class="btn btn-xs btn-default" onClick="detailContainer('<?= $key['idContainer']?>','<?= $key['numContainer']?>','1')"><span class="glyphicon glyphicon-remove"> Non Payer</span> </a>
                      <a class="btn btn-xs btn-info" onClick="detailContainer('<?= $key['idContainer']?>','<?= $key['numContainer']?>','2')"><span class="glyphicon glyphicon-plus"> Avance</span> </a>
                      <a class="btn btn-xs btn-success" onClick="detailContainer('<?= $key['idContainer']?>','<?= $key['numContainer']?>','3')"><span class="glyphicon glyphicon-ok"> Payer</span> </a> 
                    </td> -->
                    <td>
                      <a class="btn btn-xs btn-default" onClick="detailGlobalContainer('<?= $key['idContainer']?>','<?= $key['numContainer']?>')"><span class="glyphicon glyphicon-plus"></span> </a>
                      <?php 
                        if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
                            
                      ?>
                      <a class="btn btn-xs btn-info" onClick="editContainer('<?= $key['idContainer']?>')"><span class="glyphicon glyphicon-edit"></span> </a>
                      <a class="btn btn-xs btn-danger" <?= (sizeof($enregexist)>0) ? 'disabled' : 'onClick="deleteContainer('.$key['idContainer'].')"' ; ?> ><span class="glyphicon glyphicon-remove"></span> </a>
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
      
    
    <div class="modal fade" id="detailContainerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Détails des enregistrements du container : <span id="numContainerDetail"></span></h4>
                <!-- <h4 class="modal-title" align="center"> Etat : <span id="etatContainerDetail"> </span></h4> -->

            </div>

            <div class="modal-body">
              
              <div class="">
                <ul class="nav nav-tabs">
                  <input type="hidden" id="idContainerHidden">
                  <!-- <li class="cycle-tab-item active">
                    <a class="nav-link" role="tab" data-toggle="tab" href="#containerCbm">Détails des arrivages par container</a>
                  </li> -->
                  <li class="cycle-tab-item active">
                    <a class="nav-link" role="tab" data-toggle="tab" href="#record">Bagages</a>
                  </li>
                  <li class="cycle-tab-item" id="depenseItem">
                    <a class="nav-link" role="tab" data-toggle="tab" href="#depenses">Dépenses</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane fade active in" id="record" role="tabpanel" aria-labelledby="record-tab">
                    <div class="table-responsive">

                      <div id="resultsDetailsContainer"><!-- content will be loaded here --></div>

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
    
      
    <div class="modal fade" id="addContainerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title" id="myModalLabel">Ajout de container</h4>

          </div>

          <div class="modal-body">
            <form name="formulaireAjoutContainer" method="post" enctype="multipart/form-data">

              <div class="form-group">

                  <label for="numContainer" class="control-label">Numéro container</label>

                  <input type="text" maxLength="100" class="form-control" id="numContainer" name="numContainer" placeholder="Numéro container" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>
              
              <div class="form-group">

                  <label for="numBooking" class="control-label">Numéro BL</label>

                  <input type="text" maxLength="100" class="form-control" id="numBooking" name="numBooking" placeholder="Numéro BL" required="" autofocus>

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

                  <label for="numContainer_edit" class="control-label">Numéro container</label>

                  <input type="text" maxLength="100" class="form-control" id="numContainer_edit" name="numContainer_edit" placeholder="Numéro container" required="" autofocus>

                  <span class="text-danger" ></span>

              </div>

              <div class="form-group">

                  <label for="numBooking_edit" class="control-label">Numéro BL</label>

                  <input type="text" maxLength="100" class="form-control" id="numBooking_edit" name="numBooking_edit" placeholder="Numéro BL" required="">

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

                <button type="button" name="btnEditerContainer" onClick="confirmEditContainer()" class="btn btn-primary">Enregistrer</button>

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

    
    <div id="imageNvContainer"  class="modal fade " role="dialog">
      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header" style="padding:35px 50px;">

            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>

          </div>

          <div class="modal-body" style="padding:40px 50px;">

              <form   method="post" enctype="multipart/form-data">

                  <input type="hidden" name="idContainer" id="id_Upd_ND" />

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
    <div id="imageExContainer"  class="modal fade " role="dialog">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header" style="">

            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>aperçu/Modification </b></h4>

          </div>

          <div class="modal-body" style="">

            <img  width="50%" height="30%" alt="" src="" id="imgsrc_Upd" />

            <form   method="post" enctype="multipart/form-data">

                <input  type="hidden" name="idContainer" id="id_Upd_Ex" />

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
              // clearInterval(tabCycle);
              $(this).tab('show')
              // tabCycle = setInterval(tabChange, 5000);
          });
      });


    </script>