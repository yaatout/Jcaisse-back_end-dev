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

$sql="SELECT * FROM `".$nomtableInventaire."` i
LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = i.idDesignation 
WHERE d.classe=0 AND i.type=5  ORDER BY i.idInventaire DESC";
$res=mysql_query($sql);

$data=array();
$produits=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $designation=mysql_fetch_array($res1);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateInventaire']); 

  if(in_array($designation['idDesignation'], $produits)){
    // echo "Existe.";
  }
  else{

    $sqlS="SELECT SUM(quantiteStockCourant)
    FROM `".$nomtableStock."`
    where idDesignation ='".$stock['idDesignation']."'  ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);

    if($S_stock[0]==0){

      $sqlI="SELECT SUM(quantite)
      FROM `".$nomtableInventaire."`
      where idDesignation ='".$stock['idDesignation']."' and type=5  ";
      $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
      $I_stock = mysql_fetch_array($resI);

      $sqlJ="SELECT SUM(quantite)
      FROM `".$nomtableInventaire."`
      where idDesignation ='".$stock['idDesignation']."' and dateInventaire ='".$stock['dateInventaire']."' and type=5   ";
      $resJ=mysql_query($sqlJ) or die ("select stock impossible =>".mysql_error());
      $J_stock = mysql_fetch_array($resJ);

      $rows = array();
      if($date1==$date2){
        $rows[] = $i;
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['codeBarreDesignation']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['uniteStock']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$designation['nbreArticleUniteStock'].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$stock['dateInventaire'].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$J_stock[0].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$I_stock[0].'</span>';
        $rows[] = '<a href="stock.php?iDS='.$stock["idDesignation"].'"><span style="color:red;">Details</span></a>';
      }	
      else{
        $rows[] = $i;
        $rows[] = strtoupper($designation['designation']);
        $rows[] = strtoupper($designation['codeBarreDesignation']);
        $rows[] = strtoupper($designation['uniteStock']);
        $rows[] = $designation['nbreArticleUniteStock'];
        $rows[] = $stock['dateInventaire'];
        $rows[] = $J_stock[0] ;
        $rows[] = $I_stock[0] ;
        $rows[] = '<a href="stock.php?iDS='.$stock["idDesignation"].'"><span style="color:red;">Details</span></a>';
      }	

  
      $data[] = $rows;
      $i=$i + 1;

    }
    $produits[] = $designation['idDesignation'];
  }
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
