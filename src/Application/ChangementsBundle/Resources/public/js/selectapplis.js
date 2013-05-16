$(document).ready(function() {
//$(function() {
  $( "#application_certificatsbundle_certificatscentertype_endTime" ).datepicker({
maxDate: "+3M +10D",
changeMonth: true,
changeYeat: true,
numberOfMonths: 1,
dateFormat: "yy-mm-dd",
onClose: function( selectedDate ) {
$( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
}
});
    $( "#application_certificatsbundle_certificatscentertype_addedDate" ).datepicker({
maxDate: "+3M +10D",
changeMonth: true,
changeYeat: true,
numberOfMonths: 1,
dateFormat: "yy-mm-dd",
onClose: function( selectedDate ) {
$( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
}
});
  $( "#application_certificatsbundle_certificatscentertype_startDate" ).datepicker({
maxDate: "+3M +10D",
changeMonth: true,
changeYeat: true,
numberOfMonths: 1,
dateFormat: "yy-mm-dd",
onClose: function( selectedDate ) {
$( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
}
});


function remplirSelect (dataAjax) {
   $.ajax({
        url: "{{ path('certificatscenter_listbyprojet') }}", 
        type: "POST", 
        data : dataAjax, 
        dataType: "json", 
        success: function(reponse){
            // Sur Succès de la réponse AJAX
            var optionData = reponse;
                
            // Suppression des éléments de mes listes déroulantes
            var myarr=[2,3];
            
            var values = {
  selected: [],
  unselected:[]
};

$("#moncert_idapplis > option").each(function(){
  values[this.selected ? 'selected' : 'unselected'].push(this.value);
});

var cer_arr=[];
for (key in optionData['cert']) {
    cer_arr.push(optionData['cert'][key]);
        }    
$("#moncert_idapplis> option").remove();
            i = 0;  
             var selected_appli='';
              console.log("arr cert:" + cer_arr);
             for (key in optionData['applis']) {
             if(jQuery.inArray(+ key,cer_arr) != -1){
     // the element is  in the array
     //console.log("key in array: key=" + key);
       selected_appli='selected="selected"'; 
}
/*else {
    console.log("key not in array: key=" + key);
};*/
                // on remplit les applis en f() de projet
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
//Vid_cert = $("input#moncert_id").val();
Vid_projet = $("select#moncert_project").val();
Vid_cert = $("form").attr('action');
var parts = Vid_cert.split('/');
  console.log("form=" + parts[2]);
var dataAjax = {
    id_projet:Vid_projet,
     id_cert: parts[2]
};
remplirSelect (dataAjax);
// Sur changement de l'un des 'select'
// on recharge la bonne liste
$("select#moncert_project").change(function(){
//Vid_cert = $("input#moncert_id").val();
    Vid_projet = $("select#moncert_project").val();
    Vid_cert = $("form").attr('action');
var parts = Vid_cert.split('/');
  console.log("form=" + parts[2]);

    var dataAjax = {
        id_projet:Vid_projet,
        id_cert: parts[2]
    };
            
    remplirSelect (dataAjax);

}); //Eof:: sur changement de l'un des 'select'
}); //Eof:: ready