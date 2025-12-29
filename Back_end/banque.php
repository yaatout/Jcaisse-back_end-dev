<?php
session_start();

require('connection.php');

/**********************/
//$date = new DateTime('25-02-2011');
$date = new DateTime();
//R�cup�ration de l'ann�e
$annee =$date->format('Y');
//R�cup�ration du mois
$mois =$date->format('m');
//R�cup�ration du jours
$jour =$date->format('d');
$messageDel='';

if(!$_SESSION['iduserBack']){
	header('Location:index.php');
}

if($_SESSION['profil']!="SuperAdmin")
    header('Location:accueil.php');

if (isset($_POST['btnEnregistrerCompte'])) {

		$nom=htmlspecialchars(trim($_POST['nom']));

		$sql1="insert into `aaa-banque` (nom) values('".$nom."')";
		//var_dump($sql1);
  		$res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
}
if (isset($_POST['btnModifierCompte'])) {
        $idBanque=htmlspecialchars(trim($_POST['idBanque']));
        $nom=htmlspecialchars(trim($_POST['nom']));
        
        $sql2="UPDATE `aaa-banque` set  nom='".$nom."' where  idBanque=".$idBanque."";
        var_dump($sql2);
	    $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
}
if (isset($_POST['btnSuprimerCompte'])) {
        $idBanque=htmlspecialchars(trim($_POST['idBanque']));
   
        $sql1="DELETE FROM `aaa-banque` WHERE idBanque=".$idBanque;
        $res1=@mysql_query($sql1) or die ("suppression impossible personnel".mysql_error()); 
        $messageDel='Compte supprimer avec succé';
   
		
}
require('entetehtml.php');
?>

<body>

		<?php
		  require('header.php');
		?>
		<center>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addClient">
                <i class="glyphicon glyphicon-plus"></i>Ajouter une banque
            </button>
        </center>


            <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Ajouter une banque</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post" >
                              <div class="form-group">
                                  <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                  <input type="text" class="form-control" id="inputprenom" required="" name="nom" placeholder="Le nom de la banque">
                                  <span class="text-danger" ></span>
                              </div>
                              <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        <button type="submit" name="btnEnregistrerCompte" class="btn btn-primary">Enregistrer</button>
                               </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
                    
    <div class="row">
           
            <div class="container" align="center">
                
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="LISTECLIENTS">
                    <table id="exemple" class="display" border="1" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Nom Banque</th>
                                                <th>Operation</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Nom Banque</th>
                                                <th>Operation</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php  $sql1="SELECT * FROM `aaa-banque`  ";
                                                    $res1 = mysql_query($sql1) or die ("utilisateur requête 4".mysql_error());
                                                    while ($compte = mysql_fetch_array($res1)){ ?>
                                                        <tr>
                                                            <td><?php echo  $compte["nom"]; ?> </td>
                                                            
                                                            <td> <a href="#" >
                                                                <img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierCompte".$compte['idBanque']; ?> /></a>&nbsp;&nbsp;
                                                                <a href="#">
                                                                    <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerCompte".$compte['idBanque'] ; ?> /></a> 
                                                               
                                                                <div class="modal fade" <?php echo  "id=imgmodifierCompte".$compte['idBanque']; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Modifier Banque</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form name="formulairePersonnel" method="post" >


                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                                                                      <?php echo '<input type="hidden" class="form-control" id="inputprenom" name="idBanque" value="'.$compte['idBanque'].'"> '; ?>
                                                                                      <?php echo '<input type="text" class="form-control" id="inputprenom" name="nom" value="'.$compte['nom'].'"> '; ?>
                                                                                  </div>
                                                                                  <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                            <button type="submit" name="btnModifierCompte" class="btn btn-primary">Modifier</button>
                                                                                   </div>
                                                                                </form>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="modal fade" <?php echo  "id=imgsuprimerCompte".$compte['idBanque']; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Suprimer compte</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form name="formulairePersonnel" method="post" >


                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                                                                      <?php echo '<input type="hidden" class="form-control" id="inputprenom" name="idBanque" value="'.$compte['idBanque'].'"> '; ?>
                                                                                      <?php echo '<input type="text" class="form-control" id="inputprenom" name="nom" value="'.$compte['nom'].'" disabled> '; ?>
                                                                                      
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                  <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                            <button type="submit" name="btnSuprimerCompte" class="btn btn-primary">Suprimer</button>
                                                                                   </div>
                                                                                </form>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                        </tbody>
                                    </table>
                    </div>
                </div>
           </div>
	</div>

</body>
</html>
