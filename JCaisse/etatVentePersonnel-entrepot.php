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

// var_dump($_SESSION['idBoutique']);

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
require('connection.php');
require('connectionPDO.php');

require('declarationVariables.php');


$beforeTime = '00:00:00';
$afterTime = '06:00:00';

    // var_dump(date('d-m-Y',strtotime("-1 days")));

if($_SESSION['Pays']=='Canada'){  
	$date = new DateTime();
	$timezone = new DateTimeZone('Canada/Eastern');
}
else{
	$date = new DateTime();
	$timezone = new DateTimeZone('Africa/Dakar');
}
$date->setTimezone($timezone);
$heureString=$date->format('H:i:s');

// if ($heureString >= $beforeTime && $heureString < $afterTime) {
//    	// var_dump ('is between');
// 	$date = new DateTime (date('d-m-Y',strtotime("-1 days")));
// }

// $date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateHeures=$dateString.' '.$heureString;

$uploaded=0;


$sqlU = "SELECT * FROM `aaa-utilisateur` where idutilisateur=".$_SESSION['iduser'];
$resU = mysql_query($sqlU) or die ("persoonel requête 2".mysql_error());
$user = mysql_fetch_array($resU);

$iduser = $user['idutilisateur'];

$finDefault = $annee.'-'.$mois.'-'.$jour;
$dateFin = new DateTime (date('d-m-Y',strtotime("-30 days")));
$dateFin->setTimezone($timezone);
$anneeF =$dateFin->format('Y');
$moisF =$dateFin->format('m');
$jourF =$dateFin->format('d');
$debutDefault=$anneeF.'-'.$moisF.'-'.$jourF;



?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');


?>

    <div class="container">
    
        <div class="" id="personnelDiv" align="center">
            <br>
            <label for=""><b>Personnel</b> </label>
            <input type="text" name="personnelAuto" id="personnelAuto" class="form-control personnelAuto" style="width:20%" autocomplete="off">

        </div> <br> 
        <div class="container" align="center">
          <form class="form-inline" method="POST" action="#">
            <div class="form-group mx-sm-3 mb-2">
              <input type="date" class="form-control" name="dateSelected" id="input_dateDebut" value="<?= $debutDefault?>" required>
              <input type="date" class="form-control" name="dateSelected" id="input_dateFin" value="<?= $finDefault?>" required>
            </div>
            <button type="button" id="dateSelectedValidate" class="btn btn-primary mb-2"><i class="glyphicon glyphicon-ok"></i></button>
          </form>
        </div><br><br>
                    
        <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
        <script >
            $(function () {
                $(document).on("keyup", ".personnelAuto", function (e) {
                    e.preventDefault();
                    // idPanier = $(this).attr('data-idPanier');

                    var query = $(this).val();
                    if (query.length > 0) {
                    // console.log(1)
                    // alert(1)

                    $(this).typeahead({
                        source: function (query, result) {
                        $.ajax({
                            url: "ajax/operationPersonnelAjax.php",

                            method: "POST",

                            data: {
                            personnelAuto: "personnelAuto",

                            query: query,
                            },

                            dataType: "json",

                            success: function (data) {
                            console.log(data);
                            result(
                                $.map(data, function (item) {
                                return item;
                                })
                            );
                            },

                            error: function (err) {
                            console.log(err);
                            },
                        });
                        },
                    });
                    }

                    $(".personnelAuto").focus();

                    /*********** Modification **************/
                });
            });
            $(function() {
                
                /*************** Début lister Dépenses ***************/
                
                        nbEntreeEVT = $('#nbEntreeEVT').val()
                        $(".loading-gif").show();

                        // $("#etatVenteEntrepotP").empty();

                        debut=$('#input_dateDebut').val();
                        fin=$('#input_dateFin').val();
                        idP=0;

                        $("#etatVenteEntrepotP").load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":1,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":""}, function(){ //get content from PHP page
                            $(".loading-gif").hide(); //once done, hide loading element
                        });
                        //executes code below when user click on choice date button
                        $("#dateSelectedValidate").on( "click", function (e){
                            // $("#etatVenteEntrepotP").on( "click", function (e){
                            e.preventDefault();
                            $("#etatVenteEntrepotP").hide(); //hide content element
                            $(".loading-gif").show(); //show loading element
                            nbEntreeEVT = $('#nbEntreeEVT').val()
                            query = $('#searchInputEVT').val();

                            debut=$('#input_dateDebut').val();
                            fin=$('#input_dateFin').val();
                            idP=$('#personnelAuto').val().split(' . ')[0];
                            // alert(idP);
                            
                            // alert(debut+' / '+fin)

                            if (query.length == 0) {
                                $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":1,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":""}, function(){ //get content from PHP page
                                    $(".loading-gif").hide(); //once done, hide loading element
                                    $("#etatVenteEntrepotP").show(); //show content element
                                });
                                    
                            }else{
                                $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":1,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":query}, function(){ //get content from PHP page
                                    $(".loading-gif").hide(); //once done, hide loading element
                                    $("#etatVenteEntrepotP").show(); //show content element
                                });
                            }
                            // $("#etatVenteEntrepotP").load("ajax/etatVentePersonnel-EntrepotAjax.php",{"page":page,"operation":1}
                        });
                        //executes code below when user click on pagination links
                        $("#etatVenteEntrepotP").on( "click", ".pagination a", function (e){
                            // $("#etatVenteEntrepotP").on( "click", function (e){
                            e.preventDefault();
                            $("#etatVenteEntrepotP").hide(); //hide content element
                            $(".loading-gif").show(); //show loading element
                            page = $(this).attr("data-page"); //get page number from link
                            nbEntreeEVT = $('#nbEntreeEVT').val()
                            query = $('#searchInputEVT').val();

                            debut=$('#input_dateDebut').val();
                            fin=$('#input_dateFin').val();
                            idP=$('#personnelAuto').val().split(' . ')[0];

                            if (query.length == 0) {
                                $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"page":page,"operation":1,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":""}, function(){ //get content from PHP page
                                    $(".loading-gif").hide(); //once done, hide loading element
                                    $("#etatVenteEntrepotP").show(); //show content element
                                    
                                });
                                    
                            }else{
                                $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"page":page,"operation":1,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":query}, function(){ //get content from PHP page
                                    $(".loading-gif").hide(); //once done, hide loading element
                                    $("#etatVenteEntrepotP").show(); //show content element
                                });
                            }
                            // $("#etatVenteEntrepotP").load("ajax/etatVentePersonnel-EntrepotAjax.php",{"page":page,"operation":1}
                        });

                        $('#searchInputEVT').on("keyup", function(e) {
                            e.preventDefault();
                            
                            query = $('#searchInputEVT').val()
                            nbEntreeEVT = $('#nbEntreeEVT').val()

                            debut=$('#input_dateDebut').val();
                            fin=$('#input_dateFin').val();
                            idP=$('#personnelAuto').val().split(' . ')[0];

                            var keycode = (e.keyCode ? e.keyCode : e.which);
                            if (keycode == '13') {
                                // alert(1111)
                                t = 1; // code barre
                                
                                if (query.length > 0) {
                                    
                                    $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":3,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":query,"cb":t}); //load initial records
                                }else{
                                    $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":3,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":"","cb":t}); //load initial records
                                }
                            }else{
                                // alert(2222)
                                t = 0; // no code barre
                                setTimeout(() => {
                                    if (query.length > 0) {
                                        
                                        $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":3,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":query,"cb":t}); //load initial records
                                    }else{
                                        $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":3,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":"","cb":t}); //load initial records
                                    }
                                }, 100);
                            }
                        });

                        $('#nbEntreeEVT').on("change", function(e) {
                            e.preventDefault();

                            nbEntreeEVT = $('#nbEntreeEVT').val()
                            query = $('#searchInputEVT').val();

                            debut=$('#input_dateDebut').val();
                            fin=$('#input_dateFin').val();
                            idP=$('#personnelAuto').val().split(' . ')[0];

                            if (query.length == 0) {
                                $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":4,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":""}); //load initial records
                                    
                            }else{
                                $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"idP":idP,"operation":4,"debut":debut,"fin":fin,"nbEntreeEVT":nbEntreeEVT,"query":query}); //load initial records
                            }
                                
                            // $("#etatVenteEntrepotP" ).load( "ajax/etatVentePersonnel-EntrepotAjax.php",{"operation":4,"nbEntreeEVT":nbEntreeEVT}); //load initial records
                        });
                /*************** Fin lister Dépenses ***************/
                })
        </script>
        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->
        
        
        <div class="table-responsive">
            <label class="pull-left" for="nbEntreeEVT">Nombre entrées </label>
            <select class="pull-left" id="nbEntreeEVT">
            <optgroup>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option> 
                <option value="100">100</option> 
                <option value="150">150</option> 
                <option value="200">200</option> 
            </optgroup>       
            </select>
            <input class="pull-right" type="text" name="" id="searchInputEVT" placeholder="Rechercher..." autocomplete="off">
            <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
            <div id="etatVenteEntrepotP"><!-- content will be loaded here --></div>	
        </div>
        
    </div>