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

if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

 require('inventaireProduit-pharmacie.php');

}
else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

  require('inventaireProduit-entrepot.php');
 
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

  </div>

  <div class="modal fade" id="lister_Doublon" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4><span class='glyphicon glyphicon-lock'></span> Liste des Doublons </h4>
          </div>
          <div class="modal-body" >
              <div class="table-responsive">                
                <table id="listeFusion" class="table table-bordered" width="100%" border="1">
                  <thead>
                      <tr>
                        <th>Reference</th>
                        <th>Code Barre</th>
                        <th>Unite Stock (US)</th>
                        <th>Prix (US)</th>
                        <th>Prix Unitaire</th>
                        <th>Prix Achat</th>
                        <th>Date</th>
                        <th>Operations</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
          </div> 
      </div>
    </div>
  </div>

  <div class="modal fade" id="lister_Details" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4><span class='glyphicon glyphicon-lock'></span> Details Inventaire </h4>
          </div>
          <div class="modal-body" >
              <div class="table-responsive">  
                <h4> Liste des Inventaires </h4>                
                <table id="listeApres" class="table table-bordered" width="100%" border="1">
                  <thead>
                      <tr>
                        <th>Reference</th>
                        <th>Quantite</th>
                        <th>Date</th>
                        <th>Personnel</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <div class="table-responsive">  
                <h4> Stock Avant Inventaire </h4>              
                <table id="listeAvant" class="table table-bordered" width="100%" border="1">
                  <thead>
                      <tr>
                        <th>Reference</th>
                        <th>Quantite</th>
                        <th>Date</th>
                        <th>Personnel</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
          </div> 
      </div>
    </div>
  </div>
 
  <div class="container">
    <br />
    <ul class="nav nav-tabs">
      <li class="active" id="listeDoublonEvent">
          <a data-toggle="tab" href="#listeDoublonTab">LISTE DES DOUBLONS</a>
      </li>
      <!-- <li id="listeStockEvent">
        <a data-toggle="tab" href="#listeStockTab">LISTE DES STOCKS  </a>
      </li> -->
      <li id="listeNonInventaireEvent">
        <a data-toggle="tab" href="#listeNonInventaireTab">PRODUITS NON INVENTORIES  </a>
      </li>
      <li id="listeInventaireEvent">
        <a data-toggle="tab" href="#listeInventaireTab">PRODUITS INVENTORIES  </a>
      </li>
      <li id="listeDetailsInventaireEvent">
        <a data-toggle="tab" href="#listeDetailsInventaireTab">DETAILS INVENTAIRES </a>
      </li>
      <?php if($_SESSION['iduser']==1 || $_SESSION['iduser']==8 ) { ?>
      <li id="listePersonnelEvent">
        <a data-toggle="tab" href="#listePersonnelTab">PERSONNELS INVENTAIRES </a>
      </li>
      <?php }  ?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="listeDoublonTab" >
          <br />
          <div class="table-responsive">
              <ul class="nav nav-tabs">
                <li class="active" id="listeDoublonDesignationEvent">
                    <a data-toggle="tab" href="#listeDoublonDesignationTab">DOUBLON PAR DESIGNATION</a>
                </li>
                <li class="" id="listeDoublonCodeBarreEvent">
                    <a data-toggle="tab" href="#listeDoublonCodeBarreTab">DOUBLON PAR CODE BARRE</a>
                </li>     
              </ul>
              <div class="tab-content">
                <div id="listeDoublonDesignationTab" class="tab-pane fade in active">
                  <br />
                  <div class="table-responsive">
                    <table id="listeDoublonDesignation" class="display tabStock" class="tableau3" width="100%" border="1">
                      <thead>
                          <tr>
                              <th>IdDesignation</th>
                              <th>Reference</th>
                              <th>Code Barre</th>
                              <th>Unite Stock (US)</th>
                              <th>Prix (US)</th>
                              <th>Prix Unitaire</th>
                              <th>Prix Achat</th>
                              <th>Operations</th>
                          </tr>
                      </thead>
                    </table>
                  </div>
                </div>
                <div id="listeDoublonCodeBarreTab" class="tab-pane fade">
                  <br />
                  <div class="table-responsive">
                    <table id="listeDoublonCodebarre" class="display tabStock" class="tableau3" width="100%" border="1">
                      <thead>
                          <tr>
                              <th>IdDesignation</th>
                              <th>Reference</th>
                              <th>Code Barre</th>
                              <th>Unite Stock (US)</th>
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
        </div>  
        <!-- <div class="tab-pane fade" id="listeStockTab">   
            <br />
            <div class="table-responsive">  

                <table id="listeStock" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Unite Stock (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
                          <th>Prix Achat</th>
                          <th>Quantite</th>
                          <th>Inventaire</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>

            </div>
        </div> -->
        <div class="tab-pane fade" id="listeInventaireTab">   
            <br />
            <div class="table-responsive">  

                <table id="listeInventaire" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Unite Stock (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
                          <th>Prix Achat</th>
                          <th>Quantite</th>
                          <th>Inventaire</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>

            </div>
        </div>
        <div class="tab-pane fade" id="listeNonInventaireTab"> 
        <?php if($_SESSION['profil']=="Admin") { ?>
              <br /> 
              <center> 
                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#vider_Stock" data-dismiss="modal">
                    <i class="glyphicon glyphicon-plus"></i>Vider Stocks
                  </button>
              </center> 
              <div id="vider_Stock" class="modal fade"  role="dialog">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title">Confirmation</h4>
                          </div>
                            <div class="modal-body">
                              <h4><center>Voulez-vous vraiment vider les stocks des produits non inventoriés?</center></h4>
                            </div>
                            <div class="modal-footer">
                              <div class="col-sm-6 "> 
                                <button type="button" id="btn_vider_Stock" class="btn btn-danger pull-left">Vider </button>
                              </div>
                              <div class="col-sm-6 "> 
                                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                              </div>
                            </div>
                      </div>
                  </div>
              </div>
            <?php } ?>
            <br />
            <div class="table-responsive">  

                <table id="listeNonInventaire" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Unite Stock (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
                          <th>Prix Achat</th>
                          <th>Quantite</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>

            </div>
        </div>
        <div class="tab-pane fade" id="listeDetailsInventaireTab">   
            <br />
            <div class="table-responsive">  

                <table id="listeDetailsInventaire" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Unite Stock (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
                          <th>Prix Achat</th>
                          <th>Avant</th>
                          <th>Apres</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>

            </div>
        </div>
        <?php if($_SESSION['iduser']==1 || $_SESSION['iduser']==8 ) { ?>
        <div class="tab-pane fade" id="listePersonnelTab">   
            <br />
            <div class="table-responsive">  

                <table id="listePersonnel" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Nom</th>
                          <th>Prenom</th>
                          <th>Jour 1</th>
                          <th>Jour 2</th>
                          <th>Jour 3</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>

            </div>
        </div>
        <?php }  ?>
    </div>
  </div>

</body>
</html>

  <script type="text/javascript" src="scripts/inventaireProduit.js"></script>
  <script>
      $(document).ready(function() {
        $("#listePersonnelEvent").on( "click", function (e){
        e.preventDefault();
        $('#listePersonnel').DataTable({
          'processing': true,
          'serverSide': true,
          'destroy': true,
          'serverMethod': 'post',
          'ajax': {
              'url':'datatables/inventaireProduit_listePersonnel.php'
          },
          'dom': 'Blfrtip',
          "buttons": ['csv','print', 'excel', 'pdf'],
          "ordering": true,
          "order": [[0, 'asc'],[1, 'desc']],
          "lengthMenu": [10, 25, 50, 100, 500, 5000, 7500],
          'columns': [
            { data: 'idutilisateur' },
            { data: 'nom' },
            { data: 'prenom' },
            { data: 'jour_1' },
            { data: 'jour_2' },
            { data: 'jour_3' },
            { data: 'operations' }
          ],
          "fnCreatedRow": function( nRow, data, iDataIndex ) {
            $(nRow).attr('class', "personnel"+data['idutilisateur']);
          },
          'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0] },
            {
              'targets': [6], /* column index */
              'orderable': false, /* true or false */
          }
          ]
        });  
      });
      });
    </script>

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
