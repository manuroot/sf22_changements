$(document).ready(function() {
    var img_path = 'bundles/applicationcertificats/images/';
    $("td > a").click(function(event) {

        /* * A modifier: change color only sur success !!*/
        if ($(this).hasClass("open") || $(this).hasClass("closed") || $(this).hasClass("prepare")) {
            var id = $(this).attr("data-id");
            console.log("id=" + id);
            var dataAjax = {id: id};

            if ($(this).hasClass("open")) {
                $(this).removeClass("open").addClass("closed");
                $(this).closest("tr").removeClass("success").addClass("myclosed");
                $(this).children().attr("src", img_path + "cadenas-sferme.png");
            }
            else if ($(this).hasClass("prepare")) {
                $(this).children().attr("src", img_path + "cadenas-souvert.png");
                $(this).removeClass("prepare").addClass("open");
                 $(this).closest("tr").removeClass("prepare").addClass("success");
            }
            //cas closed: closed ==> prepare
            else if ($(this).hasClass("closed")) {
                /*   console.log("closed test button");*/
                $(this).children().attr("src", img_path + "cadenas-bleu.png");
                $(this).removeClass("closed").addClass("prepare");
                $(this).closest("tr").removeClass("myclosed").addClass("prepare");
                /*.addClass("prepare");*/
            }
            ;
            remplirSelect(dataAjax);
        }

    });
    function remplirSelect(dataAjax) {
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
             showWeek: true,
            firstDay: 1,
        
            
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
}); //Eof:: ready