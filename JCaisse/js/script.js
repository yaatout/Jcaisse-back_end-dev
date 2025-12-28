/*

R�sum�:

Commentaire:

version:1.1

AuteuKorka

Date de modification:

*/

/*************** Début modification tableaux ajout stock bl***************/

$(document).ready(function () {
  nbEntree = $("#nbEntreeSBl").val();
  idbl = $("#idBL").val();

  $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
    id: idbl,
    id: idbl,
    operation: 1,
    nbEntree: nbEntree,
    query: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsSBl").on("click", ".pagination a", function (e) {
    // $("#resultsProductsSBl").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntree = $("#nbEntreeSBl").val();

    query = $("#searchInputSBl").val();

    if (query.length == 0) {
      $("#resultsProductsSBl").load(
        "ajax/listeBlAjax.php",
        {
          id: idbl,
          id: idbl,
          page: page,
          operation: 1,
          nbEntree: nbEntree,
          query: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsSBl").load(
        "ajax/listeBlAjax.php",
        {
          id: idbl,
          id: idbl,
          page: page,
          operation: 1,
          nbEntree: nbEntree,
          query: query,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsSBl").load("ajax/listeBlAjax.php",{"id":idbl,"id":idbl,"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(1)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $("#searchInputSBl").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputSBl").val();

    nbEntree = $("#nbEntreeSBl").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
          id: idbl,
          operation: 3,
          nbEntree: nbEntreeS,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
          id: idbl,
          operation: 3,
          nbEntree: nbEntreeS,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
            id: idbl,
            operation: 3,
            nbEntree: nbEntreeS,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
            id: idbl,
            operation: 3,
            nbEntree: nbEntreeS,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeSBl").on("change", function (e) {
    e.preventDefault();

    nbEntree = $("#nbEntreeSBl").val();

    query = $("#searchInputSBl").val();

    if (query.length == 0) {
      $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
        id: idbl,
        operation: 4,
        nbEntree: nbEntree,
        query: "",
      }); //load initial records
    } else {
      $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
        id: idbl,
        operation: 4,
        nbEntree: nbEntree,
        query: query,
      }); //load initial records
    }
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  triSBl = 0;

  $(document).on("click", "#resultsProductsSBl th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputSBl").val();

    nbEntree = $("#nbEntreeSBl").val();

    if (triSBl == 1) {
      // alert(triSBl)

      if (query.length == 0) {
        $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
          id: idbl,
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: "",
        }); //load initial records
      } else {
        $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
          id: idbl,
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: query,
        }); //load initial records
      }

      triSBl = 0;

      // alert(triSBl)
    } else {
      // alert(triSBl)

      if (query.length == 0) {
        $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
          id: idbl,
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: "",
        }); //load initial records
      } else {
        $("#resultsProductsSBl").load("ajax/listeBlAjax.php", {
          id: idbl,
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: query,
        }); //load initial records
      }

      triSBl = 1;

      // alert(triSBl)
    }

    // alert(triSBl)
  });
});

/*************** Fin modification tableaux ajout stock bl***************/

/********* Debut alert seuil rayon **********/

$(document).ready(function () {
  $(".loading-divI").show(); //show loading element

  nbEntreeAlerteSeuil_Rayon = $("#nbEntreeAlerteSeuil_Rayon").val();

  $("#listeDesAlertesSeuil_Rayon").load(
    "ajax/alerteSeuil_RayonAjax.php",
    {
      operation: 1,
      nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
      query: "",
      cb: "",
    },
    function () {
      //get content from PHP page

      $(".loading-divI").hide(); //once done, hide loading element
    }
  );

  //executes code below when user click on pagination links

  $("#listeDesAlertesSeuil_Rayon").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesSeuil_Rayon").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeAlerteSeuil_Rayon = $("#nbEntreeAlerteSeuil_Rayon").val();

    query = $("#searchInputAlerteSeuil_Rayon").val();

    if (query.length == 0) {
      $("#listeDesAlertesSeuil_Rayon").load(
        "ajax/alerteSeuil_RayonAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesSeuil_Rayon").load(
        "ajax/alerteSeuil_RayonAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesSeuil_Rayon").load("ajax/alerteSeuil_RayonAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputAlerteSeuil_Rayon").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteSeuil_Rayon").val();

    nbEntreeAlerteSeuil_Rayon = $("#nbEntreeAlerteSeuil_Rayon").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesSeuil_Rayon").load(
          "ajax/alerteSeuil_RayonAjax.php",
          {
            operation: 3,
            nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
            query: query,
            cb: t,
          }
        ); //load initial records
      } else {
        $("#listeDesAlertesSeuil_Rayon").load(
          "ajax/alerteSeuil_RayonAjax.php",
          {
            operation: 3,
            nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
            query: "",
            cb: t,
          }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesSeuil_Rayon").load(
            "ajax/alerteSeuil_RayonAjax.php",
            {
              operation: 3,
              nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
              query: query,
              cb: t,
            }
          ); //load initial records
        } else {
          $("#listeDesAlertesSeuil_Rayon").load(
            "ajax/alerteSeuil_RayonAjax.php",
            {
              operation: 3,
              nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
              query: "",
              cb: t,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteSeuil_Rayon").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteSeuil_Rayon = $("#nbEntreeAlerteSeuil_Rayon").val();

    query = $("#searchInputAlerteSeuil_Rayon").val();

    if (query.length == 0) {
      $("#listeDesAlertesSeuil_Rayon").load("ajax/alerteSeuil_RayonAjax.php", {
        operation: 4,
        nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesSeuil_Rayon").load("ajax/alerteSeuil_RayonAjax.php", {
        operation: 4,
        nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesSeuil_Rayon" ).load( "ajax/alerteSeuil_RayonAjax.php",{"operation":4,"nbEntreeAlerteSeuil_Rayon":nbEntreeAlerteSeuil_Rayon}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  triRayon = 0;

  $(document).on("click", "#listeDesAlertesSeuil_Rayon th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputAlerteSeuil_Rayon").val();

    nbEntreeAlerteSeuil_Rayon = $("#nbEntreeAlerteSeuil_Rayon").val();

    // $("#listeDesAlertesSeuil_Rayon" ).load( "ajax/alerteSeuil_RayonAjax.php",{"operation":2,"nbEntreeAlerteSeuil_Rayon":nbEntreeAlerteSeuil_Rayon,"tri":1, "query":""}); //load initial records

    if (triRayon == 1) {
      // alert(triRayon)

      if (query.length == 0) {
        $("#listeDesAlertesSeuil_Rayon").load(
          "ajax/alerteSeuil_RayonAjax.php",
          {
            operation: 2,
            nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
            tri: 1,
            query: "",
            cb: "",
          }
        ); //load initial records
      } else {
        $("#listeDesAlertesSeuil_Rayon").load(
          "ajax/alerteSeuil_RayonAjax.php",
          {
            operation: 2,
            nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
            tri: 1,
            query: query,
            cb: "",
          }
        ); //load initial records
      }

      triRayon = 0;

      // alert(triRayon)
    } else {
      // alert(triRayon)

      if (query.length == 0) {
        $("#listeDesAlertesSeuil_Rayon").load(
          "ajax/alerteSeuil_RayonAjax.php",
          {
            operation: 2,
            nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
            tri: 0,
            query: "",
            cb: "",
          }
        ); //load initial records
      } else {
        $("#listeDesAlertesSeuil_Rayon").load(
          "ajax/alerteSeuil_RayonAjax.php",
          {
            operation: 2,
            nbEntreeAlerteSeuil_Rayon: nbEntreeAlerteSeuil_Rayon,
            tri: 0,
            query: query,
            cb: "",
          }
        ); //load initial records
      }

      triRayon = 1;

      // alert(triRayon)
    }
  });
});

/******** Fin alert seuil rayon ********/

/********* Debut inventaire partielle**********/

$(document).ready(function () {
  $(".loading-divI").show(); //show loading element

  nbEntreeInvP = $("#nbEntreeInvP").val();

  $("#resultsInventairesP").load(
    "ajax/listerStock-InventairePartielleAjax.php",
    { operation: 1, nbEntreeInvP: nbEntreeInvP, query: "", cb: "" },
    function () {
      //get content from PHP page

      $(".loading-divI").hide(); //once done, hide loading element
    }
  );

  //executes code below when user click on pagination links

  $("#resultsInventairesP").on("click", ".pagination a", function (e) {
    // $("#resultsInventairesP").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeInvP = $("#nbEntreeInvP").val();

    query = $("#searchInputInvP").val();

    if (query.length == 0) {
      $("#resultsInventairesP").load(
        "ajax/listerStock-InventairePartielleAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvP: nbEntreeInvP,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsInventairesP").load(
        "ajax/listerStock-InventairePartielleAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvP: nbEntreeInvP,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsInventairesP").load("ajax/listerStock-InventairePartielleAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputInvP").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputInvP").val();

    nbEntreeInvP = $("#nbEntreeInvP").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsInventairesP").load(
          "ajax/listerStock-InventairePartielleAjax.php",
          { operation: 3, nbEntreeInvP: nbEntreeInvP, query: query, cb: t }
        ); //load initial records
      } else {
        $("#resultsInventairesP").load(
          "ajax/listerStock-InventairePartielleAjax.php",
          { operation: 3, nbEntreeInvP: nbEntreeInvP, query: "", cb: t }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsInventairesP").load(
            "ajax/listerStock-InventairePartielleAjax.php",
            { operation: 3, nbEntreeInvP: nbEntreeInvP, query: query, cb: t }
          ); //load initial records
        } else {
          $("#resultsInventairesP").load(
            "ajax/listerStock-InventairePartielleAjax.php",
            { operation: 3, nbEntreeInvP: nbEntreeInvP, query: "", cb: t }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeInvP").on("change", function (e) {
    e.preventDefault();

    nbEntreeInvP = $("#nbEntreeInvP").val();

    query = $("#searchInputInvP").val();

    if (query.length == 0) {
      $("#resultsInventairesP").load(
        "ajax/listerStock-InventairePartielleAjax.php",
        { operation: 4, nbEntreeInvP: nbEntreeInvP, query: "", cb: "" }
      ); //load initial records
    } else {
      $("#resultsInventairesP").load(
        "ajax/listerStock-InventairePartielleAjax.php",
        { operation: 4, nbEntreeInvP: nbEntreeInvP, query: query, cb: "" }
      ); //load initial records
    }

    // $("#resultsInventairesP" ).load( "ajax/listerStock-InventairePartielleAjax.php",{"operation":4,"nbEntreeInvP":nbEntreeInvP}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triBIP = 0;

  $(document).on("click", "#resultsInventairesP th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputInvP").val();

    nbEntreeInvP = $("#nbEntreeInvP").val();

    // $("#resultsInventairesP" ).load( "ajax/listerStock-InventairePartielleAjax.php",{"operation":2,"nbEntreeInvP":nbEntreeInvP,"tri":1, "query":""}); //load initial records

    if (triBIP == 1) {
      // alert(triBIP)

      if (query.length == 0) {
        $("#resultsInventairesP").load(
          "ajax/listerStock-InventairePartielleAjax.php",
          {
            operation: 2,
            nbEntreeInvP: nbEntreeInvP,
            tri: 1,
            query: "",
            cb: "",
          }
        ); //load initial records
      } else {
        $("#resultsInventairesP").load(
          "ajax/listerStock-InventairePartielleAjax.php",
          {
            operation: 2,
            nbEntreeInvP: nbEntreeInvP,
            tri: 1,
            query: query,
            cb: "",
          }
        ); //load initial records
      }

      triBIP = 0;

      // alert(triBIP)
    } else {
      // alert(triBIP)

      if (query.length == 0) {
        $("#resultsInventairesP").load(
          "ajax/listerStock-InventairePartielleAjax.php",
          {
            operation: 2,
            nbEntreeInvP: nbEntreeInvP,
            tri: 0,
            query: "",
            cb: "",
          }
        ); //load initial records
      } else {
        $("#resultsInventairesP").load(
          "ajax/listerStock-InventairePartielleAjax.php",
          {
            operation: 2,
            nbEntreeInvP: nbEntreeInvP,
            tri: 0,
            query: query,
            cb: "",
          }
        ); //load initial records
      }

      triBIP = 1;

      // alert(triBIP)
    }
  });
});

/******** Fin inventaire partielle********/

$(document).ready(function () {
  /*************** Début lister clients en ligne ***************/

  $(document).ready(function () {
    nbEntreeEnLigne = $("#nbEntreeEnLigne").val();

    $("#listeCliEvent7").on("click", function (e) {
      $("#listeDesClientsEnLigne").load("ajax/listerClientEnLigneAjax.php", {
        operation: 1,
        nbEntreeEnLigne: nbEntreeEnLigne,
        query: "",
        cb: "",
      }); //load initial records
    });

    //executes code below when user click on pagination links

    $("#listeDesClientsEnLigne").on("click", ".pagination a", function (e) {
      // $("#listeDesClientsEnLigne").on( "click", function (e){

      e.preventDefault();

      $(".loading-div").show(); //show loading element

      // page = page+1; //get page number from link

      page = $(this).attr("data-page"); //get page number from link

      //  alert(page)

      nbEntreeEnLigne = $("#nbEntreeEnLigne").val();

      query = $("#searchInputEnLigne").val();

      if (query.length == 0) {
        $("#listeDesClientsEnLigne").load(
          "ajax/listerClientEnLigneAjax.php",
          {
            page: page,
            operation: 1,
            nbEntreeEnLigne: nbEntreeEnLigne,
            query: "",
            cb: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      } else {
        $("#listeDesClientsEnLigne").load(
          "ajax/listerClientEnLigneAjax.php",
          {
            page: page,
            operation: 1,
            nbEntreeEnLigne: nbEntreeEnLigne,
            query: query,
            cb: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      }

      // $("#listeDesClientsEnLigne").load("ajax/listerClientEnLigneAjax.php",{"page":page,"operation":1}
    });
  });

  /*************** Fin lister clients ***************/

  /*************  Debut Tri sur les clients  ******************/

  $(document).ready(function () {
    $("#searchInputEnLigne").on("keyup", function (e) {
      e.preventDefault();

      query = $("#searchInputEnLigne").val();

      nbEntreeEnLigne = $("#nbEntreeEnLigne").val();

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        // alert(1111)

        tl = 1; // code barre

        if (query.length > 0) {
          $("#listeDesClientsEnLigne").load(
            "ajax/listerClientEnLigneAjax.php",
            {
              operation: 3,
              nbEntreeEnLigne: nbEntreeEnLigne,
              query: query,
              cb: tl,
            }
          ); //load initial records
        } else {
          $("#listeDesClientsEnLigne").load(
            "ajax/listerClientEnLigneAjax.php",
            {
              operation: 3,
              nbEntreeEnLigne: nbEntreeEnLigne,
              query: "",
              cb: tl,
            }
          ); //load initial records
        }
      } else {
        // alert(2222)

        tl = 0; // no code barre

        setTimeout(() => {
          if (query.length > 0) {
            $("#listeDesClientsEnLigne").load(
              "ajax/listerClientEnLigneAjax.php",
              {
                operation: 3,
                nbEntreeEnLigne: nbEntreeEnLigne,
                query: query,
                cb: tl,
              }
            ); //load initial records
          } else {
            $("#listeDesClientsEnLigne").load(
              "ajax/listerClientEnLigneAjax.php",
              {
                operation: 3,
                nbEntreeEnLigne: nbEntreeEnLigne,
                query: "",
                cb: tl,
              }
            ); //load initial records
          }
        }, 100);
      }
    });

    $("#nbEntreeEnLigne").on("change", function (e) {
      e.preventDefault();

      nbEntreeEnLigne = $("#nbEntreeEnLigne").val();

      query = $("#searchInputEnLigne").val();

      if (query.length == 0) {
        $("#listeDesClientsEnLigne").load("ajax/listerClientEnLigneAjax.php", {
          operation: 4,
          nbEntreeEnLigne: nbEntreeEnLigne,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#listeDesClientsEnLigne").load("ajax/listerClientEnLigneAjax.php", {
          operation: 4,
          nbEntreeEnLigne: nbEntreeEnLigne,
          query: query,
          cb: "",
        }); //load initial records
      }

      // $("#listeDesClientsEnLigne" ).load( "ajax/listerClientEnLigneAjax.php",{"operation":4,"nbEntreeEnLigne":nbEntreeEnLigne}); //load initial records
    });
  });

  /*************  Fin Tri sur les clients en ligne  ******************/

  /*************** Début lister vente par date ***************/

  $(document).ready(function () {
    nbEntreeVD = $("#nbEntreeVD").val();

    $("#btnVenteParDate").on("click", function (e) {
      // $("#results_vente_par_date").on( "click", function (e){
      // alert(1)
      e.preventDefault();

      pageActu = $("#pageTotalVente").val(); //get page number from link

      // $(".loading-gif").show();
      $("#results_vente_par_date").load(
        "ajax/listerVenteParDateAjax.php",
        {
          operation: 1,
          page: pageActu,
          nbEntreeVD: nbEntreeVD,
          query: "",
          cb: "",
        },
        function (data) {
          //get content from PHP page

          // alert(data);
          $(".loading-gif").hide();
        }
      );
    });

    //executes code below when user click on pagination links

    $("#results_vente_par_date").on("click", ".pagination a", function (e) {
      // $("#results_vente_par_date").on( "click", function (e){

      e.preventDefault();

      // $(".loading-div").show(); //show loading element

      // page = page+1; //get page number from link

      page = $(this).attr("data-page"); //get page number from link
      $("#pageTotalVente").val(page);
      $("#results_vente_par_date").hide();
      $(".loading-gif").show();
      //  alert(page)

      nbEntreeVD = $("#nbEntreeVD").val();

      $("#results_vente_par_date").load(
        "ajax/listerVenteParDateAjax.php",
        { page: page, operation: 1, nbEntreeVD: nbEntreeVD, query: "", cb: "" },
        function (data) {
          //get content from PHP page

          $(".loading-gif").hide(); //once done, hide loading element
          $("#results_vente_par_date").show();
          // alert('if')
          // alert(data)
        }
      );
    });
  });
  /*************** Fin lister vente par date ***************/

  /**************** Debut controle date ****************/
  $(document).on("keyup", ".dateInputControl", function (e) {
    e.preventDefault();

    saisie = $(this).val();
    saisieLength = saisie.length;

    var keycode = e.keyCode ? e.keyCode : e.which;
    // alert(keycode)

    if (saisieLength == 1) {
      if ($.isNumeric(saisie) && saisie <= 3) {
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
      }
    } else if (saisieLength == 2) {
      if ($.isNumeric(saisie[1])) {
        if (keycode == 8) {
          $(this).val(saisie.split("+")[0]);
        } else {
          $(this).val(saisie + "-");
        }
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
      }
    } else if (saisieLength == 3) {
      if (saisie[2] == "-") {
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
        // $(this).val(saisie+"-")
      }
    } else if (saisieLength == 4) {
      splitSaisie = saisie.split("-");
      jour = splitSaisie[0];
      premierCharMois = splitSaisie[1];

      if ($.isNumeric(premierCharMois) && premierCharMois <= 1) {
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
      }
    } else if (saisieLength == 5) {
      splitSaisie = saisie.split("-");
      jour = splitSaisie[0];
      deuxiemeCharMois = splitSaisie[1][1];

      if (splitSaisie[1][0] == 0) {
        if ($.isNumeric(deuxiemeCharMois)) {
          if (keycode == 8) {
            $(this).val(saisie.split("+")[0]);
          } else {
            $(this).val(saisie + "-");
          }
        } else {
          $(this).val(saisie.substring(0, saisie.length - 1));
          // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
        }
      } else {
        if ($.isNumeric(deuxiemeCharMois) && deuxiemeCharMois <= 2) {
          if (keycode == 8) {
            $(this).val(saisie.split("+")[0]);
          } else {
            $(this).val(saisie + "-");
          }
        } else {
          $(this).val(saisie.substring(0, saisie.length - 1));
          // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
        }
      }
    } else if (saisieLength == 6) {
      if (saisie[5] == "-") {
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
        // $(this).val(saisie+"-")
      }
    } else if (saisieLength == 7) {
      splitSaisie = saisie.split("-");
      jour = splitSaisie[0];
      mois = splitSaisie[1];
      premierCharAnnee = splitSaisie[2][0];

      if ($.isNumeric(premierCharAnnee) && premierCharAnnee == 2) {
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
      }
    } else if (saisieLength == 8) {
      splitSaisie = saisie.split("-");
      jour = splitSaisie[0];
      mois = splitSaisie[1];
      deuxiemeCharAnnee = splitSaisie[2][1];
      // alert(deuxiemeCharAnnee);

      if ($.isNumeric(deuxiemeCharAnnee) && deuxiemeCharAnnee == 0) {
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
      }
    } else if (saisieLength == 9) {
      splitSaisie = saisie.split("-");
      jour = splitSaisie[0];
      mois = splitSaisie[1];
      troisiemeCharAnnee = splitSaisie[2][2];
      // alert(deuxiemeCharAnnee);

      if ($.isNumeric(troisiemeCharAnnee) && troisiemeCharAnnee <= 2) {
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
      }
    } else if (saisieLength == 10) {
      splitSaisie = saisie.split("-");
      jour = splitSaisie[0];
      mois = splitSaisie[1];
      quatriemeCharAnnee = splitSaisie[2][3];
      // alert(deuxiemeCharAnnee);

      if ($.isNumeric(quatriemeCharAnnee)) {
      } else {
        $(this).val(saisie.substring(0, saisie.length - 1));
        // alert("La date doit être de la forme jour-mois-année.\n Exemple: 01-01-2002.")
      }
    }
  });
  /**************** Fin controle date ****************/

  $(document).on("click", ".btnAddInfoSup", function (e) {
    // $("#resultsTransfert").on( "click", function (e){

    e.preventDefault();

    idLigne = $("#idLigneInfo").val();
    numeroChassis = $("#numeroChassis").val();
    numeroMoteur = $("#numeroMoteur").val();
    couleur = $("#couleur").val();
    // alert(numeroChassis+" / "+numeroMoteur)

    add_infoSup1(idLigne, numeroChassis, numeroMoteur, couleur);
  });

  $(document).on("click", ".btn_add_infoSup1", function (e) {
    // $("#resultsTransfert").on( "click", function (e){

    e.preventDefault();

    // alert($(this).attr('data-idligne'))
    $("#idLigneInfo").val($(this).attr("data-idligne"));
  });

  // alert(55256)clientMutuelleInput
  $(document).on("focus", ".codeBarreLigneMutuelle", function () {
    // alert( "Handler for .focus() called." );
    typeClient = "";
  });
});

/*************** Début lister transfert ***************/

$(document).ready(function () {
  nbEntreeTrans = $("#nbEntreeTrans").val();

  $("#resultsTransfert").load(
    "ajax/listerTransfertAjax.php",
    { operation: 1, nbEntreeTrans: nbEntreeTrans, query: "", cb: "" },
    function (data) {
      //get content from PHP page
      // alert(data);
    }
  );

  //executes code below when user click on pagination links

  $("#resultsTransfert").on("click", ".pagination a", function (e) {
    // $("#resultsTransfert").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeTrans = $("#nbEntreeTrans").val();

    query = $("#searchInputTrans").val();

    if (query.length == 0) {
      $("#resultsTransfert").load(
        "ajax/listerTransfertAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeTrans: nbEntreeTrans,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsTransfert").load(
        "ajax/listerTransfertAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeTrans: nbEntreeTrans,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsTransfert").load("ajax/listerTransfertAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputTrans").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputTrans").val();

    nbEntreeTrans = $("#nbEntreeTrans").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
          operation: 3,
          nbEntreeTrans: nbEntreeTrans,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
          operation: 3,
          nbEntreeTrans: nbEntreeTrans,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
            operation: 3,
            nbEntreeTrans: nbEntreeTrans,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
            operation: 3,
            nbEntreeTrans: nbEntreeTrans,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeTrans").on("change", function (e) {
    e.preventDefault();

    nbEntreeTrans = $("#nbEntreeTrans").val();

    query = $("#searchInputTrans").val();

    if (query.length == 0) {
      $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
        operation: 4,
        nbEntreeTrans: nbEntreeTrans,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
        operation: 4,
        nbEntreeTrans: nbEntreeTrans,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsTransfert" ).load( "ajax/listerTransfertAjax.php",{"operation":4,"nbEntreeTrans":nbEntreeTrans}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triTrans = 0;

  $(document).on("click", "#resultsTransfert th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputTrans").val();

    nbEntreeTrans = $("#nbEntreeTrans").val();

    // $("#resultsTransfert" ).load( "ajax/listerTransfertAjax.php",{"operation":2,"nbEntreeTrans":nbEntreeTrans,"tri":1, "query":""}); //load initial records

    if (triTrans == 1) {
      // alert(triTrans)

      if (query.length == 0) {
        $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
          operation: 2,
          nbEntreeTrans: nbEntreeTrans,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
          operation: 2,
          nbEntreeTrans: nbEntreeTrans,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triTrans = 0;

      // alert(triTrans)
    } else {
      // alert(triTrans)

      if (query.length == 0) {
        $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
          operation: 2,
          nbEntreeTrans: nbEntreeTrans,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsTransfert").load("ajax/listerTransfertAjax.php", {
          operation: 2,
          nbEntreeTrans: nbEntreeTrans,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triTrans = 1;

      // alert(triTrans)
    }
  });
});

/*************** Fin lister transfert ***************/

$(document).ready(function () {
  // alert(88)
  $(document).on("keyup", ".quantitePhysiquePermanent", function (e) {
    e.preventDefault();
    // alert(88)
    id = $(this).attr("data-id");

    var keycode = e.keyCode ? e.keyCode : e.which;
    // alert(keycode)

    if (keycode == "13") {
      // alert(1123)
      // inventaireDepot(id);
      inventairePermanentDepot(id);
    }
  });

  $(document).on("keyup", ".quantitePhysiqueIntermittent", function (e) {
    e.preventDefault();
    // alert(88)
    id = $(this).attr("data-id");

    var keycode = e.keyCode ? e.keyCode : e.which;
    // alert(keycode)

    if (keycode == "13") {
      // alert(1123)
      // inventaireDepot(id);
      inventaireIntermittentDepot(id);
    }
  });

  $(document).on("keyup", ".quantitePhyInt", function (e) {
    e.preventDefault();
    // alert(88)
    ids = $(this).attr("data-id").split("_");

    var keycode = e.keyCode ? e.keyCode : e.which;
    // alert(keycode)

    if (keycode == "13") {
      // alert(1123)
      // inventaireDepot(id);
      inventaireDepot_ET_I(ids[0], ids[1]);
    }
  });
});

////////////////////3333333333333333//////////////////

$(document).ready(function () {
  $(".loading-divI").show(); //show loading element

  nbEntreeInvJour3 = $("#nbEntreeInvJour3").val();

  $("#INVENTAIREJOUR3").on("click", function (e) {
    $("#resultsInventairesJour3").load(
      "ajax/listerStock-InventaireJour3Ajax.php",
      {
        operation: 1,
        nbEntreeInvJour: nbEntreeInvJour3,
        idEntrepotInventaire: idEntrepotInventaire,
        query: "",
        cb: "",
      },
      function (data) {
        //get content from PHP page
        // alert(data)
        console.log(data);
        $(".loading-divI").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#resultsInventairesJour3").on("click", ".pagination a", function (e) {
    // $("#resultsInventairesJour3").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeInvJour3 = $("#nbEntreeInvJour3").val();

    query3 = $("#searchInputInvJour3").val();

    if (query3.length == 0) {
      $("#resultsInventairesJour3").load(
        "ajax/listerStock-InventaireJour3Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvJour: nbEntreeInvJour3,
          idEntrepotInventaire: idEntrepotInventaire,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsInventairesJour3").load(
        "ajax/listerStock-InventaireJour3Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvJour: nbEntreeInvJour3,
          idEntrepotInventaire: idEntrepotInventaire,
          query: query3,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsInventairesJour3").load("ajax/listerStock-InventaireJour3Ajax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputInvJour3").on("keyup", function (e) {
    e.preventDefault();

    query3 = $("#searchInputInvJour3").val();

    nbEntreeInvJour3 = $("#nbEntreeInvJour3").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query3.length > 0) {
        $("#resultsInventairesJour3").load(
          "ajax/listerStock-InventaireJour3Ajax.php",
          {
            operation: 3,
            nbEntreeInvJour: nbEntreeInvJour3,
            idEntrepotInventaire: idEntrepotInventaire,
            query: query3,
            cb: t,
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour3").load(
          "ajax/listerStock-InventaireJour3Ajax.php",
          {
            operation: 3,
            nbEntreeInvJour: nbEntreeInvJour3,
            idEntrepotInventaire: idEntrepotInventaire,
            query: "",
            cb: t,
          }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query3.length > 0) {
          $("#resultsInventairesJour3").load(
            "ajax/listerStock-InventaireJour3Ajax.php",
            {
              operation: 3,
              nbEntreeInvJour: nbEntreeInvJour3,
              idEntrepotInventaire: idEntrepotInventaire,
              query: query3,
              cb: t,
            }
          ); //load initial records
        } else {
          $("#resultsInventairesJour3").load(
            "ajax/listerStock-InventaireJour3Ajax.php",
            {
              operation: 3,
              nbEntreeInvJour: nbEntreeInvJour3,
              idEntrepotInventaire: idEntrepotInventaire,
              query: "",
              cb: t,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeInvJour3").on("change", function (e) {
    e.preventDefault();

    nbEntreeInvJour3 = $("#nbEntreeInvJour3").val();

    query3 = $("#searchInputInvJour3").val();

    if (query3.length == 0) {
      $("#resultsInventairesJour3").load(
        "ajax/listerStock-InventaireJour3Ajax.php",
        {
          operation: 4,
          nbEntreeInvJour: nbEntreeInvJour3,
          idEntrepotInventaire: idEntrepotInventaire,
          query: "",
          cb: "",
        }
      ); //load initial records
    } else {
      $("#resultsInventairesJour3").load(
        "ajax/listerStock-InventaireJour3Ajax.php",
        {
          operation: 4,
          nbEntreeInvJour: nbEntreeInvJour3,
          idEntrepotInventaire: idEntrepotInventaire,
          query: query3,
          cb: "",
        }
      ); //load initial records
    }

    // $("#resultsInventairesJour3" ).load( "ajax/listerStock-InventaireJour3Ajax.php",{"operation":4,"nbEntreeInvJour":nbEntreeInvJour3,"idEntrepotInventaire":idEntrepotInventaire}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triBIJour3 = 0;

  $(document).on("click", "#resultsInventairesJour3 th", function (e) {
    e.preventDefault();

    //    alert(12)

    query3 = $("#searchInputInvJour3").val();

    nbEntreeInvJour3 = $("#nbEntreeInvJour3").val();

    // $("#resultsInventairesJour3" ).load( "ajax/listerStock-InventaireJour3Ajax.php",{"operation":2,"nbEntreeInvJour":nbEntreeInvJour3,"idEntrepotInventaire":idEntrepotInventaire,"tri":1, "query":""}); //load initial records

    if (triBIJour3 == 1) {
      // alert(triBIJour3)

      if (query3.length == 0) {
        $("#resultsInventairesJour3").load(
          "ajax/listerStock-InventaireJour3Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour3,
            idEntrepotInventaire: idEntrepotInventaire,
            tri: 1,
            query: "",
            cb: "",
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour3").load(
          "ajax/listerStock-InventaireJour3Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour3,
            idEntrepotInventaire: idEntrepotInventaire,
            tri: 1,
            query: query3,
            cb: "",
          }
        ); //load initial records
      }

      triBIJour3 = 0;

      // alert(triBIJour3)
    } else {
      // alert(triBIJour3)

      if (query3.length == 0) {
        $("#resultsInventairesJour3").load(
          "ajax/listerStock-InventaireJour3Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour3,
            idEntrepotInventaire: idEntrepotInventaire,
            tri: 0,
            query: "",
            cb: "",
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour3").load(
          "ajax/listerStock-InventaireJour3Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour3,
            idEntrepotInventaire: idEntrepotInventaire,
            tri: 0,
            query: query3,
            cb: "",
          }
        ); //load initial records
      }

      triBIJour3 = 1;

      // alert(triBIJour3)
    }
  });
});

////////////////////3333333333333333//////////////////

////////////////////222222222222222//////////////////

$(document).ready(function () {
  // alert(5555)
  $(".loading-divI").show(); //show loading element

  nbEntreeInvJour2 = $("#nbEntreeInvJour2").val();

  $("#INVENTAIREJOUR2").on("click", function (e) {
    // alert(2565)
    $("#resultsInventairesJour2").load(
      "ajax/listerStock-InventaireJour2Ajax.php",
      {
        operation: 1,
        nbEntreeInvJour: nbEntreeInvJour2,
        query: "",
        cb: "",
        idEntrepotInventaire: idEntrepotInventaire,
      },
      function (data) {
        //get content from PHP page
        // alert(data)
        console.log(data);
        $(".loading-divI").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#resultsInventairesJour2").on("click", ".pagination a", function (e) {
    // $("#resultsInventairesJour2").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeInvJour2 = $("#nbEntreeInvJour2").val();

    query2 = $("#searchInputInvJour2").val();

    if (query2.length == 0) {
      $("#resultsInventairesJour2").load(
        "ajax/listerStock-InventaireJour2Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvJour: nbEntreeInvJour2,
          query: "",
          cb: "",
          idEntrepotInventaire: idEntrepotInventaire,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsInventairesJour2").load(
        "ajax/listerStock-InventaireJour2Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvJour: nbEntreeInvJour2,
          query: query2,
          cb: "",
          idEntrepotInventaire: idEntrepotInventaire,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsInventairesJour2").load("ajax/listerStock-InventaireJour2Ajax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputInvJour2").on("keyup", function (e) {
    e.preventDefault();

    query2 = $("#searchInputInvJour2").val();

    nbEntreeInvJour2 = $("#nbEntreeInvJour2").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query2.length > 0) {
        $("#resultsInventairesJour2").load(
          "ajax/listerStock-InventaireJour2Ajax.php",
          {
            operation: 3,
            nbEntreeInvJour: nbEntreeInvJour2,
            query: query2,
            cb: t,
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour2").load(
          "ajax/listerStock-InventaireJour2Ajax.php",
          {
            operation: 3,
            nbEntreeInvJour: nbEntreeInvJour2,
            query: "",
            cb: t,
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query2.length > 0) {
          $("#resultsInventairesJour2").load(
            "ajax/listerStock-InventaireJour2Ajax.php",
            {
              operation: 3,
              nbEntreeInvJour: nbEntreeInvJour2,
              query: query2,
              cb: t,
              idEntrepotInventaire: idEntrepotInventaire,
            }
          ); //load initial records
        } else {
          $("#resultsInventairesJour2").load(
            "ajax/listerStock-InventaireJour2Ajax.php",
            {
              operation: 3,
              nbEntreeInvJour: nbEntreeInvJour2,
              query: "",
              cb: t,
              idEntrepotInventaire: idEntrepotInventaire,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeInvJour2").on("change", function (e) {
    e.preventDefault();

    nbEntreeInvJour2 = $("#nbEntreeInvJour2").val();

    query2 = $("#searchInputInvJour2").val();

    if (query2.length == 0) {
      $("#resultsInventairesJour2").load(
        "ajax/listerStock-InventaireJour2Ajax.php",
        {
          operation: 4,
          nbEntreeInvJour: nbEntreeInvJour2,
          query: "",
          cb: "",
          idEntrepotInventaire: idEntrepotInventaire,
        }
      ); //load initial records
    } else {
      $("#resultsInventairesJour2").load(
        "ajax/listerStock-InventaireJour2Ajax.php",
        {
          operation: 4,
          nbEntreeInvJour: nbEntreeInvJour2,
          query: query2,
          cb: "",
          idEntrepotInventaire: idEntrepotInventaire,
        }
      ); //load initial records
    }

    // $("#resultsInventairesJour2" ).load( "ajax/listerStock-InventaireJour2Ajax.php",{"operation":4,"nbEntreeInvJour":nbEntreeInvJour2}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triBIJour2 = 0;

  $(document).on("click", "#resultsInventairesJour2 th", function (e) {
    e.preventDefault();

    //    alert(12)

    query2 = $("#searchInputInvJour2").val();

    nbEntreeInvJour2 = $("#nbEntreeInvJour2").val();

    // $("#resultsInventairesJour2" ).load( "ajax/listerStock-InventaireJour2Ajax.php",{"operation":2,"nbEntreeInvJour":nbEntreeInvJour2,"tri":1, "query":""}); //load initial records

    if (triBIJour2 == 1) {
      // alert(triBIJour2)

      if (query2.length == 0) {
        $("#resultsInventairesJour2").load(
          "ajax/listerStock-InventaireJour2Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour2,
            tri: 1,
            query: "",
            cb: "",
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour2").load(
          "ajax/listerStock-InventaireJour2Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour2,
            tri: 1,
            query: query2,
            cb: "",
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      }

      triBIJour2 = 0;

      // alert(triBIJour2)
    } else {
      // alert(triBIJour2)

      if (query2.length == 0) {
        $("#resultsInventairesJour2").load(
          "ajax/listerStock-InventaireJour2Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour2,
            tri: 0,
            query: "",
            cb: "",
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour2").load(
          "ajax/listerStock-InventaireJour2Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour2,
            tri: 0,
            query: query2,
            cb: "",
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      }

      triBIJour2 = 1;

      // alert(triBIJour2)
    }
  });
});

////////////////////222222222222222//////////////////

////////////////////111111111111111//////////////////

$(document).ready(function () {
  // alert(1112333112)

  $(".loading-divI").show(); //show loading element

  nbEntreeInvJour1 = $("#nbEntreeInvJour1").val();
  idEntrepotInventaire = $("#idEntrepotInventaire").val();
  // alert(idEntrepotInventaire);

  $("#resultsInventairesJour1").load(
    "ajax/listerStock-InventaireJour1Ajax.php",
    {
      operation: 1,
      nbEntreeInvJour: nbEntreeInvJour1,
      query: "",
      cb: "",
      idEntrepotInventaire: idEntrepotInventaire,
    },
    function (data) {
      //get content from PHP page
      // alert(data)
      console.log(data);
      $(".loading-divI").hide(); //once done, hide loading element
    }
  );

  //executes code below when user click on pagination links

  $("#resultsInventairesJour1").on("click", ".pagination a", function (e) {
    // $("#resultsInventairesJour1").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeInvJour1 = $("#nbEntreeInvJour1").val();

    query1 = $("#searchInputInvJour1").val();

    if (query1.length == 0) {
      $("#resultsInventairesJour1").load(
        "ajax/listerStock-InventaireJour1Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvJour: nbEntreeInvJour1,
          query: "",
          cb: "",
          idEntrepotInventaire: idEntrepotInventaire,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsInventairesJour1").load(
        "ajax/listerStock-InventaireJour1Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvJour: nbEntreeInvJour1,
          query: query1,
          cb: "",
          idEntrepotInventaire: idEntrepotInventaire,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsInventairesJour1").load("ajax/listerStock-InventaireJour1Ajax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputInvJour1").on("keyup", function (e) {
    e.preventDefault();

    query1 = $("#searchInputInvJour1").val();

    nbEntreeInvJour1 = $("#nbEntreeInvJour1").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query1.length > 0) {
        $("#resultsInventairesJour1").load(
          "ajax/listerStock-InventaireJour1Ajax.php",
          {
            operation: 3,
            nbEntreeInvJour: nbEntreeInvJour1,
            query: query1,
            cb: t,
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour1").load(
          "ajax/listerStock-InventaireJour1Ajax.php",
          {
            operation: 3,
            nbEntreeInvJour: nbEntreeInvJour1,
            query: "",
            cb: t,
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query1.length > 0) {
          $("#resultsInventairesJour1").load(
            "ajax/listerStock-InventaireJour1Ajax.php",
            {
              operation: 3,
              nbEntreeInvJour: nbEntreeInvJour1,
              query: query1,
              cb: t,
              idEntrepotInventaire: idEntrepotInventaire,
            }
          ); //load initial records
        } else {
          $("#resultsInventairesJour1").load(
            "ajax/listerStock-InventaireJour1Ajax.php",
            {
              operation: 3,
              nbEntreeInvJour: nbEntreeInvJour1,
              query: "",
              cb: t,
              idEntrepotInventaire: idEntrepotInventaire,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeInvJour1").on("change", function (e) {
    e.preventDefault();

    nbEntreeInvJour1 = $("#nbEntreeInvJour1").val();

    query1 = $("#searchInputInvJour1").val();

    if (query1.length == 0) {
      $("#resultsInventairesJour1").load(
        "ajax/listerStock-InventaireJour1Ajax.php",
        {
          operation: 4,
          nbEntreeInvJour: nbEntreeInvJour1,
          query: "",
          cb: "",
          idEntrepotInventaire: idEntrepotInventaire,
        }
      ); //load initial records
    } else {
      $("#resultsInventairesJour1").load(
        "ajax/listerStock-InventaireJour1Ajax.php",
        {
          operation: 4,
          nbEntreeInvJour: nbEntreeInvJour1,
          query: query1,
          cb: "",
          idEntrepotInventaire: idEntrepotInventaire,
        }
      ); //load initial records
    }

    // $("#resultsInventairesJour1" ).load( "ajax/listerStock-InventaireJour1Ajax.php",{"operation":4,"nbEntreeInvJour":nbEntreeInvJour1}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triBIJour1 = 0;

  $(document).on("click", "#resultsInventairesJour1 th", function (e) {
    e.preventDefault();

    //    alert(12)

    query1 = $("#searchInputInvJour1").val();

    nbEntreeInvJour1 = $("#nbEntreeInvJour1").val();

    // $("#resultsInventairesJour1" ).load( "ajax/listerStock-InventaireJour1Ajax.php",{"operation":2,"nbEntreeInvJour":nbEntreeInvJour1,"tri":1, "query":""}); //load initial records

    if (triBIJour1 == 1) {
      // alert(triBIJour1)

      if (query1.length == 0) {
        $("#resultsInventairesJour1").load(
          "ajax/listerStock-InventaireJour1Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour1,
            tri: 1,
            query: "",
            cb: "",
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour1").load(
          "ajax/listerStock-InventaireJour1Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour1,
            tri: 1,
            query: query1,
            cb: "",
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      }

      triBIJour1 = 0;

      // alert(triBIJour1)
    } else {
      // alert(triBIJour1)

      if (query1.length == 0) {
        $("#resultsInventairesJour1").load(
          "ajax/listerStock-InventaireJour1Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour1,
            tri: 0,
            query: "",
            cb: "",
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      } else {
        $("#resultsInventairesJour1").load(
          "ajax/listerStock-InventaireJour1Ajax.php",
          {
            operation: 2,
            nbEntreeInvJour: nbEntreeInvJour1,
            tri: 0,
            query: query1,
            cb: "",
            idEntrepotInventaire: idEntrepotInventaire,
          }
        ); //load initial records
      }

      triBIJour1 = 1;

      // alert(triBIJour1)
    }
  });
});

///////////////////////111111111111///////////////////

/** Debut Rechercher Produit Vendu Aujourd'hui*/

$(function () {
  $(document).on("click", ".btn_disabled_after_click", function () {
    // alert(11)
    $(this).prop("disabled", true);
  });

  // alert(111)

  $(document).on("keyup", "#produitVenduB", function (e) {
    e.preventDefault();

    var query = $("#produitVenduB").val();

    // alert(query)

    if (query.length > 0 || query.length != "") {
      $("#produitVenduB").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 8,

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
              alert("La requête ss");
            },
          });
        },
      });

      $("#produitVenduB").focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        $(".img-load-search").show();

        var designation = $("#searchProdForm .typeahead li.active").text();

        $("#produitVenduB").val(designation);

        $("#venteContent").load(
          "ajax/loadContainerAjax.php",
          { produit: designation },
          function (data) {
            //get content from PHP page

            // alert(data)

            // $("#loading_gif_modal").modal("hide");img-load-search

            $("#produitVenduB").val(designation);

            $(".img-load-search").hide();
          }
        );

        // $("#searchProdForm").submit();
      }

      $(document).on(
        "click",
        '#searchProdForm .typeahead li a[class="dropdown-item"]',
        function (e) {
          e.preventDefault();

          $(".img-load-search").show();

          var designation = $("#searchProdForm .typeahead li.active").text();

          $("#produitVenduB").val(designation);

          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
            { produit: designation },
            function (data) {
              //get content from PHP page

              // alert(data)

              // $("#loading_gif_modal").modal("hide");

              $("#produitVenduB").val(designation);

              $(".img-load-search").hide();
            }
          );

          // $("#searchProdForm").submit();
        }
      );
    } else {
      $(".img-load-search").show();

      // window.location.href = "insertionLigneLight.php";

      $("#venteContent").load("ajax/loadContainerAjax.php", function (data) {
        //get content from PHP page

        // alert(data)

        // $("#loading_gif_modal").modal("hide");

        $(".img-load-search").hide();
      });
    }
  });

  $("#produitVenduB").val($("#produitAfter").val());
});

/** Fin Rechercher Produit Vendu Aujourd'hui*/

/************** Début chargement container vente*********************/

$(document).ready(function () {
  // alert(1)

  // alert(100)$("html,body").scrollTop(0);

  $("html,body").animate({ scrollTop: 0 }, 5);

  //executes code below when user click on pagination links

  $(document).on("click", ".paginationLoader a", function (e) {
    // $("#resultsProductsPrix").on( "click", function (e){

    // alert(2)

    e.preventDefault();

    // $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    $("#img-load-page" + page).show();

    //  alert(page)

    // $(".loading-gif").show();

    $("#venteContent").load(
      "ajax/loadContainerAjax.php",
      { page: page },
      function (data) {
        //get content from PHP page

        // alert(data)

        $("#img-load-page" + page).hide();

        // $("#loading_gif_modal").modal("hide");
      }
    );

    // $("#resultsProductsPrix").load("ajax/listerPrixAjax.php",{"page":page,"operation":1}
  });

  // $(document).on('click', "#closeInfo", function() {

  //     // alert(100)

  //     $("#msg_info_1").attr('class', "modal fade out")

  //     $(".modal-backdrop").remove();

  // });

  // $(document).on("keyup", ".codeBarreLigneVt" , function(e) {

  //     e.preventDefault();

  //     idPanier=$(this).attr('data-idPanier');

  //     value=$('#panier_'+idPanier).val()

  //     var keycode = (e.keyCode ? e.keyCode : e.which);

  //     if (keycode == '13' && value=="") {

  //     // $("#loading_gif_modal").modal("show");

  //     $(".img-load-terminer").show();

  //     remise=$('#val_remise'+idPanier).val()

  //     compte=$('#compte'+idPanier).val()

  //     clientInput=$('#clientInput'+idPanier).val()

  //     avanceInput=$('#avanceInput'+idPanier).val()

  //     compteAvance=$('#compteAvance'+idPanier).val()

  //     versement=$('#versement'+idPanier).val()

  //     // alert(idPanier+"/"+remise+"/"+compte+"/"+clientInput+"/"+avanceInput+"/"+compteAvance+"/"+versement)

  //     $.ajax({

  //         url: "ajax/operationPanierAjax.php",

  //         method: "POST",

  //         data: {

  //             operation: 1,

  //             idPagnet: idPanier,

  //             remise: remise,

  //             compte: compte,

  //             compteAvance: compteAvance,

  //             clientInput: clientInput,

  //             avanceInput: avanceInput,

  //             versement: versement,

  //             btnImprimerFacture: 'btnImprimerFacture',

  //         },

  //         success: function(data) {

  //             // alert(data)

  //             if(data == '1' || data == '') {

  //                 $("#venteContent" ).load( "ajax/loadContainerAjax.php", function(data){ //get content from PHP page

  //                     // $("#addPanier").click();

  //                     $(".loading-gif").hide();

  //                     // $("#loading_gif_modal").modal("hide");

  //                     $(".img-load-terminer").hide();

  //                 });

  //             }else {

  //                 $('#msg_info_body').html(data);

  //                 $('#msg_info_1').modal('show');

  //                 $(".img-load-terminer").hide();

  //                 $("#venteContent" ).load( "ajax/loadContainerAjax.php", function(data){ //get content from PHP page

  //                     // alert(data)

  //                     $(".loading-gif").hide();

  //                     // $("#loading_gif_modal").modal("hide");

  //                 });

  //             }

  //         },

  //         error: function() {

  //             alert("La requête ");

  //         },

  //         dataType: "text"

  //     });

  //     }

  //     // var avance = $(this).val();

  //     // var idPanier = $(this).attr('data-idPanier');

  //     // var total = $("#somme_Apayer"+idPanier).text();

  //     // // console.log(total);

  //     // // alert(1)

  //     // if (avance > parseInt(total)) {

  //     //     // alert(2)

  //     //     v = $('#avanceInput'+idPanier).val();

  //     //     // $('#avanceInput'+idPanier).blur();

  //     //     $('#avanceInput'+idPanier).val(v.substring(0, v.length-1));

  //     //     $('#msg_info_avance').modal('show');

  //     // }

  // });

  $(document).on("keyup", ".versement, .avanceInput", function (e) {
    e.preventDefault();

    idPanier = $(this).attr("data-idPanier");

    // isPrint=$(this).attr('data-print');
    // if (isPrint==1) {
    //     $("#ticket"+idPanier).submit();
    //     // alert(152);
    // }

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
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

      compteAvance_p = $(
        "#compteAvance" + idPanier + " option:selected"
      ).text();

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

            $(".filedReadonly" + idPanier).attr("disabled", "disabled");

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
            url: "ajax/operationPanierAjax.php",

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
              // alert(data);

              if (data == "1" || data == "") {
                // $("#venteContent" ).load( "ajax/loadContainerAjax.php", function(data){ //get content from PHP page
                //     // $("#addPanier").click();
                //     $(".loading-gif").hide();
                //     // $("#loading_gif_modal").modal("hide");
                //     $(".img-load-terminer").hide();
                // });
              } else {
                $("#msg_info_body").html(data);

                $("#msg_info_1").modal("show");

                $(".img-load-terminer").hide();

                $("#venteContent").load(
                  "ajax/loadContainerAjax.php",
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

          $(".filedReadonly" + idPanier).attr("disabled", "disabled");

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
          url: "ajax/operationPanierAjax.php",

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
            // alert(data);

            if (data == "1" || data == "") {
              // $("#venteContent" ).load( "ajax/loadContainerAjax.php", function(data){ //get content from PHP page
              //     // $("#addPanier").click();
              //     $(".loading-gif").hide();
              //     // $("#loading_gif_modal").modal("hide");
              //     $(".img-load-terminer").hide();
              // });
            } else {
              $("#msg_info_body").html(data);

              $("#msg_info_1").modal("show");

              $(".img-load-terminer").hide();

              $("#venteContent").load(
                "ajax/loadContainerAjax.php",
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
    }

    // var avance = $(this).val();

    // var idPanier = $(this).attr('data-idPanier');

    // var total = $("#somme_Apayer"+idPanier).text();

    // // console.log(total);

    // // alert(1)

    // if (avance > parseInt(total)) {

    //     // alert(2)

    //     v = $('#avanceInput'+idPanier).val();

    //     // $('#avanceInput'+idPanier).blur();

    //     $('#avanceInput'+idPanier).val(v.substring(0, v.length-1));

    //     $('#msg_info_avance').modal('show');

    // }
  });

  $(document).on("keyup", ".avanceInput", function () {
    var avance = $(this).val();

    var idPanier = $(this).attr("data-idPanier");

    var total = $("#somme_Apayer" + idPanier).text();

    // console.log(total);

    // alert(1)

    if (avance > parseInt(total)) {
      // alert(2)

      v = $("#avanceInput" + idPanier).val();

      // $('#avanceInput'+idPanier).blur();

      $("#avanceInput" + idPanier).val(v.substring(0, v.length - 1));

      $("#msg_info_avance").modal("show");
    }
  });

  $(document).on("click", ".modeEditionBtnLoader", function () {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-editer").show();

    var id = $(this).attr("id");

    result = id.split("-");

    idPanier = result[1];

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 30,

        idPanier: idPanier,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)

        // $("#clientInput"+idPanier).val('');

        // $("#avanceInput"+idPanier).val('');

        if (data == 1) {
          // window.location.reload();

          $(".img-load-editer").hide();

          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              // $("#loading_gif_modal").modal("hide");

              // $("#addPanier").click();
            }
          );
        } else {
          $("#msg_edit_pagnet").modal("show");

          // $("#loading_gif_modal").modal("hide");

          $(".img-load-editer").hide();
        }
      },

      error: function () {
        alert("La requête ss");
      },
    });
  });

  // $(document).on('click', ".modeEditionBtnET", function() {

  //     var id = $(this).attr('id');

  //     result = id.split('-');

  //     idPanier = result[1]

  //     $.ajax({

  //         url: "ajax/vendreLigneAjax.php",

  //         method: "POST",

  //         data: {

  //             operation: 31,

  //             idPanier: idPanier

  //         },

  //         dataType: "text",

  //         success: function(data) {

  //             // alert(data)

  //             // $("#clientInput"+idPanier).val('');

  //             // $("#avanceInput"+idPanier).val('');

  //             if (data == 1) {

  //                 window.location.reload();

  //             } else {

  //                 $('#msg_edit_pagnet').modal('show');

  //             }

  //         },

  //         error: function() {

  //             alert("La requête ss");

  //         }

  //     });

  // });

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $(document).on("keyup", ".inputRetourApres", function (e) {
    e.preventDefault();

    // alert(12354)

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // $("#loading_gif_modal").modal("show");

      $(".img-load-retourApres").show();

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
        url: "ajax/operationPanierAjax.php",

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

            $("#venteContent").load(
              "ajax/loadContainerAjax.php",
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

            $(".img-load-retourApres").hide();
          }
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    }
  });

  $(document).on("click", ".btnRetourApres", function (e) {
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
      url: "ajax/operationPanierAjax.php",

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

          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
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

  $(document).on("click", ".btnRetourAvant", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-retourAvant").show();

    $(".btnRetourAvant").attr("disabled", "disabled");

    numligne = $(this).attr("data-numligne");

    $.ajax({
      url: "ajax/operationPanierAjax.php",

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

          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              $("#msg_rtrnAvant_ligne" + numligne).modal("hide");

              $(".modal-backdrop").remove();

              $(".img-load-retourAvant").hide();

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

          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
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

  $(document).on("click", ".btnRetournerPagnet", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-retournerPanier").show();

    $(".btnRetournerPagnet").attr("disabled", "disabled");

    idPanier = $(this).attr("data-idPanier");

    $.ajax({
      url: "ajax/operationPanierAjax.php",

      method: "POST",

      data: {
        operation: 1,

        btnRetournerPagnet: "btnRetournerPagnet",

        idPagnet: idPanier,
      },

      success: function (data) {
        // alert(data)

        /****************msg_rtrn_pagnet***************** */

        if (data == "1") {
          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              $("#msg_ann_pagnet" + idPanier).modal("hide");

              $(".modal-backdrop").remove();

              $(".img-load-annulerPanier").hide();

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

          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
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

  $(document).on("click", ".btnAnnulerPagnet", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-annulerPanier").show();

    $(".btnAnnulerPagnet").attr("disabled", "disabled");

    idPanier = $(this).attr("data-idPanier");

    $.ajax({
      url: "ajax/operationPanierAjax.php",

      method: "POST",

      data: {
        operation: 1,

        btnAnnulerPagnet: "btnAnnulerPagnet",

        idPagnet: idPanier,
      },

      success: function (data) {
        // alert(data)

        /****************msg_ann_pagnet***************** */

        if (data == "1") {
          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
            function (data) {
              //get content from PHP page

              // alert(data)

              $(".loading-gif").hide();

              $("#msg_ann_pagnet" + idPanier).modal("hide");

              $(".modal-backdrop").remove();

              $(".img-load-annulerPanier").hide();

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

          $("#venteContent").load(
            "ajax/loadContainerAjax.php",
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

  $(document).on("click", ".btn_Termine_Panier", function (e) {
    idPanier = $(this).attr("data-idPanier");

    isPrint = $(this).attr("data-print");
    // if (isPrint==1) {
    //     $("#ticket"+idPanier).submit();
    //     // alert(152);
    // }

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

          $(".filedReadonly" + idPanier).attr("disabled", "disabled");

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
          url: "ajax/operationPanierAjax.php",

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
            // console.log(data);

            if (data == "1" || data == "") {
              // $("#venteContent" ).load( "ajax/loadContainerAjax.php", function(data){ //get content from PHP page
              //     // $("#addPanier").click();
              //     $(".loading-gif").hide();
              //     // $("#loading_gif_modal").modal("hide");
              //     $(".img-load-terminer").hide();
              // });
            } else {
              $("#msg_info_body").html(data);

              $("#msg_info_1").modal("show");

              $(".img-load-terminer").hide();

              $("#venteContent").load(
                "ajax/loadContainerAjax.php",
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

        $(".filedReadonly" + idPanier).attr("disabled", "disabled");

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
        url: "ajax/operationPanierAjax.php",

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
          // console.log(data);

          if (data == "1" || data == "") {
            // $("#venteContent" ).load( "ajax/loadContainerAjax.php", function(data){ //get content from PHP page
            //     // $("#addPanier").click();
            //     $(".loading-gif").hide();
            //     // $("#loading_gif_modal").modal("hide");
            //     $(".img-load-terminer").hide();
            // });
          } else {
            $("#msg_info_body").html(data);

            $("#msg_info_1").modal("show");

            $(".img-load-terminer").hide();

            $("#venteContent").load(
              "ajax/loadContainerAjax.php",
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

  $(document).on("click", "#addPanier", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load").show();

    $("#addPanier").attr("disabled", "disabled");

    $.ajax({
      url: "ajax/operationPanierAjax.php",

      method: "POST",

      data: {
        operation: 1,

        btnSavePagnetVente: "btnSavePagnetVente",
      },

      success: function (data) {
        // alert(data)

        $("#venteContent").load("ajax/loadContainerAjax.php", function (data) {
          //get content from PHP page

          // alert(data)

          $(".loading-gif").hide();

          // $("#loading_gif_modal").modal("hide");

          $(".img-load").hide();

          $(".codeBarreLigneVt").focus();
        });
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    $("#collapse1").collapse("hide");

    var lcd = $("#lcd_Machine").val();

    if (lcd == 1) {
      var quantite = " ";

      var prix = 0;

      $.ajax({
        url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

        type: "GET", // data type (can be get, post, put, delete)

        data: {
          quantite: quantite,

          prix: prix,
        }, // data in json format

        async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

        success: function (response) {
          console.log(response);
        },
      });
    }
  });

  $("#venteContent").load("ajax/loadContainerAjax.php", function (data) {
    //get content from PHP page

    // alert(data)

    $(".loading-gif").hide();

    // $("#addPanier").click();
  });
});

/*************** Fin chargement container vente ********************/

/*************** Début lister prix boutique ***************/

$(document).ready(function () {
  // alert(1)

  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreePrix = $("#nbEntreePrix").val();

  $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
    operation: 1,
    nbEntreePrix: nbEntreePrix,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsPrix").on("click", ".pagination a", function (e) {
    // $("#resultsProductsPrix").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreePrix = $("#nbEntreePrix").val();

    query = $("#searchInputPrix").val();

    if (query.length == 0) {
      $("#resultsProductsPrix").load(
        "ajax/listerPrixAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreePrix: nbEntreePrix,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsPrix").load(
        "ajax/listerPrixAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreePrix: nbEntreePrix,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsPrix").load("ajax/listerPrixAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputPrix").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputPrix").val();

    nbEntreePrix = $("#nbEntreePrix").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
          operation: 3,
          nbEntreePrix: nbEntreePrix,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
          operation: 3,
          nbEntreePrix: nbEntreePrix,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
            operation: 3,
            nbEntreePrix: nbEntreePrix,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
            operation: 3,
            nbEntreePrix: nbEntreePrix,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreePrix").on("change", function (e) {
    e.preventDefault();

    nbEntreePrix = $("#nbEntreePrix").val();

    query = $("#searchInputPrix").val();

    if (query.length == 0) {
      $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
        operation: 4,
        nbEntreePrix: nbEntreePrix,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
        operation: 4,
        nbEntreePrix: nbEntreePrix,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsProductsPrix" ).load( "ajax/listerPrixAjax.php",{"operation":4,"nbEntreePrix":nbEntreePrix}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triPrix = 0;

  $(document).on("click", "#resultsProductsPrix th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputPrix").val();

    nbEntreePrix = $("#nbEntreePrix").val();

    // $("#resultsProductsPrix" ).load( "ajax/listerPrixAjax.php",{"operation":2,"nbEntreePrix":nbEntreePrix,"tri":1, "query":""}); //load initial records

    if (triPrix == 1) {
      // alert(triPrix)

      if (query.length == 0) {
        $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
          operation: 2,
          nbEntreePrix: nbEntreePrix,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
          operation: 2,
          nbEntreePrix: nbEntreePrix,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triPrix = 0;

      // alert(triPrix)
    } else {
      // alert(triPrix)

      if (query.length == 0) {
        $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
          operation: 2,
          nbEntreePrix: nbEntreePrix,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsPrix").load("ajax/listerPrixAjax.php", {
          operation: 2,
          nbEntreePrix: nbEntreePrix,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triPrix = 1;

      // alert(triPrix)
    }
  });
});

/*************** Fin lister prix boutique ***************/

/*************** Début modification pour les tableaux initialisation ***************/

/*************** Début modification boutique***************/

$(document).ready(function () {
  // alert(1)

  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeBInit = $("#nbEntreeBInit").val();

  value = $("#catalogue").val();

  $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
    operation: 1,
    nbEntreeBInit: nbEntreeBInit,
    query: "",
    cb: "",
    nom: value,
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsInitB").on("click", ".pagination a", function (e) {
    // $("#resultsProductsInitB").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeBInit = $("#nbEntreeBInit").val();

    query = $("#searchInputBInit").val();

    value = $("#catalogue").val();

    if (query.length == 0) {
      $("#resultsProductsInitB").load(
        "ajax/listerCatalogueAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeBInit: nbEntreeBInit,
          query: "",
          cb: "",
          nom: value,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsInitB").load(
        "ajax/listerCatalogueAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeBInit: nbEntreeBInit,
          query: query,
          cb: "",
          nom: value,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputBInit").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputBInit").val();

    nbEntreeBInit = $("#nbEntreeBInit").val();

    value = $("#catalogue").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
          operation: 3,
          nbEntreeBInit: nbEntreeBInit,
          query: query,
          cb: t,
          nom: value,
        }); //load initial records
      } else {
        $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
          operation: 3,
          nbEntreeBInit: nbEntreeBInit,
          query: "",
          cb: t,
          nom: value,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
            operation: 3,
            nbEntreeBInit: nbEntreeBInit,
            query: query,
            cb: t,
            nom: value,
          }); //load initial records
        } else {
          $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
            operation: 3,
            nbEntreeBInit: nbEntreeBInit,
            query: "",
            cb: t,
            nom: value,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeBInit").on("change", function (e) {
    e.preventDefault();

    nbEntreeBInit = $("#nbEntreeBInit").val();

    query = $("#searchInputBInit").val();

    value = $("#catalogue").val();

    if (query.length == 0) {
      $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
        operation: 4,
        nbEntreeBInit: nbEntreeBInit,
        query: "",
        cb: "",
        nom: value,
      }); //load initial records
    } else {
      $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
        operation: 4,
        nbEntreeBInit: nbEntreeBInit,
        query: query,
        cb: "",
        nom: value,
      }); //load initial records
    }

    // $("#resultsProductsInitB" ).load( "ajax/listerCatalogueAjax.php",{"operation":4,"nbEntreeBInit":nbEntreeBInit}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triBInit = 0;

  $(document).on("click", "#resultsProductsInitB th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputBInit").val();

    nbEntreeBInit = $("#nbEntreeBInit").val();

    value = $("#catalogue").val();

    // $("#resultsProductsInitB" ).load( "ajax/listerCatalogueAjax.php",{"operation":2,"nbEntreeBInit":nbEntreeBInit,"tri":1, "query":""}); //load initial records

    if (triBInit == 1) {
      // alert(triB)

      if (query.length == 0) {
        $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
          operation: 2,
          nbEntreeBInit: nbEntreeBInit,
          tri: 1,
          query: "",
          cb: "",
          nom: value,
        }); //load initial records
      } else {
        $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
          operation: 2,
          nbEntreeBInit: nbEntreeBInit,
          tri: 1,
          query: query,
          cb: "",
          nom: value,
        }); //load initial records
      }

      triBInit = 0;

      // alert(triB)
    } else {
      // alert(triB)

      if (query.length == 0) {
        $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
          operation: 2,
          nbEntreeBInit: nbEntreeBInit,
          tri: 0,
          query: "",
          cb: "",
          nom: value,
        }); //load initial records
      } else {
        $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
          operation: 2,
          nbEntreeBInit: nbEntreeBInit,
          tri: 0,
          query: query,
          cb: "",
          nom: value,
        }); //load initial records
      }

      triBInit = 1;

      // alert(triB)
    }
  });
});

/*************** Début modification boutique***************/

/*************** Début modification pharmacie***************/

$(document).ready(function () {
  // alert(1)

  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreePhInit = $("#nbEntreePhInit").val();

  value = $("#cataloguePH").val();

  $("#resultsProductsInit").load("ajax/listerCatalogue-PharmacieAjax.php", {
    operation: 1,
    nbEntreePhInit: nbEntreePhInit,
    query: "",
    cb: "",
    nom: value,
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsInit").on("click", ".pagination a", function (e) {
    // $("#resultsProductsInit").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreePhInit = $("#nbEntreePhInit").val();

    query = $("#searchInputPhInit").val();

    value = $("#cataloguePH").val();

    if (query.length == 0) {
      $("#resultsProductsInit").load(
        "ajax/listerCatalogue-PharmacieAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreePhInit: nbEntreePhInit,
          query: "",
          cb: "",
          nom: value,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsInit").load(
        "ajax/listerCatalogue-PharmacieAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreePhInit: nbEntreePhInit,
          query: query,
          cb: "",
          nom: value,
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsInit").load("ajax/listerCatalogue-PharmacieAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputPhInit").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputPhInit").val();

    nbEntreePhInit = $("#nbEntreePhInit").val();

    value = $("#cataloguePH").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsInit").load(
          "ajax/listerCatalogue-PharmacieAjax.php",
          {
            operation: 3,
            nbEntreePhInit: nbEntreePhInit,
            query: query,
            cb: t,
            nom: value,
          }
        ); //load initial records
      } else {
        $("#resultsProductsInit").load(
          "ajax/listerCatalogue-PharmacieAjax.php",
          {
            operation: 3,
            nbEntreePhInit: nbEntreePhInit,
            query: "",
            cb: t,
            nom: value,
          }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsInit").load(
            "ajax/listerCatalogue-PharmacieAjax.php",
            {
              operation: 3,
              nbEntreePhInit: nbEntreePhInit,
              query: query,
              cb: t,
              nom: value,
            }
          ); //load initial records
        } else {
          $("#resultsProductsInit").load(
            "ajax/listerCatalogue-PharmacieAjax.php",
            {
              operation: 3,
              nbEntreePhInit: nbEntreePhInit,
              query: "",
              cb: t,
              nom: value,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreePhInit").on("change", function (e) {
    e.preventDefault();

    nbEntreePhInit = $("#nbEntreePhInit").val();

    query = $("#searchInputPhInit").val();

    value = $("#cataloguePH").val();

    if (query.length == 0) {
      $("#resultsProductsInit").load("ajax/listerCatalogue-PharmacieAjax.php", {
        operation: 4,
        nbEntreePhInit: nbEntreePhInit,
        query: "",
        cb: "",
        nom: value,
      }); //load initial records
    } else {
      $("#resultsProductsInit").load("ajax/listerCatalogue-PharmacieAjax.php", {
        operation: 4,
        nbEntreePhInit: nbEntreePhInit,
        query: query,
        cb: "",
        nom: value,
      }); //load initial records
    }

    // $("#resultsProductsInit" ).load( "ajax/listerCatalogue-PharmacieAjax.php",{"operation":4,"nbEntreePhInit":nbEntreePhInit}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triPhInit = 0;

  $(document).on("click", "#resultsProductsInit th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputPhInit").val();

    nbEntreePhInit = $("#nbEntreePhInit").val();

    value = $("#cataloguePH").val();

    // $("#resultsProductsInit" ).load( "ajax/listerCatalogue-PharmacieAjax.php",{"operation":2,"nbEntreePhInit":nbEntreePhInit,"tri":1, "query":""}); //load initial records

    if (triPhInit == 1) {
      // alert(triB)

      if (query.length == 0) {
        $("#resultsProductsInit").load(
          "ajax/listerCatalogue-PharmacieAjax.php",
          {
            operation: 2,
            nbEntreePhInit: nbEntreePhInit,
            tri: 1,
            query: "",
            cb: "",
            nom: value,
          }
        ); //load initial records
      } else {
        $("#resultsProductsInit").load(
          "ajax/listerCatalogue-PharmacieAjax.php",
          {
            operation: 2,
            nbEntreePhInit: nbEntreePhInit,
            tri: 1,
            query: query,
            cb: "",
            nom: value,
          }
        ); //load initial records
      }

      triPhInit = 0;

      // alert(triB)
    } else {
      // alert(triB)

      if (query.length == 0) {
        $("#resultsProductsInit").load(
          "ajax/listerCatalogue-PharmacieAjax.php",
          {
            operation: 2,
            nbEntreePhInit: nbEntreePhInit,
            tri: 0,
            query: "",
            cb: "",
            nom: value,
          }
        ); //load initial records
      } else {
        $("#resultsProductsInit").load(
          "ajax/listerCatalogue-PharmacieAjax.php",
          {
            operation: 2,
            nbEntreePhInit: nbEntreePhInit,
            tri: 0,
            query: query,
            cb: "",
            nom: value,
          }
        ); //load initial records
      }

      triPhInit = 1;

      // alert(triB)
    }
  });
});

/*************** Début modification pharmacie***************/

/*************** Début modification pour les tableaux initialisation ***************/

/*************** Début modification inventaire boutique ***************/

$(document).ready(function () {
  // alert(1)

  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeInvD = $("#nbEntreeInvD").val();

  $("#invDEvent").on("click", function (e) {
    $("#resultsInventairesD").load(
      "ajax/listerInventaireAjax.php",
      { operation: 1, nbEntreeInvD: nbEntreeInvD, query: "", cb: "" },
      function (data) {
        //get content from PHP page

        $(".loading-div").hide(); //once done, hide loading element

        // alert(data)
      }
    );
  });

  //executes code below when user click on pagination links

  $("#resultsInventairesD").on("click", ".pagination a", function (e) {
    // $("#resultsInventairesD").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeInvD = $("#nbEntreeInvD").val();

    query = $("#searchInputInvD").val();

    if (query.length == 0) {
      $("#resultsInventairesD").load(
        "ajax/listerInventaireAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvD: nbEntreeInvD,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element

          // alert(data)
        }
      );
    } else {
      $("#resultsInventairesD").load(
        "ajax/listerInventaireAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInvD: nbEntreeInvD,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsInventairesD").load("ajax/listerInventaireAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputInvD").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputInvD").val();

    nbEntreeInvD = $("#nbEntreeInvD").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
          operation: 3,
          nbEntreeInvD: nbEntreeInvD,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
          operation: 3,
          nbEntreeInvD: nbEntreeInvD,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
            operation: 3,
            nbEntreeInvD: nbEntreeInvD,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
            operation: 3,
            nbEntreeInvD: nbEntreeInvD,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeInvD").on("change", function (e) {
    e.preventDefault();

    nbEntreeInvD = $("#nbEntreeInvD").val();

    query = $("#searchInputInvD").val();

    if (query.length == 0) {
      $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
        operation: 4,
        nbEntreeInvD: nbEntreeInvD,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
        operation: 4,
        nbEntreeInvD: nbEntreeInvD,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsInventairesD" ).load( "ajax/listerInventaireAjax.php",{"operation":4,"nbEntreeInvD":nbEntreeInvD}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triBID = 0;

  $(document).on("click", "#resultsInventairesD th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputInvD").val();

    nbEntreeInvD = $("#nbEntreeInvD").val();

    // $("#resultsInventairesD" ).load( "ajax/listerInventaireAjax.php",{"operation":2,"nbEntreeInvD":nbEntreeInvD,"tri":1, "query":""}); //load initial records

    if (triBID == 1) {
      // alert(triBID)

      if (query.length == 0) {
        $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
          operation: 2,
          nbEntreeInvD: nbEntreeInvD,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
          operation: 2,
          nbEntreeInvD: nbEntreeInvD,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triBID = 0;

      // alert(triBID)
    } else {
      // alert(triBID)

      if (query.length == 0) {
        $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
          operation: 2,
          nbEntreeInvD: nbEntreeInvD,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsInventairesD").load("ajax/listerInventaireAjax.php", {
          operation: 2,
          nbEntreeInvD: nbEntreeInvD,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triBID = 1;

      // alert(triBID)
    }
  });
});

/*************** Début modification boutique***************/

$(document).ready(function () {
  // alert(1)

  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $(".loading-divI").show(); //show loading element

  nbEntreeInv = $("#nbEntreeInv").val();

  $("#resultsInventaires").load(
    "ajax/listerStock-InventaireAjax.php",
    { operation: 1, nbEntreeInv: nbEntreeInv, query: "", cb: "" },
    function () {
      //get content from PHP page

      $(".loading-divI").hide(); //once done, hide loading element
    }
  );

  //executes code below when user click on pagination links

  $("#resultsInventaires").on("click", ".pagination a", function (e) {
    // $("#resultsInventaires").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeInv = $("#nbEntreeInv").val();

    query = $("#searchInputInv").val();

    if (query.length == 0) {
      $("#resultsInventaires").load(
        "ajax/listerStock-InventaireAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInv: nbEntreeInv,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsInventaires").load(
        "ajax/listerStock-InventaireAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeInv: nbEntreeInv,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputInv").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputInv").val();

    nbEntreeInv = $("#nbEntreeInv").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
          operation: 3,
          nbEntreeInv: nbEntreeInv,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
          operation: 3,
          nbEntreeInv: nbEntreeInv,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
            operation: 3,
            nbEntreeInv: nbEntreeInv,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
            operation: 3,
            nbEntreeInv: nbEntreeInv,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeInv").on("change", function (e) {
    e.preventDefault();

    nbEntreeInv = $("#nbEntreeInv").val();

    query = $("#searchInputInv").val();

    if (query.length == 0) {
      $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
        operation: 4,
        nbEntreeInv: nbEntreeInv,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
        operation: 4,
        nbEntreeInv: nbEntreeInv,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsInventaires" ).load( "ajax/listerStock-InventaireAjax.php",{"operation":4,"nbEntreeInv":nbEntreeInv}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triBI = 0;

  $(document).on("click", "#resultsInventaires th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputInv").val();

    nbEntreeInv = $("#nbEntreeInv").val();

    // $("#resultsInventaires" ).load( "ajax/listerStock-InventaireAjax.php",{"operation":2,"nbEntreeInv":nbEntreeInv,"tri":1, "query":""}); //load initial records

    if (triBI == 1) {
      // alert(triBI)

      if (query.length == 0) {
        $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
          operation: 2,
          nbEntreeInv: nbEntreeInv,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
          operation: 2,
          nbEntreeInv: nbEntreeInv,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triBI = 0;

      // alert(triBI)
    } else {
      // alert(triBI)

      if (query.length == 0) {
        $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
          operation: 2,
          nbEntreeInv: nbEntreeInv,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsInventaires").load("ajax/listerStock-InventaireAjax.php", {
          operation: 2,
          nbEntreeInv: nbEntreeInv,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triBI = 1;

      // alert(triBI)
    }
  });
});

/*************** Début modification boutique***************/

/*************** Fin modification tableaux inventaire ***************/

/*************** Début modification tableaux ajout stock entrepot***************/

$(document).ready(function () {
  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeASEt = $("#nbEntreeASEt").val();

  $("#resultsProductsASE").load(
    "ajax/listeProduit-EntrepotAjax.php",
    { operation: 1, nbEntreeASEt: nbEntreeASEt, query: "", cb: "" },
    function () {
      //get content from PHP page

      $(".loading-div").hide(); //once done, hide loading element
      getDepot();
    }
  );

  //executes code below when user click on pagination links

  $("#resultsProductsASE").on("click", ".pagination a", function (e) {
    // $("#resultsProductsASE").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeASEt = $("#nbEntreeASEt").val();

    query = $("#searchInputASEt").val();

    if (query.length == 0) {
      $("#resultsProductsASE").load(
        "ajax/listeProduit-EntrepotAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeASEt: nbEntreeASEt,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
          getDepot();
        }
      );
    } else {
      $("#resultsProductsASE").load(
        "ajax/listeProduit-EntrepotAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeASEt: nbEntreeASEt,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
          getDepot();
        }
      );
    }

    // $("#resultsProductsASE").load("ajax/listeProduit-EntrepotAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(1)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $("#searchInputASEt").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputASEt").val();

    nbEntreeASEt = $("#nbEntreeASEt").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsASE").load(
          "ajax/listeProduit-EntrepotAjax.php",
          { operation: 3, nbEntreeASEt: nbEntreeASEt, query: query, cb: t },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
            getDepot();
          }
        );
      } else {
        $("#resultsProductsASE").load(
          "ajax/listeProduit-EntrepotAjax.php",
          { operation: 3, nbEntreeASEt: nbEntreeASEt, query: "", cb: t },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
            getDepot();
          }
        );
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsASE").load(
            "ajax/listeProduit-EntrepotAjax.php",
            { operation: 3, nbEntreeASEt: nbEntreeASEt, query: query, cb: t },
            function () {
              //get content from PHP page

              $(".loading-div").hide(); //once done, hide loading element
              getDepot();
            }
          );
        } else {
          $("#resultsProductsASE").load(
            "ajax/listeProduit-EntrepotAjax.php",
            { operation: 3, nbEntreeASEt: nbEntreeASEt, query: "", cb: t },
            function () {
              //get content from PHP page

              $(".loading-div").hide(); //once done, hide loading element
              getDepot();
            }
          );
        }
      }, 100);
    }
  });

  $("#nbEntreeASEt").on("change", function (e) {
    e.preventDefault();

    nbEntreeASEt = $("#nbEntreeASEt").val();

    query = $("#searchInputASEt").val();

    if (query.length == 0) {
      $("#resultsProductsASE").load(
        "ajax/listeProduit-EntrepotAjax.php",
        { operation: 4, nbEntreeASEt: nbEntreeASEt, query: "", cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
          getDepot();
        }
      );
    } else {
      $("#resultsProductsASE").load(
        "ajax/listeProduit-EntrepotAjax.php",
        { operation: 4, nbEntreeASEt: nbEntreeASEt, query: query, cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
          getDepot();
        }
      );
    }

    // $("#resultsProductsASE" ).load( "ajax/listeProduit-EntrepotAjax.php",{"operation":4,"nbEntreeASEt":nbEntreeASEt}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triASE = 0;

  $(document).on("click", "#resultsProductsASE th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputASEt").val();

    nbEntreeASEt = $("#nbEntreeASEt").val();

    // $("#resultsProductsASE" ).load( "ajax/listeProduit-EntrepotAjax.php",{"operation":2,"nbEntreeASEt":nbEntreeASEt,"tri":1, "query":""}); //load initial records

    // alert(triASE)

    if (triASE == 1) {
      // alert(triASE)

      if (query.length == 0) {
        $("#resultsProductsASE").load(
          "ajax/listeProduit-EntrepotAjax.php",
          {
            operation: 2,
            nbEntreeASEt: nbEntreeASEt,
            tri: 1,
            query: "",
            cb: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
            getDepot();
          }
        );
      } else {
        $("#resultsProductsASE").load(
          "ajax/listeProduit-EntrepotAjax.php",
          {
            operation: 2,
            nbEntreeASEt: nbEntreeASEt,
            tri: 1,
            query: query,
            cb: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
            getDepot();
          }
        );
      }

      triASE = 0;

      // alert(triASE)
    } else {
      // alert(triASE)

      if (query.length == 0) {
        $("#resultsProductsASE").load(
          "ajax/listeProduit-EntrepotAjax.php",
          {
            operation: 2,
            nbEntreeASEt: nbEntreeASEt,
            tri: 0,
            query: "",
            cb: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
            getDepot();
          }
        );
      } else {
        $("#resultsProductsASE").load(
          "ajax/listeProduit-EntrepotAjax.php",
          {
            operation: 2,
            nbEntreeASEt: nbEntreeASEt,
            tri: 0,
            query: query,
            cb: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
            getDepot();
          }
        );
      }

      triASE = 1;

      // alert(triASE)
    }

    // alert(triASE)
  });
});

/*************** Fin modification tableaux ajout stock entrepot***************/

/*************** Début modification tableaux stock***************/

$(document).ready(function () {
  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeSEt = $("#nbEntreeSEt").val();

  $("#resultsProductsSE").load(
    "ajax/listerStock-EntrepotAjax.php",
    { operation: 1, nbEntreeSEt: nbEntreeSEt, query: "", cb: "" },
    function (data) {
      // alert(data)
    }
  ); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsSE").on("click", ".pagination a", function (e) {
    // $("#resultsProductsSE").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeSEt = $("#nbEntreeSEt").val();

    query = $("#searchInputSEt").val();

    if (query.length == 0) {
      $("#resultsProductsSE").load(
        "ajax/listerStock-EntrepotAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeSEt: nbEntreeSEt,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsSE").load(
        "ajax/listerStock-EntrepotAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeSEt: nbEntreeSEt,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(1)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $("#searchInputSEt").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputSEt").val();

    nbEntreeSEt = $("#nbEntreeSEt").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
          operation: 3,
          nbEntreeSEt: nbEntreeSEt,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
          operation: 3,
          nbEntreeSEt: nbEntreeSEt,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
            operation: 3,
            nbEntreeSEt: nbEntreeSEt,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
            operation: 3,
            nbEntreeSEt: nbEntreeSEt,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeSEt").on("change", function (e) {
    e.preventDefault();

    nbEntreeSEt = $("#nbEntreeSEt").val();

    query = $("#searchInputSEt").val();

    if (query.length == 0) {
      $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
        operation: 4,
        nbEntreeSEt: nbEntreeSEt,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
        operation: 4,
        nbEntreeSEt: nbEntreeSEt,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsProductsSE" ).load( "ajax/listerStock-EntrepotAjax.php",{"operation":4,"nbEntreeSEt":nbEntreeSEt}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triSE = 0;

  $(document).on("click", "#resultsProductsSE th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputSEt").val();

    nbEntreeSEt = $("#nbEntreeSEt").val();

    // $("#resultsProductsSE" ).load( "ajax/listerStock-EntrepotAjax.php",{"operation":2,"nbEntreeSEt":nbEntreeSEt,"tri":1, "query":""}); //load initial records

    // alert(triSE)

    if (triSE == 1) {
      // alert(triSE)

      if (query.length == 0) {
        $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
          operation: 2,
          nbEntreeSEt: nbEntreeSEt,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
          operation: 2,
          nbEntreeSEt: nbEntreeSEt,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triSE = 0;

      // alert(triSE)
    } else {
      // alert(triSE)

      if (query.length == 0) {
        $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
          operation: 2,
          nbEntreeSEt: nbEntreeSEt,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsSE").load("ajax/listerStock-EntrepotAjax.php", {
          operation: 2,
          nbEntreeSEt: nbEntreeSEt,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triSE = 1;

      // alert(triSE)
    }

    // alert(triSE)
  });
});

/*************** Fin modification tableaux stock entrepot***************/

/*************** Début modification tableaux ajout stock pharmacie***************/

$(document).ready(function () {
  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeASPh = $("#nbEntreeASPh").val();

  $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
    operation: 1,
    nbEntreeASPh: nbEntreeASPh,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsASPh").on("click", ".pagination a", function (e) {
    // $("#resultsASPh").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeASPh = $("#nbEntreeASPh").val();

    query = $("#searchInputASPh").val();

    if (query.length == 0) {
      $("#resultsASPh").load(
        "ajax/listeProduit-PharmacieAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeASPh: nbEntreeASPh,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsASPh").load(
        "ajax/listeProduit-PharmacieAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeASPh: nbEntreeASPh,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(1)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $("#searchInputASPh").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputASPh").val();

    nbEntreeASPh = $("#nbEntreeASPh").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
          operation: 3,
          nbEntreeASPh: nbEntreeASPh,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
          operation: 3,
          nbEntreeASPh: nbEntreeASPh,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
            operation: 3,
            nbEntreeASPh: nbEntreeASPh,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
            operation: 3,
            nbEntreeASPh: nbEntreeASPh,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeASPh").on("change", function (e) {
    e.preventDefault();

    nbEntreeASPh = $("#nbEntreeASPh").val();

    query = $("#searchInputASPh").val();

    if (query.length == 0) {
      $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
        operation: 4,
        nbEntreeASPh: nbEntreeASPh,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
        operation: 4,
        nbEntreeASPh: nbEntreeASPh,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsASPh" ).load( "ajax/listeProduit-PharmacieAjax.php",{"operation":4,"nbEntreeASPh":nbEntreeASPh}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triASP = 0;

  $(document).on("click", "#resultsASPh th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputASPh").val();

    nbEntreeASPh = $("#nbEntreeASPh").val();

    // $("#resultsASPh" ).load( "ajax/listeProduit-PharmacieAjax.php",{"operation":2,"nbEntreeASPh":nbEntreeASPh,"tri":1, "query":""}); //load initial records

    // alert(triASP)

    if (triASP == 1) {
      // alert(triASP)

      if (query.length == 0) {
        $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
          operation: 2,
          nbEntreeASPh: nbEntreeASPh,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
          operation: 2,
          nbEntreeASPh: nbEntreeASPh,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triASP = 0;

      // alert(triASP)
    } else {
      // alert(triASP)

      if (query.length == 0) {
        $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
          operation: 2,
          nbEntreeASPh: nbEntreeASPh,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsASPh").load("ajax/listeProduit-PharmacieAjax.php", {
          operation: 2,
          nbEntreeASPh: nbEntreeASPh,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triASP = 1;

      // alert(triASP)
    }

    // alert(triASP)
  });
});

/*************** Fin modification tableaux ajout stock***************/

/*************** Début modification tableaux stock***************/

$(document).ready(function () {
  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeSPh = $("#nbEntreeSPh").val();

  $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
    operation: 1,
    nbEntreeSPh: nbEntreeSPh,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsStockPh").on("click", ".pagination a", function (e) {
    // $("#resultsStockPh").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeSPh = $("#nbEntreeSPh").val();

    query = $("#searchInputSPh").val();

    if (query.length == 0) {
      $("#resultsStockPh").load(
        "ajax/listerStock-PharmacieAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeSPh: nbEntreeSPh,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsStockPh").load(
        "ajax/listerStock-PharmacieAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeSPh: nbEntreeSPh,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(1)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $("#searchInputSPh").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputSPh").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
          operation: 3,
          nbEntreeSPh: nbEntreeSPh,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
          operation: 3,
          nbEntreeSPh: nbEntreeSPh,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
            operation: 3,
            nbEntreeSPh: nbEntreeSPh,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
            operation: 3,
            nbEntreeSPh: nbEntreeSPh,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeSPh").on("change", function (e) {
    e.preventDefault();

    nbEntreeSPh = $("#nbEntreeSPh").val();

    query = $("#searchInputSPh").val();

    if (query.length == 0) {
      $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
        operation: 4,
        nbEntreeSPh: nbEntreeSPh,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
        operation: 4,
        nbEntreeSPh: nbEntreeSPh,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsStockPh" ).load( "ajax/listerStock-PharmacieAjax.php",{"operation":4,"nbEntreeSPh":nbEntreeSPh}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triSP = 0;

  $(document).on("click", "#resultsStockPh th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputSPh").val();

    nbEntreeSPh = $("#nbEntreeSPh").val();

    // $("#resultsStockPh" ).load( "ajax/listerStock-PharmacieAjax.php",{"operation":2,"nbEntreeSPh":nbEntreeSPh,"tri":1, "query":""}); //load initial records

    // alert(triSP)

    if (triSP == 1) {
      // alert(triSP)

      if (query.length == 0) {
        $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
          operation: 2,
          nbEntreeSPh: nbEntreeSPh,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
          operation: 2,
          nbEntreeSPh: nbEntreeSPh,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triSP = 0;

      // alert(triSP)
    } else {
      // alert(triSP)

      if (query.length == 0) {
        $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
          operation: 2,
          nbEntreeSPh: nbEntreeSPh,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsStockPh").load("ajax/listerStock-PharmacieAjax.php", {
          operation: 2,
          nbEntreeSPh: nbEntreeSPh,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triSP = 1;

      // alert(triSP)
    }

    // alert(triSP)
  });
});

/*************** Fin modification tableaux stock pharmacie***************/

/*************** Début modification tableaux ajout stock boutique***************/

$(document).ready(function () {
  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntree = $("#nbEntreeAS").val();

  $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
    operation: 1,
    nbEntree: nbEntree,
    query: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsAS").on("click", ".pagination a", function (e) {
    // $("#resultsProductsAS").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntree = $("#nbEntreeAS").val();

    query = $("#searchInputAS").val();

    if (query.length == 0) {
      $("#resultsProductsAS").load(
        "ajax/listeProduitAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsAS").load(
        "ajax/listeProduitAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: query },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsAS").load("ajax/listeProduitAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(1)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $("#searchInputAS").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAS").val();

    nbEntree = $("#nbEntreeAS").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
          operation: 3,
          nbEntreeS: nbEntreeS,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
          operation: 3,
          nbEntreeS: nbEntreeS,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
            operation: 3,
            nbEntreeS: nbEntreeS,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
            operation: 3,
            nbEntreeS: nbEntreeS,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAS").on("change", function (e) {
    e.preventDefault();

    nbEntree = $("#nbEntreeAS").val();

    query = $("#searchInputAS").val();

    if (query.length == 0) {
      $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: "",
      }); //load initial records
    } else {
      $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: query,
      }); //load initial records
    }

    // $("#resultsProductsAS" ).load( "ajax/listeProduitAjax.php",{"operation":4,"nbEntree":nbEntree}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triAS = 0;

  $(document).on("click", "#resultsProductsAS th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputAS").val();

    nbEntree = $("#nbEntreeAS").val();

    // $("#resultsProductsAS" ).load( "ajax/listeProduitAjax.php",{"operation":2,"nbEntree":nbEntree,"tri":1, "query":""}); //load initial records

    // alert(triAS)

    if (triAS == 1) {
      // alert(triAS)

      if (query.length == 0) {
        $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: "",
        }); //load initial records
      } else {
        $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: query,
        }); //load initial records
      }

      triAS = 0;

      // alert(triAS)
    } else {
      // alert(triAS)

      if (query.length == 0) {
        $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: "",
        }); //load initial records
      } else {
        $("#resultsProductsAS").load("ajax/listeProduitAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: query,
        }); //load initial records
      }

      triAS = 1;

      // alert(triAS)
    }

    // alert(triAS)
  });
});

/*************** Fin modification tableaux ajout stock***************/

/*************** Début modification tableaux stock***************/

$(document).ready(function () {
  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeS = $("#nbEntreeS").val();

  $("#resultsProductsS").load("ajax/listerStockAjax.php", {
    operation: 1,
    nbEntreeS: nbEntreeS,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsS").on("click", ".pagination a", function (e) {
    // $("#resultsProductsS").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeS = $("#nbEntreeS").val();

    query = $("#searchInputS").val();

    if (query.length == 0) {
      $("#resultsProductsS").load(
        "ajax/listerStockAjax.php",
        { page: page, operation: 1, nbEntreeS: nbEntreeS, query: "", cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsS").load(
        "ajax/listerStockAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeS: nbEntreeS,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsS").load("ajax/listerStockAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(1)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $("#searchInputS").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputS").val();

    nbEntreeS = $("#nbEntreeS").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsS").load("ajax/listerStockAjax.php", {
          operation: 3,
          nbEntreeS: nbEntreeS,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsProductsS").load("ajax/listerStockAjax.php", {
          operation: 3,
          nbEntreeS: nbEntreeS,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsS").load("ajax/listerStockAjax.php", {
            operation: 3,
            nbEntreeS: nbEntreeS,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsS").load("ajax/listerStockAjax.php", {
            operation: 3,
            nbEntreeS: nbEntreeS,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeS").on("change", function (e) {
    e.preventDefault();

    nbEntreeS = $("#nbEntreeS").val();

    query = $("#searchInputS").val();

    if (query.length == 0) {
      $("#resultsProductsS").load("ajax/listerStockAjax.php", {
        operation: 4,
        nbEntreeS: nbEntreeS,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsProductsS").load("ajax/listerStockAjax.php", {
        operation: 4,
        nbEntreeS: nbEntreeS,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsProductsS" ).load( "ajax/listerStockAjax.php",{"operation":4,"nbEntreeS":nbEntreeS}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triS = 0;

  $(document).on("click", "#resultsProductsS th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputS").val();

    nbEntreeS = $("#nbEntreeS").val();

    // $("#resultsProductsS" ).load( "ajax/listerStockAjax.php",{"operation":2,"nbEntreeS":nbEntreeS,"tri":1, "query":""}); //load initial records

    // alert(triS)

    if (triS == 1) {
      // alert(triS)

      if (query.length == 0) {
        $("#resultsProductsS").load("ajax/listerStockAjax.php", {
          operation: 2,
          nbEntreeS: nbEntreeS,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsS").load("ajax/listerStockAjax.php", {
          operation: 2,
          nbEntreeS: nbEntreeS,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triS = 0;

      // alert(triS)
    } else {
      // alert(triS)

      if (query.length == 0) {
        $("#resultsProductsS").load("ajax/listerStockAjax.php", {
          operation: 2,
          nbEntreeS: nbEntreeS,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsS").load("ajax/listerStockAjax.php", {
          operation: 2,
          nbEntreeS: nbEntreeS,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triS = 1;

      // alert(triS)
    }

    // alert(triS)
  });
});

/*************** Fin modification tableaux stock boutique***************/

/*************** Début modification pour les tableaux references ***************/

/*************** Début modification boutique***************/

$(document).ready(function () {
  // alert(1)

  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntree = $("#nbEntree").val();

  $("#resultsProducts").load("ajax/listerProduitAjax.php", {
    operation: 1,
    nbEntree: nbEntree,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProducts").on("click", ".pagination a", function (e) {
    // $("#resultsProducts").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntree = $("#nbEntree").val();

    query = $("#searchInput").val();

    if (query.length == 0) {
      $("#resultsProducts").load(
        "ajax/listerProduitAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: "", cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProducts").load(
        "ajax/listerProduitAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: query, cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProducts").load("ajax/listerProduitAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInput").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInput").val();

    nbEntree = $("#nbEntree").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProducts").load("ajax/listerProduitAjax.php", {
          operation: 3,
          nbEntree: nbEntree,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsProducts").load("ajax/listerProduitAjax.php", {
          operation: 3,
          nbEntree: nbEntree,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProducts").load("ajax/listerProduitAjax.php", {
            operation: 3,
            nbEntree: nbEntree,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProducts").load("ajax/listerProduitAjax.php", {
            operation: 3,
            nbEntree: nbEntree,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntree").on("change", function (e) {
    e.preventDefault();

    nbEntree = $("#nbEntree").val();

    query = $("#searchInput").val();

    if (query.length == 0) {
      $("#resultsProducts").load("ajax/listerProduitAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsProducts").load("ajax/listerProduitAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsProducts" ).load( "ajax/listerProduitAjax.php",{"operation":4,"nbEntree":nbEntree}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triB = 0;

  $(document).on("click", "#tableDesignation th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInput").val();

    nbEntree = $("#nbEntree").val();

    // $("#resultsProducts" ).load( "ajax/listerProduitAjax.php",{"operation":2,"nbEntree":nbEntree,"tri":1, "query":""}); //load initial records

    if (triB == 1) {
      // alert(triB)

      if (query.length == 0) {
        $("#resultsProducts").load("ajax/listerProduitAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProducts").load("ajax/listerProduitAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triB = 0;

      // alert(triB)
    } else {
      // alert(triB)

      if (query.length == 0) {
        $("#resultsProducts").load("ajax/listerProduitAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProducts").load("ajax/listerProduitAjax.php", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triB = 1;

      // alert(triB)
    }
  });
});

/*************** Début modification boutique***************/

/*************** Début modification pharmacie***************/

$(document).ready(function () {
  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreePh = $("#nbEntreePh").val();

  $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
    operation: 1,
    nbEntreePh: nbEntreePh,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsP").on("click", ".pagination a", function (e) {
    // $("#resultsProductsP").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreePh = $("#nbEntreePh").val();

    query = $("#searchInputPh").val();

    if (query.length == 0) {
      $("#resultsProductsP").load(
        "ajax/listerProduit-PharmacieAjax.php",
        { page: page, operation: 1, nbEntreePh: nbEntreePh, query: "", cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsP").load(
        "ajax/listerProduit-PharmacieAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreePh: nbEntreePh,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(4)

  $("#searchInputPh").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputPh").val();

    nbEntreePh = $("#nbEntreePh").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      t = 1; // code barre

      // inputVal = $('#searchInputPh').val();

      // if ($.isNumeric(inputVal)) {

      if (query.length > 0) {
        $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
          operation: 3,
          nbEntreePh: nbEntreePh,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
          operation: 3,
          nbEntreePh: nbEntreePh,
          query: "",
          cb: t,
        }); //load initial records
      }

      // }else{

      // }
    } else {
      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          // alert(2222)

          $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
            operation: 3,
            nbEntreePh: nbEntreePh,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
            operation: 3,
            nbEntreePh: nbEntreePh,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreePh").on("change", function (e) {
    e.preventDefault();

    nbEntreePh = $("#nbEntreePh").val();

    query = $("#searchInputPh").val();

    if (query.length == 0) {
      $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
        operation: 4,
        nbEntreePh: nbEntreePh,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
        operation: 4,
        nbEntreePh: nbEntreePh,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":4,"nbEntreePh":nbEntreePh}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triPH = 0;

  $(document).on("click", "#resultsProductsP th", function (e) {
    e.preventDefault();

    // alert(12)

    query = $("#searchInputPh").val();

    nbEntreePh = $("#nbEntreePh").val();

    // $("#resultsProductsP" ).load( "ajax/listerProduit-PharmacieAjax.php",{"operation":2,"nbEntreePh":nbEntreePh,"tri":1, "query":""}); //load initial records

    if (triPH == 1) {
      // alert(triPH)

      if (query.length == 0) {
        $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
          operation: 2,
          nbEntreePh: nbEntreePh,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
          operation: 2,
          nbEntreePh: nbEntreePh,
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
        $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
          operation: 2,
          nbEntreePh: nbEntreePh,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsP").load("ajax/listerProduit-PharmacieAjax.php", {
          operation: 2,
          nbEntreePh: nbEntreePh,
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

/*************** Début modification pharmacie***************/

/*************** Début modification entrepot***************/

$(document).ready(function () {
  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeEt = $("#nbEntreeEt").val();

  $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
    operation: 1,
    nbEntreeEt: nbEntreeEt,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsE").on("click", ".pagination a", function (e) {
    // $("#resultsProductsE").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeEt = $("#nbEntreeEt").val();

    query = $("#searchInputEt").val();

    if (query.length == 0) {
      $("#resultsProductsE").load(
        "ajax/listerProduit-EntrepotAjax.php",
        { page: page, operation: 1, nbEntreeEt: nbEntreeEt, query: "", cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsE").load(
        "ajax/listerProduit-EntrepotAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeEt: nbEntreeEt,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  // alert(1)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  $("#searchInputEt").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputEt").val();

    nbEntreeEt = $("#nbEntreeEt").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      inputVal = $("#searchInputEt").val();

      if (query.length > 0) {
        $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
          operation: 3,
          nbEntreeEt: nbEntreeEt,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
          operation: 3,
          nbEntreeEt: nbEntreeEt,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
            operation: 3,
            nbEntreeEt: nbEntreeEt,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
            operation: 3,
            nbEntreeEt: nbEntreeEt,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeEt").on("change", function (e) {
    e.preventDefault();

    nbEntreeEt = $("#nbEntreeEt").val();

    query = $("#searchInputEt").val();

    if (query.length == 0) {
      $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
        operation: 4,
        nbEntreeEt: nbEntreeEt,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
        operation: 4,
        nbEntreeEt: nbEntreeEt,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#resultsProductsE" ).load( "ajax/listerProduit-EntrepotAjax.php",{"operation":4,"nbEntreeEt":nbEntreeEt}); //load initial records
  });
});

/*************    TRI    ******************/

$(document).ready(function () {
  // alert(1500)

  // var page = 0;

  //executes code below when user click on pagination links

  //1=croissant et 0=decroissant

  triET = 0;

  $(document).on("click", "#resultsProductsE th", function (e) {
    e.preventDefault();

    // alert(12)

    query = $("#searchInputEt").val();

    nbEntreeEt = $("#nbEntreeEt").val();

    // $("#resultsProductsE" ).load( "ajax/listerProduit-EntrepotAjax.php",{"operation":2,"nbEntreeEt":nbEntreeEt,"tri":1, "query":""}); //load initial records

    if (triET == 1) {
      // alert(triET)

      if (query.length == 0) {
        $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
          operation: 2,
          nbEntreeEt: nbEntreeEt,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
          operation: 2,
          nbEntreeEt: nbEntreeEt,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triET = 0;

      // alert(triET)
    } else {
      // alert(triET)

      if (query.length == 0) {
        $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
          operation: 2,
          nbEntreeEt: nbEntreeEt,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#resultsProductsE").load("ajax/listerProduit-EntrepotAjax.php", {
          operation: 2,
          nbEntreeEt: nbEntreeEt,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triET = 1;

      // alert(triET)
    }
  });
});

/*************** Fin modification entrepot***************/

/*************** Début modification pour les tableaux references ***************/

$(function () {
  typeClient = "";

  idMutuellePagnet = 0;

  $(".btn_Termine_Panier").on("click", function () {
    $(".cache_btn_Terminer").hide();

    $(".btn_Retourner_Produit").prop("disabled", true);
  });
});

$(function () {
  $(".btn_Termine_Panier").on("click", function () {
    $(".cache_btn_Terminer").hide();

    $(".btn_Retourner_Produit").prop("disabled", true);
  });
});

$(function () {
  $(".btn_Retour_Panier").on("click", function () {
    $(".modal-footer").hide();
  });
});

$(function () {
  // $("#addPanier").on("click", function() {

  //     // $("#addPanier").prop('disabled', true);

  //     $("#collapse1").collapse('hide');

  //     var lcd=$("#lcd_Machine").val();

  //     if(lcd==1){

  //         var quantite=" ";

  //         var prix=0;

  //         $.ajax({

  //             url : "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

  //             type: "GET", // data type (can be get, post, put, delete)

  //             data: {

  //                 "quantite": quantite,

  //                 "prix": prix,

  //             }, // data in json format

  //             async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

  //             success: function(response) {

  //                 console.log(response);

  //             },

  //         });

  //     }

  // });

  $(".addBonPanier").on("click", function () {
    var lcd = $("#lcd_Machine").val();

    if (lcd == 1) {
      var quantite = " ";

      var prix = 0;

      $.ajax({
        url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

        type: "GET", // data type (can be get, post, put, delete)

        data: {
          quantite: quantite,

          prix: prix,
        }, // data in json format

        async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

        success: function (response) {
          console.log(response);
        },
      });
    }
  });
});

$(function () {
  var idPagnet = $("#idPagnet").val();

  // alert(idPagnet)

  $("#btnImprimerFacture" + idPagnet).click(function () {
    var lcd = $("#lcd_Machine").val();

    if (lcd == 1) {
      var total = $("#somme_Apayer" + idPagnet).text();

      var quantite = " ";

      var prix = total;

      $.ajax({
        url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

        type: "GET", // data type (can be get, post, put, delete)

        data: {
          quantite: quantite,

          prix: prix,
        }, // data in json format

        async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

        success: function (response) {
          console.log(response);
        },
      });
    }
  });
});

$(function () {
  $("#btn_AjoutStock").on("click", function () {
    $("#AjoutStockModal").on("shown.bs.modal", function () {
      $("#designationPh").focus();
    });
  });
});

$(function () {
  $("#btnTableInitiale").on("click", function () {
    $("#divTableInitiale").toggle();
  });
});

$(function () {
  $("#btnLivreur").on("click", function () {
    $("#divLivreur").toggle();

    $(".echeanceDate").show();
  });
});

function modif_UniteStock(produit) {
  tab = produit.split("§");

  var unitevente = tab[0];

  var ligne = tab[1];

  var pagnet = tab[2];

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 150,

      idPagnet: pagnet,

      numLigne: ligne,

      unitevente: unitevente,
    },

    success: function (data) {
      tab1 = data.split("§");

      //if (tab1[2] == 1) {

      $("#prixUniteStock" + ligne).val(tab1[0]);

      $("#somme_Total" + pagnet).text(tab1[1]);

      $("#somme_Apayer" + pagnet).text(
        tab1[1] - $("#val_remise" + pagnet).val()
      );

      valTotal = tab1[1] - $("#val_remise" + pagnet).val();

      valApayer = tab1[4] - $("#val_remise" + pagnet).val();

      $("#somme_TotalCN" + pagnet).text(valTotal.toFixed(2));

      $("#somme_ApayerCN" + pagnet).text(valApayer.toFixed(2));

      var lcd = $("#lcd_Machine").val();

      if (lcd == 1) {
        var nd_Qte = tab1[3];

        var nd_Prix = tab1[0];

        $.ajax({
          url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

          type: "GET", // data type (can be get, post, put, delete)

          data: {
            quantite: nd_Qte,

            prix: nd_Prix,
          }, // data in json format

          async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

          success: function (response) {
            console.log(response);
          },
        });
      }

      /*    

            } else {

                document.getElementById('default' + ligne).selected = 'selected';

            }

            */

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function modif_UniteStockET(produit) {
  tab = produit.split("§");

  var unitevente = tab[0];

  var ligne = tab[1];

  var pagnet = tab[2];

  var quantite = tab[3];

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 17,

      idPagnet: pagnet,

      numLigne: ligne,

      unitevente: unitevente,

      quantite: quantite,
    },

    success: function (data) {
      tab1 = data.split("__");
      // alert(tab1)
      tab2 = tab1[1].split("§");

      if (tab1[0] == "1") {
        // alert($("#uniteVente"+ligne).val())
        if (tab2[0] == "Demi Gros") {
          quantite = 2;
        } else {
          quantite = 1;
        }

        $("#uniteVente" + ligne).attr("style", "background-color:red");
        // alert("Quantité non disponible pour "+unitevente+". Vérifiez l'unité et la quantité.")
        $("#msg_info_js").modal("show");
        setTimeout(() => {
          $("#uniteVente" + ligne).attr("style", "background-color:white");
        }, 1000);
        $("#uniteVente" + ligne + " option").removeAttr("selected");
        $(
          "#uniteVente" +
            ligne +
            " option[value='" +
            tab2[0] +
            "§" +
            ligne +
            "§" +
            pagnet +
            "§" +
            quantite +
            "']"
        ).remove();
        $("#uniteVente" + ligne).prepend(
          "<option value='" +
            tab2[0] +
            "§" +
            ligne +
            "§" +
            pagnet +
            "§" +
            quantite +
            "' selected='selected'>" +
            tab2[0] +
            "</option>"
        );

        $(
          "#uniteVente" +
            ligne +
            " option[value='" +
            tab2[0] +
            "§" +
            ligne +
            "§" +
            pagnet +
            "§" +
            quantite +
            "']"
        ).attr("selected", "selected");
      } else {
        $("#prixunitevente" + ligne).val(tab2[0]);

        $("#somme_Total" + pagnet).text(tab2[1]);

        $("#somme_Apayer" + pagnet).text(
          tab2[1] - $("#val_remise" + pagnet).val()
        );
      }
      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function modif_Depot(produit) {
  tab = produit.split("§");

  var depot = tab[0];

  var ligne = tab[1];

  var pagnet = tab[2];

  //alert(depot);

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 18,

      idPagnet: pagnet,

      numLigne: ligne,

      idEntrepot: depot,
    },

    success: function (data) {
      result = data.split("<>");

      reste = result[0] - result[1];

      if (reste >= 0) {
        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 19,

            idPagnet: pagnet,

            numLigne: ligne,

            idEntrepot: depot,
          },

          success: function (data) {},

          dataType: "text",
        });
      } else {
        $("#qte_stockET").text(result[0]);
        typevente = result[1];
        oldDepot = result[2];

        oldDepotTxt = $(
          "#depot" +
            ligne +
            " option[value='" +
            oldDepot +
            "§" +
            ligne +
            "§" +
            pagnet +
            "']"
        ).text();
        $(
          "#depot" +
            ligne +
            " option[value='" +
            oldDepot +
            "§" +
            ligne +
            "§" +
            pagnet +
            "']"
        ).remove();

        $("#depot" + ligne).prepend(
          "<option value='" +
            oldDepot +
            "§" +
            ligne +
            "§" +
            pagnet +
            "' selected='selected'>" +
            oldDepotTxt +
            "</option>"
        );

        $("#depot" + ligne).attr("style", "background-color:red");
        // alert("Quantité non disponible pour "+unitevente+". Vérifiez l'unité et la quantité.")
        $("#msg_info_js").modal("show");
        setTimeout(() => {
          $("#depot" + ligne).attr("style", "background-color:white");
        }, 1000);
      }

      //$("#somme_Total"+pagnet).text(tab1[1]);

      // $("#somme_Apayer"+pagnet).text(tab1[1] - $("#val_remise"+pagnet).val());
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  /** Debut Autocomplete Stock */

  $("#designationStock").keyup(function () {
    var query = $("#designationStock").val();

    console.log($("#designationStock").val());

    if (query.length > 0) {
      $.ajax({
        url: "ajax/ajouterStockAjax.php",

        method: "POST",

        data: $("#ajouterStockForm").serialize(),

        success: function (data) {
          $("#reponseS").html(data);
        },

        dataType: "text",
      });
    }
  });

  $("#designationStock").keyup(function () {
    var idDesignation = $("#idDesignation").val();

    if (idDesignation != "") {
      var designationStock = $("#designationStock").val();
    }
  });

  $(document).on("click", 'li[class="lic"]', function () {
    var designation = $(this).text();

    //tab=designation.split('-');

    //$("#designationStock").val(tab[0]);

    $("#designationStock").val(designation);

    //$("#idD").val(tab[1]);

    $("#reponseS").html("");

    $.ajax({
      url: "ajax/ajouterStockPrixAjax.php",

      method: "POST",

      data: $("#ajouterStockForm").serialize(),

      success: function (data2) {
        tab2 = data2.split("-");

        prix = tab2[0];

        prixuniteStock = tab2[1];

        uniteStock = tab2[2];

        $("#prixunitaire").val(prix);

        $("#prixuniteStock").val(prixuniteStock);

        $("#uniteStockId").html(uniteStock);

        console.log(data2);
      },

      dataType: "text",
    });
  });

  /** Fin Autocomplete Stock */
});

/** Debut Autocomplete Categorie Pharmacie*/

$(function () {
  $("#categoriePh").keyup(function (e) {
    e.preventDefault();

    var query = $("#categoriePh").val();

    if (query.length > 0 || query.length != "") {
      /*********** Modification **************/

      $("#categoriePh").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 18,

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

            error: function (err) {
              console.log(err);
            },
          });
        },
      });

      $("#categoriePh").focus();

      // $.ajax({

      //     url: 'ajax/vendreLigneAjax.php',

      //     method: 'POST',

      //     data: {

      //         operation: 18,

      //         query: query,

      //     },

      //     success: function(data) {

      //         $("#reponseCategorie").html(data);

      //         //console.log('insertion impo'+data);

      //     },

      //     dataType: 'text'

      // });
    }
  });

  // $(document).on('click', 'li[class="liC_Ph"]', function() {

  //     var categorie = $(this).text();

  //     $("#categoriePh").val(categorie);

  //     $("#reponseCategorie").html(' ');

  //     //  window.location.href = "insertionLigneLight.php?produit="+tab[0];

  // });
});

/** Fin Autocomplete Categorie Pharmacie*/

/** Debut Autocomplete Forme Pharmacie*/

$(function () {
  $("#formePh").keyup(function (e) {
    e.preventDefault();

    var query = $("#formePh").val();

    if (query.length > 0 || query.length != "") {
      /*********** Modification **************/

      $("#formePh").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 19,

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

            error: function (err) {
              console.log(err);
            },
          });
        },
      });

      $("#formePh").focus();
    }
  });

  // $(document).on('click', 'li[class="liF_Ph"]', function() {

  //     var forme = $(this).text();

  //     $("#formePh").val(forme);

  //     $("#reponseForme").html(' ');

  // });
});

/** Fin Autocomplete Forme Pharmacie*/

/** Debut Autocomplete Designation Pharmacie*/

$(function () {
  $("#designationPh").keyup(function () {
    var query = $("#designationPh").val();

    if (query.length > 0 || query.length != "") {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 11,

          query: query,
        },

        success: function (data) {
          $("#reponseDesignation").html(data);

          //console.log('insertion impo'+data);
        },

        dataType: "text",
      });
    } else {
      $("#reponseDesignation").html();
    }
  });

  $(document).on("click", 'li[class="liD"]', function () {
    var designation = $(this).text();

    $("#designationPh").val(designation);

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 12,

        designation: designation,
      },

      success: function (data) {
        result = data.split("<>");

        $("#reponseDesignation").html(" ");

        if (result[0] == 1) {
          $("#idFusion").val(result[1]);

          $("#designation").val(result[2]);

          $("#categorie2").children("option:selected").text(result[3]);

          $("#forme").val(result[4]);

          $("#tableau").children("option:selected").text(result[5]);

          $("#prixSession").val(result[6]);

          $("#prixPublic").val(result[7]);

          $("#codeBarre").val(result[8]);
        }

        //console.log('insertion impo'+data);
      },

      dataType: "text",
    });

    //  window.location.href = "insertionLigneLight.php?produit="+tab[0];
  });
});

/** Fin Autocomplete Designation Pharmacie*/

//*************PAIEMENT Orange Money ********************/

function effectue_paiement(idPS) {
  $.ajax({
    url: "ajax/ajouterPaiementAjax.php",

    method: "POST",

    data: {
      operation: 1,

      idPS: idPS,
    },

    success: function (data) {
      tab = data.split("<<>>");

      // alert(tab[6])

      $("#idPS_Rtr").val(tab[0]);

      $("#idBoutique_Rtr").val(tab[1]);

      $("#montantFixePayement_Rtr").val(tab[2]);

      $("#datePS_Rtr").val(tab[3]);

      $("#refTransf_Rtr").val(tab[4]);

      $("#numTel_Rtr").val(tab[5]);

      $("#imgQRCode").attr("src", "data:image/jpeg;base64," + tab[6]);

      $("#paiementModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_aj_Paiement").click(function () {
    var idPS = $("#idPS_Rtr").val();

    var datePS = $("#datePS_Rtr").val();

    var montantFixePayement = $("#montantFixePayement_Rtr").val();

    var refTransf = $("#refTransf_Rtr").val();

    var numTel = $("#numTel_Rtr").val();
    if (refTransf.length > 0 && numTel.length > 0) {
      $.ajax({
        url: "ajax/ajouterPaiementAjax.php",

        method: "POST",

        data: {
          operation: 2,

          idPS: idPS,

          refTransf: refTransf,

          numTel: numTel,
        },

        success: function (data) {
          $("#paiementModal").modal("hide");

          alert(data + " est remis à jour");
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    } else {
      alert(
        "Les champs Référence du Transfert et numéro téléphone ne doivent pas etre vide"
      );
    }
  });
});

/**Fin envoyer paiement **/
//*************FIN  PAIEMENT Orange Money ********************/

//*************PAIEMENT Wave ********************/

// function effectue_paiementWave(idPS) {

//     $.ajax({

//         url: "wave_payment.php",

//         method: "POST",

//         data: {

//             operation: 1,

//             idPS: idPS,

//         },

//         success: function(data) {

//             // tab = data.split('+');
//             // alert(data)

//             // $('#idPS_RtrW').val(tab[0]);

//             // $('#idBoutique_RtrW').val(tab[1]);

//             // $('#montantFixePayement_RtrW').val(tab[2]);

//             // $('#datePS_RtrW').val(tab[3]);

//             // $('#refTransf_RtrW').val(tab[4]);

//             // $('#numTel_RtrW').val(tab[5]);
//             document.getElementById("bodyPayWave").innerHTML='<object style="height: 500px;width: 700px;" type="text/html" data="'+data+'" ></object>';

//             $('#paiementWave').modal('show');

//         },

//         error: function() {

//             alert("La requête ");

//         },

//         dataType: "text"

//     });

// }

$(function () {
  $("#btn_aj_PaiementWave").click(function () {
    var idPS = $("#idPS_RtrW").val();

    var datePS = $("#datePS_RtrW").val();

    var montantFixePayement = $("#montantFixePayement_RtrW").val();

    var refTransf = $("#refTransf_RtrW").val();

    var numTel = $("#numTel_RtrW").val();

    if (refTransf.length > 0 && numTel.length > 0) {
      $.ajax({
        url: "ajax/ajouterPaiementAjax.php",

        method: "POST",

        data: {
          operation: 2,

          idPS: idPS,

          refTransf: refTransf,

          numTel: numTel,
        },

        success: function (data) {
          $("#paiementWave").modal("hide");

          alert(data + " est remis à jour");
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    } else {
      alert(
        "Les champs ID de transaction et numéro téléphone ne doivent pas etre vide"
      );
    }
  });
});

/**Fin envoyer paiement **/
//*************FIN  PAIEMENT WAVE ********************/

/** Debut Autocomplete Vente Boutique*/

$(function () {
  $("#codeBarreLigne").keyup(function () {
    var query = $("#codeBarreLigne").val();

    if (query.length > 0) {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 1,

          query: query,
        },

        success: function (data) {
          $("#reponseV").html(data);

          console.log("insertion impo" + data);
        },

        dataType: "text",
      });
    }
  });

  $(document).on("click", 'li[class="licV"]', function () {
    var designation = $(this).text();

    tab = designation.split("=>");

    $("#codeBarreLigne").val(tab[0]);

    $("#reponseV").html("");
  });
});

function retour_ProduitPh(ligne, panier) {
  $.ajax({
    url: "ajax/vendreLigneAjax.php",

    method: "POST",

    data: {
      operation: 7,

      idPagnet: panier,

      numligne: ligne,
    },

    success: function (data) {
      // alert(data)

      result = data.split("<>");

      if (result[0] == 1) {
        $("#tablePanier" + panier + " tr").each(function (row, tr) {
          reference = $(tr).find("td:eq(0)").text();

          // alert(reference)

          if (reference != "" && reference == result[2]) {
            $(tr).remove();

            $(".lic").text("");

            $("#codeBarreLignePh").val("");

            $("#codeBarreLignePh").focus();

            $("#somme_Total" + panier).text(result[6]);

            $("#somme_Apayer" + panier).text(
              result[6] -
                $("#val_remise" + panier).val() -
                (result[6] * result[7]) / 100
            );
          }
        });
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/** Fin Autocomplete Vente Boutique*/

/**Debut ajouter Client**/

$(function () {
  // alert(111)

  $("#btn_ajt_Client").click(function () {
    var prenom = $("#ajtPrenomCL").val();

    var nom = $("#ajtNomCL").val();

    var marqueur = $("#ajtMarqueurCL").val();

    var adresse = $("#ajtAdresseCL").val();

    var telephone = $("#ajtTelephoneCL").val();

    var plafondSelect = $("#plafondSelect").val();

    var plafond = plafondSelect == 1 ? $("#ajtPlafondCL").val() : null;

    var personnel = $("#personnel").val();

    var avoir = $("#check1").val();

    var montantAvoir = $("#montantAvoir").val();

    var matp = $("#ajtMatPCL").val();

    var numCarnet = $("#ajtNumCarnetCL").val();

    if (avoir == 1 || avoir == 0) {
      // alert(matp +"/"+numCarnet);

      $.ajax({
        url: "ajax/operationAjax_bon.php",

        method: "POST",

        data: {
          operation: 7,

          query: numCarnet,
        },

        dataType: "text",

        success: function (data) {
          if (data == 1) {
            $("#numCarnetExt").show();
          } else {
            $.ajax({
              url: "ajax/operationAjax_bon.php",

              method: "POST",

              data: {
                operation: 1,

                prenom: prenom,

                nom: nom,

                adresse: adresse,

                telephone: telephone,

                plafond: plafond,

                personnel: personnel,

                avoir: avoir,

                montantAvoir: montantAvoir,

                matp: matp,

                numCarnet: numCarnet,

                marqueur: marqueur,
              },

              success: function (data) {
                // alert(data)

                tab = data.split("<>");

                if (tab[0] == 1) {
                  /*

                                    var ligne = "<tr><td>0</td><td>" + tab[1] + "</td><td>" + tab[2] + "</td><td>" + tab[3] + "</td><td>" + tab[4] + "</td><td>0</td><td>DESACTIVER</td></tr>";

                                    $("table.tabClient").prepend(ligne);

                                    $('#ajtPrenomCL').val('');

                                    $('#ajtNomCL').val('');

                                    $('#ajtAdresseCL').val('');

                                    $('#ajtTelephoneCL').val('');

                                    $('#personnel').val('');

                                    */

                  $("#ajoutClient").modal("hide");

                  window.location.reload();
                }
              },

              error: function () {
                alert("La requête ");
              },

              dataType: "text",
            });
          }
        },

        error: function () {
          alert("La requête ss");
        },
      });
    } else {
      $.ajax({
        url: "ajax/operationAjax_bon.php",

        method: "POST",

        data: {
          operation: 1,

          prenom: prenom,

          nom: nom,

          adresse: adresse,

          telephone: telephone,

          plafond: plafond,

          personnel: personnel,

          avoir: avoir,

          montantAvoir: montantAvoir,

          matp: matp,

          numCarnet: numCarnet,

          marqueur: marqueur,
        },

        success: function (data) {
          // alert(data)

          tab = data.split("<>");

          if (tab[0] == 1) {
            /*

                        var ligne = "<tr><td>0</td><td>" + tab[1] + "</td><td>" + tab[2] + "</td><td>" + tab[3] + "</td><td>" + tab[4] + "</td><td>0</td><td>DESACTIVER</td></tr>";

                        $("table.tabClient").prepend(ligne);

                        $('#ajtPrenomCL').val('');

                        $('#ajtNomCL').val('');

                        $('#ajtAdresseCL').val('');

                        $('#ajtTelephoneCL').val('');

                        $('#personnel').val('');

                        */

            $("#ajoutClient").modal("hide");

            window.location.reload();
          }
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    }
  });
});

/**Fin ajouter Client**/

/**Debut Activer Client **/

function activer_Client(idClient) {
  $("#btn_Client-" + idClient).prop("disabled", true);

  $.ajax({
    url: "ajax/operationAjax_bon.php",

    method: "POST",

    data: {
      operation: 2,

      idClient: idClient,

      activer: 1,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#btn_Client-" + idClient).prop("disabled", true);
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin Activer Client**/

/**Debut Desactiver Client **/

function desactiver_Client(idClient) {
  $("#btn_Client-" + idClient).prop("disabled", true);

  $.ajax({
    url: "ajax/operationAjax_bon.php",

    method: "POST",

    data: {
      operation: 2,

      idClient: idClient,

      activer: 0,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#btn_Client-" + idClient).prop("disabled", true);
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin Desactiver Client**/

/**Debut archiver Client **/

function archiver_Client(idClient) {
  $("#btn_archive-" + idClient).prop("disabled", true);

  $.ajax({
    url: "ajax/operationAjax_bon.php",

    method: "POST",

    data: {
      operation: 6,

      idClient: idClient,

      archiver: 1,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#btn_archive-" + idClient).prop("disabled", true);
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin archiver Client**/

/**Debut relancer Client **/

function relancer_Client(idClient) {
  $("#btn_relancer-" + idClient).prop("disabled", true);

  $.ajax({
    url: "ajax/operationAjax_bon.php",

    method: "POST",

    data: {
      operation: 6,

      idClient: idClient,

      archiver: 0,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#btn_relancer-" + idClient).prop("disabled", true);
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin relancer Client**/

// alert(111)

/**Debut Modifier Client**/

function mdf_Client(idClient, ordre) {
  $.ajax({
    url: "ajax/operationAjax_bon.php",

    method: "POST",

    data: {
      operation: 3,

      idClient: idClient,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idCL_Mdf").val(tab[1]);

        $("#ordreCL_Mdf").val(ordre);

        $("#nomCL_Mdf").val(tab[2]);

        $("#prenomCL_Mdf").val(tab[3]);

        $("#adresseCL_Mdf").val(tab[4]);

        $("#telephoneCL_Mdf").val(tab[5]);
        // alert(tab[10])
        if (tab[10] != null && tab[10] != "") {
          $("#plafondCL_Mdf").val(tab[10]);
          $("#plafondSelect_Mdf option[value='1']").attr(
            "selected",
            "selected"
          );
          $(".plafondInput").show();
        } else {
          $("#plafondCL_Mdf").val(null);
          $("#plafondSelect_Mdf option[value='0']").attr(
            "selected",
            "selected"
          );
          $(".plafondInput").hide();
        }

        // alert(tab[6])

        if (tab[6] == 1) {
          $("#check1_Mdf").attr("checked", true);

          $("#check1_Mdf").val(1);

          $("#montantAvoir_Mdf").val(tab[7]);

          $("#ajtMatPCL_Mdf").val(tab[8]);

          $("#ajtNumCarnetCL_Mdf").val(tab[9]);

          $("#ajtNumCarnetCL_old").val(tab[9]);

          $("#personnelDiv_Mdf").hide();

          $("#divMontantAvoir_Mdf").show();

          $("#divMatP_Mdf").show();

          $("#divNumCarnet_Mdf").show();
        } else {
          $("#check1_Mdf").attr("checked", false);

          $("#divMontantAvoir_Mdf").hide();

          $("#divMatP_Mdf").hide();

          $("#divNumCarnet_Mdf").hide();

          $("#personnelDiv_Mdf").show();
        }

        $("#modifierClient").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Client").click(function () {
    var idClient = $("#idCL_Mdf").val();

    var ordre = $("#ordreCL_Mdf").val();

    var nom = $("#nomCL_Mdf").val();

    var prenom = $("#prenomCL_Mdf").val();

    var adresse = $("#adresseCL_Mdf").val();

    var telephone = $("#telephoneCL_Mdf").val();

    var plafondSelect = $("#plafondSelect_Mdf").val();

    var plafond = plafondSelect == 1 ? $("#plafondCL_Mdf").val() : null;

    var personnel = $("#personnel_Mdf").val();

    var avoir = $("#check1_Mdf").val();

    var montantAvoir = $("#montantAvoir_Mdf").val();

    var matp = $("#ajtMatPCL_Mdf").val();

    var numCarnet = $("#ajtNumCarnetCL_Mdf").val();

    var numCarnetOld = $("#ajtNumCarnetCL_old").val();

    if (avoir == 0 || avoir == 1) {
      $.ajax({
        url: "ajax/operationAjax_bon.php",

        method: "POST",

        data: {
          operation: 7,

          query: numCarnet,

          action: "modifier",

          numCarnetOld: numCarnetOld,
        },

        dataType: "text",

        success: function (data) {
          if (data == 1) {
            $("#numCarnetExt_Mdf").show();
          } else {
            $.ajax({
              url: "ajax/operationAjax_bon.php",

              method: "POST",

              data: {
                operation: 4,

                idClient: idClient,

                nom: nom,

                prenom: prenom,

                adresse: adresse,

                telephone: telephone,

                plafond: plafond,

                personnel: personnel,

                avoir: avoir,

                montantAvoir: montantAvoir,

                matp: matp,

                numCarnet: numCarnet,
              },

              success: function (data) {
                $("#idCL_Mdf").val("");

                $("#ordreCL_Mdf").val("");

                $("#nomCL_Mdf").val("");

                $("#prenomCL_Mdf").val("");

                $("#adresseCL_Mdf").val("");

                $("#telephoneCL_Mdf").val("");

                $("#plafondCL_Mdf").val("");

                $("#personnel_Mdf").val("");

                $("#check1_Mdf").val("");

                $("#montantAvoir_Mdf").val("");

                $("#ajtMatPCL_Mdf").val("");

                $("#ajtNumCarnetCL_Mdf").val("");

                $("#ajtNumCarnetCL_old").val("");

                $("#modifierClient").modal("hide");

                tab = data.split("<>");

                if (tab[0] == 1) {
                  $("#tableClient tr").each(function (row, tr) {
                    fournisseur = $(tr).find("td:eq(0)").text();

                    if (fournisseur != "" && fournisseur == ordre) {
                      $(tr).find("td:eq(1)").html(tab[2]);

                      $(tr).find("td:eq(2)").html(tab[3]);

                      $(tr).find("td:eq(3)").html(tab[4]);

                      $(tr).find("td:eq(4)").html(tab[5]);

                      $(tr).find("td:eq(5)").html(tab[6]);

                      $(tr).find("td:eq(6)").html("En cours ...");
                    }
                  });
                }

                window.location.reload();
              },

              error: function () {
                alert("La requête ");
              },

              dataType: "text",
            });
          }
        },

        error: function () {
          alert("La requête ss");
        },
      });
    } else {
      $.ajax({
        url: "ajax/operationAjax_bon.php",

        method: "POST",

        data: {
          operation: 4,

          idClient: idClient,

          nom: nom,

          prenom: prenom,

          adresse: adresse,

          telephone: telephone,

          plafond: plafond,

          personnel: personnel,

          avoir: avoir,

          montantAvoir: montantAvoir,

          matp: matp,

          numCarnet: numCarnet,
        },

        success: function (data) {
          $("#idCL_Mdf").val("");

          $("#ordreCL_Mdf").val("");

          $("#nomCL_Mdf").val("");

          $("#prenomCL_Mdf").val("");

          $("#adresseCL_Mdf").val("");

          $("#telephoneCL_Mdf").val("");

          $("#plafondCL_Mdf").val("");

          $("#personnel_Mdf").val("");

          $("#check1_Mdf").val("");

          $("#montantAvoir_Mdf").val("");

          $("#ajtMatPCL_Mdf").val("");

          $("#ajtNumCarnetCL_Mdf").val("");

          $("#ajtNumCarnetCL_old").val("");

          $("#modifierClient").modal("hide");

          tab = data.split("<>");

          if (tab[0] == 1) {
            $("#tableClient tr").each(function (row, tr) {
              fournisseur = $(tr).find("td:eq(0)").text();

              if (fournisseur != "" && fournisseur == ordre) {
                $(tr).find("td:eq(1)").html(tab[2]);

                $(tr).find("td:eq(2)").html(tab[3]);

                $(tr).find("td:eq(3)").html(tab[4]);

                $(tr).find("td:eq(4)").html(tab[5]);

                $(tr).find("td:eq(5)").html(tab[6]);

                $(tr).find("td:eq(6)").html("En cours ...");
              }
            });
          }

          window.location.reload();
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    }
  });
});

/**Fin Modifier Client**/

/**Debut Supprimer Client**/

function spm_Client(idClient, ordre) {
  $.ajax({
    url: "ajax/operationAjax_bon.php",

    method: "POST",

    data: {
      operation: 3,

      idClient: idClient,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idCL_Spm").val(tab[1]);

        $("#ordreCL_Spm").val(ordre);

        $("#nomCL_Spm").val(tab[2]);

        $("#prenomCL_Spm").val(tab[3]);

        $("#adresseCL_Spm").val(tab[4]);

        $("#telephoneCL_Spm").val(tab[5]);

        $("#supprimerClient").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Client").click(function () {
    var idClient = $("#idCL_Spm").val();

    var ordre = $("#ordreCL_Spm").val();

    $.ajax({
      url: "ajax/operationAjax_bon.php",

      method: "POST",

      data: {
        operation: 5,

        idClient: idClient,
      },

      success: function (data) {
        $("#idCL_Spm").val("");

        $("#ordreCL_Spm").val("");

        $("#nomCL_Spm").val("");

        $("#prenomCL_Spm").val("");

        $("#adresseCL_Spm").val("");

        $("#telephoneCL_Spm").val("");

        $("#supprimerClient").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableClient tr").each(function (row, tr) {
            fournisseur = $(tr).find("td:eq(0)").text();

            if (fournisseur != "" && fournisseur == ordre) {
              $(tr).find("td:eq(1)").html(tab[2]);

              $(tr).find("td:eq(2)").html(tab[3]);

              $(tr).find("td:eq(3)").html(tab[4]);

              $(tr).find("td:eq(4)").html(tab[5]);

              $(tr).find("td:eq(5)").html(tab[6]);

              $(tr).find("td:eq(6)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin Supprimer Client**/

/** Debut Autocomplete Bon Client Boutique*/

$(function () {
  $("#codeBarreLigneC").keyup(function () {
    var query = $("#codeBarreLigneC").val();

    if (query.length > 0) {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 2,

          query: query,
        },

        success: function (data) {
          $("#reponseV").html(data);

          console.log("insertion impo" + data);
        },

        dataType: "text",
      });
    }
  });

  $(document).on("click", 'li[class="lic"]', function () {
    var designation = $(this).text();

    tab = designation.split("=>");

    //$("#designationStock").val(tab[0]);

    $("#codeBarreLigneC").val(tab[0]);
  });
});

/** Fin Autocomplete Bon Client Boutique*/

/** Debut Autocomplete Vente Boutique*/

$(function () {
  // alert(1)

  idPanier = 0;

  $(document).on("keyup", ".codeBarreLigneVt", function (e) {
    e.preventDefault();

    var tabIdPanier = $(this).attr("id");

    tab = tabIdPanier.split("_");

    idPanier = tab[1];

    var query = $(this).val();

    if (query.length > 0) {
      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 1,

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

            error: function (err) {
              alert("La requête ss");
            },
          });
        },
      });

      $(this).focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      // alert(keycode)

      if (keycode == "13") {
        inputVal = $(this).val();

        if ($.isNumeric(inputVal)) {
          designation = $(this).val();
        } else {
          var designation = $(
            "#ajouterProdFormB" + idPanier + " .typeahead li.active"
          ).text();

          tab = designation.split(" => ");

          designation = tab[0];
        }

        $("#panier_" + idPanier).val("");

        $(".btn_Termine_Panier").attr("disabled", "disabled");

        $.ajax({
          url: "ajax/vendreLigneAjax.php",

          method: "POST",

          data: {
            operation: 15,

            designation: designation,

            idPagnet: idPanier,
          },

          success: function (data) {
            result = data.split("<>");

            if (result[0] == 1) {
              if (result[7] == 9) {
                var ligne =
                  "<tr>" +
                  "<td>" +
                  result[2] +
                  "</td>" +
                  "<td>Montant</td>" +
                  "<td>" +
                  result[3] +
                  "</td>" +
                  "<td><input class='form-control' onkeyup='modif_Prix(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  ")' value='" +
                  result[4] +
                  "' style='width: 30%' type='number'></input></td>" +
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
              } else {
                if (result[7] != "Article" && result[7] != "article") {
                  var ligne =
                    "<tr>" +
                    "<td>" +
                    result[2] +
                    "</td>" +
                    "<td><input class='form-control filedReadonly" +
                    idPanier +
                    "' id='ligne" +
                    result[5] +
                    "' onkeyup='modif_QuantiteP(this.value," +
                    result[5] +
                    "," +
                    idPanier +
                    "," +
                    result[8] +
                    " )' value='1' style='width: 30%' type='number' ></input></td>" +
                    "<td>" +
                    "<select id='uniteVente" +
                    result[5] +
                    "' class='form-control filedReadonly" +
                    idPanier +
                    "' onchange='modif_UniteStock(this.value)' >" +
                    "<option id='default" +
                    result[5] +
                    "' value='Article§" +
                    result[5] +
                    "§" +
                    idPanier +
                    "'>Article</option>" +
                    "<option value='" +
                    result[7] +
                    "§" +
                    result[5] +
                    "§" +
                    idPanier +
                    "'>" +
                    result[7] +
                    "</option>" +
                    "</select>" +
                    "</td>" +
                    "<td><input disabled='true' class='form-control' id='prixUniteStock" +
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
                    "<td><input class='form-control filedReadonly" +
                    idPanier +
                    "' id='ligne" +
                    result[5] +
                    "' onkeyup='modif_QuantiteP(this.value," +
                    result[5] +
                    "," +
                    idPanier +
                    ",1)' value='1' style='width: 30%' type='number' ></input></td>" +
                    "<td>" +
                    "<select id='uniteVente" +
                    result[5] +
                    "' class='form-control filedReadonly" +
                    idPanier +
                    "' onchange='modif_UniteStock(this.value)' >" +
                    "<option value='Article§" +
                    result[5] +
                    "§" +
                    idPanier +
                    "'>Article</option>" +
                    "</select>" +
                    "</td>" +
                    "<td><input disabled='true' class='form-control' id='prixUniteStock" +
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
                    ")'	 class='btn btn-warning pull-right btnRtrAvant" +
                    idPanier +
                    "'>" +
                    "<span class='glyphicon glyphicon-remove'></span>Retour" +
                    "</button>" +
                    "</td>" +
                    "</tr>";
                }
              }

              $("#tablePanier" + idPanier).prepend(ligne);

              $(".btn_Termine_Panier").removeAttr("disabled");

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] - $("#val_remise" + idPanier).val()
              );

              valTotal = result[6] - $("#val_remise" + idPanier).val();

              valApayer = result[10] - $("#val_remise" + idPanier).val();

              $("#somme_TotalCN" + idPanier).text(valTotal.toFixed(2));

              $("#somme_ApayerCN" + idPanier).text(valApayer.toFixed(2));

              var lcd = $("#lcd_Machine").val();

              if (lcd == 1) {
                var nd_Qte = 1;

                var nd_Prix = result[4];

                $.ajax({
                  url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

                  type: "GET", // data type (can be get, post, put, delete)

                  data: {
                    quantite: nd_Qte,

                    prix: nd_Prix,
                  }, // data in json format

                  async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

                  success: function (response) {
                    console.log(response);
                  },
                });
              }
            }

            if (result[0] == 2) {
              $("#tablePanier" + idPanier + " tr").each(function (row, tr) {
                reference = $(tr).find("td:eq(0)").text();

                if (reference != "" && reference == result[2]) {
                  $(tr)
                    .find("td:eq(1)")
                    .html(
                      "<input class='form-control filedReadonly" +
                        idPanier +
                        "' id='ligne" +
                        result[5] +
                        "' onkeyup='modif_QuantiteP(this.value," +
                        result[5] +
                        "," +
                        idPanier +
                        ",1)' value='" +
                        result[9] +
                        "' style='width: 30%' type='number' ></input>"
                    );

                  // $(".licV").text('');

                  $(".btn_Termine_Panier").removeAttr("disabled");

                  $("#somme_Total" + idPanier).text(result[6]);

                  $("#somme_Apayer" + idPanier).text(
                    result[6] - $("#val_remise" + idPanier).val()
                  );

                  valTotal = result[6] - $("#val_remise" + idPanier).val();

                  valApayer = result[10] - $("#val_remise" + idPanier).val();

                  $("#somme_TotalCN" + idPanier).text(valTotal.toFixed(2));

                  $("#somme_ApayerCN" + idPanier).text(valApayer.toFixed(2));

                  var lcd = $("#lcd_Machine").val();

                  if (lcd == 1) {
                    var nd_Qte = result[9];

                    var nd_Prix = result[4];

                    $.ajax({
                      url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

                      type: "GET", // data type (can be get, post, put, delete)

                      data: {
                        quantite: nd_Qte,

                        prix: nd_Prix,
                      }, // data in json format

                      async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

                      success: function (response) {
                        console.log(response);
                      },
                    });
                  }
                }
              });
            }

            if (result[0] == 3) {
              $("#qte_stock").text(result[1]);

              $("#msg_info_js").modal("show");

              $("#panier_" + idPanier).val("");
            }

            if (result[0] == 4) {
              var ligne =
                "<tr>" +
                "<td>" +
                result[2] +
                "</td>" +
                "<td><input class='form-control' onkeyup='modif_QuantiteSD(this.value," +
                result[5] +
                "," +
                idPanier +
                ")'  id='ligne" +
                result[5] +
                "'  value='1' style='width: 30%' type='number' ></input></td>" +
                "<td>" +
                result[7] +
                "</td>" +
                "<td><input class='form-control' id='prixUniteStock" +
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

            if (result[0] == 5) {
              var ligne =
                "<tr>" +
                "<td><input class='form-control' style='width: 100%'  type='text' value='" +
                result[2] +
                "' onkeyup='modif_DesignationBon(this.value," +
                result[5] +
                "," +
                idPanier +
                ")' /></td>" +
                // "<td>" + result[2] + "</td>" +

                "<td>Montant</td>" +
                "<td>Especes</td>" +
                "<td><input class='form-control' id='prixUniteStock" +
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

            if (result[0] == 6) {
              window.location.reload();
            }

            if (result[0] == 7) {
              var ligne =
                "<tr>" +
                "<td><input class='form-control' style='width: 100%'  type='text' value='" +
                result[2] +
                "' onkeyup='modif_DesignationBon(this.value," +
                result[5] +
                "," +
                idPanier +
                ")' /></td>" +
                "<td>Montant</td>" +
                "<td>Espece</td>" +
                "<td><input class='form-control' id='prixUniteStock" +
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

            if (result[0] == 8) {
              var ligne =
                "<tr>" +
                "<td>" +
                result[2] +
                "</td>" +
                "<td>" +
                result[4] +
                "</td>" +
                "<td>" +
                result[3] +
                "</td>" +
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
            }

            if (result[0] == 10) {
              if (result[7] == 9) {
                var ligne =
                  "<tr>" +
                  "<td>" +
                  result[2] +
                  "</td>" +
                  "<td>Montant</td>" +
                  "<td>" +
                  result[3] +
                  "</td>" +
                  "<td><input class='form-control' onkeyup='modif_Prix(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  ")' value='" +
                  result[4] +
                  "' style='width: 30%' type='number'></input></td>" +
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
              } else {
                if (result[7] != "Article" && result[7] != "article") {
                  var ligne =
                    "<tr>" +
                    "<td>" +
                    result[2] +
                    "</td>" +
                    "<td><input class='form-control' id='ligne" +
                    result[5] +
                    "' onkeyup='modif_QuantiteP(this.value," +
                    result[5] +
                    "," +
                    idPanier +
                    "," +
                    result[8] +
                    " )' value='1' style='width: 30%' type='number' ></input></td>" +
                    "<td>" +
                    "<select id='uniteVente" +
                    result[5] +
                    "' class='form-control' onchange='modif_UniteStock(this.value)' >" +
                    "<option id='default" +
                    result[5] +
                    "' value='Article§" +
                    result[5] +
                    "§" +
                    idPanier +
                    "'>Article</option>" +
                    "<option value='" +
                    result[7] +
                    "§" +
                    result[5] +
                    "§" +
                    idPanier +
                    "'>" +
                    result[7] +
                    "</option>" +
                    "</select>" +
                    "</td>" +
                    "<td><input  class='form-control' id='prixUniteStock" +
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
                } else {
                  var ligne =
                    "<tr>" +
                    "<td>" +
                    result[2] +
                    "</td>" +
                    "<td><input class='form-control' id='ligne" +
                    result[5] +
                    "' onkeyup='modif_QuantiteP(this.value," +
                    result[5] +
                    "," +
                    idPanier +
                    ",1)' value='1' style='width: 30%' type='number' ></input></td>" +
                    "<td>" +
                    "<select id='uniteVente" +
                    result[5] +
                    "' class='form-control' onchange='modif_UniteStock(this.value)' >" +
                    "<option value='Article§" +
                    result[5] +
                    "§" +
                    idPanier +
                    "'>Article</option>" +
                    "</select>" +
                    "</td>" +
                    "<td><input class='form-control' id='prixUniteStock" +
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
                }
              }

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

        // alert('/////////')

        // $("#panier_"+idPanier).val('');

        idFormParent = $("#panier_" + idPanier)
          .parent()
          .attr("id");

        // alert(idFormParent+'/////////')

        // setTimeout(() => {

        $("#" + idFormParent + " .typeahead").html("");

        // }, 500);
      }

      /*********** Fin tape sur Entrer **************/
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

function retour_Produit(ligne, panier) {
  $.ajax({
    url: "ajax/vendreLigneAjax.php",

    method: "POST",

    data: {
      operation: 16,

      idPagnet: panier,

      numligne: ligne,
    },

    success: function (data) {
      // alert(data)

      result = data.split("<>");

      if (result[0] == 1) {
        $("#tablePanier" + panier + " tr").each(function (row, tr) {
          reference = $(tr).find("td:eq(0)").text();

          unite = $(tr).find("td:eq(2)").text();

          if (reference != null && reference != "") {
            result2 = result[2];

            if (result2.indexOf("&amp;") >= 0) {
              result2 = result2.replace("&amp;", "&");
            }

            if (reference != "" && reference == result2) {
              $(tr).remove();

              $("#codeBarreLigneVt").val("");

              $("#codeBarreLigneVt").focus();

              $("#somme_Total" + panier).text(result[6]);

              $("#somme_Apayer" + panier).text(
                result[6] -
                  $("#val_remise" + panier).val() -
                  (result[6] * result[7]) / 100
              );

              // $("#somme_Apayer" + panier).text(result[6] - $("#val_remise" + panier).val());
            }
          } else if (unite != null && unite != "") {
            if (unite != "" && unite == result[3]) {
              $(tr).remove();

              $("#codeBarreLigneVt").val("");

              $("#codeBarreLigneVt").focus();

              $("#somme_Total" + panier).text(result[6]);

              $("#somme_Apayer" + panier).text(
                result[6] - $("#val_remise" + panier).val()
              );
            }
          }
        });
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/** Fin Autocomplete Vente Boutique*/

/** Debut Autocomplete Vente Pharmacie*/

$(function () {
  $(".codeBarreLignePh").keyup(function (e) {
    e.preventDefault();

    var tabIdPanier = $(this).attr("id");

    tab = tabIdPanier.split("_");

    idPanier = tab[1];

    var query = $(this).val();

    if (query.length > 0) {
      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 3,

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
              alert("La requête ss");
            },
          });
        },
      });

      $(this).focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        // var designation = $("#ajouterProdFormPh"+idPanier+" .typeahead li.active").text();

        // tab = designation.split(' => ');

        // designation = tab[0] || $(this).val();

        inputVal = $(this).val();

        if ($.isNumeric(inputVal)) {
          designation = $(this).val();
        } else {
          var designation = $(
            "#ajouterProdFormPh" + idPanier + " .typeahead li.active"
          ).text();

          tab = designation.split(" => ");

          designation = tab[0];
        }

        $.ajax({
          url: "ajax/vendreLigneAjax.php",

          method: "POST",

          data: {
            operation: 6,

            designation: designation,

            idPagnet: idPanier,
          },

          success: function (data) {
            result = data.split("<>");

            if (result[0] == 1) {
              if (result[7] == 9) {
                var ligne =
                  "<tr>" +
                  "<td>" +
                  result[2] +
                  "</td>" +
                  "<td>Montant</td>" +
                  "<td>" +
                  result[3] +
                  "</td>" +
                  "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  ")' value='" +
                  result[4] +
                  "' style='width: 70%' type='number'></input></td>" +
                  "<td>" +
                  "<button type='button' onclick='retour_ProduitPh(" +
                  result[5] +
                  "," +
                  idPanier +
                  ")'	 class='btn btn-warning pull-right'>" +
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
                  "<td><input class='form-control' onkeyup='modif_Quantite_PhP(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  ")' value='1' style='width: 70%' type='number' ></input></td>" +
                  "<td>" +
                  result[3] +
                  "</td>" +
                  "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," +
                  result[5] +
                  "," +
                  idPanier +
                  ")' value='" +
                  result[4] +
                  "' style='width: 70%' type='number'></input></td>" +
                  "<td>" +
                  "<button type='button' onclick='retour_ProduitPh(" +
                  result[5] +
                  "," +
                  idPanier +
                  ")'	 class='btn btn-warning pull-right'>" +
                  "<span class='glyphicon glyphicon-remove'></span>Retour" +
                  "</button>" +
                  "</td>" +
                  "</tr>";
              }

              $("#tablePanier" + idPanier).prepend(ligne);

              // $(".lic").text('');

              // $(this).val('');

              $("#panier_" + idPanier).val("");

              // $(".codeBarreLignePh").val('valueeeeeeeeee');

              // $(this).focus();

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] -
                  $("#val_remise" + idPanier).val() -
                  (result[6] * result[7]) / 100
              );
            }

            if (result[0] == 2) {
              $("#tablePanier" + idPanier + " tr").each(function (row, tr) {
                reference = $(tr).find("td:eq(0)").text();

                if (reference != "" && reference == result[2]) {
                  $(tr)
                    .find("td:eq(1)")
                    .html(
                      "<input class='form-control' onkeyup='modif_Quantite_PhP(this.value," +
                        result[5] +
                        "," +
                        idPanier +
                        ")' value='" +
                        result[7] +
                        "' style='width: 70%' type='number'></input>"
                    );

                  $("#panier_" + idPanier).val("");

                  $("#somme_Total" + idPanier).text(result[6]);

                  $("#somme_Apayer" + idPanier).text(
                    result[6] -
                      $("#val_remise" + idPanier).val() -
                      (result[6] * result[8]) / 100
                  );
                }
              });
            }

            if (result[0] == 3) {
              $("#qte_stock").text(result[1]);

              $("#msg_info_js").modal("show");

              $("#panier_" + idPanier).val("");
            }

            if (result[0] == 4) {
              var ligne =
                "<tr>" +
                "<td>" +
                result[2] +
                "</td>" +
                "<td><input class='form-control' onkeyup='modif_QuantiteSDP(this.value," +
                result[5] +
                "," +
                idPanier +
                ")' value='1' style='width: 70%' type='number' ></input></td>" +
                "<td>" +
                result[3] +
                "</td>" +
                "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," +
                result[5] +
                "," +
                idPanier +
                ")' value='" +
                result[4] +
                "' style='width: 70%' type='number'></input></td>" +
                "<td>" +
                "<button type='button' onclick='retour_ProduitPh(" +
                result[5] +
                "," +
                idPanier +
                ")'	 class='btn btn-warning pull-right'>" +
                "<span class='glyphicon glyphicon-remove'></span>Retour" +
                "</button>" +
                "</td>" +
                "</tr>";

              $("#tablePanier" + idPanier).prepend(ligne);

              $("#panier_" + idPanier).val("");

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] -
                  $("#val_remise" + idPanier).val() -
                  (result[6] * result[7]) / 100
              );
            }
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

      /*********** Fin tape sur Entrer **************/
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

$(function () {
  $("#btnEnregistrerCodeBarreAjaxPh").click(function () {
    var idPanier = $("#idPagnet").val();

    var designation = $("#codeBarreLignePh").val();

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 6,

        designation: designation,

        idPagnet: idPanier,
      },

      success: function (data) {
        result = data.split("<>");

        if (result[0] == 1) {
          var ligne =
            "<tr>" +
            "<td>" +
            result[2] +
            "</td>" +
            "<td><input class='form-control' onkeyup='modif_Quantite_PhP(this.value," +
            result[5] +
            "," +
            idPanier +
            ")' value='1' style='width: 70%' type='number' ></input></td>" +
            "<td>" +
            result[3] +
            "</td>" +
            "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," +
            result[5] +
            "," +
            idPanier +
            ")' value='" +
            result[4] +
            "' style='width: 70%' type='number'></input></td>" +
            "<td>" +
            "<button type='button' onclick='retour_ProduitPh(" +
            result[5] +
            "," +
            idPanier +
            ")'	 class='btn btn-warning pull-right'>" +
            "<span class='glyphicon glyphicon-remove'></span>Retour" +
            "</button>" +
            "</td>" +
            "</tr>";

          $("table.tabPanier").prepend(ligne);

          $(".lic").text("");

          $("#codeBarreLignePh").val("");

          $("#codeBarreLignePh").focus();

          $("#somme_Total" + idPanier).text(result[6]);

          $("#somme_Apayer" + idPanier).text(
            result[6] - $("#val_remise" + idPanier).val()
          );
        }

        if (result[0] == 2) {
          $("#tablePanier tr").each(function (row, tr) {
            reference = $(tr).find("td:eq(0)").text();

            if (reference != "" && reference == result[2]) {
              $(tr)
                .find("td:eq(1)")
                .html(
                  "<input class='form-control' onkeyup='modif_Quantite_PhP(this.value," +
                    result[5] +
                    "," +
                    idPanier +
                    ")' value='" +
                    result[7] +
                    "' style='width: 70%' type='number'></input>"
                );

              $(".lic").text("");

              $("#codeBarreLignePh").val("");

              $("#codeBarreLignePh").focus();

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] - $("#val_remise" + idPanier).val()
              );
            }
          });
        }

        if (result[0] == 3) {
          $("#qte_stock").text(result[1]);

          $("#msg_info_js").modal("show");

          $(".lic").text("");

          $("#codeBarreLignePh").val("");

          $("#codeBarreLignePh").focus();
        }

        //console.log(data);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/** Fin Autocomplete Vente Pharmacie*/

/** Debut Autocomplete Bon Client Pharmacie*/

$(function () {
  $("#codeBarreLignePhC").keyup(function () {
    var query = $("#codeBarreLignePhC").val();

    if (query.length > 0) {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 4,

          query: query,
        },

        success: function (data) {
          $("#reponseV").html(data);

          console.log("insertion impo" + data);
        },

        dataType: "text",
      });
    }
  });

  $(document).on("click", 'li[class="lic"]', function () {
    var designation = $(this).text();

    tab = designation.split("=>");

    //$("#designationStock").val(tab[0]);

    $("#codeBarreLignePhC").val(tab[0]);
  });
});

/** Fin Autocomplete Bon Client Pharmacie*/

/** Debut Autocomplete Vente Entrepot*/

$(function () {
  $(".codeBarreLigneEt").keyup(function (e) {
    e.preventDefault();

    var tabIdPanier = $(this).attr("id");

    tab = tabIdPanier.split("_");

    idPanier = tab[1];
    // alert(idPanier)

    var query = $(this).val();

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

            // alert(result[13])
            // alert(result.length)

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

              if ($("#sessioninfosup").val() == 1) {
                infoSupp =
                  "<button type='button' data-idligne='" +
                  result[5] +
                  "' data-toggle='modal' data-target='#add_infoSup_modal' class='btn btn-info pull-right btn_add_infoSup1'>" +
                  "<span class='glyphicon glyphicon-plus'></span>info. sup" +
                  "</button>";
              } else {
                infoSupp = "";
              }

              if (result[8] == 1) {
                var ligne =
                  "<tr>" +
                  "<td>" +
                  result[2] +
                  "</td>" +
                  "<td><input class='form-control' id='quantite" +
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
                  "' class='form-control' onchange='modif_UniteStockET(this.value)' >" +
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
                  "<td><input class='form-control' id='prixunitevente" +
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
                  "' class='form-control' onchange='modif_Depot(this.value)' >" +
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
                  ")'	 class='btn btn-warning pull-right'>" +
                  "<span class='glyphicon glyphicon-remove'></span>Retour" +
                  "</button>" +
                  infoSupp +
                  "</td>" +
                  "</tr>";
              } else {
                var ligne =
                  "<tr>" +
                  "<td>" +
                  result[2] +
                  "</td>" +
                  "<td><input class='form-control' id='quantite" +
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
                  "' class='form-control' onchange='modif_UniteStockET(this.value)' >" +
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
                  "<td><input class='form-control' id='prixunitevente" +
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
                  "' class='form-control' onchange='modif_Depot(this.value)' >" +
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
                  ")'	 class='btn btn-warning pull-right'>" +
                  "<span class='glyphicon glyphicon-remove'></span>Retour" +
                  "</button>" +
                  infoSupp +
                  "</td>" +
                  "</tr>";
              }

              $("#tablePanier" + idPanier).prepend(ligne);

              $("#panier_" + idPanier).val("");

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] -
                  $("#val_remise" + idPanier).val() -
                  (result[6] * result[13]) / 100
              );

              // $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());
            }

            if (result[0] == 2) {
              window.location.reload();
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
                "<td><input class='form-control' style='width: 100%'  type='text' value='" +
                result[2] +
                "' onkeyup='modif_DesignationBon(this.value," +
                result[5] +
                "," +
                idPanier +
                ")' /></td>" +
                // "<td>" + result[2] + "</td>" +

                "<td>Montant</td>" +
                "<td>Especes</td>" +
                "<td><input class='form-control' id='prixunitevente" +
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
                result[6] -
                  $("#val_remise" + idPanier).val() -
                  (result[6] * result[10]) / 100
              );

              // $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

              valTotal = result[6] - $("#val_remise" + idPanier).val();

              valApayer = result[10] - $("#val_remise" + idPanier).val();

              $("#somme_TotalCN" + idPanier).text(valTotal.toFixed(2));

              $("#somme_ApayerCN" + idPanier).text(valApayer.toFixed(2));
            }

            if (result[0] == 6) {
              window.location.reload();
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

/** Debut Autocomplete Vente Entrepot*/

$(function () {});

/** Fin Autocomplete Vente Entrepot*/

/** Debut Autocomplete Vente Entrepot*/

$(function () {
  $("#prenomFactureEt").keyup(function () {
    var query = $("#prenomFactureEt").val();

    if (query.length > 0) {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 14,

          query: query,
        },

        success: function (data) {
          $("#reponseF").html(data);

          console.log("insertion impo" + data);
        },

        dataType: "text",
      });
    }
  });

  $(document).on("click", 'li[class="lifET"]', function () {
    var client = $(this).text();

    tab = client.split("<>");

    $("#prenomFactureEt").val(tab[0]);

    $("#nomFactureEt").val(tab[1]);

    $("#adresseFactureEt").val(tab[2]);

    $("#telephoneFactureEt").val(tab[3]);

    $("#reponseF").html("");
  });
});

/** Fin Autocomplete Vente Entrepot*/

/** Debut Rechercher Produit Vendu Aujourd'hui*/

$(function () {
  // alert(111)

  $("#produitVendu").keyup(function (e) {
    e.preventDefault();

    var query = $("#produitVendu").val();

    // alert(query)

    if (query.length > 0 || query.length != "") {
      $("#produitVendu").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 8,

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
              alert("La requête ss");
            },
          });
        },
      });

      $("#produitVendu").focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        var designation = $("#searchProdForm .typeahead li.active").text();

        $("#produitVendu").val(designation);

        $("#searchProdForm").submit();
      }

      $(document).on(
        "click",
        '#searchProdForm .typeahead li a[class="dropdown-item"]',
        function (e) {
          e.preventDefault();

          var designation = $("#searchProdForm .typeahead li.active").text();

          $("#produitVendu").val(designation);

          $("#searchProdForm").submit();
        }
      );
    } else {
      window.location.href = "insertionLigneLight.php";
    }
  });

  $("#produitVendu").val($("#produitAfter").val());
});

/** Fin Rechercher Produit Vendu Aujourd'hui*/

/** Debut Rechercher Produit Vendu avant Aujourd'hui Pharmacie*/

$(function () {
  $("#produitVenduAvPh").keyup(function () {
    var query = $("#produitVenduAvPh").val();

    var date_jour = $("#dateProduitVendu").val();

    if (query.length > 0 || query.length != "") {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 9,

          query: query,

          date_jour: date_jour,
        },

        success: function (data) {
          $("#reponsePV").html(data);
        },

        dataType: "text",
      });
    } else {
      window.location.href = "actualiserVenteP.php?jour=" + date_jour;
    }
  });

  $(document).on("click", 'li[class="liVapPh"]', function () {
    var designation = $(this).text();

    tab = designation.split("=>");

    $("#produitVenduAvPh").val(tab[0]);

    $("#searchProdForm").submit();
  });

  $("#produitVenduAvPh").val($("#produitAfter").val());
});

/** Fin Rechercher Produit Vendu avant Aujourd'hui Pharmacie*/

/** Debut Rechercher Produit Vendu avant Aujourd'hui*/

$(function () {
  $("#produitVenduAv").keyup(function () {
    var query = $("#produitVenduAv").val();

    var date_jour = $("#dateProduitVendu").val();

    if (query.length > 0 || query.length != "") {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 9,

          query: query,

          date_jour: date_jour,
        },

        success: function (data) {
          $("#reponsePV").html(data);
        },

        dataType: "text",
      });
    } else {
      window.location.href = "actualiserVente.php?jour=" + date_jour;
    }
  });

  $(document).on("click", 'li[class="liVap"]', function () {
    var designation = $(this).text();

    $("#produitVenduAv").val(designation);

    $("#searchProdForm").submit();
  });

  $("#produitVenduAv").val($("#produitAfter").val());
});

/** Fin Rechercher Produit Vendu avant Aujourd'hui */

/** Debut Rechercher Produit Bon Client*/

$(function () {
  $("#produitBon").keyup(function () {
    var query = $("#produitBon").val();

    var idClient = $("#clientBon").val();

    if (query.length > 0 || query.length != "") {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 10,

          query: query,

          idClient: idClient,
        },

        success: function (data) {
          $("#reponsePV").html(data);
        },

        dataType: "text",
      });
    } else {
      window.location.href = "bonPclient.php?c=" + idClient;
    }
  });

  $(document).on("click", 'li[class="liB"]', function () {
    var designation = $(this).text();

    $("#produitBon").val(designation);

    $("#searchProdForm").submit();
  });

  $("#produitBon").val($("#produitAfter").val());
});

/** Fin Rechercher Produit Bon Client */

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

$(function () {
  $("#uniteStock").change(function () {
    var uniteStock = $("#uniteStock").val();

    var link = document.getElementById("prixuniteStock");

    if (uniteStock == "article") {
      link.setAttribute("disabled", "");
    } else {
      console.log("#idD ok " + uniteStock);

      $(link).removeAttr("disabled");
    }
  });

  $("#typeTrans").change(function () {
    var value = $(this).val();

    if (value == 2) {
      $("#listFacture").show();
    } else {
      $("#listFacture").hide();
    }
  });

  $("#typePaie").change(function () {
    var value = $(this).val();

    if (value == "Especes") {
      $("#versement").show();
    } else {
      $("#versement").hide();
    }
  });

  $("#devise").change(function () {
    var value = $(this).val();

    $.ajax({
      url: "ajax/modifierLigneAjax.php",

      method: "POST",

      data: {
        operation: 20,

        devise: value,
      },

      success: function (data) {
        location.reload();
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });

  $("#catalogue").change(function () {
    var value = $(this).val();

    nbEntreeBInit = $("#nbEntreeBInit").val();

    $("#resultsProductsInitB").load("ajax/listerCatalogueAjax.php", {
      operation: 1,
      nbEntreeBInit: nbEntreeBInit,
      query: "",
      cb: "",
      nom: value,
    }); //load initial records

    $("#searchInputBInit").val("");

    // $.ajax({

    //     url: "ajax/listerCatalogueAjax.php",

    //     method: "POST",

    //     data: {

    //         nom: value,

    //     },

    //     success: function(data) {

    //         location.reload();

    //     },

    //     error: function() {

    //         alert("La requête ");

    //     },

    //     dataType: "text"

    // });
  });

  $("#cataloguePH").change(function () {
    var value = $(this).val();

    nbEntreePhInit = $("#nbEntreePhInit").val();

    $("#resultsProductsInit").load("ajax/listerCatalogue-PharmacieAjax.php", {
      operation: 1,
      nbEntreePhInit: nbEntreePhInit,
      query: "",
      cb: "",
      nom: value,
    }); //load initial records

    $("#searchInputPhInit").val("");

    // alert(value)

    // $.ajax({

    //     url: "ajax/listerCatalogue-PharmacieAjax.php",

    //     method: "POST",

    //     data: {

    //         nom: value

    //     },

    //     success: function(data) {

    //         // alert(data)

    //         // location.reload();

    //     },

    //     error: function() {

    //         alert("La requête ");

    //     },

    //     dataType: "text"

    // });
  });
});

$(function () {
  $("#codeBarrePagnet").keyup(function () {
    var codeBarre = $("#codeBarrePagnet").val();

    tab = codeBarre.split("-");

    console.log("#codeBarre tab length " + tab.length);

    if (tab.length > 2 && tab[2] != "") {
      var idPagnet = $("#idPagnet").val();

      $.ajax({
        url: "ajax/panierAjax.php",

        method: "POST",

        data: {
          ipP: idPagnet,

          codB: codeBarre,
        },

        success: function (data) {
          console.log('"data kkkkk"' + data);

          console.log("requette succéééééééééé");
        },

        error: function () {
          console.log("La requête na pas aboutiiiii =>");

          //alert("eroooor");
        },

        dataType: "text",
      });
    }
  });
});

function factureB(par) {
  var idPagnet = par;

  var id = "#btnImprimerFacture" + par;

  // var IdTabBonClient="#tabBonClient"+par;

  // var spanId="#factureFois"+par;

  // var span=IdTabBonClient+' tbody tr td span[class="factureFois"]'

  //$(IdTabBonClient+'div ul li[class="factureFois"]')

  //$(spanId).html('X');

  $("span.factureFois").html("X");

  $("div.divFacture").css("display", "block");

  window.print();

  console.log("facture ffgg " + id);

  $("span.factureFois").html("");

  $("div.divFacture").css("display", "none");
}

function factureVer() {
  $("div.divFacture").css("display", "block");

  window.print();

  console.log("facture ffgg " + id);

  $("div.divFacture").css("display", "none");
}

/**Debut modifier la quantite dans la ligne d'un Pagnet**/

function modif_Quantite(quantite, ligne, pagnet, unitestock, quantiteCourant) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 4,

      idPagnet: pagnet,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      tab = data.split("-");

      ligneQte = tab[0] * tab[1];

      //alert("Quantite : "+ligneQte);

      restant = quantiteCourant - (quantite * unitestock + ligneQte);

      //alert("Restant : "+restant);

      if (restant >= 0) {
        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 1,

            idPagnet: pagnet,

            numLigne: ligne,

            quantite: quantite,
          },

          success: function (data) {
            tab1 = data.split("<>");

            if (tab1[0] == 1) {
              $("#somme_Total" + pagnet).text(tab1[1]);

              $("#somme_Apayer" + pagnet).text(
                tab1[1] - $("#val_remise" + pagnet).val()
              );

              var lcd = $("#lcd_Machine").val();

              if (lcd == 1) {
                var nd_Qte = tab1[2];

                var nd_Prix = tab1[3];

                $.ajax({
                  url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

                  type: "GET", // data type (can be get, post, put, delete)

                  data: {
                    quantite: nd_Qte,

                    prix: nd_Prix,
                  }, // data in json format

                  async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

                  success: function (response) {
                    console.log(response);
                  },
                });
              }
            }

            // console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });
      } else {
        reste = quantiteCourant - ligneQte;

        $("#qte_stock").text(reste);

        $("#msg_info_js").modal("show");

        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 1,

            idPagnet: pagnet,

            numLigne: ligne,

            quantite: 1,
          },

          success: function (data) {
            tab1 = data.split("<>");

            if (tab1[0] == 1) {
              $("#somme_Total" + pagnet).text(tab1[1]);

              $("#somme_Apayer" + pagnet).text(
                tab1[1] - $("#val_remise" + pagnet).val()
              );

              var lcd = $("#lcd_Machine").val();

              if (lcd == 1) {
                var nd_Qte = tab1[2];

                var nd_Prix = tab1[3];

                $.ajax({
                  url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

                  type: "GET", // data type (can be get, post, put, delete)

                  data: {
                    quantite: nd_Qte,

                    prix: nd_Prix,
                  }, // data in json format

                  async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

                  success: function (response) {
                    console.log(response);
                  },
                });
              }
            }

            console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });

        $("#ligne" + ligne).val(1);
      }

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet**/

/**Debut modifier la quantite dans la ligne d'un Pagnet**/

function modif_QuantiteP(quantite, ligne, pagnet, unitestock) {
  var stock = $("#uniteVente" + ligne).val();

  tab0 = stock.split("§");

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 9,

      idPagnet: pagnet,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      tab = data.split("-");

      ligneQte = tab[0] * tab[1];

      if (tab0[0] == "Article" || tab0[0] == "article") {
        restant = tab[2] - (quantite * 1 + ligneQte);
      } else {
        restant = tab[2] - (quantite * tab[3] + ligneQte);
      }

      //if (restant >= 0) {

      $.ajax({
        url: "ajax/modifierLigneAjax.php",

        method: "POST",

        data: {
          operation: 10,

          idPagnet: pagnet,

          numLigne: ligne,

          quantite: quantite,
        },

        success: function (data) {
          tab1 = data.split("<>");

          if (tab1[0] == 1) {
            $("#somme_Total" + pagnet).text(tab1[1]);

            $("#somme_Apayer" + pagnet).text(
              tab1[1] - $("#val_remise" + pagnet).val()
            );

            valTotal = tab1[1] - $("#val_remise" + pagnet).val();

            valApayer = tab1[4] - $("#val_remise" + pagnet).val();

            $("#somme_TotalCN" + pagnet).text(valTotal.toFixed(2));

            $("#somme_ApayerCN" + pagnet).text(valApayer.toFixed(2));

            var lcd = $("#lcd_Machine").val();

            if (lcd == 1) {
              var nd_Qte = tab1[2];

              var nd_Prix = tab1[3];

              $.ajax({
                url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

                type: "GET", // data type (can be get, post, put, delete)

                data: {
                  quantite: nd_Qte,

                  prix: nd_Prix,
                }, // data in json format

                async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

                success: function (response) {
                  console.log(response);
                },
              });
            }
          }

          console.log(data);
        },

        error: function (error) {
          console.log(error);
        },

        dataType: "text",
      });

      /*    

            } else {

                reste = tab[2] - ligneQte;

                $("#qte_stock").text(reste);

                $('#msg_info_js').modal('show');

                $.ajax({

                    url: "ajax/modifierLigneAjax.php",

                    method: "POST",

                    data: {

                        operation: 10,

                        idPagnet: pagnet,

                        numLigne: ligne,

                        quantite: 1,

                    },

                    success: function(data) {

                        $("#somme_Total" + pagnet).text(data);

                        $("#somme_Apayer" + pagnet).text(data - $("#val_remise" + pagnet).val());

                        console.log(data);

                    },

                    error: function() {

                        alert("La requête ");

                    },

                    dataType: "text"

                });

                $("#ligne" + ligne).val(1);

            }

            */

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet**/

/**Debut modifier la quantite dans la ligne d'un Pagnet**/

function modif_QuantiteET(quantite, ligne, pagnet) {
  // alert(10000)

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 15,

      idPagnet: pagnet,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      tab = data.split("-");

      // alert("Quantite : "+quantite);

      restant = tab[0] - tab[1];

      if (restant >= 0) {
        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 16,

            idPagnet: pagnet,

            numLigne: ligne,

            quantite: quantite,
          },

          success: function (data) {
            $("#somme_Total" + pagnet).text(data);

            $("#somme_Apayer" + pagnet).text(
              data - $("#val_remise" + pagnet).val()
            );

            console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });
      } else {
        reste = tab[0];

        $("#qte_stock").text(reste);

        $("#msg_info_js").modal("show");

        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 16,

            idPagnet: pagnet,

            numLigne: ligne,

            quantite: 1,
          },

          success: function (data) {
            $("#somme_Total" + pagnet).text(data);

            $("#somme_Apayer" + pagnet).text(
              data - $("#val_remise" + pagnet).val()
            );

            console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });

        $("#quantite" + ligne).val(1);
      }

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet**/

/**Debut modifier la quantite dans la ligne d'un Pagnet**/

function modif_QuantiteSD(quantite, ligne, pagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 8,

      idPagnet: pagnet,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      $("#somme_Total" + pagnet).text(data);

      $("#somme_Apayer" + pagnet).text(data - $("#val_remise" + pagnet).val());

      // console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet**/

/**Debut modifier la quantite dans la ligne d'un Pagnet de Retour**/

function modif_QuantiteRP(
  quantite,
  ligne,
  pagnet,
  unitestock,
  quantiteStockinitial
) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 4,

      idPagnet: pagnet,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      tab = data.split("-");

      ligneQte = tab[0] * tab[1];

      //alert("Quantite : "+ligneQte);

      restant = quantiteStockinitial - (quantite * unitestock + ligneQte);

      if (restant >= 0) {
        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 14,

            idPagnet: pagnet,

            numLigne: ligne,

            quantite: quantite,
          },

          success: function (data) {
            $("#somme_Total" + pagnet).text(data);

            $("#somme_Apayer" + pagnet).text(
              data - $("#val_remise" + pagnet).val()
            );

            console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });
      } else {
        reste = quantiteStockinitial - ligneQte;

        $("#qte_stock").text(reste);

        $("#msg_info_js").modal("show");

        $("#ligne" + ligne).val(1);
      }

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet de Retour**/

/**Debut modifier le prix dans la ligne d'un Pagnet**/

function modif_Prix(prix, ligne, pagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 2,

      idPagnet: pagnet,

      numLigne: ligne,

      prix: prix,
    },

    success: function (data) {
      tab1 = data.split("<>");

      if (tab1[0] == 1) {
        $("#somme_Total" + pagnet).text(tab1[1]);

        $("#somme_Apayer" + pagnet).text(
          tab1[1] - $("#val_remise" + pagnet).val()
        );

        valTotal = tab1[1] - $("#val_remise" + pagnet).val();

        valApayer = tab1[4] - $("#val_remise" + pagnet).val();

        $("#somme_TotalCN" + pagnet).text(valTotal.toFixed(2));

        $("#somme_ApayerCN" + pagnet).text(valApayer.toFixed(2));

        var lcd = $("#lcd_Machine").val();

        if (lcd == 1) {
          var nd_Qte = tab1[2];

          var nd_Prix = tab1[3];

          $.ajax({
            url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

            type: "GET", // data type (can be get, post, put, delete)

            data: {
              quantite: nd_Qte,

              prix: nd_Prix,
            }, // data in json format

            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

            success: function (response) {
              console.log(response);
            },
          });
        }
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la prix dans la ligne d'un Pagnet**/

/**Debut modifier le prix dans la ligne d'un Pagnet**/

function modif_Espece(especes, ligne, pagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 22,

      idPagnet: pagnet,

      numLigne: ligne,

      especes: especes,
    },

    success: function (data) {
      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la prix dans la ligne d'un Pagnet**/

/**Debut modifier la designation d'un Bon ou Depense dans la ligne d'un Pagnet**/

function modif_DesignationBon(designation, ligne, pagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 23,

      idPagnet: pagnet,

      numLigne: ligne,

      designation: designation,
    },

    success: function (data) {
      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la designation d'un Bon ou Depense dans la ligne d'un Pagnet**/

/**Debut modifier la quantite dans la ligne d'un Pagnet Pharmacie**/

function modif_Quantite_Ph(quantite, ligne, pagnet, quantiteCourant) {
  // alert(quantite+" "+qu)

  var restant = quantiteCourant - quantite;

  if (restant >= 0) {
    $.ajax({
      url: "ajax/modifierLigneAjax.php",

      method: "POST",

      data: {
        operation: 5,

        idPagnet: pagnet,

        numLigne: ligne,

        quantite: quantite,
      },

      success: function (data) {
        $("#somme_Total" + pagnet).text(data);

        $("#somme_Apayer" + pagnet).text(
          data - $("#val_remise" + pagnet).val()
        );

        //console.log(data);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    //reste = quantiteCourant - quantite;

    $("#qte_stock").text(quantiteCourant);

    $("#msg_info_js").modal("show");

    $.ajax({
      url: "ajax/modifierLigneAjax.php",

      method: "POST",

      data: {
        operation: 5,

        idPagnet: pagnet,

        numLigne: ligne,

        quantite: 1,
      },

      success: function (data) {
        $("#somme_Total" + pagnet).text(data);

        $("#somme_Apayer" + pagnet).text(
          data - $("#val_remise" + pagnet).val()
        );

        // console.log(data);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    $("#ligne" + ligne).val(1);
  }

  console.log(data);
}

/**Fin modifier la quantite dans la ligne d'un Pagnet Pharmacie**/

/**Debut modifier la quantite dans la ligne d'un Pagnet Pharmacie**/

function modif_Quantite_PhP(quantite, ligne, pagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 11,

      idPagnet: pagnet,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      result = data.split("<>");

      ligneQte = result[0];

      //alert("Quantite : "+ligneQte);

      restant = ligneQte - quantite;

      //alert("Restant : "+restant);

      if (restant >= 0) {
        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 12,

            idPagnet: pagnet,

            numLigne: ligne,

            quantite: quantite,
          },

          success: function (data) {
            result = data.split("<>");

            $("#somme_Total" + pagnet).text(result[0]);

            $("#somme_Apayer" + pagnet).text(
              result[0] -
                $("#val_remise" + pagnet).val() -
                (result[0] * result[1]) / 100
            );

            //console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });
      } else {
        reste = ligneQte;

        $("#qte_stock").text(reste);

        $("#msg_info_js").modal("show");

        quantite = 1;

        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 120,

            idPagnet: pagnet,

            numLigne: ligne,

            quantite: 1,
          },

          success: function (data) {
            result = data.split("<>");

            $("#somme_Total" + pagnet).text(result[1]);

            $("#somme_Apayer" + pagnet).text(
              result[0] - $("#val_remise" + pagnet).val()
            );

            $("#tablePanier tr").each(function (row, tr) {
              reference = $(tr).find("td:eq(0)").text();

              if (reference != "" && reference == result[1]) {
                //alert(reference);

                $(tr)
                  .find("td:eq(1)")
                  .html(
                    "<input class='form-control' onkeyup='modif_Quantite_PhP(" +
                      quantite +
                      "," +
                      ligne +
                      "," +
                      pagnet +
                      ")' value='1' style='width: 70%' type='number'></input>"
                  );

                $(".lic").text("");

                $("#codeBarreLignePh").val("");

                $("#codeBarreLignePh").focus();
              }
            });

            //console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });

        $("#ligne" + ligne).val(1);
      }

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet Pharmacie**/

/**Debut modifier la quantite dans la ligne d'un Pagnet**/

function modif_QuantiteSDP(quantite, ligne, pagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 13,

      idPagnet: pagnet,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      $("#somme_Total" + pagnet).text(data);

      $("#somme_Apayer" + pagnet).text(data - $("#val_remise" + pagnet).val());

      // console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet**/

/**Debut modifier le prix dans la ligne d'un Pagnet Pharmacie**/

function modif_Prix_Ph(prix, ligne, pagnet) {
  //alert(prix+' '+ligne+' '+pagnet);

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 7,

      idPagnet: pagnet,

      numLigne: ligne,

      prix: prix,
    },

    success: function (data) {
      //$('#somme_Pagnet').val(data);

      result = data.split("<>");

      $("#somme_Total" + pagnet).text(result[0]);

      $("#somme_Apayer" + pagnet).text(
        result[0] -
          $("#val_remise" + pagnet).val() -
          (result[0] * result[1]) / 100
      );

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la prix dans la ligne d'un Pagnet Pharmacie**/

/**Debut ajouter ligne dans Pagnet Transfert**/

function ajt_LigneTransfert(pagnet, transfert) {
  var compte = $("#compte-" + transfert).val();

  var caisse = $("#caisse-" + transfert).val();

  $("#btn_Transfert-" + transfert).prop("disabled", true);

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 21,

      idPagnet: pagnet,

      idTransaction: transfert,

      compte: compte,
    },

    success: function (data) {
      //alert(data);

      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr>" +
          "<td>" +
          tab[1] +
          "</td>" +
          "<td>" +
          tab[2] +
          "</td>" +
          "<td>" +
          tab[3] +
          "</td>" +
          "<td> </td>" +
          "<td>" +
          tab[4] +
          "</td>" +
          "<td>" +
          "<button disabled='true' type='button' class='btn btn-warning pull-right'>" +
          "<span class='glyphicon glyphicon-remove'></span>Retour" +
          "</button>" +
          "</td>" +
          "</tr>";

        $("table.tabPanier").prepend(ligne);

        $("#somme_Total" + pagnet).text(tab[5]);

        $("#somme_Apayer" + pagnet).text(tab[5]);
      } else {
        //alert("Erreur");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter ligne dans Pagnet Transfert**/

/**<script>**/

function date_jour(jour) {
  var x = document.getElementById(jour).value;

  var tab = x.split("-");

  var date_jour = tab[2] + "-" + tab[1] + "-" + tab[0];

  window.location.href = "historiquecaisse.php?dateprevious=" + date_jour;
}

/**</script>**/

/**<script>**/

function date_jour_Vente(jour) {
  var x = document.getElementById(jour).value;

  var tab = x.split("-");

  var date_jour = tab[2] + "-" + tab[1] + "-" + tab[0];

  window.location.href = "actualiserVente.php?jour=" + date_jour;
}

/**</script>**/

/**<script>**/

function date_jour_VenteP(jour) {
  var x = document.getElementById(jour).value;

  var tab = x.split("-");

  var date_jour = tab[2] + "-" + tab[1] + "-" + tab[0];

  window.location.href = "actualiserVenteP.php?jour=" + date_jour;
}

/**</script>**/

/**<script>**/

function date_jour_VenteET(jour) {
  var x = document.getElementById(jour).value;

  var tab = x.split("-");

  var date_jour = tab[2] + "-" + tab[1] + "-" + tab[0];

  window.location.href = "actualiserVenteET.php?jour=" + date_jour;
}

/**</script>**/

/**Debut ajouter Fournisseur Pharmacie**/

$(function () {
  $("#btn_ajt_Fournisseur").click(function () {
    var nom = $("#ajtNomFN").val();

    var adresse = $("#ajtAdresseFN").val();

    var telephone = $("#ajtTelephoneFN").val();

    var banque = $("#ajtBanqueFN").val();

    var numBanque = $("#ajtNumBankFN").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 16,

        nomFournisseur: nom,

        adresseFournisseur: adresse,

        telephoneFournisseur: telephone,

        banqueFournisseur: banque,

        numBanqueFournisseur: numBanque,
      },

      success: function (data) {
        // alert(data)
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[4] +
            "</td><td>" +
            tab[5] +
            "</td><td>0</td><td>En cours ...</td></tr>";

          $("table.tabFournisseur").prepend(ligne);

          $("#ajtNomFN").val("");

          $("#ajtAdresseFN").val("");

          $("#ajtTelephoneFN").val("");

          $("#ajtBanqueFN").val("");

          $("#ajtNumBankFN").val("");

          $("#ajoutFournisseur").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter Fournisseur Pharmacie**/

/**Debut modifier Fournisseur Pharmacie**/

function mdf_Fournisseur(idFournisseur, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 18,

      idFournisseur: idFournisseur,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idFN_Mdf").val(tab[1]);

        $("#ordreFN_Mdf").val(ordre);

        $("#nomFN_Mdf").val(tab[2]);

        $("#adresseFN_Mdf").val(tab[3]);

        $("#telephoneFN_Mdf").val(tab[4]);

        $('#ajtBanqueFN_Mdf option[value="' + tab[5] + '"]').attr(
          "selected",
          "selected"
        );

        // $('#ajtBanqueFN_Mdf').children("option:selected").text(tab[5]);

        $("#ajtNumBankFN_Mdf").val(tab[6]);

        $("#modifierFournisseur").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Fournisseur").click(function () {
    var idFournisseur = $("#idFN_Mdf").val();

    var ordre = $("#ordreFN_Mdf").val();

    var nom = $("#nomFN_Mdf").val();

    var adresse = $("#adresseFN_Mdf").val();

    var telephone = $("#telephoneFN_Mdf").val();

    var banque = $("#ajtBanqueFN_Mdf").val();

    var numBanque = $("#ajtNumBankFN_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 19,

        idFournisseur: idFournisseur,

        nomFournisseur: nom,

        adresseFournisseur: adresse,

        telephoneFournisseur: telephone,

        banqueFournisseur: banque,

        numBanqueFournisseur: numBanque,
      },

      success: function (data) {
        $("#idFN_Mdf").val("");

        $("#ordreFN_Mdf").val("");

        $("#nomFN_Mdf").val("");

        $("#adresseFN_Mdf").val("");

        $("#telephoneFN_Mdf").val("");

        $("#ajtBanqueFN_Mdf").val("");

        $("#ajtNumBankFN_Mdf").val("");

        $("#modifierFournisseur").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableFournisseur tr").each(function (row, tr) {
            fournisseur = $(tr).find("td:eq(0)").text();

            if (fournisseur != "" && fournisseur == ordre) {
              $(tr).find("td:eq(1)").html(tab[2]);

              $(tr).find("td:eq(2)").html(tab[3]);

              $(tr).find("td:eq(3)").html(tab[4]);

              $(tr).find("td:eq(4)").html(tab[5]);

              $(tr).find("td:eq(5)").html(tab[6]);

              $(tr).find("td:eq(6)").html(tab[7]);

              $(tr).find("td:eq(7)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Fournisseur Pharmacie**/

/**Debut supprimer Fournisseur Pharmacie**/

function spm_Fournisseur(idFournisseur, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 18,

      idFournisseur: idFournisseur,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idFN_Spm").val(tab[1]);

        $("#ordreFN_Spm").val(ordre);

        $("#nomFN_Spm").val(tab[2]);

        $("#adresseFN_Spm").val(tab[3]);

        $("#telephoneFN_Spm").val(tab[4]);

        $("#supprimerFournisseur").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Fournisseur").click(function () {
    var idFournisseur = $("#idFN_Spm").val();

    var ordre = $("#ordreFN_Spm").val();

    var nom = $("#nomFN_Spm").val();

    var adresse = $("#adresseFN_Spm").val();

    var telephone = $("#telephoneFN_Spm").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 20,

        idFournisseur: idFournisseur,
      },

      success: function (data) {
        $("#idFN_Spm").val("");

        $("#ordreFN_Spm").val("");

        $("#nomFN_Spm").val("");

        $("#adresseFN_Spm").val("");

        $("#telephoneFN_Spm").val("");

        $("#supprimerFournisseur").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableFournisseur tr").each(function (row, tr) {
            fournisseur = $(tr).find("td:eq(0)").text();

            if (fournisseur != "" && fournisseur == ordre) {
              $(tr).find("td:eq(1)").html(nom);

              $(tr).find("td:eq(2)").html(adresse);

              $(tr).find("td:eq(3)").html(telephone);

              $(tr).find("td:eq(4)").html(0);

              $(tr).find("td:eq(5)").html(0);

              $(tr).find("td:eq(6)").html(0);

              $(tr).find("td:eq(7)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Fournisseur Pharmacie**/

/**Debut ajouter BL Pharmacie**/

$(function () {
  $("#btn_ajt_Bl").click(function () {
    var fournisseur = $("#ajtFournisseurBL").val();

    var numero = $("#ajtNumeroBL").val();

    var date = $("#ajtDateBL").val();

    var montant = $("#ajtMontantBL").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 17,

        idFournisseur: fournisseur,

        numeroBl: numero,

        dateBl: date,

        montantBl: montant,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[4] +
            "</td><td></td><td>En cours ...</td></tr>";

          $("table.tabBl").prepend(ligne);

          $("#ajtFournisseurBL").val("");

          $("#ajtNumeroBL").val("");

          $("#ajtDateBL").val("");

          $("#ajtMontantBL").val("");

          $("#ajoutBl").modal("hide");

          window.location.href = "bl.php?id=" + tab[5];
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter BL Pharmacie**/

/**Debut modifier BL Pharmacie**/

function mdf_Bl(idBl, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 21,

      idBl: idBl,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idBL_Mdf").val(tab[1]);

        $("#ordreBL_Mdf").val(ordre);

        $("#fournisseurBL_Mdf").val(tab[2]);

        $("#numeroBL_Mdf").val(tab[3]);

        $("#dateBL_Mdf").val(tab[4]);

        $("#montantBL_Mdf").val(tab[5]);

        $("#modifierBl").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Bl").click(function () {
    var idBl = $("#idBL_Mdf").val();

    var ordre = $("#ordreBL_Mdf").val();

    var fournisseur = $("#fournisseurBL_Mdf").val();

    var numero = $("#numeroBL_Mdf").val();

    var date = $("#dateBL_Mdf").val();

    var montant = $("#montantBL_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 22,

        idBl: idBl,

        fournisseur: fournisseur,

        numeroBl: numero,

        dateBl: date,

        montantBl: montant,
      },

      success: function (data) {
        $("#idBL_Mdf").val("");

        $("#ordreBL_Mdf").val("");

        $("#fournisseurBL_Mdf").val("");

        $("#numeroBL_Mdf").val("");

        $("#dateBL_Mdf").val("");

        $("#montantBL_Mdf").val("");

        $("#modifierBl").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableBl tr").each(function (row, tr) {
            bl = $(tr).find("td:eq(0)").text();

            if (bl != "" && bl == ordre) {
              $(tr).find("td:eq(1)").html(tab[2]);

              $(tr).find("td:eq(2)").html(tab[3]);

              $(tr).find("td:eq(3)").html(tab[4]);

              $(tr).find("td:eq(4)").html(tab[5]);

              $(tr).find("td:eq(5)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier BL Pharmacie**/

/**Debut supprimer BL Pharmacie**/

function spm_Bl(idBl, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 21,

      idBl: idBl,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idBL_Spm").val(tab[1]);

        $("#ordreBL_Spm").val(ordre);

        $("#fournisseurBL_Spm").val(tab[2]);

        $("#numeroBL_Spm").val(tab[3]);

        $("#dateBL_Spm").val(tab[4]);

        $("#montantBL_Spm").val(tab[5]);

        $("#supprimerBl").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Bl").click(function () {
    var idBl = $("#idBL_Spm").val();

    var ordre = $("#ordreBL_Spm").val();

    var fournisseur = $("#fournisseurBL_Spm").val();

    var numero = $("#numeroBL_Spm").val();

    var date = $("#dateBL_Spm").val();

    var montant = $("#montantBL_Spm").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 23,

        idBl: idBl,
      },

      success: function (data) {
        $("#idBL_Spm").val("");

        $("#ordreBL_Spm").val("");

        $("#fournisseurBL_Spm").val("");

        $("#numeroBL_Spm").val("");

        $("#dateBL_Spm").val("");

        $("#montantBL_Spm").val("");

        $("#supprimerBl").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableBl tr").each(function (row, tr) {
            bl = $(tr).find("td:eq(0)").text();

            if (bl != "" && bl == ordre) {
              $(tr).find("td:eq(1)").html(numero);

              $(tr).find("td:eq(2)").html(fournisseur);

              $(tr).find("td:eq(3)").html(date);

              $(tr).find("td:eq(4)").html(montant);

              $(tr).find("td:eq(5)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer BL Pharmacie**/

/**Debut ajouter Entrepot**/

$(function () {
  $("#btn_ajt_Entrepot").click(function () {
    var nomEntrepot = $("#ajtNomEntrepot").val();

    var adresseEntrepot = $("#ajtAdresseEntrepot").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 24,

        nomEntrepot: nomEntrepot,

        adresseEntrepot: adresseEntrepot,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>En cours ...</td></tr>";

          $("table.tabEntrepot").prepend(ligne);

          $("#ajtNomEntrepot").val("");

          $("#ajtAdresseEntrepot").val("");

          $("#ajoutEntrepot").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter Entrepot**/

/**Debut modifier Entrepot**/

function mdf_Entrepot(idEntrepot, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 25,

      idEntrepot: idEntrepot,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idEntrepot_Mdf").val(tab[1]);

        $("#ordreEntrepot_Mdf").val(ordre);

        $("#nomEntrepot_Mdf").val(tab[2]);

        $("#adresseEntrepot_Mdf").val(tab[3]);

        $("#modifierEntrepot").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Entrepot").click(function () {
    var idEntrepot = $("#idEntrepot_Mdf").val();

    var ordre = $("#ordreEntrepot_Mdf").val();

    var nomEntrepot = $("#nomEntrepot_Mdf").val();

    var adresseEntrepot = $("#adresseEntrepot_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 26,

        idEntrepot: idEntrepot,

        nomEntrepot: nomEntrepot,

        adresseEntrepot: adresseEntrepot,
      },

      success: function (data) {
        $("#idEntrepot_Mdf").val("");

        $("#ordreEntrepot_Mdf").val("");

        $("#nomEntrepot_Mdf").val("");

        $("#adresseEntrepot_Mdf").val("");

        $("#modifierEntrepot").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableEntrepot tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[2]);

              $(tr).find("td:eq(2)").html(tab[3]);

              $(tr).find("td:eq(3)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Entrepot**/

/**Debut supprimer Entrepot**/

function spm_Entrepot(idEntrepot, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 25,

      idEntrepot: idEntrepot,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idEntrepot_Spm").val(tab[1]);

        $("#ordreEntrepot_Spm").val(ordre);

        $("#nomEntrepot_Spm").val(tab[2]);

        $("#adresseEntrepot_Spm").val(tab[3]);

        $("#supprimerEntrepot").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Entrepot").click(function () {
    var idEntrepot = $("#idEntrepot_Spm").val();

    var ordre = $("#ordreEntrepot_Spm").val();

    var nomEntrepot = $("#nomEntrepot_Spm").val();

    var adresseEntrepot = $("#adresseEntrepot_Spm").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 27,

        idEntrepot: idEntrepot,
      },

      success: function (data) {
        $("#idEntrepot_Spm").val("");

        $("#ordreEntrepot_Spm").val("");

        $("#nomEntrepot_Spm").val("");

        $("#adresseEntrepot_Spm").val("");

        $("#supprimerEntrepot").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableEntrepot tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(nomEntrepot);

              $(tr).find("td:eq(2)").html(adresseEntrepot);

              $(tr).find("td:eq(3)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Entrepot**/

/**Debut validation 1 Transfert Entrepot **/

function validerTransfert_1(idEntrepotTransfert, designation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 89,

      idEntrepotTransfert: idEntrepotTransfert,

      idDesignation: designation,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#btn_ValiderTransfert-" + idEntrepotTransfert).prop(
          "disabled",
          true
        );
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin validation 1 Transfert Entrepot **/

/**Debut validation 2 Transfert Entrepot **/

function validerTransfert_2(idEntrepotTransfert, designation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 90,

      idEntrepotTransfert: idEntrepotTransfert,

      idDesignation: designation,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#btn_ValiderTransfert-" + idEntrepotTransfert).prop(
          "disabled",
          true
        );
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin validation 2 Transfert Entrepot **/

/**Debut ajouter Transfert Entrepot **/

function ajt_TransfertStock(idEntrepotTransfert, idEntrepotStock) {
  var quantite = $("#quantiteTransfert-" + idEntrepotStock).val();

  $("#btn_TransfertProduit-" + idEntrepotStock).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 82,

      idEntrepotTransfert: idEntrepotTransfert,

      idEntrepotStock: idEntrepotStock,

      quantite: quantite,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#produitTtl").text(tab[2]);

        $("#produitRst").text(tab[1] - tab[2]);

        $("#produitTtl_2").text(tab[2]);

        $("#produitRst_2").text(tab[1] - tab[2]);

        $("#quantiteTransfert-" + idEntrepotStock).val(quantite);
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Transfert Entrepot **/

/**Debut ajouter Stock Entrepot**/

function ajt_StockDepot_ET(idStock) {
  $("#ajtStock").val(idStock);

  $("#ajoutStockModal").modal("show");
}

$(function () {
  $("#btn_ajt_StockEntrepot").click(function () {
    var idStock = $("#ajtStock").val();

    var idEntrepot = $("#ajtEntrepot").val();

    var quantite = $("#ajtQuantite").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 28,

        idStock: idStock,

        idEntrepot: idEntrepot,

        quantite: quantite,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          location.reload();
        } else {
          $("#ajoutStockModal").modal("hide");

          $("#msg_info_jsET").modal("show");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter Stock Entrepot**/

/**Debut transfert Stock Entrepot**/

function transfertDepot_ET(idEntrepotStock) {
  $("#transfertStock").val(idEntrepotStock);

  $("#transfertStockModal").modal("show");
}

$(function () {
  $("#btn_tft_StockEntrepot").click(function () {
    var idEntrepotStock = $("#transfertStock").val();

    var idEntrepot = $("#tftEntrepot").val();

    var quantite = $("#tftQuantite").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 38,

        idEntrepotStock: idEntrepotStock,

        idEntrepot: idEntrepot,

        quantite: quantite,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          location.reload();
        } else if (tab[0] == 3) {
          $("#ajoutStockModal").modal("hide");

          $("#msg_info_jsDP").modal("show");
        } else {
          $("#ajoutStockModal").modal("hide");

          $("#msg_info_jsET").modal("show");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin transfert Stock Entrepot**/

/**Debut ajouter Transfert Entrepot**/

function ajt_Transfert(designation) {
  var entrepot = $("#entrepot-" + designation).val();

  var quantite = $("#quantiteAStocke-" + designation).val();

  $("#btn_AjtTransfert-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 78,

      idDesignation: designation,

      idEntrepot: entrepot,

      quantite: quantite,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr><td>0</td><td>" +
          tab[1] +
          "</td><td>" +
          tab[2] +
          "</td><td>" +
          quantite +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          tab[4] +
          "</td><td>En Cours</td><td></td><td>" +
          tab[5] +
          "</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#uniteStock-" + designation).prop("disabled", true);

        $("#prixuniteStock-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else {
        alert(data);

        //alert("Erreur");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Transfert Entrepot**/

/**Debut ajouter Transfert Entrepot**/

function ajt_TransfertEntrepot(designation) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  $("#btn_AjtTransfert-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 78,

      idDesignation: designation,

      quantite: quantite,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr><td>0</td><td>" +
          tab[1] +
          "</td><td>" +
          tab[2] +
          "</td><td>" +
          quantite +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          tab[4] +
          "</td><td>En Cours</td><td></td><td>" +
          tab[5] +
          "</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#uniteStock-" + designation).prop("disabled", true);

        $("#prixuniteStock-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else {
        alert(data);

        //alert("Erreur");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Transfert Entrepot**/

/**Debut modifier Transfert Entrepot**/

function mdf_Transfert(idEntrepotTransfert, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 79,

      idEntrepotTransfert: idEntrepotTransfert,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idEntrepotTransfert_Mdf").val(idEntrepotTransfert);

        $("#ordreEntrepotTransfert_Mdf").val(ordre);

        $("#designation_Mdf").val(tab[1]);

        $("#uniteStock_Mdf").val(tab[2]);

        $("#quantite_Mdf").val(tab[3]);

        $("#modifierEntrepotTransfert").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Transfert").click(function () {
    var idEntrepotTransfert = $("#idEntrepotTransfert_Mdf").val();

    var ordre = $("#ordreEntrepotTransfert_Mdf").val();

    var quantite = $("#quantite_Mdf").val();

    $("#modifierEntrepotTransfert").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 80,

        idEntrepotTransfert: idEntrepotTransfert,

        quantite: quantite,
      },

      success: function (data) {
        $("#idEntrepotTransfert_Mdf").val("");

        $("#ordreEntrepotTransfert_Mdf").val("");

        $("#designation_Mdf").val("");

        $("#quantite_Mdf").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableTransfert tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(quantite);

              $(tr).find("td:eq(3)").html(quantite);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(" ");

              $(tr).find("td:eq(7)").html(" ");

              $(tr).find("td:eq(8)").html(tab[5]);
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Transfert Entrepot**/

/**Debut supprimer Transfert Entrepot**/

function spm_Transfert(idEntrepotTransfert, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 79,

      idEntrepotTransfert: idEntrepotTransfert,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idEntrepotTransfert_Spm").val(idEntrepotTransfert);

        $("#ordreEntrepotTransfert_Spm").val(ordre);

        $("#designation_Spm").val(tab[1]);

        $("#uniteStock_Spm").val(tab[2]);

        $("#quantite_Spm").val(tab[3]);

        $("#supprimerEntrepotTransfert").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Transfert").click(function () {
    var idEntrepotTransfert = $("#idEntrepotTransfert_Spm").val();

    var ordre = $("#ordreEntrepotTransfert_Spm").val();

    var quantite = $("#quantite_Spm").val();

    $("#supprimerEntrepotTransfert").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 81,

        idEntrepotTransfert: idEntrepotTransfert,
      },

      success: function (data) {
        $("#idEntrepotTransfert_Spm").val("");

        $("#ordreEntrepotTransfert_Spm").val("");

        $("#designation_Spm").val("");

        $("#quantite_Spm").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableTransfert tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(quantite);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(" ");

              $(tr).find("td:eq(7)").html(" ");

              $(tr).find("td:eq(8)").html(tab[5]);
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Transfert Entrepot**/

/**Debut modifier Stock Entrepot**/

function mdf_StockEntrepot(idEntrepotStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 29,

      idEntrepotStock: idEntrepotStock,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idStockEntrepot_Mdf").val(tab[1]);

        $("#ordreStockEntrepot_Mdf").val(ordre);

        $("#idEntrepot_Mdf").val(tab[2]);

        $("#quantite_Mdf").val(tab[3]);

        $("#modifierStockEntrepot").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_StockEntrepot").click(function () {
    var idEntrepotStock = $("#idStockEntrepot_Mdf").val();

    var ordre = $("#ordreStockEntrepot_Mdf").val();

    var idEntrepot = $("#idEntrepot_Mdf").val();

    var quantite = $("#quantite_Mdf").val();

    $("#modifierStockEntrepot").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 31,

        idEntrepot: idEntrepot,

        idEntrepotStock: idEntrepotStock,

        quantite: quantite,
      },

      success: function (data) {
        $("#idStockEntrepot_Mdf").val("");

        $("#ordreStockEntrepot_Mdf").val("");

        $("#idEntrepot_Mdf").val("");

        $("#quantite_Mdf").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrepot tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(quantite);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[7]);

              $(tr).find("td:eq(8)").html(tab[6]);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Stock Entrepot**/

/**Debut supprimer Stock Entrepot**/

function spm_StockEntrepot(idEntrepotStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 29,

      idEntrepotStock: idEntrepotStock,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idStockEntrepot_Spm").val(tab[1]);

        $("#ordreStockEntrepot_Spm").val(ordre);

        $("#idEntrepot_Spm").val(tab[4]);

        $("#quantite_Spm").val(tab[3]);

        $("#supprimerStockEntrepot").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_StockEntrepot").click(function () {
    var idEntrepotStock = $("#idStockEntrepot_Spm").val();

    var ordre = $("#ordreStockEntrepot_Spm").val();

    var idEntrepot = $("#idEntrepot_Spm").val();

    $("#supprimerStockEntrepot").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 32,

        idEntrepotStock: idEntrepotStock,
      },

      success: function (data) {
        $("#idStockEntrepot_Spm").val("");

        $("#ordreStockEntrepot_Spm").val("");

        $("#idEntrepot_Spm").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrepot tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[8]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[7]);

              $(tr).find("td:eq(8)").html(tab[6]);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Stock Entrepot**/

/**Debut modifier Stock Entrepot**/

function mdf_Stock_ET(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 34,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idStock_Mdf").val(tab[1]);

        $("#ordre_Mdf").val(ordre);

        $("#designation_Mdf").val(tab[2]);

        $("#qteInitiale_Mdf").val(tab[3]);

        $("#dateStockage_Mdf").val(tab[4]);

        $("#dateExpiration_Mdf").val(tab[5]);

        $("#modifierStockModal").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Stock_ET").click(function () {
    var idStock = $("#idStock_Mdf").val();

    var ordre = $("#ordre_Mdf").val();

    var quantite = $("#qteInitiale_Mdf").val();

    var prixus_Mdf = $("#prixus_Mdf").val();

    var dateExpiration = $("#dateExpiration_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 35,

        idStock: idStock,

        quantite: quantite,

        prixus: prixus_Mdf,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        // alert(data)
        $("#idStock_Mdf").val("");

        $("#ordre_Mdf").val("");

        $("#designation_Mdf").val("");

        $("#qteInitiale_Mdf").val("");

        $("#dateStockage_Mdf").val("");

        $("#dateExpiration_Mdf").val("");

        $("#modifierStockModal").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[5]);

              $(tr).find("td:eq(2)").html(tab[1]);

              $(tr).find("td:eq(3)").html(tab[2]);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Stock Entrepot**/

/**Debut supprimer Stock Entrepot**/

function spm_Stock_ET(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 34,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idStock_Spm").val(tab[1]);

        $("#ordre_Spm").val(ordre);

        $("#designation_Spm").val(tab[2]);

        $("#qteInitial_Spm").val(tab[3]);

        $("#dateStockage_Spm").val(tab[4]);

        $("#dateExpiration_Spm").val(tab[5]);

        $("#supprimerStockModal").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Stock_ET").click(function () {
    var idStock = $("#idStock_Spm").val();

    var ordre = $("#ordre_Spm").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 36,

        idStock: idStock,
      },

      success: function (data) {
        $("#idStock_Spm").val("");

        $("#ordre_Spm").val("");

        $("#designation_Spm").val("");

        $("#qteInitiale_Spm").val("");

        $("#dateStockage_Spm").val("");

        $("#dateExpiration_Spm").val("");

        $("#supprimerStockModal").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[5]);

              $(tr).find("td:eq(2)").html(tab[1]);

              $(tr).find("td:eq(3)").html(tab[2]);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Stock Entrepot**/

/**Debut Entrepot Inventaire**/

// function inventaireDepot(designation) {

//     var quantite = $('#quantite-' + designation).val();

//     if (quantite != '' && quantite != null) {

//         $('#quantite-' + designation).prop('disabled', true);

//         $('#btn_InvStock-' + designation).prop('disabled', true);

//         $.ajax({

//             url: "ajax/ajouterLigneAjax.php",

//             method: "POST",

//             data: {

//                 operation: 51,

//                 idDesignation: designation,

//                 quantite: quantite,

//             },

//             success: function(data) {},

//             error: function() {

//                 alert("La requête ");

//             },

//             dataType: "text"

//         });

//     } else {

//         alert('Le Champ est vide');

//     }

// }

function inventairePermanentDepot(designation) {
  var quantite = $("#quantite-" + designation).val();

  if (quantite != "" && quantite != null) {
    $("#quantite-" + designation).prop("disabled", true);

    $("#btn_InvStock-" + designation).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 98,
        // operation: 51,

        idDesignation: designation,

        quantite: quantite,
      },

      success: function (data) {
        // alert(data)
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    alert("Le Champ est vide");
  }
}

function inventaireIntermittentDepot(designation) {
  var quantite = $("#quantite-" + designation).val();

  if (quantite != "" && quantite != null) {
    $("#quantite-" + designation).prop("disabled", true);

    $("#btn_InvStock-" + designation).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 98,
        // operation: 99,
        // operation: 51,

        idDesignation: designation,

        quantite: quantite,
      },

      success: function (data) {
        // alert(data)
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    alert("Le Champ est vide");
  }
}

function inventaireIntermittentB(designation) {
  // var quantite = $('#quantite-' + designation).val();
  var pus = $("#pus-" + designation).val();
  var pu = $("#pu-" + designation).val();
  var pa = $("#pa-" + designation).val();
  var qtyRayonInv = $("#qtyRayon-" + designation).val();
  var qtyDepot = $("#qtyDepot-" + designation).val();

  var actuQte = $("#actuQte_" + designation).text();

  if (
    qtyDepot != "" &&
    qtyDepot != null &&
    qtyRayonInv != "" &&
    qtyRayonInv != null
  ) {
    confirm("Renseigez une seule quantité");
  } else if (
    (qtyDepot != "" && qtyDepot != null) ||
    (qtyRayonInv != "" && qtyRayonInv != null)
  ) {
    $("#pus-" + designation).prop("disabled", true);
    $("#pu-" + designation).prop("disabled", true);
    $("#pa-" + designation).prop("disabled", true);
    $("#qtyRayon-" + designation).prop("disabled", true);
    $("#qtyDepot-" + designation).prop("disabled", true);

    $("#btn_InvStock-" + designation).prop("disabled", true);

    if (qtyRayonInv == "" || qtyRayonInv == null) {
      qtyRayonInv = 0;
    }

    if (qtyDepot == "" || qtyDepot == null) {
      qtyDepot = 0;
    }

    $.ajax({
      url: "ajax/operationLigneAjax.php",

      method: "POST",

      data: {
        operation: 8,
        // operation: 99,
        // operation: 51,

        idDesignation: designation,

        qtyDepot: qtyDepot,

        qtyRayonInv: qtyRayonInv,

        prixUniteStock: pus,

        prixUnitaire: pu,

        prixAchat: pa,
      },

      success: function (data) {
        // alert(data)

        $("#pus-" + designation).prop("disabled", false);
        $("#pu-" + designation).prop("disabled", false);
        $("#pa-" + designation).prop("disabled", false);
        $("#qtyRayon-" + designation).prop("disabled", false);
        $("#qtyDepot-" + designation).prop("disabled", false);

        $("#btn_InvStock-" + designation).prop("disabled", false);

        $("#qtyRayon-" + designation).val("");
        $("#qtyDepot-" + designation).val("");

        $("#actuQte_" + designation).text(
          parseFloat(actuQte) + parseFloat(qtyDepot) + parseFloat(qtyRayonInv)
        );
        $("#actuQte_" + designation).attr("class", "alert alert-success");
        $("#actuQte_" + designation).fadeOut(400, function () {
          $("#actuQte_" + designation).fadeIn(400);
        });
      },

      error: function () {
        confirm("Une erreur est survenue. Rééssayer svp.");
      },

      dataType: "text",
    });
  } else {
    confirm("Champ vide rencontré.");
  }
}

/**Fin Entrepot Inventaire**/

/**Debut Entrepot Inventaire Pharmacie**/

function inventaireDepot_PH(designation) {
  var quantite = $("#quantite-" + designation).val();

  if (quantite != "" && quantite != null) {
    $("#quantite-" + designation).prop("disabled", true);

    $("#btn_InvStock-" + designation).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 52,

        idDesignation: designation,

        quantite: quantite,
      },

      success: function (data) {},

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    alert("Le Champ est vide");
  }
}

/**Fin Entrepot Inventaire Pharmacie**/

/**Debut Entrepot Inventaire Entrepot**/

function inventaireDepot_ET(depot, designation) {
  var quantite = $("#quantite-" + designation).val();

  if (quantite != "" && quantite != null) {
    $("#quantite-" + designation).prop("disabled", true);

    $("#btn_InvStock-" + designation).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 39,

        idEntrepot: depot,

        idDesignation: designation,

        quantite: quantite,
      },

      success: function (data) {},

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    alert("Le Champ est vide");
  }
}
/**Fin Entrepot Inventaire Entrepot**/
/**Debut Entrepot Inventaire Intermittent**/
function inventaireDepot_ET_I(depot, designation) {
  var quantite = $("#quantite-" + designation).val();
  var pus = $("#pus-" + designation).val();
  var pu = $("#pu-" + designation).val();
  var pa = $("#pa-" + designation).val();

  actuQte = $("#actuQte_" + designation).text();
  // alert(actuQte)

  if (quantite != "" && quantite != null) {
    $("#quantite-" + designation).prop("disabled", true);
    $("#pus-" + designation).prop("disabled", true);
    $("#pu-" + designation).prop("disabled", true);
    $("#pa-" + designation).prop("disabled", true);

    $("#btn_InvStock-" + designation).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 104,
        // operation: 39,

        idEntrepot: depot,

        idDesignation: designation,

        quantite: quantite,

        prixUniteStock: pus,

        prixUnitaire: pu,

        prixAchat: pa,
      },

      success: function (data) {
        $("#quantite-" + designation).prop("disabled", false);
        $("#pus-" + designation).prop("disabled", false);
        $("#pu-" + designation).prop("disabled", false);
        $("#pa-" + designation).prop("disabled", false);

        $("#btn_InvStock-" + designation).prop("disabled", false);

        $("#quantite-" + designation).val("");
        $("#actuQte_" + designation).text(
          parseFloat(actuQte) + parseFloat(quantite)
        );
        $("#actuQte_" + designation).attr("class", "alert alert-success");
        $("#actuQte_" + designation).fadeOut(400, function () {
          $("#actuQte_" + designation).fadeIn(400);
          // $(this).html(data).fadeIn(400).parent().css("background-color", "green");
          // setTimeout(() => {
          //     $(this).parent().css("background-color", "");
          // }, 1000);
        });
        // setTimeout(() => {
        //     $("#actuQte_"+designation).removeAttr('class')
        // }, 1500);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    alert("Le Champ est vide");
  }
}
/**Fin Entrepot Inventaire Intermittent**/
/**Debut Entrepot Inventaire Partiel**/
function inventairePartielDepot_ET_I(depot, designation) {
  var quantite = $("#quantite-" + designation).val();
  var pus = $("#pus-" + designation).val();
  var pu = $("#pu-" + designation).val();
  var pa = $("#pa-" + designation).val();

  actuQte = $("#actuQte_" + designation).text();

  if (quantite != "" && quantite != null) {
    $("#quantite-" + designation).prop("disabled", true);
    $("#pus-" + designation).prop("disabled", true);
    $("#pu-" + designation).prop("disabled", true);
    $("#pa-" + designation).prop("disabled", true);

    $("#btn_InvPStock-" + designation).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 105,

        idEntrepot: depot,

        idDesignation: designation,

        quantite: quantite,

        prixUniteStock: pus,

        prixUnitaire: pu,

        prixAchat: pa,
      },

      success: function (data) {
        $("#quantite-" + designation).prop("disabled", false);
        $("#pus-" + designation).prop("disabled", false);
        $("#pu-" + designation).prop("disabled", false);
        $("#pa-" + designation).prop("disabled", false);

        $("#btn_InvPStock-" + designation).prop("disabled", false);

        $("#quantite-" + designation).val("");
        $("#actuQte_" + designation).text(parseFloat(quantite));
        $("#actuQte_" + designation).attr("class", "alert alert-success");
        $("#actuQte_" + designation).fadeOut(400, function () {
          $("#actuQte_" + designation).fadeIn(400);
        });
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    alert("Le Champ est vide");
  }
}
/**Fin Entrepot Inventaire Partiel**/

/**Debut ajouter Stock**/

function ajt_Stock_Divers(designation) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var prixAchat = $("#prixAchat-" + designation).val();

  var prixUniteStock = $("#prixUniteStock-" + designation).val();

  $("#btn_AjtStock-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 91,

      idDesignation: designation,

      quantite: quantite,

      prixUniteStock: prixUniteStock,

      prixAchat: prixAchat,
    },

    success: function (data) {
      tab = data.split("<>");

      var ligne =
        "<tr><td>0</td><td>" +
        tab[0] +
        "</td><td>" +
        tab[1] +
        "</td><td>" +
        quantite +
        "</td><td>Article</td><td>" +
        tab[3] +
        "</td><td>" +
        tab[4] +
        "</td><td>" +
        tab[5] +
        "</td><td>" +
        tab[6] +
        "</td><td>En cours ...</td></tr>";

      $("table.tabStock").prepend(ligne);

      $("#quantiteAStocke-" + designation).prop("disabled", true);

      $("#prixAchat-" + designation).prop("disabled", true);

      $("#prixUniteStock-" + designation).prop("disabled", true);

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Stock**/

/**Debut ajouter Stock**/
function ajt_Stock(designation) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var uniteStock = $("#uniteStock-" + designation).val();

  var prixUniteStock = $("#prixUniteStock-" + designation).val();

  var prixUnitaire = $("#prixUnitaire-" + designation).val();

  var prixAchat = $("#prixAchat-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  $("#btn_AjtStock-" + designation).prop("disabled", true);

  $("#idDesignation_Cfm").val();
  $("#idBl_Cfm").val();
  $("#designation_Cfm").val();
  $("#codeBarre_Cfm").val();
  $("#quantite_Cfm").val();
  $("#uniteStock_Cfm").val();
  $("#dateExpiration_Cfm").val();

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 43,

      idDesignation: designation,

      quantite: quantite,

      uniteStock: uniteStock,

      prixUniteStock: prixUniteStock,

      prixUnitaire: prixUnitaire,

      prixAchat: prixAchat,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr><td>0</td><td>" +
          tab[1] +
          "</td><td>" +
          tab[2] +
          "</td><td>" +
          quantite +
          "</td><td>" +
          uniteStock +
          "</td><td>" +
          tab[4] +
          "</td><td>" +
          tab[5] +
          "</td><td>" +
          tab[6] +
          "</td><td>" +
          tab[7] +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#uniteStock-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else if (tab[0] == 2) {
        $("#msg_confirm_Stock").modal("show");
        $("#idDesignation_Cfm").val(tab[1]);
        $("#designation_Cfm").val(tab[2]);
        $("#codeBarre_Cfm").val(tab[3]);
        $("#quantite_Cfm").val(tab[4]);
        $("#uniteStock_Cfm option:selected").text(tab[5]);
        $("#dateExpiration_Cfm").val(tab[6]);
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}
/**Fin ajouter Stock**/

/**Debut confirmer ajout Stock**/
function ajt_Stock_Cfm() {
  var designation = $("#idDesignation_Cfm").val();
  var quantite = $("#quantite_Cfm").val();
  var uniteStock = $("#uniteStock_Cfm").val();
  var dateExpiration = $("#dateExpiration_Cfm").val();
  $("#btn_AjtStock_Cfm").prop("disabled", true);
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 43,

      confirm: 1,

      idDesignation: designation,

      quantite: quantite,

      uniteStock: uniteStock,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr><td>0</td><td>" +
          tab[0] +
          "</td><td>" +
          tab[1] +
          "</td><td>" +
          quantite +
          "</td><td>" +
          uniteStock +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          tab[4] +
          "</td><td>" +
          tab[5] +
          "</td><td>" +
          tab[6] +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#msg_confirm_Stock").modal("hide");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}
/**Fin confirmer ajout Stock**/

/**Debut ajouter Stock Forcer**/

function ajt_Stock_Forcer(designation) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var uniteStock = $("#uniteStock-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  $("#btn_AjtStock-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 95,

      idDesignation: designation,

      quantite: quantite,

      uniteStock: uniteStock,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      tab = data.split("<>");

      var ligne =
        "<tr><td>0</td><td>" +
        tab[0] +
        "</td><td>" +
        tab[1] +
        "</td><td>" +
        quantite +
        "</td><td>" +
        uniteStock +
        "</td><td>" +
        tab[3] +
        "</td><td>" +
        tab[4] +
        "</td><td>" +
        tab[5] +
        "</td><td>" +
        tab[6] +
        "</td><td>En cours ...</td></tr>";

      $("table.tabStock").prepend(ligne);

      $("#quantiteAStocke-" + designation).prop("disabled", true);

      $("#uniteStock-" + designation).prop("disabled", true);

      $("#dateExpiration-" + designation).prop("disabled", true);

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Stock Forcer**/

/**Debut modifier Stock**/

function mdf_Stock(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 44,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Mdf").val(idStock);

      $("#ordre_Mdf").val(ordre);

      $("#dateStockage_Mdf").val(tab[5]);

      $("#uniteStock_Mdf").val(tab[3]);

      $("#qteInitiale_Mdf").val(tab[1]);

      $("#qteReste_Mdf").val(tab[2]);

      $("#dateExpiration_Mdf").val(tab[4]);

      $("#modifierStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Stock").click(function () {
    var idStock = $("#idStock_Mdf").val();

    var ordre = $("#ordre_Mdf").val();

    var dateExpiration = $("#dateExpiration_Mdf").val();

    var quantite = $("#qteReste_Mdf").val();

    $("#modifierStockModal").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 45,

        idStock: idStock,

        quantite: quantite,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        $("#idStock_Mdf").val("");

        $("#ordre_Mdf").val("");

        $("#dateExpiration_Mdf").val("");

        $("#qteReste_Mdf").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[4]);

              $(tr).find("td:eq(5)").html(tab[5]);

              $(tr).find("td:eq(6)").html(tab[6]);

              $(tr).find("td:eq(7)").html(tab[7]);

              $(tr).find("td:eq(8)").html(tab[8]);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Stock**/

/**Debut supprimer Stock**/

function spm_Stock(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 44,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Spm").val(idStock);

      $("#ordre_Spm").val(ordre);

      $("#dateStockage_Spm").val(tab[5]);

      $("#uniteStock_Spm").val(tab[3]);

      $("#qteInitiale_Spm").val(tab[1]);

      $("#qteReste_Spm").val(tab[2]);

      $("#dateExpiration_Spm").val(tab[4]);

      $("#supprimerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Stock").click(function () {
    var idStock = $("#idStock_Spm").val();

    var ordre = $("#ordre_Spm").val();

    $("#supprimerStockModal").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 46,

        idStock: idStock,
      },

      success: function (data) {
        $("#idStock_Spm").val("");

        $("#ordre_Spm").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[4]);

              $(tr).find("td:eq(5)").html(tab[5]);

              $(tr).find("td:eq(6)").html(tab[6]);

              $(tr).find("td:eq(7)").html(tab[7]);

              $(tr).find("td:eq(8)").html(tab[8]);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Stock**/

function modif_UniteStock_Bl(produit) {
  tab = produit.split("§");

  var uniteStock = tab[0];

  var designation = tab[1];

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 88,

      uniteStock: uniteStock,

      idDesignation: designation,
    },

    success: function (data) {
      tab1 = data.split("<>");

      if (tab1[0] == 1) {
        $("#prixUniteStock-" + designation).val(tab1[1]);
      }

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Debut ajouter Stock BL**/

function ajt_Stock_Bl(designation, idBl) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var uniteStockConcat = $("#uniteStock-" + designation).val();

  tab = uniteStockConcat.split("§");

  var uniteStock = tab[0];

  var prixUniteStock = $("#prixUniteStock-" + designation).val();

  var prixAchat = $("#prixAchat-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  $("#btn_AjtStock_P-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 84,

      idDesignation: designation,

      idBl: idBl,

      quantite: quantite,

      uniteStock: uniteStock,

      prixUniteStock: prixUniteStock,

      prixAchat: prixAchat,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr><td>0</td><td>" +
          tab[1] +
          "</td><td>" +
          tab[2] +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          tab[4] +
          "</td><td>" +
          tab[5] +
          "</td><td>" +
          tab[6] +
          "</td><td></td><td>" +
          dateExpiration +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#stock").css({ "background-color": "green" });

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else {
        $("#msg_info_js").modal("show");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Stock BL**/

/**Debut modifier Stock BL**/

function mdf_Stock_Bl(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 85,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idStock_Mdf").val(idStock);

        $("#ordre_Mdf").val(ordre);

        $("#designation_Mdf").val(tab[1]);

        $("#uniteStock_Mdf").val(tab[2]);

        $("#qteInitiale_Mdf").val(tab[3]);

        $("#prixuniteStock_Mdf").val(tab[4]);

        $("#prixAchat_Mdf").val(tab[5]);

        $("#dateExpiration_Mdf").val(tab[6]);

        $("#modifierStockModal").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Stock_Bl").click(function () {
    var idStock = $("#idStock_Mdf").val();

    var ordre = $("#ordre_Mdf").val();

    var quantite = $("#qteInitiale_Mdf").val();

    var prixUniteStock = $("#prixuniteStock_Mdf").val();

    var prixAchat = $("#prixAchat_Mdf").val();

    var dateExpiration = $("#dateExpiration_Mdf").val();

    $("#modifierStockModal").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 86,

        idStock: idStock,

        quantite: quantite,

        prixUniteStock: prixUniteStock,

        prixAchat: prixAchat,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        $("#idStock_Mdf").val("");

        $("#ordre_Mdf").val("");

        $("#dateExpiration_Mdf").val("");

        $("#qteInitiale_Mdf").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStock tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[6]);

              $(tr).find("td:eq(8)").html(tab[7]);

              $(tr).find("td:eq(9)").html(tab[8]);

              $(tr).find("td:eq(10)").html("En cours ...");
            }
          });
        }

        if (tab[0] == 2) {
          $("#tableStock tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[6]);

              $(tr).find("td:eq(8)").html(tab[7]);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Stock BL**/

/**Debut supprimer Stock BL**/

function spm_Stock_Bl(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 85,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idStock_Spm").val(idStock);

        $("#ordre_Spm").val(ordre);

        $("#designation_Spm").val(tab[1]);

        $("#uniteStock_Spm").val(tab[2]);

        $("#qteInitiale_Spm").val(tab[3]);

        $("#prixuniteStock_Spm").val(tab[4]);

        $("#prixAchat_Spm").val(tab[5]);

        $("#dateExpiration_Spm").val(tab[6]);

        $("#supprimerStockModal").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Stock_Bl").click(function () {
    var idStock = $("#idStock_Spm").val();

    var ordre = $("#ordre_Spm").val();

    $("#supprimerStockModal").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 87,

        idStock: idStock,
      },

      success: function (data) {
        $("#idStock_Spm").val("");

        $("#ordre_Spm").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStock tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[6]);

              $(tr).find("td:eq(8)").html(tab[7]);

              $(tr).find("td:eq(9)").html(tab[8]);

              $(tr).find("td:eq(10)").html("En cours ...");
            }
          });
        }

        if (tab[0] == 2) {
          $("#tableStock tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[6]);

              $(tr).find("td:eq(8)").html(tab[7]);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Stock BL**/

/**Debut transferer Stock BL**/

function trf_Stock_Bl(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 85,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idStock_Trf").val(idStock);

        $("#ordre_Trf").val(ordre);

        $("#designation_Trf").val(tab[1]);

        $("#uniteStock_Trf").val(tab[2]);

        $("#transfererStockModal").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_trf_Stock_Bl").click(function () {
    var idStock = $("#idStock_Trf").val();

    var ordre = $("#ordre_Trf").val();

    var idBl = $("#idBl_Trf").val();

    $("#transfererStockModal").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 97,

        idStock: idStock,

        idBl: idBl,
      },

      success: function (data) {
        $("#idStock_Trf").val("");

        $("#ordre_Trf").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStock tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html("");

              $(tr).find("td:eq(2)").html("");

              $(tr).find("td:eq(3)").html("");

              $(tr).find("td:eq(4)").html("");

              $(tr).find("td:eq(5)").html("");

              $(tr).find("td:eq(6)").html("");

              $(tr).find("td:eq(7)").html("");

              $(tr).find("td:eq(8)").html("");

              $(tr).find("td:eq(9)").html("");

              $(tr).find("td:eq(10)").html("");
            }
          });
        }

        if (tab[0] == 2) {
          $("#tableStock tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[3]);

              $(tr).find("td:eq(5)").html(tab[4]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[6]);

              $(tr).find("td:eq(8)").html(tab[7]);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin transferer Stock BL**/

/**Debut retirer Stock**/

function retirer_Stock(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 75,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Rtr").val(idStock);

      $("#ordre_Rtr").val(ordre);

      $("#dateStockage_Rtr").val(tab[5]);

      $("#uniteStock_Rtr").val(tab[3]);

      $("#qteInitiale_Rtr").val(tab[1]);

      $("#qteReste_Rtr").val(tab[2]);

      $("#dateExpiration_Rtr").val(tab[4]);

      $("#retirerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_retirer_Stock").click(function () {
    var idStock = $("#idStock_Rtr").val();

    var ordre = $("#ordre_Rtr").val();

    var quantite = $("#qteReste_Rtr").val();

    $("#retirerStockModal").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 76,

        idStock: idStock,

        quantite: quantite,
      },

      success: function (data) {
        $("#idStock_Rtr").val("");

        $("#ordre_Rtr").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[4]);

              $(tr).find("td:eq(5)").html(tab[5]);

              $(tr).find("td:eq(6)").html(tab[6]);

              $(tr).find("td:eq(7)").html(tab[7]);

              $(tr).find("td:eq(8)").html(tab[8]);

              $(tr).find("td:eq(9)").html(" ");

              $(tr).find("td:eq(10)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin retirer Stock**/

/**Debut retourner Stock**/

function retourner_Stock(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 75,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Rtn").val(idStock);

      $("#ordre_Rtn").val(ordre);

      $("#dateStockage_Rtn").val(tab[5]);

      $("#uniteStock_Rtn").val(tab[3]);

      $("#qteInitiale_Rtn").val(tab[1]);

      $("#qteReste_Rtn").val(tab[2]);

      $("#dateExpiration_Rtn").val(tab[4]);

      $("#retournerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_retourner_Stock").click(function () {
    var idStock = $("#idStock_Rtn").val();

    var ordre = $("#ordre_Rtn").val();

    var quantite = $("#qteReste_Rtn").val();

    $("#retournerStockModal").modal("hide");

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 77,

        idStock: idStock,

        quantite: quantite,
      },

      success: function (data) {
        $("#idStock_Rtn").val("");

        $("#ordre_Rtn").val("");

        $("#qteReste_Rtn").val("");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[2]);

              $(tr).find("td:eq(3)").html(tab[3]);

              $(tr).find("td:eq(4)").html(tab[4]);

              $(tr).find("td:eq(5)").html(tab[5]);

              $(tr).find("td:eq(6)").html(tab[6]);

              $(tr).find("td:eq(7)").html(tab[7]);

              $(tr).find("td:eq(8)").html(tab[8]);

              $(tr).find("td:eq(9)").html(" ");

              $(tr).find("td:eq(10)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin retourner Stock**/

/**Debut imprimer etiquette Stock**/

function imprimer_Stock(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 75,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Imp").val(idStock);

      $("#ordre_Imp").val(ordre);

      $("#designation_Imp").val(tab[0]);

      $("#uniteStock_Imp").val(tab[3]);

      $("#prixUnitaire_Imp").val(tab[6]);

      $("#dateStockage_Imp").val(tab[5]);

      $("#qte_Imp").val(tab[2]);

      $("#idDesignation_Imp").val(tab[8]);

      $("#imprimerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin imprimer etiquette Stock**/

/**Debut imprimer etiquette prix par groupe **/

// function imprimer_Prix(idStock, ordre) {

//     $.ajax({

//         url: "ajax/ajouterLigneAjax.php",

//         method: "POST",

//         data: {

//             operation: 75,

//             idStock: idStock,

//         },

//         success: function(data) {

//             tab = data.split('<>');

//             $('#idStock_Imp').val(idStock);

//             $('#ordre_Imp').val(ordre);

//             $('#designation_Imp').val(tab[0]);

//             $('#uniteStock_Imp').val(tab[3]);

//             $('#prixUnitaire_Imp').val(tab[6]);

//             $('#dateStockage_Imp').val(tab[5]);

//             $('#qte_Imp').val(tab[2]);

//             $('#imprimerStockModal').modal('show');

//         },

//         error: function() {

//             alert("La requête ");

//         },

//         dataType: "text"

//     });

// }

/**Fin imprimer etiquette prix par groupe **/

/**Debut imprimer CodeBarre Stock**/

function imprimerCodeBarre_Stock(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 75,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStockCB_Imp").val(idStock);

      $("#ordreCB_Imp").val(ordre);

      $("#designationCB_Imp").val(tab[0]);

      $("#uniteStockCB_Imp").val(tab[3]);

      $("#prixUnitaireCB_Imp").val(tab[6]);

      $("#dateStockageCB_Imp").val(tab[5]);

      $("#qteCB_Imp").val(tab[2]);

      $("#codeBarreuniteStockCB_Imp").val(tab[7]);

      $("#imprimerCodeBarreStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin retirer CodeBarre Stock**/

/**Debut imprimer CodeBarre Stock**/

function imprimerCodeBarre_StockEntrepot(idEntrepotStock, ordre) {
  // alert(111111);
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 1000,

      idEntrepotStock: idEntrepotStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStockCB_Imp").val(idEntrepotStock);

      $("#ordreCB_Imp").val(ordre);

      $("#designationCB_Imp").val(tab[0]);

      $("#uniteStockCB_Imp").val(tab[3]);

      $("#prixUnitaireCB_Imp").val(tab[6]);

      $("#dateStockageCB_Imp").val(tab[5]);

      $("#qteCB_Imp").val(tab[2]);

      $("#codeBarreuniteStockCB_Imp").val(tab[7]);

      $("#imprimerCodeBarreStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin retirer CodeBarre Stock**/

/**Debut ajouter Stock Pharmacie**/

function ajt_Stock_P(designation) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var prixSession = $("#prixSession-" + designation).val();

  var prixPublic = $("#prixPublic-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  var idBl = $("#idBl").val();

  $("#btn_AjtStock_P-" + designation).prop("disabled", true);

  $("#idDesignation_Cfm").val();
  $("#idBl_Cfm").val();
  $("#designation_Cfm").val();
  $("#codeBarre_Cfm").val();
  $("#quantite_Cfm").val();
  $("#prixSession_Cfm").val();
  $("#prixPublic_Cfm").val();
  $("#dateExpiration_Cfm").val();

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 3,

      idDesignation: designation,

      idBl: idBl,

      quantite: quantite,

      prixSession: prixSession,

      prixPublic: prixPublic,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr id='stock'><td>0</td><td>" +
          tab[2] +
          "</td><td>" +
          tab[8] +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          tab[4] +
          "</td><td>" +
          tab[5] +
          "</td><td>" +
          tab[6] +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#stock").css({ "background-color": "green" });

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else if (tab[0] == 2) {
        $("#msg_confirm_Stock_P").modal("show");
        $("#idDesignation_Cfm").val(tab[1]);
        $("#designation_Cfm").val(tab[2]);
        $("#codeBarre_Cfm").val(tab[3]);
        $("#quantite_Cfm").val(tab[4]);
        $("#prixSession_Cfm").val(tab[5]);
        $("#prixPublic_Cfm").val(tab[6]);
        $("#dateExpiration_Cfm").val(tab[7]);
        $("#idBl_Cfm").val(tab[8]);
      } else {
        $("#msg_info_js").modal("show");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Stock Pharmacie**/

/**Debut confirmer ajout Stock Pharmacie**/
function ajt_Stock_P_Cfm() {
  var designation = $("#idDesignation_Cfm").val();
  var quantite = $("#quantite_Cfm").val();
  var prixSession = $("#prixSession_Cfm").val();
  var prixPublic = $("#prixPublic_Cfm").val();
  var dateExpiration = $("#dateExpiration_Cfm").val();
  var idBl = $("#idBl_Cfm").val();

  $("#btn_AjtStock_P_Cfm").prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 3,

      confirm: 1,

      idDesignation: designation,

      idBl: idBl,

      quantite: quantite,

      prixSession: prixSession,

      prixPublic: prixPublic,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr id='stock'><td>0</td><td>" +
          tab[2] +
          "</td><td>" +
          tab[8] +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          tab[4] +
          "</td><td>" +
          tab[5] +
          "</td><td>" +
          tab[6] +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#stock").css({ "background-color": "green" });

        $("#msg_confirm_Stock_P").modal("hide");
      } else {
        $("#msg_info_js").modal("show");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}
/**Debut confirmer ajout Stock Pharmacie**/

/**Debut ajouter Stock BL Pharmacie**/

function ajt_Stock_Bl_PH(designation, idBl) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var prixSession = $("#prixSession-" + designation).val();

  var prixPublic = $("#prixPublic-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  $("#btn_AjtStock_P-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 3,

      idDesignation: designation,

      idBl: idBl,

      quantite: quantite,

      prixSession: prixSession,

      prixPublic: prixPublic,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr id='stock'><td>0</td><td>" +
          tab[2] +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          tab[4] +
          "</td><td>" +
          tab[4] +
          "</td><td>" +
          tab[5] +
          "</td><td>" +
          tab[6] +
          "</td><td>" +
          tab[7] +
          "</td><td>" +
          dateExpiration +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#stock").css({ "background-color": "green" });

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else {
        $("#msg_info_js").modal("show");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Stock BL Pharmacie**/

/**Debut initialiser Stock**/

function init_Stock(designation) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var uniteStock = $("#uniteStock-" + designation).val();

  var prix = $("#prixUnitaire-" + designation).val();

  var prixUS = $("#prixuniteStock-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  //alert (designation+' '+quantite+' '+uniteStock+' '+prix+' '+prixUS+' '+dateExpiration);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 2,

      idDesignation: designation,

      quantite: quantite,

      uniteStock: uniteStock,

      prixUnitaire: prix,

      prixUniteStock: prixUS,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      $("#quantiteAStocke-" + designation).prop("disabled", true);

      $("#uniteStock-" + designation).prop("disabled", true);

      $("#prixUnitaire-" + designation).prop("disabled", true);

      $("#prixuniteStock-" + designation).prop("disabled", true);

      $("#dateExpiration-" + designation).prop("disabled", true);

      $("#btn_InitStock-" + designation).prop("disabled", true);

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin initialiser Stock**/

/**Debut ajouter Stock**/

$(function () {
  $("#btn_ajt_StockCatalogue").click(function () {
    var designation = $("#designation_Stock").val();

    var uniteStock = $("#uniteStock_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 53,

        designation: designation,

        uniteStock: uniteStock,

        quantite: quantite,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        if (data == 1) {
          $("#qteInitial_Stock").val("");

          $("#dateExpiration_Stock").val("");

          $("#btn_ajt_StockCatalogue").prop("disabled", false);
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

$(function () {
  $("#btn_trm_StockCatalogue").click(function () {
    var designation = $("#designation_Stock").val();

    var uniteStock = $("#uniteStock_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 53,

        designation: designation,

        uniteStock: uniteStock,

        quantite: quantite,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        if (data == 1) {
          $("#qteInitial_Stock").val("");

          $("#dateExpiration_Stock").val("");

          $("#ajt_Stock").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter Stock**/

/**Debut ajouter et terminer Stock**/

$(function () {
  $("#btn_trm_StockCatalogue").click(function () {
    var designation = $("#designation_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();
  });
});

/**Fin ajouter et terminer Stock**/

/**Debut initialiser Stock Pharmacie**/

function init_Stock_P(designation) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var prixSession = $("#prixSession-" + designation).val();

  var prixPublic = $("#prixPublic-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  //alert (designation+' '+quantite+' '+uniteStock+' '+prix+' '+prixUS+' '+dateExpiration);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 4,

      idDesignation: designation,

      quantite: quantite,

      prixSession: prixSession,

      prixPublic: prixPublic,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      $("#quantiteAStocke-" + designation).prop("disabled", true);

      $("#prixSession-" + designation).prop("disabled", true);

      $("#prixPublic-" + designation).prop("disabled", true);

      $("#dateExpiration-" + designation).prop("disabled", true);

      $("#btn_InitStock_P-" + designation).prop("disabled", true);

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin initialiser Stock Pharmacie**/

/**Debut ajouter Stock Pharmacie**/

$(function () {
  $("#btn_ajt_StockCatalogue_P").click(function () {
    var designation = $("#designation_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();

    $("#btn_ajt_StockCatalogue_P").prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 40,

        designation: designation,

        quantite: quantite,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        tab = data.split("+");

        $("#qteInitial_Stock").val("");

        $("#dateExpiration_Stock").val("");

        $("#btn_ajt_StockCatalogue_P").prop("disabled", false);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

$(function () {
  $("#btn_ajt_StockCatalogue_Ph").click(function () {
    var designation = $("#designation_Stock").val();

    var idBl = $("#idBl_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 400,

        designation: designation,

        idBl: idBl,

        quantite: quantite,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        if (data == 1) {
          $("#qteInitial_Stock").val("");

          $("#dateExpiration_Stock").val("");

          $("#btn_ajt_StockCatalogue_P").prop("disabled", false);
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter Stock Pharmacie**/

/**Debut ajouter et terminer Stock Pharmacie**/

$(function () {
  $("#btn_trm_StockCatalogue_P").click(function () {
    var designation = $("#designation_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();

    $("#btn_trm_StockCatalogue_P").prop("disabled", true);
  });
});

$(function () {
  $("#btn_trm_StockCatalogue_Ph").click(function () {
    var designation = $("#designation_Stock").val();

    var idBl = $("#idBl_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 400,

        designation: designation,

        idBl: idBl,

        quantite: quantite,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        if (data == 1) {
          $("#qteInitial_Stock").val("");

          $("#dateExpiration_Stock").val("");

          $("#ajt_Stock").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter et terminer Stock Pharmacie**/

/**Debut initialiser Produit**/

function init_Produit(id) {
  var categorie = $("#categorie-" + id).val();

  var design = $("#designation-" + id).val();

  var codeBarre = $("#codeBarre-" + id).val();

  var nbreArticle = $("#nbreArticleUniteStock-" + id).val();

  var uniteStock = $("#uniteStock-" + id).val();

  var prix = $("#prix-" + id).val();

  var prixUS = $("#prixuniteStock-" + id).val();

  var prixachat = $("#prixachat-" + id).val();

  tab = design.split("<>");

  var designation = tab[0];

  var idF = tab[1];

  var quantite = $("#quantite-" + id).val();

  var dateExpiration = $("#dateExpiration-" + id).val();

  $("#btn_init_Produit-" + id).prop("disabled", true);

  $.ajax({
    url: "ajax/initialiserProduitAjax.php",

    method: "POST",

    data: {
      operation: 1,

      idDesignation: id,

      idFusion: idF,

      categorie: categorie,

      designation: designation,

      nbreArticleUniteStock: nbreArticle,

      uniteStock: uniteStock,

      prixUnitaire: prix,

      prixUniteStock: prixUS,

      prixAchat: prixachat,

      codeBarreDesignation: codeBarre,
    },

    success: function (data) {
      $("#categorie-" + id).prop("disabled", true);

      $("#codeBarre-" + id).prop("disabled", true);

      $("#uniteStock-" + id).prop("disabled", true);

      $("#nbreArticleUniteStock-" + id).prop("disabled", true);

      $("#prixuniteStock-" + id).prop("disabled", true);

      $("#prix-" + id).prop("disabled", true);

      $("#prixachat-" + id).prop("disabled", true);

      $.ajax({
        url: "ajax/ajouterLigneAjax.php",

        method: "POST",

        data: {
          operation: 96,

          designation: designation,

          quantite: quantite,

          dateExpiration: dateExpiration,
        },

        success: function (data) {
          if (data == 1) {
            $("#quantite-" + id).prop("disabled", true);

            $("#dateExpiration-" + id).prop("disabled", true);
          }
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_init_Produit").click(function () {
    var categorie = $("#categorie").val();

    var designation = $("#designation").val();

    var codeBarre = $("#codeBarre").val();

    var nbreArticle = $("#nbreArticleUniteStock").val();

    var uniteStock = $("#uniteStock").val();

    var prix = $("#prix").val();

    var prixUS = $("#prixuniteStock").val();

    var prixachat = $("#prixachat").val();

    var quantite = $("#quantite").val();

    var dateExpiration = $("#dateExpiration").val();

    $.ajax({
      url: "ajax/initialiserProduitAjax.php",

      method: "POST",

      data: {
        operation: 1,

        idFusion: 0,

        categorie: categorie,

        designation: designation,

        nbreArticleUniteStock: nbreArticle,

        uniteStock: uniteStock,

        prixUnitaire: prix,

        prixUniteStock: prixUS,

        prixAchat: prixachat,

        codeBarreDesignation: codeBarre,
      },

      success: function (data) {
        $("#categorie").val("");

        $("#designation").val("");

        $("#codeBarre").val("");

        $("#uniteStock").val("");

        $("#nbreArticleUniteStock").val("");

        $("#prixuniteStock").val("");

        $("#prix").val("");

        $("#prixachat").val("");

        $.ajax({
          url: "ajax/ajouterLigneAjax.php",

          method: "POST",

          data: {
            operation: 96,

            designation: designation,

            quantite: quantite,

            dateExpiration: dateExpiration,
          },

          success: function (data) {
            if (data == 1) {
              $("#quantite").val("");

              $("#dateExpiration").val("");
            }
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin initialiser Produit**/

/**Debut initialiser Produit Pharmacie**/

function init_ProduitPH(id) {
  var categorie = $("#categorie-" + id).val();

  var design = $("#designation-" + id).val();

  var codeBarre = $("#codeBarre-" + id).val();

  var prixSessio = $("#prixSession-" + id).val();

  var prixPubli = $("#prixPublic-" + id).val();

  var quantite = $("#quantite-" + id).val();

  var dateExpiration = $("#dateExpiration-" + id).val();

  tab = design.split("<>");

  var designation = tab[0];

  var idF = tab[1];

  var form = tab[2];

  var tablea = tab[3];

  $("#btn_init_ProduitPH-" + id).prop("disabled", true);

  $.ajax({
    url: "ajax/initialiserProduitAjax.php",

    method: "POST",

    data: {
      operation: 2,

      idDesignation: id,

      idFusion: idF,

      categorie: categorie,

      designation: designation,

      forme: form,

      tableau: tablea,

      prixSession: prixSessio,

      prixPublic: prixPubli,

      codeBarreDesignation: codeBarre,
    },

    success: function (data) {
      $("#categorie-" + id).prop("disabled", true);

      $("#codeBarre-" + id).prop("disabled", true);

      $("#prixSession-" + id).prop("disabled", true);

      $("#prixPublic-" + id).prop("disabled", true);

      $("#init_ProduitPH-" + id).prop("disabled", true);

      //console.log(data);

      $.ajax({
        url: "ajax/ajouterLigneAjax.php",

        method: "POST",

        data: {
          operation: 40,

          designation: designation,

          quantite: quantite,

          dateExpiration: dateExpiration,
        },

        success: function (data) {
          tab = data.split("+");

          $("#quantite-" + id).prop("disabled", true);

          $("#dateExpiration-" + id).prop("disabled", true);
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_init_ProduitPH").click(function () {
    var categorie = $("#categoriePh").val();

    var design = $("#designation").val();

    var codeBarre = $("#codeBarre").val();

    var form = $("#formePh").val();

    var tablea = $("#tableau").val();

    var prixSessio = $("#prixSession").val();

    var prixPubli = $("#prixPublic").val();

    $.ajax({
      url: "ajax/initialiserProduitAjax.php",

      method: "POST",

      data: {
        operation: 2,

        idFusion: 0,

        categorie: categorie,

        designation: design,

        codeBarreDesignation: codeBarre,

        forme: form,

        tableau: tablea,

        prixSession: prixSessio,

        prixPublic: prixPubli,
      },

      success: function (data) {
        $("#designation").val("");

        $("#categoriePh").val("");

        $("#codeBarre").val("");

        $("#formePh").val("");

        $("#tableau").val("");

        $("#prixSession").val("");

        $("#prixPublic").val("");

        $("#ajt_Stock").modal("show");

        $("#designation_Stock").val(design);

        $("#categorie_Stock").val(categorie);

        //console.log(data);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin initialiser Produit Pharmacie**/

/**Debut ajouter Designation Pharmacie**/

function ajt_Reference_P() {
  var designation = $("#designationPh").val();

  var categorie = $("#categorie2").val();

  var forme = $("#forme").val();

  var tableau = $("#tableau").val();

  var prixSession = $("#prixSession").val();

  var prixPublic = $("#prixPublic").val();

  var codeBarre = $("#codeBarre").val();

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 6,

      designation: designation,

      categorie: categorie,

      forme: forme,

      tableau: tableau,

      prixSession: prixSession,

      prixPublic: prixPublic,

      codeBarre: codeBarre,
    },

    success: function (data) {
      tab = data.split("+");

      var ligne =
        "<tr id='design'><td>0</td><td>" +
        tab[0] +
        "</td><td>" +
        tab[1] +
        "</td><td>" +
        tab[2] +
        "</td><td>" +
        tab[3] +
        "</td><td>" +
        tab[4] +
        "</td><td>" +
        tab[5] +
        "</td><td>En cours ...</td></tr>";

      $("table.tabDesign").prepend(ligne);

      $("#design").css({ "background-color": "green" });

      $("#designationPh").val("");

      $("#forme").val("");

      $("#tableau").val("");

      $("#prixSession").val("");

      $("#prixPublic").val("");

      $("#AjoutStockModal").modal("hide");

      $("#ajt_Stock").modal("show");

      $("#designation_Stock").val(designation);

      $("#categorie_Stock").val(categorie);

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Designation Pharmacie**/

/**Debut modifier Designation Pharmacie**/

function mdf_Designation_Ph(idDesignation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 7,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("+");

      $("#idDesignation_Mdf").val(tab[0]);

      $("#designation_Mdf").val(tab[1]);

      $("#categorie_Mdf").children("option:selected").text(tab[2]);

      $("#forme_Mdf").children("option:selected").text(tab[3]);

      $("#tableau_Mdf").children("option:selected").text(tab[4]);

      $("#prixSession_Mdf").val(tab[5]);

      $("#prixPublic_Mdf").val(tab[6]);

      $("#modifierDesignModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Reference_P").click(function () {
    var idDesignation = $("#idDesignation_Mdf").val();

    var designation = $("#designation_Mdf").val();

    var categorie = $("#categorie_Mdf").val();

    var forme = $("#forme_Mdf").val();

    var tableau = $("#tableau_Mdf").val();

    var prixSession = $("#prixSession_Mdf").val();

    var prixPublic = $("#prixPublic_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 8,

        idDesignation: idDesignation,

        designation: designation,

        categorie: categorie,

        forme: forme,

        tableau: tableau,

        prixSession: prixSession,

        prixPublic: prixPublic,
      },

      success: function (data) {
        tab = data.split("+");

        var ligne =
          "<tr id='design'><td>0</td><td>" +
          designation +
          "</td><td> </td><td>" +
          forme +
          "</td><td>" +
          tableau +
          "</td><td>" +
          prixSession +
          "</td><td>" +
          prixPublic +
          "</td><td>En cours ...</td></tr>";

        $("table.tabDesign").prepend(ligne);

        $("#design").css({ "background-color": "yellow" });

        $("#modifierDesignModal").modal("hide");
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Designation Pharmacie**/

/**Debut ajouter CodeBarre Pharmacie**/

function mdf_CodeBarreDesign_Ph(idDesignation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 7,

      idDesignation: idDesignation,
    },

    success: function (data) {
      $("#modifierCodeBModal").modal("show");

      tab = data.split("+");

      $("#idDesignation_CB").val(tab[0]);

      $("#designation_CB").val(tab[1]);

      $("#categorie_CB").val(tab[2]);

      $("#forme_CB").val(tab[3]);

      $("#tableau_CB").val(tab[4]);

      $("#prixSession_CB").val(tab[5]);

      $("#prixPublic_CB").val(tab[6]);

      if (tab[1] != null) {
        $("#codeBarreDesignation").val(tab[7]);
      } else {
        $("#codeBarreDesignation").focus();
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_CodeBarre_P").click(function () {
    $(".error").hide();

    var idDesignation = $("#idDesignation_CB").val();

    var code = $("#codeBarreDesignation").val();

    var designation = $("#designation_CB").val();

    var categorie = $("#categorie_CB").val();

    var forme = $("#forme_CB").val();

    var tableau = $("#tableau_CB").val();

    var prixSession = $("#prixSession_CB").val();

    var prixPublic = $("#prixPublic_CB").val();

    //alert(code);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 5,

        idDesignation: idDesignation,

        code: code,
      },

      success: function (data) {
        // alert(data);

        var ligne =
          "<tr id='design'><td>0</td><td>" +
          designation +
          "</td><td>" +
          code +
          "</td><td>" +
          forme +
          "</td><td>" +
          tableau +
          "</td><td>" +
          prixSession +
          "</td><td>" +
          prixPublic +
          "</td><td>En cours ...</td></tr>";

        $("table.tabDesign").prepend(ligne);

        $("#design").css({ "background-color": "blue" });

        $("#modifierCodeBModal").modal("hide");

        //console.log(data);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    return false;
  });
});

$(function () {
  $(".btn_CodeDesign_P").click(function (event) {
    $(".error").hide();

    event.preventDefault(); //prevent default action

    event.stopPropagation();

    // validate and process form here

    //alert(123);

    return false;
  });
});

/**Fin ajouter CodeBarre Pharmacie**/

/**Debut modifier Tva Designation Pharmacie**/

function mdf_Tva_Ph(idDesignation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 15,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("<>");

      var ligne =
        "<tr id='design'><td>0</td><td>" +
        tab[1] +
        "</td><td>" +
        tab[7] +
        "</td><td>" +
        tab[3] +
        "</td><td>" +
        tab[4] +
        "</td><td>" +
        tab[5] +
        "</td><td>" +
        tab[6] +
        "</td><td>En cours ...</td></tr>";

      $("table.tabDesign").prepend(ligne);

      $("#design").css({ "background-color": "green" });
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier Tva Designation Pharmacie**/

/**Debut supprimer Designation Pharmacie**/

function spm_Designation_Ph(idDesignation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 7,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("+");

      $("#idDesignation_Spm").val(tab[0]);

      $("#designation_Spm").val(tab[1]);

      $("#categorie_Spm").children("option:selected").text(tab[2]);

      $("#forme_Spm").val(tab[3]);

      $("#tableau_Spm").val(tab[4]);

      $("#prixSession_Spm").val(tab[5]);

      $("#prixPublic_Spm").val(tab[6]);

      $("#supprimerDesignModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Reference_P").click(function () {
    var idDesignation = $("#idDesignation_Spm").val();

    var designation = $("#designation_Spm").val();

    var categorie = $("#categorie_Spm").val();

    var forme = $("#forme_Spm").val();

    var tableau = $("#tableau_Spm").val();

    var prixSession = $("#prixSession_Spm").val();

    var prixPublic = $("#prixPublic_Spm").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 9,

        idDesignation: idDesignation,

        designation: designation,
      },

      success: function (data) {
        tab = data.split("+");

        var ligne =
          "<tr id='design'><td>0</td><td>" +
          designation +
          "</td><td> </td><td>" +
          forme +
          "</td><td>" +
          tableau +
          "</td><td>" +
          prixSession +
          "</td><td>" +
          prixPublic +
          "</td><td>En cours ...</td></tr>";

        $("table.tabDesign").prepend(ligne);

        $("#design").css({ "background-color": "red" });

        $("#supprimerDesignModal").modal("hide");
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Designation Pharmacie**/

/**Debut modifier Designation Entrepot**/

function mdf_Designation_ET(idDesignation, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 37,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#ordre_Mdf").val(ordre);

      $("#idDesignation_Mdf").val(tab[0]);

      $("#designation_Mdf").val(tab[1]);

      $("#categorie_Mdf").children("option:selected").text(tab[2]);

      $("#uniteStock_Mdf").children("option:selected").text(tab[3]);

      $("#nbArticleUniteStock_Mdf").val(tab[4]);

      $("#prixuniteStock_Mdf").val(tab[5]);

      $("#prixachat_Mdf").val(tab[6]);

      $("#prix_Mdf").val(tab[7]);

      $("#modifierDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Reference_ET").click(function () {
    var ordre = $("#ordre_Mdf").val();

    var idDesignation = $("#idDesignation_Mdf").val();

    var designation = $("#designation_Mdf").val();

    var categorie = $("#categorie_Mdf").val();

    var uniteStock = $("#uniteStock_Mdf").val();

    var nbArticleUniteStock = $("#nbArticleUniteStock_Mdf").val();

    var prixUniteStock = $("#prixuniteStock_Mdf").val();

    var prixAchat = $("#prixachat_Mdf").val();

    var prixUnitaire = $("#prix_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 74,

        idDesignation: idDesignation,

        designation: designation,

        categorie: categorie,

        uniteStock: uniteStock,

        nbArticleUniteStock: nbArticleUniteStock,

        prixUniteStock: prixUniteStock,

        prixAchat: prixAchat,

        prixUnitaire: prixUnitaire,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableDesignation tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(designation);

              $(tr).find("td:eq(2)").html(categorie);

              $(tr).find("td:eq(3)").html(uniteStock);

              $(tr).find("td:eq(4)").html(tab[4]);

              $(tr).find("td:eq(5)").html(prixUniteStock);

              $(tr).find("td:eq(6)").html(prixAchat);

              $(tr).find("td:eq(7)").html("En cours ...");
            }
          });
        }

        $("#modifierDesignation").modal("hide");
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Designation Entrepot**/

/**Debut supprimer Designation Entrepot**/

function spm_Designation_ET(idDesignation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 37,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idDesignation_Spm").val(tab[0]);

      $("#designation_Spm").val(tab[1]);

      $("#categorie_Spm").children("option:selected").text(tab[2]);

      $("#uniteStock_Spm").children("option:selected").text(tab[3]);

      $("#nbArticleUniteStock_Spm").val(tab[4]);

      $("#prixuniteStock_Spm").val(tab[5]);

      $("#prixachat_Spm").val(tab[6]);

      $("#supprimerDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin supprimer Designation Entrepot**/

/**Debut ajouter CodeBarre Pharmacie**/

function mdf_CodeBarreDesign(idDesignation, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 41,

      idDesignation: idDesignation,
    },

    success: function (data) {
      $("#modifierCodeBModal").modal("show");

      tab = data.split("<>");

      $("#ordre_CB").val(ordre);

      $("#idDesignation_CB").val(idDesignation);

      if (tab[7] != null) {
        $("#codeBarreDesignation").val(tab[7]);
      } else {
        $("#codeBarreDesignation").focus();
      }

      if (tab[8] != null) {
        $("#codeBarreUniteStock").val(tab[8]);
      } else {
        $("#codeBarreUniteStock").focus();
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_CodeBarre").click(function () {
    $(".error").hide();

    var ordre = $("#ordre_CB").val();

    var idDesignation = $("#idDesignation_CB").val();

    var codeBD = $("#codeBarreDesignation").val();

    var codeBUS = $("#codeBarreUniteStock").val();

    //alert(code);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 42,

        idDesignation: idDesignation,

        codeBD: codeBD,

        codeBUS: codeBUS,
      },

      success: function (data) {
        // alert(data);

        tab = data.split("<>");

        $("#tableDesignation tr").each(function (row, tr) {
          designation = $(tr).find("td:eq(0)").text();

          if (designation != "" && designation == ordre) {
            $(tr).find("td:eq(1)").html(tab[2]);

            $(tr).find("td:eq(2)").html(tab[3]);

            $(tr).find("td:eq(3)").html(tab[4]);

            $(tr).find("td:eq(4)").html(tab[5]);

            $(tr).find("td:eq(5)").html(tab[6]);

            $(tr).find("td:eq(6)").html(tab[7]);

            $(tr).find("td:eq(7)").html("En cours ...");
          }
        });

        $("#modifierCodeBModal").modal("hide");

        //console.log(data);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    return false;
  });
});

$(function () {
  $(".btn_CodeDesign").click(function (event) {
    $(".error").hide();

    event.preventDefault(); //prevent default action

    event.stopPropagation();

    // validate and process form here

    //alert(123);

    return false;
  });
});

/**Fin ajouter CodeBarre Pharmacie**/

/**Debut modifier Designation**/

function mdf_Designation(idDesignation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 41,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idDesignation_Mdf").val(tab[0]);

      $("#designation_Mdf").val(tab[1]);

      $("#categorie_Mdf").children("option:selected").text(tab[2]);

      $("#uniteStock_Mdf").children("option:selected").text(tab[3]);

      $("#nbArticleUniteStock_Mdf").val(tab[4]);

      $("#prixuniteStock_Mdf").val(tab[5]);

      $("#prix_Mdf").val(tab[6]);

      $("#prixachat_Mdf").val(tab[9]);

      $("#modifierDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier Designation Entrepot**/

/**Debut supprimer Designation**/

function spm_Designation(idDesignation) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 41,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idDesignation_Spm").val(tab[0]);

      $("#designation_Spm").val(tab[1]);

      $("#categorie_Spm").children("option:selected").text(tab[2]);

      $("#uniteStock_Spm").children("option:selected").text(tab[3]);

      $("#nbArticleUniteStock_Spm").val(tab[4]);

      $("#prixuniteStock_Spm").val(tab[5]);

      $("#prix_Spm").val(tab[6]);

      $("#prixachat_Spm").val(tab[9]);

      $("#supprimerDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin supprimer Designation **/

/**Debut modifier Stock Pharmacie**/

function mdf_Stock_Ph0(idStock) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 10,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Mdf0").val(tab[0]);

      $("#designation_Mdf0").val(tab[1]);

      $("#numeroBl_Mdf0").val(tab[2]);

      $("#forme_Mdf0").val(tab[3]);

      $("#qteInitiale_Mdf0").val(tab[4]);

      $("#qteReste_Mdf0").val(tab[5]);

      $("#dateStockage_Mdf0").val(tab[6]);

      $("#dateExpiration_Mdf0").val(tab[7]);

      $("#prixSession_Mdf0").val(tab[8]);

      $("#prixPublic_Mdf0").val(tab[9]);

      $("#modifierStockModal0").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Stock_P0").click(function () {
    var idStock = $("#idStock_Mdf0").val();

    var numeroBl = $("#numeroBl_Mdf0").val();

    var quantite = $("#qteInitiale_Mdf0").val();

    var prixSession = $("#prixSession_Mdf0").val();

    var prixPublic = $("#prixPublic_Mdf0").val();

    var dateExpiration = $("#dateExpiration_Mdf0").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 11,

        idStock: idStock,

        numeroBl: numeroBl,

        quantite: quantite,

        prixSession: prixSession,

        prixPublic: prixPublic,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr id='stock'><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[4] +
            "</td><td>" +
            tab[4] +
            "</td><td>" +
            tab[5] +
            "</td><td>" +
            tab[6] +
            "</td><td>" +
            tab[7] +
            "</td><td>" +
            dateExpiration +
            "</td><td>En cours ...</td></tr>";

          $("table.tabStock").prepend(ligne);

          $("#stock").css({ "background-color": "yellow" });

          $("#modifierStockModal0").modal("hide");
        } else {
          $("#msg_info_js").modal("show");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

$(function () {
  $("#btn_mdf_Stock_P0BL").click(function () {
    var idStock = $("#idStock_Mdf0").val();

    var numeroBl = $("#numeroBl_Mdf0").val();

    var quantite = $("#qteInitiale_Mdf0").val();

    var prixSession = $("#prixSession_Mdf0").val();

    var prixPublic = $("#prixPublic_Mdf0").val();

    var dateExpiration = $("#dateExpiration_Mdf0").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 101,

        idStock: idStock,

        numeroBl: numeroBl,

        quantite: quantite,

        prixSession: prixSession,

        prixPublic: prixPublic,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr id='stock'><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[4] +
            "</td><td>" +
            tab[5] +
            "</td><td>" +
            tab[6] +
            "</td><td>" +
            dateExpiration +
            "</td><td>En cours ...</td></tr>";

          $("table.tabStock").prepend(ligne);

          $("#stock").css({ "background-color": "yellow" });

          $("#modifierStockModal0").modal("hide");
        } else {
          $("#msg_info_js").modal("show");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

function mdf_Stock_Ph(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 10,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#ordre_Mdf").val(ordre);

      $("#idStock_Mdf").val(tab[0]);

      $("#designation_Mdf").val(tab[1]);

      //$('#numeroBl_Mdf').children("option:selected").text(tab[2]);

      $("#numeroBl_Mdf").val(tab[2]);

      $("#forme_Mdf").val(tab[3]);

      $("#qteInitiale_Mdf").val(tab[4]);

      $("#qteReste_Mdf").val(tab[5]);

      $("#dateStockage_Mdf").val(tab[6]);

      $("#dateExpiration_Mdf").val(tab[7]);

      $("#prixSession_Mdf").val(tab[8]);

      $("#prixPublic_Mdf").val(tab[9]);

      $("#modifierStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Stock_PH").click(function () {
    var ordre = $("#ordre_Mdf").val();

    var idStock = $("#idStock_Mdf").val();

    var numeroBl = $("#numeroBl_Mdf").val();

    var initiale = $("#qteInitiale_Mdf").val();

    var quantite = $("#qteReste_Mdf").val();

    var prixSession = $("#prixSession_Mdf").val();

    var prixPublic = $("#prixPublic_Mdf").val();

    var dateExpiration = $("#dateExpiration_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 110,

        idStock: idStock,

        numeroBl: numeroBl,

        quantite: quantite,

        prixSession: prixSession,

        prixPublic: prixPublic,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[7]);

              $(tr).find("td:eq(3)").html(initiale);

              $(tr).find("td:eq(4)").html(tab[4]);

              $(tr).find("td:eq(5)").html(tab[3]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[6]);

              $(tr).find("td:eq(8)").html(dateExpiration);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });

          $("#modifierStockModal").modal("hide");
        }

        if (tab[0] == 2) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[7]);

              $(tr).find("td:eq(3)").html(initiale);

              $(tr).find("td:eq(4)").html(tab[4]);

              $(tr).find("td:eq(5)").html(tab[3]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[6]);

              $(tr).find("td:eq(8)").html(dateExpiration);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });

          $("#modifierStockModal").modal("hide");
        }

        if (tab[0] == 0) {
          $("#msg_info_js").modal("show");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

$(function () {
  $("#btn_mdf_Stock_PBL").click(function () {
    var idStock = $("#idStock_Mdf").val();

    var numeroBl = $("#numeroBl_Mdf").val();

    var initiale = $("#qteInitiale_Mdf").val();

    var quantite = $("#qteReste_Mdf").val();

    var prixSession = $("#prixSession_Mdf").val();

    var prixPublic = $("#prixPublic_Mdf").val();

    var dateExpiration = $("#dateExpiration_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 1101,

        idStock: idStock,

        numeroBl: numeroBl,

        quantite: quantite,

        prixSession: prixSession,

        prixPublic: prixPublic,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr id='stock'><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            initiale +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[4] +
            "</td><td>" +
            tab[5] +
            "</td><td>" +
            tab[6] +
            "</td><td>" +
            dateExpiration +
            "</td><td>En cours ...</td></tr>";

          $("table.tabStock").prepend(ligne);

          $("#stock").css({ "background-color": "yellow" });

          $("#modifierStockModal").modal("hide");
        }

        if (tab[0] == 2) {
          var ligne =
            "<tr id='stock'><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[4] +
            "</td><td>" +
            tab[5] +
            "</td><td>" +
            tab[6] +
            "</td><td>" +
            dateExpiration +
            "</td><td>En cours ...</td></tr>";

          $("table.tabStock").prepend(ligne);

          $("#stock").css({ "background-color": "yellow" });

          $("#modifierStockModal").modal("hide");
        }

        if (tab[0] == 0) {
          $("#msg_info_js").modal("show");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Stock Pharmacie**/

/**Debut supprimer Stock Pharmacie**/

function spm_Stock_Ph(idStock, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 10,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#ordre_Spm").val(ordre);

      $("#idStock_Spm").val(tab[0]);

      $("#designation_Spm").val(tab[1]);

      $("#numeroBl_Spm").val(tab[2]);

      $("#forme_Spm").val(tab[3]);

      $("#qteInitial_Spm").val(tab[4]);

      $("#qteReste_Spm").val(tab[5]);

      $("#dateStockage_Spm").val(tab[6]);

      $("#dateExpiration_Spm").val(tab[7]);

      $("#supprimerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Stock_PH").click(function () {
    var ordre = $("#ordre_Spm").val();

    var idStock = $("#idStock_Spm").val();

    var designation = $("#designation_Spm").val();

    var numeroBl_Spm = $("#numeroBl_Spm").val();

    var quantiteI = $("#qteInitial_Spm").val();

    var dateExpiration = $("#dateExpiration_Spm").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 12,

        idStock: idStock,

        numeroBl_Spm: numeroBl_Spm,

        designation: designation,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableStockEntrees tr").each(function (row, tr) {
            ligne = $(tr).find("td:eq(0)").text();

            if (ligne != "" && ligne == ordre) {
              $(tr).find("td:eq(1)").html(tab[1]);

              $(tr).find("td:eq(2)").html(tab[7]);

              $(tr).find("td:eq(3)").html(quantiteI);

              $(tr).find("td:eq(4)").html(tab[4]);

              $(tr).find("td:eq(5)").html(tab[3]);

              $(tr).find("td:eq(6)").html(tab[5]);

              $(tr).find("td:eq(7)").html(tab[6]);

              $(tr).find("td:eq(8)").html(dateExpiration);

              $(tr).find("td:eq(9)").html("En cours ...");
            }
          });

          $("#supprimerStockModal").modal("hide");
        } else {
          $("#msg_info_js").modal("show");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

$(function () {
  $("#btn_spm_Stock_PBL").click(function () {
    var idStock = $("#idStock_Spm").val();

    var designation = $("#designation_Spm").val();

    var numeroBl_Spm = $("#numeroBl_Spm").val();

    var forme = $("#forme_Spm").val();

    var quantiteI = $("#qteInitial_Spm").val();

    var quantiteR = $("#qteReste_Spm").val();

    var dateStockage = $("#dateStockage_Spm").val();

    var dateExpiration = $("#dateExpiration_Spm").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 120,

        idStock: idStock,

        numeroBl_Spm: numeroBl_Spm,

        designation: designation,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr id='stock'><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            quantiteI +
            "</td><td>" +
            tab[3] +
            "</td><td>" +
            tab[4] +
            "</td><td>" +
            tab[5] +
            "</td><td>" +
            tab[6] +
            "</td><td>" +
            dateExpiration +
            "</td><td>En cours ...</td></tr>";

          $("table.tabStock").prepend(ligne);

          $("#stock").css({ "background-color": "red" });

          $("#supprimerStockModal").modal("hide");
        } else {
          $("#msg_info_js").modal("show");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Stock Pharmacie**/

/**Debut retirer Stock Pharmacie**/

function rtr_Stock_P(idStock) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 10,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Rtr").val(tab[0]);

      $("#designation_Rtr").val(tab[1]);

      $("#categorie_Rtr").val(tab[2]);

      // $('#forme_Rtr').val(tab[3]);

      $("#qteInitial_Rtr").val(tab[4]);

      $("#qteReste_Rtr").val(tab[5]);

      //$('#dateStockage_Rtr').val(tab[6]);

      $("#dateExpiration_Rtr").val(tab[7]);

      $("#retirerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_rtr_Stock_P").click(function () {
    var idStock = $("#idStock_Rtr").val();

    $("#btn_RetirerStock-" + idStock).prop("disabled", true);

    $("#btn_RetirerStock_P-" + idStock).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 13,

        idStock: idStock,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr id='stock'><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>RETIRER</td><td>En cours ...</td></tr>";

          $("table.tabDate").prepend(ligne);

          $("#stock").css({ "background-color": "red" });

          $("#retirerStockModal").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin retirer Stock Pharmacie**/

/**Debut retirer Stock Entrepot**/

function rtr_Stock_ET(idEntrepotStock) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 47,

      idEntrepotStock: idEntrepotStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Rtr").val(idEntrepotStock);

      $("#designation_Rtr").val(tab[2]);

      $("#qteInitial_Rtr").val(tab[3]);

      $("#qteReste_Rtr").val(tab[4]);

      $("#dateExpiration_Rtr").val(tab[1]);

      $("#entrepot_Rtr").val(tab[5]);

      $("#retirerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_rtr_Stock_ET").click(function () {
    var idEntrepotStock = $("#idStock_Rtr").val();

    $("#btn_RetirerStock_ET-" + idEntrepotStock).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 48,

        idEntrepotStock: idEntrepotStock,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr id='stock'><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>RETIRER</td><td>" +
            tab[4] +
            "</td><td>En cours ...</td></tr>";

          $("table.tabDate").prepend(ligne);

          $("#stock").css({ "background-color": "red" });

          $("#retirerStockModal").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin retirer Stock Entrepot**/

/**Debut retirer Stock**/

function rtr_Stock(idStock) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 49,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idStock_Rtr").val(idStock);

      $("#designation_Rtr").val(tab[0]);

      $("#qteInitial_Rtr").val(tab[1]);

      $("#qteReste_Rtr").val(tab[2]);

      $("#dateExpiration_Rtr").val(tab[3]);

      $("#retirerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_rtr_Stock").click(function () {
    var idStock = $("#idStock_Rtr").val();

    var designation = $("#designation_Rtr").val();

    var quantiteR = $("#qteReste_Rtr").val();

    $("#btn_RetirerStock-" + idStock).prop("disabled", true);

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 50,

        idStock: idStock,

        quantite: quantiteR,

        designation: designation,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr id='stock'><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>RETIRER</td><td>En cours ...</td></tr>";

          $("table.tabDate").prepend(ligne);

          $("#stock").css({ "background-color": "red" });

          $("#retirerStockModal").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin retirer Stock**/

/**Debut retirer Stock Pharmacie**/

function dim_Stock_P(idStock) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 10,

      idStock: idStock,
    },

    success: function (data) {
      tab = data.split("+");

      $("#idStock_Dim").val(tab[0]);

      $("#designation_Dim").val(tab[1]);

      $("#categorie_Dim").val(tab[2]);

      // $('#forme_Rtr').val(tab[3]);

      $("#qteInitial_Dim").val(tab[4]);

      $("#qteReste_Dim").val(tab[5]);

      //$('#dateStockage_Rtr').val(tab[6]);

      $("#dateExpiration_Dim").val(tab[7]);

      $("#qteRetirer_Dim").val(tab[10]);

      $("#diminuerStockModal").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_dim_Stock_P").click(function () {
    var idStock = $("#idStock_Dim").val();

    var designation = $("#designation_Dim").val();

    var categorie = $("#categorie_Dim").val();

    //var forme=$('#forme_Rtr').val();

    var quantiteI = $("#qteInitial_Dim").val();

    var quantiteR = $("#qteReste_Dim").val();

    var quantiteD = $("#qteDiminuer_Dim").val();

    var dateStockage = $("#dateStockage_Dim").val();

    var dateExpiration = $("#dateExpiration_Dim").val();

    var quantite = quantiteR - quantiteD;

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 14,

        idStock: idStock,

        quantiteR: quantiteR,

        quantiteD: quantiteD,

        designation: designation,
      },

      success: function (data) {
        tab = data.split("+");

        if (quantiteD != 0 && quantiteD != "") {
          var ligne =
            "<tr id='stock'><td>" +
            dateExpiration +
            "</td><td>" +
            designation +
            "</td><td>" +
            categorie +
            "</td><td>" +
            quantite +
            "</td><td>En cours ...</td></tr>";

          $("table.tabDate").prepend(ligne);

          $("#stock").css({ "background-color": "yellow" });

          $("#qteDiminuer_Dim").val("");

          $("#diminuerStockModal").modal("hide");

          $("#btn_DiminuerStock_P-" + idStock).prop("disabled", true);
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin retirer Stock Pharmacie**/

/*************Debut Modification vitrine***************/

/**Debut supprimer Designation Vitrine**/

/**Debut ajouter Vitrine**/

function ajt_vitrine(id) {
  // alert(1)

  // var designation=$('#designation-'+id).val();

  var categorie = $("#categorie-" + id).val();

  var design = $("#designation-" + id).val();

  var designJC = $("#designation-" + id).val();

  var uniteStock = $("#uniteStock-" + id).val();

  var prix = $("#prixUnitaire-" + id).val();

  var prixUS = $("#prixuniteStock-" + id).val();

  var codeBarreDesignat = $("#codeBarreDesignation-" + id).val();

  var codeBarreuniteSt = $("#codeBarreuniteStock-" + id).val();

  var idFus = $("#idFusion-" + id).val();

  //alert (design);

  $.ajax({
    url: "ajax/ajouterVitrineAjax.php",

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

    success: function (data) {
      // alert(data)

      tab = data.split("+");

      var ligne =
        "<tr><td>0</td><td>" +
        tab[0] +
        "</td><td>" +
        tab[1] +
        "</td><td>" +
        tab[2] +
        "</td><td>" +
        tab[3] +
        "</td><td>" +
        tab[4] +
        "</td><td>En cours ...</td></tr>";

      $("table.tabVitrine").prepend(ligne);

      $("#uniteStock-" + id).prop("disabled", true);

      $("#prixUnitaire-" + id).prop("disabled", true);

      $("#prixuniteStock-" + id).prop("disabled", true);

      $("#init_Produit-" + id).prop("disabled", true);

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Vitrine**/

/**Debut ajouter Vitrine Pharma**/

function ajt_vitrinePH(id) {
  // var designation=$('#designation-'+id).val();

  var categorie = $("#categorie-" + id).val();

  var design = $("#designation-" + id).val();

  var designJC = $("#designation-" + id).val();

  var forme = $("#forme-" + id).val();

  var tableau = $("#tableau-" + id).val();

  var prixPublic = $("#prixPublic-" + id).val();

  var codeBarreDesignat = $("#codeBarreDesignation-" + id).val();

  var codeBarreuniteSt = $("#codeBarreuniteStock-" + id).val();

  var idFus = $("#idFusion-" + id).val();

  //console.log('data');

  //alert (design);

  $.ajax({
    url: "ajax/ajouterVitrineAjax.php",

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

    success: function (data) {
      tab = data.split("+");

      var ligne =
        "<tr><td>0</td><td>" +
        tab[0] +
        "</td><td>" +
        tab[1] +
        "</td><td>" +
        tab[2] +
        "</td><td>" +
        tab[3] +
        "</td><td>" +
        tab[4] +
        "</td><td>En cours ...</td></tr>";

      $("table.tabVitrine").prepend(ligne);

      $("#uniteStock-" + id).prop("disabled", true);

      $("#prixUnitaire-" + id).prop("disabled", true);

      $("#prixuniteStock-" + id).prop("disabled", true);

      $("#init_Produit-" + id).prop("disabled", true);

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Vitrine PHAr**/

function spm_DesignationVT(id) {
  //alert(id);

  $.ajax({
    url: "ajax/vitrineAjax.php",

    method: "POST",

    data: {
      operation: 1,

      id: id,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#id_Spm").val(tab[0]);

      $("#designation_Spm").val(tab[1]);

      $("#designation_SpmE").val(tab[1]);

      $("#categorie_Spm").val(tab[2]);

      $("#uniteStock_Spm").val(tab[3]);

      $("#prixuniteStock_Spm").val(tab[4]);

      $("#prix_Spm").val(tab[5]);

      $("#img_Spm").val(tab[6]);

      $("#supprimerDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin supprimer Designation Vitrine**/

/**Debut modification produit Vitrine**/

function edit_DesignationVT(id) {
  //alert(id);

  $.ajax({
    url: "ajax/vitrineAjax.php",

    method: "POST",

    data: {
      operation: 1,

      id: id,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#id_edit").val(tab[0]);

      $("#designation_edit").val(tab[1]);

      $("#designation_editE").val(tab[2]);

      $("#categorie_edit").val(tab[3]);

      $("#uniteStock_edit").val(tab[4]);

      $("#prixuniteStock_edit").val(tab[5]);

      $("#prix_edit").val(tab[6]);

      $("#image_edit").val(tab[7]);

      $("#modifierDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modification produit Vitrine**/

/**Debut Uploader Image Nouvelle Designation Vitrine**/

function imgNV_DesignationVT(id) {
  $.ajax({
    url: "ajax/vitrineAjax.php",

    method: "POST",

    data: {
      operation: 1,

      id: id,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#id_Upd_Nv").val(tab[0]);

      $("#imageNvDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin Uploader Image Nouvelle  Designation Vitrine**/

/**Debut Uploader Image Existante Designation Vitrine**/

function imgEX_DesignationVT(id) {
  $.ajax({
    url: "ajax/vitrineAjax.php",

    method: "POST",

    data: {
      operation: 1,

      id: id,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#id_Upd_Ex").val(tab[0]);

      $("#des_Upd_Ex").val(tab[2]);

      $("#idB_Upd_Ex").val(tab[8]);

      $("#img_Upd_Ex").val(tab[7]);

      //$('#imgsrc_Upd').src='uploads/'+tab[6];

      $("#imgsrc_Upd").attr("src", "uploads/" + tab[7]);

      $("#imgsrc_Upd").attr("alt", tab[7]);

      $("#imageExDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $(".btnRetournerProduit").click(function () {
    var clicked = $(this);

    var clicked_id = clicked.attr("id");

    var splitId = clicked_id.split("_");

    var idArticle = splitId[0];

    var idPanier = splitId[1];

    // alert(clicked_id)

    var qtCommander = $("#quantite-" + idArticle).val();

    // alert(qtCommander)

    $("#qtRetourner-" + idArticle).on("input", function () {
      var qtRetourner = $("#qtRetourner-" + idArticle).val();

      // alert(qtCommander)

      if (parseInt(qtRetourner) > parseInt(qtCommander)) {
        $(".text-error").text(
          "Attention: la quantité saisie est supérieure à la quantité commandée."
        );

        $(".text-error").removeClass("hidden");
      } else {
        $(".text-error").text("");

        $(".text-error").addClass("hidden");
      }
    });

    $(".btnConfRetourProduit-" + clicked_id).click(function () {
      var qtRetourner = $("#qtRetourner-" + idArticle).val();

      // alert(qtRetourner)

      if (parseInt(qtCommander) >= parseInt(qtRetourner)) {
        $.ajax({
          url: "ajax/vitrineAjax.php",

          method: "POST",

          data: {
            operation: 2,

            idArticle: idArticle,

            idPanier: idPanier,

            qtCommander: qtCommander,

            qtRetourner: qtRetourner,
          },

          success: function (data) {
            // tab = data.split('<>');

            // alert(1)

            // console.log(1)

            window.location.href = "commande.php";
          },

          error: function () {
            // alert("La requête ");
          },

          dataType: "text",
        });
      } else {
        $(".text-error").text(
          "Attention: la quantité saisie est supérieure à la quantité commandée."
        );

        $(".text-error").removeClass("hidden");

        // alert('Vous ne pouvez pas modifier plus de la quantité commander.')
      }
    });
  });
});

/**Fin Uploader Image Existante Designation Vitrine**/

/************* Fin Modification vitrine***************/

$(function () {
  /*  $("#form").submit(function(event){

          event.preventDefault(); //prevent default action

          var post_url = $(this).attr("action"); //get form action url

          var request_method = $(this).attr("method"); //get form GET/POST method

          var form_data = $(this).serialize(); //Encode form elements for submission



          $.ajax({

              url : post_url,

              type: request_method,

              data : form_data

          }).done(function(response){ //

              $("#server-results").html(response);

          });

      });*/
});

$("#form").submit(function (event) {
  event.preventDefault(); // Empêcher le rechargement de la page.

  var formData = new FormData($("#myForm"));

  alert(formData);

  /* $.ajax({

       type: "POST",

       url: "traitement.php",

       data: formData

     });*/
});

function modif_Remise(remise, pagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 3,

      idPagnet: pagnet,

      remise: remise,
    },

    success: function (data) {
      //$('#somme_Pagnet').val(data);

      tab = data.split("<>");

      $("#somme_Apayer" + pagnet).text(
        tab[0] - remise - (tab[0] * tab[2]) / 100
      );

      valTotal = tab[0] - $("#val_remise" + pagnet).val();

      valApayer = tab[1] - $("#val_remise" + pagnet).val();

      $("#somme_TotalCN" + pagnet).text(valTotal.toFixed(2));

      $("#somme_ApayerCN" + pagnet).text(valApayer.toFixed(2));

      var lcd = $("#lcd_Machine").val();

      if (lcd == 1) {
        var nd_Apayer = remise;

        if (nd_Apayer != null && nd_Apayer != "") {
          var nd_Qte = "-";

          var nd_Prix = "" + nd_Apayer;

          $.ajax({
            url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

            type: "GET", // data type (can be get, post, put, delete)

            data: {
              quantite: nd_Qte,

              prix: nd_Prix,
            }, // data in json format

            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

            success: function (response) {
              console.log(response);
            },
          });
        }
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function masLigne(par, idpagn) {
  var idQuantite = "#quantite" + par;

  var idQuantiteOld = "#quantiteOld" + par;

  var idPrixunitevente = "#prixunitevente" + par;

  var idUnitevente = "#unitevente" + par;

  var idPagnet = idpagn;

  var numligne = par;

  var prixunitevente = $(idPrixunitevente).text();

  var unitevente = $(idUnitevente).text();

  // var prixunitevente= $(idPrixunitevente).find(".prixunitevente").text();

  $(idQuantite).focusout(function () {
    var quantite = $(this).val();

    var quantiteOld = $(idQuantiteOld).val();

    var idClient = $("#idClientF").val();

    console.log("uniteventeSC =" + unitevente);

    console.log("numligne =" + numligne);

    console.log("idClient =" + idClient);

    //alert('idPagnet'+idPagnet);

    //  $('#resultat2').text($(this).attr('id'));

    if (quantite != quantiteOld) {
      //alert('testffffffff par'+par);

      //alert('quantiteOld'+quantiteOld);

      $.ajax({
        url: "ajax/ajouterLigneAjax.php",

        method: "POST",

        data: {
          msaLigne: 1,

          prm: idPagnet,

          idC: idClient,

          nligne: numligne,

          uVente: unitevente,

          prUnitVente: prixunitevente,

          qtiteOld: quantiteOld,

          qtite: quantite,
        },

        success: function (data) {
          //$(idPrixunitevente).html(data);

          console.log("data id $newPrix " + data);
        },

        error: function () {
          alert("La requête n'a pas abouti");
        },

        dataType: "text",
      });
    }
  });
}

function remiseB(par) {
  console.log("remise" + par);

  var idPagnet = par;

  var idRemise = "#val_remise" + par;

  var idVersement = "#versement" + par;

  var idTotalp = "#totalp" + par;

  console.log("totalp" + totalp);

  var remise = $(idRemise).val();

  var versement = $(idVersement).val();

  var totalp = $(idTotalp).val();

  if (versement > 0) {
    //res += numligne+designation+quantite;

    // alert(11)

    console.log("remise" + remise);

    console.log("versement" + versement);

    console.log("totalp" + totalp);

    /*alert(remise);

        alert(apayerPagnet);*/

    $.ajax({
      url: "ajax/remiseAjax.php",

      method: "POST",

      data: {
        btnImprimerFacture: 1,

        prm: idPagnet,

        rms: remise,

        aPP: versement,

        ttp: totalp,
      },

      success: function (data) {
        // alert(data)

        console.log("data id 1 " + data);

        // alert(data);
      },

      dataType: "text",
    });
  } else {
    // alert(22)

    $.ajax({
      url: "ajax/remiseAjax.php",

      method: "POST",

      data: {
        btnImprimerFacturePagnet: 1,

        prm: idPagnet,

        rms: remise,

        ttp: totalp,
      },

      success: function (data) {
        // alert(data)

        console.log("data id 2" + data);

        // alert(data);
      },

      dataType: "text",
    });
  }

  // IMPRIMER

  /**/

  var idPanel = "#panel" + par;

  // $(idPanel).css('display',"block");

  // alert(idPanel);

  //window.print();

  console.log("idPanel" + idPanel);
}

function remiseBClient(par, clt) {
  console.log("remise" + par);

  var idPagnet = par;

  var idRemise = "#remise" + par;

  var idTotalp = "#totalp" + par;

  console.log("remise B Client");

  var remise = $(idRemise).val();

  var totalp = $(idTotalp).val();

  $.ajax({
    url: "ajax/remiseAjax.php",

    method: "POST",

    data: {
      btnImprimerFacturePagnet: 1,

      prm: idPagnet,

      idClient: clt,

      rms: remise,

      ttp: totalp,
    },

    success: function (data) {
      console.log("data id " + data);
    },

    dataType: "text",
  });
}

/*function remiseB(par){

	  console.log("remise"+par);

      var idPagnet=par;

      var idRemise="#remise"+par;

      var idVersement="#versement"+par;

      var idTotalp="#totalp"+par;



		//alert('idRemise=='+idRemise);

           var remise= $(idRemise).val();

           var versement= $(idVersement).val();

           var totalp= $(idTotalp).val();

		   //alert('hhhh'+remise);



           if (versement>0) {

			    if (remise < totalp) {

					   //res += numligne+designation+quantite;



				   console.log("remise"+remise);

				   console.log("versement"+versement);

				   console.log("totalp"+totalp);

				   //alert(remise);

				   //alert(apayerPagnet);

						 $.ajax({

							 url:'ajax/remiseAjax.php',

							 method:'POST',

							 data:{

							   btnImprimerFacture:1,

							   prm : idPagnet ,

							   rms : remise,

							   aPP : versement,

							   ttp : totalp

							 },

							 success: function (data) {



								 console.log('data id '+data);

								  //alert(data);

							 },

							 dataType:'text'

						});

				}

				else{

					remise=0;

				   console.log("remise"+remise);

				   console.log("versement"+versement);

				   console.log("totalp"+totalp);

				   //alert(remise);

				   //alert(apayerPagnet);

						 $.ajax({

							 url:'ajax/remiseAjax.php',

							 method:'POST',

							 data:{

							   btnImprimerFacture:1,

							   prm : idPagnet ,

							   rms : remise,

							   aPP : versement,

							   ttp : totalp

							 },

							 success: function (data) {



								 console.log('data id '+data);

								  //alert(data);

							 },

							 dataType:'text'

						});

				}



           }else {

				   if (remise < totalp) {

						$.ajax({

							 url:'ajax/remiseAjax.php',

							 method:'POST',

							 data:{

							   btnImprimerFacturePagnet:1,

							   prm : idPagnet ,

							   rms : remise,

							   ttp : totalp

							 },

							 success: function (data) {



								 console.log('data id '+data);

								  //alert(data);

							 },

							 dataType:'text'

						});

				   }

				   else{

						   remise=0;

						   $.ajax({

								 url:'ajax/remiseAjax.php',

								 method:'POST',

								 data:{

								   btnImprimerFacturePagnet:1,

								   prm : idPagnet ,

								   rms : remise,

								   ttp : totalp

								 },

								 success: function (data) {



									 console.log('data id '+data);

									 // alert(data);

								 },

								 dataType:'text'

							});

				   }

           }





           // IMPRIMER

         //  var idPanel="#panel"+par;

          // $(idPanel).css('display',"block");

          // alert(idPanel);

           //window.print();

}*/

function display(idPagnet) {
  var idPanel = "#panel" + idPagnet;

  var idItem = "#item" + idPagnet;

  /*$('.panel').css('display',"none");

    $(idPanel).css('display',"block");*/

  $(".panel").attr("class", "panel panel-info noImpr");

  $(".panel-collapse").attr("class", "panel-collapse collapse");

  $(idPanel).attr("class", "panel panel-info");

  // $(idItem).attr('class',"panel-collapse collapse in");
}

function displayVersement(idVersement) {
  var idPanel = "#panel" + idVersement;

  var idItem = "#item2" + idVersement;

  $(".panel").attr("class", "panel panel-info noImpr");

  $(".panel-collapse").attr("class", "panel-collapse collapse");

  $(idPanel).attr("class", "panel panel-info");
}

$(function () {
  $("#formulaireAjouterPagnet").submit(function () {
    resultat = true;

    if ($("#idPagnetClientActiver").val() == 0) {
      $("#helpActiver").text("Operation impossible: ce client est desactivé");

      resultat = false;
    }

    return resultat;
  });
});

$(function () {
  $("#btnImprimerCB").click(function () {
    $('div[id*="demo"]').each(function () {
      var idR = $(this).attr("id");

      /* var content = document.getElementById(idR).innerHTML;

             var mywindow = window.open('', 'Print', 'height=600,width=800');



             mywindow.document.write('<html><head><title>Print</title>');

             mywindow.document.write('</head><body >');

             mywindow.document.write(content);

             mywindow.document.write('</body></html>');



             mywindow.document.close();

             mywindow.focus()

             mywindow.print();

             mywindow.close();

             return true;*/

      var restorepage = document.body.innerHTML;

      var printcontent = document.getElementById(idR).innerHTML;

      document.body.innerHTML = printcontent;

      window.print();

      document.body.innerHTML = restorepage;

      window.close();
    });

    //alert("il y a "+nombreDiv+" div commençant par txt_value_");
  });
});

function printElem(divId) {
  var content = document.getElementById(divId).innerHTML;

  var mywindow = window.open("", "Print", "height=600,width=800");

  mywindow.document.write("<html><head><title>Print</title>");

  mywindow.document.write("</head><body >");

  mywindow.document.write(content);

  mywindow.document.write("</body></html>");

  mywindow.document.close();

  mywindow.focus();

  mywindow.print();

  mywindow.close();

  return true;
}

/**Debut supprimer Designation Vitrine**/

/**Fin Uploader Image Existante Designation Vitrine**/

/**Debut ajouter Stock BL Entrepot Import-Export**/

function ajt_Stock_Bl_ET_IE(designation, idBl) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var prixAchat = $("#prixAchat-" + designation).val();

  var prixUniteStock = $("#prixUniteStock-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  $("#btn_AjtStock_P-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 55,

      idDesignation: designation,

      idBl: idBl,

      quantite: quantite,

      prixAchat: prixAchat,

      prixUniteStock: prixUniteStock,

      dateExpiration: dateExpiration,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr id='stock'><td>0</td><td>" +
          tab[1] +
          "</td><td>" +
          tab[2] +
          "</td><td>" +
          quantite +
          "</td><td>" +
          quantite +
          "</td><td>" +
          prixAchat +
          "</td><td>" +
          prixUniteStock +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          dateExpiration +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#stock").css({ "background-color": "green" });

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else {
        $("#msg_info_js").modal("show");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Stock BL Entrepot  Import-Export**/

/**Debut ajouter Voyage**/

$(function () {
  $("#btn_ajt_Voyage").click(function () {
    var destination = $("#ajtDestination").val();

    var motif = $("#ajtMotif").val();

    var dateVoyage = $("#ajtDateVoyage").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 70,

        destination: destination,

        motif: motif,

        dateVoyage: dateVoyage,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            "</td><td>" +
            tab[3] +
            "</td><td>En cours ...</td></tr>";

          $("table.tabVoyage").prepend(ligne);

          $("#ajtDestination").val("");

          $("#ajtMotif").val("");

          $("#ajtDateVoyage").val("");

          $("#ajoutVoyage").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter Voyage**/

/**Debut modifier Voyage**/

function mdf_Voyage(idVoyage, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 71,

      idVoyage: idVoyage,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idVY_Mdf").val(tab[1]);

        $("#ordreVY_Mdf").val(ordre);

        $("#destinationVY_Mdf").val(tab[2]);

        $("#motifVY_Mdf").val(tab[3]);

        $("#dateVY_Mdf").val(tab[4]);

        $("#modifierVoyage").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Voyage").click(function () {
    var idVoyage = $("#idVY_Mdf").val();

    var ordre = $("#ordreVY_Mdf").val();

    var destination = $("#destinationVY_Mdf").val();

    var motif = $("#motifVY_Mdf").val();

    var dateVoyage = $("#dateVY_Mdf").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 72,

        idVoyage: idVoyage,

        destination: destination,

        motif: motif,

        dateVoyage: dateVoyage,
      },

      success: function (data) {
        $("#idVY_Mdf").val("");

        $("#ordreVY_Mdf").val("");

        $("#destinationVY_Mdf").val("");

        $("#motifVY_Mdf").val("");

        $("#dateVY_Mdf").val("");

        $("#modifierVoyage").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableVoyage tr").each(function (row, tr) {
            fournisseur = $(tr).find("td:eq(0)").text();

            if (fournisseur != "" && fournisseur == ordre) {
              $(tr).find("td:eq(1)").html(tab[2]);

              $(tr).find("td:eq(2)").html(tab[3]);

              $(tr).find("td:eq(3)").html(tab[4]);

              $(tr).find("td:eq(4)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Voyage**/

/**Debut supprimer Voyage**/

function spm_Voyage(idVoyage, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 71,

      idVoyage: idVoyage,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idVY_Spm").val(tab[1]);

        $("#ordreVY_Spm").val(ordre);

        $("#destinationVY_Spm").val(tab[2]);

        $("#motifVY_Spm").val(tab[3]);

        $("#dateVY_Spm").val(tab[4]);

        $("#supprimerVoyage").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Voyage").click(function () {
    var idVoyage = $("#idVY_Spm").val();

    var ordre = $("#ordreVY_Spm").val();

    var destination = $("#destinationVY_Spm").val();

    var motif = $("#motifVY_Spm").val();

    var dateVoyage = $("#dateVY_Spm").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 73,

        idVoyage: idVoyage,
      },

      success: function (data) {
        $("#idVY_Spm").val("");

        $("#ordreVY_Spm").val("");

        $("#destinationVY_Spm").val("");

        $("#motifVY_Spm").val("");

        $("#dateVY_Spm").val("");

        $("#supprimerVoyage").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableVoyage tr").each(function (row, tr) {
            fournisseur = $(tr).find("td:eq(0)").text();

            if (fournisseur != "" && fournisseur == ordre) {
              $(tr).find("td:eq(1)").html(destination);

              $(tr).find("td:eq(2)").html(motif);

              $(tr).find("td:eq(3)").html(dateVoyage);

              $(tr).find("td:eq(4)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Voyage**/

/*$('#autocomplete').autocomplete({

    serviceUrl: 'ajouterStock.php'



});*/

$(document).ready(function ($) {
  $("#numTel").mask("99 999 99 99");

  $("#refTransf").mask("AANNNNNN.NNNN.ANNNNN", {
    translation: {
      A: { pattern: /[A-Za-z]/ },

      N: { pattern: /[0-9]/ },
    },
  });

  $("#dateTransfert").mask("00/00/0000");

  $("#refTransf").keyup(function () {
    var query = $("#refTransf").val();

    if (query.length >= 2) {
      //var text=query.charAt(0).toUpperCase();

      console.log("fff" + query.toUpperCase());

      //$("#refTransf").html();

      $("#refTransf").val(query.toUpperCase());
    } else {
    }
  });

  $("#refTransf_Rtr").mask("AANNNNNN.NNNN.ANNNNN", {
    translation: {
      A: { pattern: /[A-Za-z]/ },

      N: { pattern: /[0-9]/ },
    },
  });

  $("#refTransf_Rtr").keyup(function () {
    var query = $("#refTransf_Rtr").val();

    if (query.length >= 2) {
      //var text=query.charAt(0).toUpperCase();

      console.log("fff" + query.toUpperCase());

      //$("#refTransf").html();

      $("#refTransf_Rtr").val(query.toUpperCase());
    } else {
    }
  });
});

function masqueSaisieDate(obj) {
  var ch;

  var ch_gauche, ch_droite;

  ch = obj.value;

  ch.toString();

  if (ch.slice(2, 3) != "-" && ch.length >= 3) {
    ch_gauche = ch.slice(0, 2);

    ch_droite = ch.slice(2);

    if (31 >= ch_gauche) {
      if (1 >= ch_droite) {
        obj.value = ch_gauche + "-" + ch_droite;
      } else {
        obj.value = ch_gauche + "-1";
      }
    } else {
      if (1 >= ch_droite) {
        obj.value = "31-" + ch_droite;
      } else {
        obj.value = "31-1";
      }
    }
  }

  if (ch.slice(5, 6) != "-" && ch.length >= 6) {
    ch_gauche = ch.slice(0, 5);

    ch_droite = ch.slice(5);

    if (12 >= ch.slice(3, 5)) {
      obj.value = ch_gauche + "-" + ch_droite;
    } else {
      obj.value = ch.slice(0, 2) + "-12-" + ch_droite;
    }
  }

  return;
}

/**Debut ajouter Mutuelle Pharmacie**/

$(function () {
  $("#btn_ajt_Mutuelle").click(function () {
    var nom = $("#ajtNomMT").val();

    var taux = $("#ajtTauxMT").val();
    var taux1 = $("#ajtTauxMT1").val();
    var taux2 = $("#ajtTauxMT2").val();
    var taux3 = $("#ajtTauxMT3").val();
    var taux4 = $("#ajtTauxMT4").val();

    var adresse = $("#ajtAdresseMT").val();

    var telephone = $("#ajtTelephoneMT").val();

    $.ajax({
      url: "ajax/operationAjax_Mutuelle.php",

      method: "POST",

      data: {
        operation: 1,

        nomMutuelle: nom,

        tauxMutuelle: taux,
        taux1: taux1,
        taux2: taux2,
        taux3: taux3,
        taux4: taux4,

        adresseMutuelle: adresse,

        telephoneMutuelle: telephone,
      },

      success: function (data) {
        tab = data.split("<>");

        if (tab[0] == 1) {
          var ligne =
            "<tr><td>0</td><td>" +
            tab[1] +
            "</td><td>" +
            tab[2] +
            " % - " +
            tab[5] +
            " % - " +
            tab[6] +
            " % - " +
            tab[7] +
            " % - " +
            tab[8] +
            " % </td><td>" +
            tab[3] +
            "</td><td>" +
            tab[4] +
            "</td><td>0</td><td>En cours ...</td></tr>";

          $("table.tabMutuelle").prepend(ligne);

          $("#ajtNomMT").val("");

          $("#ajtTauxMT").val("");
          $("#ajtTauxMT1").val("");
          $("#ajtTauxMT2").val("");
          $("#ajtTauxMT3").val("");
          $("#ajtTauxMT4").val("");

          $("#ajtAdresseMT").val("");

          $("#ajtTelephoneMT").val("");

          $("#ajoutMutuelle").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter Mutuelle Pharmacie**/

/**Debut modifier Mutuelle Pharmacie**/

function mdf_Mutuelle(idMutuelle, ordre) {
  $.ajax({
    url: "ajax/operationAjax_Mutuelle.php",

    method: "POST",

    data: {
      operation: 2,

      idMutuelle: idMutuelle,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idMT_Mdf").val(tab[1]);

        $("#ordreMT_Mdf").val(ordre);

        $("#nomMT_Mdf").val(tab[2]);

        $("#tauxMT_Mdf").val(tab[3]);
        $("#tauxMT_Mdf1").val(tab[6]);
        $("#tauxMT_Mdf2").val(tab[7]);
        $("#tauxMT_Mdf3").val(tab[8]);
        $("#tauxMT_Mdf4").val(tab[9]);

        $("#adresseMT_Mdf").val(tab[4]);

        $("#telephoneMT_Mdf").val(tab[5]);

        $("#modifierMutuelle").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_mdf_Mutuelle").click(function () {
    var idMutuelle = $("#idMT_Mdf").val();

    var ordre = $("#ordreMT_Mdf").val();

    var nom = $("#nomMT_Mdf").val();

    var taux = $("#tauxMT_Mdf").val();
    var taux1 = $("#tauxMT_Mdf1").val();
    var taux2 = $("#tauxMT_Mdf2").val();
    var taux3 = $("#tauxMT_Mdf3").val();
    var taux4 = $("#tauxMT_Mdf4").val();

    var adresse = $("#adresseMT_Mdf").val();

    var telephone = $("#telephoneMT_Mdf").val();

    $.ajax({
      url: "ajax/operationAjax_Mutuelle.php",

      method: "POST",

      data: {
        operation: 3,

        idMutuelle: idMutuelle,

        nomMutuelle: nom,

        tauxMutuelle: taux,
        taux1: taux1,
        taux2: taux2,
        taux3: taux3,
        taux4: taux4,

        adresseMutuelle: adresse,

        telephoneMutuelle: telephone,
      },

      success: function (data) {
        $("#idMT_Mdf").val("");

        $("#ordreMT_Mdf").val("");

        $("#nomMT_Mdf").val("");

        $("#tauxMT_Mdf").val("");
        $("#tauxMT_Mdf1").val("");
        $("#tauxMT_Mdf2").val("");
        $("#tauxMT_Mdf3").val("");
        $("#tauxMT_Mdf4").val("");

        $("#adresseMT_Mdf").val("");

        $("#telephoneMT_Mdf").val("");

        $("#modifierMutuelle").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableMutuelle tr").each(function (row, tr) {
            mutuelle = $(tr).find("td:eq(0)").text();

            if (mutuelle != "" && mutuelle == ordre) {
              $(tr).find("td:eq(1)").html(tab[2]);

              $(tr)
                .find("td:eq(2)")
                .html(
                  tab[3] +
                    " % - " +
                    tab[7] +
                    " % - " +
                    tab[8] +
                    " % - " +
                    tab[9] +
                    " % - " +
                    tab[10] +
                    " %"
                );

              $(tr).find("td:eq(3)").html(tab[4]);

              $(tr).find("td:eq(4)").html(tab[5]);

              $(tr).find("td:eq(5)").html(tab[6]);

              $(tr).find("td:eq(6)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin modifier Mutuelle Pharmacie**/

/**Debut supprimer Mutuelle Pharmacie**/

function spm_Mutuelle(idMutuelle, ordre) {
  $.ajax({
    url: "ajax/operationAjax_Mutuelle.php",

    method: "POST",

    data: {
      operation: 2,

      idMutuelle: idMutuelle,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idMT_Spm").val(tab[1]);

        $("#ordreMT_Spm").val(ordre);

        $("#nomMT_Spm").val(tab[2]);

        $("#tauxMT_Spm").val(
          tab[3] +
            " % - " +
            tab[6] +
            " % - " +
            tab[7] +
            " % - " +
            tab[8] +
            " % - " +
            tab[9] +
            " %"
        );

        $("#adresseMT_Spm").val(tab[4]);

        $("#telephoneMT_Spm").val(tab[5]);

        $("#supprimerMutuelle").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_spm_Mutuelle").click(function () {
    var idMutuelle = $("#idMT_Spm").val();

    var ordre = $("#ordreMT_Spm").val();

    var nom = $("#nomMT_Spm").val();

    var taux = $("#tauxMT_Spm").val();

    var adresse = $("#adresseMT_Spm").val();

    var telephone = $("#telephoneMT_Spm").val();

    $.ajax({
      url: "ajax/operationAjax_Mutuelle.php",

      method: "POST",

      data: {
        operation: 4,

        idMutuelle: idMutuelle,
      },

      success: function (data) {
        $("#idMT_Spm").val("");

        $("#ordreMT_Spm").val("");

        $("#nomMT_Spm").val("");

        $("#tauxMT_Spm").val("");

        $("#adresseMT_Spm").val("");

        $("#telephoneMT_Spm").val("");

        $("#supprimerMutuelle").modal("hide");

        tab = data.split("<>");

        if (tab[0] == 1) {
          $("#tableMutuelle tr").each(function (row, tr) {
            mutuelle = $(tr).find("td:eq(0)").text();

            if (mutuelle != "" && mutuelle == ordre) {
              $(tr).find("td:eq(1)").html(nom);

              $(tr).find("td:eq(2)").html(taux);

              $(tr).find("td:eq(3)").html(adresse);

              $(tr).find("td:eq(4)").html(telephone);

              $(tr).find("td:eq(5)").html(0);

              $(tr).find("td:eq(6)").html("En cours ...");
            }
          });
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin supprimer Mutuelle Pharmacie**/

/** Debut Autocomplete Mutuelle Pharmacie*/

$(function () {
  $(".codeBarreLigneMutuelle").keyup(function (e) {
    e.preventDefault();

    var tabIdPanier = $(this).attr("id");

    tab = tabIdPanier.split("_");

    idMutuellePagnet = tab[1];

    var query = $(this).val();

    if (query.length > 0) {
      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 20,

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
              alert("La requête ss");
            },
          });
        },
      });

      $(this).focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        inputVal = $(this).val();

        if ($.isNumeric(inputVal)) {
          designation = $(this).val();
        } else {
          var designation = $(
            "#ajouterProdFormMutuelle" +
              idMutuellePagnet +
              " .typeahead li.active"
          ).text();

          tab = designation.split(" => ");

          designation = tab[0];
        }

        // var designation = $("#ajouterProdFormMutuelle"+idMutuellePagnet+" .typeahead li.active").text();

        // tab = designation.split(' => ');

        // designation = tab[0] || $("#panier_"+idMutuellePagnet).val();

        $.ajax({
          url: "ajax/vendreLigneAjax.php",

          method: "POST",

          data: {
            operation: 21,

            designation: designation,

            idMutuellePagnet: idMutuellePagnet,
          },

          success: function (data) {
            result = data.split("<>");

            if (result[0] == 1) {
              if (result[7] == 9) {
                var ligne =
                  "<tr>" +
                  "<td>" +
                  result[2] +
                  "</td>" +
                  "<td>Montant</td>" +
                  "<td>" +
                  result[3] +
                  "</td>" +
                  "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," +
                  result[5] +
                  "," +
                  idMutuellePagnet +
                  ")' value='" +
                  result[4] +
                  "' style='width: 70%' type='number'></input></td>" +
                  "<td>" +
                  "<button type='button' onclick='retour_ProduitPh(" +
                  result[5] +
                  "," +
                  idMutuellePagnet +
                  ")'	 class='btn btn-warning pull-right'>" +
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
                  "<td><input class='form-control' onkeyup='modif_Quantite_Mutuelle(this.value," +
                  result[5] +
                  "," +
                  idMutuellePagnet +
                  ")' value='1' style='width: 70%' type='number' ></input></td>" +
                  "<td>" +
                  result[3] +
                  "</td>" +
                  "<td><input class='form-control' onkeyup='modif_Prix_Mutuelle(this.value," +
                  result[5] +
                  "," +
                  idMutuellePagnet +
                  ")' value='" +
                  result[4] +
                  "' style='width: 70%' type='number'></input></td>" +
                  "<td>" +
                  "<button type='button' onclick='retour_Produit_Mutuelle(" +
                  result[5] +
                  "," +
                  idMutuellePagnet +
                  ")'	 class='btn btn-warning pull-right'>" +
                  "<span class='glyphicon glyphicon-remove'></span>Retour" +
                  "</button>" +
                  "</td>" +
                  "</tr>";
              }

              $("#tableMutuelle" + idMutuellePagnet).prepend(ligne);

              $("#panier_" + idMutuellePagnet).val("");

              $("#somme_Total" + idMutuellePagnet).text(result[6]);

              $("#somme_Apayer" + idMutuellePagnet).text(result[6] / 2);

              $("#somme_Apayer" + idMutuellePagnet).text(
                result[6] - (result[6] * result[7]) / 100
              );
            }

            if (result[0] == 2) {
              $("#tableMutuelle" + idMutuellePagnet + " tr").each(function (
                row,
                tr
              ) {
                reference = $(tr).find("td:eq(0)").text();

                if (reference != "" && reference == result[2]) {
                  $(tr)
                    .find("td:eq(1)")
                    .html(
                      "<input class='form-control' onkeyup='modif_Quantite_Mutuelle(this.value," +
                        result[5] +
                        "," +
                        idMutuellePagnet +
                        ")' value='" +
                        result[7] +
                        "' style='width: 70%' type='number'></input>"
                    );

                  $("#panier_" + idMutuellePagnet).val("");

                  $("#somme_Total" + idMutuellePagnet).text(result[6]);

                  $("#somme_Apayer" + idMutuellePagnet).text(
                    result[6] - (result[6] * result[8]) / 100
                  );
                }
              });
            }

            if (result[0] == 3) {
              $("#qte_stock").text(result[1]);

              $("#msg_info_js").modal("show");

              $("#panier_" + idMutuellePagnet).val("");
            }
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });

        idFormParent = $("#panier_" + idMutuellePagnet)
          .parent()
          .attr("id");

        // alert(idFormParent+'/////////')

        // setTimeout(() => {

        $("#" + idFormParent + " .typeahead").html("");
      }

      /*********** Fin tape sur Entrer **************/
    } else {
      idFormParent = $("#panier_" + idMutuellePagnet)
        .parent()
        .attr("id");

      // alert(idFormParent+'/////////')

      // setTimeout(() => {

      $("#" + idFormParent + " .typeahead").html("");
    }
  });
});

/** Fin Autocomplete Mutuelle Pharmacie*/

/**Debut modifier la quantite dans la ligne d'un Pagnet Mutuelle Pharmacie**/

function modif_Quantite_Mutuelle(quantite, ligne, idMutuellePagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 25,

      idMutuellePagnet: idMutuellePagnet,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      result = data.split("<>");

      ligneQte = result[0];

      //alert("Quantite : "+ligneQte);

      restant = ligneQte - quantite;

      //alert("Restant : "+restant);

      if (restant >= 0) {
        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 26,

            idMutuellePagnet: idMutuellePagnet,

            numLigne: ligne,

            quantite: quantite,
          },

          success: function (data) {
            tab1 = data.split("<>");

            if (tab1[0] == 1) {
              $("#tauxMutuelle" + idMutuellePagnet).val(tab1[1] + " %");

              $("#somme_Total" + idMutuellePagnet).text(tab1[2]);

              $("#somme_Apayer" + idMutuellePagnet).text(
                tab1[2] - (tab1[2] * tab1[1]) / 100
              );
            }
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });
      } else {
        reste = ligneQte;

        $("#qte_stock").text(reste);

        $("#msg_info_js").modal("show");

        quantite = 1;

        $.ajax({
          url: "ajax/modifierLigneAjax.php",

          method: "POST",

          data: {
            operation: 27,

            idMutuellePagnet: idMutuellePagnet,

            numLigne: ligne,

            quantite: 1,
          },

          success: function (data) {
            tab1 = data.split("<>");

            if (tab1[0] == 1) {
              $("#tauxMutuelle" + idMutuellePagnet).val(tab1[1] + " %");

              $("#somme_Total" + idMutuellePagnet).text(tab1[2]);

              $("#somme_Apayer" + idMutuellePagnet).text(
                tab1[2] - (tab1[2] * tab1[1]) / 100
              );

              $("#tableMutuelle tr").each(function (row, tr) {
                reference = $(tr).find("td:eq(0)").text();

                if (reference != "" && reference == tab1[3]) {
                  //alert(reference);

                  $(tr)
                    .find("td:eq(1)")
                    .html(
                      "<input class='form-control' onkeyup='modif_Quantite_Mutuelle(" +
                        quantite +
                        "," +
                        ligne +
                        "," +
                        idMutuellePagnet +
                        ")' value='1' style='width: 70%' type='number'></input>"
                    );

                  $(".licM").text("");

                  $("#codeBarreLigneMutuelle").val("");

                  $("#codeBarreLigneMutuelle").focus();
                }
              });
            }

            //console.log(data);
          },

          error: function () {
            alert("La requête ");
          },

          dataType: "text",
        });

        $("#ligne" + ligne).val(1);
      }

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet Mutuelle Pharmacie**/

/**Debut modifier le prix dans la ligne d'un Pagnet Mutuelle Pharmacie**/

function modif_Prix_Mutuelle(prix, ligne, idMutuellePagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 24,

      idMutuellePagnet: idMutuellePagnet,

      numLigne: ligne,

      prix: prix,
    },

    success: function (data) {
      tab1 = data.split("<>");

      if (tab1[0] == 1) {
        $("#tauxMutuelle" + idMutuellePagnet).val(tab1[1] + " %");

        $("#somme_Total" + idMutuellePagnet).text(tab1[2]);

        $("#somme_Apayer" + idMutuellePagnet).text(
          tab1[2] - (tab1[2] * tab1[1]) / 100
        );
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la prix dans la ligne d'un Pagnet Mutuelle Pharmacie**/

/**Debut retour d'un produit dans Pagnet Mutuelle Pharmacie**/

function retour_Produit_Mutuelle(ligne, idMutuellePagnet) {
  $.ajax({
    url: "ajax/vendreLigneAjax.php",

    method: "POST",

    data: {
      operation: 22,

      idMutuellePagnet: idMutuellePagnet,

      numligne: ligne,
    },

    success: function (data) {
      result = data.split("<>");

      if (result[0] == 1) {
        $("#tableMutuelle tr").each(function (row, tr) {
          reference = $(tr).find("td:eq(0)").text();

          if (reference != "" && reference == result[2]) {
            $(tr).remove();

            $(".licM").text("");

            $("#codeBarreLigneMutuelle").val("");

            $("#codeBarreLigneMutuelle").focus();

            $("#somme_Total" + idMutuellePagnet).text(result[6]);

            $("#somme_Apayer" + idMutuellePagnet).text(
              result[6] - (result[6] * result[7]) / 100
            );
          }
        });
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin retour d'un produit dans Pagnet Mutuelle Pharmacie**/

/** Debut modifier le nom du Client d'un Pagnet Mutuelle Pharmacie*/

$(function () {
  $(".clientMutuelleInput").keyup(function (e) {
    e.preventDefault();

    typeClient = "mutuelle";

    var query = $(this).val();

    var idMutuellePagnet = $(this).attr("data-idPanier");

    if (query.length > 0) {
      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 23,

              query: query,

              idMutuellePagnet: idMutuellePagnet,
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
              alert("La requête ss");
            },
          });
        },
      });

      $(this).focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        var client = $(
          "#ajouterProdFormMutuelle" +
            idMutuellePagnet +
            " .reponseClient .typeahead li.active"
        ).text();

        $.ajax({
          url: "ajax/vendreLigneAjax.php",

          method: "POST",

          data: {
            operation: 24,

            client: client,

            idMutuellePagnet: idMutuellePagnet,
          },

          success: function (data) {
            $(this).val(client);
          },

          dataType: "text",
        });

        typeClient = "";
      }

      /*********** Fin tape sur Entrer **************/
    }
  });
});

/** Fin modifier le nom du Client d'un Pagnet Mutuelle Pharmacie*/

/** Debut modifier le code Adherant du Client d'un Pagnet Mutuelle Pharmacie*/

$(function () {
  $("#codeAdherantMutuelle").keyup(function () {
    var idMutuellePagnet = $("#idMutuellePagnet").val();

    var query = $("#codeAdherantMutuelle").val();

    if (query.length > 0 || query.length != "") {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 25,

          codeAdherant: query,

          idMutuellePagnet: idMutuellePagnet,
        },

        success: function (data) {},

        dataType: "text",
      });
    }
  });
});

/** Fin modifier le code Adherant du Client d'un Pagnet Mutuelle Pharmacie*/

/**Debut modifier la mutuelle d'un Pagnet Mutuelle Pharmacie**/

function modif_MutuellePagnet(mutuelle) {
  tab = mutuelle.split("§");

  var idMutuelle = tab[0];

  var idMutuellePagnet = tab[1];

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 29,

      idMutuelle: idMutuelle,

      idMutuellePagnet: idMutuellePagnet,
    },

    success: function (data) {
      $(".multiMutuelle option").remove();

      tab1 = data.split("<>");

      if (tab1[0] == 1) {
        $("#tauxMutuelle" + idMutuellePagnet).val(tab1[1] + " %");

        $("#somme_Total" + idMutuellePagnet).text(tab1[2]);

        $("#somme_Apayer" + idMutuellePagnet).text(
          tab1[2] - (tab1[2] * tab1[1]) / 100
        );

        $(".multiMutuelle").append(
          "<option value='" +
            tab1[1] +
            "§" +
            tab1[7] +
            "§" +
            tab1[8] +
            "'>" +
            tab1[1] +
            "  %</option>"
        );
        if (tab1[3] != 0) {
          $(".multiMutuelle").append(
            "<option value='" +
              tab1[3] +
              "§" +
              tab1[7] +
              "§" +
              tab1[8] +
              "'>" +
              tab1[3] +
              "  %</option>"
          );
        }
        if (tab1[4] != 0) {
          $(".multiMutuelle").append(
            "<option value='" +
              tab1[4] +
              "§" +
              tab1[7] +
              "§" +
              tab1[8] +
              "'>" +
              tab1[4] +
              "  %</option>"
          );
        }
        if (tab1[5] != 0) {
          $(".multiMutuelle").append(
            "<option value='" +
              tab1[5] +
              "§" +
              tab1[7] +
              "§" +
              tab1[8] +
              "'>" +
              tab1[5] +
              "  %</option>"
          );
        }
        if (tab1[6] != 0) {
          $(".multiMutuelle").append(
            "<option value='" +
              tab1[6] +
              "§" +
              tab1[7] +
              "§" +
              tab1[8] +
              "'>" +
              tab1[6] +
              "  %</option>"
          );
        }
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function modif_MutuelleTaux(taux) {
  tab = taux.split("§");

  var tauxMutuelle = tab[0];
  var idMutuelle = tab[1];
  var idMutuellePagnet = tab[2];

  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 33,

      idMutuelle: idMutuelle,
      tauxMutuelle: tauxMutuelle,
      idMutuellePagnet: idMutuellePagnet,
    },

    success: function (data) {
      tab1 = data.split("<>");

      if (tab1[0] == 1) {
        $("#somme_Total" + idMutuellePagnet).text(tab1[2]);

        $("#somme_Apayer" + idMutuellePagnet).text(
          tab1[2] - (tab1[2] * tab1[1]) / 100
        );
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Debut modifier la mutuelle d'un Pagnet Mutuelle Pharmacie**/

/**Debut ajouter Designation Boutique**/

function ajt_Reference() {
  var designation = $("#designation").val();

  var categorie = $("#categorie2").val();

  var uniteStock = $("#uniteStock").val();

  var nbreArticleUniteStock = $("#nbArticleUniteStock").val();

  var prixUniteStock = $("#prixuniteStock").val();

  var prixUnitaire = $("#prix").val();

  var prixAchat = $("#prixachat").val();

  var codeBarre = $("#codeBarre").val();

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 92,

      designation: designation,

      categorie: categorie,

      uniteStock: uniteStock,

      nbreArticleUniteStock: nbreArticleUniteStock,

      prixUniteStock: prixUniteStock,

      prixUnitaire: prixUnitaire,

      prixAchat: prixAchat,

      codeBarre: codeBarre,
    },

    success: function (data) {
      tab = data.split("+");

      var ligne =
        "<tr id='design'><td>0</td><td>" +
        tab[0] +
        "</td><td>" +
        tab[1] +
        "</td><td>" +
        tab[2] +
        "</td><td>" +
        tab[3] +
        "</td><td>" +
        tab[4] +
        "</td><td>" +
        tab[5] +
        "</td><td>" +
        tab[6] +
        "</td><td>En cours ...</td></tr>";

      $("table.tabDesign").prepend(ligne);

      $("#design").css({ "background-color": "green" });

      $("#designation").val("");

      $("#categorie2").val("");

      $("#uniteStock").val("");

      $("#nbArticleUniteStock").val("");

      $("#prixuniteStock").val("");

      $("#prix").val("");

      $("#prixachat").val("");

      $("#codeBarre").val("");

      $("#AjoutStockModal").modal("hide");

      $("#ajt_Stock").modal("show");

      $("#designation_Stock").val(designation);

      $("#uniteStock_Stock_Option").text(uniteStock);

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Designation Boutique**/

/**Debut ajouter Designation Entrepot**/

function ajt_Reference_E() {
  var designation = $("#designation").val();

  var categorie = $("#categorie2").val();

  var uniteStock = $("#uniteStock").val();

  var uniteDetails = $("#uniteDetails").val();

  var nbreArticleUniteStock = $("#nbArticleUniteStock").val();

  var prixUniteStock = $("#prixuniteStock").val();

  var prixAchat = $("#prixachat").val();

  // var codeBarre = $('#codeBarre').val();

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 93,

      designation: designation,

      categorie: categorie,

      uniteStock: uniteStock,

      uniteDetails: uniteDetails,

      nbreArticleUniteStock: nbreArticleUniteStock,

      prixUniteStock: prixUniteStock,

      prixAchat: prixAchat,
    },

    success: function (data) {
      tab = data.split("+");

      var ligne =
        "<tr id='design'><td>0</td><td>" +
        tab[0] +
        "</td><td>" +
        tab[1] +
        "</td><td>" +
        tab[2] +
        "</td><td>" +
        tab[3] +
        "</td><td>" +
        tab[4] +
        "</td><td>" +
        tab[5] +
        "</td><td>En cours ...</td></tr>";

      $("table.tabDesign").prepend(ligne);

      $("#design").css({ "background-color": "green" });

      $("#designation").val("");

      $("#categorie2").val("");

      $("#uniteStock").val("");

      $("#uniteDetails").val("");

      $("#nbArticleUniteStock").val("");

      $("#prixuniteStock").val("");

      $("#prixachat").val("");

      $("#AjoutStockModal").modal("hide");

      $("#ajt_Stock").modal("show");

      $("#designation_Stock").val(designation);

      $("#uniteStock_Stock_Option").text(uniteStock);

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Designation Boutique**/

/**Debut ajouter Stock Entrepot**/

$(function () {
  $("#btn_ajt_StockCatalogue_Et").click(function () {
    var designation = $("#designation_Stock").val();

    var uniteStock = $("#uniteStock_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 94,

        designation: designation,

        uniteStock: uniteStock,

        quantite: quantite,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        if (data == 1) {
          $("#qteInitial_Stock").val("");

          $("#dateExpiration_Stock").val("");

          $("#btn_ajt_StockCatalogue").prop("disabled", false);
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

$(function () {
  $("#btn_trm_StockCatalogue_Et").click(function () {
    var designation = $("#designation_Stock").val();

    var uniteStock = $("#uniteStock_Stock").val();

    var quantite = $("#qteInitial_Stock").val();

    var dateExpiration = $("#dateExpiration_Stock").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 94,

        designation: designation,

        uniteStock: uniteStock,

        quantite: quantite,

        dateExpiration: dateExpiration,
      },

      success: function (data) {
        if (data == 1) {
          $("#qteInitial_Stock").val("");

          $("#dateExpiration_Stock").val("");

          $("#ajt_Stock").modal("hide");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  });
});

/**Fin ajouter Stock Entrepot**/

/**Debut modifier Categorie**/

function mdf_Categorie(idCategorie, ordre) {
  $.ajax({
    url: "ajax/operationAjax_Categorie.php",

    method: "POST",

    data: {
      operation: 1,

      idCategorie: idCategorie,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#modifierCategorie").modal("show");

        $("#idCategorie_Mdf").val(tab[1]);

        $("#ordreCT_Mdf").val(ordre);

        $("#categorie_Mdf").val(tab[2]);

        $("#categorieParent_Mdf").val(tab[3]);

        $("#nomParent_Mdf").text(tab[4]);
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier Categorie**/

/**Debut supprimer Categorie**/

// function spm_Categorie(idCategorie, ordre) {

//     $.ajax({

//         url: "ajax/operationAjax_Categorie.php",

//         method: "POST",

//         data: {

//             operation: 1,

//             idCategorie: idCategorie,

//         },

//         success: function(data) {

//             tab = data.split('<>');

//             if (tab[0] == 1) {

//                 $('#idCategorie_Spm').val(tab[1]);

//                 $('#ordreCT_Mdf').val(ordre);

//                 $('#categorie_Spm').val(tab[2]);

//                 $('#supprimerCategorie').modal('show');

//             }

//         },

//         error: function() {

//             alert("La requête ");

//         },

//         dataType: "text"

//     });

// }

/**Fin supprimer Categorie**/

/**Debut select categorie par click**/

function selectionCategorie(idDesignation) {
  var action = $("#categorie-" + idDesignation + "  option").length;

  console.log(action);

  if (action == 1) {
    $.ajax({
      url: "ajax/operationAjax_Categorie.php",

      method: "POST",

      data: {
        operation: 2,
      },

      success: function (data) {
        var data = JSON.parse(data);

        var taille = data.length;

        for (var i = 0; i < taille; i++) {
          var tab = data[i].split("<>");

          name = tab[1];

          $("#categorie-" + idDesignation).append(
            "<option value='" + name + "'>" + name + "</option>"
          );
        }
      },

      error: function () {
        alert("La requête 1");
      },

      dataType: "text",
    });
  }
}

function choisirCategorie(idDesignation) {
  var parent = $("#categorie-" + idDesignation).val();

  $.ajax({
    url: "ajax/operationAjax_Categorie.php",

    method: "POST",

    data: {
      operation: 3,

      parent: parent,
    },

    success: function (data) {
      // tab=data.split('<>');

      $("#sousCategorie-" + idDesignation).html("");

      $("#sousCategorie-" + idDesignation).append("<option></option>");

      var data = JSON.parse(data);

      var taille = data.length;

      for (var i = 0; i < taille; i++) {
        var name = data[i];

        $("#sousCategorie-" + idDesignation).append(
          "<option value='" + name + "'>" + name + "</option>"
        );
      }
    },

    error: function () {
      alert("La requête 2");
    },

    dataType: "text",
  });
}

/**Fin select categorie par click **/

/**Debut modifier categorie Designation**/

function chg_CategorieDesign(idDesignation, ordre) {
  var designation = $("#designation-" + idDesignation).val();

  var codeBarre = $("#codeBarre-" + idDesignation).val();

  var sousCategorie = $("#sousCategorie-" + idDesignation).val();

  var categorie = $("#categorie-" + idDesignation).val();

  var prixUS = $("#prixUS-" + idDesignation).val();

  var prixUN = $("#prixUN-" + idDesignation).val();

  var prixAC = $("#prixAC-" + idDesignation).val();

  $.ajax({
    url: "ajax/operationAjax_Categorie.php",

    method: "POST",

    data: {
      operation: 4,

      idDesignation: idDesignation,

      designation: designation,

      nom: sousCategorie,

      parent: categorie,

      prixUniteStock: prixUS,

      prixUnitaire: prixUN,

      prixAchat: prixAC,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#tableCategorie tr").each(function (row, tr) {
        fournisseur = $(tr).find("td:eq(0)").text();

        if (fournisseur != "" && fournisseur == ordre) {
          $(tr).find("td:eq(1)").html(designation);

          $(tr).find("td:eq(2)").html(codeBarre);

          $(tr).find("td:eq(3)").html(categorie);

          $(tr).find("td:eq(6)").html(sousCategorie);

          $(tr).find("td:eq(7)").html(prixUS);

          $(tr).find("td:eq(8)").html(prixUN);

          $(tr).find("td:eq(8)").html(prixAC);
        }
      });

      $("#btn_ChgCateg-" + idDesignation).prop("disabled", true);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier categorie Designation**/

$(function () {
  // alert(77)

  $(document).on("change", ".compte", function () {
    compte = $(this).val();

    idPanier = $(this).attr("data-idPanier");

    typeDataC = $(this).attr("data-type");

    // alert(typeDataC)

    if (typeDataC && typeDataC == "mutuelle") {
      typeDataC = "mutuelle";
    } else {
      typeDataC = "simple";
    }

    if (compte == 2) {
      $("#clientInput" + idPanier).show();

      $("#avanceInput" + idPanier).show();

      $("#compteAvance" + idPanier).show();

      $("#versement" + idPanier).hide();

      $("#versement").hide();

      $("#clientInput" + idPanier).focus();

      $("#clientInput" + idPanier).prop("required", true);

      $(".btn_Termine_Panier").on("click", function () {
        // window.location.reload();

        $("#clientInput" + idPanier).focus();

        $(".cache_btn_Terminer").show();

        // $('#msg_info_ClientRequired').modal('show')
      });
    } else if (compte == "1000") {
      /**** Debut paiement plusieurs comptes *****/
      // alert(1)
      $("#paiementMultipleModal-" + idPanier).modal("show");
    } else {
      /**** Fin paiement plusieurs comptes *****/
      if (compte == "1") {
        $("#versement" + idPanier).removeAttr("disabled");

        $("#versement" + idPanier).attr("placeholder", "Espèces...");

        $("#versement").removeAttr("disabled");

        $("#versement").attr("placeholder", "Espèces...");

        $("#clientInput" + idPanier).hide();

        $("#clientInput" + idPanier).prop("required", false);

        $("#avanceInput" + idPanier).hide();

        $("#compteAvance" + idPanier).hide();

        $("#versement").show();

        $("#versement" + idPanier).show();
      } else {
        // alert('ci else bi')

        $("#versement").attr("disabled", "disabled");

        $("#versement").removeAttr("placeholder", "");

        $("#versement" + idPanier).attr("disabled", "disabled");

        $("#versement" + idPanier).removeAttr("placeholder", "");

        $("#clientInput" + idPanier).hide();

        $("#clientInput" + idPanier).prop("required", false);

        $("#avanceInput" + idPanier).hide();

        $("#compteAvance" + idPanier).hide();

        $("#versement").show();

        $("#versement" + idPanier).show();
      }

      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 27,

          idClient: 0,

          idPanier: idPanier,

          typeData: typeDataC,
        },

        dataType: "text",

        success: function (data) {
          // alert(data)

          $("#clientInput" + idPanier).val("");

          $("#avanceInput" + idPanier).val("");
        },

        error: function () {
          alert("La requête ss");
        },
      });
    }
  });

  if ($(".compte").val() && $(".compte").val() != "1") {
    // alert(111)

    $("#versement").attr("disabled", "disabled");

    $("#versement").removeAttr("placeholder", "");

    $("#versement" + idPanier).attr("disabled", "disabled");

    $("#versement" + idPanier).removeAttr("placeholder", "");
  }
});

$(function () {
  typeData = "";

  $(document).on("keyup", ".clientInput", function (e) {
    e.preventDefault();

    typeClient = "simple";

    idPanier = $(this).attr("data-idPanier");

    typeData = $(this).attr("data-type");

    var query = $(this).val();

    if (query.length > 0) {
      //

      /*********** Modification **************/

      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 26,

              query: query,
            },

            dataType: "json",

            success: function (data) {
              // alert(data)

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

      $(this).focus();

      /*********** Modification **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        var text =
          $("#factForm .typeahead li.active").text() ||
          $("#factFormM .typeahead li.active").text();

        tab = text.split(" . ");

        idClient = tab[0];

        $.ajax({
          url: "ajax/vendreLigneAjax.php",

          method: "POST",

          data: {
            operation: 27,

            idClient: idClient,

            idPanier: idPanier,

            typeData: typeData,
          },

          dataType: "text",

          success: function (data) {
            $("#clientInput" + idPanier).val(tab[1]);

            $("#changedOk" + idPanier).show(1000);

            setTimeout(() => {
              $("#changedOk" + idPanier).hide(1000);
            }, 5000);

            $("#avanceInput" + idPanier).focus();
          },

          error: function (err) {
            console.log(err);
          },
        });

        typeClient = "";
      }
    }
  });

  /** Début click sur typeahead*/

  // $(document).on('click', 'li[class="active"]', function() {

  //     typeVente = $("#typeVente").val() || $("#typeVenteM").val();

  //     if (typeVente == '1' && typeClient == '') {

  //         var designation = $("#ajouterProdFormB"+idPanier+" .typeahead li.active").text();

  //         tab = designation.split(' => ');

  //         designation = tab[0] || $("#panier_"+idPanier).val();

  //         $.ajax({

  //             url: "ajax/vendreLigneAjax.php",

  //             method: "POST",

  //             data: {

  //                 operation: 15,

  //                 designation: designation,

  //                 idPagnet: idPanier

  //             },

  //             success: function(data) {

  //                 result = data.split('<>');

  //                 if (result[0] == 1) {

  //                     if (result[7] == 9) {

  //                         var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td>Montant</td>" +

  //                             "<td>" + result[3] + "</td>" +

  //                             "<td><input class='form-control' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 30%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                     }

  //                     else {

  //                         if(result[7]!="Article" && result[7]!="article"){

  //                             var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td><input class='form-control' id='ligne"+result[5]+"' onkeyup='modif_QuantiteP(this.value," + result[5] + "," + idPanier + "," + result[8]+" )' value='1' style='width: 30%' type='number' ></input></td>" +

  //                             "<td>" +

  //                                 "<select id='uniteVente"+result[5]+"' class='form-control' onchange='modif_UniteStock(this.value)' >" +

  //                                         "<option id='default"+result[5]+"' value='Article§"+result[5]+"§"+idPanier+"'>Article</option>"+

  //                                         "<option value='"+result[7]+"§"+result[5]+"§"+idPanier+"'>"+result[7]+"</option>"+

  //                                 "</select>"+

  //                             "</td>" +

  //                             "<td><input disabled='true' class='form-control' id='prixUniteStock"+result[5]+"' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                         }

  //                         else{

  //                             var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td><input class='form-control' id='ligne"+result[5]+"' onkeyup='modif_QuantiteP(this.value," + result[5] + "," + idPanier + ",1)' value='1' style='width: 30%' type='number' ></input></td>" +

  //                             "<td>" +

  //                                 "<select id='uniteVente"+result[5]+"' class='form-control' onchange='modif_UniteStock(this.value)' >" +

  //                                         "<option value='Article§"+result[5]+"§"+idPanier+"'>Article</option>"+

  //                                 "</select>"+

  //                             "</td>" +

  //                             "<td><input disabled='true' class='form-control' id='prixUniteStock"+result[5]+"' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                         }

  //                     }

  //                     $("#tablePanier" + idPanier).prepend(ligne);

  //                     $("#panier_"+idPanier).val('');

  //                     $("#somme_Total" + idPanier).text(result[6]);

  //                     $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                     var lcd=$("#lcd_Machine").val();

  //                     if(lcd==1){

  //                         var nd_Qte=1;

  //                         var nd_Prix=result[4];

  //                         $.ajax({

  //                             url : "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

  //                             type: "GET", // data type (can be get, post, put, delete)

  //                             data: {

  //                                 "quantite": nd_Qte,

  //                                 "prix": nd_Prix,

  //                             }, // data in json format

  //                             async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

  //                             success: function(response) {

  //                                 console.log(response);

  //                             },

  //                         });

  //                     }

  //                 }

  //                 if (result[0] == 2) {

  //                     $("#tablePanier"+idPanier+" tr").each(function(row, tr) {

  //                         reference = $(tr).find('td:eq(0)').text();

  //                         if (reference != '' && reference == result[2]) {

  //                             $(tr).find('td:eq(1)').html("<input class='form-control' id='ligne"+result[5]+"' onkeyup='modif_QuantiteP(this.value," + result[5] + "," + idPanier + ",1)' value='" + result[9] + "' style='width: 30%' type='number' ></input>");

  //                             // $(".licV").text('');

  //                             $("#panier_"+idPanier).val('');

  //                             $("#somme_Total" + idPanier).text(result[6]);

  //                             $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                             var lcd=$("#lcd_Machine").val();

  //                             if(lcd==1){

  //                                 var nd_Qte=result[9];

  //                                 var nd_Prix=result[4];

  //                                 $.ajax({

  //                                     url : "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

  //                                     type: "GET", // data type (can be get, post, put, delete)

  //                                     data: {

  //                                         "quantite": nd_Qte,

  //                                         "prix": nd_Prix,

  //                                     }, // data in json format

  //                                     async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

  //                                     success: function(response) {

  //                                         console.log(response);

  //                                     },

  //                                 });

  //                             }

  //                         }

  //                     });

  //                 }

  //                 if (result[0] == 3) {

  //                     $("#qte_stock").text(result[1]);

  //                     $('#msg_info_js').modal('show');

  //                     $("#panier_"+idPanier).val('');

  //                 }

  //                 if(result[0]== 4){

  //                     var ligne = "<tr>" +

  //                     "<td>" + result[2] + "</td>" +

  //                     "<td><input class='form-control' onkeyup='modif_QuantiteSD(this.value," + result[5] + "," + idPanier + ")'  id='ligne"+result[5]+"'  value='1' style='width: 30%' type='number' ></input></td>" +

  //                     "<td>" +result[7]+ "</td>" +

  //                     "<td><input class='form-control' id='prixUniteStock"+result[5]+"' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                     "<td>" +

  //                     "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                     "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                     "</button>" +

  //                     "</td>" +

  //                     "</tr>";

  //                     $("#tablePanier" + idPanier).prepend(ligne);

  //                     $("#panier_"+idPanier).val('');

  //                     $("#somme_Total" + idPanier).text(result[6]);

  //                     $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                 }

  //                 if(result[0]== 5){

  //                     var ligne = "<tr>" +

  //                     "<td>" + result[2] + "</td>" +

  //                     "<td>Montant</td>" +

  //                     "<td>Especes</td>" +

  //                     "<td><input class='form-control' id='prixUniteStock"+result[5]+"' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                     "<td>" +

  //                     "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                     "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                     "</button>" +

  //                     "</td>" +

  //                     "</tr>";

  //                     $("#tablePanier" + idPanier).prepend(ligne);

  //                     $("#panier_"+idPanier).val('');

  //                     $("#somme_Total" + idPanier).text(result[6]);

  //                     $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                 }

  //                 if(result[0]== 6){

  //                     window.location.reload();

  //                 }

  //                 if(result[0]== 7){

  //                     var ligne = "<tr>" +

  //                     "<td><input class='form-control' style='width: 100%'  type='text' value='" + result[2] + "' onkeyup='modif_DesignationBon(this.value," + result[5] + "," + idPanier + ")' /></td>" +

  //                     "<td>Montant</td>" +

  //                     "<td>Espece</td>" +

  //                     "<td><input class='form-control' id='prixUniteStock"+result[5]+"' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                     "<td>" +

  //                     "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                     "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                     "</button>" +

  //                     "</td>" +

  //                     "</tr>";

  //                     $("#tablePanier" + idPanier).prepend(ligne);

  //                     $("#panier_"+idPanier).val('');

  //                     $("#somme_Total" + idPanier).text(result[6]);

  //                     $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                 }

  //                 if(result[0]== 8){

  //                     var ligne = "<tr>" +

  //                     "<td>" + result[2] + "</td>" +

  //                     "<td>" +result[4]+ "</td>" +

  //                     "<td>" +result[3]+ "</td>" +

  //                     "<td>" +

  //                     "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                     "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                     "</button>" +

  //                     "</td>" +

  //                     "</tr>";

  //                     $("#tablePanier" + idPanier).prepend(ligne);

  //                     $("#panier_"+idPanier).val('');

  //                 }

  //                 if (result[0] == 10) {

  //                     if (result[7] == 9) {

  //                         var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td>Montant</td>" +

  //                             "<td>" + result[3] + "</td>" +

  //                             "<td><input class='form-control' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 30%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                     }

  //                     else {

  //                         if(result[7]!="Article" && result[7]!="article"){

  //                             var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td><input class='form-control' id='ligne"+result[5]+"' onkeyup='modif_QuantiteP(this.value," + result[5] + "," + idPanier + "," + result[8]+" )' value='1' style='width: 30%' type='number' ></input></td>" +

  //                             "<td>" +

  //                                 "<select id='uniteVente"+result[5]+"' class='form-control' onchange='modif_UniteStock(this.value)' >" +

  //                                         "<option id='default"+result[5]+"' value='Article§"+result[5]+"§"+idPanier+"'>Article</option>"+

  //                                         "<option value='"+result[7]+"§"+result[5]+"§"+idPanier+"'>"+result[7]+"</option>"+

  //                                 "</select>"+

  //                             "</td>" +

  //                             "<td><input  class='form-control' id='prixUniteStock"+result[5]+"' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                         }

  //                         else{

  //                             var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td><input class='form-control' id='ligne"+result[5]+"' onkeyup='modif_QuantiteP(this.value," + result[5] + "," + idPanier + ",1)' value='1' style='width: 30%' type='number' ></input></td>" +

  //                             "<td>" +

  //                                 "<select id='uniteVente"+result[5]+"' class='form-control' onchange='modif_UniteStock(this.value)' >" +

  //                                         "<option value='Article§"+result[5]+"§"+idPanier+"'>Article</option>"+

  //                                 "</select>"+

  //                             "</td>" +

  //                             "<td><input class='form-control' id='prixUniteStock"+result[5]+"' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                         }

  //                     }

  //                     $("#tablePanier" + idPanier).prepend(ligne);

  //                     // $(".licV").text('');

  //                     $("#panier_"+idPanier).val('');

  //                     $("#somme_Total" + idPanier).text(result[6]);

  //                     $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                 }

  //             },

  //             error: function() {

  //                 alert("La requête ");

  //             },

  //             dataType: "text"

  //         });

  //     }

  //     else if (typeVente == '2' && typeClient == '') {

  //         var designation = $("#ajouterProdFormEt"+idPanier+" .typeahead li.active").text();

  //         tab = designation.split(' => ');

  //         designation = tab[0] || $("#panier_"+idPanier).val();

  //         $.ajax({

  //             url: "ajax/vendreLigneAjax.php",

  //             method: "POST",

  //             data: {

  //                 operation: 17,

  //                 designation: designation,

  //                 idPagnet: idPanier

  //             },

  //             success: function(data) {

  //                 result = data.split('<>');

  //                 if (result[0] == 1) {

  //                     var depots = JSON.parse(result[12]);

  //                     var options = '';

  //                     $.each(depots, function(idx, depot) {

  //                         options += "<option value='"+depot[0]+"§"+result[5]+"§"+idPanier+"§1'>"+depot[1]+"</option>";

  //                     });

  //                     var ligne = "<tr>" +

  //                         "<td>" + result[2] + "</td>" +

  //                         "<td><input class='form-control' id='ligne"+result[5]+"' onkeyup='modif_QuantiteET(this.value," + result[5] + "," + idPanier + "," + result[8]+" )' value='1' style='width: 100%' type='number' ></input></td>" +

  //                         "<td><select id='uniteVente"+result[5]+"' class='form-control' onchange='modif_UniteStockET(this.value)' >" +

  //                                     "<option value='"+result[7]+"§"+result[5]+"§"+idPanier+"§1'>"+result[7]+"</option>"+

  //                                     "<option value='Demi Gros§"+result[5]+"§"+idPanier+"§2'>Demi Gros</option>"+

  //                                     "<option value='Piece§"+result[5]+"§"+idPanier+"§1'>Piece</option>"+

  //                             "</select>"+

  //                         "</td>" +

  //                         "<td><input class='form-control' id='prixUniteStock"+result[5]+"' onkeyup='modif_Prix(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 100%' type='number'></input></td>" +

  //                         "<td><select id='depot"+result[5]+"' class='form-control' onchange='modif_Depot(this.value)' >" +

  //                                     "<option value='"+result[10]+"§"+result[5]+"§"+idPanier+"§1'>"+result[11]+"</option>"+options+""+

  //                             "</select>"+

  //                         "</td>" +

  //                         "<td><button type='button' onclick='retour_Produit(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                         "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                         "</button>" +

  //                         "</td>" +

  //                     "</tr>";

  //                     $("#tablePanier" + idPanier).prepend(ligne);

  //                     $("#panier_"+idPanier).val('');

  //                     $("#somme_Total" + idPanier).text(result[6]);

  //                     $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                 }

  //                 if (result[0] == 2) {

  //                     window.location.reload();

  //                 }

  //                 if (result[0] == 3) {

  //                     $("#qte_stock").text(result[1]);

  //                     $('#msg_info_js').modal('show');

  //                     $("#panier_"+idPanier).val('');

  //                 }

  //             },

  //             error: function() {

  //                 alert("La requête ");

  //             },

  //             dataType: "text"

  //         });

  //     }

  //     else if (typeVente == '3' && typeClient == '') {

  //         var designation = $("#ajouterProdFormPh"+idPanier+" .typeahead li.active").text();

  //         tab = designation.split(' => ');

  //         designation = tab[0] || $("#panier_"+idPanier).val();

  //         $.ajax({

  //             url: "ajax/vendreLigneAjax.php",

  //             method: "POST",

  //             data: {

  //                 operation: 6,

  //                 designation: designation,

  //                 idPagnet: idPanier

  //             },

  //             success: function(data) {

  //                 result = data.split('<>');

  //                 if (result[0] == 1) {

  //                     if (result[7] == 9) {

  //                         var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td>Montant</td>" +

  //                             "<td>" + result[3] + "</td>" +

  //                             "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                     } else {

  //                         var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td><input class='form-control' onkeyup='modif_Quantite_PhP(this.value," + result[5] + "," + idPanier + ")' value='1' style='width: 70%' type='number' ></input></td>" +

  //                             "<td>" + result[3] + "</td>" +

  //                             "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                     }

  //                     $("#tablePanier"+idPanier).prepend(ligne);

  //                     $("#panier_"+idPanier).val('');

  //                     $("#somme_Total" + idPanier).text(result[6]);

  //                     $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                 }

  //                 if (result[0] == 2) {

  //                     $("#tablePanier"+idPanier+" tr").each(function(row, tr) {

  //                         reference = $(tr).find('td:eq(0)').text();

  //                         if (reference != '' && reference == result[2]) {

  //                             $(tr).find('td:eq(1)').html("<input class='form-control' onkeyup='modif_Quantite_PhP(this.value," + result[5] + "," + idPanier + ")' value='" + result[7] + "' style='width: 70%' type='number'></input>");

  //                             $("#panier_"+idPanier).val('');

  //                             $("#somme_Total" + idPanier).text(result[6]);

  //                             $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                         }

  //                     });

  //                 }

  //                 if (result[0] == 3) {

  //                     $("#qte_stock").text(result[1]);

  //                     $('#msg_info_js').modal('show');

  //                     $("#panier_"+idPanier).val('');

  //                 }

  //                 if (result[0] == 4) {

  //                     var ligne = "<tr>" +

  //                     "<td>" + result[2] + "</td>" +

  //                     "<td><input class='form-control' onkeyup='modif_QuantiteSDP(this.value," + result[5] + "," + idPanier + ")' value='1' style='width: 70%' type='number' ></input></td>" +

  //                     "<td>" + result[3] + "</td>" +

  //                     "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idPanier + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                     "<td>" +

  //                     "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idPanier + ")'	 class='btn btn-warning pull-right'>" +

  //                     "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                     "</button>" +

  //                     "</td>" +

  //                     "</tr>";

  //                     $("#tablePanier"+idPanier).prepend(ligne);

  //                     $("#panier_"+idPanier).val('');

  //                     $("#somme_Total" + idPanier).text(result[6]);

  //                     $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

  //                 }

  //             },

  //             error: function() {

  //             // alert("La requête ");

  //             },

  //             dataType: "text"

  //         });

  //     }

  //     else if (typeVente == '4' && typeClient == '') {

  //         var designation = $("#ajouterProdFormMutuelle"+idMutuellePagnet+" .typeahead li.active").text();

  //         tab = designation.split(' => ');

  //         designation = tab[0] || $("#panier_"+idMutuellePagnet).val();

  //         $.ajax({

  //             url: "ajax/vendreLigneAjax.php",

  //             method: "POST",

  //             data: {

  //                 operation: 21,

  //                 designation: designation,

  //                 idMutuellePagnet: idMutuellePagnet

  //             },

  //             success: function(data) {

  //                 result = data.split('<>');

  //                 if (result[0] == 1) {

  //                     if (result[7] == 9) {

  //                         var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td>Montant</td>" +

  //                             "<td>" + result[3] + "</td>" +

  //                             "<td><input class='form-control' onkeyup='modif_Prix_Ph(this.value," + result[5] + "," + idMutuellePagnet + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_ProduitPh(" + result[5] + "," + idMutuellePagnet + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                     } else {

  //                         var ligne = "<tr>" +

  //                             "<td>" + result[2] + "</td>" +

  //                             "<td><input class='form-control' onkeyup='modif_Quantite_Mutuelle(this.value," + result[5] + "," + idMutuellePagnet + ")' value='1' style='width: 70%' type='number' ></input></td>" +

  //                             "<td>" + result[3] + "</td>" +

  //                             "<td><input class='form-control' onkeyup='modif_Prix_Mutuelle(this.value," + result[5] + "," + idMutuellePagnet + ")' value='" + result[4] + "' style='width: 70%' type='number'></input></td>" +

  //                             "<td>" +

  //                             "<button type='button' onclick='retour_Produit_Mutuelle(" + result[5] + "," + idMutuellePagnet + ")'	 class='btn btn-warning pull-right'>" +

  //                             "<span class='glyphicon glyphicon-remove'></span>Retour" +

  //                             "</button>" +

  //                             "</td>" +

  //                             "</tr>";

  //                     }

  //                     $('#tableMutuelle'+idMutuellePagnet).prepend(ligne);

  //                     $("#panier_"+idMutuellePagnet).val('');

  //                     $("#somme_Total" + idMutuellePagnet).text(result[6]);

  //                     $("#somme_Apayer" + idMutuellePagnet).text(result[6]/2);

  //                     $("#somme_Apayer" + idMutuellePagnet).text(result[6] - ((result[6] * result[7]) /100 ));

  //                 }

  //                 if (result[0] == 2) {

  //                     $('#tableMutuelle'+idMutuellePagnet+' tr').each(function(row, tr) {

  //                         reference = $(tr).find('td:eq(0)').text();

  //                         if (reference != '' && reference == result[2]) {

  //                             $(tr).find('td:eq(1)').html("<input class='form-control' onkeyup='modif_Quantite_Mutuelle(this.value," + result[5] + "," + idMutuellePagnet + ")' value='" + result[7] + "' style='width: 70%' type='number'></input>");

  //                             $("#panier_"+idMutuellePagnet).val('');

  //                             $("#somme_Total" + idMutuellePagnet).text(result[6]);

  //                             $("#somme_Apayer" + idMutuellePagnet).text(result[6] - ((result[6] * result[8]) /100 ));

  //                         }

  //                     });

  //                 }

  //                 if (result[0] == 3) {

  //                     $("#qte_stock").text(result[1]);

  //                     $('#msg_info_js').modal('show');

  //                     $("#panier_"+idMutuellePagnet).val('');

  //                 }

  //             },

  //             error: function() {

  //                 alert("La requête ");

  //             },

  //             dataType: "text"

  //         });

  //     }

  //     if(typeClient == 'simple'){

  //         var text =  $("#factForm .typeahead li.active").text() || $("#factFormM .typeahead li.active").text();

  //         tab = text.split(' . ');

  //         idClient = tab[0];

  //         $.ajax({

  //             url: "ajax/vendreLigneAjax.php",

  //             method: "POST",

  //             data: {

  //                 operation: 27,

  //                 idClient: idClient,

  //                 idPanier: idPanier,

  //                 typeData: typeData

  //             },

  //             dataType: "text",

  //             success: function(data) {

  //                 $("#clientInput"+idPanier).val(tab[1]);

  //             },

  //             error: function() {

  //                 alert("La requête ss");

  //             }

  //         });

  //         typeClient = '';

  //     }else if(typeClient == 'mutuelle'){

  //         var client = $(this).text();

  //         $.ajax({

  //             url: 'ajax/vendreLigneAjax.php',

  //             method: 'POST',

  //             data: {

  //                 operation: 24,

  //                 client: client,

  //                 idMutuellePagnet : idMutuellePagnet

  //             },

  //             success: function(data) {

  //                 $("#clientMutuelle"+idMutuellePagnet).val(client);

  //             },

  //             dataType: 'text'

  //         });

  //         typeClient = '';

  //     }

  //     // idFormParent=$("#panier_"+idPanier).parent().attr("id");

  //     // // alert(idFormParent+'/////////')

  //     // setTimeout(() => {

  //     //     $('#'+idFormParent+' .typeahead').remove()

  //     // }, 500);

  // });

  /** Fin click sur typeahead*/
});

/** Début click sur typeahead*/

$(document).on("click", '.typeahead li a[class="dropdown-item"]', function (e) {
  e.preventDefault();

  // function aClick() {

  // $('a[class="dropdown-item"]').on('click', function() {

  // alert(12)

  typeVente = $("#typeVente").val() || $("#typeVenteM").val();

  if (typeVente == "1" && typeClient == "") {
    var designation = $(
      "#ajouterProdFormB" + idPanier + " .typeahead li.active"
    ).text();

    tab = designation.split(" => ");

    designation = tab[0] || $("#panier_" + idPanier).val();

    $("#panier_" + idPanier).val("");

    $(".btn_Termine_Panier").attr("disabled", "disabled");

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 15,

        designation: designation,

        idPagnet: idPanier,
      },

      success: function (data) {
        result = data.split("<>");

        if (result[0] == 1) {
          if (result[7] == 9) {
            var ligne =
              "<tr>" +
              "<td>" +
              result[2] +
              "</td>" +
              "<td>Montant</td>" +
              "<td>" +
              result[3] +
              "</td>" +
              "<td><input class='form-control' onkeyup='modif_Prix(this.value," +
              result[5] +
              "," +
              idPanier +
              ")' value='" +
              result[4] +
              "' style='width: 30%' type='number'></input></td>" +
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
          } else {
            if (result[7] != "Article" && result[7] != "article") {
              var ligne =
                "<tr>" +
                "<td>" +
                result[2] +
                "</td>" +
                "<td><input class='form-control filedReadonly" +
                idPanier +
                "' id='ligne" +
                result[5] +
                "' onkeyup='modif_QuantiteP(this.value," +
                result[5] +
                "," +
                idPanier +
                "," +
                result[8] +
                " )' value='1' style='width: 30%' type='number' ></input></td>" +
                "<td>" +
                "<select id='uniteVente" +
                result[5] +
                "' class='form-control filedReadonly" +
                idPanier +
                "' onchange='modif_UniteStock(this.value)' >" +
                "<option id='default" +
                result[5] +
                "' value='Article§" +
                result[5] +
                "§" +
                idPanier +
                "'>Article</option>" +
                "<option value='" +
                result[7] +
                "§" +
                result[5] +
                "§" +
                idPanier +
                "'>" +
                result[7] +
                "</option>" +
                "</select>" +
                "</td>" +
                "<td><input disabled='true' class='form-control' id='prixUniteStock" +
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
                "<td><input class='form-control filedReadonly" +
                idPanier +
                "' id='ligne" +
                result[5] +
                "' onkeyup='modif_QuantiteP(this.value," +
                result[5] +
                "," +
                idPanier +
                ",1)' value='1' style='width: 30%' type='number' ></input></td>" +
                "<td>" +
                "<select id='uniteVente" +
                result[5] +
                "' class='form-control filedReadonly" +
                idPanier +
                "' onchange='modif_UniteStock(this.value)' >" +
                "<option value='Article§" +
                result[5] +
                "§" +
                idPanier +
                "'>Article</option>" +
                "</select>" +
                "</td>" +
                "<td><input disabled='true' class='form-control' id='prixUniteStock" +
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
                ")'	 class='btn btn-warning pull-right btnRtrAvant" +
                idPanier +
                "'>" +
                "<span class='glyphicon glyphicon-remove'></span>Retour" +
                "</button>" +
                "</td>" +
                "</tr>";
            }
          }

          $("#tablePanier" + idPanier).prepend(ligne);

          $(".btn_Termine_Panier").removeAttr("disabled");

          $("#somme_Total" + idPanier).text(result[6]);

          $("#somme_Apayer" + idPanier).text(
            result[6] - $("#val_remise" + idPanier).val()
          );

          valTotal = result[6] - $("#val_remise" + idPanier).val();

          valApayer = result[10] - $("#val_remise" + idPanier).val();

          $("#somme_TotalCN" + idPanier).text(valTotal.toFixed(2));

          $("#somme_ApayerCN" + idPanier).text(valApayer.toFixed(2));

          var lcd = $("#lcd_Machine").val();

          if (lcd == 1) {
            var nd_Qte = 1;

            var nd_Prix = result[4];

            $.ajax({
              url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

              type: "GET", // data type (can be get, post, put, delete)

              data: {
                quantite: nd_Qte,

                prix: nd_Prix,
              }, // data in json format

              async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

              success: function (response) {
                console.log(response);
              },
            });
          }
        }

        if (result[0] == 2) {
          $("#tablePanier" + idPanier + " tr").each(function (row, tr) {
            reference = $(tr).find("td:eq(0)").text();

            if (reference != "" && reference == result[2]) {
              $(tr)
                .find("td:eq(1)")
                .html(
                  "<input class='form-control filedReadonly" +
                    idPanier +
                    "' id='ligne" +
                    result[5] +
                    "' onkeyup='modif_QuantiteP(this.value," +
                    result[5] +
                    "," +
                    idPanier +
                    ",1)' value='" +
                    result[9] +
                    "' style='width: 30%' type='number' ></input>"
                );

              // $(".licV").text('');

              $(".btn_Termine_Panier").removeAttr("disabled");

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] - $("#val_remise" + idPanier).val()
              );

              valTotal = result[6] - $("#val_remise" + idPanier).val();

              valApayer = result[10] - $("#val_remise" + idPanier).val();

              $("#somme_TotalCN" + idPanier).text(valTotal.toFixed(2));

              $("#somme_ApayerCN" + idPanier).text(valApayer.toFixed(2));

              var lcd = $("#lcd_Machine").val();

              if (lcd == 1) {
                var nd_Qte = result[9];

                var nd_Prix = result[4];

                $.ajax({
                  url: "http://localhost:8080/app.js", // Url of backend (can be python, php, etc..)

                  type: "GET", // data type (can be get, post, put, delete)

                  data: {
                    quantite: nd_Qte,

                    prix: nd_Prix,
                  }, // data in json format

                  async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)

                  success: function (response) {
                    console.log(response);
                  },
                });
              }
            }
          });
        }

        if (result[0] == 3) {
          $("#qte_stock").text(result[1]);

          $("#msg_info_js").modal("show");

          $("#panier_" + idPanier).val("");
        }

        if (result[0] == 4) {
          var ligne =
            "<tr>" +
            "<td>" +
            result[2] +
            "</td>" +
            "<td><input class='form-control' onkeyup='modif_QuantiteSD(this.value," +
            result[5] +
            "," +
            idPanier +
            ")'  id='ligne" +
            result[5] +
            "'  value='1' style='width: 30%' type='number' ></input></td>" +
            "<td>" +
            result[7] +
            "</td>" +
            "<td><input class='form-control' id='prixUniteStock" +
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

        if (result[0] == 5) {
          var ligne =
            "<tr>" +
            "<td><input class='form-control' style='width: 100%'  type='text' value='" +
            result[2] +
            "' onkeyup='modif_DesignationBon(this.value," +
            result[5] +
            "," +
            idPanier +
            ")' /></td>" +
            // "<td>" + result[2] + "</td>" +

            "<td>Montant</td>" +
            "<td>Especes</td>" +
            "<td><input class='form-control' id='prixUniteStock" +
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

        if (result[0] == 6) {
          window.location.reload();
        }

        if (result[0] == 7) {
          var ligne =
            "<tr>" +
            "<td><input class='form-control' style='width: 100%'  type='text' value='" +
            result[2] +
            "' onkeyup='modif_DesignationBon(this.value," +
            result[5] +
            "," +
            idPanier +
            ")' /></td>" +
            "<td>Montant</td>" +
            "<td>Espece</td>" +
            "<td><input class='form-control' id='prixUniteStock" +
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

        if (result[0] == 8) {
          var ligne =
            "<tr>" +
            "<td>" +
            result[2] +
            "</td>" +
            "<td>" +
            result[4] +
            "</td>" +
            "<td>" +
            result[3] +
            "</td>" +
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
        }

        if (result[0] == 10) {
          if (result[7] == 9) {
            var ligne =
              "<tr>" +
              "<td>" +
              result[2] +
              "</td>" +
              "<td>Montant</td>" +
              "<td>" +
              result[3] +
              "</td>" +
              "<td><input class='form-control' onkeyup='modif_Prix(this.value," +
              result[5] +
              "," +
              idPanier +
              ")' value='" +
              result[4] +
              "' style='width: 30%' type='number'></input></td>" +
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
          } else {
            if (result[7] != "Article" && result[7] != "article") {
              var ligne =
                "<tr>" +
                "<td>" +
                result[2] +
                "</td>" +
                "<td><input class='form-control' id='ligne" +
                result[5] +
                "' onkeyup='modif_QuantiteP(this.value," +
                result[5] +
                "," +
                idPanier +
                "," +
                result[8] +
                " )' value='1' style='width: 30%' type='number' ></input></td>" +
                "<td>" +
                "<select id='uniteVente" +
                result[5] +
                "' class='form-control' onchange='modif_UniteStock(this.value)' >" +
                "<option id='default" +
                result[5] +
                "' value='Article§" +
                result[5] +
                "§" +
                idPanier +
                "'>Article</option>" +
                "<option value='" +
                result[7] +
                "§" +
                result[5] +
                "§" +
                idPanier +
                "'>" +
                result[7] +
                "</option>" +
                "</select>" +
                "</td>" +
                "<td><input  class='form-control' id='prixUniteStock" +
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
            } else {
              var ligne =
                "<tr>" +
                "<td>" +
                result[2] +
                "</td>" +
                "<td><input class='form-control' id='ligne" +
                result[5] +
                "' onkeyup='modif_QuantiteP(this.value," +
                result[5] +
                "," +
                idPanier +
                ",1)' value='1' style='width: 30%' type='number' ></input></td>" +
                "<td>" +
                "<select id='uniteVente" +
                result[5] +
                "' class='form-control' onchange='modif_UniteStock(this.value)' >" +
                "<option value='Article§" +
                result[5] +
                "§" +
                idPanier +
                "'>Article</option>" +
                "</select>" +
                "</td>" +
                "<td><input class='form-control' id='prixUniteStock" +
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
            }
          }

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

    // $("#panier_"+idPanier).html('')

    idFormParent = $("#panier_" + idPanier)
      .parent()
      .attr("id");

    // alert(idFormParent+'/////////')

    // setTimeout(() => {

    $("#" + idFormParent + " .typeahead").html("");
  } else if (typeVente == "2" && typeClient == "") {
    var designation = $(
      "#ajouterProdFormEt" + idPanier + " .typeahead li.active"
    ).text();

    tab = designation.split(" => ");

    designation = tab[0] || $("#panier_" + idPanier).val();

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

        // alert(result[13])
        // alert(result.length)

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

          if ($("#sessioninfosup").val() == 1) {
            infoSupp =
              "<button type='button' data-idligne='" +
              result[5] +
              "' data-toggle='modal' data-target='#add_infoSup_modal' class='btn btn-info pull-right btn_add_infoSup1'>" +
              "<span class='glyphicon glyphicon-plus'></span>info. sup" +
              "</button>";
          } else {
            infoSupp = "";
          }

          if (result[8] == 1) {
            var ligne =
              "<tr>" +
              "<td>" +
              result[2] +
              "</td>" +
              "<td><input class='form-control' id='quantite" +
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
              "' class='form-control' onchange='modif_UniteStockET(this.value)' >" +
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
              "<td><input class='form-control' id='prixunitevente" +
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
              "' class='form-control' onchange='modif_Depot(this.value)' >" +
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
              ")'	 class='btn btn-warning pull-right'>" +
              "<span class='glyphicon glyphicon-remove'></span>Retour" +
              "</button>" +
              infoSupp +
              "</td>" +
              "</tr>";
          } else {
            var ligne =
              "<tr>" +
              "<td>" +
              result[2] +
              "</td>" +
              "<td><input class='form-control' id='quantite" +
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
              "' class='form-control' onchange='modif_UniteStockET(this.value)' >" +
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
              "<td><input class='form-control' id='prixunitevente" +
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
              "' class='form-control' onchange='modif_Depot(this.value)' >" +
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
              ")'	 class='btn btn-warning pull-right'>" +
              "<span class='glyphicon glyphicon-remove'></span>Retour" +
              "</button>" +
              infoSupp +
              "</td>" +
              "</tr>";
          }

          $("#tablePanier" + idPanier).prepend(ligne);

          $("#panier_" + idPanier).val("");

          $("#somme_Total" + idPanier).text(result[6]);

          $("#somme_Apayer" + idPanier).text(
            result[6] -
              $("#val_remise" + idPanier).val() -
              (result[6] * result[13]) / 100
          );

          // $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());
        }

        if (result[0] == 2) {
          window.location.reload();
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
            "<td><input class='form-control' style='width: 100%'  type='text' value='" +
            result[2] +
            "' onkeyup='modif_DesignationBon(this.value," +
            result[5] +
            "," +
            idPanier +
            ")' /></td>" +
            // "<td>" + result[2] + "</td>" +

            "<td>Montant</td>" +
            "<td>Especes</td>" +
            "<td><input class='form-control' id='prixunitevente" +
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
            result[6] -
              $("#val_remise" + idPanier).val() -
              (result[6] * result[10]) / 100
          );

          // $("#somme_Apayer" + idPanier).text(result[6] - $("#val_remise" + idPanier).val());

          valTotal = result[6] - $("#val_remise" + idPanier).val();

          valApayer = result[10] - $("#val_remise" + idPanier).val();

          $("#somme_TotalCN" + idPanier).text(valTotal.toFixed(2));

          $("#somme_ApayerCN" + idPanier).text(valApayer.toFixed(2));
        }

        if (result[0] == 6) {
          window.location.reload();
        }

        //console.log(data);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    $("#panier_" + idPanier).html("");
  } else if (typeVente == "3" && typeClient == "") {
    var designation = $(
      "#ajouterProdFormPh" + idPanier + " .typeahead li.active"
    ).text();

    tab = designation.split(" => ");

    designation = tab[0] || $("#panier_" + idPanier).val();

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 6,

        designation: designation,

        idPagnet: idPanier,
      },

      success: function (data) {
        result = data.split("<>");

        if (result[0] == 1) {
          if (result[7] == 9) {
            var ligne =
              "<tr>" +
              "<td>" +
              result[2] +
              "</td>" +
              "<td>Montant</td>" +
              "<td>" +
              result[3] +
              "</td>" +
              "<td><input class='form-control filedReadonly" +
              idPanier +
              "' onkeyup='modif_Prix_Ph(this.value," +
              result[5] +
              "," +
              idPanier +
              ")' value='" +
              result[4] +
              "' style='width: 70%' type='number'></input></td>" +
              "<td>" +
              "<button type='button' onclick='retour_ProduitPh(" +
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
              "<td><input class='form-control filedReadonly" +
              idPanier +
              "' id='ligne" +
              result[5] +
              "' onkeyup='modif_Quantite_PhP(this.value," +
              result[5] +
              "," +
              idPanier +
              ")' value='1' style='width: 70%' type='number' ></input></td>" +
              "<td>" +
              result[3] +
              "</td>" +
              "<td><input class='form-control filedReadonly" +
              idPanier +
              "' id='prixPublic" +
              result[5] +
              "' onkeyup='modif_Prix_Ph(this.value," +
              result[5] +
              "," +
              idPanier +
              ")' value='" +
              result[4] +
              "' style='width: 70%' type='number'></input></td>" +
              "<td>" +
              "<button type='button' onclick='retour_ProduitPh(" +
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

          // $(".lic").text('');

          // $(this).val('');

          $("#panier_" + idPanier).val("");

          // $(".codeBarreLignePh").val('valueeeeeeeeee');

          // $(this).focus();

          $("#somme_Total" + idPanier).text(result[6]);

          $("#somme_Apayer" + idPanier).text(
            result[6] -
              $("#val_remise" + idPanier).val() -
              (result[6] * result[7]) / 100
          );
        }

        if (result[0] == 2) {
          $("#tablePanier" + idPanier + " tr").each(function (row, tr) {
            reference = $(tr).find("td:eq(0)").text();

            if (reference != "" && reference == result[2]) {
              $(tr)
                .find("td:eq(1)")
                .html(
                  "<input class='form-control filedReadonly" +
                    idPanier +
                    "' onkeyup='modif_Quantite_PhP(this.value," +
                    result[5] +
                    "," +
                    idPanier +
                    ")' value='" +
                    result[7] +
                    "' style='width: 70%' type='number'></input>"
                );

              $("#panier_" + idPanier).val("");

              $("#somme_Total" + idPanier).text(result[6]);

              $("#somme_Apayer" + idPanier).text(
                result[6] -
                  $("#val_remise" + idPanier).val() -
                  (result[6] * result[8]) / 100
              );
            }
          });
        }

        if (result[0] == 3) {
          $("#qte_stock").text(result[1]);

          $("#msg_info_js").modal("show");

          $("#panier_" + idPanier).val("");
        }

        if (result[0] == 4) {
          var ligne =
            "<tr>" +
            "<td>" +
            result[2] +
            "</td>" +
            "<td><input class='form-control filedReadonly" +
            idPanier +
            "' onkeyup='modif_QuantiteSDP(this.value," +
            result[5] +
            "," +
            idPanier +
            ")' value='1' style='width: 70%' type='number' ></input></td>" +
            "<td>" +
            result[3] +
            "</td>" +
            "<td><input class='form-control filedReadonly" +
            idPanier +
            "' onkeyup='modif_Prix_Ph(this.value," +
            result[5] +
            "," +
            idPanier +
            ")' value='" +
            result[4] +
            "' style='width: 70%' type='number'></input></td>" +
            "<td>" +
            "<button type='button' onclick='retour_ProduitPh(" +
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

          $("#tablePanier" + idPanier).prepend(ligne);

          $("#panier_" + idPanier).val("");

          $("#somme_Total" + idPanier).text(result[6]);

          $("#somme_Apayer" + idPanier).text(
            result[6] -
              $("#val_remise" + idPanier).val() -
              (result[6] * result[7]) / 100
          );
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    $("#panier_" + idPanier).html("");
  } else if (typeVente == "4" && typeClient == "") {
    var designation = $(
      "#ajouterProdFormMutuelle" + idMutuellePagnet + " .typeahead li.active"
    ).text();

    tab = designation.split(" => ");

    designation = tab[0] || $("#panier_" + idMutuellePagnet).val();

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 21,

        designation: designation,

        idMutuellePagnet: idMutuellePagnet,
      },

      success: function (data) {
        result = data.split("<>");

        if (result[0] == 1) {
          if (result[7] == 9) {
            var ligne =
              "<tr>" +
              "<td>" +
              result[2] +
              "</td>" +
              "<td>Montant</td>" +
              "<td>" +
              result[3] +
              "</td>" +
              "<td><input class='form-control filedReadonly_M" +
              idMutuellePagnet +
              "' id='ligne" +
              result[5] +
              "' onkeyup='modif_Prix_Ph(this.value," +
              result[5] +
              "," +
              idMutuellePagnet +
              ")' value='" +
              result[4] +
              "' style='width: 70%' type='number'></input></td>" +
              "<td>" +
              "<button type='button' onclick='retour_ProduitPh(" +
              result[5] +
              "," +
              idMutuellePagnet +
              ")' class='btn btn-warning pull-right btnRtrAvant_M" +
              idMutuellePagnet +
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
              "<td><input class='form-control filedReadonly_M" +
              idMutuellePagnet +
              "' id='ligne" +
              result[5] +
              "' onkeyup='modif_Quantite_Mutuelle(this.value," +
              result[5] +
              "," +
              idMutuellePagnet +
              ")' value='1' style='width: 70%' type='number' ></input></td>" +
              "<td>" +
              result[3] +
              "</td>" +
              "<td><input class='form-control filedReadonly_M" +
              idMutuellePagnet +
              "'  id='prixPublic" +
              result[5] +
              "' onkeyup='modif_Prix_Mutuelle(this.value," +
              result[5] +
              "," +
              idMutuellePagnet +
              ")' value='" +
              result[4] +
              "' style='width: 70%' type='number'></input></td>" +
              "<td>" +
              "<button type='button' onclick='retour_Produit_Mutuelle(" +
              result[5] +
              "," +
              idMutuellePagnet +
              ")' class='btn btn-warning pull-right btnRtrAvant_M" +
              idMutuellePagnet +
              "'>" +
              "<span class='glyphicon glyphicon-remove'></span>Retour" +
              "</button>" +
              "</td>" +
              "</tr>";
          }

          $("#tableMutuelle" + idMutuellePagnet).prepend(ligne);

          $("#panier_" + idMutuellePagnet).val("");

          $("#somme_Total" + idMutuellePagnet).text(result[6]);

          $("#somme_Apayer" + idMutuellePagnet).text(result[6] / 2);

          $("#somme_Apayer" + idMutuellePagnet).text(
            result[6] - (result[6] * result[7]) / 100
          );
        }

        if (result[0] == 2) {
          $("#tableMutuelle" + idMutuellePagnet + " tr").each(function (
            row,
            tr
          ) {
            reference = $(tr).find("td:eq(0)").text();

            if (reference != "" && reference == result[2]) {
              $(tr)
                .find("td:eq(1)")
                .html(
                  "<input class='form-control' onkeyup='modif_Quantite_Mutuelle(this.value," +
                    result[5] +
                    "," +
                    idMutuellePagnet +
                    ")' value='" +
                    result[7] +
                    "' style='width: 70%' type='number'></input>"
                );

              $("#panier_" + idMutuellePagnet).val("");

              $("#somme_Total" + idMutuellePagnet).text(result[6]);

              $("#somme_Apayer" + idMutuellePagnet).text(
                result[6] - (result[6] * result[8]) / 100
              );
            }
          });
        }

        if (result[0] == 3) {
          $("#qte_stock").text(result[1]);

          $("#msg_info_js").modal("show");

          $("#panier_" + idMutuellePagnet).val("");
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    $("#panier_" + idMutuellePagnet).html("");
  }

  if (typeClient == "simple") {
    var text =
      $("#factForm .typeahead li.active").text() ||
      $("#factFormM .typeahead li.active").text();

    tab = text.split(" . ");

    idClient = tab[0];

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 27,

        idClient: idClient,

        idPanier: idPanier,

        typeData: typeData,
      },

      dataType: "text",

      success: function (data) {
        $("#clientInput" + idPanier).val(tab[1]);

        $("#changedOk" + idPanier).show(1000);

        setTimeout(() => {
          $("#changedOk" + idPanier).hide(1000);
        }, 5000);

        $("#avanceInput" + idPanier).focus();
      },

      error: function () {
        alert("La requête ss");
      },
    });

    typeClient = "";
  } else if (typeClient == "mutuelle") {
    var client = $(this).text();

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 24,

        client: client,

        idMutuellePagnet: idMutuellePagnet,
      },

      success: function (data) {
        $("#clientMutuelle" + idMutuellePagnet).val(client);
      },

      dataType: "text",
    });

    typeClient = "";
  }
});

/** Fin click sur typeahead*/

$(function () {
  $(".terminerMutuelle").on("click", function (e) {
    e.preventDefault();

    idMutuellePagnet = $(this).attr("data-idPanier");

    codeBeneficiaire = $("#codeBeneficiaire").val();

    numeroRecu = $("#numeroRecu").val();

    dateRecu = $("#dateRecu").val();

    nomAdherant = $("#clientMutuelle" + idMutuellePagnet).val();

    compte = $("#compte" + idMutuellePagnet).val();

    clientInput = $("#clientInput" + idMutuellePagnet).val();

    avanceInput = $("#avanceInput" + idMutuellePagnet).val();

    compteAvance = $("#compteAvance" + idMutuellePagnet).val();

    versement = $("#versementMutuelle" + idMutuellePagnet).val();

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 28,

        idMutuellePagnet: idMutuellePagnet,

        codeBeneficiaire: codeBeneficiaire,

        numeroRecu: numeroRecu,

        nomAdherant: nomAdherant,

        compte: compte,

        dateRecu: dateRecu,

        clientInput: clientInput,

        avanceInput: avanceInput,

        compteAvance: compteAvance,

        versement: versement,
      },

      success: function (data) {
        if (data == "") {
          window.location.reload();
        } else {
          $(".codeBarreLigneMutuelle").focus();

          $(".cache_btn_Terminer").show();

          $("#p_msg_info_TerminerImputation").html(data);

          $("#msg_info_TerminerImputation").modal("show");
        }
      },

      error: function (err) {
        alert(err);
      },

      dataType: "text",
    });

    // alert(idMutuellePagnet+"/88")
  });
});

$(function () {
  // alert(11111)

  $(".terminerMutuelleBon").on("click", function (e) {
    e.preventDefault();

    idMutuellePagnet = $(this).attr("data-idPanier");

    nomMutuelle = $("#mutuellePagnet" + idMutuellePagnet).val();

    codeBeneficiaire = $("#codeBeneficiaire").val();

    numeroRecu = $("#numeroRecu").val();

    dateRecu = $("#dateRecu").val();

    codeAdherant = $("#codeAdherantMutuelle").val();

    idClientAvoir = $("#idClientAvoir" + idMutuellePagnet).val();

    nomAdherant = $("#clientMutuelle" + idMutuellePagnet).val();

    avanceInput = $("#avanceInput" + idMutuellePagnet).val();

    compteAvance = $("#compteAvance" + idMutuellePagnet).val();

    // alert(codeBeneficiaire+"/"+numeroRecu+"/"+dateRecu+"/"+nomAdherant+"/"+codeAdherant)

    // alert(idClientAvoir)

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 29,

        idMutuellePagnet: idMutuellePagnet,

        nomMutuelle: nomMutuelle,

        codeBeneficiaire: codeBeneficiaire,

        codeAdherant: codeAdherant,

        numeroRecu: numeroRecu,

        nomAdherant: nomAdherant,

        idClientAvoir: idClientAvoir,

        dateRecu: dateRecu,

        avanceInput: avanceInput,

        compteAvance: compteAvance,
      },

      success: function (data) {
        // alert(data)

        if (data == "") {
          window.location.reload();
        } else {
          $(".codeBarreLigneMutuelle").focus();

          $(".cache_btn_Terminer").show();

          $("#p_msg_info_TerminerImputation").html(data);

          $("#msg_info_TerminerImputation").modal("show");
        }
      },

      error: function (err) {
        alert(err);
      },

      dataType: "text",
    });
  });

  $(".avanceInput").keyup(function () {
    var avance = $(this).val();

    var idPanier = $(this).attr("data-idPanier");

    var total = $("#somme_Apayer" + idPanier).text();

    // console.log(total);

    // alert(1)

    if (avance > parseInt(total)) {
      // alert(2)

      $("#avanceInput" + idPanier).blur();

      $("#avanceInput" + idPanier).val("");

      $("#msg_info_avance").modal("show");
    }
  });

  $(document).on("click", 'li[class="liC_Ph"]', function () {
    var categorie = $(this).text();

    $("#categoriePh").val(categorie);

    $("#reponseCategorie").html(" ");

    //  window.location.href = "insertionLigneLight.php?produit="+tab[0];
  });

  $(".modeEditionBtn").on("click", function () {
    var id = $(this).attr("id");

    result = id.split("-");

    idPanier = result[1];

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 30,

        idPanier: idPanier,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)

        // $("#clientInput"+idPanier).val('');

        // $("#avanceInput"+idPanier).val('');

        if (data == 1) {
          window.location.reload();
        } else {
          $("#msg_edit_pagnet").modal("show");
        }
      },

      error: function () {
        alert("La requête ss");
      },
    });
  });

  $(".modeEditionBtnET").on("click", function () {
    var id = $(this).attr("id");

    result = id.split("-");

    idPanier = result[1];

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 31,

        idPanier: idPanier,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)

        // $("#clientInput"+idPanier).val('');

        // $("#avanceInput"+idPanier).val('');

        if (data == 1) {
          window.location.reload();
        } else {
          $("#msg_edit_pagnet").modal("show");
        }
      },

      error: function () {
        alert("La requête ss");
      },
    });
  });

  $(".modeEditionBtnPh").on("click", function () {
    var id = $(this).attr("id");

    result = id.split("-");

    idPanier = result[1];

    // alert(idPanier);

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 32,

        idPanier: idPanier,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)closeShowInfoChange

        // $("#clientInput"+idPanier).val('');

        // $("#avanceInput"+idPanier).val('');

        if (data == 1) {
          window.location.reload();
        } else {
          $("#msg_edit_pagnet").modal("show");
        }
      },

      error: function () {
        alert("La requête ss");
      },
    });
  });

  $(".modeEditionBtnMt").on("click", function () {
    var id = $(this).attr("id");

    result = id.split("-");

    idMutuellePanier = result[1];

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 38,

        idMutuellePanier: idMutuellePanier,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)closeShowInfoChange

        // $("#clientInput"+idPanier).val('');

        // $("#avanceInput"+idPanier).val('');

        if (data == 1) {
          window.location.reload();
        } else {
          $("#msg_edit_mutuelle" + idMutuellePanier).modal("show");
        }
      },

      error: function () {
        alert("La requête ss");
      },
    });
  });

  $("#closeShowInfoChange").on("click", function () {
    // alert(idPanier);

    $.ajax({
      url: "ajax/vendreLigneAjax.php",

      method: "POST",

      data: {
        operation: 33,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)
        // $("#clientInput"+idPanier).val('');
        // $("#avanceInput"+idPanier).val('');
        // if (data == 1) {
        //     window.location.reload();
        // } else {
        //     $('#msg_edit_pagnet').modal('show');
        // }
      },

      error: function () {
        alert("La requête ss");
      },
    });
  });
});

/**Debut modifier Tva Designation Pharmacie**/

function mdf_Tva(idDesignation, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 15,

      idDesignation: idDesignation,
    },

    success: function (data) {
      $("#idStock_Spm").val("");

      $("#ordre_Spm").val("");

      tab = data.split("<>");

      if (tab[0] == 1) {
        $(".tabDesign tr").each(function (row, tr) {
          ligne = $(tr).find("td:eq(0)").text();

          if (ligne != "" && ligne == ordre) {
            $(tr).find("td:eq(1)").html(tab[1]);

            $(tr).find("td:eq(2)").html(tab[2]);

            $(tr).find("td:eq(3)").html(tab[3]);

            $(tr).find("td:eq(4)").html(tab[4]);

            $(tr).find("td:eq(5)").html(tab[5]);

            $(tr).find("td:eq(6)").html(tab[6]);

            $(tr).find("td:eq(7)").html("En cours ...");
          }
        });
      }

      if (tab[0] == 2) {
        $(".tabDesign tr").each(function (row, tr) {
          ligne = $(tr).find("td:eq(0)").text();

          if (ligne != "" && ligne == ordre) {
            $(tr).find("td:eq(1)").html(tab[1]);

            $(tr).find("td:eq(2)").html(tab[2]);

            $(tr).find("td:eq(3)").html(tab[3]);

            $(tr).find("td:eq(4)").html(tab[4]);

            $(tr).find("td:eq(5)").html(tab[5]);

            $(tr).find("td:eq(6)").html(tab[6]);

            $(tr).find("td:eq(7)").html("En cours ...");
          }
        });
      }

      if (tab[0] == 3) {
        $(".tabDesign tr").each(function (row, tr) {
          ligne = $(tr).find("td:eq(0)").text();

          if (ligne != "" && ligne == ordre) {
            $(tr).find("td:eq(1)").html(tab[1]);

            $(tr).find("td:eq(2)").html(tab[2]);

            $(tr).find("td:eq(3)").html(tab[3]);

            $(tr).find("td:eq(4)").html(tab[4]);

            $(tr).find("td:eq(5)").html(tab[5]);

            $(tr).find("td:eq(6)").html(tab[6]);

            $(tr).find("td:eq(7)").html(tab[7]);

            $(tr).find("td:eq(8)").html("En cours ...");
          }
        });
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier Tva Designation Pharmacie**/

$(document).ready(function () {
  //set initial state.

  // alert(444)

  // $('#check1').val($(this).is(':checked'));

  $("#check1").change(function () {
    if ($(this).is(":checked")) {
      // var returnVal = confirm("Are you sure?");

      $(this).attr("checked", true);

      $("#divMontantAvoir").show();

      $("#montantAvoir").focus();

      $("#divMatP").show();

      $("#divNumCarnet").show();

      $("#personnelDiv").hide();

      $(this).val(1);
    } else {
      $("#divMontantAvoir").hide();

      $("#divMatP").hide();

      $("#divNumCarnet").hide();

      $("#personnelDiv").show();

      $(this).val(0);
    }

    // $('#check1').val($(this).is(':checked'));

    // $('#montantAvoir').hide();

    // alert($(this).val())
  });

  $("#check1_Mdf").change(function () {
    if ($(this).is(":checked")) {
      // var returnVal = confirm("Are you sure?");

      $(this).attr("checked", true);

      $("#montantAvoir_Mdf").show();

      $("#montantAvoir_Mdf").focus();

      $("#personnelDiv_Mdf").hide();

      $(this).val(1);
    } else {
      $("#montantAvoir_Mdf").hide();

      $("#personnelDiv_Mdf").show();

      $(this).val(0);
    }

    // $('#check1').val($(this).is(':checked'));

    // $('#montantAvoir').hide();

    // alert($(this).val())
  });

  $(".numCarnet").keyup(function (e) {
    e.preventDefault();
  });
});

/** Debut Autocomplete Designation Boutique*/
$(function () {
  $("#designationBt").keyup(function () {
    var query = $("#designationBt").val();
    if (query.length > 0 || query.length != "") {
      $.ajax({
        url: "ajax/vendreLigneAjax.php",
        method: "POST",
        data: {
          operation: 36,
          query: query,
        },
        success: function (data) {
          $("#reponseDesignation").html(data);
          //console.log('insertion impo'+data);
        },
        dataType: "text",
      });
    } else {
      $("#reponseDesignation").html();
    }
  });
  $(document).on("click", 'li[class="liBt"]', function () {
    var designation = $(this).text();
    $("#designationBt").val(designation);
    $.ajax({
      url: "ajax/vendreLigneAjax.php",
      method: "POST",
      data: {
        operation: 37,
        designation: designation,
      },
      success: function (data) {
        result = data.split("<>");
        $("#reponseDesignation").html(" ");
        if (result[0] == 1) {
          $("#idFusion").val(result[1]);
          $("#designation").val(result[2]);
          $("#categorie2").children("option:selected").text(result[3]);
          $("#uniteStock").val(result[4]);
          $("#nbArticleUniteStock").val(result[5]);
          $("#prix").val(result[6]);
          $("#prixuniteStock").val(result[7]);
          $("#codeBarre").val(result[8]);
        }
        //console.log('insertion impo'+data);
      },
      dataType: "text",
    });
    //  window.location.href = "insertionLigneLight.php?produit="+tab[0];
  });
});
/** Fin Autocomplete Designation Boutique*/

/**Debut Uploader Image Nouvelle Designation dans catalogue Boutique**/

function imgND_DesignationD(id) {
  $.ajax({
    url: "ajax/listerProduitAjax.php",

    method: "POST",

    data: {
      operation: 5,

      id: id,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#id_Upd_ND").val(tab[0]);
      //alert(tab[0]);
      $("#imageNvDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin Uploader Image Nouvelle  Designation dans catalogue Boutique**/

/**Debut Uploader Image Existante Designation dans catalogue Boutique**/

function imgEX_DesignationD(id) {
  $.ajax({
    url: "ajax/listerProduitAjax.php",

    method: "POST",

    data: {
      operation: 5,

      id: id,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#id_Upd_Ex").val(tab[0]);

      $("#des_Upd_Ex").val(tab[2]);

      $("#img_Upd_Ex").val(tab[6]);

      //$('#imgsrc_Upd').src='uploads/'+tab[6];

      $("#imgsrc_Upd").attr("src", "vitrine/uploads/" + tab[6]);

      $("#imgsrc_Upd").attr("alt", tab[6]);

      $("#imgsrc_Upd2").attr("src", "uploads/" + tab[6]);
      $("#imgsrc_Upd2").attr("alt", tab[6]);

      $("#imageExDesignation").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}
/**fIN Uploader Image Existante Designation dans catalogue Boutique**/

/**Debut Uploader Image Nouvelle Designation Vitrine**/
function imgNV_Categorie(id) {
  // alert(id)
  $.ajax({
    url: "ajax/operationAjax_Categorie.php",
    method: "POST",
    data: {
      operation: 5,
      id: id,
    },
    success: function (data) {
      tab = data.split("<>");
      // alert(tab[0])
      $("#id_Upd_NC").val(tab[0]);
      $("#idB_Upd_Ex").val(tab[3]);
      $("#imageNvCategorie").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}
/**Fin Uploader Image Nouvelle Categorie**/

/**Debut Uploader Image Existante Categorie**/
function imgEX_Categorie(id) {
  $.ajax({
    url: "ajax/operationAjax_Categorie.php",
    method: "POST",
    data: {
      operation: 5,
      id: id,
    },
    success: function (data) {
      tab = data.split("<>");
      $("#id_Upd_Ex").val(tab[0]);
      $("#img_Upd_Ex").val(tab[3]);
      //$('#imgsrc_Upd').src='uploads/'+tab[6];
      $("#imgsrc_Upd").attr("src", "imagesCategories/" + tab[3]);
      $("#imgsrc_Upd").attr("alt", tab[3]);
      $("#imageExCategorie").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}

/**Debut supprimer Categorie**/

function spm_Categorie(idCategorie, ordre) {
  $.ajax({
    url: "ajax/operationAjax_Categorie.php",

    method: "POST",

    data: {
      operation: 1,

      idCategorie: idCategorie,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#idCategorie_Spm").val(tab[1]);

        $("#ordreCT_Mdf").val(ordre);

        $("#categorie_Spm").val(tab[2]);
        $("#categorie_Spm_Hide").val(tab[2]);

        $("#supprimerCategorie").modal("show");
      }

      $.ajax({
        url: "ajax/operationAjax_Categorie.php",

        method: "POST",

        data: {
          operation: 7,
          nomCategorie: tab[2],
        },

        success: function (data) {
          $("#newCategorie").html(data);
        },

        error: function () {
          alert("La requête ");
        },

        dataType: "text",
      });
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin supprimer Categorie**/

/**Debut modifier Taux Panier**/
function modif_TauxPanier(taux, pagnet) {
  $.ajax({
    url: "ajax/modifierLigneAjax.php",

    method: "POST",

    data: {
      operation: 31,

      idPagnet: pagnet,

      taux: taux,
    },

    success: function (data) {
      tab1 = data.split("<>");

      $("#somme_Total" + pagnet).text(tab1[0]);

      $("#somme_Apayer" + pagnet).text(
        tab1[0] - $("#val_remise" + pagnet).val() - (tab1[0] * tab1[1]) / 100
      );
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}
/**Fin modifier Taux Panier**/

/*************** Début lister alertes seuil ***************/

$(document).ready(function () {
  nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_0").val();

  // $("#toggleSEUIL").on( "click", function (e){

  //     e.preventDefault();

  $(".loading-gif").show(); //show loading element

  $("#listeDesAlertesSeuil_0").load(
    "ajax/alerteSeuil_0Ajax.php",
    {
      operation: 1,
      nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
      query: "",
      cb: "",
    },
    function () {
      //get content from PHP page

      $(".loading-gif").hide(); //once done, hide loading element
    }
  );

  // });

  //executes code below when user click on pagination links

  $("#listeDesAlertesSeuil_0").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesSeuil").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_0").val();

    query = $("#searchInputAlerteSeuil_0").val();

    if (query.length == 0) {
      $("#listeDesAlertesSeuil_0").load(
        "ajax/alerteSeuil_0Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesSeuil_0").load(
        "ajax/alerteSeuil_0Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesSeuil").load("ajax/alerteSeuilAjax.php",{"page":page,"operation":1}
  });
});

$(document).ready(function () {
  nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_1").val();

  $("#toggleSEUIL_1").on("click", function (e) {
    e.preventDefault();

    $(".loading-gif").show(); //show loading element
    $("#listeDesAlertesSeuil_1").load(
      "ajax/alerteSeuil_1Ajax.php",
      {
        operation: 1,
        nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
        query: "",
        cb: "",
      },
      function () {
        //get content from PHP page

        $(".loading-gif").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#listeDesAlertesSeuil_1").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesSeuil").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(1)

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_1").val();

    query = $("#searchInputAlerteSeuil_1").val();

    if (query.length == 0) {
      $("#listeDesAlertesSeuil_1").load(
        "ajax/alerteSeuil_1Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesSeuil_1").load(
        "ajax/alerteSeuil_1Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesSeuil").load("ajax/alerteSeuilAjax.php",{"page":page,"operation":1}
  });
});

$(document).ready(function () {
  nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_2").val();

  $("#toggleSEUIL_2").on("click", function (e) {
    e.preventDefault();

    $(".loading-gif").show(); //show loading element

    $("#listeDesAlertesSeuil_2").load(
      "ajax/alerteSeuil_2Ajax.php",
      {
        operation: 1,
        nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
        query: "",
        cb: "",
      },
      function () {
        //get content from PHP page

        $(".loading-gif").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#listeDesAlertesSeuil_2").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesSeuil").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(1)

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_2").val();

    query = $("#searchInputAlerteSeuil_2").val();

    if (query.length == 0) {
      $("#listeDesAlertesSeuil_2").load(
        "ajax/alerteSeuil_2Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesSeuil_2").load(
        "ajax/alerteSeuil_2Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesSeuil").load("ajax/alerteSeuilAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister alertes seuil ***************/

/********  Début Tri sur les alertes seuil  ******/

$(document).ready(function () {
  $("#searchInputAlerteSeuil_0").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteSeuil_0").val();

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_0").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesSeuil_0").load("ajax/alerteSeuil_0Ajax.php", {
          operation: 3,
          nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesAlertesSeuil_0").load("ajax/alerteSeuil_0Ajax.php", {
          operation: 3,
          nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesSeuil_0").load("ajax/alerteSeuil_0Ajax.php", {
            operation: 3,
            nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesAlertesSeuil_0").load("ajax/alerteSeuil_0Ajax.php", {
            operation: 3,
            nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteSeuil_0").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_0").val();

    query = $("#searchInputAlerteSeuil_0").val();

    if (query.length == 0) {
      $("#listeDesAlertesSeuil_0").load("ajax/alerteSeuil_0Ajax.php", {
        operation: 4,
        nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesSeuil_0").load("ajax/alerteSeuil_0Ajax.php", {
        operation: 4,
        nbEntreeAlerteSeuil_0: nbEntreeAlerteSeuil,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesSeuil" ).load( "ajax/alerteSeuilAjax.php",{"operation":4,"nbEntreeAlerteSeuil":nbEntreeAlerteSeuil}); //load initial records
  });
});

$(document).ready(function () {
  $("#searchInputAlerteSeuil_1").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteSeuil_1").val();

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_1").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesSeuil_1").load("ajax/alerteSeuil_1Ajax.php", {
          operation: 3,
          nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesAlertesSeuil_1").load("ajax/alerteSeuil_1Ajax.php", {
          operation: 3,
          nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesSeuil_1").load("ajax/alerteSeuil_1Ajax.php", {
            operation: 3,
            nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesAlertesSeuil_1").load("ajax/alerteSeuil_1Ajax.php", {
            operation: 3,
            nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteSeuil_1").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_1").val();

    query = $("#searchInputAlerteSeuil_1").val();

    if (query.length == 0) {
      $("#listeDesAlertesSeuil_1").load("ajax/alerteSeuil_1Ajax.php", {
        operation: 4,
        nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesSeuil_1").load("ajax/alerteSeuil_1Ajax.php", {
        operation: 4,
        nbEntreeAlerteSeuil_1: nbEntreeAlerteSeuil,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesSeuil" ).load( "ajax/alerteSeuilAjax.php",{"operation":4,"nbEntreeAlerteSeuil":nbEntreeAlerteSeuil}); //load initial records
  });
});

$(document).ready(function () {
  $("#searchInputAlerteSeuil_2").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteSeuil_2").val();

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_2").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesSeuil_2").load("ajax/alerteSeuil_2Ajax.php", {
          operation: 3,
          nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesAlertesSeuil_2").load("ajax/alerteSeuil_2Ajax.php", {
          operation: 3,
          nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesSeuil_2").load("ajax/alerteSeuil_2Ajax.php", {
            operation: 3,
            nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesAlertesSeuil_2").load("ajax/alerteSeuil_2Ajax.php", {
            operation: 3,
            nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteSeuil_2").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteSeuil = $("#nbEntreeAlerteSeuil_2").val();

    query = $("#searchInputAlerteSeuil_2").val();

    if (query.length == 0) {
      $("#listeDesAlertesSeuil_2").load("ajax/alerteSeuil_2Ajax.php", {
        operation: 4,
        nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesSeuil_2").load("ajax/alerteSeuil_2Ajax.php", {
        operation: 4,
        nbEntreeAlerteSeuil_2: nbEntreeAlerteSeuil,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesSeuil" ).load( "ajax/alerteSeuilAjax.php",{"operation":4,"nbEntreeAlerteSeuil":nbEntreeAlerteSeuil}); //load initial records
  });
});

/*************  Fin Tri sur les alertes seuil   ******************/

/*************** Début lister alertes Depots ***************/

$(document).ready(function () {
  nbEntreeAlerteDepot_0 = $("#nbEntreeAlerteDepot_0").val();

  // $("#toggleSEUIL").on( "click", function (e){

  //     e.preventDefault();

  $(".loading-gif").show(); //show loading element

  $("#listeDesAlertesDepot_0").load(
    "ajax/alerteDepot_0Ajax.php",
    {
      operation: 1,
      nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
      query: "",
      cb: "",
    },
    function () {
      //get content from PHP page

      $(".loading-gif").hide(); //once done, hide loading element
    }
  );

  // });

  //executes code below when user click on pagination links

  $("#listeDesAlertesDepot_0").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesSeuil").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    nbEntreeAlerteDepot_0 = $("#nbEntreeAlerteDepot_0").val();

    query = $("#searchInputAlerteDepot_0").val();

    if (query.length == 0) {
      $("#listeDesAlertesDepot_0").load(
        "ajax/alerteDepot_0Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesDepot_0").load(
        "ajax/alerteDepot_0Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesSeuil").load("ajax/alerteSeuilAjax.php",{"page":page,"operation":1}
  });
});

$(document).ready(function () {
  nbEntreeAlerteDepot_1 = $("#nbEntreeAlerteDepot_1").val();

  $("#toggleDepot1").on("click", function (e) {
    e.preventDefault();

    $(".loading-gif").show(); //show loading element
    $("#listeDesAlertesDepot_1").load(
      "ajax/alerteDepot_1Ajax.php",
      {
        operation: 1,
        nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
        query: "",
        cb: "",
      },
      function () {
        //get content from PHP page

        $(".loading-gif").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#listeDesAlertesDepot_1").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesSeuil").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(1)

    nbEntreeAlerteDepot_1 = $("#nbEntreeAlerteDepot_1").val();

    query = $("#searchInputAlerteDepot_1").val();

    if (query.length == 0) {
      $("#listeDesAlertesDepot_1").load(
        "ajax/alerteDepot_1Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesDepot_1").load(
        "ajax/alerteDepot_1Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesSeuil").load("ajax/alerteSeuilAjax.php",{"page":page,"operation":1}
  });
});

$(document).ready(function () {
  nbEntreeAlerteDepot_2 = $("#nbEntreeAlerteDepot_2").val();

  $("#toggleDepot2").on("click", function (e) {
    e.preventDefault();

    $(".loading-gif").show(); //show loading element

    $("#listeDesAlertesDepot_2").load(
      "ajax/alerteDepot_2Ajax.php",
      {
        operation: 1,
        nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
        query: "",
        cb: "",
      },
      function () {
        //get content from PHP page

        $(".loading-gif").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#listeDesAlertesDepot_2").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesSeuil").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(1)

    nbEntreeAlerteDepot_2 = $("#nbEntreeAlerteDepot_2").val();

    query = $("#searchInputAlerteDepot_2").val();

    if (query.length == 0) {
      $("#listeDesAlertesDepot_2").load(
        "ajax/alerteDepot_2Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesDepot_2").load(
        "ajax/alerteDepot_2Ajax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesSeuil").load("ajax/alerteSeuilAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister alertes Depots ***************/

/********  Début Tri sur les alertes Depots  ******/

$(document).ready(function () {
  $("#searchInputAlerteDepot_0").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteDepot_0").val();

    nbEntreeAlerteDepot_0 = $("#nbEntreeAlerteDepot_0").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesDepot_0").load("ajax/alerteDepot_0Ajax.php", {
          operation: 3,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesAlertesDepot_0").load("ajax/alerteDepot_0Ajax.php", {
          operation: 3,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesDepot_0").load("ajax/alerteDepot_0Ajax.php", {
            operation: 3,
            nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesAlertesDepot_0").load("ajax/alerteDepot_0Ajax.php", {
            operation: 3,
            nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteDepot_0").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteDepot_0 = $("#nbEntreeAlerteDepot_0").val();

    query = $("#searchInputAlerteDepot_0").val();

    if (query.length == 0) {
      $("#listeDesAlertesDepot_0").load("ajax/alerteDepot_0Ajax.php", {
        operation: 4,
        nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesDepot_0").load("ajax/alerteDepot_0Ajax.php", {
        operation: 4,
        nbEntreeAlerteDepot: nbEntreeAlerteDepot_0,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesSeuil" ).load( "ajax/alerteSeuilAjax.php",{"operation":4,"nbEntreeAlerteSeuil":nbEntreeAlerteSeuil}); //load initial records
  });
});

$(document).ready(function () {
  $("#searchInputAlerteDepot_1").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteDepot_1").val();

    nbEntreeAlerteDepot_1 = $("#nbEntreeAlerteDepot_1").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesDepot_1").load("ajax/alerteDepot_1Ajax.php", {
          operation: 3,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesAlertesDepot_1").load("ajax/alerteDepot_1Ajax.php", {
          operation: 3,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesDepot_1").load("ajax/alerteDepot_1Ajax.php", {
            operation: 3,
            nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesAlertesDepot_1").load("ajax/alerteDepot_1Ajax.php", {
            operation: 3,
            nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteDepot_1").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteDepot_1 = $("#nbEntreeAlerteDepot_1").val();

    query = $("#searchInputAlerteDepot_1").val();

    if (query.length == 0) {
      $("#listeDesAlertesDepot_1").load("ajax/alerteDepot_1Ajax.php", {
        operation: 4,
        nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesDepot_1").load("ajax/alerteDepot_1Ajax.php", {
        operation: 4,
        nbEntreeAlerteDepot: nbEntreeAlerteDepot_1,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesSeuil" ).load( "ajax/alerteSeuilAjax.php",{"operation":4,"nbEntreeAlerteSeuil":nbEntreeAlerteSeuil}); //load initial records
  });
});

$(document).ready(function () {
  $("#searchInputAlerteDepot_2").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteDepot_2").val();

    nbEntreeAlerteDepot_2 = $("#nbEntreeAlerteDepot_2").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesDepot_2").load("ajax/alerteDepot_2Ajax.php", {
          operation: 3,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesAlertesDepot_2").load("ajax/alerteDepot_2Ajax.php", {
          operation: 3,
          nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesDepot_2").load("ajax/alerteDepot_2Ajax.php", {
            operation: 3,
            nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesAlertesDepot_2").load("ajax/alerteDepot_2Ajax.php", {
            operation: 3,
            nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteDepot_2").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteDepot_2 = $("#nbEntreeAlerteDepot_2").val();

    query = $("#searchInputAlerteDepot_2").val();

    if (query.length == 0) {
      $("#listeDesAlertesDepot_2").load("ajax/alerteDepot_2Ajax.php", {
        operation: 4,
        nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesDepot_2").load("ajax/alerteDepot_2Ajax.php", {
        operation: 4,
        nbEntreeAlerteDepot: nbEntreeAlerteDepot_2,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesSeuil" ).load( "ajax/alerteSeuilAjax.php",{"operation":4,"nbEntreeAlerteSeuil":nbEntreeAlerteSeuil}); //load initial records
  });
});

/*************  Fin Tri sur les alertes Depots   ******************/

/*************** Début lister alertes commande ***************/

$(document).ready(function () {
  nbEntreeAlerteCmd = $("#nbEntreeAlerteCmd").val();

  // $("#toggleJour").on( "click", function (e){

  //     e.preventDefault();

  $(".loading-gif").show(); //show loading element

  $("#listeDesAlertesCmdes").load(
    "ajax/alerteCommandeAjax.php",
    { operation: 1, nbEntreeAlerteCmd: nbEntreeAlerteCmd, query: "", cb: "" },
    function () {
      //get content from PHP page

      $(".loading-gif").hide(); //once done, hide loading element
    }
  );

  // });
  //executes code below when user click on pagination links

  $("#listeDesAlertesCmdes").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesCmdes").on( "click", function (e){

    e.preventDefault();

    $(".loading-gif").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeAlerteCmd = $("#nbEntreeAlerteCmd").val();

    query = $("#searchInputAlerteCmd").val();

    if (query.length == 0) {
      $("#listeDesAlertesCmdes").load(
        "ajax/alerteCommandeAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteCmd: nbEntreeAlerteCmd,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-gif").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesCmdes").load(
        "ajax/alerteCommandeAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteCmd: nbEntreeAlerteCmd,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-gif").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesCmdes").load("ajax/alerteCommandeAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister alertes commande ***************/

/*************  Debut Tri sur les alertes commande   ******************/

$(document).ready(function () {
  $("#searchInputAlerteCmd").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteCmd").val();

    nbEntreeAlerteCmd = $("#nbEntreeAlerteCmd").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesCmdes").load("ajax/alerteCommandeAjax.php", {
          operation: 3,
          nbEntreeAlerteCmd: nbEntreeAlerteCmd,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesAlertesCmdes").load("ajax/alerteCommandeAjax.php", {
          operation: 3,
          nbEntreeAlerteCmd: nbEntreeAlerteCmd,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesCmdes").load("ajax/alerteCommandeAjax.php", {
            operation: 3,
            nbEntreeAlerteCmd: nbEntreeAlerteCmd,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesAlertesCmdes").load("ajax/alerteCommandeAjax.php", {
            operation: 3,
            nbEntreeAlerteCmd: nbEntreeAlerteCmd,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteCmd").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteCmd = $("#nbEntreeAlerteCmd").val();

    query = $("#searchInputAlerteCmd").val();

    if (query.length == 0) {
      $("#listeDesAlertesCmdes").load("ajax/alerteCommandeAjax.php", {
        operation: 4,
        nbEntreeAlerteCmd: nbEntreeAlerteCmd,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesCmdes").load("ajax/alerteCommandeAjax.php", {
        operation: 4,
        nbEntreeAlerteCmd: nbEntreeAlerteCmd,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesCmdes" ).load( "ajax/alerteCommandeAjax.php",{"operation":4,"nbEntreeCli":nbEntreeCli}); //load initial records
  });
});

/*************  Fin Tri sur les alertes commande   ******************/

/*************** Début lister alertes expirations ***************/

$(document).ready(function () {
  // alert(1)

  // alert(100)

  // var page = 0;

  // $(document).on("click", "th", function() {

  //     alert( "Handler for .click() called." );

  // });

  nbEntreeAlerteExp = $("#nbEntreeAlerteExp").val();

  $("#toggleEXPIRATION").on("click", function (e) {
    e.preventDefault();

    $(".loading-gif").show(); //show loading element

    $("#listeDesAlertesExpirations").load(
      "ajax/alerteExpirationAjax.php",
      { operation: 1, nbEntreeAlerteExp: nbEntreeAlerteExp, query: "", cb: "" },
      function () {
        //get content from PHP page

        $(".loading-gif").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#listeDesAlertesExpirations").on("click", ".pagination a", function (e) {
    // $("#listeDesAlertesExpirations").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeAlerteExp = $("#nbEntreeAlerteExp").val();

    query = $("#searchInputAlerteExp").val();

    if (query.length == 0) {
      $("#listeDesAlertesExpirations").load(
        "ajax/alerteExpirationAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteExp: nbEntreeAlerteExp,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesAlertesExpirations").load(
        "ajax/alerteExpirationAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAlerteExp: nbEntreeAlerteExp,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesAlertesExpirations").load("ajax/alerteExpirationAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister alertes expirations ***************/

/*************  Debut Tri sur les alertes expirations   ******************/

$(document).ready(function () {
  $("#searchInputAlerteExp").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAlerteExp").val();

    nbEntreeAlerteExp = $("#nbEntreeAlerteExp").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesAlertesExpirations").load("ajax/alerteExpirationAjax.php", {
          operation: 3,
          nbEntreeAlerteExp: nbEntreeAlerteExp,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesAlertesExpirations").load("ajax/alerteExpirationAjax.php", {
          operation: 3,
          nbEntreeAlerteExp: nbEntreeAlerteExp,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesAlertesExpirations").load(
            "ajax/alerteExpirationAjax.php",
            {
              operation: 3,
              nbEntreeAlerteExp: nbEntreeAlerteExp,
              query: query,
              cb: t,
            }
          ); //load initial records
        } else {
          $("#listeDesAlertesExpirations").load(
            "ajax/alerteExpirationAjax.php",
            {
              operation: 3,
              nbEntreeAlerteExp: nbEntreeAlerteExp,
              query: "",
              cb: t,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAlerteExp").on("change", function (e) {
    e.preventDefault();

    nbEntreeAlerteExp = $("#nbEntreeAlerteExp").val();

    query = $("#searchInputAlerteExp").val();

    if (query.length == 0) {
      $("#listeDesAlertesExpirations").load("ajax/alerteExpirationAjax.php", {
        operation: 4,
        nbEntreeAlerteExp: nbEntreeAlerteExp,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesAlertesExpirations").load("ajax/alerteExpirationAjax.php", {
        operation: 4,
        nbEntreeAlerteExp: nbEntreeAlerteExp,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesAlertesExpirations" ).load( "ajax/alerteExpirationAjax.php",{"operation":4,"nbEntreeAlerteExp":nbEntreeAlerteExp}); //load initial records
  });
});

/*************  Fin Tri sur les alertes expirations   ******************/

/*************** Début lister les alertes echeances Fournisseurs ***************/

$(document).ready(function () {
  nbEntreeEcheanceFnr = $("#nbEntreeEcheanceFnr").val();

  $("#toggleECHEANCE").on("click", function (e) {
    e.preventDefault();

    $(".loading-gif").show(); //show loading element

    $("#listeDesEcheancesFournisseurs").load(
      "ajax/alerteEcheance_FournisseurAjax.php",
      {
        operation: 1,
        nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
        query: "",
        cb: "",
      },
      function () {
        //get content from PHP page

        $(".loading-gif").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#listeDesEcheancesFournisseurs").on(
    "click",
    ".pagination a",
    function (e) {
      // $("#listeDesEcheancesFournisseurs").on( "click", function (e){

      e.preventDefault();

      $(".loading-div").show(); //show loading element

      // page = page+1; //get page number from link

      page = $(this).attr("data-page"); //get page number from link

      //  alert(page)

      nbEntreeEcheanceFnr = $("#nbEntreeEcheanceFnr").val();

      query = $("#searchInputEcheanceFnr").val();

      if (query.length == 0) {
        $("#listeDesEcheancesFournisseurs").load(
          "ajax/alerteEcheance_FournisseurAjax.php",
          {
            page: page,
            operation: 1,
            nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
            query: "",
            cb: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      } else {
        $("#listeDesEcheancesFournisseurs").load(
          "ajax/alerteEcheance_FournisseurAjax.php",
          {
            page: page,
            operation: 1,
            nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
            query: query,
            cb: "",
          },
          function () {
            //get content from PHP page

            $(".loading-div").hide(); //once done, hide loading element
          }
        );
      }

      // $("#listeDesEcheancesFournisseurs").load("ajax/alerteEcheance_FournisseurAjax.php",{"page":page,"operation":1}
    }
  );
});

/*************** Fin lister les alertes echeances Fournisseurs ***************/

/*************  Debut Tri sur les alertes echeances Fournisseurs   ******************/

$(document).ready(function () {
  $("#searchInputEcheanceFnr").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputEcheanceFnr").val();

    nbEntreeEcheanceFnr = $("#nbEntreeEcheanceFnr").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesEcheancesFournisseurs").load(
          "ajax/alerteEcheance_FournisseurAjax.php",
          {
            operation: 3,
            nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
            query: query,
            cb: t,
          }
        ); //load initial records
      } else {
        $("#listeDesEcheancesFournisseurs").load(
          "ajax/alerteEcheance_FournisseurAjax.php",
          {
            operation: 3,
            nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
            query: "",
            cb: t,
          }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesEcheancesFournisseurs").load(
            "ajax/alerteEcheance_FournisseurAjax.php",
            {
              operation: 3,
              nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
              query: query,
              cb: t,
            }
          ); //load initial records
        } else {
          $("#listeDesEcheancesFournisseurs").load(
            "ajax/alerteEcheance_FournisseurAjax.php",
            {
              operation: 3,
              nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
              query: "",
              cb: t,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeEcheanceFnr").on("change", function (e) {
    e.preventDefault();

    nbEntreeEcheanceFnr = $("#nbEntreeEcheanceFnr").val();

    query = $("#searchInputEcheanceFnr").val();

    if (query.length == 0) {
      $("#listeDesEcheancesFournisseurs").load(
        "ajax/alerteEcheance_FournisseurAjax.php",
        {
          operation: 4,
          nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
          query: "",
          cb: "",
        }
      ); //load initial records
    } else {
      $("#listeDesEcheancesFournisseurs").load(
        "ajax/alerteEcheance_FournisseurAjax.php",
        {
          operation: 4,
          nbEntreeEcheanceFnr: nbEntreeEcheanceFnr,
          query: query,
          cb: "",
        }
      ); //load initial records
    }

    // $("#listeDesEcheancesFournisseurs" ).load( "ajax/alerteEcheance_FournisseurAjax.php",{"operation":4,"nbEntreeEcheanceFnr":nbEntreeEcheanceFnr}); //load initial records
  });
});

/*************  Fin Tri sur les alertes echeances Fournisseurs  ******************/

/*************** Début lister les alertes echeances clients ***************/

$(document).ready(function () {
  nbEntreeEcheanceCli = $("#nbEntreeEcheanceCli").val();

  $("#toggleECHEANCE").on("click", function (e) {
    e.preventDefault();

    $(".loading-gif").show(); //show loading element

    $("#listeDesEcheancesClients").load(
      "ajax/alerteEcheance_ClientAjax.php",
      {
        operation: 1,
        nbEntreeEcheanceCli: nbEntreeEcheanceCli,
        query: "",
        cb: "",
      },
      function () {
        //get content from PHP page

        $(".loading-gif").hide(); //once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links

  $("#listeDesEcheancesClients").on("click", ".pagination a", function (e) {
    // $("#listeDesEcheancesClients").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeEcheanceCli = $("#nbEntreeEcheanceCli").val();

    query = $("#searchInputEcheanceCli").val();

    if (query.length == 0) {
      $("#listeDesEcheancesClients").load(
        "ajax/alerteEcheance_ClientAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeEcheanceCli: nbEntreeEcheanceCli,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesEcheancesClients").load(
        "ajax/alerteEcheance_ClientAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeEcheanceCli: nbEntreeEcheanceCli,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesEcheancesClients").load("ajax/alerteEcheance_ClientAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister les alertes echeances clients ***************/

/*************  Debut Tri sur les alertes echeances clients   ******************/

$(document).ready(function () {
  $("#searchInputEcheanceCli").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputEcheanceCli").val();

    nbEntreeEcheanceCli = $("#nbEntreeEcheanceCli").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesEcheancesClients").load(
          "ajax/alerteEcheance_ClientAjax.php",
          {
            operation: 3,
            nbEntreeEcheanceCli: nbEntreeEcheanceCli,
            query: query,
            cb: t,
          }
        ); //load initial records
      } else {
        $("#listeDesEcheancesClients").load(
          "ajax/alerteEcheance_ClientAjax.php",
          {
            operation: 3,
            nbEntreeEcheanceCli: nbEntreeEcheanceCli,
            query: "",
            cb: t,
          }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesEcheancesClients").load(
            "ajax/alerteEcheance_ClientAjax.php",
            {
              operation: 3,
              nbEntreeEcheanceCli: nbEntreeEcheanceCli,
              query: query,
              cb: t,
            }
          ); //load initial records
        } else {
          $("#listeDesEcheancesClients").load(
            "ajax/alerteEcheance_ClientAjax.php",
            {
              operation: 3,
              nbEntreeEcheanceCli: nbEntreeEcheanceCli,
              query: "",
              cb: t,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeEcheanceCli").on("change", function (e) {
    e.preventDefault();

    nbEntreeEcheanceCli = $("#nbEntreeEcheanceCli").val();

    query = $("#searchInputEcheanceCli").val();

    if (query.length == 0) {
      $("#listeDesEcheancesClients").load(
        "ajax/alerteEcheance_ClientAjax.php",
        {
          operation: 4,
          nbEntreeEcheanceCli: nbEntreeEcheanceCli,
          query: "",
          cb: "",
        }
      ); //load initial records
    } else {
      $("#listeDesEcheancesClients").load(
        "ajax/alerteEcheance_ClientAjax.php",
        {
          operation: 4,
          nbEntreeEcheanceCli: nbEntreeEcheanceCli,
          query: query,
          cb: "",
        }
      ); //load initial records
    }

    // $("#listeDesEcheancesClients" ).load( "ajax/alerteEcheance_ClientAjax.php",{"operation":4,"nbEntreeEcheanceCli":nbEntreeEcheanceCli}); //load initial records
  });
});

/*************  Fin Tri sur les alertes echeances clients  ******************/

/*************** Début lister clients ***************/

$(document).ready(function () {
  nbEntreeCli = $("#nbEntreeCli").val();

  // $("#listeCliEvent").on( "click", function (e){
  $("#listeDesClients").load("ajax/listerClientAjax.php", {
    operation: 1,
    nbEntreeCli: nbEntreeCli,
    query: "",
    cb: "",
  }); //load initial records
  // });

  //executes code below when user click on pagination links

  $("#listeDesClients").on("click", ".pagination a", function (e) {
    // $("#listeDesClients").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeCli = $("#nbEntreeCli").val();

    query = $("#searchInputCli").val();

    if (query.length == 0) {
      $("#listeDesClients").load(
        "ajax/listerClientAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeCli: nbEntreeCli,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesClients").load(
        "ajax/listerClientAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeCli: nbEntreeCli,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesClients").load("ajax/listerClientAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister clients ***************/

/*************  Debut Tri sur les clients  ******************/

$(document).ready(function () {
  $("#searchInputCli").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputCli").val();

    nbEntreeCli = $("#nbEntreeCli").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesClients").load("ajax/listerClientAjax.php", {
          operation: 3,
          nbEntreeCli: nbEntreeCli,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesClients").load("ajax/listerClientAjax.php", {
          operation: 3,
          nbEntreeCli: nbEntreeCli,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesClients").load("ajax/listerClientAjax.php", {
            operation: 3,
            nbEntreeCli: nbEntreeCli,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesClients").load("ajax/listerClientAjax.php", {
            operation: 3,
            nbEntreeCli: nbEntreeCli,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeCli").on("change", function (e) {
    e.preventDefault();

    nbEntreeCli = $("#nbEntreeCli").val();

    query = $("#searchInputCli").val();

    if (query.length == 0) {
      $("#listeDesClients").load("ajax/listerClientAjax.php", {
        operation: 4,
        nbEntreeCli: nbEntreeCli,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesClients").load("ajax/listerClientAjax.php", {
        operation: 4,
        nbEntreeCli: nbEntreeCli,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesClients" ).load( "ajax/listerClientAjax.php",{"operation":4,"nbEntreeCli":nbEntreeCli}); //load initial records
  });
});

/*************  Fin Tri sur les clients  ******************/

/*************** Début lister depots ***************/

$(document).ready(function () {
  nbEntreeDepot = $("#nbEntreeDepot").val();

  $("#listeCliEvent2").on("click", function (e) {
    $("#listeDesDepots").load("ajax/listerClientDepotAjax.php", {
      operation: 1,
      nbEntreeDepot: nbEntreeDepot,
      query: "",
      cb: "",
    }); //load initial records
  });

  //executes code below when user click on pagination links

  $("#listeDesDepots").on("click", ".pagination a", function (e) {
    // $("#listeDesDepots").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeDepot = $("#nbEntreeDepot").val();

    query = $("#searchInputDepot").val();

    if (query.length == 0) {
      $("#listeDesDepots").load(
        "ajax/listerClientDepotAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeDepot: nbEntreeDepot,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesDepots").load(
        "ajax/listerClientDepotAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeDepot: nbEntreeDepot,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesDepots").load("ajax/listerClientDepotAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister depots ***************/

/*************  Debut Tri sur les depots  ******************/

$(document).ready(function () {
  $("#searchInputDepot").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputDepot").val();

    nbEntreeDepot = $("#nbEntreeDepot").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesDepots").load("ajax/listerClientDepotAjax.php", {
          operation: 3,
          nbEntreeDepot: nbEntreeDepot,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesDepots").load("ajax/listerClientDepotAjax.php", {
          operation: 3,
          nbEntreeDepot: nbEntreeDepot,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesDepots").load("ajax/listerClientDepotAjax.php", {
            operation: 3,
            nbEntreeDepot: nbEntreeDepot,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesDepots").load("ajax/listerClientDepotAjax.php", {
            operation: 3,
            nbEntreeDepot: nbEntreeDepot,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeDepot").on("change", function (e) {
    e.preventDefault();

    nbEntreeDepot = $("#nbEntreeDepot").val();

    query = $("#searchInputDepot").val();

    if (query.length == 0) {
      $("#listeDesDepots").load("ajax/listerClientDepotAjax.php", {
        operation: 4,
        nbEntreeDepot: nbEntreeDepot,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesDepots").load("ajax/listerClientDepotAjax.php", {
        operation: 4,
        nbEntreeDepot: nbEntreeDepot,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesDepots" ).load( "ajax/listerClientDepotAjax.php",{"operation":4,"nbEntreeCli":nbEntreeCli}); //load initial records
  });
});

/*************  Fin Tri sur les depots  ******************/

/*************** Début lister dettes ***************/

$(document).ready(function () {
  nbEntreeDette = $("#nbEntreeDette").val();

  $("#listeCliEvent3").on("click", function (e) {
    $("#listeDesDettes").load("ajax/listerClientDetteAjax.php", {
      operation: 1,
      nbEntreeDette: nbEntreeDette,
      query: "",
      cb: "",
    }); //load initial records
  });

  //executes code below when user click on pagination links

  $("#listeDesDettes").on("click", ".pagination a", function (e) {
    // $("#listeDesDettes").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeDette = $("#nbEntreeDette").val();

    query = $("#searchInputDette").val();

    if (query.length == 0) {
      $("#listeDesDettes").load(
        "ajax/listerClientDetteAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeDette: nbEntreeDette,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesDettes").load(
        "ajax/listerClientDetteAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeDette: nbEntreeDette,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeDesDettes").load("ajax/listerClientDetteAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister dettes ***************/

/*************  Debut Tri sur les dettes  ******************/

$(document).ready(function () {
  $("#searchInputDette").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputDette").val();

    nbEntreeDette = $("#nbEntreeDette").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesDettes").load("ajax/listerClientDetteAjax.php", {
          operation: 3,
          nbEntreeDette: nbEntreeDette,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesDettes").load("ajax/listerClientDetteAjax.php", {
          operation: 3,
          nbEntreeDette: nbEntreeDette,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesDettes").load("ajax/listerClientDetteAjax.php", {
            operation: 3,
            nbEntreeDette: nbEntreeDette,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesDettes").load("ajax/listerClientDetteAjax.php", {
            operation: 3,
            nbEntreeDette: nbEntreeDette,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeDette").on("change", function (e) {
    e.preventDefault();

    nbEntreeDette = $("#nbEntreeDette").val();

    query = $("#searchInputDette").val();

    if (query.length == 0) {
      $("#listeDesDettes").load("ajax/listerClientDetteAjax.php", {
        operation: 4,
        nbEntreeDette: nbEntreeDette,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesDettes").load("ajax/listerClientDetteAjax.php", {
        operation: 4,
        nbEntreeDette: nbEntreeDette,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesDettes" ).load( "ajax/listerClientDetteAjax.php",{"operation":4,"nbEntreeCli":nbEntreeCli}); //load initial records
  });
});

/*************  Fin Tri sur les dettes  ******************/

/*************** Début lister personnels ***************/

$(document).ready(function () {
  nbEntreePersonnel = $("#nbEntreePersonnel").val();

  $("#listeCliEvent4").on("click", function (e) {
    $("#listeClientPersonnels").load("ajax/listerClientPersonnelAjax.php", {
      operation: 1,
      nbEntreePersonnel: nbEntreePersonnel,
      query: "",
      cb: "",
    }); //load initial records
  });

  //executes code below when user click on pagination links

  $("#listeClientPersonnels").on("click", ".pagination a", function (e) {
    // $("#listeClientPersonnels").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreePersonnel = $("#nbEntreePersonnel").val();

    query = $("#searchInputPersonnel").val();

    if (query.length == 0) {
      $("#listeClientPersonnels").load(
        "ajax/listerClientPersonnelAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreePersonnel: nbEntreePersonnel,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeClientPersonnels").load(
        "ajax/listerClientPersonnelAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreePersonnel: nbEntreePersonnel,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#listeClientPersonnels").load("ajax/listerClientDetteAjax.php",{"page":page,"operation":1}
  });
});

/*************** Fin lister personnels ***************/

/*************  Debut Tri sur les personnels  ******************/

$(document).ready(function () {
  $("#searchInputPersonnel").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputPersonnel").val();

    nbEntreePersonnel = $("#nbEntreePersonnel").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeClientPersonnels").load("ajax/listerClientPersonnelAjax.php", {
          operation: 3,
          nbEntreePersonnel: nbEntreePersonnel,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeClientPersonnels").load("ajax/listerClientPersonnelAjax.php", {
          operation: 3,
          nbEntreePersonnel: nbEntreePersonnel,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeClientPersonnels").load(
            "ajax/listerClientPersonnelAjax.php",
            {
              operation: 3,
              nbEntreePersonnel: nbEntreePersonnel,
              query: query,
              cb: t,
            }
          ); //load initial records
        } else {
          $("#listeClientPersonnels").load(
            "ajax/listerClientPersonnelAjax.php",
            {
              operation: 3,
              nbEntreePersonnel: nbEntreePersonnel,
              query: "",
              cb: t,
            }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreePersonnel").on("change", function (e) {
    e.preventDefault();

    nbEntreePersonnel = $("#nbEntreePersonnel").val();

    query = $("#searchInputPersonnel").val();

    if (query.length == 0) {
      $("#listeClientPersonnels").load("ajax/listerClientPersonnelAjax.php", {
        operation: 4,
        nbEntreePersonnel: nbEntreePersonnel,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeClientPersonnels").load("ajax/listerClientPersonnelAjax.php", {
        operation: 4,
        nbEntreePersonnel: nbEntreePersonnel,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeClientPersonnels" ).load( "ajax/listerClientDetteAjax.php",{"operation":4,"nbEntreeCli":nbEntreeCli}); //load initial records
  });
});

/*************  Fin Tri sur les personnels  ******************/

/*************** Début lister clients archivés ***************/

$(document).ready(function () {
  nbEntreeArch = $("#nbEntreeArch").val();

  $("#listeCliEvent5").on("click", function (e) {
    $("#listeClientArchives").load("ajax/listerClientArchiverAjax.php", {
      operation: 1,
      nbEntreeArch: nbEntreeArch,
      query: "",
      cb: "",
    }); //load initial records
  });

  //executes code below when user click on pagination links

  $("#listeClientArchives").on("click", ".pagination a", function (e) {
    // $("#listeDesDepots").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntreeArch = $("#nbEntreeArch").val();

    query = $("#searchInputArch").val();

    if (query.length == 0) {
      $("#listeClientArchives").load(
        "ajax/listerClientArchiverAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeArch: nbEntreeArch,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeClientArchives").load(
        "ajax/listerClientArchiverAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeArch: nbEntreeArch,
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

/*************** Fin lister clients archivés ***************/

/*************  Debut Tri sur les clients archivés  ******************/

$(document).ready(function () {
  $("#searchInputArch").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputArch").val();

    nbEntreeArch = $("#nbEntreeArch").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#listeClientArchives").load("ajax/listerClientArchiverAjax.php", {
          operation: 3,
          nbEntreeArch: nbEntreeArch,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeClientArchives").load("ajax/listerClientArchiverAjax.php", {
          operation: 3,
          nbEntreeArch: nbEntreeArch,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#listeClientArchives").load("ajax/listerClientArchiverAjax.php", {
            operation: 3,
            nbEntreeArch: nbEntreeArch,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeClientArchives").load("ajax/listerClientArchiverAjax.php", {
            operation: 3,
            nbEntreeArch: nbEntreeArch,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeArch").on("change", function (e) {
    e.preventDefault();

    nbEntreeArch = $("#nbEntreeArch").val();

    query = $("#searchInputArch").val();

    if (query.length == 0) {
      $("#listeClientArchives").load("ajax/listerClientArchiverAjax.php", {
        operation: 4,
        nbEntreeArch: nbEntreeArch,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeClientArchives").load("ajax/listerClientArchiverAjax.php", {
        operation: 4,
        nbEntreeArch: nbEntreeArch,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesDepots" ).load( "ajax/listerClientArchiverAjax.php",{"operation":4,"nbEntreeCli":nbEntreeCli}); //load initial records
  });
});

/*************  Fin Tri sur les clients archivés  ******************/

/*************** Début lister les clients avoir  ***************/
$(document).ready(function () {
  nbEntreeAvoir = $("#nbEntreeAvoir").val();

  $("#listeCliEvent6").on("click", function (e) {
    $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
      operation: 1,
      nbEntreeAvoir: nbEntreeAvoir,
      query: "",
      cb: "",
    }); //load initial records
  });

  //executes code below when user click on pagination links
  $("#listeDesClientsAvoir").on("click", ".pagination a", function (e) {
    // $("#listeDesClientsAvoir").on( "click", function (e){
    e.preventDefault();
    $(".loading-div").show(); //show loading element
    // page = page+1; //get page number from link
    page = $(this).attr("data-page"); //get page number from link
    //  alert(page)

    nbEntreeAvoir = $("#nbEntreeAvoir").val();
    query = $("#searchInputAvoir").val();

    if (query.length == 0) {
      $("#listeDesClientsAvoir").load(
        "ajax/listerClientAvoirAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAvoir: nbEntreeAvoir,
          query: "",
          cb: "",
        },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#listeDesClientsAvoir").load(
        "ajax/listerClientAvoirAjax.php",
        {
          page: page,
          operation: 1,
          nbEntreeAvoir: nbEntreeAvoir,
          query: query,
          cb: "",
        },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }
    // $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php",{"page":page,"operation":1}
  });
});
/*************** Fin lister les clients avoir  ***************/

/*************  Debut Tri sur les clients avoir  ******************/
$(document).ready(function () {
  $("#searchInputAvoir").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputAvoir").val();
    nbEntreeAvoir = $("#nbEntreeAvoir").val();

    var keycode = e.keyCode ? e.keyCode : e.which;
    if (keycode == "13") {
      // alert(1111)
      t = 1; // code barre

      if (query.length > 0) {
        $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
          operation: 3,
          nbEntreeAvoir: nbEntreeAvoir,
          query: query,
          cb: t,
        }); //load initial records
      } else {
        $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
          operation: 3,
          nbEntreeAvoir: nbEntreeAvoir,
          query: "",
          cb: t,
        }); //load initial records
      }
    } else {
      // alert(2222)
      t = 0; // no code barre
      setTimeout(() => {
        if (query.length > 0) {
          $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
            operation: 3,
            nbEntreeAvoir: nbEntreeAvoir,
            query: query,
            cb: t,
          }); //load initial records
        } else {
          $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
            operation: 3,
            nbEntreeAvoir: nbEntreeAvoir,
            query: "",
            cb: t,
          }); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeAvoir").on("change", function (e) {
    e.preventDefault();

    nbEntreeAvoir = $("#nbEntreeAvoir").val();
    query = $("#searchInputAvoir").val();

    if (query.length == 0) {
      $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
        operation: 4,
        nbEntreeAvoir: nbEntreeAvoir,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
        operation: 4,
        nbEntreeAvoir: nbEntreeAvoir,
        query: query,
        cb: "",
      }); //load initial records
    }

    // $("#listeDesClientsAvoir" ).load( "ajax/listerClientAvoirAjax.php",{"operation":4,"nbEntreeAvoir":nbEntreeAvoir}); //load initial records
  });
});

$(document).ready(function () {
  triB = 0;
  $(document).on("click", "#tableClient th", function (e) {
    e.preventDefault();
    //    alert(12)
    query = $("#searchInputAvoir").val();
    nbEntreeAvoir = $("#nbEntreeAvoir").val();
    // $("#listeDesClientsAvoir" ).load( "ajax/listerClientAvoirAjax.php",{"operation":2,"nbEntreeAvoir":nbEntreeAvoir,"tri":1, "query":""}); //load initial records

    if (triB == 1) {
      // alert(triB)
      if (query.length == 0) {
        $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
          operation: 2,
          nbEntreeAvoir: nbEntreeAvoir,
          tri: 1,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
          operation: 2,
          nbEntreeAvoir: nbEntreeAvoir,
          tri: 1,
          query: query,
          cb: "",
        }); //load initial records
      }

      triB = 0;
      // alert(triB)
    } else {
      // alert(triB)
      if (query.length == 0) {
        $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
          operation: 2,
          nbEntreeAvoir: nbEntreeAvoir,
          tri: 0,
          query: "",
          cb: "",
        }); //load initial records
      } else {
        $("#listeDesClientsAvoir").load("ajax/listerClientAvoirAjax.php", {
          operation: 2,
          nbEntreeAvoir: nbEntreeAvoir,
          tri: 0,
          query: query,
          cb: "",
        }); //load initial records
      }

      triB = 1;
      // alert(triB)
    }
  });
});
/*************  Fin Tri sur les clients avoir  ******************/

function sumQtyByPeriod(idDesignation) {
  date1 = $("#date1").val();
  date2 = $("#date2").val();

  // alert(numeroChassis+"/"+numeroMoteur);

  if (idDesignation) {
    $.ajax({
      url: "ajax/operationPanierAjax.php",

      method: "POST",

      data: {
        sumQtyByPeriod: "sumQtyByPeriod",
        idDesignation: idDesignation,
        date1: date1,
        date2: date2,
      },

      success: function (data) {
        // alert(data)
        $("#spandate1").text(date1);
        $("#spandate2").text(date2);
        if (data != "") {
          $("#labelTotal").text(data);
        } else {
          $("#labelTotal").text(0);
        }
        $("#sumQtyByPeriod").modal("show");
      },

      error: function (err) {
        console.log(err);
        // alert(err);
      },

      dataType: "text",
    });
  }
}

function add_infoSup(numLigne) {
  // alert(idPagnet);
  numeroChassis = $("#numeroChassis" + numLigne).val();
  numeroMoteur = $("#numeroMoteur" + numLigne).val();
  couleur = $("#couleur" + numLigne).val();

  // alert(numeroChassis+"/"+numeroMoteur);

  if (numLigne) {
    $.ajax({
      url: "ajax/operationPanierAjax.php",

      method: "POST",

      data: {
        addInfoSup: "addInfoSup",
        numLigne: numLigne,
        numeroChassis: numeroChassis,
        numeroMoteur: numeroMoteur,
        couleur: couleur,
      },

      success: function (data) {
        // alert(data)
        $("#add_infoSup_modal" + numLigne).modal("hide");
      },

      error: function (err) {
        console.log(err);
        // alert(err);
      },

      dataType: "text",
    });
  }
}

function add_infoSup1(numLigne, numeroChassis, numeroMoteur, couleur) {
  if (numLigne) {
    $.ajax({
      url: "ajax/operationPanierAjax.php",

      method: "POST",

      data: {
        addInfoSup: "addInfoSup",
        numLigne: numLigne,
        numeroChassis: numeroChassis,
        numeroMoteur: numeroMoteur,
        couleur: couleur,
      },

      success: function (data) {
        // alert(data)
        $("#add_infoSup_modal").modal("hide");
      },

      error: function (err) {
        console.log(err);
        alert(err);
      },

      dataType: "text",
    });
  }
}

function sumVenteByPeriod() {
  date1VD = $("#date1VD").val();
  date2VD = $("#date2VD").val();
  $("#results_vente_par_date").hide();
  $(".loading-gif").show();

  if (date1VD == "" || date2VD == "") {
    alert("Il manque une date");
  } else {
    $("#results_vente_par_date").load(
      "ajax/listerVenteParDateAjax.php",
      {
        operation: 1,
        nbEntreeVD: nbEntreeVD,
        date1VD: date1VD,
        date2VD: date2VD,
      },
      function (data) {
        //get content from PHP page

        // alert(data);
        $("#date1VDHidden").val(date1VD);
        $("#date2VDHidden").val(date2VD);

        $(".loading-gif").hide();
        $("#results_vente_par_date").show();
      }
    );
  }
}

function getDepot() {
  // alert(1111)

  $.ajax({
    url: "ajax/operationStockAjax.php",

    method: "POST",

    data: {
      operation: 1,
    },

    success: function (data) {
      // alert(data)
      var data = JSON.parse(data);

      var taille = data.length;
      $(".depotChoix").html("");

      for (var i = 0; i < taille; i++) {
        // var tab = data[i];

        res = data[i].split("<>");

        // alert(data[i])

        $(".depotChoix").append(
          "<option value='" + res[0] + "'>" + res[1] + "</option>"
        );
      }
    },

    error: function () {
      alert("La requête 4");
    },

    dataType: "text",
  });
}

/**Debut ajouter Stock Entrepot**/

function ajt_Stock_ET(designation) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var uniteStock = $("#uniteStock-" + designation).val();

  var prixuniteStock = $("#prixuniteStock-" + designation).val();

  var prixunitaire = $("#prixunitaire-" + designation).val();

  var prixachat = $("#prixachat-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  tab = dateExpiration.split("-");

  dateExpiration = tab[2] + "-" + tab[1] + "-" + tab[0];

  var idDepot = $("#depotChoix-" + designation).val();

  $("#btn_AjtStock-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 33,

      idDesignation: designation,

      quantite: quantite,

      uniteStock: uniteStock,

      prixUniteStock: prixuniteStock,

      prixUnitaire: prixunitaire,

      prixAchat: prixachat,

      dateExpiration: dateExpiration,

      idDepot: idDepot,
    },

    success: function (data) {
      // alert(data)

      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr><td>0</td><td>" +
          tab[1] +
          "</td><td>" +
          tab[2] +
          "</td><td>" +
          quantite +
          "</td><td></td><td>" +
          tab[3] +
          "</td><td>" +
          tab[4] +
          "</td><td>" +
          tab[5] +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#uniteStock-" + designation).prop("disabled", true);

        $("#prixuniteStock-" + designation).prop("disabled", true);

        $("#prixunitaire-" + designation).prop("disabled", true);

        $("#prixachat-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else {
        alert(data);

        //alert("Erreur");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Stock Entrepot**/

/**Debut ajouter Stock BL Entrepot**/

function ajt_Stock_Bl_ET(designation, idBl) {
  var quantite = $("#quantiteAStocke-" + designation).val();

  var prixAchat = $("#prixAchat-" + designation).val();

  var prixUniteStock = $("#prixUniteStock-" + designation).val();

  var prixUnitaire = $("#prixUnitaire-" + designation).val();

  var dateExpiration = $("#dateExpiration-" + designation).val();

  tab = dateExpiration.split("-");

  dateExpiration = tab[2] + "-" + tab[1] + "-" + tab[0];

  var idDepot = $("#depotChoix-" + designation).val();

  $("#btn_AjtStock_P-" + designation).prop("disabled", true);

  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 54,

      idDesignation: designation,

      idBl: idBl,

      quantite: quantite,

      prixAchat: prixAchat,

      prixUniteStock: prixUniteStock,

      prixUnitaire: prixUnitaire,

      dateExpiration: dateExpiration,

      idDepot: idDepot,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        var ligne =
          "<tr id='stock'><td>0</td><td>" +
          tab[1] +
          "</td><td>" +
          tab[2] +
          "</td><td>" +
          quantite +
          "</td><td>" +
          quantite +
          "</td><td>" +
          prixAchat +
          "</td><td>" +
          prixUniteStock +
          "</td><td>" +
          tab[3] +
          "</td><td>" +
          dateExpiration +
          "</td><td>En cours ...</td></tr>";

        $("table.tabStock").prepend(ligne);

        $("#stock").css({ "background-color": "green" });

        $("#quantiteAStocke-" + designation).prop("disabled", true);

        $("#dateExpiration-" + designation).prop("disabled", true);
      } else {
        $("#msg_info_js").modal("show");
      }

      //console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin ajouter Stock BL Entrepot**/

$(document).ready(function () {
  $(document).on("keyup", ".categorieSearch", function (e) {
    e.preventDefault();

    query = $(this).val();

    idInput = $(this).attr("id");
    splitIdInput = idInput.split("-");
    idDesignation = splitIdInput[1];
    // alert(query);

    if (query.length > 0) {
      /*********** Modification **************/

      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/operationAjax_Categorie.php",

            method: "POST",

            data: {
              operation: 8,

              query: query,

              idDesignation: idDesignation,
            },

            dataType: "json",

            success: function (data) {
              // alert(data)

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

      $(this).focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        var categorie = $("#tableCategorieRef .typeahead li.active").text();

        // alert(idDesignation);

        $.ajax({
          url: "ajax/operationAjax_Categorie.php",

          method: "POST",

          data: {
            operation: 9,

            idDesignation: idDesignation,

            categorie: categorie,
          },

          success: function (data) {
            // alert(data)
            // $('#categorieActu-'+idDesignation).html(data);
            // $('#categorieActu-'+idDesignation).parent().css( "background-color", "green" );

            $("#categorieActu-" + idDesignation).fadeOut(400, function () {
              $(this).html(data).fadeIn(400);
              // $(this).html(data).fadeIn(400).parent().css("background-color", "green");
              // setTimeout(() => {
              //     $(this).parent().css("background-color", "");
              // }, 1000);
            });

            $("#tableCategorieRef .typeahead").html("");

            $("#categorie-" + idDesignation).val("");
          },

          dataType: "text",
        });
      }

      /*********** Fin tape sur Entrer **************/
    }
  });

  $(document).on(
    "click",
    "#tableCategorieRef .typeahead li.active",
    function (e) {
      e.preventDefault();

      var categorie = $(this).text();

      // alert(idDesignation);

      $.ajax({
        url: "ajax/operationAjax_Categorie.php",

        method: "POST",

        data: {
          operation: 9,

          idDesignation: idDesignation,

          categorie: categorie,
        },

        success: function (data) {
          // alert(data)
          // $('#categorieActu-'+idDesignation).html(data);

          $("#categorieActu-" + idDesignation).fadeOut(400, function () {
            $(this).html(data).fadeIn(400);
            // $(this).html(data).fadeIn(400).parent().css("background-color", "green");
            // setTimeout(() => {
            //     $(this).parent().css("background-color", "");
            // }, 1000);
          });

          // $('#categorieActu-'+idDesignation).parent().css( "background-color", "green" );

          $("#tableCategorieRef .typeahead").html("");

          $("#categorie-" + idDesignation).val("");
        },

        dataType: "text",
      });
    }
  );

  $(document).on("click", ".terminer_categorie", function (e) {
    $(this).parent().parent().css("background", "green").hide(2000); // OR $(this).parents('tr').remove();
  });

  $(document).on("click", ".mdf_categorie", function (e) {
    idDesignation = $(this).attr("id").split("-")[1];

    $("#mdfId").val(idDesignation);

    $.ajax({
      url: "ajax/operationAjax_Categorie.php",

      method: "POST",

      data: {
        operation: 10,

        idDesignation: idDesignation,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)
        $("#nomProduit").html(data);
        $("#confirmMdfCategorie").modal("show");
      },

      error: function (err) {
        console.log(err);
      },
    });
  });

  $(document).on("click", ".refaire_categorie", function (e) {
    id = $(this).attr("id").split("-")[1];
    $("#refaireId").val(id);
    $("#confirmRefCategorie").modal("show");
  });

  $(document).on("keyup", ".categorieSearchMdf", function (e) {
    e.preventDefault();

    query = $(this).val();

    // idInput = $(this).attr('id');
    // splitIdInput = idInput.split("-");
    idDesignation = $("#mdfId").val();
    // alert(query + ' / '+idDesignation);

    if (query.length > 0) {
      /*********** Modification **************/

      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/operationAjax_Categorie.php",

            method: "POST",

            data: {
              operation: 8,

              query: query,

              idDesignation: idDesignation,
            },

            dataType: "json",

            success: function (data) {
              // alert(data)

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

      $(this).focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      if (keycode == "13") {
        var categorie = $("#listCategory .typeahead li.active").text();

        // alert(idDesignation);

        $.ajax({
          url: "ajax/operationAjax_Categorie.php",

          method: "POST",

          data: {
            operation: 13,

            idDesignation: idDesignation,

            categorie: categorie,
          },

          success: function (data) {
            // alert(data)
            $("#confirmMdfCategorie").modal("hide");
            $("#categorieActu2-" + idDesignation).html(data);
            $("#categorieActu2-" + idDesignation)
              .parent()
              .parent()
              .css("background", "blue")
              .hide(1000); // OR $(this).parents('tr').remove();
            $("#categorieActu2-" + idDesignation)
              .parent()
              .parent()
              .css("background", "")
              .show(1000); // OR $(this).parents('tr').remove();

            $("#listCategory .typeahead").html("");
          },

          dataType: "text",
        });
      }

      /*********** Fin tape sur Entrer **************/
    }
  });

  $(document).on("click", "#listCategory .typeahead li.active", function (e) {
    e.preventDefault();

    var categorie = $(this).text();

    // alert(idDesignation);

    $.ajax({
      url: "ajax/operationAjax_Categorie.php",

      method: "POST",

      data: {
        operation: 13,

        idDesignation: idDesignation,

        categorie: categorie,
      },

      success: function (data) {
        // alert(data)
        $("#confirmMdfCategorie").modal("hide");
        $("#categorieActu2-" + idDesignation).html(data);
        $("#categorieActu2-" + idDesignation)
          .parent()
          .parent()
          .css("background", "blue")
          .hide(1000); // OR $(this).parents('tr').remove();
        $("#categorieActu2-" + idDesignation)
          .parent()
          .parent()
          .css("background", "")
          .show(1000); // OR $(this).parents('tr').remove();

        $("#listCategory .typeahead").html("");
      },

      dataType: "text",
    });
  });
});

// function mdfCategorie() {

//     idDesignation = $("#mdfId").val();
//     // alert(idDesignation)

//     // console.log($( "input:checked" ))

//     categoryNew=[];

//     // if ($(this).is(':checked')) {
//     $("input:checkbox[name=check_list_categorie]:not(:checked)").each(function(){
//         // console.log($(this).attr('data-id'));
//         categoryNew.push($(this).val());
//     });

//     // console.log(categoryNew);

//     $.ajax({

//         url: "ajax/operationAjax_Categorie.php",

//         method: "POST",

//         data: {

//             operation: 12,

//             idDesignation: idDesignation,

//             categoryNew: categoryNew

//         },

//         dataType: "text",

//         success: function(data) {

//             // alert(data)
//             $("#confirmMdfCategorie").modal("hide");

//             if (data==1) {
//                 // alert('if')
//                 $("#categorieActu2-"+idDesignation).parent().parent().css('background','red').hide(2000); // OR $(this).parents('tr').remove();
//                 // $("#categorieActu-"+idDesignation).attr('id','');
//             } else {
//                 // alert('else')
//                 $('#categorieActu2-'+idDesignation).html(data);
//                 $("#categorieActu2-"+idDesignation).parent().parent().css('background','blue').hide(1000); // OR $(this).parents('tr').remove();
//                 $("#categorieActu2-"+idDesignation).parent().parent().css('background','').show(1000); // OR $(this).parents('tr').remove();
//             }

//         },

//         error: function(err) {

//             console.log(err);

//         }

//     });
// }

function refaireCategorie() {
  idDesignation = $("#refaireId").val();

  $.ajax({
    url: "ajax/operationAjax_Categorie.php",

    method: "POST",

    data: {
      operation: 11,

      idDesignation: idDesignation,
    },

    dataType: "text",

    success: function (data) {
      // alert(data)

      $("#confirmRefCategorie").modal("hide");
      $("#btn_RefreshCateg-" + idDesignation)
        .parent()
        .parent()
        .css("background", "gold")
        .hide(2000); // OR $(this).parents('tr').remove();
    },

    error: function (err) {
      console.log(err);
    },
  });
}

function getDesignationPromo(idDesignation_promo) {
  // alert(idDesignation_promo)
  $.ajax({
    url: "ajax/operationAjax_Categorie.php",

    method: "POST",

    data: {
      operation: 14,

      idDesignation_promo: idDesignation_promo,
    },

    success: function (data) {
      tab = data.split("<>");

      $("#idDesignation_promo").val(tab[0]);
      $("#categorie_promo").val(tab[1]);
      //alert(tab[0]);
      $("#promo").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function terminer_promo() {
  // alert(idDesignation_promo)

  idDesignation = $("#idDesignation_promo").val();
  categorie = $("#categorie_promo").val();
  prixPromo = $("#prixPromo").val();

  alert(idDesignation + " / " + categorie + " / " + prixPromo);

  if (prixPromo != "" || prixPromo != 0) {
    $.ajax({
      url: "ajax/operationAjax_Categorie.php",

      method: "POST",

      data: {
        operation: 15,
        idDesignation_promo: idDesignation,
        categorie: categorie,
        prixPromo: prixPromo,
      },

      success: function (data) {
        // alert(data)
        $("#promo").modal("hide");

        $("#promo" + idDesignation)
          .parent()
          .parent()
          .parent()
          .css("background", "")
          .hide(2000); // OR $(this).parents('tr').remove();
        $("#promo" + idDesignation)
          .parent()
          .parent()
          .parent()
          .css("background", "green")
          .show(2000);
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    alert("Veuillez renseigner le prix de la promotion !");
  }
}

$(function () {
  $(document).on("keyup", ".clientProforma", function (e) {
    e.preventDefault();
    // alert(1)
    idPanier = $(this).attr("data-idPanier");

    var query = $(this).val();

    if (query.length > 0) {
      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 39,

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

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      var text = $("#clientProformaDiv .typeahead li.active").text();

      if (text.indexOf(" -- ") == -1) {
        // will not be triggered because str has _..
        idClient = 0;
        nomClient = $("#clientProforma" + idPanier).val();
      } else {
        tab = text.split(" -- ");

        idClient = tab[0];
        nomClient = tab[1];
      }

      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 40,

          nomClient: nomClient,

          idClient: idClient,

          idPanier: idPanier,
        },

        dataType: "text",

        success: function (data) {
          // alert(data)
          $("#clientProforma" + idPanier).val(tab[1]);

          $("#clientProformaDiv .typeahead").html("");

          // $("#changedOk"+idPanier).show(1000);

          // setTimeout(() => {
          //     $("#changedOk"+idPanier).hide(1000);
          // }, 5000);

          // $("#avanceInput"+idPanier).focus();
        },

        error: function (err) {
          console.log(err);
        },
      });
    }
  });

  $(document).on(
    "click",
    "#clientProformaDiv .typeahead li.active",
    function (e) {
      e.preventDefault();

      var text = $("#clientProformaDiv .typeahead li.active").text();

      if (text.indexOf(" -- ") == -1) {
        // will not be triggered because str has _..
        idClient = 0;
        nomClient = $("#clientProforma" + idPanier).val();
      } else {
        tab = text.split(" -- ");

        idClient = tab[0];
        nomClient = tab[1];
      }

      // alert(nomClient+' // '+idClient)

      $.ajax({
        url: "ajax/vendreLigneAjax.php",

        method: "POST",

        data: {
          operation: 40,

          nomClient: nomClient,

          idClient: idClient,

          idPanier: idPanier,
        },

        dataType: "text",

        success: function (data) {
          // alert(data)

          $("#clientProforma" + idPanier).val(tab[1]);

          $("#clientProformaDiv .typeahead").html("");

          // $("#changedOk"+idPanier).show(1000);

          // setTimeout(() => {
          //     $("#changedOk"+idPanier).hide(1000);
          // }, 5000);

          // $("#avanceInput"+idPanier).focus();
        },

        error: function (err) {
          console.log(err);
        },
      });
    }
  );
});

function validerProforma(idPagnet) {
  $.ajax({
    url: "ajax/vendreLigneAjax.php",
    method: "POST",
    data: {
      operation: 41,
      idPanier: idPagnet,
    },
    dataType: "text",
    success: function (data) {
      // alert(1)
      // alert(data)

      if (data == 1) {
        $(".loading-gif").show();
        $("#entrepot-venteContent").load(
          "ajax/loadContainer-entrepotAjax.php",
          function (data) {
            //get content from PHP page

            $(".loading-gif").hide();
            $(".modal-backdrop").remove();
            // window.scrollTo(60);
            $("body").attr("style", "overflow-y:scroll;");
          }
        );
      } else {
        window.location.reload();
      }

      // // if (data == 'ok') {
      // window.location.href = "vitrine/commande";
      // }
    },
    error: function () {
      alert("La requête ddd");
    },
  });
}

function FrontImage() {
  // alert(1111)
  $(".intro").fadeOut(200).delay(50).fadeIn(200);
  $(".intro").css("background", "green").hide(2000); // OR $(this).parents('tr').remove();
  $(".intro").css("background", "green").show(2000);
}
$(document).ready(function () {
  setInterval("FrontImage()", 1000);
});

function print_Transfert(idEntrepotTransfert) {
  //    alert(idEntrepotTransfert)

  valActu = $("#transfertCheckedList").val();

  if ($("#transfertChecked" + idEntrepotTransfert).is(":checked")) {
    if (valActu == "") {
      $("#transfertCheckedList").val(idEntrepotTransfert);
    } else {
      $("#transfertCheckedList").val(valActu + " | " + idEntrepotTransfert);
    }
    $("#printTransfertChecked").prop("disabled", false);
  } else {
    splitValActu = valActu.split(" | ");
    splitValActu = jQuery.grep(splitValActu, function (value) {
      return value != idEntrepotTransfert;
    });
    // console.log(splitValActu);

    $("#transfertCheckedList").val(splitValActu.join(" | "));
    if ($("#transfertCheckedList").val() == "") {
      $("#printTransfertChecked").prop("disabled", true);
    }
  }
}

function archiver_Designation(idDesignation, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 102,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#ordre_Archiver").val(ordre);
        $("#idDesignation_Archiver").val(idDesignation);
        $("#archiverDesignation").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_Archiver_Designation").click(function () {
    $(".error").hide();

    var ordre = $("#ordre_Archiver").val();

    var idDesignation = $("#idDesignation_Archiver").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 103,

        idDesignation: idDesignation,
      },

      success: function (data) {
        tab = data.split("<>");

        $("#tableDesignation tr").each(function (row, tr) {
          designation = $(tr).find("td:eq(0)").text();

          if (designation != "" && designation == ordre) {
            $(tr).find("td:eq(1)").html(tab[2]);

            $(tr).find("td:eq(2)").html(tab[3]);

            $(tr).find("td:eq(3)").html(tab[4]);

            $(tr).find("td:eq(4)").html(tab[5]);

            $(tr).find("td:eq(5)").html(tab[6]);

            $(tr).find("td:eq(6)").html(tab[7]);

            $(tr).find("td:eq(7)").html("En cours ...");
          }
        });

        $("#archiverDesignation").modal("hide");
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    return false;
  });
});

function desarchiver_Designation(idDesignation, ordre) {
  $.ajax({
    url: "ajax/ajouterLigneAjax.php",

    method: "POST",

    data: {
      operation: 102,

      idDesignation: idDesignation,
    },

    success: function (data) {
      tab = data.split("<>");

      if (tab[0] == 1) {
        $("#ordre_Desrchiver").val(ordre);
        $("#idDesignation_Desarchiver").val(idDesignation);
        $("#desarchiverDesignation").modal("show");
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

$(function () {
  $("#btn_Desarchiver_Designation").click(function () {
    $(".error").hide();

    var ordre = $("#ordre_Desarchiver").val();

    var idDesignation = $("#idDesignation_Desarchiver").val();

    $.ajax({
      url: "ajax/ajouterLigneAjax.php",

      method: "POST",

      data: {
        operation: 103,

        idDesignation: idDesignation,
      },

      success: function (data) {
        tab = data.split("<>");

        $("#tableDesignation tr").each(function (row, tr) {
          designation = $(tr).find("td:eq(0)").text();

          if (designation != "" && designation == ordre) {
            $(tr).find("td:eq(1)").html(tab[2]);

            $(tr).find("td:eq(2)").html(tab[3]);

            $(tr).find("td:eq(3)").html(tab[4]);

            $(tr).find("td:eq(4)").html(tab[5]);

            $(tr).find("td:eq(5)").html(tab[6]);

            $(tr).find("td:eq(6)").html(tab[7]);

            $(tr).find("td:eq(7)").html("En cours ...");
          }
        });

        $("#desarchiverDesignation").modal("hide");
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });

    return false;
  });
});

$(document).ready(function () {
  nbEntree = $("#nbEntreeArchiver").val();

  $("#resultsProductsArchiver").load("ajax/listerProduitArchiverAjax.php", {
    operation: 1,
    nbEntree: nbEntree,
    query: "",
    cb: "",
  }); //load initial records

  //executes code below when user click on pagination links

  $("#resultsProductsArchiver").on("click", ".pagination a", function (e) {
    // $("#resultsProductsPrix").on( "click", function (e){

    e.preventDefault();

    $(".loading-div").show(); //show loading element

    // page = page+1; //get page number from link

    page = $(this).attr("data-page"); //get page number from link

    //  alert(page)

    nbEntree = $("#nbEntreeArchiver").val();

    query = $("#searchInputArchiver").val();

    if (query.length == 0) {
      $("#resultsProductsArchiver").load(
        "ajax/listerProduitArchiverAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: "", cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsProductsArchiver").load(
        "ajax/listerProduitArchiverAjax.php",
        { page: page, operation: 1, nbEntree: nbEntree, query: query, cb: "" },
        function () {
          //get content from PHP page

          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }

    // $("#resultsProductsPrix").load("ajax/listerPrixAjax.php",{"page":page,"operation":1}
  });
});

/********   RECHERCHE et NOMBRE D'ENTREES   ******/

$(document).ready(function () {
  $("#searchInputArchiver").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputArchiver").val();

    nbEntree = $("#nbEntreeArchiver").val();

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // alert(1111)

      t = 1; // code barre

      if (query.length > 0) {
        $("#resultsProductsArchiver").load(
          "ajax/listerProduitArchiverAjax.php",
          { operation: 3, nbEntree: nbEntree, query: query, cb: t }
        ); //load initial records
      } else {
        $("#resultsProductsArchiver").load(
          "ajax/listerProduitArchiverAjax.php",
          { operation: 3, nbEntree: nbEntree, query: "", cb: t }
        ); //load initial records
      }
    } else {
      // alert(2222)

      t = 0; // no code barre

      setTimeout(() => {
        if (query.length > 0) {
          $("#resultsProductsArchiver").load(
            "ajax/listerProduitArchiverAjax.php",
            { operation: 3, nbEntree: nbEntree, query: query, cb: t }
          ); //load initial records
        } else {
          $("#resultsProductsArchiver").load(
            "ajax/listerProduitArchiverAjax.php",
            { operation: 3, nbEntree: nbEntree, query: "", cb: t }
          ); //load initial records
        }
      }, 100);
    }
  });

  $("#nbEntreeArchiver").on("change", function (e) {
    e.preventDefault();

    nbEntree = $("#nbEntreeArchiver").val();

    query = $("#searchInputArchiver").val();

    if (query.length == 0) {
      $("#resultsProductsArchiver").load("ajax/listerProduitArchiverAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
        query: "",
        cb: "",
      }); //load initial records
    } else {
      $("#resultsProductsArchiver").load("ajax/listerProduitArchiverAjax.php", {
        operation: 4,
        nbEntree: nbEntree,
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

  triPrix = 0;

  $(document).on("click", "#resultsProductsArchiver th", function (e) {
    e.preventDefault();

    //    alert(12)

    query = $("#searchInputArchiver").val();

    nbEntree = $("#nbEntreeArchiver").val();

    if (triPrix == 1) {
      // alert(triPrix)

      if (query.length == 0) {
        $("#resultsProductsArchiver").load(
          "ajax/listerProduitArchiverAjax.php",
          { operation: 2, nbEntree: nbEntree, tri: 1, query: "", cb: "" }
        ); //load initial records
      } else {
        $("#resultsProductsArchiver").load(
          "ajax/listerProduitArchiverAjax.php",
          { operation: 2, nbEntree: nbEntree, tri: 1, query: query, cb: "" }
        ); //load initial records
      }

      triPrix = 0;

      // alert(triPrix)
    } else {
      // alert(triPrix)

      if (query.length == 0) {
        $("#resultsProductsArchiver").load(
          "ajax/listerProduitArchiverAjax.php",
          { operation: 2, nbEntree: nbEntree, tri: 0, query: "", cb: "" }
        ); //load initial records
      } else {
        $("#resultsProductsArchiver").load(
          "ajax/listerProduitArchiverAjax.php",
          { operation: 2, nbEntree: nbEntree, tri: 0, query: query, cb: "" }
        ); //load initial records
      }

      triPrix = 1;

      // alert(triPrix)
    }
  });
});
$(document).ready(function () {
  $("#paiementWave").on("hidden.bs.modal", function () {
    // do something…
    window.location.reload();
    // window.location.href="https://jcaisse.org/JCaisse/accueil.php";
  });
});

function selectPlafond(v) {
  // option = $(this).val()
  // alert(v)
  if (v == "1") {
    $(".plafondInput").show();
    $(".plafondAmount").focus();
  } else {
    $(".plafondInput").hide();
  }
}

function checkedCompte(c, p) {
  // option = $(this).val()
  // alert()
  if ($("#compte_" + c + "_" + p).is(":checked")) {
    // var returnVal = confirm("Are you sure?");
    // $(this).prop("checked", returnVal);
    $("#montantCompte_" + c + "_" + p).show();
    $("#montantCompte_" + c + "_" + p).focus();
  } else {
    // var returnVal = confirm("Are you sure?");
    // $(this).prop("checked", returnVal);
    $("#montantCompte_" + c + "_" + p).hide();
  }
}

function validerCptMultiple(p) {
  // alert(p)
  apayer = $("#somme_Apayer" + p).text();
  mtotal = 0;
  choiceArray = []; //tableau des comptes choisi
  testField = ""; //pour tester si tout les champs de checkbox choisi sont rempli
  $(
    "#paiementMultipleModal-" + p + " input:checkbox[name=checkCompte]:checked"
  ).each(function () {
    c = $(this).attr("data-idCompte");
    m = $("#montantCompte_" + c + "_" + p).val();
    mtotal = parseInt(mtotal) + parseInt(m);

    choiceArray.push(p + "-" + c + "-" + m);

    if (m == null || m == "") {
      testField = testField + c + "-";
    } else {
      $("#montantCompte_" + c + "_" + p).css("background-color", "");
    }
  });
  // alert(parseInt(mtotal)+' != '+parseInt(apayer));
  // alert(testField)
  if (testField != null && testField != "") {
    emptyField = testField.split("-");
    emptyField.forEach((e) => {
      // alert(e)
      $("#montantCompte_" + e + "_" + p).css("background-color", "red");
    });
  } else if (parseInt(mtotal) != parseInt(apayer)) {
    // alert(55222)

    $("#textAlert-" + p).show(1000);
  } else {
    // alert(5555)
    $("#textAlert-" + p).hide(1000);
    $.ajax({
      url: "ajax/operationPanierAjax.php",

      method: "POST",

      data: {
        cptManage: "cptManage",

        data: choiceArray,

        idPanier: p,
      },

      success: function (data) {
        console.log(data);
        $("#compteContent" + p).html(data);
        $("#paiementMultipleModal-" + p).modal("hide");
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  }
  console.log(choiceArray);
}

/**************** modif *****************/

function depotManagerConfirm(ligne) {
  $("#qty-" + ligne).prop("disabled", true);
  $("#dmc-" + ligne).prop("disabled", true);

  $.ajax({
    url: "ajax/operationLigneAjax.php",

    method: "POST",

    data: {
      operation: 1,

      numLigne: ligne,
    },

    success: function (data) {
      // alert(data)

      $("#dme-" + ligne).prop("disabled", false);

      $("#dmc-" + ligne).hide();
      $("#dme-" + ligne).show();

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function depotManagerEdit(ligne) {
  $("#dme-" + ligne).prop("disabled", true);

  $.ajax({
    url: "ajax/operationLigneAjax.php",

    method: "POST",

    data: {
      operation: 2,

      numLigne: ligne,
    },

    success: function (data) {
      // alert(data)

      $("#qty-" + ligne).prop("disabled", false);
      $("#dmc-" + ligne).prop("disabled", false);

      $("#qty-" + ligne).focus();

      $("#dme-" + ligne).hide();
      $("#dmc-" + ligne).show();

      console.log(data);
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function imprimerProforma(panier) {
  nbColis = $("#nbColis" + panier).val();

  // alert(data)
  if (nbColis > 0 && nbColis != "") {
    $.ajax({
      url: "ajax/operationLigneAjax.php",

      method: "POST",

      data: {
        operation: 3,

        idPanier: panier,

        nbColis: nbColis,
      },

      success: function (data) {
        // alert(data)

        if (data == 0) {
          $("#ticket" + panier).submit();
        } else {
          confirm("Confirmez tous les articles !");
          // $("#nbColis").css('  background-color','red');
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  } else {
    confirm("Mettez le nombre de collis. Valeur supérieure à 0 !");
    // $("#nbColis").css('  background-color','red');
  }
}

function terminerProforma(panier) {
  /** to get ligne confirmed by depot manager */
  $.ajax({
    url: "ajax/operationLigneAjax.php",

    method: "POST",

    data: {
      operation: 4,

      idPanier: panier,
    },

    success: function (data) {
      // alert(data)
      if (data == 0) {
        window.location.reload();
      } else {
        confirm("Confirmez tous les articles !");
        // $("#nbColis").css('  background-color','red');
      }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function editerProforma(panier) {
  /** to get ligne confirmed by depot manager */
  $.ajax({
    url: "ajax/operationLigneAjax.php",

    method: "POST",

    data: {
      operation: 5,

      idPanier: panier,
    },

    success: function (data) {
      // alert(data)
      window.location.reload();
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

var events = ["click", "keydown", "mouseover"];

var lastEvents = Date.now();
// console.log("1l/" + lastEvents);

for (let i = 0; i < events.length; i++) {
  const e = events[i];

  document.addEventListener(e, () => {
    var currentEvents = Date.now();
    // console.log("fl" + lastEvents);
    // console.log("1c/" + currentEvents);
    // console.log((currentEvents - lastEvents));

    if (currentEvents - lastEvents > 30 * 60 * 1000) {
      // var xhttp = new XMLHttpRequest();
      // // xhttp.onreadystatechange = () => {
      // //     console.log('logout')
      // // }
      // xhttp.open('GET', 'deconnexion.php', true);
      // xhttp.send();
      // lastEvents = currentEvents;
      // console.log("l/" + lastEvents);

      $.ajax({
        url: "deconnexion2.php",

        method: "Get",

        success: function (data) {
          // alert(data)
          window.location.href = "deconnexion2.php";
        },

        error: function () {
          // alert("La requête ");
        },

        dataType: "text",
      });
    } else {
      lastEvents = currentEvents;
      // console.log("c/" + currentEvents);
    }
    // alert(lastEvents + '/' + currentEvents)
  });
}

/**Debut modifier la quantite dans la ligne d'un Pagnet  proforma**/

function modif_QtyETProforma(quantite, ligne, panier) {
  // alert(10000)

  if (quantite == "") {
    quantite = 0;
  }

  $.ajax({
    url: "ajax/operationLigneAjax.php",

    method: "POST",

    data: {
      operation: 6,

      idPanier: panier,

      numLigne: ligne,

      quantite: quantite,
    },

    success: function (data) {
      // alert(data)
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**Fin modifier la quantite dans la ligne d'un Pagnet proforma**/

/************************** Modification inventaire *************************/

$(function () {
  $(document).on("keyup", "#referenceInv", function (e) {
    e.preventDefault();
    // alert(1)
    // idPanier = $(this).attr('data-idPanier');

    var query = $(this).val();

    if (query.length > 0) {
      $("#referenceInv").typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/operationInventaireReferencingAjax.php",

            method: "POST",

            data: {
              operation: 1,

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

    $("#referenceInv").focus();

    /*********** Modification **************/

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      var text = $("#tabInventaire .typeahead li.active").text();

      tab = text.split(" -/- ");

      idDesignation = tab[0];
      designation = tab[1];

      if (designation != "" && idDesignation != "") {
        $.ajax({
          url: "ajax/operationInventaireReferencingAjax.php",

          method: "POST",

          data: {
            operation: 2,

            designation: designation,

            idDesignation: idDesignation,
          },

          dataType: "text",

          success: function (data) {
            // alert(data)
            res = data.split("<>");
            if (res[0] == 1) {
              $("#inputInvHidden").val(res[1]);
              $("#referenceInv").val(tab[1]);
              $("#categorieInv").val(res[3]);
              $("#prixInv").val(res[4]);

              $("#categorieInv").prop("disabled", true);
              $("#prixInv").prop("disabled", true);
            }

            $("#tabInventaire .typeahead").html("");
          },

          error: function (err) {
            console.log(err);
          },
        });
      } else {
        // alert('hhhh')
      }
    }
  });

  $(document).on("click", "#tabInventaire .typeahead li.active", function (e) {
    e.preventDefault();

    var text = $("#tabInventaire .typeahead li.active").text();

    tab = text.split(" -/- ");

    idDesignation = tab[0];
    designation = tab[1];

    if (designation != "" && idDesignation != "") {
      $.ajax({
        url: "ajax/operationInventaireReferencingAjax.php",

        method: "POST",

        data: {
          operation: 2,

          designation: designation,

          idDesignation: idDesignation,
        },

        dataType: "text",

        success: function (data) {
          // alert(data)
          res = data.split("<>");
          if (res[0] == 1) {
            $("#inputInvHidden").val(res[1]);
            $("#referenceInv").val(tab[1]);
            $("#categorieInv").val(res[3]);
            $("#prixInv").val(res[4]);

            $("#categorieInv").prop("disabled", true);
            $("#prixInv").prop("disabled", true);
          }

          $("#tabInventaire .typeahead").html("");
        },

        error: function (err) {
          console.log(err);
        },
      });
    } else {
      // alert('hhhh')
    }
  });
});

function validerInv() {
  idDesignation = $("#inputInvHidden").val();
  designation = $("#referenceInv").val();
  categorie = $("#categorieInv").val();
  prix = $("#prixInv").val();
  quantite = $("#quantiteInv").val();
  depotChoice = $("#depotChoice").val();
  if (depotChoice != "") {
    $("#btnAddInv").prop("disabled", true);
    // alert(depotChoice)

    if (idDesignation != "") {
      if (quantite == "") {
        confirm("Mettez la quantité");
        $("#btnAddInv").prop("disabled", false);
      } else {
        $.ajax({
          url: "ajax/operationInventaireReferencingAjax.php",

          method: "POST",

          data: {
            operation: 3,

            quantite: quantite,

            depotChoice: depotChoice,

            idDesignation: idDesignation,
          },

          dataType: "text",

          success: function (data) {
            // alert(data)
            // res = data.split('<>');
            if (data == 1 || data == "1") {
              $("#inputInvHidden").val("");
              $("#referenceInv").val("");
              $("#categorieInv").val("");
              $("#prixInv").val("");
              $("#quantiteInv").val("");

              $("#btnAddInv").prop("disabled", false);
              $("#categorieInv").prop("disabled", false);
              $("#prixInv").prop("disabled", false);
            }

            // $('#tabInventaire .typeahead').html('')
          },

          error: function (err) {
            console.log(err);
          },
        });
      }
    } else {
      if (
        designation == "" ||
        categorie == "" ||
        prix == "" ||
        quantite == ""
      ) {
        confirm("Champ vide");
        $("#btnAddInv").prop("disabled", false);
      } else {
        $.ajax({
          url: "ajax/operationInventaireReferencingAjax.php",

          method: "POST",

          data: {
            operation: 4,

            designation: designation,

            categorie: categorie,

            quantite: quantite,

            depotChoice: depotChoice,

            prix: prix,
          },

          dataType: "text",

          success: function (data) {
            // alert(data)
            if (data == 1 || data == "1") {
              $("#inputInvHidden").val("");
              $("#referenceInv").val("");
              $("#categorieInv").val("");
              $("#prixInv").val("");
              $("#quantiteInv").val("");

              $("#btnAddInv").prop("disabled", false);
            }
          },

          error: function (err) {
            console.log(err);
          },
        });
      }
    }
  } else {
    confirm("Choisir depot");
  }
}

/************************** Modification rayon *************************/

function placerRayon(id) {
  totalStock = $("#totalStock").val();
  qtyRayon = $("#qtyRayon").val();
  stockTemp = $("#stockTemp").val();

  if (qtyRayon == "" || qtyRayon == null) {
    confirm("Champ vide");
  } else if (
    parseInt(qtyRayon) > parseInt(totalStock) ||
    parseInt(qtyRayon) + parseInt(stockTemp) > parseInt(totalStock)
  ) {
    confirm("Vous avez dépassé la quantité de stock disponible");
  } else {
    $("#btnPlacerRayon").prop("disabled", true);

    $.ajax({
      url: "ajax/operationLigneAjax.php",

      method: "POST",

      data: {
        operation: 7,

        qtyRayon: qtyRayon,

        stockTemp: stockTemp,

        idDesignation: id,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)
        // res = data.split('<>');
        if (data == 1 || data == "1") {
          window.location.reload();
        } else {
          confirm("Une erreur est survenue. Rééssayer SVP.");
          $("#btnPlacerRayon").prop("disabled", false);
        }

        // $('#tabInventaire .typeahead').html('')
      },

      error: function (err) {
        console.log(err);
      },
    });
  }
}

function FrontImage2() {
  // alert(1111)
  $(".intro2").fadeOut(200).delay(50).fadeIn(200);
  $(".intro2").hide(2000); // OR $(this).parents('tr').remove();
  $(".intro2").show(2000);
}
$(document).ready(function () {
  setInterval("FrontImage2()", 1000);
});

function inventairePartielleB(designation) {
  var pus = $("#pus-" + designation).val();
  var pu = $("#pu-" + designation).val();
  var pa = $("#pa-" + designation).val();
  var qtyReelle = $("#qtyReelle-" + designation).val();

  var actuQte = $("#actuQte_" + designation).text();

  if (qtyReelle != "" && qtyReelle != null) {
    $("#pus-" + designation).prop("disabled", true);
    $("#pu-" + designation).prop("disabled", true);
    $("#pa-" + designation).prop("disabled", true);
    $("#qtyReelle-" + designation).prop("disabled", true);

    $("#btn_InvStock-" + designation).prop("disabled", true);

    if (qtyReelle == "" || qtyReelle == null) {
      qtyReelle = 0;
    }

    $.ajax({
      url: "ajax/operationLigneAjax.php",

      method: "POST",

      data: {
        operation: 9,

        idDesignation: designation,

        qtyReelle: qtyReelle,

        prixUniteStock: pus,

        prixUnitaire: pu,

        prixAchat: pa,
      },

      success: function (data) {
        // alert(data)

        $("#pus-" + designation).prop("disabled", false);
        $("#pu-" + designation).prop("disabled", false);
        $("#pa-" + designation).prop("disabled", false);
        $("#qtyReelle-" + designation).prop("disabled", false);

        $("#btn_InvStock-" + designation).prop("disabled", false);

        $("#qtyReelle-" + designation).val("");

        $("#actuQte_" + designation).text(qtyReelle);
        $("#actuQte_" + designation).attr("class", "alert alert-success");
        $("#actuQte_" + designation).fadeOut(400, function () {
          $("#actuQte_" + designation).fadeIn(400);
        });
      },

      error: function () {
        confirm("Une erreur est survenue. Rééssayer svp.");
      },

      dataType: "text",
    });
  } else {
    confirm("Champ vide rencontré.");
  }
}
function placerRayonAlert(id) {
  totalStock = $("#totalStock-" + id).val();
  qtyRayon = $("#qtyRayon-" + id).val();
  stockTemp = $("#stockTemp-" + id).val();

  if (
    parseInt(qtyRayon) > parseInt(totalStock) ||
    parseInt(qtyRayon) + parseInt(stockTemp) > parseInt(totalStock)
  ) {
    confirm("Vous avez dépassé la quantité de stock disponible");
  } else {
    $("#btnPlacerRayon-" + id).prop("disabled", true);

    $.ajax({
      url: "ajax/operationLigneAjax.php",

      method: "POST",

      data: {
        operation: 7,

        qtyRayon: qtyRayon,

        stockTemp: stockTemp,

        idDesignation: id,
      },

      dataType: "text",

      success: function (data) {
        // alert(data)
        // res = data.split('<>');
        if (data == 1 || data == "1") {
          $("#totalStock-" + id).prop("disabled", true);
          $("#qtyRayon-" + id).prop("disabled", true);
          $("#stockTemp-" + id).prop("disabled", true);
          $("#btn_AjtRayon-" + id).prop("disabled", true);
        } else {
          confirm("Une erreur est survenue. Rééssayer SVP.");
        }

        // $('#tabInventaire .typeahead').html('')
      },

      error: function (err) {
        console.log(err);
      },
    });
  }
}
$(function () {
  $(document).on("keyup", ".clientProformaSearch", function (e) {
    e.preventDefault();
    // alert(1)

    var query = $(this).val();
    // alert(query)
    if (query.length > 0) {
      $(this).typeahead({
        source: function (query, result) {
          $.ajax({
            url: "ajax/vendreLigneAjax.php",

            method: "POST",

            data: {
              operation: 42,

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

// function changeNumContainer(id) {

//     emplacementChanged = $("#emplacementChanged"+id).val();

//     if (emplacementChanged=="" || emplacementChanged==null) {

//         confirm("Mettez le numéro de container avant de valider.");

//     } else {

//         // $("#emplacementChangedValidate"+id).prop('disabled', true);

//         $.ajax({

//             url: "ajax/operationPanierAjax.php",

//             method: "POST",

//             data: {

//                 changeNumContainer: "changeNumContainer",

//                 emplacementChanged: emplacementChanged,

//                 idPagnet: id

//             },

//             dataType: "text",

//             success: function(data) {
//                 // alert(data)
//                 // res = data.split('<>');
//                 if (data==1 || data=='1') {

//                     $("#numContainerdetail"+id).css('background','green').hide();

//                     $("#numContainerdetail"+id).css('background','lightGreen').show(1000);

//                     $("#numContainerdetail"+id).text('Container : '+emplacementChanged);

//                 } else {
//                     confirm("Une erreur est survenue. Rééssayer SVP.")
//                 }

//                 // $('#tabInventaire .typeahead').html('')

//             },

//             error: function(err) {

//                 confirm("Une erreur est survenue. Rééssayer SVP."+ err);

//             }

//         });

//     }
// }

//*************PAIEMENT Wave ********************/

function selectNbMoisPaiement(idPS, montant) {
  $("#idPsModalSelectNbMois").val(idPS);
  $("#montantFormuleInput").val(montant);
  $("#selectNbMoisModal").modal("show");
  var f = new Intl.NumberFormat("fr-FR", { maximumFractionDigits: 0 });
  $("#montantFormule").html(f.format(montant));
}
function effectue_paiementWave() {
  idPS = $("#idPsModalSelectNbMois").val();
  nbMois = $("#selectNbMois").val();
  $("#selectNbMoisModal").modal("hide");

  $.ajax({
    url: "wave_payment.php",

    method: "POST",

    data: {
      operation: 1,

      idPS: idPS,

      nbMois: nbMois,
    },

    success: function (data) {
      document.getElementById("bodyPayWave").innerHTML =
        '<object style="height: 500px;width: 700px;" type="text/html" data="' +
        data +
        '" ></object>';

      $("#paiementWave").modal("show");
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

function changeFormule() {
  nbMois = $("#selectNbMois").val();
  montant = $("#montantFormuleInput").val();
  $("#montantFormule").hide(10);
  $("#montantFormule").show(500);
  var f = new Intl.NumberFormat("fr-FR", { maximumFractionDigits: 0 });

  $("#montantFormule").html(f.format(montant * nbMois));
}

//******* CBM manager*******/

// function changeNumContainer(id) {
//   emplacementChanged = $("#emplacementChanged" + id).val();

//   c = emplacementChanged.split(" . ");
//   idContainer = c[0];
//   numContainer = c[1];

//   if (
//     emplacementChanged == "" ||
//     emplacementChanged == null ||
//     emplacementChanged.indexOf(" . ") < 0
//   ) {
//     confirm(
//       "Mettez le numéro de container avant de valider ou mettez un numéro valide."
//     );
//   } else {
//     // $("#emplacementChangedValidate"+id).prop('disabled', true);

//     $.ajax({
//       url: "ajax/operationPanierAjax.php",

//       method: "POST",

//       data: {
//         changeNumContainer: "changeNumContainer",

//         emplacementChanged: numContainer,

//         idContainer: idContainer,

//         idPagnet: id,
//       },

//       dataType: "text",

//       success: function (data) {
//         // alert(data)
//         // res = data.split('<>');
//         if (data == 1 || data == "1") {
//           $("#numContainerdetail" + id)
//             .css("background", "green")
//             .hide();

//           $("#numContainerdetail" + id)
//             .css("background", "lightGreen")
//             .show(1000);

//           $("#numContainerdetail" + id).text("Container : " + numContainer);
//         } else {
//           confirm("Une erreur est survenue. Rééssayer SVP.");
//         }

//         // $('#tabInventaire .typeahead').html('')
//       },

//       error: function (err) {
//         confirm("Une erreur est survenue. Rééssayer SVP." + err);
//       },
//     });
//   }
// }

// $(function () {
//   $(document).on("keyup", "#client_cbm_fretrrr", function (e) {
//     e.preventDefault();
//     // alert(1)
//     // idPanier = $(this).attr('data-idPanier');

//     var query = $(this).val();
//     $("#emplacementAutocomplete").css("background", "white");

//     if (query.length > 0) {
//       $("#client_cbm_fret").typeahead({
//         source: function (query, result) {
//           $.ajax({
//             url: "ajax/vendreLigneAjax.php",

//             method: "POST",

//             data: {
//               operation: 26,

//               query: query,
//             },

//             dataType: "json",

//             success: function (data) {
//               console.log(data);

//               result(
//                 $.map(data, function (item) {
//                   return item;
//                 })
//               );
//             },

//             error: function (err) {
//               console.log(err);
//             },
//           });
//         },
//       });
//     }

//     $("#client_cbm_fret").focus();

//     /*********** Modification **************/

//     var keycode = e.keyCode ? e.keyCode : e.which;

//     if (keycode == "13") {
//       var text = $("#tabCbm .typeahead li.active").text();
//       $("#emplacementAutocomplete").css("background", "white");
//     }
//   });

//   $(document).on("click", "#tabCbm .typeahead li.active", function (e) {
//     e.preventDefault();

//     var text = $("#tabCbm .typeahead li.active").text();
//     $("#emplacementAutocomplete").css("background", "white");
//   });
// });

// function validerEnregistrement() {
//   Client_text = $("#client_cbm_fret").val().split(" . ");
//   idClient = Client_text[0];
//   nomClient = Client_text[1];
//   qty_cbm_fret = $("#qty_cbm_fret").val();
//   prix_cbm_fret = $("#prix_cbm_fret").val();
//   nature_bagages = $("#nature_bagages").val();
//   qty_bal = $("#qty_bal").val();
//   numEmplacement = $("#emplacementAutocomplete").val().split(" . ")[0];
//   nomEmplacement = $("#emplacementAutocomplete").val().split(" . ")[1];
//   nbPcs = $("#nbPcs").val();
//   emplacement = $("#emplacement").val();
//   $("#emplacementAutocomplete").css("background", "white");

//   // alert($("#client_cbm_fret").val().indexOf(' . '));
//   if (numEmplacement && numEmplacement !== "" && numEmplacement !== null) {
//     if (
//       $("#client_cbm_fret").val().indexOf(" . ") > 0 &&
//       $("#emplacementAutocomplete").val().indexOf(" . ") > 0
//     ) {
//       if (qty_cbm_fret == 0 || qty_cbm_fret == null) {
//         confirm("Erreur : Champs vide rencontré.");
//       } else {
//         $.ajax({
//           url: "ajax/operationPanierAjax.php",

//           method: "POST",

//           data: {
//             enregistrementManager: "enregistrementManager",

//             idClient: idClient,

//             numEmplacement: numEmplacement,

//             typeEmplacement: emplacement,

//             nbPcs: nbPcs,

//             qty_cbm_fret: qty_cbm_fret,

//             prix_cbm_fret: prix_cbm_fret,

//             nature_bagages: nature_bagages,

//             qty_bal: qty_bal,
//           },

//           dataType: "text",

//           success: function (data) {
//             // alert(data)
//             // console.log(data);

//             if (data == 1 || data == "1") {
//               var currentdate = new Date();
//               nbrows = 0;
//               $("#listeEnregistrement tr").each(() => {
//                 nbrows++;
//               });
//               row =
//                 "<tr><td>" +
//                 nbrows +
//                 "</td><td>" +
//                 nomClient +
//                 "</td><td>" +
//                 currentdate.getDate() +
//                 "-" +
//                 (currentdate.getMonth() + 1) +
//                 "-" +
//                 currentdate.getFullYear() +
//                 "</td><td>" +
//                 currentdate.getHours() +
//                 ":" +
//                 currentdate.getMinutes() +
//                 "</td><td>" +
//                 qty_cbm_fret +
//                 "</td><td>" +
//                 nbPcs +
//                 "</td><td>" +
//                 nature_bagages +
//                 "</td><td>" +
//                 nomEmplacement +
//                 "</td><td>----</td></tr>";
//               // alert(row);
//               $("#listeEnregistrement").prepend(row);

//               $("#client_cbm_fret").val("");
//               $("#nature_bagages").val("");
//               $("#qty_cbm_fret").val(0);
//               $("#qty_bal").val(0);
//               $("#prix_cbm_fret").val(0);
//               $("#nbPcs").val(1);
//             } else {
//               confirm(
//                 "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
//                   data
//               );
//             }
//           },

//           error: function (err) {
//             console.log(err);
//             confirm("Erreur : Connexion impossible. Rééssayer SVP.");
//           },
//         });
//       }
//     } else {
//       confirm(
//         "Erreur : Client ou container introuvable. Créer le client ou le container s'il n'existe pas."
//       );
//     }
//   } else {
//     $("#emplacementAutocomplete").css("background", "red");
//     confirm("Erreur : le numéro de container est obligatoire.");
//   }
// }

// /***** autocomplete changement container *****/
// $(function () {
//   $(document).on("keyup", ".emplacementChanged", function (e) {
//     e.preventDefault();
//     // idPanier = $(this).attr('data-idPanier');

//     var query = $(this).val();
//     var emplacement = $("#emplacement").val();
//     // var idPagnet = $(this).attr('data-idPanier');
//     // alert(emplacement);
//     // $("#emplacementAutocomplete").css('background','white')

//     if (query.length > 0) {
//       // console.log(1)
//       // alert(1)

//       $(this).typeahead({
//         source: function (query, result) {
//           $.ajax({
//             url: "ajax/operationPanierAjax.php",

//             method: "POST",

//             data: {
//               emplacementChange: "emplacementChange",

//               query: query,

//               emplacement: $("#emplacement").val(),
//             },

//             dataType: "json",

//             success: function (data) {
//               console.log(data);
//               result(
//                 $.map(data, function (item) {
//                   return item;
//                 })
//               );
//             },

//             error: function (err) {
//               console.log(err);
//             },
//           });
//         },
//       });
//     }

//     $(".emplacementChanged").focus();

//     /*********** Modification **************/
//   });
// });

// function editContainer(idContainer) {
//   $("#idContainer_edit").val(idContainer);

//   $.ajax({
//     url: "ajax/operationPanierAjax.php",

//     method: "POST",

//     data: {
//       getContainer: "getContainer",

//       idContainer: idContainer,
//     },

//     dataType: "json",

//     success: function (data) {
//       // alert(data)
//       // console.log(data);
//       // $("#idContainer_edit").show();
//       $("#numContainer_edit").val(data.numContainer);
//       $("#dateDepart_edit").val(data.dateDepart);
//       $("#dateArrivee_edit").val(data.dateArrivee);

//       $("#editContainerModal").modal("show");
//     },

//     error: function (err) {
//       console.log(err);
//       confirm("Erreur : Connexion impossible. Rééssayer SVP.");
//     },
//   });
// }

// function confirmEditContainer() {
//   idContainer = $("#idContainer_edit").val();
//   numContainer = $("#numContainer_edit").val();
//   dateDepart = $("#dateDepart_edit").val();
//   dateArrivee = $("#dateArrivee_edit").val();

//   $.ajax({
//     url: "ajax/operationPanierAjax.php",

//     method: "POST",

//     data: {
//       confirmEditContainer: "confirmEditContainer",
//       idContainer: idContainer,
//       numContainer: numContainer,
//       dateDepart: dateDepart,
//       dateArrivee: dateArrivee,
//     },

//     dataType: "text",

//     success: function (data) {
//       // alert(data)
//       // console.log(data);

//       if (data == 1 || data == "1") {
//         $("#editContainerModal").modal("hide");

//         $("#tr" + idContainer).hide(1000);
//         $("#tr" + idContainer).show(500);

//         $("#tr" + idContainer)
//           .children("td")
//           .eq(1)
//           .text(numContainer);
//         $("#tr" + idContainer)
//           .children("td")
//           .eq(2)
//           .text(dateDepart);
//         $("#tr" + idContainer)
//           .children("td")
//           .eq(3)
//           .text(dateArrivee);
//       } else {
//         confirm(
//           "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
//             data
//         );
//       }
//     },

//     error: function (err) {
//       console.log(err);
//       confirm("Erreur : Connexion impossible. Rééssayer SVP.");
//     },
//   });
// }

// function deleteContainer(idContainer) {
//   $("#idContainer_delete").val(idContainer);

//   $("#deleteContainerModal").modal("show");
// }

// function confirmDeleteContainer() {
//   idContainer = $("#idContainer_delete").val();
//   $.ajax({
//     url: "ajax/operationPanierAjax.php",

//     method: "POST",

//     data: {
//       confirmDeleteContainer: "confirmDeleteContainer",

//       idContainer: idContainer,
//     },

//     dataType: "text",

//     success: function (data) {
//       if (data == 1 || data == "1") {
//         $("#tr" + idContainer).css("background-color", "red");
//         $("#tr" + idContainer).hide(1000);

//         $("#deleteContainerModal").modal("hide");
//       } else {
//         confirm(
//           "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
//             data
//         );
//       }
//     },

//     error: function (err) {
//       console.log(err);
//       confirm("Erreur : Connexion impossible. Rééssayer SVP.");
//     },
//   });
// }

// function changeEmplacement() {
//   emplacement = $("#emplacement").val();
//   if (emplacement == 1) {
//     $("#emplacementTxt").text("N° Container");
//     $("#divAddArrivageForm").show();
//   } else if (emplacement == 2) {
//     $("#emplacementTxt").text("N° Avion");
//     $("#divAddArrivageForm").show();
//   } else if (emplacement == 3) {
//     $("#emplacementTxt").text("Nom du dêpot");
//     $("#divAddArrivageForm").show();
//   }
// }

// function chargementBagages(idEnreg) {
//   // alert(idEnreg);
//   emplCharger = $("#emplChargerAutocomplete" + idEnreg)
//     .val()
//     .split(" --- ");
//   idEmpl = emplCharger[0].split(" . ")[0];
//   numEmpl = emplCharger[0].split(" . ")[1];
//   typeEmpl = emplCharger[1];
//   prixChargement = $("#prixChargement" + idEnreg).val();
//   // nbPcsCharger = $("#nbPcsCharger" + idEnreg).val();
//   // pcsRestant = $("#pcsRestant" + idEnreg).val();

//   // if (pcsRestant < nbPcsCharger) {
//   //   confirm(
//   //     "Une erreur est survenue lors de l'opération : la quantité charger ne doit pas être supérieure à la quantité restante!"
//   //   );
//   // } else {
//   if (emplCharger != null && emplCharger != "") {
//     $.ajax({
//       url: "ajax/operationPanierAjax.php",

//       method: "POST",

//       data: {
//         chargementBagages: "chargementBagages",

//         idEnreg: idEnreg,

//         idEmpl: idEmpl,

//         numEmpl: numEmpl,

//         typeEmpl: typeEmpl,

//         prixChargement: prixChargement,

//         // nbPcsCharger: nbPcsCharger,

//         // pcsRestant: pcsRestant,
//       },

//       dataType: "text",

//       success: function (data) {
//         if (data == 1 || data == "1") {
//           // $("#pcsRestant" + idEnreg).val(pcsRestant - nbPcsCharger);
//           // $("#nbPcsCharger" + idEnreg).val(pcsRestant - nbPcsCharger);
//           // $("#restantPcs" + idEnreg).text(pcsRestant - nbPcsCharger);
//           // if (pcsRestant - nbPcsCharger == 0) {
//           $("#tr" + idEnreg).css("background-color", "#DDFCD0");
//           $("#tr" + idEnreg).hide(1000);
//           $("#tr" + idEnreg).show(2000);
//           // }
//         } else {
//           confirm(
//             "Une erreur est survenue lors de l'opération : vérifier votre connexion et rééssayer svp!" +
//               data
//           );
//         }
//       },

//       error: function (err) {
//         console.log(err);
//         confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
//       },
//     });
//   } else {
//     confirm(
//       "Une erreur est survenue lors de l'opération : vérifier que tous les champs sont remplis svp!"
//     );
//   }
//   // alert(emplCharger + " / " + nbPcsCharger);
//   // $("#chargementBagagesModal").modal("show");
//   // }
// }

// $(function () {
//   $(document).on("keyup", ".emplChargerAutocomplete", function (e) {
//     e.preventDefault();
//     // idPanier = $(this).attr('data-idPanier');

//     var query = $(this).val();
//     // var emplacement = $("#emplacement").val();
//     // var idPagnet = $(this).attr('data-idPanier');

//     // $("#emplacementAutocomplete").css('background','white')

//     if (query.length > 0) {
//       // console.log(1)
//       // alert(1)

//       $(this).typeahead({
//         source: function (query, result) {
//           $.ajax({
//             url: "ajax/operationPanierAjax.php",

//             method: "POST",

//             data: {
//               emplCharger: "emplCharger",

//               query: query,
//             },

//             dataType: "json",

//             success: function (data) {
//               console.log(data);
//               result(
//                 $.map(data, function (item) {
//                   return item;
//                 })
//               );
//             },

//             error: function (err) {
//               console.log(err);
//             },
//           });
//         },
//       });
//     }

//     $(this).focus();

//     /*********** Modification **************/
//   });
// });

// function editEnregistrement(idEnreg) {
//   $.ajax({
//     url: "ajax/operationPanierAjax.php",

//     method: "POST",

//     data: {
//       getEnregistrement: "getEnregistrement",

//       idEnreg: idEnreg,
//     },

//     dataType: "text json",

//     success: function (data) {
//       // console.log(data);
//       $("#idEnregistrementHide").val(idEnreg);
//       $("#clientName").val(data["prenom"] + " " + data["nom"]);
//       $("#nb_cbm_fret").val(data["quantite_cbm_fret"]);
//       $("#nb_pieces").val(data["nbPieces"]);
//       $("#nat_bagages").val(data["natureBagage"]);
//     },

//     error: function (err) {
//       console.log(err);
//       confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
//     },
//   });
//   $("#editEnregModal").modal("show");
// }

// function editEnregistrementValidataion() {
//   idEnreg = $("#idEnregistrementHide").val();
//   // $("#clientName").val();
//   nb_cbm_fret = $("#nb_cbm_fret").val();
//   nb_pieces = $("#nb_pieces").val();
//   nat_bagages = $("#nat_bagages").val();

//   $.ajax({
//     url: "ajax/operationPanierAjax.php",

//     method: "POST",

//     data: {
//       editEnregistrement: "editEnregistrement",

//       idEnreg: idEnreg,
//       nb_cbm_fret: nb_cbm_fret,
//       nb_pieces: nb_pieces,
//       nat_bagages: nat_bagages,
//     },

//     dataType: "text",

//     success: function (data) {
//       $("#editEnregModal").modal("hide");

//       $("#tr" + idEnreg).css("background-color", "#D4F973");
//       $("#tr" + idEnreg).hide(1000);
//       $("#tr" + idEnreg).show(2000);
//       $("#nbcf" + idEnreg).text(nb_cbm_fret);
//       $("#nbp" + idEnreg).text(nb_pieces);
//       $("#natb" + idEnreg).text(nat_bagages);
//       setTimeout(() => {
//         $("#tr" + idEnreg).css("background-color", "#fff");
//       }, 2500);
//     },

//     error: function (err) {
//       console.log(err);
//       confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
//     },
//   });
// }

// function deleteEnregistrement(idEnreg) {
//   $("#idDelEnregHide").val(idEnreg);
//   $("#deleteEnregModal").modal("show");
// }

// function deleteEnregistrementValidataion() {
//   idEnreg = $("#idDelEnregHide").val();
//   alert(idEnreg);
//   $.ajax({
//     url: "ajax/operationPanierAjax.php",

//     method: "POST",

//     data: {
//       delEnregistrement: "delEnregistrement",

//       idEnreg: idEnreg,
//     },

//     dataType: "text",

//     success: function (data) {
//       alert(data);
//       $("#deleteEnregModal").modal("hide");

//       $("#tr" + idEnreg).css("background-color", "#DD0B0B");
//       $("#tr" + idEnreg).hide(1000);
//     },

//     error: function (err) {
//       console.log(err);
//       confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
//     },
//   });
// }

// function fermerPorteur(id, type) {
//   $.confirm({
//     title: "Confirmer!",
//     content: "Confimer l'opération!",
//     buttons: {
//       confirm: function () {
//         $.ajax({
//           url: "ajax/operationPanierAjax.php",

//           method: "POST",

//           data: {
//             fermerPorteur: "fermerPorteur",

//             id: id,

//             type: type,
//           },

//           dataType: "text",

//           success: function (data) {
//             $("#fermerPorteur").hide();
//             $("#ouvrirPorteur").show();
//             $("#arriveePorteur").show();
//           },

//           error: function (err) {
//             console.log(err);
//             confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
//           },
//         });
//       },
//       cancel: function () {},
//     },
//   });
// }

// function ouvrirPorteur(id, type) {
//   $.confirm({
//     title: "Confirmer!",
//     content: "Confimer l'opération!",
//     buttons: {
//       confirm: function () {
//         $.ajax({
//           url: "ajax/operationPanierAjax.php",

//           method: "POST",

//           data: {
//             ouvrirPorteur: "ouvrirPorteur",

//             id: id,

//             type: type,
//           },

//           dataType: "text",

//           success: function (data) {
//             $("#ouvrirPorteur").hide();
//             $("#arriveePorteur").hide();
//             $("#fermerPorteur").show();
//           },

//           error: function (err) {
//             console.log(err);
//             confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
//           },
//         });
//       },
//       cancel: function () {},
//     },
//   });
// }

// function arriveePorteur(id, type) {
//   $.confirm({
//     title: "Confirmer!",
//     content: "Confimer l'opération!",
//     buttons: {
//       confirm: function () {
//         $.ajax({
//           url: "ajax/operationPanierAjax.php",

//           method: "POST",

//           data: {
//             arriveePorteur: "arriveePorteur",

//             id: id,

//             type: type,
//           },

//           dataType: "text",

//           success: function (data) {
//             $("#fermerPorteur").hide();
//             $("#ouvrirPorteur").hide();
//             $("#arriveePorteur").addClass("disabled");
//           },

//           error: function (err) {
//             console.log(err);
//             confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
//           },
//         });
//       },
//       cancel: function () {},
//     },
//   });
// }

// $(document).ready(function () {
//   $(document).on("keyup", "#searchByBarcode", function (e) {
//     e.preventDefault();
//     // alert(88)

//     var keycode = e.keyCode ? e.keyCode : e.which;
//     // alert(keycode);
//     codeBarre = $("#searchByBarcode").val();

//     if (keycode == "13" && $.isNumeric(codeBarre)) {
//       // alert(1111111111);
//       $.ajax({
//         url: "ajax/operationPanierAjax.php",

//         method: "POST",

//         data: {
//           searchByBarcode: "searchByBarcode",

//           codeBarre: codeBarre,
//         },

//         dataType: "text json",

//         success: function (data) {
//           console.log(data[0]);
//           type = data[3][0];
//           enreg = data[0];
//           client = data[1];
//           porteur = data[2];

//           if (type == 1) {
//             // pour container type 2 pour avion
//             $("#qtyByPorteurTxt").text("Nombre de CBM");
//             $("#prixByPorteurTxt").text("Prix du CBM");
//             typePorteur = "Container";
//             unite = "M3";
//           } else {
//             $("#qtyByPorteurTxt").text("Nombre de KG");
//             $("#prixByPorteurTxt").text("Prix du KG");
//             typePorteur = "Avion";
//             unite = KG;
//           }

//           $("#nomClientTxt").text(client.prenom + " " + client.nom);
//           $("#telClientTxt").text(client.telephone);
//           $("#dateEnregTxt").text(enreg.dateEnregistrement);
//           $("#numEnreg").text("# " + enreg.idEnregistrement);
//           $("#dateChargTxt").text(enreg.dateChargement);
//           $("#porteurTxt").text(typePorteur);
//           $("#qtyTxt").text(enreg.quantite_cbm_fret + " " + unite);
//           $("#prixTxt").text(enreg.prix_cbm_fret + " FCFA");
//           $("#nbPcsTxt").text(enreg.nbPieces);
//           $("#NatBTxt").text(enreg.natureBagage);

//           $("#serachByBarcodeModal").modal("show");
//         },

//         error: function (err) {
//           console.log(err);
//           confirm("Erreur : Connexion impossible. Rééssayer SVP. " + err);
//         },
//       });
//     }
//   });
// });

$(function () {
  $(document).on("keyup", ".porteurVersement", function (e) {
    e.preventDefault();
    // idPanier = $(this).attr('data-idPanier');
    $("#porteurIdVersementHide").val(0);
    $("#porteurTypeVersementHide").val(0);
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
              porteurVersement: "porteurVersement",
              query: query,

              // emplacement: $("input[name=emplacement]:checked").val(),
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

    $(".porteurVersement").focus();

    /*********** Modification **************/

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // var text = $("#record").find("td:eq(1) .typeahead li.active").text();
      var text = $("#divporteurVersement .typeahead li.active").text();
      $(this).val(text.split(" . ")[1].split(" --- ")[0]);
      $("#porteurIdVersementHide").val(text.split(" . ")[0]);
      if (text.split(" . ")[1].split(" --- ")[1] == "Container") {
        $("#porteurTypeVersementHide").val(1);
      } else if (text.split(" . ")[1].split(" --- ")[1] == "Avion") {
        $("#porteurTypeVersementHide").val(2);
      }
    }
  });

  $(document).on(
    "click",
    "#divporteurVersement .typeahead li.active",
    function (e) {
      e.preventDefault();

      var text = $("#divporteurVersement .typeahead li.active").text();
      $(this).val(text.split(" . ")[1].split(" --- ")[0]);
      $("#porteurIdVersementHide").val(text.split(" . ")[0]);
      if (text.split(" . ")[1].split(" --- ")[1] == "Container") {
        $("#porteurTypeVersementHide").val(1);
      } else if (text.split(" . ")[1].split(" --- ")[1] == "Avion") {
        $("#porteurTypeVersementHide").val(2);
      }
    }
  );
});

function remiseCargo(idPagnet) {
  $("#remiseCargoBtn" + idPagnet).prop("disabled", true);
  remise = $("#remiseCargo" + idPagnet).val();
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      setRemise: "setRemise",

      idPagnet: idPagnet,

      remise: remise,
    },

    dataType: "text json",

    success: function (data) {
      $("#somme_Apayer" + idPagnet).text(
        parseInt($("#somme_Total" + idPagnet).text()) - remise
      );
      $("#somme_Apayer" + idPagnet).hide(500);
      $("#somme_Apayer" + idPagnet).show(2000);
      $("#remiseCargoBtn" + idPagnet).prop("disabled", false);
    },

    error: function () {
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function changeQtyCargo(idPagnet) {
  $("#changeQtyCargoBtn" + idPagnet).prop("disabled", true);
  qty = $("#changeQtyCargo" + idPagnet).val();
  $.ajax({
    url: "cargo/ajax/operationCargoAjax.php",

    method: "POST",

    data: {
      setQty: "setQty",

      idPagnet: idPagnet,

      qty: qty,
    },

    dataType: "text json",

    success: function (data) {
      // $("#somme_Apayer" + idPagnet).text(
      //   parseInt($("#somme_Total" + idPagnet).text()) - remise
      // );
      // $("#somme_Apayer" + idPagnet).hide(500);
      // $("#somme_Apayer" + idPagnet).show(2000);
      // $("#changeQtyCargoBtn" + idPagnet).prop("disabled", false);

      window.location.reload();
    },

    error: function () {
      confirm("Erreur : Connexion impossible. Rééssayer SVP.");
    },
  });
}

function mettreANiveauProforma(panier) {
  /** to get ligne confirmed by depot manager */
  $.confirm({
    title: "Confirmer!",
    content: "Confimer l'opération!",
    buttons: {
      confirm: function () {
        $.ajax({
          url: "ajax/operationLigneAjax.php",

          method: "POST",

          data: {
            operation: 10,

            idPanier: panier,
          },

          success: function (data) {
            // alert(data)
            if (data == 1) {
              window.location.reload();
            } else {
              confirm("Problème de connexion au serveur !");
              // $("#nbColis").css('  background-color','red');
            }
          },

          error: function () {
            confirm("Problème de connexion au serveur !");
          },

          dataType: "text",
        });
      },
      cancel: function () {},
    },
  });
}
