<?php

 //echo "Salaire_function";
/**
 * Récupère les informations d'une boutique
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param int $idBoutique Identifiant de la boutique
 * @return array|null Détails de la boutique
 */
function recupererBoutique(PDO $bdd, int $idBoutique) {
    
        $stmt = $bdd->prepare("
            SELECT * FROM `aaa-boutique` 
            WHERE idBoutique = :idBoutique
        ");
        $stmt->execute([':idBoutique' => $idBoutique]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ;
    
}

/**
 * Calcule les sommes de salaires pour différentes catégories
 * 
 * @param PDO $bdd Connexion à la base de données
 * @return array Tableau des sommes de salaires
 * @throws Exception En cas d'erreur de calcul
 */

 function calculerSommesSalaires(PDO  $bdd ) {
    // Initialisation des paramètres si non fournis

    // Tableau de résultats
    $resultats = [
        'nonPayes' => 0.0,
        'dejaPayes' => 0.0
    ];

    
        // Requête paramétrée pour les salaires du mois en cours
        $stmt1 = $bdd->prepare("
            SELECT SUM(partAccompagnateur) as total 
            FROM `aaa-payement-salaire` 
            WHERE aPayementBoutique =  :ap 
            AND aSalaireAccompagnateur =  :as
        ");
        
        $stmt1->execute([
            'ap' => 1,
            'as' => 0
        ]);
        $resultats['nonPayes'] = (float)$stmt1->fetchColumn();

        // Requête paramétrée pour les salaires payés
        $stmt2 = $bdd->prepare("
            SELECT SUM(partAccompagnateur) as total 
            FROM `aaa-payement-salaire` 
             WHERE aPayementBoutique =  :ap 
            AND aSalaireAccompagnateur =  :as
        ");
        
        $stmt2->execute([
            'ap' => 1,
            'as' => 1
        ]);
        $resultats['dejaPayes'] = (float)$stmt2->fetchColumn();

    

    return $resultats;
}

/**
 * Récupère les informations du personnel
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param DateTime $date Date de référence
 * @param int $profilPersonnel Profil du personnel à filtrer
 * @param int $page_position numero de la page dans la pagination
 * @param int $item_per_page Nombre d'element par page à afficher
 * @return array Liste du personnel
 * @throws Exception En cas d'erreur de récupération
 */
function recupererPersonnel(
    PDO $bdd, 
    DateTime $date, 
    int $profilPersonnel=7 ,
    int $page_position ,
    int $item_per_page
){
        $stmt = $bdd->prepare("
            SELECT 
                c.*, 
                p.*, 
                s.* 
            FROM `aaa-contrat` c
            INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
            INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
            WHERE 
                p.profilPersonnel = :profil 
                AND c.dateDebut <= :date 
                AND c.dateFin >= :date
                AND (
                    s.dateCalcul LIKE :periode1 
                    OR s.dateCalcul LIKE :periode2
                ) 
                    ORDER BY idSP DESC  LIMIT $page_position ,$item_per_page
        ");

        $periodeActuelle = $date->format('Y-m');
        $stmt->execute([
            'profil' => $profilPersonnel,
            'date' => $date->format('Y-m-d'),
            'periode1' => "%{$periodeActuelle}%",
            'periode2' => "%".strrev($periodeActuelle)."%"
        ]);

        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultats)) {
            error_log("Aucun personnel trouvé pour le profil $profilPersonnel");
        }

        return $resultats;

    
}
/**
 * Récupère les informations du personnel
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param DateTime $date Date de référence
 * @param int $profilPersonnel Profil du personnel à filtrer
 * @param int $page_position numero de la page dans la pagination
 * @param int $item_per_page Nombre d'element par page à afficher
 * @return array Liste du personnel
 * @throws Exception En cas d'erreur de récupération
 */
function recupererPersonnelFiltre(
    PDO $bdd, 
    DateTime $date, 
    int $profilPersonnel ,
    $query,
    $page_position ,
    $item_per_page
){
        $stmt = $bdd->prepare("
            SELECT 
                c.*, 
                p.*, 
                s.salaireDeBase, 
                s.salaireNet 
            FROM `aaa-contrat` c
            INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
            INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
            WHERE 
                p.profilPersonnel = :profil 
                AND c.dateDebut <= :date 
                AND c.dateFin >= :date
                AND (
                    s.dateCalcul LIKE :periode1 
                    OR s.dateCalcul LIKE :periode2
                ) 
                    ORDER BY datePS DESC  LIMIT $page_position ,$item_per_page
        ");

        $periodeActuelle = $date->format('Y-m');
        $stmt->execute([
            'profil' => $profilPersonnel,
            'date' => $date->format('Y-m-d'),
            'periode1' => "%{$periodeActuelle}%",
            'periode2' => "%".strrev($periodeActuelle)."%"
        ]);

        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultats)) {
            error_log("Aucun personnel trouvé pour le profil $profilPersonnel");
        }

        return $resultats;

   
}
/**
 * Récupère les informations du personnel
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param DateTime $date Date de référence
 * @param int $profilPersonnel Profil du personnel à filtrer
 * @return array Liste du personnel
 * @throws Exception En cas d'erreur de récupération
 */
function recupererNombrePersonnel(
    PDO $bdd, 
    DateTime $date, 
    int $profilPersonnel = 7
) {
    
        $stmt = $bdd->prepare("
            SELECT 
                COUNT(*) as total_row 
            FROM `aaa-contrat` c
            INNER JOIN `aaa-personnel` p ON c.idPersonnel = p.idPersonnel 
            INNER JOIN `aaa-salaire-personnel` s ON c.idContrat = s.idContrat
            WHERE 
                p.profilPersonnel = :profil 
                AND c.dateDebut <= :date 
                AND c.dateFin >= :date
                AND (
                    s.dateCalcul LIKE :periode1 
                    OR s.dateCalcul LIKE :periode2
                )
        ");

        $periodeActuelle = $date->format('Y-m');
        $stmt->execute([
            'profil' => $profilPersonnel,
            'date' => $date->format('Y-m-d'),
            'periode1' => "%{$periodeActuelle}%",
            'periode2' => "%".strrev($periodeActuelle)."%"
        ]);

        $resultats = $stmt->fetch();

        if (empty($resultats)) {
            error_log("Aucun personnel trouvé pour le profil $profilPersonnel");
        }

        return $resultats;
}
/**
 * Vérifie les droits d'accès de l'utilisateur
 * 
 * @param array $session Tableau de session
 * @param array $profilsAutorises Liste des profils autorisés
 * @return bool Indique si l'accès est autorisé
 */
function verifierAccesUtilisateur(
    array $session, 
    array $profilsAutorises = ['SuperAdmin', 'Assistant']
) {
    // Vérification de la présence du profil dans la session
    if (!isset($session['profil'])) {
        error_log("Tentative d'accès sans profil défini");
        return false;
    }

    // Vérification des droits
    $acceAutorise = in_array($session['profil'], $profilsAutorises);
    
    if (!$acceAutorise) {
        error_log("Accès refusé pour le profil : " . $session['profil']);
    }

    return $acceAutorise;
}

/**
 * Récupère les informations du personnel
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param DateTime $date Date de référence
 * @param int $profilPersonnel Profil du personnel à filtrer
 * @return array Liste du personnel
 * @throws Exception En cas d'erreur de récupération
 */
function recupererNombreAccompagnateur(PDO $bdd) {
    
        $stmt = $bdd->prepare("
            SELECT 
                COUNT(*) as total_row FROM `aaa-utilisateur` WHERE profil = :ac");
        $stmt->execute(["ac"=>'Accompagnateur']);
        $accompagnateurs = $stmt->fetch();
        return $accompagnateurs;

    
}

/**
 * Récupère les utilisateurs accompagnateurs
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param int $page
 * @param int $item
 * @return array Liste des accompagnateurs
 */
function recupererAccompagnateurs(PDO $bdd,$page_position,$item_per_page) {
    
        $stmt = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE profil = :ac  ORDER BY nom DESC  LIMIT $page_position ,$item_per_page");
        $stmt->execute(["ac"=>'Accompagnateur']);
        $accompagnateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        return $accompagnateurs;
    
}
function recupererInfoSalaireAccompagnateur(PDO $bdd,$matric) {
    
         // Récupération des paiements en attente
            $accompagnateur = [
                'payementAttandu' => 0,
                'payementDisponible' => 0,
                'dernierSalImpaye'=>0
            ];
            $stmt = $bdd->prepare("
                SELECT SUM(partAccompagnateur) AS value_sum 
                FROM `aaa-payement-salaire` 
                WHERE accompagnateur = :matricule 
                AND aSalaireAccompagnateur = '0'
            ");
            $stmt->execute([':matricule' => $matric]);
            $payementAttente = $stmt->fetch(PDO::FETCH_ASSOC);
            $accompagnateur['payementAttandu'] = $payementAttente['value_sum'] ?? 0;
            
            // Récupération du dernier complément
            $stmt = $bdd->prepare("
                SELECT SUM(partAccompagnateur) AS value_sum
                FROM `aaa-payement-salaire` 
                WHERE accompagnateur = :matricule 
                AND aSalaireAccompagnateur=:as
                AND aPayementBoutique = :ap 
            ");
            $stmt->execute([':matricule' => $matric,'as'=>0,'ap'=>1]);
            $dernierComplement = $stmt->fetch(PDO::FETCH_ASSOC);
            $accompagnateur['payementDisponible'] = $dernierComplement['value_sum'] ?? 0;

            // Récupération du dernier complément
                $stmt = $bdd->prepare(" 
                SELECT * FROM `aaa-payement-salaire` 
                WHERE accompagnateur = :matricule 
                AND aPayementBoutique=1 
                order by datePS DESC LIMIT 1
            ");
            $stmt->execute([':matricule' => $matric]);
            $accompagnateur['dernierSalImpaye'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        return $accompagnateur;
    
}
/**
 * Récupère les utilisateurs accompagnateurs
 * 
 * @param PDO $bdd Connexion à la base de données
 * @return array Liste des accompagnateurs
 */
function recupererAccompagnateursQuery(PDO $bdd,$query,$page_position,$item_per_page){
   
        $stmt = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE profil = :ac 
                and nom LIKE :req  
                ORDER BY nom DESC  LIMIT $page_position ,$item_per_page");

        $stmt->execute(["ac"=>'Accompagnateur' ,'req' => "%{$query}%",]);
        $accompagnateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $accompagnateurs;
    
}
function recupererUtilisateursById(PDO $bdd,$id) {
   
        $stmt = $bdd->prepare("SELECT * FROM `aaa-utilisateur` WHERE idutilisateur = :id  ");
        $stmt->execute(["id"=>$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);        
        return $user;
    
}
//////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Calcule les sommes de salaires pour différentes catégories
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param string|null $dateDebut Date de début pour le calcul (optionnel)
 * @param string|null $dateFin Date de fin pour le calcul (optionnel)
 * @return array Tableau des sommes de salaires
 * @throws Exception En cas d'erreur de calcul
 */
// function calculerSommesSalaires(PDO  $bdd, ?string $dateDebut = null, ?string $dateFin = null): array {
//     // Initialisation des paramètres si non fournis

//     // $dateDebut = $dateDebut ?? date('Y-m-01');
//     // $dateFin = $dateFin ?? date('Y-m-t');
//     if ($dateDebut === null) {
//         $dateDebut = date('Y-m-01');
//     }
//     if ($dateFin === null) {
//         $dateFin = date('Y-m-t');
//     }
//     // Tableau de résultats
//     $resultats = [
//         'mois_en_cours' => 0.0,
//         'payes' => 0.0
//     ];

//     try {
//         // Requête paramétrée pour les salaires du mois en cours
//         $stmt1 = $bdd->prepare("
//             SELECT SUM(partAccompagnateur) as total 
//             FROM `aaa-payement-salaire` 
//             WHERE aPayementBoutique = 1 
//             AND aSalaireAccompagnateur = 0
//             AND datePS BETWEEN :dateDebut AND :dateFin
//         ");
        
//         $stmt1->execute([
//             ':dateDebut' => $dateDebut,
//             ':dateFin' => $dateFin
//         ]);
//         $resultats['mois_en_cours'] = (float)$stmt1->fetchColumn();

//         // Requête paramétrée pour les salaires payés
//         $stmt2 = $bdd->prepare("
//             SELECT SUM(partAccompagnateur) as total 
//             FROM `aaa-payement-salaire` 
//             WHERE aPayementBoutique = 1 
//             AND aSalaireAccompagnateur = 1
//             AND datePS BETWEEN :dateDebut AND :dateFin
//         ");
        
//         $stmt2->execute([
//             ':dateDebut' => $dateDebut,
//             ':dateFin' => $dateFin
//         ]);
//         $resultats['payes'] = (float)$stmt2->fetchColumn();

//     } catch (PDOException $e) {
//         // Log détaillé de l'erreur
//         error_log(sprintf(
//             "Erreur de calcul des salaires : %s (Code : %d)", 
//             $e->getMessage(), 
//             $e->getCode()
//         ));

//         // Remontée de l'erreur
//         throw new Exception("Impossible de calculer les sommes de salaires", 0, $e);
//     }

//     return $resultats;
// }


/**
 * Récupère les utilisateurs avec PDO
 * 
 * @param PDO $bdd Connexion à la base de données
 * @return array Liste des utilisateurs
 */
// function recupererUtilisateurs(PDO $bdd): array {
//     try {
//         $stmt = $bdd->prepare("SELECT * FROM utilisateurs");
//         $stmt->execute();
//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     } catch (PDOException $e) {
//         error_log("Erreur de récupération des utilisateurs : " . $e->getMessage());
//         return [];
//     }
// }

/**
 * Récupère les paiements en attente pour un accompagnateur
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param string $accompagnateur Matricule de l'accompagnateur
 * @return array|null Détails du paiement
 */
// function recupererPayementAttente(PDO $bdd, string $accompagnateur): array {
//     try {
//         $stmt = $bdd->prepare("
//             SELECT * FROM `aaa-payement-salaire` 
//             WHERE accompagnateur = :accompagnateur 
//             AND aPayementBoutique = 1 
//             AND aSalaireAccompagnateur = 0 
//             ORDER BY datePS DESC 
//             LIMIT 1
//         ");
//         $stmt->execute([':accompagnateur' => $accompagnateur]);
//         return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
//     } catch (PDOException $e) {
//         error_log("Erreur de récupération du paiement en attente : " . $e->getMessage());
//         return null;
//     }
// }

/**
 * Récupère les paiements totaux pour un accompagnateur
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param string $accompagnateur Matricule de l'accompagnateur
 * @return array|null Détails du paiement
 */
// function recupererPayementTotal(PDO $bdd, string $accompagnateur): array {
//     try {
//         $stmt = $bdd->prepare("
//             SELECT * FROM `aaa-payement-salaire` 
//             WHERE accompagnateur = :accompagnateur 
//             AND aPayementBoutique = 1 
//             AND aSalaireAccompagnateur = 1 
//             ORDER BY datePS DESC 
//             LIMIT 1
//         ");
//         $stmt->execute([':accompagnateur' => $accompagnateur]);
//         return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
//     } catch (PDOException $e) {
//         error_log("Erreur de récupération du paiement total : " . $e->getMessage());
//         return null;
//     }
// }


/**
 * Récupère les informations d'un personnel
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param int $idPersonnel Identifiant du personnel
 * @return array|null Détails du personnel
 */
// function recupererPersonnelParId(PDO $bdd, int $idPersonnel): array {
//     try {
//         $stmt = $bdd->prepare("
//             SELECT * FROM `aaa-personnel` 
//             WHERE idPersonnel = :idPersonnel
//         ");
//         $stmt->execute([':idPersonnel' => $idPersonnel]);
//         return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
//     } catch (PDOException $e) {
//         error_log("Erreur de récupération du personnel : " . $e->getMessage());
//         return null;
//     }
// }

/**
 * Récupère les opérations de virement
 * 
 * @param PDO $bdd Connexion à la base de données
 * @param string $sqlv Requête SQL de sélection
 * @return array Liste des opérations de virement
 */
// function recupererOperationsVirement(PDO $bdd, string $sqlv): array {
//     try {
//         $stmt = $bdd->prepare($sqlv);
//         $stmt->execute();
//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     } catch (PDOException $e) {
//         error_log("Erreur de récupération des opérations de virement : " . $e->getMessage());
//         return [];
//     }
// }

// Fonction pour enrichir les informations des accompagnateurs
// function enrichirInformationsAccompagnateurs(PDO $bdd, array $accompagnateurs) {
//     foreach ($accompagnateurs as &$accompagnateur) {
//         // Récupération du paiement en attente
//         $payementAttente = recupererPayementAttente($bdd, $accompagnateur['matricule']);
        
//         // Récupération du paiement total
//         $payementTotal = recupererPayementTotal($bdd, $accompagnateur['matricule']);
        
//         // Calcul du montant total des paiements en attente
//         $stmt = $bdd->prepare("
//             SELECT 
//                 SUM(partAccompagnateur) AS total_attente,
//                 SUM(CASE WHEN aPayementBoutique = 1 THEN partAccompagnateur ELSE 0 END) AS total_boutique
//             FROM `aaa-payement-salaire` 
//             WHERE accompagnateur = :matricule 
//             AND aSalaireAccompagnateur = '0'
//         ");
//         $stmt->execute([':matricule' => $accompagnateur['matricule']]);
//         $totaux = $stmt->fetch(PDO::FETCH_ASSOC);

//         // Enrichissement des données
//         $accompagnateur['payementAttente'] = $totaux['total_attente'] ?? 0;
//         $accompagnateur['payementBoutique'] = $totaux['total_boutique'] ?? 0;
//         $accompagnateur['detailPayementAttente'] = $payementAttente;
//         $accompagnateur['detailPayementTotal'] = $payementTotal;
        
//         // Récupération du dernier complément
//         $stmt = $bdd->prepare("
//             SELECT * FROM `aaa-payement-salaire` 
//             WHERE accompagnateur = :matricule 
//             AND aPayementBoutique = 1 
//             ORDER BY datePS DESC 
//             LIMIT 1
//         ");
//         $stmt->execute([':matricule' => $accompagnateur['matricule']]);
//         $accompagnateur['dernierComplement'] = $stmt->fetch(PDO::FETCH_ASSOC);
//     }
    
//     return $accompagnateurs;
// }

