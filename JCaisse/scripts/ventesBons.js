$(document).ready(function() { 
    
    id=$('#inpt_Client_id').val();
    dateDebut=$('#inpt_Client_dateDebut').val();
    dateFin=$('#inpt_Client_dateFin').val();

    $("#listerVentes").load("datatables/listerVentesBons.php",{"nbre_Entree":"","query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
        if(status == "success"){
            $(".loading-gif").hide();  
            calcul_Montant_Client();
        }
    });
    
    $("#inpt_Search_ListerVentes").on("keyup", function(e) {

        e.preventDefault();
        query = $('#inpt_Search_ListerVentes').val();
        nbre_Entree = $('#slct_Nbre_ListerVentes').val() ;

        var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                if (query.length > 0) {
                    $("#listerVentes").load("datatables/listerVentesBons.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                        if(status == "success"){
                                    
                        }
                    });
                }else{
                    $("#listerVentes").load("datatables/listerVentesBons.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                        if(status == "success"){
                                    
                        }
                    });
                }
            }else{
                setTimeout(() => {
                    if (query.length > 0) {
                        $("#listerVentes").load("datatables/listerVentesBons.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                            if(status == "success"){
                                        
                            }
                        });
                    }else{
                        $("#listerVentes").load("datatables/listerVentesBons.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                            if(status == "success"){
                                        
                            }
                        });
                    }

                }, 100);
            }

            return false; 
    });

    $("#listerVentes").on( "change", "#slct_Nbre_ListerVentes", function (e){

        e.preventDefault();
        query = $('#inpt_Search_ListerVentes').val();
        nbre_Entree = $('#slct_Nbre_ListerVentes').val() ;

        if (query.length == 0) {
            $("#listerVentes").load("datatables/listerVentesBons.php",{"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerVentes').val(nbre_Entree) ;
                }
            });
        }else{
            $("#listerVentes").load("datatables/listerVentesBons.php",{"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerVentes').val(nbre_Entree) ;     
                }
            });
        }

    });

    $("#listerVentes").on( "click", ".pagination a", function (e){

        e.preventDefault();
        page = $(this).attr("data-page"); //get page number from link
        query = $('#inpt_Search_ListerVentes').val();
        nbre_Entree = $('#slct_Nbre_ListerVentes').val() ;

        if (query.length == 0) {
            $("#listerVentes").load("datatables/listerVentesBons.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerVentes').val(nbre_Entree) ;        
                }
            });
        }else{
            $("#listerVentes").load("datatables/listerVentesBons.php",{"page":page,"nbre_Entree":nbre_Entree,"query":query,"id":id,"dateDebut":dateDebut,"dateFin":dateFin},  function (response, status, xhr){
                if(status == "success"){
                    $('#slct_Nbre_ListerVentes').val(nbre_Entree) ;       
                }
            });
        }

    });

    $(function(){
        detailsVente= function (idPagnet)  {
            details=$("#tablePanier"+idPagnet).find('tr').length;
            if(details==1){
                var verrouiller = $('#panelPanier'+idPagnet).attr("panier-verrouiller");
                if(verrouiller==0){
                    var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                    if(data_Panier==null){ data_Panier = []; }
                        lister_Paniers = data_Panier.filter(p=>p.idPagnet != idPagnet);
                        localStorage.setItem("Panier", JSON.stringify(lister_Paniers));
                    var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
                    if(data_Ligne==null){ data_Ligne = []; }
                        lister_Lignes = data_Ligne.filter(l=>l.idPagnet != idPagnet);
                        localStorage.setItem("Ligne", JSON.stringify(lister_Lignes));
                }
                $.ajax({
                    url:"calculs/ventesBons.php",
                    method:"POST",
                    data:{
                        operation:'detailsVente',
                        idPagnet : idPagnet,
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        lignes = details[0];
                        panier = details[1];
                        lignes_exist=$("#tablePanier"+idPagnet).find('tr').length;
                        for(var l = 1; l<lignes_exist; l++){
                            $("#tablePanier"+idPagnet).deleteRow(l);
                        }
                        if(verrouiller==0){
                            var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                            if(data_Panier==null){
                                data_Panier = [];
                            }
                            data_Panier.push(panier);
                            localStorage.setItem("Panier", JSON.stringify(data_Panier));
                            afficher_Compte(panier['idPagnet'],panier['idCompte'],panier['type']);
                        }
                        if(lignes[0][0]==idPagnet){
                            afficher_Ligne(idPagnet,verrouiller,panier,lignes);
                            if(verrouiller==0){
                                afficher_Champ_Panier(idPagnet);
                            }
                            var sum = 0;
                            $('.inpt_Ligne_Prix_Total'+idPagnet).each(function() {
                                sum += parseFloat($(this).val());
                            });
                            $('#spn_ajouter_Panier_Total'+idPagnet).text(sum); 
                            $('#spn_ajouter_Panier_APayer'+idPagnet).text(sum);
                        }
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            } 
            couleur_Panier_Non_Termine(idPagnet);
        }
    });

    $(function(){
        ajouter_Panier= function ()  {
            $.ajax({
                url:"calculs/ventesBons.php",
                method:"POST",
                data:{
                    operation:'ajouter_Panier',
                    idClient : id
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    var compte = details[4];
                    var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                    if(data_Panier==null){
                        data_Panier = [];
                    }
                    data_Panier.push(details[5]);
                    localStorage.setItem("Panier", JSON.stringify(data_Panier));

                        form_Btn="<form class='form-inline noImpr' method='post' id='form_ajouter_Panier_Btn_"+details[0]+"'>"+
                                "<input type='number' step='5' id='inpt_ajouter_Panier_Remise_"+details[0]+"' name='inpt_ajouter_Panier_Remise' class='remise form-control col-md-2 col-sm-2 col-xs-3 inpt_ajouter_Panier_Remise' placeholder='Remise....' >"+
                                "<input type='number' step='5' class='avanceInput form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Bon"+details[0]+" champ_saisie_Client"+details[0]+" inpt_ajouter_Panier_Avance' placeholder='Avance...' id='inpt_ajouter_Panier_Avance_"+details[0]+"' />"+
                                "<select style='display:none' class='compte form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Compte"+details[0]+" champ_saisie_Client"+details[0]+"' id='slct_ajouter_Bon_Compte_"+details[0]+"'> </select>"+
                                "<input style='display:none' type='number' name='frais' id='inpt_ajouter_Panier_Frais_"+details[0]+"'  placeholder='Montant Frais...' class='versement form-control col-md-2 col-sm-2 col-xs-3 champ_saisie_Frais"+details[0]+"'>"+
                                "<button tabindex='1' type='button'  onclick='retourner_Panier("+details[0]+")' class='btn btn-danger pull-right' id='btn_retourner_Panier"+details[0]+"'  data-toggle='modal' data-target='#msg_ann_pagnet"+details[0]+"' >"+
                                    "<span class='glyphicon glyphicon-remove'></span>"+
                                "</button>"+
                                "<button type='button' onclick='valider_Panier("+details[0]+")' data-idPanier='"+details[0]+"' id='btn_valider_Panier"+details[0]+"' class='btn btn-success btn_valider_Panier"+details[0]+" pull-right' style='margin-right:2px;'>"+
                                    "<span class='glyphicon glyphicon-ok'></span>"+
                                "</button>"+
                            "</form>";

                        var panier ="<div style='margin-bottom:5px;' class='panel panel-primary' id='panelPanier"+details[0]+"' panier-type='0' panier-compte='1' panier-client='"+id+"' panier-verrouiller='0' >"+
                        "<div class='panel-heading'>"+
                        "<h4 style='padding-bottom: 18px;' data-toggle='collapse' data-parent='#accordion' href='#"+details[0]+"'  class='panel-title expand'>"+
                            "<div class='right-arrow pull-right'>+</div>"+
                            "<a href='#'  onclick='detailsVente("+details[0]+")'>"+ 
                            "<span class='hidden-xs hidden-sm col-md-2 col-lg-1'><span>N</span> 0</span>"+
                            "<span class='hidden-xs col-sm-4 col-md-3 col-lg-3'><span>Date</span> : "+details[6]+" "+details[1]+"</span>"+
                            "<span class='hidden-xs hidden-sm hidden-md col-lg-2'><span>Total</span> : <span id='spn_ajouter_Panier_Total"+details[0]+"' >"+details[2]+" </span></span>"+
                            "<span class='col-xs-6 col-sm-3 col-md-3 col-lg-2'> <span>Net HT</span> : <span id='spn_ajouter_Panier_APayer"+details[0]+"'>"+details[3]+" </span></span>"+
                            "</a>"+
                        "</h4>"+
                        "</div>"+
                        "<div id='"+details[0]+"'  class='panel-collapse collapse in'>"+
                        "<br>"+
                        "<div class='content-panel' style='margin-left:12px;margin-right:12px'>"+
                            "<div class='cacher_btn_Terminer col-lg-11 col-md-11' id='cacher_btn_Terminer"+details[0]+"'>"+
                                "<form  class='form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12' id='form_ajouter_Panier_Ligne_"+details[0]+"' onsubmit='return false' >"+
                                    "<input type='text' class='inputbasic form-control inpt_ajouter_Panier_Ligne' name='codeBarre' id='inpt_ajouter_Panier_Ligne_"+details[0]+"' data-idPanier='"+details[0]+"' style='width:100%;' autocomplete='off' />"+
                                "</form>"+
                                "<div class='col-md-8 col-sm-8 col-xs-12 content2'>"+form_Btn+"</div>"+
                            "</div>"+
                            "<table class='table table-hover' id='tablePanier"+details[0]+"'>"+
                                "<h4 style='display:none' class='pull-right' id='afficher_btn_Ticket"+details[0]+"'>"+
                                    "<button type='button' style='margin-right:5px' class='btn btn-round btn-warning' onclick='ticket_Panier("+details[0]+")'> <span>Ticket</span></button>"+
                                    "<button type='button' style='margin-right:5px' class='btn btn-round btn-info' onclick='facture_Panier("+details[0]+")'> <span>Facture</span></button>"+
                                    "<button type='button' style='margin-right:5px' onclick='retourner_Panier("+details[0]+")' class='btn btn-round btn-danger' id='btn_retourner_Panier"+details[0]+"' style='margin-right: 5px;' data-toggle='modal' data-target=''> <span>Retourner</span></button>"+
                                "</h4>"+
                                "<thead>"+
                                    "<tr>"+
                                    "<th><span>Reference</span></th>"+
                                    "<th><span>Quantite</span></th>"+
                                    "<th><span>Unite Vente</span></th>"+
                                    "<th><span>Prix Vente</span></th>"+
                                    "<th class='hidden-xs hidden-sm'><span>Prix Total</span></th>"+
                                    "<th class='hidden-xs'><span>Operations</span></th>"+
                                    "</tr>"+
                                "</thead>"+
                                "<tbody>"+
            
                                "</tbody>"+
                            "</table>"+
                            "<div style='display:none' id='afficher_Paiement"+details[0]+"'>"+
                                "<h5><b>Total panier</b> : <span id='spn_ajouter_Panier_Paiement_Total"+details[0]+"'></h5>"+
                                "<h5><b>Remise</b> : <span id='spn_ajouter_Panier_Paiement_Remise"+details[0]+"'></h5>"+
                                "<h5><b>Net HT</b> : <span id='spn_ajouter_Panier_Paiement_APayer"+details[0]+"'></h5>"+
                            "</div>"+
                        "</div>"+
                        "</div>"+
                    "</div>";
                    $("#listerVentes #accordion").prepend(panier);
                    $("#inpt_ajouter_Panier_Ligne_"+details[0]).focus();
                    afficher_Compte(details[0],1,0);
                    calcul_Panier_Non_Termine();
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        }
    });
    
    var type_query='';
    $("#listerVentes").on( "keyup", ".inpt_ajouter_Panier_Ligne", function (e){
        e.preventDefault();
        var tab_idPanier = $(this).attr('id');
        tab = tab_idPanier.split('_');
        idPagnet = tab[4];
        
        var compte = $('#panelPanier'+idPagnet).attr("panier-compte");
        var type = $('#panelPanier'+idPagnet).attr("panier-type");
        var nbre_Ligne=nombre_Ligne_Panier(idPagnet);
        var query = $(this).val();
        type_query=type_query+''+query;
        var data_Designation = [];
        for (let i = 0; i < localStorage.length; i++) {
            if(localStorage.key(i).split('_')[0]=='Designation'){
                var produits = JSON.parse(localStorage.getItem(localStorage.key(i)));
                data_Designation=data_Designation.concat(produits);
            }
        } 
        if(type==0){
            if(nbre_Ligne>0){
                data = new Array();
                data = data_Designation.filter(d=>d.classe==0 || d.classe==1);
            }
            else{
                data = new Array();
                data = data_Designation.filter(d=>d.classe==0 || d.classe==1 || d.classe==6 || d.classe==9 || d.classe==10 || d.classe==11);
            }
        }
        else if(type==10){
            data = new Array();
            data = data_Designation.filter(d=>d.classe==0 || d.classe==1);
        }
        else{
            data = new Array();
            data = data_Designation.filter(d=>d.classe==type);
        }
        $(this).typeahead({
            source: function (query, result) {
            var references = new Array();
            if (data != null) {
                for (var i = 0; i < data.length; i++) {
                references[i] = data[i].designation;
                } 
            }
            dataType: "json",
                result(
                $.map(references, function (item) {
                    return item;
                })
                );
            },
            highlighter: function (item) {
                return '<div class="reference">' + item+ '</div>';
            },
        });
        $(this).focus();
        
        //var reference=$('#form_ajouter_Panier_Ligne_'+idPagnet+' .typeahead li.active').text();
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            var type = $('#panelPanier'+idPagnet).attr("panier-type");
            var nombre_Ligne = nombre_Ligne_Panier(idPagnet);
            var reference = $(this).val();
            var produit=$('#form_ajouter_Panier_Ligne_'+idPagnet+' .typeahead li.active').text();
            var result=0;
            if($.isNumeric(type_query.substr(0, 20))){
                if ($.isNumeric(reference)) {
                    ajouter_Ligne_CodeBarre(idPagnet,reference,type);
                    result=1;
                }
            }
            else{
                ajouter_Ligne_Reference(idPagnet,reference,type);
                result=1;
            }

            $('#form_ajouter_Panier_Ligne_'+idPagnet).val('');
            $('.typeahead').html('');
            type_query='';
            setTimeout(2000);
            if(result==1){
                $.ajax({
                    url:"calculs/ventesBons.php",
                    method:"POST",
                    data:{
                        operation:'ajouter_Ligne',
                        idPagnet: idPagnet,
                        nombre_Ligne: nombre_Ligne,
                        type: type,
                        reference: reference
                    },
                    success: function (data) {
                        var details = JSON.parse(data);
                        taille=details.length;
                        if(taille > 5){
                            var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
                            if(data_Ligne==null){ data_Ligne = []; }
                            ligne_Existe = data_Ligne.find(l=>l.designation==reference && l.idPagnet==idPagnet);
                            if (ligne_Existe != null) {
                                if (details[1]==ligne_Existe.idPagnet && details[3]==ligne_Existe.designation) {
                                    ligne_Existe.numligne = details[0];
                                    localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
                                }
                            }
                        }
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                }); 
            }
            $('#inpt_ajouter_Panier_Ligne_'+idPagnet).val('');
            $('#form_ajouter_Panier_Ligne_'+idPagnet+' .typeahead li.active').remove();
        }
       
    });

    $("#listerVentes").on( "click", ".typeahead li a[class='dropdown-item']", function (e){
        var reference=$('#form_ajouter_Panier_Ligne_'+idPagnet+' .typeahead li.active').text();
        if(reference!='' && reference!=null){
            var type = $('#panelPanier'+idPagnet).attr("panier-type");
            var nombre_Ligne = nombre_Ligne_Panier(idPagnet);
            ajouter_Ligne_Reference(idPagnet,reference,type);
            $.ajax({
                url:"calculs/ventesBons.php",
                method:"POST",
                data:{
                    operation:'ajouter_Ligne',
                    idPagnet: idPagnet,
                    nombre_Ligne: nombre_Ligne,
                    type: type,
                    reference: reference
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    taille=details.length;
                    if(taille > 5){
                        var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
                        if(data_Ligne==null){ data_Ligne = []; }
                        ligne_Existe = data_Ligne.find(l=>l.designation==reference && l.idPagnet==idPagnet);
                        if (ligne_Existe != null) {
                            if (details[1]==ligne_Existe.idPagnet && details[3]==ligne_Existe.designation) {
                                ligne_Existe.numligne = details[0];
                                localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
                            }
                        }
                    }
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
            $('#inpt_ajouter_Panier_Ligne_'+idPagnet).val('');
            $('#form_ajouter_Panier_Ligne_'+idPagnet+' .typeahead li.active').remove();
        }
    });

    $(function(){
        modifier_Reference= function (idLigne)  {
            var reference=$('#txtarea_Ligne_Reference'+idLigne).val();
            var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
            if(data_Ligne==null){ data_Ligne = []; }
            ligne_Existe = data_Ligne.find(l=>l.idLigne==idLigne);
            if (ligne_Existe != null) {
                ligne_Existe.designation = reference;
                localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
                $('#txtarea_Ligne_Reference'+idLigne).val(ligne_Existe.designation);
                numligne = ligne_Existe.numligne ;
            }
            $.ajax({
                url:"calculs/ventesBons.php",
                method:"POST",
                data:{
                    operation:'modifier_Ligne_Reference',
                    numligne: numligne,
                    reference: reference
                },
                success: function (data) {
                    var details = JSON.parse(data);
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        }
    });

    $(function(){
        modifier_Quantite= function (idLigne)  {
            var quantite=$('#inpt_Ligne_Quantite'+idLigne).val();
            if(parseInt(quantite)>0){ }
            else{
                quantite=0;
                $('#inpt_Ligne_Quantite'+idLigne).val('');
            }
            var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
            if(data_Ligne==null){ data_Ligne = []; }
            ligne_Existe = data_Ligne.find(l=>l.idLigne==idLigne);
            if (ligne_Existe != null) {
                ligne_Existe.quantite = quantite;
                ligne_Existe.prixtotal = ligne_Existe.quantite * ligne_Existe.prixunitevente;
                localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
                //$('#inpt_Ligne_Quantite'+idLigne).val(ligne_Existe.quantite);
                $('#inpt_Ligne_Prix_Total'+idLigne).val(ligne_Existe.prixtotal);
                numligne = ligne_Existe.numligne ;
                calcul_Entete_Panier(ligne_Existe.idPagnet);
            }
            $.ajax({
                url:"calculs/ventesBons.php",
                method:"POST",
                data:{
                    operation:'modifier_Ligne_Quantite',
                    numligne: numligne,
                    quantite: quantite
                },
                success: function (data) {
                    var details = JSON.parse(data);
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        }
    });

    $(function(){
        modifier_Unite= function(unite)  {
            var unitevente=unite.split('<>')[0];
            var prix=unite.split('<>')[1];
            var idLigne=unite.split('<>')[2];
            var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
            if(data_Ligne==null){ data_Ligne = []; }
            ligne_Existe = data_Ligne.find(l=>l.idLigne==idLigne);
            if (ligne_Existe != null) {
                ligne_Existe.unitevente = unitevente;
                ligne_Existe.prixunitevente = prix;
                ligne_Existe.prixtotal = ligne_Existe.quantite * ligne_Existe.prixunitevente;
                localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
                $('#inpt_Ligne_Prix'+idLigne).val(ligne_Existe.prixunitevente);
                $('#inpt_Ligne_Prix_Total'+idLigne).val(ligne_Existe.prixtotal);
                numligne = ligne_Existe.numligne ;
                calcul_Entete_Panier(ligne_Existe.idPagnet);
            }
            $.ajax({
                url:"calculs/ventesBons.php",
                method:"POST",
                data:{
                    operation:'modifier_Ligne_Unite',
                    numligne: numligne,
                    unitevente: unitevente,
                    prix: prix
                },
                success: function (data) {
                    var details = JSON.parse(data);
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            });
        }
    });

    $(function(){
        modifier_Prix= function (idLigne)  {
            var prix=$('#inpt_Ligne_Prix'+idLigne).val();
            if(parseInt(prix)>0){ }
            else{
                prix=0;
                $('#inpt_Ligne_Prix'+idLigne).val('');
            }
            var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
            if(data_Ligne==null){ data_Ligne = []; }
            ligne_Existe = data_Ligne.find(l=>l.idLigne==idLigne);
            if (ligne_Existe != null) {
                ligne_Existe.prixunitevente = prix;
                ligne_Existe.prixtotal = ligne_Existe.quantite * ligne_Existe.prixunitevente;
                localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
                //$('#inpt_Ligne_Prix'+idLigne).val(ligne_Existe.prixunitevente);
                $('#inpt_Ligne_Prix_Total'+idLigne).val(ligne_Existe.prixtotal);
                numligne = ligne_Existe.numligne ;
                calcul_Entete_Panier(ligne_Existe.idPagnet);
            }
            $.ajax({
                url:"calculs/ventesBons.php",
                method:"POST",
                data:{
                    operation:'modifier_Ligne_Prix',
                    numligne: numligne,
                    prix: prix
                },
                success: function (data) {
                    var details = JSON.parse(data);
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        }
    });

    $(function(){
        retourner_Ligne= function(idLigne,idPagnet)  {
            $('#inpt_retourner_Ligne_numligne').val(idLigne);
            $('#inpt_retourner_Ligne_idPagnet').val(idPagnet);
            var verrouiller = $('#panelPanier'+idPagnet).attr("panier-verrouiller");
            if(verrouiller==0){
                $('#spn_hd_action_Ligne').text('Annuler Ligne');
                $('#spn_bd_action_Ligne').text('Voulez vous vraiment annuler cette Ligne ?');
                $('#btn_action_Ligne').text('Annuler');
            }
            else{
                $('#spn_hd_action_Ligne').text('Retourner Ligne');
                $('#spn_bd_action_Ligne').text('Voulez vous vraiment retourner cette Ligne ?');
                $('#btn_action_Ligne').text('Retourner');
            }
            $('#action_Ligne').modal('show');
        }
    });

    $("#btn_retourner_Ligne").on( "click", function (e){
        idLigne = $('#inpt_retourner_Ligne_numligne').val();
        idPagnet = $('#inpt_retourner_Ligne_idPagnet').val();
        var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
        if(data_Ligne==null){ data_Ligne = []; }
        ligne_Existe = data_Ligne.find(l=>l.idLigne==idLigne);
        if (ligne_Existe != null) {
            ligne_Existe.activer = 0;
            localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
            $('#lignePanier'+idLigne).each(function() {
                $(this).remove();
            });
            $('#action_Ligne').modal('hide');
            calcul_Entete_Panier(idPagnet);
            nbre_Ligne=nombre_Ligne_Panier(idPagnet);
            if(nbre_Ligne==0){
                $('#panelPanier'+idPagnet).attr("panier-type",0);
                $('#panelPanier'+idPagnet).attr("panier-compte",0);
                $('#panelPanier'+idPagnet).attr("panier-client",0);
                $('#panelPanier'+idPagnet).attr("class", "panel panel-primary");
                $('#inpt_ajouter_Panier_Ligne_'+idPagnet).show();
                $('#inpt_ajouter_Panier_Remise_'+idPagnet).show();
                $('#slct_ajouter_Panier_Compte_'+idPagnet).show();
                $('.champ_saisie_Bon'+idPagnet).hide();
                $('.champ_saisie_Bon'+idPagnet).val('');
                $('.champ_saisie_Caisse'+idPagnet).show();
                $('.champ_saisie_Frais'+idPagnet).hide();
                $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
                afficher_Compte(idPagnet,0,0,0);
            }
            numligne = ligne_Existe.numligne ;
        }
        $.ajax({
            url:"calculs/ventesBons.php",
            method:"POST",
            data:{
                operation:'retourner_Ligne',
                numligne: numligne,
                idPagnet : idPagnet
            },
            success: function (data) {
                var details = JSON.parse(data);

            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });

    $(function(){
        valider_Panier= function(idPagnet)  {
            var nbre_Ligne=0;
            $('.inpt_Ligne_Prix_Total'+idPagnet).each(function() {
                nbre_Ligne++;
            });
            $("#btn_valider_Panier"+idPagnet).prop("disabled", true); 
            if(nbre_Ligne > 0){
                var info_compte=$('#slct_ajouter_Panier_Compte_'+idPagnet+' option:selected').text();
                terminer_Panier(idPagnet);
                $('#afficher_Compte'+idPagnet).show(); 
                $('#spn_ajouter_Panier_Compte'+idPagnet).text(info_compte); 
            }
            else {
                $('#inpt_ajouter_Panier_Ligne_'+idPagnet).focus();
            }
            $("#btn_valider_Panier"+idPagnet).prop("disabled", false); 
        }
    });

    $(function(){
        retourner_Panier= function(idPagnet)  {
            $('#inpt_retourner_Panier').val(idPagnet);
            var verrouiller = $('#panelPanier'+idPagnet).attr("panier-verrouiller");
            if(verrouiller==0){
                $('#spn_hd_action_Panier').text('Annuler Panier');
                $('#spn_bd_action_Panier').text('Voulez vous vraiment annuler ce Panier ?');
                $('#btn_action_Panier').text('Annuler');
            }
            else{
                $('#spn_hd_action_Panier').text('Retourner Panier');
                $('#spn_bd_action_Panier').text('Voulez vous vraiment retourner ce Panier ?');
                $('#btn_action_Panier').text('Retourner');
            }
            $('#action_Panier').modal('show');
        }
    });

    $("#btn_retourner_Panier").on( "click", function (e){
        idPagnet = $('#inpt_retourner_Panier').val();
        var data_Panier = JSON.parse(localStorage.getItem("Panier"));
        if(data_Panier==null){ data_Panier = []; }
            lister_Paniers = data_Panier.filter(p=>p.idPagnet != idPagnet);
            localStorage.setItem("Panier", JSON.stringify(lister_Paniers));
        var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
        if(data_Ligne==null){ data_Ligne = []; }
            lister_Lignes = data_Ligne.filter(l=>l.idPagnet != idPagnet);
            localStorage.setItem("Ligne", JSON.stringify(lister_Lignes));
        $('#panelPanier'+idPagnet).each(function() {
            $(this).remove();
        });
        $('#action_Panier').modal('hide');
        calcul_Panier_Non_Termine();

        $.ajax({
            url:"calculs/ventesBons.php",
            method:"POST",
            data:{
                operation:'retourner_Panier',
                idPagnet: idPagnet,
                idClient: id,
                dateDebut: dateDebut,
                dateFin: dateFin,
            },
            success: function (data) {
                var details = JSON.parse(data);
                montant_client=details[1];
                var montant=montant_client.split('<>')[0];
                var bons=montant_client.split('<>')[1];
                var versements=montant_client.split('<>')[2];
                $('#spn_montant_Total').text(montant);
                $('#spn_montant_Bons').text(bons);
                $('#spn_montant_Versements').text(versements);
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });

    $("#listerVentes").on( "keyup paste change", ".inpt_ajouter_Panier_Remise", function (e){
        var tab_idPanier = $(this).attr('id');
        tab = tab_idPanier.split('_');
        idPagnet = tab[4];

        var remise = $(this).val();
        var total = $('#spn_ajouter_Panier_Total'+idPagnet).text(); 
        if(remise>0 && remise<=parseInt(total)){
        }
        else{
            remise = 0;
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).val('');
        }
        var versement = $('#inpt_ajouter_Panier_Versement_'+idPagnet).val();
        var sum = 0;
        $('.inpt_Ligne_Prix_Total'+idPagnet).each(function() {
            sum += parseFloat($(this).val());
        });
        $('#spn_ajouter_Panier_Total'+idPagnet).text(sum); 
        $('#spn_ajouter_Panier_APayer'+idPagnet).text(sum - remise);
        $('#spn_ajouter_Panier_Rendu'+idPagnet).text(versement - (sum - remise));

        
    });

    $("#listerVentes").on( "keyup paste change", ".inpt_ajouter_Panier_Avance", function (e){
        var tab_idPanier = $(this).attr('id');
        tab = tab_idPanier.split('_');
        idPagnet = tab[4];
        var avance = $(this).val();
        if(avance>0){
            $('.champ_saisie_Compte'+idPagnet).show();
        }
        else{
            avance = 0;
            $('#inpt_ajouter_Panier_Avance_'+idPagnet).val('');
            $('.champ_saisie_Compte'+idPagnet).hide();
        }
    });

    function calcul_Entete_Panier(idPagnet){
        var remise = $('#inpt_ajouter_Panier_Remise_'+idPagnet).val();
        var versement = $('#inpt_ajouter_Panier_Versement_'+idPagnet).val();

        var sum = 0;
        $('.inpt_Ligne_Prix_Total'+idPagnet).each(function() {
            sum += parseFloat($(this).val());
        });
        $('#spn_ajouter_Panier_Total'+idPagnet).text(sum); 
        $('#spn_ajouter_Panier_APayer'+idPagnet).text(sum - remise);
        if(versement!='' && versement!=null){
            $('#spn_ajouter_Panier_Rendu'+idPagnet).text(versement - (sum - remise));
        }

    }

    function calcul_Footer_Panier(remise,idPagnet){
        var sum = 0;
        $('.inpt_Ligne_Prix_Total'+idPagnet).each(function() {
            sum += parseFloat($(this).val());
        });

        if(remise!='' && remise!=null){ } else { remise = 0; }

        $('#spn_ajouter_Panier_Paiement_Total'+idPagnet).text(sum); 
        $('#spn_ajouter_Panier_Paiement_Remise'+idPagnet).text(remise);
        $('#spn_ajouter_Panier_Paiement_APayer'+idPagnet).text(sum - remise);
    }

    function terminer_Panier(idPagnet){
        var remise=$('#inpt_ajouter_Panier_Remise_'+idPagnet).val();
        var avance=$('#inpt_ajouter_Panier_Avance_'+idPagnet).val();
        var idCompte=$('#slct_ajouter_Bon_Compte_'+idPagnet+' option:selected').val();
        var compte=$('#slct_ajouter_Bon_Compte_'+idPagnet+' option:selected').text();
        $('#panelPanier'+idPagnet).attr("panier-verrouiller", 1);
        $('#cacher_btn_Terminer'+idPagnet).remove();
        $('#afficher_btn_Ticket'+idPagnet).show();
        $('#afficher_Paiement'+idPagnet).show();
        calcul_Footer_Panier(remise,idPagnet);
        $('.champ_saisie_Ligne'+idPagnet).each(function() {
            $(this).replaceWith($("<span />").text($(this).val().split('<>')[0]));
        });
        $(".btn_retourner_Ligne"+idPagnet).prop("disabled", true);
        $('#panelPanier'+idPagnet).attr("panier-verrouiller",1);
        calcul_Panier_Non_Termine();

        $.ajax({
            url:"calculs/ventesBons.php",
            method:"POST",
            data:{
                operation:'valider_Panier',
                idPagnet: idPagnet,
                idClient: id,
                dateDebut: dateDebut,
                dateFin: dateFin,
                remise: remise,
                avance: avance,
                idCompte: idCompte,
            },
            success: function (data) {
                var details = JSON.parse(data);
                if(avance!=''){
                    versement=details[1];
                    idVersement=versement.split('<>')[0];
                    dateHeure=versement.split('<>')[1];
                    date=dateHeure.split(' ')[0];
                    ajouter_Avance(idVersement,avance,compte,date,dateHeure);
                }
                montant_client=details[2];
                var montant=montant_client.split('<>')[0];
                var bons=montant_client.split('<>')[1];
                var versements=montant_client.split('<>')[2];
                $('#spn_montant_Total').text(montant);
                $('#spn_montant_Bons').text(bons);
                $('#spn_montant_Versements').text(versements);
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    }
                    
    $("#listerVentes").on( "keyup", ".inpt_ajouter_Panier_Client", function (e){
        var tab_idPanier = $(this).attr('id');
        tab = tab_idPanier.split('_');
        idPagnet = tab[4];
        var query = $(this).val();
        const data = JSON.parse(localStorage.getItem("Client"));
        var clients = new Array();
        if (data != null) {
          for (var i = 0; i < data.length; i++) {
            clients[i] = data[i].idClient + "." + data[i].nom + " " + data[i].prenom + " " + data[i].adresse;
          }
        }
        $(this).typeahead({
          source: function (query, result) {
            dataType: "json",
              result(
                $.map(clients, function (item) {
                  return item;
                })
              );
          },
            highlighter: function (item) {
                return '<span class="client">' + item+ '</span>';
            },
        });
        $(this).focus();

        var client=$('#form_ajouter_Panier_Btn_'+idPagnet+' .typeahead li.active').text();
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            tab = client.split('.');
            idClient = tab[0];
            $.ajax({
                url:"calculs/ventesBons.php",
                method:"POST",
                data:{
                    operation:'choisir_Client',
                    idPagnet: idPagnet,
                    idClient: idClient
                },
                success: function (data) {
                    $('#panelPanier'+idPagnet).attr("panier-type",0);
                    $('#panelPanier'+idPagnet).attr("panier-compte",2);
                    afficher_Champ_Panier_Compte(idPagnet,2,0);
                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            });
            $('#form_ajouter_Panier_Btn_'+idPagnet+' .typeahead li.active').remove();
        } 
       
    });

    function ajouter_Ligne_Reference(idPagnet,reference,type){
        var data_Designation = [];
        for (let i = 0; i < localStorage.length; i++) {
            if(localStorage.key(i).split('_')[0]=='Designation'){
                var produits = JSON.parse(localStorage.getItem(localStorage.key(i)));
                data_Designation=data_Designation.concat(produits);
            }
        } 
        produit=data_Designation.find(d=>d.designation==reference);
        if (produit != null) {
            var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
            if(data_Ligne==null){
                data_Ligne = [];
            }
            ligne_Existe = data_Ligne.find(l=>parseInt(l.idDesignation)==parseInt(produit.idDesignation) && l.idPagnet==idPagnet);
            if (ligne_Existe != null && ligne_Existe.activer==1) {
                ligne_Existe.quantite = parseInt(ligne_Existe.quantite) + 1;
                ligne_Existe.prixtotal = ligne_Existe.quantite * ligne_Existe.prixunitevente;
                localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
                $('#inpt_Ligne_Quantite'+ligne_Existe.idLigne).val(ligne_Existe.quantite);
                $('#inpt_Ligne_Prix_Total'+ligne_Existe.idLigne).val(ligne_Existe.prixtotal);
            }
            else{
                if(produit.classe!=10){
                    ligne_Panier = data_Ligne.filter(l=>l.idPagnet==idPagnet);
                    nbre_Ligne=ligne_Panier.length;
                    var new_Ligne = new Object();
                    new_Ligne.idLigne = idPagnet+''+nbre_Ligne;
                    new_Ligne.numligne = 0;
                    new_Ligne.idPagnet = idPagnet;
                    new_Ligne.idDesignation = produit.idDesignation;
                    new_Ligne.designation = produit.designation;
                    new_Ligne.unitevente = 'Article';
                    new_Ligne.prixunitevente = produit.prix;
                    new_Ligne.quantite = 1;
                    new_Ligne.prixtotal = produit.prix;
                    new_Ligne.classe = produit.classe;
                    new_Ligne.activer = 1;
                    data_Ligne.push(new_Ligne);
                    localStorage.setItem("Ligne", JSON.stringify(data_Ligne)); 

                    if(produit.classe==0){
                        if(produit.uniteStock=='Article' || produit.uniteStock=='article'){
                            unite = "<select id='slct_Ligne_Unite"+new_Ligne.idLigne+"' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' onchange='modifier_Unite(this.value)' >" +
                                "<option  value='Article<>"+produit.prix+"<>"+new_Ligne.idLigne+"'> Article </option>"+
                            "</select>";
                        }
                        else{
                            unite = "<select id='slct_Ligne_Unite"+new_Ligne.idPagnet+"' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' onchange='modifier_Unite(this.value)' >" +
                                "<option  value='Article<>"+produit.prix+"<>"+new_Ligne.idLigne+"'> Article </option>"+    
                                "<option value='"+produit.uniteStock+"<>"+produit.prixuniteStock+"<>"+new_Ligne.idLigne+"'> "+produit.uniteStock+ "</option>"+
                            "</select>";
                        }
                        quantite = "<input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Quantite"+new_Ligne.idLigne+"' onkeyup='modifier_Quantite("+new_Ligne.idLigne+ ")' onchange='modifier_Quantite("+new_Ligne.idLigne+ ")' value='"+new_Ligne.quantite+"'  style='width: 100px' type='number' ></input>";
                        reference = new_Ligne.designation ;
                    }
                    else{
                        if(produit.classe==1 || produit.classe==2){
                            quantite = "<input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Quantite"+new_Ligne.idLigne+"' onkeyup='modifier_Quantite("+new_Ligne.idLigne+ ")' onchange='modifier_Quantite("+new_Ligne.idLigne+ ")' value='"+new_Ligne.quantite+"'  style='width: 100px' type='number' ></input>";
                            if(produit.classe==1){   
                                reference = new_Ligne.designation ;
                            }
                            else {
                                reference = "<textarea  type='textarea' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='txtarea_Ligne_Reference"+new_Ligne.idLigne+"' onkeyup='modifier_Reference("+new_Ligne.idLigne+ ")'>"+new_Ligne.designation+" - </textarea>";
                                type = 3;
                                var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                                if(data_Panier==null){ data_Panier = []; }
                                    panier_Existe = data_Panier.find(p=>p.idPagnet==idPagnet);
                                    if (panier_Existe != null) {
                                        panier_Existe.type = type;
                                        localStorage.setItem("Panier", JSON.stringify(data_Panier));
                                    }
                            }
                        }
                        else{
                            quantite = new_Ligne.quantite;
                            reference = "<textarea  type='textarea' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='txtarea_Ligne_Reference"+new_Ligne.idLigne+"' onkeyup='modifier_Reference("+new_Ligne.idLigne+ ")'>"+new_Ligne.designation+" - </textarea>";
                            type=produit.classe;
                            var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                            if(data_Panier==null){ data_Panier = []; }
                            panier_Existe = data_Panier.find(p=>p.idPagnet==idPagnet);
                            if (panier_Existe != null) {
                                panier_Existe.type = type;
                                localStorage.setItem("Panier", JSON.stringify(data_Panier));
                            }
                        }
                        unite = produit.uniteStock;
                    }

                    var ligne = "<tr id='lignePanier"+new_Ligne.idLigne+"'>" +
                        "<td>" + reference + "</td>" +
                        "<td>" +quantite+ "</td>" +
                        "<td>" +unite+ "</td>" +
                        "<td><input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Prix"+new_Ligne.idLigne+"' onkeyup='modifier_Prix("+new_Ligne.idLigne+")' onchange='modifier_Prix("+new_Ligne.idLigne+")' value='"+produit.prix+"' style='width: 100px' type='number' step='5'></input></td>" +
                        "<td class='hidden-xs hidden-sm'><input disabled='true' class='form-control inpt_Ligne_Prix_Total"+new_Ligne.idPagnet+" champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Prix_Total"+new_Ligne.idLigne+"' value='"+produit.prix+"'  style='width: 100px' type='number'></input></td>" +
                        "<td>" +
                        "<button type='button' onclick='retourner_Ligne("+new_Ligne.idLigne+","+new_Ligne.idPagnet+")' class='btn btn-warning btn_retourner_Ligne"+new_Ligne.idPagnet+"'>" +
                        "<span class='glyphicon glyphicon-remove'></span>" +
                        "</button>" +
                        "</td>" +
                    "</tr>";
                    $("#tablePanier"+new_Ligne.idPagnet).prepend(ligne);
                    afficher_Champ_Panier_Ligne(idPagnet,produit.classe,type);
                }
                else{
                    type = 10;
                    var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                    if(data_Panier==null){ data_Panier = []; }
                    panier_Existe = data_Panier.find(p=>p.idPagnet==idPagnet);
                    if (panier_Existe != null) {
                        panier_Existe.type = type;
                        localStorage.setItem("Panier", JSON.stringify(data_Panier));
                    }
                    afficher_Champ_Panier_Ligne(idPagnet,0,type);
                }
            }
        }
        calcul_Entete_Panier(idPagnet);
    }

    function ajouter_Ligne_CodeBarre(idPagnet,reference,type){
        var data_Designation = [];
        for (let i = 0; i < localStorage.length; i++) {
            if(localStorage.key(i).split('_')[0]=='Designation'){
                var produits = JSON.parse(localStorage.getItem(localStorage.key(i)));
                data_Designation=data_Designation.concat(produits);
            }
        } 
        produit=data_Designation.find(d=>d.codeBarreDesignation==reference);
        if (produit != null) {
            var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
            if(data_Ligne==null){
                data_Ligne = [];
            }
            ligne_Existe = data_Ligne.find(l=>parseInt(l.idDesignation)==parseInt(produit.idDesignation) && l.idPagnet==idPagnet);
            if (ligne_Existe != null && ligne_Existe.activer==1) {
                ligne_Existe.quantite = parseInt(ligne_Existe.quantite) + 1;
                ligne_Existe.prixtotal = ligne_Existe.quantite * ligne_Existe.prixunitevente;
                localStorage.setItem("Ligne", JSON.stringify(data_Ligne));
                $('#inpt_Ligne_Quantite'+ligne_Existe.idLigne).val(ligne_Existe.quantite);
                $('#inpt_Ligne_Prix_Total'+ligne_Existe.idLigne).val(ligne_Existe.prixtotal);
            }
            else{
                if(produit.classe!=10){
                    ligne_Panier = data_Ligne.filter(l=>l.idPagnet==idPagnet);
                    nbre_Ligne=ligne_Panier.length;
                    var new_Ligne = new Object();
                    new_Ligne.idLigne = idPagnet+''+nbre_Ligne;
                    new_Ligne.numligne = 0;
                    new_Ligne.idPagnet = idPagnet;
                    new_Ligne.idDesignation = produit.idDesignation;
                    new_Ligne.designation = produit.designation;
                    new_Ligne.unitevente = 'Article';
                    new_Ligne.prixunitevente = produit.prix;
                    new_Ligne.quantite = 1;
                    new_Ligne.prixtotal = produit.prix;
                    new_Ligne.classe = produit.classe;
                    new_Ligne.activer = 1;
                    data_Ligne.push(new_Ligne);
                    localStorage.setItem("Ligne", JSON.stringify(data_Ligne)); 

                    if(produit.classe==0){
                        if(produit.uniteStock=='Article' || produit.uniteStock=='article'){
                            unite = "<select id='slct_Ligne_Unite"+new_Ligne.idLigne+"' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' onchange='modifier_Unite(this.value)' >" +
                                "<option  value='Article<>"+produit.prix+"<>"+new_Ligne.idLigne+"'> Article </option>"+
                            "</select>";
                        }
                        else{
                            unite = "<select id='slct_Ligne_Unite"+new_Ligne.idPagnet+"' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' onchange='modifier_Unite(this.value)' >" +
                                "<option  value='Article<>"+produit.prix+"<>"+new_Ligne.idLigne+"'> Article </option>"+    
                                "<option value='"+produit.uniteStock+"<>"+produit.prixuniteStock+"<>"+new_Ligne.idLigne+"'> "+produit.uniteStock+ "</option>"+
                            "</select>";
                        }
                        quantite = "<input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Quantite"+new_Ligne.idLigne+"' onkeyup='modifier_Quantite("+new_Ligne.idLigne+ ")' onchange='modifier_Quantite("+new_Ligne.idLigne+ ")' value='"+new_Ligne.quantite+"'  style='width: 100px' type='number' ></input>";
                        reference = new_Ligne.designation ;
                    }
                    else{
                        if(produit.classe==1 || produit.classe==2){
                            quantite = "<input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Quantite"+new_Ligne.idLigne+"' onkeyup='modifier_Quantite("+new_Ligne.idLigne+ ")' onchange='modifier_Quantite("+new_Ligne.idLigne+ ")' value='"+new_Ligne.quantite+"'  style='width: 100px' type='number' ></input>";
                            if(produit.classe==1){   
                                reference = new_Ligne.designation ;
                            }
                            else {
                                reference = "<textarea  type='textarea' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='txtarea_Ligne_Reference"+new_Ligne.idLigne+"' onkeyup='modifier_Reference("+new_Ligne.idLigne+ ")'>"+new_Ligne.designation+" - </textarea>";
                                type = 3;
                                var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                                if(data_Panier==null){ data_Panier = []; }
                                panier_Existe = data_Panier.find(p=>p.idPagnet==idPagnet);
                                if (panier_Existe != null) {
                                    panier_Existe.type = type;
                                    localStorage.setItem("Panier", JSON.stringify(data_Panier));
                                }
                            }
                        }
                        else{
                            quantite = new_Ligne.quantite;
                            reference = "<textarea  type='textarea' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='txtarea_Ligne_Reference"+new_Ligne.idLigne+"' onkeyup='modifier_Reference("+new_Ligne.idLigne+ ")'>"+new_Ligne.designation+" - </textarea>";
                            type=produit.classe;
                            var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                            if(data_Panier==null){ data_Panier = []; }
                            panier_Existe = data_Panier.find(p=>p.idPagnet==idPagnet);
                            if (panier_Existe != null) {
                                panier_Existe.type = type;
                                localStorage.setItem("Panier", JSON.stringify(data_Panier));
                            }
                        }
                        unite = produit.uniteStock;
                    }

                    var ligne = "<tr id='lignePanier"+new_Ligne.idLigne+"'>" +
                        "<td>" + reference + "</td>" +
                        "<td>" +quantite+ "</td>" +
                        "<td>" +unite+ "</td>" +
                        "<td><input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Prix"+new_Ligne.idLigne+"' onkeyup='modifier_Prix("+new_Ligne.idLigne+")' onchange='modifier_Prix("+new_Ligne.idLigne+")' value='"+produit.prix+"' style='width: 100px' type='number' step='5'></input></td>" +
                        "<td class='hidden-xs hidden-sm'><input disabled='true' class='form-control inpt_Ligne_Prix_Total"+new_Ligne.idPagnet+" champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Prix_Total"+new_Ligne.idLigne+"' value='"+produit.prix+"'  style='width: 100px' type='number'></input></td>" +
                        "<td>" +
                        "<button type='button' onclick='retourner_Ligne("+new_Ligne.idLigne+","+new_Ligne.idPagnet+")' class='btn btn-warning btn_retourner_Ligne"+new_Ligne.idPagnet+"'>" +
                        "<span class='glyphicon glyphicon-remove'></span>" +
                        "</button>" +
                        "</td>" +
                    "</tr>";
                    $("#tablePanier"+new_Ligne.idPagnet).prepend(ligne);
                    afficher_Champ_Panier_Ligne(idPagnet,produit.classe,type);
                }
                else{
                    type = 10;
                    var data_Panier = JSON.parse(localStorage.getItem("Panier"));
                    if(data_Panier==null){ data_Panier = []; }
                    panier_Existe = data_Panier.find(p=>p.idPagnet==idPagnet);
                    if (panier_Existe != null) {
                        panier_Existe.type = type;
                        localStorage.setItem("Panier", JSON.stringify(data_Panier));
                    }
                    afficher_Champ_Panier_Ligne(idPagnet,0,type);
                }
            }
        }
        calcul_Entete_Panier(idPagnet);
    }

    function afficher_Ligne(idPagnet,verrouiller,panier,lignes){
        if(verrouiller==0){
            var data_Panier = JSON.parse(localStorage.getItem("Panier"));
            if(data_Panier==null){
                data_Panier = [];
            }
            data_Panier.push(panier);
            localStorage.setItem("Panier", JSON.stringify(data_Panier));
        }
        var taille = lignes.length;
        for(var i = 0; i<taille; i++){
            if(lignes[i][7]==1){
                var ligne = "<tr id='lignePanier"+lignes[i][1]+"'>"+
                "<td>"+lignes[i][2]+"</td>"+
                "<td>"+lignes[i][3]+"</td>"+
                "<td>"+lignes[i][4]+"</td>"+
                "<td>"+lignes[i][5]+"</td>"+
                "<td class='hidden-xs hidden-sm'>"+lignes[i][6]+"</td>"+
                "<td class='hidden-xs'>"+
                    "<button type='button' disabled='true' onclick='retourner_Ligne("+lignes[i][1]+","+lignes[i][0]+")' class='btn btn-warning btn_retourner_Ligne"+lignes[i][0]+"'>" +
                        "<span class='glyphicon glyphicon-remove'></span>" +
                    "</button>" +
                "</td>"+
                "</tr>";
                if(lignes[i][0]==idPagnet){
                    $("#tablePanier"+lignes[i][0]).prepend(ligne);
                }
            }
            else {
                var data_Ligne = JSON.parse(localStorage.getItem("Ligne"));
                if(data_Ligne==null){ 
                    data_Ligne = [];
                }
                var new_Ligne = new Object();
                new_Ligne.idLigne = idPagnet+''+i;
                new_Ligne.numligne = lignes[i][1];
                new_Ligne.idPagnet = idPagnet;
                new_Ligne.idDesignation = lignes[i][8];
                new_Ligne.designation = lignes[i][2];
                new_Ligne.unitevente = lignes[i][4];
                new_Ligne.prixunitevente = lignes[i][5];
                new_Ligne.quantite = lignes[i][3];
                new_Ligne.prixtotal = lignes[i][6];
                new_Ligne.classe = lignes[i][9];
                new_Ligne.activer = 1;
                data_Ligne.push(new_Ligne);
                localStorage.setItem("Ligne", JSON.stringify(data_Ligne)); 

                if(new_Ligne.classe==0){
                    if(lignes[i][10]=='Article' || lignes[i][10]=='article'){
                        unite = "<select id='slct_Ligne_Unite"+new_Ligne.idLigne+"' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' onchange='modifier_Unite(this.value)' >" +
                            "<option  value='Article<>"+lignes[i][12]+"<>"+new_Ligne.idLigne+"'> Article </option>"+
                        "</select>";
                    }
                    else{
                        unite = "<select id='slct_Ligne_Unite"+new_Ligne.idPagnet+"' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' onchange='modifier_Unite(this.value)' >" +
                            "<option  value='Article<>"+lignes[i][12]+"<>"+new_Ligne.idLigne+"'> Article </option>"+    
                            "<option value='"+lignes[i][10]+"<>"+lignes[i][11]+"<>"+new_Ligne.idLigne+"'> "+lignes[i][10]+ "</option>"+
                        "</select>";
                    }
                    quantite = "<input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Quantite"+new_Ligne.idLigne+"' onkeyup='modifier_Quantite("+new_Ligne.idLigne+ ")' onchange='modifier_Quantite("+new_Ligne.idLigne+ ")' value='"+new_Ligne.quantite+"'  style='width: 100px' type='number' ></input>";
                    reference = new_Ligne.designation ;
                }
                else{
                    if(new_Ligne.classe==1 || new_Ligne.classe==2){
                        quantite = "<input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Quantite"+new_Ligne.idLigne+"' onkeyup='modifier_Quantite("+new_Ligne.idLigne+ ")' onchange='modifier_Quantite("+new_Ligne.idLigne+ ")' value='"+new_Ligne.quantite+"'  style='width: 100px' type='number' ></input>";
                        if(new_Ligne.classe==1){   
                            reference = new_Ligne.designation ;
                        }
                        else {
                            reference = "<textarea  type='textarea' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='txtarea_Ligne_Reference"+new_Ligne.idLigne+"' onkeyup='modifier_Reference("+new_Ligne.idLigne+ ")'>"+new_Ligne.designation+" - </textarea>";
                        }
                    }
                    else{
                        quantite = new_Ligne.quantite;
                        reference = "<textarea  type='textarea' class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='txtarea_Ligne_Reference"+new_Ligne.idLigne+"' onkeyup='modifier_Reference("+new_Ligne.idLigne+ ")'>"+new_Ligne.designation+" - </textarea>";
                    }
                    unite = lignes[i][10];
                }
        
                var ligne = "<tr id='lignePanier"+new_Ligne.idLigne+"'>" +
                    "<td>" + reference + "</td>" +
                    "<td>" +quantite+ "</td>" +
                    "<td>" +unite+ "</td>" +
                    "<td><input class='form-control champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Prix"+new_Ligne.idLigne+"' onkeyup='modifier_Prix("+new_Ligne.idLigne+")' onchange='modifier_Prix("+new_Ligne.idLigne+")' value='"+new_Ligne.prixunitevente+"' style='width: 100px' type='number' step='5'></input></td>" +
                    "<td><input disabled='true' class='form-control inpt_Ligne_Prix_Total"+new_Ligne.idPagnet+" champ_saisie_Ligne"+new_Ligne.idPagnet+"' id='inpt_Ligne_Prix_Total"+new_Ligne.idLigne+"' value='"+new_Ligne.prixtotal+"'  style='width: 100px' type='number'></input></td>" +
                    "<td>" +
                    "<button type='button' onclick='retourner_Ligne("+new_Ligne.idLigne+","+new_Ligne.idPagnet+")' class='btn btn-warning btn_retourner_Ligne"+new_Ligne.idPagnet+"'>" +
                    "<span class='glyphicon glyphicon-remove'></span>" +
                    "</button>" +
                    "</td>" +
                "</tr>";
                if(lignes[i][0]==idPagnet){
                    $("#tablePanier"+new_Ligne.idPagnet).prepend(ligne);
                }
                
                //afficher_Champ_Panier_Ligne(idPagnet,new_Ligne.classe,type);

            }
        } 
    }

    function afficher_Compte(idPagnet,compte,type){
        var data_Compte = JSON.parse(localStorage.getItem("Compte"));
        if (data_Compte != null) {
            var taille = data_Compte.length;
            $('#slct_ajouter_Panier_Compte_'+idPagnet+' option').remove();
            for(var i = 0; i<taille; i++){
                if(data_Compte[i].idCompte!=2){
                    if(data_Compte[i].idCompte==compte){
                        $('#slct_ajouter_Panier_Compte_'+idPagnet).append("<option selected value='"+data_Compte[i].idCompte+"'>"+data_Compte[i].nomCompte+"</option>");
                    }
                    else{
                        $('#slct_ajouter_Panier_Compte_'+idPagnet).append("<option value='"+data_Compte[i].idCompte+"'>"+data_Compte[i].nomCompte+"</option>");
                    }
                }
            }
            $('#slct_ajouter_Bon_Compte_'+idPagnet+' option').remove();
            for(var i = 0; i<taille; i++){
                if(data_Compte[i].idCompte!=2){
                    $('#slct_ajouter_Bon_Compte_'+idPagnet).append("<option value='"+data_Compte[i].idCompte+"'>"+data_Compte[i].nomCompte+"</option>");
                }
            }
    
        }
        var nbre_Ligne=0;
        $('.inpt_Ligne_Prix_Total'+idPagnet).each(function() {
            nbre_Ligne++;
        });
        if(nbre_Ligne==0){
            if(type==10){
                $('#panelPanier'+idPagnet).attr("class", "panel panel-warning");
            }
            else if(type==6 || type==9){
                $('#panelPanier'+idPagnet).attr("class", "panel panel-info");
            }
            else if(type==11){
                $('#panelPanier'+idPagnet).attr("class", "panel panel-default");
            }
            else {
                $('#panelPanier'+idPagnet).attr("class", "panel panel-primary");
            }
        }
        $('#inpt_ajouter_Panier_Ligne_'+idPagnet).focus();
    }

    function afficher_Champ_Panier(idPagnet){
        var type = $('#panelPanier'+idPagnet).attr("panier-type");
        var compte = $('#panelPanier'+idPagnet).attr("panier-compte");
        if(type==10){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-warning");
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==6  || type==9){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-info");
            $('#inpt_ajouter_Panier_Ligne_'+idPagnet).hide();
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==11){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-default");
            $('#inpt_ajouter_Panier_Ligne_'+idPagnet).hide();
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==0){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-success");
            $('.champ_saisie_Bon'+idPagnet).show();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        afficher_Compte(idPagnet,compte,type);
    }

    function afficher_Champ_Panier_Compte(idPagnet,compte,type){
        if(type==10){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-warning");
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==6  || type==9){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-info");
            $('#inpt_ajouter_Panier_Ligne_'+idPagnet).hide();
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==11){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-default");
            $('#inpt_ajouter_Panier_Ligne_'+idPagnet).hide();
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==0){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-success");
            $('.champ_saisie_Bon'+idPagnet).show();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        afficher_Compte(idPagnet,compte,type);
    }

    function afficher_Champ_Panier_Ligne(idPagnet,classe,type){
        $('#panelPanier'+idPagnet).attr("panier-type",type);
        var compte = $('#panelPanier'+idPagnet).attr("panier-compte");
        if(type==10){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-warning");
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==6  || type==9){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-info");
            $('#inpt_ajouter_Panier_Ligne_'+idPagnet).hide();
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==11){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-default");
            $('#inpt_ajouter_Panier_Ligne_'+idPagnet).hide();
            $('#inpt_ajouter_Panier_Remise_'+idPagnet).hide();
            $('#slct_ajouter_Panier_Compte_'+idPagnet).hide();
            $('.champ_saisie_Bon'+idPagnet).hide();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        else if(type==0 && (classe==0 || classe==1)){
            $('#panelPanier'+idPagnet).attr("class", "panel panel-success");
            $('.champ_saisie_Bon'+idPagnet).show();
            $('.champ_saisie_Caisse'+idPagnet).hide();
            $('.champ_saisie_Frais'+idPagnet).hide();
            $('.spn_ajouter_Panier_Rendu'+idPagnet).hide();
        }
        afficher_Compte(idPagnet,compte,type);
    }

    function calcul_Panier_Non_Termine(){
        var nombre = 0;
        $('.panel').each(function() {
           var verrouiller=$(this).attr("panier-verrouiller");
           if(verrouiller==0){
             nombre++;
           }
        });
        if(nombre>=2){
            $("#btn_Ajouter_Panier").prop("disabled", true);
        }
        else{
            $("#btn_Ajouter_Panier").prop("disabled", false);
        }
    }

    function nombre_Ligne_Panier(idPagnet){
        var nombre = 0;
        $('.inpt_Ligne_Prix_Total'+idPagnet).each(function() {
            nombre ++;
        });
        return nombre;
    }

    function couleur_Panier_Non_Termine(idPagnet){
        $('.panel').each(function() {
            var panel=$(this).attr("id");
            idPanier=panel.split('r')[1];
            var verrouiller = $('#panelPanier'+idPanier).attr("panier-verrouiller");
            if(verrouiller==0){
               var panel_colls = $('#'+idPanier).attr("class");
               if(panel_colls=='panel-collapse collapse in'){ 
                    $('#panelPanier'+idPanier).attr("class", "panel panel-primary");
               }else{
                   if(idPanier==idPagnet){
                        afficher_Champ_Panier(idPanier);
                   }
                   else{
                    $('#panelPanier'+idPanier).attr("class", "panel panel-primary");
                   } 
               }
            }
        });
    }

    $(function(){
        ticket_Panier= function(idPagnet)  {
            window.open("venteTicket.php?id="+idPagnet, "_blank");
            $("#inpt_ajouter_Panier_Ligne_"+idPagnet).focus();
        }
    });

    $(function(){
        facture_Panier= function(idPagnet)  {
            window.open("venteFacture.php?id="+idPagnet, "_blank");
            $("#inpt_ajouter_Panier_Ligne_"+idPagnet).focus();
        }
    });

    $(function(){
        ajouter_Versement= function ()  {
            $.ajax({
                url:"calculs/ventesBons.php",
                method:"POST",
                data:{
                    operation:'ajouter_Versement',
                    idClient : id,
                    dateDebut: dateDebut,
                    dateFin: dateFin,
                },
                success: function (data) {
                    var details = JSON.parse(data);
                    form_Btn="<form class='form-inline noImpr' method='post' id='form_ajouter_Versement_Btn_"+details[0]+"'>"+
                    "<button tabindex='1' type='button'  onclick='retourner_Versement("+details[0]+")' class='btn btn-danger pull-right' id='btn_retourner_Versement"+details[0]+"'  data-toggle='modal' data-target='#msg_ann_versement"+details[0]+"' >"+
                       "<span class='glyphicon glyphicon-remove'></span>"+
                           "</button>"+
                           "<button type='button' onclick='valider_Versement("+details[0]+")' data-idVersement='"+details[0]+"' id='btn_valider_Versement"+details[0]+"' class='btn btn-success btn_valider_Versement"+details[0]+" pull-right' style='margin-right:2px;'>"+
                               "<span class='glyphicon glyphicon-ok'></span>"+
                           "</button>"+
                       "</form>";
                   var versement ="<div style='margin-bottom:5px;' class='panel panel-warning' id='panelVersement"+details[0]+"' versement-compte='0' >"+
                   "<div class='panel-heading'>"+
                   "<h4 style='padding-bottom: 18px;' data-toggle='collapse' data-parent='#accordion' href='#"+details[0]+"'  class='panel-title expand'>"+
                       "<div class='right-arrow pull-right'>+</div>"+
                       "<a href='#'  onclick='detailsVersement("+details[0]+")'>"+ 
                       "<span class='hidden-xs hidden-sm col-md-2 col-lg-1'><span>N</span> 0</span>"+
                       "<span class='hidden-xs col-sm-4 col-md-3 col-lg-3'><span>Date</span> : "+details[1]+" "+details[2]+"</span>"+
                       "<span class='hidden-xs hidden-sm hidden-md col-lg-2'><span>Montant</span> : <span id='spn_ajouter_Versement_Total"+details[0]+"' >"+0+" </span></span>"+
                       "<span class='col-xs-6 col-sm-3 col-md-3 col-lg-2'> <span>Net HT</span> : <span id='spn_ajouter_Versement_APayer"+details[0]+"'>"+0+" </span></span>"+
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
                               "<button type='button' style='margin-right:5px' onclick='retourner_Versement("+details[0]+")' class='btn btn-round btn-danger' id='btn_retourner_Versement"+details[0]+"' style='margin-right: 5px;' data-toggle='modal' data-target=''> <span>Retourner</span></button>"+
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
                                   "<td><textarea  type='textarea' class='form-control champ_saisie_Ligne"+details[0]+"' id='txtarea_Versement_Description_"+details[0]+"'  onkeyup='modifier_Description("+details[0]+")'> </textarea></td>" +
                                   "<td><input class='form-control champ_saisie_Ligne"+details[0]+"' id='inpt_Versement_Montant_"+details[0]+"'  onkeyup='modifier_Montant("+details[0]+")' onchange='modifier_Montant("+details[0]+")' value='0' style='width: 150px' type='number' step='5'></input></td>" +
                                   "<td>"+
                                       "<select class='form-control champ_saisie_Ligne"+details[0]+"' id='slct_Versement_Compte_"+details[0]+"' onchange='modifier_Compte(this.value)' style='width: 150px' ></select>"+
                                   "</td>" +
                                   "<td>"+details[1]+"</td>"+
                                   "<td id='champ_image"+details[0]+"'>"+
                                       "<img class='btn btn-xs' src='images/upload.png' align='middle' alt='apperçu' width='50' height='45' data-toggle='modal' data-target='#imageNvVersement"+details[0]+"'  onclick='showImageVersement("+details[0]+")' 	 />"+
                                   "</td>" +
                                   "<td class='champ_modifier_Versement"+details[0]+"' style='display:none' >"+
                                        "<button type='button' class='btn btn-warning pull-right' onclick='modifier_Versement("+details[0]+")' >"+
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
                                        "<button type='button' class='btn btn-success pull-right' onclick='valider_Versement("+details[0]+")'>"+
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
                 $("#listerVentes #accordion").prepend(versement);
                 var data_Compte = JSON.parse(localStorage.getItem("Compte"));
                 if (data_Compte != null) {
                    var taille = data_Compte.length;
                    $('#slct_Versement_Compte_'+details[0]+' option').remove();
                    for(var i = 0; i<taille; i++){
                        if(data_Compte[i].idCompte!=2){
                            $('#slct_Versement_Compte_'+details[0]).append("<option value='"+data_Compte[i].idCompte+"'>"+data_Compte[i].nomCompte+"</option>");
                        }
                    }
                 }

                },
                error: function() {
                    alert("La requête "); },
                dataType:"text"
            }); 
        }
    });

    $(function(){
        detailsVersement= function (idVersement)  {
           
        }
    });

    $(function(){
        modifier_Montant= function (idVersement)  {
            var montant=$('#inpt_Versement_Montant_'+idVersement).val();
            if(montant>0){
            }
            else{
                $('#inpt_Versement_Montant_'+idVersement).val('');
            }
            $('#spn_ajouter_Versement_Total'+idVersement).text(montant); 
            $('#spn_ajouter_Versement_APayer'+idVersement).text(montant); 
        }
    });

    $(function(){
        valider_Versement= function(idVersement)  {
            $("#btn_valider_Versement"+idVersement).prop("disabled", true); 
            var description=$('#txtarea_Versement_Description_'+idVersement).val();
            var montant=$('#inpt_Versement_Montant_'+idVersement).val();
            var idCompte=$('#slct_Versement_Compte_'+idVersement+' option:selected').val();
            if(montant>0){
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
                    url:"calculs/ventesBons.php",
                    method:"POST",
                    data:{
                        operation:'valider_Versement',
                        idVersement: idVersement,
                        idClient : id,
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
                        var montant=montant_client.split('<>')[0];
                        var bons=montant_client.split('<>')[1];
                        var versements=montant_client.split('<>')[2];
                        $('#spn_montant_Total').text(montant);
                        $('#spn_montant_Bons').text(bons);
                        $('#spn_montant_Versements').text(versements);
                    },
                    error: function() {
                        alert("La requête "); },
                    dataType:"text"
                });
            }
            else{
                $('#inpt_Versement_Montant_'+idVersement).val('');
            }
            $("#btn_valider_Versement"+idVersement).prop("disabled", false); 
        }
    });

    $(function(){
        modifier_Versement= function(idVersement)  {
            var idCompte=$('#panelVersement'+idVersement).attr("versement-compte");
            $('#ancien_Versement'+idVersement).hide();
            $('#nouveau_Versement'+idVersement).show();
            var data_Compte = JSON.parse(localStorage.getItem("Compte"));
            if (data_Compte != null) {
                var taille = data_Compte.length;
                $('#slct_Versement_Compte_'+idVersement+' option').remove();
                for(var i = 0; i<taille; i++){
                    if(data_Compte[i].idCompte!=2){
                        if(data_Compte[i].idCompte==idCompte){
                            $('#slct_Versement_Compte_'+idVersement).append("<option selected value='"+data_Compte[i].idCompte+"'>"+data_Compte[i].nomCompte+"</option>");
                        }
                        else{
                            $('#slct_Versement_Compte_'+idVersement).append("<option value='"+data_Compte[i].idCompte+"'>"+data_Compte[i].nomCompte+"</option>");
                        }
                    }
                }
            }
        }
    });

    $(function(){
        retourner_Versement= function(idVersement)  {
            $('#inpt_retourner_Versement').val(idVersement);
            $('#spn_hd_action_Versement').text('Retourner Versement');
            $('#spn_bd_action_Versement').text('Voulez vous vraiment retourner ce Versement ?');
            $('#btn_action_Versement').text('Retourner');
            $('#action_Versement').modal('show');
        }
    });

    $("#btn_retourner_Versement").on( "click", function (e){
        idVersement = $('#inpt_retourner_Versement').val();
        $('#panelVersement'+idVersement).each(function() {
            $(this).remove();
        });
        $('#action_Versement').modal('hide');
        $.ajax({
            url:"calculs/ventesBons.php",
            method:"POST",
            data:{
                operation:'retourner_Versement',
                idVersement: idVersement,
                idClient: id,
                dateDebut: dateDebut,
                dateFin: dateFin,
            },
            success: function (data) {
                var details = JSON.parse(data);
                montant_client=details[1];
                var montant=montant_client.split('<>')[0];
                var bons=montant_client.split('<>')[1];
                var versements=montant_client.split('<>')[2];
                $('#spn_montant_Total').text(montant);
                $('#spn_montant_Bons').text(bons);
                $('#spn_montant_Versements').text(versements);
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    });

    $("#btn_upload_Versement").on( "click", function (e){
        var form = document.getElementById('form_upload_Versement'); //id of form
        var formdata = new FormData(form);
        $('#image_Versement').modal('hide');
        $.ajax({
            url:"calculs/ventesBons.php",
            type: "POST",
            data:  formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                var details = JSON.parse(data);
                if(details[1]!=''){
                    if(details[1]=='pdf'){ 
                        cols_image="<img class='btn btn-xs' src='images/pdf.png' align='middle' alt='apperçu' width='50' height='45' data-toggle='modal' data-target='#image_Versement"+details[0]+"'  onclick='showImageVersement("+details[0]+")'/>"+
                        "<input style='display:none;' data-image='"+details[0]+"'  id='imageVersement"+details[0]+"'  value='"+details[2]+"' />";
                    }
                    else { 
                        cols_image="<img class='btn btn-xs' src='images/img.png' align='middle' alt='apperçu' width='50' height='45' data-toggle='modal' data-target='#image_Versement"+details[0]+"'  onclick='showImageVersement("+details[0]+")'/>"+
                        "<input style='display:none;' data-image='"+details[0]+"'  id='imageVersement"+details[0]+"'  value='"+details[2]+"' />";
                    }
                    $('#champ_image'+details[0]).replaceWith($("<td />").html(cols_image));
                }
            },
            error: function(e) {
              
            }          
          });
    });

    function ajouter_Avance(idVersement,montant,compte,date,dateHeure){
        form_Btn="<form class='form-inline noImpr' method='post' id='form_ajouter_Versement_Btn_"+idVersement+"'>"+
        "<button tabindex='1' type='button'  onclick='retourner_Versement("+idVersement+")' class='btn btn-danger pull-right' id='btn_retourner_Versement"+idVersement+"'  data-toggle='modal' data-target='#msg_ann_versement"+idVersement+"' >"+
           "<span class='glyphicon glyphicon-remove'></span>"+
               "</button>"+
               "<button type='button' onclick='valider_Versement("+idVersement+")' data-idVersement='"+idVersement+"' id='btn_valider_Versement"+idVersement+"' class='btn btn-success btn_valider_Versement"+idVersement+" pull-right' style='margin-right:2px;'>"+
                   "<span class='glyphicon glyphicon-ok'></span>"+
               "</button>"+
           "</form>";
       var versement ="<div style='margin-bottom:5px;' class='panel panel-warning' id='panelVersement"+idVersement+"' versement-compte='0' >"+
       "<div class='panel-heading'>"+
       "<h4 style='padding-bottom: 18px;' data-toggle='collapse' data-parent='#accordion' href='#"+idVersement+"'  class='panel-title expand'>"+
           "<div class='right-arrow pull-right'>+</div>"+
           "<a href='#'  onclick='detailsVersement("+idVersement+")'>"+ 
           "<span class='hidden-xs hidden-sm col-md-2 col-lg-1'><span>N</span> 0</span>"+
           "<span class='hidden-xs col-sm-4 col-md-3 col-lg-3'><span>Date</span> : "+dateHeure+"</span>"+
           "<span class='hidden-xs hidden-sm hidden-md col-lg-2'><span>Montant</span> : <span id='spn_ajouter_Versement_Total"+idVersement+"' >"+montant+" </span></span>"+
           "<span class='col-xs-6 col-sm-3 col-md-3 col-lg-2'> <span>Net HT</span> : <span id='spn_ajouter_Versement_APayer"+idVersement+"'>"+montant+" </span></span>"+
           "</a>"+
       "</h4>"+
       "</div>"+
       "<div id='"+idVersement+"'  class='panel-collapse collapse in'>"+
       "<br>"+
       "<div class='content-panel' style='margin-left:12px;margin-right:12px'>"+
           "<div class='cacher_btn_Terminer col-lg-11 col-md-11' id='cacher_btn_Terminer"+idVersement+"'>"+
               "<div class='pull-right' style='margin-bottom:10px;'>"+form_Btn+"</div>"+
           "</div>"+
           "<table class='table table-bordered' id='tableVersement"+idVersement+"' >"+
               "<h4 style='display:none' class='pull-right' id='afficher_btn_Ticket"+idVersement+"'>"+
                   "<button type='button' style='margin-right:5px' class='btn btn-round btn-warning' onclick='ticket_Versement("+idVersement+")'> <span>Ticket</span></button>"+
                   "<button type='button' style='margin-right:5px' class='btn btn-round btn-info' onclick='facture_Versement("+idVersement+")'> <span>Facture</span></button>"+
                   "<button type='button' style='margin-right:5px' onclick='retourner_Versement("+idVersement+")' class='btn btn-round btn-danger' id='btn_retourner_Versement"+idVersement+"' style='margin-right: 5px;' data-toggle='modal' data-target=''> <span>Retourner</span></button>"+
               "</h4>"+
               "<thead>"+
                   "<tr>"+
                   "<th><span>Description</span></th>"+
                   "<th><span>Montant</span></th>"+
                   "<th><span>Compte</span></th>"+
                   "<th><span>Date</span></th>"+
                   "<th><span>Pieces Jointes</span></th>"+
                   "<th class='champ_modifier_Versement"+idVersement+"' style='display:none'></th>"+
                   "</tr>"+
               "</thead>"+
               "<tbody>"+
                   "<tr id='ligneVersement"+idVersement+"'>" +
                       "<td>Avance Client</td>" +
                       "<td>"+montant+"</td>" +
                       "<td>"+compte+"</td>" +
                       "<td>"+date+"</td>"+
                       "<td id='champ_image"+idVersement+"'>"+
                           "<img class='btn btn-xs' src='images/upload.png' align='middle' alt='apperçu' width='50' height='45' data-toggle='modal' data-target='#imageNvVersement"+idVersement+"'  onclick='showImageVersement("+idVersement+")' 	 />"+
                       "</td>" +
                       "<td class='champ_modifier_Versement"+idVersement+"' style='display:none' >"+
                            "<button type='button' class='btn btn-warning pull-right' onclick='modifier_Versement("+idVersement+")' >"+
                                "<span class='glyphicon glyphicon-pencil'></span>"+
                            "</button>"+
                       "</td>"+
                   "</tr>"+
/*                                "<tr style='display:none' id='nouveau_Versement"+idVersement+"'>"+
                    "<form class='form-inline noImpr'  method='post' >"+
                        "<td><textarea  type='textarea' id='txtarea_Versement_Description_"+idVersement+"' class='form-control champ_saisie_Ligne"+idVersement+"' name='paiement' placeholder='Description' >"+idVersement+"</textarea></td>"+
                        "<td><input type='number' id='inpt_Versement_Montant_"+idVersement+"' class='form-control champ_saisie_Ligne'"+idVersement+"' value='"+idVersement+"' onkeyup='modifier_Montant("+idVersement+")' onchange='modifier_Montant("+idVersement+")' style='width: 150px' ></td>"+
                        "<td><select id='slct_Versement_Compte_"+idVersement+"' onchange='modifier_Compte(this.value)' class='form-control champ_saisie_Ligne"+idVersement+"'  style='width: 150px' ></select></td>"+
                        "<td><input type='text' id='inpt_Versement_Date_"+idVersement+"' value='.$dateVersement.'  class='form-control champ_saisie_Ligne"+idVersement+"'  name='dateVersement' placeholder='jj-mm-aaaa' required=''></td>"+
                        "<td></td>"+
                        "<td>"+
                            "<input type='hidden' name='idVersement' value='"+idVersement+"' >"+
                            "<button type='button' class='btn btn-success pull-right' onclick='valider_Versement("+idVersement+")'>"+
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
     $("#listerVentes #accordion").prepend(versement);

    }

    function calcul_Montant_Client(){
        $.ajax({
            url:"calculs/ventesBons.php",
            method:"POST",
            data:{
                operation:'calcul_Montant',
                idClient: id,
                dateDebut: dateDebut,
                dateFin: dateFin,
            },
            success: function (data) {
                var details = JSON.parse(data);
                var montant=details.split('<>')[0];
                var bons=details.split('<>')[1];
                var versements=details.split('<>')[2];
                $('#spn_montant_Total').text(montant);
                $('#spn_montant_Bons').text(bons);
                $('#spn_montant_Versements').text(versements);
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        }); 
    }

});
