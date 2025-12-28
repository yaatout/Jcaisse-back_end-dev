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
        <br />
          <center>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajoutEntrepot" data-dismiss="modal" >
                <i class="glyphicon glyphicon-plus"></i>Ajouter Entrepot 
          </button>
        </center>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#entrepot">ENTREPOT</a></li>
  </ul>
  <div class="tab-content">
    <div id="entrepot" class="tab-pane fade in active">
      <div class="table-responsive">
        <table id="tableEntrepot" class="display tabEntrepot table" class="tableau3" align="left" border="1">
          <thead>
            <tr>
                <th>Ordre</th>
                <th>Nom Entrepot</th>
                <th>Adresse Entrepot</th>
                <th>Operations</th>
            </tr>
          </thead>
        </table>

        <script type="text/javascript">
          $(document).ready(function() {
              $("#tableEntrepot").dataTable({
                "bProcessing": true,
                "sAjaxSource": "ajax/listerEntrepotAjax.php",
                "aoColumns": [
                      { mData: "0" } ,
                      { mData: "1" },
                      { mData: "2" },
                      { mData: "3" }
                    ],
                    
              });  
          });
        </script>

          <div id="ajoutEntrepot" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ajouter Entrepot</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" >
                      <div class="form-group">
                        <label for="ajtNomEntrepot">Nom Entrepot </label>
                        <input type="text" class="inputbasic form-control" id="ajtNomEntrepot" />
                      </div>
                      <div class="form-group">
                        <label for="ajtAdresseEntrepot">Adresse Entrepot </label>
                        <input type="text" class="inputbasic form-control" id="ajtAdresseEntrepot" />
                      </div>
                      <div class="modal-footer row">
                        <div class="col-sm-3 "> <input type="button" id="btn_ajt_Entrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                        <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div> 

          <div id="modifierEntrepot" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modification Entrepot</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" >
                      <div class="form-group">
                        <input type="hidden"  id="idEntrepot_Mdf"  />
                        <input type="hidden"  id="ordreEntrepot_Mdf"  />
                      </div>
                      <div class="form-group">
                        <label for="nomEntrepot_Mdf">Nom Entrepot </label>
                        <input type="text" class="inputbasic form-control" id="nomEntrepot_Mdf" />
                      </div>
                      <div class="form-group">
                        <label for="adresseEntrepot_Mdf">Adresse Entrepot</label>
                        <input type="text" class="inputbasic form-control" id="adresseEntrepot_Mdf" />
                      </div>
                      <div class="modal-footer row">
                        <div class="col-sm-3 "> <input type="button" id="btn_mdf_Entrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                        <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div>

          <div id="supprimerEntrepot" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Suppression Entrepot</h4>
                  </div>
                  <div class="modal-body" style="padding:40px 50px;">
                    <form class="form" >
                      <div class="form-group">
                        <input type="hidden"  id="idEntrepot_Spm"  />
                        <input type="hidden"  id="ordreEntrepot_Spm"  />
                      </div>
                      <div class="form-group">
                        <label for="nomEntrepot_Spm">Numero </label>
                        <input type="text" disabled="true" class="inputbasic form-control" id="nomEntrepot_Spm" />
                      </div>
                      <div class="form-group">
                        <label for="adresseEntrepot_Spm">Montant TTC </label>
                        <input type="text" disabled="true" class="inputbasic form-control" id="adresseEntrepot_Spm" />
                      </div>
                      <div class="modal-footer row">
                        <div class="col-sm-3 "> <input type="button" id="btn_spm_Entrepot" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
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

