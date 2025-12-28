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

if (!$_SESSION['iduser']) {
  header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');


require('entetehtml.php');
echo '
   <body >';
require('header.php');


$sql0 = "SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='" . $_SESSION['idBoutique'] . "' and YEAR(datePs)='" . $annee . "' and MONTH(datePs)!='" . $mois . "' and aPayementBoutique=0 ";

$res0 = mysql_query($sql0);

if (mysql_num_rows($res0)) {

  if ($jour > 4) {

    if ($_SESSION['gestionnaire'] == 1) {
      echo '<div class="container"><center> <button disabled="true" type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutTransfertModal" data-dismiss="modal" id="AjoutStock">
          <i class="glyphicon glyphicon-plus"></i>Ajouter un Transfert</button></center> ';
    }
    if ($_SESSION['proprietaire'] == 1) {
      echo '<div class="container"><center> <button disabled="true" type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
          <i class="glyphicon glyphicon-plus"></i>Ajouter un Transfert</button></center> ';
    }

    echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span></h6>";
  } else {

    if ($_SESSION['gestionnaire'] == 1) {
      echo '<div class="container"><center> <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutTransfertModal" data-dismiss="modal" id="AjoutStock">
          <i class="glyphicon glyphicon-plus"></i>Ajouter un Transfert</button></center> ';
    }
    if ($_SESSION['proprietaire'] == 1) {
      echo '<div class="container"><center> <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
          <i class="glyphicon glyphicon-plus"></i>Ajouter un Transfert</button></center> ';
    }

    echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span></h6>";
  }
} else {

  if ($_SESSION['gestionnaire'] == 1) {
    echo '<div class="container"><center> <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutTransfertModal" data-dismiss="modal" id="AjoutStock">
      <i class="glyphicon glyphicon-plus"></i>Ajouter un Transfert</button></center> ';
  }
  if ($_SESSION['proprietaire'] == 1) {
    echo '<div class="container"><center> <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
      <i class="glyphicon glyphicon-plus"></i>Ajouter un Transfert</button></center> ';
  }
}

echo '<div class="modal fade" id="AjoutTransfertModal" role="dialog">';
echo '<div class="modal-dialog modal-lg">';
echo '<div class="modal-content">';
echo '<div class="modal-header" >';
echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo "<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Transfert </h4>";
echo '</div>';
echo '<div class="modal-body">
                <div class="table-responsive">
                <table id="tableStock0" class="display" width="100%" border="1">
                  <thead>
                  <tr id="thStock">
                    <th>Reference</th>
                    <th>Categorie</th>
                    <th>Entrepot</th>
                    <th>Quantite</th>
                    <th>Unite Stock(US)</th>
                    <th>Operation</th> 
                  </tr>
                  </thead>
                </table>
              
                <script type="text/javascript">
                  $(document).ready(function() {
                    $("#tableStock0").dataTable({
                    "bProcessing": true,
                    "sAjaxSource": "ajax/listeProduit-TransfertAjax.php",
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
                </script>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>';

echo '<div class="modal fade container-fluid" id="AjoutStockModal" role="dialog">';
echo '<div class="modal-dialog modal-lg">';
echo '<div class="modal-content">';
echo '<div class="modal-header" >';
echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo "<h4><span class='glyphicon glyphicon-lock'></span> Transfert de Stock </h4>";
echo '</div>';
echo '<div class="modal-body">
              <center class="row">  
                Date : <input type="date"  id="jour_date"  max="' . date('Y-m-d', strtotime('-1 days')) . '" value="' . date('Y-m-d', strtotime($dateString2)) . '" name="dateInventaire" required  />
              </center>
              <div class="table-responsive">                
                <label class="pull-left" for="nbEntreePTrf">Nombre entrées </label>
                <select class="pull-left" id="nbEntreePTrf">
                <optgroup>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option> 
                </optgroup>       
                </select>
                <input class="pull-right" type="text" name="" id="searchInputPTrf" placeholder="Rechercher..." autocomplete="off">
                <div id="resultsProductTrf"><!-- content will be loaded here --></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>';

echo '<div id="transfertStockModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Transfert Stock Entrepot</h4>
          </div>
          <div class="modal-body" style="padding:40px 50px;">
            <form class="form" >
                <input type="hidden"  id="tftidDesignation"  />
                <input type="hidden"  id="tftidEntrepot"  />
              <center class="row">  
                Date : <input type="date"  id="date_jour"  max="' . date('Y-m-d', strtotime('-1 days')) . '" value="' . date('Y-m-d', strtotime($dateString2)) . '" name="dateInventaire" required  />
              </center>
              <div class="form-group">
                <label for="tftDesignation">Reference </label>
                <input type="text" disabled="true" class="inputbasic form-control" id="tftDesignation" />
              </div>
              <div class="form-group">
                <label for="tftOrigine">Depot Origine </label>
                <input type="text" disabled="true" class="inputbasic form-control" id="tftOrigine" />
              </div>
              <div class="form-group">
                <label for="tftOrigineQte">Quantite</label>
                <input type="text" disabled="true" class="inputbasic form-control" id="tftOrigineQte" />
              </div>
              <div class="form-group">
                <label for="tftDestination">Depot Destination </label>
                <select class="form-control listeEntrepot" id="tftDestination" >
                </select>
              </div>
              <div class="form-group">
                <label for="tftDestinationQte">Quantite </label>
                <input type="text" class="inputbasic form-control" id="tftDestinationQte" />
              </div>
              <div class="modal-footer row">
                <div class="col-sm-3 "> <input type="button" id="btn_tft_EntrepotDepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>';

//if($_SESSION['enConfiguration'] ==0){
echo '<div class="container" align="center">
<br />
      <ul class="nav nav-tabs">
        <li class="active">
          <a data-toggle="tab" href="#STOCKTRANSFERT">TRANSFERTS DANS LES DEPOTS 
          </a>
        </li>
        <li>
        <a data-toggle="tab" href="#DEPOTTRANSFERT">TRANSFERTS ENTRE LES DEPOTS
        </a>
      </li>
      </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="STOCKTRANSFERT">
              <div class="table-responsive">                
                <label class="pull-left" for="nbEntreeStockTrf">Nombre entrées </label>
                <select class="pull-left" id="nbEntreeStockTrf">
                <optgroup>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option> 
                </optgroup>       
                </select>
                <input class="pull-right" type="text" name="" id="searchInputStockTrf" placeholder="Rechercher..." autocomplete="off">
                <div id="resultsStockTrf">
                  <img src="images/loading-gif3.gif" style="margin-left:5%;margin-top:8%" class="loading-gif" alt="GIF"
                      srcset="">
                </div>
              </div>
            </div>
            <div class="tab-pane" id="DEPOTTRANSFERT">';
echo '<div class="table-responsive">
                <label class="pull-left" for="nbEntreeTrans">Nombre entrées </label>
                <select class="pull-left" id="nbEntreeTrans">
                <optgroup>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option> 
                </optgroup>       
                </select>
                <input class="pull-right" type="text" name="" id="searchInputTrans" placeholder="Rechercher...">
                <div id="resultsTransfert"><!-- content will be loaded here -->
                  <div class="loading-divI"></div>
                </div>
              </div>
            </div>
        </div>
      </div>';

echo '<div class="modal fade" id="TransfertModal_0" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span id="nomDesignation"></span> => [ Quantite : <span id="produitQte"></span> / Transfert : <span id="produitTtl"></span> / Restant : <span id="produitRst"></span> ]</h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
              <table id="tableStock_0" class="display" width="100%" border="1">
                <thead>
                <tr id="thStock">
                  <th>Depot</th>
                  <th>Enregistrement</th>
                  <th>Initial</th>
                  <th>Restant</th>
                  <th>Quantite</th>
                  <th>Operation</th>
                </tr>
                </thead>
              </table>

              <script type="text/javascript">
                  function transfertStock_0(transfert,designation,quantite,total) {
                    produit=$(".produitTF"+transfert).text();
                    $("#nomDesignation").text(produit);
                    $("#produitQte").text(quantite);
                    $("#produitTtl").text(total);
                    $("#produitRst").text(quantite - total);
                    $("#TransfertModal_0").modal("show");
                    $("#tableStock_0").dataTable({
                      "bProcessing": true,
                      "destroy": true,
                      "sAjaxSource": "ajax/transfertProduitAjax.php?idD="+designation+"&idT="+transfert,
                      "aoColumns": [
                          { mData: "0" },
                          { mData: "1" },
                          { mData: "2" },
                          { mData: "3" },
                          { mData: "4" },
                          { mData: "5" },
                        ],
                        
                      }); 
                  }
              </script>
            </div>
        </div>
      </div>
    </div>
  </div>';

echo '<div class="modal fade" id="TransfertModal_1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span id="nomDesignation_1"></span> => [ Quantite : <span id="produitQte_1"></span> / Transfert : <span id="produitTtl_1"></span> / Restant : <span id="produitRst_1"></span> ]</h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
              <table id="tableStock_1" class="display" width="100%" border="1">
                <thead>
                <tr id="thStock">
                  <th>Depot</th>
                  <th>Transfert</th>
                  <th>Initial</th>
                  <th>Restant</th>
                  <th>Enregistrement</th>
                </tr>
                </thead>
              </table>

              <script type="text/javascript">
                  function transfertStock_1(transfert,designation,quantite,total) {
                    produit=$(".produitTF"+transfert).text();
                    $("#nomDesignation_1").text(produit);
                    $("#produitQte_1").text(quantite);
                    $("#produitTtl_1").text(total);
                    $("#produitRst_1").text(quantite - total);
                    $("#TransfertModal_1").modal("show");
                    $("#tableStock_1").dataTable({
                      "bProcessing": true,
                      "destroy": true,
                      "sAjaxSource": "ajax/transfertEntrepotAjax.php?idD="+designation+"&idT="+transfert,
                      "aoColumns": [
                          { mData: "0" },
                          { mData: "1" },
                          { mData: "2" },
                          { mData: "3" },
                          { mData: "4" },
                        ],
                        
                    }); 
                  }
              </script>
          
            </div>
        </div>
      </div>
    </div>
  </div>';

echo '<div class="modal fade" id="TransfertModal_2" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span id="nomDesignation_2"></span> => [ Quantite : <span id="produitQte_2"></span> / Transfert : <span id="produitTtl_2"></span> / Restant : <span id="produitRst_2"></span> ]</h4>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#TF_1">TRANSFERER</a></li>
            <li><a data-toggle="tab" href="#TF_2">TRANSFERTS EFFECTUES</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade in active" id="TF_1">
              <div class="table-responsive">
                <table id="tableStock_21" class="display" width="100%" border="1">
                  <thead>
                  <tr id="thStock">
                    <th>Depot</th>
                    <th>Enregistrement</th>
                    <th>Initial</th>
                    <th>Restant</th>
                    <th>Quantite</th>
                    <th>Operation</th>
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div class="tab-pane fade" id="TF_2">
              <div class="table-responsive">
                <table id="tableStock_22" class="display" width="100%" border="1">
                  <thead>
                  <tr id="thStock">
                    <th>Depot</th>
                    <th>Transfert</th>
                    <th>Initial</th>
                    <th>Restant</th>
                    <th>Enregistrement</th>
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <script type="text/javascript">
              function transfertStock_2(transfert,designation,quantite,total) {
                produit=$(".produitTF"+transfert).text();
                $("#nomDesignation_2").text(produit);
                $("#produitQte_2").text(quantite);
                $("#produitTtl_2").text(total);
                $("#produitRst_2").text(quantite - total);
                $("#TransfertModal_2").modal("show");
                $("#tableStock_21").dataTable({
                  "bProcessing": true,
                  "destroy": true,
                  "sAjaxSource": "ajax/transfertProduitAjax.php?idD="+designation+"&idT="+transfert,
                  "aoColumns": [
                      { mData: "0" },
                      { mData: "1" },
                      { mData: "2" },
                      { mData: "3" },
                      { mData: "4" },
                      { mData: "5" },
                    ],
                    
                }); 
                $("#tableStock_22").dataTable({
                  "bProcessing": true,
                  "destroy": true,
                  "sAjaxSource": "ajax/transfertEntrepotAjax.php?idD="+designation+"&idT="+transfert,
                  "aoColumns": [
                      { mData: "0" },
                      { mData: "1" },
                      { mData: "2" },
                      { mData: "3" },
                      { mData: "4" },
                    ],
                    
                }); 
              }
          </script>
        </div>
      </div>
    </div>
  </div>';

?>

<script type="text/javascript">
  var blink_speed = 500;

  var t = setInterval(function() {

    var ele = document.getElementById('blinker');

    ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');

  }, blink_speed);
</script>

<div id="modifierEntrepotTransfert" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modification transfert</h4>
      </div>
      <div class="modal-body" style="padding:40px 50px;">
        <form class="form">
          <div class="form-group">
            <input type="hidden" id="idEntrepotTransfert_Mdf" />
            <input type="hidden" id="ordreEntrepotTransfert_Mdf" />
          </div>
          <div class="form-group">
            <label for="designation_Mdf">Reference </label>
            <input disabled="true" type="text" class="inputbasic form-control" id="designation_Mdf" />
          </div>
          <div class="form-group">
            <label for="uniteStock_Mdf">Unite stock</label>
            <input disabled="true" type="text" class="inputbasic form-control" id="uniteStock_Mdf" />
          </div>
          <div class="form-group">
            <label for="quantite_Mdf">Quantite</label>
            <input type="text" class="inputbasic form-control" id="quantite_Mdf" />
          </div>
          <div class="modal-footer row">
            <div class="col-sm-3 "> <input type="button" id="btn_mdf_Transfert" class="btn_CodeDesign_P boutonbasic" value=" Enregistrer >>" /></div>
            <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="supprimerEntrepotTransfert" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Suppression transfert</h4>
      </div>
      <div class="modal-body" style="padding:40px 50px;">
        <form class="form">
          <div class="form-group">
            <input type="hidden" id="idEntrepotTransfert_Spm" />
            <input type="hidden" id="ordreEntrepotTransfert_Spm" />
          </div>
          <div class="form-group">
            <label for="designation_Spm">Reference </label>
            <input disabled="true" type="text" class="inputbasic form-control" id="designation_Spm" />
          </div>
          <div class="form-group">
            <label for="uniteStock_Spm">Unite stock</label>
            <input disabled="true" type="text" class="inputbasic form-control" id="uniteStock_Spm" />
          </div>
          <div class="form-group">
            <label for="quantite_Spm">Quantite</label>
            <input disabled="true" type="text" class="inputbasic form-control" id="quantite_Spm" />
          </div>
          <div class="modal-footer row">
            <div class="col-sm-3 "> <input type="button" id="btn_spm_Transfert" class="btn_CodeDesign_P boutonbasic" value=" Enregistrer >>" /></div>
            <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="msg_info_Depot" class="modal fade " role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header panel-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Alerte</h4>
      </div>
      <div class="modal-body">
        <p>Il faut choisir un depot de destination</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
echo '</body></html>';

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