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

<body>
<?php  require('header.php'); ?>

      <?php if ($_SESSION['importExp']==0){ ?>
        <center>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajouter_Fournisseur" data-dismiss="modal" >
                <i class="glyphicon glyphicon-plus"></i>Ajout Fournisseur 
            </button>
        </center>
      <?php } ?>

<div class="container">
  <br />
  <?php if ($_SESSION['importExp']==1){ ?>
    <ul class="nav nav-tabs">
        <li class="active" id="listeVoyagesEvent">
          <a data-toggle="tab" href="#LISTEVOYAGES">LISTE DES IMPORTS/EXPORTS </a>
        </li>
        <li id="listeFournisseursEvent">
          <a data-toggle="tab" href="#LISTEFOURNISSEURS">LISTE DES FOURNISSEURS
              <?php 
                if ($_SESSION['proprietaire']==1) { 
                  echo  ' => Montant des Fournisseurs =  <span id="montantSolde"></span>';
                }
              ?>
          </a>
        </li>
    </ul>
  <?php 
    } else { 
  ?>
    <ul class="nav nav-tabs">
      <li class="active">
          <a data-toggle="tab" href="#LISTEFOURNISSEURS">LISTE DES FOURNISSEURS </a>
      </li>
    </ul>
  <?php } ?>

  <?php if ($_SESSION['importExp']==1){ ?>
    <div class="tab-content">
        <div id="LISTEVOYAGES" class="tab-pane fade in active">
            <br />
            <center>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajoutVoyage" data-dismiss="modal" >
                    <i class="glyphicon glyphicon-plus"></i>Ajout Import/Export 
                </button>
            </center>
            <div class="table-responsive">
              <table id="tableVoyage" class="display tabVoyage" width="100%" border="1">
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
                            <input type="text" class="form-control" id="ajtDestination"  />
                          </div>
                          <div class="form-group">
                            <label for="ajtMotif">Motif </label>
                            <input type="text" class="form-control" id="ajtMotif" />
                          </div>
                          <div class="form-group">
                            <label for="ajtDateVoyage">Date </label>
                            <input type="date" class="form-control" id="ajtDateVoyage" />
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
                            <input type="text" class="form-control" id="destinationVY_Mdf"  />
                          </div>
                          <div class="form-group">
                            <label for="motifVY_Mdf">Motif </label>
                            <input type="text" class="form-control" id="motifVY_Mdf" />
                          </div>
                          <div class="form-group">
                            <label for="dateVY_Mdf">Date </label>
                            <input type="date" class="form-control" id="dateVY_Mdf" />
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
                            <input type="text" disabled="true" class="form-control" id="destinationVY_Mdf"  />
                          </div>
                          <div class="form-group">
                            <label for="motifVY_Spm">Motif </label>
                            <input type="text" disabled="true" class="form-control" id="motifVY_Spm" />
                          </div>
                          <div class="form-group">
                            <label for="dateVY_Spm">Date </label>
                            <input type="date" disabled="true" class="form-control" id="dateVY_Spm" />
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
        <div id="LISTEFOURNISSEURS" class="tab-pane fade">
          <br />
          <center>
              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajouter_Fournisseur" data-dismiss="modal" >
                  <i class="glyphicon glyphicon-plus"></i>Ajout Fournisseur 
              </button>
          </center>
          <div class="table-responsive">

            <table id="listeFournisseurs" class="display tabFournisseur" width="100%" border="1">
              <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Telephone</th>
                    <th>Banque</th>
                    <th>Numero compte</th>
                    <th>Montant à Verser</th>
                    <th>Operations</th>
                </tr>
              </thead>
            </table>

          </div>
        </div>
    </div>
  <?php 
    } else { 
  ?>
    <div class="tab-content">
      <div id="LISTEFOURNISSEURS" class="tab-pane fade in active">
      <br />
      <div class="table-responsive">

          <div class="container row">
            <a class="col-md-12 alert alert-info" href="#">
              <?php 
                if ($_SESSION['proprietaire']==1) { 
                  echo  ' Montant des Fournisseurs =  <span id="montantSolde"></span>';
                }
              ?>
            </a>
          </div>

        <table id="listeFournisseurs" class="display tabFournisseur" width="100%" border="1">
          <thead>
            <tr>
                <th>Ordre</th>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Telephone</th>
                <th>Banque</th>
                <th>Numero compte</th>
                <th>Montant à Verser</th>
                <th>Operations</th>
            </tr>
          </thead>
        </table>

      </div>
    </div>
  <?php } ?>
</div>

    <div id="ajouter_Fournisseur" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Ajouter Fournisseur</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
              <form class="form" >
                <div class="form-group">
                  <label for="inpt_ajt_Fournisseur_Nom">Nom </label>
                  <input type="text" class="form-control" id="inpt_ajt_Fournisseur_Nom"  />
                </div>
                <div class="form-group">
                  <label for="inpt_ajt_Fournisseur_Adresse">Adresse </label>
                  <input type="text" class="form-control" id="inpt_ajt_Fournisseur_Adresse" />
                </div>
                <div class="form-group">
                  <label for="inpt_ajt_Fournisseur_Telephone">Telephone </label>
                  <input type="text" class="form-control" id="inpt_ajt_Fournisseur_Telephone" />
                </div>
                <div class="form-group">
                  <label for="slct_ajt_Fournisseur_Banque">Banque </label>
                  <select class="form-control" id="slct_ajt_Fournisseur_Banque">
                    <?php
                    echo '<optgroup label="Compte bancaire"> ';
                        $sqlb="select * from `aaa-banque` ORDER BY idBanque ASC";
                        $resb=mysql_query($sqlb);
                        while($b =mysql_fetch_assoc($resb)){
                            echo '<option value="'.$b["nom"].'">'.$b["nom"].'</option>';
                        }
                    echo '</optgroup>';
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inpt_ajt_Fournisseur_NumBanque">Numero compte bancaire </label>
                  <input type="text" class="form-control" id="inpt_ajt_Fournisseur_NumBanque" />
                </div>
                <div class="modal-footer">
                  <div class="col-sm-6 "> 
                    <button type="button" id="btn_ajouter_Fournisseur" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Enregistrer</span> </button>
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

    <div id="modifier_Fournisseur" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modification Fournisseur</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
              <form class="form"  >
                <div class="form-group">
                  <input type="hidden"  id="inpt_mdf_Fournisseur_idFournisseur"  />
                </div>
                <div class="form-group">
                  <label for="inpt_mdf_Fournisseur_Nom">Nom </label>
                  <input type="text" class="form-control" id="inpt_mdf_Fournisseur_Nom"  />
                </div>
                <div class="form-group">
                  <label for="inpt_mdf_Fournisseur_Adresse">Adresse </label>
                  <input type="text" class="form-control" id="inpt_mdf_Fournisseur_Adresse" />
                </div>
                <div class="form-group">
                  <label for="inpt_mdf_Fournisseur_Telephone">Telephone </label>
                  <input type="text" class="form-control" id="inpt_mdf_Fournisseur_Telephone" />
                </div>
                <div class="form-group">
                  <label for="slct_mdf_Fournisseur_Banque">Banque </label>
                  <select class="form-control" id="slct_mdf_Fournisseur_Banque">
                    <?php
                    echo '<optgroup label="Compte bancaire"> ';
                        $sqlb="select * from `aaa-banque` ORDER BY idBanque ASC";
                        $resb=mysql_query($sqlb);
                        while($b =mysql_fetch_assoc($resb)){
                            echo '<option value="'.$b["nom"].'">'.$b["nom"].'</option>';
                        }
                    echo '</optgroup>';
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inpt_mdf_Fournisseur_NumBanque">Numero compte bancaire </label>
                  <input type="text" class="form-control" id="inpt_mdf_Fournisseur_NumBanque" />
                </div>
                <div class="modal-footer">
                  <div class="col-sm-6 "> 
                    <button type="button" id="btn_modifier_Fournisseur" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Enregistrer</span> </button>
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

    <div id="supprimer_Fournisseur" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Suppression Fournisseur</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
              <form class="form"  >
                <div class="form-group">
                  <input type="hidden"  id="inpt_spm_Fournisseur_idFournisseur"  />
                </div>
                <div class="form-group">
                  <label for="span_spm_Fournisseur_Nom">Nom : </label>
                  <span id="span_spm_Fournisseur_Nom" ></span>
                </div>
                <div class="form-group">
                  <label for="span_spm_Fournisseur_Adresse">Adresse : </label>
                  <span id="span_spm_Fournisseur_Adresse" ></span>
                </div>
                <div class="form-group">
                  <label for="span_spm_Fournisseur_Telephone">Telephone : </label>
                  <span id="span_spm_Fournisseur_Telephone" ></span>
                </div>
                <div class="form-group">
                  <label for="span_spm_Fournisseur_Banque">Banque : </label>
                  <span id="span_spm_Fournisseur_Banque" ></span>
                </div>
                <div class="form-group">
                  <label for="span_spm_Fournisseur_NumBanque">Numero compte bancaire : </label>
                  <span id="span_spm_Fournisseur_NumBanque" ></span>
                </div>
                <div class="modal-footer">
                  <div class="col-sm-6 "> 
                    <button type="button" id="btn_supprimer_Fournisseur" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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

  <script type="text/javascript" src="scripts/fournisseur.js"></script>

<?php

?>
