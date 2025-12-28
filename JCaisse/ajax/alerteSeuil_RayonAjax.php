<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka, Mor Mboup
Date de création : 20/03/2016
Date derni�re modification : 21/08/2023
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

  $nbEntreeAlerteSeuil = @$_POST["nbEntreeAlerteSeuil_Rayon"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeAlerteSeuil!=0) ? $nbEntreeAlerteSeuil : 10; //item to display per page
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
    
    // $data = file_get_contents('../stock_local/'.$nomtableStock.'.json');
    // // var_dump('$data');
    // // json_decode($json, true);

    // $stocks = json_decode($data, true);
    // var_dump(count($stocks));
    
    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {

      // $sqlGetStock = $bdd->prepare("SELECT DISTINCT idDesignation, quantiteStockTemp as s_stock FROM `".$nomtableEntrepotStock."`
      // GROUP BY idDesignation 
      // HAVING SUM(quantiteStockCourant)>0");
      // $sqlGetStock->execute(array()) or
      // die(print_r($sqlGetStock->errorInfo()));

    } else {
      
      // $sql="SELECT DISTINCT s.idDesignation FROM `".$nomtableStock."` s
      // WHERE (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0)" ;
      // // WHERE d.classe=0 AND (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0) ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";
      // $res=mysql_query($sql); and sum(s.quantiteStockCourant)<>s.quantiteStockTemp

      $sqlGetStock = $bdd->prepare("SELECT sum(s.quantiteStockCourant), quantiteStockTemp, s.idDesignation FROM `".$nomtableStock."` s GROUP BY s.idDesignation HAVING sum(s.quantiteStockCourant)>0 ORDER BY s.quantiteStockTemp ASC");
      // var_dump($sqlGetStock);
      $sqlGetStock->execute(array()) or
      die(print_r($sqlGetStock->errorInfo()));

      // $sqlGetStock = $bdd->prepare("SELECT DISTINCT idDesignation, SUM(quantiteStockCourant) as s_stock FROM `".$nomtableStock."`
      // GROUP BY idDesignation 
      // HAVING SUM(quantiteStockCourant)=0");
      // $sqlGetStock->execute(array()) or
      // die(print_r($sqlGetStock->errorInfo()));
    }
  } else {
    # code...
    //Limit our results within a specified range. 
    if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
    //   $sql="SELECT DISTINCT s.idDesignation FROM `".$nomtableEntrepotStock."` s
    //   WHERE (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0) and (s.designation LIKE '%".$query."%')";
    //   // WHERE d.classe=0 AND (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";
    //   $res=mysql_query($sql);
      
      // $sqlGetStock = $bdd->prepare("SELECT DISTINCT s.idDesignation FROM `".$nomtableEntrepotStock."` s
      // WHERE (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0) and (s.designation LIKE '%".$query."%')");
      // $sqlGetStock->execute(array()) or
      // die(print_r($sqlGetStock->errorInfo()));

      // $sqlGetStock = $bdd->prepare("SELECT DISTINCT idDesignation, designation, SUM(quantiteStockCourant) as s_stock FROM `".$nomtableEntrepotStock."`
      // GROUP BY idDesignation 
      // HAVING SUM(quantiteStockCourant)=0 and (designation LIKE '%".$query."%')");
      // $sqlGetStock->execute(array()) or
      // die(print_r($sqlGetStock->errorInfo()));
      
    } else {
      // $sql="SELECT DISTINCT s.idDesignation, SUM(quantiteStockCourant) as s_stock FROM `".$nomtableStock."` s
      // WHERE (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0) and (s.designation LIKE '%".$query."%')";
      // // $sql="SELECT d.idDesignation FROM `".$nomtableStock."` s
      // // LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation 
      // // WHERE d.classe=0 AND (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%')";
      // // WHERE d.classe=0 AND (s.quantiteStockCourant<>0 OR s.quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";
      // $res=mysql_query($sql);

      // $sqlGetStock = $bdd->prepare("SELECT DISTINCT s.idDesignation FROM `".$nomtableStock."` s and sum(s.quantiteStockCourant)<>s.quantiteStockTemp
      // WHERE s.designation LIKE '%".$query."%' and s.quantiteStockCourant>0 ORDER BY s.quantiteStockTemp ASC");
      $sqlGetStock = $bdd->prepare("SELECT sum(s.quantiteStockCourant), quantiteStockTemp, s.idDesignation FROM `".$nomtableStock."` s, `".$nomtableDesignation."` d  WHERE s.idDesignation=d.idDesignation and (s.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') GROUP BY s.idDesignation HAVING sum(s.quantiteStockCourant)>1 ORDER BY s.quantiteStockTemp ASC");
      // var_dump($sqlGetStock);
      $sqlGetStock->execute(array()) or
      die(print_r($sqlGetStock->errorInfo()));
      

      // $sqlGetStock = $bdd->prepare("SELECT DISTINCT idDesignation, designation, SUM(quantiteStockCourant) as s_stock FROM `".$nomtableStock."`
      // GROUP BY idDesignation 
      // HAVING SUM(quantiteStockCourant)=0 and (designation LIKE '%".$query."%')");
      // $sqlGetStock->execute(array()) or
      // die(print_r($sqlGetStock->errorInfo()));

    }
  }
  
  // $d=0;
  $produits=$sqlGetStock->fetchAll();
  // $z=0;
  
  // foreach ($produits as $stock) {

  //   $sqlS="SELECT SUM(quantiteStockCourant)
  //   FROM `".$nomtableStock."`
  //   where idDesignation ='".$stock0['idDesignation']."'  ";
  //   $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  //   $S_stock0 = mysql_fetch_array($resS);

  //   if ($S_stock0[0] == $stock0['quantiteStockTemp'] ) {
  //     # code...
  //     unset($produits[$z]);
  //   }
  //   $z++;
  // }

  // $c=0;

  // foreach ($produits as $stock) {
      
  //   $sqlS="SELECT SUM(quantiteStockCourant) as s_stock, quantiteStockTemp
  //   FROM `".$nomtableStock."`
  //   where idDesignation ='".$stock['idDesignation']."'";
  //   $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  //   $produitSum = mysql_fetch_array($resS); 

  //   if($produitSum['s_stock']== $produitSum['quantiteStockTemp']){
  //     unset($produits[$c])
  //   }

  //   $c++;
    
  // }

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
              <th style="width: 15%;">Quantité en rayon</th>
              <th style="width: 15%;">Quantité en stock</th>
              <th style="width: 15%;">Réference</th>
              <th style="width: 15%;">Catégorie</th>
              <th style="width: 18%;">Mise en rayon</th>
              <th style="width: 5%;"></th>
            </tr>
          </thead>';

  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  // $produits=array();
  $produits = array_slice($produits, $page_position, $item_per_page);
  // var_dump($produits);

  // $produits=array();
  foreach ($produits as $stock) {

    $sqlS="SELECT *
    FROM `".$nomtableStock."`
    where idDesignation ='".$stock['idDesignation']."' LIMIT 1";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $stockTemp = mysql_fetch_array($resS);

    $sqlS="SELECT SUM(quantiteStockCourant)
    FROM `".$nomtableStock."`
    where idDesignation ='".$stock['idDesignation']."'  ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);

    // var_dump($stockTemp['quantiteStockCourant']."/".$stockTemp['quantiteStockTemp']);

    $sql1="SELECT designation, categorie FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $design=mysql_fetch_array($res1);
  
      echo  '<tr>
          <input id="totalStock-'.$stock["idDesignation"].'" type="hidden" value="'.$S_stock[0].'">
          <input id="stockTemp-'.$stock["idDesignation"].'" type="hidden" value="'.$stockTemp['quantiteStockTemp'].'">
      ';    
        
        // $S_produit = $stockTemp['quantiteStockTemp'];

    
        // if($S_produit==0){
            echo  '<td><span style="color:#066649;">'.$stockTemp['quantiteStockTemp'].'</span></td>';
            echo  '<td><span style="color:#80630e;">'.$S_stock[0].'</span></td>';
            echo  '<td><span style="color:#020846;">'.strtoupper($design['designation']).'</span></td>';
            echo  '<td><span style="color:#020846;">'.strtoupper($design['categorie']).'</span></td>';
            echo  '<td><input type="number" id="qtyRayon-'.$stock["idDesignation"].'" class="form-control qtyRayon" width="20%"  min=1 value=""/>
            </td>';

            echo '<td><button type="button" class="btn btn-success" id="btnPlacerRayon-'.$stock['idDesignation'].'" onclick="placerRayonAlert('.$stock["idDesignation"].')"><i class="glyphicon glyphicon-ok">
                    </i>
                  </button>&nbsp;</td>';

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
