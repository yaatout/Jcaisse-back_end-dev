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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css">

    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/datatables.min.css">
	<script src="js/datatables.min.js"></script>
    <script> $(document).ready(function () { $("#exemple").DataTable();});</script>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>JCAISSE-BACK END</title>
</head>
<body>

	<?php
	   require('header.php');
	?>
        
	 <div class="container" >
                    <div class="jumbotron">
				
                <?php 
                      $sqlDV="SELECT SUM(montant)	FROM `aaa-comptemouvement`	where  annuler!=1 && (operation='versement' OR operation='depot')";
                                    $resDV=mysql_query($sqlDV) or die ("select stock impossible =>".mysql_error());
                                    $S_facture = mysql_fetch_array($resDV);
		                            $montantDV = $S_facture[0];
                                    
                                    $sqlR="SELECT SUM(montant)	FROM `aaa-comptemouvement`	where  operation='retrait' &&  annuler!='1'  ";
                                    $resR=mysql_query($sqlR) or die ("select stock impossible =>".mysql_error());
                                    $S_factureR = mysql_fetch_array($resR);
		                            $montantR = $S_factureR[0];
                                    
                                    $sqlVT="SELECT SUM(montant)	FROM `aaa-comptemouvement` where (operation='virement' OR operation='transfert') && annuler!='1' ";
                                    $resVT=mysql_query($sqlVT) or die ("select stock impossible =>".mysql_error());
                                    $S_factureVT = mysql_fetch_array($resVT);
		                            $montantVT = $S_factureVT[0];
                                    
                                    // POUR PRET et REMBOURSEMENT
                                    $sqlPret="SELECT SUM(montant)	FROM `aaa-comptemouvement` where operation='pret' && annuler!='1' ";
                                    $resPret=mysql_query($sqlPret) or die ("select stock impossible =>".mysql_error());
                                    $S_facturePret = mysql_fetch_array($resPret);
		                            $montantPret = $S_facturePret[0];
                                    
                                    $sqlRemb="SELECT SUM(montant)	FROM `aaa-comptemouvement` where operation='remboursement' && annuler!='1' ";
                                    $resRemb=mysql_query($sqlRemb) or die ("select stock impossible =>".mysql_error());
                                    $S_factureRemb = mysql_fetch_array($resRemb);
		                            $montantRemb = $S_factureRemb[0];
                        
                                    
                 ?>
<!--               <h2>Compte <?php echo $compte['nomCompte'].' : '.number_format($compte['montantCompte'], 0, ',', ' ')." FCFA" ; ?></h2>-->
				<div class="panel-group">
                        <div class="panel" style="background:#cecbcb;">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse1">  Liste operation  </a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-heading" style="margin-left:2%;">

                                    <?php 
                                             echo "<h6>Montant depot et versement : ".number_format($montantDV, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant retrait :  ".number_format($montantR, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant virement et transfert : ".number_format($montantVT, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant pret : ".number_format($montantPret, 0, ',', ' ')." FCFA</h6>
                                            <h6>Montant remboursement : ".number_format($montantRemb, 0, ',', ' ')." FCFA</h6>"
                                                 ;
                                       
                                       
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

			</div>
                        <br>
                        
                         
                   
                        <!-- Debut Javascript de l'Accordion pour Tout les Paniers -->
                        <script >
                            $(function() {
                                $(".expand").on( "click", function() {
                                    // $(this).next().slideToggle(200);
                                    $expand = $(this).find(">:first-child");

                                    if($expand.text() == "+") {
                                    $expand.text("-");
                                    } else {
                                    $expand.text("+");
                                    }
                                });
                            });
                        </script>
                        <!-- Fin Javascript de l'Accordion pour Tout les Paniers -->

                        <!-- Debut de l'Accordion pour Tout les Paniers -->
                        <div class="panel-group mt-5" id="accordion">

                            <?php
                            /**Debut informations sur la date d'Aujourdhui **/
                                $date = new DateTime();
                                $annee =$date->format('Y');
                                $mois =$date->format('m');
                                $jour =$date->format('d');
                                $heureString=$date->format('H:i:s');
                                $dateString2=$jour.'-'.$mois.'-'.$annee;
                            /**Fin informations sur la date d'Aujourdhui **/

                            // On détermine sur quelle page on se trouve
                            if(isset($_GET['page']) && !empty($_GET['page'])){
                                $currentPage = (int) strip_tags($_GET['page']);
                            }else{
                                $currentPage = 1;
                            }
                            // On détermine le nombre d'articles par page
                            $parPage = 10;

                            /**Debut requete pour Compter les Paniers vendus Aujourd'hui  **/
                                $sqlC="SELECT COUNT(*) FROM `aaa-comptemouvement` where annuler!='1'  ORDER BY idMouvement DESC";
                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                $nbre = mysql_fetch_array($resC) ;
                                $nbPaniers = (int) $nbre[0];
                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

                            // On calcule le nombre de pages total
                            $pages = ceil($nbPaniers / $parPage);

                            $premier = ($currentPage * $parPage) - $parPage;

                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                $sqlP1="SELECT * FROM `aaa-comptemouvement` where annuler!='1' ORDER BY dateOperation DESC LIMIT ".$premier.",".$parPage." ";
                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/


                            ?>         

                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                <?php $n=$nbPaniers - (($currentPage * 10) - 10); 
                            while ($mouvement = mysql_fetch_array($resP1)) { 
                                     $sqlv="select * from `aaa-compte` where idCompte=".$mouvement['idCompte']."";
                                      $resv=mysql_query($sqlv);
                                      $compte =mysql_fetch_assoc($resv);

                            ?>

                                    <div style="padding-top : 2px;" 
                                         <?php if($compte['typeCompte']=='compte cheques'){
                                                if($mouvement['retirer']==0){
                                                    echo ' class="panel panel-success "';
                                                } else{
                                                     echo ' class="panel panel-default"';
                                                }
                                            }else{
                                                if($mouvement['operation']=='versement' or $mouvement['operation']=='depot'  or $mouvement['operation']=='pret'){
                                                    echo ' class="panel panel-success "';
                                                } elseif($mouvement['operation']=='retrait' or $mouvement['operation']=='remboursement' ){
                                                     echo ' class="panel panel-danger"';
                                                }else{
                                                     echo ' class="panel panel-primary"';
                                                }
                                            }
                                
                                            
                                         ?>
                                        >
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" <?php echo  "href=#versement".$mouvement['idMouvement']."" ; ?>  class="panel-title expand">
                                            <div class="right-arrow pull-right">+</div>
                                            <a href="#"> Operation <?php echo $n; ?>
                                                <span class="spanDate noImpr"> Compte : <?php echo $compte['nomCompte']; ?> </span>
                                                <span class="spanDate noImpr"> Type: <?php echo $mouvement['operation']; ?> </span>
                                                <span class="spanDate noImpr"> Date: <?php echo $mouvement['dateSaisie']; ?> </span>
                                                <span class="spanDate noImpr">Montant: <?php echo number_format($mouvement['montant'], 0, ',', ' ')." FCFA" ; ?></span>
                                                
                                                 <?php 
                                                    $sql="SELECT * from `aaa-utilisateur` where idutilisateur='".$mouvement['idUser']."' ";
                                                    $res=mysql_query($sql);
                                                    $user=mysql_fetch_array($res);
                                                ?>
                                                <span class="spanDate noImpr">  #<?php echo substr(strtoupper($user['prenom']),0,3); ?></span>
                                            </a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse " <?php echo  "id=versement".$mouvement['idMouvement']."" ; ?> >
                                            <div class="panel-body" >
                                                
                                                <?php 
                                                    if($mouvement['operation']=='transfert' || $mouvement['operation']=='virement'){
                                                        echo "<p><span class='label label-info'>Numero du destinataire :</span>".$mouvement['numeroDestinataire']."</p>";
                                                    }
                                
                                                    if($compte['typeCompte']=='compte cheques'){
                                                         echo "<p><span class='label label-danger'>Date echéance  : </span>".$mouvement['dateEcheance']."</p>";
                                                    }
                        
                                
                                                        echo  "<p><span class='label label-success'>Description :</span>".$mouvement['description']."</p>";
                                                        
                                                    
                                                ?>
                                                
                                                
                                            </div>
                                        </div>

                                <?php $n=$n-1;   } ?>
                                <?php if($nbPaniers >= 11){ ?>
                                    <ul class="pagination pull-right">
                                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                            <a href="opComtes.php?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                                        </li>
                                        <?php for($page = 1; $page <= $pages; $page++): ?>
                                            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                                <a href="opComtes.php?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                            </li>
                                        <?php endfor ?>
                                            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                            <a href="opComtes.php?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                        </div>
                        <!-- Fin de l'Accordion pour Tout les Paniers -->

            
              </div>
    </div>    
</body>
</html>
