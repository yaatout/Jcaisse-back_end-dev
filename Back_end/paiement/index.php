<?php   session_start();   
        //require('../connection.php');
        require('../connectionPDO.php');

        require('../declarationVariables.php');

        if(!$_SESSION['iduserBack'])
	header('Location:../index.php');

?>
<!DOCTYPE html>
<html >
<head>
    <?php require 'paiementHead.php' ?>
</head>
<body>
    <?php  require('../header.php'); ?>
    <div class="container-fluid">
        <?php 
            define('DS', DIRECTORY_SEPARATOR);
            define('BASE_URL', $_SERVER['REQUEST_URI']);
            $url=trim(BASE_URL, '/');
            $params=explode('/', BASE_URL);
            // var_dump($url);
            // echo "<pre>"; 
            
            // print_r($params);
            // echo "</pre>";
                //die(); 

            if (isset($_GET['p'])) {
                $p=$_GET['p'];
            } else {
                $p="paiement";
            }

            if ($p==='ret') {
                //echo '111';
                include 'paiementRetard.php';
            }else if ($p==='hist') {
                //echo '222';
                include 'payementboutiquesHistorique.php';
            }else if ($p==='refT') {
                //echo '333';
                include 'referencesTransfert.php';
            }else if ($p==='param') {
                //echo '44';
                include 'paramettrePayement.php';
            }else { 
                //echo 'GGGG';
                include 'paiementBoutique.php';
            }  
                
            // if (isset($params[4]) and $params[4]!='') {

            //     $page=$params[4];
            // } else {
            //     $page='paiement';
            // }
            
        // if ($page==='retard') {
        //     //echo '111';
        //     include 'paiementRetard.php';
        // }else if ($page==='historique') {
        //     //echo '222';
        //     include 'paiementHistorique.php';
        // }else if ($page==='refT') {
        //     //echo '333';
        //     //include 'paiementRefTransfert.php';
        //     include 'paiementBoutique.php';
        // }else if ($page==='parametre') {
        //     //echo '44';
        //     include 'paiementParametre.php';
        // }else { 
        //     //echo 'GGGG';
        //     include 'paiementBoutique.php';
        // }  
        
        ?>
    </div>

   

    <script src="js/datatables.min.js"></script>
    <script> $(document).ready(function () { $("#exemple").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple1").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple2").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple3").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple4").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple5").DataTable();});</script>
   <script> $(document).ready(function () { $("#exemple6").DataTable();});</script>
   <script type="text/javascript" src="js/script.js"></script>
</body>
</html>