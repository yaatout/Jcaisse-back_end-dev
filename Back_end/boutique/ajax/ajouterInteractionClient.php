<?php
session_start();
if(!isset($_SESSION['iduserBack'])){
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

require('../../connectionPDO.php');

// Vérifier si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit();
}

// Récupérer et valider les données du formulaire
$idClient = filter_input(INPUT_POST, 'idClient', FILTER_VALIDATE_INT);
$typeInteraction = filter_input(INPUT_POST, 'typeInteraction', FILTER_SANITIZE_STRING);
$dateInteraction = filter_input(INPUT_POST, 'dateInteraction', FILTER_SANITIZE_STRING);
$notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
$suivi = filter_input(INPUT_POST, 'suivi', FILTER_SANITIZE_STRING);
$idUtilisateur = $_SESSION['iduserBack'];

// Validation des données
if (!$idClient || !$typeInteraction || !$dateInteraction) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes ou invalides']);
    exit();
}

try {
    // Préparer la requête d'insertion
    $query = $bdd->prepare("INSERT INTO `aaa-boutique-prospectInteraction` 
                          (idClient, typeInteraction, dateInteraction, notes, suivi, idUtilisateur, dateCreation) 
                          VALUES (:idClient, :typeInteraction, :dateInteraction, :notes, :suivi, :idUtilisateur, NOW())");
    
    // Exécuter la requête avec les paramètres
    $result = $query->execute([
        ':idClient' => $idClient,
        ':typeInteraction' => $typeInteraction,
        ':dateInteraction' => $dateInteraction,
        ':notes' => $notes,
        ':suivi' => $suivi,
        ':idUtilisateur' => $idUtilisateur
    ])  ;
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Interaction enregistrée avec succès']);
    } else {
        throw new Exception("Erreur lors de l'enregistrement de l'interaction");
    }
    
} catch (Exception $e) {
    error_log('Erreur lors de l\'enregistrement de l\'interaction : ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de l\'enregistrement']);
}
?>
