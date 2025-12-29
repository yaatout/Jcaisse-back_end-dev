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
/**********************/
$idStock      =@$_GET["idStock"];

$idStock2     =@$_POST["idStock"];
$annule         =@$_POST["annuler"];

/**************************/

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
if($idStock){
$sql="select * from `".$nomtableStock."` where idStock=".$idStock;
$res=@mysql_query($sql) or die ("selection impossible");
$tab=mysql_fetch_array($res);

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

echo'<article><h3>Formulaire Ajout de Stock Produit</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="ajouterStock.php">'.
'<tr><td>DESIGNATION</td><td><input type="text" class="inputbasic" id="designation" name="designation" size="40" value="'.$tab["designation"].'" /></td></tr>'.
'<tr><td>QUANTITE A STOCKER</td><td><input type="text" class="inputbasic" id="stock" name="stock" size="40" value="'.$tab["quantiteStockinitial"].'"  /></td></tr>';

if ($tab["uniteStock"]=="article")
    echo '<tr><td>UNITE STOCK</td><td><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" ><OPTION selected>'.$tab["uniteStock"].'<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>';
else if ($tab["uniteStock"]=="paquet")
    echo '<tr><td>UNITE STOCK</td><td><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" ><OPTION selected>'.$tab["uniteStock"].'<OPTION>article<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>';
else if ($tab["uniteStock"]=="caisse")
    echo '<tr><td>UNITE STOCK</td><td><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" ><OPTION selected>'.$tab["uniteStock"].'<OPTION>article<OPTION>paquet<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>';
else if ($tab["uniteStock"]=="douzaine")
    echo '<tr><td>UNITE STOCK</td><td><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" ><OPTION selected>'.$tab["uniteStock"].'<OPTION>article<OPTION>paquet<OPTION>caisse<OPTION>tonne</SELECT></td></tr>';
else if ($tab["uniteStock"]=="tonne")
    echo '<tr><td>UNITE STOCK</td><td><SELECT size=1 class="inputbasic" id="uniteStock" name="uniteStock" ><OPTION selected>'.$tab["uniteStock"].'<OPTION>article<OPTION>paquet<OPTION>caisse<OPTION>douzaine</SELECT></td></tr>';

echo '<tr><td>NOMBRE ARTICLE POUR UNE UNITE STOCK</td><td><input type="text" class="inputbasic" id="nombreArticle" name="nombreArticle" size="40" value="'.$tab["nbreArticleUniteStock"].'" /></td></tr>'.
'<tr><td>DATE EXPIRATION</td><td><input type="date" class="inputbasic" id="dateExpiration" name="dateExpiration" value = "'.$tab["dateExpiration"].'" size="40" /></td></tr>'.

'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="envoyer" value="MODIFIER  >>"/><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" />'.
'<input type="hidden" name="idStock" value="'.$idStock.'"/><input type="hidden" name="modifier" value="1"/></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table></div><br /></article>';

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