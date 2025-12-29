<?php
/*
R�sum? :
Commentaire :
Version : 2.1
see also :
Auteur : korka DIALLO
Date de cr?ation : 28-03-2020
Date derni?re modification :
*/
session_start();

if(!$_SESSION['iduserBack'])
	header('Location:index.php');

require('connection.php');

require('declarationVariables.php');
$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$annee.'-'.$mois.'-'.$jour;

 ?>
<?php require('entetehtml.php'); ?>
<body>
  <?php
  /**************** MENU HORIZONTAL *************/
   
  require('header.php');
  ?>
  ACCUEIL Editeur Catalogue
</body>
</html>
