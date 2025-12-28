<?php
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
    ];

    try
    {
        $pdo = new PDO('mysql:host=localhost;dbname=diib8761_bdjcaisse', 'diib8761_rootjcaisse', 'grandETfort2020');
    
    }catch(PDOException $e)
    {
        die('Erreur : '.$e->getMessage());
    }

?>
