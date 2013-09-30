jQuery(document).ready(function() {
        
 // Récupère le div qui contient la collection de tags
        var collectionHolder = $('ul.tags');
        var mybutton_add = '<button class="btn btn-small btn-primary"><i class="icon-plus-sign"></i> Ajouter Fichier</button>';
        var mybutton_del = '<button class="btn btn-small btn-danger"><i class="icon-remove-sign"></i> Supprimer Fichier</button>';
 // ajoute un lien « add a tag »

 //var $addTagLink = $('<hr class="hrsep"><a href="#" class="add_tag_link">' + mybutton_add + '</a>');
        var $addTagLink = $('<a href="#" class="add_tag_link">' + mybutton_add + '</a>');
 //var $addTagLink = $('<hr><a href="#" class="add_tag_link"><button id="show" type="submit" name="submit-filter" class="btn btn-small btn-primary"><i class="icon-plus-sign"></i>Ajouter Fichier</button></a>');
        var $newLinkLi = $('<li></li>').append($addTagLink);

                   
       
            // ajoute l'ancre « ajouter un tag » et li à la balise ul

            collectionHolder.find('li').each(function() {
                addTagFormDeleteLink($(this));
            });
            collectionHolder.append($newLinkLi);
            $addTagLink.on('click', function(e) {
                // empêche le lien de créer un « # » dans l'URL
                e.preventDefault();

                // ajoute un nouveau formulaire tag (voir le prochain bloc de code)
                addTagForm(collectionHolder, $newLinkLi);
            });
        
        function addTagForm(collectionHolder, $newLinkLi) {
            // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
            var prototype = collectionHolder.attr('data-prototype');

            // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
            // la longueur de la collection courante
            var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

            // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un tag"
            var $newFormLi = $('<li></li>').append(newForm);
            $newLinkLi.before($newFormLi);
            // ajoute un lien de suppression au nouveau formulaire
            addTagFormDeleteLink($newFormLi);
        }
        function addTagFormDeleteLink($tagFormLi) {
            var $removeFormA = $('<a href="#">' + mybutton_del + '</a><hr class="hrsep">');
            $tagFormLi.append($removeFormA);

            $removeFormA.on('click', function(e) {
                // empêche le lien de créer un « # » dans l'URL
                e.preventDefault();

                // supprime l'élément li pour le formulaire de tag
                $tagFormLi.remove();
            });
        }
        });