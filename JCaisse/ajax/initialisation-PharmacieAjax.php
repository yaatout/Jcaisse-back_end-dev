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

  $sql1='SELECT * from  `'.$nomtableStock.'` where designation="'.$design["designation"].'"';
  $res1=mysql_query($sql1);

  $rows = array();			
  $rows[] = strtoupper($design['designation']);
  $rows[] = strtoupper($design['categorie']);		
  $rows[] = strtoupper($design['forme']);
  if(!mysql_num_rows($res1)){
    $rows[] = '<input type="number" name="quantiteAStocke-'.$design['idDesignation'].'" id="quantiteAStocke-'.$design['idDesignation'].'" min=1 value="" required=""/>';
    $rows[] = '<input type="number" name="prixSession-'.$design['idDesignation'].'" id="prixSession-'.$design['idDesignation'].'" value="'.$design["prixSession"].'"/>';
    $rows[] = '<input type="number" name="prixPublic-'.$design['idDesignation'].'" id="prixPublic-'.$design['idDesignation'].'" value="'.$design["prixPublic"].'" required="" />';
    $rows[] = '<input type="Date" name="dateExpiration-'.$design['idDesignation'].'" id="dateExpiration-'.$design['idDesignation'].'" value=""/>';
    if ($_SESSION['caisse']==1 || $_SESSION['proprietaire']==1){
      $rows[] = '<button type="button" onclick="init_Stock_P('.$design["idDesignation"].')" id="btn_AjtStock_P-'.$design['idDesignation'].'" class="btn btn-success">
        <i class="glyphicon glyphicon-plus"></i>AJOUTER
      </button>';
    }
    else{
      $rows[] = 'NON AUT0RISE';
    }
  }
  else{
    $stock=mysql_fetch_array($res1);
    $rows[] = '<input type="number" id="quantiteAStocke-'.$design['idDesignation'].'" value="'.$stock["quantiteStockinitial"].'" value="" disabled="true"/>';
    $rows[] = '<input type="number" id="prixSession-'.$design['idDesignation'].'" value="'.$design["prixSession"].'" disabled="true"/>';
    $rows[] = '<input type="number" id="prixPublic-'.$design['idDesignation'].'" value="'.$design["prixPublic"].'" disabled="true" />';
    $rows[] = '<input type="Date" id="dateExpiration-'.$design['idDesignation'].'" value="'.$stock["dateExpiration"].'" disabled="true"/>';
    if ($_SESSION['caisse']==1 || $_SESSION['proprietaire']==1){
      $rows[] = '<button type="button" id="btn_AjtStock_P-'.$design['idDesignation'].'" class="btn btn-success" disabled="true">
      <i class="glyphicon glyphicon-plus"></i>AJOUTER
     </button>';
    }
    else{
      $rows[] = 'NON AUT0RISE';
    }
  }
  $data[] = $rows;

}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);





?>
