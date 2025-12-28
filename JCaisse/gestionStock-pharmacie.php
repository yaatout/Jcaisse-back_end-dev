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

/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
?>

<body >
   <?php require('header.php'); ?>

  <div class="container">
    <center> 
      <button type="button" class="btn btn-success" id="btn_lister_Produit">
        <i class="glyphicon glyphicon-plus"></i>Ajouter un Stock
      </button>
    </center> 

    <div class="modal fade" id="lister_Produit" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding-bottom:5px;">
                  <div class="col-sm-6 form-group" >
                      <label class="col-sm-3" style="color:blue;"> Fournisseur  </label>
                      <select style="width: 200px;" class="form-control" onchange="choix_BL_Fournisseur(this.value)" id="slct_Stock_Fournisseur">
                      </select>
                   </div> 
                   <div class="col-sm-6 pull-right form-group" >
                   <label class="col-sm-4" for="idBl" style="color:blue;"> Numero BL / Date Arrivee </label>
                      <select style="width: 200px;" class="form-control"  id="slct_Stock_BL">
                      </select>
                   </div> 
                </div> 
                <div class="table-responsive">                
                  <table id="listeProduits" class="display tabStock" class="tableau3" width="100%" border="1">
                    <thead>
                        <tr>
                            <th>IdDesignation</th>
                            <th>Reference</th>
                            <th>Code Barre</th>
                            <th>Quantite</th>
                            <th>Prix_Public</th>
                            <th>Prix_Session</th>
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
        <a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS : 
          <?php 
            if($_SESSION['proprietaire']==1){ 
             
            }
          ?>
        </a>
      </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">     
            <div class="table-responsive"> 
                <br/>
                <div class="container row">
                  <a class="col-md-12 alert alert-info" href="#">
                    <?php 
                      if ($_SESSION['proprietaire']==1) { 
                        echo  ' Valeur Stock en Prix de reviens =  <span id="montantAchats"></span> <=> Valeur Stock en Prix de vente =  <span id="montantVentes"></span> ';
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
                          <th>Forme</th>
                          <th>Prix Public</th>
                          <th>Prix Session</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>
            </div>
        </div>
    </div>
  </div>

  <script type="text/javascript" src="scripts/gestionStock_PH.js"></script>

</body>
</html>


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

<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
<div id="msg_confirm_Stock_P" class="modal fade " role="dialog">
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
                      <th>Prix(Session)</th>
                      <th>Prix(Public)</th>
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
                    <td><input type="number" class="form-control" id="prixSession_Cfm"  /></td>
                    <td><input type="number" class="form-control" id="prixPublic_Cfm"  /></td>
                    <td><input type="date" class="form-control" id="dateExpiration_Cfm"  /></td>
                    <td>
                      <button type="button" onclick="ajt_Stock_P_Cfm()" id="btn_AjtStock_P_Cfm"  class="btn btn-success">
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
</div>
<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

<?php
echo'</body></html>';



?>
