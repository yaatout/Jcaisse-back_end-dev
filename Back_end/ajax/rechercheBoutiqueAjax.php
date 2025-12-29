<?php
   session_start();
   if(!$_SESSION['iduserBack'])
       header('Location:../index.php');
   
require('../connection.php');
require('../declarationVariables.php');
    # code...
    if (!isset($_POST['operation'])) {
        $reponse="<ul><li>Boutique inexistante ou desactivée</li></ul>";
        $data = json_decode(file_get_contents('php://input'), true);
        $query=$data["query"];
        $idUser=$data["user"];
        //$reqS="SELECT * from `aaa-boutique` where enTest=1 and activer=1 and labelB LIKE '%$query%'";
        $reqS="SELECT * from `aaa-boutique` where activer=1 and labelB LIKE '%$query%'";
        $resS=mysql_query($reqS) or die ("insertion impossible".mysql_error());
        if ($resS) {
            $html="<ul class='ulcB'>";
            while ($data=mysql_fetch_array($resS)) {
                $html.="<li class='licRB' id='liID".$data['idBoutique']."' onclick=\"choisirBoutique(".$data['idBoutique'].",". $idUser.")\">".$data['labelB']."</li>";
            }
            $html.="</ul>";
        }else {
            $html="<ul><li>erreur requette</li></ul>";
        }        
        exit($html);
    } else {
        if (isset($_POST['operation']) and $_POST['operation']=='ajouterAcces') {
            $proprietaire=$_POST['proprietaire'];
            $gerant=$_POST['gerant'];
            $caissier=$_POST['caissier'];
            $vendeur=$_POST['vendeur'];
            $idBout=$_POST['b'];
            $idUser=$_POST['u'];

            $sql3="INSERT INTO `aaa-acces` (idutilisateur, idBoutique,proprietaire,gerant,caissier,vendeur) 
            VALUES ('".$idUser."','".$idBout."', '".$proprietaire."', '".$gerant."', '".$caissier."', '".$vendeur."')";
            $req3=@mysql_query($sql3) or die ("insertion dans acces impossible".mysql_error());
            exit($req3);           
        } else if (isset($_POST['operation']) and $_POST['operation']=='supprimerAcces') {
            
            
            $idBout=$_POST['b'];
            $idUser=$_POST['u'];
            $sql="DELETE FROM `aaa-acces` WHERE idutilisateur=".$idUser." and idBoutique=".$idBout;
            $res=@mysql_query($sql) or die ("suppression impossible personnel     ".mysql_error());            
            exit($res);           
        }
        
    }
    
    
    


?>