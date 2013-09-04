$(document).ready(function() {
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
 /* select id="changements_idStatus"*/
}); //Eof:: ready
