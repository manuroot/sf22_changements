 $(document).ready(function() {
    //$(function() {
      $( "#changements_dateDebut" ).datepicker({
    maxDate: "+3M +10D",
    changeMonth: true,
    changeYear: true,
    numberOfMonths: 1,
    dateFormat: "yy-mm-dd 08:00",
    onClose: function( selectedDate ) {
    $( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
    }
    });
        $( "#changements_dateFin" ).datepicker({
    maxDate: "+3M +10D",
    changeMonth: true,
    changeYear: true,
    numberOfMonths: 1,
     dateFormat: "yy-mm-dd 17:00",
    onClose: function( selectedDate ) {
    $( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
    }
    });
      $( "#changements_dateComep" ).datepicker({
    maxDate: "+3M +10D",
    changeMonth: true,
    changeYeat: true,
    numberOfMonths: 1,
    dateFormat: "yy-mm-dd",
    onClose: function( selectedDate ) {
    $( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
    }
    });
      $( "#changements_dateVsr" ).datepicker({
    maxDate: "+3M +10D",
    changeMonth: true,
    changeYeat: true,
    numberOfMonths: 1,
    dateFormat: "yy-mm-dd",
    onClose: function( selectedDate ) {
    $( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
    }
    });
  
    $('.btn-add').click(function(event) {
        var collectionHolder = $('#' + $(this).attr('data-target'));
        var prototype = collectionHolder.attr('data-prototype');
        var form = prototype.replace(/__name__/g, collectionHolder.children().length);

        collectionHolder.append(form);

        return false;
    });
    $('.btn-remove').live('click', function(event) {
        var name = $(this).attr('data-related');
        $('*[data-content="'+name+'"]').remove();

        return false;
    });
   
}); //Eof:: ready
