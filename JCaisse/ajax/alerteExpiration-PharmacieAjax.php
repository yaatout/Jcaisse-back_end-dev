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

$data=array();

//Ajout de -1 jours
$date1=date('Y-m-d', strtotime('-1 days'));  
$sql="SELECT * from  `".$nomtableStock."` where dateExpiration !='' and dateExpiration !='0000-00-00' and dateExpiration <= '".$date1."'";
$res=mysql_query($sql);
if(mysql_num_rows($res)){
	while($tab=mysql_fetch_array($res)){
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$tab['idDesignation']."'";
    $res1=mysql_query($sql1);
		if($tab["quantiteStockCourant"]!=0){
      $rows = array();
      $rows[] = '<span style="color:#C3C3C3;">'.$tab["dateExpiration"].'</span>';
      $rows[] = '<span style="color:#C3C3C3;">'.strtoupper($tab['designation']).'</span>';
      $rows[] = '<span style="color:#C3C3C3;">'.$tab['quantiteStockCourant'].'</span>';
      $rows[] = '<span style="color:#C3C3C3;">EXPIRE</span>';
      $rows[] = '<button type="button" onclick="rtr_Stock_P('.$tab["idStock"].')" id="btn_RetirerStock_P-'.$tab['idStock'].'" class="btn btn-danger" >
        <i class="glyphicon glyphicon-transfer"></i> RETIRER
        </button>';

      $data[] = $rows;
    }
	}
}	

//Ajout de 10 jours
$date2= date('Y-m-d', strtotime('+1 month'));
$sql='SELECT * from  `'.$nomtableStock.'` where dateExpiration BETWEEN "'.$dateString.'" AND "'.$date2.'"';
$res=mysql_query($sql);
if(mysql_num_rows($res)){
	while($tab=mysql_fetch_array($res)){
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$tab['idDesignation']."'";
    $res1=mysql_query($sql1);
		if($tab["quantiteStockCourant"]!=0){
      $rows = array();
      $rows[] = '<span style="color:red;">'.$tab["dateExpiration"].'</span>';
      $rows[] = '<span style="color:red;">'.strtoupper($tab['designation']).'</span>';
      $rows[] = '<span style="color:#red;">'.$tab['quantiteStockCourant'].'</span>';
      $rows[] = '<span style="color:red;">CONSEILLER</span>';
      $rows[] = '<button type="button" onclick="rtr_Stock_P('.$tab["idStock"].')" id="btn_RetirerStock_P-'.$tab['idStock'].'" class="btn btn-danger" >
      <i class="glyphicon glyphicon-transfer"></i> RETIRER
      </button>';
      $data[] = $rows;
    }
	}
}

//Ajout de 1 mois
$date3= date('Y-m-d', strtotime('+6 month'));
$date2= date('Y-m-d', strtotime('+1 month'));
$sql='SELECT * from  `'.$nomtableStock.'` where dateExpiration BETWEEN "'.$date2.'" AND "'.$date3.'" order by dateExpiration DESC';
$res=mysql_query($sql);
if(mysql_num_rows($res)){
  while($tab=mysql_fetch_array($res)){
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$tab['idDesignation']."'";
    $res1=mysql_query($sql1);
    if($tab["quantiteStockCourant"]!=0){
      $rows = array();
      $rows[] = '<span style="color:#ffcc00;">'.$tab["dateExpiration"].'</span>';
      $rows[] = '<span style="color:#ffcc00;">'.strtoupper($tab['designation']).'</span>';
      $rows[] = '<span style="color:##ffcc00;">'.$tab['quantiteStockCourant'].'</span>';
      $rows[] = '<span style="color:#ffcc00;">CONSEILLER</span>';
      $rows[] = '<button type="button" onclick="rtr_Stock_P('.$tab["idStock"].')" id="btn_RetirerStock_P-'.$tab['idStock'].'" class="btn btn-warning" >
        <i class="glyphicon glyphicon-transfer"></i> RETIRER
        </button>';
      $data[] = $rows;
    }
  }
}
  
//Ajout de +1 mois
$date3= date('Y-m-d', strtotime('+6 month'));
$sql='SELECT * from  `'.$nomtableStock.'` where dateExpiration > "'.$date3.' " order by dateExpiration DESC';
$res=mysql_query($sql);
if(mysql_num_rows($res)){
  while($tab=mysql_fetch_array($res)){
    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$tab['idDesignation']."'";
    $res1=mysql_query($sql1);
    if($tab["quantiteStockCourant"]!=0){
      $rows = array();
      $rows[] = '<span style="color:green;">'.$tab["dateExpiration"].'</span>';
      $rows[] = '<span style="color:green;">'.strtoupper($tab['designation']).'</span>';
      $rows[] = '<span style="color:green;">'.$tab['quantiteStockCourant'].'</span>';
      $rows[] = '<span style="color:green;">NORMAL</span>';
      $rows[] = '<button type="button" onclick="rtr_Stock_P('.$tab["idStock"].')" id="btn_RetirerStock_P-'.$tab['idStock'].'" class="btn btn-danger" >
      <i class="glyphicon glyphicon-transfer"></i> RETIRER
      </button>';
      $data[] = $rows;
    }
  }
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);


?>
