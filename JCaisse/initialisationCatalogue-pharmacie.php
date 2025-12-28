<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP
Date de création : 20/03/2016
Date dernière modification : 20/04/2016
*/

if(isset($_POST["btnInitiliserAutoCataloguePh"])) {
	$sql3='SELECT * from  `'.$catalogueTypeCateg.'` ';
	if($res3=mysql_query($sql3)){
		while($tab3=mysql_fetch_array($res3)){
			$sql1="insert into `".$nomtableDesignation."`
			(`designation`  ,`categorie`,`forme` ,`tableau`,`prixSession` ,`prixPublic` ,`codeBarreDesignation` ,`codeBarreuniteStock` ,classe,idFusion) values
			('".$tab3['designation']."','".$tab3['categorie']."','".$tab3['forme']."','".$tab3['tableau']."',
				'".$tab3['prixSession']."','".$tab3['prixPublic']."','"
			.$tab3['codeBarreDesignation']."','".$tab3['codeBarreuniteStock']."','".$tab3['classe']."','".$tab3['idFusion']."')";
			// $res1=@mysql_query($sql1) ;
			$res1=@mysql_query($sql1);
		}
	}

	$sql15="SELECT * FROM `".$categorieTypeCateg."`";
	if ($res15 = mysql_query($sql15)) {
		while($tab4=mysql_fetch_array($res15)){
			$sql16="insert into `".$nomtableCategorie."`
			(nomCategorie,categorieParent) values
			('".$tab4['nomCategorie']."','".$tab4['categorieParent']."')";
			$res16=@mysql_query($sql16);
		}
	}

}

require('entetehtml.php');
echo'
   <body >';
   require('header.php');


?>


    <!--    <div class="row" align="center">

			<form name="formulairePersonnel"   method="post" >
        <input type="hidden" name="ff" value="5">
				<button type="submit" class="btn btn-success" name="btnInitiliserAutoCataloguePh">
														<i class="glyphicon glyphicon-plus"></i>Initialisation automatique Ph
				</button>
			</form>
        </div> 
    -->


<?php
// var_dump($_SESSION['catalogue']);

echo'
    <div class="container">
        <ul class="nav nav-tabs">
          <li class="active">
            <a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE POUR INITIALISATION DES REFERENCES DE PRODUITS</a>
          </li>
          <button type="button" class="btn btn-success pull-right noImpr" id="btnTableInitiale" >
                    <i class="glyphicon glyphicon-plus"></i>
            </button>
            <div class="form-group row pull-right">
                <div class="col-sm-12">
                    <select id="cataloguePH" class="form-control" onchange="this.form.submit()">';
                        // if(!isset($_SESSION['catalogue'])){
                            echo ' <option value="'.$_SESSION['type'].'-'.$_SESSION['categorie'].'">'.$_SESSION['type'].'-'.$_SESSION['categorie'].'</option> ';
                        // }else {
                        //     echo ' <option value="'.$_SESSION['catalogue'].'">'.$_SESSION['catalogue'].'</option> ';
                        // }
                        $sql3='SELECT * from  `aaa-catalogueTotal`  order by id asc';
                        if($res3=mysql_query($sql3)){
                            while($catalogue=mysql_fetch_array($res3)){
                                echo '  <option value="'.$catalogue["nom"].'">'.$catalogue["nom"].'</option>';
                            }
                        }
                    echo '
                    </select>
                </div>
            </div>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">
            <div class="table-responsive" id="divTableInitiale" style="display:none;">
            <table id="tableInitiale" class="table table-bordered table-responsive display" align="left" border="1">
                <thead>
                    <tr>
                        <th>CATEGORIE</th>
                        <th>REFERENCE</th>
                        <th>CODEBARRE </th>
                        <th>FORME</th>
                        <th>TABLEAU</th>
                        <th>PRIX SESSION</th>
                        <th>PRIX PUBLIC</th>
                        <th>OPERATION</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input type="text" class="form-control" id="categoriePh" autocomplete="off"/></td>
                    <td><input type="text" class="form-control" id="designation"  /></td>
                    <td><input type="text" class="form-control" id="codeBarre"  /></td>
                    <td><input type="text" class="form-control" id="formePh" autocomplete="off" /></td>
                    <td>
                        <select  id="tableau" class="form-control">
                            <option selected >Sans</option>
                            <option value= "Hors">Hors</option>
                            <option value= "A">A</option>
                            <option value= "C">C</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" id="prixSession" value="0" /></td>
                    <td><input type="text" class="form-control" id="prixPublic"  /></td>
                    <td>
                        <button type="button" id="btn_init_ProduitPH"  class="btn btn-success">
                            <i class="glyphicon glyphicon-plus"></i>AJOUTER
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <script type="text/javascript">
                $(document).ready(function() {
                    $("#tableInitiale").dataTable({
                        bFilter: false, 
                        bInfo: false,
                        paging : false,
                        
                    });  
                });
            </script>
        </div>
                <div class="table-responsive">
                    <label class="pull-left" for="nbEntreePhInit">Nombre entrées </label>
                    <select class="pull-left" id="nbEntreePhInit">
                    <optgroup>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option> 
                    </optgroup>       
                    </select>
                    <input class="pull-right" type="text" name="" id="searchInputPhInit" autocomplete="off" placeholder="Rechercher...">
                    <div id="resultsProductsInit"><!-- content will be loaded here --></div> ';

            echo '
                </div>
            </div>
        </div>
    </div>';



    /* Debut PopUp d'Alerte sur l'ensemble de la Page **/
    echo'<div id="ajt_Stock" class="modal fade " role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header panel-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ajouter Stock</h4>
                </div>
                <div class="modal-body">
                    <form role="form" class="" >
                        <div class="form-group">
                            <label for="reference">REFERENCE <font color="red">*</font></label>
                            <input type="text" class="form-control" name="designation" id="designation_Stock"  disabled="true" />
                        </div>
                        <div class="form-group">
                            <label for="reference">Categorie <font color="red">*</font></label>
                            <input type="text" class="form-control" name="categorie" id="categorie_Stock"  disabled="true" />
                        </div>
                        <div class="form-group" >
                        <label for="forme"> Quantite <font color="red">*</font></label>
                        <input type="text" class="form-control" name="qteInitial" id="qteInitial_Stock" required />
                        </div>';
                        echo'
                        <div class="form-group" >
                        <label for="tableau"> Date Expiration <font color="red">*</font></label>
                        <input type="date" class="form-control" name="dateExpiration" id="dateExpiration_Stock"  required  />
                        </div>';
                        echo '
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success pull-left" style="margin-right:20px;" id="btn_trm_StockCatalogue_Ph" >
                        Terminer
                    </button>
                    <button type="button" class="btn btn-primary pull-left" style="margin-right:20px;" id="btn_ajt_StockCatalogue_Ph" >
                        Ajouter >> 
                    </button>
                    <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" >
                        Fermer
                    </button>
                </div>
                </div>
        </div>
    </div>';
    /** Fin PopUp d'Alerte sur l'ensemble de la Page **/

echo'</body></html>';


?>

