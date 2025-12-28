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

    $idReservation = @$_POST['id'];
      
    //Get page number from Ajax
    if(isset($_POST["page"])){
        $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
        if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
    }else{
        $page_number = 1; //if there's no page number, set it to 1
    }
        
    if ($query =="") {

        $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount
          FROM
          (SELECT r.idReservation FROM `".$nomtableLigneReservation."` r where r.idReservation='".$idReservation."' 
          UNION ALL
          SELECT v.idReservation FROM `".$nomtableVersement."` v where v.idReservation='".$idReservation."'
          ) AS a ");
        $stmt->execute();
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
        
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);


        $stmtOperation = $bdd->prepare("SELECT ordreDateLigne, codeLigne
          FROM
          (SELECT r.idReservation,r.numligne,r.dateLigne,r.heureLigne,CONCAT(r.dateLigne,r.heureLigne) as ordreDateLigne ,CONCAT(r.numligne,'+1') as codeLigne  FROM `".$nomtableLigneReservation."` r where r.idReservation='".$idReservation."'
          UNION 
          SELECT v.idReservation,v.idVersement,v.dateVersement,v.heureVersement,CONCAT(v.dateVersement,v.heureVersement) as ordreDateVersement, CONCAT(v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idReservation='".$idReservation."'  
          ) AS a ORDER BY ordreDateLigne DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtOperation->execute();
        $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

    } else {
        # code... 

        $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount
          FROM
          (SELECT r.idReservation FROM `".$nomtableLigneReservation."` r where r.idReservation='".$idReservation."' AND (r.designation LIKE :query OR r.dateArrivee LIKE :query OR r.dateDepart LIKE :query)
          UNION ALL
          SELECT v.idReservation FROM `".$nomtableVersement."` v where v.idReservation='".$idReservation."'  AND (v.montant LIKE :query OR v.dateVersement LIKE :query)
          ) AS a ");
          $stmt->execute(array(
            ':query' => '%'.$query.'%'
          ));
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
        
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);

        $stmtOperation = $bdd->prepare("SELECT ordreDateLigne, codeLigne
        FROM
        (SELECT r.idReservation,r.numligne,r.dateLigne,r.heureLigne,CONCAT(r.dateLigne,r.heureLigne) as ordreDateLigne ,CONCAT(r.numligne,'+1') as codeLigne  FROM `".$nomtableLigneReservation."` r where r.idReservation='".$idReservation."' AND (r.designation LIKE :query OR r.dateArrivee LIKE :query OR r.dateDepart LIKE :query)
        UNION 
        SELECT v.idReservation,v.idVersement,v.dateVersement,v.heureVersement,CONCAT(v.dateVersement,v.heureVersement) as ordreDateVersement, CONCAT(v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idReservation='".$idReservation."'  AND (v.montant LIKE :query OR v.dateVersement LIKE :query)
        ) AS a ORDER BY ordreDateLigne DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtOperation->execute(array(
          ':query' => '%'.$query.'%'
        ));
      $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

    }

    $total_Ventes=0;
    $total_Depenses=0;
    $cpt=0;

    echo '<div class="panel-group" id="accordion" style="margin-top:10px;">';

    foreach ($operations as $operation) {

      $type = explode("+", $operation['codeLigne']);

      if($type[1]==1){

        $stmtLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigneReservation."` WHERE numligne =:numligne ");
        $stmtLigne->execute(array(
            ':numligne' => $type[0]
        ));
        $ligne = $stmtLigne->fetch(PDO::FETCH_ASSOC);

        $cols_boutons=' <button type="submit" class="btn btn-danger pull-right" style="margin-bottom:10px; margin-right:10px;" onclick="supprimer_Bien('.$ligne['numligne'].')" >
          <span class="glyphicon glyphicon-remove"></span> Supprimer
        </button>
        <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" >
            <input type="hidden" name="numligne" id="numligne"  value="'.$ligne['numligne'].'" />
            
            <button type="submit" class="btn btn-primary pull-right" >
              Ticket
            </button>
        </form>';

        echo '<div class="panel panel-success">
            <div class="panel-heading">
              <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#Bien'.$ligne['numligne'].'" class="panel-title expand">
                <div class="right-arrow pull-right">+</div>
                <a href="#" onclick="detailsVente('.$ligne['numligne'].')">  
                  <span class="hidden-xs col-sm-2 col-md-2 col-lg-3"><span class="mot_Panier">Bien : '.$ligne['designation'].'</span></span>
                  <span class="hidden-xs hidden-sm hidden-md col-lg-2"><span class="mot_Total">Arrivee</span> : '.$ligne['dateArrivee'].' </span>
                  <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">Depart</span> : '.$ligne['dateDepart'].'</span>
                  <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> Montant : '.number_format(($ligne['prixtotal'] * $_SESSION['devise']), 0, ',', ' ').'</span>
                  <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2 pull-right">Recu : #'.$ligne['numligne'].'</span>
                </a>
              </h4>
            </div>

            <div id="Bien'.$ligne['numligne'].'" class="panel-collapse collapse">
              <div class="content-panel" style="margin:10px;">
              '.$cols_boutons.'
                <table class="table table-bordered">
                    <thead class="noImpr">
                      <tr>
                          <th>Bien</th>
                          <th>Prix</th>
                          <th>Date Arrivee</th>
                          <th>Date Depart</th>
                          <th>Jour(s)</th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td>'.$ligne['designation'].'</td>
                        <td>'.$ligne['prixbien'].'</td>
                        <td>'.$ligne['dateArrivee'].' '.$ligne['heureArrivee'].'</td>
                        <td>'.$ligne['dateDepart'].' '.$ligne['heureDepart'].'</td>
                        <td>'.$ligne['jour'].'</td>
                        <td>
                            <button type="button" disabled="true" class="btn btn-warning pull-right" onclick="modifier_Bien('.$ligne['numligne'].')" >
                                    <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </td>
                      </tr>
                  </tbody>
                </table>
              </div>
            </div>
        </div>';

      }
      else if($type[1]==2){

        $stmtVersement = $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` WHERE idVersement =:idVersement ");
        $stmtVersement->execute(array(
            ':idVersement' => $type[0]
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);

        if($versement['dateVersement']!='' && $versement['dateVersement']!=null){
          $tab=explode("-",$versement['dateVersement']);
          $dateVersement=$tab[2].'-'.$tab[1].'-'.$tab[0];
        }
        else {
          $dateVersement='';
        } 

        if ($versement['iduser']==$_SESSION['iduser']){
          $cols_boutons=' <button type="submit" class="btn btn-danger pull-right" style="margin-bottom:10px; margin-right:10px;" onclick="supprimer_Versement('.$versement['idVersement'].')" >
              <span class="glyphicon glyphicon-remove"></span> Supprimer
            </button>
            <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" >
                <input type="hidden" name="idVersement" id="idVersement"  value="'.$versement['idVersement'].'" />
                
                <button type="submit" class="btn btn-warning pull-right" >
                   Facture
                </button>
            </form>
            <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" >
                <input type="hidden" name="idVersement" id="idVersement"  value="'.$versement['idVersement'].'" />
                
                <button type="submit" class="btn btn-primary pull-right" >
                  Ticket
                </button>
            </form>';
        }
        else {
          $cols_boutons='<form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" >
                <input type="hidden" name="idVersement" id="idVersement"  value="'.$versement['idVersement'].'" />
                
                <button type="submit" class="btn btn-warning pull-right" >
                  Facture
                </button>
            </form>
            <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" >
                <input type="hidden" name="idVersement" id="idVersement"  value="'.$versement['idVersement'].'" />
                
                <button type="submit" class="btn btn-primary pull-right" >
                 Ticket
                </button>
            </form>';
        }

        if($versement['image']!=null && $versement['image']!=' '){
            $format=substr($versement['image'], -3); 
            if($format=='pdf'){ 
                $cols_image='<img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvVersement'.$versement['idVersement'].'"  onclick="showImageVersement('.$versement['idVersement'].')" 	 />
                <input style="display:none;" data-image="'.$versement['idVersement'].'"  id="imageVersement'.$versement['idVersement'].'"  value="'.$versement['image'].'" />';
            }
            else { 
              $cols_image='<img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvVersement'.$versement['idVersement'].'"  onclick="showImageVersement('.$versement['idVersement'].')" 	 />
              <input style="display:none;" data-image="'.$versement['idVersement'].'"  id="imageVersement'.$versement['idVersement'].'"  value="'.$versement['image'].'" />';
            }
        }
        else { 
            $cols_image='<img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvVersement'.$versement['idVersement'].'"  onclick="showImageVersement('.$versement['idVersement'].')" 	 />
            <input style="display:none;" data-image="'.$versement['idVersement'].'"  id="imageVersement'.$versement['idVersement'].'"  value="'.$versement['image'].'" />';
        }

          echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#versement'.$versement['idVersement'].'" class="panel-title expand">
                  <div class="right-arrow pull-right">+</div>
                  <a href="#" onclick="detailsVente('.$versement['idVersement'].')">  
                    <span class="hidden-xs col-sm-2 col-md-2 col-lg-2"><span class="mot_Panier">Versement</span></span>
                    <span class="hidden-xs hidden-sm hidden-md col-lg-2"><span class="mot_Total">Date</span> : '.$versement['dateVersement'].' </span>
                    <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">Heure</span> : '.$versement['heureVersement'].'</span>
                    <span class="col-xs-6 col-sm-3 col-md-3 col-lg-3"> Montant : '.number_format(($versement['montant'] * $_SESSION['devise']), 0, ',', ' ').'</span>
                    <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2 pull-right">Recu : #'.$versement['idVersement'].'</span>
                  </a>
                </h4>
              </div>

              <div id="versement'.$versement['idVersement'].'" class="panel-collapse collapse">
                <div class="content-panel" style="margin:10px;">
                '.$cols_boutons.'
                  <table class="table table-bordered">
                      <thead class="noImpr">
                        <tr>
                            <th style="display:none" id="fournisseur'.$versement['idVersement'].'">Fournisseur</th>
                            <th>Description</th>
                            <th>Montant</th>
                            <th>Paiement</th>
                            <th>Date</th>
                            <th>Piece Jointe</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="ancien_Versement'.$versement['idVersement'].'">
                          <td>'.$versement['paiement'].'</td>
                          <td>'.$versement['montant'].'</td>
                          <td></td>
                          <td>'.$versement['dateVersement'].'</td>
                          <td>'.$cols_image.'</td>
                          <td>
                              <button type="button" class="btn btn-warning pull-right" onclick="modifier_Versement('.$versement['idVersement'].')" >
                                      <span class="glyphicon glyphicon-pencil"></span>
                              </button>
                          </td>
                        </tr>
                        <tr style="display:none" id="nouveau_Versement'.$versement['idVersement'].'">
                          <form class="form-inline noImpr"  method="post" > 
                            <td><textarea  type="textarea" id="inpt_mdf_Versement_Description'.$versement['idVersement'].'" class="form-control" name="paiement" placeholder="Description" >'.$versement['paiement'].'</textarea></td> 
                            <td><input type="number" id="inpt_mdf_Versement_Montant'.$versement['idVersement'].'" class="form-control" value="'.$versement['montant'].'" name="montantBl" ></td>
                            <td></td>
                            <td><input type="text" id="inpt_mdf_Versement_Date'.$versement['idVersement'].'" value="'.$dateVersement.'"  class="form-control"  name="dateVersement" placeholder="jj-mm-aaaa" required=""></td>
                            <td></td>
                            <td>
                                <input type="hidden" name="idVersement" value="'.$versement['idVersement'].'" >
                                <button type="submit" class="btn btn-success pull-right" onclick="btn_modifier_Versement('.$versement['idVersement'].')">
                                        <span class="glyphicon glyphicon-ok"></span>
                                </button>
                            </td>
                          </form>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>';
      } 

        $cpt++;

    }
    echo '</div>';
    echo '<table>';

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


