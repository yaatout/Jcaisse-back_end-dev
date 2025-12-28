<?php
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../index.php');

   }

   require('../connection.php');
   require('../connectionPDO.php');
   require('../declarationVariables.php');

try{

    $nbre_Entree = @$_POST["nbre_Entree"];
    $query = @$_POST["query"];
    $item_per_page = ($nbre_Entree!=0) ? $nbre_Entree : 10; //item to display per page
    $page_number 		= 0; //page number
    $produits = array();
    $tabIdDesigantion = array();
    $tabIdStock = array();

    $idCompte = @$_POST['id'];
    $dateDebut = @$_POST['dateDebut'];
    $dateFin = @$_POST['dateFin'];
      
    //Get page number from Ajax
    if(isset($_POST["page"])){
        $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
        if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
    }else{
        $page_number = 1; //if there's no page number, set it to 1
    }
        
    if ($query =="") {

        $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableComptemouvement."` c where 
        c.idCompte='".$idCompte."' AND SUBSTR(c.dateOperation,1, 10) BETWEEN '".$dateDebut."' AND '".$dateFin."' ");
        $stmt->execute();
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
        
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);

        $stmtOperation = $bdd->prepare("SELECT * FROM `".$nomtableComptemouvement."` c where 
        c.idCompte='".$idCompte."' AND SUBSTR(c.dateOperation,1, 10) BETWEEN '".$dateDebut."' AND '".$dateFin."'
        ORDER BY dateOperation DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtOperation->execute();
        $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

    } else {

        $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount FROM `".$nomtableComptemouvement."` c where 
        c.idCompte='".$idCompte."' AND SUBSTR(c.dateOperation,1, 10) BETWEEN '".$dateDebut."' AND '".$dateFin."'
        AND (c.montant LIKE :query OR c.description LIKE :query OR c.dateOperation LIKE :query OR c.operation LIKE :query) ");
        $stmt->execute(array(
          ':query' => '%'.$query.'%'
        ));
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
        
        
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);

        $stmtOperation = $bdd->prepare("SELECT * FROM `".$nomtableComptemouvement."` c where 
        c.idCompte='".$idCompte."' AND SUBSTR(c.dateOperation,1, 10) BETWEEN '".$dateDebut."' AND '".$dateFin."'
        AND (c.montant LIKE :query OR c.description LIKE :query OR c.dateOperation LIKE :query  OR c.operation LIKE :query)
        ORDER BY dateOperation DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtOperation->execute(array(
          ':query' => '%'.$query.'%'
        ));
        $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

    }

    $total_Ventes=0;
    $total_Depenses=0;
    $cpt=1;

    echo '<div class="panel-group" id="accordion" style="margin-top:10px;">';

    foreach ($operations as $operation) {

      $stmtUser = $bdd->prepare("SELECT  * FROM `aaa-utilisateur` 
      WHERE idutilisateur=:iduser ");
      $stmtUser->execute(array(
          ':iduser' => $operation['idUser']
      ));
      $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
      $nom_user = substr(strtoupper($user['prenom']),0,3);

      if($operation['image']!=null && $operation['image']!=' '){ 
        $format=substr($operation['image'], -3);
        if($format=='pdf'){ 
           $cols_image= '<img class="btn btn-xs" src="./images/pdf.png" align="middle" alt="apperçu" width="40" height="35" data-toggle="modal" data-target="#imageNvMouvement'.$operation['idMouvement'].'" onclick="showImageMouvement('.$operation['idMouvement'].')" />
            <input style="display:none;" data-image="'.$operation['idMouvement'].'"  id="imageMouvement'.$operation['idMouvement'].'"  value="'.$operation['image'].'" />';
        }
            else { 
              $cols_image= '<img class="btn btn-xs" src="./images/img.png" align="middle" alt="apperçu" width="40" height="35" data-toggle="modal" data-target="#imageNvMouvement'.$operation['idMouvement'].'" onclick="showImageMouvement('.$operation['idMouvement'].')" />
            <input style="display:none;" data-image="'.$operation['idMouvement'].'" id="imageMouvement'.$operation['idMouvement'].'"  value="'.$operation['image'].'" />';
        } 
    }
    else{ 
     $cols_image= '<img class="btn btn-xs" src="./images/upload.png" align="middle" alt="apperçu" width="40" height="35" data-toggle="modal" data-target="#imageNvMouvement'.$operation['idMouvement'].'" onclick="showImageMouvement('.$operation['idMouvement'].')" />
        <input style="display:none;" data-image="'.$operation['idMouvement'].'" id="imageMouvement'.$operation['idMouvement'].'"  value="'.$operation['image'].'" />';
    }

    if($operation['annuler']==1){
      $couleur='default';
      $cols_boutons='';
    }
    else {
      if($operation['operation']=='retrait'){
        $couleur='danger';
      }
      else if($operation['operation']=='transfert'){
        $couleur='warning';
      }
      else{
        $couleur='success';
      }

      if($operation['mouvementLink']==0 && $operation['idVersement']==0){
        $cols_boutons='<button onclick="annuler_Mouvement('.$operation['idMouvement'].','.$operation['montant'].')" class="btn btn-danger pull-right" style="margin-bottom:10px; margin-right:10px;"  >
        <span class="glyphicon glyphicon-remove"></span> Annuler
        </button>';
      }
      else {
        $cols_boutons='';
      }

    }

      echo '<div class="panel panel-'.$couleur.'">
            <div class="panel-heading">
              <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#operation'.$operation['idMouvement'].'" class="panel-title expand">
                <div class="right-arrow pull-right">+</div>
                <a href="#">  
                  <span class="hidden-xs col-sm-2 col-md-2 col-lg-2"><span class="mot_Panier">'.$operation['idMouvement'].'</span></span>
                  <span class="hidden-xs hidden-sm hidden-md col-lg-3"><span class="mot_Total">Date</span> : '.$operation['dateOperation'].' </span>
                  <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">Tye</span> : '.$operation['operation'].'</span>
                  <span class="col-xs-6 col-sm-3 col-md-3 col-lg-3"> <span class="mot_Total">Montant</span> : '.number_format(($operation['montant'] * $_SESSION['devise']), 0, ',', ' ').'</span>
                  <span class="col-xs-5 col-sm-3 col-md-3 col-lg-1"> '.$nom_user.'</span>
                </a>
              </h4>
            </div>
            <div id="operation'.$operation['idMouvement'].'" class="panel-collapse collapse">
              <div class="content-panel" style="margin:10px;">
                  '.$cols_boutons.'
                  <table class="table table-bordered">
                      <thead class="noImpr">
                        <tr>
                            <th style="display:none" id="Compte'.$operation['idMouvement'].'">Compte</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Piece Jointe</th>
                        </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>'.strtoupper($operation['operation']).'</td>
                        <td>'.$operation['description'].'</td>
                        <td>'.$operation['montant'].'</td>
                        <td>'.$operation['dateOperation'].'</td>
                        <td>'.$cols_image.'</td>
                      </tr>
                    </tbody>
                  </table>
              </div>
            </div>
      </div>';

        $cpt++;
        // $produits[] = $designation['idDesignation'];
        // }
    }
    echo '</div>';
    echo '<table>';
    if ($cpt==0) {
        # code...
        echo '<tr>';
        echo  '<td colspan="10" align="center">Données introuvables!</td>';
        echo '</tr>';
    }
    echo '</table>';

    if($total_rows>0){
      echo '<div class="">';
      echo '<div class="col-md-1"> 
          <select class="form-control pull-left" id="slct_Nbre_ListerOperations">
          <optgroup>
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option> 
              <option value="100">100</option> 
          </optgroup>       
          </select>
          </div>';
          echo '<div class="col-md-3">';
          echo '<ul class="pull-left" style="margin-top:5px;">';
              if ($item_per_page > $total_rows) {
              # code...
                  echo '1 <span class="mot_A"> à </span> '.($total_rows).' <span class="mot_Sur"> sur </span> '.$total_rows.' <span class="mot_Articles"></span>';        
              } else {
              # code...
              if ($page_number == 1) {
                  # code...
                  echo '1 <span class="mot_A"> à </span> '.($item_per_page).' <span class="mot_Sur"> sur </span> '.$total_rows.' <span class="mot_Articles"></span>';
              } else {
                  # code...
                  if (($total_rows-($item_per_page * $page_number)) < 0) {
                  # code... 
                  echo ($item_per_page * ($page_number-1) +1).' à '.$total_rows.' <span class="mot_Sur"> sur </span> '.$total_rows.' <span class="mot_Articles"></span>';
                  } else {
                  # code...
                  echo ($item_per_page * ($page_number-1) +1).' à '.($item_per_page * $page_number).' <span class="mot_Sur"> sur </span> '.$total_rows.' <span class="mot_Articles"></span>';
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
    
}
      
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}

function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
  {
      $pagination = '';
      if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination pull-right" style="margin-top:5px;">';
          
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


