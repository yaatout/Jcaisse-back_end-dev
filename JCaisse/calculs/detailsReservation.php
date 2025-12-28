<?php
session_start();

if(!$_SESSION['iduser']){

header('Location:../index.php');

}

require('../connection.php');

require('../connectionPDO.php');

require('../declarationVariables.php');

$date = new DateTime();
$timezone = new DateTimeZone('Africa/Dakar');
$date->setTimezone($timezone);
$annee =$date->format('Y');
$mois =$date->format('m');
$jour =$date->format('d');
$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;
$dateHeures=$dateString.' '.$heureString;

/**Fin informations sur la date d'Aujourdhui **/

try{

    $operation=@htmlspecialchars($_POST["operation"]);
    $query=@$_POST["query"];
    $idVersement=@htmlspecialchars(trim($_POST['idVersement']));
    $description=@htmlspecialchars($_POST['description']);
    $montant=@$_POST["montant"];
    $paiement=@htmlspecialchars($_POST['description']);
    $dateActVersement=@htmlspecialchars($_POST['dateVersement']);
    if($dateActVersement!='' && $dateActVersement!=null){
        $dateVersement=$dateActVersement;  
    }
    else {
        $dateVersement=$dateString;
    }
    $compte=@$_POST["compte"];

    $idReservation=@$_POST["idReservation"];
    $reference=@$_POST["reference"];
    $numligne=@$_POST["numligne"];
    $prixchambre=@$_POST["prixchambre"];
    $prixpension=@$_POST["prixpension"];

    $dateArrivee=@$_POST["dateArrivee"];
    $heureArrivee=@$_POST["heureArrivee"];
    $dateDepart=@$_POST["dateDepart"];
    $heureDepart=@$_POST["heureDepart"];
    $datePension=@$_POST["datePension"];

    $nbreMoins4=@$_POST["nbreMoins4"];
    $nbreMoins12=@$_POST["nbreMoins12"];
    $nbrePlus12=@$_POST["nbrePlus12"];
    $nbrePension=@$_POST["nbrePension"];

    /**Debut Choix Reference**/
        if($operation=='choix_Reference'){

            $data = [];
            $existe = array();

            $stmtProduit = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` WHERE classe=50 AND designation Like :designation ");
            $stmtProduit->execute(array(
                'designation'=>"%$query%",
            ));
            $produits = $stmtProduit->fetchAll(PDO::FETCH_ASSOC);

            foreach($produits as $produit){
                $data[] = $produit['designation'].', Prix = '.$produit['prix'];
            }

            echo json_encode($data);
        }
    /**Fin Choix Reference**/

    /**Debut Details Chambre**/
        if($operation=='details_Chambre'){

            $stmtChambre = $bdd->prepare("SELECT  * FROM `".$nomtableLigneReservation."` 
            WHERE numligne = :numligne ");
            $stmtChambre->execute(array(
                ':numligne' => $numligne
            ));
            $chambre = $stmtChambre->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $chambre['numligne'];
            $rows[] = $chambre['designation'];
            $rows[] = $chambre['prixchambre'];
            $rows[] = $chambre['dateArrivee'].' '.$chambre['heureArrivee'];
            $rows[] = $chambre['dateDepart'].' '.$chambre['heureDepart'];
            $rows[] = $chambre['prixtotal'];

            echo json_encode($rows);
        }
    /**Fin Details Chambre**/

    /**Debut Ajouter Chambre**/
        if($operation=='ajouter_Chambre'){

            $stmtReference = $bdd->prepare("SELECT  * FROM `".$nomtableDesignation."` 
            WHERE designation =:designation");
            $stmtReference->execute(array(
                ':designation' => $reference
            ));
            $reference = $stmtReference->fetch(PDO::FETCH_ASSOC);

            $debut_date = date_create($dateArrivee); 
            $fin_date = date_create($dateDepart); 
            $interval = date_diff($debut_date, $fin_date);
            $jour=$interval->format('%R%a jours'); 

            $prixtaxe= (($jour * $nbreMoins12 * 500) + ($jour * $nbrePlus12 * 1000));
            $prixtotal= (($jour  * $prixchambre) + $prixtaxe);

            $stmLigne_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableLigneReservation."` 
            (idReservation,idDesignation,designation,prixchambre,prixtotal,dateArrivee,heureArrivee,dateDepart,heureDepart,jour,nbreMoins4,nbreMoins12,nbrePlus12,prixtaxe,dateLigne,heureLigne,classe,iduser )
            VALUES (:idReservation,:idDesignation,:designation,:prixchambre,:prixtotal,:dateArrivee,:heureArrivee,:dateDepart,:heureDepart,:jour,:nbreMoins4,:nbreMoins12,:nbrePlus12,:prixtaxe,:dateLigne,:heureLigne,:classe,:iduser)");
            $stmLigne_Ajouter->execute(array(
                ':idReservation' => $idReservation,
                ':idDesignation' => $reference['idDesignation'],
                ':designation' => $reference['designation'],
                ':prixchambre' => $prixchambre,
                ':prixtotal' => $prixtotal,
                ':dateArrivee' => $dateArrivee,
                ':heureArrivee' => $heureArrivee,
                ':dateDepart' => $dateDepart,
                ':heureDepart' => $heureDepart,
                ':jour' => $jour,
                ':nbreMoins4' => $nbreMoins4,
                ':nbreMoins12' => $nbreMoins12,
                ':nbrePlus12' => $nbrePlus12,
                ':prixtaxe' => $prixtaxe,
                ':dateLigne' => $dateString,
                ':heureLigne' => $heureString,
                ':classe' => 0,
                ':iduser' => $_SESSION['iduser']
            ));
            $last_idLigne = $bdd->lastInsertId();
           
            echo json_encode($last_idLigne);
        }
    /**Fin Ajouter Chambre**/

    /**Debut Supprimer Chambre**/
        if($operation=='supprimer_Chambre'){

            $stmtChambre_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableLigneReservation."` WHERE numligne = :numligne ");
            $stmtChambre_Supprimer->execute(array(':numligne' => $numligne ));

            echo json_encode(1);
        }
    /**Fin Supprimer Chambre**/ 

    /**Debut Details Pension**/
        if($operation=='details_Pension'){

            $stmtChambre = $bdd->prepare("SELECT  * FROM `".$nomtableLigneReservation."` 
            WHERE numligne = :numligne ");
            $stmtChambre->execute(array(
                ':numligne' => $numligne
            ));
            $chambre = $stmtChambre->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $chambre['numligne'];
            $rows[] = $chambre['designation'];
            $rows[] = $chambre['prixpension'];
            $rows[] = $chambre['datePension'];
            $rows[] = $chambre['nbrePension'];
            $rows[] = $chambre['prixtotal'];

            echo json_encode($rows);
        }
    /**Fin Details Pension**/

    /**Debut Ajouter Pension**/
        if($operation=='ajouter_Pension'){

            $prixtotal= ($prixpension  * $nbrePension);

            $stmLigne_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableLigneReservation."` 
            (idReservation,idDesignation,designation,description,prixpension,prixtotal,nbrePension,datePension,dateLigne,heureLigne,classe,iduser )
            VALUES (:idReservation,:idDesignation,:designation,:description,:prixpension,:prixtotal,:nbrePension,:datePension,:dateLigne,:heureLigne,:classe,:iduser)");
            $stmLigne_Ajouter->execute(array(
                ':idReservation' => $idReservation,
                ':idDesignation' => 0,
                ':designation' => $reference,
                ':description' => $description,
                ':prixpension' => $prixpension,
                ':prixtotal' => $prixtotal,
                ':datePension' => $datePension,
                ':nbrePension' => $nbrePension,
                ':dateLigne' => $dateString,
                ':heureLigne' => $heureString,
                ':classe' => 1,
                ':iduser' => $_SESSION['iduser']
            ));
            $last_idLigne = $bdd->lastInsertId();
           
            echo json_encode($last_idLigne);
        }
    /**Fin Ajouter Pension**/

    /**Debut Supprimer Pension**/
        if($operation=='supprimer_Pension'){

            $stmtPension_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableLigneReservation."` WHERE numligne = :numligne ");
            $stmtPension_Supprimer->execute(array(':numligne' => $numligne ));

            echo json_encode(1);
        }
    /**Fin Supprimer Pension**/ 

    /**Debut Details Versement**/
        if($operation=='details_Versement'){

            $stmtVersement = $bdd->prepare("SELECT  * FROM `".$nomtableVersement."` 
            WHERE idVersement = :idVersement ");
            $stmtVersement->execute(array(
                ':idVersement' => $idVersement
            ));
            $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $versement['idVersement'];
            $rows[] = $versement['dateVersement'];
            $rows[] = $versement['montant'];
            $rows[] = $versement['paiement'];

            echo json_encode($rows);
        }
    /**Fin Details Versement**/

    /**Debut Ajouter Versement**/
        if($operation=='ajouter_Versement'){

            $stmVersement_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableVersement."` 
            (idReservation,paiement,dateVersement,heureVersement,montant,iduser)
            VALUES (:idReservation, :paiement, :dateVersement, :heureVersement, :montant, :iduser)");
            $stmVersement_Ajouter->execute(array(
                ':idReservation' => $idReservation,
                ':paiement' => $paiement,
                ':dateVersement' => $dateVersement,
                ':heureVersement' => $heureString, 
                ':montant' => $montant,
                ':iduser' => $_SESSION['iduser']
            ));
            $versement_id = $bdd->lastInsertId();

            echo json_encode($versement_id);
        }
    /**Fin Ajouter Versement***/

    /**Debut Modifier Versement**/
        if($operation=='modifier_Versement'){

            $stmtVersement_Modifier = $bdd->prepare("UPDATE `".$nomtableVersement."` SET dateVersement =:dateVersement,
            montant=:montant, paiement =:paiement WHERE idVersement = :idVersement ");
            $stmtVersement_Modifier->execute(array(
                ':idVersement' => $idVersement,
                ':paiement' => $paiement,
                ':dateVersement' => $dateVersement,
                ':montant' => $montant
            ));

            echo json_encode(1);
        }
    /**Fin Modifier Versement**/ 

    /**Debut Supprimer Versement**/
        if($operation=='supprimer_Versement'){

            $stmtVersement_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableVersement."` WHERE idVersement = :idVersement ");
            $stmtVersement_Supprimer->execute(array(':idVersement' => $idVersement ));

            echo json_encode(1);
        }
    /**Fin Supprimer Versement**/ 

    /**Debut calculer la valeur du Montant de la Reservation*/
        if($operation=='reservation'){

            $stmtChambre = $bdd->prepare("SELECT SUM(prixtotal) as total FROM `".$nomtableLigneReservation."` WHERE idReservation =:idReservation AND classe=0 ");
            $stmtChambre->execute(array(
                ':idReservation' => $idReservation
            ));
            $chambre = $stmtChambre->fetch(PDO::FETCH_ASSOC);
            
            $stmtPension = $bdd->prepare("SELECT SUM(prixtotal) as total FROM `".$nomtableLigneReservation."` WHERE idReservation =:idReservation AND classe=1 ");
            $stmtPension->execute(array(
                ':idReservation' => $idReservation
            ));
            $pension = $stmtPension->fetch(PDO::FETCH_ASSOC);
            
            $stmtVersement = $bdd->prepare("SELECT SUM(montant) as total FROM `".$nomtableVersement."` WHERE idReservation =:idReservation ");
            $stmtVersement->execute(array(
                ':idReservation' => $idReservation
            ));
            $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);
            
            $total = $chambre['total'] + $pension['total'] - $versement['total'];
            
            $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtableReservation."` SET solde=:solde
            WHERE idReservation = :idReservation ");
            $stmPanier_Modifier->execute(array(
                ':idReservation' => $idReservation, 
                ':solde' => $total
            ));

            $rows = array();
            $rows[] = number_format(($chambre['total'] * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($pension['total'] * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];
            $rows[] = number_format(($versement['total'] * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	
            $rows[] = number_format(($total * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	

            echo json_encode($rows);
        }
    /**Fin calculer la valeur du Montant de la Reservation**/

   
}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>
