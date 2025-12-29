<?php
   session_start();

   if(!$_SESSION['iduserBack']){

   header('Location:../index.php');

   }

   require('../../connection.php');
   require('../../connectionPDO.php');
   require('../../declarationVariables.php');

try{

    $nbre_Entree = @$_POST["nbre_Entree"];
    $query = @$_POST["query"];
    $item_per_page = ($nbre_Entree!=0) ? $nbre_Entree : 10; //item to display per page
    $page_number 		= 0; //page number
    $produits = array();
    $tabIdDesigantion = array();
    $tabIdStock = array();


    $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
    $req1->execute(array('in' =>$_SESSION['compteId']))  or die(print_r($req1->errorInfo())); 
    $compte=$req1->fetch(); 
    $mouvements=null; 
    //Get page number from Ajax
    if(isset($_POST["page"])){
        $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
        if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
    }else{
        $page_number = 1; //if there's no page number, set it to 1
    }
    
    if ($query =="") {

        $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount
          FROM `aaa-comptemouvement` where idCompte=:i and annuler!=:a  ORDER BY dateSaisie DESC");
        $stmt->execute(array(
                            'i'=>$_SESSION['compteId'],
                            'a'=>'1'
                            ));
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
              
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);


        
    }

    $stmtOperation = $bdd->prepare("SELECT *
          FROM `aaa-comptemouvement` where idCompte=:i and annuler!=:a  ORDER BY dateSaisie DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtOperation->execute(array(
                                    'i'=>$_SESSION['compteId'],
                                    'a'=>'1'
                                    ));
        $mouvements = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

    $total_Ventes=0;
    $total_Depenses=0;
    $cpt=1;

    echo '<div class="panel-group" id="accordion" style="margin-top:10px;">';

    foreach ($mouvements as $mouvement) {
          $date = date_create($mouvement['dateSaisie']);
          
          $req2 = $bdd->prepare("SELECT * FROM `aaa-utilisateur`  WHERE idutilisateur=:in"); 
          $req2->execute(array('in' =>$mouvement['idUser']))  or die(print_r($req2->errorInfo())); 
          $user=$req2->fetch(); 


          if($mouvement['pJointe']!=null && $mouvement['pJointe']!=' '){
              $format=substr($mouvement['pJointe'], -3); 
              if($format=='pdf'){ 
                  $cols_image='<img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$mouvement['idMouvement'].'"  onclick="showImageBon('.$mouvement['idMouvement'].')" 	 />
                  <input style="display:none;" data-image="'.$mouvement['idMouvement'].'"  id="imageBon'.$mouvement['idMouvement'].'"  value="'.$mouvement['pJointe'].'" />';
              }
              else { 
                $cols_image='<img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$mouvement['idMouvement'].'"  onclick="showImageBon('.$mouvement['idMouvement'].')" 	 />
                <input style="display:none;" data-image="'.$mouvement['idMouvement'].'"  id="imageBon'.$mouvement['idMouvement'].'"  value="'.$mouvement['pJointe'].'" />';
              }
          }
          else { 
              $cols_image='<img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$mouvement['idMouvement'].'"  onclick="showImageBon('.$mouvement['idMouvement'].')" 	 />
              <input style="display:none;" data-image="'.$mouvement['idMouvement'].'"  id="imageBon'.$mouvement['idMouvement'].'"  value="'.$mouvement['pJointe'].'" />';
          }
          /*$cols_details = "details_Commande_PH";
          $cols_table = '<table id="listeCommande'.$bon['idBl'].'" class="table table-bordered" width="100%" border="1">
              <thead>
                <tr>
                    <th>Reference</th>
                    <th>Quantite</th>
                    <th>Forme</th>
                    <th>Prix Session</th>
                    <th>Prix Public</th>
                    <th>Montant Prix Session</th>
                    <th>Date Enregistre</th>
                    <th>Date Expiration</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          ';
          $btn_valider='<button onclick="valider_Livraison('.$bon['idBl'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-success pull-pull-left" >
                <span class="glyphicon glyphicon-ok"></span> Valider
              </button>
              <button onclick="transferer_Bon('.$bon['idBl'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-warning pull-pull-left" >
                <span class="glyphicon glyphicon-transfer"></span> Transferer
              </button>';*/
              $cols_details = "details_Commande_PH";
              $cols_table = '<table id="listeMouvement'.$mouvement['idMouvement'].'" class="table table-bordered" width="100%" border="1">
                  <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Quantite</th>
                        <th>Forme</th>
                        <th>Prix Session</th>
                        <th>Prix Public</th>
                        <th>Montant Prix Session</th>
                        <th>Date Enregistre</th>
                        <th>Date Expiration</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              ';
              $btn_valider='<button onclick="valider_Livraison('.$mouvement['idMouvement'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-success pull-pull-left" >
                    <span class="glyphicon glyphicon-ok"></span> Valider
                  </button>
                  <button onclick="transferer_Bon('.$mouvement['idMouvement'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-warning pull-pull-left" >
                    <span class="glyphicon glyphicon-transfer"></span> Transferer
                  </button>';
                
    
              $typePanel="";
              if($compte['typeCompte']==3){
                if($mouvement['retirer']==0){
                    $typePanel=' panel panel-success ';
                } else{
                  $typePanel= 'panel panel-default';
                }
              }else{
                  if($mouvement['operation']=='versement' or $mouvement['operation']=='depot' || $mouvement['operation']=='pret' ){  
                    $typePanel= ' panel panel-success ';
                  } elseif($mouvement['operation']=='retrait' or $mouvement['operation']=='remboursement' ){
                    $typePanel= 'panel panel-danger';
                  }else{
                    $typePanel= 'panel panel-primary';
                  }
              }
           
              echo '<div class="'.$typePanel.'" id="panelMouv'.$mouvement['idMouvement'].'">
                  <div class="panel-heading">
                    <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#mouvement'.$mouvement['idMouvement'].'" class="panel-title expand">
                      <div class="right-arrow pull-right">+</div>
                      <a href="#" onclick="'.$cols_details.'(.$bon[idBl])"> 
                        <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier"> Operation XXX</span></span>
                        <span class="hidden-xs hidden-sm hidden-md col-lg-3"><span class="mot_Total">DATE:</span> : '.date_format($date , 'd-m-y').' </span>
                        <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> <span class="mot_Total">Montant:</span> : <span id="spn_ajouter_Bon_Total1" >'.number_format($mouvement['montant'], 0, ',', ' ')." FCFA".'</span></span>
                        <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">Type:</span> : <span id="spn_ajouter_Bon_Total2" >'.$mouvement['operation'].'</span></span> 
                        <span class="col-xs-5 col-sm-3 col-md-3 col-lg-1"> #'.substr(strtoupper($user['prenom']),0,3).'</span>
                        </a>
                    </h4>
                  </div>
                  <div id="mouvement'.$mouvement['idMouvement'].'" class="panel-collapse collapse">
                    <div class="content-panel" style="margin:10px;">
                        <button style="margin-bottom:10px; margin-right:10px;" onclick="supprimer_Bon()" class="btn btn-danger pull-right" >
                          <span class="glyphicon glyphicon-remove"></span> Supprimer
                        </button>
                        <button style="margin-bottom:10px; margin-right:10px;" type="button" class="btn btn-round btn-info pull-right" onclick="facture_Bon()"> <span class="mot_Facture">Facture</span></button>
                        <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" action="bonCommande.php?id="  >
                            <input type="hidden" name="idBl" id="idBl"   />
                            <button type="submit" class="btn btn-warning pull-right" >
                            <span class="glyphicon glyphicon-folder-open"></span> Details
                            </button>
                        </form>
                        '.$btn_valider.'
                        <table class="table table-bordered">
                            <thead class="noImpr">
                              <tr>
                                  <th>Description</th>
                                  <th>Montant</th>
                                  <th>Date BL</th>
                                  <th>Date Echeance</th>
                                  <th>Piece Jointe</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                            <tr id="ancien_Bon'.$mouvement['idMouvement'].'">
                              
                            </tr>
                            <tr style="display:none" id="nouveau_Bon'.$mouvement['idMouvement'].'">
                              
                            </tr>
                          </tbody>
                        </table>
                        '.$cols_table.'
                    </div>
                  </div>
            </div>';
          
        
        
        $cpt++;
    }
    echo '</div>';

    if ($total_rows>$item_per_page) {
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


