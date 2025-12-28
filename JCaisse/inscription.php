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
$motdepasse          =@htmlentities($_POST["motdepasse1"]);
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
/*if($motdepasse==$motdepasse2){
				$motdepasse2=sha1($motdepasse);
				$sql0="SELECT * FROM `aaa-utilisateur` where email='".$email."'";
				$res0=mysql_query($sql0);
				if(!mysql_num_rows($res0)){*/
				
	
if(isset($_POST['annuler'])){
	header('Location:../index.php');
	}
				
//if(isset($_POST['inserer'])){
	if($email){
		$motdepasse2=sha1($motdepasse);
		//echo $motdepasse; 
		//echo $motdepasse2;
		if($telFixe)
		
			$sql1="insert into `aaa-utilisateur` (nom,prenom,adresse,email,telPortable,telFixe,motdepasse,dateinscription, profil) values('".$nom."','".$prenom."','".mysql_real_escape_string($adresse)."','".$email."','".$telPortable."','".$telFixe."','".$motdepasse2."','".$dateString."', 'Admin')";
		else
			$sql1="insert into `aaa-utilisateur` (nom,prenom,adresse,email,telPortable,motdepasse,dateinscription, profil) values('".$nom."','".$prenom."','".mysql_real_escape_string($adresse)."','".$email."','".$telPortable."','".$motdepasse2."','".$dateString."', 'Admin')";
				   
		 //echo $sql1;
		 $res1=@mysql_query($sql1) or die ("insertion utilisateur impossible-2");
		 $sql2="SELECT * FROM `aaa-utilisateur` ORDER BY idutilisateur DESC LIMIT 0,1";
		 if ($res2=mysql_query($sql2)){
			$ligne = mysql_fetch_row($res2);
			$idUtilisateur = $ligne[0];
			
			
			$sql3="INSERT INTO `aaa-acces` (idutilisateur,proprietaire,gerant,caissier,activer,profil) VALUES (".$idUtilisateur.",1,1,1,1,'Admin')";
			$req3=@mysql_query($sql3) or die ("insertion dans acces impossible");
		 }
   echo'<!DOCTYPE html>';
   echo'<html>';
   echo'<head>';
   echo'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
   echo'<script type="text/javascript" src="alertInscription.js"></script>';
   echo'<script language="JavaScript">document.location="../index.php"</script>';
   echo'</head>';
   echo'</html>';
   }else{?>
   
 <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/bootstrap.css"> 
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
   <script type="text/javascript" src="validation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>INSCRIPTION</title>
</head>
<body>
<header>
<table width="98%" align="center" border="0"><tr><td>
<center><a href="../index.php"><img src="images/logojcaisse2.png"></a></br></br><nav><ul><li></li>
</ul></nav></center></td></tr></table></header><center>

		
				
<div class="row">
  <div class="col-gl-4 col-md-4 col-ms-6 col-sx-12 col-lg-offset-4 col-md-offset-4 col-ms-offset-3">
	 <div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Formulaire Inscription</h3></div>
			<div class="panel-body">
			
			  <table align="center" border="0">
				<form role="form" class="formulaire2" id="form" method="post" action="inscription.php">
				
                
				<div class="form-group">
					<tr><td><label for="prenom">PRENOM <font color="red">*</font></label></td></tr>
					<tr><td><input type="text" class="form-control" placeholder="Votre prenom ici" id="prenom" name="prenom" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpPrenom"></div></td></tr>
				</div>
                
                
                
				<div class="form-group">
					<tr><td><label for="nom"> NOM <font color="red">*</font></label></td></tr>
					<tr><td><input type="text" class="form-control" placeholder="Votre nom ici" id="nom" name="nom" size="40" value=""/></td></tr>
					<tr><td><div class="help-block" id="helpNom"></div></td></tr>
				</div>
				
				

				<div class="form-group">
					<tr><td><label for="adresse">ADRESSE </label></td></tr>
					<tr><td><input type="text" class="form-control" placeholder="Votre adresse" id="adresse" name="adresse" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpAdresse"></div></td></tr>
				</div>
				
							
				
				<div class="form-group">
					<tr><td><label for="portable">TEL. PORTABLE <font color="red">*</font></label></td></tr>
					<tr><td><input type="text" class="form-control" placeholder="Telephone Portable" id="telPortable" name="telPortable" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpPortable"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="fixe">TEL. FIXE </label></td></tr>
					<tr><td><input type="text" class="form-control" placeholder="Telephone fixe" id="telFixe" name="telFixe" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpFixe"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="email">EMAIL <font color="red">*</font></label></td></tr>
					<tr><td><input type="email" class="form-control" placeholder="Adresse email" id="email" name="email" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpEmail"></div></td></tr>
				</div>


				<div class="form-group">
					<tr><td><label for="motdepasse1">MOT DE PASSE <font color="red">*</font></label></td></tr>
					<tr><td><input type="password" placeholder="Mot de passe" class="form-control" id="motdepasse1" name="motdepasse1" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpMotdepasse1"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><label for="motdepasse2">CONFIRME LE MOT DE PASSE <font color="red">*</font></label></td></tr>
					<tr><td><input type="password" placeholder="Confirmation du Mot de passe" class="form-control" id="motdepasse2" name="motdepasse2" size="40" value="" /></td></tr>
					<tr><td><div class="help-block" id="helpMotdepasse2"></div></td></tr>
				</div>
				
				
				<div class="form-group">
					<tr><td><input class="form-check-input" type="checkbox" id="gridCheckProprietaire" name="proprietaire" required="">
						 J'accepte les <a href="CGU.html" target="_blank">Conditions d'utilisation</a> et la <a href="PDC.html" target="_blank">Politique de confidentialité</a> de Yaatout SARL.
						</td></tr>
				</div>
					      	
					
					
						
				
				
				<tr><td align="center">
					
                    <font color="red">Les champs qui ont (*) sont obligatoires</font><br/>
					<button type="submit" class="boutonbasic" id="inserer" name="inserer">S'INSCRIRE</button>
           
				</td></tr>
				
				</form></table></div></div></div></div></div></center>
		   
           


			
			</body>
			</html>  
   
   
<?php   
 }  

?>
