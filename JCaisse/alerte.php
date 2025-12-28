<section>
  	<div class="container">
		<ul class="nav nav-tabs">
		<?php if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){ ?>
			<li class="active" id="toggleJour"><a data-toggle="tab" href="#JOUR">ALERTE COMMANDE</a></li>
			<li id="toggleSEUIL"><a data-toggle="tab" href="#SEUIL">ALERTE SEUIL</a></li>
			<li id="toggleEXPIRATION"><a data-toggle="tab" href="#EXPIRATION">ALERTE EXPIRATION</a></li>
		<?php
		} 
		else { ?>
			<li class="active" id="toggleSEUIL"><a data-toggle="tab" href="#SEUIL">ALERTE SEUIL</a></li>
			<?php if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {  ?>
				<li id="toggleDepot"><a data-toggle="tab" href="#DEPOT">ALERTE SANS DEPOT</a></li>
			<?php } ?>
			<li id="toggleEXPIRATION"><a data-toggle="tab" href="#EXPIRATION">ALERTE EXPIRATION</a></li>
			<li id="toggleECHEANCE"><a data-toggle="tab" href="#ECHEANCE">ALERTE ECHEANCE</a></li>
		<?php } ?>
		
		<?php  
		if (($_SESSION['proprietaire']==1) || ($_SESSION['gerant']==1)){
		?>
		<li id="togglePAIEMENT"><a data-toggle="tab" href="#PAIEMENT">ALERTE PAIEMENT</a></li>
		<?php 
		}
		?>
		</ul>
		<div class="tab-content">
			<?php if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){ ?>
				<div id="JOUR" class="tab-pane fade in active">
					<!-- <br><br><br>
					Révision en cours -->
					<div class="table-responsive">
						<label class="pull-left" for="nbEntreeAlerteCmd">Nombre entrées </label>
						<select class="pull-left" id="nbEntreeAlerteCmd">
						<optgroup>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option> 
						</optgroup>       
						</select>
						<input class="pull-right" type="text" name="" id="searchInputAlerteCmd" placeholder="Rechercher..." autocomplete="off">
						<div id="listeDesAlertesCmdes">

							<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">
							
						</div>
					</div>
				</div>
				 <div id="SEUIL" class="tab-pane fade">
					<!-- <br><br><br>
					<h2 align="center">Révision en cours... </h2>  -->
					<ul class="nav nav-tabs">
						<li class="active" id="toggleSEUIL_3"><a data-toggle="tab" href="#SEUIL_RAYON">ALERTE RAYON</a></li>
						<li id="toggleSEUIL_0"><a data-toggle="tab" href="#SEUIL_0">ALERTE SEUIL 0</a></li>
						<li id="toggleSEUIL_1"><a data-toggle="tab" href="#SEUIL_1">ALERTE SEUIL -20</a></li>
						<li id="toggleSEUIL_2"><a data-toggle="tab" href="#SEUIL_2">ALERTE SEUIL +20</a></li>
					</ul>
					<div class="tab-content">
						<div id="SEUIL_RAYON" class="tab-pane fade in active">
							<div class="table-responsive"> 
								<label class="pull-left" for="nbEntreeAlerteSeuil_Rayon">Nombre entrées </label>
								<select class="pull-left" id="nbEntreeAlerteSeuil_Rayon">
								<optgroup>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option> 
								</optgroup>       
								</select>
								<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_Rayon" placeholder="Rechercher..." autocomplete="off">
								<div id="listeDesAlertesSeuil_Rayon">
									<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">
								</div>
							</div>
						</div>
						<div id="SEUIL_0" class="tab-pane fade">
							<div class="table-responsive"> 
								<label class="pull-left" for="nbEntreeAlerteSeuil_0">Nombre entrées </label>
								<select class="pull-left" id="nbEntreeAlerteSeuil_0">
								<optgroup>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option> 
								</optgroup>       
								</select>
								<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_0" placeholder="Rechercher..." autocomplete="off">
								<div id="listeDesAlertesSeuil_0">
									<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

								</div>
							</div>
						</div>
						<div id="SEUIL_1" class="tab-pane fade">
							<div class="table-responsive"> 
							<label class="pull-left" for="nbEntreeAlerteSeuil_1">Nombre entrées </label>
								<select class="pull-left" id="nbEntreeAlerteSeuil_1">
								<optgroup>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option> 
								</optgroup>       
								</select>
								<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_1" placeholder="Rechercher..." autocomplete="off">
								<div id="listeDesAlertesSeuil_1"></div>
							</div>
						</div>
						<div id="SEUIL_2" class="tab-pane fade">
							<div class="table-responsive"> 
								<label class="pull-left" for="nbEntreeAlerteSeuil_2">Nombre entrées </label>
								<select class="pull-left" id="nbEntreeAlerteSeuil_2">
								<optgroup>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option> 
								</optgroup>       
								</select>
								<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_2" placeholder="Rechercher..." autocomplete="off">
								<div id="listeDesAlertesSeuil_2"></div>
							</div>
						</div>
					</div>
				</div>
				<div id="EXPIRATION" class="tab-pane fade">
				 	<div class="table-responsive">
						<label class="pull-left" for="nbEntreeAlerteExp">Nombre entrées </label>
						<select class="pull-left" id="nbEntreeAlerteExp">
						<optgroup>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option> 
						</optgroup>       
						</select>
						<input class="pull-right" type="text" name="" id="searchInputAlerteExp" placeholder="Rechercher..." autocomplete="off">
						<div id="listeDesAlertesExpirations">
							<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

						</div>	 

						<div id="retirerStockModal" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Confirmation Retrait Stock</h4>
										</div>
										<div class="modal-body" style="padding:40px 50px;">
											<form role="form" class="" >
												<input type="hidden" name="designation" id="idStock_Rtr" />
												<div class="form-group">
													<label for="reference">REFERENCE </label>
													<input type="text" class="form-control" name="designation" id="designation_Rtr"  disabled=""/>
												</div>
												<div class="form-group">
												<label for="categorie"> QUANTITE INITIALE </label>
												<input type="text" class="form-control" name="qteInitial" id="qteInitial_Rtr"  disabled="true" />
												</div>
												<div class="form-group">
												<label for="categorie"> QUANTITE RESTANTE </label>
												<input type="text" class="form-control" name="qteReste" id="qteReste_Rtr"  disabled="true" />
												</div>
												<div class="form-group">
												<label for="categorie"> DATE EXPIRATION </label>
												<input type="text" class="form-control" name="dateExpiration" id="dateExpiration_Rtr"  disabled="true" />
												</div> 
												<div align="right"> <br/>
												<font color="red"><b>Voulez-vous retirer ce produit ?</b></font><br />
												<input type="button" id="btn_rtr_Stock_P" class="boutonbasic" name="retirer" value=" Retirer >>" />
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
			<?php
			} 
			else { 
			?>
				<div id="SEUIL" class="tab-pane fade in active">
					<?php if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {  ?>
						<ul class="nav nav-tabs">
							<li class="active" id="toggleSEUIL_0"><a data-toggle="tab" href="#SEUIL_0">ALERTE SEUIL 0</a></li>
							<li id="toggleSEUIL_1"><a data-toggle="tab" href="#SEUIL_1">ALERTE SEUIL -150</a></li>
							<li id="toggleSEUIL_2"><a data-toggle="tab" href="#SEUIL_2">ALERTE SEUIL -500</a></li>
						</ul>
						<div class="tab-content">
							<div id="SEUIL_0" class="tab-pane fade in active">
								<div class="table-responsive"> 
									<label class="pull-left" for="nbEntreeAlerteSeuil_0">Nombre entrées </label>
									<select class="pull-left" id="nbEntreeAlerteSeuil_0">
									<optgroup>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="50">50</option> 
									</optgroup>       
									</select>
									<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_0" placeholder="Rechercher..." autocomplete="off">
									<div id="listeDesAlertesSeuil_0">
										<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

									</div>
								</div>
							</div>
							<div id="SEUIL_1" class="tab-pane fade">
								<div class="table-responsive"> 
								<label class="pull-left" for="nbEntreeAlerteSeuil_1">Nombre entrées </label>
									<select class="pull-left" id="nbEntreeAlerteSeuil_1">
									<optgroup>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="50">50</option> 
									</optgroup>       
									</select>
									<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_1" placeholder="Rechercher..." autocomplete="off">
									<div id="listeDesAlertesSeuil_1">
										<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

									</div>
								</div>
							</div>
							<div id="SEUIL_2" class="tab-pane fade">
								<div class="table-responsive"> 
									<label class="pull-left" for="nbEntreeAlerteSeuil_2">Nombre entrées </label>
									<select class="pull-left" id="nbEntreeAlerteSeuil_2">
									<optgroup>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="50">50</option> 
									</optgroup>       
									</select>
									<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_2" placeholder="Rechercher..." autocomplete="off">
									<div id="listeDesAlertesSeuil_2">
										<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

									</div>
								</div>
							</div>
						</div>
					<?php
					} 
					else { 
					?>
						<ul class="nav nav-tabs">
							<?php
								if ($_SESSION['idBoutique'] == 145 || $_SESSION['idBoutique'] == 111) {	
							?>
								<li class="active" id="toggleSEUIL_3"><a data-toggle="tab" href="#SEUIL_RAYON">ALERTE RAYON</a></li>
								<li id="toggleSEUIL_0"><a data-toggle="tab" href="#SEUIL_0">ALERTE SEUIL 0</a></li>
							
							<?php
								} 
								else { 
							?>
								<li class="active" id="toggleSEUIL_0"><a data-toggle="tab" href="#SEUIL_0">ALERTE SEUIL 0</a></li>
							<?php
							}	 
							?>
							<li id="toggleSEUIL_1"><a data-toggle="tab" href="#SEUIL_1">ALERTE SEUIL -20</a></li>
							<li id="toggleSEUIL_2"><a data-toggle="tab" href="#SEUIL_2">ALERTE SEUIL +20</a></li>
						</ul>
						<div class="tab-content">
							<?php
								if ($_SESSION['idBoutique'] == 145 || $_SESSION['idBoutique'] == 111) {	
							?>
								<div id="SEUIL_RAYON" class="tab-pane fade in active">
									<div class="table-responsive"> 
										<label class="pull-left" for="nbEntreeAlerteSeuil_Rayon">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeAlerteSeuil_Rayon">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option> 
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_Rayon" placeholder="Rechercher..." autocomplete="off">
										<div id="listeDesAlertesSeuil_Rayon">
											<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">
										</div>
									</div>
								</div>
								<div id="SEUIL_0" class="tab-pane fade">
									<div class="table-responsive"> 
										<label class="pull-left" for="nbEntreeAlerteSeuil_0">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeAlerteSeuil_0">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option> 
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_0" placeholder="Rechercher..." autocomplete="off">
										<div id="listeDesAlertesSeuil_0">
											<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

										</div>
									</div>
								</div>
							<?php
								}	
								else {	
							?>
								<div id="SEUIL_0" class="tab-pane fade in active">
									<div class="table-responsive"> 
										<label class="pull-left" for="nbEntreeAlerteSeuil_0">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeAlerteSeuil_0">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option> 
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_0" placeholder="Rechercher..." autocomplete="off">
										<div id="listeDesAlertesSeuil_0">
											<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

										</div>
									</div>
								</div>
							<?php
								}	 
							?>
							<div id="SEUIL_1" class="tab-pane fade">
								<div class="table-responsive"> 
								<label class="pull-left" for="nbEntreeAlerteSeuil_1">Nombre entrées </label>
									<select class="pull-left" id="nbEntreeAlerteSeuil_1">
									<optgroup>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="50">50</option> 
									</optgroup>       
									</select>
									<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_1" placeholder="Rechercher..." autocomplete="off">
									<div id="listeDesAlertesSeuil_1">
										<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

									</div>
								</div>
							</div>
							<div id="SEUIL_2" class="tab-pane fade">
								<div class="table-responsive"> 
									<label class="pull-left" for="nbEntreeAlerteSeuil_2">Nombre entrées </label>
									<select class="pull-left" id="nbEntreeAlerteSeuil_2">
									<optgroup>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="50">50</option> 
									</optgroup>       
									</select>
									<input class="pull-right" type="text" name="" id="searchInputAlerteSeuil_2" placeholder="Rechercher..." autocomplete="off">
									<div id="listeDesAlertesSeuil_2">
										<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div> 
				<?php if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {  ?>
					<div id="DEPOT" class="tab-pane fade">
						<ul class="nav nav-tabs">
							<li class="active" id="toggleDepot_0"><a data-toggle="tab" href="#DEPOT_0">ALERTE QUANTITE 0</a></li>
							<li id="toggleDepot1"><a data-toggle="tab" href="#DEPOT_1">ALERTE QUANTITE -150</a></li>
							<li id="toggleDepot2"><a data-toggle="tab" href="#DEPOT_2">ALERTE QUANTITE -500</a></li>
						</ul>
						<div class="tab-content">
							<div id="DEPOT_0" class="tab-pane fade in active">
								<div class="table-responsive"> 
									<label class="pull-left" for="nbEntreeAlerteDepot_0">Nombre entrées </label>
									<select class="pull-left" id="nbEntreeAlerteDepot_0">
									<optgroup>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="50">50</option> 
									</optgroup>       
									</select>
									<input class="pull-right" type="text" name="" id="searchInputAlerteDepot_0" placeholder="Rechercher..." autocomplete="off">
									<div id="listeDesAlertesDepot_0">
										<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

									</div>
								</div>
							</div>
							<div id="DEPOT_1" class="tab-pane fade">
								<div class="table-responsive"> 
									<label class="pull-left" for="nbEntreeAlerteDepot_1">Nombre entrées </label>
									<select class="pull-left" id="nbEntreeAlerteDepot_1">
									<optgroup>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="50">50</option> 
									</optgroup>       
									</select>
									<input class="pull-right" type="text" name="" id="searchInputAlerteDepot_1" placeholder="Rechercher..." autocomplete="off">
									<div id="listeDesAlertesDepot_1">
										<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

									</div>
								</div>
							</div>
							<div id="DEPOT_2" class="tab-pane fade">
								<div class="table-responsive"> 
									<label class="pull-left" for="nbEntreeAlerteDepot_2">Nombre entrées </label>
									<select class="pull-left" id="nbEntreeAlerteDepot_2">
									<optgroup>
										<option value="10">10</option>
										<option value="20">20</option>
										<option value="50">50</option> 
									</optgroup>       
									</select>
									<input class="pull-right" type="text" name="" id="searchInputAlerteDepot_2" placeholder="Rechercher..." autocomplete="off">
									<div id="listeDesAlertesDepot_2">
										<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div id="EXPIRATION" class="tab-pane fade">
					<div class="table-responsive">
						<label class="pull-left" for="nbEntreeAlerteExp">Nombre entrées </label>
						<select class="pull-left" id="nbEntreeAlerteExp">
						<optgroup>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option> 
						</optgroup>       
						</select>
						<input class="pull-right" type="text" name="" id="searchInputAlerteExp" placeholder="Rechercher..." autocomplete="off">
						<div id="listeDesAlertesExpirations">
							<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

						</div>	 

						<div id="retirerStockModal" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Confirmation Retrait Stock</h4>
										</div>
										<div class="modal-body" style="padding:40px 50px;">
											<form role="form" class="" >
												<input type="hidden" name="designation" id="idStock_Rtr" />
												<div class="form-group">
													<label for="reference">REFERENCE </label>
													<input type="text" class="form-control" name="designation" id="designation_Rtr"  disabled=""/>
												</div>
												<div class="form-group">
												<label for="categorie"> QUANTITE INITIALE </label>
												<input type="text" class="form-control" name="qteInitial" id="qteInitial_Rtr"  disabled="true" />
												</div>
												<div class="form-group">
												<label for="categorie"> QUANTITE RESTANTE </label>
												<input type="text" class="form-control" name="qteReste" id="qteReste_Rtr"  disabled="true" />
												</div>
												<div class="form-group">
												<label for="categorie"> DATE EXPIRATION </label>
												<input type="text" class="form-control" name="dateExpiration" id="dateExpiration_Rtr"  disabled="true" />
												</div> 
												<div align="right"> <br/>
												<font color="red"><b>Voulez-vous retirer ce produit ?</b></font><br />
												<input type="button" id="btn_rtr_Stock_P" class="boutonbasic" name="retirer" value=" Retirer >>" />
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
				 <div id="ECHEANCE" class="tab-pane fade">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#CLIENT">ECHEANCE CLIENT</a></li>
						<li><a data-toggle="tab" href="#FOURNISSEUR">ECHEANCE FOURNISSEUR</a></li>
					</ul>
					<div class="tab-content">
						<div id="CLIENT" class="tab-pane fade in active">
							<div class="table-responsive">
								<label class="pull-left" for="nbEntreeEcheanceCli">Nombre entrées </label>
								<select class="pull-left" id="nbEntreeEcheanceCli">
								<optgroup>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option> 
								</optgroup>       
								</select>
								<input class="pull-right" type="text" name="" id="searchInputEcheanceCli" placeholder="Rechercher..." autocomplete="off">
								<div id="listeDesEcheancesClients">
									<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

								</div>
							</div>
						</div>
						<div id="FOURNISSEUR" class="tab-pane fade">
							<div class="table-responsive">
								<label class="pull-left" for="nbEntreeEcheanceFnr">Nombre entrées </label>
								<select class="pull-left" id="nbEntreeEcheanceFnr">
								<optgroup>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option> 
								</optgroup>       
								</select>
								<input class="pull-right" type="text" name="" id="searchInputEcheanceFnr" placeholder="Rechercher..." autocomplete="off">
								<div id="listeDesEcheancesFournisseurs">
									<img src="images/loading-gif3.gif" style="margin-left:30%;margin-top:8%;display:none" class="loading-gif" alt="GIF" srcset="">

								</div>
							</div>
						</div>
					</div>
				</div> 
			<?php } ?>
			<!-- -->

			<div id="PAIEMENT" class="tab-pane fade">
				<table id="tablePaiement" class="display tabPaiement"  border="1" width="100%" align="left">
						<thead>
						<tr id="thPaiement">
							<th>Ordre</th>
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
							$('#togglePAIEMENT').click(function(){
								$("#tablePaiement").dataTable({
								"bProcessing": true,
								"destroy": true,
								"sAjaxSource": "ajax/alertePaiementAjax.php",
												
								"aoColumns": [
										{ mData: "0" } ,
										{ mData: "1" },
										{ mData: "2" },
										{ mData: "3" },
										{ mData: "4" },
										{ mData: "5" },
									],
									
								});  
							});
						});
					</script>

					<div id="paiementModal" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content" style="background-color: orange;font-weight:bold;color:#fff;">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title"><b>Paiement Orange Money</b></h4>
									</div>
									<div class="modal-body" align="center">
										<div class="">
											<img alt="" style="width: 70px;height: 70px;" src="images/orange-money2.jpeg" id="imgsrc_Upd" style="float:left" />           
											<h4>Payer avec Orange Money </h4>                   
										</div>
										
										<div class="" style="padding: 35px;">
											<img alt="imgQRCode 8888" id="imgQRCode" />                               
										</div>
										
										<div class="">
											<i class="fa fa-camera" aria-hidden="true" style="font-size:-webkit-xxx-large;color:#fff;padding:5px;"></i>                             
										</div>
										<div class="">
											<h4>Scanner le code QR avec l'application Orange Money de votre téléphone
											<br>pour effectuer le paiement. </h4>                            
										</div>									
										
										<div class="" style="padding: 5px;">
											<img alt="Google Play" style="width: 250px;height: 50px;" src="images/playstore.png"/>                               
											<img alt="App Store" style="width: 250px;height: 50px;" src="images/appstore2.png"/>                               
										</div>
									<!-- <div>
										<p> Pour le paiement du service <b>JCAISSE</b>, vous allez dans votre <b>compte Orange Money</b> et vous transfert le montant de votre paiement au numèro 77 524 35 94 de <b> YAATOUT SARL </b>l'entreprise éditeur du logiciel JCAISSE.</p>
										<p> Le <b>Référence de Transfert</b> qui se trouve dans le SMS reçu après transfert du montant et le numéro téléphone utilisé doivent etre renseigner dans le formulaire ci-dessous pour permettre à JCAISSE de valider le paiement.</p>
                                    </div> -->
										<form hidden role="form" class="" align="center">
										   <div class="row">
                                                <div class="col-md-6">
													<br/><br/><br/><img alt="" style="width: 90%;height: 70%;"  src="images/orange-money2.jpeg" id="imgsrc_Upd" />                               
                                                </div>
                                                <div class="col-md-6">
													<img alt="imgQRCode 8888" id="imgQRCode" />                               
                                                </div>
                                                <!-- <div  class="col-md-6">
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
                                                        <input type="text" class="form-control" name="refTransf" min="10" max="50" id="refTransf_Rtr" placeholder="Exemple de référence valide CI200802.1100.B23993" required />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="numTel"> Numéro de Téléphone </label>
                                                        <input type="text" class="form-control" name="numTel" min="9" max="15" id="numTel_Rtr" placeholder="ici le numero qui reçoit le transfert" required />
                                                    </div>

                                                    <div>
                                                        <p> Votre facture sera disponible aprés validation du paiement.</p>
                                                    </div>
                                                    <div>
                                                        <p> <b>Merci de votre confiance renouvelée.</b></p>
                                                    </div>
                                                    
                                                </div> -->
                                            </div> 
										</form>
										<div class="modal-footer row">
											<!-- <input type="button" id="btn_aj_Paiement" class="boutonbasic" name="envoyer" value="Envoyer transfert >>" /> -->

											<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
										</div>
									</div>
							</div>
						</div>
					</div>
                    
					<div id="paiementWave"  class="modal fade " role="dialog" align="center">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header" style="">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Paiement Wave </b></h4>
								</div>
								<div class="modal-body" id="bodyPayWave">
								</div>	               
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
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
