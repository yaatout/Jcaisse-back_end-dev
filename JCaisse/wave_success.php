<?php


require('connectionPDO.php');

if (isset($_GET['idps'])) {
    # code...
/**Debut informations sur la date d'Aujourdhui **/

    $date = new DateTime();

    $timezone = new DateTimeZone('Africa/Dakar');

    $date->setTimezone($timezone);

    $annee =$date->format('Y');

    $mois =$date->format('m');

    $jour =$date->format('d');

    $heureString=$date->format('H:i:s');

    $dateString=$annee.'-'.$mois.'-'.$jour;

    $dateString2=$jour.'-'.$mois.'-'.$annee;

/**Fin informations sur la date d'Aujourdhui **/

    $idPS = $_GET['idps'];
    
    $sqlUpdateLigne = $bdd->prepare("UPDATE `aaa-payement-salaire` SET aPayementBoutique=:aPB, dateRefTransfert=:dRT, naturePayement=:nP, datePaiement=:dP, heurePaiement=:hP WHERE idPS=:idPS");
    $sqlUpdateLigne->execute(array(
    'aPB' => 1,
    'dP' => $dateString,
    'hP' => $heureString,
    'dRT' => $dateString,
    'nP' => 1,
    'idPS' => $idPS
    )) or die(print_r('Update ligne '.$sqlUpdateLigne->errorInfo()));
    $sqlUpdateLigne->closeCursor();
    
 }
    // echo '';
    // echo "<script>window.close();</script>";
?>
<body align="center" >
    <br>
    <br>
    <br>
    <h2>PAIEMENT REUSSI!</h2>
    <br>
    <img src="images/success.png" alt="dddddddd" srcset="" style="margin: 15px;"><br>
    <!-- <div>
        <button onclick="closeSuccess()" id="closeSuccess" style="background-color: white;border-style:none"><img src="images/imgOK.JPG" alt="dddddddd" srcset=""></button>
    </div>
    <script type="text/javascript">
        function closeSuccess() {
            // alert(555)
            console.log(this.parentElement);
            window.location.href="https://jcaisse.org/JCaisse/accueil.php";
        }
    </script> -->
</body>