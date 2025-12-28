$(document).ready(function () {
  date = new Date();
  sec = date.getSeconds();
  setTimeout(() => {
    setInterval(() => {
      if (navigator.onLine == false) {
        if (sessionStorage.iduserSession) {
          window.location.href = "offline/insertionLigneLight.html";
        }
      } else {
        if (!localStorage.Ligne) {
          if (sessionStorage.iduserSession) {
            $.ajax({
              url: "offline/ajax/lignesOnlineAjax.php",

              method: "POST",

              success: function (data) {
                lignes=JSON.parse(data)
                paniers= JSON.parse(localStorage.getItem("Panier"));
                // for(var i=lignes.length-1; 0<=i; i--){
                //   if(lignes[i].classe==2 || lignes[i].classe==5 || lignes[i].classe==7){
                //     searchPanier=paniers.find((p) => p.idPagnet == parseInt(lignes[i].idPagnet));
                //     searchPanier.classe=lignes[i].classe;
                //     localStorage.setItem("Panier", JSON.stringify(paniers));
                //   }
                // }
                for(var i=lignes.length-1; 0<=i; i--){
                  lignes[i].synchronise=1;
                  searchPanier=paniers.find((p) => p.idPagnet == parseInt(lignes[i].idPagnet));
                  if(lignes[i].classe==2 || lignes[i].classe==5 || lignes[i].classe==7){
                    searchPanier.classe=lignes[i].classe;
                    localStorage.setItem("Panier", JSON.stringify(paniers));
                  }
                   
      
                } 

                //localStorage.setItem("Ligne", data);
                localStorage.setItem("Ligne", JSON.stringify(lignes)); 
              },
 
              error: function () {
                //alert("La requête ");
              },

              dataType: "text",
            });
          }
        }
      }
    }, 60 * 10);
  }, (60 - sec) * 10);


  sec1 = date.getSeconds();
  setTimeout(() => {
    setInterval(() => {
      //alert(1) 
      if (navigator.onLine == true) {
        if(sessionStorage.iduserSession){
          if(parseInt( sessionStorage.iduserSession)==localStorage.Iduser){
            var panierOffline = synchronisation_paniers_offline();
            var panierOnline= synchronisation_paniers_online();
            if(panierOffline){
              envoiPanier(panierOffline);
            }else{
 
            }
            if(panierOnline){
              if(panierOnline.verrouiller==1){
                envoiUpdatePanier(panierOnline);
              }else{
                //lignes=getLigne();             
              }
            }
              
          }     
        }
        
        // datejour = getDateJour()
        // var lesPaniers = getPanier();
        // paniersEnvoyes = lesPaniers.filter((p) =>p.offline == 1 && p.datepagej==datejour);  
        // //alert(paniers)
        // if (Object.keys(paniersEnvoyes).length === 0) {
        //   //window.location.href = "/JCaisse/insertionLigneLight.php";
        // } 
        // else {
        //   //for (let j = 0; j < paniers.length; j++) {
        //     lignesOff=getLigne();
        //     var min = paniersEnvoyes[0].identifiantP;
        //     panierEnv = paniersEnvoyes.find((p) => p.identifiantP == parseInt(min));
        //     if(panierEnv.length!=0){
        //       envoiPanier(panierEnv); 
        //       envoiVersement(panierEnv); 
        //     } 
          
            
        //   //}
        //   //window.location.href = "/JCaisse/insertionLigneLight.php"; 
        // }

        // paniersUpdate = lesPaniers.filter((p) =>p.offline == 0 || p.offline==2);  
        // //alert(paniers)
        // if (Object.keys(paniersUpdate).length === 0) {
        //   //window.location.href = "/JCaisse/insertionLigneLight.php";
        // }else{ 
        //   var min = paniersUpdate[0].identifiantP;
        //   if(min!=""){
        //     panierUpd = paniersUpdate.find((p) => p.identifiantP == parseInt(min));
            
        //     if(panierUpd){
        //       envoiUpdatePanier(panierUpd);
              
              

              
        //     }  
      
        //   } 
        //   else{
        //     panierUpd = paniersUpdate.filter((p) => p.offline ==2 && p.verrouiller ==1); 
        //     if(panierUpd.length!=0){
        //       envoiUpdatePanier(panierUpd); 
        //     }
        //   }
        // }
      } else {
        // $("#btnChangerBoutique").attr("disabled","disabled");  
        // $("#btnChangerVitrine").attr("disabled","disabled");

        $(document).on("click", "#btnBon", function (e) {
          message = "Votre connexion est interrompue!!!";
          document.getElementById("msg1").innerHTML = message;
          $(".msg_info").modal("show");
        });

        $(document).on("click", ".btnCloseMsg", function (e) {
          //window.location.href="/JCaisse_Mare/offline/index.html";
        });
      }
    }, 60 * 100);
  }, (60 - sec1) * 100); 

  if(sessionStorage.iduserSession){
    if(parseInt( sessionStorage.iduserSession)==localStorage.Iduser){
      //alert('ok')
      //synchronisation_paniers_online(); 
    }
  }
  
});

function envoiPanier(panier) {
  //date = new Date();
  //datejour = getDateJour();
  //if(panier.offline==1){

  datepagej = panier.datepagej;
  type = panier.type;
  classe = panier.classe;
  heurePagnet = panier.heurePagnet;
  totalp = panier.totalp;
  remise = panier.remise;
  apayerPagnet = panier.apayerPagnet;
  restourne = panier.restourne;
  versement = panier.versement;
  verrouiller = panier.verrouiller;
  idClient = panier.idClient;
  // idVitrine= panier.idVitrine;
  iduser = panier.iduser;
  idCompte = panier.idCompte;
  avance = panier.avance;
  // apayerPagnetTvaP= panier.apayerPagnetTvaP;
  // apayerPagnetTvaR= panier.apayerPagnetTvaR;
  dejaTerminer = 1;
  image = null;
  synchronise = 1;
  idPanier = panier.idPagnet;
  identifiantP = panier.identifiantP;
  var leslignes = getLigne();
  lignes = leslignes.filter((l) => l.idPagnet == idPanier);
  //nbreLignes=lignes.length;

  $.ajax({
    url: "offline/ajax/insertionPanierAjax.php",

    method: "POST",

    data: {
      //operation: 1,
      datepagej: datepagej,
      type: type,
      classe: classe,
      heurePagnet: heurePagnet,
      totalp: totalp,
      idPanier: idPanier,
      identifiantP: identifiantP,
      remise: remise,
      apayerPagnet: apayerPagnet,
      restourne: restourne,
      versement: versement,
      verrouiller: verrouiller,
      idClient: idClient,
      // idVitrine:idVitrine,
      iduser: iduser,
      idCompte: idCompte,
      avance: avance,
      // apayerPagnetTvaP:apayerPagnetTvaP,
      // apayerPagnetTvaR:apayerPagnetTvaR,
      dejaTerminer: dejaTerminer,
      synchronise: synchronise,
      lignes: JSON.stringify(lignes),
      //nbreLignes:nbreLignes
    },

    success: function (data) {
      //alert(data)

      result = data;

      //console.log('data / '+data)

      if (result == 0) {
        //console.log('if')
      } else {
        //console.log('else')
        Panier = getPanier();
        lignesOff=getLigne();
        var panierE = Panier.find((p) => p.idPagnet == idPanier);

        if(panierE.verrouiller==0) {
          panierE.synchronise = 1;
          panierE.offline = 2;
          savePanier(Panier);

          ligne=lignesOff.filter((l) => l.idPagnet == panier.idPagnet)
          //alert(ligne)
          if(ligne){ 
            for(var i=0; i<ligne.length; i++){
              ligne[i].synchronise=1;  
            }
            saveLigne(lignesOff); 
          }
        }else if(panierE.verrouiller==1){
          panierE.synchronise = 1;
          panierE.offline = 3;
          savePanier(Panier);
      
          ligne=lignesOff.filter((l) => l.idPagnet == panier.idPagnet)
          //alert(ligne)
          if(ligne){ 
            for(var i=0; i<ligne.length; i++){ 
              ligne[i].synchronise=1;  
            }
            saveLigne(lignesOff); 
          }
        }
      }
    },

    error: function () {
      //alert("La requête ");
    },

    dataType: "text",
  });

  //}
} 

function envoiUpdatePanier(panier) {
  //date = new Date();
  //datejour = getDateJour();
  //if(panier.offline==0){

  datepagej = panier.datepagej;
  type = panier.type;
  classe = panier.classe;
  heurePagnet = panier.heurePagnet;
  totalp = panier.totalp;
  remise = panier.remise;
  apayerPagnet = panier.apayerPagnet;
  restourne = panier.restourne;
  versement = panier.versement;
  verrouiller = panier.verrouiller;
  idClient = panier.idClient;
  // idVitrine= panier.idVitrine;
  iduser = panier.iduser;
  idCompte = panier.idCompte; 
  avance = panier.avance;
  // apayerPagnetTvaP= panier.apayerPagnetTvaP;
  // apayerPagnetTvaR= panier.apayerPagnetTvaR;
  dejaTerminer = 1;
  image = null; 
  synchronise = 1;
  idPanier = panier.idPagnet;
  identifiantP = panier.identifiantP; 
  // if(identifiantP==""){
  //   identifiantP=idPanier;   
  // }else{
  //   identifiantP = panier.identifiantP;
  // }
  var leslignes=getLigne(); 
  // if(panier.verrouiller==0 && panier.offline==2){
  //   lignes= leslignes.filter(l =>l.synchronise==2);     
  //   //alert(lignes) 
  // }else{
  //   lignes= leslignes.filter(l =>l.pagnet==parseInt(idPanier) && l.synchronise==0); 
  // } 
  lignes= leslignes.filter((l) =>l.idPagnet==idPanier); 
  //alert(lignes) 
  $.ajax({
    url: "offline/ajax/updatePanierOnlineAjax.php",

    method: "POST",

    data: {
      //operation: 1,
      datepagej: datepagej,
      type: type,
      classe: classe,
      heurePagnet: heurePagnet,
      totalp: totalp,
      idPanier: idPanier,
      identifiantP: identifiantP,
      remise: remise,
      apayerPagnet: apayerPagnet,
      restourne: restourne,
      versement: versement, 
      verrouiller: verrouiller,
      idClient: idClient,
      // idVitrine:idVitrine,
      iduser: iduser,
      idCompte: idCompte,
      avance: avance,
      // apayerPagnetTvaP:apayerPagnetTvaP,
      // apayerPagnetTvaR:apayerPagnetTvaR,
      dejaTerminer: dejaTerminer,
      synchronise: synchronise,
      lignes : JSON.stringify(lignes),
      //nbreLignes:nbreLignes
    },

    success: function (data) {
      alert(data)
 
      if (data!=0) { 
        //alert(5)
        Panier = getPanier();
        lignesOff=getLigne()
        
        panierE = Panier.find((p) => p.idPagnet == idPanier);
        //alert(panierE)

        if (panierE) {
          panierE.synchronise = 1;
          if(panierE.verrouiller==1){
            panierE.offline = 3;

            // ligne=lignesOff.filter((l) => l.idPagnet == panierE.idPagnet)
            // //alert(ligne)
            // //panierE.idPagnet=parseInt(data) 
            // //alert(ligne)
            // if(ligne){  
            //   for(var i=0; i<ligne.length; i++){ 
            //     ligne[i].synchronise=1; 
            //     //ligne[i].idPagnet=parseInt(data) 
            //   }
            //   saveLigne(lignesOff);    
              
            // }
          }else{
            panierE.offline = 2; 
         
            ligne=lignesOff.filter((l) => l.idPagnet == panierE.idPagnet)
            panierE.idPagnet=parseInt(data) 
            //alert(ligne)
            if(ligne){  
              for(var i=0; i<ligne.length; i++){ 
                ligne[i].synchronise=1; 
                ligne[i].idPagnet=parseInt(data) 
              }
              saveLigne(lignesOff);    
              
            }
          }
          
          savePanier(Panier);
          
          //$("#idPanier").load("container.html");

          // lesPaniers=getPanier();
          // paniers= lesPaniers.filter(p =>p.offline==1 || p.offline==0);

          // if(paniers){ 

          // }else{
          //     window.location.href="/JCaisse/insertionLigneLight.php";
          // }
        }

        //window.location.href = "/JCaisse/insertionLigneLight.php";
      }
    },

    error: function () {
      //alert("La requête ");
    },

    dataType: "text",
  });

  //}
}
 
function envoiVersement(versement) {
  date = new Date();
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
      url: "offline/ajax/insertionVersementAjax.php",

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
            panierE.offline = 2;
            savePanier(paniers);

            $("#venteContent").load(
              "ajax/loadContainerAjax.php",
              function (data) {
                //get content from PHP page

                $(".codeBarreLigneVt").focus();
              }
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
}

function addPanier() {
  lesPanier = getPanier();
  users = JSON.parse(localStorage.getItem("Boutique"));
  iduser = localStorage.getItem("Iduser");
  date = new Date();
  datejour = date.toLocaleDateString("es-CL");
  localStorage.setItem("datejour", datejour);

  var i = 1;
  //localStorage.setItem("IdPanier",pagnets.length)
  if (lesPanier != null && users != null) {
    for (var j = 0; j < lesPanier.length; j++) {
      if (lesPanier[j].verrouiller == 0) {
        i = i + 1;
      }
    }
  }

  if (i <= 2 && users != null) {
    var pagnets = getPanier();
    var comptes = getCompte();
    //panierNV=pagnets.filter(p =>p.verrouiller==0)
    localStorage.setItem("IdPanier", pagnets.length);
    heure = date.toLocaleTimeString("fr");

    tabDate = datejour.split("-");
    tabHeure = heure.split(":");

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
      tabHeure[2];
    Panier.datepagej = datejour;
    Panier.type = 0;
    Panier.heurePagnet = heure;
    Panier.totalp = 0;
    Panier.remise = 0;
    Panier.apayerPagnet = 0;
    Panier.restourne = 0;
    Panier.versement = 0;
    Panier.verrouiller = 0;
    Panier.idClient = 0;
    Panier.idVitrine = 0;
    compte = comptes.find(
      (c) => c.nomCompte == "Caisse" || c.nomCompte == "caisse"
    );
    Panier.idCompte = compte.idCompte;
    Panier.avance = 0;
    Panier.dejaTerminer = 0;
    Panier.apayerPagnetTvaP = 0;
    Panier.apayerPagnetTvaR = 0;
    Panier.image = null;
    Panier.synchronise = 0;
    //var searUser=users.find(u =>u.idutilisateur==sessionStorage.iduserSession)
    Panier.iduser = sessionStorage.iduserSession;

    pagnets.push(Panier);
    // ligne=[];
    // if(!(localStorage.lignes)){
    //     localStorage.setItem("lignes",JSON.stringify(ligne));
    // }
    savePanier(pagnets);
  }
}

function getPanier() {
  // date=new Date();
  // datejour=date.toLocaleDateString("es-CL");

  const panier = localStorage.getItem("Panier");
  if (panier == null) {
    return [];
  }
  return JSON.parse(panier);
}

function getLigne() {
  // date=new Date();
  // datejour=date.toLocaleDateString("es-CL");

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

function savePanier(pagnet) {
  localStorage.setItem("Panier", JSON.stringify(pagnet));
  //$("#idPanier").load("container.html");
}

function saveLigne(ligne) {
  localStorage.setItem("Ligne", JSON.stringify(ligne));
}

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


function dateDhier(date) {
  date = new Date() 
  const previous = new Date(date.getTime());
  previous.setDate(date.getDate() - 1); 

  return previous;
}

function padTo2Digits(num) {
return num.toString().padStart(2, '0');
}

function formatDate(date) {
return [
  padTo2Digits(date.getDate()),
  padTo2Digits(date.getMonth() + 1),
  date.getFullYear(),
//   padTo2Digits(date.getMonth() + 1),
//   padTo2Digits(date.getDate()),
].join('-');
}

function synchronisation_paniers_offline(){
  //date = new Date();  
  var lesPaniers=getPanier();  
  paniers= lesPaniers.filter(p =>p.offline==1);
  //alert(paniers)   
  if(paniers.length==0){
    //window.location.href="/JCaisse/insertionLigneLight.php";
  }else{
    var min=paniers[0].identifiantP; 
    //if(paniers!=null){               
    for (let i = 1; i < paniers.length; i++) {
      const e = paniers[i].identifiantP;
      if (parseInt(min) > parseInt(e)) {   
                
      /*if (min > e) {*/
          min = e;
      
      }  
    }         
    panier= lesPaniers.find(p =>p.identifiantP==parseInt(min));
    //envoiPanier(panier)
    return panier;
  }
}


function synchronisation_paniers_online(){
  //date = new Date();  
  var lesPaniers=getPanier();  
  paniers= lesPaniers.filter(p =>p.offline==2);
  //alert(paniers)   
  if(paniers.length==0){
    //window.location.href="/JCaisse/insertionLigneLight.php";
  }else{
    var min=paniers[0].identifiantP; 
    //if(paniers!=null){               
    for (let i = 1; i < paniers.length; i++) {
      const e = paniers[i].identifiantP;
      if (parseInt(min) > parseInt(e)) {   
                
      /*if (min > e) {*/
          min = e;
      
      }  
    }         
    panier= lesPaniers.find(p =>p.identifiantP==parseInt(min));
    // if(panier.verrouiller==1){
    //   envoiUpdatePanier(panier);
    // }
    
    return panier;
  }
}
