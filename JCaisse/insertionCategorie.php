<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka DIALLO
Date de modification:07/04/2016; 04-05-2018
*/
session_start();
if($_SESSION['iduser']){

require('connection.php');

require('connectionVitrine.php');

require('declarationVariables.php');

/**********************/
$designation         =@$_POST["designation"];
//var_dump($designation);
$prix                =@$_POST["prix"];
$prixSF              =@$_POST["prixSF"];
//var_dump($prix);

$idDesignation       =@$_POST["idDesignation"];
$idcategorie         =@$_POST["idcategorie"];
$categorieM          =@$_POST["categorieM"];
$classe              =@$_POST["classe"];
//var_dump($classe);
$desig               =@$_POST["desig"];
$uniteStock          =@$_POST["uniteStock"];
$prixService         =@$_POST["prixService"];
$montantFrais        =@$_POST["montantFrais"];
$nbArticleUniteStock =@$_POST["nbArticleUniteStock"];
$seuil 				 =@$_POST["seuil"];
$prixUnitaire        =@$_POST["prix"];
$prixuniteStock      =@$_POST["prixuniteStock"];
$codeBarreDesignation =@$_POST["codeBarreDesignation"];
$codeBarreuniteStock =@$_POST["codeBarreuniteStock"];

$categorie          =@$_POST["categorie"];
$categorie1         =@$_POST["categorie1"];
$categorieParent    =@$_POST["categorieParent"];
$newCategorie       =@$_POST["newCategorie"];
$categorieSup       =@$_POST["categorieSup"];

$modifier            =@$_POST["modifier"];
$supprimer           =@$_POST["supprimer"];
$annuler             =@$_POST["annuler"];
/***************/
$idDesignation1      =@$_POST["idDesignation"];



/*
echo $nomtableDesignation ;
echo $designation ;
echo $prix;
echo $uniteStock;
echo $prixuniteStock;
echo $nbArticleUniteStock;
*/
/**********************/
if(!$annuler){
if(!$modifier and !$supprimer){
  if($categorie1) {
  		$sql11="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".$categorie1."'";
		$res11=mysql_query($sql11);
		if(!mysql_num_rows($res11)){
				$sql="insert into `".$nomtableCategorie."` (nomcategorie,categorieParent) values ('".mysql_real_escape_string($categorie1)."',".$categorieParent.")";
				//echo $sql;
       			$res=@mysql_query($sql) or die ("insertion categorie 1 impossible".mysql_error());
		  }else{
		echo'<!DOCTYPE html>';
		echo'<html>';
		echo'<head>';
		echo'<script type="text/javascript" src="alertCategorie.js"></script>';
		echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
		echo'</head>';
		echo'</html>';
		}

	}
}
else if($modifier){ 

	if ($categorieParent == '') {
		# code...	
       $sql1="update `".$nomtableCategorie."` set nomcategorie='".mysql_real_escape_string($categorie)."' WHERE idcategorie =".$idcategorie ;
	   //    var_dump($sql1);
		$res1=@mysql_query($sql1)or die ("modification impossible1 ".mysql_error());
	} else {
		# code...	
       $sql1="update `".$nomtableCategorie."` set nomcategorie='".mysql_real_escape_string($categorie)."',categorieParent=".$categorieParent." WHERE idcategorie =".$idcategorie ;
	   //    var_dump($sql1);
		$res1=@mysql_query($sql1)or die ("modification impossible1 ".mysql_error());
	}
	// echo $sql1;
	$sql2="update `".$nomtableDesignation."` set categorie='".mysql_real_escape_string($categorie)."' where categorie='".$categorieM."'";
	$res2=@mysql_query($sql2)or die ("modification Categorie dans Reference ".mysql_error());
	// echo $sql2;	 

}
else if ($supprimer) {
	$sql2="DELETE FROM `".$nomtableCategorie."` WHERE idcategorie =".$idcategorie ;
	// echo $sql2;
	$res2=@mysql_query($sql2) or die ("suppression impossible categorie   ".mysql_error());

	// var_dump($newCategorie);
	// var_dump($categorieSup); //update `Yaatout Superette-designation` set categorie='Accessoires de bain, toilette' where categorie='ALIMETAIRE'
	
	$sql20="update `".$nomtableDesignation."` set categorie='".mysql_real_escape_string($newCategorie)."' where categorie='".$categorieSup."'";
	// var_dump($sql20);
	$res20=@mysql_query($sql20)or die ("modification Categorie dans Reference ".mysql_error());

    if($_SESSION['vitrine']==1){

		/********************** Début alert mise à jour **********************************/          

		$req50 = $bddV->prepare('UPDATE boutique SET upToDate = :up WHERE idBoutique = :idB');

		$req50->execute(array(

		'idB' => $_SESSION['idBoutique'],

		'up' => 1

		)) or die(print_r($req50->errorInfo()));

		$req50->closeCursor();

		/***************************** Fin alert mise à jour ****************************/ 

    }

}
 



if(isset($_POST["btnUploadImg"])) {

	// var_dump('fiii');
  
	  function resizeImage($resourceType,$image_width,$image_height) {
  
		$resizeWidth = 150;
  
		$resizeHeight = 150;
  
		$imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
  
		imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
  
		return $imageLayer;
  
	}
  
	$imageProcess = 0;

	if(is_array($_FILES)) {

		$fileName = $_FILES['file']['tmp_name'];

		$sourceProperties = getimagesize($fileName);

		$resizeFileName = time();

		$uploadPath = "./imagesCategories/";

		$fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

		$uploadImageType = $sourceProperties[2];

		$sourceImageWidth = $sourceProperties[0];

		$sourceImageHeight = $sourceProperties[1];

		$id         =@$_POST["idC"];

		$idBoutique         =@$_POST["idBoutique"];

		$designation         =@$_POST["designation"];

		$localPath = "./imagesCategories/";

		$remotePath = "public_html/img/categorie/";


		switch ($uploadImageType) {

			case IMAGETYPE_JPEG:

			$resourceType = imagecreatefromjpeg($fileName);

			// $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);

			// imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);

			imagejpeg($resourceType,$uploadPath."".$resizeFileName.'.'. $fileExt);

			$fileNameNew=$resizeFileName.'.'. $fileExt;

			// imagedestroy($imageLayer);

			imagedestroy($resourceType);

			$sql5="UPDATE `".$nomtableCategorie."` set image='".$fileNameNew."' where idcategorie=".$id;		
			$res5=@mysql_query($sql5)or die ("modification impossible X1 ".mysql_error());
																
			/*****************************  SEND FROM SERVER TO SERVER *****************************/

				if ((!$cnx_ftp) || (!$cnx_ftp_auth))

					{

						// echo "";

					}

				else

					{

					// ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);

						// echo " ";

						if (ftp_put($cnx_ftp,$remotePath.$fileNameNew, $localPath.$fileNameNew, FTP_BINARY)) { 

							if($_POST["image"]!=''){ 

								unlink($localPath.$_POST['image']);

								ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;

									// echo "".$remotePath.$_POST['image']; 

							}

						} else{

								//var_dump($remotePath.$fileNameNew);

								//var_dump($localPath.$fileNameNew);

								// echo "echec J"; 

							}

						ftp_quit($cnx_ftp);

					}

			/*****************************  SEND FROM SERVER TO SERVER *****************************/

				break;

			case IMAGETYPE_PNG:

				$resourceType = imagecreatefrompng($fileName);

				//$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);

				//imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);

				imagepng($resourceType,$uploadPath."".$resizeFileName.'.'. $fileExt);

				$fileNameNew=$resizeFileName.'.'. $fileExt;

				// imagedestroy($imageLayer);

				imagedestroy($resourceType);

				$sql5="UPDATE `".$nomtableCategorie."` set image='".$fileNameNew."' where idcategorie=".$id;
				// var_dump($sql5);		
				$res5=@mysql_query($sql5)or die ("modification impossible X2 ".mysql_error());
									
				/*****************************  SEND FROM SERVER TO SERVER *****************************/

					if ((!$cnx_ftp) || (!$cnx_ftp_auth))  {

							// echo "";

					}

					else{

						//                                  ftp_put($cnx_ftp,$uploadPath , $fileNameNew, FTP_BINARY);

							// echo " ";

							if (ftp_put($cnx_ftp,$remotePath.$fileNameNew, $localPath.$fileNameNew, FTP_BINARY)) { 

								if($_POST["image"]!=''){ 

									unlink($localPath.$_POST['image']);

									ftp_delete ($cnx_ftp,$remotePath.$_POST['image'] ) ;

									// echo "".$remotePath.$_POST['image']; 

								}

								} else{

										//var_dump($remotePath.$fileNameNew);

										//var_dump($localPath.$fileNameNew);

											// echo "echec J"; 

									}

							ftp_quit($cnx_ftp);

						}

				/*****************************  SEND FROM SERVER TO SERVER *****************************/

				break;

			default:

				$imageProcess = 0;

				break;

		}

	}
  
	$imageProcess = 0;
  
  }
  
  if(isset($_POST["btnSupImg"])) {
  
	$localPath = "./imagesCategories/";
  
	$remotePath = "public_html/img/categorie/";
  
	if($_POST['image'] != '') {
  
		if (unlink($localPath.$_POST['image'])) {

			   ftp_delete ($cnx_ftp,$remotePath.$_POST['image']);
			// echo "ok";

		}
  
		$id = @$_POST["idC"];

		$fileNameNew='';

		$sql5="UPDATE `".$nomtableCategorie."` set image='".$fileNameNew."' where idcategorie=".$id;		
		$res5=@mysql_query($sql5)or die ("modification impossible Y3  ".mysql_error());
		 
  
	}else {
  
		echo " ";
  
	}
  }
  


/**************** DECLARATION DES ENTETES *************/
?>

<?php require('entetehtml.php'); ?>

<body onLoad="process()">
<?php
/**************** MENU HORIZONTAL *************/

  require('header.php');

/**************** Ajouter une catégorie *************/

/**************** *************  *************/
?>
<div class="container">
    
       
        <center><button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#categorieModal" id="AjoutDesignation">Ajouter une catégorie</button></center>
    



    <div id="categorieModal" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajout d'une Catégorie</h4>
                    </div>
                    <div class="modal-body">
                       <form role="form" class=" formulaire2" name="formulaire2" method="post" action="insertionCategorie.php">
                             <input type="hidden" class="inputbasic" id="classe" name="classe" value="0" >
                            <b>NOM CATEGORIE <font color="red">*</font> </b><input type="text" class="form-control" placeholder="Entrez le nom de la catégorie" id="categorie1" name="categorie1" size="35" value="" required autofocus="" />
                            <b>CATEGORIE PARENT <font color="red">*</font> </b><select class="form-control" name="categorieParent" id="categorieParent">
                        		<option selected value="0">Sans Parent</option>
                        		 <?php
                        			$sql11="SELECT * FROM `". $nomtableCategorie."` WHERE (categorieParent IS NULL OR categorieParent=0) ORDER BY nomcategorie";
                        			$res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                        			while($ligne2 = mysql_fetch_row($res11)) {
                        				echo'<option  value= "'.$ligne2[0].'">'.$ligne2[1].'</option>';

                        			  } ?>
                        	</select>
                            </br>
                           <div align='right'>
						   <font color="red" >Les champs qui ont (*) sont obligatoires</font><br />
                            <input type="submit" class="boutonbasic" name="inserer" value="AJOUTER  >>">
                            
                           </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>


<?php
/**************** Ajouter une catégorie  - - FIN *************/

/**************** *************  *************/
?>


</div>
<br><br>
 
<?php
/**************** FENETRE NODAL ASSOCIEE AU BOUTTON ModifierDesignation *************/



/**************** TABLEAU CONTENANT LA LISTE DES PRODUITS *************/

echo'<div class="container-fluid">
<ul class="nav nav-tabs">';
echo'<li class="active"><a data-toggle="tab" href="#CATEGORIE">LISTE DES CATEGORIES DE PRODUITS</a></li>';

if ($_SESSION['vitrine']==1 || $_SESSION['venteImage']==1) {
	// if (($_SESSION['type']=="Superette") && ($_SESSION['categorie']=="Detaillant")) {

	echo'<li id="categorieEventS"><a data-toggle="tab" href="#CATEGORIEREFSANS">REFERENCES SANS CATEGORIES</a></li>';
  

	echo'<li id="categorieEventA"><a data-toggle="tab" href="#CATEGORIEREFAVEC">REFERENCES AVEC CATEGORIES</a></li>';
  
}
echo'</ul>
<div class="tab-content">';

echo'<div id="CATEGORIE" class="tab-pane fade in active">';
	echo'<div class="table-responsive">
	<table id="tableCategorie" class="display tabDesign" class="tableau3" align="left" border="1">
	<thead>
	  <tr id="thDesignation">
		  <th>Ordre</th>
		  <th>Categorie parent</th>
		  <th>Sous Categorie</th>
		  <th>Operations</th>
	  </tr>
	</thead>
	</table> '; ?>

	<script type="text/javascript">
	$(document).ready(function() {
		$("#tableCategorie").dataTable({
		  "bProcessing": true,
		  "sAjaxSource": "ajax/listerCategorieAjax.php",
		  "aoColumns": [
				{ mData: "0" } ,
				{ mData: "1" },
				{ mData: "2" },
				{ mData: "3" },
			  ],
			  "dom": "Bfrtip",
			  "buttons" : [
				"copy",
				{
				  extend: "excel",
				  messageTop: "Liste des References  ",
				},
				{
				  extend: "pdf",
				  messageTop: "Liste des References ",
				  messageBottom: null
				},
				{
				  extend: "print",
				  text: "Imprimer",
				  messageTop: "Liste des References ",
				}
			  ]
			  
		});  
	});
	</script>

	<?php
	echo '
	  <div id="modifierCategorie"  class="modal fade " role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header" style="padding:35px 50px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Modification d\'une catégorie </b></h4>
			  </div>
			  <div class="modal-body" style="padding:40px 50px;">
				  <form role="form" class="" id="form" name="formulaire2" method="post" action="insertionCategorie.php">
					<div class="form-group">
					  <label for="reference">CATEGORIE <font color="red">*</font></label>
					  <input type="text" class="form-control" name="categorie" id="categorie_Mdf" required />
					</div>
					<div class="form-group">
					  <label for="categorie"> CATEGORIE PARENT <font color="red">*</font></label>
						<select class="form-control" name="categorieParent" id="categorieParent_Mdf">
                        		<option selected id="nomParent_Mdf" ></option> ';
                        			$sql11="SELECT * FROM `". $nomtableCategorie."` WHERE (categorieParent IS NULL OR categorieParent=0) ORDER BY nomcategorie";
                        			$res11=mysql_query($sql11) or die ("insertion impossible Produit".mysql_error());
                        			while($ligne2 = mysql_fetch_row($res11)) {
                        				echo'<option  value= "'.$ligne2[0].'">'.$ligne2[1].'</option>';

									  } 
									  echo '
                        	</select>
					</div>
					<div class="form-group" align="right">
										  <font color="red"><b>Les champs qui ont (*) sont obligatoires</b></font><br />
											<input type="submit" class="boutonbasic"  name="btnModifier" value="MODIFIER  >>"/>
							<input type="hidden" id="idCategorie_Mdf" name="idcategorie" />
							<input type="hidden" name="categorieM" />
						  <input type="hidden" name="modifier" value="1"/>
						  <input type="hidden" name="designationAmodifier" />
					  </div>
				  </form>
			  </div>
			</div>
		</div>
	  </div>

	  <div id="supprimerCategorie" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header" style="padding:35px 50px;">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span><b>Suppression d\'une catégorie</b></h4>
			</div>
			<div class="modal-body" style="padding:40px 50px;">
				<p>NB : Cette opération affecte les produits de cette catégorie à la nouvelle catégorie sélectionnée.</p>			
				<form role="form" class="" id="form" name="formulaire2" method="post" action="insertionCategorie.php">
				  <div class="form-group">
					<label for="reference">Categorie </label>
					<input type="text" class="form-control" name="categorie" id="categorie_Spm" disabled=""/>
					<input type="hidden" class="form-control" name="categorieSup" id="categorie_Spm_Hide" />
				  </div>
					<div class="form-group">
						<label for="newCategorie">Nouvelle categorie </label>
						<select class="form-control" name="newCategorie" id="newCategorie" required>
						</select>
					</div>
					<div class="form-group" align="right">
						<font color="red"><b>Voulez-vous supprimer cette catégorie ? </b></font><br/>
						<input type="submit" class="boutonbasic" name="suppression" value=" SUPPRIMER >>" />
						<input type="hidden" name="idcategorie" id="idCategorie_Spm" />
						<input type="hidden" name="supprimer" value="1" />
					</div>
				</form>
			</div>
			</div>
		</div>
	  </div>
	</div>
</div>';



// if (($_SESSION['type']=="Superette") && ($_SESSION['categorie']=="Detaillant")) {

    echo'<div id="CATEGORIEREFSANS" class="tab-pane fade">

        <div class="table-responsive">

            <table id="tableCategorieRef" class="display tabCategorie" class="tableau3"  width="100%" align="left" border="1">

            <thead>

              <tr id="thDesignation">

                  <th>Ordre</th>

                  <th>Reference</th>';

                //   <th>Code barre</th>
				 

                //   <th>Categorie</th>
				 echo '
                  <th>Sous catégorie(s)</th> 
                  <th>Sous catégorie(s) actuelle(s)</th>';

                //   <th>Unite Stock</th>

                //   <th>Nombre Article U.S</th>

                //   <th>Prix U.S</th>

                //   <th>Prix </th>

                //   <th>Prix Achat</th>
				  echo '

                  <th>Operations</th>

              </tr>

            </thead>

            </table> '; ?>



            <script type="text/javascript">

            $(document).ready(function() {

              $("#categorieEventS").click(function () {

                $("#tableCategorieRef").dataTable({

                  "bProcessing": true,

                  "destroy": true,

                  "sAjaxSource": "ajax/listerProduit-CategorieAjax.php",

                  "aoColumns": [

                        { mData: "0" } ,

                        { mData: "1" },

                        { mData: "2" },

                        { mData: "3" },

                        { mData: "4" },

                        // { mData: "5" } ,

                        // { mData: "6" },

                        // { mData: "7" },

                        // { mData: "8" },

                        // { mData: "9" },

                        // { mData: "10" },

                      ],

                      "dom": "Bfrtip",

                      "buttons" : [

                        "copy",

                        {

                          extend: "excel",

                          messageTop: "Liste des References  ",

                        },

                        {

                          extend: "pdf",

                          messageTop: "Liste des References ",

                          messageBottom: null

                        },

                        {

                          extend: "print",

                          text: "Imprimer",

                          messageTop: "Liste des References ",

                        }

                      ]

                      

                }); 

                $.ajax({

                    url: "ajax/operationAjax_Categorie.php",

                    method: "POST",

                    data: {

                        operation: 2,

                    },

                    success: function (data) {

                        var data = JSON.parse(data);

                        var taille = data.length;

                              for( var i = 0; i<taille; i++){

                                  var tab = data[i].split('<>');

                                  name=tab[1];

                                  $('.categorieSelection').append("<option value='"+name+"'>"+name+"</option>");

                              }

                    },

                    error: function() {

                        alert("La requête 3"); },

                    dataType:"text"

                }); 

              });

            });

            </script>



            <?php

            echo '

        </div>

    </div>';

    echo'<div id="CATEGORIEREFAVEC" class="tab-pane fade">

        <div class="table-responsive">

            <table id="tableCategorieRefAVEC" class="display tabCategorie" class="tableau3"  width="100%" align="left" border="1">

            <thead>

              <tr id="thDesignation">

                  <th>Ordre</th>

                  <th>Reference</th>';

                //   <th>Code barre</th>
				 

                //   <th>Categorie</th>
				 echo '
                  <th>Sous catégorie(s)</th> ';
				  if ($_SESSION['proprietaire']==1 || $_SESSION['gerant']==1) {
					# code...
					echo '
					<th>Prénom - Nom</th> 
					<th>Téléphone</th> ';
				  }
                 // <th>Catégorie(s) Actuelle(s)</th>

                //   <th>Unite Stock</th>

                //   <th>Nombre Article U.S</th>

                //   <th>Prix U.S</th>

                //   <th>Prix </th>

                //   <th>Prix Achat</th>
				  echo '

                  <th>Operations</th>

              </tr>

            </thead>

            </table> '; ?>


<?php
          echo '  <script type="text/javascript">

            $(document).ready(function() {

              $("#categorieEventA").click(function () {

                $("#tableCategorieRefAVEC").dataTable({

                  "bProcessing": true,

                  "destroy": true,

                  "sAjaxSource": "ajax/listerProduit-AvecCategorieAjax.php",

                  "aoColumns": [

                        { mData: "0" } ,

                        { mData: "1" },

                        { mData: "2" },

                        { mData: "3" },';
						
						if ($_SESSION["proprietaire"]==1 || $_SESSION["gerant"]==1) {

							echo ' { mData: "4" },

							{ mData: "5" } ';
						}

                        // { mData: "6" },

                        // { mData: "7" },

                        // { mData: "8" },

                        // { mData: "9" },

                        // { mData: "10" },

                     echo ' ],

                      "dom": "Bfrtip",

                      "buttons" : [

                        "copy",

                        {

                          extend: "excel",

                          messageTop: "Liste des References  ",

                        },

                        {

                          extend: "pdf",

                          messageTop: "Liste des References ",

                          messageBottom: null

                        },

                        {

                          extend: "print",

                          text: "Imprimer",

                          messageTop: "Liste des References ",

                        }

                      ]

                      

                }); 

                $.ajax({

                    url: "ajax/operationAjax_Categorie.php",

                    method: "POST",

                    data: {

                        operation: 2,

                    },

                    success: function (data) {

                        var data = JSON.parse(data);

                        var taille = data.length;

                              for( var i = 0; i<taille; i++){

                                  var tab = data[i].split("<>");

                                  name=tab[1];

                                  $(".categorieSelection").append("<option value="+name+">+name+</option>");

                              }

                    },

                    error: function() {

                        alert("La requête 3"); },

                    dataType:"text"

                }); 

              });

            });

            </script>';

			?>

            <?php

            echo '

        </div>

    </div>';


// }


echo'</section>'.
'<script>$(document).ready(function(){$("#imgmodifier").click(function(){$("#ModifierDesignationModal").modal();});});</script>'.
'<script>$(document).ready(function(){$("#imgsup").click(function(){$("#supprimerDesignationModal").modal();});});</script>'.
'</div></div></div></body></html>';
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="insertionCategorie.php"</script>';
echo'</head>';
echo'</html>';
}
}
else{
echo'<!DOCTYPE html>';
echo'<html>';
echo'<head>';
echo'<script language="JavaScript">document.location="../index.php"</script>';
echo'</head>';
echo'</html>';
}
?>


<div id="imageNvCategorie"  class="modal fade " role="dialog">
	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header" style="padding:35px 50px;">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Image </b></h4>

			</div>

			<div class="modal-body" style="padding:40px 50px;">

				<form   method="post" enctype="multipart/form-data">

					<input type="hidden" name="idC" id="id_Upd_NC"  />

					<div class="form-group" >

					<br/>

						<input type="file" name="file" required/>

					</div>

					<div class="form-group" align="right">

						<input type="submit" class="boutonbasic"  name="btnUploadImg" value="Upload >>"/>

					</div>

				</form>

			</div>

		</div>

	</div>
</div>

<div id="imageExCategorie"  class="modal fade " role="dialog">
	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header" style="">

			<button type="button" class="close" data-dismiss="modal">&times;</button>

			<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>aperçu/Modification </b></h4>

			</div>

			<div class="modal-body" style="">

			<div class="" style="" align="center">
				<img width="50%" height="30%" style="" alt="" src="" id="imgsrc_Upd" />
			</div>

			<form method="post" enctype="multipart/form-data">

				<input type="hidden" name="idC" id="id_Upd_Ex" />

				<input type="hidden" name="image" id="img_Upd_Ex" />

				<div class="form-group" >

					<br />

					<input type="file" name="file"/>

				</div>

				<div class="form-group" align="right">

					<input type="submit" class="boutonbasic"  name="btnSupImg" value="Suprimer >>"/>

					<input type="submit" class="boutonbasic"  name="btnUploadImg" value="Modifier >>"/>

				</div>

			</form>

			</div>

		</div>

	</div>

</div>

<div id="confirmRefCategorie"  class="modal fade " role="dialog">
	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header" style="">

			<button type="button" class="close" data-dismiss="modal">&times;</button>

			<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Confirmer</b></h4>

			</div>

			<div class="modal-body" style="">

				<h3>Vous confirmez cette opération ?</h3>

			</div>
			<div class="modal-footer" style="">
				<input type="text" hidden id="refaireId">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
				<button type="button" class="btn btn-success" onclick="refaireCategorie()">Oui</button>
			</div>
		</div>
	</div>
</div>

<div id="confirmMdfCategorie"  class="modal fade " role="dialog">
	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header" style="">

			<button type="button" class="close" data-dismiss="modal">&times;</button>

			<h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> <b>Confirmer</b></h4>

			</div>

			<div class="modal-body">
				<h3 id="nomProduit"></h3>
				<div id="listCategory">
					<input type="text" class="form-control categorieSearchMdf" autocomplete="off" placeholder="Nouvelle(s) Catégorie(s)...">
				</div>
			</div>
			<div class="modal-footer" style="">
				<input type="text" hidden id="mdfId">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
				<!-- <button type="button" class="btn btn-success">Oui</button> -->
			</div>
		</div>
	</div>
</div>