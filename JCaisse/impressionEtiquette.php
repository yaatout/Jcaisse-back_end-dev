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
$idStock          =@$_POST["idStock"];
$designation      =@htmlspecialchars(trim($_POST["designation"]));
$categorie2       =@htmlentities($_POST["categorie2"]);
$idDesignation    =@$_POST["idDesignation"];

$stock            =@$_POST["stock"];
$uniteStock       =@$_POST["uniteStock"];
$nbreArticleUS    =@$_POST["nbreArticleUniteStock"];

$prixuniteStock   =@$_POST["prixuniteStock"];
$prixunitaire     =@$_POST["prixunitaire"];

$prixSession         =@$_POST["prixSession"];
$prixPublic     =@$_POST["prixPublic"];

$forme        =@$_POST["forme"];
$tableau      =@$_POST["tableau"];

$prixDeRevientDuStock     =@$_POST["prixDeRevientDuStock"];

$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];

$insererStock     =@$_POST["insererStock"];
$supprimer        =@$_POST["supprimer"];
$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
$insererDesignation   =@$_POST["insererDesignation"];

/***************/
$i     =@$_POST["idDesignation-btnAjouterStock"];
$idStock2       =@$_GET["idStock"];

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

 require('gestionStock-pharmacie.php');

}
else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

  require('gestionStock-entrepot.php');
 
 }
else{

/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 

?>

<body >
   <?php require('header.php'); ?>

  <div class="container">
<!-- 
    <div class="panel">
        <center> 
            <h4>
                Valeur Stock en Prix de Reviens =<span id="montantAchats"></span> <=> Valeur Stock en Prix de vente =  <span id="montantVentes"></span>
            </h4>
        </center> 
    </div> -->

    <center> 
      <button type="button" class="btn btn-success pull-left" id="btn_lister_Produit">
        <i class="glyphicon glyphicon-plus"></i>Ajouter des Produit
      </button>

      <form class="form-inline pull-right noImpr"  method="post" action="pdfEtiquette_A3.php" target="_blank"  >
          <button type="submit" class="btn btn-warning pull-right" >
          <span class="glyphicon glyphicon-print"></span> Imprimer les Etiquettes
          </button>
      </form>
    </center> 

    <div class="modal fade" id="lister_Produit" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Produit a Imprimer </h4>
            </div>
            <div class="modal-body" >
                <div class="table-responsive">                
                  <table id="listeProduits" class="display tabStock" class="tableau3" width="100%" border="1">
                    <thead>
                        <tr>
                            <th>IdDesignation</th>
                            <th>Reference</th>
                            <th>Quantite</th>
                            <th>Unite Impression</th>
                            <th>Prix Unite Stock</th>
                            <th>Prix Article</th>
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
        <a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES ETIQUETTES 
          <?php 
            if($_SESSION['proprietaire']==1){ 
             
            }
          ?>
        </a>
      </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">   
            <br />
            <div class="table-responsive">  
                <table id="listeEtiquette" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                            <th>IdDesignation</th>
                            <th>Reference</th>
                            <th>Quantite</th>
                            <th>Unite Etiquette</th>
                            <th>Prix Etiquette</th>
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

  <script type="text/javascript" src="scripts/impressionEtiquette.js"></script>

  <?php 

    /* Debut PopUp d'Alerte sur l'ensemble de la Page **/
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
    /** Fin PopUp d'Alerte sur l'ensemble de la Page **/

        /** Debut Message d'Alerte concernant le Stock du Produit avant la vente **/
        echo '
        <div id="msg_confirm_Stock" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Alerte</h4>
                    </div>
                    <div class="modal-body">
                        <p>Ce Stock existe deja, voulez vraiment ajouter </p>
                        <div class="table-responsive">
                        <table id="tableInitiale" class="table table-bordered table-responsive display" align="left" border="1">
                          <thead>
                            <tr>
                              <th>Reference</th>
                              <th>CodeBarre </th>
                              <th>Quantite</th>
                              <th>Unite Stock(US)</th>
                              <th>Expiration</th>
                              <th>Operation</th>
                            </tr>
                          </thead>
                          <tbody>
                          <tr>
                            <input type="hidden" class="form-control" id="idDesignation_Cfm" />
                            <input type="hidden" class="form-control" id="idBl_Cfm" />
                            <td><input type="text" class="form-control" id="designation_Cfm"  /></td>
                            <td><input type="text" class="form-control" id="codeBarre_Cfm"  /></td>
                            <td><input type="number" class="form-control" id="quantite_Cfm"  /></td>
                            <td>
                              <select class="form-control" id="uniteStock_Cfm">
                                  <option selected></option>
                                  <option value="Article">Article</option>
                              </select>
                            </td>
                            <td><input type="date" class="form-control" id="dateExpiration_Cfm"  /></td>
                            <td>
                              <button type="button" onclick="ajt_Stock_Cfm()" id="btn_AjtStock_P_Cfm"  class="btn btn-success">
                                <i class="glyphicon glyphicon-plus"></i>CONFIRMER
                              </button>
                            </td>
                          </tr>
                          </tbody>
                        </table>
              
                        <script type="text/javascript">
                          $(document).ready(function() {
                            $("#tableInitiale").dataTable({
                              bFilter: false, 
                              bInfo: false,
                              paging : false,
                              
                            });  
                          });
                        </script>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>';
        /** Fin Message d'Alerte concernant le Stock du Produit avant la vente **/

}
?>
