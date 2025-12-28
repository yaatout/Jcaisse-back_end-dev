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

session_start();

if(!$_SESSION['iduser']){
	header('Location:../JCaisse/index.php');
}

require('connection.php');

require('declarationVariables.php');

/**********************/


$idEntrepot=htmlspecialchars(trim($_GET['iDS']));
$sql3="SELECT * FROM `".$nomtableEntrepot."` where idEntrepot=".$idEntrepot."";
$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
$entrepot = mysql_fetch_assoc($res3);

// $sql2="SELECT s.quantiteStockCourant,s.nbreArticleUniteStock,i.prixuniteStock,d.prixachat FROM `".$nomtableEntrepotStock."` s
$sql2="SELECT s.quantiteStockCourant,d.nbreArticleUniteStock,d.prixuniteStock,d.prixachat FROM `".$nomtableEntrepotStock."` s
LEFT JOIN `".$nomtableStock."` i ON i.idStock = s.idStock
LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
WHERE s.idEntrepot='".$idEntrepot."' AND d.classe=0 ORDER BY s.idEntrepotStock DESC";
$res2=mysql_query($sql2);
$montantPA=0;
$montantPU=0;
while ($stock = mysql_fetch_array($res2)) {
  if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0 && $stock['quantiteStockCourant']>0){
    $quantite=$stock['quantiteStockCourant'] / $stock['nbreArticleUniteStock'];
  }
  else{
    $quantite=0;
  }

/*   if ($_SESSION['idBoutique']==119) {
    $montantPA=$montantPA + ($quantite * $stock['prixachat']);
  } else {
    $montantPA=$montantPA + ($quantite * $stock['prixachat']*$stock['nbreArticleUniteStock']);
  } */
  $montantPA=$montantPA + ($quantite * $stock['prixachat']);
  $montantPU=$montantPU + ($quantite * $stock['prixuniteStock']);
}


/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 

?>
<body >
   <?php require('header.php'); ?>

   <input id="inpt_Entrepot_id" type="hidden" value="<?= $idEntrepot?>" />

  <div class="container">
<!-- 
    <div class="panel">
        <center> 
            <h4>
                Valeur Stock en Prix de Reviens =<span id="montantAchats"></span> <=> Valeur Stock en Prix de vente =  <span id="montantVentes"></span>
            </h4>
        </center> 
    </div> -->
    
  </div>
 
  <div class="container">
    <br />
    <ul class="nav nav-tabs">
          <h3 class="alert alert-info"> Entrepot : <? echo  $entrepot['nomEntrepot'];   ?> </h3>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">   
            <br />
            <div class="table-responsive">  
                <div class="container row">
                  <a class="col-md-12 alert alert-info" href="#">
                    <?php 
                        if($_SESSION['proprietaire']==1){ 
                          echo ' <span style="color:blue;"> Valeur Stock (PA) =  '.number_format($montantPA, 2, ',', ' ').' FCFA </span>';  
                          echo ' <=> <span style="color:green;"> Valeur Stock (PU) =  '.number_format($montantPU, 2, ',', ' ').' FCFA </span>';
                        }
                    ?>
                  </a>
                </div>

                <table id="listeStock" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>IdStock</th>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Quantite</th>
                          <th>Unite Stock (US)</th>
                          <th>Nombre Articles (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
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

<script type="text/javascript" src="scripts/entrepotStock.js"></script>
