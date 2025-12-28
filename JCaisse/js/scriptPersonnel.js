/*************** Début modification Catalogue  details Pharmacie***************/
$(document).ready(function() {

    nbEntreeUserBoutique = $('#nbEntreeUserBoutique').val();

    $("#resultsUserBoutique" ).load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":1,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":"","cb":""}); //load initial records

    //executes code below when user click on pagination links
    $("#resultsUserBoutique").on( "click", ".pagination a", function (e){
    // $("#resultsProductsPDesign").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)n
        nbEntreeUserBoutique = $('#nbEntreeUserBoutique').val()
        query = $('#searchInputUserBoutique').val();

        if (query.length == 0) {

            $("#resultsUserBoutique").load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":1,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":"","cb":""}); //load initial records
                
        }else{

            $("#resultsUserBoutique").load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":1,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":query,"cb":""}); //load initial records
        
        }
        // $("#resultsProductsPDesign").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
    });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {
    // alert(4)
    $('#searchInputUserBoutique').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputUserBoutique').val();
        nbEntreeUserBoutique = $('#nbEntreeUserBoutique').val();
        //id = $('#idCDPH').val();

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            t = 1; // code barre
            // inputVal = $('#searchInputPhD').val();
            // if ($.isNumeric(inputVal)) {
            if (query.length > 0) { 
                $("#resultsUserBoutique" ).load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":3,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsUserBoutique" ).load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":3,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":"","cb":t}); //load initial records
            }     
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                
                if (query.length > 0) {         
                    $("#resultsUserBoutique" ).load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":3,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsUserBoutique" ).load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":3,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreeUserBoutique').on("change", function(e) {
        e.preventDefault();

        nbEntreeUserBoutique = $('#nbEntreeUserBoutique').val()
        query = $('#searchInputUserVitrine').val();

        if (query.length == 0) {
            $("#resultsUserBoutique" ).load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":4,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":"","cb":""}); //load initial records   
        }else{
            $("#resultsUserBoutique" ).load( "ajax/listerPersonnelBoutiqueAjax.php",{"operation":4,"nbEntreeUserBoutique":nbEntreeUserBoutique,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsProductsPDesign" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
    });

});

/*************** Fin modification Catalogue details Pharmacie***************/

/*************** Début modification Catalogue  details Pharmacie***************/
$(document).ready(function() {

    nbEntreeUserVitrine = $('#nbEntreeUserVitrine').val();

    $("#resultsUserVitrine" ).load( "ajax/listerPersonnelVitrineAjax.php",{"operation":1,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":"","cb":""}); //load initial records

    //executes code below when user click on pagination links
    $("#resultsUserVitrine").on( "click", ".pagination a", function (e){
    // $("#resultsProductsPDesign").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)n
        nbEntreeUserVitrine = $('#nbEntreeUserVitrine').val()
        query = $('#searchInputUserVitrine').val();

        if (query.length == 0) {

            $("#resultsUserVitrine").load( "ajax/listerPersonnelVitrineAjax.php",{"operation":1,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":"","cb":""}); //load initial records
                
        }else{

            $("#resultsUserVitrine").load( "ajax/listerPersonnelVitrineAjax.php",{"operation":1,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":query,"cb":""}); //load initial records
        
        }
        // $("#resultsProductsPDesign").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
    });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {
    // alert(4)
    $('#searchInputUserVitrine').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputUserVitrine').val();
        nbEntreeUserVitrine = $('#nbEntreeUserVitrine').val();
        //id = $('#idCDPH').val();

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            t = 1; // code barre
            // inputVal = $('#searchInputPhD').val();
            // if ($.isNumeric(inputVal)) {
            if (query.length > 0) { 
                $("#resultsUserVitrine" ).load( "ajax/listerPersonnelVitrineAjax.php",{"operation":3,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsUserVitrine" ).load( "ajax/listerPersonnelVitrineAjax.php",{"operation":3,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":"","cb":t}); //load initial records
            }     
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                
                if (query.length > 0) {         
                    $("#resultsUserVitrine" ).load( "ajax/listerPersonnelVitrineAjax.php",{"operation":3,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsUserVitrine" ).load( "ajax/listerPersonnelVitrineAjax.php",{"operation":3,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreeUserVitrine').on("change", function(e) {
        e.preventDefault();

        nbEntreeUserVitrine = $('#nbEntreeUserVitrine').val()
        query = $('#searchInputUserVitrine').val();

        if (query.length == 0) {
            $("#resultsUserVitrine" ).load( "ajax/listerPersonnelVitrineAjax.php",{"operation":4,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":"","cb":""}); //load initial records   
        }else{
            $("#resultsUserVitrine" ).load( "ajax/listerPersonnelVitrineAjax.php",{"operation":4,"nbEntreeUserVitrine":nbEntreeUserVitrine,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsProductsPDesign" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
    });

});

/*************** Fin modification Catalogue details Pharmacie***************/

/**Debut Activer Personnel **/
function activer_UserBoutique(idutilisateur) {
    $('#idUser_Activer').val('');
    $('#btn_Boutique-'+idutilisateur).prop('disabled', true);

    $.ajax({

        url: "ajax/operationAjax_Personnel.php",

        method: "POST",

        data: {

            operation: 1,

            idutilisateur: idutilisateur

        },

        success: function(data) {
            tab = data.split('<>');

            if (tab[0] == 1) {
                $('#idUser_Activer').val(tab[1]);
                $('#activerUser').modal('show');
                $('#btn_Boutique-'+idutilisateur).prop('disabled', true);

            }

        },

        error: function() {

            alert("La requête ");

        },

        dataType: "text"

    });

}
/**Fin Activer Personnel**/

/**Debut Desactiver Personnel **/
function desactiver_UserBoutique(idutilisateur) {
    $('#idUser_Desactiver').val('');
    $('#btn_Boutique-'+idutilisateur).prop('disabled', true);

    $.ajax({

        url: "ajax/operationAjax_Personnel.php",

        method: "POST",

        data: {

            operation: 1,

            idutilisateur: idutilisateur,

            activer : 0,

        },

        success: function(data) {
            tab = data.split('<>');

            if (tab[0] == 1) {
                $('#idUser_Desactiver').val(tab[1]);
                $('#desactiverUser').modal('show');
                $('#btn_Boutique-'+idutilisateur).prop('disabled', true);

            }

        },

        error: function() {

            alert("La requête ");

        },

        dataType: "text"

    });

}
/**Fin Desactiver Personnel**/

/**Debut Modifier Personnel**/ 
function mdf_UserBoutique(idutilisateur) {

    $('#idUser_Mdf').val('');
    $('#nomUser_Mdf').val('');
    $('#prenomUser_Mdf').val('');
    $('#telephoneUser_Mdf').val('');
    $('#emailUser_Mdf').val('');
    $('#checkProprietaire_Mdf').attr('checked',false); 
    $('#checkGerant_Mdf').attr('checked',false); 
    $('#checkGestionnaire_Mdf').attr('checked',false); 
    $('#checkCaissier_Mdf').attr('checked',false); 
    $('#checkVendeur_Mdf').attr('checked',false); 
    $('#checkVitrine_Mdf').attr('checked',false); 

    $.ajax({
        url: "ajax/operationAjax_Personnel.php",
        method: "POST",
        data: {
            operation: 1,
            idutilisateur: idutilisateur,
        },
        success: function(data) {
            tab = data.split('<>');
            if (tab[0] == 1) {
                $('#idUser_Mdf').val(tab[1]);
                $('#nomUser_Mdf').val(tab[2]);
                $('#prenomUser_Mdf').val(tab[3]);
                $('#telephoneUser_Mdf').val(tab[4]);
                $('#emailUser_Mdf').val(tab[5]);
                if (tab[6]==1) { $('#checkProprietaire_Mdf').attr('checked',true); }
                if (tab[7]==1) { $('#checkGerant_Mdf').attr('checked',true); }
                if (tab[8]==1) { $('#checkGestionnaire_Mdf').attr('checked',true); }
                if (tab[9]==1) { $('#checkCaissier_Mdf').attr('checked',true); }
                if (tab[10]==1) { $('#checkVendeur_Mdf').attr('checked',true); }
                if (tab[11]==1) { $('#checkVitrine_Mdf').attr('checked',true); }
                $('#entrepotUser_Mdf').val(tab[16]);
                $('#entrepotUser_Mdf').text(tab[17]);
                $('#modifierUser').modal('show');
            }
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}
/**Fin Modifier Personnel**/

/**Debut Modifier Personnel Vitrine**/ 
function mdf_UserVitrine(idutilisateur) {

    $('#idUserVitrine_Mdf').val('');
    $('#nomUserVitrine_Mdf').val('');
    $('#prenomUserVitrine_Mdf').val('');
    $('#telephoneUserVitrine_Mdf').val('');
    $('#emailUserVitrine_Mdf').val('');
    $('#checkTableauBord_Mdf').attr('checked',false); 
    $('#checkEcommerce_Mdf').attr('checked',false); 
    $('#checkCommande_Mdf').attr('checked',false); 
    $('#checkClient_Mdf').attr('checked',false);

    $.ajax({
        url: "ajax/operationAjax_Personnel.php",
        method: "POST",
        data: {
            operation: 1,
            idutilisateur: idutilisateur,
        },
        success: function(data) {
            tab = data.split('<>');
            if (tab[0] == 1) {
                $('#idUserVitrine_Mdf').val(tab[1]);
                $('#nomUserVitrine_Mdf').val(tab[2]);
                $('#prenomUserVitrine_Mdf').val(tab[3]);
                $('#telephoneUserVitrine_Mdf').val(tab[4]);
                $('#emailUserVitrine_Mdf').val(tab[5]);
                if (tab[12]==1) { $('#checkTableauBord_Mdf').attr('checked',true); }
                if (tab[13]==1) { $('#checkEcommerce_Mdf').attr('checked',true); }
                if (tab[14]==1) { $('#checkCommande_Mdf').attr('checked',true); }
                if (tab[15]==1) { $('#checkClient_Mdf').attr('checked',true); }
                $('#modifierUserVitrine').modal('show');
            }
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}
/**Fin Modifier Personnel Vitrine**/

/**Debut Supprimer Personnel**/
function spm_UserBoutique(idutilisateur) {

    $('#idUser_Spm').val('');
    $('#nomUser_Spm').val('');
    $('#prenomUser_Spm').val('');
    $('#telephoneUser_Spm').val('');
    $('#emailUser_Spm').val('');

    $.ajax({
        url: "ajax/operationAjax_Personnel.php",
        method: "POST",
        data: {
            operation: 1,
            idutilisateur: idutilisateur,
        },
        success: function(data) {
            tab = data.split('<>');
            if (tab[0] == 1) {
                $('#idUser_Spm').val(tab[1]);
                $('#nomUser_Spm').val(tab[2]);
                $('#prenomUser_Spm').val(tab[3]);
                $('#telephoneUser_Spm').val(tab[4]);
                $('#emailUser_Spm').val(tab[5]);
                $('#supprimerUser').modal('show');
            }
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}
/**Fin Supprimer Personnel**/







