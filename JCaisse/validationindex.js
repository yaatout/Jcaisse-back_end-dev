$(document).ready(function(){
						   
		
				   
	var resultat = true ; 
	
	$('#form').submit(function(){
		
		resultat = true ;
		
		if($('#email').val() != ""){	
		
			var pattern= new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
			if (!pattern.test($("#email").val()))
				resultat=false;
		}
		
		return resultat;
		
		});
	
	
/*    TESTER LES CHAMPS QUI NE DOIVENT PAS ETRE VIDE DES QU'ILS SONT SELECTIONNER ET 
      ENVOYER DES MESSAGES SI UN DE CES CHAMPS EST VIDE ET NETTOYER LES MESSAGES QUAND TOUT EST EN ORDRE.      */
	  

		
		$('#email').keyup(function(){
			if($('#email').val()==""){
				$('#email').attr('placeholder', 'Je ne peux pas être vide!...').parent().addClass('has-error');
			    $('#helpEmail').text('Ce champ est obligatoire');
			}else{
				var pattern= new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
				if (pattern.test($("#email").val())){
					$('#email').parent().removeClass('has-error');
					$("#helpEmail").text('');
				}
				else{
					$('#email').parent().addClass('has-error');
					$("#helpEmail").text("Adresse email invalide.");
					resultat=false;
					}
				}				   
		});			   
						   
						   						  						   						   
});