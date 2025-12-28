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
                        <th>Forme</th>
                        <th>Tableau</th>
                        <th>Prix Public</th>
                        <th>Prix Session</th>
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
                              <th>Forme</th>
                              <th>Tableau/th>
                              <th>Prix Public</th>
                              <th>Prix Session</th>
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
                              <th>Forme</th>
                              <th>Tableau</th>
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
                          <th>Forme</th>
                          <th>Tableau</th>
                          <th>Prix Public</th>
                          <th>Prix Session</th>
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
                          <th>Forme</th>
                          <th>Tableau</th>
                          <th>Prix Public</th>
                          <th>Prix Session</th>
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
                  <button disabled="true" type="button" class="btn btn-danger" data-toggle="modal" data-target="#vider_Stock" data-dismiss="modal">
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
                          <th>Forme</th>
                          <th>Tableau</th>
                          <th>Prix Public</th>
                          <th>Prix Session</th>
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
                          <th>Forme</th>
                          <th>Tableau</th>
                          <th>Prix Public</th>
                          <th>Prix Session</th>
                          <th>Avant</th>
                          <th>Apres</th>
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

  <script type="text/javascript" src="scripts/inventaireProduit_PH.js"></script>


