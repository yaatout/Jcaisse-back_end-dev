
<?php



session_start();

// if(!$_SESSION['iduser']){

//   header('Location:../index.php');

//   }

require('connection.php');


require('declarationVariables.php');



/**Debut informations sur la date d'Aujourdhui **/

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


$sql3='SELECT * from  `'.$nomtableStock.'`';

$res3=mysql_query($sql3);

    
while ($key = mysql_fetch_assoc($res3)) {
      # code...
    var_dump($key);

    $sql_t="SELECT SUM(quantiteStockinitial) FROM `".$nomtableStock."`

    where idDesignation=".$key['idDesignation']." GROUP BY idDesignation";

    $res_t=mysql_query($sql_t) or die ("select stock impossible =>".mysql_error());

    $t_stock = mysql_fetch_array($res_t) ;

    $quantite = $t_stock[0];
    
    $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

    VALUES('.$key['idStock'].',1,'.$key['idDesignation'].',"'.mysql_real_escape_string($key["designation"]).'",'.$quantite.',"'.mysql_real_escape_string($key['uniteStock']).'",'.$key['nbreArticleUniteStock'].','.$quantite.',"'.$dateString.'",'.$quantite.')';

    $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

    if ($res1) {
        # code...
        $sql2="update `".$nomtableStock."` set quantiteStockCourant=0 where idDesignation=".$key['idDesignation'];

        //echo $sql2;

        $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());
    }

}

?>