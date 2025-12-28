<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/

session_start();

date_default_timezone_set('Africa/Dakar');

if(!$_SESSION['iduser']){
	header('Location:../JCaisse/index.php');
}
require('connection.php');

require('connectionPDO.php');

require('declarationVariables.php');


if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
  require('insertionProduit-pharmacie.php');
}
else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
  // if ($_SESSION['idBoutique'] == 196) {
      
  //   require('insertImport.php');
  // } else {
      
    require('insertionProduit-entrepot.php');
  // }
} 
else {
  
?>


<?php require('entetehtml.php'); ?>

<body>
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

 
?>

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
              <i class="glyphicon glyphicon-plus"></i>Ajout Produit</button>
            </td>
          </tr>
        </table>
      </center>

    <?php  } else { ?>
      <center>
        <button type="button" class="btn btn-success" onclick="ajouter_Produit()">
            <i class="glyphicon glyphicon-plus"></i>Ajout Produit
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
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Produit_Reference"  autocomplete="off"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_CodeBarre">Code Barre</label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Produit_CodeBarre"  autocomplete="off"   />
                </div>
                <div class="form-group">
                    <label for="slct_ajt_Produit_Categorie">Categorie </label>
                    <select  id="slct_ajt_Produit_Categorie" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label for="slct_ajt_Produit_UniteStock">Unite Stock (US) </label>
                    <select onchange="choix_unite(this.value)" id="slct_ajt_Produit_UniteStock" class="form-control"></select>
                </div>
                <div class="row div_unite_Stock" style="display:none">
                  <div class="form-group col-md-6">
                    <label for="inpt_ajt_Produit_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> </label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Produit_NombreArticles" value="1" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inpt_ajt_Produit_PrixUniteStock">Prix du <span class="span_uniteStock"></span> </label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Produit_PrixUniteStock" value="0"  />
                  </div>
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_PrixUnitaire">Prix Unitaire</label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Produit_PrixUnitaire" value="0"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_PrixAchat">Prix de Reviens</label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Produit_PrixAchat" value="0"  />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 col-xs-6"> 
                  <button type="button" id="btn_ajouter_Produit" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Ajouter</span> </button>
                </div>
                <div class="col-sm-6 col-xs-6"> 
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
                    <select  id="slct_mdf_Produit_Categorie" class="inputbasic form-control"></select>
                </div>
                <div class="form-group">
                    <label for="slct_mdf_Produit_UniteStock">Unite Stock (US) </label>
                    <select onchange="choix_unite(this.value)" id="slct_mdf_Produit_UniteStock" class="inputbasic form-control"></select>
                </div>
                <div class="row div_unite_Stock">
                  <div class="form-group col-md-6">
                    <label for="inpt_mdf_Produit_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> </label>
                    <input type="number" class="inputbasic form-control" id="inpt_mdf_Produit_NombreArticles" value="1" />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inpt_mdf_Produit_PrixUniteStock">Prix du <span class="span_uniteStock"></span> </label>
                    <input type="number" class="inputbasic form-control" id="inpt_mdf_Produit_PrixUniteStock" value="0"  />
                  </div>
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Produit_PrixUnitaire">Prix Unitaire</label>
                    <input type="number" class="inputbasic form-control" id="inpt_mdf_Produit_PrixUnitaire" value="0"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Produit_PrixAchat">Prix de Reviens</label>
                    <input type="number" class="inputbasic form-control" id="inpt_mdf_Produit_PrixAchat" value="0"  />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 col-xs-6"> 
                  <button type="button" id="btn_modifier_Produit" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Modifier</span> </button>
                </div>
                <div class="col-sm-6 col-xs-6"> 
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
                        <label for="span_spm_Produit_UniteStock">Unite Stock : </label>
                        <span id="span_spm_Produit_UniteStock" ></span>
                    </div>
                    <div class="row div_unite_Stock" style="display:none">
                      <div class="form-group col-md-6">
                        <label for="span_spm_produit_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> : </label>
                        <span id="span_spm_produit_NombreArticles" ></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="span_spm_Produit_PrixUniteStock">Prix du <span class="span_uniteStock"></span> : </label>
                        <span id="span_spm_Produit_PrixUniteStock" ></span>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Produit_PrixUnitaire">Prix  Unitaire : </label>
                        <span id="span_spm_Produit_PrixUnitaire" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Produit_PrixAchat">Prix de Reviens : </label>
                        <span id="span_spm_Produit_PrixAchat" ></span>
                    </div>
                    <div class="row" id="spm_impossible" style="display:none"> 
                      <div class="form-group col-md-12">
                        <h5 style="color : red"> </h5>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 col-xs-6"> 
                      <button type="button" id="btn_supprimer_Produit" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
                    </div>
                    <div class="col-sm-6 col-xs-6"> 
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
                    <label for="inpt_ajt_Produit_Stock_Quantite">Quantite</label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Produit_Stock_Quantite" value="1"  />
                </div>
                <div class="form-group">
                    <label for="slct_ajt_Produit_Stock_UniteStock">Unite Stock (US) </label>
                    <select id="slct_ajt_Produit_Stock_UniteStock" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Produit_Stock_DateExpiration">Date Expiration</label>
                    <input type="date" class="inputbasic form-control" id="inpt_ajt_Produit_Stock_DateExpiration" />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 col-xs-6"> 
                  <button type="button" id="btn_ajouter_Produit_Stock_Ajouter" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Ajouter</span> </button>
                  <button type="button" id="btn_ajouter_Produit_Stock_Terminer" class="btn btn-success pull-right"><span class="mot_Entregistrer">Terminer</span> </button>
                </div>
                <div class="col-sm-6 col-xs-6"> 
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
                        <label for="span_act_Produit_UniteStock">Unite Stock : </label>
                        <span id="span_act_Produit_UniteStock" ></span>
                    </div>
                    <div class="row div_unite_Stock" style="display:none">
                      <div class="form-group col-md-6">
                        <label for="span_act_produit_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> : </label>
                        <span id="span_act_produit_NombreArticles" ></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="span_act_Produit_PrixUniteStock">Prix du <span class="span_uniteStock"></span> : </label>
                        <span id="span_act_Produit_PrixUniteStock" ></span>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Produit_PrixUnitaire">Prix  Unitaire : </label>
                        <span id="span_act_Produit_PrixUnitaire" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Produit_PrixAchat">Prix de Reviens : </label>
                        <span id="span_act_Produit_PrixAchat" ></span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 col-xs-6"> 
                      <button type="button" id="btn_desarchiver_Produit" class="btn btn-success pull-left"><span class="mot_Entregistrer">Desarchiver</span> </button>
                    </div>
                    <div class="col-sm-6 col-xs-6"> 
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
                          <th>Unite Stock (US)</th>
                          <th>Nombre Articles (US)</th>
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
                          <th>Unite Stock (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
                          <th>Prix Achat</th>
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
    </li> -->     
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
                  <th>Unite Stock (US)</th>
                  <th>Nombre Articles (US)</th>
                  <th>Prix (US)</th>
                  <th>Prix Unitaire</th>
                  <th>Prix Achat</th>
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
                  <th>Unite Stock (US)</th>
                  <th>Nombre Articles (US)</th>
                  <th>Prix (US)</th>
                  <th>Prix Unitaire</th>
                  <th>Prix Achat</th>
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
    <div id="listeFusionTab" class="tab-pane fade">
      <br />
      <div class="table-responsive"> 

        <center> 
          <button type="button" class="btn btn-success" id="btn_lister_Produit">
            <i class="glyphicon glyphicon-plus"></i>Ajouter des References
          </button>
        </center> 
        <br />
        <table id="listeFusion" class="display tabStock" class="tableau3" width="100%" border="1">
          <thead>
              <tr>
                  <th>IdDesignation</th>
                  <th>Reference</th>
                  <th>CodeBarre</th>
                  <th>Quantite</th>
                  <th>Unite Stock (US)</th>
                  <th>Nombre Article US</th>
                  <th>Prix_US</th>
                  <th>Prix Unitaire</th>
                  <th>Prix Achat</th>
                  <th>Stock</th>
                  <th>Operations</th>
              </tr>
          </thead>
        </table>

      </div>
    </div>
  </div>
</div>


<script type="text/javascript" src="scripts/insertionProduit.js"></script>

<?php } ?>

