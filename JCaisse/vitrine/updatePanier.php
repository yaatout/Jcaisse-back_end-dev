<?php
// session_start();

// require('../connection.php');
// require('../connectionVitrine.php');

// require('../declarationVariables.php');

    function find_p_with_position($pns,$des) {
        foreach($pns as $index => $p) {
            if(($p['designation'] == $des)){
              // if ($p['unite'] === $u) {
              //   return $index;
              // } else { 
              //   // $nbr = $p['nbreArticleUniteStock'];             
                return $index;
              // }
            }
        }
        return FALSE;
      }

    if(isset($_POST['btnUpdatePanier'])){
        $idPanier = $_POST['idPanier'];
        
        /************* get reference vitrine ****************/
        $sqlGetRefV = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."`");
        $sqlGetRefV->execute() or die(print_r($sqlGetRefV->errorInfo()));
        $refVitrine=$sqlGetRefV->fetchAll();
        // var_dump($refVitrine[0]);
        
        $sqlGetLV = $bddV->prepare("SELECT * FROM ligne where idPanier = ".$idPanier." and barrer = 0");
        $sqlGetLV->execute() or die(print_r($sqlGetLV->errorInfo()));
        $lignesV=$sqlGetLV->fetchAll();
        // var_dump($lignesV);
        $totalpV = 0;

        foreach ($lignesV as $val) {
            if (find_p_with_position($refVitrine, $val['designation']) !==FALSE) {
                $i=find_p_with_position($refVitrine, $val['designation']);
                // if ($key['designation'] == 'CHARGEUR SAMSUNG ORIGINAL DUAL FAST CHARGE') {
                //     # code...
                // var_dump($key);
    
                // }
                if ($val['unite'] !== 'article') {
                    
                    /****** Update panier *****/ 
                    $sqlGetLV = $bddV->prepare("UPDATE ligne SET prix = ".$refVitrine[$i]['prixuniteStock'].", prixTotal = ".$refVitrine[$i]['prixuniteStock'] * $val['quantite']." where idArticle = ".$val['idArticle']);
                    $sqlGetLV->execute() or die(print_r($sqlGetLV->errorInfo()));

                    $totalpV += $refVitrine[$i]['prixuniteStock'] * $val['quantite'];
                } else {
                    //  var_dump($refVitrine[$i]);
                    /****** Update panier *****/ 
                    $sqlGetLV = $bddV->prepare("UPDATE ligne SET prix = ".$refVitrine[$i]['prix'].", prixTotal = ".$refVitrine[$i]['prix'] * $val['quantite']." where idArticle = ".$val['idArticle']);
                    $sqlGetLV->execute() or die(print_r($sqlGetLV->errorInfo()));

                    $totalpV += $refVitrine[$i]['prix'] * $val['quantite'];
                }
    
            }
        }
        
                /****** Update panier *****/ 
        $sqlGetPV = $bddV->prepare("UPDATE panier SET total = ".$totalpV." where idPanier = ".$idPanier);
        $sqlGetPV->execute() or die(print_r($sqlGetPV->errorInfo()));
        /************* get reference jcaisse ****************/
        
        // $sqlGetPanier="SELECT * FROM `".$nomtablePagnet."` where idVitrine = ".$idPanier;
        // $panier = mysql_query($sqlGetPanier) or die ("persoonel requête 1".mysql_error());
        // $p = mysql_fetch_array($panier);

        // $sqlGetLignes="SELECT * FROM `".$nomtableLigne."` where idPagnet = ".$p['idPagnet'];
        // $lignes = mysql_query($sqlGetLignes) or die ("persoonel requête 1".mysql_error());
        // $totalp = 0;

        
        // while ($key = mysql_fetch_array($lignes)) {

        //     if (find_p_with_position($refVitrine, $key['designation']) !==FALSE) {
        //         $i=find_p_with_position($refVitrine, $key['designation']);
        //         // if ($key['designation'] == 'CHARGEUR SAMSUNG ORIGINAL DUAL FAST CHARGE') {
        //         //     # code...
        //         // var_dump($key);
    
        //         // }
        //         /****** Update panier *****/ 
                
        //         $sql="update `".$nomtableLigne."` set prixunitevente = ".$refVitrine[$i]['prix'].", prixtotal = ".$refVitrine[$i]['prix'] * $key['quantite']." where idDesignation=".$key['idDesignation'];
        //         $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error()); 

        //         $totalp += $refVitrine[$i]['prix'] * $key['quantite'];
    
        //     }
        // }
            
        //     $sqlUpdatePanier="update `".$nomtablePagnet."` set totalp = ".$totalp." where idPagnet=".$p['idPagnet'];
        //     $res1=@mysql_query($sqlUpdatePanier)or die ("modification impossible1 ".mysql_error());   
                    
    }
?> 