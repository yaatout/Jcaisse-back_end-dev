<?php
	session_start();
	if(!$_SESSION['iduser']){
		header('Location:../../index.php');
	}

require('../connection.php');

		$nomtableDesignation=$_SESSION['nomB']."-designation";
		$designation=htmlspecialchars($_POST['designation']);
		$reqS="SELECT * from `".$nomtableDesignation."` where designation='".$designation."'";
		$resS=mysql_query($reqS) or die ("insertion impossible");
		$data=mysql_fetch_array($resS);
		$reponse=$data['prix']."-".$data['prixuniteStock']."-".$data['uniteStock'];
		// var_dump($reponse);
		exit($reponse);

 ?>
