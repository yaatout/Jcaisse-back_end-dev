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
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;

/*
$sql="select * from `".$nomtableStock."` where quantiteStockCourant!=0 order by idStock DESC";
$res=mysql_query($sql);
*/

$dateJour=@$_GET['dateJour'];
$expDateJour = explode("-",$dateJour);
$dateJour2 = $expDateJour[2]."-".$expDateJour[1]."-".$expDateJour[0];

$sql="SELECT DISTINCT c.idClient
FROM `".$nomtableClient."` c
INNER JOIN `".$nomtableVersement."` v ON v.idClient = c.idClient
WHERE  (v.dateVersement ='".$dateJour."' || v.dateVersement ='".$dateJour2."')  ORDER BY c.idClient DESC";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$i=1;
while($client=mysql_fetch_array($res)){

  $sqlN="select * from `".$nomtableClient."` where idClient='".$client["idClient"]."'";
  $resN=mysql_query($sqlN);
  $N_client = mysql_fetch_array($resN);
  $S_versementMobile=0;
  
  if ($_SESSION['compte']==1) {

    $sqlS="SELECT SUM(montant)
    FROM `".$nomtableVersement."`
    where idClient='".$client["idClient"]."' &&  (dateVersement ='".$dateJour."' || dateVersement ='".$dateJour2."') && (idCompte=1 || idCompte=0)";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_versement = mysql_fetch_array($resS);
    
    $sqlSMobile="SELECT SUM(montant)
    FROM `".$nomtableVersement."`
    where idClient='".$client["idClient"]."' &&  (dateVersement ='".$dateJour."' || dateVersement ='".$dateJour2."') && idCompte<>0 && idCompte<>1 && idCompte<>2 && idCompte<>3";
    $resSMobile=mysql_query($sqlSMobile) or die ("select stock impossible =>".mysql_error());
    $S_versementMobile = mysql_fetch_array($resSMobile);

  }else{

    $sqlS="SELECT SUM(montant)
    FROM `".$nomtableVersement."`
    where idClient='".$client["idClient"]."' &&  (dateVersement ='".$dateJour."' || dateVersement ='".$dateJour2."')";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_versement = mysql_fetch_array($resS);

  }

  $rows = array();
  $rows[] = strtoupper($N_client["prenom"]).' &nbsp; '.strtoupper($N_client["nom"]) ;
  if ($_SESSION['devise']==0) {
    # code...
    $rows[] = number_format(($S_versement[0]), 0, ',', ' ');
    $rows[] = number_format(($S_versementMobile[0]), 0, ',', ' ');
  } else {
    # code...
    $rows[] = number_format(($S_versement[0] * $_SESSION['devise']), 0, ',', ' ');
    $rows[] = number_format(($S_versementMobile[0] * $_SESSION['devise']), 0, ',', ' ');
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
