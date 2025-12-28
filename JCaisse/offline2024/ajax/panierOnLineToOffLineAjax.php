<?php

session_start();

if(!$_SESSION['iduser']){ 

  header('Location:../index.php');

  }

require('../connectionOffline.php');
require('../declarationVariables.php');

//$idPanier=$_POST['idPagnet']


$sql7 = "SELECT * FROM `".$nomtablePagnet."`  order by idPagnet desc limit 1";

//$res7=mysql_query($sql7) or die ("selection pagniers impossible =>".mysql_error());
$res7= $pdo->prepare($sql7);
$res7->execute();
$panier=$res7->fetch(PDO::FETCH_ASSOC);

if($panier!=null){
  $result=json_encode($panier);
  
}
else{
  $result=null;

}

exit($result);
//exit($tabSearchElement);

?>