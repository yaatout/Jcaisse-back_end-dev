<?php
session_start();

date_default_timezone_set('Africa/Dakar');

if(!$_SESSION['iduser']){
  header('Location:../../index.php');
}

if($_SESSION['vitrine']==0){
  header('Location:../accueil.php');
}

require('../connection.php');
require('../connectionPDO.php');
require('../connectionVitrine.php');

require('../declarationVariables.php');
$doublons=NULL;

// require('updatePanier.php');
// require('synchro.php');

function find_p_with_position($pns,$des) {
    foreach($pns as $index => $p) {
        if(($p['idDesignation'] == $des)){
            return $index;
        }
    } 
    return FALSE;
  }
  
  if (isset($_POST['btnNewCmdPanier'])) {
        $idPanier=$_POST['idPanier'];
        $retourner=0;
        $livrer=0;
        $confirmer=0;
        $annuler=0;
        $d = '0000-00-00 00:00:00';
        $req1 = $bddV->prepare("UPDATE panier SET
                                retourner=:retourner,
                                confirmer=:confirmer,
                                livrer=:livrer,
                                annuler=:annuler,
                                dateConfirmer= :dC,
                                dateLivrer= :dL,
                                dateAnnuler= :dA,
                                dateRetourner= :dR
                                WHERE idPanier=:idPanier ");
        //var_dump($req1);
        $req1->execute(array(
                            'retourner' => $retourner,
                            'confirmer' => $confirmer,
                            'livrer' => $livrer,
                            'annuler' => $annuler,
                            'dL' => $d,
                            'dC' => $d,
                            'dR' => $d,
                            'dA' => $d,
                            'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
        $req1->closeCursor();
  }
/**********************/


    // require('../entetehtml.php');
    require('../entetehtmlVitrine.php');
    echo'
       <body >';
       require('../header.php'); ?>
                                  <!-- REcherchede tous les panier de la boutique commande en cours -->

                                    <?php
                                    // var_dump($_SESSION['idBoutique']);
                                        $req1 = $bddV->prepare("SELECT * FROM panier p
                                            INNER JOIN ligne l ON l.idPanier = p.idPanier
                                            WHERE l.idBoutique =:idBoutique
                                             AND p.finaliser=1
                                             AND p.confirmer=0
                                               AND p.livrer=0
                                               AND p.annuler=0
                                               AND p.retourner=0
                                            GROUP BY p.idPanier DESC");
                                                $req1->execute(array(
                                                    'idBoutique' =>$_SESSION['idBoutique']
                                                    )) or die(print_r($req1->errorInfo()));
                                                    $commandeEnCours=$req1->rowCount();
                                                // $commandeEnCours=$req1->fetchAll();
                                                // var_dump($commandeEnCours);
                                    ?>
                                  <!-- Fin  recherchede tous les panier de la boutique commande en cours -->

                                   <!-- REcherchede tous les panier de la boutique commande confirmé-->
                                      <?php
                                          $req22 = $bddV->prepare("SELECT * FROM panier p
                                              INNER JOIN ligne l ON l.idPanier = p.idPanier
                                              WHERE l.idBoutique =:idBoutique
                                              AND p.finaliser=1
                                               AND p.confirmer=1
                                               AND p.expedition=0
                                               AND p.livrer=0
                                               AND p.annuler=0
                                               AND p.retourner=0
                                              GROUP BY p.idPanier DESC");
                                                  $req22->execute(array(
                                                      'idBoutique' =>$_SESSION['idBoutique']
                                                      )) or die(print_r($req22->errorInfo()));
                                                  $commandeConfirme=$req22->rowCount();
                                      ?>
                                  <!-- Fin  recherchede tous les panier de la boutique commande confirmé-->
                                  <!-- REcherchede tous les panier de la boutique commande en expedition -->
                                  <?php
                                          $req20 = $bddV->prepare("SELECT * FROM panier p
                                              INNER JOIN ligne l ON l.idPanier = p.idPanier
                                              WHERE l.idBoutique =:idBoutique
                                              AND p.finaliser=1
                                               AND p.confirmer=1
                                               AND p.expedition=1
                                               AND p.livrer=0
                                               AND p.annuler=0
                                               AND p.retourner=0
                                              GROUP BY p.idPanier DESC");
                                                  $req20->execute(array(
                                                      'idBoutique' =>$_SESSION['idBoutique']
                                                      )) or die(print_r($req20->errorInfo()));
                                                  $commandeExpedition=$req20->rowCount();
                                      ?>
                                  <!-- Fin  recherchede tous les panier de la boutique commande en expedition-->

                                  <!-- REcherchede tous les panier de la boutique commande livrer-->
                                      <?php
                                          $req3 = $bddV->prepare("SELECT * FROM panier p
                                        INNER JOIN ligne l ON l.idPanier = p.idPanier
                                        WHERE l.idBoutique =:idBoutique
                                        AND p.finaliser=1
                                        AND p.confirmer=1
                                        AND p.livrer=1
                                        AND p.expedition=1
                                        AND p.annuler=0
                                        AND p.retourner=0
                                        GROUP BY p.idPanier DESC");
                                        $req3->execute(array('idBoutique' =>$_SESSION['idBoutique']))
                                          or die(print_r($req3->errorInfo()));
                                        $commandeLivrer=$req3->rowCount();
                                      ?>
                                  <!-- Fin  recherchede tous les panier de la boutique commande livrer-->

                                  <!-- REcherchede tous les panier de la boutique commande annuler-->
                                      <?php
                                          $req4 = $bddV->prepare("SELECT * FROM panier p
                                        INNER JOIN ligne l ON l.idPanier = p.idPanier
                                        WHERE l.idBoutique =:idBoutique
                                        AND p.finaliser=1
                                        AND p.annuler=1
                                        GROUP BY p.idPanier DESC");
                                        $req4->execute(array('idBoutique' =>$_SESSION['idBoutique']))
                                          or die(print_r($req4->errorInfo()));
                                        $commandeAnnuler=$req4->rowCount();
                                      ?>
                                  <!-- Fin  recherchede tous les panier de la boutique commande annuler-->

                                  <!-- REcherchede tous les panier de la boutique commande livrer-->
                                      <?php
                                          $req5 = $bddV->prepare("SELECT * FROM panier p
                                        INNER JOIN ligne l ON l.idPanier = p.idPanier
                                        WHERE l.idBoutique =:idBoutique
                                        AND p.finaliser=1
                                        AND p.retourner=1
                                        GROUP BY p.idPanier DESC");
                                        $req5->execute(array('idBoutique' =>$_SESSION['idBoutique']))
                                          or die(print_r($req5->errorInfo()));
                                        $commandeRetourner=$req5->rowCount();
                                      ?>
                                  <!-- Fin  recherchede tous les panier de la boutique commande livrer-->

   <div class="container-fluid" >
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#COMMANDESENCOURS">EN COURS <span class="badge"><?php echo $commandeEnCours; ?></span></a></li>
              <li ><a data-toggle="tab" href="#COMMANDECONFIRME">CONFIRMEES <span class="badge"><?php echo $commandeConfirme; ?></span></a></li>
              <li ><a data-toggle="tab" href="#COMMANDEEXPEDITION">EN EXPEDITION <span class="badge"><?php echo $commandeExpedition; ?></span></a></li>
              <li  ><a data-toggle="tab" href="#COMMANDESLIVRER">LIVREES <span class="badge"><?php echo $commandeLivrer; ?></span></a></li>
              <li  ><a data-toggle="tab" href="#COMMANDESANNULER">ANNULEES <span class="badge"><?php echo $commandeAnnuler; ?></span></a></li>
              <li  ><a data-toggle="tab" href="#COMMANDESRETOURNER">RETOURNEES <span class="badge"><?php echo $commandeRetourner; ?></span></a></li>
            </ul><br>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="COMMANDESENCOURS">
                   <!-- Debut de l'Accordion pour Tout les Paniers -->
                    <div class="panel-group" id="accordion">
                      <?php
                      // On détermine sur quelle page on se trouve
                      if(isset($_GET['page']) && !empty($_GET['page'])){
                          $currentPage = (int) strip_tags($_GET['page']);
                      }else{
                          $currentPage = 1;
                      }
                      // On détermine le nombre d'articles par page
                      $parPage = 10;
                     ?>
                                  <?php /*********************************************************************************/ ?>
                                  <?php /******************************DEBUT CLIENT***************************************/ ?>


                                  <!-- Debut Boucle while concernant les Paniers en cours (1 aux maximum) -->
                                      <?php
                                          $cpt=0;
                                          $articlesJcaisse = array();

                                          $sqlGetStock="SELECT idDesignation,designation,nbreArticleUniteStock,SUM(`quantiteStockCourant`) as stockTotal FROM `".$nomtableStock."` GROUP BY `idDesignation`";
                                          $refJcaisse = mysql_query($sqlGetStock) or die ("persoonel requête 1".mysql_error());

                                          while ($key = mysql_fetch_array($refJcaisse)) {
                                            $articlesJcaisse[] = $key;
                                          }
                                          // var_dump($articlesJcaisse);
                                        //   $panier=$req1->fetchAll();
                                        //   var_dump($panier);
                                          while ($panier=$req1->fetch()) {
                                              $cpt++;
                                              $stockIns = 0;
                                              //REcherchede le client qui a  -->
                                              ?>
                                               <?php
                                              if($panier['idClient'] !== '0'){
                                                $req2 = $bddV->prepare("SELECT * FROM client WHERE idClient = :idClient");
                                                $req2->execute(array('idClient' =>$panier['idClient'])) or die(print_r($req2->errorInfo()));
                                                $client=$req2->fetch();
                                              //Fin  REcherchede le client qui a
                                              ?>
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                                        <div class="right-arrow pull-right">+</div>
                                                        <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                                        <!-- <span class="spanDate noImpr"> </span> -->
                                                        <span class="spanDate noImpr"> Date: <?php echo $panier['dateFinaliser']; ?> </span>
                                                        <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                                        <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']." ".$client['nom']; ?> </span></span>
                                                        <span class="spanTotal noImpr" >Telephone: <span ><?php echo $client['telephone']; ?> </span></span>
                                                        </a>
                                                        </h4>
                                                    </div>
                                                    <?php }   ?>
                                              <?php
                                              if($panier['idGuest'] !== '0'){
                                                $req2 = $bddV->prepare("SELECT * FROM guest
                                                        WHERE idGuest = :idGuest");
                                                $req2->execute(array('idGuest' =>$panier['idGuest'])) or die(print_r($req2->errorInfo()));
                                                $client=$req2->fetch();
                                              //Fin  REcherchede le client qui a
                                              ?>
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                                                <!-- <span class="spanDate noImpr"> </span> -->
                                                                <span class="spanDate noImpr"> Date: <?php echo $panier['dateFinaliser']; ?> </span>
                                                                <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                                                <span class="spanTotal noImpr" >Client: <span ><?php echo $client['nomComplet']; ?> </span></span>
                                                                <span class="spanTotal noImpr" >Telephone: <span ><?php echo $client['telephone']; ?> </span></span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <?php }   ?>
                                                              <div
                                                                  <?php echo  "id=panier".$panier['idPanier']."" ;
                                                                  if($cpt == 1){
                                                                          ?> class="panel-collapse collapse in" <?php
                                                                          }
                                                                          else  {
                                                                          ?> class="panel-collapse collapse " <?php
                                                                          } ?>   >
                                                                    <div class="panel-body" >

                                                                        <!--************** Debut ajouter article ********************-->
                                                                            <button type="submit" class="btn btn-primary pull-left btnAjoutArticle" id="<?= $panier['idPanier'] ; ?>" data-toggle="modal" <?php echo  "data-target=#ajouterArticle".$panier['idPanier'] ; ?>>
                                                                                  <span class="glyphicon glyphicon-plus"> </span> Ajouter article
                                                                            </button>
                                                                            <div class="modal fade"  id="ajouterArticle<?= $panier['idPanier'] ; ?>" role="dialog">
                                                                                <div class="modal-dialog">
                                                                                    <!-- Modal content-->
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header panel-primary">
                                                                                            <button type="button" class="close" id="<?= $panier['idPanier'] ; ?>" data-dismiss="modal">&times;</button>
                                                                                            <h4 class="modal-title"> Ajout d'article </h4>
                                                                                        </div>
                                                                                        <form class="form-row noImpr"  id="factForm" method="post" style="padding:40px 50px;">
                                                                                            <div class="form-group col-lg-12">
                                                                                                <label for="searchProduct">Référence de l'article </label>
                                                                                                <input type="text" class="form-control searchProduct-<?= $panier['idPanier'] ; ?>" name="searchProduct" id="searchProduct" autocomplete="off" required/>
                                                                                            </div>
                                                                                            <div class="form-group col-lg-6">
                                                                                                <label for="quantite">Quantité </label>
                                                                                                <input type="number" min="1" class="form-control quantite-<?= $panier['idPanier'] ; ?>" name="quantite" id="quantite-<?= $panier['idPanier'] ; ?>"/>
                                                                                            </div>
                                                                                            <div class="form-group col-lg-6">
                                                                                                <label for="unite">Unité </label>
                                                                                                <select class="form-control selectUnite-<?= $panier['idPanier'] ; ?> unite-<?= $panier['idPanier'] ; ?>" name="" id="">
                                                                                                </select>
                                                                                            </div>
                                                                                            <br><br><br><br><br><br><br><br><br>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                <button type="button" id="<?= $panier['idPanier']; ?>" class="btn btn-success btnConfAjoutArticle btn_disabled_after_click">Ajouter</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <!--******************* Fin ajouter article *******************-->
                                                                          <!--*******************************Debut annuler Pagnet****************************************-->
                                                                          <button type="button" class="btn btn-danger pull-right btnAnnuler" id="annuler_<?= $panier['idPanier'];?>">
                                                                                  <span class="glyphicon glyphicon-remove"></span> Annuler
                                                                          </button>

                                                                          <div class="modal fade" <?php echo  "id=msg_annuler_pagnet".$panier['idPanier'] ; ?> role="dialog">
                                                                              <div class="modal-dialog">
                                                                                  <!-- Modal content-->
                                                                                  <div class="modal-content">
                                                                                      <div class="modal-header panel-primary">
                                                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                          <h4 class="modal-title">Confirmation</h4>
                                                                                      </div>
                                                                                      <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                          <div class="modal-body">
                                                                                              <p><?php echo "Voulez-vous annuler le panier numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                                              <input type="hidden" name="idPanier" id="idPanierAnnuler<?= $panier['idPanier'];?>" <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                          </div>
                                                                                          <div class="modal-footer">
                                                                                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                              <button type="button" name="btnAnnulerPagnet" class="btn btn-success btnAnnulerPagnet-<?= $panier['idPanier'];?> btn_disabled_after_click">Confirmer</button>
                                                                                          </div>
                                                                                      </form>
                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                          <!--*******************************Fin annuler Pagnet****************************************-->
                                                                        <!--*******************************Debut confirmer ****************************************-->

                                                                          <button class="btn btn-success pull-right btnConfEt" id="<?= $panier['idPanier'];?>" style="margin-right:20px;">
                                                                          Confirmer
                                                                          </button>
                                                                          <div class="modal fade" <?php echo  "id=msg_confirmer_commande".$panier['idPanier'] ; ?> role="dialog">
                                                                              <div class="modal-dialog">
                                                                                  <!-- Modal content-->
                                                                                  <div class="modal-content">
                                                                                      <div class="modal-header panel-primary">
                                                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                          <h4 class="modal-title">Confirmation</h4>
                                                                                      </div>
                                                                                      <form class="form-inline noImpr" id="factForm" method="post" >
                                                                                          <div class="modal-body">
                                                                                              <p><?php echo "Voulez-vous confirmer la commande numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                                              <input type="hidden" name="idPanier" id="idPanier-<?= $panier['idPanier'];?>"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                          </div>
                                                                                          <div class="modal-footer">
                                                                                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                              <button type="button" name="btnConfirmerCommande" class="btn btn-success btnConfirmerCommande-<?= $panier['idPanier'];?> btn_disabled_after_click">Confirmer</button>
                                                                                          </div>
                                                                                      </form> 
                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                          <div class="modal fade" <?php echo  "id=msg_echec".$panier['idPanier'] ; ?> role="dialog">
                                                                              <div class="modal-dialog">
                                                                                  <!-- Modal content-->
                                                                                  <div class="modal-content">
                                                                                      <div class="modal-header panel-primary">
                                                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                          <h4 class="modal-title">AVERTISSEMENT</h4>
                                                                                      </div>
                                                                                      <!-- <form class="form-inline noImpr"  id="factForm" method="post"  > -->
                                                                                          <div class="modal-body">
                                                                                              <p> <code> Impossible d'effectuer cette operation! <br> Stock insufisant. </code> </p>
                                                                                          </div>
                                                                                          <div class="modal-footer">
                                                                                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                          </div>
                                                                                      <!-- </form> -->
                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                        <!--*******************************Fin confirmer ****************************************-->

                                                                        <!-- <form class="form-inline noImpr"  id="updatep" method="post"  >
                                                                            <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                            <button class="btn btn-success  pull-right" style="margin-right:20px;" name="btnUpdatePanier">
                                                                                Mettre à jour
                                                                            </button>
                                                                        </form> -->
                                                                        <!--*******************************Debut Facture****************************************-->
                                                                        <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$panier['idPanier'] ;?>').submit();">
                                                                            Facture pro forma
                                                                        </button>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                                            method="post" action="vitrine/pdfFactureVitrine" >
                                                                            <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                                                        </form>
                                                                        <!--*******************************Fin Facture****************************************-->
                                                                        <!--*******************************Debut Ticket****************************************-->
                                                                        <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$panier['idPanier'] ;?>').submit();">
                                                                            Ticket de Caisse pro forma
                                                                        </button>

                                                                        <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                                            method="post" action="vitrine/barcodeFactureVitrine" >
                                                                            <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                                                        </form>
                                                                        <!--*******************************Fin Ticket****************************************-->

                                                                          <table class="table ">
                                                                              <thead class="noImpr">
                                                                                  <tr>
                                                                                      <th>Référence</th>
                                                                                      <th>Prix</th>
                                                                                      <th>Quantité</th>
                                                                                      <th>Unité</th>
                                                                                      <th>Prix Total</th>
                                                                                      <th width="150px">Dépôt</th>
                                                                                      <th></th>
                                                                                  </tr>
                                                                              </thead>
                                                                              <tbody>
                                                                                       <?php
                                                                                          $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier ORDER BY idArticle DESC");
                                                                                          $req2->execute(array(
                                                                                            'idPanier' =>$panier['idPanier']
                                                                                            )) or die(print_r($req2->errorInfo()));

                                                                                        while ($ligne=$req2->fetch()) {

                                                                                            // var_dump($ligne);
                                                                                            
                                                                                            $req0 = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE designation =:designation ");
                                                                                            $req0->execute(array(
                                                                                                'designation' =>$ligne['designation']
                                                                                                )) or die(print_r($req0->errorInfo()));
                                                                                            $result=$req0->fetch();
                                                                                            // var_dump($result);
                                                                                            $nbr = $result['nbreArticleUniteStock'];
                                                                                            $us = $result['uniteStock'];
                                                                                            // var_dump($us);
                                                                                            ?>
                                                                                        <tr <?= ($ligne['barrer'] == '1') ? 'style="text-decoration:line-through;color:red;"' : '' ; ?>>
                                                                                            <td>
                                                                                                <?php  if (file_exists("./uploads/".$ligne['image'])) { ?> 
                                                                                                    <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/<?= ($ligne['image']) ? $ligne['image'] : 'defaultImg.jpeg' ; ?>" width="60px" height="60px">

                                                                                                <?php } else { ?>

                                                                                                    <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/defaultImg.jpeg" width="60px" height="60px">

                                                                                                <?php } ?>
                                                                                                <?= mb_strtoupper($ligne['designation']); ?> 
                                                                                            </td>
                                                                                            <td> <?=  $ligne['prix']; ?>  </td>
                                                                                            <?php
                                                                                                if (find_p_with_position($articlesJcaisse, $ligne['idDesignation']) !==FALSE) {
                                                                                                    $c = 0;
                                                                                                    $i=find_p_with_position($articlesJcaisse, $ligne['idDesignation']);
                                                                                                    if ($ligne['unite'] !== 'Article' && $ligne['unite'] !== 'article' && $ligne['unite'] !== '') {
                                                                                                      $qtLigne = $ligne['quantite'] * $articlesJcaisse[$i]['nbreArticleUniteStock'];
                                                                                                      if (($qtLigne > $articlesJcaisse[$i]['stockTotal']) and ($ligne['barrer'] == '0')) {
                                                                                                        if ($ligne['barrer'] == '0') $stockIns++;
                                                                                                        $c = 1;
                                                                                                        ?>
                                                                                                        <td class="alert alert-danger qtite"> <?php echo  $ligne['quantite']; ?><?= ($ligne['barrer'] == '0') ? '<code class="code"> Stock insufisant</code><br>Disponible :'.$articlesJcaisse[$i]['stockTotal'] : '' ;?> </td>
                                                                                                        <?php
                                                                                                      }else {
                                                                                                        ?>
                                                                                                        <td <?= ($ligne['updateQuantite'] == '1') ? 'class="alert alert-info qtite"' : 'class="qtite"' ; ?>> <?php echo  $ligne['quantite']; ?></td>
                                                                                                        <?php
                                                                                                      }
                                                                                                    // }else if ($ligne['unite'] == 'Article' || $ligne['unite'] == 'article'){
                                                                                                    }else {
                                                                                                      $qtLigne = $ligne['quantite'];
                                                                                                      if ($qtLigne > $articlesJcaisse[$i]['stockTotal']) {
                                                                                                        if ($ligne['barrer'] == '0') $stockIns++;
                                                                                                        $c = 1;
                                                                                                        ?>
                                                                                                            <td class="alert alert-danger qtite"> <?php echo  $ligne['quantite']; ?><?= ($ligne['barrer'] == '0') ? '<code class="code"> Stock insufisant</code><br>Disponible :'.$articlesJcaisse[$i]['stockTotal'] : '' ;?> </td>
                                                                                                        <?php
                                                                                                      }else {
                                                                                                        ?>
                                                                                                            <td <?= ($ligne['updateQuantite'] == '1') ? 'class="alert alert-info qtite"' : 'class="qtite"' ; ?>> <?php echo  $ligne['quantite']; ?></td>
                                                                                                        <?php
                                                                                                      }
                                                                                                    }
                                                                                                  }else{
                                                                                             ?> 
                                                                                             <td><code>Article introuvable dans les références</code></td>
                                                                                             <?php
                                                                                                }
                                                                                             ?>
                                                                                            <td> <?php echo  $ligne['unite']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['prixTotal']; ?>  </td>
                                                                                            <td> 
                                                                                                <select name="entrepot" class="form-control" onChange="changeDepot(<?= $ligne['idArticle'] ?>,<?= $ligne['idDesignation'] ?>)" id="entrepot-<?= $ligne['idArticle'] ; ?>">
                                                                                                    <?php
                                                                                                        if ($ligne['idEntrepot']!=0) {
                                                                                                            
                                                                                                            $sql13="SELECT * FROM `".$nomtableEntrepot."` WHERE idEntrepot=".$ligne['idEntrepot'];
                                                                                                            
                                                                                                            $res13=$bdd->query($sql13);
                                                                                                            $entrepot=$res13->fetch();
                                                                                                    ?>
                                                                                                         <option value="<?= $entrepot['idEntrepot']; ?>"><?= $entrepot['nomEntrepot']; ?></option>
                                                                                                    <?php
                                                                                                        
                                                                                                        }
                                                                                                    ?>
                                                                                                        <option value="">------Choisir-------</option>
                                                                                                    <?php
                                                                                                        $sql13="SELECT * FROM `".$nomtableEntrepot."`";
                                                                                                        
                                                                                                        $res13=$bdd->query($sql13);
                                                                                                        $entrepots=$res13->fetchAll();

                                                                                                        foreach ($entrepots as $etp) {
                                                                                                            
                                                                                                    ?>
                                                                                                            <option value="<?= $etp['idEntrepot']; ?>"><?= $etp['nomEntrepot']; ?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    ?>
                                                                                                </select>
                                                                                                <input type="hidden" id="qty-<?= $ligne['idArticle'] ; ?>" value="<?= $ligne['quantite'] ; ?>">
                                                                                                <input type="hidden" id="uniteV-<?= $ligne['idArticle'] ; ?>" value="<?= $ligne['unite'] ; ?>">
                                                                                            </td>
                                                                                            <!-- Debut Modification -->
                                                                                            <td width="200px">
                                                                                                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                                                                                    <div class="btn-group mr-2" role="group" aria-label="First group">
                                                                                                        <input type="hidden" id="us-<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" value="<?= $us; ?>">
                                                                                                        <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnEditerProduit" class="btn btn-info btn-sm pull-right btnEditerProduit <?= ($ligne['barrer'] == '1') ? 'disabled' : '' ; ?>" data-toggle="modal" data-target= "#editerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-edit"></span> Editer aticle</button>
                                                                                                     </div>
                                                                                                    <div class="btn-group mr-2" role="group" aria-label="Second group">
                                                                                                        <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnRetournerProduit" class="btn btn-danger btn-sm pull-right btnRetournerProduit <?= ($ligne['barrer'] == '1') ? 'disabled' : '' ; ?>" data-toggle="modal" data-target= "#retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-remove"></span> Retirer</button>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="modal fade"  id="retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>" role="dialog">
                                                                                                    <div class="modal-dialog">
                                                                                                        <!-- Modal content-->
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header panel-primary">
                                                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                                <h4 class="modal-title">Confirmation</h4>
                                                                                                            </div>
                                                                                                            <form class="form-inline noImpr"  id="factForm" method="post">
                                                                                                                <div class="modal-body" align="center">
                                                                                                                    <p><?php echo "Êtes-vous sûr de vouloir retirer ce produit ?"; ?></p>
                                                                                                                    <!-- <input type="number" class="form-control" min="0" name="qtRetourner-<?= $ligne['idArticle'];?>" id="qtRetourner-<?= $ligne['idArticle'];?>" placeholder="Quantité à retourner" required> -->
                                                                                                                    <input type="hidden" name="id" id="id"  <?php echo  "value='".$ligne['idArticle']."_".$panier['idPanier']."'" ; ?>>
                                                                                                                    <!-- <input type="hidden" name="quantite-<?= $ligne['idArticle']; ?>" id="quantite-<?= $ligne['idArticle'];?>"  <?php echo  "value='".$ligne['quantite']."'" ; ?>><br><br> -->
                                                                                                                    <!-- <code class="text-error hidden"></code> -->
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                                    <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-danger btnConfRetourProduit-<?= $ligne['idArticle']."_".$panier['idPanier']; ?> btn_disabled_after_click">Confirmer</button>
                                                                                                                </div>
                                                                                                            </form>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal fade"  id="editerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>" role="dialog">
                                                                                                    <div class="modal-dialog">
                                                                                                        <!-- Modal content-->
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header panel-primary">
                                                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                                <h4 class="modal-title">Modification </h4>
                                                                                                            </div>
                                                                                                            <form class="form-row noImpr"  id="factForm" method="post" style="padding:40px 50px;">
                                                                                                                <!-- <input type="hidden" class="idArticle" id="idArticle" value="<?= $ligne['idArticle'] ; ?>">
                                                                                                                <input type="hidden" class="idPanier" id="idPanier" value="<?= $panier['idPanier'] ; ?>"> -->
                                                                                                                <input type="hidden" class="oldRef-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>">
                                                                                                                <div class="form-group col-lg-12">
                                                                                                                    <label for="searchProduct">Référence de l'article </label>
                                                                                                                    <input type="text" class="form-control searchProduct-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>" name="searchProduct" id="searchProduct" autocomplete="off"/>
                                                                                                                </div>
                                                                                                                <div class="form-group col-lg-6">
                                                                                                                    <label for="quantite">Quantité </label>
                                                                                                                    <input type="number" min="1" class="form-control quantite-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>" name="quantite" id="quantite-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"/>
                                                                                                                </div>
                                                                                                                <div class="form-group col-lg-6">
                                                                                                                    <label for="unite">Unité </label>
                                                                                                                    <select class="form-control selectUnite-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?> unite-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>" name="" id="">
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                                <br><br><br><br><br><br><br><br><br>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                                    <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-success btnConfEditProduit btn_disabled_after_click">Confirmer</button>
                                                                                                                </div>
                                                                                                            </form>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <!-- Fin Modification -->

                                                                                        </tr>
                                                                                        <?php   } ?>
                                                                              </tbody>
                                                                          </table>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <input type="hidden" id="stockIns-<?= $panier['idPanier'];?>" name="" value="<?= $stockIns; ?>">
                                      <?php   }
                                      $req1->closeCursor();
                                         ?>
                                  <!-- Fin Boucle while concernant les Paniers en cours  -->
                                  <?php /******************************FIN CLIENT***************************************/ ?>
                                  <?php /*******************************************************************************/ ?>
                    </div>
                    <!-- Fin de l'Accordion pour Tout les Paniers -->
                </div>
                <div class="tab-pane fade" id="COMMANDECONFIRME">
                    <!-- Debut de l'Accordion pour Tout les Paniers -->
                    <div class="panel-group" id="accordion">
                      <?php
                      // On détermine sur quelle page on se trouve
                      if(isset($_GET['page']) && !empty($_GET['page'])){
                          $currentPage = (int) strip_tags($_GET['page']);
                      }else{
                          $currentPage = 1;
                      }
                      // On détermine le nombre d'articles par page
                      $parPage = 10;
                     ?>
                                  <?php /*********************************************************************************/ ?>
                                  <?php /******************************DEBUT CLIENT***************************************/ ?>


                                  <!-- Debut Boucle while concernant les Paniers en cours (1 aux maximum) -->
                                      <?php
                                          $cpt=0;
                                          while ($panier=$req22->fetch()) {
                                              $cpt++;
                                              //REcherchede le client qui a  -->
                                                $req2 = $bddV->prepare("SELECT * FROM client
                                                        WHERE idClient = :idClient ");
                                                $req2->execute(array('idClient' =>$panier['idClient'])) or die(print_r($req2->errorInfo()));
                                                $client=$req2->fetch()
                                              //Fin  REcherchede le client qui a
                                              ?>
                                                          <div class="panel panel-primary">
                                                              <div class="panel-heading">
                                                                  <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                                                  <div class="right-arrow pull-right">+</div>
                                                                  <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                                                  <!-- <span class="spanDate noImpr"> </span> -->
                                                                  <span class="spanDate noImpr"> Confirmer le: <?php echo $panier['dateConfirmer']; ?> </span>
                                                                   <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                                                   <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']." ".$client['nom']; ?> </span></span>
                                                                   <span class="spanTotal noImpr" >Telephone: <span ><?php echo $client['telephone']; ?> </span></span>
                                                                  </a>
                                                                  </h4>
                                                              </div>
                                                              <div
                                                                  <?php echo  "id=panier".$panier['idPanier']."" ;
                                                                  if($cpt == 1){
                                                                        ?> class="panel-collapse collapse in" <?php
                                                                        }
                                                                        else  {
                                                                        ?> class="panel-collapse collapse " <?php
                                                                        } ?>   >
                                                                  <div class="panel-body" >

                                                                         <!--*******************************Debut Retourner Pagnet****************************************-->
                                                                              <button type="button" class="btn btn-danger pull-right btnRetourEt" id="retour_<?= $panier['idPanier'];?>">
                                                                                    <span class="glyphicon glyphicon-remove"></span> Retourner
                                                                              </button>

                                                                              <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$panier['idPanier'] ; ?> role="dialog">
                                                                                  <div class="modal-dialog">
                                                                                      <!-- Modal content-->
                                                                                      <div class="modal-content">
                                                                                          <div class="modal-header panel-primary">
                                                                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                              <h4 class="modal-title">Confirmation</h4>
                                                                                          </div>
                                                                                          <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                              <div class="modal-body">
                                                                                                  <p><?php echo "Voulez-vous retourner le panier numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                                                  <input type="hidden" name="idPanier" id="idPanierRetour<?= $panier['idPanier'];?>"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                              </div>
                                                                                              <div class="modal-footer">
                                                                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                  <button type="button" name="btnRetournerPagnet" class="btn btn-success btnRetournerPagnet-<?= $panier['idPanier'];?> btn_disabled_after_click">Confirmer</button>
                                                                                              </div>
                                                                                          </form>
                                                                                      </div>
                                                                                  </div>
                                                                              </div>
                                                                              <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                              <button class="btn btn-success btnExp pull-right" id="exp_<?= $panier['idPanier'];?>" style="margin-right:20px;">
                                                                              Expédition
                                                                              </button>
                                                                              <div class="modal fade" <?php echo  "id=msg_exp_commande".$panier['idPanier'] ; ?> role="dialog">
                                                                                  <div class="modal-dialog">
                                                                                      <!-- Modal content-->
                                                                                      <div class="modal-content">
                                                                                          <div class="modal-header panel-primary">
                                                                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                              <h4 class="modal-title">Confirmation</h4>
                                                                                          </div>
                                                                                          <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                              <div class="modal-body">
                                                                                                  <p><?php echo "Confirmer l'expédition la commande numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                                                  <input type="hidden" name="idPanier" id="idPanierExp<?= $panier['idPanier'];?>"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                              </div>
                                                                                              <div class="modal-footer">
                                                                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                  <button type="button" name="btnExpCommande" class="btn btn-success btnExpCommande-<?= $panier['idPanier'];?> btn_disabled_after_click">Confirmer</button>
                                                                                              </div>
                                                                                          </form>
                                                                                      </div>
                                                                                  </div>
                                                                              </div>
                                                                              
                                                                              <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                              <button class="btn btn-danger btnCancelConf pull-left" style="margin-right:20px;" id="cancelConf_<?= $panier['idPanier'];?>">
                                                                                Annuler la confirmation
                                                                              </button>
                                                                              <div class="modal fade" <?php echo  "id=msg_cancel_confirmation".$panier['idPanier'] ; ?> role="dialog">
                                                                                  <div class="modal-dialog">
                                                                                      <!-- Modal content-->
                                                                                      <div class="modal-content">
                                                                                          <div class="modal-header panel-primary">
                                                                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                              <h4 class="modal-title">Confirmation</h4>
                                                                                          </div>
                                                                                          <form class="form-inline noImpr"  id="factForm" method="post" >
                                                                                              <div class="modal-body">
                                                                                                  <p><?php echo "Annulation de la confirmation de la commande numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                                                  <input type="hidden" name="idPanier" id="idPanierCancelConf<?= $panier['idPanier'];?>"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                              </div>
                                                                                              <div class="modal-footer">
                                                                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                  <button type="button" name="btnCancelConf" class="btn btn-success btnCancelConf-<?= $panier['idPanier'];?> btn_disabled_after_click">Confirmer</button>
                                                                                              </div>
                                                                                          </form>
                                                                                      </div>
                                                                                  </div>
                                                                              </div>
                                                                              <!--*******************************Debut Facture****************************************-->
                                                                                <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$panier['idPanier'] ;?>').submit();">
                                                                                    Facture
                                                                                </button>

                                                                                <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                                                    method="post" action="vitrine/pdfFactureVitrine" >
                                                                                    <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                                                                </form>
                                                                              <!--*******************************Fin Facture****************************************-->
                                                                              <!--*******************************Debut Ticket****************************************-->
                                                                                <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$panier['idPanier'] ;?>').submit();">
                                                                                    Ticket de Caisse
                                                                                </button>

                                                                                <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                                                    method="post" action="vitrine/barcodeFactureVitrine" >
                                                                                    <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                                                                </form>
                                                                              <!--*******************************Fin Ticket****************************************-->
                                                                          <table class="table ">
                                                                              <thead class="noImpr">
                                                                                  <tr>
                                                                                      <th>Référence</th>
                                                                                      <th>Prix</th>
                                                                                      <th>Quantité</th>
                                                                                      <th>Unité</th>
                                                                                      <th>Prix Total</th>
                                                                                      <th></th>
                                                                                  </tr>
                                                                              </thead>
                                                                              <tbody>
                                                                                       <?php
                                                                                          $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier and barrer = 0  ORDER BY idArticle DESC");
                                                                                          $req2->execute(array(
                                                                                                      'idPanier' =>$panier['idPanier']
                                                                                                      )) or die(print_r($req2->errorInfo()));

                                                                                        while ($ligne=$req2->fetch()) {  ?>
                                                                                        <tr>
                                                                                            <td> 
                                                                                                <?php  if (file_exists("./uploads/".$ligne['image'])) { ?> 
                                                                                                    <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/<?= ($ligne['image']) ? $ligne['image'] : 'defaultImg.jpeg' ; ?>" width="60px" height="60px">

                                                                                                <?php } else { ?>

                                                                                                    <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/defaultImg.jpeg" width="60px" height="60px">

                                                                                                <?php } ?>
                                                                                                <?= mb_strtoupper($ligne['designation']); ?>  
                                                                                            </td>
                                                                                            <td> <?= $ligne['prix']; ?>  </td>
                                                                                            <td> <?= $ligne['quantite']; ?>  </td>
                                                                                            <td> <?= $ligne['unite']; ?>  </td>
                                                                                            <td> <?= $ligne['prixTotal']; ?>  </td>
                                                                                            <!-- Debut Modification -->
                                                                                            <td width="10px">
                                                                                            <button disabled type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnRetournerProduit" class="btn btn-danger btn-sm pull-right btnRetournerProduit" data-toggle="modal" data-target= "#retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-remove"></span> Retourner</button>

                                                                                                <div class="modal fade" id="retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>" role="dialog">
                                                                                                    <div class="modal-dialog">
                                                                                                        <!-- Modal content-->
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header panel-primary">
                                                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                                                <h4 class="modal-title">Confirmation</h4>
                                                                                                            </div>
                                                                                                            <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                                                <div class="modal-body" align="center">
                                                                                                                    <p><?php echo "Êtes-vous sûr de vouloir retirer ce produit ?" ; ?></p>
                                                                                                                    <!-- <input type="number" class="form-control" min="0" name="qtRetourner-<?= $ligne['idArticle'];?>" id="qtRetourner-<?= $ligne['idArticle'];?>" placeholder="Quantité à retourner" required> -->
                                                                                                                    <input type="hidden" name="id" id="id"  <?php echo  "value='".$ligne['idArticle']."_".$panier['idPanier']."'" ; ?>>
                                                                                                                    <!-- <input type="hidden" name="quantite-<?= $ligne['idArticle']; ?>" id="quantite-<?= $ligne['idArticle'];?>"  <?php echo  "value='".$ligne['quantite']."'" ; ?>><br><br>
                                                                                                                    <code class="text-error hidden"></code> -->
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                                    <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-success btnConfRetourProduit-<?= $ligne['idArticle']."_".$panier['idPanier']; ?> btn_disabled_after_click">Confirmer</button>
                                                                                                                </div>
                                                                                                            </form>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                            <!-- Fin Modification -->
                                                                                        </tr>
                                                                                        <?php   } ?>
                                                                              </tbody>
                                                                          </table>
                                                                  </div>
                                                              </div>
                                                          </div>

                                      <?php   } $req22->closeCursor();
                                ?>
                                  <!-- Fin Boucle while concernant les Paniers en cours  -->
                                  <?php /******************************FIN CLIENT***************************************/ ?>
                                  <?php /*******************************************************************************/ ?>
                    </div>
                    <!-- Fin de l'Accordion pour Tout les Paniers -->
                </div>
                <div class="tab-pane fade " id="COMMANDEEXPEDITION">
                    <div class="panel-group" id="accordion">
                          <?php
                                  $cpt=0;
                                while ($panier=$req20->fetch()) {
                                    $cpt++;
                                      //REcherchede le client qui a  -->
                                                $req2 = $bddV->prepare("SELECT * FROM client
                                                        WHERE idClient = :idClient ");
                                                $req2->execute(array('idClient' =>$panier['idClient'])) or die(print_r($req2->errorInfo()));
                                                $client=$req2->fetch()
                                      //Fin  REcherchede le client qui a
                                    ?>
                                  <div class="panel panel-primary">
                                      <div class="panel-heading">
                                          <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                          <div class="right-arrow pull-right">+</div>
                                          <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                          <!-- <span class="spanDate noImpr"> </span> -->
                                          <span class="spanDate noImpr"> Expédition: <?php echo $panier['dateExpedition'];?> </span>
                                           <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                             <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']." ".$client['nom']; ?> </span></span>
                                            <span class="spanTotal noImpr" >Telephone: <span ><?php echo $client['telephone']; ?> </span></span>
                                          </a>
                                          </h4>
                                      </div>
                                      <div
                                          <?php echo  "id=panier".$panier['idPanier']."" ;
                                          if($cpt == 1){
                                                  ?> class="panel-collapse collapse in" <?php
                                                  }
                                                  else  {
                                                  ?> class="panel-collapse collapse " <?php
                                                  } ?>   >
                                          <div class="panel-body" >
                                                <!--*******************************Fin Livrer panier****************************************-->
                                                <button class="btn btn-success btnlivrer pull-right" id="livrer_<?= $panier['idPanier'];?>"  style="margin-right:20px;">
                                                Livrer
                                                </button>
                                                <!--*******************************Debut Facture****************************************-->
                                                <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$panier['idPanier'] ;?>').submit();">
                                                    Facture
                                                </button>

                                                <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                    method="post" action="vitrine/pdfFactureVitrine" >
                                                    <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                                </form>
                                                <!--*******************************Fin Facture****************************************-->
                                                <!--*******************************Debut Ticket****************************************-->
                                                <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$panier['idPanier'] ;?>').submit();">
                                                    Ticket de Caisse
                                                </button>

                                                <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                    method="post" action="vitrine/barcodeFactureVitrine" >
                                                    <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                                </form>
                                                <!--*******************************Fin Ticket****************************************-->
                                                <div class="modal fade" <?php echo  "id=msg_livrer_commande".$panier['idPanier'] ; ?> role="dialog">
                                                    <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header panel-primary">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Confirmation</h4>
                                                            </div>
                                                            <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                <div class="modal-body">
                                                                    <p><?php echo "Avez-vous livrer la commande numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                    <input type="hidden" name="idPanier" id="idPanierLivrer<?= $panier['idPanier']; ?>"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                    <button type="button" name="btnLivrerCommande" class="btn btn-success btnLivrerCommande-<?= $panier['idPanier']; ?> btn_disabled_after_click">Confirmer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--*******************************Fin Livrer Panier****************************************-->
                                              <table class="table ">
                                                  <thead class="noImpr">
                                                      <tr>
                                                          <th>Référence</th>
                                                          <th>Prix</th>
                                                          <th>Quantité</th>
                                                          <th>Unité</th>
                                                          <th>Prix Total</th>
                                                          <th></th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                           <?php
                                                              $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier and barrer = 0 ORDER BY idArticle DESC");
                                                              $req2->execute(array(
                                                                          'idPanier' =>$panier['idPanier']
                                                                          )) or die(print_r($req2->errorInfo()));

                                                            while ($ligne=$req2->fetch()) {  ?>
                                                            <tr>
                                                                <td> 
                                                                    <?php  if (file_exists("./uploads/".$ligne['image'])) { ?> 
                                                                        <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/<?= ($ligne['image']) ? $ligne['image'] : 'defaultImg.jpeg' ; ?>" width="60px" height="60px">

                                                                    <?php } else { ?>

                                                                        <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/defaultImg.jpeg" width="60px" height="60px">

                                                                    <?php } ?>
                                                                    <?= mb_strtoupper($ligne['designation']); ?>  
                                                                </td>
                                                                <td> <?= $ligne['prix']; ?>  </td>
                                                                <td> <?= $ligne['quantite']; ?>  </td>
                                                                <td> <?= $ligne['unite']; ?>  </td>
                                                                <td> <?= $ligne['prixTotal']; ?>  </td>
                                                                <!-- Debut Modification -->
                                                                <td width="10px">
                                                                    <!-- <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnRetournerProduit" class="btn btn-danger btn-sm pull-right btnRetournerProduit" data-toggle="modal" data-target= "#retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-remove"></span> Retourner</button> -->

                                                                    <div class="modal fade"  id="retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header panel-primary">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                                </div>
                                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                    <div class="modal-body" align="center">
                                                                                        <p><?php echo "Êtes-vous sûr de vouloir retirer ce produit ?" ; ?></p>
                                                                                        <!-- <input type="number" class="form-control" min="0" name="qtRetourner-<?= $ligne['idArticle'];?>" id="qtRetourner-<?= $ligne['idArticle'];?>" placeholder="Quantité à retourner" required> -->
                                                                                        <input type="hidden" name="id" id="id"  <?php echo  "value='".$ligne['idArticle']."_".$panier['idPanier']."'" ; ?>>
                                                                                        <!-- <input type="hidden" name="quantite-<?= $ligne['idArticle']; ?>" id="quantite-<?= $ligne['idArticle'];?>"  <?php echo  "value='".$ligne['quantite']."'" ; ?>><br><br>
                                                                                        <code class="text-error hidden"></code> -->
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                        <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-success btnConfRetourProduit-<?= $ligne['idArticle']."_".$panier['idPanier']; ?> btn_disabled_after_click">Confirmer</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                    <!-- Fin Modification -->
                                                            </tr>
                                                            <?php   } ?>
                                                  </tbody>
                                              </table>
                                          </div>
                                      </div>
                                  </div>
                                  <?php
                                } $req20->closeCursor();
                                  ?>
                    </div>
                </div>
                <div class="tab-pane fade " id="COMMANDESLIVRER">
                    <div class="panel-group" id="accordion">
                          <?php
                                  $cpt=0;
                                while ($panier=$req3->fetch()) {
                                    $cpt++;
                                      //REcherchede le client qui a  -->
                                                $req2 = $bddV->prepare("SELECT * FROM client
                                                        WHERE idClient = :idClient ");
                                                $req2->execute(array('idClient' =>$panier['idClient'])) or die(print_r($req2->errorInfo()));
                                                $client=$req2->fetch()
                                      //Fin  REcherchede le client qui a
                                    ?>
                                  <div class="panel panel-primary">
                                      <div class="panel-heading">
                                          <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                          <div class="right-arrow pull-right">+</div>
                                          <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                          <!-- <span class="spanDate noImpr"> </span> -->
                                          <span class="spanDate noImpr"> Livrer le: <?php echo $panier['dateLivrer'];?> </span>
                                           <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                             <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']." ".$client['nom']; ?> </span></span>
                                            <span class="spanTotal noImpr" >Telephone: <span ><?php echo $client['telephone']; ?> </span></span>
                                          </a>
                                          </h4>
                                      </div>
                                      <div
                                          <?php echo  "id=panier".$panier['idPanier']."" ;
                                          if($cpt == 1){
                                                  ?> class="panel-collapse collapse in" <?php
                                                  }
                                                  else  {
                                                  ?> class="panel-collapse collapse " <?php
                                                  } ?>   >
                                          <div class="panel-body" >

                                            <!--*******************************Debut Retourner Pagnet****************************************-->
                                                <button type="button"    class="btn btn-danger pull-right btnRetourEt" id="retour_<?= $panier['idPanier'];?>">
                                                        <span class="glyphicon glyphicon-remove"></span>Retourner
                                                </button>
                                                  
                                            <!--*******************************Debut Facture****************************************-->
                                            <button class="btn btn-warning  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'facture'.$panier['idPanier'] ;?>').submit();">
                                                Facture
                                            </button>

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=facture".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                method="post" action="vitrine/pdfFactureVitrine" >
                                                <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                            </form>
                                            <!--*******************************Fin Facture****************************************-->
                                            <!--*******************************Debut Ticket****************************************-->
                                            <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$panier['idPanier'] ;?>').submit();">
                                                Ticket de Caisse
                                            </button>

                                            <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                method="post" action="vitrine/barcodeFactureVitrine" >
                                                <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                            </form>
                                            <!--*******************************Fin Ticket****************************************-->
                                                <div class="modal fade" <?php echo  "id=msg_rtrn_pagnet".$panier['idPanier'] ; ?> role="dialog">
                                                    <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header panel-primary">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Confirmation</h4>
                                                            </div>
                                                            <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                <div class="modal-body">
                                                                    <p><?php echo "Voulez-vous retourner le panier numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                    <input type="hidden" name="idPanier" id="idPanierRetour<?= $panier['idPanier'];?>"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                    <button type="button" name="btnRetournerPagnet" class="btn btn-success btnRetournerPagnet-<?= $panier['idPanier'];?> btn_disabled_after_click">Confirmer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--*******************************Fin Retourner Pagnet****************************************-->
                                              <table class="table ">
                                                  <thead class="noImpr">
                                                      <tr>
                                                          <th>Référence</th>
                                                          <th>Prix</th>
                                                          <th>Quantité</th>
                                                          <th>Unité</th>
                                                          <th>Prix Total</th>
                                                          <th></th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                           <?php
                                                              $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier and barrer = 0 ORDER BY idArticle DESC");
                                                              $req2->execute(array(
                                                                          'idPanier' =>$panier['idPanier']
                                                                          )) or die(print_r($req2->errorInfo()));

                                                            while ($ligne=$req2->fetch()) {  ?>
                                                            <tr>
                                                                <td> 
                                                                    <?php  if (file_exists("./uploads/".$ligne['image'])) { ?> 
                                                                        <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/<?= ($ligne['image']) ? $ligne['image'] : 'defaultImg.jpeg' ; ?>" width="60px" height="60px">

                                                                    <?php } else { ?>

                                                                        <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/defaultImg.jpeg" width="60px" height="60px">

                                                                    <?php } ?>
                                                                    <?=  mb_strtoupper($ligne['designation']); ?>  
                                                                </td>
                                                                <td> <?= $ligne['prix']; ?>  </td>
                                                                <td> <?= $ligne['quantite']; ?>  </td>
                                                                <td> <?= $ligne['unite']; ?>  </td>
                                                                <td> <?= $ligne['prixTotal']; ?>  </td>
                                                                <!-- Debut Modification -->
                                                                <td width="10px">
                                                                    <button disabled type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnRetournerProduit" class="btn btn-danger btn-sm pull-right btnRetournerProduit" data-toggle="modal" data-target= "#retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-remove"></span> Retourner</button>

                                                                    <div class="modal fade"  id="retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header panel-primary">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                                </div>
                                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                    <div class="modal-body" align="center">
                                                                                        <p><?php echo "Êtes-vous sûr de vouloir retirer ce produit ?" ; ?></p>
                                                                                        <!-- <input type="number" class="form-control" min="0" name="qtRetourner-<?= $ligne['idArticle'];?>" id="qtRetourner-<?= $ligne['idArticle'];?>" placeholder="Quantité à retourner" required> -->
                                                                                        <input type="hidden" name="id" id="id"  <?php echo  "value='".$ligne['idArticle']."_".$panier['idPanier']."'" ; ?>>
                                                                                        <!-- <input type="hidden" name="quantite-<?= $ligne['idArticle']; ?>" id="quantite-<?= $ligne['idArticle'];?>"  <?php echo  "value='".$ligne['quantite']."'" ; ?>><br><br>
                                                                                        <code class="text-error hidden"></code> -->
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                        <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-success btnConfRetourProduit-<?= $ligne['idArticle']."_".$panier['idPanier'];?> btn_disabled_after_click">Confirmer</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                    <!-- Fin Modification -->
                                                            </tr>
                                                            <?php   } ?>
                                                  </tbody>
                                              </table>
                                          </div>
                                      </div>
                                  </div>
                                  <?php
                                } $req3->closeCursor();
                                  ?>
                    </div>
                </div>
                <div class="tab-pane fade " id="COMMANDESANNULER">
                    <div class="panel-group" id="accordion">
                          <?php
                                  $cpt=0;
                                while ($panier=$req4->fetch()) {
                                    $cpt++;
                                      //REcherchede le client qui a  -->
                                                $req2 = $bddV->prepare("SELECT * FROM client
                                                        WHERE idClient = :idClient ");
                                                $req2->execute(array('idClient' =>$panier['idClient'])) or die(print_r($req2->errorInfo()));
                                                $client=$req2->fetch();
                                      //Fin  REcherchede le client qui a
                                    ?>
                                  <div class="panel panel-primary">
                                      <div class="panel-heading">
                                          <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                          <div class="right-arrow pull-right">+</div>
                                          <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                          <!-- <span class="spanDate noImpr"> </span> -->
                                          <span class="spanDate noImpr"> Annuler le: <?php echo $panier['dateAnnuler'];?> </span>
                                           <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                           <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']." ".$client['nom']; ?> </span></span>
                                          <span class="spanTotal noImpr" >Telephone: <span ><?php echo $client['telephone']; ?> </span></span>
                                            </a>
                                          </h4>
                                      </div>
                                      <div
                                          <?php echo  "id=panier".$panier['idPanier']."" ;
                                          if($cpt == 1){
                                                  ?> class="panel-collapse collapse in" <?php
                                                  }
                                                  else  {
                                                  ?> class="panel-collapse collapse " <?php
                                                  } ?>   >
                                          <div class="panel-body" >

                                            <!--*******************************Debut new command panier****************************************-->
                                                <!-- <button type="submit" class="btn btn-info pull-right" data-toggle="modal" <?php echo  "data-target=#newCommand-".$panier['idPanier'] ; ?>>
                                                        <span class="glyphicon glyphicon-refresh"></span> Commander à nouveau
                                                </button> -->

                                                <div class="modal fade" <?php echo  "id=newCommand-".$panier['idPanier'] ; ?> role="dialog">
                                                    <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header panel-primary">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Confirmation</h4>
                                                            </div>
                                                            <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                <div class="modal-body">
                                                                    <p><?php echo "Voulez-vous commander à nouveau le panier numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                    <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                    <button type="submit" name="btnNewCmdPanier" class="btn btn-success btn_disabled_after_click">Confirmer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--*******************************Fin new command panier****************************************-->
                                              <table class="table ">
                                                  <thead class="noImpr">
                                                      <tr>
                                                          <th>Référence</th>
                                                          <th>Prix</th>
                                                          <th>Quantité</th>
                                                          <th>Unité</th>
                                                          <th>Prix Total</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                           <?php
                                                              $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier and barrer = 0 ORDER BY idArticle DESC");
                                                              $req2->execute(array(
                                                                          'idPanier' =>$panier['idPanier']
                                                                          )) or die(print_r($req2->errorInfo()));

                                                            while ($ligne=$req2->fetch()) {  ?>
                                                            <tr>
                                                                <td> 
                                                                    <?php  if (file_exists("./uploads/".$ligne['image'])) { ?> 
                                                                        <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/<?= ($ligne['image']) ? $ligne['image'] : 'defaultImg.jpeg' ; ?>" width="60px" height="60px">

                                                                    <?php } else { ?>

                                                                        <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/defaultImg.jpeg" width="60px" height="60px">

                                                                    <?php } ?>
                                                                    <?= mb_strtoupper($ligne['designation']); ?>  
                                                                </td>
                                                                <td> <?= $ligne['prix']; ?>  </td>
                                                                <td> <?= $ligne['quantite']; ?>  </td>
                                                                <td> <?= $ligne['unite']; ?>  </td>
                                                                <td> <?= $ligne['prixTotal']; ?>  </td>
                                                            </tr>
                                                            <?php   } ?>
                                                  </tbody>
                                              </table>
                                          </div>
                                      </div>
                                  </div>
                                <?php }
                                  $req4->closeCursor(); ?>
                    </div>
                </div>
                <div class="tab-pane fade " id="COMMANDESRETOURNER">
                    <div class="panel-group" id="accordion">
                          <?php

                                  $cpt=0;
                                while ($panier=$req5->fetch()) {
                                    $cpt++;
                                      //REcherchede le client qui a  -->
                                                $req2 = $bddV->prepare("SELECT * FROM client
                                                        WHERE idClient = :idClient ");
                                                $req2->execute(array('idClient' =>$panier['idClient'])) or die(print_r($req2->errorInfo()));
                                                $client=$req2->fetch();
                                      //Fin  REcherchede le client qui a

                                    ?>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panierR".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                            <div class="right-arrow pull-right">+</div>
                                            <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                            <!-- <span class="spanDate noImpr"> </span> -->
                                            <span class="spanDate noImpr"> Retourner le: <?php echo $panier['dateRetourner'];?> </span>
                                             <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                             <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']." ".$client['nom']; ?> </span></span>
                                             <span class="spanTotal noImpr" >Telephone: <span ><?php echo $client['telephone']; ?> </span></span>
                                             </a>
                                            </h4>
                                        </div>
                                        <div
                                            <?php echo  "id=panierR".$panier['idPanier']."" ;
                                            if($cpt == 1){
                                                    ?> class="panel-collapse collapse in" <?php
                                                    }
                                                    else  {
                                                    ?> class="panel-collapse collapse " <?php
                                                    } ?>   >
                                            <div class="panel-body" >

                                                <!--*******************************Debut new command panier****************************************-->
                                                    <!-- <button type="submit"    class="btn btn-info pull-right" data-toggle="modal" <?php echo  "data-target=#newCommand-".$panier['idPanier'] ; ?>>
                                                            <span class="glyphicon glyphicon-refresh"></span> Commander à nouveau
                                                    </button> -->

                                                    <div class="modal fade" <?php echo  "id=newCommand-".$panier['idPanier'] ; ?> role="dialog">
                                                        <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header panel-primary">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">Confirmation</h4>
                                                                </div>
                                                                <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                    <div class="modal-body">
                                                                        <p><?php echo "Voulez-vous commander à nouveau le panier numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                        <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" name="btnNewCmdPanier" class="btn btn-success btn_disabled_after_click">Confirmer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--*******************************Fin new command panier****************************************-->
                                                <table class="table ">
                                                    <thead class="noImpr">
                                                        <tr>
                                                            <th>Référence</th>
                                                            <th>Prix</th>
                                                            <th>Quantité</th>
                                                            <th>Unité</th>
                                                            <th>Prix Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                             <?php
                                                                $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier and barrer = 0 ORDER BY idArticle DESC");
                                                                $req2->execute(array(
                                                                            'idPanier' =>$panier['idPanier']
                                                                            )) or die(print_r($req2->errorInfo()));

                                                              while ($ligne=$req2->fetch()) {  ?>
                                                              <tr>
                                                                    <td> 
                                                                        <?php  if (file_exists("./uploads/".$ligne['image'])) { ?> 
                                                                            <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/<?= ($ligne['image']) ? $ligne['image'] : 'defaultImg.jpeg' ; ?>" width="60px" height="60px">

                                                                        <?php } else { ?>

                                                                            <img alt="<?= mb_strtoupper($ligne['designation']);?>" src="vitrine/uploads/defaultImg.jpeg" width="60px" height="60px">

                                                                        <?php } ?>
                                                                        <?= mb_strtoupper($ligne['designation']); ?>  
                                                                    </td>
                                                                    <td> <?= $ligne['prix']; ?>  </td>
                                                                    <td> <?= $ligne['quantite']; ?>  </td>
                                                                    <td> <?= $ligne['unite']; ?>  </td>
                                                                    <td> <?= $ligne['prixTotal']; ?>  </td>
                                                              </tr>
                                                              <?php   } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } $req5->closeCursor();
                                  ?>
                    </div>
                </div>
            </div>
   </div>

   <!-- <div class="modal fade" role="dialog" id="imgmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
                </div>
                <img class="img-responsive" src="" id="show-img">  
            </div> 
        </div>
    </div> -->
    
    <div id="myModal" class="modal">
        <span class="close" data-dismiss="modal">&times;</span>
        <!-- <span class="close">×</span> -->
        <img class="modal-content" id="show-img">
        <div id="caption"></div>
    </div>
<?php
    echo'</body></html>';

?>
