$(document).ready(function() {

    nbEntreParam = $('#nbEntreParam').val();

    $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"operation":1,"nbEntreParam":nbEntreParam,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsParamPaiem").on( "click", ".pagination a", function (e){
    // $("#resultsParamPaiem").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)

        nbEntreParam = $('#nbEntreParam').val()
        query = $('#searchInputParamPaiem').val();

        if (query.length == 0) {
            $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"page":page,"operation":1,"nbEntreParam":nbEntreParam,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"page":page,"operation":1,"nbEntreParam":nbEntreParam,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
        // $("#resultsParamPaiem").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
    });
});
/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {

    $('#searchInputParamPaiem').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputParamPaiem').val()
        nbEntreParam = $('#nbEntreParam').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            // alert(1111)
            t = 1; // code barre
            
            if (query.length > 0) {
                
                $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"operation":3,"nbEntreParam":nbEntreParam,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"operation":3,"nbEntreParam":nbEntreParam,"query":"","cb":t}); //load initial records
            }
        }else{
            // alert(2222)
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    
                    $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"operation":3,"nbEntreParam":nbEntreParam,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"operation":3,"nbEntreParam":nbEntreParam,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreParam').on("change", function(e) {
        e.preventDefault();

        nbEntreParam = $('#nbEntreParam').val()
        query = $('#searchInputParamPaiem').val();

        if (query.length == 0) {
            $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"operation":4,"nbEntreParam":nbEntreParam,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsParamPaiem" ).load( "paiement/ajax/paramettreAjax.php",{"operation":4,"nbEntreParam":nbEntreParam,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsParamPaiem" ).load( "ajax/listerProduitAjax.php",{"operation":4,"nbEntreeBC":nbEntreeBC}); //load initial records
    });

}); 

//////////////////////////////////////
function activerParamMont(params) {
    /* $.post("paiement/ajax/operationParamettreAjax.php",{
        operation: "chercherParam",
        i:params
    }, function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        $('#Activer').modal('show'); 
    }); */
    $('#i').val(params); 
    $('#Activer').modal('show'); 
}
$("#btnActiverMontF").click(function() {
    var idvariable = $('#i').val();
    var btnActiver = $('#btnActiverMontF').val();
    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                       idvariable: idvariable,
                       btnActiver: btnActiver,
                   },
                   success: function(data) {
                    //alert(data);
                    $('#formActMontFix').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               });
});

function desactiverParamMont(params) {  
    $('#ib').val(params); 
    $('#Desactiver').modal('show'); 
}
$("#btnDesactiverMontF").click(function() {
    var idvariable = $('#ib').val();
    var btnDesactiver = $('#btnDesactiverMontF').val();
    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                       idvariable: idvariable,
                       btnDesactiver: btnDesactiver,
                   },
                   success: function(data) {
                    //alert(data);
                    $('#formDesactMontFix').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               });
});
// ACTIVER DESACTIVER POURCENTAGE
function activerParamPourc(params) {
    
    $('#i2a').val(params); 
    $('#Activer2').modal('show'); 
}
$("#btnActiver2").click(function() {
    var idvariable = $('#i2a').val();
    var btnActiver2 = $('#btnActiver2').val();
    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                       idvariable: idvariable,
                       btnActiver2: btnActiver2,
                   },
                   success: function(data) {
                    // alert(data);
                    $('#formActMontPourc').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               });
});
function desactiverParamPourc(params) {  
    $('#i2b').val(params); 
    $('#Desactiver2').modal('show'); 
}
$("#btnDesactiver2").click(function() {
    var idvariable = $('#i2b').val();
    var btnDesactiver2 = $('#btnDesactiver2').val();
    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                       idvariable: idvariable,
                       btnDesactiver2: btnDesactiver2,
                   },
                   success: function(data) {
                    // alert(data);
                    $('#formDesactMontPourc').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               });
});
//ACTIVER DESACTIVER LIGNE
function activerParamLigne(params) {
    
    $('#i3a').val(params); 
    $('#Activer3').modal('show'); 
}
$("#btnActiver3").click(function() {
    var idvariable = $('#i3a').val();
    var btnActiver3 = $('#btnActiver3').val();
    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                       idvariable: idvariable,
                       btnActiver3: btnActiver3,
                   },
                   success: function(data) {
                    // alert(data);
                    $('#formActMontLigne').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               });
});
function desactiverParamLigne(params) {  
    $('#i3b').val(params); 
    $('#Desactiver3').modal('show'); 
}
$("#btnDesactiver3").click(function() {
    var idvariable = $('#i3b').val();
    var btnDesactiver = $('#btnDesactiver3').val();
    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                       idvariable: idvariable,
                       btnDesactiver: btnDesactiver,
                   },
                   success: function(data) {
                    //alert(data);
                    $('#formDesactMontLigne').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               });
});
$("#btnEnregistrerVarParam").click(function() {
    //KKKKK $( "#myselect option:selected" ).text();

    var nomV = $('#nomV').val();
    //var type = $( "#typeMod option:selected" ).text();
    // var type = $( "#typeMod option:selected" ).text();
    var type = $('#type').val();
    var categorie = $('#categorie').val();
    var moyenneVolumeMin = $('#moyenneVolumeMin').val();
    var moyenneVolumeMax = $('#moyenneVolumeMax').val();
    var montantFixe = $('#montantFixe').val();
    var pourcentage = $('#pourcentage').val();
    var prixInsertion = $('#prixInsertion').val();
    var montantMin = $('#montantMin').val();
    var montantMax = $('#montantMax').val();
    alert(type);
    alert('cat'+categorie);
    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                       nomV: nomV,
                       type: type,
                       categorie: categorie,
                       moyenneVolumeMin: moyenneVolumeMin,
                       moyenneVolumeMax: moyenneVolumeMax,
                       montantFixe: montantFixe,
                       pourcentage: pourcentage,
                       prixInsertion: prixInsertion,
                       montantMin: montantMin,
                       montantMax: montantMax,
                       btnEnregistrerVarParam: "1",
                   },
                   success: function(data) {
                    //alert(data);
                    $('#addParamettreForm').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               }); 
});

function modVariable(params) {  
     
     $.post("paiement/ajax/operationParamettreAjax.php",{
        operation: "chercherParam",
        i:params,
    }, function(data, status){
        tab=data.split('<>');
        $('#idvariableMod').val(params);
        $('#nomVInitialMod').val(tab[1]);
        $('#nomVMod').val(tab[1]);
        $('#typeInitialMod').val(tab[2]);
        $('#typeMod').val(tab[2]);
        $('#categorieInitialMod').val(tab[3]);
        $('#categorieMod').val(tab[3]);
        $('#moyenneVolumeMinInitialMod').val(tab[4]);
        $('#moyenneVolumeMinMod').val(tab[4]);
        $('#moyenneVolumeMaxInitialMod').val(tab[5]);
        $('#moyenneVolumeMaxMod').val(tab[5]);
        $('#montantFixeInitialMod').val(tab[6]);
        $('#montantFixeMod').val(tab[6]);
        $('#prixInsertionInitialMod').val(tab[7]);
        $('#prixInsertionMod').val(tab[7]);
        $('#pourcentageInitialMod').val(tab[8]);
        $('#pourcentageMod').val(tab[8]);
        $('#montantMinInitialMod').val(tab[9]);
        $('#montantMinMod').val(tab[9]);
        $('#montantMaxInitialMod').val(tab[10]);
        $('#montantMaxMod').val(tab[10]);  
        $('#imgmodifierVar').modal('show'); 
    }); 

    
}
$("#btnModifierVarParam").click(function() {
    //KKKKK $( "#myselect option:selected" ).text();

    var idvariable = $('#idvariableMod').val();
    var nomVMod = $('#nomVMod').val();
    var typeMod = $('#typeMod').val();
    var categorieMod = $('#categorieMod').val();
    var moyenneVolumeMinMod = $('#moyenneVolumeMinMod').val();
    var moyenneVolumeMaxMod = $('#moyenneVolumeMaxMod').val();
    var montantFixeMod = $('#montantFixeMod').val();
    var pourcentageMod = $('#prixInsertionMod').val();
    var prixInsertionMod = $('#pourcentageMod').val();
    var montantMinMod = $('#montantMinMod').val();
    var montantMaxMod = $('#montantMaxMod').val();

    var nomVInitialMod = $('#nomVInitialMod').val();
    var typeInitialMod = $('#typeInitialMod').val();
    var categorieInitialMod = $('#categorieInitialMod').val();
    var moyenneVolumeMinInitialMod = $('#moyenneVolumeMinInitialMod').val();
    var moyenneVolumeMaxInitialMod = $('#moyenneVolumeMaxInitialMod').val();
    var montantFixeInitialMod = $('#montantFixeInitialMod').val();
    var pourcentageInitialMod = $('#prixInsertionInitialMod').val();
    var prixInsertionInitialMod = $('#pourcentageInitialMod').val();
    var montantMinInitialMod = $('#montantMinInitialMod').val();
    var montantMaxInitialMod = $('#montantMaxInitialMod').val();

    // alert(type);
    // alert('cat'+categorie);
    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                        idvariable:idvariable,
                       nomV: nomVMod,
                       type: typeMod,
                       categorie: categorieMod,
                       moyenneVolumeMin: moyenneVolumeMinMod,
                       moyenneVolumeMax: moyenneVolumeMaxMod,
                       montantFixe: montantFixeMod,
                       pourcentage: pourcentageMod,
                       prixInsertion: prixInsertionMod,
                       montantMin: montantMinMod,
                       montantMax: montantMaxMod,

                       nomVInitial: nomVInitialMod,
                       typeInitial: typeInitialMod,
                       categorieInitial: categorieInitialMod,
                       moyenneVolumeMinInitial: moyenneVolumeMinInitialMod,
                       moyenneVolumeMaxInitial: moyenneVolumeMaxInitialMod,
                       montantFixeInitial: montantFixeInitialMod,
                       pourcentageInitial: pourcentageInitialMod,
                       prixInsertionInitial: prixInsertionInitialMod,
                       montantMinInitial: montantMinInitialMod,
                       montantMaxInitial: montantMaxInitialMod,
                       btnModifierVarParam: "1",
                   },
                   success: function(data) {
                    // alert(data);
                    $('#modParamettreForm').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               }); 
});
function supVariable(params) {
    $.post("paiement/ajax/operationParamettreAjax.php",{
        operation: "chercherParam",
        i:params,
    }, function(data, status){
        tab=data.split('<>');
        $('#idvariableSup').val(params);
        $('#nomVInitialSup').val(tab[1]);
        $('#typeInitialSup').val(tab[2]);
        $('#categorieInitialSup').val(tab[3]);
        $('#moyenneVolumeMinInitialSup').val(tab[4]);
        $('#moyenneVolumeMaxInitialSup').val(tab[5]);
        $('#montantFixeInitialSup').val(tab[6]);
        $('#prixInsertionInitialSup').val(tab[7]);
        $('#pourcentageInitialSup').val(tab[8]);
        $('#montantMinInitialSup').val(tab[9]);
        $('#montantMaxInitialSup').val(tab[10]);  
        $('#imgsuprimerPer').modal('show'); 
    });  
}
$("#btnSupprimerVariable").click(function() {
    var i = $('#idvariableSup').val();

    $.ajax({
                   url: "paiement/ajax/operationParamettreAjax.php",
                   method: "POST",
                   data: {
                    idvariable: i,
                       btnSupprimerVariable: "1",
                   },
                   success: function(data) {
                    //alert(data);
                    $('#addParamettreForm').submit();
                   },
                   error: function() {
                       alert("La requête ");
                   },
                   dataType: "text"
               });
});

//KKK $( "#myselect option:selected" ).text();