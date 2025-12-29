<?php
session_start();
if(!isset($_SESSION['iduserBack'])){
    die('Non autorisé');
}

require('../../connectionPDO.php');

// Vérifier si l'ID du client est fourni
$idClient = filter_input(INPUT_GET, 'idClient', FILTER_VALIDATE_INT);
if (!$idClient) {
    echo '<div class="alert alert-warning">Aucun client spécifié.</div>';
    exit();
}

try {
    // Récupérer les interactions du client
    $query = $bdd->prepare("SELECT i.*, u.nom as nom_utilisateur, u.prenom as prenom_utilisateur 
                           FROM `aaa-boutique-prospectInteraction` i
                           LEFT JOIN `aaa-utilisateur` u ON i.idUtilisateur = u.idUtilisateur
                           WHERE i.idClient = :idClient 
                           ORDER BY i.dateInteraction DESC, i.dateCreation DESC");
    $query->execute([':idClient' => $idClient]);
    $interactions = $query->fetchAll(PDO::FETCH_ASSOC) or die(print_r($query->errorInfo()));

    if (empty($interactions)) {
        echo '<div class="alert alert-info">Aucune interaction enregistrée pour ce client.</div>';
        exit();
    }

    // Afficher le tableau des interactions
    echo '<div class="table-responsive">';
    echo '<table class="table table-striped table-hover">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Date/Heure</th>';
    echo '<th>Type</th>';
    echo '<th>Notes</th>';
    echo '<th>Suivi</th>';
    echo '<th>Par</th>';
    echo '<th>Date création</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($interactions as $interaction) {
        // Formater la date et l'heure
        $dateInteraction = date('d/m/Y H:i', strtotime($interaction['dateInteraction']));
        $dateCreation = date('d/m/Y H:i', strtotime($interaction['dateCreation']));
        
        // Définir les icônes en fonction du type d'interaction
        $icones = [
            'appel' => '<i class="fa fa-phone text-primary"></i>',
            'email' => '<i class="fa fa-envelope text-info"></i>',
            'rdv' => '<i class="fa fa-calendar text-success"></i>',
            'autre' => '<i class="fa fa-comment text-warning"></i>'
        ];
        //$icone = $icones[$interaction['typeInteraction']] ?? '<i class="fa fa-question-circle"></i>';
        // Vérifier si le type d'interaction existe dans le tableau $icones
        if (isset($icones[$interaction['typeInteraction']])) {
            // Si oui, utiliser l'icône correspondante
            $icone = $icones[$interaction['typeInteraction']];
        } else {
            // Sinon, utiliser une icône par défaut
            $icone = '<i class="fa fa-question-circle"></i>';
        }
        // Afficher la ligne du tableau
        echo '<tr>';
        echo '<td>' . $dateInteraction . '</td>';
        echo '<td>' . $icone . ' ' . ucfirst($interaction['typeInteraction']) . '</td>';
        echo '<td>' . nl2br(htmlspecialchars($interaction['notes'])) . '</td>';
        echo '<td>' . htmlspecialchars($interaction['suivi']) . '</td>';
        echo '<td>' . htmlspecialchars($interaction['prenom_utilisateur'] . ' ' . $interaction['nom_utilisateur']) . '</td>';
        echo '<td>' . $dateCreation . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';

} catch (Exception $e) {
    error_log('Erreur lors de la récupération des interactions : ' . $e->getMessage());
    echo '<div class="alert alert-danger">Une erreur est survenue lors de la récupération des interactions.</div>';
}
?>
