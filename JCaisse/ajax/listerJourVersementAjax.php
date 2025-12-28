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

$sql="SELECT DISTINCT c.idClient
FROM `".$nomtableClient."` c
INNER JOIN `".$nomtableVersement."` v ON v.idClient = c.idClient
WHERE  v.dateVersement ='".$dateJour."'  ORDER BY c.idClient DESC";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$i=1;
while($client=mysql_fetch_array($res)){

  $sqlN="select * from `".$nomtableClient."` where idClient='".$client["idClient"]."'";
  $resN=mysql_query($sqlN);
  $N_client = mysql_fetch_array($resN);
  
  $sqlS="SELECT SUM(montant)
  FROM `".$nomtableVersement."`
  where idClient='".$client["idClient"]."' &&  dateVersement ='".$dateJour."'";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $S_versement = mysql_fetch_array($resS);

  $rows = array();
  $rows[] = strtoupper($N_client["prenom"]).' &nbsp; '.strtoupper($N_client["nom"]) ;
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
