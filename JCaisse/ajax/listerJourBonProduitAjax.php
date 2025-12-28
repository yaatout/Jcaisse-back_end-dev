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

if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
  $sql="SELECT idClient FROM
  ( SELECT DISTINCT c.idClient
    FROM `".$nomtableClient."` c
    INNER JOIN `".$nomtablePagnet."` p ON p.idClient = c.idClient
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
    WHERE l.classe = 0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && (p.type=0 || p.type=30) 
        UNION ALL
    SELECT DISTINCT c.idClient
    FROM `".$nomtableClient."` c
    INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idClient = c.idClient
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
/*
$sql="SELECT DISTINCT c.idClient
FROM `".$nomtableClient."` c
INNER JOIN `".$nomtablePagnet."` p ON p.idClient = c.idClient
INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
WHERE l.classe=0 &&  p.datepagej ='".$dateJour."' && p.type=0  && p.verrouiller=1  ORDER BY c.idClient DESC";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
*/
$data=array();
$i=1;
while($client=mysql_fetch_array($res)){

  $sqlN="select * from `".$nomtableClient."` where idClient='".$client["idClient"]."'";
  $resN=mysql_query($sqlN);
  $N_client = mysql_fetch_array($resN);
  $S_bons=0;

  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
    $sqlP="SELECT DISTINCT p.idPagnet
      FROM `".$nomtablePagnet."` p
      INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
      WHERE l.classe!=6 && p.idClient='".$client["idClient"]."' && (p.type=0 || p.type=30)   && p.verrouiller=1 && p.datepagej ='".$dateJour."' ORDER BY p.idPagnet DESC";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $T_BonP = 0 ;
    $S_BonP = 0;
    while ($pagnet = mysql_fetch_array($resP)) {
      $sqlB="SELECT SUM(apayerPagnet)
      FROM `".$nomtablePagnet."`
      where idClient='".$client["idClient"]."' && (type=0 || type=30)  && verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' ORDER BY idPagnet DESC";
      $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
      $S_BonP = mysql_fetch_array($resB);
      $T_BonP = $S_BonP[0] + $T_BonP;
    }

    $sqlM="SELECT DISTINCT m.idMutuellePagnet
      FROM `".$nomtableMutuellePagnet."` m
      INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
      WHERE l.classe!=6 && m.idClient='".$client["idClient"]."' && (m.type=0 || m.type=30)   && m.verrouiller=1 && m.datepagej ='".$dateJour."' ORDER BY m.idMutuellePagnet DESC";
    $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());
    $T_BonM = 0 ;
    $S_BonM = 0;
    while ($mutuelle = mysql_fetch_array($resM)) {
      $sqlB="SELECT SUM(apayerPagnet)
      FROM `".$nomtableMutuellePagnet."`
      where idClient='".$client["idClient"]."' && (type=0 || type=30)  && verrouiller=1 && datepagej ='".$dateJour."' && idMutuellePagnet='".$mutuelle['idMutuellePagnet']."' ORDER BY idMutuellePagnet DESC";
      $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
      $S_BonM = mysql_fetch_array($resB);
      $T_BonM = $S_BonM[0] + $T_BonM;
    }

    $S_bons=$T_BonP + $T_BonM;
  }
  else{
    $sqlP="SELECT DISTINCT p.idPagnet
      FROM `".$nomtablePagnet."` p
      INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
      WHERE l.classe!=6 && p.idClient='".$client["idClient"]."' && (p.type=0 || p.type=30)   && p.verrouiller=1 && p.datepagej ='".$dateJour."' ORDER BY p.idPagnet DESC";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $T_BonP = 0 ;
    $S_BonP = 0;
    $Ttva_BonP = 0 ;
    while ($pagnet = mysql_fetch_array($resP)) {
      $sqlB="SELECT SUM(apayerPagnet)
      FROM `".$nomtablePagnet."`
      where idClient='".$client["idClient"]."' && (type=0 || type=30)  && verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' ORDER BY idPagnet DESC";
      $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
      $S_BonP = mysql_fetch_array($resB);
      $T_BonP = $S_BonP[0] + $T_BonP;

      if($_SESSION['Pays']=='Canada'){ 
        $sqlBP="SELECT SUM(apayerPagnetTvaP)
        FROM `".$nomtablePagnet."`
        where idClient='".$client["idClient"]."' && (type=0 || type=30)  && verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' ORDER BY idPagnet DESC";
        $resBP=mysql_query($sqlBP) or die ("select stock impossible =>".mysql_error());
        $StvaP_BonP = mysql_fetch_array($resBP);

        $sqlBR="SELECT SUM(apayerPagnetTvaR)
        FROM `".$nomtablePagnet."`
        where idClient='".$client["idClient"]."' && (type=0 || type=30)  && verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' ORDER BY idPagnet DESC";
        $resBR=mysql_query($sqlBR) or die ("select stock impossible =>".mysql_error());
        $StvaR_BonR = mysql_fetch_array($resBR);

        $Ttva_BonP = $StvaP_BonP[0] + $StvaR_BonR[0] + $Ttva_BonP;
      }
    }
    $S_bons=$T_BonP;
    if($_SESSION['Pays']=='Canada'){ 
      $S_bons=$T_BonP + $Ttva_BonP;
    }
  }
  


  $rows = array();
  $rows[] = strtoupper($N_client["prenom"]).' &nbsp; '.strtoupper($N_client["nom"]) ;
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
