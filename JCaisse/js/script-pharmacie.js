/*

R�sum�:

Commentaire:

version:1.1

Auteur: Mor Mboup

Date de modification:

*/ 


/*************** Debut chargement container vente pharmacie ********************/

$(document).ready(function() {    

    //executes code below when user click on pagination links

    $(document).on( "click", ".paginationLoaderPh a", function (e){

        // $("#resultsProductsPrix").on( "click", function (e){

            // alert(2)

        e.preventDefault();

        // $(".loading-div").show(); //show loading element

        // page = page+1; //get page number from link

        page = $(this).attr("data-page"); //get page number from link

        $("#img-load-page"+page).show();

        //  alert(page)

            

        // $(".loading-gif").show();



        $("#pharmacie-venteContent" ).load("ajax/loadContainer-pharmacieAjax.php", {"page":page}, function(data){ //get content from PHP page

            // alert(data)

            $("#img-load-page"+page).hide();

            // $("#loading_gif_modal").modal("hide");

        });

        // $("#resultsProductsPrix").load("ajax/listerPrixAjax.php",{"page":page,"operation":1}

    });
    // alert(111111)

    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

        // alert(data)

        $(".loading-gif").hide();


    });

});

/*************** Fin chargement container vente pharmacie ********************/

$(document).ready(function() {

    $(document).on("click", "#addPanier-pharmacie", function(e) {          

        // $("#loading_gif_modal").modal("show");
        // alert(552266)

        $(".img-load").show();

        $("#addPanier-pharmacie").attr("disabled","disabled");

        $.ajax({

            url: "ajax/operationPanier-pharmacieAjax.php",

            method: "POST",

            data: {

                operation: 1,

                btnSavePagnetVente: 'btnSavePagnetVente',

            },

            success: function(data) { 

                // alert(data)

                $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                    // alert(data)

                    // $(".loading-gif").hide();

                    // $("#loading_gif_modal").modal("hide");

                    $(".img-load").hide();

                    $(".codeBarreLignePh").focus();

                });

                

            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });
    });
    
    $(document).on("click", "#addImputation-pharmacie", function(e) {          

        // $("#loading_gif_modal").modal("show");
        // alert(999887)

        $(".img-load").show();

        $("#addImputation-pharmacie").attr("disabled","disabled");

        $.ajax({

            url: "ajax/operationPanier-pharmacieAjax.php",

            method: "POST",

            data: {

                operation: 1,

                btnSavePagnetImputation: 'btnSavePagnetImputation',

            },

            success: function(data) { 

                // alert(data)

                $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                    // alert(data)

                    // $(".loading-gif").hide();

                    // $("#loading_gif_modal").modal("hide");

                    $(".img-load").hide();

                    $(".codeBarreLigneMutuelle").focus();

                });

                

            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });
    });

    
    $(document).on("click", ".btn_Termine_Panier_Ph", function(e) {  
        // alert(7777777)

        idPanier=$(this).attr('data-idPanier');

        // $("#loading_gif_modal").modal("show");

        remise= ($('#val_remise'+idPanier).val()) ? $('#val_remise'+idPanier).val() : 0 ;

        compte=$('#compte'+idPanier).val()

        compte_p=$('#compte'+idPanier+" option:selected").text()

        clientInput=$('#clientInput'+idPanier).val()

        avanceInput=($('#avanceInput'+idPanier).val()) ? $('#avanceInput'+idPanier).val() : 0 ;

        compteAvance=$('#compteAvance'+idPanier).val()

        compteAvance_p=$('#compteAvance'+idPanier+" option:selected").text()

        versement=($('#versement'+idPanier).val()) ? $('#versement'+idPanier).val() : 0 ;

        total_p = $("#somme_Total"+idPanier).text();

        apayer_p = $("#somme_Apayer"+idPanier).text();

        taux_p=$('#taux'+idPanier+' :selected').val();
        

        if (compte) {
            // alert(1)  

            if (compte=="2" && clientInput=="") {

                $('#msg_info_ClientRequired').modal('show');

            } else {

                if (apayer_p!=0) {

                // alert(total_p +" / "+apayer_p);

                    $('#cache_btn_Terminer'+idPanier).hide();

                    $('.btn_Retourner_Produit'+idPanier).prop('disabled', true);


                    $(".img-load-terminer").show();

                    $(".btn_Termine_Panier_Ph"+idPanier).attr("disabled","disabled");


                    $("#btnContent"+idPanier ).show();

                    $("#panierEncours"+idPanier ).html('Panier '+idPanier);

                    $("#panelPanier"+idPanier ).attr('class','panel panel-success');

                    $(".btnRtrAvant"+idPanier).attr('class','btn btn-danger pull-right disabled');

                    $(".btn_Retourner_Produit"+idPanier).attr('class','btn btn-danger pull-right');

                    // $(".filedReadonly"+idPanier ).attr('disabled','disabled');
                    // console.log(".filedReadonly"+idPanier)
                    // tabInput=$(".filedReadonly"+idPanier );
                    $(".filedReadonly"+idPanier).each(function(i, obj) {
                        id=$(this).attr('id')

                        var originalForm = $('#'+id);
                        var div = $('<div/>',{
                            text : originalForm.val(),
                            id : originalForm.attr('id')
                        });
                        $('#'+id).replaceWith(div);
                    });
                    
                    $('#total_p'+idPanier).text(total_p);

                    $('#remise_p'+idPanier).text(remise);

                    $('#apayer_p'+idPanier).text(apayer_p);

                    taux=((total_p * taux_p) / 100);

                    p_taux='('+taux_p+') : '+taux;
    
                    $('#taux_p'+idPanier).text(p_taux);

    
                    // alert(compte+"/"+compte_p)

                    if (compte) {

                        // alert(11233)

                        $("#compte_p"+idPanier).text(compte_p);

                        $("#divcompte_p"+idPanier).show();

                    }

                    if (avanceInput > 0) {

                        // alert(11233)

                        $("#avance_p"+idPanier).text(avanceInput);

                        $('#reste_p'+idPanier).text(parseInt(total_p) - parseInt(avanceInput));

                        $('#compteAvance_p'+idPanier).text(compteAvance_p);

                        $("#divavance_p"+idPanier).show();

                    }

                    if (versement > 0) {

                        // alert(11233)

                        $('#versement_p'+idPanier).text(versement);

                        $("#divversement_p"+idPanier).show();

                        

                        $('#rendu_p'+idPanier).text(parseInt(versement) - parseInt(apayer_p));

                        $("#divrendu_p"+idPanier).show();

                    }

                    $("#footerContent"+idPanier ).show();

                }

                // 

                // alert(idPanier+"/"+remise+"/"+compte+"/"+clientInput+"/"+avanceInput+"/"+compteAvance+"/"+versement)

                $.ajax({

                    url: "ajax/operationPanier-pharmacieAjax.php",

                    method: "POST",

                    data: {

                        //operation: 1,

                        idPagnet: idPanier,

                        remise: remise,

                        compte: compte,

                        compteAvance: compteAvance,

                        clientInput: clientInput,

                        avanceInput: avanceInput,

                        versement: versement,

                        btnImprimerFacture: 'btnImprimerFacture',

                    },

                    success: function(data) {

                        // alert(data);

                        if(data == '1' || data == '') {

    

    

                            // $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                            //     // $("#addPanier").click();

                            //     $(".loading-gif").hide();

                            //     // $("#loading_gif_modal").modal("hide");

                            //     $(".img-load-terminer").hide();

                            // });

    

                        }else { 

    

                            $('#msg_info_body').html(data);

                            $('#msg_info_1').modal('show');

                            $(".img-load-terminer").hide();

                            

                            $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                                // alert(data)

                                $(".loading-gif").hide();

                                // $("#loading_gif_modal").modal("hide");

                            });

    

                        }

                        

                    },

                    error: function() {

                        alert("La requête ");

                    },

                    dataType: "text"

                });

            }

        } else {   
            // alert(idPanier)          

            if (apayer_p!=0) {
                // alert(102)
            

            // alert(total_p +" / "+apayer_p);

                $('#cache_btn_Terminer'+idPanier).hide();

                $('.btn_Retourner_Produit'+idPanier).prop('disabled', true);



                $(".img-load-terminer").show();

                $("#panelPanier"+idPanier ).attr('class','panel panel-success');

                $(".btn_Termine_Panier_Ph"+idPanier).attr("disabled","disabled");

                $("#btnContent"+idPanier ).show();

                $("#panierEncours"+idPanier ).html('Panier '+idPanier);

                $(".btnRtrAvant"+idPanier).attr('class','btn btn-danger pull-right disabled');

                $(".btn_Retourner_Produit"+idPanier).attr('class','btn btn-danger pull-right');

                // $(".filedReadonly"+idPanier ).attr('disabled','disabled');
                $(".filedReadonly"+idPanier).each(function(i, obj) {
                    id=$(this).attr('id')

                    var originalForm = $('#'+id);
                    var div = $('<div/>',{
                        text : originalForm.val(),
                        id : originalForm.attr('id')
                    });
                    $('#'+id).replaceWith(div);
                });
                

                $('#total_p'+idPanier).text(total_p);

                $('#remise_p'+idPanier).text(remise);

                $('#apayer_p'+idPanier).text(apayer_p);

                taux=((total_p * taux_p) / 100);

                p_taux='('+taux_p+') : '+taux;

                $('#taux_p'+idPanier).text(p_taux);

                // alert(compte+"/"+compte_p)

                if (compte) {

                    // alert(11233)

                    $("#compte_p"+idPanier).text(compte_p);

                    $("#divcompte_p"+idPanier).show();

                }

                if (avanceInput > 0) {

                    // alert(11233)

                    $("#avance_p"+idPanier).text(avanceInput);

                    $('#reste_p'+idPanier).text(parseInt(total_p) - parseInt(avanceInput));

                    $('#compteAvance_p'+idPanier).text(compteAvance_p);

                    $("#divavance_p"+idPanier).show();

                }

                if (versement > 0) {

                    // alert(11233)

                    $('#versement_p'+idPanier).text(versement);

                    $("#divversement_p"+idPanier).show();

                    

                    $('#rendu_p'+idPanier).text(parseInt(versement) - parseInt(apayer_p));

                    $("#divrendu_p"+idPanier).show();

                }

                

                $("#footerContent"+idPanier ).show();

            }

            // 

            // alert(idPanier+"/"+remise+"/"+compte+"/"+clientInput+"/"+avanceInput+"/"+compteAvance+"/"+versement)

            $.ajax({

                url: "ajax/operationPanier-pharmacieAjax.php",

                method: "POST",

                data: {

                    //operation: 1,

                    idPagnet: idPanier,

                    remise: remise,

                    compte: compte,

                    compteAvance: compteAvance,

                    clientInput: clientInput,

                    avanceInput: avanceInput,

                    versement: versement,

                    btnImprimerFacture: 'btnImprimerFacture',

                },

                success: function(data) {

                    //alert(data);

                    if(data == '1' || data == '') {





                        // $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        //     // $("#addPanier").click();

                        //     $(".loading-gif").hide();

                        //     // $("#loading_gif_modal").modal("hide");

                        //     $(".img-load-terminer").hide();

                        // });



                    }else { 



                        $('#msg_info_body').html(data);

                        $('#msg_info_1').modal('show');

                        $(".img-load-terminer").hide();

                        

                        $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                            // alert(data)

                            $(".loading-gif").hide();

                            // $("#loading_gif_modal").modal("hide");

                        });



                    }

                    

                },

                error: function() {

                    alert("La requête ");

                },

                dataType: "text"

            });

        }

    }) 
    
    $(document).on("click", ".btnAnnulerPagnet_Ph", function(e) {  

        // alert('annuler')
        // $("#loading_gif_modal").modal("show");

        $(".img-load-annulerPanier").show();

        $(".btnAnnulerPagnet_Ph").attr("disabled","disabled");

        idPanier=$(this).attr('data-idPanier');
        // alert(idPanier)

        $.ajax({

            url: "ajax/operationPanier-pharmacieAjax.php",

            method: "POST",

            data: {

                operation: 1,

                btnAnnulerPagnet: 'btnAnnulerPagnet',

                idPagnet: idPanier,

            },

            success: function(data) { 

                // alert(data)

                /****************msg_ann_pagnet***************** */

                if(data == '1'){

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();



                        $("#msg_ann_pagnet"+idPanier).modal('hide')

                        $(".modal-backdrop").remove();

                        $(".img-load-annulerPanier").hide();

                        // $("#loading_gif_modal").modal("hide");

                    });



                }else {

                    

                    $('#msg_info_body').html("<p>IMPOSSIBLE.</br></br> Erreur lors de l'annulation du panier n° "+idPanier+". </br></br></p>");

                    $('#msg_info_1').modal('show');

                    $(".img-load-annulerPanier").hide();

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        // $("#loading_gif_modal").modal("hide");

                    });

                }

                

            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });

    })

    $(document).on("click", ".btnRetournerPagnet_Ph", function(e) {  

        // $("#loading_gif_modal").modal("show");

        $(".img-load-retournerPanier").show();

        $(".btnRetournerPagnet_Ph").attr("disabled","disabled");

        idPanier=$(this).attr('data-idPanier');
        // alert(idPanier)

        $.ajax({

            url: "ajax/operationPanier-pharmacieAjax.php",

            method: "POST",

            data: {

                operation: 1,

                btnRetournerPagnet: 'btnRetournerPagnet',

                idPagnet: idPanier,

            },

            success: function(data) { 

                // alert(data)

                /**************** msg_rtrn_pagnet ***************** */

                if(data == '1'){

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        $("#msg_ann_pagnet"+idPanier).modal('hide')

                        $(".modal-backdrop").remove();

                        $(".img-load-annulerPanier").hide();

                        $('body').attr('style','overflow-y:scroll;')
                        // $(window).scrollTop(5);

                        // alert(456)

                        // $("#loading_gif_modal").modal("hide");

                    });

                } else {

                    $('#msg_info_body').html(data);

                    $('#msg_info_1').modal('show');

                    $(".img-load-retournerPanier").hide();

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $('body').attr('style','overflow-y:scroll;')

                        $(".loading-gif").hide();

                        // $("#loading_gif_modal").modal("hide");

                    });

                }

            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });

    })
    
    $(document).on("click", ".btnRetourAvant_Ph", function(e) {  

        // $("#loading_gif_modal").modal("show");

        $(".img-load-retourAvant").show();

        $(".btnRetourAvant_Ph").attr("disabled","disabled");

        numligne=$(this).attr('data-numligne');
        // alert(numligne);

        $.ajax({

            url: "ajax/operationPanier-pharmacieAjax.php",

            method: "POST",

            data: {

                operation: 1,

                btnRetourAvant: 'btnRetourAvant',

                numligne: numligne

            },

            success: function(data) { 

                // alert(data)

                /****************msg_rtrnAvant_ligne***************** */

                if(data == '1'){

                    // alert('data')

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        $("#msg_rtrnAvant_ligne"+numligne).modal('hide')

                        $(".modal-backdrop").remove();

                        $(".img-load-retourAvant").hide();

                        // $("#loading_gif_modal").modal("hide");

                    });

                }else {

                    $('#msg_info_body').html("<p>IMPOSSIBLE.</br></br> Erreur lors de la tentative de retour du ligne n° "+numligne+". </br></br></p>");

                    $('#msg_info_1').modal('show');

                    $(".img-load-retourAvant").hide();

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        // $("#loading_gif_modal").modal("hide");

                    });

                }

            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });

    })


    $(document).on("click", ".btnRetourApres_Ph", function(e) {  
        // alert(2588999)
        // $("#loading_gif_modal").modal("show");

        $(".img-load-retourApres").show();

        $(".btnRetourApres_Ph").attr("disabled","disabled");

        numligne=$(this).attr('data-numligne');
        // alert(numligne)

        // numligne=$("#numligne").val();

        idStock=$("#idStock"+numligne).val();

        designation=$("#designation"+numligne).val();

        idPagnet=$("#idPagnet"+numligne).val();

        quantite=$("#quantite"+numligne).val();

        forme=$("#forme"+numligne).val();

        prixPublic=$("#prixPublic"+numligne).val();
        // alert(prixPublic)
        prixtotal=$("#prixtotal"+numligne).val();

        totalp=$("#totalp"+numligne).val();

        // alert(numligne+"/"+idStock+"/"+designation+"/"+idPagnet+"/"+quantite+"/"+unitevente+"/"+prixunitevente+"/"+prixtotal+"/"+totalp)

        $.ajax({

            url: "ajax/operationPanier-pharmacieAjax.php",

            method: "POST",

            data: {

                operation: 1,

                btnRetourApres: 'btnRetourApres',

                numligne: numligne,

                idStock: idStock,

                designation: designation,

                idPagnet: idPagnet,

                quantite: quantite,

                forme: forme,

                prixPublic: prixPublic,

                prixtotal: prixtotal,

                totalp: totalp

            },

            success: function(data) { 

                // alert(data)

                /********************************* */

                if(data == '1') {

                    // alert(1)

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        $("#msg_rtrnApres_ligne"+numligne).modal('hide')

                        $(".modal-backdrop").remove();

                        $(".img-load-retourApres").hide();

                        $('body').attr('style','overflow-y:scroll;')

                        // $("#loading_gif_modal").modal("hide");

                    });

                }else {
                    // alert(2)

                    $("#msg_rtrnApres_ligne"+numligne).modal('hide')

                    $(".modal-backdrop").remove();

                    $('#msg_info_body').html(data);

                    $('#msg_info_1').modal('show');

                    // $("#loading_gif_modal").modal("hide");

                    $(".img-load-retourApres").show();

                    $('body').attr('style','overflow-y:scroll;')

                }
            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });

    })

    $(document).on("keyup", ".inputRetourApres_Ph", function(e) {  

        e.preventDefault();

        // alert(12354)

        var keycode = (e.keyCode ? e.keyCode : e.which);



        if (keycode == '13') {
                
            $(".img-load-retourApres").show();

            $(".btnRetourApres_Ph").attr("disabled","disabled");

            numligne=$(this).attr('data-numligne');
            // alert(numligne)

            // numligne=$("#numligne").val();

            idStock=$("#idStock"+numligne).val();

            designation=$("#designation"+numligne).val();

            idPagnet=$("#idPagnet"+numligne).val();

            quantite=$("#quantite"+numligne).val();

            forme=$("#forme"+numligne).val();

            prixPublic=$("#prixPublic"+numligne).val();
            // alert(prixPublic)
            prixtotal=$("#prixtotal"+numligne).val();

            totalp=$("#totalp"+numligne).val();

            // alert(numligne+"/"+idStock+"/"+designation+"/"+idPagnet+"/"+quantite+"/"+unitevente+"/"+prixunitevente+"/"+prixtotal+"/"+totalp)

            $.ajax({

                url: "ajax/operationPanier-pharmacieAjax.php",

                method: "POST",

                data: {

                    operation: 1,

                    btnRetourApres: 'btnRetourApres',

                    numligne: numligne,

                    idStock: idStock,

                    designation: designation,

                    idPagnet: idPagnet,

                    quantite: quantite,

                    forme: forme,

                    prixPublic: prixPublic,

                    prixtotal: prixtotal,

                    totalp: totalp

                },

                success: function(data) { 

                    // alert(data)

                    /********************************* */

                    if(data == '1') {

                        // alert(1)

                        $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                            // alert(data)

                            $(".loading-gif").hide();

                            $("#msg_rtrnApres_ligne"+numligne).modal('hide')

                            $(".modal-backdrop").remove();

                            $(".img-load-retourApres").hide();

                            // $("#loading_gif_modal").modal("hide");

                        });

                    }else {
                        // alert(2)

                        $("#msg_rtrnApres_ligne"+numligne).modal('hide')

                        $(".modal-backdrop").remove();

                        $('#msg_info_body').html(data);

                        $('#msg_info_1').modal('show');

                        // $("#loading_gif_modal").modal("hide");

                        $(".img-load-retourApres").show();

                    }
                },

                error: function() {

                    alert("La requête ");

                },

                dataType: "text"

            });
        }

    });

    $(document).on("click", ".modeEditionBtn_Ph", function(e) { 
        var id = $(this).attr('id');

        result = id.split('-');

        idPanier = result[1]

        // alert(idPanier);

        

        $.ajax({

            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {

                operation: 32,

                idPanier: idPanier

            },

            dataType: "text",

            success: function(data) {

                // alert(data)closeShowInfoChange

                // $("#clientInput"+idPanier).val('');

                // $("#avanceInput"+idPanier).val('');

                if (data == 1) {

                    window.location.reload();

                } else {

                    $('#msg_edit_pagnet').modal('show');

                }



            },

            error: function() {

                alert("La requête ss");

            }

        });


    });

    $(document).on("click", ".modeEditionBtn_Mt", function(e) { 
        var id = $(this).attr('id');

        result = id.split('-');

        idMutuellePanier = result[1];

        $.ajax({

            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {

                operation: 38,

                idMutuellePanier: idMutuellePanier

            },

            dataType: "text",

            success: function(data) {

                // alert(data)closeShowInfoChange

                // $("#clientInput"+idPanier).val('');

                // $("#avanceInput"+idPanier).val('');

                if (data == 1) {

                    window.location.reload();

                } else {

                    $('#msg_edit_mutuelle'+idMutuellePanier).modal('show');

                }



            },

            error: function() {

                alert("La requête ss");

            }

        });

    });

});


/** Debut Autocomplete Vente Pharmacie*/

    $(function() {

        // alert(6987)
        $(document).on("keyup",".codeBarreLignePh",function(e) {
            // alert(1456)

            e.preventDefault();

            var tabIdPanier = $(this).attr('id');

            tab = tabIdPanier.split('_');

            idPanier = tab[1];

            var query = $(this).val();

            if (query.length > 0) {

                $(this).typeahead({

                    source: function(query, result) {

                        $.ajax({

                            url: "ajax/vendreLigneAjax.php",

                            method: "POST",

                            data: {

                                operation: 3,

                                query: query

                            },

                            dataType: "json",

                            success: function(data) {

                                result($.map(data, function(item) {

                                    return item;

                                }))

                            },

                            error: function() {

                                alert("La requête ss");

                            }

                        });

                    }

                });

                $(this).focus();

                /*********** Quand on tape sur Entrer **************/

                    var keycode = (e.keyCode ? e.keyCode : e.which);

                    if (keycode == '13') {

                        // var designation = $("#ajouterProdFormPh"+idPanier+" .typeahead li.active").text();

                        // tab = designation.split(' => ');

                        // designation = tab[0] || $(this).val();



                        inputVal = $(this).val();

                        

                        if ($.isNumeric(inputVal)) {

                            designation = $(this).val();

                        } else {

                            var designation = $("#ajouterProdFormPh"+idPanier+" .typeahead li.active").text();

                            tab = designation.split(' => ');

                            designation = tab[0];                        

                        }
                        
                        $("#panier_"+idPanier).val('');

                        $.ajax({

                            url: "ajax/vendreLigneAjax.php",

                            method: "POST",

                            data: {

                                operation: 6,

                                designation: designation,

                                idPagnet: idPanier

                            },

                            success: function(data) {

                                result = data.split('<>');

                                if (result[0] == 1) {

                                    if (result[7] == 9) {

                                        var ligne = "<tr>" +

                                            "<td>" + result[2] + "</td>" +

                                            "<td>Montant</td>" +

                                            "<td>" + result[3] + "</td>" +

                                            "<td><input class='form-control filedReadonly"+idPanier+"' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

                                            "<td>" +

                                            "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right btnRtrAvant" + idPanier + "'>" +

                                            "<span class='glyphicon glyphicon-remove'></span>Retour" +

                                            "</button>" +

                                            "</td>" +

                                            "</tr>";

                                    } else {

                                        var ligne = "<tr>" +

                                        "<td>" + result[2] + "</td>" +

                                        "<td><input class='form-control filedReadonly"+idPanier+"' id='ligne"+result[5]+"' onkeyup='modif_Quantite_PhP(this.value," + result[5] + "," + idPanier + ")' value='1' style='width: 70%' type='number' ></input></td>" +

                                        "<td>" + result[3] + "</td>" +

                                        "<td><input class='form-control filedReadonly"+idPanier+"' id='prixPublic"+result[5]+"' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

                                        "<td>" +

                                        "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right btnRtrAvant" + idPanier + "'>" +

                                        "<span class='glyphicon glyphicon-remove'></span>Retour" +

                                        "</button>" +

                                        "</td>" +

                                        "</tr>";

                                    }           

                                    $("#tablePanier" + idPanier).prepend(ligne);

                                    // $(".lic").text('');

                                    // $(this).val('');

                                    $("#panier_"+idPanier).val('');

                                    // $(".codeBarreLignePh").val('valueeeeeeeeee');

                                    // $(this).focus();

                                    $("#somme_Total" + idPanier).text(result[6]);

                                    $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

                                }

                                if (result[0] == 2) {

                

                                    $("#tablePanier"+idPanier+" tr").each(function(row, tr) {

                                        reference = $(tr).find('td:eq(0)').text();

                                        if (reference != '' && reference == result[2]) {

                                            $(tr).find('td:eq(1)').html("<input class='form-control filedReadonly"+idPanier+"' onkeyup='modif_Quantite_PhP(this.value," + result[5] + "," + idPanier + ")' value='" + result[7] + "' style='width: 70%' type='number'></input>");

                                            $("#panier_"+idPanier).val('');

                                            $("#somme_Total" + idPanier).text(result[6]);

                                            $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

                                        }

                                    });

                                }

                                if (result[0] == 3) {

                                    $("#qte_stock").text(result[1]);

                                    $('#msg_info_js').modal('show');

                                    $("#panier_"+idPanier).val('');

                                }

                                if (result[0] == 4) {

                                    var ligne = "<tr>" +

                                    "<td>" + result[2] + "</td>" +

                                    "<td><input class='form-control filedReadonly"+idPanier+"' onkeyup='modif_QuantiteSDP(this.value," + result[5] + "," + idPanier + ")' value='1' style='width: 70%' type='number' ></input></td>" +

                                    "<td>" + result[3] + "</td>" +

                                    "<td><input class='form-control filedReadonly"+idPanier+"' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

                                    "<td>" +

                                    "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right btnRtrAvant" + idPanier + "'>" +

                                    "<span class='glyphicon glyphicon-remove'></span>Retour" +

                                    "</button>" +

                                    "</td>" +

                                    "</tr>";

                

                                    $("#tablePanier"+idPanier).prepend(ligne);

                                    $("#panier_"+idPanier).val('');

                                    $("#somme_Total" + idPanier).text(result[6]);

                                    $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

                                }

                            },

                            error: function() {

                                alert("La requête ");

                            },

                            dataType: "text"

                        });

                        idFormParent=$("#panier_"+idPanier).parent().attr("id");

                        // alert(idFormParent+'/////////')

                        // setTimeout(() => {            

                        $('#'+idFormParent+' .typeahead').html('')

                    }

                /*********** Fin tape sur Entrer **************/

            }

            else{

                

                idFormParent=$("#panier_"+idPanier).parent().attr("id");

                // alert(idFormParent+'/////////')

                // setTimeout(() => {            

                $('#'+idFormParent+' .typeahead').html('')

            }

        });

    });

    $(function() {

        $("#btnEnregistrerCodeBarreAjaxPh").click(function() {

            var idPanier = $("#idPagnet").val();

            var designation = $("#codeBarreLignePh").val();

            $.ajax({

                url: "ajax/vendreLigneAjax.php",

                method: "POST",

                data: {

                    operation: 6,

                    designation: designation,

                    idPagnet: idPanier

                },

                success: function(data) {

                    result = data.split('<>');

                    if (result[0] == 1) {

                        var ligne = "<tr>" +

                            "<td>" + result[2] + "</td>" +

                            "<td><input class='form-control filedReadonly"+idPanier+"' id='ligne"+result[5]+"' onkeyup='modif_Quantite_PhP(this.value," + result[5] + "," + idPanier + ")' value='1' style='width: 70%' type='number' ></input></td>" +

                            "<td>" + result[3] + "</td>" +

                            "<td><input class='form-control filedReadonly"+idPanier+"' id='prixPublic"+result[5]+"' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

                            "<td>" +

                            "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right btnRtrAvant" + idPanier + "'>" +

                            "<span class='glyphicon glyphicon-remove'></span>Retour" +

                            "</button>" +

                            "</td>" +

                            "</tr>";

                        $("table.tabPanier").prepend(ligne);

                        $(".lic").text('');

                        $("#codeBarreLignePh").val('');

                        $("#codeBarreLignePh").focus();

                        $("#somme_Total" + idPanier).text(result[6]);

                        $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

                    }

                    if (result[0] == 2) {



                        $('#tablePanier tr').each(function(row, tr) {

                            reference = $(tr).find('td:eq(0)').text();

                            if (reference != '' && reference == result[2]) {

                                $(tr).find('td:eq(1)').html("<input class='form-control filedReadonly"+idPanier+"' onkeyup='modif_Quantite_PhP(this.value," + result[5] + "," + idPanier + ")' value='" + result[7] + "' style='width: 70%' type='number'></input>");

                                $(".lic").text('');

                                $("#codeBarreLignePh").val('');

                                $("#codeBarreLignePh").focus();

                                $("#somme_Total" + idPanier).text(result[6]);

                                $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

                            }

                        });

                    }

                    if (result[0] == 3) {

                        $("#qte_stock").text(result[1]);

                        $('#msg_info_js').modal('show');

                        $(".lic").text('');

                        $("#codeBarreLignePh").val('');

                        $("#codeBarreLignePh").focus();

                    }

                    //console.log(data);

                },

                error: function() {

                    alert("La requête ");

                },

                dataType: "text"

            });

        });

        $(document).on('click', "#closeInfo", function() {

            // alert(100)

            $("#msg_info_1").attr('class', "modal fade out")

            $(".modal-backdrop").remove();

        });

    });

/** Fin Autocomplete Vente Pharmacie*/

/** Debut Autocomplete Vente Pharmacie Mutuelle*/

$(document).ready(function() {
    // alert(2586)
    /** Debut Autocomplete Mutuelle Pharmacie*/

    $(document).on("keyup", ".codeBarreLigneMutuelle", function(e) {

        e.preventDefault();

        var tabIdPanier = $(this).attr('id');

        tab = tabIdPanier.split('_');

        idMutuellePagnet = tab[1];

        var query = $(this).val();
        // alert(query)

        if (query.length > 0) {

            $(this).typeahead({

                source: function(query, result) {

                    $.ajax({

                        url: "ajax/vendreLigneAjax.php",

                        method: "POST",

                        data: {

                            operation: 20,

                            query: query

                        },

                        dataType: "json",

                        success: function(data) {

                            result($.map(data, function(item) {

                                return item;

                            }))

                        },

                        error: function() {

                            alert("La requête ss");

                        }

                    });

                }

            });

            $(this).focus();

            /*********** Quand on tape sur Entrer **************/

                var keycode = (e.keyCode ? e.keyCode : e.which);

                if (keycode == '13') {

                    inputVal = $(this).val();

                    

                    if ($.isNumeric(inputVal)) {



                        designation = $(this).val();



                    } else {

                        var designation = $("#ajouterProdFormMutuelle"+idMutuellePagnet+" .typeahead li.active").text();

                        tab = designation.split(' => ');

                        designation = tab[0];                        

                    }
                    
                    $("#panier_"+idMutuellePagnet).val('');
                    // var designation = $("#ajouterProdFormMutuelle"+idMutuellePagnet+" .typeahead li.active").text();

                    // tab = designation.split(' => ');

                    // designation = tab[0] || $("#panier_"+idMutuellePagnet).val();

                    $.ajax({

                        url: "ajax/vendreLigneAjax.php",

                        method: "POST",

                        data: {

                            operation: 21,

                            designation: designation,

                            idMutuellePagnet: idMutuellePagnet

                        },

                        success: function(data) {

                            result = data.split('<>');

                            if (result[0] == 1) {

                                if (result[7] == 9) {

                                    var ligne = "<tr>" +

                                        "<td>" + result[2] + "</td>" +

                                        "<td>Montant</td>" +

                                        "<td>" + result[3] + "</td>" +

                                        "<td><input class='form-control filedReadonly_M"+idMutuellePagnet+"' id='ligne"+result[5]+"' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idMutuellePagnet + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

                                        "<td>" +

                                        "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idMutuellePagnet + ")' class='btn btn-warning pull-right btnRtrAvant_M" + idMutuellePagnet + "'>" +

                                        "<span class='glyphicon glyphicon-remove'></span>Retour" +

                                        "</button>" +

                                        "</td>" +

                                        "</tr>";

                                } else {

                                    var ligne = "<tr>" +

                                        "<td>" + result[2] + "</td>" +

                                        "<td><input class='form-control filedReadonly_M"+idMutuellePagnet+"' id='ligne"+result[5]+"' onkeyup='modif_Quantite_Mutuelle(this.value," + result[5] + "," + idMutuellePagnet + ")' value='1' style='width: 70%' type='number' ></input></td>" +

                                        "<td>" + result[3] + "</td>" +

                                        "<td><input class='form-control filedReadonly_M"+idMutuellePagnet+"'  id='prixPublic"+result[5]+"' onkeyup='modif_Prix_Mutuelle(this.value," + result[5] + "," + idMutuellePagnet + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

                                        "<td>" +

                                        "<button type='button' onclick='retour_Produit_Mutuelle(" + result[5] + "," + idMutuellePagnet + ")' class='btn btn-warning pull-right btnRtrAvant_M" + idMutuellePagnet + "'>" +

                                        "<span class='glyphicon glyphicon-remove'></span>Retour" +

                                        "</button>" +

                                        "</td>" +

                                        "</tr>";

                                }

            

                                $('#tableMutuelle'+idMutuellePagnet).prepend(ligne);

                                $("#panier_"+idMutuellePagnet).val('');

                                $("#somme_Total" + idMutuellePagnet).text(result[6]);

                                $("#somme_Apayer" + idMutuellePagnet).text(result[6]/2);

                                $("#somme_Apayer" + idMutuellePagnet).text(result[6] - ((result[6] * result[7]) /100 ));

                            }

                            if (result[0] == 2) {

            

                                $('#tableMutuelle'+idMutuellePagnet+' tr').each(function(row, tr) {

                                    reference = $(tr).find('td:eq(0)').text();

                                    if (reference != '' && reference == result[2]) {

                                        $(tr).find('td:eq(1)').html("<input class='form-control' onkeyup='modif_Quantite_Mutuelle(this.value," + result[5] + "," + idMutuellePagnet + ")' value='" + result[7] + "' style='width: 70%' type='number'></input>");

                                        $("#panier_"+idMutuellePagnet).val('');

                                        $("#somme_Total" + idMutuellePagnet).text(result[6]);

                                        $("#somme_Apayer" + idMutuellePagnet).text(result[6] - ((result[6] * result[8]) /100 ));

                                    }

                                });

                            }

                            if (result[0] == 3) {

                                $("#qte_stock").text(result[1]);

                                $('#msg_info_js').modal('show');

                                $("#panier_"+idMutuellePagnet).val('');

                            }

                        },

                        error: function() {

                            alert("La requête ");

                        },

                        dataType: "text"

                    });

                    idFormParent=$("#panier_"+idMutuellePagnet).parent().attr("id");

                    // alert(idFormParent+'/////////')

                    // setTimeout(() => {            

                    $('#'+idFormParent+' .typeahead').html('')

                }

                /*********** Fin tape sur Entrer **************/

        }

        else{

            

            idFormParent=$("#panier_"+idMutuellePagnet).parent().attr("id");

            // alert(idFormParent+'/////////')

            // setTimeout(() => {            

            $('#'+idFormParent+' .typeahead').html('')

        }

    });

    /** Fin Autocomplete Mutuelle Pharmacie*/

})

$(function() {
    // alert(55858)

    $(document).on("click", ".btn_Termine_Panier_M", function(e) {  
        // alert(25879)

        e.preventDefault();

        idMutuellePagnet = $(this).attr('data-idPanier')

        codeBeneficiaire = $("#codeBeneficiaire"+idMutuellePagnet).val();

        codeAdherantMutuelle = $("#codeAdherantMutuelle"+idMutuellePagnet).val();

        // alert(parseInt(taux_m)+1);
        
        nom_m=$('#mutuellePagnet'+idMutuellePagnet+" option:selected").text()

        mutuelle = $("#mutuellePagnet"+idMutuellePagnet).val();

        numeroRecu = $("#numeroRecu"+idMutuellePagnet).val();

        dateRecu = $("#dateRecu"+idMutuellePagnet).val();

        nomAdherant = $("#clientMutuelle"+idMutuellePagnet).val();

        compte = $("#compte"+idMutuellePagnet).val();

        compte_m=$('#compte'+idMutuellePagnet+" option:selected").text()

        clientInput=$('#clientInput'+idMutuellePagnet).val()

        avanceInput=$('#avanceInput'+idMutuellePagnet).val()

        compteAvance=$('#compteAvance'+idMutuellePagnet).val()

        compteAvance_m=$('#compteAvance'+idMutuellePagnet+" option:selected").text()

        total_m = $("#somme_Total"+idMutuellePagnet).text();

        apayer_adherant = $("#somme_Apayer"+idMutuellePagnet).text();

        versement = $("#versementMutuelle"+idMutuellePagnet).val();
        
        $('#nom_m'+idMutuellePagnet).text(nom_m);
        $('#adherant_m'+idMutuellePagnet).text(nomAdherant);
        $('#codeAdherant_m'+idMutuellePagnet).text(codeAdherantMutuelle);
        $('#codeBeneficiaire_m'+idMutuellePagnet).text(codeBeneficiaire);
        $('#numeroRecu_m'+idMutuellePagnet).text(numeroRecu);
        $('#dateRecu_m'+idMutuellePagnet).text(dateRecu);
        
        if (mutuelle && nomAdherant && codeAdherantMutuelle && codeBeneficiaire && numeroRecu && dateRecu && nom_m) {
            
            if (compte=="2" && clientInput=="") {

                $('#msg_info_ClientRequired').modal('show');

            } else {

                $('#cache_btn_Terminer_M'+idMutuellePagnet).hide();

                $("#panelPanier"+idMutuellePagnet).attr('class','panel panel-info');
                
                $("#panierEncoursMutuelle"+idMutuellePagnet).html('Imputation');

                $('.btn_Retourner_Produit'+idMutuellePagnet).prop('disabled', true);

                $("#btnContent_M"+idMutuellePagnet ).show();

                $(".btnRtrAvant_M"+idMutuellePagnet).attr('class','btn btn-danger pull-right disabled');

                $(".btn_Retourner_Produit_M"+idMutuellePagnet).attr('class','btn btn-danger pull-right disabled');

                $(".img-load-terminer-M").show();

                $(".btn_Termine_Panier_M").attr("disabled","disabled");
                
                $(".filedReadonly_M"+idMutuellePagnet).each(function(i, obj) {
                    id=$(this).attr('id')

                    var originalForm = $('#'+id);
                    var div = $('<div/>',{
                        text : originalForm.val(),
                        id : originalForm.attr('id')
                    });
                    $('#'+id).replaceWith(div);
                });
                
                $('#total_m'+idMutuellePagnet).text(total_m);

                $('#apayer_adherant'+idMutuellePagnet).text(apayer_adherant);
                        
                if (compte) {

                    // alert(11233)

                    $("#compte_m"+idMutuellePagnet).text(compte_m);

                    $("#divcompte_m"+idMutuellePagnet).show();

                }

                if (avanceInput > 0) {

                    // alert(11233)

                    $("#avance_m"+idMutuellePagnet).text(avanceInput);

                    $('#reste_m'+idMutuellePagnet).text(parseInt(apayer_adherant) - parseInt(avanceInput));

                    $('#compteAvance_m'+idMutuellePagnet).text(compteAvance_m);

                    $("#divavance_m"+idMutuellePagnet).show();

                }

                if (versement > 0) {

                    // alert(11233)

                    $('#versement_m'+idMutuellePagnet).text(versement);

                    $("#divversement_m"+idMutuellePagnet).show();

                    

                    $('#rendu_m'+idMutuellePagnet).text(parseInt(versement) - parseInt(apayer_adherant));

                    $("#divrendu_m"+idMutuellePagnet).show();

                }

                $("#footerContent_M"+idMutuellePagnet ).show();

                $.ajax({

                    url: 'ajax/operationPanier-pharmacieAjax.php',

                    method: 'POST',

                    data: {

                        idMutuellePagnet: idMutuellePagnet,

                        mutuelle: mutuelle,

                        terminerImputation: "terminerImputation",

                        codeBeneficiaire: codeBeneficiaire,

                        numeroRecu : numeroRecu,

                        nomAdherant : nomAdherant,

                        codeAdherant : codeAdherantMutuelle,

                        compte : compte,

                        dateRecu : dateRecu,

                        clientInput : clientInput,

                        avanceInput : avanceInput,

                        compteAvance : compteAvance,

                        versement : versement

                    },

                    success: function(data) {
                        // alert(data)
                        if (data == '') {                            

                        } else {                     
                            
                            $('#msg_info_body').html(data);

                            $('#msg_info_1').modal('show');

                            $(".img-load-terminer").hide();


                            $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                                // alert(data)

                                $(".loading-gif").hide();

                                // $("#loading_gif_modal").modal("hide");

                            });
                            // $('.codeBarreLigneMutuelle').focus()   

                            // $('.cache_btn_Terminer').show();   

                            // $('#p_msg_info_TerminerImputation').html(data)

                            // $('#msg_info_TerminerImputation').modal('show')                  

                        }

                    },

                    error: function(err) {

                        alert(err)

                    },

                    dataType: 'text'

                });

                // alert(idMutuellePagnet+"/88")        

            }
            
        } else {
            // alert('infos manquantes')
            $('#msg_info_body').html("Manque d'informations concernant la mutuelle.");

            $('#msg_info_1').modal('show');
        }
    });

    
    $(document).on("click", ".btnAnnulerImputation", function(e) {  

        // alert('annuler')
        // $("#loading_gif_modal").modal("show");

        $(".img-load-annulerPanier").show();

        $(".btnAnnulerImputation").attr("disabled","disabled");

        idMutuellePagnet=$(this).attr('data-idPanier');
        // alert(idPanier)

        $.ajax({

            url: "ajax/operationPanier-pharmacieAjax.php",

            method: "POST",

            data: {

                operation: 1,

                btnAnnulerImputation: 'btnAnnulerImputation',

                idMutuellePagnet: idMutuellePagnet,

            },

            success: function(data) { 

                // alert(data)

                /****************msg_ann_pagnet***************** */

                if(data == '1'){

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();



                        $("#msg_ann_pagnet"+idPanier).modal('hide')

                        $(".modal-backdrop").remove();

                        $(".img-load-annulerPanier").hide();

                        // $("#loading_gif_modal").modal("hide");

                    });



                }else {

                    

                    $('#msg_info_body').html("<p>IMPOSSIBLE.</br></br> Erreur lors de l'annulation du panier n° "+idPanier+". </br></br></p>");

                    $('#msg_info_1').modal('show');

                    $(".img-load-annulerPanier").hide();

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        // $("#loading_gif_modal").modal("hide");

                    });

                }

                

            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });

    })

    
    $(document).on("click", ".btnRetournerImputation", function(e) {  

        // $("#loading_gif_modal").modal("show");
        // alert(1556)

        $(".img-load-retournerPanier").show();

        $(".btnRetournerImputation").attr("disabled","disabled");

        idMutuellePagnet=$(this).attr('data-idPanier');
        // alert(idMutuellePagnet)

        $.ajax({

            url: "ajax/operationPanier-pharmacieAjax.php",

            method: "POST",

            data: {

                operation: 1,

                btnRetournerImputation: 'btnRetournerImputation',

                idMutuellePagnet: idMutuellePagnet,

            },

            success: function(data) { 

                // alert(data)

                /**************** msg_rtrn_pagnet ***************** */

                if(data == '1'){

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        $("#msg_ann_pagnet"+idPanier).modal('hide')

                        $(".modal-backdrop").remove();

                        $(".img-load-retournerPanier").hide();

                        $('body').attr('style','overflow-y:scroll;')

                        // $("#loading_gif_modal").modal("hide");

                    });

                } else {

                    $('#msg_info_body').html(data);

                    $('#msg_info_1').modal('show');

                    $(".img-load-retournerPanier").hide();

                    $("#pharmacie-venteContent" ).load( "ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                        // alert(data)

                        $(".loading-gif").hide();

                        $('body').attr('style','overflow-y:scroll;')

                        // $("#loading_gif_modal").modal("hide");

                    });

                }

            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });

    })

});

/** Debut modifier le nom du Client d'un Pagnet Mutuelle Pharmacie*/

$(function() {

    $(document).on("keyup",".clientMutuelleInput",function(e) {

        e.preventDefault();

        typeClient = 'mutuelle';

        var query = $(this).val();         

        var idMutuellePagnet = $(this).attr('data-idPanier');

        if (query.length > 0) {

            $(this).typeahead({

                source: function(query, result) {

                    $.ajax({

                        url: "ajax/vendreLigneAjax.php",

                        method: "POST",

                        data: {

                            operation: 23,

                            query: query,

                            idMutuellePagnet: idMutuellePagnet

                        },

                        dataType: "json",

                        success: function(data) {

                            result($.map(data, function(item) {

                                return item;

                            }))

                        },

                        error: function() {

                            alert("La requête ss");

                        }

                    });

                }

            });

            $(this).focus();

            /*********** Quand on tape sur Entrer **************/

                var keycode = (e.keyCode ? e.keyCode : e.which);

                if (keycode == '13') {

                    var client = $("#ajouterProdFormMutuelle"+idMutuellePagnet+" .reponseClient .typeahead li.active").text();

                    $.ajax({

                        url: 'ajax/vendreLigneAjax.php',

                        method: 'POST',

                        data: {

                            operation: 24,

                            client: client,

                            idMutuellePagnet : idMutuellePagnet

                        },

                        success: function(data) {

                            $(this).val(client);

                        },

                        dataType: 'text'

                    });

                    typeClient = '';

                    

                }

                /*********** Fin tape sur Entrer **************/

        }

    });

});

/** Fin modifier le nom du Client d'un Pagnet Mutuelle Pharmacie*/


/** Debut Rechercher Produit Vendu Aujourd'hui*/

$(function() {

    // alert(111)
        $(document).on("keyup", "#produitVendu2", function(e) {

        e.preventDefault();

        var query = $("#produitVendu2").val();

        // alert(query)

        if (query.length > 0 || query.length != '') {

            $("#produitVendu2").typeahead({

                source: function(query, result) {

                    $.ajax({

                        url: "ajax/vendreLigneAjax.php",

                        method: "POST",

                        data: {

                            operation: 8,

                            query: query

                        },

                        dataType: "json",

                        success: function(data) {

                            result($.map(data, function(item) {

                                return item;

                            }))

                        },

                        error: function() {

                            alert("La requête ss");

                        }

                    });

                }

            });

            $("#produitVendu2").focus();            

            /*********** Quand on tape sur Entrer **************/

            var keycode = (e.keyCode ? e.keyCode : e.which);

            if (keycode == '13') {

                // var designation = $("#searchProdForm .typeahead li.active").text();

                // $("#produitVendu2").val(designation);

                // $("#searchProdForm").submit();

                $(".img-load-search").show();

                var designation = $("#searchProdForm .typeahead li.active").text();
                // alert(designation)
                $("#produitVendu2").val(designation);

                $("#pharmacie-venteContent" ).load("ajax/loadContainer-pharmacieAjax.php", {"produit":designation}, function(data){ //get content from PHP page

                    // alert(data)

                    // $("#loading_gif_modal").modal("hide");

                    $("#produitVendu2").val(designation);

                    $(".img-load-search").hide();

                });

            }

            $(document).on('click', '#searchProdForm .typeahead li a[class="dropdown-item"]', function(e) {

                e.preventDefault();

                $(".img-load-search").show();

                var designation = $("#searchProdForm .typeahead li.active").text();

                $("#produitVendu2").val(designation);

                $("#pharmacie-venteContent" ).load("ajax/loadContainer-pharmacieAjax.php", {"produit":designation}, function(data){ //get content from PHP page

                    // alert(data)

                    // $("#loading_gif_modal").modal("hide");

                    $("#produitVendu2").val(designation);

                    $(".img-load-search").hide();

                });

                // var designation = $("#searchProdForm .typeahead li.active").text();

                // $("#produitVendu2").val(designation);

                // $("#searchProdForm").submit();

            });

        } else {

            // window.location.href = "insertionLigneLight.php";

            $(".img-load-search").show();

            // window.location.href = "insertionLigneLight.php";

            $("#pharmacie-venteContent" ).load("ajax/loadContainer-pharmacieAjax.php", function(data){ //get content from PHP page

                // alert(data)

                // $("#loading_gif_modal").modal("hide");

                $(".img-load-search").hide();

            });

        }

    });

    $("#produitVendu2").val($("#produitAfter").val());

});

/** Fin Rechercher Produit Vendu Aujourd'hui*/

/** Debut Rechercher le Prix d'un Produit*/

$(function() {

    $(document).on("keyup", "#designationInfo", function(e) {

        e.preventDefault();

        var query = $("#designationInfo").val();
        
        if (query.length > 0 || query.length != '') {

            $("#designationInfo").typeahead({

                source: function(query, result) {

                    $.ajax({

                        url: 'ajax/designationInfo.php',

                        method: "POST",

                        data: {
                            query: query
                        },

                        dataType: "json",

                        success: function(data) {

                            result($.map(data, function(item) {

                                return item;

                            }))

                        },

                        error: function() {

                            //alert("La requête ss");

                        }

                    });

                }

            });

            $("#designationInfo").focus();            

            /*********** Quand on tape sur Entrer **************/

            var keycode = (e.keyCode ? e.keyCode : e.which);

            if (keycode == '13') {

                var designation = $("#searchDesignationForm .typeahead li.active").text();
                
                $("#designationInfo").val(designation);


            }

            $(document).on('click', '#searchDesignationForm .typeahead li a[class="dropdown-item"]', function(e) {

                e.preventDefault();

                var designation = $("#searchDesignationForm .typeahead li.active").text();

                $("#designationInfo").val(designation);

            });

        } else {

        }

    });


});

/** Fin Rechercher le Prix d'un Produit*/