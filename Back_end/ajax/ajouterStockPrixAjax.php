<?php 
	session_start();
	if(!$_SESSION['iduser']){
		header('Location:index.php');
	}
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");
		$nomtableDesignation=$_SESSION['nomB']."-designation";
		$idDesignation=htmlspecialchars($_POST['idDesignation']);
		$reqS="SELECT * from `".$nomtableDesignation."` where idDesignation='".$idDesignation."'";
		$resS=mysql_query($reqS) or die ("insertion impossible");
		$data=mysql_fetch_array($resS);
		$reponse=$data['prix']."-".$data['prixuniteStock'];
		// var_dump($reponse);     
		exit($reponse);

 ?>