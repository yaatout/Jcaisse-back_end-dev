///*************** Début paiement mois prec***************/
$(document).ready(function() {

    nbEntreRetard = $('#nbEntreRetard').val();

    $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"operation":1,"nbEntreRetard":nbEntreRetard,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsRetard").on( "click", ".pagination a", function (e){
    // $("#resultsRetard").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)

        nbEntreRetard = $('#nbEntreRetard').val()
        query = $('#searchInputRetard').val();

        if (query.length == 0) {
            $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"page":page,"operation":1,"nbEntreRetard":nbEntreRetard,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"page":page,"operation":1,"nbEntreRetard":nbEntreRetard,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
        // $("#resultsRetard").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
    });
});
/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {

    $('#searchInputRetard').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputRetard').val()
        nbEntreRetard = $('#nbEntreRetard').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            // alert(1111)
            t = 1; // code barre
            
            if (query.length > 0) {
                
                $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"operation":3,"nbEntreRetard":nbEntreRetard,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"operation":3,"nbEntreRetard":nbEntreRetard,"query":"","cb":t}); //load initial records
            }
        }else{
            // alert(2222)
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    
                    $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"operation":3,"nbEntreRetard":nbEntreRetard,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"operation":3,"nbEntreRetard":nbEntreRetard,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreRetard').on("change", function(e) {
        e.preventDefault();

        nbEntreRetard = $('#nbEntreRetard').val()
        query = $('#searchInputRetard').val();

        if (query.length == 0) {
            $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"operation":4,"nbEntreRetard":nbEntreRetard,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsRetard" ).load( "paiement/ajax/listerMoisRetardAjax.php",{"operation":4,"nbEntreRetard":nbEntreRetard,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsRetard" ).load( "ajax/listerProduitAjax.php",{"operation":4,"nbEntreeBC":nbEntreeBC}); //load initial records
    });

});   
