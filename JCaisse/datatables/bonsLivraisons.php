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

    $idFournisseur = @$_POST['id'];
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

        $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount
          FROM `".$nomtableBl."` b where  b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."' ");
        $stmt->execute();
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
        
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);


        $stmtBons = $bdd->prepare("SELECT * 
          FROM `".$nomtableBl."` b where b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
          ORDER BY b.dateBl DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtBons->execute();
        $bons = $stmtBons->fetchAll(PDO::FETCH_ASSOC);

    } else {
        # code... 

        $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount
          FROM `".$nomtableBl."` b where (b.numeroBl LIKE :query OR b.montantBl LIKE :query OR b.dateBl LIKE :query) ");
        $stmt->execute(array(
          ':query' => '%'.$query.'%'
        ));
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
        
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);


        $stmtBons = $bdd->prepare("SELECT *
          FROM `".$nomtableBl."` b where (b.numeroBl LIKE :query OR b.montantBl LIKE :query OR b.dateBl LIKE :query)
          ORDER BY b.dateBl DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtBons->execute(array(
          ':query' => '%'.$query.'%'
        ));
        $bons = $stmtBons->fetchAll(PDO::FETCH_ASSOC);

    }

    $cpt=1;

    echo '<div class="panel-group" id="accordion" style="margin-top:10px;">';

    foreach ($bons as $bon) {

      if($bon['idFournisseur']!=0){
        $stmtFournisseur = $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` WHERE idFournisseur =:idFournisseur ");
        $stmtFournisseur->execute(array(
            ':idFournisseur' => $bon['idFournisseur']
        ));
        $fournisseur = $stmtFournisseur->fetch(PDO::FETCH_ASSOC);
        $nom_fournisseur = $fournisseur['nomFournisseur'];
        $options = '<option selected value="'.$fournisseur['idFournisseur'].'">'.$fournisseur['nomFournisseur'].'</option>';
      }
      else{
        $nom_fournisseur = 'NEANT';
        $options = '<option selected value="0">----------------</option>';
      }

        $stmtUser = $bdd->prepare("SELECT  * FROM `aaa-utilisateur` 
        WHERE idutilisateur=:iduser ");
        $stmtUser->execute(array(
            ':iduser' => $bon['iduser']
        ));
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $nom_user = substr(strtoupper($user['prenom']),0,3);

        if($bon['image']!=null && $bon['image']!=' '){
            $format=substr($bon['image'], -3); 
            if($format=='pdf'){ 
                $cols_image='<img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$bon['idBl'].'"  onclick="showImageBon('.$bon['idBl'].')" 	 />
                <input style="display:none;" data-image="'.$bon['numeroBl'].'"  id="imageBon'.$bon['idBl'].'"  value="'.$bon['image'].'" />';
            }
            else { 
              $cols_image='<img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$bon['idBl'].'"  onclick="showImageBon('.$bon['idBl'].')" 	 />
              <input style="display:none;" data-image="'.$bon['numeroBl'].'"  id="imageBon'.$bon['idBl'].'"  value="'.$bon['image'].'" />';
            }
        }
        else { 
            $cols_image='<img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#imageNvBl'.$bon['idBl'].'"  onclick="showImageBon('.$bon['idBl'].')" 	 />
            <input style="display:none;" data-image="'.$bon['numeroBl'].'"  id="imageBon'.$bon['idBl'].'"  value="'.$bon['image'].'" />';
        }

        if($bon['dateEcheance']!=null && $bon['dateEcheance']!=''){
          $tab=explode("-",$bon['dateEcheance']); 
          $dateEcheance=$tab[2].'-'.$tab[1].'-'.$tab[0];
          $cols_dateEcheance='<input type="date" id="inpt_Bon_DateEcheance_'.$bon['idBl'].'"  value="'.$bon['dateEcheance'].'"  class="form-control champ_saisie_Ligne'.$bon['idBl'].'"  onkeyup="modifier_format_DateEcheance('.$bon['idBl'].')" onchange="modifier_format_DateEcheance('.$bon['idBl'].')">';
        }
        else { 
          $cols_dateEcheance='<input type="date" id="inpt_Bon_DateEcheance_'.$bon['idBl'].'"   class="form-control champ_saisie_Ligne'.$bon['idBl'].'"  onkeyup="modifier_format_DateEcheance('.$bon['idBl'].')" onchange="modifier_format_DateEcheance('.$bon['idBl'].')" >';
        }

        $stmtStock = $bdd->prepare("SELECT  COUNT(*) as nombre  FROM `".$nomtableStock."` WHERE idBl =:idBl ");
        $stmtStock->execute(array(
            ':idBl' => $bon['idBl']
        ));
        $stock = $stmtStock->fetch();
        $stock_rows = $stock['nombre'];

        if($stock_rows!=0){
          if($bon['idFournisseur']!=0){
            $cols_boutons='<form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" action="bonLivraison.php?id='.$bon['idBl'].'"  >
                  <input type="hidden" name="idBl" id="idBl"  value="'.$bon['idBl'].'" />
                  
                  <button type="submit" class="btn btn-warning pull-right" >
                  <span class="glyphicon glyphicon-folder-open"></span> Details
                  </button>
              </form>';
          }
          else{
            $cols_boutons='<button onclick="supprimer_Bon('.$bon['idBl'].')" class="btn btn-danger pull-right" style="margin-bottom:10px; margin-right:10px;"  >
            <span class="glyphicon glyphicon-remove"></span> Supprimer
            </button>';
          }

          if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
            $cols_details = "details_Livraison_PH";
            $cols_table='<table id="listeLivraison'.$bon['idBl'].'" class="table table-bordered" width="100%" border="1">
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
            </table>';
          }
          else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
            $cols_details = "details_Livraison_ET";
            $cols_table='<table id="listeLivraison'.$bon['idBl'].'" class="table table-bordered" width="100%" border="1">
              <thead>
                  <tr>
                      <th>Reference</th>
                      <th>Quantite</th>
                      <th>Unite Stock_(US)</th>
                      <th>Prix Achat</th>
                      <th>Montant Achat</th>
                      <th>Date Enregistre</th>
                      <th>Prix (US)</th>
                      <th>Prix Unitaire</th>
                      <th>Date Expiration</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
            </table>';
          }
          else{
            $cols_details = "details_Livraison";
            $cols_table='<table id="listeLivraison'.$bon['idBl'].'" class="table table-bordered" width="100%" border="1">
              <thead>
                  <tr>
                      <th>Reference</th>
                      <th>Quantite</th>
                      <th>Unite Stock_(US)</th>
                      <th>Prix Achat</th>
                      <th>Montant Achat</th>
                      <th>Date Enregistre</th>
                      <th>Prix Unitaire</th>
                      <th>Prix (US)</th>
                      <th>Date Expiration</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
            </table>';
          }


        }
        else {
          if($bon['idFournisseur']!=0){
            $cols_boutons='<button onclick="supprimer_Bon('.$bon['idBl'].')" class="btn btn-danger pull-right" style="margin-bottom:10px; margin-right:10px;"  >
            <span class="glyphicon glyphicon-remove"></span> Supprimer
            </button>
            <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" action="bonLivraison.php?id='.$bon['idBl'].'"  >
                <input type="hidden" name="idBl" id="idBl"  value="'.$bon['idBl'].'" />
                
                <button type="submit" class="btn btn-warning pull-right" >
                <span class="glyphicon glyphicon-folder-open"></span> Details
                </button>
            </form>';
          }
          else{
            $cols_boutons='<button onclick="supprimer_Bon('.$bon['idBl'].')" class="btn btn-danger pull-right" style="margin-bottom:10px; margin-right:10px;"  >
            <span class="glyphicon glyphicon-remove"></span> Supprimer
            </button>';
          }


            if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
              $cols_details = "details_Livraison_PH";
            }
            else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
              $cols_details = "details_Livraison_ET";
            }
            else {
              $cols_details = "details_Livraison";
            }

            $cols_table='';
        }

        if($bon['idFournisseur']!=0){
          if($bon['montantBl']==$bon['montantTotal']){
            $couleur='success';
          }
          else {
            $difference = $bon['montantBl'] - $bon['montantTotal'];
            $reste = $difference > 0 ? $difference : -$difference;
            if ($reste < 250){
              $couleur='info';
            }
            else{
              $couleur='danger';
            }
          }
        }
        else{
          $couleur='default';
        }

        echo '<div class="panel panel-'.$couleur.'" id="panelBon'.$bon['idBl'].'">
              <div class="panel-heading">
                <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#livraison'.$bon['idBl'].'" class="panel-title expand">
                  <div class="right-arrow pull-right">+</div>
                  <a href="#" onclick="'.$cols_details.'('.$bon['idBl'].')">  
                    <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier">BL</span></span>
                    <span class="hidden-xs hidden-sm hidden-md col-lg-3"><span class="mot_Total">DATE</span> : '.$bon['dateBl'].' '.$bon['heureBl'].' </span>
                    <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> <span class="mot_Total">TTL BON</span> : <span id="spn_ajouter_Bon_Total1'.$bon['idBl'].'" >'.number_format(($bon['montantBl'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                    <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">TTL CAL</span> : <span id="spn_ajouter_Bon_Total2'.$bon['idBl'].'" >'.number_format(($bon['montantTotal'] * $_SESSION['devise']), 0, ',', ' ').'</span></span> 
                    <span class="col-xs-5 col-sm-3 col-md-3 col-lg-1"> '.$nom_user.'</span>
                    <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2"> N: <span id="spn_ajouter_Bon_Numero'.$bon['idBl'].'" >'.$bon['numeroBl'].'</span></span>
                  </a>
                </h4>
              </div>
              <div id="livraison'.$bon['idBl'].'" class="panel-collapse collapse">
                <div class="content-panel" style="margin:10px;">
                    '.$cols_boutons.'
                    <table class="table table-bordered">
                        <thead class="noImpr">
                          <tr>
                              <th>Fournisseur</th>
                              <th>Numero</th>
                              <th>Description</th>
                              <th>Montant</th>
                              <th>Date BL</th>
                              <th>Date Echeance</th>
                              <th>Piece Jointe</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody>
                        <tr id="ancien_Bon'.$bon['idBl'].'">
                          <td>'.$nom_fournisseur.'</td>
                          <td>'.strtoupper($bon['numeroBl']).'</td>
                          <td>'.$bon['description'].'</td>
                          <td>'.$bon['montantBl'].'</td>
                          <td>'.$bon['dateBl'].'</td>
                          <td>'.$bon['dateEcheance'].'</td>
                          <td>'.$cols_image.'</td>
                          <td>
                              <button type="button" class="btn btn-warning pull-right" onclick="modifier_Bon('.$bon['idBl'].')" >
                                      <span class="glyphicon glyphicon-pencil"></span>
                              </button>
                          </td>
                        </tr>
                        <tr style="display:none" id="nouveau_Bon'.$bon['idBl'].'">
                          <form class="form-inline noImpr"  method="post" >
                              <td><select class="form-control champ_saisie_Ligne'.$bon['idBl'].'"  id="slct_Bon_Fournisseur_'.$bon['idBl'].'">'.$options.'</select></td>
                              <td><input type="text" class="form-control champ_saisie_Ligne'.$bon['idBl'].'" value="'.strtoupper($bon['numeroBl']).'" id="inpt_Bon_Numero_'.$bon['idBl'].'" onkeyup="modifier_Bon_Numero('.$bon['idBl'].')"  ></td>  
                              <td><textarea  type="textarea" class="form-control champ_saisie_Ligne'.$bon['idBl'].'" id="txtarea_Bon_Description_'.$bon['idBl'].'" placeholder="Description" >'.$bon['description'].'</textarea></td> 
                              <td><input type="number" class="form-control champ_saisie_Ligne'.$bon['idBl'].'" value="'.$bon['montantBl'].'" id="inpt_Bon_Montant_'.$bon['idBl'].'" onkeyup="modifier_Bon_Montant('.$bon['idBl'].')"  ></td>
                              <td><input type="date" value="'.$bon['dateBl'].'"  class="form-control champ_saisie_Ligne'.$bon['idBl'].'" id="inpt_Bon_Date_'.$bon['idBl'].'" onkeyup="modifier_format_DateBon('.$bon['idBl'].')" onchange="modifier_format_DateBon('.$bon['idBl'].')" ></td>
                              <td>'.$cols_dateEcheance.'</td>
                              <td>'.$cols_image.'</td>
                              <td>
                                  <input type="hidden" name="idBl" value="'.$bon['idBl'].'" id="inpt_Bon_IdBL'.$bon['idBl'].'" >
                                  <button type="button"  onclick="btn_modifier_Bon('.$bon['idBl'].')"  class="btn btn-success pull-right">
                                          <span class="glyphicon glyphicon-ok"></span>
                                  </button>
                              </td>
                          </form>
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
          <select class="form-control pull-left" id="slct_Nbre_ListerBons">
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


