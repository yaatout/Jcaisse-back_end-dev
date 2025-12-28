<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/

session_start();

if(!$_SESSION['iduser']){
	header('Location:../JCaisse/index.php');
}
require('connection.php');
require('connectionPDO.php');
require('declarationVariables.php');

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
$datehier = date('d-m-Y', strtotime('-1 days'));
$datehier_Y = date('Y-m-d', strtotime('-1 days'));

$id=@$_GET['iDS'];
if(isset($_GET['debut']) && isset($_GET['fin'])){
  $dateDebut=@htmlspecialchars($_GET["debut"]);
  $dateFin=@htmlspecialchars($_GET["fin"]);
}
else {
  $dateDebut=date('Y-m-d', strtotime('-1 years'));
  $dateFin=$dateString;
}

$sql="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$id."' ";
$res=mysql_query($sql);
$design=mysql_fetch_array($res);

$stmtStock = $bdd->prepare("SELECT SUM(quantiteStockCourant) AS total FROM `".$nomtableStock."` WHERE idDesignation=:idDesignation ");
$stmtStock->bindValue(':idDesignation', (int)$id, PDO::PARAM_INT);
$stmtStock->execute();
$stock = $stmtStock->fetch();

/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
echo'
   <body >';
   require('header.php');
echo'<div class="container" >';
?>

<input id="inpt_Produit_id" type="hidden" value="<?= $id?>" />
<input id="inpt_Produit_dateDebut" type="hidden" value="<?= $dateDebut?>" />
<input id="inpt_Produit_dateFin" type="hidden" value="<?= $dateFin?>" />

<script type="text/javascript">

  $(function() {

          id = <?php echo json_encode($id); ?>;

          dateDebut = <?php echo json_encode($dateDebut); ?>;

          dateFin = <?php echo json_encode($dateFin); ?>;

          tabDebut=dateDebut.split('-');

          tabFin=dateFin.split('-');

      var start = tabDebut[2]+'/'+tabDebut[1]+'/'+tabDebut[0];

      var end = tabFin[2]+'/'+tabFin[1]+'/'+tabFin[0];

      function cb(start, end) {

          var debut=start.format('YYYY-MM-DD');

          var fin=end.format('YYYY-MM-DD');

          window.location.href = "stock-Pharmacie.php?iDS="+id+"&&debut="+debut+"&&fin="+fin;

          //alert(start);

      }

      $('#reportrange').daterangepicker({

          startDate: start,

          endDate: end,

          locale: {

              format: 'DD/MM/YYYY',

              separator: ' - ',

              applyLabel: 'Valider',

              cancelLabel: 'Annuler',

              fromLabel: 'De',

              toLabel: 'à',

              customRangeLabel: 'Choisir',

              daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve','Sa'],

              monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'November', 'Decembre'],

              firstDay: 1

          },

          ranges: {

              'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

              'Une Semaine': [moment().subtract(6, 'days'), moment()],

              'Un Mois': [moment().subtract(30, 'days'), moment()],

              'Ce mois ci': [moment().startOf('month'), moment()],

              'Dernier Mois': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

          },

      }, cb);

      cb(start, end);



  });

</script>

<div class="col-sm-4 pull-left" >
  <a  class="btn btn-warning  pull-left" style="margin-top:8px;" href="gestionStock.php" > Retour </a>
</div>

<div class="jumbotron">
  <div class="col-sm-4 pull-right" >
    <div aria-label="navigation">

        <ul class="pager">

            <li>

            <input type="text" id="reportrange" />

            </li>

        </ul>

    </div>   
  </div>
  <h2><?php echo $design['designation']; ?></h2>
  <h5>Du : 
    <?php

            $debutDetails=explode("-", $dateDebut);

            $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];

            $finDetails=explode("-", $dateFin);

            $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];

            echo $dateDebutA." au ".$dateFinA; 

    ?>
  </h5>
      <div class="panel-group">
          <div class="panel" style="background:#cecbcb;">
              <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse" id="btn_details">Restant Stock : <?php echo number_format($stock['total'], 0, ',', ' '); ?> </a>
                  </h4>
              </div>
              <div id="collapse" class="panel-collapse collapse">
                  <div class="panel-heading" style="margin-left:2%;">
                      <h4>Total des Entrees : <span id='spn_quantite_Entrees'></span></h4>
                      <h4>Total des Sorties : <span id='spn_quantite_Sorties'></span></h4>
                      <!-- <h4>Total des Inventaires : <span id='spn_quantite_Inventaires'></span></h4> -->
                  </div>
              </div>
          </div>
      </div>
<!--       <form class="form-inline pull-right noImpr"  style="margin-right:20px;"
                method="post">
                <button type="button" id="btnRafraichirTaux" class="btn btn-info pull-right" >
                  <span class="glyphicon glyphicon-pencil"></span> Corriger
              </button> 
              <div class="col-xs-4 pull-right">
                  <input  type="text" id="inputTauxAncien"  class="form-control" width="10%"  <?php echo "value='".$stock['total']."'"  ; ?> >
              </div>
      </form> -->
</div>

<!-- Modal -->
  <br/>
  <ul class="nav nav-tabs">
    <li class="active" id="listeEntreesEvent"><a data-toggle="tab" href="#listeEntreesTab">LISTE DES ENTREES DE STOCK</a></li>
    <li id="listeSortiesEvent"><a data-toggle="tab" href="#listeSortiesTab">LISTE DES SORTIES DE VENTE</a></li>
    <?php if($_SESSION['mutuelle']==1) { ?>
      <li id="listeImputationsEvent"><a data-toggle="tab" href="#listeImputationsTab">LISTE DES SORTIES DE MUTUELLE</a></li>
    <?php } ?>
    <li id="listeInventairesEvent"><a data-toggle="tab" href="#listeInventairesTab">LISTE DES INVENTAIRES DE STOCK</a></li>
  </ul>
  <div class="tab-content">
      <div class="tab-pane fade in active" id="listeEntreesTab">
        <br />
        <div class="table-responsive">
          <table id="listeEntrees" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
              <tr>
                <th>IdStock</th>
                <th>Date Stockage</th>
                <th>Initial</th>
                <th>Restant</th>
                <th>Forme</th>
                <th>Prix Public</th>
                <th>Prix Session</th>
                <th>Expiration</th>
                <th>Personnel</th>
                <th>Operations</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="listeSortiesTab">
        <br />
        <div class="table-responsive">
          <table id="listeSorties" class="display tabStockSorties" width="100%" border="1">
            <thead>
              <tr>
                <th>IdLigne</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Quantite</th>
                <th>Unite Vente</th>
                <th>Prix Unite Vente</th>
                <th>Prix Total</th>
                <th>Facture</th>
                <th>Client</th>
                <th>Personnel</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <?php if($_SESSION['mutuelle']==1) { ?>
        <div class="tab-pane fade" id="listeImputationsTab">
          <br />
          <div class="table-responsive">
            <table id="listeImputations" class="display tabStockSorties" width="100%" border="1">
              <thead>
                <tr>
                  <th>IdLigne</th>
                  <th>Date</th>
                  <th>Heure</th>
                  <th>Quantite</th>
                  <th>Unite Vente</th>
                  <th>Prix Unite Vente</th>
                  <th>Mutuelle [taux] </th>
                  <th>Facture</th>
                  <th>Adherant</th>
                  <th>Personnel</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      <?php } ?>
      <div class="tab-pane fade" id="listeInventairesTab">
        <br />
        <div class="table-responsive">
          <table id="listeInventaires" class="display tabStockInventaire" width="100%" border="1">
            <thead>
              <tr>
                <th>IdInventaire</th>
                <th>Date </th>
                <th>Heure</th>
                <th>Quantite Stock</th>
                <th>Quantite Inventaire</th>
                <th>Date Stockage</th>
                <th>Type Inventaire</th>
                <th>Personnel</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
  </div>
</div>

    <div id="modifier_Stock" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modifier Stock</h4>
            </div>
            <form class="form" id="form_ajouter_Produit" style="padding:30px 30px;">
              <div class="modal-body">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_mdf_Stock_idStock"  >
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Stock_DateEnregistrement">Date Enregistrement</label>
                    <input disabled="true" type="date" class="form-control" id="inpt_mdf_Stock_DateEnregistrement" />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Stock_Quantite">Quantite</label>
                    <input type="number" class="form-control" id="inpt_mdf_Stock_Quantite"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Stock_Forme">Forme</label>
                    <input type="text" class="form-control" id="inpt_mdf_Stock_Forme"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Stock_PrixPublic">Prix Public</label>
                    <input type="number" class="form-control" id="inpt_mdf_Stock_PrixPublic"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Stock_PrixSession">Prix Session</label>
                    <input type="number" class="form-control" id="inpt_mdf_Stock_PrixSession" />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Stock_DateExpiration">Date Expiration</label>
                    <input type="date" class="form-control" id="inpt_mdf_Stock_DateExpiration" />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_modifier_Stock" class="btn btn-success pull-left"><span class="mot_Entregistrer">Modifier</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="stock_Retirer" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Retirer Stock</h4>
            </div>
            <form class="form" id="form_ajouter_Produit" style="padding:30px 30px;">
              <div class="modal-body">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_rtr_Stock_idStock"  >
                </div>
                <div class="form-group">
                    <label for="inpt_rtr_Stock_DateEnregistrement">Date Enregistrement</label>
                    <input disabled="true" type="date" class="form-control" id="inpt_rtr_Stock_DateEnregistrement" />
                </div>
                <div class="form-group">
                    <label for="inpt_rtr_Stock_Quantite">Quantite</label>
                    <input type="number" class="form-control" id="inpt_rtr_Stock_Quantite"  />
                </div>
                <div class="form-group">
                    <label for="inpt_rtr_Stock_DateExpiration">Date Expiration</label>
                    <input disabled="true" type="date" class="form-control" id="inpt_rtr_Stock_DateExpiration" />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_retirer_Stock" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Retirer</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="stock_Retourner" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Retourner Stock</h4>
            </div>
            <form class="form" id="form_ajouter_Produit" style="padding:30px 30px;">
              <div class="modal-body">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_rtn_Stock_idStock"  >
                </div>
                <div class="form-group">
                    <label for="inpt_rtn_Stock_DateEnregistrement">Date Enregistrement</label>
                    <input disabled="true" type="date" class="form-control" id="inpt_rtn_Stock_DateEnregistrement" />
                </div>
                <div class="form-group">
                    <label for="inpt_rtn_Stock_Quantite">Quantite</label>
                    <input type="number" class="form-control" id="inpt_rtn_Stock_Quantite"  />
                </div>
                <div class="form-group">
                    <label for="inpt_rtn_Stock_DateExpiration">Date Expiration</label>
                    <input disabled="true" type="date" class="form-control" id="inpt_rtn_Stock_DateExpiration" />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_retourner_Stock" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Retourner</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="supprimer_Stock" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Supprimer Stock</h4>
            </div>
            <div class="modal-body">
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_spm_Stock_idStock"  >
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_DateEnregistrement">Date Enregistrement : </label>
                        <span id="span_spm_Stock_DateEnregistrement" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_Quantite">Quantite : </label>
                        <span id="span_spm_Stock_Quantite" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_Forme">Unite Stock (US) : </label>
                        <span id="span_spm_Stock_Forme" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_PrixPublic">Prix Public : </label>
                        <span id="span_spm_Stock_PrixPublic" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_PrixSession">Prix Session : </label>
                        <span id="span_spm_Stock_PrixSession" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Stock_DateExpiration">Date Expiration : </label>
                        <span id="span_spm_Stock_DateExpiration" ></span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_supprimer_Stock" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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

    <div id="imprimerStockModal" class="modal fade " role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Imprimer Stock</h4>
              </div>
              <div class="modal-body" style="padding:40px 50px;">
                  <form role="form" id="etiquette" method="post" action="etiquette.php" target="_blank" >
                      <input type="hidden"  name="ordre" id="ordre_Imp"  />
                      <input type="hidden"  name="idStock" id="idStock_Imp"  />
                      <input type="hidden"  name="designation" id="designation_Imp"  />
                      <input type="hidden"  name="idDesignation" id="idDesignation_Imp"  />
                      <input type="hidden"  name="uniteStock" id="idStock_Imp"  />
                      <input type="hidden" name="prixUnitaire" id="prixUnitaire_Imp"  />
                      <div class="form-group" >
                        <label for="dateStockage"> Date Enregistrement <font color="red">*</font></label>
                        <input type="date" class="form-control" name="dateStockage" id="dateStockage_Imp" disabled="true"  />
                      </div>
                      <div class="form-group" >
                        <label for="qteReste"> Quantite a Imprimer<font color="red">*</font></label>
                        <input type="text" class="form-control" name="quantite" id="qte_Imp"  />
                      </div>
                      <div align="right">
                        <br />
                        <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
                        <input onclick="document.getElementById('etiquette').submit();"  class="boutonbasic"  name="btnModifier" value="IMPRIMER  >>"/>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </div> 

    <div id="imprimerCodeBarreStockModal" class="modal fade " role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Imprimer Code Barre</h4>
              </div>
              <div class="modal-body" style="padding:40px 50px;">
                  <form role="form" id="barcode" method="post" action="barcode.php" target="_blank" >
                      <input type="hidden" name="operation" value="2"  />
                      <input type="hidden" name="ordre" id="ordreCB_Imp"  />
                      <input type="hidden" name="idStock" id="idStockCB_Imp"  />
                      <input type="hidden" name="designation" id="designationCB_Imp"  />
                      <input type="hidden" name="prixUnitaire" id="prixUnitaireCB_Imp"  />
                      <input type="hidden" name="codeBarreuniteStock" id="codeBarreuniteStockCB_Imp"  />
                      <div class="form-group" >
                        <label for="dateStockage"> Date Enregistrement <font color="red">*</font></label>
                        <input type="date" class="form-control" name="dateStockage" id="dateStockageCB_Imp" disabled="true"  />
                      </div>
                      <div class="form-group" >
                        <label for="qteReste"> Quantite a Imprimer<font color="red">*</font></label>
                        <input type="text" class="form-control" name="quantite" id="qteCB_Imp"  />
                      </div>
                      <div align="right">
                        <br />
                        <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
                        <input onclick="document.getElementById('barcode').submit();"  class="boutonbasic"  name="btnModifier" value="IMPRIMER  >>"/>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </div>


<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
<div id="msg_info_js" class="modal fade " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alerte</h4>
            </div>
            <div class="modal-body">
                <p>PROBLEME CONNECTION INTERNET .</br>
                </br> L'operation sur ce stock a échoué. Veuillez reessayer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
<div id="msg_info_jsET" class="modal fade " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alerte</h4>
            </div>
            <div class="modal-body">
                <p>IMPOSSIBLE .</br>
                </br> La quantite restante de ce Stock est insuffisante.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->

<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
<div id="msg_info_jsDP" class="modal fade " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header panel-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alerte</h4>
            </div>
            <div class="modal-body">
                <p>IMPOSSIBLE .</br>
                </br> Ce transfert ne peut pas avoir lieu.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->



<?php
echo'</body></html>';
?>

<script type="text/javascript" src="scripts/stock_PH.js"></script>