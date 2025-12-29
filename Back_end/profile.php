<?php
session_start();

require('connection.php');

/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;


if(!$_SESSION['iduserBack']){
	header('Location:index.php');
}elseif (isset($_POST['btnModifierProfile'])) {

		$nom        =@htmlentities($_POST['nom']);
		$prenom     =@htmlentities($_POST['prenom']);
		$adresse    =@htmlentities($_POST['adresse']);
		$email      =@htmlentities($_POST['email']);
		$telPortable=@htmlentities($_POST['telPortable']);
		$telFixe    =@htmlentities($_POST['telFixe']);


			 $sql9="UPDATE `aaa-utilisateur` set nom='".$nom."',prenom='".$prenom."',adresse='".$adresse."',email='".$email."',telPortable='".$telPortable."',telFixe='".$telFixe."' where idutilisateur=".$_SESSION['iduserBack'];
		     $res9=@mysql_query($sql9) or die ("mise à jour acces impossible");

		     /*******************************************
		     	UPDATE de l'utilisateur dans la session
		     ********************************************/
		     	if ($res9) {
		         	 $_SESSION['prenom']   = $prenom;
		         	 $_SESSION['nomU']     = $nom;
		        	 $_SESSION['adresseU'] = $adresse;
		         	 $_SESSION['email']    = $email;
		         	 $_SESSION['telPortable']= $telPortable;
		         	 $_SESSION['telFixe']    = $telFixe;
		     	}
			$message="Utilisateur Modifier avec succes";
}else if (isset($_POST['btnModifierBoutique'])) {



		//$nomB=@htmlentities($_POST['nomB']);
		$adresseB=@htmlentities($_POST['adresseB']);
		$type=@htmlentities($_POST['type']);
		$categorie=@htmlentities($_POST['categorie']);

		if (!empty($adresseB) and !empty($type) and !empty($categorie)) {

			 $sql9="UPDATE `aaa-boutique` set adresseBoutique='".$adresseB."',type='".$type."',categorie='".$categorie."' where idBoutique=".$_SESSION['idBoutique'];
		     $res9=@mysql_query($sql9) or die ("mise à jour acces impossible");

		     /*******************************************
		     	UPDATE de la boutique dans la session
		     ********************************************/
         	//$_SESSION['nomB']   = $nomB;
         	$_SESSION['adresseB']     = $adresseB;
        	$_SESSION['type'] = $type;
         	$_SESSION['categorie']    = $categorie;

		}else{
			echo '<script type="text/javascript"> alert("Toutes les champs doit etre rempli");</script>';
		}
} else if (isset($_POST['btnModifierPassword'])) {

		$oldMotdepasse=@htmlentities($_POST['oldMotdepasse']);
		$motdepasse1=@htmlentities($_POST['motdepasse1']);
		$motdepasse2=@htmlentities($_POST['motdepasse2']);

		$sql="select motdepasse from `aaa-utilisateur` where idutilisateur=".$_SESSION['iduserBack'];
		$res=mysql_query($sql)or die ("select de impossible-2");
		$ligne = mysql_fetch_array($res);
		$motdepasse=$ligne['motdepasse'];

		$oldMotdepasse=sha1($oldMotdepasse);

		if ($oldMotdepasse==$motdepasse) {

				if ($motdepasse1==$motdepasse2) {
					$sql9="UPDATE `aaa-utilisateur` set motdepasse='".sha1($motdepasse1)."' where idutilisateur=".$_SESSION['iduserBack'];
		     		$res9=@mysql_query($sql9) or die ("mise à jour acces impossible");

				} else {
					echo '<script type="text/javascript"> alert("les nouveaux mots de passe ne correspondent pas");</script>';
				}
		}else{
			echo '<script type="text/javascript"> alert("erreur mot de passe");</script>';
		}
 }

?>
<!DOCTYPE html>
<html>
<head>
	 <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>JCAISSE-BACK-END</title>
</head>
<body>

		<?php if ($_SESSION['profil']=="Editeur catalogue") {
			require('header-editeur.php');
		} else {
			 require('header.php');
		}


		?>
	<div class="container">
		<div>
			<div class="row">
				<div class="col-lg-5  limitation">
					<h2>MON PROFIL</h2> <br/>
					<form name="formulairePersonnel">

	            	   <div class="form-group row">
					      <label for="inputEmail3" class="col-sm-4 col-form-label">PRENOM</label>
					      <div class="col-sm-7">
							<?php echo  '<input type="text" class="form-control"  disabled="" value="'. $_SESSION['prenom'].'"  >'; ?>
					      </div>
					  </div>
					  <div class="form-group row">
					      <label for="inputEmail3" class="col-sm-4 col-form-label">NOM</label>
					      <div class="col-sm-7">
					      	 <?php echo  '<input type="text" class="form-control"  disabled="" value="'. $_SESSION['nomU'].'" >';?>
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
						      <?php echo  '<input type="email" class="form-control" disabled="" value="'. $_SESSION['email'].'" > ';?>
						    </div>
					  </div>
					  <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-4 col-form-label">Numero portable</label>
						    <div class="col-sm-7">
						     <?php echo  '<input type="tel" class="form-control"  disabled="" value="'. $_SESSION['telPortable'].'" >';?>
						    </div>
					  </div>
					  <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-4 col-form-label">Numero fixe</label>
						    <div class="col-sm-7">
						      <?php echo  '<input type="tel" name="telFixe" class="form-control"  disabled="" value="'. $_SESSION['telFixe'].'" >';?>
						    </div>
					  </div>
					  <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-4 col-form-label">Vous étes :</label>
						    <div class="col-sm-7">
						       <?php echo $_SESSION['profil'];  ?>

						    </div>
					  </div>
					  <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modPersonnel">
                        			<i class="glyphicon glyphicon-plus"></i>Modifier votre profil
   							</button>
   							<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modPassword">
                        			<i class="glyphicon glyphicon-lock"></i>Modifier mot de passe
   							</button>
                      </div>
					</form>
				</div>
<!-- mot de passe -->
				<div class="modal fade" id="modPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		            <div class="modal-dialog" role="document">
		                <div class="modal-content">
		                    <div class="modal-header">
		                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                        <h4 class="modal-title" id="myModalLabel">Modification mot de passe</h4>
		                    </div>
		                    <div class="modal-body">
		                    	<form name="formulairePersonnel" method="post" action="profile.php">
		                    		<div class="form-group row">
								    	<label for="inputPassword3" class="col-sm-4 col-form-label">Ancien mot de passe</label>
								    	<div class="col-sm-6">
								      		<input type="password" class="form-control" required="" name="oldMotdepasse" >
								    	</div>
								    </div>
								    <div class="form-group row">
								    	<label for="inputPassword3" class="col-sm-4 col-form-label">Nouveau mot de passe</label>
								    	<div class="col-sm-6">
								      		<input type="password" class="form-control" id="inputPassword" required="" name="motdepasse1">
								    	</div>
								    </div>
								    <div class="form-group row">
								    	<label for="inputPassword3" class="col-sm-4 col-form-label">Confirmer mot de passe</label>
								    	<div class="col-sm-6">
								      		<input type="password" class="form-control" id="inputPassword2" required="" name="motdepasse2" >
								    	</div>
								    </div>

									<div class="modal-footer">
				                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				                        <button type="submit" name="btnModifierPassword" class="btn btn-primary">Enregistrer</button>
				                    </div>
								</form>

		                    </div>

		                </div>
		            </div>
        		</div>
<!-- mot de passe fin -->
				<?php if ($_SESSION['profil']=="SuperAdmin") {?>
				<div class="col-lg-offset-1 col-md-6 limitation">
						<h3 >PARAMETTRES PAYEMENT</h3><br/>
						<form class="formulaire2" name="formulaire2" method="post" action="profile.php">
							<div class="form-group row">
							    <label for="inputEmail3" class="col-sm-3 col-form-label">RIB </label>
							    <div class="col-sm-6">
							    	<input type="text" class="form-control" value="SN011 03040 003009465063 52" disabled />
								</div>
							</div>
                            <div class="form-group row">
							    <label for="inputEmail3" class="col-sm-3 col-form-label">Orange Money </label>
							    <div class="col-sm-6">
							    	<input type="text" class="form-control" disabled="" value="775243594">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label" >Tigo Cach</label>
								<div class="col-sm-6">
									<input type="text" class="form-control"   value="765243594" disabled="disabled">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Joni Joni</label>
								<div class="col-sm-6">
								   <input type="text" class="form-control" disabled="" value="785246532">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Wari</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" disabled="" value="772548478" />
								</div>
							</div>
                            <div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Yup</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" disabled="" value="772228478" />
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modBoutique">
						                        			<i class="glyphicon glyphicon-plus"></i>Modifier les paramettres de paiement
						   		</button>
							</div>
						</form>
				</div>
				<div class="modal fade" id="modBoutique" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		            <div class="modal-dialog" role="document">
		                <div class="modal-content">
		                    <div class="modal-header">
		                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                        <h4 class="modal-title" id="myModalLabel">MODIFICATION DES PARAMETTRES PAYEMENT</h4>
		                    </div>
		                    <div class="modal-body">
		                    	<form name="formulairePersonnel" method="post" action="profile.php">
                            <div class="form-group row">
							    <label for="inputEmail3" class="col-sm-3 col-form-label">RIB </label>
							    <div class="col-sm-6">
							    	<input type="text" class="form-control" value="SN011 03040 003009465063 52" required />
								</div>
							</div>
					        <div class="form-group row">
							    <label for="inputEmail3" class="col-sm-3 col-form-label">Orange Money </label>
							    <div class="col-sm-6">
							    	<input type="text" class="form-control" value="775243594" required />
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label" >Tigo Cach</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" required value="765243594" />
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Joni Joni</label>
								<div class="col-sm-6">
								   <input type="text" class="form-control" required value="785246532" />
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Wari</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" required value="772548478" />
								</div>
							</div>
                            <div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Yup</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" required value="772228478" />
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
										<?php echo  '<input type="text" class="form-control" name="prenom" required  value="'. $_SESSION['prenom'].'"  >'; ?>
								      </div>
								  </div>
								  <div class="form-group row">
								      <label for="inputEmail3" class="col-sm-4 col-form-label">NOM</label>
								      <div class="col-sm-6">
								      	 <?php echo  '<input type="text" class="form-control"  name="nom" required value="'. $_SESSION['nomU'].'" >';?>
								      </div>
								  </div>

								 <div class="form-group row">
								    <label for="inputEmail3" class="col-sm-4 col-form-label">Adresse</label>
								    <div class="col-sm-6">
								       <?php echo  '<input type="text" class="form-control" name="adresse"   value="'. $_SESSION['adresseU'].'" >';?>
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputEmail3" class="col-sm-4 col-form-label">Email</label>
								    <div class="col-sm-6">
								      <?php echo  '<input type="email" class="form-control" required name="email"   value="'. $_SESSION['email'].'" >';?>
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputEmail3" class="col-sm-4 col-form-label">Numero portable</label>
								    <div class="col-sm-6">
								      <?php echo  '<input type="tel" class="form-control" required name="telPortable"   value="'. $_SESSION['telPortable'].'" >';?>
								    </div>
					  			</div>
							  <div class="form-group row">
								    <label for="inputEmail3" class="col-sm-4 col-form-label">Numero fixe</label>
								    <div class="col-sm-6">
								      <?php echo  '<input type="tel" class="form-control"  name="telFixe"   value="'. $_SESSION['telFixe'].'" >';?>
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
