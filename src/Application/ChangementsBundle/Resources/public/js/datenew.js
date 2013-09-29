$(document).ready(function() {
    var img_s_path = '/bundles/applicationchangements/images/';

    $("#changements_dateDemande").datepicker({
        maxDate: "+5Y",
        minDate: "-5Y",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        showWeek: true,
        firstDay: 1,
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
            $("#form_bis").datepicker("option", "minDate", selectedDate);
        }
    });
    //$(function() {
    $("#changements_dateDebut").datepicker({
        minDate: "-5Y",
        maxDate: "+10Y",
        changeMonth: true,
        changeYear: true,
        showWeek: true,
        firstDay: 1,
        numberOfMonths: 1,
        dateFormat: "yy-mm-dd 08:00",
        onClose: function(selectedDate) {
            $("#form_bis").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#changements_dateFin").datepicker({
        maxDate: "+5Y",
        minDate: "-5Y",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        showWeek: true,
        firstDay: 1,
        dateFormat: "yy-mm-dd 17:00",
        onClose: function(selectedDate) {
            $("#form_bis").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#changements_dateComep").datepicker({
        maxDate: "+5Y",
        minDate: "-5Y",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        showWeek: true,
        firstDay: 1,
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
            $("#form_bis").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#changements_dateVsr").datepicker({
        maxDate: "+5Y",
        minDate: "-5Y",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        showWeek: true,
        firstDay: 1,
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
            $("#form_bis").datepicker("option", "minDate", selectedDate);
        }
    });

    function format(state) {
        if (!state.id)
            return state.text; // optgroup
        return "<img class='flag' src='" + img_s_path + state.id.toLowerCase() + ".png'/> " + state.text;
    }
    $("#changements_idStatus").select2({
        placeholder: "-- Choisir Statut(s) --",
        allowClear: true,
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) {
            return m;
        }
    });


    $("select#changements_idapplis").select2({
        placeholder: "-- Choisir Application(s) --",
        allowClear: true
    });
      $("#changements_idProjet").select2({
        placeholder: "-- Choisir Projet(s) --",
        allowClear: true
    });
    $("select#changements_idusers").select2({
        placeholder: "-- Choisir User(s) --",
        allowClear: true
    });
$("select#changements_idEnvironnement").select2({
        placeholder: "-- Choisir Environnement(s) --",
        allowClear: true
    });
    $("#changements_demandeur").select2();
    $("#changements_searchfilter_idProjet").select2({
        placeholder: "-- Choisir Projet(s) --",
        allowClear: true
    });

$('#changements_idStatus').change(function()
{
        console.log("this.value=" + $(this).val());
        var test_status=$(this).val();
    if (test_status == 2){
        if ($("#changements_dateFin[required!='required']")){
           if ($('#changements_dateFin:text').val().length == 0){
           $('#changements_dateFin').attr('required','required');
              bootbox.alert("Le champs DateFin est obligatoire (changement fermé) !");
            /*alert('Le champs DateFin est obligatoire (changement fermé)');*/
            $("#changements_dateFin_control_group > label").addClass('leserreurs');

        }
        }
    }
    else {
        if ( $("#changements_dateFin_control_group > label").hasClass('leserreurs')){
              $("#changements_dateFin_control_group > label").removeClass('leserreurs');
        }
        $('#changements_dateFin').removeAttr('required');
    }
       /* if($('#changements_dateFin').hasAttribute('required')){
    alert('true');   
} else {
    alert('false');   
}
       $('#changements_dateFin').prop('required',true);
alert('Value change to ' +  test_status);
    }*/
 /*   alert('Value change to ' +  $(this).val());*/
   /*  var theID = $('#changements_idStatus').select2('data').id;
    var theSelection = $(test).select2('data').text;
    $('#selectedID').text(theID);
    $('#selectedText').text(theSelection);*/
});
  
}); //Eof:: ready
