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
          (SELECT CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') as codePanier, p.verrouiller as verrouiller FROM `".$nomtablePagnet."` p where p.type<>2 
          AND  p.datepagej=:dateJour
          UNION 
          SELECT CONCAT(v.heureVersement,'+',v.idVersement,'+2') as codeVersement, v.verrouiller  as verrouiller FROM `".$nomtableVersement."` v where
           ( (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2))=:dateAnnee) or (v.dateVersement=:dateAnnee) )
          ) AS a ORDER BY verrouiller ASC, dateHeure DESC");
        $stmtOperation->execute(array(
          ':dateJour' => $dateString2,
          ':dateAnnee' => $dateString,
        ));
        $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC); 

    } else {
        # code... 
          $stmtOperation = $bdd->prepare("SELECT DISTINCT * ,CONCAT(codePanier) AS dateHeure
            FROM
            (SELECT CONCAT(p.heurePagnet,'+',p.idPagnet,'+1') as codePanier FROM `".$nomtablePagnet."` p where p.type<>2 AND (p.idPagnet LIKE :query OR p.datepagej LIKE :query OR p.apayerPagnet LIKE :query)
            AND p.datepagej=:dateJour
            UNION 
            SELECT CONCAT(SUBSTR(v.dateVersement,7, 4),'',SUBSTR(v.dateVersement,4, 2),'',SUBSTR(v.dateVersement,1, 2),'',v.heureVersement,'+',v.idVersement,'+2') as codeVersement FROM `".$nomtableVersement."` v where (v.idVersement LIKE :query OR v.dateVersement LIKE :query OR v.montant LIKE :query)
             AND ( (CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2))=:dateAnnee) or (v.dateVersement=:dateAnnee) )
            ) AS a ORDER BY dateHeure DESC");
          $stmtOperation->execute(array(
            ':dateJour' => $dateString2,
            ':dateAnnee' => $dateString,
            ':query' => '%'.$query.'%'
          ));
          $operations = $stmtOperation->fetchAll(PDO::FETCH_ASSOC); 
        
    }
      
    //get total number of records 
    $total_rows = count($operations);
    
    $total_pages = ceil($total_rows/$item_per_page);

    $page_position = (($page_number-1) * $item_per_page);
      
    $i = ($page_number - 1) * $item_per_page +1;
    $cpt = $i;
    $panier_NT = 0;
      
    $operations = array_slice($operations, $page_position, $item_per_page);

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

        if($vente['verrouiller']==0){
          if($_SESSION['iduser']==$vente['iduser']){
            if($_SESSION['compte']==1){

              $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` 
              WHERE idCompte = :idCompte ");
              $stmtCompte->execute(array(
                  ':idCompte' => $vente['idCompte']
              ));
              $compte = $stmtCompte->fetch(PDO::FETCH_ASSOC);

              if($vente['idClient']!=0){
                $stmtClient = $bdd->prepare("SELECT  * FROM `".$nomtableClient."` 
                WHERE idClient = :idClient ");
                $stmtClient->execute(array(
                    ':idClient' => $vente['idClient']
                ));
                $client = $stmtClient->fetch(PDO::FETCH_ASSOC);
                $inpt_Client = $client['idClient'].'. '.$client['nom'].' '.$client['prenom'].' '.$client['adresse'];
  
                $frais='<select style="display:none" name="frais" id="slct_ajouter_Panier_Frais_'.$vente['idPagnet'].'" class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Frais'.$vente['idPagnet'].' "> 
                  <option value="0">Sans Frais</option>
                  <option value="1">Avec Frais</option>
                </select>';
  
                $form_Btn='
                  <form class="form-inline noImpr" method="post" id="form_ajouter_Panier_Btn_'.$vente['idPagnet'].'">
                      <input type="number" step="5" id="inpt_ajouter_Panier_Remise_'.$vente['idPagnet'].'" name="inpt_ajouter_Panier_Remise" class="remise form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Remise" placeholder="Remise...." >
                      <select class="compte form-control col-md-2 col-sm-2 col-xs-3" onchange="choix_Compte(this.value,'.$vente['idPagnet'].')" id="slct_ajouter_Panier_Compte_'.$vente['idPagnet'].'"> </select>
                      <input  style="display:none" type="number" step="5" name="versement" id="inpt_ajouter_Panier_Versement_'.$vente['idPagnet'].'"  placeholder="Montant..." class="versement form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Versement champ_saisie_Caisse'.$vente['idPagnet'].'">
                      <input  type="text" id="inpt_ajouter_Panier_Client_'.$vente['idPagnet'].'" class="client form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Client champ_saisie_Bon'.$vente['idPagnet'].'" placeholder="Client..."  value="'.$inpt_Client.'" autocomplete="off" />
                      <input  type="number" step="5" class="versement form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Bon'.$vente['idPagnet'].' champ_saisie_Client'.$vente['idPagnet'].' inpt_ajouter_Panier_Avance" placeholder="Avance..."  autocomplete="off" id="inpt_ajouter_Panier_Avance_'.$vente['idPagnet'].'" />
                      <select style="display:none" class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Bon_Compte'.$vente['idPagnet'].'" id="slct_ajouter_Bon_Compte_'.$vente['idPagnet'].'"> </select>
                      '.$frais.'
                      <button tabindex="1" type="button"  onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-danger pull-right" id="btn_retourner_Panier'.$vente['idPagnet'].'"  data-toggle="modal" data-target="#msg_ann_pagnet'.$vente['idPagnet'].'" >
                          <span class="glyphicon glyphicon-remove"></span>
                      </button>
                      <button type="button" onclick="valider_Panier('.$vente['idPagnet'].')" data-idPanier="'.$vente['idPagnet'].'" id="btn_valider_Panier'.$vente['idPagnet'].'" class="btn btn-success btn_valider_Panier'.$vente['idPagnet'].' pull-right" style="margin-right:2px;">
                          <span class="glyphicon glyphicon-ok"></span>
                      </button>
                  </form>
                ';
              }
              else {
  
                $frais='<select style="display:none" name="frais" id="slct_ajouter_Panier_Frais_'.$vente['idPagnet'].'" class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Frais'.$vente['idPagnet'].' inpt_ajouter_Panier_Frais"> 
                  <option value="0">Sans Frais</option>
                  <option value="1">Avec Frais</option>
                </select>';
  
                $frais_Multiple1='<select  style="display:none" name="frais" id="slct_ajouter_Panier_Frais_Multiple1_'.$vente['idPagnet'].'" class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Compte_Multiple1'.$vente['idPagnet'].' inpt_ajouter_Panier_Frais_Multiple1"> 
                  <option value="0">Sans Frais</option>
                  <option value="1">Avec Frais</option>
                </select>';
  
                $frais_Multiple2='<select  style="display:none" name="frais" id="slct_ajouter_Panier_Frais_Multiple2_'.$vente['idPagnet'].'" class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Compte_Multiple2'.$vente['idPagnet'].' inpt_ajouter_Panier_Frais_Multiple2"> 
                  <option value="0">Sans Frais</option>
                  <option value="1">Avec Frais</option>
                </select>';
  
                $multiple='<div style="display:none" class="champ_saisie_Compte_Multiple'.$vente['idPagnet'].'">
                  <br/><br/>
                  <select  class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Compte_Multiple'.$vente['idPagnet'].'" onchange="choix_Compte_Multiple1(this.value,'.$vente['idPagnet'].')" id="slct_ajouter_Panier_Compte_Multiple1_'.$vente['idPagnet'].'"> </select>
                  <input   type="number" step="5" name="versement" id="inpt_ajouter_Panier_Versement_Multiple1_'.$vente['idPagnet'].'"  placeholder="Montant..." class="versement form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Versement_Multiple1 champ_saisie_Compte_Multiple'.$vente['idPagnet'].'">
                  '.$frais_Multiple1.'
                  <select  class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Compte_Multiple'.$vente['idPagnet'].'" onchange="choix_Compte_Multiple2(this.value,'.$vente['idPagnet'].')" id="slct_ajouter_Panier_Compte_Multiple2_'.$vente['idPagnet'].'"> </select>
                  <input   type="number" step="5" name="versement" id="inpt_ajouter_Panier_Versement_Multiple2_'.$vente['idPagnet'].'"  placeholder="Montant..." class="versement form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Versement_Multiple2 champ_saisie_Compte_Multiple'.$vente['idPagnet'].'">
                  '.$frais_Multiple2.'
                </div>';
  
                $form_Btn='
                  <form class="form-inline noImpr" method="post" id="form_ajouter_Panier_Btn_'.$vente['idPagnet'].'">
                      <input type="number" step="5" id="inpt_ajouter_Panier_Remise_'.$vente['idPagnet'].'" name="inpt_ajouter_Panier_Remise" class="remise form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Remise" placeholder="Remise...." >
                      <select class="compte form-control col-md-2 col-sm-2 col-xs-3" onchange="choix_Compte(this.value,'.$vente['idPagnet'].')" id="slct_ajouter_Panier_Compte_'.$vente['idPagnet'].'"> </select>
                      <input type="number" step="5" name="versement" id="inpt_ajouter_Panier_Versement_'.$vente['idPagnet'].'"  placeholder="Montant..." class="versement form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Versement champ_saisie_Caisse'.$vente['idPagnet'].'">
                      <input style="display:none" data-type="client" type="text" id="inpt_ajouter_Panier_Client_'.$vente['idPagnet'].'" class="client form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Client champ_saisie_Bon'.$vente['idPagnet'].'" placeholder="Client..." autocomplete="off" />
                      <input style="display:none" type="number" step="5" class="avanceInput form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Bon'.$vente['idPagnet'].' champ_saisie_Client'.$vente['idPagnet'].' inpt_ajouter_Panier_Avance" placeholder="Avance..."  autocomplete="off" />
                      <select style="display:none" class="compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Bon_Compte'.$vente['idPagnet'].'" id="slct_ajouter_Bon_Compte_'.$vente['idPagnet'].'"> </select>
                      '.$frais.'
                      '.$multiple.'
                      <button tabindex="1" type="button"  onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-danger pull-right" id="btn_retourner_Panier'.$vente['idPagnet'].'"  data-toggle="modal" data-target="#msg_ann_pagnet'.$vente['idPagnet'].'" >
                          <span class="glyphicon glyphicon-remove"></span>
                      </button>
                      <button type="button" onclick="valider_Panier('.$vente['idPagnet'].')" data-idPanier="'.$vente['idPagnet'].'" id="btn_valider_Panier'.$vente['idPagnet'].'" class="btn btn-success btn_valider_Panier'.$vente['idPagnet'].' pull-right" style="margin-right:2px;">
                          <span class="glyphicon glyphicon-ok"></span>
                      </button>
                  </form>
                ';
              }
            }
            else {
              $form_Btn='                          
                <form class="form-inline noImpr" method="post">
                  <input type="number" step="5" id="inpt_ajouter_Panier_Remise_'.$vente['idPagnet'].'" name="inpt_ajouter_Panier_Remise" class="remise form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Remise" placeholder="Remise...." >
                  <input type="number" step="5" name="versement" id="inpt_ajouter_Panier_Versement_'.$vente['idPagnet'].'"  placeholder="Montant..." class="versement form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Versement">
                  <button tabindex="1" type="button"  onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-danger pull-right" id="btn_retourner_Panier'.$vente['idPagnet'].'"  data-toggle="modal" data-target="#msg_ann_pagnet'.$vente['idPagnet'].'" >
                      <span class="glyphicon glyphicon-remove"></span>
                  </button>
                  <button type="button" onclick="valider_Panier('.$vente['idPagnet'].')" data-idPanier="'.$vente['idPagnet'].'" id="btn_valider_Panier'.$vente['idPagnet'].'" class="btn btn-success btn_valider_Panier'.$vente['idPagnet'].' pull-right" style="margin-right:2px;">
                      <span class="glyphicon glyphicon-ok"></span>
                  </button>
                </form>
              ';
            }
    
            if($compte['typeCompte']=='Wave'){
              $type_image='Wave';
            }
            else if($compte['typeCompte']=='Orange Money'){
              $type_image='OrangeMoney';
            }
            else {
              $type_image='Especes';
            }
                  
            $btn_update='';
            $type_panier='';
            if($vente['idClient']!=0){
              $couleur='info';
              $type_image='Shop';
              if($vente['type']==6){ $couleur='danger'; $type_panier='BEP'; $type_image='Especes';} 
              else if($vente['type']==9){ $type_panier='INI';} else if($vente['type']==11){ $type_panier='AVR';}            
              else if($vente['type']==10){ $couleur='default';} else{ $type_panier='BON'; } 
            }
            else if($vente['type']==0 || $vente['type']==5){
              $couleur='success';
              if($vente['type']==0){ 
                $type_panier='VEN'; 
                $btn_update='<h4 class="pull-left" id="afficher_btn_Modifier'.$vente['idPagnet'].'" >
                  <button type="button" onclick="modifier_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-primary" > <span class="mot_Modifier">Editer</span></button>
                </h4>';
              } else if($vente['type']==5){ $type_panier='APP';}
            }
            else if($vente['type']==3 || $vente['type']==7){
              $couleur='danger';
              if($vente['type']==3){ 
                $type_panier='DEP'; 
                $btn_update='<h4 class="pull-left" id="afficher_btn_Modifier'.$vente['idPagnet'].'" >
                <button type="button" onclick="modifier_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-primary" > <span class="mot_Modifier">Editer</span></button>
              </h4>';
              } else if($vente['type']==7){ $type_panier='RET';}
            }
            else if($vente['type']==10){
              $type_image='Shop';
              $couleur='default';
              $type_panier='PRO';
              $btn_update='<h4 class="pull-left" id="afficher_btn_Modifier'.$vente['idPagnet'].'" >
                <button type="button" onclick="modifier_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-primary" > <span class="mot_Modifier">Editer</span></button>
              </h4>';
            }
            else{
              $couleur='default';
            }
    
            echo '<div class="panel panel-primary" id="panelPanier'.$vente['idPagnet'].'" panier-type="'.$vente['type'].'" panier-compte="'.$vente['idCompte'].'" panier-client="'.$vente['idClient'].'" panier-verrouiller="'.$vente['verrouiller'].'">
                  <div class="panel-heading">
                    <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#'.$vente['idPagnet'].'" class="panel-title expand">
                      <div class="right-arrow pull-right"><img id="img_ajouter_Panier_Compte'.$vente['idPagnet'].'" src="images/'.$type_image.'.ico" width="20px" /></div>
                      <a href="#" onclick="detailsVente('.$vente['idPagnet'].')">  
                        <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier"></span> '.$type_panier.'</span>
                        <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">HEURE</span> : '.$vente['heurePagnet'].'</span>
                        <span class="hidden-xs hidden-sm hidden-md col-lg-2"><span class="mot_Total">TOTAL</span> : <span id="spn_ajouter_Panier_Total'.$vente['idPagnet'].'">'.number_format(($vente['totalp'] * $_SESSION['devise']), 0, ',', ' ').'</span> </span>
                        <span class="col-xs-6 col-sm-3 col-md-3 col-lg-3"> <span class="mot_Total">NET PAYER</span> : <span id="spn_ajouter_Panier_APayer'.$vente['idPagnet'].'">'.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                        <span style="display:none" class="col-xs-6 col-sm-3 col-md-3 col-lg-2 spn_ajouter_Panier_Rendu'.$vente['idPagnet'].'"> <span class="mot_Total">RENDU</span> : <span id="spn_ajouter_Panier_Rendu'.$vente['idPagnet'].'">'.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                        <span style="display:none" class="col-xs-6 col-sm-3 col-md-3 col-lg-2 spn_ajouter_Panier_Frais'.$vente['idPagnet'].'"> <span class="mot_Total">FRAIS</span> : <span id="spn_ajouter_Panier_Frais'.$vente['idPagnet'].'">'.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                        <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2 pull-right" id="spn_ajouter_Panier_Facture'.$vente['idPagnet'].'"><span class="mot_Facture">TICKET</span> : '.$vente['idPagnet'].'</span>
                        <span class="hidden-xs hidden-sm hidden-md col-lg-1" id="spn_ajouter_Panier_Personnel'.$vente['idPagnet'].'"> </span>
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
                            <th class="hidden-xs"><span class="mot_Unite">Unite</span> <span class="mot_Vente">Vente</span></th>
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
                        <h5><b>NET PAYER</b> : <span id="spn_ajouter_Panier_Paiement_APayer'.$vente['idPagnet'].'"> '.$_SESSION['symbole'].'</span></h5>
                        <h5><b>Compte</b> : <span id="spn_ajouter_Panier_Compte'.$vente['idPagnet'].'"> </span></h5>
                        <h5 style="display:none" class="afficher_Paiement_Frais'.$vente['idPagnet'].'"><b>Frais</b> : <span id="spn_ajouter_Panier_frais'.$vente['idPagnet'].'"> </span></h5>
                        <h5 style="display:none" class="afficher_Paiement_Caisse'.$vente['idPagnet'].'"><b>Especes</b> : <span id="spn_ajouter_Panier_Paiement_Versement'.$vente['idPagnet'].'"> '.$_SESSION['symbole'].'</span></h5>
                        <h5 style="display:none" class="afficher_Paiement_Caisse'.$vente['idPagnet'].'"><b>Rendu</b> : <span id="spn_ajouter_Panier_Paiement_Rendu'.$vente['idPagnet'].'"> '.$_SESSION['symbole'].'</span></h5>
                      </div>
                      <h5 style="display:none" id="afficher_Client'.$vente['idPagnet'].'"> 
                        <b>Client</b> : <span id="spn_ajouter_Panier_Client'.$vente['idPagnet'].'"></span>
                      </h5>
                      <h5 style="display:none" id="afficher_Client_Avance'.$vente['idPagnet'].'">
                        <b>Avance</b> : <span id="spn_ajouter_Panier_Client_Avance'.$vente['avance'].'"> '.$_SESSION['symbole'].'</span>
                      </h5>
                    </div>
                  </div>
            </div>';
    
            $panier_NT++;
          }
        }
        else { 
          if($_SESSION['proprietaire']==1){
            $btn_retourner='<button type="button" onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-danger" id="btn_retourner_Panier'.$vente['idPagnet'].'" style="margin-right: 5px;" data-toggle="modal" data-target=""> <span class="mot_Retourner">Retourner</span></button>';
          }
          else{
            $btn_retourner='<button type="button" disabled="true" onclick="retourner_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-danger" id="btn_retourner_Panier'.$vente['idPagnet'].'" style="margin-right: 5px;" data-toggle="modal" data-target=""> <span class="mot_Retourner">Retourner</span></button>';
          }
  
          $stmtUser = $bdd->prepare("SELECT  * FROM `aaa-utilisateur` 
          WHERE idutilisateur=:iduser ");
          $stmtUser->execute(array(
              ':iduser' => $vente['iduser']
          ));
          $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
          $nom_user = substr(strtoupper($user['prenom']),0,3);
  
          if($vente['idClient']!=0){

            $stmtClient = $bdd->prepare("SELECT  * FROM `".$nomtableClient."` 
            WHERE idClient = :idClient ");
            $stmtClient->execute(array(
                ':idClient' => $vente['idClient']
            ));
            $client = $stmtClient->fetch(PDO::FETCH_ASSOC);

            $div_footer='
              <div style="display:none" id="afficher_Paiement'.$vente['idPagnet'].'">
                <div> 
                  <span class="pull-left"><b>**********************************</b></span>  
                  <span class="pull-right"><b>*********************************</b></span><br/>
                </div>
                <h5><b>Total panier</b> : <span id="spn_ajouter_Panier_Paiement_Total'.$vente['idPagnet'].'">  '.number_format(($vente['totalp'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                <h5><b>Remise</b> : <span id="spn_ajouter_Panier_Paiement_Remise'.$vente['idPagnet'].'"> '.number_format(($vente['remise'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                <h5><b>NET PAYER</b> : <span id="spn_ajouter_Panier_Paiement_APayer'.$vente['idPagnet'].'"> '.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                <h5><b>Compte</b> : <span id="spn_ajouter_Panier_Compte'.$vente['idPagnet'].'"> Bon </span></h5>
                <h5 style="display:none" class="afficher_Paiement_Caisse'.$vente['idPagnet'].'"><b>Especes</b> : <span id="spn_ajouter_Panier_Paiement_Versement'.$vente['idPagnet'].'"> '.number_format(($vente['versement'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                <h5 style="display:none" class="afficher_Paiement_Caisse'.$vente['idPagnet'].'"><b>Rendu</b> : <span id="spn_ajouter_Panier_Paiement_Rendu'.$vente['idPagnet'].'"> '.number_format(($vente['restourne'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
              </div>
              <hr>
              <h5 id="afficher_Client'.$vente['idPagnet'].'"> 
                <b>Client</b> : <span id="spn_ajouter_Panier_Client'.$vente['idPagnet'].'">'.$client['nom'].' '.$client['prenom'].'</span>
              </h5>
              <h5 style="display:none" id="afficher_Client_Avance'.$vente['idPagnet'].'">
                <b>Avance</b> : <span id="spn_ajouter_Panier_Client_Avance'.$vente['avance'].'">'.$vente['avance'].' '.$_SESSION['symbole'].'</span>
              </h5>
            ';

          }
          else{
            if($_SESSION['compte']==1){
              $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` 
              WHERE idCompte = :idCompte ");
              $stmtCompte->execute(array(
                  ':idCompte' => $vente['idCompte']
              ));
              $compte = $stmtCompte->fetch(PDO::FETCH_ASSOC);
              $type = ($compte!=null) ? ($compte['nomCompte']) : ('Caisse');

              if($compte['typeCompte']=='Wave'){
                if($vente['frais']>0){
                  $frais = '<h5><b>Frais</b> : <span id="spn_ajouter_Panier_Frais'.$vente['idPagnet'].'"> '.number_format(($vente['frais'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].' </span></h5>';
                }
                else{
                  $frais = '<h5><b>Frais</b> : <span id="spn_ajouter_Panier_Frais'.$vente['idPagnet'].'"> Sans frais </span></h5>';
                }    
              }
              else if($compte['typeCompte']=='Orange Money'){
                if($vente['frais']>0){
                  $frais = '<h5><b>Frais</b> : <span id="spn_ajouter_Panier_Frais'.$vente['idPagnet'].'"> '.number_format(($vente['frais'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].' </span></h5>';
                }
                else{
                  $frais = '<h5><b>Frais</b> : <span id="spn_ajouter_Panier_Frais'.$vente['idPagnet'].'"> Sans frais</span></h5>';
                } 
              }
              else {
                $frais = '';
              }
              $div_footer='
                <div style="display:none" id="afficher_Paiement'.$vente['idPagnet'].'">
                  <div> 
                    <span class="pull-left"><b>**********************************</b></span>  
                    <span class="pull-right"><b>*********************************</b></span><br/>
                  </div>
                  <h5><b>Total panier</b> : <span id="spn_ajouter_Panier_Paiement_Total'.$vente['idPagnet'].'"> '.number_format(($vente['totalp'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                  <h5><b>Remise</b> : <span id="spn_ajouter_Panier_Paiement_Remise'.$vente['idPagnet'].'"> '.number_format(($vente['remise'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                  <h5><b>NET PAYER</b> : <span id="spn_ajouter_Panier_Paiement_APayer'.$vente['idPagnet'].'"> '.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                  <h5><b>Compte</b> : <span id="spn_ajouter_Panier_Compte'.$vente['idPagnet'].'"> '.$type.' </span></h5>
                  '.$frais .'
                  <h5 style="display:none" class="afficher_Paiement_Caisse'.$vente['idPagnet'].'"><b>Especes</b> : <span id="spn_ajouter_Panier_Paiement_Versement'.$vente['idPagnet'].'"> '.number_format(($vente['versement'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                  <h5 style="display:none" class="afficher_Paiement_Caisse'.$vente['idPagnet'].'"><b>Rendu</b> : <span id="spn_ajouter_Panier_Paiement_Rendu'.$vente['idPagnet'].'"> '.number_format(($vente['restourne'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'].'</span></h5>
                </div>
                <hr>
                <h5 style="display:none" id="afficher_Client'.$vente['idPagnet'].'"> 
                   <b>Client</b> : <span id="spn_ajouter_Panier_Client'.$vente['idPagnet'].'"></span>
                </h5>
                <h5 style="display:none" id="afficher_Client_Avance'.$vente['idPagnet'].'">
                   <b>Avance</b> : <span id="spn_ajouter_Panier_Client_Avance'.$vente['avance'].'">'.$vente['avance'].' '.$_SESSION['symbole'].'</span>
                </h5>
              ';
            }
          }

          if($compte['typeCompte']=='Wave'){
            $type_image='Wave';
          }
          else if($compte['typeCompte']=='Orange Money'){
            $type_image='OrangeMoney';
          }
          else {
            $type_image='Especes';
          }
                
          $btn_update='';
          $type_panier='';
          if($vente['idClient']!=0){
            $couleur='info';
            $type_image='Shop';
            if($vente['type']==6){ $couleur='danger'; $type_panier='BEP'; $type_image='Especes';} 
            else if($vente['type']==9){ $type_panier='INI';} else if($vente['type']==11){ $type_panier='AVR';}            
            else if($vente['type']==10){ $couleur='default';} else{ $type_panier='BON'; } 
          }
          else if($vente['type']==0 || $vente['type']==5){
            $couleur='success';
            if($vente['type']==0){ 
              $type_panier='VEN'; 
              $btn_update='<h4 class="pull-left" id="afficher_btn_Modifier'.$vente['idPagnet'].'" >
                <button type="button" onclick="modifier_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-primary" > <span class="mot_Modifier">Editer</span></button>
              </h4>';
            } else if($vente['type']==5){ $type_panier='APP';}
          }
          else if($vente['type']==3 || $vente['type']==7){
            $couleur='danger';
            if($vente['type']==3){ 
              $type_panier='DEP'; 
              $btn_update='<h4 class="pull-left" id="afficher_btn_Modifier'.$vente['idPagnet'].'" >
              <button type="button" onclick="modifier_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-primary" > <span class="mot_Modifier">Editer</span></button>
            </h4>';
            } else if($vente['type']==7){ $type_panier='RET';}
          }
          else if($vente['type']==10){
            $type_image='Shop';
            $couleur='default';
            $type_panier='PRO';
            $btn_update='<h4 class="pull-left" id="afficher_btn_Modifier'.$vente['idPagnet'].'" >
              <button type="button" onclick="modifier_Panier('.$vente['idPagnet'].')" class="btn btn-round btn-primary" > <span class="mot_Modifier">Editer</span></button>
            </h4>';
          }
          else{
            $couleur='default';
          }

          if($_SESSION['proprietaire']!=1){
            $btn_update='';
          } 

          echo '<div class="panel panel-'.$couleur.'" id="panelPanier'.$vente['idPagnet'].'" panier-type="'.$vente['type'].'" panier-compte="'.$vente['idCompte'].'" panier-client="'.$vente['idClient'].'" panier-verrouiller="'.$vente['verrouiller'].'">
                <div class="panel-heading">
                  <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#'.$vente['idPagnet'].'" class="panel-title expand">
                    <div class="right-arrow pull-right"><img id="img_ajouter_Panier_Compte'.$vente['idPagnet'].'" src="images/'.$type_image.'.ico" width="20px" /></div>
                    <a href="#" onclick="detailsVente('.$vente['idPagnet'].')">  
                      <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier"></span> '.$type_panier.'</span>
                      <span class="hidden-xs col-sm-3 col-md-3 col-lg-2"><span class="mot_Heure">HEURE</span> : '.$vente['heurePagnet'].'</span>
                      <span class="hidden-xs hidden-sm hidden-md col-lg-2"><span class="mot_Total">TOTAL</span> : <span id="spn_ajouter_Panier_Total'.$vente['idPagnet'].'">'.number_format(($vente['totalp'] * $_SESSION['devise']), 0, ',', ' ').'</span> </span>
                      <span class="col-xs-6 col-sm-3 col-md-3 col-lg-3"> <span class="mot_Total">NET PAYER</span> : <span id="spn_ajouter_Panier_APayer'.$vente['idPagnet'].'">'.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                      <span style="display:none" class="col-xs-6 col-sm-3 col-md-3 col-lg-2 spn_ajouter_Panier_Rendu'.$vente['idPagnet'].'"> <span class="mot_Total">RENDU</span> : <span id="spn_ajouter_Panier_Rendu'.$vente['idPagnet'].'">'.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                      <span style="display:none" class="col-xs-6 col-sm-3 col-md-3 col-lg-2 spn_ajouter_Panier_Frais'.$vente['idPagnet'].'"> <span class="mot_Total">FRAIS</span> : <span id="spn_ajouter_Panier_Frais'.$vente['idPagnet'].'">'.number_format(($vente['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                      <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2 pull-right" id="spn_ajouter_Panier_Facture'.$vente['idPagnet'].'"><span class="mot_Facture">TICKET</span> : '.$vente['idPagnet'].'</span>
                      <span class="hidden-xs hidden-sm hidden-md col-lg-1" id="spn_ajouter_Panier_Personnel'.$vente['idPagnet'].'"> '.$nom_user.'</span>
                    </a>
                  </h4>
                </div>
                <div id="'.$vente['idPagnet'].'" class="panel-collapse collapse">
                  <br/>
                  <div class="content-panel" style="margin-left:12px;margin-right:12px">
                    <div style="display:none" class="cacher_btn_Terminer col-lg-11 col-md-11" id="cacher_btn_Terminer'.$vente['idPagnet'].'">
                      <form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="form_ajouter_Panier_Ligne_'.$vente['idPagnet'].'" onsubmit="return false" >
                          <input type="text" data-type="reference" class="inputbasic form-control inpt_ajouter_Panier_Ligne" name="codeBarre" id="inpt_ajouter_Panier_Ligne_'.$vente['idPagnet'].'" data-idPanier="'.$vente['idPagnet'].'" style="width:100%;" autocomplete="off" />
                      </form>
                      <div class="col-md-8 col-sm-8 col-xs-12 content2" id="afficher_Form_Header'.$vente['idPagnet'].'"> </div>
                    </div>
                    <table class="table table-hover" id="tablePanier'.$vente['idPagnet'].'">
                        '.$btn_update.'
                      <h4 class="pull-right" id="afficher_btn_Ticket'.$vente['idPagnet'].'">
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

        if($versement['idClient']!=0){

          $stmtClient = $bdd->prepare("SELECT  * FROM `".$nomtableClient."` 
          WHERE idClient = :idClient ");
          $stmtClient->execute(array(
              ':idClient' => $versement['idClient']
          ));
          $client = $stmtClient->fetch(PDO::FETCH_ASSOC);

          $nom_client='<h5> 
                <b>Client</b> : <span>'.$client['nom'].' '.$client['prenom'].'</span>
              </h5>';

          $couleur='success';
          $type_versement='VER';

        } 
        else if($versement['idFournisseur']!=0){ 

          $stmtFournisseur= $bdd->prepare("SELECT  * FROM `".$nomtableFournisseur."` 
          WHERE idFournisseur = :idFournisseur ");
          $stmtFournisseur->execute(array(
              ':idFournisseur' => $versement['idFournisseur']
          ));
          $fournisseur = $stmtFournisseur->fetch(PDO::FETCH_ASSOC);

          $nom_client='<h5> 
              <b>Fournisseur</b> : <span>'.$fournisseur['nomFournisseur'].' </span>
            </h5>';

          $couleur='danger';
          $type_versement='VER';

        }

        if($versement){

          $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` 
          WHERE idCompte = :idCompte ");
          $stmtCompte->execute(array(
              ':idCompte' => $versement['idCompte']
          ));
          $compte = $stmtCompte->fetch(PDO::FETCH_ASSOC);
          $type = ($compte!=null) ? ($compte['nomCompte']) : ('Caisse');

          if($compte['typeCompte']=='Wave'){
            $type_image='Wave';
          }
          else if($compte['typeCompte']=='Orange Money'){
            $type_image='OrangeMoney';
          }
          else {
            $type_image='Especes';
          }

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
  
            echo '<div class="panel panel-'.$couleur.'" id="panelVersement'.$versement['idVersement'].'" versement-compte="'.$versement['idCompte'].'">
                <div class="panel-heading">
                  <h4 style="padding-bottom: 18px;" data-toggle="collapse" data-parent="#accordion" href="#versement'.$versement['idVersement'].'" class="panel-title expand">
                    <div class="right-arrow pull-right"><img id="img_ajouter_Versement_Compte'.$vente['idPagnet'].'"  src="images/'.$type_image.'.ico" width="20px" /></div>
                    <a href="#" onclick="detailsVersement('.$versement['idVersement'].')">  
                      <span class="hidden-xs col-sm-2 col-md-2 col-lg-1"><span class="mot_Panier"> '.$type_versement.'</span></span>
                      <span class="hidden-xs hidden-sm col-md-3 col-lg-2"><span class="mot_Total">HEURE</span> : '.$versement['heureVersement'].' </span>
                      <span class="col-xs-6 col-sm-3 col-md-3 col-lg-2"> TOTAL : <span id="spn_ajouter_Versement_Total'.$versement['idVersement'].'" >'.number_format(($versement['montant'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                      <span class="col-xs-6 col-sm-3 col-md-3 col-lg-3"> NET PAYER : <span id="spn_ajouter_Versement_APayer'.$versement['idVersement'].'" >'.number_format(($versement['montant'] * $_SESSION['devise']), 0, ',', ' ').'</span></span>
                      <span class="col-xs-5 col-sm-3 col-md-3 col-lg-2 pull-right"><span class="mot_Facture">TICKET</span> : '.$versement['idVersement'].'</span>
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
                            <td>'.number_format(($versement['montant'] * $_SESSION['devise']), 0, ',', ' ').'</td>
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
                    <hr>
                    <div>
                      '.$nom_client.'
                    </div>
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

