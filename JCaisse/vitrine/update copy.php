<?php
require('../connection.php');
require('../connectionVitrine.php');

require('../declarationVariables.php');
    function find_p_with_position($pns,$des) {
        foreach($pns as $index => $p) {
            if(($p['designation'] == $des)){    
                return $index;              
            }
        }
        return FALSE;
      }

    /************* get reference jcaisse ****************/
    $sqlGetRefJC="SELECT * FROM `".$nomtableDesignation."`";
    $refJC = mysql_query($sqlGetRefJC) or die ("persoonel requête 1".mysql_error());
    // $refJcaisse = mysql_fetch_assoc($refJC);
    // var_dump($refJcaisse);

    /************* get reference vitrine ****************/
    $sqlGetRefV = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."`");
    $sqlGetRefV->execute() or die(print_r($sqlGetRefV->errorInfo()));
    $refVitrine=$sqlGetRefV->fetchAll();
    // var_dump($refVitrine[0]);
    while ($key = mysql_fetch_array($refJC)) {
        if (find_p_with_position($refVitrine, $key['designation']) !==FALSE) {
            $i=find_p_with_position($refVitrine, $key['designation']);
            // if ($key['designation'] == 'CHARGEUR SAMSUNG ORIGINAL DUAL FAST CHARGE') {
            //     # code...
            // var_dump($key);
            // var_dump($refVitrine[$i]);

            // }
            /****** Update panier *****/    
            $req20 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET prix = :pu, prixuniteStock = :pus, uniteStock = :us, nbreArticleUniteStock = :nbus WHERE idDesignation = :idD");
            $req20->execute(array(
                'pu' => $key['prix'],
                'pus' => $key['prixuniteStock'],
                'us' => $key['uniteStock'],
                'nbus' => $key['nbreArticleUniteStock'],
                'idD' => $key['idDesignation']
            )) or die(print_r($req20->errorInfo()));

        }else{      

            $req4 = $bddV->prepare("INSERT INTO
            `".$nomtableDesignation."` (idDesignation,designation,designationJcaisse,categorie,uniteStock,nbreArticleUniteStock,prix,prixuniteStock,idBoutique)
            VALUES(:idD,:des,:desJC,:categorie,:us,:nbus,:prix,:pus,:idB)") ;
            $req4->execute(array(
                'idD' => $key['idDesignation'],
                'des' => $key['designation'],
                'desJC' =>$key['designation'],
                'categorie' => $key['categorie'],
                'us' => $key['uniteStock'],
                'nbus' => $key['nbreArticleUniteStock'],
                'prix' => $key['prix'],
                'pus' => $key['prixuniteStock'],
                'idB' => $_SESSION['idBoutique']
            ))  or die(print_r($req4->errorInfo()));
            $req4->closeCursor();
        }       
    }
?>