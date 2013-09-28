$(document).ready(function() {
   /*
     $('.btn-add').click(function(event) {
     var collectionHolder = $('#' + $(this).attr('data-target'));
     var prototype = collectionHolder.attr('data-prototype');
     var form = prototype.replace(/__name__/g, collectionHolder.children().length);
     
     collectionHolder.append(form);
     
     return false;
     });*/

    /*$('.btn-remove').live('click', function(event) {
     var name = $(this).attr('data-related');
     $('*[data-content="'+name+'"]').remove();
     
     return false;
     });
     */
  
     function remplirSelect (dataAjax) {
   $.ajax({
         url: Routing.generate('changements_listbyprojet'),
         type: "POST", 
        data : dataAjax, 
        dataType: "json", 
        success: function(reponse){
            // Sur Succès de la réponse AJAX
             var optionData = reponse;
              var cer_arr=[];
            for (key in optionData['chgmnt']) {
            cer_arr.push(optionData['chgmnt'][key]);
             
            }    
           // supprimer la precedente select list
           $("#changements_idapplis> option").remove();
        
            i = 0;  
             var selected_appli='';
              console.log("arr cert:" + cer_arr);
             var selectedItems = $("#changements_idapplis").select2("val");
             for (key in optionData['applis']) {
                 // si appartient au changement
             if(jQuery.inArray(+ key,cer_arr) != -1){
                console.log("key in array: key=" + key);
                selected_appli='selected="selected"'; 
                  /* console.log("in array: key=" + key);*/
             selectedItems.push(key);
            }
          // on remplit les applis en f() de projet
               $("#changements_idapplis").append(  '<option label="' 
                    + optionData['applis'][key]
                    + '"' + 'value="' + key  + '"' + selected_appli + '>'
                    + optionData['applis'][key]
                    + '</option>');
                i++;
                selected_appli='';
                  /* console.log("Ajout : key=" + key);*/
                  /*$("#changements_idapplis").select2("val",  "15");
                  $("#changements_idapplis").select2("val",  "23");*/
            }
            $("#changements_idapplis").select2("val", selectedItems);
    } //Eof:: success
});  //Eof:: ajax 
 
} //Eof:: fucntion remplirSelect
    function remplirSelectStandard (dataAjax) {
   $.ajax({
         url: Routing.generate('changements_listbyprojet'),
         type: "POST", 
        data : dataAjax, 
        dataType: "json", 
        success: function(reponse){
            // Sur Succès de la réponse AJAX
            var optionData = reponse;
            var values = {selected: [], unselected:[]};
            $("#changements_idapplis > option").each(function(){
                values[this.selected ? 'selected' : 'unselected'].push(this.value);
                   console.log("this.value=" + this.value);
            });
            var cer_arr=[];
            for (key in optionData['chgmnt']) {
            cer_arr.push(optionData['chgmnt'][key]);
            }    
            $("#changements_idapplis> option").remove();
        
            i = 0;  
             var selected_appli='';
              console.log("arr cert:" + cer_arr);
             for (key in optionData['applis']) {
             if(jQuery.inArray(+ key,cer_arr) != -1){
                console.log("key in array: key=" + key);
                selected_appli='selected="selected"'; 
            }
            /* pour select2 fo effacer les champs non presents dans new projet*/
            else {
                 $("#changements_idapplis option[value='" + key + "']").remove();
            }
            
           // on remplit les applis en f() de projet
               $("#changements_idapplis").append(  '<option label="' 
                    + optionData['applis'][key]
                    + '"' + 'value="' + key  + '"' + selected_appli + '>'
                    + optionData['applis'][key]
                    + '</option>');
                i++;
                selected_appli='';
            }
    } //Eof:: success
});  //Eof:: ajax 
 
} //Eof:: fucntion remplirSelect
 
//Sur fin du chargement du document
// on charge la bonne liste
//Vid_cert = $("input#moncert_id").val();
Vid_projet = $("select#changements_idProjet").val();
 Vid_chgmnt = $("div#divchmgt").html();
/*Vid_chgmt = $("form").attr('action');
var parts = Vid_chgmt.split('/');*/
  console.log("id_projet=" + Vid_projet + "id_chgmnt=" + Vid_chgmnt);
var dataAjax = {
    id_projet:Vid_projet,
     id_changement: Vid_chgmnt
};
remplirSelect (dataAjax);
  /* $("#changements_idapplis").select2("val", ["15","23"]); */    
          
// Sur changement de l'un des 'select'
// on recharge la bonne liste
$("select#changements_idProjet").change(function(){
    Vid_projet = $("select#changements_idProjet").val();
  /*  Vid_chgmt = $("form").attr('action');*/
     Vid_chgmnt = $("div#divchmgt").html();
  
//var parts = Vid_chgmt.split('/');
  console.log("debug: id_projet=" + Vid_projet + " id_chgmnt=" + Vid_chgmnt);
    var dataAjax = {
        id_projet:Vid_projet,
        id_changement: Vid_chgmnt
    };
            
    remplirSelect (dataAjax);
  /* $("#changements_idapplis").select2("val", ["15","23"]);  */
}); //Eof:: sur changement de l'un des 'select'
 
}); //Eof:: ready
