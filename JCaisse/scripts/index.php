<?php

session_start();

require('JCaisse/connection.php');

?>

<!DOCTYPE html> 
<html>
<head>
<meta charset="utf-8">
	<link rel="shortcut icon" href="JCaisse/images/favicon.png">
   <link rel="stylesheet" href="JCaisse/css/bootstrap.css"> 
   <script src="JCaisse/js/jquery-3.1.1.min.js"></script>
   <script src="JCaisse/js/bootstrap.js"></script>
   <script type="text/javascript" src="JCaisse/js/script.js"></script>
   <link rel="stylesheet" type="text/css" href="JCaisse/style.css">
  
   <title>AUTHENTIFICATION</title>
</head>
<body>
<?php


/**********************/
/*$nom     					  =@htmlentities($_POST["nom"]);
$prenom             =@htmlentities($_POST["prenom"]);*/
$email              =@htmlentities($_POST["email"]);
$motdepasse         =@htmlentities($_POST["motdepasse"]);


$_SESSION['symbole']=' ';
$_SESSION['devise']=0;

if($email){
/*****************************/
    $motdepasse2=sha1($motdepasse);
    $sql="select * from `aaa-utilisateur` where email='".$email."' and motdepasse='".$motdepasse2."'";
    $res=mysql_query($sql);
    if($tab=mysql_fetch_array($res)){
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

		$sql2="select * from `aaa-acces` where idutilisateur=".$_SESSION['iduser'];
		$res2=mysql_query($sql2);
		if ($tab2=mysql_fetch_array($res2)){
			$_SESSION['idBoutique'] = $tab2["idBoutique"];
			$_SESSION['proprietaire'] =$tab2['proprietaire'];
			$_SESSION['gerant'] =$tab2['gerant'];
			$_SESSION['caissier'] =$tab2['caissier'];
			$_SESSION['vendeur'] =$tab2['vendeur'];
			$_SESSION['activer'] =$tab2['activer'];
			if ($_SESSION['idBoutique']==0){
				echo'<!DOCTYPE html>';
				echo'<html>';
				echo'<head>';
				echo'<script language="JavaScript">document.location="JCaisse/creationBoutique.php"</script>';
				echo'</head>';
				echo'</html>';
			}
			else{
				$sql3="select * from `aaa-boutique` where idBoutique=".$_SESSION['idBoutique'];
				$res3=@mysql_query($sql3);
				if($tab3=@mysql_fetch_array($res3)){
					$_SESSION['nomB'] = $tab3["nomBoutique"];
					$_SESSION['labelB'] = $tab3["labelB"];
					$_SESSION['adresseB'] = $tab3["adresseBoutique"];
					$_SESSION['type'] = $tab3["type"];
					$_SESSION['categorie'] = $tab3["categorie"];
					$_SESSION['dateCB']  =$tab3["datecreation"];
					$_SESSION['activerB']  =$tab3["activer"];
					$_SESSION['imprimante'] =$tab3["imprimante"];
					$_SESSION['etiquette']  =$tab3["etiquette"];
					//echo  $_SESSION['activerB'];
					$_SESSION['enConfiguration']  =$tab3["enConfiguration"];

				}
			}
		}
    
        if(($_SESSION['profil']=="Admin")||($_SESSION['profil']=="SuperAdmin")||($_SESSION['profil']=="Accompagnateur"))
		    if ($_SESSION['activerB']==0){
					
					$msg_info = "<p>Votre Caisse n est pas encore ACTIVEE (ou bien elle est DESACTIVEE).</p>
					<p>Il faut contacter le service client, sur le numéro +221 77 524 35 94 !!!</p>";

				echo'<header>
						<table width="98%" align="center" border="0">
							<tr><td>'.
								'<center><img src="JCaisse/images/logojcaisse2.png"></center>
							</td></tr>
						</table>	
					</header>
					<center><section>';
				echo'<article><h3>Formulaire Authentification</h3>'.
					'<table align="center" border="0">
						<form id="form" class="formulaire1" role="form"  method="post" action="index.php">'.
						'<div class="form-group">
							<tr><td><input type="email" class="inputbasic" placeholder="Email (*)" id="email" name="email" size="35" value="" required /></td></tr>
							<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
						</div>'.
						'<div class="form-group">
							<tr><td><input type="password" class="inputbasic" placeholder="Mot de passe (*)" id="motdepasse" name="motdepasse" size="35" value="" required /></td></tr>
							<tr><td><div class="help-block" id="helpMotdepasse"></div></td></tr>
						</div>'.
							'<tr><td align="center"><input type="submit" name="envoyer" value="Connexion  >>"></td></tr>
							<tr><td align="center"><a href="JCaisse/inscription.php">Inscription</a>'.
						'<div id="apresEntre"></div>
						</form>
					</table></br></article>';
				echo'</section></center>';
			   
			}
			else{
				echo'<!DOCTYPE html>';
				echo'<html>';
				echo'<head>';
				echo'<script language="JavaScript">document.location="JCaisse/accueil.php"</script>';
				echo'</head>';
				echo'</html>';
        	} 
		else if(($_SESSION['gerant']==1)||($_SESSION['proprietaire']==1))
			if ($_SESSION['activerB']==0){
				
					$msg_info ="<p>Activation pas encore effectif.</p>
					<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";

				echo'<header>
						<table width="98%" align="center" border="0">
							<tr><td>'.
								'<center><img src="JCaisse/images/logojcaisse2.png"></center>
							</td></tr>
						</table>	
					</header>
					<center><section>';
				echo'<article><h3>Formulaire Authentification</h3>'.
					'<table align="center" border="0">
						<form id="form" class="formulaire1" role="form"  method="post" action="index.php">'.
						'<div class="form-group">
							<tr><td><input type="email" class="inputbasic" placeholder="Email (*)" id="email" name="email" size="35" value="" required /></td></tr>
							<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
						</div>'.
						'<div class="form-group">
							<tr><td><input type="password" class="inputbasic" placeholder="Mot de passe (*)" id="motdepasse" name="motdepasse" size="35" value="" required /></td></tr>
							<tr><td><div class="help-block" id="helpMotdepasse"></div></td></tr>
						</div>'.
							'<tr><td align="center"><input type="submit" name="envoyer" value="Connexion  >>"></td></tr>
							<tr><td align="center"><a href="JCaisse/inscription.php">Inscription</a>'.
						'<div id="apresEntre"></div>
						</form>
					</table></br></article>';
				echo'</section></center>';
			}
			else{
				echo'<!DOCTYPE html>';
				echo'<html>';
				echo'<head>';
				echo'<script language="JavaScript">document.location="JCaisse/accueil.php"</script>';
				echo'</head>';
				echo'</html>';
			}
		else if(($_SESSION['caissier']==1)||($_SESSION['vendeur']==1))
			if ($_SESSION['activerB']==0){

					$msg_info ="<p>Activation pas encore effectif.</p>
					<p> Il faut contacter le propriétaire de la Caisse !!!.</p>";

				echo'<header>
						<table width="98%" align="center" border="0">
							<tr><td>'.
								'<center><img src="JCaisse/images/logojcaisse2.png"></center>
							</td></tr>
						</table>	
					</header>
					<center><section>';
				echo'<article><h3>Formulaire Authentification</h3>'.
					'<table align="center" border="0">
						<form id="form" class="formulaire1" role="form"  method="post" action="index.php">'.
						'<div class="form-group">
							<tr><td><input type="email" class="inputbasic" placeholder="Email (*)" id="email" name="email" size="35" value="" required /></td></tr>
							<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
						</div>'.
						'<div class="form-group">
							<tr><td><input type="password" class="inputbasic" placeholder="Mot de passe (*)" id="motdepasse" name="motdepasse" size="35" value="" required /></td></tr>
							<tr><td><div class="help-block" id="helpMotdepasse"></div></td></tr>
						</div>'.
							'<tr><td align="center"><input type="submit" name="envoyer" value="Connexion  >>"></td></tr>
							<tr><td align="center"><a href="JCaisse/inscription.php">Inscription</a>'.
						'<div id="apresEntre"></div>
						</form>
					</table></br></article>';
				echo'</section></center>';
			}
			else{
				echo'<!DOCTYPE html>';
				echo'<html>';
				echo'<head>';
				echo'<script language="JavaScript">document.location="JCaisse/accueil.php"</script>';
				echo'</head>';
				echo'</html>';
			}
	}
	else{
		$msg_info = "Login ou Mot de passe incorrect";

		echo'<header>
				<table width="98%" align="center" border="0">
					<tr><td>'.
						'<center><img src="JCaisse/images/logojcaisse2.png"></center>
					</td></tr>
				</table>	
			</header>
			<center><section>';
		echo'<article><h3>Formulaire Authentification</h3>'.
			'<table align="center" border="0">
				<form id="form" class="formulaire1" role="form"  method="post" action="index.php">'.
				'<div class="form-group">
					<tr><td><input type="email" class="inputbasic" placeholder="Email (*)" id="email" name="email" size="35" value="" required /></td></tr>
					<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
				</div>'.
				'<div class="form-group">
					<tr><td><input type="password" class="inputbasic" placeholder="Mot de passe (*)" id="motdepasse" name="motdepasse" size="35" value="" required /></td></tr>
					<tr><td><div class="help-block" id="helpMotdepasse"></div></td></tr>
				</div>'.
					'<tr><td align="center"><input type="submit" name="envoyer" value="Connexion  >>"></td></tr>
					<tr><td align="center"><a href="JCaisse/inscription.php">Inscription</a>'.
				'<div id="apresEntre"></div>
				</form>
			</table></br></article>';
		echo'</section></center>';
	}
}
else{	
	echo'<header>
			<table width="98%" align="center" border="0">
				<tr><td>'.
					'<center><img src="JCaisse/images/logojcaisse2.png"></center>
				</td></tr>
			</table>	
		</header>
		<center><section>';
	echo'<article><h3>Formulaire Authentification</h3>'.
		'<table align="center" border="0">
			<form id="form" class="formulaire1" role="form"  method="post" action="index.php">'.
			'<div class="form-group">
				<tr><td><input type="email" class="inputbasic" placeholder="Email (*)" id="email" name="email" size="35" value="" required /></td></tr>
				<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
			</div>'.
			'<div class="form-group">
				<tr><td><input type="password" class="inputbasic" placeholder="Mot de passe (*)" id="motdepasse" name="motdepasse" size="35" value="" required /></td></tr>
				<tr><td><div class="help-block" id="helpMotdepasse"></div></td></tr>
			</div>'.
				'<tr><td align="center"><input type="submit" name="envoyer" value="Connexion  >>"></td></tr>
				<tr><td align="center"><a href="JCaisse/inscription.php">Inscription</a>'.
			'<div id="apresEntre"></div>
			</form>
		</table></br></article>';
	echo'</section></center>';
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