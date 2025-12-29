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
    require('connectionPDO.php');

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
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$dateInv=$mois.'-'.$annee;

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




echo'<body ><header>';

require('header.php');

echo'<div class="container" >';
$dateJ=null;
$dateInventaire=null;
if((!@$_GET["datenext"])&&(!@$_GET["dateprevious"])){
    $datehier = date('d-m-Y', strtotime('-1 days'));
    $datehier_Y = date('Y-m-d', strtotime('-1 days'));
    $dateJ=explode("-", $datehier);
}else if (@$_GET["dateprevious"]){
    $dateInventaire=@$_GET["dateprevious"];
    $dateDetails=explode("-", $dateInventaire);
    $dateInventaire_Y=$dateDetails[2].'-'.$dateDetails[1].'-'.$dateDetails[0];
    $dateJ=explode("-", $dateInventaire);
   
}else if (@$_GET["datenext"]){
$dateInventaire=@$_GET["datenext"];
$dateDetails=explode("-", $dateInventaire);
$dateInventaire_Y=$dateDetails[2].'-'.$dateDetails[1].'-'.$dateDetails[0];
$dateJ=explode("-", $dateInventaire);

}
    
           //var_dump($dateJ);
    $annee =$dateJ[2];
    $mois =$dateJ[1];
    $jour =$dateJ[0];
echo'<section><div class="container">'; ?>
        <?php 
           $debutPaiment=$annee.'-'.$mois.'-'.$jour;
           //var_dump($debutPaiment);

            
         ?>
       
        <script type="text/javascript">
            $(function(){
                var end   = moment();
                var start = moment().subtract(30, 'days');
                
                    function cb(start, end){
                        var debut=start.format('YYYY-MM-DD');
                        var fin=end.format('YYYY-MM-DD');
                        var debutPaiment="<?php echo $debutPaiment ?>";

                        
                        var dateDebut=start.format('DD-MM-YYYY');
                        var dateFin=end.format('DD-MM-YYYY');

                        $('#id_dateDebut').text(dateDebut);
                        $('#id_dateFin').text(dateFin);
                        $('#id_dateDebut2').text(dateDebut);
                        $('#id_dateFin2').text(dateFin);
                        
                        $('#input_dateDebut').val(debut);
                        $('#input_dateFin').val(fin);

                        var debutAnnee=start.format('YYYY-MM-DD');
                        var finAnnee=end.format('YYYY-MM-DD');
                        var debutJour=start.format('DD-MM-YYYY');
                        var finJour=end.format('DD-MM-YYY');
                       

                        $.ajax({
                            url:"ajax/etatRapportAjax.php",
                            method:"POST",
                            data:{
                                operation:"etat",
                                debut : debut,
                                fin : fin,
                                finAnnee : finAnnee,
                                debutAnnee : debutAnnee,
                                finAnnee : finAnnee,
                                debutJour : debutJour,
                                finJour : finJour
                            },
                            success: function (data) {
                                tab=data.split('<>');
                                    $('#id_PaiementTo').text(tab[0]);
                                    $('#id_PaiementEf').text(tab[1]);
                                    $('#id_PaiementNef').text(tab[2]);
                                    $('#id_PaiementAlavance').text(tab[3]);
                                    $('#id_SalaireTot').text(tab[4]);
                                    $('#id_SalairePers').text(tab[23]);
                                    console.log(tab[23])  ; 
                                   /* $('#id_SalaireAcc').text(tab[5]);
                                    $('#id_SalaireIng').text(tab[6]);
                                    $('#id_SalaireAss').text(tab[7]);
                                    $('#id_SalaireAdmin').text(tab[8]);*/
                                    $('#id_Om').text(tab[9]);
                                    $('#id_DepotOM').text(tab[10]);
                                    $('#id_RetraitOM').text(tab[11]);
                                    $('#id_TransfertOM').text(tab[12]);
                                    $('#id_Wave').text(tab[13]);
                                    $('#id_DepotWA').text(tab[14]);
                                    $('#id_RetraitWA').text(tab[15]);
                                    $('#id_TransfertWA').text(tab[16]); 
                                    $('#id_Caisse').text(tab[17]);  
                                    $('#id_DepotCA').text(tab[18]);  
                                    $('#id_RetraitCA').text(tab[19]); 
                                    $('#id_Pret').text(tab[20]);   
                                    $('#id_PretRemb').text(tab[21]);   
                                    $('#id_PretNew').text(tab[22]); 
                                    console.log(tab[24])  ; 
                                    console.log(tab[25])  ;    
                            },
                            error: function() {
                                alert("La requête "); },
                            dataType:"text"
                        });
                        // ============================================================================
                        //                        LES TABLEAUX DU RAPPORT

                        /*************** Début lister Dépenses ***************/
                            $(document).ready(function() {                                  
                                nbDepenses = $('#nbDepenses').val()
                                $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbDepenses":nbDepenses,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableDepenses").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbDepenses = $('#nbDepenses').val()
                                    query = $('#searchInputDepenses').val();

                                    if (query.length == 0) {
                                        $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbDepenses":nbDepenses,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbDepenses":nbDepenses,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputDepenses').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputDepenses').val()
                                        nbDepenses = $('#nbDepenses').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbDepenses":nbDepenses,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbDepenses":nbDepenses,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbDepenses":nbDepenses,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbDepenses":nbDepenses,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbDepenses').on("change", function(e) {
                                        e.preventDefault();

                                        nbDepenses = $('#nbDepenses').val()
                                        query = $('#searchInputDepenses').val();

                                        if (query.length == 0) {
                                            $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbDepenses":nbDepenses,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableDepenses" ).load( "ajax/rapport/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbDepenses":nbDepenses,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                            /*************** Fin lister Dépenses ***************/

                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//    
                        /*************** Début lister Salires Accomp ***************/
                            $(document).ready(function() {                                  
                                nbSalairesAcc = $('#nbSalairesAcc').val()
                                $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbSalairesAcc":nbSalairesAcc,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableSalairesAcc").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbSalairesAcc = $('#nbSalairesAcc').val()
                                    query = $('#searchInputSalairesAcc').val();

                                    if (query.length == 0) {
                                        $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbSalairesAcc":nbSalairesAcc,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbSalairesAcc":nbSalairesAcc,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputSalairesAcc').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputSalairesAcc').val()
                                        nbSalairesAcc = $('#nbSalairesAcc').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesAcc":nbSalairesAcc,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesAcc":nbSalairesAcc,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesAcc":nbSalairesAcc,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesAcc":nbSalairesAcc,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbSalairesAcc').on("change", function(e) {
                                        e.preventDefault();

                                        nbSalairesAcc = $('#nbSalairesAcc').val()
                                        query = $('#searchInputSalairesAcc').val();

                                        if (query.length == 0) {
                                            $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbSalairesAcc":nbSalairesAcc,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableSalairesAcc" ).load( "ajax/rapport/listerRapportSalairesAccAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbSalairesAcc":nbSalairesAcc,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                            /*************** Fin lister Saliare Accomp ***************/    
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//    
                        /*************** Début lister Salires Accomp ***************/
                            $(document).ready(function() {                                  
                                nbSalairesIng = $('#nbSalairesIng').val()
                                $("#tableSalairesIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbSalairesIng":nbSalairesIng,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableSalairesIng").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbSalairesIng = $('#nbSalairesIng').val()
                                    query = $('#searchInputSalairesIng').val();

                                    if (query.length == 0) {
                                        $("#tableSalairesIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbSalairesIng":nbSalairesIng,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableSalairesIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbSalairesIng":nbSalairesIng,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputSalairesIng').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputSalairesIng').val()
                                        nbSalairesAcc = $('#nbSalairesIng').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableSalairesIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesIng":nbSalairesIng,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableSalairesIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesIng":nbSalairesIng,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableSalairesIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesIng":nbSalairesIng,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableSalaireIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesIng":nbSalairesIng,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbSalairesIng').on("change", function(e) {
                                        e.preventDefault();

                                        nbSalairesAcc = $('#nbSalairesIng').val()
                                        query = $('#searchInputSalairesIng').val();

                                        if (query.length == 0) {
                                            $("#tableSalairesIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbSalairesIng":nbSalairesIng,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableSalairesIng" ).load( "ajax/rapport/listerRapportSalairesIngAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbSalairesIng":nbSalairesIng,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                            /*************** Fin lister Dépenses ***************/    
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//    
                        /*************** Début lister Salires ADm ***************/
                            $(document).ready(function() {                                  
                                nbSalairesAdm = $('#nbSalairesAdm').val()
                                $("#tableSalairesAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbSalairesAdm":nbSalairesAdm,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableSalairesAdm").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbSalairesIng = $('#nbSalairesAdm').val()
                                    query = $('#searchInputSalairesAdm').val();

                                    if (query.length == 0) {
                                        $("#tableSalairesAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbSalairesAdm":nbSalairesAdm,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableSalairesAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbSalairesAdm":nbSalairesAdm,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputSalairesAdm').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputSalairesAdm').val()
                                        nbSalairesAdm = $('#nbSalairesAdm').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableSalairesAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesAdm":nbSalairesAdm,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableSalairesAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesAdm":nbSalairesAdm,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableSalairesAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesAdm":nbSalairesAdm,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableSalaireAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesAdm":nbSalairesAdm,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbSalairesAdm').on("change", function(e) {
                                        e.preventDefault();

                                        nbSalairesAdm = $('#nbSalairesAdm').val()
                                        query = $('#searchInputSalairesAdm').val();

                                        if (query.length == 0) {
                                            $("#tableSalairesAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbSalairesAdm":nbSalairesAdm,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableSalairesAdm" ).load( "ajax/rapport/listerRapportSalairesAdmAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbSalairesAdm":nbSalairesAdm,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//    
                        /*************** Début lister Salires Editeur catalogue ***************/
                            $(document).ready(function() {                                  
                                nbSalairesEdi = $('#nbSalairesEdi').val()
                                $("#tableSalairesEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbSalairesEdi":nbSalairesEdi,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableSalairesEdi").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbSalairesEdi = $('#nbSalairesEdi').val()
                                    query = $('#searchInputSalairesEdi').val();

                                    if (query.length == 0) {
                                        $("#tableSalairesEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbSalairesEdi":nbSalairesEdi,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableSalairesEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbSalairesEdi":nbSalairesEdi,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputSalairesEdi').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputSalairesEdi').val()
                                        nbSalairesEdi = $('#nbSalairesEdi').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableSalairesEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesEdi":nbSalairesEdi,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableSalairesEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesEdi":nbSalairesEdi,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableSalairesEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesEdi":nbSalairesEdi,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableSalaireEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbSalairesEdi":nbSalairesEdi,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbSalairesEdi').on("change", function(e) {
                                        e.preventDefault();

                                        nbSalairesEdi = $('#nbSalairesEdi').val()
                                        query = $('#searchInputSalairesEdi').val();

                                        if (query.length == 0) {
                                            $("#tableSalairesEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbSalairesEdi":nbSalairesEdi,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableSalairesEdi" ).load( "ajax/rapport/listerRapportSalairesEdiAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbSalairesEdi":nbSalairesEdi,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//    
                        /*************** Début lister Compte Wave ***************/
                            $(document).ready(function() {                                  
                                nbCompteWave = $('#nbCompteWave').val()
                                $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbCompteWave":nbCompteWave,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableCompteWave").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbCompteWave = $('#nbCompteWave').val()
                                    query = $('#searchInputCompteWave').val();

                                    if (query.length == 0) {
                                        $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteWave":nbCompteWave,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteWave":nbCompteWave,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputCompteWave').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputCompteWave').val()
                                        nbCompteWave = $('#nbCompteWave').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWave":nbCompteWave,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWave":nbCompteWave,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWave":nbCompteWave,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWave":nbCompteWave,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbCompteWave').on("change", function(e) {
                                        e.preventDefault();

                                        nbCompteWave = $('#nbCompteWave').val()
                                        query = $('#searchInputCompteWave').val();

                                        if (query.length == 0) {
                                            $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteWave":nbCompteWave,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableCompteWave" ).load( "ajax/rapport/listerRapportCompteWaveAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteWave":nbCompteWave,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                        
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//    
                        /*************** Début lister Compte Wave DEPOT ***************/
                            $(document).ready(function() {                                  
                                nbCompteWaveDep = $('#nbCompteWaveDep').val()
                                $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbCompteWaveDep":nbCompteWaveDep,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableCompteWaveDep").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbCompteWaveDep = $('#nbCompteWaveDep').val()
                                    query = $('#searchInputCompteWaveDep').val();

                                    if (query.length == 0) {
                                        $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteWaveDep":nbCompteWaveDep,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteWaveDep":nbCompteWaveDep,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputCompteWaveDep').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputCompteWaveDep').val()
                                        nbCompteWaveDep = $('#nbCompteWaveDep').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWaveDep":nbCompteWaveDep,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWaveDep":nbCompteWaveDep,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWaveDep":nbCompteWaveDep,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWaveDep":nbCompteWaveDep,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbCompteWaveDep').on("change", function(e) {
                                        e.preventDefault();

                                        nbCompteWaveDep = $('#nbCompteWaveDep').val()
                                        query = $('#searchInputCompteWaveDep').val();

                                        if (query.length == 0) {
                                            $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteWaveDep":nbCompteWaveDep,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableCompteWaveDep" ).load( "ajax/rapport/listerRapportCompteWaveDepAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteWaveDep":nbCompteWaveDep,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                        
                        
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//    
                        /*************** Début lister Compte Wave RETRAIT ***************/
                            $(document).ready(function() {                                  
                                nbCompteWaveRet = $('#nbCompteWaveRet').val()
                                $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbCompteWaveRet":nbCompteWaveRet,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableCompteWaveRet").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbCompteWaveRet = $('#nbCompteWaveRet').val()
                                    query = $('#searchInputCompteWaveRet').val();

                                    if (query.length == 0) {
                                        $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteWaveRet":nbCompteWaveRet,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteWaveRet":nbCompteWaveRet,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputCompteWaveRet').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputCompteWaveRet').val()
                                        nbCompteWaveRet = $('#nbCompteWaveRet').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWaveRet":nbCompteWaveRet,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWaveRet":nbCompteWaveRet,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWaveRet":nbCompteWaveRet,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteWaveRet":nbCompteWaveRet,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbCompteWaveRet').on("change", function(e) {
                                        e.preventDefault();

                                        nbCompteWaveRet = $('#nbCompteWaveRet').val()
                                        query = $('#searchInputCompteWaveRet').val();

                                        if (query.length == 0) {
                                            $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteWaveRet":nbCompteWaveRet,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableCompteWaveRet" ).load( "ajax/rapport/listerRapportCompteWaveRetAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteWaveRet":nbCompteWaveRet,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                        
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//    
                        /*************** Début lister Compte O Money ***************/
                            $(document).ready(function() {                                  
                                nbCompteOMoney = $('#nbCompteOMoney').val()
                                $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbCompteOMoney":nbCompteOMoney,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableCompteOMoney").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbCompteOMoney = $('#nbCompteOMoney').val()
                                    query = $('#searchInputCompteOMoney').val();

                                    if (query.length == 0) {
                                        $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteOMoney":nbCompteOMoney,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteOMoney":nbCompteOMoney,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputCompteOMoney').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputCompteOMoney').val()
                                        nbCompteOMoney = $('#nbCompteOMoney').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteOMoney":nbCompteOMoney,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteOMoney":nbCompteOMoney,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteOMoney":nbCompteOMoney,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteOMoney":nbCompteOMoney,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbCompteOMoney').on("change", function(e) {
                                        e.preventDefault();

                                        nbCompteOMoney = $('#nbCompteOMoney').val()
                                        query = $('#searchInputCompteOMoney').val();

                                        if (query.length == 0) {
                                            $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteWave":nbCompteOMoney,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableCompteOMoney" ).load( "ajax/rapport/listerRapportCompteOMoneyAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteWave":nbCompteOMoney,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
                        /*************** Début lister Compte Bancaire ***************/
                            $(document).ready(function() {                                  
                                nbCompteBancaire = $('#nbCompteBancaire').val()
                                $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbCompteBancaire":nbCompteBancaire,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableCompteBancaire").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbCompteBancaire = $('#nbCompteBancaire').val()
                                    query = $('#searchInputCompteBancaire').val();

                                    if (query.length == 0) {
                                        $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteBancaire":nbCompteBancaire,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteBancaire":nbCompteBancaire,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputCompteBancaire').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputCompteBancaire').val()
                                        nbCompteBancaire = $('#nbCompteBancaire').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteBancaire":nbCompteBancaire,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteBancaire":nbCompteBancaire,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteBancaire":nbCompteBancaire,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteBancaire":nbCompteBancaire,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbCompteBancaire').on("change", function(e) {
                                        e.preventDefault();

                                        nbCompteBancaire= $('#nbCompteBancaire').val()
                                        query = $('#searchInputCompteBancaire').val();

                                        if (query.length == 0) {
                                            $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteBancaire":nbCompteBancaire,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableCompteBancaire" ).load( "ajax/rapport/listerRapportCompteBancaireAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteBancaire":nbCompteBancaire,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                         /*************** Fin lister Compte bancaire ***************/
                        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
                        /*************** Début lister Compte cheque ***************/
                            $(document).ready(function() {                                  
                                nbCompteCheque = $('#nbCompteCheque').val()
                                $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbCompteCheque":nbCompteCheque,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableCompteCheque").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbCompteBancaire = $('#nbCompteCheque').val()
                                    query = $('#searchInputCompteCheque').val();

                                    if (query.length == 0) {
                                        $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteCheque":nbCompteCheque,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteCheque":nbCompteCheque,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                $('#searchInputCompteCheque').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputCompteCheque').val()
                                        nbCompteCheque = $('#nbCompteCheque').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteCheque":nbCompteCheque,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteCheque":nbCompteCheque,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteCheque":nbCompteCheque,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteCheque":nbCompteCheque,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbCompteCheque').on("change", function(e) {
                                        e.preventDefault();

                                        nbCompteCheque= $('#nbCompteCheque').val()
                                        query = $('#searchInputCompteCheque').val();

                                        if (query.length == 0) {
                                            $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteCheque":nbCompteCheque,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableCompteCheque" ).load( "ajax/rapport/listerRapportCompteChequeAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteCheque":nbCompteCheque,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                         /*************** Fin lister Compte cheque ***************/
                         //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
                        /*************** Début lister Compte Caisse ***************/
                            $(document).ready(function() {                                  
                                nbCompteCaisse = $('#nbCompteCaisse').val()
                                $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbCompteCaisse":nbCompteCaisse,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableCompteCaisse").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbCompteCaisse = $('#nbCompteCaisse').val()
                                    query = $('#searchInputCompteCaisse').val();

                                    if (query.length == 0) {
                                        $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteCaisse":nbCompteCaisse,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbCompteCaisse":nbCompteCaisse,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                    $('#searchInputCompteCaisse').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputCompteCaisse').val()
                                        nbCompteCaisse = $('#nbCompteCaisse').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteCaisse":nbCompteCaisse,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteCaisse":nbCompteCaisse,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteCaisse":nbCompteCaisse,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbCompteCaisse":nbCompteCaisse,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbCompteCaisse').on("change", function(e) {
                                        e.preventDefault();

                                        nbCompteCaisse= $('#nbCompteCaisse').val()
                                        query = $('#searchInputCompteCaisse').val();

                                        if (query.length == 0) {
                                            $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteCaisse":nbCompteCaisse,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableCompteCaisse" ).load( "ajax/rapport/listerRapportCompteCaisseAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbCompteCaisse":nbCompteCaisse,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                         /*************** Fin lister Compte Caisse ***************/
                         //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
                        /*************** Début lister Compte Pret ***************/
                             $(document).ready(function() {                                  
                                nbComptePret = $('#nbComptePret').val()
                                $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"operation":1,"nbComptePret":nbComptePret,"query":"","cb":""}); //load initial records
                                //executes code below when user click on pagination links
                                $("#tableComptePret").on( "click", ".pagination a", function (e){
                                // $("#listeDesDepenses").on( "click", function (e){
                                    e.preventDefault();
                                    $(".loading-div").show(); //show loading element
                                    // page = page+1; //get page number from link
                                    page = $(this).attr("data-page"); //get page number from link
                                    //  alert(page)

                                    nbComptePret= $('#nbComptePret').val()
                                    query = $('#searchInputComptePret').val();

                                    if (query.length == 0) {
                                        $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbComptePret":nbComptePret,"query":"","cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                            
                                    }else{
                                        $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1,"nbComptePret":nbComptePret,"query":query,"cb":""}, function(){ //get content from PHP page
                                            $(".loading-div").hide(); //once done, hide loading element
                                        });
                                    }
                                    // $("#listeDesDepenses").load("ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"page":page,"operation":1}
                                });
                            });
                            /********   RECHERCHE et NOMBRE D'ENTREES   ******/
                            $(document).ready(function() {
                                    $('#searchInputComptePret').on("keyup", function(e) {
                                        e.preventDefault();
                                        
                                        query = $('#searchInputComptePret').val()
                                        nbComptePret = $('#nbComptePret').val()

                                        var keycode = (e.keyCode ? e.keyCode : e.which);
                                        if (keycode == '13') {
                                            // alert(1111)
                                            t = 1; // code barre
                                            
                                            if (query.length > 0) {
                                                
                                                $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbComptePret":nbComptePret,"query":query,"cb":t}); //load initial records
                                            }else{
                                                $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbComptePret":nbComptePret,"query":"","cb":t}); //load initial records
                                            }
                                        }else{
                                            // alert(2222)
                                            t = 0; // no code barre
                                            setTimeout(() => {
                                                if (query.length > 0) {
                                                    
                                                    $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbComptePret":nbComptePret,"query":query,"cb":t}); //load initial records
                                                }else{
                                                    $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"operation":3,"nbComptePret":nbComptePret,"query":"","cb":t}); //load initial records
                                                }
                                            }, 100);
                                        }
                                    });

                                    $('#nbComptePret').on("change", function(e) {
                                        e.preventDefault();

                                        nbComptePret= $('#nbComptePret').val()
                                        query = $('#searchInputComptePret').val();

                                        if (query.length == 0) {
                                            $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbComptePret":nbComptePret,"query":"","cb":""}); //load initial records
                                                
                                        }else{
                                            $("#tableComptePret" ).load( "ajax/rapport/listerRapportComptePretAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbComptePret":nbComptePret,"query":query,"cb":""}); //load initial records
                                        }
                                            
                                        // $("#listeDesDepenses" ).load( "ajax/listerRapportDepensesAjax.php?debut="+debut+"&fin="+fin,{"operation":4,"nbEntreeDepense":nbEntreeDepense}); //load initial records
                                    });

                            });
                         /*************** Fin lister Compte Pret ***************/ 
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
                                  <h3 class="panel-title" ><a href="#"> ETAT RAPPORT POUR LA PERIODE DU <span id="id_dateDebut2"></span>  AU  <span id="id_dateFin2"></span>
                                    <span class="glyphicon glyphicon-chevron-up" id="expand" style="float:right;"></span> </a></h3>
                                        <!-- <h3 class="panel-title" id="reportrange">ETAT RAPPORT POUR LA PERIODE DU <?php echo $dateString2; ?>   AU   <?php echo $dateString2; ?> </h3> -->
                                </div>
                                <div style="display:none">
                                    <input id="input_dateDebut" />
                                    <input id="input_dateFin" />
                                </div>
                                <div class="panel-body collapse in" id="collapseOne" aria-labelledby="headingOne">
                                        <table class="table table-bordered table-responsive ">
                                                <tr class="info">
                                                    <td><b>PAIEMENT BOUTIQUE :</b></td>
                                                    <td><div><b><span id="id_PaiementTo"> FCFA</span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Paiement effectif :</td>
                                                    <td><div><span id="id_PaiementEf"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Paiement non effectif :</td>
                                                    <td><div><span id="id_PaiementNef"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Paiement à l'avance :</td>
                                                    <td><div><span id="id_PaiementAlavance"></span> FCFA</span></div></td>
                                                </tr>
                                               
                                                <tr class="info">
                                                    <td><b>SALAIRES :</b></td>
                                                    <td><div><b><span id="id_SalaireTot"></span> FCFA</span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Personnel :</td>
                                                    <td><div><span id="id_SalairePers"></span> FCFA</span></div></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td>Accompagnateur :</td>
                                                    <td><div><span id="id_SalaireAcc"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Ingenieur :</td>
                                                    <td><div><span id="id_SalaireIng"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Assistant :</td>
                                                    <td><div><span id="id_SalaireAss"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Admin :</td>
                                                    <td><div><span id="id_SalaireAdmin"></span> FCFA</span></div></td>
                                                </tr> -->
                                                <tr class="info">
                                                    <td><b>ORANGE MONEY:</b></td>
                                                    <td><div><b><span id="id_Om"></span></span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Depot :</td>
                                                    <td><div><span id="id_DepotOM"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Retrait :</td>
                                                    <td><div><span id="id_RetraitOM"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Transfert :</td>
                                                    <td><div><span id="id_TransfertOM"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr class="info">
                                                    <td><b>WAVE:</b></td>
                                                    <td><div><b><span id="id_Wave"></span></span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Depot :</td>
                                                    <td><div><span id="id_DepotWA"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Retrait :</td>
                                                    <td><div><span id="id_RetraitWA"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Transfert :</td>
                                                    <td><div><span id="id_TransfertWA"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr class="info">
                                                    <td><b>CAISSE:</b></td>
                                                    <td><div><b><span id="id_Caisse"></span></span></b></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Depot :</td>
                                                    <td><div><span id="id_DepotCA"></span> FCFA</span></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Retrait :</td>
                                                    <td><div><span id="id_RetraitCA"></span> FCFA</span></div></td>
                                                </tr>
                                        </table>

                                    <form class="form-inline pull-right noImpr"  target="_blank" style="margin-right:20px;"
                                      method="post" action="pdfRapportDetails.php" >
                                      <input type="hidden" name="dateImp" id="idDateImp"  <?php echo  "value=".$dateString2."" ; ?> >
                                      <input type="hidden" name="debutAnnee" id="debutAnnee" class="debutAnnee"  value="">
                                      <input type="hidden" name="finAnnee" id="finAnnee" class="finAnnee"  value="" >
                                      <button type="submit" class="btn btn-info  pull-right" style="margin-right:20px;" >
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
                                <li>
                                    <a href="#tab_default_4" data-toggle="tab">
                                        Pret
                                    </a>
                                </li>
                              <!--   <li>
                                    <a href="#tab_default_5" data-toggle="tab">
                                        Pret par entité
                                    </a>
                                </li> -->
                            </ul>
                            <div class="tab-content"> 
                                <div class="tab-pane active" id="tab_default_1">       
                                        <ul class="nav">
                                            <li class="active">
                                                <!-- <a> LISTE DES DEPENSES :
                                                    <span style="color:green;">Valeur Depenses =  <span > <script> $('#id_PaiementTo').val();</script></span> 
                                                </a> -->
                                            </li>
                                        </ul> 
                                        <div class="table-responsive">
                                            <label class="pull-left" for="nbDepenses">Nombre entrées </label>
                                            <select class="pull-left" id="nbDepenses">
                                            <optgroup>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option> 
                                            </optgroup>       
                                            </select>
                                            <input class="pull-right" type="text" name="" id="searchInputDepenses" placeholder="Rechercher..." autocomplete="off">
                                            <div id="tableDepenses"><!-- content will be loaded here --></div>	
                                        </div>
                                </div>                                                                   
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
                                                        Assistant 
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="tab_default_21">
                                                        <ul class="nav">
                                                            <li>
                                                                <!-- <a> Salaire des accompagnateurs :  
                                                                    <span style="color:green;">Valeur Stock (PU) =  <span id="id_SalaireAcc"></span> FCFA</span>
                                                                </a> -->
                                                            </li>
                                                        </ul>       
                                                        <div class="table-responsive">
                                                            <label class="pull-left" for="nbSalairesAcc">Nombre entrées </label>
                                                            <select class="pull-left" id="nbSalairesAcc">
                                                            <optgroup>
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option> 
                                                            </optgroup>       
                                                            </select>
                                                            <input class="pull-right" type="text" name="" id="searchInputSalairesAcc" placeholder="Rechercher..." autocomplete="off">
                                                            <div id="tableSalairesAcc"><!-- content will be loaded here --></div>	
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tab_default_22">
                                                        <ul class="nav">
                                                            <li class="active">
                                                                <!-- <a> SALAIRES DES INGENIEURS :  
                                                                    <span style="color:green;">Valeur Stock (PU) =  <span id="id_SalaireIng"></span> FCFA</span>
                                                                </a> -->
                                                            </li>
                                                        </ul>  
                                                        <div class="table-responsive">
                                                            <label class="pull-left" for="nbSalairesIng">Nombre entrées </label>
                                                            <select class="pull-left" id="nbSalairesIng">
                                                            <optgroup>
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option> 
                                                            </optgroup>       
                                                            </select>
                                                            <input class="pull-right" type="text" name="" id="searchInputSalairesIng" placeholder="Rechercher..." autocomplete="off">
                                                            <div id="tableSalairesIng"><!-- content will be loaded here --></div>	
                                                        </div>    
                                                    </div>
                                                    <div class="tab-pane" id="tab_default_23">
                                                        <ul class="nav">
                                                            <li class="active">
                                                                <!-- <a> SALAIRE DES ADMINISTRATEURS :  
                                                                    <span style="color:green;">Valeur Stock (PU) =  <span id="id_SalaireAdmin"></span> FCFA</span>
                                                                </a> -->
                                                            </li>
                                                        </ul>                   
                                                        <div class="table-responsive">
                                                            <label class="pull-left" for="nbSalairesAdm">Nombre entrées </label>
                                                            <select class="pull-left" id="nbSalairesAdm">
                                                            <optgroup>
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option> 
                                                            </optgroup>       
                                                            </select>
                                                            <input class="pull-right" type="text" name="" id="searchInputSalairesAdm" placeholder="Rechercher..." autocomplete="off">
                                                            <div id="tableSalairesAdm"><!-- content will be loaded here --></div>	
                                                        </div>    
                                                    </div>
                                                    <div class="tab-pane" id="tab_default_24">
                                                        <ul class="nav">
                                                            <li class="active">
                                                                <!-- <a> SALAIRE DES EDITEURS DE CATALOGUE :  
                                                                    <span style="color:green;">Valeur Stock (PU) =  <span id="id_RapportExpires"></span> FCFA</span>
                                                                </a> -->
                                                            </li>
                                                        </ul>       
                                                        <div class="table-responsive">
                                                            <label class="pull-left" for="nbSalairesEdi">Nombre entrées </label>
                                                            <select class="pull-left" id="nbSalairesEdi">
                                                            <optgroup>
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option> 
                                                            </optgroup>       
                                                            </select>
                                                            <input class="pull-right" type="text" name="" id="searchInputSalairesEdi" placeholder="Rechercher..." autocomplete="off">
                                                            <div id="tableSalairesEdi"><!-- content will be loaded here --></div>	
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>
                                </div>
                                        
                                <div class="tab-pane" id="tab_default_3">
                                    <div class="tabbable-line">
                                        <ul class="nav nav-tabs ">
                                            <!-- kkk -->
                                            <!-- kkk -->
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
                                                Caisse
                                                </a>
                                            </li> 
                                            
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_default_31">
                                                <div class="tabbable-line">
                                                    <ul class="nav nav-tabs ">
                                                        <li class="active">
                                                            <a href="#tab_default_311" data-toggle="tab">
                                                                Wave
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_default_312" data-toggle="tab">
                                                                Orange Money
                                                            </a>
                                                        </li>
                                                    </ul> 
                                                    <!-- JJ -->
                                                    <!-- JJ -->
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="tab_default_311">
                                                            
                                                            <div class="tabbable-line">
                                                                <ul class="nav nav-tabs ">
                                                                    <li class="active">
                                                                        <a href="#tab_default_3111" data-toggle="tab">
                                                                            TOUS
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#tab_default_3112" data-toggle="tab">
                                                                            DEPOT
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#tab_default_3113" data-toggle="tab">
                                                                            RETRAIT
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" id="tab_default_3111">
                                                                        <ul class="nav">
                                                                            <li>
                                                                                <!-- <a> TOUS LES OPERATIONS  :  
                                                                                    <span style="color:green;">Valeur =  <span > <script> $('#id_PaiementTo').val()+ $('#id_PaiementTo').val() + $('#id_PaiementTo').val();</script>FCFA</span>
                                                                                </a> -->
                                                                            </li>
                                                                        </ul> 
                                                                        <div class="table-responsive">
                                                                            <label class="pull-left" for="nbCompteWave">Nombre entrées </label>
                                                                            <select class="pull-left" id="nbCompteWave">
                                                                            <optgroup>
                                                                                <option value="10">10</option>
                                                                                <option value="20">20</option>
                                                                                <option value="50">50</option> 
                                                                            </optgroup>       
                                                                            </select>
                                                                            <input class="pull-right" type="text" name="" id="searchInputCompteWave" placeholder="Rechercher..." autocomplete="off">
                                                                            <div id="tableCompteWave"><!-- content will be loaded here --></div>	
                                                                        </div> 
                                                                    </div>
                                                                    <div class="tab-pane" id="tab_default_3112">
                                                                        <ul class="nav">
                                                                            <li class="active">
                                                                                <!-- <a> TOUS LES DEPOTS:  
                                                                                    <span style="color:green;">Valeur  =  <span id="id_SortiesBon"></span> FCFA</span>
                                                                                </a> -->
                                                                            </li>
                                                                        </ul>       
                                                                        <div class="table-responsive">
                                                                            <label class="pull-left" for="nbCompteWaveDep">Nombre entrées </label>
                                                                            <select class="pull-left" id="nbCompteWaveDep">
                                                                            <optgroup>
                                                                                <option value="10">10</option>
                                                                                <option value="20">20</option>
                                                                                <option value="50">50</option> 
                                                                            </optgroup>       
                                                                            </select>
                                                                            <input class="pull-right" type="text" name="" id="searchInputCompteWaveDep" placeholder="Rechercher..." autocomplete="off">
                                                                            <div id="tableCompteWaveDep"><!-- content will be loaded here --></div>	
                                                                        </div>  
                                                                    </div> 
                                                                    <div class="tab-pane" id="tab_default_3113">
                                                                        <ul class="nav">
                                                                            <li class="active">
                                                                                <!-- <a> TOUS LES RETRAITS:  
                                                                                    <span style="color:green;">Valeur  =  <span id="id_SortiesBon"></span> FCFA</span>
                                                                                </a> -->
                                                                            </li>
                                                                        </ul>       
                                                                        <div class="table-responsive">
                                                                            <label class="pull-left" for="nbCompteWaveRet">Nombre entrées </label>
                                                                            <select class="pull-left" id="nbCompteWaveRet">
                                                                            <optgroup>
                                                                                <option value="10">10</option>
                                                                                <option value="20">20</option>
                                                                                <option value="50">50</option> 
                                                                            </optgroup>       
                                                                            </select>
                                                                            <input class="pull-right" type="text" name="" id="searchInputCompteWaveRet" placeholder="Rechercher..." autocomplete="off">
                                                                            <div id="tableCompteWaveRet"><!-- content will be loaded here --></div>	
                                                                        </div> 
                                                                    </div> 
                                                                </div> 
                                                            </div>      
                                                        </div>
                                                        <div class="tab-pane" id="tab_default_312">
                                                            <ul class="nav">
                                                                <li class="active">
                                                                    <!-- <a> Operation Orange Money:  
                                                                        <span style="color:green;">Valeur Stock (PU) =  <span id="id_SortiesBon"></span> FCFA</span>
                                                                    </a> -->
                                                                </li>
                                                            </ul>       
                                                            <div class="table-responsive">
                                                                <label class="pull-left" for="nbCompteOMoney">Nombre entrées </label>
                                                                <select class="pull-left" id="nbCompteOMoney">
                                                                <optgroup>
                                                                    <option value="10">10</option>
                                                                    <option value="20">20</option>
                                                                    <option value="50">50</option> 
                                                                </optgroup>       
                                                                </select>
                                                                <input class="pull-right" type="text" name="" id="searchInputCompteOMoney" placeholder="Rechercher..." autocomplete="off">
                                                                <div id="tableCompteOMoney"><!-- content will be loaded here --></div>	
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab_default_32">
                                                <ul class="nav">
                                                    <li class="active">
                                                        <!-- <a> LISTE DES BONS EN ESPECES :  
                                                            <span style="color:green;">Valeur Montant =  <span id="id_RapportBonsEspeces"></span> FCFA</span>
                                                        </a> -->
                                                    </li>
                                                </ul>       
                                                <div class="table-responsive">
                                                    <label class="pull-left" for="nbCompteBancaire">Nombre entrées </label>
                                                        <select class="pull-left" id="nbCompteBancaire">
                                                            <optgroup>
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option> 
                                                            </optgroup>       
                                                        </select>
                                                    <input class="pull-right" type="text" name="" id="searchInputCompteBancaire" placeholder="Rechercher..." autocomplete="off">
                                                    <div id="tableCompteBancaire"><!-- content will be loaded here --></div>	
                                                </div>  
                                            </div> 
                                            <div class="tab-pane" id="tab_default_33">
                                                <ul class="nav">
                                                    <li class="active">
                                                        <!-- <a> LISTE DES BONS EN ESPECES :  
                                                            <span style="color:green;">Valeur Montant =  <span id="id_RapportBonsEspeces"></span> FCFA</span>
                                                        </a> -->
                                                    </li>
                                                </ul>       
                                                <div class="table-responsive">
                                                    <label class="pull-left" for="nbCompteCheque">Nombre entrées </label>
                                                        <select class="pull-left" id="nbCompteCheque">
                                                            <optgroup>
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option> 
                                                            </optgroup>       
                                                        </select>
                                                    <input class="pull-right" type="text" name="" id="searchInputCompteCheque" placeholder="Rechercher..." autocomplete="off">
                                                    <div id="tableCompteCheque"><!-- content will be loaded here --></div>	
                                                </div> 
                                            </div>
                                            <div class="tab-pane" id="tab_default_34">
                                                <ul class="nav">
                                                    <li class="active">
                                                        <!-- <a> LISTE OPERATIONS DANS LA CAISSE :  
                                                            <span style="color:green;">Valeur Montant =  <span id="id_RapportBonsEspeces"></span> FCFA</span>
                                                        </a> -->
                                                    </li>
                                                </ul>       
                                                <div class="table-responsive">
                                                    <label class="pull-left" for="nbCompteCaisse">Nombre entrées </label>
                                                        <select class="pull-left" id="nbCompteCaisse">
                                                            <optgroup>
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option> 
                                                            </optgroup>       
                                                        </select>
                                                    <input class="pull-right" type="text" name="" id="searchInputCompteCaisse" placeholder="Rechercher..." autocomplete="off">
                                                    <div id="tableCompteCaisse"><!-- content will be loaded here --></div>	
                                                </div> 
                                            </div>
                                        </div>
                                    </div>                
                                </div>
                                
                                <div class="tab-pane" id="tab_default_4">
                                    <ul class="nav">
                                        <li class="active">
                                            <!-- <a> LISTE DES PRET :  
                                                <span style="color:green;">Valeur Montant =  <span id="id_RapportBonsEspeces"></span> FCFA</span>
                                            </a> -->
                                        </li>
                                    </ul>       
                                    <div class="table-responsive">
                                        <label class="pull-left" for="nbComptePret">Nombre entrées </label>
                                        <select class="pull-left" id="nbComptePret">
                                              <optgroup>
                                              <option value="10">10</option>
                                              <option value="20">20</option>
                                              <option value="50">50</option> 
                                            </optgroup>       
                                        </select>
                                        <input class="pull-right" type="text" name="" id="searchInputComptePret" placeholder="Rechercher..." autocomplete="off">
                                        <div id="tableComptePret"><!-- content will be loaded here --></div>	
                                    </div>  
                                </div>
                               <!--  <div class="tab-pane" id="tab_default_5">       
                                            <ul class="nav">
                                                <li class="active">
                                                    <a> LISTE DES PAIEMENTS : 
                                                        <span style="color:green;"> Valeur Stock (PS) = <span id="id_EntreesPA"></span> FCFA</span> <=>  
                                                        <span style="color:green;">Valeur Stock (PP) =  <span id="id_EntreesPU"></span> FCFA</span>
                                                    </a>
                                                </li>
                                            </ul> 
                                            <div class="table-responsive">
                                                <table id="tablePretPEntité" class="display tabStock" class="tableau3"  width="100%" border="1">
                                                    <thead>
                                                    <tr>
                                                        <th>ORDRE</th>
                                                        <th>NOM COMPTE</th>
                                                        <th>NUMERO COMPTE</th>
                                                        <th>MONTANT</th>
                                                        <th>OPERATIONS</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                </div> -->
                                 
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