$(document).ready(function() {

var mesdates=["#changements_filter_dateDebut","#changements_filter_dateFin"];
 $( "#certificats_filter_endTime_left_date" ).datepicker({
minDate: "-1Y",
maxDate: "+10Y",
changeMonth: true,
changeYear: true,
numberOfMonths: 1,
dateFormat: "yy-mm-dd",
onClose: function( selectedDate ) {
$( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
}
});
    $( "#certificats_filter_endTime_right_date" ).datepicker({
maxDate: "+10Y",
minDate: "-1Y",
changeMonth: true,
changeYear: true,
numberOfMonths: 1,
dateFormat: "yy-mm-dd",
onClose: function( selectedDate ) {
$( "#form_bis" ).datepicker( "option", "minDate", selectedDate );
}
});
function runEffect() {
// get effect type from
var selectedEffect = 'slide';
//var selectedEffect = $( "#effectTypes" ).val();
// most effect types need no options passed by default
var options = {};
// run the effect
$( "#effect" ).toggle( selectedEffect, options, 800 );
};
// set effect from select menu value
$( "#button" ).click(function() {
runEffect();
return false;
});
//$( "#effect" ).hide();

 var ShowHideBox = $('#ShowHideBox');
 //var  ShowHideButton = $('#ShowHideButton');
initBox();
//ShowHideBox.hide();
//var ShowHideBox = $('#ShowHideBox').hide();
$('#target').submit(function() {
 /* alert('Handler for .submit() called.');*/
   if ( $.cookie('Boxfiltercerts')==1){
           $.cookie('Boxfiltercerts', 0, {expires: 365});
   }
    else {
         $.cookie('Boxfiltercerts', 1, {expires: 365});
    }


  return true;
});
    $('#ShowHideButton').click(function(event) {
        event.preventDefault();
      if (boxVisible())
        {
             console.log("hidebox? box=1 status status="+boxVisible());
             hideBox();
             $(this).children().first().html('<i class="icon-search"></i>  Afficher Filtres');
             
        }
        else
        {
             console.log("box=1 status status="+boxVisible());
            showBoxEffect();
              $(this).children().first().html('<i class="icon-search"></i>  Masquer Filtres');
        }
    });

    function initBox()
    {
        
        console.log("initbox: box=1 status status="+boxVisible());
        if ( $.cookie('Boxcerts')==1)
   {
        if (!boxVisible()){
      /* console.log("initbox box=1 doit montrer la box");*/
            showBox();
             $('#ShowHideButton').children().first().html('<i class="icon-search"></i>  Masquer Filtres');
        }
        }
        else if ( $.cookie('Boxcerts')==0)
         {
            /*  console.log("initbox box=0 doit pas montrer la box");*/
             if (boxVisible()){
             
             hideBox();
              $('#ShowHideButton').children().first().html('<i class="icon-search"></i>  Afficher Filtres');
             }
        }
         if ( $.cookie('Boxfiltercerts')==1){
               console.log("filtre actif 1");
               
           
           $.cookie('Boxfiltercerts', 0, {expires: 365});
   }
    else {
          console.log("filtre inactif 0");
         $.cookie('Boxfiltercerts', 1, {expires: 365});
    }
    }  

    function boxVisible()
    {
         return ShowHideBox.hasClass('hidden')? false : true;
    }
    
    function showBoxEffect()
    {
         var effet="slide";
         var options = { };
     ShowHideBox.show(effet,options, 800).removeClass('hidden');
      $.cookie('Boxcerts', 1, {expires: 365});
       
    }
    
  function showBox()
    {
         var effet="slide";
           var options = { };
     ShowHideBox.show().removeClass('hidden');
      $.cookie('Boxcerts', 1, {expires: 365});
       
    }
  function hideBox()
    {
        var options = { };
      /*var options = { percent: 0 };*/
      var effet="explode";
       ShowHideBox.hide(effet, options, 800).addClass('hidden');
  $.cookie('Boxcerts', 0, {expires: 365});
    }
     // callback function to bring a hidden box back
function callbackboxshow() {
setTimeout(function() {
ShowHideBox.removeAttr( "style" ).hide().fadeIn();
}, 1000 );
};

}); //Eof:: ready
