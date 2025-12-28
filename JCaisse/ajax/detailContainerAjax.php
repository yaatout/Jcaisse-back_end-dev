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

$operation=@htmlspecialchars($_POST["operation"]);
$numContainer=@htmlspecialchars($_POST["numContainer"]);
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeContainer = @$_POST["nbEntreeContainer"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= $nbEntreeContainer; //item to display per page
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

    $sql="SELECT * FROM `".$nomtablePagnet."` p, `".$nomtableClient."` c where p.idClient=c.idClient and p.numContainer='".$numContainer."' and p.type=0 and c.archiver=0";
    
    $statement = $bdd->prepare($sql);

  } else {
    # code...
    //get total number of records from database
      # code...

    $sql="SELECT * FROM `".$nomtablePagnet."` p, `".$nomtableClient."` c where p.idClient=c.idClient and p.numContainer='".$numContainer."' and p.type=0 and c.archiver=0 and (c.prenom LIKE '%".$query."%' or c.nom LIKE '%".$query."%' or c.telephone LIKE '%".$query."%')";
    
    $statement = $bdd->prepare($sql);
  }
   
//   var_dump($sql);

  $statement->execute();
    
  $data = $statement->fetchAll(PDO::FETCH_ASSOC); 

//   var_dump($data);
//   die();

  $total_rows = sizeof($data);
  $total_pages = ceil($total_rows/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  

  //Display records fetched from database.
  echo '<table class="table table-striped contents display tableau3" id="tablePrix" border="1">';
  echo '<thead>
          <tr>
            <th>Ordre</th>
            <th>Nom complèt</th>
            <th>Téléphone</th>
            <th>CBM (m³)</th>
            <th>CBM (FCFA)</th>
            <th>BAL (FCFA)</th>
            <th>Total CBM+BAL (FCFA)</th>
            <th>Opération</th>
          </tr>
        </thead>';

$data = array_slice($data, $page_position, $item_per_page);
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
foreach ($data as $key) {
    $cbm=0;
    $bal=0;

    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$key['idPagnet'];
    
    $statement = $bdd->prepare($sql);
    $statement->execute();
      
    $lignes = $statement->fetchAll(PDO::FETCH_ASSOC); 

    foreach ($lignes as $l) {
        if (strtolower($l['designation'] == 'cbm')) {
            $cbm = $l['quantite'];
        }
        else if (strtolower($l['designation']) == 'bal') {
            $bal = $l['prixunitevente'];
        }
    }

    echo '<tr>';
    if ($i==1) {
      # code...
      echo  '<td><span style="color:blue;">'.$i.'</span></td>';
      echo  '<td><span style="color:blue;">'.$key['prenom'].' '.$key['nom'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$key['telephone'].'</span></td>';
      echo  '<td><span style="color:blue;">'.$cbm.'</span></td>';
      echo  '<td><span style="color:blue;">'.($cbm*127000).'</span></td>';
      echo  '<td><span style="color:blue;">'.number_format($bal,0,""," ").'</span></td>';
      echo  '<td><span style="color:blue;">'.number_format((($cbm*127000)+$bal),0,""," ").'</span></td>';
    } else {
      # code...
      echo  '<td>'.$i.'</td>';
      echo  '<td>'.$key['prenom'].' '.$key['nom'].'</td>';
      echo  '<td>'.$key['telephone'].'</td>';
      echo  '<td>'.$cbm.'</td>';
      echo  '<td>'.($cbm*127000).'</td>';
      echo  '<td>'.number_format($bal,0,""," ").'</td>';
      echo  '<td>'.number_format((($cbm*127000)+$bal),0,""," ").'</td>';
    }
    echo  '<td> 
            <a class="btn btn-xs btn-success" target="_blank" href="bonPclient.php?c='.$key['idClient'].'"><span class="glyphicon glyphicon-plus"></span> Voir le client</a>
          </td>';
    echo '</tr>';

    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="8" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  if ($cpt > 0) {
    echo '<div class="">';
        echo '<div class="col-md-4">';
        echo '<ul class="pull-left">';
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
    }
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


?>
