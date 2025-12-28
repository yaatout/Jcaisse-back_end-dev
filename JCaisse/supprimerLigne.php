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

/*******************/
$numligne2        =@$_POST["numligne"];
$type             =@htmlentities($_POST["type"]);
$designation      =@htmlentities($_POST["designation"]);
$unitevente				=@$_POST["unitevente"];
$prix             =@$_POST["prix"];
$quantite         =@$_POST["quantite"];
$remise           =@$_POST["remise"];
$prixtotal        =@$_POST["prixt"];

$supprimer        =@$_POST["supprimer"];
$ajout            =@$_POST["ajout"];
$annule           =@$_POST["annule"];
/***************************/
$numligne         =@$_GET["numligne"];
/************************/
if(!$annule){
if($ajout){
if($designation){
$sql1="select * from `".$nomtableJournal."` where annee=".$annee." and mois=".$mois;
$res1=mysql_query($sql1);
if(mysql_num_rows($res1)){
	 $sql2="select * from `".$nomtablePage."` where datepage='".$dateString."'";
   $res2=mysql_query($sql2);
   if(mysql_num_rows($res2)){
	     $sql="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."','".$unitevente."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
       $res=@mysql_query($sql) or die ("insertion ligne journal impossible-0");
	 }
	 else{
	     $sql1="insert into `".$nomtablePage."` (datepage,tentreesp,tsortiesp) values('".$dateString."',0,0)";
       $res1=@mysql_query($sql1) or die ("insertion page journal impossible-1");
			 
			 	     $sql2="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."','".$unitevente."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
						 $res2=@mysql_query($sql2) or die ("insertion ligne journal impossible-1");
	 }
}
else{
   $sql1="insert into `".$nomtableJournal."` (mois,annee) values(".$mois.",".$annee.")";
   $res1=@mysql_query($sql1) or die ("insertion journal impossible-2");
	 
   $sql2="insert into `".$nomtablePage."` (datepage,tentreesp,tsortiesp) values('".$dateString."',0,0)";
   $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2");
	 
	 	     $sql3="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."','".$unitevente."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
				 $res3=@mysql_query($sql3) or die ("insertion ligne journal impossible-2");
}
}
}
else{
if(!$supprimer){
$sql="select * from `".$nomtableLigne."` where numligne=".$numligne;
$res=@mysql_query($sql) or die ("selection impossible");
$tab=mysql_fetch_array($res);
/********************************/
require('entetehtml.php');

echo'<body onload="process()"><header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<nav class="deconnexion"><p align="right"><a href="deconnexion.php">Déconnexion</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></nav>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="accueil.php">Accueil</a></li><li><a href="insertionLigne.php">Journal de caisse</a></li>'.
'<li><a href="saisiehistoriquecaisse.php">Historique de caisse</a></li><li><a href="ajouterStock.php">Gestion Stock</a></li><li><a href="insertionProduit.php">Catalogue des Services/Produits</a></li>'.
'</ul></nav></header><section>';

echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="supprimerLigne.php">'.

'<tr><td>TYPE</td><td><SELECT id="type" name="type" size=1 class="inputbasic" disabled=""><OPTION selected>'.$tab["typeligne"].'<OPTION>Sortie</SELECT></td></tr>'.
'<tr><td>DESIGNATION</td><td><input type="text" class="inputbasic" id="designation" name="designation" size="40" value="'.$tab["designation"].'" disabled="" /></td></tr>'.
'<tr><td>QUANTITE</td><td><input type="text" class="inputbasic" id="quantite" name="quantite" size="40" value="'.$tab["quantite"].'" disabled="" /></td></tr>'.
'<tr><td>UNITE VENTE</td><td><SELECT size=1 class="inputbasic" id="unitevente" name="unitevente" disabled=""><OPTION selected>'.$tab["unitevente"].'<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>'.
'<tr><td>PRIX UNITE VENTE</td><td><input type="text" class="inputbasic" id="prix" name="prix" size="40" value="'.$tab["prixunitevente"].'"  disabled="" /></td></tr>'.
'<tr><td>REMISE</td><td><input type="text" class="inputbasic" id="remise" name="remise" size="40" value="'.$tab["remise"].'" disabled="" /></td></tr>'.
'<tr><td>PRIX TOTAL</td><td><input type="text" class="inputbasic" id="prixt" name="prixt" size="40" value="'.$tab["prixtotal"].'" disabled=""/></td></tr>'.

'<tr><td colspan=2 align="center"><input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" /><input type="submit" class="boutonbasic" name="annule" value="<<  ANNULER" />'.
'<input type="hidden" name="numligne" value="'.$numligne.'"/><input type="hidden" name="supprimer" value="1" /></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"><span style="color:red">Confirmer la suppression de cette Ligne, en appuyant sur SUPPRIMER >>.</span></div></td></tr>';
echo'</form></table></div><br /></article>';
}
else{

$designation2="";
$quantite2=0;
$unitevente2="";

$sql="select * from `".$nomtableLigne."` where numligne=".$numligne2;
$res=@mysql_query($sql) or die ("selection impossible");
//echo $sql;
if($tab=mysql_fetch_array($res)){
$designation2=$tab["designation"];
$quantite2=$tab["quantite"];
$unitevente2=$tab["unitevente"];
}
/*echo $tab["designation"];//$designation2;
echo $tab["quantite"];//$quantite2;
echo $tab["unitevente"];//$unitevente2;
//echo $nbreArticleUniteStock;
//echo $nombreTotal;
//echo $numligne2;*/

//retour de la ligne dans le stock
$sql="select * from `".$nomtableStock."` where designation='".$designation2."'";
$res=mysql_query($sql);
$nombreTotal=0;
$nbreArticleUniteStock=0;
if(mysql_num_rows($res)){
   if($tab=mysql_fetch_array($res))
  	 if($unitevente=="article")
  	   $nbreArticleUniteStock=1;
  	 else
       $nbreArticleUniteStock=$tab["nbreArticleUniteStock"];
   $nombreTotal=$nbreArticleUniteStock*$quantite2;
}

$sql1="INSERT INTO `".$nomtableStock."` (`designation`, `quantiteStockinitial`, `uniteStock`, `nbreArticleUniteStock`, `totalArticleStock`, `dateStockage`, `quantiteStockCourant`) VALUES('".$designation2."',".$quantite2.",'".$unitevente2."',".$nbreArticleUniteStock.",".$nombreTotal.",'Retour',".$nombreTotal.")";
$res1=@mysql_query($sql1) or die ("insertion stock impossible");

//modification du stock général par désignation
$newquantiteEnStocke=0;
$sql="select * from `".$nomtableTotalStock."` where designation='".$designation2."'";
$res=mysql_query($sql);
if(mysql_num_rows($res)){
   if($tab=mysql_fetch_array($res)){
     $newquantiteEnStocke=$tab["quantiteEnStocke"]+$nombreTotal;
     $sql="update `".$nomtableTotalStock."` set quantiteEnStocke=".$newquantiteEnStocke."' where designation='".$designation2."'";
     $res=@mysql_query($sql)or die ("modification impossible");
   }
}

//suppresion d'une ligne

$sql="DELETE FROM `".$nomtableLigne."` WHERE numligne=".$numligne2;
$res=@mysql_query($sql) or die ("suppression impossible");

/********************************/
require('entetehtml.php');

echo'<body onload="process()"><header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<nav class="deconnexion"><p align="right"><a href="deconnexion.php">Déconnexion</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></nav>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="accueil.php">Accueil</a></li><li><a href="insertionLigne.php">Journal de caisse</a></li>'.
'<li><a href="saisiehistoriquecaisse.php">Historique de caisse</a></li><li><a href="ajouterStock.php">Gestion Stock</a></li><li><a href="insertionProduit.php">Catalogue des Services/Produits</a></li>'.
'</ul></nav></header><section>';

echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="insertionLigne.php">'.
'<tr><td>TYPE</td><td><SELECT id="type" name="type" size=1 class="inputbasic" ><OPTION selected>Entree<OPTION>Sortie</SELECT></td></tr>'.
'<tr><td>DESIGNATION</td><td><input type="text" class="inputbasic" id="designation" name="designation" size="40" value="" /></td></tr>'.
'<tr><td>QUANTITE</td><td><input type="text" class="inputbasic" id="quantite" name="quantite" size="40" value="0" /></td></tr>'.
'<tr><td>UNITE VENTE</td><td><SELECT size=1 class="inputbasic" id="unitevente" name="unitevente" ><OPTION selected>article<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>'.
'<tr><td>PRIX UNITE VENTE</td><td><input type="text" class="inputbasic" id="prix" name="prix" size="40" value="" /></td></tr>'.
'<tr><td>REMISE</td><td><input type="text" class="inputbasic" id="remise" name="remise" size="40" value="0" /></td></tr>'.
'<tr><td>PRIX TOTAL</td><td><input type="text" class="inputbasic" id="prixt" name="prixt" size="40" value="" /></td></tr>'.

'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="inserer" value="ENVOYER  >>"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table></div><br /></article>';

}
}
/***************************/
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






