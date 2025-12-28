/************* Modification ***************/
/**Debut supprimer Designation Vitrine**/
/**Debut ajouter Vitrine**/

$(function () {
  // alert(12544);
  // $(".btnAjoutArticle, .btnEditerProduit").click(function () {
  //   var clicked = $(this);
  //   var id = clicked.attr("id");
  //   // alert(id)
  //   $(document).on("click", ".typeahead li a", function (e) {
  //     e.preventDefault();

  //     query = $(this).text();
  //     // alert(query)

  //     $.ajax({
  //       url: "vitrine/ajax/vitrineAjax",
  //       method: "POST",
  //       data: {
  //         operation: 6,
  //         designation: query,
  //       },
  //       success: function (data) {
  //         tab = data.split("<>");
  //         // alert(data)
  //         if (
  //           tab[1] != "Article" &&
  //           tab[1].toLowerCase() != "article" &&
  //           tab[0] != "1" &&
  //           tab[0] != "" &&
  //           tab[1] != ""
  //         ) {
  //           alert(data + "---113");
  //           // $("#options").removeAttr('class')selectUnite
  //           $(".selectUnite-" + id).html("");
  //           var art = new Option("article", "article");
  //           var us = new Option(tab[1], tab[1]);
  //           /// jquerify the DOM object 'o' so we can use the html method
  //           $(art).html("article");
  //           $(us).html(tab[1]);
  //           $(".selectUnite-" + id).append(art);
  //           $(".selectUnite-" + id).append(us);
  //         } else {
  //           alert(data + "---2");
  //           $(".selectUnite-" + id).html("");
  //           var art = new Option("article", "article");
  //           /// jquerify the DOM object 'o' so we can use the html method
  //           $(art).html("article");
  //           $(".selectUnite-" + id).append(art);
  //         }
  //         // console.log(1)
  //       },
  //       error: function () {
  //         alert("La requête ");
  //       },
  //       dataType: "text",
  //     });
  //   });
  //   $(".searchProduct-" + id).keyup(function (e) {
  //     e.preventDefault();

  //     var keycode = e.keyCode ? e.keyCode : e.which;
  //     var element = $(".typeahead li.active").text();
  //     if (keycode == "13") {
  //       $.ajax({
  //         url: "vitrine/ajax/vitrineAjax",
  //         method: "POST",
  //         data: {
  //           operation: 6,
  //           designation: element,
  //         },
  //         success: function (data) {
  //           tab = data.split("<>");
  //           // alert(data)
  //           if (
  //             tab[1] != "Article" &&
  //             tab[1].toLowerCase() != "article" &&
  //             tab[0] != "1" &&
  //             tab[0] != "" &&
  //             tab[1] != ""
  //           ) {
  //             // alert(data + "---1")
  //             // $("#options").removeAttr('class')selectUnite
  //             $(".selectUnite-" + id).html("");
  //             var art = new Option("article", "article");
  //             var us = new Option(tab[1], tab[1]);
  //             /// jquerify the DOM object 'o' so we can use the html method
  //             $(art).html("article");
  //             $(us).html(tab[1]);
  //             $(".selectUnite-" + id).append(art);
  //             $(".selectUnite-" + id).append(us);
  //           } else {
  //             // alert(data + "---2")
  //             $(".selectUnite-" + id).html("");
  //             var art = new Option("article", "article");
  //             /// jquerify the DOM object 'o' so we can use the html method
  //             $(art).html("article");
  //             $(".selectUnite-" + id).append(art);
  //           }
  //           // console.log(1)
  //         },
  //         error: function () {
  //           alert("La requête ");
  //         },
  //         dataType: "text",
  //       });
  //     }
  //   });
  // });
  // $(".btnRetournerProduit").click(function () {
  //   var clicked = $(this);
  //   var clicked_id = clicked.attr("id");
  //   var splitId = clicked_id.split("_");
  //   var idArticle = splitId[0];
  //   var idPanier = splitId[1];

  //   $(".btnConfRetourProduit-" + clicked_id).click(function () {
  //     $.ajax({
  //       url: "vitrine/ajax/vitrineAjax",
  //       method: "POST",
  //       data: {
  //         operation: 2,
  //         idArticle: idArticle,
  //         idPanier: idPanier,
  //       },
  //       success: function (data) {
  //         // tab = data.split('<>');
  //         // alert(1)
  //         // console.log(1)
  //         window.location.href = "vitrine/commande";
  //       },
  //       error: function () {
  //         alert("La requête ");
  //       },
  //       dataType: "text",
  //     });
  //     // } else {
  //     //     $('.text-error').removeClass('hidden')
  //     //     $('.text-error').text('Attention : Vous ne pouvez pas retirer plus de la quantité commander.');
  //     // }
  //   });
  // });

  // $(".btnAjoutArticle").click(function () {
  //   clicked = $(this);
  //   idPanier = clicked.attr("id");
  //   // alert(idPanier)
  //   $(".searchProduct-" + idPanier).keyup(function () {
  //     var query = $(".searchProduct-" + idPanier).val();
  //     // var typeSearchValue = $('#searchType option:selected').text();
  //     if (query.length > 0) {
  //       // alert(query)
  //       $(".searchProduct-" + idPanier).typeahead({
  //         source: function (query, result) {
  //           $.ajax({
  //             url: "vitrine/ajax/vitrineAjax",
  //             method: "POST",
  //             data: {
  //               operation: 3,
  //               query: query,
  //             },
  //             dataType: "json",
  //             // data: $("#boutiqueForm").serialize(),
  //             success: function (data) {
  //               // alert(data)
  //               // alert('dfghsswws')
  //               // var boutiques = data.split(",");
  //               result(
  //                 $.map(data, function (item) {
  //                   // console.log(item);
  //                   return item;
  //                 })
  //               );
  //             },
  //             error: function (err) {
  //               alert("La requête ss");
  //               console.log(err);
  //             },
  //           });
  //         },
  //       });
  //       $(".searchProduct-" + idPanier).focus();
  //       //  else {
  //       //     $("#reponseS").html('');
  //     }
  //   });
  // });
  // $(".btnConfAjoutArticle").click(function () {
  //   clicked = $(this);
  //   idPanier = clicked.attr("id");
  //   ref = $(".searchProduct-" + idPanier).val();
  //   quantite = $(".quantite-" + idPanier).val();
  //   unite = $(".unite-" + idPanier).val();

  //   $.ajax({
  //     url: "vitrine/ajax/vitrineAjax",
  //     method: "POST",
  //     data: {
  //       operation: 7,
  //       ref: ref,
  //       quantite: quantite,
  //       unite: unite,
  //       idPanier: idPanier,
  //     },
  //     success: function (data) {
  //       // tab = data.split('<>');
  //       // alert(data)
  //       // console.log(1)
  //       window.location.href = "vitrine/commande";
  //     },
  //     error: function () {
  //       alert("La requête ");
  //     },
  //     dataType: "text",
  //   });
  // });

  // $(".btnEditerProduit").on("click", function () {
  //   // e.preventDefault();
  //   // alert(1)
  //   idBtn = $(this).attr("id");
  //   splitId = idBtn.split("_");
  //   idArticle = splitId[0];
  //   idPanier = splitId[1];
  //   ust = $("#us-" + idArticle + "_" + idPanier).val();
  //   // alert(ust)

  //   $.ajax({
  //     url: "vitrine/ajax/vitrineAjax",
  //     method: "POST",
  //     data: {
  //       operation: 4,
  //       idA: idArticle,
  //       idP: idPanier,
  //     },
  //     dataType: "json",
  //     // data: $("#boutiqueForm").serialize(),
  //     success: function (data) {
  //       ref = data.designation;
  //       unite = data.unite;
  //       quantite = data.quantite;
  //       idA = data.idArticle;
  //       idP = data.idPanier;
  //       idB = data.idBoutique;

  //       $(".searchProduct-" + idA + "_" + idP).val(ref);
  //       $(".oldRef-" + idA + "_" + idP).val(ref);
  //       $(".quantite-" + idA + "_" + idP).val(quantite);

  //       if (ust != "Article" && ust != "article") {
  //         $(".selectUnite-" + idA + "_" + idP).html("");
  //         var art = new Option("article", "article");
  //         var us = new Option(ust, ust);
  //         /// jquerify the DOM object 'o' so we can use the html method
  //         $(art).html("article");
  //         $(us).html(ust);
  //         $(".selectUnite-" + idA + "_" + idP).append(art);
  //         $(".selectUnite-" + idA + "_" + idP).append(us);
  //       } else {
  //         // alert(data + "---2")
  //         $(".selectUnite-" + idA + "_" + idP).html("");
  //         var art = new Option("article", "article");
  //         /// jquerify the DOM object 'o' so we can use the html method
  //         $(art).html("article");
  //         $(".selectUnite-" + idA + "_" + idP).append(art);
  //       }

  //       $("option[value='" + unite + "']").attr("selected", "selected");
  //     },
  //     error: function () {
  //       alert("La requête ddd");
  //     },
  //   });

  //   $(".searchProduct-" + idArticle + "_" + idPanier).keyup(function () {
  //     var query = $(".searchProduct-" + idArticle + "_" + idPanier).val();
  //     // var typeSearchValue = $('#searchType option:selected').text();
  //     if (query.length > 0) {
  //       // alert(query)
  //       $(".searchProduct-" + idArticle + "_" + idPanier).typeahead({
  //         source: function (query, result) {
  //           $.ajax({
  //             url: "vitrine/ajax/vitrineAjax",
  //             method: "POST",
  //             data: {
  //               operation: 3,
  //               query: query,
  //             },
  //             dataType: "json",
  //             // data: $("#boutiqueForm").serialize(),
  //             success: function (data) {
  //               // alert(data)
  //               // var boutiques = data.split(",");
  //               result(
  //                 $.map(data, function (item) {
  //                   // console.log(item);
  //                   return item;
  //                 })
  //               );
  //             },
  //             error: function () {
  //               alert("La requête ddd");
  //             },
  //           });
  //         },
  //       });
  //       $(".searchProduct-" + idArticle + "_" + idPanier).focus();
  //       //  else {
  //       //     $("#reponseS").html('');
  //     }
  //   });
  // });

  // $(".btnConfEditProduit").on("click", function () {
  //   ids = $(this).attr("id");
  //   splitIds = ids.split("_");
  //   oldIdA = splitIds[0];
  //   idP = splitIds[1];
  //   newRef = $(".searchProduct-" + oldIdA + "_" + idP).val();
  //   oldRef = $(".oldRef-" + oldIdA + "_" + idP).val();
  //   newQuantite = $(".quantite-" + oldIdA + "_" + idP).val();
  //   newUnite = $(".unite-" + oldIdA + "_" + idP).val();
  //   // alert(newUnite)
  //   $.ajax({
  //     url: "vitrine/ajax/vitrineAjax",
  //     method: "POST",
  //     data: {
  //       operation: 5,
  //       oldIdA: oldIdA,
  //       idP: idP,
  //       newRef: newRef,
  //       oldRef: oldRef,
  //       newQuantite: newQuantite,
  //       newUnite: newUnite,
  //     },
  //     dataType: "text",
  //     // data: $("#boutiqueForm").serialize(),
  //     success: function (data) {
  //       // alert(data)
  //       window.location.href = "vitrine/commande";
  //     },
  //     error: function () {
  //       alert("La requête ddd");
  //     },
  //   });
  // });

  $(".btnConfEt").on("click", function () {
    id = $(this).attr("id");
    stockIns = $("#stockIns-" + id).val();
    // alert(id)
    if (stockIns == 0) {
      $("#msg_confirmer_commande" + id).modal("show");
      $(".btnConfirmerCommande-" + id).on("click", function () {
        // ids = $(this).attr('id');
        idP = $("#idPanier-" + id).val();
        // alert(idP)
        // alert(oldRef + '----' + newRef)
        $.ajax({
          url: "vitrine/ajax/entrepotAjax",
          method: "POST",
          data: {
            operation: 1,
            idPanier: idP,
          },
          dataType: "text",
          // data: $("#boutiqueForm").serialize(),
          success: function (data) {
            // alert(1)
            console.log(data);
            // if (data == 'ok') {
            window.location.href = "vitrine/commande";
            // }
          },
          error: function (err) {
            alert(err);
          },
        });
      });
      $("#msg_confirmer_commande" + id).on("hidden.bs.modal", function () {
        // alert(1)
        // $(this).data('bs.modal', null);
        // window.location.href = "index"
        $(".btnConfirmerCommande-" + id).removeClass(
          "btnConfirmerCommande-" + id
        );
      });
    } else {
      $("#msg_echec" + id).modal("show");
    }
  });

  // $(".btnExp").on("click", function () {
  //   elementId = $(this).attr("id");
  //   id = elementId.split("_")[1];
  //   // stockIns = $("#stockIns-" + id).val();
  //   // alert(id)
  //   // alert(stockIns)
  //   // if (stockIns == 0) {
  //   $("#msg_exp_commande" + id).modal("show");
  //   $(".btnExpCommande-" + id).on("click", function () {
  //     // ids = $(this).attr('id');
  //     idP = $("#idPanierExp" + id).val();
  //     //   alert(idP)
  //     // alert(oldRef + '----' + newRef)
  //     $.ajax({
  //       url: "vitrine/ajax/vitrineAjax",
  //       method: "POST",
  //       data: {
  //         operation: 13,
  //         idPanier: idP,
  //       },
  //       dataType: "text",
  //       // data: $("#boutiqueForm").serialize(),
  //       success: function (data) {
  //         // alert(1)
  //         //   alert(data)
  //         // if (data == 'ok') {
  //         window.location.href = "vitrine/commande";
  //         // }
  //       },
  //       error: function () {
  //         alert("La requête ddd");
  //       },
  //     });
  //   });
  //   $("#msg_exp_commande" + id).on("hidden.bs.modal", function () {
  //     // alert(1)
  //     // $(this).data('bs.modal', null);
  //     // window.location.href = "index"
  //     $(".btnExpCommande-" + id).removeClass("btnExpCommande-" + id);
  //   });
  //   // } else {
  //   //   $("#msg_echec" + id).modal('show')
  //   // }
  // });

  // $(".btnlivrer").on("click", function () {
  //   elementId = $(this).attr("id");
  //   id = elementId.split("_")[1];
  //   // stockIns = $("#stockIns-" + id).val();
  //   // alert(id)
  //   // alert(stockIns)
  //   // if (stockIns == 0) {
  //   $("#msg_livrer_commande" + id).modal("show");
  //   $(".btnLivrerCommande-" + id).on("click", function () {
  //     // ids = $(this).attr('id');
  //     idP = $("#idPanierLivrer" + id).val();
  //     //   alert(idP)
  //     // alert(oldRef + '----' + newRef)
  //     $.ajax({
  //       url: "vitrine/ajax/vitrineAjax",
  //       method: "POST",
  //       data: {
  //         operation: 14,
  //         idPanier: idP,
  //       },
  //       dataType: "text",
  //       // data: $("#boutiqueForm").serialize(),
  //       success: function (data) {
  //         // alert(1)
  //         //   alert(data)
  //         // if (data == 'ok') {
  //         window.location.href = "vitrine/commande";
  //         // }
  //       },
  //       error: function () {
  //         alert("La requête ddd");
  //       },
  //     });
  //   });
  //   $("#msg_livrer_commande" + id).on("hidden.bs.modal", function () {
  //     // alert(1)
  //     // $(this).data('bs.modal', null);
  //     // window.location.href = "index"
  //     $(".btnLivrerCommande-" + id).removeClass("btnLivrerCommande-" + id);
  //   });
  //   // } else {
  //   //   $("#msg_echec" + id).modal('show')
  //   // }
  // });

  // $(".btnCancelConf").on("click", function () {
  //   elementId = $(this).attr("id");
  //   id = elementId.split("_")[1];
  //   // stockIns = $("#stockIns-" + id).val();
  //   // alert(id)
  //   // alert(stockIns)
  //   // if (stockIns == 0) {
  //   $("#msg_cancel_confirmation" + id).modal("show");
  //   $(".btnCancelConf-" + id).on("click", function () {
  //     // ids = $(this).attr('id');
  //     idP = $("#idPanierCancelConf" + id).val();
  //     //   alert(idP)
  //     // alert(oldRef + '----' + newRef)
  //     $.ajax({
  //       url: "vitrine/ajax/vitrineAjax",
  //       method: "POST",
  //       data: {
  //         operation: 15,
  //         idPanier: idP,
  //       },
  //       dataType: "text",
  //       // data: $("#boutiqueForm").serialize(),
  //       success: function (data) {
  //         // alert(1)
  //         //   alert(data)
  //         // if (data == 'ok') {
  //         window.location.href = "vitrine/commande";
  //         // }
  //       },
  //       error: function () {
  //         alert("La requête ddd");
  //       },
  //     });
  //   });
  //   $("#msg_cancel_confirmation" + id).on("hidden.bs.modal", function () {
  //     // alert(1)
  //     // $(this).data('bs.modal', null);
  //     // window.location.href = "index"
  //     $(".btnCancelConf-" + id).removeClass("btnCancelConf-" + id);
  //   });
  //   // } else {
  //   //   $("#msg_echec" + id).modal('show')
  //   // }
  // });

  $(".btnRetourEt").on("click", function () {
    elementId = $(this).attr("id");
    id = elementId.split("_")[1];
    // stockIns = $("#stockIns-" + id).val();
    // alert(id)
    // alert(stockIns)
    // if (stockIns == 0) {
    $("#msg_rtrn_pagnet" + id).modal("show");
    $(".btnRetournerPagnet-" + id).on("click", function () {
      // ids = $(this).attr('id');
      idP = $("#idPanierRetour" + id).val();
      //   alert(idP)
      // alert(oldRef + '----' + newRef)
      $.ajax({
        url: "vitrine/ajax/entrepotAjax",
        method: "POST",
        data: {
          operation: 2,
          idPanier: idP,
        },
        dataType: "text",
        // data: $("#boutiqueForm").serialize(),
        success: function (data) {
          // alert(1)
          console.log(data);
          // if (data == 'ok') {
          window.location.href = "vitrine/commande";
          // }
        },
        error: function () {
          alert("La requête ddd");
        },
      });
    });
    $("#msg_rtrn_pagnet" + id).on("hidden.bs.modal", function () {
      // alert(1)
      // $(this).data('bs.modal', null);
      // window.location.href = "index"
      $(".btnRetournerPagnet-" + id).removeClass("btnRetournerPagnet-" + id);
    });
    // } else {
    //   $("#msg_echec" + id).modal('show')
    // }
  });

  // $(".btnAnnuler").on("click", function () {
  // elementId = $(this).attr("id");
  // id = elementId.split("_")[1];
  // stockIns = $("#stockIns-" + id).val();
  // alert(id)
  // alert(stockIns)
  // if (stockIns == 0) {
  // $("#msg_annuler_pagnet" + id).modal("show");
  // $(".btnAnnulerPagnet-" + id).on("click", function () {
  // ids = $(this).attr('id');
  // idP = $("#idPanierAnnuler" + id).val();
  //   alert(idP)
  // alert(oldRef + '----' + newRef)
  // $.ajax({
  //   url: "vitrine/ajax/vitrineAjax",
  //   method: "POST",
  //   data: {
  //     operation: 17,
  //     idPanier: idP,
  //   },
  //   dataType: "text",
  // data: $("#boutiqueForm").serialize(),
  // success: function (data) {
  // alert(1)
  //   alert(data)
  // if (data == 'ok') {
  // window.location.href = "vitrine/commande";
  // }
  //     },
  //     error: function () {
  //       alert("La requête ddd");
  //     },
  //   });
  // });
  // $("#msg_annuler_pagnet" + id).on("hidden.bs.modal", function () {
  // alert(1)
  // $(this).data('bs.modal', null);
  // window.location.href = "index"
  //   $(".btnAnnulerPagnet-" + id).removeClass("btnAnnulerPagnet-" + id);
  // });
  // } else {
  //   $("#msg_echec" + id).modal('show')
  // }
  // });
});

function changeDepot(idArticle, idDesignation) {
  var idEntrepot = $("#entrepot-" + idArticle).val();
  var qty = $("#qty-" + idArticle).val();
  var uniteV = $("#uniteV-" + idArticle).val();

  // alert(idEntrepot);
  $.ajax({
    url: "vitrine/ajax/entrepotAjax",

    method: "POST",

    data: {
      operation: 3,

      idArticle: idArticle,

      idDesignation: idDesignation,

      idEntrepot: idEntrepot,

      qty: qty,

      uniteV: uniteV,
    },

    success: function (data) {
      console.log(data);
      if (data == "1" || data == 1) {
        $("#entrepot-" + idArticle).css("background", "#2ECC71");
      } else if (data == "3" || data == 3) {
        $("#entrepot-" + idArticle).css("background", "#FD7070");
        confirm(
          "Une erreur est survenue: Quantité insuffisante dans le dépôt choisit."
        );
      } else {
        console.log(data);
        confirm("Une erreur est survenue. Rééssayer svp.");
      }
    },

    error: function (err) {
      console.log(err);
      confirm("Une erreur est survenue. Rééssayer svp.");
    },

    dataType: "text",
  });
}
