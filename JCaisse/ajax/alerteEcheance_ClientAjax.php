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

  $nbEntreeEcheanceCli = @$_POST["nbEntreeEcheanceCli"];
  $query = @$_POST["query"];
  //$cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeEcheanceCli!=0) ? $nbEntreeEcheanceCli : 10; //item to display per page
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
    
    $sql="SELECT COUNT(*) as total_row from `".$nomtablePagnet."` where idClient!=0  && verrouiller=1 && type=0";
    $res=mysql_query($sql);
    
  } else {
    # code...
    //get total number of records from database
      # code...
      
      $sql="SELECT COUNT(*) as total_row from `".$nomtablePagnet."` where idClient!=0  && verrouiller=1 && type=0 and (datepagej LIKE '%".$query."%') order by idPagnet desc";
      $res=mysql_query($sql);
      # code...
  }
  $total_rows = mysql_fetch_array($res);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);
  
  if ($query =="") {
    # code...;
    // $results = $bddV->prepare("SELECT * from `".$nomtablePagnet."` WHERE classe = 0 or classe = 1 ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
    $sql="SELECT * from `".$nomtablePagnet."` where idClient!=0  && verrouiller=1 && type=0 order by idPagnet desc  LIMIT $page_position, $item_per_page";
    $res=mysql_query($sql);
  } else {
    # code...
    //Limit our results within a specified range. 
      # code...
      $sql="select * from `".$nomtablePagnet."` where idClient!=0  && verrouiller=1 && type=0 and (datepagej LIKE '%".$query."%') order by idPagnet desc LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
   
    
    // $results = $bddV->prepare("SELECT * from `".$nomtablePagnet."` WHERE (classe = 0 or classe = 1) and designation LIKE '%".$query."%' ORDER BY designation ASC LIMIT $page_position, $item_per_page");
    // $results->execute(); //Execute prepared Query
  }

  //var_dump($sql);
  //var_dump($res);
  //Display records fetched from database.
  
    echo '<table class="table table-striped contents"  border="1">';
    echo '<thead>
            <tr>
              <th style="width: 15%;">Echeance</th>
              <th style="width: 15%;">Montant</th>
              <th style="width: 15%;">Facture</th>
              <th style="width: 18%;">Date</th>
              <th style="width: 18%;">Client</th>
            </tr>
          </thead>';

  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  // $produits=array();
  while($pagnet=mysql_fetch_array($res)){

    if($pagnet["dateecheance"]!=null && $pagnet["dateecheance"]!=''){
      
      echo  '<tr>';
    
          $sql1="SELECT * FROM `". $nomtableClient."` where idClient='".$pagnet['idClient']."'";
    
          $res1=mysql_query($sql1);
    
          $client = mysql_fetch_assoc($res1);
    
          // On transforme les 2 dates en timestamp
    
          $date1 = strtotime($dateString2);
    
          $date2 = strtotime($pagnet["dateecheance"]);
    
          // On récupère la différence de timestamp entre les 2 précédents
    
          $nbJoursTimestamp = $date2 - $date1; 
    
          // ** Pour convertir le timestamp (exprimé en secondes) en jours **
    
          // On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
    
          $nbJours = $nbJoursTimestamp/86400; // 86 400 = 60*60*24
    
          $rows = array();
    
          if($nbJours<0){
    
            echo  '<td><span style="color:#641E16;">'.$pagnet["dateecheance"].'</span></td>';
    
            echo  '<td><span style="color:#641E16;">'.$pagnet["totalp"].'</span></td>';
    
            echo  '<td><span style="color:#641E16;">#'.$pagnet["idPagnet"].'</span></td>';
    
            echo  '<td><span style="color:#641E16;">'.$nbJours.'</span></td>';
    
            echo  '<td><span style="color:#641E16;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
    
          }
    
          else if($nbJours>=0 && $nbJours<7){
    
            echo  '<td><span style="color:red;">'.$pagnet["dateecheance"].'</span></td>';
    
            echo  '<td><span style="color:red;">'.$pagnet["totalp"].'</span></td>';
    
            echo  '<td><span style="color:red;">#'.$pagnet["idPagnet"].'</span></td>';
    
            echo  '<td><span style="color:red;">'.$nbJours.'</span></td>';
    
            echo  '<td><span style="color:red;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
    
          }
    
          else if($nbJours>=7 && $nbJours<=14){
    
            echo  '<td><span style="color:orange;">'.$pagnet["dateecheance"].'</span></td>';
    
            echo  '<td><span style="color:orange;">'.$pagnet["totalp"].'</span></td>';
    
            echo  '<td><span style="color:orange;">#'.$pagnet["idPagnet"].'</span></td>';
    
            echo  '<td><span style="color:orange;">'.$nbJours.'</span></td>';
    
            echo  '<td><span style="color:orange;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
    
          }
    
          else {
    
            echo  '<td><span style="color:blue;">'.$pagnet["dateecheance"].'</span></td>';
    
            echo  '<td><span style="color:blue;">'.$pagnet["totalp"].'</span></td>';
    
            echo  '<td><span style="color:blue;">#'.$pagnet["idPagnet"].'</span></td>';
    
            echo  '<td><span style="color:blue;">'.$nbJours.'</span></td>';
    
            echo  '<td><span style="color:blue;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
    
          }
      echo  '</tr>';  
      $i++;
      $cpt++;
    }
    else{
      echo  '<tr>';
        //if($pagnet["dateecheance"]!=null && $pagnet["dateecheance"]!=''){
          $sql1="SELECT * FROM `". $nomtableClient."` where idClient='".$pagnet['idClient']."'";
          $res1=mysql_query($sql1);
          $client = mysql_fetch_assoc($res1);
          // On transforme les 2 dates en timestamp
          $date1 = strtotime($dateString2);
          $date2 = strtotime($pagnet["dateecheance"]);

          // On récupère la différence de timestamp entre les 2 précédents
          $nbJoursTimestamp = $date2 - $date1;
          
          // ** Pour convertir le timestamp (exprimé en secondes) en jours **
          // On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
          $nbJours = $nbJoursTimestamp/86400; // 86 400 = 60*60*24
          $rows = array();
          if($nbJours<0){
            echo  '<td><span style="color:#641E16;">'.$pagnet["dateecheance"].'</span></td>';
            echo  '<td><span style="color:#641E16;">'.$pagnet["totalp"].'</span></td>';
            echo  '<td><span style="color:#641E16;">#'.$pagnet["idPagnet"].'</span></td>';
            echo  '<td><span style="color:#641E16;">'.$nbJours.'</span></td>';
            echo  '<td><span style="color:#641E16;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
          }
          else if($nbJours>=0 && $nbJours<7){
            echo  '<td><span style="color:red;">'.$pagnet["dateecheance"].'</span></td>';
            echo  '<td><span style="color:red;">'.$pagnet["totalp"].'</span></td>';
            echo  '<td><span style="color:red;">#'.$pagnet["idPagnet"].'</span></td>';
            echo  '<td><span style="color:red;">'.$nbJours.'</span>';
            echo  '<td><span style="color:red;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
          }
          else if($nbJours>=7 && $nbJours<=14){
            echo  '<td><span style="color:orange;">'.$pagnet["dateecheance"].'</span></td>';
            echo  '<td><span style="color:orange;">'.$pagnet["totalp"].'</span></td>';
            echo  '<td><span style="color:orange;">#'.$pagnet["idPagnet"].'</span></td>';
            echo  '<td><span style="color:orange;">'.$nbJours.'</span>';
            echo  '<td><span style="color:orange;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
          }
          else {
            echo  '<td><span style="color:blue;">'.$pagnet["dateecheance"].'</span></td>';
            echo  '<td><span style="color:blue;">'.$pagnet["totalp"].'</span></td>';
            echo  '<td><span style="color:blue;">#'.$pagnet["idPagnet"].'</span></td>';
            echo  '<td><span style="color:blue;">'.$nbJours.'</span>';
            echo  '<td><span style="color:blue;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
          }
          //$data[] = $rows;
            
        //}
      echo  '</tr>';  
      $i++;
      $cpt++;
    }
    
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="6" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  echo '<div class="">';
    echo '<div class="col-md-4">';
      echo '<ul class="pull-left">';
        if ($item_per_page > $total_rows[0]) {
          # code...
            echo '1 à '.($total_rows[0]).' sur '.$total_rows[0].' entrées';        
        } else {
          # code...
          if ($page_number == 1) {
            # code...
            echo '1 à '.($item_per_page).' sur '.$total_rows[0].' entrées';
          } else {
            # code...
            if (($total_rows[0]-($item_per_page * $page_number)) < 0) {
              # code... 
              echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows[0].' sur '.$total_rows[0].' entrées';
            } else {
              # code...
              echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows[0].' entrées';
            }
          }
        }      
      echo '</ul>';
    echo '</div>';
    echo '<div class="col-md-8">';
    // To generate links, we call the pagination function here. 
    echo paginate_function($item_per_page, $page_number, $total_rows[0], $total_pages);
    echo '</div>';
  echo '';

}

/*****  Tri **/
else if ($operation=="2") {
  # code...
  $nbEntreeEcheanceCli = @$_POST["nbEntreeEcheanceCli"];
  $query = @$_POST["query"];
  $item_per_page 		= ($nbEntreeEcheanceCli!=0) ? $nbEntreeEcheanceCli : 10; //item to display per page
  $page_number 		= 0; //page number

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
    $sql0="select COUNT(*) from `".$nomtablePagnet."` where idClient!=0  && verrouiller=1 && type=0";
    $res0=mysql_query($sql0);
  } else {
    # code...
    //get total number of records from database 
    $sql0="select COUNT(*) from `".$nomtablePagnet."`";
    $res0=mysql_query($sql0);
  }
  $total_rows = mysql_fetch_array($res0);

  $total_pages = ceil($total_rows[0]/$item_per_page);

  //position of records
  $page_position = (($page_number-1) * $item_per_page);

  if ($tri==0) {
    if ($query =="") {
      # code... 
      $sql="select * from `".$nomtablePagnet."` where idClient!=0  && verrouiller=1 && type=0  order by dateecheance DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    } else {
      # code...
      //Limit our results within a specified range. 
      $sql="select * from `".$nomtablePagnet."`  and (dateecheance LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by dateecheance DESC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }
  } else {
    if ($query =="") {
      # code... 
      $sql="select * from `".$nomtablePagnet."`  order by dateecheance ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    } else {
      # code...
      //Limit our results within a specified range. 
      $sql="select * from `".$nomtablePagnet."`  and (dateecheance LIKE '%".$query."%' or codeBarreDesignation LIKE '%".$query."%') order by dateecheance ASC LIMIT $page_position, $item_per_page";
      $res=mysql_query($sql);
    }    
  }
  
  $i = ($page_number - 1) * $item_per_page +1;
  $cpt = 0;
  
  while($pagnet=mysql_fetch_array($res)){

    if($pagnet["dateecheance"]!=null && $pagnet["dateecheance"]!=''){
      
      echo  '<tr>';
    
          $sql1="SELECT * FROM `". $nomtableClient."` where idClient='".$pagnet['idClient']."'";
    
          $res1=mysql_query($sql1);
    
          $client = mysql_fetch_assoc($res1);
    
          // On transforme les 2 dates en timestamp
    
          $date1 = strtotime($dateString2);
    
          $date2 = strtotime($pagnet["dateecheance"]);
    
          // On récupère la différence de timestamp entre les 2 précédents
    
          $nbJoursTimestamp = $date2 - $date1; 
    
          // ** Pour convertir le timestamp (exprimé en secondes) en jours **
    
          // On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
    
          $nbJours = $nbJoursTimestamp/86400; // 86 400 = 60*60*24
    
          $rows = array();
    
          if($nbJours<0){
    
            echo  '<td><span style="color:#FFA07A;">'.$pagnet["dateecheance"].'</span></td>';
    
            echo  '<td><span style="color:#FFA07A;">'.$pagnet["totalp"].'</span></td>';
    
            echo  '<td><span style="color:#FFA07A;">#'.$pagnet["idPagnet"].'</span></td>';
    
            echo  '<td><span style="color:#FFA07A;">'.$nbJours.'</span></td>';
    
            echo  '<td><span style="color:#FFA07A;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
    
          }
    
          else if($nbJours>=0 && $nbJours<7){
    
            echo  '<td><span style="color:red;">'.$pagnet["dateecheance"].'</span></td>';
    
            echo  '<td><span style="color:red;">'.$pagnet["totalp"].'</span></td>';
    
            echo  '<td><span style="color:red;">#'.$pagnet["idPagnet"].'</span></td>';
    
            echo  '<td><span style="color:red;">'.$nbJours.'</span></td>';
    
            echo  '<td><span style="color:red;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
    
          }
    
          else if($nbJours>=7 && $nbJours<=14){
    
            echo  '<td><span style="color:orange;">'.$pagnet["dateecheance"].'</span></td>';
    
            echo  '<td><span style="color:orange;">'.$pagnet["totalp"].'</span></td>';
    
            echo  '<td><span style="color:orange;">#'.$pagnet["idPagnet"].'</span></td>';
    
            echo  '<td><span style="color:orange;">'.$nbJours.'</span></td>';
    
            echo  '<td><span style="color:orange;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
    
          }
    
          else {
    
            echo  '<td><span style="color:blue;">'.$pagnet["dateecheance"].'</span></td>';
    
            echo  '<td><span style="color:blue;">'.$pagnet["totalp"].'</span></td>';
    
            echo  '<td><span style="color:blue;">#'.$pagnet["idPagnet"].'</span></td>';
    
            echo  '<td><span style="color:blue;">'.$nbJours.'</span></td>';
    
            echo  '<td><span style="color:blue;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
    
          }
      echo  '</tr>';  
      $i++;
      $cpt++;
    }
    else{
      echo  '<tr>';
        //if($pagnet["dateecheance"]!=null && $pagnet["dateecheance"]!=''){
          $sql1="SELECT * FROM `". $nomtableClient."` where idClient='".$pagnet['idClient']."'";
          $res1=mysql_query($sql1);
          $client = mysql_fetch_assoc($res1);
          // On transforme les 2 dates en timestamp
          $date1 = strtotime($dateString2);
          $date2 = strtotime($pagnet["dateecheance"]);

          // On récupère la différence de timestamp entre les 2 précédents
          $nbJoursTimestamp = $date2 - $date1;
          
          // ** Pour convertir le timestamp (exprimé en secondes) en jours **
          // On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
          $nbJours = $nbJoursTimestamp/86400; // 86 400 = 60*60*24
          $rows = array();
          if($nbJours<0){
            echo  '<td><span style="color:#FFA07A;">'.$pagnet["dateecheance"].'</span></td>';
            echo  '<td><span style="color:#FFA07A;">'.$pagnet["totalp"].'</span></td>';
            echo  '<td><span style="color:#FFA07A;">#'.$pagnet["idPagnet"].'</span></td>';
            echo  '<td><span style="color:#FFA07A;">'.$nbJours.'</span></td>';
            echo  '<td><span style="color:#FFA07A;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
          }
          else if($nbJours>=0 && $nbJours<7){
            echo  '<td><span style="color:red;">'.$pagnet["dateecheance"].'</span></td>';
            echo  '<td><span style="color:red;">'.$pagnet["totalp"].'</span></td>';
            echo  '<td><span style="color:red;">#'.$pagnet["idPagnet"].'</span></td>';
            echo  '<td><span style="color:red;">'.$nbJours.'</span>';
            echo  '<td><span style="color:red;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
          }
          else if($nbJours>=7 && $nbJours<=14){
            echo  '<td><span style="color:orange;">'.$pagnet["dateecheance"].'</span></td>';
            echo  '<td><span style="color:orange;">'.$pagnet["totalp"].'</span></td>';
            echo  '<td><span style="color:orange;">#'.$pagnet["idPagnet"].'</span></td>';
            echo  '<td><span style="color:orange;">'.$nbJours.'</span>';
            echo  '<td><span style="color:orange;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
          }
          else {
            echo  '<td><span style="color:blue;">'.$pagnet["dateecheance"].'</span></td>';
            echo  '<td><span style="color:blue;">'.$pagnet["totalp"].'</span></td>';
            echo  '<td><span style="color:blue;">#'.$pagnet["idPagnet"].'</span></td>';
            echo  '<td><span style="color:blue;">'.$nbJours.'</span>';
            echo  '<td><span style="color:blue;">'.$client["nom"].' '.$client["prenom"].'</span></td>';
          }
          //$data[] = $rows;
            
        //}
      echo  '</tr>';  
      $i++;
      $cpt++;
    }
    
  }
  if ($cpt==0) {
    # code...
    echo '<tr>';
    echo  '<td colspan="6" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';
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
