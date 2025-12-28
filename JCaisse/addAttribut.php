<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Mor Mboup
Date de création : 03/10/2023
Date derni�re modification : 
*/

session_start();


if (!$_SESSION['iduser']) {
	header('Location:../index.php');
}

// if ($_SESSION['iduser']) {
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

// var_dump((time()/100));
// var_dump($_SESSION['idBoutique']);

// $sql17="SELECT * FROM `aaa-boutique`";
// $res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
// while ($b = mysql_fetch_array($res17)) {
// 	// var_dump($b)
// 	$nomBoutiqueP=$b['nomBoutique']."-pagnet";
// 	$nomBoutiqueL=$b['nomBoutique']."-lignepj";
// 	$sql20="ALTER TABLE `".$nomBoutiqueP."` ADD COLUMN IF NOT EXISTS `validerProforma` INT NULL DEFAULT NULL;";
// 	$sql21="ALTER TABLE `".$nomBoutiqueP."` ADD COLUMN IF NOT EXISTS `terminerProforma` INT NULL DEFAULT NULL;";
// 	$sql22="ALTER TABLE `".$nomBoutiqueP."` ADD COLUMN IF NOT EXISTS `nbColis` INT NULL DEFAULT NULL;";
// 	$sql23="ALTER TABLE `".$nomBoutiqueL."` ADD COLUMN IF NOT EXISTS `depotConfirm` INT NULL DEFAULT NULL;";
// 	$res12 =@mysql_query($sql20) or die ("validerProforma ".mysql_error());
// 	$res13 =@mysql_query($sql21) or die ("terminerProforma ".mysql_error());
// 	$res14 =@mysql_query($sql22) or die ("nbColis ".mysql_error());
// 	$res15 =@mysql_query($sql23) or die ("depotConfirm ".mysql_error());
// 	// var_dump(111);
// }
/**********************/

	// $sql50="ALTER TABLE `aaa-boutique` ADD IF NOT EXISTS `compte` int(11) NOT NULL;";
	// $res50 =@mysql_query($sql50) or die ("creation table compte impossible ".mysql_error());
	// $sql17="SELECT * FROM `aaa-boutique`";
	// $res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
	// while ($b = mysql_fetch_array($res17)) {
	// 	// var_dump($b)
	// 	$nomBoutiqueP=$b['nomBoutique']."-pagnet";
	// 	$val = mysql_query('select * from `'.$nomBoutiqueP.'` LIMIT 1');
		
	// 	if(!$val) {
	// 		$z=$Vasl;;
	// 	} else {
				
	// 		$sql21="ALTER TABLE `".$nomBoutiqueP."` ADD COLUMN IF NOT EXISTS `nbPcsInContainer` int NOT NULL;";
		
	// 		$sql22="ALTER TABLE `".$nomBoutiqueP."` ADD COLUMN IF NOT EXISTS `codeBarrePcsInContainer` varchar(30) NOT NULL;";

	// 		$res21 =@mysql_query($sql21) or die ("add attribut 1 ".mysql_error());
	// 		$res22 =@mysql_query($sql22) or die ("add attribut 2 ".mysql_error());
	// 	}
	// }

	// UPDATE `Zig supermarche lampfall-stock` SET `quantiteStockTemp`=`quantiteStockCourant` WHERE 1;
	// $tabId = [];
	// $sql17="SELECT * FROM `Zig supermarche lampfall-stock` where quantiteStockCourant>0";
	// $res17 = mysql_query($sql17) or die ("personel requête 2".mysql_error());
	// while ($b = mysql_fetch_array($res17)) {
	// 	// var_dump($b)
	// 	if (in_array($b['idDesignation'], $tabId)) {
			
	// 	} else {
			
	// 		$sqlS="SELECT SUM(quantiteStockCourant) FROM `".$nomtableStock."` where idDesignation ='".$b['idDesignation']."'  ";
	// 		$resS=mysql_query($sqlS) or die ("select stock impossible =>".mysql_error());
	// 		$S_stock = mysql_fetch_array($resS);
		
	// 		$sqlS0="UPDATE `Zig supermarche lampfall-stock` set quantiteStockTemp=".$S_stock[0]." where idDesignation=".$b['idDesignation'];
			
	// 		$resS0=mysql_query($sqlS0) or die ("update quantiteStockCourant impossible =>".mysql_error());

	// 		$tabId[] = $b['idDesignation'];
			
	// 	}
	// }
	?>

	<body onload="window.print()">
		
	<div id="print">
		<h1>My Heading</h1>
		<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio laudantium accusantium voluptas ea? Voluptate placeat accusamus recusandae quaerat dolores illum ea quidem odio maiores laboriosam sint explicabo facilis, quia dicta!</p>
	</div>

	<script>
		printJS({ 
          maxWidth: 375, // the width of your paper
          printable: 'print', // the id
          type: 'html',
          css: 'print.css' // your css
		});
	</script>
	</body>
