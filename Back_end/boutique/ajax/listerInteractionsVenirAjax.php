<?php
session_start();
if(!$_SESSION['iduserBack']){
    header('Location:../index.php');
    exit();
}

require('../../connectionPDO.php');

// Récupérer les paramètres
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    // Requête pour récupérer les interactions à venir (date >= aujourd'hui)
    $sql = "SELECT 
                i.idInteraction,
                i.typeInteraction,
                i.dateInteraction,
                i.notes,
                i.suivi,
                i.dateCreation,
                c.prenom,
                c.nom,
                c.nomBoutique,
                u.prenom as prenomUtilisateur,
                u.nom as nomUtilisateur
            FROM `aaa-boutique-prospectInteraction` i
            LEFT JOIN `aaa-boutique-prospectClient` c ON i.idClient = c.idBPC
            LEFT JOIN `aaa-utilisateur` u ON i.idUtilisateur = u.idUtilisateur
            WHERE i.dateInteraction >= CURDATE()";
    
    // Ajouter la recherche si nécessaire
    if (!empty($search)) {
        $sql .= " AND (
                    c.prenom LIKE :search OR 
                    c.nom LIKE :search OR 
                    c.nomBoutique LIKE :search OR 
                    i.typeInteraction LIKE :search OR 
                    i.notes LIKE :search
                 )";
    }
    
    $sql .= " ORDER BY i.dateInteraction ASC, i.dateCreation DESC LIMIT :limit";
    
    $stmt = $bdd->prepare($sql);
    
    // Binder les paramètres
    if (!empty($search)) {
        $searchParam = '%' . $search . '%';
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    
    $stmt->execute();
    $interactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retourner les résultats au format JSON
    echo json_encode([
        'success' => true,
        'data' => $interactions,
        'count' => count($interactions)
    ]);
    
} catch (PDOException $e) {
    error_log('Erreur lors de la récupération des interactions: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des interactions'
    ]);
}
?>
