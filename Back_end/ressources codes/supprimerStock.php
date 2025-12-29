<?php 
/*
Résumé:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:07/04/2016
*/
session_start();
if($_SESSION['iduser']){

mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

$nomtableJournal=$_SESSION['nomB']."-journal";
$nomtablePage=$_SESSION['nomB']."-pagej";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableTotalStock=$_SESSION['nomB']."-totalstock";

/*******************/
$idStock2         =@$_POST["idStock"];
$designation      =@htmlentities($_POST["designation"]);
$stock            =@$_POST["stock"];
$uniteStock       =@$_POST["uniteStock"];
$nombreArticle    =@$_POST["nombreArticle"];
$dateExpiration   =@$_POST["dateExpiration"];

$supprimer    =@$_POST["supprimer"];
$ajout        =@$_POST["ajout"];
$annule       =@$_POST["annule"];
/***************************/
$idStock      =@$_GET["idStock"];
/************************/


//$date = new DateTime('25-02-2011');
$date = new DateTime();
//Récupération de l'année
$annee =$date->format('Y');
//Récupération du mois
$mois =$date->format('m');
//Récupération du jours
$jour =$date->format('d');


$dateString=$annee.'-'.$mois.'-'.$jour;


if(!$annule){
if($ajout){
if($designation){
	 $sql1="insert into `".$nomtableStock."` (designation,stock,uniteStock,nbreArticleUniteStock,dateExpiration) values(".$designation.",".$stock.",".$uniteStock.",".$nombreArticle.",".$dateExpiration.")";
   $res1=@mysql_query($sql1) or die ("insertion stock impossible");
	 }
}
else{
if(!$supprimer){
$sql="select * from `".$nomtableStock."` where idStock=".$idStock;
$res=@mysql_query($sql) or die ("selection impossible");
$tab=mysql_fetch_array($res);
/********************************/

?>
<!DOCTYPE html>
<html>
<head>
	
	<meta charset="utf-8">
     <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <style>.modal-header, h4, .close {background-color: #5cb85c; color:white !important; text-align: center; font-size: 30px;}.modal-footer {background-color: #f9f9f9;}</style>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
	<title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title>
</head>
<body>
	
	<?php   require('header.php'); ?>
<div class="container">
<?php echo'<section>';

echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="supprimerStock.php">'.
'<tr><td>DESIGNATION</td><td><input type="text" class="inputbasic" id="designation" name="designation" size="40" value="'.$tab["designation"].'" disabled="" /></td></tr>'.
'<tr><td>QUANTITE A STOCKER</td><td><input type="text" class="inputbasic" id="stock" name="stock" size="40" value="'.$tab["quantiteStockinitial"].'" disabled="" /></td></tr>'.
'<tr><td>UNITE STOCK</td><td><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" disabled=""><OPTION selected>'.$tab["uniteStock"].'<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>'.
'<tr><td>NOMBRE ARTICLE POUR UNE UNITE STOCK</td><td><input type="text" class="inputbasic" id="nombreArticle" name="nombreArticle" size="40" value="'.$tab["nbreArticleUniteStock"].'" disabled="" /></td></tr>'.
'<tr><td>DATE EXPIRATION</td><td><input type="date" class="inputbasic" id="dateExpiration" name="dateExpiration" size="40" value="'.$tab["dateExpiration"].'" disabled="" /></td></tr>'.

'<tr><td colspan=2 align="center"><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" />'.
'<input type="hidden" name="idStock" value="'.$idStock.'"/><input type="hidden" name="supprimer" value="1" /></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table></div><br /></article>';
}
else{
$sql="DELETE FROM `".$nomtableStock."` WHERE idStock=".$idStock2;
$res=@mysql_query($sql) or die ("suppression impossible");

header('Location:ajouterStock.php');
/********************************/
echo'<!DOCTYPE html><html><head><script type="text/javascript" src="prixdesignation.js"></script><link rel="stylesheet" type="text/css" href="style.css"><title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title></head><body onload="process()"><header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<nav class="deconnexion"><p align="right"><a href="deconnexion.php">Déconnexion</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></nav>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="accueil.php">Accueil</a></li><li><a href="insertionLigne.php">Journal de caisse</a></li>'.
'<li><a href="saisiehistoriquecaisse.php">Historique de caisse</a></li><li><a href="ajouterStock.php">Gestion Stock</a></li><li><a href="insertionProduit.php">Catalogue des Services/Produits</a></li>'.
'</ul></nav></header><section>';

echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="supprimerStock.php">'.
'<tr><td>DESIGNATION</td><td><input type="text" class="inputbasic" id="designation" name="designation" size="40" value="" /></td></tr>'.
'<tr><td>QUANTITE A STOCKER</td><td><input type="text" class="inputbasic" id="stock" name="stock" size="40" value=""  /></td></tr>'.
'<tr><td>UNITE STOCK</td><td><input type="text" class="inputbasic" id="uniteStock" name="uniteStock" size="40" value="" /></td></tr>'.
'<tr><td>NOMBRE ARTICLE POUR UNE UNITE STOCK</td><td><input type="text" class="inputbasic" id="nombreArticle" name="nombreArticle" size="40" value="" /></td></tr>'.
'<tr><td>DATE EXPIRATION</td><td><input type="date" class="inputbasic" id="dateExpiration" name="dateExpiration" size="40" value="" /></td></tr>'.

'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="inserer" value="STOCKER  >>"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table></div><br /></article>';
}
}
/***************************/

$sql="select * from `".$nomtableStock."` where idStock=".$idStock;
$res=@mysql_query($sql) or die ("selection impossible");
$tab=mysql_fetch_array($res);

$sql="SELECT * from  `".$nomtableStock."` where designation='".$tab["designation"]."'";
$res=mysql_query($sql);

if(mysql_num_rows($res)){
echo'<article><h3>Liste des stocks de '.$tab["designation"].'</h3><table class="tableau3" width="90%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE EN STOCK</th><th>UNITE STOCK</th><th>NOMBRE ARTICLE POUR UNE UNITE STOCK</th><th>DATE EXPIRATION</th><th></th>';
while($tab=mysql_fetch_array($res))
echo'<tr><td>'.$tab["designation"].'</td><td align="right">'.$tab["quantiteStockinitial"].'</td><td align="right">'.$tab["uniteStock"].'</td><td align="right">'.$tab["nbreArticleUniteStock"].'</td><td align="right">'.$tab["dateExpiration"].'</td><td><a href="modifierStock.php?idStock='.$tab["idStock"].'"><img src="images/edit.png" align="middle" alt="modifier"/></a>&nbsp;&nbsp;<a href="supprimerStock.php?idStock='.$tab["idStock"].'">'.
'<img src="images/drop.png" align="middle" alt="supprimer"/></a></td></tr>';
}else{
echo'<article><h3>Liste des stocks est vide</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE EN STOCK</th><th>DATE EXPIRATION</th><th>UNITE STOCK</th><th>NOMBRE ARTICLE</th><th></th>';
echo'<tr><td colspan="7">le Stock de la date du '.$dateString.' est pour le moment vide </td></tr>';
}

echo'</table><br /></article>';
echo'</section></div></body></html>';
}

else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="ajouterStock.php"</script>';
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