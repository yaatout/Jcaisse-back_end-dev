<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji Mamadou Korka
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

$debut=@$_GET['debut'];
$fin=@$_GET['fin'];

$sql="SELECT *
FROM  `".$nomtablePagnet."` p
WHERE p.idClient=0 && p.remise!=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$idPagnetTab=array();
$i=1;
while($p=mysql_fetch_array($res)){

  $sql2="SELECT *
  FROM  `".$nomtableLigne."` l
  WHERE l.idPagnet = ".$p['idPagnet']." ORDER BY l.numligne DESC ";
  $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
  $l=mysql_fetch_array($res2);

  if (empty($l)) {
    // code...
  }else {
    $rows = array();
    if($i==1){
      $rows[] = $i;
      $rows[] = '<span style="color:blue;">'.$p['datepagej'].' '.$p['heurePagnet'].'</span>';
      $rows[] = '<span style="color:blue;">'.$p['totalp'].'</span>';
      $rows[] = '<span style="color:blue;">'.$p['remise'].'</span>';
      $rows[] = '<span style="color:blue;">'.$p['apayerPagnet'].'</span>';
      if($p['idClient']!=0){
        $sqlN="select * from `".$nomtableClient."` where idClient='".$p["idClient"]."'";
        $resN=mysql_query($sqlN);
        $client = mysql_fetch_array($resN);
        $rows[] = '<span style="color:blue;">'.strtoupper($client['prenom']).' '.strtoupper($client['nom']).'</span>';
      }
      else{
        $rows[] = '<span style="color:blue;">VENTE</span>';
      }
    }
    else if($dateString2==$p['datepagej']){
      $rows[] = $i;
      $rows[] = '<span style="color:#ffcc00;">'.$p['datepagej'].' '.$p['heurePagnet'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$p['totalp'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$p['remise'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.$p['apayerPagnet'].'</span>';
      if($p['idClient']!=0){
        $sqlN="select * from `".$nomtableClient."` where idClient='".$p["idClient"]."'";
        $resN=mysql_query($sqlN);
        $client = mysql_fetch_array($resN);
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($client['prenom']).' '.strtoupper($client['nom']).'</span>';
      }
      else{
        $rows[] = '<span style="color:#ffcc00;">VENTE</span>';
      }
    }
    else{
      $rows[] = $i;
      $rows[] = $p['datepagej'].' '.$p['heurePagnet'];
      $rows[] = $p['totalp'];
      $rows[] = $p['remise'];
      $rows[] = $p['apayerPagnet'];
      if($p['idClient']!=0){
        $sqlN="select * from `".$nomtableClient."` where idClient='".$p["idClient"]."'";
        $resN=mysql_query($sqlN);
        $client = mysql_fetch_array($resN);
        $rows[] = strtoupper($client['prenom']).' '.strtoupper($client['nom']);
      }
      else{
        $rows[] = 'VENTE';
      }
    }
  // }

    $db = explode("-", $debut);
    $date_debut=$db[0].''.$db[1].''.$db[2];
    $df = explode("-", $fin);
    $date_fin=$df[0].''.$df[1].''.$df[2];
    // $rows[] = '<button  type="button" onclick="rapport_panier('.$p['idPagnet'].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
    // <i class="glyphicon glyphicon-transfer"></i> Details
    // </button>';


    $data[] = $rows;
    $i=$i + 1;
    // $idPagnetTab[] = $p['idPagnet'];
  }
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
