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

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeCliBonParProduit = @$_POST["nbEntreeCliBonParProduit"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeCliBonParProduit!=0) ? $nbEntreeCliBonParProduit : 10; //item to display per page
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
    // $sql="SELECT *
    // FROM  `".$nomtableLigne."` l
    // INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    // WHERE l.classe = 0 && p.idClient!=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
    // $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

    $sql="SELECT * FROM  `".$nomtableLigne."` l 

    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

    WHERE l.classe = 0 && p.idClient !=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";

    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    
  } else {
    # code...
    //get total number of records from database
    // $sql="SELECT *
    // FROM  `".$nomtableLigne."` l
    // INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    // WHERE l.classe = 0 && p.idClient!=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) 
    // BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
    // $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

    $sql="SELECT * FROM  `".$nomtableLigne."` l 

    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet

    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation

    WHERE l.classe = 0 && p.idClient !=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2))
    BETWEEN '".$debut."' AND '".$fin."' and  (d.designation LIKE '%".$query."%') ORDER BY p.idPagnet DESC ";

    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

  }
  
  //$data=array();

  while($stock=mysql_fetch_array($res)){

    // $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stock["idDesignation"]."' and (designation LIKE '%".$query."%') ";
    // $resN=mysql_query($sqlN);
    $designation = $stock;
  
    $sqlS="SELECT SUM(prixtotal)
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    where p.idClient !=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."'  && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
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
        where p.idClient !=0 &&  p.verrouiller=1 && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ";
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
        where p.idClient !=0 &&  p.verrouiller=1 && l.unitevente!='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ";
        $resQA=mysql_query($sqlQA) or die ("select stock impossible =>".mysql_error());
        $qte_A = mysql_fetch_array($resQA);
  
        $sqlQS="SELECT SUM(quantite)
        FROM `".$nomtableLigne."` l
        INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
        INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
        where p.idClient !=0 &&  p.verrouiller=1 && l.unitevente='".$designation["uniteStock"]."' && p.type=0 && d.idDesignation='".$stock["idDesignation"]."' && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
        $resQS=mysql_query($sqlQS) or die ("select stock impossible =>".mysql_error());
        $qte_S = mysql_fetch_array($resQS);
  
        $rows = array();
        if($i==1){
          $rows[] = $i;
          $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
          if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
            $rows[] = '<span style="color:blue;">'.$qte_S[0].'</span>';
            $rows[] = '<span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span>';
            $rows[] = '<span style="color:blue;">'.$designation['nbreArticleUniteStock'].'</span>';
          }
          else{
            $rows[] = '<span style="color:blue;">'.($qte_A[0] + ($qte_S[0] * $designation['nbreArticleUniteStock'])).'</span>';
            $rows[] = ($designation['uniteDetails']) ? '<span style="color:blue;">'.strtoupper($designation['uniteDetails']).'</span>' : '<span style="color:blue;">'.strtoupper('Article').'</span>';
            $rows[] = '<span style="color:blue;">1</span>';
          }
          if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
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
          if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
            $rows[] = '<span style="color:#ffcc00;">'.$qte_S[0].'</span>';
            $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['uniteStock']).'</span>';
            $rows[] = '<span style="color:#ffcc00;">'.$designation['nbreArticleUniteStock'].'</span>';
          }
          else{
            $rows[] = '<span style="color:#ffcc00;">'.($qte_A[0] + ($qte_S[0] * $designation['nbreArticleUniteStock'])).'</span>';
            $rows[] = ($designation['uniteDetails']) ? '<span style="color:blue;">'.strtoupper($designation['uniteDetails']).'</span>' : '<span style="color:blue;">'.strtoupper('Article').'</span>';
            $rows[] = '<span style="color:#ffcc00;">1</span>';
          }
          if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
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
          if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
            $rows[] = $qte_S[0] ;
            $rows[] = strtoupper($designation['uniteStock']);
            $rows[] = $designation['nbreArticleUniteStock'];
          }
          else{
            $rows[] = ($qte_A[0] + ($qte_S[0] * $designation['nbreArticleUniteStock'])) ;
            $rows[] = ($designation['uniteDetails']) ? strtoupper($designation['uniteDetails']) : strtoupper('Article');
            $rows[] = 1;
          }
          if ($_SESSION['categorie']=="Grossiste" and $stock['unitevente'] == $designation['uniteStock']) {
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
      $rows[] = '<button  type="button" onclick="rapport_Bon('.$stock['idDesignation'].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
      <i class="glyphicon glyphicon-transfer"></i> Details
      </button>';
  
  
      $data[] = $rows;
      $i=$i + 1;
      $produits[] = $designation['idDesignation'];
    }
  }

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
                  <th style="width: 18%;">REFERENCE</th>
                  <th>FORME</th>
                  <th>TABLEAU</th>
                  <th>QUANTITE</th>
                  <th>PRIX PUBLIC (PB)</th>
                  <th>PRIX TOTAL </th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>';
      
    }
    else{
      echo '<thead>
                <tr>
                  <th>ORDRE</th>
                  <th style="width: 18%;">REFERENCE</th>
                  <th>QUANTITE</th>
                  <th>UNITE STOCK (US)</th>
                  <th>NOMBRE ARTICLE U.S</th>
                  <th>PRIX UNITE (PU)</th>
                  <th>PRIX TOTAL</th>
                  <th>OPERATIONS</th>
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
      return $pagination; 
      //return pagination links 

}
