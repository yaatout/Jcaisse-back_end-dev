
function autocompB(idUser) {
  var sel = "searchBoutAcces" + idUser;
  var responsDiv = "#reponseS" + idUser;
  var query = document.getElementById(sel).value;
  var url = "ajax/rechercheBoutiqueAjax.php";
  let formData = new FormData();
  formData.append("query", query);
  const data = {};
  data["query"] = query;
  data["user"] = idUser;
  $.post(url, JSON.stringify(data)).done(function (response) {
    $(responsDiv).html(response);
    //alert(response)
  });
}
function choisirBoutique(b, us) {
  var sel = "#searchBoutAcces" + us;
  var li = "#liID" + b;
  var responsDiv = "#reponseS" + us;

  var tableSel = "#tabBoutiqueByUser" + us;
  var selectedLi = $(li).text();
  $(responsDiv).html("");
  //$(sel).val(selectedLi);
  //alert(us+"="+tableSel);

  var markup =
    "<tr style='background-color:#ebc034'><td>" +
    selectedLi +
    "</td>" +
    "<td><input type='checkbox' checked id='proprietaire" +
    b +
    "'></td>" +
    "<td><input type='checkbox' id='gerant" +
    b +
    "'></td>" +
    "<td><input type='checkbox' id='caissier" +
    b +
    "'></td>" +
    "<td><input type='checkbox' id='vendeur" +
    b +
    "'></td>" +
    "<td><button type='button' class='btn btn-success'  id='addrowBtn" +
    b +
    "' onclick='addAccesUser(" +
    b +
    "," +
    us +
    ")'>Ajouter</button></td>" +
    "<td><button type='button' class='btn btn-danger'  id='deleterowBtn" +
    b +
    "' onclick='supAccesUser(" +
    b +
    "," +
    us +
    ")'>Annuler</button></td>" +
    "</tr>";
  $(tableSel).append(markup).slideDown(500);

  /*  */
}
function addAccesUser(b, u) {
  var proprietaire = 0;
  var gerant = 0;
  var caissier = 0;
  var vendeur = 0;
  var btnAdd = "#addrowBtn" + b;
  var btnDel = "#deleterowBtn" + b;
  var idPr = document.getElementById("proprietaire" + b);
  if (idPr.checked == true) {
    proprietaire = 1;
  }
  var idGe = document.getElementById("gerant" + b);
  if (idGe.checked == true) {
    gerant = 1;
  }

  var idCa = document.getElementById("caissier" + b);
  if (idCa.checked == true) {
    caissier = 1;
  }

  var idVe = document.getElementById("vendeur" + b);
  if (idVe.checked == true) {
    vendeur = 1;
  }

  $.ajax({
    url: "ajax/rechercheBoutiqueAjax.php",
    method: "POST",
    data: {
      operation: "ajouterAcces",
      b: b,
      u: u,
      proprietaire: proprietaire,
      gerant: gerant,
      caissier: caissier,
      vendeur: vendeur,
    },
    success: function (data) {
      $(btnAdd).hide();
      $(btnDel).show();
      console.log(data);
    },
  });
}
function supAccesUser(b, u) {
  var btnDel = "#deleterowBtn" + b;
  $.ajax({
    url: "ajax/rechercheBoutiqueAjax.php",
    method: "POST",
    data: {
      operation: "supprimerAcces",
      b: b,
      u: u,
    },
    success: function (data) {
      $("#reponseS").html(data);
      $(btnDel).hide();
      console.log(data);
    },
  });
}
function confirmReinit(i) {
  var btnNum = document.getElementById("btnNum" + i);
  var formReini = document.getElementById("formReini" + i);
}
function ongletBUser() {
  /*************** Début modification catalogue Fusion PH***************/
  $(document).ready(function () {
    nbEntreeUB = $("#nbEntreeUB").val();

    $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
      onglet: "listeUser",
      operation: 1,
      nbEntreeUB: nbEntreeUB,
      query: "",
      cb: "",
    }); //load initial records

    //executes code below when user click on pagination links
    $("#resultsProductsUB").on("click", ".pagination a", function (e) {
      // $("#resultsProductsPF").on( "click", function (e){
      e.preventDefault();
      $(".loading-div").show(); //show loading element
      // page = page+1; //get page number from link
      page = $(this).attr("data-page"); //get page number from link
      //  alert(page)n
      nbEntreeUB = $("#nbEntreeUB").val();
      query = $("#searchInputUB").val();

      if (query.length == 0) {
        $("#resultsProductsUB").load(
          "ajax/boutiqueAjax.php",
          {
            onglet: "listeUser",
            page: page,
            operation: 1,
            nbEntreeUB: nbEntreeUB,
            query: "",
            cb: "",
          },
          function () {
            //get content from PHP page
            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      } else {
        $("#resultsProductsUB").load(
          "ajax/boutiqueAjax.php",
          {
            onglet: "listeUser",
            page: page,
            operation: 1,
            nbEntreeUB: nbEntreeUB,
            query: query,
            cb: "",
          },
          function () {
            //get content from PHP page
            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      }
      // $("#resultsProductsUB").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
    });
  });

  /********   RECHERCHE et NOMBRE D'ENTREES   ******/
  $(document).ready(function () {
    // alert(4)
    $("#searchInputUB").on("keyup", function (e) {
      e.preventDefault();

      query = $("#searchInputUB").val();
      nbEntreeUB = $("#nbEntreeUB").val();

      var keycode = e.keyCode ? e.keyCode : e.which;
      if (keycode == "13") {
        t = 1; // code barre
        // inputVal = $('#searchInputUB').val();
        // if ($.isNumeric(inputVal)) {
        if (query.length > 0) {
          $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
            onglet: "listeUser",
            operation: 3,
            nbEntreeUB: nbEntreeUB,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
            onglet: "listeUser",
            operation: 3,
            nbEntreeUB: nbEntreeUB,
            query: "",
            cb: t,
          }); //load initial records
        }
      } else {
        t = 0; // no code barre
        setTimeout(() => {
          if (query.length > 0) {
            // alert(2222)
            $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
              onglet: "listeUser",
              operation: 3,
              nbEntreeUB: nbEntreeUB,
              query: query,
              cb: t,
            }); //load initial records
          } else {
            $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
              onglet: "listeUser",
              operation: 3,
              nbEntreeUB: nbEntreeUB,
              query: "",
              cb: t,
            }); //load initial records
          }
        }, 100);
      }
    });

    $("#nbEntreeUB").on("change", function (e) {
      e.preventDefault();

      nbEntreeUB = $("#nbEntreeUB").val();
      query = $("#searchInputUB").val();

      if (query.length == 0) {
        $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
          onglet: "listeUser",
          operation: 4,
          nbEntreeUB: nbEntreeUB,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
          onglet: "listeUser",
          operation: 4,
          nbEntreeUB: nbEntreeUB,
          query: query,
          cb: "",
        }); //load initial records
      }

      // $("#resultsProductsUB").load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreeUB":nbEntreeUB}); //load initial records
    });
  });

  /*************    TRI    ******************/
  $(document).ready(function () {
    // alert(1500)
    // var page = 0;
    //executes code below when user click on pagination links
    //1=croissant et 0=decroissant
    triPH = 0;
    $(document).on("click", "#resultsProductsUB th", function (e) {
      e.preventDefault();
      // alert(12)
      query = $("#searchInputUB").val();
      nbEntreeUB = $("#nbEntreeUB").val();

      // $("#resultsProductsUB" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreeUB":nbEntreeUB,"tri":1, "query":""}); //load initial records

      if (triPH == 1) {
        // alert(triPH)
        if (query.length == 0) {
          $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
            onglet: "listeUser",
            operation: 2,
            nbEntreeUB: nbEntreeUB,
            tri: 1,
            query: "",
            cb: "",
          }); //load initial records
        } else {
          $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
            onglet: "listeUser",
            operation: 2,
            nbEntreeUB: nbEntreeUB,
            tri: 1,
            query: query,
            cb: "",
          }); //load initial records
        }

        triPH = 0;
        // alert(triPH)
      } else {
        // alert(triPH)
        if (query.length == 0) {
          $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
            onglet: "listeUser",
            operation: 2,
            nbEntreeUB: nbEntreeUB,
            tri: 0,
            query: "",
            cb: "",
          }); //load initial records
        } else {
          $("#resultsProductsUB").load("ajax/boutiqueAjax.php", {
            onglet: "listeUser",
            operation: 2,
            nbEntreeUB: nbEntreeUB,
            tri: 0,
            query: query,
            cb: "",
          }); //load initial records
        }

        triPH = 1;
        // alert(triPH)
      }
    });
  });

  /*************** Fin modification Catalogue Doublons PH***************/
}
// TO CHECK
function ongletBParametre() {
  /*************** Début modification catalogue Fusion PH***************/
  $(document).ready(function () {
    nbEntreeParam = $("#nbEntreeParam").val();

    $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
      onglet: "parametre",
      operation: 1,
      nbEntreeParam: nbEntreeParam,
      query: "",
      cb: "",
    }); //load initial records

    //executes code below when user click on pagination links
    $("#resultsBoutiqueParam").on("click", ".pagination a", function (e) {
      // $("#resultsProductsPF").on( "click", function (e){
      e.preventDefault();
      $(".loading-div").show(); //show loading element
      // page = page+1; //get page number from link
      page = $(this).attr("data-page"); //get page number from link
      //  alert(page)n
      nbEntreeParam = $("#nbEntreeParam").val();
      query = $("#searchInputParam").val();

      if (query.length == 0) {
        $("#resultsBoutiqueParam").load(
          "ajax/boutiqueAjax.php",
          {
            onglet: "parametre",
            page: page,
            operation: 1,
            nbEntreeParam: nbEntreeParam,
            query: "",
            cb: "",
          },
          function () {
            //get content from PHP page
            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      } else {
        $("#resultsBoutiqueParam").load(
          "ajax/boutiqueAjax.php",
          {
            onglet: "parametre",
            page: page,
            operation: 1,
            nbEntreeParam: nbEntreeParam,
            query: query,
            cb: "",
          },
          function () {
            //get content from PHP page
            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      }
      // $("#resultsBoutiqueParam").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
    });
  });

  /********   RECHERCHE et NOMBRE D'ENTREES   ******/
  $(document).ready(function () {
    // alert(4)
    $("#searchInputParam").on("keyup", function (e) {
      e.preventDefault();

      query = $("#searchInputParam").val();
      nbEntreeParam = $("#nbEntreeParam").val();

      var keycode = e.keyCode ? e.keyCode : e.which;
      if (keycode == "13") {
        t = 1; // code barre
        // inputVal = $('#searchInputParam').val();
        // if ($.isNumeric(inputVal)) {
        if (query.length > 0) {
          $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
            onglet: "parametre",
            operation: 3,
            nbEntreeParam: nbEntreeParam,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
            onglet: "parametre",
            operation: 3,
            nbEntreeParam: nbEntreeParam,
            query: "",
            cb: t,
          }); //load initial records
        }
      } else {
        t = 0; // no code barre
        setTimeout(() => {
          if (query.length > 0) {
            // alert(2222)
            $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
              onglet: "parametre",
              operation: 3,
              nbEntreeParam: nbEntreeParam,
              query: query,
              cb: t,
            }); //load initial records
          } else {
            $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
              onglet: "parametre",
              operation: 3,
              nbEntreeParam: nbEntreeParam,
              query: "",
              cb: t,
            }); //load initial records
          }
        }, 100);
      }
    });

    $("#nbEntreeParam").on("change", function (e) {
      e.preventDefault();

      nbEntreeParam = $("#nbEntreeParam").val();
      query = $("#searchInputParam").val();

      if (query.length == 0) {
        $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
          onglet: "parametre",
          operation: 4,
          nbEntreeParam: nbEntreeParam,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
          onglet: "parametre",
          operation: 4,
          nbEntreeParam: nbEntreeParam,
          query: query,
          cb: "",
        }); //load initial records
      }

      // $("#resultsBoutiqueParam").load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreeParam":nbEntreeParam}); //load initial records
    });
  });

  /*************    TRI    ******************/
  $(document).ready(function () {
    // alert(1500)
    // var page = 0;
    //executes code below when user click on pagination links
    //1=croissant et 0=decroissant
    triPH = 0;
    $(document).on("click", "#resultsBoutiqueParam th", function (e) {
      e.preventDefault();
      // alert(12)
      query = $("#searchInputParam").val();
      nbEntreeParam = $("#nbEntreeParam").val();

      // $("#resultsBoutiqueParam" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreeParam":nbEntreeParam,"tri":1, "query":""}); //load initial records

      if (triPH == 1) {
        // alert(triPH)
        if (query.length == 0) {
          $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
            onglet: "parametre",
            operation: 2,
            nbEntreeParam: nbEntreeParam,
            tri: 1,
            query: "",
            cb: "",
          }); //load initial records
        } else {
          $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
            onglet: "parametre",
            operation: 2,
            nbEntreeParam: nbEntreeParam,
            tri: 1,
            query: query,
            cb: "",
          }); //load initial records
        }

        triPH = 0;
        // alert(triPH)
      } else {
        // alert(triPH)
        if (query.length == 0) {
          $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
            onglet: "parametre",
            operation: 2,
            nbEntreeParam: nbEntreeParam,
            tri: 0,
            query: "",
            cb: "",
          }); //load initial records
        } else {
          $("#resultsBoutiqueParam").load("ajax/boutiqueAjax.php", {
            onglet: "parametre",
            operation: 2,
            nbEntreeParam: nbEntreeParam,
            tri: 0,
            query: query,
            cb: "",
          }); //load initial records
        }

        triPH = 1;
        // alert(triPH)
      }
    });
  });

  /*************** Fin modification Catalogue Doublons PH***************/
}
function enConfiguration(i) {
  $("#iActconfiguration").val(i);
  $("#ActConfiguration").modal("show");
}
function ApresConfiguration(i) {
  $("#iapresConfiguration").val(i);
  $("#ApresConfiguration").modal("show");
}
function EnTest(i) {
  $("#enTest").val(i);
  $("#EnTest").modal("show");
}
function ApresTest(i) {
  $("#apresTest").val(i);
  $("#ApresTest").modal("show");
}
function SansCompte(i) {
  $.ajax({
    url: "ajax/boutiqueAjax.php",
    method: "POST",
    data: {
      operation: "chercheBoutique",
      i: i,
    },
    success: function (data) {
      tab = data.split("<>");

      $("#iSc").val(i);
      $("#nbSc").val(tab[0]);
      $("#SansCompte").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}
function AvecCompte(i) {
  $("#iAc").val(i);
  $("#AvecCompte").modal("show");
}
function SansMutuelle(i) {
  $("#iSm").val(i);
  $("#SansMutuelle").modal("show");
}
function AvecMutuelle(i) {
  $("#iAm").val(i);
  $("#AvecMutuelle").modal("show");
}
function SansTampon(i) {
  $("#idST").val(i);
  $("#SansTampon").modal("show");
}
function AvecTampon(i) {
  $("#idAT").val(i);
  $("#AvecTampon").modal("show");
}
function paiemFixe(i) {
  $.ajax({
    url: "ajax/boutiqueAjax.php",
    method: "POST",
    data: {
      operation: "chercheBoutique",
      i: i,
    },
    success: function (data) {
      tab = data.split("<>");

      $("#iPf").val(i);
      $("#mFHP").val(tab[1]);
      $("#PaimentFixe").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}
function activerBout(i) {
  $("#iAB").val(i);
  $("#ActiverB").modal("show");
}
function desactiverBout(i) {
  $("#iDB").val(i);
  $("#DesactiverB").modal("show");
}
function activerVit(i) {
  $.ajax({
    url: "ajax/boutiqueAjax.php",
    method: "POST",
    data: {
      operation: "chercheBoutique",
      i: i,
    },
    success: function (data) {
      tab = data.split("<>");

      $("#iAVB").val(i);
      $("#nBAV").val(tab[0]);
      $("#adBAV").val(tab[2]);
      $("#tyBAV").val(tab[3]);
      $("#catBAV").val(tab[4]);
      $("#ActiverVitrine").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}
function desactiverVit(i) {
  $.ajax({
    url: "ajax/boutiqueAjax.php",
    method: "POST",
    data: {
      operation: "chercheBoutique",
      i: i,
    },
    success: function (data) {
      tab = data.split("<>");

      $("#iDVB").val(i);
      $("#nBDV").val(tab[0]);
      $("#adBDV").val(tab[2]);
      $("#tyBDV").val(tab[3]);
      $("#catBDV").val(tab[4]);
      $("#DesactiverVitrine").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}
function modBouti(i) {
  $.ajax({
    url: "ajax/boutiqueAjax.php",
    method: "POST",
    data: {
      operation: "chercheBoutique",
      i: i,
    },
    success: function (data) {
      tab = data.split("<>");

      $("#iM").val(i);
      $("#nomBoutiqueM").val(tab[0]);
      $("#adresseBM").val(tab[2]);
      $("#adresseBInitialM").val(tab[2]);
      $("#typeBInitialM").val(tab[3]);
      $("#categorieBInitialM").val(tab[4]);
      $("#labelBM").val(tab[5]);
      $("#nomBInitialM").val(tab[5]);
      $("#imgmodifierBoutique").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}
function supBouti(i) {
  $.ajax({
    url: "ajax/boutiqueAjax.php",
    method: "POST",
    data: {
      operation: "chercheBoutique",
      i: i,
    },
    success: function (data) {
      tab = data.split("<>");

      $("#iS").val(i);
      $("#adresseBS").val(tab[2]);
      $("#typeS").val(tab[3]);
      $("#categorieS").val(tab[4]);
      $("#labelBS").val(tab[5]);
      $("#imgmodifierBoutique").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}
function detUserBouti(i) {
  /*************** Début modification catalogue Fusion PH***************/
  $(document).ready(function () {
    nbEntreeDet = $("#nbEntreeDet").val();

    $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
      details: i,
      operation: 1,
      nbEntreeDet: nbEntreeDet,
      query: "",
      cb: "",
    }); //load initial records

    //executes code below when user click on pagination links
    $("#resultsProductsDet").on("click", ".pagination a", function (e) {
      // $("#resultsProductsPF").on( "click", function (e){
      e.preventDefault();
      $(".loading-div").show(); //show loading element
      // page = page+1; //get page number from link
      page = $(this).attr("data-page"); //get page number from link
      //  alert(page)n
      nbEntreeDet = $("#nbEntreeDet").val();
      query = $("#searchInputDet").val();

      if (query.length == 0) {
        $("#resultsProductsDet").load(
          "ajax/boutiqueAjax.php",
          {
            details: i,
            page: page,
            operation: 1,
            nbEntreeDet: nbEntreeDet,
            query: "",
            cb: "",
          },
          function () {
            //get content from PHP page
            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      } else {
        $("#resultsProductsDet").load(
          "ajax/boutiqueAjax.php",
          {
            details: i,
            page: page,
            operation: 1,
            nbEntreeDet: nbEntreeDet,
            query: query,
            cb: "",
          },
          function () {
            //get content from PHP page
            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      }
    });
  });

  /********   RECHERCHE et NOMBRE D'ENTREES   ******/
  $(document).ready(function () {
    // alert(4)
    $("#searchInputDet").on("keyup", function (e) {
      e.preventDefault();

      query = $("#searchInputDet").val();
      nbEntreeDet = $("#nbEntreeDet").val();

      var keycode = e.keyCode ? e.keyCode : e.which;
      if (keycode == "13") {
        t = 1; // code barre
        // inputVal = $('#searchInputDet').val();
        // if ($.isNumeric(inputVal)) {
        if (query.length > 0) {
          $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
            details: i,
            operation: 3,
            nbEntreeDet: nbEntreeDet,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
            details: i,
            operation: 3,
            nbEntreeDet: nbEntreeDet,
            query: "",
            cb: t,
          }); //load initial records
        }
      } else {
        t = 0; // no code barre
        setTimeout(() => {
          if (query.length > 0) {
            // alert(2222)
            $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
              details: i,
              operation: 3,
              nbEntreeDet: nbEntreeDet,
              query: query,
              cb: t,
            }); //load initial records
          } else {
            $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
              details: i,
              operation: 3,
              nbEntreeDet: nbEntreeDet,
              query: "",
              cb: t,
            }); //load initial records
          }
        }, 100);
      }
    });

    $("#nbEntreeDet").on("change", function (e) {
      e.preventDefault();

      nbEntreeDet = $("#nbEntreeDet").val();
      query = $("#searchInputDet").val();

      if (query.length == 0) {
        $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
          details: i,
          operation: 4,
          nbEntreeDet: nbEntreeDet,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
          details: i,
          operation: 4,
          nbEntreeDet: nbEntreeDet,
          query: query,
          cb: "",
        }); //load initial records
      }
    });
  });

  /*************    TRI    ******************/
  $(document).ready(function () {
    // alert(1500)
    // var page = 0;
    //executes code below when user click on pagination links
    //1=croissant et 0=decroissant
    triPH = 0;
    $(document).on("click", "#resultsProductsDet th", function (e) {
      e.preventDefault();
      // alert(12)
      query = $("#searchInputDet").val();
      nbEntreeDet = $("#nbEntreeDet").val();
      if (triPH == 1) {
        // alert(triPH)
        if (query.length == 0) {
          $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
            details: i,
            operation: 2,
            nbEntreeDet: nbEntreeDet,
            tri: 1,
            query: "",
            cb: "",
          }); //load initial records
        } else {
          $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
            details: i,
            operation: 2,
            nbEntreeDet: nbEntreeDet,
            tri: 1,
            query: query,
            cb: "",
          }); //load initial records
        }

        triPH = 0;
        // alert(triPH)
      } else {
        // alert(triPH)
        if (query.length == 0) {
          $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
            details: i,
            operation: 2,
            nbEntreeDet: nbEntreeDet,
            tri: 0,
            query: "",
            cb: "",
          }); //load initial records
        } else {
          $("#resultsProductsDet").load("ajax/boutiqueAjax.php", {
            details: i,
            operation: 2,
            nbEntreeDet: nbEntreeDet,
            tri: 0,
            query: query,
            cb: "",
          }); //load initial records
        }

        triPH = 1;
        // alert(triPH)
      }
    });
  });

  /*************** Fin modification Catalogue Doublons PH***************/
}
function detailUser(i) {
  $("#iAB").val(i);
  $("#ActiverB").modal("show");
}
function reinitPassUser(i) {
  $("#iAB").val(i);
  $("#ActiverB").modal("show");
}
function addPosition(i, j) {
  $("#titrePosition").html("Position de la boutique :" + j);
  $("#i").val(i);
  
  $("#latitude").val("");
  $("#longitude").val("");
  // Obtenir la position actuelle de l'utilisateur
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        // Succès: remplir les champs avec les coordonnées
        $("#latitude").val(position.coords.latitude);
        $("#longitude").val(position.coords.longitude);
        //$("#latitude").val("coucou")
        //console.log(position);
      },
      function(error) {
        // Erreur: afficher un message et laisser les champs vides
        console.error("Erreur de géolocalisation: " + error.message);
        $("#latitude").val("");
        $("#longitude").val("");
        alert("Impossible d'obtenir votre position. Veuillez entrer les coordonnées manuellement.");
      },
      {
        enableHighAccuracy: true,
        timeout: 30000,
        maximumAge: 0
      }
    );
  } else {
    // Le navigateur ne supporte pas la géolocalisation
    console.error("La géolocalisation n'est pas supportée par ce navigateur");
    $("#latitude").val("");
    $("#longitude").val("");
    alert("La géolocalisation n'est pas supportée par votre navigateur. Veuillez entrer les coordonnées manuellement.");
  }
  
  $("#addPosition").modal("show");
}

function editPosition(idPosition, idBoutique, latitude, longitude, nomBoutique) {
  $("#titrePositionEdit").html("Modifier position de la boutique :" + nomBoutique);
  $("#idPosition").val(idPosition);
  $("#iEdit").val(idBoutique);
  
  // Pré-remplir les champs avec les valeurs existantes
  $("#latitudeEdit").val(latitude);
  $("#longitudeEdit").val(longitude);
  
  $("#editPosition").modal("show");
}

/*************************************************************************************/
/*************************************************************************************/
/*************************  DEBUT FONCTIONNALITES PROSPECT ***************************/
/*************************************************************************************/
/*************************************************************************************/


function ongletProspect() {

  ///*************** Début modification HORS PARAMETRE details boutique***************/
  $(document).ready(function() {
      nbEntreeProspect = $('#nbEntreeProspect').val();
      $("#resultsProspect" ).load( "boutique/ajax/listerProspectClientAjax.php",{"operation":1,"nbEntreeProspect":nbEntreeProspect,"query":"","cb":""}); //load initial records
      
      //executes code below when user click on pagination links
      $("#resultsProspect").on( "click", ".pagination a", function (e){
      // $("#resultsProspect").on( "click", function (e){
          e.preventDefault();
          $(".loading-div").show(); //show loading element
          // page = page+1; //get page number from link
          page = $(this).attr("data-page"); //get page number from link
          //  alert(page)

          nbEntreeProspect = $('#nbEntreeProspect').val()
          query = $('#searchInputProspect').val();

          if (query.length == 0) {
              $("#resultsProspect" ).load( "boutique/ajax/listerProspectClientAjax.php",{"page":page,"operation":1,"nbEntreeProspect":nbEntreeProspect,"query":"","cb":""}, function(){ //get content from PHP page
                  $(".loading-div").hide(); //once done, hide loading element
              });
                  
          }else{
              $("#resultsProspect" ).load( "boutique/ajax/listerProspectClientAjax.php",{"page":page,"operation":1,"nbEntreeProspect":nbEntreeProspect,"query":query,"cb":""}, function(){ //get content from PHP page
                  $(".loading-div").hide(); //once done, hide loading element
              });
          }
          // $("#resultsProspect").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
      });
  });
  /********   RECHERCHE et NOMBRE D'ENTREES   ******/
  $(document).ready(function() {
      $('#searchInputProspect').on("keyup", function(e) {
          e.preventDefault();
          
          query = $('#searchInputProspect').val()
          nbEntreeProspect = $('#nbEntreeProspect').val()

          var keycode = (e.keyCode ? e.keyCode : e.which);
          if (keycode == '13') {
              // alert(1111)
              t = 1; // code barre
              
              if (query.length > 0) {
                  
                  $("#resultsProspect" ).load( "boutique/ajax/listerProspectClientAjax.php",{"operation":3,"nbEntreeProspect":nbEntreeProspect,"query":query,"cb":t}); //load initial records
              }else{
                  $("#resultsProspect" ).load("boutique/ajax/listerProspectClientAjax.php",{"operation":3,"nbEntreeProspect":nbEntreeProspect,"query":"","cb":t}); //load initial records
              }
          }else{
              // alert(2222)
              t = 0; // no code barre
              setTimeout(() => {
                  if (query.length > 0) {
                      
                      $("#resultsProspect" ).load("boutique/ajax/listerProspectClientAjax.php",{"operation":3,"nbEntreeProspect":nbEntreeProspect,"query":query,"cb":t}); //load initial records
                  }else{
                      $("#resultsProspect" ).load("boutique/ajax/listerProspectClientAjax.php",{"operation":3,"nbEntreeProspect":nbEntreeProspect,"query":"","cb":t}); //load initial records
                  }
              }, 100);
          }
      });
      
      $('#nbEntreeProspect').on("change", function(e) {
          e.preventDefault();

          nbEntreeProspect = $('#nbEntreeProspect').val()
          query = $('#searchInputProspect').val();

          if (query.length == 0) {
              $("#resultsProspect" ).load("boutique/ajax/listerProspectClientAjax.php",{"operation":4,"nbEntreeProspect":nbEntreeProspect,"query":"","cb":""}); //load initial records
                  
          }else{
              $("#resultsProspect" ).load("boutique/ajax/listerProspectClientAjax.php",{"operation":4,"nbEntreeProspect":nbEntreeProspect,"query":query,"cb":""}); //load initial records
          }
      });

  });

 
 
  
}

function loadInteractionsVenir() {
    var limit = $('#nbEntreeEvent').val();
    var search = $('#searchInputEvent').val();
    
    $.ajax({
        url: 'boutique/ajax/listerInteractionsVenirAjax.php',
        method: 'GET',
        data: {
            limit: limit,
            search: search
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayInteractionsVenir(response.data);
            } else {
                $('#resultsEvent').html('<div class="alert alert-danger">Erreur: ' + response.message + '</div>');
            }
        },
        error: function() {
            $('#resultsEvent').html('<div class="alert alert-danger">Erreur lors du chargement des interactions</div>');
        }
    });
}

function displayInteractionsVenir(interactions) {
    var html = '';
    
    if (interactions.length === 0) {
        html = '<div class="alert alert-info">Aucune interaction à venir trouvée</div>';
    } else {
        html = '<table class="table table-striped table-bordered">' +
               '<thead>' +
               '<tr>' +
               '<th>Date</th>' +
               '<th>Type</th>' +
               '<th>Client</th>' +
               '<th>Boutique</th>' +
               '<th>Notes</th>' +
               '<th>Créé par</th>' +
               '<th>Actions</th>' +
               '</tr>' +
               '</thead>' +
               '<tbody>';
        
        interactions.forEach(function(interaction) {
            var dateInteraction = new Date(interaction.dateInteraction).toLocaleDateString('fr-FR');
            var nomClient = interaction.prenom + ' ' + interaction.nom;
            var nomUtilisateur = interaction.prenomUtilisateur + ' ' + interaction.nomUtilisateur;
            var notes = interaction.notes ? interaction.notes.substring(0, 100) + (interaction.notes.length > 100 ? '...' : '') : '';
            
            // Déterminer la couleur selon la proximité de la date
            var today = new Date();
            var interactionDate = new Date(interaction.dateInteraction);
            var daysDiff = Math.ceil((interactionDate - today) / (1000 * 60 * 60 * 24));
            var rowClass = '';
            if (daysDiff === 0) {
                rowClass = 'warning'; // Aujourd'hui - orange
            } else if (daysDiff === 1) {
                rowClass = 'info'; // Demain - bleu
            } else if (daysDiff <= 3) {
                rowClass = 'success'; // Prochain - vert
            }
            
            html += '<tr class="' + rowClass + '">' +
                   '<td>' + dateInteraction + '</td>' +
                   '<td>' + interaction.typeInteraction + '</td>' +
                   '<td>' + nomClient + '</td>' +
                   '<td>' + (interaction.nomBoutique || '-') + '</td>' +
                   '<td>' + notes + '</td>' +
                   '<td>' + nomUtilisateur + '</td>' +
                   '<td>' +
                  //  '<button class="btn btn-sm btn-primary" onclick="voirDetailsInteraction(' + interaction.idInteraction + ')" title="Voir les détails">' +
                  //  '<i class="glyphicon glyphicon-eye-open"></i>' +
                  //  '</button>' +
                   '</td>' +
                   '</tr>';
        });
        
        html += '</tbody></table>';
    }
    
    $('#resultsEvent').html(html);
}

function voirDetailsInteraction(idInteraction) {
    // Fonction pour voir les détails d'une interaction
    // Vous pouvez implémenter un modal ou une page de détails ici
    alert('Fonction de détails à implémenter pour l\'interaction ID: ' + idInteraction);
}

// Gestionnaires d'événements pour l'onglet EVENTS
$(document).ready(function() {
    // Charger les interactions quand l'onglet EVENTS est cliqué
    $('a[href="#EVENTS"]').on('click', function() {
        setTimeout(function() {
            loadInteractionsVenir();
        }, 100);
    });
    
    // Gérer le changement du nombre d'entrées
    $('#nbEntreeEvent').on('change', function() {
        loadInteractionsVenir();
    });
    
    // Gérer la recherche avec délai
    var searchTimeout;
    $('#searchInputEvent').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadInteractionsVenir();
        }, 300);
    });
    
    // Gérer la recherche au Enter
    $('#searchInputEvent').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            loadInteractionsVenir();
        }
    });
});

function addBoutiqueProspectPopPup(params) {
  //alert(params);
  $('#clientProsp').val(params);
  $('#addProspectModal').modal('show'); 
 }

$(function(){ 
  //CLIENT PROSPECT  
  $("#addNewClientProspect").click(function(){
    
    //alert("Dans add Prospect");
    prenom = $('#prenomNewClient').val();
    nom = $('#nomNewClient').val();
    adresse = $('#adresseNewClient').val();
    telPortable = $('#telPortableNewClient').val();
    telFixe = $('#telFixeNewClient').val();
    email = $('#emailNewClient').val();
    nomBoutique = $('#nomBoutiqueNewClient').val();
  
  
  
    $.post("boutique/ajax/operationProspectAjax.php",{
        operation: "addNewProspectClient",
        prenom:prenom,
        nom:nom,
        adresse:adresse,
        telPortable:telPortable,
        telFixe:telFixe,
        email:email,
        nomBoutique:nomBoutique
    }, function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
        $('#addProspectUser').modal('hide'); 
    });
  });
  //BOUTIQUE DE PROSPECT
  $("#addProspectBoutique").click(function(){
    
    clientProsp=$('#clientProsp').val();
    nomBPr = $('#nomBPr').val();
    adresseBPr = $('#adresseBPr').val();
    telephoneBPr = $('#telephoneBPr').val();
    typeBPr = $('#typeBPr').val();
    categorieBPr = $('#categorieBPr').val();
    registreComBPr = $('#registreComBPr').val();
    nineaBPr = $('#nineaBPr').val();
    accompagnateurBPr = $('#accompagnateurBPr').val();
    
  
  
    $.post("boutique/ajax/operationProspectAjax.php",{
        operation: "addNewProspectBoutique",
        clientProsp,clientProsp,
        nomBPr:nomBPr,
        adresseBPr:adresseBPr,
        telephoneBPr:telephoneBPr,
        typeBPr:typeBPr,
        categorieBPr:categorieBPr,
        registreComBPr:registreComBPr,
        nineaBPr:nineaBPr,
        accompagnateurBPr:accompagnateurBPr
    }, function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        $("#resultsProspect" ).load( "boutique/ajax/listerProspectClientAjax.php",{"operation":3,"nbEntreeProspect":10,"query":'',"cb":1}); //load initial records
              
        $('#addProspectModal').modal('hide'); 
    });
  });

});

function activerInput(id) {
  $("#clientPprenom-"+id+"").prop('disabled', false);
  $("#clientPnom-"+id+"").prop('disabled', false);
  $("#clientPadresse-"+id+"").prop('disabled', false);
  $("#clientPemail-"+id+"").prop('disabled', false);
  $("#clientPtelPortable-"+id+"").prop('disabled', false);
  $("#clientPtelFixe-"+id+"").prop('disabled', false);
  $("#clientPnomBoutique-"+id+"").prop('disabled', false);

  //$("#btnActModCP"+id+"").hide();
  //$("#btnModCP"+id+"").show();

  //$("#btnActModCP"+id).remove() ;
  $("#tdClientPost"+id+"").empty();
  let strings='<button type="button" class="btn btn-success btnModCP"  id="btnModCP'+id+'" onclick="mdf_ClientP('+id+')" "> <i class="glyphicon glyphicon-pencil"></i>Enregistrer  </button>';
  
  $("#tdClientPost"+id+"").append(strings);
  
}

function mdf_ClientP(id) {
    prenom=$("#clientPprenom-"+id+"").val();
    nom=$("#clientPnom-"+id+"").val();
    adresse=$("#clientPadresse-"+id+"").val();
    email=$("#clientPemail-"+id+"").val();
    telPort=$("#clientPtelPortable-"+id+"").val();
    telFix=$("#clientPtelFixe-"+id+"").val();
    nomBoutique=$("#clientPnomBoutique-"+id+"").val();

  $.post("boutique/ajax/operationProspectAjax.php",{
    operation: "modProspectClient",
    id:id,
    prenom:prenom,
    nom:nom,
    adresse:adresse,
    email:email,
    telPort:telPort,
    telFix:telFix,
    nomBoutique:nomBoutique
  }, function(data, status){
      //alert("Data: " + data + "\nStatus: " + status);
      //$("#btnActModCP"+id+"").show();
      //$("#btnModCP"+id+"").hide();
      $("#clientPprenom-"+id+"").prop('disabled', true);
      $("#clientPnom-"+id+"").prop('disabled', true);
      $("#clientPadresse-"+id+"").prop('disabled', true);
      $("#clientPemail-"+id+"").prop('disabled', true);
      $("#clientPtelPortable-"+id+"").prop('disabled', true);
      $("#clientPtelFixe-"+id+"").prop('disabled', true);
      $("#clientPnomBoutique-"+id+"").prop('disabled', true)
      //$("#btnModCP"+id).remove() ;
      
      $("#tdClientPost"+id).empty();
      let strings='<button type="button" class="btn btn-primary" onclick="activerInput('+id+')" id="btnActModCP'+id+'> <i class="glyphicon glyphicon-pencil"></i>Modifier</button>';  
      
      $("#tdClientPost"+id+"").append(strings);
  });

}
function spm_ClientP(){
  let id=$('#idProsCliSup').val();
  //alert("id ======"+id);
  $.post("boutique/ajax/operationProspectAjax.php",{
    operation: "supProspectClient",
    id:id
  }, function(data, status){
     $('#popUpSupClien').modal('hide'); 
  });
}
function popUpSpm_ClientP(params,nom,prenom) {
  $('#idProsCliSup').val(params);
  let prenomNom=prenom+' '+nom;
  $('#prosNomPren').text(prenomNom);
  //alert(prenomNom);
  $('#popUpSupClien').modal('show'); 
}
function DetailsBoutProspectPopPup(i,client) {
  $(document).ready(function() {
    $("#boutiqueProspectDet" ).load( "boutique/ajax/listerBoutiqueProspectAjax.php",{"operation":'lister',"i":i,"client":client}); //load initial records
    $('#detProspPop').modal('show'); 
  });
}
function installerBoutiqueProsp(idC,idB) {
  console.log("idC "+idC);
  console.log("idB "+idB);
    $.post("boutique/ajax/operationProspectAjax.php",{
      operation: "installationBoutiqueProspect",
      idC:idC,
      idB:idB
    }, function(data, status){
      if (data=='TRUE') {
          alert("Ce nom est déja attribué à une entreprise");
      }
      location.reload(true);
    });
}

// idNomBoutiqueProspect = nomBPr

$(function() {
    $("#nomBPr").keyup(function() {
        var query = $("#nomBPr").val();
        console.log(query);
        if (query.length > 0) {
            $.ajax({
                url: 'boutique/ajax/operationProspectAjax.php',
                method: 'POST',
                data:{
                    operation:'rechercheBoutique',
                    nomBoutique: query,
                },
                success: function(data) {
                    //$("#reponseS").html(data);
                    console.log("trouvé = "+data);
                    if (data=='TRUE') {
                      alert("Ce nom est déja attribué à une entreprise");
                      //$("#addProspectBoutique").prop('disabled', true);
                      $("#addProspectBoutique").attr("disabled", "disabled");
                    } else {
                      //$("#addProspectBoutique").prop('disabled', false);
                      $("#addProspectBoutique").removeAttr("disabled");
                    }
                   // console.log(data);
                },
                dataType: 'text'
            });
        }

    })
});