<?php
/*
*/
//echo 'dans    ';

$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$heureString=$date->format('H:i:s');

$dateString=$annee.'-'.$mois.'-'.$jour;
//var_dump($dateString);
$dateString2=$jour.'-'.$mois.'-'.$annee;

$dateHeures=$dateString.' '.$heureString;
$messageDel='';

if($_SESSION['profil']!="SuperAdmin" AND $_SESSION['profil']!="Assistant")
    header('Location:admin-map.php');



?>
<div class="container" >



<div class="col-sm-4 pull-left" >
    <a  class="btn btn-warning  pull-left" style="margin-top:8px;" href="compte/?p=compte" > Retour </a>
</div>


<div class="jumbotron noImpr" id="resumeAllOperation" >
   
              
</div>
        <?php if($messageDel!=''){
                echo '<center>
                            <div class="row">
                                <div class="alert alert-danger">
                                    <strong>'.$messageDel.' </strong>
                                </div>
                            </div>
                        </center>
                    ';
            }?> 
    
        <div >
         
                
                    <br>
                    
                    
            
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
       <div id="listerAllOperations"><!-- content will be loaded here --></div>
    </div>
<!-- Fin de l'Accordion pour Tout les Paniers -->
            
</div>

<script type="text/javascript" src="compte/js/scriptCompteAllOperation.js"></script>    
