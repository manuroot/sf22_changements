  $(document).ready(function() {     
                    var view="week";          
                    //    $("#example").dialog({modal: true});
                    //calendar_ajax
                     var DATA_FEED_URL = Routing.generate('calendar_ajax');
                   /* var DATA_FEED_URL = "/uzf04new/wdcalendar/datafeed";*/
                    var op = {
                        view: view,
                        theme:3,
                        showday: new Date(),
                        EditCmdhandler:Edit,
                        DeleteCmdhandler:Delete,
                        ViewCmdhandler:View,    
                        onWeekOrMonthToDay:wtd,
                        onBeforeRequestData: cal_beforerequest,
                        onAfterRequestData: cal_afterrequest,
                        onRequestDataError: cal_onerror, 
                        autoload:true,
                        url: DATA_FEED_URL + "?method=list",  
                        quickAddUrl: DATA_FEED_URL + "?method=add", 
                        quickUpdateUrl: DATA_FEED_URL + "?method=update",
                        quickDeleteUrl: DATA_FEED_URL + "?method=remove"        
                    };
                  var $dv = $("#calhead");
                    var _MH = document.documentElement.clientHeight;
                    var dvH = $dv.height() + 2;
                    op.height = _MH - dvH;
                    op.eventItems =[];

                  var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }
                   $("#caltoolbar").noSelect();
            
                    $("#hdtxtshow").datepicker({ picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
                        onReturn:function(r){                          
                            var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
                            if (p && p.datestrshow) {
                                $("#txtdatetimeshow").text(p.datestrshow);
                            }
                        } 
                    });
                    function cal_beforerequest(type)
                    {
                        var t="Chargement des données...";
                        switch(type)
                        {
                            case 1:
                                t="Chargement des données...";
                                break;
                            case 2:                      
                            case 3:  
                            case 4:    
                                t="The request is being processed ...";                                   
                                break;
                        }
                        $("#errorpannel").hide();
                        $("#loadingpannel").html(t).show();    
                    }
                    function cal_afterrequest(type)
                    {
                        switch(type)
                        {
                            case 1:
                                $("#loadingpannel").hide();
                                break;
                            case 2:
                            case 3:
                            case 4:
                                //     alert("Success:type");
                                $("#loadingpannel").html("Success!");
                                window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
                                break;
                        }            
               
                    }
                    function cal_onerror(type,data)
                    {
                        $("#errorpannel").show();
                    }
                    function Edit(data)
                    {
                        // 
       
                        // var eurl="/uzf04new/wdcalendar/editwd";   
                        var eurl="/uzf04new/wdcalendar/editwd?id={0}&start={2}&end={3}&isallday={4}&title={1}";   
                        var myid=data[0];
                        var myhref="/uzf04new/changements/edit/id/" + myid;
         
                        //    var eurl="edit.php?id={0}&start={2}&end={3}&isallday={4}&title={1}";   
                        if(data)
                        {
                            //   $(location).attr('href',eurl);
                            var url = StrFormat(eurl,data);
                            //    $(location).attr('href',eurl);
                            $(location).attr('href',myhref);
                            //   $.post("/uzf04new/changements/edit/id/id={0}");
                            /* OpenModelWindow(url,{ width: 500, height: 600, caption:"Editer Evenement",onclose:function(){
                              $("#gridcontainer").reload();
                           }
                     });*/
                        }
                    }
            
                   

 
 function View(datab)
                    {
                        var str = "Informations sur l\'evenement<br>";
                        //    str +=parse(data);
                      /*  $.each(datab, function(i, item){
                            str += "[" + i + "]: " + item + "<br>";
                        });*/
                
                        var myid=datab[0];
                        var dataAjax = {
                            id:[myid]
                        };
                     
                
                        $.ajax({
                            url: '/uzf04new/changements/edit',
                            type: 'POST',
                            cache: false,
                            data: {id:myid},
                            success: function (data) {
                        
                         $.each(data, function(i, item){
                            str += "<strong>" + i + "</strong>:";
                            str +="<font color='red'>"  + item + "</font><br>";
                        });
                                $("#example").empty();
                                $("#example").append(str);
                                $("#example").dialog({
                                    width: 600,
                                    height:450,
                                    modal: true,
                 
                 
                                    buttons: {
                                        Ok: function() {
                                            $( this ).dialog( "close" );
                                        }
                                    }     
                                });
                            }
                        });
                    }
                    
                    
                    
function Delete(data,callback)
                {           
                
                    $.alerts.okButton="Ok";  
                    $.alerts.cancelButton="Cancel";  
                    hiConfirm("Etes vous sur de vouloir supprimer cet évènement", 'Confirm',function(r){ r && callback(0);});           
                }
    function wtd(p)
                {
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }
                    $("#caltoolbar div.fcurrent").each(function() {
                        $(this).removeClass("fcurrent");
                    })
                    $("#showdaybtn").addClass("fcurrent");
                }
                //to show day view
$("#showdaybtn").click(function(e) {
                    //document.location.href="#day";
                    $("#caltoolbar div.fcurrent").each(function() {
                        $(this).removeClass("fcurrent");
                    })
                    $(this).addClass("fcurrent");
                    var p = $("#gridcontainer").swtichView("day").BcalGetOp();
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }
                });
                
                
                //to show week view
                $("#showweekbtn").click(function(e) {
                    //document.location.href="#week";
                    $("#caltoolbar div.fcurrent").each(function() {
                        $(this).removeClass("fcurrent");
                    })
                    $(this).addClass("fcurrent");
                    var p = $("#gridcontainer").swtichView("week").BcalGetOp();
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }

                });
                //to show month view
                $("#showmonthbtn").click(function(e) {
                    //document.location.href="#month";
                    $("#caltoolbar div.fcurrent").each(function() {
                        $(this).removeClass("fcurrent");
                    })
                    $(this).addClass("fcurrent");
                    var p = $("#gridcontainer").swtichView("month").BcalGetOp();
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }
                });
            
                $("#showreflashbtn").click(function(e){
                    $("#gridcontainer").reload();
                });
            
                //Add a new event
                $("#faddbtn").click(function(e) {
                    var url ="/uzf04new/wdcalendar/editwd";
                    //var url ="edit.php";
                    OpenModelWindow(url,{ width: 500, height: 500, caption: "Créer Evenement"});
                });
                //go to today
                $("#showtodaybtn").click(function(e) {
                    var p = $("#gridcontainer").gotoDate().BcalGetOp();
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }


                });
                //previous date range
                $("#sfprevbtn").click(function(e) {
                    var p = $("#gridcontainer").previousRange().BcalGetOp();
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }

                });
                //next date range
                $("#sfnextbtn").click(function(e) {
                    var p = $("#gridcontainer").nextRange().BcalGetOp();
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }
                });
            
});


