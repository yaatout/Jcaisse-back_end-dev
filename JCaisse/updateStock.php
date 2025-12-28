
<?php

    require('connection.php');

    date_default_timezone_set('Africa/Dakar');
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

    $nomtableDesignation="THIAW ET FRERES-designation";
    $nomtableStock="THIAW ET FRERES-stock";
    $nomtableEntrepotStock="THIAW ET FRERES-entrepotstock";

    $sql0="SELECT * FROM `".$nomtableDesignation."` where classe = 0 order by designation";
    $res0=mysql_query($sql0) or die ("select stock impossible =>".mysql_error());

    while ($d=mysql_fetch_array($res0)) {
        # code...
        $id=$d['idDesignation'];
        $nombreArticleUniteStock=$d['nbreArticleUniteStock'];
        $prixUniteStock=$d['prixuniteStock'];

        $sqlI="SELECT SUM(quantiteStockinitial)
        FROM `".$nomtableStock."`
        where idDesignation ='".$id."'";
        $resI=mysql_query($sqlI) or die ("select stock impossible =>".mysql_error());
        $I_stock = mysql_fetch_array($resI);

        $sqlE="SELECT SUM(quantiteStockinitial)
        FROM `".$nomtableEntrepotStock."`
        where idDesignation ='".$id."' AND (idTransfert=0 OR idTransfert IS NULL)  ";
        $resE=mysql_query($sqlE) or die ("select stock impossible =>".mysql_error());
        $E_stock = mysql_fetch_array($resE);

        $diff=$I_stock[0]-$E_stock[0];     
        $quantite=$diff*(-1);   
        var_dump($d['designation']." -> ".$diff." / ".$quantite);
        
        // $sql1='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, 
        // quantiteStockCourant,dateExpiration,iduser) 
        // VALUES('.$id.',"'.mysql_real_escape_string($d['designation']).'",'.$quantite.',"'.mysql_real_escape_string($d['uniteStock']).'"
        // ,'.$prixUniteStock.','.$nombreArticleUniteStock.',0,"'.$dateString.'",0,"'.$dateString.'",0)';

        // $res1=@mysql_query($sql1) or die ("insertion stock 2 impossible".mysql_error()) ;

    }