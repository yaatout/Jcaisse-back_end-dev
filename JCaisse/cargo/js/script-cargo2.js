$(function () {
  $(document).on("keyup", ".clientInput", function (e) {
    e.preventDefault();

    typeClient = "simple";

    idPanier = $(this).attr("data-idPanier");

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
});

function changerPorteur(idVersement) {
  var query = $("#porteurInput" + idVersement).val();
  //   alert(74);

  if (query.length > 0) {
    /*********** Modification **************/

    $("#porteurInput" + idVersement).typeahead({
      source: function (query, result) {
        $.ajax({
          url: "cargo/ajax/operationCargoAjax.php",

          method: "POST",

          data: {
            porteurVersement: "porteurVersement",
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

    $("#porteurInput" + idVersement).focus();

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

          idVersement: idVersement,
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
    }

    $(document).on("click", "#tabDepense .typeahead li.active", function (e) {
      e.preventDefault();

      var text = $("#tabDepense .typeahead li.active").text();
      $("#idDepense").val(text.split(" . ")[0]);
      $("#libelle_depense").val(text.split(" . ")[1]);
    });
  }
}
