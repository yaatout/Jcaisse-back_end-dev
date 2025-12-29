<?php
    $matricule=@$_POST["matricule"];
    
    // Récupération et traitement des données de salaire
    try {
        // Calcul des sommes de salaires
        $sommesSalaires = calculerSalaireTotalAccompagnateur($bdd,$matricule);
         
    } catch (Exception $e) {
        error_log("Erreur lors du chargement des données : " . $e->getMessage());
        die("Une erreur est survenue lors du chargement des données.");
    }
?>
<input type="hidden" id="mat" value="<?= $matricule ?>">
<input type="hidden" id="de" value="<?= $matricule ?>">
<input type="hidden" id="fe" value="<?= $matricule ?>">
        <div class="row">
			
			<div class="">
				<div class="card " style=" ;">
				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				    <div class="card-body">
                        <div class="container" align="center"> <br/> 
                            <?php
                               
                            ?>

                            <div class="jumbotron noImpr">
                                <h2>Aujourd'hui : <?= htmlspecialchars($dateString2) ?></h2>
                                <p>Cumul des Salaires non payés : 
                                    <span class="text-danger"><?= number_format($sommesSalaires, 2, ',', ' ') ?> FCFA</span>
                                </p>
                            </div>
                            <div class="modal fade" <?php echo  "id=Activer"; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Effectuer le virement</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulaireVersement" method="post" action="salaireAccompagnateurs.php">
                                                                    <div class="form-group">
                                                                        <h2>Voulez vous vraiment effectuer le virement</h2>
                                                                        <!-- <input type="hidden" name="idBoutique" <?php echo  "value=". $payement['idBoutique']."" ; ?> > -->
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnActiver" class="btn btn-primary">Effectuer le virement</button>
                                                                    </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" <?php echo  "id=Desactiver" ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Annulation</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form name="formulaireVersement" method="post" action="salaireAccompagnateurs.php">
                                                                    <div class="form-group">
                                                                        <h2>Voulez vous vraiment annuler le virement</h2>
                                                                        <!-- <input type="hidden" name="idBoutique" <?php echo  "value=". htmlspecialchars($payement['idBoutique'])."" ; ?> > -->
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                            <button type="submit" name="btnDesactiver" class="btn btn-primary">Annuler le virement</button>
                                                                    </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">DETAIL DU SALAIRE DE L'ACCOMPAGNATEUR <?php echo $matricule; ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="LISTEPERSONNEL">
                                              
                                    <div class="table-responsive">
										<label class="pull-left" for="nbEntre">Nombre entrées </label>
										<select class="pull-left" id="nbEntre">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputsalUnAcc" placeholder="Rechercher...">
										<div id="listesalUnAcc"><!-- content will be loaded here --></div>
								
						            </div>
                                </div>

                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
        
<script type="text/javascript" src="salaire/js/scriptSalaire.js"></script>