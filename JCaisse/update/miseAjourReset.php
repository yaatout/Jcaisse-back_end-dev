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
            $tableInventaire = $boutique['nomBoutique']."-inventaire";
            $tableDesignation = $boutique['nomBoutique']."-designation";
            $tableStock = $boutique['nomBoutique']."-stock";
            $tableBl = $boutique['nomBoutique']."-bl";
            $tableJournal = $boutique['nomBoutique']."-journal";
            $tableCompte = $boutique['nomBoutique']."-compte";
            $tableCompteMouvement = $boutique['nomBoutique']."-comptemouvement";
            $tableFournisseur = $boutique['nomBoutique']."-fournisseur";
      
            $stmtClient_Table = $bdd->prepare("SELECT 1 FROM `".$tableFournisseur."` ");
            $table_Existe = $stmtClient_Table->execute();

            if($table_Existe==true){

                var_dump($boutique['nomBoutique']);
                
                //  $stmt_B1 = $bdd->prepare("ALTER TABLE `".$tableBl."` ADD `commande` int(1) NOT NULL;");
                //  $stmt_B1->execute();
                //  $stmt_B2 = $bdd->prepare("ALTER TABLE `".$tableBl."` ADD `montantTotal` double NOT NULL;");
                //  $stmt_B2->execute();

                //  $stmt_F = $bdd->prepare("ALTER TABLE `".$tableFournisseur."` ADD `solde` double NOT NULL;");
                //  $stmt_F->execute();

                //  $stmt_Stock = $bdd->prepare("ALTER TABLE `".$tableStock."` ADD `quantiteCommande` double NOT NULL;");
                //  $stmt_Stock->execute();


                // $stmt_Table = $bdd->prepare("SELECT 1 FROM `".$tableCompte."` ");
                // $table_Compte = $stmt_Table->execute();
    
                // if($table_Compte==true){

                // }
                // else {
                //     var_dump($boutique['nomBoutique'].' - OK');
                //     $sql1="CREATE TABLE IF NOT EXISTS `".$tableCompte."` (



                //         `idCompte` int(11) NOT NULL AUTO_INCREMENT,
                
                
                
                //         `nomCompte` varchar(20) COLLATE utf8_bin NOT NULL,
                
                
                
                //         `typeCompte` varchar(20) COLLATE utf8_bin NOT NULL,
                
                
                
                //         `numeroCompte` varchar(50) COLLATE utf8_bin NOT NULL,
                
                
                
                //         `montantCompte` double DEFAULT NULL,
                
                
                
                //         `recevoirPayement` int(11) NOT NULL,
                
                
                
                //         `activer` tinyint(1) NOT NULL DEFAULT 1,
                
                
                
                //         PRIMARY KEY (`idCompte`)
                
                
                
                //     ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

                //     $sql1_A="INSERT INTO `".$tableCompte."` (`idCompte`, `nomCompte`, `typeCompte`, `numeroCompte`, `montantCompte`, `recevoirPayement`, `activer`) VALUES



                //     (1, 'Caisse', 'caisse', 'sans numero', 0, 1, 1),
            
            
            
                //     (2, 'Bon', 'bon', 'sans numero', 0, 1, 1),
            
            
            
                //     (3, 'Remise', 'remise', 'sans numero', 0, 1, 1);";


                //     $res1 =@mysql_query($sql1) or die ("creation table compte impossible ".mysql_error());



                //     $res1_A =@mysql_query($sql1_A) or die ("insertion table compte impossible ".mysql_error());
                // }

                $stmt_Panier = $bdd->prepare("ALTER TABLE `".$tablePagnet."` ADD `frais` double NOT NULL;");
                $stmt_Panier->execute();

                //$stmt_Versement = $bdd->prepare("ALTER TABLE `".$tableVersement."` ADD `verrouiller` int(1) NOT NULL;");
                //$stmt_Versement->execute();

                //$stmtClient_Table = $bdd->prepare("DROP TABLE `".$tableJournal."` ");
                //$stmtClient_Table->execute();

                //$stmtStock_Table = $bdd->prepare("ALTER TABLE `".$tableStock."` DROP COLUMN `commande` ");
                //$stmtStock_Table->execute();

/*
                $stmt_Page = $bdd->prepare("CREATE TABLE IF NOT EXISTS `$tablePage` (
                    `numpage` int(11) NOT NULL AUTO_INCREMENT,
                    `datepage` varchar(15) NOT NULL,
                    `totalVente` double NOT NULL,
                    `totalService` double NOT NULL,
                    `totalVersement` double NOT NULL,
                    `totalBon` double NOT NULL,
                    `totalFrais` double NOT NULL,
                    `totalCaisse` double NOT NULL,
                    `datetime` varchar(25) NOT NULL,
                    `iduser` int(11) NOT NULL,
                    PRIMARY KEY (`numpage`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=478 ;");
                $stmt_Page->execute();
 */

 /*
                $stmt_Journal = $bdd->prepare("CREATE TABLE IF NOT EXISTS `$tableJournal` (
                    `idjournal` int(11) NOT NULL AUTO_INCREMENT,
                    `mois` int(11) NOT NULL,
                    `annee` int(11) NOT NULL,
                    `totalVente` double NOT NULL,
                    `totalVersement` double NOT NULL,
                    `totalBon` double NOT NULL,
                    `totalFrais` double NOT NULL,
                    PRIMARY KEY (`idjournal`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=478 ;");
                $stmt_Journal->execute();
*/

               
            
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