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
  header('Location:../../../index.php');
  }

require('../../connection.php');
require('../../connectionPDO.php');

require('../../declarationVariables.php');

$operation=@htmlspecialchars($_POST["operation"]);
$idAvion=@htmlspecialchars($_POST["idAvion"]);
$etat=@htmlspecialchars($_POST["etat"]);
$tri=@htmlspecialchars($_POST["tri"]);


if ($operation=="1" || $operation=="3" || $operation=="4") {

  $query = @$_POST["query"];
  $retraits_etat1 = array(); // 1 : pour ce qui n'ont pas retirés leurs bagages
  $retraits_etat2 = array(); // 2 : pour ce qui ont retirés leurs bagages en donnant une avance
  $retraits_etat3 = array(); // 3 : pour ce qui ont retirés leurs bagages en payant le tout

  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
  //get total number of records from database
  
  if ($query =="") {
    
    $sql="SELECT *, SUM(apayerPagnet) as total_cbm FROM `".$nomtablePagnet."` p, `".$nomtableClient."` c where p.idClient=c.idClient and p.idAvion='".$idAvion."' and p.type=0 and c.archiver=0 GROUP BY p.idClient";
    
  } else {
    
    //get total number of records from database
      
    $sql="SELECT *, SUM(apayerPagnet) as total_cbm FROM `".$nomtablePagnet."` p, `".$nomtableClient."` c where p.idClient=c.idClient and p.idAvion='".$idAvion."' and p.type=0 and c.archiver=0 and (CONCAT(c.prenom,' ', c.nom) LIKE '%".$query."%' or c.telephone LIKE '%".$query."%') GROUP BY p.idClient";
    
  }

  $statement = $bdd->prepare($sql);
  $statement->execute();
    
  $data = $statement->fetchAll(PDO::FETCH_ASSOC); 

  $sqlV="SELECT *, SUM(montant) as total_versement FROM `".$nomtableVersement."` WHERE idAvion='".$idAvion."' GROUP BY idClient";
    // var_dump($sqlV);
  $statementV = $bdd->prepare($sqlV);
  $statementV->execute();
    
  $dataVersement = $statementV->fetchAll(PDO::FETCH_ASSOC); 

  // var_dump($data);
  
  if ($etat == "1") {
    if (sizeof($dataVersement)!=0) {
      $index=0;
      foreach ($data as $d) {
        foreach ($dataVersement as $dV) {
          if ($d['idClient'] == $dV['idClient']) {
            unset($data[$index]);
          }
           else {
            $d['reste']=$d['total_cbm'];
          }
        }
        $index++;
      }
        // array_values($data);
    }
  } else if ($etat == "2") {
     if (sizeof($dataVersement)!=0) {
      foreach ($dataVersement as $dV) {
        foreach ($data as $d) {
          if ($d['idClient'] == $dV['idClient'] && $d['total_cbm'] > $dV['total_versement']) {
            $d['reste']=$d['total_cbm'] - $dV['total_versement'];
            $retraits_etat2 [] = $d;
          } 
          // $index++;
        }
      }
    }
    $data = $retraits_etat2;
    
  } else if ($etat == "3") {
     if (sizeof($dataVersement)!=0) {
      foreach ($dataVersement as $dV) {
        foreach ($data as $d) {
          if ($d['idClient'] == $dV['idClient'] && $d['total_cbm'] == $dV['total_versement']) {
            $d['reste']=0;
            $retraits_etat3 [] = $d;
          } 
          // $index++;
        }
      }
    }
    
    $data = $retraits_etat3;
  }
  
  // var_dump($etat); 
  // var_dump($data);
  // var_dump($dataVersement);
  // die();
  
  // $total_rows = sizeof($data);
  // $total_pages = ceil($total_rows/$item_per_page);

  // //position of records
  // $page_position = (($page_number-1) * $item_per_page);
  
  //Display records fetched from database.
  echo '<table class="table table-striped" id="listePaymentByContainer" border="1">';
  echo '<thead>
          <tr>
            <th>Ordre</th>
            <th>Nom complèt</th>
            <th>Téléphone</th>
            <th>Nb. CBM/KG</th>
            <th>Prix CBM/KG</th>
            <th>Total à payer</th>
            <th>Restant</th>
            <th>Opération</th>
          </tr>
        </thead>';

// $data = array_slice($data, $page_position, $item_per_page);
  $i = 1;
  $cpt = 0;
foreach ($data as $key) {
    // $cbm=0;
    // $bal=0;

    $sql="SELECT * FROM `".$nomtableLigne."` where idPagnet=".$key['idPagnet'];
    
    $statement = $bdd->prepare($sql);
    $statement->execute();
      
    $l = $statement->fetch(PDO::FETCH_ASSOC); 
    // var_dump($lignes);

    // foreach ($lignes as $l) {
    //     if (strtolower($l['designation'] == 'cbm')) {
    //         $cbm = $l['quantite'];
    //     }
    //     else if (strtolower($l['designation']) == 'bal') {
    //         $bal = $l['prixunitevente'];
    //     }
    // }

    echo '<tr>';
    // if ($i==1) {
      
    //   echo  '<td><span style="color:blue;">'.$i.'</span></td>';
    //   echo  '<td><span style="color:blue;">'.$key['prenom'].' '.$key['nom'].'</span></td>';
    //   echo  '<td><span style="color:blue;">'.$key['telephone'].'</span></td>';
    //   echo  '<td><span style="color:blue;">'.$l['quantite'].'</span></td>';
    //   echo  '<td><span style="color:blue;">'.number_format($l['prixunitevente'],0,""," ").'</span></td>';
    //   echo  '<td><span style="color:blue;">'.number_format($l['quantite']*$l['prixunitevente'],0,""," ").'</span></td>';
    // } else {
      
      echo  '<td>'.$i.'</td>';
      echo  '<td>'.$key['prenom'].' '.$key['nom'].'</td>';
      echo  '<td>'.$key['telephone'].'</td>';
      echo  '<td>'.$l['quantite'].'</td>';
      echo  '<td>'.number_format($l['prixunitevente'],0,""," ").'</td>';
      echo  '<td>'.number_format($l['quantite']*$l['prixunitevente'],0,""," ").'</td>';
      echo  '<td>'.number_format($key['reste'],0,""," ").'</td>';
    // }
    echo  '<td> 
            <a class="btn btn-xs btn-success" target="_blank" href="bonPclient.php?c='.$key['idClient'].'"><span class="glyphicon glyphicon-plus"></span> Voir le client</a>
          </td>';
    echo '</tr>';

    $i++;
    $cpt++;
  }
  if ($cpt==0) {
    
    echo '<tr>';
    echo  '<td colspan="7" align="center">Données introuvables!</td>';
    echo '</tr>';
  }
  echo '</table>';

  // if ($cpt > 0) {
  //   echo '<div class="">';
  //       echo '<div class="col-md-4">';
  //       echo '<ul class="pull-left">';
  //           if ($item_per_page > $total_rows) {
            
  //               echo '1 à '.($total_rows).' sur '.$total_rows.' articles';        
  //           } else {
            
  //           if ($page_number == 1) {
                
  //               echo '1 à '.($item_per_page).' sur '.$total_rows.' articles';
  //           } else {
                
  //               if (($total_rows-($item_per_page * $page_number)) < 0) {
                 
  //               echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' sur '.$total_rows.' articles';
  //               } else {
                
  //               echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' sur '.$total_rows.' articles';
  //               }
  //           }
  //           }      
  //       echo '</ul>';
  //       echo '</div>';
  //   }
    // echo '<div class="col-md-8">';
    // // To generate links, we call the pagination function here. 
    // echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
    // echo '</div>';
  echo '</div>';

}


function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  {
      $pagination = '';
      if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination pull-right">';
          
          if ($current_page == 1) {
            
            $pagination .= '<li class="page-item disabled"><a data-page="1" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>
            </li>';
          } else {
            
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page - 1).'" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a></li>';
          }        
        
          if ($total_pages <= 5) {                                            
            for($page = 1; $page <= $total_pages; $page++){
              if ($current_page == $page) {
                
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              } else {
                
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
              }
              }
            }else {  
              if ($current_page == 1) {
                
                $pagination .= '<li class="page-item active"><a href="#" data-page="1" class="page-link">1</a></li>';
              } else {
                
                $pagination .= '<li class="page-item"><a href="#" data-page="1" class="page-link">1</a></li>';
              }
                                                              
              if($current_page == 1 || $current_page == 2){ 
                for($page = 2 ; $page <= 3; $page++){
                  if ($current_page == $page) {
                    
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }                  
                }         
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                                            
                } else if(($current_page > 2) and ($current_page < $total_pages - 2)){  
                  $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';

                  for($page =$current_page ; $page <= ($current_page + 1); $page++){ 
                    if ($current_page == $page) {
                      
                      $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    } else {
                      
                      $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    }
                }
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
              
              }else{
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                for($page = ($total_pages - 2) ; $page <= ($total_pages - 1); $page++){
                  if ($current_page == $page) {
                    
                    $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  } else {
                    
                    $pagination .= '<li class="page-item page"><a href="#" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                  }
                }
              }
              if ($current_page == $total_pages) {
                
                $pagination .= '<li class="page-item page active"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              } else {
                
                $pagination .= '<li class="page-item page"><a href="#" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
              }
              }
          if ($current_page == $total_pages) {
            
            $pagination .= '<li class="page-item disabled"><a data-page="'.$total_pages.'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          } else {
            
            $pagination .= '<li class="page-item"><a href="#" data-page="'.($current_page + 1).'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
          }
          
          $pagination .= '</ul>'; 
      }
      return $pagination; //return pagination links
  }


?>

<script>
$(document).ready( function () {
    $('#listePaymentByContainer').DataTable();
  } );
</script>