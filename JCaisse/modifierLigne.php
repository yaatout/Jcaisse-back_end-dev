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

require('connection.php');

require('declarationVariables.php');

/**********************/
$numligne      =@$_GET["numligne"];

$numligne2     =@$_POST["numligne"];
$annule         =@$_POST["annuler"];

/**************************/


if(!$annule){
if($numligne){
$sql="select * from `".$nomtableLigne."` where numligne=".$numligne;
$res=@mysql_query($sql) or die ("selection impossible");
$tab=mysql_fetch_array($res);

require('entetehtml.php');

echo'<body onload="process()"><header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<nav class="deconnexion"><p align="right"><a href="deconnexion.php">Déconnexion</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></nav>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="accueil.php">Accueil</a></li><li><a href="insertionLigne.php">Journal de caisse</a></li>'.
'<li><a href="saisiehistoriquecaisse.php">Historique de caisse</a></li><li><a href="ajouterStock.php">Gestion Stock</a></li><li><a href="insertionProduit.php">Catalogue des Services/Produits</a></li>'.
'</ul></nav></header><section>';


echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="insertionLigne.php">';
if ($tab["typeligne"]=="Entree")
    echo '<tr><td>TYPE</td><td><SELECT id="type" name="type" size=1 class="inputbasic"><OPTION selected>'.$tab["typeligne"].'<OPTION>Sortie</SELECT></td></tr>';
else
    echo '<tr><td>TYPE</td><td><SELECT id="type" name="type" size=1 class="inputbasic"><OPTION selected>'.$tab["typeligne"].'<OPTION>Entree</SELECT></td></tr>';

echo '<tr><td>DESIGNATION</td><td><input type="text" class="inputbasic" id="designation" name="designation" size="40" value="'.$tab["designation"].'" /></td></tr>'.
'<tr><td>QUANTITE</td><td><input type="text" class="inputbasic" id="quantite" name="quantite" size="40" value="'.$tab["quantite"].'" /></td></tr>';

if ($tab["unitevente"]=="article")
    echo '<tr><td>UNITE VENTE</td><td><SELECT size=1 class="inputbasic" id="unitevente" name="unitevente" ><OPTION selected>'.$tab["unitevente"].'<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>';
else if ($tab["unitevente"]=="paquet")
    echo '<tr><td>UNITE VENTE</td><td><SELECT size=1 class="inputbasic" id="unitevente" name="unitevente" ><OPTION selected>'.$tab["unitevente"].'<OPTION>article<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>';
else if ($tab["unitevente"]=="caisse")
    echo '<tr><td>UNITE VENTE</td><td><SELECT size=1 class="inputbasic" id="unitevente" name="unitevente" ><OPTION selected>'.$tab["unitevente"].'<OPTION>article<OPTION>paquet<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>';
else if ($tab["unitevente"]=="douzaine")
    echo '<tr><td>UNITE VENTE</td><td><SELECT size=1 class="inputbasic" id="unitevente" name="unitevente" ><OPTION selected>'.$tab["unitevente"].'<OPTION>article<OPTION>paquet<OPTION>caisse<OPTION>tonne</SELECT></td></tr>';
else if ($tab["unitevente"]=="tonne")
    echo '<tr><td>UNITE VENTE</td><td><SELECT size=1 class="inputbasic" id="unitevente" name="unitevente" ><OPTION selected>'.$tab["unitevente"].'<OPTION>article<OPTION>paquet<OPTION>caisse<OPTION>douzaine</SELECT></td></tr>';

echo '<tr><td>PRIX UNITE VENTE</td><td><input type="text" class="inputbasic" id="prix" name="prix" size="40" value="'.$tab["prixunitevente"].'" /></td></tr>'.
'<tr><td>REMISE</td><td><input type="text" class="inputbasic" id="remise" name="remise" size="40" value="'.$tab["remise"].'" /></td></tr>'.
'<tr><td>PRIX TOTAL</td><td><input type="text" class="inputbasic" id="prixt" name="prixt" size="40" value="'.$tab["prixtotal"].'" /></td></tr>'.

'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="envoyer" value="MODIFIER  >>"/><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" />'.
'<input type="hidden" name="numligne" value="'.$numligne.'"/><input type="hidden" name="modifier" value="1"/></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table></div><br /></article>';
}
/****************************/
$somme1=0.0;
$somme2=0.0;
$sommeT=0.0;
$sql='SELECT * from  `'.$nomtableLigne.'` where datepage="'.$dateString.'" order by numligne desc';
$res=mysql_query($sql);
if(mysql_num_rows($res)){
echo'<article><h3>Liste des entrées et des sorties de la caisse</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE</th><th>UNITE VENTE</th><th>PRIX UNITE VENTE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th><th></th>';
while($tab=mysql_fetch_array($res)){
echo'<tr><td>'.$tab["designation"].'</td><td align="right">'.$tab["quantite"].'</td><td align="right">'.$tab["unitevente"].'</td><td align="right">'.$tab["prixunitevente"].'</td><td align="right">'.$tab["remise"].'</td><td align="right">'.$tab["prixtotal"].'</td><td>'.$tab["typeligne"].'</td><td><a href="modifierLigne.php?numligne='.$tab["numligne"].'"><img src="images/edit.png" align="middle" alt="modifier"/></a>&nbsp;&nbsp;<a href="supprimerLigne.php?numligne='.$tab["numligne"].'">'.
'<img src="images/drop.png" align="middle" alt="supprimer"/></a></td></tr>';
if ($tab["typeligne"]=="Entree")
    $somme1+=$tab["prixtotal"];
else
    $somme2+=$tab["prixtotal"];
}
$sommeT=$somme1-$somme2;
echo'<tr bgcolor="#c0c0c0"><td colspan="2" align="right"><b>TOTAL ENTREE='.$somme1.'</b></td><td colspan="3" align="right"><b>TOTAL SORTIE='.$somme2.'</b></td><td colspan="3" align="right"><b>TOTAL ='.$sommeT.'</b></td></tr>';

$sql="update `".$nomtablePage."` set tentreesp=".$somme1.",tsortiesp=".$somme2." where datepage='".$dateString."'";
$res=@mysql_query($sql)or die ("modification impossible");

}else{
echo'<article><h3>Liste des entrées et des sorties de la caisse</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>UNITE VENTE</th><th>PRIX UNITE VENTE</th><th>QUANTITE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th>';
echo'<tr><td colspan="8">Journal de caisse de la date du '.$dateString.' est pour le moment vide </td></tr>';
}
echo'</table><br /></article>';

echo'</section></body></html>';
}
else{
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
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}
?>
