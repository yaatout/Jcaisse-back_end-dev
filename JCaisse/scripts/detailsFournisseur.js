$(document).ready(function() {  

    id=$('#inpt_Fournisseur_id').val();
    dateDebut=$('#inpt_Fournisseur_dateDebut').val();
    dateFin=$('#inpt_Fournisseur_dateFin').val();

    $("#listerOperations").load("datatables/detailsFournisseur.php",{"nbre_Entree":"","query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
        if(status == "success"){
            $(".loading-gif").hide();  
            calcul_Montant_Fournisseur();
        }
    });
    
    $("#inpt_Search_ListerOperations").on("keyup", function(e) {

        e.preventDefault();
        query = $('#inpt_Search_ListerOperations').val();
        nbre_Entree = $('#slct_Nbre_ListerOperations').val() ;

        var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                if (query.length > 0) {
                    $("#listerOperations").load("datatables/detailsFournisseur.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                        if(status == "success"){
                                    
                        }
                    });
                }else{
                    $("#listerOperations").load("datatables/detailsFournisseur.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                        if(status == "success"){
                                    
                        }
                    });
                }
            }else{
                setTimeout(() => {
                    if (query.length > 0) {
                        $("#listerOperations").load("datatables/detailsFournisseur.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                            if(status == "success"){
                                        
                            }
                        });
                    }else{
                        $("#listerOperations").load("datatables/detailsFournisseur.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                            if(status == "success"){
                                        
                            }
                        });
                    }

                }, 100);
            }

    });

    $("#listerOperations").on( "change", "#slct_Nbre_ListerOperations", function (e){
        e.preventDefault();
        query = $('#inpt_Search_ListerOperations').val();
        nbre_Entree = $('#slct_Nbre_ListerOperations').val() ;

        if (query.length == 0) {
            $("#listerOperations").load("datatables/detailsFournisseur.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;
                }
            });
        }else{
            $("#listerOperations").load("datatables/detailsFournisseur.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;     
                }
            });
        }

    });

    $("#listerOperations").on( "click", ".pagination a", function (e){

        e.preventDefault();
        page = $(this).attr("data-page"); //get page number from link
        query = $('#inpt_Search_ListerOperations').val();
        nbre_Entree = $('#slct_Nbre_ListerOperations').val() ;

        if (query.length == 0) {
            $("#listerOperations").load("datatables/detailsFournisseur.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;        
                }
            });
        }else{
            $("#listerOperations").load("datatables/detailsFournisseur.php",{"page":page,"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerOperations').val(nbre_Entree) ;       
                }
            });
        }

    });

    /**Debut Ajouter Commande**/
        $(function(){
            ajouter_Commande= function ()  {
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'ajouter_Commande',
                        idFournisseur : id,
                        dateDebut: dateDebut,
                        dateFin: dateFin,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        form_Btn="<form class='form-inline noImpr' method='post' id='form_ajouter_Bon_Btn_"+details[0]+"'>"+
                        "<button tabindex='1' type='button'  onclick='supprimer_Bon("+details[0]+")' class='btn btn-danger pull-right' id='btn_retourner_Bon"+details[0]+"'  data-toggle='modal' data-target='#msg_ann_Commande"+details[0]+"' >"+
                           "<span class='glyphicon glyphicon-remove'></span>"+
                               "</button>"+
                               "<button type='button' onclick='terminer_Bon("+details[0]+")' data-idBon='"+details[0]+"' id='btn_terminer_Bon"+details[0]+"' class='btn btn-success btn_terminer_Bon"+details[0]+" pull-right' style='margin-right:2px;'>"+
                                   "<span class='glyphicon glyphicon-ok'></span>"+
                               "</button>"+
                           "</form>";
                       var bon ="<div style='margin-bottom:5px;' class='panel panel-primary' id='panelBon"+details[0]+"'  >"+
                       "<div class='panel-heading'>"+
                       "<h4 style='padding-bottom: 18px;' data-toggle='collapse' data-parent='#accordion' href='#"+details[0]+"'  class='panel-title expand'>"+
                           "<div class='right-arrow pull-right'>+</div>"+
                           "<a href='#'  onclick='detailsBon("+details[0]+")'>"+ 
                                "<span class='hidden-xs col-sm-2 col-md-2 col-lg-1'><span>BC</span></span>"+ 
                                "<span class='hidden-xs hidden-sm hidden-md col-lg-3'><span>DATE</span> : "+details[1]+" "+details[2]+" </span>"+ 
                                "<span class='col-xs-6 col-sm-3 col-md-3 col-lg-2'> <span>TOTAL 1</span> : <span id='spn_ajouter_Bon_Total1"+details[0]+"' >"+0+" </span></span>"+ 
                                "<span class='hidden-xs col-sm-3 col-md-3 col-lg-2'><span>TOTAL 2</span> : <span id='spn_ajouter_Bon_Total2"+details[0]+"' >"+0+" </span></span>"+  
                                "<span class='col-xs-5 col-sm-3 col-md-3 col-lg-1'> </span>"+ 
                                "<span class='col-xs-5 col-sm-3 col-md-3 col-lg-2'> N: <span id='spn_ajouter_Bon_Numero"+details[0]+"' > </span></span>"+ 
                            "</a>"+
                       "</h4>"+
                       "</div>"+
                       "<div id='"+details[0]+"'  class='panel-collapse collapse in'>"+
                       "<br>"+
                       "<div class='content-panel' style='margin-left:12px;margin-right:12px'>"+
                           "<div class='cacher_btn_Terminer col-lg-11 col-md-11' id='cacher_btn_Terminer"+details[0]+"'>"+
                               "<div class='pull-right' style='margin-bottom:10px;'>"+form_Btn+"</div>"+
                           "</div>"+
                           "<table class='table table-bordered' id='tableBon"+details[0]+"' >"+
                               "<h4 style='display:none' class='pull-right' id='afficher_btn_Ticket"+details[0]+"'>"+
                                    "<button style='margin-bottom:10px; margin-right:10px;' onclick='supprimer_Bon("+details[0]+")' class='btn btn-danger pull-right' >"+
                                        "<span class='glyphicon glyphicon-remove'></span> Supprimer"+
                                    "</button>"+
                                    "<a style='margin-bottom:10px; margin-right:10px;' class='btn btn-warning  pull-right' href='bonCommande.php?id="+details[0]+"' >"+ 
                                        "<span class='glyphicon glyphicon-folder-open'></span> Details"+ 
                                    "</a>"+
                               "</h4>"+
                               "<thead>"+
                                   "<tr>"+
                                   "<th style='display:none'><span>Numero</span></th>"+
                                   "<th><span>Description</span></th>"+
                                   "<th><span>Montant</span></th>"+
                                   "<th><span>Date</span></th>"+
                                   "<th><span>Pieces Jointes</span></th>"+
                                   "<th class='champ_modifier_Bon"+details[0]+"' style='display:none'></th>"+
                                   "</tr>"+
                               "</thead>"+
                               "<tbody>"+
                                   "<tr id='ligneBon"+details[0]+"'>" +
                                       "<td style='display:none'><input class='form-control champ_saisie_Ligne"+details[0]+"' id='inpt_Bon_Numero_"+details[0]+"'  onkeyup='modifier_Bon_Numero("+details[0]+")' value='0' style='width: 150px' type='text' step='5'></input></td>" +
                                       "<td><textarea  type='textarea' class='form-control champ_saisie_Ligne"+details[0]+"' id='txtarea_Bon_Description_"+details[0]+"'  onkeyup='modifier_Bon_Description("+details[0]+")'> </textarea></td>" +
                                       "<td><input class='form-control champ_saisie_Ligne"+details[0]+"' id='inpt_Bon_Montant_"+details[0]+"'  onkeyup='modifier_Bon_Montant("+details[0]+")' onchange='modifier_Bon_Montant("+details[0]+")' value='0' style='width: 150px' type='number' step='5'></input></td>" +
                                       "<td>"+details[1]+"</td>"+
                                       "<td id='champ_image"+details[0]+"'>"+
                                           "<img class='btn btn-xs' src='images/upload.png' align='middle' alt='apperçu' width='50' height='45' data-toggle='modal' data-target='#imageNvBon"+details[0]+"'  onclick='showImageBon("+details[0]+")' 	 />"+
                                       "</td>" +
                                       "<td class='champ_modifier_Bon"+details[0]+"' style='display:none' >"+
                                            "<button disabled='true' type='button' class='btn btn-warning pull-right' onclick='modifier_Bon("+details[0]+")' >"+
                                                "<span class='glyphicon glyphicon-pencil'></span>"+
                                            "</button>"+
                                       "</td>"+
                                   "</tr>"+
    /*                                "<tr style='display:none' id='nouveau_Commande"+details[0]+"'>"+
                                    "<form class='form-inline noImpr'  method='post' >"+
                                        "<td><textarea  type='textarea' id='txtarea_Commande_Description_"+details[0]+"' class='form-control champ_saisie_Ligne"+details[0]+"' name='paiement' placeholder='Description' >"+details[0]+"</textarea></td>"+
                                        "<td><input type='number' id='inpt_Commande_Montant_"+details[0]+"' class='form-control champ_saisie_Ligne'"+details[0]+"' value='"+details[0]+"' onkeyup='modifier_Montant("+details[0]+")' onchange='modifier_Montant("+details[0]+")' style='width: 150px' ></td>"+
                                        "<td><select id='slct_Commande_Compte_"+details[0]+"' onchange='modifier_Compte(this.value)' class='form-control champ_saisie_Ligne"+details[0]+"'  style='width: 150px' ></select></td>"+
                                        "<td><input type='text' id='inpt_Commande_Date_"+details[0]+"' value='.$dateCommande.'  class='form-control champ_saisie_Ligne"+details[0]+"'  name='dateCommande' placeholder='jj-mm-aaaa' required=''></td>"+
                                        "<td></td>"+
                                        "<td>"+
                                            "<input type='hidden' name='idCommande' value='"+details[0]+"' >"+
                                            "<button type='button' class='btn btn-success pull-right' onclick='valider_Commande("+details[0]+")'>"+
                                                "<span class='glyphicon glyphicon-ok'></span>"+
                                            "</button>"+
                                        "</td>"+
                                    "</form>"+
                                   "</tr>"+ */
                               "</tbody>"+
                           "</table>"+
                       "</div>"+
                       "</div>"+
                     "</div>";
                     $("#listerOperations #accordion").prepend(bon);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
    /**Fin Ajouter Commande**/

    /**Debut Ajouter Livraison**/
        $(function(){
            ajouter_Livraison= function ()  {
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'ajouter_Livraison',
                        idFournisseur : id,
                        dateDebut: dateDebut,
                        dateFin: dateFin,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        form_Btn="<form class='form-inline noImpr' method='post' id='form_ajouter_Bon_Btn_"+details[0]+"'>"+
                        "<button tabindex='1' type='button'  onclick='supprimer_Bon("+details[0]+")' class='btn btn-danger pull-right' id='btn_retourner_Bon"+details[0]+"'  data-toggle='modal' data-target='#msg_ann_Commande"+details[0]+"' >"+
                           "<span class='glyphicon glyphicon-remove'></span>"+
                               "</button>"+
                               "<button type='button' onclick='terminer_Bon("+details[0]+")' data-idBon='"+details[0]+"' id='btn_terminer_Bon"+details[0]+"' class='btn btn-success btn_terminer_Bon"+details[0]+" pull-right' style='margin-right:2px;'>"+
                                   "<span class='glyphicon glyphicon-ok'></span>"+
                               "</button>"+
                           "</form>";
                       var bon ="<div style='margin-bottom:5px;' class='panel panel-success' id='panelBon"+details[0]+"'  >"+
                       "<div class='panel-heading'>"+
                       "<h4 style='padding-bottom: 18px;' data-toggle='collapse' data-parent='#accordion' href='#"+details[0]+"'  class='panel-title expand'>"+
                           "<div class='right-arrow pull-right'>+</div>"+
                            "<a href='#'  onclick='detailsBon("+details[0]+")'>"+ 
                                "<span class='hidden-xs col-sm-2 col-md-2 col-lg-1'><span>BL</span></span>"+ 
                                "<span class='hidden-xs hidden-sm hidden-md col-lg-3'><span>DATE</span> : "+details[1]+" "+details[2]+" </span>"+ 
                                "<span class='col-xs-6 col-sm-3 col-md-3 col-lg-2'> <span>TOTAL 1</span> : <span id='spn_ajouter_Bon_Total1"+details[0]+"' >"+0+" </span></span>"+ 
                                "<span class='hidden-xs col-sm-3 col-md-3 col-lg-2'><span>TOTAL 2</span> : <span id='spn_ajouter_Bon_Total2"+details[0]+"' >"+0+" </span></span>"+  
                                "<span class='col-xs-5 col-sm-3 col-md-3 col-lg-1'> </span>"+ 
                                "<span class='col-xs-5 col-sm-3 col-md-3 col-lg-2'> N: <span id='spn_ajouter_Bon_Numero"+details[0]+"' > </span></span>"+ 
                            "</a>"+
                       "</h4>"+
                       "</div>"+
                       "<div id='"+details[0]+"'  class='panel-collapse collapse in'>"+
                       "<br>"+
                       "<div class='content-panel' style='margin-left:12px;margin-right:12px'>"+
                           "<div class='cacher_btn_Terminer col-lg-11 col-md-11' id='cacher_btn_Terminer"+details[0]+"'>"+
                               "<div class='pull-right' style='margin-bottom:10px;'>"+form_Btn+"</div>"+
                           "</div>"+
                           "<table class='table table-bordered' id='tableBon"+details[0]+"' >"+
                               "<h4 style='display:none' class='pull-right' id='afficher_btn_Ticket"+details[0]+"'>"+
                                    "<button style='margin-bottom:10px; margin-right:10px;' onclick='supprimer_Bon("+details[0]+")' class='btn btn-danger pull-right' >"+
                                        "<span class='glyphicon glyphicon-remove'></span> Supprimer"+
                                    "</button>"+
                                    "<a style='margin-bottom:10px; margin-right:10px;' class='btn btn-warning  pull-right' href='bonLivraison.php?id="+details[0]+"' >"+ 
                                        "<span class='glyphicon glyphicon-folder-open'></span> Details"+ 
                                    "</a>"+
                               "</h4>"+  
                               "<thead>"+
                                   "<tr>"+
                                   "<th><span>Numero</span></th>"+
                                   "<th><span>Description</span></th>"+
                                   "<th><span>Montant</span></th>"+
                                   "<th><span>Date</span></th>"+
                                   "<th><span>Pieces Jointes</span></th>"+
                                   "<th class='champ_modifier_Bon"+details[0]+"' style='display:none'></th>"+
                                   "</tr>"+
                               "</thead>"+
                               "<tbody>"+
                                   "<tr id='ligneBon"+details[0]+"'>" +
                                       "<td><input class='form-control champ_saisie_Ligne"+details[0]+"' id='inpt_Bon_Numero_"+details[0]+"'  onkeyup='modifier_Bon_Numero("+details[0]+")' value='0' style='width: 150px' type='text' step='5'></input></td>" +
                                       "<td><textarea  type='textarea' class='form-control champ_saisie_Ligne"+details[0]+"' id='txtarea_Bon_Description_"+details[0]+"'  onkeyup='modifier_Bon_Description("+details[0]+")'> </textarea></td>" +
                                       "<td><input class='form-control champ_saisie_Ligne"+details[0]+"' id='inpt_Bon_Montant_"+details[0]+"'  onkeyup='modifier_Bon_Montant("+details[0]+")' onchange='modifier_Bon_Montant("+details[0]+")' value='0' style='width: 150px' type='number' step='5'></input></td>" +
                                       "<td>"+details[1]+"</td>"+
                                       "<td id='champ_image"+details[0]+"'>"+
                                           "<img class='btn btn-xs' src='images/upload.png' align='middle' alt='apperçu' width='50' height='45' data-toggle='modal' data-target='#imageNvBon"+details[0]+"'  onclick='showImageBon("+details[0]+")' 	 />"+
                                       "</td>" +
                                       "<td class='champ_modifier_Bon"+details[0]+"' style='display:none' >"+
                                            "<button disabled='true'  type='button' class='btn btn-warning pull-right' onclick='modifier_Bon("+details[0]+")' >"+
                                                "<span class='glyphicon glyphicon-pencil'></span>"+
                                            "</button>"+
                                       "</td>"+
                                   "</tr>"+
    /*                                "<tr style='display:none' id='nouveau_Commande"+details[0]+"'>"+
                                    "<form class='form-inline noImpr'  method='post' >"+
                                        "<td><textarea  type='textarea' id='txtarea_Commande_Description_"+details[0]+"' class='form-control champ_saisie_Ligne"+details[0]+"' name='paiement' placeholder='Description' >"+details[0]+"</textarea></td>"+
                                        "<td><input type='number' id='inpt_Commande_Montant_"+details[0]+"' class='form-control champ_saisie_Ligne'"+details[0]+"' value='"+details[0]+"' onkeyup='modifier_Montant("+details[0]+")' onchange='modifier_Montant("+details[0]+")' style='width: 150px' ></td>"+
                                        "<td><select id='slct_Commande_Compte_"+details[0]+"' onchange='modifier_Compte(this.value)' class='form-control champ_saisie_Ligne"+details[0]+"'  style='width: 150px' ></select></td>"+
                                        "<td><input type='text' id='inpt_Commande_Date_"+details[0]+"' value='.$dateCommande.'  class='form-control champ_saisie_Ligne"+details[0]+"'  name='dateCommande' placeholder='jj-mm-aaaa' required=''></td>"+
                                        "<td></td>"+
                                        "<td>"+
                                            "<input type='hidden' name='idCommande' value='"+details[0]+"' >"+
                                            "<button type='button' class='btn btn-success pull-right' onclick='valider_Commande("+details[0]+")'>"+
                                                "<span class='glyphicon glyphicon-ok'></span>"+
                                            "</button>"+
                                        "</td>"+
                                    "</form>"+
                                   "</tr>"+ */
                               "</tbody>"+
                           "</table>"+
                       "</div>"+
                       "</div>"+
                     "</div>";
                     $("#listerOperations #accordion").prepend(bon);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
    /**Fin Ajouter Livraison**/

    /**Debut Modifier Montant Bon Livraison et Commande**/
        $(function(){
            modifier_Bon_Numero = function (idBon)  {
                var numero=$('#inpt_Bon_Numero_'+idBon).val();
                $('#spn_ajouter_Bon_Numero'+idBon).text(numero); 
            }
        });
    /**Fin Modifier Montant Bon Livraison et Commande**/

    /**Debut Modifier Montant Bon Livraison et Commande**/
        $(function(){
            modifier_Bon_Montant = function (idBon)  {
                var montant=$('#inpt_Bon_Montant_'+idBon).val();
                $('#spn_ajouter_Bon_Total1'+idBon).text(montant); 
            }
        });
    /**Fin Modifier Montant Bon Livraison et Commande**/

    /**Debut Terminer Bon Livraison et Commande**/
        $(function(){
            terminer_Bon= function(idBon) { 
                var numero=$('#inpt_Bon_Numero_'+idBon).val();
                var description=$('#txtarea_Bon_Description_'+idBon).val();
                var montant=$('#inpt_Bon_Montant_'+idBon).val();
                $("#btn_terminer_Bon"+idBon).prop("disabled", true);
                $('#cacher_btn_Terminer'+idBon).remove();
                $('#afficher_btn_Ticket'+idBon).show();
                $('.champ_modifier_Bon'+idBon).show();
                $('.champ_saisie_Ligne'+idBon).each(function() {
                    $(this).replaceWith($("<span />").text($(this).val().split('<>')[0]));
                }); 
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'terminer_Bon',
                        idBon: idBon,
                        idFournisseur : id,
                        dateDebut: dateDebut,
                        dateFin: dateFin,
                        numero: numero,
                        description: description,
                        montant: montant,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        montant_bon=details[1];
                        var total=montant_bon.split('<>')[0];
                        var bons=montant_bon.split('<>')[1];
                        var versements=montant_bon.split('<>')[2];
                        $('#spn_montant_Total').text(total);
                        $('#spn_montant_Bons').text(bons);
                        $('#spn_montant_Versements').text(versements);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
    /**Fin Terminer Bon Livraison et Commande**/

    /**Debut Modifier Bon Livraison et Commande**/
        $(function(){
            modifier_Bon= function (idBon)  {
                $("#ancien_Bon"+idBon).hide();
                $("#fournisseur"+idBon).show();
                $("#nouveau_Bon"+idBon).show();
            }
        });
        $(function(){
            btn_modifier_Bon= function (idBon)  {
                newFournisseur=$('#slct_Bon_Fournisseur'+idBon).val();
                numero=$('#inpt_Bon_Numero_'+idBon).val();
                description=$('#txtarea_Bon_Description_'+idBon).val();
                montant=$('#inpt_Bon_Montant_'+idBon).val();
                dateBon=$('#inpt_Bon_Date_'+idBon).val();
                dateBon=format_date(dateBon);
                dateEcheance=$('#inpt_Bon_DateEcheance_'+idBon).val();
                dateEcheance=format_date(dateEcheance);
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'modifier_Bon',
                        idBon : idBon,
                        idFournisseur : id,
                        dateDebut: dateDebut,
                        dateFin: dateFin,
                        numero : numero,
                        montant : montant,
                        dateBon : dateBon,
                        dateEcheance : dateEcheance,
                        description : description
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('.champ_saisie_Ligne'+idBon).each(function() {
                            $(this).replaceWith($("<span />").text($(this).val().split('<>')[0]));
                        }); 
                        montant_bon=details[1];
                        var total=montant_bon.split('<>')[0];
                        var bons=montant_bon.split('<>')[1];
                        var versements=montant_bon.split('<>')[2];
                        $('#spn_montant_Total').text(total);
                        $('#spn_montant_Bons').text(bons);
                        $('#spn_montant_Versements').text(versements);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
    /**Fin Modifier Bon Livraison et Commande**/

    /**Debut Transferer Bon**/
        $(function(){
            transferer_Bon= function (idBon)  {
              $('#inpt_trsf_Bon_idBl').val('');
              $('#inpt_trsf_Bon_Numero').val('');
              $('#inpt_trsf_Bon_Montant').val('');
              $('#inpt_trsf_Bon_Date').val('');
              $('#slct_trsf_Bon_Fournisseur option').remove();
              $.ajax({
                url:"calculs/detailsFournisseur.php",
                method:"POST",
                data:{
                    operation:'details_Bon',
                    idBon : idBon,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                  $('#inpt_trsf_Bon_idBl').val(idBon);
                  $('#inpt_trsf_Bon_Numero').val(details[1]);
                  $('#inpt_trsf_Bon_Montant').val(details[3]);
                  $('#inpt_trsf_Bon_Date').val(details[2]);
                    
                  var choix = details[5];
                  var taille = choix.length;
                  $('#slct_trsf_Bon_Fournisseur option').remove();
      
                  $('#slct_trsf_Bon_Fournisseur').append("<option selected >--------------</option>");
      
                  for(var i = 0; i<taille; i++){
                          $('#slct_trsf_Bon_Fournisseur').append("<option value='"+choix[i][0]+"'>"+choix[i][1]+"</option>");
                  }
    
                  $('#transferer_Bon').modal('show');
                },
                error: function() {
                  $("#message_Produit_titre").text('Erreur');
                  $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                  $('#message_Produit').modal('show');
               },
                dataType:"text"
              }); 
            }
        });
        $("#btn_transferer_Bon").click( function(){
            fournisseur=$('#slct_trsf_Bon_Fournisseur').val();
            idBon=$('#inpt_trsf_Bon_idBl').val();
            $.ajax({
                url:"calculs/detailsFournisseur.php",
                method:"POST",
                data:{
                    operation:'transferer_Bon',
                    idBon : idBon,
                    idFournisseur : id,
                    dateDebut: dateDebut,
                    dateFin: dateFin,
                    fournisseur : fournisseur
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#transferer_Bon').modal('hide');
                    $('#panelBon'+idBon).each(function() {
                        $(this).remove();
                    });
                    montant_bon=details[1];
                    var total=montant_bon.split('<>')[0];
                    var bons=montant_bon.split('<>')[1];
                    var versements=montant_bon.split('<>')[2];
                    $('#spn_montant_Total').text(total);
                    $('#spn_montant_Bons').text(bons);
                    $('#spn_montant_Versements').text(versements);
                },
                error: function() {
                    $("#message_Produit_titre").text('Erreur');
                    $("#message_Produit .modal-body").text('Probleme de connexion, reactualiser la page et si cela persiste verifier votre appareil');
                    $('#message_Produit').modal('show');
                },
                dataType:"text"
            }); 
        });
    /**Fin Transferer Bon**/

    /**Debut Supprimer Bon Livraison et Commande**/
        $(function(){
            supprimer_Bon= function (idBon)  {
                $('#inpt_spm_Bon_idBon').val('');
                $('#span_spm_Bon_Numero').text('');
                $('#span_spm_Bon_Date').text('');
                $('#span_spm_Bon_Montant').text('');
                $('#span_spm_Bon_Description').text('');
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'details_Bon',
                        idBon : idBon,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_spm_Bon_idBon').val(details[0]);
                        $('#span_spm_Bon_Numero').text(details[1]);
                        $('#span_spm_Bon_Date').text(details[2]);
                        $('#span_spm_Bon_Montant').text(details[3]);
                        $('#span_spm_Bon_Description').text(details[4]);
                        $('#supprimer_Bon').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            }
        });
        $("#btn_supprimer_Bon").click( function(){
            idBon=$('#inpt_spm_Bon_idBon').val();
            $.ajax({
                url:"calculs/detailsFournisseur.php",
                method:"POST",
                data:{
                    operation:'supprimer_Bon',
                    idBon : idBon,
                    idFournisseur : id,
                    dateDebut: dateDebut,
                    dateFin: dateFin,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#supprimer_Bon').modal('hide');
                    $('#panelBon'+idBon).each(function() {
                        $(this).remove();
                    });
                    montant_bon=details[1];
                    var total=montant_bon.split('<>')[0];
                    var bons=montant_bon.split('<>')[1];
                    var versements=montant_bon.split('<>')[2];
                    $('#spn_montant_Total').text(total);
                    $('#spn_montant_Bons').text(bons);
                    $('#spn_montant_Versements').text(versements);
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Supprimer Bon Livraison et Commande**/

    /**Debut Ajouter Versement**/
        $(function(){
            ajouter_Versement= function ()  {
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'ajouter_Versement',
                        idFournisseur : id,
                        dateDebut: dateDebut,
                        dateFin: dateFin,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        form_Btn="<form class='form-inline noImpr' method='post' id='form_ajouter_Versement_Btn_"+details[0]+"'>"+
                        "<button tabindex='1' type='button'  onclick='supprimer_Versement("+details[0]+")' class='btn btn-danger pull-right' id='btn_supprimer_Versement"+details[0]+"'  data-toggle='modal' data-target='#msg_ann_versement"+details[0]+"' >"+
                           "<span class='glyphicon glyphicon-remove'></span>"+
                               "</button>"+
                               "<button type='button' onclick='terminer_Versement("+details[0]+")' data-idVersement='"+details[0]+"' id='btn_terminer_Versement"+details[0]+"' class='btn btn-success btn_terminer_Versement"+details[0]+" pull-right' style='margin-right:2px;'>"+
                                   "<span class='glyphicon glyphicon-ok'></span>"+
                               "</button>"+
                           "</form>";
                       var versement ="<div style='margin-bottom:5px;' class='panel panel-warning' id='panelVersement"+details[0]+"' versement-compte='0' >"+
                       "<div class='panel-heading'>"+
                       "<h4 style='padding-bottom: 18px;' data-toggle='collapse' data-parent='#accordion' href='#"+details[0]+"'  class='panel-title expand'>"+
                           "<div class='right-arrow pull-right'>+</div>"+
                           "<a href='#'  onclick='detailsVersement("+details[0]+")'>"+ 
                           "<span class='hidden-xs hidden-sm col-md-2 col-lg-1'><span>VE</span></span>"+
                           "<span class='hidden-xs col-sm-4 col-md-3 col-lg-3'><span>DATE</span> : "+details[1]+" "+details[2]+"</span>"+
                           "<span class='hidden-xs hidden-sm hidden-md col-lg-2'><span>TOTAL</span> : <span id='spn_ajouter_Versement_Total"+details[0]+"' >"+0+" </span></span>"+
                           "<span class='col-xs-6 col-sm-3 col-md-3 col-lg-2'> <span>NET HT</span> : <span id='spn_ajouter_Versement_APayer"+details[0]+"'>"+0+" </span></span>"+
                           "</a>"+
                       "</h4>"+
                       "</div>"+
                       "<div id='"+details[0]+"'  class='panel-collapse collapse in'>"+
                       "<br>"+
                       "<div class='content-panel' style='margin-left:12px;margin-right:12px'>"+
                           "<div class='cacher_btn_Terminer col-lg-11 col-md-11' id='cacher_btn_Terminer"+details[0]+"'>"+
                               "<div class='pull-right' style='margin-bottom:10px;'>"+form_Btn+"</div>"+
                           "</div>"+
                           "<table class='table table-bordered' id='tableVersement"+details[0]+"' >"+
                               "<h4 style='display:none' class='pull-right' id='afficher_btn_Ticket"+details[0]+"'>"+
                                   "<button type='button' style='margin-right:5px' class='btn btn-round btn-warning' onclick='ticket_Versement("+details[0]+")'> <span>Ticket</span></button>"+
                                   "<button type='button' style='margin-right:5px' class='btn btn-round btn-info' onclick='facture_Versement("+details[0]+")'> <span>Facture</span></button>"+
                                   "<button type='button' style='margin-right:5px' onclick='supprimer_Versement("+details[0]+")' class='btn btn-round btn-danger' id='btn_supprimer_Versement"+details[0]+"' style='margin-right: 5px;' data-toggle='modal' data-target=''> <span>Retourner</span></button>"+
                               "</h4>"+
                               "<thead>"+
                                   "<tr>"+
                                   "<th><span>Description</span></th>"+
                                   "<th><span>Montant</span></th>"+
                                   "<th><span>Compte</span></th>"+
                                   "<th><span>Date</span></th>"+
                                   "<th><span>Pieces Jointes</span></th>"+
                                   "<th class='champ_modifier_Versement"+details[0]+"' style='display:none'></th>"+
                                   "</tr>"+
                               "</thead>"+
                               "<tbody>"+
                                   "<tr id='ligneVersement"+details[0]+"'>" +
                                       "<td><textarea  type='textarea' class='form-control champ_saisie_Ligne"+details[0]+"' id='txtarea_Versement_Description_"+details[0]+"'  onkeyup='modifier_Versement_Description("+details[0]+")'> </textarea></td>" +
                                       "<td><input class='form-control champ_saisie_Ligne"+details[0]+"' id='inpt_Versement_Montant_"+details[0]+"'  onkeyup='modifier_Versement_Montant("+details[0]+")' onchange='modifier_Versement_Montant("+details[0]+")' value='0' style='width: 150px' type='number' step='5'></input></td>" +
                                       "<td>"+
                                           "<select class='form-control champ_saisie_Ligne"+details[0]+"' id='slct_Versement_Compte_"+details[0]+"' onchange='modifier_Compte(this.value)' style='width: 150px' ></select>"+
                                       "</td>" +
                                       "<td>"+details[1]+"</td>"+
                                       "<td id='champ_image"+details[0]+"'>"+
                                           "<img class='btn btn-xs' src='images/upload.png' align='middle' alt='apperçu' width='50' height='45' data-toggle='modal' data-target='#imageNvVersement"+details[0]+"'  onclick='showImageVersement("+details[0]+")' 	 />"+
                                       "</td>" +
                                       "<td class='champ_modifier_Versement"+details[0]+"' style='display:none' >"+
                                            "<button disabled='true' type='button' class='btn btn-warning pull-right' onclick='modifier_Versement("+details[0]+")' >"+
                                                "<span class='glyphicon glyphicon-pencil'></span>"+
                                            "</button>"+
                                       "</td>"+
                                   "</tr>"+
    /*                                "<tr style='display:none' id='nouveau_Versement"+details[0]+"'>"+
                                    "<form class='form-inline noImpr'  method='post' >"+
                                        "<td><textarea  type='textarea' id='txtarea_Versement_Description_"+details[0]+"' class='form-control champ_saisie_Ligne"+details[0]+"' name='paiement' placeholder='Description' >"+details[0]+"</textarea></td>"+
                                        "<td><input type='number' id='inpt_Versement_Montant_"+details[0]+"' class='form-control champ_saisie_Ligne'"+details[0]+"' value='"+details[0]+"' onkeyup='modifier_Montant("+details[0]+")' onchange='modifier_Montant("+details[0]+")' style='width: 150px' ></td>"+
                                        "<td><select id='slct_Versement_Compte_"+details[0]+"' onchange='modifier_Compte(this.value)' class='form-control champ_saisie_Ligne"+details[0]+"'  style='width: 150px' ></select></td>"+
                                        "<td><input type='text' id='inpt_Versement_Date_"+details[0]+"' value='.$dateVersement.'  class='form-control champ_saisie_Ligne"+details[0]+"'  name='dateVersement' placeholder='jj-mm-aaaa' required=''></td>"+
                                        "<td></td>"+
                                        "<td>"+
                                            "<input type='hidden' name='idVersement' value='"+details[0]+"' >"+
                                            "<button type='button' class='btn btn-success pull-right' onclick='terminer_Versement("+details[0]+")'>"+
                                                "<span class='glyphicon glyphicon-ok'></span>"+
                                            "</button>"+
                                        "</td>"+
                                    "</form>"+
                                   "</tr>"+ */
                               "</tbody>"+
                           "</table>"+
                       "</div>"+
                       "</div>"+
                     "</div>";
                     $("#listerOperations #accordion").prepend(versement);
                     $('#slct_Versement_Compte_'+details[0]+' option').remove();
                     var taille = details[5].length;
                     for(var i = 0; i<taille; i++){
                        $('#slct_Versement_Compte_'+details[0]).append("<option value='"+details[5][i][0]+"'>"+details[5][i][1]+"</option>");
                     }
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
    /**Fin Ajouter Versement**/

    /**Debut Modifier Montant Versement**/
        $(function(){
            modifier_Versement_Montant= function (idVersement)  {
                var montant=$('#inpt_Versement_Montant_'+idVersement).val();
                $('#spn_ajouter_Versement_Total'+idVersement).text(montant); 
                $('#spn_ajouter_Versement_APayer'+idVersement).text(montant); 
            }
        });
    /**Fin Modifier Montant Versement**/

    /**Debut Terminer Versement**/
        $(function(){
            terminer_Versement= function(idVersement)  {
                var description=$('#txtarea_Versement_Description_'+idVersement).val();
                var montant=$('#inpt_Versement_Montant_'+idVersement).val();
                var idCompte=$('#slct_Versement_Compte_'+idVersement+' option:selected').val();
                $("#btn_terminer_Versement"+idVersement).prop("disabled", true); 
                $('#cacher_btn_Terminer'+idVersement).remove();
                $('#afficher_btn_Ticket'+idVersement).show();
                $('.champ_modifier_Versement'+idVersement).show();
                var nbre=1;
                $('.champ_saisie_Ligne'+idVersement).each(function() {
                    if(nbre==3){
                        compte=$('#slct_Versement_Compte_'+idVersement+' option:selected').text();
                        $(this).replaceWith($("<span />").text(compte));
                    }
                    else{
                        $(this).replaceWith($("<span />").text($(this).val().split('<>')[0]));
                    }
                    nbre++;
                }); 
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'terminer_Versement',
                        idVersement: idVersement,
                        idFournisseur : id,
                        dateDebut: dateDebut,
                        dateFin: dateFin,
                        description: description,
                        montant: montant,
                        idCompte: idCompte,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#panelVersement'+idVersement).attr("versement-compte", idCompte);
                        montant_client=details[1];
                        var total=montant_client.split('<>')[0];
                        var bons=montant_client.split('<>')[1];
                        var versements=montant_client.split('<>')[2];
                        $('#spn_montant_Total').text(total);
                        $('#spn_montant_Bons').text(bons);
                        $('#spn_montant_Versements').text(versements);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
    /**Fin Terminer Versement**/

    /**Debut Modifier Versement**/
        $(function(){
            modifier_Versement= function (idVersement)  {
                $("#ancien_Versement"+idVersement).hide();
                $("#nouveau_Versement"+idVersement).show();
            }
        });
        $(function(){
            btn_modifier_Versement= function (idVersement)  {
                var description=$('#txtarea_Versement_Description_'+idVersement).val();
                var montant=$('#inpt_Versement_Montant_'+idVersement).val();
                var idCompte=$('#slct_Versement_Compte_'+idVersement+' option:selected').val();
                var dateVersement=$('#inpt_Versement_Date_'+idVersement).val();
                dateVersement=format_date(dateVersement);
                $("#btn_modifier_Versement"+idVersement).prop("disabled", true); 
                var nbre=1;
                $('.champ_saisie_Ligne'+idVersement).each(function() {
                    if(nbre==3){
                        compte=$('#slct_Versement_Compte_'+idVersement+' option:selected').text();
                        $(this).replaceWith($("<span />").text(compte));
                    }
                    else{
                        $(this).replaceWith($("<span />").text($(this).val().split('<>')[0]));
                    }
                    nbre++;
                });
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'modifier_Versement',
                        idVersement : idVersement,
                        idFournisseur : id,
                        dateDebut: dateDebut,
                        dateFin: dateFin,
                        description : description,
                        montant : montant,
                        dateVersement : dateVersement,
                        idCompte: idCompte
                    },
                    success: function (data) { 
                        var details = JSON.parse(data);
                        montant_client=details[1];
                        var total=montant_client.split('<>')[0];
                        var bons=montant_client.split('<>')[1];
                        var versements=montant_client.split('<>')[2];
                        $('#spn_montant_Total').text(total);
                        $('#spn_montant_Bons').text(bons);
                        $('#spn_montant_Versements').text(versements);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
        });
    /**Fin Modifier Versement**/

    /**Debut Supprimer Versement**/
        $(function(){
            supprimer_Versement= function (idVersement)  {
                $('#inpt_spm_Versement_idVersement').val('');
                $('#span_spm_Versement_Numero').text('');
                $('#span_spm_Versement_Date').text('');
                $('#span_spm_Versement_Montant').text('');
                $('#span_spm_Versement_Description').text('');
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'details_Versement',
                        idVersement : idVersement,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        $('#inpt_spm_Versement_idVersement').val(details[0]);
                        $('#span_spm_Versement_Numero').text('#'+details[0]);
                        $('#span_spm_Versement_Date').text(details[1]);
                        $('#span_spm_Versement_Montant').text(details[2]);
                        $('#span_spm_Versement_Description').text(details[3]);
                        $('#supprimer_Versement').modal('show');
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            }
        });
        $("#btn_supprimer_Versement").click( function(){
            idVersement=$('#inpt_spm_Versement_idVersement').val();
            $.ajax({
                url:"calculs/detailsFournisseur.php",
                method:"POST",
                data:{
                    operation:'supprimer_Versement',
                    idVersement : idVersement,
                    idFournisseur : id,
                    dateDebut: dateDebut,
                    dateFin: dateFin,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    $('#supprimer_Versement').modal('hide');
                    $('#panelVersement'+idVersement).each(function() {
                        $(this).remove();
                    });
                    montant_client=details[1];
                    var total=montant_client.split('<>')[0];
                    var bons=montant_client.split('<>')[1];
                    var versements=montant_client.split('<>')[2];
                    $('#spn_montant_Total').text(total);
                    $('#spn_montant_Bons').text(bons);
                    $('#spn_montant_Versements').text(versements);
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        });
    /**Fin Supprimer Versement**/

    /**Debut Modifier Format Date Echeance**/
        $(function(){
            modifier_format_DateEcheance= function (idBon)  {
                var jour=$('#inpt_Bon_DateEcheance_'+idBon).val();
            }
        });
    /**Fin Modifier Format Date Echeance**/

    /**Debut Modifier Format Date Bon Livraison et Commande**/
        $(function(){
            modifier_format_DateBon= function (idBon)  {
                var jour=$('#inpt_Bon_Date_'+idBon).val();

            }
        });
    /**Fin Modifier Format Date Bon Livraison et Commande**/

    /**Debut Modifier Format Date Versement**/
        $(function(){
            modifier_format_DateVersement= function (idVersement)  {
                var jour=$('#inpt_Versement_Date_'+idVersement).val();

            }
        });
    /**Fin Modifier Format Date Versement**/


    $(function(){
        details_Livraison= function (idBon)  {
            details=$("#listeLivraison"+idBon).find('tr').length;
            if(details==1){
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'details_Livraison',
                        idBon : idBon,
                    },
                    success: function (data) {
                        var lignes = JSON.parse(data);
                        lignes_exist=$("#listeLivraison"+idBon).find('tr').length;
                        for(var l = 1; l<lignes_exist; l++){
                            $("#listeLivraison"+idBon).deleteRow(l);
                        }

                        if(lignes[0][0]==idBon){
                            var taille = lignes.length;
                            for(var i = 0; i<taille; i++){
                                var ligne = "<tr>"+
                                    "<td>"+lignes[i][1]+"</td>"+
                                    "<td>"+lignes[i][2]+"</td>"+
                                    "<td>"+lignes[i][3]+"</td>"+
                                    "<td>"+lignes[i][4]+"</td>"+
                                    "<td>"+lignes[i][5]+"</td>"+
                                    "<td>"+lignes[i][6]+"</td>"+
                                    "<td>"+lignes[i][8]+"</td>"+
                                    "<td>"+lignes[i][9]+"</td>"+
                                    "<td>"+lignes[i][10]+"</td>"+
                                "</tr>";
                                $("#listeLivraison"+idBon).prepend(ligne);
                            }   
                        }

                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            } 
        }
        details_Livraison_PH= function (idBon)  {
            details=$("#listeLivraison"+idBon).find('tr').length;
            if(details==1){
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'details_Livraison',
                        idBon : idBon,
                    },
                    success: function (data) {
                        var lignes = JSON.parse(data);
                        lignes_exist=$("#listeLivraison"+idBon).find('tr').length;
                        for(var l = 1; l<lignes_exist; l++){
                            $("#listeLivraison"+idBon).deleteRow(l);
                        }

                        if(lignes[0][0]==idBon){
                            var taille = lignes.length;
                            for(var i = 0; i<taille; i++){
                                var ligne = "<tr>"+
                                    "<td>"+lignes[i][1]+"</td>"+
                                    "<td>"+lignes[i][2]+"</td>"+
                                    "<td>"+lignes[i][3]+"</td>"+
                                    "<td>"+lignes[i][4]+"</td>"+
                                    "<td>"+lignes[i][5]+"</td>"+
                                    "<td>"+lignes[i][6]+"</td>"+
                                    "<td>"+lignes[i][7]+"</td>"+
                                    "<td>"+lignes[i][9]+"</td>"+
                                "</tr>";
                                $("#listeLivraison"+idBon).prepend(ligne);
                            }   
                        }

                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            } 
        }
        details_Livraison_ET= function (idBon)  {
            details=$("#listeLivraison"+idBon).find('tr').length;
            if(details==1){
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'details_Livraison',
                        idBon : idBon,
                    },
                    success: function (data) {
                        var lignes = JSON.parse(data);
                        lignes_exist=$("#listeLivraison"+idBon).find('tr').length;
                        for(var l = 1; l<lignes_exist; l++){
                            $("#listeLivraison"+idBon).deleteRow(l);
                        }

                        if(lignes[0][0]==idBon){
                            var taille = lignes.length;
                            for(var i = 0; i<taille; i++){
                                var ligne = "<tr>"+
                                    "<td>"+lignes[i][1]+"</td>"+
                                    "<td>"+lignes[i][2]+"</td>"+
                                    "<td>"+lignes[i][3]+"</td>"+
                                    "<td>"+lignes[i][4]+"</td>"+
                                    "<td>"+lignes[i][5]+"</td>"+
                                    "<td>"+lignes[i][6]+"</td>"+
                                    "<td>"+lignes[i][7]+"</td>"+
                                    "<td>"+lignes[i][8]+"</td>"+
                                    "<td>"+lignes[i][9]+"</td>"+
                                "</tr>";
                                $("#listeLivraison"+idBon).prepend(ligne);
                            }   
                        }

                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            } 
        }
    });

    $(function(){
        details_Commande= function (idBon)  {
            details=$("#listeCommande"+idBon).find('tr').length;
            if(details==1){
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'details_Commande',
                        idBon : idBon,
                    },
                    success: function (data) {
                        var lignes = JSON.parse(data);
                        lignes_exist=$("#listeCommande"+idBon).find('tr').length;
                        for(var l = 1; l<lignes_exist; l++){
                            $("#listeCommande"+idBon).deleteRow(l);
                        }

                        if(lignes[0][0]==idBon){
                            var taille = lignes.length;
                            for(var i = 0; i<taille; i++){
                                var ligne = "<tr>"+
                                    "<td>"+lignes[i][1]+"</td>"+
                                    "<td>"+lignes[i][2]+"</td>"+
                                    "<td>"+lignes[i][3]+"</td>"+
                                    "<td>"+lignes[i][4]+"</td>"+
                                    "<td>"+lignes[i][5]+"</td>"+
                                    "<td>"+lignes[i][6]+"</td>"+
                                    "<td>"+lignes[i][7]+"</td>"+
                                    "<td>"+lignes[i][8]+"</td>"+
                                    "<td>"+lignes[i][9]+"</td>"+
                                "</tr>";
                                $("#listeCommande"+idBon).prepend(ligne);
                            }   
                        }

                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            } 
        }
        details_Commande_PH= function (idBon)  {
            details=$("#listeCommande"+idBon).find('tr').length;
            if(details==1){
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'details_Commande',
                        idBon : idBon,
                    },
                    success: function (data) {
                        var lignes = JSON.parse(data);
                        lignes_exist=$("#listeCommande"+idBon).find('tr').length;
                        for(var l = 1; l<lignes_exist; l++){
                            $("#listeCommande"+idBon).deleteRow(l);
                        }

                        if(lignes[0][0]==idBon){
                            var taille = lignes.length;
                            for(var i = 0; i<taille; i++){
                                var ligne = "<tr>"+
                                    "<td>"+lignes[i][1]+"</td>"+
                                    "<td>"+lignes[i][2]+"</td>"+
                                    "<td>"+lignes[i][3]+"</td>"+
                                    "<td>"+lignes[i][4]+"</td>"+
                                    "<td>"+lignes[i][5]+"</td>"+
                                    "<td>"+lignes[i][6]+"</td>"+
                                    "<td>"+lignes[i][7]+"</td>"+
                                    "<td>"+lignes[i][8]+"</td>"+
                                "</tr>";
                                $("#listeCommande"+idBon).prepend(ligne);
                            }   
                        }

                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            } 
        }
        details_Commande_ET= function (idBon)  {
            details=$("#listeCommande"+idBon).find('tr').length;
            if(details==1){
                $.ajax({
                    url:"calculs/detailsFournisseur.php",
                    method:"POST",
                    data:{
                        operation:'details_Commande',
                        idBon : idBon,
                    },
                    success: function (data) {
                        var lignes = JSON.parse(data);
                        lignes_exist=$("#listeCommande"+idBon).find('tr').length;
                        for(var l = 1; l<lignes_exist; l++){
                            $("#listeCommande"+idBon).deleteRow(l);
                        }

                        if(lignes[0][0]==idBon){
                            var taille = lignes.length;
                            for(var i = 0; i<taille; i++){
                                var ligne = "<tr>"+
                                    "<td>"+lignes[i][1]+"</td>"+
                                    "<td>"+lignes[i][2]+"</td>"+
                                    "<td>"+lignes[i][3]+"</td>"+
                                    "<td>"+lignes[i][4]+"</td>"+
                                    "<td>"+lignes[i][5]+"</td>"+
                                    "<td>"+lignes[i][6]+"</td>"+
                                    "<td>"+lignes[i][7]+"</td>"+
                                    "<td>"+lignes[i][8]+"</td>"+
                                    "<td>"+lignes[i][9]+"</td>"+
                                "</tr>";
                                $("#listeCommande"+idBon).prepend(ligne);
                            }   
                        }

                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            } 
        }
    });

    $(function(){
        valider_Livraison= function (idBon)  {
            $.ajax({
            url:"calculs/detailsFournisseur.php",
            method:"POST",
            data:{
                operation:'valider_Commande',
                idBon : idBon,
            },
            success: function (data) {
                $("#listerOperations").load("datatables/detailsFournisseur.php",{"nbre_Entree":"","query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                    if(status == "success"){
                                
                    }
                });
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
            }); 
        }
    });

    function calcul_Montant_Fournisseur(){
        $.ajax({
            url:"calculs/detailsFournisseur.php",
            method:"POST",
            data:{
                operation:'calcul_Montant',
                idFournisseur: id,
                dateDebut: dateDebut,
                dateFin: dateFin,
            },
            success: function (data) {
                var details = JSON.parse(data);
                var montant=details.split('<>')[0];
                var bons=details.split('<>')[1];
                var versements=details.split('<>')[2];
                $('#spn_montant_Total').text(montant);
                $('#spn_montant_Bl').text(bons);
                $('#spn_montant_Versements').text(versements);
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    }

    $(function(){
        ticket_Versement= function(idVersement)  {
            window.open("versementTicket.php?id="+idVersement, "_blank");
            //$("#inpt_ajouter_Panier_Ligne_"+idVersement).focus();
        }
    });

    $(function(){
        facture_Versement= function(idVersement)  {
            window.open("versementFacture.php?id="+idVersement, "_blank");
            //$("#inpt_ajouter_Panier_Ligne_"+idVersement).focus();
        }
    });

    $(function(){
        facture_Bon= function(idBon)  {
            window.open("bonCommandeFacture.php?id="+idBon);
        }
    });

    function format_date(jour){
        type=jour.split('-');
        if(type[0].length==2 || type[0].length==4){
            if(type[0].length==2){
                jour=type[2]+'-'+type[1]+'-'+type[0];
            }
        }
        else{
            type=jour.split('/');
            if(type[0].length==2 || type[0].length==4){
                if(type[0].length==2){
                    jour=type[2]+'-'+type[1]+'-'+type[0];
                }
            }
        }
        return jour;
    }




});
