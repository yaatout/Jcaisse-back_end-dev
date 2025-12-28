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
$dateString2=$jour.'-'.$mois.'-'.$annee ;

$idDesignation=@$_GET['id'];

//$sql="SELECT * from `".$nomtableStock."` where idDesignation='".$idDesignation."' order by dateStockage DESC";
//$res=mysql_query($sql);

$sql="SELECT *,CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) as dateJour 
FROM  `".$nomtableLigne."` l
INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
where p.verrouiller=1 && p.type=0 && l.idDesignation='".$idDesignation."'  ORDER BY p.idPagnet DESC ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$i=1;
while($stock=mysql_fetch_array($res)){
  
  $rows = array();
  $rows[] = $i;
  if($i==1){
    $rows[] = '<span style="color:blue;">'.$stock['dateJour'].'</span>';
    $rows[] = '<span style="color:blue;">'.$stock['heurePagnet'].'</span>';
    $rows[] = '<span style="color:blue;">'.$stock['quantite'].'</span>';
    $rows[] = '<span style="color:blue;">'.strtoupper($stock['forme']).'</span>';
    $rows[] = '<span style="color:blue;">'.$stock['prixPublic'].'</span>';
    $rows[] = '<span style="color:blue;">'.$stock['prixtotal'].'</span>';	
    $rows[] = '<span style="color:blue;">#'.$stock['idPagnet'].'</span>';
    if($stock['idClient']==0){
      $rows[] = 'Inconnu';
    }
    else{
      $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$stock["idClient"];
      $res3 = mysql_query($sql3) or die ("persoonel requête 1".mysql_error());
      $client = mysql_fetch_array($res3);
      $rows[] = strtoupper($client["nom"].' '.$client["prenom"]);
      $rows[] = 'Inconnu';
    }
  }
  else if($dateString2==$stock['datepagej']){
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateJour'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['heurePagnet'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['quantite'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['forme']).'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['prixPublic'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['prixtotal'].'</span>';	
    $rows[] = '<span style="color:#ffcc00;">#'.$stock['idPagnet'].'</span>';
    if($stock['idClient']==0){
      $rows[] = 'Inconnu';
    }
    else{
      $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$stock["idClient"];
      $res3 = mysql_query($sql3) or die ("persoonel requête 1".mysql_error());
      $client = mysql_fetch_array($res3);
      $rows[] = strtoupper($client["nom"].' '.$client["prenom"]);
    }	
  }
  else{
    $rows[] = $stock['dateJour'];
    $rows[] = $stock['heurePagnet'];
    $rows[] = $stock['quantite'];
    $rows[] = strtoupper($stock['forme']);
    $rows[] = $stock['prixPublic'];
    $rows[] = $stock['prixtotal'];	
    $rows[] = '#'.$stock['idPagnet'];
    if($stock['idClient']==0){
      $rows[] = 'Inconnu';
    }
    else{
      $sql3="SELECT * FROM `".$nomtableClient."` where idClient=".$stock["idClient"];
      $res3 = mysql_query($sql3) or die ("persoonel requête 1".mysql_error());
      $client = mysql_fetch_array($res3);
      $rows[] = strtoupper($client["nom"].' '.$client["prenom"]);
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
