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

$sql="SELECT *
FROM `".$nomtableLigne."` l
INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
WHERE l.classe = 5  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 ORDER BY p.idPagnet DESC ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$produits=array();
$i=1;
while($vente=mysql_fetch_array($res)){

  $rows = array();
  $rows[] = strtoupper($vente['designation']);
  $rows[] = number_format(($vente['totalp'] * $_SESSION['devise']), 0, ',', ' ');

  $data[] = $rows;
  $i=$i + 1;
 
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
