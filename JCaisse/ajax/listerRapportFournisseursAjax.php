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

$sql="SELECT *,CONCAT(dateBl,'',heureBl) AS dateHeure
FROM
(SELECT b.idFournisseur,b.idBl,b.dateBl,b.heureBl FROM `".$nomtableBl."` b where  b.idFournisseur!=0 AND b.dateBl BETWEEN '".$debut."' AND '".$fin."' 
UNION 
SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement FROM `".$nomtableVersement."` v where v.idFournisseur!=0 AND v.dateVersement BETWEEN '".$debut."' AND '".$fin."'
) AS a ORDER BY dateHeure DESC ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$fournisseurs=array();
$i=1;
while($bon=mysql_fetch_assoc($res)){

  $sqlN="select * from `".$nomtableFournisseur."` where idFournisseur='".$bon["idFournisseur"]."'";
  $resN=mysql_query($sqlN);
  $fournisseur = mysql_fetch_array($resN);

  $sqlB="SELECT SUM(montantBl) FROM `".$nomtableBl."` 
  WHERE idFournisseur='".$bon["idFournisseur"]."' &&  dateBl BETWEEN '".$debut."' AND '".$fin."'  ";
  $resB=mysql_query($sqlB) or die ("select stock impossible =>".mysql_error());
  $totalB = mysql_fetch_array($resB);

  $sqlV="SELECT SUM(montant) FROM `".$nomtableVersement."` 
  WHERE idFournisseur='".$bon["idFournisseur"]."' && dateVersement BETWEEN '".$debut."' AND '".$fin."'  ";
  $resV=mysql_query($sqlV) or die ("select stock impossible =>".mysql_error());
  $totalV = mysql_fetch_array($resV);

  $db = explode("-", $debut);
  $date_debut=$db[0].''.$db[1].''.$db[2];
  $df = explode("-", $fin);
  $date_fin=$df[0].''.$df[1].''.$df[2];

  if(in_array($fournisseur['idFournisseur'], $fournisseurs)){
    // echo "Existe.";
  }
  else{
    $rows = array();
    $rows[] = $i;
    $rows[] = strtoupper($fournisseur['nomFournisseur']);
    $rows[] = $totalB[0].' <button  type="button" onclick="rapport_FBons('.$bon["idFournisseur"].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
    <i class="glyphicon glyphicon-transfer"></i> Details
    </button>';
    $rows[] = $totalV[0].' <button  type="button" onclick="rapport_FVersements('.$bon["idFournisseur"].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
    <i class="glyphicon glyphicon-transfer"></i> Details
    </button>';

    $data[] = $rows;
    $i=$i + 1;
    $fournisseurs[] = $fournisseur['idFournisseur'];
  }
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
