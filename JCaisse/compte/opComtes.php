<?php
session_start();

require('../connection.php');
require('../declarationVariables.php');

date_default_timezone_set('Africa/Dakar');

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



// if(!$_SESSION['iduserBack']){
// 	header('Location:index.php');
// }
// if($_SESSION['profil']!="SuperAdmin")
//     header('Location:accueil.php');

// ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- <base href="http://localhost:81/jcaisse_save/JCaisse/"> -->
   <base href="https://jcaisse.org/JCaisse/"> 
    <!-- <base href="https://jcaisse-dev.org/JCTest/"> -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/datatables.min.css">
	<script src="js/datatables.min.js"></script>
    <script> $(document).ready(function () { $("#exemple").DataTable();});</script>
    <script type="text/javascript" src="prixdesignation.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>JCAISSE</title>
</head>
<body>

	<?php
	   require('../header.php');
	?>
        
	 <div class="container" >
                    <div class="jumbotron">
				
                <?php 
                      $sqlDV="SELECT SUM(montant)	FROM `".$nomtableComptemouvement."`	where  annuler!=1 && (operation='versement' OR operation='depot')";
                                    $resDV=mysql_query($sqlDV) or die ("select stock impossible =>".mysql_error());
                                    $S_facture = mysql_fetch_array($resDV);
		                            $montantDV = $S_facture[0];
                                    
                                    $sqlR="SELECT SUM(montant)	FROM `".$nomtableComptemouvement."`	where  operation='retrait' &&  annuler!='1'  ";
                                    $resR=mysql_query($sqlR) or die ("select stock impossible =>".mysql_error());
                                    $S_factureR = mysql_fetch_array($resR);
		                            $montantR = $S_factureR[0];
                                    
                                    $sqlVT="SELECT SUM(montant)	FROM `".$nomtableComptemouvement."` where (operation='virement' OR operation='transfert') && annuler!='1' ";
                                    $resVT=mysql_query($sqlVT) or die ("select stock impossible =>".mysql_error());
                                    $S_factureVT = mysql_fetch_array($resVT);
		                            $montantVT = $S_factureVT[0];
                                    
                                    // POUR PRET et REMBOURSEMENT
                                    $sqlPret="SELECT SUM(montant)	FROM `".$nomtableComptemouvement."` where operation='pret' && annuler!='1' ";
                                    $resPret=mysql_query($sqlPret) or die ("select stock impossible =>".mysql_error());
                                    $S_facturePret = mysql_fetch_array($resPret);
		                            $montantPret = $S_facturePret[0];
                                    
                                    $sqlRemb="SELECT SUM(montant)	FROM `".$nomtableComptemouvement."` where operation='remboursement' && annuler!='1' ";
                                    $resRemb=mysql_query($sqlRemb) or die ("select stock impossible =>".mysql_error());
                                    $S_factureRemb = mysql_fetch_array($resRemb);
		                            $montantRemb = $S_factureRemb[0];
                        
                                    
                 ?>
<!--               <h2>Compte <?php echo $compte['nomCompte'].' : '.number_format(($compte['montantCompte'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'] ; ?></h2>-->
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
                                             echo "<h6>Montant depot et versement : ".number_format(($montantDV * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant retrait :  ".number_format(($montantR * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant virement et transfert : ".number_format(($montantVT * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant pret : ".number_format(($montantPret * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>
                                            <h6>Montant remboursement : ".number_format(($montantRemb * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole']."</h6>"
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
                                $sqlC="SELECT COUNT(*) FROM `".$nomtableComptemouvement."` where annuler!='1'  ORDER BY idMouvement DESC";
                                $resC = mysql_query($sqlC) or die ("persoonel requête 2".mysql_error());
                                $nbre = mysql_fetch_array($resC) ;
                                $nbPaniers = (int) $nbre[0];
                            /**Fin requete pour Compter les Paniers vendus Aujourd'hui  **/

                            // On calcule le nombre de pages total
                            $pages = ceil($nbPaniers / $parPage);

                            $premier = ($currentPage * $parPage) - $parPage;

                            /**Debut requete pour Lister les Paniers vendus Aujourd'hui  **/
                                // $sqlP1="SELECT * FROM `".$nomtableComptemouvement."` where annuler!='1' ORDER BY dateOperation DESC LIMIT ".$premier.",".$parPage." ";
                                $sqlP1="SELECT * FROM `".$nomtableComptemouvement."` where annuler!='1' ORDER BY idMouvement DESC LIMIT ".$premier.",".$parPage." ";
                                $resP1 = mysql_query($sqlP1) or die ("persoonel requête 2".mysql_error());
                            /**Fin requete pour Lister les Paniers vendus Aujourd'hui  **/


                            ?>         

                            <!-- Debut Boucle while concernant les Paniers Vendus -->
                                <?php $n=$nbPaniers - (($currentPage * 10) - 10); 
                            while ($mouvement = mysql_fetch_array($resP1)) { 
                                     $sqlv="select * from `".$nomtableCompte."` where idCompte=".$mouvement['idCompte']."";
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
                                                <span class="spanDate noImpr"> Date: <?php echo $mouvement['dateOperation']; ?> </span>
                                                <span class="spanDate noImpr">Montant: <?php echo number_format(($mouvement['montant'] * $_SESSION['devise']), 0, ',', ' ').' '.$_SESSION['symbole'] ; ?></span>
                                                
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
                        
                                
                                                        echo  "<p><span class='label label-default'>Description :</span>".$mouvement['description']."</p>";
                                                        
                                                    if($mouvement['idCompte']=='2'){
                                                        $sql12="SELECT * FROM `".$nomtablePagnet."` p, `".$nomtableClient."` c where p.idClient=c.idClient AND p.idCompte=2 AND p.verrouiller=1 AND (p.type=0 || p.type=30) AND p.idPagnet=".$mouvement['mouvementLink'];
                                                        $res12 = mysql_query($sql12) or die ("persoonel requête 2".mysql_error());
                                                        $res = mysql_fetch_array($res12) ;

                                                        echo  "<p><span class='label label-info'>Client :</span>".$res['prenom']." ".$res['nom']." - ".$res['adresse']."</p>";
                                                    }
                                                        
                                                    
                                                ?>
                                                
                                                
                                            </div>
                                        </div>

                                <?php $n=$n-1;   } ?>
                                                 
                                <?php 
                                    echo '<div class="" style="margin-left:42%">';
                                        // To generate links, we call the pagination function here. 
                                        echo paginate_function($parPage, $currentPage, $nbPaniers, $pages);
                                    echo '</div>';
                                ?>
                            <!-- Fin Boucle while concernant les Paniers Vendus  -->

                        </div>
                        <!-- Fin de l'Accordion pour Tout les Paniers -->

            
              </div>
    </div>    
</body>
</html>


<?php    
   

function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
    {
        $pagination = '';
        if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination pull-right">';
            
            if ($current_page == 1) {
            # code...
            $pagination .= '<li class="page-item disabled"><a data-page="1" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>
            </li>';
            } else {
            # code...
            $pagination .= '<li class="page-item"><a href="compte/opComtes.php?page='.($current_page - 1).'" data-page="'.($current_page - 1).'" class="page-link"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a></li>';
            }        
        
            if ($total_pages <= 5) {                                            
            for($page = 2; $page <= $total_pages; $page++){
                if ($current_page == $page) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="compte/opComtes.php?page='.($page).'" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="compte/opComtes.php?page='.($page).'" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                }
                }
            }else {  
                if ($current_page == 1) {
                # code...
                $pagination .= '<li class="page-item active"><a href="compte/opComtes.php?page=1" data-page="1" class="page-link">1</a></li>';
                } else {
                # code...
                $pagination .= '<li class="page-item"><a href="compte/opComtes.php?page=1" data-page="1" class="page-link">1</a></li>';
                }
                                                                
                if($current_page <= 9){ 
                for($page = 2 ; $page <= 10; $page++){
                    if ($current_page == $page) {
                    # code...
                    $pagination .= '<li class="page-item page active"><a href="compte/opComtes.php?page='.($page).'" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    } else {
                    # code...
                    $pagination .= '<li class="page-item page"><a href="compte/opComtes.php?page='.($page).'" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    }                  
                }         
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                                            
                } else if(($current_page > 9) and ($current_page < $total_pages - 9)){  
                    // var_dump(1111);
                    $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';

                    for($page =$current_page-3 ; $page <= ($current_page + 3); $page++){ 
                    if ($current_page == $page) {
                        # code...
                        $pagination .= '<li class="page-item page active"><a href="compte/opComtes.php?page='.($page).'" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    } else {
                        # code...
                        $pagination .= '<li class="page-item page"><a href="compte/opComtes.php?page='.($page).'" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    }
                }
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                
                }else{
                $pagination .= '<li class="page-item"><a class="page-link">...</a></li>';
                for($page = ($total_pages - 2) ; $page <= ($total_pages - 1); $page++){
                    if ($current_page == $page) {
                    # code...
                    $pagination .= '<li class="page-item page active"><a href="compte/opComtes.php?page='.($page).'" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    } else {
                    # code...
                    $pagination .= '<li class="page-item page"><a href="compte/opComtes.php?page='.($page).'" data-page="'.$page.'" class="page-link">'.$page.'</a></li>';
                    }
                }
                }
                if ($current_page == $total_pages) {
                # code...
                $pagination .= '<li class="page-item page active"><a href="compte/opComtes.php?page='.($total_pages).'" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
                } else {
                # code...
                $pagination .= '<li class="page-item page"><a href="compte/opComtes.php?page='.($total_pages).'" data-page="'.$total_pages.'" class="page-link">'.$total_pages.'</a></li>';
                }
                }
            if ($current_page == $total_pages) {
            # code...
            $pagination .= '<li class="page-item disabled"><a data-page="'.$total_pages.'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
            } else {
            # code...
            $pagination .= '<li class="page-item"><a href="compte/opComtes.php?page='.($current_page + 1).'" data-page="'.($current_page + 1).'" class="page-link"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a></li>';
            }
            
            $pagination .= '</ul>'; 
        }
        return $pagination; //return pagination links
    }
?>