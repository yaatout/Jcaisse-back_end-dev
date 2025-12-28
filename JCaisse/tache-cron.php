

<?php



session_start();

if(!$_SESSION['iduser']){

  header('Location:index.php');

  }



  require('connection.php');
  require('connectionPDO.php');

  require('connectionVitrine.php');
  
  require('declarationVariables.php');



    /**Debut informations sur la date d'Aujourdhui **/
    $beforeTime = '00:00:00';
    $afterTime = '06:00:00';

    /**Debut informations sur la date d'Aujourdhui **/
    if($_SESSION['Pays']=='Canada'){ 
        $date = new DateTime();
        $timezone = new DateTimeZone('Canada/Eastern');
    }
    else {
        $date = new DateTime();
        $timezone = new DateTimeZone('Africa/Dakar');
    }
    $date->setTimezone($timezone);
    $heureString=$date->format('H:i:s');

    if ($heureString >= $beforeTime && $heureString < $afterTime) {
        // var_dump ('is between');
    $date = new DateTime (date('d-m-Y',strtotime("-1 days")));
    }


    $annee =$date->format('Y');

    $mois =$date->format('m');

    $jour =$date->format('d');

    $dateString=$annee.'-'.$mois.'-'.$jour;

    $dateString2=$jour.'-'.$mois.'-'.$annee;

/**Fin informations sur la date d'Aujourdhui **/


    $sql="SELECT * FROM `aaa-boutique` where tampon=1 and activer=1 and idBoutique=192";
        
    // var_dump($sql);
    $statement = $bdd->prepare($sql);
    $statement->execute();
    $boutiques = $statement->fetchAll(PDO::FETCH_ASSOC); 

    foreach ($boutiques as $b) {

        $nomtableLigne = $b['nomBoutique']."-lignepj";
        $nomtableLigneTampon = $b['nomBoutique']."-lignepjTampon";
        $nomtablePagnet = $b['nomBoutique']."-pagnet";
        $nomtablePagnetTampon = $b['nomBoutique']."-pagnetTampon";
        $nomtableVersement = $b['nomBoutique']."-versement";
        $nomtableComptemouvement = $b['nomBoutique']."-comptemouvement";

        /******* Début déplacement tampon vers standard*******/
        $sql="SELECT * FROM `".$nomtablePagnetTampon."` where verrouiller=1";
            
        // var_dump($sql);
        $statement = $bdd->prepare($sql);
        $statement->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statement->errorInfo(),true).'</pre><br>'));
        $tamponsP = $statement->fetchAll(PDO::FETCH_ASSOC); 

        $bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        try {
            // From this point and until the transaction is being committed every change to the database can be reverted
            $bdd->beginTransaction();  /**** generate barcode ****/

            foreach ($tamponsP as $tp) {
                // var_dump($tp);

                if ($tp["synchronise"] == 0) {

                    $sqlL="SELECT * FROM `".$nomtableLigneTampon."` where idPagnet = ".$tp['idPagnet'];
                
                    // var_dump($sqlL);
                    $statementL = $bdd->prepare($sqlL);
                    $statementL->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statementL->errorInfo(),true).'</pre><br>'));
                    $tamponsL = $statementL->fetchAll(PDO::FETCH_ASSOC); 

                    $req4 = $bdd->prepare("INSERT INTO `".$nomtablePagnet."` (datepagej,heurePagnet,iduser,type,totalp,apayerPagnet,verrouiller,idClient,remise,restourne,versement,idCompte,avance,idCompteAvance,tampon)
                    values (:d,:h,:u,:t,:tp,:ap,:v,:c,:r,:res,:ver,:idC,:av,:idcav,:tam)");
                    // var_dump($req4);
                    $req4->execute(array(
                        'd' => $tp['datepagej'],
                        'h' => $tp['heurePagnet'],
                        'u' => $tp['iduser'],
                        't' => $tp['type'],
                        'tp' => $tp['totalp'],
                        'ap' => $tp['apayerPagnet'],
                        'v' => 1,
                        'c' => $tp['idClient'],
                        'r' => $tp['remise'],
                        'res' => $tp['restourne'],
                        'ver' => $tp['versement'],
                        'idC' => $tp['idCompte'],
                        'av' => $tp['avance'],
                        'idcav' => $tp['idCompteAvance'],
                        'tam' => 1
                    ))  or die(file_put_contents("getCronLog.html",'<pre>'. print_r($req4->errorInfo(),true).'</pre><br>'));
                    $req4->closeCursor();
                    $idPagnet = $bdd->lastInsertId();
                    // var_dump($idPagnet);

                    foreach ($tamponsL as $tl) {
                        // var_dump($tl);
                        $preparedStatement = $bdd->prepare(
                            "insert into `".$nomtableLigne."` (designation, idDesignation, unitevente, prixunitevente, quantite, prixtotal, idPagnet, classe)
                            values (:d,:idd,:uv,:pu,:qty,:p,:idp,:c)"
                        );
                    // var_dump($preparedStatement);
                        
                        $preparedStatement->execute([
                            'd' => $tl['designation'],
                            'idd' => $tl['idDesignation'],
                            'uv' => $tl['unitevente'],
                            'pu' => $tl['prixunitevente'],
                            'qty' => $tl['quantite'],
                            'p' => $tl['prixtotal'],
                            'idp' => $idPagnet,
                            'c' => $tl['classe']
                        ]) or die(file_put_contents("getCronLog.html",'<pre>'. print_r($preparedStatement->errorInfo(),true).'</pre><br>'));
                    }
                    
                    $sqlU="UPDATE `".$nomtablePagnetTampon."` SET synchronise=1 where idPagnet=".$tp['idPagnet'];
                    
                    $statementU = $bdd->prepare($sqlU);
                    $statementU->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statementU->errorInfo(),true).'</pre><br>'));
                
                        
                }
                // mouvements relatifs à ce panier

                if ($tp['avance'] != 0) {
                                    
                    $sqlU="UPDATE `".$nomtableVersement."` SET idPagnet=".$idPagnet." where idPagnet=".$tp['idPagnet'];
                    
                    $statementU = $bdd->prepare($sqlU);
                    $statementU->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statementU->errorInfo(),true).'</pre><br>'));
                }
                    
                $sqlU="UPDATE `".$nomtableComptemouvement."` SET mouvementLink=".$idPagnet." where mouvementLink=".$tp['idPagnet'];
                
                $statementU = $bdd->prepare($sqlU);
                $statementU->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statementU->errorInfo(),true).'</pre><br>'));
                
            }

            // Make the changes to the database permanent
            $bdd->commit();
            // header("Refresh:0");
        }
        catch ( PDOException $e ) { 
            // Failed to insert the order into the database so we rollback any changes
            $bdd->rollback();
            throw $e;

            // echo '0';
        }
        /******* Fin déplacement tampon vers standard*******/

        /****** Suppression des informations tampon après synchronisation********/
        try {
            
            // From this point and until the transaction is being committed every change to the database can be reverted
            $bdd->beginTransaction();  /**** generate barcode ****/
            
            $sqlU="UPDATE `".$nomtablePagnet."` SET idPagnetTampon=0 where 1";
            
            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statementU->errorInfo(),true).'</pre><br>'));

            $sqlU="UPDATE `".$nomtableLigne."` SET numligneTampon=0 where 1";
            
            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statementU->errorInfo(),true).'</pre><br>'));

            
            $sqlU="TRUNCATE TABLE `".$nomtablePagnetTampon."`";
            
            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statementU->errorInfo(),true).'</pre><br>')); 
        
            $sqlU="TRUNCATE TABLE `".$nomtableLigneTampon."`";
            
            $statementU = $bdd->prepare($sqlU);
            $statementU->execute() or die(file_put_contents("getCronLog.html",'<pre>'. print_r($statementU->errorInfo(),true).'</pre><br>'));
            
            // Make the changes to the database permanent
            $bdd->commit();

        } 
        catch ( PDOException $e ) { 
            // Failed to insert the order into the database so we rollback any changes
            $bdd->rollback();
            throw $e;
        }
            
    }

    file_put_contents("getCronLog.html",'<pre>'.$dateString2.' ////////////////////</pre><br>');
?>