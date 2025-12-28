<?php
session_start();

if(!$_SESSION['iduser']){
  header('Location:../../index.php');
}

if($_SESSION['vitrine']==0){
  header('Location:../accueil.php');
}

require('../connection.php');
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
// var_dump(mb_strtoupper('Expédition'));
// function find_p_with_position($pns,$des) {
//     foreach($pns as $index => $p) {
//         if(($p['designation'] == $des)){
//           // if ($p['unite'] == $u) {
//           //   return $index;
//           // } else {
//           //   // $nbr = $p['nbreArticleUniteStock'];
//             return $index;
//           // }
//         }
//     }
//     return FALSE;
//   }
// if (isset($_POST['btnConfirmerCommande'])) {
//         $idPanier=$_POST['idPanier'];
//         $confirmer=1;
//       $req1 = $bddV->prepare("UPDATE panier SET
//                             confirmer=:confirmer,
//                             dateConfirmer= NOW()
//                             WHERE idPanier=:idPanier ");
//       $req1->execute(array(
//                         'confirmer' => $confirmer,
//                         'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
//       $req1->closeCursor();
//   }
  if (isset($_POST['btnExpCommande'])) {
        $idPanier=$_POST['idPanier'];
        // $confirmer=1;
        $expedition=1;
        $req1 = $bddV->prepare("UPDATE panier SET
                                expedition=:expedition,
                                dateExpedition= NOW()
                                WHERE idPanier=:idPanier ");
        $req1->execute(array(
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
        $idPagnet=htmlspecialchars(trim($_POST['idPanier']));
        $retourner=1;
        $confirmer=0;

        $sqlP="SELECT * FROM `".$nomtablePagnet."` where idVitrine=".$idPagnet." ";
        $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
        $pagnet = mysql_fetch_assoc($resP) ;

        $sqlL="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$pagnet['idPagnet']." ";
        $resL = mysql_query($sqlL) or die ("persoonel requête 2".mysql_error());
        //$ligne = mysql_fetch_assoc($resL) ;

        if($pagnet['type']==11){
            while ($ligne=mysql_fetch_assoc($resL)){
                if($ligne['idStock']!=0 && $ligne['classe']==0){
                    $sqlS="SELECT * FROM `".$nomtableStock."` where idStock=".$ligne['idStock'];
                    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                    if(mysql_num_rows($resS)){
                        $stock = mysql_fetch_assoc($resS) or die ("select stock impossible =>".mysql_error());
                        $idDesignation=$stock["idDesignation"];
                        $sqlD="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$idDesignation;
                        $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                        $designation = mysql_fetch_assoc($resD);
                        if(mysql_num_rows($resD)){
                            $quantiteStockCourant=$stock['quantiteStockCourant'];
                            $uniteStock=$stock['uniteStock'];
                            if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
                                $quantiteCourant=$quantiteStockCourant + ($designation['nbreArticleUniteStock'] * $ligne['quantite']);
                                if($quantiteStockCourant >= 0){
                                    $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    //on fait la suppression de cette ligne dans la table ligne
                                    $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne']."  ";
                                    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
                                }
                            }
                            else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                                $quantiteCourant=$quantiteStockCourant + $ligne['quantite'];
                                if($quantiteStockCourant >= 0){
                                    $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteCourant." where idStock=".$ligne['idStock'];
                                    $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                    //on fait la suppression de cette ligne dans la table ligne
                                    $sql3="DELETE FROM `".$nomtableLigne."` where numligne=".$ligne['numligne']."  ";
                                    $res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());
                                }
                            }

                        }
                    }
                }
                else{
                    if($ligne['classe']==0){
                        $sqlS="SELECT * FROM `".$nomtableDesignation."` where idDesignation=".$ligne['idDesignation'];
                        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
                        $designation = mysql_fetch_assoc($resS) ;
                            if(mysql_num_rows($resS)){
                                if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
                                    $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
                                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                                    $retour=$ligne['quantite']*$designation['nbreArticleUniteStock'];
                                    while ($stock = mysql_fetch_assoc($resD)) {
                                        if($retour>= 0){
                                            $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
                                            if($stock['totalArticleStock'] >= $quantiteStockCourant){
                                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                            }
                                            else{
                                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];
                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                            }
                                            $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
                                        }
                                    }

                                }
                                else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                                    $sqlD="SELECT * FROM `".$nomtableStock."` where idDesignation='".$ligne['idDesignation']."' ORDER BY idStock DESC ";
                                    $resD=mysql_query($sqlD) or die ("select stock impossible =>".mysql_error());
                                    $retour=$ligne['quantite'];
                                    while ($stock = mysql_fetch_assoc($resD)) {
                                        if($retour >= 0){
                                            $quantiteStockCourant = $stock['quantiteStockCourant'] + $retour;
                                            if($stock['totalArticleStock'] >= $quantiteStockCourant){
                                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$quantiteStockCourant." where idStock=".$stock['idStock'];
                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());
                                            }
                                            else{
                                                $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=".$stock['totalArticleStock']." where idStock=".$stock['idStock'];
                                                $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

                                            }
                                            $retour=($retour + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;

                                        }

                                    }

                                }

                            }
                    }
                }
            }
        }
        $req1 = $bddV->prepare("UPDATE panier SET
              retourner=:retourner ,
              confirmer=:confirmer,
              dateRetourner=NOW()
              WHERE idPanier=:idPanier ");
        //var_dump($req1);
        $req1->execute(array(
                            'retourner' => $retourner,
                            'confirmer' => $confirmer,
                            'idPanier' => $idPagnet )) or die(print_r($req1->errorInfo()));
        $req1->closeCursor();
  }
  if (isset($_POST['btnAnnulerPagnet'])) {
        $idPanier=$_POST['idPanier'];
        $annuler=1;
        $confirmer=0;
      $req1 = $bddV->prepare("UPDATE panier SET
        annuler=:annuler,
       confirmer=:confirmer,
       dateAnnuler=NOW()
       WHERE idPanier=:idPanier ");
      $req1->execute(array(
        'confirmer' => $confirmer,
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

   <div class="container" >
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
                                          $stockIns = 0;
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

                                                        <span class="spanDate noImpr"> </span>
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

                                                                <span class="spanDate noImpr"> </span>
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
                                                                                                <button type="button" id="<?= $panier['idPanier']; ?>" class="btn btn-success btnConfAjoutArticle">Ajouter</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <!--******************* Fin ajouter article *******************-->
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
                                                                        <!--*******************************Debut confirmer ****************************************-->

                                                                          <button class="btn btn-success pull-right btnConf" id="<?= $panier['idPanier'];?>" style="margin-right:20px;">
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
                                                                                      <!-- <form class="form-inline noImpr"  id="factForm" method="post"  > -->
                                                                                          <div class="modal-body">
                                                                                              <p><?php echo "Voulez-vous confirmer la commande numéro <b>".$panier['idPanier']."</b>" ; ?></p>
                                                                                              <input type="hidden" name="idPanier" id="idPanier-<?= $panier['idPanier'];?>"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                          </div>
                                                                                          <div class="modal-footer">
                                                                                              <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                              <button type="button" name="btnConfirmerCommande" class="btn btn-success btnConfirmerCommande-<?= $panier['idPanier'];?>">Confirmer</button>
                                                                                          </div>
                                                                                      <!-- </form> --> 
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

                                                                                            $req0 = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."` WHERE idDesignation =:idDesignation ");
                                                                                            $req0->execute(array(
                                                                                                'idDesignation' =>$ligne['idDesignation']
                                                                                                )) or die(print_r($req0->errorInfo()));
                                                                                            $result=$req0->fetch();
                                                                                            // var_dump($result);
                                                                                            $nbr = $result['nbreArticleUniteStock'];
                                                                                            $us = $result['uniteStock'];
                                                                                            $uniteDetails = $result['uniteDetails'];
                                                                                            // var_dump($us);
                                                                                            ?>
                                                                                        <tr>
                                                                                            <td> <?php echo mb_strtoupper($ligne['designation']); ?>  </td>
                                                                                            <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                                            <?php
                                                                                                if (find_p_with_position($articlesJcaisse, $ligne['idDesignation']) !==FALSE) {
                                                                                                    $c = 0;
                                                                                                    $i=find_p_with_position($articlesJcaisse, $ligne['idDesignation']);
                                                                                                    if ($ligne['unite'] !== 'Article' && $ligne['unite'] !== 'article' && $ligne['unite'] !== '' && $ligne['unite'] !== $uniteDetails) {
                                                                                                      $qtLigne = $ligne['quantite'] * $articlesJcaisse[$i]['nbreArticleUniteStock'];
                                                                                                      if ($qtLigne > $articlesJcaisse[$i]['stockTotal']) {
                                                                                                        $stockIns++;
                                                                                                        $c = 1;
                                                                                                        ?>
                                                                                                        <td class="alert alert-danger qtite"> <?php echo  $ligne['quantite']; ?><code class="code"> Stock insufisant </br> Quantité disponilbe : <?= $articlesJcaisse[$i]['stockTotal']; ?></code></td>
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
                                                                                                        $stockIns++;
                                                                                                        $c = 1;
                                                                                                        ?>
                                                                                                            <td class="alert alert-danger qtite"> <?php echo  $ligne['quantite']; ?><code class="code"> Stock insufisant </br> Quantité disponilbe : <?= $articlesJcaisse[$i]['stockTotal']; ?></code> </td>
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
                                                                                            <!-- Debut Modification -->
                                                                                            <td width="200px">
                                                                                                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                                                                                    <div class="btn-group mr-2" role="group" aria-label="First group">
                                                                                                        <input type="hidden" id="us-<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" value="<?= $us; ?>">
                                                                                                        <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnEditerProduit" class="btn btn-info btn-sm pull-right btnEditerProduit" data-toggle="modal" data-target= "#editerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-edit"></span> Editer aticle</button>
                                                                                                     </div>
                                                                                                    <div class="btn-group mr-2" role="group" aria-label="Second group">
                                                                                                        <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" name="btnRetournerProduit" class="btn btn-danger btn-sm pull-right btnRetournerProduit" data-toggle="modal" data-target= "#retournerProduit-<?= $ligne['idArticle']."_".$panier['idPanier'] ; ?>"><span class="glyphicon glyphicon-remove"></span> Retirer</button>
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
                                                                                                                    <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-danger btnConfRetourProduit-<?= $ligne['idArticle']."_".$panier['idPanier']; ?>">Confirmer</button>
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
                                                                                                                    <button type="button" id="<?= $ligne['idArticle']."_".$panier['idPanier']; ?>" class="btn btn-success btnConfEditProduit">Confirmer</button>
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

                                                                              <button class="btn btn-success  pull-right" style="margin-right:20px;" data-toggle="modal" <?php echo  "data-target=#msg_exp_commande".$panier['idPanier'] ; ?>>
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
                                                                                                  <input type="hidden" name="idPanier" id="idPanier"  <?php echo  "value='".$panier['idPanier']."'" ; ?>>
                                                                                              </div>
                                                                                              <div class="modal-footer">
                                                                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                                  <button type="submit" name="btnExpCommande" class="btn btn-success">Confirmer</button>
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
                                                                                          $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier  ORDER BY idArticle DESC");
                                                                                          $req2->execute(array(
                                                                                                      'idPanier' =>$panier['idPanier']
                                                                                                      )) or die(print_r($req2->errorInfo()));

                                                                                        while ($ligne=$req2->fetch()) {  ?>
                                                                                        <tr>
                                                                                            <td> <?php echo  mb_strtoupper($ligne['designation']); ?>  </td>
                                                                                            <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['unite']; ?>  </td>
                                                                                            <td> <?php echo  $ligne['prixTotal']; ?>  </td>
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
                                                <!--*******************************Fin Livrer panier****************************************-->
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
                                                              $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier ORDER BY idArticle DESC");
                                                              $req2->execute(array(
                                                                          'idPanier' =>$panier['idPanier']
                                                                          )) or die(print_r($req2->errorInfo()));

                                                            while ($ligne=$req2->fetch()) {  ?>
                                                            <tr>
                                                                <td> <?php echo  mb_strtoupper($ligne['designation']); ?>  </td>
                                                                <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                <td> <?php echo  $ligne['unite']; ?>  </td>
                                                                <td> <?php echo  $ligne['prixTotal']; ?>  </td>
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
                                                          <th>Unité</th>
                                                          <th>Prix Total</th>
                                                          <th></th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                           <?php
                                                              $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier ORDER BY idArticle DESC");
                                                              $req2->execute(array(
                                                                          'idPanier' =>$panier['idPanier']
                                                                          )) or die(print_r($req2->errorInfo()));

                                                            while ($ligne=$req2->fetch()) {  ?>
                                                            <tr>
                                                                <td> <?php echo  mb_strtoupper($ligne['designation']); ?>  </td>
                                                                <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                <td> <?php echo  $ligne['unite']; ?>  </td>
                                                                <td> <?php echo  $ligne['prixTotal']; ?>  </td>
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
                                                          <th>Unité</th>
                                                          <th>Prix Total</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                           <?php
                                                              $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier ORDER BY idArticle DESC");
                                                              $req2->execute(array(
                                                                          'idPanier' =>$panier['idPanier']
                                                                          )) or die(print_r($req2->errorInfo()));

                                                            while ($ligne=$req2->fetch()) {  ?>
                                                            <tr>
                                                                <td> <?php echo  mb_strtoupper($ligne['designation']); ?>  </td>
                                                                <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                <td> <?php echo  $ligne['unite']; ?>  </td>
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
                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panierR".$panier['idPanier']."" ; ?>  class="panel-title expand">
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
                                                            <th>Unité</th>
                                                            <th>Prix Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                             <?php
                                                                $req2 = $bddV->prepare("SELECT * FROM ligne WHERE idPanier =:idPanier ORDER BY idArticle DESC");
                                                                $req2->execute(array(
                                                                            'idPanier' =>$panier['idPanier']
                                                                            )) or die(print_r($req2->errorInfo()));

                                                              while ($ligne=$req2->fetch()) {  ?>
                                                              <tr>
                                                                  <td> <?php echo  mb_strtoupper($ligne['designation']); ?>  </td>
                                                                  <td> <?php echo  $ligne['prix']; ?>  </td>
                                                                  <td> <?php echo  $ligne['quantite']; ?>  </td>
                                                                  <td> <?php echo  $ligne['unite']; ?>  </td>
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
