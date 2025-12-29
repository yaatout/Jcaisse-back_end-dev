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
 
require('entetehtml.php');
?>

<body>

	<?php
	  require('header.php');
	?>		
    <div class="container" >
<div class="row" align="center"><h2><span id="" style="margin-top: -20px;margin-right: -15px;border-radius: 50%;background: red;color: white;"
                          class="badge bg-warning">555 materiels attribué sur 55555</span></div></h2>
            <button type="button" name="referenceTransfert" class="btn btn-warning" data-toggle="modal" data-target="#addMat"> Ajout un materiel </button> 
            <button type="button" name="referenceTransfert" class="btn btn-success" data-toggle="modal" data-target="#addCat"> Ajout un materiel </button>
            
            <!-- <button type="button" name="referenceTransfert" class="btn btn-warning" data-toggle="modal" data-target="#addSalle"> Ajout de Salle </button> -->
            <br><br><br>  
                <div class="modal fade" id="addMat" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Ajouter Categorie</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formulaireVersement" method="post" >
                                                <div class="form-group">
                                                    <label for="dateTransfert"> Nom Categorie</label>
                                                    <input type="text" class="form-control" id="nomCategorie" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="montantTransfert"> Montant</label>
                                                    <input type="text" class="form-control" id="codeCategorie" required/>
                                                </div>                      
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="button" class="btn btn-primary" name="addMatiere" id="addMateriel"> Ajouter </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                </div>                  
                <div class="modal fade" id="addMat" role="dialog" aria-labelledby="myModalLabel">
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
                                                    <label for="montantTransfert"> Quantité</label>
                                                    <input type="number" class="form-control" id="quantite"  name="quantite" required/>
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
                </div>
                <div id="modifierCodeBModal" class="modal fade" role="dialog"> 
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
                
            <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#MATERILES">MATERILES</a></li>
                  <li class=""><a data-toggle="tab" href="#PERSONNELS" onClick="personnelOnglet()">PERSONNELS</a></li>
                  <li class=""><a data-toggle="tab" href="#SALLES" onClick="salleOnglet()">SALLES</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="MATERILES" > 
                        <div class="table-responsive"> 
                            <label class="pull-left" for="nbEntreeMat">Nombre entrées </label>

                            <select class="pull-left" id="nbEntreeMat">
                                <optgroup>

                                    <option value="10">10</option>

                                    <option value="20">20</option>

                                    <option value="50">50</option> 

                                </optgroup>       
                            </select>

                            <input class="pull-right" type="text"  id="searchInputMat" placeholder="Rechercher...">

                            <div id="resultsProductsMat"> </div> 
                        </div> 
					</div>
                    <div class="tab-pane fade" id="PERSONNELS" > 
                        <div class="table-responsive"> 
                            <label class="pull-left" for="nbEntreePer">Nombre entrées </label> 
                            <select class="pull-left" id="nbEntreePer">
                                <optgroup>

                                    <option value="10">10</option>

                                    <option value="20">20</option>

                                    <option value="50">50</option> 

                                </optgroup>       
                            </select> 
                            <input class="pull-right" type="text" name="" id="searchInputPer" placeholder="Rechercher..."> 
                            <div id="resultsProductsPer"></div> 
                            
                        </div> 
                    </div>                    
                    <div class="tab-pane fade " id="SALLES">
                    <br>
                        <div class="row">
                            <button type="button" class="btn btn-success pull-left" data-toggle="modal" data-target="#addSalle" data-dismiss="modal" id="AjoutStock">
                            <i class="glyphicon glyphicon-plus"></i>Ajout de Salle</button>
                        </div> 
                        <div class="table-responsive"> <br>
                            <label class="pull-left" for="nbEntreeSal">Nombre entrées </label> 
                            <select class="pull-left" id="nbEntreeSal">
                                <optgroup>

                                    <option value="10">10</option>

                                    <option value="20">20</option>

                                    <option value="50">50</option> 

                                </optgroup>       
                            </select> 
                            <input class="pull-right" type="text" name="" id="searchInputSal" placeholder="Rechercher..."> 
                            <div id="resultsProductsSal"></div>                             
                        </div> 
                    </div>     
                  </div>

        <!-- <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#PRODUIT">MATERIELS</a></li>
            <li class=" "><a data-toggle="tab" href="#SERVICE">PERSONELS</a></li>
            <li class=" "><a data-toggle="tab" href="#FRAIS">SALLES</a></li>
        </ul>
        <div class="tab-content ">
            <div id="PRODUIT" class="tab-pane fade in active"> 
                <div class="table-responsive">

                    <label class="pull-left" for="nbEntreeMat">Nombre entrées </label>

                    <select class="pull-left" id="nbEntreeMat">
                        <optgroup>

                            <option value="10">10</option>

                            <option value="20">20</option>

                            <option value="50">50</option> 

                        </optgroup>       
                    </select>

                    <input class="pull-right" type="text" name="" id="searchInputMat" placeholder="Rechercher...">

                    <div id="resultsProductsMat"> </div>

                    </div>
                </div>
            </div>
            <div id="SERVICE" class="tab-pane fade">
 
                        <button type="button" class="btn btn-success pull-left" data-toggle="modal" data-target="#AjoutStockModal1" data-dismiss="modal" id="AjoutStock">

                        <i class="glyphicon glyphicon-plus"></i>Ajout de Service </button> 
                        
                        <div class="modal fade" id="AjoutStockModal1" role="dialog">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                <div class="modal-header" >';

                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                    <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Service </h4>

                                    <br />

                                </div>

                                <div class="modal-body">

                                <br />

                                    <form name="formulaire2" id="ajouterServiceForm" method="post" action="insertionProduit.php">

                                    <div class="form-group">

                                        <label for="reference">REFERENCE <font color="red">*</font></label>

                                        <input type="text" class="form-control" placeholder="Nom de la reference du Service ici..."  name="designation" id="designationSD" value="" required />

                                    <input type="hidden" name="idDesignation" value="" id="idD" >

                                        <div id="reponseSD"></div>

                                    </div>



                                    <div class="form-group" id="div-uniteService">

                                    <label for="uniteService">UNITE SERVICE<font color="red">*</font></label>

                                    <input type="text" class="form-control" placeholder="Unité du Service ici ..." id="uniteService" name="uniteService" value="" required />

                                    </div>



                                    <div class="form-group" id="div-prixSF">

                                    <label for="prix">PRIX UNITE SERVICE<font color="red">*</font></label>

                                    <input type="number" class="form-control" placeholder="Prix du Service ici ..." id="prixSF" name="prixSF" value=""  required/>

                                    </div>



                                    <div class="modal-footer" align="right">

                                        <font color="red">Les champs qui ont (*) sont obligatoires</font><br />

                                        <input type="hidden" name="classe" value="1" />

                                        <input type="submit" class="boutonbasic" name="inserer" value="AJOUT SERVICE >>" />

                                    </div>

                                    </form><br />

                                </div>

                                </div>

                            </div>

                        </div> 
                        <div class="table-responsive"> 
                            <label class="pull-left" for="nbEntreePer">Nombre entrées </label> 
                            <select class="pull-left" id="nbEntreePer">
                                <optgroup>

                                    <option value="10">10</option>

                                    <option value="20">20</option>

                                    <option value="50">50</option> 

                                </optgroup>       
                            </select> 
                            <input class="pull-right" type="text" name="" id="searchInputPer" placeholder="Rechercher..."> 
                            <div id="resultsProductsPer"></div> 
                            
                        </div> 
            </div> 
            
        </div> -->
    </div>
    <script  src="js/bienMateriel.js"></script>
</body>
</html>
