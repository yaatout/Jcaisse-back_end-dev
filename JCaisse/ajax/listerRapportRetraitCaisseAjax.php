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

$idDesignation=@$_GET['id'];
$debut=@$_GET['debut'];
$fin=@$_GET['fin'];

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeService = @$_POST["nbEntreeService"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeService!=0) ? $nbEntreeService : 10; //item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();

  

  $data=array();
  $i=1;

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
    $sql="SELECT *
    FROM  `".$nomtableLigne."` l 
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    WHERE l.classe = 7 && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    
  } else {
    # code...
    $sql="SELECT *
    FROM  `".$nomtableLigne."` l 
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    WHERE l.classe = 7 && p.idClient=0  && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

  }
  
  //$data=array();

  while($stock=mysql_fetch_array($res)){

    $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
    $res1=mysql_query($sql1);
    $designation=mysql_fetch_array($res1);
  
    $sqlQ="SELECT quantite
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    where  l.classe = 7 && l.numligne='".$stock['numligne']."' && p.idClient=0 &&  p.verrouiller=1 && p.type=0 &&  CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
    $resQ=mysql_query($sqlQ) or die ("select stock impossible =>".mysql_error());
    $S_stock = mysql_fetch_array($resQ);
    
    $sqlS="SELECT prixtotal
    FROM `".$nomtableLigne."` l
    INNER JOIN `".$nomtableDesignation."` d ON d.idDesignation = l.idDesignation
    INNER JOIN `".$nomtablePagnet."` p ON p.idPagnet = l.idPagnet
    where  l.classe = 7 && l.numligne='".$stock['numligne']."' && p.idClient=0 &&  p.verrouiller=1 && p.type=0 &&  CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."'  ";
    $resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
    $prixTotal = mysql_fetch_array($resS);
  
    $date1 = strtotime($dateString); 
  
    if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
      $rows = array();
      if($i==1){
        $rows[] = $i;
        $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
        $rows[] = '<span style="color:blue;">'.$stock['prixtotal'].'</span>';
        $rows[] = '<span style="color:blue;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';
      }
      else if($dateString2==$stock['datepagej']){
        $rows[] = $i;
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$stock['prixtotal'].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';
      }
      else{
        $rows[] = $i;
        $rows[] = strtoupper($designation['designation']);
        $rows[] = $stock['prixtotal'];
        $rows[] =$stock['datepagej'].' '.$stock['heurePagnet'];
      }
    }
    else{
      $rows = array();
      if($i==1){
        $rows[] = $i;
        $rows[] = '<span style="color:blue;">'.strtoupper($designation['designation']).'</span>';
        $rows[] = '<span style="color:blue;">'.$stock['prixtotal'].'</span>';
        $rows[] = '<span style="color:blue;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';
      }
      else if($dateString2==$stock['datepagej']){
        $rows[] = $i;
        $rows[] = '<span style="color:#ffcc00;">'.strtoupper($designation['designation']).'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$stock['prixtotal'].'</span>';
        $rows[] = '<span style="color:#ffcc00;">'.$stock['datepagej'].' '.$stock['heurePagnet'].' </span>';
      }
      else{
        $rows[] = $i;
        $rows[] = strtoupper($designation['designation']);
        $rows[] = $stock['prixtotal'];
        $rows[] =$stock['datepagej'].' '.$stock['heurePagnet'];
      }
    }
  
    $data[] = $rows;
    $i=$i + 1;
   
  }

    //get total number of records 
    $total_rows = count($data);

    $total_pages = ceil($total_rows/$item_per_page);

    //position of records
    $page_position = (($page_number-1) * $item_per_page);

    // if ($tri==1) {
      // usort($produits, "cmp1");
      // var_dump($produits);
    // } else {

    //usort($produits, "cmp2");
      
    //Display records fetched from database.
    $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0;
      // $produits=array();
      $data = array_slice($data, $page_position, $item_per_page);
    
    echo '<table class="table table-striped contents"  border="1">';
        echo '<thead>
                <tr>
                  <th>ORDRE</th>
                  <th style="width: 18%;">REFERENCE</th>
                  <th>MONTANT</th>
                  <th>DATE RETRAIT</th>
              </tr>
                </tr>
              </thead>';

      
      // var_dump($produits);

      // $produits=array();
      foreach ($data as $tab) {
        // $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
        // $res1=mysql_query($sql1);
        // $design=mysql_fetch_array($res1);
          echo  '<tr>';    
            echo '<td><span>'.$tab[0].'</span></td>';
            echo '<td><span>'.$tab[1].'</span></td>';
            echo '<td><span>'.$tab[2].'</span></td>';
            echo '<td><span>'.$tab[3].'</span></td>';
          echo'</tr>';
          $i++;
          $cpt++;
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
      return $pagination; 
      //return pagination links 

}
