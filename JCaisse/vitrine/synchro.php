<?php
// session_start();

require('../connection.php');
require('../connectionVitrine.php');

require('../declarationVariables.php');

    function find_with_idDesignation($pns,$des) {
        foreach($pns as $index => $p) {
            if(($p['idDesignation'] == $des)){
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

    // if(isset($_POST['btnUpdatePanier'])){
    //     $idPanier = $_POST['idPanier'];

        /************* get reference vitrine ****************/
        $sqlGetRefV = $bddV->prepare("SELECT * FROM `".$nomtableDesignation."`");
        $sqlGetRefV->execute() or die(print_r($sqlGetRefV->errorInfo()));
        $refVitrine=$sqlGetRefV->fetchAll();
        // var_dump($refVitrine[0]);

        $sqlGetRefJC="SELECT * FROM `".$nomtableDesignation."`";
        $refJC = mysql_query($sqlGetRefJC) or die ("persoonel requête 1".mysql_error());
        $articleJC = array();
        $totalp = 0;

        while ($key = mysql_fetch_array($refJC)) {
          $articleJC[] = $key;
        }

        foreach ($refVitrine as $val) {
            if (find_with_idDesignation($articleJC, $val['idDesignation']) !==FALSE) {
                $i=find_with_idDesignation($articleJC, $val['idDesignation']);

                $req1 = $bddV->prepare("UPDATE `".$nomtableDesignation."` SET
                          designation=:des,
                          designationJcaisse=:desJC,
                          prix=:prix,
                          uniteStock=:us,
                          prixuniteStock=:pus,
                          classe=:classe,
                          nbreArticleUniteStock=:nbas
                          WHERE idDesignation=:idDes");
                $req1->execute(array(
                            'des' => $articleJC[$i]['designation'],
                            'desJC' => $articleJC[$i]['designation'],
                            'prix' => $articleJC[$i]['prix'],
                            'us' => $articleJC[$i]['uniteStock'],
                            'pus' => $articleJC[$i]['prixuniteStock'],
                            'nbas' => $articleJC[$i]['nbreArticleUniteStock'],
                            'classe' => $articleJC[$i]['classe'],
                            'idDes' => $val['idDesignation']
                          )) or die(print_r($req1->errorInfo()));
                $req1->closeCursor();
            }
            // else {
            //   $sqlDelete = $bddV->prepare("DELETE FROM `".$nomtableDesignation."` WHERE idDesignation = :idD");
            //   $sqlDelete->execute(array(
            //               'idD' => $val['idDesignation']
            //             )) or die(print_r($sqlDelete->errorInfo()));
            //   $refVitrine=$sqlDelete->fetchAll();
            // }
        }

    // }
?>
