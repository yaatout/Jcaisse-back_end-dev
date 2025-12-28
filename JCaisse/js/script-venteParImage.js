$(document).ready(function () {
  $(document).on("click", ".btn_disabled_after_click", function () {
    // alert(11)
    $(this).prop("disabled", true);
  });
  //   $(document).on("click", ".terminerPanier", function (e) {
  //     // alert(11);
  //     // e.preventDefault(); //prevents the default submit action
  //     $(this).closest("form").attr("target", "_blank").submit();
  //   });
  //   alert(4444);
  //   $("#ticket").submit(function () {
  //     alert("Submitted");
  //   });
});

function addProductInCart(id, qty) {
  $.ajax({
    url: "ajax/venteParImageAjax.php",

    method: "POST",

    data: {
      operation: 1,

      idDesignation: id,

      qty: qty,
    },

    success: function (data) {
      console.log(data);

      var f = new Intl.NumberFormat("fr-FR", { maximumFractionDigits: 0 });
      $("#nbProduits").text(parseInt($("#nbProduits").text()) + parseInt(qty));
      $("#nbByArticle" + id).text(
        parseInt($("#nbByArticle" + id).text()) + parseInt(qty)
      );

      $("#qtyList-" + id).text(
        parseInt($("#qtyInput-" + id).val()) + parseInt(qty)
      );
      $("#qtyInput-" + id).val(
        parseInt($("#qtyInput-" + id).val()) + parseInt(qty)
      );

      $("#soustotal-" + id).text(
        f.format(
          parseInt($("#prixInput-" + id).val()) *
            parseInt($("#qtyInput-" + id).val())
        )
      );

      $("#totalPanier").text(
        f.format(
          parseInt($("#totalPanierInput").val()) +
            parseInt($("#prixInput-" + id).val()) * parseInt(qty)
        )
      );
      $("#totalPanierInput").val(
        parseInt($("#totalPanierInput").val()) +
          parseInt($("#prixInput-" + id).val()) * parseInt(qty)
      );
      if (parseInt($("#nbByArticle" + id).text()) + parseInt(qty) == 0) {
        $("#nbByArticle" + id).css("display", "none");
      } else {
        $("#nbByArticle" + id).css("display", "block");
      }
    },

    error: function () {
      confirm("Une erreur est survenue : vérifier votre connexion.");
    },

    dataType: "text",
  });
}

function choixCategorie(idCategorie, nomCategorie) {
  //   alert(idCategorie);

  $("#venteParImage_container").load(
    "ajax/venteParImage_containerAjax.php",
    {
      operation: 1,
      idCategorie: idCategorie,
      nomCategorie: nomCategorie,
    },
    function (data) {
      // alert(data)
      // alert(visualisation)
      //   if (visualisation == 1) {
      //     $(".imageConf").hide();
      //     // $('.qtyplus').hide();
      //     // $('.qtyplus').attr('disabled','disabled');
      //   }
      //   $(".loading-gif").hide();
      //   $("#products_container").show();
    }
  ); //load initial records
}

function deleteProductInCart(id) {
  $.ajax({
    url: "ajax/venteParImageAjax.php",

    method: "POST",

    data: {
      operation: 2,

      idDesignation: id,
    },

    success: function (data) {
      console.log(data);

      var f = new Intl.NumberFormat("fr-FR", { maximumFractionDigits: 0 });

      $("#nbProduits").text(parseInt($("#nbProduits").text()) - parseInt(data));
      $("#nbByArticle" + id).css("display", "none");
      $("#nbByArticle" + id).text(0);
      $("#delete-" + id)
        .parent()
        .parent()
        .css("background", "red")
        .hide(1500);

      $("#totalPanierInput").val(
        parseInt($("#totalPanierInput").val()) -
          parseInt($("#prixInput-" + id).val()) *
            parseInt($("#qtyInput-" + id).val())
      );

      $("#totalPanier").html(f.format(parseInt($("#totalPanierInput").val())));
      $("#qtyInput-" + id).val(0);
    },

    error: function () {
      confirm("Une erreur est survenue : vérifier votre connexion.");
    },

    dataType: "text",
  });
}
function terminerPanier() {
  //   alert(option);
  compte = $("#moyenDePaiement").val();

  $.ajax({
    url: "ajax/venteParImageAjax.php",

    method: "POST",

    data: {
      operation: 3,
      compte: compte,
    },

    success: function (data) {
      console.log(data);
      $("#idPanierInput").val(data);
      setTimeout(() => {
        $(".terminerPanier").closest("form").attr("target", "_blank").submit();
        window.location.reload();
      }, 200);
    },

    error: function () {
      confirm("Une erreur est survenue : vérifier votre connexion.");
    },

    dataType: "text",
  });
}

function afficherPanier() {
  $("#panier_container").load(
    "ajax/panier_containerAjax.php",
    {
      operation: 4,
    },
    function (data) {
      $("#panierContenuModal").modal("show");
      // alert(data)
      // alert(visualisation)
      //   if (visualisation == 1) {
      //     $(".imageConf").hide();
      //     // $('.qtyplus').hide();
      //     // $('.qtyplus').attr('disabled','disabled');
      //   }
      //   $(".loading-gif").hide();
      //   $("#products_container").show();
    }
  ); //load initial records
}
