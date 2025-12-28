<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:05/04/2016
*/
session_start();
if(!$_SESSION['iduserBack'])
        header('Location:index.php');

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



echo'<body onLoad="process()"><header>';

require('header.php');

echo'<div class="container" >';
echo'<section><div class="container">'; ?>

       
        <script type="text/javascript">
            $(function(){
                var end = moment();
                var start = moment().subtract(30, 'days');
                
                    function cb(start, end){
                        var debut=start.format('YYYY-MM-DD');
                        var fin=end.format('YYYY-MM-DD');
                        $("#tableEntrees").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=payement",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
                        });  
                        $("#tableAccompagnateurs").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=salaire"+"&sop=accompagnateurs",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                { mData: "5" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
                        });  
                        $("#tableIngenieur").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=salaire"+"&sop=ingenieur",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                { mData: "5" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
                        });
                        $("#tableAdmin").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=salaire"+"&sop=admin",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                { mData: "5" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
                        }); 
                        $("#tableEditeur").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=salaire"+"&sop=editeur",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                { mData: "5" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
                        }); 
                        $("#tableMobile").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=compte"+"&sop=mobile",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
                        });
                        $("#tableBancaire").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=compte"+"&sop=bancaire",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
                        });
                        $("#tableCheque").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=compte"+"&sop=cheque",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
                        }); 
                        $("#tablePret").dataTable({
                            "bProcessing": true,
                            "destroy": true,
                            "sAjaxSource": "ajax/listerRapportAjax.php?debut="+debut+"&fin="+fin+"&operation=compte"+"&sop=pret",
                            "aoColumns": [
                                { mData: "0" } ,
                                { mData: "1" },
                                { mData: "2" },
                                { mData: "3" },
                                { mData: "4" },
                                ],
                                "dom": "Bfrtip",
                                "buttons" : [
                                    "copy",
                                {
                                    extend: "excel",
                                    messageTop: "Liste de tout les Stock ",
                                },
                                {
                                    extend: "pdf",
                                    messageTop: "Liste de tout les Stock ",
                                    messageBottom: null
                                },
                                {
                                    extend: "print",
                                    text: "Imprimer",
                                    messageTop: "Liste de tout les Stock ",
                                }
                                ]
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
            function rapport_Entrees(id,debut,fin){
                var db =debut.toString();
                var date_debut=db[0]+''+db[1]+''+db[2]+''+db[3]+'-'+db[4]+''+db[5]+'-'+db[6]+''+db[7];
                var df =fin.toString();
                var date_fin=df[0]+''+df[1]+''+df[2]+''+df[3]+'-'+df[4]+''+df[5]+'-'+df[6]+''+df[7];
                $("#tableEntreesDetails").dataTable({
                        "bProcessing": true,
                        "destroy": true,
                        "sAjaxSource": "ajax/listerRapportEntreesDetailsAjax.php?id="+id+"&debut="+date_debut+"&fin="+date_fin,
                        "aoColumns": [
                            { mData: "0" } ,
                            { mData: "1" },
                            { mData: "2" },
                            { mData: "3" },
                            { mData: "4" },
                            { mData: "5" },
                            { mData: "6" },
                            { mData: "7" },
                            ],
                            "dom": "Bfrtip",
                            "buttons" : [
                                "copy",
                            {
                                extend: "excel",
                                messageTop: "Liste de tout les Stock ",
                            },
                            {
                                extend: "pdf",
                                messageTop: "Liste de tout les Stock ",
                                messageBottom: null
                            },
                            {
                                extend: "print",
                                text: "Imprimer",
                                messageTop: "Liste de tout les Stock ",
                            }
                            ]
                }); 
                $.ajax({
                    url:"ajax/ajouterLigneAjax.php",
                    method:"POST",
                    data:{
                        operation:67,
                        idDesignation : id,
                        debutAnnee : date_debut,
                        finAnnee : date_fin,
                    },
                    success: function (data) {
                        tab=data.split('<>');
                            $('#id_EntreesReference').text(tab[0]);
                            $('#id_EntreesDetailsPA').text(tab[1]);
                            $('#id_EntreesDetailsPU').text(tab[2]);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
                $('#rapport_Entrees').modal('show');
            }
            function rapport_Sorties(id,debut,fin){
                var db =debut.toString();
                var date_debut=db[0]+''+db[1]+''+db[2]+''+db[3]+'-'+db[4]+''+db[5]+'-'+db[6]+''+db[7];
                var df =fin.toString();
                var date_fin=df[0]+''+df[1]+''+df[2]+''+df[3]+'-'+df[4]+''+df[5]+'-'+df[6]+''+df[7];
                $("#tableSortiesDetails").dataTable({
                    "bProcessing": true,
                    "destroy": true,
                    "sAjaxSource": "ajax/listerRapportSortiesDetailsAjax.php?id="+id+"&debut="+date_debut+"&fin="+date_fin,
                    "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        { mData: "4" },
                        { mData: "5" },
                        { mData: "6" },
                        ],
                        "dom": "Bfrtip",
                        "buttons" : [
                            "copy",
                        {
                            extend: "excel",
                            messageTop: "Liste de tout les Stock ",
                        },
                        {
                            extend: "pdf",
                            messageTop: "Liste de tout les Stock ",
                            messageBottom: null
                        },
                        {
                            extend: "print",
                            text: "Imprimer",
                            messageTop: "Liste de tout les Stock ",
                        }
                        ]
                }); 
                $.ajax({
                    url:"ajax/ajouterLigneAjax.php",
                    method:"POST",
                    data:{
                        operation:67,
                        idDesignation : id,
                        debutAnnee : date_debut,
                        finAnnee : date_fin,
                    },
                    success: function (data) {
                        tab=data.split('<>');
                            $('#id_SortiesReference').text(tab[0]);
                            $('#id_SortiesDetails').text(tab[3]);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
                $('#rapport_Sorties').modal('show');
            }
            function rapport_CBons(id,debut,fin){
                var db =debut.toString();
                var date_debut=db[0]+''+db[1]+''+db[2]+''+db[3]+'-'+db[4]+''+db[5]+'-'+db[6]+''+db[7];
                var df =fin.toString();
                var date_fin=df[0]+''+df[1]+''+df[2]+''+df[3]+'-'+df[4]+''+df[5]+'-'+df[6]+''+df[7];
                $("#tableClientsBons").dataTable({
                    "bProcessing": true,
                    "destroy": true,
                    "sAjaxSource": "ajax/listerRapportClientsBonsAjax.php?id="+id+"&debut="+date_debut+"&fin="+date_fin,
                    "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        { mData: "4" },
                        { mData: "5" },
                        { mData: "6" },
                        ],
                        "dom": "Bfrtip",
                        "buttons" : [
                            "copy",
                        {
                            extend: "excel",
                            messageTop: "Liste de tout les Stock ",
                        },
                        {
                            extend: "pdf",
                            messageTop: "Liste de tout les Stock ",
                            messageBottom: null
                        },
                        {
                            extend: "print",
                            text: "Imprimer",
                            messageTop: "Liste de tout les Stock ",
                        }
                        ]
                }); 
                $.ajax({
                    url:"ajax/ajouterLigneAjax.php",
                    method:"POST",
                    data:{
                        operation:68,
                        idClient : id
                    },
                    success: function (data) {
                        tab=data.split('<>');
                            $('#id_BonsClient').text(tab[0]);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
                $('#rapport_CBons').modal('show');
            }
            function rapport_FBons(id,debut,fin){
                var db =debut.toString();
                var date_debut=db[0]+''+db[1]+''+db[2]+''+db[3]+'-'+db[4]+''+db[5]+'-'+db[6]+''+db[7];
                var df =fin.toString();
                var date_fin=df[0]+''+df[1]+''+df[2]+''+df[3]+'-'+df[4]+''+df[5]+'-'+df[6]+''+df[7];
                $("#tableFournisseursBons").dataTable({
                    "bProcessing": true,
                    "destroy": true,
                    "sAjaxSource": "ajax/listerRapportFournisseursBonsAjax.php?id="+id+"&debut="+date_debut+"&fin="+date_fin,
                    "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        ],
                        "dom": "Bfrtip",
                        "buttons" : [
                            "copy",
                        {
                            extend: "excel",
                            messageTop: "Liste de tout les Stock ",
                        },
                        {
                            extend: "pdf",
                            messageTop: "Liste de tout les Stock ",
                            messageBottom: null
                        },
                        {
                            extend: "print",
                            text: "Imprimer",
                            messageTop: "Liste de tout les Stock ",
                        }
                        ]
                }); 
                $.ajax({
                    url:"ajax/ajouterLigneAjax.php",
                    method:"POST",
                    data:{
                        operation:69,
                        idFournisseur : id
                    },
                    success: function (data) {
                        tab=data.split('<>');
                            $('#id_BonsFournisseur').text(tab[0]);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
                $('#rapport_FBons').modal('show');
            }
            function rapport_FVersements(id,debut,fin){
                var db =debut.toString();
                var date_debut=db[0]+''+db[1]+''+db[2]+''+db[3]+'-'+db[4]+''+db[5]+'-'+db[6]+''+db[7];
                var df =fin.toString();
                var date_fin=df[0]+''+df[1]+''+df[2]+''+df[3]+'-'+df[4]+''+df[5]+'-'+df[6]+''+df[7];
                $("#tableFournisseursVersements").dataTable({
                    "bProcessing": true,
                    "destroy": true,
                    "sAjaxSource": "ajax/listerRapportFournisseursVersementsAjax.php?id="+id+"&debut="+date_debut+"&fin="+date_fin,
                    "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        ],
                        "dom": "Bfrtip",
                        "buttons" : [
                            "copy",
                        {
                            extend: "excel",
                            messageTop: "Liste de tout les Stock ",
                        },
                        {
                            extend: "pdf",
                            messageTop: "Liste de tout les Stock ",
                            messageBottom: null
                        },
                        {
                            extend: "print",
                            text: "Imprimer",
                            messageTop: "Liste de tout les Stock ",
                        }
                        ]
                });
                $.ajax({
                    url:"ajax/ajouterLigneAjax.php",
                    method:"POST",
                    data:{
                        operation:69,
                        idFournisseur : id
                    },
                    success: function (data) {
                        tab=data.split('<>');
                            $('#id_VersementsFournisseur').text(tab[0]);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
                $('#rapport_FVersements').modal('show');
            }
            function rapport_Services(id,debut,fin){
                var db =debut.toString();
                var date_debut=db[0]+''+db[1]+''+db[2]+''+db[3]+'-'+db[4]+''+db[5]+'-'+db[6]+''+db[7];
                var df =fin.toString();
                var date_fin=df[0]+''+df[1]+''+df[2]+''+df[3]+'-'+df[4]+''+df[5]+'-'+df[6]+''+df[7];
                $("#tableServicesDetails").dataTable({
                    "bProcessing": true,
                    "destroy": true,
                    "sAjaxSource": "ajax/listerRapportServicesDetailsAjax.php?id="+id+"&debut="+date_debut+"&fin="+date_fin,
                    "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        { mData: "4" },
                        { mData: "5" },
                        { mData: "6" },
                        ],
                        "dom": "Bfrtip",
                        "buttons" : [
                            "copy",
                        {
                            extend: "excel",
                            messageTop: "Liste de tout les Stock ",
                        },
                        {
                            extend: "pdf",
                            messageTop: "Liste de tout les Stock ",
                            messageBottom: null
                        },
                        {
                            extend: "print",
                            text: "Imprimer",
                            messageTop: "Liste de tout les Stock ",
                        }
                        ]
                });
                $.ajax({
                    url:"ajax/ajouterLigneAjax.php",
                    method:"POST",
                    data:{
                        operation:67,
                        idDesignation : id,
                        idDesignation : id,
                        debutAnnee : date_debut,
                        finAnnee : date_fin,
                    },
                    success: function (data) {
                        tab=data.split('<>');
                            $('#id_ServicesReference').text(tab[0]);
                            $('#id_ServicesDetails').text(tab[4]);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
                $('#rapport_Services').modal('show');
            }
            function rapport_Depenses(id,debut,fin){
                var db =debut.toString();
                var date_debut=db[0]+''+db[1]+''+db[2]+''+db[3]+'-'+db[4]+''+db[5]+'-'+db[6]+''+db[7];
                var df =fin.toString();
                var date_fin=df[0]+''+df[1]+''+df[2]+''+df[3]+'-'+df[4]+''+df[5]+'-'+df[6]+''+df[7];
                $("#tableDepensesDetails").dataTable({
                    "bProcessing": true,
                    "destroy": true,
                    "sAjaxSource": "ajax/listerRapportDepensesDetailsAjax.php?id="+id+"&debut="+date_debut+"&fin="+date_fin,
                    "aoColumns": [
                        { mData: "0" } ,
                        { mData: "1" },
                        { mData: "2" },
                        { mData: "3" },
                        { mData: "4" },
                        { mData: "5" },
                        { mData: "6" },
                        ],
                        "dom": "Bfrtip",
                        "buttons" : [
                            "copy",
                        {
                            extend: "excel",
                            messageTop: "Liste de tout les Stock ",
                        },
                        {
                            extend: "pdf",
                            messageTop: "Liste de tout les Stock ",
                            messageBottom: null
                        },
                        {
                            extend: "print",
                            text: "Imprimer",
                            messageTop: "Liste de tout les Stock ",
                        }
                        ]
                });
                $.ajax({
                    url:"ajax/ajouterLigneAjax.php",
                    method:"POST",
                    data:{
                        operation:67,
                        idDesignation : id,
                        idDesignation : id,
                        debutAnnee : date_debut,
                        finAnnee : date_fin,
                    },
                    success: function (data) {
                        tab=data.split('<>');
                            $('#id_DepensesReference').text(tab[0]);
                            $('#id_DepensesDetails').text(tab[5]);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
                $('#rapport_Depenses').modal('show');
            }
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

        <!-- Debut Container Details Journal -->
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab">
                                    Les Paiements
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_default_2" data-toggle="tab">
                                    Les Salaires
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_default_3" data-toggle="tab">
                                        Les comptes 
                                    </a>
                                </li>
                                
                            </ul>
                            <div class="tab-content">
                                        <div class="tab-pane active" id="tab_default_1">       
                                            <ul class="nav">
                                                <li class="active">
                                                    <a> LISTE DES ENTREES : 
                                                        <span style="color:green;"> Valeur Stock (PS) = <span id="id_EntreesPA"></span> FCFA</span> <=>  
                                                        <span style="color:green;">Valeur Stock (PP) =  <span id="id_EntreesPU"></span> FCFA</span>
                                                    </a>
                                                </li>
                                            </ul> 
                                            <div class="table-responsive">
                                                <table id="tableEntrees" class="display tabStock" class="tableau3"  width="100%" border="1">
                                                    <thead>
                                                    <tr>
                                                        <th>ORDRE</th>
                                                        <th>REFERENCE</th>
                                                        <th>FORME</th>
                                                        <th>TABLEAU</th>
                                                        <th>OPERATIONS</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                      
                                <?php 
                                     ?>
                                        <div class="tab-pane" id="tab_default_2">
                                            <div class="tabbable-line">
                                                <ul class="nav nav-tabs ">
                                                    <li class="active">
                                                        <a href="#tab_default_21" data-toggle="tab">
                                                        Acocompagnateurs
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_default_22" data-toggle="tab">
                                                        Ingenieurs
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_default_23" data-toggle="tab">
                                                        Admin 
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_default_24" data-toggle="tab">
                                                        Editeur catalogue 
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="tab_default_21">
                                                        <ul class="nav">
                                                            <li>
                                                                <a> Salaire des accompagnateurs :  
                                                                    <span style="color:green;">Valeur Stock (PU) =  <span id="id_SortiesVente"></span> FCFA</span>
                                                                </a>
                                                            </li>
                                                        </ul>       
                                                        <div class="table-responsive">
                                                            <table id="tableAccompagnateurs" class="display tabStock" class="tableau3" width="100%" border="1">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ORDRE</th>
                                                                        <th>REFERENCE</th>
                                                                        <th>QUANTITE</th>
                                                                        <th>UNITE STOCK (US)</th>
                                                                        <th>UNITE STOCK (US)</th>
                                                                        <th>OPERATIONS</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tab_default_22">
                                                        <ul class="nav">
                                                            <li class="active">
                                                                <a> LISTE DES SORTIES EN BON :  
                                                                    <span style="color:green;">Valeur Stock (PU) =  <span id="id_SortiesBon"></span> FCFA</span>
                                                                </a>
                                                            </li>
                                                        </ul>       
                                                        <div class="table-responsive">
                                                            <table id="tableIngenieur" class="display tabStock" class="tableau3" width="100%" border="1">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ORDRE</th>
                                                                        <th>PRENOM</th>
                                                                        <th>NOM</th>
                                                                        <th>MONTANT</th>
                                                                        <th>COMPTE PAIEMENT</th>
                                                                        <th>OPERATION</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tab_default_23">
                                                        <ul class="nav">
                                                            <li class="active">
                                                                <a> LISTE DES PRODUITS RETIRES :  
                                                                    <span style="color:green;">Valeur Stock (PU) =  <span id="id_RapportRetires"></span> FCFA</span>
                                                                </a>
                                                            </li>
                                                        </ul>                   
                                                        <div class="table-responsive">
                                                            <table id="tableAdmin" class="display tabStock" class="tableau3" width="100%" border="1">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ORDRE</th>
                                                                        <th>PRENOM</th>
                                                                        <th>NOM</th>
                                                                        <th>MONTANT</th>
                                                                        <th>COMPTE PAIEMENT</th>
                                                                        <th>OPERATION</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>          
                                                        </div>  
                                                    </div>
                                                    <div class="tab-pane" id="tab_default_24">
                                                        <ul class="nav">
                                                            <li class="active">
                                                                <a> LISTE DES PRODUITS EXPIRES :  
                                                                    <span style="color:green;">Valeur Stock (PU) =  <span id="id_RapportExpires"></span> FCFA</span>
                                                                </a>
                                                            </li>
                                                        </ul>       
                                                        <div class="table-responsive">
                                                            <table id="tableEditeur" class="display tabStock" class="tableau3" width="100%" border="1">
                                                                <thead>
                                                                    <tr>
                                                                         <th>ORDRE</th>
                                                                        <th>PRENOM</th>
                                                                        <th>NOM</th>
                                                                        <th>MONTANT</th>
                                                                        <th>COMPTE PAIEMENT</th>
                                                                        <th>OPERATION</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    
                                ?>
                                <div class="tab-pane" id="tab_default_3">
                                    <div class="tabbable-line">
                                        <ul class="nav nav-tabs ">
                                            <li class="active">
                                                <a href="#tab_default_31" data-toggle="tab">
                                                Mobile
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab_default_32" data-toggle="tab">
                                                Bancaire
                                                </a>
                                            </li> 
                                            <li>
                                                <a href="#tab_default_33" data-toggle="tab">
                                                Cheque
                                                </a>
                                            </li> 
                                            <li>
                                                <a href="#tab_default_34" data-toggle="tab">
                                                Pret
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_default_31">
                                                <ul class="nav">
                                                    <li class="active">
                                                        <a> LISTE DES VERSEMENTS :   
                                                            <span style="color:green;">Valeur Montant des versements =  <span id="id_VersementsClient"></span> FCFA</span>
                                                        </a>
                                                    </li>
                                                </ul> 
                                                <div class="table-responsive">
                                                        <table id="tableMobile" class="display tabStock" class="tableau3" align="left" width="100%" border="1">
                                                            <thead>
                                                                <tr>
                                                                    <th>ORDRE</th>
                                                                    <th>OPERATION</th>
                                                                    <th>MONTANT</th>
                                                                    <th>DESCRIPTION</th>
                                                                    <th>OPERATION</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                </div>  
                                            </div>
                                            <div class="tab-pane" id="tab_default_32">
                                                <ul class="nav">
                                                    <li class="active">
                                                        <a> LISTE DES BONS EN ESPECES :  
                                                            <span style="color:green;">Valeur Montant =  <span id="id_RapportBonsEspeces"></span> FCFA</span>
                                                        </a>
                                                    </li>
                                                </ul>       
                                                <div class="table-responsive">
                                                    <table id="tableBancaire" class="display tabStock" class="tableau3" width="100%" border="1">
                                                        <thead>
                                                            <tr>
                                                                <th>ORDRE</th>
                                                                <th>OPERATION</th>
                                                                <th>MONTANT</th>
                                                                <th>DESCRIPTION</th>
                                                                <th>OPERATION</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>  
                                            </div> 
                                            <div class="tab-pane" id="tab_default_33">
                                                <ul class="nav">
                                                    <li class="active">
                                                        <a> LISTE DES BONS EN ESPECES :  
                                                            <span style="color:green;">Valeur Montant =  <span id="id_RapportBonsEspeces"></span> FCFA</span>
                                                        </a>
                                                    </li>
                                                </ul>       
                                                <div class="table-responsive">
                                                    <table id="tableCheque" class="display tabStock" class="tableau3" width="100%" border="1">
                                                        <thead>
                                                            <tr>
                                                                <th>ORDRE</th>
                                                                <th>OPERATION</th>
                                                                <th>MONTANT</th>
                                                                <th>DESCRIPTION</th>
                                                                <th>OPERATION</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>  
                                            </div>
                                            <div class="tab-pane" id="tab_default_34">
                                                <ul class="nav">
                                                    <li class="active">
                                                        <a> LISTE DES BONS EN ESPECES :  
                                                            <span style="color:green;">Valeur Montant =  <span id="id_RapportBonsEspeces"></span> FCFA</span>
                                                        </a>
                                                    </li>
                                                </ul>       
                                                <div class="table-responsive">
                                                    <table id="tablePret" class="display tabStock" class="tableau3" width="100%" border="1">
                                                        <thead>
                                                            <tr>
                                                                <th>ORDRE</th>
                                                                <th>OPERATION</th>
                                                                <th>MONTANT</th>
                                                                <th>DESCRIPTION</th>
                                                                <th>OPERATION</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>  
                                            </div>
                                        </div>
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
                                        <span style="color:green;"> Valeur Stock (PS) = <span id="id_EntreesDetailsPA"></span> FCFA</span> <=>  
                                        <span style="color:green;">Valeur Stock (PP) =  <span id="id_EntreesDetailsPU"></span> FCFA</span>
                                    <?php }
                                    else {
                                    ?>
                                        <span style="color:green;"> Valeur Stock (PA) = <span id="id_EntreesDetailsPA"></span> FCFA</span> <=>  
                                        <span style="color:green;">Valeur Stock (PU) =  <span id="id_EntreesDetailsPU"></span> FCFA</span>
                                    <?php } ?>
                                </a>
                            </li>
                        </ul> 
                        <div class="table-responsive">
                            <table id="tableEntreesDetails" class="display tabStock" class="tableau3" width="100%" border="1">
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
                        <h4 class="modal-title">Liste Sorties : <span id="id_SortiesReference"></span></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="nav">
                            <li class="active">
                                <a> 
                                    <span style="color:green;"> Valeur Produit  (PS) = <span id="id_SortiesDetails"></span> FCFA</span>
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
                            <table id="tableFournisseursBons" class="display tabStock" class="tableau3" width="100%" border="1">
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
                                    <span style="color:green;"> Valeur Service = <span id="id_ServicesDetails"></span> FCFA</span>
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
                                    <span style="color:green;"> Valeur Depense = <span id="id_DepensesDetails"></span> FCFA</span>
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

<?php

echo'</section>'.
'<script>$(document).ready(function(){$("#AjoutLigne").click(function(){$("#AjoutLigneModal").modal();});});</script></body></html>';


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