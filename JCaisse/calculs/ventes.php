<?php
session_start();

if(!$_SESSION['iduser']){

header('Location:../index.php');

}

require('../connection.php');

require('../connectionPDO.php');

require('../declarationVariables.php');

try{

    $operation=@htmlspecialchars($_POST["operation"]);
    $query=@$_POST["query"];
    $idPagnet=@$_POST["idPagnet"];
    $reference=@$_POST["reference"];
    $numligne=@$_POST["numligne"];
    $quantite=@$_POST["quantite"];
    $remise=@$_POST["remise"];
    $frais=@$_POST["frais"];
    $paiement_frais=@$_POST["paiement_frais"];
    $versement=@$_POST["versement"];
    $prix=@$_POST["prix"];
    $unitevente=@$_POST["unitevente"];
    $idClient=@$_POST["idClient"];
    $idCompte=@$_POST["idCompte"];
    $classe=@$_POST["classe"];
    $type=@$_POST["type"];
    $avance=@$_POST["avance"]; 
    $nombre_Ligne=@$_POST["nombre_Ligne"];

    /**Debut Lister les Lignes d'un Panier **/
        if($operation=='detailsVente'){

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` 
            WHERE idPagnet = :idPagnet ");
            $stmtPanier->execute(array(
                ':idPagnet' => $idPagnet
            ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
            $datas=array();
            if($panier){
                $stmtLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
                WHERE idPagnet = :idPagnet ");
                $stmtLigne->execute(array(
                    ':idPagnet' => $idPagnet
                ));
                $lignes = $stmtLigne->fetchAll(PDO::FETCH_ASSOC);
    
                $data=array();
                
                if($lignes==true){
                    foreach($lignes as $ligne){
                        $rows = array();
                        $rows[] = $ligne['idPagnet'];	
                        $rows[] = $ligne['numligne'];	
                        $rows[] = $ligne['designation'];
                        $rows[] = $ligne['quantite'];
                        $rows[] = $ligne['unitevente'];
                        $rows[] = $ligne['prixunitevente']; 
                        $rows[] = $ligne['prixtotal'];
                        $rows[] = $panier['verrouiller'];
                        $rows[] = $ligne['idDesignation'];
                        $rows[] = $ligne['classe'];
                        if($panier['verrouiller']==0){
                            $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                            WHERE idDesignation =:idDesignation");
                            $stmtReference->execute(array(
                                ':idDesignation' => $ligne['idDesignation']
                            ));
                            $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);

                            $rows[] = $reference['uniteStock'];
                            $rows[] = $reference['prixuniteStock'];
                            $rows[] = $reference['prix'];
                            $rows[] = $panier['type'];
                            $rows[] = $panier['idClient'];
                        }

                        $data[] = $rows;
                    
                    }
                }
                else{
                    $data[] = 0;
                }
                $datas[] = $data;
                $datas[] = $panier;
            }


            echo json_encode($datas);

        }
    /**Fin Lister les Lignes d'un Panier ***/

    /**Debut Ajouter Panier**/
        if($operation=='ajouter_Panier'){

            $stmPanier_Ajouter = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej, type, heurePagnet, totalp, apayerPagnet, verrouiller, idClient, iduser)
            VALUES (:datepagej, :type, :heurePagnet, :totalp, :apayerPagnet, :verrouiller, :idClient, :iduser)");
            $stmPanier_Ajouter->execute(array(
                ':datepagej' => $dateString2,
                ':type' => 0,
                ':heurePagnet' => $heureString, 
                ':totalp' => 0, 
                ':apayerPagnet' => 0,
                ':verrouiller' => 0,
                ':idClient' => 0,
                ':iduser' => $_SESSION['iduser'],
            ));
            $last_idPanier = $bdd->lastInsertId();

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` 
            WHERE idPagnet = :idPagnet ");
            $stmtPanier->execute(array(
                ':idPagnet' => $last_idPanier
            ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $last_idPanier;	
            $rows[] = $heureString;
            $rows[] = 0;	
            $rows[] = 0;
            $rows[] = $_SESSION['compte'];
            $rows[] = $panier;

            echo json_encode($rows);
        }
    /**Fin Ajouter Panier**/

    /**Debut Lister les Comptes**/
        if($operation=='lister_Compte'){

            $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` WHERE idCompte<>3 ORDER BY idCompte ");
            $stmtCompte->execute();
            $comptes = $stmtCompte->fetchAll(PDO::FETCH_ASSOC);

            $data=array();
            foreach($comptes as $compte){
                $rows = array();
                $rows[] = $compte['idCompte'];	
                $rows[] = $compte['nomCompte'];	
                $data[] = $rows; 
            }
            
            echo json_encode($data);
        }
    /**Fin Lister les Comptes**/

    /**Debut Rechercher Reference**/
        if($operation=='rechercher_Reference'){
            $data = [];
            if($classe==0){
                $stmReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."`  WHERE archiver<>1  ");
                $stmReference->execute();
                $references= $stmReference->fetchAll(PDO::FETCH_ASSOC);
            }
            else{
                if($classe==1){
                    $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                    WHERE archiver<>1  AND (classe =:produit OR classe =:service) ");
                    $stmtReference->execute(array(
                        ':produit' => 0,
                        ':service' => 1
                    ));
                    $references = $stmtReference->fetchAll(PDO::FETCH_ASSOC);
                }
                else {
                    $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                    WHERE archiver<>1 AND classe =:classe");
                    $stmtReference->execute(array(
                        ':classe' => $classe
                    ));
                    $references = $stmtReference->fetchAll(PDO::FETCH_ASSOC);
                }
            }


            foreach ($references as $reference) {
                $data[] = $reference['designation'];
            }

            echo json_encode($data);
        }
    /**Fin Rechercher Reference**/

    /**Debut Rechercher Client**/
        if($operation=='rechercher_Client'){
            $data = [];
            $stmClient = $bdd->prepare("SELECT  * FROM `".$nomtableClient."` WHERE activer<>0 AND archiver<>1 ");
            $stmClient->execute();
            $clients= $stmClient->fetchAll(PDO::FETCH_ASSOC);

            foreach ($clients as $client) {
                $data[] = $client['idClient'].'. '.$client['nom'].' '.$client['prenom'].' '.$client['adresse'];
            }

            echo json_encode($data);
        }
    /**Fin Rechercher Client**/

    /**Debut Choisir Client**/
        if($operation=='choisir_Client'){

            $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET idClient =:idClient, idCompte=2
            WHERE idPagnet =:idPagnet ");
            $stmPanier_Modifier->execute(array(
                ':idPagnet' => $idPagnet,
                ':idClient' => $idClient
            ));

            echo json_encode(1);
        }
    /**Fin Choisir Client**/

    /**Debut Choisir Compte**/
        if($operation=='choisir_Compte'){
            if($idCompte!=2){
                $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET idClient =:idClient, idCompte =:idCompte
                WHERE idPagnet =:idPagnet ");
                $stmPanier_Modifier->execute(array(
                    ':idPagnet' => $idPagnet,
                    ':idCompte' => $idCompte,
                    ':idClient' => 0
                ));
            }
            echo json_encode(1);
        }
    /**Fin Choisir Compte**/

    /**Debut Ajouter Ligne**/
        if($operation=='ajouter_Ligne'){

            $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE designation =:designation OR codeBarreDesignation =:codeBarreDesignation");
            $stmtReference->execute(array(
                ':designation' => $reference,
                ':codeBarreDesignation' => $reference,
            ));
            $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);

            if($reference['classe']!=10){
                $stmLigne_Rechercher = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
                WHERE idPagnet = :idPagnet AND idDesignation = :idDesignation limit 1  ");
                $stmLigne_Rechercher->execute(array(
                    ':idPagnet' => $idPagnet,
                    ':idDesignation' => $reference['idDesignation']
                ));
                $ligne_Existe = $stmLigne_Rechercher->fetch(PDO::FETCH_ASSOC);
                if($ligne_Existe){
                    $stmLigne_Modifier = $bdd->prepare("UPDATE `".$nomtableLigne."` SET quantite = quantite + 1 , prixtotal = prixunitevente * quantite 
                    WHERE numligne = :numligne ");
                    $stmLigne_Modifier->execute(array(
                        ':numligne' => $ligne_Existe['numligne']
                    ));
                    $result=1;
                    $last_idLigne=$ligne_Existe['numligne'];
                    $quantite=$ligne_Existe['quantite']+1;
                    $update=1;
                }
                else{              
                    if($nombre_Ligne!=0){
                        if(($type==$reference['classe']) || (($type==0 || $type==10) && ($reference['classe']==1 || $reference['classe']==0)) || ($type==3 && $reference['classe']==2)){
                            $stmLigne_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableLigne."` (idPagnet, idDesignation, designation, idStock, unitevente, prixunitevente, quantite, prixtotal, classe)
                            VALUES (:idPagnet, :idDesignation, :designation, :idStock, :unitevente, :prixunitevente, :quantite, :prixtotal, :classe)");
                            $stmLigne_Ajouter->execute(array(
                                ':idPagnet' => $idPagnet,
                                ':idDesignation' => $reference['idDesignation'],
                                ':designation' => $reference['designation'],
                                ':idStock' => 0, 
                                ':unitevente' => 'Article', 
                                ':prixunitevente' => $reference['prix'],
                                ':quantite' => 1,
                                ':prixtotal' => $reference['prix'],
                                ':classe' => $reference['classe']
                            ));
                            $last_idLigne = $bdd->lastInsertId();
                            $result=1;
                            $quantite=1;
                            $update=0;
                        }
                        else{
                            $result=0;
                        }
                    }
                    else{
                        $stmLigne_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableLigne."` (idPagnet, idDesignation, designation, idStock, unitevente, prixunitevente, quantite, prixtotal, classe)
                        VALUES (:idPagnet, :idDesignation, :designation, :idStock, :unitevente, :prixunitevente, :quantite, :prixtotal, :classe)");
                        $stmLigne_Ajouter->execute(array(
                            ':idPagnet' => $idPagnet,
                            ':idDesignation' => $reference['idDesignation'],
                            ':designation' => $reference['designation'],
                            ':idStock' => 0, 
                            ':unitevente' => 'Article', 
                            ':prixunitevente' => $reference['prix'],
                            ':quantite' => 1,
                            ':prixtotal' => $reference['prix'],
                            ':classe' => $reference['classe']
                        ));
                        $last_idLigne = $bdd->lastInsertId();
                        $result=1;
                        $quantite=1;
                        $update=0;
                    }
                }

                if($result==1){
                    if($reference['classe']==2){
                        $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET  type =:type
                        WHERE idPagnet =:idPagnet ");
                        $stmPanier_Modifier->execute(array(
                            ':idPagnet' => $idPagnet,
                            ':type' => 3 
                        ));
                        $type=3;
                    }
                    else if($reference['classe']==5){
                        $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET  type =:type
                        WHERE idPagnet =:idPagnet ");
                        $stmPanier_Modifier->execute(array(
                            ':idPagnet' => $idPagnet,
                            ':type' => 5 
                        ));
                        $type=5;
                    }
                    else if($reference['classe']==7){
                        $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET  type =:type
                        WHERE idPagnet =:idPagnet ");
                        $stmPanier_Modifier->execute(array(
                            ':idPagnet' => $idPagnet,
                            ':type' => 7 
                        ));
                        $type=7;
                    }
                    else{
                        $type=$type;
                    }

                    $rows = array();
                    $rows[] = $last_idLigne;
                    $rows[] = $idPagnet;
                    $rows[] = $reference['idDesignation'];
                    $rows[] = $reference['designation'];	
                    $rows[] = $quantite;
                    $rows[] = $reference['prix'];
                    $rows[] = $reference['uniteStock'];	
                    $rows[] = $reference['prixuniteStock'];
                    $rows[] = $update;
                    $rows[] = $type;
                    $rows[] = $reference['classe'];

                }
                else{
                    $rows = array();
                    $rows[] = 0;
                }
            }
            else{
                $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET  type =:type
                WHERE idPagnet =:idPagnet ");
                $stmPanier_Modifier->execute(array(
                    ':idPagnet' => $idPagnet,
                    ':type' => 10 
                ));

                $rows = array();
                $rows[] = $idPagnet;
                $rows[] = $reference['idDesignation'];
                $rows[] = $reference['designation'];	
                $rows[] = 'warning';
                $rows[] = 10;
            }

            echo json_encode($rows);
        }
    /**Fin Ajouter Ligne**/

    /**Debut Modifier Ligne Reference**/
        if($operation=='modifier_Ligne_Reference'){

            $stmLigne_Modifier = $bdd->prepare("UPDATE `".$nomtableLigne."` SET designation = :designation 
            WHERE numligne = :numligne ");
            $stmLigne_Modifier->execute(array(
                ':numligne' => $numligne,
                ':designation' => $reference,
            ));

            $stmLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
            WHERE numligne = :numligne ");
            $stmLigne->execute(array(
                ':numligne' => $numligne
            ));
            $ligne = $stmLigne->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $ligne['numligne'];
            $rows[] = $ligne['idPagnet'];
            $rows[] = $ligne['idDesignation'];
            $rows[] = $ligne['designation'];	
            $rows[] = $ligne['quantite'];
            $rows[] = $ligne['prixunitevente'];
            $rows[] = $ligne['prixtotal'];	

            echo json_encode($rows);
        }
    /**Fin Modifier Ligne Reference**/

    /**Debut Modifier Ligne Quantite**/
        if($operation=='modifier_Ligne_Quantite'){

                $stmLigne_Modifier = $bdd->prepare("UPDATE `".$nomtableLigne."` SET quantite = :quantite , prixtotal = prixunitevente * quantite 
                WHERE numligne = :numligne ");
                $stmLigne_Modifier->execute(array(
                    ':numligne' => $numligne,
                    ':quantite' => $quantite,
                ));

                $stmLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
                WHERE numligne = :numligne ");
                $stmLigne->execute(array(
                    ':numligne' => $numligne
                ));
                $ligne = $stmLigne->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $ligne['numligne'];
                $rows[] = $ligne['idPagnet'];
                $rows[] = $ligne['idDesignation'];
                $rows[] = $ligne['designation'];	
                $rows[] = $ligne['quantite'];
                $rows[] = $ligne['prixunitevente'];
                $rows[] = $ligne['prixtotal'];	

                echo json_encode($rows);
        }
    /**Fin Modifier Ligne Quantite**/

    /**Debut Modifier Ligne Unite Vente**/
        if($operation=='modifier_Ligne_Unite'){

                $stmLigne_Modifier = $bdd->prepare("UPDATE `".$nomtableLigne."` SET unitevente = :unitevente, prixunitevente = :prixunitevente , prixtotal = prixunitevente * quantite 
                WHERE numligne = :numligne ");
                $stmLigne_Modifier->execute(array(
                    ':numligne' => $numligne,
                    ':unitevente' => $unitevente,
                    ':prixunitevente' => $prix
                ));

                $stmLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
                WHERE numligne = :numligne ");
                $stmLigne->execute(array(
                    ':numligne' => $numligne
                ));
                $ligne = $stmLigne->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $ligne['numligne'];
                $rows[] = $ligne['idPagnet'];
                $rows[] = $ligne['idDesignation'];
                $rows[] = $ligne['designation'];	
                $rows[] = $ligne['quantite'];
                $rows[] = $ligne['prixunitevente'];
                $rows[] = $ligne['prixtotal'];	

                echo json_encode($rows);
        }
    /**Fin Modifier Ligne Unite Vente**/

    /**Debut Modifier Ligne Prix**/
        if($operation=='modifier_Ligne_Prix'){

                $stmLigne_Modifier = $bdd->prepare("UPDATE `".$nomtableLigne."` SET prixunitevente = :prixunitevente , prixtotal = prixunitevente * quantite 
                WHERE numligne = :numligne ");
                $stmLigne_Modifier->execute(array(
                    ':numligne' => $numligne,
                    ':prixunitevente' => $prix,
                ));

                $stmLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
                WHERE numligne = :numligne ");
                $stmLigne->execute(array(
                    ':numligne' => $numligne
                ));
                $ligne = $stmLigne->fetch(PDO::FETCH_ASSOC);

                $rows = array();
                $rows[] = $ligne['numligne'];
                $rows[] = $ligne['idPagnet'];
                $rows[] = $ligne['idDesignation'];
                $rows[] = $ligne['designation'];	
                $rows[] = $ligne['quantite'];
                $rows[] = $ligne['prixunitevente'];
                $rows[] = $ligne['prixtotal'];	

                echo json_encode($rows);
        }
    /**Fin Modifier Ligne Prix**/

    /**Debut Retourner Ligne**/
        if($operation=='retourner_Ligne'){

            $update = 0;

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` 
            WHERE idPagnet = :idPagnet ");
            $stmtPanier->execute(array(
                ':idPagnet' => $idPagnet
            ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
            if($panier['verrouiller']==1){

            }
            else {
                $stmLigne_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableLigne."` WHERE numligne = :numligne ");
                $stmLigne_Supprimer->execute(array(':numligne' => $numligne ));
                
                $stmtLigne= $bdd->prepare("SELECT  COUNT(*) as total FROM `".$nomtableLigne."` 
                WHERE idPagnet = :idPagnet ");
                $stmtLigne->execute(array(
                    ':idPagnet' => $idPagnet
                ));
                $ligne = $stmtLigne->fetch(PDO::FETCH_ASSOC);
                if($ligne['total']==0){
                    $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET  type =:type, idCompte =:idCompte
                    WHERE idPagnet =:idPagnet ");
                    $stmPanier_Modifier->execute(array(
                        ':idPagnet' => $idPagnet,
                        ':idCompte' => 0,
                        ':type' => 0 
                    ));
                    $update = 1;
                }
            }

            $rows = array();
            $rows[] = $numligne;
            $rows[] = $idPagnet;
            $rows[] = $update;

            echo json_encode($rows);
        }
    /**Fin Retourner Ligne**/

    /**Debut Terminer Panier**/
        if($operation=='valider_Panier'){

            try {

                $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` WHERE idPagnet = :idPagnet ");
                $stmtPanier->execute(array(
                    ':idPagnet' => $idPagnet
                ));
                $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
                if($panier['type']==0){
                    $panier_Total = enlever_Stock($idPagnet);
        
                    if(is_numeric($remise)){ } else{ $remise=0;  }
                    if(is_numeric($versement)){ } else{ $versement=0;  }

                    if($paiement_frais==1){  }  else{  $frais= -$frais; } 
                    $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET  
                    verrouiller =:verrouiller, totalp=:totalp, remise=:remise, frais=:frais, apayerPagnet = totalp - remise, versement=:versement, restourne = versement - apayerPagnet
                    WHERE idPagnet =:idPagnet ");
                    $stmPanier_Modifier->execute(array(
                        ':idPagnet' => $idPagnet,
                        ':totalp' => $panier_Total, 
                        ':remise' => $remise, 
                        ':frais' => $frais,
                        ':versement' => $versement,
                        ':verrouiller' => 1
                    ));
                }
                else{
                    $stmtLigne= $bdd->prepare("SELECT  SUM(prixtotal) as total FROM `".$nomtableLigne."` 
                    WHERE idPagnet = :idPagnet ");
                    $stmtLigne->execute(array(
                        ':idPagnet' => $idPagnet
                    ));
                    $ligne = $stmtLigne->fetch(PDO::FETCH_ASSOC);

                    $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET  
                    verrouiller =:verrouiller, totalp=:totalp, apayerPagnet =:apayerPagnet
                    WHERE idPagnet =:idPagnet ");
                    $stmPanier_Modifier->execute(array(
                        ':idPagnet' => $idPagnet,
                        ':totalp' => $ligne['total'], 
                        ':apayerPagnet' => $ligne['total'],
                        ':verrouiller' => 1
                    ));
                }

                if($_SESSION['compte']==1 && $panier['type']!=10){
                    depot_Compte_Panier($idPagnet);
                    if(is_numeric($avance)){
                        depot_Compte_Avance($idPagnet,$panier['idClient'],$avance,$idCompte);
                    } 
                } 

            } catch (\Throwable $th) {
                //throw $th;
            }
            
            $rows = array();
            $rows[] = $idPagnet;	
            echo json_encode($rows); 

        }
    /**Fin Terminer panier**/

    /**Debut Retourner Panier**/
        if($operation=='retourner_Panier'){

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` 
            WHERE idPagnet = :idPagnet ");
            $stmtPanier->execute(array(
                ':idPagnet' => $idPagnet
            ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
            if($panier['verrouiller']==1){

                if($panier['type']==0){
                    retourner_Stock($idPagnet);
                }

                $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET type=2
                WHERE idPagnet =:idPagnet ");
                $stmPanier_Modifier->execute(array(
                    ':idPagnet' => $idPagnet
                ));


                if($_SESSION['compte']==1 && $panier['type']!=10){
                    retrait_Compte_Panier($idPagnet);
                    if($panier['avance']!=0){
                        retrait_Compte_Avance($idPagnet);
                    }
                } 
            }
            else {
                $stmLigne_Supprimer = $bdd->prepare("DELETE FROM `".$nomtablePagnet."` WHERE idPagnet = :idPagnet ");
                $stmLigne_Supprimer->execute(array(':idPagnet' => $idPagnet ));
    
                $stmLigne_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableLigne."` WHERE idPagnet = :idPagnet ");
                $stmLigne_Supprimer->execute(array(':idPagnet' => $idPagnet ));
            }

            $rows = array();
            $rows[] = $panier['idPagnet'];

            echo json_encode($rows);
        }
    /**Fin Retourner panier**/

    /**Debut Lister les Lignes d'un Panier **/
        if($operation=='detailsPanier'){

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` 
            WHERE idPagnet = :idPagnet ");
            $stmtPanier->execute(array(
                ':idPagnet' => $idPagnet
            ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
            $datas = array();
            if($panier){

                $stmtUser = $bdd->prepare("SELECT  * FROM `aaa-utilisateur` 
                WHERE idutilisateur=:iduser ");
                $stmtUser->execute(array(
                    ':iduser' => $panier['iduser']
                ));
                $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

                $datas[] = $panier['idPagnet'];
                if($panier['idClient']!=0){
                    $stmtClient = $bdd->prepare("SELECT  * FROM `".$nomtableClient."` 
                    WHERE idClient = :idClient ");
                    $stmtClient->execute(array(
                        ':idClient' => $panier['idClient']
                    ));
                    $client = $stmtClient->fetch(PDO::FETCH_ASSOC);
                    $datas[] = $client['nom'].' '.$client['prenom'];
                }
                else{ $datas[] = 0; }
                $datas[] = $panier['datepagej'];
                $datas[] = $panier['heurePagnet'];
                $datas[] = number_format(($panier['totalp'] * $_SESSION['devise']), 0, ',', ' ');
                $datas[] = number_format(($panier['remise'] * $_SESSION['devise']), 0, ',', ' ');
                $datas[] = number_format(($panier['apayerPagnet'] * $_SESSION['devise']), 0, ',', ' ');
                if($panier['idCompte']!=0){
                    $stmtCompte = $bdd->prepare("SELECT  * FROM `".$nomtableCompte."` 
                    WHERE idCompte = :idCompte ");
                    $stmtCompte->execute(array(
                        ':idCompte' => $panier['idCompte']
                    ));
                    $compte = $stmtCompte->fetch(PDO::FETCH_ASSOC);
                    $datas[] = $compte['idCompte'].'. '.$compte['nomCompte'];
                }
                else{ $datas[] = '0. Caisse'; }
                $datas[] = number_format(($panier['versement'] * $_SESSION['devise']), 0, ',', ' ');
                $datas[] = number_format(($panier['restourne'] * $_SESSION['devise']), 0, ',', ' ');

                $stmtLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
                WHERE idPagnet = :idPagnet ");
                $stmtLigne->execute(array(
                    ':idPagnet' => $idPagnet
                ));
                $lignes = $stmtLigne->fetchAll(PDO::FETCH_ASSOC);

                $data=array();
                
                if($lignes==true){
                    foreach($lignes as $ligne){
                        $rows = array();
                        $rows[] = $ligne['idPagnet'];		
                        $rows[] = $ligne['designation'];
                        $rows[] = $ligne['quantite'];
                        $rows[] = $ligne['unitevente'];
                        $rows[] = $ligne['prixunitevente'];
                        $rows[] = $ligne['prixtotal'];
                        $data[] = $rows;
                    }
                }

                $datas[] = $data;
                $datas[] = $panier['type'];
                $datas[] = strtoupper($user['prenom']);
            }

            echo json_encode($datas);

        }
    /**Fin Lister les Lignes d'un Panier ***/

    /**Debut Modifier Panier**/
        if($operation=='modifier_Panier'){

            $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` 
            WHERE idPagnet = :idPagnet ");
            $stmtPanier->execute(array(
                ':idPagnet' => $idPagnet
            ));
            $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
            if($panier['verrouiller']==1){

                if($panier['type']==0){
                    retourner_Stock($idPagnet);
                }

                $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET  
                verrouiller=0, totalp=0, remise=0, apayerPagnet = 0, versement=0, restourne = 0
                WHERE idPagnet =:idPagnet ");
                $stmPanier_Modifier->execute(array(
                    ':idPagnet' => $idPagnet
                ));


                if($_SESSION['compte']==1 && $panier['type']!=10){
                    retrait_Compte_Panier($idPagnet);
                    if($panier['avance']!=0){
                        retrait_Compte_Avance($idPagnet);
                    }
                } 
            }
            $datas=array();
            if($panier){
                $stmtLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
                WHERE idPagnet = :idPagnet ");
                $stmtLigne->execute(array(
                    ':idPagnet' => $idPagnet
                ));
                $lignes = $stmtLigne->fetchAll(PDO::FETCH_ASSOC);
    
                $data=array();
                
                if($lignes==true){
                    foreach($lignes as $ligne){
                        $rows = array();
                        $rows[] = $ligne['idPagnet'];	
                        $rows[] = $ligne['numligne'];	
                        $rows[] = $ligne['designation'];
                        $rows[] = $ligne['quantite'];
                        $rows[] = $ligne['unitevente'];
                        $rows[] = $ligne['prixunitevente'];
                        $rows[] = $ligne['prixtotal'];
                        $rows[] = 0;
                        $rows[] = $ligne['idDesignation'];
                        $rows[] = $ligne['classe'];
                        $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                        WHERE idDesignation =:idDesignation");
                        $stmtReference->execute(array(
                            ':idDesignation' => $ligne['idDesignation']
                        ));
                        $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);

                        $rows[] = $reference['uniteStock'];
                        $rows[] = $reference['prixuniteStock'];
                        $rows[] = $reference['prix'];
                        $rows[] = $panier['type'];
                        $rows[] = $panier['idClient'];

                        $data[] = $rows;
                    
                    }
                }
                else{
                    $data[] = 0;
                }
                $datas[] = $data;
                $datas[] = $panier;
            }


            echo json_encode($datas);

    	    /*             
                $rows = array();
                $rows[] = $panier['idPagnet'];

                echo json_encode($rows); 
            */
        }
    /**Fin Modifier panier**/
   
}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}


    function enlever_Stock($idPagnet){
        require('../connectionPDO.php');
        require('../declarationVariables.php');

        try {

            $bdd->beginTransaction();

            $stmLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
            WHERE idPagnet = :idPagnet ");
            $stmLigne->execute(array(
                ':idPagnet' => $idPagnet
            ));
            $lignes = $stmLigne->fetchAll(PDO::FETCH_ASSOC);
            $panier_Total = 0;

            foreach ($lignes as $ligne){ 
                if($ligne['classe']==0){

                    $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                    WHERE idDesignation =:idDesignation");
                    $stmtReference->execute(array(
                        ':idDesignation' => $ligne['idDesignation']
                    ));
                    $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);

                    if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
                        $quantite_Ligne=$ligne['quantite'] * $reference['nbreArticleUniteStock']; 
                    }
                    else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                        $quantite_Ligne=$ligne['quantite']; 
                    }

                    $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` 
                    WHERE idDesignation =:idDesignation AND quantiteStockCourant!=0 ORDER BY idStock ASC");
                    $stmtStock->execute(array(
                        ':idDesignation' => $ligne['idDesignation']
                    ));
                    $stocks = $stmtStock->fetchAll(PDO::FETCH_ASSOC);

                    $stock_Total = 0;       
                    foreach ($stocks as $stock ) {
                        if($quantite_Ligne>= 0){
                            $quantiteStockCourant = $stock['quantiteStockCourant'] - $quantite_Ligne;
                            if($quantiteStockCourant > 0){
                                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=:quantiteStockCourant
                                WHERE idStock =:idStock");
                                $stmtStock_Modifier->execute(array(
                                    ':idStock' => $stock['idStock'],
                                    ':quantiteStockCourant' => $quantiteStockCourant
                                ));
                            }
                            else{
                                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=:quantiteStockCourant
                                WHERE idStock =:idStock");
                                $stmtStock_Modifier->execute(array(
                                    ':idStock' => $stock['idStock'],
                                    ':quantiteStockCourant' => 0
                                ));
                            }
                            $quantite_Ligne= $quantite_Ligne - $stock['quantiteStockCourant'] ;
                        }
                        $stock_Total = $stock_Total + $stock['quantiteStockCourant'];
                    }

                    if($stock_Total < $quantite_Ligne){
                        //Inventaire
                    }
                }
                $panier_Total = $panier_Total + $ligne['prixtotal'];
            }
            //$bdd->commit();

            return $panier_Total;
            
        }catch (PDOException $e){
        
        $pdo->rollback();
        exit('Erreur!!! '.$e->getMessage()) ;
        //var_dump($e->getMessage());
        throw $e;
        }
    }

    function retourner_Stock($idPagnet){
        require('../connectionPDO.php');
        require('../declarationVariables.php');

        try {

            $bdd->beginTransaction();

            $stmLigne = $bdd->prepare("SELECT  * FROM `".$nomtableLigne."` 
            WHERE idPagnet = :idPagnet ");
            $stmLigne->execute(array(
                ':idPagnet' => $idPagnet
            ));
            $lignes = $stmLigne->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($lignes as $ligne){ 
                if($ligne['classe']==0){

                    $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
                    WHERE idDesignation =:idDesignation");
                    $stmtReference->execute(array(
                        ':idDesignation' => $ligne['idDesignation']
                    ));
                    $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);

                    if (($ligne['unitevente']!="Article")&&($ligne['unitevente']!="article")) {
                        $quantite_Ligne=$ligne['quantite'] * $reference['nbreArticleUniteStock']; 
                    }
                    else if(($ligne['unitevente']=="Article")||($ligne['unitevente']=="article")){
                        $quantite_Ligne=$ligne['quantite']; 
                    }

                    $stmtStock = $bdd->prepare("SELECT  * FROM `".$nomtableStock."` 
                    WHERE idDesignation =:idDesignation ORDER BY idStock DESC");
                    $stmtStock->execute(array(
                        ':idDesignation' => $ligne['idDesignation']
                    ));
                    $stocks = $stmtStock->fetchAll(PDO::FETCH_ASSOC);

                    $stock_Total = 0;       
                    foreach ($stocks as $stock ) {
                        if($quantite_Ligne>= 0){
                            $quantiteStockCourant = $stock['quantiteStockCourant'] + $quantite_Ligne;
                            if($stock['totalArticleStock'] >= $quantiteStockCourant){
                                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=:quantiteStockCourant
                                WHERE idStock =:idStock");
                                $stmtStock_Modifier->execute(array(
                                    ':idStock' => $stock['idStock'],
                                    ':quantiteStockCourant' => $quantiteStockCourant
                                ));
                            }
                            else{
                                $stmtStock_Modifier = $bdd->prepare("UPDATE `".$nomtableStock."` SET quantiteStockCourant=:quantiteStockCourant
                                WHERE idStock =:idStock");
                                $stmtStock_Modifier->execute(array(
                                    ':idStock' => $stock['idStock'],
                                    ':quantiteStockCourant' => $stock['totalArticleStock']
                                ));
                            }
                            $quantite_Ligne = ($quantite_Ligne + $stock['quantiteStockCourant']) - $stock['totalArticleStock'] ;
                        }
                        $stock_Total = $stock_Total + $stock['quantiteStockCourant'];
                    }

                    if($stock_Total < $quantite_Ligne){
                        //Inventaire
                    }
                }
            }
            //$bdd->commit();
            
        }catch (PDOException $e){
        
        $pdo->rollback();
        exit('Erreur!!! '.$e->getMessage()) ;
        //var_dump($e->getMessage());
        throw $e;
        }
    }

    function depot_Compte_Panier($idPagnet){
        require('../connectionPDO.php');
        require('../declarationVariables.php');
        $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` WHERE idPagnet = :idPagnet ");
        $stmtPanier->execute(array(
            ':idPagnet' => $idPagnet
        ));
        $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
        if($panier){

            if($panier['idCompte']==0){
                $panier['idCompte']=1;
            }

            if($panier['type']==3 || $panier['type']==7){
                if($panier['type']==3){ 
                    $description='Depenses';
                }
                else{ 
                    $description='Retrait Caisse';
                }
                $stmCompte_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableComptemouvement."` 
                (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser)
                VALUES (:montant, :operation, :idCompte, :description, :dateOperation, :dateSaisie, :mouvementLink, :idUser)");
                $stmCompte_Ajouter->execute(array(
                    ':montant' => $panier['apayerPagnet'],
                    ':operation' => 'retrait',
                    ':idCompte' => $panier['idCompte'],
                    ':description' => $description, 
                    ':dateOperation' => $dateHeures, 
                    ':dateSaisie' => $dateHeures,
                    ':mouvementLink' => $idPagnet,
                    ':idUser' => $_SESSION['iduser'],
                ));
    
                $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte - :apayerPagnet 
                WHERE idCompte = :idCompte ");
                $stmCompte_Modifier->execute(array(
                    ':idCompte' => $panier['idCompte'],
                    ':apayerPagnet' => $panier['apayerPagnet'],
                ));
            }
            else{
                if($panier['type']==5){ 
                    $description='Approvisionnement Caisse';
                }
                else { 
                    if($panier['idClient']!=0){ 
                        $description='Bon client';
                    }
                    else{ 
                        $description='Encaissement vente';
                    }
                }

                $stmCompte_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableComptemouvement."` 
                (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser)
                VALUES (:montant, :operation, :idCompte, :description, :dateOperation, :dateSaisie, :mouvementLink, :idUser)");
                $stmCompte_Ajouter->execute(array(
                    ':montant' => $panier['apayerPagnet'],
                    ':operation' => 'depot',
                    ':idCompte' => $panier['idCompte'],
                    ':description' => $description, 
                    ':dateOperation' => $dateHeures, 
                    ':dateSaisie' => $dateHeures,
                    ':mouvementLink' => $idPagnet,
                    ':idUser' => $_SESSION['iduser'],
                ));
    
                $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte + :apayerPagnet 
                WHERE idCompte = :idCompte ");
                $stmCompte_Modifier->execute(array(
                    ':idCompte' => $panier['idCompte'],
                    ':apayerPagnet' => $panier['apayerPagnet'],
                ));
            }

            if($panier['remise']!=0 && $panier['remise']!=null){
                $stmCompte_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableComptemouvement."` 
                (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser)
                VALUES (:montant, :operation, :idCompte, :description, :dateOperation, :dateSaisie, :mouvementLink, :idUser)");
                $stmCompte_Ajouter->execute(array(
                    ':montant' => $panier['remise'],
                    ':operation' => 'depot',
                    ':idCompte' => 3,
                    ':description' => "Remise vente", 
                    ':dateOperation' => $dateHeures, 
                    ':dateSaisie' => $dateHeures,
                    ':mouvementLink' => $idPagnet,
                    ':idUser' => $_SESSION['iduser'],
                ));
    
                $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte + :montant 
                WHERE idCompte = :idCompte ");
                $stmCompte_Modifier->execute(array(
                    ':idCompte' => 3,
                    ':montant' => $panier['remise'],
                ));
            } 

            if (($panier['frais']!=0 && $panier['frais']!=null) && $panier['frais']<0 ){
                $montant_frais = -$panier['frais'];
                $stmCompte_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableComptemouvement."` 
                (montant,operation,idCompte,description,dateOperation,dateSaisie,mouvementLink,idUser)
                VALUES (:montant, :operation, :idCompte, :description, :dateOperation, :dateSaisie, :mouvementLink, :idUser)");
                $stmCompte_Ajouter->execute(array(
                    ':montant' => $montant_frais,
                    ':operation' => 'depot',
                    ':idCompte' => 3,
                    ':description' => "Frais mobile", 
                    ':dateOperation' => $dateHeures, 
                    ':dateSaisie' => $dateHeures,
                    ':mouvementLink' => $idPagnet,
                    ':idUser' => $_SESSION['iduser'],
                ));
    
                $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte + :montant 
                WHERE idCompte = :idCompte ");
                $stmCompte_Modifier->execute(array(
                    ':idCompte' => 3,
                    ':montant' => $montant_frais,
                ));
            }
        }
    }

    function retrait_Compte_Panier($idPagnet){
        require('../connectionPDO.php');
        require('../declarationVariables.php'); 
        $stmtPanier= $bdd->prepare("SELECT  * FROM `".$nomtablePagnet."` WHERE idPagnet = :idPagnet ");
        $stmtPanier->execute(array(
            ':idPagnet' => $idPagnet
        ));
        $panier = $stmtPanier->fetch(PDO::FETCH_ASSOC);
        if($panier){

            $stmtMouvement= $bdd->prepare("SELECT  * FROM `".$nomtableComptemouvement."`  WHERE mouvementLink = :mouvementLink ");
            $stmtMouvement->execute(array(
                ':mouvementLink' => $idPagnet
            ));
            $mouvements = $stmtMouvement->fetchAll(PDO::FETCH_ASSOC);
            foreach($mouvements as $mouvement){
                if($mouvement['idCompte']==3){
                    $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte - :montant 
                    WHERE idCompte = :idCompte ");
                    $stmCompte_Modifier->execute(array(
                        ':idCompte' => $mouvement['idCompte'],
                        ':montant' => $mouvement['montant'],
                    ));
                } 
                else{
                    if($panier['idCompte']==0){
                        $panier['idCompte']=1;
                    }
        
                    if($panier['type']==3 || $panier['type']==5){
                       
                        $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte + :montant 
                        WHERE idCompte = :idCompte ");
                        $stmCompte_Modifier->execute(array(
                            ':idCompte' => $mouvement['idCompte'],
                            ':montant' => $mouvement['montant'],
                        ));
                    } 
                    else{
            
                        $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte - :montant 
                        WHERE idCompte = :idCompte ");
                        $stmCompte_Modifier->execute(array(
                            ':idCompte' => $mouvement['idCompte'],
                            ':montant' => $mouvement['montant'],
                        ));
                    }
                } 
            }
            $stmCompte_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableComptemouvement."` WHERE mouvementLink = :mouvementLink ");
            $stmCompte_Supprimer->execute(array(':mouvementLink' => $idPagnet  ));

        }
    }

    function depot_Compte_Avance($idPagnet,$idClient,$avance,$idCompte){
        require('../connectionPDO.php');
        require('../declarationVariables.php');
        $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtablePagnet."` SET avance =:avance
        WHERE idPagnet =:idPagnet ");
        $stmPanier_Modifier->execute(array(
            ':idPagnet' => $idPagnet,
            ':avance' => $avance,
        ));

        $stmVersement_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableVersement."` 
        (idClient,paiement,montant,dateVersement,heureVersement,idCompte,idPagnet,iduser) 
        VALUES (:idClient, :paiement, :montant, :dateVersement, :heureVersement, :idCompte, :idPagnet, :iduser)");
        $stmVersement_Ajouter->execute(array(
            ':idClient' => $idClient,
            ':paiement' => 'Avance Client', 
            ':montant' => $avance, 
            ':dateVersement' => $dateString2, 
            ':heureVersement' => $heureString,
            ':idCompte' => $idCompte,
            ':idPagnet' => $idPagnet,
            ':iduser' => $_SESSION['iduser'],
        ));
        $last_idVersement = $bdd->lastInsertId();

        $stmCompte_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableComptemouvement."` 
        (montant,operation,idCompte,description,dateOperation,dateSaisie,idVersement,idUser)
        VALUES (:montant, :operation, :idCompte, :description, :dateOperation, :dateSaisie, :idVersement, :idUser)");
        $stmCompte_Ajouter->execute(array(
            ':montant' => $avance,
            ':operation' => 'depot',
            ':idCompte' => $idCompte,
            ':description' => 'Avance Client', 
            ':dateOperation' => $dateHeures, 
            ':dateSaisie' => $dateHeures,
            ':idVersement' => $last_idVersement,
            ':idUser' => $_SESSION['iduser'],
        ));

        $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte + :avance 
        WHERE idCompte = :idCompte ");
        $stmCompte_Modifier->execute(array(
            ':idCompte' => $idCompte,
            ':avance' => $avance,
        ));

        $versement=$last_idVersement.'<>'.$dateString2.' '.$heureString;

        return $versement;
    }

    function retrait_Compte_Avance($idPagnet){
        require('../connectionPDO.php');
        require('../declarationVariables.php');
        $stmtVersement= $bdd->prepare("SELECT  * FROM `".$nomtableVersement."`  WHERE idPagnet = :idPagnet ");
        $stmtVersement->execute(array(
            ':idPagnet' => $idPagnet
        ));
        $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);
        if($versement){
    
            $stmCompte_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableComptemouvement."` WHERE idVersement = :idVersement ");
            $stmCompte_Supprimer->execute(array(':idVersement' => $versement['idVersement'] ));
    
            $stmCompte_Modifier = $bdd->prepare("UPDATE `".$nomtableCompte."` SET montantCompte = montantCompte - :apayerPagnet 
            WHERE idCompte = :idCompte ");
            $stmCompte_Modifier->execute(array(
                ':idCompte' => $versement['idCompte'],
                ':apayerPagnet' => $panier['apayerPagnet'],
            ));

            $stmVersement_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableVersement."` WHERE idPagnet = :idPagnet ");
            $stmVersement_Supprimer->execute(array(':idPagnet' => $idPagnet ));
        }
    }

?>
