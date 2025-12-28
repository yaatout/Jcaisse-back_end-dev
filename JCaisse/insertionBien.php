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
      <button type="button" class="btn btn-success" onclick="ajouter_Bien()">
          <i class="glyphicon glyphicon-plus"></i>Ajout Bien
      </button>
    </center>

    <div id="ajouter_Bien" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Ajouter Bien</h4>
            </div>
            <form class="form" id="form_ajouter_Bien" style="padding:30px 30px;">
              <div class="modal-body">
                <div class="form-group">
                    <label for="inpt_ajt_Bien_Reference">Nom </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Bien_Reference"  autocomplete="off"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Bien_Numero">Numero</label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Bien_Numero"  autocomplete="off"   />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Bien_Type">Type </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Bien_Type"  autocomplete="off"   />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Bien_Prix">Prix</label>
                    <input type="number" class="inputbasic form-control" id="inpt_ajt_Bien_Prix" value="0"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Bien_Description">Description</label>
                    <textarea id="inpt_ajt_Bien_Description" class="inputbasic form-control">

                    </textarea>
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_ajouter_Bien" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Ajouter</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="modifier_Bien" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modifier Bien</h4>
            </div>
            <form class="form" id="form_modifier_Bien" style="padding:30px 30px;" >
              <div class="modal-body">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_mdf_Bien_idBien"  >
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Bien_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Bien_Reference"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Bien_Numero">Numero</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Bien_Numero"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Bien_Type">Type</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Bien_Type"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Bien_Prix">Prix</label>
                    <input type="number" class="inputbasic form-control" id="inpt_mdf_Bien_Prix" value="0"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Bien_Description">Descrition</label>
                    <textarea id="inpt_mdf_Bien_Description" class="inputbasic form-control">

                    </textarea>
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_modifier_Bien" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Modifier</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="supprimer_Bien" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Supprimer Bien</h4>
            </div>
            <div class="modal-body">
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_spm_Bien_idBien"  >
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Bien_Reference">Reference : </label>
                        <span id="span_spm_Bien_Reference" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Bien_Numero">Numero : </label>
                        <span id="span_spm_Bien_Numero" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Bien_Type">Type : </label>
                        <span id="span_spm_Bien_Type" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Bien_Prix">Prix : </label>
                        <span id="span_spm_Bien_Prix" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Bien_Description">Description : </label>
                        <span id="span_spm_Bien_Description" ></span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_supprimer_Bien" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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

    <br>
  <ul class="nav nav-tabs">
    <li class="active" id="listeBiensEvent">
        <a data-toggle="tab" href="#listeBiensTab">LISTE DES BIENS</a>
    </li>   
  </ul>
  <div class="tab-content">
    <div id="listeBiensTab" class="tab-pane fade in active">
      <br />
      <div class="table-responsive">

        <div class="container row">
        </div>

          <table id="listeBiens" class="display" class="tableau3" width="100%" border="1">
              <thead>
                  <tr>
                      <th>IdDesignation</th>
                      <th>Reference</th>
                      <th>Numero</th>
                      <th>Type</th>
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

<script type="text/javascript" src="scripts/insertionBien.js"></script>

