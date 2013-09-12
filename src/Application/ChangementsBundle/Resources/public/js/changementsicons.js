$(document).ready(function() {
    var img_path = 'bundles/applicationcertificats/images/';
    
    /*  
    
$("a.removeRecord").live("click",function(event){
      if ($(this).hasClass("open") || $(this).hasClass("closed") || $(this).hasClass("prepare")) {
     
   event.stopPropagation();
   if(confirm("Do you want to delete?")) {
    this.click;
       alert("Ok");
   }
   else
   {
       alert("Cancel");
   }       
   event.preventDefault();
      }
});*/


/*
 Are you sure?

  $(document).ready(function() {
    $("#dialog-box").dialog({
      autoOpen: false,
      modal: true
    });

  $(".close").click(function(e) {
    var currentElem = $(this);
    $("#dialog-box").dialog({
      buttons : {
        "Confirm" : function() {
          currentElem.remove()
        },
        "Cancel" : function() {
          $(this).dialog("close");
        }
      }
    });

    $("#dialog-box").dialog("open");
  });
});
 
 **/
    $("td > a").click(function(event) {

        /* * A modifier: change color only sur success !!*/
        if ($(this).hasClass("open") || $(this).hasClass("closed") || $(this).hasClass("prepare")) {
            
            var checkstr =  confirm('are you sure you want to delete this?');
            if(checkstr == true){
            // do your code
           
            /*else{
                return false;
            }*/
            var id = $(this).attr("data-id");
            console.log("id=" + id);
            var dataAjax = {
                id: id
            };

            if ($(this).hasClass("open")) {
                $(this).removeClass("open").addClass("closed");
                $(this).closest("tr").removeClass("success").addClass("myclosed");
                $(this).children().attr("src", img_path + "cadenas-sferme.png");
            }
            else if ($(this).hasClass("prepare")) {
                $(this).children().attr("src", img_path + "cadenas-souvert.png");
                $(this).closest("tr").removeClass("prepare").addClass("success");
                $(this).removeClass("prepare").addClass("open");
                
            }
            //cas closed: closed ==> prepare
            else if ($(this).hasClass("closed")) {
                /*   console.log("closed test button");*/
                $(this).children().attr("src", img_path + "cadenas-bleu.png");
                $(this).removeClass("closed").addClass("prepare");
                $(this).closest("tr").removeClass("myclosed").addClass("prepare");
            }
            ;
            remplirSelect(dataAjax);
        }
        }
    });
    function remplirSelect(dataAjax) {
        $.ajax({
            url: Routing.generate('changements_updatexhtml_changement'),
            /*  url: "{{ path('changements_updatexhtml_changement') }}", */
            type: "POST",
            data: dataAjax,
            dataType: "json",
            /* ie8 ??*/
            /*cache: false,*/
            /*contentType: 'application/json',*/
            success: function(reponse) {
            },
            error: function(e) {
                console.log(e.message);
            }
        });  //Eof:: ajax 

    } //Eof:: fucntion remplirSelect

}); //Eof:: ready

