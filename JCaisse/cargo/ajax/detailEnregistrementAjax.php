<?php
  /*
  Résumé :
  Commentaire :
  Version : 2.0
  see also :
  Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
  Date de création : 20/03/2016
  Date derni�re modification : 20/04/2016; 15-04-2018
  */
  session_start();
  if(!$_SESSION['iduser']){
    header('Location:../../../index.php');
    }

  require('../../connection.php');
  require('../../connectionPDO.php');

  require('../../declarationVariables.php');

  $operation=@htmlspecialchars($_POST["operation"]);
  $idEnreg=@htmlspecialchars($_POST["idEnreg"]);
  // $etat=@htmlspecialchars($_POST["etat"]);


  $sql="SELECT * FROM `".$nomtableEnregistrement."` where idEnregistrement=".$idEnreg;
  $statement = $bdd->prepare($sql);
  $statement->execute();    
  $enregistrement = $statement->fetch(PDO::FETCH_ASSOC); 
?>

    <div class="cycle-tab-container container-fluid">
      <ul class="nav nav-tabs">
        <!-- <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#containerCbm">Détails des arrivages par container</a>
        </li> -->
       <li class="cycle-tab-item active">
          <a class="nav-link" role="tab" data-toggle="tab" href="#arrivage">Liste des chargements</a>
        </li>
         <!-- <li class="cycle-tab-item">
          <a class="nav-link" role="tab" data-toggle="tab" href="#settings">settings</a>
        </li> -->
      </ul>
        <div class="tab-content">
          <div class="tab-pane fade active in" id="arrivage" role="tabpanel" aria-labelledby="arrivage-tab">

            <table id="listeChargement" class="table table-striped" style="width:100%;background-color:white;">
              <thead>
                  <tr>
                      <th>Ordre</th>
                      <th>Date Charg.</th>
                      <th>Heure Charg.</th>
                      <th>Nb pcs charger</th>
                      <th>Entrepot source</th>
                      <th>Porteur</th>
                      <th>N° du porteur</th>
                      <th>Opération</th>
                  </tr>
              </thead>
              <tbody>
                <?php 
                  $sql="SELECT * FROM `".$nomtableChargement."` where retirer=0 and idEnregistrement=".$idEnreg." ORDER BY idChargement";
                  // $sql="SELECT * FROM `".$nomtableEnregistrement."` where retirer=0 and etat=1 ORDER BY idEnregistrement DESC";
                  // $sql="SELECT * FROM `".$nomtableEnregistrement."` where retirer=0 and idEntrepot<>0 and idAvion=0 and idContainer=0 ORDER BY idEnregistrement DESC";
                  
                  $statement = $bdd->prepare($sql);
                  $statement->execute();
                  
                  $chargements = $statement->fetchAll(PDO::FETCH_ASSOC); 
                  $i=1;
                  $porteur='-------';
                  $numPorteur='-------';
                  foreach ($chargements as $key) {
                    if ($key['idEntrepot']==0) {
                      $entrepotTxt = '-------';
                    } else {
                      
                      $sqlE="SELECT * FROM `".$nomtableEntrepot."` where idEntrepot='".$key['idEntrepot']."'";
                      
                      $statementE = $bdd->prepare($sqlE);
                      $statementE->execute();                  
                      $entrepot = $statementE->fetch();
                      $entrepotTxt = $entrepot['nomEntrepot'];
                    }
                    // var_dump()
                    if ($key['idContainer']!=0) {
                      $porteur = 'Container';
                      
                      $sqlE="SELECT * FROM `".$nomtableContainer."` where idContainer='".$key['idContainer']."'";
                      
                      $statementE = $bdd->prepare($sqlE);
                      $statementE->execute();                  
                      $container = $statementE->fetch();
                      $numPorteur = $container['numContainer'];
                    }  
                    if ($key['idAvion']!=0) {
                      $porteur = 'Avion';
                      
                      $sqlE="SELECT * FROM `".$nomtableAvion."` where idAvion='".$key['idAvion']."'";
                      
                      $statementE = $bdd->prepare($sqlE);
                      $statementE->execute();                  
                      $avion = $statementE->fetch();
                      $numPorteur = $avion['numVol'];
                    } 
                    
                ?>
                  <tr id="tr<?= $key['idChargement']?>">
                      <td><?= $i; ?></td>
                      <td><?= $key['dateChargement']?></td>
                      <td><?= $key['heureChargement']?></td>
                      <td><?= $key['nbPiecesCharger']?></td>
                      <td><?= $entrepotTxt?></td>
                      <td><?= $porteur?></td>
                      <td><?= $numPorteur?></td>
                      <td>
                        <div class="btn-group" role="group" aria-label="operation">
                          <button type="button" class="btn btn-success" id="btnChargementBagages" onClick="chargementBagages(<?= $key['idEnregistrement']?>)"><span class="glyphicon glyphicon-ok"></span></button>
                          <button type="button" class="btn btn-default" id="btnDetailsEnreg" onClick="detailEnregistrement(<?= $key['idEnregistrement']?>)"><span class="glyphicon glyphicon-plus"></span></button>
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
