$(document).ready(function() {

        nbEntreeInst = $('#nbEntreeInst').val();
    
        $("#resultsProductsInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"operation":1,"nbEntreeInst":nbEntreeInst,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsInst").on( "click", ".pagination a", function (e){
        // $("#resultsProductsP").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)n
            nbEntreePer = $('#nbEntreeInst').val()
            query = $('#searchInputInst').val();
    
            if (query.length == 0) {
                id = $('#idCDBO').val();
                console.log('dans 1er If'+id);
                $("#resultsProductsInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"page":page,"operation":1,"nbEntreeInst":nbEntreeInst,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"page":page,"operation":1,"nbEntreeInst":nbEntreeInst,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                    console.log('dans 1er else'+id);
                });
            }
            // $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
        });
    });
    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {
        // alert(4)
        $('#searchInputInst').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputInst').val();
            nbEntreeInst = $('#nbEntreeInst').val();
            id = $('#idCDBO').val();

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                t = 1; // code barre
                // inputVal = $('#searchInputPhD').val();
                // if ($.isNumeric(inputVal)) {
                if (query.length > 0) { 

                    $("#resultsProductsInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"operation":3,"id":id,"nbEntreeInst":nbEntreeInst,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductSInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"operation":3,"id":id,"nbEntreeInst":nbEntreeInst,"query":"","cb":t}); //load initial records
                }     
            }else{
                t = 0; // no code barre
                setTimeout(() => {
                    
                    if (query.length > 0) {
                        // alert(2222)                
                        $("#resultsProductsInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"operation":3,"id":id,"nbEntreeInst":nbEntreeInst,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"operation":3,"id":id,"nbEntreeInst":nbEntreeInst,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreeInst').on("change", function(e) {
            e.preventDefault();

            nbEntreeInst = $('#nbEntreeInst').val()
            query = $('#searchInputInst').val();

            if (query.length == 0) {
                $("#resultsProductsInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"operation":4,"id":id,"nbEntreeInst":nbEntreeInst,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsInst" ).load( "ajax/listerPatrimoineInstanceAjax.php",{"operation":4,"id":id,"nbEntreeInst":nbEntreeInst,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
        });

    }); 
/////////ADDD INSTANCE ////////////////////
$(function() { 
    $("#addInstMateriel").click(function() { 
        var idM = $('#idMatToInstance').val(); 
        var tableSel="#tableauInstance"; 
            $.ajax({
                url: "ajax/listerPatrimoineInstanceAjax.php",
                method: "POST",
                data: {
                    operation: "addInstance",
                    instance:1,
                    idM: idM,
                },
                success: function(data) {
                   let tab = data.split('<>'); 
                   let codeInst=tab[0];
                   let afficherButton=tab[1];
                    if (afficherButton==0) {
                        
                        $("#addInstMateriel").remove();
                    }
                    var markup = "<tr style='background-color:#ebc034'>"+
                        "<td>"+codeInst+"</td>"+
                        "<td><input class='form-control' type='text'  id='proprietaire"+idM+"'></td>"+
                        "<td><input class='form-control' type='text' id='gerant"+idM+"'></td>"+
                        "<td><input class='form-control' type='text' id='caissier"+idM+"'></td>"+
                        "<td><input class='form-control'type='text' id='vendeur"+idM+"'></td>"+
                        "<td><input class='form-control' type='text' id='vendeur"+idM+"'></td>"+
                        "<td><button type='button' class='btn btn-danger'  id='deleterowBtn"+idM+"' onclick='supAccesUser("+idM+","+idM+")'>Annuler</button></td>"+
                    "</tr>";
                $(tableSel).append(markup).slideDown(500); 
                   
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            }); 

        
        return false;

    });
});


function autocompWhoAtt(i) {
    //alert("kooo");
    var sel="searchMatToPer"+i
    var responsDiv="#reponseMatPer"+i
    var query = document.getElementById(sel).value
   /*  var url="ajax/listerPatrimoineAjax.php"
    let formData=new FormData()
    formData.append('query',query)
    const data={}
    data['query']=query 
    data['i']=i 
    $.post(url, JSON.stringify(data)).done(function(response){
        $(responsDiv).html(response);
        //alert(response)
  }); */
    $.ajax({
        url: "ajax/listerPatrimoineAjax.php",
        method: "POST",
        data: {
            query: query, 
            i: i, 
        },
        success: function(data) {
            $(responsDiv).html(data);
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
        });
}
function choisirMatPer(m,i,tx) {
    var tdAtt="#tdAtMatToPer"+i;
    var sel="#searchMatToPer"+i;
    var idMat="#matMatToPer"+i;

    
    var responsDiv="#reponseMatPer"+i;
   //$(sel).html('');
   $(sel).val(tx); 
   $(idMat).val(m);
   $(responsDiv).html('');
   
}
function validerWhoAtt(i){
    var idM="#matMatToPer"+i;
    var m=$(idM).val(); 
    //alert(m);
    $.ajax({
        url: "ajax/listerPatrimoineAjax.php",
        method: "POST",
        data: {
            operation: "attrubMatPer",
            materiel:m,
            i: i,
            m:m,
        },
        success: function(data) {
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}