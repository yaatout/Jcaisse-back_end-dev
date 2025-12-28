<?php
/*
R�sum�:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP,EL hadji mamadou korka
Date de modification:07/04/2016; 04-05-2018
*/ 

$idDesignation =@$_POST["idDesignation"];

$prixuniteStock      =@$_POST["prixuniteStock"];

$uniteStock      =@$_POST["uniteStock"];

$prixachat      =@$_POST["prixachat"];



if(!$annuler){

  if(!$modifier and !$supprimer){

    if($designation){

        if($classe==0){

          $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";

          $res11=mysql_query($sql11);

          if(!mysql_num_rows($res11)){

            $sql="insert into `".$nomtableDesignation."` (designation,classe,uniteStock,prixuniteStock,nbreArticleUniteStock,uniteDetails,prixachat,codeBarreDesignation,categorie)values ('".mysql_real_escape_string($designation)."',0,'".mysql_real_escape_string($uniteStock)."','".$prixuniteStock."','".$nbArticleUniteStock."','".mysql_real_escape_string($uniteDetails)."','".$prixachat."','".mysql_real_escape_string($codeBarre)."','".mysql_real_escape_string($categorie2)."')";

            $res=@mysql_query($sql) or die ("insertion impossible Produit en uniteStock".mysql_error());

          }

          else{

            echo '<script type="text/javascript"> alert("ERREUR : LA REFERENCE ('.$designation.') EXISTE DEJA DANS LE CATALOGUE DES PRODUITS ...");</script>';

          }

        }

        else{

          $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".$designation."'";

          $res11=mysql_query($sql11);

          if(!mysql_num_rows($res11)){

            if($classe==1){

              if($uniteService=='Transaction'){

                $reqT="SELECT * from `aaa-transaction` where nomTransaction='".$designation."'";

                $resT=mysql_query($reqT) or die ("select stock impossible =>".mysql_error());

                $transaction = mysql_fetch_array($resT);

              

                $sql="insert into `".$nomtableDesignation."` (description,designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('".$transaction['idTransaction']."','".mysql_real_escape_string($designation)."',1,0,'".$uniteService."',0,'1','10','".mysql_real_escape_string($categorie2)."')";

                $res=@mysql_query($sql) or die ("insertion impossible Service".mysql_error());

              }

              else{

                $sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('".mysql_real_escape_string($designation)."',1,".$prixSD.",'".$uniteService."','".$prixSD."','1','10','".mysql_real_escape_string($categorie2)."')";

                $res=@mysql_query($sql) or die ("insertion impossible Service".mysql_error());

              }

              

              

            }

            else if($classe==2){

              $sql="insert into `".$nomtableDesignation."` (designation,classe,prix,uniteStock,prixuniteStock,nbreArticleUniteStock,seuil,categorie)values ('".mysql_real_escape_string($designation)."',2,".$prixSD.",'".$uniteDepence."','".$prixSD."','1','10','".mysql_real_escape_string($categorie2)."')";

              $res=@mysql_query($sql) or die ("insertion impossible Frais".mysql_error());

            }

          }

          else{

            echo '<script type="text/javascript"> alert("ERREUR : LA REFERENCE ('.$designation.') EXISTE DEJA DANS LE CATALOGUE DES SERVICES OU DEPENCES ...");</script>';

          }

        }

    }

    else if($categorie1) {

        $sql11="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".$categorie1."'";

      $res11=mysql_query($sql11);

      if(!mysql_num_rows($res11)){

          $sql="insert into `".$nomtableCategorie."` (nomcategorie) values ('".mysql_real_escape_string($categorie1)."')";

              $res=@mysql_query($sql) or die ("insertion categorie 1 impossible".mysql_error());

        }else{

      echo'<!DOCTYPE html>';

      echo'<html>';

      echo'<head>';

      echo'<script type="text/javascript" src="alertCategorie.js"></script>';

      echo'<script language="JavaScript">document.location="insertionProduit.php"</script>';

      echo'</head>';

      echo'</html>';

      }



    }

  }

  else if($modifier){ //if $modifier

 

              $sql="UPDATE `".$nomtableDesignation."` set designation='".mysql_real_escape_string($designation)."',categorie='".mysql_real_escape_string($categorie2)."',uniteStock='".mysql_real_escape_string($uniteStock)."',prixuniteStock='".$prixuniteStock."' WHERE idDesignation=".$idDesignation;

              $res=@mysql_query($sql)or die ("modification impossible1 ".mysql_error());



              $sql2="update `".$nomtableStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;

              //echo $sql2;

              $res2=@mysql_query($sql2)or die ("modification reference dans stock ".mysql_error());



              $sql3="update `".$nomtableEntrepotStock."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;

              //echo $sql2;

              $res3=@mysql_query($sql3)or die ("modification reference dans stock ".mysql_error());



              $sql4="update `".$nomtableLigne."` set designation='".mysql_real_escape_string($designation)."' where idDesignation=".$idDesignation;

              //echo $sql2;

              $res4=@mysql_query($sql4)or die ("modification reference dans stock ".mysql_error());



              

  }

  else if ($supprimer) {



      $sql="DELETE FROM `".$nomtableStock."` WHERE idDesignation=".$idDesignation;

      $res=@mysql_query($sql) or die ("suppression impossible designation".mysql_error());



      $sql1="DELETE FROM `".$nomtableEntrepotStock."` WHERE idDesignation=".$idDesignation;

      $res1=@mysql_query($sql1) or die ("suppression impossible designation".mysql_error());



      $sql2="DELETE FROM `".$nomtableDesignation."` WHERE idDesignation=".$idDesignation;

      $res2=@mysql_query($sql2) or die ("suppression impossible designation".mysql_error());



  }

}

if (isset($_POST['subImport1'])) {



  $fname=$_FILES['fileImport']['name'];

  if ($_FILES["fileImport"]["size"] > 0) {

    $fileName=$_FILES['fileImport']['tmp_name'];

    $handle=fopen($fileName,"r");

    $headers = fgetcsv($handle, 1000, ";");





    while (($data=fgetcsv($handle,1000,";")) !=FALSE) {

      $reference=htmlspecialchars(trim($data[0]));

      $categorie=htmlspecialchars(trim($data[1]));

      $uniteS=htmlspecialchars(trim($data[2]));

      $nbreAuniteS=$data[3];

      $prixU=$data[4];

      $prixUS=$data[5];

      $prixA=$data[6];

      $quantite=$data[7];

      $expiration=$data[8];

      $depot=$data[9];

      //$classe=$data[6];

      $sql10="SELECT * FROM `". $nomtableDesignation."` where designation='".mysql_real_escape_string($reference)."'";

      $res10=mysql_query($sql10);

      if(!mysql_num_rows($res10)){

        $sql3="insert into `".$nomtableDesignation."`(designation,uniteStock,nbreArticleUniteStock,prix,prixuniteStock,prixachat,categorie,classe)

        values('".mysql_real_escape_string($reference)."','".mysql_real_escape_string($uniteS)."',".$nbreAuniteS.",".$prixU.",".$prixUS.",".$prixA.",'".mysql_real_escape_string($categorie)."',0)";

        //var_dump($sql);

        $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());

      }

      if($quantite!=null || $quantite!=''){

        $sql11="SELECT * FROM `". $nomtableDesignation."` where designation='".mysql_real_escape_string($reference)."'";

        $res11=mysql_query($sql11);

        if(mysql_num_rows($res11)){

          $produit=mysql_fetch_array($res11);

          $totalArticleStock=$quantite*$nbreAuniteS;

          $sql3='INSERT INTO `'.$nomtableStock.'`(idDesignation,designation,quantiteStockinitial,uniteStock,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) 

          VALUES('.$produit['idDesignation'].',"'.mysql_real_escape_string($produit['designation']).'",'.$quantite.',"'.mysql_real_escape_string($produit['uniteStock']).'",'.$prixUS.','.$nbreAuniteS.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$expiration.'","'.$_SESSION['iduser'].'")';

          $res3=@mysql_query($sql3) or die ("insertion reference CSV impossible".mysql_error());



          if($depot!=null || $depot!=''){

            $sql13="SELECT * FROM `". $nomtableStock."` where designation='".mysql_real_escape_string($reference)."'";

            $res13=mysql_query($sql13);

            if(mysql_num_rows($res13)){

              $stock=mysql_fetch_array($res13);

              $sql4='INSERT INTO `'.$nomtableEntrepotStock.'`(idDesignation,idStock,idEntrepot,designation,quantiteStockinitial,uniteStock,prixuniteStock,nbreArticleUniteStock, totalArticleStock, dateStockage, quantiteStockCourant,dateExpiration,iduser) 

              VALUES('.$produit['idDesignation'].','.$stock['idStock'].',"'.mysql_real_escape_string($depot).'","'.mysql_real_escape_string($produit['designation']).'",'.$quantite.',"'.mysql_real_escape_string($produit['uniteStock']).'",'.$prixUS.','.$nbreAuniteS.','.$totalArticleStock.',"'.$dateString.'",'.$totalArticleStock.',"'.$expiration.'","'.$_SESSION['iduser'].'")';

              $res4=@mysql_query($sql4) or die ("insertion reference CSV impossible".mysql_error());

            }

          }



        }

      }

      $sql12="SELECT * FROM `". $nomtableCategorie."` where nomcategorie='".mysql_real_escape_string($categorie)."'";

      $res12=mysql_query($sql12);

      if(!mysql_num_rows($res12))

          if($categorie) {

          $sql="insert into `".$nomtableCategorie."` (nomcategorie) values ('".mysql_real_escape_string($categorie)."')";

          $res=@mysql_query($sql) or die ("insertion categorie CSV impossible".mysql_error());

      }

    }

    fclose($handle);

    echo'<!DOCTYPE html>';

    echo'<html>';

    echo'<head>';

    //echo'<script type="text/javascript">alert("les references qui existe deja ne sont pas importes");</script>'

    echo'<script language="JavaScript">document.location="insertionProduit.php"</script>';

    echo'</head>';

    echo'</html>';

  }



  if ( $_GET['l']==7) {



    $fname=$_FILES['fileImport']['name'];

    if ($_FILES["fileImport"]["size"] > 0) {

      $fileName=$_FILES['fileImport']['tmp_name'];

      $handle=fopen($fileName,"r");

      $tabDes;

      while (($data=fgetcsv($handle,1000,";")) !=FALSE) {



      $tabDes[]=$data;



      }

      //return  $tabDes;

      //var_dump($tabDes);

      fclose($handle);



    }



  }



}

if(isset($_POST["Export"])){



      header('Content-Type: text/csv; charset=utf-8');

      header('Content-Disposition: attachment; filename=data-Reference.csv');

      $delimiter = ";";

      $output = fopen("php://output", "w");

      $fields=array('REFERENCE','CATEGORIE', 'UNITE-STOCK','NBRES-ARTICLES-UNITE-STOCK',

                    'PRIX-UNITAIRE', 'PRIX-UNITE-STOCK', 'PRIX-ACHAT', 'QUANTITE-STOCK', 'DATE EXPIRATION', 'ENTREPOT');

      fputcsv($output,$fields, $delimiter );

      fclose($output); exit;



}

/**************** DECLARATION DES ENTETES *************/
?>

<?php require('entetehtml.php'); ?>

<body>
<?php    require('header.php');  ?>

<div class="container">

    
    <?php if ($_SESSION['enConfiguration']==0) {  ?>

    <center>
      <table border="0">
        <tr>
          <td>
            <form class="form-horizontal" action="insertionProduit.php" method="post" name="upload_excel" enctype="multipart/form-data">
                <input type="submit" name="Export" class="btn btn-success" value="Générer le modéle CSV d\'Importation d\'un catalogue"/>
            </form>

          </td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <!-- <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> -->
          <td>
            <form class="form-inline" action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post" enctype="multipart/form-data">
                <input type="file" id="importInput" name="fileImport" data-toggle="modal" onChange="loadCSV()" required>
                <button type="submit" name="subImport1" value="Importer " class="btn btn-success">Importer un catalogue</button>
            </form>
          </td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td><button type="button" class="btn btn-success" onclick="ajouter_Produit()">
            <i class="glyphicon glyphicon-plus"></i>Ajout Produit</button>
          </td>
          <td>  
            <form class="col-md-4" target="_blank" method="post" action="pdfReference.php">
              <button type="submit" class="btn btn-default" >
                  <span class="glyphicon glyphicon-download-alt"></span> Imprimer référence
              </button>
            </form> 
          </td>
        </tr>
      </table>
    </center>

    <?php  } else { ?>
    <center>
      <button type="button" class="btn btn-success" onclick="ajouter_Produit()">
          <i class="glyphicon glyphicon-plus"></i>Ajout Produit
      </button>
    </center>
    <?php  } ?>

    <div id="ajouter_Produit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Ajouter Produit</h4>
          </div>
          <form class="form" id="form_ajouter_Produit" style="padding:30px 30px;">
            <div class="modal-body">
              <div class="form-group">
                  <label for="inpt_ajt_Produit_Reference">Reference </label>
                  <input type="text" class=" form-control" id="inpt_ajt_Produit_Reference"  autocomplete="off"  />
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_CodeBarre">Code Barre</label>
                  <input type="text" class=" form-control" id="inpt_ajt_Produit_CodeBarre"  autocomplete="off"  />
              </div>
              <div class="form-group">
                  <label for="slct_ajt_Produit_Categorie">Categorie </label>
                  <select  id="slct_ajt_Produit_Categorie" class="form-control"></select>
              </div>
              <div class="form-group">
                  <label for="slct_ajt_Produit_UniteStock">Unite Stock (US) </label>
                  <select onchange="choix_unite(this.value)" id="slct_ajt_Produit_UniteStock" class="form-control"></select>
              </div>
              <div class="form-group div_unite_Stock" style="display:none">
                <label for="inpt_ajt_Produit_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> </label>
                <input type="number" class=" form-control" id="inpt_ajt_Produit_NombreArticles" value="1" />
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_PrixUniteStock">Prix Unite Stock (US)</label>
                  <input type="number" class=" form-control" id="inpt_ajt_Produit_PrixUniteStock" value="0"  />
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_PrixUnitaire">Prix Unitaire</label>
                  <input type="number" class=" form-control" id="inpt_ajt_Produit_PrixUnitaire" value="0"  />
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_PrixAchat">Prix de Reviens</label>
                  <input type="number" class=" form-control" id="inpt_ajt_Produit_PrixAchat" value="0"  />
              </div>
            </div>
            <div class="modal-footer">
              <div class="col-sm-6 "> 
                <button type="button" id="btn_ajouter_Produit" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Ajouter</span> </button>
              </div>
              <div class="col-sm-6 "> 
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="modifier_Produit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modifier Produit</h4>
          </div>
          <form class="form" id="form_modifier_Produit" style="padding:30px 30px;" >
            <div class="modal-body">
              <div class="form-group">					    
                  <input type="hidden" class="form-control" id="inpt_mdf_Produit_idProduit"  >
              </div>
              <div class="form-group">
                  <label for="inpt_mdf_Produit_Reference">Reference </label>
                  <input type="text" class=" form-control" id="inpt_mdf_Produit_Reference"  />
              </div>
              <div class="form-group">
                  <label for="inpt_mdf_Produit_CodeBarre">Code Barre</label>
                  <input type="text" class=" form-control" id="inpt_mdf_Produit_CodeBarre"  />
              </div>
              <div class="form-group">
                  <label for="slct_mdf_Produit_Categorie">Categorie </label>
                  <select  id="slct_mdf_Produit_Categorie" class=" form-control"></select>
              </div>
              <div class="form-group">
                  <label for="slct_mdf_Produit_UniteStock">Unite Stock (US) </label>
                  <select onchange="choix_unite(this.value)" id="slct_mdf_Produit_UniteStock" class=" form-control"></select>
              </div>
              <div class="form-group div_unite_Stock">
                  <label for="inpt_mdf_Produit_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> </label>
                  <input type="number" class=" form-control" id="inpt_mdf_Produit_NombreArticles" value="1" />
              </div>
              <div class="form-group">
                  <label for="inpt_mdf_Produit_PrixUniteStock">Prix Unite Stock (US) </label>
                  <input type="number" class=" form-control" id="inpt_mdf_Produit_PrixUniteStock" value="0"  />
              </div>
              <div class="form-group">
                  <label for="inpt_mdf_Produit_PrixUnitaire">Prix Unitaire </label>
                  <input type="number" class=" form-control" id="inpt_mdf_Produit_PrixUnitaire" value="0"  />
              </div>
              <div class="form-group">
                  <label for="inpt_mdf_Produit_PrixAchat">Prix de Reviens</label>
                  <input type="number" class=" form-control" id="inpt_mdf_Produit_PrixAchat" value="0"  />
              </div>
            </div>
            <div class="modal-footer">
              <div class="col-sm-6 "> 
                <button type="button" id="btn_modifier_Produit" class="btn btn-warning pull-left"><span class="mot_Entregistrer">Modifier</span> </button>
              </div>
              <div class="col-sm-6 "> 
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="supprimer_Produit" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Supprimer Produit</h4>
          </div>
          <div class="modal-body">
              <form >
                <div class="modal-body">
                  <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_spm_Produit_idProduit"  >
                  </div>
                  <div class="form-group">
                      <label for="span_spm_Produit_Reference">Reference : </label>
                      <span id="span_spm_Produit_Reference" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_spm_Produit_CodeBarre">Code Barre : </label>
                      <span id="span_spm_Produit_CodeBarre" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_spm_Produit_Categorie">Categorie : </label>
                      <span id="span_spm_Produit_Categorie" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_spm_Produit_UniteStock">Unite Stock (US) : </label>
                      <span id="span_spm_Produit_UniteStock" ></span>
                  </div>
                  <div class="form-group div_unite_Stock" style="display:none">
                      <label for="span_spm_produit_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> : </label>
                      <span id="span_spm_produit_NombreArticles" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_spm_Produit_PrixUniteStock">Prix  Unite Stock (US) : </label>
                      <span id="span_spm_Produit_PrixUniteStock" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_spm_Produit_PrixAchat">Prix de Reviens : </label>
                      <span id="span_spm_Produit_PrixAchat" ></span>
                  </div>
                  <div class="row" id="spm_impossible" style="display:none"> 
                    <div class="form-group col-md-12">
                      <h5 style="color : red"> </h5>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="col-sm-6 "> 
                    <button type="button" id="btn_supprimer_Produit" class="btn btn-danger pull-left"><span class="mot_Entregistrer">Supprimer</span> </button>
                  </div>
                  <div class="col-sm-6 "> 
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                  </div>
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>

    <div id="ajouter_Produit_Stock" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Ajouter un Stock du Produit</h4>
          </div>
          <form class="form" id="form_ajouter_Produit" style="padding:30px 30px;">
            <div class="modal-body">
              <div class="form-group">					    
                  <input type="hidden" class="form-control" id="inpt_ajt_Produit_Stock_idProduit"  >
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_Stock_Reference">Reference </label>
                  <input type="text" class=" form-control" id="inpt_ajt_Produit_Stock_Reference" disabled="true" />
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_Stock_UniteStock">Unite Stock (US) </label>
                  <input type="text" class=" form-control" id="inpt_ajt_Produit_Stock_UniteStock" disabled="true" />
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_Stock_Quantite">Quantite par <span style="color:red" id="inpt_ajt_Produit_Stock_UniteStock2"></span></label>
                  <input type="number" class=" form-control" id="inpt_ajt_Produit_Stock_Quantite" value="1"  />
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_Stock_Depot">Dépôt</label>                  
                  <select  id="inpt_ajt_Produit_Stock_Depot" class="form-control"></select>
              </div>
              <div class="form-group">
                  <label for="inpt_ajt_Produit_Stock_DateExpiration">Date Expiration</label>
                  <input type="date" class=" form-control" id="inpt_ajt_Produit_Stock_DateExpiration" />
              </div>
            </div>
            <div class="modal-footer">
              <div class="col-sm-6 "> 
                <button type="button" id="btn_ajouter_Produit_Stock_Ajouter" class="btn btn-primary pull-left"><span class="mot_Entregistrer">Ajouter</span> </button>
                <button type="button" id="btn_ajouter_Produit_Stock_Terminer" class="btn btn-success pull-right"><span class="mot_Entregistrer">Terminer</span> </button>
              </div>
              <div class="col-sm-6 "> 
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="action_Produit" class="modal fade"  role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" >Desarchiver Produit</h4>
              </div>
              <form >
                <div class="modal-body">
                  <div class="form-group">					    
                    <input type="hidden" class="form-control" id="inpt_act_Produit_idProduit"  >
                  </div>
                  <div class="form-group">
                      <label for="span_act_Produit_Reference">Reference : </label>
                      <span id="span_act_Produit_Reference" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_act_Produit_CodeBarre">Code Barre : </label>
                      <span id="span_act_Produit_CodeBarre" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_act_Produit_Categorie">Categorie : </label>
                      <span id="span_act_Produit_Categorie" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_act_Produit_UniteStock">Unite Stock (US) : </label>
                      <span id="span_act_Produit_UniteStock" ></span>
                  </div>
                  <div class="form-group div_unite_Stock" style="display:none">
                      <label for="span_act_produit_NombreArticles">Nombre Article dans <span class="span_uniteStock"></span> : </label>
                      <span id="span_act_produit_NombreArticles" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_act_Produit_PrixUniteStock">Prix  Unite Stock (US)  : </label>
                      <span id="span_act_Produit_PrixUniteStock" ></span>
                  </div>
                  <div class="form-group">
                      <label for="span_act_Produit_PrixAchat">Prix de Reviens : </label>
                      <span id="span_act_Produit_PrixAchat" ></span>
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="col-sm-6 "> 
                    <button type="button" id="btn_desarchiver_Produit" class="btn btn-success pull-left"><span class="mot_Entregistrer">Desarchiver</span> </button>
                  </div>
                  <div class="col-sm-6 "> 
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                  </div>
                </div>
              </form>
          </div>
      </div>
    </div>

    <div id="message_Produit" class="modal fade"  role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="message_Produit_titre" ></h4>
                </div>
                  <div class="modal-body">

                  </div>
                  <div class="modal-footer">
                    <div class="col-sm-12 "> 
                      <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><span class="mot_Fermer">Annuler</span></button>
                    </div>
                  </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="lister_Doublon" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4><span class='glyphicon glyphicon-lock'></span> Ajout de Stock </h4>
            </div>
            <div class="modal-body" >
                <div class="table-responsive">                
                  <table id="listeFusion" class="table table-bordered" width="100%" border="1">
                    <thead>
                        <tr>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Unite Stock (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
                          <th>Prix Achat</th>
                          <th>Date</th>
                          <th>Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
            </div> 
        </div>
      </div>
    </div>

    <br>
    <ul class="nav nav-tabs">
      <li class="active" id="listeProduitsEvent">
        <a data-toggle="tab" href="#listeProduitsTab">LISTE DES PRODUITS</a>
      </li>
      <li class="" id="listeArchivesEvent">
        <a data-toggle="tab" href="#listeArchivesTab">LISTE DES ARCHIVES</a>
      </li> 
      <li class="" id="listeDoublonEvent">
          <a data-toggle="tab" href="#listeDoublonTab">LISTE DES DOUBLONS</a>
      </li>     
    </ul>

    <div class="tab-content">
      <div id="listeProduitsTab" class="tab-pane fade in active">
        <br />
        <div class="table-responsive">

          <div class="container row">
          </div>

          <table id="listeProduits" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>IdDesignation</th>
                    <th>Reference</th>
                    <th>Code Barre</th>
                    <th>Unite Stock (US)</th>
                    <th>Nombre Articles (US)</th>
                    <th>Prix (US)</th>
                    <th>Prix Unitaire</th>
                    <th>Prix Achat</th>
                    <th>Operations</th>
                </tr>
            </thead>
          </table>

        </div>
      </div>
      <div id="listeArchivesTab" class="tab-pane fade">
        <br />
        <div class="table-responsive">

          <table id="listeArchives" class="display tabStock" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>IdDesignation</th>
                    <th>Reference</th>
                    <th>Code Barre</th>
                    <th>Unite Stock (US)</th>
                    <th>Nombre Articles (US)</th>
                    <th>Prix (US)</th>
                    <th>Prix Achat</th>
                    <th>Operations</th>
                </tr>
            </thead>
          </table>

        </div>
      </div>   
      <div id="listeDoublonTab" class="tab-pane fade">
      <br />
      <div class="table-responsive">
          <ul class="nav nav-tabs">
            <li class="active" id="listeDoublonDesignationEvent">
                <a data-toggle="tab" href="#listeDoublonDesignationTab">DOUBLON PAR DESIGNATION</a>
            </li>
            <li class="" id="listeDoublonCodeBarreEvent">
                <a data-toggle="tab" href="#listeDoublonCodeBarreTab">DOUBLON PAR CODE BARRE</a>
            </li>     
          </ul>
          <div class="tab-content">
            <div id="listeDoublonDesignationTab" class="tab-pane fade in active">
              <br />
              <div class="table-responsive">
                <table id="listeDoublonDesignation" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Unite Stock (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
                          <th>Prix Achat</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div id="listeDoublonCodeBarreTab" class="tab-pane fade">
              <br />
              <div class="table-responsive">
                <table id="listeDoublonCodebarre" class="display tabStock" class="tableau3" width="100%" border="1">
                  <thead>
                      <tr>
                          <th>IdDesignation</th>
                          <th>Reference</th>
                          <th>Code Barre</th>
                          <th>Unite Stock (US)</th>
                          <th>Prix (US)</th>
                          <th>Prix Unitaire</th>
                          <th>Prix Achat</th>
                          <th>Operations</th>
                      </tr>
                  </thead>
                </table>
              </div>
            </div>  
          </div>
      </div>
    </div>       
    </div>

</div>

<script type="text/javascript" src="scripts/insertionProduit_ET.js"></script>

</body>
</html>