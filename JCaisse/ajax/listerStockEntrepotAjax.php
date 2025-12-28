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

$idDesignation=@$_GET['id'];

$sql="SELECT * from `".$nomtableEntrepotStock."` 
where idDesignation='".$idDesignation."' order by idEntrepotStock desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  $res1=mysql_query($sql1);
  $design=mysql_fetch_array($res1);

  $sql2="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$stock['idEntrepot']."'";
  $res2=mysql_query($sql2);
  $entrepot=mysql_fetch_array($res2);

  $sql3="SELECT * FROM `". $nomtableStock."` where idStock='".$stock['idStock']."'";
  $res3=mysql_query($sql3);
  $s_expire=mysql_fetch_array($res3);

  $sql4="SELECT * FROM `". $nomtableEntrepotStock."` where idEntrepotStock='".$stock['idTransfert']."'";
  $res4=mysql_query($sql4);
  $transfert=mysql_fetch_array($res4);

  $sql5="SELECT * FROM `". $nomtableEntrepot."` where idEntrepot='".$transfert['idEntrepot']."'";
  $res5=mysql_query($sql5);
  $depot=mysql_fetch_array($res5);

  $date1 = strtotime($dateString); 
  $date2 = strtotime($stock['dateStockage']); 

  $rows = array();
  if($date1==$date2){
    $rows[] = $i;
    if($entrepot!=null){
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($entrepot['nomEntrepot']).'</span>';
    }else{
      $rows[] = '<span style="color:#ffcc00;">NEANT</span>';
    }
    if($transfert!=null){
      $rows[] = '<span style="color:#ffcc00;"> <= '.strtoupper($depot['nomEntrepot']).'</span>';
    }
    else{
      $rows[] = '<span style="color:#ffcc00;">NEANT</span>';
    }
    $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockinitial'].'</span>';
    if($design['nbreArticleUniteStock']!=0 || $design['nbreArticleUniteStock']!=null){
      if($stock['quantiteStockCourant']!=0){
        $rows[] = '<span style="color:#ffcc00;">'.($stock['quantiteStockCourant'] / $design['nbreArticleUniteStock']).'</span>';
      }
      else{
        $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockCourant'].'</span>';
      }
    }
    else{
      $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockCourant'].'</span>';
    }
    $rows[] = '<span style="color:#ffcc00;">'.strtoupper($design['uniteStock']).'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$design['prixuniteStock'].'</span>';
    $rows[] = '<span style="color:#ffcc00;">'.$stock['dateStockage'].'</span>';	
    $rows[] = '<span style="color:#ffcc00;">'.$s_expire['dateExpiration'].'</span>';
  }		
  else{
    $rows[] = $i;
    if($entrepot!=null){
      $rows[] = strtoupper($entrepot['nomEntrepot']);
    }else{
      $rows[] = 'NEANT';
    }
    if($transfert!=null){
      $rows[] = ' <= '.strtoupper($depot['nomEntrepot']);
    }
    else{
      $rows[] = 'NEANT';
    }
    $rows[] = $stock['quantiteStockinitial'];
    if($design['nbreArticleUniteStock']!=0 || $design['nbreArticleUniteStock']!=null){
      if($stock['quantiteStockCourant']!=0){
        $rows[] = ($stock['quantiteStockCourant'] / $design['nbreArticleUniteStock']);
      }
      else{
        $rows[] = $stock['quantiteStockCourant'];
      }
    }
    else{
      $rows[] = $stock['quantiteStockCourant'];
    }
    $rows[] = strtoupper($design['uniteStock']);
    $rows[] = $design['prixuniteStock'];
    $rows[] = $stock['dateStockage'];
    $rows[] = $s_expire['dateExpiration'];
  }	


  if($stock['totalArticleStock']==$stock['quantiteStockCourant']){
    if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['gestionnaire']==1){ 
      $rows[] = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idEntrepotStock"].'" /></a>&nbsp;
      <button type="button" class="btn btn-success btn_ajtStock" onclick="transfertDepot_ET('.$stock["idEntrepotStock"].')"><i class="glyphicon glyphicon-transfer">
      </i>
    </button>&nbsp
    <a onclick="imprimerCodeBarre_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
    }
    else{
      if($dateString==$stock['dateStockage'] && $_SESSION['caisse']==1){
        $rows[] = '<a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idEntrepotStock"].'" /></a>&nbsp;
        <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')"  data-toggle="modal" data-target="#imgsup'.$stock["idEntrepotStock"].'" /></a>&nbsp;
        <button type="button" class="btn btn-success btn_ajtStock" onclick="transfertDepot_ET('.$stock["idEntrepotStock"].')"><i class="glyphicon glyphicon-transfer">
      </i>
    </button>&nbsp
    <a onclick="imprimerCodeBarre_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')" data-toggle="modal" ><span style="color:green" class="glyphicon glyphicon-barcode"></span></a>&nbsp';
      }
      else{
        $rows[] = '';
      }
    }
     
  }
  else{
    if($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1 || $_SESSION['gestionnaire']==1){
      if($_SESSION['iduser']==1 || $_SESSION['iduser']==64){
        if($stock['quantiteStockCourant']!=0){
          $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idEntrepotStock"].'" /></a>&nbsp;
            <button type="button" class="btn btn-success btn_ajtStock" onclick="transfertDepot_ET('.$stock["idEntrepotStock"].')"><i class="glyphicon glyphicon-transfer">
            </i>
          </button>';
        }
        else{
          $rows[] = '<a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idEntrepotStock"].'" /></a>&nbsp;
          <button type="button" class="btn btn-success btn_ajtStock" disabled="true"><i class="glyphicon glyphicon-transfer">
            </i>
          </button>';
        }
      }
      else {
        if($stock['quantiteStockCourant']!=0){
          $rows[] = '<a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idEntrepotStock"].'" /></a>&nbsp;
            <button type="button" class="btn btn-success btn_ajtStock" onclick="transfertDepot_ET('.$stock["idEntrepotStock"].')"><i class="glyphicon glyphicon-transfer">
            </i>
          </button>';
        }
        else{
          $rows[] = '<a hidden><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_StockEntrepot('.$stock["idEntrepotStock"].','.$i.')" id="" data-toggle="modal" data-target="#imgmodifier'.$stock["idEntrepotStock"].'" /></a>&nbsp;
          <button type="button" class="btn btn-success btn_ajtStock" disabled="true"><i class="glyphicon glyphicon-transfer">
            </i>
          </button>';
        }
      }     
    }
    else{
      $rows[] = '';
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
