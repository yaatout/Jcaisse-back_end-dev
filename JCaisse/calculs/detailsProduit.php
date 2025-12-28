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
	header('Location:../index.php');
}
require('connection.php');

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


/*************  LES BOUTONS IMPORTATION ET EXPORTATION EN CSV ET AJOUTER STOCK *******************/
/*************************************************************************************************/

require('entetehtml.php'); 
?>

<body >
  
   <?php require('header.php'); ?>
<div class="container" >


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

          window.location.href = "detailsProduit.php?iDS="+id+"&&debut="+debut+"&&fin="+fin;

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
 <a  class="btn btn-warning  pull-left" style="margin-top:8px;" href="insertionProduit.php" > Retour </a>
</div>

<div class="jumbotron noImpr">

  <div class="col-sm-4 pull-right" >

      <div aria-label="navigation">

          <ul class="pager">

              <li>

              <input type="text" id="reportrange" />

              </li>

          </ul>

      </div>   

  </div>

  <h2>Produit : <?php echo $design['designation']; ?> </h2>

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

          <div class="panel-heading" >

              <h4 class="panel-title">

              <a data-toggle="collapse" href="#collapse1"> Total Entees : <span id="quantiteEntrees"></span> <=> Total Sorties : <span id="quantiteSorties"></span> </a>

              </h4>

          </div>

          <div id="collapse1" class="panel-collapse collapse">

              <div class="panel-heading" style="margin-left:2%;">

                  <h4> 
                        Montant Vente : <span id="montantVente"></span> <br/>
                        Montant Bon : <span id="montantBon"></span> 
                  </h4>
                
              </div>

          </div>

      </div>

  </div>

  <form style="display:none" class="form-inline pull-left noImpr"  target="_blank" style="margin-left:20px;"

      method="post" action="pdfOperationLigne.php" >

      <input type="hidden" name="dateDebut" id="dateDebutOP"  <?php echo  "value=".$dateDebut."" ; ?> >

      <input type="hidden" name="dateFin" id="dateFinOP"  <?php echo  "value=".$dateFin."" ; ?> >

      <input type="hidden" name="id" id="idClientOP"  <?php echo  "value=".$id."" ; ?> >

      <button class="btn btn-primary  pull-left" style="margin-left:20px;" >

          <span class="glyphicon glyphicon-print"></span> Impression Relevé

      </button>

  </form>

</div>

  <ul class="nav nav-tabs">
    <li class="active" id="listeEntreesEvent"><a data-toggle="tab" href="#listeEntreesTab">LISTE DES ENTREES DE STOCK</a></li>
    <li id="listeSortiesEvent"><a data-toggle="tab" href="#listeSortiesTab">LISTE DES SORTIES DE STOCK</a></li>
<!--     <li id="listeInventairesEvent"><a data-toggle="tab" href="#listeInventairesTab">LISTE DES INVENTAIRES DE STOCK</a></li> -->
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
                <th>Unite Stock (U.S)</th>
                <th>Prix U.S</th>
                <th>Prix Unitaire</th>
                <th>Prix Reviens</th>
                <th>Expiration</th>
                <th>Personnel</th>
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
      <div class="tab-pane fade" id="listeInventairesTab">
        <br />
        <div class="table-responsive">
          <table id="listeInventaires" class="display tabStockInventaire" width="100%" border="1">
            <thead>
              <tr>
                <th>IdInventaire</th>
                <th>Date Inventaire</th>
                <th>Quantite Inventaire</th>
                <th>Quantite Stock</th>
                <th>Date Stockage</th>
                <th>Type Inventaire</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
  </div>

</div>

</body>

</html>

<script type="text/javascript" src="scripts/detailsProduit.js"></script>