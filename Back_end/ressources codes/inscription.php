<?php
/*
Résumé:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification :07/04/2016; 15-04-2018
*/

mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

/**********************/
$nom                 =@htmlentities($_POST["nom"]);
$prenom              =@htmlentities($_POST["prenom"]);
$adresse             =@htmlentities($_POST["adresse"]);
$email               =@htmlentities($_POST["email"]);
$telPortable         =@htmlentities($_POST["telPortable"]);
$telFixe             =@htmlentities($_POST["telFixe"]);
$motdepasse          =@htmlentities($_POST["motdepasse"]);
$profil              =@htmlentities($_POST["profil"]);
$annuler             =@$_POST["annuler"];
/***************/


//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');


$dateString=$annee.'-'.$mois.'-'.$jour;

if(!$annuler){
if($email){
	$motdepasse2=sha1($motdepasse);
  // $sql1="insert into utilisateur (nom,prenom,adresse,email,motdepasse,dateinscription,profil) values('".$nom."','".$prenom."','".$adresse."','".$email."','".$motdepasse2."','".$dateString."','".$profil."')";
   $sql1="insert into utilisateur (nom,prenom,adresse,email,telPortable,telFixe,motdepasse,dateinscription, profil) values('".$nom."','".$prenom."','".$adresse."','".$email."','".$telPortable."','".$telFixe."','".$motdepasse2."','".$dateString."', 'Admin')";
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
	 echo'<script type="text/javascript" src="alertInscription.js"></script>';
   echo'<script language="JavaScript">document.location="index.php"</script>';
   echo'</head>';
   echo'</html>';
}else{
/**********************/

echo'<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="prixdesignation.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css">
			<title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
	</head>
	<body onload="process()">
	<header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="index.php">Accueil</a></li><li><a href="#">Journal de caisse</a></li>'.
'<li><a href="#">Historique de caisse</a></li><li><a href="#">Gestion Stock</a></li><li><a href="#">Catalogue des Services/Produits</a></li>'.
'</ul></nav></header><section>';


echo'<article><h3>Formulaire Inscription</h3><div id="corp1"><table width="70%" align="center" border="0">
	<form class="formulaire2" name="formulaire2" method="post" action="inscription.php">'.
		'<tr><td>NOM</td><td><input type="text" class="inputbasic" id="nom" name="nom" size="40" value="" /></td></tr>'.
		'<tr><td>PRENOM</td><td><input type="text" class="inputbasic" id="prenom" name="prenom" size="40" value="" /></td></tr>'.
		'<tr><td>ADRESSE</td><td><input type="text" class="inputbasic" id="adresse" name="adresse" size="40" value="" /></td></tr>'.
		'<tr><td>EMAIL</td><td><input type="text" class="inputbasic" placeholder="adresse email" id="email" name="email" size="40" value="" /></td></tr>'.
		'<tr><td>Telephone portable(Orange Money)</td><td><input type="text" class="inputbasic" placeholder="Telephone Portable" id="telPortable" name="telPortable" size="40" value="" /></td></tr>'.
		'<tr><td>Telephone fixe</td><td><input type="text" class="inputbasic" placeholder="Telephone fixe" id="telFixe" name="telFixe" size="40" value="" /></td></tr>'.
		'<tr><td>MOT DE PASSE</td><td><input type="password" placeholder="Mot de passe" class="inputbasic" id="motdepasse" name="motdepasse" size="40" value="" /></td></tr>'.
		'<tr><td>PROFIL</td><td><input type="text" class="inputbasic" id="profil" name="profil" size="40" value="Admin" disabled="true"/></td></tr>'.
		'<tr><td colspan="2" align="center">
			<input type="submit" class="boutonbasic" name="inserer" value="INSCRIPTION  >>">
			<input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>'.
		'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table></div><br /></article>';
echo'</section>
	</body>
	</html>';
}
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="index.php"</script>';
echo'</head>';
echo'</html>';
}

?>
