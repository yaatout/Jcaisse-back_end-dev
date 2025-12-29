<?php

session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}
require('../../connectionPDO.php');


$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
$dateString2=$jour.'-'.$mois.'-'.$annee ;

$moiM=$mois-1;
        $anneeM=$annee;
        if($moiM<10){
            $moiM='0'.$moiM;
            if($mois=='01'){
                $moiM=12;
                $anneeM=$annee-1;
                $anneeM="$anneeM";
            }
        }

$debut=@$_GET['debut'];
$fin=@$_GET['fin'];
$operation=@$_POST['operation'];
/* $fin = new DateTime($debut);
$annee =$fin->format('Y');
$mois =$fin->format('m');
$finP=$debut; */
//$operation=@htmlspecialchars($_POST["operation"]);
$tri=@htmlspecialchars($_POST["tri"]);
$nbrMois=1;
$dateInit=$annee.'-'.$mois.'-'.'01';
$finMois = date('Y-m-d', strtotime($dateInit.' + '.$nbrMois.' month'));
            //Pour se positionner sur le dernier jour du mois
$finMois= date( 'Y-m-d', strtotime( $finMois . '-1 day') );

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntrePlusMois = @$_POST["nbEntrePlusMois"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntrePlusMois!=0) ? $nbEntrePlusMois : 10; //item to display per page
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
  $req0 = $bdd->prepare("SELECT  COUNT(*) as total_row  FROM `aaa-payement-pmois` WHERE dateFin>=:finMois  ORDER BY idPPM DESC "); 
  $req0->execute(array('finMois' =>$finMois))  or die(print_r($req0->errorInfo())); 
} else {
  //get total number of records from database
    $req0 = $bdd->prepare("SELECT  COUNT(*) as total_row  FROM `aaa-payement-pmois` WHERE dateFin>=:finMois "); 
    $req0->execute(array('finMois' =>$finMois))  or die(print_r($req0->errorInfo())); 
  
}

  $total_a = $req0->fetch();
  $total_rows = $total_a[0];
  
  $total_pages = ceil($total_rows/$item_per_page);
  // var_dump($finMois);
  // var_dump($total_rows);
  
  // var_dump($item_per_page);
  // var_dump($total_pages);
      //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") {
        $req1 = $bdd->prepare("SELECT  * FROM `aaa-payement-pmois` WHERE dateFin>=:finMois "); 
        $req1->execute(array('finMois' =>$finMois))  or die(print_r($req1->errorInfo()));         
      } else {
        //Limit our results within a specified range.         
        $req1 = $bdd->prepare("SELECT  * FROM `aaa-payement-pmois` WHERE dateFin>=:finMois "); 
        $req1->execute(array('finMois' =>$finMois))  or die(print_r($req1->errorInfo())); 
                 
      }

      $payementTotal=$req1->fetchAll();

      //Display records fetched from database.
      echo '<table class="table table-striped contents display tabDesign tableau3" border="1">';
      echo '<thead>
              <tr id="">
              <th>Boutique</th>
              <th>Montant mensuel</th>
              <th>Montant Total</th>
              <th>Date paiement multiple</th>
              <th>Description</th>
              <th>Date debut paiement</th>
              <th>Date fin de paiement</th>
              <th>Nombre de mois payé</th>
              <th>Nombre de mois avancé</th>
              <th>Nombre de mois restant</th>
              </tr>
            </thead>';
      $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0; 

      foreach ($payementTotal as $pr) {  
                                        $dateToday  = $date;
                                            $debutDate  = new DateTime($pr['dateDebut']);
                                            $finDate = new DateTime($pr['dateFin']);

                                            $firstDateRestant = $pr['dateDebut'];
                                            $secondDateRestant = $pr['dateFin'];

                                            $dateDifferenceRestant = abs(strtotime($secondDateRestant) - strtotime($firstDateRestant));

                                            $yearsRestant  = floor($dateDifferenceRestant / (365 * 60 * 60 * 24));
                                            $monthsRestant = floor(($dateDifferenceRestant - $yearsRestant * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                            $daysRestant   = floor(($dateDifferenceRestant - $yearsRestant * 365 * 60 * 60 * 24 - $monthsRestant * 30 * 60 * 60 *24) / (60 * 60 * 24));
                                            $valeur=$yearsRestant*12+$monthsRestant;
                                            if ($valeur<0) {
                                                continue;
                                            }
                                            ?>
                                            <tr>                                          
                                                <td> <b><?php 
                                                        $sql3 = $bdd->prepare("SELECT * FROM `aaa-boutique` WHERE idBoutique=:i"); 
                                                        $sql3->execute(array('i' =>$pr['idBoutique']))  or die(print_r($req->errorInfo()));                                                         
                                                        if ($boutique3 =$sql3->fetch())
                                                            echo  $boutique3['labelB'];  ?>   </b>   </td>
                                                            <td> <b><?php echo  $pr['montantMensuel']; ?>   </b>   </td>
                                                            <td> <b><?php echo  $pr['montantTotal']; ?>   </b>   </td>
                                                            <td> <?php echo  $pr['datePaiement']; ?>  </td>
                                                            <td> <?php echo  $pr['description']; ?>  </td>
                                                            <td> <?php echo  $pr['dateDebut']; ?>  </td>
                                                            <td> <?php echo  $pr['dateFin']; ?>  </td>
                                                            <td> <?php echo  $pr['nombreMois']; ?>  </td>
                                                            <td> <span style="color:green;">
                                                    <?php /*
                                                        $start = new DateTime('2020-01-01 00:00:00');
                                                        $end = new DateTime('2021-03-15 00:00:00');
                                                        $diff = $start->diff($end);
                                                        $yearsInMonths = $diff->format('%r%y') * 12;
                                                        $months = $diff->format('%r%m');
                                                        $totalMonths = $yearsInMonths + $months;*/


                                                        //$d1=$dateToday->diff($debutDate);
                                                        
                                                        
                                                        $diff =$debutDate->diff($dateToday);
                                                        //var_dump($diff);
                                                        $yearsInMonths = $diff->format('%r%y') * 12;
                                                        $months = $diff->format('%r%m');
                                                        $totalMonths = $yearsInMonths + $months;
                                                        
                                                        //var_dump($totalMonths);
                                                        if ($totalMonths<0) {
                                                          echo "0";
                                                        } else {
                                                          echo $totalMonths;
                                                        }
                                                        
                                                      ?> 
                                                      </span> 
                                                </td>
                                                <td> <span style="color:red;"><?php 
                                                        $restant=$pr['nombreMois']- $totalMonths;
                                                        echo $restant;
                                                    ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-success" onclick="imprimerPPM(<?php echo $pr['idPPM']?>)" >Facture</button>
                                                </td>
                                            </tr>
                                    <?php
              
          $i++;
          $cpt++;
      } 
    if ($cpt==0) {
      echo '<tr>';
      echo  '<td colspan="9" align="center">Données introuvables!</td>';
      echo '</tr>';
    }
    echo '</table>';
    echo '<div class="">';
      echo '<div class="col-md-4">';
        echo '<ul class="pull-left">';
          if ($item_per_page > $total_rows) {
              echo '1 à '.($total_rows).' sur '.$total_rows.' articles';        
          } else {
            if ($page_number == 1) {
              echo '1 à '.($item_per_page).' sur '.$total_rows.' articles';
            } else {
              if (($total_rows-($item_per_page * $page_number)) < 0) {
                echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' articles';
              } else {
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

?>
