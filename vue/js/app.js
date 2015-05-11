$(document).ready(function(){

	$('#raty').raty({number: 5, path:"vue/js/raty-2.7.0/lib/images", scoreName: 'note'});

	function getRaty(){
		$('.ratyCommentaire').raty({
			path:"vue/js/raty-2.7.0/lib/images",
			score: function() {
				return $(this).attr('data-number');
			}, number: 5, readOnly: true 
		});
	}
	getRaty();


	//Vérification du champ du formulaire
	$("#btnEnvoyer").click(function(event)
	{
		event.preventDefault();

		var nom = $('#nom');
		var valide=true;
		$('#errorNom').remove();
		if (nom.val()==false)
		{
			$("#nom").after("<p id='errorNom' class='alert alert-danger'>Vous devez entrer un nom.</p>");
			var valide=false;
		}

		var raty = $('#raty').children('input');
		$('#errorRaty').remove();
		if (raty.val()==false) {
			raty.after("<div id='errorRaty' class='alert alert-danger'>Vous devez noter l'article.</div>");
			var valide=false;
		}

		var message = $('#message');
		$('#errorMessage').remove();
		if (message.val()==false) {
			$("#message").after("<p id='errorMessage' class='alert alert-danger'>Vous devez entrer un message.</p>");
			var valide=false;
		}

		if (valide==true) {

			$.ajax({
				type:"POST",
				data:$("#formCommentaire").serialize(),
				dataType:"json"
			})
			.done(function(resultat)
			{
				//console.log(resultat);
				//console.log(resultat.message);
				//console.log(resultat.commentaire);
				$('.alert-success').remove();
				$("#btnEnvoyer").after("<br><br><p class='alert alert-success'>"+resultat.message+"</p>");
				//console.log(resultat.commentaire);
				$("#allCommentaire").prepend(resultat.commentaire);
				//On récupère la fonction Raty
				getRaty();
				//Pour remettre à 0 les valeurs du form une fois envoyé
				$('#nom').val("");
				$('#raty').raty({score:0,path:"vue/js/raty-2.7.0/lib/images"});
				$('#message').val("");
			})
		}
	});

	//Sidebar Ajax pour faire une recherche dynamique style Google
	$("#inputSearch").keyup(function() {
		console.log($(this).val());
		$('#resultatRecherche').empty();
		var form=$(this).closest("form");
		$.ajax({
			type:"GET",
			url: form.attr("action"), //On récupère l'attribut du Form avec une variable. On pourrait écrire index.php
			data: form.serialize(),
			dataType:"json"
		})
		.done(function(resultat)
		{
			$('#resultatRecherche').append(resultat.listes);
		});

	});

});