/************* Modification ***************/
/**Debut supprimer Designation Vitrine**/
/**Debut ajouter Vitrine**/
$(document).on("click", ".btn_disabled_after_click", function () {
  // alert(1025)
  $(this).prop("disabled", true);
});

function FrontImage() {
  $(".intro").fadeOut(400).delay(100).fadeIn(400);
}
$(document).ready(function () {
  // alert(1111)
  setInterval("FrontImage()", 2200);
});

$(document).ready(function () {
  $("form").submit(function () {
    $(this).find("button[type='submit']").prop("disabled", true);
  });
  // alert(1)
  // var page = 0;
  // $(document).on("click", "th", function() {
  //     alert( "Handler for .click() called." );
  // });

  $(document).on("click", "#produitImgEvent", function () {
    nbEntreeI = $("#nbEntreeI").val();
    // alert(nbEntreeI)

    $("#resultsI").load(
      "vitrine/ajax/listerProduitImage-VitrineAjax",
      { operation: 1, nbEntree: nbEntreeI, query: "" },
      function (data) {
        //get content from PHP page
        // alert(data)//once done, hide loading element
      }
    );
  });

  //executes code below when user click on pagination links
  $("#resultsI").on("click", ".pagination a", function (e) {
    // $("#resultsI").on( "click", function (e){
    e.preventDefault();
    $(".loading-div").show(); //show loading element
    // page = page+1; //get page number from link
    page = $(this).attr("data-page"); //get page number from link
    //  alert(page)

    nbEntreeI = $("#nbEntreeI").val();
    query = $("#searchInputI").val();

    if (query.length == 0) {
      $("#resultsI").load(
        "vitrine/ajax/listerProduitImage-VitrineAjax",
        { page: page, operation: 1, nbEntree: nbEntreeI, query: "" },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#resultsI").load(
        "vitrine/ajax/listerProduitImage-VitrineAjax",
        { page: page, operation: 1, nbEntree: nbEntreeI, query: query },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }
    // $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax",{"page":page,"operation":1}
  });
});

/********   RECHERCHE    ******/
$(document).ready(function () {
  // alert(1)
  // var page = 0;
  // $(document).on("click", "th", function() {
  //     alert( "Handler for .click() called." );
  // });

  $("#searchInputI").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInputI").val();
    nbEntreeI = $("#nbEntreeI").val();
    if (query.length > 0) {
      $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax", {
        operation: 3,
        nbEntree: nbEntreeI,
        query: query,
      }); //load initial records
    }
  });

  $("#nbEntreeI").on("change", function (e) {
    e.preventDefault();

    nbEntreeI = $("#nbEntreeI").val();
    query = $("#searchInputI").val();

    if (query.length == 0) {
      $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax", {
        operation: 4,
        nbEntree: nbEntreeI,
        query: "",
      }); //load initial records
    } else {
      $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax", {
        operation: 4,
        nbEntree: nbEntreeI,
        query: query,
      }); //load initial records
    }

    // $("#resultsI" ).load( "vitrine/ajax/listerProduitImage-VitrineAjax",{"operation":4,"nbEntree":nbEntree}); //load initial records
  });
});

/*************    TRI    ******************/
$(document).ready(function () {
  // alert(1500)
  // var page = 0;
  //executes code below when user click on pagination links
  //1=croissant et 0=decroissant
  tri = 0;
  $(document).on("click", "#contents th", function (e) {
    e.preventDefault();
    // alert(12)
    query = $("#searchInputI").val();
    nbEntreeI = $("#nbEntreeI").val();
    $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax", {
      operation: 2,
      nbEntree: nbEntreeI,
      tri: 1,
      query: "",
    }); //load initial records

    if (tri == 1) {
      // alert(tri)
      if (query.length == 0) {
        $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax", {
          operation: 2,
          nbEntree: nbEntreeI,
          tri: 1,
          query: "",
        }); //load initial records
      } else {
        $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax", {
          operation: 2,
          nbEntree: nbEntreeI,
          tri: 1,
          query: query,
        }); //load initial records
      }

      tri = 0;
      // alert(tri)
    } else {
      // alert(tri)
      if (query.length == 0) {
        $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax", {
          operation: 2,
          nbEntree: nbEntreeI,
          tri: 0,
          query: "",
        }); //load initial records
      } else {
        $("#resultsI").load("vitrine/ajax/listerProduitImage-VitrineAjax", {
          operation: 2,
          nbEntree: nbEntreeI,
          tri: 0,
          query: query,
        }); //load initial records
      }

      tri = 1;
      // alert(tri)
    }
  });
});

$(document).ready(function () {
  // alert(1)
  // var page = 0;
  // $(document).on("click", "th", function() {
  //     alert( "Handler for .click() called." );
  // });

  nbEntree = $("#nbEntree").val();

  $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
    operation: 1,
    nbEntree: nbEntree,
    query: "",
  }); //load initial records

  //executes code below when user click on pagination links
  $("#results").on("click", ".pagination a", function (e) {
    // $("#results").on( "click", function (e){
    e.preventDefault();
    $(".loading-div").show(); //show loading element
    // page = page+1; //get page number from link
    page = $(this).attr("data-page"); //get page number from link
    //  alert(page)

    nbEntree = $("#nbEntree").val();
    query = $("#searchInput").val();

    if (query.length == 0) {
      $("#results").load(
        "vitrine/ajax/listerProduit-VitrineAjax",
        { page: page, operation: 1, nbEntree: nbEntree, query: "" },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    } else {
      $("#results").load(
        "vitrine/ajax/listerProduit-VitrineAjax",
        { page: page, operation: 1, nbEntree: nbEntree, query: query },
        function () {
          //get content from PHP page
          $(".loading-div").hide(); //once done, hide loading element
        }
      );
    }
    // $("#results").load("vitrine/ajax/listerProduit-VitrineAjax",{"page":page,"operation":1}
  });
});

/********   RECHERCHE    ******/
$(document).ready(function () {
  // alert(1)
  // var page = 0;
  // $(document).on("click", "th", function() {
  //     alert( "Handler for .click() called." );
  // });

  $("#searchInput").on("keyup", function (e) {
    e.preventDefault();

    query = $("#searchInput").val();
    nbEntree = $("#nbEntree").val();
    if (query.length > 0) {
      $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
        operation: 3,
        nbEntree: nbEntree,
        query: query,
      }); //load initial records
    }
  });

  $("#nbEntree").on("change", function (e) {
    e.preventDefault();

    nbEntree = $("#nbEntree").val();
    query = $("#searchInput").val();

    if (query.length == 0) {
      $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
        operation: 4,
        nbEntree: nbEntree,
        query: "",
      }); //load initial records
    } else {
      $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
        operation: 4,
        nbEntree: nbEntree,
        query: query,
      }); //load initial records
    }

    // $("#results" ).load( "vitrine/ajax/listerProduit-VitrineAjax",{"operation":4,"nbEntree":nbEntree}); //load initial records
  });
});

/*************    TRI    ******************/
$(document).ready(function () {
  // alert(1500)
  // var page = 0;
  //executes code below when user click on pagination links
  //1=croissant et 0=decroissant
  tri = 0;
  $(document).on("click", "#contents th", function (e) {
    e.preventDefault();
    // alert(12)
    query = $("#searchInput").val();
    nbEntree = $("#nbEntree").val();
    $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
      operation: 2,
      nbEntree: nbEntree,
      tri: 1,
      query: "",
    }); //load initial records

    if (tri == 1) {
      // alert(tri)
      if (query.length == 0) {
        $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: "",
        }); //load initial records
      } else {
        $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 1,
          query: query,
        }); //load initial records
      }

      tri = 0;
      // alert(tri)
    } else {
      // alert(tri)
      if (query.length == 0) {
        $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: "",
        }); //load initial records
      } else {
        $("#results").load("vitrine/ajax/listerProduit-VitrineAjax", {
          operation: 2,
          nbEntree: nbEntree,
          tri: 0,
          query: query,
        }); //load initial records
      }

      tri = 1;
      // alert(tri)
    }
  });
});

// alert(21111)

function ajt_vitrine(id) {
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
    success: function (data) {
      // alert('data')
      // tab = data.split('+');
      // var ligne = "<tr><td>0</td><td>" + tab[0] + "</td><td>" + tab[1] + "</td><td>" + tab[2] + "</td><td>" + tab[3] + "</td><td>" + tab[4] + "</td><td>En cours ...</td></tr>";
      // $("table.tabVitrine").prepend(ligne);
      // $('#uniteStock-' + id).prop('disabled', true);
      // $('#prixUnitaire-' + id).prop('disabled', true);
      // $('#prixuniteStock-' + id).prop('disabled', true);
      // $('#init_Produit-' + id).prop('disabled', true);
      $("#btn_ajtVitrine-" + id)
        .parent()
        .parent()
        .css("background", "green")
        .hide(2000); // OR $(this).parents('tr').remove();

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
    url: "vitrine/ajax/vitrineAjax",
    method: "POST",
    data: {
      operation: 1,
      id: id,
    },
    success: function (data) {
      tab = data.split("<>");
      $("#id_Spm").val(tab[0]);
      $("#designation_Spm").val(tab[1]);
      $("#designation_SpmE").val(tab[2]);
      $("#categorie_Spm").val(tab[3]);
      $("#categorieV_Spm").val(tab[4]);
      $("#uniteStock_Spm").val(tab[5]);
      $("#prixuniteStock_Spm").val(tab[6]);
      $("#prix_Spm").val(tab[7]);
      $("#img_Spm").val(tab[8]);
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
    url: "vitrine/ajax/vitrineAjax",
    method: "POST",
    data: {
      operation: 1,
      id: id,
    },
    success: function (data) {
      tab = data.split("<>");
      // console.log(tab[4]+"vhgvyvj");
      $("#id_edit").val(tab[0]);
      $("#designation_edit").val(tab[1]);
      $("#designation_editE").val(tab[2]);
      $("#categorie_edit").val(tab[3]);
      // $('#categorieV_edit').val(tab[4]);
      $("#uniteStock_edit").val(tab[5]);
      $("#prixuniteStock_edit").val(tab[6]);
      $("#prix_edit").val(tab[7]);
      $("#image_edit").val(tab[8]);
      $("#uniteDetails_edit").val(tab[10]);
      $("#modifierDesignation").modal("show");
      $("option[value='" + tab[4] + "']").attr("selected", "selected");
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
    url: "vitrine/ajax/vitrineAjax",
    method: "POST",
    data: {
      operation: 1,
      id: id,
    },
    success: function (data) {
      tab = data.split("<>");
      $("#id_Upd_Nv").val(tab[0]);
      $("#idB_Upd_Ex").val(tab[9]);
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
    url: "vitrine/ajax/vitrineAjax",
    method: "POST",
    data: {
      operation: 1,
      id: id,
    },
    success: function (data) {
      tab = data.split("<>");
      $("#id_Upd_Ex").val(tab[0]);
      $("#des_Upd_Ex").val(tab[2]);
      $("#idB_Upd_Ex").val(tab[9]);
      $("#img_Upd_Ex").val(tab[8]);
      //$('#imgsrc_Upd').src='uploads/'+tab[6];
      $("#imgsrc_Upd").attr("src", "vitrine/uploads/" + tab[8]);
      $("#imgsrc_Upd").attr("alt", tab[8]);
      $("#imageExDesignation").modal("show");
    },
    error: function () {
      alert("La requête ");
    },
    dataType: "text",
  });
}

$(function () {
  $(".btnAjoutArticle, .btnEditerProduit").click(function () {
    var clicked = $(this);
    var id = clicked.attr("id");
    // alert(id)
    $(document).on("click", ".typeahead li a", function (e) {
      e.preventDefault();

      query = $(this).text();
      // alert(query)

      $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
          operation: 6,
          designation: query,
        },
        success: function (data) {
          tab = data.split("<>");
          // alert(data)
          if (
            tab[1] != "Article" &&
            tab[1].toLowerCase() != "article" &&
            tab[0] != "1" &&
            tab[0] != "" &&
            tab[1] != ""
          ) {
            alert(data + "---113");
            // $("#options").removeAttr('class')selectUnite
            $(".selectUnite-" + id).html("");
            var art = new Option("article", "article");
            var us = new Option(tab[1], tab[1]);
            /// jquerify the DOM object 'o' so we can use the html method
            $(art).html("article");
            $(us).html(tab[1]);
            $(".selectUnite-" + id).append(art);
            $(".selectUnite-" + id).append(us);
          } else {
            alert(data + "---2");
            $(".selectUnite-" + id).html("");
            var art = new Option("article", "article");
            /// jquerify the DOM object 'o' so we can use the html method
            $(art).html("article");
            $(".selectUnite-" + id).append(art);
          }
          // console.log(1)
        },
        error: function () {
          alert("La requête ");
        },
        dataType: "text",
      });
    });
    $(".searchProduct-" + id).keyup(function (e) {
      e.preventDefault();

      var keycode = e.keyCode ? e.keyCode : e.which;
      var element = $(".typeahead li.active").text();
      if (keycode == "13") {
        $.ajax({
          url: "vitrine/ajax/vitrineAjax",
          method: "POST",
          data: {
            operation: 6,
            designation: element,
          },
          success: function (data) {
            tab = data.split("<>");
            // alert(data)
            if (
              tab[1] != "Article" &&
              tab[1].toLowerCase() != "article" &&
              tab[0] != "1" &&
              tab[0] != "" &&
              tab[1] != ""
            ) {
              // alert(data + "---1")
              // $("#options").removeAttr('class')selectUnite
              $(".selectUnite-" + id).html("");
              var art = new Option("article", "article");
              var us = new Option(tab[1], tab[1]);
              /// jquerify the DOM object 'o' so we can use the html method
              $(art).html("article");
              $(us).html(tab[1]);
              $(".selectUnite-" + id).append(art);
              $(".selectUnite-" + id).append(us);
            } else {
              // alert(data + "---2")
              $(".selectUnite-" + id).html("");
              var art = new Option("article", "article");
              /// jquerify the DOM object 'o' so we can use the html method
              $(art).html("article");
              $(".selectUnite-" + id).append(art);
            }
            // console.log(1)
          },
          error: function () {
            alert("La requête ");
          },
          dataType: "text",
        });
      }
    });
  });
  $(".btnRetournerProduit").click(function () {
    var clicked = $(this);
    var clicked_id = clicked.attr("id");
    var splitId = clicked_id.split("_");
    var idArticle = splitId[0];
    var idPanier = splitId[1];

    $(".btnConfRetourProduit-" + clicked_id).click(function () {
      $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
          operation: 2,
          idArticle: idArticle,
          idPanier: idPanier,
        },
        success: function (data) {
          // tab = data.split('<>');
          // alert(1)
          // console.log(1)
          window.location.href = "vitrine/commande";
        },
        error: function () {
          alert("La requête ");
        },
        dataType: "text",
      });
      // } else {
      //     $('.text-error').removeClass('hidden')
      //     $('.text-error').text('Attention : Vous ne pouvez pas retirer plus de la quantité commander.');
      // }
    });
  });

  $(".btnAjoutArticle").click(function () {
    clicked = $(this);
    idPanier = clicked.attr("id");
    // alert(idPanier)
    $(".searchProduct-" + idPanier).keyup(function () {
      var query = $(".searchProduct-" + idPanier).val();
      // var typeSearchValue = $('#searchType option:selected').text();
      if (query.length > 0) {
        // alert(query)
        $(".searchProduct-" + idPanier).typeahead({
          source: function (query, result) {
            $.ajax({
              url: "vitrine/ajax/vitrineAjax",
              method: "POST",
              data: {
                operation: 3,
                query: query,
              },
              dataType: "json",
              // data: $("#boutiqueForm").serialize(),
              success: function (data) {
                // alert(data)
                // alert('dfghsswws')
                // var boutiques = data.split(",");
                result(
                  $.map(data, function (item) {
                    // console.log(item);
                    return item;
                  })
                );
              },
              error: function (err) {
                alert("La requête ss");
                console.log(err);
              },
            });
          },
        });
        $(".searchProduct-" + idPanier).focus();
        //  else {
        //     $("#reponseS").html('');
      }
    });
  });
  $(".btnConfAjoutArticle").click(function () {
    clicked = $(this);
    idPanier = clicked.attr("id");
    ref = $(".searchProduct-" + idPanier).val();
    quantite = $(".quantite-" + idPanier).val();
    unite = $(".unite-" + idPanier).val();

    $.ajax({
      url: "vitrine/ajax/vitrineAjax",
      method: "POST",
      data: {
        operation: 7,
        ref: ref,
        quantite: quantite,
        unite: unite,
        idPanier: idPanier,
      },
      success: function (data) {
        // tab = data.split('<>');
        // alert(data)
        // console.log(1)
        window.location.href = "vitrine/commande";
      },
      error: function () {
        alert("La requête ");
      },
      dataType: "text",
    });
  });

  $(".btnEditerProduit").on("click", function () {
    // e.preventDefault();
    // alert(1)
    idBtn = $(this).attr("id");
    splitId = idBtn.split("_");
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
        idP: idPanier,
      },
      dataType: "json",
      // data: $("#boutiqueForm").serialize(),
      success: function (data) {
        ref = data.designation;
        unite = data.unite;
        quantite = data.quantite;
        idA = data.idArticle;
        idP = data.idPanier;
        idB = data.idBoutique;

        $(".searchProduct-" + idA + "_" + idP).val(ref);
        $(".oldRef-" + idA + "_" + idP).val(ref);
        $(".quantite-" + idA + "_" + idP).val(quantite);

        if (ust != "Article" && ust != "article") {
          $(".selectUnite-" + idA + "_" + idP).html("");
          var art = new Option("article", "article");
          var us = new Option(ust, ust);
          /// jquerify the DOM object 'o' so we can use the html method
          $(art).html("article");
          $(us).html(ust);
          $(".selectUnite-" + idA + "_" + idP).append(art);
          $(".selectUnite-" + idA + "_" + idP).append(us);
        } else {
          // alert(data + "---2")
          $(".selectUnite-" + idA + "_" + idP).html("");
          var art = new Option("article", "article");
          /// jquerify the DOM object 'o' so we can use the html method
          $(art).html("article");
          $(".selectUnite-" + idA + "_" + idP).append(art);
        }

        $("option[value='" + unite + "']").attr("selected", "selected");
      },
      error: function () {
        alert("La requête ddd");
      },
    });

    $(".searchProduct-" + idArticle + "_" + idPanier).keyup(function () {
      var query = $(".searchProduct-" + idArticle + "_" + idPanier).val();
      // var typeSearchValue = $('#searchType option:selected').text();
      if (query.length > 0) {
        // alert(query)
        $(".searchProduct-" + idArticle + "_" + idPanier).typeahead({
          source: function (query, result) {
            $.ajax({
              url: "vitrine/ajax/vitrineAjax",
              method: "POST",
              data: {
                operation: 3,
                query: query,
              },
              dataType: "json",
              // data: $("#boutiqueForm").serialize(),
              success: function (data) {
                // alert(data)
                // var boutiques = data.split(",");
                result(
                  $.map(data, function (item) {
                    // console.log(item);
                    return item;
                  })
                );
              },
              error: function () {
                alert("La requête ddd");
              },
            });
          },
        });
        $(".searchProduct-" + idArticle + "_" + idPanier).focus();
        //  else {
        //     $("#reponseS").html('');
      }
    });
  });

  $(".btnConfEditProduit").on("click", function () {
    ids = $(this).attr("id");
    splitIds = ids.split("_");
    oldIdA = splitIds[0];
    idP = splitIds[1];
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
        newUnite: newUnite,
      },
      dataType: "text",
      // data: $("#boutiqueForm").serialize(),
      success: function (data) {
        // alert(data)
        window.location.href = "vitrine/commande";
      },
      error: function () {
        alert("La requête ddd");
      },
    });
  });

  $(".btnConf").on("click", function () {
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
          url: "vitrine/ajax/vitrineAjax",
          method: "POST",
          data: {
            operation: 12,
            idPanier: idP,
          },
          dataType: "text",
          // data: $("#boutiqueForm").serialize(),
          success: function (data) {
            // alert(1)
            // alert(data)
            // if (data == 'ok') {
            window.location.href = "vitrine/commande";
            // }
          },
          error: function () {
            alert("La requête ddd");
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

  // $("#imgsrc_Upd").on('input', function() {
  //   // $('#apercu_file').attr('src',)
  //   v = $('#select_file').val()
  //   alert(v)
  // });

  $(".btnExp").on("click", function () {
    elementId = $(this).attr("id");
    id = elementId.split("_")[1];
    // stockIns = $("#stockIns-" + id).val();
    // alert(id)
    // alert(stockIns)
    // if (stockIns == 0) {
    $("#msg_exp_commande" + id).modal("show");
    $(".btnExpCommande-" + id).on("click", function () {
      // ids = $(this).attr('id');
      idP = $("#idPanierExp" + id).val();
      //   alert(idP)
      // alert(oldRef + '----' + newRef)
      $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
          operation: 13,
          idPanier: idP,
        },
        dataType: "text",
        // data: $("#boutiqueForm").serialize(),
        success: function (data) {
          // alert(1)
          //   alert(data)
          // if (data == 'ok') {
          window.location.href = "vitrine/commande";
          // }
        },
        error: function () {
          alert("La requête ddd");
        },
      });
    });
    $("#msg_exp_commande" + id).on("hidden.bs.modal", function () {
      // alert(1)
      // $(this).data('bs.modal', null);
      // window.location.href = "index"
      $(".btnExpCommande-" + id).removeClass("btnExpCommande-" + id);
    });
    // } else {
    //   $("#msg_echec" + id).modal('show')
    // }
  });

  $(".btnlivrer").on("click", function () {
    elementId = $(this).attr("id");
    id = elementId.split("_")[1];
    // stockIns = $("#stockIns-" + id).val();
    // alert(id)
    // alert(stockIns)
    // if (stockIns == 0) {
    $("#msg_livrer_commande" + id).modal("show");
    $(".btnLivrerCommande-" + id).on("click", function () {
      // ids = $(this).attr('id');
      idP = $("#idPanierLivrer" + id).val();
      //   alert(idP)
      // alert(oldRef + '----' + newRef)
      $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
          operation: 14,
          idPanier: idP,
        },
        dataType: "text",
        // data: $("#boutiqueForm").serialize(),
        success: function (data) {
          // alert(1)
          //   alert(data)
          // if (data == 'ok') {
          window.location.href = "vitrine/commande";
          // }
        },
        error: function () {
          alert("La requête ddd");
        },
      });
    });
    $("#msg_livrer_commande" + id).on("hidden.bs.modal", function () {
      // alert(1)
      // $(this).data('bs.modal', null);
      // window.location.href = "index"
      $(".btnLivrerCommande-" + id).removeClass("btnLivrerCommande-" + id);
    });
    // } else {
    //   $("#msg_echec" + id).modal('show')
    // }
  });

  $(".btnCancelConf").on("click", function () {
    elementId = $(this).attr("id");
    id = elementId.split("_")[1];
    // stockIns = $("#stockIns-" + id).val();
    // alert(id)
    // alert(stockIns)
    // if (stockIns == 0) {
    $("#msg_cancel_confirmation" + id).modal("show");
    $(".btnCancelConf-" + id).on("click", function () {
      // ids = $(this).attr('id');
      idP = $("#idPanierCancelConf" + id).val();
      //   alert(idP)
      // alert(oldRef + '----' + newRef)
      $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
          operation: 15,
          idPanier: idP,
        },
        dataType: "text",
        // data: $("#boutiqueForm").serialize(),
        success: function (data) {
          // alert(1)
          //   alert(data)
          // if (data == 'ok') {
          window.location.href = "vitrine/commande";
          // }
        },
        error: function () {
          alert("La requête ddd");
        },
      });
    });
    $("#msg_cancel_confirmation" + id).on("hidden.bs.modal", function () {
      // alert(1)
      // $(this).data('bs.modal', null);
      // window.location.href = "index"
      $(".btnCancelConf-" + id).removeClass("btnCancelConf-" + id);
    });
    // } else {
    //   $("#msg_echec" + id).modal('show')
    // }
  });

  $(".btnRetour").on("click", function () {
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
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
          operation: 16,
          idPanier: idP,
        },
        dataType: "text",
        // data: $("#boutiqueForm").serialize(),
        success: function (data) {
          // alert(1)
          //   alert(data)
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

  $(".btnAnnuler").on("click", function () {
    elementId = $(this).attr("id");
    id = elementId.split("_")[1];
    // stockIns = $("#stockIns-" + id).val();
    // alert(id)
    // alert(stockIns)
    // if (stockIns == 0) {
    $("#msg_annuler_pagnet" + id).modal("show");
    $(".btnAnnulerPagnet-" + id).on("click", function () {
      // ids = $(this).attr('id');
      idP = $("#idPanierAnnuler" + id).val();
      //   alert(idP)
      // alert(oldRef + '----' + newRef)
      $.ajax({
        url: "vitrine/ajax/vitrineAjax",
        method: "POST",
        data: {
          operation: 17,
          idPanier: idP,
        },
        dataType: "text",
        // data: $("#boutiqueForm").serialize(),
        success: function (data) {
          // alert(1)
          //   alert(data)
          // if (data == 'ok') {
          window.location.href = "vitrine/commande";
          // }
        },
        error: function () {
          alert("La requête ddd");
        },
      });
    });
    $("#msg_annuler_pagnet" + id).on("hidden.bs.modal", function () {
      // alert(1)
      // $(this).data('bs.modal', null);
      // window.location.href = "index"
      $(".btnAnnulerPagnet-" + id).removeClass("btnAnnulerPagnet-" + id);
    });
    // } else {
    //   $("#msg_echec" + id).modal('show')
    // }
  });
});

$(document).ready(function () {
  // alert(1)
  idImg = 0;
  /// Initializing croppie in my image_demo div
  $(document).on("click", "a img", function () {
    idImg = $(this).attr("id");
  });
  var image_crop = $("#image_demo").croppie({
    viewport: {
      width: 380,
      height: 490,
      type: "square",
    },
    boundary: {
      width: 400,
      height: 500,
    },
  });
  /// catching up the cover_image change event and binding the image into my croppie. Then show the modal.
  $("#cover_image").on("change", function () {
    //   alert(idImg+'/1')
    var reader = new FileReader();
    reader.onload = function (event) {
      image_crop.croppie("bind", {
        url: event.target.result,
      });
    };
    reader.readAsDataURL(this.files[0]);
    $("#uploadimageModal").modal("show");
  });

  /// Get button click event and get the current crop image
  $(".crop_image").click(function (event) {
    // var formData = new FormData();
    // alert(idImg+'/2')
    id = $("#id_Upd_Nv").val();
    idB = $("#idB_Upd_Nv").val();
    des = $("#des_Upd_Nv").val();
    image_crop
      .croppie("result", { type: "canvas", size: "viewport" })
      .then(function (res) {
        // formData.append('cropped_image', blob);
        // alert(10)
        ajaxFormPost(res, "vitrine/ajax/vitrineAjax", id, idB, des);
        // ajaxFormPost(res, 'vitrine/new/cropperAjax');
        // console.log(formData); /// Calling my ajax function with my blob data passed to it
      });
    $("#uploadimageModal").modal("hide");
    $("#imageNvDesignation").modal("hide");
  });
  /// Ajax Function
  function ajaxFormPost(formData, actionURL, id, idB, des) {
    // alert(2)
    //   alert(idImg+'/3')
    $.ajax({
      url: actionURL,
      type: "POST",
      data: {
        op: "croppe",
        id: id,
        idB: idB,
        des: des,
        data: formData,
      },
      // data: formData,
      success: function (response) {
        console.log(response);
        if (response) {
          ///Some Fancy UI, that you won't probably need
          // alert(response)
          // window.location.href = 'vitrine/vitrine';
          $("#" + idImg).attr("src", "images/iconfinder11.png");
          $("#" + idImg).attr("onclick", "imgEX_DesignationVT(" + idImg + ")");
          $("#" + idImg).attr("data-target", "#app" + idImg);
          $("#" + idImg).attr("alt", "apperçu");
          $("#" + idImg)
            .parent()
            .parent()
            .parent()
            .css("background", "green")
            .hide(2000); // OR $(this).parents('tr').remove();

          /// But this part you may need, reset the input value and put the cropped image inside my image attribute.
          $("#cover_image").val("");
          $("#uploaded-image").attr("src", response["url"]);
        }
      },
      dataType: "text",
    });
  }
});
$(document).ready(function () {
  // alert(1)
  // viewport: {
  //         width: 450,
  //         height: 450,
  //         type:'square'
  //     },
  //     boundary:{
  //         width: 650,
  //         height: 450
  //     }
  /// Initializing croppie in my image_demo div
  var image_crop = $("#image_demo_edit").croppie({
    viewport: {
      width: 500,
      height: 480,
      type: "square",
    },
    boundary: {
      width: 520,
      height: 500,
    },
  });
  /// catching up the cover_image change event and binding the image into my croppie. Then show the modal.
  $("#cover_image_edit").on("change", function () {
    var reader = new FileReader();
    reader.onload = function (event) {
      image_crop.croppie("bind", {
        url: event.target.result,
      });
    };
    reader.readAsDataURL(this.files[0]);
    $("#uploadimageModalEdit").modal("show");
  });

  /// Get button click event and get the current crop image
  $(".crop_image_edit").click(function (event) {
    // var formData = new FormData();
    id = $("#id_Upd_Ex").val();
    idB = $("#idB_Upd_Ex").val();
    des = $("#des_Upd_Ex").val();
    img = $("#img_Upd_Ex").val();
    image_crop
      .croppie("result", { type: "canvas", size: "viewport" })
      .then(function (res) {
        // formData.append('cropped_image', blob);
        ajaxFormPostEdit(res, "vitrine/ajax/vitrineAjax", id, idB, des, img);
        // ajaxFormPost(res, 'vitrine/new/cropperAjax');
        // console.log(formData); /// Calling my ajax function with my blob data passed to it
      });
    $("#uploadimageModalEdit").modal("hide");
    $("#imageExDesignation").modal("hide");
  });
  /// Ajax Function
  function ajaxFormPostEdit(formData, actionURL, id, idB, des, img) {
    $.ajax({
      url: actionURL,
      type: "POST",
      data: {
        op: "croppe",
        id: id,
        idB: idB,
        des: des,
        img: img,
        data: formData,
      },
      // data: formData,
      success: function (response) {
        // alert(response)
        if (response) {
          ///Some Fancy UI, that you won't probably need
          // alert(111)
          $("#image" + id)
            .parent()
            .parent()
            .parent()
            .css("background", "green")
            .hide(2000); // OR $(this).parents('tr').remove();
          $("#image" + id)
            .parent()
            .parent()
            .parent()
            .css("background", "")
            .show(2000);
          // window.location.href = 'vitrine/vitrine';
          /// But this part you may need, reset the input value and put the cropped image inside my image attribute.
          $("#cover_image_edit").val("");
          // $('#uploaded-image').attr('src', response['url']);
        }
      },
      dataType: "text",
    });
  }
});

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
$(document).ready(function () {
  $("#btnSubmit").click(function () {
    var fd = new FormData();
    var files = $("#userImage")[0].files;

    // Check file selected or not
    if (files.length > 0) {
      fd.append("file", files[0]);

      $.ajax({
        url: "vitrine/crop/upload",
        type: "post",
        data: fd,
        contentType: false,
        processData: false,
        success: function (response) {
          if (response != 0) {
            alert(response);
            $(".img").removeAttr("src");
            $(".img").attr("src", "vitrine/crop/uploads/" + response);
            $("div.jcrop-holder div div img").attr(
              "src",
              "vitrine/crop/uploads/" + response
            );
            // $(".preview img").show(); // Display image element
          } else {
            alert("file not uploaded");
          }
        },
      });
    } else {
      alert("Please select a file.");
    }
  });
  $("#crop").click(function () {
    var fd = new FormData();
    var files = $("#userImage")[0].files;

    // Check file selected or not
    if (files.length > 0) {
      fd.append("file", files[0]);

      $.ajax({
        url: "vitrine/crop/upload",
        type: "post",
        data: fd,
        crop: "crop",
        contentType: false,
        processData: false,
        success: function (response) {
          if (response != 0) {
            alert(response + " zzzz");
            $(".img").removeAttr("src");
            $(".img").attr("src", "vitrine/crop/uploads/" + response);
            $("div.jcrop-holder div div img").attr(
              "src",
              "vitrine/crop/uploads/" + response
            );
            // $(".preview img").show(); // Display image element
          } else {
            alert("file not uploaded");
          }
        },
      });
    } else {
      alert("Please select a file.");
    }
  });
});
/************* Modification ***************/

function deleteImg() {
  var id = $("#id_Upd_Ex").val();
  var image = $("#img_Upd_Ex").val();
  // var id = $('#id_Upd_Ex').val();

  $.ajax({
    url: "vitrine/ajax/vitrineAjax",

    method: "POST",

    data: {
      operation: "deleteImg",

      id: id,

      image: image,
    },

    success: function (data) {
      // console.log($("#"+id).parent().parent())

      $("#imageExDesignation").modal("hide");
      // if (data==1) {

      $("#image" + id).attr("src", "images/iconfinder9.png");
      $("#image" + id).attr("onclick", "imgNV_DesignationVT(" + id + ")");
      $("#image" + id).attr("data-target", "#img" + id);
      $("#image" + id).attr("alt", "apperçu");
      $("#image" + id)
        .parent()
        .parent()
        .parent()
        .css("background", "red")
        .hide(2000); // OR $(this).parents('tr').remove();
      // $("#"+id).parent().parent().parent().css('background','').show(2000);

      // } else {
      //     alert("Erreur de suppression de l'image")
      // }
    },

    error: function () {
      alert("La requête ");
    },

    dataType: "text",
  });
}

/**************************************************** */
$(document).ready(function () {
  $("img").click(function () {
    // alert(5545)
    var img = $(this).attr("src");
    var caption = $(this).attr("alt");
    $("#show-img").attr("src", img);
    $("#caption").html(caption);
    $("#myModal").modal("show");
    $(".modal-backdrop").remove();
  });
});

// function changeDepot(idArticle) {
//   var idEntrepot = $("#entrepot-" + idArticle).val();

//   $.ajax({
//     url: "vitrine/ajax/vitrineAjax",

//     method: "POST",

//     data: {
//       operation: 18,

//       idArticle: idArticle,

//       idEntrepot: idEntrepot,
//     },

//     success: function (data) {
//       if (data == "1" || data == 1) {
//       } else {
//         confirm("Une erreur est survenue. Rééssayer svp.");
//       }
//     },

//     error: function () {
//       confirm("Une erreur est survenue. Rééssayer svp.");
//     },

//     dataType: "text",
//   });
// }
