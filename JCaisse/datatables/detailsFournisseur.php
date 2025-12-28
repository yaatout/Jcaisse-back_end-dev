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
          FROM
          (SELECT b.idFournisseur FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
          UNION ALL
          SELECT v.idFournisseur FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."' AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
          ) AS a ");
        $stmt->execute();
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
        
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);


        $stmtOperation = $bdd->prepare("SELECT *,CONCAT(dateBl,'',heureBl) AS dateHeure
          FROM
          (SELECT b.idFournisseur,b.idBl,b.dateBl,b.heureBl,CONCAT(b.idBl,'+1') as codeBl FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND b.dateBl BETWEEN '".$dateDebut."' AND '".$dateFin."'
          UNION 
          SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement,CONCAT(v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'  AND v.dateVersement BETWEEN '".$dateDebut."' AND '".$dateFin."'
          ) AS a ORDER BY dateHeure DESC LIMIT ".$page_position.",".$item_per_page." ");
        $stmtOperation->execute();
        $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC);

    } else {
        # code... 

        $stmt = $bdd->prepare("SELECT COUNT(*) AS allcount
          FROM
          (SELECT b.idFournisseur FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND (b.numeroBl LIKE :query OR b.montantBl LIKE :query OR b.dateBl LIKE :query)
          UNION ALL
          SELECT v.idFournisseur FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."' AND (v.montant LIKE :query OR v.dateVersement LIKE :query)
          ) AS a ");
        $stmt->execute(array(
          ':query' => '%'.$query.'%'
        ));
        $records = $stmt->fetch();
        $total_rows = $records['allcount'];
        
        $total_pages = ceil($total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);


        $stmtOperation = $bdd->prepare("SELECT *,CONCAT(dateBl,'',heureBl) AS dateHeure
          FROM
          (SELECT b.idFournisseur,b.idBl,b.dateBl,b.heureBl,CONCAT(b.idBl,'+1') as codeBl FROM `".$nomtableBl."` b where b.idFournisseur='".$idFournisseur."' AND (b.numeroBl LIKE :query OR b.montantBl LIKE :query OR b.dateBl LIKE :query)
          UNION 
          SELECT v.idFournisseur,v.idVersement,v.dateVersement,v.heureVersement,CONCAT(v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idFournisseur='".$idFournisseur."'  AND (v.montant LIKE :query OR v.dateVersement LIKE :query)
          ) AS a ORDER BY dateHeure DESC LIMIT ".$page_position.",".$item_per_page." ");
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

        $type = explode("+", $operation['codeBl']);

        if($type[1]==1){

          $stmtBL = $bdd->prepare("SELECT  * FROM `".$nomtableBl."` WHERE idBl =:idBl ");
          $stmtBL->execute(array(
              ':idBl' => $operation['idBl']
          ));
          $bon = $stmtBL->fetch(PDO::FETCH_ASSOC);

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


          if($bon['commande']==1){

            $stmtStock = $bdd->prepare("SELECT  COUNT(*) as nombre  FROM `".$nomtableStock."` WHERE idBl =:idBl ");
            $stmtStock->execute(array(
                ':idBl' => $operation['idBl']
            ));
            $stock = $stmtStock->fetch();
            $stock_rows = $stock['nombre'];

            if($stock_rows!=0){

              if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $cols_details = "details_Commande_PH";
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
              }
              else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $cols_details = "details_Commande_ET";
                $cols_table = '<table id="listeCommande'.$bon['idBl'].'" class="table table-bordered" width="100%" border="1">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Quantite</th>
                            <th>Unite Stock_(US)</th>
                            <th>Prix Achat</th>
                            <th>Prix (US)</th>
                            <th>Montant Achat</th>
                            <th>Date Enregistre</th>
                            <th>Date Expiration</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                ';
              }
              else{
                $cols_details = "details_Commande";
                $cols_table = '<table id="listeCommande'.$bon['idBl'].'" class="table table-bordered" width="100%" border="1">
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
                  </table>
                ';
              }

              $btn_valider='<button onclick="valider_Livraison('.$bon['idBl'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-success pull-pull-left" >
                <span class="glyphicon glyphicon-ok"></span> Valider
              </button>
              <button onclick="transferer_Bon('.$bon['idBl'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-warning pull-pull-left" >
                <span class="glyphicon glyphicon-transfer"></span> Transferer
              </button>';
            }
            else{
              $cols_table = '';
              if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {
                $cols_details = "details_Commande_PH";
              }
              else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
                $cols_details = "details_Commande_ET";
              }
              else {
                $cols_details = "details_Commande";
              }
              $btn_valider='<button onclick="transferer_Bon('.$bon['idBl'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-warning pull-pull-left" >
                <span class="glyphicon glyphicon-transfer"></span> Transferer
              </button>';
            }

            echo '<div class="panel panel-primary" id="panelBon'.$bon['idBl'].'">
                  <div class="panel-heading">
                    <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#commande'.$bon['idBl'].'" class="panel-title expand">
                      <div class="right-arrow pull-right">+</div>
                      <a href="#" onclick="'.$cols_details.'('.$bon['idBl'].')">  
                        <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier">BC</span></span>
                        <span class="hidden-xs hidden-sm hidden-md col-lg-3"><span class="mot_Total">DATE</span> : '.$bon['dateBl'].' '.$bon['heureBl'].' </span>
                        <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> <span class="mot_Total">TTL BON</span> : <span id="spn_ajouter_Bon_Total1'.$bon['idBl'].'" >'.number_format(($bon['montantBl'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                        <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">TTL CAL</span> : <span id="spn_ajouter_Bon_Total2'.$bon['idBl'].'" >'.number_format(($bon['montantTotal'] * $_SESSION['devise']), 0, ',', ' ').'</span></span> 
                        <span class="col-xs-5 col-sm-3 col-md-3 col-lg-1"> '.$nom_user.'</span>
                        <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2"> N: <span id="spn_ajouter_Bon_Numero'.$bon['idBl'].'" >'.$bon['numeroBl'].'</span></span>
                      </a>
                    </h4>
                  </div>
                  <div id="commande'.$bon['idBl'].'" class="panel-collapse collapse">
                    <div class="content-panel" style="margin:10px;">
                        <button style="margin-bottom:10px; margin-right:10px;" onclick="supprimer_Bon('.$bon['idBl'].')" class="btn btn-danger pull-right" >
                          <span class="glyphicon glyphicon-remove"></span> Supprimer
                        </button>
                        <button style="margin-bottom:10px; margin-right:10px;" type="button" class="btn btn-round btn-info pull-right" onclick="facture_Bon('.$bon['idBl'].')"> <span class="mot_Facture">Facture</span></button>
                        <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" action="bonCommande.php?id='.$bon['idBl'].'"  >
                            <input type="hidden" name="idBl" id="idBl"  value="'.$bon['idBl'].'" />
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
                            <tr id="ancien_Bon'.$bon['idBl'].'">
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
                                  <td style="display:none"><input type="text" class="form-control champ_saisie_Ligne'.$bon['idBl'].'" value="'.strtoupper($bon['numeroBl']).'" id="inpt_Bon_Numero_'.$bon['idBl'].'" onkeyup="modifier_Bon_Numero('.$bon['idBl'].')"  ></td>  
                                  <td><textarea  type="textarea" class="form-control champ_saisie_Ligne'.$bon['idBl'].'" id="txtarea_Bon_Description_'.$bon['idBl'].'" placeholder="Description" >'.$bon['description'].'</textarea></td> 
                                  <td><input type="number" class="form-control champ_saisie_Ligne'.$bon['idBl'].'" value="'.$bon['montantBl'].'" id="inpt_Bon_Montant_'.$bon['idBl'].'" onkeyup="modifier_Bon_Montant('.$bon['idBl'].')"  ></td>
                                  <td><input type="date" value="'.$bon['dateBl'].'"  class="form-control champ_saisie_Ligne'.$bon['idBl'].'" id="inpt_Bon_Date_'.$bon['idBl'].'" onkeyup="modifier_format_DateBon('.$bon['idBl'].')" onchange="modifier_format_DateBon('.$bon['idBl'].')" ></td>
                                  <td>'.$cols_dateEcheance.'</td>
                                  <td>'.$cols_image.'</td>
                                  <td>
                                      <input type="hidden" name="idBl" value="'.$bon['idBl'].'" id="inpt_Bon_IdBL'.$bon['idBl'].'" />
                                      <button type="submit"  onclick="btn_modifier_Bon('.$bon['idBl'].')"  class="btn btn-success pull-right">
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
          }
          else {

            $stmtStock = $bdd->prepare("SELECT  COUNT(*) as nombre  FROM `".$nomtableStock."` WHERE idBl =:idBl ");
            $stmtStock->execute(array(
                ':idBl' => $operation['idBl']
            ));
            $stock = $stmtStock->fetch();
            $stock_rows = $stock['nombre'];

            if($stock_rows!=0){
              $cols_boutons='<button onclick="transferer_Bon('.$bon['idBl'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-warning pull-pull-left" >
                <span class="glyphicon glyphicon-transfer"></span> Transferer
              </button>
              <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" action="bonLivraison.php?id='.$bon['idBl'].'"  >
                    <input type="hidden" name="idBl" id="idBl"  value="'.$bon['idBl'].'" />
                    
                    <button type="submit" class="btn btn-warning pull-right" >
                    <span class="glyphicon glyphicon-folder-open"></span> Details
                    </button>
                </form>';

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
              $cols_boutons='<button onclick="transferer_Bon('.$bon['idBl'].')" style="margin-bottom:10px; margin-left:10px;" class="btn btn-warning pull-pull-left" >
                <span class="glyphicon glyphicon-transfer"></span> Transferer
              </button>
              <button onclick="supprimer_Bon('.$bon['idBl'].')" class="btn btn-danger pull-right" style="margin-bottom:10px; margin-right:10px;"  >
                <span class="glyphicon glyphicon-remove"></span> Supprimer
                </button>
                <form class="form-inline pull-right noImpr" style="margin-bottom:10px; margin-right:10px;" method="post" action="bonLivraison.php?id='.$bon['idBl'].'"  >
                    <input type="hidden" name="idBl" id="idBl"  value="'.$bon['idBl'].'" />
                    
                    <button type="submit" class="btn btn-warning pull-right" >
                    <span class="glyphicon glyphicon-folder-open"></span> Details
                    </button>
                </form>';

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

            echo '<div class="panel panel-'.$couleur.'" id="panelBon'.$bon['idBl'].'">
                  <div class="panel-heading">
                    <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#livraison'.$bon['idBl'].'" class="panel-title expand">
                      <div class="right-arrow pull-right">+</div>
                      <a href="#" onclick="'.$cols_details.'('.$bon['idBl'].')">  
                        <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier">BL</span></span>
                        <span class="hidden-xs hidden-sm hidden-md col-lg-3"><span class="mot_Total">DATE</span> : '.$bon['dateBl'].' '.$bon['heureBl'].' </span>
                        <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> <span class="mot_Total">TTL BON </span> : <span id="spn_ajouter_Bon_Total1'.$bon['idBl'].'" >'.number_format(($bon['montantBl'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
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
          }
        }
        else if($type[1]==2){

          $stmtVersement = $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` WHERE idVersement =:idVersement ");
          $stmtVersement->execute(array(
              ':idVersement' => $operation['idBl']
          ));
          $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);

          $stmtUser = $bdd->prepare("SELECT  * FROM `aaa-utilisateur` 
          WHERE idutilisateur=:iduser ");
          $stmtUser->execute(array(
              ':iduser' => $versement['iduser']
          ));
          $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
          $nom_user = substr(strtoupper($user['prenom']),0,3);

          if($versement['dateVersement']!='' && $versement['dateVersement']!=null){
            $tab=explode("-",$versement['dateVersement']);
            $dateVersement=$tab[2].'-'.$tab[1].'-'.$tab[0];
          }
          else {
            $dateVersement='';
          } 

          if ($versement['iduser']==$_SESSION['iduser']){
            $cols_boutons='<button type="submit" class="btn btn-danger pull-right" style="margin-bottom:10px; margin-right:10px;" onclick="supprimer_Versement('.$versement['idVersement'].')" >
                <span class="glyphicon glyphicon-remove"></span> Supprimer
              </button>
              <button disabled="true" style="margin-bottom:10px; margin-right:10px;" type="button" onclick="facture_Versement('.$versement['idVersement'].')" class="btn btn-round btn-info pull-right"> <span class="mot_Facture">Facture</span></button>
              <button disabled="true" style="margin-bottom:10px; margin-right:10px;" type="button" onclick="ticket_Versement('.$versement['idVersement'].')" class="btn btn-round btn-warning pull-right"> <span class="mot_Ticket">Ticket</span></button>';
          }
          else {
            $cols_boutons='<button disabled="true" style="margin-bottom:10px; margin-right:10px;" type="button" onclick="facture_Versement('.$versement['idVersement'].')" class="btn btn-round btn-info pull-right"> <span class="mot_Facture">Facture</span></button>
              <button disabled="true" style="margin-bottom:10px; margin-right:10px;" type="button" onclick="ticket_Versement('.$versement['idVersement'].')" class="btn btn-round btn-warning pull-right"> <span class="mot_Ticket">Ticket</span></button>';
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

          $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` 
          WHERE idCompte<>2 and idCompte<>3 ORDER BY idCompte ");
          $stmtCompte->execute();
          $comptes = $stmtCompte->fetchAll(PDO::FETCH_ASSOC);
          $option =''; $options =''; $type_image=''; $nom_compte='';
          foreach ($comptes as $compte) {
            if($versement['idCompte']==$compte['idCompte']){
              $nom_compte = $compte['nomCompte'];
              if($compte['typeCompte']=='Wave'){
                $type_image='Wave';
              }
              else if($compte['typeCompte']=='Orange Money'){
                $type_image='OrangeMoney';
              }
              else {
                $type_image='Especes';
              }
              $option = '<option  value= "'.$compte['idCompte'].'">'.$compte['nomCompte'].'</option>';
            }
            else{
              $options = $options.'<option  value= "'.$compte['idCompte'].'">'.$compte['nomCompte'].'</option>';
            }
          }

          echo '<div class="panel panel-warning" id="panelVersement'.$versement['idVersement'].'">
              <div class="panel-heading">
                <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#versement'.$versement['idVersement'].'" class="panel-title expand">
                  <div class="right-arrow pull-right">+</div>
                  <div class="right-arrow pull-right"><img src="images/'.$type_image.'.ico" width="20px" /></div>
                  <a href="#" onclick="detailsVente('.$versement['idVersement'].')">  
                      <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier">VE</span></span>
                      <span class="hidden-xs hidden-sm hidden-md col-lg-3"><span class="mot_Total">DATE</span> : '.$versement['dateVersement'].' '.$versement['heureVersement'].' </span>
                      <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> <span class="mot_Total">TOTAL</span> : <span id="spn_ajouter_Versement_Total'.$versement['idVersement'].'" >'.number_format(($versement['montant'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                      <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">TOTAL</span> : <span id="spn_ajouter_Versement_APayer'.$versement['idVersement'].'" >'.number_format(($versement['montant'] * $_SESSION['devise']), 0, ',', ' ').'</span></span> 
                      <span class="col-xs-5 col-sm-3 col-md-3 col-lg-1"> '.$nom_user.'</span>
                      <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2">Recu : #'.$versement['idVersement'].'</span>
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
                            <th>Compte</th>
                            <th>Date</th>
                            <th>Piece Jointe</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="ancien_Versement'.$versement['idVersement'].'">
                          <td>'.$versement['paiement'].'</td>
                          <td>'.$versement['montant'].'</td>
                          <td>'.$nom_compte.'</td>
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
                            <td><textarea  type="textarea" id="txtarea_Versement_Description_'.$versement['idVersement'].'" class="form-control champ_saisie_Ligne'.$versement['idVersement'].'" name="paiement" placeholder="Description" >'.$versement['paiement'].'</textarea></td> 
                            <td><input type="number" id="inpt_Versement_Montant_'.$versement['idVersement'].'" class="form-control champ_saisie_Ligne'.$versement['idVersement'].'" value="'.$versement['montant'].'" onkeyup="modifier_Versement_Montant('.$versement['idVersement'].')"  ></td>
                            <td>
                              <select class="form-control champ_saisie_Ligne'.$versement['idVersement'].'" id="slct_Versement_Compte_'.$versement['idVersement'].'"  style="width: 150px" >
                              '.$option.' '.$options.'
                              </select>
                            </td>
                            <td><input type="date" id="inpt_Versement_Date_'.$versement['idVersement'].'" value="'.$versement['dateVersement'].'"  class="form-control champ_saisie_Ligne'.$versement['idVersement'].'" onkeyup="modifier_format_DateVersement('.$versement['idVersement'].')" onchange="modifier_format_DateVersement('.$versement['idVersement'].')" ></td>
                            <td>'.$cols_image.'</td>
                            <td>
                                <input type="hidden" name="idVersement" value="'.$versement['idVersement'].'" >
                                <button type="submit" class="btn btn-success pull-right" onclick="btn_modifier_Versement('.$versement['idVersement'].')" id="btn_modifier_Versement'.$versement['idVersement'].'">
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


