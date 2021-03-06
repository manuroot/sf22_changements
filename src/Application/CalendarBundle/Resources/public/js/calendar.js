 /* jQuery.noConflict();*/
    $(document).ready(function() {
        var xcolors = ["#94a2be", "#993355", "#aaaaaa", "#f2a640", "#b373b3", "#668cd9"];
        // console.log("edit status="+edit_status);
        var resize_status = edit_status;
        var edit_status = "{{ editstatus }}";

        var plage = "{{ plage|escape('js') }}";
        var starthour = "{{ starthour|escape('js') }}";
        var endhour = "{{ endhour|escape('js') }}";
        console.log("plage horaire=" + plage);
        console.log("edit status=" + edit_status);
        var t = [];
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        /* if (!$('#events').is(':checked')) { // Note "!" sign here (this indicates that the condition is inverted)
         $(element).removeEvents("rto")          
         }
         */
        
      
        $("#datepicker").datepicker({
            dateFormat: 'yy/mm/dd',
            // While using year and month change I prefer to use inline  date picker  like (  <div id="datepicker"></div>   )
            changeMonth: true,
            changeYear: true,
            onChangeMonthYear: function(year, month, inst) {
                var date = new Date();
                console.log("month=" + month);
                $('#calendar-holder').fullCalendar('gotoDate', year, month - 1, date.getDate());
            },
            onSelect: function(dateText, inst) {
                var date = new Date(dateText);
                console.log("month=" + date.getMonth());
                $('#calendar-holder').fullCalendar('gotoDate', date.getFullYear(), date.getMonth(), date.getDate());
                /*  $('#calendar-holder').fullCalendar('gotoDate', date);*/
            }
        });
        /*$(this).find('.fc-event-time').addClass('newclass');*/
        $('#external-events div.external-event').each(function() {
            //create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end

            var mybg = $(this).css('background-color');
            console.log("mybg="+mybg);
            var myborder = $.xcolor.darken(mybg, 3);
            $(this).css('border-color', myborder);

            var eventObject = {
                title: $.trim($(this).text()), // use the element's text as the event title
                'backgroundcolor': $(this).css("background-color"),
                'myclass': $(this).attr("myclass")
            };
            /* console.log("class=" + eventObject.myclass);*/
            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true, // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });
        $('#calendar-holder').fullCalendar({
            theme: true,
            firstDay: 1,
            minTime: parseInt(starthour),
            maxTime: parseInt(endhour),
            slotMinutes: parseInt(plage),
            defaultEventMinutes: 120,
            editable: edit_status,
            selectable: edit_status,
            selectHelper: true,
            eventStartEditable: edit_status,
            eventDurationEditable: edit_status,
            droppable: edit_status,
            hiddenDays: [],
            unselectAuto: false,
            /*  height: 650,*/
            defaultView: 'month',
            header: {
                left: 'prev, next',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            /* fetch 1 time pour le mois entier*/
            lazyFetching: true,
            timeFormat: {
                agenda: 'H:mm{ - H:mm}'
            },
            eventSources: [
                {
                    url: Routing.generate('calendar_adesignajax'),
                    type: 'POST',
                    eventBorderColor: "red",
                    textColor: 'black' // a non-ajax option*/
                }

                /* ,{
                 url: Routing.generate('calendar_adesignajax_absences'), 
                 type: 'POST',
                 error: function() {
                 //alert('There was an error while fetching Google Calendar!');
                 },
                 startEditable: false, 
                 durationEditable :false,
                 editable: false,
                 selectable: false,
                 allDayDefault : true,
                 color: '#668CD9',   // a non-ajax option
                 textColor: 'black' // a non-ajax option
                 }  */
            ],
            eventRender: function(event, element, view) {
                if (!event.description)
                    event.description = "--";
                element.attr("description", event.description);
                var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");

                var bg = element.css("background-color");
                var h = $(element).css('height').replace(/[^-\d\.]/g, '');
                var w = $(element).css('width').replace(/[^-\d\.]/g, '');
                // console.log("height="+h+" width="+w);
                var longueur = h * 2;
                if (event.allDay === false && (view.name === "agendaDay" || view.name === "agendaWeek")) {
                    element.find(".fc-event-title")
                            .append("<br>" + event.description.substring(0, longueur));
                }
                //todo : description as height
                //
                // console.log("render bgcolor="+bg);

                // var t="#fffddd";
                // Darken
//var t = LightenDarkenColor(bg, -50);
                // t=t.toRgbString();
                /*  var t = tinycolor.darken(bg).toRgbString();*/
                var t = $.xcolor.darken(bg, 3);
                //     console.log("color="+t);

                element.css('border-color', t);
                element.find("div.fc-event-time").css('background-color', t);
                if (event.origine !== "absence")
                    element.find("div.fc-event-time").prepend(" <a class=\"editme\" href=\"#\"><i class=\"icon-wrench\"></i></a> ");
                /*  var desc="Pas de description";
                 if (event.description =="") desc=event.description;*/
                /*  element.find(".fc-event-time").after($("<span class=\"fc-event-icons\"></span>").html("Whatever you want the content of the span to be"));
                 */
                var text = "<p>" + event.title + '</p><p>Start: ' + start + '<br/>End: ' + end + '</p>';
                /*    element.qtip({
                 content: {
                 text    : event.description,
                 title   : {
                 text    : text
                 
                 }
                 },
                 position: {
                 my : "top center",
                 at : "bottom center",
                 container : false,
                 viewport : $('#calendar-holder'),
                 
                 adjust: {method: 'shift' 
                 }
                 },
                 style: { 
                 name: 'blue',
                 border: {
                 width: 2,
                 radius: 2,
                 color: '#6699CC'
                 },
                 width: 300
                 },
                 hide: {
                 fixed: true
                 
                 }
                 }); 
                 */

                /*.html("Whatever you want the content of the span to be"));*/
            },
            /* beforeViewRender: function(view) {
             $('.fc-event').popover('hide');
             },*/
            /*==================================
             * 
             * Ajout event 
             ==================================*/
            select: function(start, end, allDay) {
                var eventobjet = {'start': start, 'end': end, 'allDay': allDay};
                $("#titreheader").html("Ajouter un Evenement");
                console.log("select function: allday=--" + allDay + '--');
                remplirForm(eventobjet);
                $('#createEventModal').modal('show');
                $("#titleName").focus();
                $("#submitButton").click(function(event) {
                    doSubmit(event);
                    initForm();
                });
                $("#cancel-button,button.close").click(function(event) {
                    initForm();
                });
                /*  $('#createEventModal').modal('hide');*/
            },
            /*===================================
             * 
             * Deplacer Event
             ====================================*/
            eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc) {
                console.log("drop: move event");
                updateajax('calendar_dropajax', event, event['allDay'], function(result) {
                    console.log("event single drop result=" + result);
                });
            },
            /*===================================
             * 
             * CLiker/Editer Event
             ====================================*/

            eventClick: function(calEvent, jsEvent, view) {
                console.log("event click");
                if (edit_status === false || !edit_status)
                    return;
                if (calEvent.origine === "absence")
                    return;
                console.log("id after click=" + calEvent.id);
                var border = $(this).css('border-color');
                $(this).css('border-color', 'red');
                var start = $.fullCalendar.formatDate(calEvent.start, "yyyy-MM-dd HH:mm:ss");
                var end = $.fullCalendar.formatDate(calEvent.end, "yyyy-MM-dd HH:mm:ss");
                /*var boxContentString=$("div#createEventModal").html();*/
                var boxContentString = "ID=" + calEvent.id + " infos:<br>TITRE: " + calEvent.title +
                        "<br>Description:<br>" + calEvent.description + "<br>" +
                        "allday=" + calEvent.allDay + "<br>start=" + start + "<br>end=" + end;
                // plus passer par css
                // var color_index=0;
                var cssclasscolor = calEvent.className[0];

                var fgcolor = calEvent.textColor;       
               // fgcolor = "#dedede";       
                $('#colorfont').val(fgcolor);
                 
                var bgcolor = calEvent.backgroundColor;
                console.log("recup color=" + bgcolor + " font color="+fgcolor);
               // $('#color').val(bgcolor);
                // color en cours
                $(".colorbg div.colorPicker-picker").css('background-color', bgcolor);
                
                 $(".colorfont div.colorPicker-picker").css('background-color', fgcolor);
                bootbox.dialog(boxContentString, [
                    //buttons
                    {
                        "label": "Edit",
                        "class": "btn-success",
                        "callback": function(e) {
                            $("#titreheader").html("Editer un Evenement (id=" + calEvent.id + ")");
                            remplirForm(calEvent);
                            $('#createEventModal').modal('show');
                            $("#submitButton").unbind('click');
                            $("#submitButton").click(function(e) {
                                console.log("SUBMIT BUTTON");
                                console.log("id before dosubmit call=" + calEvent.id);
                                doSubmit(calEvent);
                                console.log("END SUBMIT BUTTON");
                                //  $('#submit-event-update').unbind();
                                /*    console.log("id after dosubmit call="+ calEvent.id);*/

                                initForm();
                            });
                            $("#cancel-button").click(function(event) {
                                //   $('#calendar-holder').fullCalendar('unselect');
                                initForm();
                            });
                        }

                    },
                    {
                        "label": "Delete",
                        "class": "btn-danger",
                        "callback": function(e) {
                            var dataAjax = {'id': calEvent.id};

                            deleteajax('calendar_dropajax', dataAjax, function(result) {

                                console.log("id delete=" + calEvent.id);
                                $('#calendar-holder').fullCalendar('removeEvents', calEvent.id);


                            });
                        }
                    },
                    {
                        "label": "Cancel",
                        "class": "btn-default",
                        "callback": function() {
                            // Cancel function
                        }
                    }
                ]);
                //$('#calendar-holder').fullCalendar('updateEvent',calEvent);
                //  $(this).find("div.fc-event-time").prepend(" <a class=\"editme\" href=\"#\"><i class=\"icon-wrench\"></i></a> ");
                $(this).css('border-color', border);
            },
            /*=================================
             *  DROP EXTERNAL EVENT
             * 
             ==================================*/

            drop: function(date, allDay) { // this function is called when something is dropped
                var tempDate = new Date(date);  //clone date
                var originalEventObject = $(this).data('eventObject');
                var copiedEventObject = $.extend({}, originalEventObject);
                console.log("drop start=" + date);
                var title = copiedEventObject['title'];
                var bg = copiedEventObject['backgroundcolor'];
                start = $.fullCalendar.formatDate(date, "yyyy-MM-dd HH:mm:ss");
                end = new Date(tempDate.setHours(tempDate.getHours() + 2));
                end = $.fullCalendar.formatDate(end, "yyyy-MM-dd HH:mm:ss");
                console.log("Drop function titre?=" + copiedEventObject['title']);
                if (title) {
                    var dataAjax = {
                        'title': title,
                        'start': start,
                        'end': end,
                        'backgroundColor': bg,
                        'textColor':"#ffffff",
                        'className': copiedEventObject['myclass'],
                        'allDay': allDay
                    };
                    createajax('calendar_dropajax', dataAjax, allDay, function(result) {
                        console.log("result=" + result['data']['id']);
                        dataAjax['id'] = result['data']['id'];
                        $('#calendar-holder').fullCalendar('renderEvent', dataAjax, false);
                    });
                }
                if ($('#drop-remove').is(':checked')) {
                    $(this).remove();
                }
            },
            /*=================================
             *  EVENT RESIZE
             * 
             ==================================*/
            eventResize: function(event, dayDelta, minuteDelta, revertFunc) {
                updateajax('calendar_dropajax', event, event['allDay'], function(result) {
                    console.log("resize event");
                });
            }
        });

        /*=================================
         *  REMPLIR FORM
         * 
         ==================================*/

        function remplirForm(event) {
            console.log("REMPLIR FORMULAIRE");
            var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
            console.log("start=" + start);
            if (event.title)
                $("#titleName").val(event.title);
            $('#apptStartTime').val(start);
            $('#apptEndTime').val(end);
            //$('#createEventModal').modal('show');
            var mywhen = start + ' - ' + end;
            $('#createEventModal #when').text(mywhen);
            console.log("ok allDay=" + event.allDay);
            $('#apptAllDay').val(event.allDay);
            if (event.id)
                $('#apptId').val(event.id);
            if (event.description)
                $("#descriptionName").val(event.description);
            return true;
        }

        /*=================================
         *  SUBMIT FORM
         * 
         ==================================*/

        function doSubmit(event) {
            console.log("submit1 button in doSubmit");
            console.log("dosubmit id?=" + event.id);
            /* event.preventDefault();*/
            $('#createEventModal').hide();
            console.log("submit button");
            var title = $("#titleName").val();
            var description = $("#descriptionName").val();
            var mstart = $('#apptStartTime').val();
            var mend = $('#apptEndTime').val();
            // color en hexa via colorpicker:
            var csscolor = $('#color').val();
            var textcolor = $('#colorfont').val();
            var id = $('#apptId').val();
            //recuperation de la classe associée a la couleur
            // var color_index=getcolor(csscolor);
            var cssclass = 'class0';
            //  if (color_index != -1){cssclass='class' + color_index;
            // console.log("FOUND COLOR INDEX= " + cssclass);
            //  }
            // console.log("id=" + id + " color=" + csscolor + " class=" + cssclass);

            console.log("id=" + id + " color=" + csscolor);
            var mallDay = $('#createEventModal #apptAllDay').val();
            if (title) {
                console.log("allday=" + mallDay + " mstart=" + mstart + " end=" + mend);
                var dataAjax = {
                    'title': title,
                    'className': cssclass,
                    'backgroundColor': csscolor,
                    'textColor': textcolor,
                    'description': description,
                    'start': mstart,
                    'end': mend,
                    'id': id,
                    'allDay': ($('#apptAllDay').val() == "true")
                };
                if (id) {
                    console.log("CAS 1 EDIT");
                    //updateajax(route,event,allday,callback) {
                    console.log("dosubmit Appel updateAjax id=" + id);
                    createajax('calendar_dropajax', dataAjax, mallDay, function(result) {
                        dataAjax['id'] = result['data']['id'];
                        $('#calendar-holder').fullCalendar('removeEvents', id);
                        $('#calendar-holder').fullCalendar('renderEvent', dataAjax, false);
                    });
                }
                else {
                    console.log("CAS 2 NEW");
                    createajax('calendar_dropajax', dataAjax, mallDay, function(result) {
                        dataAjax['id'] = result['data']['id'];
                        $('#calendar-holder').fullCalendar('renderEvent', dataAjax, false);
                    });
                }
            }
        }

        function initForm() {
            $('#calendar-holder').fullCalendar('unselect');
            $('#createEventModal').modal('hide');
            $("#titleName").val("");
            $("#descriptionName").val("");
            $('#apptId').val("");
            $('#color').val("");
            $('#colorfont').val("");
            $(".color div.colorPicker-picker").css('background-color', getcolorById(0));
            $('#calendar-holder').fullCalendar('unselect');
        }
        /*========================================================
         *  Fonction: Ajaxs
         ========================================================*/

        function updateajax(route, event, allday, callback) {
            console.log("update ajax");


            start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
            end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");

            // Shallow copy
            var newObject = jQuery.extend({}, event);
            newObject.start = start;
            newObject.end = end;
            newObject.className = event.className[0];
            console.log("updateajax start=" + event.start);
            console.log("updateajax end=" + event.end);
            var datareponse;
            //if (!event.id) {var id=null;}else {var id=event.id;}
            $.ajax({
                async: false,
                url: Routing.generate(route),
                type: "POST",
                dataType: "json",
                data: newObject,
                success: function(reponse) {
                    datareponse = reponse;

                },
                error: function(e) {
                    console.log(e.message);
                    datareponse = e.message;

                }
            });
            /* return datareponse;*/
            callback(datareponse);

        } //Eof:: fucntion remplirSelect



        /*========================================================
         *  Fonction: Delete AJAX
         ========================================================*/

        function deleteajax(route, event, callback) {
            var obj = $(this);
            var datareponse;
            if (!event.id) {
                var id = null;
            } else {
                var id = event.id;
            }
            var dataAjax = {
                'id': id, action: 'delete'
            };
            console.log("delete ajax");
            $.ajax({
                async: false,
                url: Routing.generate(route),
                type: "POST",
                dataType: "json",
                data: dataAjax,
                success: function(reponse) {
                    datareponse = reponse;

                },
                error: function(e) {
                    console.log(e.message);
                    datareponse = e.message;

                }
            });
            callback(datareponse);

        } //Eof:: fucntion remplirSelect

        function View(datab)
        {
            var str = "Informations sur l\'evenement<br>";
            var id = datab['id'];
            /* console.log("id=" + id);*/
            /*    http://symvideo:92/452/showxhtml*/
            var url = Routing.generate('calendar_showXhtml', {id: id});
            /*var dataAjax = {'id': id};*/
            $.colorbox({
                iframe: true,
                transition: "elastic",
                width: "600px",
                height: "600px",
                fastIframe: false,
                opacity: 0.3,
                href: url
                        /* onClosed:function(){
                         $("#gridcontainer").reload();
                         }*/
            });


        }

        /*------------------------------------------
         * 
         * EDIT WINDOW
         *
         ------------------------------------------*/

        function Edit(data)
        {
            if (data)
            {
                var id = data[0];
                var url = Routing.generate('calendar_edit', {id: id});

                /*  var url = Routing.generate('calendar_showXhtml', {id: id});*/
                /*var dataAjax = {'id': id};*/
                $.colorbox({
                    iframe: true,
                    transition: "elastic",
                    width: "80%",
                    height: "80%",
                    fastIframe: false,
                    opacity: 0.3,
                    href: url,
                    onClosed: function() {
                        $("#gridcontainer").reload();
                    }
                });
            }
        }

        /*========================================================
         *  Fonction: Ajaxs
         ========================================================*/

        function createajax(route, data, allday, callback) {
            var obj = $(this);
            var datareponse;
            console.log("create ajax request");
            /*start=data['start'];
             end=data['end'];*/
            /*  start = $.fullCalendar.formatDate(data['start'], "yyyy-MM-dd HH:mm:ss");
             end = $.fullCalendar.formatDate(data['end'], "yyyy-MM-dd HH:mm:ss");*/

            $.ajax({
                async: false,
                url: Routing.generate(route),
                type: "POST",
                dataType: "json",
                /* data: dataAjax,*/
                data: data,
                success: function(reponse) {

                    datareponse = reponse;
                    var id = reponse['data']['id'];
                    console.log("in success id=" + id);


                },
                error: function(e) {
                    console.log(e.message);
                    datareponse = e.message;

                }
            });
            /* $('#calendar-holder').fullCalendar('renderEvent', dataAjax, true);*/
            /* return datareponse;*/
            callback(datareponse);

        } //Eof:: fucntion remplirSelect


        /* $('#color1').colorPicker(
         {
         pickerDefault : "FFFFFF",
         
         // Default color set.
         colors : [
         '000000', '993300', '333300', '000080', '333399', '333333', '800000', 'FF6600',
         '808000', '008000', '008080', '0000FF', '666699', '808080', 'FF0000', 'FF9900'
         
         ]
         });*/
        var t1 = ["#ff000", "#330000", "#660000", "#990000", "#cc0000", "#000000",
            "94A2BE", "993355", "aaaaaa", "f2a640", "b373b3", "668cd9",
            '000000', '993300', '333300', '000080', '333399', '333333',
            '800000', 'FF6600', '808000', '008000', '008080', '0000FF',
            '666699', '808080', 'FF0000', 'FF9900', '99CC00', '339966',
            '33CCCC', '3366FF', '800080', '999999', 'FF00FF', 'FFCC00',
            'FFFF00', '00FF00', '00FFFF', '00CCFF', '993366', 'C0C0C0',
            'FF99CC', 'FFCC99', 'FFFF99', 'CCFFFF', '99CCFF', 'FFFFFF'];
        /* t=["FFFFFF", "FFDFDF", "FFBFBF","FF9F9F", "FF7F7F","FF5F5F","FF3F3F","FF1F1F","FF0000","DF1F00","C33B00","A75700",
         "8B7300",    "6F8F00",    "53AB00",    "37C700", "1BE300",    "00FF00" ,    "00DF1F",    "00C33B",
         "00A757", "008B73" , "006F8F","0053AB",    "0037C7",    "001BE3",    "0000FF",    "0000df",
         "0000c3",    "0000a7" , "00008b","00006f",    "000053",    "000037",    "00001b",    "000000"];*/
        /* t = [
         "94A2BE","993355","aaaaaa","f2a640","b373b3","668cd9",
         '000000', '993300', '333300', '000080', '333399', '333333', 
         '800000', 'FF6600', '808000', '008000', '008080', '0000FF',
         '666699', '808080', 'FF0000', 'FF9900', '99CC00', '339966', 
         '33CCCC', '3366FF', '800080', '999999', 'FF00FF', 'FFCC00',
         'FFFF00', '00FF00', '00FFFF', '00CCFF', '993366', 'C0C0C0',
         'FF99CC', 'FFCC99', 'FFFF99', 'CCFFFF', '99CCFF', 'FFFFFF',
         '990033', 'ff3366', 'cc0033', 'ff0033', 'ff9999', 'cc3366', 'ffccff', 'cc6699',
         '993366', '660033', 'cc3399', 'ff99cc', 'ff66cc', 'ff99ff', 'ff6699', 'cc0066',
         'ff0066', 'ff3399', 'ff0099', 'ff33cc', 'ff00cc', 'ff66ff', 'ff33ff', 'ff00ff',
         'cc0099', '990066', 'cc66cc', 'cc33cc', 'cc99ff', 'cc66ff', 'cc33ff', '993399',
         'cc00cc', 'cc00ff', '9900cc', '990099', 'cc99cc', '996699', '663366', '660099',
         '9933cc', '660066', '9900ff', '9933ff', '9966cc', '330033', '663399', '6633cc',
         '6600cc', '9966ff', '330066', '6600ff', '6633ff', 'ccccff', '9999ff', '9999cc',
         '6666cc', '6666ff', '666699', '333366', '333399', '330099', '3300cc', '3300ff',
         '3333ff', '3333cc', '0066ff', '0033ff', '3366ff', '3366cc', '000066', '000033',
         '0000ff', '000099', '0033cc', '0000cc', '336699', '0066cc', '99ccff', '6699ff',
         '003366', '6699cc', '006699', '3399cc', '0099cc', '66ccff', '3399ff', '003399',
         '0099ff', '33ccff', '00ccff', '99ffff', '66ffff', '33ffff', '00ffff', '00cccc',
         '009999', '669999', '99cccc', 'ccffff', '33cccc', '66cccc', '339999', '336666',
         '006666', '003333', '00ffcc', '33ffcc', '33cc99', '00cc99', '66ffcc', '99ffcc',
         '00ff99', '339966', '006633', '336633', '669966', '66cc66', '99ff99', '66ff66',
         '339933', '99cc99', '66ff99', '33ff99', '33cc66', '00cc66', '66cc99', '009966',
         '009933', '33ff66', '00ff66', 'ccffcc', 'ccff99', '99ff66', '99ff33', '00ff33',
         '33ff33', '00cc33', '33cc33', '66ff33', '00ff00', '66cc33', '006600', '003300',
         '009900', '33ff00', '66ff00', '99ff00', '66cc00', '00cc00', '33cc00', '339900',
         '99cc66', '669933', '99cc33', '336600', '669900', '99cc00', 'ccff66', 'ccff33',
         'ccff00', '999900', 'cccc00', 'cccc33', '333300', '666600', '999933', 'cccc66',
         '666633', '999966', 'cccc99', 'ffffcc', 'ffff99', 'ffff66', 'ffff33', 'ffff00',
         'ffcc00', 'ffcc66', 'ffcc33', 'cc9933', '996600', 'cc9900', 'ff9900', 'cc6600',
         '993300', 'cc6633', '663300', 'ff9966', 'ff6633', 'ff9933', 'ff6600', 'cc3300',
         '996633', '330000', '663333', '996666', 'cc9999', '993333', 'cc6666', 'ffcccc',
         'ff3333', 'cc3333', 'ff6666', '660000', '990000', 'cc0000', 'ff0000', 'ff3300',
         'cc9966', 'ffcc99', 'ffffff', 'cccccc', '999999', '666666', '333333'
         ];   */
        var a1colors = $.xcolor.analogous('#da0', 16);

        var b = $.xcolor.monochromatic("f00", 6);
        tt = $.xcolor.analogous('#da0', 32, 30);
        /*
         $.each(b, function(key, value){
         console.log("??" + value);
         
         });*/
        ////*
        var myRegExp = /#./;
        for (var i in tt) {
            t[i] = tt[i].toString();

            if (myRegExp.test(t[i])) {
                //console.log("matching #"+t[i]);
                t[i] = t[i].replace("#", "");
            }

        }/*
         dump(t);*/
        /*
         $.each(, function(key, value){
         console.log("??" + jQuery.type(value));
         // var t=[]; 
         //   var t=value.replace('11','');
         //   console.log("v=--"+value+"--");
         // t[key]=value.replace('#','');
         //allcolors[key]=value.substring(1);
         });*/
 $('#color').simpleColor({
    cellWidth: 15,
    cellHeight: 15,
    livePreview:true,
    colors:t,
    boxWidth:"100px",
    columns:8,
    
    callback: function(hex, element) {
      //  alert("color picked! " + hex + " for input #" + element.attr('class'));
    }
});


 var tfont = ["000000", 'FFFFFF'];

 $('#colorfont').simpleColor({
    cellWidth: 15,
    cellHeight: 15,
    livePreview:true,
    colors:t,
    boxWidth:"100px",
    columns:8,
    border:'1px solid #fff',
    
    callback: function(hex, element) {
      //  alert("color picked! " + hex + " for input #" + element.attr('class'));
    }
});



$('#colorfont').colorPicker({
                    colors: tfont,
                    pickerDefault: "FFFFFF",
                    transparency: true
                    });
        $('#color').colorPicker(
                {
                    colors: t,
                    /*  colors:  $.xcolor.monochromatic('#0066FF'),*/
                    /*  ["94A2BE","aaaaaa",'C0C0C0','808080','999999','333333',
                     
                     'CCFFFF', '99CCFF', "668cd9",'0000FF','333399','33CCCC', '3366FF',  
                     
                     'FF6600','FF0000', '666699',"f2a640",'800000',  '808000', 
                     
                     '008000', '008080',  'FF9900', '99CC00', '339966', "993355",
                     '800080', 'FF00FF', 'FFCC00',"b373b3",
                     'FFFF00', '00FF00', '00FFFF', '00CCFF', '993366', 'FF99CC',
                     'FFCC99',    'FFFF99', 'FFFFFF', '993300', '333300', '000080','000000'],*/
                    pickerDefault: "#94a2be",
                    /*pickerDefault: "#94a2be",*/
                    transparency: true,
                    onColorChange: function(id, newValue) {
                        console.log("ID: " + id + " has been changed to " + newValue);
                        /*   var colors=["#94a2be","#993355","#aaaaaa","#f2a640","#b373b3","#668cd9"];
                         alert(jQuery.inArray(newValue, colors));*/
                        /* alert("value=" + newValue + "array_value=" + getcolor(newValue));*/
                    }

                });
        function dump(obj) {
            var out = '';
            for (var i in obj) {
                out += i + ": " + obj[i] + "\n";
            }

            alert(out);
        }
        function getcolor(value) {
            /*  var colors=["#94a2be","#993355","#aaaaaa","#f2a640","#b373b3","#668cd9"];*/
            return jQuery.inArray(value, xcolors);
        }
        function getcolorById(id) {
            //  var colors=["#94a2be","#993355","#aaaaaa","#f2a640","#b373b3","#668cd9"];
            if (id <= 5)
                return xcolors[id];
            else
                return xcolors[0];
        }

        function reloadCal() {
            newSource[0] = '/feeds/calendarjson.ashx?e1=' + $('#e1').is(':checked') + '&e2=' + $('#e2').is(':checked');
            newSource[1] = $('#e3').is(':checked') ? '/feeds/caljson2.ashx' : '';
            $('#calendar-holder')
                    .fullCalendar('removeEventSource', source[0])
                    .fullCalendar('removeEventSource', source[1])
                    .fullCalendar('refetchEvents')
                    .fullCalendar('addEventSource', newSource[0])
                    .fullCalendar('addEventSource', newSource[1])
                    .fullCalendar('refetchEvents');
            source[0] = newSource[0];
            source[1] = newSource[1];
        }
        function LightenDarkenColor(col, amt) {

            var usePound = false;

            if (col[0] == "#") {
                col = col.slice(1);
                usePound = true;
            }

            var num = parseInt(col, 16);

            var r = (num >> 16) + amt;

            if (r > 255)
                r = 255;
            else if (r < 0)
                r = 0;

            var b = ((num >> 8) & 0x00FF) + amt;

            if (b > 255)
                b = 255;
            else if (b < 0)
                b = 0;

            var g = (num & 0x0000FF) + amt;

            if (g > 255)
                g = 255;
            else if (g < 0)
                g = 0;

            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);

        }
    });

