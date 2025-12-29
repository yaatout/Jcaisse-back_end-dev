<?php 
session_start();
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;

$nomtableClient=$_SESSION['nomB']."-client";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableVersement=$_SESSION['nomB']."-versement";

if(!$_SESSION['iduser']){
	header('Location:index.php');
	}

	$idClient=htmlspecialchars(trim($_GET['c'])); 
	$sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$idClient."";
	$res3 = mysql_query($sql3) or die ("persoonel requête 3".mysql_error());
	$client = mysql_fetch_array($res3);


if (isset($_POST['btnEnregistrerVersement'])) {

		$date = new DateTime();
		$annee =$date->format('Y');
		$mois =$date->format('m');
		$jour =$date->format('d');
		$dateVersement=$jour.'-'.$mois.'-'.$annee;

		$montant=htmlspecialchars(trim($_POST['montant']));
		
		$sql2="insert into `".$nomtableVersement."` (idClient,montant,dateVersement) values('".$idClient."','".$montant."','".$dateVersement."')";
  		$res2=mysql_query($sql2) or die ("insertion Versement impossible =>".mysql_error());

  		$solde=$montant+$client['solde'];
  		


  		$sql3="UPDATE `".$nomtableClient."` set solde='".$solde."' where idClient=".$idClient;
		$res3=mysql_query($sql3) or die ("update solde client impossible =>".mysql_error());

		$message="Client ajouter avec succes";
		
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
</head>
<body>
	<?php 
		  require('header.php');
		?>
		<div class="container">
		
			<h3> Les versements du client : <?php echo $client['prenom']." ".strtoupper($client['nom']); ?> </h3>
			
			
   			
	        <div class="row">
	        	<div class="col-lg-8">
	        		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addVer">
                        <i class="glyphicon glyphicon-plus"></i>Versement
   					</button><br><br>
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
									      <input type="text" class="form-control" id="montant" name="montant" placeholder="Montant">
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

        			<div id="monaccordeon2" class="panel-group ">
						
	        		
							<?php 		
								$sql4="SELECT * FROM `".$nomtableVersement."` where idClient=".$idClient." ORDER BY idVersement DESC";
								$res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
									while ($versement = mysql_fetch_array($res4)) { ?>
										
										<div class="panel panel-info">
											<div class="panel-heading">
												<h3 class="panel-title">
												<a class="accordion-toggle" <?php echo  "href=#item2".$versement['idVersement']."" ; ?>
												  dataparent="#monaccordeon2"  data-toggle="collapse"> Versement n° <?php echo $versement['idVersement']; ?></a>
												</h3>

											</div>
											<div  <?php echo  "id=item2".$versement['idVersement']."" ; ?> class="panel-collapse collapse ">
												<div class="panel-body"> 
													<table class="table ">
														<thead>
															<tr>
																<th><h5>Montant</h5></th>
																<th><h5>date versement</h5></th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td><?php echo  $versement['montant']; ?></td>
																<td><?php echo  $versement['dateVersement']; ?></td>
															</tr>
														</tbody>
													</table>
													
												</div>
											</div>
										</div>

							<?php 	}?>
									
					</div>
	        	</div>
			</div>

</body>
</html>