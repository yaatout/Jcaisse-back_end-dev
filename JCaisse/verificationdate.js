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
function process(){
	if (xmlHttp.readyState==0 || xmlHttp.readyState==4){
		dateh=encodeURIComponent(document.getElementById("dateh").value);
		xmlHttp.open("GET","verificationdate.php?dateh="+dateh, true);
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
			if (message =="Cette désignation est absente, vous pouvez l'ajouter avec son prix.")
				 theDiv.innerHTML='<span style="color:#008000;">'+message+'</span><br/>';
			else if (message =="Attention : Cette désignation est dèjà présente dans la base de données.")
				 theDiv.innerHTML='<span style="color:red">'+message+'</span><br/>';	
			else if (message =="La désignation est un champ obligatoire.")
				 theDiv.innerHTML='<span style="color:blue">'+message+'</span><br/>';	
				 
			/*if ((message !='Desole, nous avons pas ce plat!')&& (message !='Entrez le plat que vous desirez'))
				 theDiv.innerHTML+='<form name="formulaire1" method="post" action="foodstore.php">Entrez le nom de la salle de votre choix : <input type="text" id="entre2"/><br/><input name="bouton" type="submit" value="ENVOYER >>"><input type="submit" name="annuler" value="<< ANNULER"/><input type="hidden" name="foodF" value="'+document.getElementById("entre").value+'"/></form>';
			else*/
			   setTimeout('process()',1000);
		}else
			alert('quelque chose ne marche pas');
	}
}