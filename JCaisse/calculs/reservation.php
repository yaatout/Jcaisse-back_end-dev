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
    $idReservation=@$_POST["idReservation"];
    $nom=@$_POST["nom"];
    $prenom=@$_POST["prenom"];
    $adresse=@$_POST["adresse"];
    $telephone=@$_POST["telephone"];
    $pays=@$_POST["pays"];
    $dateReservation=@$_POST["dateReservation"];
    $etat=@$_POST["etat"];

    /**Debut Rechercher Reservation **/
        if($operation=='details_Reservation'){

            $stmtReservation= $bdd->prepare("SELECT  * FROM `".$nomtableReservation."` 
            WHERE idReservation = :idReservation ");
            $stmtReservation->execute(array(
                ':idReservation' => $idReservation
            ));
            $reservation = $stmtReservation->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $reservation['idReservation'];	
            $rows[] = $reservation['nom'];
            $rows[] = $reservation['prenom'];	
            $rows[] = $reservation['adresse'];
            $rows[] = $reservation['telephone'];
            $rows[] = $reservation['pays'];
            $rows[] = $reservation['dateReservation'];
            $rows[] = $reservation['solde'];

            echo json_encode($rows);
        }
    /**Fin Rechercher Reservation ***/

    /**Debut Ajouter Reservation**/
        if($operation=='ajouter_Reservation'){

            $stmReservation_Ajouter = $bdd->prepare("INSERT INTO `".$nomtableReservation."` (dateReservation, heureReservation, nom, prenom, adresse, telephone, pays,iduser)
            VALUES (:dateReservation, :heureReservation, :nom, :prenom, :adresse, :telephone, :pays, :iduser)");
            $stmReservation_Ajouter->execute(array(
                ':dateReservation' => $dateReservation,
                ':heureReservation' => $heureString, 
                ':nom' => $nom, 
                ':prenom' => $prenom,
                ':adresse' => $adresse,
                ':telephone' => $telephone,
                ':pays' => $pays,
                ':iduser' => $_SESSION['iduser']
            ));
            $last_idReservation = $bdd->lastInsertId();

            $stmtReservation= $bdd->prepare("SELECT  * FROM `".$nomtableReservation."` 
            WHERE idReservation = :idReservation ");
            $stmtReservation->execute(array(
                ':idReservation' => $last_idReservation
            ));
            $reservation = $stmtReservation->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $reservation['idReservation'];	
            $rows[] = $reservation['nom'];
            $rows[] = $reservation['prenom'];	
            $rows[] = $reservation['pays'];
            $rows[] = $reservation['dateReservation'];
            $rows[] = $reservation['solde'];

            echo json_encode($rows);
        }
    /**Fin Ajouter Reservation**/

    /**Debut Modifier Reservation**/
        if($operation=='modifier_Reservation'){

            $stmReservation_Modifier = $bdd->prepare("UPDATE `".$nomtableReservation."` SET nom = :nom , prenom = :prenom, adresse = :adresse, telephone = :telephone, pays = :pays,  dateReservation = :dateReservation    
            WHERE idReservation = :idReservation ");
            $stmReservation_Modifier->execute(array(
                ':idReservation' => $idReservation,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':adresse' => $adresse, 
                ':telephone' => $telephone, 
                ':pays' => $pays,
                ':dateReservation' => $dateReservation
            ));

            $stmtReservation= $bdd->prepare("SELECT  * FROM `".$nomtableReservation."` 
            WHERE idReservation = :idReservation ");
            $stmtReservation->execute(array(
                ':idReservation' => $idReservation
            ));
            $reservation = $stmtReservation->fetch(PDO::FETCH_ASSOC);

            $rows = array();
            $rows[] = $reservation['idReservation'];	
            $rows[] = $reservation['nom'];
            $rows[] = $reservation['prenom'];	
            $rows[] = $reservation['pays'];
            $rows[] = $reservation['dateReservation'];
            $rows[] = $reservation['solde'];

            echo json_encode($rows);
        }
    /**Fin Modifier Reservation**/

    /**Debut Supprimer Reservation**/
        if($operation=='supprimer_Reservation'){
            $stmClient_Supprimer = $bdd->prepare("DELETE FROM `".$nomtableReservation."` WHERE idReservation = :idReservation ");
            $stmClient_Supprimer->execute(array(':idReservation' => $idReservation ));

            echo json_encode(1);
        }
    /**Fin Supprimer Reservation**/

    /**Debut Modifier Reservation**/
        if($operation=='etat_Reservation'){

            $stmReservation_Modifier = $bdd->prepare("UPDATE `".$nomtableReservation."` SET etat = :etat    
            WHERE idReservation = :idReservation ");
            $stmReservation_Modifier->execute(array(
                ':idReservation' => $idReservation,
                ':etat' => $etat,
            ));

            echo json_encode($idReservation);
        }
    /**Fin Modifier Reservation**/

   
}
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}



?>
