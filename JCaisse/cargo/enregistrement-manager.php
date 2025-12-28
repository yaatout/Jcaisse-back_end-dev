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

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header-cargo.php');


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
  $idEnregistrement=htmlspecialchars(trim($_POST['idEnregistrement']));
  $localPath = "./imagesCargo/";

  if(@$_POST['image'] != '') {

      if (unlink($localPath.$_POST['image'])) {

      $fileNameNew='';

      $sql5="UPDATE `".$nomtableEnregistrement."` set image='".$fileNameNew."' where idEnregistrement=".$idEnregistrement;		
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

          $uniqueName = time().''.$idEnregistrement;
          // $uniqueName = uniqid('', true);
          //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
          $file = $uniqueName.".".$extension;
          //$file = 5f586bf96dcd38.73540086.jpg

          move_uploaded_file($tmpName, './imagesCargo/'.$file);
            
          $sql2="UPDATE `".$nomtableEnregistrement."` set image='".$file."' where idEnregistrement='".$idEnregistrement."' ";
          $res2=mysql_query($sql2) or die ("modification image enregistrement impossible =>".mysql_error());
          
      }
      else {
          echo "Une erreur est survenue";
      }
  }
}
if (isset($_POST['btnSupImg'])) {
  $idEnregistrement=htmlspecialchars(trim($_POST['idEnregistrement']));
  $localPath = "./imagesCargo/";

  if($_POST['image'] != '') {

       if (unlink($localPath.$_POST['image'])) {

        $fileNameNew='';

        $sql5="UPDATE `".$nomtableEnregistrement."` set image='".$fileNameNew."' where idEnregistrement=".$idEnregistrement;		
        $res5=@mysql_query($sql5)or die ("modification impossible   ".mysql_error());

       }

   } else {

         echo " ";

   }
}

?>
<style>
  #tabCbm .typeahead {

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


    <?php
    
      if($mois==1){
          $annee_paie=$annee - 1;
      }
      else{
          $annee_paie=$annee;
      }


      $sql0 = "SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='" . $_SESSION['idBoutique'] . "' and YEAR(datePs)='" . $annee_paie . "' and MONTH(datePs)!='" . $mois . "' and aPayementBoutique=0 ";

      $res0 = mysql_query($sql0);
      $ps = mysql_fetch_array($res0);
      $idPS = @$ps['idPS'];
      $montantFixePayement = @$ps['montantFixePayement'];

      // var_dump($idPS);
      // var_dump($montantFixePayement);
    ?>
    <!-- <div class="row" align="center">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addContainerModal"> <span class="glyphicon glyphicon-plus"> Ajouter un container</span></button>
    </div> -->
    <?php 
    $blockedBtn = 0;
    
      if (mysql_num_rows($res0)) {
        if ($jour > 5) {
          $blockedBtn = 1;
        }

          if ($jour > 0) {
        ?>
          <div class="container-fluid" align="center">
            <h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span>
            &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-default" onclick='selectNbMoisPaiement(<?= $idPS; ?>,<?= $montantFixePayement; ?>)' style='text-decoration:underline;'>Payer <img src='images/Wave.png' width='25' height='25'></button>&nbsp;&nbsp;&nbsp;&nbsp;</h6>
          </div>
        <?php 
        }
      }
    ?>

    <div class="cycle-tab-container container-fluid">
      <ul class="nav nav-tabs">
        <!-- <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#containerCbm">Détails des arrivages par container</a>
        </li> -->
       <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivage">Enregistrement de bagages</a>
        </li>
      </ul>
        <div class="tab-content">
          <div class="tab-pane fade active in" id="arrivage" role="tabpanel" aria-labelledby="arrivage-tab">
            <!-- <div class="container"> -->
              
            <div class="col-lg-2" id="divAddArrivageEmplacement" style="width:100%">
              <label for=""><b>Emplacement</b> </label>
              <select class="form-control" name="" id="emplacement" onChange="changeEmplacement()">
                <option value="0">------ Choisir -------</option>
                <option value="3">Dêpot</option>
                <option value="1">Container</option>
                <option value="2">Avion</option>
              </select>
            </div> <br> <br> <br>
            <div class="col-lg-2" id="divAddArrivageForm" style="width:100%" hidden>
              <hr style="width:100%">
              <label for="" id="emplacementTxt"> N° ou Nom de l'emplacement</label>
              <input type="text" name="emplacementAutocomplete" id="emplacementAutocomplete" class="form-control emplacementChanged" autocomplete="off">
              <!-- <br><br> -->
              <hr style="width:100%"> 
            </div><br><br>
            <table class="table table-bordered table-responsive" id="tabCbm">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Nature bagages</th> 
                        <th>Nombre CBM / KG</th>
                        <th>Destination</th>
                        <!-- <th>BAL</th> -->
                        <th>Nombre de pièces</th>
                        <th>Opération</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="record">
                        <input type="hidden" id="inputCBMHidden" class="form-control">
                        <td><input type="text" id="client_cbm_fret" class="form-control" autocomplete="off"></td>
                        <td id="natTd"><input type="text" id="nature_bagages" class="form-control" autocomplete="off"></td>
                        <td><input type="number" step="0.1" id="qty_cbm_fret" min="1" class="form-control" value="0.0"></td>
                        <!-- <td><input type="number" id="prix_cbm_fret" min="1" class="form-control" value="0"></td> -->
                        <td>
                          <select name="" id="destination" class="form-control">
                            <option value="Container">Container</option>
                            <option value="Avion">Avion</option>
                          </select>
                        </td>
                        <!-- <td><input type="number" step="0.1" id="qty_bal" class="form-control" value="0"></td> -->
                        <td><input type="number" id="nbPcs" min="1" class="form-control" value="1"></td>
                        <!-- <td align="center">127.000</td> -->
                        <td align="center"><button type="button" <?= ($blockedBtn == 1) ? "disabled=true" : "" ; ?> class="btn btn-primary" id="btnValiderCbm" onClick="validerEnregistrement()"><span class="glyphicon glyphicon-ok"></span></button></td>
                    </tr>
                </tbody>
            </table>
            <hr style="width:100%">
            <h2>Liste des enregistrements</h2>
            <div class="cycle-tab-container container-fluid">
              <ul class="nav nav-tabs">
                <!-- <li class="cycle-tab-item active">
                  <a class="nav-link" role="tab" data-toggle="tab" href="#containerCbm">Détails des arrivages par container</a>
                </li> -->
                <li class="cycle-tab-item active">
                  <a class="nav-link" role="tab" data-toggle="tab" href="#nonCharges">Non chargés</a>
                </li>
                <li class="cycle-tab-item">
                  <a class="nav-link" role="tab" data-toggle="tab" href="#charges">Chargés</a>
                </li>
              </ul>
              <div class="tab-content"> 
                <div class="tab-pane fade active in" id="nonCharges" role="tabpanel" aria-labelledby="nonCharges-tab">
                  <table id="listeEnregistrement" class="table table-striped" style="width:100%;background-color:white;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nom du Client</th>
                            <th>Date Enreg.</th>
                            <!-- <th>Heure Enreg.</th> -->
                            <th>Nb. CBM / KG</th>
                            <th>Nb pcs</th>
                            <!-- <th>Nb pcs initial</th> -->
                            <!-- <th>Nb pcs restant</th> -->
                            <th>Nat. bagages</th>
                            <th>Entrepot</th>
                            <th>Destination</th>
                            <th>Empl. Charger</th>
                            <!-- <th>Nb Pcs à Charger</th> -->
                            <th>Opération</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $sql="SELECT * FROM `".$nomtableEnregistrement."` where retirer=0 and idAvion=0 and idContainer=0 ORDER BY idEnregistrement DESC";
                        // $sql="SELECT * FROM `".$nomtableEnregistrement."` where retirer=0 and etat=1 ORDER BY idEnregistrement DESC";
                        // $sql="SELECT * FROM `".$nomtableEnregistrement."` where retirer=0 and idEntrepot<>0 and idAvion=0 and idContainer=0 ORDER BY idEnregistrement DESC";
                        
                        $statement = $bdd->prepare($sql);
                        $statement->execute();
                        
                        $enregistrements = $statement->fetchAll(PDO::FETCH_ASSOC); 
                        $i=1;
                        foreach ($enregistrements as $key) {
                          
                          $sqlC="SELECT * FROM `".$nomtableClient."` where idClient='".$key['idClient']."'";
                          
                          $statementC = $bdd->prepare($sqlC);
                          $statementC->execute();                  
                          $client = $statementC->fetch();
                            
                          $sqlE="SELECT * FROM `".$nomtableEntrepot."` where idEntrepot='".$key['idEntrepot']."'";
                          
                          $statementE = $bdd->prepare($sqlE);
                          $statementE->execute();                  
                          $entrepot = $statementE->fetch();
                            
                          // $sqlCh="SELECT SUM(nbPiecesCharger) as totalPcsCharger FROM `".$nomtableChargement."` where retirer=0 and idEnregistrement='".$key['idEnregistrement']."'";
                          
                          // $statementCh = $bdd->prepare($sqlCh);
                          // $statementCh->execute();                  
                          // $ch = $statementCh->fetch();
                          // $restantPcs = $key['nbPieces']-$ch['totalPcsCharger'];
                      ?>
                        <tr <?= ($key['etat']==2) ? "style='background-color:#DDFCD0;'" : "" ; ?> id="tr<?= $key['idEnregistrement']?>">
                            <td><?= $i; ?></td>
                            <td><?= $client['prenom'].' '.$client['nom']?></td>
                            <td><?= $key['dateEnregistrement'] ." ".$key['heureEnregistrement']?></td>
                            <!-- <td><?= $key['heureEnregistrement']?></td> -->
                            <td id="nbcf<?= $key['idEnregistrement']?>"><?= $key['quantite_cbm_fret']?></td>
                            <td id="nbp<?= $key['idEnregistrement']?>"><?= $key['nbPieces']?></td>
                            <!-- <td id="restantPcs<?= $key['idEnregistrement']?>"><?= $restantPcs?><input type="hidden" id="pcsRestant<?= $key['idEnregistrement']?>" value="<?= $restantPcs?>"></td> -->
                            <td id="natb<?= $key['idEnregistrement']?>"><?= $key['natureBagage']?></td>
                            <td><?= ($entrepot['nomEntrepot']) ? $entrepot['nomEntrepot'] : 'Néant';?></td>
                            <td id="dest<?= $key['idEnregistrement']?>"><?= $key['destination']?></td>
                            <td>
                              <form class="form-inline">
                                <div class="form-group">
                                  <input type="text" id="emplChargerAutocomplete<?= $key['idEnregistrement']?>" class="form-control emplChargerAutocomplete col-lg-2" autocomplet="off" placeholder="N° Container ou Vol" <?= ($key['idContainer']!=0 && $key['idAvion']!=0) ? "disabled='true'" : "" ; ?> style="width: 150px;">
                                </div>
                                <div class="form-group">
                                  <input type="text" id="prixChargement<?= $key['idEnregistrement']?>" class="form-control col-lg-2" value="<?= $key['prix_cbm_fret']?>" placeholder="Prix chargement" <?= ($key['idContainer']!=0 && $key['idAvion']!=0) ? "disabled='true'" : "" ; ?> style="width: 140px;">

                                </div>
                                <button type="button" class="btn btn-success" id="btnChargementBagages" onClick="chargementBagages(<?= $key['idEnregistrement']?>)" <?= ($key['idContainer']!=0 && $key['idAvion']!=0) ? "disabled='true'" : "" ; ?> ><span class="glyphicon glyphicon-ok"></span></button>
                              </form>
                            </td>
                            <!-- <td><input type="number" id="nbPcsCharger<?= $key['idEnregistrement']?>" class="form-control" placeholder="Nombre de pièces" value="<?= $restantPcs?>" <?= ($key['etat']>1) ? "disabled='true'" : "" ; ?> ></td> -->
                            <td>
                              <div class="btn-group" role="group" aria-label="operation">
                                  <form action="./cargo/barcodeCBM.php" target="_blank" method="post">
                                      <input type="hidden" name="idEnreg" value="<?= $key['idEnregistrement'];?>">
                                      <input type="hidden" name="codeBarrePcsInContainer" value="<?= $key['codeBarre'];?>">
                                      <input type="hidden" name="nbPcsInContainer" value="<?= $key['nbPieces'];?>">
                                      <input type="hidden" name="numContainer" value="<?= $key['idContainer'];?>">
                                      <input type="hidden" name="numVol" value="<?= $key['idAvion'];?>">
                                      <input type="hidden" name="idClient" value="<?= $key['idClient'];?>">
                                      <button type="submit" class="btn btn-secondary"><span class="glyphicon glyphicon-barcode"></span></button>
                                      <a <?= ($key['image']=='') ? "hidden='true'" : "" ; ?>><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_Enreg(<?= $key['idEnregistrement'];?>)"/></a>
                                      <a <?= ($key['image']!=='') ? "hidden='true'" : "" ; ?>><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNew_Enreg(<?= $key['idEnregistrement'];?>)"/></a>
                                      <button type="button" class="btn btn-info" id="btnEditEnreg" onClick="editEnregistrement(<?= $key['idEnregistrement']?>)" <?= ($key['etat']!=1) ? "disabled='true'" : "" ; ?>><span class="glyphicon glyphicon-edit"></span></button>
                                      <button type="button" class="btn btn-danger" id="btnDeleteEnreg" onClick="deleteEnregistrement(<?= $key['idEnregistrement']?>)" <?= ($key['etat']!=1) ? "disabled='true'" : "" ; ?>><span class="glyphicon glyphicon-trash"></span></button>
                                  </form>
                              </div> 
                            </td>
                        </tr>
                      <?php 
                          $i++;
                        }
                      ?>
                    </tbody>
                  </table> 
                </div>
                <div class="tab-pane fade" id="charges" role="tabpanel" aria-labelledby="charges-tab">
                  <table id="listeEnregistrement2" class="table table-striped" style="width:100%;background-color:white;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nom du Client</th>
                            <th>Date Enreg.</th>
                            <!-- <th>Heure Enreg.</th> -->
                            <th>Nb. CBM / KG</th>
                            <th>Nb pcs</th>
                            <!-- <th>Nb pcs initial</th> -->
                            <!-- <th>Nb pcs restant</th> -->
                            <th>Nat. bagages</th>
                            <th>Entrepot</th>
                            <th>Prix CBM/KG</th>
                            <th>Prix Total</th>
                            <th>Opération</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $sql="SELECT * FROM `".$nomtableEnregistrement."` where retirer=0 and (idAvion<>0 or idContainer<>0 ) ORDER BY idEnregistrement DESC";
                        // $sql="SELECT * FROM `".$nomtableEnregistrement."` where retirer=0 and etat=1 ORDER BY idEnregistrement DESC";
                        // $sql="SELECT * FROM `".$nomtableEnregistrement."` where retirer=0 and idEntrepot<>0 and idAvion=0 and idContainer=0 ORDER BY idEnregistrement DESC";
                        
                        $statement = $bdd->prepare($sql);
                        $statement->execute();
                        
                        $enregistrements = $statement->fetchAll(PDO::FETCH_ASSOC); 
                        $i=1;
                        foreach ($enregistrements as $key) {
                          
                          $sqlC="SELECT * FROM `".$nomtableClient."` where idClient='".$key['idClient']."'";
                          
                          $statementC = $bdd->prepare($sqlC);
                          $statementC->execute();                  
                          $client = $statementC->fetch();
                            
                          $sqlE="SELECT * FROM `".$nomtableEntrepot."` where idEntrepot='".$key['idEntrepot']."'";
                          
                          $statementE = $bdd->prepare($sqlE);
                          $statementE->execute();                  
                          $entrepot = $statementE->fetch();
                          
                          if ($key['idContainer']!=0) {
                            
                            $sqlP="SELECT *, numContainer as numPorteur FROM `".$nomtableContainer."` where idContainer='".$key['idContainer']."'";
                          
                          } else if ($key['idAvion']!=0) {
                            $sqlP="SELECT *, numVol as numPorteur  FROM `".$nomtableAvion."` where idAvion='".$key['idAvion']."'";

                          }
                          $statementP = $bdd->prepare($sqlP);
                          $statementP->execute();                  
                          $porteur = $statementP->fetch();
                          
                            
                          // $sqlCh="SELECT SUM(nbPiecesCharger) as totalPcsCharger FROM `".$nomtableChargement."` where retirer=0 and idEnregistrement='".$key['idEnregistrement']."'";
                          
                          // $statementCh = $bdd->prepare($sqlCh);
                          // $statementCh->execute();                  
                          // $ch = $statementCh->fetch();
                          // $restantPcs = $key['nbPieces']-$ch['totalPcsCharger'];
                      ?>
                        <tr <?= ($key['etat']==2) ? "style='background-color:#DDFCD0;'" : "" ; ?> id="tr<?= $key['idEnregistrement']?>">
                            <td><?= $i; ?></td>
                            <td><?= $client['prenom'].' '.$client['nom']?></td>
                            <td><?= $key['dateEnregistrement'] ." ".$key['heureEnregistrement']?></td>
                            <!-- <td><?= $key['heureEnregistrement']?></td> -->
                            <td id="nbcf<?= $key['idEnregistrement']?>"><?= $key['quantite_cbm_fret']?></td>
                            <td id="nbp<?= $key['idEnregistrement']?>"><?= $key['nbPieces']?></td>
                            <!-- <td id="restantPcs<?= $key['idEnregistrement']?>"><?= $restantPcs?><input type="hidden" id="pcsRestant<?= $key['idEnregistrement']?>" value="<?= $restantPcs?>"></td> -->
                            <td id="natb<?= $key['idEnregistrement']?>"><?= $key['natureBagage']?></td>
                            <td><?= ($entrepot['nomEntrepot']) ? $entrepot['nomEntrepot'] : 'Néant';?></td>
                            <td>
                              <form class="form-inline" style="display:block">
                                <div class="form-group">
                                  <b><code><?= $porteur['numPorteur']?></code></b>&ensp;
                                </div>
                                <div class="form-group">
                                  <input type="text" id="prixChargement<?= $key['idEnregistrement']?>" class="form-control col-lg-2" value="<?= $key['prix_cbm_fret']?>" placeholder="Prix chargement" style="width: 140px;">
                                </div>
                                <button type="button" class="btn btn-success" id="btnChargementBagages" onClick="setchargementPrix(<?= $key['idEnregistrement']?>)"><span class="glyphicon glyphicon-ok"></span></button>
                              </form>
                            </td>
                            <td><?= number_format($key['prix_cbm_fret']*$key['quantite_cbm_fret'], 2, ',', ' ')?></td>
                            <!-- <td><input type="number" id="nbPcsCharger<?= $key['idEnregistrement']?>" class="form-control" placeholder="Nombre de pièces" value="<?= $restantPcs?>" <?= ($key['etat']!=1) ? "disabled='true'" : "" ; ?> ></td> -->
                            <td>
                              <div class="btn-group" role="group" aria-label="operation">
                                <button type="button" class="btn btn-danger btn-sm" id="btnAnnulerChargement" onClick="annulerChargement(<?= $key['idEnregistrement']?>)"><span class="glyphicon glyphicon-remove"></span> Annuler</button>
                                <button type="button" class="btn btn-info btn-sm" id="btnEditEnreg" onClick="editEnregistrement(<?= $key['idEnregistrement']?>)"><span class="glyphicon glyphicon-edit"></span></button>
                                <!-- <button type="button" class="btn btn-success" id="btnChargementBagages" onClick="chargementBagages(<?= $key['idEnregistrement']?>)"><span class="glyphicon glyphicon-upload"></span></button> -->
                              </div> 
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
          </div>
        <!-- <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab"> Literally wolf flexitarian snackwave raw denim bitters ut synth kombucha consequat twee polaroid.</div> -->
      </div>
    </div>
      
    <div class="modal fade" id="chargementBagagesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Chargement de bagages</h4>

            </div> 

            <div class="modal-body">  
            <form name="formulaireChargerBagaes" method="post">

              <input type="text" id="idEnregistrementHide" hidden>
              <div class="form-group">
                <label for="emplacementCharger">Emplacement</label>
              <select name="emplacementCharger" id="emplacementCharger" class="form-control">
                <option value="0">---- Choisir ----</option>
                <option value="1">Container</option>
                <option value="2">Avion</option>
              </select>
              </div>
              <div class="form-group">
                <label for="emplacementAutocomplete2">Numéro emplacement</label>
              <input type="text" name="emplacementAutocomplete2" id="emplacementAutocomplete2"  class="form-control" autocomplete="off">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <button type="submit" name="btnChargerBagages" class="btn btn-primary">Valider</button>
              </div>
              
            </form>

            </div>
          </div>
        </div>
    </div>
      
    <div class="modal fade" id="detailEnregitrementModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Détails enregistrement : #<span id="numEnregistrementDetail"></span></h4>
                <!-- <h4 class="modal-title" align="center"> Etat : <span id="etatContainerDetail"> </span></h4> -->

            </div>

            <div class="modal-body">
              <div class="table-responsive">

                <!-- <input type="hidden" id="etat"> -->
                <input type="hidden" id="idEnregDetail">

                <!-- <label class="pull-left" for="nbEntreeEnreg">Nombre entrées </label>

                <select class="pull-left" id="nbEntreeEnreg">

                <optgroup>

                  <option value="10">10</option>

                  <option value="20">20</option>

                  <option value="50">50</option> 

                  <option value="100">100</option> 

                </optgroup>       

                </select>

                <input class="pull-right" type="text" name="" id="searchInputEnreg" placeholder="Rechercher..."> -->

                <div id="resultsDetailsEnreg"><!-- content will be loaded here --></div>

              </div>
            </div>
        </div>
      </div>
    </div>
      
    <div class="modal fade" id="editEnregModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Modification</h4>

            </div> 

            <div class="modal-body">  
            <form name="editEnregForm" method="post">

              <input type="text" id="idEnregistrementHide" hidden>
              <div class="form-group">
                <label for="clientName">Nom du Client</label>
                <input type="text" name="" id="clientName" readonly  class="form-control">
              </div>
              <div class="form-group">
                <label for="nb_cbm_fret">Nombre de CBM / KG</label>
                <input type="number" step="0.1" name="nb_cbm_fret" id="nb_cbm_fret"  class="form-control">
              </div>              
              <div class="form-group">
                <label for="nb_pieces">Nombre de pièces</label>
                <input type="number" name="nb_pieces" id="nb_pieces"  class="form-control">
              </div>              
              <div class="form-group">
                <label for="nat_bagages">Nature des bagages</label>
                <input type="text" name="nat_bagages" id="nat_bagages"  class="form-control">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <button type="button" id="btnEditEnreg" onClick="editEnregistrementValidataion()" class="btn btn-primary">Valider</button>
              </div>
              
            </form>

            </div>
          </div>
        </div>
    </div>
      
    <div class="modal fade" id="deleteEnregModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Modification</h4>

            </div> 

            <div class="modal-body">                   
              <input type="text" id="idDelEnregHide" hidden>
              <code><h3>Êtes-vous sûr de supprimer cet enregistrement ?</h3></code>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
              <button type="button" id="btnEditEnreg" onClick="deleteEnregistrementValidataion()" class="btn btn-danger">Supprimer</button>
            </div>
          </div>
        </div>
    </div>
      
    <div class="modal fade" id="serachByBarcodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

      <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="myModalLabel">Modification</h4>

            </div> 

            <div class="modal-body">                   
              <input type="text" id="idDelEnregHide" hidden>
              <code><h3>Êtes-vous sûr de supprimer cet enregistrement ?</h3></code>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
              <button type="button" id="btnEditEnreg" onClick="deleteEnregistrementValidataion()" class="btn btn-danger">Supprimer</button>
            </div>
          </div>
        </div>
    </div>

    <div id="imageNvDesignation"  class="modal fade " role="dialog">
      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header" style="padding:35px 50px;">

            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>

          </div>

          <div class="modal-body" style="padding:40px 50px;">

              <form   method="post" enctype="multipart/form-data">

                  <input type="hidden" name="idEnregistrement" id="id_Upd_ND" />

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
    <div id="imageExDesignation"  class="modal fade " role="dialog">

      <div class="modal-dialog">

        <div class="modal-content">

          <div class="modal-header" style="">

            <button type="button" class="close" data-dismiss="modal">&times;</button>

            <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>aperçu/Modification </b></h4>

          </div>

          <div class="modal-body" style="">

            <img  width="50%" height="30%" alt="" src="" id="imgsrc_Upd" />

            <form   method="post" enctype="multipart/form-data">

                <input  type="hidden" name="idEnregistrement" id="id_Upd_Ex" />

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

    
    <div class="modal fade" id="selectNbMoisModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Formulaire de paiement</h4>

                </div>

                <div class="modal-body">
                    <div name="deleteContainer">
                        <input type="hidden" id="idPsModalSelectNbMois">
                        <input type="hidden" id="montantFormuleInput">
                        <div class="form-group" align="center">
                            <h3>
                                <span class="">Choisir le nombre de mois à payer</span>
                            </h3>
                            <Select id="selectNbMois" onChange="changeFormule()" class="form-control" style="background-color:gold;width: 100px;">
                                <option value="1">1</option>
                                <option value="3">3</option>
                                <option value="6">6</option>
                                <option value="12">12</option>
                            </Select>
                        </div><br><br><br>
                        <div class="form-group alert alert-info" id="divFormule" style="width: 250px;">
                            Montant à payer : <span id="montantFormule"></span> FCFA
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>

                            <button type="button" name="btnSelectNbMois" onClick="effectue_paiementWave()" class="btn btn-primary">Valider</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="paiementWave" class="modal fade " role="dialog" align="center">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Paiement Wave </b>
                    </h4>
                </div>
                <div class="modal-body" id="bodyPayWave">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    <script>
      $(document).ready( function () {
          $('#listeEnregistrement').DataTable();
          $('#listeEnregistrement2').DataTable();
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