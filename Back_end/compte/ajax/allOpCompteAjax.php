<?php
   session_start();

   if(!$_SESSION['iduserBack']){

   header('Location:../index.php');

   }

   //require('../../connection.php');
   require('../../connectionPDO.php');
   require('../../declarationVariables.php');
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
if (isset($_POST["operation"])) {
  $operation=@htmlspecialchars($_POST["operation"]);
  if ($operation=="resumeTableauDeBord") {
      $idCompter=@htmlspecialchars($_POST["l"]);
    
      $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
      $req1->execute(array('in' =>$idCompter))  or die(print_r($req1->errorInfo())); 
      $compte=$req1->fetch(); 

    //echo '<h2>Compte '.strtoupper($compte['nomCompte']).' : '.number_format($compte['montantCompte'], 0, ',', ' ')." FCFA </h2>";				
        echo      '<div class="panel-group">
                              <div class="panel" style="">
                                  <div class="panel-heading">
                                      <h4 class="panel-title">
                                      <a data-toggle="collapse" href="#collapse1">  Liste operation  </a>
                                      </h4>
                                  </div>
                                  <div id="collapse1" class="panel-collapse collapse">
                                      <div class="panel-heading" style="margin-left:2%;"> ';
                                          
                                          
                                              $sqlDV = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE (
                                                                                                                              operation=:v 
                                                                                                                              OR operation=:d ) 
                                                                                                                              and annuler!=:n"); 
                                              $sqlDV->execute(array('v'=>'versement',
                                                                  'd' =>'depot' ,
                                                                  'n' =>'1' 
                                                                  ))  or die(print_r($sql01->errorInfo()));                                                         
                                              $S_facture =$sqlDV->fetch();
                                              $montantDV=$S_facture['total'];

   
                                              $sqlR = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE  
                                                  operation=:r and annuler!=:n"); 
                                              $sqlR->execute(array(
                                                  'r' =>'retrait' ,
                                                  'n' =>'1' 
                                                  ))  or die(print_r($sqlR->errorInfo()));                                                         
                                              $S_factureR =$sqlR->fetch();
                                              $montantR=$S_factureR['total'];
                                              //var_dump( $montantR);
                                              //////
                                              
                                              
                                              $sqlVT = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE 
                                                  ( operation=:v  OR operation=:t ) and annuler!=:n"); 
                                              $sqlVT->execute(array( 
                                                  'v' =>'virement' ,
                                                  't' =>'transfert' ,
                                                  'n' =>'1' 
                                                  ))  or die(print_r($sqlVT->errorInfo()));                                                         
                                              $S_factureVT =$sqlVT->fetch();
                                              $montantVT=$S_factureVT['total'];
                                              
                                              //// POUR PRET et REMBOURSEMENT
                                              $sqlPret = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE 
                                                   operation=:pr   and annuler!=:n"); 
                                              $sqlPret->execute(array( 
                                                  'pr' =>'pret' ,
                                                  'n' =>'1' 
                                                  ))  or die(print_r($sqlPret->errorInfo()));                                                         
                                              $S_facturePret =$sqlPret->fetch();
                                              $montantPret=$S_facturePret['total'];

                                              $sqlRem = $bdd->prepare("SELECT sum(montant) as total FROM `aaa-comptemouvement` WHERE 
                                                   operation=:rem   and annuler!=:n"); 
                                              $sqlRem->execute(array( 
                                                  'rem' =>'remboursement' ,
                                                  'n' =>'1' 
                                                  ))  or die(print_r($sqlRem->errorInfo()));                                                         
                                              $S_factureRem =$sqlRem->fetch();
                                              $montantRemb=$S_factureRem['total'];
                                              
                                              echo "<h6>Montant depot et versement : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant retrait :  ".number_format($montantR, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant virement et transfert : ".number_format($montantVT, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant pret : ".number_format($montantPret, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant remboursement : ".number_format($montantRemb, 0, ',', ' ')." FCFA</h6>"
                                                 ;     
                                                                                      
                                          echo '

                                      </div>
                                  </div>
                              </div>
              </div>  ';                                                                                  
    }
}
else{


      try{

          $nbre_Entree = @$_POST["nbre_Entree"];
          $query = @$_POST["query"];
          $item_per_page = ($nbre_Entree!=0) ? $nbre_Entree : 10; //item to display per page
          $page_number 		= 0; //page number
          $produits = array();
          $tabIdDesigantion = array();
          $tabIdStock = array();


          // $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
          // $req1->execute(array('in' =>$_SESSION['compteId']))  or die(print_r($req1->errorInfo())); 
          // $compte=$req1->fetch(); 
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
                FROM `aaa-comptemouvement` where annuler!=:a  ORDER BY idMouvement DESC");
              $stmt->execute(array('a'=>1
                                  ));
              $records = $stmt->fetch();
              $total_rows = $records['allcount'];
                    
              $total_pages = ceil($total_rows/$item_per_page);
              $page_position = (($page_number-1) * $item_per_page);


              
          }

          $stmtOperation = $bdd->prepare("SELECT *
                FROM `aaa-comptemouvement` where annuler!=:a  ORDER BY idMouvement DESC LIMIT ".$page_position.",".$item_per_page." ");
              $stmtOperation->execute(array(
                                          'a'=>1
                                          ));
              $mouvements = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

          $total_Ventes=0;
          $total_Depenses=0;
          $cpt=1;

          echo '<div class="panel-group" id="accordion" style="margin-top:10px;">';

          foreach ($mouvements as $mouvement) {

                //$date = date_create($mouvement['dateSaisie']);
                
                $req1 = $bdd->prepare("SELECT * FROM `aaa-compte`  WHERE idCompte=:in"); 
                $req1->execute(array('in' =>$mouvement['idCompte']))  or die(print_r($req1->errorInfo())); 
                $compte=$req1->fetch(); 

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
                    $cols_details = "details_Commande_PH";
                    $cols_table = '';
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
                              <span class=" col-lg-3"><span class="">DATE:</span> : '.$mouvement['dateSaisie'] .' </span>
                              <span class="col-xs-3 col-sm-3 col-md-3 col-lg-4"> <span class="">Montant:</span> : <span id="spn_ajouter_Bon_Total1" >'.number_format($mouvement['montant'], 0, ',', ' ')." FCFA".'</span></span>
                              <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="">Type:</span> : <span id="spn_ajouter_Bon_Total2" >'.$mouvement['operation'].'</span></span> 
                              <span class="col-xs-5 col-sm-3 col-md-3 col-lg-1"> #'.substr(strtoupper($user['prenom']),0,3).'</span>
                              </a>
                          </h4>
                        </div>
                        <div id="mouvement'.$mouvement['idMouvement'].'" class="panel-collapse collapse">
                          <div class="panel-body">
                              <div class="content-panel" style="margin:10px;">';
                                  if($mouvement['operation']=='transfert' || $mouvement['operation']=='virement'){
                                      echo "<p><span class='label label-info'>Numero du destinataire :</span>".$mouvement['numeroDestinataire']."</p>";
                                  }
                                  if($compte['typeCompte']==3){
                                      echo "<p><span class='label label-danger'>Date echéance  : </span>".$mouvement['dateEcheance']."</p>";
                                  }
                                  echo  "<p><span class='label label-success'>Description   :</span>".$mouvement['description']."</p>";                         
                                  // upload pieces jointes
                                  if ($mouvement['operation']=='retrait' ) {
                                    if($mouvement['pJointe'] != null || $mouvement['pJointe'] != '' ){ 
                                        $format=substr($mouvement['pJointe'], -3); ?>
                                                    <?php if($format=='pdf'){ ?>
                                                        <img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upPJPopup(<?php echo $mouvement['idMouvement']; ?>)" 	 />
                                                    <?php }
                                                        else { 
                                                            ?>
                                                            <img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" onclick="upPJPopup(<?php echo $mouvement['idMouvement']; ?>)" 	 />
                                                        <?php } ?>
                                                <?php
                                    }else{ ?>
                                          <img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="45" height="40" data-toggle="modal" onclick="upPJPopup(<?php echo $mouvement['idMouvement']; ?>)" 	 />
                                            
                                    <?php }    
                                }
                          
                              echo '</div>
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



      
      }
?>

