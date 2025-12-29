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

if ($operation=="1" || $operation=="3" || $operation=="4") {

  $nbEntreHist = @$_POST["nbEntreHist"];
  $query = @$_POST["query"];
  $cb = @$_POST["cb"];
  $item_per_page 		= ($nbEntreHist!=0) ? $nbEntreHist : 10; //item to display per page
  $page_number 		= 0; //page number
  
  //Get page number from Ajax
  if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
  }else{
    $page_number = 1; //if there's no page number, set it to 1
  }
  
//get total number of records from database
  
    $sqla = $bdd->prepare("SELECT COUNT(*) as total_row FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE 
     aPayementBoutique=1");     
    $sqla->execute();  
    $total_a = $sqla->fetch();

   /*  $sqlb = $bdd->prepare("SELECT COUNT(*) as total_row FROM `aaa-payement-salaire`  p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=:p
    and (`datePS` LIKE '%".$anneeM."-".$moiM."%')");     
    $sqlb->execute(array('p' =>1)); 
    $total_b = $sqlb->fetch();    */       
  
  /* $total_rows = $total_a['total_row']+$total_b['total_row']; */
  //var_dump($total_rows);
  $total_rows = $total_a['total_row'];
  $total_pages = ceil($total_rows/$item_per_page);
      
      //position of records
      $page_position = (($page_number-1) * $item_per_page);
      
      if ($query =="") { 
        $req1 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=1 
         ORDER BY datePS DESC LIMIT $page_position ,$item_per_page"); 
        $req1->execute()  or die(print_r($req1->errorInfo())); 

        /* $req2 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=:p
            and (`datePS` LIKE '%".$anneeM."-".$moiM."%') LIMIT $page_position ,$item_per_page"); 
        $req2->execute(array('p' =>1))  or die(print_r($req2->errorInfo())); */ 
        
      } else {
        //Limit our results within a specified range.         
            $req1 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=1 and
             labelB LIKE '%".$query."%'  ORDER BY datePS DESC LIMIT $page_position ,$item_per_page"); 
            $req1->execute()  or die(print_r($req1->errorInfo())); 

            /* $req2 = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` p LEFT JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE aPayementBoutique=:p 
                and labelB LIKE '%".$query."%' and (`datePS` LIKE '%".$anneeM."-".$moiM."%' )  LIMIT $page_position ,$item_per_page"); 
            $req2->execute(array('p' =>1))  or die(print_r($req2->errorInfo()));     */             
      }

      
      $payementTotal = $req1->fetchAll();
      /* $p1=$req1->fetchAll();
      $p2=$req2->fetchAll();
      $payementTotal = array_merge($p1, $p2); */
      //Display records fetched from database.
      echo '<table class="table table-striped contents display tabDesign tableau3" id="exemple" border="1" >';
      echo '<thead>
              <tr id="">
              <th>Boutique</th>
								<th>Montant Paiement</th>
								<th>Accompagnateur</th>
								<th>Etape Accompagnement</th>
								<th>Date Paiement</th>
								<th>Paiement</th>
								<th>Opérations</th>
              </tr>
            </thead>';
      $i = ($page_number - 1) * $item_per_page +1;
      $cpt = 0; 

        foreach ($payementTotal as $payement) {  ?>
                                        <tr>
                                            <td> <b>  <?php echo  $payement['labelB']; ?> </b> </td>
                                            <td> <b><?php echo  $payement['montantFixePayement']; ?> FCFA  </b>   </td>
                                           <td>  
                                                 <?php
                                                    $tel1a='';
                                                    $tel2a='';
                                                    $sql0a = $bdd->prepare("SELECT * FROM `aaa-payement-salaire` WHERE idBoutique =:b and 
                                                    aPayementBoutique=:a LIMIT 0,1");     
                                                    $sql0a->execute(array('b'=>$payement['idBoutique'],'a'=>1));  
                                                    $pSaa = $sql0a->fetch();
                                                    ///////////////////////////////////
                                                    $sql00a = $bdd->prepare("SELECT * FROM `aaa-payement-reference` WHERE idPS =:b ");     
                                                    $sql00a->execute(array('b'=>$pSaa['idPS']));  
                                                    $pReferencea = $sql00a->fetch();

                                                    echo  $pReferencea['telRefTransfertValidation'];
                                                    /*echo  $payement['accompagnateur'];*/
                                                 ?>  
                                            </td>
                                            <td>Mois <?php echo  $payement['etapeAccompagnement']; ?>  </td>
                                            <td> <?php echo  $payement['datePS']; ?>  </td>
                                            <?php
                                                 if ($payement['aPayementBoutique']==0) { ?>
                                                    <td><span>En cour...</span></td>
                                                    <td><button type="button" class="btn btn-warning" class="btn btn-success" 
                                                        onClick="validerPaiementOMPop(<?php echo  $payement['idPS'] ; ?>)" data-toggle="modal"> OMoney
                                                    	</button>
                                                      <button type="button" class="btn btn-success" class="btn btn-success" 
                                                        onClick="validerPaiementWavePop(<?php echo  $payement['idPS'] ; ?>)" data-toggle="modal" 
                                                          <?php echo  "data-target=#validerPaiementWave".$payement['idBoutique'] ; ?> >Wave
                                                      </button>
                                                      
                                                    </td>
                                                    <?php
                                                } else { ?>
                                                    <td><span>Effectif</span></td>
                                                      <td>&nbsp;&nbsp;<button type="button" class="btn btn-success" data-toggle="modal" <?php echo  "data-target=#Facture".$payement['idPS'] ; ?> >
                                                      Facture</button>
                                                    </td>
                                                    <div class="modal fade popUpFact" <?php echo  "id=Facture".$payement['idPS'] ; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Facture de paiement</h4>
                                                          </div>
                                                          <div class="modal-body">
                                                            <form name="formulaireVersement" >
                                                            <div class="form-group">
                                                              <h2>Voulez vous vraiment la facture du paiement numero : <?php echo  $payement['idPS']; ?></h2>
                                                              
                                                              
                                                              
                                                              <input type="hidden" name="idPayement" <?php echo  "value=".$payement['idPS']."" ; ?> >
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                <button type="button" onclick="imp_facture(<?php echo $payement['idPS']; ?>)" class="btn btn-primary btnPopUpFact">Imprimer la Facture de paiement</button>
                                                            </div>
                                                            </form>
                                                          </div>

                                                        </div>
                                                      </div>
                                                    </div>
                                                <?php  } ?>
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
