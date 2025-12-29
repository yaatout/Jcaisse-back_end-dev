<?php 
/*
Résum� :
Commentaire :
Version : 2.1
see also :
Auteur : EL hadji mamadou korka
Date de cr�ation : 15-04-2018
Date derni�re modification :  15-04-2018
*/



if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}

if(!$_SESSION['iduserBack'])
	header('Location:index.php');
elseif (isset($_POST['btnEnregistrerPersonnel'])) {

		$nomE=htmlspecialchars(trim($_POST['nomE']));
		$duree=$_POST['duree'];
		$pourcentage=$_POST['pourcentage'];
		$ordre=$_POST['ordre'];
		$description=$_POST['description'];
		

		if ($nomE) {
			 
			$req3 = $bdd->prepare("INSERT INTO `aaa-etapesaccompagnement` (nometape,nbremois,pourcentage,ordre,description,activer) 
			   values (:nom,:nbr,:pourC,:ord,:descr,:act) ");
			$req3->execute(array(
				'nom'=>$nomE,
				'nbr'=>$duree,
				'pourC'=>$pourcentage,
				'ord'=>$ordre,
				'descr'=>$description,
				'act'=>0
			))  or die(print_r($req3->errorInfo()));   
			$req3->closeCursor(); 
			 
			$message="Utilisateur ajouter avec succes";
		} else{
			$message="mot de pass different";
		}

		  
	
}elseif (isset($_POST['btnModifierPersonnel'])) {

		$nomE=htmlspecialchars(trim($_POST['nomE']));
		$duree=$_POST['duree'];
		$pourcentage=$_POST['pourcentage'];
		$ordre=$_POST['ordre'];
		$description=$_POST['description'];
		$idetape=$_POST['idetape'];
		
		$nomEInitial=htmlspecialchars(trim($_POST['nomEInitial']));
		$dureeInitial=$_POST['dureeInitial'];
		$pourcentageInitial=$_POST['pourcentageInitial'];
		$ordreInitial=$_POST['ordreInitial'];
		$descriptionInitial=$_POST['descriptionInitial'];

		if(($nomE==$nomEInitial)&&($duree==$dureeInitial)&&($pourcentage==$pourcentageInitial)&&($ordre==$ordreInitial)&&($description==$descriptionInitial)){
			echo '<script type="text/javascript"> alert("INFO : AUCUNE MODIFICATION POUR CETTE ETAPE ...");</script>';

		}else{

			
			$req2 = $bdd->prepare("UPDATE `aaa-etapesaccompagnement` set  `nometape`=:nom ,nbremois=:nbr,pourcentage=:pourC,
							description='".mysql_real_escape_string($description)."',ordre=:ord where idetape=:id");
            $req2->execute(array(
					'nom'=>$nomE,
					'nbr'=>$duree,
					'pourC'=>$pourcentage,
					'descr'=>$description,
					'ord'=>$ordre,
					'id'=>$idetape
				 )) or die(print_r($req2->errorInfo()));
            $req2->closeCursor();    
					
		}
	
}elseif (isset($_POST['btnSupprimerPersonnel'])) {
	
	$idetape=$_POST['idetape'];

	$req2 = $bdd->prepare("DELETE FROM `aaa-etapesaccompagnement` WHERE idetape=:id");
	$req2->execute(array('id'=>$idetape )) or die(print_r($req2->errorInfo()));
	$req2->closeCursor();    

}
if (isset($_POST['btnActiver'])) {
	$idetape=$_POST['idetape'];
	$activer=1;


	$req2 = $bdd->prepare("UPDATE `aaa-etapesaccompagnement` set  activer=:act where idetape=:id");
    $req2->execute(array(
					'act'=>$activer,
					'id'=>$idetape
				 )) or die(print_r($req2->errorInfo()));
    $req2->closeCursor();    
					
	
} elseif (isset($_POST['btnDesactiver'])) {
	$idetape=$_POST['idetape'];
	$activer=0;
	$req2 = $bdd->prepare("UPDATE `aaa-etapesaccompagnement` set  activer=:act where idetape=:id");
    $req2->execute(array(
		'act'=>$activer,
		'id'=>$idetape
	)) or die(print_r($req2->errorInfo()));
    $req2->closeCursor(); 
}

?>

<div class="container">
	<div class="row" align="center">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addParamettre">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter une étape
   		</button>
	</div>
	<div class="modal fade" id="addParamettre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Ajout d'une étape</h4>
                    </div>
                    <div class="modal-body">
                    <form name="formulaireParamettre" method="post" action="paramettreSalaire.php">

	            	  <div class="form-group">
					      <label for="inputnomE" class="control-label">NOM ETAPE <font color="red">*</font></label>					    
					      <input type="text" class="form-control" id="nomE" name="nomE" placeholder="Le nom de l'etape ici..." required="">
					      <span class="text-danger" ></span>
					  </div>
					  <div class="form-group">
					      <label for="inputDuree" class="control-label">DUREE <font color="red">*</font></label>
					      <input type="number" class="form-control" id="duree" name="duree" placeholder="nombre de mois" required="">
					      <span class="text-danger" ></span>
					  </div>
					 <div class="form-group">
					    <label for="inputPourcentage" class="control-label">POURCENTAGE <font color="red">*</font></label>
					    <input type="number" class="form-control" id="pourcentage" name="pourcentage" placeholder="Le pourcentage ici" required="">
					    <span class="text-danger" ></span>
					  </div>
					<div class="form-group">
					    <label for="inputOrdre" class="control-label">ORDRE <font color="red">*</font></label>
					    <input type="number" class="form-control" id="ordre" name="ordre" placeholder="Le numero d'ordre ici" required="">
					    <span class="text-danger" ></span>
					  </div>
					 <div class="form-group">
					    <label for="inputOrdre" class="control-label">DESCRIPTION </label>
					    <input type="test" class="form-control" id="description" name="description" placeholder="La description ici" >
					    <span class="text-danger" ></span>
					  </div> 
					  <div class="modal-footer">
					  <font color="red">Les champs qui ont (*) sont obligatoires</font>
                                </br><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                <button type="submit" name="btnEnregistrerPersonnel" class="btn btn-primary">Enregistrer</button>
                       </div>
					</form>
                    </div>

                </div>
            </div>
    </div>
		<div class="row">
			<div class="">
				
			</div>
			<div class="">
				<div class="card " style=" ;">
				  <!-- Default panel contents 
				  <div class="card-header text-white bg-success">Liste du personnel</div>-->
				  <div class="card-body">
                  <div class="container" align="center">
            <ul class="nav nav-tabs"> 
              <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES ETAPES D'ACCOMPAGNEMENT</a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTEPERSONNEL">
				    <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
						<thead>
							<tr>
								
								<th>Nom étape</th>
								<th>Durée</th>
								<th>Pourcentage</th>
								<th>Ordre</th>
								<th>Description</th>
								<th>Opérations</th>
								<th>Etat</th>
								<th>Activer/Désactiver</th>
							</tr>
						</thead>	
                        <tfoot>
							<tr>
								<th>Nom étape</th>
								<th>Durée</th>
								<th>Pourcentage</th>
								<th>Ordre</th>
								<th>Description</th>
								<th>Opérations</th>
								<th>Etat</th>
								<th>Activer/Désactiver</th>
							</tr>
						</tfoot>			
						<tbody>
							<?php 
							
							
							$stmt = $bdd->prepare("SELECT * FROM `aaa-etapesaccompagnement`");
							$stmt->execute();
							$etapes = $stmt->fetchAll(PDO::FETCH_ASSOC);

							foreach($etapes as $etape){
							//while($etape=mysql_fetch_array($res4)) {
						?>
									<tr>
										<td> <?php echo  $etape['nometape']; ?>  </td>
										<td> <?php echo  $etape['nbremois']; ?> mois </td>
										<td> <?php echo  $etape['pourcentage']; ?> %  </td>
										<td> <?php echo  $etape['ordre']; ?>  </td>
										<td> <?php echo  $etape['description']; ?>  </td>
										
										<td> 
										<?php echo'<a href="#"><img src="images/edit.png" data-target=#imgmodifierPer'.$etape['idetape'].' align="middle" alt="modifier"  data-toggle="modal" /></a>';
										
											if($etape['activer']==0){ ?>
												
												<a   href="#" >
													<img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPer".$etape['idetape'] ; ?> /></a>
													
													<?php }else{ 
													
													//echo '<a   href="#"><img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" /></a>'
													
													 } 

											 if ($etape['activer']==0) { ?>
												<td><span>Desactiver</span></td>
												<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$etape['idetape'] ; ?> >
						                        Activer</button>
												</td>
												<?php 
											} else { ?>
												<td><span>Activer</span></td>
												<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$etape['idetape'] ; ?> >
												Desactiver</button></td>
											<?php }
											
											
											 ?>
											
											
										    
											
										</td>

										<div class="modal fade" <?php echo  "id=Activer".$etape['idetape'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title" id="myModalLabel">Activation</h4>
													</div>
													<div class="modal-body">
														<form name="formulaireVersement" method="post" action="paramettreSalaire.php">
														<div class="form-group">
															<h2>Voulez vous vraiment activer cette etape</h2>
															<input type="hidden" name="idetape" <?php echo  "value=". htmlspecialchars($etape['idetape'])."" ; ?> >
														</div>
														<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																<button type="submit" name="btnActiver" class="btn btn-primary">Activer</button>
														</div>
														</form>
													</div>

												</div>
											</div>
										</div>
										<div class="modal fade" <?php echo  "id=Desactiver".$etape['idetape'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title" id="myModalLabel">Desactivation</h4>
													</div>
													<div class="modal-body">
														<form name="formulaireVersement" method="post" action="paramettreSalaire.php">
														<div class="form-group">
															<h2>Voulez vous vraiment desactiver cette etape</h2>
															<input type="hidden" name="idetape" <?php echo  "value=". htmlspecialchars($etape['idetape'])."" ; ?> >
														</div>
														<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
																<button type="submit" name="btnDesactiver" class="btn btn-primary">Desactiver</button>
														</div>
														</form>
													</div>

												</div>
											</div>
										</div>
										<!----------------------------------------------------------->
										<!----------------------------------------------------------->
										<!----------------------------------------------------------->								
										<div <?php echo  "id=imgmodifierPer".$etape['idetape']."" ; ?>   class="modal fade" role="dialog">
											
											
											
											
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">Formulaire pour modifier une etape</h4>
													</div>
													<div class="modal-body">
														<form name="formulaire2" method="post" action="paramettreSalaire.php">
														
															<input type="hidden" name="idetape" <?php echo 'value="'.$etape['idetape'].'"' ; ?> />
															
															
															<div class="form-group">
																<label for="inputNomE" class="control-label">NOM ETAPE<font color="red">*</font></label>					    
																<?php echo  '<input type="text" class="form-control" id="nomE" name="nomE" required="" placeholder="Nom Etape ici..."  value="'. $etape['nometape'].'"  >'; ?>
																<input type="hidden" name="nomEInitial" <?php echo  "value=".$etape['nometape']."" ; ?> />
																<span class="text-danger" ></span>
															</div>
									
															<div class="form-group ">
																<label for="inputDuree" class="control-label">DUREE<font color="red">*</font></label>
																<?php echo  '<input type="number" class="form-control" id="duree" name="duree" required="" placeholder="nombre de mois"  value="'. $etape['nbremois'].'"  >'; ?>
																<input type="hidden" name="dureeInitial" <?php echo  "value=".$etape['nbremois']."" ; ?> />
																<span class="text-danger" ></span>
															</div>
														
														<div class="form-group ">
															<label for="inputPourcentage" class="control-label">POURCENTAGE<font color="red">*</font></label>					    
															<input type="number" class="form-control" id="pourcentage" name="pourcentage" required="" placeholder="Le pourcentage ici"  <?php echo  'value="'. $etape['pourcentage'].'"'; ?> />
															<input type="hidden" name="pourcentageInitial" <?php echo  "value=".$etape['pourcentage']."" ; ?> />
															<span class="text-danger" ></span>
														</div> 
															
														<div class="form-group">
														<label for="inputOrdre" class="control-label">ORDRE<font color="red">*</font></label>
														<input type="number" class="form-control" id="ordre" name="ordre" required="" placeholder="Le numero d'ordre ici" <?php echo  'value="'. $etape['ordre'].'"'; ?> />
															<input type="hidden" name="ordreInitial" <?php echo  "value=".$etape['ordre']."" ; ?> />
														<span class="text-danger" ></span>
													</div>
													
													<div class="form-group">
														<label for="inputOrdre" class="control-label">DESCRIPTION </label>
														<input type="test" class="form-control" id="description" name="description" placeholder="La description ici" <?php echo  'value="'. $etape['description'].'"'; ?> />
															<input type="hidden" name="descriptionInitial" <?php echo  "value=".$etape['description']."" ; ?> />
														<span class="text-danger" ></span>
													</div> 
															
															<div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
																</br>
																<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
																<button type="submit" name="btnModifierPersonnel" class="btn btn-primary">Enregistrer</button>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>
										<!----------------------------------------------------------->
											<div <?php echo  "id=imgsuprimerPer".$etape['idetape']."" ; ?>  class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title">Supprimer une etape</h4>
														</div>
														<div class="modal-body">
															<form role="form" class="formulaire2" name="formulaire2" method="post" action="paramettreSalaire.php">
																<input type="hidden" name="idetape" <?php echo  "value=".$etape['idetape']."" ; ?> />
																
																
																<div class="form-group">
																	<label for="inputNomE" class="control-label">NOM ETAPE<font color="red">*</font></label>					    
																	<?php echo  '<input type="text" class="form-control" id="nomE" name="nomE" disabled="" placeholder="Nom Etape ici..."  value="'. $etape['nometape'].'"  >'; ?>
																	<input type="hidden" name="nomEInitial" <?php echo  "value=".$etape['nometape']."" ; ?> />
																	<span class="text-danger" ></span>
																</div>
										
																<div class="form-group ">
																	<label for="inputDuree" class="control-label">DUREE<font color="red">*</font></label>
																	<?php echo  '<input type="number" class="form-control" id="duree" name="duree" disabled="" placeholder="nombre de mois"  value="'. $etape['nbremois'].'"  >'; ?>
																	<input type="hidden" name="dureeInitial" <?php echo  "value=".$etape['nbremois']."" ; ?> />
																	<span class="text-danger" ></span>
																</div>
															
															<div class="form-group ">
																<label for="inputPourcentage" class="control-label">POURCENTAGE<font color="red">*</font></label>					    
																<input type="number" class="form-control" id="pourcentage" name="pourcentage" disabled="" placeholder="Le pourcentage ici"  <?php echo  'value="'. $etape['pourcentage'].'"'; ?> />
																<input type="hidden" name="pourcentageInitial" <?php echo  "value=".$etape['pourcentage']."" ; ?> />
																<span class="text-danger" ></span>
															</div> 
																
															<div class="form-group">
															<label for="inputOrdre" class="control-label">ORDRE<font color="red">*</font></label>
															<input type="number" class="form-control" id="ordre" name="ordre" disabled="" placeholder="Le numero d'ordre ici" <?php echo  'value="'. $etape['ordre'].'"'; ?> />
																<input type="hidden" name="ordreInitial" <?php echo  "value=".$etape['ordre']."" ; ?> />
															<span class="text-danger" ></span>
														</div>
														
														<div class="form-group">
															<label for="inputOrdre" class="control-label">DESCRIPTION </label>
															<input type="test" class="form-control" id="description" name="description" disabled="" placeholder="La description ici" <?php echo  'value="'. $etape['description'].'"'; ?> />
															<span class="text-danger" ></span>
														</div> 
																
																
																<div class="modal-footer row">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
																	<button type="submit" name="btnSupprimerPersonnel" class="btn btn-primary">Supprimer</button>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										<!----------------------------------------------------------->
									</tr>
								<?php		  
								 }
							
							?>		
						</tbody>			
					</table>
				  </div>
					
				</div>
			</div>
             </div>
					
				</div>
			</div>
		</div>
		
