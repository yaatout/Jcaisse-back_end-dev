<?php

/*

R�sum�:

Commentaire:

version:1.1

Auteur: Ibrahima DIOP,EL hadji mamadou korka

Date de modification:07/04/2016; 04-05-2018

*/

/**************** DECLARATION DES ENTETES *************/

?>



<?php require('entetehtml.php'); ?>



<body>

<?php    require('header.php');  ?>

<div class="container">

    <?php if ($_SESSION['enConfiguration']==0) {  ?>

      <center>
        <table border="0">
          <tr>
            <td>
              <form class="form-horizontal" action="insertionProduit.php" method="post" name="upload_excel" enctype="multipart/form-data">
                  <input type="submit" name="Export" class="btn btn-success" value="Générer le modéle CSV d\'Importation d\'un catalogue"/>
              </form>

            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>
              <form class="form-inline" action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post" enctype="multipart/form-data">
                  <input type="file" id="importInput" name="fileImport" data-toggle="modal" onChange="loadCSV()" required>
                  <button type="submit" name="subImport1" value="Importer " class="btn btn-success">Importer un catalogue</button>
              </form>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><button type="button" class="btn btn-success" onclick="ajouter_Produit()">
              <i class="glyphicon glyphicon-plus"></i>Ajout de Produit</button>
            </td>
          </tr>
        </table>
      </center>

    <?php  } else { ?>
      <center>
        <button type="button" class="btn btn-success" onclick="ajouter_Produit()">
            <i class="glyphicon glyphicon-plus"></i>Ajout de Produit
        </button>
      </center>
    <?php  } ?>

    <div id="ajouter_Produit" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Ajouter Produit</h4>
            </div>
            <form class="form" id="form_ajouter_Produit" style="padding:30px 30px;">
              <div class="modal-body">
                <div class="form-group">
                    <label for="inpt_ajt_Produit_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Produit_Reference" autocomplete="off" />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_CodeBarre">Code Barre</label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Produit_CodeBarre"   autocomplete="off"  />
                </div>
                <div class="form-group">
                    <label for="slct_ajt_Produit_Categorie">Categorie </label>
                    <select  id="slct_ajt_Produit_Categorie" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label for="slct_ajt_Produit_Forme">Forme </label>
                    <select  id="slct_ajt_Produit_Forme" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label for="slct_ajt_Produit_Tableau">Tableau </label>
                    <select id="slct_ajt_Produit_Tableau" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_PrixPublic">Prix Public</label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Produit_PrixPublic" value="0"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_PrixSession">Prix Session</label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Produit_PrixSession" value="0"  />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_ajouter_Produit" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Ajouter</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="modifier_Produit" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modifier Produit</h4>
            </div>
            <form class="form" id="form_modifier_Produit" style="padding:30px 30px;" >
              <div class="modal-body">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_mdf_Produit_idProduit"  >
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Produit_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Produit_Reference"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Produit_CodeBarre">Code Barre</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Produit_CodeBarre"  />
                </div>
                <div class="form-group">
                    <label for="slct_mdf_Produit_Categorie">Categorie </label>
                    <select  id="slct_mdf_Produit_Categorie" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label for="slct_mdf_Produit_Forme">Forme </label>
                    <select  id="slct_mdf_Produit_Forme" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label for="slct_mdf_Produit_Tableau">Tableau </label>
                    <select id="slct_mdf_Produit_Tableau" class="form-control"> </select>
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Produit_PrixPublic">Prix Public</label>
                    <input type="number" class="inputbasic form-control" id="inpt_mdf_Produit_PrixPublic" value="0"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Produit_PrixSession">Prix Session</label>
                    <input type="number" class="inputbasic form-control" id="inpt_mdf_Produit_PrixSession" value="0"  />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_modifier_Produit" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Modifier</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="supprimer_Produit" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Supprimer Produit</h4>
            </div>
            <div class="modal-body">
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_spm_Produit_idProduit"  >
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Produit_Reference">Reference : </label>
                        <span id="span_spm_Produit_Reference" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Produit_CodeBarre">Code Barre : </label>
                        <span id="span_spm_Produit_CodeBarre" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Produit_Categorie">Categorie : </label>
                        <span id="span_spm_Produit_Categorie" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Produit_Forme">Forme : </label>
                        <span id="span_spm_Produit_Forme" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_produit_Tableau">Tableau : </label>
                        <span id="span_spm_produit_Tableau" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Produit_PrixPublic">Prix Public : </label>
                        <span id="span_spm_Produit_PrixPublic" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Produit_PrixSession">Prix Session : </label>
                        <span id="span_spm_Produit_PrixSession" ></span>
                    </div>
                    <div class="row" id="spm_impossible" style="display:none"> 
                      <div class="form-group col-md-12">
                        <h5 style="color : red"> </h5>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_supprimer_Produit" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                    </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
    </div>

    <div id="ajouter_Produit_Stock" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Ajouter un Stock du Produit</h4>
            </div>
            <form class="form" id="form_ajouter_Produit" style="padding:30px 30px;">
              <div class="modal-body">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_ajt_Produit_Stock_idProduit"  >
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_Stock_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Produit_Stock_Reference" disabled="true" />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_Stock_Forme">Forme </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Produit_Stock_Forme" disabled="true" />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_Stock_Quantite">Quantite</label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Produit_Stock_Quantite" value="1"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_Stock_DateExpiration">Date Expiration</label>
                    <input type="date" class="inputbasic form-control" id="inpt_ajt_Produit_Stock_DateExpiration" />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_ajouter_Produit_Stock_Ajouter" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Ajouter</span> </button>
                  <button type="button" id="btn_ajouter_Produit_Stock_Terminer" class="btn btn-success pull-right"><span class="mot_Entregistrer">Terminer</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="action_Produit" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" >Desarchiver Produit</h4>
                </div>
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_act_Produit_idProduit"  >
                    </div>
                    <div class="form-group">
                        <label for="span_act_Produit_Reference">Reference : </label>
                        <span id="span_act_Produit_Reference" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Produit_CodeBarre">Code Barre : </label>
                        <span id="span_act_Produit_CodeBarre" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Produit_Categorie">Categorie : </label>
                        <span id="span_act_Produit_Categorie" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Produit_Forme">Forme : </label>
                        <span id="span_act_Produit_Forme" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_produit_Tableau">Tableau : </label>
                        <span id="span_act_produit_Tableau" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Produit_PrixPublic">Prix Public : </label>
                        <span id="span_act_Produit_PrixPublic" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Produit_PrixSession">Prix Session : </label>
                        <span id="span_act_Produit_PrixSession" ></span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_desarchiver_Produit" class="btn btn-success pull-left"><span class="mot_Entregistrer">Desarchiver</span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
    </div>

    <div id="message_Produit" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="message_Produit_titre" ></h4>
                </div>
                  <div class="modal-body">

                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-12 "> 
                      <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="lister_Produit" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Produit a Fusionner </h4>
            </div>
            <div class="modal-body" >
                <div class="table-responsive">                
                  <table id="listeReference" class="display tabStock" class="tableau3" width="100%" border="1">
                    <thead>
                        <tr>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Quantite</th>
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

    <div class="modal fade" id="lister_Doublon" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>
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
                          <th>Quantite</th>
                          <th>Stock</th>
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


    <br>
  <ul class="nav nav-tabs">
    <li class="active" id="listeProduitsEvent">
        <a data-toggle="tab" href="#listeProduitsTab">LISTE DES PRODUITS</a>
    </li>
    <li class="" id="listeArchivesEvent">
        <a data-toggle="tab" href="#listeArchivesTab">LISTE DES ARCHIVES</a>
    </li> 
    <li class="" id="listeDoublonEvent">
        <a data-toggle="tab" href="#listeDoublonTab">LISTE DES DOUBLONS</a>
    </li>
<!--     <li class="" id="listeFusionEvent">
        <a data-toggle="tab" href="#listeFusionTab">FUSION DES PRODUITS</a>
    </li>  -->
  </ul>
  <div class="tab-content">
    <div id="listeProduitsTab" class="tab-pane fade in active">
      <br />
      <div class="table-responsive">

        <div class="container row">
        </div>

        <table id="listeProduits" class="display tabStock" class="tableau3" width="100%" border="1">
          <thead>
              <tr>
                  <th>IdDesignation</th>
                  <th>Reference</th>
                  <th>Code Barre</th>
                  <th>Categorie</th>
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
    <div id="listeArchivesTab" class="tab-pane fade">
      <br />
      <div class="table-responsive">

        <table id="listeArchives" class="display tabStock" class="tableau3" width="100%" border="1">
          <thead>
              <tr>
                  <th>IdDesignation</th>
                  <th>Reference</th>
                  <th>Code Barre</th>
                  <th>Categorie</th>
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
    <div id="listeDoublonTab" class="tab-pane fade">
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
                          <th>Tableau</th>
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
    <div id="listeFusionTab" class="tab-pane fade">
      <br />
      <div class="table-responsive"> 

        <center> 
          <button type="button" class="btn btn-success" id="btn_lister_Produit">
            <i class="glyphicon glyphicon-plus"></i>Ajouter des References
          </button>
        </center> 

        <table id="listeFusion" class="display tabStock" class="tableau3" width="100%" border="1">
          <thead>
              <tr>
                  <th>IdDesignation</th>
                  <th>Reference</th>
                  <th>Code Barre</th>
                  <th>Quantite</th>
                  <th>Forme</th>
                  <th>Tableau</th>
                  <th>Prix Public</th>
                  <th>Prix Session</th>
                  <th>Stock</th>
                  <th>Operations</th>
              </tr>
          </thead>
        </table>

      </div>
    </div> 
  </div>
</div>

<script type="text/javascript" src="scripts/insertionProduit_PH.js"></script>

</body>
</html>



