$(document).ready(function() {
    var img_path = '/bundles/applicationchangements/images/';
    var img_s_path = 'bundles/applicationchangements/images/';

$.fn.extend({
    hasClasses: function (selectors) {
        var self = this;
        for (i in selectors) {
            if ($(self).hasClass(selectors[i])) 
                return true;
        }
        return false;
    }
});


    $("td > a").click(function(event) {
       if ($(this).hasClass("favoris")) {
            var id = $(this).attr("data-id");
            var name = $(this).attr("data-name");
            var message = "";
            var new_status = 0;
            var img_favori = "";
            var modal_res = "";
            var status = $(this).attr("data-status");
            console.log("favoris id=" + id);
            if (status === 1) {
                message = "Supprimer de ";
                img_favori = "star-off.png";

            }

            else {
                message = "Ajouter a ";
                img_favori = "star-on.png";
                new_status = 1;
            }

              var checkstr =  confirm(message + " vos favoris: \nstatus=" + status + "\nid=" + id + "\nnom=" + name);
             if (checkstr === true) {
                $(this).children().attr("src", img_path + img_favori);
                $(this).attr('data-status', new_status);
                /*$(this).data('data-status',new_status);*/
                var dataAjax = {id: id};
                return true;
            }
            else {
                return false;
            }
            /*});*/
        }
        /* * A modifier: change color only sur success !!*/
        if ($(this).hasClasses(['open', 'closed', 'prepare'])){
        /*if ($(this).hasClass("open") || $(this).hasClass("closed") || $(this).hasClass("prepare")) {*/
            id = $(this).attr("data-id");
            name = $(this).attr("data-name");
            checkstr = confirm("Modifier le status de la demande: \nid=" + id + "\nnom=" + name);
            if (checkstr === true) {
                console.log("id=" + id);
                dataAjax = {id: id};

                remplirSelect(dataAjax,$(this));
            }
        }
    });
    
     function changerfavoris(dataAjax,obj) {
        $.ajax({
            url: Routing.generate('changements_updatexhtml_changement'),
            /*  url: "{{ path('changements_updatexhtml_changement') }}", */
            type: "POST",
            data: dataAjax,
            dataType: "json",
              success: function(reponse) {
            },
            error: function(e) {
                console.log(e.message);
            }
        });  //Eof:: ajax 

    } //Eof:: fucntion remplirSelect

    function remplirSelect(dataAjax,obj) {
        $.ajax({
            url: Routing.generate('changements_updatexhtml_changement'),
            /*  url: "{{ path('changements_updatexhtml_changement') }}", */
            type: "POST",
            data: dataAjax,
            dataType: "json",
            /* ie8 ??*/
            /*cache: false,*/
            /*contentType: 'application/json',*/
            success: function(reponse) {
            
                if (obj.hasClass("open")) {
                    obj.removeClass("open").addClass("closed");
                    obj.closest("tr").removeClass("success").addClass("myclosed");
                    obj.children().attr("src", img_path + "cadenas-sferme.png");
                }
                else if (obj.hasClass("prepare")) {
                    obj.children().attr("src", img_path + "cadenas-souvert.png");
                    obj.removeClass("prepare").addClass("open");
                    obj.closest("tr").removeClass("prepare").addClass("success");
                }
                //cas closed: closed ==> prepare
                else if (obj.hasClass("closed")) {
                    /*   console.log("closed test button");*/
                    obj.children().attr("src", img_path + "cadenas-sbleu.png");
                    obj.removeClass("closed").addClass("prepare");
                    obj.closest("tr").removeClass("myclosed").addClass("prepare");
                    /*.addClass("prepare");*/
                }
                ;
            },
            error: function(e) {
                console.log(e.message);
            }
        });  //Eof:: ajax 

    } //Eof:: fucntion remplirSelect


    // declare freeDays global
    var eventsDays = [];
    var eventsTitle = [];

    function fetchEvents(year, month) {
        /* console.log("month:" + month);*/
        var dataAjax = {
            'year': year,
            'month': month
        };

        $.ajax({
            async: false,
            url: Routing.generate('changements_minicalendar'),
            /*url: "{{ path('epost_calendar') }}",*/
            type: "POST",
            dataType: "json",
            data: dataAjax,
            success: function(reponse) {
                var optionData = reponse;
                // loop over dayUsage array result
                $.each(optionData, function(index, value) {
                    /*   console.log("added value=" + value.date);*/
                    /*  eventsDays.push(value.date,value.title); */
                    eventsDays.push(value.date);
                    eventsTitle.push(value.title + ' (' + value.date + ')');
                    // add this date to the freeDays array
                });
                /* highlightDays(date);*/
                /* $('#datepicker').trigger('click');*/
            }
        });
    }



    function dateToYMD(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1;
        var y = date.getFullYear();
        return '' + y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
    }
    /*var eventsDays = ['05/01/2013', '05/11/2013'];*/
    fetchEvents();

    function pickDate(dateStr) {
        // Do something with the chosen date...
        window.location.href = links[dateStr];
    }
    /*
     $('.selector').datepicker({
     onSelect: function(dateText, inst) {
     
     window.location.href = '/go-to-page?date='+dateText;
     }
     }); */
    $("#minidatepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        //numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        /*altField: '#date_due',
         altFormat: 'yy-mm-dd',*/
        showWeek: true, firstDay: 1,
        beforeShowDay: editDays,
        /* onSelect: function(dateText, inst) {
         var url = Routing.generate('epost_index');
         window.location.href = url + '/' + dateText;
         
         },*/
        onChangeMonthYear: fetchEvents
                /* create: function(event, ui) {
                 fetchEvents()
                 },*/
                /*  onLoad:fetchEvents,*/

    });

    function editDays(date) {
        /* console.log("edit days");*/
        for (var i = 0; i < eventsDays.length; i++) {
            /* var t=new Date(eventsDays[i]).toString("mm-dd-yy");*/
            var t = new Date(eventsDays[i]).toString();
            /* console.log("events=" + eventsDays[i] + "--" + t + " date=" + date.toString() + " i=" + i);
             */
            /*    if (t == date.toString()) {     */
            if (new Date(eventsDays[i]).toString() === date.toString()) {
                return [true, 'free-day', eventsTitle[i]];
            }
        }
        return [true];
    }

    function format(state) {
        if (!state.id)
            return state.text; // optgroup
        return "<img class='flag' src='" + img_s_path + state.id.toLowerCase() + ".png'/> " + state.text;
    }
    $("#changements_searchfilter_idStatus").select2({
        placeholder: "-- Choisir Statut(s) --",
        allowClear: true,
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) {
            return m;
        }
    });
    $("#changements_searchfilter_idStatus_cl").click(function() {
        $("#changements_searchfilter_idStatus").select2("val", "");
    });
    /*
     $("#changements_searchfilter_idStatus").on("change", function() { $("##changements_searchfilter_idStatus_val").html($("#changements_searchfilter_idStatus").val());});
     
     $("#changements_searchfilter_idStatus").select2("container").find("ul.select2-choices").sortable({
     containment: 'parent',
     start: function() { $("#changements_searchfilter_idStatus").select2("onSortStart"); },
     update: function() { $("#changements_searchfilter_idStatus").select2("onSortEnd"); }
     });*/




    $("#changements_searchfilter_idProjet").select2({
        placeholder: "-- Choisir Projet(s) --",
        allowClear: true
    });
    $("#changements_searchfilter_idProjet_cl").click(function() {
        $("#changements_searchfilter_idProjet").select2("val", "");
    });


    $("#changements_searchfilter_idEnvironnement").select2({
        placeholder: "-- Choisir Environnement(s) --",
        allowClear: true
    });
    $("#changements_searchfilter_idEnvironnement_cl").click(function() {
        $("#changements_searchfilter_idEnvironnement").select2("val", "");
    });


    $("#changements_searchfilter_idusers").select2({
        placeholder: "-- Choisir User(s) --",
        allowClear: true
    });
    $("#changements_searchfilter_idusers_cl").click(function() {
        $("#changements_searchfilter_idusers").select2("val", "");
    });

    $("#changements_searchfilter_demandeur").select2();

}); //Eof:: ready