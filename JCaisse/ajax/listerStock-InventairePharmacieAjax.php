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


$sql="SELECT * FROM `".$nomtableStock."` s
LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
WHERE d.classe=0 ORDER BY s.idStock DESC";
$res=mysql_query($sql);

$data=array();
$produits=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $designation=mysql_fetch_array($res1);

  $sqlS="SELECT SUM(quantiteStockCourant)
  FROM `".$nomtableStock."`
  where idDesignation ='".$stock['idDesignation']."'  ";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $S_stock = mysql_fetch_array($resS);

  if(in_array($designation['idDesignation'], $produits)){
    // echo "Existe.";
   }
   else{

  $rows = array();
  $rows[] = $i;
  $rows[] = strtoupper($designation['designation']);
  $rows[] = strtoupper($designation['categorie']);
  $rows[] = $S_stock[0] ;
  $rows[] = '<input type="number" id="quantite-'.$stock["idDesignation"].'" class="form-control" width="20%"  min=1 value="" />'; 	

  $rows[] = '<button type="button" class="btn btn-success btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireDepot_PH('.$stock["idDesignation"].')"><i class="glyphicon glyphicon-plus">
    </i>CORRIGER
  </button>&nbsp;';


  $data[] = $rows;
  $i=$i + 1;
  $produits[] = $designation['idDesignation'];
}
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
