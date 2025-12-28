<?php
   
    require('../connection.php');
    require('../connectionPDO.php');

/*
    $bons_Clients = 0;    $total_bons_Clients = 0;   
    $ventes_Produits = 0;   $ventes_Mobiles = 0;
    $ventes_Services = 0;
    $depenses = 0;
    $approvisionnement = 0;
    $bons_En_Espesces = 0;
    $retrait = 0;
    $bons_Initialisation = 0;
    $prixAchat_Clients = 0;         $prixUnitaire_Clients = 0;
    $prixAchat_Ventes = 0;          $prixUnitaire_Ventes = 0;
    $remise = 0;
    $versement_Clients = 0;  $total_versement_Clients = 0;
    $versement_Fournisseurs = 0;
    $produits_Retires = 0;
    $produits_Retournes = 0;
    $prix_Achat = 0;   $total_prix_Achat = 0;
    $prix_Unitaire = 0;   $total_prix_Unitaire = 0;
    $bl_Fournisseur = 0;
    $mutuelle_Mutuelle_A_Payer = 0;
    $mutuelle_Panier_A_Payer = 0;

    */

    /**************************************** */

    //$debutAnnee = "2023-10-28";
    //$finAnnee = "2023-10-28";



    //$debutJour='01-01-2023';  
    //$finJour='31-01-2023'; 

     // Total number of records without filtering
        $stmtBoutique= $bdd->prepare("SELECT * FROM `aaa-boutique` b  ");
        $stmtBoutique->execute();
        $boutiques = $stmtBoutique->fetchAll();
        foreach ($boutiques as $boutique) {

            $tableClient = $boutique['nomBoutique']."-client";
            $tablePagnet = $boutique['nomBoutique']."-pagnet";
            $tableLigne = $boutique['nomBoutique']."-lignepj";
            $tableVersement = $boutique['nomBoutique']."-versement";
            $tablePage = $boutique['nomBoutique']."-pagej";
            $tableDesignation = $boutique['nomBoutique']."-designation";
            $tableStock = $boutique['nomBoutique']."-stock";
            $tableBl = $boutique['nomBoutique']."-bl";
      
            $stmtClient_Table = $bdd->prepare("SELECT 1 FROM `".$tableClient."` ");
            $table_Existe = $stmtClient_Table->execute();
            
            if($table_Existe==true){

                var_dump($boutique['nomBoutique']);

                $stmt_Page = $bdd->prepare("CREATE TABLE IF NOT EXISTS `$tablePage` (
                    `numpage` int(11) NOT NULL AUTO_INCREMENT,
                    `datepage` varchar(15) NOT NULL,
                    `approvisionnement` double NOT NULL,
                    `retrait` double NOT NULL,
                    `ventesCaisse` double NOT NULL,
                    `ventesMobile` double NOT NULL,
                    `services` double NOT NULL,
                    `depenses` double NOT NULL,
                    `bonsProduits` double NOT NULL,
                    `bonsEspeces` double NOT NULL,
                    `remiseVentes` double NOT NULL,
                    `remiseClients` double NOT NULL,
                    `versementsClientsCaisse` double NOT NULL,
                    `versementsClientsMobile` double NOT NULL,
                    `versementsFournisseurs` double NOT NULL,
                    `beneficesVentes` double NOT NULL,
                    `beneficesClient` double NOT NULL,
                    `datetime` varchar(25) NOT NULL,
                    PRIMARY KEY (`numpage`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=478 ;");
                $stmt_Page->execute();


                // Step 1: Setting the Start and End Dates
                $start_date = date_create($boutique['datecreation']);

                //$start_date = date_create('2020-01-01');
                //$start_date = date_create($boutique['dateCB']);
                //$start_date = date_create('2020-10-27');
                $end_date = date_create('2023-10-10');
                // Step 2: Defining the Date Interval
                $interval = new DateInterval('P1D');
                
                // Step 3: Creating the Date Range
                $date_range = new DatePeriod($start_date, $interval, $end_date);
                
                // Step 4: Looping Through the Date Range
                foreach ($date_range as $date) {
                    echo "<br />". $date->format('Y-m-d') . "<br />";

                    $jourAnnee = $date->format('Y-m-d');


                    $stmtDate = $bdd->prepare("SELECT * FROM `".$tablePage."`  WHERE datepage ='".$jourAnnee."' ");
                    $stmtDate->execute();
                    $jourExiste = $stmtDate->fetch();
                    if($jourExiste==null){

                        $bons_Clients = 0;    $total_bons_Clients = 0;   
                        $ventes_Produits = 0;   $ventes_Mobiles = 0;
                        $ventes_Services = 0;
                        $depenses = 0;
                        $approvisionnement = 0;
                        $bons_En_Espesces = 0;
                        $retrait = 0;
                        $bons_Initialisation = 0;
                        $prixAchat_Clients = 0;         $prixUnitaire_Clients = 0;
                        $prixAchat_Ventes = 0;          $prixUnitaire_Ventes = 0;
                        $remise_Ventes = 0;             $remise_Clients = 0;
                        $versement_Clients_Caisse = 0;  $versement_Clients_Mobile = 0;      $total_versement_Clients = 0;
                        $versement_Fournisseurs = 0;
                        $produits_Retires = 0;
                        $produits_Retournes = 0;
                        $prix_Achat = 0;   $total_prix_Achat = 0;
                        $prix_Unitaire = 0;   $total_prix_Unitaire = 0;
                        $bl_Fournisseur = 0;
                        $mutuelle_Mutuelle_A_Payer = 0;
                        $mutuelle_Panier_A_Payer = 0;
    
                        // Total number of records without filtering
                        $stmtLigne= $bdd->prepare("SELECT p.idClient, l.prixtotal, l.classe, p.idCompte FROM `".$tableLigne."` l 
                            INNER JOIN `".$tablePagnet."` p ON p.idPagnet = l.idPagnet
                        WHERE p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') )  ");
                        $stmtLigne->execute();
                        $lignes = $stmtLigne->fetchAll();
                        foreach ($lignes as $ligne) {
                            if($ligne['idClient']!=0){
                                if($ligne['classe']==0 || $ligne['classe']==1){
                                    $bons_Clients = $bons_Clients + $ligne['prixtotal'];
                                }
                                //Somme des Bons en Especes
                                else if ($ligne['classe']==6){
                                    $bons_En_Espesces = $bons_En_Espesces + $ligne['prixtotal'];
                                }
                                //Somme des Bon Initialisation 
                                else if ($ligne['classe']==9){
                                    $bons_Initialisation = $bons_Initialisation + $ligne['prixtotal'];
                                }
                            }
                            else{
                                //Somme des Ventes Produits
                                if($ligne['classe']==0 || $ligne['classe']==1){
                                    if($ligne['idCompte']<>0 && $ligne['idCompte']<>1 && $ligne['idCompte']<>2 && $ligne['idCompte']<>3 && $ligne['idCompte']<>1000){
                                        $ventes_Mobiles = $ventes_Mobiles + $ligne['prixtotal'];
                                    }
                                    else{
                                        if($ligne['classe']==0){
                                            $ventes_Produits = $ventes_Produits + $ligne['prixtotal'];
                                        }
                                        else if ($ligne['classe']==1){
                                            $ventes_Services = $ventes_Services + $ligne['prixtotal'];
                                        }
                                    }
                                }
                                //Somme des Depenses
                                else if ($ligne['classe']==2){
                                    $depenses = $depenses + $ligne['prixtotal'];
                                }
                                //Somme des Approvisionnements
                                else if ($ligne['classe']==5){
                                    $approvisionnement = $approvisionnement + $ligne['prixtotal'];
                                }
                                //Somme des Retraits 
                                else if ($ligne['classe']==7){
                                    $retrait = $retrait + $ligne['prixtotal'];
                                }
                            }
                        }
    
                        // Total number of records without filtering
                        if (($boutique['type']=="Pharmacie") AND ($boutique['categorie']=="Detaillant")) {
                            $stmtLigne= $bdd->prepare("SELECT p.idClient, l.quantite, d.prixSession, l.prixtotal  FROM  `".$tableLigne."` l
                            INNER JOIN `".$tablePagnet."` p ON p.idPagnet = l.idPagnet
                            INNER JOIN `".$tableDesignation."` d ON d.idDesignation = l.idDesignation
                            WHERE l.classe=0 AND p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') )  ");
                            $stmtLigne->execute();
                            $lignes = $stmtLigne->fetchAll();
                            foreach ($lignes as $ligne) {
                                if($ligne['idClient']!=0){
                                    if($ligne['prixtotal'] >= ($ligne['prixSession'] * $ligne['quantite'])){
                                        $prixAchat_Clients = $prixAchat_Clients + ($ligne['quantite'] * $ligne['prixSession']);
                                        $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                    }
                                }
                                else{
                                    if($ligne['prixtotal'] >= ($ligne['prixSession'] * $ligne['quantite'])){
                                        $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['quantite'] * $ligne['prixSession']);
                                        $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                    }
                                }
                            }
                        }
                        else {
                            $stmtLigne= $bdd->prepare("SELECT l.numligne,p.idClient, l.quantite, l.prixunitevente, l.unitevente, d.uniteStock, d.nbreArticleUniteStock, d.prixachat, l.prixtotal FROM  `".$tableLigne."` l
                            INNER JOIN `".$tablePagnet."` p ON p.idPagnet = l.idPagnet
                            INNER JOIN `".$tableDesignation."` d ON d.idDesignation = l.idDesignation
                            WHERE l.classe=0 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') )  ");
                            $stmtLigne->execute();
                            $lignes = $stmtLigne->fetchAll();
                            $i=0;
                            foreach ($lignes as $ligne) {
    
                                if (($boutique['type']=="Entrepot") AND ($boutique['categorie']=="Grossiste")) {
                                    if($ligne['idClient']!=0){
                                        if ($ligne['unitevente']==$ligne['uniteStock']) {
                                            if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                                                $prixAchat_Clients = $prixAchat_Clients + ($ligne['prixachat'] * $ligne['quantite']);
                                                $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                            }
                                        }
                                        else if ($ligne['unitevente']=="Demi Gros") {
                                            if($ligne['prixtotal'] >= (($ligne['prixachat'] / 2) * $ligne['quantite'])){
                                                $prixAchat_Clients = $prixAchat_Clients + ( ($ligne['prixachat'] / 2) * $ligne['quantite']);
                                                $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                            }
                                        }
                                        else{
                                            if($ligne['prixtotal'] >= (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                                                $prixAchat_Clients = $prixAchat_Clients + (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                                                $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                            }
                                        } 
                                    }
                                    else{
                                        if ($ligne['unitevente']==$ligne['uniteStock']) {
                                            if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                                                $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['prixachat'] * $ligne['quantite']);
                                                $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                            }
                                        }
                                        else if ($ligne['unitevente']=="Demi Gros") {
                                            if($ligne['prixtotal']>=(($ligne['prixachat'] / 2) * $ligne['quantite'])){
                                                $prixAchat_Ventes = $prixAchat_Ventes + ( ($ligne['prixachat'] / 2) * $ligne['quantite']);
                                                $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                            }
                                        }
                                        else{
                                            if($ligne['prixtotal'] >= (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                                                $prixAchat_Ventes = $prixAchat_Ventes + (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                                                $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                            }
                                        }
                                    }
    
                                    
                                }
                                else{
                                    if($ligne['idClient']!=0){
                                        if (($ligne['unitevente']!="Article") AND ($ligne['unitevente']!="article")) {
                                            if($ligne['prixtotal'] >= (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                                                $prixAchat_Clients = $prixAchat_Clients + (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                                                $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                            }
                                        }
                                        else{
                                            if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                                                $prixAchat_Clients = $prixAchat_Clients + ($ligne['prixachat'] * $ligne['quantite']);   
                                                $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                                            }
                                        } 
                                    }
                                    else{
                                        if (($ligne['unitevente']!="Article") AND ($ligne['unitevente']!="article")) {
                                            if($ligne['prixtotal'] >= (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                                                $prixAchat_Ventes = $prixAchat_Ventes + (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                                                $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                            }
                                        }
                                        else{
                                            if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                                                $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['prixachat'] * $ligne['quantite']);
                                                $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                                            }
                                        }
                                    }
                                }
    
                            }
                        }
    
                        // Total number of records without filtering
                        $stmtVentes= $bdd->prepare("SELECT SUM(p.remise) as remise FROM `".$tablePagnet."` p
                        WHERE p.idClient!=0 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') ) ");
                        $stmtVentes->execute();
                        $paniers = $stmtVentes->fetch();
                        $remise_Ventes = $paniers['remise'];
    
                        // Total number of records without filtering
                        $stmtBons= $bdd->prepare("SELECT SUM(p.remise) as remise FROM `".$tablePagnet."` p
                        WHERE p.idClient=0 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') ) ");
                        $stmtBons->execute();
                        $bons = $stmtBons->fetch();
                        $remise_Clients = $bons['remise'];
    
                        // Total number of records without filtering
                        $stmtVersement= $bdd->prepare("SELECT * FROM `".$tableVersement."` v
                        WHERE ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2))  ='".$jourAnnee."') or (v.dateVersement  ='".$jourAnnee."') )  ");
                        $stmtVersement->execute();
                        $versements = $stmtVersement->fetchAll();
                        foreach ($versements as $versement) {
                            if($versement['idClient']!=0){
                                if($versement['idCompte']<>0 && $versement['idCompte']<>1 && $versement['idCompte']<>2 && $versement['idCompte']<>3 && $versement['idCompte']<>1000){
                                    $versement_Clients_Mobile = $versement_Clients_Mobile + $versement['montant'];
                                }
                                else{
                                    $versement_Clients_Caisse = $versement_Clients_Caisse + $versement['montant'];
                                }
                            }
                            else if($versement['idFournisseur']!=0){
                                $versement_Fournisseurs = $versement_Fournisseurs + $versement['montant'];
                            }
                        }
    
                        $stmtBL = $bdd->prepare("SELECT SUM(montantBl) as montant FROM `".$tableBl."`  WHERE idFournisseur!=0 AND dateBl ='".$jourAnnee."' ");
                        $stmtBL->execute();
                        $bl = $stmtBL->fetch();
                        $bl_Fournisseur = $bl['montant'];
    
                        if (($boutique['mutuelle']==1) AND ($boutique['type']=="Pharmacie") AND ($boutique['categorie']=="Detaillant")) { 
    
                            // Total number of records without filtering
                            $stmtMutuelle= $bdd->prepare("SELECT DISTINCT p.idMutuellePagnet, SUM(apayerMutuelle) as mutuelle, SUM(apayerPagnet) as panier FROM `".$tableMutuellePagnet."` p
                                INNER JOIN `".$tableLigne."` l ON l.idMutuellePagnet = p.idMutuellePagnet
                            WHERE (l.classe=0 || l.classe=1) AND p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) ='".$jourAnnee."') or (p.datepagej ='".$jourAnnee."') ) ");
                            $stmtMutuelle->execute();
                            $mutuelle = $stmtMutuelle->fetch();
                            $mutuelle_Mutuelle_A_Payer = $mutuelle['mutuelle'];
                            $mutuelle_Panier_A_Payer = $mutuelle['panier'];
    
                        }
    
                        /*
    
                            $stmtPage = $bdd->prepare("INSERT INTO `".$tablePage."` (datepage,approvisionnement,retrait,ventesCaisse,ventesMobile,services,depenses,retrait,remises,
                                bonsProduits,bonsEspeces,versementsClients,versementsFournisseurs,beneficesVentes,beneficesClient)
                            VALUES (:datepage, :approvisionnement, :retrait, :ventesCaisse, :ventesMobile, :services, :depenses, :retrait, :remises,
                                :bonsProduits, :bonsEspeces, :versementsClients, :versementsFournisseurs, :beneficesVentes, :beneficesClient)");
                        
                        */ 
    
    
                        
                        /*
                            $stmtPage = $bdd->prepare("UPDATE `".$tablePage."` SET 
                            approvisionnement =:approvisionnement, retrait =:retrait, ventesCaisse =:ventesCaisse, ventesMobile =:ventesMobile, services =:services, depenses =:depenses, remiseVentes =:remiseVentes, remiseClient =:remiseClient, 
                            bonsProduits =:bonsProduits, bonsEspeces =:bonsEspeces, versementsClientsCaisse =:versementsClientsCaisse, versementsClientsMobile =:versementsClientsMobile, versementsFournisseurs =:versementsFournisseurs, beneficesVentes =:beneficesVentes, beneficesClient =:beneficesClient
                                WHERE datepage =:datepage ");
                        */

                        $stmtPage = $bdd->prepare("INSERT INTO `".$tablePage."` 
                               (datepage,approvisionnement,retrait,ventesCaisse,ventesMobile,services,depenses,remiseVentes,remiseClients,bonsProduits,bonsEspeces,versementsClientsCaisse,versementsClientsMobile,versementsFournisseurs,beneficesVentes,beneficesClient)
                        VALUES (:datepage,:approvisionnement,:retrait,:ventesCaisse,:ventesMobile,:services,:depenses,:remiseVentes,:remiseClients,:bonsProduits,:bonsEspeces,:versementsClientsCaisse,:versementsClientsMobile,:versementsFournisseurs,:beneficesVentes,:beneficesClient) ");
                        
                        var_dump($stmtPage);

                        /*
                        $stmtPage->execute(array(
                        ':approvisionnement' => $approvisionnement,
                        ':retrait' => $retrait,
                        ':ventesCaisse' => $ventes_Produits,
                        ':ventesMobile' => $ventes_Mobiles,
                        ':services' => $ventes_Services,
                        ':depenses' => $depenses,
                        ':remiseVentes' => $remise_Ventes,
                        ':remiseClients' => $remise_Clients,
                        ':bonsProduits' => $bons_Clients,
                        ':bonsEspeces' => $bons_En_Espesces,
                        ':versementsClientsCaisse' => $versement_Clients_Caisse,
                        ':versementsClientsMobile' => $versement_Clients_Mobile,
                        ':versementsFournisseurs' => $versement_Fournisseurs,
                        ':beneficesVentes' => ($prixUnitaire_Ventes - $prixAchat_Ventes),
                        ':beneficesClient' => ($prixUnitaire_Clients - $prixAchat_Clients),
                        ':datepage' => $jourAnnee
                        ));
                        */

                        $stmtPage->bindValue(':datepage', $jourAnnee);
                        $stmtPage->bindValue(':approvisionnement', $approvisionnement);
                        $stmtPage->bindValue(':retrait', $retrait);
                        $stmtPage->bindValue(':ventesCaisse', $ventes_Produits);
                        $stmtPage->bindValue(':ventesMobile', $ventes_Mobiles);
                        $stmtPage->bindValue(':services', $ventes_Services);
                        $stmtPage->bindValue(':depenses', $depenses);
                        $stmtPage->bindValue(':remiseVentes', $remise_Ventes);
                        $stmtPage->bindValue(':remiseClients', $remise_Clients);
                        $stmtPage->bindValue(':bonsProduits', $bons_Clients);
                        $stmtPage->bindValue(':bonsEspeces', $bons_En_Espesces);
                        $stmtPage->bindValue(':versementsClientsCaisse', $versement_Clients_Caisse);
                        $stmtPage->bindValue(':versementsClientsMobile', $versement_Clients_Mobile);
                        $stmtPage->bindValue(':versementsFournisseurs', $versement_Fournisseurs);
                        $stmtPage->bindValue(':beneficesVentes', ($prixUnitaire_Ventes - $prixAchat_Ventes));
                        $stmtPage->bindValue(':beneficesClient', ($prixUnitaire_Clients - $prixAchat_Clients));
                        $stmtPage->execute();

                        echo "Approvisionnement = ".$approvisionnement."<br/>";
                        echo "Versement Clients = ".($versement_Clients_Caisse + $versement_Clients_Mobile)."<br/>";
                        echo "Ventes Caisse= ".$ventes_Produits."<br/>"; 
                        echo "Ventes Mobile= ".$ventes_Mobiles."<br/>"; 
                        echo "Services = ".$ventes_Services."<br/>";
                        echo "Produits retournes = ".$produits_Retournes."<br/>";
                    
                        echo "Retrait = ".$retrait."<br/>";
                        echo "Depenses = ".$depenses."<br/>";
                        echo "Versement Fournisseurs = ".$versement_Fournisseurs."<br/>";
                        echo "Bons en Especes = ".$bons_En_Espesces."<br/>"; 
                        echo "Remises Ventes = ".$remise_Ventes."<br/>";
                        echo "Remises Clients = ".$remise_Clients."<br/>";
                    
                        echo "Bons Clients = ".$bons_Clients."<br/>"; 
                    
                        echo "Valeur Stock (PA) = ".$total_prix_Achat."<br/>";
                        echo "Valeur Stock (PU) = ".$total_prix_Unitaire."<br/>";
                    
                        echo "BL non payes = ".($bl_Fournisseur - $versement_Fournisseurs)."<br/>";
                    
                        echo "Benefices Ventes = ".($prixAchat_Ventes)."<br/>";
                        echo "Benefices Clients = ".($prixAchat_Clients)."<br/>";
    
                        echo "Prix Unitaire Ventes = ".($prixUnitaire_Ventes)."<br/>";
                        echo "Prix Unitaire  Clients = ".($prixUnitaire_Clients)."<br/>";
                    
                        echo "Benefices = ".( ($prixUnitaire_Ventes + $prixUnitaire_Clients) - ($prixAchat_Ventes + $prixAchat_Clients))."<br/>";
    
                        echo '/*****************************************************************************';

                    }

                }

            }

        

        }


    /*


    // Total number of records without filtering
    $stmtLigne= $bdd->prepare("SELECT p.idClient, l.prixtotal, l.classe FROM `".$tableLigne."` l 
        INNER JOIN `".$tablePagnet."` p ON p.idPagnet = l.idPagnet
    WHERE p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') )  ");
    $stmtLigne->execute();
    $lignes = $stmtLigne->fetchAll();
    foreach ($lignes as $ligne) {
        if($ligne['idClient']!=0){
            if($ligne['classe']==0 || $ligne['classe']==1){
                $bons_Clients = $bons_Clients + $ligne['prixtotal'];
            }
            //Somme des Bons en Especes
            else if ($ligne['classe']==6){
                $bons_En_Espesces = $bons_En_Espesces + $ligne['prixtotal'];
            }
            //Somme des Bon Initialisation 
            else if ($ligne['classe']==9){
                $bons_Initialisation = $bons_Initialisation + $ligne['prixtotal'];
            }
        }
        else{
            //Somme des Ventes Produits
            if($ligne['classe']==0){
                $ventes_Produits = $ventes_Produits + $ligne['prixtotal'];
            }
            else if ($ligne['classe']==1){
                $ventes_Services = $ventes_Services + $ligne['prixtotal'];
            }
            //Somme des Depenses
            else if ($ligne['classe']==2){
                $depenses = $depenses + $ligne['prixtotal'];
            }
            //Somme des Approvisionnements
            else if ($ligne['classe']==5){
                $approvisionnement = $approvisionnement + $ligne['prixtotal'];
            }
            //Somme des Retraits 
            else if ($ligne['classe']==7){
                $retrait = $retrait + $ligne['prixtotal'];
            }
        }
    }

    // Total number of records without filtering
    if (($boutique['type']=="Pharmacie") AND ($boutique['categorie']=="Detaillant")) {
        $stmtLigne= $bdd->prepare("SELECT p.idClient, l.quantite, d.prixSession, l.prixtotal  FROM  `".$tableLigne."` l
        INNER JOIN `".$tablePagnet."` p ON p.idPagnet = l.idPagnet
        INNER JOIN `".$tableDesignation."` d ON d.idDesignation = l.idDesignation
        WHERE l.classe=0 AND p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') )  ");
        $stmtLigne->execute();
        $lignes = $stmtLigne->fetchAll();
        foreach ($lignes as $ligne) {
            if($ligne['idClient']!=0){
                if($ligne['prixtotal'] >= ($ligne['prixSession'] * $ligne['quantite'])){
                    $prixAchat_Clients = $prixAchat_Clients + ($ligne['quantite'] * $ligne['prixSession']);
                    $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                }
            }
            else{
                if($ligne['prixtotal'] >= ($ligne['prixSession'] * $ligne['quantite'])){
                    $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['quantite'] * $ligne['prixSession']);
                    $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                }
            }
        }
    }
    else {
        $stmtLigne= $bdd->prepare("SELECT l.numligne,p.idClient, l.quantite, l.prixunitevente, l.unitevente, d.uniteStock, d.nbreArticleUniteStock, d.prixachat, l.prixtotal FROM  `".$tableLigne."` l
        INNER JOIN `".$tablePagnet."` p ON p.idPagnet = l.idPagnet
        INNER JOIN `".$tableDesignation."` d ON d.idDesignation = l.idDesignation
        WHERE l.classe=0 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') )  ");
        $stmtLigne->execute();
        $lignes = $stmtLigne->fetchAll();
        $i=0;
        foreach ($lignes as $ligne) {

            if (($boutique['type']=="Entrepot") AND ($boutique['categorie']=="Grossiste")) {
                if($ligne['idClient']!=0){
                    if ($ligne['unitevente']==$ligne['uniteStock']) {
                        if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                            $prixAchat_Clients = $prixAchat_Clients + ($ligne['prixachat'] * $ligne['quantite']);
                            $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                        }
                    }
                    else if ($ligne['unitevente']=="Demi Gros") {
                        if($ligne['prixtotal'] >= (($ligne['prixachat'] / 2) * $ligne['quantite'])){
                            $prixAchat_Clients = $prixAchat_Clients + ( ($ligne['prixachat'] / 2) * $ligne['quantite']);
                            $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                        }
                    }
                    else{
                        if($ligne['prixtotal'] >= (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                            $prixAchat_Clients = $prixAchat_Clients + (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                            $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                        }
                    } 
                }
                else{
                    if ($ligne['unitevente']==$ligne['uniteStock']) {
                        if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                            $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['prixachat'] * $ligne['quantite']);
                            $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                        }
                    }
                    else if ($ligne['unitevente']=="Demi Gros") {
                        if($ligne['prixtotal']>=(($ligne['prixachat'] / 2) * $ligne['quantite'])){
                            $prixAchat_Ventes = $prixAchat_Ventes + ( ($ligne['prixachat'] / 2) * $ligne['quantite']);
                            $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                        }
                    }
                    else{
                        if($ligne['prixtotal'] >= (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                            $prixAchat_Ventes = $prixAchat_Ventes + (($ligne['prixachat'] / $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                            $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                        }
                    }
                }

                
            }
            else{
                if($ligne['idClient']!=0){
                    if (($ligne['unitevente']!="Article") AND ($ligne['unitevente']!="article")) {
                        if($ligne['prixtotal'] >= (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                            $prixAchat_Clients = $prixAchat_Clients + (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                            $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                        }
                    }
                    else{
                        if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                            $prixAchat_Clients = $prixAchat_Clients + ($ligne['prixachat'] * $ligne['quantite']);   
                            $prixUnitaire_Clients = $prixUnitaire_Clients + $ligne['prixtotal'];
                        }
                    } 
                }
                else{
                    if (($ligne['unitevente']!="Article") AND ($ligne['unitevente']!="article")) {
                        if($ligne['prixtotal'] >= (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite'])){
                            $prixAchat_Ventes = $prixAchat_Ventes + (($ligne['prixachat'] * $ligne['nbreArticleUniteStock']) * $ligne['quantite']);
                            $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                        }
                    }
                    else{
                        if($ligne['prixtotal'] >= ($ligne['prixachat'] * $ligne['quantite'])){
                            $prixAchat_Ventes = $prixAchat_Ventes + ($ligne['prixachat'] * $ligne['quantite']);
                            $prixUnitaire_Ventes = $prixUnitaire_Ventes + $ligne['prixtotal'];
                        }
                    }
                }
            }

        }
    }
    
    // Total number of records without filtering
    $stmtPanier= $bdd->prepare("SELECT SUM(p.remise) as remise FROM `".$tablePagnet."` p
    WHERE p.idClient=0 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') ) ");
    $stmtPanier->execute();
    $panier = $stmtPanier->fetch();
    $remise = $panier['remise'];

    // Total number of records without filtering
    $stmtVersement= $bdd->prepare("SELECT * FROM `".$tableVersement."` v
    WHERE ((CONCAT(CONCAT(SUBSTR(v.dateVersement,7, 10),'',SUBSTR(v.dateVersement,3, 4)),'',SUBSTR(v.dateVersement,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (v.dateVersement BETWEEN '".$debutAnnee."' AND '".$finAnnee."') )  ");
    $stmtVersement->execute();
    $versements = $stmtVersement->fetchAll();
    foreach ($versements as $versement) {
        if($versement['idClient']!=0){
            $versement_Clients = $versement_Clients + $versement['montant'];
        }
        else if($versement['idFournisseur']!=0){
            $versement_Fournisseurs = $versement_Fournisseurs + $versement['montant'];
        }
    }

    // Total number of records without filtering
    $stmtInventaire= $bdd->prepare("SELECT * FROM `".$nomtableInventaire."` i
    WHERE i.dateInventaire BETWEEN '".$debutAnnee."' AND '".$finAnnee."') )  ");
    $stmtInventaire->execute();
    $inventaires = $stmtInventaire->fetchAll();
    foreach ($inventaires as $inventaire) {
        if($inventaire['type']==1){
            if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
                if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
                    if (($boutique['type']=="Pharmacie") AND ($boutique['categorie']=="Detaillant")){
                        $produits_Retires = $produits_Retires + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prixPublic']);
                    }
                    else{
                        $produits_Retires = $produits_Retires + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);  
                    }  
                }
            }
        }
        else if($inventaire['type']==3) {
            if($inventaire['quantite'] > $inventaire['quantiteStockCourant'] || $inventaire['quantite'] < $inventaire['quantiteStockCourant']){
                if ($inventaire['quantite'] < $inventaire['quantiteStockCourant']) {
                    if (($boutique['type']=="Pharmacie") AND ($boutique['categorie']=="Detaillant")){
                        $produits_Retournes = $produits_Retournes + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prixPublic']);
                    }
                    else{
                        $produits_Retournes = $produits_Retournes + (($inventaire['quantiteStockCourant'] - $inventaire['quantite']) * $inventaire['prix']);
                    }    
                }
            }
        }
    }

    */

    /*
    // Total number of records without filtering
    if (($boutique['type']=="Entrepot") AND ($boutique['categorie']=="Grossiste")) {
        
        $stmtStock = $bdd->prepare("SELECT s.quantiteStockCourant,s.nbreArticleUniteStock,i.prixuniteStock,d.prixuniteStock as ps,d.prixachat FROM `".$nomtableEntrepotStock."` s
        LEFT JOIN `".$nomtableStock."` i ON i.idStock = s.idStock
        LEFT JOIN `".$tableDesignation."` d ON d.idDesignation = s.idDesignation
        WHERE d.classe=0 ORDER BY s.idEntrepotStock DESC ");
        $stmtStock->execute();
        $stocks = $stmtStock->fetchAll();
        foreach ($stocks as $stock) {
            if($stock['quantiteStockCourant']!=null AND $stock['quantiteStockCourant']!=0){
                $quantite=$stock['quantiteStockCourant'] / $stock['nbreArticleUniteStock'];
            }
            else{
                $quantite=0;
            }
            $total_prix_Achat = $total_prix_Achat + ($quantite * $stock['prixachat']);
            $total_prix_Unitaire = $total_prix_Unitaire + ($quantite * $stock['ps']);
        }

    }
    else if (($boutique['type']=="Pharmacie") AND ($boutique['categorie']=="Detaillant")) {

        $stmtStock = $bdd->prepare("SELECT s.quantiteStockCourant, d.prixSession, d.prixPublic FROM `".$nomtableStock."` s
            LEFT JOIN  `".$tableDesignation."` d ON d.idDesignation = s.idDesignation 
            WHERE d.classe=0 AND s.quantiteStockCourant<>0 ");
        $stmtStock->execute();
        $stocks = $stmtStock->fetchAll();
        foreach ($stocks as $stock) {
            $total_prix_Achat = $total_prix_Achat + ($stock['quantiteStockCourant'] * $stock['prixSession']);
            $total_prix_Unitaire = $total_prix_Unitaire + ($stock['quantiteStockCourant'] * $stock['prixPublic']);
        }

    }
    else{
        $stmtStock = $bdd->prepare("SELECT s.quantiteStockCourant, d.prix, d.prixachat FROM `".$nomtableStock."` s
            LEFT JOIN  `".$tableDesignation."` d ON d.idDesignation = s.idDesignation 
            WHERE d.classe=0 AND s.quantiteStockCourant<>0 ");
        $stmtStock->execute();
        $stocks = $stmtStock->fetchAll();
        foreach ($stocks as $stock) {
                $total_prix_Achat = $total_prix_Achat + ($stock['quantiteStockCourant'] * $stock['prixachat']);
                $total_prix_Unitaire = $total_prix_Unitaire + ($stock['quantiteStockCourant'] * $stock['prix']);
        }

    }

    */

    /*

    $stmtBL = $bdd->prepare("SELECT SUM(montantBl) as montant FROM `".$tableBl."`  WHERE idFournisseur!=0 AND dateBl BETWEEN '".$debutAnnee."' AND '".$finAnnee."' ");
    $stmtBL->execute();
    $bl = $stmtBL->fetch();
    $bl_Fournisseur = $bl['montant'];

    if (($boutique['mutuelle']==1) AND ($boutique['type']=="Pharmacie") AND ($boutique['categorie']=="Detaillant")) { 

        // Total number of records without filtering
        $stmtMutuelle= $bdd->prepare("SELECT DISTINCT p.idMutuellePagnet, SUM(apayerMutuelle) as mutuelle, SUM(apayerPagnet) as panier FROM `".$tableMutuellePagnet."` p
            INNER JOIN `".$tableLigne."` l ON l.idMutuellePagnet = p.idMutuellePagnet
        WHERE (l.classe=0 || l.classe=1) AND p.verrouiller=1 AND  (p.type=0 || p.type=30) AND ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4)),'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debutAnnee."' AND '".$finAnnee."') or (p.datepagej BETWEEN '".$debutAnnee."' AND '".$finAnnee."') ) ");
        $stmtMutuelle->execute();
        $mutuelle = $stmtMutuelle->fetch();
        $mutuelle_Mutuelle_A_Payer = $mutuelle['mutuelle'];
        $mutuelle_Panier_A_Payer = $mutuelle['panier'];

    }

    */

    /*
    $stmtBons= $bdd->prepare("SELECT SUM(apayerPagnet) as total FROM `".$tablePagnet."` p
        LEFT JOIN `".$nomtableClient."` c ON c.idClient = p.idClient 
        WHERE c.archiver!=1 AND p.idClient!=0 AND p.verrouiller=1 AND  (p.type=0 || p.type=30)  ");
    $stmtBons->execute();
    $bons = $stmtBons->fetch();
    $total_bons_Clients = $bons['total'];

    $stmtVersement= $bdd->prepare("SELECT  SUM(montant) as total  FROM `".$tableVersement."` v
        LEFT JOIN `".$nomtableClient."` c ON c.idClient = v.idClient 
        WHERE c.archiver!=1 AND v.idClient!=0  ");
    $stmtVersement->execute();
    $versements = $stmtVersement->fetch();
    $total_versement_Clients = $versements['total'];
    */

    /*

    echo "Approvisionnement = ".$approvisionnement."<br/>";
    echo "Versement Clients = ".$versement_Clients."<br/>";
    echo "Ventes = ".$ventes_Produits."<br/>"; 
    echo "Services = ".$ventes_Services."<br/>";
    echo "Produits retournes = ".$produits_Retournes."<br/>";

    echo "Retrait = ".$retrait."<br/>";
    echo "Depenses = ".$depenses."<br/>";
    echo "Versement Fournisseurs = ".$versement_Fournisseurs."<br/>";
    echo "Bons en Especes = ".$bons_En_Espesces."<br/>"; 
    echo "Remises = ".$remise."<br/>";

    echo "Bons Clients = ".($total_bons_Clients - $total_versement_Clients)."<br/>"; 

    echo "Valeur Stock (PA) = ".$total_prix_Achat."<br/>";
    echo "Valeur Stock (PU) = ".$total_prix_Unitaire."<br/>";

    echo "BL non payes = ".($bl_Fournisseur - $versement_Fournisseurs)."<br/>";

    echo "Benefices Ventes = ".($prixAchat_Ventes)."<br/>";
    echo "Benefices Clients = ".($prixAchat_Clients)."<br/>";

    echo "Benefices = ".( ($prixUnitaire_Ventes + $prixUnitaire_Clients) - ($prixAchat_Ventes + $prixAchat_Clients))."<br/>";

    

    $bons_Produits = 0;     $ventes_Produits = 0;
    $bons_Services = 0;         $ventes_Services = 0;
    $depenses = 0;
    $approvisionnement = 0;
    $bons_En_Espesces = 0;
    $retrait = 0;
    $bons_Initialisation = 0;
    $benefices_Clients = 0;
    $benefices_Ventes = 0;
    $remise = 0;
    $versement_Clients = 0;
    $versement_Fournisseurs = 0;
    $produits_Retires = 0;
    $produits_Retournes = 0;
    $prix_Achat = 0;
    $prix_Unitaire = 0;
    $bl_Fournisseur = 0;
    $mutuelle_Mutuelle_A_Payer = 0;
    $mutuelle_Panier_A_Payer = 0;

    */