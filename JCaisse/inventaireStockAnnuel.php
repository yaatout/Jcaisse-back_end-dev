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

if (isset($_POST['viderStock'])) {
  // UPDATE `Lamp Fall distribution-entrepotstock` SET `quantiteStockCourant`=0 WHERE`idEntrepot`=2  
  // echo '<script>alert(1122);document.getElementById("viderStock").disabled = true;</script>';
  
  $sqlS="UPDATE `".$nomtableStock."` set quantiteStockCourant=0";

  $resS=mysql_query($sqlS) or die ("update quantiteStockCourant impossible =>".mysql_error());

}

if (isset($_POST['excelFichier'])) {
  header('Content-Type: text/csv; charset=utf-8');

  header('Content-Disposition: attachment; filename='.$_SESSION["labelB"].' '.$dateString2.'.csv');

  $delimiter = ";";

  $output = fopen("php://output", "w");

  $texte= strtoupper($_SESSION["labelB"])."  :  JOURNAL DE CAISSE du ".$dateString2." ";

  $entete=array($texte);

  fputcsv($output,$entete, $delimiter );

  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    $titre=array('DESIGNATION','CATEGORIE', 'PRIX SESSION', 'PRIX PUBLIC', 'QUANTITE');
  }
  else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
  }
  else{
    $titre=array('DESIGNATION','CATEGORIE', 'PRIX ACHAT', 'PRIX U', 'QUANTITE');
  }

  fputcsv($output,$titre, $delimiter );

  $contenu=array();

  $sql="SELECT d.idDesignation FROM `".$nomtableDesignation."` d
  LEFT JOIN `".$nomtableInventaire."` i ON i.idDesignation = d.idDesignation
  WHERE i.type=10 GROUP BY d.idDesignation ORDER BY d.designation ASC ";
  $res=mysql_query($sql);

  
  while($produit=mysql_fetch_array($res)){

    $sql1="SELECT * FROM `".$nomtableDesignation."` where idDesignation='".$produit['idDesignation']."'";
    $res1=mysql_query($sql1);
    $designation=mysql_fetch_array($res1);

    $sql2="SELECT * FROM `".$nomtableInventaire."` where idDesignation='".$produit['idDesignation']."' ORDER by idInventaire DESC";
    $res2=mysql_query($sql2);
    $inventaire=mysql_fetch_array($res2);

    $contenu[0]=$designation['designation'];
    $contenu[1]=$designation['categorie'];
    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      $contenu[3]=$designation['prixSession'];
      $contenu[4]=$designation['prixPublic'];
    }
    else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
    }
    else{
      $contenu[3]=$designation['prixachat'];
      $contenu[4]=$designation['prix'];
    }
    $contenu[5]=$inventaire['quantite'];

     fputcsv($output,$contenu, $delimiter );

  }

  fclose($output); exit;

}

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

/*
$sql17="SELECT * FROM `aaa-boutique`";
$res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
while ($b = mysql_fetch_array($res17)) {
	 var_dump($b['nomBoutique']);
	$tableDesignation=$b['nomBoutique']."-designation";
	$sql1="ALTER TABLE `".$tableDesignation."` MODIFY COLUMN  archiver int(1) NOT NULL;";
  //$sqlA="ALTER TABLE `".$tablePanier."` MODIFY COLUMN paiement varchar(250);";
	$res1 =@mysql_query($sql1) or die ("creation table compte impossible ".mysql_error());
}
*/


//if($_SESSION['enConfiguration'] ==0){
echo'<div class="container" >';
  // var_dump($_SESSION['enConfiguration']);
  
    echo '<ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#INVENTAIRE_1"> Inventaire du Stock par Designation </a></li>
      <li><a data-toggle="tab" href="#INVENTAIRE_2"> Inventaire du Stock par Categorie </a></li>';
      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
        echo '<li><a data-toggle="tab" href="#INVENTAIRE_3"> Inventaire du Stock par Forme </a></li>';
      }
      echo '
      <li><a data-toggle="tab" href="#INVENTAIRE_4"> Inventaire Total</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade in active" id="INVENTAIRE_1">
        <center class="row">     
            <form target="_blank" style="margin-top:10px;" method="post" action="pdfInventaireDesignation.php">
              <button type="submit" class="btn btn-default>
                  <span class="glyphicon glyphicon-download-alt"></span> PDF classé par Désignation
              </button>
            </form> 
        </center> 
        <div class="table-responsive">
          <label class="pull-left" for="nbEntreeInvDesignation">Nombre entrées </label>
          <select class="pull-left" id="nbEntreeInvDesignation">
          <optgroup>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option> 
          </optgroup>       
          </select>
          <input class="pull-right" type="text" name="" id="searchInputInvDesignation" placeholder="Rechercher...">
          <div id="resultsInvDesignation"><!-- content will be loaded here -->
            <div class="loading-divI"></div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="INVENTAIRE_2">
        <center class="row">     
            <form target="_blank" style="margin-top:10px;" method="post" action="pdfInventaireCategorie.php">
              <button type="submit" class="btn btn-default>
                  <span class="glyphicon glyphicon-download-alt"></span> PDF classé par Categorie
              </button>
            </form> 
        </center> 
        <div class="table-responsive">
            <label class="pull-left" for="nbEntreeInvCategorie">Nombre entrées </label>
            <select class="pull-left" id="nbEntreeInvCategorie">
            <optgroup>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option> 
            </optgroup>       
            </select>
            <input class="pull-right" type="text" name="" id="searchInputInvCategorie" placeholder="Rechercher...">
            <div id="resultsInvCategorie"><!-- content will be loaded here -->
              <div class="loading-divI"></div>
            </div>
        </div>
      </div> ';
      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
          echo '
        <div class="tab-pane fade" id="INVENTAIRE_3">
            <center class="row">     
              <form target="_blank" style="margin-top:10px;" method="post" action="pdfInventaireForme.php">
                <button type="submit" class="btn btn-default>
                    <span class="glyphicon glyphicon-download-alt"></span> PDF classé par Forme
                </button>
              </form>  
            </center> 
          <div class="table-responsive">
              <label class="pull-left" for="nbEntreeInvForme">Nombre entrées </label>
              <select class="pull-left" id="nbEntreeInvForme">
              <optgroup>
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option> 
              </optgroup>       
              </select>
              <input class="pull-right" type="text" name="" id="searchInputInvForme" placeholder="Rechercher...">
              <div id="resultsInvForme"><!-- content will be loaded here -->
                <div class="loading-divI"></div>
              </div>
          </div>
        </div>';
      }
      echo '
      <div class="tab-pane fade" id="INVENTAIRE_4">
        <center class="row">     
          <form  target="_blank" style="margin-top:10px;" method="post">
            <button type="submit" class="btn btn-primary" name="excelFichier" id="excelFichier" >
                <span class="glyphicon glyphicon-download-alt"></span> EXCEL Inventaire
            </button>
          </form> 
        </center> 
        <div class="table-responsive">
            <label class="pull-left" for="nbEntreeInvTotal">Nombre entrées </label>
            <select class="pull-left" id="nbEntreeInvTotal">
            <optgroup>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option> 
            </optgroup>       
            </select>
            <input class="pull-right" type="text" name="" id="searchInputInvTotal" placeholder="Rechercher...">
            <div id="resultsInvTotal"><!-- content will be loaded here -->
              <div class="loading-divI"></div>
            </div>
        </div>
      </div>
    </div>
</div>';
?>
<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary">
  Launch demo modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="alertViderStock" tabindex="-1" role="dialog" aria-labelledby="alertViderStockLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="alertViderStockLabel">Avertissement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p style="color:red;font-size:18px;">ATTENTION!!!</p>
        <br>
        <!-- <br> -->
        <p style="font-size:14px;"> La confirmation de cette opération va mettre <code>les quantités</code> de tous les produits à <code>zéro</code></p>
      </div>
      <div class="modal-footer">
        <form action="" method="post">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
          <button type="submit" class="btn btn-warning" name="viderStock" id="viderStock">Confirmer</button>
        </form>
      </div>
    </div>
  </div>
</div>

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
