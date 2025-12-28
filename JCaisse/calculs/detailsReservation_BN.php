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
    $prixBien=@$_POST["prixBien"];
    $prixpension=@$_POST["prixpension"];

    $dateArrivee=@$_POST["dateArrivee"];
    $heureArrivee=@$_POST["heureArrivee"];
    $dateDepart=@$_POST["dateDepart"];
    $heureDepart=@$_POST["heureDepart"];
    $datePension=@$_POST["datePension"];


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

    /**Debut Details Bien**/
        if($operation=='details_Bien'){

            $stmtBien = $bdd->prepare("SELECT  * FROM `".$nomtableLigneReservation."` 
            WHERE numligne = :numligne ");
            $stmtBien->execute(array(
                ':numligne' => $numligne
            ));
            $bien = $stmtBien->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $bien['numligne'];
            $rows[] = $bien['designation'];
            $rows[] = $bien['prixbien'];
            $rows[] = $bien['dateArrivee'].' '.$bien['heureArrivee'];
            $rows[] = $bien['dateDepart'].' '.$bien['heureDepart'];
            $rows[] = $bien['prixtotal'];

            echo json_encode($rows);
        }
    /**Fin Details Bien**/

    /**Debut Ajouter Bien**/
        if($operation=='ajouter_Bien'){

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

            $prixtotal= $jour * $prixBien;

            $stmLigne_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableLigneReservation."` 
            (idReservation,idDesignation,designation,prixbien,prixtotal,dateArrivee,heureArrivee,dateDepart,heureDepart,jour,dateLigne,heureLigne,classe,iduser )
            VALUES (:idReservation,:idDesignation,:designation,:prixbien,:prixtotal,:dateArrivee,:heureArrivee,:dateDepart,:heureDepart,:jour,:dateLigne,:heureLigne,:classe,:iduser)");
            $stmLigne_Ajouter->execute(array(
                ':idReservation' => $idReservation,
                ':idDesignation' => $reference['idDesignation'],
                ':designation' => $reference['designation'],
                ':prixbien' => $prixBien,
                ':prixtotal' => $prixtotal,
                ':dateArrivee' => $dateArrivee,
                ':heureArrivee' => $heureArrivee,
                ':dateDepart' => $dateDepart,
                ':heureDepart' => $heureDepart,
                ':jour' => $jour,
                ':dateLigne' => $dateString,
                ':heureLigne' => $heureString,
                ':classe' => 0,
                ':iduser' => $_SESSION['iduser']
            ));
            $last_idLigne = $bdd->lastInsertId();
           
            echo json_encode($last_idLigne);
        }
    /**Fin Ajouter Bien**/

    /**Debut Supprimer Bien**/
        if($operation=='supprimer_Bien'){

            $stmtBien_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableLigneReservation."` WHERE numligne = :numligne ");
            $stmtBien_Supprimer->execute(array(':numligne' => $numligne ));

            echo json_encode(1);
        }
    /**Fin Supprimer Bien**/ 

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

            $stmtBien = $bdd->prepare("SELECT SUM(prixtotal) as total FROM `".$nomtableLigneReservation."` WHERE idReservation =:idReservation AND classe=0 ");
            $stmtBien->execute(array(
                ':idReservation' => $idReservation
            ));
            $bien = $stmtBien->fetch(PDO::FETCH_ASSOC);
            
            $stmtVersement = $bdd->prepare("SELECT SUM(montant) as total FROM `".$nomtableVersement."` WHERE idReservation =:idReservation ");
            $stmtVersement->execute(array(
                ':idReservation' => $idReservation
            ));
            $versement = $stmtVersement->fetch(PDO::FETCH_ASSOC);
            
            $total = $bien['total'] - $versement['total'];
            
            $stmPanier_Modifier = $bdd->prepare("UPDATE `".$nomtableReservation."` SET solde=:solde
            WHERE idReservation = :idReservation ");
            $stmPanier_Modifier->execute(array(
                ':idReservation' => $idReservation, 
                ':solde' => $total
            ));

            $rows = array();
            $rows[] = number_format(($bien['total'] * $_SESSION['devise']), 2, ',', ' ').' '.$_SESSION['symbole'];	
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
