
/*************************************************************************** */
/****************************************************************************/
/*************** Début modification catalogue details boutique***************/

/////XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX///////////////
$(document).ready(function() {

    nbEntreeBO = $('#nbEntreeBO').val();
    id = $('#idCDBO').val();

    $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":1,"id":id,"nbEntreeBO":nbEntreeBO,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsProductsBO").on( "click", ".pagination a", function (e){
    // $("#resultsProductsP").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)n
        nbEntreeBO = $('#nbEntreeBO').val()
        query = $('#searchInputBO').val();

        if (query.length == 0) {
            id = $('#idCDBO').val();
            console.log('dans 1er If'+id);
            $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeBO":nbEntreeBO,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeBO":nbEntreeBO,"query":query,"cb":""}, function(){ //get content from PHP page
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
    $('#searchInputBO').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputBO').val();
        nbEntreeBO = $('#nbEntreeBO').val();
        id = $('#idCDBO').val();

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            t = 1; // code barre
            // inputVal = $('#searchInputPhD').val();
            // if ($.isNumeric(inputVal)) {
            if (query.length > 0) { 

                $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":3,"id":id,"nbEntreeBO":nbEntreeBO,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":3,"id":id,"nbEntreeBO":nbEntreeBO,"query":"","cb":t}); //load initial records
            }     
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                
                if (query.length > 0) {
                    // alert(2222)                
                    $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":3,"id":id,"nbEntreeBO":nbEntreeBO,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":3,"id":id,"nbEntreeBO":nbEntreeBO,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreePh').on("change", function(e) {
        e.preventDefault();

        nbEntreeBO = $('#nbEntreeBO').val()
        query = $('#searchInputBO').val();

        if (query.length == 0) {
            $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":4,"id":id,"nbEntreeBO":nbEntreeBO,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":4,"id":id,"nbEntreeBO":nbEntreeBO,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
    });

});

/*************    TRI    ******************/
$(document).ready(function() {
    // alert(1500)
    // var page = 0;
    //executes code below when user click on pagination links
    //1=croissant et 0=decroissant
    triPH = 0;
    $(document).on("click", "#resultsProductsP th", function(e) {
        e.preventDefault();
    // alert(12)
        query = $('#searchInputBO').val();
        nbEntreeBO = $('#nbEntreeBO').val()
        // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":1, "query":""}); //load initial records
        
        if (triPH == 1) {
            // alert(triPH)
            if (query.length == 0) {
                $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":2,"id":id,"nbEntreeBO":nbEntreeBO,"tri":1, "query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsBO" ).load( "ajax/listerCatalogueBAjax.php",{"operation":2,"id":id,"nbEntreeBO":nbEntreeBO,"tri":1, "query":query,"cb":""}); //load initial records
            }

            triPH = 0;
            // alert(triPH)
            
        } else {
            // alert(triPH)
            if (query.length == 0) {
                $("#resultsProductsBO").load( "ajax/listerCatalogueBAjax.php",{"operation":2,"id":id,"nbEntreeBO":nbEntreeBO,"tri":0, "query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsBO").load( "ajax/listerCatalogueBAjax.php",{"operation":2,"id":id,"nbEntreeBO":nbEntreeBO,"tri":0, "query":query,"cb":""}); //load initial records
            }
            
            triPH = 1;
            // alert(triPH)
        }
    });
});
function newTabDoubClickBout() {
    /*************** Début modification catalogue Doublons B***************/
    $(document).ready(function() {

        nbEntreeBD = $('#nbEntreeBD').val();
        id = $('#idCDD').val();

        $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":1,"id":id,"nbEntreeBD":nbEntreeBD,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsBD").on( "click", ".pagination a", function (e){
        // $("#resultsProductsP").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)n
            nbEntreeBD = $('#nbEntreeBD').val()
            query = $('#searchInputBD').val();

            if (query.length == 0) {
                $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeBD":nbEntreeBD,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeBD":nbEntreeBD,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
        });
    });

    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {
        // alert(4)
        $('#searchInputBD').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputBD').val()
            nbEntreeBD = $('#nbEntreeBD').val()

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                t = 1; // code barre
                // inputVal = $('#searchInputBD').val();
                // if ($.isNumeric(inputVal)) {
                if (query.length > 0) { 

                    $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":3,"id":id,"nbEntreeBD":nbEntreeBD,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":3,"id":id,"nbEntreeBD":nbEntreeBD,"query":"","cb":t}); //load initial records
                }     
            }else{
                t = 0; // no code barre
                setTimeout(() => {
                    
                    if (query.length > 0) {
                        // alert(2222)                
                        $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":3,"id":id,"nbEntreeBD":nbEntreeBD,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":3,"id":id,"nbEntreeBD":nbEntreeBD,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreeBD').on("change", function(e) {
            e.preventDefault();

            nbEntreeBD = $('#nbEntreeBD').val()
            query = $('#searchInputBD').val();

            if (query.length == 0) {
                $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":4,"id":id,"nbEntreeBD":nbEntreeBD,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":4,"id":id,"nbEntreeBD":nbEntreeBD,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreeBD":nbEntreeBD}); //load initial records
        });

    });

    /*************    TRI    ******************/
    $(document).ready(function() {
        // alert(1500)
        // var page = 0;
        //executes code below when user click on pagination links
        //1=croissant et 0=decroissant
        triPH = 0;
        $(document).on("click", "#resultsProductsBD th", function(e) {
            e.preventDefault();
        // alert(12)
            query = $('#searchInputBD').val();
            nbEntreeBD = $('#nbEntreeBD').val()
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreeBD":nbEntreeBD,"tri":1, "query":""}); //load initial records
            
            if (triPH == 1) {
                // alert(triPH)
                if (query.length == 0) {
                    $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":2,"id":id,"nbEntreeBD":nbEntreeBD,"tri":1, "query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsBD" ).load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":2,"id":id,"nbEntreeBD":nbEntreeBD,"tri":1, "query":query,"cb":""}); //load initial records
                }

                triPH = 0;
                // alert(triPH)
                
            } else {
                // alert(triPH)
                if (query.length == 0) {
                    $("#resultsProductsBD").load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":2,"id":id,"nbEntreeBD":nbEntreeBD,"tri":0, "query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsBD").load( "ajax/listerCatalogueDoublonBAjax.php",{"operation":2,"id":id,"nbEntreeBD":nbEntreeBD,"tri":0, "query":query,"cb":""}); //load initial records
                }
                
                triPH = 1;
                // alert(triPH)
            }
        });
    });

    /*************** Fin modification Catalogue Doublons B***************/   
}
/**Debut Modifier Produit  */
    function mdf_Designation_B(idDesignation,id,ordre){
        var designation = $('#designationD-' + idDesignation).val();
        var categorie = $('#categorieD-' + idDesignation).val();
        var uniteStock = $('#uniteStockD-' + idDesignation).val();
        var nbrArtUS = $('#nbrArtUSD-' + idDesignation).val();
        var prixuniteStock = $('#prixuniteStockD-' + idDesignation).val();
        var prix = $('#prixD-' + idDesignation).val();
        var prixachat = $('#prixachatD-' + idDesignation).val();
        var codeBarreDesign = $('#codeBarreDesignD-' + idDesignation).val();

        // console.log('GGGGggggggggggGGGG'+idDesignation);
        $.ajax({
            url: "ajax/catalogueSqlAjax.php",
            method: "POST",
            data: {
                operation: 'EditProduit_B',
                idDesignation : idDesignation,
                id : id,
                designation : designation,
                categorie : categorie,
                uniteStock : uniteStock,
                nbreArticleUS : nbrArtUS,
                prixUS : prixuniteStock,
                prix : prix,
                codeBarreDesignation : codeBarreDesign
            },
            success: function(data) {
                tab = data.split('<>');
                if (tab[0] == 1) {
                    $('#pencilmoD-'+ idDesignation).remove();
                    $('#designationD-' + idDesignation).prop('disabled', true);
                    $('#categorieD-' + idDesignation).prop('disabled', true);
                    $('#uniteStockD-' + idDesignation).prop('disabled', true);
                    $('#nbrArtUSD-' + idDesignation).prop('disabled', true);
                    $('#prixuniteStockD-' + idDesignation).prop('disabled', true);
                    $('#prixD-' + idDesignation).prop('disabled', true);
                    $('#prixachatD-' + idDesignation).prop('disabled', true);
                    $('#codeBarreDesignD-' + idDesignation).prop('disabled', true);
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
/**Debut Supprimer Produit */
    function spm_Designation_B(idDesignation,id,ordre){
        $.ajax({
            url:"ajax/catalogueSqlAjax.php",
            method:"POST",
            data:{
                operation:'SearchProduit',
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
                $('#uniteStock_Spm').text(result[3]);
                $('#nbArticleUniteStock_Spm').text(result[4]);
                $('#prix_Spm').text(result[5]);
                $('#prixuniteStock_Spm').text(result[6]);
                $('#supprimerDesignation').modal('show');
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    }
    $(function(){
        $("#btnSupprimerDesignation").click( function(){
            var ordre=$('#ordre_Spm').val();
            var id=$('#id_Spm').val();
            var idDesignation=$('#idDesignation_Spm').val();
            $.ajax({
                url:"ajax/catalogueSqlAjax.php",
                method:"POST",
                data:{
                    operation: 'DeleteProduit',
                    idDesignation : idDesignation,
                    id : id,
                },
                success: function (data) {
                    result=data.split('<>');
                    if(result[0]==1){
                        $('#supprimerDesignation').modal('hide');
                        var table = $('#tableDesignation').DataTable();
                        nv_ordre=ordre - 1;
                        var ligne = table.row(nv_ordre).data();
                        ligne[0] = ordre; ligne[1] = result[1];
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
/**Fin Supprimer Produit */

/**Debut Uploader Image Existante Designation catalogue**/
function imgE_DesignationC (idD,id,i){
    
    //alert("tab[6]");
    console.log("jjjjjjjjj");
    $.ajax({
        url:"ajax/catalogueSqlAjax.php",
        method:"POST",
        data:{
            operation:'image',
            id : id,
            idD : idD,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Upd_ExC').val(tab[0]);
            $('#idDE').val(idD);
            $('#img_Upd_ExC').val(tab[6]);
            $("#imgsrc_UpdC").attr("src",'uploads/'+tab[6]);
            $('#imageEDesignationC').modal('show');
        },
        error: function() {
            alert("La requête 1 "); },
        dataType:"text"
    });
}
/**Fin Uploader Image Existante Designation catalogue**/

/**Debut Uploader Image New Designation catalogue**/
function imgN_DesignationC (idD,id,i){
    $.ajax({
        url:"ajax/catalogueSqlAjax.php",
        method:"POST",
        data:{
            operation:'image',
            id : id,
            idD : idD,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Upd_NvC').val(tab[0]);
            $('#idDN').val(idD);
            console.log('iiiiiii'+idD);
            $('#imageNDesignationC').modal('show');
        },
        error: function() {
            alert("La requête 2"); },
        dataType:"text"
    });
}
/**Fin Uploader Image New Designation catalogue**/

/**Debut Doublon Designation catalogueTotal**/
function dbl_Designation_B(idD,idCT,ordre){
    //alert(id);
    /*****************DataTable debut************************/
    $("#idDo").val(idD);
    $("#idCT").val(idCT);
    $("#ordreTableDo").val(ordre);
    if ($.fn.DataTable.isDataTable('#tableDo')) {
          $('#tableDo').DataTable().destroy();
      }
    console.log(idD+" "+idCT+" "+ordre);
    $('#popD').modal('show');
    $("#tableDo").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/listeProduit-DoublonBAjax.php?idD="+idD+"&idCT="+idCT+"",
    "aoColumns": [
      { mData: "0" },
      { mData: "1" },
      { mData: "2" },
      { mData: "3" },
      { mData: "4" },
      { mData: "5" },
      { mData: "6" }
      ],
    });
}
/**Fin Doublon Designation catalogueTotal**/

/**Debut eliminer Doublon  catalogueTotal**/
function eli_Doub_B(){

    var id=$("#idDo").val();
    var idCT=$("#idCT").val();
    var ordre=$("#ordreTableDo").val();
    console.log('ordre'+ordre);
    console.log('gggggggggggFFFFFFF idCT='+idCT);
    $.ajax({
        url:"ajax/listeProduit-DoublonBAjax.php",
        method:"GET",
        data:{
            operation:1,
            tache : 'eliminerDoub',
            id : id,
            idCT : idCT,
        },
        success: function (data) {
          // tab = data.split('+');
          // tabIdFusionDoub=tab[0].join('-');
          // tabDoublon=tab[1].join('-');
          //console.log(tab[0]);
          console.log('ffffffffffffff'+data);
           $('#categorie-' + id).prop('disabled', true);
          var table = $('#tableDo').DataTable();
               var ligne = table.row(ordre).data();
               ligne[7] = 'En cours';
               $('#tableDo').dataTable().fnUpdate(ligne,ordre,undefined,false);
              $( table.row(ordre).nodes() ).css({ 'background-color' : 'yellow'});

           //$('#designationD-' + idDesignation).prop('disabled', true);
           $('#popD').modal('hide');
          
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
}
/**Fin eliminer Doublon  catalogueTotal**/

/**Debut eliminer tous Doublon  catalogueTotal**/
function eli_tousDoub_B(idCT){
    //alert(idCT);
    console.log('eliminer tous'+idCT);
    $.ajax({
        url:"ajax/listeProduit-DoublonBAjax.php",
        method:"GET",
        data:{
            operation:1,
            tache : 'eliminerTDoub',
            idCT : idCT,
        },
        success: function (data) {
          // tab = data.split('+');
          // tabIdFusionDoub=tab[0].join('-');
          // tabDoublon=tab[1].join('-');
          //console.log(tab[0]);
          console.log('ffffffffffffff'+data);
           $('#elToutDoub').modal('hide');
          
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
}
/**Fin eliminer tous Doublon  catalogueTotal**/


/**Debut Uploader Image Existante Designation Vitrine**/
function imgEX_DesignationVT(id){
    
    //alert("tab[6]");
    console.log("jjjjjjjjj");
    $.ajax({
        url:"ajax/vitrineAjax.php",
        method:"POST",
        data:{
            operation:1,
            id : id,
        },
        success: function (data) {
            tab=data.split('<>');
            
            //alert('../../uploads/'+tab[6]);
            $('#id_Upd_Ex').val(tab[0]);
            $('#img_Upd_Ex').val(tab[6]);
            $("#imgsrc_Upd").attr("src",'../JCaisse/vitrine/uploads/'+tab[6]);
            $('#imageExDesignation').modal('show');
        },
        error: function() {
            alert("La requête 1 "); },
        dataType:"text"
    });
}
/**Fin Uploader Image Existante Designation Vitrine**/

/**Debut Uploader Image Nouvelle Designation Vitrine**/
function imgNV_DesignationVT(id){
    $.ajax({
        url:"ajax/vitrineAjax.php",
        method:"POST",
        data:{
            operation:1,
            id : id,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Upd_Nv').val(tab[0]);
            $('#imageNvDesignation').modal('show');
        },
        error: function() {
            alert("La requête 2"); },
        dataType:"text"
    });
}
/**Fin Uploader Image Nouvelle  Designation Vitrine**/

function newTabFusClickB() {
    /*************** Début modification catalogue Fusion Bou***************/
    $(document).ready(function() {

        nbEntreeBF = $('#nbEntreeBF').val();
        id = $('#idCDBF').val();

        $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"operation":1,"id":id,"nbEntreeBF":nbEntreeBF,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsPBF").on( "click", ".pagination a", function (e){
        // $("#resultsProductsP").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)n
            nbEntreePhF = $('#nbEntreeBF').val()
            query = $('#searchInputBF').val();

            if (query.length == 0) {
                $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeBF":nbEntreeBF,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeBF":nbEntreeBF,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
        });
    });

    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {
        // alert(4)
        $('#searchInputBF').on("keyup", function(e) {
            e.preventDefault();

            
        id = $('#idCDBF').val();
            query = $('#searchInputBF').val()
            nbEntreeBF = $('#nbEntreeBF').val()

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                t = 1; // code barre
                // inputVal = $('#searchInputPhF').val();
                // if ($.isNumeric(inputVal)) {
                if (query.length > 0) { 

                    $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"operation":3,"id":id,"nbEntreeBF":nbEntreeBF,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"operation":3,"id":id,"nbEntreeBF":nbEntreeBF,"query":"","cb":t}); //load initial records
                }     
            }else{
                t = 0; // no code barre
                setTimeout(() => {
                    
                    if (query.length > 0) {
                        // alert(2222)                
                        $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"operation":3,"id":id,"nbEntreeBF":nbEntreeBF,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"operation":3,"id":id,"nbEntreeBF":nbEntreeBF,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreeBF').on("change", function(e) {
            e.preventDefault();

            nbEntreeBF = $('#nbEntreeBF').val()
            query = $('#searchInputBF').val();

            if (query.length == 0) {
                $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"operation":4,"id":id,"nbEntreeBF":nbEntreeBF,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsPBF" ).load( "ajax/listerCatalogueFusionBAjax.php",{"operation":4,"id":id,"nbEntreeBF":nbEntreeBF,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePhF":nbEntreePhF}); //load initial records
        });

    });


    /*************** Fin modification   PH***************/   
}
/**Debut Doublon Designation catalogueTotal**/
function fus_Designation_BO(idD,idCT,ordre){
    //alert(id);
    $("#categorieFB").val('');
    $("#uniteStockFB").val('');
    $("#nbreArticleUniteStockFB").val(0);
    $("#prixuniteStockFB").val(0);
    $("#prixFB").val(0);
    $("#prixAchatFB").val(0);
    $("#codeBarreDesignationFB").val('');
    /*****************DataTable debut************************/
    $("#idFB").val(idD);
    $("#idCTFB").val(idCT);
    if ($.fn.DataTable.isDataTable('#tableFuB')) {
          $('#tableFuB').DataTable().destroy();
      }
   
    
    console.log(idD+" "+idCT+" "+ordre);
    $("#tableFuB").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/listeProduit-FusionBAjax.php?idD="+idD+"&idCT="+idCT+"",
    "aoColumns": [
      { mData: "0" },
      { mData: "1" },
      { mData: "2" },
      { mData: "3" },
      { mData: "4" },
      { mData: "5" },
      { mData: "6" },
      { mData: "7" }
      ],"initComplete": function(settings, json) {
        idD=$("#idFB").val();
        if ($('#categorieBO-' + idD).is(":checked")) {
            $("#categorieFB").val($('#categorieBO-' + idD).val());
        }
        if ($('#uniteStockBO-' + idD).is(":checked")) {
            $("#uniteStockFB").val($('#uniteStockBO-' + idD).val());
        }
        if ($('#nbreArticleUniteStockBO-' + idD).is(":checked")) {
            $("#nbreArticleUniteStockFB").val($('#nbreArticleUniteStockBO-' + idD).val());
        }
        if ($('#prixuniteStockBO-' + idD).is(":checked")) {
            $("#prixuniteStockFB").val($('#prixuniteStockBO-' + idD).val());
        }
        if ($('#prixBO-' + idD).is(":checked")) {
            $("#prixFB").val($('#prixBO-' + idD).val());
        }
        if ($('#prixAchatBO-' + idD).is(":checked")) {
            $("#prixAchatFB").val($('#prixAchatBO-' + idD).val());
        }
        if ($('#codeBarreDesignationBO-' + idD).is(":checked")) {
            $("#codeBarreDesignationFB").val($('#codeBarreDesignationBO-' + idD).val());
        }
      }
      ,
    });
    $('#fuB').modal('show');
}
/****************************DEBUT CHEKBOX POUR FUSION*********************************/
$(function(){

    $(document).on('click','input:checkbox[name=Bout]',function(){
      console.log('cvvvvvvvvvvvvv');
         var valeur=$(this).val();
         var test = $(this).attr('id');
         tab=test.split('-');
         id=tab[0];
         if($(this). is(":checked")){
              console.log("Checkbox is checked."+id );
              if (id=='designationBO') {
                  $("#designationFB").val(valeur);
              }else if (id=='categorieBO') {
                  $("#categorieFB").val(valeur);
              }else if (id=='nbreArticleUniteStockBO') {
                  $("#nbreArticleUniteStockFB").val(valeur);
              }else if (id=='uniteStockBO') {
                  $("#uniteStockFB").val(valeur);
              }else if (id=='prixBO') {
                  $("#prixFB").val(valeur);
              }else if (id=='prixuniteStockBO') {
                  $("#prixuniteStockFB").val(valeur);
              }else if (id=='codeBarreDesignationBO') {
                    $("#codeBarreDesignationFB").val(valeur);
              }
          }
          else if($(this). is(":not(:checked)")){
              console.log("Checkbox is unchecked." );
          }
        console.log('cliiiiiiiiiiiiiiiiiiii');
    });

    $('#btnFusionB').click(function() {

      //$("#commentFormB").validate();
    });
});
function fusioner_B() {
    var id=$("#idFB").val();
    var idCT=$("#idCTFB").val();
    var categorie=$("#categorieFB").val();
    var uniteStock=$("#uniteStockFB").val();
    var nbreArticleUniteStock=$("#nbreArticleUniteStockFB").val();
    var prixuniteStock=$("#prixuniteStockFB").val();
    var prix=$("#prixFB").val();
    var prixAchat=$("#prixAchatFB").val();
    var codeBarreDesignation=$("#codeBarreDesignationFB").val();

   if (categorie.length!==0 && uniteStock.length!==0 && nbreArticleUniteStock!==0 && prixuniteStock!==0 && prix!==0 && prixAchat!==0  ) {
        console.log('gggggggggggBOUTT idCT='+idCT);
        //alert('AAA');
        $.ajax({
            url:"ajax/fusioner_B_PH_Ajax.php",
            method:"POST",
            data:{
                tache : 'fusionerB',
                id : id,
                idCT : idCT,
                categorie : categorie,
                uniteStock : uniteStock,
                nbreArticleUniteStock : nbreArticleUniteStock,
                prixuniteStock : prixuniteStock,
                prix : prix,
                prixAchat : prixAchat,
                codeBarreDesignation : codeBarreDesignation,
            },
            success: function (data) {
                
            //alert('CC'+data);
            $('#fuB').modal('hide');
            //$('#categorie-' + id).prop('disabled', true);
            $('#btnFusB-'+id).hide();
            
            },
            error: function() {
                alert("La requête 3"); },
            dataType:"text"
        });
   }else {
     alert('Une ou plusieurs des colones sont vides');
   }
   
}
/****************************FIN CHEKBOX POUR FUSION*********************************/

/**Fin Fusion Designation catalogueTotal**/

/*************** FIN modification catalogue details boutique***************/
/*************************************************************************** */
/****************************************************************************/

/*************************************************************************** */
/****************************************************************************/
/*************** Début modification categorie details boutique***************/
$(document).ready(function() {

    nbEntreeBC = $('#nbEntreeBC').val()
    id = $('#idCDCat').val();

    $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"operation":1,"id":id,"nbEntreeBC":nbEntreeBC,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsProductsBC").on( "click", ".pagination a", function (e){
    // $("#resultsProductsBC").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)
        
        id = $('#idCDCat').val();
        nbEntreeBC = $('#nbEntreeBC').val()
        query = $('#searchInputBC').val();

        if (query.length == 0) {
            $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeBC":nbEntreeBC,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeBC":nbEntreeBC,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
        // $("#resultsProductsBC").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
    });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function() {

    $('#searchInputBC').on("keyup", function(e) {
        e.preventDefault();
        
        query = $('#searchInputBC').val()
        nbEntreeBC = $('#nbEntreeBC').val()

        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            // alert(1111)
            t = 1; // code barre
            
            if (query.length > 0) {
                
                $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"operation":3,"id":id,"nbEntreeBC":nbEntreeBC,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"operation":3,"id":id,"nbEntreeBC":nbEntreeBC,"query":"","cb":t}); //load initial records
            }
        }else{
            // alert(2222)
            t = 0; // no code barre
            setTimeout(() => {
                if (query.length > 0) {
                    
                    $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"operation":3,"id":id,"nbEntreeBC":nbEntreeBC,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"operation":3,"id":id,"nbEntreeBC":nbEntreeBC,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreeBC').on("change", function(e) {
        e.preventDefault();

        nbEntreeBC = $('#nbEntreeBC').val()
        query = $('#searchInputBC').val();

        if (query.length == 0) {
            $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"operation":4,"id":id,"nbEntreeBC":nbEntreeBC,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsProductsBC" ).load( "ajax/listerCategoriesAjax.php",{"operation":4,"id":id,"nbEntreeBC":nbEntreeBC,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsProductsBC" ).load( "ajax/listerProduitAjax.php",{"operation":4,"nbEntreeBC":nbEntreeBC}); //load initial records
    });

});

/**Debut select categorie Details**/
function selectCategorieBD(id,idCataT){
    $.ajax({
        url:"ajax/categorieSqlAjax.php",
        method:"POST",
        data:{
            action:'listeSelectCategorie',
            id : idCataT,
        },
        success: function (data) {
            // tab=data.split('<>');
            $('#categorieD-'+id).empty();
            var data = JSON.parse(data);
            var len = data.length;
            console.log(len);

                for( var i = 0; i<len; i++){
                    var name = data[i];
                    $('#categorieD-'+id).append("<option value='"+name+"'>"+name+"</option>");
                     console.log('='+data[i]);

                }
            // $.each(obj,function(value)
            //       {
            //           $('#formeD-'+id).append('<option value=' + value + '>' + value + '</option>');
            //       });

        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
}
/**Fin select categorie Details **/
/**Debut select categorie par click**/
    function selectCategorieBC(id,idCataT){
        $.ajax({
            url:"ajax/categorieSqlAjax.php",
            method:"POST",
            data:{
                action:'listeSelectCategorie',
                id : idCataT,
            },
            success: function (data) {
                // tab=data.split('<>');
                $('#categorieP-'+id).empty();
                var data = JSON.parse(data);
                var len = data.length;
                //console.log(len);
    
                    for( var i = 0; i<len; i++){
                        var name = data[i];
                        $('#categorieP-'+id).append("<option value='"+name+"'>"+name+"</option>");
                         //console.log('='+data[i]);
    
                    }
            },
            error: function() {
                alert("La requête 3"); },
            dataType:"text"
        });
    }
/**Fin select categorie par click **/
/**Debut Modifier categorie Boutique */
function mdf_CategorieB(idCategorie,idCataT,ordre){
    var categorieD = $('#categorieD-' + idCategorie).val();
    var categorieP = $('#categorieP-' + idCategorie).val();
   
    $.ajax({
        url: "ajax/categorieSqlAjax.php",
        method: "POST",
        data: {
            operation: 'EditCategorie_B',
            idCategorie : idCategorie,
            id : idCataT,
            categorieD : categorieD,
            categorieP : categorieP
        },
        success: function(data) {
            tab = data.split('<>');
            if (tab[0] == 1) {
                $('#pencilmoD-'+ idCategorie).remove();
                $('#categorieP-' + idCategorie).prop('disabled', true);
                $('#categorieD-' + idCategorie).prop('disabled', true);
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
/**Fin Modifier categorie **/

/**Debut Supprimer Categorie */
function spm_CategorieB(idCategorie,id,ordre){
    $.ajax({
        url:"ajax/categorieSqlAjax.php",
        method:"POST",
        data:{
            operation:'SearchCategorie_B',
            idCategorie : idCategorie,
            id : id,
        },
        success: function (data) {
            result=data.split('<>');
            $('#ordre_SpmCateg').val(ordre);
                $('#id_SpmCatetyp').val(id);
                $('#idCategorie_Spm').val(idCategorie);
            console.log(result[1]);
            $('#categorie_SpmB').text(result[1]);
            $('#categorieP_SpmB').text(result[2]);
            $('#supprimerCategorie').modal('show');
        },
        error: function() {
            alert("La requête "); },
        dataType:"text"
    });
}
$(function(){
    $("#btnSupprimerCategorie").click( function(){
        
        console.log("AAA111");
        var ordre=$('#ordre_SpmCateg').val();
        var id=$('#id_SpmCatetyp').val();
        var idCategorie=$('#idCategorie_Spm').val();
        var categorieRempl=$('#categorieRempl').val();
        $.ajax({
            url:"ajax/categorieSqlAjax.php",
            method:"POST",
            data:{
                operation: 'DeleteCategorie_B',
                idCategorie : idCategorie,
                id : id,
                categorieRempl : categorieRempl,
            },
            success: function (data) {
                console.log("BBBB111");
                result=data.split('<>');
                if(result[0]==1){
                    $('#supprimerCategorie').modal('hide');
                    var table = $('#tableCategorie').DataTable();
                    nv_ordre=ordre - 1;
                    var ligne = table.row(nv_ordre).data();
                    ligne[0] = result[1]; ligne[1] = result[2]; ligne[2] = 'En cours';
                    $('#tableCategorie').dataTable().fnUpdate(ligne,nv_ordre,undefined,false);
                    $( table.row(nv_ordre).nodes() ).css({ 'background-color' : 'red'});
                }
            },
            error: function() {
                alert("La requête "); },
            dataType:"text"
        });
    });
});
/**Fin Supprimer Produit */

/**Debut Uploader Image New categorie**/
function imgN_CategorieC (idCategorie,id){
    $.ajax({
        url:"ajax/categorieSqlAjax.php",
        method:"POST",
        data:{
            operation:'image',
            id : id,
            idCategorie : idCategorie,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#id_Upd_NvCateg').val(tab[0]);
            $('#idCategN').val(idCategorie);
            $('#imageNCategorieC').modal('show');
        },
        error: function() {
            alert("La requête 2"); },
        dataType:"text"
    });
}
/**Fin Uploader Image New categorie**/

/**Debut Uploader Image Existante categorie**/
function imgE_CategorieC (idCategorie,id,i){
    
    //alert("tab[6]");
    console.log("jjjjjjjjj");
    $.ajax({
        url:"ajax/categorieSqlAjax.php",
        method:"POST",
        data:{
            operation:'image',
            id : id,
            idCategorie : idCategorie,
        },
        success: function (data) {
            tab=data.split('<>');
            $('#idCatalog').val(id);
            $('#idCategorie').val(idCategorie);
            $('#img_Upd_ExCategorie').val(tab[2]);
            $("#imgsrc_UpdCategorie").attr("src",'uploads/categorie/'+tab[2]);
            $('#imageECategorieC').modal('show');
        },
        error: function() {
            alert("La requête 1 "); },
        dataType:"text"
    });
}
/**Fin Uploader Image Existante categorie**/


/*************** FIN modification catalogue details boutique***************/
/*************************************************************************** */
/****************************************************************************/

//////////////////////////////////////////////////////////////////////////////
///////////////////////////////DEBUT PHARMACIE ////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////


/*************** Début modification Catalogue  details Pharmacie***************/
$(document).ready(function() {

    nbEntreePh = $('#nbEntreePhD').val();
    id = $('#idCDPH').val();

    $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":1,"id":id,"nbEntreePh":nbEntreePh,"query":"","cb":""}); //load initial records
    
    //executes code below when user click on pagination links
    $("#resultsProductsP").on( "click", ".pagination a", function (e){
    // $("#resultsProductsP").on( "click", function (e){
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        // page = page+1; //get page number from link
        page = $(this).attr("data-page"); //get page number from link
        //  alert(page)n
        nbEntreePh = $('#nbEntreePhD').val()
        query = $('#searchInputPhD').val();

        if (query.length == 0) {
            $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreePh":nbEntreePh,"query":"","cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
                
        }else{
            $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreePh":nbEntreePh,"query":query,"cb":""}, function(){ //get content from PHP page
                $(".loading-div").hide(); //once done, hide loading element
            });
        }
        // $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
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

                $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":3,"id":id,"nbEntreePh":nbEntreePh,"query":query,"cb":t}); //load initial records
            }else{
                $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":3,"id":id,"nbEntreePh":nbEntreePh,"query":"","cb":t}); //load initial records
            }     
        }else{
            t = 0; // no code barre
            setTimeout(() => {
                
                if (query.length > 0) {
                    // alert(2222)                
                    $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":3,"id":id,"nbEntreePh":nbEntreePh,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":3,"id":id,"nbEntreePh":nbEntreePh,"query":"","cb":t}); //load initial records
                }
            }, 100);
        }
    });
    
    $('#nbEntreePh').on("change", function(e) {
        e.preventDefault();

        nbEntreePh = $('#nbEntreePhD').val()
        query = $('#searchInputPhD').val();

        if (query.length == 0) {
            $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":4,"id":id,"nbEntreePh":nbEntreePh,"query":"","cb":""}); //load initial records
                
        }else{
            $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":4,"id":id,"nbEntreePh":nbEntreePh,"query":query,"cb":""}); //load initial records
        }
            
        // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
    });

});

/*************    TRI    ******************/
$(document).ready(function() {
    // alert(1500)
    // var page = 0;
    //executes code below when user click on pagination links
    //1=croissant et 0=decroissant
    triPH = 0;
    $(document).on("click", "#resultsProductsP th", function(e) {
        e.preventDefault();
    // alert(12)
        query = $('#searchInputPhD').val();
        nbEntreePh = $('#nbEntreePhD').val()
        // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":1, "query":""}); //load initial records
        
        if (triPH == 1) {
            // alert(triPH)
            if (query.length == 0) {
                $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":2,"id":id,"nbEntreePh":nbEntreePh,"tri":1, "query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsP" ).load( "ajax/listerCataloguePhAjax.php",{"operation":2,"id":id,"nbEntreePh":nbEntreePh,"tri":1, "query":query,"cb":""}); //load initial records
            }

            triPH = 0;
            // alert(triPH)
            
        } else {
            // alert(triPH)
            if (query.length == 0) {
                $("#resultsProductsP").load( "ajax/listerCataloguePhAjax.php",{"operation":2,"id":id,"nbEntreePh":nbEntreePh,"tri":0, "query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsP").load( "ajax/listerCataloguePhAjax.php",{"operation":2,"id":id,"nbEntreePh":nbEntreePh,"tri":0, "query":query,"cb":""}); //load initial records
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
        url: "ajax/catalogueSqlAjax.php",
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
        url:"ajax/catalogueSqlAjax.php",
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
            url:"ajax/catalogueSqlAjax.php",
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

        $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"operation":1,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":""}); //load initial records
        
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
                $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":query,"cb":""}, function(){ //get content from PHP page
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
                    
                    $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"operation":3,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"operation":3,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":t}); //load initial records
                }
            }else{
                // alert(2222)
                t = 0; // no code barre
                setTimeout(() => {
                    if (query.length > 0) {
                        
                        $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"operation":3,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"operation":3,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreeCatPh').on("change", function(e) {
            e.preventDefault();

            nbEntreeCatPh = $('#nbEntreeCatPh').val()
            query = $('#searchInputCatPh').val();

            if (query.length == 0) {
                $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"operation":4,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsCatPh" ).load( "ajax/listerCategoriesPhAjax.php",{"operation":4,"id":id,"nbEntreeCatPh":nbEntreeCatPh,"query":query,"cb":""}); //load initial records
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

        $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"operation":1,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":""}); //load initial records
        
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
                $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":query,"cb":""}, function(){ //get content from PHP page
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
                    
                    $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"operation":3,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"operation":3,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":t}); //load initial records
                }
            }else{
                // alert(2222)
                t = 0; // no code barre
                setTimeout(() => {
                    if (query.length > 0) {
                        
                        $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"operation":3,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"operation":3,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreeFoPh').on("change", function(e) {
            e.preventDefault();

            nbEntreeFoPh = $('#nbEntreeFoPh').val()
            query = $('#searchInputFoPhD').val();

            if (query.length == 0) {
                $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"operation":4,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsFoPh" ).load( "ajax/listerFormePhAjax.php",{"operation":4,"id":id,"nbEntreeFoPh":nbEntreeFoPh,"query":query,"cb":""}); //load initial records
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
        url: "ajax/formeSqlAjax.php",
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
        url:"ajax/formeSqlAjax.php",
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
            url:"ajax/formeSqlAjax.php",
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

/** DOUBLONS */
function newTabDoubClickPH() {
    /*************** Début modification catalogue Doublons PH***************/
    $(document).ready(function() {

        nbEntreePhD = $('#nbEntreePhD').val();
        id = $('#idCDD').val();

        $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":1,"id":id,"nbEntreePhD":nbEntreePhD,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsPD").on( "click", ".pagination a", function (e){
        // $("#resultsProductsP").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)n
            nbEntreePhD = $('#nbEntreePhD').val()
            query = $('#searchInputPhD').val();

            if (query.length == 0) {
                $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreePhD":nbEntreePhD,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreePhD":nbEntreePhD,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
        });
    });

    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {
        // alert(4)
        $('#searchInputPhD').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputPhD').val()
            nbEntreePhD = $('#nbEntreePhD').val()

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                t = 1; // code barre
                // inputVal = $('#searchInputPhD').val();
                // if ($.isNumeric(inputVal)) {
                if (query.length > 0) { 

                    $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":3,"id":id,"nbEntreePhD":nbEntreePhD,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":3,"id":id,"nbEntreePhD":nbEntreePhD,"query":"","cb":t}); //load initial records
                }     
            }else{
                t = 0; // no code barre
                setTimeout(() => {
                    
                    if (query.length > 0) {
                        // alert(2222)                
                        $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":3,"id":id,"nbEntreePhD":nbEntreePhD,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":3,"id":id,"nbEntreePhD":nbEntreePhD,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreePhD').on("change", function(e) {
            e.preventDefault();

            nbEntreePhD = $('#nbEntreePhD').val()
            query = $('#searchInputPhD').val();

            if (query.length == 0) {
                $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":4,"id":id,"nbEntreePhD":nbEntreePhD,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":4,"id":id,"nbEntreePhD":nbEntreePhD,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePhD":nbEntreePhD}); //load initial records
        });

    });

    /*************    TRI    ******************/
    $(document).ready(function() {
        // alert(1500)
        // var page = 0;
        //executes code below when user click on pagination links
        //1=croissant et 0=decroissant
        triPH = 0;
        $(document).on("click", "#resultsProductsPD th", function(e) {
            e.preventDefault();
        // alert(12)
            query = $('#searchInputPhD').val();
            nbEntreePhD = $('#nbEntreePhD').val()
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePhD":nbEntreePhD,"tri":1, "query":""}); //load initial records
            
            if (triPH == 1) {
                // alert(triPH)
                if (query.length == 0) {
                    $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":2,"id":id,"nbEntreePhD":nbEntreePhD,"tri":1, "query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsPD" ).load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":2,"id":id,"nbEntreePhD":nbEntreePhD,"tri":1, "query":query,"cb":""}); //load initial records
                }

                triPH = 0;
                // alert(triPH)
                
            } else {
                // alert(triPH)
                if (query.length == 0) {
                    $("#resultsProductsPD").load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":2,"id":id,"nbEntreePhD":nbEntreePhD,"tri":0, "query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsPD").load( "ajax/listerCatalogueDoublonPhAjax.php",{"operation":2,"id":id,"nbEntreePhD":nbEntreePhD,"tri":0, "query":query,"cb":""}); //load initial records
                }
                
                triPH = 1;
                // alert(triPH)
            }
        });
    });

    /*************** Fin modification Catalogue Doublons PH***************/   
}

/**Debut Doublon Designation catalogueTotal**/
function dbl_Designation_PH(idD,idCT,ordre){
    //alert(id);
    /*****************DataTable debut************************/
    $("#idDo").val(idD);
    $("#idCT").val(idCT);
    $("#ordreTableDo").val(ordre);
    if ($.fn.DataTable.isDataTable('#tableDo')) {
          $('#tableDo').DataTable().destroy();
      }
    console.log(idD+" "+idCT+" "+ordre);
    $('#popD').modal('show');
    $("#tableDo").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/listeProduit-DoublonPHAjax.php?idD="+idD+"&idCT="+idCT+"",
    "aoColumns": [
      { mData: "0" },
      { mData: "1" },
      { mData: "2" },
      { mData: "3" },
      { mData: "4" },
      { mData: "5" },
      { mData: "6" }
      ],
    });
   /******************DataTable Fin**************************/
    /*$.ajax({
        url:"ajax/listeProduit-DoublonPHAjax.php",
        method:"GET",
        data:{
            operation:1,
            id : idD,
            idCT : idCT,
        },
        success: function (data) {
          // tab = data.split('+');
          // tabIdFusionDoub=tab[0].join('-');
          // tabDoublon=tab[1].join('-');
          //console.log(tab[0]);
          console.log(data);
          alert(data);
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });*/
}
/**Fin Doublon Designation catalogueTotal**/

/**Debut eliminer Doublon  catalogueTotal**/
function eli_Doub_PH(){

    var id=$("#idDo").val();
    var idCT=$("#idCT").val();
    var ordre=$("#ordreTableDo").val();
    console.log('ordre'+ordre);
    console.log('gggggggggggFFFFFFF idCT='+idCT);
    $.ajax({
        url:"ajax/listeProduit-DoublonPHAjax.php",
        method:"GET",
        data:{
            operation:1,
            tache : 'eliminerDoub',
            id : id,
            idCT : idCT,
        },
        success: function (data) {
          // tab = data.split('+');
          // tabIdFusionDoub=tab[0].join('-');
          // tabDoublon=tab[1].join('-');
          //console.log(tab[0]);
          console.log('ffffffffffffff'+data);
           $('#categorie-' + id).prop('disabled', true);
          var table = $('#tableDo').DataTable();
               var ligne = table.row(ordre).data();
               ligne[7] = 'En cours';
               $('#tableDo').dataTable().fnUpdate(ligne,ordre,undefined,false);
              $( table.row(ordre).nodes() ).css({ 'background-color' : 'yellow'});

           //$('#designationD-' + idDesignation).prop('disabled', true);
           $('#popD').modal('hide');
          
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
}
/**Fin eliminer Doublon  catalogueTotal**/


/**Debut eliminer tous Doublon  catalogueTotal**/
function eli_tousDoub_PH(idCT){
    //alert(idCT);
    console.log('eliminer tous'+idCT);
    $.ajax({
        url:"ajax/listeProduit-DoublonPHAjax.php",
        method:"GET",
        data:{
            operation:1,
            tache : 'eliminerTDoub',
            idCT : idCT,
        },
        success: function (data) {
          // tab = data.split('+');
          // tabIdFusionDoub=tab[0].join('-');
          // tabDoublon=tab[1].join('-');
          //console.log(tab[0]);
          console.log('ffffffffffffff'+data);
           $('#elToutDoub').modal('hide');
          
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
}
/**Fin eliminer tous Doublon  catalogueTotal**/
/** FUSION PH */
function newTabFusClickPH() {
    /*************** Début modification catalogue Fusion PH***************/
    $(document).ready(function() {

        nbEntreePhF = $('#nbEntreePhF').val();
        id = $('#idCDF').val();

        $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":1,"id":id,"nbEntreePhF":nbEntreePhF,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsPF").on( "click", ".pagination a", function (e){
        // $("#resultsProductsP").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)n
            nbEntreePhF = $('#nbEntreePhF').val()
            query = $('#searchInputPhF').val();

            if (query.length == 0) {
                $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreePhF":nbEntreePhF,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreePhF":nbEntreePhF,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
        });
    });

    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {
        // alert(4)
        $('#searchInputPhF').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputPhF').val();
            nbEntreePhF = $('#nbEntreePhF').val();            
             id = $('#idCDF').val();

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                t = 1; // code barre
                // inputVal = $('#searchInputPhF').val();
                // if ($.isNumeric(inputVal)) {
                if (query.length > 0) { 

                    $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":3,"id":id,"nbEntreePhF":nbEntreePhF,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":3,"id":id,"nbEntreePhF":nbEntreePhF,"query":"","cb":t}); //load initial records
                }     
            }else{
                t = 0; // no code barre
                setTimeout(() => {
                    
                    if (query.length > 0) {
                        // alert(2222)                
                        $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":3,"id":id,"nbEntreePhF":nbEntreePhF,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":3,"id":id,"nbEntreePhF":nbEntreePhF,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreePhF').on("change", function(e) {
            e.preventDefault();

            nbEntreePhF = $('#nbEntreePhF').val()
            query = $('#searchInputPhF').val();
            id = $('#idCDF').val();

            if (query.length == 0) {
                $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":4,"id":id,"nbEntreePhF":nbEntreePhF,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":4,"id":id,"nbEntreePhF":nbEntreePhF,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePhF":nbEntreePhF}); //load initial records
        });

    });

    /*************    TRI    ******************/
    $(document).ready(function() {
        // alert(1500)
        // var page = 0;
        //executes code below when user click on pagination links
        //1=croissant et 0=decroissant
        triPH = 0;
        $(document).on("click", "#resultsProductsPF th", function(e) {
            e.preventDefault();
        // alert(12)
            query = $('#searchInputPhF').val();
            nbEntreePhF = $('#nbEntreePhF').val()
            // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePhF":nbEntreePhF,"tri":1, "query":""}); //load initial records
            
            if (triPH == 1) {
                // alert(triPH)
                if (query.length == 0) {
                    $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":2,"id":id,"nbEntreePhF":nbEntreePhF,"tri":1, "query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsPF" ).load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":2,"id":id,"nbEntreePhF":nbEntreePhF,"tri":1, "query":query,"cb":""}); //load initial records
                }

                triPH = 0;
                // alert(triPH)
                
            } else {
                // alert(triPH)
                if (query.length == 0) {
                    $("#resultsProductsPF").load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":2,"id":id,"nbEntreePhF":nbEntreePhF,"tri":0, "query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsPF").load( "ajax/listerCatalogueFusionPhAjax.php",{"operation":2,"id":id,"nbEntreePhF":nbEntreePhF,"tri":0, "query":query,"cb":""}); //load initial records
                }
                
                triPH = 1;
                // alert(triPH)
            }
        });
    });

    /*************** Fin modification Catalogue Doublons PH***************/   
}
/**Debut FUSION Designation catalogueTotal**/
function fus_Designation_PH(idD,idCT,ordre){
    //alert(id);
    $("#categoriePH").val('');
    $("#formPH").val('');
    $("#tableauPH").val('');
    $("#prixSessionPH").val(0);
    $("#prixPublicPH").val(0);
    $("#codeBarreDesignationPH").val('');
    /*****************DataTable debut************************/
    $("#idF").val(idD);
    $("#idCTF").val(idCT);
    if ($.fn.DataTable.isDataTable('#tableFu')) {
          $('#tableFu').DataTable().destroy();
      }
    console.log(idD+" "+idCT+" "+ordre);
    $("#tableFu").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/listeProduit-FusionPHAjax.php?idD="+idD+"&idCT="+idCT+"",
    "aoColumns": [
      { mData: "0" },
      { mData: "1" },
      { mData: "2" },
      { mData: "3" },
      { mData: "4" },
      { mData: "5" },
      { mData: "6" }
      ],"initComplete": function(settings, json) {
        idD=$("#idF").val();
        //alert('prixSession-' +idD);
        var ps='#prixSession-'+idD+"";
        
        //var ps=document.getElementById('prixSession-50271');
        //alert('la prixSession '+$('#categorie-' + idD).val());
        if ($('#categorie-' + idD).is(":checked")) {
            $("#categoriePH").val($('#categorie-' + idD).val());
            //alert('la gategorie '+$('#categorie-' + idD).val());
        }
        if ($('#forme-' + idD).is(":checked")) {
            $("#formPH").val($('#forme-' + idD).val());
        }
        if ($('#tableau-' + idD).is(":checked")) {
            $("#tableauPH").val($('#tableau-' + idD).val());
        }
        if ($('#prixSession-' + idD).is(":checked")) {
            $("#prixSessionPH").val($('#prixSession-' + idD).val());
        }
        if ($('#prixPublic-' + idD).is(":checked")) {
            $("#prixPublicPH").val($('#prixPublic-' + idD).val());
        }
        if ($('#codeBarreDesignation-' + idD).is(":checked")) {
            $("#codeBarreDesignationPH").val($('#codeBarreDesignation-' + idD).val());
        }
      }
      ,
    });
    $('#fu').modal('show');
   /******************DataTable Fin**************************/
  
   
}
/**Fin FUSION Designation catalogueTotal**/

 /****************************DEBUT CHEKBOX POUR FUSION*********************************/
 $(function(){
   
    $(document).on('click','input:checkbox[name=ph]',function(){
      console.log('cvvvvvvvvvvvvv');
         var valeur=$(this).val();
         var test = $(this).attr('id');
         tab=test.split('-');
         id=tab[0];
         if($(this). is(":checked")){
              console.log("Checkbox is checked."+id );
              if (id=='designation') {
                  $("#designationPH").val(valeur);
              }else if (id=='categorie') {
                  $("#categoriePH").val(valeur);
              }else if (id=='forme') {
                  $("#formPH").val(valeur);
              }else if (id=='tableau') {
                  $("#tableauPH").val(valeur);
              }else if (id=='prixSession') {
                  $("#prixSessionPH").val(valeur);
              }else if (id=='prixPublic') {
                  $("#prixPublicPH").val(valeur);
              }else if (id=='codeBarreDesignation') {
                    $("#codeBarreDesignationPH").val(valeur);
              }
          }
          else if($(this). is(":not(:checked)")){
              console.log("Checkbox is unchecked." );
          }
        console.log('cliiiiiiiiiiiiiiiiiiii');
    });

    $('#btnFusion').click(function() {

      //$("#commentForm").validate();
    });
});
/****************************FIN CHEKBOX POUR FUSION*********************************/
function fusioner_PH() {
    var id=$("#idF").val();
    var idCT=$("#idCTF").val();
    var categorie=$("#categoriePH").val();
    var forme=$("#formPH").val();
    var tableau=$("#tableauPH").val();
    var prixSession=$("#prixSessionPH").val();
    var prixPublic=$("#prixPublicPH").val();
    var codeBarreDesignation=$("#codeBarreDesignationPH").val();
if (categorie.length!==0 && forme.length!==0 && tableau.length!==0 && prixSession!==0 && prixPublic!==0 ) {
    console.log('gggggggggggFFFFFFF idCT='+idCT);
    //alert('AAA');
    $.ajax({
        url:"ajax/fusioner_B_PH_Ajax.php",
        method:"POST",
        data:{
            tache : 'fusionerPH',
            id : id,
            idCT : idCT,
            categorie : categorie,
            forme : forme,
            tableau : tableau,
            prixSession : prixSession,
            prixPublic : prixPublic,
            codeBarreDesignation : codeBarreDesignation,
        },
        success: function (data) {
            
          //  alert('CC'+data);
           $('#fu').modal('hide');
           //$('#categorie-' + id).prop('disabled', true);
           $('#btnFusPh-'+id).hide();
           
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
} else {
    alert('Une ou plusieurs des colones sont vides');
}
   
   
}
/** FUSION PH2 */
function newTabFusClickPH2() {
    /*************** Début modification catalogue Fusion PH***************/
    $(document).ready(function() {

        nbEntreePhF2 = $('#nbEntreePhF2').val();
        id = $('#idCDF2').val();

        $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":1,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":"","cb":""}); //load initial records
        
        //executes code below when user click on pagination links
        $("#resultsProductsPF2").on( "click", ".pagination a", function (e){
        // $("#resultsProductsPF").on( "click", function (e){
            e.preventDefault();
            $(".loading-div").show(); //show loading element
            // page = page+1; //get page number from link
            page = $(this).attr("data-page"); //get page number from link
            //  alert(page)n
            nbEntreePhF2 = $('#nbEntreePhF2').val()
            query = $('#searchInputPhF2').val();

            if (query.length == 0) {
                $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":"","cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
                    
            }else{
                $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"page":page,"operation":1,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":query,"cb":""}, function(){ //get content from PHP page
                    $(".loading-div").hide(); //once done, hide loading element
                });
            }
            // $("#resultsProductsPF2").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
        });
    });

    /********   RECHERCHE et NOMBRE D'ENTREES   ******/
    $(document).ready(function() {
        // alert(4)
        $('#searchInputPhF2').on("keyup", function(e) {
            e.preventDefault();
            
            query = $('#searchInputPhF2').val()
            nbEntreePhF2 = $('#nbEntreePhF2').val();
            id = $('#idCDF2').val();

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                t = 1; // code barre
                // inputVal = $('#searchInputPhF2').val();
                // if ($.isNumeric(inputVal)) {
                if (query.length > 0) { 

                    $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":3,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":query,"cb":t}); //load initial records
                }else{
                    $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":3,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":"","cb":t}); //load initial records
                }     
            }else{
                t = 0; // no code barre
                setTimeout(() => {
                    
                    if (query.length > 0) {
                        // alert(2222)                
                        $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":3,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":query,"cb":t}); //load initial records
                    }else{
                        $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":3,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":"","cb":t}); //load initial records
                    }
                }, 100);
            }
        });
        
        $('#nbEntreePhF2').on("change", function(e) {
            e.preventDefault();

            nbEntreePhF2 = $('#nbEntreePhF2').val()
            query = $('#searchInputPhF2').val();

            if (query.length == 0) {
                $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":4,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":"","cb":""}); //load initial records
                    
            }else{
                $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":4,"id":id,"nbEntreePhF2":nbEntreePhF2,"query":query,"cb":""}); //load initial records
            }
                
            // $("#resultsProductsPF2").load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePhF2":nbEntreePhF2}); //load initial records
        });

    });

    /*************    TRI    ******************/
    $(document).ready(function() {
        // alert(1500)
        // var page = 0;
        //executes code below when user click on pagination links
        //1=croissant et 0=decroissant
        triPH = 0;
        $(document).on("click", "#resultsProductsPF2 th", function(e) {
            e.preventDefault();
        // alert(12)
            query = $('#searchInputPhF2').val();
            nbEntreePhF2 = $('#nbEntreePhF2').val();
            
        id = $('#idCDF2').val();
            // $("#resultsProductsPF2" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePhF2":nbEntreePhF2,"tri":1, "query":""}); //load initial records
            
            if (triPH == 1) {
                // alert(triPH)
                if (query.length == 0) {
                    $("#resultsProductsPF2" ).load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":2,"id":id,"nbEntreePhF2":nbEntreePhF2,"tri":1, "query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsPF2" ).load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":2,"id":id,"nbEntreePhF2":nbEntreePhF2,"tri":1, "query":query,"cb":""}); //load initial records
                }

                triPH = 0;
                // alert(triPH)
                
            } else {
                // alert(triPH)
                if (query.length == 0) {
                    $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":2,"id":id,"nbEntreePhF2":nbEntreePhF2,"tri":0, "query":"","cb":""}); //load initial records
                        
                }else{
                    $("#resultsProductsPF2").load( "ajax/listerCatalogueFusion2PhAjax.php",{"operation":2,"id":id,"nbEntreePhF2":nbEntreePhF2,"tri":0, "query":query,"cb":""}); //load initial records
                }
                
                triPH = 1;
                // alert(triPH)
            }
        });
    });

    /*************** Fin modification Catalogue Doublons PH***************/   
}
/**Debut Doublon Designation catalogueTotal**/
function fus_Designation_PH2(idD,idCT,ordre){
    //alert(id);
    /*****************DataTable debut************************/
    $("#idF2").val(idD);
    $("#idCTF2").val(idCT);
    if ($.fn.DataTable.isDataTable('#tableFu2')) {
          $('#tableFu2').DataTable().destroy();
      }
    console.log(idD+" "+idCT+" "+ordre);
    $('#fu2').modal('show');
    $("#tableFu2").dataTable({
    "bProcessing": true,
    "sAjaxSource": "ajax/listeProduit-Fusion2PHAjax.php?idD="+idD+"&idCT="+idCT+"",
    "aoColumns": [
      { mData: "0" },
      { mData: "1" },
      { mData: "2" },
      { mData: "3" },
      { mData: "4" },
      { mData: "5" },
      { mData: "6" }
      ],"initComplete": function(settings, json) {
        idD=$("#idF2").val();
        //alert('prixSession-' +idD);
        var ps='#prixSession2-'+idD+"";
        
        //var ps=document.getElementById('prixSession-50271');
        //alert('la prixSession '+$('#categorie-' + idD).val());
        if ($('#categorie2-' + idD).is(":checked")) {
            $("#categoriePH2").val($('#categorie2-' + idD).val());
            //alert('la gategorie '+$('#categorie-' + idD).val());
        }
        if ($('#forme2-' + idD).is(":checked")) {
            $("#formPH2").val($('#forme2-' + idD).val());
        }
        if ($('#tableau2-' + idD).is(":checked")) {
            $("#tableauPH2").val($('#tableau2-' + idD).val());
        }
        if ($('#prixSession2-' + idD).is(":checked")) {
            $("#prixSessionPH2").val($('#prixSession2-' + idD).val());
        }
        if ($('#prixPublic2-' + idD).is(":checked")) {
            $("#prixPublicPH2").val($('#prixPublic2-' + idD).val());
        }
        if ($('#codeBarreDesignation2-' + idD).is(":checked")) {
            $("#codeBarreDesignationPH2").val($('#codeBarreDesignation2-' + idD).val());
        }
    },
    });
   /******************DataTable Fin**************************/
    
}
/**Fin Doublon Designation catalogueTotal**/


 /****************************DEBUT CHEKBOX POUR FUSION*********************************/
 $(function(){   
    $(document).on('click','input:checkbox[name=ph2]',function(){
      console.log('cvvvvvvvvvvvvv');
         var valeur=$(this).val();
         var test = $(this).attr('id');
         tab=test.split('-');
         id=tab[0];
         if($(this). is(":checked")){
              console.log("Checkbox is checked."+id );
              if (id=='designation2') {
                  $("#designationPH2").val(valeur);
              }else if (id=='categorie2') {
                  $("#categoriePH2").val(valeur);
              }else if (id=='forme2') {
                  $("#formPH2").val(valeur);
              }else if (id=='tableau2') {
                  $("#tableauPH2").val(valeur);
              }else if (id=='prixSession2') {
                  $("#prixSessionPH2").val(valeur);
              }else if (id=='prixPublic2') {
                  $("#prixPublicPH2").val(valeur);
              }else if (id=='codeBarreDesignation2') {
                    $("#codeBarreDesignationPH2").val(valeur);
              }
          }
          else if($(this). is(":not(:checked)")){
              console.log("Checkbox is unchecked." );
          }
        console.log('cliiiiiiiiiiiiiiiiiiii');
    });

    $('#btnFusion2').click(function() {

      //$("#commentForm").validate();
    });
});
/****************************FIN CHEKBOX POUR FUSION*********************************/
function fusioner_PH2() {
    var id=$("#idF2").val();
    var idCT=$("#idCTF2").val();
    var designation=$("#designation2").val();
    var categorie=$("#categoriePH2").val();
    var forme=$("#formPH2").val();
    var tableau=$("#tableauPH2").val();
    var prixSession=$("#prixSessionPH2").val();
    var prixPublic=$("#prixPublicPH2").val();
    var codeBarreDesignation=$("#codeBarreDesignationPH2").val();

   
    console.log('gggggggggggFFFFFFF idCT='+idCT);
    //alert('AAA');
    $.ajax({
        url:"ajax/fusioner_B_PH_Ajax.php",
        method:"POST",
        data:{
            tache : 'fusionerPH2',
            id : id,
            idCT : idCT,
            designation : designation,
            categorie : categorie,
            forme : forme,
            tableau : tableau,
            prixSession : prixSession,
            prixPublic : prixPublic,
            codeBarreDesignation : codeBarreDesignation,
        },
        success: function (data) {
            
          //  alert('CC'+data);
           $('#fu2').modal('hide');
           //$('#categorie-' + id).prop('disabled', true);
           $('#btnFusPh2-'+id).hide();
           
        },
        error: function() {
            alert("La requête 3"); },
        dataType:"text"
    });
}


