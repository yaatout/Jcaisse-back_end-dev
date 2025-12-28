
<?php

// idEntrepot: depot,SELECT * FROM `THIAW ET FRERES-entrepotstock` WHERE `idEntrepot`=5 AND `idDesignation`=6

// idDesignation: designation,
// SELECT `idDesignation`,`idEntrepot`, `quantiteStockCourant` FROM `thiaw et freres-entrepotstock` WHERE `idEntrepot`=2 and `dateStockage`='2022-07-28' order by `quantiteStockCourant` desc
// quantite: quantite,

require('connection.php');

/**Debut informations sur la date d'Aujourdhui **/
    date_default_timezone_set('Africa/Dakar');

    $date = new DateTime();

    $timezone = new DateTimeZone('Africa/Dakar');

    $date->setTimezone($timezone);

    $annee =$date->format('Y');

    $mois =$date->format('m');

    $jour =$date->format('d');

    $heureString=$date->format('H:i:s');

    $dateString=$annee.'-'.$mois.'-'.$jour;

    $dateString2=$jour.'-'.$mois.'-'.$annee;

/**Fin informations sur la date d'Aujourdhui **/

$idProduit = @$_POST['idProduit'];
$quantite = @$_POST['quantite'];

// $table1="lamp fall distribution-designation";
$table1="THIAW ET FRERES-designation";
// $table2="THIAW ET FRERES-designation";
// $table3="THIAW ET FRERES-designation";
$nomtableEntrepotStock = "THIAW ET FRERES-entrepotstock";
// $nomtableEntrepotStock = "lamp fall distribution-entrepotstock";
// $nomtableInventaire = "lamp fall distribution-inventaire";
$nomtableInventaire = "THIAW ET FRERES-inventaire";

function find_p_with_position($pns,$des) {
    foreach($pns as $index => $p) {
        if(($p[0] == $des)){
            return $index;
        }
    } 
    return FALSE;
}

$sql1="Select * from `".$table1."` where classe=0";
$res1=mysql_query($sql1) or die ("persoonel requête 2".mysql_error());
// $data_tab1=mysql_fetch_array($res1);
// var_dump($res1);


    if (($open = fopen("thiaw_et_freres-designation_SAPROLAIT.csv", "r")) !== FALSE) 
    {

    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) 
    {        
        $array[] = $data; 
    }

    fclose($open);
    }
    // echo "<pre>";
    // //To display array data
    // var_dump($array);
    // echo "</pre>";
/************************************* */
    $idEntrepot=1;
    // $idEntrepot=$array[0][1];
    // var_dump($idEntrepot);


// $sql2="SELECT * from `".$table1."` where classe=0";
// $res2=$db->query($sql2);
// $data_tab2=$res2->fetchAll();ù
    $data_tab1 = [];

    while ($key=mysql_fetch_array($res1)) {
        $data_tab1[]=$key;
    }

    foreach ($data_tab1 as $key) {
        // while ($key=mysql_fetch_assoc($res1)) {
        // var_dump($key);
        
        //         # code...

        if (find_p_with_position($array, $key['idDesignation']) !==FALSE) {

            $i=find_p_with_position($array, $key['idDesignation']);

            $quantite=$array[$i][1];

            // var_dump($array[$i][0]." / ".$array[$i][2]." || ".$key['idDesignation']." / ".$key['quantite']);

            $sql1="Select sum(quantiteStockCourant) from `".$nomtableEntrepotStock."` where idEntrepot=".$idEntrepot." and idDesignation=".$key['idDesignation'];
            // $sql1="Select sum(quantiteStockCourant) from `".$nomtableEntrepotStock."` where idEntrepot=".$key['idEntrepot']." and idDesignation=".$key['idDesignation'];
            $res1=mysql_query($sql1) or die ("update quantiteStockCourant impossible =>".mysql_error());
            // $res1=$db->query($sql1);
            $result1=mysql_fetch_array($res1);
            // $result1=$res1->fetch();
            $quantiteStockCourantTotal=($result1[0]) ? $result1[0] : 0 ;

            var_dump($quantiteStockCourantTotal);
            // var_dump($result1[0]." / ".$quantiteStockCourantTotal);
            

            /*********************** Mettre quantiteStockCourant à jour 0 *************************/
            $sqlS0="UPDATE `".$nomtableEntrepotStock."` set quantiteStockCourant=0 where idEntrepot=".$idEntrepot." and idDesignation=".$key['idDesignation'];
            // var_dump($sqlS0);
            $resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());
            
            if ($quantite > 0) {
                # code...

                /*********************** Inserer dans inventaire quantiteStockCourant *************************/
                $sql4='INSERT INTO `'.$nomtableInventaire.'` (idEntrepotStock,idDesignation,quantite,nbreArticleUniteStock,quantiteStockCourant,dateInventaire,type)

                VALUES(0,'.$key['idDesignation'].',0,'.$key['nbreArticleUniteStock'].','.$quantiteStockCourantTotal.',"'.$dateString.'",0)';
                // var_dump($sql4);
                $res4=@mysql_query($sql4) or die ("insertion stock 2 impossible".mysql_error()) ;

                /**************** Insert nouvel stock ****************/

                $totalArticleStock=$quantite*$key['nbreArticleUniteStock'];
                // var_dump($totalArticleStock);

                $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant) VALUES (0,'.$idEntrepot.','.$key['idDesignation'].',"'.mysql_real_escape_string($key["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($key['uniteStock']).'",'.$key['nbreArticleUniteStock'].','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.')';
                
                $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;
                
                // break;

            }
        }
    }

?>