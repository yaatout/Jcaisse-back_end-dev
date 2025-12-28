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
$dateJour_J=explode('-', $dateJour);
$dateAnnee=$dateJour_J[2].'-'.$dateJour_J[1].'-'.$dateJour_J[0];

if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
  $sql="SELECT DISTINCT u.idutilisateur
  FROM `aaa-utilisateur` u
  INNER JOIN `aaa-acces` a ON a.idutilisateur = u.idutilisateur
  INNER JOIN `".$nomtablePagnet."` p ON p.idUser = u.idutilisateur
  LEFT JOIN `".$nomtableMutuellePagnet."` m ON m.idUser = u.idutilisateur
  LEFT JOIN `".$nomtableVersement."` v ON v.idUser = u.idutilisateur
  WHERE a.idBoutique ='".$_SESSION['idBoutique']."' && ( p.datepagej ='".$dateJour."' or m.datepagej ='".$dateJour."' or v.dateVersement ='".$dateJour."' or v.dateVersement ='".$dateAnnee."' )   ORDER BY u.idutilisateur DESC";
  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
}
else{
  $sql="SELECT DISTINCT u.idutilisateur
  FROM `aaa-utilisateur` u
  INNER JOIN `aaa-acces` a ON a.idutilisateur = u.idutilisateur
  INNER JOIN `".$nomtablePagnet."` p ON p.idUser = u.idutilisateur
  LEFT JOIN `".$nomtableVersement."` v ON v.idUser = u.idutilisateur
  WHERE a.idBoutique ='".$_SESSION['idBoutique']."' && ( p.datepagej ='".$dateJour."' or v.dateVersement ='".$dateJour."' or v.dateVersement ='".$dateAnnee."' )   ORDER BY u.idutilisateur DESC";
  $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
}
$data=array();
$i=1;
while($personnel=mysql_fetch_array($res)){

  $sqlN="SELECT * from `aaa-utilisateur` where idutilisateur='".$personnel["idutilisateur"]."'";
  $resN=mysql_query($sqlN);
  $N_personnel = mysql_fetch_array($resN);

  $sqlApp="SELECT DISTINCT p.idPagnet
    FROM `".$nomtablePagnet."` p
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
    WHERE l.classe=5 && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.idUser='".$personnel["idutilisateur"]."'  ORDER BY p.idPagnet DESC";
  $resApp = mysql_query($sqlApp) or die ("persoonel requête 2".mysql_error());
  $T_App0 = 0 ;
  $S_App0 = 0;
  while ($pagnet = mysql_fetch_array($resApp)) {
    $sqlS="SELECT SUM(apayerPagnet)
    FROM `".$nomtablePagnet."`
    where  idClient=0  && idUser='".$personnel["idutilisateur"]."' &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_App0 = mysql_fetch_array($resS);
    $T_App0 = $S_App0[0] + $T_App0;
  }

  $sqlRC="SELECT DISTINCT p.idPagnet
    FROM `".$nomtablePagnet."` p
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
    WHERE l.classe=7  && p.idUser='".$personnel["idutilisateur"]."' && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  ORDER BY p.idPagnet DESC";
  $resRC = mysql_query($sqlRC) or die ("persoonel requête 2".mysql_error());
  $T_Rcaisse0 = 0 ;
  $S_Rcaisse0 = 0;
  while ($pagnet = mysql_fetch_array($resRC)) {
    $sqlS="SELECT SUM(apayerPagnet)
    FROM `".$nomtablePagnet."`
    where idClient=0  && idUser='".$personnel["idutilisateur"]."' && verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_Rcaisse0 = mysql_fetch_array($resS);
    $T_Rcaisse0 = $S_Rcaisse0[0] + $T_Rcaisse0;
  }

  if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
    $T_ventes0 = 0 ;

    $sqlP="SELECT *
      FROM `".$nomtablePagnet."` p
      INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
      WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";
    $resP = mysql_query($sqlP) or die ("persoonel requête 2".mysql_error());
    $T_ventesP = 0 ;
    while ($pagnet = mysql_fetch_array($resP)) {
      $T_ventesP = $T_ventesP + $pagnet['prixtotal'];
    }

    $sqlM="SELECT *
      FROM `".$nomtableMutuellePagnet."` m
      INNER JOIN `".$nomtableLigne."` l ON l.idMutuellePagnet = m.idMutuellePagnet
      WHERE l.classe=0  && m.idUser='".$personnel["idutilisateur"]."'  && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."'  && m.type=0  ORDER BY m.idMutuellePagnet DESC";
    $resM = mysql_query($sqlM) or die ("persoonel requête 2".mysql_error());
    $T_ventesM = 0 ;
    while ($mutuelle = mysql_fetch_array($resM)) {
      $T_ventesM = $T_ventesM + $mutuelle['apayerPagnet'];
    }

    $T_ventes0 = $T_ventesP + $T_ventesM ;
  }
  else{
    $sqlV="SELECT *
      FROM `".$nomtablePagnet."` p
      INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
      WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";
    $resV = mysql_query($sqlV) or die ("persoonel requête 2".mysql_error());
    $T_ventes0 = 0 ;
    while ($pagnet = mysql_fetch_array($resV)) {
      $T_ventes0 = $T_ventes0 + + $pagnet['prixtotal'];
    }
  }

  $sqlR="SELECT DISTINCT p.idPagnet
    FROM `".$nomtablePagnet."` p
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
    WHERE l.classe=0  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=1  ORDER BY p.idPagnet DESC";
  $resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());
  $T_Rventes0 = 0 ;
  $S_Rventes0 = 0;
  while ($pagnet = mysql_fetch_array($resR)) {
    $sqlS="SELECT SUM(apayerPagnet)
    FROM `".$nomtablePagnet."`
    where idClient=0 && idUser='".$personnel["idutilisateur"]."' && verrouiller=1 && datepagej ='".$dateJour."' && type=1 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_Rventes0 = mysql_fetch_array($resS);
    $T_Rventes0 = $S_Rventes0[0] + $T_Rventes0;
  }

  $sqlS="SELECT *
    FROM `".$nomtablePagnet."` p
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
    WHERE l.classe=1  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";
  $resS = mysql_query($sqlS) or die ("persoonel requête 2".mysql_error());
  $T_services0 = 0 ;
  while ($pagnet = mysql_fetch_array($resS)) {
    $T_services0 = $T_services0 + $pagnet['prixtotal'];
  }

  $sqlR="SELECT DISTINCT p.idPagnet
    FROM `".$nomtablePagnet."` p
    INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
    WHERE (l.classe=0 || l.classe=1)  && p.idUser='".$personnel["idutilisateur"]."'  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";
  $resR = mysql_query($sqlR) or die ("persoonel requête 2".mysql_error());
  $T_remises0 = 0 ;
  $S_remises0 = 0;
  while ($pagnet = mysql_fetch_array($resR)) {
    $sqlS="SELECT SUM(remise)
    FROM `".$nomtablePagnet."`
    where idClient=0 && idUser='".$personnel["idutilisateur"]."' && verrouiller=1 && datepagej ='".$dateJour."' && type=0 && idPagnet='".$pagnet['idPagnet']."'  ORDER BY idPagnet DESC";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_remises0 = mysql_fetch_array($resS);
    $T_remises0 = $S_remises0[0] + $T_remises0;
  }

  $sqlD="SELECT DISTINCT p.idPagnet
  FROM `".$nomtablePagnet."` p
  INNER JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
  WHERE l.classe=2 && p.idClient=0 && p.idUser='".$personnel["idutilisateur"]."' && p.verrouiller=1 && p.datepagej ='".$dateJour."'  && p.type=0  ORDER BY p.idPagnet DESC";
$resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());
$T_depenses0 = 0;
$S_depenses0 = 0;
while ($pagnet = mysql_fetch_array($resD)) {
  $sqlS="SELECT SUM(apayerPagnet)
  FROM `".$nomtablePagnet."`
  where idClient=0 && idUser='".$personnel["idutilisateur"]."' &&  verrouiller=1 && datepagej ='".$dateJour."' && idPagnet='".$pagnet['idPagnet']."' && type=0  ORDER BY idPagnet DESC";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $S_depenses0 = mysql_fetch_array($resS);
  $T_depenses0 = $S_depenses0[0] + $T_depenses0;
}

  $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idClient!=0 && idUser='".$personnel["idutilisateur"]."' && dateVersement  ='".$dateJour."'  ORDER BY idVersement DESC";
  $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
  $T_versementClient = mysql_fetch_array($resP5) ;

  $sqlP5="SELECT SUM(montant) FROM `".$nomtableVersement."` where idFournisseur!=0 && idUser='".$personnel["idutilisateur"]."' && dateVersement  ='".$dateAnnee."'  ORDER BY idVersement DESC";
  $resP5 = mysql_query($sqlP5) or die ("persoonel requête 2".mysql_error());
  $T_versementFournisseur = mysql_fetch_array($resP5) ;

  $Total0=$T_App0 + $T_ventes0 - $T_Rventes0 + $T_versementClient[0] - $T_versementFournisseur[0] - $T_Rcaisse0 + $T_services0 - $T_remises0 - $T_depenses0;

  $rows = array();

  $rows[] = '<b>'.$N_personnel["prenom"].' &nbsp; '.strtoupper($N_personnel["nom"]).'</b>';
  $rows[] = '<b>'.number_format(($T_App0  * $_SESSION['devise']), 0, ',', ' ').'</b>';
  $rows[] = '<b>'.number_format(($T_Rcaisse0  * $_SESSION['devise']), 0, ',', ' ').' </b>';
  $rows[] = '<b>'.number_format((($T_ventes0 - $T_Rventes0)  * $_SESSION['devise']), 0, ',', ' ').'</b>';
  $rows[] = '<b>'.number_format(($T_services0  * $_SESSION['devise']), 0, ',', ' ').'</b>';
  $rows[] = '<b>'.number_format(($T_versementClient[0]  * $_SESSION['devise']), 0, ',', ' ').'</b>';
  $rows[] = '<b>'.number_format(($T_versementFournisseur[0]  * $_SESSION['devise']), 0, ',', ' ').'</b>';
  $rows[] = '<b>'.number_format(($T_depenses0  * $_SESSION['devise']), 0, ',', ' ').'</b>';
  $rows[] = '<span style="color:orange"><b>'.number_format(($T_remises0  * $_SESSION['devise']), 0, ',', ' ').'</b></span>';
  $rows[] = '<b>'.number_format(($Total0  * $_SESSION['devise']), 0, ',', ' ').' </b>';

  $data[] = $rows;
  $i=$i + 1;
 
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData" => $data ];

echo json_encode($results);

?>
