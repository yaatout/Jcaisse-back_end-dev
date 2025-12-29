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

$somme12=0;
    //     $sqlDV="SELECT SUM(montantFixePayement)	FROM `aaa-payement-salaire`	where aPayementBoutique=1";
    //    // var_dump($sqlDV);
    //     $res2=mysql_query($sqlDV) or die ("select stock impossible =>".mysql_error());
    //     $S2 = mysql_fetch_array($res2);

		$sql30 = $bdd->prepare("SELECT sum(montantFixePayement) as total FROM `aaa-payement-salaire` WHERE aPayementBoutique=:a"); 
        $sql30->execute(array('a' =>1 ))  or die(print_r($sql30->errorInfo()));                                                         
        $total =$sql30->fetch();


		// $somme12 = $S2[0];
		$somme12 =$total['total']
?>


		<div class="row">
			<div class="">
				<div class="card " style=" ;">
				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				  <div class="card-body">
                  <div class="container">
                 <center>
					<div class="jumbotron noImpr">
						<input type="date">
						<h2>Mois : <?php echo $mois."-".$annee; ?></h2>

						<!-- <p>Total Paiements Effectifs n1: <font color="red"><?php echo $somme; ?> FCFA</font></p> -->
						<p>Total Paiements Effectifs : <font color="red"><?php echo $somme12; ?> FCFA</font></p>

					</div>
                </center>
	<?php
	if($_SESSION['profil']=="SuperAdmin"){ ?>
	<center>
		<div class="modal-body">
			<form name="formulairePayementBoutiques" method="post" action="payementboutiques.php">
			  <div>
				<!--button type="submit" name="payementBoutiques" class="btn btn-success"> Recalcul des paiements </button-->
			   </div>
			</form>
		</div>
	</center>
<?php
	} ?>

		<!-- Debut Message d'Alerte concernant le Stock du Produit avant la vente -->
			<div id="msg_info_js" class="modal fade " role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header panel-primary">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Message</h4>
						</div>
						<div class="modal-body">
							<p>
							<br>Facture <span id="id_Result"></span>
							</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
						</div>
				</div>
            </div>
        <!-- Fin Message d'Alerte concernant le Stock du Produit avant la vente -->
				
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#PAIEMENTHIST">MOIS PRECEDANT</a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="PAIEMENTHIST">" >
				<div class="table-responsive"> 
						<label class="pull-left" for="nbEntreHist">Nombre entrées </label>
						<select class="pull-left" id="nbEntreHist">
						<optgroup>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option>  
						</optgroup>       
					</select>
					<input class="pull-right" type="text" name="" id="searchInputHist" placeholder="Rechercher...">
					<div id="resultsHist"><!-- content will be loaded here --></div>
				</div>
		    </div>
		</div>
    
<script type="text/javascript" src="paiement/js/scriptPaiementHist.js"></script>