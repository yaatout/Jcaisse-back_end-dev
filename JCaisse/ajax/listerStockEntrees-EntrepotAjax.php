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

$idDesignation=@$_GET['id'];

$sql="SELECT * FROM `".$nomtableStock."`
 where idDesignation='".$idDesignation."' and quantiteStockinitial>0 ORDER BY dateStockage DESC ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());


$sqls="SELECT sum(quantiteStockinitial) FROM `".$nomtableStock."`
 where idDesignation='".$idDesignation."' and quantiteStockinitial>0";
$ress = mysql_query($sqls) or die ("persoonel requête 2".mysql_error());
$s=mysql_fetch_array($ress);
$somme=$s[0];

$data=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$idDesignation."' ";
  $res1=mysql_query($sql1);
  $design=mysql_fetch_array($res1);

  $sql2="SELECT * FROM `". $nomtableEntrepotStock."` where idStock ='".$stock['idStock']."' ";
  $res2=mysql_query($sql2);
  $entrepot=mysql_fetch_array($res2);

  $sqlU="SELECT * from `aaa-utilisateur` where idutilisateur='".$stock['iduser']."' ";
  $resU=mysql_query($sqlU);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateStockage']); 

  $rows = array();
  if ($i==1) {
    $rows[] = $i;
    $rows[] = '<span style="color:blue;">'.$stock['dateStockage'].' // '.$somme.'</span>';	
    $rows[] = '<span style="color:blue;">'.$stock['quantiteStockinitial'].'</span>';
    $rows[] = '<span style="color:blue;">'.$stock['uniteStock'].'</span>';
    $rows[] = '<span style="color:blue;">'.$stock['prixuniteStock'].'</span>';	
    $rows[] = '<span style="color:blue;">'.$stock['dateExpiration'].'</span>';
    if(mysql_num_rows($resU)){
      $user=mysql_fetch_array($resU);
      $rows[] = '<span style="color:blue;">'.strtoupper($user["prenom"]).'</span>';
    }
    else{
      $rows[] = '<span style="color:blue;">NEANT</span>';
    }

  } else if($date1==$date2){
    $rows[] = $i;
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';	
    $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockinitial'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['uniteStock'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['prixuniteStock'].'</span>';	
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateExpiration'].'</span>';
    if(mysql_num_rows($resU)){
      $user=mysql_fetch_array($resU);
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($user["prenom"]).'</span>';
    }
    else{
      $rows[] = '<span style="color:#ffcc00;">NEANT</span>';
    }
  }
  else{
    $rows[] = $i;
    $rows[] = $stock['dateStockage'];	
    $rows[] = $stock['quantiteStockinitial'];
    $rows[] = $stock['uniteStock'];
    $rows[] = $stock['prixuniteStock'];	
    $rows[] = $stock['dateExpiration'];
    if(mysql_num_rows($resU)){
      $user=mysql_fetch_array($resU);
      $rows[] = strtoupper($user["prenom"]);
    }
    else{
      $rows[] = 'NEANT';
    }
  } 


    $sqlE="SELECT SUM(quantiteStockinitial)
    FROM `".$nomtableEntrepotStock."`
    where idStock ='".$stock['idStock']."' AND (idTransfert=0 OR idTransfert IS NULL)   ";
    $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
    $E_stock = mysql_fetch_array($resE);

    if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['gestionnaire']==1){
      if($stock['quantiteStockinitial']==$E_stock[0]){
        $rows[] = '<button type="button" disabled="true" class="btn btn-success btn_ajtStock"><i class="glyphicon glyphicon-plus">
          </i>AJOUTER
        </button>
        <a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;';
      }
      else{
        if($entrepot!=null){
          $rows[] = '<button type="button" class="btn btn-success btn_ajtStock" onclick="ajt_StockDepot_ET('.$stock["idStock"].')"><i class="glyphicon glyphicon-plus">
                </i>AJOUTER
              </button>&nbsp;
              <a hidden> <img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;';
        }
        else{
          $rows[] = '<button type="button" class="btn btn-success btn_ajtStock" onclick="ajt_StockDepot_ET('.$stock["idStock"].')"><i class="glyphicon glyphicon-plus">
                </i>AJOUTER
              </button>&nbsp;
              <a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Stock_ET('.$stock["idStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idStock"].'" /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Stock_ET('.$stock["idStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idStock"].'" /></a>&nbsp;';
        }
      }
    }
    else{
      $rows[] = ' ';
    }
  //$rows[] = 'Operation';

  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
