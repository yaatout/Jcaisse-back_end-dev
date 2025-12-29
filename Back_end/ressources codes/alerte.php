<section>
  <div class="container">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#SEUIL">ALERTE SEUIL</a></li>
      <li><a data-toggle="tab" href="#EXPIRATION">ALERTE EXPIRATION</a></li>
      <li><a data-toggle="tab" href="#EXPIRATION">ALERTE ECHEANCE</a></li>
      <?php //echo'<li><a data-toggle="tab" href="#FRAIS">FRAIS</a></li>
      ?>
    </ul>
    <div class="tab-content">
    

      <div id="SEUIL" class="tab-pane fade in active">
      <?php
          $sql='SELECT * from  `'.$nomtableTotalStock.'` order by quantiteEnStocke asc';
          $res=mysql_query($sql);
          if(mysql_num_rows($res)){ ?>
            <div class="table-responsive">
              <table id="exemple" class="display" border="1">
                <thead>
                  <tr><th>QUANTITE EN STOCK</th><th>DESIGNATION</th><th>DATE EXPIRATION</th><th>REAPPROVISIONNEMENT</th></tr>
                </thead>
                <tfoot>
                  <tr><th>QUANTITE EN STOCK</th><th>DESIGNATION</th><th>DATE EXPIRATION</th><th>REAPPROVISIONNEMENT</th></tr>
                </tfoot>
                <tbody>
                  <?php  while($tab=mysql_fetch_array($res)){
                       if ($tab["quantiteEnStocke"]<=10)
                        echo'<tr><td><table width="100%" border="0"><tr><td>'.$tab["quantiteEnStocke"].'</td><td align="right"><span class="material-icons" style="font-size:16px"></td></tr></table></td><td>'.$tab["designation"].'</td><td align="center"><a href="#">'.$tab["dateExpiration"].' </a></td><td align="center"><a href="#">Réapprovisionnement </a></td></tr>';
                    		else
                    		if (($tab["quantiteEnStocke"]>10) && ($tab["quantiteEnStocke"]<=300))
                        echo'<tr><td><table width="100%" border="0"><tr><td>'.$tab["quantiteEnStocke"].'</td><td align="right"><img src="images/alerts-jaune1.png"></td></tr></table></td><td>'.$tab["designation"].'</td><td align="center"><a href="#">'.$tab["dateExpiration"].' </a></td><td align="right"></td></tr>';
                    		else
                    		if ($tab["quantiteEnStocke"]>300)
                        echo'<tr><td>'.$tab["quantiteEnStocke"].'</td><td>'.$tab["designation"].'</td><td align="center"><a href="#">'.$tab["dateExpiration"].' </a></td><td align="right"></td></tr>';
                    		}
                    }else{
                          echo'<article><h3>Liste des Stocks généraux de Produits</h3><table class="tableau2" width="90%" align="center" border="1"><th>QUANTITE EN STOCK</th><th>DESIGNATION</th><th>DATE EXPIRATION</th>';
                          echo'<tr><td colspan="6">Liste des Stocks généraux de Produits de la date du '.$dateString.' est pour le moment vide</td></tr>';
                   } ?>
                </tbody>
              </table>
            </div>
      </div>
      <!-- -->
      <div id="EXPIRATION" class="tab-pane fade ">
      <?php
          $sql='SELECT designation,quantiteEnStocke, DATE_FORMAT(dateExpiration, \'%d-%m-%Y \') AS expirDate from  `'.$nomtableTotalStock.'` order by expirDate DESC';
         // $sql='SELECT * from  `'.$nomtableTotalStock.'` order by dateExpiration DESC';
          
          $date = new DateTime();
          //echo $date;
          //var_dump($date);
          $res=mysql_query($sql);
          if(mysql_num_rows($res)){ ?>
            <div class="table-responsive">
              <table class="tableau4" width="60%" align="left" border="1">
                <thead>
                  <th>DESIGNATION</th><th>QUANTITE EN STOCK</th><th>DATE EXPIRATION</th><th>REAPPROVISIONNEMENT</th>
                </thead>
                <tbody>
                  <?php  while($tab=mysql_fetch_array($res)){
                       if ($tab["quantiteEnStocke"]<=10)
                        echo'<tr class="inputbasic1"><td>'.$tab["designation"].'</td><td>'.$tab["quantiteEnStocke"].'</td><td align="center"><a href="#">'.$tab["expirDate"].' </a></td><td align="center"><a href="#">Réapprovisionnement </a></td></tr>';
                        else
                        if (($tab["quantiteEnStocke"]>10) && ($tab["quantiteEnStocke"]<=300))
                        echo'<tr class="inputbasic2"><td>'.$tab["designation"].'</td><td>'.$tab["quantiteEnStocke"].'</td><td align="center"><a href="#">'.$tab["expirDate"].' </a></td><td align="right"></td></tr>';
                        else
                        if ($tab["quantiteEnStocke"]>300)
                        echo'<tr class="inputbasic3"><td>'.$tab["designation"].'</td><td>'.$tab["quantiteEnStocke"].'</td><td align="center"><a href="#">'.$tab["expirDate"].' </a></td><td align="right"></td></tr>';
                        }
                    }else{
                          echo'<article><h3>Liste des Stocks généraux de Produits</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE EN STOCK</th><th>DATE EXPIRATION</th>';
                          echo'<tr><td colspan="6">Liste des Stocks généraux de Produits de la date du '.$dateString.' est pour le moment vide</td></tr>';
                   } ?>
                </tbody>
              </table>
            </div>
      </div>
      
      
      <div id="EXPIRATION" class="tab-pane fade ">
      <?php
          $sql='SELECT designation,quantiteEnStocke, DATE_FORMAT(dateExpiration, \'%d-%m-%Y \') AS expirDate from  `'.$nomtableTotalStock.'` order by expirDate DESC';
         // $sql='SELECT * from  `'.$nomtableTotalStock.'` order by dateExpiration DESC';
          
          $date = new DateTime();
          //echo $date;
          //var_dump($date);
          $res=mysql_query($sql);
          if(mysql_num_rows($res)){ ?>
            <div class="table-responsive">
              <table class="tableau4" width="60%" align="left" border="1">
                <thead>
                  <th>DESIGNATION</th><th>QUANTITE EN STOCK</th><th>DATE EXPIRATION</th><th>REAPPROVISIONNEMENT</th>
                </thead>
                <tbody>
                  <?php  while($tab=mysql_fetch_array($res)){
                       if ($tab["quantiteEnStocke"]<=10)
                        echo'<tr class="inputbasic1"><td>'.$tab["designation"].'</td><td>'.$tab["quantiteEnStocke"].'</td><td align="center"><a href="#">'.$tab["expirDate"].' </a></td><td align="center"><a href="#">Réapprovisionnement </a></td></tr>';
                        else
                        if (($tab["quantiteEnStocke"]>10) && ($tab["quantiteEnStocke"]<=300))
                        echo'<tr class="inputbasic2"><td>'.$tab["designation"].'</td><td>'.$tab["quantiteEnStocke"].'</td><td align="center"><a href="#">'.$tab["expirDate"].' </a></td><td align="right"></td></tr>';
                        else
                        if ($tab["quantiteEnStocke"]>300)
                        echo'<tr class="inputbasic3"><td>'.$tab["designation"].'</td><td>'.$tab["quantiteEnStocke"].'</td><td align="center"><a href="#">'.$tab["expirDate"].' </a></td><td align="right"></td></tr>';
                        }
                    }else{
                          echo'<article><h3>Liste des Stocks généraux de Produits</h3><table class="tableau2" width="90%" align="center" border="1"><th>DESIGNATION</th><th>QUANTITE EN STOCK</th><th>DATE EXPIRATION</th>';
                          echo'<tr><td colspan="6">Liste des Stocks généraux de Produits de la date du '.$dateString.' est pour le moment vide</td></tr>';
                   } ?>
                </tbody>
              </table>
            </div>
      </div>
      
    </div>
  </div>


</section>