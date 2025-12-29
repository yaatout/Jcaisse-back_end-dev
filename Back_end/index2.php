<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016;15-04-2018
*/

session_start();


require('connectionPDO.php');

/**********************/

$email              =@htmlentities($_POST["email"]);
$motdepasse         =@htmlentities($_POST["motdepasse"]);


if($email){
/*****************************/
    $motdepasse2=sha1($motdepasse);
   
	$sql= $bdd->prepare("select * from `aaa-utilisateur` where email=:e and motdepasse=:m ");
	$sql->execute(['e'=>$email, 'm'=>$motdepasse2]);
	if($tab=$sql->fetch(PDO::FETCH_ASSOC)){
          $_SESSION['iduserBack']   = $tab["idutilisateur"];
          $_SESSION['prenom']   = $tab["prenom"];
          $_SESSION['nomU']     = $tab["nom"];
          $_SESSION['adresseU'] = $tab["adresse"];
          $_SESSION['email']    = $tab["email"];
          $_SESSION['telPortable']= $tab["telPortable"];
          $_SESSION['telFixe']    = $tab["telFixe"];
          $_SESSION["profil"]   = $tab["profil"];
		  $_SESSION['back']   = $tab["back"];
		  $_SESSION['creationB'] =$tab["creationB"];
		  $_SESSION['activer'] =$tab["activer"];
		  $_SESSION['matricule'] =$tab["matricule"];
		  $_SESSION['password'] =$motdepasse2;
		  
         if(($_SESSION['back']==1)&&($_SESSION['activer']==1)){
			if(($_SESSION['profil']=="SuperAdmin")||($_SESSION['profil']=="Admin")){
				header("Location:admin-map.php");
			}else if($_SESSION['profil']=="Accompagnateur"){
				header("Location:user-map.php");
			}else if($_SESSION['profil']=="Assistant"){
				header("Location:accueil-editeur.php");
			}/*else if($_SESSION['profil']=="Editeur catalogue"){
				
				header("accueil-editeur.php");
			}*/
    	}
	}
    else {
		echo " email ou mot de passe incorrecte";
    	}
}
else{
	echo "";
}

?>
