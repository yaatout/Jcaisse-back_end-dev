<?php 
session_start();
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");

$nomtableJournal=$_SESSION['nomB']."-journal";
$nomtablePage=$_SESSION['nomB']."-pagej";
$nomtablePagnet=$_SESSION['nomB']."-pagnet";
$nomtableLigne=$_SESSION['nomB']."-lignepj";
$nomtableDesignation=$_SESSION['nomB']."-designation";
$nomtableStock=$_SESSION['nomB']."-stock";
$nomtableTotalStock=$_SESSION['nomB']."-totalstock";

$designation  ="Ardoise";	
$unitevente="paquet";

if (!$designation)
 echo "La désignation est un champ obligatoire.";
else{
	$sql="select * from `".$nomtableDesignation."` where designation='".$designation."'";
$res=mysql_query($sql)or die ("insertion impossible");
if(mysql_num_rows($res) && ($tab=mysql_fetch_array($res)))
		   if($unitevente=="article")
            echo $tab["prix"];
			 else if($unitevente==$tab["uniteStock"])
				 echo $tab["prixuniteStock"];
	else
		echo "Cette désignation est absente, il faut l'ajouter avec son prix.";
}
	


?>
