<?php
session_start();

if(!$_SESSION['iduser']){
  header('Location:../index.php');
}

if($_SESSION['vitrine']==0){
  header('Location:accueil.php');
}

require('connection.php');
require('connectionVitrine.php');

require('declarationVariables.php');
$doublons=NULL;

if (isset($_POST['btnConfirmerCommande'])) {
        $idPanier=$_POST['idPanier'];
        $confirmer=1;
        $expedition=1;
      $req1 = $bddV->prepare("UPDATE panier SET
                            confirmer=:confirmer,
                            expedition=:expedition,
                            dateConfirmer= NOW()
                            WHERE idPanier=:idPanier ");
      $req1->execute(array(
                        'confirmer' => $confirmer,
                        'expedition' => $expedition,
                        'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
      $req1->closeCursor();
  }
if (isset($_POST['btnLivrerCommande'])) {
        $idPanier=$_POST['idPanier'];
        $livrer=1;
      $req1 = $bddV->prepare("UPDATE panier SET
                    livrer=:livrer ,
                    dateLivrer= NOW()
                    WHERE idPanier=:idPanier ");
      $req1->execute(array(
                           'livrer' => $livrer,
                           'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
      $req1->closeCursor();
  }
  if (isset($_POST['btnRetournerPagnet'])) {
        $idPanier=$_POST['idPanier'];
        $retourner=1;
        $req1 = $bddV->prepare("UPDATE panier SET
                                retourner=:retourner ,
                                dateRetourner=NOW()
                                WHERE idPanier=:idPanier ");
        //var_dump($req1);
        $req1->execute(array(
                            'retourner' => $retourner,
                            'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
        $req1->closeCursor();
  }
  if (isset($_POST['btnAnnulerPagnet'])) {
        $idPanier=$_POST['idPanier'];
        $annuler=1;
      $req1 = $bddV->prepare("UPDATE panier SET
       annuler=:annuler,
       dateAnnuler=NOW()
       WHERE idPanier=:idPanier ");
      $req1->execute(array(
                           'annuler' => $annuler,
                           'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
      $req1->closeCursor();
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


    require('entetehtml.php');
    echo'
       <body >';
       require('header.php'); ?>
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
                                    ?>
                                  <!-- Fin  recherchede tous les panier de la boutique commande en cours -->

                                   <!-- REcherchede tous les panier de la boutique commande confirmé-->
                                      <?php
                                          $req22 = $bddV->prepare("SELECT * FROM panier p
                                              INNER JOIN ligne l ON l.idPanier = p.idPanier
                                              WHERE l.idBoutique =:idBoutique
                                              AND p.finaliser=1
                                               AND p.confirmer=1
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

                                  <!-- REcherchede tous les panier de la boutique commande livrer-->
                                      <?php
                                          $req3 = $bddV->prepare("SELECT * FROM panier p
                                        INNER JOIN ligne l ON l.idPanier = p.idPanier
                                        WHERE l.idBoutique =:idBoutique
                                        AND p.finaliser=1
                                        AND p.confirmer=1
                                        AND p.livrer=1
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

   <div class="container" >
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#COMMANDESENCOURS">Commandes en cours <span class="badge"><?php echo $commandeEnCours; ?></span></a></li>
              <li ><a data-toggle="tab" href="#COMMANDECONFIRME">Commandes confirmées <span class="badge"><?php echo $commandeConfirme; ?></span></a></li>
              <li  ><a data-toggle="tab" href="#COMMANDESLIVRER">Commandes livrées <span class="badge"><?php echo $commandeLivrer; ?></span></a></li>
              <li  ><a data-toggle="tab" href="#COMMANDESANNULER">Commandes annulées <span class="badge"><?php echo $commandeAnnuler; ?></span></a></li>
              <li  ><a data-toggle="tab" href="#COMMANDESRETOURNER">Commandes retournées <span class="badge"><?php echo $commandeRetourner; ?></span></a></li>
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
                                        //   $panier=$req1->fetchAll();
                                        //   var_dump($panier);
                                          while ($panier=$req1->fetch()) {
                                              $cpt++;
                                              //REcherchede le client qui a  -->
                                              ?>
                                               <?php  
                                              if($panier['idClient'] !== '0'){
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

                                                        <span class="spanDate noImpr"> </span>
                                                        <span class="spanDate noImpr"> Date: <?php echo $panier['dateHeure']; ?> </span>
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

                                                        <span class="spanDate noImpr"> </span>
                                                        <span class="spanDate noImpr"> Date: <?php echo $panier['dateHeure']; ?> </span>
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


                                                                          <!--*******************************Debut annuler Pagnet****************************************-->
                                                                          <button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_annuler_pagnet".$panier['idPanier'] ; ?>>
                                                                                  <span class="glyphicon glyphicon-remove"></span>Annuler
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
                                                                                              <p><?php echo "Voulez-vous annuler le panier numéro <b>".$pagnet['idPagnet']."</b>" ; ?></p>
                                                                                              <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                          </div>
                                                                                          <div class="modal-footer">
                                                                                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                              <button type="submit" name="btnAnnulerPagnet" class="btn btn-success">Confirmer</button>
                                                                                          </div>
                                                                                      </form>
                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                          <!--*******************************Fin annuler Pagnet****************************************-->

                                                                          <button class="btn btn-success pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_confirmer_commande".$panier['idPanier'] ; ?>>
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
                                                                                      <form class="form-inline noImpr"  id="factForm" method="post"  >
                                                                                          <div class="modal-body">
                                                                                              <p><?php echo "Voulez-vous confirmer la commande numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                                              <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                          </div>
                                                                                          <div class="modal-footer">
                                                                                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                              <button type="submit" name="btnConfirmerCommande" class="btn btn-success">Confirmer</button>
                                                                                          </div>
                                                                                      </form>
                                                                                  </div>
                                                                              </div>
                                                                          </div>

                                                                          <table class="table ">
                                                                              <thead class="noImpr">
                                                                                  <tr>
                                                                                      <th>Référence</th>
                                                                                      <th>Prix</th>
                                                                                      <th>Quantité</th>
                                                                                      <th>Prix Total</th>
                                                                                      <th></th>
                                                                                  </tr>
                                                                              </thead>
                                                                              <tbody>
                                                                                       <?php
                                                                                          $req2 = $bddV->prepare("SELECT * FROM ligne
                                                                                                                          WHERE idPanier =:idPanier
                                                                                                                           ");
                                                                                          $req2->execute(array(
                                                                                                      'idPanier' =>$panier['idPanier']
                                                                                                      )) or die(print_r($req2->errorInfo()));

                                                                                        while ($ligne=$req2->fetch()) {  ?>
                                                                                        <tr>
                                                                                            <td> <?php echo  $ligne['designation']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['prixTotal']; ?>  </td>
                                                                                            <!-- Debut Modification -->
                                                                                            <td width="10px">                                                                                           
                                                                                            <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnRetournerProduit" class="btn btn-danger btn-sm pull-right btnRetournerProduit" data-toggle="modal" data-target= "#retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-remove"></span> Retourner</button>
                                                                                            
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
                                                                                                                    <p><?php echo "Voulez-vous retourner le produit <b>".$ligne['designation']."</b> ? <br>Veuillez entrer la quantité à retourner" ; ?></p>
                                                                                                                    <input type="number" class="form-control" min="0" name="qtRetourner-<?= $ligne['idArticle'];?>" id="qtRetourner-<?= $ligne['idArticle'];?>" placeholder="Quantité à retourner" required>
                                                                                                                    <input type="hidden" name="id" id="id"  <?php echo  "value='".$ligne['idArticle']."_".$panier['idPanier']."'" ; ?>>
                                                                                                                    <input type="hidden" name="quantite-<?= $ligne['idArticle']; ?>" id="quantite-<?= $ligne['idArticle'];?>"  <?php echo  "value='".$ligne['quantite']."'" ; ?>><br><br>
                                                                                                                    <code class="text-error hidden"></code>
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                                    <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-success btnConfRetourProduit-<?= $ligne['idArticle']."_".$panier['idPanier']; ?>">Confirmer</button>
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

                                                                  <span class="spanDate noImpr"> </span>
                                                                  <span class="spanDate noImpr"> Confirmer le: <?php echo $panier['dateConfirmer']; ?> </span>
                                                                   <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                                                   <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']; ?> </span></span>
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
                                                                              <button type="submit"    class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$panier['idPanier'] ; ?>>
                                                                                      <span class="glyphicon glyphicon-remove"></span>Retourner
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
                                                                                                  <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                              </div>
                                                                                              <div class="modal-footer">
                                                                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                  <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
                                                                                              </div>
                                                                                          </form>
                                                                                      </div>
                                                                                  </div>
                                                                              </div>
                                                                              <!--*******************************Fin Retourner Pagnet****************************************-->

                                                                              <button class="btn btn-success  pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_livrer_commande".$panier['idPanier'] ; ?>>
                                                                              Livrer
                                                                              </button>
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
                                                                                                  <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                              </div>
                                                                                              <div class="modal-footer">
                                                                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                  <button type="submit" name="btnLivrerCommande" class="btn btn-success">Confirmer</button>
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
                                                                                    method="post" action="pdfFactureVitrine.php" >
                                                                                    <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                                                                </form>
                                                                              <!--*******************************Fin Facture****************************************-->
                                                                              <!--*******************************Debut Ticket****************************************-->
                                                                                <button  class="btn btn-info  pull-right" style="margin-right:20px;" onclick="document.getElementById('<?php echo 'ticket'.$panier['idPanier'] ;?>').submit();">
                                                                                    Ticket de Caisse
                                                                                </button>

                                                                                <form class="form-inline pull-right noImpr" <?php echo  "id=ticket".$panier['idPanier'] ; ?>  target="_blank" style="margin-right:20px;"
                                                                                    method="post" action="barcodeFactureVitrine.php" >
                                                                                    <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value=".$panier['idPanier']."" ; ?> >
                                                                                </form>
                                                                              <!--*******************************Fin Ticket****************************************-->
                                                                          <table class="table ">
                                                                              <thead class="noImpr">
                                                                                  <tr>
                                                                                      <th>Référence</th>
                                                                                      <th>Prix</th>
                                                                                      <th>Quantité</th>
                                                                                      <th>Prix Total</th>
                                                                                      <th></th>
                                                                                  </tr>
                                                                              </thead>
                                                                              <tbody>
                                                                                       <?php
                                                                                          $req2 = $bddV->prepare("SELECT * FROM ligne
                                                                                                                          WHERE idPanier =:idPanier
                                                                                                                           ");
                                                                                          $req2->execute(array(
                                                                                                      'idPanier' =>$panier['idPanier']
                                                                                                      )) or die(print_r($req2->errorInfo()));

                                                                                        while ($ligne=$req2->fetch()) {  ?>
                                                                                        <tr>
                                                                                            <td> <?php echo  $ligne['designation']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['prixTotal']; ?>  </td>
                                                                                            <!-- Debut Modification -->
                                                                                            <td width="10px">                                                                                           
                                                                                            <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnRetournerProduit" class="btn btn-danger btn-sm pull-right btnRetournerProduit" data-toggle="modal" data-target= "#retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-remove"></span> Retourner</button>
                                                                                            
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
                                                                                                                    <p><?php echo "Voulez-vous retourner le produit <b>".$ligne['designation']."</b> ? <br>Veuillez entrer la quantité à retourner" ; ?></p>
                                                                                                                    <input type="number" class="form-control" min="0" name="qtRetourner-<?= $ligne['idArticle'];?>" id="qtRetourner-<?= $ligne['idArticle'];?>" placeholder="Quantité à retourner" required>
                                                                                                                    <input type="hidden" name="id" id="id"  <?php echo  "value='".$ligne['idArticle']."_".$panier['idPanier']."'" ; ?>>
                                                                                                                    <input type="hidden" name="quantite-<?= $ligne['idArticle']; ?>" id="quantite-<?= $ligne['idArticle'];?>"  <?php echo  "value='".$ligne['quantite']."'" ; ?>><br><br>
                                                                                                                    <code class="text-error hidden"></code>
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                                    <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-success btnConfRetourProduit-<?= $ligne['idArticle']."_".$panier['idPanier']; ?>">Confirmer</button>
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

                                          <span class="spanDate noImpr"> </span>
                                          <span class="spanDate noImpr"> Livrer le: <?php echo $panier['dateLivrer'];
                                           ?> </span>
                                           <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                             <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']; ?> </span></span>
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
                                                <button type="submit"    class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_rtrn_pagnet".$panier['idPanier'] ; ?>>
                                                        <span class="glyphicon glyphicon-remove"></span>Retourner
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
                                                                    <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                    <button type="submit" name="btnRetournerPagnet" class="btn btn-success">Confirmer</button>
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
                                                          <th>Prix Total</th>
                                                          <th></th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                           <?php
                                                              $req2 = $bddV->prepare("SELECT * FROM ligne
                                                                                              WHERE idPanier =:idPanier
                                                                                               ");
                                                              $req2->execute(array(
                                                                          'idPanier' =>$panier['idPanier']
                                                                          )) or die(print_r($req2->errorInfo()));

                                                            while ($ligne=$req2->fetch()) {  ?>
                                                            <tr>
                                                                <td> <?php echo  $ligne['designation']; ?>  </td>
                                                                <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                <td> <?php echo  $ligne['prixTotal']; ?>  </td>
                                                                <!-- Debut Modification -->
                                                                <td width="10px">                                                                                           
                                                                    <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnRetournerProduit" class="btn btn-danger btn-sm pull-right btnRetournerProduit" data-toggle="modal" data-target= "#retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-remove"></span> Retourner</button>
                                                                    
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
                                                                                        <p><?php echo "Voulez-vous retourner le produit <b>".$ligne['designation']."</b> ? <br>Veuillez entrer la quantité à retourner" ; ?></p>
                                                                                        <input type="number" class="form-control" min="0" name="qtRetourner-<?= $ligne['idArticle'];?>" id="qtRetourner-<?= $ligne['idArticle'];?>" placeholder="Quantité à retourner" required>
                                                                                        <input type="hidden" name="id" id="id"  <?php echo  "value='".$ligne['idArticle']."_".$panier['idPanier']."'" ; ?>>
                                                                                        <input type="hidden" name="quantite-<?= $ligne['idArticle']; ?>" id="quantite-<?= $ligne['idArticle'];?>"  <?php echo  "value='".$ligne['quantite']."'" ; ?>><br><br>
                                                                                        <code class="text-error hidden"></code>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                        <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-success btnConfRetourProduit-<?= $ligne['idArticle']."_".$panier['idPanier']; ?>">Confirmer</button>
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

                                          <span class="spanDate noImpr"> </span>
                                          <span class="spanDate noImpr"> Annuler le: <?php echo $panier['dateAnnuler'];
                                           ?> </span>
                                           <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                           <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']; ?> </span></span>
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
                                                                    <button type="submit" name="btnNewCmdPanier" class="btn btn-success">Confirmer</button>
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
                                                          <th>Prix Total</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                           <?php
                                                              $req2 = $bddV->prepare("SELECT * FROM ligne
                                                                                              WHERE idPanier =:idPanier
                                                                                               ");
                                                              $req2->execute(array(
                                                                          'idPanier' =>$panier['idPanier']
                                                                          )) or die(print_r($req2->errorInfo()));

                                                            while ($ligne=$req2->fetch()) {  ?>
                                                            <tr>
                                                                <td> <?php echo  $ligne['designation']; ?>  </td>
                                                                <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                <td> <?php echo  $ligne['prixTotal']; ?>  </td>
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
                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                            <div class="right-arrow pull-right">+</div>
                                            <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                            <span class="spanDate noImpr"> </span>
                                            <span class="spanDate noImpr"> Retourner le: <?php echo $panier['dateRetourner'];
                                             ?> </span>
                                             <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
                                             <span class="spanTotal noImpr" >Client: <span ><?php echo $client['prenom']; ?> </span></span>
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
                                                                        <button type="submit" name="btnNewCmdPanier" class="btn btn-success">Confirmer</button>
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
                                                            <th>Prix Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                             <?php
                                                                $req2 = $bddV->prepare("SELECT * FROM ligne
                                                                                                WHERE idPanier =:idPanier
                                                                                                 ");
                                                                $req2->execute(array(
                                                                            'idPanier' =>$panier['idPanier']
                                                                            )) or die(print_r($req2->errorInfo()));

                                                              while ($ligne=$req2->fetch()) {  ?>
                                                              <tr>
                                                                  <td> <?php echo  $ligne['designation']; ?>  </td>
                                                                  <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                  <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                  <td> <?php echo  $ligne['prixTotal']; ?>  </td>
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


<?php
    echo'</body></html>';

?>
