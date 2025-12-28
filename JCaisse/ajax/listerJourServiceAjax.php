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

$sql="SELECT DISTINCT d.idDesignation
FROM `".$nomtableDesignation."` d
INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
WHERE d.classe = 1  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 GROUP BY d.idDesignation ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$produits=array();
$i=1;
while($vente=mysql_fetch_array($res)){

  $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$vente["idDesignation"]."' ";
  $resN=mysql_query($sqlN);
  $N_vente = mysql_fetch_array($resN);
  $sqlS="SELECT SUM(prixtotal)
  FROM `".$nomtableLigne."` l
  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
  where p.idClient=0 &&  p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $S_vente = mysql_fetch_array($resS);

  $rows = array();
  $rows[] = strtoupper($N_vente['designation']);
  $rows[] = number_format(($S_vente[0] * $_SESSION['devise']), 0, ',', ' ');

  $data[] = $rows;
  $i=$i + 1;
 
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
