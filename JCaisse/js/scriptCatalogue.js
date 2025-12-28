//////////////////////////////////////////////////////////////////////////////
///////////////////////////////DEBUT PHARMACIE ////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////


/*************** Début modification Catalogue  details Pharmacie***************/
$(document).ready(function() {

    nbEntreePh = $('#nbEntreePhD').val();

    $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":1,"nbEntreePh":nbEntreePh,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsProductsPDesign").on( "click", ".pagination a", function (e){
    // $("#resultsProductsPDesign").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)n
        nbEntreePh = $('#nbEntreePhD').val()
        query = $('#searchInputPhD').val();

        if (query.length == 0) {
            $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"page":page,"operation":1,"nbEntreePh":nbEntreePh,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"page":page,"operation":1,"nbEntreePh":nbEntreePh,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
        // $("#resultsProductsPDesign").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
    });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {
    // alert(4)
    $('#searchInputPhD').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputPhD').val();
        nbEntreePh = $('#nbEntreePhD').val();
        id = $('#idCDPH').val();

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            t = 1; // code barre
            // inputVal = $('#searchInputPhD').val();
            // if ($.isNumeric(inputVal)) {
            if (query.length > 0) { 

                $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":3,"nbEntreePh":nbEntreePh,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":3,"nbEntreePh":nbEntreePh,"query":"","cb":t}); //load initial records
            }     
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                
                if (query.length > 0) {
                    // alert(2222)                
                    $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":3,"nbEntreePh":nbEntreePh,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":3,"nbEntreePh":nbEntreePh,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreePh').on("change", function(e) {
        e.preventDefault();

        nbEntreePh = $('#nbEntreePhD').val()
        query = $('#searchInputPhD').val();

        if (query.length == 0) {
            $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":4,"nbEntreePh":nbEntreePh,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":4,"nbEntreePh":nbEntreePh,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsProductsPDesign" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
    });

});

/*************    TRI    ******************/
$(document).ready(function() {
    // alert(1500)
    // var page = 0;
    //executes code below when user click on pagination links
    //1=croissant et 0=decroissant
    triPH = 0;
    $(document).on("click", "#resultsProductsPDesign th", function(e) {
        e.preventDefault();
    // alert(12)
        query = $('#searchInputPhD').val();
        nbEntreePh = $('#nbEntreePhD').val()
        // $("#resultsProductsPDesign" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":1, "query":""}); //load initial records
        
        if (triPH == 1) {
            // alert(triPH)
            if (query.length == 0) {
                $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":1, "query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsPDesign" ).load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":1, "query":query,"cb":""}); //load initial records
            }

            triPH = 0;
            // alert(triPH)
            
        } else {
            // alert(triPH)
            if (query.length == 0) {
                $("#resultsProductsPDesign").load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":0, "query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsPDesign").load( "ajax/catalog/listerCataloguePhAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":0, "query":query,"cb":""}); //load initial records
            }
            
            triPH = 1;
            // alert(triPH)
        }
    });
});

/*************** Fin modification Catalogue details Pharmacie***************/

/**Debut Modifier Produit Pharmacie */
function mdf_Designation_PH(idDesignation,id,ordre){
    var designation = $('#designationD-' + idDesignation).val();
    var categorie = $('#categorieD-' + idDesignation).val();
    var forme = $('#formeD-' + idDesignation).val();
    var tableau = $('#tableauD-' + idDesignation).val();
    var prixSession = $('#prixSessionD-' + idDesignation).val();
    var prixPublic = $('#prixPublicD-' + idDesignation).val();
    var prixPublic = $('#prixPublicD-' + idDesignation).val();
    var codeBarreDesignation = $('#codebarreD-' + idDesignation).val();
  
    // console.log('GGGGggggggggggGGGG'+idDesignation);
    $.ajax({
        url: "ajax/catalog/catalogueSqlAjax.php",
        method: "POST",
        data: {
            operation: 'EditProduit_PH',
            idDesignation : idDesignation,
            id : id,
            designation : designation,
            categorie : categorie,
            forme : forme,
            tableau : tableau,
            prixSession : prixSession,
            prixPublic : prixPublic,
            codeBarreDesignation : codeBarreDesignation
        },
        success: function(data) {
            tab = data.split('<>');
            if (tab[0] == 1) {
                $('#pencilmoD-'+ idDesignation).remove();
                $('#designationD-' + idDesignation).prop('disabled', true);
                $('#categorieD-' + idDesignation).prop('disabled', true);
                $('#formeD-' + idDesignation).prop('disabled', true);
                $('#tableauD-' + idDesignation).prop('disabled', true);
                $('#prixSessionD-' + idDesignation).prop('disabled', true);
                $('#prixPublicD-' + idDesignation).prop('disabled', true);
                $('#prixPublicD-' + idDesignation).prop('disabled', true);
                $('#codebarreD-' + idDesignation).prop('disabled', true);
            } else {
                $('#msg_info_js').modal('show');
            }
            //console.log(data);
        },
        error: function() {
            alert("La requête 2");
        },
        dataType: "text"
    });
  }
/**Debut Supprimer Produit Pharmacie */
function spm_Designation_PH(idDesignation,id,ordre){
    $.ajax({
        url:"ajax/catalog/catalogueSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchProduit_PH',
            idDesignation : idDesignation,
            id : id,
        },
        success: function (data) {
            result=data.split('<>');
            $('#ordre_Spm').val(ordre);
            $('#id_Spm').val(id);
            $('#idDesignation_Spm').val(result[0]);
            $('#designation_Spm').text(result[1]);
            $('#categorie_Spm').text(result[2]);
            $('#forme_Spm').text(result[3]);
            $('#tableau_Spm').text(result[4]);
            $('#prixSession_Spm').text(result[5]);
            $('#prixPublic_Spm').text(result[6]);
            $('#supprimerDesignation_PH').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
$(function(){
    $("#btnSupprimerDesignation_PH").click( function(){
        var ordre=$('#ordre_Spm').val();
        var id=$('#id_Spm').val();
        var idDesignation=$('#idDesignation_Spm').val();
        $.ajax({
            url:"ajax/catalog/catalogueSqlAjax.php",
            method:"POST",
            data:{
                operation: 'DeleteProduit_PH',
                idDesignation : idDesignation,
                id : id,
            },
            success: function (data) {
                result=data.split('<>');
                if(result[0]==1){
                    $('#supprimerDesignation_PH').modal('hide');
                    var table = $('#tableDesignation').DataTable();
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    ligne[0] = result[1];
                    ligne[1] = 'idFusion';
                    ligne[2] = result[2]; ligne[3] = result[3];
                    ligne[4] = result[4]; ligne[5] = result[5];
                    ligne[6] = result[6]; ligne[7] = 'En cours';
                    $('#tableDesignation').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'red'});
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Supprimer Produit Pharmacie */


/**Debut categorie categorie par click**/
function tabCategClick(idCataT){
    /*************** Début modification categorie details boutique***************/
    $(document).ready(function() {

        nbEntreeCatPh = $('#nbEntreeCatPh').val()
        id = $('#idCDCatPH').val();

        $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"operation":1,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsCatPh").on( "click", ".pagination a", function (e){
        // $("#resultsProductsCatPh").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)

            nbEntreeCatPh = $('#nbEntreeCatPh').val()
            query = $('#searchInputCatPh').val();

            if (query.length == 0) {
                $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsProductsCatPh").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
        });
    });

    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {

        $('#searchInputCatPh').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputCatPh').val()
            nbEntreeCatPh = $('#nbEntreeCatPh').val()

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                // alert(1111)
                t = 1; // code barre
                
                if (query.length > 0) {
                    
                    $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"operation":3,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"operation":3,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":t}); //load initial records
                }
            }else{
                // alert(2222)
                t = 0; // no code barre
                setTimeout(() => {
                    if (query.length > 0) {
                        
                        $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"operation":3,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"operation":3,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreeCatPh').on("change", function(e) {
            e.preventDefault();

            nbEntreeCatPh = $('#nbEntreeCatPh').val()
            query = $('#searchInputCatPh').val();

            if (query.length == 0) {
                $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"operation":4,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsCatPh" ).load( "ajax/catalog/listerCategoriesPhAjax.php",{"operation":4,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsCatPh" ).load( "ajax/listerProduitAjax.php",{"operation":4,"nbEntreeBC":nbEntreeBC}); //load initial records
        });

    });
    /*if ($.fn.DataTable.isDataTable('#tableCategorie')) {
          $('#tableCategorie').DataTable().destroy();
      }
    // console.log(idD+" "+idCT+" "+ordre);

    $("#tableCategorie").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/catalogueDetailsCategoriesAjax.php?id="+idCataT,
    "aoColumns": [
        { mData: "0" } ,
        { mData: "1" } ,
        { mData: "2" },
        { mData: "3" }
      ],

    });*/
}
/**Fin categorie categorie catalogueTotal**/

/**Debut Forme par click**/
function tabFormeClick(idCataT){
     /*************** Début modification categorie details boutique***************/
     $(document).ready(function() {

        nbEntreeFoPh = $('#nbEntreeFoPh').val()
        id = $('#idCFoPH').val();

        $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"operation":1,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsFoPh").on( "click", ".pagination a", function (e){
        // $("#resultsProductsFoPh").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)

            nbEntreeFoPh = $('#nbEntreeFoPh').val()
            query = $('#searchInputFoPhD').val();

            if (query.length == 0) {
                $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsProductsFoPh").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
        });
    });

    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {

        $('#searchInputFoPhD').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputFoPhD').val()
            nbEntreeFoPh = $('#nbEntreeFoPh').val()

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                // alert(1111)
                t = 1; // code barre
                
                if (query.length > 0) {
                    
                    $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"operation":3,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"operation":3,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":t}); //load initial records
                }
            }else{
                // alert(2222)
                t = 0; // no code barre
                setTimeout(() => {
                    if (query.length > 0) {
                        
                        $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"operation":3,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"operation":3,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreeFoPh').on("change", function(e) {
            e.preventDefault();

            nbEntreeFoPh = $('#nbEntreeFoPh').val()
            query = $('#searchInputFoPhD').val();

            if (query.length == 0) {
                $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"operation":4,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsFoPh" ).load( "ajax/catalog/listerFormePhAjax.php",{"operation":4,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsFoPh" ).load( "ajax/listerProduitAjax.php",{"operation":4,"nbEntreeBC":nbEntreeBC}); //load initial records
        });

    });       
}
/**Fin Forme catalogueTotal**/

/**Debut Modifier Forme  */
function mdf_FormePh(idForme,idCataT,ordre){
    var formeD = $('#formeD-' + idForme).val();
    var forme_oldD = $('#forme_oldD-' + idForme).val();
    $.ajax({
        url: "ajax/catalog/formeSqlAjax.php",
        method: "POST",
        data: {
            operation: 'EditForme_PH',
            idForme : idForme,
            id : idCataT,
            nomForme : formeD,
            forme_oldD : forme_oldD,
        },
        success: function(data) {
            tab = data.split('<>');
            if (tab[0] == 1) {
                $('#pencilmoDF-'+ idForme).remove();
                $('#formeD-' + idForme).prop('disabled', true);
            } else {
                $('#msg_info_js').modal('show');
            }
            //console.log(data);
        },
        error: function() {
            alert("La requête 2");
        },
        dataType: "text"
    });
  }  
/**Fin Modifier Forme **/

/**Debut Supprimer Forme */
function spm_FormePh(idForme,id,ordre){
    $.ajax({
        url:"ajax/catalog/formeSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchForme_PH',
            idForme : idForme,
            id : id,
        },
        success: function (data) {
            result=data.split('<>');
            $('#ordre_SpmForme').val(ordre);
                $('#id_SpmFormtyp').val(id);
                $('#idForme_Spm').val(idForme);
            console.log(result[1]);
            $('#forme_SpmPh').text(result[1]);
            $('#nomForme_Spme').val(result[1]);
            $('#supprimerForme').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}

$(function(){
    $("#btnSupprimerForme").click( function(){
        
        var ordre=$('#ordre_SpmForme').val();
        var id=$('#id_SpmFormtyp').val();
        var idForme=$('#idForme_Spm').val();
        var forme=$('#nomForme_Spme').val();
        var formeRempl=$('#formeRempl').val();
        $.ajax({
            url:"ajax/catalog/formeSqlAjax.php",
            method:"POST",
            data:{
                operation: 'DeleteForme_PH',
                idForme : idForme,
                id : id,
                forme : forme,
                formeRempl : formeRempl,
            },
            success: function (data) {
                console.log("BBBB111");
                result=data.split('<>');
                    $('#supprimerForme').modal('hide');
                    var table = $('#tableForme').DataTable();
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    ligne[0] = forme; ligne[1] = 'En cours';
                    $('#tableForme').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'red'});               
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/////////FIN SUP FORME ///////////////
