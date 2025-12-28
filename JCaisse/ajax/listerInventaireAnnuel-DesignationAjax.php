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

function cmp1($a, $b)
{
    return strcmp($b["designation"], $a["designation"]);
}

function cmp2($a, $b)
{
    return strcmp($a["designation"], $b["designation"]);
}

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

// $sql="SELECT * FROM `".$nomtableStock."` s
  // LEFT JOIN `".$nomtableDesignation."` d ON d.idDesignation = s.idDesignation
  // WHERE d.classe=0 ORDER BY s.idStock DESC";
  // $res=mysql_query($sql);

  // $data=array();
  // $produits=array();
  // $i=1;
  // while($stock=mysql_fetch_array($res)){

  //   $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
  //   $res1=mysql_query($sql1);
  //   $designation=mysql_fetch_array($res1);

  //   $sqlS="SELECT SUM(quantiteStockCourant)
  //   FROM `".$nomtableStock."`
  //   where idDesignation ='".$stock['idDesignation']."'  ";
  //   $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
  //   $S_stock = mysql_fetch_array($resS);

  //   if(in_array($designation['idDesignation'], $produits)){
  //     // echo "Existe.";
  //    }
  //    else{

  //   $rows = array();
  //   $rows[] = $i;
  //   $rows[] = strtoupper($designation['designation']);
  //   $rows[] = strtoupper($designation['codeBarreDesignation']);
  //   $rows[] = $S_stock[0] ;
  //   $rows[] = '<input type="number" id="quantiteD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="" />'; 	

  //   $rows[] = '<button type="button" class="btn btn-success btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].')"><i class="glyphicon glyphicon-plus">
  //     </i>CORRIGER
  //   </button>&nbsp;';


  //   $data[] = $rows;
  //   $i=$i + 1;
  //   $produits[] = $designation['idDesignation'];
  // }
  // }


  // $results = ["sEcho" => 1,
  //           "iTotalRecords" => count($data),
  //           "iTotalDisplayRecords" => count($data),
  //           "aaData" => $data ];

  // echo json_encode($results);



if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeInv = @$_POST["nbEntreeInv"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page = ($nbEntreeInv!=0) ? $nbEntreeInv : 10; //item to display per page
  $page_number 		= 0; //page number

  $produits = array();

  $produit_0 = array();
  $tabIdDesigantion_0 = array();
  $tabQuantite_0 = array();
  $tabIdStock_0 = array();

  $produit = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  if ($query =="") {
    # code...
    $sql="SELECT d.idDesignation,d.designation FROM `".$nomtableDesignation."` d
    LEFT JOIN `".$nomtableStock."` s ON s.idDesignation = d.idDesignation
    WHERE d.classe=0  AND (s.quantiteStockCourant<>0) AND d.archiver<>1 GROUP BY d.idDesignation ORDER BY d.designation ASC ";
    $res=mysql_query($sql);


    $sql_0="SELECT d.idDesignation,d.designation FROM `".$nomtableDesignation."` d
    WHERE d.classe=0  AND d.archiver<>1 AND idDesignation NOT IN (
      SELECT d.idDesignation FROM `".$nomtableDesignation."` d
      LEFT JOIN `".$nomtableStock."` s ON s.idDesignation = d.idDesignation
      WHERE d.classe=0  AND (s.quantiteStockCourant<>0)  AND d.archiver<>1 GROUP BY d.idDesignation 
    ) ORDER BY d.designation ASC";
    $res_0=mysql_query($sql_0);

    while($stock_0=mysql_fetch_array($res_0)){
      if (in_array($stock_0['idDesignation'], $tabIdDesigantion_0)) {
        # code...
      } else {
        # code...
  
        $tabIdDesigantion_0[]=$stock_0['idDesignation'];
        $produit_0[]=$stock_0;
      }
      
    }
    
  
    while($stock=mysql_fetch_array($res)){
      if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
        # code...
      } else {
        # code...
  
        $tabIdDesigantion[]=$stock['idDesignation'];
        $produit[]=$stock;
      }
      
    }
    
    $produits =array_merge($produit_0, $produit);
    

  } else {
    # code... 
    if ($cb==1) {
      //Limit our results within a specified range. 
      $sql="SELECT d.idDesignation,d.designation FROM `".$nomtableDesignation."` d
      LEFT JOIN `".$nomtableStock."` s ON s.idDesignation = d.idDesignation
      WHERE d.classe=0  AND (d.codeBarreDesignation='".$query."')  AND d.archiver<>1 GROUP BY d.idDesignation ";
      $res=mysql_query($sql);
    }else {
      //Limit our results within a specified range. 
      $sql="SELECT d.idDesignation,d.designation FROM `".$nomtableDesignation."` d
      LEFT JOIN `".$nomtableStock."` s ON s.idDesignation = d.idDesignation
      WHERE d.classe=0  AND (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%')  AND d.archiver<>1 GROUP BY d.idDesignation ";
      $res=mysql_query($sql);
    }

    while($stock=mysql_fetch_array($res)){
      if (in_array($stock['idDesignation'], $tabIdDesigantion)) {
        # code...
      } else {
        # code...
  
        $tabIdDesigantion[]=$stock['idDesignation'];
        $produits[]=$stock;
      }
      
    }

  }


  //get total number of records 
  $total_rows = count($produits);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  // var_dump(max($tabIdStock));

  //Display records fetched from database.

  echo '<table id="tableStock" class="table table-striped display tabStock contents" border="1">';
    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      echo '<thead>
      <tr>
        <th>REFERENCE</th>
        <th>CODEBARRE</th>
        <th>PRIX SESSION</th>
        <th>PRIX PUBLIC</th>
        <th>QTE THEORIQUE</th>
        <th>QTE PHYSIQUE</th>
        <th>OPERATIONS</th>
      </tr>
    </thead>';
    }
    else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
    }
    else{
      echo '<thead>
      <tr>
        <th>REFERENCE</th>
        <th>CODEBARRE</th>
        <th>PRIX ACHAT</th>
        <th>PRIX U</th>
        <th>PRIX US</th>
        <th>QTE THEORIQUE</th>
        <th>QTE PHYSIQUE</th>
        <th>OPERATIONS</th>
      </tr>
    </thead>';
    }

  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $produits = array_slice($produits, $page_position, $item_per_page);

  foreach ($produits as $stock) {

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $designation=mysql_fetch_array($res1);
  
    $sqlS="SELECT SUM(quantiteStockCourant)
    FROM `".$nomtableStock."`
    where idDesignation ='".$stock['idDesignation']."'  ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resS);
    //  var_dump($stock);
    
    // if(in_array($designation['idDesignation'], $produits)){
    //   // echo "Existe.";
    // }
    // else{

      $sql2="SELECT * FROM `".$nomtableInventaire."` where idDesignation='".$stock['idDesignation']."' AND type=10 ORDER by idInventaire DESC";
      $res2=mysql_query($sql2);
      $inventaire=mysql_fetch_array($res2);

      if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
        echo '<tr>';
          if($inventaire!=null){
            echo '<td style="background-color: lightslategray; ">'.strtoupper($designation['designation']).'</td>';
            echo '<td style="background-color: lightslategray; "><input type="text" id="codeBarre-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['codeBarreDesignation'].'" /></td>';
            echo '<td style="background-color: lightslategray; "><input type="number" id="prixSessionD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prixSession'].'" /></td>';
            echo '<td style="background-color: lightslategray; "><input type="number" id="prixPublicD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prixPublic'].'" /></td>';
            echo '<td style="background-color: lightslategray; ">'.$S_stock[0].'</td>';
            echo '<td style="background-color: lightslategray; ">'.'<input type="number" id="quantiteD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="" /></td>'; 	
            if($S_stock[0]==0){
              echo '<td style="background-color: lightslategray; ">
              <button type="button" class="btn btn-success btn-sm btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].',1)">
                <i class="glyphicon glyphicon-refresh"></i>
              </button>&nbsp;
              <button type="button" class="btn btn-warning btn-sm btn_ajtStock" id="btn_InvArchiver-'.$stock['idDesignation'].'" onclick="archiverAnnuelProduit('.$stock["idDesignation"].')">
                <i class="glyphicon glyphicon-export"></i>
              </button>
              </td>';
            }
            else{
              echo '<td style="background-color: lightslategray; ">
              <button type="button" class="btn btn-success btn-sm btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].',1)">
                <i class="glyphicon glyphicon-refresh"></i>
              </button>
              </td>';
            }
          }
          else{
            echo '<td>'.strtoupper($designation['designation']).'</td>';
            echo '<td><input type="text" id="codeBarre-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['codeBarreDesignation'].'" /></td>';
            echo '<td><input type="number" id="prixSessionD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prixSession'].'" /></td>';
            echo '<td><input type="number" id="prixPublicD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prixPublic'].'" /></td>';
            echo '<td>'.$S_stock[0].'</td>';
            echo '<td>'.'<input type="number" id="quantiteD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="" /></td>'; 	
            if($S_stock[0]==0){
              echo '<td>
              <button type="button" class="btn btn-success btn-sm btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].',1)">
                <i class="glyphicon glyphicon-refresh"></i>
              </button>&nbsp;
              <button type="button" class="btn btn-warning btn-sm btn_ajtStock" id="btn_InvArchiver-'.$stock['idDesignation'].'" onclick="archiverAnnuelProduit('.$stock["idDesignation"].')">
                <i class="glyphicon glyphicon-export"></i>
              </button>
              </td>';
            }
            else{
              echo '<td>
              <button type="button" class="btn btn-success btn-sm btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].',1)">
                <i class="glyphicon glyphicon-refresh"></i>
              </button>
              </td>';
            }
          }
        echo '</tr>';
      }
      else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
      }
      else{
        echo '<tr>';
          if($inventaire!=null){
            echo '<td style="background-color: lightslategray; ">'.strtoupper($designation['designation']).'</td>';
            echo '<td style="background-color: lightslategray; "><input type="text" id="codeBarre-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['codeBarreDesignation'].'" /></td>';
            echo '<td style="background-color: lightslategray; "><input type="number" id="prixachatD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prixachat'].'" /></td>';
            echo '<td style="background-color: lightslategray; "><input type="number" id="prixD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prix'].'" /></td>';            
            echo '<td style="background-color: lightslategray; "><input type="number" id="prixuniteStockD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prixuniteStock'].'" /></td>';
            echo '<td style="background-color: lightslategray; ">'.$S_stock[0].'</td>';
            echo '<td style="background-color: lightslategray; ">'.'<input type="number" id="quantiteD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="" /></td>'; 	
            if($S_stock[0]==0){
              echo '<td style="background-color: lightslategray; ">
              <button type="button" class="btn btn-success btn-sm btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].',2)">
                <i class="glyphicon glyphicon-refresh"></i>
              </button>&nbsp;
              <button type="button" class="btn btn-warning btn-sm btn_ajtStock" id="btn_InvArchiver-'.$stock['idDesignation'].'" onclick="archiverAnnuelProduit('.$stock["idDesignation"].')">
                <i class="glyphicon glyphicon-export"></i>
              </button>
              </td>';
            }
            else{
              echo '<td style="background-color: lightslategray; ">
              <button type="button" class="btn btn-success btn-sm btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].',2)">
                <i class="glyphicon glyphicon-refresh"></i>
              </button>
              </td>';
            }
          }
          else{
            echo '<td>'.strtoupper($designation['designation']).'</td>';
            echo '<td><input type="text" id="codeBarre-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['codeBarreDesignation'].'" /></td>';
            echo '<td><input type="number" id="prixachatD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prixachat'].'" /></td>';
            echo '<td><input type="number" id="prixD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prix'].'" /></td>';            
            echo '<td><input type="number" id="prixuniteStockD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="'.$designation['prixuniteStock'].'" /></td>';
            echo '<td>'.$S_stock[0].'</td>';
            echo '<td>'.'<input type="number" id="quantiteD-'.$stock["idDesignation"].'" class="form-control quantitePhysiqueIntermittent" width="20%"  min=1 value="" /></td>'; 	
            if($S_stock[0]==0){
              echo '<td>
              <button type="button" class="btn btn-success btn-sm btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].',2)">
                <i class="glyphicon glyphicon-refresh"></i>
              </button>&nbsp;
              <button type="button" class="btn btn-warning btn-sm btn_ajtStock" id="btn_InvArchiver-'.$stock['idDesignation'].'" onclick="archiverAnnuelProduit('.$stock["idDesignation"].')">
                <i class="glyphicon glyphicon-export"></i>
              </button>
              </td>';
            }
            else{
              echo '<td>
              <button type="button" class="btn btn-success btn-sm btn_ajtStock" id="btn_InvStock-'.$stock['idDesignation'].'" onclick="inventaireAnnuelDesignation('.$stock["idDesignation"].',2)">
                <i class="glyphicon glyphicon-refresh"></i>
              </button>
              </td>';
            }
          }
        echo '</tr>';
      }

      $i++;
      $cpt++;
      // $produits[] = $designation['idDesignation'];
    // }
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
      echo '<ul class="pull-left" style="margin-top:30px;">';
        if ($item_per_page > $total_rows) {
          # code...
            echo '1 à '.($total_rows).' sur '.$total_rows.' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows.' articles';
          } else {
            # code...
            if (($total_rows-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows.' articles';
            }
          }
        }      
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
    echo '</div>';
  echo '</div>';

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


  // function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  // {

    //     $pagination = '';
    //     if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
    //         $pagination .= '<ul class="pagination">';
            
    //         $right_links    = $current_page + 3; 
    //         $previous       = $current_page - 3; //previous link 
    //         $next           = $current_page + 1; //next link
    //         $first_link     = true; //boolean var to decide our first link
            
    //         if($current_page > 1){
    //           $previous_link = ($previous==0)?1:$previous;
    //           $pagination .= '<li class="first"><a href="#" data-page="1" title="First">«</a></li>'; //first link
    //           $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous"><</a></li>'; //previous link
    //               for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
    //                   if($i > 0){
    //                       $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
    //                   }
    //               }   
    //           $first_link = false; //set first link to false
    //         }
            
    //         if($first_link){ //if current active page is first link
    //             $pagination .= '<li class="first active">'.$current_page.'</li>';
    //         }elseif($current_page == $total_pages){ //if it's the last active link
    //             $pagination .= '<li class="last active">'.$current_page.'</li>';
    //         }else{ //regular current link
    //             $pagination .= '<li class="active">'.$current_page.'</li>';
    //         }
                    
    //         for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
    //             if($i<=$total_pages){
    //                 $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
    //             }
    //         }
    //         if($current_page < $total_pages){ 
    //           $next_link = ($i > $total_pages)? $total_pages : $i;
    //           $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">></a></li>'; //next link
    //           $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">»</a></li>'; //last link
    //         }
            
    //         $pagination .= '</ul>'; 
    //     }
    //     return $pagination; //return pagination links
    // }

?>