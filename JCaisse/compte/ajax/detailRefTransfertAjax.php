<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP, EL hadji mamadou korka
Date de création : 20/03/2016
Date derniére modification : 20/04/2016; 15-04-2018
*/
session_start();
/*if(!$_SESSION['iduser']){
  header('Location:../index.php');
}
*/
require('../connection.php');

require('../declarationVariables.php');


$idPS      =@$_POST["idPS"];

$data=array();

$sql="select datePS,nomBoutique,refTransfert from `aaa-payement-salaire` p INNER JOIN `aaa-boutique` b ON p.idBoutique = b.idBoutique WHERE p.idPS =".$idPS;

$res=@mysql_query($sql);
if(@mysql_num_rows($res)){
	$i=1;
	$tab=@mysql_fetch_array($res);	

  $result=$tab['datePS'].'+'.$tab['nomBoutique'].'+'.$tab['refTransfert'];
	 

exit($result);
}
?>
