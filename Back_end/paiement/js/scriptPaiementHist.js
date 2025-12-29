///*************** Début paiement mois prec***************/
$(document).ready(function() {

    nbEntreHist = $('#nbEntreHist').val();

    $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"operation":1,"nbEntreHist":nbEntreHist,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsHist").on( "click", ".pagination a", function (e){
    // $("#resultsHist").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)

        nbEntreHist = $('#nbEntreHist').val()
        query = $('#searchInputHist').val();

        if (query.length == 0) {
            $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"page":page,"operation":1,"nbEntreHist":nbEntreHist,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"page":page,"operation":1,"nbEntreHist":nbEntreHist,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
        // $("#resultsHist").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
    });
});
/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {

    $('#searchInputHist').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputHist').val()
        nbEntreHist = $('#nbEntreHist').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            // alert(1111)
            t = 1; // code barre
            
            if (query.length > 0) {
                
                $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"operation":3,"nbEntreHist":nbEntreHist,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"operation":3,"nbEntreHist":nbEntreHist,"query":"","cb":t}); //load initial records
            }
        }else{
            // alert(2222)
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    
                    $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"operation":3,"nbEntreHist":nbEntreHist,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"operation":3,"nbEntreHist":nbEntreHist,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreHist').on("change", function(e) {
        e.preventDefault();

        nbEntreHist = $('#nbEntreHist').val()
        query = $('#searchInputHist').val();

        if (query.length == 0) {
            $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"operation":4,"nbEntreHist":nbEntreHist,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsHist" ).load( "paiement/ajax/listerMoisHistAjax.php",{"operation":4,"nbEntreHist":nbEntreHist,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsHist" ).load( "ajax/listerProduitAjax.php",{"operation":4,"nbEntreeBC":nbEntreeBC}); //load initial records
    });

});   
