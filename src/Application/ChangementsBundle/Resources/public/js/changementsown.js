$(document).ready(function() {

    var mydates = [
        "#docchangements_searchfilter_updatedAt",
        "#docchangements_searchfilter_updatedAt_max",
        "#changements_searchfilter_dateDebut",
        "#changements_searchfilter_dateFin",
        "#changements_searchfilter_dateDebut_max",
        "#changements_searchfilter_dateFin_max",
        "#certificatsfiles_searchfilter_updatedAt"
    ];
    /*var mydates=["#changements_searchfilter_dateDebut","#changements_searchfilter_dateFin"];*/
    mydates.forEach(function(entry) {
        /* console.log(entry);*/
        $(entry).datepicker({
            minDate: "-5Y",
            maxDate: "+10Y",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: "yy-mm-dd",
            showWeek: true,
            firstDay: 1,
            onClose: function(selectedDate) {
                $("#form_bis").datepicker("option", "minDate", selectedDate);
            }
        });

    });

$("a.editme").colorbox({
            iframe:true,
            transition:	"elastic",
            width:"70%",
            height:"70%",
              fastIframe:false,
            opacity:0.3
        
        });
    function runEffect() {
// get effect type from
        var selectedEffect = 'slide';
//var selectedEffect = $( "#effectTypes" ).val();
// most effect types need no options passed by default
        var options = {};
// run the effect
        $("#effect").toggle(selectedEffect, options, 800);
    }
    ;
// set effect from select menu value
    $("#button").click(function() {
        runEffect();
        return false;
    });
//$( "#effect" ).hide();


    $("#reset").click(function() {
        /*  console.log('reset bouton');*/
        $.cookie('actiffiltera', 0, {expires: 365});
    });


    $('#filter').click(function() {
        $.cookie('actiffiltera', 1, {expires: 365});
    });


    var ShowHideBox = $('#showhideboxown');
    //var  ShowHideButton = $('#ShowHideButton');
  initBox();
//ShowHideBoxown.hide();
//var ShowHideBoxown = $('#ShowHideBoxown').hide();
    $('#target').submit(function() {
        /* alert('Handler for .submit() called.');*/
        if ($.cookie('Boxfilterown') == 1) {
            $.cookie('Boxfilterown', 0, {expires: 365});
        }
        else {
            $.cookie('Boxfilterown', 1, {expires: 365});
        }


        return true;
    });
    $('#ShowHideButton').click(function(event) {
        event.preventDefault();
        if (boxVisible())
        {
            /*  console.log("hidebox? box=1 status status="+boxVisible());*/
            hideBox();
            $(this).children().first().html('<i class="icon-search icon-mesfiltres"></i>  Afficher Filtres');

        }
        else
        {
            /*  console.log("box=1 status status="+boxVisible());*/
            showBoxEffect();
            $(this).children().first().html('<i class="icon-search icon-mesfiltres"></i>  Masquer Filtres');
        }
    });

    function initBox()
    {
        if ($.cookie('actiffiltera') == 1) {
            // si filtres actifs : bouton en rouge
            $('#reset').removeClass("btn-warning").addClass("btn-danger");
            //btn btn-medium btn-warning
        }
        /*   console.log("initbox: box=1 status status="+boxVisible());*/
        if ($.cookie('Boxchangementown') == 1)
        {
            if (!boxVisible()) {
                /* console.log("initbox box=1 doit montrer la box");*/
                showBox();
                $('#ShowHideButton').children().first().html('<i class="icon-search icon-mesfiltres"></i>  Masquer Filtres');
            }
        }
        else if ($.cookie('Boxchangementown') == 0)
        {
            /*  console.log("initbox box=0 doit pas montrer la box");*/
            if (boxVisible()) {

                hideBox();
                
                $('#ShowHideButton').children().first().html('<i class="icon-search icon-mesfiltres"></i>  Afficher Filtres');
            }
        }
        if ($.cookie('Boxfilterown') == 1) {
            /* console.log("filtre actif 1");*/


            $.cookie('Boxfilterown', 0, {expires: 365});
        }
        else {
            /* console.log("filtre inactif 0");*/
            $.cookie('Boxfilterown', 1, {expires: 365});
        }
    }

    function boxVisible()
    {
        return ShowHideBox.hasClass('hidden') ? false : true;
    }

    function showBoxEffect()
    {
        var effet = "slide";
        var options = {};
        ShowHideBox.show(effet, options, 800).removeClass('hidden');
        $.cookie('Boxchangementown', 1, {expires: 365});

    }

    function showBox()
    {
        var effet = "slide";
        var options = {};
        ShowHideBox.show().removeClass('hidden');
        $.cookie('Boxchangementown', 1, {expires: 365});

    }
    function hideBox()
    {
        var options = {};
        /*var options = { percent: 0 };*/
        var effet="slide";
       /* var effet = "slide";*/
        /*ShowHideBox.hide(effet, options, 800);*/
        ShowHideBox.hide( "slide", { direction: "left" }, "slow", function(){
            $(this).addClass('hidden');
        });
                //.addClass('hidden');
        /*ShowHideBox.hide( "drop", { direction: "left" }, "slow" ).addClass('hidden');
        */
     /*   ShowHideBox.delay(2000).fadeOut(1000, function () {
   $(this).addClass('hidden');
 });*/
       /* ShowHideBox.addClass('hidden');*/
        $.cookie('Boxchangementown', 0, {expires: 365});
    }
    // callback function to bring a hidden box back
    function callbackboxshow() {
        setTimeout(function() {
            ShowHideBox.removeAttr("style").hide().fadeIn();
        }, 1000);
    }
    ;

}); //Eof:: ready

