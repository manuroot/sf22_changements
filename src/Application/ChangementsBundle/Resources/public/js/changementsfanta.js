$(document).ready(function() {
    /*passer en parametre inactivié session*/
      var userid = "{{ user.username|escape('js') }}";
      /*var test = {{ testArray|json_encode|raw }};
       var test = {{ testArray|json_encode|raw }};*/
    var img_path = '/bundles/applicationchangements/images/';
    var img_s_path = 'bundles/applicationchangements/images/';

    $.fn.extend({
        hasClasses: function(selectors) {
            var self = this;
            for (i in selectors) {
                if ($(self).hasClass(selectors[i]))
                    return true;
            }
            return false;
        }
    });

    $("a.editme").colorbox({
            iframe:true,
            transition:	"elastic",
            width:"70%",
            height:"70%",
              fastIframe:false,
            opacity:0.3
        
        });

    $("#mytitleb").popover({
        html: true,
        delay: {show: 300, hide: 300},
        placement: 'bottom',
        trigger: 'hover',
        title: function() {
            return $("#popover-head").html();
        },
        content: function() {

            return $("#popover-content").html();
        }
    });


    $("a.favoris").popover({delay: {show: 300, hide: 300}, placement: 'bottom', trigger: 'hover'});
    $("a.tooltip_comments,a.tooltip_edit,a.tooltip_show").popover(
            {html: true, delay: {show: 300, hide: 300}, placement: 'left', trigger: 'hover'}
    );
           $("th.thinfos > a.mytooltip").popover(
            {html: true, delay: {show: 300, hide: 1000}, placement: 'top', trigger: 'hover'}
    );

    /*========================================================
     *  Ajout de favoris
     ========================================================*/

    $("td > a.favoris").click(function(event) {

        var url_login = Routing.generate('fos_user_security_login');
        var dataAjax = {from: 'favoris'};
        /*ca a l'air de bugger  !! */
        var chck = checkuser(dataAjax);
           if (chck === false) {
         window.location.replace(url_login);
        }
        else {
            /*  console.log("grgfdgf");*/
            /*    alert("alredy logged in");*/
            /*  console.log("grgfdgf");*/
            if ($(this).hasClass("favoris")) {
                var id = $(this).attr("data-id");
                /*$(this).closest("tr>td").data().css( "color", "red" );*/
                var name = $(this).attr("data-name");
                var message = "";
                var new_status = 0;
                var img_favori = "";
                var modal_res = "";
                var obj = $(this);
                var status = $(this).attr("data-status");
                console.log("favoris id=" + id + "status=" + status);
                if (status == 1) {
                    message = "Supprimer de ";
                    img_favori = "star-off.png";

                }

                else {
                    message = "Ajouter a ";
                    img_favori = "star-on.png";
                    new_status = 1;
                }
                var mess = "<i class='icon-wrench icon-2x'></i><br><h3>" + message + " vos favoris: </h3><p>status=" + status + "</p><p>id=" + id + "</p><p>nom=" + name + "</p>";
                bootbox.confirm(mess, function(checkstr) {
                    console.log("confirm result=" + checkstr);
                    /*Example.show("Confirm result: "+result);*/


                    // var checkstr = confirm(message + " vos favoris: \nstatus=" + status + "\nid=" + id + "\nnom=" + name);
                    if (checkstr === true) {
                        /*$(this).data('data-status',new_status);*/
                        var dataAjax = {id: id};

                        changerfavoris(dataAjax, obj);
                    }

                });
                return true;
            }
            /* pas de class favoris*/
            else {
                return false;
            }
        }
        /*});*/

    });

    /*========================================================
     *  Changement de status
     ========================================================*/
    /*
     $('.show-details').popover({
     placement: function(tip, ele) {
     var width = $(window).width();
     return width >= 975 ? 'left' : ( width < 600 ? 'top' : 'right' );
     }
     });
     */
    $("td > a.okstatus").click(function(event) {
        /* * A modifier: change color only sur success !!*/
        var url_login = Routing.generate('fos_user_security_logout');
        var datax = {from: 'favoris'};
        var chck = checkuser(datax);
           if (chck === false) {
         window.location.replace(url_login);
        }
        else {
        if ($(this).hasClasses(['open', 'closed', 'prepare'])) {
            /*if ($(this).hasClass("open") || $(this).hasClass("closed") || $(this).hasClass("prepare")) {*/
            var id = $(this).attr("data-id");
            var name = $(this).attr("data-name");
            var obj = $(this);
            origin_mess = "<i class='icon-wrench icon-2x'></i><br><h3>Modifier le status de la demande ?</h3>";
            var mess = origin_mess + "<p>id=" + id + "</p><p>nom=" + name + "</p>";

            
            bootbox.confirm(mess, function(checkstr) {
                console.log("confirm result=" + checkstr);

                // var checkstr = confirm("Modifier le status de la demande: \nid=" + id + "\nnom=" + name);
                if (checkstr === true) {
                    console.log("id=" + id);
                    dataAjax = {id: id};

                    remplirSelect(dataAjax, obj);
                }
            });
            return true;
        }
    }

    });

    function removeTableRow(trId) {
        $('tr#' + trId).remove();
    }
    
 
  /*
    setInterval(function()
{
  
    $.ajax({
      type:"POST",
       url: Routing.generate('ajax_checkuser'),
      datatype:"html",
      success:function(data)
      {
      var url_login = Routing.generate('fos_user_security_logout');
     
        if (data.status === false) {window.location.replace(url_login);}
            },
             error: function(e) {
            myVar=false;
             }
        });
  
        }, 300000);
 */
    function checkuser(dataAjax) {
                        
        $.ajax({
            data: dataAjax,
            url: Routing.generate('ajax_checkuser'),
            async: false,
            type: "POST",
            cache: false,
            success: function(data)
            {
                myVar = data.status;
            },
             error: function(e) {
            myVar=false;
             }
        });
        return myVar;
    }
    /*========================================================
     *  Fonction: ajout au favoris
     ========================================================*/

    function changerfavoris(dataAjax, obj) {
        $.ajax({
            url: Routing.generate('changements_updatexhtml_favoris'),
            /*  url: "{{ path('changements_updatexhtml_changement') }}", */
            type: "POST",
            data: dataAjax,
            dataType: "json",
            success: function(reponse) {
                var img_favori = "";
                var status = obj.attr("data-status");
                var id = obj.attr("data-id");
                console.log("before id =" + id + " status = " + status);
                if (status == 1) {
                    message = "Enregistrement: " + id + " supprimé de vos favoris";
                    img_favori = "star-off.png";
                    new_status = 0;

                }

                else {
                    message = "Enregistrement: " + id + " ajouté de vos favoris";
                    img_favori = "star-on.png";
                    new_status = 1;
                }
                if (reponse['mystatus'] === "removed") {
                    img_favori = "star-off.png";
                    //$(this).closest("tr").parent().find("id^=id");
                    // var mytr = $("div#matable").find("tr#" + id);
                    var mytr = obj.closest("tr#" + id);
                    // var mytr=obj.closest("tr").parent();
                    // var id_parent=mytr.attr("id");

                    /*if (mytr.attr("id") === id) {*/
                    // $("tr#" + id).remove();
                    // mytr.remove();

                    mytr.fadeTo(600, 0, function() {
                        $(this).remove();
                    });
                    // $("#mytable tr:eq(id)").remove();*/

                    console.log("remove tr id=" + id);

                    // }
                }
                else {
                    img_favori = "star-on.png";
                }

                console.log("reponse:" + reponse['mystatus']);
                console.log("src=" + img_path + img_favori);
                obj.children().attr("src", img_path + img_favori);
                obj.attr('data-status', new_status);
                $.pnotify({
                    title: 'Modification Favoris',
                    text: message,
                    animation: 'show',
                    nonblock_opacity: 0.2,
                    type: 'success',
                    icon: 'icon-flag',
                    width: '350px',
                    opacity: .9
                });


            },
            error: function(e) {
                console.log(e.message);
            }
        });  //Eof:: ajax 
//return;
    } //Eof:: fucntion remplirSelect

    /*========================================================
     *  Fonction: Modif du status
     ========================================================*/

    function remplirSelect(dataAjax, obj) {
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

                var status = "";

                var id = obj.attr("data-id");
                var txtid = "id=" + id + " ";
                if (obj.hasClass("open")) {
                    obj.removeClass("open").addClass("closed");
                    obj.closest("tr").removeClass("success").addClass("myclosed");
                    obj.children().attr("src", img_path + "cadenas-sferme.png");
                    status = "open ==> closed";
                }
                else if (obj.hasClass("prepare")) {
                    obj.children().attr("src", img_path + "cadenas-souvert.png");
                    obj.removeClass("prepare").addClass("open");
                    obj.closest("tr").removeClass("prepare").addClass("success");
                    status = "prepare ==> open";
                }
                //cas closed: closed ==> prepare
                else if (obj.hasClass("closed")) {
                    /*   console.log("closed test button");*/
                    obj.children().attr("src", img_path + "cadenas-sbleu.png");
                    obj.removeClass("closed").addClass("prepare");
                    obj.closest("tr").removeClass("myclosed").addClass("prepare");
                    /*.addClass("prepare");*/
                    status = "closed ==> prepare";
                }
                ;
                var message = txtid + status;
                $.pnotify({
                    title: 'Modification Status',
                    text: message,
                    animation: 'show',
                    nonblock_opacity: 0.2,
                    type: 'success',
                    icon: 'icon-flag',
                    width: '350px',
                    opacity: .9
                });

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

    /*========================================================
     *  Calendrier
     ========================================================*/

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
        onSelect: postmonth,
        /*onSelect: function(dateText, inst) {
         alert(dateText);
         },*/
        /* onSelect: function(dateText, inst) {
         var url = Routing.generate('changements_fanta');
         window.location.href = url + '/' + dateText;
         
         },*/
        onChangeMonthYear: fetchEvents
                /* create: function(event, ui) {
                 fetchEvents()
                 },*/
                /*  onLoad:fetchEvents,*/

    });

    function postmonth(date) {

        /*alert('Form is submitting');*/
        //$("#changements_searchfilter_dateDebut").html(date.toString());
        $("input#changements_searchfilter_dateDebut").val(date);
        $("input#changements_searchfilter_dateDebut_max").val(date);
        $("button#filter").trigger("click");
        /*  $(".form").submit();*/

        /*var dataAjax = {
         'year': year,
         'month': month
         };*/

        /* $.ajax({
         async: false,
         url: Routing.generate('changements_fanta'),
         
         type: "POST",
         dataType: "json",
         data: date
         });*/
    }

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
                /*a class="ui-state-default" href="#">
                 3
                 </a>*/
            }
        }
        return [true];
    }

    /*========================================================
     *  Fonctions: select2 pour formulaire
     ========================================================*/

    function formatsmall(state) {
        if (!state.id)
            return state.text; // optgroup
        return "<img class='flag' src='" + img_s_path + state.id.toLowerCase() + ".png'/> " + state.text;
    }
    function format(state) {
        if (!state.id)
            return state.text; // optgroup
        return "<img width='10px' class='flag' src='" + img_s_path + state.id.toLowerCase() + ".png'/> " + state.text;
    }
$("#searchstatus_idStatus").select2({
        placeholder: "-- Statut(s) --",
        allowClear: true,
        formatResult: formatsmall,
        formatSelection: formatsmall,
        escapeMarkup: function(m) {
            return m;
        }
    });

$("#searchstatus_idStatus").click(function() {
        var myval=$("#searchstatus_idStatus").val();
        if (myval == null) {
            $("#changements_searchfilter_idStatus").select2("val","");
        }
        else {
       $("#changements_searchfilter_idStatus").select2("val", myval);
     /*  myval.forEach(function(entry) {
        console.log("val idstatus="+ entry);  
       
    });*/
        }
        
    });
    
    $("#changements_searchfilter_idStatus").select2({
        placeholder: "-- Statut(s) --",
        allowClear: true,
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) {
            return m;
        }
    });


    $('.alert').each(function() {
        var html = $(this).html();
        $.pnotify({
            title: 'Flash Message',
            text: html,
            animation: 'show',
            nonblock_opacity: 0.2,
            type: 'success',
            icon: 'icon-flag',
            width: '350px',
            opacity: .9
        });
    });

    $("#changements_searchfilter_idStatus").click(function() {
        var myval=$("#changements_searchfilter_idStatus").val();
        if (myval == null) {
            $("#searchstatus_idStatus").select2("val","");
        }else {
       $("#searchstatus_idStatus").select2("val", myval);
      /* myval.forEach(function(entry) {
        console.log("val idstatus="+ entry);  
      
    });*/
        }
   
    });
    
    /*  $("#changements_searchfilter_idStatus").select2("val", 1);*/
  /*   $("#searchstatus_1").click(function() {
if($('#searchstatus_1').is(':checked')){
      $("#changements_searchfilter_idStatus").select2("val", 1);
    }
    else { 
        $("#changements_searchfilter_idStatus").removeselect2("val", ""); }
        $("#selectBox option[value='option1']").remove();
        $("#changements_searchfilter_idStatus").select2("val", ""); }
     });*/
/* cl: clear function (not used) */

    $("#changements_searchfilter_idStatus_cl").click(function() {
        $("#changements_searchfilter_idStatus").select2("val", "");
        var myval=$("#changements_searchfilter_idStatus").val();
        console.log("val idstatus="+ myval);
      /* $("#searchstatus_0").prop("checked",true)*/
    });


  $("#changements_searchfilter_idProjet").select2({
        placeholder: "-- Choisir Projet(s) --",
        allowClear: true
    });
 

   $("#changements_searchfilter_idProjet_cl").click(function() {
        console.log("val idstatus=");
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
    $("#changements_searchfilter_idKind").select2();
    /*
     //if submit button is clicked
     $('#submit').click(function () {        
     
     //Get the data from all the fields
     var name = $('input[name=name]');
     var email = $('input[name=email]');
     var website = $('input[name=website]');
     var comment = $('textarea[name=comment]');
     //Simple validation to make sure user entered something
     //If error found, add hightlight class to the text field
     if (name.val()=='') {
     name.addClass('hightlight');
     return false;
     } else name.removeClass('hightlight');
     
     if (email.val()=='') {
     email.addClass('hightlight');
     return false;
     } else email.removeClass('hightlight');
     
     if (comment.val()=='') {
     comment.addClass('hightlight');
     return false;
     } else comment.removeClass('hightlight');
     
     //organize the data properly
     var data = 'name=' + name.val() + '&email=' + email.val() + '&website='
     + website.val() + '&comment=' + encodeURIComponent(comment.val());
     
     //disabled all the text fields
     $('.text').attr('disabled','true');
     
     //show the loading sign
     $('.loading').show();
     
     //start the ajax
     $.ajax({
     //this is the php file that processes the data and send mail
     url: "process.php",    
     
     //GET method is used
     type: "GET",
     //pass the data            
     data: data,        
     
     //Do not cache the page
     cache: false,
     
     //success
     success: function (html) {                
     //if process.php returned 1/true (send mail success)
     if (html==1) {                    
     //hide the form
     $('.form').fadeOut('slow');                    
     
     //show the success message
     $('.done').fadeIn('slow');
     
     //if process.php returned 0/false (send mail failed)
     } else alert('Sorry, unexpected error. Please try again later.');                
     }        
     });
     
     //cancel the submit button default behaviours
     return false;
     });     
     
     */
}); //Eof:: ready