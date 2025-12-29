<?php
/*
Résumé:
Commentaire:
version:1.2
Auteur:EL hadji mamadou korka
Date de modification:23-12-2025
Modification: Migration vers PDO
*/
session_start();

if(!$_SESSION['iduserBack'])
	header('Location:index.php');

require('connectionPDO.php');

require('declarationVariables.php');
$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
    
    // Récupération de la date
    $annee = $date->format('Y');
    $mois = $date->format('m');
    $jour = $date->format('d');
    $heureString = $date->format('H:i:s');
    $dateString = $annee.'-'.$mois.'-'.$jour;
    $dateString2 = $annee.'-'.$mois.'-'.$jour;

/**********************/

if (isset($_POST['btnAjouterCataloque'])) {
    if(isset($_POST['type']) && isset($_POST['categorie'])) {
        $catalogueTotal = 'aaa-catalogueTotal';
        
        // Création de la table si elle n'existe pas
        $sql00 = "CREATE TABLE IF NOT EXISTS `$catalogueTotal` (
            `id` INT NOT NULL AUTO_INCREMENT, 
            `nom` VARCHAR(55) NOT NULL,
            `categorie` VARCHAR(55) NOT NULL,
            `type` VARCHAR(55) NOT NULL,
            `dateCatalogue` VARCHAR(15) NOT NULL, 
            PRIMARY KEY (`id`),
            UNIQUE (`nom`)
        ) ENGINE=InnoDB";
        
        try {
            $bdd->exec($sql00);

            $type = $_POST['type'];
            $categorie = $_POST['categorie'];
            $typeCategorie = $type . "-" . $categorie;
            
            // Vérification dans la table aaa-boutique
            $req1 = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE type = :type AND categorie = :categorie");
            $req1->execute(array(
                'type' => $type,
                'categorie' => $categorie
            ));
            
            if($req1->rowCount() > 0) {
                $catTypeCateg = 'aaa-catalogue-'.$typeCategorie;
                
                // Insertion dans la table catalogueTotal
                $req2 = $bdd->prepare("INSERT IGNORE INTO `$catalogueTotal` 
                                     (nom, type, categorie, dateCatalogue) 
                                     VALUES (:nom, :type, :categorie, :dateCatalogue)");
                
                $req2->execute(array(
                    'nom' => $typeCategorie,
                    'type' => $type,
                    'categorie' => $categorie,
                    'dateCatalogue' => $dateString2
                ));
            }
        } catch(PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}

if (isset($_POST['btnSupprimer'])) {
    $id = $_POST["id"];
    $nom = $_POST["nom"];
    $type = $_POST["type"];
    
    try {
        // Désactiver temporairement la vérification des clés étrangères
        $bdd->exec("SET FOREIGN_KEY_CHECKS=0");
        
        // Suppression de l'entrée dans aaa-catalogueTotal
        $req = $bdd->prepare("DELETE FROM `aaa-catalogueTotal` WHERE id = :id");
        $req->execute(array('id' => $id));
        
        // Suppression des tables associées
        $tablesToDrop = array(
            'aaa-catalogue-'.$nom,
            'aaa-categorie-'.$nom
        );
        
        if ($type == "Pharmacie") {
            $tablesToDrop[] = 'aaa-forme-'.$nom;
        }
        
        foreach($tablesToDrop as $table) {
            try {
                $bdd->exec("DROP TABLE IF EXISTS `$table`");
            } catch(PDOException $e) {
                // Log l'erreur mais continue l'exécution
                error_log("Erreur lors de la suppression de la table $table: " . $e->getMessage());
            }
        }
        
        // Réactiver la vérification des clés étrangères
        $bdd->exec("SET FOREIGN_KEY_CHECKS=1");
        
    } catch(PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
}

/**************** DECLARATION DES ENTETES *************/
?>

<?php require('entetehtml.php'); ?>

<body >
<?php
/**************** MENU HORIZONTAL *************/


  require('header.php');

/**************** Ajouter une catégorie *************/

/**************** *************  *************/

echo'<div class="container">';
if($_SESSION['profil']!="Editeur catalogue")
	echo'<div class="row" align="center">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPersonnel">
												<i class="glyphicon glyphicon-plus"></i>Création catalogue
			</button>';

?>  </div>
		<div class="modal fade" id="addPersonnel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
								<div class="modal-content">
										<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title" id="myModalLabel">Creation catalogue</h4>
										</div>
										<div class="modal-body">
												<form name="formulairePersonnel" method="post"  >
													<div class="form-group row">
															<div class="col-sm-6">
																 <div class="form-group">
																    <label for="categorie"> Type de boutique </label>
																    <select class="form-control" name="type" id="categorie2">
																		<option selected value= "Sans categorie">Sans categorie</option>
																		 <?php
																		 $sql1="SELECT * FROM `aaa-typeboutique` ";
																		 if($res1 = mysql_query($sql1)) {
																			 while($type = mysql_fetch_array($res1)) {
																				echo'<option  value= "'.$type['libelle'].'">'.$type['libelle'].'</option>';
																			  }
																			} ?>
																	</select>
																</div>
														 </div>
														 <div class="col-sm-6">
																<div class="form-group">
																    <label for="categorie"> CATEGORIE </label>

																    <select class="form-control" name="categorie" id="categorie2">
																		<option selected value= "Sans categorie">Sans categorie</option>
																		 <?php
																		 $sql2="SELECT * FROM `aaa-categorie` ";
																		 if($res2 = mysql_query($sql2)) {
																			 while($categorie = mysql_fetch_array($res2)) {
																				echo'<option  value= "'.$categorie['nomcategorie'].'">'.$categorie['nomcategorie'].'</option>';
																			}
																		} ?>
																	</select>
																</div>
														</div>
													</div>
													<div class="modal-footer row">
															<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
															<button type="submit" name="btnAjouterCataloque" class="btn btn-primary">Enregistrer</button>
													</div>
												</form>
										</div>

								</div>
						</div>
		</div>


</div>
<br><br>

<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/
?>
		<div class="container">
				<div class="container" align="center">
				        <ul class="nav nav-tabs">
				          <li class="active"><a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES STOCKS DE PRODUITS</a></li>
				        </ul>
				        <div class="tab-content">
				            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
				                    <?php
										$req = $bdd->query('SELECT * FROM `aaa-catalogueTotal` ORDER BY id DESC');
										$catalogues = $req->fetchAll(PDO::FETCH_ASSOC);
									?>
									    <table id="exemple" class="display" border="1" id="userTable">
										    <thead>
										 		<tr>
													<th>NOM</th>
													<th>CATEGORIE</th>
													<th>TYPE</th>
													<th>DATE</th>
													<th>OPERATION</th>
												</tr>
											</thead>
											<tfoot><tr>
												<th>NOM</th>
												<th>CATEGORIE</th>
												<th>TYPE</th>
												<th>DATE</th>
												<th>OPERATION</th>
												</tr>
											</tfoot>
											<?php foreach($catalogues as $catalogue): 
												// Échapper les sorties pour la sécurité
												$nom = htmlspecialchars($catalogue['nom'], ENT_QUOTES, 'UTF-8');
												$categorie = htmlspecialchars($catalogue['categorie'], ENT_QUOTES, 'UTF-8');
												$type = htmlspecialchars($catalogue['type'], ENT_QUOTES, 'UTF-8');
												$dateCatalogue = htmlspecialchars($catalogue['dateCatalogue'], ENT_QUOTES, 'UTF-8');
												$id = (int)$catalogue['id'];
											?>
												<tr>
													<td><?= $nom ?></td>
													<td><?= $categorie ?></td>
													<td><?= $type ?></td>
													<td><?= $dateCatalogue ?></td>
													<td>
														<?php if(($_SESSION['profil'] == "SuperAdmin") || ($_SESSION['profil'] == "Admin")): ?>
															<a href="#" data-toggle="modal" data-target="#imgsup<?= $id ?>">
																<img src="images/drop.png" align="middle" alt="supprimer" />
															</a>
															<?php echo  '<div id="imgsup'.$id.'"  class="modal fade " role="dialog">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-header" style="padding:35px 50px;">
																				<button type="button" class="close" data-dismiss="modal">&times;</button>
																					<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Suppression </b></h4>
																			</div>
																			<div class="modal-body" style="padding:40px 50px;">
																				<form role="form" class="" id="form" name="formulaire2" method="post" >
																					<input type="hidden" name="id" value="'.$id.'"/>
																					<input type="hidden" name="nom" value="'.$nom.'"/>
																					<input type="hidden" name="type" value="'.$type.'"/>
																					<div class="form-group" >
																						<h3>Voulez vous vraiment supprimer le catalogue</h3>';
																						echo '<h2>'.$nom.'<h2>
																					</div>
																					<div class="form-group" align="right">
																						<input type="submit" class="boutonbasic"  name="btnSupprimer" value="SUPPRIMER  >>"/>
																					</div>
																				</form>
																			</div>
																		</div>
																	</div>
																</div>';?>
														<?php endif; ?>
														<a href="catalogueDetail.php?i=<?= $id ?>">Détails</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</table>
							</div>
						</div>
				</div>
		</div>
	</body>
</html>
<!-- <td>	<a href="#" >
																															<?php if(($_SESSION['profil']=="SuperAdmin") || ($_SESSION['profil']=="Admin") ){	
																																echo '<a><img src="images/drop.png" align="middle" alt="supprimer"  data-toggle="modal" data-target="#imgsup'.$tab3["id"].'" /></a>';} ?>
																																<?php echo  "<a href=catalogueDetail.php?i=".$tab3['id']."  >Details</a>" ; ?>
																															<?php echo  '<div id="imgsup'.$tab3["id"].'"  class="modal fade " role="dialog">
																																	<div class="modal-dialog">
																																				<div class="modal-content">
																																					<div class="modal-header" style="padding:35px 50px;">
																																						<button type="button" class="close" data-dismiss="modal">&times;</button>
																																						<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Suppression </b></h4>
																																					</div>
																																					<div class="modal-body" style="padding:40px 50px;">
																																							<form role="form" class="" id="form" name="formulaire2" method="post" >
																																									<input type="hidden" name="id" value="'.$tab3["id"].'"/>
																																									<input type="hidden" name="nom" value="'.$tab3["nom"].'"/>
																																									<input type="hidden" name="type" value="'.$tab3["type"].'"/>
																																									<div class="form-group" >
																																									<h3>Voulez vous vraiment supprimer le catalogue</h3>';
																																											echo '<h2>'.$tab3["nom"].'<h2>
																																									</div>
																																									<div class="form-group" align="right">
																																											<input type="submit" class="boutonbasic"  name="btnSupprimer" value="SUPPRIMER  >>"/>
																																									</div>
																																							</form>
																																					</div>
																																				</div>
																																			</div>
																																	</div>';?>

																										</td> -->