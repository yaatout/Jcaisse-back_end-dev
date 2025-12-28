<?php

    session_start();

    if(!$_SESSION['iduser']){
        header('Location:../index.php');
    }
    require('connection.php');

    require('declarationVariables.php');

    $date = new DateTime();
    $timezone = new DateTimeZone('Africa/Dakar');
    $date->setTimezone($timezone);
    $ids = array();
    $produits = array();

    if (isset($_GET['s']) && isset($_GET['e'])) {

        $debut = $_GET['s'];
        $fin = $_GET['e'];

        // var_dump($debut." **** ".$fin);
    }


    $sql="SELECT * FROM  `".$nomtableLigne."` l , `".$nomtablePagnet."` p 

        where p.idPagnet = l.idPagnet && l.classe = 0 && p.verrouiller=1 

        && (p.type=0 || p.type=30) && ((CONCAT(CONCAT(SUBSTR(p.datepagej,7, 10),'',SUBSTR(p.datepagej,3, 4))
        ,'',SUBSTR(p.datepagej,1, 2)) BETWEEN '".$debut."' AND '".$fin."') 
        OR (datepagej BETWEEN '".$debut."' AND '".$fin."')) ORDER BY l.designation";

    $res = mysql_query($sql) or die ("persoonel requête 2".mysql_error());
    
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            }

            tr:nth-child(even) {
            background-color: #dddddd;
            }

            h3{
                text-decoration:underline;
                background: lightgray;
            }
        </style>
    </head>
    <body>
        
        <div class="">
            <h3 align="center">Sorties du <?= $debut?> au <?= $fin?></h3>
        <table id="tableSortiesDetails" class="tableau3" width="100%" border="1">
            <thead>
                <tr>
                    <th>ORDRE</th>
                    <th>REFERENCE</th>
                    <th>PRIX VENTE</th>
                    <th>QUANTITE</th>
                    <th>UNITE</th>
                    <th>PRIX TOTAL</th>
                    <th>N°FACTURE</th>
                    <th>DATE</th>
                    <th>TYPE VENTE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i=1;
                    while ($line=mysql_fetch_array($res)) {
                    
                        // if (in_array($line['idDesignation'], $ids)) {
                                    
                        // }

                        // else {
                            
                            ?>
                                <tr>
                                    <th><?= $i?></th>
                                    <th><?= $line['designation']?></th>
                                    <th><?= $line['prixunitevente']?></th>
                                    <th><?= $line['quantite']?></th>
                                    <th><?= $line['unitevente']?></th>
                                    <th><?= $line['prixtotal']?></th>
                                    <th><?= $line['idPagnet']?></th>
                                    <th><?= $line['datepagej']?></th>
                                    <th><?= ($line['type']==0) ? 'Simple' : 'Bon' ; ?></th>
                                </tr>

                            <?php
                                            
                            
                            $ids[] = $line['idDesignation'];
                            $i++;
                        // }
                    }
                ?>
        
            </tbody>
        </table>
    </div>

    <?php

?>
</body>
</html>