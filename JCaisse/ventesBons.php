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


$id=@$_GET['c'];
if(isset($_GET['debut']) && isset($_GET['fin'])){
  $dateDebut=@htmlspecialchars($_GET["debut"]);
  $dateFin=@htmlspecialchars($_GET["fin"]);
}
else {
  $dateDebut=date('Y-m-d', strtotime('-1 years'));
  $dateFin=$dateString;
}

$stmtClient = $bdd->prepare("SELECT  * FROM `".$nomtableClient."` 
WHERE idClient = :idClient ");
$stmtClient->execute(array(
    ':idClient' => $id
));
$client = $stmtClient->fetch(PDO::FETCH_ASSOC);

?>

<?php require('entetehtml.php'); ?>

<body>

<input id="inpt_Client_id" type="hidden" value="<?= $id?>" />
<input id="inpt_Client_dateDebut" type="hidden" value="<?= $dateDebut?>" />
<input id="inpt_Client_dateFin" type="hidden" value="<?= $dateFin?>" />

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

            window.location.href = "bonPclient.php?c="+id+"&&debut="+debut+"&&fin="+fin;


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

    function showImageVersement(idVersement) {
        var nom=$('#imageVersement'+idVersement).attr("data-image");
        $('#idVersement_View').text(nom);
        $('#inpt_upload_Versement').val(idVersement);
        $('#input_file_Versement').val('');
        $('#image_Versement').modal('show');
        var file = $('#imageVersement'+idVersement).val();
        if(file!=null && file!=''){
            var format = file.substr(file.length - 3);
            if(format=='pdf'){
                document.getElementById('output_pdf_Versement').style.display = "block";
                document.getElementById('output_image_Versement').style.display = "none";
                document.getElementById("output_pdf_Versement").src="./PiecesJointes/"+file;
            }
            else{
                document.getElementById('output_image_Versement').style.display = "block";
                document.getElementById('output_pdf_Versement').style.display = "none";
                document.getElementById("output_image_Versement").src="./PiecesJointes/"+file;
            }
        }
        else{
            document.getElementById('output_pdf_Versement').style.display = "none";
            document.getElementById('output_image_Versement').style.display = "none";
        }
    }
    function showPreviewVersement(event) {
        var file = document.getElementById('input_file_Versement').value;
        var reader = new FileReader();
        reader.onload = function()
        {
            var format = file.substr(file.length - 3);
            var pdf = document.getElementById('output_pdf_Versement');
            var image = document.getElementById('output_image_Versement');
            if(format=='pdf'){
                document.getElementById('output_pdf_Versement').style.display = "block";
                document.getElementById('output_image_Versement').style.display = "none";
                pdf.src = reader.result;
            }
            else{
                document.getElementById('output_image_Versement').style.display = "block";
                document.getElementById('output_pdf_Versement').style.display = "none";
                image.src = reader.result;
            }
        }
        reader.readAsDataURL(event.target.files[0]);
        document.getElementById('btn_upload_Versement').style.display = "block";
    }

</script>

<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');
  
  if (($_SESSION['proprietaire']==0 && $_SESSION['gerant']==0 && $_SESSION['gestionnaire']==0) && ($_SESSION['vendeur']==1 || $_SESSION['caissier']==1)) {
    echo '<input  style="display:none" type="number" id="inpt_Type_Vente_Local" value="1" />';
  }
  else{
    echo '<input  style="display:none" type="number" id="inpt_Type_Vente_Local" value="0" />';
  }
  
 
?>

<div class="container">

<div class="col-sm-4 pull-left" >
     <a  class="btn btn-warning  pull-left" style="margin-top:8px;" href="bon.php" > Retour </a>
    </div>

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
        <h2>Les Operations du Client : <?php echo strtoupper($client['nom']).' '.$client['prenom']; ?> </h2>
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
                    <a data-toggle="collapse" href="#collapse1">Montant à verser : <span id='spn_montant_Total'></span> </a>
                    </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse">
                    <div class="panel-heading" style="margin-left:2%;">
                        <h4>Total des Bons : <span id='spn_montant_Bons'></span></h4>
                        <h4>Total des Versements : <span id='spn_montant_Versements'></span></h4>
                    </div>
                </div>
            </div>
        </div>
        <form class="form-inline pull-left noImpr"  target="_blank" style="margin-left:20px;"
            method="post" action="pdfOperationsClient.php" >
            <input type="hidden" name="idClient" id="idClient"  <?php echo  "value=".$id."" ; ?> >
            <input type="hidden" name="dateDebut"  <?php echo  "value=".$dateDebut."" ; ?> >
            <input type="hidden" name="dateFin"  <?php echo  "value=".$dateFin."" ; ?> >
            <button  class="btn btn-primary  pull-left" style="margin-left:20px;" >
                <span class="glyphicon glyphicon-print"></span> Impression Relevé
            </button>
        </form>
    </div>

    <form  class="pull-right form-inline" id="searchProdForm" method="post" name="searchProdForm" >
        <div class="form-group">
            <img src="images/loading-gif3.gif" class="img-load-search form-group" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""> 
        </div> 
    </form>
    <div class="pull-right">
        <input type="text" size="45" class="form-control" name="produit" placeholder="Rechercher produit vendu..."  id="inpt_Search_ListerVentes" autocomplete="off" />
    </div>   

    <?php  

        $sql0="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique ='".$_SESSION['idBoutique']."' and YEAR(datePs)='".$annee."' and MONTH(datePs)!='".$mois."' and aPayementBoutique=0 ";

        $res0=mysqli_query($conn,$sql0);
        $ps = mysqli_fetch_array($res0) ;
        $idPS = @$ps['idPS'];
        $montantFixePayement = @$ps['montantFixePayement'];

        if(mysqli_num_rows($res0)){

            if($jour > 0){

                if($jour > 4){
                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button disabled="true" type="button" class="btn btn-success noImpr" onclick="ajouter_Panier()"  id="btn_Ajouter_Panier">
                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                            </button>
                        </form>
                    '; 
                } 
                else{
                    echo ' 
                        <form name="formulairePagnet" method="post" >
                            <button type="button" class="btn btn-success noImpr" onclick="ajouter_Panier()"  id="btn_Ajouter_Panier">
                                <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                                <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                            </button>
                        </form>
                    ';
                }

                echo "<h6><span id='blinker' style='color:red;'>VEUILLEZ EFFECTUER LE PAIEMENT DU SERVICE JCAISSE AVANT LE 5 !</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick='selectNbMoisPaiement(".$idPS.",".$montantFixePayement.")' style='text-decoration:underline;'>Payer <img src='images/Wave.png' width='25' height='25'></a>&nbsp;&nbsp;&nbsp;&nbsp;<a hidden href='#' onclick='effectue_paiement(".$idPS.")' style='text-decoration:underline;'><img src='images/Orange.png' width='25' height='25'></a></h6>";

                echo '<br>';

            }

            else{

                echo ' 
                    <form name="formulairePagnet" method="post" >
                        <button type="button" class="btn btn-success noImpr" onclick="ajouter_Panier()"  id="btn_Ajouter_Panier">
                            <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                            <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                        </button>
                    </form>
                    
                ';

                echo '<br>';

            }

        }
        else{

            echo ' 
                <form name="formulairePagnet" method="post" >
                    <button type="button" class="btn btn-success noImpr" onclick="ajouter_Panier()"  id="btn_Ajouter_Panier">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Panier
                        <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                    </button>

                    <button type="button" class="btn btn-success noImpr" onclick="ajouter_Versement()"  id="btn_Ajouter_Versement">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un Versement
                        <img src="images/loading-gif3.gif" class="img-load" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset="">                    
                    </button>
                </form>
            ';

            echo '<br>';

        }										

    ?>

    <div class="table-responsive">
            <img src="images/loading-gif3.gif" style="margin-left:40%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
            <div id="listerVentes"><!-- content will be loaded here --></div>
    </div>

    <div id="action_Ligne" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id='spn_hd_action_Ligne'></span></h4>
                </div>
                  <div class="modal-body">
                        <input type="hidden" id="inpt_retourner_Ligne_numligne" />
                        <input type="hidden" id="inpt_retourner_Ligne_idPagnet" />
                        <span id='spn_bd_action_Ligne'></span>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_retourner_Ligne" class="btn btn-danger pull-left"><span id='btn_action_Ligne'></span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div id="action_Panier" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id='spn_hd_action_Panier'></span></h4>
                </div>
                  <div class="modal-body">
                        <input type="hidden" id="inpt_retourner_Panier" />
                        <span id='spn_bd_action_Panier'></span>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_retourner_Panier" class="btn btn-danger pull-left"><span id='btn_action_Panier'></span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div id="action_Versement" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id='spn_hd_action_Versement'></span></h4>
                </div>
                  <div class="modal-body">
                        <input type="hidden" id="inpt_retourner_Versement" />
                        <span id='spn_bd_action_Versement'></span>
                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-6 "> 
                      <button type="button" id="btn_retourner_Versement" class="btn btn-danger pull-left"><span id='btn_action_Versement'></span> </button>
                    </div>
                    <div class="col-sm-6 "> 
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div class="modal fade"  id="image_Versement" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Versement : <b> # <span id="idVersement_View"></span></b></h4>
                </div>
                <form   method="post" enctype="multipart/form-data" id="form_upload_Versement">
                    <div class="modal-body" style="padding:40px 50px;">
                        <input  type="text" style="display:none;" name="idVersement" id="inpt_upload_Versement" />
                        <input  type="text" style="display:none;" name="operation" id="inpt_upload_Operation" value="upload_Image_Versement" />
                        <div class="form-group" style="text-align:center;" >
                            <input type="file" name="file" accept="image/*" id="input_file_Versement" onchange="showPreviewVersement(event);"/><br />
                            <img style="display:none;" width="500px" height="500px" id="output_image_Versement"/>
                            <iframe style="display:none;" id="output_pdf_Versement" width="100%" height="500px"></iframe>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-6 "> 
                            <button type="button" style="display:none" class="btn btn-success pull-left" id="btn_upload_Versement"><span class="glyphicon glyphicon-upload"></span> Enregistrer</button>
                        </div>
                        <div class="col-sm-6 "> 
                            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Fermer</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="./scripts/ventesBons.js"></script>

<script type="text/javascript" src="./load/loadData.js"></script>
