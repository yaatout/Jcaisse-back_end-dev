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

if (isset($_GET['c'])) {
  $idContainer=$_GET['c'];
} else {
  $idContainer=0;
}
if (isset($_GET['a'])) {
  $idAvion=$_GET['a'];
} else {
  $idAvion=0;
}


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
    <div class="" id="divAddArrivageEmplacement" align="center">
      <br>
      <label for=""><b>Entrepot de déchargement</b> </label>
      <input type="text" name="dechargeAutocomplete" id="dechargeAutocomplete" class="form-control dechargeAutocomplete" style="width:20%" autocomplete="off">
      <input type="hidden" id="dechargeHide">

    </div> <br> 
    <div class="cycle-tab-container container-fluid">
      <ul class="nav nav-tabs"> 
        <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivage">Liste des arrivages</a>
        </li>
        <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivageRecu">Arrivages reçus</a>
        </li>
        <!-- <li class="cycle-tab-item pull-right">
          <input type="text" id="searchByBarcode" class="form-control" autocomplete="off" placeholder="Rechercher par code-barre...">
        </li> -->
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade active in" id="arrivage" role="tabpanel" aria-labelledby="arrivage-tab">
          <table id="arrivageList" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                  <th></th>
                  <th>Nom du Client</th>
                  <th>Date Enreg.</th>
                  <!-- <th>Heure Enreg.</th> -->
                  <th>Nb. CBM/KG</th>
                  <th>Prix CBM/KG</th>
                  <th>Total (FCFA)</th>
                  <th>Nb pcs</th>
                  <!-- <th>Nb pcs initial</th> -->
                  <!-- <th>Nb pcs restant</th> -->
                  <th>Nat. bagages</th>
                  <th>Porteur</th>
                  <th>Entrepot</th>
                  <!-- <th>Nb Pcs à Charger</th> -->
                  <th>Opération</th>
                </tr>
            </thead>
              <tbody>
                <?php 
                  $sql="SELECT * FROM `".$nomtableEnregistrement."` where recu=0 and retirer=0 and idContainer= ".$idContainer." and idAvion=".$idAvion." ORDER BY etat";
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
                      
                    $sqlE="SELECT * FROM `".$nomtableEntrepot."` where idEntrepot='".$key['idEntrepotArrive']."'";
                    
                    $statementE = $bdd->prepare($sqlE);
                    $statementE->execute();                  
                    $entrepot = $statementE->fetch();

                    
                    $sqlC="SELECT * FROM `".$nomtableContainer."` where idContainer='".$key['idContainer']."'";
                    
                    $statementC = $bdd->prepare($sqlC);
                    $statementC->execute();                  
                    $container = $statementC->fetch();

                    $sqlA="SELECT * FROM `".$nomtableAvion."` where idAvion='".$key['idAvion']."'";
                    
                    $statementA = $bdd->prepare($sqlA);
                    $statementA->execute();                  
                    $avion = $statementA->fetch();

                    if ($container['etat']==2 || $avion['etat']==2) {
                    
                      
                ?>
                  <tr <?= ($key['etat']==2) ? "style='background-color:#F9D6D4;'" : "" ; ?> id="tr<?= $key['idEnregistrement']?>">
                      <td><?= $key['codeBarre']; ?></td>
                      <td><?= $client['prenom'].' '.$client['nom']?></td>
                      <td><?= $key['dateEnregistrement']?></td>
                      <!-- <td><?= $key['heureEnregistrement']?></td> -->
                      <td id="nbcf<?= $key['idEnregistrement']?>"><?= $key['quantite_cbm_fret']?></td>
                      <td id="prixcf<?= $key['idEnregistrement']?>"><?= number_format($key['prix_cbm_fret'], 0, ',', ' ')?></td>
                      <td id="totalcf<?= $key['idEnregistrement']?>"><?= number_format(($key['prix_cbm_fret']*$key['quantite_cbm_fret']), 0, ',', ' ')?></td>
                      <td id="nbp<?= $key['idEnregistrement']?>"><?= $key['nbPieces']?></td>
                      <!-- <td id="restantPcs<?= $key['idEnregistrement']?>"><?= $restantPcs?><input type="hidden" id="pcsRestant<?= $key['idEnregistrement']?>" value="<?= $restantPcs?>"></td> -->
                      <td id="natb<?= $key['idEnregistrement']?>"><?= $key['natureBagage']?></td>
                      <td id="port<?= $key['idEnregistrement']?>"><?= ($container['etat']==2) ? 'Container - '.$container['numContainer'] : 'Vol - '.$avion['numVol'] ?></td>
                      <td id="ent<?= $key['idEnregistrement']?>"><?= ($entrepot['nomEntrepot']) ? $entrepot['nomEntrepot'].' - '.$entrepot['adresseEntrepot'] : 'Néant';?></td>
                      <!-- <td><input type="number" id="nbPcsCharger<?= $key['idEnregistrement']?>" class="form-control" placeholder="Nombre de pièces" value="<?= $restantPcs?>" <?= ($key['etat']!=1) ? "disabled='true'" : "" ; ?> ></td> -->
                      <td>
                        <div class="btn-group" role="group" aria-label="operation">
                          <button type="button" class="btn btn-info" id="btnEditEnreg" onClick="editEnregistrement(<?= $key['idEnregistrement']?>)"><span class="glyphicon glyphicon-edit"></span></button>
                          <button type="button" class="btn btn-success" id="btnRecuEnreg<?= $key['idEnregistrement']?>" onClick="recuEnregistrement(<?= $key['idEnregistrement']?>)" <?= ($key['recu']==1) ? "disabled='true'" : "" ; ?> ><span class="glyphicon glyphicon-ok"></span> Reçu</button>
                          <img src="images/iconfinder9.png" alt="" onClick="getImage('<?= $key['image']?>')">
                          <!-- <button type="button" class="btn btn-success" id="btnChargementBagages" onClick="chargementBagages(<?= $key['idEnregistrement']?>)"><span class="glyphicon glyphicon-upload"></span></button> -->
                          <!-- <button> -->
                            <!-- <form action="barcodeCBM.php" target="_blank" method="post">
                                <input type="hidden" name="idEnreg" value="<?= $key['idEnregistrement'];?>">
                                <input type="hidden" name="codeBarrePcsInContainer" value="<?= $key['codeBarre'];?>">
                                <input type="hidden" name="nbPcsInContainer" value="<?= $key['nbPieces'];?>">
                                <input type="hidden" name="numContainer" value="<?= $key['idContainer'];?>">
                                <input type="hidden" name="numVol" value="<?= $key['idAvion'];?>">
                                <input type="hidden" name="idClient" value="<?= $key['idClient'];?>">
                                <button type="button" class="btn btn-info" id="btnEditEnreg" onClick="editEnregistrement(<?= $key['idEnregistrement']?>)" <?= ($key['etat']!=1) ? "disabled='true'" : "" ; ?>><span class="glyphicon glyphicon-edit"></span></button>
                                <button type="button" class="btn btn-danger" id="btnDeleteEnreg" onClick="deleteEnregistrement(<?= $key['idEnregistrement']?>)" <?= ($key['etat']!=1) ? "disabled='true'" : "" ; ?>><span class="glyphicon glyphicon-trash"></span></button>
                                <button type="submit" class="btn btn-secondary pull-left">

                                    <span class="glyphicon glyphicon-barcode"> </span>

                                </button>
                            </form> -->
                          <!-- </button> -->
                        </div> 
                      </td>
                  </tr>
                <?php 
                    $i++;
                  }
                  }
                ?>
              </tbody>
          </table>          
        </div>
        <div class="tab-pane fade" id="arrivageRecu" role="tabpanel" aria-labelledby="arrivageRecu-tab">
          <table id="arrivageRecuList" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                  <th></th>
                  <th>Nom du Client</th>
                  <th>Date Enreg.</th>
                  <!-- <th>Heure Enreg.</th> -->
                  <th>Nb. CBM/KG</th>
                  <th>Prix CBM/KG</th>
                  <th>Total (FCFA)</th>
                  <th>Nb pcs</th>
                  <!-- <th>Nb pcs initial</th> -->
                  <!-- <th>Nb pcs restant</th> -->
                  <th>Nat. bagages</th>
                  <th>Porteur</th>
                  <th>Entrepot</th>
                  <!-- <th>Nb Pcs à Charger</th> -->
                  <!-- <th>Opération</th> -->
                </tr>
            </thead>
              <tbody>
                <?php 
                  $sql="SELECT * FROM `".$nomtableEnregistrement."` where recu=1 and retirer=0 and idContainer= ".$idContainer." and idAvion=".$idAvion." ORDER BY etat";
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
                      
                    $sqlE="SELECT * FROM `".$nomtableEntrepot."` where idEntrepot='".$key['idEntrepotArrive']."'";
                    
                    $statementE = $bdd->prepare($sqlE);
                    $statementE->execute();                  
                    $entrepot = $statementE->fetch();

                    
                    $sqlC="SELECT * FROM `".$nomtableContainer."` where idContainer='".$key['idContainer']."'";
                    
                    $statementC = $bdd->prepare($sqlC);
                    $statementC->execute();                  
                    $container = $statementC->fetch();

                    $sqlA="SELECT * FROM `".$nomtableAvion."` where idAvion='".$key['idAvion']."'";
                    
                    $statementA = $bdd->prepare($sqlA);
                    $statementA->execute();                  
                    $avion = $statementA->fetch();

                    if ($container['etat']==2 || $avion['etat']==2) {
                    
                      
                ?>
                  <tr <?= ($key['etat']==2) ? "style='background-color:#DDFCD0;'" : "" ; ?> id="tr<?= $key['idEnregistrement']?>">
                    <td><?= $key['codeBarre']; ?></td>
                    <td><?= $client['prenom'].' '.$client['nom']?></td>
                    <td><?= $key['dateEnregistrement']?></td>
                    <!-- <td><?= $key['heureEnregistrement']?></td> -->
                    <td id="nbcf<?= $key['idEnregistrement']?>"><?= $key['quantite_cbm_fret']?></td>
                    <td id="prixcf<?= $key['idEnregistrement']?>"><?= number_format($key['prix_cbm_fret'], 0, ',', ' ')?></td>
                    <td id="totalcf<?= $key['idEnregistrement']?>"><?= number_format(($key['prix_cbm_fret']*$key['quantite_cbm_fret']), 0, ',', ' ')?></td>
                    <td id="nbp<?= $key['idEnregistrement']?>"><?= $key['nbPieces']?></td>
                    <!-- <td id="restantPcs<?= $key['idEnregistrement']?>"><?= $restantPcs?><input type="hidden" id="pcsRestant<?= $key['idEnregistrement']?>" value="<?= $restantPcs?>"></td> -->
                    <td id="natb<?= $key['idEnregistrement']?>"><?= $key['natureBagage']?></td>
                    <td id="port<?= $key['idEnregistrement']?>"><?= ($container['etat']==2) ? 'Container - '.$container['numContainer'] : 'Vol - '.$avion['numVol'] ?></td>
                    <td id="ent<?= $key['idEnregistrement']?>"><?= ($entrepot['nomEntrepot']) ? $entrepot['nomEntrepot'].' - '.$entrepot['adresseEntrepot'] : 'Néant';?></td>
                    <!-- <td><input type="number" id="nbPcsCharger<?= $key['idEnregistrement']?>" class="form-control" placeholder="Nombre de pièces" value="<?= $restantPcs?>" <?= ($key['etat']!=1) ? "disabled='true'" : "" ; ?> ></td> -->
                  </tr>
                <?php 
                    $i++;
                  }
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

    <script>
      $(document).ready( function () {
          $('#arrivageList').DataTable();
          $('#arrivageRecuList').DataTable();
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