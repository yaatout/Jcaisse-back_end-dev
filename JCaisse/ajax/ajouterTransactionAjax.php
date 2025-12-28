<?php
	session_start();
	if(!$_SESSION['iduser']){
		header('Location:index.php');
	}

require('../connection.php');

		
		 $reponse="<ul><li>aucune donnee trouvé</li></ul>";
		// $query=htmlspecialchars($_POST['q']);
		 $query=htmlspecialchars($_POST['designation']);
		  $reqS="SELECT * from `aaa-transaction` where nomTransaction LIKE '%$query%'";
		  // $sql2="SELECT * from `".$nomtableStock."` where designation='".$tab["designation"]."'";
		  $resS=mysql_query($reqS) or die ("insertion impossible");

		   if($resS){
		      $reponse="<ul class='ulc'>";
		        while ($data=mysql_fetch_array($resS)) {
		         // $reponse.="<li class='lic'>".$data['designation']."-".$data['idDesignation']."</li>";
		          $reponse.="<li class='lic'>".$data['nomTransaction']."</li>";
		        }
		      $reponse.="</ul>";
		   }
		  exit($reponse);

 ?>