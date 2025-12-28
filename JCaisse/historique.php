<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de création : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
//ob_end_flush();

session_start();


if(!$_SESSION['iduser']){
	header('Location:../index.php');
}


require('connection.php');

require('declarationVariables.php');

require('entetehtml.php');

require('header.php');
?>
<body >

<div class="container" align="center">
	<?php 
	// $date = new DateTime();
	// $timezone = new DateTimeZone('Africa/Dakar');
	// $date->setTimezone($timezone);
	// $heureString=$date->format('H:i:s'); 
	/**Debut informations sur la date d'Aujourdhui **/
    $beforeTime = '00:00:00';
    $afterTime = '06:00:00';

    /**Debut informations sur la date d'Aujourdhui **/
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

    if ($heureString >= $beforeTime && $heureString < $afterTime) {
        // var_dump ('is between');
    $date = new DateTime (date('d-m-Y',strtotime("-1 days")));
    }


	$annee =$date->format('Y');
	$mois =$date->format('m');
	$jour =$date->format('d');
	$dateString2=$jour.'-'.$mois.'-'.$annee;

	if(isset($_GET['dateJour'])){
		$dateJour=@htmlspecialchars($_GET["dateJour"]);
		$suivant = date('Y-m-d', strtotime($dateJour. ' + 1 days'));
		$precedant = date('Y-m-d', strtotime($dateJour. ' - 1 days'));
	}
	else {
		$dateJour = date('Y-m-d', strtotime($dateString. ' - 1 days'));
		$suivant = date('Y-m-d', strtotime($dateJour. ' + 1 days'));
		$precedant = date('Y-m-d', strtotime($dateJour. ' - 2 days'));
	}

?>

	<input id="inpt_Historique_dateJour" type="hidden" value="<?= $dateJour ?>" />

	<div class="container" style="margin-bottom:5px">
		<a class="btn btn-default pull-left" href="historique.php?dateJour=<?php echo $precedant; ?>" title="Précédent">
			Précédent
		</a>
		<input type="date"  id="jour_date"  onchange="date_historique('jour_date');"  <?php echo '  max="'.date('Y-m-d', strtotime('-1 days')).'" ';?> value="<?php echo $dateJour;?>" name="dateInventaire" required  />
		<a class="btn btn-default pull-right" href="historique.php?dateJour=<?php echo $suivant;?>" title="Suivant">
			Suivant
		</a>
	</div>

	<table >
		<tr>
			<td>
				<div class="container">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading row" id="col" data-toggle="collapse" data-target="#collapse" aria-expanded="true" aria-controls="collapseOne">
								<h3 class="panel-title">ETAT DE LA CAISSE DU <?php echo date('d-m-Y', strtotime($dateJour)); ?></h3>
								<span><a class="btn btn-sm btn-warning pull-right" id="btn_details">Détails</a></span>
							</div>
							<img src="images/loading-gif3.gif" style="margin-left:40%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
							<div class="panel-body collapse" id="collapse_details"  aria-labelledby="headingOne">
							<table class="table table-bordered table-responsive" style="display:none" id="tableau_Bord">
									<tr>
										<td>Approvisionnement Caisse : </td>
										<td>
											<span id="montant_Approvisionnement"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_Approvisionnement" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_Approvisionnement">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Approvisionnements Caisse</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeApprovisionnement" class="display" width="100%" border="1">
																	<thead>
																	<tr>
																		<th>Heure</th>
																		<th>Description</th>
																		<th>Montant</th>
																		<th>Facture</th>
																		<th>Compte</th>
																		<th>Personnel</th>
																	</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Retrait Caisse : </td>
										<td>
											<span id="montant_Retrait"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_Retrait" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_Retrait">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Retraits Caisse</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeRetrait" class="display" width="100%" border="1">
																	<thead>
																		<tr>
																			<th>Heure</th>
																			<th>Description</th>
																			<th>Montant</th>
																			<th>Facture</th>
																			<th>Compte</th>
																			<th>Personnel</th>
																		</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Vente Caisse : </td>
										<td>
											<span id="montant_Vente_Caisse"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_VenteCaisse" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_VenteCaisse">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Ventes Caisse</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeVenteCaisse" class="display" width="100%" border="1">
																	<thead>
																	<tr>
																		<th>Heure</th>
																		<th>Reference</th>
																		<th>Quantite</th>
																		<th>Unite</th>
																		<th>Prix</th>
																		<th>Total</th>
																		<th>Facture</th>
																		<th>Personnel</th>
																	</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Vente Mobile : </td>
										<td>
											<span id="montant_Vente_Mobile"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_VenteMobile" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_VenteMobile">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Ventes Mobile</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeVenteMobile" class="display" width="100%" border="1">
																	<thead>
																	<tr>
																		<th>Heure</th>
																		<th>Reference</th>
																		<th>Quantite</th>
																		<th>Unite</th>
																		<th>Prix</th>
																		<th>Total</th>
																		<th>Facture</th>
																		<th>Compte</th>
																		<th>Personnel</th>
																	</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<?php if ($_SESSION['mutuelle']==1){ ?>
										<tr>
											<td>Vente Mutuelle : </td>
											<td>
												<span id="montant_Vente_Mutuelle"></span>&nbsp;&nbsp;
												<button class="btn btn-default btn-xs" id="btn_details_VenteMutuelle" style="margin-right:20px;" >
													<span style="color:green;font-size: 12px;">Details</span>
												</button>
												<div class="modal" id="details_VenteMutuelle">
													<div class="modal-dialog modal-lg">
														<div class="modal-content">
															<!-- Modal Header -->
															<div class="modal-header">
																<h4 class="modal-title"><b> Details des Ventes Mutuelle</b> </h4>
																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<!-- Modal body -->
															<div class="modal-body">
																<div class="table-responsive">
																	<table id="listeVenteMutuelle" class="display" width="100%" border="1">
																		<thead>
																			<tr>
																				<th>Heure</th>
																				<th>Reference</th>
																				<th>Quantite</th>
																				<th>Unite</th>
																				<th>Prix_Public</th>
																				<th>Total</th>
																				<th>Mutuelle</th>
																				<th>Facture</th>
																				<th>Compte</th>
																				<th>Personnel</th>
																			</tr>
																		</thead>
																	</table>
																</div>
															</div>
															<!-- Modal footer -->
															<div class="modal-footer">
																<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
															</div>
														</div>
													</div>
												</div>
											</td>
										</tr>
									<?php }	?>
									<tr>
										<td>Services : </td>
										<td>
											<span id="montant_Services"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_Services" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_Services">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Services</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeServices" class="display" width="100%" border="1">
																	<thead>
																		<tr>
																			<th>Heure</th>
																			<th>Description</th>
																			<th>Montant</th>
																			<th>Facture</th>
																			<th>Compte</th>
																			<th>Personnel</th>
																		</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Depenses : </td>
										<td>
											<span id="montant_Depenses"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_Depenses" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_Depenses">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Depenses</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeDepenses" class="display" width="100%" border="1">
																	<thead>
																		<tr>
																			<th>Heure</th>
																			<th>Description</th>
																			<th>Montant</th>
																			<th>Facture</th>
																			<th>Compte</th>
																			<th>Personnel</th>
																		</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Bons en Especes : </td>
										<td>
											<span id="montant_Bon_Especes"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_BonEspeces" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_BonEspeces">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Bons en Especes</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeBonEspeces" class="display" width="100%" border="1">
																	<thead>
																		<tr>
																			<th>Heure</th>
																			<th>Description</th>
																			<th>Montant</th>
																			<th>Facture</th>
																			<th>Client</th>
																			<th>Compte</th>
																			<th>Personnel</th>
																		</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Versements Clients : </td>
										<td>
											<span id="montant_Versement_Client"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_VersementClient" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_VersementClient">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Versements Clients</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeVersementClient" class="display" width="100%" border="1">
																	<thead>
																		<tr>
																			<th>Heure</th>
																			<th>Description</th>
																			<th>Montant</th>
																			<th>Facture</th>
																			<th>Client</th>
																			<th>Compte</th>
																			<th>Personnel</th>
																		</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Versements Fournisseurs : </td>
										<td>
											<span id="montant_Versement_Fournisseur"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_VersementFournisseur" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_VersementFournisseur">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Versements Fournisseurs</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeVersementFournisseur" class="display" width="100%" border="1">
																	<thead>
																		<tr>
																			<th>Heure</th>
																			<th>Description</th>
																			<th>Montant</th>
																			<th>Facture</th>
																			<th>Fournisseur</th>
																			<th>Compte</th>
																			<th>Personnel</th>
																		</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<?php if ($_SESSION['mutuelle']==1){ ?>
										<tr>
											<td>Versements Mutuelles : </td>
											<td>
												<span id="montant_Versement_Mutuelle"></span>&nbsp;&nbsp;
												<button class="btn btn-default btn-xs" id="btn_details_VersementMutuelle" style="margin-right:20px;" >
													<span style="color:green;font-size: 12px;">Details</span>
												</button>
												<div class="modal" id="details_VersementMutuelle">
													<div class="modal-dialog modal-lg">
														<div class="modal-content">
															<!-- Modal Header -->
															<div class="modal-header">
																<h4 class="modal-title"><b> Details des Versements Mutuelles</b> </h4>
																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<!-- Modal body -->
															<div class="modal-body">
																<div class="table-responsive">
																	<table id="listeVersementMutuelle" class="display" width="100%" border="1">
																		<thead>
																			<tr>
																				<th>Heure</th>
																				<th>Description</th>
																				<th>Montant</th>
																				<th>Facture</th>
																				<th>Mutuelle</th>
																				<th>Compte</th>
																				<th>Personnel</th>
																			</tr>
																		</thead>
																	</table>
																</div>
															</div>
															<!-- Modal footer -->
															<div class="modal-footer">
																<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
															</div>
														</div>
													</div>
												</div>
											</td>
										</tr>
									<?php }	?>
									<tr>
										<td>Remise : </td>
										<td>
											<span id="montant_Remise"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_Remise" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_Remise">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Remises</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeRemise" class="display" width="100%" border="1">
																	<thead>
																	<tr>
																		<th>Heure</th>
																		<th>Montant</th>
																		<th>Facture</th>
																		<th>Compte</th>
																		<th>Personnel</th>
																	</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Total Operations : </td>
										<td>
											<span id="montant_Total_Operations"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_TotalOperations" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_TotalOperations">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Operations</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<ul class="nav nav-tabs">
																<li class="active" id="listeCaisseEvent">
																	<a data-toggle="tab" href="#listeCaisseTab">Operations sur la Caisse</a>
																</li>
																<li class="" id="listeMobileEvent">
																	<a data-toggle="tab" href="#listeMobileTab">Operations sur les Comptes Mobiles </a>
																</li> 
																<li class="" id="listeCompteEvent">
																	<a data-toggle="tab" href="#listeCompteTab">Montant des Comptes</a>
																</li>     
															</ul>
															<div class="tab-content">
																<div id="listeCaisseTab" class="tab-pane fade in active">
																	<div class="table-responsive">
																		<br/>
																		<table id="listeTotalCaisse" class="display" width="100%" border="1">
																			<thead>
																			<tr>
																				<th>Personnel</th>
																				<th>Approvi sionnement</th>
																				<th>Retrait</th>
																				<th>Vente</th>
																				<th>Depense</th>
																				<th>Versement Client</th>
																				<th>Versement Fournisseur</th>
																				<th>Bon en Especes</th>
																				<th>Remise</th>
																				<th>Montant Total</th>
																			</tr>
																			</thead>
																		</table>
																	</div>
																</div>
																<div id="listeMobileTab" class="tab-pane fade">
																	<div class="table-responsive">
																	<br/>
																		<table id="listeTotalMobile" class="display" width="100%" border="1">
																			<thead>
																				<tr>
																					<th>Personnel</th>
																					<th>Approvi sionnement</th>
																					<th>Retrait</th>
																					<th>Vente</th>
																					<th>Depense</th>
																					<th>Versement Client</th>
																					<th>Versement Fournisseur</th>
																					<th>Bon en Especes</th>
																					<th>Remise</th>
																					<th>Montant Total</th>
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
																<div id="listeCompteTab" class="tab-pane fade">
																	<div class="table-responsive">
																	<br/>
																		<table id="listeTotalCompte" class="display" width="100%" border="1">
																			<thead>
																				<tr>
																					<th>Compte</th>
																					<th>Approvi sionnement</th>
																					<th>Retrait</th>
																					<th>Vente</th>
																					<th>Depense</th>
																					<th>Versement Client</th>
																					<th>Versement Fournisseur</th>
																					<th>Bon en Especes</th>
																					<th>Remise</th>
																					<th>Montant Total</th>
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>Bons Client : </td>
										<td>
											<span id="montant_Bon_Client"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_BonClient" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_BonClient">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Bons Client</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeBonClient" class="display" width="100%" border="1">
																	<thead>
																	<tr>
																		<th>Heure</th>
																		<th>Reference</th>
																		<th>Quantite</th>
																		<th>Unite</th>
																		<th>Prix</th>
																		<th>Total</th>
																		<th>Facture</th>
																		<th>Client</th>
																		<th>Personnel</th>
																	</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<?php if ($_SESSION['mutuelle']==1){ ?>
										<tr>
										<td>Bons Mutuelles : </td>
										<td>
											<span id="montant_Bon_Mutuelle"></span>&nbsp;&nbsp;
											<button class="btn btn-default btn-xs" id="btn_details_BonMutuelle" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Details</span>
											</button>
											<div class="modal" id="details_BonMutuelle">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Bons Mutuelle</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table id="listeBonMutuelle" class="display" width="100%" border="1">
																	<thead>
																		<tr>
																			<th>Heure</th>
																			<th>Reference</th>
																			<th>Quantite</th>
																			<th>Unite</th>
																			<th>Prix_Public</th>
																			<th>Total</th>
																			<th>Mutuelle</th>
																			<th>Facture</th>
																			<th>Compte</th>
																			<th>Personnel</th>
																		</tr>
																	</thead>
																</table>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" data-dismiss="modal">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<?php }	?>
									<?php if ($_SESSION['proprietaire']==1){ ?>
									<tr>
										<td>Benefices : </td>
										<td>
											<button class="btn btn-default btn-xs" id="btn_details_Benefices" style="margin-right:20px;" >
												<span style="color:green;font-size: 12px;">Calculer</span>
											</button>
											<div class="modal" id="details_Benefices">
												<div class="modal-dialog">
													<div class="modal-content">
														<!-- Modal Header -->
														<div class="modal-header">
															<h4 class="modal-title"><b> Details des Benefices</b> </h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<!-- Modal body -->
														<div class="modal-body">
															<div class="table-responsive">
																<table class="table table-bordered table-responsive" id="table_Benefices"  width="100%" border="1">
																	<thead>
																		<tr>
																			<th>Benefices Ventes</th>
																			<th>Benefices Bons</th>
																		</tr>
																	</thead>
																	<tbody>
																	<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%" class="loading-gif-1" alt="GIF" srcset="">
																		<tr>
																			<td><span id="montant_Benefices_Vente"></span></td>
																			<td><span id="montant_Benefices_Client"></span></td>
																		</tr>
																	</tbody>
																</table>
																<h5><b> Total des Benefices : <span id="montant_Benefices"></span></b> </h5>
															</div>
														</div>
														<!-- Modal footer -->
														<div class="modal-footer">
															<button type="button" class="btn btn-success" id="btn_Retour_Benefices">Fermer</button>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<?php }?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>

<?php if ($_SESSION['proprietaire']==1){ ?>
	<!-- Debut Container Details Journal -->
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="tabbable-panel">
						<div class="tabbable-line">
							<ul class="nav nav-tabs">
								<li class="active" id="listeApprovisionnemntEvent">
									<a data-toggle="tab" href="#listeApprovisionnemntTab">Approvisionnement</a>
								</li>
								<li class="" id="listeRetraitEvent">
									<a data-toggle="tab" href="#listeRetraitTab">Retrait</a>
								</li>
								<li class="" id="listeVenteCaisseEvent">
									<a data-toggle="tab" href="#listeVenteCaisseTab">Vente Caisse</a>
								</li> 
								<li class="" id="listeVenteMobileEvent">
									<a data-toggle="tab" href="#listeVenteMobileTab">Vente Mobile</a>
								</li>
								<li class="" id="listeDepensesEvent">
									<a data-toggle="tab" href="#listeDepensesTab">Depenses</a>
								</li>  
								<li class="" id="listeVersementsClientEvent">
									<a data-toggle="tab" href="#listeVersementsClientTab">Versements Client</a>
								</li> 
								<li class="" id="listeVersementsFournisseurEvent">
									<a data-toggle="tab" href="#listeVersementsFournisseurTab">Versements Fournisseur</a>
								</li> 
								<li class="" id="listeBonsClientEvent">
									<a data-toggle="tab" href="#listeBonsClientTab">Bons Cient</a>
								</li>         
							</ul>
							<div class="tab-content">
								<div id="listeApprovisionnemntTab" class="tab-pane fade in active">
									<br />
									<div class="table-responsive">
										<img src="images/loading-gif3.gif" class="loading-gif-2" alt="GIF" srcset="">
										<!-- Debut de l'Accordion pour Tout les Paniers -->
											<div class="table-responsive">
												<div id="listePanierApprovisionnement"><!-- content will be loaded here --></div>
											</div>
										<!-- Fin de l'Accordion pour Tout les Paniers -->
									</div>
								</div>
								<div id="listeRetraitTab" class="tab-pane fade">
									<br />
									<div class="table-responsive">
										<img src="images/loading-gif3.gif" class="loading-gif-2" alt="GIF" srcset="">
										<!-- Debut de l'Accordion pour Tout les Paniers -->
											<div class="table-responsive">
												<div id="listePanierRetrait"><!-- content will be loaded here --></div>
											</div>
										<!-- Fin de l'Accordion pour Tout les Paniers -->
									</div>
								</div>
								<div id="listeVenteCaisseTab" class="tab-pane fade">
									<br />
									<div class="table-responsive">
										<img src="images/loading-gif3.gif" class="loading-gif-2" alt="GIF" srcset="">
										<!-- Debut de l'Accordion pour Tout les Paniers -->
											<div class="table-responsive">
												<div id="listePanierVenteCaisse"><!-- content will be loaded here --></div>
											</div>
										<!-- Fin de l'Accordion pour Tout les Paniers -->
									</div>
								</div>
								<div id="listeVenteMobileTab" class="tab-pane fade">
									<br />
									<div class="table-responsive">
										<img src="images/loading-gif3.gif" class="loading-gif-2" alt="GIF" srcset="">
										<!-- Debut de l'Accordion pour Tout les Paniers -->
											<div class="table-responsive">
												<div id="listePanierVenteMobile"><!-- content will be loaded here --></div>
											</div>
										<!-- Fin de l'Accordion pour Tout les Paniers -->
									</div>
								</div>
								<div id="listeDepensesTab" class="tab-pane fade">
									<br />
									<div class="table-responsive">
										<img src="images/loading-gif3.gif" class="loading-gif-2" alt="GIF" srcset="">
										<!-- Debut de l'Accordion pour Tout les Paniers -->
											<div class="table-responsive">
												<div id="listePanierDepenses"><!-- content will be loaded here --></div>
											</div>
										<!-- Fin de l'Accordion pour Tout les Paniers -->
									</div>
								</div>
								<div id="listeVersementsClientTab" class="tab-pane fade">
									<br />
									<div class="table-responsive">
										<img src="images/loading-gif3.gif" class="loading-gif-2" alt="GIF" srcset="">
										<!-- Debut de l'Accordion pour Tout les Paniers -->
											<div class="table-responsive">
												<div id="listePanierVersementsClient"><!-- content will be loaded here --></div>
											</div>
										<!-- Fin de l'Accordion pour Tout les Paniers -->
									</div>
								</div>
								<div id="listeVersementsFournisseurTab" class="tab-pane fade">
									<br />
									<div class="table-responsive">
										<img src="images/loading-gif3.gif" class="loading-gif-2" alt="GIF" srcset="">
										<!-- Debut de l'Accordion pour Tout les Paniers -->
											<div class="table-responsive">
												<div id="listePanierVersementsFournisseur"><!-- content will be loaded here --></div>
											</div>
										<!-- Fin de l'Accordion pour Tout les Paniers -->
									</div>
								</div>
								<div id="listeBonsClientTab" class="tab-pane fade">
									<br />
									<div class="table-responsive">
										<img src="images/loading-gif3.gif" class="loading-gif-2" alt="GIF" srcset="">
										<!-- Debut de l'Accordion pour Tout les Paniers -->
											<div class="table-responsive">
												<div id="listePanierBonsClient"><!-- content will be loaded here --></div>
											</div>
										<!-- Fin de l'Accordion pour Tout les Paniers -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- Fin Container Details Journal -->
<?php } ?>

	<div class="modal fade"  id="imageNvDepenses" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Depenses : <b> # <span id="idDepenses_View"></span></b></h4>
				</div>
				<form   method="post" enctype="multipart/form-data">
					<div class="modal-body" style="padding:40px 50px;">
						<input  type="text" style="display:none;" name="idDepense" id="idDepenses_Upd_Nv" />
						<div class="form-group" style="text-align:center;" >
							<input type="file" name="file" accept="image/*" id="input_file_Depenses" onchange="showPreviewDepenses(event);"/><br />
							<img style="display:none;" width="500px" height="500px" id="output_image_Depenses"/>
							<iframe style="display:none;" id="output_pdf_Depenses" width="100%" height="500px"></iframe>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" style="display:none" class="btn btn-success pull-left" name="btnUploadImgDepenses" id="btn_upload_Depenses" >
							<span class="glyphicon glyphicon-upload"></span> Enregistrer
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade"  id="imageNvVersement" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><span class="glyphicon glyphicon-picture"></span> Versement : <b> # <span id="idVersement_View"></span></b></h4>
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

</div>


<script type="text/javascript">

	function showImageDepenses(idDepenses) {
		var nom=$('#imageDepenses'+idDepenses).attr("data-image");
		$('#idDepenses_View').text(nom);
		$('#idDepenses_Upd_Nv').val(idDepenses);
		$('#input_file_Depenses').val('');
		$('#imageNvDepenses').modal('show');
		var file = $('#imageDepenses'+idDepenses).val();
		if(file!=null && file!=''){
			var format = file.substr(file.length - 3);
			if(format=='pdf'){
				document.getElementById('output_pdf_Depenses').style.display = "block";
				document.getElementById('output_image_Depenses').style.display = "none";
				document.getElementById("output_pdf_Depenses").src="./PiecesJointes/"+file;
			}
			else{
				document.getElementById('output_image_Depenses').style.display = "block";
				document.getElementById('output_pdf_Depenses').style.display = "none";
				document.getElementById("output_image_Depenses").src="./PiecesJointes/"+file;
			}
		}
		else{
			document.getElementById('output_pdf_Depenses').style.display = "none";
			document.getElementById('output_image_Depenses').style.display = "none";
		}
	}
	function showPreviewDepenses(event) {
		var file = document.getElementById('input_file_Depenses').value;
		var reader = new FileReader();
		reader.onload = function()
		{
			var format = file.substr(file.length - 3);
			var pdf = document.getElementById('output_pdf_Depenses');
			var image = document.getElementById('output_image_Depenses');
			if(format=='pdf'){
				document.getElementById('output_pdf_Depenses').style.display = "block";
				document.getElementById('output_image_Depenses').style.display = "none";
				pdf.src = reader.result;
			}
			else{
				document.getElementById('output_image_Depenses').style.display = "block";
				document.getElementById('output_pdf_Depenses').style.display = "none";
				image.src = reader.result;
			}
		}
		reader.readAsDataURL(event.target.files[0]);
		document.getElementById('btn_upload_Depenses').style.display = "block";
	}

	function showImageVersement(idVersement) {
		var nom=$('#imageVersement'+idVersement).attr("data-image");
		$('#idVersement_View').text(nom);
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

</script>

</body>
</html> 

<script type="text/javascript" src="./scripts/historique.js"></script>

<?php

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

/**Debut Button upload Image Depenses**/
if (isset($_POST['btnUploadImgDepenses'])) {
    $idPagnet=htmlspecialchars(trim($_POST['idDepense']));
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
/**Fin Button upload Image Depenses**/

?>

