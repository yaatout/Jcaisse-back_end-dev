/************************DEBUT CATEORIES *************************/
/*****************************************************************/
    $(function() { 
        $("#addCategorie").click(function() { 

            var nomCategorie = $('#nomCategorie').val();
            var codeCategorie = $('#codeCategorie').val();  
    
            if (nomCategorie.length!=0 && codeCategorie.length!=0) {

                $.ajax({
                    url: "ajax/listerPatrimoineAjax.php",
                    method: "POST",
                    data: {
                        operation: "addCategorie",
                        categorie:1,
                        nomCategorie: nomCategorie,
                        codeCategorie: codeCategorie, 
                    },
                    success: function(data) {
                        $('#addCat').modal('hide');
                    },
                    error: function() {
                        alert("La requête ");
                    },
                    dataType: "text"
                });
            } else {
                alert("Remplissez les champs requises");
            }
        
            return false;

        });

    });
    function categorieOnglet() {
        $(document).ready(function() {

            nbEntreeCat = $('#nbEntreeCat').val();
        
            $("#resultsProductsCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"operation":1,"nbEntreeCat":nbEntreeCat,"query":"","cb":""}); //load initial records
            
            //executes code below when user click on pagination links
            $("#resultsProductsCat").on( "click", ".pagination a", function (e){
            // $("#resultsProductsP").on( "click", function (e){
                e.preventDefault();
                $(".loading-div").show(); //show loading element
                // page = page+1; //get page number from link
                page = $(this).attr("data-page"); //get page number from link
                //  alert(page)n
                nbEntreeCat = $('#nbEntreeCat').val()
                query = $('#searchInputCat').val();
        
                if (query.length == 0) {
                    id = $('#idCDBO').val();
                    console.log('dans 1er If'+id);
                    $("#resultsProductsCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"page":page,"operation":1,"nbEntreeCat":nbEntreeCat,"query":"","cb":""}, function(){ //get content from PHP page
                        $(".loading-div").hide(); //once done, hide loading element
                    });
                        
                }else{
                    $("#resultsProductsCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"page":page,"operation":1,"nbEntreeCat":nbEntreeCat,"query":query,"cb":""}, function(){ //get content from PHP page
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
            $('#searchInputCat').on("keyup", function(e) {
                e.preventDefault();
                
                query = $('#searchInputCat').val();
                nbEntreeCat = $('#nbEntreeCat').val();
                id = $('#idCDBO').val();

                var keycode = (e.keyCode ? e.keyCode : e.which);
                if (keycode == '13') {
                    t = 1; // code barre
                    // inputVal = $('#searchInputPhD').val();
                    // if ($.isNumeric(inputVal)) {
                    if (query.length > 0) { 

                        $("#resultsProductsCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"operation":3,"id":id,"nbEntreeCat":nbEntreeCat,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductSCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"operation":3,"id":id,"nbEntreeCat":nbEntreeCat,"query":"","cb":t}); //load initial records
                    }     
                }else{
                    t = 0; // no code barre
                    setTimeout(() => {
                        
                        if (query.length > 0) {
                            // alert(2222)                
                            $("#resultsProductsCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"operation":3,"id":id,"nbEntreeCat":nbEntreeCat,"query":query,"cb":t}); //load initial records
                        }else{
                            $("#resultsProductsCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"operation":3,"id":id,"nbEntreeCat":nbEntreeCat,"query":"","cb":t}); //load initial records
                        }
                    }, 100);
                }
            });
            
            $('#nbEntreeCat').on("change", function(e) {
                e.preventDefault();

                nbEntreeCat = $('#nbEntreeCat').val()
                query = $('#searchInputCat').val();

                if (query.length == 0) {
                    $("#resultsProductsCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"operation":4,"id":id,"nbEntreeCat":nbEntreeCat,"query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsCat" ).load( "ajax/listerPatrimoineAjax.php",{"categorie":1,"operation":4,"id":id,"nbEntreeCat":nbEntreeCat,"query":query,"cb":""}); //load initial records
                }
                    
                // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
            });

        });     
    }
    function mdf_cat(c) {

        $.ajax({

            url: "ajax/listerPatrimoineAjax.php",

            method: "POST",

            data: {

                operation: "recherche",
                categorie:1,
                i: c,

            },

            success: function(data) {
                $('#modCat').modal('show');
                tab = data.split('<>'); 
                $('#idCatMod').val(c);

                    $('#nomCategorieMod').val(tab[0]);
                    $('#codeCategorieMod').val(tab[1]);
            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });

    }

    $(function() {

        $("#btn_mdf_categorie").click(function() {
            var idM = $('#idCatMod').val();  

            var nomCategorieMod = $('#nomCategorieMod').val();
            var codeCategorieMod = $('#codeCategorieMod').val(); 

            //alert(code);
            $.ajax({
                url: "ajax/listerPatrimoineAjax.php",
                method: "POST",
                data: {
                    operation: "modeCategorie",
                    categorie:1,
                    i: idM,
                    nomCategorieMod: nomCategorieMod,
                    codeCategorieMod: codeCategorieMod, 
                },
                success: function(data) {
                    $('#modCat').modal('hide');
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
            return false;

        });

    });
    function sup_cat(i) {
        $.ajax({
            url: "ajax/listerPatrimoineAjax.php",
            method: "POST",
            data: {
                operation: "recherche",
                categorie:1, 
                i: i,
            },
            success: function(data) {
                $('#supCatModal').modal('show');
                tab = data.split('<>'); 
            /* $('#idSSup').val(s);

                    $('#nomSalleSup').val(tab[0]);
                    $('#codeSalleSup').val(tab[1]);
                    $('#descriptionSalleSup').val(tab[2]);*/
            },
            error: function() {
                alert("La requête ");
            },
            dataType: "text"

        });

    }
    $(function() {

        $("#btn_sup_cat").click(function() {
            var ids = $('#idSSup').val();  
            
            //alert(code);
            $.ajax({
                url: "ajax/listerPatrimoineAjax.php",
                method: "POST",
                data: {
                    operation: "supSalle",
                    salle:1,
                    s: ids,
                },
                success: function(data) {
                    $('#supprimerSalModal').modal('hide');
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
            return false;

        });

    });
//////////////////////FIN CATEGORIE/////////////////////


/*******************************************************/
/***********************DEBUT MATERIEL******************/
/*******************************************************/
//AJOUTER MAT
$(function() { 
    $("#addMateriel").click(function() { 

        var nomMateriel = $('#nomMateriel').val();
        var iCategorie = $('#iCategorie').val(); 
        var prixAchat = $('#prixAchat').val(); 
        var quantite = $('#quantite').val(); 
        prixAchat = prixAchat.trim();  
        nomMateriel = nomMateriel.trim();

        let code='';
        let tabNom = nomMateriel.split(" ");
            tabNom.forEach((mot) => {
                console.log(mot);
                console.log(mot.charAt(0).toUpperCase());
                let lettre=mot.charAt(0).toUpperCase();
                code=code+lettre;
            
            });
        console.log(code);
        
        // alert(iCategorie);
        var dateM = $('#dateM').val();
        if (nomMateriel.length!=0 && prixAchat.length!=0 && quantite>0) {

            $.ajax({
                url: "ajax/listerPatrimoineAjax.php",
                method: "POST",
                data: {
                    operation: "addMateriel",
                    materiel:1,
                    nomMateriel: nomMateriel,
                    code: code,
                    iCategorie: iCategorie,
                    prixAchat: prixAchat,
                    quantite:quantite,
                    dateM: dateM,
                },
                success: function(data) {
                    $('#addMat').modal('hide');
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
        } else {
            alert("Remplissez les champs requises");
        } 
       
        return false;

    });

});
$(document).ready(function() {

    nbEntreeMat = $('#nbEntreeMat').val();

    $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"operation":1,"nbEntreeMat":nbEntreeMat,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsProductsMat").on( "click", ".pagination a", function (e){
    // $("#resultsProductsP").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)n
        nbEntreeMat = $('#nbEntreeMat').val()
        query = $('#searchInputMat').val();

        if (query.length == 0) {
           // id = $('#idCDBO').val();
            console.log('dans 1er If');
            $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"page":page,"operation":1,"nbEntreeMat":nbEntreeMat,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"page":page,"operation":1,"nbEntreeMat":nbEntreeMat,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
                console.log('dans 1er else');
            });
        }
        // $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
    });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {
    // alert(4)
    $('#searchInputMat').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputMat').val();
        nbEntreeMat = $('#nbEntreeMat').val();
        id = $('#idCDBO').val();

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            t = 1; // code barre
            // inputVal = $('#searchInputPhD').val();
            // if ($.isNumeric(inputVal)) {
            if (query.length > 0) {  
                $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"operation":3,"id":id,"nbEntreeMat":nbEntreeMat,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"operation":3,"id":id,"nbEntreeMat":nbEntreeMat,"query":"","cb":t}); //load initial records
            }     
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                
                if (query.length > 0) {
                    // alert(2222)                
                    $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"operation":3,"id":id,"nbEntreeMat":nbEntreeMat,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"operation":3,"id":id,"nbEntreeMat":nbEntreeMat,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreeMat').on("change", function(e) {
        e.preventDefault();

        nbEntreeMat = $('#nbEntreeMat').val()
        query = $('#searchInputMat').val();

        if (query.length == 0) {
            $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"operation":4,"id":id,"nbEntreeMat":nbEntreeMat,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsProductsMat" ).load( "ajax/listerPatrimoineAjax.php",{"materiel":1,"operation":4,"id":id,"nbEntreeMat":nbEntreeMat,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
    });

});

function mdf_CodeBarreMateriel(i) {

    $.ajax({

        url: "ajax/listerPatrimoineAjax.php",

        method: "POST",

        data: {

            operation: "recherche",

            i: i,
            materiel:1,

        },

        success: function(data) {

            $('#modifierCodeBModal').modal('show');

            tab = data.split('<>'); 
            $('#idM_CB').val(i);

            if (tab[3] != null || tab[3].length === 0 ) {

                $('#codeBarreMateriel').val(tab[3]);

            } else {

                $('#codeBarreMateriel').focus();

            }
        },

        error: function() {

            alert("La requête ");

        },

        dataType: "text"

    });

}

$(function() {

    $("#btn_mdf_CodeBarreMateriel").click(function() {

        var idM_CB = $('#idM_CB').val();

        var codeBarreMateriel = $('#codeBarreMateriel').val();

        //alert(code);

        $.ajax({

            url: "ajax/listerPatrimoineAjax.php",

            method: "POST",

            data: {

                operation: "modeCBMateriel",

                i: idM_CB,
                materiel:1,

                codeBarreMateriel: codeBarreMateriel,

            },

            success: function(data) {

                $('#modifierCodeBModal').modal('hide');

            },

            error: function() {

                alert("La requête ");

            },

            dataType: "text"

        });

        return false;

    });

});

function mdf_CodeRFIDMateriel(i) {

    $.ajax({

        url: "ajax/listerPatrimoineAjax.php",

        method: "POST",

        data: {

            operation: "recherche",

            i: i,
            materiel:1,

        },

        success: function(data) {
            
            
            $('#modifierCodeRFIDModal').modal('show');

            tab = data.split('<>'); 

            if (tab[4] != null || tab[4].length === 0 ) {

                $('#codeRFID').val(tab[4]);

            } else {

                $('#codeRFID').focus();

            }
        },

        error: function() {

            alert("La requête ");

        },

        dataType: "text"

    });

}

$(function() {

    $("#btn_mdf_CodeRFIDMateriel").click(function() {
        var idM_CRFID = $('#idM_CRFID').val();        
        var codeRFID = $('#codeRFID').val();
        //alert(code);
        $.ajax({
            url: "ajax/listerPatrimoineAjax.php",
            method: "POST",
            data: {
                operation: "modeCRFIDMateriel",
                i: idM_CRFID,
                codeRFID: codeRFID,
                materiel:1,
            },
            success: function(data) {
                $('#modifierCodeRFIDModal').modal('hide');
            },
            error: function() {
                alert("La requête ");
            },
            dataType: "text"
        });
        return false;

    });

});
function mdf_Materiel(i) {

    $.ajax({

        url: "ajax/listerPatrimoineAjax.php",

        method: "POST",

        data: {

            operation: "recherche",
            materiel:1,
            i: i,

        },

        success: function(data) {
            $('#modifierMatModal').modal('show');
            tab = data.split('<>'); 
            $('#idM').val(i);

                $('#nomMatMod').val(tab[0]);
                $('#prixAchatMatMod').val(tab[1]);
                $('#dateMatMod').val(tab[2]);
        },

        error: function() {

            alert("La requête ");

        },

        dataType: "text"

    });

}

$(function() {

    $("#btn_mdf_Materiel").click(function() {
        var idM = $('#idM').val();  

        var nomMatMod = $('#nomMatMod').val();
        var prixAchatMatMod = $('#prixAchatMatMod').val();
        var dateMatMod = $('#dateMatMod').val();

        
                
                
        //alert(code);
        $.ajax({
            url: "ajax/listerPatrimoineAjax.php",
            method: "POST",
            data: {
                operation: "modeMateriel",
                materiel:1,
                i: idM,
                nomMatMod: nomMatMod,
                prixAchatMatMod: prixAchatMatMod,
                dateMatMod: dateMatMod,
            },
            success: function(data) {
                $('#modifierMatModal').modal('hide');
            },
            error: function() {
                alert("La requête ");
            },
            dataType: "text"
        });
        return false;

    });

});

function sup_Materiel(i) {

    $.ajax({

        url: "ajax/listerPatrimoineAjax.php",

        method: "POST",

        data: {

            operation: "recherche",
            materiel:1, 
            i: i,

        },

        success: function(data) {
            $('#supprimerMatModal').modal('show');
            tab = data.split('<>'); 
            $('#idMSup').val(i);

                $('#nomMatSup').val(tab[0]);
                $('#prixAchatMatSup').val(tab[1]);
                $('#dateMatSup').val(tab[2]);
        },

        error: function() {

            alert("La requête ");

        },

        dataType: "text"

    });

}
$(function() {

    $("#btn_sup_Materiel").click(function() {
        var idM = $('#idMSup').val();  
        
        //alert(code);
        $.ajax({
            url: "ajax/listerPatrimoineAjax.php",
            method: "POST",
            data: {
                operation: "supMateriel",
                materiel:1,
                i: idM,
            },
            success: function(data) {
                $('#supprimerMatModal').modal('hide');
            },
            error: function() {
                alert("La requête ");
            },
            dataType: "text"
        });
        return false;

    });

});
/******************FIN MATERIELS ******************/

/****************** DEBUT PERSONNEL *****************/
function personnelOnglet() {
    $(document).ready(function() {

        nbEntreePer = $('#nbEntreePer').val();
    
        $("#resultsProductsPer" ).load( "ajax/listerPatrimoineAjax.php",{"personnel":1,"operation":1,"nbEntreePer":nbEntreePer,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsPer").on( "click", ".pagination a", function (e){
        // $("#resultsProductsP").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)n
            nbEntreePer = $('#nbEntreePer').val()
            query = $('#searchInputPer').val();
    
            if (query.length == 0) {
                id = $('#idCDBO').val();
                console.log('dans 1er If'+id);
                $("#resultsProductsPer" ).load( "ajax/listerPatrimoineAjax.php",{"personnel":1,"page":page,"operation":1,"nbEntreePer":nbEntreePer,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsPer" ).load( "ajax/listerPatrimoineAjax.php",{"personnel":1,"page":page,"operation":1,"nbEntreePer":nbEntreePer,"query":query,"cb":""}, function(){ //get content from PHP page
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
        $('#searchInputPer').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputPer').val();
            nbEntreePer = $('#nbEntreePer').val();
            id = $('#idCDBO').val();

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                t = 1; // code barre
                // inputVal = $('#searchInputPhD').val();
                // if ($.isNumeric(inputVal)) {
                if (query.length > 0) { 

                    $("#resultsProductsPer" ).load( "ajax/ajax/listerPatrimoineAjax.php",{"personnel":1,"operation":3,"id":id,"nbEntreePer":nbEntreePer,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsPer" ).load( "ajax/ajax/listerPatrimoineAjax.php",{"personnel":1,"operation":3,"id":id,"nbEntreePer":nbEntreePer,"query":"","cb":t}); //load initial records
                }     
            }else{
                t = 0; // no code barre
                setTimeout(() => {
                    
                    if (query.length > 0) {
                        // alert(2222)                
                        $("#resultsProductsPer" ).load( "ajax/ajax/listerPatrimoineAjax.php",{"personnel":1,"operation":3,"id":id,"nbEntreePer":nbEntreePer,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsPer" ).load( "ajax/ajax/listerPatrimoineAjax.php",{"personnel":1,"operation":3,"id":id,"nbEntreePer":nbEntreePer,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntrePer').on("change", function(e) {
            e.preventDefault();

            nbEntreePer = $('#nbEntreePer').val()
            query = $('#searchInputPer').val();

            if (query.length == 0) {
                $("#resultsProductsPer" ).load( "ajax/listerPatrimoineAjax.php",{"personnel":1,"operation":4,"id":id,"nbEntreePer":nbEntreePer,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsPer" ).load( "ajax/listerPatrimoineAjax.php",{"personnel":1,"operation":4,"id":id,"nbEntreePer":nbEntreePer,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
        });

    });    
}

function autocompMatToPer(i) {
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
function validerAtMatToPer(i){
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

/******************FIN PERSONNEL ******************/

////////////////////////////////////////////////////
/****************** DEBUT  SALLES*****************/
////////////////////////////////////////////////////

function salleOnglet() {
    $(document).ready(function() {

        nbEntreeSal = $('#nbEntreeSal').val();
    
        $("#resultsProductsSal" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"operation":1,"nbEntreeSal":nbEntreeSal,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsSal").on( "click", ".pagination a", function (e){
        // $("#resultsProductsP").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)n
            nbEntreePer = $('#nbEntreeSal').val()
            query = $('#searchInputSal').val();
    
            if (query.length == 0) {
                id = $('#idCDBO').val();
                console.log('dans 1er If'+id);
                $("#resultsProductsSal" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"page":page,"operation":1,"nbEntreeSal":nbEntreeSal,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsSal" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"page":page,"operation":1,"nbEntreeSal":nbEntreeSal,"query":query,"cb":""}, function(){ //get content from PHP page
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
        $('#searchInputSal').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputSal').val();
            nbEntreeSal = $('#nbEntreeSal').val();
            id = $('#idCDBO').val();

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                t = 1; // code barre
                // inputVal = $('#searchInputPhD').val();
                // if ($.isNumeric(inputVal)) {
                if (query.length > 0) { 

                    $("#resultsProductsSal" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"operation":3,"id":id,"nbEntreeSal":nbEntreeSal,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductSalt" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"operation":3,"id":id,"nbEntreeSal":nbEntreeSal,"query":"","cb":t}); //load initial records
                }     
            }else{
                t = 0; // no code barre
                setTimeout(() => {
                    
                    if (query.length > 0) {
                        // alert(2222)                
                        $("#resultsProductsSal" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"operation":3,"id":id,"nbEntreeSal":nbEntreeSal,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsSal" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"operation":3,"id":id,"nbEntreeSal":nbEntreeSal,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreeSal').on("change", function(e) {
            e.preventDefault();

            nbEntreeSal = $('#nbEntreeSal').val()
            query = $('#searchInputSal').val();

            if (query.length == 0) {
                $("#resultsProductsSal" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"operation":4,"id":id,"nbEntreeSal":nbEntreeSal,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsSal" ).load( "ajax/listerPatrimoineAjax.php",{"salle":1,"operation":4,"id":id,"nbEntreeSal":nbEntreeSal,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
        });

    });     
}
//AJOUTER SALLE
$(function() { 
    $("#btnAddSalle").click(function() { 
        var nomSalle = $('#nomSalle').val();
        var codeSalle = $('#codeSalle').val();
        var description = $('#description').val();
         nomSalle = nomSalle.trim();
         codeSalle = codeSalle.trim();
         description = description.trim();
 
        if (nomSalle.length!=0 && codeSalle.length!=0) {
            $.ajax({
                url: "ajax/listerPatrimoineAjax.php",
                method: "POST",
                data: {
                    operation: "addSalle",
                    salle:1,
                    nomSalle: nomSalle,
                    codeSalle: codeSalle,
                    description: description,
                },
                success: function(data) {
                    $('#addSalle').modal('hide');
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
        } else {
            alert("Remplissez les champs requises");
        }

        
        return false;

    });

});
function autocompMatToSal(s) {
    //alert("kooo");
    var sel="searchMatToSal"+s
    var responsDiv="#reponseMatSal"+s
    var query = document.getElementById(sel).value
    /* var url="ajax/listerPatrimoineAjax.php"
        let formData=new FormData()
        formData.append('query',query)
        const data={}
        data['query']=query 
        data['s']=s 
        $.post(url, JSON.stringify(data)).done(function(response){
            $(responsDiv).html(response);
            //alert(response)
    }); */

    $.ajax({
        url: "ajax/listerPatrimoineAjax.php",
        method: "POST",
        data: {
            query: query, 
            s: s, 
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
function choisirMatSal(m,s,tx) {
    var tdAtt="#tdAtMatToSal"+s;
    var sel="#searchMatToSal"+s;
    var idMat="#matMatToSal"+s;
    var responsDiv="#reponseMatSal"+s;
   //$(sel).html('');
   $(sel).val(tx); 
   $(idMat).val(m);
   $(responsDiv).html('');   
}
function validerAtMatToSal(s){
    var idM="#matMatToSal"+s;
    var m=$(idM).val(); 
    //alert(m);
    $.ajax({
        url: "ajax/listerPatrimoineAjax.php",
        method: "POST",
        data: {
            operation: "attrubMatSal",
            salle:m,
            s: s,
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
function mdf_Salle(s) {

    $.ajax({

        url: "ajax/listerPatrimoineAjax.php",

        method: "POST",

        data: {

            operation: "recherche",
            salle:1,
            s: s,

        },

        success: function(data) {
            $('#modifierSalModal').modal('show');
            tab = data.split('<>'); 
            $('#idS').val(s);

                $('#nomSalleMod').val(tab[0]);
                $('#codeSalleMod').val(tab[1]);
                $('#descriptionSalleMod').val(tab[2]);
        },

        error: function() {

            alert("La requête ");

        },

        dataType: "text"

    });

}

$(function() {

    $("#btn_mdf_Salle").click(function() {
        var idS = $('#idS').val();  
        var nomSalle = $('#nomSalleMod').val();
        var codeSalle = $('#codeSalleMod').val();
        var description = $('#descriptionSalleMod').val();

        nomSalle = nomSalle.trim();
        codeSalle = codeSalle.trim(); 
       if (nomSalle.length!=0 && codeSalle.length!=0) {
            //alert(code);
            $.ajax({
                url: "ajax/listerPatrimoineAjax.php",
                method: "POST",
                data: {
                    operation: "modeSalle",
                    salle:1,
                    s: idS,
                    nomSalleMod: nomSalleMod,
                    codeSalleMod: codeSalleMod,
                    descriptionSalleMod: descriptionSalleMod,
                },
                success: function(data) {
                    $('#modifierSalModal').modal('hide');
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
       }else{
            alert("Remplissez les champs requises");
       }
        
        return false;

    });

});
function sup_Salle(s) {

    $.ajax({

        url: "ajax/listerPatrimoineAjax.php",

        method: "POST",

        data: {

            operation: "recherche",
            salle:1, 
            s: s,

        },

        success: function(data) {
            $('#supprimerSalModal').modal('show');
            tab = data.split('<>'); 
            $('#idSSup').val(s);

                $('#nomSalleSup').val(tab[0]);
                $('#codeSalleSup').val(tab[1]);
                $('#descriptionSalleSup').val(tab[2]);
        },

        error: function() {

            alert("La requête ");

        },

        dataType: "text"

    });

}
$(function() {

    $("#btn_sup_Salle").click(function() {
        var ids = $('#idSSup').val();  
        
        //alert(code);
        $.ajax({
            url: "ajax/listerPatrimoineAjax.php",
            method: "POST",
            data: {
                operation: "supSalle",
                salle:1,
                s: ids,
            },
            success: function(data) {
                $('#supprimerSalModal').modal('hide');
            },
            error: function() {
                alert("La requête ");
            },
            dataType: "text"
        });
        return false;

    });

});

/*****************************************************/
