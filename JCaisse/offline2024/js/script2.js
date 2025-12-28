/*************** Début chargement container vente ********************/

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

    alert(page);

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

  $(function () {
    typeData = "";

    $(document).on("keyup", ".clientInput", function (e) {
      e.preventDefault();

      typeClient = "simple";

      idPanier = $(this).attr("data-idPanier");

      typeData = $(this).attr("data-type");

      var query = $(this).val();

      if (query.length > 0) {
        /*********** Modification **************/
        const clients = JSON.parse(localStorage.getItem("Client"));
        data = clients.filter((c) => c.activer == 1 && c.archiver == 0);
        //var lesClients=JSON.parse(localStorage.getItem("Client"));
        var tab = new Array();
        if (data != null) {
          for (var i = 0; i < data.length; i++) {
            tab[i] =
              data[i].idClient + "." + data[i].prenom + " " + data[i].nom;
          }
        }

        $(this).typeahead({
          source: function (query, result) {
            dataType: "json",
              result(
                $.map(tab, function (item) {
                  return item;
                })
              );
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
          //alert(idClient)
          //$("#clientInput"+idPanier).val(tab[1]);

          lesClients = getClient();
          searchClient = lesClients.find(
            (c) => c.idClient == parseInt(idClient)
          );

          if (searchClient != undefined) {
            //localStorage.setItem('idCompte',searchCompte.idCompte);
            var paniers = getPanier();

            var searchPanier = paniers.find((p) => p.idPagnet == idPanier);

            if (searchPanier != undefined) {
              searchPanier.idClient = searchClient.idClient;
            }
            savePanier(paniers);
          }

          $("#avanceInput" + idPanier).focus();
        }
      }
      localStorage.setItem("typeVente", "simple");
    });
  });

  $(document).on("keyup", ".avanceInput", function () {
    var avance = $(this).val();

    var idPanier = $(this).attr("data-idPanier");

    var total = $("#somme_Apayer" + idPanier).text();

    //localStorage.setItem('avance',avance)

    var paniers = getPanier();

    var searchPanier = paniers.find((p) => p.idPagnet == idPanier);

    if (searchPanier != undefined) {
      searchPanier.avance = avance;
    }
    savePanier(paniers);

    if (avance > parseInt(total)) {
      v = $("#avanceInput" + idPanier).val();

      // $('#avanceInput'+idPanier).blur();

      $("#avanceInput" + idPanier).val(v.substring(0, v.length - 1));

      //localStorage.setItem('avance',v.substring(0, v.length-1))

      if (searchPanier != undefined) {
        searchPanier.avance = v.substring(0, v.length - 1);
      }
      savePanier(paniers);

      $("#msg_info_avance").modal("show");
    }
  });

  $(document).on("click", ".modeEditionBtn", function () {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-editer").show();

    var id = $(this).attr("id");
    //alert(id)

    result = id.split("-");

    idPagnet = result[1];

    localStorage.setItem("IdPanier", idPagnet);

    var lesPaniers = getPanier();
    date = new Date();
    datejour = getDateJour();

    if (lesPaniers != null) {
      var searchPanier = lesPaniers.find((p) => p.idPagnet == idPagnet);

      if (searchPanier != undefined) {
        searchPanier.verrouiller = 0;
      } else {
        alert("Erreur");
      }
      savePanier(lesPaniers);
      $("#panierClient").load("loadContainerClient.html");
    }
  });

  $(document).on("keyup", ".inputRetourApres", function (e) {
    e.preventDefault();

    // alert(12354)

    var keycode = e.keyCode ? e.keyCode : e.which;

    if (keycode == "13") {
      // $("#loading_gif_modal").modal("show");

      $(".img-load-retourApres").show();

      numligne = $(this).attr("data-numligne");
    }
  });

  $(document).on("click", ".btnRetourApres", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-retourApres").show();

    $(".btnRetourApres").attr("disabled", "disabled");

    numligne = $(this).attr("data-numligne");
    retour_Produit(numligne);
    $(".modal-backdrop").remove();
    $("#panierClient").load("loadContainerClient.html");
  });

  $(document).on("click", ".btnRetourAvant", function (e) {
    // $("#loading_gif_modal").modal("show");

    $(".img-load-retourAvant").show();

    $(".btnRetourAvant").attr("disabled", "disabled");

    numligne = $(this).attr("data-numligne");

    // $("#msg_rtrnAvant_ligne"+numligne).modal('hide')
    retour_Produit(numligne);
    $(".modal-backdrop").remove();

    $("#panierClient").load("loadContainerClient.html");
  });

  $(document).on("click", ".btnRetournerPagnet", function (e) {
    // $("#loading_gif_modal").modal("show");

    //$(".img-load-retournerPanier").show();

    $(".btnRetournerPagnet").attr("disabled", "disabled");

    idpanier = $(this).attr("data-idPanier");

    panier = getPanier();
    ligne = getLigne();
    //var searchPanier=panier.filter(p =>p.idPagnet==idpanier);

    // if(searchPanier != undefined){
    //     searchPanier.type=2;

    // }else{
    //     alert("Erreur");
    // }

    paniers = panier.filter((p) => p.idPagnet != idpanier);
    //alert(paniers)

    lignes = ligne.filter((l) => l.idPagnet != idpanier);
    //alert("ligne: "+lignes)
    saveLigne(lignes);
    savePanier(paniers);

    $(".modal-backdrop").remove();

    $("#panierClient").load("loadContainerClient.html");
  });

  $(document).on("click", ".btnAnnulerPagnet", function (e) {
    //alert(1);
    // $("#loading_gif_modal").modal("show");

    //$(".img-load-annulerPanier").show();

    $(".btnAnnulerPagnet").attr("disabled", "disabled");

    var idPanier = $(this).attr("data-idPanier");

    panier = getPanier();
    ligne = getLigne();

    paniers = panier.filter((p) => p.idPagnet != idPanier);

    lignes = ligne.filter((l) => l.idPagnet != idPanier);
    //alert("ligne: "+lignes)
    saveLigne(lignes);
    savePanier(paniers);

    //alert(panier);

    $(".modal-backdrop").remove();
    $("#panierClient").load("loadContainerClient.html");
  });

  $(document).on("click", ".btn_Termine_Panier", function (e) {
    //somme_A_payer();
    //users=JSON.parse(localStorage.getItem("Boutique"));
    date = new Date();
    datejour = getDateJour();
    localStorage.setItem("datejour", datejour);

    heure = date.toLocaleTimeString("fr");

    tabDate = datejour.split("-");
    tabHeure = heure.split(":");

    var idPanier = $(this).attr("data-idPanier");

    paniers = getPanier();
    // $("#loading_gif_modal").modal("show");

    remise = $("#val_remise1" + idPanier).val()
      ? $("#val_remise1" + idPanier).val()
      : 0;
    versement = $("#versement" + idPanier).val()
      ? $("#versement" + idPanier).val()
      : 0;

    //$("#pagnet"+idPanier).attr('class', "panel-collapse collapse in");

    //versement=localStorage.getItem("versement")
    //idClient=localStorage.getItem('idClient');
    total_p = $("#somme_Total" + idPanier).text();
    // alert(versement);

    apayer_p = $("#somme_Apayer" + idPanier).text();

    //var paniers=getPanier();

    var searchPanier = paniers.find((p) => p.idPagnet == idPanier);
    // var searUser=users.find(u =>u.idutilisateur==sessionStorage.iduserSession)
    // Panier.iduser =searUser.idutilisateur;

    if (searchPanier != undefined) {
      searchPanier.verrouiller = 1;
      searchPanier.totalp = total_p;
      searchPanier.apayerPagnet = apayer_p;
      searchPanier.remise = remise;
      searchPanier.versement = versement;
      //searchPanier.datepagej=datejour;
      searchPanier.heurePagnet = heure;
      searchPanier.identifiantP =
        tabDate[0] +
        "" +
        tabDate[1] +
        "" +
        tabDate[2] +
        "" +
        tabHeure[0] +
        "" +
        tabHeure[1] +
        "" +
        tabHeure[2] +
        "" +
        searchPanier.iduser;
      //searchPanier.idCompte=idCompte;
      //searchPanier.idClient=idClient;

      if (versement == 0) {
        searchPanier.restourne = 0;
      } else {
        searchPanier.restourne = versement - apayer_p;
      }
      // if(searchPanier.idCompte==2){
      searchPanier.type = 30;
      // }
      // else{
      //     searchPanier.type=0;
      // }
      searchPanier.dejaTerminer = 1;
    } else {
      alert("Erreur");
    }
    savePanier(paniers);
    localStorage.setItem("position", "bc");

    //$("#somme_Total"+idPanier).text(somme_A_payer(idPanier));
    // $("#somme_Apayer"+idPanier).text(somme_A_payer(idPanier) - remise);

    $("#panierClient").load("loadContainerClient.html");
  });

  $(document).on("click", "#addPanier", function (e) {
    //window.location.reload(true)
    addPanier();
    localStorage.setItem("typeVente", "bon");

    // $("#panierClient").load("loadContainerClient.html");
  });

  // $(function(){

  //     date = new Date();
  //     sec = date.getSeconds();
  //     setTimeout(()=>{
  //         setInterval(()=>{
  //             if(navigator.onLine==false){
  //                 $(".menu a").each(function(){

  //                     if($(this).hasClass("disabled")){
  //                     $(this).removeAttr("href");
  //                     }
  //                 });
  //                 $('.offline').hide()

  //             }else{
  //                 $('.offline').show()
  //             }

  //         }, 60 * 1);
  //     }, (60 - sec) * 1);

  //     $('.caissier').hide()

  // });

  //   window.onload = function() {
  //     $("#pagnet"+idPanier).collapse("show");
  //   }

  //$(".modeEditionBtn").attr("disabled","disabled");
});

function getDateJour() {
  let date = new Date();
  let dateDujour = date.toLocaleDateString("es-CL");
  heure = date.toLocaleTimeString("fr");
  hd=heure.split(":");
  if(hd[0]>5){ 
    return dateDujour
  }else{
    return formatDate(dateDhier(dateDujour))
  }

}   

function getHeureJour() {
  let date = new Date();
  //let dateDujour = date.toLocaleDateString("es-CL");
  heure = date.toLocaleTimeString("fr");
  return heure
}

function dateDhier(date = new Date()) {
  const previous = new Date(date.getTime());
  previous.setDate(date.getDate() - 1);

  return previous;
}

function padTo2Digits(num) {
  return num.toString().padStart(2, "0");
}

function formatDate(date) {
  return [
    padTo2Digits(date.getDate()),
    padTo2Digits(date.getMonth() + 1),
    date.getFullYear(),
    //   padTo2Digits(date.getMonth() + 1),
    //   padTo2Digits(date.getDate()),
  ].join("-");
}

/*************** Fin chargement container vente ********************/

/** Debut Autocomplete Vente Boutique*/
$(function () {
  idPanier = 0;

  $(document).on("keyup", ".codeBarreLigneVt", function (e) {
    e.preventDefault();

    var tabIdPanier = $(this).attr("id");

    tab = tabIdPanier.split("_");

    idPanier = tab[1];

    var query = $(this).val();

    //alert(query);

    if (query.length > 0) {
      const data = JSON.parse(localStorage.getItem("Designation"));
      var tab = new Array();
      if (data != null) {
        for (var i = 0; i < data.length; i++) {
          tab[i] = data[i].designation;
        }
      }

      $(this).typeahead({
        source: function (query, result) {
          dataType: "json",
            result(
              $.map(tab, function (item) {
                return item;
              })
            );
        },
      });

      $(this).focus();

      /*********** Quand on tape sur Entrer **************/

      var keycode = e.keyCode ? e.keyCode : e.which;

      // alert(keycode)

      if (keycode == "13") {
        inputVal = $(this).val();
        if ($.isNumeric(inputVal)) {
          codebarre = $(this).val();
          val = $(this).val().length;
          if (val >= 9) {
            addLigneKeyup(idPanier, codebarre);
          }
        } else {
          addLigne(idPanier);
        }
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
    localStorage.setItem("typeVente", "bon");
  });

  $("#panierClient").load("loadContainerClient.html");
});
/** Fin Autocomplete Vente Boutique*/

/*** */
var design = "";

$(document).on("click", '.typeahead li a[class="dropdown-item"]', function (e) {
  e.preventDefault();

  if (localStorage.getItem("typeVente") != "simple") {
    addLigne(idPanier);
  }
});

$(document).on(
  "click",
  '#factForm .typeahead li a[class="dropdown-item"]',
  function (e1) {
    e1.preventDefault();
    var text =
      $("#factForm .typeahead li.active").text() ||
      $("#factFormM .typeahead li.active").text();

    tab = text.split(".");

    idClient = tab[0];
    lesClients = getClient();
    searchClient = lesClients.find((c) => c.idClient == parseInt(idClient));

    if (searchClient != undefined) {
      //localStorage.setItem('idCompte',searchCompte.idCompte);
      var paniers = getPanier();

      var searchPanier = paniers.find((p) => p.idPagnet == idPanier);

      if (searchPanier != undefined) {
        searchPanier.idClient = searchClient.idClient;
      }
      savePanier(paniers);
    }
    $("#avanceInput" + idPanier).focus();
  }
);

// $(function(){
//   if(sessionStorage.proprietaireSession==0){
//     alert(1)
//     $('#accueil1').hide()
//   }

// });

/** Fonction Ajout */

function savePanier(panier) {
  localStorage.setItem("Panier", JSON.stringify(panier));
  //$("#idPanier").load("container.html");
}

function sauvegardePanier(pagnet) {
  var dateHier = formatDate(dateDhier(date));

  localStorage.setItem("pagnet_du_" + dateHier, JSON.stringify(pagnet));
  //$("#idPanier").load("container.html");
}

function sauvegardeLigne(ligne) {
  var dateHier = formatDate(dateDhier(date));

  localStorage.setItem("ligne_du_" + dateHier, JSON.stringify(ligne));
  //$("#idPanier").load("container.html");
}

function saveLigne(ligne) {
  localStorage.setItem("Ligne", JSON.stringify(ligne));

  $(".codeBarreLigneVt").val("");
}

function getPanier() { 
  date = new Date();
  datejour = getDateJour();
  //datejour = date.toLocaleDateString("es-CL");
  iduser = localStorage.getItem("Iduser");
  const pagnier = localStorage.getItem("Panier");
  if (pagnier == null) {
    return [];
  }
  return JSON.parse(pagnier);
}

function getPanierSynch() {
  const pagnier = localStorage.getItem("PanierSynch");
  if (pagnier == null) {
    return [];
  }
  return JSON.parse(pagnier);
}

function getLigne() {
  date = new Date();
  //datejour = date.toLocaleDateString("es-CL");
  datejour = getDateJour();
  iduser = localStorage.getItem("Iduser");
  const ligne = localStorage.getItem("Ligne");
  if (ligne == null) {
    return [];
  }

  return JSON.parse(ligne);
}

function getCompte() {
  const compte = localStorage.getItem("Compte");
  if (compte == null) {
    return [];
  }

  return JSON.parse(compte);
}

function getClient() {
  const client = localStorage.getItem("Client");
  if (client == null) {
    return [];
  }

  return JSON.parse(client);
}

function getVersement() {
  // date=new Date();
  // datejour=date.toLocaleDateString("es-CL");

  const versement = localStorage.getItem("Versement");
  if (versement == null) {
    return [];
  }
  return JSON.parse(versement);
}

function getUser() {
  const user = localStorage.getItem("User");
  if (user == null) {
    return [];
  }

  return JSON.parse(user);
}

function getBoutique() {
  const boutique = localStorage.getItem("Boutique");
  if (boutique == null) {
    return [];
  }

  return JSON.parse(boutique);
}

function getAcces() {
  const acces = localStorage.getItem("Access");
  if (acces == null) {
    return [];
  }

  return JSON.parse(acces);
}

function getDesignation() {
  const designation = localStorage.getItem("Designation");
  if (designation == null) {
    return [];
  }

  return JSON.parse(designation);
}

function addPanier() {
  lesPaniers = getPanier();
  users = JSON.parse(localStorage.getItem("Boutique"));
  //comptes=JSON.parse(localStorage.getItem("Compte"));
  date = new Date();
  //datejour = date.toLocaleDateString("es-CL");
  datejour = getDateJour();
  localStorage.setItem("datejour", datejour);

  heure = date.toLocaleTimeString("fr");

  tabDate = datejour.split("-");
  tabHeure = heure.split(":");

  //datehier=tabDate[0]-1+'-'+tabDate[1]+'-'+tabDate[2]

  //alert(date.getDate()-1)

  var i = 1;
  //localStorage.setItem("IdPanier",pagnets.length)
  if (lesPaniers != null && users != null) {
    for (var j = 0; j < lesPaniers.length; j++) {
      if (lesPaniers[j].verrouiller == 0) {
        i = i + 1;
      }
    }
  }

  if (i <= 2 && users != null) {
    //alert(lesPaniers.length)
    var pagnets = getPanier();
    var comptes = getCompte();
    //panierNV=pagnets.filter(p =>p.verrouiller==0)
    localStorage.setItem("IdPanier", pagnets.length);
    idClient = localStorage.getItem("idClient");

    Panier = new Object();
    Panier.idPagnet = pagnets.length;
    Panier.identifiantP =
      tabDate[0] +
      "" +
      tabDate[1] +
      "" +
      tabDate[2] +
      "" +
      tabHeure[0] +
      "" +
      tabHeure[1] +
      "" +
      tabHeure[2] +
      "" +
      sessionStorage.iduserSession;
    Panier.datepagej = datejour;
    Panier.type = 0;
    Panier.heurePagnet = heure;
    Panier.totalp = 0;
    Panier.remise = 0;
    Panier.apayerPagnet = 0;
    Panier.restourne = 0;
    Panier.versement = 0;
    Panier.verrouiller = 0;
    Panier.idClient = idClient;
    Panier.idVitrine = 0;
    compte = comptes.find((c) => c.nomCompte == "Bon" || c.nomCompte == "bon");
    Panier.idCompte = compte.idCompte;
    Panier.avance = 0;
    Panier.dejaTerminer = 0;
    Panier.apayerPagnetTvaP = 0;
    Panier.apayerPagnetTvaR = 0;
    Panier.image = null;
    Panier.synchronise = 0;
    Panier.offline = 1;
    //var searUser=users.find(u =>u.idutilisateur==sessionStorage.iduserSession)
    Panier.iduser = sessionStorage.iduserSession;

    pagnets.push(Panier);
    // ligne=[];
    // if(!localStorage.ligne){
    //     localStorage.setItem("ligne",JSON.stringify(ligne));
    // }
    savePanier(pagnets);

    var p =
      '<div class="panel panel-primary" id=panelPanier' +
      Panier.idPagnet +
      ">" +
      '<div class="panel-heading">' +
      '<h4 data-toggle="collapse" data-parent="#accordion" href="#pagnet' +
      Panier.idPagnet +
      '"  class="panel-title expand">' +
      '<div class="right-arrow pull-right">+</div>' +
      '<a class="row" href="#">' +
      '<span class="noImpr col-md-2 col-sm-2 col-xs-12" id="panierEncours' +
      Panier.idPagnet +
      '"> Panier : En cours... </span>' +
      '<span class="noImpr col-md-3 col-sm-3 col-xs-12">Heure: ' +
      Panier.heurePagnet +
      "</span>" +
      '<span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total panier: <span id="somme_Total' +
      Panier.idPagnet +
      '" > ' +
      Panier.totalp +
      " </span></span>" +
      '<span class="noImpr col-md-3 col-sm-3 col-xs-12" >Total A  payer: <span id="somme_Apayer' +
      Panier.idPagnet +
      '" >' +
      (Panier.apayerPagnet - Panier.remise) +
      "</span></span>" +
      "</a>" +
      "</h4>" +
      "</div>" +
      '<div id="pagnet' +
      Panier.idPagnet +
      '" class="panel-collapse collapse in panelVente" > ' +
      '<div class="panel-body" >' +
      '<div class="cache_btn_Terminer row" id="cache_btn_Terminer' +
      Panier.idPagnet +
      '">' +
      //<!--*******************************Debut Ajouter Ligne****************************************-->

      '<form  class="form-inline noImpr ajouterProdForm col-md-4 col-sm-4 col-xs-12" id="ajouterProdFormB' +
      Panier.idPagnet +
      '" onsubmit="return false" >' +
      '<input type="text" class="inputbasic form-control codeBarreLigneVt" name="codeBarre" id="panier_' +
      Panier.idPagnet +
      '" data-idPanier=' +
      Panier.idPagnet +
      ' style="width:100%;" autofocus="" autocomplete="off"  required />' +
      '<input type="hidden" name="idPagnet" id="idPagnet" value=' +
      Panier.idPagnet +
      ">" +
      "</form>" +
      //<!--*******************************Fin Ajouter Ligne****************************************-->

      '<div class="col-md-8 col-sm-8 col-xs-12 content2">' +
      //<!--*******************************Debut Terminer Pagnet****************************************-->

      '<form class="form-inline noImpr" id="factForm" method="post">' +
      //'<div id="compte_caisse'+Panier.idPagnet+'">'+
      '<input type="number" min="0" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" id="val_remise1' +
      Panier.idPagnet +
      '" onkeyup="remise_Panier(this.value,' +
      Panier.idPagnet +
      ')" name="remise"  class="remise form-control col-md-2 col-sm-2 col-xs-3" placeholder="Remise....">' +
      '<select class="compte ' +
      Panier.idPagnet +
      ' compteCaisse form-control col-md-2 col-sm-2 col-xs-3" style="display:none;" onchange="modif_CompteCaisse(this.value,' +
      Panier.idPagnet +
      ')" data-idPanier=' +
      Panier.idPagnet +
      ' name="compte' +
      Panier.idPagnet +
      '"  id="compteCaisse' +
      Panier.idPagnet +
      '">' +
      "</select>" +
      '<input type="number" min="0" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" name="versement" id="versement' +
      Panier.idPagnet +
      '" onkeyup="modif_Versement(this.value,' +
      Panier.idPagnet +
      ')"  placeholder="Espèce..."' +
      'class="versement form-control col-md-2 col-sm-2 col-xs-3" style="display:none;">' +
      '<input type="text" class="client clientInput form-control col-md-2 col-sm-2 col-xs-3" style="display:none;"  data-type="simple" data-idPanier="' +
      Panier.idPagnet +
      '" name="clientInput" id="clientInput' +
      Panier.idPagnet +
      '" autocomplete="off" placeholder="Client"/>' +
      '<input type="number"  class="avanceInput form-control col-md-2 col-sm-2 col-xs-3"  data-idPanier="' +
      Panier.idPagnet +
      '" name="avanceInput" id="avanceInput' +
      Panier.idPagnet +
      '" autocomplete="off" placeholder="Avance"/>' +
      '<select class="compte compteBon form-control col-md-2 col-sm-2 col-xs-3" onchange="modif_CompteBon(this.value,' +
      Panier.idPagnet +
      ')"  name="compte"  id="compteBon' +
      Panier.idPagnet +
      '">' +
      "</select>" +
      '<input type="hidden" name="idPagnet"   value=' +
      Panier.idPagnet +
      ">" +
      '<input type="hidden" name="totalp" id=totalp' +
      Panier.idPagnet +
      ' value="0" >' +
      '<button type="button" style="" disabled="true" name="btnImprimerFacture" data-idPanier=' +
      Panier.idPagnet +
      " id=btnImprimerFacture" +
      Panier.idPagnet +
      ' class="btn btn-success btn_Termine_Panier btn_Termine_Panier' +
      Panier.idPagnet +
      ' terminer" data-toggle="modal" ><span class="glyphicon glyphicon-ok"></span>' +
      '<img src="images/loading-gif3.gif" class="img-load-terminer" style="height: 30px;width: 30px;display:none;" alt="GIF" srcset=""></button>' +
      '<button tabindex="1" type="button" class="btn btn-danger annuler" data-toggle="modal" data-target=#msg_ann_pagnet' +
      Panier.idPagnet +
      ">" +
      '<span class="glyphicon glyphicon-remove"></span>' +
      "</button>" +
      "</form>" +
      //<!--*******************************Fin Terminer Pagnet****************************************-->

      //<!--*******************************Debut Annuler Pagnet****************************************-->

      '<div class="modal fade" id="msg_ann_pagnet' +
      Panier.idPagnet +
      '" role="dialog">' +
      '<div class="modal-dialog">' +
      //<!-- Modal content-->

      '<div class="modal-content">' +
      '<div class="modal-header panel-primary">' +
      '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
      '<h4 class="modal-title">Confirmation</h4>' +
      "</div>" +
      '<form class="form-inline noImpr"  id="factForm" method="post"  >' +
      '<div class="modal-body">' +
      "<p>Voulez-vous annuler le panier numéro <b>" +
      Panier.idPagnet +
      "</b></p>" +
      '<input type="hidden" name="idPagnet" id="idPagnet"  value=' +
      Panier.idPagnet +
      ">" +
      "</div>" +
      '<div class="modal-footer">' +
      '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
      '<button type="button" name="btnAnnulerPagnet" data-idPanier=' +
      Panier.idPagnet +
      ' class="btn btn-success btnAnnulerPagnet">Confirmer<img src="images/loading-gif3.gif" class="img-load-annulerPanier" style="height: 30px;width: 30px; display:none;" alt="GIF" srcset=""></button>' +
      "</div>" +
      "</form>" +
      "</div>" +
      "</div>" +
      "</div>" +
      //<!--*******************************Fin Annuler Pagnet****************************************-->

      "</div>" +
      "</div>" +
      "<table  id=tablePanier" +
      Panier.idPagnet +
      ' class="tabPanier table"  width="100%" >' +
      "<thead>" +
      "<tr>" +
      "<th>Référence</th>" +
      "<th>Quantité</th>" +
      "<th>Unité Vente</th>" +
      //<!-- <th class="hidden-sm hidden-xs">Unitévente</th>'+ -->

      "<th>Prix Unité Vente</th>" +
      "</tr>" +
      "</thead>" +
      "<tbody>" +
      "</tbody>" +
      "</table>" +
      "</div>" +
      "</div>" +
      "</div>";

    $("#accordion").prepend(p);

    compte_Panier(Panier);

    $("#panier_" + Panier.idPagnet).focus();
  }
}

function addLigne(idPanier) {
  var designation = $(
    "#ajouterProdFormB" + idPanier + " .typeahead li.active"
  ).text();

  //$(".btn_Termine_Panier").attr("disabled","disabled");

  const design = JSON.parse(localStorage.getItem("Designation"));

  if (design != null) {
    for (var i = 0; i < design.length; i++) {
      if (design[i].designation == designation) {
        var lignes = getLigne();
        //$(".btn_Termine_Panier").removeAttr("disabled");
        var searchLigne = lignes.find(
          (l) => l.designation == designation && l.idPagnet == idPanier
        );
        if (searchLigne != undefined) {
          searchLigne.quantite = parseInt(searchLigne.quantite) + 1;
          $("#ligneQuantite" + idPanier + "" + searchLigne.numligne).val(
            searchLigne.quantite
          );
          saveLigne(lignes);
        } else {
          var ligne = new Object();
          ligne.numligne = lignes.length;
          ligne.idPagnet = idPanier;
          ligne.idDesignation = design[i].idDesignation;
          ligne.designation = design[i].designation;
          ligne.idStock = 0;
          ligne.unitevente = "Article";
          ligne.prixunitevente = design[i].prix;
          ligne.quantite = 1;
          ligne.prixtotal = ligne.quantite * design[i].prix;
          ligne.classe = design[i].classe;
          ligne.synchronise = 0;
          ligne.prixtotalTvaP = 0;
          ligne.prixtotalTvaR = 0;

          if (
            design[i].uniteStock != "Article" &&
            design[i].uniteStock != "article"
          ) {
            var lignePanier =
              "<tr>" +
              "<td>" +
              designation +
              "</td>" +
              "<td><input type='number' min='1' oninput='this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null' class='form-control filedReadonly" +
              idPanier +
              "' id='ligneQuantite" +
              idPanier +
              "" +
              ligne.numligne +
              "' onkeyup='modif_Quantite(this.value," +
              ligne.numligne +
              " )' value='1' style='width: 30%' type='number' ></input></td>" +
              "<input type='hidden' id='nbreArticleUniteStock' value='" +
              design[i].nbreArticleUniteStock +
              "'/>" +
              "<td>" +
              "<select class='form-control filedReadonly" +
              idPanier +
              "' id='uniteVente" +
              idPanier +
              "" +
              ligne.numligne +
              "'  onchange='modif_UniteStock(this.value," +
              ligne.numligne +
              "," +
              design[i].nbreArticleUniteStock +
              ")' >" +
              "<option id='default" +
              design[i].uniteStock +
              "' value='Article<>" +
              design[i].prix +
              "<>" +
              idPanier +
              "'>Article</option>" +
              "<option value='" +
              design[i].uniteStock +
              "<>" +
              design[i].prixuniteStock +
              "<>" +
              idPanier +
              "'>" +
              design[i].uniteStock +
              "</option>" +
              "</select>" +
              "</td>" +
              "<td><input type='number' min='0' oninput='this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null' class='form-control' id='prixUniteStock" +
              idPanier +
              "" +
              ligne.numligne +
              "' onkeyup='modif_Prix(this.value," +
              ligne.numligne +
              ")' value='" +
              design[i].prix +
              "' style='width: 70%' type='number'></input></td>" +
              "<td>" +
              "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit" +
              idPanier +
              "' data-toggle='modal' data-target=#msg_rtrnAvant_ligne" +
              lignes.length +
              ">" +
              "<span class='glyphicon glyphicon-remove'></span>Retour" +
              "</button>" +
              "<div class='modal fade' id=msg_rtrnAvant_ligne" +
              lignes.length +
              " role='dialog'>" +
              "<div class='modal-dialog'>" +
              //<!-- Modal content-->

              "<div class='modal-content'>" +
              "<div class='modal-header panel-primary'>" +
              "<button type='button' class='close' data-dismiss='modal'>&times;</button>" +
              "<h4 class='modal-title'>Confirmation</h4>" +
              "</div>" +
              "<form class='form-inline noImpr' id='factForm' method='post'>" +
              "<div class='modal-body'>" +
              "<p>Voulez-vous annuler cette ligne du panier numéro <b>" +
              lignes.length +
              "</b></p>" +
              "<input type='hidden' name='idPagnet' value=" +
              100 +
              ">" +
              "</div>" +
              "<div class='modal-footer'>" +
              "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>" +
              "<button type='button' onclick='retour_Produit(" +
              ligne.numligne +
              "," +
              idPanier +
              ")' name='btnRetourAvant' data-numligne='2617' class='btn btn-success btnRetourAvant'>Confirmer</button>" +
              "</div>" +
              "</form>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</td>" +
              "</tr>";
            $("#tablePanier" + idPanier).prepend(lignePanier);

            lignes.push(ligne);
            saveLigne(lignes);
            //localStorage.setItem("nbreArticleUniteStock", design[i].uniteStock+'<>'+design[i].nbreArticleUniteStock+'<>'+idPanier+'<>'+ligne.numligne);
          } else {
            var lignePanier =
              "<tr>" +
              "<td>" +
              designation +
              "</td>" +
              "<td><input type='number' min='1' oninput='this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null' class='form-control filedReadonly" +
              idPanier +
              "' id='ligneQuantite" +
              idPanier +
              "" +
              ligne.numligne +
              "' onkeyup='modif_Quantite(this.value," +
              ligne.numligne +
              ")' value='1' style='width: 30%' type='number' ></input></td>" +
              "<input type='hidden' id='nbreArticleUniteStock' value='1'/>" +
              "<td>" +
              "<select class='form-control filedReadonly" +
              idPanier +
              "' id='uniteVente" +
              idPanier +
              "" +
              ligne.numligne +
              "' onchange='modif_UniteStock(this.value," +
              ligne.numligne +
              "," +
              design[i].nbreArticleUniteStock +
              ")' >" +
              "<option value='Article" +
              design[i].designation +
              "<>" +
              idPanier +
              "'>Article</option>" +
              "</select>" +
              "</td>" +
              "<td><input type='number' min='0' oninput='this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null' disabled='true' class='form-control' id='prixUniteStock" +
              idPanier +
              "" +
              ligne.numligne +
              "' onkeyup='modif_Prix(this.value," +
              ligne.numligne +
              ")' value='" +
              design[i].prixuniteStock +
              "' style='width: 70%' type='number'></input></td>" +
              "<td>" +
              "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit" +
              idPanier +
              "' data-toggle='modal' data-target=#msg_rtrnAvant_ligne" +
              lignes.length +
              ">" +
              "<span class='glyphicon glyphicon-remove'></span>Retour" +
              "</button>" +
              "<div class='modal fade' id=msg_rtrnAvant_ligne" +
              lignes.length +
              " role='dialog'>" +
              "<div class='modal-dialog'>" +
              //<!-- Modal content-->

              "<div class='modal-content'>" +
              "<div class='modal-header panel-primary'>" +
              "<button type='button' class='close' data-dismiss='modal'>&times;</button>" +
              "<h4 class='modal-title'>Confirmation</h4>" +
              "</div>" +
              "<form class='form-inline noImpr' id='factForm' method='post'>" +
              "<div class='modal-body'>" +
              "<p>Voulez-vous annuler cette ligne du panier numéro <b>" +
              lignes.length +
              "</b></p>" +
              "<input type='hidden' name='idPagnet' value=" +
              100 +
              ">" +
              "</div>" +
              "<div class='modal-footer'>" +
              "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>" +
              "<button type='button' onclick='retour_Produit(" +
              ligne.numligne +
              "," +
              idPanier +
              ")' name='btnRetourAvant' data-numligne='" +
              ligne.numligne +
              "' class='btn btn-success btnRetourAvant'>Confirmer</button>" +
              "</div>" +
              "</form>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</td>" +
              "</tr>";
            $("#tablePanier" + idPanier).prepend(lignePanier);

            lignes.push(ligne);
            saveLigne(lignes);
            //localStorage.setItem("nbreArticleUniteStock", 'Artcle'+'<>'+1+'<>'+idPanier+'<>'+ligne.numligne);
          }
        }
        //localStorage.setItem("ligne", JSON.stringify(lignes));
      }
    }

    //location.reload();
  }

  $("#val_remise1" + idPanier).text();
  $("#somme_Total" + idPanier).text(somme_A_payer(idPanier));
  $("#somme_Apayer" + idPanier).text(somme_A_payer(idPanier));

  $("#btnImprimerFacture" + idPanier).removeAttr("disabled");

  $(".codeBarreLigneVt").val("");
  $("#panier_" + idPanier).focus();
}

function addLigneKeyup(idPanier, codebarre) {
  const design = JSON.parse(localStorage.getItem("Designation"));

  if (design != null) {
    for (var i = 0; i < design.length; i++) {
      if (parseInt(design[i].codeBarreDesignation) == codebarre) {
        var searchDesignation = design.find(
          (d) => d.codeBarreDesignation == codebarre
        );
        var lignes = getLigne();
        var searchLigne = lignes.find(
          (l) =>
            l.designation == searchDesignation.designation &&
            l.idPagnet == idPanier
        );
        if (searchLigne != undefined) {
          //alert(searchDesignation.designation)

          searchLigne.quantite = parseInt(searchLigne.quantite) + 1;
          //alert(searchLigne.quantite)
          $("#ligneQuantite" + idPanier + "" + searchLigne.numligne).val(
            searchLigne.quantite
          );
          saveLigne(lignes);
        } else {
          var ligne = new Object();
          ligne.numligne = lignes.length;
          ligne.idPagnet = idPanier;
          ligne.idDesignation = design[i].idDesignation;
          ligne.designation = design[i].designation;
          ligne.idStock = 0;
          ligne.unitevente = "Article";
          ligne.prixunitevente = design[i].prix;
          ligne.quantite = 1;
          ligne.prixtotal = ligne.quantite * design[i].prix;
          ligne.classe = design[i].classe;
          ligne.synchronise = 0;
          ligne.prixtotalTvaP = 0;
          ligne.prixtotalTvaR = 0;

          if (
            design[i].uniteStock != "Article" &&
            design[i].uniteStock != "article"
          ) {
            var lignePanier =
              "<tr>" +
              "<td>" +
              searchDesignation.designation +
              "</td>" +
              "<td><input class='form-control filedReadonly" +
              idPanier +
              "' id='ligneQuantite" +
              idPanier +
              "" +
              ligne.numligne +
              "' onkeyup='modif_Quantite(this.value," +
              ligne.numligne +
              " )' value='1' style='width: 30%' type='number' ></input></td>" +
              "<input type='hidden' id='nbreArticleUniteStock' value='" +
              design[i].nbreArticleUniteStock +
              "'/>" +
              "<td>" +
              "<select class='form-control filedReadonly" +
              idPanier +
              "' id='uniteVente" +
              idPanier +
              "" +
              ligne.numligne +
              "'  onchange='modif_UniteStock(this.value," +
              ligne.numligne +
              "," +
              design[i].nbreArticleUniteStock +
              ")' >" +
              "<option id='default" +
              design[i].uniteStock +
              "' value='Article<>" +
              design[i].prix +
              "<>" +
              idPanier +
              "'>Article</option>" +
              "<option value='" +
              design[i].uniteStock +
              "<>" +
              design[i].prixuniteStock +
              "<>" +
              idPanier +
              "'>" +
              design[i].uniteStock +
              "</option>" +
              "</select>" +
              "</td>" +
              "<td><input class='form-control' id='prixUniteStock" +
              idPanier +
              "" +
              ligne.numligne +
              "' onkeyup='modif_Prix(this.value," +
              ligne.numligne +
              ")' value='" +
              design[i].prix +
              "' style='width: 70%' type='number'></input></td>" +
              "<td>" +
              "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit" +
              idPanier +
              "' data-toggle='modal' data-target=#msg_rtrnAvant_ligne" +
              lignes.length +
              ">" +
              "<span class='glyphicon glyphicon-remove'></span>Retour" +
              "</button>" +
              "<div class='modal fade' id=msg_rtrnAvant_ligne" +
              lignes.length +
              " role='dialog'>" +
              "<div class='modal-dialog'>" +
              //<!-- Modal content-->

              "<div class='modal-content'>" +
              "<div class='modal-header panel-primary'>" +
              "<button type='button' class='close' data-dismiss='modal'>&times;</button>" +
              "<h4 class='modal-title'>Confirmation</h4>" +
              "</div>" +
              "<form class='form-inline noImpr' id='factForm' method='post'>" +
              "<div class='modal-body'>" +
              "<p>Voulez-vous annuler cette ligne du panier numéro <b>" +
              lignes.length +
              "</b></p>" +
              "<input type='hidden' name='idPagnet' value=" +
              100 +
              ">" +
              "</div>" +
              "<div class='modal-footer'>" +
              "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>" +
              "<button type='button' onclick='retour_Produit(" +
              ligne.numligne +
              "," +
              idPanier +
              ")' name='btnRetourAvant' data-numligne='2617' class='btn btn-success btnRetourAvant'>Confirmer</button>" +
              "</div>" +
              "</form>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</td>" +
              "</tr>";
            $("#tablePanier" + idPanier).prepend(lignePanier);

            lignes.push(ligne);
            saveLigne(lignes);
            //localStorage.setItem("nbreArticleUniteStock", design[i].uniteStock+'<>'+design[i].nbreArticleUniteStock+'<>'+idPanier+'<>'+ligne.numligne);
          } else {
            var lignePanier =
              "<tr>" +
              "<td>" +
              searchDesignation.designation +
              "</td>" +
              "<td><input class='form-control filedReadonly" +
              idPanier +
              "' id='ligneQuantite" +
              idPanier +
              "" +
              ligne.numligne +
              "' onkeyup='modif_Quantite(this.value," +
              ligne.numligne +
              ")' value='1' style='width: 30%' type='number' ></input></td>" +
              "<input type='hidden' id='nbreArticleUniteStock' value='1'/>" +
              "<td>" +
              "<select class='form-control filedReadonly" +
              idPanier +
              "' id='uniteVente" +
              idPanier +
              "" +
              ligne.numligne +
              "' onchange='modif_UniteStock(this.value," +
              ligne.numligne +
              "," +
              design[i].nbreArticleUniteStock +
              ")' >" +
              "<option value='Article" +
              design[i].designation +
              "<>" +
              idPanier +
              "'>Article</option>" +
              "</select>" +
              "</td>" +
              "<td><input disabled='true' class='form-control' id='prixUniteStock" +
              idPanier +
              "" +
              ligne.numligne +
              "' onkeyup='modif_Prix(this.value," +
              ligne.numligne +
              ")' value='" +
              design[i].prixuniteStock +
              "' style='width: 70%' type='number'></input></td>" +
              "<td>" +
              "<button type='submit' 	 class='btn btn-warning pull-right btn_Retourner_Produit btn_Retourner_Produit" +
              idPanier +
              "' data-toggle='modal' data-target=#msg_rtrnAvant_ligne" +
              lignes.length +
              ">" +
              "<span class='glyphicon glyphicon-remove'></span>Retour" +
              "</button>" +
              "<div class='modal fade' id=msg_rtrnAvant_ligne" +
              lignes.length +
              " role='dialog'>" +
              "<div class='modal-dialog'>" +
              //<!-- Modal content-->

              "<div class='modal-content'>" +
              "<div class='modal-header panel-primary'>" +
              "<button type='button' class='close' data-dismiss='modal'>&times;</button>" +
              "<h4 class='modal-title'>Confirmation</h4>" +
              "</div>" +
              "<form class='form-inline noImpr' id='factForm' method='post'>" +
              "<div class='modal-body'>" +
              "<p>Voulez-vous annuler cette ligne du panier numéro <b>" +
              lignes.length +
              "</b></p>" +
              "<input type='hidden' name='idPagnet' value=" +
              100 +
              ">" +
              "</div>" +
              "<div class='modal-footer'>" +
              "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>" +
              "<button type='button' onclick='retour_Produit(" +
              ligne.numligne +
              "," +
              idPanier +
              ")' name='btnRetourAvant' data-numligne='" +
              ligne.numligne +
              "' class='btn btn-success btnRetourAvant'>Confirmer</button>" +
              "</div>" +
              "</form>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</td>" +
              "</tr>";
            $("#tablePanier" + idPanier).prepend(lignePanier);

            lignes.push(ligne);
            saveLigne(lignes);
            //localStorage.setItem("nbreArticleUniteStock", 'Artcle'+'<>'+1+'<>'+idPanier+'<>'+ligne.numligne);
          }
        }
        //localStorage.setItem("ligne", JSON.stringify(lignes));
      }
    }
  }

  $("#val_remise1" + idPanier).text();
  $("#somme_Total" + idPanier).text(somme_A_payer(idPanier));
  $("#somme_Apayer" + idPanier).text(somme_A_payer(idPanier));
  $("#btnImprimerFacture" + idPanier).removeAttr("disabled");

  $(".codeBarreLigneVt").html("§");
  $("#panier_" + idPanier).focus();
}

function addPanierV() {
  lespaniers = getPanier();
  users = JSON.parse(localStorage.getItem("Boutique"));
  //comptes=JSON.parse(localStorage.getItem("Compte"));
  date = new Date();
  //datejour = date.toLocaleDateString("es-CL");
  datejour = getDateJour();
  //localStorage.setItem("datejour",datejour)

  heure = date.toLocaleTimeString("fr");

  tabDate = datejour.split("-");
  tabHeure = heure.split(":");

  //datehier=tabDate[0]-1+'-'+tabDate[1]+'-'+tabDate[2]

  //alert(date.getDate()-1)

  // var i=1;
  // //localStorage.setItem("IdPanier",pagnets.length)
  // if(lesPaniers!=null && users!=null){
  //     for(var j=0; j< lesPaniers.length;j++){
  //         if(lesPaniers[j].verrouiller==2){
  //             i=i+1;
  //         }
  //     }
  // }

  //if(i<=1  && users!=null){
  //alert(lesPaniers.length)
  var pagnets = getPanier();
  var comptes = getCompte();
  //panierNV=pagnets.filter(p =>p.verrouiller==0)
  localStorage.setItem("IdPanier", pagnets.length);
  idClient = localStorage.getItem("idClient");

  description = document.getElementById("typeVersement").value;
  montant = document.getElementById("montantV").value;
  datev = document.getElementById("date").value;
  comptev = document.getElementById("compteV").value;

  //alert(montant+' '+datev)

  Panier = new Object();
  Panier.idPagnet = pagnets.length;

  Panier.identifiantP =
    tabDate[0] +
    "" +
    tabDate[1] +
    "" +
    tabDate[2] +
    "" +
    tabHeure[0] +
    "" +
    tabHeure[1] +
    "" +
    tabHeure[2] +
    "" +
    sessionStorage.iduserSession;
  Panier.datepagej = datev;
  Panier.type = 40;
  Panier.heurePagnet = heure;
  Panier.apayerPagnet = 0;
  Panier.totalp = montant;
  Panier.verrouiller = 2;
  Panier.offline = 1;
  Panier.idClient = idClient;
  comptev = document.getElementById("compteV").value;
  if (comptev == "Caisse") {
    compte = comptes.find(
      (c) =>
        c.nomCompte == "Caisse" ||
        c.nomCompte == "caisse" ||
        c.nomCompte == "CAISSE"
    );
    Panier.idCompte = compte.idCompte;
  }
  if (comptev == "Wave") {
    compte = comptes.find(
      (c) =>
        c.nomCompte == "Wave" || c.nomCompte == "wave" || c.nomCompte == "WAVE"
    );
    Panier.idCompte = compte.idCompte;
  }
  if (comptev == "Orange money") {
    compte = comptes.find(
      (c) =>
        c.nomCompte == "Orange money" ||
        c.nomCompte == "Orange Money" ||
        c.nomCompte == "ORANGE MONEY"
    );
    Panier.idCompte = compte.idCompte;
  }

  Panier.image = null;
  Panier.synchronise = 0;
  Panier.iduser = sessionStorage.iduserSession;

  pagnets.push(Panier);

  savePanier(pagnets);

  addLigneV(Panier.idPagnet);

  //}
}

function addLigneV(idPanier) {
  description = document.getElementById("typeVersement").value;
  montant = document.getElementById("montantV").value;
  dateV = document.getElementById("date").value;
  compte = document.getElementById("compteV").value;
  //alert(compte)
  var lignes = getLigne();

  var ligne = new Object();
  ligne.numligne = lignes.length;
  ligne.idPagnet = idPanier;
  ligne.prixunitevente = montant;
  ligne.quantite = 1;
  ligne.designation = dateV;
  ligne.unitevente = compte;

  $("#tablePanierV" + idPanier).prepend(ligne);

  lignes.push(ligne);

  saveLigne(lignes);
}

function somme_A_payer(idPanier) {
  const ligne = getLigne();
  const panier = getPanier();
  var sommeT = 0;
  if (ligne != null) {
    var idP = panier.find((p) => p.idPagnet == idPanier);
    if (idP != undefined) {
      for (var i = ligne.length - 1; 0 <= i; i--) {
        //var tab=[];
        if (idP.idPagnet == ligne[i].idPagnet) {
          sommeT = sommeT + ligne[i].quantite * ligne[i].prixunitevente;
        }
      }
    }
  }
  $("#somme_Total" + idPanier).text(sommeT);
  $("#somme_Apayer" + idPanier).text(sommeT);
  return sommeT;
}

function totalVente() {
  date = new Date();
  //datejour = date.toLocaleDateString("es-CL");
  datejour = getDateJour();
  //heure=date.toLocaleTimeString("fr");
  const panier = getPanier();

  if (panier != null) {
    var sommeV = 0;
    for (var i = 0; i < panier.length; i++) {
      if (panier[i].datepagej == datejour) {
        sommeV = sommeV + parseInt(panier[i].totalp);
      }
    }
    return sommeV;
  }
}
/** Fin fonction Ajout */

/**Debut modifier la quantite dans la ligne d'un Pagnet**/

function modif_Quantite(quantite, ligne) {
  var lignes = getLigne();
  var searchLigne = lignes.find((l) => l.numligne == ligne);
  if (searchLigne != undefined) {
    searchLigne.quantite = quantite;
    searchLigne.prixtotal = searchLigne.prixunitevente * quantite;
    //alert("Total "+searchLigne.prixtotal)
    saveLigne(lignes);
  }

  $("#somme_Total" + searchLigne.idPagnet).text(
    somme_A_payer(searchLigne.idPagnet)
  );

  $("#somme_Apayer" + searchLigne.idPagnet).text(
    somme_A_payer(searchLigne.idPagnet)
  );

  return quantite;
}

function modif_UniteStock(unitevente, numligne, nbreArticleUniteStk, idPanier) {
  tab = unitevente.split("<>");

  var uniteStock = tab[0];
  var prixVente = tab[1];

  var lignes = getLigne();
  if (uniteStock != "Article" && uniteStock != "article") {
    //alert('Caisse'+ nbreArticleUniteStk)
    var searchLigne = lignes.find(
      (l) => l.numligne == numligne && l.idPagnet == idPanier
    );
    searchLigne.unitevente = uniteStock;
    if (searchLigne != undefined) {
      searchLigne.prixunitevente = prixVente;
      searchLigne.prixtotal = searchLigne.prixunitevente * searchLigne.quantite;
      $(
        "#prixUniteStock" + searchLigne.idPagnet + "" + searchLigne.numligne
      ).val(searchLigne.prixunitevente);
      saveLigne(lignes);

      //localStorage.setItem('nbreArticleUniteStock', unitevente+'<>'+nbreArticleUniteStk+'<>'+idPanier+'<>'+numligne);

      //$("#prixUniteStock" + idPanier+''+searchLigne.designation).text(500);
    }
  } else {
    //alert('Article'+ nbreArticleUniteStk)
    var searchLigne = lignes.find(
      (l) => l.numligne == numligne && l.idPagnet == idPanier
    );
    searchLigne.unitevente = uniteStock;
    if (searchLigne != undefined) {
      searchLigne.prixunitevente = prixVente;
      searchLigne.prixtotal = searchLigne.prixunitevente * searchLigne.quantite;
      $(
        "#prixUniteStock" + searchLigne.idPagnet + "" + searchLigne.numligne
      ).val(searchLigne.prixunitevente);
      saveLigne(lignes);
      //localStorage.setItem('nbreArticleUniteStock', unitevente+'<>'+1+'<>'+idPanier+'<>'+numligne);
    }
  }
  //$("#panierClient").load("loadContainerClient.html");

  paniers = getPanier();

  var panier = paniers.find((p) => p.idPagnet == idPanier);
  //alert(somme_A_payer())
  if (panier != undefined) {
    panier.totalp = somme_A_payer(idPanier);
    panier.apayerPagnet = somme_A_payer(idPanier);
    //
    savePanier(paniers);
    $("#somme_Total" + idPanier).text(somme_A_payer(idPanier));
    $("#somme_Apayer" + idPanier).text(somme_A_payer(idPanier));
    //location.reload("container.html")
    //$("#panelPanier"+paniers[paniers.length]).reload();
    //$("#panelPanier"+idPanier).load();
  }

  $("#panier_" + idPanier).focus();
}

function modif_UniteStock(unitevente, numligne, nbreArticleUniteStk) {
  tab = unitevente.split("<>");

  var uniteStock = tab[0];
  var prixVente = tab[1];

  var lignes = getLigne();
  if (uniteStock != "Article" && uniteStock != "article") {
    var searchLigne = lignes.find((l) => l.numligne == numligne);
    searchLigne.unitevente = uniteStock;
    if (searchLigne != undefined) {
      searchLigne.prixunitevente = prixVente;
      searchLigne.prixtotal = searchLigne.prixunitevente * searchLigne.quantite;
      $(
        "#prixUniteStock" + searchLigne.idPagnet + "" + searchLigne.numligne
      ).val(searchLigne.prixunitevente);
      saveLigne(lignes);
    }
  } else {
    //alert('Article'+ nbreArticleUniteStk)
    var searchLigne = lignes.find((l) => l.numligne == numligne);
    searchLigne.unitevente = uniteStock;
    if (searchLigne != undefined) {
      searchLigne.prixunitevente = prixVente;
      searchLigne.prixtotal = searchLigne.prixunitevente * searchLigne.quantite;
      $(
        "#prixUniteStock" + searchLigne.idPagnet + "" + searchLigne.numligne
      ).val(searchLigne.prixunitevente);
      saveLigne(lignes);
      //localStorage.setItem('nbreArticleUniteStock', unitevente+'<>'+1+'<>'+idPanier+'<>'+numligne);
    }
  }
  //$("#panierClient").load("loadContainerClient.html");

  paniers = getPanier();

  var panier = paniers.find((p) => p.idPagnet == searchLigne.idPagnet);
  //alert(somme_A_payer())
  if (panier != undefined) {
    panier.totalp = somme_A_payer(searchLigne.idPagnet);
    panier.apayerPagnet = somme_A_payer(searchLigne.idPagnet);
    //
    savePanier(paniers);
    $("#somme_Total" + searchLigne.idPagnet).text(
      somme_A_payer(searchLigne.idPagnet)
    );
    $("#somme_Apayer" + searchLigne.idPagnet).text(
      somme_A_payer(searchLigne.idPagnet)
    );
  }

  $("#panier_" + idPanier).focus();
}

function modif_Prix(prix, ligne) {
  var lignes = getLigne();
  var searchLigne = lignes.find((l) => l.numligne == ligne);

  if (ligne != undefined) {
    searchLigne.prixunitevente = prix;
    searchLigne.prixtotal = modif_Quantite(searchLigne.quantite, ligne) * prix;
    saveLigne(lignes);
  }

  // $("#somme_Total" + pagnet).text(prix);

  // $("#somme_Apayer" + pagnet).text(prix - $("#val_remise" + pagnet).val());

  $("#somme_Total" + searchLigne.idPagnet).text(
    somme_A_payer(searchLigne.idPagnet)
  );

  $("#somme_Apayer" + searchLigne.idPagnet).text(
    somme_A_payer(searchLigne.idPagnet) -
      $("#val_remise1" + searchLigne.idPagnet).val()
  );

  return prix;
}

function remise_Panier(prix, idPanier) {
  var total = somme_A_payer(idPanier);
  //alert('ok '+total)

  var remise = $("#val_remise1" + idPanier).val();

  if (remise > total) {
    v = $("#val_remise1" + idPanier).val();

    $("#val_remise1" + idPanier).val(v.substring(0, v.length - 1));
    v1 = v.substring(0, v.length - 1);
    //alert(v1)

    $("#somme_Apayer" + idPanier).text(somme_A_payer(idPanier) - v1);

    //$('#msg_info_avance').modal('show');
  } else {
    $("#somme_Apayer" + idPanier).text(somme_A_payer(idPanier) - prix);
  }
}

function retour_Produit(numligne, idPanier) {
  //alert(numligne)
  lignes = getLigne();
  ligne = lignes.filter((l) => l.numligne != numligne);
  saveLigne(ligne);
}

function imprimerTicket(id) {
  //document.getElementById("ticket"+id).submit();
  localStorage.setItem("idPanierT", id);
  window.open("/JCaisse/offline/ticketCaisse.html", "_blank");
}

function imprimerFacture(id) {
  nomclient = $("#nomclient" + id).val();
  adresseclient = $("#adresseclient" + id).val();
  telephone = $("#telephone" + id).val();

  localStorage.setItem("idPanierF", id);
  localStorage.setItem("nomclient", nomclient);
  localStorage.setItem("adresseclient", adresseclient);
  localStorage.setItem("telephone", telephone);

  window.open("/JCaisse/offline/factureClient.html");
}

// function modif_Versement(versement,id) {
//     //alert(versement+' '+id)

//     //localStorage.setItem("versement",versement)
//     // paniers=getPanier()
//     // panier=paniers.find(p=>p.idPagnet==id)
//     // if(panier!=undefined){
//     //     panier.versement=versement
//     //     alert(panier.versement)
//     //     savePanier(paniers)
//     // }
//     return versement

// }

// function modifier_VersementB(idPanier) {

//     localStorage.setItem("versement",versement)

// }

function compte_Panier(panier) {
  if (panier.type == 30) {
    document.getElementById("compteCaisse" + panier.idPagnet).style.fontWeight =
      "bold";
    document.getElementById("val_remise1" + panier.idPagnet).style.fontWeight =
      "bold";
    document.getElementById("clientInput" + panier.idPagnet).style.fontWeight =
      "bold";
    document.getElementById("avanceInput" + panier.idPagnet).style.fontWeight =
      "bold";
    document.getElementById("compteBon" + panier.idPagnet).style.fontWeight =
      "bold";

    lescomptes = JSON.parse(localStorage.getItem("Compte"));
    compteCaisse = lescomptes.filter((cpt) => cpt.nomCompte != "Bon");

    compteBon = lescomptes.filter((cpt) => cpt.nomCompte != "Bon");
    compteCpt = lescomptes.filter(
      (cpt) => cpt.nomCompte != "Bon" && cpt.nomCompte != "Caisse"
    );

    var select = $("#compteCaisse" + panier.idPagnet);
    var selectBon = $("#compteBon" + panier.idPagnet);

    //Create optGroup
    var optGroup1 = $("<option/>", {
      //label: 0
    });
    $("<option>Bon</option>", {}).appendTo(optGroup1);
    optGroup1.appendTo(select);

    for (var i = 0; i < compteCaisse.length; i++) {
      //Create option and append to optGroup created above
      //alert(comptes[i].nomCompte)
      var optGroup = $("<option/>", {
        label: compteCaisse[i].nomCompte,
      });

      // var optGroup = $('<option/>', {
      //     label: compteCaisse[i].nomCompte
      // })

      $("<option>", {
        value: compteCaisse[i].idCompte,
        text: compteCaisse[i].nomCompte,
      }).appendTo(optGroup);

      optGroup.appendTo(select);
    }

    //var selectBon1 = $("select#compteBon1"+idPanier);

    //Create optGroup
    var optGroup2 = $("<option/>", {
      //label: 0
    });
    $("<option>Caisse</option>", {}).appendTo(optGroup2);
    optGroup2.appendTo(selectBon);

    for (var i = 0; i < compteCpt.length; i++) {
      //Create option and append to optGroup created above
      //alert(comptes[i].nomCompte)
      var optGroup2 = $("<option/>", {
        label: compteCpt[i].nomCompte,
      });

      $("<option>", {
        value: compteCpt[i].idCompte,
        text: compteCpt[i].nomCompte,
      }).appendTo(optGroup2);
      optGroup2.appendTo(selectBon);
    }
  } else {
    document.getElementById("compteCaisse" + panier.idPagnet).style.fontWeight =
      "bold";
    document.getElementById("versement" + panier.idPagnet).style.fontWeight =
      "bold";
    document.getElementById("val_remise1" + panier.idPagnet).style.fontWeight =
      "bold";

    lescomptes = JSON.parse(localStorage.getItem("Compte"));

    compteCaisse = lescomptes.filter((cpt) => cpt.nomCompte != "Caisse");

    compteBon = lescomptes.filter((cpt) => cpt.nomCompte != "Bon");
    compteCpt = lescomptes.filter((cpt) => cpt.nomCompte != "Bon");

    var select = $("#compteCaisse" + panier.idPagnet);
    var selectBon = $("#compteBon" + panier.idPagnet);

    //Create optGroup
    var optGroup1 = $("<option/>", {
      //label: 0
    });
    $("<option>Caisse</option>", {}).appendTo(optGroup1);
    optGroup1.appendTo(select);

    for (var i = 0; i < compteCaisse.length; i++) {
      //Create option and append to optGroup created above
      //alert(comptes[i].nomCompte)
      var optGroup = $("<option/>", {
        label: compteCaisse[i].nomCompte,
      });

      // var optGroup = $('<option/>', {
      //     label: compteCaisse[i].nomCompte
      // })

      $("<option>", {
        value: compteCaisse[i].idCompte,
        text: compteCaisse[i].nomCompte,
      }).appendTo(optGroup);

      optGroup.appendTo(select);
    }

    //var selectBon1 = $("select#compteBon1"+idPanier);

    //Create optGroup
    var optGroup2 = $("<option/>", {
      //label: 0
    });
    $("<option>Caisse</option>", {}).appendTo(optGroup2);
    optGroup2.appendTo(selectBon);

    for (var i = 0; i < compteCpt.length; i++) {
      //Create option and append to optGroup created above
      //alert(comptes[i].nomCompte)
      var optGroup2 = $("<option/>", {
        label: compteCpt[i].nomCompte,
      });

      $("<option>", {
        value: compteCpt[i].idCompte,
        text: compteCpt[i].nomCompte,
      }).appendTo(optGroup2);
      optGroup2.appendTo(selectBon);
    }
  }
}

function modif_CompteCaisse(compte, idPanier) {
  $("#compteCaisse" + idPanier + " option").each(function () {
    // if it's selected, add it to the array above
    if (this.selected) {
      if (this.text == "Bon") {
        $("#compteCaisse" + idPanier).show();
        $("#compteBon" + idPanier).show();
        document.getElementById("compteBon" + idPanier).style.fontWeight =
          "bold";
        //document.getElementById("compteBon1"+idPanier).style.fontWeight = "bold";
        //document.getElementById("val_remise1"+idPanier).style.fontWeight = "bold";
        $("#clientInput" + idPanier).show();
        $("#avanceInput" + idPanier).show();
        $("#versement" + idPanier).hide();
        document.getElementById("clientInput" + idPanier).style.fontWeight =
          "bold";
        document.getElementById("avanceInput" + idPanier).style.fontWeight =
          "bold";
        lesComptes = getCompte();
        searchCompte = lesComptes.find((c) => c.nomCompte == this.text);
        if (searchCompte != undefined) {
          //localStorage.setItem('idCompte',searchCompte.idCompte);
          var paniers = getPanier();

          var searchPanier = paniers.find((p) => p.idPagnet == idPanier);

          if (searchPanier != undefined) {
            searchPanier.idCompte = searchCompte.idCompte;
            searchPanier.type = 30;
          }
          savePanier(paniers);
        }

        localStorage.setItem("typeVente", "simple");
        compte = lesComptes.find(
          (c) => c.nomCompte == "Caisse" || c.nomCompte == "caisse"
        );
        localStorage.setItem("idCompte2", compte.idCompte);
      }

      if (this.text == "Caisse") {
        $("#clientInput" + idPanier).hide();
        $("#avanceInput" + idPanier).hide();
        $("#compteBon" + idPanier).hide();
        $("#versement" + idPanier).show();

        localStorage.setItem("typeVente", "bon");

        lesComptes = getCompte();
        searchCompte = lesComptes.find((c) => c.nomCompte == this.text);
        if (searchCompte != undefined) {
          //localStorage.setItem('idCompte',searchCompte.idCompte);
          var paniers = getPanier();

          var searchPanier = paniers.find((p) => p.idPagnet == idPanier);

          if (searchPanier != undefined) {
            searchPanier.idCompte = searchCompte.idCompte;
            searchPanier.idClient = 0;
            searchPanier.type = 0;
          }
          savePanier(paniers);
        }
      }

      if (this.text != "Caisse" && this.text != "Bon") {
        //alert('ok')
        document.getElementById("versement" + idPanier).disabled = true;
        document.getElementById("versement" + idPanier).placeholder = "";
        $("#versement" + idPanier).show();
        $("#compteBon" + idPanier).hide();
        $("#clientInput" + idPanier).hide();
        $("#avanceInput" + idPanier).hide();

        lesComptes = getCompte();
        searchCompte = lesComptes.find((c) => c.nomCompte == this.text);
        if (searchCompte != undefined) {
          //localStorage.setItem('idCompte',searchCompte.idCompte);
          var paniers = getPanier();

          var searchPanier = paniers.find((p) => p.idPagnet == idPanier);

          if (searchPanier != undefined) {
            searchPanier.idCompte = searchCompte.idCompte;
            searchPanier.idClient = 0;
            searchPanier.type = 0;
          }
          savePanier(paniers);
        }
      }
    }
  });
}

function modif_CompteBon(compte, idPanier) {
  $("#compteBon" + idPanier + " option").each(function () {
    // if it's selected, add it to the array above
    if (this.selected) {
      lesComptes = getCompte();
      searchCompte = lesComptes.find((c) => c.nomCompte == this.text);
      if (searchCompte != undefined) {
        localStorage.setItem("idCompte2", searchCompte.idCompte);
      }
    }
  });
}

// function remise_Panier(prix,idPanier) {

//     $("#somme_Apayer" + idPanier).text(somme_A_payer(idPanier) - prix);

// }
function getIdPanier(idp) {
  idp = localStorage.getItem("IdPanier");
  return idp;
}

function envoiVersement(versement) {
  date = new Date();
  //datejour = date.toLocaleDateString("es-CL");
  datejour = getDateJour();
  if (versement.verrouiller == 2 && versement.synchronise == 0) {
    dateVersement = versement.datepagej;
    //type= versement.type;
    heureVersement = versement.heurePagnet;
    montant = versement.totalp;
    //apayerPagnet= versement.apayerPagnet;
    //verrouiller= versement.verrouiller;
    idClient = versement.idClient;
    idCompte = versement.idCompte;
    iduser = versement.iduser;
    synchronise = 1;
    idPanier = versement.idPagnet;
    identifiantV = versement.identifiantP;
    //var leslignes=getLigne();
    //lignes= leslignes.filter(l =>l.idPagnet==idPanier);

    $.ajax({
      url: "ajax/insertionVersementAjax.php",

      method: "POST",

      data: {
        dateVersement: dateVersement,
        heureVersement: heureVersement,
        montant: montant,
        idPanier: idPanier,
        identifiantV: identifiantV,
        idClient: idClient,
        idCompte: idCompte,
        iduser: iduser,
        synchronise: synchronise,
      },

      success: function (data) {
        //alert(data)
        result = data;
        if (result == 0) {
        } else {
          paniers = getPanier();
          panierE = paniers.find((p) => p.idPagnet == idPanier);

          if (panierE != undefined) {
            panierE.synchronise = 1;
            savePanier(paniers);

            $("#panierClient").load("loadContainerClient.html");
          }
        }
      },

      error: function () {
        alert("La requête ");
      },

      dataType: "text",
    });
  }
}

//localStorage.setItem('typeVente','bon');

//localStorage.setItem('idCompte',searchCompte.idCompte);
// var paniers=getPanier();

// var searchPanier=paniers.find(p =>p.idPagnet==idPanier);

// if(searchPanier != undefined){
//     searchPanier.idClient=0;
//     var compte=lesComptes.find(c =>c.nomCompte=='Caisse' || c.nomCompte=='caisse')
//     searchPanier.idCompte=compte.idCompte;
// }
// savePanier(paniers)

/**Fin modifier la quantite dans la ligne d'un Pagnet**/
