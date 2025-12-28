<?php
	session_start();
	if(!$_SESSION['iduser']){
		header('Location:index.php');
	}

require('../connection.php');

require('../declarationVariables.php');



  //$reponse+=sizeof($codeBrute);

  $source = [];

  // $reponse="<ul><li>aucune donnee trouvé</li></ul>";

  	$query=htmlspecialchars(trim($_POST['query']));

	$sqlD="SELECT  * FROM `".$nomtableDesignation."` d WHERE (d.classe=0 || d.classe=1) AND d.designation LIKE '%$query%'  ";

	$resD = mysql_query($sqlD) or die ("persoonel requête 2".mysql_error());

  if (($_SESSION['type']=="Pharmacie") && ($_SESSION['categorie']=="Detaillant")) {

      while ($data=mysql_fetch_array($resD)) {

        $source[] = $data['designation'].' : Prix = '.$data['prixPublic'];

      }

  }
  else if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
	  while ($data=mysql_fetch_array($resD)) {

        $source[] = $data['designation'].' : Prix = '.$data['prixuniteStock'];

      }	
  }

  else{

	  while ($data=mysql_fetch_array($resD)) { 

        $source[] = $data['designation'].' : Article = '.$data['prix'].' : '.$data['uniteStock'].' = '.$data['prixuniteStock'];

      }

  }

  // exit($reponse);

  echo json_encode($source);

 ?>
