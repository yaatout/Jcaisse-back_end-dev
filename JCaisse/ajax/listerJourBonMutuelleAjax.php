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
/*
if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
  $sql="SELECT idClient FROM
  ( SELECT DISTINCT c.idClient
    FROM `".$nomtableClient."` c
    INNER JOIN `".$nomtablePagnet."` p ON p.idClient = c.idClient
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
    WHERE l.classe = 0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && (p.type=0 || p.type=30) 
        UNION ALL

    SELECT DISTINCT c.idClient
    FROM `".$nomtableMutuelle."` i
    INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuelle = i.idMutuelle
    INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
    WHERE l.classe = 0 && m.verrouiller=1 && m.datepagej ='".$dateJour."' && (m.type=0 || m.type=30) 
  ) AS a GROUP BY idClient DESC";
  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
}
else{
  $sql="SELECT DISTINCT c.idClient
  FROM `".$nomtableClient."` c
  INNER JOIN `".$nomtablePagnet."` p ON p.idClient = c.idClient
  INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
  WHERE l.classe = 0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && (p.type=0 || p.type=30)  GROUP BY c.idClient DESC ";
  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
}
*/
$sql="SELECT DISTINCT i.idMutuelle
    FROM `".$nomtableMutuelle."` i
    INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuelle = i.idMutuelle
    INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
    WHERE l.classe = 0 && m.verrouiller=1 && m.datepagej ='".$dateJour."' && (m.type=0 || m.type=30)  ORDER BY i.idMutuelle DESC";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$i=1;
while($mutuelle=mysql_fetch_array($res)){

  $sqlN="select * from `".$nomtableMutuelle."` where idMutuelle='".$mutuelle["idMutuelle"]."'";
  $resN=mysql_query($sqlN);
  $N_mutuelle = mysql_fetch_array($resN);
  $S_bons=0;

  $sqlB="SELECT SUM(apayerMutuelle)
  FROM `".$nomtableMutuellePagnet."`
  where (type=0 || type=30)  && verrouiller=1 && datepagej ='".$dateJour."' && idMutuelle='".$mutuelle['idMutuelle']."' ";
  $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
  $bons = mysql_fetch_array($resB);

  $S_bons = $bons[0];

  $rows = array();
  $rows[] = strtoupper($N_mutuelle["nomMutuelle"]).' ('.$N_mutuelle["tauxMutuelle"].' %)' ;
  $rows[] = number_format(($S_bons * $_SESSION['devise']), 2, ',', ' ');

  $data[] = $rows;
  $i=$i + 1;
 
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
