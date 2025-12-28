/*************** Début modification Catalogue  details Pharmacie***************/
$(document).ready(function () {
  nbEntree = $("#nbEntreePTrf").val();

  $("#resultsProductTrf").load("ajax/listeProduit-EntrepotStockAjax.php", {
    operation: 1,
    nbEntree: nbEntree,
    query: "",
    cb: "",
  }); //load initial records
  $(".loading-div").hide();
  choixDepot();
  //executes code below when user click on pagination links
  $("#resultsProductTrf").on("click", ".pagination a", function (e) {
    // $("#resultsProductsPDesign").on( "click", function (e){
    e.preventDefault();
    $(".loading-div").show(); //show loading element
    // page = page+1; //get page number from link
    page = $(this).attr("data-page"); //get page number from link
    //  alert(page)n
    nbEntreePh = $("#nbEntreePTrf").val();
    query = $("#searchInputPTrf").val();

    if (query.length == 0) {
      $("#resultsProductTrf").load(
        "ajax/listeProduit-EntrepotStockAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: "", cb: "" },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
          choixDepot();
        }
      );
    } else {
      $("#resultsProductTrf").load(
        "ajax/listeProduit-EntrepotStockAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: query, cb: "" },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
          choixDepot();
        }
      );
    }
    // $("#resultsProductsPDesign").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function () {
  // alert(4)
  $("#searchInputPTrf").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputPTrf").val();
    nbEntree = $("#nbEntreePTrf").val();
    //id = $('#idCDPH').val();

    var keycode = e.keyCode ? e.keyCode : e.which;
    if (keycode == "13") {
      t = 1; // code barre
      // inputVal = $('#searchInputPhD').val();
      // if ($.isNumeric(inputVal)) {
      if (query.length > 0) {
        $("#resultsProductTrf").load(
          "ajax/listeProduit-EntrepotStockAjax.php",
          { operation: 3, nbEntree: nbEntree, query: query, cb: t }
        ); //load initial records
        $(".loading-div").hide();
        choixDepot();
      } else {
        $("#resultsProductTrf").load(
          "ajax/listeProduit-EntrepotStockAjax.php",
          { operation: 3, nbEntree: nbEntree, query: "", cb: t }
        ); //load initial records
        $(".loading-div").hide();
        choixDepot();
      }
    } else {
      t = 0; // no code barre
      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductTrf").load(
            "ajax/listeProduit-EntrepotStockAjax.php",
            { operation: 3, nbEntree: nbEntree, query: query, cb: t }
          ); //load initial records
          $(".loading-div").hide();
          choixDepot();
        } else {
          $("#resultsProductTrf").load(
            "ajax/listeProduit-EntrepotStockAjax.php",
            { operation: 3, nbEntree: nbEntree, query: "", cb: t }
          ); //load initial records
          $(".loading-div").hide();
          choixDepot();
        }
      }, 100);
    }
  });

  $("#nbEntreePTrf").on("change", function (e) {
    e.preventDefault();

    nbEntree = $("#nbEntreePTrf").val();
    query = $("#searchInputPTrf").val();

    if (query.length == 0) {
      $("#resultsProductTrf").load("ajax/listeProduit-EntrepotStockAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: "",
        cb: "",
      }); //load initial records
      $(".loading-div").hide();
      choixDepot();
    } else {
      $("#resultsProductTrf").load("ajax/listeProduit-EntrepotStockAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: query,
        cb: "",
      }); //load initial records
      $(".loading-div").hide();
      choixDepot();
    }

    // $("#resultsProductsPDesign" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
  });
});

/*************    TRI    ******************/
$(document).ready(function () {
  // alert(1500)
  // var page = 0;
  //executes code below when user click on pagination links
  //1=croissant et 0=decroissant
  tri = 0;
  $(document).on("click", "#resultsProductTrf th", function (e) {
    e.preventDefault();
    // alert(12)
    query = $("#searchInputPTrf").val();
    nbEntree = $("#nbEntreePTrf").val();
    // $("#resultsProductsPDesign" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":1, "query":""}); //load initial records

    if (tri == 1) {
      // alert(triPH)
      if (query.length == 0) {
        $("#resultsProductTrf").load(
          "ajax/listeProduit-EntrepotStockAjax.php",
          { operation: 2, nbEntree: nbEntree, tri: 1, query: "", cb: "" }
        ); //load initial records
        $(".loading-div").hide();
        choixDepot();
      } else {
        $("#resultsProductTrf").load(
          "ajax/listeProduit-EntrepotStockAjax.php",
          { operation: 2, nbEntree: nbEntree, tri: 1, query: query, cb: "" }
        ); //load initial records
        $(".loading-div").hide();
        choixDepot();
      }

      tri = 0;
      // alert(triPH)
    } else {
      // alert(triPH)
      if (query.length == 0) {
        $("#resultsProductTrf").load(
          "ajax/listeProduit-EntrepotStockAjax.php",
          { operation: 2, nbEntree: nbEntree, tri: 0, query: "", cb: "" }
        ); //load initial records
        $(".loading-div").hide();
        choixDepot();
      } else {
        $("#resultsProductTrf").load(
          "ajax/listeProduit-EntrepotStockAjax.php",
          { operation: 2, nbEntree: nbEntree, tri: 0, query: query, cb: "" }
        ); //load initial records
        $(".loading-div").hide();
        choixDepot();
      }

      tri = 1;
      // alert(triPH)
    }
  });
});

/*************** Fin modification Catalogue details Pharmacie***************/

/*************** Début modification Catalogue  details Pharmacie***************/
$(document).ready(function () {
  nbEntree = $("#nbEntreeStockTrf").val();

  $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
    operation: 1,
    nbEntree: nbEntree,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links
  $("#resultsStockTrf").on("click", ".pagination a", function (e) {
    // $("#resultsProductsPDesign").on( "click", function (e){
    e.preventDefault();
    $(".loading-div").show(); //show loading element
    // page = page+1; //get page number from link
    page = $(this).attr("data-page"); //get page number from link
    //  alert(page)n
    nbEntreePh = $("#nbEntreeStockTrf").val();
    query = $("#searchInputStockTrf").val();

    if (query.length == 0) {
      $("#resultsStockTrf").load(
        "ajax/listerTransfert-StockAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: "", cb: "" },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsStockTrf").load(
        "ajax/listerTransfert-StockAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: query, cb: "" },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }
    // $("#resultsProductsPDesign").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/
$(document).ready(function () {
  // alert(4)
  $("#searchInputStockTrf").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputStockTrf").val();
    nbEntree = $("#nbEntreeStockTrf").val();
    //id = $('#idCDPH').val();

    var keycode = e.keyCode ? e.keyCode : e.which;
    if (keycode == "13") {
      t = 1; // code barre
      // inputVal = $('#searchInputPhD').val();
      // if ($.isNumeric(inputVal)) {
      if (query.length > 0) {
        $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
          operation: 3,
          nbEntree: nbEntree,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
          operation: 3,
          nbEntree: nbEntree,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      t = 0; // no code barre
      setTimeout(() => {
        if (query.length > 0) {
          // alert(2222)
          $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
            operation: 3,
            nbEntree: nbEntree,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
            operation: 3,
            nbEntree: nbEntree,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeStockTrf").on("change", function (e) {
    e.preventDefault();

    nbEntree = $("#nbEntreeStockTrf").val();
    query = $("#searchInputStockTrf").val();

    if (query.length == 0) {
      $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsProductsPDesign" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
  });
});

/*************    TRI    ******************/
$(document).ready(function () {
  // alert(1500)
  // var page = 0;
  //executes code below when user click on pagination links
  //1=croissant et 0=decroissant
  tri = 0;
  $(document).on("click", "#resultsStockTrf th", function (e) {
    e.preventDefault();
    // alert(12)
    query = $("#searchInputStockTrf").val();
    nbEntree = $("#nbEntreeStockTrf").val();
    // $("#resultsProductsPDesign" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":1, "query":""}); //load initial records

    if (tri == 1) {
      // alert(triPH)
      if (query.length == 0) {
        $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      tri = 0;
      // alert(triPH)
    } else {
      // alert(triPH)
      if (query.length == 0) {
        $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsStockTrf").load("ajax/listerTransfert-StockAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      tri = 1;
      // alert(triPH)
    }
  });
});
/*************** Fin modification Catalogue details Pharmacie***************/

function choixDepot() {
  $.ajax({
    url: "ajax/operationStockAjax.php",

    method: "POST",

    data: {
      operation: 2,
    },

    success: function (data) {
      // alert(data)
      var data = JSON.parse(data);

      var taille = data.length;
      $(".listeDepot").html("");

      for (var i = 0; i < taille; i++) {
        // var tab = data[i];

        res = data[i].split("<>");

        // alert(data[i])

        $(".listeDepot").append(
          "<option value='" + res[0] + "'>" + res[1] + "</option>"
        );
      }
    },

    error: function () {
      confirm("Problème de connexion ");
    },

    dataType: "text",
  });
}

/**Debut ajouter Stock Entrepot**/

function trf_Stock_Depot(idStock) {
  var quantite = $(".quantiteAStocke-" + idStock).val();

  var idEntrepot = $("#listeDepot-" + idStock).val();

  var dateJour = $("#jour_date").val();

  $("#btn_TrfStock-" + idStock).prop("disabled", true);

  if (idEntrepot != "" && idEntrepot != 0 && idEntrepot != null) {
    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 28,

        idStock: idStock,

        idEntrepot: idEntrepot,

        quantite: quantite,

        dateJour: dateJour,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr><td>" +
            tab[2] +
            "</td><td>" +
            tab[7] +
            "</td><td> SANS DEPOT </td><td>" +
            tab[6] +
            "</td><td>" +
            tab[1] +
            "</td><td>" +
            quantite +
            "</td><td>" +
            tab[8] +
            "</td><td>En cours ...</td></tr>";

          $("table.tableStockTrf").prepend(ligne);

          $(".quantiteAStocke-" + idStock).prop("disabled", true);
        } else {
          alert(data);
        }

        //console.log(data);
      },

      error: function () {
        confirm("Problème de connexion ");
      },

      dataType: "text",
    });
  } else {
    $("#msg_info_Depot").modal("show");
    $("#quantiteAStocke-" + idStock).val("");
  }
}

function btn_trf_Entrepot_Depot(idEntrepotStock) {
  $("#tftidDesignation").val("");
  $("#tftidEntrepot").val("");
  $("#tftDestinationQte").val("");
  $("#tftDestination").val("");
  $("#tftOrigine").val("");
  $("#tftOrigineQte").val("");

  $("#btn_tft_EntrepotDepot").prop("disabled", false);

  $.ajax({
    url: "ajax/operationStockAjax.php",

    method: "POST",

    data: {
      operation: 4,

      idEntrepotStock: idEntrepotStock,
    },

    success: function (data) {
      var data = JSON.parse(data);

      var taille = data.length;
      $(".listeEntrepot").html("");
      $(".listeEntrepot").append("<option value=''>----------</option>");
      $(".listeEntrepot").append("<option value='0'>Sans Depot</option>");
      for (var i = 0; i < taille; i++) {
        res = data[i].split("<>");

        $(".listeEntrepot").append(
          "<option value='" + res[0] + "'>" + res[1] + "</option>"
        );

        $("#tftDesignation").val(res[2]);
        $("#tftOrigine").val(res[3]);
        $("#tftOrigineQte").val(res[4]);
        $("#tftidDesignation").val(res[5]);
        $("#tftidEntrepot").val(res[6]);
      }
    },

    error: function () {
      confirm("Problème de connexion ");
    },

    dataType: "text",
  });

  $("#transfertStockModal").modal("show");
}

$(function () {
  $("#btn_tft_EntrepotDepot").click(function () {
    var idDesignation = $("#tftidDesignation").val();

    var idDepot = $("#tftidEntrepot").val();

    var quantite = $("#tftDestinationQte").val();

    var idEntrepot = $("#tftDestination").val();

    var dateJour = $("#date_jour").val();

    $("#btn_tft_EntrepotDepot").prop("disabled", true);

    if (
      quantite != "" &&
      quantite != 0 &&
      idEntrepot != "" &&
      idEntrepot != null
    ) {
      $.ajax({
        url: "ajax/ajouterLigneAjax.php",

        method: "POST",

        data: {
          operation: 100,

          idDesignation: idDesignation,

          idDepot: idDepot,

          idEntrepot: idEntrepot,

          quantite: quantite,

          dateJour: dateJour,
        },

        success: function (data) {
          tab = data.split("<>");

          if (tab[0] == 1) {
            var ligne =
              "<tr><td>" +
              tab[1] +
              "</td><td> </td><td>" +
              tab[2] +
              "</td><td>" +
              tab[3] +
              "</td><td>" +
              tab[4] +
              "</td><td>" +
              quantite +
              "</td><td> </td><td>En cours ...</td></tr>";

            $("table.tableStockTrf").prepend(ligne);

            $("#transfertStockModal").modal("hide");
          } else {
            alert(data);
          }
        },

        error: function () {
          confirm("Problème de connexion ");
        },

        dataType: "text",
      });
    } else {
      $("#msg_info_Depot").modal("show");
      $("#quantiteAStocke-" + idStock).val("");
    }
  });
});

/**Fin ajouter Stock Entrepot**/

var depotArray = [];

$(function () {
  $(document).on("keyup", "#tftDesignation", function (e) {
    e.preventDefault();

    var query = $("#tftDesignation").val();

    if (query.length > 0) {
      $("#tftDesignation").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/operationTransfertAjax.php",

            method: "POST",

            data: {
              getReference: "getReference",

              query: query,
            },

            dataType: "json",

            success: function (data) {
              console.log(data);

              result(
                $.map(data, function (item) {
                  return item;
                })
              );
            },

            error: function (err) {
              console.log(err);
            },
          });
        },
      });
    }

    $("#tftDesignation").focus();

    /*********** Modification **************/

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // var text = $("#record").find("td:eq(1) .typeahead li.active").text();
      var text = $("#tftDesignationDiv .typeahead li.active").text();
      $("#tftDesignation").val(text.split(" . ")[1]);
      // alert(text);
      $("#idDesHidden").val(text.split(" . ")[0]);
      getStockDispo(text.split(" . ")[0]);
    }
  });

  $(document).on(
    "click",
    "#tftDesignationDiv .typeahead li.active",
    function (e) {
      e.preventDefault();

      var text = $("#tftDesignationDiv .typeahead li.active").text();
      $("#tftDesignation").val(text.split(" . ")[1]);
      // alert(text.split(" . ")[0]);
      $("#idDesHidden").val(text.split(" . ")[0]);

      getStockDispo(text.split(" . ")[0]);
    }
  );
});

function getStockDispo(idDesignation) {
  $.ajax({
    url: "ajax/operationTransfertAjax.php",

    method: "POST",

    data: {
      getStockDispo: "getStockDispo",

      idDesignation: idDesignation,
    },

    success: function (data) {
      console.log(JSON.parse(data));
      data = JSON.parse(data);
      content = "";
      data.forEach((e) => {
        depotArray.push(e);
        content += "<label>" + e + "</label><br>";
      });

      getEntrepot();

      $("#stockDisponible").html(content);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function getEntrepot() {
  $.ajax({
    url: "ajax/operationTransfertAjax.php",

    method: "POST",

    data: {
      getEntrepot: "getEntrepot",
    },

    success: function (data) {
      console.log(JSON.parse(data));
      data = JSON.parse(data);
      content = "";
      data.forEach((e) => {
        idEntrepot = e.split(" ==> ")[0];
        nomEntrepot = e.split(" ==> ")[1];
        content +=
          "<option value='" + idEntrepot + "'>" + nomEntrepot + "</option>";
      });

      $(".listeEntrepot").html(content);
    },

    error: function () {
      confirm("Problème de connexion ");
    },

    dataType: "text",
  });
}

function validateTransfert() {
  designation = $("#tftDesignation").val();
  idDesignation = $("#idDesHidden").val();
  depotOrigine = $("#depotOrigine").val();
  depotDestination = $("#depotDestination").val();
  depotDestinationQte = $("#depotDestinationQte").val();

  depotOrigineName = $("#depotOrigine option:selected").text();
  depotDestinationName = $("#depotDestination option:selected").text();
  isInferieur = 0;
  // alert(depotDestinationName + "/" + depotOrigineName);

  depotArray.forEach((d) => {
    if (d.split(" ==> ")[0] === depotOrigineName) {
      if (parseInt(d.split(" ==> ")[1]) < parseInt(depotDestinationQte)) {
        isInferieur = 1;
      }
    }
  });

  $("#btn_tft_Entrepot").prop("disabled", true);

  setTimeout(() => {
    if (designation == "" || depotDestinationQte == "") {
      confirm("Tous les champs sont obligatoire ");
    } else {
      if (depotDestinationName === depotOrigineName) {
        confirm(
          "Le dépôt de destination doit être différent du dépôt d'origine "
        );
      } else if (isInferieur == 1) {
        confirm("La quantité saisie est supérieur à la quantité restante. ");
      } else {
        $.ajax({
          url: "ajax/operationTransfertAjax.php",

          method: "POST",

          data: {
            validateTransfert: "validateTransfert",
            designation: designation,
            idDesignation: idDesignation,
            depotOrigine: depotOrigine,
            depotDestination: depotDestination,
            depotDestinationQte: depotDestinationQte,
            depotOrigineName: depotOrigineName,
            depotDestinationName: depotDestinationName,
          },

          success: function (data) {
            // console.log(JSON.parse(data));

            data = data.split(" ==> ");
            var ligne =
              "<tr><td>" +
              data[0] +
              "</td><td>" +
              data[1] +
              "</td><td>" +
              data[2] +
              "</td><td>" +
              data[3] +
              "</td><td>" +
              data[4] +
              "</td><td>" +
              data[5] +
              "</td><td>" +
              data[6] +
              "</td><td>En cours ...</td></tr>";

            $("table.tableStockTrf").prepend(ligne);

            designation = $("#tftDesignation").val("");
            depotDestinationQte = $("#depotDestinationQte").val("");
            $("#btn_tft_Entrepot").prop("disabled", false);

            //   $("#transfertStockModal").modal("hide");
          },

          error: function () {
            confirm("Problème de connexion ");
          },

          dataType: "text",
        });
      }
    }
  }, 500);
}
