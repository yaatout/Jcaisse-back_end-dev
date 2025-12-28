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

$id=@$_GET['id'];

$sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($design=mysql_fetch_array($res)){

  $rows = array();			
  $rows[] = $design['designation'];	
  $rows[] = $design['uniteStock'];
  $rows[] = '<input type="number" class="form-control" id="quantiteAStocke-'.$design['idDesignation'].'" min=1 value="" required=""/>';
  $rows[] = '<input type="number" class="form-control" id="prixAchat-'.$design['idDesignation'].'" min=1 value="'.$design['prixachat'].'" required=""/>';
  $rows[] = '<input type="number" class="form-control" id="prixUniteStock-'.$design['idDesignation'].'" min=1 value="'.$design['prixuniteStock'].'" required=""/>';
  $rows[] = '<input type="number" class="form-control" id="prixUnitaire-'.$design['idDesignation'].'" min=1 value="'.$design['prix'].'" required=""/>';
  $rows[] = '<input type="text" class="form-control dateInputControl" size="10" maxlength="10" id="dateExpiration-'.$design['idDesignation'].'" value="" placeholder="01-01-2020"/>';
  $rows[] = '<td><select class="form-control depotChoix" id="depotChoix-'.$design['idDesignation'].'">
              </select>
            </td>';
  $rows[] = '<button type="button" onclick="ajt_Stock_Bl_ET('.$design["idDesignation"].','.$id.')" id="btn_AjtStock_P-'.$design['idDesignation'].'" class="btn btn-success btn-sm">
    <i class="glyphicon glyphicon-plus"></i>
   </button>';
  
  $data[] = $rows;
  $i=$i + 1;
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
