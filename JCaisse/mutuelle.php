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

  $sql12="SELECT SUM(apayerMutuelle) FROM `".$nomtableMutuellePagnet."` where verrouiller=1 AND type=0 ";
  $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
  $TotalB = mysql_fetch_array($res12) ; 

  $sql13="SELECT SUM(montant) FROM `".$nomtableVersement."` where idMutuelle!=0 ";
  $res13 = mysql_query($sql13) or die ("persoonel requête 2".mysql_error());
  $TotalV = mysql_fetch_array($res13) ;
  
  $SommeMontantAverser=$TotalB[0] - $TotalV[0];

?>

<div class="container">
  <ul class="nav nav-tabs">
    <li class="active">
          <a data-toggle="tab" href="#LISTEMUTUELLES">LISTE DES MUTUELLES 
            <?php
              if($_SESSION['proprietaire']==1){ 
                echo  ' => Valeur Montant à Verser =  '.number_format($SommeMontantAverser, 2, ',', ' ').' FCFA ';
              }
              ?>
          </a>
      </li>
  </ul>
  <div class="tab-content">
    <div id="LISTEMUTUELLES" class="tab-pane fade in active">
      <br />
      <center>
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajoutMutuelle" data-dismiss="modal" >
              <i class="glyphicon glyphicon-plus"></i>Ajout Mutuelle 
          </button>
      </center>
      <div class="table-responsive">

        <table id="tableMutuelle" class="display tabMutuelle" width="100%" border="1">
          <thead>
            <tr>
                <th>Ordre</th>
                <th>Nom</th>
                <th>Taux %</th>
                <th>Adresse</th>
                <th>Telephone</th>
                <th>Montant à Verser</th>
                <th>Operations</th>
            </tr>
          </thead>
        </table>

        <script type="text/javascript">
          $(document).ready(function() {
              $("#tableMutuelle").dataTable({
                "bProcessing": true,
                "sAjaxSource": "ajax/listerMutuelleAjax.php",
                "aoColumns": [
                      { mData: "0" } ,
                      { mData: "1" },
                      { mData: "2" },
                      { mData: "3" },
                      { mData: "4" },
                      { mData: "5" },
                      { mData: "6" }
                    ],
                    
              });  
          });
        </script>

        <div id="ajoutMutuelle" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Ajouter Mutuelle</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                  <form class="form" >
                    <div class="form-group">
                      <label for="ajtNomMT">Nom </label>
                      <input type="text" class="inputbasic form-control" id="ajtNomMT"  />
                    </div>
                    <div class="form-group">
                      <label for="ajtTauxMT">1er Taux % </label>
                      <input type="number" class="inputbasic form-control" id="ajtTauxMT"  />
                    </div>
                    <div class="form-group">
                      <label for="ajtTauxMT1">2ieme Taux % </label>
                      <input type="number" class="inputbasic form-control" id="ajtTauxMT1"  />
                    </div>
                    <div class="form-group">
                      <label for="ajtTauxMT2">3ieme Taux % </label>
                      <input type="number" class="inputbasic form-control" id="ajtTauxMT2"  />
                    </div>
                    <div class="form-group">
                      <label for="ajtTauxMT3">4ieme Taux % </label>
                      <input type="number" class="inputbasic form-control" id="ajtTauxMT3"  />
                    </div>
                    <div class="form-group">
                      <label for="ajtTauxMT4">5ieme Taux % </label>
                      <input type="number" class="inputbasic form-control" id="ajtTauxMT4"  />
                    </div>
                    <div class="form-group">
                      <label for="ajtAdresseMT">Adresse </label>
                      <input type="text" class="inputbasic form-control" id="ajtAdresseMT" />
                    </div>
                    <div class="form-group">
                      <label for="ajtTelephoneMT">Telephone </label>
                      <input type="text" class="inputbasic form-control" id="ajtTelephoneMT" />
                    </div>
                    <div class="modal-footer row">
                      <div class="col-sm-3 "> <input type="button" id="btn_ajt_Mutuelle" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                      <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>

        <div id="modifierMutuelle" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Modification Mutuelle</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                  <form class="form"  >
                    <div class="form-group">
                      <input type="hidden"  id="idMT_Mdf"  />
                      <input type="hidden"  id="ordreMT_Mdf"  />
                    </div>
                    <div class="form-group">
                      <label for="nomMT_Mdf">Nom </label>
                      <input type="text" class="inputbasic form-control" id="nomMT_Mdf"  />
                    </div>
                    <div class="form-group">
                      <label for="tauxMT_Mdf">1er Taux % </label>
                      <input type="text" class="inputbasic form-control" id="tauxMT_Mdf"  />
                    </div>
                    <div class="form-group">
                      <label for="tauxMT_Mdf1">2ieme Taux % </label>
                      <input type="text" class="inputbasic form-control" id="tauxMT_Mdf1"  />
                    </div>
                    <div class="form-group">
                      <label for="tauxMT_Mdf2">3ieme Taux % </label>
                      <input type="text" class="inputbasic form-control" id="tauxMT_Mdf2"  />
                    </div>
                    <div class="form-group">
                      <label for="tauxMT_Mdf3">4ieme Taux % </label>
                      <input type="text" class="inputbasic form-control" id="tauxMT_Mdf3"  />
                    </div>
                    <div class="form-group">
                      <label for="tauxMT_Mdf4">5ieme Taux % </label>
                      <input type="text" class="inputbasic form-control" id="tauxMT_Mdf4"  />
                    </div>
                    <div class="form-group">
                      <label for="adresseMT_Mdf">Adresse </label>
                      <input type="text" class="inputbasic form-control" id="adresseMT_Mdf" />
                    </div>
                    <div class="form-group">
                      <label for="telephoneMT_Mdf">Telephone </label>
                      <input type="text" class="inputbasic form-control" id="telephoneMT_Mdf" />
                    </div>
                    <div class="modal-footer row">
                      <div class="col-sm-3 "> <input type="button" id="btn_mdf_Mutuelle" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                      <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>

        <div id="supprimerMutuelle" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Suppression Mutuelle</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                  <form class="form"  >
                    <div class="form-group">
                      <input type="hidden"  id="idMT_Spm"  />
                      <input type="hidden"  id="ordreMT_Spm"  />
                    </div>
                    <div class="form-group">
                      <label for="nomMT_Spm">Nom </label>
                      <input type="text" disabled="true" class="inputbasic form-control" id="nomMT_Spm"  />
                    </div>
                    <div class="form-group">
                      <label for="tauxMT_Spm">Taux % </label>
                      <input type="text" disabled="true" class="inputbasic form-control" id="tauxMT_Spm"  />
                    </div>
                    <div class="form-group">
                      <label for="adresseMT_Spm">Adresse </label>
                      <input type="text" disabled="true" class="inputbasic form-control" id="adresseMT_Spm" />
                    </div>
                    <div class="form-group">
                      <label for="telephoneMT_Spm">Telephone </label>
                      <input type="text" disabled="true" class="inputbasic form-control" id="telephoneMT_Spm" />
                    </div>
                    <div class="modal-footer row">
                      <div class="col-sm-3 "> <input type="button" id="btn_spm_Mutuelle" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
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
