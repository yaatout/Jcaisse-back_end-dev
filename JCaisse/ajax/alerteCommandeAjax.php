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

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

    $nbEntreeAlerteCmd = @$_POST["nbEntreeAlerteCmd"];
    $query = @$_POST["query"];
    $cb = @$_POST["cb"];
    $item_per_page = ($nbEntreeAlerteCmd!=0) ? $nbEntreeAlerteCmd : 10; //item to display per page
    $page_number 		= 0; //page number
    $total_rows 		= 0; 
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

    if ($query =="") {

      # code...

      $sql="SELECT l.idDesignation FROM `".$nomtableLigne."` l 
      INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
      WHERE l.classe = 0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej ='".$dateString2."' ORDER BY p.idPagnet DESC ";
      $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

      // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";

      //$res=mysql_query($sql);

    } else {

      # code... 

      if ($cb==1) {

        //Limit our results within a specified range. 

        $sql="SELECT l.idDesignation FROM `".$nomtableLigne."` l 
        INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
        WHERE l.classe = 0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej ='".$dateString2."' and (l.designation LIKE '%".$query."%') ORDER BY p.idPagnet DESC ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";

        //$res=mysql_query($sql);

      } else {

        //Limit our results within a specified range. 

        $sql="SELECT l.idDesignation FROM `".$nomtableLigne."` l 
        INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
        WHERE l.classe = 0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej ='".$dateString2."' and (l.designation LIKE '%".$query."%') ORDER BY p.idPagnet DESC ";
        $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

        // WHERE d.classe=0 AND (quantiteStockCourant<>0 OR quantiteStockCourant=0) and (d.designation LIKE '%".$query."%' or d.codeBarreDesignation LIKE '%".$query."%') ORDER BY s.idStock DESC LIMIT $page_position, $item_per_page";      

        //$res=mysql_query($sql);

      }

      
  }

  while($ligne=mysql_fetch_array($res)){
    if (in_array($ligne['idDesignation'], $tabIdDesigantion)) {
      # code...
    } else {
      # code...
      
      $sqlS="SELECT SUM(quantiteStockCourant)
      FROM `".$nomtableStock."`
      where idDesignation ='".$ligne['idDesignation']."' ";
      $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
      $S_produit = mysql_fetch_array($resS);

      if ($S_produit[0]<=1){

        $tabIdDesigantion[]=$ligne['idDesignation'];
        $produits[]=$ligne;
        $total_rows+=1;

      }
    }
    
  }


  // $total_rows = count($produits);
  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

//   if ($query =="") {

//     # code...

//     $sql="SELECT l.idDesignation FROM `".$nomtableLigne."` l 
//     INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//     WHERE l.classe = 0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej ='".$dateString2."' ORDER BY p.idPagnet DESC LIMIT $page_position, $item_per_page ";
//     $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

//   } else {

//     # code... 

//     if ($cb==1) {

//       //Limit our results within a specified range. 

//       $sql="SELECT l.idDesignation FROM `".$nomtableLigne."` l 
//       INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//       WHERE l.classe = 0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej ='".$dateString2."' and (l.designation LIKE '%".$query."%') ORDER BY p.idPagnet DESC LIMIT $page_position, $item_per_page ";
//       $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

//     }else {

//       //Limit our results within a specified range. 

//       $sql="SELECT l.idDesignation FROM `".$nomtableLigne."` l 
//       INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
//       WHERE l.classe = 0  && p.idClient=0  && p.verrouiller=1 && p.type=0 && p.datepagej ='".$dateString2."' and (l.designation LIKE '%".$query."%') ORDER BY p.idPagnet DESC LIMIT $page_position, $item_per_page";
//       $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

//     }
// }

    echo '<table class="table table-striped contents"  border="1">';
      echo '<thead>
              <tr>
                <th style="width: 15%;">Quantité</th>
                <th style="width: 15%;">Référence</th>
                <th style="width: 15%;">Forme</th>
                <th style="width: 18%;">Statut</th>
              </tr>
            </thead>';

      $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0;
      $p=count($produits);
      $produits = array_slice($produits, $page_position, $item_per_page);

      foreach ($produits as $reference) {

        $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$reference["idDesignation"]."' ";
        $resN=mysql_query($sqlN);
        $designation = mysql_fetch_array($resN);
      
        $sqlS="SELECT SUM(quantiteStockCourant)
        FROM `".$nomtableStock."`
        where idDesignation ='".$reference['idDesignation']."' ";
        $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
        $S_produit = mysql_fetch_array($resS); 
        if ($S_produit[0]<=1){
          
          echo'<tr>';
            //$rows = array();
      
            echo  '<td><span style="color:red;">'.$S_produit[0].'</span></td>';
            echo  '<td><span style="color:red;">'.strtoupper($designation['designation']).'</span></td>';
            echo  '<td><span style="color:red;">'.strtoupper($designation['forme']).'</span></td>';
            echo  '<td><span style="color:blue;">A COMMANDER</span></td>';
      
            //$data[] = $rows;
            //$produits[] = $designation['idDesignation'];
          
          echo'</tr>'; 
          
        } 
        // else {
          
        //   echo'<tr>';
        //     //$rows = array();
      
        //     echo  '<td><span style="color:green;">'.$S_produit[0].'/'.$p.'</span></td>';
        //     echo  '<td><span style="color:green;">'.strtoupper($designation['designation']).'</span></td>';
        //     echo  '<td><span style="color:green;">'.strtoupper($designation['forme']).'</span></td>';
        //     echo  '<td><span style="color:blue;">A COMMANDER</span></td>';
      
        //     //$data[] = $rows;
        //     //$produits[] = $designation['idDesignation'];
          
        //   echo'</tr>'; 
          
        // } 
        // }

        $i++;
        $cpt++;
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

        echo '<ul class="pull-left" style="margin-top:30px;">';

          if ($item_per_page > $total_rows) {

            //# code...

              echo '1 a '.($total_rows).' sur '.$total_rows.' entrees';        

          } else {

            //# code...

            if ($page_number == 1) {

             // # code...

              echo '1 a '.($item_per_page).' sur '.$total_rows.' entrees';

            } else {

             // # code...

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

    echo '</div>';
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
