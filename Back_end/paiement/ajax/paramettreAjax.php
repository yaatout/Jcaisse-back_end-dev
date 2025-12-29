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

$operation=@$_POST['operation'];

$tri=@htmlspecialchars($_POST["tri"]);

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreParam = @$_POST["nbEntreParam"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreParam!=0) ? $nbEntreParam : 10; //item to display per page
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
  $sqla = $bdd->prepare("SELECT COUNT(*) as total_row FROM `aaa-variablespayement` ");  
} else {
  //get total number of records from database
  $sqla = $bdd->prepare("SELECT COUNT(*) as total_row FROM `aaa-variablespayement` where  nomvariable LIKE '%".$query."%' ");    
}

    
    $sqla->execute();  
    $total_a = $sqla->fetch();


  $total_rows = $total_a['total_row'];
  $total_pages = ceil($total_rows/$item_per_page);
      
      //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") {
        $req1 = $bdd->prepare("SELECT * FROM `aaa-variablespayement` ORDER BY `nomvariable` ASC  LIMIT $page_position ,$item_per_page"); 
        $req1->execute()  or die(print_r($req1->errorInfo())); 

        /* $req2 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=:p
            and (`datePS` LIKE '%".$annee."-".$mois."%') LIMIT $page_position ,$item_per_page"); 
        $req2->execute(array('p' =>1))  or die(print_r($req2->errorInfo()));  */
        
      } else {
        //Limit our results within a specified range.         
            $req1 = $bdd->prepare("SELECT * FROM `aaa-variablespayement` WHERE 
                 nomvariable LIKE '%".$query."%'  ORDER BY `nomvariable` ASC LIMIT $page_position ,$item_per_page"); 
            $req1->execute()  or die(print_r($req1->errorInfo())); 

            /* $req2 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=:p 
                and labelB LIKE '%".$query."%' and (`datePS` LIKE '%".$annee."-".$mois."%' )  LIMIT $page_position ,$item_per_page"); 
            $req2->execute(array('p' =>1))  or die(print_r($req2->errorInfo())); */                 
      }

      
      $variables = $req1->fetchAll();
      /* $p1=$req1->fetchAll();
      $p2=$req2->fetchAll();
      $payementTotal = array_merge($p1, $p2); */
      //Display records fetched from database.
      echo '<table class="table table-striped contents display tabDesign tableau3" border="1">';
      echo '<thead>
              <tr id="">
              <th>Nom Variable</th>
              <th>Type Caisse</th>
              <th>Categorie Caisse</th>
              <th>Volume Donnees Min</th>
              <th>Volume Donnees Max</th> 
              <th style="border-left-style: none;">Montant Fixe (FCFA)</th>
              <th style="border-left-style: none;"></th>
              <th colspan="">Pourcentage sur Ventes (%)</th>
              <th colspan="">Prix insertion ligne (FCFA)</th>
              <th>Opérations</th>
              </tr>
            </thead>';
      $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0; 

        foreach ($variables as $variable) {  ?>
            <tr>
                                                    <td> <?php echo  $variable['nomvariable']; ?>  
                                                    </td>
                                                    <td> <?php echo  $variable['typecaisse']; ?> </td>
                                                    <td> <?php echo  $variable['categoriecaisse']; ?>  </td>                                                   
                                                    <td> <?php echo  $variable['moyenneVolumeMin']; ?></td>
                                                    <td> <?php echo  $variable['moyenneVolumeMax']; ?></td>
                                                    <td> <?php echo  $variable['montant']; ?></td>
                                                    <td>
                                                        <?php if ($variable['activerMontant']==0) { ?>
                                                                <button type="button" class="btn btn-success" onClick="activerParamMont(<?php echo  $variable['idvariable'] ; ?>)" >
                                                                Activer</button>
                                                                <?php
                                                            } else { ?>
                                                            <button type="button" class="btn btn-danger" onClick="desactiverParamMont(<?php echo  $variable['idvariable'] ; ?>)" >
                                                            Desactiver</button>
                                                        <?php }?>
                                                    </td>
                                                    <td> <?php echo  $variable['pourcentage']; ?>% 
	                                                    <?php
	                                                    	if ($variable['activerPourcentage']==0) { ?>
	                                                         <button type="button"  class="btn btn-success" onClick="activerParamPourc(<?php echo  $variable['idvariable'] ; ?>)">
	                                                            Activer</button>
	                                                            <?php
	                                                        } else { ?>
	                                                            <button type="button"  class="btn btn-danger"  onClick="desactiverParamPourc(<?php echo  $variable['idvariable'] ; ?>)" >
	                                                            Desactiver</button>
	                                                        <?php }
	                                                    ?>
                                                    </td>
                                                    <td> <?php echo  $variable['prixLigne'];   ?> 
	                                                    <?php
	                                                    if ($variable['activerPrix']==0) { ?>
	                                                            <button type="button" class="btn btn-success"  onClick="activerParamLigne(<?php echo  $variable['idvariable'] ; ?>)">
	                                                            Activer</button>
	                                                            <?php
	                                                        } else { ?>
	                                                            <button type="button" class="btn btn-danger"  onClick="desactiverParamLigne(<?php echo  $variable['idvariable'] ; ?>)">
	                                                            Desactiver</button>
	                                                        <?php }
	                                                         ?>
	                                                </td>
                                                  <td> <a onClick="modVariable(<?php echo $variable['idvariable']  ?>)" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                                       <a onClick="supVariable(<?php echo $variable['idvariable']  ?>)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> 
	                                                    
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
