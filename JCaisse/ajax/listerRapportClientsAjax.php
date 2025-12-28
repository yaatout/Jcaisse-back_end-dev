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

$debut=@$_GET['debut'];
$fin=@$_GET['fin'];


$sql="SELECT *,CONCAT(CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)),'',heurePagnet) AS dateHeure
FROM
(SELECT p.idClient,p.idPagnet,p.datepagej,p.heurePagnet FROM `".$nomtablePagnet."` p where p.idClient!=0 AND p.verrouiller=1 AND CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'
UNION 
SELECT v.idClient,v.idVersement,v.dateVersement,v.heureVersement FROM `".$nomtableVersement."` v where v.idClient!=0 AND CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$debut."' AND '".$fin."'
) AS a ORDER BY dateHeure DESC";
$res=mysql_query($sql);

$data=array();
$clients=array();
$i=1;
while($bon=mysql_fetch_assoc($res)){

  $sqlN="select * from `".$nomtableClient."` where idClient='".$bon["idClient"]."'";
  $resN=mysql_query($sqlN);
  $client = mysql_fetch_array($resN);

  $sqlB="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` 
  WHERE idClient='".$bon["idClient"]."' &&  verrouiller=1 && type=0 && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
  $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
  $totalB = mysql_fetch_array($resB);

  $sqlR="SELECT SUM(apayerPagnet) FROM `".$nomtablePagnet."` 
  WHERE idClient='".$bon["idClient"]."' &&  verrouiller=1 && type=1 && CONCAT(CONCAT(SUBSTR(datepagej,7, 10),'',SUBSTR(datepagej,3, 4)),'',SUBSTR(datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
  $resR=mysql_query($sqlR) or die ("select stock impossible =>".mysql_error());
  $totalR = mysql_fetch_array($resR);

  $sqlV="SELECT SUM(montant) FROM `".$nomtableVersement."` 
  WHERE idClient='".$bon["idClient"]."' && CONCAT(CONCAT(SUBSTR(dateVersement,7, 10),'',SUBSTR(dateVersement,3, 4)),'',SUBSTR(dateVersement,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
  $resV=mysql_query($sqlV) or die ("select stock impossible =>".mysql_error());
  $totalV = mysql_fetch_array($resV);

  $db = explode("-", $debut);
  $date_debut=$db[0].''.$db[1].''.$db[2];
  $df = explode("-", $fin);
  $date_fin=$df[0].''.$df[1].''.$df[2];

  if(in_array($client['idClient'], $clients)){
    // echo "Existe.";
  }
  else{
    $rows = array();
    $rows[] = $i;
    $rows[] = strtoupper($client['prenom']).' '.strtoupper($client['nom']);
    $rows[] = ($totalB[0] - $totalR[0]).' <button  type="button" onclick="rapport_CBons('.$bon["idClient"].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
    <i class="glyphicon glyphicon-transfer"></i> Details
    </button>';
    $rows[] = $totalV[0].' <button  type="button" onclick="rapport_CVersements('.$bon["idClient"].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
    <i class="glyphicon glyphicon-transfer"></i> Details
    </button>';

    $data[] = $rows;
    $i=$i + 1;
    $clients[] = $client['idClient'];
  }
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
