<?php

//mysql_connect("localhost","root","");
//mysql_select_db("bdjournalcaisse");
  // try
  // {
  //   $bdd = new PDO('mysql:host=localhost;dbname=bdjournalcaisse', 'root', 'root');
  // } catch(Exception $e)
  // {
  //     die('Erreur : '.$e->getMessage());
  // }    
/*
$req = $bdd->prepare('SELECT nom, prix FROM jeux_video WHERE
possesseur = :possesseur');
$req->execute(array('possesseur' => 'Patrick', 'prixmax' => 20)) or
die(print_r($req->errorInfo()));

mysql_connect("yatoutshxpsn.mysql.db","yatoutshxpsn","Nce87xzYXtPk");
mysql_select_db("yatoutshxpsn");*/
  
try
{
  $bdd = new PDO('mysql:host=localhost;dbname=bdjournalcaisse', 'root', '');
  // echo 'ok';
}catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

?>
