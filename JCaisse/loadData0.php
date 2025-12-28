<?php 
    // require('connection.php');
    // require('declarationVariables.php');

    
	$tabSearchElement=array();
    $lignes=array();

	$reqS="SELECT * from `".$nomtableDesignation."`";

	$resS=mysql_query($reqS) or die ("insertion impossible");

	$sql="select * from `aaa-utilisateur`";
	$res=mysql_query($sql) or die ("insertion impossible");

	$reqB="SELECT * from `aaa-boutique` where idBoutique='".$_SESSION['idBoutique']."'";
	$resB=mysql_query($reqB) or die ("insertion impossible");

	$reqA="SELECT * from `aaa-acces` where caissier=1 or vendeur=1";
	$resA=mysql_query($reqA) or die ("insertion impossible");

	$reqC="SELECT * from `".$nomtableCompte."`";
	$resC=mysql_query($reqC) or die ("insertion impossible");

	$reqCli="SELECT * from `".$nomtableClient."`";
	$resCli=mysql_query($reqCli) or die ("insertion impossible");

    // var_dump($_SESSION['iduser']);
    // var_dump($dateString2);

	while($user=mysql_fetch_assoc($res)){
		if (in_array($user['idutilisateur'], $tabSearchElement)){

		}else{
			$tab[]=$user;
			//var_dump($tab);
		}
	}
	
	while($des=mysql_fetch_assoc($resS)){
		if (in_array($des['idDesignation'], $tabSearchElement)){

		}else{
			$design[]=$des;

		}
	}

	while($boutik=mysql_fetch_assoc($resB)){
		if (in_array($boutik['idBoutique'], $tabSearchElement)){

		}else{
			$tabB[]=$boutik;
			
		}
	}

	while($access=mysql_fetch_assoc($resA)){
		if (in_array($access['idutilisateur'], $tabSearchElement)){

		}else{
			$tabA[]=$access;
			
		}
	}

	while($cpt=mysql_fetch_assoc($resC)){
		if (in_array($cpt['idCompte'], $tabSearchElement)){

		}else{
			$tabC[]=$cpt;
			
		}
	}

	while($cli=mysql_fetch_assoc($resCli)){
		if (in_array($cli['idClient'], $tabSearchElement)){

		}else{
			$tabCli[]=$cli;
			
		}
	}
    $sqlP="SELECT *FROM `".$nomtablePagnet."` where datepagej ='".$dateString2."'";
    $resP=mysql_query($sqlP) or die ("insertion impossible");

    while($panier=mysql_fetch_assoc($resP)){
        if (in_array($panier['idPagnet'], $tabSearchElement)){
            //var_dump($panier['idPagnet']);
        }else{
            $tabP[]=$panier;
            $sqlL="SELECT *FROM `".$nomtableLigne."` where  idPagnet='".$panier['idPagnet']."'";
            $resL=mysql_query($sqlL) or die ("insertion impossible");
            while($ligne=mysql_fetch_assoc($resL)){
                if (in_array($ligne['numligne'], $tabSearchElement)){

                }else{
                    $lignes[]=$ligne;
                    //var_dump($lignes);
                }
            }
            // $lignes=mysql_fetch_assoc($resL);
            // array_push($lignes);
            // var_dump($lignes);
        }
    }

?>

<script type="text/javascript">

	// date = new Date();
	// sec = date.getSeconds();
	// setTimeout(()=>{
	// 	setInterval(()=>{
	// 		if(navigator.onLine==false){
	// 			// idOnline=sessionStorage.idUSessionOnline
	// 			// iduser=localStorage.getItem('iduser')
	// 			// //alert(iduser)
	// 			// idOffline=sessionStorage.iduserSession

	// 			//alert(idOnline+' '+idOffline)
	// 			//if(idOnline){
    //                 searchBoutique=boutiques.find(b=>b.idBoutique == localStorage.idBoutique);
	// 				if(searchBoutique.offline ==1){
	// 					window.location.href="/JCaisse/offline/insertionLigneLight.html";
	// 				}
    //                 //else{
	// 					//window.location.href="/JCaisse/offline/index.html";
	// 				//}	
				
	// 		}else{

    //         }

	// 	}, 60 * 1);
	// }, (60 - sec) * 1); 
	
	const design=<?php echo json_encode($design); ?>;
	localStorage.setItem('Designation', JSON.stringify(design));

	const tab=<?php echo json_encode($tab); ?>;
	localStorage.setItem('User', JSON.stringify(tab));

	const tabB=<?php echo json_encode($tabB); ?>;
	localStorage.setItem('Boutique', JSON.stringify(tabB));

	const tabA=<?php echo json_encode($tabA); ?>;
	localStorage.setItem('Access', JSON.stringify(tabA));

	const tabC=<?php echo json_encode($tabC); ?>;
	localStorage.setItem('Compte', JSON.stringify(tabC));

	const tabCli=<?php echo json_encode($tabCli); ?>;
	localStorage.setItem('Client', JSON.stringify(tabCli));
	
    users=JSON.parse(localStorage.getItem("User"));
    acces=JSON.parse(localStorage.getItem("Access"));
    boutiques=JSON.parse(localStorage.getItem("Boutique"));
    
    
    const id=<?php echo $_SESSION['iduser']; ?>;
    sessionStorage.setItem('idUSessionOnline', id);
    //localStorage.setItem('iduser',id);
    searchUser=users.find(u=>u.idutilisateur == id);
    if(searchUser){
        
        sessionStorage.setItem('iduserSession',searchUser.idutilisateur)
        localStorage.setItem('iduser',searchUser.idutilisateur)
        sessionStorage.setItem('prenomSession',searchUser.prenom)
        sessionStorage.setItem('nomUserSession',searchUser.nom)                   
        sessionStorage.setItem('adresseUserSession',searchUser.adresse)
        sessionStorage.setItem('emailSession',searchUser.email)
        sessionStorage.setItem('telPortableSession',searchUser.telPortable)
        sessionStorage.setItem('telFixeSession',searchUser.telFixe)
        sessionStorage.setItem('profilSession',searchUser.profil)
        sessionStorage.setItem('creationBSession',searchUser.creationB)
        sessionStorage.setItem('passwordSession',searchUser.motdepasse)

        searchAcces=acces.find(a=>a.idutilisateur == sessionStorage.iduserSession);
        for(var i=0; i<boutiques.length;i++){                       
            sessionStorage.setItem('idBoutiqueSession',boutiques[i].idBoutique)
            localStorage.setItem('idBoutique',boutiques[i].idBoutique)
        }
        sessionStorage.setItem('proprietaireSession',searchAcces.proprietaire)
        sessionStorage.setItem('gerantSession',searchAcces.gerant)
        sessionStorage.setItem('caissierSession',searchAcces.caissier)
        sessionStorage.setItem('vendeurSession',searchAcces.vendeur)
        sessionStorage.setItem('activerSession',searchAcces.activer)

        searchBoutique=boutiques.find(b=>b.idBoutique == sessionStorage.idBoutiqueSession);
        sessionStorage.setItem('nomBSession',searchBoutique.nomBoutique)
        sessionStorage.setItem('labelBSession',searchBoutique.labelB)
        sessionStorage.setItem('adresseBSession',searchBoutique.adresseBoutique)
        sessionStorage.setItem('typeSession',searchBoutique.type)
        sessionStorage.setItem('categorieSession',searchBoutique.categorie)
        sessionStorage.setItem('dateCBSession',searchBoutique.datecreation)
        sessionStorage.setItem('activerBSession',searchBoutique.activer)
        sessionStorage.setItem('caisseSession',searchBoutique.caisse)
        sessionStorage.setItem('telBoutiqueSession',searchBoutique.telephone)
        localStorage.setItem('telephoneBoutique',searchBoutique.telephone)
        sessionStorage.setItem('RegistreComSession',searchBoutique.RegistreCom)
        sessionStorage.setItem('NineaSession',searchBoutique.Ninea)
        sessionStorage.setItem('enConfigurationSession',searchBoutique.enConfiguration)
        sessionStorage.setItem('vitrineSession',searchBoutique.vitrine)
        sessionStorage.setItem('importExpSession',searchBoutique.importExp);

                                       
    }
    
    
    
    
</script>
<?php 
    if($_SESSION['offline']==1){

        //require('JCaisse/loadData.php');

        echo'<!DOCTYPE html>';

        echo'<html>';

        echo'<head>';

        echo'<script language="JavaScript">document.location="offline/insertionLigneLight.html"</script>';

        echo'</head>';

        echo'</html>';


    }
    
?>
<!-- <script type="text/javascript">
    lespaniers=JSON.parse(localStorage.getItem("pagnet")) || [];
    leslignes= JSON.parse(localStorage.getItem("ligne")) || [];
    var paniers=<?php echo json_encode($tabP); ?>;
    
    if(!localStorage.pagnet){
        //lespaniers.push(paniers[i]);
        localStorage.setItem('pagnet', JSON.stringify(paniers));
    }else{
        localStorage.removeItem("pagnet");
        localStorage.setItem('pagnet', JSON.stringify(paniers));
    }
    //else{
    //     for(var i=0; i<paniers.length;i++){
    //         //alert(paniers[i])
    //         for(var j=0; j<lespaniers.length;j++){
    //             alert(JSON.stringify(paniers[i]))
    //             //alert(JSON.stringify(lespaniers[j]))

    //             if(JSON.stringify(paniers[i])==JSON.stringify(lespaniers[j])){
                    
    //             }else{
    //                 lespaniers.push(paniers[i]);
    //                 localStorage.setItem('pagnet', JSON.stringify(lespaniers));
    //             }
    //         }
    //     }
    // }
        
    //}
    lignes=<?php echo json_encode($lignes); ?>;
    //for(var i=0; i<paniers.length;i++){
        if(!localStorage.ligne){
            //leslignes.push(lignes[i]);
            localStorage.setItem('ligne', JSON.stringify(lignes));
        }else{
            localStorage.removeItem("ligne");
            localStorage.setItem('ligne', JSON.stringify(lignes));
        }
    //}
    // else{
    //     for(var i=0; i<lignes.length;i++){
    //         for(var j=0; j<leslignes.length;j++){

    //             if(JSON.stringify(lignes[i])==JSON.stringify(leslignes[j])){
                    
    //             }else{
    //                 leslignes.push(lignes[i]);
    //                 localStorage.setItem('ligne', JSON.stringify(leslignes));
    //             }
    //         }
    //     }
    // }
</script> -->

