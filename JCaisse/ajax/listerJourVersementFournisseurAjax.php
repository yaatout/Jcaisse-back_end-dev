<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de création : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
session_start();
if(!$_SESSION['iduser']){
  header('Location:../index.php');
  }

require('../connection.php');

require('../declarationVariables.php');

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
/*
$sql="select * from `".$nomtableStock."` where quantiteStockCourant!=0 order by idStock DESC";
$res=mysql_query($sql);
*/

$dateJour=@$_GET['dateJour'];
$dateJour_J=explode('-', $dateJour);
$dateAnnee=$dateJour_J[2].'-'.$dateJour_J[1].'-'.$dateJour_J[0];

$sql="SELECT DISTINCT c.idFournisseur
FROM `".$nomtableFournisseur."` c
INNER JOIN `".$nomtableVersement."` v ON v.idFournisseur = c.idFournisseur
WHERE  v.dateVersement ='".$dateAnnee."'  ORDER BY c.idFournisseur DESC";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$i=1;
while($fournisseur=mysql_fetch_array($res)){

  $sqlN="select * from `".$nomtableFournisseur."` where idFournisseur='".$fournisseur["idFournisseur"]."'";
  $resN=mysql_query($sqlN);
  $N_fournisseur = mysql_fetch_array($resN);
  
  $sqlS="SELECT SUM(montant)
  FROM `".$nomtableVersement."`
  where idFournisseur='".$fournisseur["idFournisseur"]."' &&  dateVersement ='".$dateAnnee."'";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $S_versement = mysql_fetch_array($resS);

  $rows = array();
  $rows[] = strtoupper($N_fournisseur["nomFournisseur"]) ;
  $rows[] = number_format(($S_versement[0] * $_SESSION['devise']), 0, ',', ' ');

  $data[] = $rows;
  $i=$i + 1;
 
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
