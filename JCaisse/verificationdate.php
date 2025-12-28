<?php 
/*
Résumé:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:05/04/2016
*/
require('connection.php');

require('declarationVariables.php');

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>';
echo '<response>';
$designation  =@$_GET['desig'];	
	if (!$designation)
		 echo "La désignation est un champ obligatoire.";
	else{
  	$sql="select * from '".$nomtableDesignation."' where designation='".$designation."'";
		$res=mysql_query($sql)or die ("insertion impossible");
		if(mysql_num_rows($res))
  		echo "Attention : Cette désignation est dèjà présente dans la base de données.";
  	else
  		echo "Cette désignation est absente, vous pouvez l'ajouter avec son prix.";
	}
echo '</response>';
?>
