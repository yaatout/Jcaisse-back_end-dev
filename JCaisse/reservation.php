<?php
session_start();
date_default_timezone_set('Africa/Dakar');
if(!$_SESSION['iduser']){
	header('Location:../index.php');
    }
    
require('connection.php');
require('declarationVariables.php');

/**********************/
/**Debut informations sur la date d'Aujourdhui **/
    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
    $date->setTimezone($timezone);
    $annee =$date->format('Y');
    $mois =$date->format('m');
    $jour =$date->format('d');
    $heureString=$date->format('H:i:s');
    $dateString=$annee.'-'.$mois.'-'.$jour;
    $dateString2=$jour.'-'.$mois.'-'.$annee;
/**Fin informations sur la date d'Aujourdhui **/

if(isset($_GET['debut']) && isset($_GET['fin'])){
    $dateDebut=@htmlspecialchars($_GET["debut"]);
    $dateFin=@htmlspecialchars($_GET["fin"]);
}
else {
    $dateDebut=date('Y-m-d', strtotime('-1 month'));
    $dateFin=date('Y-m-d', strtotime('+2 month'));
}
  

require('entetehtml.php');
?>
<!-- Debut Code HTML -->
<body>
    <?php require('header.php'); ?>
    <!-- Debut Container HTML -->
    <div class="container" >

    <input id="inpt_Reservation_dateDebut" type="hidden" value="<?= $dateDebut?>" />
    <input id="inpt_Reservation_dateFin" type="hidden" value="<?= $dateFin?>" />

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

                window.location.href = "reservation.php?debut="+debut+"&&fin="+fin;


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

    <div class="jumbotron noImpr">
        <div class="col-sm-2 pull-right" >
            <div aria-label="navigation">
                <ul class="pager">
                    <li>
                        <input type="text" id="reportrange" />
                    </li>
                </ul>
            </div> 
        </div>
        <h2>Liste des reservations  </h2>
        <h4>Du : 
            <?php
                    $debutDetails=explode("-", $dateDebut);
                    $dateDebutA=$debutDetails[2].'-'.$debutDetails[1].'-'.$debutDetails[0];
                    $finDetails=explode("-", $dateFin);
                    $dateFinA=$finDetails[2].'-'.$finDetails[1].'-'.$finDetails[0];
                    echo $dateDebutA." au ".$dateFinA; 
            ?>
        </h4>
    </div>

    <center>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ajouter_Reservation">
            <i class="glyphicon glyphicon-plus"></i> Ajouter Reservation
        </button>
    </center> 

        <div id="ajouter_Reservation" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajouter Reservation</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                        <form name="formulaire_Reservation">
                            <div class="form-group">
                                <label for="inpt_ajt_Reservation_Nom">Nom </label>
                                <input type="text" class="form-control" id="inpt_ajt_Reservation_Nom" />
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Reservation_Prenom">Prenom(s) </label>
                                <input type="text" class="form-control" id="inpt_ajt_Reservation_Prenom" />
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Reservation_Adresse">Adresse </label>
                                <input type="text" class="form-control" id="inpt_ajt_Reservation_Adresse" />
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Reservation_Telephone">Telephone </label>
                                <input type="text" class="form-control" id="inpt_ajt_Reservation_Telephone" />
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Reservation_Pays">Nationalite </label>
                                <input type="text" class="form-control" id="inpt_ajt_Reservation_Pays" />
                            </div>
                            <div class="form-group">
                                <label for="inpt_ajt_Reservation_Date">Date Reservation </label>
                                <input type="date" class="form-control" id="inpt_ajt_Reservation_Date" />
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            <button type="button" id="btn_ajouter_Reservation" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
            
        <div id="modifier_Reservation" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modifier Reservation</h4>
                </div>
                <form class="form" >
                <div class="modal-body" style="padding:40px 50px;">
                    <div class="form-group">					    
                        <input type="hidden" class="form-control" id="inpt_mdf_Reservation_idReservation"  >
                    </div>
                    <div class="form-group">
                        <label for="inpt_mdf_Reservation_Nom">Nom </label>
                        <input type="text" class="form-control" id="inpt_mdf_Reservation_Nom" />
                    </div>
                    <div class="form-group">
                        <label for="inpt_mdf_Reservation_Prenom">Prenom(s) </label>
                        <input type="text" class="form-control" id="inpt_mdf_Reservation_Prenom" />
                    </div>
                    <div class="form-group">
                        <label for="inpt_mdf_Reservation_Adresse">Adresse </label>
                        <input type="text" class="form-control" id="inpt_mdf_Reservation_Adresse" />
                    </div>
                    <div class="form-group">
                        <label for="inpt_mdf_Reservation_Telephone">Telephone </label>
                        <input type="text" class="form-control" id="inpt_mdf_Reservation_Telephone" />
                    </div>
                    <div class="form-group">
                        <label for="inpt_mdf_Reservation_Pays">Nationalite </label>
                        <input type="text" class="form-control" id="inpt_mdf_Reservation_Pays" />
                    </div>
                    <div class="form-group">
                        <label for="inpt_mdf_Reservation_Date">Date Reservation </label>
                        <input type="date" class="form-control" id="inpt_mdf_Reservation_Date" />
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-6 "> 
                    <button type="button" id="btn_modifier_Reservation" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Modifier</span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                    </div>
                </div>
                </form>
            </div>
            </div>
        </div>

        <div id="supprimer_Reservation" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Supprimer Reservation</h4>
                </div>
                <div class="modal-body">
                    <form >
                    <div class="modal-body">
                        <div class="form-group">					    
                        <input type="hidden" class="form-control" id="inpt_spm_Reservation_idReservation"  >
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="span_spm_Reservation_Prenom">Prenom : </label>
                                <span id="span_spm_Reservation_Prenom" ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="span_spm_Reservation_Nom">Nom : </label>
                                <span id="span_spm_Reservation_Nom" ></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="span_spm_Reservation_Adresse">Adresse : </label>
                                <span id="span_spm_Reservation_Adresse" ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="span_spm_Reservation_Telephone">Telephone : </label>
                                <span id="span_spm_Reservation_Telephone" ></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="span_spm_Reservation_Pays">Pays : </label>
                                <span id="span_spm_Reservation_Pays" ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="span_spm_Reservation_Date">Date Reservation : </label>
                                <span id="span_spm_Reservation_Date" ></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-6 "> 
                        <button type="button" id="btn_supprimer_Reservation" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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

        <div id="etat_Reservation" class="modal fade"  role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" >Changer l'etat de la Reservation</h4>
                    </div>
                    <form >
                    <div class="modal-body">
                        <div class="form-group">					    
                            <input type="hidden" class="form-control" id="inpt_etat_Reservation_idReservation"  >
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="span_etat_Reservation_Prenom">Prenom : </label>
                                <span id="span_etat_Reservation_Prenom" ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="span_etat_Reservation_Nom">Nom : </label>
                                <span id="span_etat_Reservation_Nom" ></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="span_etat_Reservation_Adresse">Adresse : </label>
                                <span id="span_etat_Reservation_Adresse" ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="span_etat_Reservation_Telephone">Telephone : </label>
                                <span id="span_etat_Reservation_Telephone" ></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="span_etat_Reservation_Pays">Pays : </label>
                                <span id="span_etat_Reservation_Pays" ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="span_etat_Reservation_Date">Date Reservation : </label>
                                <span id="span_etat_Reservation_Date" ></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-10 "> 
                        <button type="button" id="btn_Reservation_EnAttente" class="btn btn-primary pull-left"><span class="mot_Entregistrer">En attente</span> </button>
                        <button type="button" id="btn_Reservation_EnCours" class="btn btn-success pull-left"><span class="mot_Entregistrer">En cours</span> </button>
                        <button type="button" id="btn_Reservation_Terminer" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Terminer</span> </button>
                        </div>
                        <div class="col-sm-2 "> 
                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Debut de l'Accordion pour Tout les Paniers -->
        <div class="table-responsive">
            <table id="listeReservations" class="display dataTable" class="tableau3" width="100%" border="1">
                <thead>
                    <tr>
                        <th>IdReservation</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Pays</th>
                        <th>Date Reservation</th>
                        <th>Date Arrivee</th>
                        <th>Date Depart</th>
                        <th>Montant-à-Verser</th>
                        <th>Statut</th>
                        <th>Operations</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- Fin de l'Accordion pour Tout les Paniers -->

    </div>
    <!-- Fin Container HTML -->
</header>
</body>
<!-- Fin Code HTML -->

<script type="text/javascript" src="./scripts/reservation.js"></script>
