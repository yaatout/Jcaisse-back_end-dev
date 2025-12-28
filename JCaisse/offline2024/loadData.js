function getIdUser() {
  //date=new Date();
  //datejour=date.toLocaleDateString("es-CL");
  //iduser=localStorage.getItem('Iduser')
  const user = localStorage.getItem("Users");
  if (user == null) {
    return [];
  }
  return JSON.parse(user);
}

function saveIdUser(id) {
  localStorage.setItem("Users", JSON.stringify(id));
  //$("#idPanier").load("container.html");
}

$.ajax({
  url: "JCaisse/offline/ajax/loadDataAjax.php",

  method: "POST",

  success: function (data) {
    //alert(data);
    var tab = data.split("<>");

    localStorage.setItem("Iduser", tab[0]);

    localStorage.setItem("Boutique", tab[1]);

    localStorage.setItem("Designation", tab[2]);

    localStorage.setItem("Compte", tab[3]);

    //localStorage.setItem("idCompte2",1);

    localStorage.setItem("Client", tab[4]);

    //localStorage.setItem('Payement-salaire', tab[5]);

    localStorage.setItem("Bon", tab[5]);

    localStorage.setItem("Versement", tab[6]);

    users = JSON.parse(localStorage.getItem("Boutique"));
    //alert(users)
    iduser = localStorage.getItem("Iduser");
    searchUser = users.find((u) => u.idutilisateur == parseInt(iduser));
    //alert(searchUser.idutilisateur)
    theUsers = getIdUser();
    var i = 1;
    //localStorage.setItem("IdPanier",pagnets.length)

    //alert(theUsers)
    if (theUsers != null) {
      for (var j = 0; j < theUsers.length; j++) {
        if (theUsers[j].idU == parseInt(tab[0])) {
          i = i + 1;
        }
      }
    }

    if (i <= 1) {
      //alert(lesPaniers.length)
      Users = getIdUser();

      User = new Object();
      User.idU = parseInt(tab[0]);
      User.prenom=searchUser.prenom;
      User.nom=searchUser.nom; 

      Users.push(User);
      saveIdUser(Users);
    }

    if (!localStorage.Panier) {
      localStorage.setItem("Panier", tab[7]);
      paniers = JSON.parse(tab[7]);
      //newpaniers=[];
      for (var i = 0; i < paniers.length; i++) {
        paniers[i].offline = 2;
        if (paniers[i].verrouiller == 0) {
          paniers[i].synchronise = 0;
        } else {
          paniers[i].synchronise = 1;
        }

        //newpaniers.push()
      }

      localStorage.setItem("Panier", JSON.stringify(paniers));
    } else {
      paniers_off = JSON.parse(localStorage.getItem("Panier"));
      paniers = paniers_off.filter((p) => p.iduser == parseInt(tab[0]));
      //alert(paniers)
      
      if(paniers.length==0){
        //P=new Object()
        panier=JSON.parse(tab[7]) 
        if(panier.length!=0){   
          for(var i=0; i<panier.length; i++){
            paniers_off.push(panier[i])
          } 
          localStorage.setItem("Panier", JSON.stringify(paniers_off));
        }
        
      }else{  
      }
    }
    //console.log('ok');
    if (!localStorage.Ligne) {
      $.ajax({
        url: "JCaisse/offline/ajax/lignesOnlineAjax.php",

        method: "POST",

        success: function (data) {
          //alert(data)

          //(!localStorage.Ligne){
          lignes=JSON.parse(data)
          paniers= JSON.parse(localStorage.getItem("Panier"));
          for(var i=lignes.length-1; 0<=i; i--){
            searchPanier.synchronise=1;
            searchPanier=paniers.find((p) => p.idPagnet == parseInt(lignes[i].idPagnet));
            if(lignes[i].classe==2 || lignes[i].classe==5 || lignes[i].classe==7){
              searchPanier.classe=lignes[i].classe;
              localStorage.setItem("Panier", JSON.stringify(paniers));
            }
             

          } 

          //localStorage.setItem("Ligne", data);
          localStorage.setItem("Ligne", JSON.stringify(lignes)); 
          //}
        },

        error: function () {
          //alert("La requête ");
        },

        dataType: "text",
      });
    }

    if (searchUser) {
      sessionStorage.setItem("iduserSession", searchUser.idutilisateur);
      //localStorage.setItem('iduser',searchUser.idutilisateur)
      sessionStorage.setItem("prenomSession", searchUser.prenom);
      sessionStorage.setItem("nomUserSession", searchUser.nom);
      sessionStorage.setItem("adresseUserSession", searchUser.adresse);
      sessionStorage.setItem("emailSession", searchUser.email);
      sessionStorage.setItem("telPortableSession", searchUser.telPortable);
      sessionStorage.setItem("telFixeSession", searchUser.telFixe);
      sessionStorage.setItem("profilSession", searchUser.profil);
      sessionStorage.setItem("creationBSession", searchUser.creationB);
      sessionStorage.setItem("passwordSession", searchUser.motdepasse);

      sessionStorage.setItem("idBoutiqueSession", searchUser.idBoutique);
      //localStorage.setItem('idBoutique',boutiques[i].idBoutique)

      sessionStorage.setItem("proprietaireSession", searchUser.proprietaire);
      sessionStorage.setItem("gerantSession", searchUser.gerant);
      sessionStorage.setItem("caissierSession", searchUser.caissier);
      sessionStorage.setItem("vendeurSession", searchUser.vendeur);
      sessionStorage.setItem("activerSession", searchUser.activer);

      //searchUser=boutiques.find(b=>b.idBoutique == sessionStorage.idBoutiqueSession);
      sessionStorage.setItem("nomBSession", searchUser.nomBoutique);
      sessionStorage.setItem("labelBSession", searchUser.labelB);
      sessionStorage.setItem("adresseBSession", searchUser.adresseBoutique);
      sessionStorage.setItem("typeSession", searchUser.type);
      sessionStorage.setItem("categorieSession", searchUser.categorie);
      sessionStorage.setItem("dateCBSession", searchUser.datecreation);
      sessionStorage.setItem("activerBSession", searchUser.activer);
      sessionStorage.setItem("caisseSession", searchUser.caisse);
      sessionStorage.setItem("telBoutiqueSession", searchUser.telephone);
      sessionStorage.setItem("RegistreComSession", searchUser.RegistreCom);
      sessionStorage.setItem("NineaSession", searchUser.Ninea);
      sessionStorage.setItem(
        "enConfigurationSession",
        searchUser.enConfiguration
      );
      sessionStorage.setItem("vitrineSession", searchUser.vitrine);
      sessionStorage.setItem("importExpSession", searchUser.importExp);
      // ligne=[];
      // if(!localStorage.ligne){
      //     localStorage.setItem("ligne",JSON.stringify(ligne));
      // }

      //location.reload()
      window.location.href = "/JCaisse/insertionLigneLight.php";
    }
  },

  error: function (data) {
    //alert(data);
  },

  dataType: "text",
});
