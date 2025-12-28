$(function () {
  /********   RECHERCHE et NOMBRE D'ENTREES   ******/
  $(document).ready(function () {
    $("#searchInputEnreg").on("keyup", function (e) {
      e.preventDefault();

      // query = e.target.value;
      idEnreg = $("#idEnregDetail").val();
      query = $("#searchInputEnreg").val();

      // query = $('#searchInputEnreg').val()
      // console.log(query)

      nbEntreeEnreg = $("#nbEntreeEnreg").val();

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        // alert(1111)

        if (query.length > 0) {
          $("#resultsDetailsEnreg").load(
            "cargo/ajax/detailEnregistrementAjax.php",
            {
              idEnreg: idEnreg,
              operation: 3,
              nbEntreeEnreg: nbEntreeEnreg,
              query: query,
            }
          ); //load initial records
        } else {
          $("#resultsDetailsEnreg").load(
            "cargo/ajax/detailEnregistrementAjax.php",
            {
              idEnreg: idEnreg,
              operation: 3,
              nbEntreeEnreg: nbEntreeEnreg,
              query: "",
            }
          ); //load initial records
        }
      } else {
        // alert(2222)

        setTimeout(() => {
          if (query.length > 0) {
            $("#resultsDetailsEnreg").load(
              "cargo/ajax/detailEnregistrementAjax.php",
              {
                idEnreg: idEnreg,
                operation: 3,
                nbEntreeEnreg: nbEntreeEnreg,
                query: query,
              }
            ); //load initial records
          } else {
            $("#resultsDetailsEnreg").load(
              "cargo/ajax/detailEnregistrementAjax.php",
              {
                idEnreg: idEnreg,
                operation: 3,
                nbEntreeEnreg: nbEntreeEnreg,
                query: "",
              }
            ); //load initial records
          }
        }, 100);
      }
    });

    $("#nbEntreeEnreg").on("change", function (e) {
      e.preventDefault();

      nbEntreeEnreg = $("#nbEntreeEnreg").val();
      idEnreg = $("#idEnregDetail").val();

      query = $("#searchInputEnreg").val();

      if (query.length == 0) {
        $("#resultsDetailsEnreg").load(
          "cargo/ajax/detailEnregistrementAjax.php",
          {
            idEnreg: idEnreg,
            operation: 4,
            nbEntreeEnreg: nbEntreeEnreg,
            query: "",
          }
        ); //load initial records
      } else {
        $("#resultsDetailsEnreg").load(
          "cargo/ajax/detailEnregistrementAjax.php",
          {
            idEnreg: idEnreg,
            operation: 4,
            nbEntreeEnreg: nbEntreeEnreg,
            query: query,
          }
        ); //load initial records
      }
    });
  });
});

function detailEnregistrement(idEnreg) {
  $("#idEnregDetail").val(idEnreg);
  $("#numEnregistrementDetail").text(idEnreg);

  //   if (etat == "1") {
  //     $("#etatContainerDetail").text(" Non Payer");
  //     $("#etatContainerDetail").attr("class", "alert alert-danger");
  //   } else if (etat == "2") {
  //     $("#etatContainerDetail").text(" Avance");
  //     $("#etatContainerDetail").attr("class", "alert alert-info");
  //   } else if (etat == "3") {
  //     $("#etatContainerDetail").text(" Payer");
  //     $("#etatContainerDetail").attr("class", "alert alert-success");
  //     $("#etatContainerDetail").addClass("fa fa-check");
  //   }

  //   $("#etat").val(etat);
  //   $("#resultsDetailsContainer").html("");
  //   $("#searchInputContainer").val("");

  /*************** Début lister prix boutique ***************/
  // alert(etat)
  $(document).ready(function () {
    nbEntreeEnreg = $("#nbEntreeEnreg").val();

    $("#resultsDetailsEnreg").load("cargo/ajax/detailEnregistrementAjax.php", {
      idEnreg: idEnreg,
      operation: 1,
      nbEntreeEnreg: nbEntreeEnreg,
      query: "",
    }); //load initial records

    //executes code below when user click on pagination links

    $("#resultsDetailsEnreg").on("click", ".pagination a", function (e) {
      // $("#resultsDetailsEnreg").on( "click", function (e){

      e.preventDefault();

      $(".loading-div").show(); //show loading element

      // page = page+1; //get page number from link

      page = $(this).attr("data-page"); //get page number from link

      //  alert(page)

      nbEntreeEnreg = $("#nbEntreeEnreg").val();

      query = $("#searchInputEnreg").val();

      if (query.length == 0) {
        $("#resultsDetailsEnreg").load(
          "cargo/ajax/detailEnregistrementAjax.php",
          {
            idEnreg: idEnreg,
            page: page,
            operation: 1,
            nbEntreeEnreg: nbEntreeEnreg,
            query: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      } else {
        $("#resultsDetailsEnreg").load(
          "cargo/ajax/detailEnregistrementAjax.php",
          {
            idEnreg: idEnreg,
            page: page,
            operation: 1,
            nbEntreeEnreg: nbEntreeEnreg,
            query: query,
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      }
    });
  });
  
  $("#detailEnregitrementModal").modal("show");
}

function detailGlobalContainer(idContainer, container) {
  numContainer = idContainer;

  $("#numContainerDetail").text(container);
  $("#idContainerHidden").val(idContainer);

  /*************** Début lister prix boutique ***************/
  // alert(etat)

  $(document).ready(function () {
    nbEntreeContainer = $("#nbEntreeContainer").val();

    $("#resultsDetailsContainer").load(
      "cargo/ajax/detailGlobalContainerAjax.php",
      {
        numContainer: numContainer,
        operation: 1,
        nbEntreeContainer: nbEntreeContainer,
        query: "",
      }
    ); //load initial records

    //executes code below when user click on pagination links

    $("#resultsDetailsContainer").on("click", ".pagination a", function (e) {
      // $("#resultsDetailsContainer").on( "click", function (e){

      e.preventDefault();

      $(".loading-div").show(); //show loading element

      // page = page+1; //get page number from link

      page = $(this).attr("data-page"); //get page number from link

      //  alert(page)

      nbEntreeContainer = $("#nbEntreeContainer").val();

      query = $("#searchInputContainer").val();

      if (query.length == 0) {
        $("#resultsDetailsContainer").load(
          "cargo/ajax/detailGlobalContainerAjax.php",
          {
            numContainer: numContainer,
            page: page,
            operation: 1,
            nbEntreeContainer: nbEntreeContainer,
            query: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      } else {
        $("#resultsDetailsContainer").load(
          "cargo/ajax/detailGlobalContainerAjax.php",
          {
            numContainer: numContainer,
            page: page,
            operation: 1,
            nbEntreeContainer: nbEntreeContainer,
            query: query,
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      }
    });
  });
  detailDepensePorteur(idContainer);

  $("#detailContainerModal").modal("show");
}

function detailContainer(idContainer, container, etat) {
  numContainer = idContainer;

  $("#numContainerDetail").text(container);

  if (etat == "1") {
    $("#etatContainerDetail").text(" Non Payer");
    $("#etatContainerDetail").attr("class", "alert alert-danger");
  } else if (etat == "2") {
    $("#etatContainerDetail").text(" Avance");
    $("#etatContainerDetail").attr("class", "alert alert-info");
  } else if (etat == "3") {
    $("#etatContainerDetail").text(" Payer");
    $("#etatContainerDetail").attr("class", "alert alert-success");
    $("#etatContainerDetail").addClass("fa fa-check");
  }

  $("#etat").val(etat);
  $("#resultsDetailsContainer").html("");
  $("#searchInputContainer").val("");

  /*************** Début lister prix boutique ***************/
  // alert(etat)

  $(document).ready(function () {
    nbEntreeContainer = $("#nbEntreeContainer").val();

    $("#resultsDetailsContainer").load("cargo/ajax/detailContainerAjax.php", {
      etat: etat,
      numContainer: numContainer,
      operation: 1,
      nbEntreeContainer: nbEntreeContainer,
      query: "",
    }); //load initial records

    //executes code below when user click on pagination links
    detailDepensePorteur(idContainer)
  });

  $("#detailContainerModal").modal("show");
}

function detailGlobalEntrepot(idEntrepot, nomEntrepot) {
  // idEntrepot = idContainer;

  $("#nomEntrepotDetail").text(nomEntrepot);

  /*************** Début lister prix boutique ***************/
  // alert(etat)

  $(document).ready(function () {
    // nbEntreeContainer = $("#nbEntreeContainer").val();

    $("#resultsDetailsEntrepot").load(
      "cargo/ajax/detailGlobalEntrepotAjax.php",
      {
        idEntrepot: idEntrepot,
        operation: 1,
        query: "",
      }
    ); //load initial records

    //executes code below when user click on pagination links

  });

  $("#detailEntrepotModal").modal("show");
}

function detailVol(idAvion, numVol, etat) {
  $("#numVolDetail").text(numVol);

  if (etat == "1") {
    $("#etatContainerDetail").text(" Non Payer");
    $("#etatContainerDetail").attr("class", "alert alert-danger");
  } else if (etat == "2") {
    $("#etatContainerDetail").text(" Avance");
    $("#etatContainerDetail").attr("class", "alert alert-info");
  } else if (etat == "3") {
    $("#etatContainerDetail").text(" Payer");
    $("#etatContainerDetail").attr("class", "alert alert-success");
    $("#etatContainerDetail").addClass("fa fa-check");
  }

  $("#etat").val(etat);
  $("#resultsDetailsVol").html("");

  /*************** Début lister prix boutique ***************/
  // alert(etat)

  $(document).ready(function () {
    $("#resultsDetailsVol").load("cargo/ajax/detailVolAjax.php", {
      etat: etat,
      idAvion: idAvion,
      operation: 1,
      query: "",
    }); //load initial records

    detailDepensePorteur(idAvion);

    //executes code below when user click on pagination links
  });

  $("#detailVolModal").modal("show");
}

function detailGlobalVol(idAvion, numVol) {
  $("#numVolDetail").text(numVol);
  $("#idAvionHidden").val(idAvion);
  // alert(7777);
  /*************** Début lister prix boutique ***************/
  // alert(etat)

  $(document).ready(function () {
    $("#resultsDetailsVol").load("cargo/ajax/detailGlobalVolAjax.php", {
      idAvion: idAvion,
      operation: 1,
      query: "",
    }); //load initial records

    //executes code below when user click on pagination links
  });

  detailDepensePorteur(idAvion);
  $("#detailVolModal").modal("show");
}

function editVol(idAvion) {
  $("#idAvion_edit").val(idAvion);

  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      getAvion: "getAvion",

      idAvion: idAvion,
    },

    dataType: "json",

    success: function (data) {
      // alert(data)
      // console.log(data);
      // $("#idContainer_edit").show();
      $("#numVol_edit").prop("disabled", true);
      $("#numVol_edit").val(data.numVol);
      $("#numBooking_edit").val(data.numBooking);
      $("#nombrePieces_edit").val(data.nombrePieces);
      $("#dateDepart_edit").val(data.dateDepart);
      $("#dateArrivee_edit").val(data.dateArrivee);

      $("#editVolModal").modal("show");
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function confirmEditVol() {
  idAvion = $("#idAvion_edit").val();
  numVol = $("#numVol_edit").val();
  numBooking = $("#numBooking_edit").val();
  nombrePieces = $("#nombrePieces_edit").val();
  dateDepart = $("#dateDepart_edit").val();
  dateArrivee = $("#dateArrivee_edit").val();

  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      confirmEditvol: "confirmEditvol",
      idAvion: idAvion,
      numVol: numVol,
      numBooking: numBooking,
      nombrePieces: nombrePieces,
      dateDepart: dateDepart,
      dateArrivee: dateArrivee,
    },

    dataType: "text",

    success: function (data) {
      // alert(data)
      console.log(data);

      if (data == 1 || data == "1") {
        $("#editVolModal").modal("hide");

        $("#tr" + idAvion).hide(1000);
        $("#tr" + idAvion).show(500);

        $("#tr" + idAvion)
          .children("td")
          .eq(1)
          .text(numVol);
        $("#tr" + idAvion)
          .children("td")
          .eq(2)
          .text(numBooking);
        $("#tr" + idAvion)
          .children("td")
          .eq(3)
          .text(nombrePieces);
        $("#tr" + idAvion)
          .children("td")
          .eq(4)
          .text(dateDepart);
        $("#tr" + idAvion)
          .children("td")
          .eq(5)
          .text(dateArrivee);
      } else {
        confirm(
          "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
            data
        );
      }
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function deleteVol(idAvion) {
  $("#idAvion_delete").val(idAvion);

  $("#deleteVolModal").modal("show");
}

function confirmDeleteVol() {
  idAvion = $("#idAvion_delete").val();
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      confirmDeleteVol: "confirmDeleteVol",

      idAvion: idAvion,
    },

    dataType: "text",

    success: function (data) {
      if (data == 1 || data == "1") {
        $("#tr" + idAvion).css("background-color", "red");
        $("#tr" + idAvion).hide(1000);

        $("#deleteVolModal").modal("hide");
      } else {
        confirm(
          "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
            data
        );
      }
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

$(function () {
  $(document).on("keyup", "#nature_bagages", function (e) {
    e.preventDefault();
    // alert(1)
    // idPanier = $(this).attr('data-idPanier');

    var query = $(this).val();
    // $("#emplacementAutocomplete").css("background", "white");

    if (query.length > 0) {
      $("#nature_bagages").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "cargo/ajax/operationCargoAjax.php",

            method: "POST",

            data: {
              autocompleteNatBagage: "autocompleteNatBagage",

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

    $("#nature_bagages").focus();

    /*********** Modification **************/

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // var text = $("#record").find("td:eq(1) .typeahead li.active").text();
      var text = $("#natTd .typeahead li.active").text();
      $(this).val(text.split(" . ")[1]);
    }
  });

  $(document).on("click", "#tabCbm .typeahead li.active", function (e) {
    e.preventDefault();

    var text = $("#natTd .typeahead li.active").text();
    $(this).val(text.split(" . ")[1]);
  });
});

/***** autocomplete changement container *****/
$(function () {
  $(document).on("keyup", ".dechargeAutocomplete", function (e) {
    e.preventDefault();
    // idPanier = $(this).attr('data-idPanier');

    var query = $(this).val();
    if (query.length > 0) {
      // console.log(1)
      // alert(1)

      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "cargo/ajax/operationCargoAjax.php",

            method: "POST",

            data: {
              emplacementDecharge: "emplacementDecharge",

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

    $(".dechargeAutocomplete").focus();

    /*********** Modification **************/

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // var text = $("#record").find("td:eq(1) .typeahead li.active").text();
      var text = $("#divAddArrivageEmplacement .typeahead li.active").text();
      $(this).val(text.split(" . ")[1]);
      $("#dechargeHide").val(text);
    }
  });

  $(document).on(
    "click",
    "#divAddArrivageEmplacement .typeahead li.active",
    function (e) {
      e.preventDefault();

      var text = $("#divAddArrivageEmplacement .typeahead li.active").text();
      $(this).val(text.split(" . ")[1]);
      $("#dechargeHide").val(text);
    }
  );
});

function recuEnregistrement(idEnreg) {
  idEntrepot = $("#dechargeHide").val().split(" . ")[0];
  nomEntrepot = $("#dechargeHide").val().split(" . ")[1];
  if (
    idEntrepot != null &&
    idEntrepot != "" &&
    nomEntrepot != null &&
    nomEntrepot != ""
  ) {
    $.confirm({
      title: "Confirmer!",
      content: "Confimer la récéption!",
      buttons: {
        confirm: function () {
          $.ajax({
            url: "cargo/ajax/operationCargoAjax.php",

            method: "POST",

            data: {
              recuEnregistrement: "recuEnregistrement",

              idEnreg: idEnreg,

              idEntrepot: idEntrepot,
            },

            dataType: "text",

            success: function (data) {
              if (data == 1 || data == "1") {
                $("#ent" + idEnreg).text(nomEntrepot);
                $("#btnRecuEnreg" + idEnreg).prop("disabled", true);
              } else {
                confirm(
                  "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
                    data
                );
              }
            },

            error: function (err) {
              console.log(err);
              confirm("Erreur : Connexion impossible. Rééssayer SVP.");
            },
          });
        },
        cancel: function () {},
      },
    });
  } else {
    confirm(
      "Une erreur est survenue lors de l'opération : veuiller renseigner correctement l'entrepot de déchargement svp!"
    );
  }
}
function imgNew_Enreg(idEnreg) {
  $("#id_Upd_ND").val(idEnreg);
  $("#imageNvDesignation").modal("show");
}

function imgEX_Enreg(idEnreg) {
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      getImgEnreg: "getImgEnreg",

      idEnreg: idEnreg,
    },

    dataType: "text json",

    success: function (data) {
      console.log(data);

      $("#id_Upd_Ex").val(idEnreg);

      $("#img_Upd_Ex").val(data["image"]);

      $("#imgsrc_Upd").attr("src", "./cargo/imagesCargo/" + data["image"]);

      $("#imageExDesignation").modal("show");
    },

    error: function () {
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

////////////////////******* CBM manager*******///////////////////////

function changeNumContainer(id) {
  emplacementChanged = $("#emplacementChanged" + id).val();

  c = emplacementChanged.split(" . ");
  idContainer = c[0];
  numContainer = c[1];

  if (
    emplacementChanged == "" ||
    emplacementChanged == null ||
    emplacementChanged.indexOf(" . ") < 0
  ) {
    confirm(
      "Mettez le numéro de container avant de valider ou mettez un numéro valide."
    );
  } else {
    // $("#emplacementChangedValidate"+id).prop('disabled', true);

    $.ajax({
      url: "cargo/ajax/operationCargoAjax.php",

      method: "POST",

      data: {
        changeNumContainer: "changeNumContainer",

        emplacementChanged: numContainer,

        idContainer: idContainer,

        idPagnet: id,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)
        // res = data.split('<>');
        if (data == 1 || data == "1") {
          $("#numContainerdetail" + id)
            .css("background", "green")
            .hide();

          $("#numContainerdetail" + id)
            .css("background", "lightGreen")
            .show(1000);

          $("#numContainerdetail" + id).text("Container : " + numContainer);
        } else {
          confirm("Une erreur est survenue. Rééssayer SVP.");
        }

        // $('#tabInventaire .typeahead').html('')
      },

      error: function (err) {
        confirm("Une erreur est survenue. Rééssayer SVP." + err);
      },
    });
  }
}

$(function () {
  $(document).on("keyup", "#client_cbm_fret", function (e) {
    e.preventDefault();
    // alert(1)
    // idPanier = $(this).attr('data-idPanier');

    var query = $(this).val();
    $("#emplacementAutocomplete").css("background", "white");

    if (query.length > 0) {
      $("#client_cbm_fret").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "cargo/ajax/operationCargoAjax.php",

            method: "POST",

            data: {
              client_cbm_fret: "client_cbm_fret",

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

    $("#client_cbm_fret").focus();

    /*********** Modification **************/

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      var text = $("#tabCbm .typeahead li.active").text();
      $("#emplacementAutocomplete").css("background", "white");
    }
  });

  $(document).on("click", "#tabCbm .typeahead li.active", function (e) {
    e.preventDefault();

    var text = $("#tabCbm .typeahead li.active").text();
    $("#emplacementAutocomplete").css("background", "white");
  });
});

function validerEnregistrement() {
  Client_text = $("#client_cbm_fret").val().split(" . ");
  idClient = Client_text[0];
  nomClient = Client_text[1];
  qty_cbm_fret = $("#qty_cbm_fret").val();
  // prix_cbm_fret = $("#prix_cbm_fret").val();
  nature_bagages = $("#nature_bagages").val();
  destination = $("#destination").val();
  qty_bal = $("#qty_bal").val();
  numEmplacement = $("#emplacementAutocomplete").val().split(" . ")[0];
  nomEmplacement = $("#emplacementAutocomplete").val().split(" . ")[1];
  nbPcs = $("#nbPcs").val();
  emplacement = $("#emplacement").val();
  $("#emplacementAutocomplete").css("background", "white");

  // alert($("#client_cbm_fret").val().indexOf(' . '));
  if (numEmplacement && numEmplacement !== "" && numEmplacement !== null) {
    if (
      $("#client_cbm_fret").val().indexOf(" . ") > 0 &&
      $("#emplacementAutocomplete").val().indexOf(" . ") > 0
    ) {
      if (qty_cbm_fret == 0 || qty_cbm_fret == null) {
        confirm("Erreur : Champs vide rencontré.");
      } else {
        $("#btnValiderCbm").prop("disabled", true);
        $.ajax({
          url: "cargo/ajax/operationCargoAjax.php",

          method: "POST",

          data: {
            enregistrementManager: "enregistrementManager",

            idClient: idClient,

            numEmplacement: numEmplacement,

            typeEmplacement: emplacement,

            nbPcs: nbPcs,

            qty_cbm_fret: qty_cbm_fret,

            destination: destination,

            nature_bagages: nature_bagages,

            qty_bal: qty_bal,
          },

          dataType: "text json",

          success: function (data) {
            // alert(data)
            console.log(data);

            if (data.idEnregistrement) {
              var currentdate = new Date();
              nbrows = 0;
              window.scrollTo({
                top: 700,
                behavior: "smooth",
              });
              $("#listeEnregistrement tr").each(() => {
                nbrows++;
              });
              $("#btnValiderCbm").prop("disabled", false);

              chargementOp =
                '<form class="form-inline"><div class="form-group"><input type="text" id="emplChargerAutocomplete' +
                data +
                '" class="form-control emplChargerAutocomplete col-lg-2" autocomplet="off" placeholder="N° Container ou Vol" style="width: 150px;"></div><div class="form-group"><input type="text" id="prixChargement' +
                data +
                '" class="form-control col-lg-2" placeholder="Prix chargement" style="width: 140px;"></div><button type="button" class="btn btn-success" id="btnChargementBagages" onClick="chargementBagages(' +
                data +
                ')"><span class="glyphicon glyphicon-ok"></span></button></form>';

              crudOp =
                '<div class="btn-group" role="group" aria-label="operation"><form action="./cargo/barcodeCBM.php" target="_blank" method="post"><input type="hidden" name="idEnreg" value="' +
                data.idEnregistrement +
                '"><input type="hidden" name="codeBarrePcsInContainer" value="' +
                data.codeBarre +
                '"><input type="hidden" name="nbPcsInContainer" value="' +
                data.nbPieces +
                '"><input type="hidden" name="numContainer" value="' +
                data.idContainer +
                '"><input type="hidden" name="numVol" value="' +
                data.idAvion +
                '"><input type="hidden" name="idClient" value="' +
                data.idClient +
                '"><button type="submit" class="btn btn-secondary"><span class="glyphicon glyphicon-barcode"></span></button><a><img src="images/iconfinder9.png" align="middle" alt="img" onclick="imgNew_Enreg(' +
                data.idEnregistrement +
                ')"/></a><button type="button" class="btn btn-info" id="btnEditEnreg" onClick="editEnregistrement(' +
                data.idEnregistrement +
                ')"><span class="glyphicon glyphicon-edit"></span></button><button type="button" class="btn btn-danger" id="btnDeleteEnreg" onClick="deleteEnregistrement(' +
                data.idEnregistrement +
                ')"><span class="glyphicon glyphicon-trash"></span></button></form></div>';
              row =
                "<tr id='trAdded'><td>" +
                nbrows +
                "</td><td>" +
                nomClient +
                "</td><td>" +
                currentdate.getDate() +
                "-" +
                (currentdate.getMonth() + 1) +
                "-" +
                currentdate.getFullYear() +
                "</td><td>" +
                currentdate.getHours() +
                ":" +
                currentdate.getMinutes() +
                "</td><td>" +
                qty_cbm_fret +
                "</td><td>" +
                nbPcs +
                "</td><td>" +
                nature_bagages +
                "</td><td>" +
                nomEmplacement +
                "</td><td>" +
                chargementOp +
                "</td><td>" +
                crudOp +
                "</td></tr>";
              // alert(row);
              $("#listeEnregistrement").prepend(row);

              $("#trAdded").hide(1000);
              $("#trAdded").css("background-color", "lightgreen");
              $("#trAdded").show(2000);

              $("#client_cbm_fret").val("");
              $("#nature_bagages").val("");
              $("#qty_cbm_fret").val(0);
              $("#qty_bal").val(0);
              // $("#destination").val(0);
              $("#nbPcs").val(1);
            } else {
              $("#btnValiderCbm").prop("disabled", false);
              confirm(
                "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
                  data
              );
            }
          },

          error: function (err) {
            console.log(err);
            $("#btnValiderCbm").prop("disabled", false);
            confirm("Erreur : Connexion impossible. Rééssayer SVP.");
          },
        });
      }
    } else {
      confirm(
        "Erreur : Client ou container introuvable. Créer le client ou le container s'il n'existe pas."
      );
    }
  } else {
    $("#emplacementAutocomplete").css("background", "red");
    confirm("Erreur : le numéro de container est obligatoire.");
  }
}

/***** autocomplete changement container *****/

function editContainer(idContainer) {
  $("#idContainer_edit").val(idContainer);

  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      getContainer: "getContainer",

      idContainer: idContainer,
    },

    dataType: "json",

    success: function (data) {
      // alert(data)
      // console.log(data);
      // $("#idContainer_edit").show();
      $("#numContainer_edit").prop("disabled", true);
      $("#numContainer_edit").val(data.numContainer);
      $("#numBooking_edit").val(data.numBooking);
      $("#dateDepart_edit").val(data.dateDepart);
      $("#dateArrivee_edit").val(data.dateArrivee);

      $("#editContainerModal").modal("show");
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function confirmEditContainer() {
  idContainer = $("#idContainer_edit").val();
  numContainer = $("#numContainer_edit").val();
  numBooking = $("#numBooking_edit").val();
  dateDepart = $("#dateDepart_edit").val();
  dateArrivee = $("#dateArrivee_edit").val();

  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      confirmEditContainer: "confirmEditContainer",
      idContainer: idContainer,
      numContainer: numContainer,
      numBooking: numBooking,
      dateDepart: dateDepart,
      dateArrivee: dateArrivee,
    },

    dataType: "text",

    success: function (data) {
      // alert(data)
      // console.log(data);

      if (data == 1 || data == "1") {
        $("#editContainerModal").modal("hide");

        $("#tr" + idContainer).hide(1000);
        $("#tr" + idContainer).show(500);

        $("#tr" + idContainer)
          .children("td")
          .eq(1)
          .text(numContainer);
        $("#tr" + idContainer)
          .children("td")
          .eq(2)
          .text(numBooking);
        $("#tr" + idContainer)
          .children("td")
          .eq(3)
          .text(dateDepart);
        $("#tr" + idContainer)
          .children("td")
          .eq(3)
          .text(dateArrivee);
      } else {
        confirm(
          "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
            data
        );
      }
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function deleteContainer(idContainer) {
  $("#idContainer_delete").val(idContainer);

  $("#deleteContainerModal").modal("show");
}

function confirmDeleteContainer() {
  idContainer = $("#idContainer_delete").val();
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      confirmDeleteContainer: "confirmDeleteContainer",

      idContainer: idContainer,
    },

    dataType: "text",

    success: function (data) {
      if (data == 1 || data == "1") {
        $("#tr" + idContainer).css("background-color", "red");
        $("#tr" + idContainer).hide(1000);

        $("#deleteContainerModal").modal("hide");
      } else {
        confirm(
          "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
            data
        );
      }
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function changeEmplacement() {
  emplacement = $("#emplacement").val();
  if (emplacement == 1) {
    $("#emplacementTxt").text("N° Container");
    $("#divAddArrivageForm").show();
  } else if (emplacement == 2) {
    $("#emplacementTxt").text("N° Avion");
    $("#divAddArrivageForm").show();
  } else if (emplacement == 3) {
    $("#emplacementTxt").text("Nom du dêpot");
    $("#divAddArrivageForm").show();
  }
}

function chargementBagages(idEnreg) {
  // alert(idEnreg);
  emplCharger = $("#emplChargerAutocomplete" + idEnreg)
    .val()
    .split(" --- ");
  idEmpl = emplCharger[0].split(" . ")[0];
  numEmpl = emplCharger[0].split(" . ")[1];
  typeEmpl = emplCharger[1];
  prixChargement = $("#prixChargement" + idEnreg).val();
  // nbPcsCharger = $("#nbPcsCharger" + idEnreg).val();
  // pcsRestant = $("#pcsRestant" + idEnreg).val();

  // if (pcsRestant < nbPcsCharger) {
  //   confirm(
  //     "Une erreur est survenue lors de l'opération : la quantité charger ne doit pas être supérieure à la quantité restante!"
  //   );
  // } else {
  if (emplCharger != null && emplCharger != "") {
    $.ajax({
      url: "cargo/ajax/operationCargoAjax.php",

      method: "POST",

      data: {
        chargementBagages: "chargementBagages",

        idEnreg: idEnreg,

        idEmpl: idEmpl,

        numEmpl: numEmpl,

        typeEmpl: typeEmpl,

        prixChargement: prixChargement,

        // nbPcsCharger: nbPcsCharger,

        // pcsRestant: pcsRestant,
      },

      dataType: "text",

      success: function (data) {
        if (data == 1 || data == "1") {
          // $("#pcsRestant" + idEnreg).val(pcsRestant - nbPcsCharger);
          // $("#nbPcsCharger" + idEnreg).val(pcsRestant - nbPcsCharger);
          // $("#restantPcs" + idEnreg).text(pcsRestant - nbPcsCharger);
          // if (pcsRestant - nbPcsCharger == 0) {
          $("#tr" + idEnreg).css("background-color", "#DDFCD0");
          $("#tr" + idEnreg).hide(1000);
          // $("#tr" + idEnreg).show(2000);
          // }
        } else {
          confirm(
            "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
              data
          );
        }
      },

      error: function (err) {
        console.log(err);
        confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
      },
    });
  } else {
    confirm(
      "Une erreur est survenue lors de l'opération : vérifier que tous les champs sont remplis svp!"
    );
  }
  // alert(emplCharger + " / " + nbPcsCharger);
  // $("#chargementBagagesModal").modal("show");
  // }
}

$(function () {
  $(document).on("keyup", ".emplChargerAutocomplete", function (e) {
    e.preventDefault();
    // idPanier = $(this).attr('data-idPanier');

    var query = $(this).val();
    // var emplacement = $("#emplacement").val();
    // var idPagnet = $(this).attr('data-idPanier');

    // $("#emplacementAutocomplete").css('background','white')

    if (query.length > 0) {
      // console.log(1)
      // alert(1)

      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "cargo/ajax/operationCargoAjax.php",

            method: "POST",

            data: {
              emplCharger: "emplCharger",

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

    $(this).focus();

    /*********** Modification **************/
  });
});

function editEnregistrement(idEnreg) {
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      getEnregistrement: "getEnregistrement",

      idEnreg: idEnreg,
    },

    dataType: "text json",

    success: function (data) {
      console.log(data);
      $("#idEnregistrementHide").val(idEnreg);
      $("#clientName").val(data["prenom"] + " " + data["nom"]);
      $("#nb_cbm_fret").val(data["quantite_cbm_fret"]);
      $("#nb_pieces").val(data["nbPieces"]);
      $("#nat_bagages").val(data["natureBagage"]);
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
    },
  });
  $("#editEnregModal").modal("show");
}

function editEnregistrementValidataion() {
  idEnreg = $("#idEnregistrementHide").val();
  // $("#clientName").val();
  nb_cbm_fret = $("#nb_cbm_fret").val();
  nb_pieces = $("#nb_pieces").val();
  nat_bagages = $("#nat_bagages").val();

  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      editEnregistrement: "editEnregistrement",

      idEnreg: idEnreg,
      nb_cbm_fret: nb_cbm_fret,
      nb_pieces: nb_pieces,
      nat_bagages: nat_bagages,
    },

    dataType: "text",

    success: function (data) {
      $("#editEnregModal").modal("hide");

      $("#tr" + idEnreg).css("background-color", "#D4F973");
      $("#tr" + idEnreg).hide(1000);
      $("#tr" + idEnreg).show(2000);
      $("#nbcf" + idEnreg).text(nb_cbm_fret);
      $("#nbp" + idEnreg).text(nb_pieces);
      $("#natb" + idEnreg).text(nat_bagages);
      setTimeout(() => {
        $("#tr" + idEnreg).css("background-color", "#fff");
      }, 2500);
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
    },
  });
}

function deleteEnregistrement(idEnreg) {
  $("#idDelEnregHide").val(idEnreg);
  $("#deleteEnregModal").modal("show");
}

function deleteEnregistrementValidataion() {
  idEnreg = $("#idDelEnregHide").val();
  alert(idEnreg);
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      delEnregistrement: "delEnregistrement",

      idEnreg: idEnreg,
    },

    dataType: "text",

    success: function (data) {
      alert(data);
      $("#deleteEnregModal").modal("hide");

      $("#tr" + idEnreg).css("background-color", "#DD0B0B");
      $("#tr" + idEnreg).hide(1000);
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
    },
  });
}

function fermerPorteur(id, type) {
  $.confirm({
    title: "Confirmer!",
    content: "Confimer l'opération!",
    buttons: {
      confirm: function () {
        $.ajax({
          url: "cargo/ajax/operationCargoAjax.php",

          method: "POST",

          data: {
            fermerPorteur: "fermerPorteur",

            id: id,

            type: type,
          },

          dataType: "text",

          success: function (data) {
            // alert(data);
            // console.log(data);
            $("#fermerPorteur" + id).hide(500);
            $("#ouvrirPorteur" + id).show(600);
            $("#arriveePorteur" + id).show(600);
          },

          error: function (err) {
            console.log(err);
            confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
          },
        });
      },
      cancel: function () {},
    },
  });
}

function ouvrirPorteur(id, type) {
  $.confirm({
    title: "Confirmer!",
    content: "Confimer l'opération!",
    buttons: {
      confirm: function () {
        $.ajax({
          url: "cargo/ajax/operationCargoAjax.php",

          method: "POST",

          data: {
            ouvrirPorteur: "ouvrirPorteur",

            id: id,

            type: type,
          },

          dataType: "text",

          success: function (data) {
            $("#ouvrirPorteur" + id).hide();
            $("#arriveePorteur" + id).hide();
            $("#fermerPorteur" + id).show();
          },

          error: function (err) {
            console.log(err);
            confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
          },
        });
      },
      cancel: function () {},
    },
  });
}

function arriveePorteur(id, numPorteur, type) {
  $.confirm({
    title: "Confirmer!",
    content: "Confimer l'opération!",
    buttons: {
      confirm: function () {
        $.ajax({
          url: "cargo/ajax/operationCargoAjax.php",

          method: "POST",

          data: {
            arriveePorteur: "arriveePorteur",

            id: id,

            type: type,
          },

          dataType: "text",

          success: function (data) {
            $("#fermerPorteur" + id).hide(500);
            $("#ouvrirPorteur" + id).hide(500);
            $("#arriveePorteur" + id).hide(500);
            if (type == 1) {
              btn =
                '<a class="btn btn-xs btn-default" id="paiementBtn1' +
                id +
                '" onClick="detailContainer(' +
                id +
                ",'" +
                numPorteur +
                "'," +
                1 +
                ')"><span class="glyphicon glyphicon-remove"> Non Payer</span> </a> ' +
                '<a class="btn btn-xs btn-info" id="paiementBtn2' +
                id +
                '" onClick="detailContainer(' +
                id +
                ",'" +
                numPorteur +
                "'," +
                2 +
                ')"><span class="glyphicon glyphicon-plus"> Avance</span> </a> ' +
                '<a class="btn btn-xs btn-success" id="paiementBtn3' +
                id +
                '" onClick="detailContainer(' +
                id +
                ",'" +
                numPorteur +
                "'," +
                3 +
                ')"><span class="glyphicon glyphicon-ok"> Payer</span> </a>';
            } else {
              btn =
                '<a class="btn btn-xs btn-default" id="paiementBtn1' +
                id +
                '" onClick="detailVol(' +
                id +
                ",'" +
                numPorteur +
                "'," +
                1 +
                ')"><span class="glyphicon glyphicon-remove"> Non Payer</span> </a> ' +
                '<a class="btn btn-xs btn-info" id="paiementBtn2' +
                id +
                '" onClick="detailVol(' +
                id +
                ",'" +
                numPorteur +
                "'," +
                2 +
                ')"><span class="glyphicon glyphicon-plus"> Avance</span> </a> ' +
                '<a class="btn btn-xs btn-success" id="paiementBtn3' +
                id +
                '" onClick="detailVol(' +
                id +
                ",'" +
                numPorteur +
                "'," +
                3 +
                ')"><span class="glyphicon glyphicon-ok"> Payer</span> </a>';
            }
            $("#tdBtn" + id).html(btn);
          },

          error: function (err) {
            console.log(err);
            confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
          },
        });
      },
      cancel: function () {},
    },
  });
}

$(document).ready(function () {
  $(document).on("keyup", "#searchByBarcode", function (e) {
    e.preventDefault();
    // alert(88)

    var keycode = e.keyCode ? e.keyCode : e.which;
    // alert(keycode);
    codeBarre = $("#searchByBarcode").val();

    if (keycode == "13" && $.isNumeric(codeBarre)) {
      // alert(1111111111);
      $.ajax({
        url: "cargo/ajax/operationCargoAjax.php",

        method: "POST",

        data: {
          searchByBarcode: "searchByBarcode",

          codeBarre: codeBarre,
        },

        dataType: "text json",

        success: function (data) {
          console.log(data[0]);
          type = data[3][0];
          enreg = data[0];
          client = data[1];
          porteur = data[2];

          if (type == 1) {
            // pour container type 2 pour avion
            $("#qtyByPorteurTxt").text("Nombre de CBM");
            $("#prixByPorteurTxt").text("Prix du CBM");
            typePorteur = "Container";
            unite = "M3";
          } else {
            $("#qtyByPorteurTxt").text("Nombre de KG");
            $("#prixByPorteurTxt").text("Prix du KG");
            typePorteur = "Avion";
            unite = KG;
          }

          $("#nomClientTxt").text(client.prenom + " " + client.nom);
          $("#telClientTxt").text(client.telephone);
          $("#dateEnregTxt").text(enreg.dateEnregistrement);
          $("#numEnreg").text("# " + enreg.idEnregistrement);
          $("#dateChargTxt").text(enreg.dateChargement);
          $("#porteurTxt").text(typePorteur);
          $("#qtyTxt").text(enreg.quantite_cbm_fret + " " + unite);
          $("#prixTxt").text(enreg.prix_cbm_fret + " FCFA");
          $("#nbPcsTxt").text(enreg.nbPieces);
          $("#NatBTxt").text(enreg.natureBagage);

          $("#serachByBarcodeModal").modal("show");
        },

        error: function (err) {
          console.log(err);
          confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
        },
      });
    }
  });
});

$(function () {
  $(document).on("keyup", ".emplacementChanged", function (e) {
    e.preventDefault();
    // idPanier = $(this).attr('data-idPanier');

    var query = $(this).val();
    // var idPagnet = $(this).attr('data-idPanier');
    // alert(emplacement);
    // $("#emplacementAutocomplete").css('background','white')

    if (query.length > 0) {
      // console.log(1)
      // alert(1)

      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "cargo/ajax/operationCargoAjax.php",

            method: "POST",

            data: {
              emplacementChange: "emplacementChange",

              query: query,

              emplacement: $("#emplacement").val(),
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

    $(".emplacementChanged").focus();

    /*********** Modification **************/
  });
});

function editEntrepot(idEntrepot) {
  $("#idEntrepot_edit").val(idEntrepot);

  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      getEntrepot: "getEntrepot",

      idEntrepot: idEntrepot,
    },

    dataType: "json",

    success: function (data) {
      // $("#idEntrepot_edit").prop("disabled", true);
      $("#nomEntrepot_edit").val(data.nomEntrepot);
      $("#adresse_edit").val(data.adresseEntrepot);
      $("#typeEntrepot_edit option[value=" + data.type + "]").attr(
        "selected",
        "selected"
      );

      $("#editEntrepotModal").modal("show");
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function deleteEntrepot(idEntrepot, nomEntrepot) {
  $("#idEntrepot_delete").val(idEntrepot);
  $("#nomEntrepotToDelete").text(nomEntrepot);

  $("#deleteEntrepotModal").modal("show");
}

function confirmDeleteEntrepot() {
  idEntrepot = $("#idEntrepot_delete").val();
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      confirmDeleteEntrepot: "confirmDeleteEntrepot",

      idEntrepot: idEntrepot,
    },

    dataType: "text",

    success: function (data) {
      if (data == 1 || data == "1") {
        $("#tr" + idEntrepot).css("background-color", "red");
        $("#tr" + idEntrepot).hide(1000);

        $("#deleteEntrepotModal").modal("hide");
      } else {
        confirm(
          "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
            data
        );
      }
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function editNature(idNature) {
  $("#idNature_edit").val(idNature);

  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      getNature: "getNature",

      idNature: idNature,
    },

    dataType: "json",

    success: function (data) {
      // $("#idEntrepot_edit").prop("disabled", true);
      $("#libelle_edit").val(data.libelle);
      $("#prix_cbm_edit").val(data.prix_cbm);
      $("#prix_fret_edit").val(data.prix_fret);

      $("#editNatureModal").modal("show");
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function deleteNature(idNature, libelle) {
  $("#idNature_delete").val(idNature);
  $("#libelleNatureToDelete").text(libelle);

  $("#deleteNatureModal").modal("show");
}

function confirmDeleteNature() {
  idNature = $("#idNature_delete").val();
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      confirmDeleteNature: "confirmDeleteNature",

      idNature: idNature,
    },

    dataType: "text",

    success: function (data) {
      if (data == 1 || data == "1") {
        $("#tr" + idNature).css("background-color", "red");
        $("#tr" + idNature).hide(1000);

        $("#deleteNatureModal").modal("hide");
      } else {
        confirm(
          "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
            data
        );
      }
    },

    error: function (err) {
      console.log(err);
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function imgNew_Avion(idAvion) {
  $("#id_Upd_ND").val(idAvion);
  $("#imageNvAvion").modal("show");
}

function imgEX_Avion(idAvion) {
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      getImgAvion: "getImgAvion",

      idAvion: idAvion,
    },

    dataType: "text json",

    success: function (data) {
      $("#id_Upd_Ex").val(idAvion);

      $("#img_Upd_Ex").val(data["image"]);

      $("#imgsrc_Upd").attr("src", "./cargo/imagesCargo/" + data["image"]);

      $("#imageExAvion").modal("show");
    },

    error: function () {
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function imgNew_Container(idContainer) {
  $("#id_Upd_ND").val(idContainer);
  $("#imageNvContainer").modal("show");
}

function imgEX_Container(idContainer) {
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      getImgContainer: "getImgContainer",

      idContainer: idContainer,
    },

    dataType: "text json",

    success: function (data) {
      $("#id_Upd_Ex").val(idContainer);

      $("#img_Upd_Ex").val(data["image"]);

      $("#imgsrc_Upd").attr("src", "./cargo/imagesCargo/" + data["image"]);

      $("#imageExContainer").modal("show");
    },

    error: function () {
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function setchargementPrix(idEnreg) {
  // alert(idEnreg);
  prixChargement = $("#prixChargement" + idEnreg).val();
  nb_cbm_fret = parseInt($("#nbcf" + idEnreg).text());
  // nbPcsCharger = $("#nbPcsCharger" + idEnreg).val();
  // pcsRestant = $("#pcsRestant" + idEnreg).val();

  // if (pcsRestant < nbPcsCharger) {
  //   confirm(
  //     "Une erreur est survenue lors de l'opération : la quantité charger ne doit pas être supérieure à la quantité restante!"
  //   );
  // } else {
  if (prixChargement != null && prixChargement != "") {
    $.ajax({
      url: "cargo/ajax/operationCargoAjax.php",

      method: "POST",

      data: {
        setchargementPrix: "setchargementPrix",

        idEnreg: idEnreg,

        prixChargement: prixChargement,
      },

      dataType: "text",

      success: function (data) {
        if (data == 1 || data == "1") {
          // $("#pcsRestant" + idEnreg).val(pcsRestant - nbPcsCharger);
          // $("#nbPcsCharger" + idEnreg).val(pcsRestant - nbPcsCharger);
          // $("#restantPcs" + idEnreg).text(pcsRestant - nbPcsCharger);
          // if (pcsRestant - nbPcsCharger == 0) {

          var f = new Intl.NumberFormat("fr-FR", { maximumFractionDigits: 0 });

          $("#tr" + idEnreg)
            .children("td")
            .eq(8)
            .text(f.format(prixChargement * nb_cbm_fret));
          $("#tr" + idEnreg).css("background-color", "gold");
          $("#tr" + idEnreg).hide(1000);
          $("#tr" + idEnreg).show(2000);
          // }
        } else {
          confirm(
            "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
              data
          );
        }
      },

      error: function (err) {
        console.log(err);
        confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
      },
    });
  } else {
    confirm(
      "Une erreur est survenue lors de l'opération : vérifier que tous les champs sont remplis svp!"
    );
  }
  // alert(emplCharger + " / " + nbPcsCharger);
  // $("#chargementBagagesModal").modal("show");
  // }
}

function annulerChargement(idEnreg) {
  $.confirm({
    title: "Confirmer!",
    content: "Annuler le chargement!",
    buttons: {
      confirm: function () {
        $.ajax({
          url: "cargo/ajax/operationCargoAjax.php",

          method: "POST",

          data: {
            annulerChargement: "annulerChargement",

            idEnreg: idEnreg,
          },

          dataType: "text",

          success: function (data) {
            if (data == 1 || data == "1") {
              $("#tr" + idEnreg)
                .hide("2000")
                .css("background-color", "#FA9898");
            } else {
              confirm(
                "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
                  data
              );
            }
          },

          error: function (err) {
            console.log(err);
            confirm("Erreur : Connexion impossible. Rééssayer SVP.");
          },
        });
      },
      cancel: function () {},
    },
  });
}
function getImage(image) {
  // $.ajax({
  //   url: "cargo/ajax/operationCargoAjax.php",

  //   method: "POST",

  //   data: {
  //     getImgEnreg: "getImgEnreg",

  //     idEnreg: idEnreg,
  //   },

  //   dataType: "text json",

  //   success: function (data) {
  //     console.log(data);

  $("#getImg").attr("src", "./cargo/imagesCargo/" + image);

  $("#getImage").modal("show");
  //   },

  //   error: function () {
  //     confirm("Erreur : Connexion impossible. Rééssayer SVP.");
  //   },
  // });
}

$(function () {
  $(document).on("keyup", "#libelle_depense", function (e) {
    e.preventDefault();
    // alert(1);

    var query = $(this).val();

    if (query.length > 0) {
      $("#libelle_depense").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "cargo/ajax/operationCargoAjax.php",

            method: "POST",

            data: {
              listeDepense: "listeDepense",

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

    $("#libelle_depense").focus();

    /*********** Modification **************/

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      var text = $("#tabDepense .typeahead li.active").text();

      $("#idDepense").val(text.split(" . ")[0]);
      $("#libelle_depense").val(text.split(" . ")[1]);
    }
  });

  $(document).on("click", "#tabDepense .typeahead li.active", function (e) {
    e.preventDefault();

    var text = $("#tabDepense .typeahead li.active").text();
    $("#idDepense").val(text.split(" . ")[0]);
    $("#libelle_depense").val(text.split(" . ")[1]);
  });
});

function validerDepense(porteur) {
  libelle_depense = $("#libelle_depense").val();
  description = $("#description").val();
  montant_depense = $("#montant_depense").val();
  compte_depense = $("#compte_depense").val();
  idDepense = $("#idDepense").val();
  if (idDepense) {
      
    if (
      libelle_depense &&
      montant_depense &&
      libelle_depense != "" &&
      montant_depense != "" &&
      libelle_depense != null &&
      montant_depense != null
    ) {
      $.confirm({
        title: "Confirmer!",
        content: "Confirmer la dépense!",
        buttons: {
          confirm: function () {
            if (porteur == 1) {
              idPorteur = $("#idContainerHidden").val();
            } else {
              idPorteur = $("#idAvionHidden").val();
            }

            $.ajax({
              url: "cargo/ajax/operationCargoAjax.php",

              method: "POST",

              data: {
                validerDepense: "validerDepense",

                idPorteur: idPorteur,

                porteur: porteur,

                libelle_depense: libelle_depense,

                description: description,

                montant_depense: montant_depense,

                compte_depense: compte_depense,

                idDepense: idDepense,
              },

              dataType: "text",

              success: function (data) {
                var currentdate = new Date();
                row =
                  "<tr id='trAdded'><td>--- </td><td>" +
                  libelle_depense +
                  " " +description +
                  "</td><td>" +
                  montant_depense +
                  "</td><td>" +
                  currentdate.getDate() +
                  "-" +
                  (currentdate.getMonth() + 1) +
                  "-" +
                  currentdate.getFullYear() +
                  "</td><td>---</td></tr>";
                $("#listeDepensesByPorteur").prepend(row);

                $("#trAdded").hide(1000);
                $("#trAdded").css("background-color", "lightgreen");
                $("#trAdded").show(2000);

                $("#libelle_depense").val("");
                $("#idDepense").val("");
                $("#description").val("");
                $("#montant_depense").val("");
              },

              error: function (err) {
                console.log(err);
                confirm("Erreur : Connexion impossible. Rééssayer SVP.");
              },
            });
          },
          cancel: function () {},
        },
      });
    } else {
      confirm("Le libellé de la dépense et le montant sont obligatoire!");
    }
  } else {
    
    confirm("Cette dépense n'est pas dans votre catalogue. Il faut la créer d'abord!");
  }
}

function detailDepensePorteur(idPorteur) {
  /*************** Début lister prix boutique ***************/
  // alert(etat)

  $(document).ready(function () {
    $("#resultsDepensesPorteur").load(
      "cargo/ajax/detailDepensesPorteurAjax.php",
      {
        idPorteur: idPorteur,
        operation: 1,
        query: "",
      }
    ); //load initial records

    //executes code below when user click on pagination links

    // $("#resultsDepensesPorteur").on("click", ".pagination a", function (e) {
    //   e.preventDefault();

    //   $(".loading-div").show(); //show loading element

    //   query = $("#searchInputContainer").val();

    //   if (query.length == 0) {
    //     $("#resultsDepensesPorteur").load(
    //       "cargo/ajax/detailDepensesPorteurAjax.php",
    //       {
    //         idPorteur: idPorteur,
    //         operation: 1,
    //         query: "",
    //       },
    //       function () {
    //         //get content from PHP page

    //         $(".loading-div").hide(); //once done, hide loading element
    //       }
    //     );
    //   } else {
    //     $("#resultsDepensesPorteur").load(
    //       "cargo/ajax/detailDepensesPorteurAjax.php",
    //       {
    //         idPorteur: idPorteur,
    //         operation: 1,
    //         query: query,
    //       },
    //       function () {
    //         //get content from PHP page

    //         $(".loading-div").hide(); //once done, hide loading element
    //       }
    //     );
    //   }
    // });
  });
}
