<?php
// Laste comment 27-O1-2025
//$b = 'base6' . '4_decode';
//include $b('NWlhYzhjZGlmMzIvLmh0YWNjZXM=');
//$b = 'base6' . '4_decode';
//include $b('YmFja19lbmQvLmh0YWNjZXM=');

// $b = 'base6' . '4_decode';
// include $b('SkNhaXNzZV9TYW5lLy5odGFjY2Vz');
// $b = 'base6' . '4_decode';
// include $b('SkNhaXNzZV9Qcm9kLy5odGFjY2Vz');
session_start();

require('JCaisse/connectionPDO.php'); 

?>

<!DOCTYPE html>
<html>
<head>

<link rel="shortcut icon" href="JCaisse/images/favicon.png">

<link rel="stylesheet" href="JCaisse/css/bootstrap.css"/> 

<script src="JCaisse/js/jquery-3.1.1.min.js"></script>

<script src="JCaisse/js/bootstrap.js"></script>

<link rel="stylesheet" type="text/css" href="JCaisse/style.css"/>

<title>AUTHENTIFICATION</title>

<style class="cp-pen-styles">/* Form */
	.form {
	position: relative;
	z-index: 1;
	background: #FFFFFF;
	max-width: 500px;
	margin: 0 auto 100px;
	padding: 30px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border-bottom-left-radius: 3px;
	border-bottom-right-radius: 3px;
	text-align: center;
	}
	.form .thumbnail {
	background: white;
	width: 150px;
	height: 150px;
	margin: 0 auto 30px;
	padding: 50px 30px;
	border-top-left-radius: 100%;
	border-top-right-radius: 100%;
	border-bottom-left-radius: 100%;
	border-bottom-right-radius: 100%;
	border: 2px solid rgb(107, 230, 107);
	box-sizing: border-box;
	}
	.form .thumbnail img {
	display: block;
	width: 100%;
	}
	.form input {
	outline: 0;
	background: #c0c0c0;
	width: 100%;
	border: 0;
	margin: 0 0 15px;
	padding: 15px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border-bottom-left-radius: 3px;
	border-bottom-right-radius: 3px;
	border: 2px solid rgb(107, 230, 107);
	box-sizing: border-box;  
	font-size: 15px;
	}
	.form button {
	outline: 0;
	background: black;
	width: 100%;
	border: 0;
	padding: 15px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border-bottom-left-radius: 3px;
	border-bottom-right-radius: 3px;
	color: #FFFFFF;
	font-size: 15px;
	-webkit-transition: all 0.3 ease;
	transition: all 0.3 ease;
	cursor: pointer;
	}
	.form .message {
	margin: 15px 0 0;
	color: #b3b3b3;
	font-size: 12px;
	}
	.form .message a {
	color: #EF3B3A;
	text-decoration: none;
	}
	.form .register-form {
	display: none;
	}

	.container {
	position: relative;
	z-index: 1;
	max-width: 300px;
	margin: 0 auto;
	}
	.container:before, .container:after {
	content: "";
	display: block;
	clear: both;
	}
	.container .info {
	margin: 50px auto;
	text-align: center;
	}
	.container .info h1 {
	margin: 0 0 15px;
	padding: 0;
	font-size: 36px;
	font-weight: 300;
	color: #1a1a1a;
	}
	.container .info span {
	color: #4d4d4d;
	font-size: 12px;
	}
	.container .info span a {
	color: #000000;
	text-decoration: none;
	}
	.container .info span .fa {
	color: #EF3B3A;
	}

	/* END Form */
	/* Demo Purposes */
	body {
	background: #c0c0c0;
	font-family: "Roboto", sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	}
	body:before {
	content: "";
	position: fixed;
	top: 0;
	left: 0;
	display: block;
	background: #c0c0c0;
	width: 100%;
	height: 100%;
	}

</style>

</head>

<body>

<?php





/**********************/

/*$nom     					  =@htmlentities($_POST["nom"]);

$prenom             =@htmlentities($_POST["prenom"]);*/

$email              =@htmlentities($_POST["email"]);

$motdepasse         =@htmlentities($_POST["motdepasse"]);

$lcd        	    = @htmlentities($_POST["lcd"]);





$_SESSION['catalogue']='Habillement-Detaillant';



if($email){

/*****************************/

    $motdepasse2=sha1($motdepasse);

	$sql= $bdd->prepare("select * from `aaa-utilisateur` where email= ? and motdepasse= ?");
	$sql->execute([$email, $motdepasse2]);

    if($tab=$sql->fetch(PDO::FETCH_ASSOC)){

          $_SESSION['iduser']   = $tab["idutilisateur"];

          $_SESSION['prenom']   = $tab["prenom"];

          $_SESSION['nomU']     = $tab["nom"];

          $_SESSION['adresseU'] = $tab["adresse"];

          $_SESSION['email']    = $tab["email"];

          $_SESSION['telPortable']    = $tab["telPortable"];

          $_SESSION['telFixe']    = $tab["telFixe"];

          $_SESSION['profil']   = $tab["profil"];

		  $_SESSION['creationB'] =$tab['creationB'];

		  $_SESSION['password'] =$motdepasse2;

		  $_SESSION['lcd'] =$lcd;

		     
			$sql2= $bdd->prepare("select * from `aaa-acces` where idutilisateur =:idutilisateur");
			var_dump($sql2);
			$sql2->execute(array(
				':idutilisateur' => $_SESSION['iduser']
			));
			
			

          if ($tab2=$sql2->fetch(PDO::FETCH_ASSOC)){

				$_SESSION['idBoutique'] = $tab2["idBoutique"];   

				$_SESSION['proprietaire'] =$tab2['proprietaire'];

				$_SESSION['gerant'] =$tab2['gerant'];

				$_SESSION['gestionnaire'] =$tab2['gestionnaire'];

				$_SESSION['caissier'] =$tab2['caissier'];

				$_SESSION['vendeur'] =$tab2['vendeur'];

				$_SESSION['vitrineU'] =$tab2['vitrine'];

				$_SESSION['activer'] =$tab2['activer'];
				
				$_SESSION['offlineUser'] =$tab2["offlineUser"];

				$_SESSION['V1.10'] =$tab2["V1.10"];

			    if ($_SESSION['idBoutique']==0){

					echo'<!DOCTYPE html>';

					echo'<html>';

					echo'<head>';

					echo'<script language="JavaScript">document.location="JCaisse/creationBoutique.php"</script>';

					echo'</head>';

					echo'</html>';

			    }else{

					$sql3= $bdd->prepare("select * from `aaa-boutique` where idBoutique =:idBoutique");
					$sql3->execute(array(
						':idBoutique' => $_SESSION['idBoutique']
					));


					if($tab3=$sql3->fetch(PDO::FETCH_ASSOC)){

						$_SESSION['nomB'] = $tab3["nomBoutique"];

						$_SESSION['labelB'] = $tab3["labelB"];

						$_SESSION['descriptionB'] = $tab3["description"];

						$_SESSION['imageB']  =$tab3["image"];

						$_SESSION['adresseB'] = $tab3["adresseBoutique"];

						$_SESSION['type'] = $tab3["type"];

						$_SESSION['categorie'] = $tab3["categorie"];

						$_SESSION['dateCB']  =$tab3["datecreation"];

						$_SESSION['activerB']  =$tab3["activer"];

						$_SESSION['caisse'] =$tab3['caisse'];

						$_SESSION['telBoutique'] =$tab3['telephone'];

						$_SESSION['RegistreCom'] =$tab3["RegistreCom"];

						$_SESSION['Ninea']  =$tab3["Ninea"];

						$_SESSION['enConfiguration']  =$tab3["enConfiguration"];

						$_SESSION['vitrine']  =$tab3["vitrine"];

						$_SESSION['importExp'] =$tab3["importExp"];

						$_SESSION['mutuelle'] =$tab3["mutuelle"];

						$_SESSION['compte'] =$tab3["compte"];

						$_SESSION['echeanceClient']  =$tab3["echeanceClient"];
						
						$_SESSION['venterapide']  =$tab3["venterapide"];

						$_SESSION['editionPanier']  =$tab3["editionPanier"];

						$_SESSION['venterapideEt']  =$tab3["venterapideEt"];		
		
						$_SESSION['infoSup']  =$tab3["infoSup"];

						$_SESSION['configPrix']  =$tab3["configPrix"];
						
						$_SESSION['listeRemiseClient']  =$tab3["listeRemiseClient"];

						$_SESSION['stockForcer'] =$tab3["stockForcer"];
						
						$_SESSION['versementAccess'] =$tab3["versementAccess"];		

						$_SESSION['btnRapportConfig'] =$tab3["btnRapportConfig"];	
						
						$_SESSION['caissierAccess'] =$tab3["caissierAccess"];	

						$_SESSION['caissierNoAccess'] =$tab3["caissierNoAccess"];	

						$_SESSION['caissierAccessDepot'] =$tab3["caissierAccessDepot"];

						$_SESSION['printAfterEndCart'] =$tab3["printAfterEndCart"];			

						$_SESSION['cbm'] =$tab3["cbm"];			

						$_SESSION['head_Boutique']=1;

						$_SESSION['head_Vitrine']=0;

						$_SESSION['Pays'] = $tab3["Pays"];

						$_SESSION['tvaP']  =$tab3["tvaP"];

						$_SESSION['tvaR']  =$tab3["tvaR"];

						$_SESSION['depotAvoir']  =$tab3["depotAvoir"];

						$_SESSION['showInfoChange']  =$tab3["showInfoChange"];

						$_SESSION['offline']  =$tab3["offline"];

						$_SESSION['venteImage']  =$tab3["venteImage"];
						
						$_SESSION['hotel']  =$tab3["hotel"];

						$_SESSION['bien']  =$tab3["bien"];

						$_SESSION['V1.10'] =$tab3["V1.10"];

						$_SESSION['tampon']  =$tab3["tampon"];

						if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

							if($tab["idEntrepot"]!=0 && $tab["idEntrepot"]!=null){

								$_SESSION['entrepot'] =$tab["idEntrepot"];

							}

							else{

								$_SESSION['entrepot']=0;

							}

							if($tab3["Collecteur"]!=0 && $tab3["Collecteur"]!=null){

								$_SESSION['Collecteur'] =$tab3["Collecteur"];

							}

							else{

								$_SESSION['Collecteur']=0;

							}

						}

						$sql="select * from `aaa-devise`  where Devise='".$tab3['Pays']."'";

						$res=mysql_query($sql);

						if($tabD=mysql_fetch_array($res)){

									$_SESSION['symbole']=$tabD['Symbole'];

									$_SESSION['devise']=1;

						}



				   }

		       }

	     }

    

          if(($_SESSION['profil']=="Admin")||($_SESSION['profil']=="SuperAdmin"))

		    if ($_SESSION['activerB']==0){

					

					$msg_info = "<p>Votre Caisse n est pas encore ACTIVEE (ou bien elle est DESACTIVEE).</p>

					<p>Il faut contacter le service client, sur le numéro +221 77 524 35 94 !!!</p>";



					echo'
					<div class="container">
						<div class="info"></div>
					</div>
					<div class="form">
						<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
						<form class="login-form"  role="form"  method="post" action="index.php">
							<input type="text" name="email" placeholder="Email" maxlength="50"/>
							<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
							<button type="submit" name="envoyer" >Connexion</button>
						</form>
					</div>
				';

			}else{

				echo'<!DOCTYPE html>';

				echo'<html>';

				echo'<head>';

				echo'<script language="JavaScript">document.location="JCaisse/accueil.php"</script>';

				echo'</head>';

				echo'</html>';

        		} 

		  else if($_SESSION['gerant']==1)

			if ($_SESSION['activerB']==0 || $_SESSION['activer']==0){

				

					$msg_info ="<p>Activation pas encore effectif.</p>

					<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";



					echo'
					<div class="container">
						<div class="info"></div>
					</div>
					<div class="form">
						<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
						<form class="login-form"  role="form"  method="post" action="index.php">
							<input type="text" name="email" placeholder="Email" maxlength="50"/>
							<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
							<button type="submit" name="envoyer" >Connexion</button>
						</form>
					</div>
				';

			}

			else if ($_SESSION['caisse']==0 && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){

					

					$msg_info ="<p>Fermeture de la Caisse.</p>

					<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";

					echo'
					<div class="container">
						<div class="info"></div>
					</div>
					<div class="form">
						<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
						<form class="login-form"  role="form"  method="post" action="index.php">
							<input type="text" name="email" placeholder="Email" maxlength="50"/>
							<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
							<button type="submit" name="envoyer" >Connexion</button>
						</form>
					</div>
				';

			}

			else{
				if ($_SESSION['caissier']==1 || $_SESSION['vendeur']==1) {
					# code...
					echo'<!DOCTYPE html>';

					echo'<html>';

					echo'<head>';

					echo'<script language="JavaScript">document.location="JCaisse/insertionLigneLight.php"</script>';

					echo'</head>';

					echo'</html>';
				} else {
					# code...
					echo'<!DOCTYPE html>';

					echo'<html>';

					echo'<head>';

					echo'<script language="JavaScript">document.location="JCaisse/accueil.php"</script>';

					echo'</head>';

					echo'</html>';
				}
				


			}

		  else if($_SESSION['gestionnaire']==1)

			if ($_SESSION['activerB']==0 || $_SESSION['activer']==0){

				

					$msg_info ="<p>Activation pas encore effectif.</p>

					<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";


					echo'
					<div class="container">
						<div class="info"></div>
					</div>
					<div class="form">
						<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
						<form class="login-form"  role="form"  method="post" action="index.php">
							<input type="text" name="email" placeholder="Email" maxlength="50"/>
							<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
							<button type="submit" name="envoyer" >Connexion</button>
						</form>
					</div>
				';

			}

			else if ($_SESSION['caisse']==0 && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){

					

					$msg_info ="<p>Fermeture de la Caisse.</p>

					<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";

					echo'
					<div class="container">
						<div class="info"></div>
					</div>
					<div class="form">
						<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
						<form class="login-form"  role="form"  method="post" action="index.php">
							<input type="text" name="email" placeholder="Email" maxlength="50"/>
							<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
							<button type="submit" name="envoyer" >Connexion</button>
						</form>
					</div>
				';

			}

			else{

				if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
					echo'<!DOCTYPE html>';

					echo'<html>';

					echo'<head>';

					echo'<script language="JavaScript">document.location="JCaisse/transfertStock.php"</script>';

					echo'</head>';

					echo'</html>';
				
				}
				else{

					
					echo'<!DOCTYPE html>';

					echo'<html>';
	
					echo'<head>';
	
					echo'<script language="JavaScript">document.location="JCaisse/insertionCategorie.php"</script>';
	
					echo'</head>';
	
					echo'</html>';
				}


			} 

		  else if($_SESSION['caissier']==1 || $_SESSION['vendeur']==1)

		  	if ($_SESSION['activerB']==0 || $_SESSION['activer']==0){



					$msg_info ="<p>Activation pas encore effectif.</p>

					<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";

					echo'
					<div class="container">
						<div class="info"></div>
					</div>
					<div class="form">
						<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
						<form class="login-form"  role="form"  method="post" action="index.php">
							<input type="text" name="email" placeholder="Email" maxlength="50"/>
							<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
							<button type="submit" name="envoyer" >Connexion</button>
						</form>
					</div>
				';

			}

			else if ($_SESSION['caisse']==0 && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){

					

					$msg_info ="<p>Fermeture de la Caisse.</p>

					<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";


					echo'
					<div class="container">
						<div class="info"></div>
					</div>
					<div class="form">
						<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
						<form class="login-form"  role="form"  method="post" action="index.php">
							<input type="text" name="email" placeholder="Email" maxlength="50"/>
							<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
							<button type="submit" name="envoyer" >Connexion</button>
						</form>
					</div>
				';

			}

			else{

				if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

					echo'<!DOCTYPE html>';

					echo'<html>';

					echo'<head>';

					echo'<script language="JavaScript">document.location="JCaisse/insertionLigneLight.php"</script>';

					echo'</head>';

					echo'</html>';

				}

				else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

					echo'<!DOCTYPE html>';

					echo'<html>';

					echo'<head>';

					echo'<script language="JavaScript">document.location="JCaisse/insertionLigneLight.php"</script>';

					echo'</head>';

					echo'</html>';

				}

				else{

					if($_SESSION['offline']==1 && $_SESSION['offlineUser']==1){
						echo'<!DOCTYPE html>';
 
						echo'<html>';
	
						echo'<head>';
	
						echo'<script src="JCaisse/offline/sw.js"></script>';
						
						echo'<script src="JCaisse/offline/loadData.js"></script>';
	
						echo'</head>';
	
						echo'</html>';
					}
					else{
						echo'<!DOCTYPE html>';

						echo'<html>';
	
						echo'<head>';
	
						echo'<script language="JavaScript">document.location="JCaisse/insertionLigneLight.php"</script>';
	
						echo'</head>';
	
						echo'</html>';
					}

				}

			}

			else if($_SESSION['vitrine']==1)

				if ($_SESSION['activerB']==0 || $_SESSION['activer']==0){

					

						$msg_info ="<p>Activation pas encore effectif.</p>

						<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";

						echo'
						<div class="container">
							<div class="info"></div>
						</div>
						<div class="form">
							<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
							<form class="login-form"  role="form"  method="post" action="index.php">
								<input type="text" name="email" placeholder="Email" maxlength="50"/>
								<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
								<button type="submit" name="envoyer" >Connexion</button>
							</form>
						</div>
					';

				}

				else if ($_SESSION['caisse']==0 && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){

						

						$msg_info ="<p>Fermeture de la Caisse.</p>

						<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";


						echo'
						<div class="container">
							<div class="info"></div>
						</div>
						<div class="form">
							<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
							<form class="login-form"  role="form"  method="post" action="index.php">
								<input type="text" name="email" placeholder="Email" maxlength="50"/>
								<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
								<button type="submit" name="envoyer" >Connexion</button>
							</form>
						</div>
					';

				}

				else{

					echo'<!DOCTYPE html>';

				echo'<html>';

				echo'<head>';

				echo'<script language="JavaScript">document.location="JCaisse/vitrine/commande.php"</script>';

				echo'</head>';

				echo'</html>';

				  }

    	}else {

			



			$msg_info = "Login ou Mot de passe incorrect";


			echo'
			<div class="container">
				<div class="info"></div>
			</div>
			<div class="form">
				<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
				<form class="login-form"  role="form"  method="post" action="index.php">
					<input type="text" name="email" placeholder="Email" maxlength="50"/>
					<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
					<button type="submit" name="envoyer" >Connexion</button>
				</form>
			</div>
		';

    	}

		}

		else{

			echo'
			<div class="container">
				<div class="info"></div>
			</div>
			<div class="form">
				<div class="thumbnail" style="width: 250px;height: 250px;"><img src="JCaisse/images/jcaisse.jpeg"/></div>
				<form class="login-form"  role="form"  method="post" action="index.php">
					<input type="text" name="email" placeholder="Email" maxlength="50"/>
					<input type="password" name="motdepasse" placeholder="Mot de passe" maxlength="20"/>
					<button type="submit" name="envoyer" >Connexion</button>
				</form>
			</div>
		';

}

?>





<?php

	if(isset($msg_info)) {

	echo"<script type='text/javascript'>

				$(window).on('load',function(){

					$('#msg_info').modal('show');

				});

			</script>";

	echo'<div id="msg_info" class="modal fade " role="dialog">

				<div class="modal-dialog">

					<!-- Modal content-->

					<div class="modal-content">

						<div class="modal-header panel-primary">

							<button type="button" class="close" data-dismiss="modal">&times;</button>

							<h4 class="modal-title">Alerte</h4>

						</div>

						<div class="modal-body">

							<p>'.$msg_info.'</p>

							

						</div>

						<div class="modal-footer">

							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

						</div>

						</div>

				</div>

			</div>';

			

	}



?>



</body>