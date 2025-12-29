<?php

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



?>

   <div class="row">
			<div class="">

			</div>
			<div class="">
				<div class="card " style=" ;">
			    <div class="card-body">
                <div class="container">
            

	<?php
	
	if($_SESSION['profil']=="SuperAdmin"){ ?>
	<center>
		<div class="modal-body">
			<form name="referenceTransfertform" method="post" action="referenceTransfert.php">
			  <div>
				<button type="button" name="referenceTransfertXXXX" class="btn btn-primary" data-toggle="modal" data-target="#Referencetransferts"> Ajout de Référence Transfert </button>
			   </div>
			</form>
		</div>
	</center>
<?php
	} ?>

<div class="modal fade" id="Referencetransferts" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Envoie de référence de paiement</h4>
			</div>
			<div class="modal-body">
				<form name="formulaireVersement" method="post" action="referencesTransfert.php">
				
						
						
						<div class="form-group">
							<label for="dateTransfert"> Date Envoie</label>
							<input type="Date" class="form-control" name="dateTransfert" id="dateTransfert" value=""  />
						</div> 
						<div class="form-group">
							<label for="montantTransfert"> Montant</label>
							<input type="number" class="form-control" name="montantTransfert" id="montantTransfert" value=""  />
						</div>
						
						<div class="form-group">
							<label for="refTransf">Référence du Transfert </label>
							<input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransf" value="" />
						</div>
						<div class="form-group">
							<label for="numTel"> Numéro de Téléphone </label>
							<input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel" value="" />
						</div>
			
				  
				  
				  
				  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						<button type="submit" name="btnReferencetransferts" class="btn btn-primary">Envoyer Référence Transfert</button>
				   </div>
				</form>
			</div>

		</div>
	</div>
</div>

<div class="modal fade" id="DetReferencetransferts" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Envoie de référence de paiement</h4>
			</div>
			<div class="modal-body">
				<form name="formulaireVersement" > 
						
						<div class="form-group">
							<label for="montantTransfert"> Montant</label>
							<input type="text" class="form-control" id="nomBoutique" disabled  />
						</div>
						<div class="form-group">
							<label for="dateTransfert"> Date PS</label>
							<input type="text" class="form-control" id="datePS" disabled  />
						</div> 
						
						<div class="form-group">
							<label for="refTransf">Référence du Transfert </label>
							<input type="text" class="form-control"  id="refTransfert" disabled />
						</div>
					  	<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					   </div>
				</form>
			</div>

		</div>
	</div>
</div>
<section>
  	<div class="container">
		<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#SEUIL">VALIDATION REFERENCES TRANSFERTS</a></li>
		
		</ul>
		<div class="tab-content">
			<div id="SEUIL" class="tab-pane fade  in active ">
				<table id="tableValidationPaiement" class="display tabValidationPaiement"  border="1" width="100%" align="left">
						<thead>
						<tr id="thValidationPaiement">
							<th>Ordre</th>
							<th>REf</th>
							<th>Boutique</th>
							<th>Mois</th>
							<th>Montant Paiement</th>
							<th>Date de Paiement</th>
							<th>Statut</th>
							<th>Operations</th>
						</tr>
						</thead>
					</table>

					<script type="text/javascript">
						$(document).ready(function() {
							$("#tableValidationPaiement").dataTable({
							"bProcessing": true,
							"sAjaxSource": "ajax/alerteValidationPaiementAjax.php",
							                
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
								
							});  
						});
					</script>

					<div id="validationPaiementModal" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Envoie Référence Transfert</h4>
									</div>
									<div class="modal-body" style="padding:40px 50px;">
										<form role="form" class="" >
										
											<input type="hidden" name="idPS" id="idPS_Rtr" />
											
											
											<div class="form-group">
												<label for="datePS"> Mois</label>
												<input type="text" class="form-control" name="DatePS" id="datePS_Rtr"  disabled="true" />
											</div> 
											<div class="form-group">
												<label for="categorie"> Montant</label>
												<input type="text" class="form-control" name="montantFixePayement" id="montantFixePayement_Rtr"  disabled="true" />
											</div>
											
											<div class="form-group">
												<label for="refTransf">Référence du Transfert </label>
												<input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransf_Rtr" autofocus="" required />
											</div>
											<div class="form-group">
												<label for="numTel"> Numéro de Téléphone </label>
												<input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel_Rtr" required />
											</div>
											
											<div>
												<p> Le <b>Référence de Transfert</b> se trouve dans le SMS reçu après l'envoie du montant à payer par Orange Money, Wari, YUP, ... vers le numéro de téléphone <b>77 524 35 94</b>.</p>
												<p>Aprés la validation de votre paiement, nous vous enverrons votre facture dans l'adresse mail :<b> <?php echo $_SESSION['email'] ; ?></b></p>
											</div>
											<div align="right"> <br/>
											<font color="red"><b>Voulez-vous envoyer la référence de transfert ?</b></font><br />
											<input type="button" id="btn_aj_validationPaiement" class="boutonbasic" name="envoyer" value="Envoyer transfert >>" />
											</div>
										</form>
									</div>
							</div>
						</div>
					</div>

					<div id="diminuerStockModal" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Confirmation Diminiuer Stock</h4>
									</div>
									<div class="modal-body" style="padding:40px 50px;">
										<form role="form" class="" > 
											<input type="hidden" name="designation" id="idStock_Dim" />
											<input type="hidden" name="designation" id="dateExpiration_Dim" />
											<input type="hidden"  name="qteInitial" id="qteInitial_Dim"  disabled="true" />
											<input type="hidden"  name="qteReste" id="qteReste_Dim"  disabled="true" />
											<input type="hidden"  name="qteRetirer" id="qteRetirer_Dim"  disabled="true" />
											<div class="form-group">
												<label for="reference">REFERENCE </label>
												<input type="text" class="form-control" name="designation" id="designation_Dim"  disabled=""/>
											</div>
											<div class="form-group">
											<label for="categorie"> CATEGORIE </label>
											<input type="text" class="form-control" name="categorie" id="categorie_Dim"  disabled="true" />
											</div>
											<div class="form-group">
											<label for="categorie"> QUANTITE A DIMINUER </label>
											<input type="number" class="form-control" name="qteDiminuer" id="qteDiminuer_Dim"  />
											</div> 
											<div align="right"> <br/>
											<font color="red"><b>Voulez-vous diminiuer ce produit ?</b></font><br />
											<input type="button" id="btn_dim_Stock_P" class="boutonbasic" name="diminuer" value=" Retourner >>" />
											</div>
										</form>
									</div>
							</div>
						</div>
					</div>
			</div>
			
		</div>
	</div>
</section>
			
			
			
</div>
</div>
	
</div>
</div>
</div>
		
</body>
</html>