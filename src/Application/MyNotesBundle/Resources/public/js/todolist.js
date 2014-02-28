
$(document).ready(function(){

	// Get items
	function getItems(exampleNr)
	{
		var columns = [];

		$(exampleNr + ' ul.sortable-list').each(function(){
			columns.push($(this).sortable('toArray').join(','));				
		});

		return columns.join('|');
	}

	// Load items from cookie
	function loadItemsFromCookie(name)
	{
		if ( $.cookie(name) != null )
		{
			renderItems($.cookie(name));
		}
		else 
		{
			alert('No items saved in "' + name + '".');
		}	
	}

	// Render items
	function renderItems(items)
	{
		var html = '';
                //y()=("1,2,4"|)
		var columns = items.split('|');
		for ( var c in columns )
		{
			html += '<div class="column left';
            		if ( c == 0 )
			{
				html += ' first';
			}
        		html += '"><ul class="sortable-list">';
        		if ( columns[c] != '' )
			{
				var items = columns[c].split(',');
                		for ( var i in items )
				{
					html += '<li class="sortable-item" id="' + items[i] + '">Sortable item ' + items[i] + '</li>';
				}
			}

			html += '</ul></div>';
		}
		$('#example-2-4-renderarea').html(html);
	}

	// Example 2.1: Get items
	/*$('#example-2-1 .sortable-list').sortable({
		connectWith: '#example-2-1 .sortable-list'
	});*/

	$('#btn-get').click(function(){
		alert(getItems('#example-2-1'));
	});
        // Example 2.2: Save items
	$('#btn-save').click(function(){
		$.cookie('cookie-a', getItems('#example-2-1'));
/*
 <h3 class="color-yellow">STANDARD</h3>
    <ul class="sortable-list ui-sortable">
        <li style="" class="sortable-item widget color-yellow" id="D">
  
    */
   
   /*
$("h3").find("li").each(function(){
			//console.log("this"+ $(this).)			// On actualise sa position
						index = parseInt($(this).index()+1);
						// On la met à jour dans la page
						$(this).find(".count").text(index);
					});*/
		alert('Items saved (' + $.cookie('cookie-a') + ')');
	});
        
        
        // Au double clic sur le texte
      /*  var elt=$(".sortable-list > li");
                                       
                                        
			$(elt).dblclick(function(){
				// On récupère sa valeur
				txt = $(this).text();
				// On ajoute un champ de saisie avec la valeur
				$(this).html("<input value='"+txt+"' />");
				// On la sélectionne par défaut
				$(this).find("input").select();
			})*/
			
			// Lorsque l'on quitte la zone de saisie du texte
			/*$(elt).live("blur", function(){
				// On récupère la valeur du champ de saisie
				txt = $(this).val();
				// On insère dans le <li> la nouvelle valeur textuelle
				$(this).parent().html(txt);
			})
			
			// On autorise la même action lorsque l'on valide par la touche entrée
			$(elt).live("keyup", function(e) {
				if(e.keyCode == 13) {
					$(this).trigger("blur");
				}
			});*/
                        
/*
 * 
 var obj = $(this);
			
			// Empêcher la sélection des éléments à la sourirs (meilleure gestion du drag & drop)
			var _preventDefault = function(evt) { evt.preventDefault(); };
			$("li").bind("dragstart", _preventDefault).bind("selectstart", _preventDefault);

			// Initialisation du composant "sortable"
			$(obj).sortable({
				axis: "y", // Le sortable ne s'applique que sur l'axe vertical
				containment: ".shoppingList", // Le drag ne peut sortir de l'élément qui contient la liste
				handle: ".item", // Le drag ne peut se faire que sur l'élément .item (le texte)
				distance: 10, // Le drag ne commence qu'à partir de 10px de distance de l'élément
				// Evenement appelé lorsque l'élément est relaché
				stop: function(event, ui){
					// Pour chaque item de liste
					$(obj).find("li").each(function(){
						// On actualise sa position
						index = parseInt($(this).index()+1);
						// On la met à jour dans la page
						$(this).find(".count").text(index);
					});
				}
			});
			 
  
 *
 */
	// Example 2.3: Save items automaticly
	$('#example-2-1 .sortable-list').sortable({
             placeholder: "ui-sortable-placeholder",
		connectWith: '#example-2-1 .sortable-list',
                distance: 10,
                stop: function(event, ui){
					// Pour chaque item de liste
					$(this).find("li").each(function(){
						// On actualise sa position
						index = parseInt($(this).index()+1);
						// On la met à jour dans la page
						$(this).find(".count").text(index);
                                               //   $(this).prepend('<span class="count">'+parseInt($(elt).index()+1)+'</span>');
					});
                                      
				},
                               
		update: function(){
			$.cookie('cookie-a', getItems('#example-2-1'));
		}
	});


	// Example 2.4: Load items
	$('#btn-load-example').click(function(){
		renderItems(getItems('#example-2-1'));
	});

	$('#btn-load-cookie-a').click(function(){
		loadItemsFromCookie('cookie-a');	
	});

	$('#btn-load-cookie-b').click(function(){
		loadItemsFromCookie('cookie-b');	
	});
        
        $("span.check").click(function(){
            if ($(this).hasClass("unchecked")){
                
                $(this).removeClass("unchecked").addClass("checked");
               
            }else{
                 $(this).removeClass("checked").addClass("unchecked");
            }
             $(this).parent().toggleClass("bought");
				// On alterne la classe de l'item (le <li>), le CSS associé fera que l'élément sera barré
			/*	$(this).parent().toggleClass("bought");*/
				
				// Si cet élément est acheté
				//if($(this).parent().hasClass("bought"))
					// On modifie la classe en ajoutant la classe "checked"
				//	$(this).removeClass("unchecked").addClass("checked");
				// Le cas contraire
				/*else
					// On modifie la classe en retirant la classe "checked"
					$(this).removeClass("checked").addClass("unchecked");*/
			});

$(".trash").droppable({
    
  /*  stop: function(event, ui){
					// Pour chaque item de liste
					$(obj).find("li").each(function(){
						// On actualise sa position
						index = parseInt($(this).index()+1);
						// On la met à jour dans la page
						$(this).find(".count").text(index);
					});
				},*/
			
				// Lorsque l'on relache un élément sur la poubelle
				drop: function(event, ui){
					// On retire la classe "hover" associée au div .trash
					$(this).removeClass("hover");
					// On ajoute la classe "deleted" au div .trash pour signifier que l'élément a bien été supprimé
					$(this).addClass("deleted");
					// On affiche un petit message "Cet élément a été supprimé" en récupérant la valeur textuelle de l'élément relaché
				
                                      //  $(this).text(ui.draggable.find(".item").text()+" supprimé !");
				
					// On supprimer l'élément de la page, le setTimeout est un fix pour IE (http://dev.jqueryui.com/ticket/4088)
					setTimeout(function() { ui.draggable.remove(); }, 1);
                                     //   setTimeout(function() {   $(this).text("g"); }, 3000);
                                      
					 $(".trash").removeClass('emptytrash').addClass('noemptytrash');
                   //   setTimeout(function() {   $(this).text("g"); }, 3000);
                                   $(this).text("Corbeille");
                 
                                        //setTimeout(function() { ui.draggable.remove(); }, 1);
					
					// On retourne à l'état originel de la poubelle après 2000 ms soit 2 secondes
					/*var elt = $(this);
					setTimeout(function(){ elt.removeClass("deleted"); */
                                        /*setTimeout(function(){ 
                                             $(this).text(ui.draggable.find(".item").text()+" Corbeille");
                                             }, 2000);*/
                                            
                                          //  elt.text("Trash"); }, 4000);*/
			
                        
					// On retourne à l'état originel de la poubelle après 2000 ms soit 2 secondes
					//elt = $(this);
					//setTimeout(function(){ elt.removeClass("deleted"); elt.text("Trash"); }, 2000);
				},
				// Lorsque l'on passe un élément au dessus de la poubelle
				over: function(event, ui){
					// On ajoute la classe "hover" au div .trash
					$(this).addClass("hover");
					// On cache l'élément déplacé
					//ui.draggable.hide();
                                        ui.draggable.addClass("hover");
					// On indique via un petit message si l'on veut bien supprimer cet élément
					$(this).text("Remove "+ui.draggable.find(".item").text());
					// On change le curseur
					$(this).css("cursor", "pointer");
				},
				// Lorsque l'on quitte la poubelle
				out: function(event, ui){
					// On retire la classe "hover" au div .trash
					$(this).removeClass("hover");
                                         ui.draggable.removeClass("hover");
					// On réaffiche l'élément déplacé
				//	ui.draggable.show();
					// On remet le texte par défaut
					$(this).text("");
					// Ainsi que le curseur par défaut
					$(this).css("cursor", "normal");
				}
			})
			
});
 