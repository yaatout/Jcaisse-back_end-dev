<?php
session_start();
date_default_timezone_set('Africa/Dakar');
if(!$_SESSION['iduser']){
	header('Location:../index.php');
    }
    
require('connection.php');
require('connectionPDO.php');
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
    $dateDebut=date('Y-m-d', strtotime('-1 years'));
    $dateFin=$dateString;
}
  


if ($_SESSION['compte']==1) {
    # code...
    /***Debut compte qui fait le paiement ***/
    $sqlGetComptePay="SELECT * FROM `".$nomtableCompte."` where idCompte <> 2 and idCompte <> 3 ORDER BY idCompte";
    $resPay = mysql_query($sqlGetComptePay) or die ("persoonel requête 2".mysql_error());
}

/**Debut Button upload Image Bl**/
if (isset($_POST['btnUploadImgBon'])) {
    $idBl=htmlspecialchars(trim($_POST['idBon']));
    if(isset($_FILES['file'])){
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif','pdf'];
        $maxSize = 400000;

        if(in_array($extension, $extensions) && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './PiecesJointes/'.$file);

            $sql2="UPDATE `".$nomtableBl."` set image='".$file."' where idBl='".$idBl."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Bl**/

require('entetehtml.php');

?>
<!-- Debut Code HTML -->
<body>
    <?php require('header.php'); ?>
    <!-- Debut Container HTML -->
    <div class="container" >

    <input id="inpt_Fournisseur_dateDebut" type="hidden" value="<?= $dateDebut?>" />
    <input id="inpt_Fournisseur_dateFin" type="hidden" value="<?= $dateFin?>" />

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

                window.location.href = "bonsLivraisons.php?debut="+debut+"&&fin="+fin;


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

        function showImageBon(idBon) {
            var nom=$('#imageBon'+idBon).attr("data-image");
            $('#idBon_View').text(nom);
            $('#idBon_Upd_Nv').val(idBon);
            $('#input_file_Bon').val('');
            $('#imageNvBon').modal('show');
            var file = $('#imageBon'+idBon).val();
            if(file!=null && file!=''){
                var format = file.substr(file.length - 3);
                if(format=='pdf'){
                    document.getElementById('output_pdf_Bon').style.display = "block";
                    document.getElementById('output_image_Bon').style.display = "none";
                    document.getElementById("output_pdf_Bon").src="./PiecesJointes/"+file;
                }
                else{
                    document.getElementById('output_image_Bon').style.display = "block";
                    document.getElementById('output_pdf_Bon').style.display = "none";
                    document.getElementById("output_image_Bon").src="./PiecesJointes/"+file;
                }
            }
            else{
                document.getElementById('output_pdf_Bon').style.display = "none";
                document.getElementById('output_image_Bon').style.display = "none";
            }
        }
        function showPreviewBon(event) {
            var file = document.getElementById('input_file_Bon').value;
            var reader = new FileReader();
            reader.onload = function()
            {
                var format = file.substr(file.length - 3);
                var pdf = document.getElementById('output_pdf_Bon');
                var image = document.getElementById('output_image_Bon');
                if(format=='pdf'){
                    document.getElementById('output_pdf_Bon').style.display = "block";
                    document.getElementById('output_image_Bon').style.display = "none";
                    pdf.src = reader.result;
                }
                else{
                    document.getElementById('output_image_Bon').style.display = "block";
                    document.getElementById('output_pdf_Bon').style.display = "none";
                    image.src = reader.result;
                }
            }
            reader.readAsDataURL(event.target.files[0]);
            document.getElementById('btn_upload_Bon').style.display = "block";
        }

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
        <h2>Liste des Bons de Livraison </h2>
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
            </div>
        </div>
        <div class="form-group pull-right noImpr" >
            <input type="text" size="45" class="form-control form-group" name="produit" placeholder="Rechercher ..."  id="inpt_Search_ListerBons" autocomplete="off" />
        </div> 
    </div>

        <!--*******************************Debut Bouttons****************************************-->
        <div class="table-responsive" >
            <center>
                    <button type="button" style="margin-right:50px" class="btn btn-success pull-left" onclick="ajouter_Livraison()">
                        <i class="glyphicon glyphicon-plus"></i> Ajouter Bon Livraison
                    </button>
            </center>
        </div> 
        <!--*******************************Debut Bouttons****************************************-->


        <div id="transferer_Bon" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Transferer Bon</h4>
                    </div>
                    <form class="form" id="form_transferer_Bon" style="padding:30px 30px;" >
                    <div class="modal-body">
                        <div class="form-group">					    
                            <input type="hidden" class="form-control" id="inpt_trsf_Bon_idBl"  >
                        </div>
                        <div class="form-group">
                            <label for="inpt_trsf_Bon_Numero">Numero </label>
                            <input type="text" disabled="true" class="form-control" id="inpt_trsf_Bon_Numero"  />
                        </div>
                        <div class="form-group">
                            <label for="inpt_trsf_Bon_Montant">Montant</label>
                            <input type="number" disabled="true"  class="form-control" id="inpt_trsf_Bon_Montant" />
                        </div>
                        <div class="form-group">
                            <label for="inpt_trsf_Bon_Date">Date</label>
                            <input type="text" disabled="true"  class="form-control" id="inpt_trsf_Bon_Date"  />
                        </div>
                        <div class="form-group">
                            <label for="slct_trsf_Bon_Fournisseur">Fournisseur </label>
                            <select class="form-control" onchange="choix_BL_Fournisseur(this.value)" id="slct_trsf_Bon_Fournisseur">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-6 "> 
                        <button type="button" id="btn_transferer_Bon" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Transferer</span> </button>
                        </div>
                        <div class="col-sm-6 "> 
                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="supprimer_Bon" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Supprimer Bon</h4>
                    </div>
                    <div class="modal-body">
                        <form >
                        <div class="modal-body">
                            <div class="form-group">					    
                            <input type="hidden" class="form-control" id="inpt_spm_Bon_idBon"  >
                            </div>
                            <div class="form-group">
                                <label for="span_spm_Bon_Numero">Numero : </label>
                                <span id="span_spm_Bon_Numero" ></span>
                            </div>
                            <div class="form-group">
                                <label for="span_spm_Bon_Date">Date : </label>
                                <span id="span_spm_Bon_Date" ></span>
                            </div>
                            <div class="form-group">
                                <label for="span_spm_Bon_Montant">Montant : </label>
                                <span id="span_spm_Bon_Montant" ></span>
                            </div>
                            <div class="form-group">
                                <label for="span_spm_Bon_Description">Description : </label>
                                <span id="span_spm_Bon_Description" ></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-6 "> 
                            <button type="button" id="btn_supprimer_Bon" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
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

        <!-- Debut Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->
            <style >
                /* Firefox */
                input[type=number] {
                    -moz-appearance: textfield;
                }
                /* Chrome */
                input::-webkit-inner-spin-button,
                input::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin:0;
                }
                /* Opéra*/
                input::-o-inner-spin-button,
                input::-o-outer-spin-button {
                    -o-appearance: none;
                    margin:0
                }
            </style>
        <!-- Fin Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->

        <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
            <script >
                $(function() {
                    $(".expand").on( "click", function() {
                        // $(this).next().slideToggle(200);
                        $expand = $(this).find(">:first-child");

                        if($expand.text() == "+") {
                        $expand.text("-");
                        } else {
                        $expand.text("+");
                        }
                    });
                });
            </script>
        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->


        <!-- Debut de l'Accordion pour Tout les Paniers -->
        <div class="table-responsive">
            <div id="listerBons"><!-- content will be loaded here --></div>
        </div>
        <!-- Fin de l'Accordion pour Tout les Paniers -->

        <div class="modal fade"  id="imageNvBon" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Bon : <b> <span id="idBon_View"></span></b></h4>
                    </div>
                    <form   method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:40px 50px;">
                            <input  type="text" style="display:none;" name="idBon" id="idBon_Upd_Nv" />
                            <div class="form-group" style="text-align:center;" >
                                <input type="file" name="file" accept="image/*" id="input_file_Bon" onchange="showPreviewBon(event);"/><br />
                                <img style="display:none;" width="500px" height="500px" id="output_image_Bon"/>
                                <iframe style="display:none;" id="output_pdf_Bon" width="100%" height="500px"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBon" id="btn_upload_Bon" >
                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <form class="form-inline noImpr"  id="factForm" method="post"  >
                        <div class="modal-body">
                            <p></p>
                            <input type="hidden" name="idBl" id="idBl" />
                            <input type="hidden" name="montantB" id="montantBl"/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="btnAnnulerBl" class="btn btn-success">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- Fin Container HTML -->
</header>
</body>
<!-- Fin Code HTML -->

<script type="text/javascript" src="./scripts/bonsLivraisons.js"></script>
