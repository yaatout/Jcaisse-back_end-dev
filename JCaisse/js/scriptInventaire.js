$(document).ready(function() {
    $(".loading-divI").show(); //show loading element
    nbEntreeInv = $('#nbEntreeInvDesignation').val()

    $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"operation":1,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}, function(){ //get content from PHP page
        $(".loading-divI").hide(); //once done, hide loading element
    });

    //executes code below when user click on pagination links
    $("#resultsInvDesignation").on( "click", ".pagination a", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link

        nbEntreeInv = $('#nbEntreeInvDesignation').val()
        query = $('#searchInputInvDesignation').val();

        if (query.length == 0) {
            $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"page":page,"operation":1,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }else{
            $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"page":page,"operation":1,"nbEntreeInv":nbEntreeInv,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });

        }
    });

    $('#searchInputInvDesignation').on("keyup", function(e) {
        e.preventDefault();

        query = $('#searchInputInvDesignation').val()
        nbEntreeInv = $('#nbEntreeInvDesignation').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);

        if (keycode == '13') {
            t = 1; // code barre
            if (query.length > 0) {
                $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":"","cb":t}); //load initial records
            }
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"operation":3,"nbEntreeInvDesignation":nbEntreeInv,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"operation":3,"nbEntreeInvDesignation":nbEntreeInv,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });

    $('#nbEntreeInvDesignation').on("change", function(e) {

        e.preventDefault();

        nbEntreeInv = $('#nbEntreeInvDesignation').val()
        query = $('#searchInputInvDesignation').val();

        if (query.length == 0) {
            $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}); //load initial records
        }else{
            $("#resultsInvDesignation" ).load( "ajax/listerInventaireAnnuel-DesignationAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv,"query":query,"cb":""}); //load initial records
        }
    });
});

$(document).ready(function() {
    $(".loading-divI").show(); //show loading element
    nbEntreeInv = $('#nbEntreeInvCategorie').val()

    $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"operation":1,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}, function(){ //get content from PHP page
        $(".loading-divI").hide(); //once done, hide loading element
    });

    //executes code below when user click on pagination links
    $("#resultsInvCategorie").on( "click", ".pagination a", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link

        nbEntreeInv = $('#nbEntreeInvCategorie').val()
        query = $('#searchInputInvCategorie').val();

        if (query.length == 0) {
            $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"page":page,"operation":1,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }else{
            $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"page":page,"operation":1,"nbEntreeInv":nbEntreeInv,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });

        }
    });

    $('#searchInputInvCategorie').on("keyup", function(e) {
        e.preventDefault();

        query = $('#searchInputInvCategorie').val()
        nbEntreeInv = $('#nbEntreeInvCategorie').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);

        if (keycode == '13') {
            t = 1; // code barre
            if (query.length > 0) {
                $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":"","cb":t}); //load initial records
            }
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });

    $('#nbEntreeInvCategorie').on("change", function(e) {

        e.preventDefault();

        nbEntreeInv = $('#nbEntreeInvCategorie').val()
        query = $('#searchInputInvCategorie').val();

        if (query.length == 0) {
            $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}); //load initial records
        }else{
            $("#resultsInvCategorie" ).load( "ajax/listerInventaireAnnuel-CategorieAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv,"query":query,"cb":""}); //load initial records
        }
    });
});

$(document).ready(function() {
    $(".loading-divI").show(); //show loading element
    nbEntreeInv = $('#nbEntreeInvForme').val()

    $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"operation":1,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}, function(){ //get content from PHP page
        $(".loading-divI").hide(); //once done, hide loading element
    });

    //executes code below when user click on pagination links
    $("#resultsInvForme").on( "click", ".pagination a", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link

        nbEntreeInv = $('#nbEntreeInvForme').val()
        query = $('#searchInputInvForme').val();

        if (query.length == 0) {
            $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"page":page,"operation":1,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }else{
            $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"page":page,"operation":1,"nbEntreeInv":nbEntreeInv,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });

        }
    });

    $('#searchInputInvForme').on("keyup", function(e) {
        e.preventDefault();

        query = $('#searchInputInvForme').val()
        nbEntreeInv = $('#nbEntreeInvForme').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);

        if (keycode == '13') {
            t = 1; // code barre
            if (query.length > 0) {
                $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":"","cb":t}); //load initial records
            }
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });

    $('#nbEntreeInvForme').on("change", function(e) {

        e.preventDefault();

        nbEntreeInv = $('#nbEntreeInvForme').val()
        query = $('#searchInputInvForme').val();

        if (query.length == 0) {
            $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}); //load initial records
        }else{
            $("#resultsInvForme" ).load( "ajax/listerInventaireAnnuel-FormeAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv,"query":query,"cb":""}); //load initial records
        }
    });
});

function inventaireAnnuelDesignation(designation,type) {

    $('#btn_InvStock-' + designation).prop('disabled', true);
    $('#quantiteD-' + designation).prop('disabled', true);
    $('#codeBarre-' + designation).prop('disabled', true);
    $('#prixSessionD-' + designation).prop('disabled', true);
    $('#prixPublicD-' + designation).prop('disabled', true);
    $('#prixachatD-' + designation).prop('disabled', true);
    $('#prixD-' + designation).prop('disabled', true);
    $('#prixuniteStockD-' + designation).prop('disabled', true);

    var quantite = $('#quantiteD-' + designation).val();
    var codeBarre = $('#codeBarre-' + designation).val();

    if (quantite != '' && quantite != null) {
        if(type==1){
            var prixSession = $('#prixSessionD-' + designation).val();
            var prixPublic = $('#prixPublicD-' + designation).val();
            $.ajax({
                url: "ajax/operationAjax_Inventaire.php",
                method: "POST",
                data: {
                    operation: 1,
                    idDesignation: designation,
                    codeBarre: codeBarre,
                    quantite: quantite,
                    prixSession: prixSession,
                    prixPublic: prixPublic,
                },
                success: function(data) {
                    // alert(data)
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
        }
        else if(type==2){
            var prixachat = $('#prixachatD-' + designation).val();
            var prix = $('#prixD-' + designation).val();
            var prixuniteStock = $('#prixuniteStockD-' + designation).val();
            $.ajax({
                url: "ajax/operationAjax_Inventaire.php",
                method: "POST",
                data: {
                    operation: 2,
                    idDesignation: designation,
                    codeBarre: codeBarre,
                    quantite: quantite,
                    prixachat: prixachat,
                    prix: prix,
                    prixuniteStock: prixuniteStock,
                },
                success: function(data) {
                    // alert(data)
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
        }

    } else {

        alert('Le Champ est vide');

    }

}

function inventaireAnnuelCategorie(designation,type) {

    $('#btn_InvStock-' + designation).prop('disabled', true);
    $('#quantiteC-' + designation).prop('disabled', true);
    $('#codeBarreC-' + designation).prop('disabled', true);
    $('#categorie-' + designation).prop('disabled', true);
    $('#prixSessionC-' + designation).prop('disabled', true);
    $('#prixPublicC-' + designation).prop('disabled', true);
    $('#prixachatC-' + designation).prop('disabled', true);
    $('#prixC-' + designation).prop('disabled', true);
    $('#prixuniteStockC-' + designation).prop('disabled', true);

    var quantite = $('#quantiteC-' + designation).val();
    var categorie = $('#categorie-' + designation).val();

    if (quantite != '' && quantite != null) {
        if(type==1){
            var prixSession = $('#prixSessionC-' + designation).val();
            var prixPublic = $('#prixPublicC-' + designation).val();
            $.ajax({
                url: "ajax/operationAjax_Inventaire.php",
                method: "POST",
                data: {
                    operation: 1,
                    idDesignation: designation,
                    categorie: categorie,
                    quantite: quantite,
                    prixSession: prixSession,
                    prixPublic: prixPublic,
                },
                success: function(data) {
                    // alert(data)
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
        }
        else if(type==2){
            var prixachat = $('#prixachatC-' + designation).val();
            var prix = $('#prixC-' + designation).val();
            var prixuniteStock = $('#prixuniteStockC-' + designation).val();
            $.ajax({
                url: "ajax/operationAjax_Inventaire.php",
                method: "POST",
                data: {
                    operation: 2,
                    idDesignation: designation,
                    categorie: categorie,
                    quantite: quantite,
                    prixachat: prixachat,
                    prix: prix,
                    prixuniteStock: prixuniteStock,
                },
                success: function(data) {
                    // alert(data)
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
        }

    } else {

        alert('Le Champ est vide');

    }

}

function inventaireAnnuelForme(designation,type) {

    $('#btn_InvStock-' + designation).prop('disabled', true);
    $('#quantiteF-' + designation).prop('disabled', true);
    $('#forme-' + designation).prop('disabled', true);
    $('#prixSessionF-' + designation).prop('disabled', true);
    $('#prixPublicF-' + designation).prop('disabled', true);
    $('#prixachatF-' + designation).prop('disabled', true);
    $('#prixF-' + designation).prop('disabled', true);
    $('#prixuniteStockF-' + designation).prop('disabled', true);

    var quantite = $('#quantiteF-' + designation).val();

    if (quantite != '' && quantite != null) {
        if(type==1){
            var prixSession = $('#prixSessionF-' + designation).val();
            var prixPublic = $('#prixPublicF-' + designation).val();
            var forme = $('#forme-' + designation).val();
            $.ajax({
                url: "ajax/operationAjax_Inventaire.php",
                method: "POST",
                data: {
                    operation: 1,
                    idDesignation: designation,
                    forme: forme,
                    quantite: quantite,
                    prixSession: prixSession,
                    prixPublic: prixPublic,
                },
                success: function(data) {
                    // alert(data)
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
        }


    } else {

        alert('Le Champ est vide');

    }

}

function archiverAnnuelProduit(designation) {

    $('#btn_InvArchiver-' + designation).prop('disabled', true);
    $('#quantite-' + designation).prop('disabled', true);
    $('#codeBarre-' + designation).prop('disabled', true);
    $('#categorie-' + designation).prop('disabled', true);
    $('#forme-' + designation).prop('disabled', true);
    $('#prixSession-' + designation).prop('disabled', true);
    $('#prixPublic-' + designation).prop('disabled', true);
    $('#prixachat-' + designation).prop('disabled', true);
    $('#prix-' + designation).prop('disabled', true);
    $('#prixuniteStock-' + designation).prop('disabled', true);

    $.ajax({
        url: "ajax/operationAjax_Inventaire.php",
        method: "POST",
        data: {
            operation: 3,
            idDesignation: designation,
        },
        success: function(data) {

        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}

$(document).ready(function() {

    nbEntreeInvD = $('#nbEntreeInvTotal').val()


    $("#resultsInvTotal" ).load( "ajax/listerInventaireAnnuelAjax.php",{"operation":1,"nbEntreeInvD":nbEntreeInvD,"query":"","cb":""}, function(data){ //get content from PHP page
        $(".loading-div").hide(); //once done, hide loading element
    });

    //executes code below when user click on pagination links
    $("#resultsInvTotal").on( "click", ".pagination a", function (e){

        e.preventDefault();

        $(".loading-div").show(); //show loading element

        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        nbEntreeInvD = $('#nbEntreeInvTotal').val()
        query = $('#searchInputInvTotal').val();

        if (query.length == 0) {
            $("#resultsInvTotal" ).load( "ajax/listerInventaireAnnuelAjax.php",{"page":page,"operation":1,"nbEntreeInvD":nbEntreeInvD,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }else{
            $("#resultsInvTotal" ).load( "ajax/listerInventaireAnnuelAjax.php",{"page":page,"operation":1,"nbEntreeInvD":nbEntreeInvD,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
    });

    $('#searchInputInvTotal').on("keyup", function(e) {

        e.preventDefault();

        query = $('#searchInputInvTotal').val()
        nbEntreeInv = $('#nbEntreeInvTotal').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);

        if (keycode == '13') {
            t = 1; // code barre
            if (query.length > 0) {
                $("#resultsInvTotal" ).load( "ajax/listerInventaireAnnuelAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsInvTotal" ).load( "ajax/listerInventaireAnnuelAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":"","cb":t}); //load initial records
            }
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    $("#resultsInvTotal" ).load( "ajax/listerInventaireAnnuelAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsInvTotal" ).load( "ajax/listerInventaireAnnuelAjax.php",{"operation":3,"nbEntreeInv":nbEntreeInv,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }

    });
   

    $('#nbEntreeInvTotal').on("change", function(e) {

        e.preventDefault();

        nbEntreeInv = $('#nbEntreeInvTotal').val()
        query = $('#searchInputInvTotal').val();

        if (query.length == 0) {
            $("#resultsInvTotal" ).load( "ajax/listerStock-InventaireAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv,"query":"","cb":""}); //load initial records
        }else{
            $("#resultsInvTotal" ).load( "ajax/listerStock-InventaireAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv,"query":query,"cb":""}); //load initial records
        }
    });

});

