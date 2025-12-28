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
  // var_dump($debut);
  // echo $debut;
  // var_dump($fin);
  // echo $fin;

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeParStock = @$_POST["nbEntreeParStock"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeParStock!=0) ? $nbEntreeParStock : 10; //item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();

  

  $data=array();
  $i=1;

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  //get total number of records from database
  
  if ($query =="") {
    # code...
    
    $sql="SELECT * FROM `".$nomtableStock."` s
    LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
    WHERE d.classe=0 AND s.dateStockage BETWEEN '".$debut."' AND '".$fin."' ORDER BY s.idStock DESC";

    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    
  } else {
        # code... 
        if ($cb==1) {
          //get total number of records from database
          $sql="SELECT * FROM `".$nomtableStock."` s
          LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
          WHERE d.classe=0 AND s.dateStockage BETWEEN '".$debut."' AND '".$fin."' and (d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC";

          $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        }else {
          //get total number of records from database
          $sql="SELECT * FROM `".$nomtableStock."` s
          LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
          WHERE d.classe=0 AND s.dateStockage BETWEEN '".$debut."' AND '".$fin."' and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC";

          $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
        }
  }
  
  //$data=array();

  while($stock=mysql_fetch_array($res)){



    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  
    $res1=mysql_query($sql1);
  
    $designation=mysql_fetch_array($res1);
  
  
  
    $date1 = strtotime($dateString);
  
    $date2 = strtotime($stock['dateStockage']);
  
  
  
    // if(in_array($designation['idDesignation'], $produits)){
  
    //   // echo "Existe.";
  
    // }
  
    // else{
  
        if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
  
          /*
  
          $sqlS="SELECT SUM(quantiteStockinitial)
  
          FROM `".$nomtableStock."`
  
          where idDesignation ='".$stock['idDesignation']."' AND dateStockage BETWEEN '".$debut."' AND '".$fin."' ";
  
          $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  
          $S_stock = mysql_fetch_array($resS);
  
          */
  
  
  
          $rows = array();
  
          if($i==1){
  
            $rows[] = $i;
  
            $rows[] = '<span style="color:blue;">'.strtoupper($stock['dateStockage']).'</span>';
  
            $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
  
            $rows[] = '<span style="color:blue;">'.strtoupper($designation['forme']).'</span>';
  
            $rows[] = '<span style="color:blue;">'.strtoupper($designation['tableau']).'</span>';
  
            $rows[] = '<span style="color:blue;">'.$stock['quantiteStockinitial'].'</span>';
  
            $rows[] = '<span style="color:blue;">'.$designation['prixSession'].'</span>';
  
            $rows[] = '<span style="color:blue;">'.$designation['prixPublic'].'</span>';
  
          }
  
          else if($date1==$date2){
  
            $rows[] = $i;
  
            $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['dateStockage']).'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['forme']).'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['tableau']).'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockinitial'].'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.$designation['prixSession'].'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.$designation['prixPublic'].'</span>';
  
          }
  
          else{
  
            $rows[] = $i;
  
            $rows[] = strtoupper($stock['dateStockage']);
  
            $rows[] = strtoupper($designation['designation']);
  
            $rows[] = strtoupper($designation['forme']);
  
            $rows[] = strtoupper($designation['tableau']);
  
            $rows[] = $stock['quantiteStockinitial'];
  
            $rows[] = $designation['prixSession'];
  
            $rows[] = $designation['prixPublic'];
  
          }
  
        }
  
        else{
  
          // $sqlS="SELECT SUM(totalArticleStock)
  
          // FROM `".$nomtableStock."`
  
          // where idDesignation ='".$stock['idDesignation']."' AND dateStockage BETWEEN '".$debut."' AND '".$fin."' ";
  
          // $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  
          // $S_stock = mysql_fetch_array($resS);
  
  
  
          $rows = array();
  
          if($i==1){
  
            $rows[] = $i;
  
            $rows[] = '<span style="color:blue;">'.strtoupper($stock['dateStockage']).'</span>';
  
            $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
  
            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
  
              if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
  
                $rows[] = '<span style="color:blue;">'.round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']),1) .'</span>';
  
              }
  
              else{
  
                $rows[] = '<span style="color:blue;">'.$stock['quantiteStockinitial'].'</span>';
  
              }
  
            }
  
            else{
  
              $rows[] = '<span style="color:blue;">'.$stock['quantiteStockinitial'].'</span>';
  
            }
  
            $rows[] = '<span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span>';
  
            $rows[] = '<span style="color:blue;">'.$designation['nbreArticleUniteStock'].'</span>';
  
            $rows[] = '<span style="color:blue;">'.$designation['prixachat'].'</span>';
  
            $rows[] = '<span style="color:blue;">'.$stock['prix'].'</span>';
  
          }
  
          else if($date1==$date2){
  
            $rows[] = $i;
  
            $rows[] = strtoupper($stock['dateStockage']);
  
            $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
  
            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
  
              if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
  
                $rows[] = '<span style="color:#ffcc00;">'.round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']),1) .'</span>';
  
              }
  
              else{
  
                $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockinitial'].'</span>';
  
              }
  
            }
  
            else{
  
              $rows[] = '<span style="color:#ffcc00;">'.$stock['quantiteStockinitial'].'</span>';
  
            }
  
            $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['uniteStock']).'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.$designation['nbreArticleUniteStock'].'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.$designation['prixachat'].'</span>';
  
            $rows[] = '<span style="color:#ffcc00;">'.$stock['prix'].'</span>';
  
          }
  
          else{
  
            $rows[] = $i;
  
            $rows[] = strtoupper($stock['dateStockage']);
  
            $rows[] = strtoupper($designation['designation']);
  
            if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
  
              if($stock['quantiteStockinitial']!=0 && $stock['quantiteStockinitial']!=null){
  
                $rows[] = round(($stock['quantiteStockinitial'] / $designation['nbreArticleUniteStock']),1);
  
              }
  
              else{
  
                $rows[] = $stock['quantiteStockinitial'] ;
  
              }
  
            }
  
            else{
  
              $rows[] = $stock['quantiteStockinitial'] ;
  
            }
  
            $rows[] = strtoupper($designation['uniteStock']);
  
            $rows[] = $designation['nbreArticleUniteStock'];
  
            $rows[] = $designation['prixachat'];
  
            $rows[] = $stock['prix'];
  
          }
  
        }
  
  
  
      // $db = explode("-", $debut);
  
      // $date_debut=$db[0].''.$db[1].''.$db[2];
  
      // $df = explode("-", $fin);
  
      // $date_fin=$df[0].''.$df[1].''.$df[2];
  
      // $rows[] = '<button  type="button" onclick="rapport_Entrees('.$stock['idDesignation'].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
  
      // <i class="glyphicon glyphicon-transfer"></i> Details
  
      // </button>';
  
  
  
      $data[] = $rows;
  
      $i=$i + 1;
  
    //   $produits[] = $designation['idDesignation'];
  
    // }
  
  }

  //var_dump($data);
  //echo $data[33][0];

    //get total number of records 
    $total_rows = count($data);

    $total_pages = ceil($total_rows/$item_per_page);

    //position of records
    $page_position = (($page_number-1) * $item_per_page);

    // if ($tri==1) {
      // usort($produits, "cmp1");
      // var_dump($produits);
    // } else {

    //usort($produits, "cmp2");
      
    //Display records fetched from database.
    $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0;
      // $produits=array();
      $data = array_slice($data, $page_position, $item_per_page);
    
    echo '<table class="table table-striped contents"  border="1">';
      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")){
        echo '<thead>
                  <tr>
                    <th>ORDRE</th>
                    <th>DATE</th>
                    <th style="width: 18%;">REFERENCE</th>
                    <th>FORME</th>
                    <th>TABLEAU</th>
                    <th>QUANTITE</th>
                    <th>PRIX SESSION (PS)</th>
                    <th>PRIX PUBLIC (PB)</th>
                  </tr>
                </thead>';
        
      }
      else{
          echo '<thead>
                  <tr>
                    <th>ORDRE</th>
                    <th>DATE</th>
                    <th style="width: 18%;">REFERENCE</th>
                    <th>QUANTITE</th>
                    <th>UNITE STOCK (US) </th>
                    <th>NOMBRE ARTICLE U.S</th>
                    <th>PRIX ACHAT (PA)</th>
                    <th>PRIX UNITAIRE (PU)</th>
                  </tr>
                </thead>';
      }

      
      // var_dump($produits);

      // $produits=array();
      foreach ($data as $tab) {
        // $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
        // $res1=mysql_query($sql1);
        // $design=mysql_fetch_array($res1);
          echo  '<tr>';    
            echo '<td><span>'.$tab[0].'</span></td>';
            echo '<td><span>'.$tab[1].'</span></td>';
            echo '<td><span>'.$tab[2].'</span></td>';
            echo '<td><span>'.$tab[3].'</span></td>';
            echo '<td><span>'.$tab[4].'</span></td>';
            echo '<td><span>'.$tab[5].'</span></td>';
            echo '<td><span>'.$tab[6].'</span></td>';
            echo '<td><span>'.$tab[7].'</span></td>';
          echo'</tr>';
          $i++;
          $cpt++;
      }  
        
      
      if ($cpt==0) {
        # code...
        echo '<tr>';
        echo  '<td colspan="10" align="center">Données introuvables!</td>';
        echo '</tr>';
      }
    echo '</table>';
  
    echo '<div class="">';
      echo '<div class="col-md-4">';
        echo '<ul class="pull-left">';
          if ($item_per_page > $total_rows) {
            # code...
              echo '1 à '.($total_rows).' sur '.$total_rows.' entrées';        
          } else {
            # code...
            if ($page_number == 1) {
              # code...
              echo '1 à '.($item_per_page).' sur '.$total_rows.' entrées';
            } else {
              # code...
              if (($total_rows-($item_per_page * $page_number)) < 0) {
                # code... 
                echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' entrées';
              } else {
                # code...
                echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows.' entrées';
              }
            }
          }      
        echo '</ul>';
      echo '</div>';
      echo '<div class="col-md-8">';
      // To generate links, we call the pagination function here. 
      echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
      echo '</div>';
    echo '';

}

function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  {
      $pagination = '';
      if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination pull-right">';
          
          if ($current_page == 1) {
            # code...
            $pagination .= '<li class="page-item disabled"><a data-page="1" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>
            </li>';
          } else {
            # code...
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page - 1).'" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a></li>';
          }        
        
          if ($total_pages <= 5) {                                            
            for($page = 1; $page <= $total_pages; $page++){
              if ($current_page == $page) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              }
              }
            }else {  
              if ($current_page == 1) {
                # code...
                $pagination .= '<li class="page-item active"><a href="#" data-page="1" class="page-link">1</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item"><a href="#" data-page="1" class="page-link">1</a></li>';
              }
                                                              
              if($current_page == 1 || $current_page == 2){ 
                for($page = 2 ; $page <= 3; $page++){
                  if ($current_page == $page) {
                    # code...
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    # code...
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }                  
                }         
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                                            
                } else if(($current_page > 2) and ($current_page < $total_pages - 2)){  
                  $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';

                  for($page =$current_page ; $page <= ($current_page + 1); $page++){ 
                    if ($current_page == $page) {
                      # code...
                      $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    } else {
                      # code...
                      $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    }
                }
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
              
              }else{
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                for($page = ($total_pages - 2) ; $page <= ($total_pages - 1); $page++){
                  if ($current_page == $page) {
                    # code...
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    # code...
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }
                }
              }
              if ($current_page == $total_pages) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              }
              }
          if ($current_page == $total_pages) {
            # code...
            $pagination .= '<li class="page-item disabled"><a data-page="'.$total_pages.'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          } else {
            # code...
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page + 1).'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          }
          
          $pagination .= '</ul>'; 
      }
      return $pagination; //return pagination links
}

// if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
//   $sql="SELECT l.designation, l.quantite, l.prixTotal, d.idDesignation, d.prixPublic
//   FROM `".$nomtableLigne."` l
//   INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
//   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//   WHERE l.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
// }else{
//   $sql="SELECT l.designation, l.quantite, l.prixtotal, d.idDesignation, p.datepagej, p.heurePagnet
//   FROM `".$nomtableLigne."` l
//   INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
//   INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//   WHERE l.classe = 2 && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
// // }
// $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

// $data=array();
// //$produits=array();
// $i=1;
// while($stock=mysql_fetch_array($res)){

//   $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stock["idDesignation"]."' ";
//   $resN=mysql_query($sqlN);
//   $designation = mysql_fetch_array($resN);

//   // $sqlS="SELECT prixtotal
//   // FROM `".$nomtableLigne."` l
//   // INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
//   // INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//   // where l.classe = 2 && p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
//   // $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
//   // $prixTotal = mysql_fetch_array($resS);

//   // if(in_array($designation['idDesignation'], $produits)){
//   //   // echo "Existe.";
//   // }
//   // else{
//     // $sqlQ="SELECT quantite
//     // FROM `".$nomtableLigne."` l
//     // INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
//     // INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//     // where p.idClient=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ";
//     // $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
//     // $qte = mysql_fetch_array($resQ);

//       if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
//         $rows = array();
//         if($i==1){
//           $rows[] = $i;
//           $rows[] = '<span style="color:blue;">'.strtoupper($stock['designation']).'</span>';
//           $rows[] = '<span style="color:blue;">'.$stock["quantite"].'</span>';
//           $rows[] = '<span style="color:blue;">'.strtoupper($designation['forme']).'</span>';
//           $rows[] = '<span style="color:blue;">'.number_format($designation['prixPublic'], 0, ',', ' ').'</span>';
//           $rows[] = '<span style="color:blue;">'.number_format($stock["prixtotal"], 0, ',', ' ').'</span>';
//         }
//         else if($dateString2==$stock['datepagej']){
//           $rows[] = $i;
//           $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['designation']).'</span>';
//           $rows[] = '<span style="color:#ffcc00;">'.$stock["quantite"].'</span>';
//           $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['forme']).'</span>';
//           $rows[] = '<span style="color:#ffcc00;">'.number_format($designation['prixPublic'], 0, ',', ' ').'</span>';
//           $rows[] = '<span style="color:#ffcc00;">'.number_format($stock["prixtotal"], 0, ',', ' ').'</span>';
//         }
//         else{
//           $rows[] = $i;
//           $rows[] = strtoupper($stock['designation']);
//           $rows[] = $stock["quantite"];
//           $rows[] = strtoupper($designation['forme']);
//           $rows[] = number_format($designation['prixPublic'], 0, ',', ' ');
//           $rows[] = number_format($stock["prixtotal"], 0, ',', ' ');
//         }
//       }
//       else{
//         $rows = array();
//         if($i==1){
//           $rows[] = $i;
//           $rows[] = '<span style="color:blue;">'.strtoupper($stock['designation']).'</span>';
//           $rows[] = '<span style="color:blue;">'.$stock["quantite"].'</span>';
//           $rows[] = '<span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span>';
//           $rows[] = '<span style="color:blue;">'.number_format($designation['prix'], 0, ',', ' ').'</span>';
//           $rows[] = '<span style="color:blue;">'.number_format($stock["prixtotal"], 0, ',', ' ').'</span>';
//         }
//         else if($dateString2==$stock['datepagej']){
//           $rows[] = $i;
//           $rows[] = '<span style="color:#ffcc00;">'.strtoupper($stock['designation']).'</span>';
//           $rows[] = '<span style="color:#ffcc00;">'.$stock["quantite"].'</span>';
//           $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['uniteStock']).'</span>';
//           $rows[] = '<span style="color:#ffcc00;">'.number_format($designation['prix'], 0, ',', ' ').'</span>';
//           $rows[] = '<span style="color:#ffcc00;">'.number_format($stock["prixtotal"], 0, ',', ' ').'</span>';
//         }
//         else{
//           $rows[] = $i;
//           $rows[] = strtoupper($stock['designation']);
//           $rows[] = $stock["quantite"];
//           $rows[] = strtoupper($designation['uniteStock']);
//           $rows[] = number_format($designation['prix'], 0, ',', ' ');
//           $rows[] = number_format($stock["prixtotal"], 0, ',', ' ');
//         }
//       }

//       $db = explode("-", $debut);
//       $date_debut=$db[0].''.$db[1].''.$db[2];
//       $df = explode("-", $fin);
//       $date_fin=$df[0].''.$df[1].''.$df[2];
//       $rows[] = '<span style="color:blue;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';

//       $data[] = $rows;
//       $i=$i + 1;
//       // $produits[] = $designation['idDesignation'];
//     // }
// }

// $results = ["sEcho" => 1,
//           "iTotalRecords" => count($data),
//           "iTotalDisplayRecords" => count($data),
//           "aaData" => $data ];

// echo json_encode($results);

?>
<script type="text/javascript">
  $(function() {
    debut $('#debut').val(debut);
    $('#fin').val(fin);

    alert()

  });

</script>