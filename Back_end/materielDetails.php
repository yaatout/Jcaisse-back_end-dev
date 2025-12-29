<?php
session_start();


require('connectionPDO.php');

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
if (! isset($_GET['m']) and $_GET['m']<1) {
    header('Location:admin-map.php');
} 
 require('entetehtml.php');
?>

<body>

	<?php
	  require('header.php');
	?>		
    <div class="container-fluid" >
            <div class="row" align="center"><h2><span id="" style="margin-top: -20px;margin-right: -15px;border-radius: 50%;background: red;color: white;"
                          class="badge bg-warning">555 Images sur 55555</span></div></h2>
                          <span align="center" class="label label-default">555 Images sur 55555</span>
            <!-- <button type="button" name="referenceTransfert" class="btn btn-warning" data-toggle="modal" data-target="#addMat"> Ajout un materiel </button>
            <button type="button" name="referenceTransfert" class="btn btn-warning" data-toggle="modal" data-target="#addSalle"> Ajout de Salle </button> -->
            <br><br><br>                    
                <!-- <div class="modal fade" id="addElm" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Ajouter Materiel</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" >
                                                <div class="form-group">
                                                    <label for="dateTransfert"> Nom matériel</label>
                                                    <input type="text" class="form-control" id="nomMateriel" name="nomMateriel" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="montantTransfert"> Montant</label>
                                                    <input type="number" class="form-control" id="prixAchat"  name="prixAchat" required/>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="montantTransfert"> Date</label>
                                                    <input type="date" class="form-control" id="dateM"  name="dateM" />
                                                </div>                      
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="button" class="btn btn-primary" name="addMatiere" id="addMateriel"> Ajouter </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                </div> -->
                <!-- <div id="modifierCodeBModal" class="modal fade" role="dialog"> 
                    <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                        <h4 class="modal-title">Modification code barre</h4>

                        </div>

                        <div class="modal-body" style="padding:40px 50px;">

                        <form class="form" onsubmit="return false" >

                            <input type="hidden"  name="designation" id="idM_CB" />

                            <div class="form-group">

                            <label for="codeBD">CodeBarre Materiel </label>

                            <input type="text" class="inputbasic form-control" id="codeBarreMateriel" autofocus=""   required />

                            </div>

                            <div class="form-group ">



                            </div>

                            <div class="modal-footer row">

                            <div class="col-sm-3 "> <input type="submit" id="btn_mdf_CodeBarreMateriel" class="btn_CodeDesign boutonbasic"  value=" Enregistrer >>" /></div>

                            <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>

                            </div>

                        </form>

                        </div>

                    </div>

                    </div> 
                    </div>

                    <div id="modifierCodeRFIDModal" class="modal fade" role="dialog">

                        <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                            <h4 class="modal-title">Modification code RFID</h4>

                            </div>

                            <div class="modal-body" style="padding:40px 50px;">

                            <form class="form" onsubmit="return false" >

                                <input type="hidden"  name="designation" id="idM_CRFID" />

                                <div class="form-group">

                                <label for="codeBD">CodeBarre RFID </label>

                                <input type="text" class="inputbasic form-control" id="codeRFID" autofocus=""   required />

                                </div>

                                <div class="modal-footer row">

                                <div class="col-sm-3 "> <input type="submit" id="btn_mdf_CodeRFIDMateriel" class="btn_CodeDesign boutonbasic"  value=" Enregistrer >>" /></div>

                                <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>

                                </div>

                            </form>

                            </div>

                        </div>

                        </div>

                    </div>
                    <div id="modifierMatModal" class="modal fade" role="dialog">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                <h4 class="modal-title">Modification Materiel</h4>

                                </div>

                                <div class="modal-body" style="padding:40px 50px;">
                                    <form class="form" onsubmit="return false" >
                                        <input type="hidden"  name="designation" id="idM" />
                                        <div class="form-group">
                                            <label for="dateTransfert"> Nom matériel</label>
                                            <input type="text" class="form-control" name="nomMateriel" id="nomMatMod" />
                                        </div>
                                        <div class="form-group">
                                            <label for="montantTransfert"> Montant</label>
                                            <input type="number" class="form-control"   name="prixAchat" id="prixAchatMatMod" />
                                        </div>
                                        <div class="form-group">
                                            <label for="montantTransfert"> Date</label>
                                            <input type="date" class="form-control"   name="dateM" id="dateMatMod" />
                                        </div>                      
                                        <div class="modal-footer row"> 
                                            <div class="col-sm-3 "> <input type="submit" id="btn_mdf_Materiel" class="btn_CodeDesign boutonbasic"  value=" Modifier >>" /></div>
                                            <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="supprimerMatModal" class="modal fade" role="dialog">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                <h4 class="modal-title">Supression Materiel</h4>

                                </div>

                                <div class="modal-body" style="padding:40px 50px;">
                                    <form class="form" onsubmit="return false" >
                                        <input type="hidden"  name="designation" id="idMSup" />
                                        <div class="form-group">
                                            <label for="dateTransfert"> Nom matériel</label>
                                            <input type="text" class="form-control" name="nomMateriel" id="nomMatSup" disabled />
                                        </div>
                                        <div class="form-group">
                                            <label for="montantTransfert"> Montant</label>
                                            <input type="number" class="form-control"   name="prixAchat" id="prixAchatMatSup" disabled />
                                        </div>
                                        <div class="form-group">
                                            <label for="montantTransfert"> Date</label>
                                            <input type="date" class="form-control"   name="dateM" id="dateMatSup" disabled/>
                                        </div>                      
                                        <div class="modal-footer row"> 
                                            <div class="col-sm-3 "> <input type="submit" id="btn_sup_Materiel" class="btn_CodeDesign boutonbasic"  value=" Supprimer >>" /></div>
                                            <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal fade" id="addSalle" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Ajouter Materiel</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form name="formulaireVersement" method="post" >
                                                        <div class="form-group">
                                                            <label for="dateTransfert"> Nom salle</label>
                                                            <input type="text" class="form-control" name="nomSalle" id="nomSalle" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="montantTransfert"> Code salle</label>
                                                            <input type="text" class="form-control"   name="codeSalle" id="codeSalle" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="montantTransfert"> Description</label>
                                                            <input type="text" class="form-control" id="description"/>
                                                        </div>
                                                                            
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <input type="button" class="btn btn-primary" name="addSalle" id="btnAddSalle" value="Ajouter"/>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                    </div>
                    <div id="modifierSalModal" class="modal fade" role="dialog">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                <h4 class="modal-title">Modification Salle</h4>

                                </div>

                                <div class="modal-body" style="padding:40px 50px;">
                                    <form class="form" onsubmit="return false" >
                                        <input type="hidden"  name="designation" id="idS" />
                                        <div class="form-group">
                                            <label for="dateTransfert"> Nom salle</label>
                                            <input type="text" class="form-control" name="nomSalle" id="nomSalleMod" />
                                        </div>
                                        <div class="form-group">
                                            <label for="montantTransfert"> Code Salle</label>
                                            <input type="text" class="form-control"   name="codeSalle" id="codeSalleMod" />
                                        </div>  
                                        <div class="form-group">
                                            <label for="montantTransfert"> Description</label>
                                            <input type="number" class="form-control"   name="description" id="descriptionSalleMod" />
                                        </div>                    
                                        <div class="modal-footer row"> 
                                            <div class="col-sm-3 "> <input type="submit" id="btn_mdf_Salle" class="btn_CodeDesign boutonbasic"  value=" Modifier >>" /></div>
                                            <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="supprimerSalModal" class="modal fade" role="dialog">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                <h4 class="modal-title">suppression de Salle</h4>

                                </div>

                                <div class="modal-body" style="padding:40px 50px;">
                                    <form class="form" onsubmit="return false" >
                                        <input type="hidden"  name="designation" id="idSSup" />
                                        <div class="form-group">
                                            <label for="dateTransfert"> Nom salle</label>
                                            <input type="text" class="form-control" name="nomSalle" id="nomSalleSup" disabled/>
                                        </div>
                                        <div class="form-group">
                                            <label for="montantTransfert"> Code Salle</label>
                                            <input type="text" class="form-control"   name="codeSalle" id="codeSalleSup" disabled/>
                                        </div>  
                                        <div class="form-group">
                                            <label for="montantTransfert"> Description</label>
                                            <input type="number" class="form-control"   name="description" id="descriptionSalleSup" disabled/>
                                        </div>                    
                                        <div class="modal-footer row"> 
                                            <div class="col-sm-3 "> <input type="submit" id="btn_sup_Salle" class="btn_CodeDesign boutonbasic"  value=" Modifier >>" /></div>
                                            <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> 
                -->
        
                    <div class="row">
                        <div class="col-sm-3"> 
                            <div class="panel  panel-primary">
                                <!-- Default panel contents -->
                                <?php 
                                    $idM=$_GET["m"];
                                    $req = $bdd->prepare("SELECT  * from `aaa-bien-materiel` where idMateriel=:i "); 
                                    $req->execute(array('i'=>$idM))  or die(print_r($req->errorInfo()));  
                                    $mat = $req->fetch();
                                ?>
                                <div class="panel-heading"> <h3>Materiel</h3></div>
                                    <div class="panel-body">
                                        <h5>Nom du materiel : <?php echo $mat['nomMateriel'] ?></h5><hr>
                                            <?php  
                                                $req6 = $bdd->prepare("SELECT * from `aaa-bien-categorie` where idCategorie =:i "); 
                                                $req6->execute(array('i'=>$mat['idCategorie']))  or die(print_r($req6->errorInfo()));
                                                $categ=$req6->fetch();
                                            ?>
                                            
                                        <h5>Categorie : <?php echo $categ['nomCategorie'] ?></h5><hr>
                                        <h5>Quantité : <?php echo $mat['quantite'] ?></h5><hr>
                                        <h5>Date achat : <?php echo $mat['dateMateriel'] ?></h5><hr>
                                    </div> 
                                </div> 
                            </div>
                        <div class="col-sm-9">
                            <div class="panel panel-info">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading"> 
                                        <h3>Détails Matériel</h3>
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class='row'>
                                            <?php  
                                                $req = $bdd->prepare("SELECT  COUNT(idInstanceMat) as total_row  from `aaa-bien-instanceMat` where idMateriel=:i "); 
                                                $req->execute(array('i'=>$idM))  or die(print_r($req->errorInfo()));  
                                                $total_rows = $req->fetch();
                                                $nomb=$total_rows[0];
                                                
                                                if (intval($nomb)< intval($mat['quantite'])) {?>
                                                    <button type="button" name="referenceTransfert" class="btn btn-warning pull-left"
                                                            id="addInstMateriel" > Ajout une instance 
                                                    </button><br><br>
                                                    <input type="hidden" id="idMatToInstance" value="<?php echo $mat['idMateriel'] ?>">
                                                <?php } ?>
                                            
                                            
                                        </div>
                                        <!-- Table -->  
                                        <div class="table-responsive"> 
                                            <label class="pull-left" for="nbEntreeInst">Nombre entrées </label>
                                            <select class="pull-left" id="nbEntreeInst">
                                                <optgroup> 
                                                    <option value="10">10</option> 
                                                    <option value="20">20</option> 
                                                    <option value="50">50</option>  
                                                </optgroup>       
                                            </select>
                                            <input class="pull-right" type="text"  id="searchInputInst" placeholder="Rechercher...">
                                            <div id="resultsProductsInst"> </div> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div>
                        </div>
                    </div>
                
    </div>
    <script  src="js/bienMaterielInstance.js"></script>
</body>
</html>
