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

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
$dateString2=$jour.'-'.$mois.'-'.$annee ;

$debut=@$_GET['debut'];
  $fin=@$_GET['fin'];
  // var_dump($debut);
  // echo $debut;
  // var_dump($fin);
  // echo $fin;

$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreeSVenteParPagnet = @$_POST["nbEntreeSVenteParPagnet"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreeSVenteParPagnet!=0) ? $nbEntreeSVenteParPagnet : 10; //item to display per page
  $page_number 		= 0; //page number
  $produits = array();
  $tabIdDesigantion = array();
  $tabIdStock = array();
  $ventes = array();
  

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
      $sth = $bdd->prepare("SELECT * FROM  `".$nomtablePagnet."` p
    
        WHERE p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ");

    // $sql="SELECT * FROM  `".$nomtablePagnet."` p
    
    // WHERE p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."' ORDER BY p.idPagnet DESC ";
    
    // $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    
  } else {
    # code...
    //get total number of records from database
    $sth = $dbh->prepare("SELECT * FROM  `".$nomtablePagnet."` p

    WHERE p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) 
    BETWEEN '".$debut."' AND '".$fin."' and (p.datepagej LIKE '%".$query."%' OR p.totalp LIKE '%".$query."%') ORDER BY p.idPagnet DESC");

    // $sql="SELECT * FROM  `".$nomtablePagnet."` p

    // WHERE p.idClient=0 && p.verrouiller=1 && p.type=0 && CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) 
    // BETWEEN '".$debut."' AND '".$fin."' and (p.datepagej LIKE '%".$query."%' OR p.totalp LIKE '%".$query."%') ORDER BY p.idPagnet DESC ";

    // $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());

  }
  $sth->execute();

  //$data=array();
  //$paniers=mysql_fetch_assoc($res);

  // var_dump($sth->fetchAll());

  $results = $sth->fetchAll();
  
  $exportFile=$results;

    //get total number of records 
    $total_rows = count($results);

    $total_pages = ceil($total_rows/$item_per_page);

    //position of records
    $page_position = (($page_number-1) * $item_per_page);

      
    //Display records fetched from database.
    $i = ($page_number - 1) * $item_per_page +1;
    $cpt = 0;
    // $produits=array();
    $data = array_slice($results, $page_position, $item_per_page);
    
    echo '<table class="table table-striped contents"  border="1">';
        echo '<thead>
                <tr>
                  <th>ORDRE</th>
                  <th>DATE</th>
                  <th>TOTAL PANIERS</th>
                  <th>TOTAL PRODUITS</th>
                  <th>TOTAL SERVICES</th>
                  <th>REMISE</th>
                  <th>OPERATIONS</th>
                </tr>
              </thead>';

  // while($p=$sth->fetch()){

  foreach ($data as $p) {

    // $sth = $dbh->prepare("SELECT *
  
    //     FROM  `".$nomtableLigne."` l
      
    //     WHERE l.classe = 0 && l.idPagnet = ".$p["idPagnet"]." ORDER BY l.numligne DESC");

    $sql2="SELECT *
  
    FROM  `".$nomtableLigne."` l
  
    WHERE l.classe = 0 && l.idPagnet = ".$p["idPagnet"]." ORDER BY l.numligne DESC ";
  
    $res2 = mysql_query($sql2) or die ("persoonel requête 2".mysql_error());
  
    $l=mysql_fetch_array($res2);
  
    // $sqlN="select * from `".$nomtableDesignation."` where idDesignation='".$stock["idDesignation"]."'";
  
    // $resN=mysql_query($sqlN);
  
    // $designation = mysql_fetch_array($resN);
  
  
  
    // while($l=mysql_fetch_array($res2)){
  
    if (empty($l)) {
  
      // code...
  
    }else {
  
      $rows = array();
  
      $service=$p['totalp'] - ($l['prixtotal'] + $p['remise']);
      echo '<tr>';
      if($i==1){
    
        echo  '<td>'.$i.'</td>';
  
        echo  '<td><span style="color:blue;">'.$p['datepagej'].' '.$p['heurePagnet'].'</span></td>';
  
        echo  '<td><span style="color:blue;">'.$p['totalp'].'</span></td>';
  
        echo  '<td><span style="color:blue;">'.$l['prixtotal'].'</span></td>';
  
        echo  '<td><span style="color:blue;">'.$service.'</span></td>';
  
        echo  '<td><span style="color:blue;">'.$p['remise'].'</span></td>';
  
      }
  
      else if($dateString2==$p['datepagej']){
  
        echo  '<td>'.$i.'</td>';
  
        echo  '<td><span style="color:#ffcc00;">'.$p['datepagej'].' '.$p['heurePagnet'].'</span></td>';
  
        echo  '<td><span style="color:#ffcc00;">'.$p['totalp'].'</span></td>';
  
        echo  '<td><span style="color:#ffcc00;">'.$l['prixtotal'].'</span></td>';
  
        echo  '<td><span style="color:#ffcc00;">'.$service.'</span></td>';
  
        echo  '<td><span style="color:#ffcc00;">'.$p['remise'].'</span></td>';
  
      }
  
      else{
  
        echo  '<td>'.$i.'</td>';
  
        echo  '<td>'.$p['datepagej'].' '.$p['heurePagnet'].'</td>';
  
        echo  '<td>'.$p['totalp'].'</td>';
  
        echo  '<td>'.$l['prixtotal'].'</td>';
  
        echo  '<td>'.$service.'</td>';
  
        echo  '<td>'.$p['remise'].'</td>';
  
      }
  
      $db = explode("-", $debut);
  
      $date_debut=$db[0].''.$db[1].''.$db[2];
  
      $df = explode("-", $fin);
  
      $date_fin=$df[0].''.$df[1].''.$df[2];
  
      echo  '<td><button  type="button" onclick="rapport_panier('.$p['idPagnet'].','.$date_debut.','.$date_fin.')" class="btn btn-primary" >
  
      <i class="glyphicon glyphicon-transfer"></i> Details
  
      </button></td>';
      echo '</tr>';

      // $p['service'] = $service;

      // $p['prixtotal'] = $l['prixtotal'];

      // $ventes[] = $p;

      // $data[] = $rows;
  
      $i=$i + 1;
      $cpt++;
  
      // $idPagnetTab[] = $p['idPagnet'];
  
    }
  
  }

  //var_dump($ventes);
  //echo $data[33][0];


      
      // var_dump($produits);

      // $produits=array();
      // foreach ($data as $tab) {
      //   // $sql1="SELECT * FROM `". $nomtableDesignation."` where idDesignation='".$stock['idDesignation']."'";
      //   // $res1=mysql_query($sql1);
      //   // $design=mysql_fetch_array($res1);
      //     echo  '<tr>';    
      //       echo '<td><span>'.$tab[0].'</span></td>';
      //       echo '<td><span>'.$tab[1].'</span></td>';
      //       echo '<td><span>'.$tab[2].'</span></td>';
      //       echo '<td><span>'.$tab[3].'</span></td>';
      //       echo '<td><span>'.$tab[4].'</span></td>';
      //       echo '<td><span>'.$tab[5].'</span></td>';
      //       echo '<td><span>'.$tab[6].'</span></td>';
      //     echo'</tr>';
      //     $i++;
      //     $cpt++;
      // }  
        
      
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
