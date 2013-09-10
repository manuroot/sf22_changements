$(document).ready(function() {
    var img_s_path = '/bundles/applicationchangements/images/';
  
 $("#changements_dateDemande").datepicker({
        maxDate: "+5Y",
        minDate: "-5Y",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
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
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
            $("#form_bis").datepicker("option", "minDate", selectedDate);
        }
    });
    
      function format(state) {
            if (!state.id) return state.text; // optgroup
            return "<img class='flag' src='" + img_s_path + state.id.toLowerCase() + ".png'/> " + state.text;
        }
        $("#changements_idStatus").select2({
             placeholder: "-- Choisir Statut(s) --",
                allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function(m) { return m; }
        });
        
        
     $("select#changements_idapplis").select2({
                placeholder: "-- Choisir Application(s) --",
                allowClear: true
            });
             $("select#changements_idusers").select2({
                placeholder: "-- Choisir User(s) --",
                allowClear: true
            });
        
               $("#changements_demandeur").select2(); 
                $("#changements_searchfilter_idProjet").select2({
                placeholder: "-- Choisir Projet(s) --",
                allowClear: true
            });
           
           
              $("#changements_idProjet").select2({
                placeholder: "-- Choisir Projet(s) --",
                allowClear: true
            });
}); //Eof:: ready
