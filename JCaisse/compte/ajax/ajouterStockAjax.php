<?php 
	session_start();
	if(!$_SESSION['iduser']){
		header('Location:index.php');
	}
mysql_connect("localhost","root","");
mysql_select_db("bdjournalcaisse");
		$nomtableDesignation=$_SESSION['nomB']."-designation";

		 $reponse="<ul><li>aucune donnee trouvé</li></ul>";
		// $query=htmlspecialchars($_POST['q']);
		 $query=htmlspecialchars($_POST['designation']);
		  $reqS="SELECT * from `".$nomtableDesignation."` where classe='0' and designation LIKE '%$query%'";
		  // $sql2="SELECT * from `".$nomtableStock."` where designation='".$tab["designation"]."'";
		  $resS=mysql_query($reqS) or die ("insertion impossible");
		  
		   if($resS){
		      $reponse="<ul class='ulc'>";
		        while ($data=mysql_fetch_array($resS)) {
		          $reponse.="<li class='lic'>".$data['designation']."-".$data['idDesignation']."</li>";
		        }
		      $reponse.="</ul>";
		   }
		  exit($reponse);

 ?>