<?php
 

   require('../connection.php');
   require('../connectionPDO.php');

   $stmtBoutique= $bdd->prepare("SELECT * FROM `aaa-boutique` b  ");
   $stmtBoutique->execute();
   $boutiques = $stmtBoutique->fetchAll();
   foreach ($boutiques as $boutique) {

      $tableClient = $boutique['nomBoutique']."-client";
      $tablePagnet = $boutique['nomBoutique']."-pagnet";
      $tableVersement = $boutique['nomBoutique']."-versement";

      $stmtClient_Table = $bdd->prepare("SELECT 1 FROM `".$tableClient."` ");
      $table_Existe = $stmtClient_Table->execute();
      
      if($table_Existe==true){

         var_dump($boutique['nomBoutique']);

         // Fetch records
         $stmtClient = $bdd->prepare("SELECT * FROM `".$tableClient."` ");
         $stmtClient->execute();
         $clients = $stmtClient->fetchAll();
      
         foreach ($clients as $client) {
      
            //Somme des Pagnets Bons du Client
            $stmtBons = $bdd->prepare("SELECT SUM(apayerPagnet) AS total FROM `".$tablePagnet."` WHERE idClient=:idClient AND verrouiller=1 AND (type=0 OR type=30 OR type=11) ");
            $stmtBons->bindValue(':idClient', $client['idClient'], PDO::PARAM_INT);
            $stmtBons->execute();
            $bons = $stmtBons->fetch();
      
            //Somme des Versements du Client
            $stmtVersement = $bdd->prepare("SELECT SUM(montant) AS total FROM `".$tableVersement."` WHERE idClient=:idClient ");
            $stmtVersement->bindValue(':idClient', $client['idClient'], PDO::PARAM_INT);
            $stmtVersement->execute();
            $versements = $stmtVersement->fetch();
      
            //Montant a verser du Client
            $solde = $bons['total'] - $versements['total'];
      
            if (($boutique['type']=="Pharmacie") && ($boutique['categorie']=="Detaillant")) {

               $tableMutuellePagnet = $boutique['nomBoutique']."-mutuellepagnet";

               $stmtMutuelle_Table = $bdd->prepare("SELECT 1 FROM `".$tableMutuellePagnet."` ");
               $table_Existe = $stmtMutuelle_Table->execute();
               
               if($table_Existe==true){

                  //Somme des Imputations du Client
                  $stmtMutuelles = $bdd->prepare("SELECT SUM(apayerPagnet) AS total FROM `".$tableMutuellePagnet."` WHERE idClient=:idClient AND verrouiller=1 AND (type=0 OR type=30 OR type=11) ");
                  $stmtMutuelles->bindValue(':idClient', $client['idClient'], PDO::PARAM_INT);
                  $stmtMutuelles->execute();
                  $mutuelles = $stmtMutuelles->fetch();
         
                  //Montant a verser du Client
                  $solde = $solde + $mutuelles['total'];

               }
      
            }
      
            //Mise a jour du solde de tout les clients archives
            $stmtBons = $bdd->prepare("UPDATE  `".$tableClient."` SET solde=:solde WHERE idClient=:idClient ");
            $stmtBons->bindValue(':idClient', $client['idClient'], PDO::PARAM_INT);
            $stmtBons->bindValue(':solde', $solde);
            $stmtBons->execute();
            
         }
      }

   }


