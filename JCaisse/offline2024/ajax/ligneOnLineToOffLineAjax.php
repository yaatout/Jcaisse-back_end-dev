<?php

session_start();

if(!$_SESSION['iduser']){ 

  header('Location:../index.php');

  }

require('../connectionOffline.php');
require('../declarationVariables.php');

//$idPanier=$_POST['idPagnet']


$sql = "SELECT * FROM `".$nomtableLigne."`  order by numLigne desc limit 1";

//$res=mysql_query($sql) or die ("selection lignes impossible =>".mysql_error());
$res= $pdo->prepare($sql);
$res->execute();
$ligne=$res->fetch(PDO::FETCH_ASSOC);

if($ligne!=null){
  $result=json_encode($ligne);
  
}
else{
  $result=null;

}

exit($result);
//exit($tabSearchElement);

?>