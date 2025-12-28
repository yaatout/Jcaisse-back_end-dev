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



$sql="select * from `".$nomtableDesignation."` where classe=0 order by idDesignation desc";
$res=mysql_query($sql);

$data=array();
$i=1;
while($design=mysql_fetch_array($res)){

  $sql1="SELECT * FROM `". $nomtableStock."` where idDesignation='".$design['idDesignation']."' ORDER BY idStock DESC";
  $res1=mysql_query($sql1);
  $stock=mysql_fetch_array($res1);

  $rows = array();			
  $rows[] = strtoupper($design['designation']);	
  $rows[] = strtoupper($design['categorie']);
  $rows[] = '<input type="number" class="form-control" id="quantiteAStocke-'.$design['idDesignation'].'" min=1 value="" required=""/>';
  $rows[] = '<select class="form-control"  id="uniteStock-'.$design['idDesignation'].'">
              <option selected value= "'.$design["uniteStock"].'">'.$design["uniteStock"].'</option>
            </select>';
  $rows[] = '<button type="button" onclick="ajt_TransfertEntrepot('.$design["idDesignation"].')" id="btn_AjtTransfert-'.$design['idDesignation'].'" class="btn btn-success">
    <i class="glyphicon glyphicon-plus"></i>TRANSFERER
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
