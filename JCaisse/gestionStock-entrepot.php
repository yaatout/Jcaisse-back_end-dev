<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/

if (isset($_POST['excelFichier'])) {

    header('Content-Type: text/csv; charset=utf-8');

    header('Content-Disposition: attachment; filename=' . $_SESSION["labelB"] . ' ' . $dateString2 . '.csv');

    $delimiter = ";";

    $output = fopen("php://output", "w");

    $texte = strtoupper($_SESSION["labelB"]) . "  :  JOURNAL DE CAISSE du " . $dateString2 . " ";

    $entete = array($texte);

    fputcsv($output, $entete, $delimiter);

    $titre = array('DESIGNATION', 'CATEGORIE', 'PRIX ACHAT', 'PRIX', 'RESTANT', 'VENDU');

    fputcsv($output, $titre, $delimiter);

    $contenu = array();


    $sql = "SELECT d.idDesignation,d.designation FROM `" . $nomtableDesignation . "` d
  LEFT JOIN `" . $nomtableEntrepotStock . "` s ON s.idDesignation = d.idDesignation
  WHERE d.classe=0  AND (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0)  GROUP BY d.idDesignation ORDER BY d.designation ASC ";
    $res = mysql_query($sql);


    while ($produit = mysql_fetch_array($res)) {

        $sql1 = "SELECT * FROM `" . $nomtableDesignation . "` where idDesignation='" . $produit['idDesignation'] . "'";
        $res1 = mysql_query($sql1);
        $designation = mysql_fetch_array($res1);

        $sqlS = "SELECT SUM(quantiteStockCourant) FROM `" . $nomtableEntrepotStock . "` where idDesignation ='" . $produit['idDesignation'] . "'  ";
        $resS = mysql_query($sqlS) or die("select stock impossible =>" . mysql_error());
        $S_stock = mysql_fetch_array($resS);

        $sqlV = "SELECT *
    FROM `" . $nomtableLigne . "` l
    INNER JOIN `" . $nomtableDesignation . "` d ON d.idDesignation = l.idDesignation
    INNER JOIN `" . $nomtablePagnet . "` p ON p.idPagnet = l.idPagnet
    where p.verrouiller=1 && (p.type=0 || p.type=1 || p.type=30) && p.datepagej='" . $dateString2 . "' && d.idDesignation='" . $produit["idDesignation"] . "' ";
        $resV = mysql_query($sqlV) or die("select stock impossible =>" . mysql_error());
        $quantite = 0;
        while ($vente = mysql_fetch_array($resV)) {
            if ($vente['unitevente'] == $designation['uniteStock']) {
                $quantite = $quantite + ($vente['quantite'] * $designation['nbreArticleUniteStock']);
            } else if ($vente['unitevente'] == 'Demi Gros') {
                $quantite = $quantite + (($vente['quantite'] * $designation['nbreArticleUniteStock']) / 2);
            } else {
                $quantite = $quantite + $vente['quantite'];
            }
        }

        $contenu[0] = $designation['designation'];
        $contenu[1] = $designation['categorie'];
        $contenu[3] = $designation['prixachat'];
        $contenu[4] = $designation['prix'];
        $contenu[5] = ($S_stock[0] / $designation['nbreArticleUniteStock']);
        $contenu[6] = ($quantite / $designation['nbreArticleUniteStock']);

        fputcsv($output, $contenu, $delimiter);
    }

    fclose($output);
    exit;
}

require('entetehtml.php');
?>

<body>
    <?php require('header.php'); ?>

    <div class="container">
        <center>
            <button type="button" class="btn btn-success" id="btn_lister_Produit">
                <i class="glyphicon glyphicon-plus"></i>Ajouter un Stock
            </button>
        </center>

        <?php
        if ($_SESSION['idBoutique'] == 194) { ?>
        <a class="btn btn-info btn-lg" href="transfertStockEntrepot.php">Transfert</a>
        <?php
        }
        ?>

        <div class="modal fade" id="lister_Produit" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding-bottom:5px;">
                            <div class="col-sm-6 form-group">
                                <label class="col-sm-3" style="color:blue;"> Fournisseur </label>
                                <select style="width: 200px;" class="form-control"
                                    onchange="choix_BL_Fournisseur(this.value)" id="slct_Stock_Fournisseur">
                                </select>
                            </div>
                            <div class="col-sm-6 pull-right form-group">
                                <label class="col-sm-4" for="idBl" style="color:blue;"> Numero BL / Date Arrivee
                                </label>
                                <select style="width: 200px;" class="form-control" id="slct_Stock_BL">
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="listeProduits" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                    <tr>
                                        <th>IdDesignation</th>
                                        <th>Reference</th>
                                        <th>Quantite</th>
                                        <th>Unite Stock (US)</th>
                                        <th>Prix_(US)</th>
                                        <th>Prix_Achat</th>
                                        <th>Entrepot</th>
                                        <th>Expiration</th>
                                        <th>Operations</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="container">
        <br />
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS
                    <?php
                    if ($_SESSION['proprietaire'] == 1) {
                    }
                    ?>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
                <div class="table-responsive">
                    <?php if ($_SESSION['proprietaire'] == 1) {  ?>
                    <div class="container row">
                        <br />
                        <div id="accordion" style="background-color:white">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            VALEUR STOCK
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        <!-- <a class="col-md-12 alert alert-info" href="#"> -->
                                        <a href="#" style="margin:15px;">
                                            <?php
                                            if ($_SESSION['proprietaire'] == 1) {
                                                echo  ' Valeur Stock en Prix de reviens =  <span id="montantAchats"></span> <=> Valeur Stock en Prix de vente =  <span id="montantVentes"></span> <br>';
                                            }
                                            ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><br>
                    <?php  } ?>
                    <table id="listeStock" class="display tabStock" class="tableau3" width="100%" border="1">
                        <thead>
                            <tr>
                                <th>IdStock</th>
                                <th>IdDesignation</th>
                                <th>Reference</th>
                                <th>Sans Depot</th>
                                <th>Sur Depot</th>
                                <th>Unite Stock (US)</th>
                                <th>Nombre Articles (US)</th>
                                <th>Prix (US)</th>
                                <th>Prix Achat</th>
                                <th>Operations</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script type="text/javascript" src="scripts/gestionStock_ET.js"></script>

<?php

/* Debut PopUp d'Alerte sur l'ensemble de la Page **/
if (isset($msg_info)) {
    echo "<script type='text/javascript'>
                  $(window).on('load',function(){
                      $('#msg_info').modal('show');
                  });
              </script>";
    echo '<div id="msg_info" class="modal fade " role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header panel-primary">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Alerte</h4>
                        </div>
                        <div class="modal-body">
                            <p>' . $msg_info . '</p>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                </div>
            </div>';
}
/** Fin PopUp d'Alerte sur l'ensemble de la Page **/


?>