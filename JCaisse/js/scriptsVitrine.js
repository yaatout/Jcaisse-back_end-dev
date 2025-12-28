/************* Modification ***************/
/**Debut supprimer Designation Vitrine**/
/**Debut ajouter Vitrine**/

// alert(21111)

function ajt_vitrine(id) {
    // var designation=$('#designation-'+id).val();
    var categorie = $('#categorie-' + id).val();
    var design = $('#designation-' + id).val();
    var designJC = $('#designation-' + id).val();
    var uniteStock = $('#uniteStock-' + id).val();
    var prix = $('#prixUnitaire-' + id).val();
    var prixUS = $('#prixuniteStock-' + id).val();
    var codeBarreDesignat = $('#codeBarreDesignation-' + id).val();
    var codeBarreuniteSt = $('#codeBarreuniteStock-' + id).val();
    var idFus = $('#idFusion-' + id).val();
    //alert (design);
    $.ajax({
        url: "vitrine/ajax/ajouterVitrineAjax",
        method: "POST",
        data: {
            operation: 1,
            idDesignation: id,
            categorie: categorie,
            designation: design,
            designationJC: designJC,
            uniteStock: uniteStock,
            prix: prix,
            prixuniteStock: prixUS,
            codeBarreDesignation: codeBarreDesignat,
            codeBarreuniteStock: codeBarreuniteSt,
            idFusion: idFus,
        },
        success: function(data) {
            // alert(data)
            tab = data.split('+');
            var ligne = "<tr><td>0</td><td>" + tab[0] + "</td><td>" + tab[1] + "</td><td>" + tab[2] + "</td><td>" + tab[3] + "</td><td>" + tab[4] + "</td><td>En cours ...</td></tr>";
            $("table.tabVitrine").prepend(ligne);
            $('#uniteStock-' + id).prop('disabled', true);
            $('#prixUnitaire-' + id).prop('disabled', true);
            $('#prixuniteStock-' + id).prop('disabled', true);
            $('#init_Produit-' + id).prop('disabled', true);
            //console.log(data);
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}
/**Fin ajouter Vitrine**/

/**Debut ajouter Vitrine Pharma**/
function ajt_vitrinePH(id) {
    // var designation=$('#designation-'+id).val();
    var categorie = $('#categorie-' + id).val();
    var design = $('#designation-' + id).val();
    var designJC = $('#designation-' + id).val();
    var forme = $('#forme-' + id).val();
    var tableau = $('#tableau-' + id).val();
    var prixPublic = $('#prixPublic-' + id).val();
    var codeBarreDesignat = $('#codeBarreDesignation-' + id).val();
    var codeBarreuniteSt = $('#codeBarreuniteStock-' + id).val();
    var idFus = $('#idFusion-' + id).val();
    //console.log('data');
    //alert (design);
    $.ajax({
        url: "vitrine/ajax/ajouterVitrineAjax",
        method: "POST",
        data: {
            operation: 2,
            idDesignation: id,
            categorie: categorie,
            designation: design,
            designationJC: designJC,
            forme: forme,
            tableau: tableau,
            prixPublic: prixPublic,
            codeBarreDesignation: codeBarreDesignat,
            codeBarreuniteStock: codeBarreuniteSt,
            idFusion: idFus,
        },
        success: function(data) {
            tab = data.split('+');
            var ligne = "<tr><td>0</td><td>" + tab[0] + "</td><td>" + tab[1] + "</td><td>" + tab[2] + "</td><td>" + tab[3] + "</td><td>" + tab[4] + "</td><td>En cours ...</td></tr>";
            $("table.tabVitrine").prepend(ligne);
            $('#uniteStock-' + id).prop('disabled', true);
            $('#prixUnitaire-' + id).prop('disabled', true);
            $('#prixuniteStock-' + id).prop('disabled', true);
            $('#init_Produit-' + id).prop('disabled', true);
            //console.log(data);
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}
/**Fin ajouter Vitrine PHAr**/
function spm_DesignationVT(id) {
    //alert(id);
    $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
            operation: 1,
            id: id,
        },
        success: function(data) {
            tab = data.split('<>');
            $('#id_Spm').val(tab[0]);
            $('#designation_Spm').val(tab[1]);
            $('#designation_SpmE').val(tab[2]);
            $('#categorie_Spm').val(tab[3]);
            $('#categorieV_Spm').val(tab[4]);
            $('#uniteStock_Spm').val(tab[5]);
            $('#prixuniteStock_Spm').val(tab[6]);
            $('#prix_Spm').val(tab[7]);
            $('#img_Spm').val(tab[8]);
            $('#supprimerDesignation').modal('show');
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}
/**Fin supprimer Designation Vitrine**/
/**Debut modification produit Vitrine**/
function edit_DesignationVT(id) {
    //alert(id);
    $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
            operation: 1,
            id: id,
        },
        success: function(data) {
            tab = data.split('<>');
              // console.log(tab[4]+"vhgvyvj");
            $('#id_edit').val(tab[0]);
            $('#designation_edit').val(tab[1]);
            $('#designation_editE').val(tab[2]);
            $('#categorie_edit').val(tab[3]);
            // $('#categorieV_edit').val(tab[4]);
            $('#uniteStock_edit').val(tab[5]);
            $('#prixuniteStock_edit').val(tab[6]);
            $('#prix_edit').val(tab[7]);
            $('#image_edit').val(tab[8]);
            $('#modifierDesignation').modal('show');
            $("option[value='" + tab[4] + "']").attr('selected', 'selected');
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}
/**Fin modification produit Vitrine**/

/**Debut Uploader Image Nouvelle Designation Vitrine**/
function imgNV_DesignationVT(id) {
    $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
            operation: 1,
            id: id,
        },
        success: function(data) {
            tab = data.split('<>');
            $('#id_Upd_Nv').val(tab[0]);
            $('#idB_Upd_Ex').val(tab[9]);
            $('#imageNvDesignation').modal('show');
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}
/**Fin Uploader Image Nouvelle  Designation Vitrine**/

/**Debut Uploader Image Existante Designation Vitrine**/
function imgEX_DesignationVT(id) {
    $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
            operation: 1,
            id: id,
        },
        success: function(data) {
            tab = data.split('<>');
            $('#id_Upd_Ex').val(tab[0]);
            $('#des_Upd_Ex').val(tab[2]);
            $('#idB_Upd_Ex').val(tab[9]);
            $('#img_Upd_Ex').val(tab[8]);
            //$('#imgsrc_Upd').src='uploads/'+tab[6];
            $('#imgsrc_Upd').attr('src', "vitrine/uploads/" + tab[8]);
            $('#imgsrc_Upd').attr('alt', tab[8]);
            $('#imageExDesignation').modal('show');
        },
        error: function() {
            alert("La requête ");
        },
        dataType: "text"
    });
}

$(function() {
    $(".btnAjoutArticle, .btnEditerProduit").click(function() {
        var clicked = $(this)
        var id = clicked.attr('id');
        // alert(id)
        $(document).on("click", '.typeahead li a', function(e) {
            e.preventDefault();

            query = $(this).text();
            // alert(query)

            $.ajax({
                url: "vitrine/ajax/vitrineAjax",
                method: "POST",
                data: {
                    operation: 6,
                    designation: query
                },
                success: function(data) {
                    tab = data.split('<>');
                    // alert(data)
                    if ((tab[1] != 'Article') && (tab[1].toLowerCase() != 'article') && (tab[0] != '1')) {
                        // alert(data + "---113")
                        // $("#options").removeAttr('class')selectUnite
                        $(".selectUnite-" + id).html('');
                        var art = new Option("article", "article");
                        var us = new Option(tab[1], tab[1]);
                        /// jquerify the DOM object 'o' so we can use the html method
                        $(art).html('article');
                        $(us).html(tab[1]);
                        $(".selectUnite-" + id).append(art);
                        $(".selectUnite-" + id).append(us);
                    } else {
                        // alert(data + "---2")
                        $(".selectUnite-" + id).html('');
                        var art = new Option("article", "article");
                        /// jquerify the DOM object 'o' so we can use the html method
                        $(art).html("article");
                        $(".selectUnite-" + id).append(art);
                    }
                    // console.log(1)
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
        })
        $(".searchProduct-" + id).keyup(function(e) {
            e.preventDefault();

            var keycode = (e.keyCode ? e.keyCode : e.which);
            var element = $(".typeahead li.active").text();
            if (keycode == '13') {

                $.ajax({
                    url: "vitrine/ajax/vitrineAjax",
                    method: "POST",
                    data: {
                        operation: 6,
                        designation: element
                    },
                    success: function(data) {
                        tab = data.split('<>');
                        // alert(data)
                        if ((tab[1] != 'Article') && (tab[1].toLowerCase() != 'article') && (tab[0] != '1')) {
                            // alert(data + "---1")
                            // $("#options").removeAttr('class')selectUnite
                            $(".selectUnite-" + id).html('');
                            var art = new Option("article", "article");
                            var us = new Option(tab[1], tab[1]);
                            /// jquerify the DOM object 'o' so we can use the html method
                            $(art).html("article");
                            $(us).html(tab[1]);
                            $(".selectUnite-" + id).append(art);
                            $(".selectUnite-" + id).append(us);
                        } else {
                            // alert(data + "---2")
                            $(".selectUnite-" + id).html('');
                            var art = new Option("article", "article");
                            /// jquerify the DOM object 'o' so we can use the html method
                            $(art).html("article");
                            $(".selectUnite-" + id).append(art);
                        }
                        // console.log(1)
                    },
                    error: function() {
                        alert("La requête ");
                    },
                    dataType: "text"
                });
            }

        })
    })
    $(".btnRetournerProduit").click(function() {
        var clicked = $(this)
        var clicked_id = clicked.attr('id');
        var splitId = clicked_id.split("_");
        var idArticle = splitId[0];
        var idPanier = splitId[1];
        // alert(clicked_id)
        // var qtCommander = $('#quantite-' + idArticle).val();
        // alert(qtCommander)
        $(".btnConfRetourProduit-" + clicked_id).click(function() {
            // var qtRetourner = $('#qtRetourner-' + idArticle).val();
            // alert(qtRetourner)
            // if (qtCommander >= qtRetourner) {

            $.ajax({
                url: "vitrine/ajax/vitrineAjax",
                method: "POST",
                data: {
                    operation: 2,
                    idArticle: idArticle,
                    idPanier: idPanier
                },
                success: function(data) {
                    // tab = data.split('<>');
                    // alert(1)
                    // console.log(1)
                    window.location.href = "vitrine/commande"
                },
                error: function() {
                    alert("La requête ");
                },
                dataType: "text"
            });
            // } else {
            //     $('.text-error').removeClass('hidden')
            //     $('.text-error').text('Attention : Vous ne pouvez pas retirer plus de la quantité commander.');
            // }
        });
    });

    $(".btnAjoutArticle").click(function() {
        clicked = $(this)
        idPanier = clicked.attr('id');
        // alert(idPanier)
        $(".searchProduct-" + idPanier).keyup(function() {
            var query = $(".searchProduct-" + idPanier).val();
            // var typeSearchValue = $('#searchType option:selected').text();
            if (query.length > 0) {
                // alert(query)
                $(".searchProduct-" + idPanier).typeahead({
                    source: function(query, result) {
                        $.ajax({
                            url: "vitrine/ajax/vitrineAjax",
                            method: "POST",
                            data: {
                                operation: 3,
                                query: query
                            },
                            dataType: "json",
                            // data: $("#boutiqueForm").serialize(),
                            success: function(data) {
                                // alert(data)
                                  // alert('dfghsswws')
                                // var boutiques = data.split(",");
                                result($.map(data, function(item) {
                                    // console.log(item);
                                    return item;
                                }))
                            },
                            error: function() {
                                alert("La requête ss");
                            }
                        });
                    }
                });
                $(".searchProduct-" + idPanier).focus();
                //  else {
                //     $("#reponseS").html('');
            }
        });
    });
    $(".btnConfAjoutArticle").click(function() {
        clicked = $(this)
        idPanier = clicked.attr('id');
        ref = $('.searchProduct-' + idPanier).val();
        quantite = $('.quantite-' + idPanier).val();
        unite = $('.unite-' + idPanier).val();

        $.ajax({
            url: "vitrine/ajax/vitrineAjax",
            method: "POST",
            data: {
                operation: 7,
                ref: ref,
                quantite: quantite,
                unite: unite,
                idPanier: idPanier
            },
            success: function(data) {
                // tab = data.split('<>');
                // alert(data)
                // console.log(1)
                window.location.href = "vitrine/commande"
            },
            error: function() {
                alert("La requête ");
            },
            dataType: "text"
        });
    });

    $(".btnEditerProduit").on('click', function() {
        // e.preventDefault();
        // alert(1)
        idBtn = $(this).attr("id");
        splitId = idBtn.split('_');
        idArticle = splitId[0];
        idPanier = splitId[1];
        ust = $("#us-" + idArticle + "_" + idPanier).val();
        // alert(ust)

        $.ajax({
            url: "vitrine/ajax/vitrineAjax",
            method: "POST",
            data: {
                operation: 4,
                idA: idArticle,
                idP: idPanier
            },
            dataType: "json",
            // data: $("#boutiqueForm").serialize(),
            success: function(data) {
                ref = data.designation;
                unite = data.unite;
                quantite = data.quantite;
                idA = data.idArticle;
                idP = data.idPanier;
                idB = data.idBoutique;

                $(".searchProduct-" + idA + "_" + idP).val(ref);
                $(".oldRef-" + idA + "_" + idP).val(ref);
                $(".quantite-" + idA + "_" + idP).val(quantite);

                if ((ust != 'Article') && (ust != 'article')) {

                    $(".selectUnite-" + idA + "_" + idP).html('');
                    var art = new Option("article", "article");
                    var us = new Option(ust, ust);
                    /// jquerify the DOM object 'o' so we can use the html method
                    $(art).html("article");
                    $(us).html(ust);
                    $(".selectUnite-" + idA + "_" + idP).append(art);
                    $(".selectUnite-" + idA + "_" + idP).append(us);

                } else {
                    // alert(data + "---2")
                    $(".selectUnite-" + idA + "_" + idP).html('');
                    var art = new Option("article", "article");
                    /// jquerify the DOM object 'o' so we can use the html method
                    $(art).html("article");
                    $(".selectUnite-" + idA + "_" + idP).append(art);
                }

                $("option[value='" + unite + "']").attr('selected', 'selected');
            },
            error: function() {
                alert("La requête ddd");
            }
        });

        $(".searchProduct-" + idArticle + '_' + idPanier).keyup(function() {
            var query = $(".searchProduct-" + idArticle + '_' + idPanier).val();
            // var typeSearchValue = $('#searchType option:selected').text();
            if (query.length > 0) {
                // alert(query)
                $(".searchProduct-" + idArticle + '_' + idPanier).typeahead({
                    source: function(query, result) {
                        $.ajax({
                            url: "vitrine/ajax/vitrineAjax",
                            method: "POST",
                            data: {
                                operation: 3,
                                query: query
                            },
                            dataType: "json",
                            // data: $("#boutiqueForm").serialize(),
                            success: function(data) {
                                // alert(data)
                                // var boutiques = data.split(",");
                                result($.map(data, function(item) {
                                    // console.log(item);
                                    return item;
                                }))
                            },
                            error: function() {
                                alert("La requête ddd");
                            }
                        });
                    }
                });
                $(".searchProduct-" + idArticle + '_' + idPanier).focus();
                //  else {
                //     $("#reponseS").html('');
            }
        });
    });

    $(".btnConfEditProduit").on('click', function() {

        ids = $(this).attr('id');
        splitIds = ids.split('_');
        oldIdA = splitIds[0]
        idP = splitIds[1]
        newRef = $(".searchProduct-" + oldIdA + "_" + idP).val();
        oldRef = $(".oldRef-" + oldIdA + "_" + idP).val();
        newQuantite = $(".quantite-" + oldIdA + "_" + idP).val();
        newUnite = $(".unite-" + oldIdA + "_" + idP).val();
        // alert(newUnite)
        $.ajax({
            url: "vitrine/ajax/vitrineAjax",
            method: "POST",
            data: {
                operation: 5,
                oldIdA: oldIdA,
                idP: idP,
                newRef: newRef,
                oldRef: oldRef,
                newQuantite: newQuantite,
                newUnite: newUnite
            },
            dataType: "text",
            // data: $("#boutiqueForm").serialize(),
            success: function(data) {
                // alert(data)
                window.location.href = "vitrine/commande";
            },
            error: function() {
                alert("La requête ddd");
            }
        });
    });

    $(".btnConf").on('click', function() {
        id = $(this).attr("id")
        stockIns = $("#stockIns-" + id).val();
        // alert(id)
        if (stockIns == 0) {
          $("#msg_confirmer_commande" + id).modal('show');
          $(".btnConfirmerCommande-" + id).on('click', function() {
              // ids = $(this).attr('id');
              idP = $("#idPanier-" + id).val();
              // alert(idP)
              // alert(oldRef + '----' + newRef)
              $.ajax({
                  url: "vitrine/ajax/vitrineAjax",
                  method: "POST",
                  data: {
                      operation: 12,
                      idPanier: idP
                  },
                  dataType: "text",
                  // data: $("#boutiqueForm").serialize(),
                  success: function(data) {
                      // alert(1)
                      // alert(data)
                      // if (data == 'ok') {
                      window.location.href = "vitrine/commande";
                      // }
                  },
                  error: function() {
                      alert("La requête ddd");
                  }
              });
          });
            $("#msg_confirmer_commande" + id).on('hidden.bs.modal', function(){
            // alert(1)
                // $(this).data('bs.modal', null);
                // window.location.href = "index"
                $(".btnConfirmerCommande-" + id).removeClass("btnConfirmerCommande-" + id);
            });
        } else {
          $("#msg_echec" + id).modal('show')
        }
    });

    // $("#imgsrc_Upd").on('input', function() {
    //   // $('#apercu_file').attr('src',)
    //   v = $('#select_file').val()
    //   alert(v)
    // });

});
$(document).ready(function () {
    // alert(1)
    /// Initializing croppie in my image_demo div
    var image_crop = $('#image_demo').croppie({
        viewport: {
            width: 270,
            height: 370,
            type:'square'
        },
        boundary:{
            width: 650,
            height: 390
        }
    });
    /// catching up the cover_image change event and binding the image into my croppie. Then show the modal.
    $('#cover_image').on('change', function(){
        var reader = new FileReader();
        reader.onload = function (event) {
            image_crop.croppie('bind', {
                url: event.target.result,
            });
        }
        reader.readAsDataURL(this.files[0]);
        $('#uploadimageModal').modal('show');
    });

    /// Get button click event and get the current crop image
    $('.crop_image').click(function(event){
        // var formData = new FormData();
        id = $('#id_Upd_Nv').val();
        idB = $('#idB_Upd_Nv').val();
        des = $('#des_Upd_Nv').val();
        image_crop.croppie('result',
         {type: 'canvas', size: 'viewport'}).then(function(res) {
            // formData.append('cropped_image', blob);
            ajaxFormPost(res, 'vitrine/ajax/vitrineAjax', id, idB, des);
            // ajaxFormPost(res, 'vitrine/new/cropperAjax');
            // console.log(formData); /// Calling my ajax function with my blob data passed to it
        });
        $('#uploadimageModal').modal('hide');
        $('#imageNvDesignation').modal('hide');
    });
    /// Ajax Function
    function ajaxFormPost(formData, actionURL, id, idB, des){
        $.ajax({
            url: actionURL,
            type: 'POST',
            data: {
                op: 'croppe',
                id: id,
                idB: idB,
                des: des,
                data: formData
            },
            // data: formData,
            success: function(response) {
                // alert(response)
                if (response) {
    ///Some Fancy UI, that you won't probably need
                        // alert(111)
                        window.location.href = 'vitrine/vitrine';
    /// But this part you may need, reset the input value and put the cropped image inside my image attribute.
                        $('#cover_image').val("");
                        $('#uploaded-image').attr('src', response['url']);
                }
            },
            dataType: 'text'
        });
    }
})
$(document).ready(function () {
    // alert(1)
    /// Initializing croppie in my image_demo div
    var image_crop = $('#image_demo_edit').croppie({
        viewport: {
            width: 270,
            height: 370,
            type:'square'
        },
        boundary:{
            width: 650,
            height: 390
        }
    });
    /// catching up the cover_image change event and binding the image into my croppie. Then show the modal.
    $('#cover_image_edit').on('change', function(){
        var reader = new FileReader();
        reader.onload = function (event) {
            image_crop.croppie('bind', {
                url: event.target.result,
            });
        }
        reader.readAsDataURL(this.files[0]);
        $('#uploadimageModalEdit').modal('show');
    });

    /// Get button click event and get the current crop image
    $('.crop_image_edit').click(function(event){
        // var formData = new FormData();
        id = $('#id_Upd_Ex').val();
        idB = $('#idB_Upd_Ex').val();
        des = $('#des_Upd_Ex').val();
        img = $('#img_Upd_Ex').val();
        image_crop.croppie('result',
         {type: 'canvas', size: 'viewport'}).then(function(res) {
            // formData.append('cropped_image', blob);
            ajaxFormPostEdit(res, 'vitrine/ajax/vitrineAjax', id, idB, des, img);
            // ajaxFormPost(res, 'vitrine/new/cropperAjax');
            // console.log(formData); /// Calling my ajax function with my blob data passed to it
        });
        $('#uploadimageModalEdit').modal('hide');
        $('#imageExDesignation').modal('hide');
    });
    /// Ajax Function
    function ajaxFormPostEdit(formData, actionURL, id, idB, des, img){
        $.ajax({
            url: actionURL,
            type: 'POST',
            data: {
                op: 'croppe',
                id: id,
                idB: idB,
                des: des,
                img: img,
                data: formData
            },
            // data: formData,
            success: function(response) {
                // alert(response)
                if (response) {
    ///Some Fancy UI, that you won't probably need
                        // alert(111)
                        window.location.href = 'vitrine/vitrine';
    /// But this part you may need, reset the input value and put the cropped image inside my image attribute.
                        $('#cover_image_edit').val("");
                        // $('#uploaded-image').attr('src', response['url']);
                }
            },
            dataType: 'text'
        });
    }
})

/**Fin Uploader Image Existante Designation Vitrine**/

// $(document).ready(function(){
//     var size;
//     $('#cropbox').Jcrop({
//       aspectRatio: 1,
//       onSelect: function(c){
//        size = {x:c.x,y:c.y,w:c.w,h:c.h};
//        $("#crop").css("visibility", "visible");
//       }
//     });
//
//     $("#crop").click(function(){
//         // var img = $("#cropbox").attr('src');
//
//         // $("#cropped_img").show();
//         // $("#cropped_img").attr('src','image-crop?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&img='+img);
//     });
// });
$(document).ready(function(){

    $("#btnSubmit").click(function(){

        var fd = new FormData();
        var files = $('#userImage')[0].files;

        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);

           $.ajax({
              url: 'vitrine/crop/upload',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){
                 if(response != 0){
                   alert(response)
                   $(".img").removeAttr("src");
                   $(".img").attr("src",'vitrine/crop/uploads/'+response);
                   $("div.jcrop-holder div div img").attr("src",'vitrine/crop/uploads/'+response);
                    // $(".preview img").show(); // Display image element
                 }else{
                    alert('file not uploaded');
                 }
              },
           });
        }else{
           alert("Please select a file.");
        }
    });
    $("#crop").click(function(){

        var fd = new FormData();
        var files = $('#userImage')[0].files;

        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);

           $.ajax({
              url: 'vitrine/crop/upload',
              type: 'post',
              data: fd,
              crop: 'crop',
              contentType: false,
              processData: false,
              success: function(response){
                 if(response != 0){
                   alert(response+" zzzz")
                   $(".img").removeAttr("src");
                   $(".img").attr("src",'vitrine/crop/uploads/'+response);
                   $("div.jcrop-holder div div img").attr("src",'vitrine/crop/uploads/'+response);
                    // $(".preview img").show(); // Display image element
                 }else{
                    alert('file not uploaded');
                 }
              },
           });
        }else{
           alert("Please select a file.");
        }
    });
});
/************* Modification ***************/
