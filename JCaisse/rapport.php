<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:05/04/2016
*/
session_start();
if($_SESSION['iduser']){

require('connection.php');

require('declarationVariables.php');

require('entetehtml.php');
/**********************/

$type             =@htmlentities($_POST["type"]);

$dateInventaire1     =@$_POST["dateInventaire"];

$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime($dateInventaire1);
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$dateInventaire=$jour.'-'.$mois.'-'.$annee;

/**********************/
$numligne         =@$_POST["numligne"];
$type             =@htmlentities($_POST["type"]);
$designation      =@htmlentities($_POST["designation"]);
$unitevente		  =@$_POST["unitevente"];
$prix             =@$_POST["prix"];
$quantite         =@$_POST["quantite"];
$remise           =@$_POST["remise"];
$prixtotal        =@$_POST["prixt"];

$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/

$debut='';
$fin='';

echo '

<!-- Debut Style CSS des Tabs -->
    <style>
        /***
        Bootstrap Line Tabs by @keenthemes
        A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: http://j.mp/metronictheme
        Licensed under MIT
        ***/

        /* Tabs panel */
        .tabbable-panel {
        border:3px solid #eee;
        padding: 10px;
        }

        /* Default mode */
        .tabbable-line > .nav-tabs {
        border: none;
        margin: 0px;
        }
        .tabbable-line > .nav-tabs > li {
        margin-right: 2px;
        }
        .tabbable-line > .nav-tabs > li > a {
        border: 0;
        margin-right: 0;
        color: #737373;
        }
        .tabbable-line > .nav-tabs > li > a > i {
        color: #a6a6a6;
        }
        .tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
        border-bottom: 4px solid #fbcdcf;
        }
        .tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
        border: 0;
        background: none !important;
        color: #333333;
        }
        .tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
        color: #a6a6a6;
        }
        .tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
        margin-top: 0px;
        }
        .tabbable-line > .nav-tabs > li.active {
        border-bottom: 4px solid #f3565d;
        position: relative;
        }
        .tabbable-line > .nav-tabs > li.active > a {
        border: 0;
        color: #333333;
        }
        .tabbable-line > .nav-tabs > li.active > a > i {
        color: #404040;
        }
        .tabbable-line > .tab-content {
        margin-top: -3px;
        background-color: #fff;
        border: 0;
        border-top: 1px solid #eee;
        padding: 15px 0;
        }
        .portlet .tabbable-line > .tab-content {
        padding-bottom: 0;
        }

        /* Below tabs mode */

        .tabbable-line.tabs-below > .nav-tabs > li {
        border-top: 4px solid transparent;
        }
        .tabbable-line.tabs-below > .nav-tabs > li > a {
        margin-top: 0;
        }
        .tabbable-line.tabs-below > .nav-tabs > li:hover {
        border-bottom: 0;
        border-top: 4px solid #fbcdcf;
        }
        .tabbable-line.tabs-below > .nav-tabs > li.active {
        margin-bottom: -2px;
        border-bottom: 0;
        border-top: 4px solid #f3565d;
        }
        .tabbable-line.tabs-below > .tab-content {
        margin-top: -10px;
        border-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        }
    </style>
<!-- Fin Style CSS des Tabs -->

<!-- Debut Javascript de l"Accordion pour Tout les Paniers -->
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
<!-- Fin Javascript de l"Accordion pour Tout les Paniers -->

';

/**Debut Button upload Image Bl**/
if (isset($_POST['btnUploadImgBl'])) {
    $idBl=htmlspecialchars(trim($_POST['idBl']));
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

/**Debut Button upload Image Versement**/
if (isset($_POST['btnUploadImgVersement'])) {
    $idVersement=htmlspecialchars(trim($_POST['idVersement']));
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

            $sql2="UPDATE `".$nomtableVersement."` set image='".$file."' where idVersement='".$idVersement."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Versement**/

/**Debut Button upload Image Panier**/
if (isset($_POST['btnUploadImgPanier'])) {
    $idPagnet=htmlspecialchars(trim($_POST['idPanier']));
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

            $sql2="UPDATE `".$nomtablePagnet."` set image='".$file."' where idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Panier**/

/**Debut Button upload Image Bon**/
if (isset($_POST['btnUploadImgBon'])) {
    $idPagnet=htmlspecialchars(trim($_POST['idPagnet']));
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

            $sql2="UPDATE `".$nomtablePagnet."` set image='".$file."' where idPagnet='".$idPagnet."' ";
            $res2=mysql_query($sql2) or die ("modification 1 impossible =>".mysql_error());
        }
        else{
            echo "Une erreur est survenue";
        }
    }
}
/**Fin Button upload Image Bon**/



echo'<body><header>';

require('header.php');

echo'<div class="container" >';

if(!$annuler){
if((!@$_POST["dateInventaire"])&&(!@$_GET["datenext"])&&(!@$_GET["dateprevious"])){
$datehier = date('d-m-Y', strtotime('-1 days'));
$datehier_Y = date('Y-m-d', strtotime('-1 days'));

echo'<section><div class="container">'; ?>

<?php
//-----------------------------------------------------------------------------------

       ?>

        <script type="text/javascript">
            $(function() {
                var start = moment().subtract(30, 'days');
                var end = moment();

                function cb(start, end) {
                    //alert(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
                    var dateDebut=start.format('DD-MM-YYYY');
                    var dateFin=end.format('DD-MM-YYYY');
                    $('#id_dateDebut').text(dateDebut);
                    $('#id_dateFin').text(dateFin);
                    $('#id_dateDebut2').text(dateDebut);
                    $('#id_dateFin2').text(dateFin);

                    var debut=start.format('YYYY-MM-DD');
                    var fin=end.format('YYYY-MM-DD');

                    $('#input_dateDebut').val(debut);
                    $('#input_dateFin').val(fin);

                    var debutAnnee=start.format('YYYY-MM-DD');
                    var finAnnee=end.format('YYYY-MM-DD');
                    var debutJour=start.format('DD-MM-YYYY');
                    var finJour=end.format('DD-MM-YYY');

                    $('.debutAnnee').val(debutAnnee);
                    $('.finAnnee').val(finAnnee);
                    // alert(debutAnnee +' / '+finAnnee);
                    $.ajax({
                        url:"ajax/ajouterLigneAjax.php", 
                        method:"POST",
                        data:{
                            operation:57,
                            debutAnnee : debutAnnee,
                            finAnnee : finAnnee,
                            debutJour : debutJour,
                            finJour : finJour
                        },
                        success: function (data) {
                            tab=data.split('<>');
                            // alert(data)
                            /************************************************************* */
                                $('#idRapport_EntreeArgent').text(tab[0]);
                                $('#idRapport_VersementsClient').text(tab[1]);
                                $('#idRapport_Vente').text(tab[2]);
                                $('#idRapport_Services').text(tab[3]);
                                $('#idRapport_ProdduitsRetournes').text(tab[4]);
                                $('#idRapport_SortiesArgent').text(tab[5]);
                                $('#idRapport_Depenses').text(tab[6]);
                                $('#idRapport_FournisseursVersements').text(tab[7]);
                                $('#idRapport_BonsEspeces').text(tab[8]);
                                $('#idRapport_RemisesVente').text(tab[9]);
                                $('#idRapport_RestantCaisse').text(tab[10]);
                                $('#idRapport_RecouvrementClients').text(tab[11]);
                                //$('#idRapport_ValeurStockCourant').text(tab[12]);
                                $('#idRapport_ValeursEntreesPA').text(tab[12]);
                                $('#idRapport_ValeursEntreesPU').text(tab[13]);
                                $('#idRapport_Patrimoine').text(tab[14]);
                                $('#idRapport_PatrimoineEntreesPU').text(tab[13]);
                                $('#idRapport_PatrimoineRestantCaisse').text(tab[10]);
                                $('#idRapport_PatrimoineBl_non_paye').text(tab[15]);
                                $('#idRapport_PatrimoineProduitsRetires').text(tab[16]);
                                $('#idRapport_RemisesClient').text(tab[22]);
                                $('#idRapport_Benefice').text(tab[23]);
                                $('#idRapport_ApproCaisse').text(tab[24]);
                                $('#idRapport_Mutuelle').text(tab[25]);
                                $('#idRapport_Recouvrement').text(tab[26]);
                                $('#idRapport_PatrimoineRecouvrement').text(tab[26]);
                                $('#idRapport_VenteImputation').text(tab[27]);
                                $('#idRapport_RetraitCaisse').text(tab[28]);
                            /************************************************************* */

                            /************************************************************* */
                                $('#idTab_Depenses').text(tab[6]);
                                $('.idTab_EntreesPA').text(tab[17]);
                                $('.idTab_EntreesPU').text(tab[18]);
                                $('.idTab_Ventes').text(tab[19]);
                                $('.idTab_Bons').text(tab[20]);
                                $('#idTab_ProduitsRetires').text(tab[16]);
                                $('#idTab_ProduitsRetournes').text(tab[4]);
                                $('#idTab_BonsEspeces').text(tab[8]);
                                $('#idTab_VersementsClient').text(tab[1]);
                                $('#idTab_BonsDeLivraison').text(tab[21]);
                                $('#idTab_VersementsFournisseurs').text(tab[7]);
                                $('#idTab_Services').text(tab[3]);
                                $('#idTab_RemisesVente').text(tab[9]);
                                $('#idTab_RemisesClient').text(tab[22]);
                                $('#idTab_ApproC').text(tab[24]);
                                $('#idTab_Mutuelles').text(tab[25]);
                                $('#idTab_MutuellesVentes').text(tab[27]);
                                $('#idTab_RetraitC').text(tab[28]);
                            /************************************************************* */

                        },
                        error: function(err) {
                            console.log(err);
                            alert("La requête "); },
                        dataType:"text"
                    });

                    $(document).ready(function() {
                        nbEntreeDepense = $('#nbEntreeDepense').val()
                        $(".loading-gif").show();

                        $("#listeDesDepenses").empty();

                        $(this).removeClass('active');
                        $('.nav-tabs a:first').tab('show');

                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesDepenses").load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbEntreeDepense":nbEntreeDepense,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                            $("#container_Result").show();
                        });

                    });                  

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

            $(document).ready(function() {

                /*************** Début lister Dépenses ***************/
                        //executes code below when user click on pagination links
                        $("#listeDesDepenses").on( "click", ".pagination a", function (e){
                            // $("#listeDesDepenses").on( "click", function (e){
                            e.preventDefault();
                            $(".loading-gif").show(); //show loading element
                            page = $(this).attr("data-page"); //get page number from link
                            nbEntreeDepense = $('#nbEntreeDepense').val()
                            query = $('#searchInputDepense').val();

                            debut=$('#input_dateDebut').val();
                            fin=$('#input_dateFin').val();

                            if (query.length == 0) {
                                $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeDepense":nbEntreeDepense,"query":"","cb":""}, function(){ //get content from PHP page
                                    $(".loading-gif").hide(); //once done, hide loading element
                                });
                                    
                            }else{
                                $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeDepense":nbEntreeDepense,"query":query,"cb":""}, function(){ //get content from PHP page
                                    $(".loading-gif").hide(); //once done, hide loading element
                                });
                            }
                            // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                        });

                        $('#searchInputDepense').on("keyup", function(e) {
                            e.preventDefault();
                            
                            query = $('#searchInputDepense').val()
                            nbEntreeDepense = $('#nbEntreeDepense').val()

                            debut=$('#input_dateDebut').val();
                            fin=$('#input_dateFin').val();

                            var keycode = (e.keyCode ? e.keyCode : e.which);
                            if (keycode == '13') {
                                // alert(1111)
                                t = 1; // code barre
                                
                                if (query.length > 0) {
                                    
                                    $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbEntreeDepense":nbEntreeDepense,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbEntreeDepense":nbEntreeDepense,"query":"","cb":t}); //load initial records
                                }
                            }else{
                                // alert(2222)
                                t = 0; // no code barre
                                setTimeout(() => {
                                    if (query.length > 0) {
                                        
                                        $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbEntreeDepense":nbEntreeDepense,"query":query,"cb":t}); //load initial records
                                    }else{
                                        $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbEntreeDepense":nbEntreeDepense,"query":"","cb":t}); //load initial records
                                    }
                                }, 100);
                            }
                        });

                        $('#nbEntreeDepense').on("change", function(e) {
                            e.preventDefault();

                            nbEntreeDepense = $('#nbEntreeDepense').val()
                            query = $('#searchInputDepense').val();

                            debut=$('#input_dateDebut').val();
                            fin=$('#input_dateFin').val();

                            if (query.length == 0) {
                                $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense,"query":"","cb":""}); //load initial records
                                    
                            }else{
                                $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense,"query":query,"cb":""}); //load initial records
                            }
                                
                            // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                        });
                /*************** Fin lister Dépenses ***************/

                /*************** Début lister entrées par stock ***************/
                    $("#entreesEvent").on( "click", function (e){

                        e.preventDefault();
                        $(".btn_Impression").hide();
                        $(".loading-gif").show();

                        nbEntreeParStock = $('#nbEntreeParStock').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeParStock":nbEntreeParStock,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                            $(".btn_Impression").show();
                        });

                    });
                    
                    $("#listeDesEntreesParStock").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeParStock = $('#nbEntreeParStock').val();
                        query = $('#searchInputParStock').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeParStock":nbEntreeParStock,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeParStock":nbEntreeParStock,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesEntreesParStock").load("ajax/listerRapportEntreesStockAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputParStock').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputParStock').val();
                        nbEntreeParStock = $('#nbEntreeParStock').val();                       
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeParStock":nbEntreeParStock,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeParStock":nbEntreeParStock,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeParStock":nbEntreeParStock,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeParStock":nbEntreeParStock,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeParStock').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeParStock = $('#nbEntreeParStock').val()
                        query = $('#searchInputParStock').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeParStock":nbEntreeParStock,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeParStock":nbEntreeParStock,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesEntreesParStock" ).load( "ajax/listerRapportEntreesStockAjax.php",{"operation":4,"nbEntreeParStock":nbEntreeParStock}); //load initial records
                    });
                /*************** Fin lister entrées par stock ***************/

                /*************** Début lister entrées par produit ***************/
                    $("#entreesProduitEvent").on( "click", function (e){

                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeParProduit = $('#nbEntreeParProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeParProduit":nbEntreeParProduit,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    //executes code below when user click on pagination links
                    $("#listeDesEntreesParProduit").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link

                        nbEntreeParProduit = $('#nbEntreeParProduit').val();
                        query = $('#searchInputParProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeParProduit":nbEntreeParProduit,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeParProduit":nbEntreeParProduit,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesEntreesParProduit").load("ajax/listerRapportEntreesAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputParProduit').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputParProduit').val()
                        nbEntreeParProduit = $('#nbEntreeParProduit').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();
                        

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeParProduit":nbEntreeParProduit,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeParProduit":nbEntreeParProduit,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeParProduit":nbEntreeParProduit,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeParProduit":nbEntreeParProduit,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeParProduit').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeParProduit = $('#nbEntreeParProduit').val()
                        query = $('#searchInputParProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeParProduit":nbEntreeParProduit,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeParProduit":nbEntreeParProduit,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesEntreesParProduit" ).load( "ajax/listerRapportEntreesAjax.php",{"operation":4,"nbEntreeParProduit":nbEntreeParProduit}); //load initial records
                    });
                /*************** Fin lister entrées par produit ***************/

                /*************** Début lister sorties par panier ***************/
                    $("#sortiesEvent").on( "click", function (e){

                        $('#tab_default_31 a:first').tab('show');

                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeSVenteParPagnet = $('#nbEntreeSVenteParPagnet').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesSortiesVParPagnet").load("ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                            //$("#tab_default_31A").show();
                        });

                    });

                    $("#listeDesSortiesVParPagnet").on( "click", ".pagination a", function (e){

                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeSVenteParPagnet = $('#nbEntreeSVenteParPagnet').val();
                        query = $('#searchInputSVenteParPagnet').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesSortiesVParPagnet").load("ajax/listerRapportVentePanierAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputSVenteParPagnet').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputSVenteParPagnet').val();
                        nbEntreeSVenteParPagnet = $('#nbEntreeSVenteParPagnet').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeSVenteParPagnet').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeSVenteParPagnet = $('#nbEntreeSVenteParPagnet').val()
                        query = $('#searchInputSVenteParPagnet').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesSortiesVParPagnet" ).load( "ajax/listerRapportVentePanierAjax.php",{"operation":4,"nbEntreeSVenteParPagnet":nbEntreeSVenteParPagnet}); //load initial records
                    });
                /*************** Fin lister sorties par panier ***************/

                /*************** Début lister sorties par produit ***************/
                    $("#ventesProduitEvent").on( "click", function (e){

                        e.preventDefault();
                        $(".btn_Impression").hide();
                        $(".loading-gif").show();

                        nbEntreeSVenteParProduit = $('#nbEntreeSVenteParProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                            $(".btn_Impression").show();
                        });

                    });

                    //executes code below when user click on pagination links
                    $("#listeDesSortiesVParProduit").on( "click", ".pagination a", function (e){
                        // $("#listeDesSortiesVParProduit").on( "click", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeSVenteParProduit = $('#nbEntreeSVenteParProduit').val();
                        query = $('#searchInputSVenteParProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesSortiesVParProduit").load("ajax/listerRapportVenteAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputSVenteParProduit').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputSVenteParProduit').val()
                        nbEntreeSVenteParProduit = $('#nbEntreeSVenteParProduit').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();
                        

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeSVenteParProduit').on("change", function(e) {

                        e.preventDefault();

                        nbEntreeSVenteParProduit = $('#nbEntreeSVenteParProduit').val();
                        query = $('#searchInputSVenteParProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesSortiesVParProduit" ).load( "ajax/listerRapportVenteAjax.php",{"operation":4,"nbEntreeSVenteParProduit":nbEntreeSVenteParProduit}); //load initial records
                    });

                /*************** Fin lister sorties par produit ***************/
                
                /*************** Début lister bons par panier ***************/
                    $("#sortiesBonsEvent").on( "click", function (e){
                        
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeBonParPanier = $('#nbEntreeBonParPanier').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesBonsParPanier").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link

                        nbEntreeBonParPanier = $('#nbEntreeBonParPanier').val();
                        query = $('#searchInputBonParPanier').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesBonsParPanier").load("ajax/listerRapportBonPanierAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputBonParPanier').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputBonParPanier').val()
                        nbEntreeBonParPanier = $('#nbEntreeBonParPanier').val()                                   
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeBonParPanier').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeBonParPanier = $('#nbEntreeBonParPanier').val()
                        query = $('#searchInputBonParPanier').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeBonParPanier":nbEntreeBonParPanier,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesBonsParPanier" ).load( "ajax/listerRapportBonPanierAjax.php",{"operation":4,"nbEntreeBonParPanier":nbEntreeBonParPanier}); //load initial records
                    });
                /*************** Fin lister bons par panier ***************/

                /*************** Début lister bons par produit ***************/
                    $("#bonsProduitEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".btn_Impression").hide();
                        $(".loading-gif").show();

                        nbEntreeBonParProduit = $(".nbEntreeBonParProduit").val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                            $(".btn_Impression").show();
                        });

                    });

                    $(".listeDesBonsParProduit").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link

                        nbEntreeBonParProduit = $(".nbEntreeBonParProduit").val();
                        query = $('.searchInputBonParProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $(".listeDesBonsParProduit").load("ajax/listerRapportBonAjax.php",{"page":page,"operation":1}
                    });

                    $('.searchInputBonParProduit').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('.searchInputBonParProduit').val()
                        nbEntreeBonParProduit = $(".nbEntreeBonParProduit").val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":query,"cb":t}); //load initial records
                            }else{
                                $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":query,"cb":t}); //load initial records
                                }else{
                                    $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $(".nbEntreeBonParProduit").on("change", function(e) {
                        e.preventDefault();

                        nbEntreeBonParProduit = $(".nbEntreeBonParProduit").val()
                        query = $('.searchInputBonParProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeBonParProduit":nbEntreeBonParProduit,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $(".listeDesBonsParProduit" ).load( "ajax/listerRapportBonAjax.php",{"operation":4,"nbEntreeBonParProduit":nbEntreeBonParProduit}); //load initial records
                    });
                /*************** Fin lister bons par produit ***************/

                /*************** Début lister clients bons par produit ***************/
                    $("#clientsEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeCliBonParProduit = $(".nbEntreeCliBonParProduit").val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $(".listeDesClientsBonsParProduit").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link

                        nbEntreeCliBonParProduit = $(".nbEntreeCliBonParProduit").val();
                        query = $('.searchInputCliBonProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $(".listeDesClientsBonsParProduit").load("ajax/listerRapportClientsBonsProduitsAjax.php",{"page":page,"operation":1}
                    });

                    $('.searchInputCliBonProduit').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('.searchInputCliBonProduit').val()
                        nbEntreeCliBonParProduit = $(".nbEntreeCliBonParProduit").val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":query,"cb":t}); //load initial records
                            }else{
                                $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":query,"cb":t}); //load initial records
                                }else{
                                    $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $(".nbEntreeCliBonParProduit").on("change", function(e) {
                        e.preventDefault();

                        nbEntreeCliBonParProduit = $(".nbEntreeCliBonParProduit").val()
                        query = $('.searchInputCliBonProduit').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $(".listeDesClientsBonsParProduit" ).load( "ajax/listerRapportClientsBonsProduitsAjax.php",{"operation":4,"nbEntreeCliBonParProduit":nbEntreeCliBonParProduit}); //load initial records
                    });
                /*************** Fin lister clients bons par produit ***************/

                /*************** Début lister produits retires ***************/
                    $("#sortiesRetiresEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeRetireProd = $('#nbEntreeRetireProd').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeRetireProd":nbEntreeRetireProd,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesProduitsRetires").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        nbEntreeRetireProd = $('#nbEntreeRetireProd').val();
                        query = $('#searchInputRetireProd').val();

                        if (query.length == 0) {
                            $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeRetireProd":nbEntreeRetireProd,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeRetireProd":nbEntreeRetireProd,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesProduitsRetires").load("ajax/listerRapportRetiresAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputRetireProd').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputRetireProd').val()
                        nbEntreeRetireProd = $('#nbEntreeRetireProd').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRetireProd":nbEntreeRetireProd,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRetireProd":nbEntreeRetireProd,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRetireProd":nbEntreeRetireProd,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRetireProd":nbEntreeRetireProd,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeRetireProd').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeRetireProd = $('#nbEntreeRetireProd').val()
                        query = $('#searchInputRetireProd').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeRetireProd":nbEntreeRetireProd,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeRetireProd":nbEntreeRetireProd,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesProduitsRetires" ).load( "ajax/listerRapportRetiresAjax.php",{"operation":4,"nbEntreeRetireProd":nbEntreeRetireProd}); //load initial records
                    });
                /*************** Fin lister produits retires ***************/

                /*************** Début lister produits retournés ***************/
                    $("#sortiesExpiresEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeRetourneProd = $('#nbEntreeRetourneProd').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesProduitsRetournes").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link

                        nbEntreeRetourneProd = $('#nbEntreeRetourneProd').val();
                        query = $('#searchInputRetourneProd').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesProduitsRetournes").load("ajax/listerRapportExpiresAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputRetourneProd').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputRetourneProd').val()
                        nbEntreeRetourneProd = $('#nbEntreeRetourneProd').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeRetourneProd').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeRetourneProd = $('#nbEntreeRetourneProd').val()
                        query = $('#searchInputRetourneProd').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeRetourneProd":nbEntreeRetourneProd,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesProduitsRetournes" ).load( "ajax/listerRapportExpiresAjax.php",{"operation":4,"nbEntreeRetourneProd":nbEntreeRetourneProd}); //load initial records
                    });
                /*************** Fin lister produits retournés ***************/

                /*************** Début lister bons en espèces ***************/
                    $("#bonEnEspeceEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeBonEnEspece = $('#nbEntreeBonEnEspece').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesBonsEnEspece").on( "click", ".pagination a", function (e){

                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link

                        nbEntreeBonEnEspece = $('#nbEntreeBonEnEspece').val();
                        query = $('#searchInputBonEnEspece').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesBonsEnEspece").load("ajax/listerRapportClientsBonsAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputBonEnEspece').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputBonEnEspece').val()
                        nbEntreeBonEnEspece = $('#nbEntreeBonEnEspece').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeBonEnEspece').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeBonEnEspece = $('#nbEntreeBonEnEspece').val()
                        query = $('#searchInputBonEnEspece').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeBonEnEspece":nbEntreeBonEnEspece,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesBonsEnEspece" ).load( "ajax/listerRapportClientsBonsAjax.php",{"operation":4,"nbEntreeBonEnEspece":nbEntreeBonEnEspece}); //load initial records
                    });
                /*************** Fin lister bons en espèces ***************/

                /*************** Début lister versements ***************/
                    $("#versementsClientEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeVersement = $('#nbEntreeVersement').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeVersement":nbEntreeVersement,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesVersements").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeVersement = $('#nbEntreeVersement').val();
                        query = $('#searchInputVersement').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeVersement":nbEntreeVersement,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeVersement":nbEntreeVersement,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesVersements").load("ajax/listerRapportClientsVersementsAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputVersement').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputVersement').val()
                        nbEntreeVersement = $('#nbEntreeVersement').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeVersement":nbEntreeVersement,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeVersement":nbEntreeVersement,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeVersement":nbEntreeVersement,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeVersement":nbEntreeVersement,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeVersement').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeVersement = $('#nbEntreeVersement').val()
                        query = $('#searchInputVersement').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeVersement":nbEntreeVersement,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeVersement":nbEntreeVersement,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesVersements" ).load( "ajax/listerRapportClientsVersementsAjax.php",{"operation":4,"nbEntreeVersement":nbEntreeVersement}); //load initial records
                    });
                /*************** Fin lister versements ***************/

                /*************** Début lister bons de livraison ***************/
                    $("#fournisseursEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeBL = $('#nbEntreeBL').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeBL":nbEntreeBL,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesBonsLivraison").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeBL = $('#nbEntreeBL').val();
                        query = $('#searchInputBL').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeBL":nbEntreeBL,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeBL":nbEntreeBL,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesBonsLivraison").load("ajax/listerRapportFournisseursBonsAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputBL').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputBL').val()
                        nbEntreeBL = $('#nbEntreeBL').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();                                    

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBL":nbEntreeBL,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBL":nbEntreeBL,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBL":nbEntreeBL,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeBL":nbEntreeBL,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeBL').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeBL = $('#nbEntreeBL').val()
                        query = $('#searchInputBL').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeBL":nbEntreeBL,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeBL":nbEntreeBL,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesBonsLivraison" ).load( "ajax/listerRapportFournisseursBonsAjax.php",{"operation":4,"nbEntreeBL":nbEntreeBL}); //load initial records
                    });
                /*************** Fin lister bons de livraison ***************/

                /*************** Début lister fournisseur versement ***************/
                    $("#versementsFourniEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeVF = $('#nbEntreeVF').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeVF":nbEntreeVF,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesVersementsF").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link

                        nbEntreeVF = $('#nbEntreeVF').val();
                        query = $('#searchInputVF').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeVF":nbEntreeVF,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeVF":nbEntreeVF,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesVersementsF").load("ajax/listerRapportFournisseursVersementsAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputVF').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputVF').val()
                        nbEntreeVF = $('#nbEntreeVF').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeVF":nbEntreeVF,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeVF":nbEntreeVF,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeVF":nbEntreeVF,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeVF":nbEntreeVF,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeVF').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeVF = $('#nbEntreeVF').val()
                        query = $('#searchInputVF').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeVF":nbEntreeVF,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeVF":nbEntreeVF,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesVersementsF" ).load( "ajax/listerRapportFournisseursVersementsAjax.php",{"operation":4,"nbEntreeVF":nbEntreeVF}); //load initial records
                    });
                /*************** Fin lister fournisseur versement ***************/

                /*************** Début lister services  ***************/
                    $("#servicesEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeService = $('#nbEntreeService').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeService":nbEntreeService,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesServices").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link

                        nbEntreeService = $('#nbEntreeService').val();
                        query = $('#searchInputService').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeService":nbEntreeService,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeService":nbEntreeService,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesServices").load("ajax/listerRapportServicesAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputService').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputService').val()
                        nbEntreeService = $('#nbEntreeService').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeService":nbEntreeService,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeService":nbEntreeService,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeService":nbEntreeService,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeService":nbEntreeService,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeService').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeService = $('#nbEntreeService').val()
                        query = $('#searchInputService').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeService":nbEntreeService,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeService":nbEntreeService,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesServices" ).load( "ajax/listerRapportServicesAjax.php",{"operation":4,"nbEntreeService":nbEntreeService}); //load initial records
                    });
                /*************** Fin lister services ***************/

                /*************** Début lister remises ***************/
                    $("#remisesEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeRemiseVente = $('#nbEntreeRemiseVente').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesRemisesVente").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeRemiseVente = $('#nbEntreeRemiseVente').val();
                        query = $('#searchInputRemiseVente').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesRemisesVente").load("ajax/listerRapportRemisesVenteAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputRemiseVente').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputRemiseVente').val()
                        nbEntreeRemiseVente = $('#nbEntreeRemiseVente').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeRemiseVente').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeRemiseVente = $('#nbEntreeRemiseVente').val()
                        query = $('#searchInputRemiseVente').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeRemiseVente":nbEntreeRemiseVente,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesRemisesVente" ).load( "ajax/listerRapportRemisesVenteAjax.php",{"operation":4,"nbEntreeRemiseVente":nbEntreeRemiseVente}); //load initial records
                    });
                /*************** Fin lister remises ***************/

                /*************** Début lister Remises clients ***************/
                    $("#remiseClientEvent").on( "click", function (e){
                    
                        e.preventDefault();
                        $(".loading-gif").show();

                        listeDesRemisesClient = $('#listeDesRemisesClient').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"listeDesRemisesClient":listeDesRemisesClient,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesRemisesClient").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        listeDesRemisesClient = $('#listeDesRemisesClient').val();
                        query = $('#searchInputRemiseClient').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"listeDesRemisesClient":listeDesRemisesClient,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"listeDesRemisesClient":listeDesRemisesClient,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesRemisesClient").load("ajax/listerRapportRemisesClientAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputRemiseClient').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputRemiseClient').val()
                        listeDesRemisesClient = $('#listeDesRemisesClient').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();                                    

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"listeDesRemisesClient":listeDesRemisesClient,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"listeDesRemisesClient":listeDesRemisesClient,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"listeDesRemisesClient":listeDesRemisesClient,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"listeDesRemisesClient":listeDesRemisesClient,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#listeDesRemisesClient').on("change", function(e) {
                        e.preventDefault();

                        listeDesRemisesClient = $('#listeDesRemisesClient').val()
                        query = $('#searchInputRemiseClient').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"listeDesRemisesClient":listeDesRemisesClient,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"listeDesRemisesClient":listeDesRemisesClient,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesRemisesClient" ).load( "ajax/listerRapportRemisesClientAjax.php",{"operation":4,"listeDesRemisesClient":listeDesRemisesClient}); //load initial records
                    });
                /*************** Fin lister Remises clients ***************/
                    
                /*************** Début lister Mutuelles ***************/
                    $("#mutuellesEvent").on( "click", function (e){

                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeMutuelle = $('#nbEntreeMutuelle').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbEntreeMutuelle":nbEntreeMutuelle,"query":"","cb":""}, function(data){ //get content from PHP page
                            // alert(data)
                            $(".loading-gif").hide(); //once done, hide loading element
                        });
                    });

                    $("#listeDesMutuelles").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeMutuelle = $('#nbEntreeMutuelle').val()
                        query = $('#searchInputMutuelle').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeMutuelle":nbEntreeMutuelle,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeMutuelle":nbEntreeMutuelle,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesMutuelles").load("ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                    });

                    $('#searchInputMutuelle').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputMutuelle').val()
                        nbEntreeMutuelle = $('#nbEntreeMutuelle').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbEntreeMutuelle":nbEntreeMutuelle,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbEntreeMutuelle":nbEntreeMutuelle,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbEntreeMutuelle":nbEntreeMutuelle,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbEntreeMutuelle":nbEntreeMutuelle,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeMutuelle').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeMutuelle = $('#nbEntreeMutuelle').val()
                        query = $('#searchInputMutuelle').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeMutuelle":nbEntreeMutuelle,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeMutuelle":nbEntreeMutuelle,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesMutuelles" ).load( "ajax/listerRapportMutuellesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeMutuelle":nbEntreeMutuelle}); //load initial records
                    });
                /*************** Fin lister Mutuelles ***************/
                
                /*************** Début lister retraitC  ***************/
                    $("#retraitCEvent").on( "click", function (e){

                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeRC = $('#nbEntreeRC').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeRC":nbEntreeRC,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesRetraitC").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeRC = $('#nbEntreeRC').val();
                        query = $('#searchInputRC').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeRC":nbEntreeRC,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeRC":nbEntreeRC,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesRetraitC").load("ajax/listerRapportRetraitCaisseAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputRC').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputRC').val()
                        nbEntreeRC = $('#nbEntreeRC').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRC":nbEntreeRC,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRC":nbEntreeRC,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRC":nbEntreeRC,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeRC":nbEntreeRC,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeRC').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeRC = $('#nbEntreeRC').val()
                        query = $('#searchInputRC').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeRC":nbEntreeRC,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeRC":nbEntreeRC,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesRetraitC" ).load( "ajax/listerRapportRetraitCaisseAjax.php",{"operation":4,"nbEntreeRC":nbEntreeRC}); //load initial records
                    });
                /*************** Fin lister retraitC ***************/

                /*************** Début lister appro  ***************/
                    $("#approEvent").on( "click", function (e){

                        e.preventDefault();
                        $(".loading-gif").show();

                        nbEntreeAppro = $('#nbEntreeAppro').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":1,"nbEntreeAppro":nbEntreeAppro,"query":"","cb":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });

                    });

                    $("#listeDesApproC").on( "click", ".pagination a", function (e){
                        e.preventDefault();
                        $(".loading-gif").show(); //show loading element
                        // page = page+1; //get page number from link
                        page = $(this).attr("data-page"); //get page number from link
                        //  alert(page)

                        nbEntreeAppro = $('#nbEntreeAppro').val();
                        query = $('#searchInputAppro').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin ,{"page":page,"operation":1,"nbEntreeAppro":nbEntreeAppro,"query":"","cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                                
                        }else{
                            $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbEntreeAppro":nbEntreeAppro,"query":query,"cb":""}, function(){ //get content from PHP page
                                $(".loading-gif").hide(); //once done, hide loading element
                            });
                        }
                        // $("#listeDesApproC").load("ajax/listerRapportApproCaisseAjax.php",{"page":page,"operation":1}
                    });

                    $('#searchInputAppro').on("keyup", function(e) {
                        e.preventDefault();
                        
                        query = $('#searchInputAppro').val()
                        nbEntreeAppro = $('#nbEntreeAppro').val()
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        var keycode = (e.keyCode ? e.keyCode : e.which);
                        if (keycode == '13') {
                            // alert(1111)
                            t = 1; // code barre
                            
                            if (query.length > 0) {
                                
                                $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeAppro":nbEntreeAppro,"query":query,"cb":t}); //load initial records
                            }else{
                                $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeAppro":nbEntreeAppro,"query":"","cb":t}); //load initial records
                            }
                        }else{
                            // alert(2222)
                            t = 0; // no code barre
                            setTimeout(() => {
                                if (query.length > 0) {
                                    
                                    $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeAppro":nbEntreeAppro,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":3,"nbEntreeAppro":nbEntreeAppro,"query":"","cb":t}); //load initial records
                                }
                            }, 100);
                        }
                    });

                    $('#nbEntreeAppro').on("change", function(e) {
                        e.preventDefault();

                        nbEntreeAppro = $('#nbEntreeAppro').val()
                        query = $('#searchInputAppro').val();
                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();

                        if (query.length == 0) {
                            $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeAppro":nbEntreeAppro,"query":"","cb":""}); //load initial records
                                
                        }else{
                            $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php?debut="+debut+"&fin="+fin ,{"operation":4,"nbEntreeAppro":nbEntreeAppro,"query":query,"cb":""}); //load initial records
                        }
                            
                        // $("#listeDesApproC" ).load( "ajax/listerRapportApproCaisseAjax.php",{"operation":4,"nbEntreeAppro":nbEntreeAppro}); //load initial records
                    });
                /*************** Fin lister appro ***************/
                                        

            });

            function sortiesTarget() {
                // alert(1)
                debut=$('#input_dateDebut').val();
                fin=$('#input_dateFin').val();
                url = "sortiesTargetRapportQty.php?s="+debut+"&e="+fin;

                window.open(url);
            }

            function showImageVersement(idVersement) {
                $('#idVersement_View').text(idVersement);
                $('#idVersement_Upd_Nv').val(idVersement);
                $('#input_file_Versement').val('');
                $('#imageNvVersement').modal('show');
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
            function showImageBl(idBl) {
                var numero = $('#numeroBl'+idBl).val();
                $('#numeroBl_View').text(numero);
                $('#idBl_Upd_Nv').val(idBl);
                $('#input_file_Bl').val('');
                $('#imageNvBl').modal('show');
                var file = $('#imageBl'+idBl).val();
                if(file!=null && file!=''){
                    var format = file.substr(file.length - 3);
                    if(format=='pdf'){
                        document.getElementById('output_pdf_Bl').style.display = "block";
                        document.getElementById('output_image_Bl').style.display = "none";
                        document.getElementById("output_pdf_Bl").src="./PiecesJointes/"+file;
                    }
                    else{
                        document.getElementById('output_image_Bl').style.display = "block";
                        document.getElementById('output_pdf_Bl').style.display = "none";
                        document.getElementById("output_image_Bl").src="./PiecesJointes/"+file;
                    }
                }
                else{
                    document.getElementById('output_pdf_Bl').style.display = "none";
                    document.getElementById('output_image_Bl').style.display = "none";
                }
            }
            function showPreviewBl(event) {
                var file = document.getElementById('input_file_Bl').value;
                var reader = new FileReader();
                reader.onload = function()
                {
                    var format = file.substr(file.length - 3);
                    var pdf = document.getElementById('output_pdf_Bl');
                    var image = document.getElementById('output_image_Bl');
                    if(format=='pdf'){
                        document.getElementById('output_pdf_Bl').style.display = "block";
                        document.getElementById('output_image_Bl').style.display = "none";
                        pdf.src = reader.result;
                    }
                    else{
                        document.getElementById('output_image_Bl').style.display = "block";
                        document.getElementById('output_pdf_Bl').style.display = "none";
                        image.src = reader.result;
                    }
                }
                reader.readAsDataURL(event.target.files[0]);
                document.getElementById('btn_upload_Bl').style.display = "block";
            }
            function showImagePanier(idPagnet) {
                $('#idPanier_Upd_Nv').val(idPagnet);
                $('#input_file_Panier').val('');
                $('#imageNvPanier').modal('show');
                var file = $('#imagePanier'+idPagnet).val();
                if(file!=null && file!=''){
                    var format = file.substr(file.length - 3);
                    if(format=='pdf'){
                        document.getElementById('output_pdf_Panier').style.display = "block";
                        document.getElementById('output_image_Panier').style.display = "none";
                        document.getElementById("output_pdf_Panier").src="./PiecesJointes/"+file;
                    }
                    else{
                        document.getElementById('output_image_Panier').style.display = "block";
                        document.getElementById('output_pdf_Panier').style.display = "none";
                        document.getElementById("output_image_Panier").src="./PiecesJointes/"+file;
                    }
                }
                else{
                    document.getElementById('output_pdf_Panier').style.display = "none";
                    document.getElementById('output_image_Panier').style.display = "none";
                }
            }
            function showPreviewPanier(event) {
                var file = document.getElementById('input_file_Panier').value;
                var reader = new FileReader();
                reader.onload = function()
                {
                    var format = file.substr(file.length - 3);
                    var pdf = document.getElementById('output_pdf_Panier');
                    var image = document.getElementById('output_image_Panier');
                    if(format=='pdf'){
                        document.getElementById('output_pdf_Panier').style.display = "block";
                        document.getElementById('output_image_Panier').style.display = "none";
                        pdf.src = reader.result;
                    }
                    else{
                        document.getElementById('output_image_Panier').style.display = "block";
                        document.getElementById('output_pdf_Panier').style.display = "none";
                        image.src = reader.result;
                    }
                }
                reader.readAsDataURL(event.target.files[0]);
                document.getElementById('btn_upload_Panier').style.display = "block";
            }
            function showImageBon(idPagnet) {
                $('#idBon_View').text(idPagnet);
                $('#idBon_Upd_Nv').val(idPagnet);
                $('#input_file_Bon').val('');
                $('#imageNvBon').modal('show');
                var file = $('#imageBon'+idPagnet).val();
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

            $(function() {
                $("#col").on( "click", function() {
                    $e = $('#expand').attr('class');

                    if($e == "glyphicon glyphicon-chevron-down") {
                        $('#expand').attr("class","glyphicon glyphicon-chevron-up");
                    } else {
                        $('#expand').attr("class","glyphicon glyphicon-chevron-down");
                    }
                });
            });
        </script>


        <table >
                <tr>
                    <td>
                        <div aria-label="navigation">
                            <ul class="pager">
                                <li>
                                <input type="text" id="reportrange" />
                                </li>
                            </ul>
                        </div>
                        <div class="container">

                        </div>
                    </td>
                </tr>
        </table>

        <table>
            <tr>
                <td>
                    <div class="container">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading" id="col" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                  <h3 class="panel-title" id="reportrange"><a href="#"> ETAT RAPPORT POUR LA PERIODE DU <span id="id_dateDebut2" ></span>  AU  <span id="id_dateFin2"></span><span class="glyphicon glyphicon-chevron-up" id="expand" style="float:right;"></span> </a></h3>
                                        <!-- <h3 class="panel-title" id="reportrange">ETAT RAPPORT POUR LA PERIODE DU <?php echo $dateString2; ?>   AU   <?php echo $dateString2; ?> </h3> -->
                                </div>
                                <div style="display:none">
                                    <input id="input_dateDebut" />
                                    <input id="input_dateFin" />
                                </div>
                                <div class="panel-body collapse in" id="collapseOne" aria-labelledby="headingOne">
                                        <table class="table table-bordered table-responsive ">
                                                <tr class="info">
                                                    <td><b>ENTREES EN ARGENT :</b></td>
                                                    <td><div><b><span id="idRapport_EntreeArgent"></span> <?php echo $_SESSION['symbole']; ?></span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Approvisionnement caisse :</td>
                                                    <td><div><span id="idRapport_ApproCaisse"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Versements clients :</td>
                                                    <td><div><span id="idRapport_VersementsClient"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Ventes :</td>

                                                    <td><div><span id="idRapport_Vente"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                    
                                                    <?php
                                                        
                                                        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
                                                    
                                                    ?>
                                                    <tr>

                                                        <td>Ventes Imputation:</td>

                                                        <td><div><span id="idRapport_VenteImputation"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                    </tr>
                                                    
                                                    <?php
                                                        } 
                                                    
                                                    ?>
                                                <tr>
                                                    <td>Services :</td>
                                                    <td><div><span id="idRapport_Services"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Produits retournés :</td>
                                                    <td><div><span id="idRapport_ProdduitsRetournes"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr class="info">
                                                    <td><b>SORTIES EN ARGENT :</b></td>
                                                    <td><div><b><span id="idRapport_SortiesArgent"></span> <?php echo $_SESSION['symbole']; ?></span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Retrait caisse :</td>
                                                    <td><div><span id="idRapport_RetraitCaisse"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Dépenses :</td>
                                                    <td><div><span id="idRapport_Depenses"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Versements fournisseurs :</td>
                                                    <td><div><span id="idRapport_FournisseursVersements"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Bons en espèces :</td>
                                                    <td><div><span id="idRapport_BonsEspeces"></span><?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Remises Ventes :</td>
                                                    <td><div><span id="idRapport_RemisesVente"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr class="warning">
                                                    <td><b>RESTANT CAISSE  :</b></td>
                                                    <td><div><b><span id="idRapport_RestantCaisse"></span> <?php echo $_SESSION['symbole']; ?></span></b></div></td>
                                                </tr>

                                                <tr class="info">
                                                    <td><b>MONTANT A RECOUVRIR :</b></td>
                                                    <td><div><b><span id="idRapport_Recouvrement"></span> <?php echo $_SESSION['symbole']; ?></span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Bons (en produits + en epèces) non payés :</td>
                                                    <td><div><span id="idRapport_RecouvrementClients"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                    <!-- <td><div><span id="id_SortiesBon3"></span><?php echo $_SESSION['symbole']; ?></span></div></td> -->
                                                </tr>
                                                <?php
                                                
                                                    if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
                                                
                                                ?>

                                                <tr>
                                                    <td>Bons Mutuelles :</td>
                                                    <td><div><span id="idRapport_Mutuelle"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                
                                                <?php
                                                
                                                    }                                                
                                                ?>
                                                <!-- <tr>
                                                    <td>Bons en epèce non payés :</td>
                                                    <td><div><span id="id_RapportBonsEspeces4"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr> -->
                                                <tr class="info">
                                                    <td><b>VALEUR STOCKS COURANTS :</b></td>
                                                    <td><div><b><span id="idRapport_ValeurStockCourant"></span></span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Valeurs stock (PA) :</td>
                                                    <td><div><span id="idRapport_ValeursEntreesPA"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Valeurs stock (PU) :</td>
                                                    <td><div><span id="idRapport_ValeursEntreesPU"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr class="info">
                                                    <td><b>PATRIMOINE DE L'ENTREPRISE :</b></td>
                                                    <td><div><b><span id="idRapport_Patrimoine"></span> <?php echo $_SESSION['symbole']; ?></span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Valeurs stock (PU) :</td>
                                                    <td><div><span id="idRapport_PatrimoineEntreesPU"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Montant à recouvrir :</td>
                                                    <td><div><span id="idRapport_PatrimoineRecouvrement"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Restant caisse :</td>
                                                    <td><div><span id="idRapport_PatrimoineRestantCaisse"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr class="danger">
                                                    <td>BL non payés :</td>
                                                    <td><div><span id="idRapport_PatrimoineBl_non_paye"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr class="danger">
                                                    <td>Porduits retirés :</td>
                                                    <td><div><span id="idRapport_PatrimoineProduitsRetires"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr class="danger">
                                                    <td>Remises Clients:</td>
                                                    <td><div><span id="idRapport_RemisesClient"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                                <tr class="success">
                                                    <td>Benefices :</td>
                                                    <td><div><span id="idRapport_Benefice"></span> <?php echo $_SESSION['symbole']; ?></span></div></td>
                                                </tr>
                                        </table>

                                    <form class="form-inline pull-right noImpr"  target="_blank" style="margin-right:20px;"
                                      method="post" action="pdfRapportDetails.php" >
                                      <input type="hidden" name="dateImp" id="idDateImp"  <?php echo  "value=".$dateString2."" ; ?> >
                                      <input type="hidden" name="debutAnnee" id="debutAnnee" class="debutAnnee"  value="">
                                      <input type="hidden" name="finAnnee" id="finAnnee" class="finAnnee"  value="" >
                                      <button disabled="true" type="submit" class="btn btn-info  pull-right" style="margin-right:20px;" >
                                        <span class="glyphicon glyphicon-download-alt"></span> RAPPORT DETAILE
                                      </button>
                                    </form>
                                    <form class="form-inline pull-right noImpr"  target="_blank" style="margin-right:20px;"
                                        method="post" action="pdfRapportSynthese.php" >
                                        <input type="hidden" name="dateImp" id="idDateImp"  <?php echo  "value=".$dateString2."" ; ?> >
                                        <input type="hidden" name="debutAnnee" id="debutAnnee" class="debutAnnee"  value="">
                                        <input type="hidden" name="finAnnee" id="finAnnee" class="finAnnee"  value="" >
                                        <button type="submit" class="btn btn-success  pull-right" style="margin-right:20px;" >
                                            <span class="glyphicon glyphicon-download-alt"></span> RAPPORT GLOBAL
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- /**********************************************/ -->

        <div class="row container" <?= ($_SESSION["btnRapportConfig"]==0) ? 'hidden' : '' ; ?>>
            <button class="btn btn-default" id="sortiesTarget" onClick="sortiesTarget()">Sorties</button>
        </div>

        <!-- /**********************************************/ -->

        <!-- Debut Container Details Journal -->
            <div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabbable-panel">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs">
                                    <li class="active" id="depensesEvent">
                                        <a href="#tab_default_1" data-toggle="tab">
                                            Depenses
                                        </a>
                                    </li>
                                    <li id="entreesEvent">
                                        <a href="#tab_default_2" data-toggle="tab">
                                            Entrées
                                        </a>
                                    </li>
                                    <li id="sortiesEvent">
                                        <a href="#tab_default_3" data-toggle="tab">
                                            Sorties 
                                        </a>
                                    </li>
                                    <li id="clientsEvent">
                                        <a href="#tab_default_4" data-toggle="tab">
                                            Clients 
                                        </a>
                                    </li>
                                    <li id="fournisseursEvent">
                                        <a href="#tab_default_5" data-toggle="tab">
                                            Fournisseurs 
                                        </a>
                                    </li>
                                    <li id="servicesEvent">
                                        <a href="#tab_default_6" data-toggle="tab">
                                            Services 
                                        </a>
                                    </li>
                                    <li id="remisesEvent">
                                        <a href="#tab_default_7" data-toggle="tab">
                                            Remises 
                                        </a>
                                    </li>
                                    <li id="retraitCEvent">
                                        <a href="#tab_default_9" data-toggle="tab">
                                            Retrait caisse 
                                        </a>
                                    </li>
                                    <li id="approEvent">
                                        <a href="#tab_default_10" data-toggle="tab">
                                            Approvisionnement caisse 
                                        </a>
                                    </li>
                                    <?php
                                    
                                        if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
                                    
                                    ?>
                                            <li id="mutuellesEvent">
                                                <a href="#tab_default_8" data-toggle="tab">
                                                    Mutuelles 
                                                </a>
                                            </li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                                <div class="tab-content" id="container_Result">
                                    <div class="tab-pane active" id="tab_default_1">       
                                        <ul class="nav">
                                            <li class="active">
                                                <a> LISTE DES DEPENSES :
                                                    <span style="color:green;">Valeur Depenses =  <span id="idTab_Depenses"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                </a>
                                            </li>
                                        </ul> 
                                        <div class="table-responsive">
                                            <label class="pull-left" for="nbEntreeDepense">Nombre entrées </label>
                                            <select class="pull-left" id="nbEntreeDepense">
                                            <optgroup>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option> 
                                            </optgroup>       
                                            </select>
                                            <input class="pull-right" type="text" name="" id="searchInputDepense" placeholder="Rechercher..." autocomplete="off">
                                            <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                            <div id="listeDesDepenses"><!-- content will be loaded here --></div>	
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_default_2">
                                        <div class="tabbable-line">
                                            <ul class="nav nav-tabs ">
                                                <li class="active entreesStockEvent">
                                                    <a href="#tab_default_21" data-toggle="tab">
                                                        Par Stock
                                                    </a>
                                                </li>
                                                <li class="entreesProduitEvent" id="entreesProduitEvent">
                                                    <a href="#tab_default_22" data-toggle="tab">
                                                        Par Produits
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_default_21">
                                                    <ul class="nav">
                                                        <a> LISTE DES ENTREES PAR STOCK :
                                                            <span style="color:green;"> Valeur Stock (PA) = <span class="idTab_EntreesPA"></span> <?php echo $_SESSION['symbole']; ?></span> <=>
                                                            <span style="color:green;">Valeur Stock (PU) =  <span class="idTab_EntreesPU"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                        </a>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeParStock">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeParStock">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputParStock" placeholder="Rechercher..." autocomplete="off">
                                                        
                                                        <input type="hidden" id="debut" value="">
                                                        <input type="hidden" id="fin" value="">
													<img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        <div id="listeDesEntreesParStock"><!-- content will be loaded here --></div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_default_22">
                                                    <ul class="nav">
                                                        <li class="active">
                                                            <a> LISTE DES ENTREES PAR PRODUIT :
                                                                <span style="color:green;"> Valeur Stock (PA) = <span class="idTab_EntreesPA"></span> <?php echo $_SESSION['symbole']; ?></span> <=>
                                                                <span style="color:green;"> Valeur Stock (PU) = <span class="idTab_EntreesPU"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeParProduit">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeParProduit">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputParProduit" placeholder="Rechercher..." autocomplete="off">
                                                        
                                                        <input type="hidden" id="debut" value="">
                                                        <input type="hidden" id="fin" value="">
													<img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        <div id="listeDesEntreesParProduit"><!-- content will be loaded here --></div>	 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_default_3">
                                        <div class="tabbable-line">
                                            <ul class="nav nav-tabs ">
                                                <li class="active sortiesVentesEvent">
                                                    <a href="#tab_default_31" data-toggle="tab">
                                                        Ventes
                                                    </a>
                                                </li>
                                                <li class="sortiesBonsEvent" id="sortiesBonsEvent">
                                                    <a href="#tab_default_32" data-toggle="tab">
                                                        Bons
                                                    </a>
                                                </li>
                                                <li class="sortiesRetiresEvent" id="sortiesRetiresEvent">
                                                    <a href="#tab_default_34" data-toggle="tab">
                                                        Retirés
                                                    </a>
                                                </li>
                                                <li class="sortiesExpiresEvent" id="sortiesExpiresEvent">
                                                    <a href="#tab_default_35" data-toggle="tab">
                                                        Retournés
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_default_31">
                                                    <div class="tabbable-line">
                                                        <ul class="nav nav-tabs ">
                                                            <li class="active ventesPanierEvent">
                                                                <a href="#tab_default_31A" data-toggle="tab">
                                                                    Par Paniers
                                                                </a>
                                                            </li>
                                                            <li class="ventesProduitEvent" id="ventesProduitEvent">
                                                                <a href="#tab_default_31B" data-toggle="tab">
                                                                    Par Produits
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="tab_default_31A">
                                                                <ul class="nav">
                                                                    <li>
                                                                        <a> LISTE DES SORTIES EN VENTE PAR PANIER:
                                                                            <span style="color:green;">Valeur Vente =  <span class="idTab_Ventes"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                                        </a>
                                                                    </li>
                                                                </ul>       
                                                                <div class="table-responsive">
                                                                    <label class="pull-left" for="nbEntreeSVenteParPagnet">Nombre entrées </label>
                                                                    <select class="pull-left" id="nbEntreeSVenteParPagnet">
                                                                    <optgroup>
                                                                        <option value="10">10</option>
                                                                        <option value="20">20</option>
                                                                        <option value="50">50</option> 
                                                                    </optgroup>       
                                                                    </select>
                                                                    <input class="pull-right" type="text" name="" id="searchInputSVenteParPagnet" placeholder="Rechercher..." autocomplete="off">
                                                                    
                                                                    <input type="hidden" id="debut" value="">
                                                                    <input type="hidden" id="fin" value="">
													                <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                                    <div id="listeDesSortiesVParPagnet"><!-- content will be loaded here --></div>	
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="tab_default_31B">
                                                                <ul class="nav">
                                                                    <li class="active">
                                                                        <a> LISTE DES SORTIES EN VENTE PAR PRODUIT:
                                                                            <span style="color:green;">Valeur Vente =  <span class="idTab_Ventes"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                                        </a>
                                                                    </li>
                                                                </ul>       
                                                                <div class="table-responsive">
                                                                    <label class="pull-left" for="nbEntreeSVenteParProduit">Nombre entrées </label>
                                                                    <select class="pull-left" id="nbEntreeSVenteParProduit">
                                                                    <optgroup>
                                                                        <option value="10">10</option>
                                                                        <option value="20">20</option>
                                                                        <option value="50">50</option> 
                                                                    </optgroup>       
                                                                    </select>
                                                                    <input class="pull-right" type="text" name="" id="searchInputSVenteParProduit" placeholder="Rechercher..." autocomplete="off">
                                                                    
                                                                    <input type="hidden" id="debut" value="">
                                                                    <input type="hidden" id="fin" value="">
                                                                    <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                                    <div id="listeDesSortiesVParProduit"><!-- content will be loaded here --></div>	
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="tab-pane" id="tab_default_32">
                                                    <div class="tabbable-line">
                                                        <ul class="nav nav-tabs ">
                                                            <li class="active bonsPanierEvent">
                                                                <a href="#tab_default_32A" data-toggle="tab">
                                                                    Par Paniers
                                                                </a>
                                                            </li>
                                                            <li class="bonsProduitEvent" id="bonsProduitEvent">
                                                                <a href="#tab_default_32B" data-toggle="tab">
                                                                    Par Produits
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="tab_default_32A">
                                                                <ul class="nav">
                                                                    <li>
                                                                        <a> LISTE DES SORTIES EN BON PAR PANIER :
                                                                            <span style="color:green;">Valeur Bon =  <span class="idTab_Bons"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                                        </a>
                                                                    </li>
                                                                </ul>       
                                                                <div class="table-responsive">
                                                                    <label class="pull-left" for="nbEntreeBonParPanier">Nombre entrées </label>
                                                                    <select class="pull-left" id="nbEntreeBonParPanier">
                                                                    <optgroup>
                                                                        <option value="10">10</option>
                                                                        <option value="20">20</option>
                                                                        <option value="50">50</option> 
                                                                    </optgroup>       
                                                                    </select>
                                                                    <input class="pull-right" type="text" name="" id="searchInputBonParPanier" placeholder="Rechercher..." autocomplete="off">
                                                                    
                                                                    <input type="hidden" id="debut" value="">
                                                                    <input type="hidden" id="fin" value="">
													                <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                                    <div id="listeDesBonsParPanier"><!-- content will be loaded here --></div>	
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="tab_default_32B">
                                                                <ul class="nav">
                                                                    <li class="active">
                                                                        <a> LISTE DES SORTIES EN VENTE PAR PRODUIT:
                                                                            <span style="color:green;">Valeur Bon =  <span class="idTab_Bons"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                                        </a>
                                                                    </li>
                                                                </ul>       
                                                                <div class="table-responsive">
                                                                    <label class="pull-left" for="nbEntreeBonParProduit">Nombre entrées </label>
                                                                    <select class="pull-left nbEntreeBonParProduit">
                                                                    <optgroup>
                                                                        <option value="10">10</option>
                                                                        <option value="20">20</option>
                                                                        <option value="50">50</option> 
                                                                    </optgroup>       
                                                                    </select>
                                                                    <input class="pull-right searchInputBonParProduit" type="text" name=""  placeholder="Rechercher..." autocomplete="off">
                                                                    
                                                                    <input type="hidden" id="debut" value="">
                                                                    <input type="hidden" id="fin" value="">
													                <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                                    <div class="listeDesBonsParProduit"><!-- content will be loaded here --></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="tab-pane" id="tab_default_34">
                                                    <ul class="nav">
                                                        <li class="active">
                                                            <a> LISTE DES PRODUITS RETIRES :
                                                                <span style="color:green;">Valeur Produit =  <span id="idTab_ProduitsRetires"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeRetireProd">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeRetireProd">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputRetireProd" placeholder="Rechercher..." autocomplete="off">
                                                        
                                                        <input type="hidden" id="debut" value="">
                                                        <input type="hidden" id="fin" value="">
                                                        <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        <div id="listeDesProduitsRetires"><!-- content will be loaded here --></div>
                                                    </div>  
                                                </div>
                                                <div class="tab-pane" id="tab_default_35">
                                                    <ul class="nav">
                                                        <li class="active">
                                                            <a> LISTE DES PRODUITS RETOURNES :
                                                                <span style="color:green;">Valeur Produit =  <span id="idTab_ProduitsRetournes"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeRetourneProd">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeRetourneProd">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputRetourneProd" placeholder="Rechercher..." autocomplete="off">
                                                        
                                                        <input type="hidden" id="debut" value="">
                                                        <input type="hidden" id="fin" value="">
                                                        <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        <div id="listeDesProduitsRetournes"><!-- content will be loaded here --></div>	
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>                
                                    </div>
                                    <div class="tab-pane" id="tab_default_4">
                                        <div class="tabbable-line">
                                            <ul class="nav nav-tabs ">
                                                <li class="active bonEnProduitEvent">
                                                    <a href="#tab_default_41" data-toggle="tab">
                                                        Bons en Produits
                                                    </a>
                                                </li>
                                                <li class="bonEnEspeceEvent" id="bonEnEspeceEvent">
                                                    <a href="#tab_default_42" data-toggle="tab">
                                                        Bons en Espèces
                                                    </a>
                                                </li>
                                                <li class="versementsClientEvent" id="versementsClientEvent">
                                                    <a href="#tab_default_43" data-toggle="tab">
                                                        Versements
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_default_41">
                                                    <ul class="nav">
                                                        <li>
                                                            <a> LISTE DES SORTIES EN BON PAR PRODUIT :
                                                                <span style="color:green;">Valeur Bon =  <span class="idTab_Bons"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeCliBonParProduit">Nombre entrées </label>
                                                        <select class="pull-left nbEntreeCliBonParProduit">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right searchInputCliBonProduit" type="text" name=""  placeholder="Rechercher..." autocomplete="off">
                                                        
                                                        <input type="hidden" id="debut" value="">
                                                        <input type="hidden" id="fin" value="">
													    <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        <div class="listeDesClientsBonsParProduit"><!-- content will be loaded here --></div>	
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_default_42">
                                                    <ul class="nav">
                                                        <li class="active">
                                                            <a> LISTE DES BONS EN ESPECES :
                                                                <span style="color:green;">Valeur Bon =  <span id="idTab_BonsEspeces"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeBonEnEspece">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeBonEnEspece">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputBonEnEspece" placeholder="Rechercher..." autocomplete="off">
													    <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        
                                                        <div id="listeDesBonsEnEspece"><!-- content will be loaded here --></div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_default_43">
                                                    <ul class="nav">
                                                        <li class="active">
                                                            <a> LISTE DES VERSEMENTS :
                                                                <span style="color:green;">Valeur Versement =  <span id="idTab_VersementsClient"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeVersement">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeVersement">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputVersement" placeholder="Rechercher..." autocomplete="off">
													    <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        
                                                        <div id="listeDesVersements"><!-- content will be loaded here --></div>	
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                             
                                    </div>
                                    <div class="tab-pane" id="tab_default_5">
                                        <div class="tabbable-line">
                                            <ul class="nav nav-tabs ">
                                                <li class="active bonLivraisonEvent" id="bonLivraisonEvent">
                                                    <a href="#tab_default_51" data-toggle="tab">
                                                        Bons de Livraison (BL)
                                                    </a>
                                                </li>
                                                <li class="versementsFourniEvent" id="versementsFourniEvent">
                                                    <a href="#tab_default_52" data-toggle="tab">
                                                        Versements
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_default_51">
                                                    <ul class="nav">
                                                        <li>
                                                            <a> LISTE DES BONS DE LIVRAISON :  
                                                                <span style="color:green;">Valeur Bon =  <span id="idTab_BonsDeLivraison"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeBL">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeBL">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputBL" placeholder="Rechercher..." autocomplete="off">
													    <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        
                                                        <div id="listeDesBonsLivraison"><!-- content will be loaded here --></div>	
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_default_52">
                                                    <ul class="nav">
                                                        <li class="active">
                                                            <a> LISTE DES VERSEMENTS :
                                                                <span style="color:green;">Valeur Versement =  <span id="idTab_VersementsFournisseurs"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeVF">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeVF">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputVF" placeholder="Rechercher..." autocomplete="off">
													    <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        
                                                        <div id="listeDesVersementsF"><!-- content will be loaded here --></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                             
                                    </div>
                                    <div class="tab-pane" id="tab_default_6">
                                        <ul class="nav">
                                            <li class="active">
                                                <a> LISTE DES SERVICES :
                                                    <span style="color:green;">Valeur Service =  <span id="idTab_Services"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="table-responsive">
                                            <label class="pull-left" for="nbEntreeService">Nombre entrées </label>
                                            <select class="pull-left" id="nbEntreeService">
                                            <optgroup>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option> 
                                            </optgroup>       
                                            </select>
                                            <input class="pull-right" type="text" name="" id="searchInputService" placeholder="Rechercher..." autocomplete="off">
                                            <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                            
                                            <div id="listeDesServices"><!-- content will be loaded here --></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_default_7">
                                        <div class="tabbable-line">
                                            <ul class="nav nav-tabs ">
                                                <li class="active remiseVenteEvent">
                                                    <a href="#tab_default_71" data-toggle="tab">
                                                        Remise Vente
                                                    </a>
                                                </li>
                                                <li class="remiseClientEvent" id="remiseClientEvent">
                                                    <a href="#tab_default_72" data-toggle="tab">
                                                        Remise Client
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_default_71">
                                                    <ul class="nav">
                                                        <li class="active">
                                                            <a> LISTE DES REMISES :
                                                                <span style="color:green;">Valeur Remise =  <span id="idTab_RemisesVente"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeRemiseVente">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeRemiseVente">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputRemiseVente" placeholder="Rechercher..." autocomplete="off">
													    <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        
                                                        <div id="listeDesRemisesVente"><!-- content will be loaded here --></div>	
                                                    </div> 
                                                </div>
                                                <div class="tab-pane" id="tab_default_72">
                                                    <ul class="nav">
                                                        <li class="active">
                                                            <a> LISTE DES REMISES :
                                                                <span style="color:green;">Valeur Remise =  <span id="idTab_RemisesClient"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>       
                                                    <div class="table-responsive">
                                                        <label class="pull-left" for="nbEntreeRemiseClient">Nombre entrées </label>
                                                        <select class="pull-left" id="nbEntreeRemiseClient">
                                                        <optgroup>
                                                            <option value="10">10</option>
                                                            <option value="20">20</option>
                                                            <option value="50">50</option> 
                                                        </optgroup>       
                                                        </select>
                                                        <input class="pull-right" type="text" name="" id="searchInputRemiseClient" placeholder="Rechercher..." autocomplete="off">
													    <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                                        
                                                        <div id="listeDesRemisesClient"><!-- content will be loaded here --></div>	
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>                             
                                    </div>
                                    <div class="tab-pane" id="tab_default_8">
                                        <ul class="nav">
                                            <li class="active">
                                                <a> LISTE DES BONS MUTUELLES :
                                                    <span style="color:green;">Bons mutuelles =  <span id="idTab_Mutuelles"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                </a>
                                                <a> LISTE DES VENTES MUTUELLES :
                                                    <span style="color:green;">Ventes mutuelles =  <span id="idTab_MutuellesVentes"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="table-responsive">
                                            <label class="pull-left" for="nbEntreeMutuelle">Nombre entrées </label>
                                            <select class="pull-left" id="nbEntreeMutuelle">
                                            <optgroup>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option> 
                                            </optgroup>       
                                            </select>
                                            <input class="pull-right" type="text" name="" id="searchInputMutuelle" placeholder="Rechercher..." autocomplete="off">
                                            <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                            
                                            <div id="listeDesMutuelles"><!-- content will be loaded here --></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_default_9">
                                        <ul class="nav">
                                            <li class="active">
                                                <a> LISTE DES RETRAITS DE CAISSE :
                                                    <span style="color:green;">Valeur retrait =  <span id="idTab_RetraitC"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="table-responsive">
                                            <label class="pull-left" for="nbEntreeRC">Nombre entrées </label>
                                            <select class="pull-left" id="nbEntreeRC">
                                            <optgroup>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option> 
                                            </optgroup>       
                                            </select>
                                            <input class="pull-right" type="text" name="" id="searchInputRC" placeholder="Rechercher..." autocomplete="off">
                                            <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                            
                                            <div id="listeDesRetraitC"><!-- content will be loaded here --></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_default_10">
                                        <ul class="nav">
                                            <li class="active">
                                                <a> LISTE DES APPROVISIONNEMENT DE CAISSE :
                                                    <span style="color:green;">Valeur approvissionnement =  <span id="idTab_ApproC"></span> <?php echo $_SESSION['symbole']; ?></span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="table-responsive">
                                            <label class="pull-left" for="nbEntreeAppro">Nombre entrées </label>
                                            <select class="pull-left" id="nbEntreeAppro">
                                            <optgroup>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option> 
                                            </optgroup>       
                                            </select>
                                            <input class="pull-right" type="text" name="" id="searchInputAppro" placeholder="Rechercher..." autocomplete="off">
                                            <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                                            
                                            <div id="listeDesApproC"><!-- content will be loaded here --></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Fin Container Details Journal -->

        <div id="rapport_Entrees" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste Entrees : <span id="id_EntreesReference"></span></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav">
                            <li class="active">
                                <a>
                                    <?php  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){?>
                                        <span style="color:green;"> Valeur Stock (PS) = <span id="id_EntreesDetailsPA"></span> <?php echo $_SESSION['symbole']; ?></span> <=>
                                        <span style="color:green;">Valeur Stock (PP) =  <span id="id_EntreesDetailsPU"></span> <?php echo $_SESSION['symbole']; ?></span>
                                    <?php }
                                    else {
                                    ?>
                                        <span style="color:green;"> Valeur Stock (PA) = <span id="id_EntreesDetailsPA"></span> <?php echo $_SESSION['symbole']; ?></span> <=>
                                        <span style="color:green;">Valeur Stock (PU) =  <span id="id_EntreesDetailsPU"></span> <?php echo $_SESSION['symbole']; ?></span>
                                    <?php } ?>
                                </a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table id="tableEntreesProduitDetails" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                    <?php  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>FORME</th>
                                            <th>TABLEAU</th>
                                            <th>QUANTITE</th>
                                            <th>PRIX SESSION (PS)</th>
                                            <th>PRIX PUBLIC (PP)</th>
                                            <th>DATE ENTREES</th>
                                        </tr>
                                    <?php }
                                    else {
                                    ?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>QUANTITE</th>
                                            <th>UNITE STOCK (US)</th>
                                            <th>NOMBRE ARTICLE U.S</th>
                                            <th>PRIX ACHAT (PA)</th>
                                            <th>PRIX UNITE (PU)</th>
                                            <th>DATE ENTREES</th>
                                        </tr>
                                    <?php } ?>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <div id="rapport_Sorties" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste des Sorties : <span id="id_SortiesReference"></span></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav">
                            <li class="active">
                                <a>
                                    <span style="color:green;"> Valeur Produit (PS) = <span id="id_SortiesDetails"></span> <?php echo $_SESSION['symbole']; ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table id="tableSortiesDetails" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                    <?php  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>PRIX VENTE</th>
                                            <th>QUANTITE</th>
                                            <th>FORME</th>
                                            <th>PRIX TOTAL</th>
                                            <th>DATE SORTIES</th>
                                        </tr>
                                    <?php }
                                    else {
                                    ?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>PRIX VENTE</th>
                                            <th>QUANTITE</th>
                                            <th>UNITE STOCK (US)</th>
                                            <th>PRIX TOTAL</th>
                                            <th>DATE SORTIES</th>
                                        </tr>
                                    <?php } ?>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="rapport_Bon" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste des Bons : <span id="id_BonReference"></span></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav">
                            <li class="active">
                                <a>
                                    <span style="color:green;"> Valeur Produit (PS) = <span id="id_BonDetails"></span> <?php echo $_SESSION['symbole']; ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table id="tableBonDetails" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                    <?php  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>FORME</th>
                                            <th>PRIX VENTE</th>
                                            <th>QUANTITE</th>
                                            <th>PRIX TOTAL</th>
                                            <th>DATE SORTIES</th>
                                        </tr>
                                    <?php }
                                    else {
                                    ?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>PRIX VENTE</th>
                                            <th>QUANTITE</th>
                                            <th>UNITE STOCK (US)</th>
                                            <th>PRIX TOTAL</th>
                                            <th>DATE SORTIES</th>
                                        </tr>
                                    <?php } ?>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <div id="rapport_panier" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">DETAILS DU PANIER <span id=""></span></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav">
                            <li class="active">
                                <a>
                                  <span style="color:green;"> TOTAL PANIER  = <span id="id_totalPanier"></span> <?php echo $_SESSION['symbole']; ?></span><br>
                                  <span style="color:green;"> REMISE  = <span id="id_remisePanier"></span> <?php echo $_SESSION['symbole']; ?></span><br>
                                  <span style="color:green;"> TOTAL A PAYER  = <span id="id_totalPayerPanier"></span> <?php echo $_SESSION['symbole']; ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table id="tablePanierDetails" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                    <?php  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>UNITE VENTE</th>
                                            <th>PRIX VENTE</th>
                                            <th>QUANTITE</th>
                                            <th>PRIX TOTAL</th>
                                        </tr>
                                    <?php }
                                    else {
                                    ?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>UNITE VENTE</th>
                                            <th>PRIX VENTE</th>
                                            <th>QUANTITE</th>
                                            <th>PRIX TOTAL</th>
                                        </tr>
                                    <?php } ?>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <div id="rapport_CBons" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste Bons : <span id="id_BonsClient"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tableClientsBons" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                <?php  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>FORME</th>
                                            <th>QUANTITE</th>
                                            <th>PRIX VENTE</th>
                                            <th>PRIX TOTAL</th>
                                            <th>DATE SORTIES</th>
                                        </tr>
                                    <?php }
                                    else {
                                    ?>
                                        <tr>
                                            <th>ORDRE</th>
                                            <th>REFERENCE</th>
                                            <th>QUANTITE</th>
                                            <th>UNITE STOCK (US)</th>
                                            <th>PRIX VENTE</th>
                                            <th>PRIX TOTAL</th>
                                            <th>DATE SORTIES</th>
                                        </tr>
                                    <?php } ?>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <div id="rapport_CVersements" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste Versements : <span id="id_VersementsClient"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tableClientsVersements" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                <tr>
                                    <th>ORDRE</th>
                                    <th>MONTANT</th>
                                    <th>PAIEMENT</th>
                                    <th>DATE VERSEMENT</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <div id="rapport_FBons" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste Bons : <span id="id_BonsFournisseur"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tableFournisseursBonsDetails" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                <tr>
                                    <th>ORDRE</th>
                                    <th>NUMERO</th>
                                    <th>MONTANT</th>
                                    <th>DATE BL</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <div id="rapport_FVersements" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste Versements : <span id="id_VersementsFournisseur"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tableFournisseursVersements" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                <tr>
                                    <th>ORDRE</th>
                                    <th>MONTANT</th>
                                    <th>PAIEMENT</th>
                                    <th>DATE VERSEMENT</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <div id="rapport_Services" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste Services : <span id="id_ServicesReference"></span></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav">
                            <li class="active">
                                <a>
                                    <span style="color:green;"> Valeur Service = <span id="id_ServicesDetails"></span> <?php echo $_SESSION['symbole']; ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table id="tableServicesDetails" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                <tr>
                                    <th>ORDRE</th>
                                    <th>REFERENCE</th>
                                    <th>QUANTITE</th>
                                    <th>UNITE</th>
                                    <th>PRIX UNITE</th>
                                    <th>PRIX TOTAL</th>
                                    <th>DATE SORTIES</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
            </div>
        </div>

        <div id="rapport_Depenses" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header panel-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Liste Depenses : <span id="id_DepensesReference"></span></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav">
                            <li class="active">
                                <a>
                                    <span style="color:green;"> Valeur Depense = <span id="id_DepensesDetails"></span> <?php echo $_SESSION['symbole']; ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table id="tableDepensesDetails" class="display tabStock" class="tableau3" width="100%" border="1">
                                <thead>
                                <tr>
                                    <th>ORDRE</th>
                                    <th>REFERENCE</th>
                                    <th>QUANTITE</th>
                                    <th>UNITE</th>
                                    <th>PRIX UNITE</th>
                                    <th>PRIX TOTAL</th>
                                    <th>DATE SORTIES</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade"  id="imageNvVersement" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding:35px 50px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Versement : <b># <span id="idVersement_View"></span></b></h4>
                    </div>
                    <form   method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:40px 50px;">
                            <input  type="text" style="display:none;" name="idVersement" id="idVersement_Upd_Nv" />
                            <div class="form-group" style="text-align:center;" >
                                <input type="file" name="file" accept="image/*" id="input_file_Versement" onchange="showPreviewVersement(event);"/><br />
                                <img style="display:none;" width="500px" height="500px" id="output_image_Versement"/>
                                <iframe style="display:none;" id="output_pdf_Versement" width="100%" height="500px"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgVersement" id="btn_upload_Versement" >
                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade"  id="imageNvBl" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding:35px 50px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> BL : <b><span id="numeroBl_View"></span></b></h4>
                    </div>
                    <form   method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:40px 50px;">
                            <input  type="text" style="display:none;" name="idBl" id="idBl_Upd_Nv" />
                            <div class="form-group" style="text-align:center;" >
                                <input type="file" name="file" accept="image/*" id="input_file_Bl" onchange="showPreviewBl(event);"/><br />
                                <img style="display:none;" width="500px" height="500px" id="output_image_Bl"/>
                                <iframe style="display:none;" id="output_pdf_Bl" width="100%" height="500px"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgBl" id="btn_upload_Bl" >
                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade"  id="imageNvPanier" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding:35px 50px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Panier : <b>#</b></h4>
                    </div>
                    <form   method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:40px 50px;">
                            <input  type="text" style="display:none;" name="idPanier" id="idPanier_Upd_Nv" />
                            <div class="form-group" style="text-align:center;" >
                                <input type="file" name="file" accept="image/*" id="input_file_Panier" onchange="showPreviewPanier(event);"/><br />
                                <img style="display:none;" width="500px" height="500px" id="output_image_Panier"/>
                                <iframe style="display:none;" id="output_pdf_Panier" width="100%" height="500px"></iframe>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgPanier" id="btn_upload_Panier" >
                                <span class="glyphicon glyphicon-upload"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade"  id="imageNvBon" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding:35px 50px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Bon : <b># <span id="idBon_View"></span></b></h4>
                    </div>
                    <form   method="post" enctype="multipart/form-data">
                        <div class="modal-body" style="padding:40px 50px;">
                            <input  type="text" style="display:none;" name="idPagnet" id="idBon_Upd_Nv" />
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

<?php

echo'</section>'.
'<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';

}

}else
{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}
?>

<!-- Debut PopUp d'Alerte sur l'ensemble de la Page -->
<?php
    if(isset($msg_info)) {
    echo"<script type='text/javascript'>
                $(window).on('load',function(){
                    $('#msg_info').modal('show');
                });
            </script>";
    echo'<div id="msg_info" class="modal fade " role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header panel-primary">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Alerte</h4>
                        </div>
                        <div class="modal-body">
                            <p>'.$msg_info.'</p>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                </div>
            </div>';

    }
?>
<!-- Fin PopUp d'Alerte sur l'ensemble de la Page -->
