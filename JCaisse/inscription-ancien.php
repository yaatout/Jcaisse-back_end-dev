<?php
/*
Résumé:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification :07/04/2016; 15-04-2018
*/

require('connection.php');

/**********************/
$nom                 =@htmlentities($_POST["nom"]);
$prenom              =@htmlentities($_POST["prenom"]);
$adresse             =@htmlentities($_POST["adresse"]);
$email               =@htmlentities($_POST["email"]);
$telPortable         =@htmlentities($_POST["telPortable"]);
$telFixe             =@htmlentities($_POST["telFixe"]);
$motdepasse          =@htmlentities($_POST["motdepasse"]);
$motdepasse2          =@htmlentities($_POST["motdepasse2"]);
$profil              ='Admin';
$annuler             =@$_POST["annuler"];
/***************/


//$date = new DateTime('25-02-2011');
$date = new DateTime();
//Récupération de l'année
$annee =$date->format('Y');
//Récupération du mois
$mois =$date->format('m');
//Récupération du jours
$jour =$date->format('d');

$dateString=$annee.'-'.$mois.'-'.$jour;

$dateString2=$jour.'-'.$mois.'-'.$annee;

if(!$annuler){
	if(($email) && ($nom) && ($prenom) && ($motdepasse) && ($motdepasse2))
		if($motdepasse==$motdepasse2){
				$motdepasse2=sha1($motdepasse);
				$sql0="SELECT * FROM utilisateur where email='".$email."'";
				$res0=mysql_query($sql0);
				if(!mysql_num_rows($res0)){
			   $sql1="insert into utilisateur (nom,prenom,adresse,email,telPortable,telFixe,motdepasse,dateinscription, profil) values('".$nom."','".$prenom."','".$adresse."','".$email."','".$telPortable."','".$telFixe."','".$motdepasse2."','".$dateString2."', 'Admin')";
			   $res1=@mysql_query($sql1) or die ("insertion utilisateur impossible-2");
			   $sql2="SELECT * FROM utilisateur ORDER BY idutilisateur DESC LIMIT 0,1";
						 if ($res2=mysql_query($sql2)){
								$ligne = mysql_fetch_row($res2);
								$idUtilisateur = $ligne[0];
								$proprietaire=1;
			
								$sql3="INSERT INTO acces (idutilisateur,proprietaire, profil) VALUES (".$idUtilisateur.", ".$proprietaire.", 'Admin')";
								$req3=@mysql_query($sql3) or die ("insertion dans acces impossible");
								
								
			
						 } else {
							echo "requette 2 sur le dernier user";
						 }
				   echo'<!DOCTYPE html>';
				   echo'<html>';
				   echo'<head>';
				   echo'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
				   echo'<script type="text/javascript" src="alertInscription.js"></script>';
				   echo'<script language="JavaScript">document.location="../index.php"</script>';
				   echo'</head>';
				   echo'</html>';
				   }else{
				   echo'<!DOCTYPE html>';
				   echo'<html>';
				   echo'<head>';
				   echo'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				   <link rel="stylesheet" href="css/bootstrap.css"> 
				   <script src="js/jquery-3.1.1.min.js"></script>
				   <script src="js/bootstrap.js"></script>';
				   
				   echo'<script type="text/javascript" src="alertEmail.js"></script>';
				   echo'<link rel="stylesheet" type="text/css" href="style.css">
					   <title>JOURNAL DE CAISSE</title>
						</head>
						<body onload="process()">
						<header>';
						echo'<table width="98%" align="center" border="1"><tr><td>'.
						'<center><img src="images/logo-yatout12.gif"></br></br><nav><ul><li></li>'.
						'</ul></nav></center></td></tr></table></header>
						
						
						
<center>		
<div class="row">
  <div class="col-gl-4 col-md-4 col-ms-6 col-sx-12 col-lg-offset-4 col-md-offset-4 col-ms-offset-3">
	 <div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Formulaire Inscription </h3></div>
			<div class="panel-body">
			
			  <table width="20%" align="center" border="0">
				<form role="form" class="formulaire2" id="form" method="post" action="inscription.php">
				
				<div class="form-group">
					<tr><td><label for="nom"> NOM </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre nom ici" id="nom" name="nom" size="40" value="'.$nom.'" /></td></tr>
					<tr><td><div class="help-block" id="helpNom"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="prenom">PRENOM </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre prenom ici" id="prenom" name="prenom" size="40" value="'.$prenom.'" /></td></tr>
					<tr><td><div class="help-block" id="helpPrenom"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="adresse">ADRESSE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre adresse" id="adresse" name="adresse" size="40" value="'.$adresse.'" /></td></tr>
					<tr><td><div class="help-block" id="helpAdresse"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="email">EMAIL </label></td></tr>
					<tr><td><input type="email" class="inputbasic" placeholder="adresse email" id="email" name="email" size="40" value="'.$email.'" /></td></tr>
					<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="portable">TEL. PORTABLE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="Telephone Portable" id="telPortable" name="telPortable" size="40" value="'.$telPortable.'" /></td></tr>
					<tr><td><div class="help-block" id="helpPortable"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="fixe">TEL. FIXE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="Telephone fixe" id="telFixe" name="telFixe" size="40" value="'.$telFixe.'" /></td></tr>
					<tr><td><div class="help-block" id="helpFixe"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="motdepasse1">MOT DE PASSE </label></td></tr>
					<tr><td><input type="password" placeholder="Mot de passe" class="inputbasic" id="motdepasse" name="motdepasse" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpmotdepasse"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="motdepasse2">MOT DE PASSE </label></td></tr>
					<tr><td><input type="password" placeholder="Confirmation du Mot de passe" class="inputbasic" id="motdepasse2" name="motdepasse2" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpmotdepasse2"></div></td></tr>
				</div>
				
				<tr><td align="center">

					<button type="submit" class="boutonbasic" id="inserer" name="inserer">ENVOYER</button>
					<button type="submit" class="boutonbasic" id="annuler" name="annuler"> ANNULER </button>
				</td></tr>
				
				</form></table></div></div></div></div></div></center>

		
						
			<script type="text/javascript" src="validation.js"></script>			
			</body>
			</html>';
			   
		}
	}else{
			   
			   
			   
			echo'<!DOCTYPE html>
			<html>
			<head>';
			echo'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				   <link rel="stylesheet" href="css/bootstrap.css"> 
				   <script src="js/jquery-3.1.1.min.js"></script>
				   <script src="js/bootstrap.js"></script>
			   <script type="text/javascript" src="alertMotdepasse.js"></script>
				<link rel="stylesheet" type="text/css" href="style.css">
				<title>JOURNAL DE CAISSE</title>
			</head>
			<body onload="process()">
			<header>';
		echo'<table width="98%" align="center" border="0"><tr><td>'.
		'<center><img src="images/logo-yatout12.gif"></br></br><nav><ul><li></li>'.
		'</ul></nav></center></td></tr></table></header><center>
		
		
		
<center>		
<div class="row">
  <div class="col-gl-4 col-md-4 col-ms-6 col-sx-12 col-lg-offset-4 col-md-offset-4 col-ms-offset-3">
	 <div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Formulaire Inscription</h3></div>
			<div class="panel-body">
			
			  <table width="20%" align="center" border="0">
				<form role="form" class="formulaire2" id="form" method="post" action="inscription.php">
				
				<div class="form-group">
					<tr><td><label for="nom"> NOM </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre nom ici" id="nom" name="nom" size="40" value="'.$nom.'" /></td></tr>
					<tr><td><div class="help-block" id="helpNom"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="prenom">PRENOM </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre prenom ici" id="prenom" name="prenom" size="40" value="'.$prenom.'" /></td></tr>
					<tr><td><div class="help-block" id="helpPrenom"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="adresse">ADRESSE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre adresse" id="adresse" name="adresse" size="40" value="'.$adresse.'" /></td></tr>
					<tr><td><div class="help-block" id="helpAdresse"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="email">EMAIL </label></td></tr>
					<tr><td><input type="email" class="inputbasic" placeholder="adresse email" id="email" name="email" size="40" value="'.$email.'" /></td></tr>
					<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="portable">TEL. PORTABLE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="Telephone Portable" id="telPortable" name="telPortable" size="40" value="'.$telPortable.'" /></td></tr>
					<tr><td><div class="help-block" id="helpPortable"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="fixe">TEL. FIXE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="Telephone fixe" id="telFixe" name="telFixe" size="40" value="'.$telFixe.'" /></td></tr>
					<tr><td><div class="help-block" id="helpFixe"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="motdepasse1">MOT DE PASSE </label></td></tr>
					<tr><td><input type="password" placeholder="Mot de passe" class="inputbasic" id="motdepasse" name="motdepasse" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpmotdepasse1"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="motdepasse2">MOT DE PASSE </label></td></tr>
					<tr><td><input type="password" placeholder="Confirmation du Mot de passe" class="inputbasic" id="motdepasse2" name="motdepasse2" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpmotdepasse2"></div></td></tr>
				</div>
				
				<tr><td align="center">

					<button type="submit" class="boutonbasic" id="inserer" name="inserer">ENVOYER</button>
					<button type="submit" class="boutonbasic" id="annuler" name="annuler"> ANNULER </button>
				</td></tr>
				
				</form></table></div></div></div></div></div></center>
		
		
		
		    </center>
			<script type="text/javascript" src="validation.js"></script>
			</body>
			</html>';		}
	else{
/**********************/
if((!$email) && (!$nom) && (!$prenom) && (!$motdepasse) && (!$motdepasse2) && (!$adresse) && (!$telPortable) && (!$telFixe)){
				   echo'<!DOCTYPE html>';
				   echo'<html>';
				   echo'<head>';
				   echo'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				   <link rel="stylesheet" href="css/bootstrap.css"> 
				   <link rel="stylesheet" href="css/datatables.min.css">
				   <script src="js/jquery-3.1.1.min.js"></script>
				   <script src="js/bootstrap.js"></script>';
				   }else{
				   echo'<!DOCTYPE html>';
				   echo'<html>';
				   echo'<head>';
				   echo'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				   <link rel="stylesheet" href="css/bootstrap.css"> 
				   <link rel="stylesheet" href="css/datatables.min.css">
				   <script src="js/jquery-3.1.1.min.js"></script>
				   <script src="js/bootstrap.js"></script>';
				   echo'<script type="text/javascript" src="alertchampVide.js"></script>';   
				   }

echo'<link rel="stylesheet" type="text/css" href="style.css">
	<title>JOURNAL DE CAISSE</title>
	</head>
	<body onload="process()">
	<header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<center><img src="images/logo-yatout12.gif"></br></br><nav><ul><li></li>'.
'</ul></nav></center></td></tr></table></header><center>



<center>		
<div class="row">
  <div class="col-gl-4 col-md-4 col-ms-6 col-sx-12 col-lg-offset-4 col-md-offset-4 col-ms-offset-3">
	 <div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Formulaire Inscription</h3></div>
			<div class="panel-body">
			
			  <table width="20%" align="center" border="0">
				<form role="form" class="formulaire2" id="form" method="post" action="inscription.php">
				
				<div class="form-group">
					<tr><td><label for="nom"> NOM </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre nom ici" id="nom" name="nom" size="40" value="'.$nom.'" /></td></tr>
					<tr><td><div class="help-block" id="helpNom"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="prenom">PRENOM </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre prenom ici" id="prenom" name="prenom" size="40" value="'.$prenom.'" /></td></tr>
					<tr><td><div class="help-block" id="helpPrenom"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="adresse">ADRESSE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="votre adresse" id="adresse" name="adresse" size="40" value="'.$adresse.'" /></td></tr>
					<tr><td><div class="help-block" id="helpAdresse"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="email">EMAIL </label></td></tr>
					<tr><td><input type="email" class="inputbasic" placeholder="adresse email" id="email" name="email" size="40" value="'.$email.'" /></td></tr>
					<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="portable">TEL. PORTABLE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="Telephone Portable" id="telPortable" name="telPortable" size="40" value="'.$telPortable.'" /></td></tr>
					<tr><td><div class="help-block" id="helpPortable"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="fixe">TEL. FIXE </label></td></tr>
					<tr><td><input type="text" class="inputbasic" placeholder="Telephone fixe" id="telFixe" name="telFixe" size="40" value="'.$telFixe.'" /></td></tr>
					<tr><td><div class="help-block" id="helpFixe"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="motdepasse1">MOT DE PASSE </label></td></tr>
					<tr><td><input type="password" placeholder="Mot de passe" class="inputbasic" id="motdepasse" name="motdepasse" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpmotdepasse1"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="motdepasse2">MOT DE PASSE </label></td></tr>
					<tr><td><input type="password" placeholder="Confirmation du Mot de passe" class="inputbasic" id="motdepasse2" name="motdepasse2" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpmotdepasse2"></div></td></tr>
				</div>
				
				<tr><td align="center">

					<button type="submit" class="boutonbasic" id="inserer" name="inserer">ENVOYER</button>
					<button type="submit" class="boutonbasic" id="annuler" name="annuler"> ANNULER </button>
				</td></tr>
				
				</form></table></div></div></div></div></div></center>
		

</center>
	<script type="text/javascript" src="validation.js"></script>
	</body>
	</html>';
	}
}else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}

?>
