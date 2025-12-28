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

$sql0="SELECT * FROM `aaa-utilisateur` WHERE idutilisateur =".$_SESSION['iduser'];
$res0=mysql_query($sql0);
$entrepot = mysql_fetch_array($res0);

// $sql="SELECT * FROM `".$nomtableEntrepotTransfert."` WHERE idEntrepot='".$entrepot['idEntrepot']."' ORDER BY idEntrepotTransfert DESC";
// $res=mysql_query($sql);

// $data=array();
// $produits=array();
// $i=1;
// while($transfert=mysql_fetch_array($res)){

//   $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$transfert['idDesignation']."' ";
//   $res1=mysql_query($sql1);
//   $designation=mysql_fetch_array($res1);

//   $sql2="SELECT * FROM `aaa-utilisateur` WHERE idutilisateur =".$transfert['iduser'];
//   $res2=mysql_query($sql2);
//   $personne = mysql_fetch_array($res2);

//   $date1 = strtotime($dateString); 
//   $date2 = strtotime($transfert['dateTransfert']); 

//   $rows = array();
//   if($i==1){
//     echo  '<td>'.$i;
//     echo  '<td><span style="color:blue;">'.strtoupper($designation['designation']).'</span></td>';
//     echo  '<td><span style="color:blue;">'.$transfert['quantiteInitiale'].'</span></td>';
//     echo  '<td><span style="color:blue;">'.$transfert['quantiteFinale'].'</span></td>';
//     echo  '<td><span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span></td>';
//     echo  '<td><span style="color:blue;">'.$transfert['dateTransfert'].'</span></td>';
//   }	
//   else if($date1==$date2){
//     echo  '<td>'.$i;
//     echo  '<td><span style="color:green;">'.strtoupper($designation['designation']).'</span></td>';
//     echo  '<td><span style="color:green;">'.$transfert['quantiteInitiale'].'</span></td>';
//     echo  '<td><span style="color:green;">'.$transfert['quantiteFinale'].'</span></td>';
//     echo  '<td><span style="color:green;">'.strtoupper($designation['uniteStock']).'</span></td>';
//     echo  '<td><span style="color:green;">'.$transfert['dateTransfert'].'</span></td>';
//   }	
//   else{
//     echo  '<td>'.$i;
//     echo  '<td>'.strtoupper($designation['designation']);
//     echo  '<td>'.$transfert['quantiteInitiale'];
//     echo  '<td>'.$transfert['quantiteFinale'];
//     echo  '<td>'.strtoupper($designation['uniteStock']);
//     echo  '<td>'.$transfert['dateTransfert'];
//   }	

//   $total=0;
//   $sqlE="SELECT SUM(quantiteStockinitial)
//   FROM `".$nomtableEntrepotStock."`
//   where  idEntrepotTransfert ='".$transfert['idEntrepotTransfert']."'  ";
//   $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
//   $E_stock = mysql_fetch_array($resE);
//   if($E_stock[0]!=null){
//     $total=$E_stock[0];
//   }

//   if($transfert["quantite"]==$total){
//     echo  '<td>OK';
//     echo  '<td>Aucune';
//   }
//   else{
//     if($transfert["etat1"]==1){
//         if($transfert["etat2"]==1){
//           echo  '<td>En cours';
//           echo  '<td><button type="button" disabled="true" class="btn btn-info btn_ajtStock"><i class="glyphicon glyphicon-ok">
//             </i>
//           </button>&nbsp;';
//         }else{
//           echo  '<td>En cours';
//             echo  '<td><button type="button" class="btn btn-success btn_ajtStock" onclick="validerTransfert_2('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].')" id="btn_ValiderTransfert-'.$transfert['idEntrepotTransfert'].'"><i class="glyphicon glyphicon-ok">
//             </i>
//           </button>&nbsp;';
//         }
//     }
//     else{
//       if($E_stock[0]!=null && $E_stock[0]!=0){
//         echo  '<td>En cours';
//         echo  '<td><a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"  /></a>&nbsp;';
//       }
//       else{
//         echo  '<td>En cours';
//         echo  '<td><a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"  /></a>&nbsp;
//         <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"   /></a>&nbsp;';
//       }
//     }
//   }

//   echo  '<td>'.strtoupper($personne['nom']);




//   $data[] = $rows;
//   $i=$i + 1;

// }


// $results = ["sEcho" => 1,
//           "iTotalRecords" => count($data),
//           "iTotalDisplayRecords" => count($data),
//           "aaData" => $data ];

// echo json_encode($results);


$operation=@htmlspecialchars($_POST["operation"]);
// $operation="1";
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeS = @$_POST["nbEntreeTransfert"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page = ($nbEntreeS!=0) ? $nbEntreeS : 10; //item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  } else {
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  
  if ($query =="") {
    # code...
    $sql="SELECT COUNT(*) as total_row FROM `".$nomtableEntrepotTransfert."` WHERE idEntrepot='".$entrepot['idEntrepot']."' ORDER BY idEntrepotTransfert DESC";
    $res=mysql_query($sql);
  } else {
    # code...
    //get total number of records from database
    $sql="SELECT COUNT(*) as total_row FROM `".$nomtableEntrepotTransfert."` WHERE idEntrepot='".$entrepot['idEntrepot']."' and (designation LIKE '%".$query."%') ORDER BY idEntrepotTransfert DESC";
    $res=mysql_query($sql);
  }

  $total_rows = mysql_fetch_array($res);
  
  $total_rows = $total_rows[0];

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code...
    $sql="SELECT * FROM `".$nomtableEntrepotTransfert."` WHERE idEntrepot='".$entrepot['idEntrepot']."' ORDER BY idEntrepotTransfert DESC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    # code...
    //Limit our results within a specified range. 
    $sql="SELECT * FROM `".$nomtableEntrepotTransfert."` WHERE idEntrepot='".$entrepot['idEntrepot']."' and (designation LIKE '%".$query."%') ORDER BY idEntrepotTransfert DESC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  }


  // var_dump(max($tabIdStock));

//Display records fetched from database.
echo '<table id="tableTransfert" class="table table-striped display tabStock tableau3" align="left" border="1">';
echo '    <thead>
          <tr id="thStock">
            <th>ORDRE</th>
            <th>REFERENCE</th>
            <th>INITIALE</th>
            <th>FINALE</th>
            <th>UNITE STOCK </th>
            <th>DATE TRANSFERT</th>
            <th>STATUT</th>
            <th>OPERATIONS</th>
            <th>PERSONNEL</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
    
  while($transfert=mysql_fetch_array($res)){

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$transfert['idDesignation']."' ";
    $res1=mysql_query($sql1);
    $designation=mysql_fetch_array($res1);

    $sql2="SELECT * FROM `aaa-utilisateur` WHERE idutilisateur =".$transfert['iduser'];
    $res2=mysql_query($sql2);
    $personne = mysql_fetch_array($res2);

    $date1 = strtotime($dateString); 
    $date2 = strtotime($transfert['dateTransfert']); 

    $rows = array();
    
    echo '<tr>';

    if($i==1){
      echo  '<td>'.$i.'</td>';
      echo  '<td><span style="color:blue;">'.strtoupper($designation['designation']).'</span></td>';
      echo  '<td><span style="color:blue;">'.$transfert['quantiteInitiale'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$transfert['quantiteFinale'].'</span></td>';
      echo  '<td><span style="color:blue;">'.strtoupper($designation['uniteStock']).'</span></td>';
      echo  '<td><span style="color:blue;">'.$transfert['dateTransfert'].'</span></td>';
    }	
    else if($date1==$date2){
      echo  '<td>'.$i.'</td>';
      echo  '<td><span style="color:green;">'.strtoupper($designation['designation']).'</span></td>';
      echo  '<td><span style="color:green;">'.$transfert['quantiteInitiale'].'</span></td>';
      echo  '<td><span style="color:green;">'.$transfert['quantiteFinale'].'</span></td>';
      echo  '<td><span style="color:green;">'.strtoupper($designation['uniteStock']).'</span></td>';
      echo  '<td><span style="color:green;">'.$transfert['dateTransfert'].'</span></td>';
    }	
    else{
      echo  '<td>'.$i.'</td>';
      echo  '<td>'.strtoupper($designation['designation']).'</td>';
      echo  '<td>'.$transfert['quantiteInitiale'].'</td>';
      echo  '<td>'.$transfert['quantiteFinale'].'</td>';
      echo  '<td>'.strtoupper($designation['uniteStock']).'</td>';
      echo  '<td>'.$transfert['dateTransfert'].'</td>';
    }	

    $total=0;
    $sqlE="SELECT SUM(quantiteStockinitial)
    FROM `".$nomtableEntrepotStock."`
    where  idEntrepotTransfert ='".$transfert['idEntrepotTransfert']."'  ";
    $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
    $E_stock = mysql_fetch_array($resE);
    if($E_stock[0]!=null){
      $total=$E_stock[0];
    }

    if($transfert["quantite"]==$total){
      echo  '<td>OK</td>';
      echo  '<td>Aucune</td>';
    }
    else{
      if($transfert["etat1"]==1){
          if($transfert["etat2"]==1){
            echo  '<td>En cours</td>';
            echo  '<td><button type="button" disabled="true" class="btn btn-info btn_ajtStock"><i class="glyphicon glyphicon-ok">
              </i>
            </button>&nbsp;</td>';
          }else{
            echo  '<td>En cours</td>';
              echo  '<td><button type="button" class="btn btn-success btn_ajtStock" onclick="validerTransfert_2('.$transfert["idEntrepotTransfert"].','.$designation["idDesignation"].')" id="btn_ValiderTransfert-'.$transfert['idEntrepotTransfert'].'"><i class="glyphicon glyphicon-ok">
              </i>
            </button>&nbsp;</td>';
          }
      }
      else{
        if($E_stock[0]!=null && $E_stock[0]!=0){
          echo  '<td>En cours</td>';
          echo  '<td><a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"  /></a>&nbsp;</td>';
        }
        else{
          echo  '<td>En cours</td>';
          echo  '<td><a><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"  /></a>&nbsp;
          <a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_Transfert('.$transfert["idEntrepotTransfert"].','.$i.')"   /></a>&nbsp;</td>';
        }
      }
    }

    echo  '<td>'.strtoupper($personne['nom']).'</td>';

    echo '</tr>';

    $i=$i + 1;
    $cpt++;

  }


  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="9" align="center">Données introuvables!</td>';
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
