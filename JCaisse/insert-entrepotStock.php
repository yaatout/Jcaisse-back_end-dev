<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de création : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
//ob_end_flush();

session_start();


if(!$_SESSION['iduser']){
	header('Location:../index.php');
}
if($_SESSION['iduser']){
	if ((!$_SESSION['creationB']) && ($_SESSION['profil']=='Admin')){
	    echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script language="JavaScript">document.location="creationBoutique.php"</script>';
		echo'</head>';
		echo'</html>';
	}

require('connection.php');

require('declarationVariables.php');

    $sql='select * from `'.$nomtableStock.'`';

    $res=mysql_query($sql);
    $idDepot=1;

    while ($stock=mysql_fetch_array($res)) {     
        
        $sql1='INSERT INTO `'.$nomtableEntrepotStock.'` (idStock,idEntrepot,idDesignation,designation,quantiteStockinitial,uniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant)

        VALUES('.$stock['idStock'].','.$idDepot.','.$stock['idDesignation'].',"'.mysql_real_escape_string($stock["designation"]).'",'.$stock['quantiteStockinitial'].',"'.mysql_real_escape_string($stock['uniteStock']).'",'.$stock['nbreArticleUniteStock'].','.$stock['totalArticleStock'].',"'.$dateString.'",'.$stock['quantiteStockCourant'].')';
        // var_dump($sql1);
        $res1=@mysql_query($sql1) or die ("insertion stock 12 impossible".mysql_error()) ;

        
        $sql0="UPDATE `".$nomtableStock."` set quantiteStockCourant=0 WHERE idStock='".$stock['idStock']."'";
        $res0=mysql_query($sql0) or die ("update quantiteStockCourant impossible =>".mysql_error());

    }
}