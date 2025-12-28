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

session_start();

require('connection.php');

require('declarationVariables.php');

if(!$_SESSION['iduser'])
	header('Location:../index.php');
elseif (isset($_POST['btnEnregistrerPersonnel'])) {

		$nom=htmlspecialchars(trim($_POST['nom']));
		$prenom=htmlspecialchars(trim($_POST['prenom']));
		$motdepasse=sha1($nom);
		$telephone=htmlspecialchars(trim($_POST['telephone']));
		$email=htmlspecialchars(trim($_POST['email']));
		//$email=$nom.'@'.$nom;

		/****************
		$adresse=htmlspecialchars(trim($_POST['adresse']));
		$email=htmlspecialchars(trim($_POST['email']));
		$motdepasse1=htmlspecialchars(trim($_POST['motdepasse1']));
		$motdepasse2=htmlspecialchars(trim($_POST['motdepasse2']));
		***************/

		$proprietaire=0;
		$gerant=0;
		$gestionnaire=0;
		$caissier=0;
		$vendeur=0;
		$vitrine=0;
		
		if (isset($_POST['proprietaire'])) {
				$proprietaire=1;
			}
		if (isset($_POST['gerant'])) {
				$gerant=1;
			}
		if (isset($_POST['gestionnaire'])) {
				$gestionnaire=1;
			}
		if (isset($_POST['caissier'])) {
				$caissier=1;
			}
		if (isset($_POST['vendeur'])) {
			$vendeur=1;
		}
		if (isset($_POST['vitrine'])) {
				$vitrine=1;
			}	
		if ($email) {
			$sql0="SELECT * FROM `aaa-utilisateur` WHERE email='".$email."' ";
			$res0 = mysql_query($sql0) or die ("persoonel requête 4".mysql_error());
			$existe = mysql_fetch_array($res0);
			if ($existe!=null){
				$message="Cette adresse email est deja utilise";
			}
			else{
				if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
					$entrepot=htmlspecialchars(trim($_POST['entrepot']));
					if($entrepot!='' || $entrepot!=null){
						$sql1="insert into `aaa-utilisateur` (idEntrepot,nom,prenom,telPortable,email,motdepasse,dateinscription) values('".$entrepot."','".$nom."','".$prenom."','".$telephone."','".$email."','".$motdepasse."','".$dateString."')";
						$res1=mysql_query($sql1) or die ("insertion personnel impossible =>".mysql_error() );
					}
					else{
						$sql1="insert into `aaa-utilisateur` (nom,prenom,telPortable,email,motdepasse,dateinscription) values('".$nom."','".$prenom."','".$telephone."','".$email."','".$motdepasse."','".$dateString."')";
						$res1=mysql_query($sql1) or die ("insertion personnel impossible =>".mysql_error() );
					}
				}
				else{
					$sql1="insert into `aaa-utilisateur` (nom,prenom,telPortable,email,motdepasse,dateinscription) values('".$nom."','".$prenom."','".$telephone."','".$email."','".$motdepasse."','".$dateString."')";
					$res1=mysql_query($sql1) or die ("insertion personnel impossible =>".mysql_error() );
				}
	
	
				 $sql2="SELECT * FROM `aaa-utilisateur` ORDER BY idutilisateur DESC LIMIT 0,1";
				   if ($res2=mysql_query($sql2)){
						$ligne = mysql_fetch_row($res2);
						$idUtilisateur = $ligne[0];
						$sql3="INSERT INTO `aaa-acces` (idutilisateur, idBoutique,proprietaire,gerant,gestionnaire,caissier,vendeur,vitrine) 
						VALUES ('".$idUtilisateur."','".$_SESSION['idBoutique']."', '".$proprietaire."', '".$gerant."', '".$gestionnaire."', '".$caissier."', '".$vendeur."', '".$vitrine."')";
						  $req3=@mysql_query($sql3) or die ("insertion dans acces impossible");
				   } else {
					   echo "requette 2 sur le dernier user";
				   }
				$message="Utilisateur ajouter avec succes";
			}

		} else{
			$message="mot de pass different";
		}
	
}
elseif (isset($_POST['btnModifierPersonnel'])) {

		$idutilisateur=$_POST['idutilisateur'];

		$nom=$_POST['nom'];
		$prenom=$_POST['prenom'];
		$email=$_POST['email'];
		$telephone=$_POST['telephone'];

		if ( isset($_POST['proprietaire']) ) { $proprietaire=1; } else { $proprietaire=0; }
		if ( isset($_POST['gerant']) ) { $gerant=1; } else { $gerant=0; }
		if ( isset($_POST['gestionnaire']) ) { $gestionnaire=1; } else { $gestionnaire=0; }
		if ( isset($_POST['caissier']) ) { $caissier=1; } else { $caissier=0; }
		if ( isset($_POST['vendeur']) ) { $vendeur=1; } else { $vendeur=0; }
		if ( isset($_POST['vitrine']) ) { $vitrine=1; } else { $vitrine=0; }
		

		$sql2="UPDATE `aaa-acces` set proprietaire='".$proprietaire."',gerant='".$gerant."',gestionnaire='".$gestionnaire."',caissier='".$caissier."',vendeur='".$vendeur."',vitrine='".$vitrine."' where idutilisateur='".$idutilisateur."' AND idBoutique='".$_SESSION['idBoutique']."' ";
		$res2=@mysql_query($sql2) or die ("mise à jour acces pour modPer impossible".mysql_error());
		if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
			$entrepot=htmlspecialchars(trim($_POST['entrepot']));
			if($entrepot!='' || $entrepot!=null){
				$sql3="UPDATE `aaa-utilisateur` set  `idEntrepot`='".$entrepot."',`nom`='".$nom."',prenom='".$prenom."',telPortable='".$telephone."',email='".$email."' where idutilisateur=".$idutilisateur;
				$res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());
			}
			else{
				$sql3="UPDATE `aaa-utilisateur` set  `nom`='".$nom."',prenom='".$prenom."',telPortable='".$telephone."',email='".$email."' where idutilisateur=".$idutilisateur;
				$res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());
			}
		}
		else{
			$sql3="UPDATE `aaa-utilisateur` set  `nom`='".$nom."',prenom='".$prenom."',telPortable='".$telephone."',email='".$email."' where idutilisateur=".$idutilisateur;
			$res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());
		}

}
elseif (isset($_POST['btnModifierPersonnelVitrine'])) {

	$idutilisateur=$_POST['idutilisateur'];

	$nom=$_POST['nom'];
	$prenom=$_POST['prenom'];
	$email=$_POST['email'];
	$telephone=$_POST['telephone'];

	if ( isset($_POST['tableauBord']) ) { $tableauBord=1; } else { $tableauBord=0; }
	if ( isset($_POST['ecommerce']) ) { $ecommerce=1; } else { $ecommerce=0; }
	if ( isset($_POST['commande']) ) { $commande=1; } else { $commande=0; }
	if ( isset($_POST['client']) ) { $client=1; } else { $client=0; }
	

	$sql2="UPDATE `aaa-acces` set tableauBord='".$tableauBord."',ecommerce='".$ecommerce."',commande='".$commande."',client='".$client."' where idutilisateur='".$idutilisateur."' AND idBoutique='".$_SESSION['idBoutique']."' ";
	$res2=@mysql_query($sql2) or die ("mise à jour acces pour modPer impossible".mysql_error());
	if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
		$entrepot=htmlspecialchars(trim($_POST['entrepot']));
		if($entrepot!='' || $entrepot!=null){
			$sql3="UPDATE `aaa-utilisateur` set  `idEntrepot`='".$entrepot."',`nom`='".$nom."',prenom='".$prenom."',telPortable='".$telephone."',email='".$email."' where idutilisateur=".$idutilisateur;
			$res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());
		}
		else{
			$sql3="UPDATE `aaa-utilisateur` set  `nom`='".$nom."',prenom='".$prenom."',telPortable='".$telephone."',email='".$email."' where idutilisateur=".$idutilisateur;
			$res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());
		}
	}
	else{
		$sql3="UPDATE `aaa-utilisateur` set  `nom`='".$nom."',prenom='".$prenom."',telPortable='".$telephone."',email='".$email."' where idutilisateur=".$idutilisateur;
		$res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());
	}

}
elseif (isset($_POST['btnSupprimerPersonnel'])) {
	$idutilisateur=$_POST['idutilisateur'];

  	$sql5="DELETE FROM `aaa-acces` WHERE idutilisateur='".$idutilisateur."' AND idBoutique='".$_SESSION['idBoutique']."' ";
  	$res5=@mysql_query($sql5) or die ("suppression impossible personnel acces      ".mysql_error());

	/*
		$sql="DELETE FROM `aaa-utilisateur` WHERE idutilisateur=".$idutilisateur;
		$res=@mysql_query($sql) or die ("suppression impossible personnel     ".mysql_error());
	*/
}
if (isset($_POST['btnActiver'])) {
	$idutilisateur=$_POST['idutilisateur'];
	$activer=1;
	$sql3="UPDATE `aaa-acces` set  activer='".$activer."' where idutilisateur='".$idutilisateur."' AND idBoutique='".$_SESSION['idBoutique']."' ";
		  $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
} elseif (isset($_POST['btnDesactiver'])) {
	$idutilisateur=$_POST['idutilisateur'];
	$activer=0;
	$sql3="UPDATE `aaa-acces` set  activer='".$activer."' where idutilisateur='".$idutilisateur."' AND idBoutique='".$_SESSION['idBoutique']."' ";
		  $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
}
require('entetehtml.php');
?>

<body>
	
	<?php   require('header.php'); ?>
	<div class="container">
		<div class="row" align="center" style="margin-bottom:10px;">
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPersonnel">
							<i class="glyphicon glyphicon-plus"></i>Ajouter un membre
			</button>
		</div>
		<div class="modal fade" id="addPersonnel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Ajout un Personnel</h4>
					</div>
					<div class="modal-body">
					<form name="formulairePersonnel" method="post" action="personnel.php">

					<div class="form-group">
						<label for="inputEmail3" class="control-label">PRENOM <font color="red">*</font></label>					    
						<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom" required="">
						<span class="text-danger" ></span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
						<input type="text" class="form-control" id="inputNom" name="nom" placeholder="Votre nom" required="">
						<span class="text-danger" ></span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="col-form-label">Telephone</label>
						<input type="text" class="form-control" id="inputTelephone" name="telephone" placeholder="Votre Telephone" required="">
						<span class="text-danger" ></span>
					</div>
					<div class="form-group">
						<label for="inputEmail3" class="control-label">Email <font color="red">*</font></label>
						<input type="email" class="form-control" id="inputEmail" name="email" placeholder="Votre email" required="">
						<span class="text-danger" ></span>
					</div>
					<?php if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")): ?>
						<div class="form-group row">
							<label for="inputPassword3" class="col-sm-2 col-form-label">Entrepot</label>
							<div class="col-sm-3">
								<select class="form-control" name="entrepot" >
									<option selected ></option>
									<option value="0" >Tous</option>
									<?php
										$sql1="SELECT * FROM `". $nomtableEntrepot."` ORDER BY idEntrepot DESC";
										$res1=mysql_query($sql1);
										while($depot = mysql_fetch_array($res1)) {
											echo'<option  value= "'.$depot['idEntrepot'].'">'.$depot['nomEntrepot'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
					<?php endif; ?>
						
						<div class="form-group ">
							<div class=""><label for="inputEmail3" class="control-label">Profil<font color="red"></font></label></div>
						<div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="gridCheckProprietaire" name="proprietaire" />
								<label class="form-check-label" for="gridCheckProprietaire">
								Proprietaire(Admin)
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="gridCheckGerant" name="gerant" />
								<label class="form-check-label" for="gridCheckGerant">
									Gérant(e)
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="gridCheckGestionnaire" name="gestionnaire" />
								<label class="form-check-label" for="gridCheckGestionnaire">
									Gestionnaire
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="gridCheckCaissier" name="caissier" />
								<label class="form-check-label" for="gridCheckCaissier">
								Caissiér(e)
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="gridCheckVendeur" name="vendeur" />
								<label class="form-check-label" for="gridCheckVendeur">
								Vendeur(se)
								</label>
							</div>
						</div>
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
				<div class="card " style=" ;">
					<!-- Default panel contents 
					<div class="card-header text-white bg-success">Liste du personnel</div>-->
					<div class="card-body">
						<div class="container">
							<ul class="nav nav-tabs"> 
							<li class="active"><a data-toggle="tab" href="#PERSONNELBOUTIQUE">LISTE DU PERSONNEL DE LA BOUTIQUE</a></li>
							<?php    
								$sqlVt="SELECT * FROM `aaa-boutique` WHERE idBoutique =".$_SESSION['idBoutique']."  "; 
								$resVt = mysql_query($sqlVt) or die ("persoonel requête 4".mysql_error());
								$boutiqueVt = mysql_fetch_array($resVt);
								if($boutiqueVt['vitrine']==1){ ?>
									<li><a data-toggle="tab" href="#PERSONNELVITRINE">LISTE DU PERSONNEL DE LA VITRINE</a></li>
								<?php 
								}
							?>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade in active" id="PERSONNELBOUTIQUE">
									<div class="table-responsive">                
										<label class="pull-left" for="nbEntreeUserBoutique">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeUserBoutique">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option> 
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputUserBoutique" placeholder="Rechercher..." autocomplete="off">
										<div id="resultsUserBoutique"><!-- content will be loaded here --></div>
									</div>
								</div>
								<div class="tab-pane fade" id="PERSONNELVITRINE">
									<div class="table-responsive">                
										<label class="pull-left" for="nbEntreeUserVitrine">Nombre entrées </label>
										<select class="pull-left" id="nbEntreeUserVitrine">
										<optgroup>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option> 
										</optgroup>       
										</select>
										<input class="pull-right" type="text" name="" id="searchInputUserVitrine" placeholder="Rechercher..." autocomplete="off">
										<div id="resultsUserVitrine"><!-- content will be loaded here --></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="modal fade" id="activerUser"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Activation</h4>
					</div>
					<div class="modal-body">
						<form name="formulaireVersement" method="post" action="personnel.php">
						<div class="form-group">
							<h2>Voulez vous vraiment activer ce personnel</h2>
							<input type="hidden" name="idutilisateur" id="idUser_Activer" >
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

		<div class="modal fade" id="desactiverUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Desactivation</h4>
					</div>
					<div class="modal-body">
						<form name="formulaireVersement" method="post" action="personnel.php">
						<div class="form-group">
							<h2>Voulez vous vraiment desactiver ce personnel</h2>
							<input type="hidden" name="idutilisateur" id="idUser_Desactiver">
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

		<div class="modal fade" id="modifierUser" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour modifier un personnel</h4>
					</div>
					<div class="modal-body">
						<form name="formulaire2" method="post" action="personnel.php">
							<input type="hidden" id="idUser_Mdf" name="idutilisateur" >
							<div class="form-group">
								<label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
								<input type="text" class="form-control" id="prenomUser_Mdf" name="prenom" required="" placeholder="Votre prenom" />
								<span class="text-danger" ></span>
							</div>
		
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
								<input type="text" class="form-control" id="nomUser_Mdf" name="nom" required="" placeholder="Votre prenom"    />
							</div>
							
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">Telephone<font color="red">*</font></label>
								<input type="text" class="form-control" id="telephoneUser_Mdf" name="telephone" required="" placeholder="Votre telephone" />
							</div>

							<div class="form-group ">
								<label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
								<input type="email" class="form-control" id="emailUser_Mdf" name="email" required="" placeholder="Email"  />
							</div> 

							<?php if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")): ?>
								<div class="form-group row">
									<label for="inputPassword3" class="col-sm-2 col-form-label">Entrepot</label>
									<div class="col-sm-3">
										<select class="form-control" name="entrepot" >
											<option selected id="entrepotUser_Mdf"></option>
											<option value="0" >Tous</option>
											<?php
												$sql1="SELECT * FROM `". $nomtableEntrepot."` ORDER BY idEntrepot DESC";
												$res1=mysql_query($sql1);
												while($depot = mysql_fetch_array($res1)) {
													echo'<option  value= "'.$depot['idEntrepot'].'">'.$depot['nomEntrepot'].'</option>';
												}
											?>
										</select>
									</div>
								</div>
							<?php endif; ?>

							<div class="form-group ">
								<div class=""><label for="inputEmail3" class="control-label">Profil<font color="red"></font></label></div>
									<div class="">
										<div class="form-check ">
											<input class="form-check-input" type="checkbox"  id="checkProprietaire_Mdf"  name="proprietaire" />
											<label class="form-check-label" for="checkProprietaire_Mdf" > Proprietaire(Admin)</label>
										</div>
										<div class="form-check ">
											<input class="form-check-input" type="checkbox" id="checkGerant_Mdf"  name="gerant">		
											<label class="form-check-label" for="checkGerant_Mdf"> Gérant(e)</label>
										</div>
										<div class="form-check ">
											<input class="form-check-input" type="checkbox" id="checkGestionnaire_Mdf" name="gestionnaire">		
											<label class="form-check-label" for="checkGestionnaire_Mdf"> Gestionnaire</label>
										</div>
										<div class="form-check ">
											<input class="form-check-input" type="checkbox" id="checkCaissier_Mdf" name="caissier">	
											<label class="form-check-label" for="checkCaissier_Mdf">  Caissiér(re)</label>
										</div>
										<div class="form-check ">
											<input class="form-check-input" type="checkbox" id="checkVendeur_Mdf"  name="vendeur">				
											<label class="form-check-label" for="checkVendeur_Mdf">  Vendeur(se)</label>
										</div>
										<div class="form-check ">	
											<input class="form-check-input" type="checkbox" id="checkVitrine_Mdf" name="vitrine">			
											<label class="form-check-label" for="checkVitrine_Mdf">  Vitrine</label>
										</div>
									</div>
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

		<div class="modal fade" id="supprimerUser" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Supprimer un personnel</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
						<input type="hidden" id="idUser_Spm" name="idutilisateur" >
							<div class="form-group">
								<label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
								<input type="text" class="form-control" disabled="true" id="prenomUser_Spm" name="prenom" required="" placeholder="Votre prenom" />
								<span class="text-danger" ></span>
							</div>
		
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
								<input type="text" class="form-control" disabled="true" id="nomUser_Spm" name="nom" required="" placeholder="Votre prenom"    />
							</div>
							
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">Telephone<font color="red">*</font></label>
								<input type="text" class="form-control" disabled="true" id="telephoneUser_Spm" name="telephone" required="" placeholder="Votre telephone" />
							</div>

							<div class="form-group ">
								<label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
								<input type="email" class="form-control" disabled="true" id="emailUser_Spm" name="email" required="" placeholder="Email"  />
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

		<div class="modal fade" id="modifierUserVitrine" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour modifier un personnel</h4>
					</div>
					<div class="modal-body">
						<form name="formulaire2" method="post" action="personnel.php">
							<input type="hidden" id="idUserVitrine_Mdf" name="idutilisateur" >
							<div class="form-group">
								<label for="inputEmail3" class="control-label">PRENOM<font color="red">*</font></label>					    
								<input type="text" class="form-control" id="prenomUserVitrine_Mdf" name="prenom" required="" placeholder="Votre prenom" />
								<span class="text-danger" ></span>
							</div>
		
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM<font color="red">*</font></label>
								<input type="text" class="form-control" id="nomUserVitrine_Mdf" name="nom" required="" placeholder="Votre prenom"    />
							</div>
							
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">Telephone<font color="red">*</font></label>
								<input type="text" class="form-control" id="telephoneUserVitrine_Mdf" name="telephone" required="" placeholder="Votre telephone" />
							</div>

							<div class="form-group ">
								<label for="inputEmail3" class="control-label">EMAIL<font color="red">*</font></label>					    
								<input type="email" class="form-control" id="emailUserVitrine_Mdf" name="email" required="" placeholder="Email"  />
							</div> 

							<?php if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")): ?>

								<div class="form-group row">
									<label for="inputPassword3" class="col-sm-2 col-form-label">Entrepot</label>
									<div class="col-sm-3">
										<select class="form-control" name="entrepot" >
											
										</select>
									</div>
								</div>

							<?php endif; ?>

							<div class="form-group ">
								<div class=""><label for="inputEmail3" class="control-label">Profil<font color="red"></font></label></div>
									<div class="">
										<div class="form-check ">
											<input class="form-check-input" type="checkbox"  id="checkTableauBord_Mdf"  name="tableauBord" />
											<label class="form-check-label" for="checkTableauBord_Mdf" > Tableau de Bord</label>
										</div>
										<div class="form-check ">
											<input class="form-check-input" type="checkbox" id="checkEcommerce_Mdf"  name="ecommerce">		
											<label class="form-check-label" for="checkEcommerce_Mdf"> E commerce</label>
										</div>
										<div class="form-check ">
											<input class="form-check-input" type="checkbox" id="checkCommande_Mdf" name="commande">		
											<label class="form-check-label" for="checkCommande_Mdf"> Commande </label>
										</div>
										<div class="form-check ">
											<input class="form-check-input" type="checkbox" id="checkClient_Mdf" name="client">	
											<label class="form-check-label" for="checkClient_Mdf">  Client </label>
										</div>
									</div>
							</div>
							<div class="modal-footer row"><font color="red">Les champs qui ont (*) sont obligatoires</font>
								</br>
								<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
								<button type="submit" name="btnModifierPersonnelVitrine" class="btn btn-primary">Enregistrer</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>


	</div>
		
</body>
</html>