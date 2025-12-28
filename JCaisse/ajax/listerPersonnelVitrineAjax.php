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
// $produits=array();


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntree = @$_POST["nbEntreeUserVitrine"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page = ($nbEntree!=0) ? $nbEntree : 10; //item to display per page
  $page_number 		= 0; //page number
  $personnels = array();
  $tabIdUser = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  if ($query =="") {
    # code...
    $sql="SELECT * FROM `aaa-acces` a
    LEFT JOIN `aaa-utilisateur` u ON u.idutilisateur = a.idutilisateur 
    WHERE a.idBoutique =".$_SESSION['idBoutique']." AND visible=0 AND a.vitrine=1 ORDER BY u.idutilisateur DESC ";
    $res = mysql_query($sql) or die ("persoonel requête 4".mysql_error());

  } else {
    # code... 
    $sql="SELECT * FROM `aaa-acces` a
    LEFT JOIN `aaa-utilisateur` u ON u.idutilisateur = a.idutilisateur 
    WHERE a.idBoutique =".$_SESSION['idBoutique']." AND visible=0 AND a.vitrine=1 AND (u.nom LIKE '%".$query."%' or u.prenom LIKE '%".$query."%' or u.adresse LIKE '%".$query."%') ORDER BY u.idutilisateur DESC ";
    $res = mysql_query($sql) or die ("persoonel requête 4".mysql_error());
  }

  //$personnels=mysql_fetch_array($res);
  while($user=mysql_fetch_array($res)){
    if (in_array($user['idutilisateur'], $tabIdUser)) {
      # code...
    } else {
      # code...
      $sqlU="SELECT *
      FROM `aaa-utilisateur`  
      WHERE idutilisateur =".$user['idutilisateur']."";
      $resU = mysql_query($sqlU) or die ("persoonel requête 4".mysql_error());
      $utilisateur = mysql_fetch_array($resU);
      if($utilisateur!=null){
        $tabIdUser[]=$user['idutilisateur'];
        $personnels[]=$user;
      }

    }
    
  }
 
  //get total number of records 
  $total_rows = count($personnels);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  // var_dump(max($tabIdStock));

  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabStock" id="tableStock" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th>NOM</th>
            <th>PRENOM</th>
            <th>EMAIL</th>
            <th>TABLEAU DE BORD</th>
            <th>E COMMERCE</th>
            <th>COMMANDE</th>
            <th>CLIENT</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $personnels = array_slice($personnels, $page_position, $item_per_page);
 

  // $produits=array();
  foreach ($personnels as $acces) {

    $sqlU="SELECT *
    FROM `aaa-utilisateur`  
    WHERE idutilisateur =".$acces['idutilisateur'];
    $resU = mysql_query($sqlU) or die ("persoonel requête 4".mysql_error());
    $utilisateur = mysql_fetch_array($resU);

    echo '<tr>';
      echo  '<td>'.strtoupper($utilisateur['nom']).'</td>';
      echo  '<td>'.strtoupper($utilisateur['prenom']).'</td>';
      echo  '<td>'.strtoupper($utilisateur['email']).'</td>';
      if($acces['tableauBord']==1){
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" checked=""  name="proprietaire"></td>';
      }
      else{
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" name="proprietaire"></td>';
      }
      if($acces['ecommerce']==1){
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" checked=""  name="gerant"></td>';
      }
      else{
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" name="gerant"></td>';
      }
      if($acces['commande']==1){
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" checked=""  name="gestionnaire"></td>';
      }
      else{
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" name="gestionnaire"></td>';
      }
      if($acces['client']==1){
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" checked=""  name="caissier"></td>';
      }
      else{
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" name="caissier"></td>';
      }

        echo  '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_UserVitrine('.$utilisateur["idutilisateur"].')" data-toggle="modal" /></a></td>';
    
    echo '</tr>';


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

/*****  Tri **/
else if ($operation=="2") {
  # code...
  $nbEntree = @$_POST["nbEntreeUserVitrine"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page = ($nbEntree!=0) ? $nbEntree : 10;//item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdUser = array();
  $tabIdStock = array();

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }

  if ($query =="") {
    # code...
    $sql="SELECT * FROM `aaa-acces` a
    LEFT JOIN `aaa-utilisateur` u ON u.idutilisateur = a.idutilisateur 
    WHERE a.idBoutique =".$_SESSION['idBoutique']." AND visible=0 AND a.vitrine=1  ORDER BY u.idutilisateur DESC ";
    $res = mysql_query($sql) or die ("persoonel requête 4".mysql_error());

  } else {
    # code... 
    $sql="SELECT * FROM `aaa-acces` a
    LEFT JOIN `aaa-utilisateur` u ON u.idutilisateur = a.idutilisateur 
    WHERE a.idBoutique =".$_SESSION['idBoutique']." AND visible=0 AND a.vitrine=1 AND (u.nom LIKE '%".$query."%' or u.prenom LIKE '%".$query."%' or u.adresse LIKE '%".$query."%') ORDER BY u.idutilisateur DESC ";
    $res = mysql_query($sql) or die ("persoonel requête 4".mysql_error());
  }

  while($user=mysql_fetch_array($res)){
    if (in_array($user['idutilisateur'], $tabIdUser)) {
      # code...
    } else {
      # code...
      $sqlU="SELECT *
      FROM `aaa-utilisateur`  
      WHERE idutilisateur =".$user['idutilisateur']."";
      $resU = mysql_query($sqlU) or die ("persoonel requête 4".mysql_error());
      $utilisateur = mysql_fetch_array($resU);
      if($utilisateur!=null){
        $tabIdUser[]=$user['idutilisateur'];
        $personnels[]=$user;
      }

    }
    
  }

  //get total number of records 
  $total_rows = count($personnels);

  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  // var_dump(max($tabIdStock));

  //Display records fetched from database.
  echo '<table class="table table-striped contents display tabStock" id="tableStock" border="1">';
  echo '<thead>
          <tr id="thStock">
            <th>NOM</th>
            <th>PRENOM</th>
            <th>EMAIL</th>
            <th>TABLEAU DE BORD</th>
            <th>E COMMERCE</th>
            <th>COMMANDE</th>
            <th>CLIENT</th>
            <th>OPERATIONS</th>
          </tr>
        </thead>';
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;

  $personnels = array_slice($personnels, $page_position, $item_per_page);
  // var_dump($produits);

  // $produits=array();

  // $produits=array();
  foreach ($personnels as $acces) {

    $sqlU="SELECT *
    FROM `aaa-utilisateur`  
    WHERE idutilisateur =".$acces['idutilisateur'];
    $resU = mysql_query($sqlU) or die ("persoonel requête 4".mysql_error());
    $utilisateur = mysql_fetch_array($resU);

    echo '<tr>';
      echo  '<td>'.strtoupper($utilisateur['nom']).'</td>';
      echo  '<td>'.strtoupper($utilisateur['prenom']).'</td>';
      echo  '<td>'.strtoupper($utilisateur['email']).'</td>';
      if($acces['tableauBord']==1){
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" checked=""  name="proprietaire"></td>';
      }
      else{
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" name="proprietaire"></td>';
      }
      if($acces['ecommerce']==1){
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" checked=""  name="gerant"></td>';
      }
      else{
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" name="gerant"></td>';
      }
      if($acces['commande']==1){
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" checked=""  name="gestionnaire"></td>';
      }
      else{
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" name="gestionnaire"></td>';
      }
      if($acces['client']==1){
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" checked=""  name="caissier"></td>';
      }
      else{
        echo  '<td><input disabled=""  class="form-check-input" type="checkbox" name="caissier"></td>';
      }

        echo  '<td><a ><img src="images/edit.png" align="middle" alt="modifier" onclick="mdf_UserVitrine('.$utilisateur["idutilisateur"].')" data-toggle="modal" /></a></td>';
    
    echo '</tr>';

      $i++;
      $cpt++;
      // $produits[] = $designation['idDesignation'];
    // }
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