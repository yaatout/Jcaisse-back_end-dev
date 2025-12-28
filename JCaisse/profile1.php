<?php 
session_start();

require('connection.php');

require('declarationVariables.php');


if(!$_SESSION['iduser']){
	header('Location:../index.php');
}elseif (isset($_POST['btnModifierProfile'])) {

		$nom        =@htmlentities($_POST['nom']);
		$prenom     =@htmlentities($_POST['prenom']);
		$adresse    =@htmlentities($_POST['adresse']);
		$email      =@htmlentities($_POST['email']);
		$motdepasse1=@htmlentities($_POST['motdepasse1']);
		$motdepasse2=@htmlentities($_POST['motdepasse2']);
		$telPortable=@htmlentities($_POST['telPortable']);
		$telFixe    =@htmlentities($_POST['telFixe']);
		
		if (($motdepasse1!="")and($motdepasse2!="")and($motdepasse1==$motdepasse2)) {
			$motdepasse=sha1($motdepasse1);
			
			 $sql9="UPDATE utilisateur set nom='".$nom."',prenom='".$prenom."',adresse='".$adresse."',email='".$email."',telPortable='".$telPortable."',telFixe='".$telFixe."',motdepasse='".$motdepasse."' where idutilisateur=".$_SESSION['iduser'];
		     $res9=@mysql_query($sql9) or die ("mise à jour acces impossible");

		     /*******************************************
		     	UPDATE de l'utilisateur dans la session
		     ********************************************/
         	 $_SESSION['prenom']   = $prenom;
         	 $_SESSION['nomU']     = $nom;
        	$_SESSION['adresseU'] = $adresse;
         	 $_SESSION['email']    = $email;
         	 $_SESSION['telPortable']= $telPortable;
         	 $_SESSION['telFixe']    = $telFixe;
			$message="Utilisateur ajouter avec succes";
		} else{
			$message="mot de pass different";
		}
}else if (isset($_POST['btnModifierBoutique'])) {
	
	

		$nomB=htmlspecialchars(trim($_POST['nomB']));
		$adresseB=htmlspecialchars(trim($_POST['adresseB']));
		$type=htmlspecialchars(trim($_POST['type']));
		$categorie=htmlspecialchars($_POST['categorie']);

		if (!empty($nomB) and !empty($adresseB) and !empty($type) and !empty($categorie)) {
			# code...
			 $sql9="UPDATE boutique set nomBoutique='".$nomB."',adresseBoutique='".$adresseB."',type='".$type."',categorie='".$categorie."' where idBoutique=".$_SESSION['idBoutique'];
		     $res9=@mysql_query($sql9) or die ("mise à jour acces impossible");

		     /*******************************************
		     	UPDATE de la boutique dans la session
		     ********************************************/
         	$_SESSION['nomB']   = $nomB;
         	$_SESSION['adresseB']     = $adresseB;
        	$_SESSION['type'] = $type;
         	$_SESSION['categorie']    = $categorie;	
		
		}else{
			echo '<script type="text/javascript"> alert("Toutes les champs doit etre rempli");</script>';
		}

	
			
			
 }
require('entetehtml.php');
?>

<body>
	
		<?php 
		  require('header.php');
		?>
	<div class="container">	
		<div>
			<div class="row">
				<div class="col-lg-5  limitation">
					<h2>Utilisateur</h2>
					<form name="formulairePersonnel">

	            	   <div class="form-group row">
					      <label for="inputEmail3" class="col-sm-4 col-form-label">PRENOM</label>			
					      <div class="col-sm-7">		    
							<input type="text" class="form-control"  disabled="" <?php echo  "value=". htmlspecialchars($_SESSION['prenom'])."" ; ?> >
					      </div>
					  </div>
					  <div class="form-group row">
					      <label for="inputEmail3" class="col-sm-4 col-form-label">NOM</label>
					      <div class="col-sm-7">
					      	 <input type="text" class="form-control" disabled="" <?php echo  "value=". $_SESSION['nomU']."" ; ?>>
					      </div>
					  </div>
					 
					  <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-4 col-form-label">Adresse</label>
						    <div class="col-sm-7">
						      <input type="text" class="form-control" disabled="" <?php echo  "value=". $_SESSION['adresseU']."" ; ?>>
						    </div>
					  </div>
					  <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-4 col-form-label">Email</label>
						    <div class="col-sm-7">
						      <input type="email" class="form-control" disabled="" <?php echo  "value=". $_SESSION['email']."" ; ?>>
						    </div>
					  </div>
					  <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-4 col-form-label">Numero portable</label>
						    <div class="col-sm-7">
						      <input type="tel" name="telPortable" class="form-control" disabled="" <?php echo  "value=". $_SESSION['telPortable']."" ; ?>>
						    </div>
					  </div>
					  <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-4 col-form-label">Numero fixe</label>
						    <div class="col-sm-7">
						      <input type="tel" name="telFixe" class="form-control" disabled="" <?php echo  "value=". $_SESSION['telFixe']."" ; ?>>
						    </div>
					  </div>
					  <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-4 col-form-label">Vous étes :</label>
						    <div class="col-sm-7">
						       <?php if ($_SESSION['proprietaire']==1) {    echo 'proprietaire ';   } ?> 
							    <?php if ($_SESSION['gerant']==1) {    echo 'Gerant ';   } ?> 
							    <?php if ($_SESSION['caissier']==1) {    echo 'Caissier ';   } ?> 
						    </div>
					  </div>
					  <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modPersonnel">
                        			<i class="glyphicon glyphicon-plus"></i>Modifier votre profil
   							</button>
                      </div>
					</form>
				</div>

				<?php if ($_SESSION['proprietaire']==1) {?>
				<div class="col-lg-offset-1 col-md-6 limitation">
						<h3 >Boutique</h3>
						<form class="formulaire2" name="formulaire2" method="post" action="boutique.php">
							<div class="form-group row">
							    <label for="inputEmail3" class="col-sm-3 col-form-label">NOM BOUTIQUE</label>
							    <div class="col-sm-6">
							    	<input type="text" class="form-control" disabled="" <?php echo  "value=".$_SESSION['nomB']."" ; ?> disabled="">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">ADRESSE</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" disabled="" <?php echo  "value=".$_SESSION['adresseB']."" ; ?>>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">TYPE</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" disabled="" <?php echo  "value=".$_SESSION['type'] ."" ; ?>>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">CATEGORIE</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" disabled="" <?php echo  "value=".$_SESSION['categorie']."" ; ?>>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modBoutique">
						                        			<i class="glyphicon glyphicon-plus"></i>Modifier votre boutique
						   		</button>
							</div>
						</form>
				</div>
				<div class="modal fade" id="modBoutique" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		            <div class="modal-dialog" role="document">
		                <div class="modal-content">
		                    <div class="modal-header">
		                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                        <h4 class="modal-title" id="myModalLabel">Modification de la boutique</h4>
		                    </div>
		                    <div class="modal-body">
		                    	<form name="formulairePersonnel" method="post" action="profile.php">

					            	<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">NOM BOUTIQUE</label>
									    <div class="col-sm-5">
									      <input type="text" name="nomB" class="form-control" <?php echo  "value=".$_SESSION['nomB']."" ; ?> disabled="">
							    		</div>
						    		</div>
								    <div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">ADRESSE</label>
									    <div class="col-sm-5">
									      <input type="text" name="adresseB" class="form-control"  <?php echo  "value=".$_SESSION['adresseB']."" ; ?>>
									    </div>
								    </div>
									 
									<div class="form-group row">
									    <label for="inputEmail3" class="col-sm-4 col-form-label">TYPE</label>
									    <div class="col-sm-6">
										    <select name="type" id="type"> <?php 
												$sql10="SELECT * FROM typeBoutique";
												$res10=mysql_query($sql10) or die ("mise à jour acces impossible".mysql_error());
												while($ligne = mysql_fetch_row($res10)) { 
												echo'<option value= "'.$ligne[1].'">'.$ligne[1].'</option>';				
													} ?>
											</select>
										</div>
							   	    </div>
									  <div class="form-group row">
										    <label for="inputEmail3" class="col-sm-4 col-form-label">CATEGORIE</label>
										    <div class="col-sm-6">
										      <select name="categorie" id="categorie"> <?php 
													$sql11="SELECT * FROM categorie";
													$res11=mysql_query($sql11);
													while($ligne2 = mysql_fetch_row($res11)) { 
													echo'<option value= "'.$ligne2[1].'">'.$ligne2[1].'</option>';				
														} ?>
												</select>
									   		 </div>
									  </div>
									 
									    <div class="modal-footer">
				                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                                <button type="submit" name="btnModifierBoutique" class="btn btn-primary">Enregistrer</button>
				                       </div>
								</form>
		                       
		                    </div>

		                </div>
		            </div>
        		</div>
				<?php } ?>
			</div>		
		</div>
			<div class="modal fade" id="modPersonnel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                        <h4 class="modal-title" id="myModalLabel">Modification du Profil</h4>
	                    </div>
	                    <div class="modal-body">
	                    	<form name="formulairePersonnel" method="post" action="profile.php">

				            	  <div class="form-group row">
								      <label for="inputEmail3" class="col-sm-4 col-form-label">PRENOM</label>			
								      <div class="col-sm-6">		    
										<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  <?php echo  "value=". $_SESSION['prenom']."" ; ?> >
								      </div>
								  </div>
								  <div class="form-group row">
								      <label for="inputEmail3" class="col-sm-4 col-form-label">NOM</label>
								      <div class="col-sm-6">
								      	 <input type="text" class="form-control" id="inputNom" name="nom" placeholder="Votre nom"  <?php echo  "value=". $_SESSION['nomU']."" ; ?>>
								      </div>
								  </div>
								 
								 <div class="form-group row">
								    <label for="inputEmail3" class="col-sm-4 col-form-label">Adresse</label>
								    <div class="col-sm-6">
								      <input type="text" class="form-control" id="inputAdresse" name="adresse" placeholder="Votre adresse"  <?php echo  "value=". $_SESSION['adresseU']."" ; ?>>
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputEmail3" class="col-sm-4 col-form-label">Email</label>
								    <div class="col-sm-6">
								      <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Votre email"  <?php echo  "value=". $_SESSION['email']."" ; ?>>
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputEmail3" class="col-sm-4 col-form-label">Numero portable</label>
								    <div class="col-sm-6">
								      <input type="tel" name="telPortable" class="form-control"  <?php echo  "value=". $_SESSION['telPortable']."" ; ?>>
								    </div>
					  			</div>
							  <div class="form-group row">
								    <label for="inputEmail3" class="col-sm-4 col-form-label">Numero fixe</label>
								    <div class="col-sm-6">
								      <input type="tel" name="telFixe" class="form-control"  <?php echo  "value=". $_SESSION['telFixe']."" ; ?>>
								    </div>
							  </div>
								  <div class="form-group row">
								    <label for="inputPassword3" class="col-sm-4 col-form-label">Mot de passe</label>
								    <div class="col-sm-6">
								      <input type="password" class="form-control" id="inputPassword" name="motdepasse1" placeholder="Nouveau Password">
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputPassword3" class="col-sm-4 col-form-label">Confirmer mot de passe</label>
								    <div class="col-sm-6">
								      <input type="password" class="form-control" id="inputPassword2" name="motdepasse2" placeholder="Confirme Nouveau Password">
								    </div>
								  </div>
								    <div class="modal-footer">
			                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			                                <button type="submit" name="btnModifierProfile" class="btn btn-primary">Enregistrer</button>
			                       </div>
							</form>
	                       
	                    </div>

	                </div>
	            </div>
        	</div>

        	
	</div>
</body>
</html>