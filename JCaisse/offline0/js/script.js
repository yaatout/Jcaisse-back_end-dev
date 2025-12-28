/*************** Début chargement container vente ********************/

$(document).ready(function() {

    // alert(1)

    // alert(100)$("html,body").scrollTop(0);



     $("html,body").animate({scrollTop: 0}, 5);

    //executes code below when user click on pagination links

    $(document).on( "click", ".paginationLoader a", function (e){

        // $("#resultsProductsPrix").on( "click", function (e){

            // alert(2)

        e.preventDefault();

        // $(".loading-div").show(); //show loading element

        // page = page+1; //get page number from link

        page = $(this).attr("data-page"); //get page number from link

        alert(page)

        

        $("#img-load-page"+page).show();

        //  alert(page)

            

        // $(".loading-gif").show();



        $("#venteContent" ).load("ajax/loadContainerAjax.php", {"page":page}, function(data){ //get content from PHP page

            // alert(data)

            $("#img-load-page"+page).hide();

            // $("#loading_gif_modal").modal("hide");

        });

        // $("#resultsProductsPrix").load("ajax/listerPrixAjax.php",{"page":page,"operation":1}

    });

    $(function() { 

        typeData = '';
    
        $(document).on("keyup", ".clientInput", function(e) {
    
            e.preventDefault();
    
            typeClient = 'simple';
    
            idPanier = $(this).attr('data-idPanier');
    
            typeData = $(this).attr('data-type');
    
            var query = $(this).val();
    
            if (query.length > 0) {
    
    
                /*********** Modification **************/
                const data=JSON.parse(localStorage.getItem("Client"));
                //var lesClients=JSON.parse(localStorage.getItem("Client"));
                var tab =new Array();
                if(data!=null){
                    
                    for(var i=0; i<data.length; i++){
                        tab[i]= data[i].idClient+"."+data[i].prenom+" "+data[i].nom;
                    }    
                    
                }
    
                $(this).typeahead({
    
                    source: function(query, result) {  
                        
                        
                            dataType: "json",
  
                                result($.map(tab, function(item) {
                                    
                                return item;
    
                                }))
                            }

                });
                
    
                $(this).focus();
    
                /*********** Modification **************/
    
                var keycode = (e.keyCode ? e.keyCode : e.which);
    
                if (keycode == '13') {
    
                    var text = $("#factForm .typeahead li.active").text() || $("#factFormM .typeahead li.active").text();
    
                    tab = text.split(' . ');
    
                    idClient = tab[0];
                    //alert(idClient)
                    //$("#clientInput"+idPanier).val(tab[1]);

                    lesClients=getClient();
                    searchClient=lesClients.find(c =>c.idClient==parseInt(idClient)); 
                     
                    if(searchClient != undefined){
                        
                        //localStorage.setItem('idCompte',searchCompte.idCompte);
                        var paniers=getPagnet();

                        var searchPanier=paniers.find(p =>p.idPagnet==idPanier);
                        
                        if(searchPanier != undefined){
                            searchPanier.idClient=searchClient.idClient;
                        }
                        savePanier(paniers) 

                    }

                    $("#avanceInput"+idPanier).focus();
    
                }
    
            }
            localStorage.setItem('typeVente', 'simple');
        });
    
    
    });


    $(document).on("keyup", ".avanceInput", function() {

        var avance = $(this).val();        

        var idPanier = $(this).attr('data-idPanier');        

        var total = $("#somme_Apayer"+idPanier).text();
        
        //localStorage.setItem('avance',avance)

        var paniers=getPagnet();
        
        var searchPanier=paniers.find(p =>p.idPagnet==idPanier);
        
        if(searchPanier != undefined){
            searchPanier.avance=avance;
        }
        savePanier(paniers) 

        if (avance > parseInt(total)) {

            v = $('#avanceInput'+idPanier).val();

            // $('#avanceInput'+idPanier).blur();

            $('#avanceInput'+idPanier).val(v.substring(0, v.length-1));

            //localStorage.setItem('avance',v.substring(0, v.length-1))

            if(searchPanier != undefined){
                searchPanier.avance=v.substring(0, v.length-1);
            }
            savePanier(paniers) 

            $('#msg_info_avance').modal('show');

        } 

    });


    $(document).on('click', ".modeEditionBtn", function() {

        // $("#loading_gif_modal").modal("show");

        $(".img-load-editer").show();

        var id = $(this).attr('id');
        //alert(id)

        result = id.split('-');

        idPagnet = result[1]

        localStorage.setItem('IdPanier', idPagnet)

        var lesPaniers=JSON.parse(localStorage.getItem("pagnet"));
        date=new Date();
        datejour=date.toLocaleDateString("es-CL");
                            
        if(lesPaniers!=null){

            var searchPanier=lesPaniers.find(p =>p.idPagnet==idPagnet);
        
                if(searchPanier != undefined){
                    searchPanier.verrouiller=0;
                    
                }else{
                    alert("Erreur");
                }
                savePanier(lesPaniers);
                $("#idPanier").load("container.html");


        }

    });


    $(document).on("keyup", ".inputRetourApres", function(e) {  

        e.preventDefault();

        // alert(12354)

        var keycode = (e.keyCode ? e.keyCode : e.which);



        if (keycode == '13') {

            // $("#loading_gif_modal").modal("show");

            $(".img-load-retourApres").show();

            numligne=$(this).attr('data-numligne');

            

        }

    }) 
 
    
    $(document).on("click", ".btnRetourApres", function(e) {  

        

        // $("#loading_gif_modal").modal("show");

        $(".img-load-retourApres").show();

        $(".btnRetourApres").attr("disabled","disabled");

        numligne=$(this).attr('data-numligne');
        retour_Produit(numligne)
        $(".modal-backdrop").remove();
        $("#idPanier").load("container.html");


    })


    $(document).on("click", ".btnRetourAvant", function(e) {  


        // $("#loading_gif_modal").modal("show");

        $(".img-load-retourAvant").show();

        $(".btnRetourAvant").attr("disabled","disabled");

        numligne=$(this).attr('data-numligne');

        // $("#msg_rtrnAvant_ligne"+numligne).modal('hide')
        retour_Produit(numligne)
        $(".modal-backdrop").remove();

        
        $("#idPanier").load("container.html");

    })


    $(document).on("click", ".btnRetournerPagnet", function(e) {  

        

        // $("#loading_gif_modal").modal("show");

        //$(".img-load-retournerPanier").show();

        $(".btnRetournerPagnet").attr("disabled","disabled");

        idpanier=$(this).attr('data-idPanier');

        var panier=getPagnet();
        
        var searchPanier=panier.find(p =>p.idPagnet==idpanier);
        
        if(searchPanier != undefined){
            searchPanier.type=2;
            
        }else{
            alert("Erreur");
        }
        savePanier(panier);

        $(".modal-backdrop").remove();

        $("#idPanier").load("container.html");

        
    })   


    $(document).on("click", ".btnAnnulerPagnet", function(e) {  
   
        //alert(1);
        // $("#loading_gif_modal").modal("show");

        //$(".img-load-annulerPanier").show();

        $(".btnAnnulerPagnet").attr("disabled","disabled");

        var idPanier=$(this).attr('data-idPanier');

        panier=getPagnet();
        ligne=getLigne();
        
        paniers=panier.filter(p=>p.idPagnet!=idPanier);
      
        
        lignes=ligne.filter(l=>l.idPagnet!=idPanier)
        //alert("ligne: "+lignes)
        saveLigne(lignes)
        savePanier(paniers);
        
        //alert(panier);
         
        $(".modal-backdrop").remove();
        $("#idPanier").load("container.html");
    })
 

    $(document).on("click", ".btn_Termine_Panier", function(e) { 

        
        //somme_A_payer();
        var idPanier=$(this).attr('data-idPanier');
    

        // $("#loading_gif_modal").modal("show");

        remise= ($('#val_remise1'+idPanier).val()) ? $('#val_remise1'+idPanier).val() : 0 ;

        
        $("#pagnet"+idPanier).attr('class', "panel-collapse collapse in");

        
        versement=localStorage.getItem("versement")
        idClient=localStorage.getItem('idClient');
        total_p = $("#somme_Total"+idPanier).text();
       // alert(versement);

        apayer_p = $("#somme_Apayer"+idPanier).text();

        var paniers=getPagnet();
        
        var searchPanier=paniers.find(p =>p.idPagnet==idPanier);
        
        if(searchPanier != undefined){
            searchPanier.verrouiller=1;
            searchPanier.totalp=total_p;
            searchPanier.apayerPagnet=apayer_p;
            searchPanier.remise=remise;
            searchPanier.versement=parseInt(versement);
            //searchPanier.idCompte=idCompte;
            searchPanier.idClient=idClient;
            
            if(versement==0){
                searchPanier.restourne=0;
            }
            else{
                searchPanier.restourne=parseInt(versement-apayer_p);
            }
            if(searchPanier.idCompte==2){
                searchPanier.type=30;
            }
            else{
                searchPanier.type=0;
            }
            searchPanier.dejaTerminer=1;

            
            
        }else{
            alert("Erreur");
        }
        savePanier(paniers);

        $("#somme_Total"+idPanier).text(somme_A_payer());
        $("#somme_Apayer"+idPanier).text(somme_A_payer());
        //document.getElementById('total').innerHTML ='<div class="panel-group">'+

        '<div class="panel" style="background:#cecbcb;">'+

            '<div class="panel-heading" >'+

                '<h4 class="panel-title">'+

                '<a data-toggle="collapse" href="#collapse1" id="totalV">Total opérations : '+totalVente()+',00 F CFA'+'</a>'+

                '</h4>'+

            '</div>'+

            

        '</div>'+

    '</div>';
    

        $("#idPanier").load("container.html"); 

       

    }) 


    $(document).on("click", "#addPanier", function(e) { 
        
        addPagnet();         
        localStorage.setItem("versement",0)
        localStorage.setItem('idCompte2',1);
        localStorage.setItem('idClient',0);
        localStorage.setItem('typeVente', 'bon')
        
        $("#idPanier").load("container.html");                           
        
              
    });

    $(function(){
        //boutiques=getBoutique();
        //searchBoutique=boutiques.find(b=>b.idBoutique == localStorage.idBoutique);
        //alert(searchBoutique)
        // if(searchBoutique.offline ==1){
            
        //     $(".menu a").each(function(){
                
        //         if($(this).hasClass("disabled")){
        //         $(this).removeAttr("href");
        //         }
        //     });
     
        // }

        date = new Date();
        sec = date.getSeconds();
        setTimeout(()=>{
            setInterval(()=>{
                if(navigator.onLine==false){
                    $(".menu a").each(function(){
                
                        if($(this).hasClass("disabled")){
                        $(this).removeAttr("href");
                        }
                    });
                    $('.offline').hide()
                    
                }else{
                    $('.offline').show() 
                }

            }, 60 * 1);
        }, (60 - sec) * 1);
         
        $('.caissier').hide()


       
    }); 
    

      window.onload = function() {
        $("#pagnet"+idPanier).collapse("show");
      }

    
    
    //$(".modeEditionBtn").attr("disabled","disabled");
    

});

/*************** Fin chargement container vente ********************/

/** Debut Autocomplete Vente Boutique*/
$(function() {
 

    idPanier =0;

    $(document).on("keyup", ".codeBarreLigneVt",function(e) {

        e.preventDefault();

        var tabIdPanier = $(this).attr('id');

        tab = tabIdPanier.split('_');

        idPanier = tab[1];

        var query = $(this).val();
        
        //alert(query);

        if (query.length > 0) {

            const data=JSON.parse(localStorage.getItem("Designation"));
            var tab =new Array();
            if(data!=null){
                for(var i=0; i<data.length; i++){
                    tab[i]= data[i].designation;
                }    
                
            }

            $(this).typeahead({
                
                source: function(query, result) {
                    dataType: "json",
                    result($.map(tab, function(item) {

                        return item;

                    }))
                }

            });

            $(this).focus(); 
            
            /*********** Quand on tape sur Entrer **************/

            var keycode = (e.keyCode ? e.keyCode : e.which);

            // alert(keycode)

            if (keycode == '13') {

                inputVal = $(this).val();
                if ($.isNumeric(inputVal)) {
                    codebarre = $(this).val();
                    //alert(codebarre)
                    $("#panier_"+idPanier).val('');

                    //$(".btn_Termine_Panier").attr("disabled","disabled");

                    const design=JSON.parse(localStorage.getItem("Designation"));

                        
                    if(design!=null){
                        for(var i=0; i<design.length;i++){
                            
                            if(design[i].codeBarreDesignation==codebarre){
                                var searchDesignation=design.find(d =>d.codeBarreDesignation==codebarre);
                                var lignes=getLigne();
                                //$(".btn_Termine_Panier").removeAttr("disabled");
                                var searchLigne=lignes.find(l =>l.designation==searchDesignation.designation);
                                if(searchLigne != undefined){
                                    var lignePExiste=0;
                                    var lignePQuantite=0;
                                    var lignePrixUniteVente='';
                                    var lignePrixUniteStock=0;
                                    for(var j=0; j<lignes.length;j++){
                                        if(lignes[j].idPagnet==idPanier && lignes[j].designation==searchDesignation.designation){
                                            lignePExiste=1;
                                            lignes[j].quantite++;
                                            lignePQuantite=lignes[j].quantite;
                                            lignePrixUniteVente='Article';
                                            lignePrixUniteStock=lignes[j].prixunitevente;
                                            //, 1+'<>'+idPanier+'<>'+lignes[j].numligne);

                                            break;
                                        }
                                    }
                                    if(lignePExiste==1){
                                        $("#ligneQuantite"+idPanier+""+searchDesignation.designation).val(lignePQuantite);
                                        $("#uniteVente"+idPanier+""+searchDesignation.designation).val(lignePrixUniteVente);
                                        $("#prixUniteStock"+idPanier+""+searchDesignation.designation).val(lignePrixUniteStock);
                                        saveLigne(lignes);
                                        //$("#idPanier").load("container.html");
                                    }
                                    else{
                                        var ligne = new Object();
                                        ligne.numligne = lignes.length;
                                        ligne.idPagnet =idPanier;
                                        ligne.idDesignation = design[i].idDesignation;
                                        ligne.designation = design[i].designation;
                                        ligne.idStock=0;
                                        ligne.unitevente = 'Article';
                                        ligne.prixunitevente = design[i].prix;
                                        ligne.quantite = 1;
                                        ligne.prixtotal = ligne.quantite*design[i].prix; 
                                        ligne.classe = 0;
                                        ligne.synchronise=0;
                                        ligne.prixtotalTvaP=0;
                                        ligne.prixtotalTvaR=0;
                                    
                                        if(design[i].uniteStock!="Article" && design[i].uniteStock!="article"){

                                            //alert(design[i].uniteStock)

                                            var lignePanier = "<tr>" +
                                        
                                                "<td>" +searchDesignation.designation+ "</td>" +
                                        
                                                "<td><input class='form-control filedReadonly"+idPanier+"' id='ligneQuantite"+idPanier+""+searchDesignation.designation+"' onkeyup='modif_Quantite(this.value," + ligne.numligne + " )' value='1' style='width: 30%' type='number' ></input></td>" +
                                                "<input type='hidden' id='nbreArticleUniteStock' value='"+design[i].nbreArticleUniteStock+"'/>"+
                                                "<td>" +
                                        
                                                    "<select class='form-control filedReadonly"+idPanier+"' id='uniteVente"+idPanier+""+searchDesignation.designation+"' onchange='modif_UniteStock(this.value," + ligne.numligne + " ," + design[i].nbreArticleUniteStock + ","+idPanier+"')' >" +
                                        
                                                            "<option  value='Article§"+design[i].prix+"§"+idPanier+"'>Article</option>"+
                                        
                                                            "<option value='"+design[i].uniteStock+"§"+design[i].designation+"§"+idPanier+"'>Caisse</option>"+
                                        
                                                    "</select>"+
                                        
                                                "</td>" +
                                        
                                                "<td><input  class='form-control' id='prixUniteStock"+design[i].prix+"' onkeyup='modif_Prix(this.value,"+ ligne.numligne +")' value='" + design[i].prix + "' style='width: 70%' type='number'></input></td>" +
                                        
                                        
                                                // "<td>" +
                                                //     "<button type='button' onclick='retour_Produit(" + design[i].idDesignation + "," + idPanier + ")'	 class='btn btn-warning pull-right btnRtrAvant"+idPanier+"'>" +
                                            
                                                //         "<span class='glyphicon glyphicon-remove'></span>Retour" +
                                            
                                                //     "</button>" +
                                        
                                                // "</td>" +

                                                "<td>"+

                                                    "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit"+idPanier+"' data-toggle='modal' data-target=#msg_rtrnAvant_ligne"+lignes.length+">"+

                                                            "<span class='glyphicon glyphicon-remove'></span>Retour"+

                                                    "</button>"+

                                                    "<div class='modal fade' id=msg_rtrnAvant_ligne"+lignes.length+" role='dialog'>"+

                                                        "<div class='modal-dialog'>"+

                                                            //<!-- Modal content-->

                                                            "<div class='modal-content'>"+

                                                                "<div class='modal-header panel-primary'>"+

                                                                    "<button type='button' class='close' data-dismiss='modal'>&times;</button>"+

                                                                    "<h4 class='modal-title'>Confirmation</h4>"+

                                                                "</div>"+

                                                                "<form class='form-inline noImpr' id='factForm' method='post'>"+

                                                                    "<div class='modal-body'>"+

                                                                        "<p>Voulez-vous annuler cette ligne du panier numéro <b>"+lignes.length+"</b></p>"+

                                                                        "<input type='hidden' name='idPagnet' value="+100+">"+

                                                                        // "<input type='hidden' name='designation' value=Arraw >"+

                                                                        // "<input type='hidden' name='numligne' value=2617 >"+

                                                                        // "<input type='hidden' name='idStock' value=0 >"+

                                                                        // "<input type='hidden' name='unitevente' value=article >"+

                                                                        // "<input type='hidden' name='quantite' value=1 >"+

                                                                        // "<input type='hidden' name='prixunitevente' 	value=1000 >"+

                                                                        // "<input type='hidden' name='prixtotal' value=1000 >"+

                                                                        // "<input type='hidden' name='totalp' value=0 >"+

                                                                    "</div>"+

                                                                    "<div class='modal-footer'>"+

                                                                        "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+

                                                                        "<button type='button' onclick='retour_Produit(" + ligne.numligne + "," + idPanier + ")' name='btnRetourAvant' data-numligne='2617' class='btn btn-success btnRetourAvant'>Confirmer</button>"+

                                                                    "</div>"+

                                                                "</form>"+

                                                            "</div>"+

                                                        "</div>"+

                                                    "</div>"+

                                                "</td>"+
                                        
                                            "</tr>";
                                        
                                        }
                                        else{
                                            var lignePanier = "<tr>" +
                                        
                                                "<td>" +searchDesignation.designation+ "</td>" +
                                        
                                                "<td><input class='form-control filedReadonly"+idPanier+"' id='ligneQuantite"+idPanier+""+searchDesignation.designation+"' onkeyup='modif_Quantite(this.value," + ligne.numligne + " )' value='1' style='width: 30%' type='number' ></input></td>" +
                                                "<input type='hidden' id='nbreArticleUniteStock' value='1'/>"+
                                                "<td>" +
                                        
                                                    "<select  class='form-control filedReadonly"+idPanier+"' id='uniteVente"+idPanier+""+searchDesignation.designation+"' onchange='modif_UniteStock(this.value," + ligne.numligne + " ," + design[i].nbreArticleUniteStock + ","+idPanier+"')' >" +
                                        
                                                            "<option value='Article§"+design[i].prix+"§"+idPanier+"'>Article</option>"+
                                        
                                                    "</select>"+
                                        
                                                "</td>" +
                                        
                                                "<td><input class='form-control' id='prixUniteStock"+idPanier+""+searchDesignation.designation+"' onkeyup='modif_Prix(this.value,"+ ligne.numligne +")' value='" + design[i].prixuniteStock + "' style='width: 70%' type='number'></input></td>" +
                                        
                                                // "<td>" +
                                        
                                                //     "<button type='button' onclick='retour_Produit(" + design[i].idDesignation + "," + idPanier + ")'	 class='btn btn-warning pull-right btnRtrAvant"+idPanier+"'>" +
                                            
                                                //         "<span class='glyphicon glyphicon-remove'></span>Retour" +
                                            
                                                //     "</button>" +
                                        
                                                // "</td>" +

                                                "<td>"+

                                                    "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit"+idPanier+"' data-toggle='modal' data-target=#msg_rtrnAvant_ligne"+lignes.length+">"+

                                                            "<span class='glyphicon glyphicon-remove'></span>Retour"+

                                                    "</button>"+

                                                    "<div class='modal fade' id=msg_rtrnAvant_ligne"+lignes.length+" role='dialog'>"+

                                                        "<div class='modal-dialog'>"+

                                                            //<!-- Modal content-->

                                                            "<div class='modal-content'>"+

                                                                "<div class='modal-header panel-primary'>"+

                                                                    "<button type='button' class='close' data-dismiss='modal'>&times;</button>"+

                                                                    "<h4 class='modal-title'>Confirmation</h4>"+

                                                                "</div>"+

                                                                "<form class='form-inline noImpr' id='factForm' method='post'>"+

                                                                    "<div class='modal-body'>"+

                                                                        "<p>Voulez-vous annuler cette ligne du panier numéro <b>"+lignes.length+"</b></p>"+

                                                                        "<input type='hidden' name='idPagnet' value="+100+">"+

                                                                        // "<input type='hidden' name='designation' value=Arraw >"+

                                                                        // "<input type='hidden' name='numligne' value=2617 >"+

                                                                        // "<input type='hidden' name='idStock' value=0 >"+

                                                                        // "<input type='hidden' name='unitevente' value=article >"+

                                                                        // "<input type='hidden' name='quantite' value=1 >"+

                                                                        // "<input type='hidden' name='prixunitevente' 	value=1000 >"+

                                                                        // "<input type='hidden' name='prixtotal' value=1000 >"+

                                                                        // "<input type='hidden' name='totalp' value=0 >"+

                                                                    "</div>"+

                                                                    "<div class='modal-footer'>"+

                                                                        "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+

                                                                        "<button type='button' onclick='retour_Produit(" + ligne.numligne + "," + idPanier + ")' name='btnRetourAvant' data-numligne='2617' class='btn btn-success btnRetourAvant'>Confirmer</button>"+

                                                                    "</div>"+

                                                                "</form>"+

                                                            "</div>"+

                                                        "</div>"+

                                                    "</div>"+

                                                "</td>"+
                                            
                                                "</tr>";
                                        
                                        }
                                        $("#tablePanier" + idPanier).prepend(lignePanier);

                                        lignes.push(ligne);
                                        saveLigne(lignes);
                                        
                                    }

                                }else{
                                    var ligne = new Object();
                                    ligne.numligne = lignes.length;
                                    ligne.idPagnet =idPanier;
                                    ligne.idDesignation = design[i].idDesignation;
                                    ligne.designation = design[i].designation;
                                    ligne.idStock=0;
                                    ligne.unitevente = 'Article';
                                    ligne.prixunitevente = design[i].prix;
                                    ligne.quantite = 1;
                                    ligne.prixtotal = ligne.quantite * design[i].prix;
                                    ligne.classe = 0;
                                    ligne.synchronise = 0;
                                    ligne.prixtotalTvaP=0;
                                    ligne.prixtotalTvaR=0;
                                
                                    if(design[i].uniteStock!="Article" && design[i].uniteStock!="article"){

                                        var lignePanier = "<tr>" +
                                    
                                            "<td>" +searchDesignation.designation+ "</td>" +
                                    
                                            "<td><input class='form-control filedReadonly"+idPanier+"' id='ligneQuantite"+idPanier+""+searchDesignation.designation+"' onkeyup='modif_Quantite(this.value," + ligne.numligne + " )' value='1' style='width: 30%' type='number' ></input></td>" +
                                        "<input type='hidden' id='nbreArticleUniteStock' value='"+design[i].nbreArticleUniteStock+"'/>"+
                                            "<td>" +
                                    
                                                "<select class='form-control filedReadonly"+idPanier+"' id='uniteVente1'  onchange='modif_UniteStock(this.value," + ligne.numligne + " ," + design[i].nbreArticleUniteStock + ","+idPanier+"')' >" +
                                    
                                                        "<option id='default"+design[i].uniteStock+"' value='Article§"+design[i].designation+"§"+idPanier+"'>Article</option>"+
                                    
                                                        "<option value='"+design[i].uniteStock+"§"+design[i].designation+"§"+idPanier+"'>"+design[i].uniteStock+"</option>"+
                                    
                                                "</select>"+
                                    
                                            "</td>" +
                                    
                                            "<td><input class='form-control' id='prixUniteStock"+design[i].prix+"' onkeyup='modif_Prix(this.value," + ligne.numligne + ")' value='" + design[i].prix + "' style='width: 70%' type='number'></input></td>" +
                                    
                                            "<td>"+

                                                "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit"+idPanier+"' data-toggle='modal' data-target=#msg_rtrnAvant_ligne"+lignes.length+">"+

                                                        "<span class='glyphicon glyphicon-remove'></span>Retour"+

                                                "</button>"+

                                                "<div class='modal fade' id=msg_rtrnAvant_ligne"+lignes.length+" role='dialog'>"+

                                                    "<div class='modal-dialog'>"+

                                                        //<!-- Modal content-->

                                                        "<div class='modal-content'>"+

                                                            "<div class='modal-header panel-primary'>"+

                                                                "<button type='button' class='close' data-dismiss='modal'>&times;</button>"+

                                                                "<h4 class='modal-title'>Confirmation</h4>"+

                                                            "</div>"+

                                                            "<form class='form-inline noImpr' id='factForm' method='post'>"+

                                                                "<div class='modal-body'>"+

                                                                    "<p>Voulez-vous annuler cette ligne du panier numéro <b>"+lignes.length+"</b></p>"+

                                                                    "<input type='hidden' name='idPagnet' value="+100+">"+

                                                    

                                                                "</div>"+

                                                                "<div class='modal-footer'>"+

                                                                    "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+

                                                                    "<button type='button' onclick='retour_Produit(" + ligne.numligne + "," + idPanier + ")' name='btnRetourAvant' data-numligne='2617' class='btn btn-success btnRetourAvant'>Confirmer</button>"+

                                                                "</div>"+

                                                            "</form>"+

                                                        "</div>"+

                                                    "</div>"+

                                                "</div>"+

                                            "</td>"+
                                    
                                        "</tr>";
                                        $("#tablePanier" + idPanier).prepend(lignePanier);

                                        lignes.push(ligne);
                                        saveLigne(lignes); 
                                        //localStorage.setItem("nbreArticleUniteStock", design[i].uniteStock+'<>'+design[i].nbreArticleUniteStock+'<>'+idPanier+'<>'+ligne.numligne);
                                    
                                    }
                                    else{
                                        var lignePanier = "<tr>" +
                                    
                                            "<td>" +searchDesignation.designation+ "</td>" +
                                    
                                            "<td><input class='form-control filedReadonly"+idPanier+"' id='ligneQuantite"+idPanier+""+searchDesignation.designation+"' onkeyup='modif_Quantite(this.value," + ligne.numligne + ")' value='1' style='width: 30%' type='number' ></input></td>" +
                                            "<input type='hidden' id='nbreArticleUniteStock' value='1'/>"+
                                            "<td>" +
                                    
                                                "<select class='form-control filedReadonly"+idPanier+"' id='uniteVente"+idPanier+""+searchDesignation.designation+"' onchange='modif_UniteStock(this.value," + ligne.numligne + "," + design[i].nbreArticleUniteStock + ","+idPanier+"')' >" +
                                    
                                                    "<option value='Article"+design[i].designation+"§"+idPanier+"'>Article</option>"+
                                    
                                                "</select>"+
                                    
                                            "</td>" +
                                    
                                            "<td><input disabled='true' class='form-control' id='prixUniteStock"+idPanier+""+searchDesignation.designation+"' onkeyup='modif_Prix(this.value,"+ ligne.numligne +")' value='" + design[i].prixuniteStock + "' style='width: 70%' type='number'></input></td>" +
                                    

                                            "<td>"+

                                                "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit"+idPanier+"' data-toggle='modal' data-target=#msg_rtrnAvant_ligne"+lignes.length+">"+

                                                        "<span class='glyphicon glyphicon-remove'></span>Retour"+

                                                "</button>"+

                                                "<div class='modal fade' id=msg_rtrnAvant_ligne"+lignes.length+" role='dialog'>"+

                                                    "<div class='modal-dialog'>"+

                                                        //<!-- Modal content-->

                                                        "<div class='modal-content'>"+

                                                            "<div class='modal-header panel-primary'>"+

                                                                "<button type='button' class='close' data-dismiss='modal'>&times;</button>"+

                                                                "<h4 class='modal-title'>Confirmation</h4>"+

                                                            "</div>"+

                                                            "<form class='form-inline noImpr' id='factForm' method='post'>"+

                                                                "<div class='modal-body'>"+

                                                                    "<p>Voulez-vous annuler cette ligne du panier numéro <b>"+lignes.length+"</b></p>"+

                                                                    "<input type='hidden' name='idPagnet' value="+100+">"+


                                                                "</div>"+

                                                                "<div class='modal-footer'>"+

                                                                    "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+

                                                                    "<button type='button' onclick='retour_Produit(" + ligne.numligne + "," + idPanier + ")' name='btnRetourAvant' data-numligne='"+ ligne.numligne +"' class='btn btn-success btnRetourAvant'>Confirmer</button>"+

                                                                "</div>"+

                                                            "</form>"+

                                                        "</div>"+

                                                    "</div>"+

                                                "</div>"+

                                            "</td>"+
                                    
                                        "</tr>";
                                        $("#tablePanier" + idPanier).prepend(lignePanier);

                                        lignes.push(ligne);
                                        saveLigne(lignes); 
                                        //localStorage.setItem("nbreArticleUniteStock", 'Artcle'+'<>'+1+'<>'+idPanier+'<>'+ligne.numligne);
                                    
                                    }
                                    
                                    
                                    

                                }
                                //localStorage.setItem("ligne", JSON.stringify(lignes));
                            }              
                        }
                        $("#idPanier").load("container.html"); 
                        
                        $("#somme_Total" + idPanier).text(somme_A_payer());
                                

                        $("#somme_Apayer" + idPanier).text((somme_A_payer()));

                        document.getElementById("val_remise1" + idPanier).onkeyup = function() {remise()};
                        //document.getElementById("prixUniteStock" + idPanier).onkeyup = function() {modif_Prix(lignes[i].prixunitevente,lignes[i].numligne)};

                        function remise() {
                        //var number = document.getElementById("Number");
                        //number.value = number.value+1;
                        //alert('ok')
                        
                            $("#somme_Apayer" + idPanier).text((somme_A_payer()) - $("#val_remise1" + idPanier).val());
                    }
                        
                    }

                }else{
                    addLigne();
                }


                $(".btn_Termine_Panier").attr("disabled","disabled");

                

            }

        /*********** Fin tape sur Entrer **************/

        }

        else{

            idFormParent=$("#panier_"+idPanier).parent().attr("id");

            // alert(idFormParent+'/////////')

            // setTimeout(() => {            

            $('#'+idFormParent+' .typeahead').html('')

        }
        localStorage.setItem('typeVente', 'bon');
    }); 

});
/** Fin Autocomplete Vente Boutique*/

/*** */
var design="";

$(document).on('click', '.typeahead li a[class="dropdown-item"]', function(e) {

    e.preventDefault();

    if (localStorage.getItem('typeVente')!='simple') {                    

        addLigne();
    }
 
});

$(document).on('click', '#factForm .typeahead li a[class="dropdown-item"]', function(e1) {

    e1.preventDefault();
    var text =  $("#factForm .typeahead li.active").text() || $("#factFormM .typeahead li.active").text();

        tab = text.split('.');

        idClient = tab[0];
        lesClients=getClient();
        searchClient=lesClients.find(c =>c.idClient==parseInt(idClient)); 
            
        if(searchClient != undefined){
            
            //localStorage.setItem('idCompte',searchCompte.idCompte);
            var paniers=getPagnet();

            var searchPanier=paniers.find(p =>p.idPagnet==idPanier);
            
            if(searchPanier != undefined){
                searchPanier.idClient=searchClient.idClient;
            }
            savePanier(paniers) 

        }
        $("#avanceInput"+idPanier).focus();
})

/** Fonction Ajout */ 

function savePanier(pagnet){

    localStorage.setItem("pagnet", JSON.stringify(pagnet));    
}

function saveLigne(ligne){
    localStorage.setItem("ligne", JSON.stringify(ligne));
}

function getPagnet(){
    
    const pagnier= localStorage.getItem("pagnet");
    if (pagnier==null) {  
        return [];
    } 
    return JSON.parse(pagnier);
}

function getPanierSynch(){
    
    const pagnier= localStorage.getItem("PanierSynch");
    if (pagnier==null) {  
        return [];
    } 
    return JSON.parse(pagnier);
}

function getLigne(){    
    const ligne= localStorage.getItem("ligne");
    if (ligne==null) {  
        return [];
    }
    
    return JSON.parse(ligne);
}

function getCompte(){    
    const compte= localStorage.getItem("Compte");
    if (compte==null) {  
        return [];
    }
    
    return JSON.parse(compte);
}

function getClient(){    
    const client= localStorage.getItem("Client");
    if (client==null) {  
        return [];
    }
    
    return JSON.parse(client);
}

function getUser(){    
    const user= localStorage.getItem("User");
    if (user==null) {  
        return [];
    }
    
    return JSON.parse(user);
}

function getBoutique(){    
    const boutique= localStorage.getItem("Boutique");
    if (boutique==null) {  
        return [];
    }
    
    return JSON.parse(boutique);
}

function getAcces(){    
    const acces= localStorage.getItem("Access");
    if (acces==null) {  
        return [];
    }
    
    return JSON.parse(acces);
}

function getDesignation(){
    const designation= localStorage.getItem("Designation");
    if (designation==null) {  
        return [];
    }
    
    return JSON.parse(designation);
}

function addPagnet(){
    lesPaniers=JSON.parse(localStorage.getItem("pagnet"));
    users=JSON.parse(localStorage.getItem("Boutique"));
    //comptes=JSON.parse(localStorage.getItem("Compte"));
    date=new Date(); 
    datejour=date.toLocaleDateString("es-CL");
    localStorage.setItem("datejour",datejour)
    
    
    var i=1;
    if(lesPaniers!=null && users!=null){
        for(var j=0; j< lesPaniers.length;j++){
            if(lesPaniers[j].verrouiller==0){
                i=i+1;
            }  
        }
    }

    if(i<=1  && users!=null){

        var pagnets=getPagnet();
        localStorage.setItem("IdPanier",pagnets.length)
        heure=date.toLocaleTimeString("fr");
    
        Panier = new Object();
        Panier.idPagnet =pagnets.length;
        Panier.datepagej =datejour;
        Panier.type = 0;
        Panier.heurePagnet = heure;
        Panier.totalp = 0;
        Panier.remise = 0;
        Panier.apayerPagnet = 0;
        Panier.restourne = 0;
        Panier.versement = 0;
        Panier.verrouiller =0; 
        Panier.idClient =0;
        Panier.idVitrine =0;
        Panier.idCompte =1;
        Panier.avance=0
        Panier.dejaTerminer=0;
        Panier.apayerPagnetTvaP=0
        Panier.apayerPagnetTvaR=0;
        Panier.image=null;
        Panier.synchronise=0;
        var searUser=users.find(u =>u.idutilisateur==sessionStorage.iduserSession)
        Panier.iduser =searUser.idutilisateur;
           
        pagnets.push(Panier);
        ligne=[];
        if(!localStorage.ligne){
            localStorage.setItem("ligne",JSON.stringify(ligne));
        }
        savePanier(pagnets);

    }  

    // $("#somme_Total" + idPanier).text(somme_A_payer());
                    
    // $("#somme_Apayer" + idPanier).text((somme_A_payer()));
       
}

function addLigne(){
    //alert(1);
    var designation = $("#ajouterProdFormB"+idPanier+" .typeahead li.active").text();

    $("#panier_"+idPanier).val('');

    //$(".btn_Termine_Panier").attr("disabled","disabled");

    const design=JSON.parse(localStorage.getItem("Designation"));
            
    if(design!=null){
            for(var i=0; i<design.length;i++){
                
                if(design[i].designation==designation){
                    var lignes=getLigne();
                    //$(".btn_Termine_Panier").removeAttr("disabled");
                    var searchLigne=lignes.find(l =>l.designation==designation);
                    if(searchLigne != undefined){
                        var lignePExiste=0;
                        var lignePQuantite=0;
                        var lignePrixUniteVente='';
                        var lignePrixUniteStock=0;
                        for(var j=0; j<lignes.length;j++){
                            if(lignes[j].idPagnet==idPanier && lignes[j].designation==designation){
                                lignePExiste=1;
                                lignes[j].quantite++;
                                lignePQuantite=lignes[j].quantite;
                                lignePrixUniteVente='Article';
                                lignePrixUniteStock=lignes[j].prixunitevente;
                                //localStorage.setItem('nbreArticleUniteStock', 1+'<>'+idPanier+'<>'+lignes[j].numligne);

                                break;
                            }
                        }
                        if(lignePExiste==1){
                            $("#ligneQuantite"+idPanier+""+designation).val(lignePQuantite);
                            $("#uniteVente"+idPanier+""+designation).val(lignePrixUniteVente);
                            $("#prixUniteStock"+idPanier+""+designation).val(lignePrixUniteStock);
                            saveLigne(lignes);
                            //$("#idPanier").load("container.html");
                        }
                        else{
                            var ligne = new Object();
                            ligne.numligne = lignes.length;
                            ligne.idPagnet =idPanier;
                            ligne.idDesignation = design[i].idDesignation;
                            ligne.designation = design[i].designation;
                            ligne.idStock=0;
                            ligne.unitevente = 'Article';
                            ligne.prixunitevente = design[i].prix;
                            ligne.quantite = 1;
                            ligne.prixtotal = ligne.quantite*design[i].prix; 
                            ligne.classe = 0;
                            ligne.synchronise=0;
                            ligne.prixtotalTvaP=0;
                            ligne.prixtotalTvaR=0;
                        
                            if(design[i].uniteStock!="Article" && design[i].uniteStock!="article"){

                                //alert(design[i].uniteStock)

                                var lignePanier = "<tr>" +
                            
                                    "<td>" + designation + "</td>" +
                            
                                    "<td><input class='form-control filedReadonly"+idPanier+"' id='ligneQuantite"+idPanier+""+designation+"' onkeyup='modif_Quantite(this.value," + ligne.numligne + " )' value='1' style='width: 30%' type='number' ></input></td>" +
                                    "<input type='hidden' id='nbreArticleUniteStock' value='"+design[i].nbreArticleUniteStock+"'/>"+
                                    "<td>" +
                            
                                        "<select class='form-control filedReadonly"+idPanier+"' id='uniteVente"+idPanier+""+designation+"' onchange='modif_UniteStock(this.value," + ligne.numligne + " ," + design[i].nbreArticleUniteStock + ","+idPanier+"')' >" +
                            
                                                "<option  value='Article§"+design[i].prix+"§"+idPanier+"'>Article</option>"+
                            
                                                "<option value='"+design[i].uniteStock+"§"+design[i].designation+"§"+idPanier+"'>Caisse</option>"+
                            
                                        "</select>"+
                            
                                    "</td>" +
                            
                                    "<td><input  class='form-control' id='prixUniteStock"+idPanier+""+designation+"' onkeyup='modif_Prix(this.value,"+ ligne.numligne +")' value='" + design[i].prix + "' style='width: 70%' type='number'></input></td>" +
                            
                            
                                    // "<td>" +
                                    //     "<button type='button' onclick='retour_Produit(" + design[i].idDesignation + "," + idPanier + ")'	 class='btn btn-warning pull-right btnRtrAvant"+idPanier+"'>" +
                                
                                    //         "<span class='glyphicon glyphicon-remove'></span>Retour" +
                                
                                    //     "</button>" +
                            
                                    // "</td>" +

                                    "<td>"+

                                        "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit"+idPanier+"' data-toggle='modal' data-target=#msg_rtrnAvant_ligne"+lignes.length+">"+

                                                "<span class='glyphicon glyphicon-remove'></span>Retour"+

                                        "</button>"+

                                        "<div class='modal fade' id=msg_rtrnAvant_ligne"+lignes.length+" role='dialog'>"+

                                            "<div class='modal-dialog'>"+

                                                //<!-- Modal content-->

                                                "<div class='modal-content'>"+

                                                    "<div class='modal-header panel-primary'>"+

                                                        "<button type='button' class='close' data-dismiss='modal'>&times;</button>"+

                                                        "<h4 class='modal-title'>Confirmation</h4>"+

                                                    "</div>"+

                                                    "<form class='form-inline noImpr' id='factForm' method='post'>"+

                                                        "<div class='modal-body'>"+

                                                            "<p>Voulez-vous annuler cette ligne du panier numéro <b>"+lignes.length+"</b></p>"+

                                                            "<input type='hidden' name='idPagnet' value="+100+">"+

                                                            

                                                        "</div>"+

                                                        "<div class='modal-footer'>"+

                                                            "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+

                                                            "<button type='button' onclick='retour_Produit(" + ligne.numligne + "," + idPanier + ")' name='btnRetourAvant' data-numligne='2617' class='btn btn-success btnRetourAvant'>Confirmer</button>"+

                                                        "</div>"+

                                                    "</form>"+

                                                "</div>"+

                                            "</div>"+

                                        "</div>"+

                                    "</td>"+
                            
                                "</tr>";
                            
                            }
                            else{
                                var lignePanier = "<tr>" +
                            
                                    "<td>" + designation + "</td>" +
                            
                                    "<td><input class='form-control filedReadonly"+idPanier+"' id='ligneQuantite"+idPanier+""+designation+"' onkeyup='modif_Quantite(this.value," + ligne.numligne + " )' value='1' style='width: 30%' type='number' ></input></td>" +
                                    "<input type='hidden' id='nbreArticleUniteStock' value='1'/>"+
                                    "<td>" +
                            
                                        "<select  class='form-control filedReadonly"+idPanier+"' id='uniteVente"+idPanier+""+designation+"' onchange='modif_UniteStock(this.value," + ligne.numligne + " ," + design[i].nbreArticleUniteStock + ","+idPanier+"')' >" +
                            
                                                "<option value='Article§"+design[i].prix+"§"+idPanier+"'>Article</option>"+
                            
                                        "</select>"+
                            
                                    "</td>" +
                            
                                    "<td><input class='form-control' id='prixUniteStock"+idPanier+""+designation+"' onkeyup='modif_Prix(this.value,"+ ligne.numligne +")' value='" + design[i].prixuniteStock + "' style='width: 70%' type='number'></input></td>" +
                            
                                    // "<td>" +
                            
                                    //     "<button type='button' onclick='retour_Produit(" + design[i].idDesignation + "," + idPanier + ")'	 class='btn btn-warning pull-right btnRtrAvant"+idPanier+"'>" +
                                
                                    //         "<span class='glyphicon glyphicon-remove'></span>Retour" +
                                
                                    //     "</button>" +
                            
                                    // "</td>" +

                                    "<td>"+

                                        "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit"+idPanier+"' data-toggle='modal' data-target=#msg_rtrnAvant_ligne"+lignes.length+">"+

                                                "<span class='glyphicon glyphicon-remove'></span>Retour"+

                                        "</button>"+

                                        "<div class='modal fade' id=msg_rtrnAvant_ligne"+lignes.length+" role='dialog'>"+

                                            "<div class='modal-dialog'>"+

                                                //<!-- Modal content-->

                                                "<div class='modal-content'>"+

                                                    "<div class='modal-header panel-primary'>"+

                                                        "<button type='button' class='close' data-dismiss='modal'>&times;</button>"+

                                                        "<h4 class='modal-title'>Confirmation</h4>"+

                                                    "</div>"+

                                                    "<form class='form-inline noImpr' id='factForm' method='post'>"+

                                                        "<div class='modal-body'>"+

                                                            "<p>Voulez-vous annuler cette ligne du panier numéro <b>"+lignes.length+"</b></p>"+

                                                            "<input type='hidden' name='idPagnet' value="+100+">"+

                                                            

                                                        "</div>"+

                                                        "<div class='modal-footer'>"+

                                                            "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+

                                                            "<button type='button' onclick='retour_Produit(" + ligne.numligne + "," + idPanier + ")' name='btnRetourAvant' data-numligne='2617' class='btn btn-success btnRetourAvant'>Confirmer</button>"+

                                                        "</div>"+

                                                    "</form>"+

                                                "</div>"+

                                            "</div>"+

                                        "</div>"+

                                    "</td>"+
                                
                                    "</tr>";
                            
                            }
                            $("#tablePanier" + idPanier).prepend(lignePanier);
    
                            lignes.push(ligne);
                            saveLigne(lignes);
                            
                        }

                    }else{
                        var ligne = new Object();
                        ligne.numligne = lignes.length;
                        ligne.idPagnet =idPanier;
                        ligne.idDesignation = design[i].idDesignation;
                        ligne.designation = design[i].designation;
                        ligne.idStock=0;
                        ligne.unitevente = 'Article';
                        ligne.prixunitevente = design[i].prix;
                        ligne.quantite = 1;
                        ligne.prixtotal = ligne.quantite * design[i].prix;
                        ligne.classe = 0;
                        ligne.synchronise = 0;
                        ligne.prixtotalTvaP=0;
                        ligne.prixtotalTvaR=0;
                    
                        if(design[i].uniteStock!="Article" && design[i].uniteStock!="article"){

                            var lignePanier = "<tr>" +
                        
                                "<td>" + designation + "</td>" +
                        
                                "<td><input class='form-control filedReadonly"+idPanier+"' id='ligneQuantite"+idPanier+""+designation+"' onkeyup='modif_Quantite(this.value," + ligne.numligne + " )' value='1' style='width: 30%' type='number' ></input></td>" +
                               "<input type='hidden' id='nbreArticleUniteStock' value='"+design[i].nbreArticleUniteStock+"'/>"+
                                "<td>" +
                        
                                    "<select class='form-control filedReadonly"+idPanier+"' id='uniteVente1'  onchange='modif_UniteStock(this.value," + ligne.numligne + " ," + design[i].nbreArticleUniteStock + ","+idPanier+"')' >" +
                        
                                            "<option id='default"+design[i].uniteStock+"' value='Article§"+design[i].designation+"§"+idPanier+"'>Article</option>"+
                        
                                            "<option value='"+design[i].uniteStock+"§"+design[i].designation+"§"+idPanier+"'>"+design[i].uniteStock+"</option>"+
                        
                                    "</select>"+
                        
                                "</td>" +
                        
                                "<td><input class='form-control' id='prixUniteStock"+idPanier+""+designation+"' onkeyup='modif_Prix(this.value," + ligne.numligne + ")' value='" + design[i].prix + "' style='width: 70%' type='number'></input></td>" +
                        
                                "<td>"+

                                    "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit"+idPanier+"' data-toggle='modal' data-target=#msg_rtrnAvant_ligne"+lignes.length+">"+

                                            "<span class='glyphicon glyphicon-remove'></span>Retour"+

                                    "</button>"+

                                    "<div class='modal fade' id=msg_rtrnAvant_ligne"+lignes.length+" role='dialog'>"+

                                        "<div class='modal-dialog'>"+

                                            //<!-- Modal content-->

                                            "<div class='modal-content'>"+

                                                "<div class='modal-header panel-primary'>"+

                                                    "<button type='button' class='close' data-dismiss='modal'>&times;</button>"+

                                                    "<h4 class='modal-title'>Confirmation</h4>"+

                                                "</div>"+

                                                "<form class='form-inline noImpr' id='factForm' method='post'>"+

                                                    "<div class='modal-body'>"+

                                                        "<p>Voulez-vous annuler cette ligne du panier numéro <b>"+lignes.length+"</b></p>"+

                                                        "<input type='hidden' name='idPagnet' value="+100+">"+

                                        

                                                    "</div>"+

                                                    "<div class='modal-footer'>"+

                                                        "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+

                                                        "<button type='button' onclick='retour_Produit(" + ligne.numligne + "," + idPanier + ")' name='btnRetourAvant' data-numligne='2617' class='btn btn-success btnRetourAvant'>Confirmer</button>"+

                                                    "</div>"+

                                                "</form>"+

                                            "</div>"+

                                        "</div>"+

                                    "</div>"+

                                 "</td>"+
                        
                            "</tr>";
                            $("#tablePanier" + idPanier).prepend(lignePanier);

                            lignes.push(ligne);
                            saveLigne(lignes); 
                            //localStorage.setItem("nbreArticleUniteStock", design[i].uniteStock+'<>'+design[i].nbreArticleUniteStock+'<>'+idPanier+'<>'+ligne.numligne);
                        
                        }
                        else{
                            var lignePanier = "<tr>" +
                        
                                "<td>" + designation + "</td>" +
                        
                                "<td><input class='form-control filedReadonly"+idPanier+"' id='ligneQuantite"+idPanier+""+designation+"' onkeyup='modif_Quantite(this.value," + ligne.numligne + ")' value='1' style='width: 30%' type='number' ></input></td>" +
                                "<input type='hidden' id='nbreArticleUniteStock' value='1'/>"+
                                "<td>" +
                        
                                    "<select class='form-control filedReadonly"+idPanier+"' id='uniteVente"+idPanier+""+designation+"' onchange='modif_UniteStock(this.value," + ligne.numligne + "," + design[i].nbreArticleUniteStock + ","+idPanier+"')' >" +
                        
                                        "<option value='Article"+design[i].designation+"§"+idPanier+"'>Article</option>"+
                        
                                    "</select>"+
                        
                                "</td>" +
                        
                                "<td><input disabled='true' class='form-control' id='prixUniteStock"+idPanier+""+designation+"' onkeyup='modif_Prix(this.value,"+ ligne.numligne +")' value='" + design[i].prixuniteStock + "' style='width: 70%' type='number'></input></td>" +
                        

                                "<td>"+

                                    "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit"+idPanier+"' data-toggle='modal' data-target=#msg_rtrnAvant_ligne"+lignes.length+">"+

                                            "<span class='glyphicon glyphicon-remove'></span>Retour"+

                                    "</button>"+

                                    "<div class='modal fade' id=msg_rtrnAvant_ligne"+lignes.length+" role='dialog'>"+

                                        "<div class='modal-dialog'>"+

                                            //<!-- Modal content-->

                                            "<div class='modal-content'>"+

                                                "<div class='modal-header panel-primary'>"+

                                                    "<button type='button' class='close' data-dismiss='modal'>&times;</button>"+

                                                    "<h4 class='modal-title'>Confirmation</h4>"+

                                                "</div>"+

                                                "<form class='form-inline noImpr' id='factForm' method='post'>"+

                                                    "<div class='modal-body'>"+

                                                        "<p>Voulez-vous annuler cette ligne du panier numéro <b>"+lignes.length+"</b></p>"+

                                                        "<input type='hidden' name='idPagnet' value="+100+">"+


                                                    "</div>"+

                                                    "<div class='modal-footer'>"+

                                                        "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>"+

                                                        "<button type='button' onclick='retour_Produit(" + ligne.numligne + "," + idPanier + ")' name='btnRetourAvant' data-numligne='"+ ligne.numligne +"' class='btn btn-success btnRetourAvant'>Confirmer</button>"+

                                                    "</div>"+

                                                "</form>"+

                                            "</div>"+

                                        "</div>"+

                                    "</div>"+

                                 "</td>"+
                        
                            "</tr>";
                            $("#tablePanier" + idPanier).prepend(lignePanier);

                            lignes.push(ligne);
                            saveLigne(lignes); 
                            //localStorage.setItem("nbreArticleUniteStock", 'Artcle'+'<>'+1+'<>'+idPanier+'<>'+ligne.numligne);
                        
                        }
                        
                        
                        

                    }
                    //localStorage.setItem("ligne", JSON.stringify(lignes));
                }              
            }
            $("#idPanier").load("container.html"); 
            
            $("#somme_Total" + idPanier).text(somme_A_payer());
                    

            $("#somme_Apayer" + idPanier).text((somme_A_payer()));

            document.getElementById("val_remise1" + idPanier).onkeyup = function() {remise()};
            //document.getElementById("prixUniteStock" + idPanier).onkeyup = function() {modif_Prix(lignes[i].prixunitevente,lignes[i].numligne)};

            function remise() {
            //var number = document.getElementById("Number");
            //number.value = number.value+1;
            //alert('ok')
            
                $("#somme_Apayer" + idPanier).text((somme_A_payer()) - $("#val_remise1" + idPanier).val());
        }
            
    }
}


function somme_A_payer(){
    const ligne=JSON.parse(localStorage.getItem("ligne"));
    const panier=JSON.parse(localStorage.getItem("pagnet"));
   // alert('somme='+idPanier);
//    str=localStorage.getItem('nbreArticleUniteStock');
//    tab=str.split('<>')
   nbreArticleUniteStock=$('#nbreArticleUniteStock').val();
   //alert(nbreArticleUniteStock)
    if(ligne!=null){
        var idP=panier.find(p=>p.idPagnet==panier[panier.length-1].idPagnet);
        var sommeT=0;
            for(var i=ligne.length-1; 0<=i;i--){  
                //var tab=[];              
                if(idP.idPagnet==ligne[i].idPagnet){
                                      
                    if(ligne[i].unitevente!='Article' && ligne[i].unitevente!='article'){

                        designations=getDesignation()
                        searchDesignation=designations.find(d =>d.idDesignation==ligne[i].idDesignation); 
                        
                        nbreArticleUniteStok1=searchDesignation.nbreArticleUniteStock;
                
                        sommeT=sommeT+(ligne[i].quantite * ligne[i].prixunitevente)*nbreArticleUniteStok1;
                                             
                    }
                            
                    else{
                        
                        sommeT=sommeT+(ligne[i].quantite * ligne[i].prixunitevente);                            
                    }

                }
                
            }
            //alert(sommeT);
            return sommeT;
    }
}

function totalVente(){
    date=new Date();
    datejour=date.toLocaleDateString("es-CL");
    //heure=date.toLocaleTimeString("fr");
    const panier=JSON.parse(localStorage.getItem("pagnet"));

    if(panier!=null){
        var sommeV=0;
        for(var i=0; i<panier.length;i++){ 
            if(panier[i].datepagej==datejour){       
                sommeV=sommeV+parseInt(panier[i].totalp);
            }
        }
        return sommeV;
            
    }
}
/** Fin fonction Ajout */

/**Debut modifier la quantite dans la ligne d'un Pagnet**/

function modif_Quantite(quantite,ligne) {
    var lignes=getLigne();
    var searchLigne=lignes.find(l =>l.numligne==ligne);  
    if(searchLigne != undefined){
        
        searchLigne.quantite=quantite;
        searchLigne.prixtotal=searchLigne.prixunitevente*quantite
        //alert("Total "+searchLigne.prixtotal)
        saveLigne(lignes);
    }
   
    $("#somme_Total" + idPanier).text(somme_A_payer());
                    

    $("#somme_Apayer" + idPanier).text((somme_A_payer()));

    return quantite;
       
}

function modif_UniteStock(unitevente,numligne,nbreArticleUniteStk, idPanier) {
    
    tab = unitevente.split('§');
    
    var uniteStock = tab[0];
    
    var lignes=getLigne();
    if(uniteStock!='Article' && uniteStock!='article'){
        //alert('Caisse'+ nbreArticleUniteStk)
        var searchLigne=lignes.find(l =>l.numligne==numligne); 
        searchLigne.unitevente=uniteStock;
        if(searchLigne != undefined){
            searchLigne.prixtotal=modif_Quantite(searchLigne.quantite,numligne)*modif_Prix(searchLigne.prixunitevente,numligne)*nbreArticleUniteStk;
            //searchLigne.prixunitevente=(searchLigne.prixunitevente)*nbreArticleUniteStk
            saveLigne(lignes);

            //localStorage.setItem('nbreArticleUniteStock', unitevente+'<>'+nbreArticleUniteStk+'<>'+idPanier+'<>'+numligne);
            
            //$("#prixUniteStock" + idPanier+''+searchLigne.designation).text(500);
            
        }
        
        
    }  
    else{
        //alert('Article'+ nbreArticleUniteStk)
        var searchLigne=lignes.find(l =>l.numligne==numligne); 
        searchLigne.unitevente=uniteStock;
        if(searchLigne != undefined){
            
            searchLigne.prixtotal=modif_Quantite(searchLigne.quantite,numligne)*modif_Prix(searchLigne.prixunitevente,numligne);
            saveLigne(lignes);
            //localStorage.setItem('nbreArticleUniteStock', unitevente+'<>'+1+'<>'+idPanier+'<>'+numligne);
            
        }
         
    }
    $("#idPanier").load("container.html"); 

    $("#somme_Total" + idPanier).text(somme_A_payer());

    $("#somme_Apayer" + idPanier).text(somme_A_payer() - $("#val_remise1" + idPanier).val()); 

    
}

function modif_Prix(prix,ligne) {
    
    //tab = prix.split('<>');
    //alert('Ligne: '+ligne)
    //var uniteStock = tab[0];

    var lignes=getLigne();
    var searchLigne=lignes.find(l =>l.numligne==ligne);

    if(ligne != undefined){        
        searchLigne.prixunitevente=prix; 
        searchLigne.prixtotal=modif_Quantite(searchLigne.quantite,ligne)*prix;
        saveLigne(lignes);
    }

    // $("#somme_Total" + pagnet).text(prix);

    // $("#somme_Apayer" + pagnet).text(prix - $("#val_remise" + pagnet).val());

    //$("#somme_Total" + idPanier).text(somme_A_payer());
                    

    $("#somme_Apayer" + idPanier).text(somme_A_payer()- $("#val_remise" + idPanier).val());
    
     return prix;  
}

function retour_Produit(numligne, idPanier){
    //alert(numligne)
    lignes=getLigne();
    ligne=lignes.filter(l=>l.numligne!=numligne);
    saveLigne(ligne);   
}

function imprimerTicket(id) {
   
    //document.getElementById("ticket"+id).submit();
    localStorage.setItem('idPanierT', id);
    window.open('/JCaisse/offline/ticketCaisse.html','_blank');     
}

function imprimerFacture(id) {
   
    nomclient=$("#nomclient"+id).val();
    adresseclient=$("#adresseclient"+id).val();
    telephone=$("#telephone"+id).val();
   
    localStorage.setItem('idPanierF', id);
    localStorage.setItem('nomclient', nomclient);
    localStorage.setItem('adresseclient', adresseclient);
    localStorage.setItem('telephone', telephone);

    window.open('/JCaisse/offline/factureClient.html');
}

function modif_Versement(versement,id) {
    
    localStorage.setItem("versement",versement)
          
}

function modifier_VersementB(idPanier) {
    
    localStorage.setItem("versement",versement)
          
}

/**Fin modifier la quantite dans la ligne d'un Pagnet**/