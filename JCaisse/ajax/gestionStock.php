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

 $sql2="SELECT d.prix,s.quantiteStockCourant,s.idStock,d.prixachat FROM `".$nomtableStock."` s
LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
WHERE d.classe=0 ORDER BY s.idStock DESC";
$res2=mysql_query($sql2);
$montantPA=0;
$montantPU=0;
while ($stock = mysql_fetch_array($res2)) {
  if($stock['quantiteStockCourant']!=null && $stock['quantiteStockCourant']!=0){
    $montantPA=$montantPA + ($stock['quantiteStockCourant'] * $stock['prixachat']);
    $montantPU=$montantPU + ($stock['quantiteStockCourant'] * $stock['prix']);
  }
}

/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
echo'
   <body >';
   require('header.php');

   echo '
   <script type="text/javascript">
      $(document).ready(function() {
        $("#btnAjoutStockModal").click(function(){
          $("#AjoutStockModal").modal();
          $("#tableStock0").dataTable({
          "bProcessing": true,
          "destroy": true,
          "sAjaxSource": "ajax/listeProduitAjax.php",
          "aoColumns": [
              { mData: "0" } ,
              { mData: "1" },
              { mData: "2" },
              { mData: "3" },
              { mData: "4" },
              { mData: "5" },
            ],
            
          });  
        });
      });
    </script> ';


echo'<div class="container"><center> <button type="button" class="btn btn-success" id="btnAjoutStockModal">
<i class="glyphicon glyphicon-plus"></i>Ajouter un Stock</button></center> ';

echo'<div class="modal fade" id="AjoutStockModal" role="dialog">';
echo'<div class="modal-dialog modal-lg">';
  echo'<div class="modal-content">';
    echo'<div class="modal-header" >';
    echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
    echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>";
      echo'</div>';
      if (($_SESSION['type']=="Divers") && ($_SESSION['categorie']=="Detaillant")) {
        echo'<div class="modal-body">
              <div class="table-responsive">
                <table id="tableStock0" class="display" width="100%" border="1">
                  <thead>
                  <tr id="thStock">
                    <th>Reference</th>
                    <th>Codebarre</th>
                    <th>Quantite</th>
                    <th>Prix Achat</th>
                    <th>Prix Vente</th>
                    <th>Operation</th> 
                  </tr>
                  </thead>
                </table>
              </div>
          </div> ';
      }
      else{
          echo'<div class="modal-body">
              <div class="table-responsive">
                <table id="tableStock0" class="display" width="100%" border="1">
                  <thead>
                  <tr id="thStock">
                    <th>Reference</th>
                    <th>Codebarre</th>
                    <th>Quantite</th>
                    <th>Unite Stock(US)</th>
                    <th>Expiration</th>
                    <th>Operation</th> 
                  </tr>
                  </thead>
                </table>
              </div>
          </div> ';
      }
        echo '
      </div>
    </div>
  </div>
</div>';

//if($_SESSION['enConfiguration'] ==0){
  echo'<div class="container" align="center">
  <br />
  <ul class="nav nav-tabs">
    <li class="active">
      <a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS : ';
        if($_SESSION['proprietaire']==1){ 
          echo ' <span style="color:blue;"> Valeur Stock (PA) =  '.number_format($montantPA, 2, ',', ' ').' FCFA </span>';  
          echo ' <=> <span style="color:green;">Valeur Stock (PU) =  '.number_format($montantPU, 2, ',', ' ').' FCFA </span>';
        }
        echo '
      </a>
    </li>';
    if (($_SESSION['type']=="Superette") && ($_SESSION['categorie']=="Detaillant")) {
      echo '
      <li>
        <a data-toggle="tab" href="#STOCKVENTEFORCEE">VENTE FORCEE</a>
      </li> ';
    }
    echo '
  </ul>
  <div class="tab-content">
      <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
        echo'<div class="table-responsive">
            <table id="tableStock" class="display tabStock" class="tableau3" align="left" border="1">
            <thead>
              <tr id="thStock">
                <th>ORDRE</th>
                <th>REFERENCE</th>
                <th>CODEBARRE</th>
                <th>QUANTITE</th>
                <th>UNITE STOCK (US)</th>
                <th>NOMBRE ARTICLE U.S</th>
                <th>PRIX U.S</th>
                <th>PRIX UNITAIRE (PU)</th>
                <th>PRIX ACHAT (PA)</th>
                <th>OPERATIONS</th>
              </tr>
            </thead>
            </table>
          
            <script type="text/javascript">
              $(document).ready(function() {
                  $("#tableStock").dataTable({
                    "bProcessing": true,
                    "sAjaxSource": "ajax/listerStockAjax.php",
                    "aoColumns": [
                          { mData: "0" } ,
                          { mData: "1" },
                          { mData: "2" },
                          { mData: "3" },
                          { mData: "4" },
                          { mData: "5" },
                          { mData: "6" },
                          { mData: "7" },
                          { mData: "8" },
                          { mData: "9" },
                        ],
                        "dom": "Bfrtip",
                        "buttons" : [
                            "copy",
                          {
                            extend: "excel",
                            messageTop: "Liste de tout les Stock ",
                          },
                          {
                            extend: "pdf",
                            messageTop: "Liste de tout les Stock ",
                            messageBottom: null
                          },
                          {
                            extend: "print",
                            text: "Imprimer",
                            messageTop: "Liste de tout les Stock ",
                          }
                        ]
                  });  
              });
            </script>

        </div>
      </div>
      <div class="tab-pane fade" id="STOCKVENTEFORCEE">
        <div class="table-responsive">
            <table id="tableVenteForcee" class="display" class="tableau3" width="100%" border="1">
            <thead>
              <tr id="thStock">
                <th>ORDRE</th>
                <th>REFERENCE</th>
                <th>CODEBARRE</th>
                <th>UNITE STOCK (US)</th>
                <th>NOMBRE ARTICLE U.S</th>
                <th>DATE</th>
                <th>QUANTITE</th>
                <th>TOTAL</th>
                <th>OPERATION</th>
              </tr>
            </thead>
            </table>
          
            <script type="text/javascript">
              $(document).ready(function() {
                  $("#tableVenteForcee").dataTable({
                    "bProcessing": true,
                    "sAjaxSource": "ajax/listerStockVenteForceeAjax.php",
                    "aoColumns": [
                          { mData: "0" } ,
                          { mData: "1" },
                          { mData: "2" },
                          { mData: "3" },
                          { mData: "4" },
                          { mData: "5" },
                          { mData: "6" },
                          { mData: "7" },
                          { mData: "8" },
                        ],
                        "dom": "Bfrtip",
                        "buttons" : [
                            "copy",
                          {
                            extend: "excel",
                            messageTop: "Liste de tout les Stock ",
                          },
                          {
                            extend: "pdf",
                            messageTop: "Liste de tout les Stock ",
                            messageBottom: null
                          },
                          {
                            extend: "print",
                            text: "Imprimer",
                            messageTop: "Liste de tout les Stock ",
                          }
                        ]
                  });  
              });
            </script>

        </div>
      </div>
  </div>
</div>';

echo'</body></html>';

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

}
?>
