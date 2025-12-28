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

$dateJour=@$_GET['dateJour'];


// $typeCompte = "typeCompte='Wave' or typeCompte='Orange Money'";

$sql="select * from `".$nomtableCompte."` 
WHERE typeCompte='Wave' || typeCompte='Orange Money' ";
$res=mysql_query($sql);


$data=array();
$produits=array();
$i=1;
while($compte=mysql_fetch_array($res)){
  // var_dump($compte);

  if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
    $sqlV="SELECT *
    FROM `".$nomtableDesignation."` d
    INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.idCompte ='".$compte['idCompte']."'  && p.datepagej ='".$dateJour."' && p.type=0 ";
    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
    $T_ventes_V = 0;
    while ($pagnet = mysql_fetch_array($resV)) {
        $T_ventes_V=$T_ventes_V + $pagnet['prixtotal'];
    }


    $sqlM="SELECT *
    FROM `".$nomtableDesignation."` d
    INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
    INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet
    WHERE l.classe = 0 && m.idClient=0  && m.verrouiller=1 && m.idCompte ='".$compte['idCompte']."' && m.datepagej ='".$dateJour."' && m.type=0 ";
    $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());
    $T_ventes_M = 0;
    while ($mutuelle = mysql_fetch_array($resM)) {
        $T_ventes_M=$T_ventes_M + $mutuelle['apayerPagnet'];
    }

    $T_ventesMobile = $T_ventes_V + $T_ventes_M;
  }
  else {
    $sqlV="SELECT *
    FROM `".$nomtableDesignation."` d
    INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    WHERE l.classe = 0 && p.idClient=0  && p.verrouiller=1 && p.idCompte ='".$compte['idCompte']."'  && p.datepagej ='".$dateJour."' && p.type=0 ";
    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
    $T_ventes_V = 0;
    while ($pagnet = mysql_fetch_array($resV)) {
        $T_ventes_V=$T_ventes_V + $pagnet['prixtotal'];
    }

    $T_ventesMobile = $T_ventes_V ;
  }

  /******* voir si le compte a une vente en compte multiple ********/
  $mntCpt = 0;
  $sqlV = "SELECT *

  FROM  `" . $nomtablePagnet . "` p WHERE p.idClient=0  && p.verrouiller=1 && p.idCompte=1000 && p.datepagej ='" . $dateJour . "' && p.type=0 ";

  $resV = mysql_query($sqlV) or die("persoonel requête 2" . mysql_error());

  while ($pagnet = mysql_fetch_array($resV)) {

    $idsComptes = explode('_', $pagnet['idsCpteMultiple']);
    // var_dump($idsComptes);
    foreach ($idsComptes as $key) {
      if ($key == $compte['idCompte']) {
          
        $sqlM = "SELECT *
          FROM  `" . $nomtableComptemouvement . "` WHERE idCompte=" . $key . "  && mouvementLink=" . $pagnet['idPagnet'] . "";

        $resM = mysql_query($sqlM) or die("persoonel requête Mv" . mysql_error());
        $cptMv = mysql_fetch_array($resM);
        $mntCpt = $cptMv['montant'];

        $T_ventesMobile = $T_ventesMobile + $mntCpt;
      }
    }
  }

  $rows = array();
  $rows[] = strtoupper($compte['nomCompte']);
  $rows[] = number_format(($T_ventesMobile * $_SESSION['devise']), 2, ',', ' ');

  $data[] = $rows;
  $i=$i + 1;
 
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
