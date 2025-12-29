<?php 
    function calculerSalaireTotalAccompagnateur(PDO $pdo, string $matricule) {
        $query = "SELECT SUM(partAccompagnateur) as total 
                  FROM `aaa-payement-salaire` 
                  WHERE accompagnateur = :matricule 
                  AND aPayementBoutique = 1   
                  AND aSalaireAccompagnateur=0";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['matricule' => $matricule]);
        
        return (float)$stmt->fetchColumn();
    }

    function listePartUnAccompagnateur(PDO $pdo, string $matricule) {
        $query = "SELECT * 
                  FROM `aaa-payement-salaire` 
                  WHERE accompagnateur = :matricule 
                  ORDER BY datePS DESC ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['matricule' => $matricule]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>