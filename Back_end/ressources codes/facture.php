<?php 
/*
Résumé:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:07/04/2016
*/

/*session_start();
if($_SESSION['iduser']){*/

mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

/**********************/
$numligne         =@$_POST["numligne"];
$type             =@htmlentities($_POST["type"]);
$designation      =@htmlentities($_POST["designation"]);
$prix             =@$_POST["prix"];
$quantite         =@$_POST["quantite"];
$remise           =@$_POST["remise"];
$prixtotal        =@$_POST["prixt"];
//$desig          =@$_POST["desig"];
$modifier         =@$_POST["modifier"];
$annuler          =@$_POST["annuler"];
/***************/

$numligne2       =@$_GET["numligne"];
/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//Récupération de l'année
$annee =$date->format('Y');
//Récupération du mois
$mois =$date->format('m');
//Récupération du jours
$jour =$date->format('d');


$dateString=$jour.'-'.$mois.'-'.$annee;

$somme1=0.0;
$somme2=0.0;
$sommeT=0.0;

$sql="select * from produit where designation='".$designation."'";
$res=mysql_query($sql);
if(mysql_num_rows($res)){
   if($tab=mysql_fetch_array($res))
     $prix=$tab["prix"];
     $prixtotal=$prix*$quantite-$remise;
}
if(!$annuler){
if(!$modifier){
if($designation){

$sql1="select * from journal where annee=".$annee." and mois=".$mois;
$res1=mysql_query($sql1);
if(mysql_num_rows($res1)){

	 $sql2="select * from pagej where datepage='".$dateString."'";
   $res2=mysql_query($sql2);
   if(mysql_num_rows($res2)){
	     $sql="insert into lignepj (datepage,designation,prixunitaire,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
       $res=@mysql_query($sql) or die ("insertion ligne journal impossible-0");
	 }
	 else{
	     $sql1="insert into pagej (datepage,tentreesp,tsortiesp) values('".$dateString."',0,0)";
       $res1=@mysql_query($sql1) or die ("insertion page journal impossible-1");
			 
			 $sql2="insert into lignepj (datepage,designation,prixunitaire,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
       $res2=@mysql_query($sql2) or die ("insertion ligne journal impossible-1");
	 }
}
else{
   $sql1="insert into journal (mois,annee) values(".$mois.",".$annee.")";
   $res1=@mysql_query($sql1) or die ("insertion journal impossible-2");
	 
   $sql2="insert into pagej (datepage,tentreesp,tsortiesp) values('".$dateString."',0,0)";
   $res2=@mysql_query($sql2) or die ("insertion page journal impossible-2");
	 
	 $sql3="insert into lignepj (datepage,designation,prixunitaire,quantite,remise,prixtotal,typeligne) values('".$dateString."','".$designation."',".$prix.",".$quantite.",".$remise.",".$prixtotal.",'".$type."')";
   $res3=@mysql_query($sql3) or die ("insertion ligne journal impossible-2");
}
}
}
else{
if($numligne){
$sql="update lignepj set typeligne='".$type."',designation='".$designation."',prixunitaire='".$prix."',quantite='".$quantite."',remise='".$remise."',prixtotal=".$prixtotal." where numligne=".$numligne;
$res=@mysql_query($sql)or die ("modification impossible");
}
}

echo'<!DOCTYPE html><html><head><script type="text/javascript" src="prixdesignation.js"></script><link rel="stylesheet" type="text/css" href="style.css"><title>JOURNAL DE CAISSE SALAM SERVICES SOLUTIONS</title></head><body onload="process()"><header>';
echo'<table width="98%" align="center" border="0"><tr><td>'.
'<nav class="deconnexion"><p align="right"><a href="deconnexion.php">Déconnexion</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></nav>'.
'<h1><img src="images/logogif.gif">BOUTIQUE & MULTI-SERVICES</h1><nav><ul><li><a href="insertionLigne.php">Journal de caisse</a></li>'.
'<li><a href="saisiehistoriquecaisse.php">Historique de caisse</a></li><li><a href="insertionProduit.php">Catalogue des Services/Produits</a></li>'.
'<li><a href="statistique_tendance.php">Statistiques & Tendances</a></li></ul></nav></header><section>';

echo'<article><h3>Formulaire pour ajouter les entrées/sorties de la caisse</h3><div id="corp1"><table width="70%" align="center" border="0"><form class="formulaire2" name="formulaire2" method="post" action="insertionLigne.php">'.
'<tr><td>TYPE</td><td><input type="text" class="inputbasic" id="type" name="type" size="40" value="Entree" /></td></tr>'.
'<tr><td>DESIGNATION</td><td><input type="text" class="inputbasic" id="designation" name="designation" size="40" value="" /></td></tr>'.
'<tr><td>PRIX UNITAIRE</td><td><input type="text" class="inputbasic" id="prix" name="prix" size="40" value=""   disabled=""/></td></tr>'.
'<tr><td>QUANTITE</td><td><input type="text" class="inputbasic" id="quantite" name="quantite" size="40" value="0" /></td></tr>'.
'<tr><td>REMISE</td><td><input type="text" class="inputbasic" id="remise" name="remise" size="40" value="0" /></td></tr>'.
'<tr><td>PRIX TOTAL</td><td><input type="text" class="inputbasic" id="prixt" name="prixt" size="40" value=""   disabled=""/></td></tr>'.

'<tr><td colspan="2" align="center"><input type="submit" class="boutonbasic" name="inserer" value="ENVOYER  >>"><input type="submit" class="boutonbasic" name="annuler" value="<<  ANNULER" /></td></tr>'.
'<tr><td colspan="2"><div id="apresEntre"></div></td></tr>';
echo'</form></table></div><br /></article>';
/*****************************/
$somme1=0.0;
$somme2=0.0;
$sommeT=0.0;
$sql="select * from lignepj where datepage='".$dateString."' order by numligne desc";
$res=mysql_query($sql);
if(mysql_num_rows($res)){
echo'<article><h3>Liste des entrées et des sorties de la caisse</h3><form><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>PRIX UNITAIRE</th><th>QUANTITE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th><th></th>';
while($tab=mysql_fetch_array($res)){
echo'<tr><td>'.$tab["designation"].'</td><td align="right">'.$tab["prixunitaire"].'</td><td align="right">'.$tab["quantite"].'</td><td align="right">'.$tab["remise"].'</td><td align="right">'.$tab["prixtotal"].'</td><td>'.$tab["typeligne"].'</td><td><input type="checkbox"/></td></tr>';
if ($tab["typeligne"]=="Entree")
    $somme1+=$tab["prixtotal"];
else
    $somme2+=$tab["prixtotal"];
}
$sommeT=$somme1-$somme2;
echo'<tr bgcolor="#c0c0c0"><td colspan="2" align="right"><b>TOTAL ENTREE='.$somme1.'</b></td><td colspan="2" align="right"><b>TOTAL SORTIE='.$somme2.'</b></td><td colspan="3" align="right"><b>TOTAL ='.$sommeT.'</b></td></tr>';

$sql="update pagej set tentreesp=".$somme1.",tsortiesp=".$somme2." where datepage='".$dateString."'";
$res=@mysql_query($sql)or die ("modification impossible");

}else{
echo'<article><h3>Liste des entrées et des sorties de la caisse</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>PRIX UNITAIRE</th><th>QUANTITE</th><th>REMISE</th><th>PRIX TOTAL</th><th>TYPE</th><th></th>';
echo'<tr><td colspan="7">Journal de caisse de la date du '.$dateString.' est pour le moment vide </td></tr>';
}
echo'</table></form><br /></article>';

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
/*}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="index.php"</script>';
echo'</head>';
echo'</html>';
}*/
?>