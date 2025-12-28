<?php

	session_start();


	require('../connexion.php');

?>


<?php

	$email              =@htmlentities($_POST["username"]);
	$motdepasse         =@htmlentities($_POST["password"]);


	$_SESSION['symbole']=' ';
	$_SESSION['devise']=0;
	$_SESSION['catalogue']='Habillement-Detaillant';
	$_SESSION['imprimante']='MJ5803';
	$_SESSION['etiquette']=1;

    $result='';
	if($email){

		//$motdepasse2=sha1($motdepasse);
		$sql="select * from `aaa-utilisateur` where email='".$email."' and motdepasse='".$motdepasse."'";
		$res=mysql_query($sql);
		if($tab=mysql_fetch_array($res)){
			$_SESSION['iduser']   = $tab["idutilisateur"];
			$_SESSION['prenom']   = $tab["prenom"];
			$_SESSION['nomU']     = $tab["nom"];
			$_SESSION['adresseU'] = $tab["adresse"];
			$_SESSION['email']    = $tab["email"];
			$_SESSION['telPortable']    = $tab["telPortable"];
			$_SESSION['telFixe']    = $tab["telFixe"];
			$_SESSION['profil']   = $tab["profil"];
			$_SESSION['creationB'] =$tab['creationB'];
			$_SESSION['password'] =$motdepasse;

			$result=$_SESSION['iduser'].' '.$_SESSION['prenom'].' '.$_SESSION['nomU'] ;
			
			$sql2="select * from `aaa-acces` where idutilisateur=".$_SESSION['iduser'];
			$res2=mysql_query($sql2);
			if ($tab2=mysql_fetch_array($res2)){
					$_SESSION['idBoutique'] = $tab2["idBoutique"];
					$_SESSION['proprietaire'] =$tab2['proprietaire'];
					$_SESSION['gerant'] =$tab2['gerant'];
					$_SESSION['caissier'] =$tab2['caissier'];
					$_SESSION['activer'] =$tab2['activer'];
					if ($_SESSION['idBoutique']==0){
						
					}else{
						$sql3="select * from `aaa-boutique` where idBoutique=".$_SESSION['idBoutique'];
						$res3=@mysql_query($sql3);
						if($tab3=@mysql_fetch_array($res3)){
							$_SESSION['nomB'] = $tab3["nomBoutique"];
							$_SESSION['labelB'] = $tab3["labelB"];
							$_SESSION['adresseB'] = $tab3["adresseBoutique"];
							$_SESSION['type'] = $tab3["type"];
							$_SESSION['categorie'] = $tab3["categorie"];
							$_SESSION['dateCB']  =$tab3["datecreation"];
							$_SESSION['activerB']  =$tab3["activer"];
							$_SESSION['caisse'] =$tab3['caisse'];
							$_SESSION['telBoutique'] =$tab3['telephone'];
							$_SESSION['RegistreCom'] =$tab3["RegistreCom"];
							$_SESSION['Ninea']  =$tab3["Ninea"];
							$_SESSION['enConfiguration']  =$tab3["enConfiguration"];
							$_SESSION['vitrine']  =$tab3["vitrine"];
							$_SESSION['importExp'] =$tab3["importExp"];
							if (($_SESSION['type']=="Entrepot") && ($_SESSION['categorie']=="Grossiste")) {
								if($tab["idEntrepot"]!=0 || $tab["idEntrepot"]!=null){
									$_SESSION['entrepot'] =$tab["idEntrepot"];
								}
								else{
									$_SESSION['entrepot']=0;
								}
							}

					}
				}
			}
		
			
	    }
    }
    exit($result);
?>



