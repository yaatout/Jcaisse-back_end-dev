<?php
/*
Résum� :
Commentaire :
Version : 2.1
see also :
Auteur : Ibrahima DIOP
Date de cr�ation : 5-08-2019
Date derni�re modification :  17-10-2019
*/

session_start();

require('connection.php');

require('declarationVariables.php');

if(!$_SESSION['iduserBack'])
	header('Location:index.php');

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

    require('entetehtml.php');
    ?>
    <body>

<?php   require('header.php');


?>

<body>
    

    <div class="row">
        <div class="">
            <div class="card " style=" ;">
              <!-- Default panel contents
              <div class="card-header text-white bg-success">Liste du personnel</div>-->
              <div class="card-body">
                <div class="container">
                    <center>
                    <?php
                        $somme=0;
                        $mois2=$mois-1;
                        $sql1="SELECT * FROM `aaa-payement-salaire`  WHERE ( datePS  NOT LIKE '%".$annee."-".$mois."%' ) and aPayementBoutique=0";
                        //  $sql1="SELECT idBoutique FROM `aaa-payement-salaire` WHERE `datePS` LIKE '%".$annee."-0".$mois2."%' and aPayementBoutique=0" ;
                        $res1 = mysql_query($sql1) or die ("etape requête 4".mysql_error());
                        while($boutiqueP=mysql_fetch_array($res1)) {
                            $sql4="SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =".$boutiqueP["idBoutique"]." order by datePS DESC LIMIT 1" ;
                            $res4 = mysql_query($sql4) or die ("etape requête 4".mysql_error());
                            while($payement=mysql_fetch_array($res4)) {
                                $somme=$somme+$payement['montantFixePayement'];
                            }
                        }
                    ?>

                        <div class="jumbotron noImpr">
                            <h2>Mois : <?php echo "".($mois-1)."-".$annee; ?></h2>

                            <p>Total Paiements Effectifs: <font color="red"><?php echo $somme; ?> FCFA</font></p>

                        </div>
                    </center>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#INGENIEURS">INGENIEURS</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="INGENIEURS">
                                <div class="table-responsive">                
									<label class="pull-left" for="nbEntreeRetardING">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeRetardING">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputRetardING" placeholder="Rechercher...">
										<!-- <input type="hidden" id="idCDPH" value="<?php echo $tab0['id']; ?>"> -->
										<div id="resultsRetardING"><!-- content will be loaded here --></div>
								</div>
                        </div>
                                
                    </div>
                </div>
            </div>
                
        </div>
    </div>
    
</body>
</html>