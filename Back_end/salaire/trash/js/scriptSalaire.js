/*************************************************************************************** */ 
/*************************************************************************************** */ 
/*************************************************************************************** */ 
//**************************** */ LISTE SALAIRE PERSONNEL /***************************** */
/*************************************************************************************** */ 
/*************************************************************************************** */ 
$(document).ready(function() {

    nbEntrePerson = $('#nbEntrePerson').val();

    $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"operation":1,"nbEntrePerson":nbEntrePerson,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#listePerson").on( "click", ".pagination a", function (e){
    // $("#listesalUnAcc").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)

        nbEntrePerson = $('#nbEntrePerson').val()
        query = $('#searchInputPerson').val();

        if (query.length == 0) {
            $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"page":page,"operation":1,"nbEntrePerson":nbEntrePerson,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"page":page,"operation":1,"nbEntrePerson":nbEntrePerson,"query":query,"cb":"","matricule":matricule}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
    });
});
/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {

    $('#searchInputPerson').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputPerson').val()
        nbEntrePerson = $('#nbEntrePerson').val()
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            // alert(1111)
            t = 1; // code barre
            
            if (query.length > 0) {
                
                $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"operation":3,"nbEntrePerson":nbEntrePerson,"query":query,"cb":t,"matricule":matricule}); //load initial records
            }else{
                $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"operation":3,"nbEntrePerson":nbEntrePerson,"query":"","cb":t,"matricule":matricule}); //load initial records
            }
        }else{
            // alert(2222)
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    
                    $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"operation":3,"nbEntrePerson":nbEntrePerson,"query":query,"cb":t,"matricule":matricule}); //load initial records
                }else{
                    $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"operation":3,"nbEntrePerson":nbEntrePerson,"query":"","cb":t,"matricule":matricule}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntrePerson').on("change", function(e) {
        e.preventDefault();

        nbEntrePerson = $('#nbEntrePerson').val()
        query = $('#searchInputPerson').val()

        if (query.length == 0) {
            $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"operation":4,"nbEntrePerson":nbEntrePerson,"query":"","cb":"","matricule":matricule}); //load initial records
                
        }else{
            $("#listePerson" ).load( "salaire/ajax/salaireAllPersAjax.php",{"operation":4,"nbEntrePerson":nbEntrePerson,"query":query,"cb":"","matricule":matricule}); //load initial records
        }
    });

});
/*************************************************************************************** */ 
/*************************************************************************************** */ 
/*************************************************************************************** */ 
//********************** */ LISTE SALAIRE DES ACCOMPAGNATEURS /************************* */
/*************************************************************************************** */ 
/*************************************************************************************** */ 
function listeAllAccomp() {
    
    $(document).ready(function() {

        nbEntreAcc = $('#nbEntreAcc').val();
    
        $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"operation":1,"nbEntreAcc":nbEntreAcc,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#listeAccompagnateurs").on( "click", ".pagination a", function (e){
        // $("#listesalUnAcc").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)
    
            nbEntreAcc = $('#nbEntreAcc').val()
            query = $('#searchInputAcc').val();
    
            if (query.length == 0) {
                $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"page":page,"operation":1,"nbEntreAcc":nbEntreAcc,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"page":page,"operation":1,"nbEntreAcc":nbEntreAcc,"query":query,"cb":"","matricule":matricule}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
        });
    });
    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {
    
        $('#searchInputAcc').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputAcc').val()
            nbEntreAcc = $('#nbEntreAcc').val()
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                // alert(1111)
                t = 1; // code barre
                
                if (query.length > 0) {
                    
                    $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"operation":3,"nbEntreAcc":nbEntreAcc,"query":query,"cb":t,"matricule":matricule}); //load initial records
                }else{
                    $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"operation":3,"nbEntreAcc":nbEntreAcc,"query":"","cb":t,"matricule":matricule}); //load initial records
                }
            }else{
                // alert(2222)
                t = 0; // no code barre
                setTimeout(() => {
                    if (query.length > 0) {
                        
                        $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"operation":3,"nbEntreAcc":nbEntreAcc,"query":query,"cb":t,"matricule":matricule}); //load initial records
                    }else{
                        $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"operation":3,"nbEntreAcc":nbEntreAcc,"query":"","cb":t,"matricule":matricule}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreAcc').on("change", function(e) {
            e.preventDefault();
    
            nbEntreAcc = $('#nbEntreAcc').val()
            query = $('#searchInputAcc').val()
    
            if (query.length == 0) {
                $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"operation":4,"nbEntreAcc":nbEntreAcc,"query":"","cb":"","matricule":matricule}); //load initial records
                    
            }else{
                $("#listeAccompagnateurs" ).load( "salaire/ajax/salaireAllAccAjax.php",{"operation":4,"nbEntreAcc":nbEntreAcc,"query":query,"cb":"","matricule":matricule}); //load initial records
            }
        });
    
    });
}



/* mm
// POUR ONE CONCERNANANT UN ACCOMPAGNATEUR DONNEE
*/ 
$(document).ready(function() {

    nbEntre = $('#nbEntre').val();
    matricule=$('#mat').val();

    $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"operation":1,"nbEntre":nbEntre,"query":"","cb":"","matricule":matricule}); //load initial records
    
    //executes code below when user click on pagination links
    $("#listesalUnAcc").on( "click", ".pagination a", function (e){
    // $("#listesalUnAcc").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)

        nbEntre = $('#nbEntre').val()
        query = $('#searchInputsalUnAcc').val();

        if (query.length == 0) {
            $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"page":page,"operation":1,"nbEntre":nbEntre,"query":"","cb":"","matricule":matricule}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"page":page,"operation":1,"nbEntre":nbEntre,"query":query,"cb":"","matricule":matricule}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
        // $("#listesalUnAcc").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
    });
});
/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {

    $('#searchInputsalUnAcc').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputsalUnAcc').val()
        nbEntre = $('#nbEntre').val()
        matricule=$('#mat').val()
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            // alert(1111)
            t = 1; // code barre
            
            if (query.length > 0) {
                
                $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"operation":3,"nbEntre":nbEntre,"query":query,"cb":t,"matricule":matricule}); //load initial records
            }else{
                $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"operation":3,"nbEntre":nbEntre,"query":"","cb":t,"matricule":matricule}); //load initial records
            }
        }else{
            // alert(2222)
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    
                    $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"operation":3,"nbEntre":nbEntre,"query":query,"cb":t,"matricule":matricule}); //load initial records
                }else{
                    $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"operation":3,"nbEntre":nbEntre,"query":"","cb":t,"matricule":matricule}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntre').on("change", function(e) {
        e.preventDefault();

        nbEntre = $('#nbEntre').val()
        query = $('#searchInputsalUnAcc').val()
        matricule=$('#mat').val()

        if (query.length == 0) {
            $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"operation":4,"nbEntre":nbEntre,"query":"","cb":"","matricule":matricule}); //load initial records
                
        }else{
            $("#listesalUnAcc" ).load( "salaire/ajax/salaireOneAjax.php",{"operation":4,"nbEntre":nbEntre,"query":query,"cb":"","matricule":matricule}); //load initial records
        }
    });

});

