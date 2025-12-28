<?php 
    session_start();

    if(!$_SESSION['iduser']){ 

    header('Location:../index.php');

    }

    require('../connexion.php');

    require('../declarationVariables.php');

    $idBoutique= $_POST['idBoutique'];
	$tabSearchElement=array();

	$reqB="SELECT * from `aaa-boutique` where idBoutique='".$idBoutique."' ";
	$resB=mysql_query($reqB) or die ("insertion impossible");

	while($boutik=mysql_fetch_assoc($resB)){
		if (in_array($boutik['idBoutique'], $tabSearchElement)){

		}else{
			$tabB[]=$boutik;
			
		}
	}
    exit($idBoutique);
?>   
<script type="text/javascript">
	const tabB=<?php echo json_encode($tabB); ?>;
	localStorage.setItem('Boutique', JSON.stringify(tabB));

	
</script>

