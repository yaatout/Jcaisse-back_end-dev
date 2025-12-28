$(document).ready(function() {

  $(".loading-gif").hide();
  $(".loading-gif-1").hide();

  dateJour=$('#inpt_Historique_dateJour').val();

/** Debut Details Operation Historique */

  $("#btn_details").on( "click", function (e){
    e.preventDefault();
 
    $(".loading-gif").show();
    $.ajax({
      url:"calculs/historique.php",
      method:"POST",
      data:{
          operation:'details',
          dateJour : dateJour
      },
      success: function (data) {
        $(".loading-gif").hide();
        $("#tableau_Bord").show();
        var details = JSON.parse(data);
           $("#montant_Approvisionnement").text(details[0]);
           $("#montant_Retrait").text(details[1]);
           $("#montant_Vente_Caisse").text(details[2]);
           $("#montant_Vente_Mobile").text(details[3]);
           $("#montant_Services").text(details[4]);
           $("#montant_Depenses").text(details[5]);
           $("#montant_Bon_Especes").text(details[6]);
           $("#montant_Versement_Client").text(details[7]);
           $("#montant_Versement_Fournisseur").text(details[8]);  
           $("#montant_Remise").text(details[9]);
           $("#montant_Total_Operations").text(details[10]); 
           $("#montant_Bon_Client").text(details[11]);
           $("#montant_Vente_Mutuelle").text(details[12]);
           $("#montant_Versement_Mutuelle").text(details[13]);
           $("#montant_Bon_Mutuelle").text(details[14]);
      },
      error: function() {
          console("La requête "); 
      },
      dataType:"text"
  });
    $("#collapse_details").collapse("show");
  });

  $("#btn_details_Approvisionnement").on( "click", function (e){
    e.preventDefault();
      $('#listeApprovisionnement').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeApprovisionnement.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'heurePagnet' },
            { data: 'designation' },
            { data: 'prixtotal' },
            { data: 'idPagnet' },
            { data: 'compte' },
            { data: 'personnel' }
        ],
        'columnDefs': [ 
            {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_Approvisionnement").modal(); 

  });

  $("#btn_details_Retrait").on( "click", function (e){
    e.preventDefault();
      $('#listeRetrait').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeRetrait.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'heurePagnet' },
          { data: 'designation' },
          { data: 'prixtotal' },
          { data: 'idPagnet' },
          { data: 'compte' },
          { data: 'personnel' }
        ],
        'columnDefs': [ 
            {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_Retrait").modal(); 

  });

  $("#btn_details_VenteCaisse").on( "click", function (e){
    e.preventDefault();
      $('#listeVenteCaisse').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeVenteCaisse.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'heurePagnet' },
            { data: 'designation' },
            { data: 'quantite' },
            { data: 'unitevente' },
            { data: 'prixunitevente' },
            { data: 'prixtotal' },
            { data: 'idPagnet' },
            { data: 'personnel' },
        ],
        'columnDefs': [ 
            {
            'targets': [6,7], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_VenteCaisse").modal(); 

  });

  $("#btn_details_VenteMobile").on( "click", function (e){
    e.preventDefault();
      $('#listeVenteMobile').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeVenteMobile.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'heurePagnet' },
            { data: 'designation' },
            { data: 'quantite' },
            { data: 'unitevente' },
            { data: 'prixunitevente' },
            { data: 'prixtotal' },
            { data: 'idPagnet' },
            { data: 'compte' },
            { data: 'personnel' },
        ],
        'columnDefs': [ 
            {
            'targets': [6,7,8], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_VenteMobile").modal(); 

  });

  $("#btn_details_Services").on( "click", function (e){
    e.preventDefault();
      $('#listeServices').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeServices.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'heurePagnet' },
          { data: 'designation' },
          { data: 'prixtotal' },
          { data: 'idPagnet' },
          { data: 'compte' },
          { data: 'personnel' }
        ],
        'columnDefs': [ 
            {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_Services").modal(); 

  });

  $("#btn_details_Depenses").on( "click", function (e){
    e.preventDefault();
      $('#listeDepenses').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeDepenses.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'heurePagnet' },
          { data: 'designation' },
          { data: 'prixtotal' },
          { data: 'idPagnet' },
          { data: 'compte' },
          { data: 'personnel' }
        ],
        'columnDefs': [ 
            {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_Depenses").modal(); 

  });

  $("#btn_details_BonEspeces").on( "click", function (e){
    e.preventDefault();
      $('#listeBonEspeces').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeBonEspeces.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'heurePagnet' },
          { data: 'designation' },
          { data: 'prixtotal' },
          { data: 'idPagnet' },
          { data: 'client' },
          { data: 'compte' },
          { data: 'personnel' }
        ],
        'columnDefs': [ 
            {
            'targets': [3,4,5,6], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_BonEspeces").modal(); 

  });

  $("#btn_details_VersementClient").on( "click", function (e){
    e.preventDefault();
      $('#listeVersementClient').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeVersementClient.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'heureVersement' },
          { data: 'paiement' },
          { data: 'montant' },
          { data: 'idVersement' },
          { data: 'client' },
          { data: 'compte' },
          { data: 'personnel' }
        ],
        'columnDefs': [ 
            {
            'targets': [3,4,5,6], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
      $("#details_VersementClient").modal(); 
  });

  $("#btn_details_VersementFournisseur").on( "click", function (e){
    e.preventDefault();
      $('#listeVersementFournisseur').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeVersementFournisseur.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'heureVersement' },
          { data: 'paiement' },
          { data: 'montant' },
          { data: 'idVersement' },
          { data: 'fournisseur' },
          { data: 'compte' },
          { data: 'personnel' }
        ],
        'columnDefs': [ 
            {
            'targets': [3,4,5,6], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
      $("#details_VersementFournisseur").modal(); 
  });

  $("#btn_details_Remise").on( "click", function (e){
    e.preventDefault();
      $('#listeRemise').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeRemise.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'heurePagnet' },
            { data: 'remise' },
            { data: 'idPagnet' },
            { data: 'compte' },
            { data: 'personnel' }
        ],
        'columnDefs': [ 
            {
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_Remise").modal(); 

  });

  $("#btn_details_TotalOperations").on( "click", function (e){
    e.preventDefault();
      $('#listeTotalCaisse').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeOperations_Caisse.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'personnel' },
            { data: 'approvisionnement' },
            { data: 'retrait' },
            { data: 'vente' },
            { data: 'depense' },
            { data: 'versementClient' },
            { data: 'versementFournisseur' },
            { data: 'bonEspeces' },
            { data: 'remise' },
            { data: 'total' }
        ],
        'columnDefs': [ 
            {
            'targets': [6,7], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_TotalOperations").modal(); 

  });

  $("#btn_details_BonClient").on( "click", function (e){
    e.preventDefault();
      $('#listeBonClient').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeBonClient.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'heurePagnet' },
            { data: 'designation' },
            { data: 'quantite' },
            { data: 'unitevente' },
            { data: 'prixunitevente' },
            { data: 'prixtotal' },
            { data: 'idPagnet' },
            { data: 'client' },
            { data: 'personnel' },
        ],
        'columnDefs': [ 
            {
            'targets': [6,7], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_BonClient").modal(); 

  });

  $("#listeMobileEvent").on( "click", function (e){
    e.preventDefault();
    $('#listeTotalMobile').DataTable({
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'datatables/historique_listeOperations_Mobile.php',
          'data':{
            'dateJour' : dateJour
          }
      },
      'dom': 'Blfrtip',
      "buttons": ['csv','print', 'excel', 'pdf'],
      "ordering": true,
      "order": [[0, 'desc']],
      'columns': [
          { data: 'personnel' },
          { data: 'approvisionnement' },
          { data: 'retrait' },
          { data: 'vente' },
          { data: 'depense' },
          { data: 'versementClient' },
          { data: 'versementFournisseur' },
          { data: 'bonEspeces' },
          { data: 'remise' },
          { data: 'total' }
      ],
      'columnDefs': [ 
          {
          'targets': [6,7], /* column index */
          'orderable': false, /* true or false */
          }
      ]
    }); 
  });

  $("#listeCompteEvent").on( "click", function (e){
    e.preventDefault();
    $('#listeTotalCompte').DataTable({
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'datatables/historique_listeOperations_Compte.php',
          'data':{
            'dateJour' : dateJour
          }
      },
      'dom': 'Blfrtip',
      "buttons": ['csv','print', 'excel', 'pdf'],
      "ordering": true,
      "order": [[0, 'desc']],
      'columns': [
          { data: 'compte' },
          { data: 'approvisionnement' },
          { data: 'retrait' },
          { data: 'vente' },
          { data: 'depense' },
          { data: 'versementClient' },
          { data: 'versementFournisseur' },
          { data: 'bonEspeces' },
          { data: 'remise' },
          { data: 'total' }
      ],
      'columnDefs': [ 
          {
          'targets': [6,7], /* column index */
          'orderable': false, /* true or false */
          }
      ]
    }); 
  });

  $("#btn_details_Benefices").on( "click", function (e){
    e.preventDefault();
 
    $("#details_Benefices").show();
    $(".loading-gif-1").show();
      $.ajax({
        url:"calculs/historique.php",
        method:"POST",
        data:{
            operation:'benefices',
            dateJour : dateJour
        },
        success: function (data) {
          $(".loading-gif-1").hide();
          var details = JSON.parse(data);
            $("#montant_Benefices_Vente").text(details[0]);
            $("#montant_Benefices_Client").text(details[1]);
            $("#montant_Benefices").text(details[2]);
        },
        error: function() {
            console("La requête "); 
        },
        dataType:"text"
    });

  });

  $("#btn_Retour_Benefices").on( "click", function (e){
    e.preventDefault();
 
    $("#details_Benefices").hide();

  });

  $("#btn_details_VenteMutuelle").on( "click", function (e){
    e.preventDefault();
      $('#listeVenteMutuelle').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeVenteMutuelle.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
            { data: 'heurePagnet' },
            { data: 'designation' },
            { data: 'quantite' },
            { data: 'forme' },
            { data: 'prixPublic' },
            { data: 'prixtotal' },
            { data: 'mutuelle' },
            { data: 'idMutuellePagnet' },
            { data: 'compte' },
            { data: 'personnel' },
        ],
        'columnDefs': [ 
            {
            'targets': [6,7], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
    $("#details_VenteMutuelle").modal(); 

  });

  $("#btn_details_BonMutuelle").on( "click", function (e){
    $("#details_BonMutuelle").modal();
    e.preventDefault();
      $('#listeBonMutuelle').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'datatables/historique_listeBonMutuelle.php',
            'data':{
              'dateJour' : dateJour
            }
        },
        'dom': 'Blfrtip',
        "buttons": ['csv','print', 'excel', 'pdf'],
        "ordering": true,
        "order": [[0, 'desc']],
        'columns': [
          { data: 'heurePagnet' },
          { data: 'designation' },
          { data: 'quantite' },
          { data: 'forme' },
          { data: 'prixPublic' },
          { data: 'prixtotal' },
          { data: 'mutuelle' },
          { data: 'idMutuellePagnet' },
          { data: 'compte' },
          { data: 'personnel' },
        ],
        'columnDefs': [ 
            {
            'targets': [6,7], /* column index */
            'orderable': false, /* true or false */
            }
        ]
      }); 
     

  });

/** Fin Details Operation Historique */


/** Debut Paniers Approvisionnement */
  $("#listePanierApprovisionnement").load("datatables/historique_PanierApprovisionnement.php",{"nbre_Entree":"","query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $(".loading-gif-2").hide();     
      }
  });

  $("#listePanierApprovisionnement").on( "change", "#slct_Nbre_Approvisionnement", function (e){
      e.preventDefault();
      nbre_Entree = $('#slct_Nbre_Approvisionnement').val() ;

      $("#listePanierApprovisionnement").load("datatables/historique_PanierApprovisionnement.php",{"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
        if(status == "success"){
            $('#slct_Nbre_Approvisionnement').val(nbre_Entree) ;     
        }
      });

  });

  $("#listePanierApprovisionnement").on( "click", ".pagination a", function (e){

      e.preventDefault();
      page = $(this).attr("data-page"); //get page number from link
      nbre_Entree = $('#slct_Nbre_Approvisionnement').val() ;

      $("#listePanierApprovisionnement").load("datatables/historique_PanierApprovisionnement.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
        if(status == "success"){
            $('#slct_Nbre_Approvisionnement').val(nbre_Entree) ;        
        }
      });

  });
/** Fin Paniers Approvisionnement */


/** Debut Paniers Retrait */
  $("#listeRetraitEvent").on( "click", function (e){
    e.preventDefault();
    var vide = $.trim($("#listePanierRetrait").text());
    if(vide==''){
      $(".loading-gif-2").show(); 
      $("#listePanierRetrait").load("datatables/historique_PanierRetrait.php",{"nbre_Entree":"","query":"","dateJour":dateJour},  function (response, status, xhr){
        if(status == "success"){
          $(".loading-gif-2").hide();       
        }
      });
    }
  });
    
  $("#listePanierRetrait").on( "change", "#slct_Nbre_Retrait", function (e){
    e.preventDefault();
    nbre_Entree = $('#slct_Nbre_Retrait').val() ;
    
    $("#listePanierRetrait").load("datatables/historique_PanierRetrait.php",{"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $('#slct_Nbre_Retrait').val(nbre_Entree) ;
      }
    });
    
  });
    
  $("#listePanierRetrait").on( "click", ".pagination a", function (e){
    
    e.preventDefault();
    page = $(this).attr("data-page"); //get page number from link
    nbre_Entree = $('#slct_Nbre_Retrait').val() ;
    
    $("#listePanierRetrait").load("datatables/historique_PanierRetrait.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $('#slct_Nbre_Retrait').val(nbre_Entree) ;        
      }
    });
    
  });
/** Fin Paniers Retrait */

/** Debut Paniers VenteCaisse */
  $("#listeVenteCaisseEvent").on( "click", function (e){
    e.preventDefault();
    var vide = $.trim($("#listePanierVenteCaisse").text());
    if(vide==''){
      $(".loading-gif-2").show(); 
      $("#listePanierVenteCaisse").load("datatables/historique_PanierVenteCaisse.php",{"nbre_Entree":"","query":"","dateJour":dateJour},  function (response, status, xhr){
          if(status == "success"){
            $(".loading-gif-2").hide();       
          }
      });
    }
  });
    
  $("#listePanierVenteCaisse").on( "change", "#slct_Nbre_VenteCaisse", function (e){
    e.preventDefault();
    nbre_Entree = $('#slct_Nbre_VenteCaisse').val() ;
    $(".loading-gif-2").show();

    $("#listePanierVenteCaisse").load("datatables/historique_PanierVenteCaisse.php",{"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $(".loading-gif-2").hide();
        $('#slct_Nbre_VenteCaisse').val(nbre_Entree) ;
      }
    });
    
  });
    
  $("#listePanierVenteCaisse").on( "click", ".pagination a", function (e){
    
    e.preventDefault();
    page = $(this).attr("data-page"); //get page number from link
    nbre_Entree = $('#slct_Nbre_VenteCaisse').val() ;
    $(".loading-gif-2").show();
    
    $("#listePanierVenteCaisse").load("datatables/historique_PanierVenteCaisse.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $(".loading-gif-2").hide();
        $('#slct_Nbre_VenteCaisse').val(nbre_Entree) ;        
      }
    });
    
  });
/** Fin Paniers VenteCaisse */

/** Debut Paniers VenteMobile */
  $("#listeVenteMobileEvent").on( "click", function (e){
    e.preventDefault();
    var vide = $.trim($("#listePanierVenteMobile").text());
    if(vide==''){
      $(".loading-gif-2").show(); 
      $("#listePanierVenteMobile").load("datatables/historique_PanierVenteMobile.php",{"nbre_Entree":"","query":"","dateJour":dateJour},  function (response, status, xhr){
          if(status == "success"){
            $(".loading-gif-2").hide();       
          }
      });
    }
  });
    
  $("#listePanierVenteMobile").on( "change", "#slct_Nbre_VenteMobile", function (e){
    e.preventDefault();
    nbre_Entree = $('#slct_Nbre_VenteMobile').val() ;
    $(".loading-gif-2").show();

    $("#listePanierVenteMobile").load("datatables/historique_PanierVenteMobile.php",{"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $(".loading-gif-2").hide();
        $('#slct_Nbre_VenteMobile').val(nbre_Entree) ;
      }
    });
    
  });
    
  $("#listePanierVenteMobile").on( "click", ".pagination a", function (e){
    
    e.preventDefault();
    page = $(this).attr("data-page"); //get page number from link
    nbre_Entree = $('#slct_Nbre_VenteMobile').val() ;
    $(".loading-gif-2").show();
    
    $("#listePanierVenteMobile").load("datatables/historique_PanierVenteMobile.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $(".loading-gif-2").hide();
        $('#slct_Nbre_VenteMobile').val(nbre_Entree) ;        
      }
    });
    
  });
/** Fin Paniers VenteMobile */

/** Debut Paniers Depenses */
  $("#listeDepensesEvent").on( "click", function (e){
    e.preventDefault();
    var vide = $.trim($("#listePanierDepenses").text());
    if(vide==''){
      $(".loading-gif-2").show(); 
      $("#listePanierDepenses").load("datatables/historique_PanierDepenses.php",{"nbre_Entree":"","query":"","dateJour":dateJour},  function (response, status, xhr){
        if(status == "success"){
          $(".loading-gif-2").hide();       
        }
      });
    }
  });
    
  $("#listePanierDepenses").on( "change", "#slct_Nbre_Depenses", function (e){
    e.preventDefault();
    nbre_Entree = $('#slct_Nbre_Depenses').val() ;
    
    $("#listePanierDepenses").load("datatables/historique_PanierDepenses.php",{"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $('#slct_Nbre_Depenses').val(nbre_Entree) ;
      }
    });
    
  });
    
  $("#listePanierDepenses").on( "click", ".pagination a", function (e){
    
    e.preventDefault();
    page = $(this).attr("data-page"); //get page number from link
    nbre_Entree = $('#slct_Nbre_Depenses').val() ;
    
    $("#listePanierDepenses").load("datatables/historique_PanierDepenses.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $('#slct_Nbre_Depenses').val(nbre_Entree) ;        
      }
    });
    
  });
/** Fin Paniers Depenses */

/** Debut Paniers VersementsClient */
  $("#listeVersementsClientEvent").on( "click", function (e){
    e.preventDefault();
    var vide = $.trim($("#listePanierVersementsClient").text());
    if(vide==''){
      $(".loading-gif-2").show(); 
      $("#listePanierVersementsClient").load("datatables/historique_PanierVersementsClient.php",{"nbre_Entree":"","query":"","dateJour":dateJour},  function (response, status, xhr){
        if(status == "success"){
          $(".loading-gif-2").hide();       
        }
      });
    }
  });

  $("#listePanierVersementsClient").on( "change", "#slct_Nbre_VersementsClient", function (e){
    e.preventDefault();
    nbre_Entree = $('#slct_Nbre_VersementsClient').val() ;
    
    $("#listePanierVersementsClient").load("datatables/historique_PanierVersementsClient.php",{"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $('#slct_Nbre_VersementsClient').val(nbre_Entree) ;
      }
    });
    
  });
    
  $("#listePanierVersementsClient").on( "click", ".pagination a", function (e){
    
    e.preventDefault();
    page = $(this).attr("data-page"); //get page number from link
    nbre_Entree = $('#slct_Nbre_VersementsClient').val() ;
    
    $("#listePanierVersementsClient").load("datatables/historique_PanierVersementsClient.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $('#slct_Nbre_VersementsClient').val(nbre_Entree) ;        
      }
    });
    
  });
/** Fin Paniers VersementsClient */

/** Debut Paniers VersementsFournisseur */
  $("#listeVersementsFournisseurEvent").on( "click", function (e){
    e.preventDefault();
    var vide = $.trim($("#listePanierVersementsFournisseur").text());
    if(vide==''){
      $(".loading-gif-2").show(); 
      $("#listePanierVersementsFournisseur").load("datatables/historique_PanierVersementsFournisseur.php",{"nbre_Entree":"","query":"","dateJour":dateJour},  function (response, status, xhr){
        if(status == "success"){
          $(".loading-gif-2").hide();       
        }
      });
    }
  });
    
  $("#listePanierVersementsFournisseur").on( "change", "#slct_Nbre_VersementsFournisseur", function (e){
    e.preventDefault();
    nbre_Entree = $('#slct_Nbre_VersementsFournisseur').val() ;
    
    $("#listePanierVersementsFournisseur").load("datatables/historique_PanierVersementsFournisseur.php",{"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $('#slct_Nbre_VersementsFournisseur').val(nbre_Entree) ;
      }
    });
    
  });
    
  $("#listePanierVersementsFournisseur").on( "click", ".pagination a", function (e){
    
    e.preventDefault();
    page = $(this).attr("data-page"); //get page number from link
    nbre_Entree = $('#slct_Nbre_VersementsFournisseur').val() ;
    
    $("#listePanierVersementsFournisseur").load("datatables/historique_PanierVersementsFournisseur.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $('#slct_Nbre_VersementsFournisseur').val(nbre_Entree) ;        
      }
    });
    
  });
/** Fin Paniers VersementsFournisseur */

/** Debut Paniers BonsClient */
  $("#listeBonsClientEvent").on( "click", function (e){
    e.preventDefault();
    var vide = $.trim($("#listePanierBonsClient").text());
    if(vide==''){
      $(".loading-gif-2").show(); 
      $("#listePanierBonsClient").load("datatables/historique_PanierBonsClient.php",{"nbre_Entree":"","query":"","dateJour":dateJour},  function (response, status, xhr){
          if(status == "success"){
            $(".loading-gif-2").hide();       
          }
      });
    }
  });
    
  $("#listePanierBonsClient").on( "change", "#slct_Nbre_BonsClient", function (e){
    e.preventDefault();
    nbre_Entree = $('#slct_Nbre_BonsClient').val() ;
    $(".loading-gif-2").show();

    $("#listePanierBonsClient").load("datatables/historique_PanierBonsClient.php",{"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $(".loading-gif-2").hide();
        $('#slct_Nbre_BonsClient').val(nbre_Entree) ;
      }
    });
    
  });
    
  $("#listePanierBonsClient").on( "click", ".pagination a", function (e){
    
    e.preventDefault();
    page = $(this).attr("data-page"); //get page number from link
    nbre_Entree = $('#slct_Nbre_BonsClient').val() ;
    $(".loading-gif-2").show();
    
    $("#listePanierBonsClient").load("datatables/historique_PanierBonsClient.php",{"page":page,"nbre_Entree":nbre_Entree,"query":"","dateJour":dateJour},  function (response, status, xhr){
      if(status == "success"){
        $(".loading-gif-2").hide();
        $('#slct_Nbre_BonsClient').val(nbre_Entree) ;        
      }
    });
    
  });
/** Fin Paniers BonsClient */

function details_Panier(idPagnet,table) {
  idTable=table+""+idPagnet;
  details=$("#"+idTable).find('tr').length;
  if(details==1){
      $.ajax({
          url:"calculs/historique.php",
          method:"POST",
          data:{
              operation:'lignes',
              idPagnet : idPagnet,
          },
          success: function (data) {
              var lignes = JSON.parse(data);
              lignes_exist=$("#"+idTable).find('tr').length;
              for(var l = 1; l<lignes_exist; l++){
                  $("#"+table+""+idPagnet).deleteRow(l);
              }
              if(lignes[0][0]===idPagnet){
                  var taille = lignes.length;
                  for(var i = 0; i<taille; i++){
                    var ligne = "<tr>"+
                    "<td>"+lignes[i][2]+"</td>"+
                    "<td>"+lignes[i][3]+"</td>"+
                    "<td>"+lignes[i][4]+"</td>"+
                    "<td>"+lignes[i][5]+"</td>"+
                    "<td class='hidden-xs hidden-sm'>"+lignes[i][6]+"</td>"+
                    "<td class='hidden-xs'>"+
                        "<button disabled='true' type='button' class='btn btn-warning' >" +
                            "<span> Retour </span>" +
                        "</button>" +
                    "</td>"+
                    "</tr>";
                    $("#"+idTable+"").prepend(ligne);
                  }   
              }
          },
          error: function() {
              alert("La requête "); },
          dataType:"text"
      }); 
  } 
}

$(function(){
  panier_Approvisionnement= function (idPagnet)  {
    table="panier_Approvisionnement";
    details_Panier(idPagnet,table);
  }
}); 

$(function(){
  panier_Retrait= function (idPagnet)  {
    table="panier_Retrait";
    details_Panier(idPagnet,table);
  }
});

$(function(){
  panier_VenteCaisse= function (idPagnet)  {
    table="panier_VenteCaisse";
    details_Panier(idPagnet,table);
  }
});

$(function(){
  panier_VenteMobile= function (idPagnet)  {
    table="panier_VenteMobile";
    details_Panier(idPagnet,table);
  }
});

$(function(){
  panier_Depenses= function (idPagnet)  {
    table="panier_Depenses";
    details_Panier(idPagnet,table);
  }
});

$(function(){
  panier_BonsClient= function (idPagnet)  {
    table="panier_BonsClient";
    details_Panier(idPagnet,table);
  }
});


});