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

require('declarationVariables.php');

require('connection.php');

/**********************/
$numligne         =@$_POST["numligne"];
$type             =@htmlentities($_POST["type"]);
$designation      =@htmlentities($_POST["designation"]);
$unitevente				=@$_POST["unitevente"];
$prix             =@$_POST["prix"];
$quantite         =@$_POST["quantite"];
$remise           =@$_POST["remise"];
$prixtotal        =@$_POST["prixt"];

$numligne2       =@$_GET["numligne"];

$dateh             =@$_POST["dateh"];

$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/


if(!$annuler){
if(!$dateh){
require('entetehtml.php');
echo'<body onload="process()"><header>';

echo'<table width="98%" align="center" border="0"><tr><td>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav class="navbar navbar-inverse"><div class="container-fluid"><ul class="nav navbar-nav"><li><a href="accueil.php">ACCUEIL</a></li><li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">CAISSE<span class="caret"></span></a>'.
'<ul class="dropdown-menu"><li><a href="insertionLigne.php">JOURNAL</a></li><li><a href="saisiehistoriquecaisse.php">HISTORIQUE</a></li></ul></li>'.
'<li><a href="#">BON</a></li><li><a href="ajouterStock.php">STOCK</a></li><li><a href="insertionProduit.php">CATALOGUE</a></li>'.
'</ul><ul class="nav navbar-nav navbar-right"><li><a href="#"><span class="glyphicon glyphicon-user"></span> PROFIL</a></li>'.
'<li><a href="deconnexion.php"><span class="glyphicon glyphicon-log-out"></span> SORTIE</a></li></ul></div></nav></header><section>';

echo'<div class="container" align="center"><button type="button" class="btn btn-primary btn-lg" id="dateJournal">Date Journal de Caisse</button>';

echo'<div class="modal fade" id="dateJournalModal" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo'<h4><span class="glyphicon glyphicon-lock"></span> Recherche de Journal </h4>';
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';

echo'<table width="70%" align="center" border="1"><form class="formulaire2" name="formulaire2" method="post" action="saisiehistoriquecaisse.php">'.
'<tr><td>DATE HISTORIQUE : </td><td><input type="date" class="inputbasic" name="dateh" id="dateh" value = "" size="40" /></td></tr>'.
'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="inserer" value="CHERCHER  >>"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>';
echo'</form></table><br />'.
'</div></div></div></div></div>'.
'<script>$(document).ready(function(){$("#dateJournal").click(function(){$("#dateJournalModal").modal();});});</script>';
echo'</section></body></html>';
}
else{
require('entetehtml.php');
echo'<body onload="process()"><header>';

echo'<table width="98%" align="center" border="0"><tr><td>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav class="navbar navbar-inverse"><div class="container-fluid"><ul class="nav navbar-nav"><li><a href="accueil.php">ACCUEIL</a></li><li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">CAISSE<span class="caret"></span></a>'.
'<ul class="dropdown-menu"><li><a href="insertionLigne.php">JOURNAL</a></li><li><a href="saisiehistoriquecaisse.php">HISTORIQUE</a></li></ul></li>'.
'<li><a href="#">BON</a></li><li><a href="ajouterStock.php">STOCK</a></li><li><a href="insertionProduit.php">CATALOGUE</a></li>'.
'</ul><ul class="nav navbar-nav navbar-right"><li><a href="#"><span class="glyphicon glyphicon-user"></span> PROFIL</a></li>'.
'<li><a href="deconnexion.php"><span class="glyphicon glyphicon-log-out"></span> SORTIE</a></li></ul></div></nav></header><section>';

/*
echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="saisiehistoriquecaisse.php">'.
'<tr><td>DATE HISTORIQUE : </td><td><input class="inputbasic" type="date" name="dateh" id="dateh" value = "'.$dateh.'" size="40" /></td></tr>'.

'<tr><td>TYPE</td><td><SELECT id="type" name="type" size=1 class="inputbasic" ><OPTION selected>Entree<OPTION>Sortie</SELECT></td></tr>'.
'<tr><td>DESIGNATION</td><td><input type="text" class="inputbasic" id="designation" name="designation" size="40" value="" /></td></tr>'.
'<tr><td>QUANTITE</td><td><input type="text" class="inputbasic" id="quantite" name="quantite" size="40" value="0" /></td></tr>'.
'<tr><td>UNITE VENTE</td><td><SELECT size=1 class="inputbasic" id="unitevente" name="unitevente" ><OPTION selected>article<OPTION>paquet<OPTION>caisse<OPTION>douzaine<OPTION>tonne</SELECT></td></tr>'.
'<tr><td>PRIX UNITE VENTE</td><td><input type="text" class="inputbasic" id="prix" name="prix" size="40" value="" /></td></tr>'.
'<tr><td>REMISE</td><td><input type="text" class="inputbasic" id="remise" name="remise" size="40" value="0" /></td></tr>'.
'<tr><td>PRIX TOTAL</td><td><input type="text" class="inputbasic" id="prixt" name="prixt" size="40" value="" /></td></tr>'.

'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="inserer" value="ENVOYER  >>"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table></div><br /></article>';*/
/*****************************/
if($designation){
$sql1="select * from `".$nomtableJournal."` where annee=".$annee." and mois=".$mois;
$res1=mysql_query($sql1);
if(mysql_num_rows($res1)){
	 $sql2="select * from `".$nomtablePage."` where datepage='".$dateString."'";
   $res2=mysql_query($sql2);
   if(mysql_num_rows($res2)){
	     $sql="insert into `".$nomtableLigne."` (datepage,designation,unitevente,prixunitevente,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."','".$unitevente."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
			 //echo $sql;
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
/*$somme1=0.0;
$somme2=0.0;
$sommeT=0.0;
if($type)
	 $sql='SELECT * from  `'.$nomtableLigne.'` where datepage="'.$dateString.'" and typeligne=`'.$type.'` order by numligne desc';
else
   $sql='SELECT * from  `'.$nomtableLigne.'` where datepage="'.$dateString.'" order by numligne desc';
$res=mysql_query($sql);
if(mysql_num_rows($res)){
echo'<article><h3>Liste des entrées et des sorties de la caisse</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>UNITE VENTE</th><th>PRIX UNITE VENTE</th><th>QUANTITE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th>';
while($tab=mysql_fetch_array($res)){
echo'<tr><td>'.$tab["designation"].'</td><td align="right">'.$tab["unitevente"].'</td><td align="right">'.$tab["prixunitevente"].'</td><td align="right">'.$tab["quantite"].'</td><td align="right">'.$tab["remise"].'</td><td align="right">'.$tab["prixtotal"].'</td><td>'.$tab["typeligne"].'</td></tr>';
if ($tab["typeligne"]=="Entree")
    $somme1+=$tab["prixtotal"];
else
    $somme2+=$tab["prixtotal"];
}
$sommeT=$somme1-$somme2;
echo'<tr bgcolor="#c0c0c0"><td colspan="2" align="right"><b>TOTAL ENTREE='.$somme1.'</b></td><td colspan="2" align="right"><b>TOTAL SORTIE='.$somme2.'</b></td><td colspan="3" align="right"><b>TOTAL ='.$sommeT.'</b></td></tr>';

}else{
echo'<article><h3>Liste des entrées et des sorties de la caisse</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>PRIX UNITAIRE</th><th>QUANTITE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th>';
echo'<tr><td colspan="6">Journal de caisse de la date du '.$dateString.' est pour le moment vide </td></tr>';
}
echo'</table><br /></article>';

echo'</section></body></html>';
}*/

echo'<div class="container" align="center"><button type="button" class="btn btn-primary btn-lg" id="dateJournal">Date Journal de Caisse</button>';

echo'<div class="modal fade" id="dateJournalModal" role="dialog">';
echo'<div class="modal-dialog">';
echo'<div class="modal-content">';
echo'<div class="modal-header" style="padding:35px 50px;">';
echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
echo'<h4><span class="glyphicon glyphicon-lock"></span> Recherche de Journal </h4>';
echo'</div>';
echo'<div class="modal-body" style="padding:40px 50px;">';
echo'<table width="70%" align="center" border="1"><form class="formulaire2" name="formulaire2" method="post" action="saisiehistoriquecaisse.php">'.
'<tr><td>DATE HISTORIQUE : </td><td><input type="date" class="inputbasic" name="dateh" id="dateh" value = "" size="40" /></td></tr>'.
'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="inserer" value="CHERCHER  >>"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>';
echo'</form></table><br />'.
'</div></div></div></div></div>';

$somme1=0.0;
$somme2=0.0;
$sommeT=0.0;
$sql='SELECT * from  `'.$nomtableLigne.'` where datepage="'.$dateString.'" order by numligne desc';

$res=mysql_query($sql);
echo'<div class="container"><ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#PRODUIT">JOURNAL DE CAISSE DU '.$dateString.'</a></li>';
echo'</ul><div class="tab-content">';
if(mysql_num_rows($res)){
echo'<table class="tableau2" width="80%" align="left" border="1"><th>DESIGNATION</th><th>QUANTITE</th><th>UNITE VENTE</th><th>PRIX UNITE VENTE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th><th></th>';
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
echo'<table class="tableau2" width="80%" align="left" border="1"><th>DESIGNATION</th><th>UNITE VENTE</th><th>PRIX UNITE VENTE</th><th>QUANTITE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th>';
echo'<tr><td colspan="8">Journal de caisse de la date du '.$dateString.' est pour le moment vide </td></tr>';
}
echo'</table></div><br />'.
'<script>$(document).ready(function(){$("#dateJournal").click(function(){$("#dateJournalModal").modal();});});</script>';
echo'</section></body></html>';

}

}else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="saisiehistoriquecaisse.php"</script>';
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