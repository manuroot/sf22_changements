$(document).ready(function() {
    $('.star').raty({
        readOnly: true,
        score: function() {
            return $(this).attr('data-score');
        }
    });
                                                                                                           
});

 
 