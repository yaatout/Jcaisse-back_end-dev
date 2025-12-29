<?php

// Vérification des droits d'accès
if (!verifierAccesUtilisateur($_SESSION)) {
    die('Accès non autorisé');
}


// Récupération et traitement des données de salaire
try {
    // Calcul des sommes de salaires
    $sommesSalaires = calculerSommesSalaires($bdd);
    
    // Récupération du personnel
    // $personnels = recupererPersonnel($bdd, $date);
    
    // // Récupération et enrichissement des accompagnateurs
    // $accompagnateurs = recupererAccompagnateurs($bdd);
    // $accompagnateurs = enrichirInformationsAccompagnateurs($bdd, $accompagnateurs);
    
} catch (Exception $e) {
    error_log("Erreur lors du chargement des données : " . $e->getMessage());
    die("Une erreur est survenue lors du chargement des données.");
}
?>

<div class="row">
			
				<div class="card " style=" ;">
				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				  <div class="card-body">
                    <div class="container" align="center"> <br/>
                      <?php
						$sommesSalaires = calculerSommesSalaires($bdd);
						// $personnels = recupererPersonnel($bdd, $date);
						// $accompagnateurs = recupererAccompagnateurs($bdd);
						// $accompagnateurs = enrichirInformationsAccompagnateurs($bdd, $accompagnateurs);
						?>
                        <div class="jumbotron noImpr">

                            <h2>Aujourd'hui : <?= htmlspecialchars($dateString2) ?></h2>

                            <p>Cumul des Salaires du mois en cours : 
                                <span class="text-danger"><?= number_format($sommesSalaires['nonPayes'], 2, ',', ' ') ?> FCFA</span>
                            </p>
                            <p>Cumul des Salaires payés : 
                                <span class="text-danger"><?= number_format($sommesSalaires['dejaPayes'], 2, ',', ' ') ?> FCFA</span>
                            </p>
                            <?php
                            if($_SESSION['profil']=="SuperAdmin" || $_SESSION['profil']=="Assistant"){ ?>
                                <center>
                                <div class="modal-body">
                                    <form name="formulairePayementSalaire" method="post" action="#">
                                      <div>
                                        <!-- <button type="submit" name="virementSalaire" class="btn btn-success"> Virement des Salaires </button> -->
                                        <button class="btn btn-danger">  Virement des Salaires Button desactivé</button>
                                       </div>
                                    </form>
                                </div>
                                </center>
                               <?php
                            }  ?>
                        </div>
						
						

                        <ul class="nav nav-tabs">
                          <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DU PERSONNEL</a></li>
                          <li ><a data-toggle="tab" href="#LISTEACCOMPAGNATEUR"  onClick="listeAllAccomp()">LISTE DES PARTS DES ACCOMPAGNATEURS</a></li>
                          <li ><a data-toggle="tab" href="#LISTEING"  onClick="pEnCours()">INGENIEURS</a></li>
                          <li ><a data-toggle="tab" href="#LISTEEDICATA"  onClick="pEnCours()">EDITEUR CATALOGUE</a></li>
                          <li ><a data-toggle="tab" href="#LISTEASSIST"  onClick="pEnCours()">ASSISTANT5(E)</a></li>
                        </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="LISTEPERSONNEL" >
						<div class="table-responsive">
										<label class="pull-left" for="nbEntrePerson">Nombre entrées </label>
										<select class="pull-left" id="nbEntrePerson">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputPerson" placeholder="Rechercher...">
										<div id="listePerson"><!-- content will be loaded here --></div>
								
						</div>
					</div>
                    <div class="tab-pane fade" id="LISTEACCOMPAGNATEUR" >
                        <div class="table-responsive">
										<label class="pull-left" for="nbEntreAcc">Nombre entrées </label>
										<select class="pull-left" id="nbEntreAcc">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputAcc" placeholder="Rechercher...">
										<div id="listeAccompagnateurs"><!-- content will be loaded here --></div>
								
						</div>
                    </div>                    
                    
                    <div class="tab-pane fade" id="LISTEING" >
                        <div class="table-responsive">
										<label class="pull-left" for="nbEntrePlusMois">Nombre entrées </label>
										<select class="pull-left" id="nbEntrePlusMois">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputPlusMois" placeholder="Rechercher...">
										<div id="resultsPlusMois"><!-- content will be loaded here --></div>
								
						</div>
                    </div>
                    <div class="tab-pane fade " id="LISTEEDICATA">
                        <div class="table-responsive">
										<label class="pull-left" for="nbEntrePlusMois">Nombre entrées </label>
										<select class="pull-left" id="nbEntrePlusMois">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputPlusMois" placeholder="Rechercher...">
										<div id="resultsPlusMois"><!-- content will be loaded here --></div>
								
						</div>
                    </div>  
                    <div class="tab-pane fade " id="LISTEASSIST">
                        <div class="table-responsive">
										<label class="pull-left" for="nbEntrePlusMois">Nombre entrées </label>
										<select class="pull-left" id="nbEntrePlusMois">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>  
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputPlusMois" placeholder="Rechercher...">
										<div id="resultsPlusMois"><!-- content will be loaded here --></div>
								
						</div>
                    </div>  
                  </div>
                </div>
			        </div>
                  </div>
				</div>
			
		</div>