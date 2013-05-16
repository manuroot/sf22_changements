//Remplir les select du formulaire en fonction de la réponse AJAX
function remplirSelect (dataAjax) {
    //TST
    //alert("coucou");
    // Envoi requête AJAX   	
    $.ajax({
        url: baseUrl + "/chrono/remplir", 
        type: "POST", 
        data : dataAjax, 
        dataType: "json", 
        success: function(reponse){
            // Sur Succès de la réponse AJAX
            var optionData = reponse;
            // Suppression des éléments de mes listes déroulantes
            $("#moncert_idapplis> option").remove();
            i = 0;  
            // console.log("defapp=" + optionData['def_application']);
            var selected_appli='';
             for (key in optionData['applis']) {
                //      console.log("defapp=" + optionData['def_application']);
                // console.log("key=" + key);
                if (key==optionData['def_application']){
                    selected_appli='selected="selected"'; 
                }
                //console.log("key=def=" + key);
                $("#moncert_idapplis").append(  '<option label="' 
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
$(document).ready( function() {
Vid_chrono = $("input#id").val();
Vid_projet = $("select#moncert_projet").val();
var dataAjax = {
    id_projet:[Vid_projet]
        
};
remplirSelect (dataAjax);
      
          
// Sur changement de l'un des 'select'
// on recharge la bonne liste
$("select#moncert_projet").change(function(){
    Vid_projet = $("select#moncert_projet").val();
    var dataAjax = {
        id_projet:[Vid_projet]
    };
            
    remplirSelect (dataAjax);
 
}); //Eof:: sur changement de l'un des 'select'

 

}); //Eof:: ready



 