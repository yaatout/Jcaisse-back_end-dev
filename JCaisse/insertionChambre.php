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
	header('Location:../index.php');
}
require('connection.php');

require('connectionPDO.php');

require('declarationVariables.php');
  
?>


<?php require('entetehtml.php'); ?>

<body>
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

 
?>

<div class="container">

    <center>
      <button type="button" class="btn btn-success" onclick="ajouter_Chambre()">
          <i class="glyphicon glyphicon-plus"></i>Ajout Chambre
      </button>
    </center>

    <div id="ajouter_Chambre" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Ajouter Chambre</h4>
            </div>
            <form class="form" id="form_ajouter_Chambre" style="padding:30px 30px;">
              <div class="modal-body">
                <div class="form-group">
                    <label for="inpt_ajt_Chambre_Reference">Nom </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Chambre_Reference"  autocomplete="off"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Chambre_Numero">Numero</label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Chambre_Numero"  autocomplete="off"   />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Chambre_Type">Type </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Chambre_Type"  autocomplete="off"   />
                </div>
                <div class="form-group">
                    <label for="slct_ajt_Chambre_Categorie">Batiment </label>
                    <select  id="slct_ajt_Chambre_Categorie" class="form-control">
                      <option>----------</option>
                      <option>Aubonne</option>
                      <option>Chateau de Rolle</option>
                      <option>Gimel</option>
                      <option>Gland</option>
                      <option>Goree</option>
                      <option>Mont sur Rolle</option>
                      <option>Rolle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Chambre_Prix">Prix</label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Chambre_Prix" value="0"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Chambre_Description">Descrition</label>
                    <textarea id="inpt_ajt_Chambre_Description" class="inputbasic form-control">

                    </textarea>
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_ajouter_Chambre" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Ajouter</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="modifier_Chambre" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modifier Chambre</h4>
            </div>
            <form class="form" id="form_modifier_Chambre" style="padding:30px 30px;" >
              <div class="modal-body">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_mdf_Chambre_idChambre"  >
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Chambre_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Chambre_Reference"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Chambre_Numero">Numero</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Chambre_Numero"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Chambre_Type">Type</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Chambre_Type"  />
                </div>
                <div class="form-group">
                    <label for="slct_mdf_Chambre_Categorie">Site </label>
                    <select  id="slct_mdf_Chambre_Categorie" class="form-control">
                    </select>
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Chambre_Prix">Prix Saison</label>
                    <input type="number" class="inputbasic form-control" id="inpt_mdf_Chambre_Prix" value="0"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Chambre_Description">Descrition</label>
                    <textarea id="inpt_mdf_Chambre_Description" class="inputbasic form-control">

                    </textarea>
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_modifier_Chambre" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Modifier</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="supprimer_Chambre" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Supprimer Chambre</h4>
            </div>
            <div class="modal-body">
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_spm_Chambre_idChambre"  >
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Chambre_Reference">Reference : </label>
                        <span id="span_spm_Chambre_Reference" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Chambre_Numero">Numero : </label>
                        <span id="span_spm_Chambre_Numero" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Chambre_Type">Type : </label>
                        <span id="span_spm_Chambre_Type" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Chambre_Categorie">Site : </label>
                        <span id="span_spm_Chambre_Categorie" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Chambre_Prix">Prix : </label>
                        <span id="span_spm_Chambre_Prix" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Chambre_Description">Description : </label>
                        <span id="span_spm_Chambre_Description" ></span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_supprimer_Chambre" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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


    <div id="action_Chambre" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" >Desarchiver Chambre</h4>
                </div>
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_act_Chambre_idChambre"  >
                    </div>
                    <div class="form-group">
                        <label for="span_act_Chambre_Reference">Reference : </label>
                        <span id="span_act_Chambre_Reference" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Chambre_Numero">Code Barre : </label>
                        <span id="span_act_Chambre_Numero" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Chambre_Categorie">Categorie : </label>
                        <span id="span_act_Chambre_Categorie" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Chambre_UniteStock">Unite Stock : </label>
                        <span id="span_act_Chambre_UniteStock" ></span>
                    </div>
                    <div class="row div_unite_Stock" style="display:none">
                      <div class="form-group col-md-6">
                        <label for="span_act_Chambre_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> : </label>
                        <span id="span_act_Chambre_NombreArticles" ></span>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="span_act_Chambre_PrixUniteStock">Prix du <span class="span_uniteStock"></span> : </label>
                        <span id="span_act_Chambre_PrixUniteStock" ></span>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Chambre_PrixUnitaire">Prix  Unitaire : </label>
                        <span id="span_act_Chambre_PrixUnitaire" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_act_Chambre_PrixAchat">Prix de Reviens : </label>
                        <span id="span_act_Chambre_PrixAchat" ></span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_desarchiver_Chambre" class="btn btn-success pull-left"><span class="mot_Entregistrer">Desarchiver</span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
    </div>

    <div id="message_Chambre" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="message_Chambre_titre" ></h4>
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

    <br>
  <ul class="nav nav-tabs">
    <li class="active" id="listeChambresEvent">
        <a data-toggle="tab" href="#listeChambresTab">LISTE DES CHAMBRES</a>
    </li>   
  </ul>
  <div class="tab-content">
    <div id="listeChambresTab" class="tab-pane fade in active">
      <br />
      <div class="table-responsive">

        <div class="container row">
        </div>

          <table id="listeChambres" class="display" class="tableau3" width="100%" border="1">
              <thead>
                  <tr>
                      <th>IdDesignation</th>
                      <th>Reference</th>
                      <th>Numero</th>
                      <th>Type</th>
                      <th>Site</th>
                      <th>Prix</th>
                      <th>Description</th>
                      <th>Operations</th>
                  </tr>
              </thead>
            </table>

      </div>
    </div>       
  </div>
</div>

<script type="text/javascript" src="scripts/insertionChambre.js"></script>

