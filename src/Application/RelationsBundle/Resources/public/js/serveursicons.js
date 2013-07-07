$(document).ready(function() {
 //var img_path='/bundles/applicationcertificats/images/';
  var img_path=window.menuDownUrl;
 $("td > a").click(function(event) {
    
   /*  console.log("open test button");*/
  // ne pas prendre edit/update a. 
  if ($(this).hasClass("open") || $(this).hasClass("closed")){
        var id=$(this).attr("data-id");
               console.log("id=" + id);
             var dataAjax = {id:id};
             
       if ($(this).hasClass("open")){
              $(this).removeClass("open").addClass("closed");
               $(this).children().attr("src", img_path + "cadenas-sferme.png");
                $(this).closest("tr").addClass("error");
       }
  
 else if ($(this).hasClass("closed")){
       $(this).removeClass("closed").addClass("open");
      $(this).children().attr("src",img_path + "cadenas-souvert.png");
      $(this).closest("tr").removeClass("error");
 };
 remplirSelect(dataAjax);
  }
 
    });
 function remplirSelect (dataAjax) {
   $.ajax({
        url: Routing.generate('certificatscenter_updatexhtml_certificats'),
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

