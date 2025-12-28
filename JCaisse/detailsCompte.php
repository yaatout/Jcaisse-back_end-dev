<?php
session_start();
date_default_timezone_set('Africa/Dakar');
if (!$_SESSION['iduser']) {
    header('Location:../index.php');
}

require('connection.php');
require('declarationVariables.php');

/**********************/
/**Debut informations sur la date d'Aujourdhui **/
$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
$annee = $date->format('Y');
$mois = $date->format('m');
$jour = $date->format('d');
$heureString = $date->format('H:i:s');
$dateString = $annee . '-' . $mois . '-' . $jour;
$dateString2 = $jour . '-' . $mois . '-' . $annee;
/**Fin informations sur la date d'Aujourdhui **/

$idCompte = htmlspecialchars(trim($_GET['c']));
if (isset($_GET['debut']) && isset($_GET['fin'])) {
    $dateDebut = @htmlspecialchars($_GET["debut"]);
    $dateFin = @htmlspecialchars($_GET["fin"]);
} else {
    $dateDebut = date('Y-m-d', strtotime('-1 years'));
    $dateFin = $dateString;
}

$sql0 = "SELECT * FROM `" . $nomtableCompte . "` where idCompte=" . $idCompte . "";
$res0 = mysql_query($sql0) or die("persoonel requête 3" . mysql_error());
$compte = mysql_fetch_assoc($res0);

/***Debut compte qui reçoit paiement ***/
$sqlGetComptePay = "SELECT * FROM `" . $nomtableCompte . "` where idCompte <> 2 and idCompte <> 3 ORDER BY idCompte";
$resPay = mysql_query($sqlGetComptePay) or die("persoonel requête 2" . mysql_error());

$compteTransaction = array();
$compteBancaire = array();
$compteType = array();

$sqlv = "select * from `aaa-transaction` where typeTransaction = 'Transaction' ORDER BY nomTransaction ASC";
$resv = mysql_query($sqlv);
while ($operation = mysql_fetch_assoc($resv)) {
    $compteTransaction[] = $operation["nomTransaction"];
}
$sqlb = "select * from `aaa-banque` ORDER BY nom";
$resb = mysql_query($sqlb);
while ($b = mysql_fetch_assoc($resb)) {
    $compteBancaire[] = $b["nom"];
}
// var_dump($compteBancaire);
$sqlt = "select * from `" . $nomtableComptetype . "` ORDER BY nomType ASC ";
$rest = mysql_query($sqlt);
while ($t = mysql_fetch_assoc($rest)) {
    $compteType[] = $t["nomType"];
}

if ($_SESSION['compte'] == 1) {
    # code...
    /***Debut compte qui fait le paiement ***/
    $sqlGetComptePay = "SELECT * FROM `" . $nomtableCompte . "` where idCompte <> 2 and idCompte <> 3 ORDER BY idCompte";
    $resPay = mysql_query($sqlGetComptePay) or die("persoonel requête 2" . mysql_error());
}

if (isset($_POST['btnEnregistrerMouvement'])) {

    $montant = htmlspecialchars(trim($_POST['montant']));
    $idCompteDest = '';
    if (isset($_POST['compteDest'])) {
        $idCompteDest = htmlspecialchars(trim($_POST['compteDest']));
    }
    $avecFrais = '';
    if (isset($_POST['avecFrais'])) {
        $avecFrais = htmlspecialchars(trim($_POST['avecFrais']));
    }
    $montantFrais = 0;
    if (isset($_POST['montantFrais'])) {
        $montantFrais = htmlspecialchars(trim($_POST['montantFrais']));
    }
    $numeroDestinataire = '';
    if (isset($_POST['numeroDestinataire'])) {
        $numeroDestinataire = htmlspecialchars(trim($_POST['numeroDestinataire']));
    }
    $compteDonateur = '';
    if (isset($_POST['compteDonateur'])) {
        $compteDonateur = htmlspecialchars(trim($_POST['compteDonateur']));
    }
    $nomClient = '';
    if (isset($_POST['nomClient'])) {
        $nomClient = htmlspecialchars(trim($_POST['nomClient']));
    }
    $dateEcheance = '2021-01-01';
    if (isset($_POST['dateEcheance'])) {
        $dateEcheance = htmlspecialchars(trim($_POST['dateEcheance']));
    }
    $operation = trim($_POST['operation']);
    $idCompte = trim($_POST['compte']);
    $description = htmlspecialchars(trim($_POST['description']));
    $dateSaisie = $dateHeures;
    if (isset($_POST['dateSaisie'])) {
        $dateSaisie = htmlspecialchars(trim($_POST['dateSaisie']));
    }

    $newMontant = 0;

    //var_dump($operation);
    $sqlv = "select * from `" . $nomtableCompte . "` where idCompte=" . $_GET['c'] . "";
    $resv = mysql_query($sqlv);
    $compte = mysql_fetch_assoc($resv);

    if ($operation == 'versement' or $operation == 'depot' or $operation == 'pret') {
        $newMontant = $compte['montantCompte'] + $montant;
        // var_dump(1);
        $sql1 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('" . $montant . "','" . $operation . "','" . $idCompte . "','" . $numeroDestinataire . "','" . $compteDonateur . "','" . $nomClient . "','" . $dateEcheance . "','" . $dateSaisie . "','" . $dateHeures . "','" . $description . "','" . $_SESSION['iduser'] . "')";
        $res1 = mysql_query($sql1) or die("insertion Cmpte impossible =>" . mysql_error());

        $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $_GET['c'] . "";
        $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());
    } else if ($operation == 'transfert') {

        if ($compte['montantCompte'] >= $montant) {

            if ($idCompteDest != '') {

                $newMontant = $compte['montantCompte'] - $montant - $montantFrais;
                $sql1 = "insert into `" . $nomtableComptemouvement . "` (montant,frais,operation,idCompte,compteDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('" . $montant . "','" . $montantFrais . "','" . $operation . "'," . $idCompte . "," . $idCompteDest . ",'" . $compteDonateur . "','" . $nomClient . "','" . $dateEcheance . "','" . $dateSaisie . "','" . $dateHeures . "','" . $description . "','" . $_SESSION['iduser'] . "')";
                $res1 = mysql_query($sql1) or die("insertion transfert impossible =>" . mysql_error());
                //var_dump($sql1);

                $sqlGetMouv = "SELECT * FROM `" . $nomtableComptemouvement . "` ORDER BY idMouvement DESC LIMIT 1";
                $resMv = mysql_query($sqlGetMouv) or die("persoonel requête 2" . mysql_error());
                $mouvementLink = mysql_fetch_array($resMv);

                $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $_GET['c'] . "";
                $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());

                $description2 = "Trasfert reçu d un autre compte local";
                $sql1_0 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,compteDonateur,dateEcheance,dateSaisie,dateOperation,mouvementLink,description,idUser) values('" . $montant . "','depot'," . $idCompteDest . "," . $idCompte . ",'" . $dateEcheance . "','" . $dateSaisie . "','" . $dateHeures . "'," . $mouvementLink['idMouvement'] . ",'" . $description2 . "','" . $_SESSION['iduser'] . "')";
                $res1_0 = mysql_query($sql1_0) or die("insertion Cmpte impossible =>" . mysql_error());

                $sql2_0 = "UPDATE `" . $nomtableCompte . "` set  montantCompte= montantCompte + '" . $montant . "' where  idCompte=" . $idCompteDest . "";
                $res2_0 = @mysql_query($sql2_0) or die("mise à jour compte dest pour activer ou pas " . mysql_error());
            } else {

                $newMontant = $compte['montantCompte'] - $montant - $montantFrais;
                $sql1 = "insert into `" . $nomtableComptemouvement . "` (montant,frais,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('" . $montant . "','" . $montantFrais . "','" . $operation . "','" . $idCompte . "','" . $numeroDestinataire . "','" . $compteDonateur . "','" . $nomClient . "','" . $dateEcheance . "','" . $dateSaisie . "','" . $dateHeures . "','" . $description . "','" . $_SESSION['iduser'] . "')";
                $res1 = mysql_query($sql1) or die("insertion Cmpte impossible =>" . mysql_error());
                //var_dump($sql1);

                $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $_GET['c'] . "";
                $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());
            }
        } else {
            $messageDel = '';
            echo " <script> 
                            
                    alert('Le montant que vous voulez retirer est superieur à la solde du compte');
                </script>";
        }
    } else {
        // var_dump(2);
        if ($_SESSION['idBoutique'] == 194) {

            $newMontant = $compte['montantCompte'] - $montant;
            $sql1 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('" . $montant . "','" . $operation . "','" . $idCompte . "','" . $numeroDestinataire . "','" . $compteDonateur . "','" . $nomClient . "','" . $dateEcheance . "','" . $dateSaisie . "','" . $dateHeures . "','" . $description . "','" . $_SESSION['iduser'] . "')";
            $res1 = mysql_query($sql1) or die("insertion Cmpte impossible =>" . mysql_error());
            //var_dump($sql1);

            $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $_GET['c'] . "";
            $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());
        } else if ($compte['montantCompte'] >= $montant) {

            $newMontant = $compte['montantCompte'] - $montant;
            $sql1 = "insert into `" . $nomtableComptemouvement . "` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('" . $montant . "','" . $operation . "','" . $idCompte . "','" . $numeroDestinataire . "','" . $compteDonateur . "','" . $nomClient . "','" . $dateEcheance . "','" . $dateSaisie . "','" . $dateHeures . "','" . $description . "','" . $_SESSION['iduser'] . "')";
            $res1 = mysql_query($sql1) or die("insertion Cmpte impossible =>" . mysql_error());
            //var_dump($sql1);

            $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $_GET['c'] . "";
            $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());
        } else {
            $messageDel = '';
            echo " <script> 
                            
                    alert('Le montant que vous voulez retirer est superieur à la solde du compte');
                </script>";
        }
    }
}
if (isset($_POST['btnAnnulerMouvement'])) {

    $idMouvement = trim($_POST['idMouvement']);

    $sql1 = "SELECT * FROM `" . $nomtableComptemouvement . "` where idMouvement=" . $idMouvement . "";
    $res1 = mysql_query($sql1) or die("persoonel requête 3" . mysql_error());
    $mouvement = mysql_fetch_assoc($res1);

    $newMontant = 0;

    if ($mouvement['operation'] == 'versement' or $mouvement['operation'] == 'depot' or $mouvement['operation'] == 'pret') {
        if ($compte['montantCompte'] >= $mouvement['montant']) {
            $newMontant = $compte['montantCompte'] - $mouvement['montant'];

            $sql = "UPDATE `" . $nomtableComptemouvement . "` set  annuler='1' where  idMouvement=" . $idMouvement . "";
            $res = @mysql_query($sql) or die("mise à jour idClient " . mysql_error());

            $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $idCompte . "";
            $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());
        } else {
            $messageDel = 'Désolé mais la somme disponible est inferieur au montant à rembourser';
        }
    } else if ($mouvement['operation'] == 'transfert') {
        $newMontant = $compte['montantCompte'] + $mouvement['montant'] + $mouvement['frais'];

        $sql = "UPDATE `" . $nomtableComptemouvement . "` set  annuler='1' where  idMouvement=" . $idMouvement . "";
        $res = @mysql_query($sql) or die("mise à jour idClient " . mysql_error());
        //var_dump($sql1);

        $sql0 = "UPDATE `" . $nomtableComptemouvement . "` set  annuler='1' where  mouvementLink=" . $idMouvement . "";
        $res0 = @mysql_query($sql0) or die("mise à jour idClient " . mysql_error());

        $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $idCompte . "";
        $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());

        $sql2_0 = "UPDATE `" . $nomtableCompte . "` set montantCompte= montantCompte - '" . $mouvement['montant'] . "' where  idCompte=" . $mouvement['compteDestinataire'] . "";
        $res2_0 = @mysql_query($sql2_0) or die("mise à jour idClient pour activer ou pas " . mysql_error());
    } else {

        if ($_SESSION['idBoutique'] == 194) {

            $newMontant = $compte['montantCompte'] + $mouvement['montant'];

            $sql = "UPDATE `" . $nomtableComptemouvement . "` set  annuler='1' where  idMouvement=" . $idMouvement . "";
            $res = @mysql_query($sql) or die("mise à jour idClient " . mysql_error());

            $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $idCompte . "";
            $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());
        } else if ($compte['montantCompte'] >= $mouvement['montant']) {
            $newMontant = $compte['montantCompte'] + $mouvement['montant'];

            $sql = "UPDATE `" . $nomtableComptemouvement . "` set  annuler='1' where  idMouvement=" . $idMouvement . "";
            $res = @mysql_query($sql) or die("mise à jour idClient " . mysql_error());

            $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $idCompte . "";
            $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());
        } else {
            $messageDel = 'Désolé mais la somme disponible est inferieur au montant à rembourser';
        }
    }
}
if (isset($_POST['btnRetirerCheque'])) {

    $montant = htmlspecialchars(trim($_POST['montant']));
    $operation = trim($_POST['operation']);
    $idCompte = trim($_POST['compte']);
    $idMouvement = trim($_POST['mouvement']);
    $description = trim($_POST['description']);

    $newMontant = 0;

    //var_dump($operation);
    $sqlv = "select * from `" . $nomtableCompte . "` where idCompte=" . $_GET['c'] . "";
    $resv = mysql_query($sqlv);
    $compte = mysql_fetch_assoc($resv);


    if ($operation == 'versement' or $operation == 'depot') {

        if ($compte['montantCompte'] >= $montant) {
            $newMontant = $compte['montantCompte'] - $montant;

            $sql = "UPDATE `" . $nomtableComptemouvement . "` set  retirer=1, dateRetrait='" . $dateHeures . "',description='" . $description . "' where idMouvement=" . $idMouvement . "";
            //echo $sql;
            $res = @mysql_query($sql) or die("mise à jour idClient " . mysql_error());

            $sql2 = "UPDATE `" . $nomtableCompte . "` set  montantCompte='" . $newMontant . "' where  idCompte=" . $_GET['c'] . "";
            $res2 = @mysql_query($sql2) or die("mise à jour idClient pour activer ou pas " . mysql_error());
        } else {
            $messageDel = '';
        }
    }
    /*else{
            
            $newMontant=$compte['montantCompte']+$montant;
                
            $sql="UPDATE `".$nomtableComptemouvement."` set  annuler='1' where  idMouvement=".$idMouvement."";
            $res=@mysql_query($sql) or die ("mise à jour idClient ".mysql_error());
            //var_dump($sql1);

           $sql2="UPDATE `".$nomtableCompte."` set  montantCompte='".$newMontant."' where  idCompte=".$_GET['c']."";
           $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
       
    }*/
}

/**Debut Button upload Image Mouvement**/
if (isset($_POST['btnUploadImgMouvement'])) {
    $idMouvement = htmlspecialchars(trim($_POST['idMouvement']));
    if (isset($_FILES['file'])) {
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif', 'pdf'];
        $maxSize = 400000;

        if (in_array($extension, $extensions) && $error == 0) {

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName . "." . $extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './images/' . $file);

            $sql2 = "UPDATE `" . $nomtableComptemouvement . "` set image='" . $file . "' where idMouvement='" . $idMouvement . "' ";
            $res2 = mysql_query($sql2) or die("modification 1 impossible =>" . mysql_error());
        } else {
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Mouvement**/

require('entetehtml.php');
?>
<!-- Debut Code HTML -->

<body>
    <?php require('header.php'); ?>
    <!-- Debut Container HTML -->
    <div class="container">

        <input id="inpt_Compte_id" type="hidden" value="<?= $idCompte ?>" />
        <input id="inpt_Compte_dateDebut" type="hidden" value="<?= $dateDebut ?>" />
        <input id="inpt_Compte_dateFin" type="hidden" value="<?= $dateFin ?>" />

        <script type="text/javascript">
            $(function() {

                id = <?php echo json_encode($idCompte); ?>;

                dateDebut = <?php echo json_encode($dateDebut); ?>;

                dateFin = <?php echo json_encode($dateFin); ?>;

                tabDebut = dateDebut.split('-');

                tabFin = dateFin.split('-');

                var start = tabDebut[2] + '/' + tabDebut[1] + '/' + tabDebut[0];

                var end = tabFin[2] + '/' + tabFin[1] + '/' + tabFin[0];

                function cb(start, end) {

                    var debut = start.format('YYYY-MM-DD');

                    var fin = end.format('YYYY-MM-DD');

                    window.location.href = "detailsCompte.php?c=" + id + "&&debut=" + debut + "&&fin=" + fin;


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

                        daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],

                        monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
                            'Aout', 'Septembre', 'Octobre', 'November', 'Decembre'
                        ],

                        firstDay: 1

                    },

                    ranges: {

                        'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

                        'Une Semaine': [moment().subtract(6, 'days'), moment()],

                        'Un Mois': [moment().subtract(30, 'days'), moment()],

                        'Ce mois ci': [moment().startOf('month'), moment()],

                        'Dernier Mois': [moment().subtract(1, 'month').startOf('month'), moment().subtract(
                            1, 'month').endOf('month')]

                    },

                }, cb);

                cb(start, end);

            });

            function showImageMouvement(idMouvement) {
                var nom = $('#imageMouvement' + idMouvement).attr("data-image");
                $('#idMouvement_View').text(nom);
                $('#idMouvement_Upd_Nv').val(idMouvement);
                $('#input_file_Mouvement').val('');
                $('#imageNvMouvement').modal('show');
                var file = $('#imageMouvement' + idMouvement).val();
                if (file != null && file != '') {
                    var format = file.substr(file.length - 3);
                    if (format == 'pdf') {
                        document.getElementById('output_pdf_Mouvement').style.display = "block";
                        document.getElementById('output_image_Mouvement').style.display = "none";
                        document.getElementById("output_pdf_Mouvement").src = "images/" + file;
                    } else {
                        document.getElementById('output_image_Mouvement').style.display = "block";
                        document.getElementById('output_pdf_Mouvement').style.display = "none";
                        document.getElementById("output_image_Mouvement").src = "images/" + file;
                    }
                } else {
                    document.getElementById('output_pdf_Mouvement').style.display = "none";
                    document.getElementById('output_image_Mouvement').style.display = "none";
                }
            }

            function showPreviewMouvement(event) {
                var file = document.getElementById('input_file_Mouvement').value;
                var reader = new FileReader();
                reader.onload = function() {
                    var format = file.substr(file.length - 3);
                    var pdf = document.getElementById('output_pdf_Mouvement');
                    var image = document.getElementById('output_image_Mouvement');
                    if (format == 'pdf') {
                        document.getElementById('output_pdf_Mouvement').style.display = "block";
                        document.getElementById('output_image_Mouvement').style.display = "none";
                        pdf.src = reader.result;
                    } else {
                        document.getElementById('output_image_Mouvement').style.display = "block";
                        document.getElementById('output_pdf_Mouvement').style.display = "none";
                        image.src = reader.result;
                    }
                }
                reader.readAsDataURL(event.target.files[0]);
                document.getElementById('btn_upload_Mouvement').style.display = "block";
            }
        </script>

        <div class="col-sm-4 pull-left">
            <a class="btn btn-warning  pull-left" style="margin-top:8px;" href="compte.php"> Retour </a>
        </div>

        <div class="jumbotron noImpr">
            <div class="col-sm-2 pull-right">
                <div aria-label="navigation">
                    <ul class="pager">
                        <li>
                            <input type="text" id="reportrange" />
                        </li>
                    </ul>
                </div>
            </div>
            <h2>Les Operations du Compte : <?php echo strtoupper($compte['nomCompte']); ?> </h2>
            <h5>Du :
                <?php
                $debutDetails = explode("-", $dateDebut);
                $dateDebutA = $debutDetails[2] . '-' . $debutDetails[1] . '-' . $debutDetails[0];
                $finDetails = explode("-", $dateFin);
                $dateFinA = $finDetails[2] . '-' . $finDetails[1] . '-' . $finDetails[0];
                echo $dateDebutA . " au " . $dateFinA;
                ?>
            </h5>
            <div class="panel-group">
                <div class="panel" style="background:#cecbcb;">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">Montant :
                                <?php echo number_format($compte['montantCompte'], 2, ',', ' '); ?> </a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                    </div>
                </div>
            </div>
            <form class="form-inline pull-left noImpr" target="_blank" style="margin-left:20px;" method="post" action="pdfOperationsCompte.php">
            </form>
            <div class="form-group pull-right noImpr">
                <input type="text" size="45" class="form-control form-group" name="produit" placeholder="Rechercher ..." id="inpt_Search_ListerOperations" autocomplete="off" />
            </div>
        </div>

        <?php
        // var_dump($compte['typeCompte']);
        if (in_array($compte['typeCompte'], $compteBancaire) || in_array($compte['typeCompte'], $compteTransaction) || $compte['typeCompte'] == 'compte tiers' || $compte['typeCompte'] == 'compte epargne' || strtolower($compte['typeCompte']) == 'caisse') {
            if (in_array($compte['typeCompte'], $compteBancaire)) {  ?>
                <button align="right" type="button" class="btn btn-success noImpr  pull-left" data-toggle="modal" data-target="#versement">
                    <i class="glyphicon glyphicon-plus"></i>Versement
                </button>
                <div class="modal fade" id="versement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Ajout un nouveau Versement</h4>
                            </div>
                            <div class="modal-body">
                                <form name="formulairePersonnel" method="post">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Montant <font color="red">*</font>
                                        </label>
                                        <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant">
                                        <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">

                                        <input type="hidden" class="form-control" value="versement" name="operation">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                        <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Description <font color="red">*</font>
                                        </label>
                                        <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Informations complémentaires">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="modal-footer">
                                        <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary noImpr col-sm-offset-4" data-toggle="modal" data-target="#virement">
                    <i class="glyphicon glyphicon-plus"></i>Virement
                </button>
                <div class="modal fade" id="virement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Nouveau virement</h4>
                            </div>
                            <div class="modal-body">
                                <form name="formulairePersonnel" method="post">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Montant <font color="red">*</font>
                                        </label>
                                        <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant">
                                        <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Numero compte du destinataire <font color="red">*</font></label>
                                        <input type="tel" class="form-control" name="numeroDestinataire" required placeholder="Numero Compte">

                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">

                                        <input type="hidden" class="form-control" value="virement" name="operation">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                        <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Description <font color="red">*</font>
                                        </label>
                                        <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Informations complémentaires">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="modal-footer">
                                        <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            <?php
            } else {  ?>
                <button align="right" type="button" class="btn btn-success noImpr  pull-left" data-toggle="modal" data-target="#depot">
                    <i class="glyphicon glyphicon-plus"></i>Depot
                </button>
                <div class="modal fade" id="depot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Ajout un nouveau depot</h4>
                            </div>
                            <div class="modal-body">
                                <form name="formulairePersonnel" method="post">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Montant <font color="red">*</font>
                                        </label>
                                        <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant">
                                        <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">

                                        <input type="hidden" class="form-control" value="depot" name="operation">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                        <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Description <font color="red">*</font>
                                        </label>
                                        <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Informations complémentaires">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="modal-footer">
                                        <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary noImpr col-sm-offset-5" data-toggle="modal" data-target="#Transfert">
                    <i class="glyphicon glyphicon-plus"></i>Transfert
                </button>
                <div class="modal fade" id="Transfert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Faire un transfert</h4>
                            </div>
                            <div class="modal-body">
                                <form name="formulairePersonnel" method="post">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Montant <font color="red">*</font>
                                        </label>
                                        <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant">
                                        <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                        <input type="hidden" class="form-control" value="transfert" name="operation">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline  myRadio">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="entreCompte" value="option1" checked>
                                            <label class="form-check-label" for="entreCompte">Entre mes comptes</label>
                                            <input class="form-check-input" style="margin-left: 50px;" type="radio" name="inlineRadioOptions" id="autreCompte" value="option2">
                                            <label class="form-check-label" for="autreCompte">Vers un autre compte</label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divCompteDest">
                                        <label for="inputEmail3" class="control-label">Compte destinataire <font color="red">*
                                            </font></label>
                                        <select class="form-control compte" id="compteDest" name="compteDest" required>
                                            <!-- <option value="caisse">Caisse</option> -->
                                            <?php
                                            while ($cpt = mysql_fetch_array($resPay)) { ?>
                                                <option value="<?= $cpt['idCompte']; ?>"><?= $cpt['nomCompte']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group" id="divNumDest" style="display: none;">
                                        <label for="inputEmail3" class="control-label">Numero Téléphone du destinataire <font color="red">*</font></label>
                                        <input type="tel" class="form-control" id="numeroDestinataire" name="numeroDestinataire" placeholder="Numero Telephone du destinataire">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline  myRadio">
                                            <input class="form-check-input" style="margin-left: 50px;" type="checkbox" name="avecFrais" id="avecFrais" value="option3">
                                            <label class="form-check-label" for="avecFrais">Avec frais</label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divMontantFrais" style="display: none;">
                                        <label for="inputEmail3" class="control-label">Montant frais <font color="red">*</font>
                                        </label>
                                        <input type="tel" class="form-control" id="montantFrais" name="montantFrais" placeholder="Montant frais">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                        <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Description <font color="red">*</font>
                                        </label>
                                        <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Informations complémentaires">
                                        <span class="text-danger"></span>
                                    </div>
                                    <div class="modal-footer">
                                        <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            <?php  } ?>

            <button type="button" class="btn btn-danger noImpr  pull-right" data-toggle="modal" data-target="#retrait">
                <i class="glyphicon glyphicon-plus"></i>Retrait
            </button>
            <div class="modal fade" id="retrait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Faire un retrait</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post">
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Montant <font color="red">*</font>
                                    </label>
                                    <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant">
                                    <input type="hidden" class="form-control" name="compte" value="<?php echo  $_GET['c']; ?>">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">

                                    <input type="hidden" class="form-control" value="retrait" name="operation">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                    <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Description <font color="red">*</font>
                                    </label>
                                    <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Informations complémentaires">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="modal-footer">
                                    <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php } elseif ($compte['typeCompte'] == 'compte cheques') { //COMPTE CHEQUE  
        ?>

            <button align="right" type="button" class="btn btn-success noImpr  pull-left" data-toggle="modal" data-target="#depotC">
                <i class="glyphicon glyphicon-plus"></i>Depot

            </button>
            <div class="modal fade" id="depotC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau compte</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post">
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Montant <font color="red">*</font>
                                    </label>
                                    <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant">
                                    <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" value="depot" name="operation">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Banque<font color="red">*</font></label>
                                    <select name="compteDonateur">
                                        <?php

                                        $sqlv = "select * from `aaa-banque` ";
                                        $resv = mysql_query($sqlv);
                                        while ($operation = mysql_fetch_assoc($resv)) {
                                            echo '<option value="' . $operation["nom"] . '">' . $operation["nom"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Nom client <font color="red">*</font>
                                    </label>
                                    <input type="test" min="1" class="form-control" id="inputprenom" required="" name="nomClient">

                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Date echéance<font color="red">*</font>
                                    </label>
                                    <input type="date" class="form-control" id="inputprenom" required="" name="dateEcheance">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Description <font color="red">*</font>
                                    </label>
                                    <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Informations complémentaires">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="modal-footer">
                                    <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        <?php } elseif ($compte['typeCompte'] == 'compte pret') { //COMPTE CHEQUE  
        ?>

            <button align="right" type="button" class="btn btn-success noImpr  pull-left" data-toggle="modal" data-target="#depotPret">
                <i class="glyphicon glyphicon-plus"></i>Nouveau pret

            </button>
            <div class="modal fade" id="depotPret" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau pret</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post">
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Montant <font color="red">*</font>
                                    </label>
                                    <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant">
                                    <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">

                                    <input type="hidden" class="form-control" value="pret" name="operation">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                    <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Description <font color="red">*</font>
                                    </label>
                                    <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Informations complémentaires">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="modal-footer">
                                    <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <button align="right" type="button" class="btn btn-danger noImpr  pull-right" data-toggle="modal" data-target="#depotRemb">
                <i class="glyphicon glyphicon-plus"></i>Remboursement

            </button>
            <div class="modal fade" id="depotRemb" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau remboursement</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post">
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Montant <font color="red">*</font>
                                    </label>
                                    <input type="number" min="1" class="form-control" id="inputprenom" required="" name="montant">
                                    <input type="hidden" class="form-control" name="compte" value=" <?php echo  $_GET['c']; ?>">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">

                                    <input type="hidden" class="form-control" value="remboursement" name="operation">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Date <font color="red">*</font></label>
                                    <input type="date" class="form-control" id="inputprenom" required="" name="dateSaisie">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Description <font color="red">*</font>
                                    </label>
                                    <input type="texterea" class="form-control" id="inputprenom" required="" name="description" placeholder="Informations complémentaires">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="modal-footer">
                                    <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                    <button type="submit" name="btnEnregistrerMouvement" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php }
        ?>

        <!-- Debut Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->
        <style>
            /* Firefox */
            input[type=number] {
                -moz-appearance: textfield;
            }

            /* Chrome */
            input::-webkit-inner-spin-button,
            input::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Opéra*/
            input::-o-inner-spin-button,
            input::-o-outer-spin-button {
                -o-appearance: none;
                margin: 0
            }
        </style>
        <!-- Fin Style CSS pour eliminer les ascenseurs sur les Input de type Numeric -->

        <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
        <script>
            $(function() {
                $(".expand").on("click", function() {
                    // $(this).next().slideToggle(200);
                    $expand = $(this).find(">:first-child");

                    if ($expand.text() == "+") {
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
            <div id="listerOperations">
                <!-- content will be loaded here -->
            </div>
        </div>
        <!-- Fin de l'Accordion pour Tout les Paniers -->


        <div id="annuler_Operation" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Annuler Operation</h4>
                    </div>
                    <form class="form-inline noImpr" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="idMouvement" id="inpt_annl_Mouvement_idFournisseur" />
                            <p>Voulez vous vraiment annuler cette operation
                                numero <span id='span_annl_Mouvement_Numero'></span> de
                                <span id='span_annl_Mouvement_Montant'></span>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="btnAnnulerMouvement" class="btn btn-success">Confirmer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="imageNvMouvement" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Mouvement : <b># <span id="idMouvement_View"></span></b></h4>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:0px 150px;">
                            <input type="text" style="display:none;" name="idMouvement" id="idMouvement_Upd_Nv" />
                            <div class="form-group" style="text-align:center;">
                                <input type="file" name="file" accept="image/*" id="input_file_Mouvement" onchange="showPreviewMouvement(event);" /><br />
                                <img style="display:none;" width="300px" height="400px" id="output_image_Mouvement" />
                                <iframe style="display:none;" id="output_pdf_Mouvement" width="100%" height="100%"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgMouvement" id="btn_upload_Mouvement">
                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                            </button>
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

<script type="text/javascript" src="./scripts/detailsCompte.js"></script>