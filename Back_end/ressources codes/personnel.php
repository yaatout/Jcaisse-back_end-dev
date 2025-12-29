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
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;;


if(!$_SESSION['iduser'])
	header('Location:index.php');
elseif (isset($_POST['btnEnregistrerPersonnel'])) {

		$nom=htmlspecialchars(trim($_POST['nom']));
		$prenom=htmlspecialchars(trim($_POST['prenom']));
		$motdepasse=sha1($nom);
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
		$caissier=0;
		
		if (isset($_POST['proprietaire'])) {
				$proprietaire=1;
			}
		if (isset($_POST['gerant'])) {
				$gerant=1;
			}
		if (isset($_POST['caissier'])) {
				$caissier=1;
			}
		if ($email) {
			 $sql1="insert into utilisateur (nom,prenom,email,motdepasse,dateinscription) values('".$nom."','".$prenom."','".$email."','".$motdepasse."','".$dateString."')";
  			 $res1=mysql_query($sql1) or die ("insertion personnel impossible =>".mysql_error() );

			 $sql2="SELECT * FROM utilisateur ORDER BY idutilisateur DESC LIMIT 0,1";
  			 if ($res2=mysql_query($sql2)){
					$ligne = mysql_fetch_row($res2);
					$idUtilisateur = $ligne[0];
					$sql3="INSERT INTO acces (idutilisateur, idBoutique,proprietaire,gerant,caissier) 
					VALUES ('".$idUtilisateur."','".$_SESSION['idBoutique']."', '".$proprietaire."', '".$gerant."', '".$caissier."')";
  					$req3=@mysql_query($sql3) or die ("insertion dans acces impossible");

  			 } else {
  			 	echo "requette 2 sur le dernier user";
  			 }
			$message="Utilisateur ajouter avec succes";
		} else{
			$message="mot de pass different";
		}
	
}elseif (isset($_POST['btnModifierPersonnel'])) {

		$idutilisateur=$_POST['idutilisateur'];

		$nom=$_POST['nom'];
		$prenom=$_POST['prenom'];
		$email=$_POST['email'];
		$proprietaire=0;
		$gerant=0;
		$caissier=0;
		
		if (isset($_POST['proprietaire'])) {
				$proprietaire=1;
			}
		if (isset($_POST['gerant'])) {
				$gerant=1;
			}
		if (isset($_POST['caissier'])) {
				$caissier=1;
			}
		 $sql2="UPDATE acces set proprietaire='".$proprietaire."',gerant='".$gerant."',caissier='".$caissier."' where idutilisateur=".$idutilisateur;
		     $res2=@mysql_query($sql2) or die ("mise à jour acces pour modPer impossible".mysql_error());

		  $sql3="UPDATE utilisateur set  `nom`='".$nom."',prenom='".$prenom."',email='".$email."' where idutilisateur=".$idutilisateur;
		  $res3=@mysql_query($sql3) or die ("mise à jour acces pour modPer impossible".mysql_error());

	
}elseif (isset($_POST['btnSupprimerPersonnel'])) {
	$idutilisateur=$_POST['idutilisateur'];

	$sql="DELETE FROM utilisateur WHERE idutilisateur=".$idutilisateur;
  	$res=@mysql_query($sql) or die ("suppression impossible personnel     ".mysql_error());

  	$sql5="DELETE FROM acces WHERE idutilisateur=".$idutilisateur;
  	$res5=@mysql_query($sql5) or die ("suppression impossible personnel acces      ".mysql_error());
}
if (isset($_POST['btnActiver'])) {
	$idutilisateur=$_POST['idutilisateur'];
	$activer=1;
	$sql3="UPDATE acces set  activer='".$activer."' where idutilisateur=".$idutilisateur;
		  $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
} elseif (isset($_POST['btnDesactiver'])) {
	$idutilisateur=$_POST['idutilisateur'];
	$activer=0;
	$sql3="UPDATE acces set  activer='".$activer."' where idutilisateur=".$idutilisateur;
		  $res3=@mysql_query($sql3) or die ("mise à jour acces pour activer ou pas ".mysql_error());
}
?>
<!DOCTYPE html>
<html>
<head>
	
	<meta charset="utf-8">
     <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/datatables.min.css">
	<script src="js/datatables.min.js"></script>
    <script> $(document).ready(function () { $("#exemple").DataTable();});</script>
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
	<title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
</head>
<body>
	
	<?php   require('header.php'); ?>
<div class="container">
	<div class="row" align="center">
		<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPersonnel">
                        <i class="glyphicon glyphicon-plus"></i>Ajouter un nouveau membre du personnel
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
					      <label for="inputEmail3" class="control-label">PRENOM</label>					    
					      <input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom">
					      <span class="text-danger" ></span>
					  </div>
					  <div class="form-group">
					      <label for="inputEmail3" class="control-label">NOM</label>
					      <input type="text" class="form-control" id="inputNom" name="nom" placeholder="Votre nom">
					      <span class="text-danger" ></span>
					  </div>
					 <div class="form-group">
					    <label for="inputEmail3" class="control-label">Email</label>
					    <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Votre email">
					    <span class="text-danger" ></span>
					  </div>
						<!--	 <div class="form-group row">
					    <label for="inputEmail3" class="col-sm-2 col-form-label">Adresse</label>
					    <div class="col-sm-3">
					      <input type="text" class="form-control" id="inputAdresse" name="adresse" placeholder="Votre adresse">
					    </div>
					  </div>
					  
					  <div class="form-group row">
					    <label for="inputPassword3" class="col-sm-2 col-form-label">Mot de passe</label>
					    <div class="col-sm-3">
					      <input type="password" class="form-control" id="inputPassword" name="motdepasse1" placeholder="Password">
					    </div>
					  </div>
					  <div class="form-group row">
					    <label for="inputPassword3" class="col-sm-2 col-form-label">Confirmer mot de passe</label>
					    <div class="col-sm-3">
					      <input type="password" class="form-control" id="inputPassword2" name="motdepasse2" placeholder="Password">
					    </div>
					  </div>
					  -->
					  <div class="form-group row">
					    <div class="col-sm-2">Profil</div>
					    <div class="col-sm-10">
					      	<div class="form-check">
						        <input class="form-check-input" type="checkbox" id="gridCheckProprietaire" name="proprietaire">
						        <label class="form-check-label" for="gridCheckProprietaire">
						          Proprietaire(Admin)
						        </label>
					      	</div>
						  	<div class="form-check">
							    <input class="form-check-input" type="checkbox" id="gridCheckGerant" name="gerant">
							    <label class="form-check-label" for="gridCheckGerant">
							        Gérant(e)
							     </label>
							      </div>
						    <div class="form-check">
						        <input class="form-check-input" type="checkbox" id="gridCheckCaissier" name="caissier">
						        <label class="form-check-label" for="gridCheckCaissier">
						          Caissiér(re)
						        </label>
						    </div>
						</div>
					  </div>
					  <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
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
              <li class="active"><a data-toggle="tab" href="#LISTEPERSONNEL">LISTE DES MEMBRES DU PERSONNEL</a></li>
            </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="LISTEPERSONNEL">
				    <table id="exemple" class="display" border="1" class="table table-bordered table-striped" id="userTable">
						<thead>
							<tr>
								<th>Prénom</th>
								<th>Nom</th>
							<!--	<th>Adresse</th> -->
								<th>Email</th>
								<th>Date inscription</th>
								<th>Propriétaire</th>
								<th>Gérant</th>
								<th>Caissier</th>
								<th>Opérations</th>
								<th>Etat</th>
								<th>Activer/Désactiver</th>
								
							</tr>
						</thead>	
                        <tfoot>
							<tr>
								<th>Prénom</th>
								<th>Nom</th>
							<!--	<th>Adresse</th> -->
								<th>Email</th>
								<th>Date inscription</th>
								<th>Propriétaire</th>
								<th>Gérant</th>
								<th>Caissier</th>
								<th>Opération</th>
								<th>Etat</th>
								<th>Activer/Désactiver</th>
								
							</tr>
						</tfoot>			
						<tbody>
							<?php 
							/*$sql4="SELECT nom,prenom,adresse,email,dateinscription,proprietaire,gerant,caissier
							 FROM utilisateur as u, acces as a 
							WHERE idBoutique ='".$_SESSION['idBoutique']."' AND a.idutilisateur = u.idutilisateur";
							if($res4 = mysql_query($sql4)) {
								while($ligne = mysql_fetch_array($res4)) { 
									?>*/
							$sql4="SELECT idutilisateur,proprietaire,gerant,caissier,activer
							 FROM acces  
							WHERE idBoutique =".$_SESSION['idBoutique'];
							$res4 = mysql_query($sql4) or die ("persoonel requête 4".mysql_error());
							while ($acces = mysql_fetch_array($res4)) {
								
								$sql5="SELECT nom,prenom,adresse,email,dateinscription
								 FROM utilisateur  
								WHERE idutilisateur =".$acces['idutilisateur'];
								if($res5 = mysql_query($sql5)) {
								while($utilisateur = mysql_fetch_array($res5)) { 
									?>
									<tr>
										<td> <?php echo  $utilisateur['prenom']; ?>  </td>
										<td> <?php echo  $utilisateur['nom']; ?>  </td>
										<!--<td> <?php echo  $utilisateur['adresse']; ?>  </td>-->
										<td> <?php echo  $utilisateur['email']; ?>  </td>
										<td> <?php echo  $utilisateur['dateinscription']; ?>  </td>
										<td> <?php echo  $acces['proprietaire']; ?>  </td>
										<td> <?php echo  $acces['gerant']; ?>  </td>
										<td> <?php echo  $acces['caissier']; ?>  </td>
										<td> 
											<a href="#" >
        										<img src="images/edit.png" <?php echo  "data-target=#imgmodifierPer".$acces['idutilisateur'] ; ?> align="middle" alt="modifier"  data-toggle="modal" /></a>&nbsp;&nbsp;
										    <a   href="#" >
										        <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerPer".$acces['idutilisateur'] ; ?> /></a>
										</td>

											<?php if ($acces['activer']==0) { ?>
												<td><span>Desactiver</span></td>
												<td><button type="button" class="btn btn-success" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Activer".$acces['idutilisateur'] ; ?> >
						                        Activer</button>
												</td>
												<?php 
											} else { ?>
												<td><span>Activer</span></td>
												<td><button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Desactiver".$acces['idutilisateur'] ; ?> >
												Desactiver</button></td>
											<?php }
											 ?>
											

											

					<div class="modal fade" <?php echo  "id=Activer".$acces['idutilisateur'] ; ?> tabindex="-1" role="dialog" 			aria-labelledby="myModalLabel">
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
									     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($acces['idutilisateur'])."" ; ?> >
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
        			<div class="modal fade" <?php echo  "id=Desactiver".$acces['idutilisateur'] ; ?> tabindex="-1" role="dialog" 		aria-labelledby="myModalLabel">
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
									     <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($acces['idutilisateur'])."" ; ?> >
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
		<div <?php echo  "id=imgmodifierPer".$acces['idutilisateur']."" ; ?>   class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Formulaire pour modifier un personnel</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
						    <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($acces['idutilisateur'])."" ; ?>>
							<label for="inputEmail3" class="control-label">PRENOM</label>					    
							<?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'. $utilisateur['prenom'].'"  >'; ?>
							<span class="text-danger" ></span>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM</label>
								<?php echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'. $utilisateur['nom'].'"  >;' ?>
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">EMAIL</label>					    
								<?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $utilisateur['email'].'"  >'; ?>
								<span class="text-danger" ></span>
							</div> 
							<div class="form-group ">
								<div class="">Profil</div> <br>
									<div class="">
										<div class="form-check ">
											<label class="form-check-label" for="gridCheckProprietaire" > Proprietaire(Admin)</label>
												<?php if ($acces['proprietaire']==1) {
													echo '<input class="form-check-input" type="checkbox" checked="" id="gridCheckProprietaire" name="proprietaire">';
													}else{
														echo '<input class="form-check-input" type="checkbox"  id="gridCheckProprietaire" name="proprietaire">';
													}; ?>
											
										</div>
										<div class="form-check ">
											<label class="form-check-label" for="gridCheckGerant"> Gérant(e)</label>
											<?php if ($acces['gerant']==1) {
													echo '<input class="form-check-input" type="checkbox" checked="" id="gridCheckGerant" name="gerant">';
													}else{
														echo '<input class="form-check-input" type="checkbox" id="gridCheckGerant" name="gerant">';
													}; ?>
										</div>
										<div class="form-check ">
											<label class="form-check-label" for="gridCheckCaissier">  Caissiér(re)</label>
											<?php if ($acces['caissier']==1) {
													echo '<input class="form-check-input" type="checkbox" checked="" id="gridCheckCaissier" name="caissier">';
													}else{
														echo '<input class="form-check-input" type="checkbox" id="gridCheckCaissier" name="caissier">';
													}; ?>
										</div>
									</div>
							</div>
						    <div class="modal-footer row">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
								<button type="submit" name="btnModifierPersonnel" class="btn btn-primary">Enregistrer</button>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!----------------------------------------------------------->
		<div <?php echo  "id=imgsuprimerPer".$acces['idutilisateur']."" ; ?>  class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Supprimer un personnel</h4>
					</div>
					<div class="modal-body">
						<form role="form" class="formulaire2" name="formulaire2" method="post" action="personnel.php">
						    <input type="hidden" name="idutilisateur" <?php echo  "value=". htmlspecialchars($acces['idutilisateur'])."" ; ?> >
							<label for="inputEmail3" class="control-label">PRENOM</label>					    
							<?php echo  '<input type="text" class="form-control" id="inputprenom" name="prenom" placeholder="Votre prenom"  value="'. $utilisateur['prenom'].'" disabled >'; ?>
							<span class="text-danger" ></span>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">NOM</label>
								echo  '<input type="text" class="form-control" id="inputprenom" name="nom" placeholder="Votre prenom"  value="'. $utilisateur['nom'].'" disabled >;' ?>
								<span class="text-danger" ></span>
							</div>
							<div class="form-group ">
								<label for="inputEmail3" class="control-label">EMAIL</label>					    
								<?php echo  '<input type="email" class="form-control" id="inputprenom" name="email" placeholder="Email"  value="'. $utilisateur['email'].'" disabled >'; ?>
								<span class="text-danger" ></span>
							</div> 
							<div class="form-group ">
								<div class="">Profil</div> <br>
									<div class="">
										<div class="form-check ">
											<label class="form-check-label" for="gridCheckProprietaire"> Proprietaire(Admin)</label>
											<input class="form-check-input" type="checkbox" id="gridCheckProprietaire" name="proprietaire">
										</div>
										<div class="form-check ">
											<label class="form-check-label" for="gridCheckGerant"> Gérant(e)</label>
											<input class="form-check-input" type="checkbox" id="gridCheckGerant" name="gerant">
										</div>
										<div class="form-check ">
											<label class="form-check-label" for="gridCheckCaissier">  Caissiér(re)</label>
											<input class="form-check-input" type="checkbox" id="gridCheckCaissier" name="caissier">
										</div>
									</div>
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
								} else {
								echo "Erreur de requête de base de données.".mysql_error();
								}
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
		
</body>
</html>