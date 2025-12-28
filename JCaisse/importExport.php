<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/

session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');

require('declarationVariables.php');


?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

?>

<div class="container">
        <center>
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajoutVoyage" data-dismiss="modal" >
              <i class="glyphicon glyphicon-plus"></i>Ajout Import/Export 
          </button>
        </center>
        <br />
  <ul class="nav nav-tabs">
    <li class="active">
        <a data-toggle="tab" href="#LISTEVOYAGES">LISTE DES IMPORTS/EXPORTS 
          <?php
            if($_SESSION['proprietaire']==1){
            }
            ?>
        </a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="LISTEVOYAGES" class="tab-pane fade in active">
      <div class="table-responsive">

        <table id="tableVoyage" class="display tabVoyage" border="1">
          <thead>
            <tr>
                <th>Ordre</th>
                <th>Destination</th>
                <th>Motif</th>
                <th>Date</th>
                <th>Operations</th>
            </tr>
          </thead>
        </table>

        <script type="text/javascript">
          $(document).ready(function() {
              $("#tableVoyage").dataTable({
                "bProcessing": true,
                "sAjaxSource": "ajax/listerVoyageAjax.php",
                "aoColumns": [
                      { mData: "0" } ,
                      { mData: "1" },
                      { mData: "2" },
                      { mData: "3" },
                      { mData: "4" },
                    ],
                    
              });  
          });
        </script>

        <div id="ajoutVoyage" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Ajouter Import/Export</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                  <form class="form" >
                    <div class="form-group">
                      <label for="ajtDestination">Destination </label>
                      <input type="text" class="inputbasic form-control" id="ajtDestination"  />
                    </div>
                    <div class="form-group">
                      <label for="ajtMotif">Motif </label>
                      <input type="text" class="inputbasic form-control" id="ajtMotif" />
                    </div>
                    <div class="form-group">
                      <label for="ajtDateVoyage">Date </label>
                      <input type="date" class="inputbasic form-control" id="ajtDateVoyage" />
                    </div>
                    <div class="modal-footer row">
                      <div class="col-sm-3 "> <input type="button" id="btn_ajt_Voyage" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                      <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>

        <div id="modifierVoyage" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Modification Import/Export</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                  <form class="form"  >
                    <div class="form-group">
                      <input type="hidden"  id="idVY_Mdf"  />
                      <input type="hidden"  id="ordreVY_Mdf"  />
                    </div>
                    <div class="form-group">
                      <label for="destinationVY_Mdf">Destination </label>
                      <input type="text" class="inputbasic form-control" id="destinationVY_Mdf"  />
                    </div>
                    <div class="form-group">
                      <label for="motifVY_Mdf">Motif </label>
                      <input type="text" class="inputbasic form-control" id="motifVY_Mdf" />
                    </div>
                    <div class="form-group">
                      <label for="dateVY_Mdf">Date </label>
                      <input type="date" class="inputbasic form-control" id="dateVY_Mdf" />
                    </div>
                    <div class="modal-footer row">
                      <div class="col-sm-3 "> <input type="button" id="btn_mdf_Voyage" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                      <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>

        <div id="supprimerVoyage" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Suppression Import/Export</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                  <form class="form"  >
                    <div class="form-group">
                      <input type="hidden"  id="idVY_Spm"  />
                      <input type="hidden"  id="ordreVY_Spm"  />
                    </div>
                    <div class="form-group">
                      <label for="destinationVY_Mdf">Destination </label>
                      <input type="text" disabled="true" class="inputbasic form-control" id="destinationVY_Mdf"  />
                    </div>
                    <div class="form-group">
                      <label for="motifVY_Spm">Motif </label>
                      <input type="text" disabled="true" class="inputbasic form-control" id="motifVY_Spm" />
                    </div>
                    <div class="form-group">
                      <label for="dateVY_Spm">Date </label>
                      <input type="date" disabled="true" class="inputbasic form-control" id="dateVY_Spm" />
                    </div>
                    <div class="modal-footer row">
                      <div class="col-sm-3 "> <input type="button" id="btn_spm_Voyage" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                      <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

?>
