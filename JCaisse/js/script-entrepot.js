/*

R�sum�:

Commentaire:

version:1.1

Auteur: Mor Mboup

Date de modification:

*/

/*************** Debut chargement container vente pharmacie ********************/

$(document).ready(function () {
  //executes code below when user click on pagination links

  $(document).on("click", ".paginationLoaderEt a", function (e) {
    // $("#resultsProductsPrix").on( "click", function (e){

    // alert(3)

    e.preventDefault();

    // $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    $("#img-load-page" + page).show();

    //  alert(page)
    // $(".loading-gif").show();

    $("#entrepot-venteContent").load(
      "ajax/loadContainer-entrepotAjax.php",
      { page: page },
      function (data) {
        //get content from PHP page

        // alert(data)

        $("#img-load-page" + page).hide();

        $("#pageCourante").val(page);

        // $("#loading_gif_modal").modal("hide");
      }
    );

    // $("#resultsProductsPrix").load("ajax/listerPrixAjax.php",{"page":page,"operation":1}
  });
  // alert(111111)

  $("#entrepot-venteContent").load(
    "ajax/loadContainer-entrepotAjax.php",
    function (data) {
      //get content from PHP page

      // alert(data)

      $(".loading-gif").hide();
    }
  );
});

/*************** Fin chargement container vente pharmacie ********************/

$(document).ready(function () {
  // alert(77878)
  $(document).on("click", "#addPanier-entrepot", function (e) {
    // $("#loading_gif_modal").modal("show");
    // alert(552266)

    $(".img-load").show();

    $("#addPanier-entrepot").attr("disabled", "disabled");

    $.ajax({
      url: "ajax/operationPanier-entrepotAjax.php",

      method: "POST",

      data: {
        operation: 1,

        btnSavePagnetVente: "btnSavePagnetVente",
      },

      success: function (data) {
        // alert(data)

        $("#entrepot-venteContent").load(
          "ajax/loadContainer-entrepotAjax.php",
          function (data) {
            //get content from PHP page

            // alert(data)

            // $(".loading-gif").hide();

            // $("#loading_gif_modal").modal("hide");

            $(".img-load").hide();

            $(".codeBarreLigneEt").focus();
          }
        );
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });

  $(document).on("click", ".btn_Termine_Panier_Et", function (e) {
    idPanier = $(this).attr("data-idPanier");

    // $("#loading_gif_modal").modal("show");

    remise = $("#val_remise" + idPanier).val()
      ? $("#val_remise" + idPanier).val()
      : 0;

    compte = $("#compte" + idPanier).val();

    compte_p = $("#compte" + idPanier + " option:selected").text();

    clientInput = $("#clientInput" + idPanier).val();

    avanceInput = $("#avanceInput" + idPanier).val()
      ? $("#avanceInput" + idPanier).val()
      : 0;

    compteAvance = $("#compteAvance" + idPanier).val();

    compteAvance_p = $("#compteAvance" + idPanier + " option:selected").text();

    versement = $("#versement" + idPanier).val()
      ? $("#versement" + idPanier).val()
      : 0;

    total_p = $("#somme_Total" + idPanier).text();

    apayer_p = $("#somme_Apayer" + idPanier).text();

    if (compte) {
      if (compte == "2" && clientInput == "") {
        $("#msg_info_ClientRequired").modal("show");
      } else {
        if (apayer_p != 0) {
          // alert(total_p +" / "+apayer_p);

          $("#cache_btn_Terminer" + idPanier).hide();

          $(".btn_Retourner_Produit" + idPanier).prop("disabled", true);

          // $(".img-load-terminer").show();

          $(".btn_Termine_Panier" + idPanier).attr("disabled", "disabled");

          $("#btnContent" + idPanier).show();

          $("#panierEncours" + idPanier).html("Panier " + idPanier);

          $("#panelPanier" + idPanier).attr("class", "panel panel-success");

          $(".btnRtrAvant" + idPanier).attr(
            "class",
            "btn btn-danger pull-right disabled"
          );

          $(".btn_Retourner_Produit" + idPanier).attr(
            "class",
            "btn btn-danger pull-right"
          );

          // $(".filedReadonly"+idPanier ).attr('disabled','disabled');

          $(".filedReadonly_E" + idPanier).each(function (i, obj) {
            id = $(this).attr("id");

            var originalForm = $("#" + id);
            var div = $("<div/>", {
              text: originalForm.val(),
              id: originalForm.attr("id"),
            });
            $("#" + id).replaceWith(div);
          });

          $(".filedReadonly_ES" + idPanier).each(function (i, obj) {
            id = $(this).attr("id");
            // alert(id)

            var originalForm = $("#" + id + " option:selected");
            var div = $("<div/>", {
              text: originalForm.text(),
              id: originalForm.attr("id"),
            });
            $("#" + id).replaceWith(div);
          });

          $("#total_p" + idPanier).text(total_p);

          $("#remise_p" + idPanier).text(remise);

          $("#apayer_p" + idPanier).text(apayer_p);

          // alert(compte+"/"+compte_p)

          if (compte) {
            // alert(11233)

            $("#compte_p" + idPanier).text(compte_p);

            $("#divcompte_p" + idPanier).show();
          }

          if (avanceInput > 0) {
            // alert(11233)

            $("#avance_p" + idPanier).text(avanceInput);

            $("#reste_p" + idPanier).text(
              parseInt(total_p) - parseInt(avanceInput)
            );

            $("#compteAvance_p" + idPanier).text(compteAvance_p);

            $("#divavance_p" + idPanier).show();
          }

          if (versement > 0) {
            // alert(11233)

            $("#versement_p" + idPanier).text(versement);

            $("#divversement_p" + idPanier).show();

            $("#rendu_p" + idPanier).text(
              parseInt(versement) - parseInt(apayer_p)
            );

            $("#divrendu_p" + idPanier).show();
          }

          $("#footerContent" + idPanier).show();
        }

        //

        // alert(idPanier+"/"+remise+"/"+compte+"/"+clientInput+"/"+avanceInput+"/"+compteAvance+"/"+versement)

        $.ajax({
          url: "ajax/operationPanier-entrepotAjax.php",

          method: "POST",

          data: {
            //operation: 1,

            idPagnet: idPanier,

            remise: remise,

            compte: compte,

            compteAvance: compteAvance,

            clientInput: clientInput,

            avanceInput: avanceInput,

            versement: versement,

            btnImprimerFacture: "btnImprimerFacture",
          },

          success: function (data) {
            ins = data.split("<>");

            if (data == "1" || data == "") {
            } else if (ins[0] == "0" || ins[0] == 0) {
              $("#panelPanier" + idPanier).attr("class", "panel panel-primary");

              $("#btnContent" + idPanier).hide();

              $("#cache_btn_Terminer" + idPanier).show();

              // confirm("Certains articles ne sont plus disponible");

              $("#entrepot-venteContent").load(
                "ajax/loadContainer-entrepotAjax.php",
                function (data) {
                  //get content from PHP page

                  // alert(data)
                  $(".loading-gif").hide();

                  i = 0;
                  ins.forEach((e) => {
                    if (i != 0) {
                      $("#quantite" + e).css("background-color", "#FC8080");
                    }
                    i++;
                  });
                }
              );
            } else {
              $("#msg_info_body").html(data);

              $("#msg_info_1").modal("show");

              $(".img-load-terminer").hide();

              $("#entrepot-venteContent").load(
                "ajax/loadContainer-entrepotAjax.php",
                function (data) {
                  //get content from PHP page

                  // alert(data)

                  $(".loading-gif").hide();

                  // $("#loading_gif_modal").modal("hide");
                }
              );
            }
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });
      }
    } else {
      // alert(4858)
      if (apayer_p != 0) {
        // alert(147)

        // alert(total_p +" / "+apayer_p);

        $("#cache_btn_Terminer" + idPanier).hide();

        $(".btn_Retourner_Produit" + idPanier).prop("disabled", true);

        // $(".img-load-terminer").show();

        $(".btn_Termine_Panier" + idPanier).attr("disabled", "disabled");

        $("#btnContent" + idPanier).show();

        $("#panierEncours" + idPanier).html("Panier " + idPanier);

        $("#panelPanier" + idPanier).attr("class", "panel panel-success");

        $(".btnRtrAvant" + idPanier).attr(
          "class",
          "btn btn-danger pull-right disabled"
        );

        $(".btn_Retourner_Produit" + idPanier).attr(
          "class",
          "btn btn-danger pull-right"
        );

        // $(".filedReadonly"+idPanier ).attr('disabled','disabled');

        $(".filedReadonly_E" + idPanier).each(function (i, obj) {
          id = $(this).attr("id");
          // alert(id)

          var originalForm = $("#" + id);
          var div = $("<div/>", {
            text: originalForm.val(),
            id: originalForm.attr("id"),
          });
          $("#" + id).replaceWith(div);
        });

        $(".filedReadonly_ES" + idPanier).each(function (i, obj) {
          id = $(this).attr("id");
          // alert(id)

          var originalForm = $("#" + id + " option:selected");
          var div = $("<div/>", {
            text: originalForm.text(),
            id: originalForm.attr("id"),
          });
          $("#" + id).replaceWith(div);
        });

        $("#total_p" + idPanier).text(total_p);

        $("#remise_p" + idPanier).text(remise);

        $("#apayer_p" + idPanier).text(apayer_p);

        // alert(compte+"/"+compte_p)

        if (compte) {
          // alert(11233)

          $("#compte_p" + idPanier).text(compte_p);

          $("#divcompte_p" + idPanier).show();
        }

        if (avanceInput > 0) {
          // alert(11233)

          $("#avance_p" + idPanier).text(avanceInput);

          $("#reste_p" + idPanier).text(
            parseInt(total_p) - parseInt(avanceInput)
          );

          $("#compteAvance_p" + idPanier).text(compteAvance_p);

          $("#divavance_p" + idPanier).show();
        }

        if (versement > 0) {
          // alert(11233)

          $("#versement_p" + idPanier).text(versement);

          $("#divversement_p" + idPanier).show();

          $("#rendu_p" + idPanier).text(
            parseInt(versement) - parseInt(apayer_p)
          );

          $("#divrendu_p" + idPanier).show();
        }

        $("#footerContent" + idPanier).show();
      }

      //

      // alert(idPanier+"/"+remise+"/"+compte+"/"+clientInput+"/"+avanceInput+"/"+compteAvance+"/"+versement)

      $.ajax({
        url: "ajax/operationPanier-entrepotAjax.php",

        method: "POST",

        data: {
          //operation: 1,

          idPagnet: idPanier,

          remise: remise,

          compte: compte,

          compteAvance: compteAvance,

          clientInput: clientInput,

          avanceInput: avanceInput,

          versement: versement,

          btnImprimerFacture: "btnImprimerFacture",
        },

        success: function (data) {
          ins = data.split("<>");

          if (data == "1" || data == "") {
          } else if (ins[0] == "0" || ins[0] == 0) {
            // $("#entrepot-venteContent" ).hide();

            $("#panelPanier" + idPanier).attr("class", "panel panel-primary");

            $("#btnContent" + idPanier).hide();

            $("#cache_btn_Terminer" + idPanier).show();

            // confirm("Certains articles ne sont plus disponible");

            $("#entrepot-venteContent").load(
              "ajax/loadContainer-entrepotAjax.php",
              function (data) {
                //get content from PHP page

                // alert(data)
                $(".loading-gif").hide();

                i = 0;
                ins.forEach((e) => {
                  if (i != 0) {
                    // alert(e)
                    // alert($("#quantite"+e ).val())
                    $("#quantite" + e).css("background-color", "#FC8080");
                    // .parent().css("background-color", "green")
                  }
                  i++;
                });

                // $("#entrepot-venteContent" ).show();
                // $("#loading_gif_modal").modal("hide");
              }
            );
          } else {
            $("#msg_info_body").html(data);

            $("#msg_info_1").modal("show");

            $(".img-load-terminer").hide();

            $("#entrepot-venteContent").load(
              "ajax/loadContainer-entrepotAjax.php",
              function (data) {
                //get content from PHP page

                // alert(data)

                $(".loading-gif").hide();

                // $("#loading_gif_modal").modal("hide");
              }
            );
          }
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    }
  });

  $(document).on("click", ".btnAnnulerPagnet_Et", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-annulerPanier").show();

    $(".btnAnnulerPagnet").attr("disabled", "disabled");

    idPanier = $(this).attr("data-idPanier");

    $.ajax({
      url: "ajax/operationPanier-entrepotAjax.php",

      method: "POST",

      data: {
        operation: 1,

        btnAnnulerPagnet: "btnAnnulerPagnet",

        idPagnet: idPanier,
      },

      success: function (data) {
        // alert(data)

        /****************msg_ann_pagnet***************** */

        if (data == "1" || data == "") {
          $("#entrepot-venteContent").load(
            "ajax/loadContainer-entrepotAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              $("#msg_ann_pagnet" + idPanier).modal("hide");

              $(".modal-backdrop").remove();

              $(".img-load-annulerPanier").hide();

              $("body").attr("style", "overflow-y:scroll;");

              // $("#loading_gif_modal").modal("hide");
            }
          );
        } else {
          $("#msg_info_body").html(
            "<p>IMPOSSIBLE.</br></br> Erreur lors de l'annulation du panier n° " +
              idPanier +
              ". </br></br></p>"
          );

          $("#msg_info_1").modal("show");

          $(".img-load-annulerPanier").hide();

          $("body").attr("style", "overflow-y:scroll;");

          $("#entrepot-venteContent").load(
            "ajax/loadContainer-entrepotAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              // $("#loading_gif_modal").modal("hide");
            }
          );
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });

  $(document).on("click", ".btnRetournerPagnetLoader", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-retournerPanier").show();

    $(".btnRetournerPagnet").attr("disabled", "disabled");

    idPanier = $(this).attr("data-idPanier");

    $.ajax({
      url: "ajax/operationPanier-entrepotAjax.php",

      method: "POST",

      data: {
        operation: 1,

        btnRetournerPagnet: "btnRetournerPagnet",

        idPagnet: idPanier,
      },

      success: function (data) {
        // alert(data)

        /****************msg_rtrn_pagnet***************** */

        if (data == "1" || data == "") {
          $("#entrepot-venteContent").load(
            "ajax/loadContainer-entrepotAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              $("#msg_ann_pagnet" + idPanier).modal("hide");

              $(".modal-backdrop").remove();

              $(".img-load-annulerPanier").hide();

              $("body").attr("style", "overflow-y:scroll;");

              // $("#loading_gif_modal").modal("hide");
            }
          );
        } else {
          $("#msg_info_body").html(
            "<p>IMPOSSIBLE.</br></br> Erreur lors de la tentative de retour du panier n° " +
              idPanier +
              ". </br></br></p>"
          );

          $("#msg_info_1").modal("show");

          $(".img-load-retournerPanier").hide();

          $("body").attr("style", "overflow-y:scroll;");

          $("#entrepot-venteContent").load(
            "ajax/loadContainer-entrepotAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              // $("#loading_gif_modal").modal("hide");
            }
          );
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });

  $(document).on("click", ".btnRetourAvant_Et", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-retourAvant").show();

    $(".btnRetourAvant").attr("disabled", "disabled");

    numligne = $(this).attr("data-numligne");

    $.ajax({
      url: "ajax/operationPanier-entrepotAjax.php",

      method: "POST",

      data: {
        operation: 1,

        btnRetourAvant: "btnRetourAvant",

        numligne: numligne,
      },

      success: function (data) {
        // alert(data)

        /****************msg_rtrnAvant_ligne***************** */

        if (data == "1") {
          // alert('data')

          $("#entrepot-venteContent").load(
            "ajax/loadContainer-entrepotAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              $("#msg_rtrnAvant_ligne" + numligne).modal("hide");

              $(".modal-backdrop").remove();

              $(".img-load-retourAvant").hide();

              $("body").attr("style", "overflow-y:scroll;");

              // $("#loading_gif_modal").modal("hide");
            }
          );
        } else {
          $("#msg_info_body").html(
            "<p>IMPOSSIBLE.</br></br> Erreur lors de la tentative de retour du ligne n° " +
              numligne +
              ". </br></br></p>"
          );

          $("#msg_info_1").modal("show");

          $(".img-load-retourAvant").hide();

          $("body").attr("style", "overflow-y:scroll;");

          $("#entrepot-venteContent").load(
            "ajax/loadContainer-entrepotAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              // $("#loading_gif_modal").modal("hide");
            }
          );
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });

  $(document).on("click", ".btnRetourApres_ET", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-retourApres").show();

    $(".btnRetourApres").attr("disabled", "disabled");

    numligne = $(this).attr("data-numligne");

    // numligne=$("#numligne").val();

    idStock = $("#idStock" + numligne).val();

    designation = $("#designation" + numligne).val();

    idPagnet = $("#idPagnet" + numligne).val();

    quantite = $("#quantite" + numligne).val();

    unitevente = $("#unitevente" + numligne).val();

    prixunitevente = $("#prixunitevente" + numligne).val();

    prixtotal = $("#prixtotal" + numligne).val();

    totalp = $("#totalp" + numligne).val();

    // alert(numligne+"/"+idStock+"/"+designation+"/"+idPagnet+"/"+quantite+"/"+unitevente+"/"+prixunitevente+"/"+prixtotal+"/"+totalp)

    $.ajax({
      url: "ajax/operationPanier-entrepotAjax.php",

      method: "POST",

      data: {
        operation: 1,

        btnRetourApres: "btnRetourApres",

        numligne: numligne,

        idStock: idStock,

        designation: designation,

        idPagnet: idPagnet,

        quantite: quantite,

        unitevente: unitevente,

        prixunitevente: prixunitevente,

        prixtotal: prixtotal,

        totalp: totalp,
      },

      success: function (data) {
        // alert(data)

        /********************************* */

        if (data == "1") {
          // alert(1)

          $("#entrepot-venteContent").load(
            "ajax/loadContainer-entrepotAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              $("#msg_rtrnApres_ligne" + numligne).modal("hide");

              $(".modal-backdrop").remove();

              $(".img-load-retourApres").hide();

              // $("#loading_gif_modal").modal("hide");
            }
          );
        } else {
          // alert(2)

          $("#msg_rtrnApres_ligne" + numligne).modal("hide");

          $(".modal-backdrop").remove();

          $("#msg_info_body").html(data);

          $("#msg_info_1").modal("show");

          // $("#loading_gif_modal").modal("hide");

          $(".img-load-retourApres").show();
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });

  $(document).on("click", ".modeEditionBtnETLoader", function (e) {
    var id = $(this).attr("id");

    var page = $("#pageCourante").val();

    result = id.split("-");

    idPanier = result[1];

    $.ajax({
      url: "ajax/operationPanier-entrepotAjax.php",

      method: "POST",

      data: {
        operation: 31,

        idPanier: idPanier,

        modeEditionBtnET: "modeEditionBtnET",
      },

      dataType: "text",

      success: function (data) {
        // alert(data+'77')

        // $("#clientInput"+idPanier).val('');

        // $("#avanceInput"+idPanier).val('');

        if (data == 1) {
          // window.location.reload();

          $("#entrepot-venteContent").load(
            "ajax/loadContainer-entrepotAjax.php",
            { page: page },
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();
              $("#pageCourante").val(page);

              // $("#msg_rtrnApres_ligne"+numligne).modal('hide')

              // $(".modal-backdrop").remove();

              // $(".img-load-retourApres").hide();

              // $("#loading_gif_modal").modal("hide");
            }
          );
        } else {
          $("#msg_edit_pagnet").modal("show");
        }
      },

      error: function () {
        alert("La requête ss");
      },
    });
  });
});

/** Debut Autocomplete Vente Entrepot*/

$(function () {
  $(document).on("keyup", ".codeBarreLigneEt", function (e) {
    e.preventDefault();

    var tabIdPanier = $(this).attr("id");

    tab = tabIdPanier.split("_");

    idPanier = tab[1];

    var query = $(this).val();
    // alert(query)

    if (query.length > 0) {
      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 13,

              query: query,

              idPagnet: idPanier,
            },

            dataType: "json",

            success: function (data) {
              result(
                $.map(data, function (item) {
                  return item;
                })
              );
            },

            error: function (err) {
              alert("la requête ss");
            },
          });
        },
      });

      $(this).focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        // alert(1)
        // var designation = $("#ajouterProdFormEt"+idPanier+" .typeahead li.active").text();

        // tab = designation.split(' => ');

        // designation = tab[0] || $(this).val();

        inputVal = $(this).val();

        if ($.isNumeric(inputVal)) {
          designation = $(this).val();
        } else {
          var designation = $(
            "#ajouterProdFormEt" + idPanier + " .typeahead li.active"
          ).text();

          tab = designation.split(" => ");

          designation = tab[0];
        }
        // /******* modification *******/
        // alert(designation);

        $("#panier_" + idPanier).val("");

        $.ajax({
          url: "ajax/vendreLigneAjax.php",

          method: "POST",

          data: {
            operation: 17,

            designation: designation,

            idPagnet: idPanier,
          },

          success: function (data) {
            // alert(data)

            result = data.split("<>");

            if (result[0] == 1) {
              var depots = JSON.parse(result[12]);

              var options = "";

              $.each(depots, function (idx, depot) {
                options +=
                  "<option value='" +
                  depot[0] +
                  "§" +
                  result[5] +
                  "§" +
                  idPanier +
                  "§1'>" +
                  depot[1] +
                  "</option>";
              });

              if (result[8] == 1) {
                var ligne =
                  "<tr>" +
                  "<td>" +
                  result[2] +
                  "</td>" +
                  "<td><input class='form-control filedReadonly_E" +
                  idPanier +
                  "' id='quantite" +
                  result[5] +
                  "' onkeyup='modif_QuantiteET(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  "," +
                  result[8] +
                  " )' value='1' style='width: 100%' type='number' ></input></td>" +
                  "<td><select id='uniteVente" +
                  result[5] +
                  "' class='form-control filedReadonly_ES" +
                  idPanier +
                  "' onchange='modif_UniteStockET(this.value)' >" +
                  "<option value='" +
                  result[7] +
                  "§" +
                  result[5] +
                  "§" +
                  idPanier +
                  "§1'>" +
                  result[7] +
                  "</option>" +
                  "<option value='Piece§" +
                  result[5] +
                  "§" +
                  idPanier +
                  "§1'>Piece</option>" +
                  "</select>" +
                  "</td>" +
                  "<td><input class='form-control filedReadonly_E" +
                  idPanier +
                  "' id='prixunitevente" +
                  result[5] +
                  "' onkeyup='modif_Prix(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  ")' value='" +
                  result[4] +
                  "' style='width: 100%' type='number'></input></td>" +
                  "<td><select id='depot" +
                  result[5] +
                  "' class='form-control filedReadonly_ES" +
                  idPanier +
                  "' onchange='modif_Depot(this.value)' >" +
                  "<option value='" +
                  result[10] +
                  "§" +
                  result[5] +
                  "§" +
                  idPanier +
                  "§1'>" +
                  result[11] +
                  "</option>" +
                  options +
                  "" +
                  "</select>" +
                  "</td>" +
                  "<td><button type='button' onclick='retour_Produit(" +
                  result[5] +
                  "," +
                  idPanier +
                  ")'	 class='btn btn-warning pull-right btnRtrAvant" +
                  idPanier +
                  "'>" +
                  "<span class='glyphicon glyphicon-remove'></span>Retour" +
                  "</button>" +
                  "</td>" +
                  "</tr>";
              } else {
                var ligne =
                  "<tr>" +
                  "<td>" +
                  result[2] +
                  "</td>" +
                  "<td><input class='form-control filedReadonly_E" +
                  idPanier +
                  "' id='quantite" +
                  result[5] +
                  "' onkeyup='modif_QuantiteET(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  "," +
                  result[8] +
                  " )' value='1' style='width: 100%' type='number' ></input></td>" +
                  "<td><select id='uniteVente" +
                  result[5] +
                  "' class='form-control filedReadonly_ES" +
                  idPanier +
                  "' onchange='modif_UniteStockET(this.value)' >" +
                  "<option value='" +
                  result[7] +
                  "§" +
                  result[5] +
                  "§" +
                  idPanier +
                  "§1'>" +
                  result[7] +
                  "</option>" +
                  "<option value='Demi Gros§" +
                  result[5] +
                  "§" +
                  idPanier +
                  "§2'>Demi Gros</option>" +
                  "<option value='Piece§" +
                  result[5] +
                  "§" +
                  idPanier +
                  "§1'>Piece</option>" +
                  "</select>" +
                  "</td>" +
                  "<td><input class='form-control filedReadonly_E" +
                  idPanier +
                  "' id='prixunitevente" +
                  result[5] +
                  "' onkeyup='modif_Prix(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  ")' value='" +
                  result[4] +
                  "' style='width: 100%' type='number'></input></td>" +
                  "<td><select id='depot" +
                  result[5] +
                  "' class='form-control filedReadonly_ES" +
                  idPanier +
                  "' onchange='modif_Depot(this.value)' >" +
                  "<option value='" +
                  result[10] +
                  "§" +
                  result[5] +
                  "§" +
                  idPanier +
                  "§1'>" +
                  result[11] +
                  "</option>" +
                  options +
                  "" +
                  "</select>" +
                  "</td>" +
                  "<td><button type='button' onclick='retour_Produit(" +
                  result[5] +
                  "," +
                  idPanier +
                  ")'	 class='btn btn-warning pull-right btnRtrAvant" +
                  idPanier +
                  "'>" +
                  "<span class='glyphicon glyphicon-remove'></span>Retour" +
                  "</button>" +
                  "</td>" +
                  "</tr>";
              }

              $("#tablePanier" + idPanier).prepend(ligne);

              $("#panier_" + idPanier).val("");

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] - $("#val_remise" + idPanier).val()
              );
            }

            if (result[0] == 2) {
              $("#entrepot-venteContent").load(
                "ajax/loadContainer-entrepotAjax.php",
                function (data) {
                  //get content from PHP page

                  // alert(data)

                  $(".loading-gif").hide();
                }
              );
            }

            if (result[0] == 3) {
              $("#qte_stock").text(result[1]);

              $("#msg_info_js").modal("show");

              $("#panier_" + idPanier).val("");
            }

            if (result[0] == 5) {
              // alert(1111)

              var ligne =
                "<tr>" +
                "<td><input class='form-control filedReadonly_E" +
                idPanier +
                "' id='quantite" +
                result[5] +
                "' style='width: 100%'  type='text' value='" +
                result[2] +
                "' onkeyup='modif_DesignationBon(this.value," +
                result[5] +
                "," +
                idPanier +
                ")' /></td>" +
                // "<td>" + result[2] + "</td>" +

                "<td>Montant</td>" +
                "<td>Especes</td>" +
                "<td><input class='form-control filedReadonly_E" +
                idPanier +
                "' id='prixunitevente" +
                result[5] +
                "' onkeyup='modif_Prix(this.value," +
                result[5] +
                "," +
                idPanier +
                ")' value='" +
                result[4] +
                "' style='width: 70%' type='number'></input></td>" +
                "<td>" +
                "<button type='button' onclick='retour_Produit(" +
                result[5] +
                "," +
                idPanier +
                ")'	 class='btn btn-warning pull-right'>" +
                "<span class='glyphicon glyphicon-remove'></span>Retour" +
                "</button>" +
                "</td>" +
                "</tr>";

              $("#tablePanier" + idPanier).prepend(ligne);

              $(".btn_Termine_Panier").removeAttr("disabled");

              $("#panier_" + idPanier).val("");

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] - $("#val_remise" + idPanier).val()
              );

              valTotal = result[6] - $("#val_remise" + idPanier).val();

              valApayer = result[10] - $("#val_remise" + idPanier).val();

              $("#somme_TotalCN" + idPanier).text(valTotal.toFixed(2));

              $("#somme_ApayerCN" + idPanier).text(valApayer.toFixed(2));
            }

            //console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });

        idFormParent = $("#panier_" + idPanier)
          .parent()
          .attr("id");

        // alert(idFormParent+'/////////')

        // setTimeout(() => {

        $("#" + idFormParent + " .typeahead").html("");
      }
    } else {
      idFormParent = $("#panier_" + idPanier)
        .parent()
        .attr("id");

      // alert(idFormParent+'/////////')

      // setTimeout(() => {

      $("#" + idFormParent + " .typeahead").html("");
    }
  });
});

/** Fin Autocomplete Vente Entrepot*/

/** Debut Rechercher le Prix d'un Produit*/

$(function () {
  $(document).on("keyup", "#designationInfo", function (e) {
    e.preventDefault();

    var query = $("#designationInfo").val();

    if (query.length > 0 || query.length != "") {
      $("#designationInfo").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/designationInfo.php",

            method: "POST",

            data: {
              query: query,
            },

            dataType: "json",

            success: function (data) {
              result(
                $.map(data, function (item) {
                  return item;
                })
              );
            },

            error: function () {
              //alert("La requête ss");
            },
          });
        },
      });

      $("#designationInfo").focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        var designation = $(
          "#searchDesignationForm .typeahead li.active"
        ).text();

        $("#designationInfo").val(designation);
      }

      $(document).on(
        "click",
        '#searchDesignationForm .typeahead li a[class="dropdown-item"]',
        function (e) {
          e.preventDefault();

          var designation = $(
            "#searchDesignationForm .typeahead li.active"
          ).text();

          $("#designationInfo").val(designation);
        }
      );
    } else {
    }
  });
});

/** Fin Rechercher le Prix d'un Produit*/
