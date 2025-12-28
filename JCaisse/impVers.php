<?php
session_start();

require('connection.php');

/**********************/
require('declarationVariables.php');

$idClient=htmlspecialchars(trim($_GET['c']));

if(!$_SESSION['iduser']){
	header('Location:../index.php');
} ?>

<?php require('entetehtml.php'); ?>
<body>

		<?php
		  require('header.php');
		?>
    <div class="container-fluid" >
     
    
<?php
								$sql4="SELECT * FROM `".$nomtableVersement."` where idClient=".$idClient." ORDER BY idVersement DESC";
								$res4 = mysql_query($sql4) or die ("persoonel requête 2".mysql_error());
									while ($versement = mysql_fetch_array($res4)) { ?>
                                        <div class="row">
                                              <div class="">
                                                <a href="#" class="thumbnail">
                                                  <dl class="dl-horizontal">
														<div><b>BOUTIQUE :   <?php echo  strtoupper($_SESSION['labelB']) ;?> </b>
														</div>
														<div><b>ADDRESSE :   <?php echo  strtoupper($_SESSION['adresseB']) ;?> </b>
														</div>

														<dt>Montant</dt>
														<dd><?php echo  $versement['montant']; ?></dd>
														<dt>Date versement</dt>
														<dd><?php echo  $versement['dateVersement'];  ?></dd>
													</dl>
													 <div align="center">MERCI POUR VOTRE FIDELITE A BIENTOT !</div>
													 <div  align="center"> A BIENTOT !</div>
                                                </a>
                                              </div>
                                              ...
                                            </div>

                                    <?php 	}?>
			
    </div>
</body>
</html>
