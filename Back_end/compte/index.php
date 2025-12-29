<?php   session_start();   
        require('../connectionPDO.php');

        require('../declarationVariables.php');

        if(!$_SESSION['iduserBack'])
	header('Location:../index.php');

?>
<!DOCTYPE html>
<html >
<head>
    <?php require 'compteHead.php' ?>
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
                $p="compte";
            }

            if ($p==='ops') {
                //echo '111';
                include 'opComptes.php';
            }else if ($p==='detCmp') {
                if (isset($_GET['c'])) {
                    $c=$_GET['c'];
                    include 'compteDe.php';
                }
            }else{
                include 'compte.php';
            }
                
            
        
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