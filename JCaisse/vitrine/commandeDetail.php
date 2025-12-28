<?php
session_start();
if(!$_SESSION['iduser']){
	header('Location:../../index.php');
    }

require('../connection.php');
require('../connectionVitrine.php');
require('../declarationVariables.php');

/**********************/
/**Debut informations sur la date d'Aujourdhui **/
    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
    $date->setTimezone($timezone);
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $heureString=$date->format('H:i:s');
    $dateString=$annee.'-'.$mois.'-'.$jour;
    $dateString2=$jour.'-'.$mois.'-'.$annee;
/**Fin informations sur la date d'Aujourdhui **/

$nomtableClient=$_SESSION['nomB']."-client";
$nomtableVersement=$_SESSION['nomB']."-versement";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableBon=$_SESSION['nomB']."-bon";

$doublons=NULL;
$client=NULL;
$idClient=NULL;
$idGuest=NULL;
$guest=NULL;
if (isset($_GET['c'])) {
    $idClient=htmlspecialchars(trim($_GET['c']));
    $req2 = $bddV->prepare("SELECT * FROM client  WHERE idClient = :idClient ");
    $req2->execute(array('idClient' =>$idClient)) or die(print_r($req2->errorInfo()));
    $client=$req2->fetch();
    # code...
} elseif (isset($_GET['g'])) {
    # code...
    $idGuest=htmlspecialchars(trim($_GET['g']));

    $req2 = $bddV->prepare("SELECT * FROM guest  WHERE idGuest = :idGuest ");
    $req2->execute(array('idGuest' =>$idGuest)) or die(print_r($req2->errorInfo()));
    $guest=$req2->fetch();
}


//var_dump($client);

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

 //require('bonPclient-pharmacie.php');

}
else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

    //require('bonPclient-entrepot.php');

}

else{

/*if (isset($_POST['btnConfirmerCommande'])) {
        $idPanier=$_POST['idPanier'];
        $confirmer=1;
      $req1 = $bddV->prepare("UPDATE panier SET confirmer=:confirmer WHERE idPanier=:idPanier ");
      $req1->execute(array(
                           'confirmer' => $confirmer,
                           'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
      $req1->closeCursor();
  }
if (isset($_POST['btnLivrerCommande'])) {
        $idPanier=$_POST['idPanier'];
        $livrer=1;
      $req1 = $bddV->prepare("UPDATE panier SET livrer=:livrer WHERE idPanier=:idPanier ");
      $req1->execute(array(
                           'livrer' => $livrer,
                           'idPanier' => $idPanier )) or die(print_r($req1->errorInfo()));
      $req1->closeCursor();
  }*/
require('../entetehtmlVitrine.php');
?>
<!-- Debut Code HTML -->
<body onLoad="">
<header>
    <?php require('../header.php'); ?>
    <!-- Debut Container HTML -->
    <div class="container" >


        <div class="jumbotron noImpr">
           <?php
                if (isset($client)) { ?>
                    <h2>Les Operations du client : <?php echo strtoupper($client['prenom'])." ".strtoupper($client['nom']); ?> </h2>
                <?php } elseif (isset($guest)) { ?>
                    <h2>Les Operations de l'invité : <?php echo strtoupper($guest['nomComplet']); ?> </h2>

                <?php }

             ?>
            <div class="panel-group">
                        <div class="panel" style="background:#cecbcb;">
                            <div class="panel-heading" >
                                <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse1">Contacts :    </a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-heading" style="margin-left:2%;">
                                    <?php
                                        if (isset($client)) {
                                        $req5 = $bddV->prepare("SELECT sum(prixTotal) FROM panier p
                                    INNER JOIN ligne l ON l.idPanier = p.idPanier
                                    WHERE l.idBoutique =:idBoutique
                                    AND idClient =:idClient ");

                                        $req5->execute(array(
                                            'idBoutique' =>$_SESSION['idBoutique'],
                                            'idClient' =>$client['idClient']
                                            )) or die(print_r($req5->errorInfo()));
                                         $TotalB=$req5->fetch();
                                       /* while ($panier=$req5->fetch()) {
                                            //echo "<pre>";
                                           print_r($panier) ;
                                           //echo "</pre>";
                                        }*/
                                            ?>
                                            <h4>Téléphone : <?php echo $client['telephone']; ?></h4>
                                            <h4>Total des Paniers : <?php echo $TotalB[0]; ?></h4>
                                        <?php } elseif (isset($guest)) {
                                        $req5 = $bddV->prepare("SELECT sum(prixTotal) FROM panier p
                                            INNER JOIN ligne l ON l.idPanier = p.idPanier
                                            WHERE l.idBoutique =:idBoutique
                                            AND idGuest =:idGuest");

                                        $req5->execute(array(
                                            'idBoutique' =>$_SESSION['idBoutique'],
                                            'idGuest' =>$guest['idGuest']
                                            )) or die(print_r($req5->errorInfo()));
                                         $TotalB=$req5->fetch();

                                            ?>
                                            <h4>Téléphone : <?php echo $guest['telephone']; ?></h4>
                                            <h4>Total des Paniers : <?php echo $TotalB[0]; ?></h4>
                                        <?php }

                                    ?>

                                </div>
                            </div>

                        </div>
            </div>
            <div class="panel-group">

            </div>
        </div>


        <!-- Debut Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->
            <style >
                /* Firefox */
                input[type=number] {
                    -moz-appearance: textfield;
                }
                /* Chrome */
                input::-webkit-inner-spin-button,
                input::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin:0;
                }
                /* Opéra*/
                input::-o-inner-spin-button,
                input::-o-outer-spin-button {
                    -o-appearance: none;
                    margin:0
                }
            </style>
        <!-- Fin Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->

        <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
            <script >
                $(function() {
                $(".expand").on( "click", function() {
                    // $(this).next().slideToggle(200);
                    $expand = $(this).find(">:first-child");

                    if($expand.text() == "+") {
                    $expand.text("-");
                    } else {
                    $expand.text("+");
                    }
                });
                });
            </script>
        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->

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
                <?php if (isset($client)): ?>
                            <?php /*********************************************************************************/ ?>
                            <?php /******************************DEBUT CLIENT***************************************/ ?>
                            <!-- REcherchede tous les panier du client -->
                            <?php
                                $req1 = $bddV->prepare("SELECT * FROM panier p
                                    INNER JOIN ligne l ON l.idPanier = p.idPanier
                                    WHERE l.idBoutique =:idBoutique
                                    AND idClient =:idClient AND p.livrer=0
                                    GROUP BY p.idPanier DESC");
                                        $req1->execute(array(
                                            'idBoutique' =>$_SESSION['idBoutique'],
                                            'idClient' =>$client['idClient']
                                            )) or die(print_r($req1->errorInfo()));

                                        /*while ($panier=$req1->fetch()) {

                                            $doublons[] =$panier['idPanier'];
                                        }
                                        $panierS=array_unique ($doublons);*/

                            ?>
                            <!-- Fin  recherchede tous les panier du client -->

                            <!-- Debut Boucle while concernant les Paniers en cours (1 aux maximum) -->
                                <?php
                                    $cpt=0;
                                    while ($panier=$req1->fetch()) {
                                        $cpt++; ?>
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading">
                                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                                            <div class="right-arrow pull-right">+</div>
                                                            <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                                            <span class="spanDate noImpr"> </span>
                                                            <span class="spanDate noImpr"> Date: <?php echo $panier['dateHeure']; ?> </span>
                                                            <span class="spanDate noImpr"> </span>
                                                             <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
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

                                <?php   } ?>
                            <!-- Fin Boucle while concernant les Paniers en cours  -->
                            <?php /******************************FIN CLIENT***************************************/ ?>
                            <?php /*******************************************************************************/ ?>

                <?php elseif (isset($guest)): ?>

                        <?php /*********************************************************************************/ ?>
                        <?php /******************************DEBUT INVITE***************************************/ ?>
                        <!-- Recherchede tous les panier de l'invité -->
                        <?php
                            $req3 = $bddV->prepare("SELECT * FROM panier p
                                INNER JOIN ligne l ON l.idPanier = p.idPanier
                                WHERE l.idBoutique =:idBoutique
                                AND idGuest =:idGuest
                                GROUP BY p.idPanier");
                                    $req3->execute(array(
                                        'idBoutique' =>$_SESSION['idBoutique'],
                                        'idGuest' =>$guest['idGuest']
                                        )) or die(print_r($req3->errorInfo()));

                        ?>
                        <!-- Fin  recherchede tous les panier du client -->

                        <!-- Debut Boucle while concernant les Paniers en cours  -->
                            <?php
                                $cpt=0;
                                while ($panier=$req3->fetch()) {
                                    $cpt++; ?>
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#panier".$panier['idPanier']."" ; ?>  class="panel-title expand">
                                                        <div class="right-arrow pull-right">+</div>
                                                        <a href="#"> Commande N : <?php echo ''.$panier['idPanier']; ?>

                                                        <span class="spanDate noImpr"> </span>
                                                        <span class="spanDate noImpr"> Date: <?php echo $panier['dateHeure']; ?> </span>
                                                        <span class="spanDate noImpr"> </span>
                                                         <span class="spanTotal noImpr" >Total panier: <span ><?php echo $panier['total']; ?> </span></span>
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
                                                                                $req4 = $bddV->prepare("SELECT * FROM ligne
                                                                                                                WHERE idPanier =:idPanier
                                                                                                                 ");
                                                                                $req4->execute(array(
                                                                                            'idPanier' =>$panier['idPanier']
                                                                                            )) or die(print_r($req4->errorInfo()));

                                                                              while ($ligne=$req4->fetch()) {  ?>
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

                            <?php   } ?>
                        <!-- Fin Boucle while concernant les Paniers en cours  -->
                        <?php /******************************FIN INVITE***************************************/ ?>
                        <?php /*******************************************************************************/ ?>
                <?php endif ?>

            </div>
        <!-- Fin de l'Accordion pour Tout les Paniers -->

        <?php /*****************************/
        echo''.
        '<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';
        ?>

        <!-- Debut PopUp d'Alerte sur l'ensemble de la Page -->
            <?php
                if(isset($msg_info)) {
                echo"<script type='text/javascript'>
                            $(window).on('load',function(){
                                $('#msg_info').modal('show');
                            });
                        </script>";
                echo'<div id="msg_info" class="modal fade " role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header panel-primary">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Alerte</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>'.$msg_info.'</p>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>
                            </div>
                        </div>';

                }
            ?>
        <!-- Fin PopUp d'Alerte sur l'ensemble de la Page -->

        <!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
            <div id="msg_info_js" class="modal fade " role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header panel-primary">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Alerte</h4>
                            </div>
                            <div class="modal-body">
                                <p>IMPOSSIBLE.</br>
                                </br> La quantité de ce stock est insuffisant pour la ligne.
                                </br> Il vous reste  <span id="qte_stock"></span> Unités dans le Stock.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                            </div>
                    </div>
            </div>
        <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

    </div>
    <!-- Fin Container HTML -->
</header>
</body>
<!-- Fin Code HTML -->

 <?php
   }
  ?>
