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



$dateJour=@$_GET['dateJour'];


if ($_SESSION['compte']==1) {

  # code...



  $sql="SELECT DISTINCT d.idDesignation

  FROM `".$nomtableDesignation."` d

  INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

  WHERE d.classe = 0 && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=0 GROUP BY d.idDesignation ";

  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

} else {

  # code...



  $sql="SELECT DISTINCT d.idDesignation

  FROM `".$nomtableDesignation."` d

  INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation

  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

  WHERE d.classe = 0 && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 GROUP BY d.idDesignation ";

  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

}



$data=array();

$produits=array();

$i=1;

while($vente=mysql_fetch_array($res)){



  $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$vente["idDesignation"]."' ";

  $resN=mysql_query($sqlN);

  $N_vente = mysql_fetch_array($resN);

/*
  $sqlS="SELECT SUM(prixtotal)

  FROM `".$nomtableLigne."` l

  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

  where p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";

  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

  $S_vente = mysql_fetch_array($resS);

*/

  $sqlS="SELECT SUM(prixtotal)

  FROM `".$nomtableLigne."` l

  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

  where p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";

  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());

  $P_vente = mysql_fetch_array($resS);



  if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

    $sqlUS="SELECT SUM(quantite)

    FROM `".$nomtableLigne."` l

    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

    where  p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && l.unitevente='".$N_vente["uniteStock"]."' && d.idDesignation='".$vente["idDesignation"]."' ";

    $resUS=mysql_query($sqlUS) or die ("select stock impossible =>".mysql_error());

    $S_vente_US = mysql_fetch_array($resUS);



    $sqlDM="SELECT SUM(quantite)

    FROM `".$nomtableLigne."` l

    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

    where p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && l.unitevente='Demi Gros' && d.idDesignation='".$vente["idDesignation"]."' ";

    $resDM=mysql_query($sqlDM) or die ("select stock impossible =>".mysql_error());

    $S_vente_DM = mysql_fetch_array($resDM);



    $sqlUN="SELECT SUM(quantite)

    FROM `".$nomtableLigne."` l

    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

    where p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && l.unitevente!='".$N_vente["uniteStock"]."' && l.unitevente!='Demi Gros' && d.idDesignation='".$vente["idDesignation"]."' ";

    $resUN=mysql_query($sqlUN) or die ("select stock impossible =>".mysql_error());

    $S_vente_UN = mysql_fetch_array($resUN);



    $T_vente_UN = ($S_vente_US[0] * $N_vente['prixachat']) + ($S_vente_DM[0] * ($N_vente['prixachat']/2)) + ($S_vente_UN[0] * ($N_vente['prixachat']/$N_vente['nbreArticleUniteStock']));



  }

  else{

    $sqlUS="SELECT SUM(quantite)

    FROM `".$nomtableLigne."` l

    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

    where p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && l.unitevente='".$N_vente["uniteStock"]."' && d.idDesignation='".$vente["idDesignation"]."' ";

    $resUS=mysql_query($sqlUS) or die ("select stock impossible =>".mysql_error());

    $S_vente_US = mysql_fetch_array($resUS);

  

    $sqlUN="SELECT SUM(quantite)

    FROM `".$nomtableLigne."` l

    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

    where p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && l.unitevente!='".$N_vente["uniteStock"]."' && d.idDesignation='".$vente["idDesignation"]."' ";

    $resUN=mysql_query($sqlUN) or die ("select stock impossible =>".mysql_error());

    $S_vente_UN = mysql_fetch_array($resUN);

  

    $T_vente_UN = ($S_vente_US[0] * $N_vente['nbreArticleUniteStock'] * $N_vente['prixachat']) + ($S_vente_UN[0] * $N_vente['prixachat']);

  

  }



  

  $rows = array();

  $rows[] = strtoupper($N_vente['designation']);

  $rows[] = number_format(($P_vente[0] * $_SESSION['devise']), 0, ',', ' ');

  $rows[] = number_format(($T_vente_UN * $_SESSION['devise']), 0, ',', ' ');



  $data[] = $rows;

  $i=$i + 1;

 

}





$results = ["sEcho" => 1,

          "iTotalRecords" => count($data),

          "iTotalDisplayRecords" => count($data),

          "aaData" => $data ];



echo json_encode($results);



?>

