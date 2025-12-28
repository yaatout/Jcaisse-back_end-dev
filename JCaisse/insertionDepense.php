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
	header('Location:../JCaisse/index.php');
}
require('connection.php');

require('connectionPDO.php');

require('declarationVariables.php');

if(isset($_GET['debut']) && isset($_GET['fin'])){
  $dateDebut=@htmlspecialchars($_GET["debut"]);
  $dateFin=@htmlspecialchars($_GET["fin"]);
}
else {
  $dateDebut=date('Y-m-d', strtotime('-1 months'));
  $dateFin=$dateString;
}


?>

<?php require('entetehtml.php'); ?>

<body>
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

 
?>

<input id="inpt_Depense_dateDebut" type="hidden" value="<?= $dateDebut?>" />
<input id="inpt_Depense_dateFin" type="hidden" value="<?= $dateFin?>" />

<script type="text/javascript">

  $(function() {

          dateDebut = <?php echo json_encode($dateDebut); ?>;

          dateFin = <?php echo json_encode($dateFin); ?>;

          tabDebut=dateDebut.split('-');

          tabFin=dateFin.split('-');

      var start = tabDebut[2]+'/'+tabDebut[1]+'/'+tabDebut[0];

      var end = tabFin[2]+'/'+tabFin[1]+'/'+tabFin[0];

      function cb(start, end) {

          var debut=start.format('YYYY-MM-DD');

          var fin=end.format('YYYY-MM-DD');

          $('#listeMouvements').DataTable({
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'datatables/insertionDepense_listeMouvements.php',
                'data':{
                    'dateDebut' : debut,
                    'dateFin' : fin
                }
            },
            'dom': 'Blfrtip',
            "buttons": ['csv','print', 'excel', 'pdf'],
            "ordering": true,
            "order": [[0, 'desc']],
            'columns': [
              { data: 'idDesignation' }, 
              { data: 'designation' },
              { data: 'quantite' },
              { data: 'montant' },
              { data: 'operations' },
            ],
            "fnCreatedRow": function( nRow, data, iDataIndex ) {
              $(nRow).attr('class', "depense"+data['idDesignation']);
           },
           'columnDefs': [ 
            { "bVisible": false, "aTargets": [ 0 ] },
            {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
            }
          ]
        });

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

<div class="container">

  <center>
    <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#ajouter_Depense" data-dismiss="modal" >
        <i class="glyphicon glyphicon-plus"></i>Ajout Depense
    </button>
  </center>

    <div id="ajouter_Depense" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Ajouter Depense</h4>
            </div>
            <form class="form" >
              <div class="modal-body" style="padding:40px 50px;">
                <div class="form-group">
                    <label for="inpt_ajt_Depense_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Depense_Reference"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Depense_Unite">Unite Depense (UD)</label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Depense_Unite"  />
                </div>
                <div class="form-group">
                    <label for="inpt_ajt_Depense_Prix">Prix  Unite Depense</label>
                    <input type="text" class="inputbasic form-control" id="inpt_ajt_Depense_Prix"  />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_ajouter_Depense" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Enregistrer</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="modifier_Depense" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modifier Depense</h4>
            </div>
            <form class="form" >
              <div class="modal-body" style="padding:40px 50px;">
                <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_mdf_Depense_idDepense"  >
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Depense_Reference">Reference </label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Depense_Reference"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Depense_Unite">Unite Depense (UD)</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Depense_Unite"  />
                </div>
                <div class="form-group">
                    <label for="inpt_mdf_Depense_Prix">Prix  Unite Depense</label>
                    <input type="text" class="inputbasic form-control" id="inpt_mdf_Depense_Prix"  />
                </div>
              </div>
              <div class="modal-footer">
                <div class="col-sm-6 "> 
                  <button type="button" id="btn_modifier_Depense" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Enregistrer</span> </button>
                </div>
                <div class="col-sm-6 "> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div id="supprimer_Depense" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Supprimer Depense</h4>
            </div>
            <div class="modal-body">
                <form >
                  <div class="modal-body">
                    <div class="form-group">					    
                      <input type="hidden" class="form-control" id="inpt_spm_Depense_idDepense"  >
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Depense_Reference">Reference : </label>
                        <span id="span_spm_Depense_Reference" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Depense_Unite">Unite Depense (UD) : </label>
                        <span id="span_spm_Depense_Unite" ></span>
                    </div>
                    <div class="form-group">
                        <label for="span_spm_Depense_Prix">Prix  Unite Depense : </label>
                        <span id="span_spm_Depense_Prix" ></span>
                    </div>
                    <div class="row" id="spm_impossible" style="display:none"> 
                      <div class="form-group col-md-12">
                        <h5 style="color : red"> </h5>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_supprimer_Depense" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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


  <ul class="nav nav-tabs">
    <li class="active" id="listeDepensesEvent">
        <a data-toggle="tab" href="#listeDepensesTab">LISTE DES DEPENSES</a>
    </li>
    <li class="" id="listeMouvementsEvent">
        <a data-toggle="tab" href="#listeMouvementsTab">LISTE DES MOUVEMENTS</a>
    </li>   
  </ul>
  <div class="tab-content">
    <div id="listeDepensesTab" class="tab-pane fade in active">
      <br />
      <div class="table-responsive">
          <table id="listeDepenses" class="display tabStock" class="tableau3" width="100%" border="1">
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
    <div id="listeMouvementsTab" class="tab-pane fade">
      <br />
      <div class="row">
        <div class="col-sm-4 pull-right" >
          <div aria-label="navigation">

              <ul class="pager">

                  <li>

                  <input type="text" id="reportrange" />

                  </li>

              </ul>

          </div>   
        </div>
      </div>
      <br />
      <div class="table-responsive">
          <table id="listeMouvements" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>IdDesignation</th>
                    <th>Reference</th>
                    <th>Quantite</th>
                    <th>Montant</th>
                    <th>Operations</th>
                </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>

</div>



<script type="text/javascript" src="scripts/insertionDepense.js"></script>
