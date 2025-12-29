<?php

// Vérification des droits d'accès
// if (!verifierAccesUtilisateur($_SESSION)) {
//     die('Accès non autorisé');
// }


// Récupération et traitement des données de salaire
//try {
    //$sommesSalaires = calculerSommesSalaires($bdd);
    // Calcul des sommes de salaires
    
    // Récupération du personnel
    // $personnels = recupererPersonnel($bdd, $date);
    
    // // Récupération et enrichissement des accompagnateurs
    // $accompagnateurs = recupererAccompagnateurs($bdd);
    // $accompagnateurs = enrichirInformationsAccompagnateurs($bdd, $accompagnateurs);

	
    /************************************************** */
    // Tableau de résultats pour les PERSONNELS
        $profilPersonnel=7;
        $periodeActuelle = $date->format('Y-m');
        // echo $periodeActuelle."<br>";
        // echo strrev($periodeActuelle)."<br>";
        $sommesSalairesPersonnel = [
            'nonPayes' => 0.0,
            'dejaPayes' => 0.0
        ];

        // Requête paramétrée pour les salaires du mois en cours
        $stmt1 = $bdd->prepare("
            SELECT   SUM(salaireDeBase) as total FROM `aaa-contrat` c
                INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
                INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
                WHERE   p.profilPersonnel = :profil   AND c.dateDebut <= :date AND c.dateFin >= :date
                AND (  s.dateCalcul LIKE :periode1   ) AND aPayer =  :ap 
        ");
        
        $stmt1->execute([
            'ap' => 0,
            'profil' => $profilPersonnel,
              'date' => $date->format('Y-m-d'),
              'periode1' => "%{$periodeActuelle}%"
        ]);
        $sommesSalairesPersonnel['nonPayes'] = (float)$stmt1->fetchColumn();

        // Requête paramétrée pour les salaires payés
        // $stmt2 = $bdd->prepare("
        //      SELECT   SUM(salaireDeBase) as total FROM `aaa-contrat` c
        //         INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
        //         INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
        //         WHERE   p.profilPersonnel = :profil   AND c.dateDebut <= :date AND c.dateFin >= :date
        //         AND (  s.dateCalcul LIKE :periode1  ) AND aPayer =  :ap  
        // ");
        
        // $stmt2->execute([
        //     'ap' => 1,
        //     'profil' => $profilPersonnel,
        //       'date' => $date->format('Y-m-d'),
        //       'periode1' => "%{$periodeActuelle}%"
        // ]);
        // $sommesSalairesPersonnel['dejaPayes'] = (float)$stmt2->fetchColumn();
        $stmt2 = $bdd->prepare("
             SELECT * FROM `aaa-contrat` c
                INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
                INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
                WHERE   p.profilPersonnel = :profil   AND c.dateDebut <= :date AND c.dateFin >= :date
                AND (  s.dateCalcul LIKE :periode1  )   
        ");
        
        $resultats=$stmt2->execute([
            //'ap' => 1,
            'profil' => $profilPersonnel,
              'date' => $date->format('Y-m-d'),
              'periode1' => "%{$periodeActuelle}%"
        ]);

        $resultats=$stmt2->fetchAll();
        //var_dump($resultats);
        $total=0;
        foreach ($resultats as $res) {

            $total=$total+$res["salaireDeBase"]-$res["retenu"];
            
        //var_dump($total);
        }
        $sommesSalairesPersonnel['dejaPayes'] = $total;
    /************************************************** */
    // Tableau de résultats pour les accompagnateurs
        $sommesSalairesAccomp = [
            'nonPayes' => 0.0,
            'dejaPayes' => 0.0
        ];

        // Requête paramétrée pour les salaires du mois en cours
        $stmt1 = $bdd->prepare("
            SELECT SUM(partAccompagnateur) as total 
            FROM `aaa-payement-salaire` 
            WHERE aPayementBoutique =  :ap 
            AND aSalaireAccompagnateur =  :as
        ");
        
        $stmt1->execute([
            'ap' => 1,
            'as' => 0
        ]);
        $sommesSalairesAccomp['nonPayes'] = (float)$stmt1->fetchColumn();

        // Requête paramétrée pour les salaires payés
        $stmt2 = $bdd->prepare("
            SELECT SUM(partAccompagnateur) as total 
            FROM `aaa-payement-salaire` 
             WHERE aPayementBoutique =  :ap 
            AND aSalaireAccompagnateur =  :as
        ");
        
        $stmt2->execute([
            'ap' => 1,
            'as' => 1
        ]);
        $sommesSalairesAccomp['dejaPayes'] = (float)$stmt2->fetchColumn();
    

?>

<div class="row">
			
				<div class="card " style=" ;">
				  <!-- Default panel contents
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				  <div class="card-body">
                    <div class="container" align="center"> <br/>
                      <?php
						//$sommesSalaires = calculerSommesSalaires($bdd);
						// $personnels = recupererPersonnel($bdd, $date);
						// $accompagnateurs = recupererAccompagnateurs($bdd);
						// $accompagnateurs = enrichirInformationsAccompagnateurs($bdd, $accompagnateurs);
						?>
                        <div class="jumbotron noImpr">

                            <h2>Aujourd'hui : <?= htmlspecialchars($dateString2) ?></h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <h2>Salaires du Personnel</h2>
                                    <p>Cumul des Salaires non payé : 
                                        <span class="text-danger"><?= number_format($sommesSalairesPersonnel['nonPayes']) ?> FCFA</span>
                                    </p>
                                    <p>Cumul des Salaires payés : 
                                        <span class="text-danger"><?= number_format($sommesSalairesPersonnel['dejaPayes']) ?> FCFA</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h2>Salaires des Accompagnateurs</h2>
                                    <p>Cumul des Salaires non payé : 
                                        <span class="text-danger"><?= number_format($sommesSalairesAccomp['nonPayes']) ?> FCFA</span>
                                    </p>
                                    <p>Cumul des Salaires payés : 
                                        <span class="text-danger"><?= number_format($sommesSalairesAccomp['dejaPayes']) ?> FCFA</span>
                                    </p>
                                </div>
                            </div>
                            
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