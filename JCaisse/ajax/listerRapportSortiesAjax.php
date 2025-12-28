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
$dateString2=$jour.'-'.$mois.'-'.$annee ;

$debut=@$_GET['debut'];
$fin=@$_GET['fin'];

//$sql="SELECT * from `".$nomtableStock."` where idDesignation='".$idDesignation."' order by dateStockage DESC";
//$res=mysql_query($sql);

/*
$sql="SELECT DISTINCT d.idDesignation
FROM `".$nomtableDesignation."` d
INNER JOIN `".$nomtableLigne."` l ON l.idDesignation = d.idDesignation
INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
WHERE d.classe = 0  && p.idClient =1 || p.idClient =0   && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' GROUP BY d.idDesignation ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
*/

$sql="SELECT *
FROM  `".$nomtableLigne."` l 
INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
WHERE l.classe = 0  && p.idClient =1 || p.idClient =0   && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
$res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

$data=array();
$produits=array();
$i=1;
while($stock=mysql_fetch_array($res)){

  $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stock["idDesignation"]."' ";
  $resN=mysql_query($sqlN);
  $designation = mysql_fetch_array($resN);
  
  $sqlS="SELECT SUM(prixtotal)
  FROM `".$nomtableLigne."` l
  INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
  INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
  where p.idClient =1 || p.idClient =0  &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
  $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  $prixTotal = mysql_fetch_array($resS);

  if(in_array($designation['idDesignation'], $produits)){
    // echo "Existe.";
  }
  else{
    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      $sqlQ="SELECT SUM(quantite)
      FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      where p.idClient =1 || p.idClient =0  &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ";
      $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
      $qte = mysql_fetch_array($resQ);

      $rows = array();
      if($i==1){
        $rows[] = $i;
        $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
        $rows[] = '<span style="color:blue;">'.strtoupper($designation['forme']).'</span>';
        $rows[] = '<span style="color:blue;">'.strtoupper($designation['tableau']).'</span>';
        $rows[] = '<span style="color:blue;">'.$qte[0].'</span>';
        $rows[] = '<span style="color:blue;">'.$designation['prixPublic'].'</span>';
        $rows[] = '<span style="color:blue;">'.$prixTotal[0].'</span>';
      }
      else if($dateString2==$stock['datepagej']){
        $rows[] = $i;
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['forme']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['tableau']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$qte[0].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$designation['prixPublic'].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$prixTotal[0].'</span>';
      }
      else{
        $rows[] = $i;
        $rows[] = strtoupper($designation['designation']);
        $rows[] = strtoupper($designation['forme']);
        $rows[] = strtoupper($designation['tableau']);
        $rows[] = $qte[0];
        $rows[] = $designation['prixPublic'];
        $rows[] = $prixTotal[0];
      }
    }
    else{
      $sqlQA="SELECT SUM(quantite)
      FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      where p.idClient =1 || p.idClient =0  &&  p.verrouiller=1 && l.unitevente!='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ";
      $resQA=mysql_query($sqlQA) or die ("select stock impossible =>".mysql_error());
      $qte_A = mysql_fetch_array($resQA);
    
      $sqlQS="SELECT SUM(quantite)
      FROM `".$nomtableLigne."` l
      INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      where p.idClient =1 || p.idClient =0  &&  p.verrouiller=1 && l.unitevente='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
      $resQS=mysql_query($sqlQS) or die ("select stock impossible =>".mysql_error());
      $qte_S = mysql_fetch_array($resQS);

      $rows = array();
      if($i==1){
        $rows[] = $i;
        $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
        if ($_SESSION['categorie']=="Grossiste") {
          $rows[] = '<span style="color:blue;">'.$qte_S[0].'</span>';
        }
        else{
          $rows[] = '<span style="color:blue;">'.($qte_A[0] + ($qte_S[0] * $designation['nbreArticleUniteStock'])).'</span>';
        }
        $rows[] = '<span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span>';
        $rows[] = '<span style="color:blue;">'.$designation['nbreArticleUniteStock'].'</span>';
        if ($_SESSION['categorie']=="Grossiste") {
          $rows[] = '<span style="color:blue;">'.$designation['prixuniteStock'].'</span>';
        }
        else{
          $rows[] = '<span style="color:blue;">'.$designation['prix'].'</span>';
        }
        $rows[] = '<span style="color:blue;">'.$prixTotal[0].'</span>';
      }
      else if($dateString2==$stock['datepagej']){
        $rows[] = $i;
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
        if ($_SESSION['categorie']=="Grossiste") {
          $rows[] = '<span style="color:#ffcc00;">'.$qte_S[0].'</span>';
        }
        else{
          $rows[] = '<span style="color:#ffcc00;">'.($qte_A[0] + ($qte_S[0] * $designation['nbreArticleUniteStock'])).'</span>';
        }
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['uniteStock']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$designation['nbreArticleUniteStock'].'</span>';
        if ($_SESSION['categorie']=="Grossiste") {
          $rows[] = '<span style="color:#ffcc00;">'.$designation['prixuniteStock'].'</span>';
        }
        else{
          $rows[] = '<span style="color:#ffcc00;">'.$designation['prix'].'</span>';
        }
        $rows[] = '<span style="color:#ffcc00;">'.$prixTotal[0].'</span>';
      }
      else{
        $rows[] = $i;
        $rows[] = strtoupper($designation['designation']);
        if ($_SESSION['categorie']=="Grossiste") {
          $rows[] = $qte_S[0] ;
        }
        else{
          $rows[] = ($qte_A[0] + ($qte_S[0] * $designation['nbreArticleUniteStock'])) ;
        }
        $rows[] = strtoupper($designation['uniteStock']);
        $rows[] = $designation['nbreArticleUniteStock'];
        if ($_SESSION['categorie']=="Grossiste") {
          $rows[] = $designation['prixuniteStock'];
        }
        else{
          $rows[] = $designation['prix'];
        } 
        $rows[] = $prixTotal[0];
      }
    }

    $db = explode("-", $debut);
    $date_debut=$db[0].''.$db[1].''.$db[2];
    $df = explode("-", $fin);
    $date_fin=$df[0].''.$df[1].''.$df[2];
    $rows[] = '<button  type="button" onclick="rapport_Sorties('.$stock['idDesignation'].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
    <i class="glyphicon glyphicon-transfer"></i> Details
    </button>';


    $data[] = $rows;
    $i=$i + 1;
    $produits[] = $designation['idDesignation'];
  }
}


$results = ["sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data), 
          "aaData" => $data ];

echo json_encode($results);

?>
