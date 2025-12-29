<?php 
/*
Résumé:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:05/04/2016
*/
session_start();
if($_SESSION['iduser']){

mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");
/**********************/

$type             =@htmlentities($_POST["type"]);
$dateh             =@$_POST["dateh"];

$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/


if(!$annuler){
if(!$dateh){
echo'<!DOCTYPE html><html><head>'.
'<meta name="viewport" content="width=device-width, initial-scale=1">'.
'<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">'.
'<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>'.
'<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>'.
'<style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>'.	

'<script type="text/javascript" src="prixdesignation.js"></script><link rel="stylesheet" type="text/css" href="style.css"><title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title></head><body onload="process()"><header>';

echo'<table width="98%" align="center" border="0"><tr><td>'.
'<nav class="deconnexion"><p align="right"><a href="deconnexion.php">Déconnexion</a></p></nav>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav class="navbar navbar-inverse"><div class="container-fluid"><ul class="nav navbar-nav"><li><a href="accueil.php">ACCUEIL</a></li><li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">CAISSE<span class="caret"></span></a>'.
'<ul class="dropdown-menu"><li><a href="insertionLigne.php">JOURNAL</a></li><li><a href="saisiehistoriquecaisse.php">HISTORIQUE</a></li></ul></li>'.
'<li><a href="#">BON</a></li><li><a href="ajouterStock.php">STOCK</a></li><li><a href="insertionProduit.php">CATALOGUE</a></li>'.
'</ul><ul class="nav navbar-nav navbar-right"><li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>'.
'<li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li></ul></div></nav></header><section>';


echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="1"><form name="formulaire1" method="post" action="historiquecaisse.php">'.
'<tr><td>DATE HISTORIQUE : </td><td><input type="date" name="dateh" id="dateh" value = "'.$dateh.'" size="40" /></td></tr>'.
'<tr><td>TYPE LIGNES : </td><td><input type="text" id="type" name="type" size="40" value="'.$type.'" /></td></tr>'.
'<tr><td colspan="2" align="center"><input type="submit" name="inserer" value="CHERCHER  >>"><input type="submit" name="annuler" value="<<  ANNULER" /></td></tr>';
echo'</form></table></div><br /></article>';
echo'</section></body></html>';
}
else{
$date = new DateTime($dateh);
//Récupération de l'année
$annee =$date->format('Y');
//Récupération du mois
$mois =$date->format('m');
//Récupération du jours
$jour =$date->format('d');
$dateString=$jour.'-'.$mois.'-'.$annee;

echo'<!DOCTYPE html><html><head><script type="text/javascript" src="prixdesignation.js"></script><link rel="stylesheet" type="text/css" href="style.css"><title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title></head><body onload="process()"><header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<nav class="deconnexion"><p align="right"><a href="deconnexion.php">Déconnexion</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></nav>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="accueil.php">Accueil</a></li><li><a href="insertionLigne.php">Journal de caisse</a></li>'.
'<li><a href="saisiehistoriquecaisse.php">Historique de caisse</a></li><li><a href="ajouterStock.php">Gestion Stock</a></li><li><a href="insertionProduit.php">Catalogue des Services/Produits</a></li>'.
'</ul></nav></header><section>';

echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="1"><form name="formulaire1" method="post" action="historiquecaisse.php">'.
'<tr><td>DATE HISTORIQUE : </td><td><input type="date" name="dateh" id="dateh" value = "'.$dateh.'" size="40" /></td></tr>'.
'<tr><td>TYPE LIGNES : </td><td><input type="text" id="type" name="type" size="40" value="'.$type.'" /></td></tr>'.
'<tr><td colspan="2" align="center"><input type="submit" name="inserer" value="CHERCHER  >>"><input type="submit" name="annuler" value="<<  ANNULER" /></td></tr>';
echo'</form></table></div><br /></article>';
/*****************************/


$somme1=0.0;
$somme2=0.0;
$sommeT=0.0;
if($type)
	$sql="select * from lignepj where datepage='".$dateString."' and typeligne='".$type."' order by numligne desc";
else
  $sql="select * from lignepj where datepage='".$dateString."' order by numligne desc";
$res=mysql_query($sql);
if(mysql_num_rows($res)){
echo'<article><h3>Liste des entrées et des sorties de la caisse</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>PRIX UNITAIRE</th><th>QUANTITE</th><th>PRIX TOTAL</th><th>TYPE</th>';
while($tab=mysql_fetch_array($res)){
echo'<tr><td>'.$tab["designation"].'</td><td align="right">'.$tab["prixunitaire"].'</td><td align="right">'.$tab["quantite"].'</td><td align="right">'.$tab["prixtotal"].'</td><td>'.$tab["typeligne"].'</td></tr>';
if ($tab["typeligne"]=="Entree")
    $somme1+=$tab["prixtotal"];
else
    $somme2+=$tab["prixtotal"];
}
$sommeT=$somme1-$somme2;
echo'<tr bgcolor="#c0c0c0"><td colspan="2" align="right"><b>TOTAL ENTREE='.$somme1.'</b></td><td colspan="2" align="right"><b>TOTAL SORTIE='.$somme2.'</b></td><td colspan="1" align="right"><b>TOTAL ='.$sommeT.'</b></td></tr>';

}else{
echo'<article><h3>Liste des entrées et des sorties de la caisse</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>PRIX UNITAIRE</th><th>QUANTITE</th><th>PRIX TOTAL</th><th>TYPE</th>';
echo'<tr><td colspan="5">Journal de caisse de la date du '.$dateString.' est pour le moment vide </td></tr>';
}
echo'</table><br /></article>';

echo'</section></body></html>';
}
}else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="insertionLigne.php"</script>';
echo'</head>';
echo'</html>';
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