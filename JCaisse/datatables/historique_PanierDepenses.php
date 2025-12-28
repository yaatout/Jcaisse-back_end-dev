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

    $dateJour = @$_POST['dateJour'];
      
    //Get page number from Ajax
    if(isset($_POST["page"])){
        $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
        if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
    }else{
        $page_number = 1; //if there's no page number, set it to 1
    }
        
    if ($query =="") {
       
        $stmt = $bdd->prepare("SELECT COUNT(DISTINCT p.idPagnet) AS allcount FROM `".$nomtablePagnet."` p
        LEFT JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
        WHERE p.verrouiller=1 AND p.idClient=0 AND  (p.type=0 || p.type=30) AND l.classe=2 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') )  ");
        $stmt->execute();
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];

        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);

        $stmtOperation = $bdd->prepare("SELECT DISTINCT p.idPagnet, p.heurePagnet, p.totalp, p.apayerPagnet, p.verrouiller, p.image FROM `".$nomtablePagnet."` p
        LEFT JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
        WHERE p.verrouiller=1 AND p.idClient=0 AND  (p.type=0 || p.type=30) AND l.classe=2 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') ) 
        ORDER BY p.heurePagnet DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtOperation->execute();
        $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

    } else {

        $stmt = $bdd->prepare("SELECT COUNT(DISTINCT p.idPagnet) AS allcount FROM `".$nomtablePagnet."` p
        LEFT JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
        WHERE p.verrouiller=1 AND p.idClient=0 AND  (p.type=0 || p.type=30) AND l.classe=2 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') ) 
        AND (p.idPagnet LIKE :query OR p.heurePagnet LIKE :query OR p.apayerPagnet LIKE :query OR l.designation LIKE :query) ");
        $stmt->execute(array(
          ':query' => '%'.$query.'%'
        ));
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];

        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);

        $stmtOperation = $bdd->prepare("SELECT DISTINCT p.idPagnet, p.heurePagnet, p.totalp, p.apayerPagnet, p.verrouiller, p.image FROM `".$nomtablePagnet."` p
        LEFT JOIN `".$nomtableLigne."` l ON l.idPagnet = p.idPagnet
        WHERE p.verrouiller=1 AND p.idClient=0 AND  (p.type=0 || p.type=30) AND l.classe=2 AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$dateJour."') or (p.datepagej ='".$dateJour."') ) 
        AND (p.idPagnet LIKE :query OR p.heurePagnet LIKE :query OR p.apayerPagnet LIKE :query OR l.designation LIKE :query)
        ORDER BY p.heurePagnet DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtOperation->execute(array(
          ':query' => '%'.$query.'%'
        ));
        $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

    }

    $cpt=1;

    echo '<div class="panel-group" id="accordion">';

    foreach ($operations as $vente) {

      if($vente['image']!=null && $vente['image']!=' '){
          $format=substr($vente['image'], -3); 
          if($format=='pdf'){ 
              $cols_image='<img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvDepenses'.$vente['idPagnet'].'"  onclick="showImageDepenses('.$vente['idPagnet'].')" 	 />
              <input style="display:none;" data-image="'.$vente['idPagnet'].'"  id="imageDepenses'.$vente['idPagnet'].'"  value="'.$vente['image'].'" />';
          }
          else { 
            $cols_image='<img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvDepenses'.$vente['idPagnet'].'"  onclick="showImageDepenses('.$vente['idPagnet'].')" 	 />
            <input style="display:none;" data-image="'.$vente['idPagnet'].'"  id="imageDepenses'.$vente['idPagnet'].'"  value="'.$vente['image'].'" />';
          }
      }
      else { 
          $cols_image='<img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvDepenses'.$vente['idPagnet'].'"  onclick="showImageDepenses('.$vente['idPagnet'].')" 	 />
          <input style="display:none;" data-image="'.$vente['idPagnet'].'"  id="imageDepenses'.$vente['idPagnet'].'"  value="'.$vente['image'].'" />';
      }

      echo '<div class="panel panel-danger">
            <div class="panel-heading">
              <h4 style="padding-bottom: 18px;" class="panel-title expand">
                <div class="right-arrow pull-right">+</div>
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#Depenses'.$vente['idPagnet'].'" onclick="panier_Depenses('.$vente['idPagnet'].')">  
                  <span class="hidden-xs col-sm-2 col-md-2 col-lg-2"><span class="mot_Panier">Panier</span> : '.$cpt.'</span>
                  <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">Heure</span> : '.$vente['heurePagnet'].'</span>
                  <span class="hidden-xs hidden-sm hidden-md col-lg-2"><span class="mot_Total">Total</span> <span class="mot_Panier"></span> : '.number_format(($vente['totalp'] * $_SESSION['devise']), 0, ',', ' ').'</span>
                  <span class="col-xs-6 col-sm-3 col-md-3 col-lg-3"> <span class="mot_Total">A Payer Panier</span> <span class="mot_à"></span> <span class="mot_Payer"></span> : '.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span>
                  <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2 pull-right"><span class="mot_Facture">Facture</span> : '.$vente['idPagnet'].'</span>
                </a>
              </h4>
            </div>
            <div id="Depenses'.$vente['idPagnet'].'" class="panel-collapse collapse">
              <div class="content-panel">
                <div class="panel-body">
                  <table class="table table-hover" id="panier_Depenses'.$vente['idPagnet'].'">
                  <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" action="barcodeFacture.php" target="_blank" >
                  <input type="hidden" name="idPagnet" id="idPagnet"  value="'.$vente['idPagnet'].'" />
                  <button type="submit" class="btn btn-warning pull-right" >
                      <span class="mot_Ticket">Ticket</span>
                  </button>
              </form>
                  <h4 class="pull-left">'.$cols_image.'</h4>
                    <thead>
                      <tr>
                        <th><span class="mot_Reference">Reference</span></th>
                        <th><span class="mot_Quantite">Quantite</span></th>
                        <th><span class="mot_Unite">Unite</span> <span class="mot_Vente">Vente</span></th>
                        <th><span class="mot_Prix">Prix</span> <span class="mot_Unite">Vente</span></th>
                        <th class="hidden-xs hidden-sm"><span class="mot_Prix">Prix</span> <span class="mot_Total">Total</span></th>
                        <th class="hidden-xs"><span class="mot_Operations">Operations</span></th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
      </div>';

      $cpt++;

    }
    echo '</div>';
    echo '<table>';
    if ($cpt==1) {
        # code...
        echo '<tr>';
        echo  '<td colspan="10" align="center">Données introuvables!</td>';
        echo '</tr>';
    }
    echo '</table>';

    if($total_rows>0){
      echo '<div class="">';
      echo '<div class="col-md-1"> 
          <select class="form-control pull-left" id="slct_Nbre_Depenses">
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


