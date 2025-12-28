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

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreePrix = @$_POST["nbEntreePrix"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= $nbEntreePrix; //item to display per page
  $page_number 		= 0; //page number

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
    $sql="select COUNT(DISTINCT(prix)) as total_row from `".$nomtableDesignation."` where classe=0";
    $res=mysql_query($sql);
  } else {
    # code...
    //get total number of records from database
      # code...
      $sql="select COUNT(DISTINCT(prix)) as total_row from `".$nomtableDesignation."` where classe=0 and (prix LIKE '%".$query."%')";
      $res=mysql_query($sql);
  }

  $total_rows = mysql_fetch_array($res);
  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code...
    $sql="select prix, COUNT(*) as nombreFois from `".$nomtableDesignation."` where classe=0 GROUP BY prix ORDER BY prix ASC LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
  } else {
    # code...
    //Limit our results within a specified range. 
      # code...
      $sql="select prix, COUNT(*) as nombreFois from `".$nomtableDesignation."` where classe=0 and (prix LIKE '%".$query."%') GROUP BY prix ORDER BY prix ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    
    // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
  }

  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabPrix tableau3" id="tablePrix" border="1">';
  echo '<thead>
          <tr id="thDesignation">
            <th>PRIX</th>
            <th>NOMBRE</th>
            <th>Operations</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  while($design=mysql_fetch_array($res)){ //fetch values
      
    echo '<tr>';
    if ($i==1) {
      # code...
      echo  '<td><span style="color:blue;">'.$design['prix'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$design['nombreFois'].'</span></td>';
    } else {
      # code...
      echo  '<td>'.$design['prix'].'</td>';
      echo  '<td>'.$design['nombreFois'].'</td>';;
    }
    echo  '<td style="width:25%">          
              <form action="etiquette.php" method="post" target="_blank">
                <input type="hidden" name="prixUnitaire" value="'.$design['prix'].'">
                <input type="hidden" name="nombreFois" value="'.$design['nombreFois'].'">
                <button type="submit" class="btn btn-primary" onclick="imprimer_Prix('.$design["prix"].','.$design['nombreFois'].')">
                <span style="color:white" class="glyphicon glyphicon-print"></span> Imprimer
                </button>
              </form>
          </td>';

    echo '</tr>';

    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="3" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
          # code...
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            # code...
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows[0].' articles';
            }
          }
        }      
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages);
    echo '</div>';
  echo '</div>';

}

/*****  Tri **/
if ($operation=="2") {
  # code...
  $nbEntreePrix = @$_POST["nbEntreePrix"];
  $query = @$_POST["query"];
  $item_per_page 		= $nbEntreePrix; //item to display per page
  $page_number 		= 0; //page number
  $tabIdDesigantion = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }

  //get total number of records from database
  
  if ($query =="") {
    # code...
    $sql="select COUNT(DISTINCT(prix)) as total_row from `".$nomtableDesignation."` where classe=0";
    $res=mysql_query($sql);
  } else {
    # code...
    //get total number of records from database
      # code...
      $sql="select COUNT(DISTINCT(prix)) as total_row from `".$nomtableDesignation."` where classe=0 and (prix LIKE '%".$query."%')";
      $res=mysql_query($sql);
  }

  $total_rows = mysql_fetch_array($res);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($tri==0) {
    if ($query =="") {
      # code...
      $sql="select prix, COUNT(*) as nombreFois from `".$nomtableDesignation."` where classe=0 GROUP BY prix ORDER BY prix DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
    } else {
      # code...
      //Limit our results within a specified range. 
        # code...
        $sql="select prix, COUNT(*) as nombreFois from `".$nomtableDesignation."` where classe=0 and (prix LIKE '%".$query."%') GROUP BY prix ORDER BY prix DESC LIMIT $page_position, $item_per_page";
        $res=mysql_query($sql);
      
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
    }
    
  } else {
    if ($query =="") {
      # code...
      $sql="select prix, COUNT(*) as nombreFois from `".$nomtableDesignation."` where classe=0 GROUP BY prix ORDER BY prix ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
    } else {
      # code...
      //Limit our results within a specified range. 
        # code...
        $sql="select prix, COUNT(*) as nombreFois from `".$nomtableDesignation."` where classe=0 and (prix LIKE '%".$query."%') GROUP BY prix ORDER BY prix ASC LIMIT $page_position, $item_per_page";
        $res=mysql_query($sql);
      
      // $results = $bddV->prepare("SELECT * from `".$nomtableDesignation."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
      // $results->execute(); //Execute prepared Query
    }
  }
  
  // var_dump($tabIdDesigantion);
  // $results->bind_result($id, $name, $message); //bind variables to prepared statement

   //Display records fetched from database.
   echo '<table class="table table-striped contents display tabPrix tableau3" id="tablePrix" border="1">';
   echo '<thead>
           <tr id="thDesignation">
             <th>PRIX</th>
             <th>NOMBRE</th>
             <th>Operations</th>
           </tr>
         </thead>';
   $i = ($page_number - 1) * $item_per_page +1;
   $cpt = 0;
   while($design=mysql_fetch_array($res)){ //fetch values
             
     echo '<tr>';
     if ($i==1) {
       # code...
       echo  '<td><span style="color:blue;">'.$design['prix'].'</span></td>';
       echo  '<td><span style="color:blue;">'.$design['nombreFois'].'</span></td>';
     } else {
       # code...
       echo  '<td>'.$design['prix'].'</td>';
       echo  '<td>'.$design['nombreFois'].'</td>';;
     }
     echo  '<td style="width:25%">          
              <form action="etiquette.php" method="post" target="_blank">
                <input type="hidden" name="prixUnitaire" value="'.$design['prix'].'">
                <input type="hidden" name="nombreFois" value="'.$design['nombreFois'].'">
                <button type="submit" class="btn btn-primary" onclick="imprimer_Prix('.$design["prix"].','.$design['nombreFois'].')">
                <span style="color:white" class="glyphicon glyphicon-print"></span> Imprimer
                </button>
              </form>
            </td>';
 
     echo '</tr>';
 
     $i++;
     $cpt++;
   }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="3" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
          # code...
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' articles';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' articles';
          } else {
            # code...
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' articles';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows[0].' articles';
            }
          }
        }  
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages);
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
