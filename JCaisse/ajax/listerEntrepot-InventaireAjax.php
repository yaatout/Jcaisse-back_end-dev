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

$idEntrepot=@$_GET['id'];

$sql="SELECT * FROM `".$nomtableEntrepotStock."` s
LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
WHERE s.idEntrepot='".$idEntrepot."' AND d.classe=0 ORDER BY s.idEntrepotStock DESC";
$res=mysql_query($sql);

$data=array();
$produits=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $designation=mysql_fetch_array($res1);

  $sqlS="SELECT SUM(quantiteStockCourant)
  FROM `".$nomtableEntrepotStock."`
  where idDesignation ='".$stock['idDesignation']."' AND  idEntrepot ='".$idEntrepot."' ";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $S_stock = mysql_fetch_array($resS);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateStockage']); 

  // $sqlI="SELECT SUM(quantiteStockinitial)
  // FROM `".$nomtableStock."`
  // where idDesignation ='".$stock['idDesignation']."'  ";
  // $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
  // $I_stock = mysql_fetch_array($resI);

  // $sqlE="SELECT SUM(quantiteStockinitial)
  // FROM `".$nomtableEntrepotStock."`
  // where idDesignation ='".$stock['idDesignation']."'  ";
  // $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
  // $E_stock = mysql_fetch_array($resE);

  if(in_array($designation['idDesignation'], $produits)){
    // echo "Existe.";
   }
   else{ 

  $rows = array();
  $rows[] = $i;
  $rows[] = strtoupper($designation['designation']);
  $rows[] = strtoupper($designation['categorie']);
  $rows[] = '<input type="number" id="pus-'.$stock["idDesignation"].'" data-id="'.$idEntrepot.'_'.$stock["idDesignation"].'" class="form-control pus" width="20%"  min=1 value="'.$designation["prixuniteStock"].'" />'; 
  $rows[] = '<input type="number" id="pu-'.$stock["idDesignation"].'" data-id="'.$idEntrepot.'_'.$stock["idDesignation"].'" class="form-control pu" width="20%"  min=1 value="'.$designation["prix"].'" />'; 
  $rows[] = '<input type="number" id="pa-'.$stock["idDesignation"].'" data-id="'.$idEntrepot.'_'.$stock["idDesignation"].'" class="form-control pa" width="20%"  min=1 value="'.$designation["prixachat"].'" />'; 
  if ($designation['nbreArticleUniteStock']!=0 || $designation['nbreArticleUniteStock']!=null) {
    if($S_stock[0]!=0 && $S_stock[0]!=null){
      $rows[] = '<span id="actuQte_'.$stock["idDesignation"].'">'.($S_stock[0] / $designation['nbreArticleUniteStock']).'</span>';
    }
    else{
      $rows[] = '<span id="actuQte_'.$stock["idDesignation"].'">'.$S_stock[0].'</span>' ;
    }
  }
  else{
    $rows[] = '<span id="actuQte_'.$stock["idDesignation"].'">'.$S_stock[0].'</span>' ;
  }
  $rows[] = '<input type="number" id="quantite-'.$stock["idDesignation"].'" data-id="'.$idEntrepot.'_'.$stock["idDesignation"].'" class="form-control quantitePhyInt" width="20%"  min=1 value="" />'; 

  $rows[] = '<button type="button" class="btn btn-success btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireDepot_ET_I('.$idEntrepot.','.$stock["idDesignation"].')"><i class="glyphicon glyphicon-plus">
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
