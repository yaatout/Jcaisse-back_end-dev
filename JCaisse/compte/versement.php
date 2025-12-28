<?php
session_start();
if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');


	$idClient=htmlspecialchars(trim($_GET['c']));
	$sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient."";
	$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
	$client = mysql_fetch_array($res3);


if (isset($_POST['btnEnregistrerVersement'])) {

		$date = new DateTime();
		$annee =$date->format('Y');
		$mois =$date->format('m');
		$jour =$date->format('d');
		$heureString=$date->format('H:i:s');
		$dateVersement=$annee.'-'.$mois.'-'.$jour;

		$montant=htmlspecialchars(trim($_POST['montant']));

		$sql2="insert into `".$nomtableVersement."` (idClient,montant,dateVersement,heureVersement,iduser) values(".$idClient.",".$montant.",'".$dateVersement."','".$heureString."',".$_SESSION['iduser'].")";
		//echo $sql2;
  		$res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

  		$solde=$montant+$client['solde'];



  		$sql3="UPDATE `".$nomtableClient."` set solde=".$solde." where idClient=".$idClient;
		$res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

		$message="Client ajouter avec succes";

	}
if (isset($_POST['btnAnnulerVersement'])) {
	$idVersement=htmlspecialchars(trim($_POST['idVersement']));
	$montant=htmlspecialchars(trim($_POST['montant']));

	//on fait la suppression de cette Versement
	$sql3="DELETE FROM `".$nomtableVersement."` where idVersement=".$idVersement;
	$res3=@mysql_query($sql3) or die ("mise à jour client  impossible".mysql_error());

	//Update du solde du client
	$solde=$client['solde'] - $montant ;
  	$sql3="UPDATE `".$nomtableClient."` set solde=".$solde." where idClient=".$idClient;
	$res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

  }

require('entetehtml.php');
?>

<body>
	<?php
		  require('header.php');
		?>
		<div class="container">

			<h3 class="noImpr"> Les versements du client : <?php echo $client['prenom']." ".strtoupper($client['nom']); ?> </h3>



	        <div class="row">
	        	<div class="">
	        		<button type="button" class="btn btn-success noImpr" data-toggle="modal" data-target="#addVer">
                        <i class="glyphicon glyphicon-plus"></i>Versement
   					</button>
						<br><br>
   					<div class="modal fade" id="addVer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			            <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                    <div class="modal-header">
			                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                        <h4 class="modal-title" id="myModalLabel">Nouveau versement</h4>
			                    </div>
			                    <div class="modal-body">
				                    <form name="formulaireVersement" method="post" <?php echo  "action=versement.php?c=". $idClient."" ; ?>>
									  <div class="form-group">
									      <label for="inputEmail3" class="control-label">Montant</label>
									      <input type="number" class="form-control" id="montant" name="montant" placeholder="Montant" required="">
									      <span class="text-danger" ></span>
									  </div>
									  <div class="modal-footer">
				                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                            <button type="submit" name="btnEnregistrerVersement" class="btn btn-primary">Enregistrer</button>
				                       </div>
									</form>
			                    </div>

			                </div>
			            </div>
        			</div>

        			<div id="monaccordeon2" class="panel-group col-lg-7 col-md-7 col-sm-7">


							<?php
								$sql4="SELECT * FROM `".$nomtableVersement."` where idClient=".$idClient." ORDER BY idVersement DESC";
								$res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
									while ($versement = mysql_fetch_array($res4)) { ?>

										<div class="panel panel-info" <?php echo  "id=panel".$versement['idVersement']."" ; ?>>
											<div class="panel-heading noImpr">
												<h3 class="panel-title">
												<a class="accordion-toggle" <?php echo  "href=#item2".$versement['idVersement']."" ; ?> onclick='displayVersement("<?php echo  $versement['idVersement']; ?>");'
												  dataparent="#monaccordeon2"  data-toggle="collapse"> Versement n° <?php echo $versement['idVersement']; ?><?php echo "-".$idClient  ?> </a>
												</h3>

											</div>
											<div  <?php echo  "id=item2".$versement['idVersement']."" ; ?> class="panel-collapse collapse ">
												<div class="panel-body">
													
													 <form class="form-inline noImpr"  id="factForm" method="post"  >
														<input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>
														<input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>
														  <button type="submit" name="btnAnnulerVersement"	 class="btn btn-danger pull-right" 	 >
															<span class="glyphicon glyphicon-remove"></span>Annuler
														 </button>
													</form>
													<form class="form-inline pull-right noImpr" style="margin-right:20px;"
													 method="post" action="barcodeFacture.php" >
														<input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
																				 <button type="submit" class="btn btn-success pull-right" data-toggle="modal" name="barcodeFactureV">
																								<span class="glyphicon glyphicon-lock"></span>Facture
																				 </button>
													 </form>
													 
					<div <?php echo  "id=divImpVer".$versement['idVersement']."" ; ?>  >

						<div >
						
						Date : <?php 
						
						$date1=$versement['dateVersement'];
						
						$date2 = new DateTime($date1);
							//R�cup�ration de l'ann�e
							$annee =$date2->format('Y');
							//R�cup�ration du mois
							$mois =$date2->format('m');
							//R�cup�ration du jours
							$jour =$date2->format('d');
						$date=$jour.'-'.$mois.'-'.$annee;
											
						
						echo $date; ?>Heure : <?php 
						
						$heureV=$versement['heureVersement'];
															
						
						echo $heureV; ?><br/>
							<?php echo  '********************************************* <br/>'; ?>
							 <?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
							<?php echo  $_SESSION['adresseB'] ;?>
							 <?php echo  '<br/>*********************************************'; ?>
						</div>
						<div> </div>

						<div><b>Montant : <?php echo  $versement['montant']; ?></b></div>
						
						<div class="divFacture" style="display:none;">

							N° <?php echo $versement['idVersement']; ?> <?php echo "-".$idClient  ?>
							<span class="spanDate"> <?php echo $versement['dateVersement']; ?> </span>

						</div>
						 <div  align="center"> A BIENTOT !</div>
					</div>
				</div>
			</div>
		</div>

							<?php 	}?>

					</div>

                </div>
			</div>

</body>
</html>
