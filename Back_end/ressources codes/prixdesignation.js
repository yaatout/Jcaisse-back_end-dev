/*
Résumé:
Commentaire:
version:1.1
Auteur: Ibrahima DIOP
Date de modification:05/04/2016
*/
var xmlHttp=createXmlHttpRequestObject();

function createXmlHttpRequestObject(){
  var xmlHttp;
  if (window.XMLHttpRequest){
     try{
          xmlHttp= new XMLHttpRequest();
     }catch(e){
          xmlHttp=false;
     }
  }else{
     try{
          xmlHttp= new ActiveXObject("Microsoft.XMLHTTP");
     }catch(e){
          xmlHttp=false;
     }
  }
  if (!xmlHttp)
     alert("impossible de créer l'objet");
  else
     return xmlHttp;
}
var quantite=0;
var remise=0.0;
var total=0.0;
function process(){
	if (xmlHttp.readyState==0 || xmlHttp.readyState==4){
		designation=encodeURIComponent(document.getElementById("designation").value);
		unitevente=encodeURIComponent(document.getElementById("unitevente").value);
		//alert(unitevente);
		quantite=encodeURIComponent(document.getElementById("quantite").value);
		remise=encodeURIComponent(document.getElementById("remise").value);
		xmlHttp.open("GET","prixdesignation.php?desig="+designation+"&unitev="+unitevente, true);
		xmlHttp.onreadystatechange=handleServerResponse;
		xmlHttp.send(null);
	}else{
		setTimeout('process()',1000);
	}
}

function handleServerResponse(){
 if (xmlHttp.readyState==4){
		if (xmlHttp.status==200){
			resp=xmlHttp.responseXML;
			xmlDoc=resp.documentElement;
			message=xmlDoc.firstChild.data;
		  theDiv=document.getElementById('apresEntre');
			if (message =="Cette désignation est absente, il faut l'ajouter avec son prix.")
				 theDiv.innerHTML='<span style="color:red">'+message+'</span><br/>';
			else if (message =="La désignation est un champ obligatoire.")
				 theDiv.innerHTML='<span style="color:blue">'+message+'</span><br/>';
		  else{
			theDiv.innerHTML='<span style="color:#008000;">Vérification réussit : Cette désignation est bien dans la base de données.</span><br/>';
			document.getElementById('prix').value=message;
			//alert(message);
			total=quantite * message-remise;	
			//alert(total);	
			document.getElementById('prixt').value=total;
			}
			setTimeout('process()',1000);
		}else
			alert('quelque chose ne marche pas');
	}
}