<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Mor Mboup
Date de création : 21/08/2023
Date dernière modification : 21/08/2023
*/

session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');

/**********************/


$sql1="SELECT i.idInventaire,i.idDesignation,i.quantite,i.quantiteStockCourant,d.prix from `".$nomtableInventaire."` i
INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation order by i.idInventaire desc";
$res1=mysql_query($sql1);
$plus=0;
$moins=0;
while ($inventaire = mysql_fetch_array($res1)) {
  if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
    if($inventaire['quantite'] > $inventaire['quantiteStockCourant']){
      $plus = $plus + (($inventaire['quantite'] - $inventaire['quantiteStockCourant']) * $inventaire['prix']);
    }
    else if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
      $moins = $moins + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
    }
  }
}


/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 

echo'
   <body >';
   require('header.php');
   ?>

   <?php
   if ($_SESSION['caisse']==1 || $_SESSION['proprietaire']==1){
   
   }
   ?>
   


<?php

//if($_SESSION['enConfiguration'] ==0){
echo'<div class="container" >';

  echo '<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#INVENTAIRE"> Inventaire du Stock </a></li>
    <li id="invDEvent">
      <a data-toggle="tab" href="#INVENTAIREDETAIL"> Details Inventaire du Stock';
        if($_SESSION['proprietaire']==1){ 
           echo ' => Valeur Inventaire = '.number_format((($moins - $plus) * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
        }
        echo '
      </a>
    </li>
  </ul>
  <div class="tab-content">
      <div class="tab-pane fade in active" id="INVENTAIRE">';
      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
        echo'<center class="row" style="margin-bottom:10px;">     
          <form class="col-md-4" target="_blank" style="margin-top:10px;" method="post" action="pdfInventaireDesignation_Pharmacie.php">
            <button type="submit" class="btn btn-default" >
                <span class="glyphicon glyphicon-download-alt"></span> PDF classé par Désignation
            </button>
          </form>  
          <form class="col-md-4" target="_blank" style="margin-top:10px;" method="post" action="pdfInventaireCategorie_Pharmacie.php">
            <button type="submit" class="btn btn-primary" >
                <span class="glyphicon glyphicon-download-alt"></span> PDF classé par Categorie
            </button>
          </form>    
          <form class="col-md-4" target="_blank" style="margin-top:10px;" method="post" action="pdfInventaireForme_Pharmacie.php">
            <button type="submit" class="btn btn-primary" >
                <span class="glyphicon glyphicon-download-alt"></span> PDF classé par Forme
            </button>
          </form>    
        </center> ';
       
       }
        echo'<div class="table-responsive">
          <label class="pull-left" for="nbEntreeInvP">Nombre entrées </label>
          <select class="pull-left" id="nbEntreeInvP">
          <optgroup>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option> 
          </optgroup>       
          </select>
          <input class="pull-right" type="text" name="" id="searchInputInvP" placeholder="Rechercher...">
          <div id="resultsInventairesP"><!-- content will be loaded here -->
            <div class="loading-divI"></div>
          </div>
        </div>
      </div>
  </div>
</div>';
?>

<div id="modifierStockEntrepot" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modification Stock Entrepot</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form class="form" >
            <div class="form-group">
              <input type="hidden"  id="idStockEntrepot_Mdf"  />
              <input type="hidden"  id="ordreStockEntrepot_Mdf"  />
            </div>
            <div class="form-group">
              <label for="idEntrepot_Mdf">Entrepot </label>
              <select class="form-control" id="idEntrepot_Mdf">
                  <option selected></option>
                  <?php
                      $sql11="SELECT * FROM `". $nomtableEntrepot."`";
                      $res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                      while($ligne2 = mysql_fetch_row($res11)) {
                          echo'<option  value= "'.$ligne2['0'].'">'.$ligne2['1'].'</option>';
                        } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="quantite_Mdf">Quantite </label>
              <input type="text" class="inputbasic form-control" id="quantite_Mdf" />
            </div>
            <div class="modal-footer row">
              <div class="col-sm-3 "> <input type="button" id="btn_mdf_StockEntrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
              <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<div id="supprimerStockEntrepot" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Suppression Stock Entrepot</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form class="form" >
            <div class="form-group">
              <input type="hidden"  id="idStockEntrepot_Spm"  />
              <input type="hidden"  id="ordreStockEntrepot_Spm"  />
            </div>
            <div class="form-group">
              <label for="idEntrepot_Spm">Entrepot </label>
              <input type="text" class="form-control" id="idEntrepot_Spm"  disabled="true" />
            </div>
            <div class="form-group">
              <label for="quantite_Spm">Quantite </label>
              <input type="text" class="inputbasic form-control" id="quantite_Spm" disabled="true" />
            </div>
            <div class="modal-footer row">
              <div class="col-sm-3 "> <input type="button" id="btn_spm_StockEntrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
              <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

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
                <p>PROBLEME CONNECTION INTERNET .</br>
                </br> L'operation sur ce stock a échoué. Veuillez reessayer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->


<?php
echo'
  <script>
  alert(1111)
  if (window.history.replaceState) {
      window.history.replaceState( null, null, window.location.href );
  }
  </script>
</body>
</html>';


?>
