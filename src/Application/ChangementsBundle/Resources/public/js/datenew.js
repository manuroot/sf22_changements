$(document).ready(function() {

    //$(function() {
    $("#changements_dateDebut").datepicker({
        minDate: "-5Y",
        maxDate: "+10Y",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: "yy-mm-dd",
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
}); //Eof:: ready
