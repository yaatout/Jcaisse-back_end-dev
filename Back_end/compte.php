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


$heureString=$date->format('H:i:s');
$dateString=$annee.'-'.$mois.'-'.$jour;
$dateString2=$jour.'-'.$mois.'-'.$annee;
$dateHeures=$dateString.' '.$heureString;

$messageDel='';

if(!$_SESSION['iduserBack']){
	header('Location:index.php');
}

if($_SESSION['profil']!="SuperAdmin" AND $_SESSION['profil']!="Assistant")
    header('Location:admin-map.php');

if (isset($_POST['btnEnregistrerCompte'])) {

		$nomCompte=htmlspecialchars(trim($_POST['nomCompte']));
		$typeCompte=htmlspecialchars(trim($_POST['typeCompte']));
		$numeroCompte=htmlspecialchars(trim($_POST['numeroCompte']));
		$montantCompte=htmlspecialchars(trim($_POST['montantCompte']));

		$sql1="insert into `aaa-compte` (nomCompte,typeCompte,numeroCompte,montantCompte) values('".$nomCompte."','".$typeCompte."','".$numeroCompte."','".$montantCompte."')";
		//var_dump($sql1);
  		$res1=mysql_query($sql1) or die ("insertion Cmpte impossible =>".mysql_error() );
    
    if($montantCompte>0){
        $sqlv="select * from `aaa-compte` where nomCompte='".$nomCompte."'";
        $resv=mysql_query($sqlv);
        $compte =mysql_fetch_assoc($resv);
        
        $numeroDestinataire='';
         $compteDonateur='';
        $nomClient='';
        $dateEcheance='2021-01-01';
        $operation='depot';
        $idCompte=$compte['idCompte'];
        $dateSaisie=$dateHeures;
        $description='INITIALISATION';
        if($typeCompte=='pret'){
            $operation='versement';
        }
        
        $sql2="insert into `aaa-comptemouvement` (montant,operation,idCompte,numeroDestinataire,compteDonateur,nomClient,dateEcheance,dateSaisie,dateOperation,description,idUser) values('".$montantCompte."','".$operation."','".$idCompte."','".$numeroDestinataire."','".$compteDonateur."','".$nomClient."','".$dateEcheance."','".$dateHeures."','".$dateSaisie."','".$description."','".$_SESSION['iduserBack']."')";
          $res2=mysql_query($sql2) or die ("insertion Cmpte impossible =>".mysql_error() );
    }
}
if (isset($_POST['btnModifierCompte'])) {
        $idCompte=htmlspecialchars(trim($_POST['idCompte']));
        $nomCompte=htmlspecialchars(trim($_POST['nomCompte']));
    if($nomCompte!=''){
		$typeCompte=htmlspecialchars(trim($_POST['typeCompte']));
		$numeroCompte=htmlspecialchars(trim($_POST['numeroCompte']));

		 $sql2="UPDATE `aaa-compte` set  nomCompte='".$nomCompte."',typeCompte='".$typeCompte."',numeroCompte='".$numeroCompte."' where  idCompte=".$idCompte."";
	      $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
    }
		
}
if (isset($_POST['btnSuprimerCompte'])) {
        $idCompte=htmlspecialchars(trim($_POST['idCompte']));
        $sqlDV="SELECT * FROM `aaa-comptemouvement`	where idCompte=".$idCompte;
        $resDV=mysql_query($sqlDV) or die ("select stock impossible =>".mysql_error());
        $S_facture = mysql_fetch_array($resDV);
        
    if( mysql_num_rows($resDV)){
        
		 $sql2="UPDATE `aaa-compte` set  activer='0' where  idCompte=".$idCompte."";
         $res2=@mysql_query($sql2) or die ("mise à jour idClient pour activer ou pas ".mysql_error());
   
		//$messageDel='Ce compte ne paut etre suprimer car il contient des valeurs';
		$messageDel='Compte desactiver avec succé';
    }else{
        $sql1="DELETE FROM `aaa-compte` WHERE idCompte=".$idCompte;
        $res1=@mysql_query($sql1) or die ("suppression impossible personnel".mysql_error()); 
        $messageDel='Compte supprimer avec succé';
    }
		
}




require('entetehtml.php');
?>

<body>

		<?php
		  require('header.php');
		?>
		<center>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addClient">
                                    <i class="glyphicon glyphicon-plus"></i>Ajouter compte
                    </button>
        </center>


            <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Ajout un nouveau compte</h4>
                        </div>
                        <div class="modal-body">
                            <form name="formulairePersonnel" method="post" >

                              
                              <div class="form-group">
                                  <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                  <input type="text" class="form-control" id="inputprenom" required="" name="nomCompte" placeholder="Le nom du compte ici...">
                                  <span class="text-danger" ></span>
                              </div>
                              <div class="form-group">
                                  <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                 <select name="typeCompte" id="typeCompteS" >
                                                        <?php
                                                            $sqlv="select * from `aaa-comptetype` ";
                                                            $resv=mysql_query($sqlv);
                                                            while($operation =mysql_fetch_assoc($resv)){
                                                               echo '<option value="'.$operation["idType"].'">'.$operation["nomType"].'</option>';
                                                            }
                                                        ?>
                                     
                                                    </select>
                                  <span class="text-danger" ></span>
                              </div>
                                <div class="form-group">
                                  <label for="inputEmail3" class="control-label" id="nclB">NUMERO COMPTE  <font color="red">*</font></label>
                                  <input type="text" class="form-control" id="numeroCompte"  name="numeroCompte" placeholder="Numero du compte" value=""> 
                                  <span class="text-danger" ></span>
                              </div> 
                                <div class="form-group">
                                  <label for="inputEmail3" class="control-label" id="icLB">INITIALISATION COMPTE  </label>
                                  <input type="number" min="0" class="form-control" id="montantCompte" value="0" name="montantCompte" placeholder="Le montant initial du compte">
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
                 <?php if($messageDel!=''){
                      echo '<center>
                                <div class="row">
                                    <div class="alert alert-danger">
                                        <strong>'.$messageDel.' </strong>
                                    </div>
                                </div>
                            </center>
                        ';
                }?>    
    <div class="row">
           
            <div class="container" align="center">
                
               
                <ul class="nav nav-tabs">
                  <li class="active">
                      <a data-toggle="tab" href="#LISTECLIENTS">LISTE DES COMPTES
                            <?php
							     if ($_SESSION['profil']=="SuperAdmin") {
                                        $sqlC="SELECT SUM(montantCompte)    FROM `aaa-compte`  where activer='1' and typeCompte !='compte pret' ";
                                        $resC=mysql_query($sqlC) or die ("select stock impossible =>".mysql_error());
                                        $S_f = mysql_fetch_array($resC);
                                        $montantT = $S_f[0];
						 		        echo  ' => Montant Total =  '.number_format($montantT,0, ',', ' ').' FCFA ';
                                 } elseif ($_SESSION['profil']=="Assistant") {
                                    $sqlC="SELECT SUM(montantCompte)    FROM `aaa-compte`  where activer='1' and typeCompte ='compte mobile' ";
                                    $resC=mysql_query($sqlC) or die ("select stock impossible =>".mysql_error());
                                    $S_f = mysql_fetch_array($resC);
                                    $montantT = $S_f[0];
                                    echo  ' => Montant Total =  '.number_format($montantT,0, ',', ' ').' FCFA ';
                                 
                                 } 
                                 
							
						  ?>                                      
                      </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="LISTECLIENTS">
                    <table id="exemple" class="display" border="1" class="table table-bordered   table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Nom Compte</th>
                                                <th>Type Compte</th>
                                                <th>Numero Compte</th>
                                                <th>Etat</th>
                                                <th>Operation</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                 <th>Nom Compte</th>
                                                <th>Type Compte</th>
                                                <th>Numero Compte</th>
                                                <th>Etat</th>
                                                <th>Operation</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php  
                                                if ($_SESSION['profil']=="SuperAdmin" ) {
                                                    $sql1="SELECT * FROM `aaa-compte`  where activer='1'" ;
                                                } elseif($_SESSION['profil']=="Assistant") {
                                                    $sql1="SELECT * FROM `aaa-compte`  where activer='1' and typeCompte='compte mobile'" ;
                                                }
                                                
                                                
                                                    $res1 = mysql_query($sql1) or die ("utilisateur requête 4".mysql_error());
                                                    while ($compte = mysql_fetch_array($res1)){ ?>
                                                        <tr>
                                                            <td><?php echo  $compte["nomCompte"]; ?> </td>
                                                            <td><?php 
                                                                $sql0="SELECT * FROM `aaa-comptetype` WHERE idType ='".$compte["typeCompte"]."' ";
                                                                $res0=mysql_query($sql0);
                                                                $type=mysql_fetch_array($res0);
                                                                 
                                                                echo  $type["nomType"]; 
                                                            
                                                                ?> 
                                                            </td>
                                                            <td><?php echo  $compte["numeroCompte"]; ?> </td>
                                                            <td><?php echo  number_format($compte['montantCompte'], 0, ',', ' ')." FCFA"; ?> <a   <?php echo  "href=compteDe.php?c=".$compte['idCompte'] ; ?> > Details</a></td>
                                                            <td> <a href="#" >
                                                                <img src="images/edit.png"  align="middle" alt="modifier"  data-toggle="modal" <?php echo  "data-target=#imgmodifierCompte".$compte['idCompte']; ?> /></a>&nbsp;&nbsp;
                                                                <a href="#">
                                                                    <img src="images/drop.png" align="middle" alt="supprimer" id="" data-toggle="modal" <?php echo  "data-target=#imgsuprimerCompte".$compte['idCompte'] ; ?> /></a> 
                                                               
                                                                <div class="modal fade" <?php echo  "id=imgmodifierCompte".$compte['idCompte']; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Modifier compte</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form name="formulairePersonnel" method="post" >


                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NOM <font color="red">*</font></label>
                                                                                      <?php echo '<input type="hidden" class="form-control" id="inputprenom" name="idCompte" value="'.$compte['idCompte'].'"> '; ?>
                                                                                      <?php echo '<input type="text" class="form-control" id="inputprenom" name="nomCompte" value="'.$compte['nomCompte'].'"> '; ?>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                                                                     <select name="typeCompte" >
                                                                                                            <?php
                                                                                                                echo '<option selected value="'.$compte["typeCompte"].'">'.$compte["typeCompte"].'</option>';
                                                                                                                $sqlv="select * from `aaa-comptetype` ";
                                                                                                                $resv=mysql_query($sqlv);
                                                                                                                while($operation =mysql_fetch_assoc($resv)){
                                                                                                                   echo '<option value="'.$operation["nomType"].'">'.$operation["nomType"].'</option>';
                                                                                                                }
                                                                                                            ?>
                                                                                                        </select>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                    <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NUMERO COMPTE  <font color="red">*</font></label>
                                                                                         <?php echo '<input type="text" class="form-control" id="inputprenom" name="numeroCompte" value="'.$compte['numeroCompte'].'"> '; ?>
                                                                                      <span class="text-danger" ></span>
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
                                                                
                                                                <div class="modal fade" <?php echo  "id=imgsuprimerCompte".$compte['idCompte']; ?> tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                                                                      <?php echo '<input type="hidden" class="form-control" id="inputprenom" name="idCompte" value="'.$compte['idCompte'].'"> '; ?>
                                                                                      <?php echo '<input type="text" class="form-control" id="inputprenom" name="nomCompte" value="'.$compte['nomCompte'].'" disabled> '; ?>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">Type<font color="red">*</font></label>
                                                                                      <?php echo '<input type="hidden" class="form-control" id="inputprenom" name="idCompte" value="'.$compte['idCompte'].'"> '; ?>
                                                                                      <?php echo '<input type="text" class="form-control" id="inputprenom" name="typeCompte" value="'.$compte['typeCompte'].'" disabled> '; ?>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                    <div class="form-group">
                                                                                      <label for="inputEmail3" class="control-label">NUMERO COMPTE  <font color="red">*</font></label>
                                                                                         <?php echo '<input type="text" class="form-control" id="inputprenom" name="numeroCompte" value="'.$compte['numeroCompte'].'" disabled> '; ?>
                                                                                      <span class="text-danger" ></span>
                                                                                  </div>
                                                                                  <div class="modal-footer"><font color="red">Les champs qui ont (*) sont obligatoires</font>
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                                                            <button type="submit" name="btnSuprimerCompteXXX" class="btn btn-primary">Suprimer</button>
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
