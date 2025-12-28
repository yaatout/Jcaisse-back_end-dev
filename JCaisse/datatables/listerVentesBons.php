<?php
   session_start();

   if(!$_SESSION['iduser']){

   header('Location:../JCaisse/index.php');

   }

   require('../connection.php');
   require('../connectionPDO.php');
   require('../declarationVariables.php');

try{

    $idClient = @$_POST['id'];
    $dateDebut = @$_POST['dateDebut'];
    $dateFin = @$_POST['dateFin'];

    $nbre_Entree = @$_POST["nbre_Entree"];
    $query = @$_POST["query"];
    $item_per_page = ($nbre_Entree!=0) ? $nbre_Entree : 10; //item to display per page
    $page_number 		= 0; //page number
    $produits = array();
    $tabIdDesigantion = array();
    $tabIdStock = array();
      
    //Get page number from Ajax
    if(isset($_POST["page"])){
        $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
        if(!is_numeric($page_number)){die('Numéro de page invalide!');} //incase of invalid page number
    }else{
        $page_number = 1; //if there's no page number, set it to 1
    }
        
    if ($query =="") {
        # code...

        $stmtOperation = $bdd->prepare("SELECT DISTINCT * ,CONCAT(codePanier) AS dateHeure
          FROM
          (SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') as codePanier FROM `".$nomtablePagnet."` p where p.idClient=:idClient AND p.type<>2 
          AND ( (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN :dateDebut AND :dateFin)  or (p.datepagej BETWEEN :dateDebut AND :dateFin) )
          UNION 
          SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idClient=:idClient  
          AND ( (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN :dateDebut AND :dateFin) or (v.dateVersement BETWEEN :dateDebut AND :dateFin) )
          ) AS a ORDER BY dateHeure DESC");
        $stmtOperation->execute(array(
          ':idClient' => $idClient,
          ':dateDebut' => $dateDebut,
          ':dateFin' => $dateFin
        ));
        $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC); 

    } else {
        # code... 
          $stmtOperation = $bdd->prepare("SELECT DISTINCT * ,CONCAT(codePanier) AS dateHeure
            FROM
            (SELECT CONCAT(SUBSTR(p.datepagej,7, 4),'',SUBSTR(p.datepagej,4, 2),'',SUBSTR(p.datepagej,1, 2),'',p.heurePagnet,'+',p.idPagnet,'+1') as codePanier FROM `".$nomtablePagnet."` p where p.idClient=:idClient AND p.type<>2 AND (p.idPagnet LIKE :query OR p.datepagej LIKE :query OR p.apayerPagnet LIKE :query)
            AND ( (CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN :dateDebut AND :dateFin)  or (p.datepagej BETWEEN :dateDebut AND :dateFin) )
            UNION 
            SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where v.idClient=:idClient AND (v.idVersement LIKE :query OR v.dateVersement LIKE :query OR v.montant LIKE :query)
            AND ( (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN :dateDebut AND :dateFin) or (v.dateVersement BETWEEN :dateDebut AND :dateFin) )
            ) AS a ORDER BY dateHeure DESC");
          $stmtOperation->execute(array(
            ':idClient' => $idClient,
            ':dateDebut' => $dateDebut,
            ':dateFin' => $dateFin,
            ':query' => '%'.$query.'%'
          ));
          $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC); 
        
    }
      
    //get total number of records 
    $total_rows = count($operations);
    
    $total_pages = ceil($total_rows/$item_per_page);
    
    //position of records
    $page_position = (($page_number-1) * $item_per_page);
      
    $i = ($page_number - 1) * $item_per_page +1;
    $cpt = $i;
    $panier_NT = 0;
      
    $operations = array_slice($operations, $page_position, $item_per_page);
    //var_dump($operations);

    echo '<div class="panel-group" id="accordion" style="margin-top:5px;">';

    foreach ($operations as $operation) {

      $type = explode("+", $operation['dateHeure']);

      if($type[2]==1){
        $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` 
        WHERE idPagnet = :idPagnet ");
        $stmtPanier->execute(array(
            ':idPagnet' => $type[1]
        ));
        $vente = $stmtPanier->fetch(PDO::FETCH_ASSOC);

        $stmtUser = $bdd->prepare("SELECT  * FROM `aaa-utilisateur` 
        WHERE idutilisateur=:iduser ");
        $stmtUser->execute(array(
            ':iduser' => $vente['iduser']
        ));
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $nom_user = substr(strtoupper($user['prenom']),0,3);
        
        if($vente){
          if($vente['verrouiller']==0){
            if($_SESSION['iduser']==$vente['iduser']){
              $form_Btn='
              <form class="form-inline noImpr" method="post" id="form_ajouter_Panier_Btn_'.$vente['idPagnet'].'">
                  <input type="number" step="5" id="inpt_ajouter_Panier_Remise_'.$vente['idPagnet'].'" name="inpt_ajouter_Panier_Remise" class="remise form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Remise" placeholder="Remise...." >
                  <input  type="number" step="5" class="versement form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Bon'.$vente['idPagnet'].' champ_saisie_Client'.$vente['idPagnet'].' inpt_ajouter_Panier_Avance" placeholder="Avance..."  autocomplete="off" id="inpt_ajouter_Panier_Avance_'.$vente['idPagnet'].'" />
                  <select style="display:none" class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Compte'.$vente['idPagnet'].' champ_saisie_Client'.$vente['idPagnet'].'" id="slct_ajouter_Bon_Compte_'.$vente['idPagnet'].'"> </select>
                  <input style="display:none" type="number" name="frais" id="inpt_ajouter_Panier_Frais_'.$vente['idPagnet'].'"  placeholder="Montant Frais..." class="versement form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Frais'.$vente['idPagnet'].'">
                  <button tabindex="1" type="button"  onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-danger pull-right" id="btn_retourner_Panier'.$vente['idPagnet'].'"  data-toggle="modal" data-target="#msg_ann_pagnet'.$vente['idPagnet'].'" >
                      <span class="glyphicon glyphicon-remove"></span>
                  </button>
                  <button type="button" onclick="valider_Panier('.$vente['idPagnet'].')" data-idPanier="'.$vente['idPagnet'].'" id="btn_valider_Panier'.$vente['idPagnet'].'" class="btn btn-success btn_valider_Panier'.$vente['idPagnet'].' pull-right" style="margin-right:2px;">
                      <span class="glyphicon glyphicon-ok"></span>
                  </button>
              </form>
              ';
          
              if($vente['type']==0){
                $couleur='success';
              }
              else if($vente['type']==6){
                $couleur='default';
              }
              else if($vente['type']==10){
                $couleur='warning';
              }
          
              echo '<div class="panel panel-primary" id="panelPanier'.$vente['idPagnet'].'" panier-type="'.$vente['type'].'" panier-compte="'.$vente['idCompte'].'" panier-client="'.$vente['idClient'].'" panier-verrouiller="'.$vente['verrouiller'].'">
                    <div class="panel-heading">
                      <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#'.$vente['idPagnet'].'" class="panel-title expand">
                        <div class="right-arrow pull-right">+</div>
                        <a href="#" onclick="detailsVente('.$vente['idPagnet'].')">  
                          <span class="hidden-xs hidden-sm col-md-2 col-lg-1"><span class="mot_Panier">N</span> '.$cpt.'</span>
                          <span class="hidden-xs col-sm-4 col-md-3 col-lg-3"><span class="mot_Heure">Date</span> : '.$vente['datepagej'].' '.$vente['heurePagnet'].'</span>
                          <span class="hidden-xs hidden-sm hidden-md col-lg-2"><span class="mot_Total">Total</span> : <span id="spn_ajouter_Panier_Total'.$vente['idPagnet'].'">'.number_format(($vente['totalp'] * $_SESSION['devise']), 0, ',', ' ').'</span> </span>
                          <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> <span class="mot_Total">Net HT</span> : <span id="spn_ajouter_Panier_APayer'.$vente['idPagnet'].'">'.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                        </a>
                      </h4>
                    </div>
          
                    <div id="'.$vente['idPagnet'].'" class="panel-collapse collapse">
                      <br/>
                      <div class="content-panel" style="margin-left:12px;margin-right:12px">
                        <div class="cacher_btn_Terminer col-lg-11 col-md-11" id="cacher_btn_Terminer'.$vente['idPagnet'].'">
                          <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="form_ajouter_Panier_Ligne_'.$vente['idPagnet'].'" onsubmit="return false" >
                              <input type="text" data-type="reference" class="inputbasic form-control inpt_ajouter_Panier_Ligne" name="codeBarre" id="inpt_ajouter_Panier_Ligne_'.$vente['idPagnet'].'" data-idPanier="'.$vente['idPagnet'].'" style="width:100%;" autocomplete="off" />
                          </form>
                          <div class="col-md-8 col-sm-8 col-xs-12 content2">
                            '.$form_Btn.'
                          </div>
                        </div>
                        <table class="table table-hover" id="tablePanier'.$vente['idPagnet'].'">
                            <h4 style="display:none" class="pull-right" id="afficher_btn_Ticket'.$vente['idPagnet'].'">
                              <button type="button" class="btn btn-round btn-warning" onclick="ticket_Panier('.$vente['idPagnet'].')"> <span class="mot_Ticket">Ticket</span></button>
                              <button type="button" class="btn btn-round btn-info" onclick="facture_Panier('.$vente['idPagnet'].')"> <span class="mot_Facture">Facture</span></button>
                              <button type="button" onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-danger" id="btn_retourner_Panier'.$vente['idPagnet'].'" style="margin-right: 5px;" data-toggle="modal" data-target=""> <span class="mot_Retourner">Retourner</span></button>
                            </h4>
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
                        <div style="display:none" id="afficher_Paiement'.$vente['idPagnet'].'">
                          <h5><b>Total panier</b> : <span id="spn_ajouter_Panier_Paiement_Total'.$vente['idPagnet'].'"> '.$_SESSION['symbole'].'</span></h5>
                          <h5><b>Remise</b> : <span id="spn_ajouter_Panier_Paiement_Remise'.$vente['idPagnet'].'"> '.$_SESSION['symbole'].'</span></h5>
                          <h5><b>Net HT</b> : <span id="spn_ajouter_Panier_Paiement_APayer'.$vente['idPagnet'].'"> '.$_SESSION['symbole'].'</span></h5>
                        </div>
                      </div>
                    </div>
              </div>';
          
              $panier_NT++;
            }
          }
          else { 
            if($_SESSION['iduser']==$vente['iduser']){
              $btn_retourner='<button type="button" onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-danger" id="btn_retourner_Panier'.$vente['idPagnet'].'" style="margin-right: 5px;" data-toggle="modal" data-target=""> <span class="mot_Retourner">Retourner</span></button>';
            }
            else{
              $btn_retourner='<button type="button" disabled="true" onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-danger" id="btn_retourner_Panier'.$vente['idPagnet'].'" style="margin-right: 5px;" data-toggle="modal" data-target=""> <span class="mot_Retourner">Retourner</span></button>';
            }
          
            if($_SESSION['compte']==1){
              $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` 
              WHERE idCompte = :idCompte ");
              $stmtCompte->execute(array(
                  ':idCompte' => $vente['idCompte']
              ));
              $compte = $stmtCompte->fetch(PDO::FETCH_ASSOC);
              $type = ($compte!=null) ? ($compte['nomCompte']) : ('Caisse');
              $div_footer='
                <div>
                  <h5><b>Total panier</b> : '.$vente['totalp'].' '.$_SESSION['symbole'].'</h5>
                  <h5><b>Remise</b>  : '.$vente['remise'].' '.$_SESSION['symbole'].'</h5>
                  <h5><b>Net HT</b>  : '.$vente['apayerPagnet'].' '.$_SESSION['symbole'].'</h5>
                </div>
              ';
            }
            else{
              $div_footer='
                <div>
                  <h5><b>Total panier</b> : '.$vente['totalp'].' '.$_SESSION['symbole'].'</h5>
                  <h5><b>Remise</b>  : '.$vente['remise'].' '.$_SESSION['symbole'].'</h5>
                  <h5><b>Net HT</b>  : '.$vente['apayerPagnet'].' '.$_SESSION['symbole'].'</h5>
                </div>
              ';
            }
                        
            if($vente['type']==0 || $vente['type']==30){
              $couleur='success';
            }
            else if($vente['type']==6 || $vente['type']==9){
              $couleur='info';
            }
            else if($vente['type']==10){
              $couleur='warning';
            }
            else if($vente['type']==11){
              $couleur='default';
            }
            else{
              $couleur='default';
            }
            echo '<div class="panel panel-'.$couleur.'" id="panelPanier'.$vente['idPagnet'].'" panier-type="'.$vente['type'].'" panier-compte="'.$vente['idCompte'].'" panier-client="'.$vente['idClient'].'" panier-verrouiller="'.$vente['verrouiller'].'">
                  <div class="panel-heading">
                    <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#'.$vente['idPagnet'].'" class="panel-title expand">
                      <div class="right-arrow pull-right">+</div>
                      <a href="#" onclick="detailsVente('.$vente['idPagnet'].')">  
                        <span class="hidden-xs hidden-sm col-md-2 col-lg-1"><span class="mot_Panier">N</span> '.$cpt.'</span>
                        <span class="hidden-xs col-sm-4 col-md-3 col-lg-3"><span class="mot_Heure">Date</span> : '.$vente['datepagej'].' '.$vente['heurePagnet'].'</span>
                        <span class="hidden-xs hidden-sm hidden-md col-lg-2"><span class="mot_Total">Total</span> <span class="mot_Panier"></span> : '.number_format(($vente['totalp'] * $_SESSION['devise']), 0, ',', ' ').'</span>
                        <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> <span class="mot_Total">Net HT</span> <span class="mot_à"></span> <span class="mot_Payer"></span> : '.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span>
                        <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2 pull-right"><span class="mot_Facture">Ticket</span> : '.$vente['idPagnet'].'</span>
                        <span class="hidden-xs hidden-sm hidden-md col-lg-1"> '.$nom_user.'</span>
                      </a>
                    </h4>
                  </div>
                  <div id="'.$vente['idPagnet'].'" class="panel-collapse collapse">
                    <div class="content-panel" style="margin-left:12px;margin-right:12px">
                      <table class="table table-hover" id="tablePanier'.$vente['idPagnet'].'">
                        <h4 class="pull-right">
                          <button type="button" onclick="ticket_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-warning"> <span class="mot_Ticket">Ticket</span></button>
                          <button type="button" onclick="facture_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-info"> <span class="mot_Facture">Facture</span></button>
                          '.$btn_retourner.'
                        </h4>
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
                        '.$div_footer.'
                    </div>
                  </div>
            </div>';
          }
        }
      }
      else{

        $stmtVersement = $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` WHERE idVersement =:idVersement ");
        $stmtVersement->execute(array(
            ':idVersement' => $type[1]
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);

        $stmtUser = $bdd->prepare("SELECT  * FROM `aaa-utilisateur` 
        WHERE idutilisateur=:iduser ");
        $stmtUser->execute(array(
            ':iduser' => $versement['iduser']
        ));
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $nom_user = substr(strtoupper($user['prenom']),0,3);

        if($versement){

          $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` 
          WHERE idCompte = :idCompte ");
          $stmtCompte->execute(array(
              ':idCompte' => $versement['idCompte']
          ));
          $compte = $stmtCompte->fetch(PDO::FETCH_ASSOC);
          $type = ($compte!=null) ? ($compte['nomCompte']) : ('Caisse');

          if($versement['dateVersement']!='' && $versement['dateVersement']!=null){
            $tab=explode("-",$versement['dateVersement']);
            $dateVersement=$tab[2].'-'.$tab[1].'-'.$tab[0];
          }
          else {
            $dateVersement='';
          } 
  
          if ($versement['iduser']==$_SESSION['iduser']){
            $btn_retourner='<button type="button" onclick="retourner_Versement('.$versement['idVersement'].')" class="btn btn-round btn-danger" id="btn_retourner_Versement'.$versement['idVersement'].'" style="margin-right: 5px;" data-toggle="modal" data-target=""> <span class="mot_Retourner">Retourner</span></button>';
          }
          else {
            $btn_retourner='<button type="button" disabled="true" onclick="retourner_Versement('.$versement['idVersement'].')" class="btn btn-round btn-danger" id="btn_retourner_Versement'.$versement['idVersement'].'" style="margin-right: 5px;" data-toggle="modal" data-target=""> <span class="mot_Retourner">Retourner</span></button>';
          }
  
          if($versement['image']!=null && $versement['image']!=' '){
              $format=substr($versement['image'], -3); 
              if($format=='pdf'){ 
                  $cols_image='<img class="btn btn-xs" src="images/pdf.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#image_Versement'.$versement['idVersement'].'"  onclick="showImageVersement('.$versement['idVersement'].')" 	 />
                  <input style="display:none;" data-image="'.$versement['idVersement'].'"  id="imageVersement'.$versement['idVersement'].'"  value="'.$versement['image'].'" />';
              }
              else { 
                $cols_image='<img class="btn btn-xs" src="images/img.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#image_Versement'.$versement['idVersement'].'"  onclick="showImageVersement('.$versement['idVersement'].')" 	 />
                <input style="display:none;" data-image="'.$versement['idVersement'].'"  id="imageVersement'.$versement['idVersement'].'"  value="'.$versement['image'].'" />';
              }
          }
          else { 
              $cols_image='<img class="btn btn-xs" src="images/upload.png" align="middle" alt="apperçu" width="50" height="45" data-toggle="modal" data-target="#image_Versement'.$versement['idVersement'].'"  onclick="showImageVersement('.$versement['idVersement'].')" 	 />
              <input style="display:none;" data-image="'.$versement['idVersement'].'"  id="imageVersement'.$versement['idVersement'].'"  value="'.$versement['image'].'" />';
          }
  
            echo '<div class="panel panel-warning" id="panelVersement'.$versement['idVersement'].'" versement-compte="'.$versement['idCompte'].'">
                <div class="panel-heading">
                  <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#versement'.$versement['idVersement'].'" class="panel-title expand">
                    <div class="right-arrow pull-right">+</div>
                    <a href="#" onclick="detailsVersement('.$versement['idVersement'].')">  
                      <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier">N '.$cpt.'</span></span>
                      <span class="hidden-xs hidden-sm col-md-3 col-lg-3"><span class="mot_Total">Date</span> : '.$versement['dateVersement'].' '.$versement['heureVersement'].' </span>
                      <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> Montant : <span id="spn_ajouter_Versement_Total'.$versement['idVersement'].'" >'.number_format(($versement['montant'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                      <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> Net HT : <span id="spn_ajouter_Versement_APayer'.$versement['idVersement'].'" >'.number_format(($versement['montant'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                      <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2 pull-right"><span class="mot_Facture">Ticket</span> : '.$versement['idVersement'].'</span>
                      <span class="hidden-xs hidden-sm hidden-md col-lg-1"> '.$nom_user.'</span>
                    </a>
                  </h4>
                </div>
  
                <div id="versement'.$versement['idVersement'].'" class="panel-collapse collapse">
                  <div class="content-panel" style="margin:10px;">
                    <table class="table table-bordered">
                        <h4 class="pull-right">
                          <button type="button" onclick="ticket_Versement('.$versement['idVersement'].')" class="btn btn-round btn-warning"> <span class="mot_Ticket">Ticket</span></button>
                          <button type="button" onclick="facture_Versement('.$versement['idVersement'].')" class="btn btn-round btn-info"> <span class="mot_Facture">Facture</span></button>
                          '.$btn_retourner.'
                        </h4>
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
                            <td>'.$type.'</td>
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
                              <td><input type="number" id="inpt_Versement_Montant_'.$versement['idVersement'].'" class="form-control champ_saisie_Ligne'.$versement['idVersement'].'" value="'.$versement['montant'].'" onkeyup="modifier_Montant('.$versement['idVersement'].')" onchange="modifier_Montant('.$versement['idVersement'].')" style="width: 150px" ></td>
                              <td><select id="slct_Versement_Compte_'.$versement['idVersement'].'" onchange="modifier_Compte(this.value)" class="form-control champ_saisie_Ligne'.$versement['idVersement'].'"  style="width: 150px" ></select></td>
                              <td><input type="text" id="inpt_Versement_Date_'.$versement['idVersement'].'" value="'.$dateVersement.'"  class="form-control champ_saisie_Ligne'.$versement['idVersement'].'"  name="dateVersement" placeholder="jj-mm-aaaa" required=""></td>
                              <td>'.$cols_image.'</td>
                              <td>
                                  <input type="hidden" name="idVersement" value="'.$versement['idVersement'].'" >
                                  <button type="button" class="btn btn-success pull-right" onclick="valider_Versement('.$versement['idVersement'].')">
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

      }

      $cpt++;

    }
    echo '</div>';

    if ($total_rows>$item_per_page) {
      echo '<div class="">';
            echo '<div class="col-md-1"> 
              <select class="form-control pull-left" id="slct_Nbre_ListerVentes">
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

<script>
    $(document).ready(function() {    
      nombre_Panier_NT = <?php echo json_encode($panier_NT); ?>;
      if(nombre_Panier_NT>=2){
          $("#btn_Ajouter_Panier").prop("disabled", true);
      }
    });
</script>

