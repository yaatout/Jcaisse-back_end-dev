<?php
/*
 try
 {
   $bddV = new PDO('mysql:host=109.234.162.38;port=3306;dbname=nboh3098_bdvitrine','nboh3098_yatoutshxpvitrin','@dJVC]k}2qQs');
   $bddV->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 }catch(Exception $e)
 {
     die('Erreur : '.$e->getMessage());
  }

     $cnx_ftp=ftp_connect("yaatout.org");
    $cnx_ftp_auth=ftp_login($cnx_ftp,"nboh3098",'YtpUv6E3tADt');
 */   
    
    try
{
  $bdd = new PDO('mysql:host=localhost;dbname=diib8761_bdjcaisse', 'diib8761_jcaisse2', 'grandETfort2020');
  $bdd->exec("SET CHARACTER SET utf8"); 
  // echo 'ok';
}catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

  //
//   try
//   {
//     $bddV = new PDO('mysql:host=localhost;dbname=bdvitrine', 'root', '');
//   }catch(Exception $e)
//   {
//       die('Erreur : '.$e->getMessage());
//   }

//     try
//  {
//    $bddV = new PDO('mysql:host=yatoutshxpvitrin.mysql.db;dbname=yatoutshxpvitrin', 'yatoutshxpvitrin', 'vitrineYatout1');
//  }catch(Exception $e)
//  {
//      die('Erreur : '.$e->getMessage());
//  }
?>
