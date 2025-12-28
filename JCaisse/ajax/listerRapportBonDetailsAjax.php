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

$idDesignation=@$_GET['id'];
$debut=@$_GET['debut'];
$fin=@$_GET['fin'];

$sql="SELECT *
FROM  `".$nomtableLigne."` l
INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
WHERE p.idClient!=0 && l.idDesignation='".$idDesignation."' && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$produits=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $designation=mysql_fetch_array($res1);

  $sqlQ="SELECT quantite
  FROM `".$nomtableLigne."` l
  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
  where p.idClient!=0 &&  l.idDesignation='".$idDesignation."' && l.numligne='".$stock['numligne']."' &&  p.verrouiller=1 && p.type=0 &&  CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
  $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
  $S_stock = mysql_fetch_array($resQ);

  $sqlS="SELECT prixtotal
  FROM `".$nomtableLigne."` l
  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
  where p.idClient!=0 && l.idDesignation='".$idDesignation."' && l.numligne='".$stock['numligne']."' &&  p.verrouiller=1 && p.type=0 &&  CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $prixTotal = mysql_fetch_array($resS);

  $date1 = strtotime($dateString);

  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
    $rows = array();
    if($i==1){
      $rows[] = $i;
      $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
      $rows[] = '<span style="color:blue;">'.strtoupper($designation['forme']).'</span>';
      $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['prixPublic'].'</span>';
      $rows[] = '<span style="color:blue;">'.$prixTotal[0].'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';
    }
    else if($dateString2==$stock['datepagej']){
      $rows[] = $i;
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['forme']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$S_stock[0].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['prixPublic'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$prixTotal[0].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';
    }
    else{
      $rows[] = $i;
      $rows[] = strtoupper($designation['designation']);
      $rows[] = strtoupper($designation['forme']);
      $rows[] = $S_stock[0];
      $rows[] = $stock['prixPublic'];
      $rows[] = $prixTotal[0];
      $rows[] =$stock['datepagej'].' '.$stock['heurePagnet'];
    }
  }
  else{
    $rows = array();
    if($i==1){
      $rows[] = $i;
      $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
      $rows[] = '<span style="color:blue;">'.$S_stock[0].'</span>';
      $rows[] = '<span style="color:blue;">'.strtoupper($stock['unitevente']).'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['prixunitevente'].'</span>';
      $rows[] = '<span style="color:blue;">'.$prixTotal[0].'</span>';
      $rows[] = '<span style="color:blue;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';
    }
    else if($dateString2==$stock['datepagej']){
      $rows[] = $i;
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$S_stock[0].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['unitevente']).'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['prixunitevente'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$prixTotal[0].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';
    }
    else{
      $rows[] = $i;
      $rows[] = strtoupper($designation['designation']);
      $rows[] = $S_stock[0];
      $rows[] = strtoupper($stock['unitevente']);
      $rows[] = $stock['prixunitevente'];
      $rows[] = $prixTotal[0];
      $rows[] =$stock['datepagej'].' '.$stock['heurePagnet'];
    }
  }

  $data[] = $rows;
  $i=$i + 1;

}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
