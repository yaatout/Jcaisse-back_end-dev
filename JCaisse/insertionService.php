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
    <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#ajouter_Service" data-dismiss="modal" >
        <i class="glyphicon glyphicon-plus"></i>Ajout Service
    </button>
  </center>

    <div id="ajouter_Service" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Ajouter Service</h4>
            </div>
            <form class="form" >
              <div class="modal-body" style="padding:40px 50px;">
                <div class="form-group">
                    <label for="inpt_ajt_Service_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Service_Reference"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Service_Unite">Unite Service (US)</label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Service_Unite"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Service_Prix">Prix  Unite Service</label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Service_Prix"  />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_ajouter_Service" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Enregistrer</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="modifier_Service" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modifier Service</h4>
            </div>
            <form class="form" >
              <div class="modal-body" style="padding:40px 50px;">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_mdf_Service_idService"  >
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Service_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Service_Reference"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Service_Unite">Unite Service (US)</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Service_Unite"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Service_Prix">Prix  Unite Service</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Service_Prix"  />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_modifier_Service" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Enregistrer</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="supprimer_Service" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Supprimer Service</h4>
            </div>
            <div class="modal-body">
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_spm_Service_idService"  >
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Service_Reference">Reference : </label>
                        <span id="span_spm_Service_Reference" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Service_Unite">Unite Service (US) : </label>
                        <span id="span_spm_Service_Unite" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Service_Prix">Prix  Unite Service : </label>
                        <span id="span_spm_Service_Prix" ></span>
                    </div>
                    <div class="row" id="spm_impossible" style="display:none"> 
                      <div class="form-group col-md-12">
                        <h5 style="color : red"> </h5>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_supprimer_Service" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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


  <div class="table-responsive">
    <br />
    <table id="listeServices" class="display tabStock" class="tableau3" width="100%" border="1">
      <thead>
          <tr>
              <th>IdDesignation</th>
              <th>Reference</th>
              <th>Unite Service (US)</th>
              <th>Prix Unite Service</th>
              <th>Operations</th>
          </tr>
      </thead>
    </table>
  </div>

</div>



<script type="text/javascript" src="scripts/insertionService.js"></script>
