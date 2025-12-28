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
session_start();

if(!$_SESSION['iduser']){
	header('Location:../index.php');
}

require('connection.php');

require('declarationVariables.php');


require('entetehtml.php'); 
echo'
   <body >';
   require('header.php');

   
   $sql="SELECT * FROM `".$nomtableEntrepotTransfert."` WHERE etat2=0 and iduser=".$_SESSION['iduser']."";
   $res=mysql_query($sql);
   $transferts=mysql_fetch_array($res);


?>
    <script type="text/javascript">
            
        $(document).ready(function() {
            nbEntreeTransfert = $('#nbEntreeTransfert').val()
            // $(".loading-gif").show();


            $("#listeDesTransferts").load( "ajax/listerTransfert-EntrepotAjax.php",{"operation":1,"nbEntreeTransfert":nbEntreeTransfert,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-gif").hide(); //once done, hide loading element
                // $("#container_Result").show();

            });
            /*************** Début lister Dépenses ***************/
            //executes code below when user click on pagination links
            $("#listeDesTransferts").on( "click", ".pagination a", function (e){
                // $("#listeDesTransferts").on( "click", function (e){
                e.preventDefault();
                $(".loading-gif").show(); //show loading element
                page = $(this).attr("data-page"); //get page number from link
                nbEntreeTransfert = $('#nbEntreeTransfert').val()
                query = $('#searchInputTransfert').val();

                if (query.length == 0) {
                    $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"page":page,"operation":1,"nbEntreeTransfert":nbEntreeTransfert,"query":"","cb":""}, function(){ //get content from PHP page
                        $(".loading-gif").hide(); //once done, hide loading element
                    });
                        
                }else{
                    $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"page":page,"operation":1,"nbEntreeTransfert":nbEntreeTransfert,"query":query,"cb":""}, function(){ //get content from PHP page
                        $(".loading-gif").hide(); //once done, hide loading element
                    });
                }
                // $("#listeDesTransferts").load("ajax/listerTransfert-EntrepotAjax.php",{"page":page,"operation":1}
            });

            $('#searchInputTransfert').on("keyup", function(e) {
                e.preventDefault();
                
                query = $('#searchInputTransfert').val()
                nbEntreeTransfert = $('#nbEntreeTransfert').val()

                var keycode = (e.keyCode ? e.keyCode : e.which);
                if (keycode == '13') {
                    // alert(1111)
                    t = 1; // code barre
                    
                    if (query.length > 0) {
                        
                        $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"operation":3,"nbEntreeTransfert":nbEntreeTransfert,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"operation":3,"nbEntreeTransfert":nbEntreeTransfert,"query":"","cb":t}); //load initial records
                    }
                }else{
                    // alert(2222)
                    t = 0; // no code barre
                    setTimeout(() => {
                        if (query.length > 0) {
                            
                            $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"operation":3,"nbEntreeTransfert":nbEntreeTransfert,"query":query,"cb":t}); //load initial records
                        }else{
                            $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"operation":3,"nbEntreeTransfert":nbEntreeTransfert,"query":"","cb":t}); //load initial records
                        }
                    }, 100);
                }
            });

            $('#nbEntreeTransfert').on("change", function(e) {
                e.preventDefault();

                nbEntreeTransfert = $('#nbEntreeTransfert').val()
                query = $('#searchInputTransfert').val();

                if (query.length == 0) {
                    $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"operation":4,"nbEntreeTransfert":nbEntreeTransfert,"query":"","cb":""}); //load initial records
                        
                }else{
                    $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"operation":4,"nbEntreeTransfert":nbEntreeTransfert,"query":query,"cb":""}); //load initial records
                }
                    
                // $("#listeDesTransferts" ).load( "ajax/listerTransfert-EntrepotAjax.php",{"operation":4,"nbEntreeTransfert":nbEntreeTransfert}); //load initial records
            });
            /*************** Fin lister Dépenses ***************/
    
        });
    </script>

<?php          

if (count($transferts) <= 100) {
  # code...
  
  echo'<div class="container"><center> <button type="button" class="btn btn-success" data-toggle="modal" data-target="#AjoutStockModal" data-dismiss="modal" id="AjoutStock">
  <i class="glyphicon glyphicon-plus"></i>Ajouter un Transfert</button></center> ';
}

echo'<div class="modal fade" id="AjoutStockModal" role="dialog">';
echo'<div class="modal-dialog modal-lg">';
  echo'<div class="modal-content">';
    echo'<div class="modal-header" >';
    echo'<button type="button" class="close" data-dismiss="modal">&times;</button>';
    echo"<h4><span class='glyphicon glyphicon-lock'></span> Ajout de Transfert </h4>";
      echo'</div>';
        echo'<div class="modal-body">
            <div class="table-responsive">
            <table id="tableStock0" class="display" width="100%" border="1">
              <thead>
              <tr id="thStock">
                <th>Reference</th>
                <th>Categorie</th>
                <th>Quantite</th>
                <th>Unite Stock(US)</th>
                <th>Operation</th> 
              </tr>
              </thead>
            </table>
          
            <script type="text/javascript">
              $(document).ready(function() {
                $("#tableStock0").dataTable({
                "bProcessing": true,
                "sAjaxSource": "ajax/listeProduit-TransfertEntrepotAjax.php",
                "aoColumns": [
                    { mData: "0" } ,
                    { mData: "1" },
                    { mData: "2" },
                    { mData: "3" },
                    { mData: "4" },
                  ],
                  
                });  
              });
            </script>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>';

//if($_SESSION['enConfiguration'] ==0){
echo'<div class="container" align="center">
<br />
        <ul class="nav nav-tabs">
          <li class="active">
            <a data-toggle="tab" href="#STOCKPRODUITDETAIL">LISTE DES TRANSFERTS  
            </a>
          </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="STOCKPRODUITDETAIL">';
              echo'<div class="table-responsive">
                  
                <label class="pull-left" for="nbEntreeTransfert">Nombre entrées </label>
                <select class="pull-left" id="nbEntreeTransfert">
                <optgroup>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option> 
                    <option value="100">100</option> 
                </optgroup>       
                </select>
                <input class="pull-right" type="text" name="" id="searchInputTransfert" placeholder="Rechercher..." autocomplete="off">
                <img src="images/loading-gif3.gif" style="margin-left:25%;margin-top:8%" class="loading-gif" alt="GIF" srcset="">
                <div id="listeDesTransferts"><!-- content will be loaded here --></div>	

              </div>
            </div>
        </div>
      </div>';
    ?>
      <div id="modifierEntrepotTransfert" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modification transfert</h4>
              </div>
              <div class="modal-body" style="padding:40px 50px;">
                <form class="form" >
                  <div class="form-group">
                    <input type="hidden"  id="idEntrepotTransfert_Mdf"  />
                    <input type="hidden"  id="ordreEntrepotTransfert_Mdf"  />
                  </div>
                  <div class="form-group">
                    <label for="designation_Mdf">Reference </label>
                    <input disabled="true" type="text" class="inputbasic form-control" id="designation_Mdf" />
                  </div>
                  <div class="form-group">
                    <label for="uniteStock_Mdf">Unite stock</label>
                    <input disabled="true" type="text" class="inputbasic form-control" id="uniteStock_Mdf" />
                  </div>
                  <div class="form-group">
                    <label for="quantite_Mdf">Quantite</label>
                    <input type="text" class="inputbasic form-control" id="quantite_Mdf" />
                  </div>
                  <div class="modal-footer row">
                    <div class="col-sm-3 "> <input type="button" id="btn_mdf_Transfert" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                    <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
      </div>

      <div id="supprimerEntrepotTransfert" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Suppression transfert</h4>
              </div>
              <div class="modal-body" style="padding:40px 50px;">
                <form class="form" >
                  <div class="form-group">
                    <input type="hidden"  id="idEntrepotTransfert_Spm"  />
                    <input type="hidden"  id="ordreEntrepotTransfert_Spm"  />
                  </div>
                  <div class="form-group">
                    <label for="designation_Spm">Reference </label>
                    <input disabled="true" type="text" class="inputbasic form-control" id="designation_Spm" />
                  </div>
                  <div class="form-group">
                    <label for="uniteStock_Spm">Unite stock</label>
                    <input disabled="true" type="text" class="inputbasic form-control" id="uniteStock_Spm" />
                  </div>
                  <div class="form-group">
                    <label for="quantite_Spm">Quantite</label>
                    <input disabled="true" type="text" class="inputbasic form-control" id="quantite_Spm" />
                  </div>
                  <div class="modal-footer row">
                    <div class="col-sm-3 "> <input type="button" id="btn_spm_Transfert" class="btn_CodeDesign_P boutonbasic"  value=" Enregistrer >>" /></div>
                    <div class="col-sm-3 "> <input type="button" class="boutonbasic" data-dismiss="modal" name="annule" value="<<  ANNULER" /></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
      </div>
      
  <?php

echo'</body></html>';

    /* Debut PopUp d'Alerte sur l'ensemble de la Page **/
    if(isset($msg_info)) {
      echo"<script type='text/javascript'>
                  $(window).on('load',function(){
                      $('#msg_info').modal('show');
                  });
              </script>";
      echo'<div id="msg_info" class="modal fade " role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header panel-primary">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Alerte</h4>
                        </div>
                        <div class="modal-body">
                            <p>'.$msg_info.'</p>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                </div>
            </div>';
            
    }
    /** Fin PopUp d'Alerte sur l'ensemble de la Page **/


?>
