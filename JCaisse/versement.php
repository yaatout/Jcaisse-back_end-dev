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
		$timezone = new DateTimeZone('Africa/Dakar');
		$date->setTimezone($timezone);

		$annee =$date->format('Y');
		$mois =$date->format('m');
		$jour =$date->format('d');
		$heureString=$date->format('H:i:s');
		$dateVersement=$jour.'-'.$mois.'-'.$annee;

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
			<?php
				$sql2="SELECT * FROM `".$nomtablePagnet."` where idClient='".$idClient."'  ORDER BY idPagnet DESC";
				$res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
				$total=0;
				while ($pagnet0 = mysql_fetch_array($res2))
				$total+=$pagnet0['apayerPagnet'];

				$T_solde=0;

				$sql12="SELECT solde FROM `".$nomtableClient."` where idClient=".$idClient." ";
				$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
				$versement = mysql_fetch_array($res12) ;
				$T_solde=$versement[0] - $total ;
			?>

			<div class="jumbotron noImpr">
				<div class="col-sm-2 pull-right" >
					<form name="formulaireInfo" id="formulaireInfo" method="post" action="ajax/designationInfo.php">
							<div class="form-group" >
								<input type="text" class="form-control" placeholder="Recherche Prix..." id="designationInfo" name="designation" size="100"/>
								<div id="reponseS"></div>
								<div id="resultatS"></div>
								
							</div>
					</form>
				</div>
				<h2>Les versements du client  : <?php echo $client['prenom']." ".strtoupper($client['nom']); ?> </h2>
					<h6>Solde : <?php echo ($T_solde * $_SESSION['devise']).' '.$_SESSION['symbole']; ?>   </h6>
					<?php $sql12="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient=".$idClient." ";
					$res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
									$Total = mysql_fetch_array($res12) ; ?>
				<p>Total des Versements : <?php 
					if($Total[0]!=null){
						echo ($Total[0] * $_SESSION['devise']).' '.$_SESSION['symbole'];
					}
					else{
						echo '0 '.$_SESSION['symbole'];
					}
				?>   
				</p>

			</div>

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
				<div class="panel-group" id="accordion">

					<?php
					/**Debut informations sur la date d'Aujourdhui **/
						$date = new DateTime();
						$annee =$date->format('Y');
						$mois =$date->format('m');
						$jour =$date->format('d');
						$heureString=$date->format('H:i:s');
						$dateString2=$jour.'-'.$mois.'-'.$annee;
					/**Fin informations sur la date d'Aujourdhui **/

					// On détermine sur quelle page on se trouve
					if(isset($_GET['page']) && !empty($_GET['page'])){
						$currentPage = (int) strip_tags($_GET['page']);
					}else{
						$currentPage = 1;
					}
					// On détermine le nombre d'articles par page
					$parPage = 10;

					/**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
						$sqlC="SELECT COUNT(*) FROM `".$nomtableVersement."` where idClient=".$idClient." ORDER BY idVersement DESC";
						$resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
						$nbre = mysql_fetch_array($resC) ;
						$nbPaniers = (int) $nbre[0];
					/**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

					// On calcule le nombre de pages total
					$pages = ceil($nbPaniers / $parPage);
                                
					$premier = ($currentPage * $parPage) - $parPage;

					/**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
						$sqlP1="SELECT * FROM `".$nomtableVersement."` where idClient='".$idClient."' ORDER BY idVersement DESC LIMIT ".$premier.",".$parPage." ";
						$resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
					/**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/


					?>         

					<!-- Debut Boucle while concernant les Paniers Vendus -->
						<?php $n=$nbPaniers - (($currentPage * 10) - 10); while ($versement = mysql_fetch_array($resP1)) {   ?>

							<div style="padding-top : 2px;" class="panel panel-warning">
								<div class="panel-heading">
									<h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$versement['idVersement']."" ; ?>  class="panel-title expand">
									<div class="right-arrow pull-right">+</div>
									<a href="#"> Versement <?php echo $n; ?>
										<span class="spanDate noImpr"> </span>
										<span class="spanDate noImpr"> Date: <?php echo $versement['dateVersement']; ?> </span>
										<span class="spanDate noImpr">Heure: <?php echo $versement['heureVersement']; ?></span>
										<span class="spanDate noImpr">Montant: <?php echo ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?></span>
										<span class="spanDate noImpr"> Facture : #<?php echo $versement['idVersement']; ?></span>
									</a>
									</h4>
								</div>
								<div class="panel-collapse collapse " <?php echo  "id=versement".$versement['idVersement']."" ; ?> >
									<div class="panel-body" >
										<?php if ($versement['iduser']==$_SESSION['iduser']){ ?>
											<button type="submit" class="btn btn-danger pull-right" data-toggle="modal" <?php echo  "data-target=#msg_anl_versement".$versement['idVersement'] ; ?>	 >
												<span class="glyphicon glyphicon-remove"></span>Annuler
											</button>
										<?php }
										else {  ?>
											<button disabled="true" type="submit" class="btn btn-danger pull-right" data-toggle="modal" >
												<span class="glyphicon glyphicon-remove"></span>Annuler
											</button>
										<?php }?>
										
										<div class="modal fade" <?php echo  "id=msg_anl_versement".$versement['idVersement'] ; ?> role="dialog">
											<div class="modal-dialog">
												<!-- Modal content-->
												<div class="modal-content">
													<div class="modal-header panel-primary">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">Confirmation</h4>
													</div>
													<form class="form-inline noImpr"  id="factForm" method="post"  >
														<div class="modal-body">
															<p><?php echo "Voulez-vous annuler le versement numéro <b>".$n."<b>" ; ?></p>
															<input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?>>
															<input type="hidden" name="montant" id="montant"  <?php echo  "value=".$versement['montant']."" ; ?>>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															<button type="submit" name="btnAnnulerVersement" class="btn btn-success">Confirmer</button>
														</div>
													</form>
												</div>
											</div>
										</div>

										<form class="form-inline pull-right noImpr" style="margin-right:20px;"
											method="post"  >
											<input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
											
											<?php if ($_SESSION['caissier']==1){ ?>
											
											<button disabled="true" type="submit" class="btn btn-warning pull-right" data-toggle="modal" name="barcodeFactureV">
											<span class="glyphicon glyphicon-lock"></span>Facture
											</button>
											
											<?php } ?>
											
										</form>

										<form class="form-inline pull-right noImpr" style="margin-right:20px;"
											method="post" action="barcodeFacture.php" target="_blank"  >
											<input type="hidden" name="idVersement" id="idVersement"  <?php echo  "value=".$versement['idVersement']."" ; ?> >
											
											<?php if ($_SESSION['caissier']==1){ ?>
											
											<button type="submit" class="btn btn-info pull-right" data-toggle="modal" name="barcodeFactureV">
											<span class="glyphicon glyphicon-lock"></span>Ticket de Caisse
											</button>
											
											<?php } ?>
											
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
																
												echo $date; ?> <br/>Heure : <?php 

												$heureV=$versement['heureVersement'];

												echo $heureV; ?><br/>
												<?php echo  '********************************************* <br/>'; ?>
												<?php echo  '<b> '.$_SESSION['labelB'].'</b><br/>'; ?>
												<?php echo  $_SESSION['adresseB'] ;?>
												<?php echo  '<br/>*********************************************'; ?>
											</div>
											<div> </div>
											<div><b>Montant : <?php echo  ($versement['montant'] * $_SESSION['devise']).' '.$_SESSION['symbole']; ?></b></div>
											<div class="divFacture" style="display:none;">
											N° <?php echo $versement['idVersement']; ?> <?php echo "-".$idClient  ?>
											<span class="spanDate"> <?php echo $versement['dateVersement']; ?> </span>
										</div>
										<div  align="center"> A BIENTOT !</div>
									</div>
								</div>
							</div>

						<?php $n=$n-1;   } ?>
						<?php if($nbPaniers >= 11){ ?>
							<ul class="pagination pull-right">
								<!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
								<li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
									<a href="versement.php?c=<?= $idClient; ?>&&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
								</li>
								<?php for($page = 1; $page <= $pages; $page++): ?>
									<!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
									<li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
										<a href="versement.php?c=<?= $idClient; ?>&&page=<?= $page ?>" class="page-link"><?= $page ?></a>
									</li>
								<?php endfor ?>
									<!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
									<li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
									<a href="versement.php?c=<?= $idClient; ?>&&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
								</li>
							</ul>
						<?php } ?>
					<!-- Fin Boucle while concernant les Paniers Vendus  -->

				</div>
				<!-- Fin de l'Accordion pour Tout les Paniers -->

		</div>

</body>
</html>
