<?php
session_start();
if(!$_SESSION['iduserBack']){
	header('Location:../../index.php');
}

require('../../connectionPDO.php');
require('../../declarationVariables.php');
//require_once '../functions/salaire_functions.php';

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);

$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour ;
$dateString2=$jour.'-'.$mois.'-'.$annee ;


$operation=@$_POST['operation'];
if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntre = $_POST["nbEntreeRetardPers"];
  $query = $_POST["query"];
  $cb = $_POST["cb"];
  $item_per_page 		= ($nbEntre!=0) ? $nbEntre : 10; //item to display per page
  $page_number 		= 0; //page number
  
  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  } 
   
    //$total_a = recupererNombrePersonnel($bdd, $date, $profilPersonnel = 7);
    $profilPersonnel = 7;
    $stmt = $bdd->prepare("
                         SELECT COUNT(*) as total_row FROM `aaa-contrat` c
                             INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
                             INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
                             WHERE   p.profilPersonnel = :profil AND aPayer =  :ap 
                     ");
                     
    $stmt->execute([
            'ap' => 0,
            'profil' => $profilPersonnel
        ]);
    $total_a = $stmt->fetch();
    //var_dump($total_a);
    $total_rows = $total_a['total_row'];
    $total_pages = ceil($total_rows/$item_per_page);
      
      //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") {
        //$salaireTotal =recupererPersonnel($bdd, $date, 7,$page_position ,$item_per_page);
        
          $stmt = $bdd->prepare("SELECT * FROM `aaa-contrat` c
                             INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
                             INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
                             WHERE   p.profilPersonnel = :profil AND aPayer =  :ap  
                  ORDER BY idSP DESC  LIMIT $page_position ,$item_per_page ");

          $periodeActuelle = $date->format('Y-m');
          $stmt->execute([
            'ap' => 0,
            'profil' => $profilPersonnel
            ]);

          $salaireTotal = $stmt->fetchAll(PDO::FETCH_ASSOC);

      } else {
          //Limit our results within a specified range. 
          $stmt = $bdd->prepare("SELECT * FROM `aaa-contrat` c
                             INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
                             INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
                             WHERE   p.profilPersonnel = :profil AND aPayer =  :ap  
                  ORDER BY idSP DESC  LIMIT $page_position ,$item_per_page ");

          $periodeActuelle = $date->format('Y-m');
          $stmt->execute([
            'ap' => 0,
            'profil' => $profilPersonnel
            ]);

          $salaireTotal = $stmt->fetchAll(PDO::FETCH_ASSOC);              
      }
      //$salaireTotal = $req1->fetchAll();
      //Display records fetched from database.
      echo '<table class="table table-striped contents display tabDesign tableau3" border="1">';
      echo '<thead>
              <th>PERSONNEL</th>
              <th>Prénom</th>
              <th>Nom</th>
              <th>Salaire de base (FCFA)</th>
              <th>Avance 1 (FCFA)</th>
              <th>Avance 2 (FCFA)</th>
              <th>Restant (FCFA)</th>
              <th>Date Calcul</th>
              <th>Date Fin</th>
              <th>Etape</th>
              <th>Virement</th>
              <th>Opération</th>
            </thead>';
      $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0; 

        foreach ($salaireTotal as $salaire) {  ?>
            <tr>
                <td> 
                    <?php echo $salaire['matriculePersonnel']; ?>  </td>
                <td> <?php echo  $salaire['prenomPersonnel'];  ?>  </td>
                <td> <?php echo  $salaire['nomPersonnel'];   ?>  </td>
                <td> <?php echo  $salaire['salaireDeBase'];  ?>  </td>
                <td> <?php echo  $salaire['avanceSalaire1'];  ?>  </td>
                <td> <?php echo  $salaire['avanceSalaire2'];  ?>  </td>
                <td> <?php echo  $salaire['retenu'];  ?>  
                </td>
                <td> <?php echo  $salaire['dateCalcul'];  ?>  </td>
                <td> <?php echo  $salaire['dateFin'];  ?>  </td>
                <td> <?php echo  $salaire['etape']; ?> </td>
                  <?php 
                    if ($salaire['aPayer']==0) { ?>
                        <td><span>En cour...</span></td>
                <td>
                            <!-- <?php /* if ($jour>=25 && $jour<=15 ) { */ ?> -->
                            <button type="button" class="btn btn-success" class="btn btn-success" onclick="popVirSalPers('<?=$salaire['idContrat']?>','<?=$salaire['idPersonnel'] ?>','<?=$salaire['idSP'] ?>')" >
                                Virer
                            </button>
                            <button type="button" class="btn btn-success" class="btn btn-success"  onclick="popVirSalPersAvance('<?=$salaire['idContrat']?>','<?=$salaire['idPersonnel'] ?>','<?=$salaire['idSP'] ?>')"  >
                                Payer avance
                            </button>
                                <!-- <?php /* } else {  */?>
                                    <p>..</p>
                                  <?php /* } */  ?> -->
                                                 
                </td>
                        <?php
                    } else { ?>
                <td><span>Effectif</span></td>
                <td>
                            <!-- <button type="button" class="btn btn-danger" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#DesactiverI9999".$salaire['idContrat'] ; ?> >
                                      Annuler</button> 
                             -->
                </td>
                  <?php }
                ?>                                        
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
}?>