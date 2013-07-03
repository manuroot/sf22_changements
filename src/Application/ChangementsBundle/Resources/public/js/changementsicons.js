$(document).ready(function() {
 var img_path='bundles/applicationcertificats/images/';
 $("td > a").click(function(event) {
    
   /*  console.log("open test button");*/
  // ne pas prendre edit/update a. 
  if ($(this).hasClass("open") || $(this).hasClass("closed")){
        var id=$(this).attr("data-id");
               console.log("id=" + id);
             var dataAjax = {id:id};
             
       if ($(this).hasClass("open")){
           //cas open: open ==> closed
       if( $(this).closest("tr").hasClass("success")){
               $(this).children().attr("src", img_path + "cadenas-sferme.png");
                 $(this).removeClass("open").addClass("closed");
            $(this).closest("tr").removeClass("success");
       }
       //cas open: prepare ==> open
       else if ($(this).closest("tr").hasClass("warning")){
               $(this).children().attr("src", img_path + "cadenas-souvert.png");
              /*   $(this).removeClass("open").addClass("closed");*/
            $(this).closest("tr").removeClass("warning").addClass("success");
       }
       
          
 } 
 //cas closed: closed ==> prepare
 else if ($(this).hasClass("closed")){
   /*   console.log("closed test button");*/
     /* var id=$(this).attr("data-id");
             var dataAjax = {id:id};*/

      $(this).children().attr("src",img_path + "cadenas-bleu.png");
      $(this).removeClass("closed").addClass("open");
      $(this).closest("tr").addClass("warning");
 };
 remplirSelect(dataAjax);
  }
 
    });
 function remplirSelect (dataAjax) {
   $.ajax({
        url: Routing.generate('changements_updatexhtml_changement'),
       /*  url: "{{ path('changements_updatexhtml_changement') }}", */
        type: "POST", 
        data : dataAjax, 
        dataType: "json", 
        success: function(reponse){
            // Sur Succès de la réponse AJAX
         

    } //Eof:: success
});  //Eof:: ajax 
 
} //Eof:: fucntion remplirSelect

}); //Eof:: ready

