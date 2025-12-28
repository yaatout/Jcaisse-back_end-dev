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
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');

/**********************/

// if (isset($_POST['viderStock'])) {
//   // UPDATE `Lamp Fall distribution-entrepotstock` SET `quantiteStockCourant`=0 WHERE`idEntrepot`=2  
//   // echo '<script>alert(1122);document.getElementById("viderStock").disabled = true;</script>';
  
//   $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0";

//   $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

// }

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
echo'<div class="container">';
  // var_dump($_SESSION['enConfiguration']);
// if ($_SESSION['enConfiguration']==0){

//   echo '<br><div align="center"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#alertViderStock">Vider la table stock</button></div><br>';
// }       
  echo'<center class="row">     
  <form class="col-md-4" target="_blank" style="margin-top:10px;margin-bottom:10px;" method="post" action="pdfPecheStock1.php">
    <button type="submit" class="btn btn-success" >
        <span class="glyphicon glyphicon-download-alt"></span> PDF Quantite egale à 0
    </button>
  </form>  
  <form class="col-md-4" target="_blank" style="margin-top:10px;margin-bottom:10px;" method="post" action="pdfPecheStock2.php">
    <button type="submit" class="btn btn-primary" >
        <span class="glyphicon glyphicon-download-alt"></span> PDF Quantite comprise entre 1 et 20
    </button>
  </form>  
  <form class="col-md-4" target="_blank" style="margin-top:10px;margin-bottom:10px;" method="post" action="pdfPecheStock3.php">
    <button type="submit" class="btn btn-info" >
        <span class="glyphicon glyphicon-download-alt"></span> PDF Quantite superieure à 20
    </button>
  </form>
  </center>';
  echo '<ul class="nav nav-tabs">
    <li class="active" id="INVENTAIREJOUR1"><a data-toggle="tab" href="#INVENTAIRE1"> Quantite egale à 0 </a></li>
    <li class="" id="INVENTAIREJOUR2"><a data-toggle="tab" href="#INVENTAIRE2"> Quantite comprise entre 1 et 20 </a></li>
    <li class="" id="INVENTAIREJOUR3"><a data-toggle="tab" href="#INVENTAIRE3"> Quantite superieure à 20 </a></li>
  </ul>
    <div class="tab-content">
      <div class="tab-pane fade in active" id="INVENTAIRE1">';
        echo'<div class="table-responsive">
          <label class="pull-left" for="nbEntreeInvJour1">Nombre entrées </label>
          <select class="pull-left" id="nbEntreeInvJour1">
          <optgroup>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option> 
          </optgroup>       
          </select>
          <input class="pull-right" type="text" name="" id="searchInputInvJour1" placeholder="Rechercher...">
          <div id="resultsInventairesJour1"><!-- content will be loaded here -->
            <div class="loading-divI"></div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="INVENTAIRE2">';
        echo'<div class="table-responsive">
          <label class="pull-left" for="nbEntreeInvJour2">Nombre entrées </label>
          <select class="pull-left" id="nbEntreeInvJour2">
          <optgroup>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option> 
          </optgroup>       
          </select>
          <input class="pull-right" type="text" name="" id="searchInputInvJour2" placeholder="Rechercher...">
          <div id="resultsInventairesJour2"><!-- content will be loaded here -->
            <div class="loading-divI"></div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="INVENTAIRE3">';
        echo'<div class="table-responsive">
          <label class="pull-left" for="nbEntreeInvJour3">Nombre entrées </label>
          <select class="pull-left" id="nbEntreeInvJour3">
          <optgroup>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option> 
          </optgroup>       
          </select>
          <input class="pull-right" type="text" name="" id="searchInputInvJour3" placeholder="Rechercher...">
          <div id="resultsInventairesJour3"><!-- content will be loaded here -->
            <div class="loading-divI"></div>
          </div>
        </div>
      </div>
    </div>
  </div>';
?>
  <!-- Button trigger modal -->
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
