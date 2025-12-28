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
require('../connectionPDO.php');

require('../declarationVariables.php');

function cmp1($a, $b)
{
    return strcmp($b["designation"], $a["designation"]);
}
function cmp2($a, $b)
{
    return number_format($a["s_stock"]) - number_format($b["s_stock"]);
}



$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeAlerteDepot = @$_POST["nbEntreeAlerteDepot"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeAlerteDepot!=0) ? $nbEntreeAlerteDepot : 10; //item to display per page
  $page_number 		= 0; //page number
  $total_rows 		= 0; //rows number
  $produits = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  //get total number of records from database
  
  if ($query =="") {
    
    $sqlGetStock = $bdd->prepare("SELECT  SUM(quantiteStockinitial) as s_stock, idDesignation FROM `".$nomtableEntrepotStock."` e
    WHERE (e.idTransfert=0 OR e.idTransfert IS NULL) GROUP BY idDesignation ");
    $sqlGetStock->execute(array()) or
    die(print_r($sqlGetStock->errorInfo()));
   
  } else {
    $sqlGetStock = $bdd->prepare("SELECT SUM(e.quantiteStockinitial) as s_stock, idDesignation FROM `".$nomtableEntrepotStock."` e
    WHERE (idTransfert=0 OR idTransfert IS NULL) and (designation LIKE '%".$query."%') GROUP BY idDesignation ");
    $sqlGetStock->execute(array()) or
    die(print_r($sqlGetStock->errorInfo()));
  }
  // $c=0;
  // $d=0;
  $stocks=$sqlGetStock->fetchAll();

  foreach ($stocks as $stock) {
    if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
      # code...
    } else {
      # code...

        $sqlS="SELECT idDesignation, SUM(quantiteStockinitial) as s_stock
        FROM `".$nomtableStock."`
        where idDesignation ='".$stock['idDesignation']."' GROUP BY idDesignation";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $produitSum = mysql_fetch_array($resS);
        $difference=$produitSum['s_stock'] - $stock['s_stock'];
        if($difference == 0){
            $tabIdDesigantion[]=$stock['idDesignation'];
            $stock['s_stock']= $difference;
            $produits[]=$stock;
        }
    }
  }
  
  //Trier le tableau par ordre croissant
  asort($produits);

  //get total number of records 
  $total_rows = count($produits);
  // var_dump($total_rows); 
  // var_dump($d);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  // if ($tri==1) {
    // usort($produits, "cmp1");
    // var_dump($produits);
  // } else {
    //usort($produits, "cmp2");
    
    // var_dump($produits);
  // }

  // if ($tri==1) {
  //   usort($produits, "cmp1");
  //   // var_dump($produits);
  // } else {
  //   usort($produits, "cmp2");
  //   // var_dump($produits);
  // }

  //var_dump($sql);
  //var_dump($res);
  //Display records fetched from database.
  
    echo '<table class="table table-striped contents"  border="1">';
    echo '<thead>
            <tr>
              <th style="width: 15%;">Quantité</th>
              <th style="width: 15%;">Réference</th>
              <th style="width: 15%;">Catégorie</th>
              <th style="width: 18%;">Statut</th>
            </tr>
          </thead>';

  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  // $produits=array();
  $produits = array_slice($produits, $page_position, $item_per_page);
  // var_dump($produits);

  // $produits=array();
  foreach ($produits as $stock) {

    $sql1="SELECT designation, categorie FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $design=mysql_fetch_array($res1);
  
      echo  '<tr>';    
        
        $S_depot = $stock['s_stock'];

    
        // if($S_produit==0){
            echo  '<td><span style="color:#020846;">'.$S_depot.'</span></td>';
            echo  '<td><span style="color:#020846;">'.strtoupper($design['designation']).'</span></td>';
            echo  '<td><span style="color:#020846;">'.strtoupper($design['categorie']).'</span></td>';
            echo  '<td><span style="color:#09169C;">REAPPROVISIONNEMENT</span>';

          echo'</tr>';
          $i++;
          $cpt++;
        // }  
    // }
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="4" align="center">Données introuvables!</td>';
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

function paginate_function($item_per_page, $current_page, $total_records, $total_pages){
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

?>
