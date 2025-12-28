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

  
  /****** AVOIR ******/
  
  if ($_SESSION['depotAvoir']==1) {
    # code...
    $T_avoir=0;

    $sqlA="SELECT SUM(montantAvoir) as totalAvoir from `".$nomtableClient."` where avoir=1 and archiver=0";
    $resA=mysql_query($sqlA);
    $clientA=mysql_fetch_array($resA);

    $T_avoir=$clientA['totalAvoir'];
  }  
?>

<div class="container">
    <center>
      <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ajoutClient" data-dismiss="modal" >
          <i class="glyphicon glyphicon-plus"></i>Ajout Client
      </button>
    </center> 

    <div id="ajoutClient" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Ajouter Client</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
            <div class="row">
              <form class="form" >
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>">
                  <label for="ajtPrenomCL">Prenom </label>
                  <input type="text" class="inputbasic form-control" id="ajtPrenomCL" autofocus/>
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>">
                  <label for="ajtNomCL">Nom </label>
                  <input type="text" class="inputbasic form-control" id="ajtNomCL"  />
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>">
                  <label for="ajtAdresseCL">Adresse </label>
                  <input type="text" class="inputbasic form-control" id="ajtAdresseCL" />
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>">
                  <label for="ajtTelephoneCL">Telephone </label>
                  <input type="text" class="inputbasic form-control" id="ajtTelephoneCL" />
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-12' : '' ; ?>" >
                  <label for="plafondSelect">Plafond </label>
                  <select onchange="selectPlafond(this.value)" name="plafondSelect" id="plafondSelect" class="inputbasic form-control plafondSelect" style="width:20%">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                  </select>
                </div>
                <div class="form-group plafondInput <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>" style="display:none">
                  <label for="ajtPlafondCL">Montant Plafond </label>
                  <input type="text" class="inputbasic form-control plafondAmount" id="ajtPlafondCL" />
                </div>
                <?php if ($_SESSION['depotAvoir']==1) { ?>
                <div class="form-check col-md-6">
                  <input class="form-check-input" type="checkbox" id="check1" name="option1" value="0">
                  <label class="form-check-label" for="check1"> Dépôt avoir</label>
                </div>
                <div class="form-group col-md-6" id="divMontantAvoir" style="display:none;">
                  <label for="montantAvoir">Montant avoir </label>
                  <input type="number" class="inputbasic form-control" id="montantAvoir" value="0" placeholder="Saisir le montant avoir"/>
                </div>
                <div class="form-group col-md-6" id="divMatP" style="display:none;">
                  <label for="ajtMatPCL">Matricule Pension </label>
                  <input type="text" class="inputbasic form-control" id="ajtMatPCL" value="0000"/>
                </div>
                <div class="form-group col-md-6" id="divNumCarnet" style="display:none;">
                  <label for="ajtNumCarnetCL">N° Carnet </label>
                  <input type="text" class="inputbasic form-control numCarnet" id="ajtNumCarnetCL" placeholder="N° Carnet" />
                </div>
                <div class="form-group col-md-12" id="numCarnetExt" style="display:none;" align="center">
                  <code>Ce numéro de carnet existe déjà!</code>
                </div>
                <?php } ?>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-12' : '' ; ?>" id="personnelDiv">
                  <label for="personnel">Fait partie du personnel </label>
                  <select name="personnel" id="personnel" class="inputbasic form-control" style="width:20%">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                  </select>
                </div>
                </div>
                <div class="modal-footer row">
                  <div class="col-sm-3 "> <input type="button" id="btn_ajt_Client" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                  <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>

    <div id="modifierClient" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modification Client</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
            <div class="row">
              <form class="form" >
                <div class="form-group hidden">
                  <input type="hidden"  id="idCL_Mdf"  />
                  <input type="hidden"  id="ordreCL_Mdf"  />
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>">
                  <label for="prenomCL_Mdf">Prenom </label>
                  <input type="text" class="inputbasic form-control" id="prenomCL_Mdf"  />
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>">
                  <label for="nomCL_Mdf">Nom </label>
                  <input type="text" class="inputbasic form-control" id="nomCL_Mdf"  />
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>">
                  <label for="adresseCL_Mdf">Adresse </label>
                  <input type="text" class="inputbasic form-control" id="adresseCL_Mdf" />
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>">
                  <label for="telephoneCL_Mdf">Telephone </label>
                  <input type="text" class="inputbasic form-control" id="telephoneCL_Mdf" />
                </div>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-12' : '' ; ?>" >
                  <label for="plafondSelect_Mdf">Plafond </label>
                  <select onchange="selectPlafond(this.value)" name="plafondSelect_Mdf" id="plafondSelect_Mdf" class="inputbasic form-control plafondSelect" style="width:20%">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                  </select>
                </div>
                <div class="form-group plafondInput <?= ($_SESSION['depotAvoir']==1) ? 'col-md-6' : '' ; ?>" style="display:none">
                  <label for="plafondCL_Mdf">Montant Plafond </label>
                  <input type="text" class="inputbasic form-control plafondAmount" id="plafondCL_Mdf" />
                </div>
                <?php if ($_SESSION['depotAvoir']==1) { ?>
                <div class="form-check col-md-6">
                  <input class="form-check-input" type="checkbox" id="check1_Mdf" name="option1" value="0">
                  <label class="form-check-label" for="check1_Mdf"> Dépôt avoir</label>
                </div>
                <div class="form-group col-md-6" id="divMontantAvoir_Mdf" style="display:none;">
                  <label for="montantAvoir">Montant avoir </label>
                  <input type="number" class="inputbasic form-control" id="montantAvoir_Mdf"/>
                </div>
                <div class="form-group col-md-6" id="divMatP_Mdf" style="display:none;">
                  <label for="ajtMatPCL">Matricule Pension </label>
                  <input type="text" class="inputbasic form-control" id="ajtMatPCL_Mdf" />
                </div>
                <div class="form-group col-md-6" id="divNumCarnet_Mdf" style="display:none;">
                  <label for="ajtNumCarnetCL">N° Carnet </label>
                  <input type="text" class="inputbasic form-control numCarnet" id="ajtNumCarnetCL_Mdf" />
                  <input type="hidden" class="inputbasic form-control numCarnet" id="ajtNumCarnetCL_old" />
                </div>
                <div class="form-group col-md-12" id="numCarnetExt_Mdf" style="display:none;" align="center">
                  <code>Ce numéro de carnet existe déjà!</code>
                </div>
                <?php } ?>
                <div class="form-group <?= ($_SESSION['depotAvoir']==1) ? 'col-md-12' : '' ; ?>" id="personnelDiv_Mdf">
                  <label for="personnel_Mdf">Fait partie du personnel </label>
                  <select name="personnel_Mdf" id="personnel_Mdf" class="inputbasic form-control" style="width:20%">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                  </select>
                </div>
                </div>
                <div class="modal-footer">
                  <div class="col-sm-3 "> <input type="button" id="btn_mdf_Client" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                  <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>

    <div id="supprimerClient" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Suppression Client</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
              <form class="form"  >
                <div class="form-group">
                  <input type="hidden"  id="idCL_Spm"  />
                  <input type="hidden"  id="ordreCL_Spm"  />
                </div>
                <div class="form-group">
                  <label for="prenomCL_Mdf">Prenom </label>
                  <input type="text" disabled="true" class="inputbasic form-control" id="prenomCL_Spm"  />
                </div>
                <div class="form-group">
                  <label for="nomCL_Mdf">Nom </label>
                  <input type="text" disabled="true" class="inputbasic form-control" id="nomCL_Spm"  />
                </div>
                <div class="form-group">
                  <label for="adresseCL_Mdf">Adresse </label>
                  <input type="text" disabled="true" class="inputbasic form-control" id="adresseCL_Spm" />
                </div>
                <div class="form-group">
                  <label for="telephoneCL_Mdf">Telephone </label>
                  <input type="text" disabled="true" class="inputbasic form-control" id="telephoneCL_Spm" />
                </div>
                <div class="modal-footer row">
                  <div class="col-sm-3 "> <input type="button" id="btn_spm_Client" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                  <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>

    <br>
  <ul class="nav nav-tabs">
    <li class="active" id="listeClientsEvent">
        <a data-toggle="tab" href="#LISTECLIENTS">LISTE DES CLIENTS</a>
    </li>
    <li class="" id="listeDepotsEvent">
        <a data-toggle="tab" href="#listeDepotsTab">LISTE DES DEPOTS</a>
    </li>
    <li class="" id="listeDettesEvent">
        <a data-toggle="tab" href="#listeDettesTab">LISTE DES DETTES</a>
    </li>
    <li class="" id="listePersonnelsEvent">
        <a data-toggle="tab" href="#listePersonnelsTab">LISTE DES PERSONNELS</a>
    </li>
    <li class="" id="listeArchivesEvent">
        <a data-toggle="tab" href="#listeArchivesTab">LISTE DES ARCHIVES</a>
    </li>    
    <?php
      if ($_SESSION['depotAvoir']==1) {
        # code...
    ?>
      <li class="" id="listeAvoirsEvent">
          <a data-toggle="tab" href="#listeAvoirsTab">LISTE DES AVOIRS</a>
      </li>
    <?php
      }
    ?>   
    <?php
      if ($_SESSION['vitrine']==1) {
        # code...
    ?>
      <li class="" id="listeVitrineEvent">
          <a data-toggle="tab" href="#listeVitrineTab">LISTE DES EN LIGNE</a>
      </li>
    <?php
      }
    ?>
  </ul>
  <div class="tab-content">
    <div id="LISTECLIENTS" class="tab-pane fade in active">
      <br />
      <div class="table-responsive">

        <div class="container row">
          <a class="col-lg-6 alert alert-info" href="#">LISTE DES CLIENTS
            <?php 
              if ($_SESSION['proprietaire']==1) { 
                echo  ' => Valeur Montant des clients =  <span id="montantClients"></span>';
              }
            ?>
          </a>
        </div>

        <table id="listeClients" class="display dataTable" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Adresse</th>
                    <th>Telephone</th>
                    <th>Plafond</th>
                    <th>Montant-à-Verser</th>
                    <th>Operations</th>
                </tr>
            </thead>
        </table>

      </div>
    </div>
    <div id="listeDepotsTab" class="tab-pane fade">
      <br />
      <div class="table-responsive">

        <div class="container row">
          <a class="col-lg-6 alert alert-info" href="#">LISTE DES DEPOTS
            <?php 
              if ($_SESSION['proprietaire']==1) { 
                echo  ' => Valeur Montant des depots =  <span id="montantDepots">0</span>';
              }
            ?>
          </a>
        </div>

        <table id="listeDepots" class="display tabStock" class="tableau3" width="100%" border="1" >
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Adresse</th>
                    <th>Telephone</th>
                    <th>Montant-à-Verser</th>
                    <th>Operations</th>
                </tr>
            </thead>
        </table>

      </div>
    </div>
    <div id="listeDettesTab" class="tab-pane fade">
      <br />
      <div class="table-responsive">

        <div class="container row">
          <a class="col-lg-6 alert alert-info" href="#">LISTE DES DETTES
            <?php 
              if ($_SESSION['proprietaire']==1) { 
                echo  ' => Valeur Montant des dettes = <span id="montantDettes">0</span>';
              }
            ?>
          </a>
        </div>

        <table id="listeDettes" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Adresse</th>
                    <th>Telephone</th>
                    <th>Montant-à-Verser</th>
                    <th>Operations</th>
                </tr>
            </thead>
        </table>
      </div>
    </div>
    <div id="listePersonnelsTab" class="tab-pane fade">
      <br />
      <div class="table-responsive">

        <div class="container row">
          <a class="col-lg-6 alert alert-info" href="#">LISTE DU PERSONNEL
            <?php 
              if ($_SESSION['proprietaire']==1) { 
                echo  ' => Valeur Montant du personnel = <span id="montantPersonnels">0</span>';
              }
            ?>
          </a>
        </div>

        <table id="listePersonnels" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Adresse</th>
                    <th>Telephone</th>
                    <th>Montant-à-Verser</th>
                    <th>Operations</th>
                </tr>
            </thead>
        </table>

      </div>
    </div>
    <div id="listeArchivesTab" class="tab-pane fade">
      <br />
      <div class="table-responsive">

        <div class="container row">
          <a class="col-lg-6 alert alert-info" href="#">LISTE DES ARCHIVES
            <?php 
              if ($_SESSION['proprietaire']==1) { 
                echo  ' => Valeur Montant des Archives = <span id="montantArchives">0</span>';
              }
            ?>
          </a>
        </div>

        <table id="listeArchives" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Adresse</th>
                    <th>Telephone</th>
                    <th>Montant-à-Verser</th>
                    <th>Operations</th>
                </tr>
            </thead>
        </table>

      </div>
    </div>    
    <?php
      if ($_SESSION['depotAvoir']==1) {
        # code...
    ?>
    <div id="listeAvoirsTab" class="tab-pane fade">
      <br />
      <div class="table-responsive">

        <div class="container row">
          <a class="col-lg-6 alert alert-info" href="#">LISTE DES AVOIRS
            <?php 
              if ($_SESSION['proprietaire']==1) { 
                echo  ' => Valeur Montant des avoirs = <span id="montantAvoirs">0</span>';
              }
            ?>
          </a>
        </div>

        <table id="listeAvoirs" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Adresse</th>
                    <th>Telephone</th>
                    <th>Montant-à-Verser</th>
                    <th>Operations</th>
                </tr>
            </thead>
        </table>

      </div>
    </div>
    <?php
      }
    ?>    
    <?php
      if ($_SESSION['vitrine']==1) {
        # code...
    ?>
    <div id="listeVitrineTab" class="tab-pane fade">
      <br />
      <div class="table-responsive">
          <label class="pull-left" for="nbEntreeEnLigne">Nombre entrées </label>
          <select class="pull-left" id="nbEntreeEnLigne">
            <optgroup>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option> 
            </optgroup>       
          </select>
          <input class="pull-right" type="text" name="" id="searchInputEnLigne" placeholder="Rechercher..." autocomplete="off">
          <div id="listeDesClientsEnLigne"><!-- content will be loaded here --></div>
      </div>
    </div>
    <?php
      }
    ?>
  </div>
</div>

<script type="text/javascript" src="scripts/bon.js"></script>
