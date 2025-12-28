<?php 
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:07/04/2016
*/
session_start();
if($_SESSION['iduser']){

require('connection.php');

require('declarationVariables.php');

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
require('entetehtml.php');
?>

<body>
	
	<?php   require('header.php'); ?>
<div class="container">
<?php echo'<section>';

echo'<article><h3>Formulaire pour ajouter les entr�es/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="supprimerStock.php">'.
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
require('entetehtml.php');
echo'<body onload="process()"><header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<nav class="deconnexion"><p align="right"><a href="deconnexion.php">D�connexion</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></nav>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="accueil.php">Accueil</a></li><li><a href="insertionLigne.php">Journal de caisse</a></li>'.
'<li><a href="saisiehistoriquecaisse.php">Historique de caisse</a></li><li><a href="ajouterStock.php">Gestion Stock</a></li><li><a href="insertionProduit.php">Catalogue des Services/Produits</a></li>'.
'</ul></nav></header><section>';

echo'<article><h3>Formulaire pour ajouter les entr�es/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="supprimerStock.php">'.
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
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}
 ?>