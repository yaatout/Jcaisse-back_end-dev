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
  header('Location:../../index.php');
}

if($_SESSION['vitrine']==0){
	header('Location:../../accueil.php');
}

require('../../connection.php');
require('../../connectionVitrine.php');

require('../../declarationVariables.php');


$operation=@htmlspecialchars($_POST["operation"]);

if ($operation=="1") {
  # code...

$item_per_page 		= 10; //item to display per page
$page_number 		= 0; //page number

//Get page number from Ajax
if(isset($_POST["page"])){
	$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
	if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
}else{
	$page_number = 1; //if there's no page number, set it to 1
}

//get total number of records from database
$req2 = $bddV->prepare("SELECT COUNT(*) from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1");
$req2->execute() or die(print_r($req2->errorInfo()));
$total_rows = $req2->fetch();

$total_pages = ceil($total_rows[0]/$item_per_page);

//position of records
$page_position = (($page_number-1) * $item_per_page);

//Limit our results within a specified range. 
$results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
$results->execute(); //Execute prepared Query
// $results->bind_result($id, $name, $message); //bind variables to prepared statement

//Display records fetched from database.
echo '<table class="table table-striped contents" id="contents" border="1">';
echo '<div id="target">Click here</div>';
echo '<thead>
        <tr id="thStock">
          <th id="th1">ORDRE</th>
          <th>REFERENCE</th>
          <th>REFERENCE E-COMMERCE</th>
          <th>CATEGORIE</th>
          <th>CATEGORIE VITRINE</th>
          <th>UNITE STOCK</th>
          <th>UNITE DETAILS</th>
          <th>PRIX </th>
          <th>PRIX UNITE STOCK</th>
          <th>OPERATIONS</th>
        </tr>
      </thead>';
$i = ($page_number - 1) * $item_per_page +1;
while($r=$results->fetch()){ //fetch values
	echo '<tr>';
	echo  '<td>' .$i.'</td>';
	echo  '<td>JCaisse' .strtoupper($r['designationJcaisse']).'</td>';
	echo  '<td>' .strtoupper($r['designation']).'</td>';
	echo  '<td>' .strtoupper($r['categorie']).'</td>';
	echo  '<td>categorieVitrine' .strtoupper($r['categorieVitrine']).'</td>';
	echo  '<td>uniteStock' .strtoupper($r['uniteStock']).'</td>';
	echo  '<td>uniteDetails' .strtoupper($r['uniteDetails']).'</td>';
	echo  '<td>' .$r['prixuniteStock'].'</td>';
	echo  '<td>' .$r['prix'].'</td>';
  if ($r["image"]) {
    echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
      <a><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#app'.$r["id"].'" /></a>&nbsp;
      <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
    }
    else{
      echo  '<td><a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgsup'.$r["idDesignation"].'" /></a>&nbsp;
      <a><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_DesignationVT('.$r["id"].')" data-toggle="modal" data-target="#img'.$r["id"].'" /></a>&nbsp;
      <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$r["id"].')"  data-toggle="modal" data-target="#imgedit'.$r["idDesignation"].'" /></a></td>';
    }
	echo '</tr>';
  $i++;
}
echo '</table>';

echo '<div align="center">';
// To generate links, we call the pagination function here. 
echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages);
echo '</div>';

}

// $req2 = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0");
  // $req2->execute() or die(print_r($req2->errorInfo()));
  // if ($req2) {
  // $data=array();
  // $i=1;
  // while($design=$req2->fetch()){

  //   $rows = array();
  //   $rows[] = $i;
  //   $rows[] = strtoupper($design['designationJcaisse']);
  //   $rows[] = strtoupper($design['designation']);
  //   $rows[] = strtoupper($design['categorie']);
  //   $rows[] = strtoupper($design['categorieVitrine']);
  //   $rows[] = strtoupper($design['uniteStock']);
  //   $rows[] = strtoupper($design['uniteDetails']);
  //   $rows[] = $design['prixuniteStock'];
  //   $rows[] = $design['prix'];
  //   if ($design["image"]) {
  //     $rows[] = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$design["id"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>&nbsp;
  //     <a><img src="images/iconfinder11.png" align="middle" alt="apperçu" onclick="imgEX_DesignationVT('.$design["id"].')" data-toggle="modal" data-target="#app'.$design["id"].'" /></a>&nbsp;
  //     <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$design["id"].')"  data-toggle="modal" data-target="#imgedit'.$design["idDesignation"].'" /></a>';
  //   }
  //   else{
  //     $rows[] = '<a><img src="images/drop.png" align="middle" alt="supprimer" onclick="spm_DesignationVT('.$design["id"].')"  data-toggle="modal" data-target="#imgsup'.$design["idDesignation"].'" /></a>&nbsp;
  //     <a><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNV_DesignationVT('.$design["id"].')" data-toggle="modal" data-target="#img'.$design["id"].'" /></a>&nbsp;
  //     <a><img src="images/edit.png" align="middle" alt="modifier" onclick="edit_DesignationVT('.$design["id"].')"  data-toggle="modal" data-target="#imgedit'.$design["idDesignation"].'" /></a>';
  //   }

  //   $data[] = $rows;
  //   $i=$i + 1;
  // }

  // }

  // $results = ["sEcho" => 1,
  //           "iTotalRecords" => count($data),
  //           "iTotalDisplayRecords" => count($data),
  //           "aaData" => $data ];

  // echo json_encode($results);


  function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  {
      $pagination = '';
      if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination">';
          
          if ($current_page == 1) {
            # code...
            $pagination .= '<li class="page-item disabled"><a href="#" data-page="1" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>
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
            $pagination .= '<li class="page-item disabled"><a href="#" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
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
