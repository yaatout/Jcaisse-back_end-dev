<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:06/12/2019
*/
session_start();

if(!$_SESSION['iduser']){
    header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');

session_destroy();
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="./JCaisse/index.php"</script>';
echo'</head>';
echo'</html>';

?>