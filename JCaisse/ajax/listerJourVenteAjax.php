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

if (($_SESSION['mutuelle']==1) && ($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
  if ($_SESSION['compte']==1) {
    # code...
  
    $sql="SELECT idDesignation FROM
    ( SELECT DISTINCT d.idDesignation
      FROM `".$nomtableDesignation."` d
      INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE d.classe = 0  && p.idClient=0  && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=0
          UNION ALL
      SELECT DISTINCT d.idDesignation
      FROM `".$nomtableDesignation."` d
      INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
      INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet
      WHERE d.classe = 0  && m.idClient=0  && m.verrouiller=1 && (m.idCompte=1 || m.idCompte=0) && m.datepagej ='".$dateJour."' && m.type=0
    ) AS a GROUP BY idDesignation ";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  } else {
    # code...
  
    $sql="SELECT idDesignation FROM
    ( SELECT DISTINCT d.idDesignation
      FROM `".$nomtableDesignation."` d
      INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE d.classe = 0  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0
          UNION ALL
      SELECT DISTINCT d.idDesignation
      FROM `".$nomtableDesignation."` d
      INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
      INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet
      WHERE d.classe = 0  && m.idClient=0  && m.verrouiller=1 && m.datepagej ='".$dateJour."' && m.type=0
    ) AS a GROUP BY idDesignation ";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  }
}
else {
  if ($_SESSION['compte']==1) {
    # code...
  
    $sql="SELECT DISTINCT d.idDesignation
    FROM `".$nomtableDesignation."` d
    INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    WHERE d.classe = 0  && p.idClient=0  && p.verrouiller=1 && (p.idCompte=1 || p.idCompte=0) && p.datepagej ='".$dateJour."' && p.type=0 GROUP BY d.idDesignation ";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  } else {
    # code...
  
    $sql="SELECT DISTINCT d.idDesignation
    FROM `".$nomtableDesignation."` d
    INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    WHERE d.classe = 0  && p.idClient=0  && p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 GROUP BY d.idDesignation ";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
  }
}

$data=array();
$produits=array();
$i=1;
while($vente=mysql_fetch_array($res)) {

  $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$vente["idDesignation"]."' ";
  $resN=mysql_query($sqlN);
  $N_vente = mysql_fetch_array($resN);
  $S_vente=0;
  $Q_vente=0;

  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) { 
    $sqlS="SELECT SUM(prixtotal)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    where p.idClient=0 &&  p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $P_vente = mysql_fetch_array($resS);
  
    $sqlM="SELECT *
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet
    where m.idClient=0 &&  m.verrouiller=1 && m.datepagej ='".$dateJour."' && m.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
    $resM=mysql_query($sqlM) or die ("select stock impossible =>".mysql_error());
    $M_vente=0;
    while ($Taux_vente=mysql_fetch_array($resM)){
      $M_vente=$M_vente + ($Taux_vente['prixtotal'] - (($Taux_vente['prixtotal'] * $Taux_vente['taux']) / 100));
    }
    $S_vente=$P_vente[0] + $M_vente;


    $sqlS="SELECT SUM(l.quantite)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    where p.idClient=0 &&  p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $QP_vente = mysql_fetch_array($resS);
    
    $sqlS="SELECT SUM(l.quantite)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtableMutuellePagnet."` m ON m.idMutuellePagnet = l.idMutuellePagnet
    where m.idClient=0 &&  m.verrouiller=1 && m.datepagej ='".$dateJour."' && m.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $QM_vente = mysql_fetch_array($resS);

    $Q_vente=$QP_vente[0]+$QM_vente[0];

  }
  else {
    $sqlS="SELECT SUM(prixtotal)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    where p.idClient=0 &&  p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $P_vente = mysql_fetch_array($resS);

    $S_vente=$P_vente[0];

    if($_SESSION['Pays']=='Canada'){ 
      $sqlP="SELECT SUM(prixtotalTvaP)
      FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      where p.idClient=0 &&  p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
      $resP=mysql_query($sqlP) or die ("select stock impossible =>".mysql_error());
      $tvaP_vente = mysql_fetch_array($resP);

      $sqlR="SELECT SUM(prixtotalTvaR)
      FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      where p.idClient=0 &&  p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
      $resR=mysql_query($sqlR) or die ("select stock impossible =>".mysql_error());
      $tvaR_vente = mysql_fetch_array($resR);

      $S_venteTva=$tvaP_vente[0] + $tvaR_vente[0];
      $S_venteTotal=$S_vente + $S_venteTva;
    }    

    $sqlS="SELECT SUM(l.quantite)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    where p.idClient=0 &&  p.verrouiller=1 && p.datepagej ='".$dateJour."' && p.type=0 && d.idDesignation='".$vente["idDesignation"]."' ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $QB_vente = mysql_fetch_array($resS);
    $Q_vente=$QB_vente[0];
  }

  $rows = array();
  $rows[] = strtoupper($N_vente['designation']);
  $rows[] = number_format($Q_vente, 0, ',', ' ');
  $rows[] = number_format(($S_vente * $_SESSION['devise']), 2, ',', ' ');
  
  if($_SESSION['Pays']=='Canada'){
    $rows[] = number_format(($S_venteTva * $_SESSION['devise']), 2, ',', ' ');
    $rows[] = number_format(($S_venteTotal * $_SESSION['devise']), 2, ',', ' ');
  }
  else{
    $rows[] = number_format((0 * $_SESSION['devise']), 0, ',', ' ');
    $rows[] = number_format(($S_vente * $_SESSION['devise']), 0, ',', ' ');
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
