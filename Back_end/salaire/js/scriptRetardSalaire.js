/*************************************************************************************** */ 
//**************************** */ LISTE SALAIRE PERSONNEL /***************************** */
/*************************************************************************************** */ 



$(document).ready(function() {

    nbEntreeRetardPers = $('#nbEntreeRetardPers').val();

    $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"operation":1,"nbEntreeRetardPers":nbEntreeRetardPers,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsRetardPers").on( "click", ".pagination a", function (e){
    // $("#listesalUnAcc").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)

        nbEntreeRetardPers = $('#nbEntreeRetardPers').val()
        query = $('#searchInputRetardPers').val();

        if (query.length == 0) {
            $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"page":page,"operation":1,"nbEntreeRetardPers":nbEntreeRetardPers,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"page":page,"operation":1,"nbEntreeRetardPers":nbEntreeRetardPers,"query":query,"cb":"","matricule":matricule}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
    });
});
/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {

    $('#searchInputRetardPers').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputRetardPers').val()
        nbEntreeRetardPers = $('#nbEntreeRetardPers').val()
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            // alert(1111)
            t = 1; // code barre
            
            if (query.length > 0) {
                
                $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"operation":3,"nbEntreeRetardPers":nbEntreeRetardPers,"query":query,"cb":t,"matricule":matricule}); //load initial records
            }else{
                $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"operation":3,"nbEntreeRetardPers":nbEntreeRetardPers,"query":"","cb":t,"matricule":matricule}); //load initial records
            }
        }else{
            // alert(2222)
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    
                    $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"operation":3,"nbEntreeRetardPers":nbEntreeRetardPers,"query":query,"cb":t,"matricule":matricule}); //load initial records
                }else{
                    $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"operation":3,"nbEntreeRetardPers":nbEntreeRetardPers,"query":"","cb":t,"matricule":matricule}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreeRetardPers').on("change", function(e) {
        e.preventDefault();

        nbEntreeRetardPers = $('#nbEntreeRetardPers').val()
        query = $('#searchInputRetardPers').val()

        if (query.length == 0) {
            $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"operation":4,"nbEntreeRetardPers":nbEntreeRetardPers,"query":"","cb":"","matricule":matricule}); //load initial records
                
        }else{
            $("#resultsRetardPers" ).load( "salaire/ajax/salaireAllPersRetardAjax.php",{"operation":4,"nbEntreeRetardPers":nbEntreeRetardPers,"query":query,"cb":"","matricule":matricule}); //load initial records
        }
    });

});